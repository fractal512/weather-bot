<?php

return [
    'webhook_url' => env('VIBER_WEBHOOK_URL', 'https://chatapi.viber.com/pa/set_webhook'),
    'message_url' => env('VIBER_MESSAGE_URL', 'https://chatapi.viber.com/pa/send_message'),
    'bot_url' => env('VIBER_BOT_URL'),
    'auth_token' => env('VIBER_AUTH_TOKEN'),
];
