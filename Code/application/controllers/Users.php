<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function get_team_list()
	{	
		if($this->ion_auth->logged_in()){
			
			$bulkData = array();

			$system_users = $this->ion_auth->users(array(1,2))->result();
			foreach ($system_users as $system_user) {
				if(isset($system_user->saas_id) && $this->session->userdata('saas_id') == $system_user->saas_id){
				$tempRow[] = $system_user;
				$tempRow['id'] = $system_user->user_id;
				$tempRow['email'] = $system_user->email;
				$tempRow['active'] = $system_user->active;
				$tempRow['first_name'] = $system_user->first_name.' '.$system_user->last_name;
				$tempRow['last_name'] = $system_user->last_name;
				
				$group = $this->ion_auth->get_users_groups($system_user->user_id)->result();
				if($group[0]->name == 'admin'){
					$tempRow['role'] = $this->lang->line('admin')?htmlspecialchars($this->lang->line('admin')):'Admin';
				}else{
					$tempRow['role'] = $this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member';
				}
				$tempRow['group_id'] = $group[0]->id;
				$tempRow['projects_count'] = get_count('id','project_users','user_id='.$system_user->user_id);
				$tempRow['tasks_count'] = get_count('id','task_users','user_id='.$system_user->user_id);

				$tempRow['phone'] = $system_user->phone!=0?$system_user->phone:'';
				
				$tempRow['status'] = $system_user->active==1?('<span class="badge badge-success">'.($this->lang->line('active')?$this->lang->line('active'):'Active').'</span>'):('<span class="badge badge-danger">'.($this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive').'</span>');

				$rows[] = $tempRow;
				}
			}

			$bulkData['rows'] = $rows;
			print_r(json_encode($bulkData));
		}else{
			return '';
		}
	}

	public function get_client_list()
	{	
		if($this->ion_auth->logged_in()){
			
			$bulkData = array();

			$system_users = $this->ion_auth->users(array(4))->result();
			foreach ($system_users as $system_user) {
				if(isset($system_user->saas_id) && $this->session->userdata('saas_id') == $system_user->saas_id){
				$tempRow[] = $system_user;
				$tempRow['id'] = $system_user->user_id;
				$tempRow['email'] = $system_user->email;
				$tempRow['active'] = $system_user->active;
				$tempRow['first_name'] = $system_user->first_name.' '.$system_user->last_name;
				$tempRow['last_name'] = $system_user->last_name;
				$tempRow['phone'] = $system_user->phone!=0?$system_user->phone:'';
				$tempRow['company'] = company_details('company_name', $system_user->user_id);
				$tempRow['projects_count'] = get_count('id','projects','client_id='.$system_user->user_id);
				$tempRow['status'] = $system_user->active==1?('<span class="badge badge-success">'.($this->lang->line('active')?$this->lang->line('active'):'Active').'</span>'):('<span class="badge badge-danger">'.($this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive').'</span>');

				$rows[] = $tempRow;
				}
			}

			$bulkData['rows'] = $rows;
			print_r(json_encode($bulkData));
		}else{
			return '';
		}
	}

	public function client()
	{	
		if ($this->ion_auth->logged_in() && is_module_allowed('clients') && ($this->ion_auth->is_admin() || permissions('client_view')))
		{
			$this->data['page_title'] = 'Clients - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$system_users = $this->ion_auth->users(array(4))->result();
			foreach ($system_users as $system_user) {
				if(isset($system_user->saas_id) && $this->session->userdata('saas_id') == $system_user->saas_id){
				$tempRow['id'] = $system_user->user_id;
				$tempRow['email'] = $system_user->email;
				$tempRow['active'] = $system_user->active;
				$tempRow['first_name'] = $system_user->first_name;
				$tempRow['last_name'] = $system_user->last_name;
				$tempRow['phone'] = $system_user->phone!=0?$system_user->phone:'';
				$tempRow['company'] = company_details('company_name', $system_user->user_id);

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
				$tempRow['projects_count'] = get_count('id','projects','client_id='.$system_user->user_id);
				$rows[] = $tempRow;
				}
			}
			$this->data['system_users'] = isset($rows)?$rows:'';
			$this->data['user_groups'] = $this->ion_auth->groups(array(1,2,4))->result();
			$this->load->view('clients',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function index()
	{	
		
		if ($this->ion_auth->logged_in() && is_module_allowed('team_members') && ($this->ion_auth->is_admin() || permissions('user_view') || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Users - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();

			$query3 = $this->db->get('departments');
        	$this->data['departments'] = $query3->result_array();
			$query = $this->db->get('devices');
        	$this->data['devices'] = $query->result_array();
			if($this->ion_auth->in_group(3)){
				$system_users = $this->ion_auth->users(array(3))->result();
			}else{
				$system_users = $this->ion_auth->users(array(1,2))->result();
			}
			foreach ($system_users as $system_user) {
				if($this->session->userdata('saas_id') == $system_user->saas_id){
					$tempRow['employee_id'] = $system_user->employee_id;
					$tempRow['id'] = $system_user->user_id;
					$tempRow['email'] = $system_user->email;
					$tempRow['active'] = $system_user->active;
					$tempRow['first_name'] = $system_user->first_name;
					$tempRow['last_name'] = $system_user->last_name;
					$tempRow['father_name'] = $system_user->father_name;
					$tempRow['cnic'] = $system_user->cnic;
					$tempRow['gender'] = $system_user->gender;
					$tempRow['join_date'] = $system_user->join_date;
					$tempRow['company'] = company_details('company_name', $system_user->user_id);
					$tempRow['phone'] = $system_user->phone!=0?$system_user->phone:'';
					$user_id = $tempRow['id'];


					$shift_query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
					$shift_result = $shift_query->row_array();
					$shift_id = $shift_result['shift_id'];

					if ($shift_id === '0') {
						$tempRow['shift_type'] = '<span class="text-muted">No Shift Assigned</span>';
					} else {
						$shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
						$shift_result = $shift_query->row_array();
						$tempRow['shift_type'] = $shift_result['name'];
					}
					$tempRow['profile'] = '';
					if($system_user->profile){
						if(file_exists('assets/uploads/profiles/'.$system_user->profile)){
							$file_upload_path = 'assets/uploads/profiles/'.$system_user->profile;
						}else{
							$file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$system_user->profile;
						}
						$tempRow['profile'] = base_url($file_upload_path);
					}

					$shift_query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
					$shift_result = $shift_query->row_array();
					$department_id = $shift_result['department'];

					if ($department_id === '0' || $department_id ==='') {
						$tempRow['department'] = '';
					} else {
						$departmentQuery = $this->db->query("SELECT * FROM departments WHERE id = $department_id");
						$department_result = $departmentQuery->row_array();
						$tempRow['department'] = $department_result['department_name'];
					}

					$tempRow['short_name'] = mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8");
					$group = $this->ion_auth->get_users_groups($system_user->user_id)->result();
					if($group[0]->name == 'admin'){
						$tempRow['role'] = $this->lang->line('admin')?htmlspecialchars($this->lang->line('admin')):'Admin';
					}else{
						$tempRow['role'] = $this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member';
					}
					$tempRow['group_id'] = $group[0]->id;
					$tempRow['projects_count'] = get_count('id','project_users','user_id='.$system_user->user_id);
					$tempRow['tasks_count'] = get_count('id','task_users','user_id='.$system_user->user_id);
					$rows[] = $tempRow;
				}
			}
			$this->data['system_users'] = isset($rows)?$rows:'';
			$this->data['user_groups'] = $this->ion_auth->get_all_groups();
			if($this->ion_auth->in_group(3)){
				$this->load->view('saas-admins',$this->data);
			}else{
				$this->load->view('users',$this->data);
			}
			
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_saas_users()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			return $this->users_model->get_saas_users();
		}else{
			return '';
		}
	}

	public function saas()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			set_expire_all_expired_plans();
			$this->notifications_model->edit('', 'new_user', '', '', '');
			$this->data['page_title'] = 'Users - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['plans'] = $this->plans_model->get_plans();
			$this->load->view('saas-users',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function company()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(4))
		{
			$this->data['page_title'] = 'Company Settings - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['company_details'] = company_details();
			$this->load->view('company',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function profile()
	{	
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Profile - '.company_name();
			$this->data['current_user'] = $profile_user = $this->ion_auth->user()->row();
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();
			$query3 = $this->db->get('departments');
        	$this->data['departments'] = $query3->result_array();
			
			$user_id = $profile_user->user_id;
			// Check if the user ID is not empty and is numeric
			if (!empty($user_id) && is_numeric($user_id)) {
				// Fetch all records from the users table based on the user ID
				$query = $this->db->query("SELECT * FROM users WHERE id = {$user_id}");
				$user_data = $query->row();
	
				if ($user_data) {
					// Update the $tempRow array with the fetched user data
					$tempRow['id'] = $user_data->id;
					$tempRow['email'] = $user_data->email;
					$tempRow['active'] = $user_data->active;
					$tempRow['first_name'] = $user_data->first_name;
					$tempRow['last_name'] = $user_data->last_name;
					$tempRow['phone'] = $user_data->phone != 0 ? $user_data->phone : '';
					$tempRow['profile'] = !empty($user_data->profile)?$user_data->profile:'';
					$tempRow['short_name'] = mb_substr($user_data->first_name, 0, 1, "utf-8").''.mb_substr($user_data->last_name, 0, 1, "utf-8");
					$tempRow['projects_count'] = get_count('id','project_users','user_id='.$user_data->id);
					$tempRow['tasks_count'] = get_count('id','task_users','user_id='.$user_data->id);
					$group = $this->ion_auth->get_users_groups($user_data->id)->result();
					$tempRow['role'] = ucfirst($group[0]->name);
					$tempRow['group_id'] = $group[0]->id;
					$user_id = $tempRow['id'];
					$shift_query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
					$shift_result = $shift_query->row_array();
					$tempRow['shift_id'] = $shift_result['shift_id'];
					$tempRow['cnic'] = $user_data->cnic;
					$tempRow['father_name'] = $user_data->father_name;
					$tempRow['gender'] = $user_data->gender;
					$deviceArray = json_decode($user_data->device_id);
					$deviceNumber = isset($deviceArray[0]) ? intval($deviceArray[0]) : 1;
					$tempRow['device'] = $deviceNumber;
					$tempRow['status'] = $user_data->active;
					$tempRow['employee_id'] = $user_data->employee_id;
					$tempRow['department'] = $user_data->department;
					
					$department_id = $tempRow['department'];

					$query = $this->db->query("SELECT * FROM departments WHERE id = $department_id");
					$department = $query->row_array();
					$tempRow['department_name'] = $department['department_name'];
					// Function to check if a date is in the desired format
					function isValidDateFormat($date)
					{
						$dateTimeObj = DateTime::createFromFormat('d M Y', $date);
						return $dateTimeObj !== false && !array_sum($dateTimeObj->getLastErrors());
					}

					// Assuming $user_data->DOB and $user_data->join_date are in the format 'Y-m-d' (e.g., '2022-01-03')
					if (isValidDateFormat($user_data->DOB)) {
						$dateOfBirth = $user_data->DOB;
					} else {
						$dateOfBirth = date("d M Y", strtotime($user_data->DOB));
					}

					if (isValidDateFormat($user_data->join_date)) {
						$joinDate = $user_data->join_date;
					} else {
						$joinDate = date("d M Y", strtotime($user_data->join_date));
					}

					$tempRow['date_of_birth'] = $dateOfBirth;
					$tempRow['join_date'] = $joinDate;
					$tempRow['desgnation'] = $user_data->desgnation;
					$tempRow['emg_person'] = $user_data->emg_person;
					$tempRow['emg_number'] = $user_data->emg_number;
					$tempRow['address'] = $user_data->address;
	
					// Assign the updated $tempRow to $this->data['profile_user']
					$this->data['profile_user'] = $tempRow;
				}
			}
        		
			$this->data['profile_user'] = $tempRow;
			$this->data['user_groups'] = $this->ion_auth->groups(array(1,2))->result();
			$this->load->view('profile',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function detail()
	{	
		if ($this->ion_auth->logged_in())
		{
			
			$this->data['page_title'] = 'Profile - '.company_name();
			$this->data['current_user'] = $profile_user = $this->ion_auth->user()->row();
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();
			$query3 = $this->db->get('departments');
        	$this->data['departments'] = $query3->result_array();
			$query = $this->db->get('devices');
        	$this->data['devices'] = $query->result_array();
			
			// Get the user ID from the current URL
			$user_id = $this->uri->segment($this->uri->total_segments());

			// Check if the user ID is not empty and is numeric
			if (!empty($user_id) && is_numeric($user_id)) {
				// Fetch all records from the users table based on the user ID
				$query = $this->db->query("SELECT * FROM users WHERE id = {$user_id}");
				$user_data = $query->row();
	
				if ($user_data) {
					// Update the $tempRow array with the fetched user data
					$tempRow['id'] = $user_data->id;
					$tempRow['email'] = $user_data->email;
					$tempRow['active'] = $user_data->active;
					$tempRow['first_name'] = $user_data->first_name;
					$tempRow['last_name'] = $user_data->last_name;
					$tempRow['phone'] = $user_data->phone != 0 ? $user_data->phone : '';
					$tempRow['profile'] = !empty($user_data->profile)?$user_data->profile:'';
					$tempRow['short_name'] = mb_substr($user_data->first_name, 0, 1, "utf-8").''.mb_substr($user_data->last_name, 0, 1, "utf-8");
					$tempRow['projects_count'] = get_count('id','project_users','user_id='.$user_data->id);
					$tempRow['tasks_count'] = get_count('id','task_users','user_id='.$user_data->id);
					$group = $this->ion_auth->get_users_groups($user_data->id)->result();
					$tempRow['role'] = ucfirst($group[0]->name);
					$tempRow['group_id'] = $group[0]->id;
					$user_id = $tempRow['id'];
					$shift_query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
					$shift_result = $shift_query->row_array();
					$tempRow['shift_id'] = $shift_result['shift_id'];
					$tempRow['cnic'] = $user_data->cnic;
					$tempRow['father_name'] = $user_data->father_name;
					$tempRow['gender'] = $user_data->gender;
					$tempRow['status'] = $user_data->active;
					$tempRow['employee_id'] = $user_data->employee_id;
					$deviceArray = json_decode($user_data->device_id);
					$deviceNumber = isset($deviceArray[0]) ? intval($deviceArray[0]) : 1;
					$tempRow['device_id'] = $deviceNumber;
					$tempRow['department'] = $user_data->department;
					// Function to check if a date is in the desired format
					function isValidDateFormat($date)
					{
						$dateTimeObj = DateTime::createFromFormat('d M Y', $date);
						return $dateTimeObj !== false && !array_sum($dateTimeObj->getLastErrors());
					}

					// Assuming $user_data->DOB and $user_data->join_date are in the format 'Y-m-d' (e.g., '2022-01-03')
					if (isValidDateFormat($user_data->DOB)) {
						$dateOfBirth = $user_data->DOB;
					} else {
						$dateOfBirth = date("d M Y", strtotime($user_data->DOB));
					}

					if (isValidDateFormat($user_data->join_date)) {
						$joinDate = $user_data->join_date;
					} else {
						$joinDate = date("d M Y", strtotime($user_data->join_date));
					}

					$tempRow['date_of_birth'] = $dateOfBirth;
					$tempRow['join_date'] = $joinDate;
					$tempRow['desgnation'] = $user_data->desgnation;
					$tempRow['emg_person'] = $user_data->emg_person;
					$tempRow['emg_number'] = $user_data->emg_number;
					$tempRow['address'] = $user_data->address;
	
					// Assign the updated $tempRow to $this->data['profile_user']
					$this->data['profile_user'] = $tempRow;
				}
			}
			
			$this->data['profile_user'] = $tempRow;
			$this->data['user_groups'] = $this->ion_auth->get_all_groups();
			$this->load->view('detail',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function ajax_get_user_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$system_user = $this->ion_auth->user($id)->row();
			$where = " WHERE id = $id ";
			$query = $this->db->query("SELECT * FROM users " . $where);
			$results = $query->result_array();
			if(!empty($system_user)){
				$tempRow['id'] = $system_user->id;
				$tempRow['profile'] = $system_user->profile;
				$tempRow['first_name'] = $system_user->first_name;
				$tempRow['last_name'] = $system_user->last_name;
				$tempRow['father_name'] = $system_user->father_name;
				$tempRow['company'] = company_details('company_name', $system_user->id);
				$tempRow['short_name'] = mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8");
				$tempRow['phone'] = $system_user->phone;
				$tempRow['active'] = $system_user->active;
				$tempRow['cnic'] = $results[0]['cnic'];
				$tempRow['gender'] = $results[0]['gender'];
				$tempRow['desgnation'] = $results[0]['desgnation'];
				$tempRow['department'] = $results[0]['department'];
				$tempRow['emg_person'] = $results[0]['emg_person'];
				$tempRow['emg_number'] = $results[0]['emg_number'];
				$tempRow['device_id'] = $results[0]['device_id'];
				$tempRow['DOB'] = $results[0]['DOB'];
				$tempRow['email'] = $results[0]['email'];
				$tempRow['join_date'] = $results[0]['join_date'];
				$tempRow['address'] = $results[0]['address'];
				$tempRow['type'] = $results[0]['shift_id'];
				$tempRow['employee_id'] = $results[0]['employee_id'];
				$current_plan = get_current_plan($system_user->saas_id);
				if($current_plan){
					$tempRow['current_plan_expiry'] = format_date($current_plan['end_date'],system_date_format());
					$tempRow['current_plan_id'] = $current_plan['plan_id'];
				}
				$group = $this->ion_auth->get_users_groups($system_user->id)->result();
				$tempRow['role'] = ucfirst($group[0]->name);
				$tempRow['group_id'] = $group[0]->id;
				$this->data['error'] = false;
				$this->data['data'] = $tempRow;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'No user found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data);
		}
	}
	
public function get_active_inactive()
	{
		$activeInactive = $this->input->get('active_users');
		$department = $this->input->get('department_users');
		if ($activeInactive == '1') {
			$this->db->where('active', 1);
		}
		if ($activeInactive == '2') {
			$this->db->where('active', 0);
		}
		if (!empty($department)) {
			$this->db->where('department', $department);
		}
		$query = $this->db->get('users'); // Assuming your table is named 'users'
		$system_users = $query->result();
		$rows=[];
		$serial_no = 1;
		foreach ($system_users as $system_user) {
			if($this->session->userdata('saas_id') == $system_user->saas_id){
				$tempRow['employee_id'] = '<a  href="'.base_url('users/detail/'.$system_user->id).'">'.$system_user->employee_id.'</a>';
				$tempRow['id'] = $system_user->id;
				$tempRow['email'] = $system_user->email;
				if ($system_user->active == 1) {
					$tempRow['status'] = '<span class="text-muted">Active</span>';
				}else{
					$tempRow['status'] = '<span class="text-muted">Deactive</span>';
				}
				$user_id = $tempRow['id'];
				$tempRow['first_name'] = $system_user->first_name;
				$tempRow['last_name'] = $system_user->last_name;
				$tempRow['name'] = '<a href="'.base_url('users/detail/'.$system_user->id).'">'.$system_user->first_name.' '.$system_user->last_name.'</a>';
				$tempRow['father_name'] = $system_user->father_name;
				$tempRow['cnic'] = $system_user->cnic;
				$tempRow['gender'] = $system_user->gender;
				$tempRow['joining_date'] = $system_user->join_date;
				$tempRow['s_no'] = $serial_no;
				$tempRow['action'] = '<a href="#" data-edit="'.$system_user->id.'" class="modal-edit-user" title="Edit" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a>';
				$tempRow['company'] = company_details('company_name', $system_user->user_id);
				$tempRow['mobile'] = $system_user->phone!=0?$system_user->phone:'';

				$shift_query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
				$shift_result = $shift_query->row_array();
				$shift_id = $shift_result['shift_id'];

				if ($shift_id === '0') {
					$tempRow['shift_type'] = '<span class="text-muted">No Shift Assigned</span>';
				} else {
					$shift_query = $this->db->query("SELECT * FROM shift WHERE id = $shift_id");
					$shift_result = $shift_query->row_array();
					$tempRow['shift_type'] = $shift_result['name'];
				}
				$tempRow['profile'] = '';
				if($system_user->profile){
					if(file_exists('assets/uploads/profiles/'.$system_user->profile)){
						$file_upload_path = 'assets/uploads/profiles/'.$system_user->profile;
					}else{
						$file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$system_user->profile;
					}
					$tempRow['profile'] = base_url($file_upload_path);
				}

				$shift_query = $this->db->query("SELECT * FROM users WHERE id = $user_id");
				$shift_result = $shift_query->row_array();
				$department_id = $shift_result['department'];

				if ($department_id === '0' || $department_id ==='') {
					$tempRow['department'] = '';
				} else {
					$departmentQuery = $this->db->query("SELECT * FROM departments WHERE id = $department_id");
					$department_result = $departmentQuery->row_array();
					$tempRow['department'] = $department_result['department_name'];
				}

				$tempRow['short_name'] = mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8");
				$group = $this->ion_auth->get_users_groups($user_id)->result();
				$tempRow['role'] = $group[0]->description;
				$tempRow['group_id'] = $group[0]->id;
				$tempRow['projects_count'] = '<span class="badge badge-secondary">'.get_count('id','project_users','user_id='.$user_id).'</span>';
				$tempRow['tasks_count'] = '<span class="badge badge-secondary">'.get_count('id','task_users','user_id='.$user_id).'</span>';
				$rows[] = $tempRow;
				$serial_no++;
			}
		}
		echo json_encode($rows);
	}
	
	public function get_employee_id()
	{
		if ($this->ion_auth->logged_in())
		{
			$report =  $this->users_model->get_employee_id();
			echo json_encode($report);	
		}else{
			return '';
		}
	}

}







