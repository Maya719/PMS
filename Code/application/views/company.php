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
            <?=$this->lang->line('company')?$this->lang->line('company'):'Company'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('company')?$this->lang->line('company'):'Company'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">

              <div class="col-md-12">
                <div class="card card-primary" id="settings-card">
                  <form action="<?=base_url('settings/save-company-setting')?>" method="POST" id="setting-form">
                    <div class="card-body row">

                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('company_name')?$this->lang->line('company_name'):'Company Name'?><span class="text-danger">*</span><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('this_details_will_be_used_as_billing_details')?$this->lang->line('this_details_will_be_used_as_billing_details'):"This details will be used as billing details."?>"></i></label>
                        <input type="text" name="company_name" value="<?=isset($company_details->company_name)?htmlspecialchars($company_details->company_name):''?>" class="form-control" required="">
                      </div>
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('address')?$this->lang->line('address'):'Address'?></label>
                        <input type="text" name="address" value="<?=isset($company_details->address)?htmlspecialchars($company_details->address):''?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('city')?$this->lang->line('city'):'City'?></label>
                        <input type="text" name="city" value="<?=isset($company_details->city)?htmlspecialchars($company_details->city):''?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('state')?$this->lang->line('state'):'State'?></label>
                        <input type="text" name="state" value="<?=isset($company_details->state)?htmlspecialchars($company_details->state):''?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('country')?$this->lang->line('country'):'Country'?></label>
                        <input type="text" name="country" value="<?=isset($company_details->country)?htmlspecialchars($company_details->country):''?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('zip_code')?$this->lang->line('zip_code'):'Zip Code'?></label>
                        <input type="text" name="zip_code" value="<?=isset($company_details->zip_code)?htmlspecialchars($company_details->zip_code):''?>" class="form-control">
                      </div>
                    </div>

                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
                    </div>
                    <div class="result"></div>
                  </form>


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
