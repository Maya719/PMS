<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
    <title><?=$page_title?></title>
    
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="shortcut icon" href="<?=base_url('assets/uploads/logos/'.favicon())?>">
    <link rel="stylesheet" href="<?=base_url('assets/frontend/css/animate.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/frontend/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/frontend/css/font-awesome.min.css')?>">

		<!-- google font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700,800' rel='stylesheet' type='text/css'>
    
    <link rel="stylesheet" href="<?=base_url('assets/frontend/css/custom.css')?>">

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

						<li><a href="<?=base_url()?>" class="home-menu"><?=$this->lang->line('home')?$this->lang->line('home'):'Home'?></a></li>
						
					</ul>
				</div>
			</div>
		</nav>
		<!-- end navigation -->

		<section id="divider">
			<div class="container">
				<div class="row">
          			<div class="col-md-12 wow bounceIn features">
						<h2 class="text-uppercase"><?=$data[0]['title']?></h2>
          			</div>
				</div>
			</div>
		</section>
		<!-- end divider -->

		<!-- start contact -->
			<div class="container">
				<div class="row">
					<div class="col-md-12 wow fadeInUp card" data-wow-delay="0.6s" >
					<?=$data[0]['content']?>
					</div>
				</div>
			</div>
		<!-- end contact -->
    
    <script src="<?=base_url('assets/frontend/js/jquery.js')?>"></script>
    <script src="<?=base_url('assets/frontend/js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('assets/frontend/js/wow.min.js')?>"></script>
    <script src="<?=base_url('assets/frontend/js/custom.js')?>"></script>
	</body>
</html>