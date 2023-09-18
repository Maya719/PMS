<?php $this->load->view('includes/head'); ?>
<style>
    .hidden{
    display: none;
  }
  
.loader-container {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.2); /* Light gray color with opacity */
    z-index: 9999; /* Set a high z-index to ensure it's above other content */
}

.loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
      <!-- Main Content -->
      <div class="loader-container"><div class="loader"></div></div>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <div class="section-header-back">
              <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
            <?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance'?> 
              <!-- <?php if ($this->ion_auth->in_group(1)){ ?>
                <a href="#" id="modal-add-attendance" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?> -->
              <?php
                if(!$this->ion_auth->is_admin()){
              ?>
              <div class="btn-group">
                <a href="<?=base_url('attendance/user_attendance/'.$user_id)?>" class="btn btn-sm "><?=$this->lang->line('report')?htmlspecialchars($this->lang->line('report')):'Report View'?></a>
                <a href="#" class="btn btn-sm btn-primary"><?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?></a>
              </div>
              <?php
                }
              ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card-primary card-statistic-2">
                  <div class="card-stats">
                    <?php
                    if($this->ion_auth->is_admin()){
                    ?>
                    <div class="card-stats-title"><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'Attendance'?></div>
                    <div class="card-stats-items mb-3">
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                        <a onclick="staff()" class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" id="total_staff"><?= $totalStaff ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('presents')?$this->lang->line('presents'):'Total Staff'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a onclick="pre()" class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" id="present"><?= $presentStaff ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('presents')?$this->lang->line('presents'):'Presents'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a onclick="late()" class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" id="late"><?= $late ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('late')?$this->lang->line('late'):'late'?></div>
                      </div>
                      <div class="card-stats-item">
                        <div class="card-stats-item-count text-primary">
                          <a class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" onclick="abs()" id="absents"><?= $absents ?></div></a>
                        </div>
                        <div class="card-stats-item-label text-primary"><?=$this->lang->line('absents')?$this->lang->line('absents'):'Absents'?></div>
                      </div>
                    </div>
                    <?php
                    }else{
                    ?>
                    <div class="card-stats-title"><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'Attendance(Day`s)'?></div>
                    <div class="card-stats-items mb-3">
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a onclick="pre()" class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" id="present"><?= $presentStaff ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('presents')?$this->lang->line('presents'):'Presents'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a onclick="late()" class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" id="late"><?= $late ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('late')?$this->lang->line('late'):'late'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a onclick="late()" class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" id="late_min"><?= $late ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('late_min')?$this->lang->line('late_min'):'Late Min'?></div>
                      </div>
                      <div class="card-stats-item">
                        <div class="card-stats-item-count text-primary">
                          <a class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" onclick="abs()" id="absents"><?= $absents ?></div></a>
                        </div>
                        <div class="card-stats-item-label text-primary"><?=$this->lang->line('absents')?$this->lang->line('absents'):'Absents'?></div>
                      </div>
                    </div>
                    <?php }
                    ?>                  
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card card-primary card-statistic-2">
                  <div class="card-stats">
                    <div class="card-stats-title"><?=$this->lang->line('leaves_request')?$this->lang->line('leaves_request'):'Leaves'?></div>
                    <div class="card-stats-items mb-3">
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a class="text-primary" href="<?=base_url('leaves')?>"><div class="card-stats-item-count preabs" id="leave_total"><?= $leaves ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('total_leaves')?$this->lang->line('total_leaves'):'Total'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a class="text-primary" href="<?=base_url('leaves')?>"><div class="card-stats-item-count preabs" id="leave_pending"><?= $leaves ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('pending')?$this->lang->line('pending'):'Pending'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a onclick="leave()" class="text-primary" href="javascript:void(0)"><div class="card-stats-item-count preabs" id="leave"><?= $leaves ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('approved')?$this->lang->line('approved'):'Approved'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a class="text-primary" href="<?=base_url('leaves')?>"><div class="card-stats-item-count preabs" id="leave_rejected"><?= $leaves ?></div></a>
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
                    <div class="card-stats-title"><?=$this->lang->line('biometric')?$this->lang->line('biometric'):'Biometric Requests'?></div>
                    <div class="card-stats-items mb-3">
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                          <a class="text-primary" href="<?=base_url('biometric_missing')?>"><div class="card-stats-item-count preabs" id="bio_total"><?= $leaves ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('bio_total')?$this->lang->line('bio_total'):'Total'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count">
                        <a class="text-primary" href="<?=base_url('biometric_missing')?>"><div class="card-stats-item-count preabs" id="bio_pending"><?= $leaves ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('pending')?$this->lang->line('pending'):'Pending'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count" id="">
                        <a class="text-primary" href="<?=base_url('biometric_missing')?>"><div class="card-stats-item-count preabs" id="bio_approved"><?= $leaves ?></div></a>

                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('approved')?$this->lang->line('approved'):'Approved'?></div>
                      </div>
                      <div class="card-stats-item text-primary">
                        <div class="card-stats-item-count" id="">
                        <a class="text-primary" href="<?=base_url('biometric_missing')?>"><div class="card-stats-item-count preabs" id="bio_rejected"><?= $leaves ?></div></a>
                        </div>
                        <div class="card-stats-item-label"><?=$this->lang->line('Rejected')?$this->lang->line('Rejected'):'Rejected'?></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <?php if($this->ion_auth->is_admin()){ ?>
                <div class="form-group col-md-6">
                  <select class="form-control select2" id="shifts_ids">
                    <option value=""><?=$this->lang->line('select_shift')?$this->lang->line('select_shift'):'Select Shift'?></option>
                    <?php foreach($shifts as $shift){ ?>
                      <option value="<?= $shift['id'] ?>"><?= $shift['name'] ?></option>
                    <?php 
                  }?>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <select class="form-control select2" id="department">
                    <option value=""><?=$this->lang->line('select_department')?$this->lang->line('select_department'):'Select Department'?></option>
                    <?php foreach($departments as $department){ ?>
                      <option value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
                    <?php 
                  }?>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <select class="form-control select2" id="attendance_filter_user">
                    <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=$system_user->employee_id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>
              <?php } ?>
              <?php if($this->ion_auth->is_admin()){ ?>
              <div class="form-group col-md-6">
                <input type="text" name="from" id="from" class="form-control">
              </div>
              <?php
              }else{
                ?>
                <div class="form-group col-md-4">
                  <select class="form-control select2" id="attendance_filter" onchange="updateDivContent()">
                    <option value="today"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Today'?></option>
                    <option value="ystdy"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Yesterday'?></option>
                    <option value="tweek"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Week'?></option>
                    <option value="tmonth" selected><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Month'?></option>
                    <option value="lmonth"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Month'?></option>
                    <option value="custom"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Custom'?></option>
                  </select>
                </div>
                <div id="myDiv" class="form-group col-md-4 hidden">
                  <input type="text" name="from" id="from" class="form-control">
                </div>
                
                <div id="myDiv2" class="form-group col-md-4 hidden">
                  <input type="text" name="too" id="too" class="form-control">
                </div>
              <?php
              }
              ?>
              <div class="form-group col-md-4 hidden">
                <input type="hidden" name="preabs" id="preabs" class="form-control" value="">
              </div>
              <!-- <div class="form-group col-md-2">
                <button type="button" class="btn btn-primary btn-lg btn-block" id="filter">
                  <?=$this->lang->line('filter')?$this->lang->line('filter'):'Filter'?>
                </button>
              </div> -->
            </div>
            <div id="toolbar">
            <h5 id="filterDate">
              <?php 
              $currentDate = date('j M');
              echo $currentDate;
              ?>
            </h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                  <div class="card card-primary">
                    <div class="card-body"> 
                      <table class='table-striped' id='attendance_list'
                        data-toggle="table"
                        <?php
                          if ($this->ion_auth->is_admin()) {
                              $url = base_url('attendance/get_attendance');
                          } else {
                              $url = base_url('attendance/get_user_attendance');
                          }
                        ?>
                        data-url="<?= $url ?>"
                        data-click-to-select="true"
                        data-side-pagination="server"
                        data-pagination="true"
                        data-search="true" 
                        data-show-columns="true"
                        data-page-list="[5, 10, 20, 50, 100, 200]"
                        data-show-refresh="false" 
                        data-trim-on-search="false"
                        data-sort-name="id" 
                        data-sort-order="desc"
                        data-mobile-responsive="true"
                        data-toolbar="#toolbar"
                        data-show-export="false" 
                        data-export-types="['json', 'csv', 'txt', 'sql', 'doc', 'excel', 'pdf']"
                        data-maintain-selected="true"
                        data-query-params="queryParams">
                        <thead>
                          <tr>
                            <th data-width="10%" data-field="s.n" data-sortable="false"><?=$this->lang->line('s.n')?$this->lang->line('s.n'):'#'?></th>
                            <th data-width="20%" data-field="user_id" data-sortable="false"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Emp ID'?></th>
                            <th data-field="user" data-sortable="false"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Employee'?></th>
                            <?php if(!$this->ion_auth->is_admin()){ ?><th data-field="date" data-sortable="false"><?=$this->lang->line('date')?htmlspecialchars($this->lang->line('date')):'Date'?></th><?php } ?>
                            <th data-field="check_in" data-sortable="false"><?=$this->lang->line('check_in')?htmlspecialchars($this->lang->line('check_in')):'Time (in/out)'?></th>
                            <th data-field="shift_name" data-sortable="false" data-visible="false"><?=$this->lang->line('shift_name')?htmlspecialchars($this->lang->line('shift_name')):'Shift'?></th>
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
<form action="<?=base_url('attendance/create')?>" method="POST" class="modal-part" id="modal-add-attendance-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></label>
      <select name="user_id" class="form-control select2 user_id">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){  ?>
        <option value="<?=$system_user->employee_id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  <?php } ?>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('check_in')?htmlspecialchars($this->lang->line('check_in')):'Check In'?></label>
      <input type="text" name="check_in" class="form-control datetimepicker">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('check_out')?htmlspecialchars($this->lang->line('check_out')):'Check Out'?></label>
      <input type="text" name="check_out" class="form-control datetimepicker">
    </div>
  </span>

  <div class="form-group">
    <label><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></label>
    <textarea type="text" name="note" class="form-control"></textarea>
  </div>
</form>

<form action="<?=base_url('attendance/edit')?>" method="POST" class="modal-part" id="modal-edit-attendance-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">

  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></label>
      <select name="user_id" id="user_id" class="form-control select2 user_id">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->employee_id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
    <span class="row">
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('check_in')?htmlspecialchars($this->lang->line('check_in')):'Check In'?></label>
        <input type="text" name="check_in" id="check_in" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('check_out')?htmlspecialchars($this->lang->line('check_out')):'Check Out'?></label>
        <input type="text" name="check_out" id="check_out" class="form-control">
      </div>
    </span>
  <?php } ?>

  <div class="form-group">
    <label><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></label>
    <textarea type="text" name="note" id="note" class="form-control"></textarea>
  </div>

