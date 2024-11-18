<?php

namespace App\Http\Controllers;
use App\Models\AppConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use GuzzleHttp\Client; 
use Gyurobenjamin\Closeio\Closeio;
 

class CloseController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Retrieve the existing AppConnection for general purposes (first record for this company)
        $appConnection = AppConnection::where('company_id', $company_id)->first();

        // Retrieve the existing AppConnection for SMS
        $smsConnection = AppConnection::where('company_id', $company_id)
                                    ->where('type', 'SMS')
                                    ->first();

        // Retrieve the existing AppConnection for Sales SMS
        $salesSmsConnection = AppConnection::where('company_id', $company_id)
                                        ->where('type', 'Sales_SMS')
                                        ->first();

        // Initialize smsEnabled and salesSmsEnabled as false by default
        $smsEnabled = false;
        $salesSmsEnabled = false;

        // Check if there is an existing api_key and if sms_enabled is true for SMS
        if ($smsConnection && isset(json_decode($smsConnection->api_key)->sms_enabled)) {
            $smsEnabled = json_decode($smsConnection->api_key)->sms_enabled;
        }

        // Check if there is an existing api_key and if sales_sms_enabled is true for Sales SMS
        if ($salesSmsConnection && isset(json_decode($salesSmsConnection->api_key)->sales_sms_enabled)) {
            $salesSmsEnabled = json_decode($salesSmsConnection->api_key)->sales_sms_enabled;
        }

        // Pass both appConnection, smsEnabled, and salesSmsEnabled to the view
        return view('AppConnections', compact('appConnection', 'smsEnabled', 'salesSmsEnabled'));
    }

    
    public function saveSMSToggle(Request $request)
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Fetch or create the AppConnection record for SMS
        $appConnection = AppConnection::firstOrNew([
            'company_id' => $company_id,
            'type' => 'SMS',
        ]);

        // Decode the existing api_key JSON or initialize an empty object
        $apiKeyData = json_decode($appConnection->api_key) ?? new \stdClass();

        // Check if api_key is not an array or object, and initialize it as an empty stdClass
        if (!is_object($apiKeyData)) {
            $apiKeyData = new \stdClass();
        }

        // Update the sms_enabled property based on the toggle switch
        $apiKeyData->sms_enabled = $request->has('enable_sms') ? true : false;

        // Save the updated api_key back as JSON
        $appConnection->api_key = json_encode($apiKeyData);

        // Save the AppConnection record
        $appConnection->save();

        return response()->json(['success' => 'SMS setting saved successfully!']);
    }


    public function saveSalesSmsToggle(Request $request)
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Fetch or create the AppConnection record for Sales SMS
        $appConnection = AppConnection::firstOrNew([
            'company_id' => $company_id,
            'type' => 'Sales_SMS', // Ensure this matches the type for Sales SMS
        ]);

        // Decode the existing api_key JSON or initialize an empty object
        $apiKeyData = json_decode($appConnection->api_key) ?? new \stdClass();

        // Ensure that the api_key is an object (stdClass)
        if (!is_object($apiKeyData)) {
            $apiKeyData = new \stdClass();
        }

        // Update the sales_sms_enabled property based on the toggle switch
        $apiKeyData->sales_sms_enabled = $request->has('enable_sales_sms') ? true : false;

        // Save the updated api_key back as JSON
        $appConnection->api_key = json_encode($apiKeyData);

        // Save the AppConnection record
        $appConnection->save();

        return response()->json(['success' => 'Sales SMS setting updated successfully.']);
    }




    public function saveApiKey(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'type' => 'required|string|in:Close,Zapier,Salesforce,Pipedrive',
        ]);
    
        $user = Auth::user();
        $company_id = $user->company_id;
    
        // Create a JSON structure to store the api_key, pending, and signed notes together.
        $apiData = json_encode([
            'api_key' => $request->api_key,
            'pending' => $request->pending ?? '', // Use the value or default to an empty string if not provided
            'signed' => $request->signed ?? '',   // Use the value or default to an empty string if not provided
        ]);
    
        if ($request->type === 'Close') {
    
            $client = new Client();
            
            try {
                $response = $client->request('GET', 'https://api.close.com/api/v1/me/', [
                    'auth' => [$request->api_key, '']
                ]);
    
                if ($response->getStatusCode() === 200) {
                    $appConnection = AppConnection::where('company_id', $company_id)
                                                ->where('type', $request->type)
                                                ->first();
    
                    if ($appConnection) {
                        $appConnection->update(['api_key' => $apiData]);
                    } else {
                        AppConnection::create([
                            'company_id' => $company_id,
                            'type' => $request->type,
                            'api_key' => $apiData,
                        ]);
                    }
    
                    return response()->json(['success' => 'Close API Key saved successfully.']);
                } else {
                    return response()->json(['error' => 'Invalid Close API Key.'], 400);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to validate Close API key.',
                    'message' => $e->getMessage(),
                ], 500);
            }
            
        } else {
            $appConnection = AppConnection::updateOrCreate(
                ['company_id' => $company_id, 'type' => $request->type],
                ['api_key' => $apiData]
            );
    
            return response()->json(['success' => "{$request->type} API Key saved successfully."]);
        }
    }
    

    public function getLeads(Request $request)

    {
        $user = Auth::user();
        $company_id = $user->company_id;

        $type = $request->get('type', 'Close');  

        $api_key = $request->get('api_key');

        // Retrieve the existing API key for the selected type
        $appConnection = AppConnection::where('company_id', $company_id)
                                    ->where('type', $type)
                                    ->first();

        if (!$appConnection || !$appConnection->api_key) {

            return response()->json(['error' => 'API Key not found for the selected CRM type.'], 404);
        
        }

        $client = new Client();

        try {
            $response = $client->request('GET', 'https://api.close.com/api/v1/lead/', [
                'auth' => [$api_key, ''] // Use Basic Auth with the API key as the username
            ]);

            $leads = json_decode($response->getBody()->getContents(), true);
            return response()->json($leads);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch leads.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),   
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function addLead(Request $request)

    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Retrieve the existing API key
        $appConnection = AppConnection::where('company_id', $company_id)->first();

        if (!$appConnection || !$appConnection->api_key) {
            return response()->json(['error' => 'API Key not found.'], 404);
        }

        $client = new Client();
        $data = [
            'name' => $request->input('name'),
            'status_id' => $request->input('status_id'),
        ];

        try {
            $response = $client->request('POST', 'https://api.close.com/api/v1/lead/', [
                'auth' => [$appConnection->api_key, ''], // Use Basic Auth with the API key as the username
                'json' => $data, // Pass data directly as JSON
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add lead.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }


    public function getLeadStatuses(Request $request)
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Retrieve the existing API key
        $appConnection = AppConnection::where('company_id', $company_id)->first();

        if (!$appConnection || !$appConnection->api_key) {
            return response()->json(['error' => 'API Key not found.'], 404);
        }

        $client = new Client();

        try {
            $response = $client->request('GET', 'https://api.close.com/api/v1/status/lead/', [
                'auth' => [$appConnection->api_key, ''], // Use Basic Auth with the API key as the username
            ]);

            $statuses = json_decode($response->getBody()->getContents(), true);
            return response()->json($statuses);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch lead statuses.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }


 

    public function addComment(Request $request, $leadId)
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Retrieve the existing API key
        $appConnection = AppConnection::where('company_id', $company_id)->first();

        if (!$appConnection || !$appConnection->api_key) {
            return response()->json(['error' => 'API Key not found.'], 404);
        }

        $client = new Client();
        $data = [
            'note' => $request->input('note'),
            'lead_id' => $leadId,
        ];

        try {
            $response = $client->request('POST', 'https://api.close.com/api/v1/activity/note/', [
                'auth' => [$appConnection->api_key, ''], // Use Basic Auth with the API key as the username
                'json' => $data, // Pass data directly as JSON
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add comment.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }



}