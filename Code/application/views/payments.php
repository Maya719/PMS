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
            <?=$this->lang->line('payments')?$this->lang->line('payments'):'Payments'?><?=$this->ion_auth->is_admin()?' / '.($this->lang->line('income')?htmlspecialchars($this->lang->line('income')):'Income'):''?>
            
            <?php if($this->ion_auth->is_admin()){ ?>
              <a href="#" id="modal-add-payments" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('payments')?$this->lang->line('payments'):'Payments'?>
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
                            <select id='client' class="form-control col-md-4 mr-1">
                              <option value=""><?=$this->lang->line('select_observer')?$this->lang->line('select_observer'):'Select Observer'?></option>
                              <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
                              <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                              <?php } } ?>
                            </select>
                          <?php } ?>
                          <select id='project' class="form-control col-md-4 mr-1">
                            <option value=""><?=$this->lang->line('select_project')?$this->lang->line('select_project'):'Select Project'?></option>
                            <?php foreach($projects as $project){ ?>
                            <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
                            <?php } ?>
                          </select>
                          <select id='status_filter' class="form-control col-md-3">
                            <option value="none"><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?></option>
                            <option value="0"><?=$this->lang->line('due')?$this->lang->line('due'):'Due'?></option>
                            <option value="1"><?=$this->lang->line('paid')?$this->lang->line('paid'):'Paid'?></option>
                            <option value="2"><?=$this->lang->line('overdue')?$this->lang->line('overdue'):'Overdue'?></option>
                          </select>
                        </div>
                        <table class='table-striped' id='invoices_list'
                          data-toggle="table"
                          data-url="<?=base_url('invoices/get_payments')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="false"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="true" data-show-columns="true"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="DESC"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="false"
                          data-maintain-selected="true"
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "payments-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              
                              <?php if($this->ion_auth->is_admin()){ ?>
                                <th data-field="to_id" data-sortable="true"><?=$this->lang->line('observer')?$this->lang->line('observer'):'Observer'?></th>
                              <?php } ?>

                              <th data-field="invoice_id" data-sortable="true"><?=$this->lang->line('invoice')?$this->lang->line('invoice'):'Invoice'?></th>

                              <th data-field="amount" data-sortable="true"><?=$this->lang->line('amount')?$this->lang->line('amount'):'Amount'?> - <?=get_currency('currency_code')?></th>

                              <th data-field="payment_type" data-sortable="true"><?=$this->lang->line('payment_type')?$this->lang->line('payment_type'):'Payment Type'?></th>
                              <th data-field="payment_date" data-sortable="true" data-visible="false"><?=$this->lang->line('payment_date')?$this->lang->line('payment_date'):'Payment Date'?></th>
                              
                              <?php if($this->ion_auth->is_admin()){ ?>
                              <th data-field="receipt" data-sortable="true"><?=$this->lang->line('receipt')?$this->lang->line('receipt'):'Receipt'?></th>
                              <?php } ?>

                              <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>

                              <?php if($this->ion_auth->is_admin()){ ?>
                                <th data-field="action" data-sortable="true"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
                              <?php } ?>
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


  <form action="<?=base_url('invoices/order_completed')?>" method="POST" class="modal-part" id="modal-add-payments-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="row">
    <input type="hidden" value="By Admin" name="payment_type">
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('select_invoice')?htmlspecialchars($this->lang->line('select_invoice')):'Select Invoice'?><span class="text-danger">*</span></label>
      <select class="form-control select2" id="invoices_id" name="invoices_id">
        <option value=""><?=$this->lang->line('select_invoice')?htmlspecialchars($this->lang->line('select_invoice')):'Select Invoice'?></option>
        <?php foreach($invoices as $invoice){ ?>
        <option value="<?=htmlspecialchars($invoice['id'])?>"><?=htmlspecialchars($invoice['invoice_id'])?> - <?=htmlspecialchars($invoice['to_user'])?></option>
        <?php } ?>
      </select>
    </div>
    
  </div>
</form>

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
    return {
      "to_id": $('#client').val(),
      "items_id": $('#project').val(),
      "status": $('#status_filter').val(),
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
  $('#tool').on('change',function(e){
    $('#invoices_list').bootstrapTable('refresh');
  });
</script>
</body>
</html>
