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
              <?=$this->lang->line('shift_schedule')?$this->lang->line('shift_schedule'):'Office Shift Scheduling'?>
              <?php if($this->ion_auth->is_admin() ){ ?>
              <a href="#" id="modal-add-shift" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create_shift')?$this->lang->line('create_shift'):'Create Shift'?></a>
              <?php } ?>
              <!-- <?php if($this->ion_auth->is_admin() ){ ?>
              <a href="#" id="modal-add-assign-shift" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('assign_shift')?$this->lang->line('assign_shift'):'Assign Shift'?></a>
              <?php } ?> -->
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('shift_schedule')?$this->lang->line('shift_schedule'):'Shift Scheduling'?>
              </div>
            </div>
          </div>
          <div class="section-body">
            
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body">
                        <table class='table-striped' id='shift_list'
                          data-toggle="table"
                          data-url="<?=base_url('shift/get_shift')?>"
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
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "shift-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="sr_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('sr_no'):'#'?></th>
                              <th data-field="name" data-sortable="true"><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></th>
                              <th data-field="users" data-sortable="false"><?=$this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member'?></th>
                              <th data-field="starting_time" data-sortable="true"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></th>
                              <th data-field="ending_time" data-sortable="true"><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?></th>
                              <th data-field="break_start" data-sortable="false" data-visible="true"><?=$this->lang->line('break_start')?$this->lang->line('break_start'):'Break Start'?></th>
                              <th data-field="break_end" data-sortable="true"><?=$this->lang->line('break_end')?$this->lang->line('break_end'):'Break End'?></th>
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


<form action="<?=base_url('shift/create')?>" method="POST" class="modal-part" id="modal-add-shift-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
    
    <div class="form-group">
        <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required="">
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
            <input type="text" name="starting_time" class="form-control timepicker" required="" value="9:00 AM">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
            <input type="text" name="ending_time" class="form-control timepicker" required="" value="6:00 PM">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('break_start')?$this->lang->line('break_start'):'Break Start'?><span class="text-danger">*</span></label>
            <input type="text" name="break_start" class="form-control timepicker" required="" value="1:15 PM">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('break_end')?$this->lang->line('break_end'):'Break End'?><span class="text-danger">*</span></label>
            <input type="text" name="break_end" class="form-control timepicker" required="" value="2:15 PM">
        </div>
    </div>

</form>

<form action="<?=base_url('shift/shift_create')?>" method="POST" class="modal-part" id="modal-add-assign-shift-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
    <div class="form-group">
        <label><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?></label>
        <select name="type" class="form-control select2">
            <?php foreach($shift_types as $shift_type){ ?>
            <option value="<?= $shift_type['id'] ?>"><?= $shift_type['name'] ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
            <input type="text" name="starting_time" class="form-control timepicker" required="" value="9:00 AM">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
            <input type="text" name="ending_time" class="form-control timepicker" required="" value="6:00 PM">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('break_start')?$this->lang->line('break_start'):'Break Start'?><span class="text-danger">*</span></label>
            <input type="text" name="break_start" class="form-control timepicker" required="" value="1:15 PM">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('break_end')?$this->lang->line('break_end'):'Break End'?><span class="text-danger">*</span></label>
            <input type="text" name="break_end" class="form-control timepicker" required="" value="2:15 PM">
        </div>
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('users')?$this->lang->line('users'):'Users'?><span class="text-danger">*</span></label>
        <select name="users[]" class="form-control select2" multiple="">
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
        </select>
    </div>
</form>

<form action="<?=base_url('shift/edit')?>" method="POST" class="modal-part" id="modal-edit-shift-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">

    <input type="hidden" name="update_id" id="update_id">

    <div class="form-group">
        <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control" required="">
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
            <input type="text" name="starting_time" id="starting_time" class="form-control timepicker" required="">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
            <input type="text" name="ending_time" id="ending_time" class="form-control timepicker" required="">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('break_start')?$this->lang->line('break_start'):'Break Start'?><span class="text-danger">*</span></label>
            <input type="text" name="break_start" id="break_start" class="form-control timepicker" required="">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('break_end')?$this->lang->line('break_end'):'Break End'?><span class="text-danger">*</span></label>
            <input type="text" name="break_end" id="break_end" class="form-control timepicker" required="">
        </div>
    </div>

    <div class="form-group">
    <label><?=$this->lang->line('users')?$this->lang->line('users'):'Users'?><span class="text-danger">*</span></label>
    <select name="users[]" id="users" class="form-control select2" multiple="">
        <option value="0"><?=$this->lang->line('casual_leave')?$this->lang->line('casual_leave'):'Select Shift'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
    </select>
    </div>

</form>

<div id="modal-edit-shift"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
    return {
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
  $('#tool').on('change',function(e){
    $('#shift_list').bootstrapTable('refresh');
  });
</script>
</body>
</html>
