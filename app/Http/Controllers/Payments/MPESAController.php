<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MPESAController extends Controller
{
   

    public function getAccessToken()
    {
        $url = env('MPESA_ENV') === 0 ? '' :  '';

        $curl = curl_init($url);
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_HTTPHEADER => ['Content-Type: application/json;charset=utf-8'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_USERPWD => env('MPESA_CONSUMER_KEY') . ':' .env('MPESA_CONSUMER_SECRET'),
            )
        );

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return $response->access_token;
    }


    public function transactionStatus()
    {
        $body = array(
            'Initiator' => env('MPESA_B2C_INITIATOR'),
            'SecurityCredential' => env('MPESA_B2C_PASSWORD'), // mpesa public key
            'CommandId' => 'TransactionaStatusQuery', //
            'TransactionId' => request('transactionid'),
            'PartyA' => env('MPESA_SHORTCODE'),
            'IdentifierType' => '4',
            'Remarks' => 'Check transaction',
            'QueueTimeOutURL' => env('MPESA_TEST_URL') . '/api/transactional_status/timeout-url',
            'ResultURL' => env('MPESA_TEST_URL') . '/api/transactional_status/result-url',
            'Occasion' => 'VerifyTransaction',
        );

        $url = env('MPESA_ENV') === 0 ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/query':'https://api.safaricom.co.ke/mpesa/c2b/v1/query';
        $response = $this->makeHttp($url,$body);
        return $response;
    }

    public function reverseTransactionS()
    {
        $body = array(
            'Initiator' => env('MPESA_B2C_INITIATOR'),
            'SecurityCredential' => env('MPESA_B2C_PASSWORD'), // mpesa public key
            'CommandId' => 'TransactionReversal', //
            'TransactionId' => request('transactionid'),
            'Amount' => request('amount'),
            'ReceiverParty' => env('MPESA_SHORTCODE'),
            'ReceiverPartyIdentifier' => 11, 
            'ResultURL' => env('MPESA_TEST_URL') . '/api/reversal/result-url',
            'QueueTimeOutURL' => env('MPESA_TEST_URL') . '/api/reversal/timeout-url', // Once live put APP_URL
            'Remarks' => 'ReversalRequest',
            'Occasion' => 'ReversTransaction',
        );

        $url = env('MPESA_ENV') === 0 ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/reversal':'https://api.safaricom.co.ke/mpesa/c2b/v1/reversal';
        $response = $this->makeHttp($url,$body);
        return $response;
    }



    public function makeHttp($url,$body)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                    CURLOPT_URL => $url,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json','Authorization:Bearer ' . $this->getAccessToken()],
                    CURLOPT_RETURNTRANSER => true,
                    CURLOPT_POST => false,
                    CURLOPT_POSTFIELDS => json_encode($body),
               )
          );
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    }


    public function registerURLS()
    {
        $body = array(
            'ShortCode' => env('MPESA_SHORTCODE'),
            'ResponseType' => 'Completed',
            'Confirmation_url' => env('MPESA_TEST_URL') . '/api/confirmation',
            'ValidationURL' => env('MPESA_TEST_URL') . '/api/validation'
        );

        $url = env('MPESA_ENV') === 0 ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl':'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
        $response = $this->makeHttp($url,$body);
        return $response;
    }

    
    public function simulateTransaction()
    {
        $body = array(
                'ShortCode' => env('MPESA_SHORTCODE'),
                'Msisdn' => env('385777577575775'),
                'Amount' => request('amount'),
                'BillRefNumber' => request('account'),
                'CommandId'  => 'CustomerPayBillOnline'
          );

        $url = env('MPESA_ENV') === 0 ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate':'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';

        $response = $this->makeHttp($url,$body);
        return $response;
    }


    public function b2cRequest()
    {
          $curl_post_data = array(
            'InitiatorName' => env('MPESA_B2C_INITIATOR'),
            'SecurityCredentials' => env('MPESA_B2C_PASSWORD'),
            'CommandId' => 'SalaryPayment',
            'Amount' => request('amount'),
            'PartyA' => env('MPESA_SHORTCODE'),
            'PartyB' => request('phone'),
            'Remarks' => request('remarks'),
            'QueueTimeOutURL' => env('MPESA_TEST_URL') . '/api/b2cresult',
            'ResultURL' => env('MPESA_TEST_URL') . '/api/b2ctimeout',
            'Occasion' => request('occasion'),
        );

        $url = env('MPESA_ENV') === 0 ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/paymentrequest':'https://api.safaricom.co.ke/mpesa/c2b/v1/paymentrequest';
        $res = $this->makeHttp($url,$curl_post_data);
        return $res;
    }



    public function validation()
    {
        Log::info('Validation endpoint hit');
        Log::info(request()->all());

        return array(
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000,1000)
        );
    }


    
    public function stkPush()
    {
        Log::info('STK Push endpoint hit');
        Log::info(request()->all());

        return array(
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000,1000)
        );
    }


    
    public function b2cCallback()
    {
        Log::info('Validation endpoint hit');
        Log::info(request()->all());

        return array(
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000,1000)
        );
    }


    public function trasactionStatusResponse()
    {
        Log::info('trasaction Status Response endpoint hit');
        Log::info(request()->all());

        return array(
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000,1000)
        );
    }


    public function confirmation()
    {
        Log::info('Confirmation endpoint hit');
        Log::info(request()->all());
    }












    
}
