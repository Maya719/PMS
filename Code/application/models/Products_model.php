<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($id){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->delete('products'))
            return true;
        else
            return false;
    }

    function get_products_by_id($id){
        $where = "";

        $where .= " WHERE id = $id ";

        $where .= " AND saas_id = ".$this->session->userdata('saas_id');

        $query = $this->db->query("SELECT * FROM products ".$where);
    
        $results = $query->result_array();  

        return $results;
    }

    function get_products_array($id = ''){
        $where = "";

        $where .= " WHERE saas_id = ".$this->session->userdata('saas_id');

        if(!empty($id)){
            $where .= " AND id = $id ";
        }

        $query = $this->db->query("SELECT * FROM products ".$where);
    
        $results = $query->result_array();  

        return $results;
    }

    function get_products(){
 
        $offset = 0;$limit = 10;
        $sort = 'id'; $order = 'ASC';
        $get = $this->input->get();
        $where = '';
        $where .= " WHERE saas_id = ".$this->session->userdata('saas_id');
        if(isset($get['sort']))
            $sort = strip_tags($get['sort']);
        if(isset($get['offset']))
            $offset = strip_tags($get['offset']);
        if(isset($get['limit']))
            $limit = strip_tags($get['limit']);
        if(isset($get['order']))
            $order = strip_tags($get['order']);

        if(isset($get['search']) &&  !empty($get['search'])){
            $search = strip_tags($get['search']);
            $where .= " AND (id like '%".$search."%' OR name like '%".$search."%' OR price like '%".$search."%')";
        }

        $query = $this->db->query("SELECT COUNT('id') as total FROM products ".$where);
    
        $res = $query->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        
        $query = $this->db->query("SELECT * FROM products ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array();  
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($results as $result) {
				$tempRow = $result;

                $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-products" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_products" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                
                $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function create($data){
        if($this->db->insert('products', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->update('products', $data))
            return true;
        else
            return false;
    }

}