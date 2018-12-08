<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends MY_Controller {

    //private $mod_id;

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $data['create_perm'] = FALSE;
        if ($this->permission('create', $this->mod_id)) {
            $data['create_perm'] = TRUE;
        }

        if (($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) && ($this->module_data['is_active'] == 1)) {
            $data['mod_id'] = $this->mod_id;
            $this->view_page('inventory_list', 'Product View', $data);
        } else {
            $this->error_page('inventory_list', 'Unauthorized Access!');
        }
    }

    public function view_list() {
        $users = array();


        if ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) {

            $select = "i.*, p.product_name ";
            $from = "inventory i ";
            $from .= "INNER JOIN products p ON p.id = i.product_id ";
            $where = "";

            $users = $this->cms_modal->get_data($from, $select, $where, true);
        }


        //Actions populate as per user group permission
        if (!empty($users)) {

            //add buttons to user list data
            foreach ($users as $i => $u) {

                $actions = '<div class="btn-group">';
                //            $actions .= '<a class="btn btn-primary"><i class="icon_documents_alt"></i></a>';
                //add edit button if user has permission
                if ($this->permission('edit', $this->mod_id)) {
                    $actions .= '<a href="' . base_url('index.php/inventory/add_edit/' . $u['id']) . '" class="btn btn-warning"><i class="mdi mdi-lead-pencil"></i></a>';
                }
                //add delete button if user has permission

                if ($this->permission('delete', $this->mod_id)) {
                    $actions .= '<a class="btn btn-danger delete" data-id="' . $u['id'] . '"><i class="mdi mdi-delete"></i></a>';
                }
                $actions .= '</div>';

                $users[$i] += array('actions' => $actions);
            }
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

    public function add_edit() {

        $session = $this->session->userdata();
        $data['id'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : NULL;
        $post = $this->input->post();
        $data['mod_id'] = $this->mod_id;

        //---Permission Check ------//
        //--------------------------//
        //--User-details-form-validations--//
        $this->form_validation->set_rules('product_id', 'Product Name', 'required');
        $this->form_validation->set_rules('purchase_qty', 'Quantity', 'required'); 
        $this->form_validation->set_rules('purchase_price', 'Price', 'required'); 


        if ($this->form_validation->run() === false) {
 
            $data['products'] = $this->cms_modal->get_data('products', '*', '', true);
             if ($data['id'] != NULL) {
                $data['user_det'] = $this->cms_modal->get_data('inventory', '*', 'id=' . $data['id']);
            }

            if ($this->permission('create', $this->mod_id)) {
                $this->view_page('inventory_form', 'Add User', $data);
            } else if ($this->permission('edit', $this->mod_id)) {
                $this->view_page('inventory_form', 'Edit User', $data);
            } else {
                $this->error_page($this->uri->segment(1).'/inventory_form', 'Unauthorized Access!');
            }
        } else {
            $record_arr = array();   

            $record_arr['product_id'] = $post['product_id'];
            $record_arr['purchase_qty'] = $post['purchase_qty'];
            $record_arr['purchase_price'] = $post['purchase_price']; 

            if ($data['id'] != NULL) {

                $group_user_id = $data['id'];
                ($this->Cms_modal->edit_data('inventory', $record_arr, 'id =' . $data['id'])) ? $this->session->set_flashdata('success_message', 'Record Successfully Update') : FALSE;
                $this->session->set_flashdata('success_message', 'Record Successfully Updated');
                
            } else {

                $recent_id = $this->Cms_modal->insert_data($record_arr, 'inventory');
                $group_user_id = $recent_id;
                $this->session->set_flashdata('success_message', 'Record Successfully Inserted');
            } 

            header("Location: " . base_url('index.php/inventory'));
        }
    }

  public function delete() {
        $id = $this->input->post('id'); 

        if (!$this->permission('delete', $this->mod_id)) {
            $this->error_page($this->uri->segment(1).'/delete', 'Unauthorized Access!'); 
            echo json_encode(['success' => false, 'msg' => 'Unauthorized Access for Delete!']);
            die;
        }  

        $check = $this->cms_modal->delete_data('inventory', array('id' => $id));

        if ($check) {
            echo json_encode(array('success' => 'yes'));
            $this->log_entry->entry($this->user_id, "Delete User Group", $this->uri->segment(1).'/delete');
        } else {
            echo json_encode(array('success' => 'no'));
        }
    }

    

}

?>