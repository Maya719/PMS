<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LanguageLoaderHook {

    function LoadLanguage() {
        $ci =& get_instance();
        $lang = $ci->session->userdata('lang');
        if($lang){
            if(get_languages('', $lang)){
                $ci->lang->load('custom_labels',$lang);
            }else{
                $default_language = default_language();
                if($default_language){
                    if(get_languages('', $default_language)){
                        $ci->lang->load('custom_labels',$default_language);
                        $ci->config->set_item('language', $default_language);
                    }else{
                        $ci->lang->load('custom_labels','english');
                    }
                }else{
                    $ci->lang->load('custom_labels','english');
                }
            }
        }else{
            $default_language = default_language();
            if($default_language){
                if(get_languages('', $default_language)){
                    $ci->lang->load('custom_labels',$default_language);
                    $ci->config->set_item('language', $default_language);
                }else{
                    $ci->lang->load('custom_labels','english');
                }
            }else{
                $ci->lang->load('custom_labels','english');
            }
        }
    }

}
