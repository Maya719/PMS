<?php $this->load->view('includes/head'); ?>
<link rel="stylesheet" href="<?=base_url('assets/modules/fullcalendar/main.css');?>" >

<style>
   #calendar {
    width: 100%!important; /* Set the container's width to 100% of its parent */
 
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
            <div class="section-header-back">
              <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
            <?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?> 
              <?php
              $isAdmin = $this->ion_auth->is_admin();
              if(my_plan_features('tasks')){ if ($this->ion_auth->is_admin() || permissions('task_create')){ ?>
                <a href="#" id="modal-add-task" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } } ?>
              
              <div class="btn-group">
                <a href="<?=base_url('projects/tasks-list')?>" class="btn btn-sm"><?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?></a>
                <a href="#" class="btn btn-sm btn-primary"><?=$this->lang->line('kanban_view')?htmlspecialchars($this->lang->line('kanban_view')):'Kanban View'?></a>
              </div>

            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item active"><a href="<?=base_url('projects')?>"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="form-group col-md-6">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/tasks")?>"><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                  <?php foreach($projects as $project){ ?>
                  <option value="<?=base_url("projects/tasks/".htmlspecialchars($project['id']))?>" <?=(!empty($this->uri->segment(3)) && is_numeric($this->uri->segment(3)) && $this->uri->segment(3) == $project['id'])?"selected":""?>><?=htmlspecialchars($project['title'])?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-6">
                <div class="input-group">
                  <input type="text" class="form-control" id="task_search_value">
                  <div class="input-group-append">
                    <button class="btn btn-primary task_search" type="button"><?=$this->lang->line('search')?htmlspecialchars($this->lang->line('search')):'Search'?></button>
                  </div>
                </div>
              </div>

              <?php if($this->ion_auth->is_admin()){ ?>
                <div class="form-group col-md-3">
                  <select class="form-control select2 project_filter">
                    <option value="<?=base_url("projects/tasks")?>"><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=base_url("projects/tasks?user=".htmlspecialchars($system_user->id))?>" <?=(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user']) && $_GET['user'] == $system_user->id)?"selected":""?>><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>
              <?php } ?>

              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/tasks")?>"><?=$this->lang->line('select_days')?htmlspecialchars($this->lang->line('select_days')):'Select Days'?></option>

                  <option value="<?=base_url("projects/tasks?upcoming=1")?>" <?=(isset($_GET['upcoming']) && !empty($_GET['upcoming']) && is_numeric($_GET['upcoming']) && $_GET['upcoming'] == 1)?"selected":""?>><?=$this->lang->line('today')?htmlspecialchars($this->lang->line('today')):'Today'?></option>

                  <option value="<?=base_url("projects/tasks?upcoming=2")?>" <?=(isset($_GET['upcoming']) && !empty($_GET['upcoming']) && is_numeric($_GET['upcoming']) && $_GET['upcoming'] == 2)?"selected":""?>><?=$this->lang->line('tomorrow')?htmlspecialchars($this->lang->line('tomorrow')):'Tomorrow'?></option>

                  <option value="<?=base_url("projects/tasks?upcoming=3")?>" <?=(isset($_GET['upcoming']) && !empty($_GET['upcoming']) && is_numeric($_GET['upcoming']) && $_GET['upcoming'] == 3)?"selected":""?>><?=$this->lang->line('coming_in_3_days')?htmlspecialchars($this->lang->line('coming_in_3_days')):'Coming in 3 days'?></option>

                  <option value="<?=base_url("projects/tasks?upcoming=7")?>" <?=(isset($_GET['upcoming']) && !empty($_GET['upcoming']) && is_numeric($_GET['upcoming']) && $_GET['upcoming'] == 7)?"selected":""?>><?=$this->lang->line('coming_in_7_days')?htmlspecialchars($this->lang->line('coming_in_7_days')):'Coming in 7 days'?></option>

                  <option value="<?=base_url("projects/tasks?upcoming=15")?>" <?=(isset($_GET['upcoming']) && !empty($_GET['upcoming']) && is_numeric($_GET['upcoming']) && $_GET['upcoming'] == 15)?"selected":""?>><?=$this->lang->line('coming_in_15_days')?htmlspecialchars($this->lang->line('coming_in_15_days')):'Coming in 15 days'?></option>

                  <option value="<?=base_url("projects/tasks?upcoming=30")?>" <?=(isset($_GET['upcoming']) && !empty($_GET['upcoming']) && is_numeric($_GET['upcoming']) && $_GET['upcoming'] == 30)?"selected":""?>><?=$this->lang->line('coming_in_30_days')?htmlspecialchars($this->lang->line('coming_in_30_days')):'Coming in 30 days'?></option>

                  <option value="<?=base_url("projects/tasks?upcoming=all")?>" <?=(isset($_GET['upcoming']) && !empty($_GET['upcoming']) && is_numeric($_GET['upcoming']) && $_GET['upcoming'] == 'all')?"selected":""?>><?=$this->lang->line('all')?htmlspecialchars($this->lang->line('all')):'All'?> <?=$this->lang->line('upcoming')?htmlspecialchars($this->lang->line('upcoming')):'Upcoming'?></option>

                </select>
              </div>

              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/tasks")?>"><?=$this->lang->line('select_priority')?htmlspecialchars($this->lang->line('select_priority')):'Select Priority'?></option>

                  <?php foreach($task_priorities as $priority){ ?>
                    <option value="<?=base_url("projects/tasks?priority=".htmlspecialchars($priority['id']))?>" <?=(isset($_GET['priority']) && !empty($_GET['priority']) && is_numeric($_GET['priority']) && $_GET['priority'] == $priority['id'])?"selected":""?>><?=htmlspecialchars($priority['title'])?></option>
                  <?php } ?>

                </select>
              </div>

              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/tasks")?>"><?=$this->lang->line('sort_by')?$this->lang->line('sort_by'):'Sort By'?></option>
                  <option value="<?=base_url("projects/tasks?sortby=latest")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'latest')?"selected":""?>><?=$this->lang->line('latest')?$this->lang->line('latest'):'Latest'?></option>
                  <option value="<?=base_url("projects/tasks?sortby=old")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'old')?"selected":""?>><?=$this->lang->line('old')?$this->lang->line('old'):'Old'?></option>
                  <option value="<?=base_url("projects/tasks?sortby=name")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'name')?"selected":""?>><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></option>

                  <option value="<?=base_url("projects/tasks?sortby=due")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'due')?"selected":""?>><?=$this->lang->line('due_date')?htmlspecialchars($this->lang->line('due_date')):'Due Date'?></option>

                  <option value="<?=base_url("projects/tasks?sortby=start")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'start')?"selected":""?>><?=$this->lang->line('starting_date')?htmlspecialchars($this->lang->line('starting_date')):'Starting Date'?></option>

                </select>
              </div>

            </div>
            <div class="row">
            
            <?php if(isset($task_status) && count($task_status)>0){ 
              foreach($task_status as $key => $status){ 
                $temp[$key] = $status['id'];
              } ?>
              <div class="col-md-12">
                <div class="kanban" data-plugin="<?=($this->ion_auth->is_admin() || permissions('task_status'))?'dragula':''?>" data-containers='<?=json_encode($temp)?>'>
                  <?php foreach($task_status as $status){ ?>
                    <div class="tasks animated" data-sr-id="<?=htmlspecialchars($status['id'])?>" >
                      <div class="mt-0 task-header"><?=htmlspecialchars($status['title'])?>(
                        <span class="count">
                          <?php 
                            $total_task_count = 0;
                            if(isset($tasks) && !empty($tasks)){ foreach($tasks as $task){ if($status['title'] == $task['task_status']){
                                $total_task_count =  $total_task_count + 1;
                              }
                            } 
                            echo $total_task_count;
                            }
                          ?>
                        </span>
                        )
                      </div>
                      <div id="<?=htmlspecialchars($status['id'])?>" data-status="<?=htmlspecialchars($status['id'])?>" class="task-list-items">

                          <?php if(isset($tasks) && !empty($tasks)){ foreach($tasks as $task){ if($status['title'] == $task['task_status']){ ?>
                            <div class="card card-<?=htmlspecialchars($task['priority_class'])?> mt-1 mb-1" data-id="<?=htmlspecialchars($task['id'])?>">
                              <div class="card-body">
                                <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
                                  <li class="media">
                                    <div class="media-body">
                                      <div class="media-right">

                                        <a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
                                        <div class="dropdown-menu">
                                          <a href="#" data-edit="<?=htmlspecialchars($task['id'])?>" class="modal-task-detail dropdown-item"><?=$this->lang->line('details')?$this->lang->line('details'):'Details'?></a>

                                          <?php if ($this->ion_auth->is_admin() || permissions('task_edit')){ ?>
                                            <a href="#" data-edit="<?=htmlspecialchars($task['id'])?>" class="modal-edit-task dropdown-item"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></a>
                                          <?php } ?>

                                          <?php if ($this->ion_auth->is_admin() || permissions('task_delete')){ ?>
                                            <a href="#" class="text-danger delete_task dropdown-item" data-id="<?=htmlspecialchars($task['id'])?>"><?=$this->lang->line('trash')?$this->lang->line('trash'):'Trash'?></a>
                                          <?php } ?>
                                        </div>

                                      </div>
                                      <div class="media-title mb-1"><a href="#" data-edit="<?=htmlspecialchars($task['id'])?>" class="modal-task-detail"><?=htmlspecialchars($task['title'])?></a></div>
                                      <div class="author-box-job mb-2">
                                        <i class="fas fa-calendar-alt"></i>
                                          <span class="task_completed_div <?=$task['status'] == 4?'':'d-none'?>"> <?=$this->lang->line('completed')?htmlspecialchars($this->lang->line('completed')):'Completed'?></span>
                                            <span class="task_date_div <?=$task['status'] == 4?'d-none':''?>"> <?=htmlspecialchars($task['days_count'])?> <?=$this->lang->line('days')?$this->lang->line('days'):'Days'?> <?=htmlspecialchars($task['days_status'])?></span>
                                      </div>

                                      <?php  if(!empty($task['task_users'])){ ?>
                                        <div class="mt-2 mb-2">
                                        <div class="section-title mt-3 mb-1"><?=$this->lang->line('team_members')?htmlspecialchars($this->lang->line('team_members')):'Team Members'?></div>
                                          <?php foreach($task['task_users'] as $task_user){ 
                                            if(!empty($task_user['profile'])){
                                              if(file_exists('assets/uploads/profiles/'.$task_user['profile'])){
                                                $file_upload_path = 'assets/uploads/profiles/'.$task_user['profile'];
                                              }else{
                                                $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$task_user['profile'];
                                              }
                                          ?>
                                            <figure class="avatar avatar-sm">
                                              <img src="<?=base_url($file_upload_path)?>" alt="<?=htmlspecialchars($task_user['first_name'])?> <?=htmlspecialchars($task_user['last_name'])?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($task_user['first_name'])?> <?=htmlspecialchars($task_user['last_name'])?>">
                                            </figure>
                                          <?php }else{ ?>
                                            <figure class="avatar avatar-sm bg-primary text-white" data-initial="<?=ucfirst(mb_substr(htmlspecialchars($task_user['first_name']), 0, 1, 'utf-8')).''.ucfirst(mb_substr(htmlspecialchars($task_user['last_name']), 0, 1, 'utf-8'))?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($task_user['first_name'])?> <?=htmlspecialchars($task_user['last_name'])?>">
                                            </figure>
                                        <?php } } ?>
                                        </div>
                                      <?php } ?>

                                      <div class="media-links mt-2">
                                        <div class="section-title mt-3 mb-1"><?=$this->lang->line('priority')?htmlspecialchars($this->lang->line('priority')):'Priority'?></div>
                                        <span class="badge badge-<?=htmlspecialchars($task['priority_class'])?>">
                                        <?=htmlspecialchars($task['task_priority'])?>
                                        </span>
                                      </div>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          <?php } } } ?>


                      </div>
                    </div>
                  <?php } ?>
                  
                </div>
              </div>
            <?php } ?>

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
                <!--<div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Date'?></div>
                  <div class="profile-widget-item-value" id="task_starting_time"></div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label"><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Due Date'?></div>
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

            <li class="nav-item">
              <a class="nav-link" id="progress-tab" data-toggle="tab" href="#progress" role="tab" aria-controls="progress" aria-selected="false"><?=$this->lang->line('progress')?$this->lang->line('progress'):'Progress'?></a>
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
                  data-query-params="queryParams">
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
                   <!-- <th data-field="starting_time" data-sortable="false"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></th>-->
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
                     <th data-field="project_title" data-sortable="true"><?=$this->lang->line('project_title')?$this->lang->line('project_title'):'Project Title'?></th> 
                    <th data-field="s_no" data-sortable="false"><?=$this->lang->line('#')?$this->lang->line('#'):'#'?></th>
                    <th data-field="status" data-sortable="false"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
                    <th data-field="date_time" data-sortable="true"><?=$this->lang->line('date_time')?$this->lang->line('date_time'):'Date / Time'?></th>
                  </tr>
                </thead>
              </table>
            </div>

            <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                 <div class="row">
                     <div class="col-md-12">
                <div class="card-body" id="calendar"> 
                </div>
                 </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<form action="<?=base_url('projects/create-task')?>" method="POST" class="modal-part" id="modal-add-task-part" data-title="<?=$this->lang->line('create_new_task')?$this->lang->line('create_new_task'):'Create New Task'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <?php if(isset($project_id) && !empty($project_id)){ ?>
    <input type="hidden" name="project_id" value="<?=htmlspecialchars($project_id)?>">
  <?php }else{ ?>
    <div class="form-group">
      <label><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?><span class="text-danger">*</span></label>
      <select name="project_id" id="project_id" class="form-control select2">
        <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
        <?php foreach($projects as $project){ ?>
        <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
        <?php } ?>
      </select>
    </div>
  <?php } ?>
  
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
  <!--
  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
      <input type="time" name="starting_time" class="form-control timepicker" required>
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
      <input type="time" name="ending_time"  class="form-control timepicker" required>
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
  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
      <input type="time" name="starting_time" class="form-control timepicker">
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
      <input type="time" name="ending_time"  class="form-control timepicker" required>
    </div>
  </span>


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
<script src="<?=base_url('assets/modules/fullcalendar/main.js')?>"></script>


