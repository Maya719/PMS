<?php $this->load->view('includes/head'); ?>

            
<div class="card-body row">
    <div class="col-md-1 create" >
        <?php if($this->ion_auth->is_admin() ){ ?>
            <a href="#" id="modal-add-leaves" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
        <?php } ?>
    </div>

    <div class="col-md-12" >
        <table class='table-striped' id='department_list'
            data-toggle="table"
            data-url="<?=base_url('department/get_departments')?>"
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
            data-export-types='["txt","excel"]'
            data-export-options='{
            "fileName": "leaves-list",
            "ignoreColumn": ["state"] 
            }'
            data-query-params="queryParams">
            <thead>
            <tr>
                <th data-field="s_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('s_no'):'#'?></th>
                <th data-field="company_name" data-sortable="true"><?=$this->lang->line('company_name')?$this->lang->line('company_name'):'Company Name'?></th>
                <th data-field="department_name" data-sortable="true"><?=$this->lang->line('department_name')?$this->lang->line('department_name'):'Department Name'?></th>
                <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<form action="<?=base_url('department/create')?>" method="POST" class="modal-part" id="modal-add-leaves-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
    <div class="row" id="dates">
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('company_name')?$this->lang->line('company_name'):'Company name'?><span class="text-danger">*</span></label>
          <input type="text" name="company_name" class="form-control" value="<?= company_name() ?>" disabled>
      </div>
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('department_name')?$this->lang->line('department_name'):'Department Name'?><span class="text-danger">*</span></label>
          <input type="text" name="department_name" class="form-control" required="">
      </div>
    </div>
  </div>
  
  
</form>

<!-- update/edit Model -->
<form action="<?=base_url('department/edit')?>" method="POST" class="modal-part" id="modal-edit-department-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">
  <div class="row" id="dates">
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('company_name')?$this->lang->line('company_name'):'company Name'?><span class="text-danger">*</span></label>
          <input type="text" name="company_name" id="company_name" class="form-control" value="<?= company_name() ?>" disabled>
      </div>
      <div class="form-group col-md-12">
          <label><?=$this->lang->line('department_name')?$this->lang->line('department_name'):'Department Name'?><span class="text-danger">*</span></label>
          <input type="text" name="department_name" id="department_name" class="form-control" required="">
      </div>
  </div>
</form>

<div id="modal-edit-department"></div>

<script>
  function queryParams(p) {
    return {
      "limit": p.limit,
      "sort": p.sort,
      "order": p.order,
      "offset": p.offset,
      "search": p.search
    };
  }
</script>

<style>
.left-pad{
  padding-left:0.5em !important;
  padding-right:0.5em !important;
  padding-bottom:0.5em !important;
  padding-top:0.5em !important;
}
.create {
    position: absolute;
    top: 0;
    left: 0;
    margin-top: 20px;
    margin-left: 15px;
    z-index: 100; /* Set a higher value for z-index */
}
</style>




