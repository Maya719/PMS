<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('front/meta'); ?>
	
    <link rel="stylesheet" href="<?=base_url('assets/front/one/css/animate.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/front/one/css/bootstrap.min.css')?>">
  	<link rel="stylesheet" href="<?=base_url('assets/modules/fontawesome/css/all.min.css')?>">
    <style>
      :root{--theme-color: <?=theme_color()?>;}
  	</style>
    <link rel="stylesheet" href="<?=base_url('assets/front/one/css/custom.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/front/comman.css')?>">
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
		<!-- start preloader -->
		<div class="preloader">
				<div class="sk-spinner sk-spinner-rotating-plane"></div>
		</div>
		<!-- end preloader -->
		<!-- start navigation -->
		<nav class="navbar navbar-default navbar-fixed-top timwork-nav" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon icon-bar"></span>
						<span class="icon icon-bar"></span>
						<span class="icon icon-bar"></span>
					</button>
					<a href="<?=base_url()?>" class="navbar-brand">
						<img class="navbar-logo" alt="<?=company_name()?>" src="<?=base_url('assets/uploads/logos/'.full_logo())?>">
					</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right text-uppercase">

						<?php if(frontend_permissions('home')){ ?>
						<li><a href="#home" class="home-menu"><?=$this->lang->line('home')?$this->lang->line('home'):'Home'?></a></li>
						<?php } ?>

						<?php if(frontend_permissions('features') && $features){ ?>
						<li><a href="#divider" class="home-menu"><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></a></li>
						<?php } ?>
						<?php if(frontend_permissions('subscription_plans')){ ?>
						<li><a href="#pricing" class="home-menu"><?=$this->lang->line('pricing')?htmlspecialchars($this->lang->line('pricing')):'Pricing'?></a></li>
						<?php } ?>
						<?php if(frontend_permissions('contact')){ ?>
						<li><a href="#contact" class="home-menu"><?=$this->lang->line('contact')?$this->lang->line('contact'):'Contact'?></a></li>
						<?php } ?>

						<li><a  href="<?=base_url('auth')?>" class="home-menu" target="_blank"><button type="button" class="text-uppercase btn"><?=$this->lang->line('login')?$this->lang->line('login'):'Login'?></button></a></li>

						<li><a  href="<?=base_url('auth/register')?>" class="home-menu" target="_blank"><button type="button" class="text-uppercase btn btn-primary"><?=$this->lang->line('get_start')?$this->lang->line('get_start'):'Get Start'?></button></a></li>

						

					</ul>
				</div>
			</div>
		</nav>
		<!-- end navigation -->

		<?php if(frontend_permissions('home')){ ?>
		<!-- start home -->
		<section id="home">
			<div class="overlay">
				<div class="container">
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10 wow fadeIn" data-wow-delay="0.3s">
							<h1 class="text-upper"><?=$this->lang->line('frontend_home_title')?htmlspecialchars($this->lang->line('frontend_home_title')):'Professional Project Management tool and CRM'?></h1>
							<p class="tm-white"><?=$this->lang->line('frontend_home_description')?htmlspecialchars($this->lang->line('frontend_home_description')):'TimeWork pms.mobipixels.com is a perfect, robust, lightweight, superfast web application to fulfill all your Team Collaboration, Project Management and CRM needs.'?></p>
							<a href="<?=base_url('auth/register')?>" target="_blank" class="btn btn-primary text-uppercase mt-25"><?=$this->lang->line('get_start')?$this->lang->line('get_start'):'Get Start'?></a>
						</div>
						<div class="col-md-1"></div>
					</div>
				</div>
			</div>
		</section>
    	<!-- end home -->
    	<?php } ?>
    
    	<?php if(frontend_permissions('features') && $features){  ?>
		<!-- start divider -->
		<section id="divider">
			<div class="container">
				<div class="row">
          			<div class="col-md-12 wow bounceIn features">
						<h2 class="text-uppercase"><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></h2>
          			</div>
					<?php foreach($features as $feature){ ?>
					<div class="col-md-4 wow fadeInUp templatemo-box" data-wow-delay="0.3s">
						<?php if(isset($feature['icon'])){ ?>
						<i class="front-feature-icon <?=$feature['icon']?>"></i>
						<?php } ?>
						<h3 class="text-uppercase"><?=htmlspecialchars($feature['title'])?></h3>
						<p><?=htmlspecialchars($feature['description'])?></p>
					</div>
					<?php } ?>
				</div>
			</div>
		</section>
		<!-- end divider -->
    	<?php } ?>

    	<?php if(frontend_permissions('subscription_plans')){ ?>
		<!-- start pricing -->
		<section id="pricing">
			<div class="container">
				<div class="row">
					<div class="col-md-12 wow bounceIn">
						<h2 class="text-uppercase"><?=$this->lang->line('pricing')?htmlspecialchars($this->lang->line('pricing')):'Pricing'?></h2>
          			</div>
          
					<?php foreach($plans as $plan){ ?>
						<div class="col-md-4 wow fadeIn" data-wow-delay="0.6s">
						<div class="pricing text-uppercase">
							<div class="pricing-title">
							<h4><?=htmlspecialchars($plan['title'])?></h4>
							<p><?=get_saas_currency('currency_symbol')?><?=htmlspecialchars($plan['price'])?></p>
							<small class="text-lowercase">
								<?php
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
							</small>
							</div>
							<ul>
								<li><?=$this->lang->line('storage')?$this->lang->line('storage'):'Storage'?> <div class="badge badge-primary"><?=$plan['storage']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['storage'].'GB')?></div></li>

								<li><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?> <div class="badge badge-primary"><?=$plan['projects']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['projects'])?></div></li>

								<li><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?> <div class="badge badge-primary"><?=$plan['tasks']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['tasks'])?></div></li>

								<li><?=$this->lang->line('users')?$this->lang->line('users'):'Users'?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('including_admins_clients_and_users')?$this->lang->line('including_admins_clients_and_users'):'Including Admins, Clients and Users.'?>"></i> <div class="badge badge-primary"><?=$plan['users']<0?$this->lang->line('unlimited')?$this->lang->line('unlimited'):'Unlimited':htmlspecialchars($plan['users'])?></div></li>


								
								<?php
									$modules = '';
									if($plan["modules"] != ''){
									echo '<hr style="margin: 4px;"><li>'.($this->lang->line('modules')?$this->lang->line('modules'):'Modules').'</li>';
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
										$modules .= '<li class="modules"><i class="fas fa-check text-success"></i> '.$mod_name.'</li>';
										}elseif($mod_name){
										$modules .= '<li class="modules"><i class="fas fa-times text-danger"></i> '.$mod_name.'</li>';
										}
									}
									}
									echo $modules;
								?>

							</ul>
							<a href="<?=base_url('auth/register')?>" class="btn btn-primary text-uppercase"><?=$this->lang->line('get_start')?$this->lang->line('get_start'):'Get Start'?></a>
						</div>
						</div>
					<?php } ?>
          
				</div>
			</div>
		</section>
		<!-- end pricing -->
    	<?php } ?>

    	<?php if(frontend_permissions('contact')){ ?>
		<!-- start contact -->
		<section id="contact">
			<div class="overlay">
				<div class="container">
					<div class="row">
						<div class="col-md-12 wow fadeInUp card" data-wow-delay="0.6s" id="front_contact_form_card">
              				<h2 class="text-uppercase"><?=$this->lang->line('contact')?$this->lang->line('contact'):'Contact'?></h2>
							<div class="contact-form">
								<form action="<?=base_url('front/send-mail')?>" id="front_contact_form" method="POST">
									<div class="col-md-6">
										<input type="text" class="form-control" name="name" placeholder="<?=$this->lang->line('name')?$this->lang->line('name'):'Name'?>">
									</div>
									<div class="col-md-6">
										<input type="email" class="form-control" name="email" placeholder="<?=$this->lang->line('email')?$this->lang->line('email'):'Email'?>">
									</div>
									<div class="col-md-12">
										<textarea class="form-control" placeholder="<?=$this->lang->line('type_your_message')?$this->lang->line('type_your_message'):'Type your message'?>" name="msg" rows="4"></textarea>
									</div>
									<div class="col-md-12 result">
									</div>
									<div class="col-md-4">
										<button type="submit" class="form-control text-uppercase btn btn-primary savebtn">
										<?=$this->lang->line('send_message')?$this->lang->line('send_message'):'Send Message'?>
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- end contact -->
    	<?php } ?>

		<!-- start footer -->
		<footer>

			<div class="btn-group dropup">
				<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-language"></i>
				</button>
				<div class="dropdown-menu">
					<?php $languages = get_languages('', '', 1);
					if($languages){
					foreach($languages as $language){  ?>
						<a href="<?=base_url('languages/change/'.$language['language'])?>" class="dropdown-item <?=$language['language']==$this->session->userdata('lang') || ($language['language']==default_language() && !$this->session->userdata('lang'))?'active':''?>">
						<?=ucfirst($language['language'])?>
						</a><br>
					<?php } } ?>
				</div>
			</div>
			

			<div class="container">
				<div class="row">
					<p><?=htmlspecialchars(footer_text())?></p>
					<p>
						<?php if(frontend_permissions('about')){ ?>
							<a href="<?=base_url('front/about-us')?>"><?=$this->lang->line('about')?$this->lang->line('about'):'About Us'?></a>
						<?php } ?>
						<?php if(frontend_permissions('privacy')){ ?>
							 - <a href="<?=base_url('front/privacy-policy')?>"><?=$this->lang->line('privacy_policy')?$this->lang->line('privacy_policy'):'Privacy Policy'?></a>
						<?php } ?>
						<?php if(frontend_permissions('terms')){ ?>
							 - <a href="<?=base_url('front/terms-and-conditions')?>"><?=$this->lang->line('terms_and_conditions')?$this->lang->line('terms_and_conditions'):'Terms and Conditions'?></a>
						<?php } ?>
					</p>
				</div>
			</div>
		</footer>
		<!-- end footer -->
    
		<script src="<?=base_url('assets/front/one/js/jquery.js')?>"></script>
		<script src="<?=base_url('assets/front/one/js/bootstrap.min.js')?>"></script>
		<script src="<?=base_url('assets/front/one/js/wow.min.js')?>"></script>
		<script src="<?=base_url('assets/front/one/js/custom.js')?>"></script>

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