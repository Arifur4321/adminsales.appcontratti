<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Contract;  
use App\Models\VariableList; 
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\SalesDetails; 

 

class ContractController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    
    public function getActiveSalesCount($userId)
    {
        $user = User::find($userId);
        
        if ($user && $user->company) {
            $activeCount = SalesDetails::where('company_id', $user->company_id)
                                       ->where('status', 'active')
                                       ->count();
            return response()->json(['success' => true, 'activeCount' => $activeCount]);
        }
        
        return response()->json(['success' => false, 'message' => 'No active sales found.']);
    }
    


   // for gtting all similar  sales 
    public function getSalesDetails($userId)
    {
        // Fetch sales details associated with the company of the given user
        $user = User::find($userId);
    
        if ($user && $user->company) {
            $salesDetails = SalesDetails::where('company_id', $user->company_id)->get();
            return response()->json(['success' => true, 'salesDetails' => $salesDetails]);
        }
    
        return response()->json(['success' => false, 'message' => 'No sales details found.']);
    }
    
    

    public function activateSales(Request $request)
    {
        // Find the sales detail by email
        $salesDetail = SalesDetails::where('email', $request->email)->first();

        if ($salesDetail) {
            // Get the user's company
            $company = $salesDetail->company;

            if ($company) {
                // Count active salespersons for this company
                $activeSalesCount = SalesDetails::where('company_id', $company->id)
                                                ->where('status', 'active')
                                                ->count();

                // Check if the active count exceeds the limit
                if ($activeSalesCount >= $company->NumOfsales) {
                    return response()->json([
                        'success' => false,
                        'message' => "Your limit is {$company->NumOfsales}. You can't activate more salespersons."
                    ]);
                }

                // Activate the salesperson if the limit is not exceeded
                $salesDetail->status = 'active';
                $salesDetail->save();

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Sales detail not found']);
    }

    // public function activateSales(Request $request)
    // {
    //     // Find the sales detail by email
    //     $salesDetail = SalesDetails::where('email', $request->email)->first();
        
    //     if ($salesDetail) {
    //         // Update the status to 'active'
    //         $salesDetail->status = 'active';
    //         $salesDetail->save();
    //         return response()->json(['success' => true]);
    //     }
        
    //     return response()->json(['success' => false, 'message' => 'Sales detail not found']);
    // }
    
    public function deactivateSales(Request $request)
    {
        // Find the sales detail by email
        $salesDetail = SalesDetails::where('email', $request->email)->first();
        
        if ($salesDetail) {
            // Update the status to 'inactive'
            $salesDetail->status = 'inactive';
            $salesDetail->save();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Sales detail not found']);
    }
    

    public function index(Request $request)
    {
        // Fetch users with related company names and max number of sales (NumOfsales)
        $users = User::with('company')->get(); // Use Eloquent relationship

        // Fetch number of sales for each company
        foreach ($users as $user) {
            $user->numberOfSales = SalesDetails::where('company_id', $user->company_id)->count();
        }

        // Pass the users to the view
        return view('AdminList', compact('users'));
    }

    public function updateMaxSales(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'max_sales' => 'required|integer|min:1'
        ]);

        // Find the company and update the NumOfsales
        $company = Company::find($id);
        $company->NumOfsales = $request->max_sales;
        $company->save();

        // Return a JSON response
        return response()->json(['success' => true, 'message' => 'Max sales number updated successfully.']);
    }

    // delete from main company user table
    public function deleteUser($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    }

    //for activate main user
    // public function activateUser($id)
    // {
    //     $user = User::find($id);
    //     if ($user) {
    //         $user->status = 'active'; // Set status to active
    //         $user->save();
    //         return response()->json(['success' => true, 'message' => 'User activated successfully!']);
    //     }
    //     return response()->json(['success' => false, 'message' => 'User not found!']);
    // }

    // // Method to deactivate the user
    // public function deactivateUser($id)
    // {
    //     $user = User::find($id);
    //     if ($user) {
    //         $user->status = 'disactive'; // Set status to disactive
    //         $user->save();
    //         return response()->json(['success' => true, 'message' => 'User deactivated successfully!']);
    //     }
    //     return response()->json(['success' => false, 'message' => 'User not found!']);
    // }
// Method to activate the user
    public function activateUser($id)
    {
        $user = User::find($id);
        if ($user) {
            // Activate the user
            $user->status = 'active'; // Set status to active
            $user->save();

            // Also activate all related sales details with the same company_id
            SalesDetails::where('company_id', $user->company_id)
                        ->update(['status' => 'active']);

            return response()->json(['success' => true, 'message' => 'User and related sales details activated successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'User not found!']);
    }


    // Method to deactivate the user
public function deactivateUser($id)
{
    $user = User::find($id);
    if ($user) {
        // Deactivate the user
        $user->status = 'disactive'; // Set status to disactive
        $user->save();

        // Also deactivate all related sales details with the same company_id
        SalesDetails::where('company_id', $user->company_id)
                    ->update(['status' => 'inactive']);

        return response()->json(['success' => true, 'message' => 'User and related sales details deactivated successfully!']);
    }
    return response()->json(['success' => false, 'message' => 'User not found!']);
}

    


    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        
        if ($user) {
            // Update user fields
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            
            // Update password if provided
            $newPassword = $request->input('password');
            if ($newPassword) {
                $user->password = Hash::make($newPassword);
            }
            
            // Update company's max sales
            $company = $user->company;
            if ($company && $request->has('max_sales')) {
                $company->NumOfsales = $request->input('max_sales');
                $company->save();
            }
    
            $user->save();
    
            // Send updated email with the new information
            $this->sendUpdateNotification($user, $newPassword);
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'User not found']);
    }
    
    protected function sendUpdateNotification($user, $newPassword)
    {
        // Calculate the number of sales for the user's company
        $user->numberOfSales = SalesDetails::where('company_id', $user->company_id)->count();
    
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $newPassword,
            'user' => $user, // Pass the user object with calculated numberOfSales
        ];
    
        Mail::send('emails.user-updated', $data, function ($message) use ($user) {
            $message->to($user->email)->subject('Your Account Information Has Been Updated');
        });
    }
    

    // public function updateUser(Request $request, $id)
    // {
    //     $user = User::find($id);

    //     if ($user) {
    //         // Update user fields
    //         $user->name = $request->input('name');
    //         $user->email = $request->input('email');

    //         // Update password only if a new one is provided
    //         $newPassword = $request->input('password');
    //         if ($newPassword) {
    //             $user->password = Hash::make($newPassword);
    //         }

    //         $user->save();

    //         // Send email with updated information
    //         $this->sendUpdateNotification($user, $newPassword);

    //         return response()->json(['success' => true]);
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'User not found']);
    //     }
    // }

    // protected function sendUpdateNotification($user, $newPassword)
    // {
    //     $data = [
    //         'name' => $user->name,
    //         'email' => $user->email,
    //         'password' => $newPassword
    //     ];

    //     Mail::send('emails.user-updated', $data, function ($message) use ($user) {
    //         $message->to($user->email)->subject('Your Account Information Has Been Updated');
    //     });
    // }

   


    public function createContractWithUpdatePage()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Create a new contract with only the ID incremented
        $contract = new Contract();
        $contract->contract_name = "Write your contract name"; // Set the default value for contract_name
        $contract->company_id = $user->company_id; // Set the company_id from the authenticated user
        $contract->save();

        // Get the ID of the newly created contract
        $contractId = $contract->id;

        // Redirect to the edit-contract-list page with the new contract ID
        echo '<script>window.location.href = "/edit-contract-list/' . $contractId . '";</script>';
    }


    public function destroy($id)
    {
        $contract = Contract::find($id);
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }
        
        $contract->delete();
        
        return redirect()->back()->with('success', 'Contract deleted successfully.');
    }
 
    

    public function show()
    {
      
        $variables = VariableList::all();
        return view('ContractList', compact('variables'));
    }
    
     
    

    public function savecontract(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'contract_name' => 'required|string',
            
            'editor_content' => 'required|string',
        ]);
    
        // Create a new Contract instance
        $contract = new Contract();
        $contract->contract_name = $request->input('contract_name');
  
        $contract->editor_content = $request->input('editor_content');
        $contract->logged_in_user_name = auth()->user()->name; // Assuming you have authentication
        //$contract->last_update_name = auth()->user()->name; // Assuming you have authentication
        $contract->save(); // Save the contract data
    
        // You can return a response to the client if needed
        return response()->json(['message' => 'Contract saved successfully']);

     
    }

   // main upload method for photo without  size limitation 
    public function upload(Request $request)
    {
       if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('media'), $fileName);
            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }




  

   // for update the contract 
    public function updatecontract(Request $request)
    {
        // Validation
        $request->validate([
            'id' => 'required|exists:contracts,id',
            'contract_name' => 'required|string',
            'editor_content' => 'required|string',
            // Add more validation rules as needed
        ]);
    
        // Find the contract by ID
        $contract = Contract::findOrFail($request->input('id'));
    
        // Update contract details
        $contract->update([
            'contract_name' => $request->input('contract_name'),
            'editor_content' => $request->input('editor_content'),
            // Include other fields if needed
        ]);
    
        // Return a JSON response indicating success
        return response()->json(['message' => 'Contract updated successfully'], 200);
    }
    

    // for edit 
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);

        return view('contracts.edit-modal', compact('contract'));
    }

 

}

