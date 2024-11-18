<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\VariableList; 
use App\Models\Product;
use App\Models\HeaderAndFooter;
use App\Models\contractvariablecheckbox; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
//For the pricelist table
use App\Models\PriceList;
 
use App\Models\HeaderAndFooterContractpage;

class EditContractListController extends Controller
{

    
 
    public function insertMandatoryStatus(Request $request)
    {
        $contractId = $request->input('contractId');
        $variableId = $request->input('variable_id');
        $mandatory = $request->input('mandatory');

        // Find the record by contractId and variableId
        $record = ContractVariableCheckbox::where('ContractID', $contractId)
                                        ->where('VariableID', $variableId)
                                        ->first();

        // If the record exists, update the mandatory field, else create a new record
        if ($record) {
            $record->Mandatory = $mandatory;
            $record->save();
        } else {
            $newRecord = new ContractVariableCheckbox();
            $newRecord->ContractID = $contractId;
            $newRecord->VariableID = $variableId;
            $newRecord->Mandatory = $mandatory;
            $newRecord->save();
        }

        return response()->json(['success' => true]);
    }


       public function getPriceId(Request $request)
        {
            $contractID = $request->input('contractID');

            // Retrieve the contract by contractID
            $contract = Contract::find($contractID);

            if ($contract) {
                // If contract is found, return the price_id
                return response()->json(['price_id' => $contract->price_id] );
            } else {
                // If contract is not found, return an error message
                return response()->json(['error' => 'Contract not found.'], 404);
            }
        }

        public function getProductId(Request $request)
        {
            $contractID = $request->input('contractID');

            // Retrieve the contract by contractID
            $contract = Contract::find($contractID);

            if ($contract) {
                // If contract is found, return the price_id
                return response()->json(['product_id' => $contract->product_id] );
            } else {
                // If contract is not found, return an error message
                return response()->json(['error' => 'Contract not found.'], 404);
            }
        }

    //insert foreign key price id 
    public function insertPriceId(Request $request) {
        // Retrieve the contractId and productId from the request
        $contractId = $request->input('contractId');
        $productId = $request->input('productId');
    
        try {
            // Find the contract by its ID
            $contract = Contract::findOrFail($contractId);
    
            // Update the contract with the new price ID
            $contract->price_id = $productId; // Assuming $productId is the price ID
    
            // Save the changes to the database
            $contract->save();
    
            // Return a success response
            return response()->json(['message' => 'Price ID inserted successfully'], 200);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json(['error' => 'Failed to update contract.'], 500);
        }
    }


    public function saveSelectedProduct(Request $request)
    {
        // Get contract ID and product ID from the request
        $contractId = $request->input('contractId');
        $productId = $request->input('productId');
    
        try {
            // Find the contract by its ID
            $contract = Contract::findOrFail($contractId);
    
            // Update the contract with the new price ID
            $contract->product_id = $productId; // Assuming $productId is the price ID
    
            // Save the changes to the database
            $contract->save();
    
            // Return a success response
            return response()->json(['message' => 'Price ID inserted successfully'], 200);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json(['error' => 'Failed to update contract.'], 500);
        }

    }

    public function deleteSelectedProduct(Request $request)
    {
        // Get contract ID and product ID from the request
        $contractId = $request->input('contractId');
        $productId = $request->input('productId');

        try {
            $contract = Contract::findOrFail($contractId);
            $contract->product_id = null; // Assuming product_id is nullable
            $contract->save();
    
            return response()->json(['message' => 'Price ID deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete price ID.'], 500);
        }
    }

    
    //for delete foreign price id
    public function deletePriceId(Request $request) {
        $contractId = $request->input('contractId');
    
        try {
            $contract = Contract::findOrFail($contractId);
            $contract->price_id = null; // Assuming price_id is nullable
            $contract->save();
    
            return response()->json(['message' => 'Price ID deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete price ID.'], 500);
        }
    }
    
