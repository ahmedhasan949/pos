<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Subject_lib
 *
 * @author: Muhammad Umar Hayat
 * @Description: users library to perform user functions.
 */
class Event_lib
{
    /*
     * Property: 		CI
     * Description:             This will hold CI_Controller instance to perform all CI functionality
     * Type:     		Private
     */
    private $CI;
    const api_message_key = "AIzaSyDLwcHk93UVYSGtp-eDNi2RvnGDPoCDnNU";
   
    public function __construct()
    {
        $this->CI = &get_instance();
         $this->api_message_key = "AIzaSyDLwcHk93UVYSGtp-eDNi2RvnGDPoCDnNU";
        
    }

    /*
     * Mehtod:   		add_subject
     * Params: 			.....
     * Description:             Add Subject
     * Returns: 		.....
     */
     
    public function Event_notification($request = array()) {
        
        $this->CI->load->model('Event_model');  
            
        $token_records = $this->db->get('notification_token')->result_array();
        $getEvents = $this->CI->Event_model->get_upcomming_event($request['token']);
        
        $body="";
        foreach($getEvents as $value) {
            $body = "Reminder: Upcomming Event ".$value['title']." At ".$value['start_date'];    
        }
        
        
       
        //send_fcm($token,$body,$click_action,$admin_id=NULL,self::api_message_key);
         
        
        if (empty($getEvents)) {
           
            $response['result']['status'] = 'error';
            $response['result']['response'] = "No Upcomming Event Found";
            return array($response, 406);
        } 
        
        

        $response['result']['status'] = 'success';
        $response['result']['response'] = "Upcomming Event";
        $response['result']['data'] = $getEvents;
        return array($response, 200);
        
    }

    
     
    public function Events($request = array())  {   
         
        $this->CI->load->model('Event_model');
        $getEvents = $this->CI->Event_model->get_all_table('event'); 
        
        if (empty($getEvents)) {
           
            $response['result']['status'] = 'error';
            $response['result']['response'] = "Event Are not Available";
            return array($response, 406);
        } 

        $response['result']['status'] = 'success';
        $response['result']['response'] = "Event Successfully Recieved";
        $response['result']['data'] = $getEvents;
        return array($response, 200);
    } 
    
    
     public function get_event_gallery($request = array())  {
        $event_id = $request['event_id'];
        $this->CI->load->model('Event_gallery_model');
        $getGalleries = $this->CI->Event_gallery_model->get_gallery_img('event_gal_images',$event_id); 
        
        if (empty($getGalleries)) {
           
            $response['result']['status'] = 'error';
            $response['result']['response'] = "Event Galleries Are not Available";
            return array($response, 406);
        } 

        $response['result']['status'] = 'success';
        $response['result']['response'] = "Event Galleries Successfully Recieved";
        $response['result']['data'] = $getGalleries;
        return array($response, 200);
    } 
    
    
    function add_poll_answer($data) {
        
        $this->CI->load->model('Poll_model'); 
        $add_poll_answer = $this->CI->Poll_model->add_api_poll_answer($data); 
       
      
        if (!$add_poll_answer) {
           
            $response['result']['status'] = 'error';
            $response['result']['response'] = "Poll Comment Failed Submit.";
            return array($response, 406);
        } 

        $response['result']['status'] = 'success';
        $response['result']['response'] = "Poll Comment Successfully Submit.";
        return array($response, 200);
        
    }
    
    public function get_event_details($request = array()) {
        
        $this->CI->load->model('Event_model');
        
        
        $getEvents = $this->CI->Event_model->get_events($request); 
       
        
        $strt_date = $getEvents[0]['start_date'];
        $end_date = $getEvents[0]['_date'];
        list($data,$strt_time) = explode(' ',$strt_date);
        $end_date = $getEvents[0]['end_date'];
        list($date,$end_time) = explode(' ',$end_date);
        
        list($lat,$long) = explode(',', $getEvents[0]['latLng']);
        
        $getEvents[0]['start_time'] = $strt_time;
        $getEvents[0]['end_time'] = $end_time;
        
        $getEvents[0]['latitude'] = $lat;
        $getEvents[0]['longitude'] = $long;
        
        if (empty($getEvents)) {
           
            $response['result']['status'] = 'error';
            $response['result']['response'] = "Event Details Are not Available";
            return array($response, 406);
        } 

        $response['result']['status'] = 'success';
        $response['result']['response'] = "Event Details Successfully Recieved";
        $response['result']['data'] = $getEvents;
        return array($response, 200);
        
    }
    
    
    public function get_polls($request = array()) {
        
         $this->CI->load->model('Event_model');
         
         
        if (empty($request)) {
             
            $response['result']['status'] = 'error';
            $response['result']['response'] = "Event Id not Found.";
            return array($response, 406);
            exit;
        } 
         
         
        $get_event_poll = $this->CI->Event_model->get_event_poll($request['id']); 
        
        if (empty($get_event_poll)) {
           
            $response['result']['status'] = 'error';
            $response['result']['response'] = "Event Polls Are not Available";
            return array($response, 406);
        } 

        $response['result']['status'] = 'success';
        $response['result']['response'] = "Event Polls Successfully Recieved";
        $response['result']['data'] = $get_event_poll;
        return array($response, 200);
        
    } 
    
    function upload_gallery($data) {
        
        $this->CI->load->model('Event_gallery_model'); 
    
        $json = json_decode(file_get_contents('php://input'),true);
        $event_id = $json["event_id"]; //within square bracket should be same as Utils.imageName & Utils.imageList
        $imageList = $json["imageList"];
        $i = 0;
        
        
     
        $response = array();
     
        if (isset($imageList)) {
        	if (is_array($imageList)) {
        		foreach($imageList as $image) {
    	      		$decodedImage = base64_decode("$image");
    	      		
    	      		$return = file_put_contents("uploads/Event_Gallery/".$event_id."_".time().".JPG", $decodedImage);
    	      		if($return !== false) {
    	      		    
    	      		    //$this->CI->Event_gallery_model->insert_userPhoto_data();
    	      		    
        			   $response['success'] = 1;
        			   $response['message'] = "Image Uploaded Successfully";
        			} else {
        			   $response['success'] = 0;
        			   $response['message'] = "Image Uploaded Failed";
        			}
    			$i++;
    	        }
        	}
        } else{
        	$response['success'] = 0;
            $response['message'] = "List is empty.";
        }
     
        echo json_encode($response);
    }
}
