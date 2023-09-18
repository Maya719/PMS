<?php $this->load->view('includes/head'); ?>
<style>

.hidden{
    display: none;
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
            <?=$this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves'?> 
              <?php if (!$this->ion_auth->in_group(4)){ ?>
                <a href="#" id="modal-add-leaves" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <?php if($this->ion_auth->is_admin()){ ?>
                <div class="form-group col-md-6">
                  <select class="form-control select2 leaves_filter" id="leaves_filter_user">
                    <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>
              <?php } ?>
                <div class="form-group col-md-6">
                <select class="form-control select2" name="filter" id="leaves_filter" onchange="updateDivContent()">
                  <option value="today"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Today'?></option>
                  <option value="ystdy"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Yesterday'?></option>
                  <option value="tweek"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Week'?></option>
                  <option value="tmonth" selected><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Month'?></option>
                  <option value="lmonth"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Month'?></option>
                  <option value="custom"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Custom'?></option>
                </select>
              </div>
                <div id="myDiv" class="form-group col-md-6 hidden">
                  <input type="text" name="from" id="from" class="form-control datepicker">
                </div>
                <div id="myDiv2" class="form-group col-md-6 hidden">
                  <input type="text" name="too" id="too" class="form-control datepicker">
                </div>
              </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body" >
                        <div class="table-container"> 
                          <table class='table table-striped' id='leaves_list'
                            data-toggle="table"
                            data-url="<?=base_url('leaves/get_leaves')?>"
                            data-click-to-select="true"
                            data-side-pagination="server"
                            data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-search="true" data-show-columns="true"
                            data-show-refresh="false" data-trim-on-search="false"
                            data-sort-name="id" data-sort-order="desc"
                            data-mobile-responsive="true"
                            data-toolbar="" 
                            data-show-export="false"
                            data-maintain-selected="true"
                            data-export-types='["txt","excel"]'
                            data-export-options='{
                              "fileName": "leaves-list",
                              "ignoreColumn": ["state"] 
                            }'
                            data-query-params="queryParams">
                            <thead>
                              <tr>
                                <th data-field="sr_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('sr_no'):'#'?></th>
                                <?php if($this->ion_auth->is_admin()){ ?>
                                <th data-field="employee_id" data-sortable="true" data-visible="false"><?=$this->lang->line('employee_id')?$this->lang->line('employee_id'):'Emp ID'?></th>
                                  <th data-field="user" data-sortable="false"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Employee'?></th>
                                <?php } ?>
                                <th data-field="type" data-sortable="false"><?=$this->lang->line('type')?$this->lang->line('type'):'Leave Type'?></th>
                                <th data-field="starting_date_time" data-sortable="false"><?=$this->lang->line('starting_date_time')?$this->lang->line('starting_date_time'):'Start Date / Time'?></th>
                                <th data-field="ending_date_time" data-sortable="false"><?=$this->lang->line('ending_date_time')?$this->lang->line('ending_date_time'):'End Date / Time'?></th>
                                <th data-field="leave_duration" data-sortable="false"><?=$this->lang->line('leave_duration')?$this->lang->line('leave_duration'):'Leave Duration'?></th>
                                <th data-field="leave_reason" data-sortable="false"><?=$this->lang->line('leave_reason')?$this->lang->line('leave_reason'):'Leave Reason'?></th>
                                <th data-field="paid" data-sortable="false"><?=$this->lang->line('paid')?$this->lang->line('paid'):'Paid / Unpaid'?></th>
                                <th data-field="status" data-sortable="false"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
                                <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
                              </tr>
                            </thead>
                          </table>
                        </div>
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


<form action="<?=base_url('leaves/create')?>" method="POST" class="modal-part" id="modal-add-leaves-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Users'?></label>
      <select name="user_id_add" id="user_id_add" class="form-control select2">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  <?php } ?>
  
  <!--<div class="form-group">-->
  <!--    <label><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?></label>-->
  <!--    <select name="type_add" id="type_add" class="form-control select2">-->
  <!--      <option value="0"><?=$this->lang->line('casual_leave')?$this->lang->line('casual_leave'):'Casual Leave'?></option>-->
  <!--      <option value="1"><?=$this->lang->line('marriage_leave')?$this->lang->line('marriage_leave'):'Marriage Leave'?></option>-->
  <!--      <option value="2"><?=$this->lang->line('medical_leave')?$this->lang->line('medical_leave'):'Medical Leave'?></option>-->
  <!--      <option value="3"><?=$this->lang->line('maternity_leave')?$this->lang->line('maternity_leave'):'Maternity Leave'?></option>-->
  <!--    </select>-->
  <!--</div>-->
  
  <div class="form-group">
      <select class="form-control select2" name="type_add" id="type_add" >
        <option value=""><?=$this->lang->line('select_type')?$this->lang->line('select_type'):'Select Type'?></option>
        <?php foreach($leaves_type as $leaves){ ?>
          <option value="<?= $leaves['id'] ?>"><?= $leaves['name'] ?></option>
        <?php 
      }?>
      </select>
  </div>

  <?php if($this->ion_auth->is_admin()){ ?>
  <div class="form-group">
      <label><?=$this->lang->line('paid_unpaid')?$this->lang->line('paid_unpaid'):'Paid / Unpaid Leave'?></label>
      <select name="paid" class="form-control select2">
        <option value="0"><?=$this->lang->line('paid')?$this->lang->line('paid'):'Paid Leave'?></option>
        <option value="1"><?=$this->lang->line('unpaid')?$this->lang->line('unpaid'):'Unpaid Leave'?></option>
      </select>
  </div>
  <?php } ?>

    <div class="form-group form-check form-check-inline col-md-6 md-3">
      <input class="form-check-input" type="checkbox" id="half_day" name="half_day">
      <label class="form-check-label text-danger" for="half_day"><?=$this->lang->line('half_day')?$this->lang->line('half_day'):'Half Day'?></label>
    </div>

    <div class= "form-group form-check form-check-inline col-md-5 ">
      <input class="form-check-input" type="checkbox" id="short_leave" name="short_leave">
      <label class="form-check-label text-danger" for="short_leave"><?=$this->lang->line('short_leave')?$this->lang->line('short_leave'):'Short Leave'?></label>
    </div>

  <div id="date_fields">
      <div id="full_day_dates" class="row">
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
              <input type="text" id="starting_date_create" name="starting_date" class="form-control datepicker" required="">
          </div>
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
              <input type="text" id="ending_date_create" name="ending_date" class="form-control datepicker" required="" >
          </div>
      </div>
      <div id="half_day_date" class="row" style="display: none;">
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
              <input type="text" id="date_half" name="date_half" class="form-control datepicker" required="">
          </div>
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('time')?$this->lang->line('time'):'Time'?><span class="text-danger">*</span></label>
              <select name="half_day_period" class=" form-group form-control select2">
                  <option value="0">First Time</option>
                  <option value="1">Second Time</option>
              </select>
          </div>
      </div>
      <div id="short_leave_dates" class="row" style="display: none;">
            <div class="col-md-4 form-group">
                <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
                <input type="text" id="date" name="date" class="form-control datepicker" required="">
            </div>
              <div class="col-md-4 form-group">
                  <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
                  <input type="text" name="starting_time" id="starting_time_create" class="form-control timepicker" required="">
              </div>
              <div class="col-md-4 form-group">
                  <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
                  <input type="text" name="ending_time" id="ending_time_create" class="form-control timepicker" required="">
              </div>
      </div>
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('leave_reason')?$this->lang->line('leave_reason'):'Leave Reason'?><span class="text-danger">*</span></label>
    <textarea type="text" name="leave_reason" class="form-control" required=""></textarea>
  </div>

  <div id="leaves_count" class="row">
    <div class="col-md-4 form-group">
        <label><?=$this->lang->line('total_leaves')?$this->lang->line('total_leaves'):'Total Leaves'?><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('the_total_leaves_are_in_year_and_are_from_1st_Jan_to_31st_Dec_of_this_year')?$this->lang->line('the_total_leaves_are_in_year_and_are_from_1st_Jan_to_31st_Dec_of_this_year'):"The Total leaves are in year and are from 1st Jan to 31st Dec of this year."?>"></i></label>
        <input type="number" id="total_leaves" name="total_leaves" class="form-control" required="" readonly>
    </div>
    <div class="col-md-4 form-group">
        <label><?=$this->lang->line('consumed_leaves')?$this->lang->line('consumed_leaves'):'Consumed Leaves'?></label>
        <input type="number" id="consumed_leaves" name="consumed_leaves" class="form-control" required="" readonly>
    </div>
    <div class="col-md-4 form-group">
        <label><?=$this->lang->line('remaining_leaves')?$this->lang->line('remaining_leaves'):'Remaining Leaves'?></label>
        <input type="number" id="remaining_leaves" name="remaining_leaves" class="form-control" required="" readonly>
    </div>
  </div>