// <script>
//   var calendar; // Declare a variable to store the calendar instance
//   var is_module_allowed_timesheet = '<?=is_module_allowed('timesheet')?>';
//   var isAdmin = <?php echo $isAdmin ? 'true' : 'false'; ?>;
//   var locale = '<?php echo isset($current_lang[0]['short_code'])?htmlspecialchars($current_lang[0]['short_code']):'en'; ?>';

//   // Create a default empty calendar instance
//   var calendarEl = document.getElementById('calendar');
//   calendar = new FullCalendar.Calendar(calendarEl, {
//     locale: locale,
//     initialView: 'dayGridMonth',
//     events: [], // No events by default
//   });

//   document.querySelectorAll('.modal-task-detail').forEach(function (link) {
//     link.addEventListener('click', function (event) {
//       event.preventDefault();
//       var taskId = link.getAttribute('data-edit');

//       var data = {
//         "task_id": taskId
//       };

//       // Destroy the existing calendar instance if it exists
//       if (calendar) {
//         calendar.destroy();
//       }

//       $.ajax({
//         url: '<?= base_url('projects/get_user_progress') ?>',
//         method: "POST",
//         data: data,
//         success: function (response) {
//           // Handle the successful response
//           var variable = JSON.parse(response);
//           var calendarEvents = [];
//           var startDate = null;
//           var calendarEvents = [];
//           var previousEndDate = null; // Initialize a variable to keep track of the previous end date
//           for (var i = 0; i < variable.length; i++) {
//             var status = variable[i].status;
//             var task_status = '';
//             var eventColor = '';
//             if (status == 0) {
//               task_status = 'created';
//               eventColor = 'purple';
//             } else if (status == 1) {
//               task_status = 'todo';
//               eventColor = 'blue'; 
//             } else if (status == 2) {
//               task_status = 'inprogress';
//               eventColor = 'orange'; 
//             } else if (status == 3) {
//               task_status = 'inreview';
//               eventColor = '#a2b9bc'; 
//             } else if (status == 4) {
//               task_status = 'completed';
//               eventColor = 'green';
//             }

