<?php $this->load->view('includes/head'); ?>
<style>
    table th,
    table td {
        width: 50px;
        padding: 5px;
        border: 0.5px solid black;
    }
 
    .GFG {
        color: green;
    }
 
    .OK {
        font-size: 18px;
    }
    .draggable {
  position: absolute;
  cursor: move;
}
</style>
<link rel="stylesheet" href="style.css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"/>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../node_modules/table-dragger/dist/table-dragger.min.js"></script>
<link rel="stylesheet" type="text/css" href="dragtable.css" />
		
		
</head>
<body>
    <?php
    $id = $_GET['id'];
    ?>
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
              <?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Employees'?> 
              <?php if(my_plan_features('users')){ if ($this->ion_auth->is_admin() || permissions('user_create')){ ?> 
                <a href="#" id="modal-add-user" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } } ?> 
             <!-- <div class="btn-group">
                <a href="#" class="btn btn-sm btn-primary">
                      <?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view').' ('.base_url('user-list').')'):'List View'?>
                  </a>
                  <a href="<?=base_url('projects')?>" class="btn btn-sm"><?=$this->lang->line('kanban_view')?htmlspecialchars($this->lang->line('kanban_view')):'Kanban View'?></a>
              </div> -->

            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Employees'?></div>
            </div>
          </div>
          
<!--
            <div class="section-body">
            <div class="row">
              <?php
                if(isset($system_users) && !empty($system_users)){
                foreach ($system_users as $system_user) {
              ?>
              <div class="col-md-6">
                <div class="card card-primary profile-widget">
                  <div class="profile-widget-header mb-0">  
                    <span class="avatar-item mb-0"> 
                    <?php
                      if(isset($system_user['profile']) && !empty($system_user['profile'])){
                    ?>       
                      <img alt="image" src="<?=htmlspecialchars($system_user['profile'])?>" class="rounded-circle profile-widget-picture">
                    <?php }else{ ?>
                      <figure class="user-avatar avatar avatar-xl rounded-circle profile-widget-picture" data-initial="<?=htmlspecialchars($system_user['short_name'])?>"></figure>
                    <?php } ?>
                    <?php if ($this->ion_auth->is_admin() || permissions('user_view_all') || $this->ion_auth->in_group(3)){ ?>
                      <a href="#" data-edit="<?=htmlspecialchars($system_user['id'])?>" class="avatar-badge modal-edit-user text-white" title="Edit" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a>
                    <?php } ?>
                    </span> 
                    <div class="profile-widget-items">
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></div>
                        <div class="profile-widget-item-value"><span class="badge badge-secondary"><?=htmlspecialchars($system_user['projects_count'])?></span></div>
                      </div>
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></div>
                        <div class="profile-widget-item-value"><span class="badge badge-secondary"><?=htmlspecialchars($system_user['tasks_count'])?></span></div>
                      </div>
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></div>
                        <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['active'])==1?'<span class="badge badge-success">'.($this->lang->line('active')?$this->lang->line('active'):'Active').'</span>':'<span class="badge badge-danger">'.($this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive').'</span>'?></div>
                      </div>
                    </div>
                  </div>
                <div class="profile-widget mt-0">
                    <div class="profile-widget-header mb-0">
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label"><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></div>
                          <div class="profile-widget-item-value mt-1">
                            <?=htmlspecialchars($system_user['first_name'])?> <?=htmlspecialchars($system_user['last_name'])?>
                          </div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label"><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?></div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['email'])?></div>
                        </div>
                      </div>
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label"><?=$this->lang->line('mobile')?$this->lang->line('mobile'):'Mobile'?></div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['phone'])?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label"><?=$this->lang->line('role')?$this->lang->line('role'):'Role'?></div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['role'])?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                } }
              ?>-->
              
              <!--List Group View-->
