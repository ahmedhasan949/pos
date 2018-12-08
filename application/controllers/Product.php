<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

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
            $this->view_page('product_list', 'Product View', $data);
        } else {
            $this->error_page('product_list', 'Unauthorized Access!');
        }
    }

    public function view_list() {
        $users = array();


        if ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) {

            $select = "p.*, c.category_name as category";
            $from = "products p ";
            $from .= "INNER JOIN product_category c ON c.id = p.product_category order by p.id desc";
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
                    $actions .= '<a href="' . base_url('index.php/product/add_edit/' . $u['id']) . '" class="btn btn-warning"><i class="mdi mdi-lead-pencil"></i></a>';
                }
                //add delete button if user has permission

                if ($this->permission('delete', $this->mod_id)) {
                    $actions .= '<a class="btn btn-danger delete" data-id="' . $u['id'] . '"><i class="mdi mdi-delete"></i></a>';
                }
                $actions .= '</div>';

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

    public function add_edit() {

        $session = $this->session->userdata();
        $data['id'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : NULL;
        $post = $this->input->post();
        $data['mod_id'] = $this->mod_id;

        //---Permission Check ------//
        //--------------------------//
        //--User-details-form-validations--//
        $this->form_validation->set_rules('product_name', 'Product_name', 'required');
        if($data['id'] == NULL) {
         $this->form_validation->set_rules('product_name', 'Product_name', 'is_unique[products.product_name]');
        }
      
        $this->form_validation->set_rules('product_category', 'Category', 'required');
        $this->form_validation->set_rules('product_sell_price', 'Price', 'required'); 


        if ($this->form_validation->run() === false) {

            $data['category'] = $this->cms_modal->get_data('product_category', '*', '', true);
            if ($data['id'] != NULL) {
                $data['user_det'] = $this->cms_modal->get_data('products', '*', 'id=' . $data['id']);
            }

            if ($this->permission('create', $this->mod_id)) {
                $this->view_page('product_form', 'Add User', $data);
            } else if ($this->permission('edit', $this->mod_id)) {
                $this->view_page('product_form', 'Edit User', $data);
            } else {
                $this->error_page('product/product_form', 'Unauthorized Access!');
            }
        } else {
            $record_arr = array();

            if ($_FILES['product_picture']['name']) {
                $filename = time() . '_' . $_FILES['product_picture']['name'];
                $record_arr['product_picture'] = 'uploads/Product_Image/' . $filename;
               $this->upload($filename); 
            }

            $record_arr['product_name'] = $post['product_name'];
            $record_arr['product_category'] = $post['product_category'];
            $record_arr['product_sell_price'] = $post['product_sell_price'];



            if ($data['id'] != NULL) {

                $group_user_id = $data['id'];
                ($this->Cms_modal->edit_data('products', $record_arr, 'id =' . $data['id'])) ? $this->session->set_flashdata('success_message', 'Record Successfully Update') : FALSE;
                $this->session->set_flashdata('success_message', 'Record Successfully Updated');
            } else {

                $recent_id = $this->Cms_modal->insert_data($record_arr, 'products');
                $group_user_id = $recent_id;
                $this->session->set_flashdata('success_message', 'Record Successfully Inserted');
            }

            header("Location: " . base_url('index.php/product'));
        }
    }

    public function delete() {
        $id = $this->input->post('id');

        if (!$this->permission('delete', $this->mod_id)) {
            $this->error_page('User_group/delete', 'Unauthorized Access!');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized Access for Delete!']);
            die;
        }

        $check = $this->cms_modal->delete_data('products', array('id' => $id));

        if ($check) {
            echo json_encode(array('success' => 'yes'));
            $this->log_entry->entry($this->user_id, "Delete User Group", 'User_group/delete');
        } else {
            echo json_encode(array('success' => 'no'));
        }
    }

    function upload($filename) {

        $config['upload_path'] = './uploads/Product_Image';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['overwrite'] = TRUE;
        $new_name = $filename;
        $config['file_name'] = $new_name;
//            $config['max_size'] = 1000;
        $config['max_width'] = 200;
        $config['max_height'] = 200;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('product_picture')) {
            $this->session->set_flashdata('error_message', strip_tags($this->upload->display_errors())); 
            header("Refresh:0"); 
            exit;
        } else {
            return array('upload_data' => $this->upload->data());
        }
    }

   

}

?>