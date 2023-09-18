<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <div class="section-header-back">
              <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
            <?=$this->lang->line('saas_admins')?$this->lang->line('saas_admins'):'SaaS Admins'?> <a href="#" id="modal-add-user" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('saas_admins')?$this->lang->line('saas_admins'):'SaaS Admins'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <?php
                if(isset($system_users) && !empty($system_users)){
                foreach ($system_users as $system_user) {
              ?>
              <div class="col-md-6">
                <div class="card card-primary profile-widget">
                  <div class="profile-widget-header mb-0">  
                    <span class="avatar-item mb-0"> 
                    <?php
                      if(isset($system_user['profile']) && !empty($system_user['profile'])){
                    ?>       
                      <img alt="image" src="<?=htmlspecialchars($system_user['profile'])?>" class="rounded-circle profile-widget-picture">
                    <?php }else{ ?>
                      <figure class="user-avatar avatar avatar-xl rounded-circle profile-widget-picture" data-initial="<?=htmlspecialchars($system_user['short_name'])?>"></figure>
                    <?php } ?>
                    <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)){ ?>
                      <a href="#" data-edit="<?=htmlspecialchars($system_user['id'])?>" class="avatar-badge modal-edit-user text-white" title="Edit" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a>
                    <?php } ?>
                    </span> 
                    <div class="profile-widget-items">
                      <div class="profile-widget-item">
                          <div class="profile-widget-item-label"><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></div>
                          <div class="profile-widget-item-value mt-1">
                            <?=htmlspecialchars($system_user['first_name'])?> <?=htmlspecialchars($system_user['last_name'])?>
                          </div>
                      </div>
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></div>
                        <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['active'])==1?'<span class="badge badge-success">'.($this->lang->line('active')?$this->lang->line('active'):'Active').'</span>':'<span class="badge badge-danger">'.($this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive').'</span>'?></div>
                      </div>
                    </div>
                    <div class="profile-widget-items">
                      <div class="profile-widget-item">
                          <div class="profile-widget-item-label"><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?></div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['email'])?></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                } }
              ?>

            </div>    
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<form action="<?=base_url('auth/create-user')?>" method="POST" class="modal-part" id="modal-add-user-part" data-title="<?=$this->lang->line('create_new_user')?$this->lang->line('create_new_user'):'Create New User'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('first_name')?$this->lang->line('first_name'):'First Name'?><span class="text-danger">*</span></label>
      <input type="text" name="first_name" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('last_name')?$this->lang->line('last_name'):'Last Name'?><span class="text-danger">*</span></label>
      <input type="text" name="last_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?><span class="text-danger">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('this_email_will_not_be_updated_latter')?$this->lang->line('this_email_will_not_be_updated_latter'):'This email will not be updated latter.'?>"></i></label>
      <input type="email" name="email"  class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('password')?$this->lang->line('password'):'Password'?><span class="text-danger">*</span></label>
      <input type="text" name="password"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('confirm_password')?$this->lang->line('confirm_password'):'Confirm Password'?><span class="text-danger">*</span></label>
      <input type="text" name="password_confirm"  class="form-control">
    </div>
    <input type="hidden" name="groups" value="3">
    <input type="hidden" name="create_saas_admin" value="1">
  </div>
</form>

<form action="<?=base_url('auth/edit-user')?>" method="POST" class="modal-part" id="modal-edit-user-part" data-title="<?=$this->lang->line('edit_user')?$this->lang->line('edit_user'):'Edit User'?>" data-btn_login="<?=$this->lang->line('login')?$this->lang->line('login'):'Login'?>" data-btn_delete="<?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?>" data-btn_update="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>" data-btn_active="<?=$this->lang->line('active')?$this->lang->line('active'):'Active'?>" data-btn_deactive="<?=$this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive'?>">
  <input type="hidden" name="update_id" id="update_id" value="">
  <input type="hidden" name="groups" value="3">
  <input type="hidden" name="old_profile_pic" id="old_profile_pic" value="">
  <div class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('first_name')?$this->lang->line('first_name'):'First Name'?><span class="text-danger">*</span></label>
      <input type="text" id="first_name" name="first_name" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('last_name')?$this->lang->line('last_name'):'Last Name'?><span class="text-danger">*</span></label>
      <input type="text" id="last_name" name="last_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('password')?$this->lang->line('password'):'Password'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')?$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password'):'Leave Password and Confirm Password empty for no change in Password.'?>"></i></label>
      <input type="text" name="password"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('confirm_password')?$this->lang->line('confirm_password'):'Confirm Password'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')?$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password'):'Leave Password and Confirm Password empty for no change in Password.'?>"></i></label>
      <input type="text" name="password_confirm"  class="form-control">
    </div>
  </div>
</form>
<div id="modal-edit-user"></div>
<?php $this->load->view('includes/js'); ?>
</body>
</html>