<!--<div class="list-group">
  <?php
  if(isset($system_users) && !empty($system_users)){
    foreach ($system_users as $system_user) {
  ?>
  <div class="list-group-item">
    <div class="d-flex w-100 justify-content-between align-items-center">
      <h5 class="mb-1"><?= htmlspecialchars($system_user['first_name']) . ' ' . htmlspecialchars($system_user['last_name']) ?></h5>
      <small class="badge <?= htmlspecialchars($system_user['active']) == 1 ? 'badge-success' : 'badge-danger' ?>"><?= htmlspecialchars($system_user['active']) == 1 ? ($this->lang->line('active') ? $this->lang->line('active') : 'Active') : ($this->lang->line('deactive') ? $this->lang->line('deactive') : 'Deactive') ?></small>
    </div>
    <p class="mb-1"><?= htmlspecialchars($system_user['email']) ?></p>
    <p class="mb-1"><?= htmlspecialchars($system_user['phone']) ?></p>
    <p class="mb-1"><?= htmlspecialchars($system_user['role']) ?></p>
    <div class="d-flex w-100 justify-content-end">
      <?php if ($this->ion_auth->is_admin() || permissions('user_view_all') || $this->ion_auth->in_group(3)) { ?>
        <a href="#" data-edit="<?= htmlspecialchars($system_user['id']) ?>" class="badge badge-primary modal-edit-user"><?= ($this->lang->line('edit') ? $this->lang->line('edit') : 'Edit') ?></a>
      <?php } ?>
      <span class="badge badge-secondary ml-1"><?= htmlspecialchars($system_user['projects_count']) . ' ' . ($this->lang->line('projects') ? $this->lang->line('projects') : 'Projects') ?></span>
      <span class="badge badge-secondary ml-1"><?= htmlspecialchars($system_user['tasks_count']) . ' ' . ($this->lang->line('tasks') ? $this->lang->line('tasks') : 'Tasks') ?></span>
    </div>
  </div>
  <?php
    }
  }
  ?>
</div>
<br>-->
<!--End of List Group View -->



<!-- table Format-->
<?php 
$user_id = $_GET['user'];
// use $user_id to query and display the data for the user
?>
<div class="row">
  <div class="form-group col-md-6">
      <select class="form-control select2" id="active_users" onchange="refreshTable()">
        <option value="1"><?=$this->lang->line('select_active')?$this->lang->line('select_active'):'Active'?></option>
        <option value="2"><?=$this->lang->line('select_active')?$this->lang->line('select_active'):'Inactive'?></option>
        <option value="3"><?=$this->lang->line('select_all')?$this->lang->line('select_all'):'Select All'?></option>
      </select>
  </div>
  <div class="form-group col-md-6">
      <select class="form-control select2" id="department_users" onchange="refreshTable()">
        <option value=""><?=$this->lang->line('select_department')?$this->lang->line('select_department'):'Select Department'?></option>
        <?php foreach($departments as $department){ ?>
          <option value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
        <?php 
      }?>
      </select>
  </div>
