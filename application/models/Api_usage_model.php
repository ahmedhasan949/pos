<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Api_usage_model
 *
 * @Author: Muhammad Umar Hayat
 * @Date: JUNE 26, 2016
 * @Description: All Operations Upon api_usage Database Table.
 */
require_once APPPATH."models/__base.php";
class Api_usage_model extends __base_model
{
    private $table_name     = 'api_usage';
    private $primary_key    = 'usage_id';

    public $usage_id        = NULL;
    public $key_id          = NULL;
    public $total_requests  = NULL;
    public $date_added      = NULL;
    public $last_updated    = NULL;

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
}
