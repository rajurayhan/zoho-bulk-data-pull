<?php 

namespace App\Http\Traits;

use App\Models\AccessToken;

trait TokenCheckTrait
{
    public function checkToken()
    {
        $token = AccessToken::latest()->first();

        if(!$token){
            echo 'Authenticate this app with zoho first to continue';
            exit();
        }
    }
}
