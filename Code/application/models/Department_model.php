<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function create($data){
        if($this->db->insert('departments', $data))
            return $this->db->insert_id();
        else
            return $this->db->last_query();; 
    }
    
    function get_departments(){
        $offset = 0;
        $limit = 10;
        $sort = 'id'; 
        $order = 'DESC';
        $get = $this->input->get();
        $where = '';
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
            $where = " WHERE (id like '%".$search."%' OR company_name like '%".$search."%' OR department_name like '%".$search."%')";
        }
        $query = $this->db->query("SELECT * FROM departments".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array(); 

        $query2 = $this->db->query("SELECT COUNT('id') as total FROM departments");
        $res = $query2->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        $data=[]; 
        $s_no=$offset+1; 
        foreach ($results as $department) {
            $company_name=$department["company_name"];
            $department_name=$department["department_name"];
            $id=$department["id"];
            $action = '';
            if($this->ion_auth->is_admin() || permissions('departments_edit')){
                $edit_btn = '<a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-department" data-edit="'.$department['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>';
            }else{
                $edit_btn = '<a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>';
            }

            if($this->ion_auth->is_admin() || permissions('departments_delete')){
                $delete_btn = '<a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_department" data-id="'.$department['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>';
            }else{
                $delete_btn = '<a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>';
            }

            $action = '<span class="d-flex">'.$edit_btn.''.$delete_btn.'</span>';

            $data[]=[
                "s_no"=>$s_no,
                "company_name"=>$company_name,
                "department_name"=>$department_name,
                'action'=> $action
            ];
            $s_no++;
        }
        $bulkData = array();
        $bulkData['total'] = $total;
        $bulkData['rows'] = $data;
        print_r(json_encode($bulkData));
    }
    function delete($id){
        $this->db->where('id', $id);
       if($this->db->delete('departments'))
           return true;
       else
           return false;
   }
   function get_device_by_id($id){
    $where = "";
    if($this->ion_auth->is_admin()){
        $where .= " WHERE id = $id ";
    }else{
        $where .= " AND id = $id ";
    }

    $query = $this->db->query("SELECT * FROM departments ".$where);

    $results = $query->result_array();  
    foreach ($results as $result) {
        $id = $result["id"];

        $usersQuery = $this->db->query("SELECT * FROM users");
        $usersresult = $usersQuery->result_array();
        $users='';
        foreach ($usersresult as $value) {
            $device_id = json_decode($value['device_id'], true);
            if (is_array($device_id) && in_array($id, $device_id)) {
                $users = $users.','.$value["id"];
            }
        }
        $array[]=[
            'id'=>$result["id"],
            'department_name'=>$result["department_name"],
            'saas_id'=>$result["saas_id"],
        ];
    }

    return $array;
}

function edit($id, $data){
    $this->db->where('id', $id);
    if($this->db->update('departments', $data))
        return true;
    else
        return false;
}
}
