<?php

namespace Cbf\Inc\Traits;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

trait ConsumeExternalService
{

    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = true)
    {
        try{
            $client = new Client([
                'base_uri' => 'https://api.hubapi.com'
            ]);

            $queryParams['hapikey'] = '8eb58d35-a8f6-4ef1-8c8a-fba26fccf4e7';

            $response = $client->request($method, $requestUrl, [
                $isJsonRequest ?  'json'  : 'form_params' => $formParams,
                'headers' => $headers,
                'query' => $queryParams
            ]);

            $response = $response->getBody()->getContents();

            $response = json_decode($response, true);

            return $response;
        }catch (ClientException $e){
            return $e->getMessage();
        }
    }

}
?>
