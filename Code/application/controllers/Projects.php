<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function stop_timesheet_timer()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{

			$data['ending_time'] = date("Y-m-d H:i:s");
			$data['note'] = $this->input->post('note')?$this->input->post('note'):'';

			$id = $this->input->post('id')?$this->input->post('id'):'';
		
			$task_id = $this->input->post('task_id')?$this->input->post('task_id'):'';

			if($id == ''){
				$user_id = $this->input->post('user_id')?$this->input->post('user_id'):$this->session->userdata('user_id');
			}else{
				$user_id = '';
			}

			if($this->projects_model->edit_timesheet($data, $id, $task_id, $user_id)){
				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('updated_successfully')?$this->lang->line('updated_successfully'):"Updated successfully.";
				echo json_encode($this->data); 
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}
	
	public function get_timeline()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			return $this->projects_model->get_timeline();
		}else{
			return '';
		}
	}

	public function delete_timesheet($timesheet_id='')
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{

			if(empty($timesheet_id)){
				$timesheet_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if(!empty($timesheet_id) && $this->projects_model->delete_timesheet($timesheet_id)){
				
				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function get_timesheet_by_id()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->projects_model->get_timesheet_by_id($this->input->post('id'));
				$this->data['error'] = false;
				$this->data['data'] = $data?$data:'';
				$this->data['message'] = "Success";
				echo json_encode($this->data); 
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function get_timesheet()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			return $this->projects_model->get_timesheet();
		}else{
			return '';
		}
	}

	public function edit_timesheet()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('update_id', 'ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('project_id', 'Project', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('task_id', 'Task', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){

				if($this->input->post('project_id')){
					$data['project_id'] = $this->input->post('project_id');
				}
				if($this->input->post('task_id')){
					$data['task_id'] = $this->input->post('task_id');
				}
				if($this->input->post('user_id')){
					$data['user_id'] = $this->input->post('user_id');
				}
				if($this->input->post('starting_time')){
					$data['starting_time'] = format_date($this->input->post('starting_time'),"Y-m-d H:i:s");
				}
				if($this->input->post('ending_time')){
					$data['ending_time'] = format_date($this->input->post('ending_time'),"Y-m-d H:i:s");
				}
				
				$data['note'] = $this->input->post('note')?$this->input->post('note'):'';

				if($this->projects_model->edit_timesheet($data, $this->input->post('update_id'))){

					$this->session->set_flashdata('message', $this->lang->line('updated_successfully')?$this->lang->line('updated_successfully'):"Updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('updated_successfully')?$this->lang->line('updated_successfully'):"Updated successfully.";
					echo json_encode($this->data); 

				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function create_timesheet()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('project_id', 'Project', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('task_id', 'Task', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				if(check_my_timer($this->input->post('task_id'))){
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('timer_already_running_on_this_task')?$this->lang->line('timer_already_running_on_this_task'):"Timer already running on this task";
					echo json_encode($this->data);
					return false;
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['project_id'] = $this->input->post('project_id');
				$data['task_id'] = $this->input->post('task_id');
				$data['user_id'] = $this->input->post('user_id')?$this->input->post('user_id'):$this->session->userdata('user_id');
				$data['starting_time'] = $this->input->post('starting_time')?format_date($this->input->post('starting_time'),"Y-m-d H:i:s"):date("Y-m-d H:i:s");
				$data['ending_time'] = $this->input->post('ending_time')?format_date($this->input->post('ending_time'),"Y-m-d H:i:s"):NULL;
				$data['note'] = $this->input->post('note')?$this->input->post('note'):'';

				$id = $this->projects_model->create_timesheet($data);
				
				if($id){

					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['data'] = $id;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 

				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function timesheet()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('timesheet') && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->data['page_title'] = 'Timesheet - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			
			if($this->ion_auth->is_admin()){
				if(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user'])){
					$this->data['projects'] = $this->projects_model->get_projects($_GET['user']);
				}else{
					$this->data['projects'] = $this->projects_model->get_projects();
				}
			}else{
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}
			$this->load->view('timesheet',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function calendar()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('calendar') && ($this->ion_auth->is_admin() || permissions('calendar_view')))
		{
			$this->data['page_title'] = 'Calendar - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->data['project_id'] = $project_id = $this->uri->segment(3);

			$this->data['project_status'] = project_status();
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();

			if($project_id && $this->ion_auth->in_group(3) && !is_my_project($project_id)){
				redirect('projects/calendar', 'refresh');
			}

			if(isset($_GET['status']) && !empty($_GET['status']) && is_numeric($_GET['status'])){
				$filter = $_GET['status'];
				$filter_type = 'status';
			}elseif(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user'])){
				$filter = $_GET['user'];
				$filter_type = 'user';
			}elseif(isset($_GET['client']) && !empty($_GET['client']) && is_numeric($_GET['client'])){
				$filter = $_GET['client'];
				$filter_type = 'client';
			}else{
				$filter = (isset($_GET['sortby']) && !empty($_GET['sortby']) && ($_GET['sortby'] == 'latest' || $_GET['sortby'] == 'old' || $_GET['sortby'] == 'name'))?$_GET['sortby']:'latest';
				$filter_type = 'sortby';
			}

			if($this->ion_auth->is_admin()){
					$tasks = $this->projects_model->get_tasks();

					$projects = $this->projects_model->get_projects('',$project_id, '', '', $filter_type, $filter);

					$this->data['projects_filter'] = $this->projects_model->get_projects();
			}else{
				$tasks = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id);

				$projects = $this->projects_model->get_projects($this->session->userdata('user_id'),$project_id, '', '', $filter_type, $filter);

				$this->data['projects_filter'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

			if(isset($projects) && !empty($projects)){
				foreach($projects as $project){
				$gantt_project['id'] = 'p_'.$project['id'];
				$gantt_project['is_project'] = 'yes';
				$gantt_project['className'] = 'bg-primary border border-primary font-weight-bold';
				$gantt_project['title'] = ($this->lang->line('project')?$this->lang->line('project'):'Project').': '.$project['title'];
				$gantt_project['start'] = format_date($project['starting_date'],"Y-m-d");
				$gantt_project['end'] = date('Y-m-d', strtotime($project['ending_date'] . ' + 1 day'));

				if(isset($tasks) && !empty($tasks)){
					foreach($tasks as $key => $task){
					if($project['id'] == $task['project_id']){
						$gantt_task['id'] = $task['id'];
						$gantt_task['is_project'] = 'no';
						$gantt_task['className'] = 'bg-'.$task['task_class'].' border border-'.$task['task_class'];
						$gantt_task['title'] = ($this->lang->line('task')?$this->lang->line('task'):'Task').': '.$task['title'];
						
						$gantt_task['start'] = $task['starting_date']?format_date($task['starting_date'],"Y-m-d"):format_date($project['starting_date'],"Y-m-d");
						$gantt_task['end'] = date('Y-m-d', strtotime($task['due_date'] . ' + 1 day'));
						$gantt_task_data[] = $gantt_task;
					}
					}
				}else{
					$gantt_task_data = array();
				}
				$gantt_project_data[] = $gantt_project;
				}
			}else{
				$gantt_project_data = array();
				$gantt_task_data = array();
			}

			if(isset($gantt_project_data) && isset($gantt_task_data)){
				$gantt_final_data = array_merge($gantt_project_data,$gantt_task_data);
			}else{
				$gantt_final_data = $gantt_project_data;
			}

			$this->data['final_data'] = (isset($gantt_final_data) && !empty($gantt_final_data))?$gantt_final_data:'';

			$this->load->view('calendar',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	public function edit_gantt()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('gantt_edit')))
		{
			$this->form_validation->set_rules('update_id', 'ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_date', 'Ending Date', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$id = preg_replace('/^p_/', '', $this->input->post('update_id'));
				$ending_date = format_date($this->input->post('ending_date'),"Y-m-d");
				$starting_date = format_date($this->input->post('starting_date'),"Y-m-d");

				if($this->input->post('is_project') == 'yes'){
					$data = array(
						'starting_date' => $starting_date,
						'ending_date' => $ending_date,	
					);
					$updated = $this->projects_model->edit_project($id,$data);
				}else{
					$data = array(
						'starting_date' => $starting_date,
						'due_date' => $ending_date
					);
					$updated = $this->projects_model->edit_task($id, $data);
				}

				if($updated){
					$this->data['error'] = false;
					$this->data['message'] = "Successfull.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function gantt()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('gantt') && ($this->ion_auth->is_admin() || permissions('gantt_view')))
		{
			$this->data['page_title'] = 'Gantt - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->data['project_id'] = $project_id = $this->uri->segment(3);
			$this->data['project_status'] = project_status();
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();

			if($project_id && $this->ion_auth->in_group(3) && !is_my_project($project_id)){
				redirect('projects/calendar', 'refresh');
			}

			if(isset($_GET['status']) && !empty($_GET['status']) && is_numeric($_GET['status'])){
				$filter = $_GET['status'];
				$filter_type = 'status';
			}elseif(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user'])){
				$filter = $_GET['user'];
				$filter_type = 'user';
			}elseif(isset($_GET['client']) && !empty($_GET['client']) && is_numeric($_GET['client'])){
				$filter = $_GET['client'];
				$filter_type = 'client';
			}else{
				$filter = (isset($_GET['sortby']) && !empty($_GET['sortby']) && ($_GET['sortby'] == 'latest' || $_GET['sortby'] == 'old' || $_GET['sortby'] == 'name'))?$_GET['sortby']:'latest';
				$filter_type = 'sortby';
			}

			if($this->ion_auth->is_admin()){
					$tasks = $this->projects_model->get_tasks();

					$projects = $this->projects_model->get_projects('',$project_id, '', '', $filter_type, $filter);

					$this->data['projects_filter'] = $this->projects_model->get_projects();
			}else{
				$tasks = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id);

				$projects = $this->projects_model->get_projects($this->session->userdata('user_id'),$project_id, '', '', $filter_type, $filter);

				$this->data['projects_filter'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

			if(isset($projects) && !empty($projects)){
				foreach($projects as $project){
				$gantt_project['id'] = 'p_'.$project['id'];
				$gantt_project['is_project'] = 'yes';
				$gantt_project['name'] = ($this->lang->line('project')?$this->lang->line('project'):'Project').': '.$project['title'];
				$gantt_project['status'] = $project['project_status'];
				$gantt_project['due_days'] = $project['days_count'].' '.($this->lang->line('days')?$this->lang->line('days'):'Days').' '.$project['days_status'];
				$gantt_project['start'] = format_date($project['starting_date'],"Y-m-d");
				$gantt_project['end'] = format_date($project['ending_date'],"Y-m-d");
				$gantt_project['progress'] = 0;
				$gantt_project['dependencies'] = '';

				if(isset($tasks) && !empty($tasks)){
					foreach($tasks as $key => $task){
					if($project['id'] == $task['project_id']){
						$gantt_task['id'] = $task['id'];
						$gantt_task['is_project'] = 'no';
						$gantt_task['name'] = ($this->lang->line('task')?$this->lang->line('task'):'Task').': '.$task['title'];
						$gantt_task['status'] = $task['task_status'];
						$gantt_task['due_days'] = $task['days_count'].' '.($this->lang->line('days')?$this->lang->line('days'):'Days').' '.$task['days_status'];
						$gantt_task['start'] = $task['starting_date']?format_date($task['starting_date'],"Y-m-d"):format_date($project['starting_date'],"Y-m-d");
						$gantt_task['end'] = format_date($task['due_date'],"Y-m-d");
						$gantt_task['progress'] = 100;
						$gantt_task['dependencies'] = 'p_'.$task['project_id'];
						$gantt_task_data[] = $gantt_task;
					}
					}
				}else{
					$gantt_task_data = array();
				}
				$gantt_project_data[] = $gantt_project;
				}
			}else{
				$gantt_project_data = array();
				$gantt_task_data = array();
			}

			if(isset($gantt_project_data) && isset($gantt_task_data)){
				$gantt_final_data = array_merge($gantt_project_data,$gantt_task_data);
			}else{
				$gantt_final_data = $gantt_project_data;
			}

			$this->data['gantt_final_data'] = (isset($gantt_final_data) && !empty($gantt_final_data))?$gantt_final_data:'';

			$this->load->view('gantt',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function delete_project($project_id='')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('project_delete')))
		{

			if(empty($project_id)){
				$project_id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(!empty($project_id)){
				$tasks = $this->projects_model->get_tasks('','',$project_id);
			}else{
				$tasks = false;
			}
			
			if(!empty($project_id) && $this->projects_model->delete_project($project_id)){

				$this->projects_model->delete_project_files('',$project_id);
				$this->projects_model->delete_project_users($project_id);

				$this->projects_model->delete_timesheet('', $project_id);

				$this->notifications_model->delete('', 'new_project', $project_id);
				$this->notifications_model->delete('', 'project_status', $project_id);
				$this->notifications_model->delete('', 'project_file', $project_id);
				$this->notifications_model->delete('', 'project_comment', $project_id);

				$this->projects_model->delete_task_comment('','','project_comment',$project_id);
				
				if($tasks){
					foreach($tasks as $task){
						
							$this->projects_model->delete_task_files('',$task['id']);
							$this->projects_model->delete_task_comment('','','task_comment',$task['id']);
							$this->projects_model->delete_task_users($task['id']);
							$this->projects_model->delete_task($task['id']);
							
							$this->notifications_model->delete('', 'new_task', $task['id']);
							$this->notifications_model->delete('', 'task_status', $task['id']);
							$this->notifications_model->delete('', 'task_file', $task['id']);
							$this->notifications_model->delete('', 'task_comment', $task['id']);
					}
				}

				$this->session->set_flashdata('message', $this->lang->line('project_deleted_successfully')?$this->lang->line('project_deleted_successfully'):"Project deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('project_deleted_successfully')?$this->lang->line('project_deleted_successfully'):"Project deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
	
	public function delete_task_comment($id='')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			if(!empty($id) && $this->projects_model->delete_task_comment($id)){
				$this->data['error'] = false;
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}
		}
	}

	public function delete_task($task_id='')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('task_delete')))
		{

			if(empty($task_id)){
				$task_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if(!empty($task_id) && $this->projects_model->delete_task($task_id)){
				$this->projects_model->delete_task_files('',$task_id);
				$this->projects_model->delete_task_comment('','','task_comment',$task_id);
				$this->projects_model->delete_task_users($task_id);
				
				$this->projects_model->delete_timesheet('', '', $task_id);

				$this->notifications_model->delete('', 'new_task', $task_id);
				$this->notifications_model->delete('', 'task_status', $task_id);
				$this->notifications_model->delete('', 'task_file', $task_id);
				$this->notifications_model->delete('', 'task_comment', $task_id);

				$this->session->set_flashdata('message', $this->lang->line('task_deleted_successfully')?$this->lang->line('task_deleted_successfully'):"Task deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('task_deleted_successfully')?$this->lang->line('task_deleted_successfully'):"Task deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function create_project_comment()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('comment_project_id', 'Project ID', 'trim|required|is_numeric|strip_tags|xss_clean');
			$this->form_validation->set_rules('message', 'Message', 'trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == TRUE){

					$data = array(
						'type' => 'project_comment',
						'from_id' => $this->session->userdata('user_id'),
						'to_id' => $this->input->post('comment_project_id'),
						'message' => $this->input->post('message'),		
					);

					if($this->projects_model->create_comment($data)){

						$project_old = $this->projects_model->get_projects('',$this->input->post('comment_project_id'));

						if($project_old[0]['project_users_ids']){
							foreach($project_old[0]['project_users_ids_array'] as $user){
								if($user != $this->session->userdata('user_id')){
									$data = array(
										'notification' => '"<span class="text-info">'.$this->input->post('message').'</span>"',
										'type' => 'project_comment',	
										'type_id' => $this->input->post('comment_project_id'),	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $user,	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}
						}
						if($project_old[0]['client_id']){
							if($project_old[0]['client_id'] != $this->session->userdata('user_id')){
								$data = array(
									'notification' => '"<span class="text-info">'.$this->input->post('message').'</span>"',
									'type' => 'project_comment',	
									'type_id' => $this->input->post('comment_project_id'),	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $project_old[0]['client_id'],	
								);
								$notification_id = $this->notifications_model->create($data);
							}
						}

						$system_admins = $this->ion_auth->users(array(1))->result();
						if($system_admins){
							foreach ($system_admins as $system_user) {
								if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
									$data = array(
										'notification' => '"<span class="text-info">'.$this->input->post('message').'</span>"',
										'type' => 'project_comment',	
										'type_id' => $this->input->post('comment_project_id'),	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $system_user->user_id,	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}
						}

						$this->session->set_flashdata('message', $this->lang->line('comment_created_successfully')?$this->lang->line('comment_created_successfully'):"Comment created successfully.");
						$this->session->set_flashdata('message_type', 'success');

						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('comment_created_successfully')?$this->lang->line('comment_created_successfully'):"Comment created successfully.";
						echo json_encode($this->data); 
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
						echo json_encode($this->data);
					}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function create_comment()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('comment_task_id', 'Task ID', 'trim|required|is_numeric|strip_tags|xss_clean');
			
			if($this->input->post('is_comment') == 'true'){
				$this->form_validation->set_rules('message', 'Message', 'trim|required|strip_tags|xss_clean');
			}

			if($this->input->post('is_attachment') == 'true'){
				if (empty($_FILES['attachment']['name'])){
					$this->form_validation->set_rules('attachment', 'Attachment', 'required');
				}
			}

			if($this->form_validation->run() == TRUE){

				if (!empty($_FILES['attachment']['name'])){

					if(!is_storage_limit_exceeded()){
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('storage_limit_exceeded')?$this->lang->line('storage_limit_exceeded'):'Storage Limit Exceeded';
						echo json_encode($this->data);
						return false;
					}

					$upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/tasks/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
	
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = file_upload_format();
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$this->load->library('upload', $config);
					$full_logo = '';
					if ($this->upload->do_upload('attachment')){
						$data = array(
							'type' => 'task',
							'type_id' => $this->input->post('comment_task_id'),
							'user_id' => $this->session->userdata('user_id'),
							'file_name' => $this->upload->data('file_name'),
							'file_type' => $this->upload->data('file_ext'),		
							'file_size' => $this->upload->data('file_size'),		
						);

						if($this->projects_model->upload_files($data)){

							$task_id = $this->input->post('comment_task_id');
							$task_old = $this->projects_model->get_tasks('',$task_id);
							$project_old = $this->projects_model->get_projects('',$task_old[0]['project_id']);
							$users = $this->projects_model->get_task_users($task_id);

							if($users){
								foreach($users as $key => $user){
									if($user['id'] != $this->session->userdata('user_id')){
										$data = array(
											'notification' => '<span class="text-info">'.$this->upload->data('file_name').'</span>',
											'type' => 'task_file',	
											'type_id' => $task_id,	
											'from_id' => $this->session->userdata('user_id'),
											'to_id' => $user['id'],	
										);
										$notification_id = $this->notifications_model->create($data);
									}
								}
							}
							if($project_old[0]['client_id']){
								if($project_old[0]['client_id'] != $this->session->userdata('user_id')){
									$data = array(
										'notification' => '<span class="text-info">'.$this->upload->data('file_name').'</span>',
										'type' => 'task_file',	
										'type_id' => $task_id,	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $project_old[0]['client_id'],	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}

							$system_admins = $this->ion_auth->users(array(1))->result();
							if($system_admins){
								foreach ($system_admins as $system_user) {
									if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
										$data = array(
											'notification' => '<span class="text-info">'.$this->upload->data('file_name').'</span>',
											'type' => 'task_file',	
											'type_id' => $task_id,	
											'from_id' => $this->session->userdata('user_id'),
											'to_id' => $system_user->user_id,	
										);
										$notification_id = $this->notifications_model->create($data);
									}
								}
							}

							$this->data['error'] = false;
							$this->data['message'] = $this->lang->line('comment_created_successfully')?$this->lang->line('comment_created_successfully'):"Comment created successfully.";
							echo json_encode($this->data); 
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
							echo json_encode($this->data);
						}

					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}

				if($this->input->post('is_comment') == 'true'){
					$data = array(
						'type' => 'task_comment',
						'from_id' => $this->session->userdata('user_id'),
						'to_id' => $this->input->post('comment_task_id'),
						'message' => $this->input->post('message'),		
					);

					if($this->projects_model->create_comment($data)){

						
						$task_id = $this->input->post('comment_task_id');
						$task_old = $this->projects_model->get_tasks('',$task_id);
						$project_old = $this->projects_model->get_projects('',$task_old[0]['project_id']);
						$users = $this->projects_model->get_task_users($task_id);

						if($users){
							foreach($users as $key => $user){
								if($user['id'] != $this->session->userdata('user_id')){
									$data = array(
										'notification' => '"<span class="text-info">'.$this->input->post('message').'</span>"',
										'type' => 'task_comment',	
										'type_id' => $task_id,	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $user['id'],	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}
						}
						if($project_old[0]['client_id']){
							if($project_old[0]['client_id'] != $this->session->userdata('user_id')){
								$data = array(
									'notification' => '"<span class="text-info">'.$this->input->post('message').'</span>"',
									'type' => 'task_comment',	
									'type_id' => $task_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $project_old[0]['client_id'],	
								);
								$notification_id = $this->notifications_model->create($data);
							}
						}

						$system_admins = $this->ion_auth->users(array(1))->result();
						if($system_admins){
							foreach ($system_admins as $system_user) {
								if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
									$data = array(
										'notification' => '"<span class="text-info">'.$this->input->post('message').'</span>"',
										'type' => 'task_comment',	
										'type_id' => $task_id,	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $system_user->user_id,	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}
						}

						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('comment_created_successfully')?$this->lang->line('comment_created_successfully'):"Comment created successfully.";
						echo json_encode($this->data); 
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
						echo json_encode($this->data);
					}
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function task_status_update($task_id = '', $new_status = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(!$task_id && !$new_status){

				$this->form_validation->set_rules('id', 'Task ID', 'trim|required|strip_tags|xss_clean|is_numeric');
				$this->form_validation->set_rules('status', 'New status', 'trim|required|strip_tags|xss_clean|is_numeric');
			
				if($this->form_validation->run() == FALSE){
					$this->data['error'] = true;
					$this->data['message'] = validation_errors();
					echo json_encode($this->data);
					return false;
				}

				$task_id = $this->input->post('id');
				$new_status = $this->input->post('status');
			}

			$task_status_new = task_status($new_status);
			$task_old = $this->projects_model->get_tasks('',$task_id);
			$project_old = $this->projects_model->get_projects('',$task_old[0]['project_id']);
			
			$task_status = $this->input->post('status');
			$task_status_new = task_status($this->input->post('status'));
			$task_old = $this->projects_model->get_tasks('',$task_id);
			$project_old = $this->projects_model->get_projects('',$task_old[0]['project_id']);

			if($this->input->post('status') != $this->input->post('status_duplicate')){
				$this->db->where('id', $task_id);
				$query = $this->db->get('tasks');
				$inserted_task_data = $query->row_array(); 
				$timeline_data = array(
					'saas_id' => $inserted_task_data['saas_id'],
					'project_id' => $inserted_task_data['project_id'],
					'task_id' => $inserted_task_data['id'],
					'status' => $this->input->post('status'),
					'date_time' => date('Y-m-d H:i:s')
				);
				$this->projects_model->create_timeline($timeline_data);
			}
			
			if($this->projects_model->task_status_update($task_id, $new_status)){

				if(!$this->ion_auth->in_group(4)){
					if($this->input->post('status') == 2){
						if(!check_my_timer($this->input->post('id'))){
							$data['saas_id'] = $this->session->userdata('saas_id');
							$data['project_id'] = $task_old[0]['project_id'];
							$data['task_id'] = $task_id;
							$data['user_id'] = $this->session->userdata('user_id');
							$data['starting_time'] = date("Y-m-d H:i:s");
							$data['ending_time'] = NULL;
							$this->projects_model->create_timesheet($data);
						}
					}else{
						if(check_my_timer($this->input->post('id'))){
							$data['ending_time'] = date("Y-m-d H:i:s");
							$this->projects_model->edit_timesheet($data, '', $this->input->post('id'), $this->session->userdata('user_id'));
						}
					}
				}

				if($project_old[0]['client_id']){
					if($project_old[0]['client_id'] != $this->session->userdata('user_id')){
						$data = array(
							'notification' => $task_old[0]['status'],
							'type' => 'task_status',	
							'type_id' => $task_id,	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $project_old[0]['client_id'],	
						);
						$notification_id = $this->notifications_model->create($data);
					}
				}
				$users = $this->projects_model->get_task_users($task_id);
				if($users){
					foreach($users as $key => $user){
						if($user['id'] != $this->session->userdata('user_id')){
							$data = array(
								'notification' => $task_old[0]['status'],
								'type' => 'task_status',	
								'type_id' => $task_id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $user['id'],	
							);
							$notification_id = $this->notifications_model->create($data);
						}
					}

				}
				$system_admins = $this->ion_auth->users(array(1))->result();
				if($system_admins){
					foreach ($system_admins as $system_user) {
						if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
							$data = array(
								'notification' => $task_old[0]['status'],	
								'type' => 'task_status',
								'type_id' => $task_id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $system_user->user_id,	
							);
							$notification_id = $this->notifications_model->create($data);
						}
					}
				}

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('status_updated_successfully')?$this->lang->line('status_updated_successfully'):"Status updated successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_task_users($task_id = '',$user_id = '')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($user_id)){
				$user_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if($this->projects_model->delete_task_users($task_id, $user_id)){
				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('user_deleted_successfully')?$this->lang->line('user_deleted_successfully'):"User deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_project_task_users($user_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($user_id)){
				$user_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			if($this->projects_model->delete_project_users('', $user_id) && delete_task_users('', $user_id)){
				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('user_deleted_successfully')?$this->lang->line('user_deleted_successfully'):"User deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_project_users($project_id = '',$user_id = '')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($user_id)){
				$user_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if($this->projects_model->delete_project_users($project_id, $user_id)){
				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('user_deleted_successfully')?$this->lang->line('user_deleted_successfully'):"User deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_task_files($file_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($file_id)){
				$file_id = $this->uri->segment(3);
				
			}
			if($this->projects_model->delete_task_files($file_id)){
				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('file_deleted_successfully')?$this->lang->line('file_deleted_successfully'):"File deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_project_files($file_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($file_id)){
				$file_id = $this->uri->segment(3);
				
			}
			if($this->projects_model->delete_project_files($file_id)){
				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('file_deleted_successfully')?$this->lang->line('file_deleted_successfully'):"File deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function upload_files($project_id)
	{
		$project_id = !empty($project_id)?$project_id:$this->uri->segment(3);
		if ($this->ion_auth->logged_in() && $project_id)
		{
			$upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/projects/';
			if(!is_dir($upload_path)){
				mkdir($upload_path,0775,true);
			}

			$config['upload_path']          = $upload_path;
			$config['allowed_types']        = file_upload_format();
			$config['overwrite']             = false;
			$config['max_size']             = 0;
			$config['max_width']            = 0;
			$config['max_height']           = 0;
			$this->load->library('upload', $config);
			if (!empty($_FILES['file']['name'])){
				
				if(!is_storage_limit_exceeded()){
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('storage_limit_exceeded')?$this->lang->line('storage_limit_exceeded'):'Storage Limit Exceeded';
					echo json_encode($this->data);
					return false;
				}

				if ($this->upload->do_upload('file')){
					$file_data = $this->upload->data();
					$data = array(
						'type' => 'project',
						'type_id' => $project_id,
						'user_id' => $this->session->userdata('user_id'),
						'file_name' => $file_data['file_name'],
						'file_type' => $file_data['file_ext'],		
						'file_size' => $file_data['file_size'],		
					);
					if($this->projects_model->upload_files($data)){
						$users = $this->projects_model->get_project_users($project_id);
						$project_old = $this->projects_model->get_projects('',$project_id);
						if($users){
							foreach($users as $key => $user){
								if($user['id'] != $this->session->userdata('user_id')){
									$data = array(
										'notification' => '<span class="text-info">'.$file_data['file_name'].'</span>',
										'type' => 'project_file',	
										'type_id' => $project_id,	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $user['id'],	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}
						}
						if($project_old[0]['client_id']){
							if($project_old[0]['client_id'] != $this->session->userdata('user_id')){
								$data = array(
									'notification' => '<span class="text-info">'.$file_data['file_name'].'</span>',
									'type' => 'project_file',	
									'type_id' => $project_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $project_old[0]['client_id'],	
								);
								$notification_id = $this->notifications_model->create($data);
							}
						}

						$system_admins = $this->ion_auth->users(array(1))->result();
						if($system_admins){
							foreach ($system_admins as $system_user) {
								if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
									$data = array(
										'notification' => '<span class="text-info">'.$file_data['file_name'].'</span>',
										'type' => 'project_file',	
										'type_id' => $project_id,	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $system_user->user_id,	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}
						}
					}
					return true;
				}else{
					header("HTTP/1.0 400 Bad Request");
					$this->data = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
					return false;
				}
			}
			header("HTTP/1.0 400 Bad Request");
			$this->data = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
			echo json_encode($this->data);
			return false;
		}else{
			header("HTTP/1.0 400 Bad Request");
			$this->data = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
			echo json_encode($this->data);
			return false;
		}
	}

	public function get_tasks_files($task_id = '')
	{
		if ($this->ion_auth->logged_in())
		{	
			$task_id = (empty($task_id) && isset($_GET['task_id']) && !empty($_GET['task_id']))?$_GET['task_id']:'';
			$files = $this->projects_model->get_tasks_files($task_id);
			if($files){
				foreach($files as $key => $file){
					$temp[$key] = $file;
					$temp[$key]['file_size'] = formatBytes($file['file_size']);
					if(file_exists('assets/uploads/tasks/'.$file['file_name'])){
						$file_upload_path = 'assets/uploads/tasks/'.$file['file_name'];
					}else{
						$file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/tasks/'.$file['file_name'];
					}
					if($this->ion_auth->is_admin()){
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a>
						<a href="'.base_url('projects/delete-task-files/'.$file['id']).'" data-delete="'.base_url('projects/delete-task-files/'.$file['id']).'" class="btn btn-icon btn-sm btn-danger delete_files" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
					}else{
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a>';
					}

				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_project_files($project_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$files = $this->projects_model->get_project_files($project_id);
			if($files){
				foreach($files as $key => $file){
					$temp[$key] = $file;
					$temp[$key]['file_size'] = formatBytes($file['file_size']);
					
					if(file_exists('assets/uploads/projects/'.$file['file_name'])){
						$file_upload_path = 'assets/uploads/projects/'.$file['file_name'];
					}else{
						$file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/projects/'.$file['file_name'];
					}
					if($this->ion_auth->is_admin()){
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a>
						<a href="'.base_url('projects/delete-project-files/'.$file['id']).'" data-delete="'.base_url('projects/delete-project-files/'.$file['id']).'" class="btn btn-icon btn-sm btn-danger delete_files" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
					}else{
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a>';
					}

				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_project_users($project_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$users = $this->projects_model->get_project_users($project_id);
			if($users){
				foreach($users as $key => $user){
					$temp[$key] = $user;
					$temp[$key]['full_name'] = $user['first_name'].' '.$user['last_name'];
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	public function get_project_users2($project_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$users = $this->projects_model->get_project_users2($project_id);
			if($users){
				foreach($users as $key => $user){
					$temp[$key] = $user;
					$temp[$key]['full_name'] = $user['first_name'].' '.$user['last_name'];
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function detail()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('project_view')))
		{

			$this->data['page_title'] = 'Projects Detail - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();
			
			$this->data['project_status'] = project_status();
			$this->data['task_status'] = task_status();
			
			$id = preg_replace('/^p_/', '', $this->uri->segment(3));

			if($id && is_numeric($id)){

				if($this->ion_auth->in_group(4) && !is_my_project($id)){
					redirect('projects', 'refresh');
				}

				$this->notifications_model->edit('', 'new_project', $id, '', '');
				$this->notifications_model->edit('', 'project_status', $id, '', '');
				$this->notifications_model->edit('', 'project_file', $id, '', '');
				$this->notifications_model->edit('', 'project_comment', $id, '', '');

				if($this->ion_auth->is_admin()){
					$this->data['project'] = $this->projects_model->get_projects('',$id);
				}else{
					$this->data['project'] = $this->projects_model->get_projects($this->session->userdata('user_id'),$id);
				}
				
				$this->data['project_comments'] = $this->projects_model->get_comments('project_comment','',$id);

			}else{
				redirect('projects', 'refresh');
			}
			$this->load->view('projects-detail',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_tasks_list()
	{
		if ($this->ion_auth->logged_in())
		{
			return $this->projects_model->get_tasks_list();
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
	
	public function tasks_list()
	{
		
		if ($this->ion_auth->logged_in() && is_module_allowed('tasks') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('task_view')))
		{
			
			$this->data['page_title'] = 'Tasks - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();

			$this->data['projecr_users'] = $this->projects_model->get_project_users();

			$this->data['task_status'] = task_status();
			$this->data['task_priorities'] = priorities();

			if($this->ion_auth->is_admin()){
				$this->data['projects'] = $this->projects_model->get_projects();
			}else{
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

			$this->load->view('tasks-list',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_projects_list()
	{
		if ($this->ion_auth->logged_in())
		{
			return $this->projects_model->get_projects_list();
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
	
	public function list()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('projects') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('project_view')))
		{
			$this->data['page_title'] = 'Projects - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			
            // $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
            $userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();

			$this->data['project_status'] = project_status();

			if($this->ion_auth->is_admin()){
				$this->data['projects_all'] = $this->projects_model->get_projects();
			}else{
				$this->data['projects_all'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

			$this->load->view('projects-list',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('projects') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('project_view')))
		{
			$this->data['page_title'] = 'Projects - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			
			// $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
            $userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();

			$this->data['project_status'] = project_status();

			$config = array();
			$config["base_url"] = base_url('projects');
			$config["total_rows"] = get_count('id','projects','');
			$config["per_page"] = 10;
			$config["uri_segment"] = 2;
			    
            $config['next_link']        = 'Next';
            $config['prev_link']        = 'Previous';
            $config['first_link']       = false;
            $config['last_link']        = false;
            $config['full_tag_open']    = '<nav aria-label="...">
											<ul class="pagination">';
            $config['full_tag_close']   = '</ul>
											</nav>';
			$config['attributes']       = ['class' => 'page-link'];
			
            $config['first_tag_open']   = '<li class="page-item">';
			$config['first_tag_close']  = '</li>';
			
            $config['prev_tag_open']    = '<li class="page-item">';
			$config['prev_tag_close']   = '</li>';
			
            $config['next_tag_open']    = '<li class="page-item">';
			$config['next_tag_close']   = '</li>';
			
            $config['last_tag_open']    = '<li class="page-item">';
			$config['last_tag_close']   = '</li>';
			
            $config['cur_tag_open']     = '<li class="page-item active">
			<a class="page-link" href="#">';
			$config['cur_tag_close']    = '<span class="sr-only">(current)</span></a>
			</li>';
			
            $config['num_tag_open']     = '<li class="page-item">';
            $config['num_tag_close']    = '</li>';
			
			$this->pagination->initialize($config);

			$page = ($this->uri->segment(2) && is_numeric($this->uri->segment(2)))?$this->uri->segment(2):0;
			$this->data["links"] = $this->pagination->create_links();

			if(isset($_GET['status']) && !empty($_GET['status']) && is_numeric($_GET['status'])){
				$filter = $_GET['status'];
				$filter_type = 'status';
			}elseif(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user'])){
				$filter = $_GET['user'];
				$filter_type = 'user';
			}elseif(isset($_GET['client']) && !empty($_GET['client']) && is_numeric($_GET['client'])){
				$filter = $_GET['client'];
				$filter_type = 'client';
			}else{
				$filter = (isset($_GET['sortby']) && !empty($_GET['sortby']) && ($_GET['sortby'] == 'latest' || $_GET['sortby'] == 'old' || $_GET['sortby'] == 'name'))?$_GET['sortby']:'latest';
				$filter_type = 'sortby';
			}
			
			if($this->ion_auth->is_admin()){
				$this->data['projects'] = $this->projects_model->get_projects('','',$config["per_page"], $page, $filter_type, $filter);
				$this->data['projects_all'] = $this->projects_model->get_projects();
			}else{
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'),'',$config["per_page"], $page, $filter_type, $filter);
				$this->data['projects_all'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}
			$this->load->view('projects',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_clients_projects()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('to_id', 'Client ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$this->data['error'] = false;
				$this->data['data'] = $this->projects_model->get_clients_projects($this->input->post('to_id'));
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
	public function get_tasks_by_project_id()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('project_id', 'Project ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				if($this->ion_auth->is_admin()){
					$this->data['data'] = $this->projects_model->get_tasks('','',$this->input->post('project_id'));
				}else{
					$this->data['data'] = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$this->input->post('project_id'));
				}

				$this->data['error'] = false;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
	public function get_projects()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('project_id', 'Project ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if($this->form_validation->run() == TRUE){
				$this->data['error'] = false;
				$this->data['data'] = $this->projects_model->get_projects('',$this->input->post('project_id'));
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function edit_project()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('project_edit')))
		{
			$this->form_validation->set_rules('title', 'Project Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('client', 'Client', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('update_id', 'Project ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_date', 'Ending Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('budget', 'Budget', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$project_id = $this->input->post('update_id');
				$starting_date = format_date($this->input->post('starting_date'),"Y-m-d");

				if ($this->input->post('present')) {
					$data = array(
						'client_id' => $this->input->post('client')?$this->input->post('client'):NULL,
						'saas_id' => $this->session->userdata('saas_id'),
						'created_by' => $this->session->userdata('user_id'),
						'title' => $this->input->post('title'),
						'description' => $this->input->post('description'),
						'starting_date' => $starting_date,
						'ending_date' => 0,
						'present' => 1,
						'budget' => $this->input->post('budget'),		
						'status' => $this->input->post('status'),		
					);
				} else{
					$ending_date = format_date($this->input->post('ending_date'),"Y-m-d");

					if($ending_date < $starting_date){
						$response['error'] = true;
						$response['message'] = $this->lang->line('ending_date_should_not_be_less_then_starting_date')?$this->lang->line('ending_date_should_not_be_less_then_starting_date'):"Ending date should not be less then starting date.";
						echo json_encode($response);
						return false;
					}

					$data = array(
						'client_id' => $this->input->post('client')?$this->input->post('client'):NULL,
						'saas_id' => $this->session->userdata('saas_id'),
						'created_by' => $this->session->userdata('user_id'),
						'title' => $this->input->post('title'),
						'description' => $this->input->post('description'),
						'starting_date' => $starting_date,
						'ending_date' => $ending_date,
						'present' => 0,
						'budget' => $this->input->post('budget'),		
						'status' => $this->input->post('status'),		
					);
				}

				$project_old = $this->projects_model->get_projects('',$project_id);

				if($this->projects_model->edit_project($project_id,$data)){
					if($this->input->post('users')){
						$this->projects_model->delete_project_users($project_id);

						$new_status = project_status('',$this->input->post('status'));

						foreach($this->input->post('users') as $user_id){
							$user_data = array(
								'user_id' => $user_id,
								'project_id' => $project_id,	
							);
							$this->projects_model->create_project_users($user_data);

							if($project_old[0]['status'] != $this->input->post('status')){
								$data = array(
									'notification' => $project_old[0]['status'],
									'type' => 'project_status',	
									'type_id' => $project_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $user_id,	
								);
								$notification_id = $this->notifications_model->create($data);
							}

						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'project_id' => $project_id,	
						);
						$this->projects_model->create_project_users($user_data);
					}

					if($project_old[0]['status'] != $this->input->post('status')){
						$system_admins = $this->ion_auth->users(array(1))->result();
						if($system_admins){
							foreach ($system_admins as $system_user) {
								if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
									$data = array(
										'notification' => $project_old[0]['status'],
										'type' => 'project_status',	
										'type_id' => $project_id,	
										'from_id' => $this->session->userdata('user_id'),
										'to_id' => $system_user->user_id,	
									);
									$notification_id = $this->notifications_model->create($data);
								}
							}
						}
					}

					if($this->input->post('client') && $project_old[0]['status'] != $this->input->post('status')){
						$data = array(
							'notification' => $project_old[0]['status'],
							'type' => 'project_status',	
							'type_id' => $project_id,	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $this->input->post('client'),	
						);
						$notification_id = $this->notifications_model->create($data);
					}
					$this->session->set_flashdata('message', $this->lang->line('project_updated_successfully')?$this->lang->line('project_updated_successfully'):"Project updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('project_updated_successfully')?$this->lang->line('project_updated_successfully'):"Project updated successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function create_project()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('project_create')))
		{
			if(!my_plan_features('projects')){ 
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

			$this->form_validation->set_rules('title', 'Project Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('client', 'Client', 'trim|strip_tags|xss_clean');
			
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_date', 'Ending Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('budget', 'Budget', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$starting_date = format_date($this->input->post('starting_date'),"Y-m-d");
				
				if ($this->input->post('present')) {
					$data = array(
						'client_id' => $this->input->post('client')?$this->input->post('client'):NULL,
						'saas_id' => $this->session->userdata('saas_id'),
						'created_by' => $this->session->userdata('user_id'),
						'title' => $this->input->post('title'),
						'description' => $this->input->post('description'),
						'starting_date' => $starting_date,
						'ending_date' => 0,
						'present' => 1,
						'budget' => $this->input->post('budget'),		
						'status' => $this->input->post('status'),		
					);
				} else{
					$ending_date = format_date($this->input->post('ending_date'),"Y-m-d");

					if($ending_date < $starting_date){
						$response['error'] = true;
						$response['message'] = $this->lang->line('ending_date_should_not_be_less_then_starting_date')?$this->lang->line('ending_date_should_not_be_less_then_starting_date'):"Ending date should not be less then starting date.";
						echo json_encode($response);
						return false;
					}

					$data = array(
						'client_id' => $this->input->post('client')?$this->input->post('client'):NULL,
						'saas_id' => $this->session->userdata('saas_id'),
						'created_by' => $this->session->userdata('user_id'),
						'title' => $this->input->post('title'),
						'description' => $this->input->post('description'),
						'starting_date' => $starting_date,
						'ending_date' => $ending_date,
						'present' => 0,
						'budget' => $this->input->post('budget'),		
						'status' => $this->input->post('status'),		
					);
				}
				$project_id = $this->projects_model->create_project($data);
				foreach($this->input->post('users') as $user){
					$team = $this->ion_auth->user($user)->row();
					$team_member .= $team->first_name.' '.$team->last_name.',';
				}
				if($project_id){
					$CLIENT_DATA = $this->ion_auth->user($this->input->post('client'))->row();
					$template_data = array();
					$template_data['CLIENT_NAME'] = $CLIENT_DATA->first_name.' '.$CLIENT_DATA->last_name;
					$template_data['PROJECT_TITLE'] = $this->input->post('title');
					$template_data['PROJECT_DESCRIPTION'] = $this->input->post('description');
					$template_data['STARTING_DATE'] = $starting_date;
					$template_data['DUE_DATE'] = $ending_date;
					$template_data['TEAM_MEMBER'] = $team_member;
					$template_data['BUDGET'] = $this->input->post('budget');
					$template_data['PROJECT_URL'] = base_url('projects');
					$email_template = render_email_template('client_new_project', $template_data);

					if($this->input->post('client')){
						$data = array(
							'notification' => '<span class="text-info">'.$this->input->post('title').'</span>',
							'type' => 'new_project',	
							'type_id' => $project_id,	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $this->input->post('client'),	
						);
						$notification_id = $this->notifications_model->create($data);
						
						if($this->input->post('send_email_notification')){
							send_mail($CLIENT_DATA->email, $email_template[0]['subject'], $email_template[0]['message']);
						}
					}

					$system_admins = $this->ion_auth->users(array(1))->result();
					if($system_admins){
						foreach ($system_admins as $system_user) {
							if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
								$data = array(
									'notification' => '<span class="text-info">'.$this->input->post('title').'</span>',
									'type' => 'new_project',
									'type_id' => $project_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $system_user->user_id,	
								);
								$notification_id = $this->notifications_model->create($data);
							}
						}
					}

					if($this->input->post('users')){
						foreach($this->input->post('users') as $user_id){
						    
							$user_data = array(
							'user_id' => $user_id,
							'project_id' => $project_id,	
							);
							$this->projects_model->create_project_users($user_data);
							$data = array(
								'notification' => '<span class="text-info">'.$this->input->post('title').'</span>',
								'type' => 'new_project',	
								'type_id' => $project_id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $user_id,	
							);
							$notification_id = $this->notifications_model->create($data);
							
							if($this->input->post('send_email_notification')){
								$to_user = $this->ion_auth->user($user_id)->row();
								$template_data = array();
								$template_data['PROJECT_TITLE'] = $this->input->post('title');
								$template_data['NAME'] = $to_user->first_name.' '.$to_user->last_name;
								$template_data['PROJECT_DESCRIPTION'] = $this->input->post('description');
								$template_data['STARTING_DATE'] = $starting_date;
								$template_data['DUE_DATE'] = $ending_date;
								$template_data['TEAM_MEMBER'] = $team_member;
								$template_data['BUDGET'] = $this->input->post('budget');
								$template_data['PROJECT_URL'] = base_url('projects');
								$email_template = render_email_template('new_project', $template_data);
								
								send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
							}
						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'project_id' => $project_id,	
						);
						$this->projects_model->create_project_users($user_data);
					}
					$this->session->set_flashdata('message', $this->lang->line('project_created_successfully')?$this->lang->line('project_created_successfully'):"Project created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('project_created_successfully')?$this->lang->line('project_created_successfully'):"Project created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}
	
	public function tasks()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('tasks') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('task_view')))
		{
			
			if($this->uri->segment(3) && (!is_numeric($this->uri->segment(3)) || !is_my_project($this->uri->segment(3)))){
				redirect('projects/tasks', 'refresh');
			}

			$this->data['page_title'] = 'Tasks - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			// $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$this->data['project_id'] = $project_id = $this->uri->segment(3);

			if($project_id && $this->ion_auth->in_group(4)){
				if(!is_my_project($project_id)){
					redirect('projects/tasks', 'refresh');
				}
			}

			if($this->uri->segment(4) && is_numeric($this->uri->segment(4))){
				$this->notifications_model->edit('', 'task_comment', $this->uri->segment(4), '', '');
				$this->notifications_model->edit('', 'task_file', $this->uri->segment(4), '', '');
				$this->notifications_model->edit('', 'task_status', $this->uri->segment(4), '', '');
				$this->notifications_model->edit('', 'new_task', $this->uri->segment(4), '', '');
			}

			$this->data['projecr_users'] = $this->projects_model->get_project_users($project_id);
			$this->data['task_status'] = task_status();
			$this->data['task_priorities'] = priorities();

			if($this->ion_auth->is_admin()){
				if(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user'])){
					$this->data['tasks'] = $this->projects_model->get_tasks($_GET['user'],'',$project_id);
					$this->data['projects'] = $this->projects_model->get_projects($_GET['user']);
				}elseif(isset($_GET['search']) && !empty($_GET['search'])){
					$this->data['tasks'] = $this->projects_model->get_tasks('','',$project_id,$_GET['search']);
				}elseif(isset($_GET['sortby']) && !empty($_GET['sortby'])){
					$this->data['tasks'] = $this->projects_model->get_tasks('','',$project_id,'',$_GET['sortby']);
				}elseif(isset($_GET['priority']) && !empty($_GET['priority']) && is_numeric($_GET['priority'])){
					$this->data['tasks'] = $this->projects_model->get_tasks('','',$project_id,'','',$_GET['priority']);
				}elseif(isset($_GET['upcoming']) && !empty($_GET['upcoming'])){
					$this->data['tasks'] = $this->projects_model->get_tasks('','',$project_id,'','','', $_GET['upcoming']);
				}else{
					$this->data['tasks'] = $this->projects_model->get_tasks('','',$project_id);
				}
				$this->data['projects'] = $this->projects_model->get_projects();
			}else{
				if(isset($_GET['search']) && !empty($_GET['search'])){
					$this->data['tasks'] = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id,$_GET['search']);
				}elseif(isset($_GET['sortby']) && !empty($_GET['sortby'])){
					$this->data['tasks'] = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id,'',$_GET['sortby']);
				}elseif(isset($_GET['priority']) && !empty($_GET['priority']) && is_numeric($_GET['priority'])){
					$this->data['tasks'] = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id,'','',$_GET['priority']);
				}elseif(isset($_GET['upcoming']) && !empty($_GET['upcoming'])){
					$this->data['tasks'] = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id,'','','', $_GET['upcoming']);
				}else{
					$this->data['tasks'] = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id);
				}
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

			$this->load->view('tasks',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_tasks()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('task_id', 'Task ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$this->data['error'] = false;
				$this->data['data'] = $this->projects_model->get_tasks('',$this->input->post('task_id'));
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function get_comments()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['error'] = false;
			$this->data['data'] = $this->projects_model->get_comments('task_comment','',$this->input->post('to_id'));
			$this->data['message'] = 'Successful';
			echo json_encode($this->data);
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
public function create_task()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('task_create')))
		{
			if(!my_plan_features('tasks')){ 
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

			$this->form_validation->set_rules('project_id', 'ID', 'trim|required|is_numeric|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'Task Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('priority', 'Priority', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$due_date = format_date($this->input->post('due_date'),"Y-m-d");
				$starting_date = format_date($this->input->post('starting_date'),"Y-m-d");
				
				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),
					'project_id' => $this->input->post('project_id'),
					'created_by' => $this->session->userdata('user_id'),
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'due_date' => $due_date,
					'starting_date' => $starting_date,
					'priority' => $this->input->post('priority'),
					'status' => $this->input->post('status'),		
				);
				$task_id = $this->projects_model->create_task($data);
				if($task_id){
				    $team_members='';
					foreach($this->input->post('users') as $user){
						$team = $this->ion_auth->user($user)->row();
						$team_members .= $team->first_name.' '.$team->last_name.',';
					}
					$project_old = $this->projects_model->get_projects('',$this->input->post('project_id'));
					if($this->input->post('users')){
						
						foreach($this->input->post('users') as $user_id){
							
							$user_data = array(
								'user_id' => $user_id,
								'task_id' => $task_id,	
							);
							$this->projects_model->create_task_users($user_data);
							if($user_id != $this->session->userdata('user_id')){
								$data = array(
									'notification' => '<span class="text-info">'.$this->input->post('title').'</span>',
									'type' => 'new_task',	
									'type_id' => $task_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $user_id,	
								);
								$notification_id = $this->notifications_model->create($data);
								
								if($this->input->post('send_email_notification')){
									$to_user = $this->ion_auth->user($user_id)->row();
									$template_data = array();
									$template_data['PROJECT_TITLE'] = $project_old[0]['title'];
									$template_data['TASK_TITLE'] = $this->input->post('title');
									$template_data['TASK_DESCRIPTION'] = $this->input->post('description');
									$template_data['STARTING_DATE'] = $starting_date;
									$template_data['TEAM_MEMBER'] = $team_members;
									$template_data['DUE_DATE'] = $due_date;
									$template_data['NAME'] = $to_user->first_name.' '.$to_user->last_name;
									$template_data['TASK_URL'] = base_url('projects/tasks');
									$email_template = render_email_template('new_task', $template_data);
									send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
								}
							}
						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'task_id' => $task_id,	
						);
						$this->projects_model->create_task_users($user_data);
					}

					$system_admins = $this->ion_auth->users(array(1))->result();
					if($system_admins){
						foreach ($system_admins as $system_user) {
							if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
								$data = array(
									'notification' => '<span class="text-info">'.$this->input->post('title').'</span>',
									'type' => 'new_task',	
									'type_id' => $task_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $system_user->user_id,	
								);
								$notification_id = $this->notifications_model->create($data);
							}
						}
					}
					
					if($project_old[0]['client_id']){
						if($project_old[0]['client_id'] != $this->session->userdata('user_id')){
							$data = array(
								'notification' => '<span class="text-info">'.$this->input->post('title').'</span>',
								'type' => 'new_task',	
								'type_id' => $task_id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $project_old[0]['client_id'],	
							);
							$notification_id = $this->notifications_model->create($data);
							$to_user = $this->ion_auth->user($project_old[0]['client_id'])->row();
							$template_data = array();
							$template_data['PROJECT_TITLE'] = $project_old[0]['title'];
							$template_data['TASK_TITLE'] = $this->input->post('title');
							$template_data['TASK_DESCRIPTION'] = $this->input->post('description');
							$template_data['STARTING_DATE'] = $starting_date;
							$template_data['TEAM_MEMBER'] = $team_members;
							$template_data['DUE_DATE'] = $due_date;
							$template_data['CLIENT_NAME'] = $to_user->first_name.' '.$to_user->last_name;
							$template_data['TASK_URL'] = base_url('projects/tasks');
							$email_template = render_email_template('client_new_task', $template_data);
							if($this->input->post('send_email_notification')){
								send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
							}
						}
					}

					$this->session->set_flashdata('message', $this->lang->line('task_created_successfully')?$this->lang->line('task_created_successfully'):"Task created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('task_created_successfully')?$this->lang->line('task_created_successfully'):"Task created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function edit_task()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('task_edit')))
		{
			$this->form_validation->set_rules('update_id', 'ID', 'trim|required|is_numeric|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'Task Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('priority', 'Priority', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$task_id = $this->input->post('update_id');
				$due_date = format_date($this->input->post('due_date'),"Y-m-d");
				$starting_date = format_date($this->input->post('starting_date'),"Y-m-d");
				
				$data = array(
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'due_date' => $due_date,
					'starting_date' => $starting_date,
					'priority' => $this->input->post('priority'),
					'status' => $this->input->post('status'),		
				);
				
				$task_status = $this->input->post('status');
				$task_status_new = task_status($this->input->post('status'));
				$task_old = $this->projects_model->get_tasks('',$task_id);
				$project_old = $this->projects_model->get_projects('',$task_old[0]['project_id']);

				if($this->input->post('status') != $this->input->post('status_duplicate')){
					$this->db->where('id', $task_id);
					$query = $this->db->get('tasks');
					$inserted_task_data = $query->row_array(); 
					$timeline_data = array(
						'saas_id' => $inserted_task_data['saas_id'],
						'project_id' => $inserted_task_data['project_id'],
						'task_id' => $inserted_task_data['id'],
						'status' => $this->input->post('status'),
						'date_time' => date('Y-m-d H:i:s')
					);
					$this->projects_model->create_timeline($timeline_data);
				}

				if($this->projects_model->edit_task($task_id,$data)){


					if($this->input->post('users')){
						$this->projects_model->delete_task_users($task_id);
						foreach($this->input->post('users') as $user_id){
							$user_data = array(
								'user_id' => $user_id,
								'task_id' => $task_id,	
							);
							$this->projects_model->create_task_users($user_data);
							
							if($user_id != $this->session->userdata('user_id') && $this->input->post('status') != $task_old[0]['status']){
								$this->db->where('id', $task_status);
								$query = $this->db->get('task_status');
								$task_data = $query->row_array(); 
								$data = array(
									'notification' => 'Project <span class="text-info">'.$this->input->post('title').'</span> now <span class="text-info">'.$task_data['title'].'</span>.',
									'type' => 'task_status',	
									'type_id' => $task_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $user_id,	
								);
								$notification_id = $this->notifications_model->create($data);
							}

						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'task_id' => $task_id,	
						);
						$this->projects_model->create_task_users($user_data);
					}

					if($project_old[0]['client_id'] && $this->input->post('status') != $task_old[0]['status']){
						if($project_old[0]['client_id'] != $this->session->userdata('user_id')){
							$this->db->where('id', $task_status);
							$query = $this->db->get('task_status');
							$task_data = $query->row_array(); 
							$data = array(
								'notification' => 'Project <span class="text-info">'.$this->input->post('title').'</span> now <span class="text-info">'.$task_data['title'].'</span>.',
								'type' => 'task_status',	
								'type_id' => $task_id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $project_old[0]['client_id'],	
							);
							$notification_id = $this->notifications_model->create($data);
						}
					}
					
					$system_admins = $this->ion_auth->users(array(1))->result();
					if($system_admins){
						foreach ($system_admins as $system_user) {
							if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id') && $this->input->post('status') != $task_old[0]['status']){
								$this->db->where('id', $task_status);
								$query = $this->db->get('task_status');
								$task_data = $query->row_array(); 
								$data = array(
									'notification' => 'Project <span class="text-info">'.$this->input->post('title').'</span> now <span class="text-info">'.$task_data['title'].'</span>.',
									'type_id' => $task_id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $system_user->user_id,	
								);
								$notification_id = $this->notifications_model->create($data);
							}
						}
					}

					$this->session->set_flashdata('message', $this->lang->line('task_updated_successfully')?$this->lang->line('task_updated_successfully'):"Task updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('task_updated_successfully')?$this->lang->line('task_updated_successfully'):"Task updated successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}
		public function get_user_progress(){
        $user_id=$this->input->post('user_id');
        $task_id=$this->input->post('task_id');
        $Query = $this->db->query("SELECT * FROM timeline WHERE task_id='$task_id'");
        $result = $Query->result_array();
    
    // Check if there are results
    if (!empty($result)) {
        foreach ($result as $row) {
            $task_id = $row['task_id'];
            $status = $row['status'];
            $date_time = $row['date_time'];
    
            $taskInfo = array(
                'task_id' => $task_id,
                'status' => $status,
                'date_time' => $date_time
            );
    
            // Add the task information array to the result array
            $taskInfoArray[] = $taskInfo;
            
        }
    } 
        echo json_encode($taskInfoArray);
    }

}







