<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Meetings extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('meetings') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('meetings_view')))
		{
			$this->data['page_title'] = 'Video Meetings - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2,4))->result();
			$this->notifications_model->edit('', 'new_estimate', '', '', '');
			$this->load->view('meetings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function view()
	{
		if ($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && $this->ion_auth->logged_in() && is_module_allowed('meetings') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('meetings_view')))
		{
			$meetings = $this->meetings_model->get_meetings($this->uri->segment(3));
			if(!$meetings){
				redirect('meetings', 'refresh');
			}
			if($this->ion_auth->is_admin() || $meetings[0]['created_by'] == $this->session->userdata('user_id')){
				$data = array(	
					'status' => 1
				);
				$this->meetings_model->edit($this->uri->segment(3), $data);
			}
			$this->data['page_title'] = $meetings[0]['title'].' - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$meeting = $this->meetings_model->get_meetings($this->uri->segment(3));
			$this->data['meeting'] = $meeting;
			if($meeting[0]['status'] != 1 && (!$this->ion_auth->is_admin() || $meetings[0]['created_by'] != $this->session->userdata('user_id'))){
				$this->load->view('meetings-not-started',$this->data);
			}else{
				$this->load->view('meetings-view',$this->data);
			}
		}else{
			redirect('meetings', 'refresh');
		}
	}

	public function end()
	{
		if ($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && $this->ion_auth->logged_in() && is_module_allowed('meetings') && !$this->ion_auth->in_group(3))
		{
			$meetings = $this->meetings_model->get_meetings($this->uri->segment(3));
			if(!$meetings){
				redirect('meetings', 'refresh');
			}
			if($this->ion_auth->is_admin() || $meetings[0]['created_by'] == $this->session->userdata('user_id')){
				$data = array(	
					'status' => 2
				);
				$this->meetings_model->edit($this->uri->segment(3), $data);
			}
			redirect('meetings', 'refresh');
		}else{
			redirect('meetings', 'refresh');
		}
	}

	public function create()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('meetings') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('meetings_create')))
		{
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date_and_time', 'Starting Time', 'required|xss_clean');
			$this->form_validation->set_rules('duration', 'Duration', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('users[]', 'Users', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),
					'created_by' => $this->session->userdata('user_id'),	
					'title' => preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('title')),	
					'starting_date_and_time' => format_date($this->input->post('starting_date_and_time'),"Y-m-d H:i:s"),	
					'duration' => $this->input->post('duration'),	
					'users' => implode(',', $this->input->post('users')),
				);

				$id = $this->meetings_model->create($data);
				
				if($id){
					if($this->input->post('users')){
						
						$template_data = array();
						$template_data['MEETING_TITLE'] = preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('title'));
						$template_data['STARTING_DATE_AND_TIME'] = format_date($this->input->post('starting_date_and_time'),"Y-m-d H:i:s");
						$template_data['DURATION'] = $this->input->post('duration');
						$template_data['MEETING_URL'] = base_url('meetings');
						$email_template = render_email_template('new_meeting', $template_data);

						if(is_numeric($this->input->post('users'))){
							$data = array(
								'notification' => preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('title')),
								'type' => 'new_meeting',	
								'type_id' => $id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $this->input->post('users'),	
							);
							$notification_id = $this->notifications_model->create($data);
							if($this->input->post('send_email_notification')){
								$to_user = $this->ion_auth->user($this->input->post('users'))->row();
								send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
							}
						}else{
							foreach($this->input->post('users') as $user_id){
								$data = array(
									'notification' => preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('title')),
									'type' => 'new_meeting',	
									'type_id' => $id,	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $user_id,	
								);
								$notification_id = $this->notifications_model->create($data);
								if($this->input->post('send_email_notification')){
									$to_user = $this->ion_auth->user($user_id)->row();
									send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
								}
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

	public function edit()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('meetings') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('meetings_edit')))
		{
			$this->form_validation->set_rules('update_id', 'Update ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date_and_time', 'Starting Time', 'required|xss_clean');
			$this->form_validation->set_rules('duration', 'Duration', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('users[]', 'Users', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$data = array(	
					'title' => preg_replace("/[^A-Za-z0-9 ]/", '', $this->input->post('title')),	
					'starting_date_and_time' => format_date($this->input->post('starting_date_and_time'),"Y-m-d H:i:s"),	
					'duration' => $this->input->post('duration'),	
					'users' => implode(',', $this->input->post('users')),
					'status' => $this->input->post('status'),
				);

				if($this->meetings_model->edit($this->input->post('update_id'), $data)){

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
		if ($this->ion_auth->logged_in() && is_module_allowed('meetings') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('meetings_delete')))
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			if(!empty($id) && is_numeric($id) && $this->meetings_model->delete($id)){

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

	public function ajax_get_meetings_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$meetings = $this->meetings_model->get_meetings($id);
			if(!empty($meetings)){
				$this->data['error'] = false;
				$this->data['data'] = $meetings;
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

	public function get_meetings($meeting_id = '')
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('meetings') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('meetings_view')))
		{
            $meetings = $this->meetings_model->get_meetings($meeting_id);
			if($meetings){
				foreach($meetings as $key => $meeting){
					$temp[$key] = $meeting;
					$temp[$key]['title'] = '<strong>'.$meeting['title'].'</strong>';

					$temp[$key]['starting_date_and_time'] = format_date($meeting['starting_date_and_time'],system_date_format()." ".system_time_format());

					$action = '';

					if($meeting['status'] == 0){
						$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('scheduled')?$this->lang->line('scheduled'):'Scheduled').'</div>';
						if($this->ion_auth->is_admin() || $meeting['created_by'] == $this->session->userdata('user_id')){
							$action .= '<a href="'.(base_url('meetings/view/'.$meeting['id'])).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('start')?htmlspecialchars($this->lang->line('start')):'Start').'" >'.($this->lang->line('start')?htmlspecialchars($this->lang->line('start')):'Start').'</a>';
						}else{
							$action .= '<a href="'.(base_url('meetings/view/'.$meeting['id'])).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('join')?htmlspecialchars($this->lang->line('join')):'Join').'" >'.($this->lang->line('join')?htmlspecialchars($this->lang->line('join')):'Join').'</a>';
						}
					}elseif($meeting['status'] == 1){
						$temp[$key]['status'] = '<div class="badge badge-warning">'.($this->lang->line('running')?$this->lang->line('running'):'Running').'</div>';
						$action .= '<a href="'.(base_url('meetings/view/'.$meeting['id'])).'" class="btn btn-icon btn-sm btn-warning mr-1" data-toggle="tooltip" title="'.($this->lang->line('join')?htmlspecialchars($this->lang->line('join')):'Join').'" >'.($this->lang->line('join')?htmlspecialchars($this->lang->line('join')):'Join').'</a>';
						if($this->ion_auth->is_admin() || $meeting['created_by'] == $this->session->userdata('user_id')){
							$action .= '<a href="'.(base_url('meetings/end/'.$meeting['id'])).'" class="btn btn-icon btn-sm btn-danger mr-1" data-toggle="tooltip" title="'.($this->lang->line('stop')?htmlspecialchars($this->lang->line('stop')):'Stop').'">'.($this->lang->line('stop')?htmlspecialchars($this->lang->line('stop')):'Stop').'</a>';
						}
					}elseif($meeting['status'] == 2){
						$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('completed')?$this->lang->line('completed'):'Completed').'</div>';
						if($this->ion_auth->is_admin() || $meeting['created_by'] == $this->session->userdata('user_id')){
							$action .= '<a href="'.(base_url('meetings/view/'.$meeting['id'])).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('start')?htmlspecialchars($this->lang->line('start')):'Start').'" >'.($this->lang->line('start')?htmlspecialchars($this->lang->line('start')):'Start').'</a>';
						}else{
							$action .= '<a href="'.(base_url('meetings/view/'.$meeting['id'])).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('join')?htmlspecialchars($this->lang->line('join')):'Join').'" >'.($this->lang->line('join')?htmlspecialchars($this->lang->line('join')):'Join').'</a>';
						}
					}

					if($this->ion_auth->is_admin() || permissions('meetings_edit')){
						$action .= '<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-meetings mr-1" data-id="'.$meeting["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>';
					}

					if($this->ion_auth->is_admin() || permissions('meetings_delete')){
						$action .= '<a href="#" class="btn btn-icon btn-sm btn-danger delete_meeting" data-id="'.$meeting["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>';
					}

					$temp[$key]['action'] = '<span class="d-flex">'.$action.'</span>';
							
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

}