<?php


namespace App\Services\ViberApi;

use App\Models\ViberBotUser;
use App\Services\WeatherApi\OpenWeatherMapService;
use Carbon\Carbon;

class CronJobService extends BaseService
{
    public function __construct(){
        parent::__construct();
        $this->endpoint = config('viber.message_url');
    }

    public function run()
    {
        $timeout = ini_get('max_execution_time') ? (float) (ini_get('max_execution_time')) : (float) 30 + 10;
        $start = microtime(true);

        $users = ViberBotUser::all();
        foreach ($users as $user) {

            $this->serveUser($user);

            $elapsed = microtime(true) - $start;
            if( ! ( $elapsed < ( $timeout - 10 ) ) ){
                break;
            }
        }

        return $this;
    }

    private function serveUser($user)
    {
        $now = Carbon::now();
        $diff = $now->diff($user->updated_at);
        $diffSec = $diff->s
            + $diff->i * 60
            + $diff->h * 60 * 60
            + $diff->d * 60 * 60 * 24;
        $userIntervalSec = $user->interval * 60 * 60;
        if ( $diffSec < $userIntervalSec ) return;

        $message['receiver'] = $user->viber_user_id;
        $message['type'] = 'text';

        $weather = new OpenWeatherMapService();
        $city = $user->city;
        $message['text'] = $weather->prepareRequestWithCity($city)
            ->performApiRequest()
            ->renderOutput();
        $this->setMessage($message)->performCallbackRequest();

        ViberBotUser::where('viber_user_id', $user->viber_user_id)
                    ->update(['updated_at' => $now]);
    }
}