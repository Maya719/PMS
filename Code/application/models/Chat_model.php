<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete_chat($from_id, $to_id){
        if($this->db->query("DELETE FROM messages WHERE type='chat' AND ((from_id=$from_id AND to_id=$to_id) OR (from_id=$to_id AND to_id=$from_id))")){
            return true;
        }else{
            return false;
        }
    }

    function get_chat($from_id, $to_id, $is_read = ''){
        if($is_read){
            $query = $this->db->query("SELECT * FROM messages WHERE type='chat' AND is_read=0 AND from_id=$to_id AND to_id=$from_id");
        }else{
            $query = $this->db->query("SELECT * FROM messages WHERE type='chat' AND ((from_id=$from_id AND to_id=$to_id) OR (from_id=$to_id AND to_id=$from_id))");
        }
        return $query->result_array();
    }

    function get_unread_msg_count($user_id){
        $query = $this->db->query("SELECT * FROM messages WHERE type='chat' AND is_read=0 AND to_id=$user_id");
        return $query->result_array();
    }
    
    function get_unread_msg_count_numeric($user_id) {
        $query = $this->db->query("SELECT COUNT(*) AS unread_count FROM messages WHERE type='chat' AND is_read=0 AND to_id=$user_id");
        $result = $query->row_array(); // Fetch the single row
        return $result['unread_count']; // Return the unread count
    }

    function chat_mark_read($from_id, $to_id){
        if($this->db->query("UPDATE messages SET is_read=1 WHERE type='chat' AND from_id=$to_id AND to_id=$from_id")){
            return true;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('messages', $data))
            return $this->db->insert_id();
        else
            return false; 
    }
}