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
            <?=$this->lang->line('team_members')?htmlspecialchars($this->lang->line('team_members')):'Team Members'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('team_members')?htmlspecialchars($this->lang->line('team_members')):'Team Members'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">


              
            <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='income_list'
                          data-toggle="table"
                          data-url="<?=base_url('users/get_team_list')?>"
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
                            "fileName": "income-list",
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              
                              <th data-field="first_name" data-sortable="true"><?=$this->lang->line('name')?htmlspecialchars($this->lang->line('name')):'Name'?></th>

                              <th data-field="email" data-sortable="true"><?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?></th>

                              <th data-field="phone" data-sortable="true"><?=$this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone'?></th>

                              <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?></th>

                              <th data-field="role" data-sortable="true" data-visible="false"><?=$this->lang->line('role')?htmlspecialchars($this->lang->line('role')):'Role'?></th>

                              <th data-field="projects_count" data-sortable="true" data-visible="false"><?=$this->lang->line('projects')?htmlspecialchars($this->lang->line('projects')):'Projects'?></th>

                              <th data-field="tasks_count" data-sortable="true" data-visible="false"><?=$this->lang->line('tasks')?htmlspecialchars($this->lang->line('tasks')):'Tasks'?></th>
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
</body>
</html>
