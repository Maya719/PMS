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
             <?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?> 
              <?php if(my_plan_features('projects')){  if ($this->ion_auth->is_admin() || permissions('project_create')){ ?>  
                <a href="#" id="modal-add-project" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } } ?>
              <div class="btn-group">
                <a href="<?=base_url('projects/list')?>" class="btn btn-sm"><?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?></a>
                <a href="#" class="btn btn-sm btn-primary"><?=$this->lang->line('grid_view')?htmlspecialchars($this->lang->line('grid_view')):'Grid View'?></a>
              </div>
             <!-- <div class="btn-group">
                <a href="<?=base_url('projects/grid')?>" class="btn btn-sm"><?=$this->lang->line('grid_view')?htmlspecialchars($this->lang->line('grid_view')):'Grid View'?></a>
                <a href="#" class="btn btn-sm btn-primary"><?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?></a>
              </div>-->
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>"><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                  <?php foreach($projects_all as $project_all){ ?>
                  <option value="<?=base_url("projects/detail/".htmlspecialchars($project_all['id']))?>"><?=htmlspecialchars($project_all['title'])?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>"><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?></option>
                  <?php foreach($project_status as $status){ ?>
                  <option value="<?=base_url("projects?status=".htmlspecialchars($status['id']))?>" <?=(isset($_GET['status']) && !empty($_GET['status']) && is_numeric($_GET['status']) && $_GET['status'] == $status['id'])?"selected":""?>><?=htmlspecialchars($status['title'])?></option>
                  <?php } ?>
                </select>
              </div>
              
              <?php if(!is_client()){ ?>
              <div class="form-group col-md-2">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>"><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                  <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=base_url("projects?user=".htmlspecialchars($system_user->id))?>" <?=(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user']) && $_GET['user'] == $system_user->id)?"selected":""?>><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              
              <div class="form-group col-md-2">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>"><?=$this->lang->line('select_Observer')?$this->lang->line('select_observer'):'Select Client'?></option>
                  <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=base_url("projects?client=".htmlspecialchars($system_client->id))?>" <?=(isset($_GET['client']) && !empty($_GET['client']) && is_numeric($_GET['client']) && $_GET['client'] == $system_client->id)?"selected":""?>><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              <?php } ?>

              <div class="form-group col-md-2">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>"><?=$this->lang->line('sort_by')?$this->lang->line('sort_by'):'Sort By'?></option>
                  <option value="<?=base_url("projects?sortby=latest")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'latest')?"selected":""?>><?=$this->lang->line('latest')?$this->lang->line('latest'):'Latest'?></option>
                  <option value="<?=base_url("projects?sortby=old")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'old')?"selected":""?>><?=$this->lang->line('old')?$this->lang->line('old'):'Old'?></option>
                  <option value="<?=base_url("projects?sortby=name")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'name')?"selected":""?>><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></option>
                </select>
              </div>
            </div>
            <div class="row">

              <?php
              if(isset($projects) && !empty($projects)){
              foreach($projects as $project){
              ?>
              <div class="col-md-6">
                <div class="card card-<?=htmlspecialchars($project['project_class'])?>">
                  <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
                      <li class="media">
                        <div class="media-body">
                          <div class="media-right">
                            <a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
                            <div class="dropdown-menu">

                            <a class="dropdown-item" href="<?=base_url("projects/detail/".htmlspecialchars($project['id']))?>"><?=$this->lang->line('details')?$this->lang->line('details'):'Details'?></a>

                            <?php if ($this->ion_auth->is_admin() || permissions('project_edit')){ ?>  
                              
                              <a href="#" data-edit="<?=htmlspecialchars($project['id'])?>" class="modal-edit-project dropdown-item"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></a>
                            <?php } ?>

                            <?php if ($this->ion_auth->is_admin() || permissions('task_view')){ ?>
                              
                              <a class="dropdown-item" href="<?=base_url("projects/tasks/".htmlspecialchars($project['id']))?>"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></a>
                            <?php } ?>

                            <?php if ($this->ion_auth->is_admin() || permissions('project_delete')){ ?>
                              
                              <a href="#" class="text-danger delete_project dropdown-item " data-id="<?=htmlspecialchars($project['id'])?>"><?=$this->lang->line('trash')?$this->lang->line('trash'):'Trash'?></a>
                            <?php } ?>
                            
                            </div>
                          </div>
                          <div class="media-title mb-1"><a href="<?=base_url('projects/detail/'.htmlspecialchars($project['id']))?>"><?=htmlspecialchars($project['title'])?></a></div>
                          <div class="author-box-job mb-1">
                          
                            <span class="mb-1 badge badge-<?=htmlspecialchars($project['project_class'])?>">
                            <i class="fa fa-tasks"></i> <?=htmlspecialchars($project['project_status'])?>
                            </span>

                            <?php if(!empty($project['project_client'])){ ?>
                              <span class="mb-1 badge badge-<?=htmlspecialchars($project['project_class'])?>">
                              <i class="fas fa-user"></i> <?=htmlspecialchars($project['project_client']->first_name)?> <?=htmlspecialchars($project['project_client']->last_name)?>
                              </span>
                            <?php } ?>

                            <span class="mb-1 badge badge-<?=htmlspecialchars($project['project_class'])?>">
                            <i class="fas fa-calendar-alt"></i> <?=htmlspecialchars($project['days_count'])?> <?=$this->lang->line('days')?$this->lang->line('days'):'Days'?> <?=htmlspecialchars($project['days_status'])?>
                            </span>
                            
                            <span class="mb-1 badge badge-<?=htmlspecialchars($project['project_class'])?>">
                            <i class="fas fa-layer-group"></i> <?=htmlspecialchars($project['completed_tasks'])?>/<?=htmlspecialchars($project['total_tasks'])?> <?=$this->lang->line('task_completed')?$this->lang->line('task_completed'):'Task Completed'?>
                            </span>
                          </div>
                          <div class="media-description text-muted"><?=mb_substr(htmlspecialchars($project['description']), 0, 100, "utf-8").'...'?></div>
                          
                            <?php if(!empty($project['project_users'])){ ?>
                              <div class="mt-3 mb-2">
                                
                            <div class="section-title mt-0 mb-1"><?=$this->lang->line('team_members')?htmlspecialchars($this->lang->line('team_members')):'Team Members'?></div>
                                <?php foreach($project['project_users'] as $project_user){ 
                                  if(!empty($project_user['profile'])){
                                    if(file_exists('assets/uploads/profiles/'.$project_user['profile'])){
                                      $file_upload_path = 'assets/uploads/profiles/'.$project_user['profile'];
                                    }else{
                                      $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$project_user['profile'];
                                    }
                                ?>
                                  <figure class="avatar avatar-sm">

                                    <img src="<?=base_url($file_upload_path)?>" alt="<?=htmlspecialchars($project_user['first_name'])?> <?=htmlspecialchars($project_user['last_name'])?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($project_user['first_name'])?> <?=htmlspecialchars($project_user['last_name'])?>">
                                  </figure>
                                <?php }else{ ?>
                                  <figure class="avatar avatar-sm bg-primary text-white" data-initial="<?=ucfirst(mb_substr(htmlspecialchars($project_user['first_name']), 0, 1, 'utf-8')).''.ucfirst(mb_substr(htmlspecialchars($project_user['last_name']), 0, 1, 'utf-8'))?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($project_user['first_name'])?> <?=htmlspecialchars($project_user['last_name'])?>">
                                  </figure>
                              <?php } } ?>
                              </div>
                            <?php } 
                            $progres_count = 0;
                            if($project['total_tasks'] > 0){
                              $progres_count = ($project['completed_tasks'] / $project['total_tasks']) * 100;
                            }
                            $progres_count = round($progres_count);
                            ?>
                            
                            <div class="media-links mt-3">
                            <div class="section-title mt-0 mb-1"><?=$this->lang->line('progress')?htmlspecialchars($this->lang->line('progress')):'Progress'?></div>
                            <div class="progress">
                              <div class="progress-bar" role="progressbar" data-width="<?=$progres_count?>%" aria-valuenow="<?=$progres_count?>" aria-valuemin="0" aria-valuemax="100"><?=$progres_count?> %</div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <?php } } ?>

            </div>
            <div class="row">
              <div class="col-md-12">
              <!-- Pagination links with HTML -->
              <?php echo $links; ?>
              
              </div>
            </div>    
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<form action="<?=base_url('projects/create-project')?>" method="POST" class="modal-part" id="modal-add-project-part" data-title="<?=$this->lang->line('create_new_project')?$this->lang->line('create_new_project'):'Create New Project'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="form-group">
    <label><?=$this->lang->line('project_title')?$this->lang->line('project_title'):'Project Title'?><span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required="">
  </div>
  <div class="form-group">
    <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
    <textarea type="text" name="description" class="form-control"></textarea>
  </div>
  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
      <input type="text" name="starting_date" class="form-control datepicker">
    </div>

    <div class="form-group col-md-6">
      <label for="ending_date"><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
      <div class="form-check form-check-inline">
        <input class="form-check-input"  type="checkbox" id="present" name="present">
        <label class="form-check-label text-danger" style="font-size: 12px  !important ;" for="present"><?=$this->lang->line('present')?$this->lang->line('present'):'Present'?></label>
      </div>
      <input type="text" name="ending_date" id="endingDateInput" class="form-control datepicker">
      <input type="text" name="present_input" id="present_input" class="form-control" style="display: none;" value="Present" readonly>
    </div>
  </span>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('budget')?$this->lang->line('budget'):'Budget'?> - <?=get_currency('currency_code')?></label>
      <input type="number" pattern="[0-9]" name="budget" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?><span class="text-danger">*</span></label>
      <select name="status" class="form-control select2">
        <?php foreach($project_status as $status){ ?>
        <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
        <?php } ?>
      </select>
    </div>
  </span>
  <div class="form-group">
    <label><?=$this->lang->line('project_users')?$this->lang->line('project_users'):'Project Users'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('add_users_who_will_work_on_this_project_only_this_users_are_able_to_see_this_project')?$this->lang->line('add_users_who_will_work_on_this_project_only_this_users_are_able_to_see_this_project'):"Add users who will work on this project. Only this users are able to see this project."?>"></i></label>
    <select name="users[]" class="form-control select2" multiple="">
      <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
  <div class="form-group">
    <label><?=$this->lang->line('project_observer')?$this->lang->line('project_observer'):'Project Observer'?></label>
    <select name="client" class="form-control select2">
      <option value=""><?=$this->lang->line('select_Observer')?$this->lang->line('select_Observer'):'Select Observer'?></option>
      <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
      <?php } } ?>
    </select>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="send_email_notification" name="send_email_notification">
    <label class="form-check-label text-danger" for="send_email_notification"><?=$this->lang->line('send_email_notification')?$this->lang->line('send_email_notification'):'Send email notification'?></label>
  </div>

