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
            <?=$this->lang->line('biometric_missing')?$this->lang->line('biometric_missing'):'Biometric Request'?> 
              <?php if (!$this->ion_auth->in_group(4)){ ?>
                <a href="#" id="modal-add-biometric" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('biometric_missing')?$this->lang->line('biometric_missing'):'Biometric Request'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <?php if($this->ion_auth->is_admin()){ ?>
                <div class="form-group col-md-6">
                  <select class="form-control select2 biometric_missing_filter" id="biometric_missing_filter_user">
                    <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>
              <?php } ?>
              <div class="form-group col-md-6">
                <select class="form-control select2" name="filter" id="biometric_filter" onchange="updateDivContent()">
                  <option value="today"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Today'?></option>
                  <option value="ystdy"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Yesterday'?></option>
                  <option value="tweek"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Week'?></option>
                  <option value="tmonth" selected><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Month'?></option>
                  <option value="lmonth"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Month'?></option>
                  <option value="custom"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Custom'?></option>
                </select>
              </div>
            </div>
            <div class="row">
              <div id="myDiv" class="form-group col-md-6 hidden">
                <input type="text" name="from" id="from" class="form-control datepicker">
              </div>
              <div id="myDiv2" class="form-group col-md-6 hidden">
                <input type="text" name="too" id="too" class="form-control datepicker">
              </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='biometric_missing_list'
                          data-toggle="table"
                          data-url="<?=base_url('biometric_missing/get_biometric')?>"
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
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "biometric-missing-list",
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
                              <th data-field="date" data-sortable="true"><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?></th>
                              <th data-field="time" data-sortable="true"><?=$this->lang->line('time')?$this->lang->line('time'):'Time'?></th>
                              <th data-field="type" data-sortable="false"><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?></th>
                              <th data-field="reason" data-sortable="false" ><?=$this->lang->line('missing_reason')?$this->lang->line('missing_reason'):'Missing Reason'?></th>
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
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>


<form action="<?=base_url('biometric_missing/create')?>" method="POST" class="modal-part" id="modal-add-biometric-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'users'?></label>
      <select name="user_id_add" id="user_id_add" class="form-control select2">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } }?>
      </select>
    </div>
  <?php } ?>
  
    <div class="form-group">
      <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
      <input type="text" name="date" class="form-control datepicker" required="">
    </div>

    <div class="form-group form-check form-check-inline col-md-6 md-3">
      <input class="form-check-input" type="checkbox" id="check_in_create" name="check_in" checked>
      <label class="form-check-label text-danger" for="check_in"><?=$this->lang->line('check_in')?$this->lang->line('check_in'):'Check In'?></label>
    </div>

    <div class= "form-group form-check form-check-inline col-md-5 ">
      <input class="form-check-input" type="checkbox" id="check_out_create" name="check_out">
      <label class="form-check-label text-danger" for="check_out"><?=$this->lang->line('check_out')?$this->lang->line('check_out'):'Check Out'?></label>
    </div>

    <div class="form-group">
      <label><?=$this->lang->line('time')?$this->lang->line('time'):'Time'?><span class="text-danger">*</span></label>
      <input type="text" name="time" id="time_field" class="form-control timepicker" required="">
    </div>

    <div class="form-group">
      <label><?=$this->lang->line('reason')?$this->lang->line('reason'):'Missing Reason'?><span class="text-danger">*</span></label>
      <textarea type="text" name="reason" class="form-control" required=""></textarea>
    </div>

</form>

<form action="<?=base_url('biometric_missing/edit')?>" method="POST" class="modal-part" id="modal-edit-biometric-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" >

  <input type="hidden" name="employee_id" id="employee_id" >

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
    <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
    <input type="text" name="date" id="date" class="form-control datepicker" required="">
  </div>
  
  <div class="form-group form-check form-check-inline col-md-6 md-3">
    <input class="form-check-input" type="checkbox" id="check_in" name="check_in" >
    <label class="form-check-label text-danger" for="check_in"><?=$this->lang->line('check_in')?$this->lang->line('check_in'):'Check In'?></label>
  </div>

  <div class= "form-group form-check form-check-inline col-md-5 ">
    <input class="form-check-input" type="checkbox" id="check_out" name="check_out" >
    <label class="form-check-label text-danger" for="check_out"><?=$this->lang->line('check_out')?$this->lang->line('check_out'):'Check Out'?></label>
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('time')?$this->lang->line('time'):'Time'?><span class="text-danger">*</span></label>
    <input type="text" name="time" id="time" class="form-control timepicker" required="">
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('reason')?$this->lang->line('reason'):'Missing Reason'?><span class="text-danger">*</span></label>
    <textarea type="text" name="reason" id="reason" class="form-control" required=""></textarea>
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

