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
            <?=$this->lang->line('expenses')?$this->lang->line('expenses'):'Expenses'?> 
              <?php if (!$this->ion_auth->in_group(4)){ ?>
                <a href="#" id="modal-add-expenses" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('expenses')?$this->lang->line('expenses'):'Expenses'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">

              <div class="form-group col-md-4">
                <select class="form-control select2 expenses_filter" id="expenses_filter_user">
                  <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                  <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              <div class="form-group col-md-4">
                <select class="form-control select2 expenses_filter client_id" id="expenses_filter_client">
                  <option value=""><?=$this->lang->line('select_observer')?$this->lang->line('select_observer'):'Select Observer'?></option>
                  <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              <div class="form-group col-md-4">
                <select class="form-control select2 expenses_filter project_id" id="expenses_filter_project">
                  <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                  <?php foreach($projects as $project){ ?>
                  <option value="<?=$project['id']?>"><?=htmlspecialchars($project['title'])?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='expenses_list'
                          data-toggle="table"
                          data-url="<?=base_url('expenses/get_expenses')?>"
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
                            "fileName": "expenses-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="description" data-sortable="true"><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?></th>
                              <th data-field="date" data-sortable="true"><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?></th>
                              <th data-field="amount" data-sortable="true"><?=$this->lang->line('amount')?$this->lang->line('amount'):'Amount'?> - <?=get_currency('currency_code')?></th>

                              <th data-field="receipt" data-sortable="true"><?=$this->lang->line('receipt')?$this->lang->line('receipt'):'Receipt'?></th>
                              <th data-field="user" data-sortable="true" data-visible="false"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></th>
                              <th data-field="client" data-sortable="false" data-visible="false"><?=$this->lang->line('observer')?$this->lang->line('observer'):'Observer'?></th>
                              <th data-field="project_titile" data-sortable="false" data-visible="false"><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?></th>

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


<form action="<?=base_url('expenses/create')?>" method="POST" class="modal-part" id="modal-add-expenses-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  
  <div class="form-group">
    <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
    <textarea type="text" name="description" class="form-control"></textarea>
  </div>

  <span class="row">
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
      <input type="text" name="date" class="form-control datepicker">
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('amount_usd')?$this->lang->line('amount_usd'):'Amount'?> - <?=get_currency('currency_code')?><span class="text-danger">*</span></label>
      <input type="number" pattern="[0-9]" name="amount" class="form-control">
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></label>
      <select name="team_member_id" class="form-control select2 user_id">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  </span>
  
  <span class="row">
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('Observer')?$this->lang->line('Observer'):'observer'?></label>
      <select class="form-control select2 client_id" name="client_id">
        <option value=""><?=$this->lang->line('select_observer')?$this->lang->line('select_observer'):'Select Observer'?></option>
        <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
        <?php } } ?>
      </select>
    </div>

    <div class="form-group col-md-8">
      <label><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?></label>
      <select name="project_id" class="form-control select2 project_id">
        <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
        <?php foreach($projects as $project){ ?>
        <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
        <?php } ?>
      </select>
    </div>
  </span>
  
  <div class="form-group">
    <label><?=$this->lang->line('receipt')?$this->lang->line('receipt'):'Receipt'?></label>
    <input type="file" name="receipt" class="form-control">
  </div>
  
</form>

<form action="<?=base_url('expenses/edit')?>" method="POST" class="modal-part" id="modal-edit-expenses-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">
  <input type="hidden" name="old_receipt" id="old_receipt" value="">

  <div class="form-group">
    <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
    <textarea type="text" name="description" id="description" class="form-control"></textarea>
  </div>
  <span class="row">
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?><span class="text-danger">*</span></label>
      <input type="text" name="date" id="date" class="form-control datepicker">
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('amount_usd')?$this->lang->line('amount_usd'):'Amount'?> - <?=get_currency('currency_code')?><span class="text-danger">*</span></label>
      <input type="number" pattern="[0-9]" name="amount" id="amount" class="form-control">
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?></label>
      <select name="team_member_id" id="team_member_id" class="form-control select2 user_id">
        <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
  </span>
  
  <span class="row">
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('observer')?$this->lang->line('observer'):'Observer'?></label>
      <select class="form-control select2" name="client_id" id="client_id">
        <option value=""><?=$this->lang->line('select_Observer')?$this->lang->line('select_Observer'):'Select Observer'?></option>
        <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
        <?php } } ?>
      </select>
    </div>

    <div class="form-group col-md-8">
      <label><?=$this->lang->line('project')?$this->lang->line('project'):'Project'?></label>
      <select name="project_id" id="project_id" class="form-control select2">
        <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
        <?php foreach($projects as $project){ ?>
        <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
        <?php } ?>
      </select>
    </div>
  </span>
  
  <div class="form-group">
    <label><?=$this->lang->line('receipt')?$this->lang->line('receipt'):'Receipt'?></label>
    <input type="file" name="receipt" class="form-control">
  </div>

</form>

<div id="modal-edit-expenses"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
      return {
        "project_id": $('#expenses_filter_project').val(),
        "client_id": $('#expenses_filter_client').val(),
        "team_member_id": $('#expenses_filter_user').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }

  $(document).on('change','.client_id',function(){
    $.ajax({
        type: "POST",
        url: base_url+'projects/get_clients_projects/'+$(this).val(), 
        data: "to_id="+$(this).val(),
        dataType: "json",
        success: function(result) 
        {	
          var projects = '<option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>';
          $.each(result['data'], function (key, val) {
            projects +='<option value="'+val.id+'">'+val.title+'</option>';
          });
          $(".project_id").html(projects);
        }        
    });
  });
  $(document).on('change','#client_id',function(e,data){
    console.log(data);
    $.ajax({
        type: "POST",
        url: base_url+'projects/get_clients_projects/'+$(this).val(), 
        data: "to_id="+$(this).val(),
        dataType: "json",
        success: function(result) 
        {	
          var projects = '<option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>';
          $.each(result['data'], function (key, val) {
            projects +='<option value="'+val.id+'" '+(data==val.id?"selected":"")+'>'+val.title+'</option>';
          });
          $("#project_id").html(projects);
        }        
    });
  });

  $(document).on('change','.expenses_filter',function(){
    $('#expenses_list').bootstrapTable('refresh');
  });
  
</script>
</body>
</html>
