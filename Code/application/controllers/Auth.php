<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	/**
	 * Redirect if needed
	 */
	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			redirect('home', 'refresh');
		}else{
			$this->data['page_title'] = 'Login - '.company_name();
			$this->load->view('login',$this->data);
		}
	}

		/**
	 * Redirect if needed
	 */
	public function confirmation()
	{
		if ($this->ion_auth->logged_in())
		{
			redirect('home', 'refresh');
		}else{
			$this->data['page_title'] = 'Confirmation - '.company_name();
			$this->load->view('confirmation',$this->data);
		}
	}

	/**
	 * login_as_admin
	 */
	public function login_as_admin()
	{	
		
		$this->form_validation->set_rules('id', 'User ID', 'trim|required|strip_tags|xss_clean|is_numeric');

		if ($this->form_validation->run() === TRUE)
		{
			$user = $this->ion_auth->user($this->input->post('id'))->row();
			if($user && ($this->ion_auth->in_group(3) || (($this->ion_auth->is_admin() || permissions('user_view_all'))  && $this->session->userdata('saas_id') == $user->saas_id))){

				if ($this->ion_auth->login_as_admin($user->email))
				{
					$this->data['error'] = false;
					$this->data['message'] = $this->ion_auth->messages();
					echo json_encode($this->data);
					return false;
				}
				else
				{
					$this->data['error'] = true;
					$this->data['message'] = $this->ion_auth->errors();
					echo json_encode($this->data);
					return false;
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('access_denied')?htmlspecialchars($this->lang->line('access_denied')):"Access Denied";
				echo json_encode($this->data);
				return false;
			}
		}
		else
		{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?htmlspecialchars($this->lang->line('access_denied')):"Access Denied";
			echo json_encode($this->data);
			return false;
		}

	}

	/**
	 * Log the user in
	 */
	public function login()
	{	
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), true))
			{
				$this->data['error'] = false;
				$this->data['message'] = $this->ion_auth->messages();
				echo json_encode($this->data);
			}
			else
			{
				$this->data['error'] = true;
				$this->data['message'] = $this->ion_auth->errors();
				echo json_encode($this->data);
			}
		}
		else
		{
			$this->data['error'] = true;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			echo json_encode($this->data);
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";
		$this->ion_auth->logout();
		redirect('auth', 'refresh');
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = [
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			];
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			];

			// render
			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}else{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE)
		{
			$this->data['error'] = true;
			$this->data['data'] = '';
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			echo json_encode($this->data);
			return false;

		}else{
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity))
			{
				if ($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->data['error'] = true;
				$this->data['data'] = '';
				$this->data['message'] = $this->ion_auth->errors();
				echo json_encode($this->data);
				return false;
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{	
				$this->data['error'] = false;
				$this->data['data'] = '';
				$this->data['message'] = $this->ion_auth->messages();
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['data'] = '';
				$this->data['message'] = $this->ion_auth->errors();
				echo json_encode($this->data);
				return false;
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
		    $this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth", 'refresh');
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE)
			{
                $this->data['page_title'] = 'Reset Password - '.company_name();
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                $this->data['user_id'] = [
					'user_id' => $user->id,
				];
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;
				
			    $this->load->view('reset',$this->data);
			
			}
			else
			{
				$identity = $user->{$this->config->item('identity', 'ion_auth')};

				// finally change the password
				$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

				if ($change)
				{
					$this->ion_auth->activate($user->id);

					// if the password was successfully changed
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					$this->session->set_flashdata('message_type', 'success');
					redirect("auth", 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('auth/reset_password/' . $code, 'refresh');
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth", 'refresh');
		}
	}

	public function delete_user($id = '')
	{
		$id = !empty($id)?$id:$this->input->post('id');

		if(empty($id) || !is_numeric($id)){
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('invalid_user_id')?$this->lang->line('invalid_user_id'):"Invalid User ID";
			echo json_encode($this->data);
			return false;
		}

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !permissions('user_delete') && !$this->ion_auth->in_group(3)))
		{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('you_must_be_an_administrator_to_take_this_action')?$this->lang->line('you_must_be_an_administrator_to_take_this_action'):"You must be an administrator to take this action.";
			echo json_encode($this->data);
			return false;
		}

		if($this->ion_auth->delete_user($id)){
			
			$this->projects_model->delete_project_users('', $id);
			$this->projects_model->delete_task_users('', $id);
			$this->projects_model->delete_task_comment('', $id);
			$this->notifications_model->delete('', '', '', $id); // set noti delete
			$this->notifications_model->delete('', '', '', '', $id); // received noti delete

			$project_files = $this->projects_model->get_project_files('', $id);
			if($project_files){
				foreach($project_files as $project_file){
					$this->projects_model->delete_project_files($project_file['id']);
				}
			}

			$task_files = $this->projects_model->get_tasks_files('', $id);
			if($task_files){
				foreach($task_files as $task_file){
					$this->projects_model->delete_task_files($task_file['id']);
				}
			}

			$this->support_model->delete('', $id);
			$this->support_model->delete_support_message('', $id);

			if($id == $this->session->userdata('user_id')){
				$this->ion_auth->logout();
			}

			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$this->session->set_flashdata('message_type', 'success');
			$this->data['error'] = false;
			$this->data['message'] = $this->ion_auth->messages();
			echo json_encode($this->data);
			return false;
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->ion_auth->errors();
			echo json_encode($this->data);
			return false;
		}

	}
 
	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id = '', $code = FALSE)
	{
		$id = !empty($id)?$id:$this->input->post('id');

		if(empty($id) || !is_numeric($id)){
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('invalid_user_id')?$this->lang->line('invalid_user_id'):"Invalid User ID";
			echo json_encode($this->data);
			return false;
		}

		if ($code !== FALSE){
            if($this->ion_auth->activate($id, $code)){
    			$this->session->set_flashdata('message', $this->ion_auth->messages());
    			$this->session->set_flashdata('message_type', 'success');
    		}else{
    			$this->session->set_flashdata('message', $this->ion_auth->errors());
    			$this->session->set_flashdata('message_type', 'success');
    		}
			redirect("auth", 'refresh');
		}

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !permissions('user_edit') && !$this->ion_auth->in_group(3)))
		{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('you_must_be_an_administrator_to_take_this_action')?$this->lang->line('you_must_be_an_administrator_to_take_this_action'):"You must be an administrator to take this action.";
			echo json_encode($this->data);
			return false;
		}

		if($this->ion_auth->activate($id)){

			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$this->session->set_flashdata('message_type', 'success');
			$this->data['error'] = false;
			$this->data['message'] = $this->ion_auth->messages();
			echo json_encode($this->data);
			return false;
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->ion_auth->errors();
			echo json_encode($this->data);
			return false;
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		$id = !empty($id)?$id:$this->input->post('id');

		if(empty($id) || !is_numeric($id)){
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('invalid_user_id')?$this->lang->line('invalid_user_id'):"Invalid User ID";
			echo json_encode($this->data);
			return false;
		}

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !permissions('user_edit') && !$this->ion_auth->in_group(3)))
		{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('you_must_be_an_administrator_to_take_this_action')?$this->lang->line('you_must_be_an_administrator_to_take_this_action'):"You must be an administrator to take this action.";
			echo json_encode($this->data);
			return false;
		}

		if($this->ion_auth->deactivate($id)){
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$this->session->set_flashdata('message_type', 'success');
			$this->data['error'] = false;
			$this->data['message'] = $this->ion_auth->messages();
			echo json_encode($this->data);
			return false;
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->ion_auth->errors();
			echo json_encode($this->data);
			return false;
		}
				
	}

	public function register(){
		if (!$this->ion_auth->logged_in() && !turn_off_new_user_registration())
		{
			$this->data['page_title'] = 'Register - '.company_name();
			$this->load->view('register',$this->data);
		}else{
			redirect('home', 'refresh');
		}
	}

	/**
	 * Create or login
	 */
	public function social_auth()
	{
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|strip_tags|xss_clean|valid_email');

		if ($this->input->post('email') && $this->ion_auth->login_as_admin($this->input->post('email')))
		{
			$this->data['error'] = false;
			$this->data['message'] = $this->ion_auth->messages();
			echo json_encode($this->data);
			return false;
		}

		if ($this->form_validation->run() === TRUE)
		{
			$identity = strtolower($this->input->post('email'));
			$password = uniqid(rand(), true);

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
			];
			$group = array(1);
		}

		$recaptcha_secret_key = get_google_recaptcha_secret_key();
		if($recaptcha_secret_key){
			$token = $this->input->post('token');
			$action = $this->input->post('action');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptcha_secret_key, 'response' => $token)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			$arrResponse = json_decode($response, true);
			
			if($arrResponse["success"] != '1' || $arrResponse["action"] != $action || $arrResponse["score"] <= 0.6) {
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data); 
				return false;
			}
		}

		if ($this->form_validation->run() === TRUE && $new_user_id = $this->ion_auth->register($identity, $password, $identity, $additional_data, $group))
		{

			$plan = $this->plans_model->get_plans(1);
			$dt = strtotime(date("Y-m-d"));

			if($plan[0]['billing_type'] == "One Time" && $plan[0]['price'] < 1){
				$date = NULL;
			}elseif($plan[0]['billing_type'] == "Monthly" && $plan[0]['price'] < 1){
				$date = date("Y-m-d", strtotime("+1 month", $dt));
			}elseif($plan[0]['billing_type'] == "Yearly" && $plan[0]['price'] < 1){
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
				$date = date("Y-m-d", strtotime("-1 day", $dt));
			}

			$users_plans_data = array(
				'saas_id' => $new_user_id,		
				'plan_id' => 1,		
				'start_date' => date("Y-m-d"),			
				'end_date' => $date,			
			);
			$this->plans_model->create_users_plans($users_plans_data);

			// notification to the saas admins
			$saas_admins = $this->ion_auth->users(array(3))->result();
			foreach($saas_admins as $saas_admin){
				$data = array(
					'notification' => 'New user registered.',
					'type' => 'new_user',	
					'type_id' => $new_user_id,	
					'from_id' => $new_user_id,
					'to_id' => $saas_admin->user_id,	
				);
				$notification_id = $this->notifications_model->create($data);
			}

			$update_saas_id_data = [
				'saas_id' => $new_user_id,
			];

			$this->ion_auth->update($new_user_id, $update_saas_id_data);

			$this->ion_auth->login_as_admin($identity);

			$this->data['error'] = false;
			$this->data['message'] = $this->ion_auth->messages();
			echo json_encode($this->data); 
			return false; 
		}
		else
		{
			$this->data['error'] = true;
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			echo json_encode($this->data);
			return false; 
		}
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		if(!my_plan_features('users') && !$this->ion_auth->in_group(3)){ 
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
			echo json_encode($this->data);
			return false;
		}

		$this->data['title'] = $this->lang->line('create_user_heading');

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required|strip_tags|xss_clean');

		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		}
		else
		{
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|strip_tags|xss_clean|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if($this->input->post('new_register')){
			$this->form_validation->set_rules('agree', $this->lang->line('i_agree_to_the_terms_and_conditions')?htmlspecialchars($this->lang->line('i_agree_to_the_terms_and_conditions')):'I agree to the terms and conditions', 'trim|required|strip_tags|xss_clean');
		}

		if ($this->form_validation->run() === TRUE)
		{
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$devices = $this->input->post('device')?$this->input->post('device'):''; // Fill this array with device IDs
        
			// Convert the array of device IDs to a comma-separated string
			$device_ids_str = '["'.$devices.'"]';

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'father_name' => $this->input->post('father_name')?$this->input->post('father_name'):'',
				'phone' => $this->input->post('phone'),
				'gender' => $this->input->post('gender')?$this->input->post('gender'):'',
				'cnic' => $this->input->post('cnic')?$this->input->post('cnic'):'',
				'desgnation' => $this->input->post('desgnation')?$this->input->post('desgnation'):'',
				'department' => $this->input->post('department')?$this->input->post('department'):'',
				'join_date' => $this->input->post('join_date')?date("Y-m-d", strtotime($this->input->post('join_date'))):'',
				'emg_person' => $this->input->post('emg_person')?$this->input->post('emg_person'):'',
				'emg_number' => $this->input->post('emg_number')?$this->input->post('emg_number'):'',
				'employee_id' => $this->input->post('employee_id')?$this->input->post('employee_id'):'',
				'device_id' => $device_ids_str,
				'address' => $this->input->post('address')?$this->input->post('address'):'',
				'DOB' => $this->input->post('date_of_birth')?date("Y-m-d", strtotime($this->input->post('date_of_birth'))):'',
				'finger_config' =>'1',
				'shift_id' => $this->input->post('type')?$this->input->post('type'):'1',
			];
			$group = array($this->input->post('groups'));
		}

		$recaptcha_secret_key = get_google_recaptcha_secret_key();
		if($recaptcha_secret_key && $this->input->post('new_register')){
			$token = $this->input->post('token');
			$action = $this->input->post('action');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptcha_secret_key, 'response' => $token)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			$arrResponse = json_decode($response, true);
			
			if($arrResponse["success"] != '1' || $arrResponse["action"] != $action || $arrResponse["score"] <= 0.6) {
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data); 
				return false;
			}
		}

		if ($this->form_validation->run() === TRUE && $new_user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group))
		{

		    if($this->ion_auth->is_admin()  || permissions('user_create') || $this->input->post('create_saas_admin')){
				$update_saas_id_data = [
					'saas_id' => $this->session->userdata('saas_id'),
				];
			}else{
				$update_saas_id_data = [
					'saas_id' => $new_user_id,
				];

				$plan = $this->plans_model->get_plans(1);
				$dt = strtotime(date("Y-m-d"));

				if($plan[0]['billing_type'] == "One Time" && $plan[0]['price'] < 1){
					$date = NULL;
				}elseif($plan[0]['billing_type'] == "Monthly" && $plan[0]['price'] < 1){
					$date = date("Y-m-d", strtotime("+1 month", $dt));
				}elseif($plan[0]['billing_type'] == "Yearly" && $plan[0]['price'] < 1){
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
					$date = date("Y-m-d", strtotime("-1 day", $dt));
				}

				$users_plans_data = array(
					'saas_id' => $new_user_id,		
					'plan_id' => 1,		
					'start_date' => date("Y-m-d"),			
					'end_date' => $date,			
				);
				$this->plans_model->create_users_plans($users_plans_data);

				// notification to the saas admins
				$saas_admins = $this->ion_auth->users(array(3))->result();
				foreach($saas_admins as $saas_admin){
					$data = array(
						'notification' => 'New user registered.',
						'type' => 'new_user',	
						'type_id' => $new_user_id,	
						'from_id' => $new_user_id,
						'to_id' => $saas_admin->user_id,	
					);
					$notification_id = $this->notifications_model->create($data);
				}

			}

			$this->ion_auth->update($new_user_id, $update_saas_id_data);

			
			if ($this->input->post('company'))
			{
				$cdata_json = array(
					'company_name' => $this->input->post('company'),		
					'address' => '',		
					'city' => '',
					'state' => '',	
					'country' => '',		
					'zip_code' => '',		
				);
				$cdata = array(
					'value' => json_encode($cdata_json)
				);
				$csetting_type = 'company_'.$new_user_id;
				$this->settings_model->save_settings($csetting_type,$cdata);
			}

			if(email_activation()){

				$this->ion_auth->deactivate($new_user_id);
				$this->ion_auth_model->clear_messages();

				$activation_code = $this->ion_auth_model->activation_code;

				$template_data = array();
				$template_data['EMAIL_CONFIRMATION_LINK'] = base_url('auth/activate/'. $new_user_id .'/'. $activation_code);
				$template_data['NAME'] = $this->input->post('first_name').' '.$this->input->post('last_name');
				$email_template = render_email_template('email_verification', $template_data);
				send_mail($this->input->post('email'), $email_template[0]['subject'], $email_template[0]['message']);

				if($this->ion_auth->logged_in()){
					$msg = $this->lang->line('make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address')?$this->lang->line('make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address'):"Make sure to activate the account or ask the user to confirm the email address.";
				}else{
					$msg = $this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account')?$this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account'):"Please check your inbox and confirm your email address to activate your account.";
				}

			}else{
				if($this->input->post('new_register')){
				  $msg = $this->lang->line('user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials')?$this->lang->line('user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials'):"User registered successfully. Go to the login page and login with your credentials.";
				}else{
					$msg = $this->ion_auth->messages();
				}
			}
			
			$template_data = array();
			$template_data['LOGIN_EMAIL'] = $this->input->post('email');
			$template_data['LOGIN_PASSWORD'] = $this->input->post('password');
			$email_template = render_email_template('new_user_registration', $template_data);

			if($this->input->post('delete_lead')){
				$this->leads_model->delete($this->input->post('delete_lead'));
				$this->notifications_model->delete('', 'new_lead', $this->input->post('delete_lead'));
			}

			$this->session->set_flashdata('message', $msg);
			$this->session->set_flashdata('message_type', 'success');
			$this->data['error'] = false;
			$this->data['message'] = $msg;
			echo json_encode($this->data); 
		}
		else
		{
			$this->data['error'] = true;
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			echo json_encode($this->data);
			return false; 
		}
	}

	/**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('auth', 'refresh');
		}
		redirect('/', 'refresh');
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	public function edit_user()
	{
		$id = $this->input->post('update_id');

		if(empty($id) || !is_numeric($id)){

			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('invalid_user_id')?$this->lang->line('invalid_user_id'):"Invalid User ID";
			echo json_encode($this->data);
			return false;
		}

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !permissions('user_edit') && !$this->ion_auth->in_group(3)))
		{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
			return false;
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();
		
		// validate form input
		$this->form_validation->set_rules('update_id', 'User ID', 'trim|required|strip_tags|xss_clean|is_numeric');
		$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|strip_tags|xss_clean|is_numeric');
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim|strip_tags|xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{

				$profile_pic = '';
				if (!empty($_FILES['profile']['name'])){

					$upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = 'jpg|png|';
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('profile')){
						$profile_pic = $this->upload->data('file_name');
						if($this->input->post('old_profile_pic')){
							$unlink_path = $upload_path.''.$this->input->post('old_profile_pic');
							unlink($unlink_path);
						}
					}
				}

				$devices = $this->input->post('device')?$this->input->post('device'):''; // Fill this array with device IDs
        
				// Convert the array of device IDs to a comma-separated string
				$device_ids_str = '["'.$devices.'"]';

				$data = [
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'company' => $this->input->post('company'),
					'father_name' => $this->input->post('father_name')?$this->input->post('father_name'):'',
					'phone' => $this->input->post('phone'),
					'gender' => $this->input->post('gender')?$this->input->post('gender'):'',
					'cnic' => $this->input->post('cnic')?$this->input->post('cnic'):'',
					'desgnation' => $this->input->post('desgnation')?$this->input->post('desgnation'):'',
					'department' => $this->input->post('department')?$this->input->post('department'):'',
					'join_date' => $this->input->post('join_date')?date("Y-m-d", strtotime($this->input->post('join_date'))):'',
					'emg_person' => $this->input->post('emg_person')?$this->input->post('emg_person'):'',
					'emg_number' => $this->input->post('emg_number')?$this->input->post('emg_number'):'',
					'employee_id' => $this->input->post('employee_id')?$this->input->post('employee_id'):'',
					'device_id' => $device_ids_str,
					'address' => $this->input->post('address')?$this->input->post('address'):'',
					'DOB' => $this->input->post('date_of_birth')?date("Y-m-d", strtotime($this->input->post('date_of_birth'))):'',
					'finger_config' =>'1',
					'shift_id' => $this->input->post('type')?$this->input->post('type'):'1',
				];

				if(!empty($profile_pic)){
					$data["profile"] = $profile_pic;
				}

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3))
				{
					$groupData = array($this->input->post('groups'));
					if (isset($groupData) && !empty($groupData) && $currentGroups[0]->id != $this->input->post('groups'))
					{
						$this->ion_auth->remove_from_group('', $id);
						foreach ($groupData as $grp)
						{
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data))
				{
					if ($this->input->post('company'))
					{
						$company_details = company_details('', $user->id);
						if($company_details){
							$cdata_json = array(
								'company_name' => $this->input->post('company') == ''?(isset($company_details->company_name)?htmlspecialchars($company_details->company_name):''):$this->input->post('company'),		
								'address' => isset($company_details->address)?htmlspecialchars($company_details->address):'',		
								'city' => isset($company_details->city)?htmlspecialchars($company_details->city):'',		
								'state' => isset($company_details->state)?htmlspecialchars($company_details->state):'',		
								'country' => isset($company_details->country)?htmlspecialchars($company_details->country):'',		
								'zip_code' => isset($company_details->zip_code)?htmlspecialchars($company_details->zip_code):'',		
							);
						}else{
							$cdata_json = array(
								'company_name' => $this->input->post('company'),		
								'address' => '',		
								'city' => '',
								'state' => '',	
								'country' => '',		
								'zip_code' => '',		
							);
						}
						$cdata = array(
							'value' => json_encode($cdata_json)
						);
		
						$csetting_type = 'company_'.$user->id;
		
						$this->settings_model->save_settings($csetting_type,$cdata);
					}

					if ($this->input->post('plan_id'))
					{
						$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
						
						$users_plans_data['plan_id'] = $this->input->post('plan_id');

						$date = 'date';

						if($plan[0]['billing_type'] == "One Time"){
							$date = NULL;
							$users_plans_data['end_date'] = $date;
							$users_plans_data['expired'] = 1;
						}

						if($this->input->post('end_date') != '' && $date != NULL){
							$date = format_date($this->input->post('end_date'),"Y-m-d");
							$users_plans_data['end_date'] = $date;
							$users_plans_data['expired'] = date("Y-m-d") > format_date($this->input->post('end_date'),"Y-m-d")?0:1;
						}

						$users_plans_id = $this->plans_model->update_users_plans($user->id, $users_plans_data);
					}

					$this->session->set_flashdata('message', $this->ion_auth->messages());
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->ion_auth->messages();
					echo json_encode($this->data);
					return false;

				}
				else
				{
					$this->data['error'] = true;
					$this->data['message'] = $this->ion_auth->errors();
					echo json_encode($this->data);
					return false;
				}

			}
		}

			$this->data['error'] = true;
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			echo json_encode($this->data);
			return false;

	}

	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = [
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			];
			$this->data['description'] = [
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			];

			$this->_render_page('auth/create_group', $this->data);
		}
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'trim|required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], array(
					'description' => $_POST['group_description']
				));

				if ($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = [
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
		];
		if ($this->config->item('admin_group', 'ion_auth') === $group->name) {
			$this->data['group_name']['readonly'] = 'readonly';
		}
		
		$this->data['group_description'] = [
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		];

		$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'edit_group', $this->data);
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
			return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

}