   // to insert contractvariablecheckbox when pop up variable is checked

 
   public function insertContractVariable(Request $request)
   {
       // Retrieve contract_id and variable_id from the request
       $contractId = $request->input('contract_id');
       $variableId = $request->input('variable_id');
       $mandatory = $request->input('Mandatory');
       $orderValue = $request->input('orderValue');
   
       // Delete all existing records for the given contract_id and variable_id
       ContractVariableCheckbox::where('ContractID', $contractId)
                               ->where('VariableID', $variableId)
                               ->delete();
   
       // Create a new record with the updated values
       $contractVariable = new ContractVariableCheckbox();
       $contractVariable->LoggedinUser = Auth::user()->name;
       $contractVariable->ContractID = $contractId; // Ensure this matches the actual column name
       $contractVariable->VariableID = $variableId;
       $contractVariable->Mandatory = $mandatory;
       $contractVariable->Order = $orderValue;
       $contractVariable->save();
   
       // Return a response indicating success
       return response()->json(['message' => 'Data inserted successfully']);
   }



       //delete the row from contractvariablecheckbox when unchecked
       public function deleteContractVariable(Request $request)
       {
           // Retrieve the ContractID and VariableID from the request
           $contractId = $request->input('contract_id');
           $variableId = $request->input('variable_id');
       
           // Check if the checkbox is unchecked
           if (!$request->has('checked') || $request->input('checked') !== 'true') {
               // Delete rows from the database table where ContractID and VariableID match
               ContractVariableCheckbox::where('ContractID', $contractId)
                   ->where('VariableID', $variableId)
                   ->delete();
       
               // Respond with a success message or any necessary data
               return response()->json(['message' => 'Contract variable deleted successfully']);
           }
       
           // Respond with a message indicating that the checkbox is checked
           return response()->json(['message' => 'Checkbox is checked, no action taken']);
       }
       
    //    public function deleteContractVariable(Request $request)
    //    {
    //        // Retrieve the ContractID and VariableID from the request
    //        $contractId = $request->input('ContractID');
    //        $variableId = $request->input('VariableID');
   
    //        // Delete rows from the database table where ContractID and VariableID match
    //        ContractVariableCheckbox::where('ContractID', $contractId)
    //            ->where('VariableID', $variableId)
    //            ->delete();
   
    //        // Respond with a success message or any necessary data
    //        return response()->json(['message' => 'Contract variable deleted successfully']);
    //    }

       // checked the variableID corresponding to contractID

    //    public function checkedVariable(Request $request)
    //    {
    //        $contractID = $request->input('contract_id');
       
    //        // Retrieve variable IDs and order values
    //        $variables = ContractVariableCheckbox::where('ContractID', $contractID)
    //                                             ->select('VariableID', 'Order')
    //                                             ->get();
       
    //        // Prepare the response data array
    //        $responseData = [
    //            'variableIDs' => $variables->pluck('VariableID')->toArray(),
    //            'orderValues' => $variables->pluck('Order')->toArray(),
    //        ];
       
    //        // Return the response as JSON
    //        return response()->json($responseData);
    //    }
       

    public function checkedVariable(Request $request)
    {
        $contractID = $request->input('contract_id');
    
        // Retrieve variable IDs, order values, and mandatory status
        $variables = ContractVariableCheckbox::where('ContractID', $contractID)
                                             ->select('VariableID', 'Order', 'Mandatory') // Include 'Mandatory' in the selection
                                             ->get();
    
        // Prepare the response data array
        $responseData = [
            'variableIDs' => $variables->pluck('VariableID')->toArray(),
            'orderValues' => $variables->pluck('Order')->toArray(),
            'mandatoryStatuses' => $variables->pluck('Mandatory')->toArray() // Add mandatory statuses to the response
        ];
    
        // Return the response as JSON
        return response()->json($responseData);
    }


        public function getMandatoryFieldValues(Request $request)
        {
            $contractID = $request->input('contract_id');

            // Get mandatory field values for the given contract ID
            $mandatoryFieldValues = ContractVariableCheckbox::where('ContractID', $contractID)
                ->pluck('Mandatory', 'VariableID')
                ->toArray();

            return response()->json($mandatoryFieldValues);
        }

