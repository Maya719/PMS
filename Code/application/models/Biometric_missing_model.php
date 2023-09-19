<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biometric_missing_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($user_id, $id){
        if($this->ion_auth->is_admin() || permissions('biometric_request_view_all')){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        }else{
            $this->db->where('id', $id);
            $this->db->where('user_id', $user_id);
            $this->db->where('saas_id', $this->session->userdata('saas_id'));
        }
        if($this->db->delete('biometric_missing'))
            return true;
        else
            return false;
    }

    function get_biometric_by_id($id){
        $where = "";
        if($this->ion_auth->is_admin() || permissions('biometric_request_view_all')){
            $where .= " WHERE id = $id ";
        }else{
            $where .= " WHERE user_id = ".$this->session->userdata('user_id');
            $where .= " AND id = $id ";
        }

        $where .= " AND saas_id = ".$this->session->userdata('saas_id');

        $query = $this->db->query("SELECT * FROM biometric_missing ".$where);
    
        $results = $query->result_array();  

        return $results;
    }

    function get_biometric() {
        $offset = 0;
        $limit = 10;
        $sort = 'bm.id';
        $order = 'ASC';
        $get = $this->input->get();
        $where = '';
    
        if ($this->ion_auth->is_admin() || permissions('biometric_request_view_all')) {
            if (isset($get['user_id']) && !empty($get['user_id'])) {
                $where .= " WHERE bm.user_id = ".$get['user_id'];
            } else {
                $where .= " WHERE bm.id != ''";
            }
        } else {
            $where .= " WHERE bm.user_id = ".$this->session->userdata('user_id');
        }
        if (isset($get['sort'])) {
            $sort = strip_tags($get['sort']);
        }
    
        if (isset($get['offset'])) {
            $offset = strip_tags($get['offset']);
        }
    
        if (isset($get['limit'])) {
            $limit = strip_tags($get['limit']);
        }
    
        if (isset($get['order'])) {
            $order = strip_tags($get['order']);
        }
    
        if (isset($get['search']) && !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where .= " AND (bm.id LIKE '%".$search."%' OR bm.date LIKE '%".$search."%' OR bm.time LIKE '%".$search."%' OR bm.reason LIKE '%".$search."%')";
        }
    
        $where .= " AND bm.saas_id = ".$this->session->userdata('saas_id');
        
        if (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'today') {
            $currentDate = date('Y-m-d');
            $where .= " AND DATE(bm.date) = '{$currentDate}' ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'tweek') {
            $currentDate = date('Y-m-d');
            $fromDate = date('Y-m-d', strtotime('last Monday'));
            $toDate = $currentDate;
            $where .= " AND (DATE(bm.date) BETWEEN '{$fromDate}' AND '{$toDate}') ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'ystdy') {
            $currentDate = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
            $where .= " AND DATE(bm.date) = '{$yesterday}' ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'tmonth') {
            $currentDate = date('Y-m-d');
            $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
            $where .= " AND (DATE(bm.date) BETWEEN '{$firstDayOfMonth}' AND '{$currentDate}')  ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'lmonth') {
            $lastMonthStart = date('Y-m-01', strtotime('first day of -1 month'));
            $lastMonthEnd = date('Y-m-t', strtotime('last day of -1 month'));
            $where .= " AND (DATE(bm.date) BETWEEN '{$lastMonthStart}' AND '{$lastMonthEnd}')  ";
        } elseif (isset($get['filter']) && !empty($get['filter']) && $get['filter'] == 'custom' && isset($get['from']) && !empty($get['from']) && isset($get['too']) && !empty($get['too'])) {
            $fromDate = format_date($get['from'], "Y-m-d");
            $toDate = format_date($get['too'], "Y-m-d");
            $where .= " AND (DATE(bm.date) BETWEEN '{$fromDate}' AND '{$toDate}')  ";
        }
        
        $LEFT_JOIN = " LEFT JOIN users u ON u.id = bm.user_id ";
    
        $query = $this->db->query("SELECT COUNT(bm.id) AS total FROM biometric_missing bm $LEFT_JOIN ".$where);
        $res = $query->result_array();
    
        foreach($res as $row){
            $total = $row['total'];
        }
    
        $query = $this->db->query("SELECT bm.*, CONCAT(u.first_name, ' ', u.last_name) as user FROM biometric_missing bm $LEFT_JOIN ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
        $results = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $counter = 1;
    
        foreach ($results as $result) {
            $tempRow = $result;
        
            if ($result['status'] == 0) {
                $tempRow['status'] = '<div class="badge badge-info">'.($this->lang->line('pending') ? htmlspecialchars($this->lang->line('pending')) : 'Pending').'</div>';
            } elseif ($result['status'] == 1) {
                $tempRow['status'] = '<div class="badge badge-success">'.($this->lang->line('approved') ? htmlspecialchars($this->lang->line('approved')) : 'Approved').'</div>';
            } else {
                $tempRow['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected') ? htmlspecialchars($this->lang->line('rejected')) : 'Rejected').'</div>';
            }    
            
            if ($result['type'] == 'check_in') {
                $tempRow['type'] = 'Check In';
            } elseif ($result['type'] == 'check_out') {
                $tempRow['type'] = 'Check Out';
            }
            $tempRow['date'] = format_date($result['date'], system_date_format());
            $tempRow['time'] = format_date($result['time'], system_time_format());
            $tempRow['sr_no'] = $counter;
            $tempRow['employee_id'] = $result['employee_id'];
            if ($this->ion_auth->is_admin() || permissions('biometric_request_view_all')) {
                if ($result['status'] == 1) {
                    $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                } else{
                    if(permissions('biometric_request_edit') && permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-biometric" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_biometric" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(!permissions('biometric_request_edit') && permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_biometric" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(permissions('biometric_request_edit') && !permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-biometric" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(!permissions('biometric_request_edit') && !permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }
                }
            } else {
                if ($result['status'] == 0) {
                    if(permissions('biometric_request_edit') && permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-biometric" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_biometric" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(!permissions('biometric_request_edit') && permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_biometric" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(permissions('biometric_request_edit') && !permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-biometric" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }elseif(!permissions('biometric_request_edit') && !permissions('biometric_request_delete')){
                        $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                    }
                } else {
                    $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 disabled" data-toggle="tooltip" title="'.($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete').'"><i class="fas fa-trash"></i></a></span>';
                }
            }
            $rows[] = $tempRow;
            $counter++;
        }
    
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    
    }

    function create($data){
        if($this->db->insert('biometric_missing', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->update('biometric_missing', $data))
            return true;
        else
            return false;
    }

    function get_shift_time($result){
        $user_id = isset($result['user_id']) ? $result['user_id'] : $this->session->userdata('user_id');
        $shift_query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
        $shift_result = $shift_query->row_array();
        $shift_id = $shift_result['shift_id'];
        $sqlQuery = $this->db->last_query();

        if ($shift_id == 0) {
            return array(
                'check_in' => '09:00 AM',
                'check_out' => '06:00 PM'
            );
        } else {
            $shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
            $shift_result = $shift_query->row_array();
            $sqlQuery = $this->db->last_query();
            return array(
                'check_in' => $shift_result['starting_time'],
                'check_out' => $shift_result['ending_time']
            );
        }  
        
    }

}
