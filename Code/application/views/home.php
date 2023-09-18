<?php $this->load->view('includes/head'); ?>
<style>
  #cookie-bar2 .cookie-bar-btn,
#cookie-bar2 .cookie-bar-btn:after { -webkit-transition: all .5s ease-in-out; -moz-transition: all .5s ease-in-out; -ms-transition: all .5s ease-in-out; -o-transition: all .5s ease-in-out; transition: all .5s ease-in-out; }
#cookie-bar2 { display: none; position: fixed; bottom: 19px; right: 80px; z-index: 9999; overflow: hidden; width: 300px; min-height: 20px; padding: 14px 0; background: #404040; text-align: center; border-radius: 4px; }
#cookie-bar2 * { margin: 0; outline: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
#cookie-bar2 .cookie-bar-body { width: 90%; margin: 0 auto; }
#cookie-bar2 a[href^=tel] { color: inherit; }
#cookie-bar2 a:focus,
#cookie-bar2 button:focus { outline: unset; outline: none; }
#cookie-bar2 p { font-size: 18px; line-height: 1.4; color: #fff; font-weight: 400; }
#cookie-bar2 .cookie-bar-action { padding-top: 10px; }
#cookie-bar2 .cookie-bar-btn:hover,
#cookie-bar2 .cookie-bar-btn:focus { text-decoration: none; }
#cookie-bar2 .cookie-bar-btn:after { position: absolute; top: 0; right: 52%; bottom: 0; left: 52%; z-index: -1; border-bottom: 4px solid #14428d; background: rgba(20, 66, 141, .3); content: ''; }
#cookie-bar2 .cookie-bar-btn:hover:after,
#cookie-bar2 .cookie-bar-btn:focus:after { right: 0; left: 0; }
@media only screen and (max-width: 767px) {
#cookie-bar2 { padding: 15px 0; }
#cookie-bar2 .cookie-bar-body { width: 96%; }
#cookie-bar2 p { font-size: 16px; }
}
</style>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></h1>
          </div>

          <?php if($this->ion_auth->is_admin()){ 
            $my_plan = get_current_plan();
            if($my_plan && !is_null($my_plan['end_date']) && ($my_plan['expired'] == 0 || $my_plan['end_date'] <= date('Y-m-d',date(strtotime("+".alert_days()." day", strtotime(date('Y-m-d'))))))){ 
          ?>
          <div class="row mb-4">
            <div class="col-md-12">
              <div class="hero text-white bg-danger">
                <div class="hero-inner">
                  <h2><?=$this->lang->line('alert')?$this->lang->line('alert'):'Alert...'?></h2>
                  <?php 
                    if($my_plan['expired'] == 0){ 
                  ?>
                    <p class="lead"><?=$this->lang->line('your_subscription_plan_has_been_expired_on_date')?$this->lang->line('your_subscription_plan_has_been_expired_on_date'):'Your subscription plan has been expired on date'?> <?=htmlspecialchars(format_date($my_plan["end_date"],system_date_format()))?>. <?=$this->lang->line('renew_it_now')?$this->lang->line('renew_it_now'):'Renew it now.'?></p>
                  <?php }elseif($my_plan['end_date'] <= date('Y-m-d',date(strtotime("+".alert_days()." day", strtotime(date('Y-m-d')))))){ ?>
                    <p class="lead"><?=$this->lang->line('your_current_subscription_plan_is_expiring_on_date')?$this->lang->line('your_current_subscription_plan_is_expiring_on_date'):'Your current subscription plan is expiring on date'?> <?=htmlspecialchars(format_date($my_plan["end_date"],system_date_format()))?>.</p>
                  <?php } ?>
                  <div class="mt-4">
                    <a href="<?=base_url('plans')?>" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-arrow-right"></i> <?=$this->lang->line('renew_plan')?$this->lang->line('renew_plan'):'Renew Plan.'?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } } ?>
          <?php
          if($this->ion_auth->is_admin() || permissions('attendance_view')){
            ?>
          <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-6">
              <h5>AMS</h5>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
              <?php
              $currentDate = date("d M, Y"); 
                echo '<h5 class="text-right">'.$currentDate.'</h5>';
              ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-primary card-statistic-2">
                <div class="card-stats">
                  <?php
                  if($this->ion_auth->is_admin()){
                  ?>
                  <div class="card-stats-title"><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'Today Attendance'?> - 
                  <?php
                  }else{
                  ?>
                  <div class="card-stats-title"><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'This Month Attendance(Day`s)'?> - 
                  <?php }
                  ?>                  
                  <div class="dropdown d-inline">
                      <a href="<?=base_url('attendance')?>"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></a>
                    </div>
                  </div>
                  <div class="card-stats-items mb-3">
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count" id="presents">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('presents')?$this->lang->line('presents'):'Presents'?></div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count" id="leaves">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves'?></div>
                    </div>
                    <div class="card-stats-item">
                      <div class="card-stats-item-count text-primary" id="absents">
                      </div>
                      <div class="card-stats-item-label text-primary"><?=$this->lang->line('absents')?$this->lang->line('absents'):'Absents'?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-primary card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title"><?=$this->lang->line('leaves_request')?$this->lang->line('leaves_request'):'Total Leaves Requests(This month)'?> - 
                    <div class="dropdown d-inline">
                      <a href="<?=base_url('leaves')?>"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></a>
                    </div>
                  </div>
                  <div class="card-stats-items mb-3">
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count" id="leave_pending">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('pending')?$this->lang->line('pending'):'Pending'?></div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count" id="leave_approved">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('approved')?$this->lang->line('approved'):'Approved'?></div>
                    </div>
                    <div class="card-stats-item text-primary">
                      <div class="card-stats-item-count" id="leave_rejected">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected'?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-primary card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title"><?=$this->lang->line('biometric')?$this->lang->line('biometric'):'Total Biometric Requests(This month)'?> - 
                    <div class="dropdown d-inline">
                      <a href="<?=base_url('biometric_missing')?>"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></a>
                    </div>
                  </div>
                  <div class="card-stats-items mb-3">
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count" id="bio_pending">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('pending')?$this->lang->line('pending'):'Pending'?></div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count" id="bio_approved">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('approved')?$this->lang->line('approved'):'Approved'?></div>
                    </div>
                    <div class="card-stats-item text-primary">
                      <div class="card-stats-item-count" id="bio_rejected">
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('Rejected')?$this->lang->line('Rejected'):'Rejected'?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
            }
            ?>
            <?php
          if($this->ion_auth->is_admin() || permissions('project_view')){
            ?>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5>PMS</h5>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-primary card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title"><?=$this->lang->line('project_statistics')?$this->lang->line('project_statistics'):'Project Statistics'?> - 
                    <div class="dropdown d-inline">
                      <a href="<?=base_url('projects')?>"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></a>
                    </div>
                  </div>
                  <div class="card-stats-items mb-3">
                    <?php
                      if($this->ion_auth->is_admin() || permissions('project_view_all')){
                        $pendingP = get_count('id','projects','(status=1 OR status=2) AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
                      }elseif(is_client()){
                        $pendingP =  get_count('id','projects','(status=1 OR status=2) AND client_id='.htmlspecialchars($this->session->userdata('user_id')));
                      }elseif(permissions('project_view_selected')){
                        $selectedUsers = selected_users();
                        foreach ($selectedUsers as $selectedUser) {
                          $pendingP += get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','(status=1 OR status=2) AND pu.user_id='.htmlspecialchars($selectedUser));
                        }
                      }else{
                        $pendingP = get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','(status=1 OR status=2) AND pu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
                      }
                      
                      if($this->ion_auth->is_admin() || permissions('project_view_all')){
                        $completedP = get_count('id','projects','status=3 AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
                      }elseif(is_client()){
                        $completedP =  get_count('id','projects','status=3 AND client_id='.htmlspecialchars($this->session->userdata('user_id')));
                      }elseif(permissions('project_view_selected')){
                        $selectedUsers = selected_users();
                        foreach ($selectedUsers as $selectedUser) {
                          $completedP += get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','status=3 AND pu.user_id='.htmlspecialchars($selectedUser));
                        }
                      }else{
                        $completedP = get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','status=3 AND pu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
                      }
                    ?> 
                    <div class="card-stats-item">
                      <div class="card-stats-item-count text-primary">
                      <?=htmlspecialchars($pendingP)+htmlspecialchars($completedP)?>
                      </div>
                      <div class="card-stats-item-label text-primary"><?=$this->lang->line('total')?$this->lang->line('total'):'Total'?></div>
                    </div>
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count">
                      <?php
                        echo htmlspecialchars($pendingP);
                      ?>
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('pending')?$this->lang->line('pending'):'Pending'?></div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count">
                      <?php
                        echo htmlspecialchars($completedP);
                      ?>
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('completed')?$this->lang->line('completed'):'Completed'?></div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-primary card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title"><?=$this->lang->line('tasks_statistics')?$this->lang->line('tasks_statistics'):'Tasks Statistics'?> - 
                    <div class="dropdown d-inline">
                      <a href="<?=base_url('projects/tasks')?>"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></a>
                    </div>
                  </div>
                  <div class="card-stats-items mb-3">
                    <?php 
                      if($this->ion_auth->is_admin() || permissions('project_view_all')){
                        $pendingT =  get_count('id','tasks','(status=1 OR status=2 OR status=3) AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
                      }elseif(is_client()){
                        $pendingT = get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id','(t.status=1 OR t.status=2 OR t.status=3) AND p.client_id = '.htmlspecialchars($this->session->userdata('user_id')));
                      }elseif(permissions('project_view_selected')){
                        $selectedUsers = selected_users();
                        foreach ($selectedUsers as $selectedUser) {
                          $pendingT =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','(status=1 OR status=2 OR status=3) AND tu.user_id='.htmlspecialchars($selectedUser));
                        }
                      }else{
                        $pendingT =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','(status=1 OR status=2 OR status=3) AND tu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
                      }
                      if($this->ion_auth->is_admin() || permissions('project_view_all')){
                        $completedT =  get_count('id','tasks','status=4 AND saas_id='.$this->session->userdata('saas_id'));
                      }elseif(is_client()){
                        $completedT = get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id','t.status=4 AND p.client_id = '.htmlspecialchars($this->session->userdata('user_id')));
                      }elseif(permissions('project_view_selected')){
                        $selectedUsers = selected_users();
                        foreach ($selectedUsers as $selectedUser) {
                          $completedT +=  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','status=4 AND tu.user_id='.$selectedUser);
                        }
                      }else{
                        $completedT =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','status=4 AND tu.user_id='.$this->session->userdata('user_id'));
                      }

                    ?>
                    <div class="card-stats-item text-primary">
                      <div class="card-stats-item-count">
                      <?=htmlspecialchars($pendingT)+htmlspecialchars($completedT)?>
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('total')?$this->lang->line('total'):'Total'?></div>
                    </div>
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count">
                      <?php
                          echo htmlspecialchars($pendingT);
                      ?>
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('pending')?$this->lang->line('pending'):'Pending'?></div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count">
                      <?php
                          echo htmlspecialchars($completedT);
                      ?>
                      </div>
                      <div class="card-stats-item-label"><?=$this->lang->line('completed')?$this->lang->line('completed'):'Completed'?></div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            
            <!-- <?php if($this->ion_auth->is_admin() || is_client()){ 
              $get_my_invoices_details = get_my_invoices_details();
            ?>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card-primary card-statistic-2">
                  <div class="card-stats">
                    <div class="card-stats-title"><?=$this->lang->line('invoices')?$this->lang->line('invoices'):'Invoices'?> (<?=get_currency('currency_symbol')?>)</div>
                    <div class="card-stats-items mb-3">
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count"><?=htmlspecialchars($get_my_invoices_details['total'])?></div>
                        <div class="card-stats-item-label"><?=$this->lang->line('total')?$this->lang->line('total'):'Total'?></div>
                      </div>
                      <div class="card-stats-item text-danger">
                        <div class="card-stats-item-count"><?=htmlspecialchars($get_my_invoices_details['due'])?></div>
                        <div class="card-stats-item-label"><?=$this->lang->line('due')?$this->lang->line('due'):'Due'?></div>
                      </div>
                      <div class="card-stats-item text-success">
                        <div class="card-stats-item-count"><?=htmlspecialchars($get_my_invoices_details['paid'])?></div>
                        <div class="card-stats-item-label"><?=$this->lang->line('paid')?$this->lang->line('paid'):'Paid'?></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php }?> -->
          </div>
          <?php }?>
          <div class="row">
          <?php
          if($this->ion_auth->is_admin() || permissions('project_view')){
            ?>
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h4><?=$this->lang->line('project_statistics')?htmlspecialchars($this->lang->line('project_statistics')):'Project Statistics'?></h4>
                </div>
                <div class="card-body">
                  <canvas id="project_chart" height="auto"></canvas>
                </div>
              </div>
            </div>
            <?php }?>
            <?php
              if($this->ion_auth->is_admin() || permissions('project_view')){
            ?>
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h4><?=$this->lang->line('tasks_statistics')?$this->lang->line('tasks_statistics'):'Tasks Statistics'?></h4>
                </div>
                <div class="card-body">
                  <canvas id="task_chart" height="auto"></canvas>
                </div>
              </div>
            </div>
            <?php }?>
          </div>
        </section>
      </div>
      <?php if(is_module_allowed('attendance') && $this->session->userdata('alerts') == '1' && $current_user->finger_config == '1'){ 
        $this->session->set_userdata('alerts', '0');
        ?>
      <div id="cookie-bar2">
        <div class="cookie-bar-body">
          <p id="time"></p>
          <div class="cookie-bar-action">
            <button id = "close_alart" type="button" class="text-uppercase btn btn-primary cookie-bar-btn"><?=$this->lang->line('ok')?$this->lang->line('ok'):'OK'?></button>
          </div>
        </div>
      </div>
      <?php } ?>
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<form action="<?=base_url('meetings/edit')?>" method="POST" class="modal-part" id="modal-edit-meetings-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">

<input type="hidden" name="update_id" id="update_id">

<div class="form-group">
  <label><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?><span class="text-danger">*</span></label>
  <input type="text" name="title" id="title" class="form-control" required="">
</div>

<div class="form-group">
  <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
  <input type="text" name="starting_date_and_time" id="starting_date_and_time" class="form-control datetimepicker">
</div>
<div class="form-group">
  <label><?=$this->lang->line('duration')?$this->lang->line('duration'):'Duration (Minutes)'?><span class="text-danger">*</span></label>
  <input type="number" pattern="[0-9]" name="duration" id="duration" class="form-control">
</div>

<div class="form-group">
  <label><?=$this->lang->line('users')?$this->lang->line('users'):'Users'?><span class="text-danger">*</span></label>
  <select name="users[]" id="users" class="form-control select2" multiple="">
    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
    <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
    <?php } } ?>
  </select>
</div>

<div class="form-group">
  <label><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?><span class="text-danger">*</span></label>
  <select name="status" id="status" class="form-control select2">
    <option value="0"><?=$this->lang->line('scheduled')?$this->lang->line('scheduled'):'Scheduled'?></option>
    <option value="1"><?=$this->lang->line('running')?$this->lang->line('running'):'Running'?></option>
    <option value="2"><?=$this->lang->line('completed')?$this->lang->line('completed'):'Completed'?></option>
  </select>
</div>

</form>

<div id="modal-edit-meetings"></div>

<?php
  foreach($project_status as $project_title){
    $tmpP[] = $project_title['title'];
    if($this->ion_auth->is_admin() || permissions('project_view_all')){
      $tmpPV[] =  get_count('id','projects','status='.$project_title['id'].' AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
    }elseif(is_client()){
      $tmpPV[] =  get_count('id','projects','client_id='.htmlspecialchars($this->session->userdata('user_id')).' AND status='.htmlspecialchars($project_title['id']));
    }elseif(permissions('project_view_selected')){
      $selectedUsers = selected_users();
      foreach ($selectedUsers as $selectedUser) {
        $chartSelector += get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','status='.$project_title['id'].' AND pu.user_id='.htmlspecialchars($selectedUser));
      }
      $tmpPV[] =  $chartSelector;
    }else{
      $tmpPV[] =  get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','status='.$project_title['id'].' AND pu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
    }
  }

  foreach($task_status as $task_title){
    $tmpT[] = $task_title['title'];
    if($this->ion_auth->is_admin() || permissions('project_view_all')){
      $tmpTV[] =  get_count('id','tasks','status='.htmlspecialchars($task_title['id']).' AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
    }elseif(is_client()){
      $tmpTV[] =  get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id','p.client_id = '.htmlspecialchars($this->session->userdata('user_id')).' AND t.status = '.htmlspecialchars($task_title['id']));
    }else{
      $tmpTV[] =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','status='.htmlspecialchars($task_title['id']).' AND tu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
    }
  }

?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var cookieBar = document.getElementById("cookie-bar2");
    var close_alart = document.getElementById("close_alart");
    var id = <?= $this->session->userdata('user_id') ?>;
        var data = {
          "user_id": id,
        };
        
        $.ajax({
          url: '<?= base_url('attendance/get_user_checkin_time') ?>', // Replace with your actual API URL
          type: 'POST', // Adjust the request method if needed
          data: data,
          success: function(response) {
              var data = JSON.parse(response);
              if (data.remind == 1) {
                <?php if(is_module_allowed('attendance') && $this->session->userdata('reminder') == '1' && $current_user->finger_config == '1'){ 
                    $this->session->set_userdata('reminder', '0');
                ?>
                  swal({
                  title: 'Attendance Reminder',
                  text: 'Yesterday\'s attendance is not recorded. Please remember to record it.',
                  icon: 'info',
                  dangerMode: true,
                  buttons: ['Cancel', 'OK'],
                });
                <?php
                }
                ?>
              }
              console.log(data);
              if (data.time === "" || data.time == null) {
                var string ='Notice! '+data.user+'<br> Your Punch is not Recorded Yet';
              }else{
                var string ='Hello '+data.user+'<br> Your Punch Time is : '+data.time;
              }
              $("#time").html(string);
            
          },
          error: function(jqXHR, textStatus, errorThrown) {
              // Handle errors
          }
      });
    setTimeout(function () {
        cookieBar.style.display = "block"; // Show the cookie bar
        close_alart.addEventListener("click", function () {
            cookieBar.style.display = "none"; // Hide the cookie bar
        });

        setTimeout(function () {
            cookieBar.style.display = "none"; // Hide the cookie bar
        }, 10000); // Hide after 5 seconds (5000 milliseconds)
    }, 2000); // Show after 2 seconds (2000 milliseconds)
    
})
</script>


<script>
  project_status = '<?=json_encode($tmpP)?>';
  project_status_values = '<?=json_encode($tmpPV)?>';
  task_status = '<?=json_encode($tmpT)?>';
  task_status_values = '<?=json_encode($tmpTV)?>';
</script>

<?php $this->load->view('includes/js'); ?>
<script src="<?=base_url('assets/js/page/home.js')?>"></script>

<script>
$(document).ready(function() {
    // Function to perform the AJAX request
      function performAjaxRequest() {
    $.ajax({
      url: '<?= base_url('attendance/get_count_abs') ?>', // Replace with your actual API URL
      type: 'GET', // Adjust the request method if needed
      success: function(response) {
        var data = JSON.parse(response);
        $("#presents").html(data.present);
        $("#absents").html(data.abs);
        $("#leaves").html(data.leave);
        $("#leave_pending").html(data.leave_pending);
        $("#leave_approved").html(data.leave_approved);
        $("#leave_rejected").html(data.leave_rejected);
        $("#bio_pending").html(data.bio_pending);
        $("#bio_approved").html(data.bio_approved);
        $("#bio_rejected").html(data.bio_rejected);
      },
      error: function(xhr, status, error) {
        // This function is executed if the request encounters an error
        console.log('An error occurred: ' + error);
      }
    });
  }
    // Trigger the AJAX request when the document is ready
    performAjaxRequest();

    // Trigger the AJAX request when the select element's value changes
    $('#filter').on('change', function() {
      performAjaxRequest();
    });
});
</script>
</body>
</html>
