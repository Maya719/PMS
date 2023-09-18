<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function get_saas_users(){
 
        $offset = 0;$limit = 10;
        $sort = 'id'; $order = 'ASC';
        $where = ' WHERE g.id=1 AND u.id=u.saas_id ';
        $get = $this->input->get();
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
            $where .= " AND (u.id like '%".$search."%' OR u.first_name like '%".$search."%' OR u.last_name like '%".$search."%' OR u.email like '%".$search."%' OR p.title like '%".$search."%')";
        }
    
        if(isset($get['filter']) && !empty($get['filter'])){
            $filter = strip_tags($get['filter']);
            if($filter == 'all'){
            }else{
                $where .= " AND up.plan_id=$filter ";
            }
        }
    
        $query = $this->db->query("SELECT COUNT('u.id') as total FROM users u 
        LEFT JOIN users_groups ug ON u.id=ug.user_id
        LEFT JOIN `groups` g ON ug.group_id=g.id 
        LEFT JOIN users_plans up ON u.saas_id=up.saas_id
        LEFT JOIN plans p ON up.plan_id=p.id
        ".$where);
    
        $res = $query->result_array();
        foreach($res as $row){
            $total = $row['total'];
        }
        
        $query = $this->db->query("SELECT * FROM users u 
        LEFT JOIN users_groups ug ON u.id=ug.user_id
        LEFT JOIN `groups` g ON ug.group_id=g.id 
        LEFT JOIN users_plans up ON u.saas_id=up.saas_id
        LEFT JOIN plans p ON up.plan_id=p.id
        ".$where." ORDER BY u.".$sort." ".$order." LIMIT ".$offset.", ".$limit);
    
        $system_users = $query->result();   
    
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($system_users as $system_user) {
            if($system_user->user_id == $system_user->saas_id){
                $tempRow['id'] = $system_user->user_id;
                $tempRow['email'] = $system_user->email;

                $profile = '<figure class="avatar avatar-sm mr-2" data-initial="'.mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8").'"></figure>';
                if($system_user->profile){
                    if(file_exists('assets/uploads/profiles/'.$system_user->profile)){
                        $file_upload_path = 'assets/uploads/profiles/'.$system_user->profile;
                    }else{
                        $file_upload_path = 'assets/uploads/f'.$system_user->saas_id.'/profiles/'.$system_user->profile;
                    }
                    $profile = '<img alt="image" src="'.base_url($file_upload_path).'" class="avatar avatar-sm mr-2">';
                }

                $tempRow['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-primary  modal-edit-user" data-edit="'.$system_user->user_id.'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a></span>';

                $tempRow['first_name'] = '<li class="media">
                    '.$profile.'
                    <div>
                    <div class="media-title">'.$system_user->first_name.' '.$system_user->last_name.'</div>
                    <span class="text-small text-muted">'.$system_user->email.'</span>
                    </div>
                </li>';
                
                $system_user_billing_type = $system_user->billing_type; 
                if($system_user->billing_type == 'Monthly'){
                    $system_user_billing_type = $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
                }elseif($system_user->billing_type == 'Yearly'){
                    $system_user_billing_type = $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
                }elseif($system_user->billing_type == 'One Time'){
                    $system_user_billing_type = $this->lang->line('one_time')?$this->lang->line('one_time'):'One Time';
                }elseif($system_user->billing_type == 'three_days_trial_plan'){
                    $system_user_billing_type = $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
                }elseif($system_user->billing_type == 'seven_days_trial_plan'){
                    $system_user_billing_type = $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
                }elseif($system_user->billing_type == 'fifteen_days_trial_plan'){
                    $system_user_billing_type = $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
                }elseif($system_user->billing_type == 'thirty_days_trial_plan'){
                    $system_user_billing_type = $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
                }

                $tempRow['plan'] = '<li class="media">
                    <div>
                    <div class="media-title mb-0">'.$system_user->title.'</div>
                    <span class="text-small text-muted"> '.($this->lang->line('billing_type')?$this->lang->line('billing_type'):'Billing Type').': <strong>'.$system_user_billing_type.'</strong></span><br>
                    <span class="text-small text-muted"> '.($this->lang->line('expiring')?$this->lang->line('expiring'):'Expiring').': '.($system_user->end_date != NULL?format_date($system_user->end_date,system_date_format()):($this->lang->line('no_expiry_date')?htmlspecialchars($this->lang->line('no_expiry_date')):'No Expiry Date')).'</span>
                    </div>
                </li>';
                

                $tempRow['features'] = '
                <strong>'.($this->lang->line('storage')?$this->lang->line('storage'):'Storage').': </strong>'.formatBytes(check_my_storage('', $system_user->user_id), 'bytes').'/'.($system_user->storage<0?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$system_user->storage).'GB<br>

                <strong>'.($this->lang->line('projects')?$this->lang->line('projects'):'Projects').': </strong>'.get_count('id','projects','saas_id='.$system_user->user_id).'/'.($system_user->projects<0?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$system_user->projects).'<br>

                <strong>'.($this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks').': </strong>'.get_count('id','tasks','saas_id='.$system_user->user_id).'/'.($system_user->tasks<0?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$system_user->tasks).'<br>
                <strong>'.($this->lang->line('users')?$this->lang->line('users'):'Users').': </strong>'.get_count('id','users','saas_id='.$system_user->user_id).'/'.($system_user->users<0?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$system_user->users);

                $tempRow['status'] = '
                <strong>'.($this->lang->line('user')?$this->lang->line('user'):'User').': </strong>'.(($system_user->active==1)?'<span class="badge badge-success mb-1">'.($this->lang->line('active')?$this->lang->line('active'):'Active').'</span>':'<span class="badge badge-danger mb-1">'.($this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive').'</span>').'<br>
                <strong>'.($this->lang->line('plan')?$this->lang->line('plan'):'Plan').': </strong>'.(($system_user->expired==1)?'<span class="badge badge-success">'.($this->lang->line('active')?$this->lang->line('active'):'Active').'</span>':'<span class="badge badge-danger">'.($this->lang->line('expired')?$this->lang->line('expired'):'Expired').'</span>');

                $tempRow['first_name_1'] = $system_user->first_name;
                $tempRow['last_name'] = $system_user->last_name;
                $tempRow['phone'] = $system_user->phone!=0?$system_user->phone:($this->lang->line('no_number')?$this->lang->line('no_number'):'No Number');

                $tempRow['profile'] = '';
                if($system_user->profile){
                    if(file_exists('assets/uploads/profiles/'.$system_user->profile)){
                        $file_upload_path = 'assets/uploads/profiles/'.$system_user->profile;
                        }else{
                        $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$system_user->profile;
                    }
                    $tempRow['profile'] = base_url($file_upload_path);
                }

                $tempRow['short_name'] = mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8");
                $group = $this->ion_auth->get_users_groups($system_user->user_id)->result();
                $tempRow['role'] = ucfirst($group[0]->name);
                $tempRow['group_id'] = $group[0]->id;
                $tempRow['projects_count'] = get_count('id','project_users','user_id='.$system_user->user_id);
                $tempRow['tasks_count'] = get_count('id','task_users','user_id='.$system_user->user_id);
                $tempRow['users_count'] = get_count('id','users','saas_id='.$system_user->user_id);
                $rows[] = $tempRow;
            }	
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    
    public function get_employee_id() {
        $query = $this->db->query("SELECT MAX(employee_id) AS max_employee_id FROM users");
        $result = $query->row_array();
        return array(
            'max_employee_id' => $result['max_employee_id']
        );
    }

}