</form>

<form action="<?=base_url('projects/edit-project')?>" method="POST"  class="modal-part" id="modal-edit-project-part" data-title="<?=$this->lang->line('edit_project')?$this->lang->line('edit_project'):'Edit Project'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id">
  <div class="form-group">
    <label><?=$this->lang->line('project_title')?$this->lang->line('project_title'):'Project Title'?><span class="text-danger">*</span></label>
    <input type="text" name="title" id="title" class="form-control" required="">
  </div>
  <div class="form-group">
    <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
    <textarea type="text" name="description" id="description" class="form-control"></textarea>
  </div>
  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
      <input type="text" name="starting_date" id="starting_date" class="form-control datepicker">
    </div>
    <div class="form-group col-md-6">
      <label for="ending_date"><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
      <div class="form-check form-check-inline">
        <input class="form-check-input"  type="checkbox" id="present_edit" name="present">
        <label class="form-check-label text-danger" style="font-size: 12px  !important ;" for="present_edit"><?=$this->lang->line('present')?$this->lang->line('present'):'Present'?></label>
      </div>
      <input type="text" name="ending_date" id="ending_date" class="form-control datepicker">
      <input type="text" name="present_edit_input" id="present_edit_input" class="form-control" style="display: none;" value="Present" readonly>
    </div>
  </span>
  
  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('budget')?$this->lang->line('budget'):'Budget'?> - <?=get_currency('currency_code')?></label>
      <input type="number" pattern="[0-9]" name="budget" id="budget" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?><span class="text-danger">*</span></label>
      <select name="status" id="status" class="form-control select2">
        <?php foreach($project_status as $status){ ?>
        <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
        <?php } ?>
      </select>
    </div>
  </span>

  <div class="form-group">
    <label><?=$this->lang->line('project_users')?$this->lang->line('project_users'):'Project Users'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('add_users_who_will_work_on_this_project_only_this_users_are_able_to_see_this_project')?$this->lang->line('add_users_who_will_work_on_this_project_only_this_users_are_able_to_see_this_project'):"Add users who will work on this project. Only this users are able to see this project."?>"></i></label>
    <select name="users[]" id="users" class="form-control select2" multiple="">
      <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
  <div class="form-group">
    <label><?=$this->lang->line('project_observer')?$this->lang->line('project_observer'):'Project Observer'?></label>
    <select name="client" id="client" class="form-control select2">
      <option value=""><?=$this->lang->line('select_observer')?$this->lang->line('select_observer'):'Select Observer'?></option>
      <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
