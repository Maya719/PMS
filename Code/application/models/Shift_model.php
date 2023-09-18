<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($id){
        $this->db->where('id', $id);
        if($this->db->delete('shift'))
            return true;
        else
            return false;
    }

    function get_shift_by_id($id)
    {
        $where = " WHERE id = $id ";

        $query = $this->db->query("SELECT * FROM shift " . $where);

        $results = $query->result_array();

        if (!empty($results)) {
            $shift = $results[0];

            $user_ids_query = $this->db->query("SELECT id FROM users WHERE shift_id = $id");
            $user_ids_result = $user_ids_query->result_array();

            if (!empty($user_ids_result)) {
                $user_ids = array_column($user_ids_result, 'id');
                $shift['users'] = implode(',', $user_ids);
            } else {
                $shift['users'] = 'No assigned member';
            }

            return $shift;
        }

        return array();
    }


    function get_user_details($user_id) {
        $query = $this->db->query("SELECT first_name, last_name, profile FROM users WHERE id = ?", $user_id);
        $user_details = $query->row_array(); 
    
        return $user_details;
    }

    function get_shift()
    {
        $offset = 0;
        $limit = 10;
        $sort = 's.id';
        $order = 'ASC';
        $get = $this->input->get();
        $where = '';
        $LEFT_JOIN = '';

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
            $where .= " AND (s.id LIKE '%" . $search . "%' OR s.starting_time LIKE '%" . $search . "%' OR s.ending_time LIKE '%" . $search . "%' OR s.break_start LIKE '%" . $search . "%' OR s.break_end LIKE '%" . $search . "%' OR s.name LIKE '%" . $search . "%')";
        }

        $query = $this->db->query("SELECT COUNT(s.id) AS total FROM shift s $LEFT_JOIN " . $where);
        $res = $query->result_array();

        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT s.*, GROUP_CONCAT(u.id) AS user_ids 
                                FROM shift s
                                LEFT JOIN users u ON u.shift_id = s.id
                                $LEFT_JOIN $where
                                GROUP BY s.id
                                ORDER BY $sort $order
                                LIMIT $offset, $limit");

        $results = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $counter = 1;

        foreach ($results as $result) {
            $tempRow = $result;
            $user_ids = $result['user_ids'];
            $user_ids_array = explode(',', $user_ids);

            $tempRow['users'] = [];

            foreach ($user_ids_array as $user_id) {
                $user_details = $this->get_user_details($user_id);

                if (!empty($user_details)) {
                    $first_name = htmlspecialchars($user_details['first_name']);
                    $last_name = htmlspecialchars($user_details['last_name']);
                    $tempRow['users'][] = $first_name . ' ' . $last_name;
                }
            }
            
            $tempRow['users'] = implode('<br>', $tempRow['users']);

            $tempRow['starting_time'] = format_date($result['starting_time'], system_time_format());
            $tempRow['ending_time'] = format_date($result['ending_time'], system_time_format());
            $tempRow['break_start'] = format_date($result['break_start'], system_time_format());
            $tempRow['break_end'] = format_date($result['break_end'], system_time_format());
            $tempRow['half_day_check_in'] = format_date($result['half_day_check_in'], system_time_format());
            $tempRow['half_day_check_out'] = format_date($result['half_day_check_out'], system_time_format());
            $tempRow['sr_no'] = $counter;
            $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-shift" data-edit="' . $result['id'] . '" data-toggle="tooltip" title="' . ($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit') . '"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_shift" data-id="' . $result['id'] . '" data-toggle="tooltip" title="' . ($this->lang->line('delete') ? htmlspecialchars($this->lang->line('delete')) : 'Delete') . '"><i class="fas fa-trash"></i></a></span>';
           
            $rows[] = $tempRow;
            $counter++;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function create($data){
        if($this->db->insert('shift', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('shift', $data))
            return true;
        else
            return false;
    }

    public function updateUserShiftId($type, $user_ids)
	{
		$this->db->set('shift_id', $type);
		$this->db->where_in('id', $user_ids);
		$this->db->update('users');

        $this->db->where_not_in('id', $user_ids);
        $this->db->where('shift_id', $type);
        $this->db->set('shift_id', 0);
        $this->db->update('users');
	}


    
    

}