</form>

<div id="modal-edit-attendance"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  function staff(){
    $('#preabs').val('staff');
    console.log($('#preabs').val());
  }
  function abs(){
    $('#preabs').val('absent');
    console.log($('#preabs').val());
  }
  function pre(){
    $('#preabs').val('present');
    console.log($('#preabs').val());
  }
  function late(){
    $('#preabs').val('late');
    console.log($('#preabs').val());
  }
  function leave(){
    $('#preabs').val('leave');
    console.log($('#preabs').val());
  }
</script>
<script>
  function queryParams(p) {
    var userId = $('#attendance_filter_user').val();
    
    var params = {
      "user_id": userId,
      "filter": $('#attendance_filter').val(),
      "department": $('#department').val(),
      "shifts": $('#shifts_ids').val(),
      "from": $('#from').val(),
      "too": $('#too').val(),
      "preabs": $('#preabs').val(),
      limit: p.limit,
      sort: p.sort,
      order: p.order,
      offset: p.offset,
      search: p.search
    };
    return params;
  }
</script>

<script>
function showLoader() {
$('.loader-container').css('display', 'block');
}

function hideLoader() {
    $('.loader-container').css('display', 'none');
}
</script>
<script>
  $( '#shifts_ids').on('change', function(e) {
    $('#attendance_list').bootstrapTable('refresh');
  });
