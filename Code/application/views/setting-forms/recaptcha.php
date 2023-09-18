<form action="<?=base_url('settings/save-recaptcha-setting')?>" method="POST" id="language-form">

    <div class="card-header">
      <h4><?=$this->lang->line('google_recaptcha')?$this->lang->line('google_recaptcha'):'Google reCAPTCHA'?> V3</h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('site_key')?htmlspecialchars($this->lang->line('site_key')):'Site Key'?></label>
        <input type="text" name="site_key" value="<?=(isset($site_key) && !empty($site_key))?htmlspecialchars($site_key):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('secret_key')?htmlspecialchars($this->lang->line('secret_key')):'Secret Key'?></label>
        <input type="text" name="secret_key" value="<?=(isset($secret_key) && !empty($secret_key))?htmlspecialchars($secret_key):''?>" class="form-control">
      </div>
    </div>

    <div class="card-footer bg-whitesmoke text-md-right">
      <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
    </div>
    <div class="result"></div>
</form>