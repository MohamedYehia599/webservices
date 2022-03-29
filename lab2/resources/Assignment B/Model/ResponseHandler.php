<?php
namespace Model;
use Model\MySQLHandler;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResponseHandler
 *
 * @author webre
 */
class ResponseHandler {

    private MySQLHandler $db;  //an object from MYSQLHandler class
    private $logger;  //logger object to log response if needed (bonus)
	
	
   //$db : an object from MYSQLHandler class
    public function __construct($logger=null) {
      $this->db= new MySQLHandler("items");
    }
	

    
	public function output_response($data,$status_code,$method=null){
        http_response_code($status_code);
		header('Content-Type: application/json');
		
		echo json_encode($data);
		if (!is_string($data)){$data = "successfull";}
		$data_to_log = "User ip: " . $_SERVER['REMOTE_ADDR'] . PHP_EOL .
		    'date : ' . date("F j, Y, g:i a") . PHP_EOL .
            "Attempt: " . $method . PHP_EOL .
            "result: " . ($data) . PHP_EOL .
            "=======================================" . PHP_EOL;

        file_put_contents('log.txt', $data_to_log, FILE_APPEND);
	}
	
    
	
    
	 //***********************************************************************************************************
	//use this function to handle the GET HTTP Verb
	//$id is the resource_id	
	//***********************************************************************************************************
    public function handle_get($id) {
    $data=$this->db->get_record_by_id($id);
	if($data != null){
		$this->output_response($data,200,"GET");
	}else {
		$data = array("error"=>"there is no such record");
		$this->output_response($data,400,"GET");
	} 
	
    }
     //***********************************************************************************************************
	//use this function to handle the POST HTTP Verb
	//$params is sent params for a new resource
	//***********************************************************************************************************
    public function handle_post($params) {
        //  echo "<pre>";
		//  print_r($params);
		//  echo "<pre>";
		$bool=$this->db->save($params);
		if($bool==true){ $data = "data entered successfully";
			$this->output_response($data,200,"POST");}
		else{ $data = "you entered wrong data";
			 $this->output_response($data,400,"POST");
			}
     
    }

	//***********************************************************************************************************
	//use this function to handle the PUT HTTP Verb
	//$params is sent params for a new resource
	//$id is the resource_id
	//***********************************************************************************************************
    public function handle_put($params, $id) {

		 $bool =$this->db->update($params,$id);
		// echo $bool ;
		// die;
		if($bool == true){
			$data = "data updated successfully";
			$this->output_response($data,200,"PUT");
		}
		else{ 
			$data="there is no such record";
			$this->output_response($data,400,"PUT");
		     }
     
    }
    //***********************************************************************************************************
	//use this function to handle the GET HTTP Verb
	//$id is the resource_id
	//***********************************************************************************************************
    public function handle_delete($id) {
      $bool = $this->db->delete($id);
	  if ($bool == true){
		  $data= "data deleted successfully";
		$this->output_response($data,200,"DELETE");
	  }else {
		  $data ="there is no such record";
		  $this->output_response($data,400,"DELETE");
	}
    }

}
