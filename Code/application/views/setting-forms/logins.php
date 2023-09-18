<form action="<?=base_url('settings/save-logins-setting')?>" method="POST" id="language-form">

    <div class="card-header">
      <h4><?=$this->lang->line('social_login')?htmlspecialchars($this->lang->line('social_login')):'Social Login'?></h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('google_client_id')?htmlspecialchars($this->lang->line('google_client_id')):'Google Client ID'?></label>
        <input type="text" name="google_client_id" value="<?=(isset($google_client_id) && !empty($google_client_id))?htmlspecialchars($google_client_id):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('google_client_secret')?htmlspecialchars($this->lang->line('google_client_secret')):'Google Client Secret'?></label>
        <input type="text" name="google_client_secret" value="<?=(isset($google_client_secret) && !empty($google_client_secret))?htmlspecialchars($google_client_secret):''?>" class="form-control">
      </div>
    </div>

    <div class="card-footer bg-whitesmoke text-md-right">
      <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
    </div>
    <div class="result"></div>
</form>