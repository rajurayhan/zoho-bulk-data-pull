<?php
namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\ZohoServices\CreateBulkReadjob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;

class ZohoAuthController extends Controller
{
    private $scopes = 'ZohoCRM.bulk.ALL,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL';
    public function redirectToZoho()
    {
           try {
            $requestBody = [ 
                'client_id' => config('services.zoho.client_id'),
                'response_type' => 'code',
                'redirect_uri' => route('zoho.oauth.callback'),
                // 'scope' => 'ZohoCRM.modules.contacts.READ',
                'scope' => $this->scopes,
            ];

            $redirectURL = 'https://accounts.zoho.com/oauth/v2/auth?'.http_build_query($requestBody);

            return redirect()->away($redirectURL);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function handleZohoCallback(Request $request)
    { 
        try {
            $requestBody = [
                'code' => request()->code,
                'client_id' => config('services.zoho.client_id'),
                'client_secret' => config('services.zoho.client_secret'),
                // 'scope' => 'ZohoCRM.modules.contacts.READ',
                'scope' => $this->scopes,
                'grant_type' => 'authorization_code',
                'redirect_uri' => route('zoho.oauth.callback')
            ];

            $client = new GuzzleClient();
            $api = 'https://accounts.zoho.com/oauth/v2/token?'.http_build_query($requestBody);
            $response = $client->request('POST', $api);
            $response = $response->getBody()->getContents();
            $data = json_decode($response);
            \Log::info(['Zoho Token' => $data]);

            if($data->access_token){
                $tokenObj = new AccessToken();
                $tokenObj->token = $data->access_token; 

                $tokenObj->save();
            }

            // return $data;
            return redirect()->route('home'); 
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function bulkCallback(Request $request){
        return $request->all();
    }

    public function bulkContacts(Request $request){
        set_time_limit(300);
        $token = AccessToken::latest()->first();
        \Log::info(['Latest Token' => $token->token]);
        $bulk = new CreateBulkReadjob($token->token, 'Contacts');
        return $bulk->execute();
    }

    public function bulkLeads(Request $request){
        set_time_limit(300);
        $token = AccessToken::latest()->first();
        $bulk = new CreateBulkReadjob($token->token, 'Leads');
        return $bulk->execute();
    }
}
