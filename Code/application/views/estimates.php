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
              <?=$this->lang->line('estimates')?$this->lang->line('estimates'):'Estimates'?>
              <?php if($this->ion_auth->is_admin()){ ?>
              <a href="#" id="modal-add-estimates" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('estimates')?$this->lang->line('estimates'):'Estimates'?>
              </div>
            </div>
          </div>
          <div class="section-body">
            
            <div class="row">

                
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <div id="tool" class="row">
                          <?php if($this->ion_auth->is_admin()){ ?>
                            <select id='client' class="form-control col-md-6 mr-1">
                              <option value=""><?=$this->lang->line('select_observer')?$this->lang->line('select_observer'):'Select Observer'?></option>
                              <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
                              <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                              <?php } } ?>
                            </select>
                            
                            <select id='status_filter' class="form-control col-md-5">
                              <option value="none"><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?></option>
                              <option value="0"><?=$this->lang->line('draft')?$this->lang->line('draft'):'Draft'?></option>
                              <option value="1"><?=$this->lang->line('sent')?$this->lang->line('sent'):'Sent'?></option>
                              <option value="2"><?=$this->lang->line('accepted')?$this->lang->line('accepted'):'Accepted'?></option>
                              <option value="3"><?=$this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected'?></option>
                            </select>

                          <?php } ?>
                        </div>
                        <table class='table-striped' id='estimates_list'
                          data-toggle="table"
                          data-url="<?=base_url('estimates/get_estimates')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="false"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="true" data-show-columns="false"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="DESC"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="false"
                          data-maintain-selected="true"
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "estimates-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="estimate_id" data-sortable="true"><?=$this->lang->line('estimate')?$this->lang->line('estimate'):'Estimate'?></th>
                              <?php if($this->ion_auth->is_admin()){ ?>
                                <th data-field="to_id" data-sortable="true"><?=$this->lang->line('clients')?$this->lang->line('clients'):'Clients'?></th>
                              <?php } ?>
                              <th data-field="due_date" data-sortable="true"><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?></th>
                              <th data-field="amount" data-sortable="true"><?=$this->lang->line('amount')?$this->lang->line('amount'):'Amount'?> - <?=get_currency('currency_code')?></th>
                              <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
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

<form action="<?=base_url('estimates/create')?>" method="POST" class="modal-part" id="modal-add-estimates-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="row">
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('estimate_date')?$this->lang->line('estimate_date'):'Estimate Date'?><span class="text-danger">*</span></label>
      <input type="text" name="estimate_date" class="form-control datepicker">
    </div>
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?><span class="text-danger">*</span></label>
      <input type="text" name="due_date" class="form-control datepicker">
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?><span class="text-danger">*</span></label>
      <select class="form-control select2" name="status">
        <option value="0"><?=$this->lang->line('draft')?$this->lang->line('draft'):'Draft'?></option>
        <option value="1"><?=$this->lang->line('sent')?$this->lang->line('sent'):'Sent'?></option>
        <option value="2"><?=$this->lang->line('accepted')?$this->lang->line('accepted'):'Accepted'?></option>
        <option value="3"><?=$this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected'?></option>
      </select>
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('select_observer')?$this->lang->line('select_observer'):'Select Observer'?><span class="text-danger">*</span></label>
      <select class="form-control select2" name="to_id">
        <option value=""><?=$this->lang->line('select_observer')?$this->lang->line('select_observer'):'Select Observer'?></option>
        <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
    <div class="form-group col-md-8">
      <label><?=$this->lang->line('products')?$this->lang->line('products'):'Products'?><span class="text-danger">*</span></label>
      <select name="products_id[]" id="products_id" class="form-control select2" multiple="">
      <?php if($products){ foreach($products as $product){ ?>
        <option value="<?=htmlspecialchars($product['id'])?>"><?=htmlspecialchars($product['name'])?></option>
      <?php } } ?>
      </select>
    </div>
    
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('tax')?$this->lang->line('taxes'):'Taxes'?></label>
      <select name="tax[]" class="form-control select2" multiple="">
        <?php foreach($taxes as $tax){ ?>
        <option value="<?=htmlspecialchars($tax['id'])?>"><?=htmlspecialchars($tax['title'])?> (<?=htmlspecialchars($tax['tax'])?>%)</option>
        <?php } ?>
      </select>
    </div>

    <div class="form-group col-md-12">
      <label><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></label>
      <textarea type="text" name="note" class="form-control"></textarea>
    </div>
    <div class="form-group col-md-12">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="send_email_notification" name="send_email_notification">
        <label class="form-check-label text-danger" for="send_email_notification"><?=$this->lang->line('send_email_notification')?$this->lang->line('send_email_notification'):'Send email notification'?></label>
      </div>
    </div>
  </div>
