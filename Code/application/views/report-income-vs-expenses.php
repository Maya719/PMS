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
            <h1><?=$this->lang->line('income_vs_expenses')?$this->lang->line('income_vs_expenses'):'Income VS Expenses'?></h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('reports')?$this->lang->line('reports'):'Reports'?>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="form-group col-md-5">
              <input type="text" name="from" id="from" class="form-control">
            </div>
            <div class="form-group col-md-5">
              <input type="text" name="too" id="too" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <button type="button" class="btn btn-primary btn-lg btn-block" id="filter">
                <?=$this->lang->line('filter')?$this->lang->line('filter'):'Filter'?>
              </button>
            </div>

            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-body">
                  <canvas id="income-vs-expenses" height="auto"></canvas>
                </div>
              </div>
            </div>
            
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-body"> 
                  <table class='table-striped' id='get_income_vs_expenses_list'
                    data-toggle="table"
                    data-url="<?=base_url('reports/get_income_vs_expenses')?>"
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
                            "fileName": "income_vs_expenses_list"
                          }'
                    data-query-params="queryParams">
                    <thead>
                      <tr>
                        <th data-field="date" data-sortable="true" data-footer-formatter="idFormatter"><?=$this->lang->line('date')?$this->lang->line('date'):'Date'?></th>
                        <th data-field="income" data-sortable="true" data-footer-formatter="priceFormatter"><?=$this->lang->line('income')?$this->lang->line('income'):'Income'?> - <?=get_currency('currency_code')?></th>
                        <th data-field="expenses" data-sortable="true" data-footer-formatter="priceFormatter"><?=$this->lang->line('expenses')?$this->lang->line('expenses'):'Expenses'?> - <?=get_currency('currency_code')?></th>
                        <th data-field="profit" data-sortable="true" data-footer-formatter="priceFormatter"><?=$this->lang->line('profit')?$this->lang->line('profit'):'Profit'?> - <?=get_currency('currency_code')?></th>
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
function chart_load() {
  $.ajax({
    type: "POST",
    url: base_url+'reports/get_income_vs_expenses_chart', 
    data: "from="+$('#from').val()+"&too="+$('#too').val(),
    dataType: "json",
    success: function(result) 
    {	 
      dates = result['dates'];
      income = result['income'];
      expenses = result['expenses'];

      ctx = document.getElementById("income-vs-expenses").getContext('2d');
      myLineChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: dates,
              datasets: [{
                  label: 'Income - '+currency_code,
                  fill: false,
                  data: income,
                  borderColor: [
                      '#0cc500'
                  ],
                  borderWidth: 3
              },{
                  label: 'Expenses - '+currency_code,
                  fill: false,
                  data: expenses,
                  borderColor: [
                      '#dc0000'
                  ],
                  borderWidth: 3
              }]
          }
      });
    }        
  });
}
</script>

<script>
  function queryParams(p){
    return {
      "from": $('#from').val(),
      "too": $('#too').val(),
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }

  function idFormatter() {
    return 'Total'
  }
  function priceFormatter(data) {
    var field = this.field
    return data.map(function (row) {
      return +row[field];
    }).reduce(function (sum, i) {
      return sum + i;
    }, 0)
  }
</script>

<script>
$('#filter').on('click',function(e){
  $('#get_income_vs_expenses_list').bootstrapTable('refresh');
  chart_load();
});
</script>

<script>
$(document).ready(function(){
  $('#from').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
  });

  $('#too').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
  });

  chart_load();
});
</script>

</body>
</html>
