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
              <?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?>
              </div>
            </div>
          </div>
          <div class="section-body">
            
            <div class="row">

                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body">
                        <table class='table-striped' id='video_meetings_list'
                          data-toggle="table"
                          data-url="<?=base_url('meetings/get_meetings')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="false"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="true" data-show-columns="true"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="DESC"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="true"
                          data-maintain-selected="true"
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "video_meetings-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?></th>
                              <th data-field="starting_date_and_time" data-sortable="true"><?=$this->lang->line('starting_time')?$this->lang->line('starting_time'):'Starting Time'?></th>
                              <th data-field="duration" data-sortable="true"><?=$this->lang->line('duration')?$this->lang->line('duration'):'Duration (Minutes)'?></th>
                              <th data-field="created_user" data-sortable="false" data-visible="false"><?=$this->lang->line('scheduled_by')?$this->lang->line('scheduled_by'):'Scheduled By'?></th>
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
    $('#video_meetings_list').bootstrapTable('refresh');
  });
</script>
</body>
</html>
