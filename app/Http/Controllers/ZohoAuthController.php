<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZohoAuthController extends Controller
{
    public function redirectToZoho()
    {
        $state = bin2hex(random_bytes(16)); // Generate a random state
        session(['oauth_state' => $state]);

        $url = "https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id=" . config('services.zoho.client_id') . "&scope=ZohoCRM.modules.ALL&state=" . $state . "&redirect_uri=" . config('services.zoho.redirect_uri');
        
        return redirect($url);
    }

    public function handleZohoCallback(Request $request)
    {
        if ($request->has('code') && $request->has('state') && $request->state === session('oauth_state')) {
            // Request an access token using the received authorization code
            $response = Http::post('https://accounts.zoho.com/oauth/v2/token', [
                'code' => $request->code,
                'client_id' => config('services.zoho.client_id'),
                'client_secret' => config('services.zoho.client_secret'),
                'redirect_uri' => config('services.zoho.redirect_uri'),
                'grant_type' => 'authorization_code',
            ]);

            $accessToken = $response->json()['access_token'];
            // Store or use the access token as needed

            return redirect()->route('home')->with('status', 'Successfully connected to Zoho CRM');
        }

        return redirect()->route('home')->with('error', 'Failed to authenticate with Zoho CRM');
    }
}
