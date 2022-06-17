<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AirtelControoler extends Controller
{
    private static $client_id = "e78927fe-e87d-479a-8b89-260df346d393";
    private static $client_secret_id = "ab672211-4197-4c11-ba79-b29ce2034ca2";
    private static $grant_type = "client_credentials";
    public $url = "https://openapiuat.airtel.africa";


    public function getAccessToken()
    {  
        $_url = $this->url."/auth/oauth2/token";

        $ch = curl_init($_url);

        // Setup request to send json via POST
       
        $data = array(
            'client_id'=>self::$client_id,
            'client_secret_id'=>self::$client_secret_id,
            'grant_type'=>self::$grant_type
        );

        $payload = json_encode($data);

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);
        return $result;
    }

}
