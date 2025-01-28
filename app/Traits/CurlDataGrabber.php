<?php

namespace App\Traits;

trait CurlDataGrabber
{
    function sendRequest($url,$method='get',$body=[],$headers=[]){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $error = curl_error($curl);
        if ($error) {
            return false;
        }
        return $response;
    }

}
