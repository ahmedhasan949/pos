<?php

/*
 * ---------------------------------------------------------------------------------------
 * File: 		__base.php
 * Type:		Model
 * Created On: 	Aug 5, 13
 * Created by: 	M Rizwan
 * Description: Base model from which all models are inherited.
 *				Common model function are included in this class.
 *
 * ---------------------------------------------------------------------------------------
 */

class __Base_Model extends CI_Model {

	function __construct()
   	{
		parent::__construct();

   	}


    /*
    * To save the data in the table which is passed to the class variables
    */
   public function save($data = array())
   {
       if(count($data)==0)
       {
           /**
            * Get Class Variables and Only take the not null data
            * ====================================================
            */
           $data = array();
           $class_vars = get_class_vars(get_class($this));
           foreach ($class_vars as $name => $value)
           {
               if(!is_null($this->$name) && (!empty($this->$name) || $this->$name == '0') && $name != $this->getPrimaryKeyName())
               {
                   $data[$name] = $this->$name;
               }
           }
           /**
            * ----------------------------------------------------------------------
            */
       }

       if($this->{$this->getPrimaryKeyName()} == '')
       {
           $this->db->insert($this->getTableName(), $data);
           $this->{$this->getPrimaryKeyName()} = $this->db->insert_id();
           return $this->{$this->getPrimaryKeyName()};
       }
       else
       {
           if(sizeof($data) > 0)
           {
               $this->db->where($this->getPrimaryKeyName(), $this->{$this->getPrimaryKeyName()});
               return $this->db->update($this->getTableName(), $data);
           }
           else
           {
               return TRUE;
           }
       }
   }
   /*
     * Purpose: To get rows by providing a column name and value
     */
    public function get_record_for_field($field, $value)
    {
        $sql    =   "SELECT * FROM ".$this->getTableName()." WHERE `$field` = '$value'";
        $query  =   $this->db->query($sql);
        return $query->result();
    }


     public function get_record($select, $where='', $like1='', $like2='', $limit='')
     {
			 	$this->db->select('*');
				$this->db->from($this->getTableName());
				if (!empty($where)) {
					$this->db->where($where);
				}
				if(!empty($like1))
				{
					$this->db->like($like1);
				}
				if(!empty($like2))
				{
					$this->db->or_like($like2);
				}
				$this->db->order_by('last_updated','DESC');
				return $this->db->get()->result();
     }

		 public function delete_record($where)
		 {
		 		$this->db->where($where);
				$this->db->from($this->getTableName());
				return $this->db->delete();
		 }
		 /*
      * Purpose: To update record with where condition
      */
     public function update_Api_record($data = array(), $where = array())
     {
         $this->db->where($where);

         return $this->db->update($this->getTableName(), $data) ? TRUE : FALSE;
     }
		 public function insert_record($data = array())
     {
         return $this->db->insert($this->getTableName(), $data) ? TRUE : FALSE;
     }
}
?>
