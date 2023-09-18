<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meetings_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($id){
        $this->db->where('id', $id);
        if($this->db->delete('meetings'))
            return true;
        else
            return false;
    }

    function get_meetings($meeting_id = ''){

        $where = " WHERE m.saas_id=".$this->session->userdata('saas_id');

        if(!$this->ion_auth->is_admin()){
            $where .= " AND (find_in_set(".$this->session->userdata('user_id').",users) OR m.created_by=".$this->session->userdata('user_id').") ";
        }

        $where .= (!empty($meeting_id) && is_numeric($meeting_id))?" AND m.id=$meeting_id":"";
        
        $get = $this->input->get();
        if(isset($get['search']) &&  !empty($get['search'])){
            $search = strip_tags($get['search']);
            $where .= " AND (m.id like '%".$search."%' OR u.first_name like '%".$search."%' OR m.title like '%".$search."%')";
        }

        $order_by = 'ORDER BY m.id DESC';

        $left_join = "LEFT JOIN users u ON m.created_by=u.id";
        $query = $this->db->query("SELECT m.*, CONCAT(u.first_name, ' ', u.last_name) as created_user FROM meetings m $left_join $where $order_by");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('meetings', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('meetings', $data))
            return true;
        else
            return false;
    }

}