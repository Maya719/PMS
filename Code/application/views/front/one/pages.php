<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?=(isset($page_title) && !empty($page_title))?htmlspecialchars($page_title):'Page'?></title>
    <link rel="shortcut icon" href="<?=base_url('assets/uploads/logos/'.htmlspecialchars(favicon()))?>">
    <link rel="stylesheet" href="<?=base_url('assets/front/one/css/animate.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/front/one/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/front/one/css/font-awesome.min.css')?>">

    <link rel="stylesheet" href="<?=base_url('assets/front/one/css/custom.css')?>">

	</head>
	<body>
		<!-- start preloader -->
		<div class="preloader">
				<div class="sk-spinner sk-spinner-rotating-plane"></div>
		</div>
		<!-- end preloader -->

		<!-- start navigation -->
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon icon-bar"></span>
						<span class="icon icon-bar"></span>
						<span class="icon icon-bar"></span>
					</button>
					<a href="<?=base_url()?>" class="navbar-brand">
						<img class="navbar-logo" alt="<?=htmlspecialchars(company_name())?>" src="<?=base_url('assets/uploads/logos/'.htmlspecialchars(full_logo()))?>">
					</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right text-uppercase">
						<li><a href="<?=base_url()?>" class="home-menu"><button type="button" class="text-uppercase btn"><?=$this->lang->line('go_back')?htmlspecialchars($this->lang->line('go_back')):'Go Back'?></button></a></li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- end navigation -->

		<section id="divider" class="mt-25">
			<div class="container mt-25">
				<div class="row">
          			<div class="col-md-12 wow bounceIn features">
						<h2 class="text-uppercase">
							<?php
								if($data[0]['title'] == 'About Us'){
									echo $this->lang->line('about')?htmlspecialchars($this->lang->line('about')):'About Us';
								}elseif($data[0]['title'] == 'Privacy Policy'){
									echo $this->lang->line('privacy_policy')?htmlspecialchars($this->lang->line('privacy_policy')):'Privacy Policy';
								}elseif($data[0]['title'] == 'Terms and Conditions'){
									echo $this->lang->line('terms_and_conditions')?htmlspecialchars($this->lang->line('terms_and_conditions')):'Terms and Conditions';
								}else{
									echo $data[0]['title'];
								}
							?>
						</h2>
          			</div>
				</div>
			</div>
		</section>
		<!-- end divider -->

		<!-- start contact -->
			<div class="container">
				<div class="row">
					<div class="col-md-12 wow fadeInUp card" data-wow-delay="0.6s" >
					<?=isset($data[0]['content'])?$data[0]['content']:''?>
					</div>
				</div>
			</div>
		<!-- end contact -->
    
    <script src="<?=base_url('assets/front/one/js/jquery.js')?>"></script>
    <script src="<?=base_url('assets/front/one/js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('assets/front/one/js/wow.min.js')?>"></script>
    <script src="<?=base_url('assets/front/one/js/custom.js')?>"></script>
	</body>
</html>