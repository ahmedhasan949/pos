<?php

/**  
 * Description of Subject
 *
 * @author      Muhammad Umar Hayat
 * @description Contacts Class For The Basic Functionality of including Syncing Contacts
 */
  
require_once APPPATH.'libraries/api/REST_Controller.php';

class Categories extends REST_Controller
{
    const api_message_key = "AIzaSyDLwcHk93UVYSGtp-eDNi2RvnGDPoCDnNU";
    
    public function __construct() {
         
        parent::__construct();
        
    }
     
    
    public function Categories_all_get() {   
        $data = $this->_get_args;
        
        try {
            $this->load->library("api/Categories_lib");
            $s = new Categories_lib;
            $result = $s->Categories($data);
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
