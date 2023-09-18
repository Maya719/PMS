<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function delete($expenses_id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{

			if(empty($expenses_id)){
				$expenses_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if(!empty($expenses_id) && $this->expenses_model->delete($expenses_id)){
				
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

	public function get_expenses_by_id()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->expenses_model->get_expenses_by_id($this->input->post('id'));
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

	public function get_expenses()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			return $this->expenses_model->get_expenses();
		}else{
			return '';
		}
	}

	public function edit()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('update_id', 'ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('date', 'Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('team_member_id', 'Team Member', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('client_id', 'Clint', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('project_id', 'Project', 'trim|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){

				if (!empty($_FILES['receipt']['name']) && is_storage_limit_exceeded()){
					$upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/receipt/';
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
					if ($this->upload->do_upload('receipt')){
						$data['receipt'] = $this->upload->data('file_name');
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}else{
					$data['receipt'] = $this->input->post('old_receipt');
				}

				$data['description'] = $this->input->post('description');
				$data['date'] = $this->input->post('date')?format_date($this->input->post('date'),"Y-m-d"):date("Y-m-d");
				$data['amount'] = $this->input->post('amount');
				$data['team_member_id'] = $this->input->post('team_member_id')?$this->input->post('team_member_id'):NULL;
				$data['client_id'] = $this->input->post('client_id')?$this->input->post('client_id'):NULL;
				$data['project_id'] = $this->input->post('project_id')?$this->input->post('project_id'):NULL;

				if($this->expenses_model->edit($data, $this->input->post('update_id'))){

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

	public function create()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('date', 'Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('team_member_id', 'Team Member', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('client_id', 'Clint', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('project_id', 'Project', 'trim|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				
				if (!empty($_FILES['receipt']['name']) && is_storage_limit_exceeded()){
					$upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/receipt/';
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
					if ($this->upload->do_upload('receipt')){
						$data['receipt'] = $this->upload->data('file_name');
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['description'] = $this->input->post('description');
				$data['date'] = $this->input->post('date')?format_date($this->input->post('date'),"Y-m-d"):date("Y-m-d");
				$data['amount'] = $this->input->post('amount');
				$data['team_member_id'] = $this->input->post('team_member_id')?$this->input->post('team_member_id'):NULL;
				$data['client_id'] = $this->input->post('client_id')?$this->input->post('client_id'):NULL;
				$data['project_id'] = $this->input->post('project_id')?$this->input->post('project_id'):NULL;

				$id = $this->expenses_model->create($data);
				
				if($id){

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

	public function index()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('expenses') && $this->ion_auth->is_admin())
		{
			$this->data['page_title'] = 'Expenses - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
            $this->data['system_clients'] = $this->ion_auth->users(4)->result();
			$this->data['projects'] = $this->projects_model->get_projects();
			$this->load->view('expenses',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}







