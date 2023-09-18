<form action="<?=base_url('settings/save-custom-code-setting')?>" method="POST" id="language-form">
    <div class="card-body row">
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('header_code')?$this->lang->line('header_code'):'Header Code'?></label>
          <textarea name="header_code" id="header_code"><?=$header_code?></textarea>
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('footer_code')?$this->lang->line('footer_code'):'Footer Code'?></label>
        <textarea name="footer_code" id="footer_code"><?=$footer_code?></textarea>
      </div>
    </div>
    <?php if($this->ion_auth->in_group(3)){ ?>
      <div class="card-footer bg-whitesmoke text-md-right">
        <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
      </div>
    <?php } ?>
    <div class="result"></div>
  </form>