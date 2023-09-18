<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday extends CI_Controller
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
			$this->data['page_title'] = 'Holiday - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$query3 = $this->db->get('departments');
        	$this->data['departments'] = $query3->result_array();
			// $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$this->load->view('holiday',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
    
	public function create()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_date', 'Ending Date', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|strip_tags|xss_clean');
			$apply = $this->input->post('applyforcreate');
			if ($apply == '0') {
				$apply = '0';
				$departments = json_encode(array());
				$users = json_encode(array());
			}elseif($apply == '1') {
				$apply = '1';
				$departments = $this->input->post('department');
				$departments=json_encode($departments);
				$users= json_encode(array());
			}else{
				$apply = '2';
				$departments = json_encode(array());
				$users = $this->input->post('users');
				$users=json_encode($users);
			}
			if($this->form_validation->run() == TRUE){
				$data = array(
					'apply' => $apply,
					'department' => $departments,
					'users' => $users,
					'remarks' => $this->input->post('remarks'),
					'type' => $this->input->post('type_add'),
				);
				if ($data['type'] == '2') {
					$currentDate = date('Y-m-d');
					if ($this->input->post('sat') && $this->input->post('sun')) {
						$sat = $this->input->post('sat');
						$sun = $this->input->post('sun');
						$data['starting_date'] = date('Y-m-d', strtotime('next Saturday', strtotime($currentDate)));
						$data['ending_date'] = date('Y-m-d', strtotime('next Sunday', strtotime($currentDate)));
					}elseif ($this->input->post('sat')) {
						$data['starting_date'] = date('Y-m-d', strtotime('next Saturday', strtotime($currentDate)));
						$data['ending_date'] = $data['starting_date'];
					}else{
						$data['ending_date'] = date('Y-m-d', strtotime('next Sunday', strtotime($currentDate)));
						$data['starting_date'] = $data['ending_date'];
					}
				}else{
					$starting_date = $this->input->post('starting_date');
					$ending_date = $this->input->post('ending_date');
					$data['starting_date'] = date("Y-m-d", strtotime($starting_date));
					$data['ending_date'] = date("Y-m-d", strtotime($ending_date));
				}
					
					$data['holiday_duration'] = 1 + round(abs(strtotime($this->input->post('ending_date')) - strtotime($this->input->post('starting_date')))/86400) . " Full Day/s";

				$id = $this->holiday_model->create($data);
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

	public function get_holiday()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			return $this->holiday_model->get_holiday();
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
			$this->form_validation->set_rules('update_id', 'Holiday ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
                    $data['remarks'] = $this->input->post('remarks');
                    $data['type'] = $this->input->post('type');
					$apply = $this->input->post('applyforedit');
					if ($apply == '0') {
						$data["apply"] = '0';
						$data["department"] = json_encode(array());
						$data["users"] = json_encode(array());
					}elseif($apply == '1') {
						$data["apply"] = '1';
						$departments = $this->input->post('department');
						$data["department"]=json_encode($departments);
						$data["users"]= json_encode(array());
					}else{
						$data["apply"]= '2';
						$data["department"] = json_encode(array());
						$users = $this->input->post('users');
						$data["users"] = json_encode($users);
					}

					if ($data['type'] == '2') {
						$currentDate = date('Y-m-d');
						if ($this->input->post('sat') && $this->input->post('sun')) {
							$sat = $this->input->post('sat');
							$sun = $this->input->post('sun');
							$data['starting_date'] = date('Y-m-d', strtotime('next Saturday', strtotime($currentDate)));
							$data['ending_date'] = date('Y-m-d', strtotime('next Sunday', strtotime($currentDate)));
						}elseif ($this->input->post('sat')) {
							$data['starting_date'] = date('Y-m-d', strtotime('next Saturday', strtotime($currentDate)));
							$data['ending_date'] = $data['starting_date'];
						}else{
							$data['ending_date'] = date('Y-m-d', strtotime('next Sunday', strtotime($currentDate)));
							$data['starting_date'] = $data['ending_date'];
						}
					}else{
						$starting_date = $this->input->post('starting_date');
						$ending_date = $this->input->post('ending_date');
						$data['starting_date'] = date("Y-m-d", strtotime($starting_date));
						$data['ending_date'] = date("Y-m-d", strtotime($ending_date));
					}
					$data['holiday_duration'] = 1 + round(abs(strtotime($this->input->post('ending_date')) - strtotime($this->input->post('starting_date')))/86400) . " Full Day/s";
					
				if($this->holiday_model->edit($this->input->post('update_id'), $data)){
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
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{

			if(empty($id)){
				$id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->holiday_model->delete($id)){
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
    public function get_holiday_by_id()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->holiday_model->get_leaves_by_id($this->input->post('id'));
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
