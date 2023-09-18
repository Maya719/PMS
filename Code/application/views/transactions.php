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
            <?=$this->lang->line('transactions')?$this->lang->line('transactions'):'Transactions'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('transactions')?$this->lang->line('transactions'):'Transactions'?>
              </div>
            </div>
          </div>
          <div class="section-body">
            
            <div class="row">

                
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='transactions_list'
                          data-toggle="table"
                          data-url="<?=base_url('plans/get_transactions')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="false"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="false" data-show-columns="false"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="asc"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="false"
                          data-maintain-selected="true"
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "transactions-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="user" data-sortable="true"><?=$this->lang->line('user')?$this->lang->line('user'):'User'?></th>
                              <th data-field="amount" data-sortable="true"><?=$this->lang->line('amount_usd')?$this->lang->line('amount_usd').' - '.get_saas_currency('currency_code'):'Price - '.get_saas_currency('currency_code')?></th>
                              <th data-field="created" data-sortable="true"><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?></th>
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

</body>
</html>
