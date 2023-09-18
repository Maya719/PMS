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

                            <select id='client' class="form-control col-md-6 mr-1">
                              <option value=""><?=$this->lang->line('select_clients')?$this->lang->line('select_clients'):'Select Clients'?></option>
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

                        </div>
                        <table class='table-striped' id='estimates_list'
                          data-toggle="table"
                          data-url="<?=base_url('estimates/get_estimates')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="false"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="false" data-show-columns="true"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="DESC"
                          data-show-footer="true"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="true"
                          data-maintain-selected="true"
                          data-export-options='{
                            "fileName": "estimates-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="estimate_id" data-sortable="true"><?=$this->lang->line('estimate')?$this->lang->line('estimate'):'Estimate'?></th>
                           
                              <th data-field="to_id" data-sortable="true"><?=$this->lang->line('clients')?$this->lang->line('clients'):'Clients'?></th>
                              
                              <th data-field="due_date" data-sortable="true"><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?></th>
                              <th data-field="amount" data-sortable="true"><?=$this->lang->line('amount')?$this->lang->line('amount'):'Amount'?> - <?=get_currency('currency_code')?></th>
                              <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>
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
