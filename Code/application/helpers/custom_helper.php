<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

function get_notifications_live()
{
    
    $CI =& get_instance();
    $new_noti = true;
    $show_beep_for_msg = false;
    $show_beep_for_support_msg = false;
    $notifications = get_notifications();
    $unread_msg_count = get_unread_msg_count();
    $unread_support_msg_count = get_unread_support_msg_count();
    $chat_message_count = get_unread_msg_count_numeric(); 

    if(($CI->ion_auth->is_admin() || permissions('chat_view') || $CI->ion_auth->in_group(3)) && ($unread_msg_count || $unread_support_msg_count) && is_module_allowed('chat')){
        if($unread_msg_count){
            $show_beep_for_msg = true;
        }
        if($unread_support_msg_count){
            $show_beep_for_support_msg = true;
        }
    }

    $new_support_message_received = '';
    if($show_beep_for_support_msg){ 
        $new_noti = false;
        $new_support_message_received = '<a href="'.(base_url('support')).'" class="dropdown-item dropdown-item-unread">
        <figure class="dropdown-item-icon avatar avatar-m bg-primary text-white fa fa-question-circle"></figure>
        <h6 class="dropdown-item-desc m-2">
        '.($CI->lang->line('new_support_message_received')?htmlspecialchars($CI->lang->line('new_support_message_received')):'New support message received').'
        </h6>
        </a>';
    }

    $new_message = '';
    if($show_beep_for_msg){ 
        $new_noti = false;

        $new_message = '<a href="'.(base_url('chat')).'" class="dropdown-item dropdown-item-unread">
        <figure class="dropdown-item-icon avatar avatar-m bg-primary text-white fa fa-comment-alt"></figure>
        <h6 class="dropdown-item-desc m-2">
        '.($CI->lang->line('new_message')?$CI->lang->line('new_message'):'New Message').'
        </h6>
        </a>';
    }

    $count=0;

    if($notifications){ 
        $new_noti = false;
        $whole_noti = '';

        foreach($notifications as $notification){
            $profile = '';

            if(isset($notification['profile']) && !empty($notification['profile'])){ 
                if(file_exists('assets/uploads/profiles/'.$notification['profile'])){
                    $file_upload_path = 'assets/uploads/profiles/'.$notification['profile'];
                }else{
                    $file_upload_path = 'assets/uploads/f'.$CI->session->userdata('saas_id').'/profiles/'.$notification['profile'];
                } 
                $profile = '<figure class="dropdown-item-icon avatar avatar-m bg-transparent">
                <img src="'.(base_url($file_upload_path)).'" alt="'.(htmlspecialchars($notification['first_name']).' '.htmlspecialchars($notification['last_name'])).'" data-toggle="tooltip" data-placement="top" title="'.(htmlspecialchars($notification['first_name']).' '.htmlspecialchars($notification['last_name'])).'" data-original-title="">
                </figure>';
            }else{
                $profile = '<figure class="dropdown-item-icon avatar avatar-m bg-primary text-white" data-initial="" data-toggle="tooltip" data-placement="top" title="'.(mb_substr(htmlspecialchars($notification['first_name']), 0, 1, "utf-8").''.mb_substr(htmlspecialchars($notification['last_name']), 0, 1, "utf-8")).'" data-original-title="'.(htmlspecialchars($notification['first_name']).' '.htmlspecialchars($notification['last_name'])).'">
                </figure>';
            }

            $whole_noti .= '<a href="'.($notification['notification_url']).'" class="dropdown-item dropdown-item-unread">
                
                '.($profile).'
                
                <div class="dropdown-item-desc  ml-2">
                '.($notification['notification']).'
                <div class="time text-primary">'.(time_elapsed_string($notification['created'])).'</div>
                </div>
            </a>';
            $count++;

        }
    }else{ if($new_noti){
        $whole_noti = '<a class="dropdown-item dropdown-item-unread">
        <div class="dropdown-item-desc  ml-2">
            '.($CI->lang->line('no_new_notifications')?$CI->lang->line('no_new_notifications'):'No new notifications.').'
        </div>
        </a>';
    } }
    
    if($chat_message_count != 0){
        $count ++;
    }

    return '<li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg"><i class="far fa-bell"></i>
    '.($notifications || $show_beep_for_msg || $show_beep_for_support_msg?'<span style="
    background-color: #ff761b ; /* Background color of the badge */
    color: #fff; /* Text color of the badge */
    border-radius: 50%; /* Make the badge circular */
    padding: 4px 6px; /* Adjust padding for spacing */
    font-size: 12px; /* Font size of the badge text */
    line-height: 1;
    position: absolute; /* Position the badge relative to the icon */
    top: -2px; /* Adjust vertical position */
    right: 2px; /* Adjust horizontal position */">' . $count . '</span>':'').'
    </a>
    <div class="dropdown-menu dropdown-list dropdown-menu-right">
        <div class="dropdown-header">
        '.($CI->lang->line('notifications')?$CI->lang->line('notifications'):'Notifications').'
        
        </div>
        <div class="dropdown-list-content dropdown-list-icons" id="new_live_notifications">

        '.($new_support_message_received).'
        '.($new_message).'
        '.($whole_noti).'

        </div>
        <div class="dropdown-footer text-center">
        <a href="'.(base_url('notifications')).'">'.($CI->lang->line('view_all')?$CI->lang->line('view_all'):'View All').' <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
    </li>';
}

function get_google_client_id()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'logins']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return '';
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->google_client_id)){
        return $data->google_client_id;
    }else{
        return '';
    }
}

function get_google_client_secret()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'logins']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return '';
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->google_client_secret)){
        return $data->google_client_secret;
    }else{
        return '';
    }
}

function get_mata_data($type = '')
{
    $CI =& get_instance();
    
    $where_type = 'seo';

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if($type == ''){
        return $data;
    }

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        return false;
    }
}

function get_unread_msg_count()
{
    $CI =& get_instance();
    return $CI->chat_model->get_unread_msg_count($CI->session->userdata('user_id'));				
}

