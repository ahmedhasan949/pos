<?php

/**  
 * Description of Subject
 *
 * @author      Muhammad Umar Hayat
 * @description Contacts Class For The Basic Functionality of including Syncing Contacts
 */
  
require_once APPPATH.'libraries/api/REST_Controller.php';

class User extends REST_Controller
{
    const api_message_key = "AIzaSyDLwcHk93UVYSGtp-eDNi2RvnGDPoCDnNU";
    
    public function __construct() {
         
        parent::__construct();
        
    }
    /*
     * Mehtod:   		add_subject
     * Params: 			.....
     * Description:             .....
     * Returns: 		.....
     */ 
     
    public function Login_post() {   
        $data = $this->_post_args;
        
        try {
            $this->load->library("api/User_lib");
            $s = new User_lib;
            $result = $s->Login_user($data);
        }
        
        catch(Exception $e){
            
            $response['result']['status']  = 'error';
            $response['result']['response']	= $e->getMessage();
            $this->response($response, $e->getCode());
        }
        header("Access-Control-Allow-Origin: *");
        $this->response($result[0], $result[1]);
    } 
    
    
    
      
    
     
}