</form>

<div id="modal-edit-project"></div>
<?php $this->load->view('includes/js'); ?>
<script>
  const presentCheckbox = document.getElementById('present');
  const endingDateInput = document.getElementById('endingDateInput');

  present.addEventListener('change', function () {
    if (presentCheckbox.checked) {
      endingDateInput.value = 'Present';
      $('#endingDateInput').hide();
      $('#present_input').show();
    }else {
      const currentDate = new Date();
      const day = String(currentDate.getDate()).padStart(2, '0');
      const month = currentDate.toLocaleString('en-US', { month: 'short' });
      const year = currentDate.getFullYear();
      const formattedDate = `${day} ${month} ${year}`;
      endingDateInput.value = formattedDate;
      $('#endingDateInput').show();
      $('#present_input').hide();
    }
  });

  var endingDateValue = '';
  $(document).ready(function () {
    $('#modal-edit-project').click(function () {
      endingDateValue = $('#ending_date').val();
    });
  });

	const presentCheck = document.getElementById('present_edit');
	const endingDate = document.getElementById('ending_date');

	present_edit.addEventListener('change', function () {
		if (presentCheck.checked) {
			endingDate.value = 'Present';
      $('#ending_date').hide();
      $('#present_edit_input').show();
		} else {
			endingDate.value = endingDateValue;
      $('#present_edit_input').hide();
      $('#ending_date').show();
		}
	});
</script>
</body>
</html>
