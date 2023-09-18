<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }

    function get_expenses_by_id($id){
 
        $where = " WHERE id = ".$id;

        $where .= " AND saas_id = ".$this->session->userdata('saas_id');
        
        $query = $this->db->query("SELECT * FROM expenses ".$where);
    
        $results = $query->result_array();  

        return $results;
    }
    
    function get_expenses(){
 
        $offset = 0;$limit = 10;
        $sort = 'e.id'; $order = 'ASC';
        $get = $this->input->get();
        if(isset($get['team_member_id']) && !empty($get['team_member_id'])){
            $where = " WHERE e.team_member_id = ".$get['team_member_id'];
        }else{
            $where = " WHERE e.id != '' ";
        }
        if(isset($get['client_id']) && !empty($get['client_id'])){
            $where .= " AND e.client_id = ".$get['client_id'];
        }
        if(isset($get['project_id']) && !empty($get['project_id'])){
            $where .= " AND e.project_id = ".$get['project_id'];
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
            $where .= " AND (e.id like '%".$search."%' OR u.first_name like '%".$search."%' OR u.last_name like '%".$search."%' OR uu.first_name like '%".$search."%' OR uu.last_name like '%".$search."%' OR e.date like '%".$search."%' OR e.description like '%".$search."%' OR e.amount like '%".$search."%' OR p.title like '%".$search."%')";
        }
    
        $where .= " AND e.saas_id = ".$this->session->userdata('saas_id');

		$LEFT_JOIN = " LEFT JOIN users u ON u.id=e.team_member_id ";
		$LEFT_JOIN .= " LEFT JOIN users uu ON uu.id=e.client_id ";
		$LEFT_JOIN .= " LEFT JOIN projects p ON p.id=e.project_id ";

        $query = $this->db->query("SELECT COUNT('e.id') as total FROM expenses e $LEFT_JOIN ".$where);
    
        $res = $query->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        
        $query = $this->db->query("SELECT e.*, CONCAT(u.first_name, ' ', u.last_name) as user, CONCAT(uu.first_name, ' ', uu.last_name) as client, p.title as project_titile FROM expenses e $LEFT_JOIN ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array();  
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($results as $result) {
				$tempRow = $result;

                $tempRow['date'] = format_date($result['date'],system_date_format());
                
                if($result['receipt']){
                    
					if(file_exists('assets/uploads/receipt/'.$result['receipt'])){
						$file_upload_path = 'assets/uploads/receipt/'.$result['receipt'];
					}else{
						$file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/receipt/'.$result['receipt'];
					}
                    $tempRow['receipt'] = '<span class="d-flex"><a target="_blank" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View').'"><i class="fas fa-eye"></i></a><a download="'.$result['receipt'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a></span>';
                }

                $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-expenses" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_expenses" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                
                $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_expenses_chart(){
        $where = " WHERE e.saas_id = ".$this->session->userdata('saas_id');

		$LEFT_JOIN = " LEFT JOIN users u ON u.id=e.team_member_id ";
		$LEFT_JOIN .= " LEFT JOIN users uu ON uu.id=e.client_id ";
		$LEFT_JOIN .= " LEFT JOIN projects p ON p.id=e.project_id ";

        $query = $this->db->query("SELECT e.*, CONCAT(u.first_name, ' ', u.last_name) as user, CONCAT(uu.first_name, ' ', uu.last_name) as client, p.title as project_titile FROM expenses e $LEFT_JOIN ".$where);
        $results = $query->result_array();  
        if($results){
            return $results;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('expenses', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($data, $expenses_id){
        $this->db->where('id', $expenses_id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->update('expenses', $data))
            return true;
        else
            return false;
    }

    function delete($expenses_id){
        $query = $this->db->query('SELECT receipt FROM expenses WHERE id='.$expenses_id);
        $data = $query->result_array();
        if(!empty($data[0]['receipt'])){
            
            if(file_exists('assets/uploads/receipt/'.$data[0]['receipt'])){
                $file_upload_path = 'assets/uploads/receipt/'.$data[0]['receipt'];
            }else{
                $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/receipt/'.$data[0]['receipt'];
            }

            unlink($file_upload_path);
        }

        $this->db->where('id', $expenses_id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->delete('expenses'))
            return true;
        else
            return false;
    }
    

}