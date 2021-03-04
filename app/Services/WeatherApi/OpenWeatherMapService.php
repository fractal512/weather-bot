<?php


namespace App\Services\WeatherApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class OpenWeatherMapService
{
    protected $query;

    protected $endpoint;

    protected $response;

    private $appid;

    private $units;

    public function __construct()
    {
        $this->endpoint = config('openweathermap.api_url');
        $this->appid = config('openweathermap.api_key');
        $this->units = config('openweathermap.units');
    }

    public function performApiRequest()
    {
        $client = new Client();
        try {
            $response = $client->get(
                $this->endpoint . "?" . $this->query,
                [
                    'headers' => [
                        'Cache-Control' => 'no-cache'
                    ]
                ]
            );
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->response = $response->getBody()->getContents();

            return $this;
        }

        $this->response = $response->getBody()->getContents();

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function prepareRequestWithCity($city)
    {
        $args = [
            'q' => $city,
            'appid' => $this->appid,
            'units' => $this->units
        ];
        $this->query = http_build_query($args);
        return $this;
    }

    public function renderOutput()
    {
        $response = json_decode($this->response, false);

        if(isset($response->cod) && $response->cod == 200){
            $output = "Weather in {$response->name}:\r\n";
            $output .= "{$response->weather[0]->main} {$response->weather[0]->main}\r\n";
            $temperature = round($response->main->temp, 1);
            $output .= "Temperature: {$temperature}°C\r\n";
            $temperature = round($response->main->feels_like, 1);
            $output .= "Feels like: {$temperature}°C\r\n";
            $temperature = round($response->main->temp_min, 1);
            $output .= "Min. temperature: {$temperature}°C\r\n";
            $temperature = round($response->main->temp_max, 1);
            $output .= "Max. temperature: {$temperature}°C\r\n";
            $output .= "Pressure: {$response->main->pressure} hPa\r\n";
            $output .= "Humidity: {$response->main->humidity} %\r\n";
            $output .= "Visibility: {$response->visibility} m\r\n";
            $output .= "Wind: {$response->wind->speed} m/s (direction: {$response->wind->deg}°)\r\n";
            $output .= "Clouds: {$response->clouds->all}";
        }else{
            $output = "Error occurred while obtaining weather!";
        }

        //return print_r($this->response, true);
        return $output;
    }
}