function get_chat_message_count()
{
    $CI =& get_instance();

    $unread_msg_count = get_unread_msg_count_numeric(); // Assuming this function gets the unread chat message count


    if ($unread_msg_count > 0) {
        return '<span class="chat-message-count" 
        style="
        background-color: #ff0000; /* Background color of the badge */
        color: #fff; /* Text color of the badge */
        border-radius: 50%; /* Make the badge circular */
        padding: 4px 8px; /* Adjust padding for spacing */
        font-size: 12px; /* Font size of the badge text */
        position: absolute; /* Position the badge relative to the icon */
        top: -8px; /* Adjust vertical position */
        right: -8px; /* Adjust horizontal position */"> ' . $unread_msg_count . '</span>';
    }

    return '';
}

function get_unread_msg_count_numeric()
{
    $CI =& get_instance();
    return $CI->chat_model->get_unread_msg_count_numeric($CI->session->userdata('user_id'));				
}

function get_unread_support_msg_count()
{
    $CI =& get_instance();
    return $CI->support_model->get_unread_support_msg_count($CI->session->userdata('user_id'));				
}

function from_email()
{
    $CI =& get_instance();
    $CI->load->library('session');
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return 'admin@high.com';
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->from_email)){
        return $data->from_email;
    }else{
        return 'admin@high.com';
    }
}

function set_expire_all_expired_plans(){
    $CI =& get_instance();
    if($CI->db->query("UPDATE users_plans SET expired=0 WHERE expired=1 AND end_date < CURDATE() ")){
        return true;
    }else{
        return false;
    }
}

function render_email_template($template_name, $template_data){
    $CI = get_instance();

    if(!$template_name && !$template_data){
        return false;
    }
    
    $pre_template_data = array();
    $pre_template_data['COMPANY_NAME'] = company_name();
    $pre_template_data['DASHBOARD_URL'] = base_url();
    $pre_template_data['LOGO_URL'] = full_logo();
    
    $template_data = array_merge($pre_template_data,$template_data);

    $template_code = $CI->settings_model->get_email_templates($template_name);
    if($template_code){
        if(isset($template_code[0]['message']) && $template_code[0]['message'] != ''){
            $template = $template_code[0]['message'];
            foreach($template_data as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            $template_code[0]['message'] = $template;
            return $template_code;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function send_mail($to, $subject, $message) {
    $CI = get_instance();
    
    $email_library = get_email_library();

    if($email_library == 'codeigniter'){
        $email_config = Array();
        $email_config["protocol"] = "smtp";
        $email_config["charset"] = "utf-8";
        $email_config["mailtype"] = "html";
        $email_config["smtp_host"] = smtp_host();
        $email_config["smtp_port"] = smtp_port();
        $email_config["smtp_user"] = smtp_email();
        $email_config["smtp_pass"] = smtp_password();
        $email_config["smtp_crypto"] = smtp_encryption();
        if($email_config["smtp_crypto"] == 'none'){
            $email_config["smtp_crypto"] = "";
        }
        $CI->load->library('email', $email_config);
        $CI->email->clear(true);
        $CI->email->set_newline("\r\n");
        $CI->email->set_crlf("\r\n");
        $CI->email->from(from_email(), company_name());
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if($CI->email->send()){
            return true;
        }else{
            return false;
        }
    }else{
        $CI = new PHPMailer(); 
        $CI->CharSet = 'UTF-8';
        $CI->IsSMTP(); 
        $CI->SMTPDebug = 1; 
        $CI->SMTPAuth = true;
        $CI->SMTPSecure = smtp_encryption();
        $CI->Host = smtp_host();
        $CI->Port = smtp_port();
        $CI->IsHTML(true);
        $CI->Username = smtp_email();
        $CI->Password = smtp_password();
        $CI->SetFrom(from_email(), company_name());
        $CI->Subject = $subject;
        $CI->Body = $message;
        $CI->AddAddress($to);
        if($CI->Send()){
            return true;
        }else{
            return false;
        }
    }
}

function get_google_recaptcha_site_key()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'recaptcha']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->site_key)){
        return $data->site_key;
    }else{
        return '';
    }
} 
function get_google_recaptcha_secret_key()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'recaptcha']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->secret_key)){
        return $data->secret_key;
    }else{
        return '';
    }
} 
function get_header_code()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'custom_code']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->header_code)){
        return $data->header_code;
    }else{
        return '';
    }
} 

function get_footer_code()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'custom_code']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->footer_code)){
        return $data->footer_code;
    }else{
        return '';
    }
} 

function turn_off_new_user_registration(){    
    
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return 0;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->turn_off_new_user_registration) && !empty($data->turn_off_new_user_registration)){
        return $data->turn_off_new_user_registration;
    }else{
        return 0;
    }

}

function theme_color(){    
    
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->theme_color) && !empty($data->theme_color)){
        return $data->theme_color;
    }else{
        return '#e52165';
    }

}

function email_activation(){    
    
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->email_activation) && !empty($data->email_activation)){
        return $data->email_activation;
    }else{
        return false;
    }

}

function is_storage_limit_exceeded($user_id = ''){
    $CI =& get_instance();
    if(empty($user_id)){
        $user_id = $CI->session->userdata('saas_id');
    }
    $current_storage = check_my_storage('assets/uploads/f'.$user_id);
    $current_plan = get_current_plan($user_id);
    if($current_plan['storage'] < 0){
        return true;
    }
    $current_plan_storage = tobytes($current_plan['storage']);
    if($current_storage >= $current_plan_storage){
        return false;
    }else{
        return true;
    }
}

function check_my_storage($dir = '', $user_id = '')
{   
    $CI =& get_instance();

    if(empty($user_id)){
        $user_id = $CI->session->userdata('saas_id');
    }

    if(empty($dir)){
        $dir = 'assets/uploads/f'.$user_id;
    }

    $size = 0;
    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : check_my_storage($each);
    }
    return $size;
}

function tobytes($size, $type = "GB"){
    $types = array("B", "KB", "MB", "GB", "TB", "PB"); 
    if($key = array_search($type, $types))
        return $size * pow(1024, $key);
    else 
        return "invalid type";
}

