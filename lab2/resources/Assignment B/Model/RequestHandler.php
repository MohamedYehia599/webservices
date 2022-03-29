<?php

namespace Model;

use Model\ResponseHandler;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RestfulHelper
 *
 * @author webre
 */
class RequestHandler
{
    private $__method;
    private $__parameters = array();
    private $__resource;
    private $__resource_id;
    private $__allowed_methods = array("GET", "POST", "DELETE", "PUT");
    private  ResponseHandler  $response;

    function get__method()
    {
        return $this->__method;
    }

    function get__parameters()
    {
        return $this->__parameters;
    }

    function get__resource()
    {
        return $this->__resource;
    }

    function get__resource_id()
    {
        return $this->__resource_id;
    }




    public function __construct()
    {
        $this->response = new ResponseHandler();
        $this->scan();
    }

    //***********************************************************************************************************
    //this function should output or return the request elements (resource, method, parameters and resource id)
    //if $output is false the function should returns otherwise it should echo the response in JSON formats
    //***********************************************************************************************************
    public function scan($output = true)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        $request = explode("/", $url);
        $resource = isset($request[6]) ? $request[6] : "";
        $this->__resource_id = isset($request[7]) && is_numeric($request[7]) ? $request[7] : "";
        if ($method == "POST" || $method == "PUT") {
            
            $par_json=file_get_contents("php://input");
            $parameters =json_decode($par_json,true);
            if (empty($parameters)){
                $this->response->output_response( "you didnt enter any value",400);
                die;
            }
             foreach ($parameters as $key => $value) {
                 $this->__parameters[$key] = $value;
             }
        }


        $this->validate($method, $resource);
    }
    //***********************************************************************************************************
    //this function should validate the request 
    //if $output is false the function should returns the result otherwise it should echo the results in JSON formats
    //$correct_resource : The resource which the service should accepts, "items" in this example. 
    //***********************************************************************************************************
    public function validate($method, $correct_resource, $output = true)
    {
        if (in_array($method, $this->__allowed_methods)) {
            $this->__method = $method;
        } else {
            $this->response->output_response( "you entered wrong method",400);
            return;
        }
        if (strtolower($correct_resource) === 'items') {
            $this->__resource = $correct_resource;
        } else {
            $this->response->output_response( "you entered wrong resource",400);
            return;
        }

       
        if ($this->__method != null && $this->__resource != null) {
            
            switch ($this->__method) {
                case "POST":
                    $this->response->handle_post($this->__parameters);
                    break;
                case "GET" :
                    $this->response->handle_get($this->__resource_id); 
                    break;
                case "DELETE" :
                    $this->response->handle_delete($this->__resource_id);
                    break;
                case "PUT" :
                    $this->response->handle_put($this->__parameters,$this->__resource_id);         
                    break;  
            }
        }
    }
}
