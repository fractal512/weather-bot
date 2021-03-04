<?php

namespace App\Http\Controllers\Viber;

use App\Http\Controllers\Controller;
use App\Services\ViberApi\BotService;
use App\Services\ViberApi\CronJobService;
use Illuminate\Http\Request;

class BotController extends Controller
{
    /**
     * Send callback response to Viber request.
     *
     * @param Request $request
     * @return string
     */
    public function respond(Request $request)
    {

        $viber = json_decode($request->all()[0], false);

        $service = app(BotService::class, compact('viber'));
        $service->dispatchEvents()->performCallbackRequest();

        return $service->getResponse();
    }

    /**
     * Do cron job in production environment.
     *
     * @return string
     */
    public function doCronJob()
    {
        $service = app(CronJobService::class);
        $service->run();
    }
}
