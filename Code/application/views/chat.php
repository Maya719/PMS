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
            <?=$this->lang->line('chat')?$this->lang->line('chat'):'Chat'?> 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('chat')?$this->lang->line('chat'):'Chat'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row justify-content-center">
              <div class="col-md-4" style="height: 600px;">
                <div class="card card-primary">
                  <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border" style="max-height: 600px; overflow: auto;">
                    <?php
                      if(isset($chat_users) && !empty($chat_users)){
                      foreach ($chat_users as $user) {
                      if($user['id'] != $this->session->userdata('user_id')){
                    ?>
                      <li class="media mb-1 pb-1 cursor-pointer user-selected-for-chat" data-id="<?=htmlspecialchars($user['id'])?>">

                        <?php
                          if(isset($user['profile']) && !empty($user['profile'])){
                        ?>     
                          <figure class="avatar avatar-sm">
                            <img src="<?=htmlspecialchars($user['profile'])?>" alt="<?=htmlspecialchars($user['first_name'])?> <?=htmlspecialchars($user['last_name'])?>">
                          </figure>

                        <?php }else{ ?>
                          <figure class="user-avatar avatar avatar-sm rounded-circle profile-widget-picture" data-initial="<?=htmlspecialchars($user['short_name'])?>"></figure>
                        <?php } ?>

                        <div class="media-body">
                          <div class="ml-2 font-weight-bold"><?=htmlspecialchars($user['first_name'])?> <?=htmlspecialchars($user['last_name'])?></div>
                          <?=$user['is_read']==0?'':'<div class="ml-2 text-small text-muted new_msg">'.($this->lang->line('new_message')?$this->lang->line('new_message'):'New Message').'</div>'?>
                        </div>
                      </li>
                    <?php } } } ?>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card chat-box card-primary" id="mychatbox">
                  <div class="card-header">
                      <li class="media" id="current_chating_user">
                          <h3><?=$this->lang->line('select_chat')?$this->lang->line('select_chat'):'Select Chat'?></h3>
                      </li>
                      <?php if($this->ion_auth->is_admin() || permissions('chat_delete')){ ?>
                      <div class="card-header-action ml-auto">
                        <button class="btn btn-icon btn-danger d-none" id="delete_chat" data-id=""><i class="fas fa-trash"></i></button>
                      </div>
                      <?php } ?>
                  </div>
                  <div class="card-body chat-content">
                  </div>
                  <div class="card-footer chat-form">
                    <form id="chat-form" class='d-none' action="<?=base_url('chat/create')?>" method="POST">
                      <input type="hidden" name="to_id" id="to_id" value="">
                      <input type="text" class="form-control" name="message" id="chat_input" placeholder="<?=$this->lang->line('type_your_message')?$this->lang->line('type_your_message'):'Type your message'?>">
                      <button class="btn btn-primary">
                        <i class="far fa-paper-plane"></i>
                      </button>
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
<script src="<?=base_url('assets/js/page/chat.js');?>"></script>
</body>
</html>