   // ----------------
 
    // testing method 
    public function showvariable()
    {  
        $loggedInUserName = auth::user()->name ;
        $headerEntries = HeaderAndFooter::where('type', 'Header')->pluck('name', 'id')->toArray();
        $footerEntries = HeaderAndFooter::where('type', 'Footer')->pluck('name', 'id')->toArray();
        $variables = VariableList::all();
        return view('Edit-ContractList', compact('variables','headerEntries', 'footerEntries', 'loggedInUserName' ));
    }

     
    // public function edit($id)
    // {
       
    //     $contract = Contract::findOrFail($id);
    //     $variables = VariableList::all();
    //     $products = Product::all(); 
    //     $headerEntries = HeaderAndFooter::where('type', 'Header')->pluck('name', 'id')->toArray();
    //     $footerEntries = HeaderAndFooter::where('type', 'Footer')->pluck('name', 'id')->toArray();
    //     $priceLists = PriceList::all();
    //     return view('Edit-ContractList', compact('contract', 'variables', 'products','headerEntries', 'footerEntries','priceLists'));
    // }

    
    // public function edit($id)
    
    // {
       
    //     $contract = Contract::findOrFail($id);
        
     
    //     $variables = VariableList::all();
    //     $products = Product::all();

 
    //     $headerEntries = HeaderAndFooter::where('type', 'Header')->pluck('name', 'id')->toArray();
    //     $footerEntries = HeaderAndFooter::where('type', 'Footer')->pluck('name', 'id')->toArray();

      
    //     $priceLists = PriceList::all();

     
    //     $headerFooterData = HeaderAndFooterContractpage::where('contractID', $id)->first();

 
    //     return view('Edit-ContractList', compact('contract', 'variables', 'products', 'headerEntries', 'footerEntries', 'priceLists', 'headerFooterData'));
    // }




        public function edit($id)
        {
            // Get the current logged-in user
            $user = Auth::user();
            $companyId = $user->company_id;

            // Fetch the contract details with the matching company_id
            $contract = Contract::where('id', $id)->where('company_id', $companyId)->firstOrFail();

            // Fetch all variables associated with the same company_id
            $variables = VariableList::where('company_id', $companyId)->get();

            // Fetch all products associated with the same company_id
            $products = Product::where('company_id', $companyId)->get();

            // Fetch header and footer entries with the same company_id
            $headerEntries = HeaderAndFooter::where('type', 'Header')
                            ->where('company_id', $companyId)
                            ->pluck('name', 'id')
                            ->toArray();

            $footerEntries = HeaderAndFooter::where('type', 'Footer')
                            ->where('company_id', $companyId)
                            ->pluck('name', 'id')
                            ->toArray();

            // Fetch price lists with the same company_id
            $priceLists = PriceList::where('company_id', $companyId)->get();

            // Fetch existing header/footer settings for the contract with the same company_id
            $headerFooterData = HeaderAndFooterContractpage::where('contractID', $id)->first();

            // Pass all the necessary data to the view
            return view('Edit-ContractList', compact('contract', 'variables', 'products', 'headerEntries', 'footerEntries', 'priceLists', 'headerFooterData'));
        }


    public function updateContract(Request $request)
    {
        // Validate the request data
        $request->validate([
            'id' => 'required|exists:contracts,id',
            'contract_name' => 'required|string',
            'editor_content' => 'required|string',
            // Add validation rules for other fields if needed
        ]);
        
        // Find the contract by its ID
        $contract = Contract::findOrFail($request->id);
        
        // Update contract details
        $contract->contract_name = $request->contract_name;
        $contract->editor_content = $request->editor_content;
        // Update other fields as needed
        
        // Save the updated contract
        $contract->save();
        
        // Redirect back with a success message or return a response
        return response()->json(['message' => 'Contract updated successfully']);
    }
}
