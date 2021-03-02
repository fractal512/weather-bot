<?php

namespace App\Http\Controllers\Viber;

use App\Services\ViberWebhookService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class WebhookController extends Controller
{
    /**
     * Set Viber Webhook.
     *
     * @return string
     */
    public function setWebhook()
    {
        $service = app(ViberWebhookService::class);
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
        $service = app(ViberWebhookService::class);
        $service->setMessage($message)->performCallbackRequest();

        return $service->getResponse();
    }
}
