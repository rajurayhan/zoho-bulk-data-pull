<?php
namespace App\ZohoServices;

class DownloadBulkReadResult 
{
    public $token;  
    public $jobId;  
    function __construct($token, $jobId) {
        $this->token = $token;
        $this->jobId = $jobId;
    }

    public function execute(){
        $curl_pointer = curl_init();
        
        $curl_options = array();
        $curl_options[CURLOPT_URL] = "https://www.zohoapis.com/crm/bulk/v2/read/".$this->jobId."/result";
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
        $headersArray = array();
        
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $this->token;
        
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
        $response = $content;
        if ($response == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $response = json_decode($content, true);
        }
        $contentDisp = $headerMap['Content-Disposition'];
        $fileName = substr($contentDisp, strrpos($contentDisp, "'") + 1, strlen($contentDisp));
        
        if (strpos($fileName, "=") !== false)
        {
            $fileName = substr($fileName, strrpos($fileName, "=") + 1, strlen($fileName));
            
            $fileName = str_replace(array(
                '\'',
                '"'
            ), '', $fileName);
        }
        $filePath = base_path()."/downloads/";
        $fp = fopen($filePath . $fileName, "w"); // $filePath - absolute path where downloaded file has to be stored.
        $stream = $response;
        fputs($fp, $stream);
        fclose($fp); 

        return $fileName;
    }
}