<?php


namespace Memd\Inc\Services;
use GuzzleHttp\Exception\ClientException;
use Memd\Inc\Traits\ConsumeExternalService as ConsumeExternalService;

class MemdService
{

    use ConsumeExternalService;

    protected $baseUri;

    protected $clientId;

    protected $clientSecret;



    public function __construct()
    {
        $this->baseUri = get_option('memd_uri') ?  get_option('memd_uri') : null;
        $this->clientId = get_option('memd_client_id') ? get_option('memd_client_id')  : null;
        $this->clientSecret = get_option('memd_client_secret') ? get_option('memd_client_secret') : null ;
    }

    public function createMember($user){
      return $this->makeRequest(
          'POST',
         '/v1/partnermember',
                  [],
                  [
                      "externalID" => $user['externalID'],
                      "memberID" => $user['memberID'],
                      "name" => [
                        "First" => $user['First'],
                        "Middle" => $user['Middle'],
                        "Last" => $user['Last']
                        ],
                      "email" => $user['email'],
                      "phone" => $user['phone'],
                      "dob" => $user['dob'],
                      "gender" => $user['gender'],
                      "address" => [
                            "address1" => $user['address1'],
                            "address2" => null,
                            "city" => $user['city'],
                            "state" => $user['state'],
                            "zipCode" => $user['zipCode']
                      ],
                    "termsAgreed" => true,
                    "preferredLanguage" => "NP",
                    "plancode" =>$user['plan_code'],
                    "relationship" => "18",
                     "misc3" =>$user['misc3'],
                     "benefitstart" => $user['benefitstart'],
                     "benefitend" => $user['benefitend']
                  ],
                  [
                    'Content-Type' => 'application/json'
                  ],
       true
           );
    }

    public function createDependentMember($user,$mainExternalId){
        return $this->makeRequest(
            'POST',
            '/v1/partnermember/'.$mainExternalId.'/dependent',
            [],
            [
                "externalID" => $user['externalID'],
                "memberID" => $user['memberID'],
                "name" => [
                    "First" => $user['First'],
                    "Middle" => $user['Middle'],
                    "Last" => $user['Last']
                ],
                "email" => $user['email'],
                "phone" => $user['phone'],
                "dob" => $user['dob'],
                "gender" => $user['gender'],
                "address" => [
                    "address1" => $user['address1'],
                    "address2" => null,
                    "city" => $user['city'],
                    "state" => $user['state'],
                    "zipCode" => $user['zipCode']
                ],
                "termsAgreed" => true,
                "preferredLanguage" => "NP",
                "plancode" =>"XSX6R684",
                "relationship" => $user['relationship'],
            ],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }

    function updatePrimaryMember($user,$memberExternalId){
        return $this->makeRequest(
            'PUT',
            '/v1/partnermember/' . $memberExternalId,
            [],
            [
                "name" => [
                    "First" => $user['First'],
                    "Middle" => $user['Middle'],
                    "Last" => $user['Last']
                ],
                 "email" => $user['email'],
                "phone" => $user['phone'],
                "dob" => $user['dob'],
                "gender" => $user['gender'],
                "address" => [
                    "address1" => $user['address1'],
                    "address2" => null,
                    "city" => $user['city'],
                    "state" => $user['state'],
                    "zipCode" => $user['zipCode']
                ],
                "termsAgreed" => true,
                "preferredLanguage" => "NP"
            ],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }

    function updateDependentMember($user, $memberSubscribedId,$memberExternalId){
        return $this->makeRequest(
            'PUT',
            '/v1/partnermember/' . $memberSubscribedId . '/dependent/' . $memberExternalId,
            [],
            [
                "name" => [
                    "First" => $user['First'],
                    "Middle" => $user['Middle'],
                    "Last" => $user['Last']
                ],
                "email" => $user['email'],
                "phone" => $user['phone'],
                "dob" => $user['dob'],
                "gender" => $user['gender'],
                "address" => [
                    "address1" => $user['address1'],
                    "address2" => null,
                    "city" => $user['city'],
                    "state" => $user['state'],
                    "zipCode" => $user['zipCode']
                ],
                "termsAgreed" => true,
                "preferredLanguage" => "NP",
                "consentedToSubscriber" => false
            ],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }

    function getMember($memberExternalId){
        return $this->makeRequest(
            'GET',
            '/v1/partnermember/' . $memberExternalId,
            [],
            [],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }

    function createPolicy($memberExternalId, $planCode,$start,$end){
        return $this->makeRequest(
            'POST',
            '/v1/partnermember/' .$memberExternalId. '/policy/',
            [],
            [
                "benefitstart" => $start,
                "benefitend" => $end,
                "plancode" => $planCode

            ],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }

    function getMemberPolicy($memberExternalId){
        $member = $this->getMember($memberExternalId);
        return $member['policies'];
    }

    public function memd_visit_link($member_id){
        return $this->makeRequest(
            'POST',
            'v2/partnerpatient/login',
            [],
            [
                "UserId" => $member_id

            ],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }

    function terminatePolicy($memberExternalId, $planCode,$end){
        return $this->makeRequest(
            'POST',
            '/v1/member/' .$memberExternalId. '/policy/' . $planCode,
            [],
            [
                "termdate" => $end

            ],
            [
                'Content-Type' => 'application/json'
            ],
            true
        );
    }
}