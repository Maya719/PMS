
    


<div class="navbar-bg"></div>

<nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav mr-auto">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
  <ul class="navbar-nav navbar-right">

<?php if(!$this->ion_auth->in_group(3) && !$this->ion_auth->in_group(4) && is_module_allowed('timesheet')){ ?>
    <li id="nav_timer" class="<?=(check_my_timer())?'':'d-none'?>"><a href="<?=base_url('projects/timesheet')?>" class="nav-link nav-link-lg beep" target="_blank"><i class="far fa-clock"></i></a></li>
<?php } ?>

  <?php 
  if(is_module_allowed('notifications')){ 
    echo get_notifications_live(); 
  } ?>
  
  <?php if(is_module_allowed('languages')){ ?>
    <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
      <i class="fa fa-language"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <?php $languages = get_languages('', '', 1);
          if($languages){
          foreach($languages as $language){  ?>
            <a href="<?=base_url('languages/change/'.$language['language'])?>" class="dropdown-item <?=$language['language'] == $this->session->userdata('lang') || ($language['language'] == default_language() && !$this->session->userdata('lang'))?'active':''?>">
              <?=ucfirst($language['language'])?>
            </a>
        <?php } } ?>
      </div>
    </li>
  <?php } ?>

    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      <?php if(isset($current_user->profile) && !empty($current_user->profile)){ 
          if(file_exists('assets/uploads/profiles/'.$current_user->profile)){
            $file_upload_path = 'assets/uploads/profiles/'.$current_user->profile;
          }else{
            $file_upload_path = 'assets/uploads/f'.$this->session->userdata('saas_id').'/profiles/'.$current_user->profile;
          }
        ?>
        <img alt="image" src="<?=base_url($file_upload_path)?>" class="rounded-circle mr-1">
      <?php }else{ ?>
          <figure class="avatar mr-2 avatar-sm bg-danger text-white" data-initial="<?=mb_substr(htmlspecialchars($current_user->first_name), 0, 1, "utf-8").''.mb_substr(htmlspecialchars($current_user->last_name), 0, 1, "utf-8")?>"></figure>
      <?php } ?>
      <div class="d-sm-none d-lg-inline-block"><?=htmlspecialchars($current_user->first_name)?> <?=htmlspecialchars($current_user->last_name)?></div></a>
      <div class="dropdown-menu dropdown-menu-right">
        <?php
          if($this->ion_auth->is_admin()){
            $my_plan = get_current_plan(); ?>
          <div class="dropdown-title">
            <h6 class="text-danger"><?=$my_plan['title']?></h6>
          </div>
        <?php  }
        ?>
        
        <a href="<?=base_url('users/profile')?>" class="dropdown-item has-icon <?=(current_url() == base_url('users/profile'))?'active':''?>">
          <i class="far fa-user"></i> <?=$this->lang->line('profile')?$this->lang->line('profile'):'Profile'?>
        </a>
        
        <?php if($this->ion_auth->in_group(4)){ ?>
          <a href="<?=base_url('users/company')?>" class="dropdown-item has-icon <?=(current_url() == base_url('users/company'))?'active':''?>">
            <i class="far fa-copyright"></i> <?=$this->lang->line('company')?$this->lang->line('company'):'Company'?>
          </a>
        <?php } ?>

        <div class="dropdown-divider"></div>
        <a href="<?=base_url('auth/logout')?>" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> <?=$this->lang->line('logout')?$this->lang->line('logout'):'Logout'?>
        </a>
      </div>
    </li>
  </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?=base_url()?>"><img class="navbar-logos" alt="Logo" src="<?=base_url('assets/uploads/logos/'.full_logo())?>"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?=base_url()?>"><img class="navbar-logos" alt="Logo Half" src="<?=base_url('assets/uploads/logos/'.half_logo())?>"></a>
    </div>
    <ul class="sidebar-menu">
      <li <?= (current_url() == base_url('/') || current_url() == base_url('home'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url()?>"><i class="fas fa-home text-primary"></i> <span><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></span></a></li>
      
     <?php if(($this->ion_auth->is_admin() || permissions('attendance_view_all') || permissions('attendance_view') || permissions('leaves_view') || permissions('biometric_request_view') || permissions('plan_holiday_view')) && (is_module_allowed('leaves') || is_module_allowed('attendance') || is_module_allowed('biometric_missing'))){ ?>           
        <li class="dropdown <?=((current_url() == base_url('leaves') || current_url() == base_url('attendance') || $this->uri->segment(1) == 'attendance' || current_url() == base_url('biometric_missing') || current_url() == base_url('holiday') ) )?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-fingerprint text-success"></i> 
        <span><?=$this->lang->line('ams')?$this->lang->line('ams'):'AMS'?></span></a>
          <ul class="dropdown-menu">

            <?php if(($this->ion_auth->is_admin() || permissions('attendance_view_all')) && is_module_allowed('attendance')) { ?>
              <li <?=(current_url() == base_url('attendance'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('attendance')?>"><span><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'Attendance'?></span></a></li>
            <?php } ?>

            <?php if(is_module_allowed('attendance') && permissions('attendance_view') && !permissions('attendance_view_all')){ ?>
              <li <?=(current_url() == base_url('attendance/user_attendance'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('attendance/user_attendance')?>"><span><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'Attendance'?></span></a></li>
            <?php } ?>
            
            <?php if(is_module_allowed('leaves') && ($this->ion_auth->is_admin() || permissions('leaves_view'))){ ?>
              <li <?=(current_url() == base_url('leaves'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('leaves')?>"><span><?=$this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves'?></span></a></li>
            <?php } ?>

            <?php if( is_module_allowed('biometric_missing') && ($this->ion_auth->is_admin() || permissions('biometric_request_view'))){ ?>
              <li style=" line-height: 1" <?=(current_url() == base_url('biometric_missing'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('biometric_missing')?>"><span><?=$this->lang->line('biometric_request')?$this->lang->line('biometric_request'):'Biometric Request'?></span></a></li>
            <?php } ?>

            <?php if(($this->ion_auth->is_admin() || permissions('plan_holiday_view')) && is_module_allowed('attendance')){ ?>
              <li <?=(current_url() == base_url('holiday'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('holiday')?>"><span><?=$this->lang->line('holiday')?$this->lang->line('holiday'):'Plan Holiday'?></span></a></li>
            <?php } ?>

          </ul>
        </li>
      <?php } ?>

      <?php if(($this->ion_auth->is_admin() || permissions('project_view') || permissions('task_view') || permissions('gantt_view') || permissions('calendar_view')) && (is_module_allowed('projects') || is_module_allowed('tasks') || is_module_allowed('timesheet') || is_module_allowed('gantt') || is_module_allowed('calendar'))){ ?>           
                <li class="dropdown <?=((current_url() == base_url('projects') || $this->uri->segment(2) == 'detail' || $this->uri->segment(2) == 'list') || (current_url() == base_url('projects/tasks') || $this->uri->segment(2) == 'tasks-list') || current_url() == base_url('projects/timesheet') || current_url() == base_url('projects/gantt') || current_url() == base_url('projects/calendar') )?'active':''; ?>">

        <a class="nav-link has-dropdown" href="#"><i class="fas fa-rocket text-info"></i> 
        <span><?=$this->lang->line('pms')?$this->lang->line('pms'):'PMS'?></span></a>
          <ul class="dropdown-menu">
            <?php if(is_module_allowed('projects') && ($this->ion_auth->is_admin() || permissions('project_view'))){ ?>
              <li <?=(current_url() == base_url('projects/list') || $this->uri->segment(2) == 'detail' || $this->uri->segment(2) == 'list')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('projects/list')?>"><span><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></span></a></li>
            <?php } ?>

            <?php if(is_module_allowed('tasks') && ($this->ion_auth->is_admin() || permissions('task_view'))){ ?>
              <li <?=(current_url() == base_url('projects/tasks') || $this->uri->segment(2) == 'tasks')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('projects/tasks')?>"> <span><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></span></a></li>
            <?php } ?>

            <?php if( is_module_allowed('timesheet') && ($this->ion_auth->is_admin() || permissions('task_view'))){ ?>
              <li <?=(current_url() == base_url('projects/timesheet') || $this->uri->segment(2) == 'timesheet')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('projects/timesheet')?>"> <span><?=$this->lang->line('timesheet')?$this->lang->line('timesheet'):'Timesheet'?></span></a></li>
            <?php } ?>

            <?php if(($this->ion_auth->is_admin() || permissions('gantt_view')) && is_module_allowed('gantt')){ ?>
              <li <?=(current_url() == base_url('projects/gantt') || $this->uri->segment(2) == 'gantt')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('projects/gantt')?>"><span><?=$this->lang->line('gantt')?$this->lang->line('gantt'):'Gantt'?></span></a></li>
            <?php } ?>

            <?php if($this->ion_auth->is_admin() || permissions('calendar_view')){ ?>
              <li <?=(current_url() == base_url('projects/calendar') || $this->uri->segment(2) == 'calendar')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('projects/calendar')?>"><span><?=$this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar'?></span></a></li>
            <?php } ?>

          </ul>
        </li>
      <?php } ?>

      <?php if(($this->ion_auth->is_admin() || permissions('user_view')) && is_module_allowed('team_members') ){ ?>
        <li <?=(current_url() == base_url('users'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users')?>"><i class="fas fa-users text-warning"></i><span><?=$this->lang->line('employees')?$this->lang->line('employees'):'Employees'?></span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('chat_view')) && !$this->ion_auth->in_group(3) && is_module_allowed('chat')){ ?>  
        <li <?= (current_url() == base_url('chat'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('chat')?>"><i class="fas fa-comment-alt text-dark"></i> <span><?=$this->lang->line('chat')?$this->lang->line('chat'):'Chat'?></span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('todo_view')) && !$this->ion_auth->in_group(3) && is_module_allowed('todo')){ ?>  
        <li <?= (current_url() == base_url('todo'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('todo')?>"><i class="fas fa-clipboard-check text-primary"></i> <span><?=$this->lang->line('to_do')?$this->lang->line('to_do'):'Todo'?></span></a></li>
      <?php } ?>
      
      <?php if (($this->ion_auth->is_admin() || permissions('notes_view')) && !$this->ion_auth->in_group(3) && is_module_allowed('notes')){ ?>  
        <li <?= (current_url() == base_url('notes'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('notes')?>"><i class="fas fa-sticky-note text-success"></i> <span><?=$this->lang->line('notes')?$this->lang->line('notes'):'Notes'?></span></a></li>
      <?php } ?>

      <!-- <?php if($this->ion_auth->is_admin() || permissions('meetings_view')){ ?>
        <li <?=(current_url() == base_url('meetings'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('meetings')?>"><span><?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?></span></a></li>
      <?php } ?> -->

      <?php if(($this->ion_auth->is_admin() || permissions('client_view') || permissions('lead_view')) && (is_module_allowed('clients') || is_module_allowed('leads')) ){ ?>           
        <li class="dropdown <?=((current_url() == base_url('users/client') || current_url() == base_url('leads')) )?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-handshake text-info"></i>  
        <span ><?=$this->lang->line('crm')?$this->lang->line('crm'):'CRM'?></span></a>
          <ul class="dropdown-menu">

            <?php if(is_module_allowed('clients') && ($this->ion_auth->is_admin() || permissions('client_view'))){ ?>
              <li <?=(current_url() == base_url('users/client'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users/client')?>"><span><?=$this->lang->line('clients')?$this->lang->line('clients'):'Clients'?></span></a></li>
            <?php } ?>

            <?php if(is_module_allowed('leads') && ($this->ion_auth->is_admin() || permissions('lead_view'))){ ?>
              <li <?=(current_url() == base_url('leads'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('leads')?>"><span><?=$this->lang->line('leads')?$this->lang->line('leads'):'Leads'?></span></a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>

      <!-- <?php if(($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)) && (is_module_allowed('invoices') || is_module_allowed('estimates') || is_module_allowed('taxes'))){ ?>           
        <li class="dropdown <?=((current_url() == base_url('invoices') || current_url() == base_url('estimates') || current_url() == base_url('products') || current_url() == base_url('settings/taxes') || $this->uri->segment(1) == 'invoices' || $this->uri->segment(1) == 'estimates') && ($this->uri->segment(2) != 'payments'))?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-shopping-cart text-warning"></i> 
        <span><?=$this->lang->line('sales')?$this->lang->line('sales'):'Sales'?></span></a>
          <ul class="dropdown-menu">
            <?php if(is_module_allowed('invoices')){ ?>
              <li <?=(current_url() == base_url('invoices') || $this->uri->segment(1) == 'invoices' && ($this->uri->segment(2) != 'payments'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('invoices')?>"><?=$this->lang->line('invoices')?$this->lang->line('invoices'):'Invoices'?></a></li> 
            <?php } ?>

            <?php if(is_module_allowed('estimates')){ ?>
              <li <?=(current_url() == base_url('estimates') || $this->uri->segment(1) == 'estimates')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('estimates')?>"><?=$this->lang->line('estimates')?$this->lang->line('estimates'):'Estimates'?></a></li> 
            <?php } ?>

            <?php if($this->ion_auth->is_admin() && is_module_allowed('estimates')){ ?>
              <li <?=(current_url() == base_url('products'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('products')?>"><?=$this->lang->line('products')?$this->lang->line('products'):'Products'?></a></li>
            <?php } ?>

            <?php if($this->ion_auth->is_admin() && is_module_allowed('taxes')){ ?>
              <li <?=(current_url() == base_url('settings/taxes'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/taxes')?>"><?=$this->lang->line('taxes')?$this->lang->line('taxes'):'Taxes'?></a></li>
            <?php } ?>

          </ul>
        </li>
      <?php } ?> -->

      <!-- <?php if(($this->ion_auth->is_admin() || $this->ion_auth->in_group(4)) && (is_module_allowed('payments') || is_module_allowed('expenses'))){ ?>           
        <li class="dropdown <?=(current_url() == base_url('invoices/payments') || $this->uri->segment(2) == 'payments'|| current_url() == base_url('expenses'))?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-credit-card text-dark"></i> 
        <span><?=$this->lang->line('finance')?$this->lang->line('finance'):'Finance'?></span></a>
          <ul class="dropdown-menu">
            <?php if(is_module_allowed('payments')){ ?>
              <li <?=(current_url() == base_url('invoices/payments') || $this->uri->segment(2) == 'payments')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('invoices/payments')?>"><?=$this->lang->line('payments')?$this->lang->line('payments'):'Payments'?><?=$this->ion_auth->is_admin()?' / '.($this->lang->line('income')?htmlspecialchars($this->lang->line('income')):'Income'):''?></a></li>
            <?php } ?>

            <?php if($this->ion_auth->is_admin() && is_module_allowed('expenses')){ ?>
              <li <?=(current_url() == base_url('expenses'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('expenses')?>"><?=$this->lang->line('expenses')?$this->lang->line('expenses'):'Expenses'?></a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?> -->
      
      <?php if ($this->ion_auth->is_admin()){ ?>  
        <li <?= (current_url() == base_url('plans'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('plans')?>"><i class="fas fa-dollar-sign text-dark"></i> <span><?=$this->lang->line('subscription_plans')?$this->lang->line('subscription_plans'):'Plans'?></span></a></li>
      <?php } ?>
      
      <?php if(($this->ion_auth->is_admin() || permissions('reports_view')) && is_module_allowed('reports')){ ?>           
        <li class="dropdown <?=(current_url() == base_url('reports') || $this->uri->segment(1) == 'reports')?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-chart-bar text-primary"></i> 
        <span><?=$this->lang->line('reports')?$this->lang->line('reports'):'Reports'?></span></a>
          <ul class="dropdown-menu">

              <li <?=(current_url() == base_url('reports/projects'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/projects')?>"><?=$this->lang->line('projects')?htmlspecialchars($this->lang->line('projects')):'Projects'?></a></li>

              <li <?=(current_url() == base_url('reports/tasks'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/tasks')?>"><?=$this->lang->line('tasks')?htmlspecialchars($this->lang->line('tasks')):'Tasks'?></a></li>
              
              <li <?=(current_url() == base_url('reports/clients'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/clients')?>"><?=$this->lang->line('clients')?htmlspecialchars($this->lang->line('clients')):'Clients'?></a></li>

              <li <?=(current_url() == base_url('reports/team'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/team')?>"><?=$this->lang->line('team_members')?htmlspecialchars($this->lang->line('team_members')):'Team Members'?></a></li>

              <!--<li <?=(current_url() == base_url('reports/meetings'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/meetings')?>"><?=$this->lang->line('video_meetings')?htmlspecialchars($this->lang->line('video_meetings')):'Video Meetings'?></a></li>-->

              <li <?=(current_url() == base_url('reports/leads'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/leads')?>"><?=$this->lang->line('leads')?htmlspecialchars($this->lang->line('leads')):'Leads'?></a></li>
              
              <li <?=(current_url() == base_url('reports/timesheet'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/timesheet')?>"><?=$this->lang->line('timesheet')?htmlspecialchars($this->lang->line('timesheet')):'Timesheet'?></a></li>

              <li <?=(current_url() == base_url('reports/leaves'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/leaves')?>"><?=$this->lang->line('leaves')?htmlspecialchars($this->lang->line('leaves')):'Leaves'?></a></li>

              <li <?=(current_url() == base_url('reports/attendance'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/attendance')?>"><?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance'?></a></li>

              <!-- <li <?=(current_url() == base_url('reports/estimates'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/estimates')?>"><?=$this->lang->line('estimates')?htmlspecialchars($this->lang->line('estimates')):'Estimates'?></a></li> -->

              <!--<li <?=(current_url() == base_url('reports/income'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/income')?>"><?=$this->lang->line('income')?$this->lang->line('income'):'Income'?></a></li>-->

              <!--<li <?=(current_url() == base_url('reports/expenses'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports/expenses')?>"><?=$this->lang->line('expenses')?$this->lang->line('expenses'):'Expenses'?></a></li> -->

              <!--<li <?=(current_url() == base_url('reports'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('reports')?>"><?=$this->lang->line('income_vs_expenses')?$this->lang->line('income_vs_expenses'):'Income VS Expenses'?></a></li>-->
          </ul>
        </li>
      <?php } ?>

      <?php if ($this->ion_auth->in_group(3)){ ?> 
      <li class="dropdown <?=($this->uri->segment(1) == 'plans' || current_url() == base_url('users/saas'))?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa fa-dollar-sign text-success"></i> 
        <span><?=$this->lang->line('subscription')?htmlspecialchars($this->lang->line('subscription')):'Subscription'?></span></a>
        <ul class="dropdown-menu">

          <li <?=(current_url() == base_url('plans'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('plans')?>"><?=$this->lang->line('subscription_plans')?$this->lang->line('subscription_plans'):'Plans'?></a></li>

          <li <?=(current_url() == base_url('plans/orders'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('plans/orders')?>"><?=$this->lang->line('orders')?$this->lang->line('orders'):'Orders'?></a></li>

          <li <?=(current_url() == base_url('plans/offline-requests'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('plans/offline-requests')?>"><?=$this->lang->line('offline_requests')?$this->lang->line('offline_requests'):'Offline Requests'?></a></li>

          <li <?=(current_url() == base_url('users/saas'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users/saas')?>"><?=$this->lang->line('subscribers')?htmlspecialchars($this->lang->line('subscribers')):'Subscribers'?></a></li>

        </ul>
      </li>


      <li class="dropdown <?=($this->uri->segment(1) == 'front')?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-puzzle-piece text-info"></i> 
        <span><?=$this->lang->line('frontend')?$this->lang->line('frontend'):'Frontend'?></span></a>
        <ul class="dropdown-menu">

          <li <?=(current_url() == base_url('front/landing'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('front/landing')?>"><?=$this->lang->line('general')?$this->lang->line('general'):'General'?></a></li>

          <li <?=(current_url() == base_url('front/features'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('front/features')?>"><?=$this->lang->line('features')?$this->lang->line('features'):'Features'?></a></li>

          <li <?=(current_url() == base_url('front/about'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('front/about')?>"><?=$this->lang->line('about')?$this->lang->line('about'):'About Us'?></a></li>

          <li <?=(current_url() == base_url('front/saas-privacy-policy'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('front/saas-privacy-policy')?>"><?=$this->lang->line('privacy_policy')?$this->lang->line('privacy_policy'):'Privacy Policy'?></a></li>

          <li <?=(current_url() == base_url('front/saas-terms-and-conditions'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('front/saas-terms-and-conditions')?>"><?=$this->lang->line('terms_and_conditions')?$this->lang->line('terms_and_conditions'):'Terms and Conditions'?></a></li>

        </ul>
      </li>

      <li <?= (current_url() == base_url('users'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users')?>"><i class="fas fa-user-tie text-success"></i> <span><?=$this->lang->line('saas_admins')?$this->lang->line('saas_admins'):'SaaS Admins'?></span></a></li>
      
      <?php } ?> 


      <?php if($this->ion_auth->is_admin() || $this->ion_auth->in_group(3) || permissions('general_view') || permissions('company_view') || permissions('leave_type_view') || permissions('device_view') || permissions('departments_view') || permissions('shift_view') || permissions('time_schedule_view')){ ?>   

        <?php if (is_module_allowed('support') && ($this->ion_auth->is_admin() || permissions('support_view'))){ ?> 
        <li <?=($this->uri->segment(1) == 'support')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('support')?>"><i class="fas fa-question-circle text-success"></i> <span><?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?></a></li>
        <?php } ?>

        <li class="dropdown  mb-3 <?=($this->uri->segment(1) == 'settings' || $this->uri->segment(1) == 'languages')?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-cog text-info"></i> 
        <span><?=$this->lang->line('settings')?$this->lang->line('settings'):'Settings'?></span></a>
          <ul class="dropdown-menu">

            <?php if($this->ion_auth->is_admin() || permissions('general_view')){ ?>
              <li <?=(current_url() == base_url('settings'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings')?>"><?=$this->lang->line('general')?$this->lang->line('general'):'General'?></a></li>
            <?php } ?>

            <!-- <?php if (is_module_allowed('payment_gateway')){ ?> 
              <li <?=(current_url() == base_url('settings/payment'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/payment')?>"><?=$this->lang->line('payment_gateway')?$this->lang->line('payment_gateway'):'Payment Gateway'?></a></li>
            <?php } ?> -->

            <?php if ($this->ion_auth->in_group(3)){ ?> 

              <li <?=(current_url() == base_url('settings/seo'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/seo')?>"><?=$this->lang->line('seo')?$this->lang->line('seo'):'SEO'?></a></li>

              <li <?=(current_url() == base_url('settings/logins'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/logins')?>"><?=$this->lang->line('social_login')?htmlspecialchars($this->lang->line('social_login')):'Social Login'?></a></li>

              <li <?=(current_url() == base_url('settings/email'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/email')?>"><?=$this->lang->line('email')?$this->lang->line('email'):'Email'?></a></li>
              
              <li <?=(current_url() == base_url('settings/email-templates'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/email-templates')?>"><?=$this->lang->line('email_templates')?$this->lang->line('email_templates'):'Email Templates'?></a></li>

              <li <?=(current_url() == base_url('languages'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('languages')?>"><?=$this->lang->line('languages')?$this->lang->line('languages'):'Languages'?></a></li>

              <li <?=(current_url() == base_url('settings/update'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/update')?>"><?=$this->lang->line('update')?$this->lang->line('update'):'Update'?></a></li>

              <li <?=(current_url() == base_url('settings/recaptcha'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/recaptcha')?>"><?=$this->lang->line('google_recaptcha')?$this->lang->line('google_recaptcha'):'Google reCAPTCHA'?></a></li>

              <li <?=(current_url() == base_url('settings/custom-code'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/custom-code')?>"><?=$this->lang->line('custom_code')?$this->lang->line('custom_code'):'Custom Code'?></a></li>

            <?php }else{ ?>
              <?php if($this->ion_auth->is_admin() || permissions('company_view')){ ?>
                <li <?=(current_url() == base_url('settings/company'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/company')?>"><?=$this->lang->line('company')?$this->lang->line('company'):'Company'?></a></li>
              <?php } ?>

              <?php if($this->ion_auth->is_admin() || permissions('leave_type_view')){ ?>
                <li <?=(current_url() == base_url('settings/leaves'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/leaves')?>"><?=$this->lang->line('leave_type')?$this->lang->line('leave_type'):'Leave Type'?></a></li>
              <?php } ?>

              <?php if($this->ion_auth->is_admin() || permissions('device_view')){ ?>
                <li <?=(current_url() == base_url('settings/device_config'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/device_config')?>"><?=$this->lang->line('device_config')?$this->lang->line('device_config'):'Device Configuration'?></a></li>
              <?php } ?>

              <?php if($this->ion_auth->is_admin() || permissions('departments_view')){ ?>
                <li <?=(current_url() == base_url('settings/departments'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/departments')?>"><?=$this->lang->line('departments')?$this->lang->line('departments'):'Departments'?></a></li>
              <?php } ?>
              
              <?php if($this->ion_auth->is_admin() || permissions('shift_view')){ ?>
                <li <?=(current_url() == base_url('settings/shift'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/shift')?>"><?=$this->lang->line('shift_schedule')?$this->lang->line('shift_schedule'):'Shift Schedule'?></a></li>
              <?php } ?>

              <?php if($this->ion_auth->is_admin() || permissions('time_schedule_view')){ ?>
                <li <?=(current_url() == base_url('settings/department'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/department')?>"><?=$this->lang->line('time_schedule')?$this->lang->line('time_schedule'):'Time Schedule'?></a></li>
              <?php } ?>

              <!-- <?php if (is_module_allowed('taxes')){ ?>
                <li <?=(current_url() == base_url('settings/taxes'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/taxes')?>"><?=$this->lang->line('taxes')?$this->lang->line('taxes'):'Taxes'?></a></li>
              <?php } ?>  -->
              
              <?php if ($this->ion_auth->is_admin() && is_module_allowed('user_permissions') ){ ?> 
                <li <?=(current_url() == base_url('settings/roles'))?'class="active"':''; ?>><a class="nav-link  " href="<?=base_url('settings/roles')?>"><?=$this->lang->line('roles')?$this->lang->line('roles'):'Roles'?></a></li>
                <li <?=(current_url() == base_url('settings/roles-permissions'))?'class="active"':''; ?>><a class="nav-link  mb-3" href="<?=base_url('settings/roles-permissions')?>"><?=$this->lang->line('roles_permissions')?$this->lang->line('roles_permissions'):'Permissions'?></a></li>
              <?php } ?>
              
            <?php } ?>


          </ul>
        </li>
      <?php } ?>
      
    </ul>
  </aside>

</div>