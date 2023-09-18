<form action="<?=base_url('settings/save-update-setting')?>" method="POST" id="setting-update-form" enctype="multipart/form-data">
  <div class="card-header">
    <h4><?=$this->lang->line('current_system_version')?$this->lang->line('current_system_version'):'Current System Version'?> <?=htmlspecialchars(get_system_version())?></h4>
  </div>
  <div class="card-body row">
    <div class="jumbotron text-center">
      <h2><?=$this->lang->line('update_guide')?$this->lang->line('update_guide'):'Update Guide'?></h2>
      <p class="lead text-muted mt-3"><?=$this->lang->line('select_the_update_zip_file_and_hit_install_update_button')?$this->lang->line('select_the_update_zip_file_and_hit_install_update_button'):'Select the update zip file and hit Install Update button.'?></p>
      <p class="lead text-danger"><?=$this->lang->line('please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file')?$this->lang->line('please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file'):'Please take a backup before going further. Follow the further instructions given with the update file.'?><p>
    </div>
    <div class="custom-file">
      <input type="file" name="update" class="custom-file-input" id="update">
      <label class="custom-file-label" for="update"><?=$this->lang->line('choose_file')?$this->lang->line('choose_file'):'Choose file'?></label>
    </div>
  </div>
  <?php if ($this->ion_auth->in_group(3)){ ?>
    <div class="card-footer bg-whitesmoke text-md-right">
      <button class="btn btn-primary savebtn"><?=$this->lang->line('install_update')?$this->lang->line('install_update'):'Install Update'?></button>
    </div>
  <?php } ?>
  <div class="result"></div>
</form>