//             var endDate = null;
//             if (i < variable.length - 1) {
//               endDate = variable[i + 1].date_time; // Set end date to start date of the next status
//             } else if (status == 4) {
//               endDate = variable[i].date_time; // Keep the completion time as the end date for "completed" status
//             }
//             if (endDate == null) {
//               var currentDate = new Date();
//               var year = currentDate.getFullYear();
//               var month = String(currentDate.getMonth() + 1).padStart(2, '0');
//               var day = String(currentDate.getDate()).padStart(2, '0');
//               var hours = String(currentDate.getHours()).padStart(2, '0');
//               var minutes = String(currentDate.getMinutes()).padStart(2, '0');
//               var seconds = String(currentDate.getSeconds()).padStart(2, '0');
//               endDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
//             }
//             var task = {
//               id: i + 1,
//               title: task_status + (i + 1), // Adjust title as needed
//               start: variable[i].date_time,
//               end: endDate,
//               status: task_status,
//               color: eventColor, // Set the event color
//             };

//             calendarEvents.push(task);
//           }
//           console.log(calendarEvents);

//           var calendar = new FullCalendar.Calendar(calendarEl, {
//             locale: locale,
//             initialView: 'dayGridMonth',
//             events: calendarEvents,
//           });
//           calendar.render();
//         },
//         error: function (xhr, status, error) {
//           // Handle errors
//           console.error("Error: " + error);

