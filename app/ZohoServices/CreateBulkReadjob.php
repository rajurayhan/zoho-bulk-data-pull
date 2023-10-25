<?php

namespace App\ZohoServices;

class CreateBulkReadjob
{
    public $token; 
    function __construct($token) {
        $this->token = $token;
    }
	public function execute(){
        $curl_pointer = curl_init();
        
        $curl_options = array();
        $curl_options[CURLOPT_URL] = "https://www.zohoapis.com/crm/bulk/v2/read";
        $curl_options[CURLOPT_RETURNTRANSFER] = true; 
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
        $requestBody = array();
        $callback = array();
        $callback["url"]="http://localhost:8000/zoho/bulk-callback";
        $callback["method"]="post";
        $query = array();
        $query["module"]="Contacts";
        $fields = array();
        $fields[] = "Last_Name";
        $fields[] = "Owner";
        $fields[] = "Owner.last_name";
        $fields[] = "Account_Name.Account_Name";
        $fields[] = "Account_Name.Phone";
        $fields[] = "Lead_Source";
        $fields[] = "Created_Time";
        $requestBody["callback"] =$callback;
        $query["fields"]=$fields;
        // $criteria = array();
        // $criteria["group_operator"]="or";
        // $group = array();
        // $criteria1 = array();
        // $criteria1["api_name"]="Lead_Source";
        // $criteria1["comparator"]="equal";
        // $criteria1["value"]="Advertisement";
        // $criteria2 = array();   
        // $criteria2["api_name"]="Owner.last_name";
        // $criteria2["comparator"]="equal";
        // $criteria2["value"]="Boyle";
        // $criteria3 = array();
        // $criteria3["api_name"]="Account_Name.Phone";
        // $criteria3["comparator"]="contains";
        // $criteria3["value"]="5";
        // $group = [$criteria1,$criteria2,$criteria3];
        // $criteria["group"] = $group;
        // $query["criteria"] =$criteria;
        $query["page"] =1;
        $requestBody["query"]=$query;
        $curl_options[CURLOPT_POSTFIELDS]= json_encode($requestBody);
        $headersArray = array();

        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $this->token;
        $headersArray[] = "Content-Type".":"."application/json";
        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        
        curl_setopt_array($curl_pointer, $curl_options);

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
        list ($headers, $content) = explode("\r\n\r\n", $result, 2);
        if(strpos($headers," 100 Continue")!==false){
            list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
        }
        $headerArray = (explode("\r\n", $headers, 50));
        $headerMap = array();
        foreach ($headerArray as $key) {
            if (strpos($key, ":") != false) {
                $firstHalf = substr($key, 0, strpos($key, ":"));
                $secondHalf = substr($key, strpos($key, ":") + 1);
                $headerMap[$firstHalf] = trim($secondHalf);
            }
        }
        $jsonResponse = json_decode($content, true);
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);
        }
        // var_dump($headerMap);
        return $jsonResponse;
        // var_dump($jsonResponse);
        // var_dump($responseInfo['http_code']);
       
    }
    
} 