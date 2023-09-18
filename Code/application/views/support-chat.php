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
                <?=$support_data[0]['ticket_id']?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item active"><a href="<?=base_url('support')?>"><?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?></a></div>
              <div class="breadcrumb-item">
              <?=$support_data[0]['ticket_id']?>
              </div>
            </div>
          </div>
          <div class="section-body">


            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary" id="support-form-card">
                  <div class="card-header">
                    <h4><?=$support_data[0]['ticket_id']?> - <?=$support_data[0]['subject']?></h4>
                  </div>
                  <div class="card-body">
                    <div class="tickets">
                      <div class="ticket-content">

                        <?php if($support_messages){ foreach($support_messages as $support_message){ ?>
                            <div class="ticket-header">
                            <div class="ticket-detail">
                                <div class="ticket-title">
                                <h4><?=$support_message['user']?></h4>
                                </div>
                                <div class="ticket-info">
                                <div class="text-primary font-weight-600"><?=time_elapsed_string($support_message['created'])?></div>
                                </div>
                            </div>
                            </div>
                            <div class="ticket-description">
                            <p><?=$support_message['message']?></p>
                            <div class="ticket-divider"></div>
                            </div>
                        <?php } }else{ if($this->ion_auth->is_admin()){ ?>
                            
                            <div class="ticket-description">
                            <p><?=$this->lang->line('please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP')?htmlspecialchars($this->lang->line('please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP')):'Please explain your problem in detail. We will get back to you ASAP.'?></p>
                            <div class="ticket-divider"></div>
                            </div>

                        <?php }else{ ?>

                            <div class="ticket-description">
                            <p><?=$this->lang->line('we_couldnt_find_any_data')?htmlspecialchars($this->lang->line('we_couldnt_find_any_data')):"We couldn't find any data"?>
                            <div class="ticket-divider"></div>
                            </div>

                        <?php } } ?>

                        
                        <div class="ticket-form">
                            <form action="<?=base_url('support/create_support_message')?>" method="POST" id="support-form">
                                <input type="hidden" name="to_id" value="<?=$ticket_id?>">
                                <textarea name="message"></textarea>
                                <div class="form-group text-right mt-5">
                                    <button class="btn btn-primary btn-lg savebtn">
                                    <?=$this->lang->line('send')?htmlspecialchars($this->lang->line('send')):'Send'?>
                                    </button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js"></script>
<script>

tinymce.init({
  selector: 'textarea',
  height: 240,
  plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap quickbars emoticons code',
  menubar: 'edit view insert format tools table tc help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor permanentpen removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment code',
  setup: function (editor) {
    editor.on("change keyup", function (e) {
        tinyMCE.triggerSave(); 
    });
  },
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

</script>

</body>
</html>
