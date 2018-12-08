<?php

class Event_model extends CI_Model {
    
    const MEMBER = 1;
    const NON_MEMBER = 2;
    
    function __construct() {
        parent::__construct();
    }

    function get_all_table($table_name, $id = NULL) {
        $this->db->from($table_name);
      //  $this->db->where('status', 1);
        if (isset($id)) {
            $this->db->where($table_name . "_id", $id);
        }
        $query = $this->db->get();
        $data = $query->result_array();

        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }
    
    function get_upcomming_event($user_token) {
        $token_check =  $this->CI->db->get_where('notification_token',array('user_token'=>$user_token))->result_array(); 
        
        if(empty($token_check[0])) {
           $this->CI->db->insert('notification_token',array('user_token'=>$user_token));
        } 
       
        $this->db->from('event'); 
        $this->db->where('status', 1);
        $this->db->where('DATEDIFF(`start_date`,CURDATE())', 2);
        $query = $this->db->get();
        return $query->result_array();   
    }
    
    
    public function send_fcm($token,$body,$click_action,$admin_id=NULL,$API_ACCESS_KEY)
	{
	  
    $registrationIds = $token;

     $msg = array
          (
		'body' 	=> $body,
		'title'	=> 'PMLN',
             	'icon'	=> 'myicon',
              	'sound' => 'mySound',
                'click_action' => $click_action,
              	'admin_id' => strval($admin_id)
          );

	$fields = array
			(
				'to'		=> $registrationIds,
				'notification'	=> $msg,

			);
	
	
	$headers = array
			(
				'Authorization: key=' . $API_ACCESS_KEY,
				'Content-Type: application/json'
			);

		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );

		curl_close( $ch );
    //echo json_encode($result);
    
	}
    
    function get_event_poll($event_id) {
         
        $this->db->from('cms_modules'); 
        $this->db->where('is_active', 1);
        $this->db->where('id', $event_id);
        $query = $this->db->get();
        return $query->result_array();  
        
    }
    
    function get_roles($id){
        
        $this->db->select('msr.*, r.name as role_name');
        $this->db->from('member_select_role msr'); 
        $this->db->join('member_role r','r.member_role_id = msr.member_role_id','Left');
        $this->db->where('r.status', 1);
        $this->db->where('msr.member_id', $id);
      
        $query = $this->db->get();
        $data = $query->result_array();

        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    
    }

    function create_record($table_name, $post, $file_name = NULL) {

        if ($file_name) {
            $file_name = str_replace(' ', '_', $file_name);
            $post['upload_image'] = 'uploads/Event_Images/' . time() . "_" . $file_name;
        }
        
        $post['start_date'] = date('Y-m-d H:i:s', strtotime($post['start_date']));
        $post['end_date'] = date('Y-m-d H:i:s', strtotime($post['end_date']));
        
        $data = $this->db->insert($table_name, $post);

        if ($data) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }
    
     function get_likes_records($event_id) {
        
        $this->db->select('lik.*, CONCAT(me.first_name," " ,me.last_name) as member_name , me.upload_image');
        $this->db->from('likes lik'); 
        $this->db->join('member me','me.member_id = lik.user_id','Left');
         $this->db->where('lik.status', 1);
         
         if($event_id) {
            $this->db->where('lik.event_id', $event_id);
         }
      
        $query = $this->db->get();
        $data = $query->result_array();

        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
        
    } 

    function update_record($table_name, $primary_key_name, $post, $file_name, $primary_value) {
        //$this->output->enable_profiler(TRUE);

        if ($file_name !== NULL) {
            $file_name = str_replace(' ', '_', $file_name);
            $post['upload_image'] = 'uploads/Event_Images/' . time() . "_" . $file_name;
        } else {
            unset($post['upload_image']);
        }

        $post['start_date'] = date('Y-m-d H:i:s', strtotime($post['start_date']));
        $post['end_date'] = date('Y-m-d H:i:s', strtotime($post['end_date']));

        $this->db->where($primary_key_name, $primary_value);
        unset($post[$primary_key_name]);
        $data = $this->db->update($table_name, $post);

        if ($data) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function get_events($request) {
        $user_id = ($request['id']) ? $request['id'] : 0;
        $event_id = ($request['event_id']) ? $request['event_id'] : 0;
        
         //$data = $this->db->insert('likes',array('event_id' => $event_id , 'user_id'=>$user_id,'is_like' => 1)); 
        $this->db->select('e.*, l.is_like, at.checked_in');
        $this->db->from('event e'); 
        $this->db->join('likes l','l.event_id = e.event_id','Left');
        $this->db->join('attendees at','at.event_id = e.event_id','Left');
        $this->db->where('e.status', 1);
        $this->db->where('e.event_id', $event_id);
        $this->db->where('e.event_id', $event_id);
         
         if($event_id) {
            $this->db->where('lik.event_id', $event_id);
         }
      
        $query = $this->db->get();
        $data = $query->result_array();
 
         
        
        if($data) {
                  
            exit;
        } 
        
        return array();
    }

}

?>