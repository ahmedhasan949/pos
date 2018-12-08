<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modules extends MY_Controller {

    //private $mod_id;
    public function __construct() {
        parent::__construct();

        //Module id as per module listed in database
        //$this->mod_id = 2;
    }

    public function index() {

        if ( ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id))  && ($this->module_data['is_active'] == 1) ) {
            //$data['mod_id'] = $this->mod_id;

            $data['create_perm'] = $this->permission('create', $this->mod_id);

            if ($data['create_perm']) {
                $data['modules'] = $this->cms_modal->get_data('cms_modules', '*', 'is_active = 1 AND parent_module = 0', false);
            }

            $this->view_page('modules', 'Modules View', $data);
        } else {
            $this->error_page('modules', 'Unauthorized Access!');
        }
    }

    public function view_modules() {
        $modules = array();

        if ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) {
            $modules = $this->cms_modal->get_data('cms_modules ORDER BY sort_order ASC', '*', '', true);
        }

        //Actions populate as per user group permission
        if (!empty($modules)) {
            //add buttons to modules list data
            foreach ($modules as $i => $m) {
                $actions = '<div class="btn-group">';
                //add edit button if user has permission
                if ($this->permission('edit', $this->mod_id)) {
                    $actions .= '<a class="btn btn-warning edit_modal" id="' . $m['id'] . '"  data-toggle="modal" data-target="#modal_form" ><i class="icon_pencil-edit"></i></a>';
                }
                //add delete button if user has permission
                if ($this->permission('delete', $this->mod_id)) {
                    $actions .= '<a class="btn btn-danger delete" data-id="' . $m['id'] . '"><i class="icon_close_alt2"></i></a>';
                }
                $actions .= '</div>';
                $modules[$i] += array('actions' => $actions);
            }
        }

        $this->log_entry->entry($this->user_id, 'View Module List', 'Modules/view_modules');

        //Generate data here 
        $json_data = array(
            "draw" => intval(1),
            "recordsTotal" => count($modules),
            "recordsFiltered" => count($modules),
            "data" => $modules   // total data array
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }

    public function add_module() {
        // post[id] is submit from ajax to display the data on modal
        // module_id is for form update
        $session = $this->session->userdata();
        $post = $this->input->post();

        $post['id'] = (isset($post['id'])) ? $post['id'] : NULL;
        $data['mod_id'] = $this->mod_id;
        
        //---Permission Check ------//
        if ( !$this->permission('create', $this->mod_id) &&  $post['id'] == NULL) { 
            $this->log_entry->entry($this->user_id, "Unauthorized Access!", 'Modules/add_module');
            echo json_encode(['success' => 'no', 'msg' => 'Unauthorized Access for Create']);
            die;
        }
        
        if (!$this->permission('edit', $this->mod_id)) {
            echo json_encode(['success' => 'no', 'msg' => 'Unauthorized Access on Edit']);
            $this->log_entry->entry($this->user_id, "Unauthorized Access on Edit", 'Modules/add_module');
            die;
        }

        if ($post['id'] != NULL) {
            $module_det = $this->cms_modal->get_data('cms_modules', '*', 'id = ' . $post['id'], false);
            echo json_encode(array('success' => 'yes', 'records' => $module_det));
            exit;
        }


        //--------------------------//
        //--User-details-form-validations--//
//        $this->form_validation->set_rules('parent_module', 'Parent Module', 'required');  
//        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|numeric');
        $this->form_validation->set_rules('module_method_location', 'Method Location', 'required');
        $this->form_validation->set_rules('module_logo', 'Module Logo', 'required');
        //---------------------------------//
        //--User-login-form-details -------//
        if (isset($post['module_id']) && $post['module_id'] == NULL) {
            $this->form_validation->set_rules('module_title', 'Module Title', 'required|is_unique[cms_modules.module_title]');
            $this->form_validation->set_rules('module_name', 'Module Name', 'required|is_unique[cms_modules.module_name]');
        }


        if ($this->form_validation->run() === false) {
            echo json_encode(array('success' => 'no', 'msg' => validation_errors()));
        } else {

            $post['is_active'] = ($post['is_active'] == 1) ? 1 : 0;

            $msg = "";
            if ($post['module_id'] != NULL) {
                $id = $post['module_id'];
                unset($post['module_id']); // unset hidden value because it includes in post array.
                unset($post['id']); // unset hidden value because it includes in post array.
                unset($post['controller']); // unset hidden controller checkbox which create controller file only on Create form
                $check = $this->cms_modal->edit_data('cms_modules', $post, 'id =' . $id);
                $this->log_entry->entry($this->user_id, 'Edit Module Record', 'Modules/add_module');
                $msg = "Record Successfully Updated.";
            } else {
                unset($post['module_id']);
                $last_sort = $this->cms_modal->get_data('cms_modules order by id DESC limit 1', 'sort_order', '', false);
                $post['sort_order'] = (int) $last_sort['sort_order'] + 1;
                ($post['controller'] == 1) ? $this->create_controller($post['module_method_location']) : '';
                unset($post['controller']);
                $check = $this->cms_modal->insert_data($post, 'cms_modules');
                $this->log_entry->entry($this->user_id, 'Create Module Record', 'Modules/add_module');
                $msg = "Record Successfully Created.";
            }

            if ($check) {
                echo json_encode(array('success' => 'yes', 'msg' => $msg));
            } else {
                echo json_encode(array('success' => 'no', 'msg' => 'error Creating record'));
            }
        }
    }

    public function update_sort_order() {
        $sort_value = $this->input->post('sort_val');
        $module_id = $this->input->post('module_id');

        if ($this->permission('edit', $this->mod_id) === FALSE) {
            echo json_encode(['success' => FALSE, 'msg' => 'Unauthorized Access on Edit']);
            $this->log_entry->entry($this->user_id, "Unauthorized Access on sort!", 'Modules/update_sort_order');
            die;
        }


        $this->form_validation->set_rules('sort_val', 'Sort Order', 'required|numeric');

        if ($this->form_validation->run() === false) {
            echo json_encode(array('success' => FALSE, 'msg' => strip_tags(validation_errors())));
        } else {

            $check = $this->cms_modal->edit_data('cms_modules', array('sort_order' => $sort_value), 'id =' . $module_id);

            if ($check) {
                echo json_encode(array('success' => TRUE, 'msg' => 'Sort Order Updated Successfully'));
                $this->log_entry->entry($this->user_id, "Update Sort Module", 'Modules/update_sort_order');
            } else {
                echo json_encode(array('success' => FALSE, 'msg' => 'Some bad had happened'));
            }
        }
    }

    public function delete() {

        if (!$this->permission('delete', $this->mod_id)) { 
            $this->log_entry->entry($this->user_id, "Unauthorized Access on Delete!", 'Modules/delete');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized Access on Delete!']);
            die;
        }

        $id = $this->input->post('id');
        $check = $this->cms_modal->delete_data('cms_modules', array('id' => $id));

        if ($check) {
            echo json_encode(array('success' => 'yes'));
            $this->log_entry->entry($this->user_id, "Delete Module Record", 'Modules/delete');
        } else {
            echo json_encode(array('success' => 'no'));
        }
    }

}
