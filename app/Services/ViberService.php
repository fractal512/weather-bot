<?php


namespace App\Services;

use GuzzleHttp\Client;


class ViberService
{
    protected $message;

    protected $endpoint;

    protected $response;

    public function __construct(){
        //
    }

    public function performCallbackRequest(){

        $client = new Client();
        $response = $client->post(
            $this->endpoint,
            [
                'headers' => [
                    'Cache-Control'      => 'no-cache',
                    //'Content-Type'       => 'application/json',
                    'X-Viber-Auth-Token' => config('viber.auth_token')
                ],
                'json' => $this->message
            ]
        );

        $this->response = $response->getBody()->getContents();

        return $this;
    }

    public function getResponse(){
        return $this->response;
    }

    /**
     * @param mixed $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}