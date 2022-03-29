<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Weather
 *
 * @author webre
 */
class GuzzleWeather implements Weather_Interface
{

    //put your code here
    private $url;

    public function __construct()
    {
    }

    public function get_cities()
    {

        $cities_json = file_get_contents(__CITIES_FILE);
        $cities_array = json_decode($cities_json, true);
        $egyptian_cities = array();

        foreach ($cities_array as $key => $value) {
            foreach ($value as $ke => $val) {
                if ($ke === "country" && $val === "EG") {
                    array_push($egyptian_cities, $cities_array[$key]);
                }
            }
        }


        //  var_dump($egyptian_cities);
        return $egyptian_cities;
    }

    public function get_weather($cityid)
    {
        $this->url = __API . $cityid . __API_key;
        $client = new GuzzleHttp\Client();
        $response = $client->get($this->url);
        return json_decode($response->getBody());
    }

    public function get_current_time()
    {
        echo date("l") . " " . date("h") . " " . date("a") . "<br>";
        echo  date("d") . "th" . " " . date("F") . " " . date("Y") . "<br>";
    }
}
