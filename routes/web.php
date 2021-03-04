<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::group(['prefix' => config('app.uri')], function() {
    Route::get('/', function () {
        return view('welcome');
    });

    // change this URI in production for security
    Route::get('/set-webhook', 'Viber\WebhookController@setWebhook')->name('set_webhook');
    Route::get('/remove-webhook', 'Viber\WebhookController@removeWebhook')->name('remove_webhook');

    Route::post('/bot', 'Viber\MessageController@respond')->name('bot');

    // workaround used for localhost development purposes
    // uses third-party remote hosting as a proxy to communicate with Viber API server from localhost
    Route::get('/broker', 'Viber\BrokerController@checkViberApiRequest')->name('broker');
});
