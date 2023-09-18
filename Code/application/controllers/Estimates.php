<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Estimates extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function estimate_to_invoice($id='')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			$data = array(
				'type' => 'invoice',			
			);

			if(!empty($id) && is_numeric($id) && $this->estimates_model->edit($id, $data)){

				$this->notifications_model->delete('', 'new_estimate', $id);

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

	public function reject()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(4))
		{
			if($this->uri->segment(3) && is_numeric($this->uri->segment(3))){
				$data = array(
					'status' => 3,			
				);

				$estimates = $this->estimates_model->get_estimates($this->uri->segment(3));
				if(empty($estimates)){
					$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
					$this->session->set_flashdata('message_type', 'success');
					redirect('estimates/view/'.$this->uri->segment(3), 'refresh');
				}

				if($this->estimates_model->edit($this->uri->segment(3), $data)){
					if(!empty($estimates)){
						$data = array(
							'notification' => $estimates[0]['estimate_id'],
							'type' => 'estimate_reject',	
							'type_id' => $this->uri->segment(3),	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $estimates[0]['from_id'],	
						);
						$notification_id = $this->notifications_model->create($data);
					}

					$this->session->set_flashdata('message', $this->lang->line('updated_successfully')?$this->lang->line('updated_successfully'):"Updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					redirect('estimates/view/'.$this->uri->segment(3), 'refresh');
				}else{
					$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
					$this->session->set_flashdata('message_type', 'success');
					redirect('estimates/view/'.$this->uri->segment(3), 'refresh');
				}
			}else{
				redirect('estimates', 'refresh');
			}
		}else{
			redirect('estimates', 'refresh'); 
		}
	}

	
	public function accept()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(4))
		{
			if($this->uri->segment(3) && is_numeric($this->uri->segment(3))){
				$data = array(
					'status' => 2,			
				);

				$estimates = $this->estimates_model->get_estimates($this->uri->segment(3));
				if(empty($estimates)){
					$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
					$this->session->set_flashdata('message_type', 'success');
					redirect('estimates/view/'.$this->uri->segment(3), 'refresh');
				}

				if($this->estimates_model->edit($this->uri->segment(3), $data)){
					if(!empty($estimates)){
						$data = array(
							'notification' => $estimates[0]['estimate_id'],
							'type' => 'estimate_accept',	
							'type_id' => $this->uri->segment(3),	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $estimates[0]['from_id'],	
						);
						$notification_id = $this->notifications_model->create($data);
					}

					$this->session->set_flashdata('message', $this->lang->line('updated_successfully')?$this->lang->line('updated_successfully'):"Updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					redirect('estimates/view/'.$this->uri->segment(3), 'refresh');
				}else{
					$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
					$this->session->set_flashdata('message_type', 'success');
					redirect('estimates/view/'.$this->uri->segment(3), 'refresh');
				}
			}else{
				redirect('estimates', 'refresh');
			}
		}else{
			redirect('estimates', 'refresh'); 
		}
	}

    public function index()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('estimates') && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$this->data['page_title'] = 'Estimates - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
            $this->data['system_clients'] = $this->ion_auth->users(4)->result();
			$this->data['products'] = $this->products_model->get_products_array();
            $this->data['taxes'] = get_tax();
			$this->load->view('estimates',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

    public function view()
	{
		if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$estimates = $this->estimates_model->get_estimates($this->uri->segment(3));
			if(!$estimates){
				redirect('estimates', 'refresh');
			}

			$subamount = 0;
			$projects = array();
			if(is_numeric($estimates[0]['products_id'])){
				$project = $this->products_model->get_products_array($estimates[0]['products_id']);
				
				if($project){
					$projects[] = $project[0];
				}
			}elseif(!empty($estimates[0]['products_id'])){
				foreach(explode(',', $estimates[0]['products_id']) as $project_id){
					$project = $this->products_model->get_products_array($project_id);
					if($project){
						$projects[] = $project[0];
					}
				}
			}else{
				$estimates[0]['amount'] = 0;
			}

			$this->data['items'] = $projects;
			$final_total = $estimates[0]['amount'];
			$this->data['taxes'] = array();
			if($estimates[0]['tax'] && $estimates[0]['tax'] != ''){
				$total_tax_per = 0;
				if(is_numeric($estimates[0]['tax'])){
					$taxes = get_tax($estimates[0]['tax']);
					if($taxes){
						$this->data['taxes'][0] = $taxes[0];
						$this->data['taxes'][0]['tax_amount'] = $estimates[0]['amount']*$taxes[0]['tax']/100;
						$total_tax_per = $total_tax_per+$taxes[0]['tax'];
					}
				}else{
					foreach(explode(',', $estimates[0]['tax']) as $tax_key => $tax_id){
						$taxes = get_tax($tax_id);
						if($taxes){
							$this->data['taxes'][$tax_key] = $taxes[0];
							$this->data['taxes'][$tax_key]['tax_amount'] = $estimates[0]['amount']*$taxes[0]['tax']/100;
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

			$this->data['estimate'] = $estimates;
			$estimate_from = company_details('',$estimates[0]['from_id']);
			if($estimate_from){
				$this->data['estimate_from'] = $estimate_from;
			}else{
			    $estimate_from = new stdClass();
			    $estimate_from->company_name = '';
			    $estimate_from->address = '';
			    $estimate_from->city = '';
			    $estimate_from->state = '';
			    $estimate_from->country = '';
			    $estimate_from->zip_code = '';
			    $this->data['estimate_from'] = $estimate_from;
			}
			$estimate_to = company_details('',$estimates[0]['to_id']);
			if($estimate_to){
				$this->data['estimate_to'] = $estimate_to;
			}else{
			    $estimate_to = new stdClass();
			    $estimate_to->company_name = '';
			    $estimate_to->address = '';
			    $estimate_to->city = '';
			    $estimate_to->state = '';
			    $estimate_to->country = '';
			    $estimate_to->zip_code = '';
			    $this->data['estimate_to'] = $estimate_to;
			}
			$this->notifications_model->edit('', 'new_estimate', $estimates[0]['id'], '', '');

			$this->data['page_title'] = $estimates[0]['estimate_id'].' - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('estimates-view',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	
	public function generate_pdf(){
		if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && $this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			
			$estimates = $this->estimates_model->get_estimates($this->uri->segment(3));
			if(!$estimates){
				redirect('estimates', 'refresh');
			}

			$subamount = 0;
			$projects = array();
			if(is_numeric($estimates[0]['products_id'])){
				$project = $this->products_model->get_products_array($estimates[0]['products_id']);
				if($project){
					$projects[] = $project[0];
				}
			}elseif(!empty($estimates[0]['products_id'])){
				foreach(explode(',', $estimates[0]['products_id']) as $project_id){
					$project = $this->products_model->get_products_array($project_id);
					if($project){
						$projects[] = $project[0];
					}
				}
			}else{
				$estimates[0]['amount'] = 0;
			}

			$this->data['items'] = $projects;
			$final_total = $estimates[0]['amount'];
			$this->data['taxes'] = array();
			if($estimates[0]['tax'] && $estimates[0]['tax'] != ''){
				$total_tax_per = 0;
				if(is_numeric($estimates[0]['tax'])){
					$taxes = get_tax($estimates[0]['tax']);
					if($taxes){
						$this->data['taxes'][0] = $taxes[0];
						$this->data['taxes'][0]['tax_amount'] = $estimates[0]['amount']*$taxes[0]['tax']/100;
						$total_tax_per = $total_tax_per+$taxes[0]['tax'];
					}
				}else{
					foreach(explode(',', $estimates[0]['tax']) as $tax_key => $tax_id){
						$taxes = get_tax($tax_id);
						if($taxes){
							$this->data['taxes'][$tax_key] = $taxes[0];
							$this->data['taxes'][$tax_key]['tax_amount'] = $estimates[0]['amount']*$taxes[0]['tax']/100;
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

			$this->data['estimate'] = $estimates;
						
			$estimate_from = company_details('',$estimates[0]['from_id']);
			if($estimate_from){
				$this->data['estimate_from'] = $estimate_from;
			}else{
			    $estimate_from = new stdClass();
			    $estimate_from->company_name = '';
			    $estimate_from->address = '';
			    $estimate_from->city = '';
			    $estimate_from->state = '';
			    $estimate_from->country = '';
			    $estimate_from->zip_code = '';
			    $this->data['estimate_from'] = $estimate_from;
			}
			$estimate_to = company_details('',$estimates[0]['to_id']);
			if($estimate_to){
				$this->data['estimate_to'] = $estimate_to;
			}else{
			    $estimate_to = new stdClass();
			    $estimate_to->company_name = '';
			    $estimate_to->address = '';
			    $estimate_to->city = '';
			    $estimate_to->state = '';
			    $estimate_to->country = '';
			    $estimate_to->zip_code = '';
			    $this->data['estimate_to'] = $estimate_to;
			}
			
			$this->data['page_title'] = $estimates[0]['estimate_id'].' - '.company_name();
			
			$this->load->view('estimates-pdf.php', $this->data);

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
			$this->dompdf->stream($estimates[0]['estimate_id'].".pdf", array("Attachment"=>0));
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function ajax_get_estimates_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id) && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$estimates = $this->estimates_model->get_estimates($id);
			if(!empty($estimates)){
				$temp = [];
				foreach($estimates as $key => $estimate){
					$temp[$key] = $estimate;
					$temp[$key]['estimate_date'] = format_date($estimate['invoice_date'],system_date_format());
					$temp[$key]['due_date'] = format_date($estimate['due_date'],system_date_format());
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

	public function get_estimates($estimate_id = '')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
            $estimates = $this->estimates_model->get_estimates($estimate_id);
			if($estimates){
				foreach($estimates as $key => $estimate){
					$temp[$key] = $estimate;
					$temp[$key]['estimate_id'] = '<a href="'.(base_url('estimates/view/'.$estimate['id'])).'" target="_blank"><strong>'.$estimate['estimate_id'].'</strong></a>';
					$temp[$key]['to_id'] = $estimate['to_user'];
					$temp[$key]['due_date'] = format_date($estimate['due_date'],system_date_format());
					$temp[$key]['payment_date'] = $estimate['payment_date']?format_date($estimate['payment_date'],system_date_format()):'';

					$amount = $estimate['amount'];

					if($estimate['tax'] && $estimate['tax'] != ''){
						$total_tax_per = 0;
						if(is_numeric($estimate['tax'])){
							$taxes = get_tax($estimate['tax']);
							if($taxes){
								$total_tax_per = $total_tax_per+$taxes[0]['tax'];
							}
						}else{
							foreach(explode(',', $estimate['tax']) as $tax_id){
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

					$temp[$key]['projects'] = $estimate['to_user'];

					$temp[$key]['amount'] = round($amount);

					if($estimate['status'] == 0){
						$temp[$key]['status'] = '<div class="badge badge-warning">'.($this->lang->line('draft')?$this->lang->line('draft'):'Draft').'</div>';
					}elseif($estimate['status'] == 1){
						if($this->ion_auth->in_group(4)){
							$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('received')?$this->lang->line('received'):'Received').'</div>';
						}else{
							$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('sent')?$this->lang->line('sent'):'Sent').'</div>';
						}
					}elseif($estimate['status'] == 2){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('accepted')?$this->lang->line('accepted'):'Accepted').'</div>';
					}elseif($estimate['status'] == 3){
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
					}
					$temp[$key]['action'] = '<span class="d-flex">

					<a href="'.(base_url('estimates/view/'.$estimate['id'])).'" class="btn btn-icon btn-sm btn-primary mr-1" data-id="'.$estimate["id"].'" data-toggle="tooltip" title="'.($this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View').'" target="_blank"><i class="fas fa-eye"></i></a>
					
					<a href="'.(base_url('estimates/generate-pdf/'.$estimate['id'])).'" class="btn btn-icon btn-sm btn-warning mr-1" data-id="'.$estimate["id"].'" data-toggle="tooltip" title="'.($this->lang->line('print')?htmlspecialchars($this->lang->line('print')):'Print').'" target="_blank"><i class="fas fa-print"></i></a>';
					
					if($this->ion_auth->is_admin()){
						$temp[$key]['action'] .= '

						<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-estimates mr-1" data-id="'.$estimate["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-warning estimate_to_invoice mr-1" data-id="'.$estimate["id"].'" data-toggle="tooltip" title="'.($this->lang->line('convert')?htmlspecialchars($this->lang->line('convert')):'Convert').'"><i class="fa fa-sync"></i></a>

						<a href="#" class="btn btn-icon btn-sm btn-danger delete_estimate" data-id="'.$estimate["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
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
			$this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'required|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('to_id', 'Client', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('products_id[]', 'Products', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('note', 'Note', 'strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$amount = 0;
				foreach($this->input->post('products_id') as $pro_id){
					$pros = $this->products_model->get_products_by_id($pro_id);
					if($pros){
						$amount = $amount+$pros[0]['price'];
					}
				}
				
				$data = array(
					'created_by' => $this->session->userdata('user_id'),
					'from_id' => $this->session->userdata('saas_id'),
					'type' => 'estimate',	
					'to_id' => $this->input->post('to_id'),	
					'products_id' => implode(',', $this->input->post('products_id')),	
					'amount' => $amount,		
					'note' => $this->input->post('note'),	
					'status' => $this->input->post('status'),	
					'tax' => $this->input->post('tax')?implode(',', $this->input->post('tax')):'',	
					'invoice_date' => format_date($this->input->post('estimate_date'),"Y-m-d"),	
					'due_date' => format_date($this->input->post('due_date'),"Y-m-d"),	
				);

				$estimate_id = $this->estimates_model->create($data);
				
				if($estimate_id){
					if($this->input->post('to_id') && $this->input->post('status') != 0){
						$estimates = $this->estimates_model->get_estimates($estimate_id);
						if(!empty($estimates)){
							$data = array(
								'notification' => $estimates[0]['estimate_id'],
								'type' => 'new_estimate',	
								'type_id' => $estimate_id,	
								'from_id' => $this->session->userdata('user_id'),
								'to_id' => $this->input->post('to_id'),	
							);
							$notification_id = $this->notifications_model->create($data);

							if($amount > 0 && $this->input->post('tax') && $this->input->post('tax') != ''){
								$total_tax_per = 0;
								if(is_numeric($this->input->post('tax'))){
									$taxes = get_tax($this->input->post('tax'));
									if($taxes){
										$total_tax_per = $total_tax_per+$taxes[0]['tax'];
									}
								}else{
									foreach($this->input->post('tax') as $tax_id){
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
							
							$template_data = array();
							$template_data['ESTIMATE_NUMBER'] = $estimates[0]['estimate_id'];
							$template_data['ESTIMATE_AMOUNT'] = round($amount);
							$template_data['ESTIMATE_DATE'] = format_date($this->input->post('estimate_date'),"Y-m-d");
							$template_data['ESTIMATE_DUE_DATE'] = format_date($this->input->post('due_date'),"Y-m-d");
							$template_data['ESTIMATE_URL'] = base_url('estimates');
							$email_template = render_email_template('new_estimate', $template_data);
							if($this->input->post('send_email_notification')){
								$to_user = $this->ion_auth->user($this->input->post('to_id'))->row();
								send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
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

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			if(!empty($id) && is_numeric($id) && $this->estimates_model->delete($id)){

				$this->notifications_model->delete('', 'new_estimate', $id);

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
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'required|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('to_id', 'Client', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('products_id[]', 'Products', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('note', 'Note', 'strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$amount = 0;
				foreach($this->input->post('products_id') as $pro_id){
					$pros = $this->products_model->get_products_by_id($pro_id);
					if($pros){
						$amount = $amount+$pros[0]['price'];
					}
				}
				
				$data = array(	
					'to_id' => $this->input->post('to_id'),	
					'products_id' => implode(',', $this->input->post('products_id')),	
					'amount' => $amount,		
					'note' => $this->input->post('note'),	
					'status' => $this->input->post('status'),	
					'tax' => $this->input->post('tax')?implode(',', $this->input->post('tax')):'',	
					'invoice_date' => format_date($this->input->post('estimate_date'),"Y-m-d"),	
					'due_date' => format_date($this->input->post('due_date'),"Y-m-d"),	
				);

				if($this->estimates_model->edit($this->input->post('update_id'), $data)){
					
					if($this->input->post('to_id')){
						$estimates = $this->estimates_model->get_estimates($this->input->post('update_id'));
						if(!empty($estimates)){
							if($estimates[0]['status'] == 1 && $this->input->post('status') != 0){
								$data = array(
									'notification' => $estimates[0]['estimate_id'],
									'type' => 'new_estimate',	
									'type_id' => $this->input->post('update_id'),	
									'from_id' => $this->session->userdata('user_id'),
									'to_id' => $this->input->post('to_id'),	
								);
								$notification_id = $this->notifications_model->create($data);
							}

							
							if($amount > 0 && $this->input->post('tax') && $this->input->post('tax') != ''){
								$total_tax_per = 0;
								if(is_numeric($this->input->post('tax'))){
									$taxes = get_tax($this->input->post('tax'));
									if($taxes){
										$total_tax_per = $total_tax_per+$taxes[0]['tax'];
									}
								}else{
									foreach($this->input->post('tax') as $tax_id){
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
							
							$template_data = array();
							$template_data['ESTIMATE_NUMBER'] = $estimates[0]['estimate_id'];
							$template_data['ESTIMATE_AMOUNT'] = round($amount);
							$template_data['ESTIMATE_DATE'] = format_date($this->input->post('estimate_date'),"Y-m-d");
							$template_data['ESTIMATE_DUE_DATE'] = format_date($this->input->post('due_date'),"Y-m-d");
							$template_data['ESTIMATE_URL'] = base_url('estimates');
							$email_template = render_email_template('new_estimate', $template_data);
							if($this->input->post('send_email_notification')){
								$to_user = $this->ion_auth->user($this->input->post('to_id'))->row();
								send_mail($to_user->email, $email_template[0]['subject'], $email_template[0]['message']);
							}
						}
					}

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
}
