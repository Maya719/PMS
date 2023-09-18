<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('estimates'))
		{

			if(empty($id)){
				$id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->products_model->delete($id)){

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

	public function edit()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('estimates'))
		{
			$this->form_validation->set_rules('price', 'Product ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('name', 'Product Reason', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				
				$data = array(
					'name' => $this->input->post('name'),	
					'price' => $this->input->post('price'),
				);

				if($this->products_model->edit($this->input->post('update_id'), $data)){
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

	public function get_products_by_id()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('estimates'))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->products_model->get_products_by_id($this->input->post('id'));
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

	public function get_products()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('estimates'))
		{
			return $this->products_model->get_products();
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function create()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('estimates'))
		{
			$this->form_validation->set_rules('price', 'Product Days', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('name', 'Starting Date', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),
					'name' => $this->input->post('name'),	
					'price' => $this->input->post('price'),
				);

				$id = $this->products_model->create($data);
				
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
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('estimates'))
		{
			$this->data['page_title'] = 'Products - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->load->view('products',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}
