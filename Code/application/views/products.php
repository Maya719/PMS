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
            <?=$this->lang->line('products')?$this->lang->line('products'):'Products'?> 
              <?php if (!$this->ion_auth->in_group(4)){ ?>
                <a href="#" id="modal-add-products" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('products')?$this->lang->line('products'):'Products'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body"> 
                        <table class='table-striped' id='products_list'
                          data-toggle="table"
                          data-url="<?=base_url('products/get_products')?>"
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
                            "fileName": "products-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="name" data-sortable="name"><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></th>
                              <th data-field="price" data-sortable="true"><?=$this->lang->line('price')?$this->lang->line('price'):'Price'?> - <?=get_currency('currency_code')?></th>
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


<form action="<?=base_url('products/create')?>" method="POST" class="modal-part" id="modal-add-products-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  
  <div class="form-group">
    <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required="">
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('price')?$this->lang->line('price'):'Price'?> - <?=get_currency('currency_code')?> <span class="text-danger">*</span></label>
    <input type="number" pattern="[0-9]" name="price" class="form-control" required="">
  </div>

</form>

<form action="<?=base_url('products/edit')?>" method="POST" class="modal-part" id="modal-edit-products-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <input type="hidden" name="update_id" id="update_id" value="">

  <div class="form-group">
    <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
    <input type="text" name="name" id="name" class="form-control" required="">
  </div>

  <div class="form-group">
    <label><?=$this->lang->line('price')?$this->lang->line('price'):'Price'?> - <?=get_currency('currency_code')?> <span class="text-danger">*</span></label>
    <input type="number" pattern="[0-9]" name="price" id="price" class="form-control" required="">
  </div>

</form>

<div id="modal-edit-products"></div>

<?php $this->load->view('includes/js'); ?>
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
</body>
</html>