//           // Render the empty calendar
//           calendar.render();
//         }
//       });
//     });
//   });
// </script>

<script>
  var calendar; // Declare a variable to store the calendar instance
  var is_module_allowed_timesheet = '<?=is_module_allowed('timesheet')?>';
  var isAdmin = <?php echo $isAdmin ? 'true' : 'false'; ?>;
  var locale = '<?php echo isset($current_lang[0]['short_code'])?htmlspecialchars($current_lang[0]['short_code']):'en'; ?>';

  // Create a default empty calendar instance
  var calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    locale: locale,
    initialView: 'dayGridMonth',
    events: [], // No events by default
  });

  document.querySelectorAll('.modal-task-detail').forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();
      var taskId = link.getAttribute('data-edit');

      var data = {
        "task_id": taskId
      };

      // Destroy the existing calendar instance if it exists
      if (calendar) {
        calendar.destroy();
      }

      $.ajax({
        url: '<?= base_url('projects/get_user_progress') ?>',
        method: "POST",
        data: data,
        success: function (response) {
          // Handle the successful response
          var variable = JSON.parse(response);
          var calendarEvents = [];
          var startDate = null;
          var previousEndDate = null; // Initialize a variable to keep track of the previous end date
          for (var i = 0; i < variable.length; i++) {
            var status = variable[i].status;
            var task_status = '';
            var eventColor = '';
            if (status == 0) {
              task_status = 'created';
              eventColor = 'purple';
            } else if (status == 1) {
              task_status = 'todo';
              eventColor = 'blue'; // Change the color for status 1 to 'blue'
            } else if (status == 2) {
              task_status = 'inprogress';
              eventColor = 'orange'; // Change the color for status 2 to 'orange'
            } else if (status == 3) {
              task_status = 'inreview';
              eventColor = '#a2b9bc'; // Change the color for status 3 to 'LightGray'
            } else if (status == 4) {
              task_status = 'completed';
              eventColor = 'green';
            }

            var endDate = null;
            if (i < variable.length - 1) {
              endDate = variable[i + 1].date_time; // Set end date to start date of the next status
            } else if (status == 4) {
              endDate = variable[i].date_time; // Keep the completion time as the end date for "completed" status
            }
            if (endDate == null) {
              var currentDate = new Date();
              var year = currentDate.getFullYear();
              var month = String(currentDate.getMonth() + 1).padStart(2, '0');
              var day = String(currentDate.getDate()).padStart(2, '0');
              var hours = String(currentDate.getHours()).padStart(2, '0');
              var minutes = String(currentDate.getMinutes()).padStart(2, '0');
              var seconds = String(currentDate.getSeconds()).padStart(2, '0');
              endDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }
            var task = {
              id: i + 1,
              title: task_status + (i + 1), // Adjust title as needed
              start: variable[i].date_time,
              end: endDate,
              status: task_status,
              color: eventColor, // Set the event color
            };

            calendarEvents.push(task);
          }
          console.log(calendarEvents);

          // Use the existing 'calendar' instance, no need to redeclare it
          calendar.setOption('events', calendarEvents);
        },
        error: function (xhr, status, error) {
          // Handle errors
          console.error("Error: " + error);

          // Render the empty calendar
          calendar.render();
        }
      });
    });
  });
</script>

<script src="<?=base_url('assets/modules/dragula/dragula.min.js');?>"></script>
<script src="<?=base_url('assets/js/page/tasks.js');?>"></script>

</body>
</html>
