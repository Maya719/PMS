<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function get_pages($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$get_pages = $this->front_model->get_pages($id);
			if($get_pages){
				foreach($get_pages as $key => $get_page){
					$temp[$key] = $get_page;

					$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-success mr-1 edit_pages" data-id="'.$get_page["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a></span>';
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_feature($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$features = $this->front_model->get_feature($id);
			if($features){

				$lang = $this->session->userdata('lang')?$this->session->userdata('lang'):default_language();

				foreach($features as $key => $feature){
					$temp[$key] = $feature;
					$title = json_decode($feature['title']);
					$temp[$key]['title'] = isset($title->{$lang})?$title->{$lang}:'';

					$description = json_decode($feature['description']);
					$temp[$key]['description'] = isset($description->{$lang})?$description->{$lang}:'';

					$icon = json_decode($feature['icon']);
					$temp[$key]['icon'] = isset($icon->{$lang})?'<i class="'.$icon->{$lang}.'"></i>':'';

					$temp[$key]['action'] = '<span class="d-flex"><a href="'.base_url('front/edit-feature/'.$feature["id"]).'" class="btn btn-icon btn-sm btn-success mr-1" data-id="'.$feature["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger delete_feature" data-id="'.$feature["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function delete_feature($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			if(empty($id)){
				$id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			
			if(!empty($id) && is_numeric($id)){

				$this->front_model->delete_feature($id);
				
				$this->session->set_flashdata('message', $this->lang->line('feature_deleted_successfully')?$this->lang->line('feature_deleted_successfully'):"Feature deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('feature_deleted_successfully')?$this->lang->line('feature_deleted_successfully'):"Feature deleted successfully.";
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

	public function edit_feature()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3) && $this->uri->segment(3) && is_numeric($this->uri->segment(3)))
		{
			$this->data['page_title'] = 'Edit Feature - '.company_name();
			$this->data['lang'] = $this->languages_model->get_languages('', '', 1);
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['features'] = $this->front_model->get_feature($this->uri->segment(3));
			$this->load->view('edit-feature',$this->data);
		}else{
            redirect('front/landing', 'refresh');
		}
	}

	public function create_feature()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Create Feature - '.company_name();
			$this->data['lang'] = $this->languages_model->get_languages('', '', 1);
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('create-feature',$this->data);
		}else{
            redirect('front/landing', 'refresh');
		}
	}

	public function edit_pages()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
		
			$data = array(
				'content' => xss_clean($this->input->post('content'))
			);
			
			if(!empty($this->input->post('update_id')) && is_numeric($this->input->post('update_id'))){
				if($this->front_model->edit_pages($this->input->post('update_id'),$data)){
					$this->session->set_flashdata('message', $this->lang->line('pages_updated_successfully')?$this->lang->line('pages_updated_successfully'):"Pages updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('pages_updated_successfully')?$this->lang->line('pages_updated_successfully'):"Pages updated successfully.";
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

	public function order_feature()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			foreach(json_decode($_POST['data']) as $key => $feature){
				$data = array(
					'order_by_id' => $key
				);
				$this->front_model->edit_feature($feature->id,$data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function save_feature()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$lang = $this->languages_model->get_languages('', '', 1);
			$data_title = array();
			$data_desc = array();
			$data_icon = array();
			foreach($lang as $lan){
				if($this->input->post($lan['language'].'_title') && $this->input->post($lan['language'].'_description')){
					$data_title[$lan['language']] = xss_clean($this->input->post($lan['language'].'_title'));
					$data_desc[$lan['language']] = xss_clean($this->input->post($lan['language'].'_description'));
					$data_icon[$lan['language']] = xss_clean($this->input->post($lan['language'].'_icon'));
				}else{
					$data_title[$lan['language']] = '';
					$data_desc[$lan['language']] = '';
					$data_icon[$lan['language']] = '';
				}
			}
			
			$data = array(
				'title' => json_encode($data_title),
				'description' => json_encode($data_desc),
				'icon' => json_encode($data_icon)
			);
			
			if(!empty($this->input->post('update_id')) && is_numeric($this->input->post('update_id'))){
				if($this->front_model->edit_feature($this->input->post('update_id'),$data)){
					$this->session->set_flashdata('message', $this->lang->line('feature_updated_successfully')?$this->lang->line('feature_updated_successfully'):"Feature updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('feature_updated_successfully')?$this->lang->line('feature_updated_successfully'):"Feature updated successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				if($this->front_model->create_feature($data)){
					$this->session->set_flashdata('message', $this->lang->line('feature_created_successfully')?$this->lang->line('feature_created_successfully'):"Feature created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('feature_created_successfully')?$this->lang->line('feature_created_successfully'):"Feature created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}
		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}
	
	public function about_us()
	{
		$this->data['page_title'] = 'About Us - '.company_name();
		$this->data['data'] = $this->front_model->get_pages(1);
		$theme_name = frontend_permissions('theme_name');
		if($theme_name == 'theme_two'){
			$this->load->view('front/two/pages',$this->data);
		}else{
			$this->load->view('front/one/pages',$this->data);
		}
	}

	public function privacy_policy()
	{
		$this->data['page_title'] = 'Privacy Policy - '.company_name();
		$this->data['data'] = $this->front_model->get_pages(2);
		$theme_name = frontend_permissions('theme_name');
		if($theme_name == 'theme_two'){
			$this->load->view('front/two/pages',$this->data);
		}else{
			$this->load->view('front/one/pages',$this->data);
		}
	}

	public function terms_and_conditions()
	{
		$this->data['page_title'] = 'Terms and Conditions - '.company_name();
		$this->data['data'] = $this->front_model->get_pages(3);
		$theme_name = frontend_permissions('theme_name');
		if($theme_name == 'theme_two'){
			$this->load->view('front/two/pages',$this->data);
		}else{
			$this->load->view('front/one/pages',$this->data);
		}
	}

	public function theme()
	{
		
		$this->data['page_title'] = 'Home - '.company_name();
		$this->data['plans'] = $this->plans_model->get_plans();
		$features = $this->front_model->get_feature();
		$lang = $this->session->userdata('lang')?$this->session->userdata('lang'):default_language();
		$tmp = array();
		if($features){
			foreach($features as $key => $feature){
				$feature_icon = json_decode($feature['icon']);
				if(isset($feature_icon->{$lang})){
					$tmp[$key]['icon'] = $feature_icon->{$lang};
				}
				$feature_title = json_decode($feature['title']);
				if(isset($feature_title->{$lang})){
					$tmp[$key]['title'] = $feature_title->{$lang};
				}
				$feature_description = json_decode($feature['description']);
				if(isset($feature_description->{$lang})){
					$tmp[$key]['description'] = $feature_description->{$lang};
				}
			}
		}
		$this->data['features'] = $tmp;
		$this->data['home'] = get_home();
		
		if($this->uri->segment(3)== 'three'){
			$this->load->view('front/three/front',$this->data);
		}elseif($this->uri->segment(3) == 'two'){
			$this->load->view('front/two/front',$this->data);
		}else{
			$this->load->view('front/one/front',$this->data);
		}
	}

	public function index()
	{
		if(!frontend_permissions('landing_page')){
			redirect('auth', 'refresh');
		}
		if ($this->ion_auth->logged_in())
		{
            redirect('home', 'refresh');
		}else{
			
			$meta_title = get_mata_data('meta_title');
			$meta_description = get_mata_data('meta_description');
			$meta_keywords = get_mata_data('meta_keywords');

			$this->data['page_title'] = $meta_title?$meta_title:'Home - '.company_name();
			$this->data['meta_description'] = $meta_description?$meta_description:company_name();
			$this->data['meta_keywords'] = $meta_keywords?$meta_keywords:company_name();

			$this->data['plans'] = $this->plans_model->get_plans();
			$features = $this->front_model->get_feature();
			$lang = $this->session->userdata('lang')?$this->session->userdata('lang'):default_language();
			$tmp = array();
			if($features){
				foreach($features as $key => $feature){
					$feature_icon = json_decode($feature['icon']);
					if(isset($feature_icon->{$lang})){
						$tmp[$key]['icon'] = $feature_icon->{$lang};
					}
					$feature_title = json_decode($feature['title']);
					if(isset($feature_title->{$lang})){
						$tmp[$key]['title'] = $feature_title->{$lang};
					}
					$feature_description = json_decode($feature['description']);
					if(isset($feature_description->{$lang})){
						$tmp[$key]['description'] = $feature_description->{$lang};
					}
				}
			}
			$this->data['features'] = $tmp;
			$this->data['home'] = get_home();
			$theme_name = frontend_permissions('theme_name');
			if($theme_name == 'theme_three'){
				$this->load->view('front/three/front',$this->data);
			}elseif($theme_name == 'theme_two'){
				$this->load->view('front/two/front',$this->data);
			}else{
				$this->load->view('front/one/front',$this->data);
			}
		}
	}

	public function landing()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Frontend - '.company_name();
			$this->data['lang'] = $this->languages_model->get_languages('', '', 1);
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['features'] = $this->front_model->get_feature($this->uri->segment(3));
			$this->data['frontend_permissions'] = frontend_permissions();
			$this->data['home'] = get_home();
			$this->load->view('saas-front',$this->data);
		}else{
            redirect('auth', 'refresh');
		}
	}

	public function features()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Frontend - '.company_name();
			$this->data['lang'] = $this->languages_model->get_languages('', '', 1);
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['features'] = $this->front_model->get_feature($this->uri->segment(3));
			$this->load->view('saas-features',$this->data);
		}else{
            redirect('auth', 'refresh');
		}
	}
	public function about()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Frontend - '.company_name();
			$this->data['lang'] = $this->languages_model->get_languages('', '', 1);
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['data'] = $this->front_model->get_pages(1);
			$this->load->view('saas-about',$this->data);
		}else{
            redirect('auth', 'refresh');
		}
	}
	public function saas_terms_and_conditions()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Frontend - '.company_name();
			$this->data['lang'] = $this->languages_model->get_languages('', '', 1);
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['data'] = $this->front_model->get_pages(3);
			$this->load->view('saas-terms',$this->data);
		}else{
            redirect('auth', 'refresh');
		}
	}
	public function saas_privacy_policy()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Frontend - '.company_name();
			$this->data['lang'] = $this->languages_model->get_languages('', '', 1);
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['data'] = $this->front_model->get_pages(2);
			$this->load->view('saas-privacy',$this->data);
		}else{
            redirect('auth', 'refresh');
		}
	}


	public function send_mail()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|strip_tags|xss_clean|valid_email');
		$this->form_validation->set_rules('msg', 'Message', 'trim|required|strip_tags|xss_clean');
		if($this->form_validation->run() == TRUE){

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

			try{

				$template_data = array();
				$template_data['NAME'] = $this->input->post('name');
				$template_data['EMAIL'] = $this->input->post('email');
				$template_data['MESSAGE'] = $this->input->post('msg');
				$email_template = render_email_template('front_enquiry_form', $template_data);
				send_mail(from_email(), $email_template[0]['subject'], $email_template[0]['message']);

			}catch(Exception $e){
				$this->session->set_flashdata('message', $this->lang->line('we_will_get_back_to_you_soon')?$this->lang->line('we_will_get_back_to_you_soon'):"We will get back to you soon.");
				$this->session->set_flashdata('message_type', 'success');
			}
			
			$this->session->set_flashdata('message', $this->lang->line('we_will_get_back_to_you_soon')?$this->lang->line('we_will_get_back_to_you_soon'):"We will get back to you soon.");
			$this->session->set_flashdata('message_type', 'success');

			$this->data['error'] = false;
			$this->data['message'] = $this->lang->line('we_will_get_back_to_you_soon')?$this->lang->line('we_will_get_back_to_you_soon'):"We will get back to you soon.";
			echo json_encode($this->data); 
			return false;
		}else{
			$this->data['error'] = true;
			$this->data['message'] = validation_errors();
			echo json_encode($this->data); 
			return false;
		}
	}

}