</form>

<form action="<?=base_url('leaves/edit')?>" method="POST" class="modal-part" id="modal-edit-leaves-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">

  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'users'?></label>
      <select name="user_id" id="user_id" class="form-control select2">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  <?php } ?>

  <!--<div class="form-group">-->
  <!--    <label><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?></label>-->
  <!--    <select name="type" id="type" class="form-control select2">-->
  <!--      <option value="0"><?=$this->lang->line('casual_leave')?$this->lang->line('casual_leave'):'Casual Leave'?></option>-->
  <!--      <option value="1"><?=$this->lang->line('marriage_leave')?$this->lang->line('marriage_leave'):'Marriage Leave'?></option>-->
  <!--      <option value="2"><?=$this->lang->line('medical_leave')?$this->lang->line('medical_leave'):'Medical Leave'?></option>-->
  <!--      <option value="3"><?=$this->lang->line('maternity_leave')?$this->lang->line('maternity_leave'):'Maternity Leave'?></option>-->
  <!--    </select>-->
  <!--</div>-->

    <div class="form-group">
      <select class="form-control select2" name="type" id="type" >
        <?php foreach($leaves_type as $leaves){ ?>
          <option value="<?= $leaves['id'] ?>"><?= $leaves['name'] ?></option>
        <?php 
      }?>
      </select>
  </div>
  
  <?php if($this->ion_auth->is_admin()){ ?>
  <div class="form-group">
      <label><?=$this->lang->line('paid_unpaid')?$this->lang->line('paid_unpaid'):'Paid / Unpaid Leave'?></label>
      <select name="paid" id="paid" class="form-control select2">
        <option value="0"><?=$this->lang->line('paid')?$this->lang->line('paid'):'Paid Leave'?></option>
        <option value="1"><?=$this->lang->line('unpaid')?$this->lang->line('unpaid'):'Unpaid Leave'?></option>
      </select>
  </div>
  <?php } ?>


  <input type="hidden" name="leave_duration" id="leave_duration" value="">

  <div class="form-group">
    <label><?=$this->lang->line('leave')?$this->lang->line('leave'):'Leave'?><span class="text-danger">*</span></label>
    <input type="text" name="leave" id="leave" class="form-control" required="" readonly></input>
  </div>

  <div id="date_fields">
      <div id="full_day_dates_edit" class="row" >
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
              <input type="text" id="starting_date" name="starting_date" class="form-control datepicker" required="">
          </div>
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
              <input type="text" id="ending_date" name="ending_date" class="form-control datepicker" required="">
          </div>
      </div>
      <div id="half_day_date_edit" class="row" style="display: none;">
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
              <input type="text" id="date_half" name="date_half" class="form-control datepicker" required="">
          </div>
          <div class="col-md-6 form-group">
              <label><?=$this->lang->line('time')?$this->lang->line('time'):'Time'?><span class="text-danger">*</span></label>
              <select name="half_day_period" id="half_day_period" class=" form-group form-control select2">
                  <option value="0">First Time</option>
                  <option value="1">Second Time</option>
              </select>
          </div>
      </div>
      <div id="short_leave_dates_edit" class="row" style="display: none;">
        <div class="col-md-4 form-group">
            <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
            <input type="text" id="date" name="date" class="form-control datepicker" required="">
        </div>
        <div class="col-md-4 form-group">
            <label><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?><span class="text-danger">*</span></label>
            <input type="text" id="starting_time" name="starting_time" class="form-control timepicker" required="">
        </div>
        <div class="col-md-4 form-group">
            <label><?=$this->lang->line('ending_time')?$this->lang->line('ending_time'):'Ending Time'?><span class="text-danger">*</span></label>
            <input type="text" id="ending_time" name="ending_time" class="form-control timepicker" required="">
        </div>
      </div>
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('leave_reason')?$this->lang->line('leave_reason'):'Leave Reason'?><span class="text-danger">*</span></label>
    <textarea type="text" name="leave_reason" id="leave_reason" class="form-control" required=""></textarea>
  </div>


  
  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></label>
      <select name="status" id="status" class="form-control select2">
        <option value=""><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?></option>
        <option value="0"><?=$this->lang->line('pending')?htmlspecialchars($this->lang->line('pending')):'Pending'?></option>
        <option value="1"><?=$this->lang->line('approved')?htmlspecialchars($this->lang->line('approved')):'Approved'?></option>
        <option value="2"><?=$this->lang->line('rejected')?htmlspecialchars($this->lang->line('rejected')):'Rejected'?></option>
      </select>
    </div>
  <?php } ?>
  
