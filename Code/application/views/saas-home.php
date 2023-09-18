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
            <h1><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></h1>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="card card-primary card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4><?=$this->lang->line('earnings')?$this->lang->line('earnings'):'Earnings'?></h4>
                  </div>
                  <div class="card-body">
                  <?=htmlspecialchars(get_saas_currency('currency_symbol'))?><?=htmlspecialchars(get_earnings())?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-primary card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4><?=$this->lang->line('orders')?$this->lang->line('orders'):'Orders'?></h4>
                  </div>
                  <div class="card-body">
                  <?=htmlspecialchars(get_count('id','orders',''))?>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="card card-primary card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-money-bill-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4><?=$this->lang->line('subscribers')?htmlspecialchars($this->lang->line('subscribers')):'Subscribers'?></h4>
                  </div>
                  <div class="card-body">
                  <?=get_count('saas_id','users','saas_id != 1 GROUP BY saas_id')?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h4><?=$this->lang->line('last_days_earning')?$this->lang->line('last_days_earning'):'Last 30 days earning'?></h4>
                </div>
                <div class="card-body">
                  <canvas id="all_in_one" height="auto"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h4><?=$this->lang->line('subscribers_statistics')?htmlspecialchars($this->lang->line('subscribers_statistics')):'Subscriber Statistics'?></h4>
                </div>
                <div class="card-body">
                  <canvas id="users_chart" height="auto"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h4><?=$this->lang->line('subscription_statistics')?htmlspecialchars($this->lang->line('subscription_statistics')):'Subscription Statistics'?></h4>
                </div>
                <div class="card-body">
                  <canvas id="users_plan" height="auto"></canvas>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<?php


$users_values = array(count($this->ion_auth->where('users.active', 1)->where('users.id = users.saas_id')->users(1)->result()),count($this->ion_auth->where('users.active', 0)->where('users.id = users.saas_id')->users(1)->result()),get_count('id','users_plans','plan_id=1'),get_count('id','users_plans','plan_id!=1'));

if($transaction_chart){
  foreach($transaction_chart as $transaction){
    $tmpT[] = htmlspecialchars( format_date($transaction['date'],system_date_format()));
    $tmpTV[] = htmlspecialchars($transaction['amount']);
  }
}else{
  $tmpT[] = '';
  $tmpTV[] = '';
}

$tmpP[] = $this->lang->line('expired')?$this->lang->line('expired'):'Expired';
$tmpPV[] = get_count('id','users_plans','expired=0');
foreach($plans as $plan){
  $tmpP[] = htmlspecialchars($plan['title']);
  $tmpPV[] = get_count('id','users_plans','expired=1 AND plan_id='.htmlspecialchars($plan['id']));
}

?>

<script>
  users_values = '<?=json_encode($users_values)?>';
  plans = '<?=json_encode($tmpP)?>';
  plans_values = '<?=json_encode($tmpPV)?>';
  trans = '<?=json_encode($tmpT)?>';
  trans_values = '<?=json_encode($tmpTV)?>';
  Users_Statistics = '<?=$this->lang->line('subscribers_statistics')?$this->lang->line('subscribers_statistics'):'Subscriber Statistics'?>';
  Active = '<?=$this->lang->line('active')?$this->lang->line('active'):'Active'?>';
  Deactive = '<?=$this->lang->line('deactive')?$this->lang->line('deactive'):'Deactive'?>';
  Free = '<?=$this->lang->line('free')?$this->lang->line('free'):'Free'?>';
  Paid = '<?=$this->lang->line('paid')?$this->lang->line('paid'):'Paid'?>';
</script>

<?php $this->load->view('includes/js'); ?>
<script src="<?=base_url('assets/js/page/saas-home.js')?>"></script>
</body>
</html>
