<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait CurlDataGrabber
{
    function sendRequest($url, $method = 'GET', $body = [], $headers = [])
    {
        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
        ];

        if (!empty($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        if ($method === 'POST' && !empty($body)) {
            $options[CURLOPT_POSTFIELDS] = http_build_query($body);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('Curl error: ' . $error);
            return false;
        }

        return $response;
    }
}