<div id="modal-edit-biometric"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  var hasAdjustedHeight = false;
  function queryParams(p){
      return {
        "user_id": $('#biometric_missing_filter_user').val(),
        "filter": $('#biometric_filter').val(),
        "from": $('#from').val(),
        "too": $('#too').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }

  $(document).on('change','.biometric_missing_filter, #biometric_filter , #from, #too',function(){
    $('#biometric_missing_list').bootstrapTable('refresh');var hasAdjustedHeight = false;
      $('#biometric_missing_list').on('load-success.bs.table', function () {
          if (!hasAdjustedHeight) {
              adjustTableHeight();
              hasAdjustedHeight = true;
          }
      });
  });

  $(document).ready(function() {
    $('#from').daterangepicker({
      locale: { format: date_format_js },
      singleDatePicker: true,
      maxDate: moment().startOf('day') // Set minimum date to today
    });

    $('#too').daterangepicker({
      locale: {format: date_format_js},
      singleDatePicker: true,
      maxDate: moment().startOf('day') // Set minimum date to today
    });

  var check_in = ''; // Initialize check_in variable
  var check_out = ''; // Initialize check_out variable

  $('select[name="user_id_add"]').on('change', function() {
    updateShiftTime();
  });

  $('.btn-create').on('click', function() {
    // Call the updateShiftTime function after the button is clicked and the form appears
    updateShiftTime();
  });

  function updateShiftTime() {
    var user_id = $('select[name="user_id_add"]').val();

    $.ajax({
      url: '<?= base_url('biometric_missing/get_shift_time') ?>',
      method: 'POST',
      dataType: 'json',
      data: {
        user_id: user_id
      },
      success: function(response) {
        // Retrieve the calculated values from the JSON response
        check_in = moment(response.check_in, 'HH:mm:ss').format(time_format_js);
        check_out = moment(response.check_out, 'HH:mm:ss').format(time_format_js);

        // Update the input fields in the form
        updateCheckboxesAndTimeField();
      },
    });
  }

  $('#check_in_create').change(function() {
    if ($(this).is(':checked')) {
      $('#check_out_create').prop('checked', false);
      updateCheckboxesAndTimeField();
    } else {
      // Prevent unchecking both checkboxes
      $(this).prop('checked', true);
    }
  });

  $('#check_out_create').change(function() {
    if ($(this).is(':checked')) {
      $('#check_in_create').prop('checked', false);
      updateCheckboxesAndTimeField();
    } else {
      // Prevent unchecking both checkboxes
      $(this).prop('checked', true);
    }
  });

  // Ensure at least one checkbox is always selected
  $('#check_in_create, #check_out_create').change(function() {
    if (!$('#check_in_create').is(':checked') && !$('#check_out_create').is(':checked')) {
      $('#check_in_create').prop('checked', true);
    }
  });

  // Function to update the checkboxes and time field
  function updateCheckboxesAndTimeField() {
    if ($('#check_in_create').is(':checked')) {
      $('#check_out_create').prop('checked', false);
      $('#time_field').val(check_in);
    } else if ($('#check_out_create').is(':checked')) {
      $('#check_in_create').prop('checked', false);
      $('#time_field').val(check_out);
    }
  }

  // Trigger the change event on page load to set the default time
  $('#check_in_create').change();

  updateShiftTime();
});


$(document).ready(function() {
  $('#check_in').change(function() {
    if ($(this).is(':checked')) {
      $('#check_out').prop('checked', false);
    } else {
      // Prevent unchecking both checkboxes
      $(this).prop('checked', true);
    }
  });

  $('#check_out').change(function() {
    if ($(this).is(':checked')) {
      $('#check_in').prop('checked', false);
    } else {
      // Prevent unchecking both checkboxes
      $(this).prop('checked', true);
    }
  });

  // Ensure at least one checkbox is always selected
  $('#check_in, #check_out').change(function() {
    if (!$('#check_in').is(':checked') && !$('#check_out').is(':checked')) {
      $('#check_in').prop('checked', true);
    }
  });

});
  
  document.addEventListener("DOMContentLoaded", function() {
    var createButton = document.querySelector("button[type='submit']");
    var errorMessage = document.querySelector('.alert.alert-danger');

    if (createButton && errorMessage) {
      createButton.addEventListener("click", function() {
        errorMessage.textContent = "<?php echo $data['message']; ?>";
        errorMessage.style.display = "block";
        console.log(<?php echo json_encode($data['sql']); ?>);
      });
    }
  });

  function updateDivContent() {
    var select = document.getElementById("biometric_filter");
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
      $('#biometric_missing_list').on('load-success.bs.table', function () {
          if (!hasAdjustedHeight) {
              adjustTableHeight();
              hasAdjustedHeight = true;
          }
      });
  });

  function adjustTableHeight() {
      var options = $('#biometric_missing_list').bootstrapTable('getOptions');
      const rowCount = $('#biometric_missing_list tbody tr').length;
      const maxVisibleRows = 4; // Adjust this value as needed

      if (rowCount <= maxVisibleRows) {
          options.height = 'auto'; // Set auto height for fewer rows
      } else {
          options.height = 700; // Set fixed height for more rows
      }

      $('#biometric_missing_list').bootstrapTable('refreshOptions', options);
  }
</script>
</body>
</html>