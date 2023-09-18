<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Plans extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function create_session($plan_id = '')
	{	
		$stripeSecret = get_stripe_secret_key();
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && $stripeSecret)
		{
			if(empty($plan_id)){
				$plan_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			if(!empty($plan_id) || is_numeric($plan_id)){
				$plan = $this->plans_model->get_plans($plan_id);
				if($plan){
					require_once('vendor/stripe/stripe-php/init.php');
					
					\Stripe\Stripe::setApiKey($stripeSecret);
					$session = \Stripe\Checkout\Session::create([
						'payment_method_types' => ['card'],
						'line_items' => [[
						'price_data' => [
							'currency' => get_saas_currency('currency_code'),
							'product_data' => [
							'name' => $plan[0]['title'],
							],
							'unit_amount' => $plan[0]['price']*100,
						],
						'quantity' => 1,
						]],
						'metadata' => [
							'plan_id' => $plan_id,
						],
						'mode' => 'payment',
						'success_url' => base_url().'plans/soc?sid={CHECKOUT_SESSION_ID}',
						'cancel_url' => base_url().'plans/soc?sid={CHECKOUT_SESSION_ID}',
					]);
					$data = array('id' => $session->id, 'data' => $session);
					echo json_encode($data);
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
			echo json_encode($this->data);
		}
	}

	public function index()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			if ($this->ion_auth->is_admin()){
				$this->notifications_model->edit('', 'offline_request', '', '', '');
			}
			$this->data['page_title'] = 'Subscription Plans - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['plans'] = $this->plans_model->get_plans();
			$this->load->view('plans',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function soc()
	{	
		$stripeSecret = get_stripe_secret_key();
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && $stripeSecret)
		{
			if(isset($_GET['sid']) && $_GET['sid'] != ''){
				require_once('vendor/stripe/stripe-php/init.php');
				$stripe = new \Stripe\StripeClient($stripeSecret);
				try{
					$payment_details = $stripe->checkout->sessions->retrieve($_GET['sid']);
					if($payment_details->payment_status == 'paid'){
						$plan = $this->plans_model->get_plans($payment_details->metadata->plan_id);
						if($plan){
							if($plan[0]['price'] > 0){
								$transaction_data = array(
									'saas_id' => $this->session->userdata('saas_id'),			
									'amount' => $plan[0]['price'],		
									'status' => 1,		
								);

								$transaction_id = $this->plans_model->create_transaction($transaction_data);

								$order_data = array(
									'saas_id' => $this->session->userdata('saas_id'),		
									'plan_id' => $payment_details->metadata->plan_id,		
									'transaction_id' => $transaction_id,			
								);
								$order_id = $this->plans_model->create_order($order_data);
							}
							
							$dt = strtotime(date("Y-m-d"));
							if($plan[0]['billing_type'] == "One Time"){
								$date = NULL;
							}elseif($plan[0]['billing_type'] == "Monthly"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}elseif($plan[0]['billing_type'] == "Yearly"){
								$date = date("Y-m-d", strtotime("+1 year", $dt));
							}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+7 days", $dt));
							}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+15 days", $dt));
							}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}else{
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}

							$my_plan = get_current_plan();
							if($my_plan){
								if($my_plan['expired'] == 1){
									if($my_plan['plan_id'] == 1 && $my_plan['plan_id'] == $payment_details->metadata->plan_id){
										$date = date("Y-m-d", strtotime("+3 days", $dt));
										if($plan[0]['billing_type'] == "One Time"){
											$date = NULL;
										}
									}else{
										if(empty($my_plan['end_date'])){
											$dt = strtotime(date("Y-m-d"));
										}else{
											$dt = strtotime($my_plan['end_date']);
										}

										if($plan[0]['billing_type'] == "One Time"){
											$date = NULL;
										}elseif($plan[0]['billing_type'] == "Monthly"){
											$date = date("Y-m-d", strtotime("+1 month", $dt));
										}elseif($plan[0]['billing_type'] == "Yearly"){
											$date = date("Y-m-d", strtotime("+1 year", $dt));
										}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+3 days", $dt));
										}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+7 days", $dt));
										}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+15 days", $dt));
										}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+1 month", $dt));
										}else{
											$date = date("Y-m-d", strtotime("+3 days", $dt));
										}
									}
								}
								$users_plans_data = array(
									'plan_id' => $payment_details->metadata->plan_id,		
									'expired' => 1,		
									'start_date' => date("Y-m-d"),			
									'end_date' => $date,			
								);
								$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
							}else{
								$users_plans_data = array(	
									'expired' => 1,				
									'plan_id' => $payment_details->metadata->plan_id,		
									'start_date' => date("Y-m-d"),			
									'end_date' => $date,			
								);
								$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
							}
							
							if($users_plans_id){

								// notification to the saas admins
								$saas_admins = $this->ion_auth->users(array(3))->result();
								foreach($saas_admins as $saas_admin){
									$data = array(
										'notification' => '<span class="text-info">'.$plan[0]['title'].'</span>',
										'type' => 'new_plan',	
										'type_id' => $payment_details->metadata->plan_id,	
										'from_id' => $this->session->userdata('saas_id'),
										'to_id' => $saas_admin->user_id,	
									);
									$notification_id = $this->notifications_model->create($data);
								}

								$this->session->set_flashdata('message', $this->lang->line('plan_subscribed_successfully')?$this->lang->line('plan_subscribed_successfully'):"Plan subscribed successfully.");
								$this->session->set_flashdata('message_type', 'success');
							}else{
								$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
								$this->session->set_flashdata('message_type', 'success');
							}
						}else{
							$this->session->set_flashdata('message', $this->lang->line('choose_valid_subscription_plan')?$this->lang->line('choose_valid_subscription_plan'):"Choose valid subscription plan.");
							$this->session->set_flashdata('message_type', 'success');
						}
					}else{
						$this->session->set_flashdata('message', $this->lang->line('payment_unsuccessful_please_try_again_later')?$this->lang->line('payment_unsuccessful_please_try_again_later'):"Payment unsuccessful. Please Try again later.");
						$this->session->set_flashdata('message_type', 'success');
					}
				}catch(Exception $e){
					$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
					$this->session->set_flashdata('message_type', 'success');
				}
			}else{
				$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
				$this->session->set_flashdata('message_type', 'success');
			}
			redirect('plans', 'refresh');
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function orders()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->notifications_model->edit('', 'new_plan', '', '', '');
			$this->data['page_title'] = 'Subscription Orders - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('orders',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function transactions()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Transactions - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('transactions',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	public function get_transactions($transaction_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$transactions = $this->plans_model->get_transactions($transaction_id);
			if($transactions){
				foreach($transactions as $key => $transaction){
					$temp[$key] = $transaction;
					$temp[$key]['user'] = $transaction['first_name']." ".$transaction['last_name'];
					$temp[$key]['created'] = format_date($transaction['created'],system_date_format());

					if($transaction['status']==1){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('completed')?$this->lang->line('completed'):'Completed').'</div>';
					}else{
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
					}
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_orders($order_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$orders = $this->plans_model->get_orders($order_id);
			if($orders){
				foreach($orders as $key => $order){
					$temp[$key] = $order;
					$temp[$key]['user'] = $order['first_name']." ".$order['last_name'];

					if($order["billing_type"] == 'One Time'){
						$temp[$key]['billing_type'] = $this->lang->line('one_time')?$this->lang->line('one_time'):'One Time';
					}elseif($order["billing_type"] == 'Monthly'){
						$temp[$key]['billing_type'] = $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
					}elseif($order["billing_type"] == 'three_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
					}elseif($order["billing_type"] == 'seven_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
					}elseif($order["billing_type"] == 'fifteen_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
					}elseif($order["billing_type"] == 'thirty_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
					}else{
						$temp[$key]['billing_type'] = $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
					}

					if($order['status']==1){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('completed')?$this->lang->line('completed'):'Completed').'</div>';
					}else{
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
					}

					$temp[$key]['created'] = format_date($order['created'],system_date_format());
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_offline_requests($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$offline_requests = $this->plans_model->get_offline_requests($id);
			if($offline_requests){
				foreach($offline_requests as $key => $offline_request){
					$temp[$key] = $offline_request;
					$temp[$key]['user'] = $offline_request['first_name']." ".$offline_request['last_name'];

					if($offline_request["billing_type"] == 'One Time'){
						$billing_type = $this->lang->line('one_time')?$this->lang->line('one_time'):'One Time';
					}elseif($offline_request["billing_type"] == 'Monthly'){
						$billing_type = $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
					}elseif($offline_request["billing_type"] == 'three_days_trial_plan'){
						$billing_type = $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
					}elseif($offline_request["billing_type"] == 'seven_days_trial_plan'){
						$billing_type = $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
					}elseif($offline_request["billing_type"] == 'fifteen_days_trial_plan'){
						$billing_type = $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
					}elseif($offline_request["billing_type"] == 'thirty_days_trial_plan'){
						$billing_type = $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
					}else{
						$billing_type = $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
					}

					$temp[$key]['title'] = '<b>'.$offline_request['title']."</b><br><b>".($this->lang->line('billing_type')?htmlspecialchars($this->lang->line('billing_type')):'Billing Type').':</b> '.$billing_type."<br><b>".($this->lang->line('price_usd')?$this->lang->line('price_usd'):'Price').':</b> '.get_saas_currency('currency_sy').' '.$offline_request['price'];

					$temp[$key]['created'] = format_date($offline_request['created'],system_date_format());
					
					if($offline_request['receipt']){
						$file_upload_path = '';
						if(file_exists('assets/uploads/receipt/'.$offline_request['receipt'])){
							$file_upload_path = 'assets/uploads/receipt/'.$offline_request['receipt'];
						}
						$temp[$key]['receipt'] = '<span class="d-flex"><a target="_blank" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View').'"><i class="fas fa-eye"></i></a><a download="'.$offline_request['receipt'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a></span>';
					}

					if($offline_request['status']==0){
						$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('pending')?$this->lang->line('pending'):'Pending').'</div>';
						$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-success mr-1 accept_request" data-id="'.$offline_request["id"].'" data-plan_id="'.$offline_request["plan_id"].'" data-saas_id="'.$offline_request["saas_id"].'" data-toggle="tooltip" title="'.($this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept').'"><i class="fas fa-check"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger reject_request" data-id="'.$offline_request["id"].'" data-plan_id="'.$offline_request["plan_id"].'" data-toggle="tooltip" title="'.($this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject').'"><i class="fas fa-times"></i></a></span>';
					}elseif($offline_request['status']==1){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('accepted')?$this->lang->line('accepted'):'Accepted').'</div>';
						$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="disabled btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept').'"><i class="fas fa-check"></i></a><a href="#" class="disabled btn btn-icon btn-sm btn-danger" data-toggle="tooltip" title="'.($this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject').'"><i class="fas fa-times"></i></a></span>';
					}else{
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
						$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="disabled btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept').'"><i class="fas fa-check"></i></a><a href="#" class="disabled btn btn-icon btn-sm btn-danger" data-toggle="tooltip" title="'.($this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject').'"><i class="fas fa-times"></i></a></span>';
					}
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function offline_requests()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->notifications_model->edit('', 'offline_request', '', '', '');
			$this->data['page_title'] = 'Offline Requests - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('offline_requests',$this->data);

		}else{
			redirect('auth', 'refresh'); 
		}
		
	}

	public function reject_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('id', 'Request ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$data = array(
					'status' => 2,			
				);
				if($this->plans_model->accept_reject_request($this->input->post('id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('offline_request_rejected_successfully')?$this->lang->line('offline_request_rejected_successfully'):"Offline request rejected successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('offline_request_rejected_successfully')?$this->lang->line('offline_request_rejected_successfully'):"Offline request rejected successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
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

	public function accept_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('id', 'Request ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('saas_id', 'SaaS ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$data = array(
					'status' => 1,			
				);
				if($this->plans_model->accept_reject_request($this->input->post('id'), $data)){

					$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
					if($plan[0]['price'] > 0){
						$transaction_data = array(
							'saas_id' => $this->input->post('saas_id'),			
							'amount' => $plan[0]['price'],		
							'status' => 1,		
						);
	
						$transaction_id = $this->plans_model->create_transaction($transaction_data);
	
						$order_data = array(
							'saas_id' => $this->input->post('saas_id'),			
							'plan_id' => $this->input->post('plan_id'),		
							'transaction_id' => $transaction_id,			
						);
						$order_id = $this->plans_model->create_order($order_data);
					}
					
					$dt = strtotime(date("Y-m-d"));
					if($plan[0]['billing_type'] == "One Time"){
						$date = NULL;
					}elseif($plan[0]['billing_type'] == "Monthly"){
						$date = date("Y-m-d", strtotime("+1 month", $dt));
					}elseif($plan[0]['billing_type'] == "Yearly"){
						$date = date("Y-m-d", strtotime("+1 year", $dt));
					}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+3 days", $dt));
					}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+7 days", $dt));
					}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+15 days", $dt));
					}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+1 month", $dt));
					}else{
						$date = date("Y-m-d", strtotime("+3 days", $dt));
					}
	
					$my_plan = get_current_plan();
					if($my_plan){
						if($my_plan['expired'] == 1 && $my_plan['plan_id'] == $this->input->post('plan_id')){
							if($my_plan['plan_id'] == 1){
								$date = date("Y-m-d", strtotime("+3 days", $dt));
								if($plan[0]['billing_type'] == "One Time"){
									$date = NULL;
								}
							}else{
								if(empty($my_plan['end_date'])){
									$dt = strtotime(date("Y-m-d"));
								}else{
									$dt = strtotime($my_plan['end_date']);
								}

								if($plan[0]['billing_type'] == "One Time"){
									$date = NULL;
								}elseif($plan[0]['billing_type'] == "Monthly"){
									$date = date("Y-m-d", strtotime("+1 month", $dt));
								}elseif($plan[0]['billing_type'] == "Yearly"){
									$date = date("Y-m-d", strtotime("+1 year", $dt));
								}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+3 days", $dt));
								}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+7 days", $dt));
								}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+15 days", $dt));
								}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+1 month", $dt));
								}else{
									$date = date("Y-m-d", strtotime("+3 days", $dt));
								}
							}
						}
						$users_plans_data = array(
							'plan_id' => $this->input->post('plan_id'),		
							'expired' => 1,		
							'start_date' => date("Y-m-d"),			
							'end_date' => $date,			
						);
						$users_plans_id = $this->plans_model->update_users_plans($this->input->post('saas_id'), $users_plans_data);
					}else{
						$users_plans_data = array(		
							'expired' => 1,				
							'plan_id' => $this->input->post('plan_id'),		
							'start_date' => date("Y-m-d"),			
							'end_date' => $date,			
						);

						$users_plans_id = $this->plans_model->update_users_plans($this->input->post('saas_id'), $users_plans_data);
					}

					if($users_plans_id){

						// notification to the creator admin

						$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
						$plan_name = '';
						if($plan){
							$plan_name = $plan[0]['title'];
						}
						$notification_data = array(
							'notification' => '<span class="text-info">'.$plan_name.'</span>',
							'type' => 'offline_request',	
							'type_id' => $this->input->post('plan_id'),	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $this->input->post('saas_id'),	
						);
						$this->notifications_model->create($notification_data);

						$this->session->set_flashdata('message', $this->lang->line('offline_request_accepted_successfully')?$this->lang->line('offline_request_accepted_successfully'):"Offline request accepted successfully.");
						$this->session->set_flashdata('message_type', 'success');
						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('offline_request_accepted_successfully')?$this->lang->line('offline_request_accepted_successfully'):"Offline request accepted successfully.";
						echo json_encode($this->data); 
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
						echo json_encode($this->data);
					}
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
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

	public function create_offline_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if(empty($_FILES['receipt']['name'])){
				$this->form_validation->set_rules('receipt', 'Upload Receipt', 'trim|required|strip_tags|xss_clean');
			}

			if($this->form_validation->run() == TRUE){

				$upload_path = 'assets/uploads/receipt';
				if(!is_dir($upload_path)){
					mkdir($upload_path,0775,true);
				}
				$image = time().'-'.str_replace(' ', '-', $_FILES["receipt"]['name']);
				$config['file_name'] = $image;
				$config['upload_path']          = $upload_path;
				$config['allowed_types']        = 'jpg|png|jpeg';
				$config['overwrite']             = false;
				$config['max_size']             = 0;
				$config['max_width']            = 0;
				$config['max_height']           = 0;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('receipt')){
					$this->data['error'] = true;
					$this->data['message'] = $this->upload->display_errors();
					echo json_encode($this->data); 
					return false;
				}

				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),			
					'plan_id' => $this->input->post('plan_id'),			
					'receipt' => $image,			
				);

				$offline_request_id = $this->plans_model->create_offline_request($data);
				if($offline_request_id){

					// notification to the saas admins
					$saas_admins = $this->ion_auth->users(array(3))->result();
					$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
					$plan_name = '';
					if($plan){
						$plan_name = $plan[0]['title'];
					}
					foreach($saas_admins as $saas_admin){
						$notification_data = array(
							'notification' => '<span class="text-info">'.$plan_name.'</span>',
							'type' => 'offline_request',	
							'type_id' => $this->input->post('plan_id'),	
							'from_id' => $this->session->userdata('saas_id'),
							'to_id' => $saas_admin->user_id,	
						);
						$this->notifications_model->create($notification_data);
					}

					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('offline_bank_transfer_request_sent_successfully')?htmlspecialchars($this->lang->line('offline_bank_transfer_request_sent_successfully')):"Offline / Bank Transfer request sent successfully.";
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


	public function order_completed()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->form_validation->set_rules('status', 'Status', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');

			$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
			if($this->form_validation->run() == TRUE && $plan){
				if($plan[0]['price'] > 0){
					$transaction_data = array(
						'saas_id' => $this->session->userdata('saas_id'),			
						'amount' => $plan[0]['price'],		
						'status' => $this->input->post('status')?$this->input->post('status'):0,		
					);

					$transaction_id = $this->plans_model->create_transaction($transaction_data);

					$order_data = array(
						'saas_id' => $this->session->userdata('saas_id'),		
						'plan_id' => $this->input->post('plan_id'),		
						'transaction_id' => $transaction_id,			
					);
					$order_id = $this->plans_model->create_order($order_data);
				}
				
				$dt = strtotime(date("Y-m-d"));
				if($plan[0]['billing_type'] == "One Time"){
					$date = NULL;
				}elseif($plan[0]['billing_type'] == "Monthly"){
					$date = date("Y-m-d", strtotime("+1 month", $dt));
				}elseif($plan[0]['billing_type'] == "Yearly"){
					$date = date("Y-m-d", strtotime("+1 year", $dt));
				}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+3 days", $dt));
				}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+7 days", $dt));
				}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+15 days", $dt));
				}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+1 month", $dt));
				}else{
					$date = date("Y-m-d", strtotime("+3 days", $dt));
				}

				$my_plan = get_current_plan();
				if($my_plan){
					if($my_plan['expired'] == 1 && $my_plan['plan_id'] == $this->input->post('plan_id')){
						if($my_plan['plan_id'] == 1){
							$date = date("Y-m-d", strtotime("+3 days", $dt));
							if($plan[0]['billing_type'] == "One Time"){
								$date = NULL;
							}
						}else{
							if(empty($my_plan['end_date'])){
								$dt = strtotime(date("Y-m-d"));
							}else{
								$dt = strtotime($my_plan['end_date']);
							}

							if($plan[0]['billing_type'] == "One Time"){
								$date = NULL;
							}elseif($plan[0]['billing_type'] == "Monthly"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}elseif($plan[0]['billing_type'] == "Yearly"){
								$date = date("Y-m-d", strtotime("+1 year", $dt));
							}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+7 days", $dt));
							}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+15 days", $dt));
							}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}else{
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}
						}
					}
					$users_plans_data = array(
						'plan_id' => $this->input->post('plan_id'),		
						'expired' => 1,		
						'start_date' => date("Y-m-d"),			
						'end_date' => $date,			
					);
					$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
				}else{
					$users_plans_data = array(	
						'expired' => 1,				
						'plan_id' => $this->input->post('plan_id'),		
						'start_date' => date("Y-m-d"),			
						'end_date' => $date,			
					);

					$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
				}
				
				if($users_plans_id){
					
					// notification to the saas admins
					$saas_admins = $this->ion_auth->users(array(3))->result();
					foreach($saas_admins as $saas_admin){
						$data = array(
							'notification' => '<span class="text-info">'.$plan[0]['title'].'</span>',
							'type' => 'new_plan',	
							'type_id' => $this->input->post('plan_id'),	
							'from_id' => $this->session->userdata('saas_id'),
							'to_id' => $saas_admin->user_id,	
						);
						$notification_id = $this->notifications_model->create($data);
					}

					$this->session->set_flashdata('message', $this->lang->line('plan_subscribed_successfully')?$this->lang->line('plan_subscribed_successfully'):"Plan subscribed successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('plan_subscribed_successfully')?$this->lang->line('plan_subscribed_successfully'):"Plan subscribed successfully.";
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
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->plans_model->delete($id)){
				$this->plans_model->delete_plan_update_users_plan($id);
				$this->notifications_model->delete('', 'new_plan', $id);
				$this->notifications_model->delete('', 'offline_request', $id);
				$this->session->set_flashdata('message', $this->lang->line('plan_deleted_successfully')?$this->lang->line('plan_deleted_successfully'):"Plan deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('plan_deleted_successfully')?$this->lang->line('plan_deleted_successfully'):"Plan deleted successfully.";
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

	public function validate($plan_id = '')
	{	
		if(empty($plan_id)){
			$plan_id = $this->uri->segment(3)?$this->uri->segment(3):'';
		}
		
		$plan = $this->plans_model->get_plans($plan_id);

		if(!empty($plan_id) && is_numeric($plan_id) && $plan){
			$this->data['validationError'] = false;
			$this->data['plan'] = $plan;
			$this->data['message'] = "Successfully.";
			echo json_encode($this->data);
		}else{
			$this->data['validationError'] = true;
			$this->data['message'] = "Unsuccessfully.";
			echo json_encode($this->data);
		}
		
	}

	public function edit()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('update_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'Price', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('billing_type', 'Billing Type', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('projects', 'Projects', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('tasks', 'Tasks', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('users', 'Users', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('storage', 'Storage', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if($this->form_validation->run() == TRUE){

				$modules['select_all'] = $this->input->post('select_all')?1:0;
				$modules['projects'] = $this->input->post('projects_module')?1:0;
				$modules['tasks'] = $this->input->post('tasks_module')?1:0;
				$modules['gantt'] = $this->input->post('gantt')?1:0;
				$modules['timesheet'] = $this->input->post('timesheet')?1:0;
				$modules['team_members'] = $this->input->post('team_members')?1:0;
				$modules['clients'] = $this->input->post('clients')?1:0;
				$modules['invoices'] = $this->input->post('invoices')?1:0;
				$modules['payments'] = $this->input->post('payments')?1:0;
				$modules['expenses'] = $this->input->post('expenses')?1:0;
				$modules['calendar'] = $this->input->post('calendar')?1:0;
				$modules['leaves'] = $this->input->post('leaves')?1:0;
				$modules['biometric_missing'] = $this->input->post('biometric_missing')?1:0;
				$modules['todo'] = $this->input->post('todo')?1:0;
				$modules['notes'] = $this->input->post('notes')?1:0;
				$modules['chat'] = $this->input->post('chat')?1:0;
				$modules['leads'] = $this->input->post('leads')?1:0;
				$modules['payment_gateway'] = $this->input->post('payment_gateway')?1:0;
				$modules['taxes'] = $this->input->post('taxes')?1:0;
				$modules['custom_currency'] = $this->input->post('custom_currency')?1:0;
				$modules['user_permissions'] = $this->input->post('user_permissions')?1:0;
				$modules['notifications'] = $this->input->post('notifications')?1:0;
				$modules['languages'] = $this->input->post('languages')?1:0;
				$modules['meetings'] = $this->input->post('meetings')?1:0;
				$modules['estimates'] = $this->input->post('estimates')?1:0;
				$modules['reports'] = $this->input->post('reports')?1:0;
				$modules['attendance'] = $this->input->post('attendance')?1:0;
				$modules['support'] = $this->input->post('support')?1:0;

				$data = array(
					'title' => $this->input->post('title'),		
					'price' => $this->input->post('price')<0?0:$this->input->post('price'),		
					'billing_type' => $this->input->post('billing_type'),		
					'projects' => $this->input->post('projects'),		
					'tasks' => $this->input->post('tasks'),			
					'users' => $this->input->post('users'),			
					'storage' => $this->input->post('storage'),		
					'modules' => json_encode($modules),		
				);

				if($this->plans_model->edit($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('plan_updated_successfully')?$this->lang->line('plan_updated_successfully'):"Plan updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('plan_updated_successfully')?$this->lang->line('plan_updated_successfully'):"Plan updated successfully.";
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
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'Price', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('billing_type', 'Billing Type', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('projects', 'Projects', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('tasks', 'Tasks', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('users', 'Users', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('storage', 'Storage', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				
				$modules['select_all'] = $this->input->post('select_all')?1:0;
				$modules['projects'] = $this->input->post('projects_module')?1:0;
				$modules['tasks'] = $this->input->post('tasks_module')?1:0;
				$modules['gantt'] = $this->input->post('gantt')?1:0;
				$modules['timesheet'] = $this->input->post('timesheet')?1:0;
				$modules['team_members'] = $this->input->post('team_members')?1:0;
				$modules['clients'] = $this->input->post('clients')?1:0;
				$modules['invoices'] = $this->input->post('invoices')?1:0;
				$modules['payments'] = $this->input->post('payments')?1:0;
				$modules['expenses'] = $this->input->post('expenses')?1:0;
				$modules['calendar'] = $this->input->post('calendar')?1:0;
				$modules['leaves'] = $this->input->post('leaves')?1:0;
				$modules['biometric_missing'] = $this->input->post('biometric_missing')?1:0;
				$modules['todo'] = $this->input->post('todo')?1:0;
				$modules['notes'] = $this->input->post('notes')?1:0;
				$modules['chat'] = $this->input->post('chat')?1:0;
				$modules['leads'] = $this->input->post('leads')?1:0;
				$modules['payment_gateway'] = $this->input->post('payment_gateway')?1:0;
				$modules['taxes'] = $this->input->post('taxes')?1:0;
				$modules['custom_currency'] = $this->input->post('custom_currency')?1:0;
				$modules['user_permissions'] = $this->input->post('user_permissions')?1:0;
				$modules['notifications'] = $this->input->post('notifications')?1:0;
				$modules['languages'] = $this->input->post('languages')?1:0;
				$modules['meetings'] = $this->input->post('meetings')?1:0;
				$modules['estimates'] = $this->input->post('estimates')?1:0;
				$modules['reports'] = $this->input->post('reports')?1:0;
				$modules['support'] = $this->input->post('support')?1:0;

				$data = array(
					'title' => $this->input->post('title'),		
					'price' => $this->input->post('price')<0?0:$this->input->post('price'),		
					'billing_type' => $this->input->post('billing_type'),		
					'projects' => $this->input->post('projects'),		
					'tasks' => $this->input->post('tasks'),		
					'users' => $this->input->post('users'),		
					'storage' => $this->input->post('storage'),		
					'modules' => json_encode($modules),		
				);

				$plan_id = $this->plans_model->create($data);
				
				if($plan_id){
					$this->session->set_flashdata('message', $this->lang->line('plan_created_successfully')?$this->lang->line('plan_created_successfully'):"Plan created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('plan_created_successfully')?$this->lang->line('plan_created_successfully'):"Plan created successfully.";
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

	public function get_plans($plan_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$plans = $this->plans_model->get_plans($plan_id);
			if($plans){
				foreach($plans as $key => $plan){
					$temp[$key] = $plan;

					if($plan["billing_type"] == 'One Time'){
						$temp[$key]['billing_type'] = $this->lang->line('one_time')?$this->lang->line('one_time'):'One Time';
					}elseif($plan["billing_type"] == 'Monthly'){
						$temp[$key]['billing_type'] = $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
					}elseif($plan["billing_type"] == 'three_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
					}elseif($plan["billing_type"] == 'seven_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
					}elseif($plan["billing_type"] == 'fifteen_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
					}elseif($plan["billing_type"] == 'thirty_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
					}else{
						$temp[$key]['billing_type'] = $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
					}
					$modules = '';
					if($plan["modules"] != ''){
						foreach(json_decode($plan["modules"]) as $mod_key => $mod){
							$mod_name = '';
							if($mod_key == 'projects'){
								$mod_name = $this->lang->line('projects')?$this->lang->line('projects'):'Projects';
							}elseif($mod_key == 'tasks'){
								$mod_name = $this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks';
							}elseif($mod_key == 'gantt'){
								$mod_name = $this->lang->line('gantt')?$this->lang->line('gantt'):'Gantt';
							}elseif($mod_key == 'timesheet'){
								$mod_name = $this->lang->line('timesheet')?$this->lang->line('timesheet'):'Timesheet';
							}elseif($mod_key == 'team_members'){
								$mod_name = $this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members';
							}elseif($mod_key == 'clients'){
								$mod_name = $this->lang->line('clients')?$this->lang->line('clients'):'Clients';
							}elseif($mod_key == 'invoices'){
								$mod_name = $this->lang->line('invoices')?$this->lang->line('invoices'):'Invoices';
							}elseif($mod_key == 'payments'){
								$mod_name = $this->lang->line('payments')?$this->lang->line('payments'):'Payments';
							}elseif($mod_key == 'expenses'){
								$mod_name = $this->lang->line('expenses')?$this->lang->line('expenses'):'Expenses';
							}elseif($mod_key == 'calendar'){
								$mod_name = $this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar';
							}elseif($mod_key == 'leaves'){
								$mod_name = $this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves';
							}elseif($mod_key == 'biometric_missing'){
									$mod_name = $this->lang->line('biometric_missing')?$this->lang->line('biometric_missing'):'Biometric Missing';
							}elseif($mod_key == 'todo'){
								$mod_name = $this->lang->line('todo')?$this->lang->line('todo'):'Todo';
							}elseif($mod_key == 'notes'){
								$mod_name = $this->lang->line('notes')?$this->lang->line('notes'):'Notes';
							}elseif($mod_key == 'chat'){
								$mod_name = $this->lang->line('chat')?$this->lang->line('chat'):'Chat';
							}elseif($mod_key == 'leads'){
								$mod_name = $this->lang->line('leads')?$this->lang->line('leads'):'Leads';
							}elseif($mod_key == 'payment_gateway'){
								$mod_name = $this->lang->line('payment_gateway')?$this->lang->line('payment_gateway'):'Payment Gateway';
							}elseif($mod_key == 'taxes'){
								$mod_name = $this->lang->line('taxes')?$this->lang->line('taxes'):'Taxes';
							}elseif($mod_key == 'custom_currency'){
								$mod_name = $this->lang->line('custom_currency')?$this->lang->line('custom_currency'):'Custom Currency';
							}elseif($mod_key == 'user_permissions'){
								$mod_name = $this->lang->line('user_permissions')?$this->lang->line('user_permissions'):'User Permissions';
							}elseif($mod_key == 'notifications'){
								$mod_name = $this->lang->line('notifications')?$this->lang->line('notifications'):'Notifications';
							}elseif($mod_key == 'languages'){
								$mod_name = $this->lang->line('languages')?$this->lang->line('languages'):'Languages';
							}elseif($mod_key == 'meetings'){
								$mod_name = $this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings';
							}elseif($mod_key == 'estimates'){
								$mod_name = $this->lang->line('estimates')?$this->lang->line('estimates'):'Estimates';
							}elseif($mod_key == 'reports'){
								$mod_name = $this->lang->line('reports')?$this->lang->line('reports'):'Reports';
							}elseif($mod_key == 'attendance'){
								$mod_name = $this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance';
							}elseif($mod_key == 'support'){
								$mod_name = $this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support';
							}
							
							if($mod_name && $mod == 1){
								$modules .= '<div class="pricing-item d-inline-flex mb-1 mr-2">
												<div class="pricing-item-icon mr-1"><i class="fas fa-check"></i></div>
												<div class="pricing-item-label">'.$mod_name.'</div>
											</div>';
							}elseif($mod_name){
								$modules .= '<div class="pricing-item d-inline-flex mb-1 mr-2">
												<div class="pricing-item-icon bg-danger text-white mr-1"><i class="fas fa-times"></i></div>
												<div class="pricing-item-label">'.$mod_name.'</div>
											</div>';
							}
						}
					}
					$temp[$key]['modules'] = '<div class="pricing bg-transparent shadow-none m-1">
						<div class="pricing-details">
						'.$modules.'
						</div>
					</div>';

					$temp[$key]['features'] = '
					<strong>'.($this->lang->line('storage')?$this->lang->line('storage'):"Storage").': </strong>'.(($plan["storage"] < 0)?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$plan["storage"].'GB').'<br>
					<strong>'.($this->lang->line('projects')?$this->lang->line('projects'):"Projects").': </strong>'.(($plan["projects"] < 0)?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$plan["projects"]).'<br>
					<strong>'.($this->lang->line('tasks')?$this->lang->line('tasks'):"Tasks").': </strong>'.(($plan["tasks"] < 0)?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$plan["tasks"]).'<br>
					<strong>'.($this->lang->line('users')?$this->lang->line('users'):"Users").': </strong>'.(($plan["users"] < 0)?($this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited'):$plan["users"]);

					$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-success modal-edit-plan mr-1" data-id="'.$plan["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger delete_plan" data-id="'.$plan["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function ajax_get_plan_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$plans = $this->plans_model->get_plans($id);
			if(!empty($plans)){
				$this->data['error'] = false;
				$this->data['data'] = $plans;
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

}







