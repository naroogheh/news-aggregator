<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait CurlDataGrabber
{
    function sendRequest($url,$method='GET',$body=[],$headers=[]){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $error = curl_error($curl);
        if ($error) {
            Log::error('Curl error: ' . $error);
            return false;
        }
        return $response;
    }

}
