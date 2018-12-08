<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CMS_modal extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    //THIS PART USED To FETCH DATA
    public function get_data($from, $select = '', $where = '', $result_array = false) {
        //echo 'SELECT '.$select.' FROM '.$from.' WHERE '.$where;

        /*
          //Select Criteria
          if($select == '')
          {
          $this->db->select('*');
          }
          else
          {
          if(!is_array($select))
          {
          $this->db->select($select);
          }
          else
          {
          $this->db->select(implode(',', $select));
          }
          }
          //Where Criteria
          if($where != '')
          {
          $this->db->where($where);
          }
          //From or Tables involved
          $query = $this->db->get($from);
          if($query->num_rows() > 1)
          {
          return $query->result_array();
          }
          else
          {
          return $query->row_array();
          }
         */

        $sql = "SELECT * FROM " . $from;

        if ($select != '') {
            $sql = "SELECT " . $select . " FROM " . $from;
        }

        if ($where != '') {
            $sql .= " WHERE " . $where;
        }

        $rows = $this->db->query($sql);
        if ($rows->num_rows() <= 1) {
            if ($result_array == false) {
                $query = $this->db->query($sql);
                return $query->row_array();
            } else {
                $query = $this->db->query($sql);
                return $query->result_array();
            }
        } else {
            $query = $this->db->query($sql);
            return $query->result_array();
        }
    }

    //THIS PART IS USED TO INSERT DATA
    public function insert_data($data, $table) {
        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    // THIS PART IS USED TO EDIT DATA
    public function edit_data($table, $data, $id) {
        // $this->output->enable_profiler(TRUE);
        return $this->db->update($table, $data, $id) or die($this->db->error());
    }

    //THIS PART IS USED TO DELETE DATA
    public function delete_data($table, $id) {
        return $this->db->delete($table, $id);
    }

    // THIS IS CODEIGNITER BATCH FUNCTION USED TO INSERT MULTIPLE RECORDS IN TABLE
    public function batch_insert($twod_array, $table) {
        return $this->db->insert_batch($table, $twod_array);         
    }
    
    public function insert_select($insert_qry, $select,$from,$where) {
        
        if($insert_qry != "") {
            $sql = "INSERT " . $insert_qry;
        } 

        if ($select != '') {
            $sql .= "SELECT " . $select . " FROM " . $from;
        }

        if ($where != '') {
            $sql .= " WHERE " . $where;
        }

        $this->db->query($sql); 
        return  $insert_id = $this->db->insert_id();
    }

}
