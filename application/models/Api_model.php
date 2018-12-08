<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api_model extends CI_Model{

    public function validate_api($api_secret)
    {
        $this->db->select('non_member_id');
        $this->db->where('api_secret',$api_secret);
        $query = $this->db->get('api_keys');
        if ($query->num_rows() > 0) 
        {
            //return $query->result_array();
            foreach ($query->result_array() as $row)
{
        //return $row['api_secret'];
        //echo $row['api_id'];
        return $row['non_member_id'];
}
        } 
        else 
        {
            return 0;
        }

    }

    

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

    
    
    public function insert($data, $table) {

        if ($this->db->insert($table, $data)) {
            $insert_id = $this->db->insert_id();
            return $insert_id;
        } else {

            return false;
        }
    }
    
    public function selectAll($table) {
//        $this->db->select("*")
//                ->from($table);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function selectByOtherCol($col, $col_val, $table) {

        $this->db->where($col, $col_val);

        //$this->db->where('is_active', 1); @NOTE changes

        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {

            return $query->result_array();

        } else {

            return 0;

        }

    }
    

    public function selectByTwoCol($col_one, $col_val_one, $col_two, $col_val_two, $table) 
    {
        $this->db->where($col_one, $col_val_one);
        $this->db->where($col_two, $col_val_two);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) 
        {
            return $query->result();
        } 
        else 
        {
            return 0;
        }
    }
    ## update by other column
    public function updateByOtherCol($col,$col_val, $data, $table) {
        
        //$this->db->trans_start();
        $this->db->where($col, $col_val);
        $update=$this->db->update($table, $data);
        //$this->db->trans_complete();
        //if ($this->db->trans_status() === FALSE) 
        if($update>0)
        {
            return true;
        } 
        else
        {
            return false;
        }
    }
    
}
