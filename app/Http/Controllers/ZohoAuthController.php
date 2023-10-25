<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;

class ZohoAuthController extends Controller
{
    public function redirectToZoho()
    {
        // $state = bin2hex(random_bytes(16)); // Generate a random state
        // session(['oauth_state' => $state]);

        // $url = "https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.modules.ALL&response_type=code&client_id=" . config('services.zoho.client_id') . "&scope=ZohoCRM.modules.ALL&state=" . $state . "&redirect_uri=" . config('services.zoho.redirect_uri');
        
        // return redirect($url);

        try {
            $requestBody = [ 
                'client_id' => config('services.zoho.client_id'),
                'response_type' => 'code',
                'redirect_uri' => route('zoho.oauth.callback'),
                'scope' => 'ZohoCRM.modules.ALL',
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
                'scope' => 'ZohoCRM.modules.ALL',
                'grant_type' => 'authorization_code',
                'redirect_uri' => route('zoho.oauth.callback')
            ];

            $client = new GuzzleClient();
            $api = 'https://accounts.zoho.com/oauth/v2/token?'.http_build_query($requestBody);
            $response = $client->request('POST', $api);
            $response = $response->getBody()->getContents();
            $data = json_decode($response);

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
