<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_auth {

    protected $CI;
            
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->model('cms_modal');    
    }
	public function session_check()
    {
        if(!$this->CI->session->userdata('isLogged'))
        {
            redirect('auth/login', 'refresh');
        }    
    }
}
