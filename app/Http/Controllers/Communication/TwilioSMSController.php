<?php

namespace App\Http\Controllers\Communication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwilioSMSController extends Controller
{
    
    public function sendSMS()
    {
       $phone_to = request('phone');
       $message = request('message');

       $from = getenv("TWILIO_FROM");
       //open connection

       $ch = curl_init();
       //set the url, number of POST vars, POST data
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_USERPWD, getenv("TWILIO_SID").':'.getenv("TWILIO_TOKEN"));
       curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
       curl_setopt($ch, CURLOPT_URL, sprintf('https://api.twilio.com/2010-04-01/Accounts/'.getenv("TWILIO_SID").'/Messages.json', getenv("TWILIO_SID")));
       curl_setopt($ch, CURLOPT_POST, 3);
       curl_setopt($ch, CURLOPT_POSTFIELDS, 'To='.$phone_to.'&From='.$from.'&Body='.$message);
       // execute post
       $result = curl_exec($ch);
       $result = json_decode($result);

       // close connection
       curl_close($ch);

       return [$result];
       //Sending message ends here
    }
}
