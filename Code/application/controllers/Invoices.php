<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function invoice_to_estimate($id='')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			$data = array(
				'type' => 'estimate',			
			);

			if(!empty($id) && is_numeric($id) && $this->invoices_model->edit($id, $data)){

				$this->notifications_model->delete('', 'new_invoice', $id);
				$this->notifications_model->delete('', 'payment_received', $id);
				$this->notifications_model->delete('', 'bank_transfer_reject', $id);
				$this->notifications_model->delete('', 'bank_transfer_accept', $id);
				$this->notifications_model->delete('', 'bank_transfer', $id);

				$this->session->set_flashdata('message', $this->lang->line('updated_successfully')?htmlspecialchars($this->lang->line('updated_successfully')):"Updated successfully");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('updated_successfully')?htmlspecialchars($this->lang->line('updated_successfully')):"Updated successfully";
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

	public function reject_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('id', 'Request ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$data = array(
					'status' => 3,			
				);
				$invoices = $this->invoices_model->get_invoices($this->input->post('id'));
				if($this->invoices_model->edit($this->input->post('id'), $data)){
					if(!empty($invoices)){
						$data = array(
							'notification' => $invoices[0]['invoice_id'],
							'type' => 'bank_transfer_reject',	
							'type_id' => $this->input->post('id'),	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $invoices[0]['to_id'],	
						);
						$notification_id = $this->notifications_model->create($data);
					}

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
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('id', 'Request ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$data = array(
					'status' => 1,			
				);
				$invoices = $this->invoices_model->get_invoices($this->input->post('id'));
				if($this->invoices_model->edit($this->input->post('id'), $data)){
					if(!empty($invoices)){
						$data = array(
							'notification' => $invoices[0]['invoice_id'],
							'type' => 'bank_transfer_accept',	
							'type_id' => $this->input->post('id'),	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $invoices[0]['to_id'],	
						);
						$notification_id = $this->notifications_model->create($data);
					}
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
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function create_session($invoice_id = '', $amount = '')
	{	
		$stripeSecret = get_stripe_secret_key(true);
		if ($this->ion_auth->logged_in() && $stripeSecret && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			if(empty($invoice_id)){
				$invoice_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			if(empty($amount)){
				$amount = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			if(!empty($invoice_id) && is_numeric($invoice_id) && !empty($amount) && is_numeric($amount)){

					require_once('vendor/stripe/stripe-php/init.php');
					
					\Stripe\Stripe::setApiKey($stripeSecret);
					$session = \Stripe\Checkout\Session::create([
						'payment_method_types' => ['card'],
						'line_items' => [[
						'price_data' => [
							'currency' => get_currency('currency_code'),
							'product_data' => [
							'name' => 'Invoice',
							],
							'unit_amount' => $amount*100,
						],
						'quantity' => 1,
						]],
						'metadata' => [
							'invoice_id' => $invoice_id,
						],
						'mode' => 'payment',
						'success_url' => base_url().'invoices/soc?sid={CHECKOUT_SESSION_ID}',
						'cancel_url' => base_url().'invoices/soc?sid={CHECKOUT_SESSION_ID}',
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
	}

	public function soc()
	{	
		$stripeSecret = get_stripe_secret_key(true);
		if ($this->ion_auth->logged_in() && $stripeSecret && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			if(isset($_GET['sid']) && !empty($_GET['sid'])){
				require_once('vendor/stripe/stripe-php/init.php');
				$stripe = new \Stripe\StripeClient($stripeSecret);
				try{
					$payment_details = $stripe->checkout->sessions->retrieve($_GET['sid']);
					if($payment_details->payment_status == 'paid'){
						$data = array(
							'status' => 1,		
							'payment_type' => 'Stripe',		
							'payment_date' => date("Y-m-d"),		
						);

						if($this->invoices_model->edit($payment_details->metadata->invoice_id, $data)){
								
							$system_admins = $this->ion_auth->users(array(1))->result();
							if($system_admins){
								$invoices = $this->invoices_model->get_invoices($payment_details->metadata->invoice_id);
								if(!empty($invoices)){
									foreach ($system_admins as $system_user) {
										if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
											$data = array(
												'notification' => $invoices[0]['invoice_id'],
												'type' => 'payment_received',	
												'type_id' => $payment_details->metadata->invoice_id,	
												'from_id' => $this->session->userdata('user_id'),
												'to_id' => $system_user->user_id,	
											);
											$notification_id = $this->notifications_model->create($data);
										}
									}
								}
							}
							$this->session->set_flashdata('message', $this->lang->line('payment_successful')?$this->lang->line('payment_successful'):"Payment Successful.");
							$this->session->set_flashdata('message_type', 'success');
						}else{
							$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
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
			redirect('invoices/view/'.$payment_details->metadata->invoice_id, 'refresh');
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function order_completed()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$this->form_validation->set_rules('invoices_id', 'Invoices ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required|strip_tags|xss_clean');
			
			if($this->input->post('payment_type') == 'Bank' && empty($_FILES['receipt']['name'])){
				$this->form_validation->set_rules('receipt', 'Upload Receipt', 'trim|required|strip_tags|xss_clean');
			}

			if($this->form_validation->run() == TRUE){
				if($this->input->post('payment_type') == 'Bank'){
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
						'status' => 0,		
						'payment_type' => 'Bank Transfer',		
						'payment_date' => date("Y-m-d"),		
						'receipt' => $image,		
					);
				}else{
					$data = array(
						'status' => 1,		
						'payment_type' => $this->input->post('payment_type'),		
						'payment_date' => date("Y-m-d"),		
					);
				}

				if($this->invoices_model->edit($this->input->post('invoices_id'), $data)){
					$system_admins = $this->ion_auth->users(array(1))->result();
					if($this->input->post('payment_type') == 'Bank'){
						if($system_admins && !$this->ion_auth->is_admin()){
							$invoices = $this->invoices_model->get_invoices($this->input->post('invoices_id'));
							if(!empty($invoices)){
								foreach ($system_admins as $system_user) {
									if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
										$data = array(
											'notification' => $invoices[0]['invoice_id'],
											'type' => 'bank_transfer',	
											'type_id' => $this->input->post('invoices_id'),	
											'from_id' => $this->session->userdata('user_id'),
											'to_id' => $system_user->user_id,	
										);
										$notification_id = $this->notifications_model->create($data);
									}
								}
							}
						}
						$this->session->set_flashdata('message', $this->lang->line('offline_bank_transfer_request_sent_successfully')?$this->lang->line('offline_bank_transfer_request_sent_successfully'):"Offline / Bank Transfer request sent successfully.");
						$this->session->set_flashdata('message_type', 'success');
						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('offline_bank_transfer_request_sent_successfully')?$this->lang->line('offline_bank_transfer_request_sent_successfully'):"Offline / Bank Transfer request sent successfully.";
						echo json_encode($this->data);
					}else{
						$data = array(
							'status' => 1,		
							'payment_type' => $this->input->post('payment_type'),		
							'payment_date' => date("Y-m-d"),		
						);
						if($system_admins && !$this->ion_auth->is_admin()){
							$invoices = $this->invoices_model->get_invoices($this->input->post('invoices_id'));
							if(!empty($invoices)){
								foreach ($system_admins as $system_user) {
									if($this->session->userdata('saas_id') == $system_user->saas_id && $system_user->user_id != $this->session->userdata('user_id')){
										$data = array(
											'notification' => $invoices[0]['invoice_id'],
											'type' => 'payment_received',	
											'type_id' => $this->input->post('invoices_id'),	
											'from_id' => $this->session->userdata('user_id'),
											'to_id' => $system_user->user_id,	
										);
										$notification_id = $this->notifications_model->create($data);
									}
								}
							}
						}
						$this->session->set_flashdata('message', $this->lang->line('payment_successful')?$this->lang->line('payment_successful'):"Payment Successful.");
						$this->session->set_flashdata('message_type', 'success');
						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('payment_successful')?$this->lang->line('payment_successful'):"Payment Successful.";
						echo json_encode($this->data);
					}
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
		if ($this->ion_auth->logged_in() && is_module_allowed('invoices') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$this->data['page_title'] = 'Invoices - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
            $this->data['system_clients'] = $this->ion_auth->users(4)->result();
			if($this->ion_auth->is_admin()){
				$this->data['projects'] = $this->projects_model->get_projects();
			}else{
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

			$this->data['products'] = $this->products_model->get_products_array();
            $this->data['taxes'] = get_tax();
			$this->load->view('invoices',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function payments()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('payments') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$this->data['page_title'] = 'Payments - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
            $this->data['system_clients'] = $this->ion_auth->users(4)->result();
			if($this->ion_auth->is_admin()){
				$this->data['projects'] = $this->projects_model->get_projects();
			}else{
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

            $this->data['taxes'] = get_tax();
            $this->data['invoices'] = $this->invoices_model->get_invoices();
			$this->load->view('payments',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

    public function view()
	{
		if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			
			$invoices = $this->invoices_model->get_invoices($this->uri->segment(3));
			if(!$invoices){
				redirect('invoices', 'refresh');
			}

			$subamount = 0;
			$projects = array();
			if(is_numeric($invoices[0]['items_id'])){
				$project = $this->projects_model->get_projects('',$invoices[0]['items_id']);
				if($project){
					$projects[] = $project[0];
				}
			}elseif(!empty($invoices[0]['items_id'])){
				foreach(explode(',', $invoices[0]['items_id']) as $project_id){
					$project = $this->projects_model->get_projects('',$project_id);
					if($project){
						$projects[] = $project[0];
					}
				}
			}

			
			$products = array();
			if(is_numeric($invoices[0]['products_id'])){
				$product = $this->products_model->get_products_array($invoices[0]['products_id']);
				
				if($product){
					$products[] = $product[0];
				}
			}elseif(!empty($invoices[0]['products_id'])){
				foreach(explode(',', $invoices[0]['products_id']) as $product_id){
					$product = $this->products_model->get_products_array($product_id);
					if($product){
						$products[] = $product[0];
					}
				}
			}
			$this->data['products'] = $products;

			$this->data['items'] = $projects;
			$final_total = $invoices[0]['amount'];
			$this->data['taxes'] = array();
			if($invoices[0]['tax'] && $invoices[0]['tax'] != ''){
				$total_tax_per = 0;
				if(is_numeric($invoices[0]['tax'])){
					$taxes = get_tax($invoices[0]['tax']);
					if($taxes){
						$this->data['taxes'][0] = $taxes[0];
						$this->data['taxes'][0]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
						$total_tax_per = $total_tax_per+$taxes[0]['tax'];
					}
				}else{
					foreach(explode(',', $invoices[0]['tax']) as $tax_key => $tax_id){
						$taxes = get_tax($tax_id);
						if($taxes){
							$this->data['taxes'][$tax_key] = $taxes[0];
							$this->data['taxes'][$tax_key]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
							$total_tax_per = $total_tax_per+$taxes[0]['tax'];
						}
					}
				}
				if($total_tax_per != 0){
					$total_tax = $final_total*$total_tax_per/100;
				}else{
					$total_tax = 0;
				}
				$final_total = $final_total+$total_tax;
			}
			$this->data['final_total'] = round($final_total);

			$this->data['invoice'] = $invoices;
			$invoice_from = company_details('',$invoices[0]['from_id']);
			if($invoice_from){
				$this->data['invoice_from'] = $invoice_from;
			}else{
			    $invoice_from = new stdClass();
			    $invoice_from->company_name = '';
			    $invoice_from->address = '';
			    $invoice_from->city = '';
			    $invoice_from->state = '';
			    $invoice_from->country = '';
			    $invoice_from->zip_code = '';
			    $this->data['invoice_from'] = $invoice_from;
			}
			$invoice_to = company_details('',$invoices[0]['to_id']);
			if($invoice_to){
				$this->data['invoice_to'] = $invoice_to;
			}else{
			    $invoice_to = new stdClass();
			    $invoice_to->company_name = '';
			    $invoice_to->address = '';
			    $invoice_to->city = '';
			    $invoice_to->state = '';
			    $invoice_to->country = '';
			    $invoice_to->zip_code = '';
			    $this->data['invoice_to'] = $invoice_to;
			}
			$this->notifications_model->edit('', 'new_invoice', $invoices[0]['id'], '', '');
			$this->notifications_model->edit('', 'payment_received', $invoices[0]['id'], '', '');
			$this->notifications_model->edit('', 'bank_transfer_reject', $invoices[0]['id'], '', '');
			$this->notifications_model->edit('', 'bank_transfer_accept', $invoices[0]['id'], '', '');
			$this->notifications_model->edit('', 'bank_transfer', $invoices[0]['id'], '', '');
			$this->data['page_title'] = $invoices[0]['invoice_id'].' - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('invoices-view',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	
	public function generate_pdf(){
		if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			
			$invoices = $this->invoices_model->get_invoices($this->uri->segment(3));
			if(!$invoices){
				redirect('invoices', 'refresh');
			}

			$subamount = 0;
			$projects = array();
			if(is_numeric($invoices[0]['items_id'])){
				$project = $this->projects_model->get_projects('',$invoices[0]['items_id']);
				if($project){
					$projects[] = $project[0];
				}
			}elseif(!empty($invoices[0]['items_id'])){
				foreach(explode(',', $invoices[0]['items_id']) as $project_id){
					$project = $this->projects_model->get_projects('',$project_id);
					if($project){
						$projects[] = $project[0];
					}
				}
			}

			$products = array();
			if(is_numeric($invoices[0]['products_id'])){
				$product = $this->products_model->get_products_array($invoices[0]['products_id']);
				
				if($product){
					$products[] = $product[0];
				}
			}elseif(!empty($invoices[0]['products_id'])){
				foreach(explode(',', $invoices[0]['products_id']) as $product_id){
					$product = $this->products_model->get_products_array($product_id);
					if($product){
						$products[] = $product[0];
					}
				}
			}
			$this->data['products'] = $products;

			$this->data['items'] = $projects;
			$final_total = $invoices[0]['amount'];
			$this->data['taxes'] = array();
			if($invoices[0]['tax'] && $invoices[0]['tax'] != ''){
				$total_tax_per = 0;
				if(is_numeric($invoices[0]['tax'])){
					$taxes = get_tax($invoices[0]['tax']);
					if($taxes){
						$this->data['taxes'][0] = $taxes[0];
						$this->data['taxes'][0]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
						$total_tax_per = $total_tax_per+$taxes[0]['tax'];
					}
				}else{
					foreach(explode(',', $invoices[0]['tax']) as $tax_key => $tax_id){
						$taxes = get_tax($tax_id);
						if($taxes){
							$this->data['taxes'][$tax_key] = $taxes[0];
							$this->data['taxes'][$tax_key]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
							$total_tax_per = $total_tax_per+$taxes[0]['tax'];
						}
					}
				}
				if($total_tax_per != 0){
					$total_tax = $final_total*$total_tax_per/100;
				}else{
					$total_tax = 0;
				}
				$final_total = $final_total+$total_tax;
			}
			$this->data['final_total'] = round($final_total);

			$this->data['invoice'] = $invoices;
			$invoice_from = company_details('',$invoices[0]['from_id']);
			if($invoice_from){
				$this->data['invoice_from'] = $invoice_from;
			}else{
			    $invoice_from = new stdClass();
			    $invoice_from->company_name = '';
			    $invoice_from->address = '';
			    $invoice_from->city = '';
			    $invoice_from->state = '';
			    $invoice_from->country = '';
			    $invoice_from->zip_code = '';
			    $this->data['invoice_from'] = $invoice_from;
			}
			$invoice_to = company_details('',$invoices[0]['to_id']);
			if($invoice_to){
				$this->data['invoice_to'] = $invoice_to;
			}else{
			    $invoice_to = new stdClass();
			    $invoice_to->company_name = '';
			    $invoice_to->address = '';
			    $invoice_to->city = '';
			    $invoice_to->state = '';
			    $invoice_to->country = '';
			    $invoice_to->zip_code = '';
			    $this->data['invoice_to'] = $invoice_to;
			}
			
			$this->data['page_title'] = $invoices[0]['invoice_id'].' - '.company_name();
			
			$this->load->view('invoices-pdf.php', $this->data);

			$html = $this->output->get_output();

			$this->load->library('pdf');

			$options = $this->dompdf->getOptions();
			$options->setisPhpEnabled(true);
			$options->setisRemoteEnabled(true);
			$options->setisJavascriptEnabled(true);
			$options->setisHtml5ParserEnabled(true);

			$this->dompdf->setOptions($options);
			$this->dompdf->loadHtml($html);
			$this->dompdf->render();
			$this->dompdf->stream($invoices[0]['invoice_id'].".pdf", array("Attachment"=>0));
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function ajax_get_invoices_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id) && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$invoices = $this->invoices_model->get_invoices($id);
			if(!empty($invoices)){
				$temp = [];
				foreach($invoices as $key => $invoice){
					$temp[$key] = $invoice;
					$temp[$key]['invoice_date'] = format_date($invoice['invoice_date'],system_date_format());
					$temp[$key]['due_date'] = format_date($invoice['due_date'],system_date_format());
				}
				$this->data['error'] = false;
				$this->data['data'] = $temp;
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

	public function get_payments($invoice_id = '')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
            $invoices = $this->invoices_model->get_invoices($invoice_id, true);
			if($invoices){
				foreach($invoices as $key => $invoice){
						$temp[$key] = $invoice;
						$temp[$key]['invoice_id'] = '<a href="'.(base_url('invoices/view/'.$invoice['id'])).'" target="_blank"><strong>'.$invoice['invoice_id'].'</strong></a>';
						$temp[$key]['to_id'] = $invoice['to_user'];
						$temp[$key]['due_date'] = format_date($invoice['due_date'],system_date_format());
						$temp[$key]['payment_date'] = $invoice['payment_date']?format_date($invoice['payment_date'],system_date_format()):'';

						$amount = $invoice['amount'];

						if($invoice['tax'] && $invoice['tax'] != ''){
							$total_tax_per = 0;
							if(is_numeric($invoice['tax'])){
								$taxes = get_tax($invoice['tax']);
								if($taxes){
									$total_tax_per = $total_tax_per+$taxes[0]['tax'];
								}
							}else{
								foreach(explode(',', $invoice['tax']) as $tax_id){
									$taxes = get_tax($tax_id);
									if($taxes){
										$total_tax_per = $total_tax_per+$taxes[0]['tax'];
									}
								}
							}
							if($total_tax_per != 0){
								$total_tax = $amount*$total_tax_per/100;
							}else{
								$total_tax = 0;
							}
							$amount = $amount+$total_tax;
						}

						$projects = '';
						if(is_numeric($invoice['items_id'])){
							$project = $this->projects_model->get_projects('',$invoice['items_id']);
							if($project){
								$projects .= '<a href="'.(base_url('projects/detail/'.$project[0]['id'])).'" target="_blank">'.$project[0]['title'].'</a><br>';
							}
						}else{
							foreach(explode(',', $invoice['items_id']) as $project_id){
								$project = $this->projects_model->get_projects('',$project_id);
								if($project){
									$projects .= '<a href="'.(base_url('projects/detail/'.$project[0]['id'])).'" target="_blank">'.$project[0]['title'].'</a><br>';
								}
							}
						}
						
						if(!empty($projects)){
							$temp[$key]['projects'] = $projects;
						}else{
							$temp[$key]['projects'] = $invoice['to_user'];
						}

						$temp[$key]['amount'] = round($amount);

						if($invoice['status'] == 0 || $invoice['status'] == 2){
							$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('pending')?$this->lang->line('pending'):'Pending').'</div>';
						}elseif($invoice['status'] == 1){
							$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('paid')?$this->lang->line('paid'):'Paid').'</div>';
						}elseif($invoice['status'] == 3){
							$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
						}

						if($invoice['receipt']){
							$file_upload_path = '';
							if(file_exists('assets/uploads/receipt/'.$invoice['receipt'])){
								$file_upload_path = 'assets/uploads/receipt/'.$invoice['receipt'];
							}
							$temp[$key]['receipt'] = '<span class="d-flex"><a target="_blank" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View').'"><i class="fas fa-eye"></i></a><a download="'.$invoice['receipt'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a></span>';
						}

						if($invoice['payment_type'] == 'Bank Transfer'){ 
							$temp[$key]['payment_type'] = $this->lang->line('offline_bank_transfer')?htmlspecialchars($this->lang->line('offline_bank_transfer')):'Bank Transfer';
						}
						if($invoice['payment_type'] == 'By Admin'){ 
							$temp[$key]['payment_type'] = $this->lang->line('by_admin')?htmlspecialchars($this->lang->line('by_admin')):'By Admin';
						}

						if($this->ion_auth->is_admin()){
							if($invoice['payment_type'] == 'Bank Transfer' && ($invoice['status'] == 0 || $invoice['status'] == 2)){

								$temp[$key]['action'] = '<span class="d-flex">
								<a href="#" class="btn btn-icon btn-sm btn-success accept_payment_request mr-1" data-id="'.$invoice["id"].'" data-toggle="tooltip" title="'.($this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept').'"><i class="fas fa-check"></i></a>
								
								<a href="#" class="btn btn-icon btn-sm btn-danger reject_payment_request" data-id="'.$invoice["id"].'" data-toggle="tooltip" title="'.($this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject').'"><i class="fas fa-times"></i></a></span>';
							}else{
								$temp[$key]['action'] = '<span class="d-flex">
								<a href="#" class="disabled btn btn-icon btn-sm btn-success mr-1"><i class="fas fa-check"></i></a>
								
								<a href="#" class="disabled btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a></span>';
							}
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

	public function get_invoices($invoice_id = '')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
            $invoices = $this->invoices_model->get_invoices($invoice_id);
			if($invoices){
				foreach($invoices as $key => $invoice){
					$temp[$key] = $invoice;
					$temp[$key]['invoice_id'] = '<a href="'.(base_url('invoices/view/'.$invoice['id'])).'" target="_blank"><strong>'.$invoice['invoice_id'].'</strong></a>';
					$temp[$key]['to_id'] = $invoice['to_user'];
					$temp[$key]['due_date'] = format_date($invoice['due_date'],system_date_format());
					$temp[$key]['payment_date'] = $invoice['payment_date']?format_date($invoice['payment_date'],system_date_format()):'';

					$amount = $invoice['amount'];

					if($invoice['tax'] && $invoice['tax'] != ''){
						$total_tax_per = 0;
						if(is_numeric($invoice['tax'])){
							$taxes = get_tax($invoice['tax']);
							if($taxes){
								$total_tax_per = $total_tax_per+$taxes[0]['tax'];
							}
						}else{
							foreach(explode(',', $invoice['tax']) as $tax_id){
								$taxes = get_tax($tax_id);
								if($taxes){
									$total_tax_per = $total_tax_per+$taxes[0]['tax'];
								}
							}
						}
						if($total_tax_per != 0){
							$total_tax = $amount*$total_tax_per/100;
						}else{
							$total_tax = 0;
						}
						$amount = $amount+$total_tax;
					}

					$projects = '';
					if(is_numeric($invoice['items_id'])){
						$project = $this->projects_model->get_projects('',$invoice['items_id']);
						if($project){
							$projects .= '<a href="'.(base_url('projects/detail/'.$project[0]['id'])).'" target="_blank">'.$project[0]['title'].'</a><br>';
						}
					}else{
						foreach(explode(',', $invoice['items_id']) as $project_id){
							$project = $this->projects_model->get_projects('',$project_id);
							if($project){
								$projects .= '<a href="'.(base_url('projects/detail/'.$project[0]['id'])).'" target="_blank">'.$project[0]['title'].'</a><br>';
							}
						}
					}
					
					if(!empty($projects)){
						$temp[$key]['projects'] = $projects;
					}else{
						$temp[$key]['projects'] = $invoice['to_user'];
					}

					$temp[$key]['amount'] = round($amount);

					if($invoice['status'] == 0){
						$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('due')?$this->lang->line('due'):'Due').'</div>';
						$temp[$key]['status_pay'] = '<div class="badge badge-info">'.($this->lang->line('pending')?$this->lang->line('pending'):'Pending').'</div>';
					}elseif($invoice['status'] == 1){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('paid')?$this->lang->line('paid'):'Paid').'</div>';
						$temp[$key]['status_pay'] = '<div class="badge badge-success">'.($this->lang->line('paid')?$this->lang->line('paid'):'Paid').'</div>';
					}elseif($invoice['status'] == 2 || $invoice['status'] == 3){
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('overdue')?$this->lang->line('overdue'):'Overdue').'</div>';
						$temp[$key]['status_pay'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
					}
					$temp[$key]['action'] = '<span class="d-flex">

					<a href="'.(base_url('invoices/view/'.$invoice['id'])).'" class="btn btn-icon btn-sm btn-primary mr-1" data-id="'.$invoice["id"].'" data-toggle="tooltip" title="'.($this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View').'" target="_blank"><i class="fas fa-eye"></i></a>
					
					<a href="'.(base_url('invoices/generate-pdf/'.$invoice['id'])).'" class="btn btn-icon btn-sm btn-warning mr-1" data-id="'.$invoice["id"].'" data-toggle="tooltip" title="'.($this->lang->line('print')?htmlspecialchars($this->lang->line('print')):'Print').'" target="_blank"><i class="fas fa-print"></i></a>';
					
					if($this->ion_auth->is_admin()){
						$temp[$key]['action'] .= '<span class="d-flex">

						<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-invoices mr-1" data-id="'.$invoice["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-warning invoice_to_estimate mr-1" data-id="'.$invoice["id"].'" data-toggle="tooltip" title="'.($this->lang->line('convert')?htmlspecialchars($this->lang->line('convert')):'Convert').'"><i class="fa fa-sync"></i></a>

						<a href="#" class="btn btn-icon btn-sm btn-danger delete_invoice" data-id="'.$invoice["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a>
						
						</span>';
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


    public function create()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'required|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('to_id', 'Client', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('items_id[]', 'Projects', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('products_id[]', 'Products', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('note', 'Note', 'strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$amount = 0;
				foreach($this->input->post('items_id') as $project_id){
					$projects = $this->projects_model->get_projects('',$project_id);
					if($projects){
						$amount = $amount+$projects[0]['budget'];
					}
				}
				foreach($this->input->post('products_id') as $pro_id){
					$pros = $this->products_model->get_products_by_id($pro_id);
					if($pros){
						$amount = $amount+$pros[0]['price'];
					}
				}

				
				$data = array(
					'created_by' => $this->session->userdata('user_id'),
					'from_id' => $this->session->userdata('saas_id'),
					'to_id' => $this->input->post('to_id'),	
					'items_id' => implode(',', $this->input->post('items_id')),	
					'products_id' => implode(',', $this->input->post('products_id')),	
					'amount' => $amount,		
					'note' => $this->input->post('note'),	
					'status' => $this->input->post('status'),	
					'tax' => $this->input->post('tax')?implode(',', $this->input->post('tax')):'',	
					'invoice_date' => format_date($this->input->post('invoice_date'),"Y-m-d"),	
					'due_date' => format_date($this->input->post('due_date'),"Y-m-d"),	
				);

				$invoice_id = $this->invoices_model->create($data);
				
				if($invoice_id){
					if($this->input->post('to_id')){
						$invoices = $this->invoices_model->get_invoices($invoice_id);
						if(!empty($invoices)){
							$data = array(
								'notification' => $invoices[0]['invoice_id'],
								'type' => 'new_invoice',	
								'type_id' => $invoice_id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $this->input->post('to_id'),	
							);
							$notification_id = $this->notifications_model->create($data);

								
							$final_total = $amount;
							$this->data['taxes'] = array();
							if($final_total > 0 && $this->input->post('tax') && $this->input->post('tax') != ''){
								$total_tax_per = 0;
								if(is_numeric($this->input->post('tax'))){
									$taxes = get_tax($this->input->post('tax'));
									if($taxes){
										$this->data['taxes'][0] = $taxes[0];
										$this->data['taxes'][0]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
										$total_tax_per = $total_tax_per+$taxes[0]['tax'];
									}
								}else{
									foreach($this->input->post('tax') as $tax_key => $tax_id){
										$taxes = get_tax($tax_id);
										if($taxes){
											$this->data['taxes'][$tax_key] = $taxes[0];
											$this->data['taxes'][$tax_key]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
											$total_tax_per = $total_tax_per+$taxes[0]['tax'];
										}
									}
								}
								if($total_tax_per != 0){
									$total_tax = $final_total*$total_tax_per/100;
								}else{
									$total_tax = 0;
								}
								$final_total = $final_total+$total_tax;
							}

							$template_data = array();
							$template_data['INVOICE_NUMBER'] = $invoices[0]['invoice_id'];
							$template_data['INVOICE_AMOUNT'] = round($final_total);
							$template_data['INVOICE_DATE'] = format_date($this->input->post('invoice_date'),"Y-m-d");
							$template_data['INVOICE_DUE_DATE'] = format_date($this->input->post('due_date'),"Y-m-d");
							$template_data['INVOICE_URL'] = base_url('invoices');
							$email_template = render_email_template('new_invoice', $template_data);
							if($this->input->post('send_email_notification')){
								$to_user = $this->ion_auth->user($this->input->post('to_id'))->row();
								send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
							}

						}
					}

					$this->session->set_flashdata('message', $this->lang->line('invoice_created_successfully')?$this->lang->line('invoice_created_successfully'):"Invoice created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('invoice_created_successfully')?$this->lang->line('invoice_created_successfully'):"Invoice created successfully.";
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
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			if(!empty($id) && is_numeric($id) && $this->invoices_model->delete($id)){

				$this->notifications_model->delete('', 'new_invoice', $id);
				$this->notifications_model->delete('', 'payment_received', $id);
				$this->notifications_model->delete('', 'bank_transfer_reject', $id);
				$this->notifications_model->delete('', 'bank_transfer_accept', $id);
				$this->notifications_model->delete('', 'bank_transfer', $id);

				$this->session->set_flashdata('message', $this->lang->line('invoice_deleted_successfully')?$this->lang->line('invoice_deleted_successfully'):"Invoice deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('invoice_deleted_successfully')?$this->lang->line('invoice_deleted_successfully'):"Invoice deleted successfully.";
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
			$this->form_validation->set_rules('update_id', 'Invoice ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'required|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('to_id', 'Client', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('items_id[]', 'Projects', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('products_id[]', 'Products', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('note', 'Note', 'strip_tags|xss_clean');
			if($this->form_validation->run() == TRUE){
				$amount = 0;
				foreach($this->input->post('items_id') as $project_id){
					$projects = $this->projects_model->get_projects('',$project_id);
					if($projects){
						$amount = $amount+$projects[0]['budget'];
					}
				}
				
				foreach($this->input->post('products_id') as $pro_id){
					$pros = $this->products_model->get_products_by_id($pro_id);
					if($pros){
						$amount = $amount+$pros[0]['price'];
					}
				}
				
				$data = array(
					'to_id' => $this->input->post('to_id'),	
					'items_id' => implode(',', $this->input->post('items_id')),	
					'products_id' => implode(',', $this->input->post('products_id')),	
					'amount' => $amount,		
					'note' => $this->input->post('note'),	
					'status' => $this->input->post('status'),	
					'tax' => $this->input->post('tax')?implode(',', $this->input->post('tax')):'',	
					'invoice_date' => format_date($this->input->post('invoice_date'),"Y-m-d"),	
					'due_date' => format_date($this->input->post('due_date'),"Y-m-d"),	
				);
				
				if($this->input->post('to_id') && $this->input->post('send_email_notification')){
					$invoices = $this->invoices_model->get_invoices($this->input->post('update_id'));
					if($invoices){
						
						$final_total = $amount;
						$this->data['taxes'] = array();
						if($final_total > 0 && $this->input->post('tax') && $this->input->post('tax') != ''){
							$total_tax_per = 0;
							if(is_numeric($this->input->post('tax'))){
								$taxes = get_tax($this->input->post('tax'));
								if($taxes){
									$this->data['taxes'][0] = $taxes[0];
									$this->data['taxes'][0]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
									$total_tax_per = $total_tax_per+$taxes[0]['tax'];
								}
							}else{
								foreach($this->input->post('tax') as $tax_key => $tax_id){
									$taxes = get_tax($tax_id);
									if($taxes){
										$this->data['taxes'][$tax_key] = $taxes[0];
										$this->data['taxes'][$tax_key]['tax_amount'] = $invoices[0]['amount']*$taxes[0]['tax']/100;
										$total_tax_per = $total_tax_per+$taxes[0]['tax'];
									}
								}
							}
							if($total_tax_per != 0){
								$total_tax = $final_total*$total_tax_per/100;
							}else{
								$total_tax = 0;
							}
							$final_total = $final_total+$total_tax;
						}

						$template_data = array();
						$template_data['INVOICE_NUMBER'] = $invoices[0]['invoice_id'];
						$template_data['INVOICE_AMOUNT'] = round($final_total);
						$template_data['INVOICE_DATE'] = format_date($this->input->post('invoice_date'),"Y-m-d");
						$template_data['INVOICE_DUE_DATE'] = format_date($this->input->post('due_date'),"Y-m-d");
						$template_data['INVOICE_URL'] = base_url('invoices');
						$email_template = render_email_template('new_invoice', $template_data);
						$to_user = $this->ion_auth->user($this->input->post('to_id'))->row();
						send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
					}
				}

				if($this->invoices_model->edit($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('invoice_updated_successfully')?$this->lang->line('invoice_updated_successfully'):"Invoice updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('invoice_updated_successfully')?$this->lang->line('invoice_updated_successfully'):"Invoice updated successfully.";
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
