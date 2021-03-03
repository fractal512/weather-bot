<?php

namespace App\Http\Controllers\Viber;

use App\Http\Controllers\Controller;
use App\Services\ViberApi\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Send callback response to Viber request.
     *
     * @param Request $request
     * @return string
     */
    public function respond(Request $request)
    {
        //dd($request);
        $service = app(MessageService::class);
        $service->dispatchRequest($request)->performCallbackRequest();

        return $service->getResponse();
    }
}
