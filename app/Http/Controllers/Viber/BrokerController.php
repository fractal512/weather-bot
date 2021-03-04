<?php

namespace App\Http\Controllers\Viber;

use App\Services\ViberApi\BrokerService;
use App\Http\Controllers\Controller;

/**
 * Class BrokerController
 * workaround used for localhost development purposes
 * uses third-party remote hosting as a proxy
 * to communicate with Viber API server from localhost
 *
 * @package App\Http\Controllers\Viber
 */
class BrokerController extends Controller
{
    /**
     * Check out Viber API updates via proxy server
     * and run message callback.
     *
     * @return string
     */
    public function checkViberApiRequest()
    {
        return $this->performRequests( route('bot') );
    }

    /**
     * Do cron job in development environment.
     *
     * @return string
     */
    public function doCronJob()
    {
        return $this->performRequests( route('bot-cron') );
    }

    /**
     * Perform requests.
     *
     * @param string $url
     * @return string
     */
    private function performRequests($url)
    {
        $service = app(BrokerService::class);
        $service->performGetRequest();
        //$apiResponse = json_decode($service->getResponse(), true);
        $apiResponse = $service->getResponse();

        $service->setEndpoint($url)
            ->setMessage($apiResponse)
            //->performGetRequest();
            ->performCallbackRequest();

        //return print_r($service->getResponse(), true);
        return print_r($apiResponse, true);
    }
}