</form>

<div id="modal-edit-estimates"></div>
<form action="<?=base_url('estimates/edit')?>" method="POST" class="modal-part" id="modal-edit-estimates-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <div class="row">
    <input type="hidden" name="update_id" id="update_id">
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('estimate_date')?$this->lang->line('estimate_date'):'Estimate Date'?><span class="text-danger">*</span></label>
      <input type="text" name="estimate_date" id="estimate_date" class="form-control datepicker">
    </div>
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?><span class="text-danger">*</span></label>
      <input type="text" name="due_date" id="due_date" class="form-control datepicker">
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?><span class="text-danger">*</span></label>
      <select class="form-control select2" name="status" id="status">
        <option value="0"><?=$this->lang->line('draft')?$this->lang->line('draft'):'Draft'?></option>
        <option value="1"><?=$this->lang->line('sent')?$this->lang->line('sent'):'Sent'?></option>
        <option value="2"><?=$this->lang->line('accepted')?$this->lang->line('accepted'):'Accepted'?></option>
        <option value="3"><?=$this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected'?></option>
      </select>
    </div>

    <div class="form-group col-md-4">
      <label><?=$this->lang->line('select_clients')?$this->lang->line('select_clients'):'Select Client'?><span class="text-danger">*</span></label>
      <select class="form-control select2" id="to_id" name="to_id">
        <option value=""><?=$this->lang->line('select_clients')?$this->lang->line('select_clients'):'Select Clients'?></option>
        <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
        <?php } } ?>
      </select>
    </div>
    <div class="form-group col-md-8">
      <label><?=$this->lang->line('products')?$this->lang->line('products'):'Products'?><span class="text-danger">*</span></label>
      <select name="products_id[]" id="update_products_id" class="form-control select2" multiple="">
      <?php if($products){ foreach($products as $product){ ?>
        <option value="<?=htmlspecialchars($product['id'])?>"><?=htmlspecialchars($product['name'])?></option>
      <?php } } ?>
      </select>
    </div>
    
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('tax')?$this->lang->line('taxes'):'Taxes'?></label>
      <select name="tax[]" id="tax" class="form-control select2" multiple="">
        <?php foreach($taxes as $tax){ ?>
        <option value="<?=htmlspecialchars($tax['id'])?>"><?=htmlspecialchars($tax['title'])?> (<?=htmlspecialchars($tax['tax'])?>%)</option>
        <?php } ?>
      </select>
    </div>

    <div class="form-group col-md-12">
      <label><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></label>
      <textarea type="text" name="note" id="note" class="form-control"></textarea>
    </div>

    <div class="form-group col-md-12">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="send_email_notification" name="send_email_notification">
        <label class="form-check-label text-danger" for="send_email_notification"><?=$this->lang->line('send_email_notification')?$this->lang->line('send_email_notification'):'Send email notification'?></label>
      </div>
    </div>
  </div>
</form>

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
    return {
      "to_id": $('#client').val(),
      "status": $('#status_filter').val(),
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
  $('#tool').on('change',function(e){
    $('#estimates_list').bootstrapTable('refresh');
  });
</script>
</body>
</html>
