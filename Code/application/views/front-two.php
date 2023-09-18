<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('includes/head'); ?>
</head>
<body class="layout-3">
<div id="app">
    <div class="main-wrapper container">
      <div class="navbar-bg" style="height: 70px;background-color: #00529a;"></div>

      <nav class="navbar navbar-expand-lg main-navbar">
        <a href="<?=base_url()?>"><img class="sidebar-gone-hide" alt="Logo" src="<?=base_url('assets/uploads/logos/'.full_logo())?>" height="27px"></a>
        <a href="<?=base_url()?>" class="navbar-brand m-0 sidebar-gone-show"><?=company_name()?></a>
        
        <ul class="navbar-nav ml-auto sidebar-gone-show">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
            <i class="fa fa-language"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
            
              <?php $languages = get_languages();
                if($languages){
                foreach($languages as $language){  ?>
                  <a href="<?=base_url('languages/change/'.$language['language'])?>" class="dropdown-item <?=$language['language']==$this->session->userdata('lang') || ($language['language']=='english' && !$this->session->userdata('lang'))?'active':''?>">
                    <?=ucfirst($language['language'])?>
                  </a>
              <?php } } ?>

            </div>
          </li>
        </ul>

        <div class="nav-collapse">
          <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
          <ul class="navbar-nav">
            <?php if(frontend_permissions('features') && $features){ ?>
            <li class="nav-item"><a href="#Features" class="nav-link home-menu"><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></a></li>
            <?php } ?>
            <?php if(frontend_permissions('subscription_plans')){ ?>
            <li class="nav-item"><a href="#Subscription" class="nav-link home-menu"><?=$this->lang->line('subscription_plans')?$this->lang->line('subscription_plans'):'Subscription Plans'?></a></li>
            <?php } ?>
            <?php if(frontend_permissions('contact')){ ?>
            <li class="nav-item"><a href="#Contact" class="nav-link home-menu"><?=$this->lang->line('contact')?$this->lang->line('contact'):'Contact'?></a></li>
            <?php } ?>
            <li class="nav-item"><a href="<?=base_url('auth')?>" class="nav-link"><?=$this->lang->line('get_start')?$this->lang->line('get_start'):'Get Start'?></a></li>
          </ul>
        </div>
        <ul class="navbar-nav ml-auto sidebar-gone-hide">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
            <i class="fa fa-language"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              
              <?php $languages = get_languages();
                if($languages){
                foreach($languages as $language){  ?>
                  <a href="<?=base_url('languages/change/'.$language['language'])?>" class="dropdown-item <?=$language['language']==$this->session->userdata('lang') || ($language['language']=='english' && !$this->session->userdata('lang'))?'active':''?>">
                    <?=ucfirst($language['language'])?>
                  </a>
              <?php } } ?>

            </div>
          </li>
        </ul>
      </nav>

      <nav class="navbar navbar-secondary navbar-expand-lg sidebar-gone-show">
        <div class="container">
          <ul class="navbar-nav">
            <?php if(frontend_permissions('features') && $features){ ?>
              <li class="nav-item">
                <a href="#Features" class="nav-link home-menu"><span><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></span></a>
              </li>
            <?php } ?>
            <?php if(frontend_permissions('subscription_plans')){ ?>
              <li class="nav-item">
                <a href="#Subscription" class="nav-link home-menu"><span><?=$this->lang->line('subscription_plans')?$this->lang->line('subscription_plans'):'Subscription Plans'?></span></a>
              </li>
            <?php } ?>
            <?php if(frontend_permissions('contact')){ ?>
              <li class="nav-item">
                <a href="#Contact" class="nav-link home-menu"></i><span><?=$this->lang->line('contact')?$this->lang->line('contact'):'Contact'?></span></a>
              </li>
            <?php } ?>
              
              <li class="nav-item">
                <a href="<?=base_url('auth')?>" class="nav-link"><span><?=$this->lang->line('get_start')?$this->lang->line('get_start'):'Get Start'?></span></a>
              </li>
          </ul>
        </div>
      </nav>

      <!-- Main Content -->
      <div class="main-content" style="padding-top: 80px;">
        <section class="section">
          <div class="section-body">
            <div class="row justify-content-center">

              <?php if(frontend_permissions('features') && $features){  ?>
              <h3 class="col-md-12 text-center mb-3 mt-5 text-primary" id="Features"><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></h3>
              <?php foreach($features as $feature){ ?>
              <div class="card col-md-3 m-1">
                <div class="card-body">
                  <div class="summary">
                    <div class="summary-info bg-transparent">
                      <h4><?=htmlspecialchars($feature['title'])?></h4>
                      <div class="text-muted"><?=htmlspecialchars($feature['description'])?></div>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>
              <hr class="col-md-12"></hr>
              <?php } ?>

              

              <?php if(frontend_permissions('subscription_plans')){ ?>
              <h3 class="col-md-12 text-center mb-3 mt-5 text-primary" id="Subscription"><?=$this->lang->line('subscription_plans')?$this->lang->line('subscription_plans'):'Subscription Plans'?></h3>
              <?php 
                foreach($plans as $plan){
              ?>
                  <div class="col-md-4">
                    <div class="pricing card">
                      <div class="pricing-title">
                        <?=htmlspecialchars(htmlspecialchars($plan['title']))?> 

                      </div>
                      <div class="pricing-padding">
                        <div class="pricing-price">
                          <div>$ <?=htmlspecialchars(htmlspecialchars($plan['price']))?></div>
                          <div>
                            <?php
                              if($plan['billing_type'] == 'Monthly'){
                                echo $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
                              }else{
                                echo $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
                              }
                            ?> 
                          </div>
                        </div>
                        <div class="pricing-details">
                          <div class="pricing-item">
                            <div class="pricing-item-label mr-1 font-weight-bold"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></div>
                            <div class="badge badge-primary"><?=$plan['projects']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['projects'])?></div>
                          </div>
                          <div class="pricing-item">
                            <div class="pricing-item-label mr-1 font-weight-bold"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></div>
                            <div class="badge badge-primary"><?=$plan['tasks']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['tasks'])?></div>
                          </div>
                          <div class="pricing-item">
                            <div class="pricing-item-label mr-1 font-weight-bold"><?=$this->lang->line('users')?$this->lang->line('users'):'Users'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('including_admins_clients_and_users')?$this->lang->line('including_admins_clients_and_users'):'Including Admins, Clients and Users.'?>"></i></div>
                            <div class="badge badge-primary"><?=$plan['users']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['users'])?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php }  ?>
            </div>
            <hr class="col-md-12"></hr>
            <?php } ?>
                
            <?php if(frontend_permissions('contact')){ ?>
            <h3 class="col-md-12 text-center mb-3 mt-5 text-primary" id="Contact"><?=$this->lang->line('contact')?$this->lang->line('contact'):'Contact'?></h3>
            <div class="card card-primary" id="front_contact_form_card">
              <div class="row m-0">
                <div class="col-12 col-md-12 p-0">
                  <div class="card-body">
                    <form action="<?=base_url('front/send-mail')?>" id="front_contact_form" method="POST">
                      <div class="form-group floating-addon">
                        <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="far fa-user"></i>
                            </div>
                          </div>
                          <input id="name" type="text" class="form-control" name="<?=$this->lang->line('name')?$this->lang->line('name'):'Name'?>" placeholder="<?=$this->lang->line('name')?$this->lang->line('name'):'Name'?>">
                        </div>
                      </div>

                      <div class="form-group floating-addon">
                        <label><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-envelope"></i>
                            </div>
                          </div>
                          <input id="email" type="email" class="form-control" name="<?=$this->lang->line('email')?$this->lang->line('email'):'Email'?>" placeholder="<?=$this->lang->line('email')?$this->lang->line('email'):'Email'?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <label><?=$this->lang->line('message')?$this->lang->line('message'):'Message'?></label>
                        <textarea class="form-control" placeholder="<?=$this->lang->line('type_your_message')?$this->lang->line('type_your_message'):'Type your message'?>" name="msg" data-height="150"></textarea>
                      </div>
                      <div class="result"></div>
                      <div class="form-group text-right">
                        <button type="submit" class="btn btn-round btn-lg btn-primary savebtn">
                        <?=$this->lang->line('send_message')?$this->lang->line('send_message'):'Send Message'?>
                        </button>
                      </div>
                    </form>
                  </div>  
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

  <a id="back-to-top" href="#" class="btn btn-primary back-to-top"><i class="fas fa-chevron-up"></i></a>

<?php $this->load->view('includes/js'); ?>
<script>
$(document).ready(function(){
	$(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
				$('#back-to-top').fadeIn();
			} else {
				$('#back-to-top').fadeOut();
			}
    });
    
		// scroll body to 0px on click
		$('#back-to-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 400);
			return false;
		});
});
</script>
</body>
</html>