</div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="GFG" class="table table-striped table-bordered sortable" id="user-list"
              data-toggle="table"
              data-toolbar="#toolbar"
              data-url="<?=base_url('users/get_active_inactive')?>"
              data-search="true"
              data-show-columns="true"
              data-pagination="true"
              data-filter-control="true"
              data-filter-show-clear="true"
              data-mobile-responsive="true"
              data-minimum-count-columns="2"
              data-page-size="10"
              data-page-list="[10, 25, 50, 100]"
              data-row-style="rowStyle"
              data-query-params="queryParams">
              <thead >
                <tr>
                    <th class="handle" data-field="s_no" data-sortable="false"><?= $this->lang->line('s_no')?$this->lang->line('s_no'):'#'?></th>
                    <th class="handle" data-field="employee_id" data-sortable="true"><?= $this->lang->line('employee_id')?$this->lang->line('employee_id'):'Emp ID'?></th>
                    <!--<th data-field="id" data-sortable="true" data-visible="false"><?=$this->lang->line('id')?htmlspecialchars($this->lang->line('id')):'Employee ID'?></th>-->
                    <th class="handle" data-field="name" data-sortable="true"><?= $this->lang->line('name')?$this->lang->line('name'):'Name'?></th>
                    <th class="handle" data-field="email" data-sortable="false"><?= $this->lang->line('email')?$this->lang->line('email'):'Email'?></th>
                    <th class="handle" data-field="mobile" data-sortable="false"><?= $this->lang->line('mobile')?$this->lang->line('mobile'):'Mobile'?></th>
                    <th class="handle" data-field="role" data-sortable="false"><?= $this->lang->line('role')?$this->lang->line('role'):'Role'?></th>
                    <th class="handle" data-field="status" data-sortable="false" data-visible="true"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>
                    <th class="handle" data-field="projects_count" data-sortable="true"><?= $this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></th>
                    <th data-field="tasks_count" data-sortable="true"><?= $this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></th>
                    <th data-field="shift_type" data-sortable="false" data-visible="true"><?=$this->lang->line('shift_type')?htmlspecialchars($this->lang->line('shift_type')):'Shift'?></th>
                    <?php if ($this->ion_auth->is_admin() || permissions('user_view_all') || $this->ion_auth->in_group(3)){ ?>
                    <th data-field="cnic" data-sortable="false" data-visible="false"><?=$this->lang->line('cnic')?htmlspecialchars($this->lang->line('cnic')):'CNIC'?></th>
                    <th data-field="father_name" data-sortable="false" data-visible="false"><?=$this->lang->line('father_name')?htmlspecialchars($this->lang->line('father_name')):'Father Name'?></th>
                    <th data-field="department" data-sortable="false" data-visible="false"><?=$this->lang->line('department')?htmlspecialchars($this->lang->line('department')):'Department'?></th>
                    <th data-field="joining_date" data-sortable="false" data-visible="false"><?=$this->lang->line('joining_date')?htmlspecialchars($this->lang->line('joining_date')):'Joining Date'?></th>
                    <th data-field="gender" data-sortable="false" data-visible="false"><?=$this->lang->line('gender')?htmlspecialchars($this->lang->line('gender')):'Gender'?></th>
                  <?php } ?>
                    <?php if ($this->ion_auth->is_admin() || permissions('user_edit') || permissions('user_delete') || $this->ion_auth->in_group(3)){ ?>
                    <th data-field="action" data-sortable="false" data-visible="true"><?=$this->lang->line('action')?htmlspecialchars($this->lang->line('action')):'Action'?></th>
                  <?php } ?>
                </tr>
                </div>
              </thead>
              
            </table>
          </div>
        </div>
      </div>
    </div><!--end of the table format  -->

