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
              <?=$this->lang->line('estimates')?$this->lang->line('estimates'):'Estimates'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active">
                <a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a>
              </div>
              <div class="breadcrumb-item">
                <a href="<?=base_url('estimates')?>"><?=$this->lang->line('estimates')?$this->lang->line('estimates'):'Estimates'?></a>
              </div>
              <div class="breadcrumb-item">
                <?=htmlspecialchars($estimate[0]['estimate_id'])?>
              </div>
            </div>
          </div>
          
          <div class="section-body">

          
            <div class="row mb-4">
              <div class="col-lg-12">
              <?php if($this->ion_auth->in_group(4) && $estimate[0]['status'] == 1){ ?>
              <a href="<?=base_url('estimates/accept/'.$estimate[0]['id'])?>" class="btn btn-success btn-icon icon-left"><i class="fas fa-check"></i> <?=$this->lang->line('accept')?$this->lang->line('accept'):'Accept'?></a>
              <a href="<?=base_url('estimates/reject/'.$estimate[0]['id'])?>" class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i> <?=$this->lang->line('reject')?$this->lang->line('reject'):'Reject'?></a>
              <?php } ?>
              <a href="<?=base_url('estimates/generate-pdf/'.$estimate[0]['id'])?>" class="btn btn-warning btn-icon icon-left" target="_blank"><i class="fas fa-print"></i> <?=$this->lang->line('print')?$this->lang->line('print'):'Print'?></a>
              </div>
            </div>

            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                      <h2><?=$this->lang->line('estimate')?$this->lang->line('estimate'):'Estimate'?></h2>
                      <div class="invoice-number"><?=htmlspecialchars($estimate[0]['estimate_id'])?></div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <h5 class="text-primary text-uppercase"><?=$estimate_from->company_name?htmlspecialchars($estimate_from->company_name):htmlspecialchars($estimate[0]['from_user'])?></h5>
                          <?=$estimate_from->address?htmlspecialchars($estimate_from->address):''?><br>
                          <?=$estimate_from->city?htmlspecialchars($estimate_from->city):''?>, <?=$estimate_from->state?htmlspecialchars($estimate_from->state):''?><br>
                          <?=$estimate_from->country?htmlspecialchars($estimate_from->country):''?> <?=$estimate_from->zip_code?htmlspecialchars($estimate_from->zip_code):''?><br>
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <?=$this->lang->line('sent_to')?$this->lang->line('sent_to'):'Sent To'?>:<br>
                          <strong><?=$estimate_to->company_name?htmlspecialchars($estimate_to->company_name):htmlspecialchars($estimate[0]['to_user'])?></strong><br>
                          <?=$estimate_to->address?htmlspecialchars($estimate_to->address):''?><br>
                          <?=$estimate_to->city?htmlspecialchars($estimate_to->city):''?>, <?=$estimate_to->state?htmlspecialchars($estimate_to->state):''?><br>
                          <?=$estimate_to->country?htmlspecialchars($estimate_to->country):''?> <?=$estimate_to->zip_code?htmlspecialchars($estimate_to->zip_code):''?><br>
                        </address>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong><?=$this->lang->line('due_date')?$this->lang->line('due_date'):'Due Date'?>:</strong> <?=htmlspecialchars(format_date($estimate[0]['due_date'],system_date_format()))?><br>
                          <strong><?=$this->lang->line('status')?$this->lang->line('status'):'Status'?>:</strong>
                          <?php
                            if($estimate[0]['status'] == 0){
                              echo '<span class="text-warning text-uppercase">'.($this->lang->line('draft')?$this->lang->line('draft'):'Draft').'</span>';
                            }elseif($estimate[0]['status'] == 1){
                              if($this->ion_auth->in_group(4)){
                                echo '<span class="text-info text-uppercase">'.($this->lang->line('received')?$this->lang->line('received'):'Received').'</span>';
                              }else{
                                echo '<span class="text-info text-uppercase">'.($this->lang->line('sent')?$this->lang->line('sent'):'Sent').'</span>';
                              }
                            }elseif($estimate[0]['status'] == 2){
                              echo '<span class="text-success text-uppercase">'.($this->lang->line('accepted')?$this->lang->line('accepted'):'Accepted').'</span>';
                            }elseif($estimate[0]['status'] == 3){
                              echo '<span class="text-danger text-uppercase">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</span>';
                            }
                          ?>
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong><?=$this->lang->line('estimate_date')?$this->lang->line('estimate_date'):'Estimate Date'?>:</strong><br>
                          <?=htmlspecialchars(format_date($estimate[0]['invoice_date'],system_date_format()))?>
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
                        <?php foreach($items as $key => $item){ ?>
                          <tr>
                            <td><?=htmlspecialchars($key+1)?></td>
                            <td><?=htmlspecialchars($item['name'])?></td>
                            <td class="text-right"><?=htmlspecialchars($item['price'])?></td>
                          </tr>
                        <?php } ?>
                      </table>
                    </div>
                    <div class="row mt-4">
                      <div class="col-lg-7">
                        <?php if($estimate[0]['note']){ ?>
                          <strong><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?>:</strong> <?=htmlspecialchars($estimate[0]['note'])?>
                        <?php } ?>
                      </div>
                      <div class="col-lg-5 text-right">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name"><?=$this->lang->line('subtotal')?$this->lang->line('subtotal'):'Subtotal'?> (<?=htmlspecialchars(get_currency('currency_symbol'))?>)</div>
                          <div class="invoice-detail-value"><?=htmlspecialchars($estimate[0]['amount'])?></div>
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
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>
<?php $this->load->view('includes/js'); ?>

</body>
</html>
