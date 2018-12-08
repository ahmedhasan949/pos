<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MY_Controller {

    //  private $mod_id;
    public function __construct() {
        parent::__construct();
        //Module id as per module listed in database
        //$this->mod_id = 6;
    }

    public function index() {
        if (($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) && ($this->module_data['is_active'] == 1)) {
            $data['mod_id'] = $this->mod_id;
            $data['groups'] = $this->cms_modal->get_data('cms_user_groups', '*', 'is_active = 1', true);
            $this->view_page('permissions', 'Permissions View', $data);
        } else {
            $this->error_page('permissions', 'Unauthorized Access!');
        }
    }

    public function view_permissions() {
        $perms = array();
        $id = $this->input->post('grp_id');
        $where = "cmgp.group_id = $id";
        if ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) {
            $perms = $this->cms_modal->get_data(
                    'cms_module_group_permission cmgp 
                    INNER JOIN cms_user_groups cug ON cug.id = cmgp.group_id
                    INNER JOIN cms_modules cm ON cm.id = cmgp.module_id', 'cmgp.*, cug.group_name, cm.module_title', $where, true);
        }
        //Actions populate as per user group permission
        if (!empty($perms)) {
            //add buttons to user list data
            foreach ($perms as $i => $u) {

                $actions = '<div class="btn-group">';
                //add delete button if user has permission
                if ($this->permission('delete', $this->mod_id) && $u['protected'] == 0) {
                    $actions .= '<a class="btn btn-danger delete" data-id="' . $u['id'] . '"><i class="mdi mdi-delete"></i></a>';
                }
                $actions .= '</div>';
                $perms[$i] += array('actions' => $actions);
            }

            $this->log_entry->entry($this->user_id, "View Permission Record", 'Permissions/view_permissions');
        }

        $json_data = array(
            "draw" => intval(1),
            "recordsTotal" => count($perms),
            "recordsFiltered" => count($perms),
            "data" => $perms   // total data array
        );
        header('Content-Type: application/json');
        echo json_encode($json_data);
    }

    public function change_permission() {
        if ($this->permission('edit', $this->mod_id)) {
            $id = $this->input->post('id');
            $opr = $this->input->post('opr');
            $perm = $this->input->post('perm');
            $edit = array($perm . '_perm' => $opr);
            $whr = array('id' => $id);



            if ($this->cms_modal->edit_data('cms_module_group_permission', $edit, $whr)) {
                echo json_encode(['success' => true, 'msg' => 'Success']);
                $this->log_entry->entry($this->user_id, "Change Permission", 'Permissions/change_permission');
            } else {
                echo json_encode(['success' => false, 'msg' => 'Error!']);
            }
        } else {
            $this->log_entry->entry($this->user_id, "Unauthorized Access!", 'permissions/change_permisssion');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized!']);
        }
    }

    public function get_modules() {
        $id = $this->input->post('id');
        $inUseMods = $this->cms_modal->get_data('cms_module_group_permission', 'module_id', 'group_id = ' . $id, true);

        $modIds = array();
        if (!empty($inUseMods)) {
            foreach ($inUseMods as $m) {
                array_push($modIds, $m['module_id']);
            }
            $not_in = 'NOT IN (' . implode(',', $modIds) . ')'; // for unique module permission
        } else {
            $not_in = ''; // if there is no permission against new group , it will return all module
        }
        $newMods = $this->cms_modal->get_data('cms_modules', 'id, module_title', 'id '.$not_in, true);
        echo json_encode(['success' => true, 'data' => $newMods]);
    }

    public function add_permission() {
        $post = $this->input->post();
        if ($this->permission('create', $this->mod_id)) {
            if ($this->cms_modal->insert_data($post, 'cms_module_group_permission')) {
                echo json_encode(['success' => true, 'msg' => 'Success!']);
                $this->log_entry->entry($this->user_id, "Insert Permission", 'Permissions/add_permission');
            } else {
                echo json_encode(['success' => false, 'msg' => 'Error!']);
            }
        } else {
            $this->log_entry->entry($this->user_id, "Unauthorized Access!", 'permissions/add_permisssion');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized!']);
        }
    }

    public function delete() {
        $id = $this->input->post('id');
        $check = $this->Cms_modal->delete_data('cms_module_group_permission', array('id' => $id));

        if ($check) {
            echo json_encode(array('success' => 'yes'));
            $this->log_entry->entry($this->user_id, "Delete Permission", 'Permissions/delete');
        } else {
            echo json_encode(array('success' => 'no'));
        }
    }

}
