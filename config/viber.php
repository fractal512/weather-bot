<?php

return [
    'webhook_url' => env('VIBER_WEBHOOK_URL', 'https://chatapi.viber.com/pa/set_webhook'),
    'message_url' => env('VIBER_MESSAGE_URL', 'https://chatapi.viber.com/pa/send_message'),
    'auth_token' => env('VIBER_AUTH_TOKEN'),
];
