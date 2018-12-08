<?php

/**  
 * Description of Subject
 *
 * @author      Muhammad Umar Hayat
 * @description Contacts Class For The Basic Functionality of including Syncing Contacts
 */
  
require_once APPPATH.'libraries/api/REST_Controller.php';

class UserGuide extends REST_Controller
{
    /*
     * Mehtod:   		__construct
     * Params: 			.....
     * Description:             Calls parent contructor and load CI_Controller instance into $CI
     * Returns: 		.....
     */
    public function __construct() {
         
        parent::__construct();
        
    }
    /*
     * Mehtod:   		add_subject
     * Params: 			.....
     * Description:             .....
     * Returns: 		.....
     */ 
    
    public function Guide_get(){   
        
        try {
            $this->load->library("api/Guide_lib");
            $s = new Guide_lib;
            $result = $s->User_guide();
        }
        catch(Exception $e)
        {
            $response['result']['status']  = 'error';
            $response['result']['response']	= $e->getMessage();
            $this->response($response, $e->getCode());
        }
        header("Access-Control-Allow-Origin: *");
        $this->response($result[0], $result[1]);
    } 
    
    
    public function Contact_get(){   
        
        try {
            $this->load->library("api/Guide_lib");
            $s = new Guide_lib;
            $result = $s->get_contact_us();
        }
        catch(Exception $e)
        {
            $response['result']['status']  = 'error';
            $response['result']['response']	= $e->getMessage();
            $this->response($response, $e->getCode());
        }
        header("Access-Control-Allow-Origin: *");
        $this->response($result[0], $result[1]);
    }
    
    
     public function ContactSubmit_post(){   
        $data = $this->_post_args;
        try {
            $this->load->library("api/Guide_lib");
            $s = new Guide_lib;
            $result = $s->Contact_us($data);
        }
        catch(Exception $e)
        {
            $response['result']['status']  = 'error';
            $response['result']['response']	= $e->getMessage();
            $this->response($response, $e->getCode());
        }
        header("Access-Control-Allow-Origin: *");
        $this->response($result[0], $result[1]);
    } 
     
}
