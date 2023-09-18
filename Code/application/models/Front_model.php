<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete_feature($id){
        $this->db->where('id', $id);
        if($this->db->delete('features'))
            return true;
        else
            return false;
    }

    function get_pages($id = ''){
        $where = "";
        $where .= (!empty($id) && is_numeric($id))?" WHERE id=$id":"";
        $query = $this->db->query("SELECT * FROM pages $where ORDER BY id DESC");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_feature($id = ''){
        $where = "";
        $where .= (!empty($id) && is_numeric($id))?" WHERE id=$id":"";
        $query = $this->db->query("SELECT * FROM features $where ORDER BY order_by_id ASC");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create_feature($data){
        if($this->db->insert('features', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit_feature($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('features', $data))
            return true;
        else
            return false;
    }

    function edit_pages($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('pages', $data))
            return true;
        else
            return false;
    }

}