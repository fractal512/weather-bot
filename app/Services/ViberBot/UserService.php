<?php


namespace App\Services\ViberBot;

use App\Models\ViberBotUser;
use App\Services\WeatherApi\OpenWeatherMapService;

class UserService
{
    private $userViberData;

    private $userDbData;

    public function __construct($viberData){
        $this->userViberData = $viberData;
        $this->userDbData = new ViberBotUser;
    }

    public function setUpdateInterval($interval)
    {
        $this->userDbData
             ->where('viber_user_id', $this->userViberData->sender->id)
             ->update(['interval' => $interval]);
    }

    public function setupUserWithCity()
    {
        $weather = new OpenWeatherMapService();
        $response = $weather->prepareRequestWithCity($this->userViberData->message->text)
            ->performApiRequest()
            ->getResponse();
        $decodedResponse = json_decode($response, false);

        if(isset($decodedResponse->cod) && $decodedResponse->cod == 200){
            $user = $this->userDbData->where('viber_user_id', $this->userViberData->sender->id)->first();
            if(!$user){
                $this->userDbData->viber_user_id = $this->userViberData->sender->id;
                $this->userDbData->name = $this->userViberData->sender->name;
                $this->userDbData->language = $this->userViberData->sender->language;
                $this->userDbData->country = $this->userViberData->sender->country;
                $this->userDbData->city = $decodedResponse->name;
                $this->userDbData->interval = 1;
                $this->userDbData->save();
            }else{
                $this->userDbData
                     ->where('viber_user_id', $this->userViberData->sender->id)
                     ->update(['city' => $decodedResponse->name]);
            }
            return true;
        }

        return false;
    }

    public function getUserCity()
    {
        $user = $this->userDbData
                     ->where('viber_user_id', $this->userViberData->sender->id)
                     ->first();
        return $user->getAttribute('city');
    }

    public function unsubscribe()
    {
        $user = $this->userDbData
            ->where('viber_user_id', $this->userViberData->user_id)
            ->delete();
    }
}