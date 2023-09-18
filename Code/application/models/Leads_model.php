<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leads_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function get_leads(){
 
        $offset = 0;$limit = 10;
        $sort = 'l.id'; $order = 'ASC';
        $get = $this->input->get();
        $where = " WHERE l.saas_id = ".$this->session->userdata('saas_id');
        if(!$this->ion_auth->is_admin()){
            $where .= " AND (l.assigned = ".$this->session->userdata('user_id')." OR l.created_by = ".$this->session->userdata('user_id').") ";
        }
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
            $where .= " AND (l.id like '%".$search."%' OR l.company like '%".$search."%' OR l.source like '%".$search."%' OR l.email like '%".$search."%' OR l.mobile like '%".$search."%' OR l.status like '%".$search."%' OR l.assigned like '%".$search."%')";
        }

        if(isset($get['assigned']) &&  !empty($get['assigned'])){
            $assigned_id = strip_tags($get['assigned']);
            $where .= " AND l.assigned = $assigned_id ";
        }

        if(isset($get['status']) &&  !empty($get['status'])){
            $status = strip_tags($get['status']);
            $where .= " AND l.status = '$status' ";
        }
    
		$LEFT_JOIN = " LEFT JOIN users u ON u.id=l.assigned ";

        $query = $this->db->query("SELECT COUNT('l.id') as total FROM leads l $LEFT_JOIN ".$where);
    
        $res = $query->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        
        $query = $this->db->query("SELECT l.*, CONCAT(u.first_name, ' ', u.last_name) as assigned FROM leads l $LEFT_JOIN ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array();  
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($results as $result) {
				$tempRow = $result;

                $tempRow['email'] = $result['email'].'<br>'.$result['mobile'];
                
                $tempRow['created'] = format_date($result['created'],system_date_format());

                if($result['status'] == 'new'){
                    $status = $this->lang->line('new')?$this->lang->line('new'):'New';
                }elseif($result['status'] == 'qualified'){
                    $status = $this->lang->line('qualified')?$this->lang->line('qualified'):'Qualified';
                }elseif($result['status'] == 'discussion'){
                    $status = $this->lang->line('discussion')?$this->lang->line('discussion'):'Discussion';
                }elseif($result['status'] == 'won'){
                    $status = $this->lang->line('won')?$this->lang->line('won'):'Won';
                }elseif($result['status'] == 'lost'){
                    $status = $this->lang->line('lost')?$this->lang->line('lost'):'Lost';
                }else{
                    $status = ucfirst($result['status']);
                }
                $tempRow['status'] = '<span class="badge badge-info">'.$status.'</span>';

                if($this->ion_auth->is_admin() || permissions('lead_edit')){
                    $edit_btn = '<a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-leads" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>';
                }else{
                    $edit_btn = '<a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>';
                }

                if($this->ion_auth->is_admin() || permissions('lead_delete')){
                    $delete_btn = '<a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_leads" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>';
                }else{
                    $delete_btn = '<a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>';
                }

                if($this->ion_auth->is_admin()){
                    $convert_to_client_btn = '<a href="#" class="btn btn-icon btn-sm btn-success mr-1 convert_to_client" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('convert_to_client')?htmlspecialchars($this->lang->line('convert_to_client')):'Convert to Client').'"><i class="fas fa-user-plus"></i></a>';
                }else{
                    $convert_to_client_btn = '';
                }

                $tempRow['action'] = '<span class="d-flex">'.$convert_to_client_btn.''.$edit_btn.''.$delete_btn.'</span>';
                
                $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_leads_by_id($lead_id = ''){
        $where = " WHERE saas_id = ".$this->session->userdata('saas_id');
        $where .= (!empty($lead_id) && is_numeric($lead_id))?" AND id=$lead_id":"";
        $query = $this->db->query("SELECT * FROM leads $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('leads', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('leads', $data))
            return true;
        else
            return false;
    }

    function delete($id){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->delete('leads'))
            return true;
        else
            return false;
    }

}