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
              <?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?>
              <?php if($this->ion_auth->is_admin() || permissions('meetings_create')){ ?>
              <a href="#" id="modal-add-meetings" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?>
              </div>
            </div>
          </div>
          <div class="section-body">
            
            <div class="row">

                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body">
                        <table class='table-striped' id='video_meetings_list'
                          data-toggle="table"
                          data-url="<?=base_url('meetings/get_meetings')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="false"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="true" data-show-columns="true"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="DESC"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="false"
                          data-maintain-selected="true"
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "video_meetings-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?></th>
                              <th data-field="starting_date_and_time" data-sortable="true"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></th>
                              <th data-field="duration" data-sortable="true"><?=$this->lang->line('duration')?$this->lang->line('duration'):'Duration (Minutes)'?></th>
                              <th data-field="created_user" data-sortable="false" data-visible="false"><?=$this->lang->line('scheduled_by')?$this->lang->line('scheduled_by'):'Scheduled By'?></th>
                              <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
                              <th data-field="action" data-sortable="true"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
              
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>


  <form action="<?=base_url('meetings/create')?>" method="POST" class="modal-part" id="modal-add-meetings-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <div class="form-group">
    <label><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?><span class="text-danger">*</span><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('any_special_characters_not_allowed')?$this->lang->line('any_special_characters_not_allowed'):"Any special characters not allowed"?>"></i></label>
    <input type="text" name="title" class="form-control" required="">
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
    <input type="text" name="starting_date_and_time" class="form-control datetimepicker">
  </div>
  <div class="form-group">
    <label><?=$this->lang->line('duration')?$this->lang->line('duration'):'Duration (Minutes)'?><span class="text-danger">*</span></label>
    <input type="number" pattern="[0-9]" name="duration" class="form-control">
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('users')?$this->lang->line('users'):'Users'?><span class="text-danger">*</span></label>
    <select name="users[]" class="form-control select2" multiple="">
      <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
      <?php } } ?>
    </select>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="send_email_notification" name="send_email_notification">
    <label class="form-check-label text-danger" for="send_email_notification"><?=$this->lang->line('send_email_notification')?$this->lang->line('send_email_notification'):'Send email notification'?></label>
  </div>

</form>


<form action="<?=base_url('meetings/edit')?>" method="POST" class="modal-part" id="modal-edit-meetings-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">

<input type="hidden" name="update_id" id="update_id">

<div class="form-group">
  <label><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?><span class="text-danger">*</span><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('any_special_characters_not_allowed')?$this->lang->line('any_special_characters_not_allowed'):"Any special characters not allowed"?>"></i></label>
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

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
    return {
      "to_id": $('#client').val(),
      "status": $('#status_filter').val(),
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
  $('#tool').on('change',function(e){
    $('#video_meetings_list').bootstrapTable('refresh');
  });
</script>
</body>
</html>
