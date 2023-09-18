<?php $this->load->view('includes/head'); ?>
<link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-iconpicker/bootstrap-iconpicker.min.css');?>">
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
        <div class="main-content">
          <section class="section">
            <div class="section-header">
              <div class="section-header-back">
                <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
              </div>
              <h1><?=$this->lang->line('edit_feature')?$this->lang->line('edit_feature'):'Edit Feature'?></h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
                <div class="breadcrumb-item active"><a href="<?=base_url('front/features')?>"><?=$this->lang->line('frontend')?$this->lang->line('frontend'):'Frontend'?></a></div>
                <div class="breadcrumb-item"><?=$this->lang->line('edit_feature')?$this->lang->line('edit_feature'):'Edit Feature'?></div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary">
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
                    <div class="card card-primary" id="feature-card">
                        <form action="<?=base_url('front/save-feature')?>" method="POST" id="feature-form">
                            <input type="hidden" name="update_id" value="<?=$this->uri->segment(3)?>">
                            <div class="tab-content no-padding" id="myTab2Content">
                                <?php foreach($lang as $kay => $lan){ ?>
                                <?php foreach($features as $feature){ ?>
                                <div class="tab-pane fade <?=$kay==0?'show active':''?>" id="<?=htmlspecialchars($lan['language'])?>" role="tabpanel" aria-labelledby="tab-<?=htmlspecialchars($lan['language'])?>">
                                    <div class="card-header">
                                      <h4><?=ucfirst(htmlspecialchars($lan['language']))?> 
                                      <?php if($lan['language']==default_language()){ ?>
                                      <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('must_enter_title_and_description_for_default_language')?$this->lang->line('must_enter_title_and_description_for_default_language'):'Must enter Title and Description for default language.'?>"></i>
                                      <?php } ?>
                                      </h4>
                                    </div>
                                    <div class="card-body row">
                                    
                                        <div class="form-group col-md-12">
                                          <label><?=$this->lang->line('icon')?$this->lang->line('icon'):'Icon'?><span class="text-danger">*</span></label>
                                          <?php $feature_icon = json_decode($feature['icon']);?>
                                          <button role="iconpicker" data-icon="<?=isset($feature_icon->{$lan['language']})?htmlspecialchars($feature_icon->{$lan['language']}):''?>" data-cols="10" data-iconset="fontawesome5" data-label-header="{0} of {1} pages" data-label-footer="{0} - {1} of {2} icons" data-placement="top" data-rows="5" data-search="true" data-search-text="" data-selected-class="btn-success" data-unselected-class="" class="icon btn btn-block btn-default border iconpicker dropdown-toggle" id="<?=htmlspecialchars($lan['language'])?>_icon" name="<?=htmlspecialchars($lan['language'])?>_icon" title="" data-original-title="icon"><i class="<?=isset($feature_icon->{$lan['language']})?htmlspecialchars($feature_icon->{$lan['language']}):''?>"></i><input type="hidden" name="<?=htmlspecialchars($lan['language'])?>_icon" value="<?=isset($feature_icon->{$lan['language']})?htmlspecialchars($feature_icon->{$lan['language']}):''?>"><span class="caret"></span></button>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?><span class="text-danger">*</span></label>
                                            <?php $feature_title = json_decode($feature['title']);?>
                                            <input type="text" name="<?=htmlspecialchars($lan['language'])?>_title" value="<?=isset($feature_title->{$lan['language']})?htmlspecialchars($feature_title->{$lan['language']}):''?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
                                            <?php $feature_description = json_decode($feature['description']); ?>
                                            <textarea type="text" name="<?=$lan['language']?>_description" class="form-control"><?=isset($feature_description->{$lan['language']})?htmlspecialchars($feature_description->{$lan['language']}):''?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php } } ?>
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
<script src="<?=base_url('assets/modules/bootstrap-iconpicker/bootstrap-iconpicker.min.js');?>"></script>

</body>
</html>
