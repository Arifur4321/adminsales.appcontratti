<?php

namespace App\Http\Controllers;

use App\Models\SalesListDraft;

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
use App\Models\SalesDetails; 
use App\Models\ProductToSales;
use HelloSign\Client as HelloSignClient;

use HelloSign\Client;
use Dropbox\Sign\Api\SignatureRequestApi;
use Dropbox\Sign\ApiException;

use Dropbox\Sign\Configuration;
 
class ContractHistoryController extends Controller
{
    
    
    protected $signatureRequestApi;

    public function __construct()
    {   
        $config = Configuration::getDefaultConfiguration();
        $config->setUsername(env('HELLOSIGN_API_KEY'));
        $this->signatureRequestApi = new SignatureRequestApi($config);
    }

    public function getSignedPdfUrl($id)
    {
        $item = SalesListDraft::findOrFail($id);

        if ($item->status == 'signed') {
            try {
                $result = $this->signatureRequestApi->signatureRequestFilesAsFileUrl($item->envelope_id);
                $fileUrl = $result->getFileUrl();

                if ($fileUrl) {
                    return response()->json(['success' => true, 'file_url' => $fileUrl]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Failed to retrieve the signed PDF URL.']);
                }
            } catch (ApiException $e) {
                $error = $e->getResponseObject();
                return response()->json(['success' => false, 'message' => 'Failed to retrieve the signed PDF URL: ' . print_r($error->getError(), true)]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Item is not signed or could not fetch link']);
    }


    public function fetchHistory(Request $request)
    {
        $email = $request->input('email');
    
        // Find the sales record by email
        $salesDetail = SalesDetails::where('email', $email)->first();
    
        if ($salesDetail) {
            // Get history from SalesListDraft where sales_id matches
            $history = SalesListDraft::where('sales_id', $salesDetail->id)
                ->select( 'contract_name', 'selected_pdf_name', 'id' , 'status' )
                ->get();
    
            return response()->json(['history' => $history]);
        }
    
        return response()->json(['history' => []], 404);
    }
    

    public function downloadPdf($id)
{
    // Log the function call
    Log::info('downloadPdf called with ID: ' . $id);

    // Retrieve the record from SalesListDraft using the provided ID
    $record = SalesListDraft::findOrFail($id);

    // Check if the envelope_id is exactly 40 characters long (indicating a HelloSign signature) and if the status is 'signed'
    if (strlen($record->envelope_id) === 40 && $record->status === 'signed') {
        try {
            Log::info('Attempting to fetch signed PDF via HelloSign API for envelope ID: ' . $record->envelope_id);

            // Fetch the signed PDF URL from HelloSign API
            $result = $this->signatureRequestApi->signatureRequestFilesAsFileUrl($record->envelope_id);
            $fileUrl = $result->getFileUrl();

            if ($fileUrl) {
                Log::info('Successfully retrieved signed PDF URL from HelloSign: ' . $fileUrl);

                // Redirect to the HelloSign URL to download the PDF directly
                return redirect($fileUrl);
            } else {
                Log::error('Failed to retrieve signed PDF URL from HelloSign API.');
            }
        } catch (ApiException $e) {
            $error = $e->getResponseObject();
            Log::error('HelloSign API Error:', ['error' => $error]);
        }
    }

    // If HelloSign retrieval fails or is not valid, check the database for PDF content
    if ($record->pdf_content) {
        Log::info('PDF found in the database for ID: ' . $id);

        // Retrieve the PDF content from the BLOB (pdf_content column)
        $pdfContent = $record->pdf_content;
        $fileName = ($record->selected_pdf_name ?? 'signed_contract_' . $id) . '.pdf';

        // Return a response to download the PDF
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Length' => strlen($pdfContent)  // Set content length header
        ]);
    }

    // If neither HelloSign nor database retrieval succeeds, respond with an error message
    return response()->json(['success' => false, 'message' => 'Signed PDF file not found. Please contact support.']);
}


    // public function downloadPdf($id)
    // {
    //     $record = SalesListDraft::findOrFail($id);
    //     $pdfContent = $record->pdf_content;

    //     return response()->streamDownload(function () use ($pdfContent) {
    //         echo $pdfContent;
    //     }, "{$record->selected_pdf_name}.pdf");
    // }


    public function showAll()
    {
        // Fetch all rows from SalesListDraft table with related SalesDetails
      //  $salesListDraft = SalesListDraft::with('salesDetails')->get();

      $user = Auth::user();
        
      // Fetch products where company_id matches the authenticated user's company_id
      $salesListDraft = SalesListDraft::where('company_id', $user->company_id)->get();
      
      // Return the view with the fetched data
        return view('Contract-History', compact('salesListDraft'));
    }  

    
    // for delete row in sales draft list table
    public function destroy($id)
    {
        $salesListDraft = SalesListDraft::find($id);

        if (!$salesListDraft) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // Delete the record
        $salesListDraft->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }
}