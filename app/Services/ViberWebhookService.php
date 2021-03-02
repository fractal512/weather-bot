<?php


namespace App\Services;


class ViberWebhookService extends ViberService
{
    public function __construct(){
        parent::__construct();

        $this->endpoint = config('viber.webhook_url');
        $this->message = [
            "url" => config('viber.bot_url'),
            "event_types" => ["conversation_started"]
        ];
    }
}