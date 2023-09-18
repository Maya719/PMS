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
              <?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?> 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></div>
            </div>
          </div>
          <div class="section-body">
            <div id="tool" class="row">
              
              <div class="form-group col-md-4">
                <select class="form-control select2" id="project_filters_user">
                  <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                  <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>
              
              <div class="form-group col-md-4">
                <select class="form-control select2" id="project_filters_client">
                  <option value=""><?=$this->lang->line('select_clients')?$this->lang->line('select_clients'):'Select Clients'?></option>
                  <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                  <?php } } ?>
                </select>
              </div>

              
              <div class="form-group col-md-4">
                <select class="form-control select2" id="project_filters_status">
                  <option value=""><?=$this->lang->line('select_status')?$this->lang->line('select_status'):'Select Status'?></option>
                  <?php foreach($project_status as $status){ ?>
                  <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
                  <?php } ?>
                </select>
              </div>

            </div>


            <div class="row">





  
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body"> 
                    <table class='table-striped' id='projects_list'
                      data-toggle="table"
                      data-url="<?=base_url('projects/get_projects_list')?>"
                      data-click-to-select="true"
                      data-side-pagination="server"
                      data-pagination="true"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="true" data-show-columns="true"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="id" data-sort-order="DESC"
                      data-mobile-responsive="true"
                      data-toolbar="" data-show-export="true"
                      data-maintain-selected="true"
                      data-export-options='{
                        "fileName": "projects_list",
                      }'
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          
                          <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?></th>

                          <th data-field="budget" data-sortable="true"><?=$this->lang->line('budget')?htmlspecialchars($this->lang->line('budget')):'Budget'?> - <?=get_currency('currency_code')?></th>

                          <th data-field="starting_date" data-sortable="true"><?=$this->lang->line('starting_date')?$this->lang->line('starting_date'):'Starting Date'?></th>

                          <th data-field="ending_date" data-sortable="true"><?=$this->lang->line('ending_date')?$this->lang->line('ending_date'):'Ending Date'?></th>

                          <th data-field="project_status" data-sortable="true"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>

                          <th data-field="project_client" data-sortable="false" data-visible="false"><?=$this->lang->line('project_client')?htmlspecialchars($this->lang->line('project_client')):'Project Client'?></th>

                          <th data-field="stats" data-sortable="false" data-visible="false"><?=$this->lang->line('stats')?htmlspecialchars($this->lang->line('stats')):'Stats'?></th>

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
      "status": $('#project_filters_status').val(),
      "user": $('#project_filters_user').val(),
      "client": $('#project_filters_client').val(),
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
  
  $('#tool').on('change',function(e){
    $('#projects_list').bootstrapTable('refresh');
  });
</script>
</body>
</html>
