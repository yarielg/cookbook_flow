<?php


namespace Cbf\Inc\Services;
use GuzzleHttp\Exception\ClientException;
use Cbf\Inc\Traits\ConsumeExternalService as ConsumeExternalService;

class HubspotService
{

    use ConsumeExternalService;


    public function __construct()
    {

    }

    public function createContact($email, $first, $last){
      return $this->makeRequest(
          'POST',
         '/contacts/v1/contact',
                  [],
                  [
                    "properties" => [
                    	[
		                    "property" => "email",
		                    "value" => $email
	                    ],
	                    [
		                    "property" => "firstname",
		                    "value" => $first
	                    ],
	                    [
		                    "property" => "lastname",
		                    "value" => $last
	                    ]
                    ]
                  ],
                  [
                    'Content-Type' => 'application/json'
                  ],
       true
           );
    }

    public function addContactToList($email,$list_id){
        return $this->makeRequest(
            'POST',
            '/contacts/v1/lists/' . $list_id . '/add',
            [],
            [
                "emails" => [
                	$email
                ]
            ],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }

	public function getLists(){
		return $this->makeRequest(
			'GET',
			'/contacts/v1/lists',
			[],
			[],
			[
				'Content-Type' => 'application/json'
			],
			true
		);
	}

}