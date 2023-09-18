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
                <a href="#" class="btn btn-sm btn-primary"><?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?></a>
                <a href="<?=base_url('projects')?>" class="btn btn-sm"><?=$this->lang->line('grid_view')?htmlspecialchars($this->lang->line('grid_view')):'Grid View'?></a>
              </div>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></div>
            </div>
          </div>
          <div class="section-body">
            <div id="tool" class="row">
              
              <?php if(!$this->ion_auth->in_group(4)){ ?>
                <div class="form-group col-md-3">
                  <select class="form-control select2 project_filter">
                    <option value="<?=base_url("projects")?>"><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                    <?php foreach($projects_all as $project_all){ ?>
                    <option value="<?=base_url("projects/detail/".htmlspecialchars($project_all['id']))?>"><?=htmlspecialchars($project_all['title'])?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-3">
                <select class="form-control select2" id="project_filters_user">
                  <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                  <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              
              <div class="form-group col-md-3">
                <select class="form-control select2" id="project_filters_client">
                  <option value=""><?=$this->lang->line('select_clients')?$this->lang->line('select_clients'):'Select Clients'?></option>
                  <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>

              
              <div class="form-group col-md-3">
                <select class="form-control select2" id="project_filters_status">
                  <option value=""><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?></option>
                  <?php foreach($project_status as $status){ ?>
                  <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
                  <?php } ?>
                </select>
              </div>

              <?php } ?>
            </div>


            <div class="row">





  
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body"> 
                    <table class='table-striped' id='projects_list'
                      data-toggle="table"
                      data-url="<?=base_url('projects/get_projects_list')?>"
                      data-click-to-select="true"
                      data-side-pagination="server"
                      data-pagination="true"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="true" data-show-columns="true"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="id" data-sort-order="DESC"
                      data-mobile-responsive="true"
                      data-toolbar="" data-show-export="false"
                      data-maintain-selected="true"
                      data-export-options='{
                        "fileName": "projects_list",
                      }'
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          
                          <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?></th>

                          <th data-field="project_client" data-sortable="false"><?=$this->lang->line('project_client')?htmlspecialchars($this->lang->line('project_client')):'Project Client'?></th>

                          <th data-field="project_users" data-sortable="false"><?=$this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member'?></th>

                          <th data-field="stats" data-sortable="false"><?=$this->lang->line('stats')?htmlspecialchars($this->lang->line('stats')):'Stats'?></th>

                          <th data-field="starting_date" data-sortable="true" data-visible="false"><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?></th>

                          <th data-field="ending_date" data-sortable="true" data-visible="false"><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?></th>

                          <th data-field="project_status" data-sortable="true"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>

                          <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?htmlspecialchars($this->lang->line('action')):'Action'?></th>

                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
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
    <label><?=$this->lang->line('project_client')?$this->lang->line('project_client'):'Project Client'?></label>
    <select name="client" class="form-control select2">
      <option value=""><?=$this->lang->line('select_clients')?$this->lang->line('select_clients'):'Select Clients'?></option>
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
    <label><?=$this->lang->line('project_client')?$this->lang->line('project_client'):'Project Client'?></label>
    <select name="client" id="client" class="form-control select2">
      <option value=""><?=$this->lang->line('select_clients')?$this->lang->line('select_clients'):'Select Clients'?></option>
      <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
</form>

<div id="modal-edit-project"></div>
<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
    return {
      "status": $('#project_filters_status').val(),
      "user": $('#project_filters_user').val(),
      "client": $('#project_filters_client').val(),
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
  
  $('#tool').on('change',function(e){
    $('#projects_list').bootstrapTable('refresh');
  });
  
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
      console.log(endingDateValue);
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
