<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function logins()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Social Login - '.company_name();
			$this->data['main_page'] = 'logins';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['google_client_id'] = get_google_client_id();
			$this->data['google_client_secret'] = get_google_client_secret();

			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	public function save_logins_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('google_client_id', 'google client id', 'trim|xss_clean');
			$this->form_validation->set_rules('google_client_secret', 'google client id', 'trim|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data_json = array(
					'google_client_id' => $this->input->post('google_client_id'),
					'google_client_secret' => $this->input->post('google_client_secret'),
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				$setting_type = 'logins';

				if($this->settings_model->save_settings($setting_type,$data)){
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

	public function seo()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'SEO - '.company_name();
			$this->data['main_page'] = 'seo';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['meta_title'] = get_mata_data('meta_title');
			$this->data['meta_description'] = get_mata_data('meta_description');
			$this->data['meta_keywords'] = get_mata_data('meta_keywords');

			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	public function save_seo_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('meta_title', 'meta title', 'trim|xss_clean');
			$this->form_validation->set_rules('meta_description', 'meta description', 'trim|xss_clean');
			$this->form_validation->set_rules('meta_keywords', 'meta keywords', 'trim|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data_json = array(
					'meta_title' => $this->input->post('meta_title'),
					'meta_description' => $this->input->post('meta_description'),
					'meta_keywords' => $this->input->post('meta_keywords'),
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				$setting_type = 'seo';

				if($this->settings_model->save_settings($setting_type,$data)){
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

	public function clear_cache()
	{	
		$cache_path = 'install';
		delete_files($cache_path, true);
		rmdir($cache_path);
		redirect('auth', 'refresh');
	}

	public function taxes()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(1) && is_module_allowed('taxes'))
		{
			$this->data['page_title'] = 'Taxes - '.company_name();
			$this->data['main_page'] = 'taxes';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	
	public function get_taxes($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$taxes = $this->settings_model->get_taxes($id);
			if($taxes){
				foreach($taxes as $key => $tax){
					$temp[$key] = $tax;
					$temp[$key]['action'] = $temp[$key]['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-success mr-1 edit_tax" data-id="'.$tax["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger delete_tax" data-id="'.$tax["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
				}
			}else{
				$temp= array();
			}

			return print_r(json_encode($temp));
			
		}else{
			return '';
		}
	}

	public function delete_taxes($id='')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->settings_model->delete_taxes($id)){

				$this->session->set_flashdata('message', $this->lang->line('tax_deleted_successfully')?$this->lang->line('tax_deleted_successfully'):"Tax deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('tax_deleted_successfully')?$this->lang->line('tax_deleted_successfully'):"Tax deleted successfully.";
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
	
	public function save_taxes_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			
			$this->form_validation->set_rules('title', 'Tax Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('tax', 'Tax Rate', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if($this->form_validation->run() == TRUE){
				if($this->input->post('update_id') && $this->input->post('update_id') != ''){
					$data = array(		
						'title' => $this->input->post('title'),		
						'tax' => $this->input->post('tax'),		
					);
					if($this->settings_model->update_taxes($this->input->post('update_id'),$data)){
						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('tax_updated_successfully')?$this->lang->line('tax_updated_successfully'):"Tax updated successfully.";
						echo json_encode($this->data); 
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
						echo json_encode($this->data);
					}
				}else{
					$data = array(
						'saas_id' => $this->session->userdata('saas_id'),		
						'title' => $this->input->post('title'),		
						'tax' => $this->input->post('tax'),		
					);
					if($this->settings_model->create_taxes($data)){
						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('tax_created_successfully')?$this->lang->line('tax_created_successfully'):"Tax created successfully.";
						echo json_encode($this->data); 
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
						echo json_encode($this->data);
					}
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

	public function company()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(1))
		{
			$this->data['page_title'] = 'Company Settings - '.company_name();
			$this->data['main_page'] = 'company';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['company_details'] = company_details();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function save_company_setting()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			if($this->ion_auth->in_group(4)){
				$setting_type = 'company_'.$this->session->userdata('user_id');
			}else{
				$setting_type = 'company_'.$this->session->userdata('saas_id');
			}

			$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == TRUE){

				$data_json = array(
					'company_name' => $this->input->post('company_name'),		
					'address' => $this->input->post('address'),		
					'city' => $this->input->post('city'),		
					'state' => $this->input->post('state'),		
					'country' => $this->input->post('country'),		
					'zip_code' => $this->input->post('zip_code'),		
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = $this->lang->line('company_setting_saved')?$this->lang->line('company_setting_saved'):"Company Setting Saved.";
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
	
	public function shift()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(1))
		{

			$this->data['page_title'] = 'Shift Settings - '.company_name();
			$this->data['main_page'] = 'shift';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			// $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function departments()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(1))
		{

			$this->data['page_title'] = 'Department Settings - '.company_name();
			$this->data['main_page'] = 'departments';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['system_users'] = $this->ion_auth->users(array(1,2,4))->result();
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function device_config()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(1))
		{

			$this->data['page_title'] = 'Device Settings - '.company_name();
			$this->data['main_page'] = 'device_config';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			// $this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$userRow = $this->attendance_model->get_user_by_active();
			// Store the fetched row in the 'system_users' key of the data array.
			$this->data['system_users'] = $userRow;
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function department()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(1))
		{

			$this->data['page_title'] = 'Timing Settings - '.company_name();
			$this->data['main_page'] = 'department';
			$query = $this->db->get('shift');
        	$this->data['shift_types'] = $query->result_array();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function save_department_setting()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			if($this->ion_auth->in_group(4)){
				$setting_type = 'department_';
			}else{
				$setting_type = 'department_';
			}
			
			$this->form_validation->set_rules('half_day_check_in', 'Check In', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('half_day_check_out', 'Check Out', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('type', 'Shift Type', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$shift_id = $this->input->post('type');
				$setting_type = 'department_'.$shift_id;

				$data_json = array(
						'half_day_check_in' => $this->input->post('half_day_check_in'),
						'half_day_check_out' => $this->input->post('half_day_check_out'),
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = $this->lang->line('company_setting_saved')?$this->lang->line('company_setting_saved'):"Department Setting Saved.";
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

    public function save_grace_minutes_setting()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			if($this->ion_auth->in_group(4)){
				$setting_type = 'grace_minutes_';
			}else{
				$setting_type = 'grace_minutes_';
			}
			
			$this->form_validation->set_rules('days_counter', 'Days Counter', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('grace_minutes', 'Grace Minutes', 'trim|required|strip_tags|xss_clean');

			$apply = '';
			if ($this->input->post('enableGraceMinutes')) {
				$apply = '1';
			}else{
				$apply = '0';
			}

			if($this->form_validation->run() == TRUE){

				$data_json = array(
						'apply' => $apply,
						'days_counter' => $this->input->post('days_counter'),
						'grace_minutes' => $this->input->post('grace_minutes'),
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = $this->lang->line('company_setting_saved')?$this->lang->line('company_setting_saved'):"Department Setting Saved.";
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
	
	public function get_department_time()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$type = $this->input->post('shift_type');
			$report = $this->settings_model->get_department_time($type);
			echo json_encode($report);	
		} else {
			return '';
		}
	}
	
	public function get_grace_minutes()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)))
		{
			$report = $this->settings_model->get_grace_minutes();
			echo json_encode($report);	
		} else {
			return '';
		}
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'general';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['timezones'] = timezones();
			$this->data['time_formats'] = time_formats();
			$this->data['date_formats'] = date_formats();

			$this->data['company_name'] = company_name();
			$this->data['company_email'] = company_email();
			
			if($this->ion_auth->in_group(3)){
				$this->data['currency_code'] = get_saas_currency('currency_code');
				$this->data['currency_symbol'] = get_saas_currency('currency_symbol');
			}else{
				$this->data['currency_code'] = get_currency('currency_code');
				$this->data['currency_symbol'] = get_currency('currency_symbol');
			}

			$this->data['footer_text'] = footer_text();
			$this->data['google_analytics'] = google_analytics();
			$this->data['mysql_timezone'] = mysql_timezone();
			$this->data['php_timezone'] = php_timezone();
			$this->data['date_format'] = system_date_format();
			$this->data['time_format'] = system_time_format();
			$this->data['file_upload_format'] = file_upload_format();
			$this->data['date_format_js'] = system_date_format_js();
			$this->data['time_format_js'] = system_time_format_js();
			$this->data['full_logo'] = full_logo();
			$this->data['half_logo'] = half_logo();
			$this->data['favicon'] = favicon();
			$this->data['alert_days'] = alert_days();
			$this->data['default_language'] = default_language();
			$this->data['email_activation'] = email_activation();
			$this->data['theme_color'] = theme_color();
			$this->data['turn_off_new_user_registration'] = turn_off_new_user_registration();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	public function migrate()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->load->library('migration');
			$this->migration->latest();
			redirect('settings/update', 'refresh');

		}else{
			redirect('auth', 'refresh');
		}
	}

	public function update()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'update';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function recaptcha()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'recaptcha';
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->data['site_key'] = get_google_recaptcha_site_key();
			$this->data['secret_key'] = get_google_recaptcha_secret_key();

			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	public function save_recaptcha_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('site_key', 'site key', 'trim|xss_clean');
			$this->form_validation->set_rules('secret_key', 'secret key', 'trim|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data_json = array(
					'site_key' => $this->input->post('site_key'),
					'secret_key' => $this->input->post('secret_key'),
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				$setting_type = 'recaptcha';

				if($this->settings_model->save_settings($setting_type,$data)){
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

	public function payment()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('payment_gateway') && ($this->ion_auth->in_group(1) || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'payment';
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->ion_auth->in_group(3)){
				$this->data['paypal_client_id'] = get_payment_paypal();
				$this->data['paypal_secret'] = get_paypal_secret();
				$this->data['stripe_publishable_key'] = get_stripe_publishable_key();
				$this->data['stripe_secret_key'] = get_stripe_secret_key();
				$this->data['razorpay_key_id'] = get_razorpay_key_id();
				$this->data['razorpay_key_secret'] = get_razorpay_key_secret();
				$this->data['paystack_public_key'] = get_paystack_public_key();
				$this->data['paystack_secret_key'] = get_paystack_secret_key();
				$this->data['offline_bank_transfer'] = get_offline_bank_transfer();
				$this->data['bank_details'] = get_bank_details();
			}else{
				$this->data['paypal_client_id'] = get_payment_paypal(true);
				$this->data['paypal_secret'] = get_paypal_secret(true);
				$this->data['stripe_publishable_key'] = get_stripe_publishable_key(true);
				$this->data['stripe_secret_key'] = get_stripe_secret_key(true);
				$this->data['razorpay_key_id'] = get_razorpay_key_id(true);
				$this->data['razorpay_key_secret'] = get_razorpay_key_secret(true);
				$this->data['paystack_public_key'] = get_paystack_public_key(true);
				$this->data['paystack_secret_key'] = get_paystack_secret_key(true);
				$this->data['offline_bank_transfer'] = get_offline_bank_transfer(true);
				$this->data['bank_details'] = get_bank_details(true);
			}

			$this->load->view('settings',$this->data);

		}else{
			redirect('auth', 'refresh');
		}
	}

	public function save_payment_setting()
	{
		
		if ($this->ion_auth->logged_in() && ($this->ion_auth->in_group(3) || $this->ion_auth->in_group(1)))
		{

			$data_json = array();
			$data_json['paypal_client_id'] = $this->input->post('paypal_client_id')?$this->input->post('paypal_client_id'):'';
			$data_json['paypal_secret'] = $this->input->post('paypal_secret')?$this->input->post('paypal_secret'):'';
			$data_json['stripe_publishable_key'] = $this->input->post('stripe_publishable_key')?$this->input->post('stripe_publishable_key'):'';
			$data_json['stripe_secret_key'] = $this->input->post('stripe_secret_key')?$this->input->post('stripe_secret_key'):'';
			$data_json['razorpay_key_id'] = $this->input->post('razorpay_key_id')?$this->input->post('razorpay_key_id'):'';
			$data_json['razorpay_key_secret'] = $this->input->post('razorpay_key_secret')?$this->input->post('razorpay_key_secret'):'';
			$data_json['paystack_public_key'] = $this->input->post('paystack_public_key')?$this->input->post('paystack_public_key'):'';
			$data_json['paystack_secret_key'] = $this->input->post('paystack_secret_key')?$this->input->post('paystack_secret_key'):'';
			$data_json['offline_bank_transfer'] = $this->input->post('offline_bank_transfer') != ''?1:'';

			$data_json['bank_details'] = $this->input->post('bank_details') != ''?$this->input->post('bank_details'):'';
			
			$data = array(
				'value' => json_encode($data_json)
			);

			if($this->ion_auth->in_group(3)){
				$setting_type = 'payment';
			}else{
				$setting_type = 'payment_'.$this->session->userdata('saas_id');
			}
			
			if($this->settings_model->save_settings($setting_type,$data)){
				$this->data['error'] = false;
				$this->data['data'] = $data_json;
				$this->data['message'] = $this->lang->line('payment_setting_saved')?$this->lang->line('payment_setting_saved'):"Payment Setting Saved.";
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

	public function save_front_setting()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			$data_json = array();
			$data_json['theme_name'] = $this->input->post('theme_name');
			$data_json['landing_page'] = $this->input->post('landing_page') != ''?1:0;
			$data_json['home'] = $this->input->post('home') != ''?1:0;
			$data_json['features'] = $this->input->post('features') != ''?1:0;
			$data_json['subscription_plans'] = $this->input->post('subscription_plans') != ''?1:0;
			$data_json['contact'] = $this->input->post('contact') != ''?1:0;
			$data_json['about'] = $this->input->post('about') != ''?1:0;
			$data_json['privacy'] = $this->input->post('privacy') != ''?1:0;
			$data_json['terms'] = $this->input->post('terms') != ''?1:0;

			$data = array(
				'value' => json_encode($data_json)
			);
			$setting_type = 'frontend';
			
			if($this->settings_model->save_settings($setting_type,$data)){
				$this->data['error'] = false;
				$this->data['data'] = $data_json;
				$this->data['message'] = $this->lang->line('frontend_setting_saved')?$this->lang->line('frontend_setting_saved'):"Frontend Setting Saved.";
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

	public function save_update_setting()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{		
				$upload_path = 'update';
				if(!is_dir($upload_path)){
					mkdir($upload_path,0775,true);
				}

				$config['upload_path']          = $upload_path;
				$config['allowed_types']        = 'zip';
				$config['overwrite']             = true;

				$this->load->library('upload', $config);
				if (!empty($_FILES['update']['name']) && ($_FILES['update']['name'] == 'update.zip' || $_FILES['update']['name'] == 'additional.zip')){

					if ($this->upload->do_upload('update')){
							$update_data = $this->upload->data();

							$zip = new ZipArchive;
							if ($zip->open($update_data['full_path']) === TRUE) 
							{
								if($zip->extractTo($upload_path)){
									$zip->close();
									if(is_dir($upload_path) && is_dir($upload_path.'/files') && file_exists($upload_path."/version.txt") && file_exists($upload_path.'/validate.txt')){

										$version = file_get_contents($upload_path."/version.txt");
										$validate = file_get_contents($upload_path.'/validate.txt');
										if($version && $validate == 'hhmsbbhmrs'){

											recurse_copy($upload_path.'/files', './');
												
											if(is_numeric($version)){
												$data = array(
													'value' => $version
												);
												$this->settings_model->save_settings('system_version',$data);
											}

											delete_files($upload_path, true);
											rmdir($upload_path);

											$this->session->set_flashdata('message', $this->lang->line('system_updated_successfully')?$this->lang->line('system_updated_successfully'):"System updated successfully.");
											$this->session->set_flashdata('message_type', 'success');

											$this->data['error'] = false;
											$this->data['message'] = $this->lang->line('system_updated_successfully')?$this->lang->line('system_updated_successfully'):"System updated successfully.";
											echo json_encode($this->data); 

										}else{
											$this->data['error'] = true;
											$this->data['message'] = $this->lang->line('wrong_update_file_is_selected')?$this->lang->line('wrong_update_file_is_selected'):"Wrong update file is selected.";
											echo json_encode($this->data); 
											return false;
										}
										
									}else{
										
										$this->data['error'] = true;
										$this->data['message'] = $this->lang->line('select_valid_zip_file')?$this->lang->line('select_valid_zip_file'):"Select valid zip file.";
										echo json_encode($this->data); 
										return false;
									}
								}else{
									$this->data['error'] = true;
									$this->data['message'] = $this->lang->line('error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later')?$this->lang->line('error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later'):"Error occured during file extracting. Select valid zip file OR Please Try again later.";
									echo json_encode($this->data); 
									return false;
								}
							}else{
								
								$this->data['error'] = true;
								$this->data['message'] = $this->lang->line('error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later')?$this->lang->line('error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later'):"Error occured during file uploading. Select valid zip file OR Please Try again later.";
								echo json_encode($this->data); 
								return false;
							}
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
					
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('select_valid_zip_file')?$this->lang->line('select_valid_zip_file'):"Select valid zip file.";
					echo json_encode($this->data); 
					return false;
				}
		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function user_permissions()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('user_permissions'))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'permissions';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['permissions'] = permissions();
			$this->data['clients_permissions'] = clients_permissions();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function custom_code()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Custom Code - '.company_name();
			$this->data['main_page'] = 'custom-code';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['header_code'] = get_header_code();
			$this->data['footer_code'] = get_footer_code();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function save_custom_code_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
				$data_json = array(
					'header_code' => $this->input->post('header_code'),
					'footer_code' => $this->input->post('footer_code'),
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				$setting_type = 'custom_code';

				if($this->settings_model->save_settings($setting_type,$data)){
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
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function email_templates()
	{ 
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'email-templates';
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->data['email_templates'] = $this->settings_model->get_email_templates();
			if($this->uri->segment(3)){
				$this->data['template'] = $this->settings_model->get_email_templates($this->uri->segment(3));
			}else{
				$this->data['template'] = $this->settings_model->get_email_templates('new_user_registration');
			}

			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function save_email_templates_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('subject', 'Subject', 'required');
			$this->form_validation->set_rules('message', 'Message', 'required');

			if($this->form_validation->run() == TRUE){

				$data = array(
					'subject' => $this->input->post('subject'),
					'message' => $this->input->post('message'),	
				);

				if($this->settings_model->update_email_templates($this->input->post('name'),$data)){
				    
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('email_setting_saved')?$this->lang->line('email_setting_saved'):"Email Setting Saved.";
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

	public function email()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'email';
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->data['smtp_host'] = smtp_host();
			$this->data['smtp_port'] = smtp_port();
			$this->data['smtp_username'] = smtp_email();
			$this->data['smtp_password'] = smtp_password();
			$this->data['smtp_encryption'] = smtp_encryption();
			$this->data['email_library'] = get_email_library();
			$this->data['from_email'] = from_email();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

    public function save_permission_setting()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
				$data_json = array(
					'project_view' => $this->input->post('project_view') != ''?1:0,
					'project_create' => $this->input->post('project_create') != ''?1:0,
					'project_edit' => $this->input->post('project_edit') != ''?1:0,
					'project_delete' => $this->input->post('project_delete') != ''?1:0,
					'task_view' => $this->input->post('task_view') != ''?1:0,
					'task_create' => $this->input->post('task_create') != ''?1:0,
					'task_edit' => $this->input->post('task_edit') != ''?1:0,
					'task_delete' => $this->input->post('task_delete') != ''?1:0,
					'user_view' => $this->input->post('user_view') != ''?1:0,
					'client_view' => $this->input->post('client_view') != ''?1:0,
					'setting_view' => $this->input->post('setting_view') != ''?1:0,
					'setting_update' => $this->input->post('setting_update') != ''?1:0,
					'todo_view' => $this->input->post('todo_view') != ''?1:0,
					'notes_view' => $this->input->post('notes_view') != ''?1:0,
					'chat_view' => $this->input->post('chat_view') != ''?1:0,
					'chat_delete' => $this->input->post('chat_delete') != ''?1:0,
					'team_members_and_client_can_chat' => $this->input->post('team_members_and_client_can_chat') != ''?1:0,
					'task_status' => $this->input->post('task_status') != ''?1:0,
					'project_budget' => $this->input->post('project_budget') != ''?1:0,
					'gantt_view' => $this->input->post('gantt_view') != ''?1:0,
					'gantt_edit' => $this->input->post('gantt_edit') != ''?1:0,
					'calendar_view' => $this->input->post('calendar_view') != ''?1:0,
					'meetings_view' => $this->input->post('meetings_view') != ''?1:0,
					'meetings_create' => $this->input->post('meetings_create') != ''?1:0,
					'meetings_edit' => $this->input->post('meetings_edit') != ''?1:0,
					'meetings_delete' => $this->input->post('meetings_delete') != ''?1:0,
					'lead_view' => $this->input->post('lead_view') != ''?1:0,
					'lead_create' => $this->input->post('lead_create') != ''?1:0,
					'lead_edit' => $this->input->post('lead_edit') != ''?1:0,
					'lead_delete' => $this->input->post('lead_delete') != ''?1:0,
				);

				$data = array(
					'value' => json_encode($data_json)
				);
				$setting_type = 'permissions';
				if(!$this->ion_auth->in_group(3)){
					$setting_type = 'permissions_'.$this->session->userdata('saas_id');
				}

				$client_data_json = array(
					'project_view' => $this->input->post('client_project_view') != ''?1:0,
					'project_create' => $this->input->post('client_project_create') != ''?1:0,
					'project_edit' => $this->input->post('client_project_edit') != ''?1:0,
					'project_delete' => $this->input->post('client_project_delete') != ''?1:0,
					'task_view' => $this->input->post('client_task_view') != ''?1:0,
					'task_create' => $this->input->post('client_task_create') != ''?1:0,
					'task_edit' => $this->input->post('client_task_edit') != ''?1:0,
					'task_delete' => $this->input->post('client_task_delete') != ''?1:0,
					'user_view' => $this->input->post('client_user_view') != ''?1:0,
					'client_view' => $this->input->post('client_client_view') != ''?1:0,
					'setting_view' => $this->input->post('client_setting_view') != ''?1:0,
					'setting_update' => $this->input->post('client_setting_update') != ''?1:0,
					'todo_view' => $this->input->post('client_todo_view') != ''?1:0,
					'notes_view' => $this->input->post('client_notes_view') != ''?1:0,
					'chat_view' => $this->input->post('client_chat_view') != ''?1:0,
					'chat_delete' => $this->input->post('client_chat_delete') != ''?1:0,
					'team_members_and_client_can_chat' => $this->input->post('team_members_and_client_can_chat') != ''?1:0,
					'task_status' => $this->input->post('client_task_status') != ''?1:0,
					'project_budget' => $this->input->post('client_project_budget') != ''?1:0,
					'gantt_view' => $this->input->post('client_gantt_view') != ''?1:0,
					'gantt_edit' => $this->input->post('client_gantt_edit') != ''?1:0,
					'calendar_view' => $this->input->post('client_calendar_view') != ''?1:0,
					'lead_view' => $this->input->post('client_lead_view') != ''?1:0,
					'lead_create' => $this->input->post('client_lead_create') != ''?1:0,
					'lead_edit' => $this->input->post('client_lead_edit') != ''?1:0,
					'lead_delete' => $this->input->post('client_lead_delete') != ''?1:0,
				);

				$client_data = array(
					'value' => json_encode($client_data_json)
				);

				$client_setting_type = 'clients_permissions';
				if(!$this->ion_auth->in_group(3)){
					$client_setting_type = 'clients_permissions_'.$this->session->userdata('saas_id');
				}

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->settings_model->save_settings($client_setting_type,$client_data);
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = $this->lang->line('permissions_setting_saved')?$this->lang->line('permissions_setting_saved'):"Permissions Setting Saved.";
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
	
	public function save_permissions_setting()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$roles = $this->settings_model->get_roles_permissions();
			foreach ($roles as $role) {
				$data_json = array(
					'attendance_view' => $this->input->post($role['name'].'_attendance_view') != ''?1:0,
					'attendance_view_all' => $this->input->post($role['name'].'_attendance_view_all') != ''?1:0,
					'leaves_view' => $this->input->post($role['name'].'_leaves_view') != ''?1:0,
					'leaves_create' => $this->input->post($role['name'].'_leaves_create') != ''?1:0,
					'leaves_edit' => $this->input->post($role['name'].'_leaves_edit') != ''?1:0,
					'leaves_delete' => $this->input->post($role['name'].'_leaves_delete') != ''?1:0,
					'leaves_status' => $this->input->post($role['name'].'_leaves_status') != ''?1:0,
					'leaves_view_all' => $this->input->post($role['name'].'_leaves_view_all') != ''?1:0,
					'biometric_request_view' => $this->input->post($role['name'].'_biometric_request_view') != ''?1:0,
					'biometric_request_create' => $this->input->post($role['name'].'_biometric_request_create') != ''?1:0,
					'biometric_request_edit' => $this->input->post($role['name'].'_biometric_request_edit') != ''?1:0,
					'biometric_request_delete' => $this->input->post($role['name'].'_biometric_request_delete') != ''?1:0,
					'biometric_request_status' => $this->input->post($role['name'].'_biometric_request_status') != ''?1:0,
					'biometric_request_view_all' => $this->input->post($role['name'].'_biometric_request_view_all') != ''?1:0,
					'project_view' => $this->input->post($role['name'].'_project_view') != ''?1:0,
					'project_create' => $this->input->post($role['name'].'_project_create') != ''?1:0,
					'project_edit' => $this->input->post($role['name'].'_project_edit') != ''?1:0,
					'project_delete' => $this->input->post($role['name'].'_project_delete') != ''?1:0,
					'project_view_all' => $this->input->post($role['name'].'_project_view_all') != ''?1:0,
					'task_view' => $this->input->post($role['name'].'_task_view') != ''?1:0,
					'task_create' => $this->input->post($role['name'].'_task_create') != ''?1:0,
					'task_edit' => $this->input->post($role['name'].'_task_edit') != ''?1:0,
					'task_delete' => $this->input->post($role['name'].'_task_delete') != ''?1:0,
					'task_status' => $this->input->post($role['name'].'_task_status') != ''?1:0,
					'task_view_all' => $this->input->post($role['name'].'_task_view_all') != ''?1:0,
					'device_view' => $this->input->post($role['name'].'_device_view') != ''?1:0,
					'device_create' => $this->input->post($role['name'].'_device_create') != ''?1:0,
					'device_edit' => $this->input->post($role['name'].'_device_edit') != ''?1:0,
					'device_delete' => $this->input->post($role['name'].'_device_delete') != ''?1:0,
					'departments_view' => $this->input->post($role['name'].'_departments_view') != ''?1:0,
					'departments_create' => $this->input->post($role['name'].'_departments_create') != ''?1:0,
					'departments_edit' => $this->input->post($role['name'].'_departments_edit') != ''?1:0,
					'departments_delete' => $this->input->post($role['name'].'_departments_delete') != ''?1:0,
					'shift_view' => $this->input->post($role['name'].'_shift_view') != ''?1:0,
					'shift_create' => $this->input->post($role['name'].'_shift_create') != ''?1:0,
					'shift_edit' => $this->input->post($role['name'].'_shift_edit') != ''?1:0,
					'shift_delete' => $this->input->post($role['name'].'_shift_delete') != ''?1:0,
					'plan_holiday_view' => $this->input->post($role['name'].'_plan_holiday_view') != ''?1:0,
					'plan_holiday_create' => $this->input->post($role['name'].'_plan_holiday_create') != ''?1:0,
					'plan_holiday_edit' => $this->input->post($role['name'].'_plan_holiday_edit') != ''?1:0,
					'plan_holiday_delete' => $this->input->post($role['name'].'_plan_holiday_delete') != ''?1:0,
					'time_schedule_view' => $this->input->post($role['name'].'_time_schedule_view') != ''?1:0,
					'time_schedule_edit' => $this->input->post($role['name'].'_time_schedule_edit') != ''?1:0,
					'user_view' => $this->input->post($role['name'].'_user_view') != ''?1:0,
					'user_edit' => $this->input->post($role['name'].'_user_edit') != ''?1:0,
					'client_view' => $this->input->post($role['name'].'_client_view') != ''?1:0,
					'setting_view' => $this->input->post($role['name'].'_setting_view') != ''?1:0,
					'setting_update' => $this->input->post($role['name'].'_setting_update') != ''?1:0,
					'todo_view' => $this->input->post($role['name'].'_todo_view') != ''?1:0,
					'notes_view' => $this->input->post($role['name'].'_notes_view') != ''?1:0,
					'chat_view' => $this->input->post($role['name'].'_chat_view') != ''?1:0,
					'chat_delete' => $this->input->post($role['name'].'_chat_delete') != ''?1:0,
					'project_budget' => $this->input->post($role['name'].'_project_budget') != ''?1:0,
					'gantt_view' => $this->input->post($role['name'].'_gantt_view') != ''?1:0,
					'gantt_edit' => $this->input->post($role['name'].'_gantt_edit') != ''?1:0,
					'calendar_view' => $this->input->post($role['name'].'_calendar_view') != ''?1:0,
					'meetings_view' => $this->input->post($role['name'].'_meetings_view') != ''?1:0,
					'meetings_create' => $this->input->post($role['name'].'_meetings_create') != ''?1:0,
					'meetings_edit' => $this->input->post($role['name'].'_meetings_edit') != ''?1:0,
					'meetings_delete' => $this->input->post($role['name'].'_meetings_delete') != ''?1:0,
					'lead_view' => $this->input->post($role['name'].'_lead_view') != ''?1:0,
					'lead_create' => $this->input->post($role['name'].'_lead_create') != ''?1:0,
					'lead_edit' => $this->input->post($role['name'].'_lead_edit') != ''?1:0,
					'lead_delete' => $this->input->post($role['name'].'_lead_delete') != ''?1:0,
					'attendance_view_selected' => $this->input->post($role['name'].'_attendance_view_selected') != ''?1:0,
					'leaves_view_selected' => $this->input->post($role['name'].'_leaves_view_selected') != ''?1:0,
					'project_view_selected' => $this->input->post($role['name'].'_project_view_selected') != ''?1:0,
					'reports_view' => $this->input->post($role['name'].'_reports_view') != ''?1:0,
					'client_create' => $this->input->post($role['name'].'_client_create') != ''?1:0,
					'client_edit' => $this->input->post($role['name'].'_client_edit') != ''?1:0,
					'client_delete' => $this->input->post($role['name'].'_client_delete') != ''?1:0,
					'user_create' => $this->input->post($role['name'].'_user_create') != ''?1:0,
					'user_view_selected' => $this->input->post($role['name'].'_user_view_selected') != ''?1:0,
					'user_delete' => $this->input->post($role['name'].'_user_delete') != ''?1:0,
					'user_view_all' => $this->input->post($role['name'].'_user_view_all') != ''?1:0,
					'leave_type_view' => $this->input->post($role['name'].'_leave_type_view') != ''?1:0,
					'leave_type_create' => $this->input->post($role['name'].'_leave_type_create') != ''?1:0,
					'leave_type_edit' => $this->input->post($role['name'].'_leave_type_edit') != ''?1:0,
					'leave_type_delete' => $this->input->post($role['name'].'_leave_type_delete') != ''?1:0,
					'general_view' => $this->input->post($role['name'].'_general_view') != ''?1:0,
					'company_view' => $this->input->post($role['name'].'_company_view') != ''?1:0,
					'support_view' => $this->input->post($role['name'].'_support_view') != ''?1:0,
					'team_members_and_client_can_chat' => $this->input->post('team_members_and_client_can_chat') != ''?1:0,
				);

				$data = array(
					'value' => json_encode($data_json)
				);
				$setting_type = $role['name'].'_permissions';
				if(!$this->ion_auth->in_group(3)){
					$setting_type = $role['name'].'_permissions_'.$this->session->userdata('saas_id');
				}

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = $this->lang->line('permissions_setting_saved')?$this->lang->line('permissions_setting_saved'):"Permissions Setting Saved.";
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				}
				
			}
			echo json_encode($this->data);
		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function save_email_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			$setting_type = 'email';
			$this->form_validation->set_rules('smtp_host', 'SMTP Host', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_port', 'SMTP Port', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_username', 'Username', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_password', 'Password', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_encryption', 'Encryption', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('email_library', 'email library', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('from_email', 'from email', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$template_path 	= 'assets/templates/email.php';
                    
        		$output_path 	= 'application/config/email.php';
        
        		$email_file = file_get_contents($template_path);

        		if($this->input->post('smtp_encryption') == 'none'){
				     $smtp_encryption = $this->input->post('smtp_encryption');
				}else{
				     $smtp_encryption = $this->input->post('smtp_encryption').'://'.$this->input->post('smtp_host');
				}
				
        		$new  = str_replace("%SMTP_HOST%",$smtp_encryption,$email_file);
        		$new  = str_replace("%SMTP_PORT%",$this->input->post('smtp_port'),$new);
        		$new  = str_replace("%SMTP_USER%",$this->input->post('smtp_username'),$new);
        		$new  = str_replace("%SMTP_PASS%",$this->input->post('smtp_password'),$new);
        
        		if(!write_file($output_path, $new)){
        			$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
					return false;
        		} 

				$data_json = array(
					'smtp_host' => $this->input->post('smtp_host'),
					'smtp_port' => $this->input->post('smtp_port'),
					'smtp_username' => $this->input->post('smtp_username'),
					'smtp_password' => $this->input->post('smtp_password'),
					'smtp_encryption' => $this->input->post('smtp_encryption'),	
					'email_library' => $this->input->post('email_library'),	
					'from_email' => $this->input->post('from_email'),	
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				if(!$this->ion_auth->in_group(3)){
					$setting_type = 'email_'.$this->session->userdata('saas_id');
				}

				if($this->settings_model->save_settings($setting_type,$data)){
				    
				    if($this->input->post('email')){  
            			$body = "<html>
            				<body>
            					<p>SMTP is perfectly configured.</p>
            					<p>Go To your workspace <a href='".base_url()."'>Click Here</a></p>
            				</body>
            			</html>";
						send_mail($this->input->post('email'),'Testing SMTP',$body);
				    }
				    
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = $this->lang->line('email_setting_saved')?$this->lang->line('email_setting_saved'):"Email Setting Saved.";
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

	public function save_general_setting()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{

			$setting_type = 'general';
			if($this->ion_auth->in_group(3)){
				$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('footer_text', 'Footer Text', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('google_analytics', 'Google Analytics', 'trim|strip_tags|xss_clean');
				$this->form_validation->set_rules('alert_days', 'Alert Days', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('currency_code', 'Currency Code', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('theme_color', 'Theme Color', 'trim|required|strip_tags|xss_clean');
			}

			$this->form_validation->set_rules('default_language', 'Default Language', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('mysql_timezone', 'Timezone', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('php_timezone', 'Timezone', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('date_format', 'Date Format', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('time_format', 'Time Format', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('file_upload_format', 'File Upload Format', 'trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == TRUE){

				if($this->ion_auth->in_group(3)){
					$upload_path = 'assets/uploads/logos/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}

					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = 'gif|jpg|png|ico';
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$this->load->library('upload', $config);
					if (!empty($_FILES['full_logo']['name'])){
						if ($this->upload->do_upload('full_logo')){
								$full_logo = $this->upload->data('file_name');
								if($this->input->post('full_logo_old')){
									$unlink_path = $upload_path.''.$this->input->post('full_logo_old');
									unlink($unlink_path);
								}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data); 
							return false;
						}
					}else{
						$full_logo = $this->input->post('full_logo_old');
					}

					if (!empty($_FILES['half_logo']['name'])){
						if ($this->upload->do_upload('half_logo')){
								$half_logo = $this->upload->data('file_name');
								if($this->input->post('half_logo_old')){
									$unlink_path = $upload_path.''.$this->input->post('half_logo_old');
									unlink($unlink_path);
								}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data);  
							return false;
						}
					}else{
						$half_logo = $this->input->post('half_logo_old');
					}

					if (!empty($_FILES['favicon']['name'])){
						if ($this->upload->do_upload('favicon')){
							$favicon = $this->upload->data('file_name');
							if($this->input->post('favicon_old')){
								$unlink_path = $upload_path.''.$this->input->post('favicon_old');
								unlink($unlink_path);
							}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data);  
							return false;
						}
					}else{
						$favicon = $this->input->post('favicon_old');
					}

					$data_json = array(
						'company_name' => $this->input->post('company_name'),
						'footer_text' => $this->input->post('footer_text'),
						'currency_code' => $this->input->post('currency_code'),
						'currency_symbol' => $this->input->post('currency_symbol'),
						'google_analytics' => $this->input->post('google_analytics'),
						'mysql_timezone' => !empty($this->input->post('mysql_timezone') && $this->input->post('mysql_timezone') == '00:00')?'+'.$this->input->post('mysql_timezone'):$this->input->post('mysql_timezone'),
						'php_timezone' => $this->input->post('php_timezone'),
						'date_format' => $this->input->post('date_format'),
						'time_format' => $this->input->post('time_format'),	
						'date_format_js' => $this->input->post('date_format_js'),
						'time_format_js' => $this->input->post('time_format_js'),		
						'file_upload_format' => $this->input->post('file_upload_format'),		
						'alert_days' => $this->input->post('alert_days'),		
						'full_logo' => $full_logo,		
						'half_logo' => $half_logo,		
						'favicon' => $favicon,			
						'default_language' => $this->input->post('default_language'),
						'email_activation' => $this->input->post('email_activation'),
						'theme_color' => $this->input->post('theme_color'),
						'turn_off_new_user_registration' => $this->input->post('turn_off_new_user_registration'),
					);
				}else{

					$setting_type = 'general_'.$this->session->userdata('saas_id');

					$data_json = array(
						'mysql_timezone' => !empty($this->input->post('mysql_timezone') && $this->input->post('mysql_timezone') == '00:00')?'+'.$this->input->post('mysql_timezone'):$this->input->post('mysql_timezone'),
						'currency_code' => $this->input->post('currency_code'),
						'currency_symbol' => $this->input->post('currency_symbol'),
						'php_timezone' => $this->input->post('php_timezone'),
						'date_format' => $this->input->post('date_format'),
						'time_format' => $this->input->post('time_format'),	
						'date_format_js' => $this->input->post('date_format_js'),
						'time_format_js' => $this->input->post('time_format_js'),		
						'file_upload_format' => $this->input->post('file_upload_format'),
						'alert_days' => $this->input->post('alert_days'),		
						'default_language' => $this->input->post('default_language'),	
					);
				}
				$data = array(
					'value' => json_encode($data_json)
				);

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = $this->lang->line('general_setting_saved')?$this->lang->line('general_setting_saved'):"General Setting Saved.";
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
		
	public function roles()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('user_permissions'))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'roles';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$userRow = $this->attendance_model->get_user_by_active();
			$this->data['system_users'] = $userRow;
			$this->data['permissions'] = permissions();
			$query = $this->db->get('permissions_list');
        	$this->data['permissions_list'] = $query->result_array();
			// $this->data['roles'] = get_roles_permissions();
			$this->data['clients_permissions'] = clients_permissions();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function roles_permissions()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && is_module_allowed('user_permissions'))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'roles_permissions';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['permissions'] = permissions();
			$this->data['roles'] = $this->settings_model->get_roles_permissions();
			$roles = $this->settings_model->get_roles_permissions();
			foreach ($roles as $role) {
				$this->data[$role['name'].'_permissions'] = get_permissions($role['name']);
			}
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function roles_create()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('name', 'Type', 'trim|required|strip_tags|xss_clean|unique_name');
			$this->form_validation->set_rules('description', 'Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('descriptive_name', 'Description', 'trim|strip_tags|xss_clean');

			$permissions = $this->input->post('permissions');
			$permissions=json_encode($permissions);
			$users = $this->input->post('users');
			$users=json_encode($users);

			if($this->form_validation->run() == TRUE){
				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),	
					'name' => $this->input->post('name'),	
					'description' => $this->input->post('description'),	
					'descriptive_name' => $this->input->post('descriptive_name'),	
					'permissions' => $permissions,	
					'assigned_users' => $users,
				);
				
				$id = $this->settings_model->roles_create($data);
				
				$data_json = array(
					'project_view' => 0,
					'project_create' => 0,
					'project_edit' => 0,
					'project_delete' => 0,
					'task_view' => 0,
					'task_create' => 0,
					'task_edit' => 0,
					'task_delete' => 0,
					'user_view' => 0,
					'user_edit' => 0,
					'client_view' => 0,
					'setting_view' => 0,
					'setting_update' => 0,
					'todo_view' => 0,
					'notes_view' => 0,
					'chat_view' => 0,
					'chat_delete' => 0,
					'team_members_and_client_can_chat' => 0,
					'task_status' => 0,
					'project_budget' => 0,
					'gantt_view' => 0,
					'gantt_edit' => 0,
					'calendar_view' => 0,
					'meetings_view' => 0,
					'meetings_create' => 0,
					'meetings_edit' => 0,
					'meetings_delete' => 0,
					'lead_view' => 0,
					'lead_create' => 0,
					'lead_edit' => 0,
					'lead_delete' => 0,
				);

				$data = array(
					'value' => json_encode($data_json)
				);
				$setting_type = 'permissions';
				if(!$this->ion_auth->in_group(3)){
					$setting_type = $this->input->post('name').'_permissions_'.$this->session->userdata('saas_id');
				}

				$this->settings_model->save_settings($setting_type,$data);

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


	public function roles_delete($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() )
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			if(!empty($id) && is_numeric($id) && $this->settings_model->roles_delete($id)){

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

	public function roles_edit()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('descriptive_name', 'Description', 'trim|strip_tags|xss_clean');

			$permissions = $this->input->post('permissions');
			$permissions=json_encode($permissions);
			$users = $this->input->post('users');
			$users=json_encode($users);

			if($this->form_validation->run() == TRUE){

				$data = array(
					'name' => $this->input->post('name'),	
					'description' => $this->input->post('description'),	
					'descriptive_name' => $this->input->post('descriptive_name'),
					'permissions' => $permissions,
					'assigned_users' => $users,
				);

				if($this->settings_model->roles_edit($this->input->post('update_id'), $data)){

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

	
	public function get_roles_by_id()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->settings_model->get_roles_by_id($this->input->post('id'));
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
	
	public function get_roles()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			return $this->settings_model->get_roles();
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}
	
	public function leaves()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(1))
		{

			$this->data['page_title'] = 'Leaves Settings - '.company_name();
			$this->data['main_page'] = 'leaves';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$query = $this->db->get('leaves_type');
        	$this->data['leaves_type'] = $query->result_array();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_leaves_type()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			return $this->settings_model->get_leaves_type();
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function get_leaves_type_by_id()
	{	
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{	
			$this->form_validation->set_rules('id', 'id', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = $this->settings_model->get_leaves_type_by_id($this->input->post('id'));
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

	public function leaves_type_edit()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('update_id', 'Update ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('total_leaves', 'Total Leaves', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$data = array(
					'name' => $this->input->post('name'),
					'total_leaves' => $this->input->post('total_leaves'),
				);

				if($this->settings_model->leaves_type_edit($this->input->post('update_id'), $data)){

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

	public function leaves_type_create()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4))
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('total_leaves', 'Total Leaves', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$data = array(
					'name' => $this->input->post('name'),
					'total_leaves' => $this->input->post('total_leaves'),
				);

				$id = $this->settings_model->leaves_type_create($data);
				
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

	public function leaves_type_delete($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() )
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			if(!empty($id) && is_numeric($id) && $this->settings_model->leaves_type_delete($id)){

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
	
	
	
	}


