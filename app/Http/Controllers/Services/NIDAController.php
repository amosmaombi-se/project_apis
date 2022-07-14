<?php

namespace App\Http\Controllers\Services;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NIDAController extends Controller
{
     private static $nida_url = 'https://ors.brela.go.tz/um/load/load_nida';


     public  function getPersonInformation(){
           $nationalId = request('nationalId');

           $url = self::$nida_url . '/'. $nationalId;
        
       // $url = "https://ors.brela.go.tz/um/load/load_nida/123444444444444";
           $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'application/x-www-form-urlencoded'
            ));

            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
     }


   }
