<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RapidApiService
{  


	public  function get(string $host, string $url, array $params)
	{
       return Http::withHeaders(
          [
            'x-rapidapi-host' => $host,
            'x-rapidapi-key'  => ''
          ]
       )->get($url,$params);
	}

}
