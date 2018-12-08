<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class System_setting extends MY_Controller {

    //private $mod_id;

    public function __construct() {
        parent::__construct();

        //Module id as per module listed in database
        // $this->mod_id = 7;
    }

//    public function load_view($data = NULL) {
//
//        $data['mod_id'] = $this->mod_id;
//        $data['system_det'] = $this->cms_modal->get_data('cms_system_settings', '*', 'id = 1', false);
//        $this->view_page('system_setting_form', 'System Setting View', $data);
//    }

    public function index() {
        if (($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) && ($this->module_data['is_active'] == 1)) {
            $data['system_det'] = $this->cms_modal->get_data('cms_system_settings', '*', 'id = 1', false);
            $this->view_page('system_setting_form', 'System Setting View', $data);
        } else {
            $this->error_page('system_setting', 'Unauthorized Access!');
        }
    }

    public function update_system_setting() {

        if ($this->permission('edit', $this->mod_id)) {

            $session = $this->session->userdata();
            $post = $this->input->post();
            //--------------------------//
            //--System-setting-form-validations--//  
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('timezone', 'Timezone', 'required');
            //---------------------------------// 

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error_message', validation_errors());
                $data['error_msg'] = $this->session->flashdata('error_message');
                $data['mod_id'] = $this->mod_id;
                $this->view_page('system_setting_form', 'System Setting View', $data);
            } else {
                if ($_FILES['company_logo']['name']) {
                    $path = pathinfo($_FILES['company_logo']['name']);
                    $post['company_logo'] = 'uploads/System_Setting/logo.'.$path['extension'];
                    $this->upload();
                }
                $check = $this->cms_modal->edit_data('cms_system_settings', $post, 'id = 1');

                if ($check) {
                    $this->session->set_flashdata('success_message', 'Successfully Updated');
                    $this->log_entry->entry($this->user_id, "Update System Setting", 'System_setting/update_system_setting');
                    redirect('system_setting');
                }
            }
        } else {
            $this->error_page('system_setting', 'Unauthorized Access on Update!');
            $this->log_entry->entry($this->user_id, "Unauthorized Access on Update", 'System_setting/update_system_setting');
        }
    }

    function upload() {

        $config['upload_path'] = './uploads/System_Setting';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['overwrite'] = TRUE;
        $new_name = 'logo';
        $config['file_name'] = $new_name;
//            $config['max_size'] = 1000;
//        $config['max_width'] = 344;
//        $config['max_height'] = 160;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('company_logo')) {
            $this->session->set_flashdata('error_message', strip_tags($this->upload->display_errors()));
            redirect('system_setting');
            exit;
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

}
