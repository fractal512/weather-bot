<?php

namespace App\Http\Controllers\Viber;

use App\Http\Controllers\Controller;
use App\Services\ViberMessageService;
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
        $service = app(ViberMessageService::class);
        $service->dispatchRequest($request)->performCallbackRequest();

        return $service->getResponse();
    }
}