function formatBytes($kb, $type = 'kb') { 

    if($type == 'kb'){
        $bytes = $kb*1024;
    }else{
        $bytes = $kb;
    }

    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    $tb = $gb * 1024;
    if ($bytes <= 0) {
        return $bytes;
    }

    if (($bytes > 0) && ($bytes < $kb)) {
        return bcdiv($bytes, 1, 2) . ' B';
    } elseif (($bytes >= $kb) && ($bytes < $mb)) {
        return bcdiv($bytes / $kb, 1, 2) . ' KB';
    } elseif (($bytes >= $mb) && ($bytes < $gb)) {
        return bcdiv($bytes / $mb, 1, 2) . ' MB';
    } elseif (($bytes >= $gb) && ($bytes < $tb)) {
        return bcdiv($bytes / $gb, 1, 2) . ' GB';
    } elseif ($bytes >= $tb) {
        return bcdiv($bytes / $tb, 1, 2) . ' TB';
    } else {
        return bcdiv($bytes, 1, 2) . ' B';
    }
} 

function check_my_timer($task_id = '', $user_id = ''){
    $CI =& get_instance();
    $where = '';
    if($task_id){
        $where .= " AND task_id=$task_id ";
    }
    if($user_id){
        $where .= " AND user_id=$user_id ";
    }else{
        $where .= " AND user_id=".$CI->session->userdata('user_id');
    }

    $where .= " AND saas_id=".$CI->session->userdata('saas_id');

    $query = $CI->db->query("SELECT id FROM timesheet WHERE starting_time IS NOT NULL and ending_time IS NULL $where");
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function default_language()
{
    $CI =& get_instance();

    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->default_language)){
        return $data->default_language;
    }else{
        return 'english';
    }
}


function get_my_invoices_details(){
    $CI =& get_instance();
    $invoices = $CI->invoices_model->get_invoices();
    if($invoices){

        $amount_final = 0;
        $amount_due_final = 0;

        foreach($invoices as $key => $invoice){

            $amount = 0;
            $amount_due = 0;

            if($invoice['status'] == 1){
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
            }else{
                $amount_due = $invoice['amount'];
                if($invoice['tax'] && $invoice['tax'] != ''){
                    $total_tax_per_due = 0;
                    if(is_numeric($invoice['tax'])){
                        $taxes = get_tax($invoice['tax']);
                        if($taxes){
                            $total_tax_per_due = $total_tax_per_due+$taxes[0]['tax'];
                        }
                    }else{
                        foreach(explode(',', $invoice['tax']) as $tax_id){
                            $taxes = get_tax($tax_id);
                            if($taxes){
                                $total_tax_per_due = $total_tax_per_due+$taxes[0]['tax'];
                            }
                        }
                    }
                    if($total_tax_per_due != 0){
                        $total_tax_due = $amount_due*$total_tax_per_due/100;
                    }else{
                        $total_tax_due = 0;
                    }
                    $amount_due = $amount_due+$total_tax_due;
                }
            }     
            if($amount != 0){
                $amount_final = $amount_final+$amount;
            }else{
                $amount_final = $amount_final;
            }
            if($amount_due != 0){
                $amount_due_final = $amount_due_final+$amount_due;
            }else{
                $amount_due_final = $amount_due_final;
            }
        }
        $data['paid'] = isset($amount_final)?$amount_final:0;
        $data['due'] = isset($amount_due_final)?$amount_due_final:0;
        $data['total'] = $data['paid'] + $data['due'];
    }else{
        $data['paid'] = 0;
        $data['due'] = 0;
        $data['total'] = 0;
    }
return $data;
}

function company_details($type = '', $user_id = '')
{
    $CI =& get_instance();
    if(empty($user_id)){
        if($CI->ion_auth->in_group(4)){
            $where_type = 'company_'.$CI->session->userdata('user_id');
        }else{
            $where_type = 'company_'.$CI->session->userdata('saas_id');
        }
    }else{
        $where_type = 'company_'.$user_id;
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return '';
    }
    
    $data = json_decode($data[0]['value']);

    if($type == ''){
        return $data;
    }

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        return '';
    }
} 

function get_tax($tax_id = '')
{
    $CI =& get_instance();
    $CI->db->from('taxes');
    $CI->db->where(['saas_id'=>$CI->session->userdata('saas_id')]);
    if($tax_id){
        $CI->db->where(['id'=>$tax_id]);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return array();
    }
} 

function get_currency($type)
{
    $CI =& get_instance();

    $where_type = 'general_'.$CI->session->userdata('saas_id');

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
} 

function get_saas_currency($type)
{
    $CI =& get_instance();
    
    $where_type = 'general';

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
} 

function get_home($lang = '')
{
    $CI =& get_instance();
    $CI->db->where(['type'=>'home']);
    $query = $CI->db->get('settings');
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(empty($lang)){
        return $data;
    }else{
        if(isset($data->$lang)){
            return $data->$lang;
        }else{
            return true;
        }
    }
}

function get_languages($language_id = '', $language_name = '', $status = ''){
    $CI =& get_instance();
    $languages = $CI->languages_model->get_languages($language_id, $language_name, $status);
    if(empty($languages)){
        return false;
    }else{
        return $languages;
    }
}

