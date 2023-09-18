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
                <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
              </div>
              <h1><?=$this->lang->line('about')?$this->lang->line('about'):'About Us'?></h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
                <div class="breadcrumb-item"><?=$this->lang->line('about')?$this->lang->line('about'):'About Us'?></div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">

                <div class="col-md-12" id="home-card">
                  <div class="card card-primary">
                    <div class="card-body">
                      <form action="<?=base_url('front/edit-pages')?>" method="POST" id="home-form">
                        <input type="hidden" name="update_id" id="update_id" value="<?=$data[0]['id']?>">
                        <textarea name="content"><?=$data[0]['content']?></textarea>
                        
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
  plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap  emoticons code',
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
