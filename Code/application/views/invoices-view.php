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
              <?=$this->lang->line('invoices')?$this->lang->line('invoices'):'Invoices'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active">
                <a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a>
              </div>
              <div class="breadcrumb-item">
                <a href="<?=base_url('invoices')?>"><?=$this->lang->line('invoices')?$this->lang->line('invoices'):'Invoices'?></a>
              </div>
              <div class="breadcrumb-item">
                <?=htmlspecialchars($invoice[0]['invoice_id'])?>
              </div>
            </div>
          </div>
          
          <div class="section-body">

            <div class="row mb-4">
              <div class="col-lg-12">
                <?php if($this->ion_auth->in_group(4) && $invoice[0]['status'] != 1 && ((get_stripe_secret_key(true) && get_stripe_publishable_key(true) || get_razorpay_key_id(true) || get_offline_bank_transfer(true)))){ ?>
                  <a href="#" class="btn btn-primary btn-icon icon-left payment-button" data-amount="<?=htmlspecialchars($final_total)?>" data-id="<?=htmlspecialchars($invoice[0]['id'])?>"><i class="fas fa-credit-card"></i> <?=$this->lang->line('process_payment')?$this->lang->line('process_payment'):'Process Payment'?></a>
                <?php } ?>
                <a href="<?=base_url('invoices/generate-pdf/'.$invoice[0]['id'])?>" class="btn btn-danger btn-icon icon-left" target="_blank"><i class="fas fa-print"></i> <?=$this->lang->line('print')?$this->lang->line('print'):'Print'?></a>
              </div>
            </div>
              
            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                      <h2><?=$this->lang->line('invoice')?$this->lang->line('invoice'):'Invoice'?></h2>
                      <div class="invoice-number"><?=htmlspecialchars($invoice[0]['invoice_id'])?></div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <h5 class="text-primary text-uppercase"><?=$invoice_from->company_name?htmlspecialchars($invoice_from->company_name):htmlspecialchars($invoice[0]['from_user'])?></h5>
                          <?=$invoice_from->address?htmlspecialchars($invoice_from->address):''?><br>
                          <?=$invoice_from->city?htmlspecialchars($invoice_from->city):''?>, <?=$invoice_from->state?htmlspecialchars($invoice_from->state):''?><br>
                          <?=$invoice_from->country?htmlspecialchars($invoice_from->country):''?> <?=$invoice_from->zip_code?htmlspecialchars($invoice_from->zip_code):''?><br>
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <?=$this->lang->line('billed_to')?$this->lang->line('billed_to'):'Billed To'?>:<br>
                          <strong><?=$invoice_to->company_name?htmlspecialchars($invoice_to->company_name):htmlspecialchars($invoice[0]['to_user'])?></strong><br>
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
                              echo '<span class="text-info text-uppercase">'.($this->lang->line('due')?$this->lang->line('due'):'Due').'</span>';
                            }elseif($invoice[0]['status'] == 1){
                              echo '<span class="text-success text-uppercase">'.($this->lang->line('paid')?htmlspecialchars($this->lang->line('paid')):'Paid').'</span>';
                            }elseif($invoice[0]['status'] == 2){
                              echo '<span class="text-danger text-uppercase">'.($this->lang->line('overdue')?htmlspecialchars($this->lang->line('overdue')):'Overdue').'</span>';
                            }
                          ?>
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
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
                      <table class="table table-striped table-md">
                        <tr>
                          <th data-width="40">#</th>
                          <th><?=$this->lang->line('item')?$this->lang->line('item'):'Item'?></th>
                          <th class="text-right"><?=$this->lang->line('total')?$this->lang->line('total'):'Total'?> (<?=htmlspecialchars(get_currency('currency_symbol'))?>)</th>
                        </tr>

                        <?php
                        $key_num = 0;
                        foreach($items as $key => $item){ 
                          $key_num = $key_num + 1;
                          ?>
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
                          <div class="invoice-detail-name"><?=$this->lang->line('subtotal')?$this->lang->line('subtotal'):'Subtotal'?> (<?=htmlspecialchars(get_currency('currency_symbol'))?>)</div>
                          <div class="invoice-detail-value"><?=htmlspecialchars($invoice[0]['amount'])?></div>
                        </div>
                        <?php if($taxes){ foreach($taxes as $tax){ ?>
                          <div class="invoice-detail-item">
                            <div class="invoice-detail-name"><?=htmlspecialchars($tax['title'])?> (<?=htmlspecialchars($tax['tax'])?>%)</div>
                            <div class="invoice-detail-value"><?=htmlspecialchars(get_currency('currency_symbol'))?><?=htmlspecialchars($tax['tax_amount'])?></div>
                          </div> 
                        <?php } } ?>
                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name"><?=$this->lang->line('total')?$this->lang->line('total'):'Total'?> (<?=htmlspecialchars(get_currency('currency_symbol'))?>)</div>
                          <div class="invoice-detail-value invoice-detail-value-lg"><?=htmlspecialchars($final_total)?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row d-none" id="payment-div">
            <div id="paypal-button" class="col-md-8 mx-auto paymet-box"></div>
            <?php if(get_stripe_secret_key(true) && get_stripe_publishable_key(true)){ ?>
              <button id="stripe-button" class="col-md-8 btn mx-auto paymet-box">
                <img src="<?=base_url('assets/img/stripe.png')?>" width="14%" alt="Stripe">
              </button>
            <?php } ?>
            <?php if(get_razorpay_key_id(true)){ ?>
              <button id="razorpay-button" class="col-md-8 btn mx-auto paymet-box">
                  <img src="<?=base_url('assets/img/razorpay.png')?>" width="27%" alt="Stripe">
              </button>
            <?php } ?>
            <?php if(get_paystack_public_key(true)){ ?>
              <button id="paystack-button" class="col-md-8 btn mx-auto paymet-box">
                <img src="<?=base_url('assets/img/paystack.png')?>" width="24%" alt="Paystack">
              </button>
            <?php } ?>
            <?php if(get_offline_bank_transfer(true)){ ?>

              <div id="accordion" class="col-md-8 paymet-box mx-auto">
                <div class="accordion mb-0">
                  <div class="accordion-header text-center" role="button" data-toggle="collapse" data-target="#panel-body-3">
                    <h4><?=$this->lang->line('offline_bank_transfer')?$this->lang->line('offline_bank_transfer'):'Offline / Bank Transfer'?></h4>
                  </div>
                  <div class="accordion-body collapse" id="panel-body-3" data-parent="#accordion">
                    <p class="mb-0"><?=get_bank_details(true)?></p>

                    <form action="<?=base_url('invoices/order-completed/')?>" method="POST" id="bank-transfer-form">
                      <div class="card-footer bg-whitesmoke">
                        <div class="form-group">
                          <label><?=$this->lang->line('upload_receipt')?htmlspecialchars($this->lang->line('upload_receipt')):'Upload Receipt'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('supported_formats')?htmlspecialchars($this->lang->line('supported_formats')):'Supported Formats: jpg, jpeg, png'?>" data-original-title="<?=$this->lang->line('supported_formats')?htmlspecialchars($this->lang->line('supported_formats')):'Supported Formats: jpg, jpeg, png'?>"></i> </label>
                          <input type="file" name="receipt" class="form-control">
                          <input type="hidden" name="payment_type" id="payment_type" value="Bank">
                          <input type="hidden" name="invoices_id" id="invoices_id">
                        </div>
                        <button class="btn btn-primary savebtn"><?=$this->lang->line('upload_and_send_for_confirmation')?htmlspecialchars($this->lang->line('upload_and_send_for_confirmation')):'Upload and Send for Confirmation'?></button>
                      </div>
                      <div class="result"></div>
                    </form>

                  </div>
                </div>
              </div>

            <?php } ?>
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>
<?php $this->load->view('includes/js'); ?>

<script>
paypal_client_id = "<?=get_payment_paypal(true)?>";
get_stripe_publishable_key = "<?=get_stripe_publishable_key(true)?>";
razorpay_key_id = "<?=get_razorpay_key_id(true)?>";
offline_bank_transfer = "<?=get_offline_bank_transfer(true)?>";
paystack_user_email_id = "<?=$this->session->userdata('email')?>";
paystack_public_key = "<?=get_paystack_public_key(true)?>";
</script>

<?php if(get_payment_paypal(true)){ ?>
<script src="https://www.paypal.com/sdk/js?client-id=<?=get_payment_paypal(true)?>&currency=<?=get_currency('currency_code')?>"></script>
<?php } ?>

<?php if(get_stripe_publishable_key(true)){ ?>
<script src="https://js.stripe.com/v3/"></script>
<?php } ?>

<script src="https://js.paystack.co/v1/inline.js"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script src="<?=base_url('assets/js/page/payments.js');?>"></script>

</body>
</html>