</form>

<div id="modal-edit-leaves"></div>

<?php $this->load->view('includes/js'); ?>

<script>
  var hasAdjustedHeight = false;
  function queryParams(p) {
    return {
      "user_id": $('#leaves_filter_user').val() || $('.team-member.active').data('user-id'),
      "filter": $('#leaves_filter').val(),
      "from": $('#from').val(),
      "too": $('#too').val(),
      "limit": p.limit,
      "sort": p.sort,
      "order": p.order,
      "offset": p.offset,
      "search": p.search
    };
  }

  $(document).ready(function() {
    // Event handler for leaves_filter_user change
    $('#leaves_filter_user, #leaves_filter, #from, #too').on('change', function() {
      var hasAdjustedHeight = false;
      $('#leaves_list').on('load-success.bs.table', function () {
          if (!hasAdjustedHeight) {
              adjustTableHeight();
              hasAdjustedHeight = true;
          }
      });
      $('.team-member').removeClass('active');
      $('#leaves_list').bootstrapTable('refresh');
      console.log($('#from').val());
      console.log($('#too').val());
      console.log($('#leaves_filter').val());
    });

    // Event handler for clicking on team member names
    $(document).on('click', '.team-member', function() {
      var userId = $(this).data('user-id');
      $('.team-member').removeClass('active');
      $(this).addClass('active');
      $('#leaves_filter_user').val(userId).trigger('change');
    });
  });

  $(document).ready(function() {
    $('#from').daterangepicker({
      locale: { format: date_format_js },
      singleDatePicker: true,
      maxDate: moment().startOf('day') // Set minimum date to today
    });
    $('#from').on('change', function() {
      var from = $('#from').val();
      $('#too').daterangepicker({
        locale: { format: date_format_js },
        singleDatePicker: true,
        minDate: moment(from, date_format_js).toDate() // Set the minimum date to today
      });
    });
    var starting_date_create = $('#starting_date_create').val();
      $('#ending_date_create').daterangepicker({
        locale: { format: date_format_js },
        singleDatePicker: true,
        minDate: moment(starting_date_create, date_format_js).toDate() // Set the minimum date to today
      });

    $('#starting_date_create').on('change', function() {
      var starting_date_create = $('#starting_date_create').val();
      $('#ending_date_create').daterangepicker({
        locale: { format: date_format_js },
        singleDatePicker: true,
        minDate: moment(starting_date_create, date_format_js).toDate() // Set the minimum date to today
      });
    });

    var starting_date_create = $('#starting_date').val();
      $('#ending_date').daterangepicker({
        locale: { format: date_format_js },
        singleDatePicker: true,
        minDate: moment(starting_date_create, date_format_js).toDate() // Set the minimum date to today
      });

    $('#starting_date').on('change', function() {
      var starting_date_create = $('#starting_date').val();
      $('#ending_date').daterangepicker({
        locale: { format: date_format_js },
        singleDatePicker: true,
        minDate: moment(starting_date_create, date_format_js).toDate() // Set the minimum date to today
      });
    });

    $('#half_day').change(function() {
        if ($(this).is(':checked')) {
            $('#full_day_dates').hide();
            $('#short_leave').prop('checked', false);
            $('#short_leave_dates').hide();
            $('#half_day_date').show();
        } else {
            $('#full_day_dates').show();
            $('#half_day_date').hide();
        }
    });

    $('#short_leave').change(function() {
        if ($(this).is(':checked')) {
            $('#full_day_dates').hide();
            $('#half_day').prop('checked', false);
            $('#half_day_date').hide();
            $('#short_leave_dates').show();
        } else {
            $('#full_day_dates').show();
            $('#short_leave_dates').hide();
        }
    });

    $('select[name="user_id_add"]').on('change', function() {
      updateLeaveCounts();
    });

    // Event listener for type select
    $('select[name="type_add"]').on('change', function() {
      updateLeaveCounts();
    });

    $('.btn-create').on('click', function() {
      // Call the updateLeaveCounts function after the button is clicked and the form appears
      updateLeaveCounts();
    });
  
    function updateLeaveCounts() {

      var type = $('select[name="type_add"]').val();
      var user_id = $('select[name="user_id_add"]').val();

      $.ajax({
        url: '<?= base_url('leaves/get_leaves_count') ?>',
        method: 'POST', // or 'POST' depending on your server-side implementation
        dataType: 'json',
        data: {
          user_id: user_id, // Only if user selection is available in the form
          type: type
        },
        success: function(response) {
          // Retrieve the calculated values from the JSON response
          var totalLeaves = response.total_leaves;
          var consumedLeaves = response.consumed_leaves;
          var remainingLeaves = response.remaining_leaves;
          var query = response.query;
          // Update the input fields in the form
          
          $('#total_leaves').val(totalLeaves);
          $('#consumed_leaves').val(consumedLeaves);
          $('#remaining_leaves').val(remainingLeaves);
        },
      });
    }
      
    // Call the updateLeaveCounts function initially to populate the initial values
    updateLeaveCounts();

  });
  
  function updateDivContent() {
    var select = document.getElementById("leaves_filter");
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
  
  $(document).ready(function () {
      $('#leaves_list').on('load-success.bs.table', function () {
          if (!hasAdjustedHeight) {
              adjustTableHeight();
              hasAdjustedHeight = true;
          }
      });
  });

  function adjustTableHeight() {
      var options = $('#leaves_list').bootstrapTable('getOptions');
      const rowCount = $('#leaves_list tbody tr').length;
      console.log(rowCount);
      const maxVisibleRows = 4; // Adjust this value as needed

      if (rowCount <= maxVisibleRows) {
          options.height = 'auto'; // Set auto height for fewer rows
      } else {
          options.height = 700; // Set fixed height for more rows
      }

      $('#leaves_list').bootstrapTable('refreshOptions', options);
  }


</script>

</body>
</html>