<!-- tabular view for complete page-->
<!--
<div class="row">
  <div class="col-lg-12">
      <div style="width: 100%; overflow-x: auto;">
    <div class="card">
      <div class="card-header">
        <h4>User List</h4>
       </div>
      <div class="card-body">
        <div class="table-responsive">
          <div style="width: 100%; overflow-x: auto;">
            <table class="table table-striped table-bordered" id="user-list">
              <thead>
                <tr>
                  <th><?= $this->lang->line('name')?$this->lang->line('name'):'Name'?></th>
                  <th><?= $this->lang->line('email')?$this->lang->line('email'):'Email'?></th>
                  <th><?= $this->lang->line('mobile')?$this->lang->line('mobile'):'Mobile'?></th>
                  <th><?= $this->lang->line('role')?$this->lang->line('role'):'Role'?></th>
                  <th><?= $this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
                  <th><?= $this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></th>
                  <th><?= $this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></th>
                  <?php if ($this->ion_auth->is_admin() || permissions('user_view_all') || $this->ion_auth->in_group(3)){ ?>
                    <th>Action</th>
                    <th>Performance</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php
                  if(isset($system_users) && !empty($system_users)){
                    foreach ($system_users as $system_user) {
                ?>
                <tr>
                  <td><?= htmlspecialchars($system_user['first_name'])?> <?= htmlspecialchars($system_user['last_name'])?></td>
                  <td><?= htmlspecialchars($system_user['email'])?></td>
                  <td><?= htmlspecialchars($system_user['phone'])?></td>
                  <td><?= htmlspecialchars($system_user['role'])?></td>
                  <td><?= htmlspecialchars($system_user['active'])==1?($this->lang->line('active')?$this->lang->line('active'):'Active'):($this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive')?></td>
                  <td><span class="badge badge-secondary"><?= htmlspecialchars($system_user['projects_count'])?></span></td>
                  <td><span class="badge badge-secondary"><?= htmlspecialchars($system_user['tasks_count'])?></span></td>
                  <?php if ($this->ion_auth->is_admin() || permissions('user_view_all') || $this->ion_auth->in_group(3)){ ?>
                    <td><a href="#" data-edit="<?= htmlspecialchars($system_user['id'])?>" class="modal-edit-user" title="Edit" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a></td>
                    <td><?= htmlspecialchars($system_user['performance'])?></td>
                  <?php } ?>
                </tr>
                <?php
                    }
                  }
                ?>
              </tbody>
             
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>-->






<!-- End of the tabular View-->


<!-- Another View of the tabular view-->
<!--<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4>User List</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered" id="user-list">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(isset($system_users) && !empty($system_users)){
                foreach ($system_users as $system_user) {
              ?>
              <tr>
                <td><?=htmlspecialchars($system_user['first_name'])?> <?=htmlspecialchars($system_user['last_name'])?></td>
                <td><?=htmlspecialchars($system_user['email'])?></td>
                <td><?=htmlspecialchars($system_user['phone'])?></td>
                <td><?=htmlspecialchars($system_user['role'])?></td>
                <td><?=htmlspecialchars($system_user['active'])==1 ? 'Active' : 'Deactive'?></td>
              </tr>
              <?php
                } 
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // DataTable Initialization
    var table = $('#user-list').DataTable({
      "paging": true,
      "searching": true,
      "info": true,
      "columnDefs": [{
        "targets": [4],
        "visible": false,
        "searchable": false
      }],
      "order": [[0, "asc"]],
      "language": {
        "paginate": {
          "next": "<i class='fas fa-chevron-right'></i>",
          "previous": "<i class='fas fa-chevron-left'></i>"
        },
        "search": "<i class='fas fa-search'></i>"
      }
    });

    // Add Search Bar
    $('#user-list_filter').prepend('<div class="input-group"><input type="text" class="form-control" placeholder="Search" aria-label="Search"><div class="input-group-append"><span class="input-group-text"><i class="fas fa-search"></i></span></div></div>');
    $('#user-list_filter').addClass('pb-3');

    // Add Hide/Show Columns Function
    var checkbox = '<input type="checkbox" class="form-check-input dt-checkboxes">';
    $('#user-list thead tr').prepend('<th>' + checkbox + '</th>');
    $('#user-list tfoot tr').prepend('<th>' + checkbox + '</th>');
    $('#user-list thead input.dt-checkboxes').on('click', function() {
      var column = table.column($(this).parent().index() + ':visible');
      column.visible(!column.visible());
    });
    $('#user-list tfoot input.dt-checkboxes').on('click', function() {
      var column = table.column($(this).parent().index() + ':visible');
      column.visible(!column.visible());
    });
  });
