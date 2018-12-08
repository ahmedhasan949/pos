<?php
                     defined('BASEPATH') OR exit('No direct script access allowed');
                     
                     class Accounts extends MY_Controller {   
                            public function __construct() {
                                parent::__construct();                                
                            }  
                            
                            public function index() {                                
                                if ($this->permission('view_all', $this->mod_id) || $this->permission('view', $this->mod_id)) {
                                    $data['mod_id'] = $this->mod_id;
                                    $this->view_page('accounts', 'accounts View', $data);
                                } else {
                                    $this->error_page('accounts', 'Unauthorized Access!');
                                }
                            }
                     }
                ?>