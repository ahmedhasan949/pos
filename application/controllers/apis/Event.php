<?php

/**  
 * Description of Subject
 *
 * @author      Muhammad Umar Hayat
 * @description Contacts Class For The Basic Functionality of including Syncing Contacts
 */
  
require_once APPPATH.'libraries/api/REST_Controller.php';

class Event extends REST_Controller
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
     
    public function Events_Notification_post() {   
        $data = $this->_post_args;
        try {
            $this->load->library("api/Event_lib");
            $s = new Event_lib;
            $result = $s->Event_notification($data);
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
    
    
    public function Events_post() {   
        $data = $this->_post_args;
        try {
            $this->load->library("api/Event_lib");
            $s = new Event_lib;
            $result = $s->Events($data);
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
    
    public function Gallery_get() {   
        $data = $this->_get_args;
        try {
            $this->load->library("api/Event_lib");
            $s = new Event_lib;
            $result = $s->get_event_gallery($data);
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
    
     public function EventDetail_get() {   
        $data = $this->_get_args;
        try {
            $this->load->library("api/Event_lib");
            $s = new Event_lib;
            $result = $s->get_event_details($data);
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
    
     public function Get_Poll_get() {   
        $data = $this->_get_args;
		 
        try {
            $this->load->library("api/Event_lib");
            $s = new Event_lib;
            $result = $s->get_polls($data);
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
    
    
    public function Add_PollAnswer_post() {   
        $data = $this->_post_args;
        try {
            $this->load->library("api/Event_lib");
            $s = new Event_lib;
            $result = $s->add_poll_answer($data);
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
    
    public function Upload_Gallery_post() {   
        $data = $this->_post_args;
        try {
            $this->load->library("api/Event_lib");
            $s = new Event_lib;
            $result = $s->upload_gallery($data);
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
