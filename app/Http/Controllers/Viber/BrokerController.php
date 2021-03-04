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
        $service = app(BrokerService::class);
        $service->performGetRequest();
        //$apiResponse = json_decode($service->getResponse(), true);
        $apiResponse = $service->getResponse();

        $service->setEndpoint(route('bot'))
                ->setMessage($apiResponse)
                //->performGetRequest();
                ->performCallbackRequest();

        //return print_r($service->getResponse(), true);
        return print_r($apiResponse, true);
    }
}
