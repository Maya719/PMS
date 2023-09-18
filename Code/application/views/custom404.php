<?php $this->load->view('includes/head'); ?>
</head>
<body>
<div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="page-error">
          <div class="page-inner">
            <h1>404</h1>
            <div class="page-description">
            <?=$this->lang->line('404_error_message')?htmlspecialchars($this->lang->line('404_error_message')):'The page you were looking for could not be found.'?>
            </div>
            <div class="page-search">
              <div class="mt-3">
                <a href="<?=base_url()?>"><?=$this->lang->line('go_back')?htmlspecialchars($this->lang->line('go_back')):'Go Back'?> <?=$this->lang->line('home')?htmlspecialchars($this->lang->line('home')):'Home'?></a>
              </div>
            </div>
          </div>
        </div>
        <div class="simple-footer mt-5">
        <?=htmlspecialchars(footer_text())?>
        </div>
      </div>
    </section>
  </div>
</body>
</html>
