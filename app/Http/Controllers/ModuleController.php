<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\AccessToken;
use App\ZohoServices\GetListofModules;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Modules::all();
        return view('modules.index', compact('modules'));
    }

    public function syncModules(){
        set_time_limit(300);
        $token = AccessToken::latest()->first();

        $bulk = new GetListofModules($token->token);
        $response =  $bulk->execute();
        if(isset($response['modules'])){
            foreach ($response['modules'] as $key => $module) {
                Modules::updateOrCreate(
                    ['api_name' => $module['api_name']],
                    ['name' => $module['module_name']],
                );
            }
        }

        return redirect()->back();
    }
}
