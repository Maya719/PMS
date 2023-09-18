<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }


    function get_notifications($user_id ='', $notification_id = ''){

        $where = "";
        $left_join = " LEFT JOIN users u ON n.from_id=u.id ";

        if(!empty($user_id)){
            $query = $this->db->query("SELECT n.*,u.first_name,u.last_name,u.profile FROM notifications n $left_join WHERE to_id=$user_id ORDER BY n.created DESC");
        }else{
            $query = $this->db->query("SELECT n.*,u.first_name,u.last_name,u.profile FROM notifications n $left_join WHERE to_id=".$this->session->userdata('user_id')." ORDER BY n.created DESC");
        }
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('notifications', $data))
            return $this->db->insert_id();
        else
            return false; 
    }
    
    function delete($id = '', $type = '', $type_id = '', $from_id = '', $to_id = ''){
        if($id){
            $this->db->where('id', $id);
        }
        if($type){
            $this->db->where('type', $type);
        }
        if($type_id){
            $this->db->where('type_id', $type_id);
        }
        if($from_id){
            $this->db->where('from_id', $from_id);
        }
        if($to_id){
            $this->db->where('to_id', $to_id);
        }
        if($id || $type || $type_id || $from_id || $to_id){
            $this->db->delete('notifications');
            return true;
        }
        return false;
    }

    function edit($id = '', $type = '', $type_id = '', $from_id = '', $to_id = ''){
        if($id){
            $this->db->where('id', $id);
        }
        if($type){
            $this->db->where('type', $type);
        }
        if($type_id){
            $this->db->where('type_id', $type_id);
        }
        if($from_id){
            $this->db->where('from_id', $from_id);
        }
        if($to_id){
            $this->db->where('to_id', $to_id);
        }else{
            $this->db->where('to_id', $this->session->userdata('user_id'));
        }

        $data = array('is_read' => 1);

        if($this->db->update('notifications', $data))
            return true;
        else
            return false;
    }

}