<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
        <div class="main-content">
          <section class="section">
            <div class="section-header">
              <div class="section-header-back">
                <a href="<?=base_url()?>" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
              </div>
              <h1><?=$this->lang->line('frontend')?$this->lang->line('frontend'):'Frontend'?></h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
                <div class="breadcrumb-item"><?=$this->lang->line('frontend')?$this->lang->line('frontend'):'Frontend'?></div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">
              
                <div class="col-md-3">
                  <div class="card card-primary">
                    <div class="card-body">
                      <ul class="nav nav-pills flex-column">
                        <li class="nav-item"><a href="<?=base_url('front/landing')?>" class="nav-link"><?=$this->lang->line('general')?$this->lang->line('general'):'General'?></a></li>
                        <li class="nav-item"><a href="<?=base_url('front/home')?>" class="nav-link active"><?=$this->lang->line('home')?$this->lang->line('home'):'Home'?></a></li>
                        <li class="nav-item"><a href="<?=base_url('front/features')?>" class="nav-link"><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></a></li>
                        <li class="nav-item"><a href="<?=base_url('front/about')?>" class="nav-link"><?=$this->lang->line('about')?$this->lang->line('about'):'About Us'?></a></li>
                        <li class="nav-item"><a href="<?=base_url('front/saas-privacy-policy')?>" class="nav-link"><?=$this->lang->line('privacy_policy')?$this->lang->line('privacy_policy'):'Privacy Policy'?></a></li>
                        <li class="nav-item"><a href="<?=base_url('front/saas-terms-and-conditions')?>" class="nav-link"><?=$this->lang->line('terms_and_conditions')?$this->lang->line('terms_and_conditions'):'Terms and Conditions'?></a></li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="col-md-9" id="home_div">
                  <div class="card card-primary">
                    <div class="card-body">
                          <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                            <?php foreach($lang as $kay => $lan){ ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?=$kay==0?'active':''?>" id="tab-<?=htmlspecialchars($lan['language'])?>" data-toggle="tab" href="#<?=htmlspecialchars($lan['language'])?>" role="tab" aria-controls="<?=htmlspecialchars($lan['language'])?>" aria-selected="true"><?=ucfirst(htmlspecialchars($lan['language']))?></a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card" id="home-card">
                                    <form action="<?=base_url('settings/save-home-setting')?>" method="POST" id="home-form">
                                        <div class="tab-content no-padding" id="myTab2Content">
                                            <?php foreach($lang as $kay => $lan){ ?>
                                              <div class="tab-pane fade <?=$kay==0?'show active':''?>" id="<?=htmlspecialchars($lan['language'])?>" role="tabpanel" aria-labelledby="tab-<?=htmlspecialchars($lan['language'])?>">
                                                <div class="card-header">
                                                  <h4><?=ucfirst(htmlspecialchars($lan['language']))?> 
                                                  <?php if($lan['language']=='english'){ ?>
                                                  <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('must_enter_title_and_description_for_default_language')?$this->lang->line('must_enter_title_and_description_for_default_language'):'Must enter Title and Description for default language.'?>"></i>
                                                  <?php } ?>
                                                  </h4>
                                                </div>
                                                <div class="card-body row">
                                                    <div class="form-group col-md-12">
                                                        <label><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?><span class="text-danger">*</span></label>
                                                        <input type="text" name="<?=htmlspecialchars($lan['language'])?>_title" value="<?=isset($home->{$lan['language']}->title)?htmlspecialchars($home->{$lan['language']}->title):''?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
                                                        <textarea type="text" name="<?=$lan['language']?>_description" class="form-control"><?=isset($home->{$lan['language']}->description)?htmlspecialchars($home->{$lan['language']}->description):''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }  ?>
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
