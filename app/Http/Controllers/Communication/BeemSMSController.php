<?php

namespace App\Http\Controllers\Communication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BeemSMSController extends Controller
{
    private $api_key = "04aacbf69a4d6f17";
    private $secret_key = "YTQyNWQwYThkN2U1NGQ5NDk1ODU4ZDljZWEzNDUyYmUzMmJjZjc5ZThhNzc2ZGE4OWZlN2MzMDQxNWY2NzdjZA==";

    private $send_url = "https://apisms.beem.africa/v1/send";
    private $balance_url = "https://apisms.beem.africa/public/v1/vendors/balance"; 
    private $report_url = "https://dlrapi.beem.africa/public/v1/delivery-reports"; 
    private $sender_name_url = "https://apisms.beem.africa/public/v1/sender-names"; 
    private $template_url = "https://apisms.beem.africa/public/v1/sms-templates";

    private function httpRequests($body,$url)
    {
         $ch = curl_init($url);
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'Authorization:Basic ' . base64_encode("$this->api_key:$this->secret_key"),
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($body)
            ));

           $response = curl_exec($ch);
            if($response === FALSE){
                    echo $response;
                die(curl_error($ch));
            }
            var_dump($response);

    }

    public function sendMessage()
    {
        $postData = array(
                'source_addr' => 'INFO',
                'encoding'=>0,
                'schedule_time' => '',
                'message' => 'Oyaaaaaa natest ma mesage', // request('message'), 
                'recipients' => [   
                                    array('recipient_id' => '1','dest_addr'=>'255655007457'),
                                    array('recipient_id' => '2','dest_addr'=>'255753683801')
                                 ]
            );
         $this->httpRequests($postData,$this->send_url);
    }



     public function requestSenderName()
     {   
        $postData = array('senderid' => 'AMOS-COP', // Client Sender Name 
                          'sample_content'=> request('sample_content'),
                          );

         $this->httpRequests($postData,$this->sender_name_url);
     }


      public function addSMSTemplates()
     {   
        $postData = ['message'  => request('message'), //Custom message body
                     'sms_title'=> request('sms_title'), //Title of the message template
                    ];

         $this->httpRequests($postData,$this->template_url);
      }


     public function editSMSTemplates()
     {   
         $body = ['message'  => request('message'), //Custom message body
                  'sms_title'=> request('sms_title'), //Title of the message template
                 ];

         $parameter_id = ['template_id'  => request('template_id')];
         $url = $this->template_url . '?' . http_build_query($parameter_id);
         $this->httpRequests($body,$url);
      }


    public  function deleteSMSTemplates()
    {
         $body = ['template_id'  => request('template_id')];
       //  $url = $this->template_url . '?' . http_build_query($parameter_id);
        // $this->httpRequests($body,$url);
    }



    public function checkBalance()
    {

        $ch = curl_init($this->balance_url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$this->api_key:$this->secret_key"),
                'Content-Type: application/json'
            ),
        ));
        // Send the request
        $response = curl_exec($ch);

        if($response === FALSE){
                echo $response;
            die(curl_error($ch));
        }
        var_dump($response);
    }


    public  function derivelyReport()
    {
            $dest_addr = '255655007457'; //  request('phone');
            $request_id = 44351808;  //  request('request_id');
            $body = array('request_id' => $request_id,'dest_addr' => $dest_addr);

            // Setup cURL
            $ch = curl_init();
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            $url = $this->report_url . '?' . http_build_query($body);

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($ch, array(
                CURLOPT_HTTPGET => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization:Basic ' . base64_encode("$this->api_key:$this->secret_key"),
                    'Content-Type: application/json',
                ),
            ));

            // Send the request
            $response = curl_exec($ch);

            // Check for errors
            if ($response === false) {
                echo $response;
                die(curl_error($ch));
            }
            var_dump($response);
    }


    public function senderName()
    {

    }


}
