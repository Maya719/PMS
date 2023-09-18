<?php $this->load->view('includes/head'); ?>
</head>
<body class="w-100 h-100" id="app">
  <section class="section w-100 h-100">
    <div class="jumbotron w-100 h-100 text-center align-middle">
      <?php if($meeting[0]['status'] == 0){ ?>
        <h2 class="text-danger"><?=$this->lang->line('meeting_not_started')?$this->lang->line('meeting_not_started'):'Meeting not started.'?></h2>
        <a href="<?=base_url('meetings')?>"><p class="lead text-info"><?=$this->lang->line('come_back_later')?$this->lang->line('come_back_later'):'Come back later.'?></p></a>
      <?php }else{ ?>
        <h2 class="text-danger"><?=$this->lang->line('meeting_over')?$this->lang->line('meeting_over'):'Meeting Over'?></h2>
        <a href="<?=base_url('meetings')?>"><p class="lead text-info"><?=$this->lang->line('go_back')?$this->lang->line('go_back'):'Go Back'?></p></a>
      <?php } ?>
    </div>
  </section>
<?php $this->load->view('includes/js'); ?>
</body>
</html>
