<?php

namespace App\Http\Controllers\Viber;

use App\Services\ViberApi\WebhookService;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{
    /**
     * Set Viber Webhook.
     *
     * @return string
     */
    public function setWebhook()
    {
        $service = app(WebhookService::class);
        $service->performCallbackRequest();

        return $service->getResponse();
    }

    /**
     * Remove Viber Webhook.
     *
     * @return string
     */
    public function removeWebhook()
    {
        $message = [ "url" => "" ];
        $service = app(WebhookService::class);
        $service->setMessage($message)->performCallbackRequest();

        return $service->getResponse();
    }
}
