<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Languages extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	function change($language = "") {
		$language = ($language != "") ? $language : "english";
		if($this->languages_model->get_languages('',$language) && file_exists('./application/language/'.$language.'/custom_labels_lang.php')){
			$this->session->set_userdata('lang', $language);
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->languages_model->delete($id)){

				$this->session->set_flashdata('message', $this->lang->line('language_deleted_successfully')?$this->lang->line('language_deleted_successfully'):"Language deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('language_deleted_successfully')?$this->lang->line('language_deleted_successfully'):"Language deleted successfully.";
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

	public function editing()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3) && $this->uri->segment(3))
		{
			if(!$this->languages_model->get_languages('',$this->uri->segment(3))){
				redirect('languages', 'refresh');
			}
			$this->lang->load('custom_labels',$this->uri->segment(3));
			$this->data['page_title'] = 'Edit Language - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['languages'] = $this->languages_model->get_languages();
			$this->load->view('languages',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
		
	}

	public function edit()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('language_lang', 'Language Name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('short_code_lang', 'short code', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('active_lang', 'rtl', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status_lang', 'status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				
				$new_lang = lcfirst($this->input->post('language_lang'));
				$short_code = lcfirst($this->input->post('short_code_lang'));

				if(preg_match('/[^A-Za-z]/', $new_lang) && preg_match('/[^A-Za-z]/', $short_code))
				{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('only_english_characters_allowed')?$this->lang->line('only_english_characters_allowed'):"Only english characters allowed.";
					echo json_encode($this->data);
					return false;
				}

				$data = array(
					'language' => $new_lang,	
					'short_code' => $short_code,
					'active' => $this->input->post('active_lang'),	
					'status' => $this->input->post('status_lang'),	
				);

				$lang_array = '';
				$lang = array();

				foreach ($this->input->post() as $key => $label )
				{
					$label_data = json_encode(strip_tags($label), JSON_UNESCAPED_UNICODE);
					$label_key = $key;
					$lang_array.= "\$lang['".$label_key."'] = $label_data;"."\n";
				}
			
				$lang_array_final = "<?php defined('BASEPATH') OR exit('No direct script access allowed');\n\n\n".$lang_array;
	
                if(!is_dir('./application/language/'.$new_lang)){
                    mkdir('./application/language/'.$new_lang,0775,true);
                }

				if (file_exists('./application/language/'.$new_lang.'/custom_labels_lang.php')) {
					delete_files('./application/language/'.$new_lang.'/custom_labels_lang.php');
				}

				if($this->languages_model->edit($this->input->post('update_lang'), $data) && write_file('./application/language/'.$new_lang.'/custom_labels_lang.php', $lang_array_final)){
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('language_updated_successfully')?$this->lang->line('language_updated_successfully'):"Language updated successfully.";
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

	public function get_languages($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$languages = $this->languages_model->get_languages($id);
			if($languages){
				foreach($languages as $key => $language){
					$temp[$key] = $language;
					$temp[$key]['language'] = ucfirst($language['language']);
					$temp[$key]['active'] = $language['active']==0?'NO RTL':'RTL';

					$temp[$key]['status'] = $language['status']==0?'<div class="badge badge-danger">'.($this->lang->line('deactive')?htmlspecialchars($this->lang->line('deactive')):'Deactive').'</div>':'<div class="badge badge-success">'.($this->lang->line('active')?htmlspecialchars($this->lang->line('active')):'Active').'</div>';

					$temp[$key]['action'] = $temp[$key]['action'] = '<span class="d-flex"><a href="'.base_url('languages/editing/'.$language['language']).'" class="btn btn-icon btn-sm btn-success mr-1" data-id="'.$language["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger delete_language" data-id="'.$language["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
				}
			}else{
				$temp= array();
			}

			return print_r(json_encode($temp));
			
		}else{
			return '';
		}
	}

	public function create()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('language_lang', 'language name', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('short_code_lang', 'short code', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('active_lang', 'rtl', 'trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == TRUE){

				$new_lang = lcfirst($this->input->post('language_lang'));
				$short_code = lcfirst($this->input->post('short_code_lang'));

				if($this->languages_model->get_languages('',$new_lang)){
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('already_exists_this_language')?$this->lang->line('already_exists_this_language'):"Already exists this language.";
					echo json_encode($this->data);
					return false;
				}

				if(preg_match('/[^A-Za-z]/', $new_lang) && preg_match('/[^A-Za-z]/', $short_code))
				{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('only_english_characters_allowed')?$this->lang->line('only_english_characters_allowed'):"Only english characters allowed.";
					echo json_encode($this->data);
					return false;
				}

				$data = array(
					'language' => $new_lang,	
					'short_code' => $short_code,	
					'active' => $this->input->post('active_lang'),	
				);

				$language_id = $this->languages_model->create($data);
				
				$lang_array_final = "<?php defined('BASEPATH') OR exit('No direct script access allowed');\n\n\n";
	
                if(!is_dir('./application/language/'.$new_lang)){
                    mkdir('./application/language/'.$new_lang,0775,true);
                }

                recurse_copy('system/language/english', './application/language/'.$new_lang);

				if (file_exists('./application/language/'.$new_lang.'/custom_labels_lang.php')) {
					delete_files('./application/language/'.$new_lang.'/custom_labels_lang.php');
				}

				if($language_id && write_file('./application/language/'.$new_lang.'/custom_labels_lang.php', $lang_array_final)){
					$this->session->set_flashdata('message', $this->lang->line('language_created_successfully')?$this->lang->line('language_created_successfully'):"Language created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('language_created_successfully')?$this->lang->line('language_created_successfully'):"Language created successfully.";
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

	public function index()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['main_page'] = 'languages';
			$this->data['page_title'] = 'Languages - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}