function get_notifications($user_id = ''){  

    $CI =& get_instance();
    $left_join = " LEFT JOIN users u ON n.from_id=u.id ";
    $query = $CI->db->query("SELECT n.*,u.first_name,u.last_name,u.profile FROM notifications n $left_join WHERE is_read=0 AND to_id=".$CI->session->userdata('user_id')." ORDER BY n.created DESC LIMIT 10");
    $notifications = $query->result_array();
    if($notifications){
        foreach($notifications as $key => $notification){
            $temp[$key] = $notification;

            $extra = '';
            $notification_url = base_url('notifications');
            $notification_txt = $notification['notification'];
            if($notification['type'] == 'offline_request' && $CI->ion_auth->in_group(3)){
                $notification_txt = $CI->lang->line('offline_bank_transfer_request_created_for_subscription_plan')?$CI->lang->line('offline_bank_transfer_request_created_for_subscription_plan')." ".$notification['notification']:"Offline / Bank Transfer request created for subscription plan ".$notification['notification'];
                $notification_url = base_url('plans/offline-requests');
                $plan = $CI->plans_model->get_plans($notification['type_id']);
                if($plan){
                    $extra = '<div class="text-small">
                        '.($CI->lang->line('plan')?$CI->lang->line('plan'):'Plan').': <span class="text-info">'.$plan[0]['title'].'</span> 
                    </div>'; 
                }
            }elseif($notification['type'] == 'new_plan' && $CI->ion_auth->in_group(3)){
                $notification_txt = $CI->lang->line('ordered_subscription_plan')?$CI->lang->line('ordered_subscription_plan')." ".$notification['notification']:"Ordered subscription plan ".$notification['notification'];
                $notification_url = base_url('plans/orders');
                $plan = $CI->plans_model->get_plans($notification['type_id']);
                if($plan){
                    $user = $CI->ion_auth->user($notification['from_id'])->row();
                    if($user){
                        $ADD = 'User: <span class="text-info">'.$user->first_name.' '.$user->last_name.'</span>';
                    }else{
                        $ADD = '';
                    }
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('plan')?$CI->lang->line('plan'):'Plan').': <span class="text-info">'.$plan[0]['title'].'</span> 
                        '.($CI->lang->line('transaction')?$CI->lang->line('transaction'):'Transaction').': <span class="text-info">'.$plan[0]['price'].'</span> 
                        '.$ADD.'
                    </div>';
                }
            }elseif($notification['type'] == 'new_user' && $CI->ion_auth->in_group(3)){
                $notification_txt = $CI->lang->line('new_user_registered')?$CI->lang->line('new_user_registered'):"New user registered.";
                $notification_url = base_url('users/saas');
                $user = $CI->ion_auth->user($notification['type_id'])->row();
                if($user){
                    $extra = '<div class="text-small">
                        '.($CI->lang->line('user')?$CI->lang->line('user'):'User').': <span class="text-info">'.$user->first_name.' '.$user->last_name.'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'offline_request' && $CI->ion_auth->is_admin()){
                
                $notification_txt = $CI->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')?$CI->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')." ".$notification['notification']:"Your Offline / Bank Transfer request accepted for subscription plan ".$notification['notification'];
                $notification_url = base_url('plans');
                $plan = $CI->plans_model->get_plans($notification['type_id']);
                if($plan){
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('plan')?$CI->lang->line('plan'):'Plan').': <span class="text-info">'.$plan[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'new_project'){
                $notification_txt = $CI->lang->line('new_project_created')?$notification['notification']." ".$CI->lang->line('new_project_created'):$notification['notification']." new project created.";
                $notification_url = base_url('projects/detail/'.$notification['type_id']);
                $project = $CI->projects_model->get_projects('',$notification['type_id']);
                if($project){
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$project[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'project_status'){
                $old_status = project_status('',$notification['notification']);
                $project = $CI->projects_model->get_projects('',$notification['type_id']);

                if($old_status && $project){
                    $notification_txt = $CI->lang->line('project_status_changed')?$CI->lang->line('project_status_changed').' <span class="text-info text-strike">'.$old_status[0]['title'].'</span> = <span class="text-info">'.$project[0]['project_status'].'</span>':'Project status changed. <span class="text-info text-strike">'.$old_status[0]['title'].'</span> = <span class="text-info">'.$project[0]['project_status'].'</span>';
                }

                $notification_url = base_url('projects/detail/'.$notification['type_id']);
                
                if($project){
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$project[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'project_file'){
                $notification_txt = $CI->lang->line('project_file_uploaded')?$notification['notification']." ".$CI->lang->line('project_file_uploaded'):$notification['notification']." project file uploaded.";
                $notification_url = base_url('projects/detail/'.$notification['type_id']);
                $project = $CI->projects_model->get_projects('',$notification['type_id']);
                if($project){
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$project[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'new_task'){
                $notification_txt = $CI->lang->line('task_assigned_you')?$notification['notification']." ".$CI->lang->line('task_assigned_you'):$notification['notification']." task assigned you.";
                $task = $CI->projects_model->get_tasks('',$notification['type_id']);
                if($task){
                    $notification_url = base_url('projects/tasks/'.$task[0]['project_id'].'/'.$notification['type_id']);
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$task[0]['project_title'].'</span> 
                    '.($CI->lang->line('task')?$CI->lang->line('task'):'Task').': <span class="text-info">'.$task[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'task_status'){
                $task_status_old = task_status($notification['notification']);
                $task = $CI->projects_model->get_tasks('',$notification['type_id']);

                if($task_status_old && $task){
                    $notification_txt = $CI->lang->line('task_status_changed')?($CI->lang->line('task_status_changed').' <span class="text-info text-strike">'.$task_status_old[0]['title'].'</span> = <span class="text-info">'.$task[0]['task_status'].'</span>'):('Task status changed. <span class="text-info text-strike">'.$task_status_old[0]['title'].'</span> = <span class="text-info">'.$task[0]['task_status'].'</span>');
                }
                
                if($task){
                    $notification_url = base_url('projects/tasks/'.$task[0]['project_id'].'/'.$notification['type_id']);
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$task[0]['project_title'].'</span> 
                    '.($CI->lang->line('task')?$CI->lang->line('task'):'Task').': <span class="text-info">'.$task[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'task_file'){
                $notification_txt = $CI->lang->line('task_file_uploaded')?$notification['notification']." ".$CI->lang->line('task_file_uploaded'):$notification['notification']." task file uploaded.";
                $task = $CI->projects_model->get_tasks('',$notification['type_id']);
                if($task){
                    $notification_url = base_url('projects/tasks/'.$task[0]['project_id'].'/'.$notification['type_id']);
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$task[0]['project_title'].'</span> 
                    '.($CI->lang->line('task')?$CI->lang->line('task'):'Task').': <span class="text-info">'.$task[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'task_comment'){
                $notification_txt = $CI->lang->line('new_task_comment')?$CI->lang->line('new_task_comment')." ".$notification['notification']:"New task comment ".$notification['notification'];
                $task = $CI->projects_model->get_tasks('',$notification['type_id']);
                if($task){
                    $notification_url = base_url('projects/tasks/'.$task[0]['project_id'].'/'.$notification['type_id']);
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$task[0]['project_title'].'</span> 
                    '.($CI->lang->line('task')?$CI->lang->line('task'):'Task').': <span class="text-info">'.$task[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'project_comment'){
                $notification_txt = $CI->lang->line('new_project_comment')?$CI->lang->line('new_project_comment')." ".$notification['notification']:"New project comment ".$notification['notification'];
                $notification_url = base_url('projects/detail/'.$notification['type_id']);
                $project = $CI->projects_model->get_projects('',$notification['type_id']);
                if($project){
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('project')?$CI->lang->line('project'):'Project').': <span class="text-info">'.$project[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'new_invoice'){
                $invoice = $CI->invoices_model->get_invoices($notification['type_id']);
                if($invoice){
                    $invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
                    $notification_txt = $CI->lang->line('new_invoice_received')?$invoice_id." ".$CI->lang->line('new_invoice_received'):$invoice_id." new invoice received.";
                    $notification_url = base_url('invoices/view/'.$invoice[0]['id']);
                }
            }elseif($notification['type'] == 'bank_transfer'){
                $invoice = $CI->invoices_model->get_invoices($notification['type_id']);
                if($invoice){
                    $invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
                    $notification_txt = $CI->lang->line('bank_transfer_request_received_for_the_invoice')?$CI->lang->line('bank_transfer_request_received_for_the_invoice')." ".$invoice_id:"Bank transfer request received for the invoice ".$invoice_id;
                    $notification_url = base_url('invoices/view/'.$invoice[0]['id']);
                }
            }elseif($notification['type'] == 'bank_transfer_accept'){
                $invoice = $CI->invoices_model->get_invoices($notification['type_id']);
                if($invoice){
                    $invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
                    $notification_txt = $CI->lang->line('bank_transfer_request_accepted_for_the_invoice')?$CI->lang->line('bank_transfer_request_accepted_for_the_invoice')." ".$invoice_id:"Bank transfer request accepted for the invoice ".$invoice_id;
                    $notification_url = base_url('invoices/view/'.$invoice[0]['id']);
                }
            }elseif($notification['type'] == 'bank_transfer_reject'){
                $invoice = $CI->invoices_model->get_invoices($notification['type_id']);
                if($invoice){
                    $invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
                    $notification_txt = $CI->lang->line('bank_transfer_request_rejected_for_the_invoice')?$CI->lang->line('bank_transfer_request_rejected_for_the_invoice')." ".$invoice_id:"Bank transfer request rejected for the invoice ".$invoice_id;
                    $notification_url = base_url('invoices/view/'.$invoice[0]['id']);
                }
            }elseif($notification['type'] == 'payment_received'){
                $invoice = $CI->invoices_model->get_invoices($notification['type_id']);
                if($invoice){
                    $invoice_id = '<span class="text-info">'.$invoice[0]['invoice_id'].'</span>';
                    $notification_txt = $CI->lang->line('payment_received_for_the_invoice')?$CI->lang->line('payment_received_for_the_invoice')." ".$invoice_id:"Payment received for the invoice ".$invoice_id;
                    $notification_url = base_url('invoices/view/'.$invoice[0]['id']);
                }
            }elseif($notification['type'] == 'new_estimate'){
                $estimates = $CI->estimates_model->get_estimates($notification['type_id']); 
                if($estimates){
                    $title = '<span class="text-info">'.$notification['notification'].'</span>';
                    $notification_txt = $CI->lang->line('new_estimate_received')?$title." ".$CI->lang->line('new_estimate_received'):$title." new estimate received.";
                    $notification_url = base_url('estimates/view/'.$notification['type_id']);
                }
            }elseif($notification['type'] == 'estimate_reject'){
                $estimates = $CI->estimates_model->get_estimates($notification['type_id']); 
                if($estimates){
                    $title = '<span class="text-info">'.$notification['notification'].'</span>';
                    $notification_txt = $CI->lang->line('estimate_rejected')?$title." ".$CI->lang->line('estimate_rejected'):$title." estimate rejected.";
                    $notification_url = base_url('estimates/view/'.$notification['type_id']);
                }
            }elseif($notification['type'] == 'estimate_accept'){
                $estimates = $CI->estimates_model->get_estimates($notification['type_id']); 
                if($estimates){
                    $title = '<span class="text-info">'.$notification['notification'].'</span>';
                    $notification_txt = $CI->lang->line('estimate_accepted')?$title." ".$CI->lang->line('estimate_accepted'):$title." estimate accepted.";
                    $notification_url = base_url('estimates/view/'.$notification['type_id']);
                }
            }elseif($notification['type'] == 'new_meeting'){
                $meetings = $CI->meetings_model->get_meetings($notification['type_id']); 
                if($meetings){
                    $title = '<span class="text-info">'.$notification['notification'].'</span>';
                    $notification_txt = $CI->lang->line('new_meeting_created')?$title." ".$CI->lang->line('new_meeting_created'):$title." new meeting scheduled.";
                    $notification_url = base_url('meetings/view/'.$notification['type_id']);
                }
            }elseif($notification['type'] == 'leave_request'){
                $leave = $CI->leaves_model->get_leaves_by_id($notification['type_id']);
                if($leave){
                    $notification_txt = $CI->lang->line('leave_request_received')?htmlspecialchars($CI->lang->line('leave_request_received')):'Leave request received';
                    $notification_url = base_url('leaves');
                }
            }elseif($notification['type'] == 'leave_request_accepted'){
                $leave = $CI->leaves_model->get_leaves_by_id($notification['type_id']);
                if($leave){
                    $notification_txt = $CI->lang->line('leave_request_accepted')?htmlspecialchars($CI->lang->line('leave_request_accepted')):'Leave request accepted';
                    $notification_url = base_url('leaves');
                }
            }elseif($notification['type'] == 'leave_request_rejected'){
                $leave = $CI->leaves_model->get_leaves_by_id($notification['type_id']);
                if($leave){
                    $notification_txt = $CI->lang->line('leave_request_rejected')?htmlspecialchars($CI->lang->line('leave_request_rejected')):'Leave request rejected';
                    $notification_url = base_url('leaves');
                }
            }elseif($notification['type'] == 'new_lead'){
                $leads = $CI->leads_model->get_leads_by_id($notification['type_id']);
                if($leads){
                $title = '<span class="text-info">'.$notification['notification'].'</span>';
                $notification_txt = $CI->lang->line('new_lead_assigned_to_you')?$title." ".$CI->lang->line('new_lead_assigned_to_you'):$title." New lead assigned to you.";
                $notification_url = base_url('leads');
                }
            }

            $temp[$key]['notification_url'] = $notification_url;

            $temp[$key]['notification'] = $notification_txt.' '.$extra;
            
            $temp[$key]['first_name'] = $notification['first_name'];
            $temp[$key]['last_name'] = $notification['last_name'];
            $temp[$key]['profile'] = $notification['profile'];

        }
    }else{
        $temp = array();
    }

    if(!empty($temp)){
        return $temp;
    }else{
        return false;
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    if(!is_dir($dst)){
        mkdir($dst,0775,true);
    }
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function get_features($feature_type = '')
{
    $CI =& get_instance();
    $CI->db->where(['type'=>'features']);
    $query = $CI->db->get('settings');
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($feature_type)){
        return $data;
    }else{
        if(isset($data->$feature_type)){
            return $data->$feature_type;
        }else{
            return true;
        }
    }
}

function frontend_permissions($permissions_type = '')
{
    $CI =& get_instance();

    $CI->db->where(['type'=>'frontend']);
    $query = $CI->db->get('settings');
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($permissions_type)){
        return $data;
    }else{
        if(isset($data->$permissions_type)){
            return $data->$permissions_type;
        }else{
            return true;
        }
    }
} 

function is_module_allowed($module_type = '')
{
    $CI =& get_instance();

    if($CI->session->userdata('saas_id') == ''){
        return true;
    }
    if($CI->ion_auth->in_group(3)){
        return true;
    }
    $count_query = $CI->db->query("SELECT * FROM users_plans WHERE saas_id=".$CI->session->userdata('saas_id')." AND expired=1 AND (end_date >= CURDATE() || end_date IS NULL)");
    $count = $count_query->row_array();

    if($count){
            $current_plan = get_current_plan();
    }else{
        return false;
    }

    if($current_plan['modules'] != ''){
        $data = json_decode($current_plan['modules']);
        if(isset($data->{$module_type}) && $data->{$module_type} == 1){
            return true;
        }else{
            return false;
        } 
    }else{
        return true;
    }
} 

function my_plan_features($feature_type = '')
{
    $CI =& get_instance();

    if($CI->session->userdata('saas_id') == ''){
        return true;
    }
    $count_query = $CI->db->query("SELECT * FROM users_plans WHERE saas_id=".$CI->session->userdata('saas_id')." AND (end_date >= CURDATE() || end_date IS NULL)");
    $count = $count_query->row_array();
    if($count){
        $current_plan = get_current_plan();
    }else{
        return false;
    }

    if($current_plan[$feature_type] < 0){
        return true;
    }elseif($current_plan[$feature_type] == get_count('id',$feature_type,'saas_id='.$CI->session->userdata('saas_id'))){
        return false;
    }else{
        if($current_plan[$feature_type] < get_count('id',$feature_type,'saas_id='.$CI->session->userdata('saas_id'))){
            return false;
        }
        return true;
    }
    
} 

function get_current_plan($saas_id = ''){
    $CI =& get_instance();

    if(empty($saas_id)){
        $saas_id = $CI->session->userdata('saas_id');
    }

    if(empty($saas_id)){
        return false;
    }

    $left_join = " LEFT JOIN plans p ON up.plan_id=p.id ";

    $query = $CI->db->query("SELECT up.*,p.title,p.price,p.billing_type,p.projects,p.tasks,p.users,p.storage,p.modules FROM users_plans up $left_join WHERE up.saas_id=$saas_id ORDER BY up.created DESC LIMIT 1");
    $data = $query->row_array();

    if(!empty($data) && $saas_id){
        return $data;
    }else{
        return false;
    }
}

// function permissions($permissions_type = '')
// {
//     $CI =& get_instance();

//     $CI->db->where('type', 'permissions_'.$CI->session->userdata('saas_id'));
//     $count = $CI->db->get('settings');

//     if($count->num_rows() > 0){
//         $where_type = 'permissions_'.$CI->session->userdata('saas_id');
//     }else{
//         $where_type = 'permissions';
//     }

//     if($CI->ion_auth->in_group(4)){
//         $CI->db->where('type', 'clients_permissions_'.$CI->session->userdata('saas_id'));
//         $count = $CI->db->get('settings');

//         if($count->num_rows() > 0){
//             $where_type = 'clients_permissions_'.$CI->session->userdata('saas_id');
//         }else{
//             $where_type = 'permissions';
//         }
//     }

//     $CI->db->where(['type'=>$where_type]);
//     $query = $CI->db->get('settings');
//     $data = $query->result_array();

//     if(!$data){
//         return false;
//     }
    
//     $data = json_decode($data[0]['value']);

//     if(empty($permissions_type)){
//         return $data;
//     }else{
//         if(isset($data->$permissions_type)){
//             return $data->$permissions_type;
//         }else{
//             return false;
//         }
//     }
// }  

function permissions($permissions_type = '')
{
    $CI =& get_instance();

    $group = $CI->ion_auth->get_users_groups($CI->session->userdata('user_id'))->result();
    $role = $group[0]->name;
    $CI->db->where('type', $role.'_permissions_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');

    if($count->num_rows() > 0){
        $where_type = $role.'_permissions_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'permissions';
    }

    $CI->db->where(['type'=>$where_type]);
    $query = $CI->db->get('settings');
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($permissions_type)){
        return $data;
    }else{
        if(isset($data->$permissions_type)){
            return $data->$permissions_type;
        }else{
            return false;
        }
    }
} 

function get_permissions($permissions_type = '')
{
    $CI =& get_instance();

    $CI->db->where('type', $permissions_type.'_permissions_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');

    if($count->num_rows() > 0){
        $where_type = $permissions_type.'_permissions_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'permissions';
    }

    $CI->db->where(['type'=>$where_type]);
    $query = $CI->db->get('settings');
    $data = $query->result_array();
    
    $data = json_decode($data[0]['value']);
    return $data;
} 

function users_permissions($permissions_type = '')
{
    $CI =& get_instance();

    $CI->db->where('type', 'permissions_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');

    if($count->num_rows() > 0){
        $where_type = 'permissions_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'permissions';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($permissions_type)){
        return $data;
    }else{
        if(isset($data->$permissions_type)){
            return $data->$permissions_type;
        }else{
            return false;
        }
    }
} 
function is_client(){
    $CI =& get_instance();
    $saas_id = $CI->session->userdata('saas_id');
    $user_id = $CI->session->userdata('user_id');

    $query = $CI->db->select('groups.name AS group_name')
    ->from('users_groups')
    ->join('groups', 'users_groups.group_id = groups.id')
    ->where('users_groups.user_id', $user_id)
    ->where('groups.saas_id', $saas_id)
    ->where('groups.name', 'client')
    ->get();
    if ($query->num_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function selected_users() {
    $CI =& get_instance();
    $saas_id = $CI->session->userdata('saas_id');
    $user_id = $CI->session->userdata('user_id');

    // Step 1: Get the 'group_id' for the current user from the 'users_groups' table
    $CI->db->select('group_id');
    $CI->db->where('user_id', $user_id);
    $query = $CI->db->get('users_groups');

    if ($query->num_rows() > 0) {
        $row = $query->row();
        $group_id = $row->group_id;
        $row = $query->row();
        $group_id = $row->group_id;
         // Use the retrieved group_id to fetch assigned users from the groups table
        $CI->db->select('assigned_users');
        $CI->db->where('id', $group_id);
        $CI->db->where('saas_id', $saas_id);
        $query = $CI->db->get('groups');
        $row = $query->row();
        $assigned_users = $row->assigned_users;
        $assigned_users_array = json_decode($assigned_users);
        // $assigned_users_array[] = $user_id;
        return $assigned_users_array;
    }
    return 0;
}
function clients_permissions($permissions_type = '')
{
    $CI =& get_instance();

    $CI->db->where('type', 'clients_permissions_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');

    if($count->num_rows() > 0){
        $where_type = 'clients_permissions_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'clients_permissions';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($permissions_type)){
        return $data;
    }else{
        if(isset($data->$permissions_type)){
            return $data->$permissions_type;
        }else{
            return false;
        }
    }
} 

function project_status($field = '', $status_id = ''){
    $CI =& get_instance();
    if(!empty($field)){
        $CI->db->select($field);
    }
    $CI->db->from('project_status');
    if(!empty($status_id)){
        $CI->db->where(['id'=>$status_id]);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        foreach($data as $key => $project_title){
            $tmp[$key] = $project_title;
            if($project_title['title'] == 'Not Started'){
            $tmp[$key]['title'] = $CI->lang->line('not_started')?$CI->lang->line('not_started'):'Not Started';
            }elseif($project_title['title'] == 'On Going'){
            $tmp[$key]['title'] = $CI->lang->line('on_going')?$CI->lang->line('on_going'):'On Going';
            }elseif($project_title['title'] == 'Finished'){
            $tmp[$key]['title'] = $CI->lang->line('finished')?$CI->lang->line('finished'):'Finished';
            }
        }
        return $tmp;
    }else{
        return false;
    }
}

function priorities(){
    $CI =& get_instance();
    $CI->db->from('priorities');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        foreach($data as $key => $priority){
            $tmp[$key] = $priority;
            if($priority['title'] == 'Low'){
            $tmp[$key]['title'] = $CI->lang->line('low')?$CI->lang->line('low'):'Low';
            }elseif($priority['title'] == 'Medium'){
            $tmp[$key]['title'] = $CI->lang->line('medium')?$CI->lang->line('medium'):'Medium';
            }elseif($priority['title'] == 'High'){
            $tmp[$key]['title'] = $CI->lang->line('high')?$CI->lang->line('high'):'High';
            }
        }
        return $tmp;
    }else{
        return false;
    }
}

function task_status($id = ''){
    $CI =& get_instance();
    $CI->db->from('task_status');
    if(!empty($id)){
        $CI->db->where(['id'=>$id]);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        foreach($data as $key => $task_title){
            $tmp[$key] = $task_title;
            if($task_title['title'] == 'Todo'){
            $tmp[$key]['title'] = $CI->lang->line('todo')?$CI->lang->line('todo'):'Todo';
            }elseif($task_title['title'] == 'In Progress'){
            $tmp[$key]['title'] = $CI->lang->line('in_progress')?$CI->lang->line('in_progress'):'In Progress';
            }elseif($task_title['title'] == 'In Review'){
            $tmp[$key]['title'] = $CI->lang->line('in_review')?$CI->lang->line('in_review'):'In Review';
            }elseif($task_title['title'] == 'Completed'){
            $tmp[$key]['title'] = $CI->lang->line('completed')?$CI->lang->line('completed'):'Completed';
            }
        }
        return $tmp;
    }else{
        return false;
    }
}

function get_razorpay_key_id($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->razorpay_key_id)){
            return $data->razorpay_key_id;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_paystack_public_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paystack_public_key)){
            return $data->paystack_public_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_bank_details($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->bank_details)){
            return $data->bank_details;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_offline_bank_transfer($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->offline_bank_transfer)){
            return $data->offline_bank_transfer;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_stripe_publishable_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->stripe_publishable_key)){
            return $data->stripe_publishable_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}
function get_stripe_secret_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->stripe_secret_key)){
            return $data->stripe_secret_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_paystack_secret_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paystack_secret_key)){
            return $data->paystack_secret_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_razorpay_key_secret($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->razorpay_key_secret)){
            return $data->razorpay_key_secret;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_paypal_secret($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paypal_secret)){
            return $data->paypal_secret;
        }else{
            return true;
        }
    }else{
        return false;
    }
}

function get_payment_paypal($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paypal_client_id)){
            return $data->paypal_client_id;
        }else{
            return true;
        }
    }else{
        return false;
    }
}

