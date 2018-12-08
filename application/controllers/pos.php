<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class POS extends MY_Controller {

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
            $this->view_page('pos_list', 'Product View', $data);
        } else {
            $this->error_page('pos_list', 'Unauthorized Access!');
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


                if ($this->permission('delete', $this->mod_id)) {
                    $actions .= '<a class="btn btn-danger delete" data-id="' . $u['id'] . '"><i style="color:#ffff;" class="fas fa-trash"></i></a>';
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
                $this->error_page($this->uri->segment(1) . '/inventory_form', 'Unauthorized Access!');
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
            $this->error_page($this->uri->segment(1) . '/delete', 'Unauthorized Access!');
            echo json_encode(['success' => false, 'msg' => 'Unauthorized Access for Delete!']);
            die;
        }

        $check = $this->cms_modal->delete_data('inventory', array('id' => $id));

        if ($check) {
            echo json_encode(array('success' => 'yes'));
            $this->log_entry->entry($this->user_id, "Delete User Group", $this->uri->segment(1) . '/delete');
        } else {
            echo json_encode(array('success' => 'no'));
        }
    }

    public function insert_cart() {

        $data = array();
        $product_id = $this->input->post('pos_item');

        if (empty($product_id)) {
            echo json_encode(array('success' => FALSE));
            exit;
        }

        $select = "p.*, c.category_name as category";
        $from = "products p ";
        $from .= "INNER JOIN product_category c ON c.id = p.product_category ";
        $where = "p.id = " . $product_id;

        $product = $this->cms_modal->get_data($from, $select, $where, false);

        $data = array(
            'id' => $product['id'],
            'qty' => 1,
            'price' => $product['product_sell_price'],
            'name' => $product['product_name'],
            'category' => $product['product_category']
        );


        if ($this->cart->insert($data)) {
            echo json_encode(array('success' => TRUE, 'cart_data' => $data));
        } else {
            echo json_encode(array('success' => FALSE));
        }
    }

    function empty_cart() {
        $this->cart->destroy();
    }

    public function transaction() {
        $post = $this->input->post();
//        print_r($post); die;
        $form_data = $this->unserializeForm($post['frm_data']);


        //<<<<<<<<<< INSERT INTO USER TABLE WITH CUSTOMER GROUP >>>>>>>>>>>

        $customer_info['fullname'] = $form_data['customer_name'];
        $customer_info['contact_no'] = $form_data['customer_mobile'];
        $customer_info['username'] = str_replace(' ', '_', $form_data['customer_name']);
        $customer_info['password'] = $this->generateRandomString(8);
        $customer_info['is_active'] = 1;
        $this->Cms_modal->insert_data($customer_info, 'cms_users');

        //<<<<<<<<<< INSERT INTO USER TABLE WITH CUSTOMER GROUP >>>>>>>>>>>

        $record_arr['user_id'] = $this->session->userdata('user_id');
        $record_arr['total_amount'] = $post['total_amount'];
        $record_arr['discount_amount'] = 0;
        $record_arr['customer_type'] = 1;
        $record_arr['comments'] = $post['comments'];

        $recent_id = $this->Cms_modal->insert_data($record_arr, 'transaction');



        if (!empty($post['product_ids'])) {
            foreach ($post['product_ids'] as $key => $value_product) {

                $data[$key]['transaction_id'] = $recent_id;
                $data[$key]['product_id'] = $value_product;
                $data[$key]['sale_quantity'] = $post['product_qty'][$key];
            }

            $check = $this->db->insert_batch('transaction_items', $data);
        }


        if ($check) {
            $this->cart->destroy();
            echo json_encode(array('success'=>'yes','msg' => 'Transaction Completed.'));
        } else {
            echo json_encode(array('success'=>'no','msg' => 'Error Completeing Transaction.'));
        }
    }

    public function update_cart() {
        $data = array('rowid' => $cart['rowid'],
            'price' => $cart['price'],
            'amount' => $price * $cart['qty'],
            'qty' => $cart['qty']);

        // This function update item into cart.
        $this->cart->update($data);
    }

    function unserializeForm($str) {
        $returndata = array();
        $strArray = explode("&", $str);
        $i = 0;
        foreach ($strArray as $item) {
            $array = explode("=", $item);
            $returndata[$array[0]] = $array[1];
        }

        return $returndata;
    }

    function generateRandomString($length) {
        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $charsLength = strlen($characters) - 1;
        $string = "";
        for ($i = 0; $i < $length; $i++) {
            $randNum = mt_rand(0, $charsLength);
            $string .= $characters[$randNum];
        }
        return $string;
    }

}

?>