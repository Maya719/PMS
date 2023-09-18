<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('shift')  && $this->ion_auth->is_admin())
		{
			$this->data['page_title'] = 'Shift Scheduling - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			// $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();
			$this->load->view('shift',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	// public function list()
	// {
	// 	if ($this->ion_auth->logged_in() && is_module_allowed('shift')  && $this->ion_auth->is_admin())
	// 	{
	// 		$this->data['page_title'] = 'Shift Scheduling - '.company_name();
	// 		$this->data['current_user'] = $this->ion_auth->user()->row();
	// 		$this->data['system_users'] = $this->ion_auth->users(array(1,2,4))->result();
	// 		$query = $this->db->get('shift');
    //     	$this->data['shift_types'] = $query->result_array();
	// 		$this->load->view('shift_list',$this->data);
	// 	}else{
	// 		redirect('auth', 'refresh');
	// 	}
	// }

	public function create()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('starting_time', 'Starting Time', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_time', 'Ending Time', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('break_start', 'Break Start', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('break_end', 'Break End', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('half_day_check_out', 'Half Day Check Out', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('half_day_check_in', 'Half Day Check In', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');


			if($this->form_validation->run() == TRUE){
				$data = array(
					'starting_time' => format_date($this->input->post('starting_time'),"H:i:s"),
					'ending_time' => format_date($this->input->post('ending_time'),"H:i:s"),
					'break_start' => format_date($this->input->post('break_start'),"H:i:s"),
					'break_end' => format_date($this->input->post('break_end'),"H:i:s"),
					'half_day_check_out' => format_date($this->input->post('half_day_check_out'),"H:i:s"),
					'half_day_check_in' => format_date($this->input->post('half_day_check_in'),"H:i:s"),
					'name' => $this->input->post('name'),	
				);
				
				$id = $this->shift_model->create($data);
				
				if($id){

					if($this->ion_auth->in_group(2)){
						$system_admins = $this->ion_auth->users(array(1))->result();
						foreach ($system_admins as $system_user) {
							if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
								$notification_data = array(
									'notification' => 'Biometric missing request received',
									'type' => 'biometric_request',	
									'type_id' => $id,	
									'from_id' => $this->input->post('user_id')?$this->input->post('user_id'):$this->session->userdata('user_id'),
									'to_id' => $system_user->user_id,		
								);
								$notification_id = $this->notifications_model->create($notification_data);
							}
						}
					}

					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
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

	public function shift_create()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4)) {
			$this->form_validation->set_rules('type', 'Type', 'trim|required|strip_tags|xss_clean');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'type' => $this->input->post('type'),
				);

				$user_ids = $this->input->post('users')?$this->input->post('users'):'';
				// Update users with shift_id
				$this->shift_model->updateUserShiftId($data['type'], $user_ids);


				$this->session->set_flashdata('message', $this->lang->line('created_successfully') ? $this->lang->line('created_successfully') : "Created successfully.");
				$this->session->set_flashdata('message_type', 'success');
				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('created_successfully') ? $this->lang->line('created_successfully') : "Created successfully.";
				echo json_encode($this->data);
			} else {
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data);
			}
		} else {
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied') ? $this->lang->line('access_denied') : "Access Denied";
			echo json_encode($this->data);
		}
	}

	

	public function edit()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('update_id', 'Update ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('starting_time', 'Starting Time', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_time', 'Ending Time', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('break_start', 'Break Start', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('break_end', 'Break End', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('half_day_check_out', 'Half Day Check Out', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('half_day_check_in', 'Half Day Check In', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$data = array(
					'starting_time' => format_date($this->input->post('starting_time'),"H:i:s"),
					'ending_time' => format_date($this->input->post('ending_time'),"H:i:s"),
					'break_start' => format_date($this->input->post('break_start'),"H:i:s"),
					'break_end' => format_date($this->input->post('break_end'),"H:i:s"),
					'half_day_check_out' => format_date($this->input->post('half_day_check_out'),"H:i:s"),
					'half_day_check_in' => format_date($this->input->post('half_day_check_in'),"H:i:s"),
					'name' => $this->input->post('name'),
				);

				if($this->shift_model->edit($this->input->post('update_id'), $data)){

					$user_ids = $this->input->post('users')?$this->input->post('users'):'';

					// Update users with shift_id
					$this->shift_model->updateUserShiftId($this->input->post('update_id'), $user_ids);

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
	
	public function delete($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('shift'))
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			if(!empty($id) && is_numeric($id) && $this->shift_model->delete($id)){

				$this->notifications_model->delete('', 'new_meeting', $id);

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

	public function get_shift()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			return $this->shift_model->get_shift();
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function get_shift_by_id()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->shift_model->get_shift_by_id($this->input->post('id'));
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

}