</script>
<script>
  $( '#department').on('change', function(e) {
  $('#attendance_list').bootstrapTable('refresh');
  var department = $('#department').val();
  var data = {
  "department": department,
  "active": '1'
};
console.log(data);
showLoader();
$.ajax({
    url: '<?= base_url('attendance/get_users_by_department') ?>', // Replace with your actual API URL
    type: 'POST', // Adjust the request method if needed
    data: data,
    success: function(response) {
      hideLoader();
      var data = JSON.parse(response);
      const select = $("#attendance_filter_user");
      // Clear previous options
      select.empty().append('<option value="">Select Users</option>');

      // Add new options based on the user data
      $.each(data, function(index, user) {
        const option = $('<option>')
          .val(user.employee_id) // Assuming each user has a unique "id" property
          .text(user.first_name+' '+user.last_name); // Assuming each user has a "name" property
          select.append(option);
      });
    },
    error: function(xhr, status, error) {
      // Handle any errors that occur during the AJAX request
      console.log('Error:', error);
    }
  });

  });
</script>
<script>
  $( '#shifts_ids').on('change', function(e) {
  $('#attendance_list').bootstrapTable('refresh');
  var shifts_ids = $('#shifts_ids').val();
  var data = {
  "shifts_ids": shifts_ids,
  "active": '1'
};
console.log(data);
showLoader();
$.ajax({
    url: '<?= base_url('attendance/get_users_by_shifts') ?>', // Replace with your actual API URL
    type: 'POST', // Adjust the request method if needed
    data: data,
    success: function(response) {
        hideLoader();
        var data = JSON.parse(response);
        console.log(data);
        const select = $("#attendance_filter_user");
        select.empty(); // Clear previous options
        
        if (data.length === 0) {
            select.append('<option value="" selected>No users found</option>');
        } else {
            select.append('<option value="" selected>Select Users</option>');
            
            // Add new options based on the user data
            $.each(data, function(index, user) {
                const option = $('<option>')
                    .val(user.employee_id) // Assuming each user has a unique "employee_id"
                    .text(user.first_name + ' ' + user.last_name);
                select.append(option);
            });
        }
    },
    error: function(xhr, status, error) {
      // Handle any errors that occur during the AJAX request
      console.log('Error:', error);
    }
  });

  });
