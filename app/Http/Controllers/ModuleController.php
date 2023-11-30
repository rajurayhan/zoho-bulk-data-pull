<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\AccessToken;
use App\ZohoServices\CreateBulkReadjob;
use App\ZohoServices\GetListofModules;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Modules::with('fields', 'bulk_request')->get();
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

    public function makeRequest($id){
        $module = Modules::with('fields')->findOrFail($id);
        $fieldsArray = $module->fields->pluck('api_name');
        $token = AccessToken::latest()->first();

        $bulk = new CreateBulkReadjob($token->token, $module->api_name, $fieldsArray);
        $response =  $bulk->execute();
        return $response;
    }
}
