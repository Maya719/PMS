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
              <?php
              if(my_plan_features('tasks')){ if ($this->ion_auth->is_admin() || permissions('task_create')){ ?>
                <a href="#" id="modal-add-task" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } } ?>
              <div class="btn-group">
                <a href="#" class="btn btn-sm btn-primary"><?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?></a>
                <a href="<?=base_url('projects/tasks')?>" class="btn btn-sm"><?=$this->lang->line('kanban_view')?htmlspecialchars($this->lang->line('kanban_view')):'Kanban View'?></a>
              </div>
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

              <?php if($this->ion_auth->is_admin()){ ?>
                <div class="form-group col-md-3">
                  <select class="form-control select2" id="task_filters_user">
                    <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>
              <?php } ?>
              
              <div class="form-group col-md-<?=$this->ion_auth->is_admin()?'3':'6'?>">
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
                      data-toolbar="" data-show-export="false"
                      data-maintain-selected="true"
                      data-export-options='{
                        "fileName": "tasks_list",
                      }'
                      data-query-params="queryParams1">
                      <thead>
                        <tr>
                          
                          <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?></th>
                          
                          <th data-field="project_id" data-sortable="true"><?=$this->lang->line('project')?htmlspecialchars($this->lang->line('project')):'project'?></th>

                          <th data-field="task_users" data-sortable="false"><?=$this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member'?></th>

                          <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>
                          
                          <th data-field="priority" data-sortable="true" data-visible="false"><?=$this->lang->line('priority')?htmlspecialchars($this->lang->line('priority')):'Priority'?></th>

                          <th data-field="stats" data-sortable="false"><?=$this->lang->line('stats')?htmlspecialchars($this->lang->line('stats')):'Stats'?></th>

                          <th data-field="starting_date" data-sortable="true" data-visible="false"><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?></th>

                          <th data-field="due_date" data-sortable="true" data-visible="false"><?=$this->lang->line('due_date')?htmlspecialchars($this->lang->line('due_date')):'Due Date'?></th>
                          
                          <th data-field="starting_time" data-sortable="true" data-visible="false"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></th>

                          <th data-field="ending_time" data-sortable="true" data-visible="false"><?=$this->lang->line('ending_time')?htmlspecialchars($this->lang->line('ending_time')):'Ending Time'?></th>

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

