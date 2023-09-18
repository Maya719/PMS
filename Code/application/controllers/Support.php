<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function create_support_message()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->form_validation->set_rules('message', 'message', 'required|xss_clean');
			$this->form_validation->set_rules('to_id', 'ticket ID', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){

				$data['from_id'] = $this->session->userdata('user_id');
				$data['to_id'] = $this->input->post('to_id');
				$data['message'] = $this->input->post('message');

				$id = $this->support_model->create_support_message($data);
				
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

	public function chat()
	{
		if ($this->ion_auth->logged_in() && $this->uri->segment(3) && is_numeric($this->uri->segment(3)) && is_module_allowed('support') && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Support Chat - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$support_data = $this->support_model->get_support_by_id($this->uri->segment(3));
			if($support_data){
				$this->data['ticket_id'] = $this->uri->segment(3);
				$this->data['support_data'] = $support_data;
				$this->data['support_messages'] = $this->support_model->get_support_messages_by_ticket_id($this->uri->segment(3));
				$this->support_model->mark_as_read_support_messages_by_user_id($this->session->userdata('user_id'), $this->uri->segment(3));
				$this->load->view('support-chat',$this->data);
			}else{
				redirect('support', 'refresh');
			}
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function edit()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('update_id', 'ID', 'trim|required|strip_tags|xss_clean|is_numeric');
		
			if($this->form_validation->run() == TRUE){

				$data['subject'] = $this->input->post('subject');
				$data['status'] = $this->input->post('status');
				$data['user_id'] = $this->input->post('user_id');

				if($this->support_model->edit($data, $this->input->post('update_id'))){

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

	public function get_support_by_id()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->support_model->get_support_by_id($this->input->post('id'));
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

	public function index()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('support') && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Support - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1))->result();
			
			$this->load->view('support',$this->data);

		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_support()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			return $this->support_model->get_support();
		}else{
			return '';
		}
	}

	public function create()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->form_validation->set_rules('subject', 'ticket subject', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$data['user_id'] = $this->input->post('user_id')?$this->input->post('user_id'):$this->session->userdata('user_id');
				$data['subject'] = $this->input->post('subject');

				if($this->input->post('status')){
					$data['status'] = $this->input->post('status');
				}

				$id = $this->support_model->create($data);
				
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

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{

			if(empty($id)){
				$id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if(!empty($id) && $this->support_model->delete($id)){

				$this->support_model->delete_support_message($id);

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



}







