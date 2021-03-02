<?php


namespace App\Services;


use GuzzleHttp\Client;

class ViberBrokerService extends ViberService
{
    public function __construct(){
        parent::__construct();

        $this->endpoint = config('viber.message_url');
    }

    public function performGetRequest(){
        $client = new Client();
        $response = $client->request(
            'GET',
            $this->endpoint,
            [
                'headers' => [
                    'Cache-Control'      => 'no-cache',
                    'Content-Type'       => 'application/json'
                ]
            ]
        );

        $this->response = $response->getBody()->getContents();

        return $this;
    }
}