<form action="<?=base_url('projects/create-comment')?>" method="POST" class="modal-part" id="modal-task-detail-part" data-title="<?=$this->lang->line('task_detail')?$this->lang->line('task_detail'):'Task Detail'?>">
  <div class="row">
    <div class="col-md-12">
      <div class="card author-box mb-0">
        <div class="card-body p-0">

          <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
            <li class="media mb-0">
              <div class="media-body">
                <div class="media-right"><div class="" id="task_priority"></div></div>
                <div class="media-title mb-0"><h5 id="task_title"></h5></div>
                <div class="author-box-job mb-2">
                  <a target="_blank" href="#" id="task_project"></a>
                </div>
                <div class="media-description description-wrapper" id="task_description"></div>
               
              </div>
            </li>
          </ul>
          <div class="profile-widget mt-0">
            <div class="profile-widget-header">
              <div class="profile-widget-items">
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></div>
                  <div class="profile-widget-item-value mt-1" id="task_users">
                    <figure class="avatar avatar-sm bg-primary text-white" data-initial="UM" data-toggle="tooltip" data-placement="top" title="Mithun Parmar"></figure>
                    <figure class="avatar avatar-sm bg-primary text-white" data-initial="UM" data-toggle="tooltip" data-placement="top" title="Mithun Parmar"></figure>
                  </div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></div>
                  <div class="profile-widget-item-value" id="task_days_count"></div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?></div>
                  <div class="profile-widget-item-value" id="task_starting_date"></div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?></div>
                  <div class="profile-widget-item-value" id="task_due_date"></div>
                </div>
               <!-- <div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></div>
                  <div class="profile-widget-item-value" id="task_starting_time"></div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?></div>
                  <div class="profile-widget-item-value" id="task_ending_time"></div>
                </div>-->
                
              </div>
            </div>
          </div>
                                
          <ul class="nav nav-tabs mt-2" id="myTab2" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="true"><?=$this->lang->line('comments')?$this->lang->line('comments'):'Comments'?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="attachments-tab" data-toggle="tab" href="#attachments" role="tab" aria-controls="attachments" aria-selected="false"><?=$this->lang->line('attachments')?$this->lang->line('attachments'):'Attachments'?></a>
            </li>
            <?php if(!is_client() && is_module_allowed('timesheet')){ ?>
            <li class="nav-item">
              <a class="nav-link" id="timesheet-tab" data-toggle="tab" href="#timesheet" role="tab" aria-controls="timesheet" aria-selected="false"><?=$this->lang->line('timesheet')?$this->lang->line('timesheet'):'Timesheet'?></a>
            </li>
            <?php } ?>
            <li class="nav-item">
              <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab" aria-controls="timeline" aria-selected="false"><?=$this->lang->line('timeline')?$this->lang->line('timeline'):'Timeline'?></a>
            </li>
          </ul>
          <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                <div class="p-0 d-flex">
                    <input type="hidden" name="comment_task_id" id="comment_task_id" value="">
                    <input type="hidden" name="is_comment" id="is_comment" value="true">
                    <input type="text" name="message" id="message" class="form-control" placeholder="<?=$this->lang->line('type_your_message')?$this->lang->line('type_your_message'):'Type your message'?>">
                    <button type="submit" class="btn btn-primary savebtn">
                      <i class="far fa-paper-plane"></i>
                    </button>
                </div>
                <div id="comments_append">
                </div>
            </div>
            <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                <div class="p-0 d-flex">
                    <input type="hidden" name="is_attachment" id="is_attachment" value="false">
                    <input type="file" name="attachment" id="attachment" class="form-control">
                    <button type="submit" class="btn btn-primary savebtn">
                      <i class="far fa-paper-plane"></i>
                    </button>
                </div>
                <table class='table-striped' id='file_list'
                  data-toggle="table"
                  data-url="<?=base_url('projects/get_tasks_files/')?>"
                  data-click-to-select="true"
                  data-side-pagination="server"
                  data-pagination="false"
                  data-page-list="[5, 10, 20, 50, 100, 200]"
                  data-search="false" data-show-columns="false"
                  data-show-refresh="false" data-trim-on-search="false"
                  data-sort-name="id" data-sort-order="desc"
                  data-mobile-responsive="true"
                  data-toolbar="" data-show-export="false"
                  data-maintain-selected="true"
                  data-export-types='["txt","excel"]'
                  data-export-options='{
                    "fileName": "users-list",
                    "ignoreColumn": ["state"] 
                  }'
                  data-query-params="queryParams1">
                  <thead>
                    <tr>
                      <th data-field="file_name" data-sortable="true"><?=$this->lang->line('file')?$this->lang->line('file'):'File'?></th>
                      <th data-field="file_type" data-sortable="true"><?=$this->lang->line('file_type')?$this->lang->line('file_type'):'File Type'?></th>
                      <th data-field="file_size" data-sortable="true"><?=$this->lang->line('size')?$this->lang->line('size'):'Size'?></th>
                      <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
                      
                    </tr>
                  </thead>
                </table>
                
                <?php
                  if(!is_storage_limit_exceeded()){ ?>
                    <div class="alert alert-danger mt-2">
                      <?=$this->lang->line('storage_limit_exceeded')?$this->lang->line('storage_limit_exceeded'):'Storage Limit Exceeded'?>
                    </div>
                <?php } ?>
            </div>

            
            <?php if(!is_client() && is_module_allowed('timesheet')){ ?>
            <div class="tab-pane fade" id="timesheet" role="tabpanel" aria-labelledby="timesheet-tab">
              <table class='table-striped' id='timesheet_list'
                data-toggle="table"
                data-url="<?=base_url('projects/get_timesheet')?>"
                data-click-to-select="true"
                data-side-pagination="server"
                data-pagination="true"
                data-page-list="[5, 10, 20, 50, 100, 200]"
                data-search="false" data-show-columns="false"
                data-show-refresh="false" data-trim-on-search="false"
                data-sort-name="id" data-sort-order="desc"
                data-mobile-responsive="true"
                data-toolbar="" data-show-export="false"
                data-maintain-selected="true"
                data-export-types='["txt","excel"]'
                data-export-options='{
                  "fileName": "timesheet-list",
                  "ignoreColumn": ["state"] 
                }'
                data-query-params="queryParams">
                <thead>
                  <tr>
                    <?php if($this->ion_auth->is_admin()){ ?>
                      <th data-field="user" data-sortable="false"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></th>
                    <?php } ?>
                    <th data-field="starting_time" data-sortable="true"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></th>
                    <th data-field="ending_time" data-sortable="true"><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?></th>
                    <th data-field="total_time" data-sortable="false"><?=$this->lang->line('total_time')?$this->lang->line('total_time'):'Total Time'?></th>
                  </tr>
                </thead>
              </table>
            </div>
            <?php } ?>
            <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
              <table class='table-striped' id='timeline_list'
                data-toggle="table"
                data-url="<?=base_url('projects/get_timeline')?>"
                data-click-to-select="true"
                data-side-pagination="server"
                data-pagination="true"
                data-page-list="[5, 10, 20, 50, 100, 200]"
                data-search="false" data-show-columns="false"
                data-show-refresh="false" data-trim-on-search="false"
                data-sort-name="id" data-sort-order="desc"
                data-mobile-responsive="true"
                data-toolbar="" data-show-export="false"
                data-maintain-selected="true"
                data-export-types='["txt","excel"]'
                data-export-options='{
                  "fileName": "timeline-list",
                  "ignoreColumn": ["state"] 
                }'
                data-query-params="queryParams">
                <thead>
                  <tr>
                    <!-- <th data-field="project_title" data-sortable="true"><?=$this->lang->line('project_title')?$this->lang->line('project_title'):'Project Title'?></th> -->
                    <th data-field="s_no" data-sortable="false"><?=$this->lang->line('#')?$this->lang->line('#'):'#'?></th>
                    <th data-field="status" data-sortable="false"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
                    <th data-field="date_time" data-sortable="true"><?=$this->lang->line('date_time')?$this->lang->line('date_time'):'Date / Time'?></th>
                  </tr>
                </thead>
              </table>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<form action="<?=base_url('projects/create-task')?>" method="POST" class="modal-part" id="modal-add-task-part" data-title="<?=$this->lang->line('create_new_task')?$this->lang->line('create_new_task'):'Create New Task'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

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
    <label><?=$this->lang->line('task_title')?$this->lang->line('task_title'):'Task Title'?><span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required>
  </div>
  <div class="form-group">
    <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
    <textarea type="text" name="description" class="form-control"></textarea>
  </div>
  <div class="form-group">
    
  </div>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
      <input type="text" name="starting_date" class="form-control datepicker">
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?><span class="text-danger">*</span></label>
      <input type="text" name="due_date"  class="form-control datepicker" required>
    </div>
  </span>
  
