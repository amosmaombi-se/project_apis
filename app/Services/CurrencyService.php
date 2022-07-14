<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{  
	 private string $host = '';
	 private string $url  = '';

	public  function __construct(protected RapidApiService $rapidapiservice)
	{

	}

	public  function convert(string $from, string $to, float $amount)
	{
	   $response = $this->rapidapiservice->get($this->host, $this->url,compact('from','to','amount'));

       if($response->failed()){
       	   throw new \RuntimeException("Error Processing Request", $response->status());
       }


       return (float) $response->json('rates.', . $to .' rate_for_amount');
	}

}
