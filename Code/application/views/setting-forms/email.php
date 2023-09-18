<form action="<?=base_url('settings/save-email-setting')?>" method="POST" id="setting-form">
    <div class="card-body row">
      
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('email_library')?htmlspecialchars($this->lang->line('email_library')):'Email Library'?></label>
        <select name="email_library" class="form-control">
          <option value="codeigniter" <?=(isset($email_library) && $email_library == 'codeigniter')?'selected':'';?> >CodeIgniter Email Library</option>
          <option value="phpmailer" <?=(isset($email_library) && $email_library == 'phpmailer')?'selected':'';?> >PHPMailer Email Library</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('from_email')?htmlspecialchars($this->lang->line('from_email')):'From Email'?></label>
        <input type="text" name="from_email" value="<?=htmlspecialchars($from_email)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('smtp_host')?$this->lang->line('smtp_host'):'SMTP Host'?></label>
        <input type="text" name="smtp_host" value="<?=htmlspecialchars($smtp_host)?>" class="form-control" required="">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('smtp_port')?$this->lang->line('smtp_port'):'SMTP Port'?></label>
        <input type="text" name="smtp_port" value="<?=htmlspecialchars($smtp_port)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('username_email')?$this->lang->line('username_email'):'Username/Email'?></label>
        <input type="text" name="smtp_username" value="<?=htmlspecialchars($smtp_username)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('password')?$this->lang->line('password'):'Password'?></label>
        <input type="text" name="smtp_password" value="<?=htmlspecialchars($smtp_password)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('encryption')?$this->lang->line('encryption'):'Encryption'?></label>
        <select name="smtp_encryption" class="form-control">
          <option value="none" <?=(isset($smtp_encryption) && $smtp_encryption == '')?'selected':'';?> >None</option>
          <option value="ssl" <?=(isset($smtp_encryption) && $smtp_encryption == 'ssl')?'selected':'';?> >SSL</option>
          <option value="tls" <?=(isset($smtp_encryption) && $smtp_encryption == 'tls')?'selected':'';?> >TLS</option>
        </select>
      </div>
      
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('send_test_mail_to')?$this->lang->line('send_test_mail_to'):'Send test mail to'?></label>
        <input type="text" name="email" value="" class="form-control">
      </div>
    </div>
    <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)){ ?>
      <div class="card-footer bg-whitesmoke text-md-right">
        <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
      </div>
    <?php } ?>
    <div class="result"></div>
  </form>