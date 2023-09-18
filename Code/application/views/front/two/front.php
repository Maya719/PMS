<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <?php $this->load->view('front/meta'); ?>

  <link rel="stylesheet" href="<?=base_url('assets/modules/fontawesome/css/all.min.css')?>">
  <style>
      :root{--theme-color: <?=theme_color()?>;}
  </style>
  <link type="text/css" href="<?=base_url('assets/front/two/css/custom.css')?>" rel="stylesheet">
  <link type="text/css" href="<?=base_url('assets/front/comman.css')?>" rel="stylesheet">

  <?php $google_analytics = google_analytics(); if($google_analytics){ ?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?=htmlspecialchars($google_analytics)?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?=htmlspecialchars($google_analytics)?>');
	</script>
	<?php } ?>

</head>

<body>
  <?php $my_lang = $this->session->userdata('lang')?$this->session->userdata('lang'):default_language(); ?>

  <header class="header-global">
    <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg navbar-transparent navbar-light">
      <div class="container">
        <a class="navbar-brand mr-lg-5" href="<?=base_url()?>">
          <img src="<?=base_url('assets/uploads/logos/'.htmlspecialchars(full_logo()))?>">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar_global">
          <div class="navbar-collapse-header">
            <div class="row">
              <div class="col-6 collapse-brand">
                <a href="<?=base_url()?>">
                  <img src="<?=base_url('assets/uploads/logos/'.htmlspecialchars(full_logo()))?>">
                </a>
              </div>
              <div class="col-6 collapse-close">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>

          <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
            
            <?php if(frontend_permissions('subscription_plans')){ ?>
              <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="scroll" href="#PLANS"><?=$this->lang->line('pricing')?htmlspecialchars($this->lang->line('pricing')):'Pricing'?></a>
              </li>
            <?php } ?>

						<?php if(frontend_permissions('features') && $features){ ?>
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="scroll" href="#FEATURES"><?=$this->lang->line('features')?htmlspecialchars($this->lang->line('features')):'Features'?></a>
            </li>
            <?php } ?>

            <?php if(frontend_permissions('contact')){ ?>
              <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="scroll" href="#CONTACT"><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?></a>
              </li>
            <?php } ?>

            
          </ul>

          <ul class="navbar-nav align-items-lg-center ml-lg-auto">

            <li class="nav-item ml-lg-4">
              <a href="<?=base_url('auth')?>" target="_blank" class="btn btn-white">
                <span class="nav-link-inner"><?=$this->lang->line('login')?htmlspecialchars($this->lang->line('login')):'Login'?></span>
              </a>
              <a href="<?=base_url('auth/register')?>" target="_blank" class="btn btn-primary">
                <span class="nav-link-inner"><?=$this->lang->line('get_start')?htmlspecialchars($this->lang->line('get_start')):'Get Start'?></span>
              </a>
            </li>

            <?php $languages = get_languages('', '', 1);
              if($languages){ ?>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="dropdown" href="#" role="button">
                  <i class="fa fa-language"></i>
                </a>
                <div class="dropdown-menu">
                  <?php foreach($languages as $language){  ?>
                    <a href="<?=base_url('languages/change/'.$language['language'])?>" class="dropdown-item <?=$language['language']==$this->session->userdata('lang') || ($language['language']==default_language() && !$this->session->userdata('lang'))?'active':''?>" class="dropdown-item"><?=ucfirst($language['language'])?></a>
                  <?php } ?>
                </div>
              </li>
            <?php } ?>
            
          </ul>


        </div>
      </div>
    </nav>
  </header>
  <main>

    <?php if(frontend_permissions('home')){  
		?>
    <div class="position-relative">
      <!-- shape Hero -->
      <section class="section-shaped my-0" id="HOME">
        <div class="shape shape-style-1 shape-default shape-skew">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
        <div class="container shape-container d-flex">
          <div class="col px-0">
            <div class="row">
              <div class="col-lg-6">
                <h1 class="display-3 text-white"><?=$this->lang->line('frontend_home_title')?htmlspecialchars($this->lang->line('frontend_home_title')):'Professional Project Management tool and CRM'?></h1>
                <p class="lead  text-white"><?=$this->lang->line('frontend_home_description')?htmlspecialchars($this->lang->line('frontend_home_description')):'TimeWork pms.mobipixels.com is a perfect, robust, lightweight, superfast web application to fulfill all your Team Collaboration, Project Management and CRM needs.'?></p>
                <div class="btn-wrapper">
                  <a href="<?=base_url('auth/register')?>" class="btn btn-primary btn-icon mb-3 mb-sm-0" >
                  <?=$this->lang->line('get_start')?$this->lang->line('get_start'):'Get Start'?>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- 1st Hero Variation -->
    </div>
    <?php } ?>

    <?php if(frontend_permissions('subscription_plans')){ ?>
    <section class="section pt-lg-0 mt--200 mt-minus-300" id="PLANS">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="row row-grid">
              <?php foreach($plans as $plan){ ?>
                <div class="col-lg-4 mb-2">
                  <div class="card shadow border-0">
                    <div class="card-body text-center">
                      <h4 class="text-success text-uppercase mb-0"><?=htmlspecialchars($plan['title'])?></h4>
                      <p class="description"><span class="font-weight-bold"><?=htmlspecialchars(get_saas_currency('currency_symbol'))?><?=htmlspecialchars($plan['price'])?></span> / <?php
                        if($plan['billing_type'] == 'One Time'){
                            echo $this->lang->line('one_time')?htmlspecialchars($this->lang->line('one_time')):'One Time';
                        }elseif($plan['billing_type'] == 'Monthly'){
                            echo $this->lang->line('monthly')?htmlspecialchars($this->lang->line('monthly')):'Monthly';
                        }elseif($plan["billing_type"] == 'three_days_trial_plan'){
                            echo $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
                        }elseif($plan["billing_type"] == 'seven_days_trial_plan'){
                            echo $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
                        }elseif($plan["billing_type"] == 'fifteen_days_trial_plan'){
                            echo $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
                        }elseif($plan["billing_type"] == 'thirty_days_trial_plan'){
                            echo $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
                        }else{
                            echo $this->lang->line('yearly')?htmlspecialchars($this->lang->line('yearly')):'Yearly';
                        }
                        ?>
                      </p>
                      <hr class="m-0 mb-3">
                      <div>
                        <span class="mt-1 mb-1">
                        <?=$this->lang->line('storage')?$this->lang->line('storage'):'Storage'?> <div class="badge badge-primary"><?=$plan['storage']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['storage'].' GB')?></div><br>
                        </span>
                        <span class="mt-1 mb-1">
                        <?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?> <div class="badge badge-primary"><?=$plan['projects']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['projects'])?></div><br>
                        </span>
                        <span class="mt-1 mb-1">
                        <?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?> <div class="badge badge-primary"><?=$plan['tasks']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['tasks'])?></div><br>
                        </span>
                        <span class="mt-1 mb-1">
                        <?=$this->lang->line('users')?$this->lang->line('users'):'Users'?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('including_admins_clients_and_users')?$this->lang->line('including_admins_clients_and_users'):'Including Admins, Clients and Users.'?>"></i> <div class="badge badge-primary"><?=$plan['users']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['users'])?></div>
                        </span>
                      </div>
                      <hr class="mt-3 mb-3">
                      <div>
                        <?php
                          if($plan["modules"] != ''){

                            foreach(json_decode($plan["modules"]) as $mod_key => $mod){
                              $mod_name = '';
                              if($mod_key == 'projects'){
                              $mod_name = $this->lang->line('projects')?$this->lang->line('projects'):'Projects';
                              }elseif($mod_key == 'tasks'){
                              $mod_name = $this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks';
                              }elseif($mod_key == 'gantt'){
                              $mod_name = $this->lang->line('gantt')?$this->lang->line('gantt'):'Gantt';
                              }elseif($mod_key == 'timesheet'){
                              $mod_name = $this->lang->line('timesheet')?$this->lang->line('timesheet'):'Timesheet';
                              }elseif($mod_key == 'team_members'){
                              $mod_name = $this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members';
                              }elseif($mod_key == 'clients'){
                              $mod_name = $this->lang->line('clients')?$this->lang->line('clients'):'Clients';
                              }elseif($mod_key == 'invoices'){
                              $mod_name = $this->lang->line('invoices')?$this->lang->line('invoices'):'Invoices';
                              }elseif($mod_key == 'payments'){
                              $mod_name = $this->lang->line('payments')?$this->lang->line('payments'):'Payments';
                              }elseif($mod_key == 'expenses'){
                              $mod_name = $this->lang->line('expenses')?$this->lang->line('expenses'):'Expenses';
                              }elseif($mod_key == 'calendar'){
                              $mod_name = $this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar';
                              }elseif($mod_key == 'leaves'){
                              $mod_name = $this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves';
                              }elseif($mod_key == 'todo'){
                              $mod_name = $this->lang->line('todo')?$this->lang->line('todo'):'Todo';
                              }elseif($mod_key == 'notes'){
                              $mod_name = $this->lang->line('notes')?$this->lang->line('notes'):'Notes';
                              }elseif($mod_key == 'chat'){
                              $mod_name = $this->lang->line('chat')?$this->lang->line('chat'):'Chat';
                              }elseif($mod_key == 'leads'){
                              $mod_name = $this->lang->line('leads')?$this->lang->line('leads'):'Leads';
                              }elseif($mod_key == 'payment_gateway'){
                              $mod_name = $this->lang->line('payment_gateway')?$this->lang->line('payment_gateway'):'Payment Gateway';
                              }elseif($mod_key == 'taxes'){
                              $mod_name = $this->lang->line('taxes')?$this->lang->line('taxes'):'Taxes';
                              }elseif($mod_key == 'custom_currency'){
                              $mod_name = $this->lang->line('custom_currency')?$this->lang->line('custom_currency'):'Custom Currency';
                              }elseif($mod_key == 'user_permissions'){
                              $mod_name = $this->lang->line('user_permissions')?$this->lang->line('user_permissions'):'User Permissions';
                              }elseif($mod_key == 'notifications'){
                              $mod_name = $this->lang->line('notifications')?$this->lang->line('notifications'):'Notifications';
                              }elseif($mod_key == 'languages'){
                              $mod_name = $this->lang->line('languages')?$this->lang->line('languages'):'Languages';
                              }elseif($mod_key == 'meetings'){
                                $mod_name = $this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings';
                              }elseif($mod_key == 'estimates'){
                                $mod_name = $this->lang->line('estimates')?$this->lang->line('estimates'):'Estimates';
                              }elseif($mod_key == 'reports'){
                                $mod_name = $this->lang->line('reports')?$this->lang->line('reports'):'Reports';
                              }elseif($mod_key == 'attendance'){
                                $mod_name = $this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance';
                              }elseif($mod_key == 'support'){
                                $mod_name = $this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support';
                              }

                              if($mod_name && $mod == 1){
                                echo '<span class="badge badge-pill badge-success"><i class="fa fa-check text-success"></i> '.htmlspecialchars($mod_name).'</span><br>';
                              }elseif($mod_name){
                                echo '<span class="badge badge-pill badge-warning"><i class="fa fa-times text-danger"></i> '.htmlspecialchars($mod_name).'</span><br>';
                              }
                            }
                          }
                        ?>
                      </div>

                      <hr class="m-3">

                      <a href="<?=base_url('auth/register')?>" target="_blank" class="btn btn-primary text-uppercase"><?=$this->lang->line('get_start')?$this->lang->line('get_start'):'Get Start'?></a>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php } ?>

    <?php if(frontend_permissions('features') && $features){  ?>
    <section class="section section-shaped my-0 overflow-hidden" id="FEATURES">
      <div class="shape shape-style-3 bg-gradient-default shape-skew">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
      <div class="container pb-300">
        <div class="row text-center justify-content-center">
          <div class="col-lg-10">
            <h2 class="display-3 text-white"><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></h2>
          </div>
        </div>
        <div class="row row-grid mt-5">
          <?php if($features){ foreach($features as $feature){ ?>
            <div class="col-lg-4">
              <div class="icon icon-lg icon-shape bg-gradient-white shadow rounded-circle text-primary">
                <i class="<?=isset($feature['icon'])?htmlspecialchars($feature['icon']):'fa fa-fire'?> text-primary"></i>
              </div>
              <h5 class="text-white mt-3"><?=isset($feature['title'])?htmlspecialchars($feature['title']):''?></h5>
              <p class="text-white mt-3"><?=isset($feature['description'])?htmlspecialchars($feature['description']):''?></p>
            </div>
          <?php } } ?>
        </div>
      </div>
    </section>
    <?php } ?>

    <?php if(frontend_permissions('contact')){ ?>
    <section class="section section-lg pt-lg-0 section-contact-us pb-0 mt-5" id="CONTACT">
      <div class="container">
        <div class="row justify-content-center mt--300">
          <div class="col-lg-8">
            <div class="card bg-gradient-secondary shadow">
              <div class="card-body p-lg-5">
                <h4 class="mb-1"><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?></h4>
                <form id="front_contact_form" method="POST" action="<?=base_url('front/send-mail')?>">
                  <div class="form-group mt-5">
                    <div class="input-group input-group-alternative">
                      <input class="form-control" placeholder="<?=$this->lang->line('name')?htmlspecialchars($this->lang->line('name')):'Name'?>" type="text" name="name" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group input-group-alternative">
                      <input class="form-control" placeholder="<?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?>" type="email" name="email" required>
                    </div>
                  </div>
                  <div class="form-group mb-4">
                    <textarea class="form-control form-control-alternative" name="msg" rows="4" cols="80" placeholder="<?=$this->lang->line('type_your_message')?htmlspecialchars($this->lang->line('type_your_message')):'Type your message'?>" required></textarea>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-primary btn-round btn-block btn-lg savebtn"><?=$this->lang->line('send_message')?htmlspecialchars($this->lang->line('send_message')):'Send Message'?></button>
                  </div>
                  <div class="result mt-1">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php } ?>
  </main>
  <footer class="footer has-cards pb-4">
    <div class="container">
      <hr>
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-6">
          <div class="copyright">
            <a href="<?=base_url()?>" target="_blank"><?=htmlspecialchars(footer_text())?></a>
          </div>
        </div>
        <div class="col-md-6">
          <ul class="nav nav-footer justify-content-end">
            <?php if(frontend_permissions('about')){ ?>
              <li class="nav-item">
                <a href="<?=base_url('front/about-us')?>" class="nav-link"><?=$this->lang->line('about')?htmlspecialchars($this->lang->line('about')):'About Us'?></a>
              </li>
						<?php } ?>
						<?php if(frontend_permissions('privacy')){ ?>
              <li class="nav-item">
                <a href="<?=base_url('front/privacy-policy')?>" class="nav-link"><?=$this->lang->line('privacy_policy')?htmlspecialchars($this->lang->line('privacy_policy')):'Privacy Policy'?></a>
              </li>
						<?php } ?>
						<?php if(frontend_permissions('terms')){ ?>
              <li class="nav-item">
                <a href="<?=base_url('front/terms-and-conditions')?>" class="nav-link"><?=$this->lang->line('terms_and_conditions')?htmlspecialchars($this->lang->line('terms_and_conditions')):'Terms and Conditions'?></a>
              </li>
						<?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  <script src="<?=base_url('assets/modules/jquery.min.js')?>"></script>
  <script src="<?=base_url('assets/modules/popper.js')?>"></script>
  <script src="<?=base_url('assets/modules/bootstrap/js/bootstrap.min.js')?>"></script>
  <script src="<?=base_url('assets/front/two/js/custom.js')?>"></script>
  
  <script>
  site_key = '<?php echo get_google_recaptcha_site_key(); ?>';
  </script>

  <?php $recaptcha_site_key = get_google_recaptcha_site_key(); if($recaptcha_site_key){ ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?=htmlspecialchars($recaptcha_site_key)?>"></script>
  <?php } ?>

  
  <div id="cookie-bar">
    <div class="cookie-bar-body">
      <p><?=$this->lang->line('frontend_cookie_message')?htmlspecialchars($this->lang->line('frontend_cookie_message')):'We use cookies to ensure that we give you the best experience on our website.'?></p>
      <div class="cookie-bar-action">
        <button type="button" class="text-uppercase btn btn-primary cookie-bar-btn"><?=$this->lang->line('i_agree')?$this->lang->line('i_agree'):'I Agree!'?></button>
      </div>
    </div>
  </div>

  <script src="<?=base_url('assets/front/comman.js')?>"></script>
</body>

</html>