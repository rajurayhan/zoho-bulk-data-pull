<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\AccessToken;
use App\Models\BulkRequest;
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

        // return $response['data'][0];
        \Log::info(['Request Response' => $response]);
        //{"data":[{"status":"success","code":"ADDED_SUCCESSFULLY","message":"Added successfully.","details":{"id":"573545000053974001","operation":"read","state":"ADDED","created_by":{"id":"573545000001183001","name":"Kim Anselmo"},"created_time":"2023-11-30T04:15:37-06:00"}}],"info":[]}
        if(isset($response['data'])){
            $data = $response['data'][0];
            if($data['status'] == 'success'){
                $savedata = [
                    'module_id' => $module->id, 
                    'job_id' => $data['details']['id'],
                    'status' => $data['details']['state'],
                    'response' => json_encode($response)
                ];

                BulkRequest::create($savedata);
            }
        }
        // return $response;
        return redirect()->back();
    }
}
