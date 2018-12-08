<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Api_keys_model
 *
 * @Author: Muhammad Umar Hayat
 * @Date: JUNE 26, 2016
 * @Description: All Operations Upon Api_keys Database Table.
 */
require_once APPPATH."models/__base.php";
class Api_keys_model extends __base_model
{
    private $table_name     = 'api_keys';
    private $primary_key    = 'key_id';

    public $key_id          = NULL;
    public $user_id         = NULL;
    public $api_id          = NULL;
    public $api_secret      = NULL;
    public $requests_allowed= NULL;
    public $total_requests  = NULL;
    public $expiry          = NULL;
    public $date_added      = NULL;

    const UNLIMITED_REQUEST = -1;
    const NEVER_EXPIRES     = -1;
    const EXPIRED           = 0;

    /*
     * Impletemented the abstract method of parent class to get table name
     */
    protected function getTableName()
    {
        return $this->table_name;
    }
    /*
     * Impletemented the abstract method of parent class to get primary key column name
     */
    protected function getPrimaryKeyName()
    {
        return $this->primary_key;
    }
    /*
     * Generate API ID From Users Data
     */
    public function generateApiID($param = array())
    {
        $user_data  = implode(',', $param);
        $user_data  .= time();
        return md5($user_data);
    }
    /*
     * Generate API Secret From Api ID
     */
    public function generateApiSecret($param = '')
    {
        $param  .= time().rand(999, 99999999999);
        return md5($param).substr(str_shuffle($param), 0, 10);
    }
    /*
     * Validate User Session
     */
    public function validate($api_secret = '', $api_id = '', $type = 'single')
    {
        if($type == 'single')
        {
            $data = $this->get_record_for_field('api_secret', $api_secret);

            if(!empty($data))
            {
                if($data[0]->expiry == 0)
                {
                    return false;
                }
                elseif(time() > $data[0]->expiry && $data[0]->expiry != Api_keys_model::NEVER_EXPIRES)
                {
                    return false;
                }
                else
                {
                    return $data[0]->user_id;
                }
            }
            return false;
        }
        else
        {
            $data = $this->CI->get_record_for_field('api_secret', $api_secret);

            if(empty($data))
            {
                if($data[0]->api_id == $api_id)
                {
                    if($data[0]->expiry == 0)
                    {
                        return false;
                    }
                    elseif(time() > $data[0]->expiry && $data[0]->expiry != Api_keys_model::NEVER_EXPIRES)
                    {
                        return false;
                    }
                    else
                    {
                        return $data[0]->user_id;
                    }
                }
                return false;
            }
            return false;
        }
    }
}
