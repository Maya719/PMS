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
            <?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?> 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item active"><a href="<?=base_url('projects')?>"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row" id="tool">

            
              <div class="form-group col-md-6">
                <select class="form-control select2" id="task_filters_project">
                  <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                  <?php foreach($projects as $project){ ?>
                  <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-3">
                <select class="form-control select2" id="task_filters_user">
                  <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                  <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              
              <div class="form-group col-md-3">
                <select class="form-control select2" id="task_filters_status">
                  <option value=""><?=$this->lang->line('select_status')?htmlspecialchars($this->lang->line('select_status')):'Select Status'?></option>
                  <?php foreach($task_status as $status){ ?>
                  <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-6">
                <select class="form-control select2" id="task_filters_upcoming">
                  <option value=""><?=$this->lang->line('select_days')?htmlspecialchars($this->lang->line('select_days')):'Select Days'?></option>

                  <option value="1"><?=$this->lang->line('today')?htmlspecialchars($this->lang->line('today')):'Today'?></option>

                  <option value="2"><?=$this->lang->line('tomorrow')?htmlspecialchars($this->lang->line('tomorrow')):'Tomorrow'?></option>

                  <option value="3"><?=$this->lang->line('coming_in_3_days')?htmlspecialchars($this->lang->line('coming_in_3_days')):'Coming in 3 days'?></option>

                  <option value="7"><?=$this->lang->line('coming_in_7_days')?htmlspecialchars($this->lang->line('coming_in_7_days')):'Coming in 7 days'?></option>

                  <option value="15"><?=$this->lang->line('coming_in_15_days')?htmlspecialchars($this->lang->line('coming_in_15_days')):'Coming in 15 days'?></option>

                  <option value="30"><?=$this->lang->line('coming_in_30_days')?htmlspecialchars($this->lang->line('coming_in_30_days')):'Coming in 30 days'?></option>

                  <option value="all"><?=$this->lang->line('all')?htmlspecialchars($this->lang->line('all')):'All'?> <?=$this->lang->line('upcoming')?htmlspecialchars($this->lang->line('upcoming')):'Upcoming'?></option>

                </select>
              </div>

              <div class="form-group col-md-6">
                <select class="form-control select2" id="task_filters_priority">
                  <option value=""><?=$this->lang->line('select_priority')?htmlspecialchars($this->lang->line('select_priority')):'Select Priority'?></option>

                  <?php foreach($task_priorities as $priority){ ?>
                    <option value="<?=htmlspecialchars($priority['id'])?>"><?=htmlspecialchars($priority['title'])?></option>
                  <?php } ?>

                </select>
              </div>

            </div>
            <div class="row">
            




            
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body"> 
                    <table class='table-striped' id='tasks_list'
                      data-toggle="table"
                      data-url="<?=base_url('projects/get_tasks_list')?>"
                      data-click-to-select="true"
                      data-side-pagination="server"
                      data-pagination="true"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="true" data-show-columns="true"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="id" data-sort-order="DESC"
                      data-mobile-responsive="true"
                      data-toolbar="" data-show-export="true"
                      data-maintain-selected="true"
                      data-export-options='{
                        "fileName": "tasks_list",
                      }'
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          
                          <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?></th>

                          <th data-field="project_id" data-sortable="true" data-visible="false"><?=$this->lang->line('project')?htmlspecialchars($this->lang->line('project')):'Project'?></th>

                          <th data-field="starting_date" data-sortable="true"><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?></th>

                          <th data-field="due_date" data-sortable="true"><?=$this->lang->line('due_date')?htmlspecialchars($this->lang->line('due_date')):'Due Date'?></th>
                          
                          <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>
                          
                          <th data-field="priority" data-sortable="true" data-visible="false"><?=$this->lang->line('priority')?htmlspecialchars($this->lang->line('priority')):'Priority'?></th>

                          <th data-field="stats" data-sortable="false" data-visible="false"><?=$this->lang->line('stats')?htmlspecialchars($this->lang->line('stats')):'Stats'?></th>


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

<?php $this->load->view('includes/js'); ?>
<script>
  var is_module_allowed_timesheet = '<?=is_module_allowed('timesheet')?>';
</script>
<script src="<?=base_url('assets/modules/dragula/dragula.min.js');?>"></script>
<script src="<?=base_url('assets/js/page/tasks.js');?>"></script>

<script>
  function queryParams(p){
    return {
      "project": $('#task_filters_project').val(),
      "status": $('#task_filters_status').val(),
      "user": $('#task_filters_user').val(),
      "priority": $('#task_filters_priority').val(),
      "upcoming": $('#task_filters_upcoming').val(),
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
  
  $('#tool').on('change',function(e){
    $('#tasks_list').bootstrapTable('refresh');
  });
</script>

</body>
</html>