function get_system_version(){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    $CI->db->where(['type'=>'system_version']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data[0]['value'];
    }else{
        return false;
    }
}

function is_my_project($id){
    $CI =& get_instance();
    if($CI->ion_auth->in_group(4)){
    $query = $CI->db->query("SELECT id FROM projects WHERE id=$id AND client_id=".$CI->session->userdata('user_id'));
    }else{
        $query = $CI->db->query("SELECT id FROM projects WHERE id=$id AND saas_id=".$CI->session->userdata('saas_id'));
    }
        
    $res = $query->result_array();
    if(!empty($res)){
        return true;
    }else{
        return false;
    } 
}

function get_earnings(){ 
    
    $CI =& get_instance();
    $query = $CI->db->query("SELECT sum(amount) AS amount FROM transactions WHERE status=1");
    $res = $query->result_array();
    if(!empty($res)){
        return $res[0]['amount']?$res[0]['amount']:0;
    }else{
        return false;
    }
    
}

function get_count($field,$table,$where = ''){ 
    if(!empty($where))
        $where = "where ".$where;
        
    $CI =& get_instance();
    $query = $CI->db->query("SELECT $field FROM ".$table." ".$where." ");
    $res = $query->result_array();
    if(!empty($res)){
        return count($res);
    }else{
        return 0;
    }
    
}

