<?php

namespace PocketBase;

class HttpClient
{
    public function doRequest(string $url, string $method, $bodyParams = [], ?string $token = null): string
    {
        $ch = curl_init();

        if ($token != '') {
            $headers = array(
                'Content-Type:application/json',
                'Authorization: ' . $token
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($bodyParams) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyParams);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}