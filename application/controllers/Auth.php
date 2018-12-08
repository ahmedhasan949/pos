<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function login() {

        $is_login = $this->session->userdata('isLogged');

        if ($is_login) {
            redirect('dashboard');
            exit;
        }


        $post = $this->input->post();

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() === false) {
            $this->load->view('login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user_det = $this->cms_modal->get_data('cms_users', '*', 'username = "' . $username . '"');

            if (!empty($user_det)) {


                if (password_verify($password, $user_det['password'])) { // Checking for password authentication
                    $this->log_entry->entry($user_det['id'], 'Login', 'auth/login'); //log entry
                    $session_vars = array(//session data
                        'isLogged' => true,
                        'user_id' => $user_det['id'],
                        'fullname' => $user_det['fullname'],
                        'designation' => $user_det['designation'],
                        'profile_pic' => $user_det['profile_picture']
                    );



                    $this->session->set_userdata($session_vars);
                    redirect('dashboard');
                } else {
                    $data['err'] = array('success' => false, 'msg' => 'Incorrect Password!');
                    $this->load->view('login', $data);
                }
            } else {
                $data['err'] = array('success' => false, 'msg' => 'Username not found');
                $this->load->view('login', $data);
            }
        }
    }

    public function logout() {
        $this->log_entry->entry($this->session->user_id, 'Logout', 'auth/logout'); //log entry
        $this->Cms_modal->edit_data('cms_users', array('is_login' => 0), 'id =' . $this->session->user_id);
        $session_vars = array('isLogged', 'user_id', 'fullname', 'designation');
        $this->session->unset_userdata($session_vars);



        redirect('auth/login');
    }

    public function register() {

        $post = $this->input->post();

        $this->form_validation->set_rules('fullname', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[cms_users.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[cms_users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');


        if ($this->form_validation->run() === false) {
            echo json_encode(array('success' => 'no', 'msg' => validation_errors()));
        } else {

            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            $post['is_active'] = 1;
            $check = $this->cms_modal->insert_data($post, 'cms_users');


            $usr_grp_rel['user_id'] = $check;
            $usr_grp_rel['group_id'] = 1;
            $check = $this->cms_modal->insert_data($usr_grp_rel, 'cms_usr_grp_rel');



            if (!empty($check)) {
                echo json_encode(array('success' => 'yes', 'msg' => "Account Successfully Created"));
            } else {
                echo json_encode(array('success' => 'no', 'msg' => 'Error Creating Record'));
            }
        }
    }

}
