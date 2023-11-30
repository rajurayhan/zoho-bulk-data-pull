<?php

namespace App\Http\Controllers;

use App\Http\Traits\TokenCheckTrait;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\AccessToken;
use App\Models\BulkRequest;
use App\ZohoServices\CreateBulkReadjob;
use App\ZohoServices\DownloadBulkReadResult;
use App\ZohoServices\GetListofModules;
use App\ZohoServices\GettheStatusoftheBulkReadJob;
use Illuminate\Support\Facades\Response;

class ModuleController extends Controller
{
    use TokenCheckTrait;
    
    public function index()
    {
        $modules = Modules::with('fields', 'bulk_request')->get();
        return view('modules.index', compact('modules'));
    }

    public function syncModules(){
        set_time_limit(300);
        $this->checkToken();
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
        $this->checkToken();
        $module = Modules::with('fields')->findOrFail($id); 
        // if(sizeof($module->fields) < 1){
        //     return 'Sync Fields first';
        // }
        $fieldsArray = $module->fields->pluck('api_name');
        $token = AccessToken::latest()->first();

        $bulk = new CreateBulkReadjob($token->token, $module->api_name, $fieldsArray);
        $response =  $bulk->execute(); 
        \Log::info(['Request Response' => $response]);
        //{"data":[{"status":"success","code":"ADDED_SUCCESSFULLY","message":"Added successfully.","details":{"id":"573545000053974001","operation":"read","state":"ADDED","created_by":{"id":"573545000001183001","name":"Kim Anselmo"},"created_time":"2023-11-30T04:15:37-06:00"}}],"info":[]}
        if(isset($response['data'])){
            $data = $response['data'][0];
            if($data['status'] == 'success'){
                $savedata = [
                    'job_id' => $data['details']['id'],
                    'status' => $data['details']['state'],
                    'response' => json_encode($response)
                ];

                BulkRequest::updateOrCreate(
                    ['module_id' => $module->id], 
                    $savedata
                );
            }
        }
        // return $response;
        return redirect()->back();
    } 
    public function checkRequestStatus($id){
        $this->checkToken();
        $module = Modules::with('bulk_request')->findOrFail($id);
        if($module->bulk_request){
            $token = AccessToken::latest()->first();
            $jobId = $module->bulk_request->job_id;
            $bulk = new GettheStatusoftheBulkReadJob($token->token, $jobId);

            $response =  $bulk->execute(); 
            if(isset($response['data'])){
                $data = $response['data'][0];
                $bulkRequest = BulkRequest::where('module_id', $module->id)->first();
                $bulkRequest->status = $data['state'];
                $bulkRequest->save();

            }

            return redirect()->back();
        } 
    }

    public function downloadRequest($id){ 
        $this->checkToken();
        $module = Modules::with('bulk_request')->findOrFail($id);
        if($module->bulk_request){ 
            if($module->bulk_request->file_path != null){
                $filePath = base_path().'/downloads/'.$module->bulk_request->file_path;
                if(file_exists($filePath)){
                    return Response::download($filePath, $module->api_name.".zip");
                } 
            } 
            $token = AccessToken::latest()->first();
            $jobId = $module->bulk_request->job_id;
            $bulk = new DownloadBulkReadResult($token->token, $jobId);
            $fileName = $bulk->execute(); 

            $bulkRequest = BulkRequest::where('module_id', $module->id)->first();
            $bulkRequest->file_path = $fileName;
            $bulkRequest->save();

            return $bulkRequest;

            $filePath = base_path().'/downloads/'.$fileName;
            if(file_exists($filePath)){
                return Response::download($filePath, $module->api_name.".zip");
            } 
        }
        return redirect()->back();
    }
}
