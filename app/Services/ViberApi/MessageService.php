<?php


namespace App\Services\ViberApi;


use App\Services\ViberBot\UserService;
use App\Services\WeatherApi\OpenWeatherMapService;

class MessageService extends BaseService
{
    private $viber;

    private $keyboard = [
        [
            "Columns" => 3,
            "BgColor" => "#808B96",
            "ActionType" => "reply",
            "ActionBody" => "1_hour",
            "Text" => "1 hour",
            "TextSize" => "regular"
        ],
        [
            "Columns" => 3,
            "BgColor" => "#808B96",
            "ActionType" => "reply",
            "ActionBody" => "6_hours",
            "Text" => "6 hours",
            "TextSize" => "regular"
        ],
        [
            "Columns" => 3,
            "BgColor" => "#808B96",
            "ActionType" => "reply",
            "ActionBody" => "12_hours",
            "Text" => "12 hours",
            "TextSize" => "regular"
        ],
        [
            "Columns" => 3,
            "BgColor" => "#808B96",
            "ActionType" => "reply",
            "ActionBody" => "24_hours",
            "Text" => "24 hours",
            "TextSize" => "regular"
        ]
    ];

    /**
     * @var UserService
     */
    private $user;

    public function __construct($viber){
        parent::__construct();
        $this->endpoint = config('viber.message_url');
        $this->viber = $viber;
        $this->user = new UserService($this->viber);
    }

    public function dispatchEvents()
    {
        if( !isset($this->viber->event) ) abort(500);
        switch ($this->viber->event) {
            case "conversation_started":
                if( !isset($this->viber->user->id) ) abort(500);
                if( !isset($this->viber->user->name) ) abort(500);
                $this->askUserCity();
                break;
            case "message":
                if( !isset($this->viber->sender->id) ) abort(500);
                if( !isset($this->viber->message->text) ) abort(500);
                $this->dispatchMessageEvent();
                break;
            case "unsubscribed":
                if( !isset($this->viber->user_id) ) abort(500);
                $this->user->unsubscribe();
                break;
        }
        return $this;
    }

    private function askUserCity()
    {
        $message['receiver'] = $this->viber->user->id;
        $message['type'] = 'text';
        $message['text'] = "Hello, {$this->viber->user->name}!\r\nWelcome to Weather bot!\r\nEnter your city, please:";
        $this->setMessage($message);
    }

    private function dispatchMessageEvent()
    {
        switch ($this->viber->message->text) {
            case "1_hour":
                $this->dispatchWeatherUpdateInterval(1);
                break;
            case "6_hours":
                $this->dispatchWeatherUpdateInterval(6);
                break;
            case "12_hours":
                $this->dispatchWeatherUpdateInterval(12);
                break;
            case "24_hours":
                $this->dispatchWeatherUpdateInterval(24);
                break;
            default:
                $this->dispatchUserCity();
                break;
        }
    }

    private function dispatchWeatherUpdateInterval($interval)
    {
        $this->user->setUpdateInterval($interval);
        $this->prepareMessage();
    }

    private function askWeatherUpdateInterval()
    {
        $message['receiver'] = $this->viber->sender->id;
        $message['type'] = 'text';
        $city = $this->user->getUserCity();
        $message['text'] = "The bot will show the weather for $city using service OpenWeather (https://openweathermap.org/).\r\nPlease choose how often would you like to receive weather information?";
        $message['keyboard'] = [
            "Type" => "keyboard",
            "Revision" => 1,
            "DefaultHeight" => false,
            "Buttons" => $this->keyboard
        ];
        $this->setMessage($message);
    }

    private function dispatchUserCity()
    {
        $userCity = $this->user->setupUserWithCity();
        if( !$userCity ){
            $this->askAgainUserCity();
        }else{
            $this->askWeatherUpdateInterval();
        }
    }

    private function askAgainUserCity()
    {
        $message['receiver'] = $this->viber->sender->id;
        $message['type'] = 'text';
        $message['text'] = "Sorry! City {$this->viber->message->text} not found.\r\nEnter correct city name or enter another city, please:";
        $this->setMessage($message);
    }

    private function prepareMessage()
    {
        $message['receiver'] = $this->viber->sender->id;
        $message['type'] = 'text';

        $weather = new OpenWeatherMapService();
        $city = $this->user->getUserCity();
        $message['text'] = $weather->prepareRequestWithCity($city)
                                   ->performApiRequest()
                                   ->renderOutput();

        $this->setMessage($message);
    }
}