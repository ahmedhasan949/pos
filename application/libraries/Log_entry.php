<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_entry {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('session');
        $this->CI->load->model('cms_modal');
    }

    public function Entry($id, $action, $link) {
        $insert_data = array(
            'user_id' => $id,
            'log_action' => $action,
            'log_link' => $link
        );
        $this->CI->cms_modal->insert_data($insert_data, 'cms_user_log');
    }

}