</script>
<script>
$('#from, #too, #attendance_filter, #attendance_filter_user, #preabs').on('change', function(e) {
  $('#attendance_list').bootstrapTable('refresh');
  if ($('#attendance_filter').val() == 'custom') {
  var date = $('#from').val();
  var parts = date.split(' ');
  var date2 = $('#too').val();
  var parts2 = date2.split(' ');
  var formattedDate;
  formattedDate = parts[0] + ' ' + parts[1] + ' - ' + parts2[0] + ' ' + parts2[1];
  var from = $('#from').val();
  var too = $('#too').val();
  }
  if ($('#attendance_filter').val() == 'today') {
    var currentDate = new Date();
    var day = currentDate.getDate();
    var monthIndex = currentDate.getMonth();
    var year = currentDate.getFullYear();
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var formattedDate = day + ' ' + months[monthIndex];
    var from = day + ' ' + months[monthIndex] + ' ' + year;
    var too = day + ' ' + months[monthIndex] + ' ' + year;
  }
  if ($('#attendance_filter').val() == 'ystdy') {
    var currentDate = new Date();
    currentDate.setDate(currentDate.getDate() - 1);

    var day = currentDate.getDate();
    var monthIndex = currentDate.getMonth();
    var year = currentDate.getFullYear();

    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var formattedDate = day + ' ' + months[monthIndex];
    var from = day + ' ' + months[monthIndex] + ' ' + year;
    var too = day + ' ' + months[monthIndex] + ' ' + year;
  }
  if ($('#attendance_filter').val() == 'tweek') {
    var currentDate = new Date();
var currentDay = currentDate.getDay(); // Sunday: 0, Monday: 1, Tuesday: 2, ..., Saturday: 6

// Calculate the date of the last Monday
var lastMondayDate = new Date(currentDate);
lastMondayDate.setDate(currentDate.getDate() - currentDay +1); // Subtract 7 days to go back to the previous Monday

var lastMondayDayOfMonth = lastMondayDate.getDate();
var currentDayOfMonth = currentDate.getDate();

var lastMondayMonth = lastMondayDate.toLocaleString('default', { month: 'short' });
var currentMonth = currentDate.toLocaleString('default', { month: 'short' });

var formattedDate = lastMondayDayOfMonth + ' ' + lastMondayMonth + ' - ' + currentDayOfMonth + ' ' + currentMonth;

var from = lastMondayDayOfMonth + ' ' + lastMondayMonth + ' ' + currentDate.getFullYear();
var too = currentDayOfMonth + ' ' + currentMonth + ' ' + currentDate.getFullYear();

  }
  if ($('#attendance_filter').val() == 'tmonth') {
    var currentDate = new Date();
var firstDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

var firstDayOfMonth = firstDateOfMonth.getDate();
var currentDayOfMonth = currentDate.getDate();

var currentMonth = currentDate.toLocaleString('default', { month: 'short' });
var formattedDate = firstDayOfMonth + ' ' + currentMonth + ' - ' + currentDayOfMonth + ' ' + currentMonth;

var from = firstDayOfMonth + ' ' + currentMonth + ' ' + currentDate.getFullYear();
var too = currentDayOfMonth + ' ' + currentMonth + ' ' + currentDate.getFullYear();

  }
  if ($('#attendance_filter').val() == 'lmonth') {
    var currentDate = new Date();
var firstDateOfCurrentMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
var lastDateOfPreviousMonth = new Date(firstDateOfCurrentMonth.getTime() - 1);
var firstDateOfPreviousMonth = new Date(lastDateOfPreviousMonth.getFullYear(), lastDateOfPreviousMonth.getMonth(), 1);

var firstDayOfPreviousMonth = firstDateOfPreviousMonth.getDate();
var lastDayOfPreviousMonth = lastDateOfPreviousMonth.getDate();

var previousMonth = lastDateOfPreviousMonth.toLocaleString('default', { month: 'short' });
var formattedDate = firstDayOfPreviousMonth + ' ' + previousMonth + ' - ' + lastDayOfPreviousMonth + ' ' + previousMonth;

var from = firstDayOfPreviousMonth + ' ' + previousMonth + ' ' + lastDateOfPreviousMonth.getFullYear();
var too = lastDayOfPreviousMonth + ' ' + previousMonth + ' ' + lastDateOfPreviousMonth.getFullYear();

  }
  if ($('#attendance_filter').val() != 'custom' && $('#attendance_filter').val() != 'lmonth' && $('#attendance_filter').val() != 'tmonth' && $('#attendance_filter').val() != 'tweek' && $('#attendance_filter').val() != 'ystdy' && $('#attendance_filter').val() != 'today') {
  var date = $('#from').val();
  var parts = date.split(' ');
  var formattedDate;
  formattedDate = parts[0] + ' ' + parts[1];
  var from = $('#from').val();
  }
  $('#filterDate').html(formattedDate);

  var data = {
  "from": from,
  "too": too,
};
console.log(data);
showLoader();
$.ajax({
    url: '<?= base_url('attendance/get_filter_page') ?>', // Replace with your actual API URL
    type: 'POST', // Adjust the request method if needed
    data: data,
    success: function(response) {
      var data = JSON.parse(response);
      hideLoader();
      console.log(data);
      $('#present').html(data.present);
      $('#total_staff').html(data.total_staff);
      $('#late').html(data.late);
      $('#late_min').html(data.late_min);
      $('#absents').html(data.absent);
      $('#halfday').html(data.halfday);
      $('#leave').html(data.leaves);
      $('#leave_pending').html(data.leaves_pending);
      $('#leave_rejected').html(data.leaves_rejected);
      $('#leave_total').html(data.leaves_total);
      $('#bio_total').html(data.total_bio);
      $('#bio_pending').html(data.bio_pending);
      $('#bio_approved').html(data.bio_approved);
      $('#bio_rejected').html(data.bio_rejected);
    },
    error: function(xhr, status, error) {
      // Handle any errors that occur during the AJAX request
      console.log('Error:', error);
    }
  });
});


$(document).ready(function(){
  $(document).on('click', '.preabs', function() {
  $('#attendance_list').bootstrapTable('refresh');
      $('#preabs').val('');
    });

  $('#from').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
    isInvalidDate: function(date) {
        // Disable dates in the future
        return date.isAfter(moment()); // Make sure to include the moment.js library
    }
  });

  $('#too').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
    isInvalidDate: function(date) {
        // Disable dates in the future
        return date.isAfter(moment()); // Make sure to include the moment.js library
    }
  });
});
</script>

<script>
function updateDivContent() {
  var select = document.getElementById("attendance_filter");
  var div = document.getElementById("myDiv");
  var div2 = document.getElementById("myDiv2");
  if (select.value === "custom") {
    div.classList.remove("hidden");
    div2.classList.remove("hidden");
  } else {
    div.classList.add("hidden");
    div2.classList.add("hidden");
  }
}
</script>
</body>
</html>
