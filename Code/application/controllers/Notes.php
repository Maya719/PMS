<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notes extends CI_Controller
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
			
			if(!empty($id) && is_numeric($id) && $this->notes_model->delete($this->session->userdata('user_id'), $id)){

				$this->session->set_flashdata('message', $this->lang->line('note_deleted_successfully')?$this->lang->line('note_deleted_successfully'):"Note deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('note_deleted_successfully')?$this->lang->line('note_deleted_successfully'):"Note deleted successfully.";
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
			$this->form_validation->set_rules('update_id', 'Note ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'description' => $this->input->post('description'),	
				);

				if($this->notes_model->edit($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('note_updated_successfully')?$this->lang->line('note_updated_successfully'):"Note updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('note_updated_successfully')?$this->lang->line('note_updated_successfully'):"Note updated successfully.";
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

	public function get_notes()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('id', 'Note ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if($this->form_validation->run() == TRUE){
				$this->data['error'] = false;
				$this->data['data'] = $this->notes_model->get_notes($this->session->userdata('user_id'),$this->input->post('id'));
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

	public function create()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'user_id' => $this->session->userdata('user_id'),
					'description' => $this->input->post('description'),	
				);

				$note_id = $this->notes_model->create($data);
				
				if($note_id){
					$this->session->set_flashdata('message', $this->lang->line('note_created_successfully')?$this->lang->line('note_created_successfully'):"Note created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('note_created_successfully')?$this->lang->line('note_created_successfully'):"Note created successfully.";
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
		if ($this->ion_auth->logged_in() && is_module_allowed('notes') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('notes_view')))
		{
			$this->data['page_title'] = 'Notes - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['notes'] = $this->notes_model->get_notes($this->session->userdata('user_id'));
			$this->load->view('notes',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}
