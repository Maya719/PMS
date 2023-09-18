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
            <?=$this->lang->line('timesheet')?$this->lang->line('timesheet'):'Timesheet'?> 
              <?php if (!$this->ion_auth->in_group(4)){ ?>
                <a href="#" id="modal-add-timesheet" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('timesheet')?$this->lang->line('timesheet'):'Timesheet'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="form-group col-md-<?=$this->ion_auth->is_admin()?'4':'6'?>">
                <select class="form-control select2 timesheet_filter" id="timesheet_filter_project">
                  <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                  <?php foreach($projects as $project){ ?>
                  <option value="<?=$project['id']?>" <?=($this->uri->segment(3) == $project['id']?'selected':'')?>><?=htmlspecialchars($project['title'])?></option>
                  <?php } ?>
                  

                </select>
              </div>
              <div class="form-group col-md-<?=$this->ion_auth->is_admin()?'4':'6'?>">
                <select class="form-control select2 timesheet_filter" id="timesheet_filter_task">
                  <option value=""><?=$this->lang->line('select_task')?$this->lang->line('select_task'):'Select Task'?></option>
                </select>
              </div>

              <?php if($this->ion_auth->is_admin()){ ?>
                <div class="form-group col-md-4">
                  <select class="form-control select2 timesheet_filter" id="timesheet_filter_user">
                    <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>
              <?php } ?>
            </div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='timesheet_list'
                          data-toggle="table"
                          data-url="<?=base_url('projects/get_timesheet')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="true"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="true" data-show-columns="true"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="desc"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="false"
                          data-maintain-selected="true"
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <?php if($this->ion_auth->is_admin()){ ?>
                                <th data-field="user" data-sortable="false"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></th>
                                <th data-field="project_title" data-sortable="false"><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?></th>
                              <?php } ?>
                              <th data-field="task_title" data-sortable="false"><?=$this->lang->line('task')?$this->lang->line('task'):'Task'?></th>
                              <th data-field="project_title" data-sortable="false" data-visible="false"><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?></th>
                              <th data-field="starting_time" data-sortable="true"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></th>
                              <th data-field="ending_time" data-sortable="true"><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?></th>
                              <th data-field="total_time" data-sortable="false"><?=$this->lang->line('total_time')?$this->lang->line('total_time'):'Total Time'?></th>
                              <th data-field="note" data-sortable="false" data-visible="false"><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></th>
                              <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
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


<form action="<?=base_url('projects/create-timesheet')?>" method="POST" class="modal-part" id="modal-add-timesheet-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></label>
      <select name="user_id" class="form-control select2 user_id">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){  ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  <?php } ?>

  <div class="form-group">
    <label><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?><span class="text-danger">*</span></label>
    <select name="project_id" class="form-control select2 project_id">
      <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
      <?php foreach($projects as $project){ ?>
      <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
      <?php } ?>
    </select>
  </div>
  
  <div class="form-group">
    <label><?=$this->lang->line('task')?$this->lang->line('task'):'Task'?><span class="text-danger">*</span></label>
    <select name="task_id" class="form-control select2 task_id">
    </select>
  </div>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></label>
      <input type="text" name="starting_time" class="form-control datetimepicker">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?></label>
      <input type="text" name="ending_time" class="form-control datetimepicker">
    </div>
  </span>

  <div class="form-group">
    <label><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></label>
    <textarea type="text" name="note" class="form-control"></textarea>
  </div>

</form>

<form action="<?=base_url('projects/edit-timesheet')?>" method="POST" class="modal-part" id="modal-edit-timesheet-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">


  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></label>
      <select name="user_id" id="user_id" class="form-control select2 user_id">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  <?php } ?>

  <div class="form-group">
    <label><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?><span class="text-danger">*</span></label>
    <select name="project_id" id="project_id" class="form-control select2">
      <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
      <?php foreach($projects as $project){ ?>
      <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
      <?php } ?>
    </select>
  </div>
  
  <div class="form-group">
    <label><?=$this->lang->line('task')?$this->lang->line('task'):'Task'?><span class="text-danger">*</span></label>
    <select name="task_id" id="task_id" class="form-control select2">
    </select>
  </div>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></label>
      <input type="text" name="starting_time" id="starting_time" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?></label>
      <input type="text" name="ending_time" id="ending_time" class="form-control">
    </div>
  </span>

  <div class="form-group">
    <label><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></label>
    <textarea type="text" name="note" id="note" class="form-control"></textarea>
  </div>

</form>

<div id="modal-edit-timesheet"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
      return {
        "project_id": $('#timesheet_filter_project').val(),
        "task_id": $('#timesheet_filter_task').val(),
        "user_id": $('#timesheet_filter_user').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }

  $(document).on('change','.timesheet_filter',function(){
    $('#timesheet_list').bootstrapTable('refresh');
  });
  
  $(document).on('change','#timesheet_filter_project',function(){
    $.ajax({
      type: "POST",
      url: base_url+'projects/get_tasks_by_project_id', 
      data: "project_id="+$(this).val(),
      dataType: "json",
      success: function(data) 
      {	
        var tasks = '<option value=""><?=$this->lang->line('select_task')?$this->lang->line('select_task'):'Select Task'?></option>';
        if(data['data']){
          $.each(data['data'], function (key, val) {
            tasks +=' <option value="'+val.id+'">'+val.title+'</option>';
          });
        }
        $("#timesheet_filter_task").html(tasks);
      }        
    });
  });

  
  $(document).on('change','.project_id',function(){
    $.ajax({
      type: "POST",
      url: base_url+'projects/get_tasks_by_project_id', 
      data: "project_id="+$(this).val(),
      dataType: "json",
      success: function(data) 
      {	
        var tasks = '';
        if(data['data']){
          $.each(data['data'], function (key, val) {
            tasks +=' <option value="'+val.id+'">'+val.title+'</option>';
          });
        }
        $(".task_id").html(tasks);
      }        
    });
  });

    $(document).on('change','#project_id',function(){
    $.ajax({
      type: "POST",
      url: base_url+'projects/get_tasks_by_project_id', 
      data: "project_id="+$(this).val(),
      dataType: "json",
      success: function(data) 
      {	
        var tasks = '';
        if(data['data']){
          $.each(data['data'], function (key, val) {
            tasks +=' <option value="'+val.id+'">'+val.title+'</option>';
          });
        }
        $("#task_id").html(tasks);
      }        
    });
  });

</script>
</body>
</html>