function get_email_library()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }

    $data = json_decode($data[0]['value']);

    if(!empty($data->email_library)){
        return $data->email_library;
    }else{
        return "codeigniter";
    }
}

function smtp_host()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }

    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_host)){
        return $data->smtp_host;
    }else{
        return false;
    }
} 

function smtp_port()
{
    $CI =& get_instance();

    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_port)){
        return $data->smtp_port;
    }else{
        return false;
    }
} 

function smtp_email()
{
    $CI =& get_instance();
    $CI->load->library('session');
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_username)){
        return $data->smtp_username;
    }else{
        return false;
    }
}

function smtp_password()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_password)){
        return $data->smtp_password;
    }else{
        return false;
    }
}

function smtp_encryption()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_encryption)){
        return $data->smtp_encryption;
    }else{
        return false;
    }
}

function company_name()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->company_name)){
        return $data->company_name;
    }else{
        return 'Your Company';
    }
} 

function company_email()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->company_email)){
        return $data->company_email;
    }else{
        return 'admin@admin.com';
    }
} 

function footer_text()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->footer_text)){
        return $data->footer_text;
    }else{
        return company_name().' '.date('Y').' All Rights Reserved';
    }
} 

function google_analytics()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->google_analytics)){
        return $data->google_analytics;
    }else{
        return false;
    }
} 

