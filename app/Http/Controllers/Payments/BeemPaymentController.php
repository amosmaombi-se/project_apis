<?php

namespace App\Http\Controllers\Payments;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BeemPaymentController extends Controller
{
    public $url ='https://checkout.beem.africa/v1/checkout';
    public $username = "6da7dd6a2186bf90";
    public $password = "==";
    public $secure_token = "";

    public function checkOut(){
            $reference_number = str_pad(date('YmdHis'), 8, "0", STR_PAD_LEFT);
            $transaction_id  =  Str::uuid()->toString();

            $reference_number = $reference_number;
            $amount = 5000; //request('amount');
            $mobile = 255655007457;
            $transaction_id = $transaction_id;
            $sendSource = true; 
            $body = array('amount' => $amount, 'transaction_id' => $transaction_id, 'reference_number' => 
                $reference_number, 'mobile' => $mobile, 'sendSource' => $sendSource);

            // Setup cURL
            $ch = curl_init();
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            $Url = $this->url . '?' . http_build_query($body);

            curl_setopt($ch, CURLOPT_URL, $Url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($ch, array(
                CURLOPT_HTTPGET => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization:Basic ' . base64_encode("$this->username:$this->password"),
                    'Content-Type: application/json',
                    'beem-secure-token: $this->secure_token',
                ),
            ));

            // Send the request
            $response = curl_exec($ch);

            // Check for errors
            if ($response === false) {
                echo $response;

                die(curl_error($ch));
            }
            return $response;

    }
}
