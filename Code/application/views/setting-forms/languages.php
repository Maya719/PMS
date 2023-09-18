<form action="<?=base_url('languages/create')?>" method="POST" id="language-form" enctype="multipart/form-data">

  <div class="card-body row">
    
    <input type="text" name="language_lang" id="language" class="form-control col-md-6 m-1" placeholder="<?=$this->lang->line('language_name')?$this->lang->line('language_name'):'Language name'?>" required>
    <input type="text" name="short_code_lang" id="short_code" class="form-control col-md-2 m-1" placeholder="<?=$this->lang->line('short_code')?$this->lang->line('short_code'):'Short Code'?>" required>

    <select name="active_lang" class="form-control col-md-2 m-1 ">
      <option value="0">NO RTL</option>
      <option value="1">RTL</option>
    </select>

    <button type="submit" class="btn btn-primary savebtn col-md-1 m-1"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></button> 

    <div class="col-md-12">
      <table class='table-striped' id='languages_list'
        data-toggle="table"
        data-url="<?=base_url('languages/get_languages')?>"
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
          "fileName": "languages-list",
          "ignoreColumn": ["state"] 
        }'
        data-query-params="queryParams">
        <thead>
          <tr>
            <th data-field="language" data-sortable="true"><?=$this->lang->line('languages')?$this->lang->line('languages'):'Languages'?></th>
            <th data-field="short_code" data-sortable="true"><?=$this->lang->line('short_code')?$this->lang->line('short_code'):'Short Code'?></th>
            <th data-field="active" data-sortable="true"><?=$this->lang->line('is_rtl')?htmlspecialchars($this->lang->line('is_rtl')):'Is RTL'?></th>
            <th data-field="status" data-sortable="true"><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></th>
            <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
          </tr>
        </thead>
      </table>
    </div>


  </div>
  <div class="result"></div>
</form>