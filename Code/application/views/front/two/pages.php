<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?=(isset($page_title) && !empty($page_title))?htmlspecialchars($page_title):'Page'?></title>
  <link href="<?=base_url('assets/uploads/logos/'.htmlspecialchars(favicon()))?>" rel="icon" type="image/png">
  <link rel="stylesheet" href="<?=base_url('assets/modules/fontawesome/css/all.min.css')?>">
  <link type="text/css" href="<?=base_url('assets/front/two/css/custom.css')?>" rel="stylesheet">
</head>

<body>
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
          <ul class="navbar-nav align-items-lg-center ml-lg-auto">
            <li class="nav-item ml-lg-4">
              <a href="<?=base_url()?>" class="btn btn-white">
                <span class="nav-link-inner"><?=$this->lang->line('go_back')?htmlspecialchars($this->lang->line('go_back')):'Go Back'?></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <section class="section section-lg" id="PLANS">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-12 text-center mb-4">
            <h2 class="display-3">
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
          <div class="col-lg-12">
            <div class="row row-grid">
              <?=isset($data[0]['content'])?$data[0]['content']:''?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script src="<?=base_url('assets/modules/jquery.min.js')?>"></script>
  <script src="<?=base_url('assets/modules/popper.js')?>"></script>
  <script src="<?=base_url('assets/modules/bootstrap/js/bootstrap.min.js')?>"></script>
  <script src="<?=base_url('assets/front/two/js/custom.js')?>"></script>
</body>

</html>