</script>-->



            </div>    
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

            
            
          
<form action="<?=base_url('auth/create-user')?>" method="POST" class="modal-part" id="modal-add-user-part" data-title="<?=$this->lang->line('create_new_user')?$this->lang->line('create_new_user'):'Create New User'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="row">
      <div class="form-group col-md-6">
      <label><?=$this->lang->line('employee_id')?$this->lang->line('employee_id'):'Employee Id'?><span class="text-danger">*</span></label>
      <input type="text" name="employee_id" id="employee_id_create" class="form-control" required="" readonly>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('first_name')?$this->lang->line('first_name'):'First Name'?><span class="text-danger">*</span></label>
      <input type="text" name="first_name" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('last_name')?$this->lang->line('last_name'):'Last Name'?><span class="text-danger">*</span></label>
      <input type="text" name="last_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('father_name')?$this->lang->line('father_name'):'Father Name'?><span class="text-danger">*</span></label>
      <input type="text" name="father_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?><span class="text-danger">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('this_email_will_not_be_updated_latter')?$this->lang->line('this_email_will_not_be_updated_latter'):'This email will not be updated latter.'?>"></i></label>
      <input type="email" name="email"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('gender')?$this->lang->line('gender'):'Gender'?><span class="text-danger">*</span></label>
      <select name="gender" class="form-control select2">
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('mobile')?$this->lang->line('mobile'):'Mobile'?></label>
      <input type="text" name="phone"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('cnic')?$this->lang->line('cnic'):'CNIC'?></label>
      <input type="text" name="cnic"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('password')?$this->lang->line('password'):'Password'?><span class="text-danger">*</span></label>
      <input type="text" name="password"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('confirm_password')?$this->lang->line('confirm_password'):'Confirm Password'?><span class="text-danger">*</span></label>
      <input type="text" name="password_confirm"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('role')?$this->lang->line('role'):'Role'?><span class="text-danger">*</span></label>
      <select name="groups" class="form-control select2">
        <?php foreach ($user_groups as $user_group) { ?>
          <option value="<?=htmlspecialchars($user_group->id)?>"><?=ucfirst(htmlspecialchars($user_group->description))?></option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('desgnation')?$this->lang->line('desgnation'):'Designation'?><span class="text-danger">*</span></label>
      <input type="text" name="desgnation" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label><?=$this->lang->line('department')?$this->lang->line('type'):'Department'?></label>
        <select name="department" class="form-control select2">
            <?php foreach($departments as $department){ ?>
            <option value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('join_date')?$this->lang->line('join_date'):'Join Date'?><span class="text-danger">*</span></label>
      <input type="text" name="join_date" class="form-control datepicker">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('emg_person')?$this->lang->line('emg_person'):'Emergency Person'?><span class="text-danger">*</span></label>
      <input type="text" name="emg_person" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('emg_number')?$this->lang->line('emg_number'):'Emergency Contact Number'?><span class="text-danger">*</span></label>
      <input type="text" name="emg_number" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label><?=$this->lang->line('type')?$this->lang->line('type'):'Shift Type'?></label>
        <select name="type" class="form-control select2">
            <?php foreach($shift_types as $shift_type){ ?>
            <option value="<?= $shift_type['id'] ?>"><?= $shift_type['name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('date_of_birth')?$this->lang->line('date_of_birth'):'Date of Birth'?></label>
      <input type="text" name="date_of_birth" class="form-control datepicker">
    </div>
    
    <div class="form-group col-md-6">
        <label><?=$this->lang->line('device')?$this->lang->line('device'):'Device'?></label>
        <select  name="device" class="form-control select2">
            <?php foreach($devices as $device){ ?>
            <option value="<?= $device['id'] ?>"><?= $device['device_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('address')?$this->lang->line('address'):'Address'?><span class="text-danger">*</span></label>
      <textarea class="form-control" style="height:100px;" name="address" rows="10"></textarea>
    </div>
    
     <!--<div class="form-group col-md-6">
      <label><?=$this->lang->line('task_over_due')?$this->lang->line('task_over_due'):'Task Over Due'?><span class="text-danger">*</span></label>
      <input type="text" name="task_over_due" class="form-control" required="">
    </div>-->
  </div>
</form>

<form action="<?=base_url('auth/edit-user')?>" method="POST" class="modal-part" id="modal-edit-user-part" data-title="<?=$this->lang->line('edit_user')?$this->lang->line('edit_user'):'Edit User'?>" data-btn_login="<?=$this->lang->line('login')?$this->lang->line('login'):'Login'?>" data-btn_delete="<?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?>" data-btn_update="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>" data-btn_active="<?=$this->lang->line('active')?$this->lang->line('active'):'Active'?>" data-btn_deactive="<?=$this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive'?>">
  <input type="hidden" name="update_id" id="update_id" value="">
  <input type="hidden" name="status" id="active" value="">
  <input type="hidden" name="old_profile_pic" id="old_profile_pic" value="">
  <div class="row">
      <div class="form-group col-md-6">
      <label><?=$this->lang->line('employee_id')?$this->lang->line('employee_id'):'Employee Id'?><span class="text-danger">*</span></label>
      <input type="text" name="employee_id" id="employee_id" class="form-control" required="" readonly>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('first_name')?$this->lang->line('first_name'):'First Name'?><span class="text-danger">*</span></label>
      <input type="text" id="first_name" name="first_name" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('last_name')?$this->lang->line('last_name'):'Last Name'?><span class="text-danger">*</span></label>
      <input type="text" id="last_name" name="last_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('father_name')?$this->lang->line('father_name'):'Father Name'?><span class="text-danger">*</span></label>
      <input type="text" id="father_name" value="abc" name="father_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?><span class="text-danger">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('this_email_will_not_be_updated_latter')?$this->lang->line('this_email_will_not_be_updated_latter'):'This email can not be updated.'?>"></i></label>
      <input type="email" name="email" id="email"  class="form-control" readonly>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('gender')?$this->lang->line('gender'):'Gender'?><span class="text-danger">*</span></label>
      <select name="gender" id="gender" class="form-control select2">
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('mobile')?$this->lang->line('mobile'):'Mobile'?></label>
      <input type="text" id="phone" name="phone" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('cnic')?$this->lang->line('cnic'):'CNIC'?></label>
      <input type="text" name="cnic" id="cnic" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('password')?$this->lang->line('password'):'Password'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')?$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password'):'Leave Password and Confirm Password empty for no change in Password.'?>"></i></label>
      <input type="text" name="password"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('confirm_password')?$this->lang->line('confirm_password'):'Confirm Password'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')?$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password'):'Leave Password and Confirm Password empty for no change in Password.'?>"></i></label>
      <input type="text" name="password_confirm"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('role')?$this->lang->line('role'):'Role'?><span class="text-danger">*</span></label>
      <select name="groups" id="groups" class="form-control select2">
        <?php foreach ($user_groups as $user_group) { ?>
          <option value="<?=htmlspecialchars($user_group->id)?>"><?=ucfirst(htmlspecialchars($user_group->description))?></option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('desgnation')?$this->lang->line('desgnation'):'Designation'?><span class="text-danger">*</span></label>
      <input type="text" id="desgnation" name="desgnation" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label><?=$this->lang->line('department')?$this->lang->line('type'):'Department'?></label>
        <select  id="department" name="department" class="form-control select2">
            <?php foreach($departments as $department){ ?>
            <option value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('join_date')?$this->lang->line('join_date'):'Join Date'?><span class="text-danger">*</span></label>
      <input type="text" id="join_date" name="join_date" class="form-control datepicker">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('emg_person')?$this->lang->line('emg_person'):'Emergency Person'?><span class="text-danger">*</span></label>
      <input type="text" id="emg_person" name="emg_person" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('emg_number')?$this->lang->line('emg_number'):'Emergency Contact Number'?><span class="text-danger">*</span></label>
      <input type="text" id="emg_number" name="emg_number" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label><?=$this->lang->line('type')?$this->lang->line('type'):'Shift Type'?></label>
        <select name="type" id="type" class="form-control select2">
            <?php foreach($shift_types as $shift_type){ ?>
            <option value="<?= $shift_type['id'] ?>"><?= $shift_type['name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('date_of_birth')?$this->lang->line('date_of_birth'):'Date of Birth'?><span class="text-danger">*</span></label>
      <input type="text" id="date_of_birth" name="date_of_birth" class="form-control datepicker">
    </div>
    
    <div class="form-group col-md-6">
        <label><?=$this->lang->line('device')?$this->lang->line('device'):'Device'?></label>
        <select  id="device" name="device" class="form-control select2">
            <?php foreach($devices as $device){ ?>
            <option value="<?= $device['id'] ?>"><?= $device['device_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('address')?$this->lang->line('address'):'Address'?><span class="text-danger">*</span></label>
      <textarea id="address" class="form-control" style="height:100px;" name="address" rows="10"></textarea>
    </div>
  </div>
</form>
<div id="modal-edit-user"></div>
<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
    return {
        "active_users": $('#active_users').val(),
        "department_users": $('#department_users').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }
  
  $(document).ready(function() {
    function getEmployeeId() {

      $.ajax({
        url: '<?= base_url('users/get_employee_id') ?>',
        method: 'POST', // or 'POST' depending on your server-side implementation
        dataType: 'json',
        success: function(response) {
          // Retrieve the calculated values from the JSON response
          var employee_id = response.max_employee_id;
          employee_id++;
          // Update the input fields in the form
          
          $('#employee_id_create').val(employee_id);
        },
      });
    }
    getEmployeeId();
  });
  </script>
  <script>
    function refreshTable(){
      $('#GFG').bootstrapTable('refresh');
    }
  </script>
</body>
</html>
