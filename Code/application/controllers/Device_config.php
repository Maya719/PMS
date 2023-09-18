<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Device_config extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}


	public function index()
	{
		if ($this->ion_auth->logged_in()  && is_module_allowed('attendance') && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->data['page_title'] = 'Device Configuration - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			// $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$this->load->view('device_config',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	public function create()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('device_name', 'Device Name', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('device_ip', 'Device Ip Address', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('port', 'Device External Port', 'trim|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),
					'device_name' => $this->input->post('device_name'),
					'device_ip' => $this->input->post('device_ip'),
					'port' => $this->input->post('port'),
				);
				$id = $this->device_config_model->create($data);
				if($id){
					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again." ;
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
	
	public function get_device_config()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			return $this->device_config_model->get_device_config();
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
	    
	public function delete($id='')
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{

			if(empty($id)){
				$id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->device_config_model->delete($id)){
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
	public function get_device_by_id()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->device_config_model->get_device_by_id($this->input->post('id'));
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

	
	public function edit()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('update_id', 'Device ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('device_name', 'Device Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('device_ip', 'Device Ip', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('port', 'Device External Port', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
                    $data['device_name'] = $this->input->post('device_name');
                    $data['device_ip'] = $this->input->post('device_ip');
                    $data['port'] = $this->input->post('port');
					
				if($this->device_config_model->edit($this->input->post('update_id'), $data)){
					$user_ids = $this->input->post('users')?$this->input->post('users'):'';

					// Update users with shift_id
					$this->device_config_model->updateUserDeviceId($this->input->post('update_id'), $user_ids);
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

    
}