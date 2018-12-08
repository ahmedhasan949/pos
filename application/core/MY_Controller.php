<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    var $user_id;
    protected $mod_id;

    public function __construct() {
        parent::__construct();
        $this->user_auth->session_check();
        $system = $this->cms_modal->get_data('cms_system_settings');
        $this->user_id = $this->session->user_id;
        date_default_timezone_set($system['timezone']);        
        $module_data = $this->cms_modal->get_data('cms_modules', '*', "module_method_location = '" . strtolower($this->uri->segment(1)) . "'");
        
        $this->module_data = $module_data;
        $this->mod_id = $module_data['id'];
    }

    public function view_page($path, $action, $data = '') {
        //--Company Details-------------//
        $system = $this->cms_modal->get_data('cms_system_settings');
        $user = $this->cms_modal->get_data('cms_users', "*", 'id=' . $this->user_id, false);

        $data['company_name'] = $system['company_name'];
        $data['company_logo'] = $system['company_logo'];
        $data['profile_pic'] = $user['profile_picture'];
        //------------------------------//
        //--Modules to be loaded starts here--//

        $ug = $this->cms_modal->get_data('cms_usr_grp_rel', 'group_id', 'user_id = ' . $this->user_id, true); //getting user group id
        $grp = [];
        foreach ($ug as $grp_value) {
            array_push($grp, $grp_value['group_id']);
        }

        $select = "cm.*,cmgp.view_perm,cmgp.view_all_perm,cmgp.group_id";
        $from = "cms_module_group_permission cmgp INNER JOIN cms_modules cm ON cm.id = cmgp.module_id";
        $where = 'cmgp.group_id IN (' . implode(',', $grp) . ') AND cm.parent_module = 0 AND cm.is_active = 1 AND (cmgp.view_all_perm = 1 OR cmgp.view_perm = 1)'
                . 'GROUP BY cm.module_name '
                . 'ORDER BY cm.sort_order ASC';
        $mods = $this->cms_modal->get_data($from, $select, $where, true);


        $data['mods'] = $mods;
        //------------------------------//
        //--Extracting-module-name------//  
        //$mod_id = $data['mod_id'];
        $data['module_det'] = $this->cms_modal->get_data('cms_modules', '*', 'id = ' . $this->mod_id, false);       
        $data['mod_name'] = $data['module_det']['module_name'];
        //------------------------------//
        //--Log entry ------------------//
        $this->log_entry->entry($this->user_id, $action, $path);
        //------------------------------//
        $this->load->view('header', $data);
        $this->load->view($path, $data);
        $this->load->view('footer');
    }

    protected function error_page($path, $msg) {
        //--Company Details-------------//
        $system = $this->cms_modal->get_data('cms_system_settings');
        $user = $this->cms_modal->get_data('cms_users', "*", 'id=' . $this->user_id, false);
        $data['company_name'] = $system['company_name'];
        $data['company_logo'] = $system['company_logo'];
        $data['profile_pic'] = $user['profile_picture'];
        //------------------------------//
        //--Modules to be loaded starts here--//
        $ug = $this->cms_modal->get_data('cms_usr_grp_rel', 'group_id', 'user_id = ' . $this->user_id, true); //getting user group id
        $grp = [];
        foreach ($ug as $grp_value) {
            array_push($grp, $grp_value['group_id']);
        }

        $select = "cm.*,cmgp.view_perm,cmgp.view_all_perm,cmgp.group_id";
        $from = "cms_module_group_permission cmgp INNER JOIN cms_modules cm ON cm.id = cmgp.module_id";
        $where = 'cmgp.group_id IN (' . implode(',', $grp) . ') AND cm.parent_module = 0 AND cm.is_active = 1 AND (cmgp.view_all_perm = 1 OR cmgp.view_perm = 1)'
                . 'GROUP BY cm.module_name '
                . 'ORDER BY cm.sort_order ASC';
        $mods = $this->cms_modal->get_data($from, $select, $where, true);

//        $ug = $this->cms_modal->get_data('cms_users', 'user_group', 'id = ' . $this->user_id); //getting user group id
//        $mods = $this->cms_modal->get_data(
//                'cms_module_group_permission cmgp INNER JOIN cms_modules cm ON cm.id = cmgp.module_id', 'cm.*,cmgp.view_perm,cmgp.view_all_perm', 'cmgp.group_id = ' . $ug['user_group'] . ' AND cm.parent_module = 0 AND cm.is_active = 1 ORDER BY sort_order ASC', true);
//        foreach ($mods as $i => $m) {
//            $sub_mods = $this->cms_modal->get_data(
//                    'cms_module_group_permission cmgp INNER JOIN cms_modules cm ON cm.id = cmgp.module_id', 'cm.*', 'cmgp.group_id = ' . $ug['user_group'] . ' AND cm.parent_module = ' . $m['id'] . ' AND cm.is_active = 1', true); //populating sub menu as per user group permission
//            if (!empty($sub_mods)) {
//                $mods[$i] += array('sub_menu' => $sub_mods);
//            }
//        }
        $data['mods'] = $mods;
        //------------------------------//
        //--Log entry ------------------//
        $this->log_entry->entry($this->user_id, $msg, $path);
        //------------------------------//
        //--Error Message --------------//
        $data['msg'] = $msg;
        //------------------------------//
        $this->load->view('header', $data);
        $this->load->view('error_page', $data);
        $this->load->view('footer', $data); 
    }

    protected function permission($type, $module_id) {

        $ug = $this->cms_modal->get_data('cms_usr_grp_rel', '*', 'user_id = ' . $this->user_id, true); // getting user group id from cms_usr_grp_rel it contain multiple or single groups
        $grp = [];
        foreach ($ug as $grp_value) {
            array_push($grp, $grp_value['group_id']);
        }

        $select = $type . '_perm';
        $from = " cms_module_group_permission";
        $where = ' module_id = ' . $module_id . ' AND group_id IN (' . implode(',', $grp) . ')';
        $permission = $this->cms_modal->get_data($from, $select, $where, true); // get multiple permissions from cms_module_group_permission  

        foreach ($permission as $value) {
            if ($value[$type . '_perm'] == 1) { // if any permission have rights then it return true else false
                return true;
                exit;
            }
        }

        return FALSE;
    }

    protected function create_controller($module_controller_name) {

        $file_path = APPPATH . 'controllers\\' . '' . ucfirst($module_controller_name) . '.php';
        $handle = fopen($file_path, 'w') or die('Cannot open file:  ' . $file_path);
        $file_body = "<?php
                     defined('BASEPATH') OR exit('No direct script access allowed');
                     
                     class " . ucfirst($module_controller_name) . " extends MY_Controller {   
                            public function __construct() {
                                parent::__construct();                                
                            }  
                            
                            public function index() {                                
                                if (\$this->permission('view_all', \$this->mod_id) || \$this->permission('view', \$this->mod_id)) {
                                    \$data['mod_id'] = \$this->mod_id;
                                    \$this->view_page('" . $module_controller_name . "', '" . $module_controller_name . " View', \$data);
                                } else {
                                    \$this->error_page('" . $module_controller_name . "', 'Unauthorized Access!');
                                }
                            }
                     }
                ?>";


        fwrite($handle, $file_body);
    }

}
