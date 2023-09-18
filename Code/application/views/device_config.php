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
            <?=$this->lang->line('device_config')?$this->lang->line('device_config'):'Device Configuration'?> 
              <?php if (!$this->ion_auth->in_group(4)){ ?>
                <a href="#" id="modal-add-leaves" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('device_config')?$this->lang->line('device_config'):'Device Configuration'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='leaves_list'
                          data-toggle="table"
                          data-url="<?=base_url('device_config/get_device_config')?>"
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
                              <th data-field="s_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('s_no'):'S.no'?></th>
                              <th data-field="device_name" data-sortable="true"><?=$this->lang->line('device_name')?$this->lang->line('device_name'):'Device Name'?></th>
                              <th data-field="device_ip" data-sortable="true"><?=$this->lang->line('device_ip')?$this->lang->line('device_ip'):'Device Ip Address'?></th>
                              <th data-field="users" data-sortable="false" class="left-pad" data-formatter="teamMembersFormatter">
                                <?=$this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member'?>
                            </th> 
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


<form action="<?=base_url('device_config/create')?>" method="POST" class="modal-part" id="modal-add-leaves-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
    <div class="row" id="dates">
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('device_name')?$this->lang->line('device_name'):'Device name'?><span class="text-danger">*</span></label>
          <input type="text" name="device_name" class="form-control" required="">
      </div>
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('device_ip')?$this->lang->line('device_ip'):'Device Ip Address'?><span class="text-danger">*</span></label>
          <input type="text" name="device_ip" class="form-control" required="">
      </div>
    </div>
  </div>
  
  
</form>

<!-- update/edit Model -->
<form action="<?=base_url('device_config/edit')?>" method="POST" class="modal-part" id="modal-edit-device-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">
  <div class="row" id="dates">
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('device_name')?$this->lang->line('device_name'):'Device name'?><span class="text-danger">*</span></label>
          <input type="text" name="device_name" id="device_name" class="form-control" required="">
      </div>
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('device_ip')?$this->lang->line('device_ip'):'Device Ip Address'?><span class="text-danger">*</span></label>
          <input type="text" name="device_ip" id="device_ip" class="form-control" required="">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('users')?$this->lang->line('users'):'Users'?><span class="text-danger">*</span></label>
        <select name="users[]" id="users" class="form-control select2" multiple="">
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
        </select>
    </div>
  </div>
</form>
<div id="modal-edit-device"></div>

<?php $this->load->view('includes/js'); ?>
<script>
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
    $('#ending_date_create').daterangepicker({
      locale: { format: date_format_js },
      singleDatePicker: true,
  });
      
  });
  
</script>
</body>
</html>