<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function create_timesheet($data){
        if($this->db->insert('timesheet', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit_timesheet($data, $timesheet_id = '', $task_id = '', $user_id = ''){
        if(!empty($timesheet_id)){
            $this->db->where('id', $timesheet_id);
        }
        if(!empty($task_id)){
            $this->db->where('task_id', $task_id);
        }
        if(!empty($user_id)){
            $this->db->where('user_id', $user_id);
        }
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->update('timesheet', $data))
            return true;
        else
            return false;
    }
    
    function get_timeline(){
        $offset = 0;
        $limit = 10;
        $sort = 't.id'; $order = 'ASC';
        $get = $this->input->get();
        
        $where = " WHERE t.saas_id = ".$this->session->userdata('saas_id');
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
            $where .= " AND (t.id like '%".$search."%' OR t.status like '%".$search."%' OR t.date_time like ')";
        }

        $where .= " AND t.saas_id = ".$this->session->userdata('saas_id');

        if(isset($get['project_id']) &&  !empty($get['project_id'])){
            $project_id = strip_tags($get['project_id']);
            $where .= " AND t.project_id = $project_id ";
        }

        if(isset($get['task_id']) &&  !empty($get['task_id'])){
            $task_id = strip_tags($get['task_id']);
            $where .= " AND t.task_id = $task_id ";
        }
    
		$LEFT_JOIN = " LEFT JOIN projects p ON p.id=t.project_id ";
		$LEFT_JOIN .= " LEFT JOIN tasks ts ON ts.id=t.task_id ";

        $query = $this->db->query("SELECT COUNT('t.id') as total FROM timeline t $LEFT_JOIN ".$where);
        
        $res = $query->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        
        $query = $this->db->query("SELECT t.*, p.title as project_title, ts.title as task_title FROM timeline t $LEFT_JOIN ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array();  
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($results as $result) {
				$tempRow = $result;
                $tempRow['project_title'] = '<a href="'.base_url('projects/detail/'.$result['project_id']).'" target="_blank">'.$result['project_title'].'</a>';
                
                $tempRow['task_title'] = '<a href="'.base_url('projects/tasks/'.$result['project_id']).'" target="_blank">'.$result['task_title'].'</a>';
                $tempRow['date_time_default'] = $result['date_time'];
                $tempRow['date_time'] = format_date($result['date_time'],system_date_format()." ".system_time_format());

                if($result['status'] == '0')
                {
                    $tempRow['status'] = 'Created';
                }elseif($result['status'] == '1')
                {
                    $tempRow['status'] = 'To Do';
                }elseif($result['status'] == '2')
                {
                    $tempRow['status'] = 'In Progress';
                }elseif($result['status'] == '3')
                {
                    $tempRow['status'] = 'In Review';
                }elseif($result['status'] == '4')
                {
                    $tempRow['status'] = 'Completed';
                }

                $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;

        usort($bulkData['rows'], function($a, $b) {
            return strtotime($a['date_time_default']) - strtotime($b['date_time_default']);
        });

        $createdRows = array();
        $otherRows = array();

        foreach ($bulkData['rows'] as $row) {
            if ($row['status'] == 'Created') {
                $createdRows[] = $row;
            } else {
                $otherRows[] = $row;
            }
        }

        $sortedRows = array_merge($createdRows, $otherRows);

        $bulkData['rows'] = $sortedRows;

        foreach ($bulkData['rows'] as $index => $row) {
            $bulkData['rows'][$index]['s_no'] = $index + 1;
        }

        print_r(json_encode($bulkData));
    }

    function create_timeline($data){
        if($this->db->insert('timeline', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function delete_timesheet($timesheet_id = '', $project_id = '', $task_id = '', $user_id = ''){
        if($timesheet_id){
            $this->db->where('id', $timesheet_id);
        }
        if($project_id){
            $this->db->where('project_id', $project_id);
        }
        if($task_id){
            $this->db->where('task_id', $task_id);
        }
        if($user_id){
            $this->db->where('user_id', $user_id);
        }

        if($timesheet_id = '' && $project_id = '' && $task_id = '' && $user_id = ''){
            return false;
        }

        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->delete('timesheet'))
            return true;
        else
            return false;
    }

    function get_timesheet_by_id($id){
 
        $where = " WHERE t.id = ".$id;
        if(!$this->ion_auth->is_admin()){
            $where .= " AND t.user_id = ".$this->session->userdata('user_id');
        }

        $where .= " AND t.saas_id = ".$this->session->userdata('saas_id');

		$LEFT_JOIN = " LEFT JOIN users u ON u.id=t.user_id ";
		$LEFT_JOIN .= " LEFT JOIN projects p ON p.id=t.project_id ";
		$LEFT_JOIN .= " LEFT JOIN tasks ts ON ts.id=t.task_id ";

        $query = $this->db->query("SELECT t.*, CONCAT(u.first_name, ' ', u.last_name) as user, p.title as project_title, ts.title as task_title FROM timesheet t $LEFT_JOIN ".$where);
    
        $results = $query->result_array();  

        return $results;
    }
    
    function get_timesheet(){
 
        $offset = 0;$limit = 10;
        $sort = 't.id'; $order = 'ASC';
        $get = $this->input->get();
        if($this->ion_auth->is_admin()){
            if(isset($get['user_id']) && !empty($get['user_id'])){
                $where = " WHERE t.user_id = ".$get['user_id'];
            }else{
                $where = " WHERE t.id != '' ";
            }
        }else{
            $where = " WHERE t.user_id = ".$this->session->userdata('user_id');
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
            $where .= " AND (t.id like '%".$search."%' OR u.first_name like '%".$search."%' OR u.last_name like '%".$search."%' OR t.starting_time like '%".$search."%' OR t.ending_time like '%".$search."%' OR p.title like '%".$search."%' OR ts.title like '%".$search."%')";
        }

        $where .= " AND t.saas_id = ".$this->session->userdata('saas_id');

        if(isset($get['project_id']) &&  !empty($get['project_id'])){
            $project_id = strip_tags($get['project_id']);
            $where .= " AND t.project_id = $project_id ";
        }

        if(isset($get['task_id']) &&  !empty($get['task_id'])){
            $task_id = strip_tags($get['task_id']);
            $where .= " AND t.task_id = $task_id ";
        }
    
		$LEFT_JOIN = " LEFT JOIN users u ON u.id=t.user_id ";
		$LEFT_JOIN .= " LEFT JOIN projects p ON p.id=t.project_id ";
		$LEFT_JOIN .= " LEFT JOIN tasks ts ON ts.id=t.task_id ";

        $query = $this->db->query("SELECT COUNT('t.id') as total FROM timesheet t $LEFT_JOIN ".$where);
    
        $res = $query->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        
        $query = $this->db->query("SELECT t.*, CONCAT(u.first_name, ' ', u.last_name) as user, p.title as project_title, ts.title as task_title FROM timesheet t $LEFT_JOIN ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $results = $query->result_array();  
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($results as $result) {
				$tempRow = $result;

                $tempRow['project_title'] = '<a href="'.base_url('projects/detail/'.$result['project_id']).'" target="_blank">'.$result['project_title'].'</a>';
                
                $tempRow['task_title'] = '<a href="'.base_url('projects/tasks/'.$result['project_id']).'" target="_blank">'.$result['task_title'].'</a>';
                $stop = '';
                if($result['ending_time'] && $result['starting_time']){
                    $datetime1 = new DateTime($result['ending_time']);
                    $datetime2 = new DateTime($result['starting_time']);
                    $interval = $datetime1->diff($datetime2);
                    $total_time = $interval->format('%d')>0?"<div><strong>".($this->lang->line('days')?htmlspecialchars($this->lang->line('days')):'Days').":</strong> ".$interval->format('%d')."</div>":"";
                    $total_time .= $interval->format('%h')>0?"<div><strong>".($this->lang->line('hours')?htmlspecialchars($this->lang->line('hours')):'Hours').":</strong> ".$interval->format('%h')."</div>":"";
                    $total_time .= $interval->format('%i')>0?"<div><strong>".($this->lang->line('minutes')?htmlspecialchars($this->lang->line('minutes')):'Minutes').":</strong> ".$interval->format('%i')."</div>":"";
                    $tempRow['total_time'] = $total_time==""?"<strong>".($this->lang->line('hours')?htmlspecialchars($this->lang->line('hours')):'Hours').":</strong> 0":$total_time;
                }else{
                    $tempRow['total_time'] = '<div class="text-danger">'.($this->lang->line('in_progress')?htmlspecialchars($this->lang->line('in_progress')):'In Progress').'</div>';
                    $stop = '<a href="#" class="btn btn-icon btn-sm btn-warning stop_timesheet_timer" data-id="'.$result['id'].'" data-toggle="tooltip" title="Stop"><i class="fas fa-clock"></i></a>';
                }


                $tempRow['starting_time'] = format_date($result['starting_time'],system_date_format()." ".system_time_format());
                $tempRow['ending_time'] = format_date($result['ending_time'],system_date_format()." ".system_time_format());
                

                $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary mr-1 modal-edit-timesheet" data-edit="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger mr-1 delete_timesheet" data-id="'.$result['id'].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>'.$stop.'</span>';
                

                $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }


    function get_comments($type = '',$from_id = '',$to_id = ''){

        $where = " WHERE m.type = '$type' AND m.to_id = $to_id ";
        $order = " ORDER BY m.created DESC ";

        $left_join = " LEFT JOIN users u ON m.from_id=u.id ";

        $query = $this->db->query("SELECT m.*,u.first_name,u.last_name,u.profile FROM messages m $left_join $where $order ");
    
        $comments = $query->result_array();
 
        $temp = [];

        foreach($comments as $key => $comment){
            $temp[$key] = $comment;
            $temp[$key]['can_delete'] = false;
            if($comment['from_id'] == $this->session->userdata('user_id')){
                $temp[$key]['can_delete'] = true;
            }
            if($this->ion_auth->is_admin()){
                $temp[$key]['can_delete'] = true;
            }
            $temp[$key]['created'] = format_date($comment['created'],system_date_format());
            $temp[$key]['profile'] = $comment['profile'];
            $temp[$key]['short_name'] = ucfirst(mb_substr($comment['first_name'], 0, 1, 'utf-8')).''.ucfirst(mb_substr($comment['last_name'], 0, 1, 'utf-8'));
        }
        $comments = $temp;
        if($comments){
            return $comments;
        }else{
            return false;
        }
    }

    function create_comment($data){
        if($this->db->insert('messages', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function task_status_update($task_id, $new_status){
        $this->db->set('status', $new_status);
        $this->db->where('id', $task_id);
        if($this->db->update('tasks'))
            return true;
        else
            return false;
    }

    function delete_task_files($file_id='',$task_id=''){
        if($file_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE id='.$file_id.'');
            $data = $query->result_array();
            if(!empty($data)){
                
                if(file_exists('assets/uploads/tasks/'.$data[0]['file_name'])){
                    $file_upload_path = 'assets/uploads/tasks/'.$data[0]['file_name'];
                }else{
                    $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/tasks/'.$data[0]['file_name'];
                }

                if(unlink($file_upload_path)){
                    $this->db->delete('media_files', array('id' => $file_id));
                }
            }
            return true;
        }elseif($task_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE type="task" AND type_id='.$task_id.'');
            $datas = $query->result_array();
            if(!empty($datas)){
                foreach($datas as $data){
                    
                    if(file_exists('assets/uploads/tasks/'.$data['file_name'])){
                        $file_upload_path = 'assets/uploads/tasks/'.$data['file_name'];
                    }else{
                        $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/tasks/'.$data['file_name'];
                    }

                    if(unlink($file_upload_path)){
                        $this->db->delete('media_files', array('id' => $data['id']));
                    }
                }
            }
            return true;
        }else{
            return false;
        }
        
    }
    function delete_project_files($file_id='',$project_id=''){
        if($file_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE id='.$file_id.'');
            $data = $query->result_array();
            if(!empty($data)){
                
                if(file_exists('assets/uploads/projects/'.$data[0]['file_name'])){
                    $file_upload_path = 'assets/uploads/projects/'.$data[0]['file_name'];
                }else{
                    $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/projects/'.$data[0]['file_name'];
                }

                if(unlink($file_upload_path)){
                    $this->db->delete('media_files', array('id' => $file_id));
                }
            }
            return true;
        }elseif($project_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE type="project" AND type_id='.$project_id.'');
            $datas = $query->result_array();
            if(!empty($datas)){
                foreach($datas as $data){
                    
                    if(file_exists('assets/uploads/projects/'.$data['file_name'])){
                        $file_upload_path = 'assets/uploads/projects/'.$data['file_name'];
                    }else{
                        $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/projects/'.$data['file_name'];
                    }

                    if(unlink($file_upload_path)){
                        $this->db->delete('media_files', array('id' => $data['id']));
                    }
                }
            }
            return true;
        }else{
            return false;
        }
        
    }

    function upload_files($data){
        if($this->db->insert('media_files', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_project($data){
        if($this->db->insert('projects', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_task($data){
        if($this->db->insert('tasks', $data))
        {
            $inserted_id = $this->db->insert_id();
            $this->db->where('id', $inserted_id);
            $query = $this->db->get('tasks');
            $inserted_task_data = $query->row_array(); 
            $timeline_data = array(
                'saas_id' => $inserted_task_data['saas_id'],
                'project_id' => $inserted_task_data['project_id'],
                'task_id' => $inserted_task_data['id'],
                'status' => 0,
                'date_time' => date('Y-m-d H:i:s')
            );
            
            $this->db->insert('timeline', $timeline_data);
            $timeline_data['status'] = $inserted_task_data['status'];
            $this->db->insert('timeline', $timeline_data);
            
            return $inserted_id;
        }
        else
            return false; 
    }

    function edit_task($task_id, $data){
        $this->db->where('id', $task_id);
        if($this->db->update('tasks', $data))
            return true;
        else
            return false;
    }

    function edit_project($project_id, $data){
        $this->db->where('id', $project_id);
        if($this->db->update('projects', $data))
            return true;
        else
            return false;
    }

    function create_project_users($data){
        if($this->db->insert('project_users', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_task_users($data){
        if($this->db->insert('task_users', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function delete_project_users($project_id='',$user_id=''){

        if(empty($project_id) && empty($user_id)){
            return false;
        }

        if(!empty($project_id)){
            $this->db->where('project_id', $project_id);
        }
        if(!empty($user_id)){
            $this->db->where('user_id', $user_id);
        }
        $this->db->delete('project_users');
        return true;
    }

    function delete_task_comment($comment_id='',$user_id='',$type='',$to_id=''){

        if(empty($comment_id) && empty($user_id) && empty($type) && empty($to_id)){
            return false;
        }
        if(!empty($type)){
            $this->db->where('type', $type);
        }
        if(!empty($to_id)){
            $this->db->where('to_id', $to_id);
        }
        if(!empty($comment_id)){
            $this->db->where('id', $comment_id);
        }
        if(!empty($user_id)){
            $this->db->where('from_id', $user_id);
        }
        $this->db->delete('messages');
        return true;
    }

    function delete_task($task_id){
        $this->db->where('id', $task_id);
        if($this->db->delete('tasks'))
            return true;
        else
            return false;
    }

    
    function delete_project($project_id){
        $this->db->where('id', $project_id);
        if($this->db->delete('projects'))
            return true;
        else
            return false;
    }

    function delete_task_users($task_id='',$user_id=''){

        if(empty($task_id) && empty($user_id)){
            return false;
        }

        if(!empty($task_id)){
            $this->db->where('task_id', $task_id);
        }
        if(!empty($user_id)){
            $this->db->where('user_id', $user_id);
        }
        $this->db->delete('task_users');
        return true;
    }

    function get_tasks_files($task_id = '',$user_id = ''){
        $where = "";
        $where .= (!empty($task_id) && is_numeric($task_id))?"AND type_id=$task_id":"";
        $where .= (!empty($user_id) && is_numeric($user_id))?"AND user_id=$user_id":"";
        $query = $this->db->query("SELECT * FROM media_files WHERE type='task' $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_project_files($project_id = '',$user_id = ''){
        $where = "";
        $where .= (!empty($project_id) && is_numeric($project_id))?"AND type_id=$project_id":"";
        $where .= (!empty($user_id) && is_numeric($user_id))?"AND user_id=$user_id":"";
        $query = $this->db->query("SELECT * FROM media_files WHERE type='project' $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_project_users($project_id = ''){
        $where = " WHERE u.saas_id = ".$this->session->userdata('saas_id');
        $where .= (!empty($project_id) && is_numeric($project_id))?" AND pu.project_id=$project_id ":" ";
        $left_join = "LEFT JOIN users u ON pu.user_id=u.id";
        $query = $this->db->query("SELECT u.id,u.email,u.first_name,u.last_name,u.profile FROM project_users pu $left_join $where GROUP BY pu.user_id");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }
    function get_project_users2($project_id = ''){
        
        $where = " WHERE u.saas_id = ".$this->session->userdata('saas_id');
        $where .= (!empty($project_id) && is_numeric($project_id))?" AND pu.project_id=$project_id ":" ";
        $left_join = "LEFT JOIN users u ON pu.user_id=u.id";
        $query = $this->db->query("SELECT u.id,u.email,u.first_name,u.last_name,u.profile FROM project_users pu $left_join $where GROUP BY pu.user_id");
        $data = $query->result_array();
        if($data){
            foreach ($data as &$user) {
                $id = $user["id"];
                $query2 = $this->db->query("SELECT
                COUNT(tu.task_id) AS task_count,
                SUM(CASE WHEN t.status = 4 THEN 1 ELSE 0 END) AS completed
                FROM task_users tu
                JOIN tasks t ON tu.task_id = t.id
                WHERE tu.user_id = $id AND t.project_id = $project_id");
                $data2 = $query2->row_array(); // Use row_array() as we expect a single row
                $user["task_count"] = $data2["completed"].' / '.$data2["task_count"];
                $user["performance"] = $data2["completed"]/$data2["task_count"]*100 .'%';
                $user['action'] = '<span class="d-flex"><a href="'.base_url("projects/tasks?user=$id").'" class="btn btn-sm btn-info view-performance-btn" data-toggle="tooltip" title="'.($this->lang->line('edit') ? htmlspecialchars($this->lang->line('edit')) : 'Edit').'">View Performance</a></span>';
            }
            return $data;
        }else{
            return false;
        }
    }

    function get_task_users($task_id = ''){
        $where = "";
        $where .= (!empty($task_id) && is_numeric($task_id))?"WHERE pu.task_id=$task_id":"";
        $left_join = "LEFT JOIN users u ON pu.user_id=u.id";
        $query = $this->db->query("SELECT u.id,u.email,u.first_name,u.last_name,u.profile FROM task_users pu $left_join $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_clients_projects($user_id){
        if($user_id){
            $query = $this->db->query("SELECT * FROM projects WHERE client_id=$user_id");
        }else{
            $query = $this->db->query("SELECT * FROM projects");
        }
        $projects = $query->result_array();
        if($projects){
            return $projects;
        }else{
            return false;
        }
    }

    function get_projects_list(){
 
        $offset = 0;$limit = 10;
        $sort = 'p.id'; $order = 'ASC';
        $get = $this->input->get();
        $where = '';

        $where = "WHERE p.saas_id=".$this->session->userdata('saas_id');

        if(!$this->ion_auth->is_admin()  && !permissions('project_view_all')) {
            if(is_client()){
                if(empty($get['client'])){
                    $where .= " AND p.client_id=".$this->session->userdata('user_id');
                }
            }else{
                if(empty($get['user'])){
                    $where .= " AND pu.user_id=".$this->session->userdata('user_id');
                }
            }
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
            $where .= " AND (p.id like '%".$search."%' OR p.title like '%".$search."%' OR ps.title like '%".$search."%' OR p.description like '%".$search."%' OR p.status like '%".$search."%')";
        }

        if(isset($get['status']) &&  !empty($get['status']) && is_numeric($get['status'])){
            $status = strip_tags($get['status']);
            $where .= " AND ps.id = $status";
        }

        if(isset($get['user']) &&  !empty($get['user']) && is_numeric($get['user'])){
            $user = strip_tags($get['user']);
            $where .= " AND pu.user_id = $user";
        }

        if(isset($get['client']) &&  !empty($get['client']) && is_numeric($get['client'])){
            $client = strip_tags($get['client']);
            $where .= " AND p.client_id = $client";
        }

        $left_join = " LEFT JOIN projects p ON pu.project_id=p.id ";
        $left_join .= " LEFT JOIN project_status ps ON p.status=ps.id ";
        
        $query = $this->db->query("SELECT COUNT('p.id') as total FROM project_users pu $left_join $where GROUP BY pu.project_id");
        // echo $this->db->last_query();
        $res = $query->result_array();
        // foreach($res as $row){
             $total = count($res);
        // }
        
        $query = $this->db->query("SELECT p.*,ps.title AS project_status,ps.class AS project_class FROM project_users pu $left_join $where GROUP BY pu.project_id ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit);

        $results = $query->result_array();  
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($results as $result) {
				$tempRow = $result;
            
                $tempRow['title'] = '<a href="'.base_url('projects/detail/'.htmlspecialchars($result['id'])).'">'.htmlspecialchars($result['title']).'</a>';

                if($tempRow['project_status'] == 'Not Started'){
                    $tempRow['project_status'] = '<span class="mb-1 badge badge-'.htmlspecialchars($result['project_class']).'">
						'.($this->lang->line('not_started')?$this->lang->line('not_started'):'Not Started').'
						</span>';
                }elseif($tempRow['project_status'] == 'On Going'){
                    $tempRow['project_status'] = '<span class="mb-1 badge badge-'.htmlspecialchars($result['project_class']).'">
						'.($this->lang->line('on_going')?$this->lang->line('on_going'):'On Going').'
						</span>';
                }elseif($tempRow['project_status'] == 'Finished'){
                    $tempRow['project_status'] = '<span class="mb-1 badge badge-'.htmlspecialchars($result['project_class']).'">
						'.($this->lang->line('finished')?$this->lang->line('finished'):'Finished').'
						</span>';
                }
                $tempRow['starting_date'] = format_date($result['starting_date'],system_date_format());
                if ($result['ending_date'] == "0000-00-00"){
                    $tempRow['ending_date'] = "Present";
                    $days_count = count_days_btw_two_dates(date("Y-m-d"),$result['starting_date']);
                    $tempRow['stats'] = '
						<i class="fas fa-calendar-alt"></i> '.htmlspecialchars($days_count['days']).' '.($this->lang->line('days')?$this->lang->line('days'):'Days').'
						<br>
						<i class="fas fa-layer-group"></i> '.(get_count('id','tasks','status = 4 and project_id='.$result['id'])).'/'.(get_count('id','tasks','project_id='.$result['id'])).' '.($this->lang->line('task_completed')?$this->lang->line('task_completed'):'Task Completed');
                }else{
                    $tempRow['ending_date'] = format_date($result['ending_date'],system_date_format());
                    $days_count = count_days_btw_two_dates(date("Y-m-d"),$result['ending_date']);
    
                    $tempRow['stats'] = '
                            <i class="fas fa-calendar-alt"></i> '.htmlspecialchars($days_count['days']).' '.($this->lang->line('days')?$this->lang->line('days'):'Days').' '.htmlspecialchars($days_count['days_status']).'
                            <br>
                            <i class="fas fa-layer-group"></i> '.(get_count('id','tasks','status = 4 and project_id='.$result['id'])).'/'.(get_count('id','tasks','project_id='.$result['id'])).' '.($this->lang->line('task_completed')?$this->lang->line('task_completed'):'Task Completed');
                }
						
                if($result['client_id']){
                    $tempRow['project_client'] = $project_client = $this->ion_auth->user($result['client_id'])->row();
                    
                }else{
                    $tempRow['project_client'] = $project_client = null;
                }

                if(!empty($project_client)){
                    $tempRow['project_client'] = $project_client->first_name.' '.$project_client->last_name;
                }else{
                    $tempRow['project_client'] = '';
                }

                $tempRow['project_users'] = $project_users = $this->get_project_users($result['id']);
                $tempRow['project_users_ids'] = '';
                if(!empty($project_users)){
                    foreach($project_users as  $pkey => $project_user){
                        $tempid[$pkey] = $project_user['id'];
                    }
                    $tempRow['project_users_ids'] = implode(",",$tempid);
                    $tempRow['project_users_ids_array'] = $tempid;
                }

                $profile_html = '';
                if(!empty($project_users)){ 
                    foreach($project_users as $project_user){ 
                    if(!empty($project_user['profile'])){
                            if(file_exists('assets/uploads/profiles/'.$project_user['profile'])){
                            $file_upload_path = 'assets/uploads/profiles/'.$project_user['profile'];
                            }else{
                            $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$project_user['profile'];
                            }

                        $profile_html .= '<figure class="avatar avatar-sm mr-1">
                            <img src="'.base_url($file_upload_path).'" alt="'.htmlspecialchars($project_user['first_name']).' '.htmlspecialchars($project_user['last_name']).'" data-toggle="tooltip" data-placement="top" title="'.htmlspecialchars($project_user['first_name']).' '.htmlspecialchars($project_user['last_name']).'">
                        </figure>';
                    }else{
                        $profile_html .= '<figure class="avatar avatar-sm bg-primary text-white mr-1" data-initial="'.ucfirst(mb_substr(htmlspecialchars($project_user['first_name']), 0, 1, 'utf-8')).''.ucfirst(mb_substr(htmlspecialchars($project_user['last_name']), 0, 1, 'utf-8')).'" data-toggle="tooltip" data-placement="top" title="'.htmlspecialchars($project_user['first_name']).' '.htmlspecialchars($project_user['last_name']).'">
                        </figure>';
                    } } 
                }
                $tempRow['project_users'] = $profile_html;


                $action_btn = '';
                if ($this->ion_auth->is_admin() || permissions('project_edit')){  
                    $action_btn .= '<a href="#" data-edit="'.htmlspecialchars($result['id']).'" class="modal-edit-project dropdown-item">'.($this->lang->line('edit')?$this->lang->line('edit'):'Edit').'</a>';
                }

                if ($this->ion_auth->is_admin() || permissions('task_view')){
                    $action_btn .= '<a class="dropdown-item" href="'.base_url("projects/tasks/".htmlspecialchars($result['id'])).'">'.($this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks').'</a>';
                }

                if ($this->ion_auth->is_admin() || permissions('project_delete')){
                    $action_btn .= '<a href="#" class="text-danger delete_project dropdown-item " data-id="'.htmlspecialchars($result['id']).'">'.($this->lang->line('trash')?$this->lang->line('trash'):'Trash').'</a>';
                }
                    
                $tempRow['action'] = '<a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
                <div class="dropdown-menu">

                <a class="dropdown-item" href="'.base_url("projects/detail/".htmlspecialchars($result['id'])).'">'.($this->lang->line('details')?$this->lang->line('details'):'Details').'</a>
                '.$action_btn.'
                </div>';

                
                $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    
    function get_projects($user_id = '',$project_id = '',$limit='', $start='', $filter_type='', $filter=''){

        if(!empty($limit)){
            $where_limit = ' LIMIT '.$limit;
            if(!empty($start)){
                $where_limit .= ' OFFSET '.$start;
            }
        }else{
            $where_limit = '';
        }

        $where = "";
        $order = " ORDER BY p.created DESC ";

        if(!empty($filter_type) && !empty($filter) && is_numeric($filter) && $filter_type == 'status'){
            $where = "WHERE ps.id = $filter";
        }elseif(!empty($filter_type) && !empty($filter) && is_numeric($filter) && $filter_type == 'user'){
            $where = "WHERE pu.user_id = $filter";
        }elseif(!empty($filter_type) && !empty($filter) && is_numeric($filter) && $filter_type == 'client'){
            $where = "WHERE p.client_id = $filter";
        }elseif(!empty($filter_type) && !empty($filter) && $filter_type == 'sortby'){
            if($filter == 'old'){
                $order = " ORDER BY p.created ASC ";
            }elseif($filter == 'name'){
                $order = " ORDER BY p.title ASC ";
            }else{
                $order = " ORDER BY p.created DESC ";
            }
            
        }

        $where .= (!empty($project_id) && is_numeric($project_id) && empty($where))?"WHERE pu.project_id=$project_id":"";

        if(!empty($user_id) && is_numeric($user_id)){
            if(is_client()){
                $where .= (empty($where))?" WHERE p.client_id=$user_id ":" AND p.client_id=$user_id ";
            }else{
                $where .= (empty($where))?" WHERE pu.user_id=$user_id ":"";
            }
        }
        $where .= empty($where)?" WHERE p.saas_id=".$this->session->userdata('saas_id'):" AND p.saas_id=".$this->session->userdata('saas_id');

        $left_join = " LEFT JOIN projects p ON pu.project_id=p.id ";
        $left_join .= " LEFT JOIN project_status ps ON p.status=ps.id ";
        $query = $this->db->query("SELECT p.*,ps.title AS project_status,ps.class AS project_class FROM project_users pu $left_join $where GROUP BY pu.project_id $order $where_limit");
    
        $projects = $query->result_array();

        $temp = [];

        foreach($projects as $key => $project){
            $temp[$key] = $project;
            
            if($temp[$key]['project_status'] == 'Not Started'){
                $temp[$key]['project_status'] = $this->lang->line('not_started')?$this->lang->line('not_started'):'Not Started';
            }elseif($temp[$key]['project_status'] == 'On Going'){
                $temp[$key]['project_status'] = $this->lang->line('on_going')?$this->lang->line('on_going'):'On Going';
            }elseif($temp[$key]['project_status'] == 'Finished'){
                $temp[$key]['project_status'] = $this->lang->line('finished')?$this->lang->line('finished'):'Finished';
            }
            $temp[$key]['starting_date'] = format_date($project['starting_date'],system_date_format());
            if ($project['ending_date'] == "0000-00-00"){
                $temp[$key]['ending_date'] = "Present";
                $days_count = count_days_btw_two_dates(date("Y-m-d"),$project['starting_date']);
                $temp[$key]['days_count'] = $days_count['days'];
                $temp[$key]['days_status'] = ' ';
            }else{
                $temp[$key]['ending_date'] = format_date($project['ending_date'],system_date_format());
                $days_count = count_days_btw_two_dates(date("Y-m-d"),$project['ending_date']);
                $temp[$key]['days_count'] = $days_count['days'];
                $temp[$key]['days_status'] = $days_count['days_status'];
            }
            $temp[$key]['total_tasks'] = get_count('id','tasks','project_id='.$project['id']);
            $temp[$key]['completed_tasks'] = get_count('id','tasks','status = 4 and project_id='.$project['id']);
            if($project['client_id']){
                $temp[$key]['project_client'] = $this->ion_auth->user($project['client_id'])->row();
                
            }else{
                $temp[$key]['project_client'] = null;
            }
            $temp[$key]['project_users'] = $project_users = $this->get_project_users($project['id']);
            $temp[$key]['project_users_ids'] = '';
            if(!empty($project_users)){
                foreach($project_users as  $pkey => $project_user){
                    $tempid[$pkey] = $project_user['id'];
                }
                $temp[$key]['project_users_ids'] = implode(",",$tempid);
                $temp[$key]['project_users_ids_array'] = $tempid;
            }
            
        }
        $projects = $temp;
        if($projects){
            return $projects;
        }else{
            return false;
        }
    }

    function get_tasks($user_id = '',$task_id = '',$project_id = '', $search = '', $sort = 'created', $priority = '', $upcoming = ''){

        $where = "";

        if($sort == 'old'){
            $order = " ORDER BY t.created ASC ";
        }elseif($sort == 'name'){
            $order = " ORDER BY t.title ASC ";
        }elseif($sort == 'start'){
            $order = " ORDER BY t.starting_date ASC ";
        }elseif($sort == 'due'){
            $order = " ORDER BY t.due_date ASC ";
        }else{
            $order = " ORDER BY t.created DESC ";
        }

        $where .= (!empty($task_id) && is_numeric($task_id) && empty($where))?"WHERE t.id=$task_id":"";
        
        if(!empty($project_id) && is_numeric($project_id) && empty($where)){
            $where .="WHERE p.id=$project_id";
        }elseif(!empty($project_id) && is_numeric($project_id) && !empty($where)){
            $where .=" AND p.id=$project_id";
        }

        if(!empty($user_id) && is_numeric($user_id)){
            if(is_client()){
                $where .= (empty($where))?" WHERE p.client_id=$user_id ":" AND p.client_id=$user_id ";
            }else{
                $where .= (empty($where))?" WHERE tu.user_id=$user_id ":"";
            }
        }

        $where .= empty($where)?" WHERE t.saas_id=".$this->session->userdata('saas_id'):" AND t.saas_id=".$this->session->userdata('saas_id');

        if(!empty($search)){
            $where .= (empty($where))?" WHERE (t.title like '%".$search."%') ":" AND (t.title like '%".$search."%') ";
        }

        if(!empty($priority)){
            $where .= empty($where)?" WHERE tp.id=".$priority:" AND tp.id=".$priority;
        }

        if(!empty($upcoming)){
            $today_date = date("Y-m-d");
            $dt = strtotime(date("Y-m-d"));
            if($upcoming == 'all'){
                $upcoming_date = date("Y-m-d", strtotime("+100 years", $dt));
            }elseif($upcoming == 1){
                $upcoming_date = date("Y-m-d");
            }elseif($upcoming == 2){
                $today_date = date("Y-m-d", strtotime("+1 day", $dt));
                $upcoming_date = date("Y-m-d", strtotime("+1 day", $dt));
            }elseif($upcoming == 30){
                $upcoming_date = date("Y-m-d", strtotime("+1 month", $dt));
            }elseif($upcoming == 3){
                $upcoming_date = date("Y-m-d", strtotime("+3 days", $dt));
            }elseif($upcoming == 7){
                $upcoming_date = date("Y-m-d", strtotime("+7 days", $dt));
            }elseif($upcoming == 15){
                $upcoming_date = date("Y-m-d", strtotime("+15 days", $dt));
            }else{
                $upcoming_date = '';
            }
            if($upcoming_date != ''){
                $where .= empty($where)?" WHERE t.starting_date BETWEEN '$today_date' AND '$upcoming_date' ":" AND t.starting_date BETWEEN '$today_date' AND '$upcoming_date' ";
            }
        }

        $left_join = " LEFT JOIN tasks t ON tu.task_id=t.id ";
        $left_join .= " LEFT JOIN task_status ts ON t.status=ts.id ";
        $left_join .= " LEFT JOIN priorities tp ON t.priority=tp.id ";
        $left_join .= " LEFT JOIN projects p ON t.project_id=p.id ";
        $query = $this->db->query("SELECT t.*,ts.title AS task_status,ts.class AS task_class,tp.title AS task_priority,tp.class AS priority_class,p.title AS project_title FROM task_users tu $left_join $where GROUP BY tu.task_id $order ");

        $tasks = $query->result_array();
        // echo $this->db->last_query();
        $temp = [];

        foreach($tasks as $key => $task){
            $temp[$key] = $task;
            
            if(!$this->ion_auth->in_group(4)){
                $temp[$key]['can_see_time'] = true;
            }else{
                $temp[$key]['can_see_time'] = false;
            }

            if(check_my_timer($task['id'])){
                $temp[$key]['timer_running'] = true;
                $timer_running_id = check_my_timer($task['id']);
                $temp[$key]['timer_running_id'] = isset($timer_running_id[0]['id'])?$timer_running_id[0]['id']:'';
            }else{
                $temp[$key]['timer_running'] = false;
            }

            if($task['task_status'] == 'Todo'){
                $temp[$key]['task_status'] = $this->lang->line('todo')?$this->lang->line('todo'):'Todo';
            }elseif($task['task_status'] == 'In Progress'){
                $temp[$key]['task_status'] = $this->lang->line('in_progress')?$this->lang->line('in_progress'):'In Progress';
            }elseif($task['task_status'] == 'In Review'){
                $temp[$key]['task_status'] = $this->lang->line('in_review')?$this->lang->line('in_review'):'In Review';
            }elseif($task['task_status'] == 'Completed'){
                $temp[$key]['task_status'] = $this->lang->line('completed')?$this->lang->line('completed'):'Completed';
            }

            if($task['task_priority'] == 'Low'){
                $temp[$key]['task_priority'] = $this->lang->line('low')?$this->lang->line('low'):'Low';
            }elseif($task['task_priority'] == 'Medium'){
                $temp[$key]['task_priority'] = $this->lang->line('medium')?$this->lang->line('medium'):'Medium';
            }elseif($task['task_priority'] == 'High'){
                $temp[$key]['task_priority'] = $this->lang->line('high')?$this->lang->line('high'):'High';
            }

            $temp[$key]['due_date'] = format_date($task['due_date'],system_date_format());
            $temp[$key]['starting_date'] = $task['starting_date']?format_date($task['starting_date'],system_date_format()):'';
            $days_count = count_days_btw_two_dates(date("Y-m-d"),$task['due_date']);
            $temp[$key]['days_count'] = $days_count['days'];
            $temp[$key]['days_status'] = $days_count['days_status'];
            $temp[$key]['task_users'] = $task_users = $this->get_task_users($task['id']);
            $temp[$key]['task_users_ids'] = '';
            if(!empty($task_users)){
                foreach($task_users as  $pkey => $task_user){
                    $tempid[$pkey] = $task_user['id'];
                }
                $temp[$key]['task_users_ids'] = implode(",",$tempid);
            }
            if ($task['task_status'] == 'In Review') {
                $latestReviewDate = $this->db->select('date_time')
                    ->from('timeline')
                    ->where('task_id', $task['id'])
                    ->where('status', 3)
                    ->order_by('date_time', 'DESC')
                    ->limit(1)
                    ->get()
                    ->row();
        
                $review_date = !empty($latestReviewDate) ? format_date($latestReviewDate->date_time,system_date_format()) : '';
                $review_days_count = count_days_btw_two_dates(date("Y-m-d"),$review_date);
                $temp[$key]['review_days_count'] = $review_days_count['days'];
            }
            
        }
        $tasks = $temp;
        if($tasks){
            return $tasks;
        }else{
            return false;
        }
    }

    
    function get_tasks_list(){
 
        $offset = 0;
        $limit = 10;
        $sort = 'p.id'; 
        $order = 'ASC';
        $get = $this->input->get();
        $where = '';
        $where = "WHERE t.saas_id=".$this->session->userdata('saas_id');

        if(!$this->ion_auth->is_admin()){
            if(is_client()){
                $where .= " AND p.client_id=".$this->session->userdata('user_id');
            }else{
                if(empty($get['user'])){
                    $where .= " AND tu.user_id=".$this->session->userdata('user_id');
                }
            }
        }

        if(isset($get['sort']))
            $sort = strip_tags($get['sort']);
        if(isset($get['offset']))
            $offset = strip_tags($get['offset']);
        if(isset($get['limit']))
            $limit = strip_tags($get['limit']);
        if(isset($get['order']))
            $order = strip_tags($get['order']);

        if(isset($get['project']) && !empty($get['project']) && is_numeric($get['project'])){
            $project = strip_tags($get['project']);
            $where .= " AND p.id=$project ";
        }

        if(isset($get['user']) &&  !empty($get['user']) && is_numeric($get['user'])){
            $user = strip_tags($get['user']);
            $where .= " AND tu.user_id = $user";
        }

        if(isset($get['status']) &&  !empty($get['status']) && is_numeric($get['status'])){
            $status = strip_tags($get['status']);
            $where .= " AND t.status = $status";
        }
        if(isset($get['priority']) &&  !empty($get['priority']) && is_numeric($get['priority'])){
            $priority = strip_tags($get['priority']);
            $where .= " AND t.priority = $priority";
        }

        if(isset($get['search']) &&  !empty($get['search'])){
            $search = strip_tags($get['search']);
            $where .= " AND (p.id like '%".$search."%' OR p.title like '%".$search."%' OR p.description like '%".$search."%' OR p.status like '%".$search."%' OR t.title like '%".$search."%' OR t.description like '%".$search."%' OR t.saas_id like '%".$search."%' OR t.project_id like '%".$search."%' OR t.priority like '%".$search."%' OR t.status like '%".$search."%')";
        }

        if(isset($get['upcoming']) &&  !empty($get['upcoming'])){
            $upcoming = strip_tags($get['upcoming']);
            $today_date = date("Y-m-d");
            $dt = strtotime(date("Y-m-d"));
            if($upcoming == 'all'){
                $upcoming_date = date("Y-m-d", strtotime("+100 years", $dt));
            }elseif($upcoming == 1){
                $upcoming_date = date("Y-m-d");
            }elseif($upcoming == 2){
                $today_date = date("Y-m-d", strtotime("+1 day", $dt));
                $upcoming_date = date("Y-m-d", strtotime("+1 day", $dt));
            }elseif($upcoming == 30){
                $upcoming_date = date("Y-m-d", strtotime("+1 month", $dt));
            }elseif($upcoming == 3){
                $upcoming_date = date("Y-m-d", strtotime("+3 days", $dt));
            }elseif($upcoming == 7){
                $upcoming_date = date("Y-m-d", strtotime("+7 days", $dt));
            }elseif($upcoming == 15){
                $upcoming_date = date("Y-m-d", strtotime("+15 days", $dt));
            }else{
                $upcoming_date = '';
            }
            if($upcoming_date != ''){
                $where .= " AND t.starting_date BETWEEN '$today_date' AND '$upcoming_date' ";
            }
        }

        $left_join = " LEFT JOIN tasks t ON tu.task_id=t.id ";
        $left_join .= " LEFT JOIN task_status ts ON t.status=ts.id ";
        $left_join .= " LEFT JOIN priorities tp ON t.priority=tp.id ";
        $left_join .= " LEFT JOIN projects p ON t.project_id=p.id ";
        $query = $this->db->query("SELECT t.*,ts.title AS task_status,ts.class AS task_class,tp.title AS task_priority,tp.class AS priority_class,p.title AS project_title FROM task_users tu $left_join $where GROUP BY tu.task_id $order ");

        // echo $this->db->last_query();
        $res = $query->result_array();
        // foreach($res as $row){
             $total = count($res);
        // }
        
        $query = $this->db->query("SELECT t.*,ts.title AS task_status,ts.class AS task_class,tp.title AS task_priority,tp.class AS priority_class,p.title AS project_title FROM task_users tu $left_join $where GROUP BY tu.task_id $order LIMIT $limit OFFSET $offset");

        $results = $query->result_array();  
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($results as $result) {
				$tempRow = $result;
            
                $tempRow['title'] = '<a href="#" data-edit="'.htmlspecialchars($result['id']).'" class="modal-task-detail">'.htmlspecialchars($result['title']).'</a>';
                
                $tempRow['project_id'] = '<a href="'.base_url('projects/detail/'.htmlspecialchars($result['project_id'])).'" target="_blank">'.htmlspecialchars($result['project_title']).'</a>';

                $tempRow['starting_date'] = $result['starting_date']?format_date($result['starting_date'],system_date_format()):'';
                $tempRow['due_date'] = format_date($result['due_date'],system_date_format());
                $days_count = count_days_btw_two_dates(date("Y-m-d"),$result['due_date']);

                $stat_label = htmlspecialchars($days_count['days']).' '.($this->lang->line('days')?$this->lang->line('days'):'Days').' '.htmlspecialchars($days_count['days_status']);


                if($result['task_status'] == 'Todo'){
                    $tempRow['status'] = '<span class="badge badge-'.htmlspecialchars($result['task_class']).'">
						'.($this->lang->line('todo')?$this->lang->line('todo'):'Todo').'
						</span>';
                }elseif($result['task_status'] == 'In Progress'){
                    $tempRow['status'] = '<span class="badge badge-'.htmlspecialchars($result['task_class']).'">
                    '.($this->lang->line('in_progress')?$this->lang->line('in_progress'):'In Progress').'
                    </span>';
                }elseif($result['task_status'] == 'In Review'){
                    $tempRow['status'] = '<span class="badge badge-'.htmlspecialchars($result['task_class']).'">
                    '.($this->lang->line('in_review')?$this->lang->line('in_review'):'In Review').'
                    </span>';
                    $latestReviewDate = $this->db->select('date_time')
                        ->from('timeline')
                        ->where('task_id', $result['id'])
                        ->where('status', 3)
                        ->order_by('date_time', 'DESC')
                        ->limit(1)
                        ->get()
                        ->row();
                    $review_date = !empty($latestReviewDate) ? format_date($latestReviewDate->date_time,system_date_format()) : '';
                    $review_days_count = count_days_btw_two_dates(date("Y-m-d"),$review_date);
                    $stat_label = $review_days_count['days'].' '.($this->lang->line('days')?$this->lang->line('days'):'Days').' In Review';
                }elseif($result['task_status'] == 'Completed'){
                    $tempRow['status'] = '<span class="badge badge-'.htmlspecialchars($result['task_class']).'">
                    '.($this->lang->line('completed')?$this->lang->line('completed'):'Completed').'
                    </span>';
                    $stat_label = '<span class="badge badge-'.htmlspecialchars($result['task_class']).'">
                    '.($this->lang->line('completed')?$this->lang->line('completed'):'Completed').'
                    </span>';
                }
    
                if($result['task_priority'] == 'Low'){
                    $tempRow['priority'] = '<span class="badge badge-'.htmlspecialchars($result['priority_class']).'">
                    '.($this->lang->line('low')?$this->lang->line('low'):'Low').'
                    </span>';
                }elseif($result['task_priority'] == 'Medium'){
                    $tempRow['priority'] = '<span class="badge badge-'.htmlspecialchars($result['priority_class']).'">
                    '.($this->lang->line('medium')?$this->lang->line('medium'):'Medium').'
                    </span>';
                }elseif($result['task_priority'] == 'High'){
                    $tempRow['priority'] = '<span class="badge badge-'.htmlspecialchars($result['priority_class']).'">
                    '.($this->lang->line('high')?$this->lang->line('high'):'High').'
                    </span>';
                }

				$tempRow['stats'] = '<i class="fas fa-calendar-alt"></i> '.$stat_label;

                $task_users = $this->get_task_users($result['id']);

                $profile_html = '';
                if(!empty($task_users)){ 
                    foreach($task_users as $task_user){ 
                    if(!empty($task_user['profile'])){
                            if(file_exists('assets/uploads/profiles/'.$task_user['profile'])){
                            $file_upload_path = 'assets/uploads/profiles/'.$task_user['profile'];
                            }else{
                            $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$task_user['profile'];
                            }

                        $profile_html .= '<figure class="avatar avatar-sm mr-1">
                            <img src="'.base_url($file_upload_path).'" alt="'.htmlspecialchars($task_user['first_name']).' '.htmlspecialchars($task_user['last_name']).'" data-toggle="tooltip" data-placement="top" title="'.htmlspecialchars($task_user['first_name']).' '.htmlspecialchars($task_user['last_name']).'">
                        </figure>';
                    }else{
                        $profile_html .= '<figure class="avatar avatar-sm bg-primary text-white mr-1" data-initial="'.ucfirst(mb_substr(htmlspecialchars($task_user['first_name']), 0, 1, 'utf-8')).''.ucfirst(mb_substr(htmlspecialchars($task_user['last_name']), 0, 1, 'utf-8')).'" data-toggle="tooltip" data-placement="top" title="'.htmlspecialchars($task_user['first_name']).' '.htmlspecialchars($task_user['last_name']).'">
                        </figure>';
                    } } 
                }
                $tempRow['task_users'] = $profile_html;
                

                $action_btn = '';

                if ($this->ion_auth->is_admin() || permissions('task_edit')){  
                    $action_btn .= '<a href="#" data-edit="'.htmlspecialchars($result['id']).'" class="modal-edit-task dropdown-item">'.($this->lang->line('edit')?$this->lang->line('edit'):'Edit').'</a>';
                }

                if ($this->ion_auth->is_admin() || permissions('task_delete')){
                    $action_btn .= '<a href="#" class="text-danger delete_task dropdown-item" data-id="'.htmlspecialchars($result['id']).'">'.($this->lang->line('trash')?$this->lang->line('trash'):'Trash').'</a>';
                }

                $tempRow['action'] = '<a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
                <div class="dropdown-menu">

                <a class="dropdown-item modal-task-detail" href="#" data-edit="'.htmlspecialchars($result['id']).'">'.($this->lang->line('details')?$this->lang->line('details'):'Details').'</a>
                '.$action_btn.'
                </div>';

                
                $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }


}