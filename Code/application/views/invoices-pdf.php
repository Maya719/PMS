<?php $this->load->view('includes/head'); ?>
<style>

body{
  color: #000000;
}

</style>
</head>
<body>
  <section class="section">
    <div class="section-body">
      <div class="invoice" style="padding: 0px;">
        <div class="invoice-print">
          <div class="row">
            <div class="col-lg-12">
              <div class="invoice-title">
                <h2><?=$this->lang->line('invoice')?$this->lang->line('invoice'):'Invoice'?></h2>
                <div class="invoice-number"><?=htmlspecialchars($invoice[0]['invoice_id'])?></div>
              </div>
              <hr style="margin-top: 40px; margin-bottom: 10px;">
              <div class="row">
                <div class="col-md-6">
                  <address>
                    <h5 class="text-uppercase" style="color: <?=theme_color()?>;" ><?=$invoice_from->company_name?htmlspecialchars($invoice_from->company_name):htmlspecialchars($invoice[0]['from_user'])?></h5>
                    <?=$invoice_from->address?htmlspecialchars($invoice_from->address):''?><br>
                    <?=$invoice_from->city?htmlspecialchars($invoice_from->city):''?>, <?=$invoice_from->state?htmlspecialchars($invoice_from->state):''?><br>
                    <?=$invoice_from->country?htmlspecialchars($invoice_from->country):''?> <?=$invoice_from->zip_code?htmlspecialchars($invoice_from->zip_code):''?><br>
                  </address>
                </div>
                <div class="col-md-6 text-md-right text-right" >
                  <address class="float-right">
                    <?=$this->lang->line('billed_to')?$this->lang->line('billed_to'):'Billed To'?>:<br>
                    <strong class="text-uppercase"><?=$invoice_to->company_name?htmlspecialchars($invoice_to->company_name):htmlspecialchars($invoice[0]['to_user'])?></strong><br>
                    <?=$invoice_to->address?htmlspecialchars($invoice_to->address):''?><br>
                    <?=$invoice_to->city?htmlspecialchars($invoice_to->city):''?>, <?=$invoice_to->state?htmlspecialchars($invoice_to->state):''?><br>
                    <?=$invoice_to->country?htmlspecialchars($invoice_to->country):''?> <?=$invoice_to->zip_code?htmlspecialchars($invoice_to->zip_code):''?><br>
                  </address>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <address>
                    <strong><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?>:</strong> <?=htmlspecialchars(format_date($invoice[0]['due_date'],system_date_format()))?><br>
                    <strong><?=$this->lang->line('payment_status')?$this->lang->line('payment_status'):'Payment Status'?>:</strong>
                    <?php
                      if($invoice[0]['status'] == 0){
                        echo '<span class="text-info text-uppercase">'.($this->lang->line('due')?htmlspecialchars($this->lang->line('due')):'Due').'</span>';
                      }elseif($invoice[0]['status'] == 1){
                        echo '<span class="text-success text-uppercase">'.($this->lang->line('paid')?htmlspecialchars($this->lang->line('paid')):'Paid').'</span>';
                      }elseif($invoice[0]['status'] == 2){
                        echo '<span class="text-danger text-uppercase">'.($this->lang->line('overdue')?htmlspecialchars($this->lang->line('overdue')):'Overdue').'</span>';
                      }
                    ?>
                  </address>
                </div>
                <div class="col-md-6 text-md-right text-right" >
                  <address class="float-right">
                    <strong><?=$this->lang->line('invoice_date')?$this->lang->line('invoice_date'):'Invoice Date'?>:</strong><br>
                    <?=htmlspecialchars(format_date($invoice[0]['invoice_date'],system_date_format()))?>
                  </address>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mt-4">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped table-sm">
                  <tr>
                    <th data-width="40">#</th>
                    <th><?=$this->lang->line('item')?$this->lang->line('item'):'Item'?></th>
                    <th class="text-right"><?=$this->lang->line('total')?$this->lang->line('total'):'Total'?> (<?=htmlspecialchars(get_currency('currency_symbol'))?>)</th>
                  </tr>
                  <?php $key_num = 0; foreach($items as $key => $item){ $key_num = $key_num + 1; ?>
                    <tr>
                      <td><?=htmlspecialchars($key_num)?></td>
                      <td><?=htmlspecialchars($item['title'])?></td>
                      <td class="text-right"><?=htmlspecialchars($item['budget'])?></td>
                    </tr>
                  <?php } ?>
                  <?php foreach($products as $key => $item){ $key_num = $key_num + 1; ?>
                    <tr>
                      <td><?=htmlspecialchars($key_num)?></td>
                      <td><?=htmlspecialchars($item['name'])?></td>
                      <td class="text-right"><?=htmlspecialchars($item['price'])?></td>
                    </tr>
                  <?php } ?>
                </table>
              </div>
              <div class="row mt-4">
                <div class="col-lg-7">
                  <?php if($invoice[0]['note']){ ?>
                    <strong><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?>:</strong> <?=htmlspecialchars($invoice[0]['note'])?>
                  <?php } ?>
                </div>
                <div class="col-lg-5 text-right">
                  <div class="invoice-detail-item">
                    <div class="invoice-detail-name">
                      <?=$this->lang->line('subtotal')?$this->lang->line('subtotal'):'Subtotal'?>: 
                      <strong class="text-dark"><?=htmlspecialchars(get_currency('currency_symbol'))?><?=htmlspecialchars($invoice[0]['amount'])?></strong>
                    </div>
                    
                  </div>
                  <?php if($taxes){ foreach($taxes as $tax){ ?>
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name"><?=htmlspecialchars($tax['title'])?> (<?=htmlspecialchars($tax['tax'])?>%):
                      <strong class="text-dark"><?=htmlspecialchars(get_currency('currency_symbol'))?><?=htmlspecialchars($tax['tax_amount'])?></strong>
                      </div>
                    </div> 
                  <?php } } ?>
                  <hr class="mt-2 mb-2">
                  <div class="invoice-detail-item">
                    <div class="invoice-detail-value invoice-detail-value-lg"><?=$this->lang->line('total')?$this->lang->line('total'):'Total'?>: <?=htmlspecialchars(get_currency('currency_symbol'))?><?=htmlspecialchars($final_total)?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
        
<!-- General JS Scripts -->
<script src="<?=base_url('assets/modules/jquery.min.js')?>"></script>
<script src="<?=base_url('assets/modules/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('assets/js/stisla.js')?>"></script>
 
</body>
</html>
