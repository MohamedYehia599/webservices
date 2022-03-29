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
class Weather implements Weather_Interface
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



        return $egyptian_cities;
    }

    public function get_weather($cityid)
    {
        $api = __API . $cityid . __API_key;
        // echo $api;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $api);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $data = curl_exec($ch);
            // var_dump();
            curl_close($ch);
        //  echo '<pre>';
        
        // //  print_r(json_decode($data));
        //  echo '<pre>';
            
         $data_arr = json_decode($data, true);

            return $data_arr;
        } catch (Exception $e) {
            echo "message : " . $e->getMessage();
        }
    }

    public function get_current_time()
    {
        echo date("l") . " " . date("h") . " " . date("a") . "<br>";
        echo  date("d") . "th" . " " . date("F") . " " . date("Y") . "<br>";
    }
}
