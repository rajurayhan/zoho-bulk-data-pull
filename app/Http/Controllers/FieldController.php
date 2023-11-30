<?php

namespace App\Http\Controllers;

use App\Http\Traits\TokenCheckTrait;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use App\Models\Fields;
use App\Models\Modules;
use App\ZohoServices\FieldsMetaData;

class FieldController extends Controller
{

    use TokenCheckTrait;
    public function index()
    {
        $fields = Fields::with('module')->get();
        return view('fields.index', compact('fields'));
    }

    public function syncfields($moduleId){
        set_time_limit(300); 
        $this->checkToken();
        $module = Modules::findOrFail($moduleId);
        $token = AccessToken::latest()->first();

        $bulk = new FieldsMetaData($token->token, $module->api_name);
        $response =  $bulk->execute();
        if(isset($response['fields'])){
            foreach ($response['fields'] as $key => $field) {
                Fields::updateOrCreate(
                    ['api_name' => $field['api_name'], 'module_id' => $module->id],
                    ['name' => $field['field_label']],
                );
            }
        }

        return redirect()->back();
    }
}
