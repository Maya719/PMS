<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Leads extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->leads_model->delete($id)){

				$this->notifications_model->delete('', 'new_lead', $id);

				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Lead deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Lead deleted successfully.";
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

	public function edit()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('update_id', 'Lead ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('company', 'company', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('value', 'value', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('source', 'source', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('email', 'email', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('mobile', 'mobile', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'status', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('assigned', 'assigned', 'trim|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'company' => $this->input->post('company'),	
					'value' => $this->input->post('value'),	
					'source' => $this->input->post('source'),	
					'email' => $this->input->post('email'),	
					'mobile' => $this->input->post('mobile'),	
					'status' => $this->input->post('status'),	
					'assigned' => $this->input->post('assigned')?$this->input->post('assigned'):$this->session->userdata('user_id'),	
				);
				
				if($this->leads_model->edit($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('updated_successfully')?$this->lang->line('updated_successfully'):"Lead updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('updated_successfully')?$this->lang->line('updated_successfully'):"Lead updated successfully.";
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

	public function ajax_get_leads_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$leads = $this->leads_model->get_leads_by_id($id);
			if(!empty($leads)){
				$this->data['error'] = false;
				$this->data['data'] = $leads;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'No user found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function get_leads()
	{
		if ($this->ion_auth->logged_in())
		{
			return $this->leads_model->get_leads();
		}else{
			return '';
		}
	}

	public function create()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('company', 'company', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('value', 'value', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('source', 'source', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('email', 'email', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('mobile', 'mobile', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'status', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('assigned', 'assigned', 'trim|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),
					'created_by' => $this->session->userdata('user_id'),
					'company' => $this->input->post('company'),	
					'value' => $this->input->post('value'),	
					'source' => $this->input->post('source'),	
					'email' => $this->input->post('email'),	
					'mobile' => $this->input->post('mobile'),	
					'status' => $this->input->post('status'),	
					'assigned' => $this->input->post('assigned')?$this->input->post('assigned'):$this->session->userdata('user_id'),	
				);

				$id = $this->leads_model->create($data);
				
				if($id){

					if($this->input->post('assigned')){
						$data = array(
							'notification' => $this->input->post('company'),
							'type' => 'new_lead',	
							'type_id' => $id,	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $this->input->post('assigned'),	
						);
						$notification_id = $this->notifications_model->create($data);
					}

					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Lead created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Lead created successfully.";
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

	public function index()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('leads') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('lead_view')))
		{
			$this->data['page_title'] = 'Leads - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			
			$this->notifications_model->edit('', 'new_lead');

			$this->load->view('leads',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}
