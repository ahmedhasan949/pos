<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    //private $mod_id;

    public function __construct() {
        parent::__construct();
        //Module id as per module listed in database
        // $this->mod_id = 5;
    }

    public function index() {
        
         $data['create_perm'] = FALSE;
        if ($this->permission('create', $this->mod_id)) { 
            $data['create_perm'] = TRUE;
        }
        
        
        if (($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) && ($this->module_data['is_active'] == 1)) {
            $data['mod_id'] = $this->mod_id;
            $this->view_page('users', 'Users View', $data);
        } else {
            $this->error_page('users', 'Unauthorized Access!');
        }
    }

    private function get_groups($is_del) {
        // Selected groups of each record

        $select = "cu.id, cug.group_name,cugr.group_id";
        $from = "cms_users cu";
        $from .= " LEFT JOIN cms_usr_grp_rel cugr on cugr.user_id = cu.id";
        $from .= " LEFT JOIN cms_user_groups cug on  cug.id = cugr.group_id ";
        $where = "cu.is_del = " . $is_del;

        $groups = $this->cms_modal->get_data($from, $select, $where, true);

        $user_group = array();
        foreach ($groups as $value):
            $user_group[$value['id']][] = "&nbsp;&nbsp;" . $value['group_name'];
        endforeach;

        return $user_group;
        // Selected groups of each record
    }

    public function view_users() {
        $users = array();

        $user_group = $this->get_groups(0);
        
        

        if ($this->permission('view_all', $this->mod_id)) {
            $users = $this->cms_modal->get_data('cms_users', 'id, username, DATE_FORMAT(join_date, "%e %b, %Y %l:%i %p") join_date, is_active,delete_protected', 'is_del = 0', true);
        } else if ($this->permission('view', $this->mod_id)) {
            $users = $this->cms_modal->get_data('cms_users', 'id, username, DATE_FORMAT(join_date, "%e %b, %Y %l:%i %p") join_date, is_active,delete_protected', 'user_add_by = ' . $this->user_id . ' AND is_del = 0', true);
        }


        //Actions populate as per user group permission
        if (!empty($users)) {

            //add buttons to user list data
            foreach ($users as $i => $u) {

                $actions = '<div class="btn-group">';
                //            $actions .= '<a class="btn btn-primary"><i class="icon_documents_alt"></i></a>';
                //add edit button if user has permission
                if ($this->permission('edit', $this->mod_id)) {
                    $actions .= '<a href="' . base_url('index.php/users/add_user/' . $u['id']) . '" class="btn btn-warning"><i class="mdi mdi-lead-pencil"></i></a>';
                }
                //add delete button if user has permission

                if ($this->permission('delete', $this->mod_id) && $u['delete_protected'] == 0) {
                    $actions .= '<a class="btn btn-danger delete" data-id="' . $u['id'] . '"><i class="mdi mdi-delete"></i></a>';
                }
                $actions .= '</div>';

                $users[$i]['group_name'] = $user_group[$u['id']];
                $users[$i] += array('actions' => $actions);
            }
        }

        $this->log_entry->entry($this->user_id, 'View User List', 'Users/view_users');

        $json_data = array(
            "draw" => intval(1),
            "recordsTotal" => count($users),
            "recordsFiltered" => count($users),
            "data" => $users   // total data array
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }

    public function view_trash_records() {
        $users = array();

        $user_group = $this->get_groups(1);

        if ($this->permission('view_all', $this->mod_id)) {
            $users = $this->cms_modal->get_data('cms_users', 'id, username, DATE_FORMAT(join_date, "%e %b, %Y %l:%i %p") join_date,  is_active,delete_protected', 'is_del = 1', true);
        } else if ($this->permission('view', $this->mod_id)) {
            $users = $this->cms_modal->get_data('cms_users', 'id, username, DATE_FORMAT(join_date, "%e %b, %Y %l:%i %p") join_date,  is_active,delete_protected', 'user_add_by = ' . $this->user_id . ' AND is_del = 1', true);
        }
        //Actions populate as per user group permission
        if (!empty($users)) {

            //add buttons to user list data
            foreach ($users as $i => $u) {

                $actions = '<div class="btn-group">';
                //            $actions .= '<a class="btn btn-primary"><i class="icon_documents_alt"></i></a>';
                //add edit button if user has permission
                if ($this->permission('edit', $this->mod_id)) {
                    $actions .= '<a class="btn btn-success delete_restore" title="Restore Record" alt="Restore Record" href="#" data-id="' . $u['id'] . '" data-val = "0" ><i class="mdi mdi-redo-variant"></i></a>';
                }
                //add delete button if user has permission

                if ($this->permission('delete', $this->mod_id) && $u['delete_protected'] == 0) {
                    $actions .= '<a class="btn btn-danger delete_restore" data-id="' . $u['id'] . '" data-val="1"><i class="mdi mdi-delete-forever"></i></a>';
                }
                $actions .= '</div>';

                $users[$i] += array('actions' => $actions);
                $users[$i]['group_name'] = $user_group[$u['id']];
                $this->log_entry->entry($this->user_id, 'View Trash Records', 'Users/view_trash_records');
            }
        } else {
            $users = array();
        }
        $json_data = array(
            "draw" => intval(1),
            "recordsTotal" => count($users),
            "recordsFiltered" => count($users),
            "data" => $users   // total data array
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }

    public function add_user() {

        $session = $this->session->userdata();
        $data['id'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : NULL;
        $post = $this->input->post();
        $data['mod_id'] = $this->mod_id;

        //---Permission Check ------//
        //--------------------------//
        //--User-details-form-validations--//
        $this->form_validation->set_rules('fullname', 'Fullname', 'required');
        $this->form_validation->set_rules('designation', 'Designation', 'required');

        $this->form_validation->set_rules('contact', 'Contact', 'required|numeric');
        $this->form_validation->set_rules('group[]', 'Group', 'required');
        //---------------------------------//
        //--User-login-form-details -------//
        if ($data['id'] == NULL) {
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[cms_users.email]|valid_email');
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[cms_users.username]');
        }
        // $this->form_validation->set_rules('password', 'Password', 'required');
        //  $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        //---------------------------------//


        if ($this->form_validation->run() === false) { 

            $select = "cugr.* , g.group_name";
            $from = "cms_usr_grp_rel cugr INNER JOIN cms_user_groups g ON g.id = cugr.group_id";
            $where = "cugr.user_id = " . $session['user_id'] . " ORDER BY cugr.group_id ASC";
            $super_admin = $this->cms_modal->get_data($from, $select, $where, true);

            $check_super_admin = array();
            foreach ($super_admin as $grop_val) {
                $check_super_admin[] = $grop_val['group_name'];
            }

//            $user_group = $this->cms_modal->get_data('cms_users cm INNER JOIN cms_user_groups cug ON cug.id = cm.user_group', 'cm.id, cm.fullname, DATE_FORMAT(cm.join_date, "%e %b, %Y %l:%i %p") join_date, cm.designation, cug.group_name, cm.is_active', 'cm.is_active = 1 and cm.id = ' . $session['user_id']);
            $data['super_admin_perm'] = (in_array('Super Admin', $check_super_admin)) ? TRUE : FALSE;
            $data['groups'] = $this->cms_modal->get_data('cms_user_groups', '*', 'is_active = 1', true);

            if ($data['id'] != NULL) {
                $select = "cugr.* , g.group_name";
                $from = "cms_usr_grp_rel cugr INNER JOIN cms_user_groups g ON g.id = cugr.group_id";
                $where = "cugr.user_id = " . $data['id'] . " ORDER BY cugr.group_id ASC";
                $grop_rel = $this->cms_modal->get_data($from, $select, $where, true);

                $data['user_det'] = $this->cms_modal->get_data('cms_users', '*', 'id=' . $data['id']);
                // {{ This section select record from cms_usr_grp_rel against user_id and store the group id in $selected_group array {{
                $selected_group = array();
                foreach ($grop_rel as $value) {
                    $selected_group[] = $value['group_id'];
                }
                $data['selected_grop'] = $selected_group;
                // }} This section select record from cms_usr_grp_rel against user_id and store the group id in $selected_group array }}
            }


            if ($this->permission('create', $this->mod_id)) {
                $this->view_page('add_user', 'Add User', $data);
            } else if ($this->permission('edit', $this->mod_id)) {
                $this->view_page('add_user', 'Edit User', $data);
            } else {
                $this->error_page('users/add_user', 'Unauthorized Access!');
            }
        } else {
            $record_arr = array();

            if (isset($post['username'])) {
                $record_arr['username'] = $post['username'];
            }

            if (!empty($post['password'])) {
                $record_arr['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            }

            if ($_FILES['profile_picture']['name']) {
                $last_id = $this->cms_modal->get_data('cms_users ORDER BY id DESC LIMIT 1', 'id', '', false);
//                $new_id = ($data['id'] == NULL) ? (int) $last_id['id'] + 1 : $data['id'];
//                $path = pathinfo($_FILES['profile_picture']['name']);
                $filename = time() . '_' . $_FILES['profile_picture']['name'];
                $record_arr['profile_picture'] = 'uploads/User_Profile/' . $filename;
                $this->upload($filename);
            }

            $record_arr['fullname'] = $post['fullname'];
            $record_arr['designation'] = $post['designation'];
            $record_arr['email'] = $post['email'];
            $record_arr['contact_no'] = $post['contact'];
            $record_arr['user_add_by'] = $session['user_id'];
            $record_arr['is_active'] = ($post['is_active'] == 1) ? 1 : 0;
            $multi_insert_arr = array();

            if ($data['id'] != NULL) {
                $group_user_id = $data['id'];
                ($this->Cms_modal->edit_data('cms_users', $record_arr, 'id =' . $data['id'])) ? $this->session->set_flashdata('success_message', 'Record Successfully Update') : FALSE;
                // Delete all cms_usr_grp_rel record where id is user_id (Delete Insert)
                (!empty($post['group'])) ? $this->cms_modal->delete_data('cms_usr_grp_rel', 'user_id = ' . $data['id']) : '';
                $this->log_entry->entry($this->user_id, 'Edit User', 'Users/add_user');
            } else {
                $recent_id = $this->Cms_modal->insert_data($record_arr, 'cms_users');
                $group_user_id = $recent_id;
                (!empty($recent_id)) ? $this->session->set_flashdata('success_message', 'Record Successfully Inserted') : '0';
                $this->log_entry->entry($this->user_id, 'Create User and Create Multi Roles', 'Users/add_user');
            }


            if (!empty($post['group'])) { // insert multi select group record in cms_usr_grp_rel table
                foreach ($post['group'] as $value) {
                    $multi_insert_arr[] = array('user_id' => $group_user_id, 'group_id' => $value);
                }
                $this->cms_modal->batch_insert($multi_insert_arr, 'cms_usr_grp_rel');
            } // insert multi select group record in cms_usr_grp_rel table


            header("Location: " . base_url('index.php/users'));
        }
    }

    public function delete() {
        $id = $this->input->post('id');
        $session = $this->session->userdata();
        
        if (!$this->permission('delete', $this->mod_id)) {
            $this->log_entry->entry($this->user_id, "Unauthorized Access!", $this->controller . '/delete');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized Access for Delete!']);
            die;
        }

        if ($session['user_id'] == $id) {
            echo json_encode(array('success' => false, 'msg' => 'You cannot delete yourself while you are login.'));
        } else {
            echo ( $this->Cms_modal->edit_data('cms_users', array('is_del' => 1), 'id =' . $id) ) ? json_encode(array('success' => TRUE)) : json_encode(array('success' => FALSE));
            $this->log_entry->entry($this->user_id, 'Trash User', 'Users/delete');
        }
    }

    public function delete_restore() {
        $post = $this->input->post();
        $id = $post['id'];
        $deleted = $post['deleted'];
        
         if (!$this->permission('delete', $this->mod_id)) {
            $this->log_entry->entry($this->user_id, "Unauthorized Access!", $this->controller . '/delete');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized Access for Delete!']);
            die;
        }

        if ($deleted == 1) {
            echo ($this->cms_modal->delete_data('cms_users', 'id=' . $id)) ? json_encode(array('success' => 'yes', 'msg' => 'Record Parmanently Deleted')) : json_encode(array('success' => 'no'));
            $this->log_entry->entry($this->user_id, 'Parmanently Delete User', 'Users/delete_restore');
        } else if ($deleted == 0) {
            echo ($this->cms_modal->edit_data('cms_users', array('is_del' => 0), 'id =' . $id)) ? json_encode(array('success' => 'yes', 'msg' => 'Record restored')) : json_encode(array('success' => 'no'));
            $this->log_entry->entry($this->user_id, 'Restore Deleted User', 'Users/delete_restore');
        }
    }

    function upload($filename) {

        $config['upload_path'] = './uploads/User_Profile';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['overwrite'] = TRUE;
        $new_name = $filename;
        $config['file_name'] = $new_name;
//            $config['max_size'] = 1000;
        $config['max_width'] = 344;
        $config['max_height'] = 160;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('profile_picture')) {
            $this->session->set_flashdata('error_message', strip_tags($this->upload->display_errors()));
            redirect('users');
            exit;
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

}

?>