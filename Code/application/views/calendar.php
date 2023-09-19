<?php $this->load->view('includes/head'); ?>
<link rel="stylesheet" href="<?=base_url('assets/modules/fullcalendar/main.css');?>" >

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
            <?=$this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar'?> 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="form-group col-md-6">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/calendar")?>"><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                  <?php foreach($projects_filter as $project){ ?>
                  <option value="<?=base_url("projects/calendar/".htmlspecialchars($project['id']))?>" <?=(!empty($this->uri->segment(3)) && is_numeric($this->uri->segment(3)) && $this->uri->segment(3) == $project['id'])?"selected":""?>><?=htmlspecialchars($project['title'])?></option>
                  <?php } ?>
                </select>
              </div>
              
              
              <div class="form-group col-md-2">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/calendar")?>"><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?></option>
                  <?php foreach($project_status as $status){ ?>
                  <option value="<?=base_url("projects/calendar?status=".htmlspecialchars($status['id']))?>" <?=(isset($_GET['status']) && !empty($_GET['status']) && is_numeric($_GET['status']) && $_GET['status'] == $status['id'])?"selected":""?>><?=htmlspecialchars($status['title'])?></option>
                  <?php } ?>
                </select>
              </div>
              
              <?php if(!is_client()){ ?>
              <div class="form-group col-md-2">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/calendar")?>"><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                  <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=base_url("projects/calendar?user=".htmlspecialchars($system_user->id))?>" <?=(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user']) && $_GET['user'] == $system_user->id)?"selected":""?>><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              
              <div class="form-group col-md-2">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects/calendar")?>"><?=$this->lang->line('select_client')?$this->lang->line('select_client'):'Select Client'?></option>
                  <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=base_url("projects/calendar?client=".htmlspecialchars($system_client->id))?>" <?=(isset($_GET['client']) && !empty($_GET['client']) && is_numeric($_GET['client']) && $_GET['client'] == $system_client->id)?"selected":""?>><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              <?php } ?>

            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body" id="calendar"> 
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
            <?php if(!is_client()){ ?>
            <li class="nav-item">
              <a class="nav-link" id="timesheet-tab" data-toggle="tab" href="#timesheet" role="tab" aria-controls="timesheet" aria-selected="false"><?=$this->lang->line('timesheet')?$this->lang->line('timesheet'):'Timesheet'?></a>
            </li>
            <?php } ?>
          </ul>
          <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                <div class="p-0 d-flex">
                    <input type="hidden" name="comment_task_id" id="comment_task_id" value="">
                    <input type="hidden" name="is_comment" id="is_comment" value="true">
                    <input type="text" name="message" id="message" class="form-control" placeholder="Type a message" required>
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
            </div>

            <?php if(!is_client()){ ?>
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
                    <?php if($this->ion_auth->is_admin() || permissions('project_view_all')){ ?>
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

          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<div id="modal-task-detail"></div>

<?php $this->load->view('includes/js'); ?>
<script src="<?=base_url('assets/modules/fullcalendar/main.js')?>"></script>

<script>
  function queryParams(p){
      return {
        "task_id": $('#comment_task_id').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }

<?php 
    if(isset($final_data) && !empty($final_data)){
      $final_data = json_encode($final_data); ?>
      var tasks = <?php echo $final_data;?>;
<?php }else{ 
      $final_data = '';
      ?>
      var tasks = '';
<?php } 
$default_language = $this->session->userdata('lang')?$this->session->userdata('lang'):default_language();
$current_lang = get_languages('', $default_language);
?>

  var locale = '<?php echo isset($current_lang[0]['short_code'])?htmlspecialchars($current_lang[0]['short_code']):'en'; ?>';
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: locale,
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
    events: tasks,
    eventClick: function(info) {
      if(info.event.extendedProps.is_project == 'yes'){
        window.open(base_url+'projects/detail/'+info.event.id, '_blank');
      }else{
          $.ajax({
              type: "POST",
              url: base_url+'projects/get_tasks', 
              data: "task_id="+info.event.id,
              dataType: "json",
              success: function(result) 
              {	

                if(result['error'] == false){
                  if(result['data'][0]['can_see_time']){
                    $('#timer_btn').removeClass('d-none');
                    $('#timer_btn').attr('data-project_id',result['data'][0]['project_id']);
                    $('#timer_btn').attr('data-task_id',result['data'][0]['id']);
                  }else{
                    $('#timer_btn').addClass('d-none');
                  }

                  if(result['data'][0]['timer_running']){
                    $('#timer_btn').removeClass('bg-success');
                    $('#timer_btn').addClass('bg-danger');
                    $('#timer_btn').html(stop_timer);
                  }else{
                    $('#timer_btn').removeClass('bg-danger');
                    $('#timer_btn').addClass('bg-success');
                    $('#timer_btn').html(start_timer);
                  }

                  $("#task_title").html(result['data'][0]['title']).removeClass().addClass('text-'+result['data'][0]['task_class']);
                  $("#comment_task_id").val(result['data'][0]['id']);
                  $("#attachment_task_id").val(result['data'][0]['id']);
                  $("#task_project").html(result['data'][0]['project_title']).attr('href',base_url+'projects/detail/'+result['data'][0]['project_id']);
                  $("#task_description").html(result['data'][0]['description']);

                  
                  if(result['data'][0]['status'] == 4){
                    $("#task_days_count").html(completed);
                  }else{
                    $("#task_days_count").html(result['data'][0]['days_count']+' '+days+' '+result['data'][0]['days_status']);
                  }

                  $("#task_due_date").html(result['data'][0]['due_date']);
	              	$("#task_starting_date").html(result['data'][0]['starting_date']);
                  $("#task_priority").html(result['data'][0]['task_priority']).removeClass().addClass('text-'+result['data'][0]['priority_class']);

                  var profile_1 = '';
                  $.each(result['data'][0]['task_users'], function (key, val) {
                    if(val.profile){
                      var file_upload_path = '';
                      if(doesFileExist(base_url+'assets/uploads/profiles/'+val.profile)){
                        file_upload_path = base_url+'assets/uploads/profiles/'+val.profile;
                      }else{
                        file_upload_path = base_url+'assets/uploads/f'+saas_id+'/profiles/'+val.profile;
                      }
                      profile_1 += '<figure class="avatar avatar-sm mr-1">'+
                              '<img src="'+file_upload_path+'" alt="'+val.first_name+' '+val.last_name+'" data-toggle="tooltip" data-placement="top" title="'+val.first_name+' '+val.last_name+'">'+
                            '</figure>';
                    }else{
                      profile_1 += '<figure class="avatar avatar-sm bg-primary text-white mr-1" data-initial="'+val.first_name.charAt(0)+''+val.last_name.charAt(0)+'" data-toggle="tooltip" data-placement="top" title="'+val.first_name+' '+val.last_name+'"></figure>';
                    }
                  });

                  $("#task_users").html(profile_1);
                  
                  $("#modal-task-detail").trigger("click");
                  
                  $.ajax({
                    type: "POST",
                    url: base_url+'projects/get_comments', 
                    data: "type=task_comment&to_id="+result['data'][0]['id'],
                    dataType: "json",
                    success: function(result_1) 
                    {	
                      if(result_1['error'] == false){
                        var html = '';
                        var profile = '';
                        $.each(result_1['data'], function (key, val) {
                          if(val.profile){
                            
                            var file_upload_path = '';
                            if(doesFileExist(base_url+'assets/uploads/profiles/'+val.profile)){
                              file_upload_path = base_url+'assets/uploads/profiles/'+val.profile;
                            }else{
                              file_upload_path = base_url+'assets/uploads/f'+saas_id+'/profiles/'+val.profile;
                            }

                            profile = '<figure class="avatar avatar-md mr-3">'+
                              '<img src="'+file_upload_path+'" alt="'+val.first_name+' '+val.last_name+'">'+
                            '</figure>';
                          }else{
                            profile = '<figure class="avatar avatar-md bg-primary text-white mr-3" data-initial="'+val.short_name+'"></figure>';
                          }
                          if(val.can_delete){
                            var can_delete = '<div class="float-right text-primary"><a href="#" class="btn btn-icon btn-sm btn-danger delete_comment" data-id="'+val.id+'" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></a></div>';
                          }
                          html += '<ul class="list-unstyled list-unstyled-border mt-3">'+
                          '<li class="media">'+profile+
                            '<div class="media-body">'+
                            '<div class="float-right text-primary">'+val.created+'</div>'+
                            '<div class="media-title">'+val.first_name+' '+val.last_name+'</div>'+can_delete+
                            '<span class="text-muted">'+val.message+'</span>'+
                            '</div>'+
                          '</li>'+
                            '</ul>';
                        });
                        $("#comments_append").html(html);
                      }
                    }        
                  });

                }else{
                  iziToast.error({
                    title: something_wrong_try_again,
                    message: "",
                    position: 'topRight'
                  });
                }
              }        
          });

      }
    }
    });
    calendar.render();
  });
  

  $(document).on('change','.calendar_filter',function(){
    $('#calendar_list').bootstrapTable('refresh');
  });

</script>
</body>
</html>
