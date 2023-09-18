<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_live_notifications()
	{
		if ($this->ion_auth->logged_in())
		{
			$data = array();
			$data['notifications'] = get_notifications();
			$data['unread_msg_count'] = get_unread_msg_count();
			$data['unread_support_msg_count'] = get_unread_support_msg_count();

			if($data){
				$this->data['error'] = false;
				$this->data['data'] = $data;
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				echo json_encode($this->data);
			}
		}
	}

	public function delete_by_type($id = '', $type = '', $type_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if($this->notifications_model->delete($id, $type, $type_id)){
				return true;
			}else{
				return false;
			}

		}else{
			return false;
		}
	}

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->notifications_model->delete($id)){

				$this->session->set_flashdata('message', $this->lang->line('notification_deleted_successfully')?$this->lang->line('notification_deleted_successfully'):"Notification deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('notification_deleted_successfully')?$this->lang->line('notification_deleted_successfully'):"Notification deleted successfully.";
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

	public function get_notifications()
	{
		if ($this->ion_auth->logged_in())
		{
			$notifications = $this->notifications_model->get_notifications();
			if($notifications){
				foreach($notifications as $key => $notification){
					$temp[$key] = $notification;

					$extra = '';
					$notification_url = base_url('notifications');
					$notification_txt = $notification['notification'];
					if($notification['type'] == 'offline_request' && $this->ion_auth->in_group(3)){
						
						$notification_txt = $this->lang->line('offline_bank_transfer_request_created_for_subscription_plan')?$this->lang->line('offline_bank_transfer_request_created_for_subscription_plan')." ".$notification['notification']:"Offline / Bank Transfer request created for subscription plan ".$notification['notification'];
						$notification_url = base_url('plans/offline-requests');
						$plan = $this->plans_model->get_plans($notification['type_id']);
						if($plan){
							$extra = ($this->lang->line('plan')?$this->lang->line('plan'):'Plan').': <a target="_blank" href="'.base_url('plans').'">'.$plan[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'new_plan' && $this->ion_auth->in_group(3)){
						$notification_txt = $this->lang->line('ordered_subscription_plan')?$this->lang->line('ordered_subscription_plan')." ".$notification['notification']:"Ordered subscription plan ".$notification['notification'];
						$notification_url = base_url('plans/orders');
						$plan = $this->plans_model->get_plans($notification['type_id']);
						if($plan){
							$extra = ($this->lang->line('plan')?$this->lang->line('plan'):'Plan').': <a target="_blank" href="'.base_url('plans').'">'.$plan[0]['title'].'</a> ';
							$extra .= ($this->lang->line('transaction')?$this->lang->line('transaction'):'Transaction').': <a target="_blank" href="'.base_url('plans/transactions').'">$'.$plan[0]['price'].'</a> ';

							$user = $this->ion_auth->user($notification['from_id'])->row();
							if($user){
								$extra .= ($this->lang->line('user')?$this->lang->line('user'):'User').': <a target="_blank" href="'.base_url('users/saas').'">'.$user->first_name.' '.$user->last_name.'</a>';
							}

						}
					}elseif($notification['type'] == 'new_user' && $this->ion_auth->in_group(3)){
						$notification_txt = $this->lang->line('new_user_registered')?$this->lang->line('new_user_registered'):"New user registered.";
						$notification_url = base_url('users/saas');
						$user = $this->ion_auth->user($notification['type_id'])->row();
						if($user){
							$extra = ($this->lang->line('user')?$this->lang->line('user'):'User').': <a target="_blank" href="'.base_url('users/saas').'">'.$user->first_name.' '.$user->last_name.'</a>';
						}
					}elseif($notification['type'] == 'offline_request' && $this->ion_auth->is_admin()){
						$notification_txt = $this->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')?$this->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')." ".$notification['notification']:"Your Offline / Bank Transfer request accepted for subscription plan ".$notification['notification'];
						
						$notification_url = base_url('plans');
						$plan = $this->plans_model->get_plans($notification['type_id']);
						if($plan){
							$extra = ($this->lang->line('plan')?$this->lang->line('plan'):'Plan').': <a target="_blank" href="'.base_url('plans').'">'.$plan[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'new_project'){
						$notification_txt = $this->lang->line('new_project_created')?$notification['notification']." ".$this->lang->line('new_project_created'):$notification['notification']." new project created.";
						$notification_url = base_url('projects/detail/'.$notification['type_id']);
						$project = $this->projects_model->get_projects('',$notification['type_id']);
						if($project){
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.$notification_url.'">'.$project[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'project_status'){

						$old_status = project_status('',$notification['notification']);
						$project = $this->projects_model->get_projects('',$notification['type_id']);
						if($old_status && $project){
							$notification_txt = $this->lang->line('project_status_changed')?$this->lang->line('project_status_changed').' <span class="text-info text-strike">'.$old_status[0]['title'].'</span> = <span class="text-info">'.$project[0]['project_status'].'</span>':'Project status changed. <span class="text-info text-strike">'.$old_status[0]['title'].'</span> = <span class="text-info">'.$project[0]['project_status'].'</span>';
						}
		
						$notification_url = base_url('projects/detail/'.$notification['type_id']);
						if($project){
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.$notification_url.'">'.$project[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'project_file'){
						$notification_txt = $this->lang->line('project_file_uploaded')?$notification['notification']." ".$this->lang->line('project_file_uploaded'):$notification['notification']." project file uploaded.";
						$notification_url = base_url('projects/detail/'.$notification['type_id']);
						$project = $this->projects_model->get_projects('',$notification['type_id']);
						if($project){
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.$notification_url.'">'.$project[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'new_task'){
						$notification_txt = $this->lang->line('task_assigned_you')?$notification['notification']." ".$this->lang->line('task_assigned_you'):$notification['notification']." task assigned you.";
						$task = $this->projects_model->get_tasks('',$notification['type_id']);
						if($task){
							$notification_url = base_url('projects/tasks/'.$task[0]['project_id']);
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.base_url('projects/detail/'.$task[0]['project_id']).'">'.$task[0]['project_title'].'</a> ';
							$extra .= ($this->lang->line('task')?$this->lang->line('task'):'Task').': <a target="_blank" href="'.base_url('projects/tasks/'.$task[0]['project_id']).'">'.$task[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'task_status'){

						$task_status_old = task_status($notification['notification']);
						$task = $this->projects_model->get_tasks('',$notification['type_id']);

						if($task_status_old && $task){
							$notification_txt = $this->lang->line('task_status_changed')?($this->lang->line('task_status_changed').' <span class="text-info text-strike">'.$task_status_old[0]['title'].'</span> = <span class="text-info">'.$task[0]['task_status'].'</span>'):('Task status changed. <span class="text-info text-strike">'.$task_status_old[0]['title'].'</span> = <span class="text-info">'.$task[0]['task_status'].'</span>');
						}

						if($task){
							$notification_url = base_url('projects/tasks/'.$task[0]['project_id']);
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.base_url('projects/detail/'.$task[0]['project_id']).'">'.$task[0]['project_title'].'</a> ';
							$extra .= ($this->lang->line('task')?$this->lang->line('task'):'Task').': <a target="_blank" href="'.base_url('projects/tasks/'.$task[0]['project_id']).'">'.$task[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'task_file'){
						$notification_txt = $this->lang->line('task_file_uploaded')?$notification['notification']." ".$this->lang->line('task_file_uploaded'):$notification['notification']." task file uploaded.";
						$task = $this->projects_model->get_tasks('',$notification['type_id']);
						if($task){
							$notification_url = base_url('projects/tasks/'.$task[0]['project_id']);
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.base_url('projects/detail/'.$task[0]['project_id']).'">'.$task[0]['project_title'].'</a> ';
							$extra .= ($this->lang->line('task')?$this->lang->line('task'):'Task').': <a target="_blank" href="'.base_url('projects/tasks/'.$task[0]['project_id']).'">'.$task[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'task_comment'){
						$notification_txt = $this->lang->line('new_task_comment')?$this->lang->line('new_task_comment')." ".$notification['notification']:"New task comment ".$notification['notification'];
						$task = $this->projects_model->get_tasks('',$notification['type_id']);
						if($task){
							$notification_url = base_url('projects/tasks/'.$task[0]['project_id']);
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.base_url('projects/detail/'.$task[0]['project_id']).'">'.$task[0]['project_title'].'</a> ';
							$extra .= ($this->lang->line('task')?$this->lang->line('task'):'Task').': <a target="_blank" href="'.base_url('projects/tasks/'.$task[0]['project_id']).'">'.$task[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'project_comment'){
						$notification_txt = $this->lang->line('new_project_comment')?$this->lang->line('new_project_comment')." ".$notification['notification']:"New project comment ".$notification['notification'];
						$notification_url = base_url('projects/detail/'.$notification['type_id']);
						$project = $this->projects_model->get_projects('',$notification['type_id']);
						if($project){
							$extra = ($this->lang->line('project')?$this->lang->line('project'):'Project').': <a target="_blank" href="'.$notification_url.'">'.$project[0]['title'].'</a>';
						}
					}elseif($notification['type'] == 'new_invoice'){
						$invoice = $this->invoices_model->get_invoices($notification['type_id']);
						if($invoice){
							$invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
							$notification_txt = $this->lang->line('new_invoice_received')?$invoice_id." ".$this->lang->line('new_invoice_received'):$invoice_id." new invoice received.";
							$notification_url = base_url('invoices/view/'.$invoice[0]['id']);
						}
					}elseif($notification['type'] == 'bank_transfer'){
						$invoice = $this->invoices_model->get_invoices($notification['type_id']);
						if($invoice){
							$invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
							$notification_txt = $this->lang->line('bank_transfer_request_received_for_the_invoice')?$this->lang->line('bank_transfer_request_received_for_the_invoice')." ".$invoice_id:"Bank transfer request received for the invoice ".$invoice_id;
							$notification_url = base_url('invoices/view/'.$invoice[0]['id']);
						}
					}elseif($notification['type'] == 'bank_transfer_accept'){
						$invoice = $this->invoices_model->get_invoices($notification['type_id']);
						if($invoice){
							$invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
							$notification_txt = $this->lang->line('bank_transfer_request_accepted_for_the_invoice')?$this->lang->line('bank_transfer_request_accepted_for_the_invoice')." ".$invoice_id:"Bank transfer request accepted for the invoice ".$invoice_id;
							$notification_url = base_url('invoices/view/'.$invoice[0]['id']);
						}
					}elseif($notification['type'] == 'bank_transfer_reject'){
						$invoice = $this->invoices_model->get_invoices($notification['type_id']);
						if($invoice){
							$invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
							$notification_txt = $this->lang->line('bank_transfer_request_rejected_for_the_invoice')?$this->lang->line('bank_transfer_request_rejected_for_the_invoice')." ".$invoice_id:"Bank transfer request rejected for the invoice ".$invoice_id;
							$notification_url = base_url('invoices/view/'.$invoice[0]['id']);
						}
					}elseif($notification['type'] == 'payment_received'){
						$invoice = $this->invoices_model->get_invoices($notification['type_id']);
						if($invoice){
							$invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
							$notification_txt = $this->lang->line('payment_received_for_the_invoice')?$this->lang->line('payment_received_for_the_invoice')." ".$invoice_id:"Payment received for the invoice ".$invoice_id;
							$notification_url = base_url('invoices/view/'.$invoice[0]['id']);
						}
					}elseif($notification['type'] == 'new_estimate'){
						$estimates = $this->estimates_model->get_estimates($notification['type_id']); 
						if($estimates){
							$title = '<span class="text-info">'.$notification['notification'].'</span>';
							$notification_txt = $this->lang->line('new_estimate_received')?$title." ".$this->lang->line('new_estimate_received'):$title." new estimate received.";
							$notification_url = base_url('estimates/view/'.$notification['type_id']);
						}
					}elseif($notification['type'] == 'estimate_reject'){
						$estimates = $this->estimates_model->get_estimates($notification['type_id']); 
						if($estimates){
							$title = '<span class="text-info">'.$notification['notification'].'</span>';
							$notification_txt = $this->lang->line('estimate_rejected')?$title." ".$this->lang->line('estimate_rejected'):$title." estimate rejected.";
							$notification_url = base_url('estimates/view/'.$notification['type_id']);
						}
					}elseif($notification['type'] == 'estimate_accept'){
						$estimates = $this->estimates_model->get_estimates($notification['type_id']); 
						if($estimates){
							$title = '<span class="text-info">'.$notification['notification'].'</span>';
							$notification_txt = $this->lang->line('estimate_accepted')?$title." ".$this->lang->line('estimate_accepted'):$title." estimate accepted.";
							$notification_url = base_url('estimates/view/'.$notification['type_id']);
						}
					}elseif($notification['type'] == 'new_meeting'){
						$meetings = $this->meetings_model->get_meetings($notification['type_id']); 
						if($meetings){
							$title = '<span class="text-info">'.$notification['notification'].'</span>';
							$notification_txt = $this->lang->line('new_meeting_created')?$title." ".$this->lang->line('new_meeting_created'):$title." new meeting scheduled.";
							$notification_url = base_url('meetings/view/'.$notification['type_id']);
						}
					}elseif($notification['type'] == 'leave_request'){
						$leave = $this->leaves_model->get_leaves_by_id($notification['type_id']);
						if($leave){
							$notification_txt = $this->lang->line('leave_request_received')?htmlspecialchars($this->lang->line('leave_request_received')):'Leave request received';
							$notification_url = base_url('leaves');
						}
					}elseif($notification['type'] == 'leave_request_accepted'){
						$leave = $this->leaves_model->get_leaves_by_id($notification['type_id']);
						if($leave){
							$notification_txt = $this->lang->line('leave_request_accepted')?htmlspecialchars($this->lang->line('leave_request_accepted')):'Leave request accepted';
							$notification_url = base_url('leaves');
						}
					}elseif($notification['type'] == 'leave_request_rejected'){
						$leave = $this->leaves_model->get_leaves_by_id($notification['type_id']);
						if($leave){
							$notification_txt = $this->lang->line('leave_request_rejected')?htmlspecialchars($this->lang->line('leave_request_rejected')):'Leave request rejected';
							$notification_url = base_url('leaves');
						}
					}elseif($notification['type'] == 'new_lead'){
						$leads = $this->leads_model->get_leads_by_id($notification['type_id']);
						if($leads){
						$title = '<span class="text-info">'.$notification['notification'].'</span>';
						$notification_txt = $this->lang->line('new_lead_assigned_to_you')?$title." ".$this->lang->line('new_lead_assigned_to_you'):$title." New lead assigned to you.";
						$notification_url = base_url('leads');
						}
					}
					
					$temp[$key]['notification'] = '<a target="_blank" href="'.$notification_url.'"><b>'.$notification_txt.'</b></a><br>'.$extra;
					
					$temp[$key]['first_name'] = $notification['first_name']." ".$notification['last_name'];
					$temp[$key]['is_read'] = $notification['is_read']==1?'<div class="badge badge-success">Yes</div>':'<div class="badge badge-danger">No</div>';

					$temp[$key]['action'] = '<span class="d-flex"><a href="'.$notification_url.'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View').'"><i class="fas fa-eye"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger delete_notification" data-id="'.$notification["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';

					$temp[$key]['created'] = format_date($notification['created'],system_date_format());
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->notifications_model->edit('', '', '', '', '');
			$this->data['page_title'] = 'Notifications - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('notifications',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}