function mysql_timezone()
{
    $CI =& get_instance();

    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->mysql_timezone)){
        return $data->mysql_timezone;
    }else{
        return '-11:00';
    }
} 

function alert_days()
{
    $CI =& get_instance();
    

    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->alert_days) && !empty($data->alert_days)){
        return $data->alert_days;
    }else{
        return 1;
    }
} 

function php_timezone()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->php_timezone)){
        return $data->php_timezone;
    }else{
        return 'Pacific/Midway';
    }
} 

function system_date_format_js()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->date_format_js)){
        return $data->date_format_js;
    }else{
        return 'd-m-yyyy';
    }
} 

function system_time_format_js()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->time_format_js)){
        return $data->time_format_js;
    }else{
        return 'hh:MM A';
    }
} 

function count_days_btw_two_dates($today , $sec_date){
    $CI =& get_instance();
    $today=date_create($today);
    $sec_date=date_create($sec_date);
    $diff=date_diff($today,$sec_date);
    $data['days'] = $diff->format("%a");
    if($today < $sec_date || $today == $sec_date){
        $data['days_status'] = $CI->lang->line('left')?$CI->lang->line('left'):'Left';
    }else{
        $data['days_status'] = $CI->lang->line('overdue')?$CI->lang->line('overdue'):'Overdue';
    }
    return $data;
}

