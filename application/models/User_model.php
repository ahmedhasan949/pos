<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('cms_modal');
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

    function UserAuthLogin($request) {

        $username = $request['username'];
        $password = $request['password'];
        $role = $request['role'];
        
        $role_query = "";
        if($role == 'Salesman') {
            $role_query = "g.group_name LIKE '%Salesman%'";
        } else if ($role = "Customer") {
           $role_query = "g.group_name LIKE '%Customer%'";
        }
        
        $select = "u.*, ug.group_id,g.group_name";
        $from  = "cms_users u ";
        $from .= "LEFT JOIN cms_usr_grp_rel ug ON ug.user_id = u.id ";
        $from .= "LEFT JOIN cms_user_groups g ON g.id = ug.group_id ";
        $where = "u.username = '".$username."' AND $role_query ";
        
        $user_det = $this->cms_modal->get_data($from, $select,$where); 

        if (!empty($user_det)) {  
            if (password_verify($password, $user_det['password'])) {  
                return $user_det;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function get_upcomming_event($user_token) {
        $token_check = $this->CI->db->get_where('notification_token', array('user_token' => $user_token))->result_array();

        if (empty($token_check[0])) {
            $this->CI->db->insert('notification_token', array('user_token' => $user_token));
        }

        $this->db->from('event');
        $this->db->where('status', 1);
        $this->db->where('DATEDIFF(`start_date`,CURDATE())', 2);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_event_poll($event_id) {

        $this->db->from('cms_modules');
        $this->db->where('is_active', 1);
        $this->db->where('id', $event_id);
        $query = $this->db->get();
        return $query->result_array();
    }

}

?>