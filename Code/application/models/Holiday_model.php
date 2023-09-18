<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($id){
         $this->db->where('id', $id);
        if($this->db->delete('holiday'))
            return true;
        else
            return false;
    }

    function get_leaves_by_id($id){
        $where = "";
        if($this->ion_auth->is_admin()){
            $where .= " WHERE id = $id ";
        }else{
            $where .= " AND id = $id ";
        }

        $query = $this->db->query("SELECT * FROM holiday ".$where);
    
        $results = $query->result_array();  
        
        $results4["id"]=$results[0]["id"];
        $results4["apply"]=$results[0]["apply"];
        $results4["ending_date"]=$results[0]["ending_date"];
        $results4["holiday_duration"]=$results[0]["holiday_duration"];
        $results4["remarks"]=$results[0]["remarks"];
        $results4["starting_date"]=$results[0]["starting_date"];
        $results4["type"]=$results[0]["type"];
        $usersarray = json_decode($results[0]["users"]);
        $departmentarray = json_decode($results[0]["department"]);
        $results4["users"]= implode(',',$usersarray );
        $results4["department"]= implode(',',$departmentarray );

        return $results4;
    }

    function get_holiday(){
        $offset = 0;$limit = 10;
        $sort = 'l.id'; $order = 'ASC';
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
        $query = $this->db->query("SELECT * FROM holiday");
    
        $results = $query->result_array(); 
        $data=[]; 
        $s_no=1; 
        foreach ($results as $holiday) {
            $timestamp = strtotime($holiday["starting_date"]);
            $timestamp2 = strtotime($holiday["ending_date"]);
            $starting_date = date("d M Y", $timestamp);
            $ending_date = date("d M Y", $timestamp2);
            $holiday_duration=$holiday["holiday_duration"];
            $remarks=$holiday["remarks"];
            $type=$holiday["type"];
            $apply=$holiday["apply"];
            if ($type == '0') {
                $type = 'National Day';
            }elseif ($type == '1') {
                $type = 'Rest Day';
            }elseif($type == '2'){
                $type = 'Weekend';
            }elseif($type == '4'){
                $type = 'Religious Day';
            }else{
                $type = 'Unplanned';
            }
            if ($apply == '0') {
                $users='All';

            }elseif($apply == '1') {
                $department=$holiday["department"];
                $departments=json_decode($department);
                $make=[];
                foreach ($departments as $singleDepartment) {
                    $findDep = $this->db->query("SELECT department_name FROM departments WHERE id=".$singleDepartment);
                    $depResult = $findDep->result_array(); 
                    $make[] = $depResult;
                }
                $departmentNames=array();
                foreach ($make as $departmentData) {
                    $departmentNames[] = '<span class="badge badge-secondary">' . $departmentData[0]['department_name'] . '</span>';
                }
                $usersString = implode(' ', $departmentNames);
                $users = $usersString;
            }else{
                $usersKey=json_decode($holiday["users"]);
                $profile_html='';
                foreach ($usersKey as $user) {
                    $avatarQurey = $this->db->query("SELECT * FROM users WHERE id=".$user);
                    $Avatars = $avatarQurey->result_array(); 
                    $first_name = htmlspecialchars($Avatars[0]['first_name']);
                    $last_name = htmlspecialchars($Avatars[0]['last_name']);
                    $profile = $Avatars[0]['profile'];
                    // if (!empty($profile)) {
                    //     $profile_image_path = (file_exists('assets/uploads/profiles/' . $profile)) ? 'assets/uploads/profiles/' . $profile : 'assets/uploads/f' . $this->session->userdata('saas_id') . '/profiles/' . $profile;
                    //     $profile_html .= '<figure class="avatar avatar-sm mr-1" style="margin-top:3px; margin-bottom:3px;">
                    //         <img src="' . base_url($profile_image_path) . '" alt="' . $first_name . ' ' . $last_name . '" data-toggle="tooltip" data-placement="top" title="' . $first_name . ' ' . $last_name . '">
                    //     </figure>';
                    // } else {
                    //     $profile_html .= '<figure  style="margin-top:3px; margin-bottom:3px;" class="avatar avatar-sm bg-primary text-white mr-1" data-initial="' . ucfirst(mb_substr($first_name, 0, 1, 'utf-8')) . '' . ucfirst(mb_substr($last_name, 0, 1, 'utf-8')) . '" data-toggle="tooltip" data-placement="top" title="' . $first_name . ' ' . $last_name . '">
                    //     </figure>';
                    // }
                    if (!empty($first_name) && !empty($last_name)) {
    $profile_html .= '<span>' . $first_name . ' ' . $last_name . '</span>'.'<br>';
}



                }
                $users = $profile_html;
            }

            $data[]=[
                's_no'=>$s_no,
                'starting_date'=>$starting_date,
                'ending_date'=>$ending_date,
                'holiday_duration'=>$holiday_duration,
                'remarks'=>$remarks,
                'apply_on'=>$users,
                'type'=>$type,
                'action'=>'<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-holiday" data-edit="'.$holiday['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_holiday" data-id="'.$holiday['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>'
            ];
            $s_no++;
        }
        $bulkData = array();
        $bulkData['rows'] = $data;
        $bulkData['total'] = count($data);
        print_r(json_encode($bulkData));
    }

    function create($data){
        if($this->db->insert('holiday', $data))
            return $this->db->insert_id();
        else
            return $this->db->last_query();; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('holiday', $data))
            return true;
        else
            return false;
    }

    public function get_department_time(){
        $query = $this->db->query("SELECT value FROM settings WHERE type='department_' ");
        $result = $query->result_array();
        
        if ($result) {
            return $result[0]['value'];
        } else {
            return false;
        }
    }
    
    function get_leaves_count($result){
        $type = $result['type'];
        $user_id = isset($result['user_id']) ? $result['user_id'] : $this->session->userdata('user_id');
            $total_leaves = 0;
        switch ($type) {
            case 0: // Casual Leave
                $total_leaves = 10;
                break;
            case 1: // Marriage Leave
                $total_leaves = 5;
                break;
            case 2: // Medical Leave
                $total_leaves = 8;
                break;
            case 3: // Maternity Leave
                $total_leaves = 60;
                break;
            default:
                $total_leaves = 0;
                break;
        }

        $from = date('Y-01-01');
        $to = date('Y-12-31');
        
        $this->db->select('SUM(CASE 
            WHEN leave_duration LIKE "%Full%" THEN DATEDIFF(ending_date, starting_date) + 1
            WHEN leave_duration LIKE "%Half%" THEN (DATEDIFF(ending_date, starting_date) + 1) * 0.5
            ELSE 0
        END) as consumed_leaves');
        $this->db->from('leaves');
        $this->db->where('user_id', $user_id);
        $this->db->where('type', $type);
        $this->db->where('status', '1');
        $this->db->where('starting_date >=', $from);
        $this->db->where('starting_date <=', $to);
        $this->db->group_start();
        $this->db->like('leave_duration', 'Full', 'both');
        $this->db->or_like('leave_duration', 'Half', 'both');
        $this->db->group_end();
        $query = $this->db->get();

        $sqlQuery = $this->db->last_query();
    
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $consumed_leaves = ($row->consumed_leaves !== null) ? $row->consumed_leaves : 0;
        } else {
            $consumed_leaves = 0;
        }
        $consumed_leaves = rtrim($consumed_leaves, '.0');
        $consumed_leaves = (int)$consumed_leaves;
        $remaining_leaves = $total_leaves - $consumed_leaves;
    
        return array(
            'query' => $sqlQuery,
            'total_leaves' => $total_leaves,
            'consumed_leaves' => $consumed_leaves,
            'remaining_leaves' => $remaining_leaves
        );
    }



}