function format_date($date, $date_format){
    $date = str_replace('/', '-', $date);  
    $date = date_create($date);
    return date_format($date,$date_format);
}

function system_date_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->date_format)){
        return $data->date_format;
    }else{
        return 'd-m-Y';
    }
} 

function system_time_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->time_format)){
        return $data->time_format;
    }else{
        return 'h:i A';
    }
} 

function full_logo()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->full_logo)){
        return $data->full_logo;
    }else{
        return 'logo.png';
    }
} 

function file_upload_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->file_upload_format)){
        return $data->file_upload_format;
    }else{
        return 'jpg|png';
    }
}

function half_logo()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->half_logo)){
        return $data->half_logo;
    }else{
        return 'logo-half.png';
    }
} 

function favicon()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();
    
    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->favicon)){
        return $data->favicon;
    }else{
        return 'favicon.png';
    }
} 


function time_formats(){
    $CI =& get_instance();
    $CI->db->from('time_formats');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function date_formats(){
    $CI =& get_instance();
    $CI->db->from('date_formats');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function timezones(){
    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();
    
        $data = $offset = $added = array();
        foreach ($list as $abbr => $info) {
            foreach ($info as $zone) {
                if ( ! empty($zone['timezone_id'])
                    AND
                    ! in_array($zone['timezone_id'], $added)
                    AND 
                      in_array($zone['timezone_id'], $idents)) {
                    $z = new DateTimeZone($zone['timezone_id']);
                    $c = new DateTime(null, $z);
                    $zone['time'] = $c->format('H:i a');
                    $offset[] = $zone['offset'] = $z->getOffset($c);
                    $data[] = $zone;
                    $added[] = $zone['timezone_id'];
                }
            }
        }
    
        array_multisort($offset, SORT_ASC, $data);
        
        $i = 0;$temp = array();
        foreach ($data as $key => $row) {
            $temp[0] = $row['time'];
            $temp[1] = formatOffset($row['offset']);
            $temp[2] = $row['timezone_id'];
            $options[$i++] = $temp;
        }
        
        if(!empty($options)){
            return $options;
        }
}

function formatOffset($offset) {
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);

    if ($hour == 0 AND $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT).':'. str_pad($minutes,2, '0');
}

?>