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
            <?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?> 
              <a href="#" id="modal-add-support" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?> <?=$this->lang->line('ticket')?htmlspecialchars($this->lang->line('ticket')):'Ticket'?></a>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="form-group col-md-2">
                <select class="form-control" id="support_filter_status">
                  <option value=""><?=$this->lang->line('select_status')?htmlspecialchars($this->lang->line('select_status')):'Select Status'?></option>
                  <?php if($this->ion_auth->in_group(3)){ ?>
                    <option value="1"><?=$this->lang->line('received')?htmlspecialchars($this->lang->line('received')):'Received'?></option>
                  <?php }else{ ?>
                    <option value="1"><?=$this->lang->line('sent')?htmlspecialchars($this->lang->line('sent')):'Sent'?></option>
                  <?php } ?>
                  <option value="2"><?=$this->lang->line('opened_and_resolving')?htmlspecialchars($this->lang->line('opened_and_resolving')):'Opened and Resolving'?></option>
                  <option value="3"><?=$this->lang->line('resolved_and_closed')?htmlspecialchars($this->lang->line('resolved_and_closed')):'Resolved and Closed'?></option>
                </select>
              </div>

              <?php if($this->ion_auth->in_group(3)){ ?>
                <div class="form-group col-md-2">
                  <select class="form-control select2" id="support_filter_user">
                    <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                    <?php foreach($system_users as $system_user){ ?>
                    <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                    <?php } ?>
                  </select>
                </div>
              <?php } ?>

              <div class="form-group col-md-3">
                <input type="text" name="from" id="from" class="form-control">
              </div>
              <div class="form-group col-md-3">
                <input type="text" name="too" id="too" class="form-control">
              </div>
              <div class="form-group col-md-2">
                <button type="button" class="btn btn-primary btn-lg btn-block" id="filter">
                  <?=$this->lang->line('filter')?$this->lang->line('filter'):'Filter'?>
                </button>
              </div>
            
            </div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='support_list'
                          data-toggle="table"
                          data-url="<?=base_url('support/get_support')?>"
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
                          data-query-params="queryParams">
                          <thead>
                            <tr>

                              <th data-field="ticket_id" data-sortable="true"><?=$this->lang->line('ticket')?htmlspecialchars($this->lang->line('ticket')):'Ticket'?> <?=$this->lang->line('id')?htmlspecialchars($this->lang->line('id')):'ID'?></th>

                              <?php if($this->ion_auth->in_group(3)){ ?>
                                <th data-field="user" data-sortable="false"><?=$this->lang->line('users')?htmlspecialchars($this->lang->line('users')):'Users'?></th>
                              <?php } ?>

                              <th data-field="subject" data-sortable="true"><?=$this->lang->line('ticket')?htmlspecialchars($this->lang->line('ticket')):'Ticket'?></th>

                              <th data-field="created" data-sortable="true"><?=$this->lang->line('created')?htmlspecialchars($this->lang->line('created')):'Created'?></th>

                              <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>

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


<form action="<?=base_url('support/create')?>" method="POST" class="modal-part" id="modal-add-support-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">

  <?php if($this->ion_auth->in_group(3)){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></label>
      <select name="user_id" class="form-control select2">
        <?php foreach($system_users as $system_user){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } ?>
      </select>
    </div>

    <div class="form-group">
      <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></label>
      <select name="status" class="form-control">
        <option value="1"><?=$this->lang->line('received')?htmlspecialchars($this->lang->line('received')):'Received'?></option>
        <option value="2"><?=$this->lang->line('opened_and_resolving')?htmlspecialchars($this->lang->line('opened_and_resolving')):'Opened and Resolving'?></option>
        <option value="3"><?=$this->lang->line('resolved_and_closed')?htmlspecialchars($this->lang->line('resolved_and_closed')):'Resolved and Closed'?></option>
      </select>
    </div>

  <?php } ?>

  <div class="form-group">
    <label><?=$this->lang->line('ticket')?htmlspecialchars($this->lang->line('ticket')):'Ticket'?> <?=$this->lang->line('subject')?htmlspecialchars($this->lang->line('subject')):'Subject'?></label>
    <input type="text" name="subject"  class="form-control">
  </div>

</form>


<form action="<?=base_url('support/edit')?>" method="POST" class="modal-part" id="modal-edit-support-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">

  <?php if($this->ion_auth->in_group(3)){ ?>
    <div class="form-group">
      <label><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></label>
      <select name="user_id" id="user_id" class="form-control select2">
        <?php foreach($system_users as $system_user){ ?>
        <option value="<?=$system_user->id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } ?>
      </select>
    </div>

    <div class="form-group">
      <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></label>
      <select name="status" id="status" class="form-control">
        <option value="1"><?=$this->lang->line('received')?htmlspecialchars($this->lang->line('received')):'Received'?></option>
        <option value="2"><?=$this->lang->line('opened_and_resolving')?htmlspecialchars($this->lang->line('opened_and_resolving')):'Opened and Resolving'?></option>
        <option value="3"><?=$this->lang->line('resolved_and_closed')?htmlspecialchars($this->lang->line('resolved_and_closed')):'Resolved and Closed'?></option>
      </select>
    </div>

  <?php } ?>

  <div class="form-group">
    <label><?=$this->lang->line('ticket')?htmlspecialchars($this->lang->line('ticket')):'Ticket'?> <?=$this->lang->line('subject')?htmlspecialchars($this->lang->line('subject')):'Subject'?></label>
    <input type="text" name="subject" id="subject" class="form-control">
  </div>

</form>

<div id="modal-edit-support"></div>

<?php $this->load->view('includes/js'); ?>
<script>
  function queryParams(p){
      return {
        "status": $('#support_filter_status').val(),
        "user_id": $('#support_filter_user').val(),
        "from": $('#from').val(),
        "too": $('#too').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
      };
  }

</script>

<script>
$('#filter').on('click',function(e){
  $('#support_list').bootstrapTable('refresh');
});

$(document).ready(function(){
  $('#from').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
  });

  $('#too').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
  });
});
</script>

</body>
</html>