<!--<span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
      <input type="text" name="starting_time" id="starting_time" class="form-control timepicker">
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
      <input type="text" name="ending_time" id="engind_time"  class="form-control timepicker" required>
    </div>
  </span>-->
  
  <div class="form-group">
    <label><?=$this->lang->line('priority')?$this->lang->line('priority'):'Priority'?><span class="text-danger">*</span></label>
    <select name="priority" class="form-control select2" required>
      <?php foreach($task_priorities as $priorities){ ?>
      <option value="<?=htmlspecialchars($priorities['id'])?>"><?=htmlspecialchars($priorities['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?><span class="text-danger">*</span></label>
    <select name="status" class="form-control select2" required>
      <?php foreach($task_status as $status){ ?>
      <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('assign_users')?$this->lang->line('assign_users'):'Assign Users'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('assign_task_to_the_users_who_will_work_on_this_task_only_this_users_are_able_to_se_this_task')?$this->lang->line('assign_task_to_the_users_who_will_work_on_this_task_only_this_users_are_able_to_se_this_task'):"Assign task to the users who will work on this task. Only this users are able to see this task."?>"></i></label>
    <select name="users[]" id="users_append" class="form-control select2" multiple="">
      <?php if(!empty($project_id)){ foreach($projecr_users as $projecr_user){ ?>
      <option value="<?=htmlspecialchars($projecr_user['id'])?>"><?=htmlspecialchars($projecr_user['first_name'])?> <?=htmlspecialchars($projecr_user['last_name'])?></option>
      <?php } } ?>
    </select>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="send_email_notification" name="send_email_notification">
    <label class="form-check-label text-danger" for="send_email_notification"><?=$this->lang->line('send_email_notification')?$this->lang->line('send_email_notification'):'Send email notification'?></label>
  </div>

</form>

<form action="<?=base_url('projects/edit-task')?>" method="POST" class="modal-part" id="modal-edit-task-part" data-title="<?=$this->lang->line('edit_task')?$this->lang->line('edit_task'):'Edit Task'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">
  <input type="hidden" name="status_duplicate" id="status_duplicate" value="">
  <div class="form-group">
    <label><?=$this->lang->line('task_title')?$this->lang->line('task_title'):'Task Title'?><span class="text-danger">*</span></label>
    <input type="text" name="title" id="title" class="form-control" required>
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
      <label><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?><span class="text-danger">*</span></label>
      <input type="text" name="due_date" id="due_date"  class="form-control datepicker" required>
    </div>
  </span>
  
 <!-- <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
      <input type="text" name="starting_time" id="starting_time" class="form-control timepicker"required>
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
      <input type="text" name="ending_time" id="engind_time"  class="form-control timepicker" required>
    </div>
  </span>-->

  <div class="form-group">
    <label><?=$this->lang->line('priority')?$this->lang->line('priority'):'Priority'?><span class="text-danger">*</span></label>
    <select name="priority" id="priority" class="form-control select2" required>
      <?php foreach($task_priorities as $priorities){ ?>
      <option value="<?=htmlspecialchars($priorities['id'])?>"><?=htmlspecialchars($priorities['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?><span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control select2" required>
      <?php foreach($task_status as $status){ ?>
      <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
      <?php } ?>
    </select>
  </div>
 
  <div class="form-group">
    <label><?=$this->lang->line('assign_users')?$this->lang->line('assign_users'):'Assign Users'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('assign_task_to_the_users_who_will_work_on_this_task_only_this_users_are_able_to_se_this_task')?$this->lang->line('assign_task_to_the_users_who_will_work_on_this_task_only_this_users_are_able_to_se_this_task'):"Assign task to the users who will work on this task. Only this users are able to see this task."?>"></i></label>
    <select name="users[]" id="users" class="form-control select2" multiple="">
      <?php foreach($projecr_users as $projecr_user){ ?>
      <option value="<?=htmlspecialchars($projecr_user['id'])?>"><?=htmlspecialchars($projecr_user['first_name'])?> <?=htmlspecialchars($projecr_user['last_name'])?></option>
      <?php } ?>
    </select>
  </div>


</form>

<div id="modal-edit-task"></div>
<div id="modal-task-detail"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  var is_module_allowed_timesheet = '<?=is_module_allowed('timesheet')?>';
</script>
<script src="<?=base_url('assets/modules/dragula/dragula.min.js');?>"></script>
<script src="<?=base_url('assets/js/page/tasks.js');?>"></script>

<script>
  function queryParams1(p){
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
