<form action="<?=base_url('settings/save-taxes-setting')?>" method="POST" id="taxes-form" enctype="multipart/form-data">

  <div class="card-body row">
    
    <div class="p-1 d-flex col-md-6">
      <input type="hidden" name="update_id" id="update_id">
      <input type="text" name="title" id="title" class="form-control" placeholder="<?=$this->lang->line('tax_name')?$this->lang->line('tax_name'):'Tax Name'?>" required>
    </div>
    <div class="p-1 d-flex col-md-6">
      <input type="number" name="tax" id="tax" class="form-control" placeholder="<?=$this->lang->line('tax_rate')?$this->lang->line('tax_rate'):'Tax Rate(%)'?>" required>
      <button type="submit" class="btn btn-primary ml-1 savebtn">
      <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>
      </button>
      <button type="reset" value="Reset" id="tax_cancel" class="btn btn-danger d-none ml-1">
      X
      </button>
    </div>
    <div class="col-md-12">
      <table class='table-striped' id='taxes_list'
        data-toggle="table"
        data-url="<?=base_url('settings/get_taxes')?>"
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
          "fileName": "taxes-list",
          "ignoreColumn": ["state"] 
        }'
        data-query-params="queryParams">
        <thead>
          <tr>
            <th data-field="title" data-sortable="true"><?=$this->lang->line('tax_name')?$this->lang->line('tax_name'):'Tax Name'?></th>
            <th data-field="tax" data-sortable="true"><?=$this->lang->line('tax_rate')?$this->lang->line('tax_rate'):'Tax Rate(%)'?></th>
            <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
          </tr>
        </thead>
      </table>
    </div>


  </div>
  <div class="result"></div>
</form>