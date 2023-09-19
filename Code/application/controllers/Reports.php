<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function attendance()
	{
		if($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('reports_view')) && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Attendance Report - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$query3 = $this->db->get('departments');
        	$this->data['departments'] = $query3->result_array();
			$this->load->view('report-attendance',$this->data);

		}else{
			redirect('auth', 'refresh');
		}
	}

	public function leaves()
	{
		if($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('reports_view')) && is_module_allowed('reports')){
			$this->data['page_title'] = 'Leaves - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->load->view('report-leaves',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function timesheet()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Timesheet - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			
			if( ($this->ion_auth->is_admin() || permissions('reports_view')) ){
				if(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user'])){
					$this->data['projects'] = $this->projects_model->get_projects($_GET['user']);
				}else{
					$this->data['projects'] = $this->projects_model->get_projects();
				}
			}else{
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}
			$this->load->view('report-timesheet',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function estimates()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Estimates - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			
            $this->data['system_clients'] = $this->ion_auth->users(4)->result();
			$this->load->view('report-estimates',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function leads()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Leads - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->load->view('report-leads',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function meetings()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Video Meetings - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('report-meetings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function clients()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Clients - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->load->view('report-clients',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function team()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Team Members - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->load->view('report-team',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function tasks()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			
			$this->data['page_title'] = 'Tasks - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();

			$this->data['projecr_users'] = $this->projects_model->get_project_users();

			$this->data['task_status'] = task_status();
			$this->data['task_priorities'] = priorities();

			$this->data['projects'] = $this->projects_model->get_projects();

			$this->load->view('report-tasks',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function projects()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports')){
			$this->data['page_title'] = 'Projects - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();

			$this->data['project_status'] = project_status();

			$this->data['projects_all'] = $this->projects_model->get_projects();

			$this->load->view('report-projects',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function expenses()
	{
		if ($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports'))
		{
			$this->data['page_title'] = 'Expenses - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('report-expenses',$this->data);

		}
	}

	public function get_expenses()
	{	
		if ($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view')) )
		{
			$results = $this->expenses_model->get_expenses_chart();
			$rows = array();
			$tempRow = array();
			if ($results)
			{
				if($this->input->get('from')){
					$from = format_date($this->input->get('from'),"Y-m-d");
				}else{
					$from = date("Y-m-d");
				}

				if($this->input->get('too') != ''){
					$too = format_date($this->input->get('too'),"Y-m-d");
				}else{
					$too = date("Y-m-d");
				}
				foreach ($results as $result) {
					if($result['date'] >= $from && $result['date'] <= $too){
						$tempRow = $result;
						$tempRow['date'] = format_date($result['date'],system_date_format());
						$rows[] = $tempRow;
					}
				}
		
				print_r(json_encode($rows));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	
	public function get_expenses_chart()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view')) )
		{
			if($this->input->post('from')){
				$from = new DateTime($this->input->post('from'));
			}else{
				$from = new DateTime();
			}

			if($this->input->post('too')){
				$too = new DateTime($this->input->post('too'));
			}else{
				$too = new DateTime();
			}

			$begin = $from;
			$end = $too;
			
			$end = $end->modify('+1 day');
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			$dates = array();
			$dates_formated = array();
			foreach ($period as $dt) {
				$dates[] = $dt->format("Y-m-d");
				$dates_formated[] = $dt->format(system_date_format());
			}
			
            $expenses = $this->expenses_model->get_expenses_chart();
			foreach($dates as $key => $date){
				if($expenses){
					$expens[$key] = 0;
					foreach($expenses as $expense){
						if($expense['date'] == $date){
							$expens[$key] = $expense['amount'];
						}
					}
				}else{
					$expens[$key] = 0;
				}
			}

			$this->data['dates'] = $dates_formated;
			$this->data['expenses'] = $expens;
			echo json_encode($this->data);
		}else{
			$this->data['dates'] = array();
			$this->data['expenses'] = array();
			echo json_encode($this->data);
		}
	}

	public function income()
	{
		if ($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports'))
		{
			$this->data['page_title'] = 'Income - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('report-income',$this->data);

		}
	}

	public function get_income($invoice_id = '')
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view')) )
		{
			$temp = array();
			$temprow = array();
            $invoices = $this->invoices_model->get_invoices($invoice_id, true);
			if($invoices){
				if($this->input->get('from')){
					$from = format_date($this->input->get('from'),"Y-m-d");
				}else{
					$from = date("Y-m-d");
				}
	
				if($this->input->get('too') != ''){
					$too = format_date($this->input->get('too'),"Y-m-d");
				}else{
					$too = date("Y-m-d");
				}
				foreach($invoices as $key => $invoice){

					if($invoice['payment_date'] >= $from && $invoice['payment_date'] <= $too){
						$temp = $invoice;
						$temp['invoice_id'] = '<a href="'.(base_url('invoices/view/'.$invoice['id'])).'" target="_blank"><strong>'.$invoice['invoice_id'].'</strong></a>';
						$temp['to_id'] = $invoice['to_user'];
						$temp['due_date'] = format_date($invoice['due_date'],system_date_format());
						$temp['payment_date'] = $invoice['payment_date']?format_date($invoice['payment_date'],system_date_format()):'';

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
							$temp['projects'] = $projects;
						}else{
							$temp['projects'] = $invoice['to_user'];
						}

						$temp['amount'] = round($amount);

						if($invoice['status'] == 0 || $invoice['status'] == 2){
							$temp['status'] = '<div class="badge badge-info">'.($this->lang->line('pending')?$this->lang->line('pending'):'Pending').'</div>';
						}elseif($invoice['status'] == 1){
							$temp['status'] = '<div class="badge badge-success">'.($this->lang->line('paid')?$this->lang->line('paid'):'Paid').'</div>';
						}elseif($invoice['status'] == 3){
							$temp['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
						}
						$temprow[] = $temp;
					}
				}
				return print_r(json_encode($temprow));
			}else{
				return '';
			}

		}else{
			return '';
		}
	}

	public function get_income_chart()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view')) )
		{
			if($this->input->post('from')){
				$from = new DateTime($this->input->post('from'));
			}else{
				$from = new DateTime();
			}

			if($this->input->post('too')){
				$too = new DateTime($this->input->post('too'));
			}else{
				$too = new DateTime();
			}

			$begin = $from;
			$end = $too;
			
			$end = $end->modify('+1 day');
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			$dates = array();
			$dates_formated = array();
			foreach ($period as $dt) {
				$dates[] = $dt->format("Y-m-d");
				$dates_formated[] = $dt->format(system_date_format());
			}
			
            $invoices = $this->invoices_model->get_invoices('', true);
			foreach($dates as $key => $date){
				
				if($invoices){
					$income[$key] = 0;
					foreach($invoices as $invoice){

						if($invoice['payment_date'] == $date){
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
							$income[$key] = round($amount);
						}	
					}
				}else{
					$income[$key] = 0;
				}
			}

			$this->data['dates'] = $dates_formated;
			$this->data['income'] = $income;
			echo json_encode($this->data);
		}else{
			$this->data['dates'] = array();
			$this->data['income'] = array();
			echo json_encode($this->data);
		}
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view'))  && is_module_allowed('reports'))
		{
			$this->data['page_title'] = 'Income VS Expenses - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('report-income-vs-expenses',$this->data);

		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_income_vs_expenses_chart()
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view')) )
		{
			if($this->input->post('from')){
				$from = new DateTime($this->input->post('from'));
			}else{
				$from = new DateTime();
			}

			if($this->input->post('too')){
				$too = new DateTime($this->input->post('too'));
			}else{
				$too = new DateTime();
			}

			$begin = $from;
			$end = $too;
			
			$end = $end->modify('+1 day');
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			$dates = array();
			$dates_formated = array();
			foreach ($period as $dt) {
				$dates[] = $dt->format("Y-m-d");
				$dates_formated[] = $dt->format(system_date_format());
			}
			
            $invoices = $this->invoices_model->get_invoices('', true);
            $expenses = $this->expenses_model->get_expenses_chart();
			foreach($dates as $key => $date){
				if($expenses){
					$expens[$key] = 0;
					foreach($expenses as $expense){
						if($expense['date'] == $date){
							$expens[$key] = $expense['amount'];
						}
					}
				}else{
					$expens[$key] = 0;
				}
				if($invoices){
					$income[$key] = 0;
					foreach($invoices as $invoice){
						if($invoice['payment_date'] == $date){
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
							$income[$key] = round($amount);
						}	
					}
				}else{
					$income[$key] = 0;
				}
			}

			$this->data['dates'] = $dates_formated;
			$this->data['income'] = $income;
			$this->data['expenses'] = $expens;
			echo json_encode($this->data);
		}else{
			$this->data['dates'] = array();
			$this->data['income'] = array();
			$this->data['expenses'] = array();
			echo json_encode($this->data);
		}
	}

	public function get_income_vs_expenses($send_array = false)
	{
		if($this->ion_auth->logged_in() &&  ($this->ion_auth->is_admin() || permissions('reports_view')) )
		{
			$get = $this->input->get();

			if(isset($get['from'])){
				$from = new DateTime($get['from']);
			}else{
				$from = new DateTime();
			}

			if(isset($get['too'])){
				$too = new DateTime($get['too']);
			}else{
				$too = new DateTime();
			}

			$begin = $from;
			$end = $too;
			$end = $end->modify('+1 day');
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			$dates = array();
			foreach ($period as $dt) {
				$dates[] = $dt->format("Y-m-d");
			}
			
			$temp = array();
			$rows = array();
            $invoices = $this->invoices_model->get_invoices('', true);
            $expenses = $this->expenses_model->get_expenses_chart();

			foreach($dates as $key =>  $date){
				if($expenses){
					$expens = 0;
					foreach($expenses as $expense){
						if($expense['date'] == $date){
							$expens += $expense['amount'];
						}
					}
				}else{
					$expens = 0;
				}
				if($invoices){
					$income = 0;
					foreach($invoices as $invoice){
						
						if($invoice['payment_date'] == $date){
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
							$income += round($amount);
						}	
					}
				}else{
					$income = 0;
				}
				$temp['date'] = format_date($date,system_date_format());
				$temp['income'] = $income;
				$temp['expenses'] = $expens;
				$temp['profit'] = $income - $expens;
				
                $rows[] = $temp;
			}
			if($send_array){
				return $rows;
			}else{
				return print_r(json_encode($rows));
			}
		}else{
			return '';
		}
	}

}
