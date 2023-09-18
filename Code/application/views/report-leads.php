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
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('leads')?$this->lang->line('leads'):'Leads'?></div>
            </div>
          </div>
          <div class="section-body">
            
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
                      data-toolbar="#tool" data-show-export="true"
                      data-maintain-selected="true"
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="company"><?=$this->lang->line('company_name')?htmlspecialchars($this->lang->line('company_name')):'Company Name'?></th>
                          <th data-field="email"><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?></th>
                          <th data-field="value" data-visible="false"><?=$this->lang->line('value')?$this->lang->line('value'):'Value'?> - <?=get_currency('currency_code')?></th>
                          <th data-field="assigned" data-visible="false"><?=$this->lang->line('assigned')?$this->lang->line('assigned'):'Assigned'?></th>
                        
                          <th data-field="created"><?=$this->lang->line('created')?$this->lang->line('created'):'Created'?></th>
                          <th data-field="status"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>
                          <th data-field="source" data-visible="false"><?=$this->lang->line('source')?$this->lang->line('source'):'Source'?></th>
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
