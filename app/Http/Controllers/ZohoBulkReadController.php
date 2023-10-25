<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zoho\CRM\Library\ZCRMRestClient;
use Zoho\CRM\Api\BulkAPI;
use Zoho\CRM\Common\HttpClient;
use Zoho\CRM\Exception\ZCRMException;

class ZohoBulkReadController extends Controller
{
    public function bulkRead(Request $request)
    {
        // Zoho CRM Rest Client Init
        ZCRMRestClient::initialize([
            "client_id" => "YOUR_CLIENT_ID",
            "client_secret" => "YOUR_CLIENT_SECRET",
            "redirect_uri" => "YOUR_REDIRECT_URI",
            "currentUserEmail" => "YOUR_CURRENT_USER_EMAIL"
        ]);

        // Zoho Bulk API instance
        $bulkAPI = new BulkAPI();

        // Module Name
        $module = "Leads";  // Chnage it as per requirement

        // Define the query criteria (you can modify this as needed)
        $query = "Last_Name:equals:Josh"; 
        $fileType = "json"; // Change this to "csv" if you want CSV format

        try {
            // Make the bulk read request
            $response = $bulkAPI->getRecords($module, $query, $fileType);

            // Process the response (you can return it as JSON, save to a file, etc.)
            return response()->json($response);
        } catch (ZCRMException $e) {
            // Handle API errors
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
