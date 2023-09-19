<?php $this->load->view('includes/head'); ?>
<style>
  .hidden{
    display: none;
  }
  .name-container {
    max-height: 25px; /* Adjust the height as needed */
    overflow: hidden;
    position: relative;
}

.name-content {
    display: inline-block;
}

.show-more-btn,
.show-less-btn {
    cursor: pointer;
    color: blue;
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
            <?=$this->lang->line('holiday')?$this->lang->line('holiday'):'Plan Holiday'?> 
              <?php if (!$this->ion_auth->in_group(4) && ($this->ion_auth->is_admin() || permissions('plan_holiday_create'))){ ?>
                <a href="#" id="modal-add-leaves" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('holiday')?$this->lang->line('holiday'):'Plan Holiday'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='leaves_list'
                          data-toggle="table"
                          data-url="<?=base_url('holiday/get_holiday')?>"
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
                            "fileName": "leaves-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="s_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('s_no'):'#'?></th>
                              <th data-field="type" data-sortable="true"><?=$this->lang->line('type')?$this->lang->line('type'):'Holiday Type'?></th>
                              <th data-field="starting_date" data-sortable="true"><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?></th>
                              <th data-field="ending_date" data-sortable="true"><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date / Time'?></th>
                              <th data-field="apply_on" data-sortable="false" data-formatter="teamMembersFormatter">
                                  <?=$this->lang->line('apply_on')?htmlspecialchars($this->lang->line('apply_on')):'Apply On'?>
                              </th>
                              <th data-field="remarks" data-sortable="true"><?=$this->lang->line('leave_reason')?$this->lang->line('leave_reason'):'Remarks'?></th>
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


<form action="<?=base_url('holiday/create')?>" method="POST" class="modal-part" id="modal-add-leaves-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
    <div class="form-group">
        <label><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?></label>
        <select name="type_add" id="type_add" class="form-control select2">
            <option value="0"><?=$this->lang->line('national_day')?$this->lang->line('national_day'):'National Day'?></option>
            <option value="1"><?=$this->lang->line('rest_day')?$this->lang->line('rest_day'):'Rest Day'?></option>
            <option value="4"><?=$this->lang->line('religious_day')?$this->lang->line('religious_day'):'Religious Day'?></option>
            <option value="3"><?=$this->lang->line('unplanned')?$this->lang->line('unplanned'):'Unplanned'?></option>
        </select>
    </div>
    <div class="row" id="dates">
        <div class="col-md-6 form-group" >
            <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
            <input type="text" id="starting_date_create" name="starting_date" class="form-control datepicker" required="">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
            <input type="text" id="ending_date_create" name="ending_date" class="form-control datepicker" required="" >
        </div>
    </div>
  <div class="form-group">
    <label><?=$this->lang->line('applyfor')?$this->lang->line('applyfor'):'Apply for'?></label>
    <select name="applyforcreate" id="apply2" class="form-control select2">
          <option value="0"><?=$this->lang->line('all')?$this->lang->line('all'):'All Users'?></option>
          <option value="1"><?=$this->lang->line('Department')?$this->lang->line('Department'):'Department'?></option>
          <option value="2"><?=$this->lang->line('users')?$this->lang->line('users'):'Selected User/s'?></option>
    </select>
  </div>

  <div id="department2" class="form-group hidden">
        <label><?=$this->lang->line('department')?$this->lang->line('type'):'Select Department/s'?></label>
        <select name="department[]" class="form-control select2" multiple>
            <?php foreach($departments as $department){ ?>
            <option value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
            <?php } ?>
        </select>
  </div>
    <div id="users2" class="form-group hidden">
    <label><?=$this->lang->line('department')?$this->lang->line('type'):'Select User/s'?></label>
        <select name="users[]" class="form-control select2" multiple>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
        </select>
  </div>
  <div class="form-group">
    <label><?=$this->lang->line('remarks')?$this->lang->line('remarks'):'Remarks'?><span class="text-danger">*</span></label>
    <textarea type="text" name="remarks" class="form-control" required=""></textarea>
  </div>
</form>
<form action="<?=base_url('holiday/edit')?>" method="POST" class="modal-part" id="modal-edit-holiday-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">

  <div class="form-group">
      <label><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?></label>
      <select name="type" id="type" class="form-control select2">
            <option value="0"><?=$this->lang->line('national_day')?$this->lang->line('national_day'):'National Day'?></option>
            <option value="1"><?=$this->lang->line('rest_day')?$this->lang->line('rest_day'):'Rest Day'?></option>
            <option value="4"><?=$this->lang->line('religious_day')?$this->lang->line('religious_day'):'Religious Day'?></option>
            <option value="3"><?=$this->lang->line('unplanned')?$this->lang->line('unplanned'):'Unplanned'?></option>
      </select>
  </div>
  

  <input type="hidden" name="leave_duration" id="leave_duration" value="">
    <div class="row" id="dates2">
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?><span class="text-danger">*</span></label>
            <input type="text" id="starting_date2" name="starting_date" class="form-control datepicker" required="">
        </div>
        <div class="col-md-6 form-group">
            <label><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?><span class="text-danger">*</span></label>
            <input type="text" id="ending_date" name="ending_date" class="form-control datepicker" required="">
        </div>
    </div>
    <div class="form-group">
      <label><?=$this->lang->line('applyfor')?$this->lang->line('applyfor'):'Apply for'?></label>
      <select name="applyforedit" id="apply4" class="form-control select2">
          <option value="0"><?=$this->lang->line('all')?$this->lang->line('all'):'All Users'?></option>
          <option value="1"><?=$this->lang->line('Department')?$this->lang->line('Department'):'Department'?></option>
          <option value="2"><?=$this->lang->line('users')?$this->lang->line('users'):'Selected User/s'?></option>
      </select>
    </div>
    <div id="department" class="form-group hidden">
        <label><?=$this->lang->line('department')?$this->lang->line('type'):'Select Department/s'?></label>
        <select name="department[]" id="department3" class="form-control select2" multiple>
            <?php foreach($departments as $department){ ?>
            <option value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
            <?php } ?>
        </select>
  </div>
    <div id="users" class="form-group hidden">
    <label><?=$this->lang->line('department')?$this->lang->line('type'):'Select User/s'?></label>
        <select name="users[]" id="users3" class="form-control select2" multiple>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
        </select>
  </div>
    
  <div class="form-group">
    <label><?=$this->lang->line('remarks')?$this->lang->line('remarks'):'Remarks'?><span class="text-danger">*</span></label>
    <textarea type="text" name="remarks" id="remarks" class="form-control" required=""></textarea>
  </div>

</form>

<div id="modal-edit-holiday"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  var hasAdjustedHeight = false;
  function queryParams(p) {
    return {
      "user_id": $('#leaves_filter_user').val() || $('.team-member.active').data('user-id'),
      "limit": p.limit,
      "sort": p.sort,
      "order": p.order,
      "offset": p.offset,
      "search": p.search
    };
  }
  $(document).ready(function() {
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
});
  
</script>
<script>
  $(document).ready(function() {
  // Target the select element by its ID or class (replace "selectElement" with your actual selector)
  $('#apply').change(function() {
    var selectedValue = $(this).val();
    if (selectedValue == '1') {
      $('#department').removeClass('hidden');
    } else {
      // Add the "hidden" class back if the selected value is not 'option1'
      $('#department').addClass('hidden');
    }
    if (selectedValue == '2') {
      $('#users').removeClass('hidden');
    } else {
      // Add the "hidden" class back if the selected value is not 'option1'
      $('#users').addClass('hidden');
    }
  });
});
</script>
<script>
  $(document).ready(function() {
  // Target the select element by its ID or class (replace "selectElement" with your actual selector)
  $('#apply2').change(function() {
    var selectedValue = $(this).val();
    if (selectedValue == '1') {
      $('#department2').removeClass('hidden');
    } else {
      // Add the "hidden" class back if the selected value is not 'option1'
      $('#department2').addClass('hidden');
    }
    if (selectedValue == '2') {
      $('#users2').removeClass('hidden');
    } else {
      // Add the "hidden" class back if the selected value is not 'option1'
      $('#users2').addClass('hidden');
    }
  });
});
</script>
<script>
  $(document).ready(function() {
  // Target the select element by its ID or class (replace "selectElement" with your actual selector)
  $('#apply4').change(function() {
    var selectedValue = $(this).val();
    if (selectedValue == '1') {
      $('#department').removeClass('hidden');
    } else {
      // Add the "hidden" class back if the selected value is not 'option1'
      $('#department').addClass('hidden');
    }
    if (selectedValue == '2') {
      $('#users').removeClass('hidden');
    } else {
      // Add the "hidden" class back if the selected value is not 'option1'
      $('#users').addClass('hidden');
    }
  });
});
$(document).ready(function () {
    var $nameContainer = $(".name-container");
    var $nameContent = $(".name-content");
    var $showMoreBtn = $(".show-more-btn");
    var $showLessBtn = $(".show-less-btn");
    
    $showMoreBtn.on("click", function () {
        $nameContainer.css("max-height", "none");
        $showMoreBtn.hide();
        $showLessBtn.show();
    });
    
    $showLessBtn.on("click", function () {
        $nameContainer.css("max-height", "25px"); // Adjust the height
        $showMoreBtn.show();
        $showLessBtn.hide();
    });
});



    document.addEventListener('DOMContentLoaded', function() {
        const showMoreButtons = document.querySelectorAll('.show-more-button');

        showMoreButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const nameContainer = button.closest('.name-container');
                const truncatedSpan = nameContainer.querySelector('.truncated');
                const fullSpan = nameContainer.querySelector('.full');

                truncatedSpan.style.display = 'none';
                fullSpan.style.display = 'inline';
                button.style.display = 'none';
            });
        });
    });

function teamMembersFormatter(value, row) {
        const teamMembers = value.split('<br>');
        const maxVisibleTeamMembers = 10;

        if (teamMembers.length > maxVisibleTeamMembers) {
            const visibleMembers = teamMembers.slice(0, maxVisibleTeamMembers).join('<br>');
            const hiddenMembers = teamMembers.slice(maxVisibleTeamMembers).join('<br>');
            return `
                <div class="visible-members">${visibleMembers}</div>
                <div class="hidden-members" style="display: none">${hiddenMembers}</div>
                <a href="#" class="see-more-link">See More...</a>
            `;
        } else {
            return value;
        }
    }

    $(document).ready(function() {
        $(document).on('click', '.see-more-link', function(e) {
            e.preventDefault();
            console.log('See More link clicked');
            const $this = $(this);
            const $cell = $this.closest('td');
            $cell.find('.hidden-members').slideToggle();
            $this.text($this.text() === 'See More...' ? 'See Less' : 'See More...');
        });
    });

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
