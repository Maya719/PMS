<?php $this->load->view('includes/head'); ?>

            
<div class="card-body row">
    <div class="col-md-1 create" >
        <?php if($this->ion_auth->is_admin() ){ ?>
            <a href="#" id="modal-add-leaves-type" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create_leave_type')?$this->lang->line('create_leave_type'):'Create Leave Type'?></a>
        <?php } ?>
    </div>

    <div class="col-md-12" >
        <table class='table-striped' id='leaves_type_list'
            data-toggle="table"
            data-url="<?=base_url('settings/get_leaves_type')?>"
            data-click-to-select="true"
            data-side-pagination="server"
            data-pagination="true"
            
            data-page-list="[5, 10, 20, 50, 100, 200]"
            data-search="false" data-show-columns="true"
            data-show-refresh="false" data-trim-on-search="false"
            data-sort-name="id" data-sort-order="DESC"
            data-mobile-responsive="true"
            data-toolbar="" data-show-export="false"
            data-maintain-selected="true"
            data-export-types='["txt","excel"]'
            data-export-options='{
            "fileName": "shift-list",
            "ignoreColumn": ["state"] 
            }'
            data-query-params="queryParams">
            <thead>
            <tr>
                <th data-field="sr_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('sr_no'):'#'?></th>
                <th data-field="name" data-sortable="false"><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></th>
                <th data-field="total_leaves" data-sortable="false"><?=$this->lang->line('total_leaves')?$this->lang->line('total_leaves'):'Total Leaves'?></th>
                <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<form action="<?=base_url('settings/leaves_type_create')?>" method="POST" class="modal-part" id="modal-add-leaves-type-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
    
    <div class="form-group">
        <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required="">
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('total_leaves')?$this->lang->line('total_leaves'):'Total Leaves'?><span class="text-danger">*</span></label>
        <input type="number" name="total_leaves" class="form-control" required="">
    </div>

</form>

<form action="<?=base_url('settings/leaves_type_edit')?>" method="POST" class="modal-part" id="modal-edit-leaves-type-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">

    <input type="hidden" name="update_id" id="update_id">

    <div class="form-group">
        <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control" required="">
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('total_leaves')?$this->lang->line('total_leaves'):'Total Leaves'?><span class="text-danger">*</span></label>
        <input type="number" name="total_leaves" id="total_leaves" class="form-control" required="">
    </div>

</form>

<div id="modal-edit-leaves-type"></div>

<script>
  function queryParams(p){
    return {
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
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




