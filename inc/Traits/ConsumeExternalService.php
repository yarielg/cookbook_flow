<?php

namespace Memd\Inc\Traits;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

trait ConsumeExternalService
{

    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {
        try{
            $client = new Client([
                'base_uri' => $this->baseUri
            ]);

            $token =  $this->resolveToken($client);
            $headers['Authorization'] = "Bearer {$token}";

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

    public function resolveToken($client){

        try{
            if($client == null){
                $client = new Client([
                    'base_uri' => $this->baseUri
                ]);
            }

            $headers = array();
            $headers['Accept'] = 'application/json';
            $headers['Content-Type'] = 'x-www-form-urlencoded';

            $formParams = array();
            $formParams['grant_type'] = 'password';
            $formParams['username'] = get_option('memd_user');
            $formParams['password'] = get_option('memd_password');
            $formParams['client_id'] = get_option('memd_client_id');
            $formParams['client_secret'] = get_option('memd_client_secret');

            $response = $client->request('POST', '/v2/token', [
                false ? 'json' : 'form_params' => $formParams,
                'headers' => $headers,
                'query' => []
            ]);

            $response = $response->getBody()->getContents();

            $response = json_decode($response, true);


            if($response['access_token']){
                return  $response['access_token'];
            }
            return array('success' => false , 'message' => 'No token provided');
        }catch (ClientException $e){
            return $e->getMessage();
        }
    }

}
?>
