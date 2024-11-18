<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SalesDetails; 
use App\Models\ProductToSales;
use App\Mail\SalesDetailsMail;
use Illuminate\Support\Facades\Mail;
use App\Models\SalesListDraft;

use Illuminate\Support\Facades\Auth;

 
class SalesDetailsController extends Controller

{

    public function save(Request $request, $id = null)
    {
        $user = Auth::user();
    
        if ($id) {
            $salesDetails = SalesDetails::findOrFail($id);
    
            // Check if the nickname is changing and ensure uniqueness within the same company
            if ($request->nickname !== $salesDetails->nickname) {
                if (SalesDetails::where('nickname', $request->nickname)
                    ->where('company_id', $user->company_id)
                    ->exists()) {
                    return redirect()->back()->withErrors(['nickname' => 'Nickname already exists within your company.'])->withInput();
                }
            }
    
            // Check if the name is changing and ensure uniqueness within the same company
            if ($request->name !== $salesDetails->name) {
                if (SalesDetails::where('name', $request->name)
                    ->where('company_id', $user->company_id)
                    ->exists()) {
                    return redirect()->back()->withErrors(['name' => 'Name already exists within your company.'])->withInput();
                }
            }
    
            // Check if the email is changing and ensure uniqueness globally
            if ($request->email !== $salesDetails->email) {
                if (SalesDetails::where('email', $request->email)->exists()) {
                    return redirect()->back()->withErrors(['email' => 'Email already exists.'])->withInput();
                }
            }
    
            // Update the sales details
            $salesDetails->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'nickname' => $request->nickname,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password,
                'description' => $request->description,
                'company_id' => $user->company_id,
            ]);
        } else {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'nickname' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|max:255',
                'description' => 'required|string',
                'company_id' => $user->company_id,
            ]);
    
            // Ensure nickname and name uniqueness within the company during creation
            if (SalesDetails::where('nickname', $validatedData['nickname'])
                ->where('company_id', $user->company_id)
                ->exists()) {
                return redirect()->back()->withErrors(['nickname' => 'Nickname already exists within your company.'])->withInput();
            }
    
            if (SalesDetails::where('name', $validatedData['name'])
                ->where('company_id', $user->company_id)
                ->exists()) {
                return redirect()->back()->withErrors(['name' => 'Name already exists within your company.'])->withInput();
            }
    
            // Create new sales details
            $salesDetails = SalesDetails::create($validatedData);
        }
    
        // Send the email
        Mail::to($salesDetails->email)->send(new SalesDetailsMail($salesDetails->name, $salesDetails->email, $request->password));
    
        $request->session()->flash('success', 'Sales details saved successfully.');
    
        return redirect()->back();
    }
    



    // public function save(Request $request, $id = null)
    // {

    //     $user = Auth::user();

       

    //     if ($id) {
    //         $salesDetails = SalesDetails::findOrFail($id);

    //         if ($request->nickname !== $salesDetails->nickname) {
    //             if (SalesDetails::where('nickname', $request->nickname)->exists()) {
    //                 return redirect()->back()->withErrors(['nickname' => 'Nickname already exists.'])->withInput();
    //             }
    //         }

           
            

    //         if ($request->name !== $salesDetails->name) {
    //             if (SalesDetails::where('name', $request->name)->exists()) {
    //                 return redirect()->back()->withErrors(['name' => 'name already exists in your company.'])->withInput();
    //             }
    //         }

         
    

    //         if ($request->email !== $salesDetails->email) {
    //             if (SalesDetails::where('email', $request->email)->exists()) {
    //                 return redirect()->back()->withErrors(['email' => 'Email already exists.'])->withInput();
    //             }
    //         }

    //         $salesDetails->update([
    //             'name' => $request->name,
    //             'surname' => $request->surname,
    //             'nickname' => $request->nickname,
    //             'phone' => $request->phone,
    //             'email' => $request->email,
    //             'password' => $request->password,
    //             'description' => $request->description,

    //             'company_id' => $user->company_id,
    //         ]);
    //     } else {
    //         $validatedData = $request->validate([
    //             'name' => 'required|string|max:255',
    //             'surname' => 'required|string|max:255',
    //             'nickname' => 'required|string|max:255|unique:sales_details',
    //             'phone' => 'required|string|max:255|unique:sales_details',
    //             'email' => 'required|string|email|max:255|unique:sales_details',
    //             'password' => 'required|string|max:255',
    //             'description' => 'required|string',

    //             'company_id' => $user->company_id,


    //         ]);

    //         $salesDetails = SalesDetails::create($validatedData);
    //     }

    //     // Send the email
    //     Mail::to($salesDetails->email)->send(new SalesDetailsMail($salesDetails->name, $salesDetails->email, $request->password));

    //     $request->session()->flash('success', 'Sales details saved successfully.');

    //     return redirect()->back();
    // }

  



        public function checkUnique(Request $request)
        {
            // Retrieve field and value from the request
            $field = $request->input('field');
            $value = $request->input('value');

            // Check uniqueness based on the field
            switch ($field) {
               case 'nickname':
                   $exists = SalesDetails::where('nickname', $value)->exists();
                    break;
                case 'name':
      
                   $exists = false; // Passwords should not be checked for uniqueness
                    break;
               // case 'phone':
                //    $exists = SalesDetails::where('phone', $value)->exists();
                 //   break;
                case 'email':
                    $exists = SalesDetails::where('email', $value)->exists();
                    break;
                default:
                    $exists = false;
                    break;
            }

            // Return JSON response indicating uniqueness
            return response()->json(['unique' => !$exists]);
        }


        // public function displayChecked(Request $request)
        // {
            
        //      $products = Product::all();
        //     $salesId = $request->input('salesDetailsId');
    
           
        //     foreach ($products as $product) {
        //         $product->isSelected = ProductToSales::where('product_id', $product->id)
        //                                             ->where('sales_id', $salesId)
        //                                             ->exists();
        //     }
        //     return response()->json(['products' => $products]);
        // }
     
        
            public function displayChecked(Request $request)
            {
                // Get the authenticated user
                $user = Auth::user();
        
                // Fetch products where company_id matches the authenticated user's company_id
                $products = Product::where('company_id', $user->company_id)->get();
        
                // Get the sales ID from the request
                $salesId = $request->input('salesDetailsId');
        
                // Loop through each product and check if it's selected
                foreach ($products as $product) {
                    $product->isSelected = ProductToSales::where('product_id', $product->id)
                                                        ->where('sales_id', $salesId)
                                                        ->exists();
                }
        
                // Return JSON data
                return response()->json(['products' => $products]);
            }
   




        public function updateProductStatus(Request $request)
        {
            // Validate incoming request data if needed
    
            $productId = $request->input('product_id');
            $salesDetailsId = $request->input('sales_details_id');
            $isChecked = $request->input('is_checked');
    
            // If checkbox is checked, add the product to the database
            if ($isChecked) {
                ProductToSales::create([
                    'product_id' => $productId,
                    'sales_id' => $salesDetailsId,
                ]);
            } else { // If checkbox is unchecked, remove the product from the database
                ProductToSales::where('product_id', $productId)
                              ->where('sales_id', $salesDetailsId)
                              ->delete();
            }
    
            // You can return a response to indicate success or failure
            return response()->json(['message' => 'Product status updated successfully']);
        }
    
    //for manytomany table
    public function saveProductToSales(Request $request)
    {
        // Retrieve sales ID from the request or session, assuming you have it available
        $salesId = $request->input('sales_details_id');

        // Retrieve product IDs from the request
        $productIds = $request->input('product_ids');

        // Loop through each product ID and save it to product_to_sales table
        foreach ($productIds as $productId) {
            ProductToSales::create([
                'product_id' => $productId,
                'sales_id' => $salesId,
            ]);
        }

        // You can return a response to indicate success or failure
        return response()->json(['message' => 'Products saved to sales successfully']);
    }
    
    public function editSales($id)
    {
        $user = Auth::user();
        
        // Fetch products where company_id matches the authenticated user's company_id
        $products = Product::where('company_id', $user->company_id)->get();

        //$products = Product::all(); // Fetch all products from the database
        
        $salesDetails = SalesDetails::findOrFail($id);  //  fetch all sales details data from database  
        return view('Sales-Details', compact('products','salesDetails'));
    }
 

    
    public function Productshow()
    {
        $products = Product::all(); // Fetch all products from the database
       // $salesDetails = SalesDetails::all();  //  fetch all sales details data from database  

        $salesDetails = new  salesDetails();
        $salesDetails -> name = "Write your Name";
        $salesDetails -> surname = "Write your SurName";
        $salesDetails -> save();
        $salesDetailsid =  $salesDetails->id;
        
       // return view('Sales-Details', compact('products','salesDetails')); 
       echo '<script>window.location.href = "/Sales-Details/' . $salesDetailsid . '";</script>';
    }
 
  
        public function show()
        {
            // Get the authenticated user
            $user = Auth::user();
            
        // $salesDetails = SalesDetails::all(); // Fetch all sales details from the database

            $salesDetails = SalesDetails::where('company_id', $user->company_id)->get();
            
            return view('Sales-Lists', compact('salesDetails'));
        
        }

        public function destroy($id)
        {
            // Find and delete the SalesDetails record
            $SalesDetails = SalesDetails::findOrFail($id);
            $SalesDetails->delete();

            // Find and delete the SalesListDraft record(s) with the matching sales_id
            $SalesListDraft = SalesListDraft::where('sales_id', $id);
            $SalesListDraft->delete();

            return response()->json(['message' => 'Price list and draft entry deleted successfully']);
        }

 
         
}
