<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($id){
        $this->db->where('id', $id);
        if($this->db->delete('invoices'))
            return true;
        else
            return false;
    }

    function get_invoices($invoice_id = '', $get_payments = false){
        $where = " WHERE type='invoice' ";
        if($this->ion_auth->in_group(4)){
            $where .= " AND i.to_id=".$this->session->userdata('user_id');
        }else{
            $where .= " AND i.from_id=".$this->session->userdata('saas_id');
        }
        $where .= (!empty($invoice_id) && is_numeric($invoice_id))?" AND i.id=$invoice_id":"";
        
        $get = $this->input->get();
        if(isset($get['search']) &&  !empty($get['search'])){
            $search = strip_tags($get['search']);
            $where .= " AND (i.id like '%".$search."%' OR u.first_name like '%".$search."%' OR uu.first_name like '%".$search."%')";
        }
        if(isset($get['to_id']) &&  !empty($get['to_id'])){
            $where .= " AND to_id=".$get['to_id'];
        }
        if(isset($get['items_id']) &&  !empty($get['items_id'])){
            $where .= " AND find_in_set(".$get['items_id'].",items_id)";
        }
        if(isset($get['status']) && $get['status'] != 'none'){
            $where .= " AND status=".$get['status'];
        }

        $order_by = 'ORDER BY i.id DESC';
        if($get_payments){
            $where .= " AND i.payment_type != '' AND i.payment_type IS NOT NULL ";
            $order_by = 'ORDER BY i.payment_date DESC';
        }


        $left_join = "LEFT JOIN users u ON i.to_id=u.id";
        $left_join .= " LEFT JOIN users uu ON i.from_id=uu.id";
        $query = $this->db->query("SELECT i.*, CONCAT('INV-', LPAD(i.id,6,'0')) as invoice_id, CONCAT(u.first_name, ' ', u.last_name) as to_user, CONCAT(uu.first_name, ' ', uu.last_name) as from_user FROM invoices i $left_join $where $order_by");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('invoices', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('invoices', $data))
            return true;
        else
            return false;
    }

}