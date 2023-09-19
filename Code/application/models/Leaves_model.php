<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaves_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($user_id, $id){
        if($this->ion_auth->is_admin() || permissions('leaves_view_all')){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        }else{
         $this->db->where('id', $id);
         $this->db->where('user_id', $user_id);
         $this->db->where('saas_id', $this->session->userdata('saas_id'));
        }
        if($this->db->delete('leaves'))
            return true;
        else
            return false;
    }

    function get_leaves_by_id($id){
        $where = "";
        if($this->ion_auth->is_admin() || permissions('leaves_view_all')){
            $where .= " WHERE id = $id ";
        }else{
            $where .= " WHERE user_id = ".$this->session->userdata('user_id');
            $where .= " AND id = $id ";
        }

        $where .= " AND saas_id = ".$this->session->userdata('saas_id');

        $query = $this->db->query("SELECT * FROM leaves ".$where);
    
        $results = $query->result_array();  

        return $results;
    }

    function get_leaves(){
        $offset = 0;$limit = 10;
        $sort = 'l.id'; $order = 'ASC';
        $get = $this->input->get();
        $where = '';
        if($this->ion_auth->is_admin() || permissions('leaves_view_all')){
            if(isset($get['user_id']) && !empty($get['user_id'])){
                $where .= " WHERE l.user_id = ".$get['user_id'];
            }else{
                $where .= " WHERE l.id != '' ";
            }
        }else{
            $where .= " WHERE l.user_id = ".$this->session->userdata('user_id');
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
            $where .= " AND (l.id like '%".$search."%' OR u.first_name like '%".$search."%' OR u.last_name like '%".$search."%' OR l.starting_date like '%".$search."%' OR l.ending_date like '%".$search."%' OR l.starting_time like '%".$search."%' OR l.ending_time like '%".$search."%' OR l.leave_duration like '%".$search."%' OR l.leave_reason like '%".$search."%' OR l.status like '%".$search."%')";
        }

        $where .= " AND l.saas_id = ".$this->session->userdata('saas_id');
        
        if (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'today') {
            $currentDate = date('Y-m-d');
            $where .= " AND (DATE(l.starting_date) = '{$currentDate}' OR DATE(l.starting_date) = '{$currentDate}') ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'tweek') {
            $currentDate = date('Y-m-d');
            $today = date('D');
            $fromDate = ($today === 'Mon') ? date('Y-m-d', strtotime('this Monday')) : date('Y-m-d', strtotime('last Monday'));
            $toDate = $currentDate;
            $where .= " AND ((DATE(l.starting_date) BETWEEN '{$fromDate}' AND '{$toDate}') OR (DATE(l.ending_date) BETWEEN '{$fromDate}' AND '{$toDate}')) ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'ystdy') {
            $currentDate = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
            $where .= " AND (DATE(l.starting_date) = '{$yesterday}' OR DATE(l.ending_date) = '{$yesterday}') ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'tmonth') {
            $currentDate = date('Y-m-d');
            $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
            $lastDayOfMonth = date('Y-m-t', strtotime($currentDate));
            $where .= " AND ((DATE(l.starting_date) BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}') OR (DATE(l.ending_date) BETWEEN '{$firstDayOfMonth}' AND '{$lastDayOfMonth}')) ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'lmonth') {
            $lastMonthStart = date('Y-m-01', strtotime('first day of -1 month'));
            $lastMonthEnd = date('Y-m-t', strtotime('last day of -1 month'));
            $where .= " AND ((DATE(l.starting_date) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}') OR (DATE(l.ending_date) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}')) ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'custom' && isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
            $fromDate = format_date($get['from'], "Y-m-d");
            $toDate = format_date($get['too'], "Y-m-d");
            $where .= " AND ((DATE(l.starting_date) BETWEEN '{$fromDate}' AND '{$toDate}') OR (DATE(l.ending_date) BETWEEN '{$fromDate}' AND '{$toDate}')) ";
        }
        
		$LEFT_JOIN = " LEFT JOIN users u ON u.id=l.user_id ";

        $query = $this->db->query("SELECT COUNT('l.id') as total FROM leaves l $LEFT_JOIN ".$where);
    
        $res = $query->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        
        $query = $this->db->query("SELECT l.*, CONCAT(u.first_name, ' ', u.last_name) as user FROM leaves l $LEFT_JOIN ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array();  

        $sqlQuery = $this->db->last_query();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $counter = 1;
        
        foreach ($results as $result) {
				$tempRow = $result;
                if($result['status'] == 0){
                    $tempRow['status'] = '<div class="badge badge-info">'.($this->lang->line('pending')?htmlspecialchars($this->lang->line('pending')):'Pending').'</div>';
                }elseif($result['status'] == 1){
                    $tempRow['status'] = '<div class="badge badge-success">'.($this->lang->line('approved')?htmlspecialchars($this->lang->line('approved')):'Approved').'</div>';
                }else{
                    $tempRow['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?htmlspecialchars($this->lang->line('rejected')):'Rejected').'</div>';
                }

                // if($result['type'] == 0){
                //     $tempRow['type'] = ($this->lang->line('casual_leave')?htmlspecialchars($this->lang->line('casual_leave')):'Casual Leave');
                // }elseif($result['type'] == 1){
                //     $tempRow['type'] = ($this->lang->line('marriage_leave')?htmlspecialchars($this->lang->line('marriage_leave')):'Marriage Leave');
                // }elseif($result['type'] == 2){
                //     $tempRow['type'] = ($this->lang->line('medical_leave')?htmlspecialchars($this->lang->line('medical_leave')):'Medical Leave');
                // }else{
                //     $tempRow['type'] = ($this->lang->line('maternity_leave')?htmlspecialchars($this->lang->line('maternity_leave')):'Maternity Leave');
                // }
                
                $tempRow['type'] = '';
                $query = $this->db->query("SELECT * FROM leaves_type");
                $leaves = $query->result_array();
                if(!empty($leaves)){
                    foreach ($leaves as $leave){
                        if($result['type'] == ($leave['id'])){
                            $tempRow['type'] = $leave['name'];
                        }
                    }
                }

                if($result['paid'] == 0){
                    $tempRow['paid'] = ($this->lang->line('paid')?htmlspecialchars($this->lang->line('paid')):'Paid Leave');
                }else{
                    $tempRow['paid'] = ($this->lang->line('unpaid')?htmlspecialchars($this->lang->line('unpaid')):'Unpaid Leave');
                }
                $starting_date = format_date($result['starting_date'], system_date_format());
                $starting_time = date('g:i a', strtotime($result['starting_time']));
                $tempRow['starting_date_time'] = $starting_date . " " . $starting_time;
                $ending_date = format_date($result['ending_date'], system_date_format());
                $ending_time = date('g:i a', strtotime($result['ending_time']));
                $tempRow['ending_date_time'] = $ending_date . " " . $ending_time;
                $tempRow['sr_no'] = $counter;
                $tempRow['employee_id'] = $result['employee_id'];
                $tempRow['user'] = '<a href="#" class="team-member" data-user-id="'.$result['user_id'].'">'.htmlspecialchars($result['user']).'</a>';
                if($this->ion_auth->is_admin() || permissions('leaves_view_all') || (permissions('leaves_edit') && permissions('leaves_delete'))){
                    if(permissions('leaves_edit') && permissions('leaves_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-leaves" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_leaves" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }
                    elseif(!permissions('leaves_edit') && permissions('leaves_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_leaves" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(permissions('leaves_edit') && !permissions('leaves_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-leaves" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(!permissions('leaves_edit') && !permissions('leaves_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }
                }else{
                    if($result['status'] == 0){
                        if(permissions('leaves_edit') && permissions('leaves_delete')){
                            $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-leaves" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_leaves" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                        }
                        elseif(!permissions('leaves_edit') && permissions('leaves_delete')){
                            $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_leaves" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                        }elseif(permissions('leaves_edit') && !permissions('leaves_delete')){
                            $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-leaves" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                        }elseif(!permissions('leaves_edit') && !permissions('leaves_delete')){
                            $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
                        }
                    }else{
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';

                    }
                }
                
                $rows[] = $tempRow;
                $counter++;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function create($data){
        if($this->db->insert('leaves', $data))
            return $this->db->insert_id();
        else
            return $this->db->last_query();; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->update('leaves', $data))
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

        //     $total_leaves = 0;
        // switch ($type) {
        //     case 0: // Casual Leave
        //         $total_leaves = 10;
        //         break;
        //     case 1: // Marriage Leave
        //         $total_leaves = 5;
        //         break;
        //     case 2: // Medical Leave
        //         $total_leaves = 8;
        //         break;
        //     case 3: // Maternity Leave
        //         $total_leaves = 60;
        //         break;
        //     default:
        //         $total_leaves = 0;
        //         break;
        // }
        
        $total_leaves = 0;
        $querys = $this->db->query("SELECT * FROM leaves_type");
        $leaves = $querys->result_array();
        if(!empty($leaves)){
            foreach ($leaves as $leave){
                if($type == $leave['id']){
                    $total_leaves = $leave['total_leaves'];
                }
            }
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
    
        $consumed_leaves = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $consumed_leaves = ($row->consumed_leaves !== null) ? $row->consumed_leaves : 0;
        } else {
            $consumed_leaves = 0;
        }
        if ($consumed_leaves != floor($consumed_leaves)) {
            // Keep it as it is, since it has a decimal part
        } else {
            // Convert to an integer, since it's a whole number
            $consumed_leaves = intval($consumed_leaves);
        }
        
        $remaining_leaves = $total_leaves - $consumed_leaves;
    
        return array(
            'query' => $sqlQuery,
            'total_leaves' => $total_leaves,
            'consumed_leaves' => $consumed_leaves,
            'remaining_leaves' => $remaining_leaves
        );
    }


}
