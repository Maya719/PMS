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
            <?=$this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves'?> 
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
                  <select class="form-control select2 leaves_filter_user" id="leaves_filter_user">
                    <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <select class="form-control select2 leaves_filter" id="leaves_filter">
                    <option value="all"><?=$this->lang->line('select_filter')?$this->lang->line('select_filter'):'All Time'?></option>
                    <option value="today"><?=$this->lang->line('select_filter')?$this->lang->line('select_filter'):'Today'?></option>
                    <option value="ystdy"><?=$this->lang->line('select_filter')?$this->lang->line('select_filter'):'Yesterday'?></option>
                    <option value="tweek"><?=$this->lang->line('select_filter')?$this->lang->line('select_filter'):'This Week'?></option>
                    <option value="tmonth"><?=$this->lang->line('select_filter')?$this->lang->line('select_filter'):'This Month'?></option>
                    <option value="lmonth"><?=$this->lang->line('select_filter')?$this->lang->line('select_filter'):'Last Month'?></option>
                    <option value="custom"><?=$this->lang->line('select_filter')?$this->lang->line('select_filter'):'Custom'?></option>
                  </select>
                </div>
                <div id="myDiv" class="form-group col-md-6" style="display:none">
                  <input type="text" name="from" id="from" class="form-control datepicker from" >
                </div>
                <div id="myDiv2" class="form-group col-md-6" style="display:none">
                  <input type="text" name="to" id="to" class="form-control datepicker to" >
                </div>
              <?php } ?>
            </div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='leaves_list'
                          data-toggle="table"
                          data-url="<?=base_url('leaves/get_leaves')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="true"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="false" data-show-columns="true"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="DESC"
                          data-mobile-responsive="true"
                          data-toolbar="#toolbar"
                          data-show-export="true" 
                          data-export-types="['json', 'csv', 'txt', 'sql', 'doc', 'excel', 'pdf']" 
                          data-maintain-selected="true"
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="sr_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('sr_no'):'#'?></th>
                              <?php if($this->ion_auth->is_admin()){ ?>
                              <th data-field="employee_id" data-sortable="false" data-visible="false"><?=$this->lang->line('employee_id')?$this->lang->line('employee_id'):'Emp ID'?></th>
                                <th data-field="user" data-sortable="false"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></th>
                              <?php } ?>
                              <th data-field="type" data-sortable="false"><?=$this->lang->line('type')?$this->lang->line('type'):'Leave Type'?></th>
                              <th data-field="starting_date_time" data-sortable="true"><?=$this->lang->line('starting_date_time')?$this->lang->line('starting_date_time'):'Start Date / Time'?></th>
                              <th data-field="ending_date_time" data-sortable="true"><?=$this->lang->line('ending_date_time')?$this->lang->line('ending_date_time'):'End Date / Time'?></th>
                              <th data-field="leave_duration" data-sortable="false"><?=$this->lang->line('leave_duration')?$this->lang->line('leave_duration'):'Leave Duration'?></th>
                              <th data-field="leave_reason" data-sortable="false" ><?=$this->lang->line('leave_reason')?$this->lang->line('leave_reason'):'Leave Reason'?></th>
                              <th data-field="paid" data-sortable="false"><?=$this->lang->line('paid')?$this->lang->line('paid'):'Paid / Unpaid'?></th>
                              <th data-field="status" data-sortable="false"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
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


<form action="<?=base_url('leaves/create')?>" method="POST" class="modal-part" id="modal-add-leaves-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'users'?></label>
      <select name="user_id" class="form-control select2">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  <?php } ?>
  
  <div class="form-group">
    <label><?=$this->lang->line('leave_days')?$this->lang->line('leave_days'):'Leave Days'?><span class="text-danger">*</span></label>
    <input type="number" pattern="[0-9]" name="leave_days" class="form-control" required="">
  </div>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
      <input type="text" name="starting_date" class="form-control datepicker" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
      <input type="text" name="ending_date" class="form-control datepicker" required="">
    </div>
  </span>

  <div class="form-group">
    <label><?=$this->lang->line('leave_reason')?$this->lang->line('leave_reason'):'Leave Reason'?><span class="text-danger">*</span></label>
    <textarea type="text" name="leave_reason" class="form-control" required=""></textarea>
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
  
  <div class="form-group">
    <label><?=$this->lang->line('leave_days')?$this->lang->line('leave_days'):'Leave Days'?><span class="text-danger">*</span></label>
    <input type="number" pattern="[0-9]" name="leave_days" id="leave_days" class="form-control" required="">
  </div>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
      <input type="text" name="starting_date" id="starting_date" class="form-control datepicker" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
      <input type="text" name="ending_date" id="ending_date" class="form-control datepicker" required="">
    </div>
  </span>

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
  function queryParams(p){
      return {
        "user_id": $('#leaves_filter_user').val(),
        "filter": $('#leaves_filter').val(),
        "from": $('#from').val(),
        "to": $('#to').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }

  $(document).on('change','.leaves_filter, .leaves_filter_user, .from, .to',function(){
    $('#leaves_list').bootstrapTable('refresh');
    hasAdjustedHeight = false;
    $('#leaves_list').on('load-success.bs.table', function () {
        if (!hasAdjustedHeight) {
            adjustTableHeight();
            hasAdjustedHeight = true;
        }
    });
    var select = document.getElementById("leaves_filter");
    var div = document.getElementById("myDiv");
    var div2 = document.getElementById("myDiv2");
    if (select.value !== "custom") {
      div.style.display = "none"; // Hide div
      div2.style.display = "none"; // Hide div2
      $('#from').val(''); // Set from value to null
      $('#to').val(''); // Set to value to null
    }else{
      div.style.display = "block"; // Show div
      div2.style.display = "block"; // Show div2
    }
  });

  $(document).ready(function(){

    $('#from').daterangepicker({
      locale: {format: date_format_js},
      singleDatePicker: true,
    });

    $('#to').daterangepicker({
      locale: {format: date_format_js},
      singleDatePicker: true,
    });
  });

  function getMonthName(monthIndex) {
  var months = [
    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
  ];
  return months[monthIndex];}
  
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