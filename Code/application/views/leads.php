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
            <?=$this->lang->line('leads')?$this->lang->line('leads'):'Leads'?> 
              <?php if($this->ion_auth->is_admin() || permissions('lead_create')){ ?> 
                <a href="#" id="modal-add-leads" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('leads')?$this->lang->line('leads'):'Leads'?></div>
            </div>
          </div>
          <div class="section-body">
            
            <?php if($this->ion_auth->is_admin()){ ?>
              <div class="row">
                <div class="form-group col-md-6">
                  <select class="form-control select2 lead_filter" id="lead_filter_user">
                    <option value=""><?=$this->lang->line('assigned')?$this->lang->line('assigned'):'Assigned'?></option>
                    <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                    <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } } ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <select name="status" id="status_filter" class="form-control">
                    <option value=""><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></option>
                    <option value="new"><?=$this->lang->line('new')?$this->lang->line('new'):'New'?></option>
                    <option value="qualified"><?=$this->lang->line('qualified')?$this->lang->line('qualified'):'Qualified'?></option>
                    <option value="discussion"><?=$this->lang->line('discussion')?$this->lang->line('discussion'):'Discussion'?></option>
                    <option value="won"><?=$this->lang->line('won')?$this->lang->line('won'):'Won'?></option>
                    <option value="lost"><?=$this->lang->line('lost')?$this->lang->line('lost'):'Lost'?></option>
                  </select>
                </div>

              </div>
            <?php } ?>

            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body"> 
                    <table class='table-striped' id='leads_list'
                      data-toggle="table"
                      data-url="<?=base_url('leads/get_leads')?>"
                      data-click-to-select="true"
                      data-side-pagination="server"
                      data-pagination="true"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="true" data-show-columns="true"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="id" data-sort-order="desc"
                      data-mobile-responsive="true"
                      data-toolbar="#tool" data-show-export="false"
                      data-maintain-selected="true"
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="company"><?=$this->lang->line('company_name')?htmlspecialchars($this->lang->line('company_name')):'Company Name'?></th>
                          <th data-field="email"><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?></th>
                          <th data-field="value" data-visible="false"><?=$this->lang->line('value')?$this->lang->line('value'):'Value'?> - <?=get_currency('currency_code')?></th>

                          <?php if($this->ion_auth->is_admin()){ ?>
                          <th data-field="assigned" data-visible="false"><?=$this->lang->line('assigned')?$this->lang->line('assigned'):'Assigned'?></th>
                          <?php } ?>

                          <th data-field="created"><?=$this->lang->line('created')?$this->lang->line('created'):'Created'?></th>
                          <th data-field="status"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>
                          <th data-field="source" data-visible="false"><?=$this->lang->line('source')?$this->lang->line('source'):'Source'?></th>
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

  
<form action="<?=base_url('leads/create')?>" method="POST" class="modal-part" id="modal-add-leads-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="form-group">
    <label><?=$this->lang->line('company_name')?htmlspecialchars($this->lang->line('company_name')):'Company Name'?><span class="text-danger">*</span></label>
    <input type="text" name="company" class="form-control" required="">
  </div>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('value')?$this->lang->line('value'):'Value'?> - <?=get_currency('currency_code')?><span class="text-danger">*</span></label>
      <input type="number" pattern="[0-9]" name="value" class="form-control" required="">
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('source')?$this->lang->line('source'):'Source'?><span class="text-danger">*</span></label>
      <input type="text" name="source" placeholder="Facebook" class="form-control" required="">
    </div>
  </span>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?><span class="text-danger">*</span></label>
      <input type="text" name="email" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone'?></label>
      <input type="text" name="mobile" class="form-control">
    </div>
  </span>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?><span class="text-danger">*</span></label>
      <select name="status" class="form-control">
        <option value="new"><?=$this->lang->line('new')?$this->lang->line('new'):'New'?></option>
        <option value="qualified"><?=$this->lang->line('qualified')?$this->lang->line('qualified'):'Qualified'?></option>
        <option value="discussion"><?=$this->lang->line('discussion')?$this->lang->line('discussion'):'Discussion'?></option>
        <option value="won"><?=$this->lang->line('won')?$this->lang->line('won'):'Won'?></option>
        <option value="lost"><?=$this->lang->line('lost')?$this->lang->line('lost'):'Lost'?></option>
      </select>
    </div>
    
    <?php if($this->ion_auth->is_admin()){ ?>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('assigned')?$this->lang->line('assigned'):'Assigned'?></label>
      <select name="assigned" class="form-control select2">
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
    <?php } ?>
  </span> 

