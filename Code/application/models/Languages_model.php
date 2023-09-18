<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Languages_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($id){
        $this->db->where('id', $id);
        if($this->db->delete('languages'))
            return true;
        else
            return false;
    }

    function get_languages($language_id = '', $language_name = '', $status = ''){
        $where = "";
        $where .= (!empty($language_id) && is_numeric($language_id))?" WHERE id=$language_id":"";
        $where .= (!empty($language_name))?" WHERE language='".lcfirst($language_name)."'":"";
        $where .= (!empty($status))?" WHERE status='".$status."'":"";
        $query = $this->db->query("SELECT * FROM languages $where ");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('languages', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($language, $data){
        $this->db->where('language', $language);
        if($this->db->update('languages', $data))
            return true;
        else
            return false;
    }

}