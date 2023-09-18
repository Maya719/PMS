<form action="<?=base_url('settings/save-general-setting')?>" method="POST" id="setting-form">
    <div class="card-body row">

      <?php if ($this->ion_auth->in_group(3)){ ?>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('company_name')?$this->lang->line('company_name'):'Company Name'?><span class="text-danger">*</span></label>
        <input type="text" name="company_name" value="<?=htmlspecialchars($company_name)?>" class="form-control" required="">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('footer_text')?$this->lang->line('footer_text'):'Footer Text'?><span class="text-danger">*</span></label>
        <input type="text" name="footer_text" value="<?=htmlspecialchars($footer_text)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('google_analytics')?$this->lang->line('google_analytics'):'Google Analytics'?></label>
        <input type="text" name="google_analytics" value="<?=htmlspecialchars($google_analytics)?>" class="form-control">
      </div>
      
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('show_subscription_plan_expiry_alert_before')?$this->lang->line('show_subscription_plan_expiry_alert_before'):'Show Subscription Plan Expiry Alert Before'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date')?$this->lang->line('this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date'):'This will show alert box in main dashboard to the user about their plan expiry date.'?>"></i></label>
        <select name="alert_days" id="alert_days" class="form-control select2">
        <?php
          $alert_days_list = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
          foreach($alert_days_list as $alert_day){ ?>
            <option value="<?=htmlspecialchars($alert_day)?>" <?=(isset($alert_days) && $alert_days == $alert_day)?'selected':'';?>><?=htmlspecialchars($alert_day)?> Days</option>
          <?php } ?>
        </select>
      </div>
      <?php } ?>

      <?php if (is_module_allowed('custom_currency')){ ?> 
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('currency_code')?$this->lang->line('currency_code'):'Currency Code'?><span class="text-danger">*</span><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('currency_code_need_as_per_three_letter_iso_code')?$this->lang->line('currency_code_need_as_per_three_letter_iso_code'):'Currency code need as per three letter ISO code. Make sure payment gateways supporting this currency.'?>"></i></label>
        <input type="text" name="currency_code" value="<?=isset($currency_code)?htmlspecialchars($currency_code):'USD'?>" class="form-control" required="">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('currency_symbol')?$this->lang->line('currency_symbol'):'Currency Symbol'?><span class="text-danger">*</span></label>
        <input type="text" name="currency_symbol" value="<?=isset($currency_symbol)?htmlspecialchars($currency_symbol):'$'?>" class="form-control" required="">
      </div>
      <?php } ?> 

      <div class="form-group col-md-6">
        <label><?=$this->lang->line('timezone')?$this->lang->line('timezone'):'Timezone'?><span class="text-danger">*</span></label>
        <input type="hidden" id="mysql_timezone" name="mysql_timezone" value="<?=htmlspecialchars($mysql_timezone)?>">
        <select name="php_timezone" id="php_timezone" class="form-control select2">
          <?php foreach($timezones as $option){ ?>
            <option value="<?=htmlspecialchars($option[2])?>" data-gmt="<?=htmlspecialchars($option['1']);?>" <?=(isset($php_timezone) && $php_timezone == $option[2])?'selected':'';?>><?=htmlspecialchars($option[2])?> - GMT <?=htmlspecialchars($option[1])?> - <?=htmlspecialchars($option[0])?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('date_format')?$this->lang->line('date_format'):'Date Format'?><span class="text-danger">*</span></label>
        <input type="hidden" id="date_format_js" name="date_format_js" value="<?=isset($date_format_js)?htmlspecialchars($date_format_js):''?>">
        <select name="date_format" id="date_format" class="form-control select2">
          <?php foreach($date_formats as $option){ ?>
            <option data-js_value="<?=htmlspecialchars($option['js_format'])?>" value="<?=htmlspecialchars($option['format'])?>" <?=(isset($date_format) && $date_format == $option['format'])?'selected':'';?>><?=htmlspecialchars($option['format'])?> (<?=date(htmlspecialchars($option['format']))?>)</option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('time_format')?$this->lang->line('time_format'):'Time Format'?><span class="text-danger">*</span></label>
        <input type="hidden" id="time_format_js" name="time_format_js" value="<?=isset($time_format_js)?htmlspecialchars($time_format_js):''?>">
        <select name="time_format" id="time_format" class="form-control">
          <?php foreach($time_formats as $option){ ?>
            <option data-js_value="<?=htmlspecialchars($option['js_format'])?>" value="<?=htmlspecialchars($option['format'])?>" <?=(isset($time_format) && $time_format == $option['format'])?'selected':'';?>><?=htmlspecialchars($option['description'])?> (<?=date(htmlspecialchars($option['format']))?>)</option>
          <?php } ?>
        </select>
      </div>

      <?php $languages = get_languages('', '', 1);
        if($languages){ ?>
        <div class="form-group col-md-6">
          <label><?=$this->lang->line('default_language')?$this->lang->line('default_language'):'Default Language'?><span class="text-danger">*</span></label>
          <select name="default_language" id="default_language" class="form-control select2">
            <?php foreach($languages as $language){ ?>
              <option value="<?=htmlspecialchars($language['language'])?>" <?=(isset($default_language) && $default_language == $language['language'])?'selected':'';?>><?=htmlspecialchars(ucfirst($language['language']))?></option>
            <?php } ?>
          </select>
        </div>
      <?php } ?>

      <div class="form-group col-md-6">
        <label><?=$this->lang->line('file_upload_format')?$this->lang->line('file_upload_format'):'File Upload Format'?><span class="text-danger">*</span><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('only_this_type_of_files_going_to_be_allowed_to_upload_in_projects_and_tasks')?$this->lang->line('only_this_type_of_files_going_to_be_allowed_to_upload_in_projects_and_tasks'):'Only this type of files going to be allowed to upload in projects and tasks.'?>"></i></label>
        <input type="text" name="file_upload_format" value="<?=htmlspecialchars($file_upload_format)?>" class="form-control">
      </div>
      
      <?php if ($this->ion_auth->in_group(3)){ ?>
      
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('required_email_confirmation_for_new_users')?$this->lang->line('required_email_confirmation_for_new_users'):'Required email confirmation for new users'?><span class="text-danger">*</span></label>
        <select name="email_activation" id="email_activation" class="form-control">
          <option value="0" <?=(isset($email_activation) && $email_activation == 0)?'selected':'';?>><?=$this->lang->line('no')?htmlspecialchars($this->lang->line('no')):'No'?></option>
          <option value="1" <?=(isset($email_activation) && $email_activation == 1)?'selected':'';?>><?=$this->lang->line('yes')?htmlspecialchars($this->lang->line('yes')):'Yes'?></option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('turn_off_new_user_registration')?htmlspecialchars($this->lang->line('turn_off_new_user_registration')):'Turn off new user registration'?><span class="text-danger">*</span></label>
        <select name="turn_off_new_user_registration" id="turn_off_new_user_registration" class="form-control">
          <option value="0" <?=(isset($turn_off_new_user_registration) && $turn_off_new_user_registration == 0)?'selected':'';?>><?=$this->lang->line('no')?htmlspecialchars($this->lang->line('no')):'No'?></option>
          <option value="1" <?=(isset($turn_off_new_user_registration) && $turn_off_new_user_registration == 1)?'selected':'';?>><?=$this->lang->line('yes')?htmlspecialchars($this->lang->line('yes')):'Yes'?></option>
        </select>
      </div>

      <div class="form-group col-md-12">
        <label><label><?=$this->lang->line('theme_color')?$this->lang->line('theme_color'):'Theme Color'?><span class="text-danger">*</span></label></label>
        <input type="color" name="theme_color" value="<?=htmlspecialchars($theme_color)?>" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <img alt="Full Logo" id="full_logo-img" src="<?=base_url('assets/uploads/logos/'.htmlspecialchars($full_logo))?>" class="system-logos">
          <input type="hidden" name="full_logo_old" value="<?=htmlspecialchars($full_logo)?>">
        <div class="custom-file form-group mt-1">
          <input type="file" name="full_logo" class="custom-file-input" id="full_logo">
          <label class="custom-file-label" for="full_logo"><?=$this->lang->line('full_logo')?$this->lang->line('full_logo'):'Full Logo'?></label>
        </div>
      </div>
      <div class="form-group col-md-4">
        <img alt="Half Logo" id="half_logo-img" src="<?=base_url('assets/uploads/logos/'.htmlspecialchars($half_logo))?>" class="system-logos">
          <input type="hidden" name="half_logo_old" value="<?=htmlspecialchars($half_logo)?>">
        <div class="custom-file mt-1">
          <input type="file" name="half_logo" class="custom-file-input" id="half_logo">
          <label class="custom-file-label" for="half_logo"><?=$this->lang->line('half_logo')?$this->lang->line('half_logo'):'Half Logo'?></label>
        </div>
      </div>
      <div class="form-group col-md-4">
        <img alt="Favicon" id="favicon-img" src="<?=base_url('assets/uploads/logos/'.htmlspecialchars($favicon))?>" class="system-logos">
          <input type="hidden" name="favicon_old" value="<?=htmlspecialchars($favicon)?>">
        <div class="custom-file mt-1">
          <input type="file" name="favicon" class="custom-file-input" id="favicon">
          <label class="custom-file-label" for="favicon"><?=$this->lang->line('favicon')?$this->lang->line('favicon'):'Favicon'?></label>
        </div>
      </div>
      <?php } ?>

    </div>
    <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)){ ?>
      <div class="card-footer bg-whitesmoke text-md-right">
        <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
      </div>
    <?php } ?>
    <div class="result"></div>
  </form>