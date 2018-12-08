<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_group extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
//        $user_group = $this->cms_modal->get_data('cms_users cm INNER JOIN cms_user_groups cug ON cug.id = cm.user_group', 'count(cm.id) as no_users,  cug.group_name, cm.is_active', 'cm.is_active = 1 and cm.id = ' . $this->user_id);
        $data['create_perm'] = FALSE;
        if ($this->permission('create', $this->mod_id)) {
            $data['create_perm'] = TRUE;
        }
        
        if ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) {
            $data['mod_id'] = $this->mod_id;
            $this->view_page('user_group', 'user_group View', $data);
        } else {
            $this->error_page('user_group', 'Unauthorized Access!');
        }
    }

    public function view_group() {
        $group = array();

        if ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) {

            $select = "cug.*,count(cugr.id) as no_user";
            $from = "cms_user_groups cug LEFT JOIN cms_usr_grp_rel cugr on cugr.group_id = cug.id GROUP BY cug.group_name";
            $where = "";
            $group = $this->cms_modal->get_data($from, $select, $where, true);
        }

        //Actions populate as per user group permission
        if (!empty($group)) {
            //add buttons to modules list data
            foreach ($group as $i => $m) {
                $actions = '<div class="btn-group">';
                //add edit button if user has permission
                if ($this->permission('edit', $this->mod_id)) {
                    $actions .= '<a href="' . base_url('index.php/User_group/add_edit_user/' . $m['id']) . '" class="btn btn-warning"><i class="mdi mdi-lead-pencil"></i></a>';
                }
                //add delete button if user has permission

                if ($this->permission('delete', $this->mod_id)) {
                    $actions .= '<a class="btn btn-danger delete" data-id="' . $m['id'] . '"><i class="mdi mdi-delete"></i></a>';
                }
                $actions .= '</div>';
                $group[$i] += array('actions' => $actions);
            }
        }


        $this->log_entry->entry($this->user_id, 'View Group List', 'User_group/view_group');

        //Generate data here 
        $json_data = array(
            "draw" => intval(1),
            "recordsTotal" => count($group),
            "recordsFiltered" => count($group),
            "data" => $group   // total data array
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }

    public function add_edit_user() {

        $session = $this->session->userdata();
        $data['edit_id'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : NULL;
        $data['mod_id'] = $this->mod_id;
        $post = $this->input->post();


        //---Permission Check ------//
        //--------------------------//

        $this->form_validation->set_rules('group_name', 'Group Name', 'required');
        if ($data['edit_id'] == NULL) {
            $this->form_validation->set_rules('group_name', 'Group Name', 'is_unique[cms_user_groups.group_name]');
        }


        if ($this->form_validation->run() === false) {

            if ($data['edit_id'] != NULL) {
                $data['user_grp_det'] = $this->cms_modal->get_data('cms_user_groups', '*', 'id=' . $data['edit_id']);
            }

            if ($this->permission('create', $this->mod_id)) {
                $this->view_page('user_group_form', 'Add User Group', $data);
            } else if ($this->permission('edit', $this->mod_id)) {
                $this->view_page('user_group_form', 'Add User Group', $data);
            } else {
                $this->error_page('user_group_form', 'Unauthorized Access!');
            }
        } else {
            $post['is_active'] = ($post['is_active'] == 1) ? 1 : 0;
            if ($data['edit_id'] != NULL) {
                ($this->Cms_modal->edit_data('cms_user_groups', $post, 'id =' . $data['edit_id'])) ? $this->session->set_flashdata('success_message', 'Record Successfully Update') : FALSE;
                $this->log_entry->entry($this->user_id, 'Edit User Group', 'Users/add_user');
            } else {
                ($this->Cms_modal->insert_data($post, 'cms_user_groups')) ? $this->session->set_flashdata('success_message', 'Record Successfully Inserted') : '0';
                $this->log_entry->entry($this->user_id, 'Create User Group', 'Users/add_user');
            }
            header("Location: " . base_url('index.php/user_group'));
        }
    }

    public function delete() {
        $id = $this->input->post('id');

        $select = "cug.*,count(cugr.id) as no_user";
        $from = "cms_user_groups cug LEFT JOIN cms_usr_grp_rel cugr on cugr.group_id = cug.id";
        $where = "cug.id = " . $id . " GROUP BY cug.group_name";
        $group = $this->cms_modal->get_data($from, $select, $where, false);


        if (!$this->permission('delete', $this->mod_id)) {
            $this->error_page('User_group/delete', 'Unauthorized Access!');
            $this->log_entry->entry($this->user_id, "Unauthorized Access!", 'User_group/delete');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized Access for Delete!']);
            die;
        }

        if ($group['no_user'] > 0) {
            $this->log_entry->entry($this->user_id, "Can't Delete Group Contain User", 'User_group/delete');
            echo json_encode(['success' => false, 'msg' => "Can't Delete Group which Contain Users."]);
            exit;
        }

        $check = $this->cms_modal->delete_data('cms_user_groups', array('id' => $id));

        if ($check) {
            echo json_encode(array('success' => 'yes'));
            $this->log_entry->entry($this->user_id, "Delete User Group", 'User_group/delete');
        } else {
            echo json_encode(array('success' => 'no'));
        }
    }

}

?>