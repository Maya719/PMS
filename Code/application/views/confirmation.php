<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
              <a href="<?=base_url()?>">
                <img src="<?=base_url('assets/uploads/logos/'.full_logo());?>" alt="logo" width="40%">
              </a>
            </div>

            <div class="card card-primary">
              <div class="card-header d-flex justify-content-center"><h4><?=$this->lang->line('confirmation')?htmlspecialchars($this->lang->line('confirmation')):'Confirmation'?></h4></div>

              <div class="card-body">
                <div class="row">
                  <?php if(email_activation()){ ?>
                    <div class="form-group text-center text-danger col-md-12">
                      <?=$this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account')?$this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account'):'Please check your inbox and confirm your email address to activate your account.'?>
                    </div>
                  <?php } ?>

                  <div class="form-group text-center col-md-12">
                    <?=$this->lang->line('your_account_has_been_successfully_created')?htmlspecialchars($this->lang->line('your_account_has_been_successfully_created')):'Your account has been successfully created.'?>
                  </div>


                  <div class="form-group col-md-12">
                    <a href="<?=base_url('auth')?>" class="savebtn btn btn-primary btn-lg btn-block text-white" >
                    <?=$this->lang->line('login')?htmlspecialchars($this->lang->line('login')):'Login'?>
                    </a>
                  </div>

              </div>
            </div>
            <div class="simple-footer">
              <?=htmlspecialchars(footer_text())?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<script>
  site_key = '<?php echo get_google_recaptcha_site_key(); ?>';
</script>

<?php $recaptcha_site_key = get_google_recaptcha_site_key(); if($recaptcha_site_key){ ?>
  <script src="https://www.google.com/recaptcha/api.js?render=<?=htmlspecialchars($recaptcha_site_key)?>"></script>
<?php } ?>

<?php $this->load->view('includes/js'); ?>

</body>
</html>