</form>


<form action="<?=base_url('leads/edit')?>" method="POST" class="modal-part" id="modal-edit-leads-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id">
  <div class="form-group">
    <label><?=$this->lang->line('company_name')?htmlspecialchars($this->lang->line('company_name')):'Company Name'?><span class="text-danger">*</span></label>
    <input type="text" name="company" class="form-control company" required="">
  </div>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('value')?$this->lang->line('value'):'Value'?> - <?=get_currency('currency_code')?><span class="text-danger">*</span></label>
      <input type="number" pattern="[0-9]" name="value" id="value" class="form-control" required="">
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('source')?$this->lang->line('source'):'Source'?><span class="text-danger">*</span></label>
      <input type="text" name="source" id="source" placeholder="Facebook" class="form-control" required="">
    </div>
  </span>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?><span class="text-danger">*</span></label>
      <input type="text" name="email" class="form-control email" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone'?></label>
      <input type="text" name="mobile" id="mobile" class="form-control">
    </div>
  </span>

  <span class="row">
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?><span class="text-danger">*</span></label>
      <select name="status" id="status" class="form-control">
        <option value="new"><?=$this->lang->line('new')?$this->lang->line('new'):'New'?></option>
        <option value="qualified"><?=$this->lang->line('qualified')?$this->lang->line('qualified'):'Qualified'?></option>
        <option value="discussion"><?=$this->lang->line('discussion')?$this->lang->line('discussion'):'Discussion'?></option>
        <option value="won"><?=$this->lang->line('won')?$this->lang->line('won'):'Won'?></option>
        <option value="lost"><?=$this->lang->line('lost')?$this->lang->line('lost'):'Lost'?></option>
      </select>
    </div>
    
    <?php if($this->ion_auth->is_admin()){ ?>
      <div class="form-group col-md-6">
        <label><?=$this->lang->line('assigned')?$this->lang->line('assigned'):'Assigned'?></label>
        <select name="assigned" id="assigned" class="form-control select2">
          <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
          <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
          <?php } } ?>
        </select>
      </div>
    <?php } ?>
  </span> 

</form>
<div id="modal-edit-leads"></div>

<form action="<?=base_url('auth/create-user')?>" method="POST" class="modal-part" id="modal-add-lead-user-part" data-title="<?=$this->lang->line('convert_to_client')?htmlspecialchars($this->lang->line('convert_to_client')):'Convert to Client'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="row">
    <div class="form-group col-md-6">
      <input type="hidden" name="groups" value="4">
      <input type="hidden" name="delete_lead" id="delete_lead">
      <label><?=$this->lang->line('first_name')?$this->lang->line('first_name'):'First Name'?><span class="text-danger">*</span></label>
      <input type="text" name="first_name" id="first_name" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('last_name')?$this->lang->line('last_name'):'Last Name'?><span class="text-danger">*</span></label>
      <input type="text" name="last_name" value="Client" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('company_name')?$this->lang->line('company_name'):'Company Name'?></label>
      <input type="text" name="company" class="form-control company">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?><span class="text-danger">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('this_email_will_not_be_updated_latter')?$this->lang->line('this_email_will_not_be_updated_latter'):'This email will not be updated latter.'?>"></i></label>
      <input type="email" name="email" class="form-control email">
    </div>

    <div class="form-group col-md-6">
      <label><?=$this->lang->line('mobile')?$this->lang->line('mobile'):'Mobile'?></label>
      <input type="text" name="phone" id="phone" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('password')?$this->lang->line('password'):'Password'?><span class="text-danger">*</span></label>
      <input type="text" name="password"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('confirm_password')?$this->lang->line('confirm_password'):'Confirm Password'?><span class="text-danger">*</span></label>
      <input type="text" name="password_confirm"  class="form-control">
    </div>
  </div>
</form>
<div id="modal-add-lead-user"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
      return {
        "assigned": $('#lead_filter_user').val(),
        "status": $('#status_filter').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }
  $('#lead_filter_user').on('change',function(e){
    $('#leads_list').bootstrapTable('refresh');
  });
  $('#status_filter').on('change',function(e){
    $('#leads_list').bootstrapTable('refresh');
  });
</script>
</body>
</html>
