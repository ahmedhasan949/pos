<?php

class Categories_model extends CI_Model {
    
  
    
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
    
    function get_categories() {
        $this->db->from('product_category');
        $this->db->where('parent_id', 0);
        $query = $this->db->get();
        return $query->result_array(); 
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
    
     function customer_history_records($event_id) {
        
        $this->db->select('tr.*, ti.product_id,p.product_name');
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
