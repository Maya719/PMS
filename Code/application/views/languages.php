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
              <h1><?=$this->lang->line('edit_language')?htmlspecialchars($this->lang->line('edit_language')):'Edit Language'?></h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?htmlspecialchars($this->lang->line('dashboard')):'Dashboard'?></a></div>
                <div class="breadcrumb-item active"><a href="<?=base_url('languages')?>"><?=$this->lang->line('languages')?htmlspecialchars($this->lang->line('languages')):'Languages'?></a></div>
                <div class="breadcrumb-item"><?=$this->lang->line('edit_language')?htmlspecialchars($this->lang->line('edit_language')):'Edit Language'?></div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">


                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column">
                                <?php
                                $current_lang = get_languages('', $this->uri->segment(3));
                                foreach($languages as $kay => $lan){ ?>
                                <li class="nav-item">
                                    <a class="nav-link <?=$lan['language']==$this->uri->segment(3)?'active':''?>" href="<?=base_url('languages/editing/'.$lan['language'])?>"><?=ucfirst(htmlspecialchars($lan['language']))?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>



                <div class="col-md-9">
                    <div class="card card-primary" id="language-card">
                        <form action="<?=base_url('languages/edit')?>" method="POST" id="language-form">
                            <div class="card-header">
                              <h4><?=$this->lang->line('editing')?htmlspecialchars($this->lang->line('editing')):'Editing'?> <?=ucfirst(htmlspecialchars($this->uri->segment(3)))?></h4>
                            </div>
                            <div class="card-body row">

                                <div class="form-group col-md-4">
                                    <label><?=$this->lang->line('language_name')?$this->lang->line('language_name'):'Language name'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')?htmlspecialchars($this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')):"Don't edit it if you don't want to edit language name."?>"></i></label>
                                    <input type="text" name="language_lang" value="<?=ucfirst(htmlspecialchars($this->uri->segment(3)))?>" class="form-control">
                                    <input type="hidden" name="update_lang" value="<?=htmlspecialchars($this->uri->segment(3))?>" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label><?=$this->lang->line('short_code')?$this->lang->line('short_code'):'Short Code'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')?htmlspecialchars($this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')):"Don't edit it if you don't want to edit language name."?>"></i></label>
                                    <input type="text" name="short_code_lang" value="<?=isset($current_lang[0]['short_code'])?htmlspecialchars($current_lang[0]['short_code']):''?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label><?=$this->lang->line('is_rtl')?htmlspecialchars($this->lang->line('is_rtl')):'Is RTL'?></label>
                                    <select name="active_lang" class="form-control">
                                        <option value="0" <?=(isset($current_lang[0]['active']) && $current_lang[0]['active']==0)?'selected':''?>>NO RTL</option>
                                        <option value="1" <?=(isset($current_lang[0]['active']) && $current_lang[0]['active']==1)?'selected':''?>>RTL</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></label>
                                    <select name="status_lang" class="form-control">
                                        <option value="0" <?=(isset($current_lang[0]['status']) && $current_lang[0]['status']==0)?'selected':''?>><?=$this->lang->line('deactive')?htmlspecialchars($this->lang->line('deactive')):'Deactive'?></option>
                                        <option value="1" <?=(isset($current_lang[0]['status']) && $current_lang[0]['status']==1)?'selected':''?>><?=$this->lang->line('active')?htmlspecialchars($this->lang->line('active')):'Active'?></option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Dashboard</label>
                                    <input type="text" name="dashboard" value="<?=$this->lang->line('dashboard')?htmlspecialchars($this->lang->line('dashboard')):'Dashboard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Editing</label>
                                    <input type="text" name="editing" value="<?=$this->lang->line('editing')?htmlspecialchars($this->lang->line('editing')):'Editing'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pages</label>
                                    <input type="text" name="pages" value="<?=$this->lang->line('pages')?htmlspecialchars($this->lang->line('pages')):'Pages'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Page Title</label>
                                    <input type="text" name="pages_title" value="<?=$this->lang->line('pages_title')?htmlspecialchars($this->lang->line('pages_title')):'Page Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Page Content</label>
                                    <input type="text" name="pages_content" value="<?=$this->lang->line('pages_content')?htmlspecialchars($this->lang->line('pages_content')):'Page Content'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Taxes</label>
                                    <input type="text" name="taxes" value="<?=$this->lang->line('taxes')?htmlspecialchars($this->lang->line('taxes')):'Taxes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tax Name</label>
                                    <input type="text" name="tax_name" value="<?=$this->lang->line('tax_name')?htmlspecialchars($this->lang->line('tax_name')):'Tax Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tax Rate(%)</label>
                                    <input type="text" name="tax_rate" value="<?=$this->lang->line('tax_rate')?htmlspecialchars($this->lang->line('tax_rate')):'Tax Rate(%)'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Currency Symbol</label>
                                    <input type="text" name="currency_symbol" value="<?=$this->lang->line('currency_symbol')?htmlspecialchars($this->lang->line('currency_symbol')):'Currency Symbol'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Currency Code</label>
                                    <input type="text" name="currency_code" value="<?=$this->lang->line('currency_code')?htmlspecialchars($this->lang->line('currency_code')):'Currency Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit</label>
                                    <input type="text" name="edit" value="<?=$this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Chat</label>
                                    <input type="text" name="select_chat" value="<?=$this->lang->line('select_chat')?htmlspecialchars($this->lang->line('select_chat')):'Select Chat'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New Message</label>
                                    <input type="text" name="new_message" value="<?=$this->lang->line('new_message')?htmlspecialchars($this->lang->line('new_message')):'New Message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Enable OR Disable Sections</label>
                                    <input type="text" name="enable_or_disable_sections" value="<?=$this->lang->line('enable_or_disable_sections')?htmlspecialchars($this->lang->line('enable_or_disable_sections')):'Enable OR Disable Sections'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Frontend Customization</label>
                                    <input type="text" name="frontend_customization" value="<?=$this->lang->line('frontend_customization')?htmlspecialchars($this->lang->line('frontend_customization')):'Frontend Customization'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contact Form</label>
                                    <input type="text" name="contact_form" value="<?=$this->lang->line('contact_form')?htmlspecialchars($this->lang->line('contact_form')):'Contact Form'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Plans</label>
                                    <input type="text" name="subscription_plans" value="<?=$this->lang->line('subscription_plans')?htmlspecialchars($this->lang->line('subscription_plans')):'Plans'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Plan</label>
                                    <input type="text" name="plan" value="<?=$this->lang->line('plan')?htmlspecialchars($this->lang->line('plan')):'Plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>All</label>
                                    <input type="text" name="all" value="<?=$this->lang->line('all')?htmlspecialchars($this->lang->line('all')):'All'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Total</label>
                                    <input type="text" name="total" value="<?=$this->lang->line('total')?htmlspecialchars($this->lang->line('total')):'Total'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subtotal</label>
                                    <input type="text" name="subtotal" value="<?=$this->lang->line('subtotal')?htmlspecialchars($this->lang->line('subtotal')):'Subtotal'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Item</label>
                                    <input type="text" name="item" value="<?=$this->lang->line('item')?htmlspecialchars($this->lang->line('item')):'Item'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Completed</label>
                                    <input type="text" name="completed" value="<?=$this->lang->line('completed')?htmlspecialchars($this->lang->line('completed')):'Completed'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Rejected</label>
                                    <input type="text" name="rejected" value="<?=$this->lang->line('rejected')?htmlspecialchars($this->lang->line('rejected')):'Rejected'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Reject</label>
                                    <input type="text" name="reject" value="<?=$this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pending</label>
                                    <input type="text" name="pending" value="<?=$this->lang->line('pending')?htmlspecialchars($this->lang->line('pending')):'Pending'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Approved</label>
                                    <input type="text" name="approved" value="<?=$this->lang->line('approved')?htmlspecialchars($this->lang->line('approved')):'Approved'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Accepted</label>
                                    <input type="text" name="accepted" value="<?=$this->lang->line('accepted')?htmlspecialchars($this->lang->line('accepted')):'Accepted'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Accept</label>
                                    <input type="text" name="accept" value="<?=$this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Features</label>
                                    <input type="text" name="features" value="<?=$this->lang->line('features')?htmlspecialchars($this->lang->line('features')):'Features'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contact</label>
                                    <input type="text" name="contact" value="<?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Get Start</label>
                                    <input type="text" name="get_start" value="<?=$this->lang->line('get_start')?htmlspecialchars($this->lang->line('get_start')):'Get Start'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Monthly</label>
                                    <input type="text" name="monthly" value="<?=$this->lang->line('monthly')?htmlspecialchars($this->lang->line('monthly')):'Monthly'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Yearly</label>
                                    <input type="text" name="yearly" value="<?=$this->lang->line('yearly')?htmlspecialchars($this->lang->line('yearly')):'Yearly'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Unlimited</label>
                                    <input type="text" name="unlimited" value="<?=$this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Invoices</label>
                                    <input type="text" name="invoices" value="<?=$this->lang->line('invoices')?htmlspecialchars($this->lang->line('invoices')):'Invoices'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Invoice</label>
                                    <input type="text" name="invoice" value="<?=$this->lang->line('invoice')?htmlspecialchars($this->lang->line('invoice')):'Invoice'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Task Title</label>
                                    <input type="text" name="task_title" value="<?=$this->lang->line('task_title')?htmlspecialchars($this->lang->line('task_title')):'Task Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Projects</label>
                                    <input type="text" name="projects" value="<?=$this->lang->line('projects')?htmlspecialchars($this->lang->line('projects')):'Projects'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Project</label>
                                    <input type="text" name="project" value="<?=$this->lang->line('project')?htmlspecialchars($this->lang->line('project')):'Project'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Project</label>
                                    <input type="text" name="select_project" value="<?=$this->lang->line('select_project')?htmlspecialchars($this->lang->line('select_project')):'Select Project'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Details</label>
                                    <input type="text" name="details" value="<?=$this->lang->line('details')?htmlspecialchars($this->lang->line('details')):'Details'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Detail</label>
                                    <input type="text" name="detail" value="<?=$this->lang->line('detail')?htmlspecialchars($this->lang->line('detail')):'Detail'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Task Completed</label>
                                    <input type="text" name="task_completed" value="<?=$this->lang->line('task_completed')?htmlspecialchars($this->lang->line('task_completed')):'Task Completed'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Projects Detail</label>
                                    <input type="text" name="projects_detail" value="<?=$this->lang->line('projects_detail')?htmlspecialchars($this->lang->line('projects_detail')):'Projects Detail'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Latest</label>
                                    <input type="text" name="latest" value="<?=$this->lang->line('latest')?htmlspecialchars($this->lang->line('latest')):'Latest'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Old</label>
                                    <input type="text" name="old" value="<?=$this->lang->line('old')?htmlspecialchars($this->lang->line('old')):'Old'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create New Project</label>
                                    <input type="text" name="create_new_project" value="<?=$this->lang->line('create_new_project')?htmlspecialchars($this->lang->line('create_new_project')):'Create New Project'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sort By</label>
                                    <input type="text" name="sort_by" value="<?=$this->lang->line('sort_by')?htmlspecialchars($this->lang->line('sort_by')):'Sort By'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Clients</label>
                                    <input type="text" name="select_clients" value="<?=$this->lang->line('select_clients')?htmlspecialchars($this->lang->line('select_clients')):'Select Clients'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Users</label>
                                    <input type="text" name="select_users" value="<?=$this->lang->line('select_users')?htmlspecialchars($this->lang->line('select_users')):'Select Users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Status</label>
                                    <input type="text" name="select_status" value="<?=$this->lang->line('select_status')?htmlspecialchars($this->lang->line('select_status')):'Select Status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tasks</label>
                                    <input type="text" name="tasks" value="<?=$this->lang->line('tasks')?htmlspecialchars($this->lang->line('tasks')):'Tasks'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Task</label>
                                    <input type="text" name="task" value="<?=$this->lang->line('task')?htmlspecialchars($this->lang->line('task')):'Task'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Project Title</label>
                                    <input type="text" name="project_title" value="<?=$this->lang->line('project_title')?htmlspecialchars($this->lang->line('project_title')):'Project Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Trash</label>
                                    <input type="text" name="trash" value="<?=$this->lang->line('trash')?htmlspecialchars($this->lang->line('trash')):'Trash'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Low</label>
                                    <input type="text" name="low" value="<?=$this->lang->line('low')?htmlspecialchars($this->lang->line('low')):'Low'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Medium</label>
                                    <input type="text" name="medium" value="<?=$this->lang->line('medium')?htmlspecialchars($this->lang->line('medium')):'Medium'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>High</label>
                                    <input type="text" name="high" value="<?=$this->lang->line('high')?htmlspecialchars($this->lang->line('high')):'High'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Days</label>
                                    <input type="text" name="days" value="<?=$this->lang->line('days')?htmlspecialchars($this->lang->line('days')):'Days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Left</label>
                                    <input type="text" name="left" value="<?=$this->lang->line('left')?htmlspecialchars($this->lang->line('left')):'Left'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Discount(%)</label>
                                    <input type="text" name="discount" value="<?=$this->lang->line('discount')?htmlspecialchars($this->lang->line('discount')):'Discount(%)'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Overdue</label>
                                    <input type="text" name="overdue" value="<?=$this->lang->line('overdue')?htmlspecialchars($this->lang->line('overdue')):'Overdue'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Due</label>
                                    <input type="text" name="due" value="<?=$this->lang->line('due')?htmlspecialchars($this->lang->line('due')):'Due'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>User</label>
                                    <input type="text" name="user" value="<?=$this->lang->line('user')?htmlspecialchars($this->lang->line('user')):'User'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Task Detail</label>
                                    <input type="text" name="task_detail" value="<?=$this->lang->line('task_detail')?htmlspecialchars($this->lang->line('task_detail')):'Task Detail'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?=$this->lang->line('name')?htmlspecialchars($this->lang->line('name')):'Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="text" name="email" value="<?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload Project Files</label>
                                    <input type="text" name="upload_project_files" value="<?=$this->lang->line('upload_project_files')?htmlspecialchars($this->lang->line('upload_project_files')):'Upload Project Files'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Project Files</label>
                                    <input type="text" name="project_files" value="<?=$this->lang->line('project_files')?htmlspecialchars($this->lang->line('project_files')):'Project Files'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payment Gateway</label>
                                    <input type="text" name="payment_gateway" value="<?=$this->lang->line('payment_gateway')?htmlspecialchars($this->lang->line('payment_gateway')):'Payment Gateway'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Message</label>
                                    <input type="text" name="message" value="<?=$this->lang->line('message')?htmlspecialchars($this->lang->line('message')):'Message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Type your message</label>
                                    <input type="text" name="type_your_message" value="<?=$this->lang->line('type_your_message')?htmlspecialchars($this->lang->line('type_your_message')):'Type your message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send Message</label>
                                    <input type="text" name="send_message" value="<?=$this->lang->line('send_message')?htmlspecialchars($this->lang->line('send_message')):'Send Message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Login</label>
                                    <input type="text" name="login" value="<?=$this->lang->line('login')?htmlspecialchars($this->lang->line('login')):'Login'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send</label>
                                    <input type="text" name="send" value="<?=$this->lang->line('send')?htmlspecialchars($this->lang->line('send')):'Send'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Profile</label>
                                    <input type="text" name="profile" value="<?=$this->lang->line('profile')?htmlspecialchars($this->lang->line('profile')):'Profile'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="text" name="password" value="<?=$this->lang->line('password')?htmlspecialchars($this->lang->line('password')):'Password'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Encryption</label>
                                    <input type="text" name="encryption" value="<?=$this->lang->line('encryption')?htmlspecialchars($this->lang->line('encryption')):'Encryption'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Team Members and Client can chat?</label>
                                    <input type="text" name="team_embers_and_client_can_chat" value="<?=$this->lang->line('team_embers_and_client_can_chat')?htmlspecialchars($this->lang->line('team_embers_and_client_can_chat')):'Team Members and Client can chat?'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>General permissions</label>
                                    <input type="text" name="general_permissions" value="<?=$this->lang->line('general_permissions')?htmlspecialchars($this->lang->line('general_permissions')):'General permissions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send test mail to</label>
                                    <input type="text" name="send_test_mail_to" value="<?=$this->lang->line('send_test_mail_to')?htmlspecialchars($this->lang->line('send_test_mail_to')):'Send test mail to'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirm Password</label>
                                    <input type="text" name="confirm_password" value="<?=$this->lang->line('confirm_password')?htmlspecialchars($this->lang->line('confirm_password')):'Confirm Password'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Forgot Password</label>
                                    <input type="text" name="forgot_password" value="<?=$this->lang->line('forgot_password')?htmlspecialchars($this->lang->line('forgot_password')):'Forgot Password'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Remember Me</label>
                                    <input type="text" name="remember_me" value="<?=$this->lang->line('remember_me')?htmlspecialchars($this->lang->line('remember_me')):'Remember Me'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Don't have an account?</label>
                                    <input type="text" name="dont_have_an_account" value="<?=$this->lang->line('dont_have_an_account')?htmlspecialchars($this->lang->line('dont_have_an_account')):"Don't have an account?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create One</label>
                                    <input type="text" name="create_one" value="<?=$this->lang->line('create_one')?htmlspecialchars($this->lang->line('create_one')):'Create One'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Register</label>
                                    <input type="text" name="register" value="<?=$this->lang->line('register')?htmlspecialchars($this->lang->line('register')):'Register'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" value="<?=$this->lang->line('first_name')?htmlspecialchars($this->lang->line('first_name')):'First Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" value="<?=$this->lang->line('last_name')?htmlspecialchars($this->lang->line('last_name')):'Last Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password Confirmation</label>
                                    <input type="text" name="password_confirmation" value="<?=$this->lang->line('password_confirmation')?htmlspecialchars($this->lang->line('password_confirmation')):'Password Confirmation'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Already have an account?</label>
                                    <input type="text" name="already_have_an_account" value="<?=$this->lang->line('already_have_an_account')?htmlspecialchars($this->lang->line('already_have_an_account')):'Already have an account?'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Login Here</label>
                                    <input type="text" name="login_here" value="<?=$this->lang->line('login_here')?htmlspecialchars($this->lang->line('login_here')):'Login Here'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Notifications</label>
                                    <input type="text" name="notifications" value="<?=$this->lang->line('notifications')?htmlspecialchars($this->lang->line('notifications')):'Notifications'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>View</label>
                                    <input type="text" name="view" value="<?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User Statistics</label>
                                    <input type="text" name="user_statistics" value="<?=$this->lang->line('user_statistics')?htmlspecialchars($this->lang->line('user_statistics')):'User Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tasks Statistics</label>
                                    <input type="text" name="tasks_statistics" value="<?=$this->lang->line('tasks_statistics')?htmlspecialchars($this->lang->line('tasks_statistics')):'Tasks Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Project Statistics</label>
                                    <input type="text" name="project_statistics" value="<?=$this->lang->line('project_statistics')?htmlspecialchars($this->lang->line('project_statistics')):'Project Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No new notifications</label>
                                    <input type="text" name="no_new_notifications" value="<?=$this->lang->line('no_new_notifications')?htmlspecialchars($this->lang->line('no_new_notifications')):'No new notifications.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>View All</label>
                                    <input type="text" name="view_all" value="<?=$this->lang->line('view_all')?htmlspecialchars($this->lang->line('view_all')):'View All'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Clients</label>
                                    <input type="text" name="clients" value="<?=$this->lang->line('clients')?htmlspecialchars($this->lang->line('clients')):'Clients'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ToDo</label>
                                    <input type="text" name="todo" value="<?=$this->lang->line('todo')?htmlspecialchars($this->lang->line('todo')):'ToDo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit ToDo</label>
                                    <input type="text" name="edit_todo" value="<?=$this->lang->line('edit_todo')?htmlspecialchars($this->lang->line('edit_todo')):'Edit ToDo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create New ToDo</label>
                                    <input type="text" name="create_new_dodo" value="<?=$this->lang->line('create_new_dodo')?htmlspecialchars($this->lang->line('create_new_dodo')):'Create New ToDo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Notes</label>
                                    <input type="text" name="notes" value="<?=$this->lang->line('notes')?htmlspecialchars($this->lang->line('notes')):'Notes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Note</label>
                                    <input type="text" name="note" value="<?=$this->lang->line('note')?htmlspecialchars($this->lang->line('note')):'Note'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create New Note</label>
                                    <input type="text" name="create_new_note" value="<?=$this->lang->line('create_new_note')?htmlspecialchars($this->lang->line('create_new_note')):'Create New Note'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Note</label>
                                    <input type="text" name="edit_note" value="<?=$this->lang->line('edit_note')?htmlspecialchars($this->lang->line('edit_note')):'Edit Note'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Chat</label>
                                    <input type="text" name="chat" value="<?=$this->lang->line('chat')?htmlspecialchars($this->lang->line('chat')):'Chat'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Orders</label>
                                    <input type="text" name="orders" value="<?=$this->lang->line('orders')?htmlspecialchars($this->lang->line('orders')):'Orders'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>offline / bank transfer requests</label>
                                    <input type="text" name="offline_requests" value="<?=$this->lang->line('offline_requests')?htmlspecialchars($this->lang->line('offline_requests')):'offline / bank transfer requests'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Transactions</label>
                                    <input type="text" name="transactions" value="<?=$this->lang->line('transactions')?htmlspecialchars($this->lang->line('transactions')):'Transactions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Transaction</label>
                                    <input type="text" name="transaction" value="<?=$this->lang->line('transaction')?htmlspecialchars($this->lang->line('transaction')):'Transaction'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Users and Plan</label>
                                    <input type="text" name="users_and_plan" value="<?=$this->lang->line('users_and_plan')?htmlspecialchars($this->lang->line('users_and_plan')):'Users and Plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Users and Plans</label>
                                    <input type="text" name="users_and_plans" value="<?=$this->lang->line('users_and_plans')?htmlspecialchars($this->lang->line('users_and_plans')):'Users and Plans'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SaaS Admins</label>
                                    <input type="text" name="saas_admins" value="<?=$this->lang->line('saas_admins')?htmlspecialchars($this->lang->line('saas_admins')):'SaaS Admins'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Admin</label>
                                    <input type="text" name="admin" value="<?=$this->lang->line('admin')?htmlspecialchars($this->lang->line('admin')):'Admin'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Frontend</label>
                                    <input type="text" name="frontend" value="<?=$this->lang->line('frontend')?htmlspecialchars($this->lang->line('frontend')):'Frontend'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Settings</label>
                                    <input type="text" name="settings" value="<?=$this->lang->line('settings')?htmlspecialchars($this->lang->line('settings')):'Settings'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>General</label>
                                    <input type="text" name="general" value="<?=$this->lang->line('general')?htmlspecialchars($this->lang->line('general')):'General'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Print</label>
                                    <input type="text" name="print" value="<?=$this->lang->line('print')?htmlspecialchars($this->lang->line('print')):'Print'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Process Payment</label>
                                    <input type="text" name="process_payment" value="<?=$this->lang->line('process_payment')?htmlspecialchars($this->lang->line('process_payment')):'Process Payment'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payment Type</label>
                                    <input type="text" name="payment_type" value="<?=$this->lang->line('payment_type')?htmlspecialchars($this->lang->line('payment_type')):'Payment Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payment Date</label>
                                    <input type="text" name="payment_date" value="<?=$this->lang->line('payment_date')?htmlspecialchars($this->lang->line('payment_date')):'Payment Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payment</label>
                                    <input type="text" name="payment" value="<?=$this->lang->line('payment')?htmlspecialchars($this->lang->line('payment')):'Payment'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payments</label>
                                    <input type="text" name="payments" value="<?=$this->lang->line('payments')?htmlspecialchars($this->lang->line('payments')):'Payments'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payment Status</label>
                                    <input type="text" name="payment_status" value="<?=$this->lang->line('payment_status')?htmlspecialchars($this->lang->line('payment_status')):'Payment Status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Languages</label>
                                    <input type="text" name="languages" value="<?=$this->lang->line('languages')?htmlspecialchars($this->lang->line('languages')):'Languages'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Language</label>
                                    <input type="text" name="edit_language" value="<?=$this->lang->line('edit_language')?htmlspecialchars($this->lang->line('edit_language')):'Edit Language'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Update</label>
                                    <input type="text" name="update" value="<?=$this->lang->line('update')?htmlspecialchars($this->lang->line('update')):'Update'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Project</label>
                                    <input type="text" name="edit_project" value="<?=$this->lang->line('edit_project')?htmlspecialchars($this->lang->line('edit_project')):'Edit Project'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Delete</label>
                                    <input type="text" name="delete" value="<?=$this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Budget</label>
                                    <input type="text" name="budget" value="<?=$this->lang->line('budget')?htmlspecialchars($this->lang->line('budget')):'Budget'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Show project budget</label>
                                    <input type="text" name="show_project_budget" value="<?=$this->lang->line('show_project_budget')?htmlspecialchars($this->lang->line('show_project_budget')):'Show project budget'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Can change task status</label>
                                    <input type="text" name="can_change_task_status" value="<?=$this->lang->line('can_change_task_status')?htmlspecialchars($this->lang->line('can_change_task_status')):'Can change task status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Client Detail</label>
                                    <input type="text" name="client_detail" value="<?=$this->lang->line('client_detail')?htmlspecialchars($this->lang->line('client_detail')):'Client Detail'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>View Tasks</label>
                                    <input type="text" name="view_tasks" value="<?=$this->lang->line('view_tasks')?htmlspecialchars($this->lang->line('view_tasks')):'View Tasks'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Search</label>
                                    <input type="text" name="search" value="<?=$this->lang->line('search')?htmlspecialchars($this->lang->line('search')):'Search'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google Analytics</label>
                                    <input type="text" name="google_analytics" value="<?=$this->lang->line('google_analytics')?htmlspecialchars($this->lang->line('google_analytics')):'Google Analytics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Footer Text</label>
                                    <input type="text" name="footer_text" value="<?=$this->lang->line('footer_text')?htmlspecialchars($this->lang->line('footer_text')):'Footer Text'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Company Name</label>
                                    <input type="text" name="company_name" value="<?=$this->lang->line('company_name')?htmlspecialchars($this->lang->line('company_name')):'Company Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Company</label>
                                    <input type="text" name="company" value="<?=$this->lang->line('company')?htmlspecialchars($this->lang->line('company')):'Company'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User Permissions</label>
                                    <input type="text" name="user_permissions" value="<?=$this->lang->line('user_permissions')?htmlspecialchars($this->lang->line('user_permissions')):'User Permissions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Client Permissions</label>
                                    <input type="text" name="client_permissions" value="<?=$this->lang->line('client_permissions')?htmlspecialchars($this->lang->line('client_permissions')):'Client Permissions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Earnings</label>
                                    <input type="text" name="earnings" value="<?=$this->lang->line('earnings')?htmlspecialchars($this->lang->line('earnings')):'Earnings'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last days earning</label>
                                    <input type="text" name="last_days_earning" value="<?=$this->lang->line('last_days_earning')?htmlspecialchars($this->lang->line('last_days_earning')):'Last 30 days earning'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Users Statistics</label>
                                    <input type="text" name="users_statistics" value="<?=$this->lang->line('users_statistics')?htmlspecialchars($this->lang->line('users_statistics')):'Users Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Active</label>
                                    <input type="text" name="active" value="<?=$this->lang->line('active')?htmlspecialchars($this->lang->line('active')):'Active'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Deactive</label>
                                    <input type="text" name="deactive" value="<?=$this->lang->line('deactive')?htmlspecialchars($this->lang->line('deactive')):'Deactive'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>In Review</label>
                                    <input type="text" name="in_review" value="<?=$this->lang->line('in_review')?htmlspecialchars($this->lang->line('in_review')):'In Review'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>In Progress</label>
                                    <input type="text" name="in_progress" value="<?=$this->lang->line('in_progress')?htmlspecialchars($this->lang->line('in_progress')):'In Progress'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Finished</label>
                                    <input type="text" name="finished" value="<?=$this->lang->line('finished')?htmlspecialchars($this->lang->line('finished')):'Finished'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Today</label>
                                    <input type="text" name="today" value="<?=$this->lang->line('today')?htmlspecialchars($this->lang->line('today')):'Today'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upcoming</label>
                                    <input type="text" name="upcoming" value="<?=$this->lang->line('upcoming')?htmlspecialchars($this->lang->line('upcoming')):'Upcoming'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>On Going</label>
                                    <input type="text" name="on_going" value="<?=$this->lang->line('on_going')?htmlspecialchars($this->lang->line('on_going')):'On Going'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Not Started</label>
                                    <input type="text" name="not_started" value="<?=$this->lang->line('not_started')?htmlspecialchars($this->lang->line('not_started')):'Not Started'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Task Status</label>
                                    <input type="text" name="task_status" value="<?=$this->lang->line('task_status')?htmlspecialchars($this->lang->line('task_status')):'Task Status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Project Status</label>
                                    <input type="text" name="project_status" value="<?=$this->lang->line('project_status')?htmlspecialchars($this->lang->line('project_status')):'Project Status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit User</label>
                                    <input type="text" name="edit_user" value="<?=$this->lang->line('edit_user')?htmlspecialchars($this->lang->line('edit_user')):'Edit User'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User Profile</label>
                                    <input type="text" name="user_profile" value="<?=$this->lang->line('user_profile')?htmlspecialchars($this->lang->line('user_profile')):'User Profile'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Free</label>
                                    <input type="text" name="free" value="<?=$this->lang->line('free')?htmlspecialchars($this->lang->line('free')):'Free'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paid</label>
                                    <input type="text" name="paid" value="<?=$this->lang->line('paid')?htmlspecialchars($this->lang->line('paid')):'Paid'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Expired</label>
                                    <input type="text" name="expired" value="<?=$this->lang->line('expired')?htmlspecialchars($this->lang->line('expired')):'Expired'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Logout</label>
                                    <input type="text" name="logout" value="<?=$this->lang->line('logout')?htmlspecialchars($this->lang->line('logout')):'Logout'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create</label>
                                    <input type="text" name="create" value="<?=$this->lang->line('create')?htmlspecialchars($this->lang->line('create')):'Create'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Language name</label>
                                    <input type="text" name="language_name" value="<?=$this->lang->line('language_name')?htmlspecialchars($this->lang->line('language_name')):'Language name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create New User</label>
                                    <input type="text" name="create_new_user" value="<?=$this->lang->line('create_new_user')?htmlspecialchars($this->lang->line('create_new_user')):'Create New User'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Description</label>
                                    <input type="text" name="description" value="<?=$this->lang->line('description')?htmlspecialchars($this->lang->line('description')):'Description'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Priority</label>
                                    <input type="text" name="priority" value="<?=$this->lang->line('priority')?htmlspecialchars($this->lang->line('priority')):'Priority'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Comments</label>
                                    <input type="text" name="comments" value="<?=$this->lang->line('comments')?htmlspecialchars($this->lang->line('comments')):'Comments'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Attachments</label>
                                    <input type="text" name="attachments" value="<?=$this->lang->line('attachments')?htmlspecialchars($this->lang->line('attachments')):'Attachments'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Invoice Date</label>
                                    <input type="text" name="invoice_date" value="<?=$this->lang->line('invoice_date')?htmlspecialchars($this->lang->line('invoice_date')):'Invoice Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Due Date</label>
                                    <input type="text" name="due_date" value="<?=$this->lang->line('due_date')?htmlspecialchars($this->lang->line('due_date')):'Due Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pending Tasks</label>
                                    <input type="text" name="pending_tasks" value="<?=$this->lang->line('pending_tasks')?htmlspecialchars($this->lang->line('pending_tasks')):'Pending Tasks'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Completed Tasks</label>
                                    <input type="text" name="completed_tasks" value="<?=$this->lang->line('completed_tasks')?htmlspecialchars($this->lang->line('completed_tasks')):'Completed Tasks'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Total Tasks</label>
                                    <input type="text" name="total_tasks" value="<?=$this->lang->line('total_tasks')?htmlspecialchars($this->lang->line('total_tasks')):'Total Tasks'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ending Date</label>
                                    <input type="text" name="ending_date" value="<?=$this->lang->line('ending_date')?htmlspecialchars($this->lang->line('ending_date')):'Ending Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Starting Date</label>
                                    <input type="text" name="starting_date" value="<?=$this->lang->line('starting_date')?htmlspecialchars($this->lang->line('starting_date')):'Starting Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create Feature</label>
                                    <input type="text" name="create_feature" value="<?=$this->lang->line('create_feature')?htmlspecialchars($this->lang->line('create_feature')):'Create Feature'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Price</label>
                                    <input type="text" name="price_usd" value="<?=$this->lang->line('price_usd')?htmlspecialchars($this->lang->line('price_usd')):'Price'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Amount</label>
                                    <input type="text" name="amount_usd" value="<?=$this->lang->line('amount_usd')?htmlspecialchars($this->lang->line('amount_usd')):'Amount'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Billed To</label>
                                    <input type="text" name="billed_to" value="<?=$this->lang->line('billed_to')?htmlspecialchars($this->lang->line('billed_to')):'Billed To'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Billing Type</label>
                                    <input type="text" name="billing_type" value="<?=$this->lang->line('billing_type')?htmlspecialchars($this->lang->line('billing_type')):'Billing Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Date</label>
                                    <input type="text" name="date" value="<?=$this->lang->line('date')?htmlspecialchars($this->lang->line('date')):'Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Home</label>
                                    <input type="text" name="home" value="<?=$this->lang->line('home')?htmlspecialchars($this->lang->line('home')):'Home'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Expiring</label>
                                    <input type="text" name="expiring" value="<?=$this->lang->line('expiring')?htmlspecialchars($this->lang->line('expiring')):'Expiring'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Status</label>
                                    <input type="text" name="status" value="<?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Assign Users</label>
                                    <input type="text" name="assign_users" value="<?=$this->lang->line('assign_users')?htmlspecialchars($this->lang->line('assign_users')):'Assign Users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Project Users</label>
                                    <input type="text" name="project_users" value="<?=$this->lang->line('project_users')?htmlspecialchars($this->lang->line('project_users')):'Project Users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Action</label>
                                    <input type="text" name="action" value="<?=$this->lang->line('action')?htmlspecialchars($this->lang->line('action')):'Action'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Role</label>
                                    <input type="text" name="role" value="<?=$this->lang->line('role')?htmlspecialchars($this->lang->line('role')):'Role'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="<?=$this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Project Client</label>
                                    <input type="text" name="project_client" value="<?=$this->lang->line('project_client')?htmlspecialchars($this->lang->line('project_client')):'Project Client'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Features and Usage</label>
                                    <input type="text" name="features_and_usage" value="<?=$this->lang->line('features_and_usage')?htmlspecialchars($this->lang->line('features_and_usage')):'Features and Usage'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Request Date</label>
                                    <input type="text" name="request_date" value="<?=$this->lang->line('request_date')?htmlspecialchars($this->lang->line('request_date')):'Request Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Alert...</label>
                                    <input type="text" name="alert" value="<?=$this->lang->line('alert')?htmlspecialchars($this->lang->line('alert')):'Alert...'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Renew it now.</label>
                                    <input type="text" name="renew_it_now" value="<?=$this->lang->line('renew_it_now')?htmlspecialchars($this->lang->line('renew_it_now')):'Renew it now.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Renew Plan.</label>
                                    <input type="text" name="renew_plan" value="<?=$this->lang->line('renew_plan')?htmlspecialchars($this->lang->line('renew_plan')):'Renew Plan.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upgrade</label>
                                    <input type="text" name="subscribe" value="<?=$this->lang->line('subscribe')?htmlspecialchars($this->lang->line('subscribe')):'Upgrade'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Task Overview</label>
                                    <input type="text" name="task_overview" value="<?=$this->lang->line('task_overview')?htmlspecialchars($this->lang->line('task_overview')):'Task Overview'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Plan Expiry Date</label>
                                    <input type="text" name="plan_expiry_date" value="<?=$this->lang->line('plan_expiry_date')?htmlspecialchars($this->lang->line('plan_expiry_date')):'Plan Expiry Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User Plan</label>
                                    <input type="text" name="user_plan" value="<?=$this->lang->line('user_plan')?htmlspecialchars($this->lang->line('user_plan')):'User Plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile" value="<?=$this->lang->line('mobile')?htmlspecialchars($this->lang->line('mobile')):'Mobile'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Razorpay</label>
                                    <input type="text" name="razorpay" value="<?=$this->lang->line('razorpay')?htmlspecialchars($this->lang->line('razorpay')):'Razorpay'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Key ID</label>
                                    <input type="text" name="key_id" value="<?=$this->lang->line('key_id')?htmlspecialchars($this->lang->line('key_id')):'Key ID'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Key Secret</label>
                                    <input type="text" name="key_secret" value="<?=$this->lang->line('key_secret')?htmlspecialchars($this->lang->line('key_secret')):'Key Secret'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Stripe</label>
                                    <input type="text" name="stripe" value="<?=$this->lang->line('stripe')?htmlspecialchars($this->lang->line('stripe')):'Stripe'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Secret Key</label>
                                    <input type="text" name="secret_key" value="<?=$this->lang->line('secret_key')?htmlspecialchars($this->lang->line('secret_key')):'Secret Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Publishable Key</label>
                                    <input type="text" name="publishable_key" value="<?=$this->lang->line('publishable_key')?htmlspecialchars($this->lang->line('publishable_key')):'Publishable Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paypal</label>
                                    <input type="text" name="paypal" value="<?=$this->lang->line('paypal')?htmlspecialchars($this->lang->line('paypal')):'Paypal'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paypal Client ID</label>
                                    <input type="text" name="paypal_client_id" value="<?=$this->lang->line('paypal_client_id')?htmlspecialchars($this->lang->line('paypal_client_id')):'Paypal Client ID'?>" class="form-control">
                                </div>
                                    <div class="form-group col-md-6">
                                        <label>Paypal Secret</label>
                                        <input type="text" name="paypal_secret" value="<?=$this->lang->line('paypal_secret')?htmlspecialchars($this->lang->line('paypal_secret')):'Paypal Secret'?>" class="form-control">
                                    </div>
                                <div class="form-group col-md-6">
                                    <label>Paystack</label>
                                    <input type="text" name="paystack" value="<?=$this->lang->line('paystack')?htmlspecialchars($this->lang->line('paystack')):'Paystack'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paystack Public Key</label>
                                    <input type="text" name="paystack_public_key" value="<?=$this->lang->line('paypal_client_id')?htmlspecialchars($this->lang->line('paystack_public_key')):'Paystack Public Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paystack Secret Key</label>
                                    <input type="text" name="paystack_secret_key" value="<?=$this->lang->line('paystack_secret_key')?htmlspecialchars($this->lang->line('paystack_secret_key')):'Paystack Secret Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Timezone</label>
                                    <input type="text" name="timezone" value="<?=$this->lang->line('timezone')?htmlspecialchars($this->lang->line('timezone')):'Timezone'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Username/Email</label>
                                    <input type="text" name="username_email" value="<?=$this->lang->line('username_email')?htmlspecialchars($this->lang->line('username_email')):'Username/Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SMTP Port</label>
                                    <input type="text" name="smtp_port" value="<?=$this->lang->line('smtp_port')?htmlspecialchars($this->lang->line('smtp_port')):'SMTP Port'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SMTP Host</label>
                                    <input type="text" name="smtp_host" value="<?=$this->lang->line('smtp_host')?htmlspecialchars($this->lang->line('smtp_host')):'SMTP Host'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Favicon</label>
                                    <input type="text" name="favicon" value="<?=$this->lang->line('favicon')?htmlspecialchars($this->lang->line('favicon')):'Favicon'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>File</label>
                                    <input type="text" name="file" value="<?=$this->lang->line('file')?htmlspecialchars($this->lang->line('file')):'File'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Size</label>
                                    <input type="text" name="size" value="<?=$this->lang->line('size')?htmlspecialchars($this->lang->line('size')):'Size'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Task</label>
                                    <input type="text" name="edit_task" value="<?=$this->lang->line('edit_task')?htmlspecialchars($this->lang->line('edit_task')):'Edit Task'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create New Task</label>
                                    <input type="text" name="create_new_task" value="<?=$this->lang->line('create_new_task')?htmlspecialchars($this->lang->line('create_new_task')):'Create New Task'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>File Type</label>
                                    <input type="text" name="file_type" value="<?=$this->lang->line('file_type')?htmlspecialchars($this->lang->line('file_type')):'File Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Half Logo</label>
                                    <input type="text" name="half_logo" value="<?=$this->lang->line('half_logo')?htmlspecialchars($this->lang->line('half_logo')):'Half Logo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Full Logo</label>
                                    <input type="text" name="full_logo" value="<?=$this->lang->line('full_logo')?htmlspecialchars($this->lang->line('full_logo')):'Full Logo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>File Upload Format</label>
                                    <input type="text" name="file_upload_format" value="<?=$this->lang->line('file_upload_format')?htmlspecialchars($this->lang->line('file_upload_format')):'File Upload Format'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Time Format</label>
                                    <input type="text" name="time_format" value="<?=$this->lang->line('time_format')?htmlspecialchars($this->lang->line('time_format')):'Time Format'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Date Format</label>
                                    <input type="text" name="date_format" value="<?=$this->lang->line('date_format')?htmlspecialchars($this->lang->line('date_format')):'Date Format'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Feature</label>
                                    <input type="text" name="edit_feature" value="<?=$this->lang->line('edit_feature')?htmlspecialchars($this->lang->line('edit_feature')):'Edit Feature'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Save Changes</label>
                                    <input type="text" name="save_changes" value="<?=$this->lang->line('save_changes')?htmlspecialchars($this->lang->line('save_changes')):'Save Changes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Landing Page</label>
                                    <input type="text" name="landing_page" value="<?=$this->lang->line('landing_page')?htmlspecialchars($this->lang->line('landing_page')):'Landing Page'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Offline / Bank Transfer</label>
                                    <input type="text" name="offline_bank_transfer" value="<?=$this->lang->line('offline_bank_transfer')?htmlspecialchars($this->lang->line('offline_bank_transfer')):'Offline / Bank Transfer'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No Number</label>
                                    <input type="text" name="no_number" value="<?=$this->lang->line('no_number')?htmlspecialchars($this->lang->line('no_number')):'No Number'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Current System Version:</label>
                                    <input type="text" name="current_system_version" value="<?=$this->lang->line('current_system_version')?htmlspecialchars($this->lang->line('current_system_version')):'Current System Version:'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Update Guide</label>
                                    <input type="text" name="update_guide" value="<?=$this->lang->line('update_guide')?htmlspecialchars($this->lang->line('update_guide')):'Update Guide'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Install Update</label>
                                    <input type="text" name="install_update" value="<?=$this->lang->line('install_update')?htmlspecialchars($this->lang->line('install_update')):'Install Update'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Choose file</label>
                                    <input type="text" name="choose_file" value="<?=$this->lang->line('choose_file')?htmlspecialchars($this->lang->line('choose_file')):'Choose file'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Task</label>
                                    <input type="text" name="select_task" value="<?=$this->lang->line('select_task')?htmlspecialchars($this->lang->line('select_task')):'Select Task'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Gantt</label>
                                    <input type="text" name="gantt" value="<?=$this->lang->line('gantt')?htmlspecialchars($this->lang->line('gantt')):'Gantt'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Leaves</label>
                                    <input type="text" name="leaves" value="<?=$this->lang->line('leaves')?htmlspecialchars($this->lang->line('leaves')):'Leaves'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Team Members</label>
                                    <input type="text" name="team_members" value="<?=$this->lang->line('team_members')?htmlspecialchars($this->lang->line('team_members')):'Team Members'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Team Member</label>
                                    <input type="text" name="team_member" value="<?=$this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Users</label>
                                    <input type="text" name="users" value="<?=$this->lang->line('users')?htmlspecialchars($this->lang->line('users')):'Users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Receipt</label>
                                    <input type="text" name="receipt" value="<?=$this->lang->line('receipt')?htmlspecialchars($this->lang->line('receipt')):'Receipt'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Timesheet</label>
                                    <input type="text" name="timesheet" value="<?=$this->lang->line('timesheet')?htmlspecialchars($this->lang->line('timesheet')):'Timesheet'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Calendar</label>
                                    <input type="text" name="calendar" value="<?=$this->lang->line('calendar')?htmlspecialchars($this->lang->line('calendar')):'Calendar'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Start Timer</label>
                                    <input type="text" name="start_timer" value="<?=$this->lang->line('start_timer')?htmlspecialchars($this->lang->line('start_timer')):'Start Timer'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Stop Timer</label>
                                    <input type="text" name="stop_timer" value="<?=$this->lang->line('stop_timer')?htmlspecialchars($this->lang->line('stop_timer')):'Stop Timer'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Starting Time</label>
                                    <input type="text" name="starting_time" value="<?=$this->lang->line('starting_time')?htmlspecialchars($this->lang->line('starting_time')):'Starting Time'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ending Time</label>
                                    <input type="text" name="ending_time" value="<?=$this->lang->line('ending_time')?htmlspecialchars($this->lang->line('ending_time')):'Ending Time'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Total Time</label>
                                    <input type="text" name="total_time" value="<?=$this->lang->line('total_time')?htmlspecialchars($this->lang->line('total_time')):'Total Time'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Leave Reason</label>
                                    <input type="text" name="leave_reason" value="<?=$this->lang->line('leave_reason')?htmlspecialchars($this->lang->line('leave_reason')):'Leave Reason'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Leave Days</label>
                                    <input type="text" name="leave_days" value="<?=$this->lang->line('leave_days')?htmlspecialchars($this->lang->line('leave_days')):'Leave Days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Finance</label>
                                    <input type="text" name="finance" value="<?=$this->lang->line('finance')?htmlspecialchars($this->lang->line('finance')):'Finance'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Reports</label>
                                    <input type="text" name="reports" value="<?=$this->lang->line('reports')?htmlspecialchars($this->lang->line('reports')):'Reports'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Filter</label>
                                    <input type="text" name="filter" value="<?=$this->lang->line('filter')?htmlspecialchars($this->lang->line('filter')):'Filter'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Profit</label>
                                    <input type="text" name="profit" value="<?=$this->lang->line('profit')?htmlspecialchars($this->lang->line('profit')):'Profit'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Expenses</label>
                                    <input type="text" name="expenses" value="<?=$this->lang->line('expenses')?htmlspecialchars($this->lang->line('expenses')):'Expenses'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Income</label>
                                    <input type="text" name="income" value="<?=$this->lang->line('income')?htmlspecialchars($this->lang->line('income')):'Income'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Income VS Expenses</label>
                                    <input type="text" name="income_vs_expenses" value="<?=$this->lang->line('income_vs_expenses')?htmlspecialchars($this->lang->line('income_vs_expenses')):'Income VS Expenses'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>About Us</label>
                                    <input type="text" name="about" value="<?=$this->lang->line('about')?htmlspecialchars($this->lang->line('about')):'About Us'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Terms and Conditions</label>
                                    <input type="text" name="terms_and_conditions" value="<?=$this->lang->line('terms_and_conditions')?htmlspecialchars($this->lang->line('terms_and_conditions')):'Terms and Conditions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Privacy Policy</label>
                                    <input type="text" name="privacy_policy" value="<?=$this->lang->line('privacy_policy')?htmlspecialchars($this->lang->line('privacy_policy')):'Privacy Policy'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Icon</label>
                                    <input type="text" name="icon" value="<?=$this->lang->line('icon')?htmlspecialchars($this->lang->line('icon')):'Icon'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Modules</label>
                                    <input type="text" name="modules" value="<?=$this->lang->line('modules')?htmlspecialchars($this->lang->line('modules')):'Modules'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Currency</label>
                                    <input type="text" name="custom_currency" value="<?=$this->lang->line('custom_currency')?htmlspecialchars($this->lang->line('custom_currency')):'Custom Currency'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Storage</label>
                                    <input type="text" name="storage" value="<?=$this->lang->line('storage')?htmlspecialchars($this->lang->line('storage')):'Storage'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select All</label>
                                    <input type="text" name="select_all" value="<?=$this->lang->line('select_all')?htmlspecialchars($this->lang->line('select_all')):'Select All'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Products</label>
                                    <input type="text" name="products" value="<?=$this->lang->line('products')?htmlspecialchars($this->lang->line('products')):'Products'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Estimates</label>
                                    <input type="text" name="estimates" value="<?=$this->lang->line('estimates')?htmlspecialchars($this->lang->line('estimates')):'Estimates'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Estimate</label>
                                    <input type="text" name="estimate" value="<?=$this->lang->line('estimate')?htmlspecialchars($this->lang->line('estimate')):'Estimate'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Estimate Date</label>
                                    <input type="text" name="estimate_date" value="<?=$this->lang->line('estimate_date')?htmlspecialchars($this->lang->line('estimate_date')):'Estimate Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Draft</label>
                                    <input type="text" name="draft" value="<?=$this->lang->line('draft')?htmlspecialchars($this->lang->line('draft')):'Draft'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sent</label>
                                    <input type="text" name="sent" value="<?=$this->lang->line('sent')?htmlspecialchars($this->lang->line('sent')):'Sent'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sent To</label>
                                    <input type="text" name="sent_to" value="<?=$this->lang->line('sent_to')?htmlspecialchars($this->lang->line('sent_to')):'Sent To'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Received</label>
                                    <input type="text" name="received" value="<?=$this->lang->line('received')?htmlspecialchars($this->lang->line('received')):'Received'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Leave</label>
                                    <input type="text" name="leave" value="<?=$this->lang->line('leave')?htmlspecialchars($this->lang->line('leave')):'Leave'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Scheduled By</label>
                                    <input type="text" name="scheduled_by" value="<?=$this->lang->line('scheduled_by')?htmlspecialchars($this->lang->line('scheduled_by')):'Scheduled By'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Scheduled</label>
                                    <input type="text" name="scheduled" value="<?=$this->lang->line('scheduled')?htmlspecialchars($this->lang->line('scheduled')):'Scheduled'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Running</label>
                                    <input type="text" name="running" value="<?=$this->lang->line('running')?htmlspecialchars($this->lang->line('running')):'Running'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Scheduled</label>
                                    <input type="text" name="scheduled" value="<?=$this->lang->line('scheduled')?htmlspecialchars($this->lang->line('scheduled')):'Scheduled'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Scheduled</label>
                                    <input type="text" name="scheduled" value="<?=$this->lang->line('scheduled')?htmlspecialchars($this->lang->line('scheduled')):'Scheduled'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meeting Over</label>
                                    <input type="text" name="meeting_over" value="<?=$this->lang->line('meeting_over')?htmlspecialchars($this->lang->line('meeting_over')):'Meeting Over'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Go Back</label>
                                    <input type="text" name="go_back" value="<?=$this->lang->line('go_back')?htmlspecialchars($this->lang->line('go_back')):'Go Back'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Video Meetings</label>
                                    <input type="text" name="video_meetings" value="<?=$this->lang->line('video_meetings')?htmlspecialchars($this->lang->line('video_meetings')):'Video Meetings'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Duration (Minutes)</label>
                                    <input type="text" name="duration" value="<?=$this->lang->line('duration')?htmlspecialchars($this->lang->line('duration')):'Duration (Minutes)'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meeting not started.</label>
                                    <input type="text" name="meeting_not_started" value="<?=$this->lang->line('meeting_not_started')?htmlspecialchars($this->lang->line('meeting_not_started')):'Meeting not started.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Come back later.</label>
                                    <input type="text" name="come_back_later" value="<?=$this->lang->line('come_back_later')?htmlspecialchars($this->lang->line('come_back_later')):'Come back later.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>One Time</label>
                                    <input type="text" name="one_time" value="<?=$this->lang->line('one_time')?htmlspecialchars($this->lang->line('one_time')):'One Time'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Color</label>
                                    <input type="text" name="theme_color" value="<?=$this->lang->line('theme_color')?htmlspecialchars($this->lang->line('theme_color')):'Theme Color'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Code</label>
                                    <input type="text" name="custom_code" value="<?=$this->lang->line('custom_code')?htmlspecialchars($this->lang->line('custom_code')):'Custom Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Header Code</label>
                                    <input type="text" name="header_code" value="<?=$this->lang->line('header_code')?htmlspecialchars($this->lang->line('header_code')):'Header Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Footer Code</label>
                                    <input type="text" name="footer_code" value="<?=$this->lang->line('footer_code')?htmlspecialchars($this->lang->line('footer_code')):'Footer Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Email Template</label>
                                    <input type="text" name="select_template" value="<?=$this->lang->line('select_template')?htmlspecialchars($this->lang->line('select_template')):'Select Email Template'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Templates</label>
                                    <input type="text" name="email_templates" value="<?=$this->lang->line('email_templates')?htmlspecialchars($this->lang->line('email_templates')):'Email Templates'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Subject</label>
                                    <input type="text" name="email_subject" value="<?=$this->lang->line('email_subject')?htmlspecialchars($this->lang->line('email_subject')):'Email Subject'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Message</label>
                                    <input type="text" name="email_message" value="<?=$this->lang->line('email_message')?htmlspecialchars($this->lang->line('email_message')):'Email Message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New user registration</label>
                                    <input type="text" name="new_user_registration" value="<?=$this->lang->line('new_user_registration')?htmlspecialchars($this->lang->line('new_user_registration')):'New user registration'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email verification</label>
                                    <input type="text" name="email_verification" value="<?=$this->lang->line('email_verification')?htmlspecialchars($this->lang->line('email_verification')):'Email verification'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New project</label>
                                    <input type="text" name="new_project" value="<?=$this->lang->line('new_project')?htmlspecialchars($this->lang->line('new_project')):'New project'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New task</label>
                                    <input type="text" name="new_task" value="<?=$this->lang->line('new_task')?htmlspecialchars($this->lang->line('new_task')):'New task'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New meeting</label>
                                    <input type="text" name="new_meeting" value="<?=$this->lang->line('new_meeting')?htmlspecialchars($this->lang->line('new_meeting')):'New meeting'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New invoice</label>
                                    <input type="text" name="new_invoice" value="<?=$this->lang->line('new_invoice')?htmlspecialchars($this->lang->line('new_invoice')):'New invoice'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New estimate</label>
                                    <input type="text" name="new_estimate" value="<?=$this->lang->line('new_estimate')?htmlspecialchars($this->lang->line('new_estimate')):'New estimate'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send email notification</label>
                                    <input type="text" name="send_email_notification" value="<?=$this->lang->line('send_email_notification')?htmlspecialchars($this->lang->line('send_email_notification')):'Send email notification'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Address</label>
                                    <input type="text" name="address" value="<?=$this->lang->line('address')?htmlspecialchars($this->lang->line('address')):'Address'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>City</label>
                                    <input type="text" name="city" value="<?=$this->lang->line('city')?htmlspecialchars($this->lang->line('city')):'City'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>State</label>
                                    <input type="text" name="state" value="<?=$this->lang->line('state')?htmlspecialchars($this->lang->line('state')):'State'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Country</label>
                                    <input type="text" name="country" value="<?=$this->lang->line('country')?htmlspecialchars($this->lang->line('country')):'Country'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Zip Code</label>
                                    <input type="text" name="zip_code" value="<?=$this->lang->line('zip_code')?htmlspecialchars($this->lang->line('zip_code')):'Zip Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Month</label>
                                    <input type="text" name="month" value="<?=$this->lang->line('month')?htmlspecialchars($this->lang->line('month')):'Month'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Quarter Day</label>
                                    <input type="text" name="quarter_day" value="<?=$this->lang->line('quarter_day')?htmlspecialchars($this->lang->line('quarter_day')):'Quarter Day'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Half Day</label>
                                    <input type="text" name="half_day" value="<?=$this->lang->line('half_day')?htmlspecialchars($this->lang->line('half_day')):'Half Day'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Day</label>
                                    <input type="text" name="day" value="<?=$this->lang->line('day')?htmlspecialchars($this->lang->line('day')):'Day'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Week</label>
                                    <input type="text" name="week" value="<?=$this->lang->line('week')?htmlspecialchars($this->lang->line('week')):'Week'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Short Code</label>
                                    <input type="text" name="short_code" value="<?=$this->lang->line('short_code')?htmlspecialchars($this->lang->line('short_code')):'Short Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Library</label>
                                    <input type="text" name="email_library" value="<?=$this->lang->line('email_library')?htmlspecialchars($this->lang->line('email_library')):'Email Library'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google reCAPTCHA</label>
                                    <input type="text" name="google_recaptcha" value="<?=$this->lang->line('google_recaptcha')?htmlspecialchars($this->lang->line('google_recaptcha')):'Google reCAPTCHA'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Site Key</label>
                                    <input type="text" name="site_key" value="<?=$this->lang->line('site_key')?htmlspecialchars($this->lang->line('site_key')):'Site Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Secret Key</label>
                                    <input type="text" name="secret_key" value="<?=$this->lang->line('secret_key')?htmlspecialchars($this->lang->line('secret_key')):'Secret Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Leads</label>
                                    <input type="text" name="leads" value="<?=$this->lang->line('leads')?htmlspecialchars($this->lang->line('leads')):'Leads'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Value</label>
                                    <input type="text" name="value" value="<?=$this->lang->line('value')?htmlspecialchars($this->lang->line('value')):'Value'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Source</label>
                                    <input type="text" name="source" value="<?=$this->lang->line('source')?htmlspecialchars($this->lang->line('source')):'Source'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New</label>
                                    <input type="text" name="new" value="<?=$this->lang->line('new')?htmlspecialchars($this->lang->line('new')):'New'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Qualified</label>
                                    <input type="text" name="qualified" value="<?=$this->lang->line('qualified')?htmlspecialchars($this->lang->line('qualified')):'Qualified'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Discussion</label>
                                    <input type="text" name="discussion" value="<?=$this->lang->line('discussion')?htmlspecialchars($this->lang->line('discussion')):'Discussion'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Won</label>
                                    <input type="text" name="won" value="<?=$this->lang->line('won')?htmlspecialchars($this->lang->line('won')):'Won'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Lost</label>
                                    <input type="text" name="lost" value="<?=$this->lang->line('lost')?htmlspecialchars($this->lang->line('lost')):'Lost'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Assigned</label>
                                    <input type="text" name="assigned" value="<?=$this->lang->line('assigned')?htmlspecialchars($this->lang->line('assigned')):'Assigned'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Created</label>
                                    <input type="text" name="created" value="<?=$this->lang->line('created')?htmlspecialchars($this->lang->line('created')):'Created'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Preview</label>
                                    <input type="text" name="preview" value="<?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>I Agree!</label>
                                    <input type="text" name="i_agree" value="<?=$this->lang->line('i_agree')?htmlspecialchars($this->lang->line('i_agree')):'I Agree!'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pricing</label>
                                    <input type="text" name="pricing" value="<?=$this->lang->line('pricing')?htmlspecialchars($this->lang->line('pricing')):'Pricing'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Account</label>
                                    <input type="text" name="account" value="<?=$this->lang->line('account')?htmlspecialchars($this->lang->line('account')):'Account'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Useful Links</label>
                                    <input type="text" name="useful_links" value="<?=$this->lang->line('useful_links')?htmlspecialchars($this->lang->line('useful_links')):'Useful Links'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Getting Started</label>
                                    <input type="text" name="getting_started" value="<?=$this->lang->line('getting_started')?htmlspecialchars($this->lang->line('getting_started')):'Getting Started'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>3 days trial plan</label>
                                    <input type="text" name="three_days_trial_plan" value="<?=$this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>7 days trial plan</label>
                                    <input type="text" name="seven_days_trial_plan" value="<?=$this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>15 days trial plan</label>
                                    <input type="text" name="fifteen_days_trial_plan" value="<?=$this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>30 days trial plan</label>
                                    <input type="text" name="thirty_days_trial_plan" value="<?=$this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscription</label>
                                    <input type="text" name="subscription" value="<?=$this->lang->line('subscription')?htmlspecialchars($this->lang->line('subscription')):'Subscription'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscription Statistics</label>
                                    <input type="text" name="subscription_statistics" value="<?=$this->lang->line('subscription_statistics')?htmlspecialchars($this->lang->line('subscription_statistics')):'Subscription Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscribers</label>
                                    <input type="text" name="subscribers" value="<?=$this->lang->line('subscribers')?htmlspecialchars($this->lang->line('subscribers')):'Subscribers'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscriber Statistics</label>
                                    <input type="text" name="subscribers_statistics" value="<?=$this->lang->line('subscribers_statistics')?htmlspecialchars($this->lang->line('subscribers_statistics')):'Subscriber Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Is RTL</label>
                                    <input type="text" name="is_rtl" value="<?=$this->lang->line('is_rtl')?htmlspecialchars($this->lang->line('is_rtl')):'Is RTL'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sales</label>
                                    <input type="text" name="sales" value="<?=$this->lang->line('sales')?htmlspecialchars($this->lang->line('sales')):'Sales'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No Expiry Date</label>
                                    <input type="text" name="no_expiry_date" value="<?=$this->lang->line('no_expiry_date')?htmlspecialchars($this->lang->line('no_expiry_date')):'No Expiry Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>From Email</label>
                                    <input type="text" name="from_email" value="<?=$this->lang->line('from_email')?htmlspecialchars($this->lang->line('from_email')):'From Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Turn off new user registration</label>
                                    <input type="text" name="turn_off_new_user_registration" value="<?=$this->lang->line('turn_off_new_user_registration')?htmlspecialchars($this->lang->line('turn_off_new_user_registration')):'Turn off new user registration'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Yes</label>
                                    <input type="text" name="yes" value="<?=$this->lang->line('yes')?htmlspecialchars($this->lang->line('yes')):'Yes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No</label>
                                    <input type="text" name="no" value="<?=$this->lang->line('no')?htmlspecialchars($this->lang->line('no')):'No'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirmation</label>
                                    <input type="text" name="confirmation" value="<?=$this->lang->line('confirmation')?htmlspecialchars($this->lang->line('confirmation')):'Confirmation'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SEO</label>
                                    <input type="text" name="seo" value="<?=$this->lang->line('seo')?htmlspecialchars($this->lang->line('seo')):'SEO'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meta Title</label>
                                    <input type="text" name="meta_title" value="<?=$this->lang->line('meta_title')?htmlspecialchars($this->lang->line('meta_title')):'Meta Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meta Description</label>
                                    <input type="text" name="meta_description" value="<?=$this->lang->line('meta_description')?htmlspecialchars($this->lang->line('meta_description')):'Meta Description'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="<?=$this->lang->line('meta_keywords')?htmlspecialchars($this->lang->line('meta_keywords')):'Meta Keywords'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Payment</label>
                                    <input type="text" name="custom_payment" value="<?=$this->lang->line('custom_payment')?htmlspecialchars($this->lang->line('custom_payment')):'Custom Payment'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload Receipt</label>
                                    <input type="text" name="upload_receipt" value="<?=$this->lang->line('upload_receipt')?htmlspecialchars($this->lang->line('upload_receipt')):'Upload Receipt'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>OK</label>
                                    <input type="text" name="ok" value="<?=$this->lang->line('ok')?htmlspecialchars($this->lang->line('ok')):'OK'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Drag Date</label>
                                    <input type="text" name="drag_date" value="<?=$this->lang->line('drag_date')?htmlspecialchars($this->lang->line('drag_date')):'Drag Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Social Login</label>
                                    <input type="text" name="social_login" value="<?=$this->lang->line('social_login')?htmlspecialchars($this->lang->line('social_login')):'Social Login'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google</label>
                                    <input type="text" name="google" value="<?=$this->lang->line('google')?htmlspecialchars($this->lang->line('google')):'Google'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google Client ID</label>
                                    <input type="text" name="google_client_id" value="<?=$this->lang->line('google_client_id')?htmlspecialchars($this->lang->line('google_client_id')):'Google Client ID'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google Client Secret</label>
                                    <input type="text" name="google_client_secret" value="<?=$this->lang->line('google_client_secret')?htmlspecialchars($this->lang->line('google_client_secret')):'Google Client Secret'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Attendance</label>
                                    <input type="text" name="attendance" value="<?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Check In</label>
                                    <input type="text" name="check_in" value="<?=$this->lang->line('check_in')?htmlspecialchars($this->lang->line('check_in')):'Check In'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Check Out</label>
                                    <input type="text" name="check_out" value="<?=$this->lang->line('check_out')?htmlspecialchars($this->lang->line('check_out')):'Check Out'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>You are currently checked out</label>
                                    <input type="text" name="you_are_currently_checked_out" value="<?=$this->lang->line('you_are_currently_checked_out')?htmlspecialchars($this->lang->line('you_are_currently_checked_out')):'You are currently checked out'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>You checked in at</label>
                                    <input type="text" name="check_in_at" value="<?=$this->lang->line('check_in_at')?htmlspecialchars($this->lang->line('check_in_at')):'You checked in at'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Support</label>
                                    <input type="text" name="support" value="<?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subject</label>
                                    <input type="text" name="subject" value="<?=$this->lang->line('subject')?htmlspecialchars($this->lang->line('subject')):'Subject'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ticket</label>
                                    <input type="text" name="ticket" value="<?=$this->lang->line('ticket')?htmlspecialchars($this->lang->line('ticket')):'Ticket'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Opened and Resolving</label>
                                    <input type="text" name="opened_and_resolving" value="<?=$this->lang->line('opened_and_resolving')?htmlspecialchars($this->lang->line('opened_and_resolving')):'Opened and Resolving'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Resolved and Closed</label>
                                    <input type="text" name="resolved_and_closed" value="<?=$this->lang->line('resolved_and_closed')?htmlspecialchars($this->lang->line('resolved_and_closed')):'Resolved and Closed'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ID</label>
                                    <input type="text" name="id" value="<?=$this->lang->line('id')?htmlspecialchars($this->lang->line('id')):'ID'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New support message received</label>
                                    <input type="text" name="new_support_message_received" value="<?=$this->lang->line('new_support_message_received')?htmlspecialchars($this->lang->line('new_support_message_received')):'New support message received'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Enable</label>
                                    <input type="text" name="enable" value="<?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Start</label>
                                    <input type="text" name="start" value="<?=$this->lang->line('start')?htmlspecialchars($this->lang->line('start')):'Start'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Join</label>
                                    <input type="text" name="join" value="<?=$this->lang->line('join')?htmlspecialchars($this->lang->line('join')):'Join'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Stop</label>
                                    <input type="text" name="stop" value="<?=$this->lang->line('stop')?htmlspecialchars($this->lang->line('stop')):'Stop'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Download</label>
                                    <input type="text" name="download" value="<?=$this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Priority</label>
                                    <input type="text" name="select_priority" value="<?=$this->lang->line('select_priority')?htmlspecialchars($this->lang->line('select_priority')):'Select Priority'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Days</label>
                                    <input type="text" name="select_days" value="<?=$this->lang->line('select_days')?htmlspecialchars($this->lang->line('select_days')):'Select Days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tomorrow</label>
                                    <input type="text" name="tomorrow" value="<?=$this->lang->line('tomorrow')?htmlspecialchars($this->lang->line('tomorrow')):'Tomorrow'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Coming in 3 days</label>
                                    <input type="text" name="coming_in_3_days" value="<?=$this->lang->line('coming_in_3_days')?htmlspecialchars($this->lang->line('coming_in_3_days')):'Coming in 3 days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Coming in 7 days</label>
                                    <input type="text" name="coming_in_7_days" value="<?=$this->lang->line('coming_in_7_days')?htmlspecialchars($this->lang->line('coming_in_7_days')):'Coming in 7 days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Coming in 15 days</label>
                                    <input type="text" name="coming_in_15_days" value="<?=$this->lang->line('coming_in_15_days')?htmlspecialchars($this->lang->line('coming_in_15_days')):'Coming in 15 days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Coming in 30 days</label>
                                    <input type="text" name="coming_in_30_days" value="<?=$this->lang->line('coming_in_30_days')?htmlspecialchars($this->lang->line('coming_in_30_days')):'Coming in 30 days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Coming in 3 days</label>
                                    <input type="text" name="coming_in_3_days" value="<?=$this->lang->line('coming_in_3_days')?htmlspecialchars($this->lang->line('coming_in_3_days')):'Coming in 3 days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Delete Account</label>
                                    <input type="text" name="delete_account" value="<?=$this->lang->line('delete_account')?htmlspecialchars($this->lang->line('delete_account')):'Delete Account'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Hours</label>
                                    <input type="text" name="hours" value="<?=$this->lang->line('hours')?htmlspecialchars($this->lang->line('hours')):'Hours'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Minutes</label>
                                    <input type="text" name="minutes" value="<?=$this->lang->line('minutes')?htmlspecialchars($this->lang->line('minutes')):'Minutes'?>" class="form-control">
                                </div>
                            
                                <div class="form-group col-md-6">
                                    <label>Progress</label>
                                    <input type="text" name="progress" value="<?=$this->lang->line('progress')?htmlspecialchars($this->lang->line('progress')):'Progress'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>By Admin</label>
                                    <input type="text" name="by_admin" value="<?=$this->lang->line('by_admin')?htmlspecialchars($this->lang->line('by_admin')):'By Admin'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Invoice</label>
                                    <input type="text" name="select_invoice" value="<?=$this->lang->line('select_invoice')?htmlspecialchars($this->lang->line('select_invoice')):'Select Invoice'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Convert to Client</label>
                                    <input type="text" name="convert_to_client" value="<?=$this->lang->line('convert_to_client')?htmlspecialchars($this->lang->line('convert_to_client')):'Convert to Client'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Stats</label>
                                    <input type="text" name="stats" value="<?=$this->lang->line('stats')?htmlspecialchars($this->lang->line('stats')):'Stats'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Grid View</label>
                                    <input type="text" name="grid_view" value="<?=$this->lang->line('grid_view')?htmlspecialchars($this->lang->line('grid_view')):'Grid View'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>List View</label>
                                    <input type="text" name="list_view" value="<?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Kanban View</label>
                                    <input type="text" name="kanban_view" value="<?=$this->lang->line('kanban_view')?htmlspecialchars($this->lang->line('kanban_view')):'Kanban View'?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Convert invoice to estimate</label>
                                    <input type="text" name="convert_invoice_to_estimate" value="<?=$this->lang->line('convert_invoice_to_estimate')?htmlspecialchars($this->lang->line('convert_invoice_to_estimate')):'Convert invoice to estimate'?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Convert estimate to invoice</label>
                                    <input type="text" name="convert_estimate_to_invoice" value="<?=$this->lang->line('convert_estimate_to_invoice')?htmlspecialchars($this->lang->line('convert_estimate_to_invoice')):'Convert estimate to invoice'?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Convert</label>
                                    <input type="text" name="convert" value="<?=$this->lang->line('convert')?htmlspecialchars($this->lang->line('convert')):'Convert'?>" class="form-control">
                                </div>
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              

                              

                              
                              
                              
                              

                              
                              
                              <div class="form-group col-md-12">
                                  <label>This email will not be updated latter.</label>
                                  <input type="text" name="this_email_will_not_be_updated_latter" value="<?=$this->lang->line('this_email_will_not_be_updated_latter')?htmlspecialchars($this->lang->line('this_email_will_not_be_updated_latter')):'This email will not be updated latter.'?>" class="form-control">
                              </div>
                                <div class="form-group col-md-12">
                                    <label>We will send a link to reset your password.</label>
                                    <input type="text" name="we_will_send_a_link_to_reset_your_password" value="<?=$this->lang->line('we_will_send_a_link_to_reset_your_password')?htmlspecialchars($this->lang->line('we_will_send_a_link_to_reset_your_password')):'We will send a link to reset your password.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Including Admins, Clients and Users.</label>
                                    <input type="text" name="including_admins_clients_and_users" value="<?=$this->lang->line('including_admins_clients_and_users')?htmlspecialchars($this->lang->line('including_admins_clients_and_users')):'Including Admins, Clients and Users.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your subscription plan has been expired on date</label>
                                    <input type="text" name="your_subscription_plan_has_been_expired_on_date" value="<?=$this->lang->line('your_subscription_plan_has_been_expired_on_date')?htmlspecialchars($this->lang->line('your_subscription_plan_has_been_expired_on_date')):'Your subscription plan has been expired on date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your current subscription plan is expiring on date</label>
                                    <input type="text" name="your_current_subscription_plan_is_expiring_on_date" value="<?=$this->lang->line('your_current_subscription_plan_is_expiring_on_date')?htmlspecialchars($this->lang->line('your_current_subscription_plan_is_expiring_on_date')):'Your current subscription plan is expiring on date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This is your current active plan and expiring on date</label>
                                    <input type="text" name="this_is_your_current_active_plan_and_expiring_on_date" value="<?=$this->lang->line('this_is_your_current_active_plan_and_expiring_on_date')?htmlspecialchars($this->lang->line('this_is_your_current_active_plan_and_expiring_on_date')):'This is your current active plan and expiring on date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This is your current active plan, No Expiry Date.</label>
                                    <input type="text" name="this_is_your_current_active_plan" value="<?=$this->lang->line('this_is_your_current_active_plan')?htmlspecialchars($this->lang->line('this_is_your_current_active_plan')):'This is your current active plan, No Expiry Date.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Set value in minus (-1) to make it Unlimited.</label>
                                    <input type="text" name="set_value_in_minus_to_make_it_unlimited" value="<?=$this->lang->line('set_value_in_minus_to_make_it_unlimited')?htmlspecialchars($this->lang->line('set_value_in_minus_to_make_it_unlimited')):'Set value in minus (-1) to make it Unlimited.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Leave Password and Confirm Password empty for no change in Password.</label>
                                    <input type="text" name="leave_password_and_confirm_password_empty_for_no_change_in_password" value="<?=$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')?htmlspecialchars($this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')):'Leave Password and Confirm Password empty for no change in Password.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Disable "Landing Page" option to Disable whole frontend.</label>
                                    <input type="text" name="disable_landing_page_option_to_disable_whole_frontend" value="<?=$this->lang->line('disable_landing_page_option_to_disable_whole_frontend')?htmlspecialchars($this->lang->line('disable_landing_page_option_to_disable_whole_frontend')):'Disable Landing Page option to Disable whole frontend.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>If any changes have been made in above "Frontend Customization" section then save it first.</label>
                                    <input type="text" name="if_any_changes_have_been_made_in_above_frontend_customization_section_then_save_it_first" value="<?=$this->lang->line('if_any_changes_have_been_made_in_above_frontend_customization_section_then_save_it_first')?htmlspecialchars($this->lang->line('if_any_changes_have_been_made_in_above_frontend_customization_section_then_save_it_first')):'If any changes have been made in above Frontend Customization section then save it first.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Must enter Title and Description for default language.</label>
                                    <input type="text" name="must_enter_title_and_description_for_default_language" value="<?=$this->lang->line('must_enter_title_and_description_for_default_language')?htmlspecialchars($this->lang->line('must_enter_title_and_description_for_default_language')):'Must enter Title and Description for default language.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Show Subscription Plan Expiry Alert Before</label>
                                    <input type="text" name="show_subscription_plan_expiry_alert_before" value="<?=$this->lang->line('show_subscription_plan_expiry_alert_before')?htmlspecialchars($this->lang->line('show_subscription_plan_expiry_alert_before')):'Show Subscription Plan Expiry Alert Before'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This will show alert box in main dashboard to the user about their plan expiry date.</label>
                                    <input type="text" name="this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date" value="<?=$this->lang->line('this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date')?htmlspecialchars($this->lang->line('this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date')):'This will show alert box in main dashboard to the user about their plan expiry date.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Only this type of files going to be allowed to upload in projects and tasks.</label>
                                    <input type="text" name="only_this_type_of_files_going_to_be_allowed_to_upload_in_projects_and_tasks" value="<?=$this->lang->line('only_this_type_of_files_going_to_be_allowed_to_upload_in_projects_and_tasks')?htmlspecialchars($this->lang->line('only_this_type_of_files_going_to_be_allowed_to_upload_in_projects_and_tasks')):'Only this type of files going to be allowed to upload in projects and tasks.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You have to contact a user by yourself for further process.</label>
                                    <input type="text" name="you_have_to_contact_a_user_by_yourself_for_further_process" value="<?=$this->lang->line('you_have_to_contact_a_user_by_yourself_for_further_process')?htmlspecialchars($this->lang->line('you_have_to_contact_a_user_by_yourself_for_further_process')):'You have to contact a user by yourself for further process.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Select the update zip file and hit Install Update button.</label>
                                    <input type="text" name="select_the_update_zip_file_and_hit_install_update_button" value="<?=$this->lang->line('select_the_update_zip_file_and_hit_install_update_button')?htmlspecialchars($this->lang->line('select_the_update_zip_file_and_hit_install_update_button')):'Select the update zip file and hit Install Update button.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Please take a backup before going further. Follow the further instructions given with the update file.</label>
                                    <input type="text" name="please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file" value="<?=$this->lang->line('please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file')?htmlspecialchars($this->lang->line('please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file')):'Please take a backup before going further. Follow the further instructions given with the update file.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Don't edit it if you don't want to edit language name.</label>
                                    <input type="text" name="dont_edit_it_if_you_dont_want_to_edit_language_name" value="<?=$this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')?htmlspecialchars($this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')):"Don't edit it if you don't want to edit language name."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Leave empty for no changes.</label>
                                    <input type="text" name="leave_empty_for_no_changes" value="<?=$this->lang->line('leave_empty_for_no_changes')?htmlspecialchars($this->lang->line('leave_empty_for_no_changes')):"Leave empty for no changes."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Add users who will work on this project. Only this users are able to see this project.</label>
                                    <input type="text" name="add_users_who_will_work_on_this_project_only_this_users_are_able_to_see_this_project" value="<?=$this->lang->line('add_users_who_will_work_on_this_project_only_this_users_are_able_to_see_this_project')?htmlspecialchars($this->lang->line('add_users_who_will_work_on_this_project_only_this_users_are_able_to_see_this_project')):"Add users who will work on this project. Only this users are able to see this project."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Assign task to the users who will work on this task. Only this users are able to see this task.</label>
                                    <input type="text" name="assign_task_to_the_users_who_will_work_on_this_task_only_this_users_are_able_to_se_this_task" value="<?=$this->lang->line('assign_task_to_the_users_who_will_work_on_this_task_only_this_users_are_able_to_se_this_task')?htmlspecialchars($this->lang->line('assign_task_to_the_users_who_will_work_on_this_task_only_this_users_are_able_to_se_this_task')):"Assign task to the users who will work on this task. Only this users are able to see this task."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Admin always have all the permission. Here you can set permissions for users and clients.</label>
                                    <input type="text" name="admin_always_have_all_the_permission_here_you_can_set_permissions_for_users_and_clients" value="<?=$this->lang->line('admin_always_have_all_the_permission_here_you_can_set_permissions_for_users_and_clients')?htmlspecialchars($this->lang->line('admin_always_have_all_the_permission_here_you_can_set_permissions_for_users_and_clients')):"Admin always have all the permission. Here you can set permissions for users and clients."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default.</label>
                                    <input type="text" name="only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default" value="<?=$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')?htmlspecialchars($this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')):"Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Settings have some some sensetive information about application. Make sure you have proper knowledge about what permission you are giving to the users.</label>
                                    <input type="text" name="settings_have_some_some_sensetive_information_about_application_make_sure_you_have_proper_knowledge_about_what_permission_you_are_giving_to_the_users" value="<?=$this->lang->line('settings_have_some_some_sensetive_information_about_application_make_sure_you_have_proper_knowledge_about_what_permission_you_are_giving_to_the_users')?htmlspecialchars($this->lang->line('settings_have_some_some_sensetive_information_about_application_make_sure_you_have_proper_knowledge_about_what_permission_you_are_giving_to_the_users')):"Settings have some some sensetive information about application. Make sure you have proper knowledge about what permission you are giving to the users."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>New user registered.</label>
                                    <input type="text" name="new_user_registered" value="<?=$this->lang->line('new_user_registered')?htmlspecialchars($this->lang->line('new_user_registered')):"New user registered."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Ordered subscription plan</label>
                                    <input type="text" name="ordered_subscription_plan" value="<?=$this->lang->line('ordered_subscription_plan')?htmlspecialchars($this->lang->line('ordered_subscription_plan')):"Ordered subscription plan"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your Offline / Bank Transfer request accepted for subscription plan</label>
                                    <input type="text" name="your_offline_bank_transfer_request_accepted_for_subscription_plan" value="<?=$this->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')?htmlspecialchars($this->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')):"Your Offline / Bank Transfer request accepted for subscription plan"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offline / Bank Transfer request created for subscription plan</label>
                                    <input type="text" name="offline_bank_transfer_request_created_for_subscription_plan" value="<?=$this->lang->line('offline_bank_transfer_request_created_for_subscription_plan')?htmlspecialchars($this->lang->line('offline_bank_transfer_request_created_for_subscription_plan')):"Offline / Bank Transfer request created for subscription plan"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>task file uploaded.</label>
                                    <input type="text" name="task_file_uploaded" value="<?=$this->lang->line('task_file_uploaded')?htmlspecialchars($this->lang->line('task_file_uploaded')):"task file uploaded."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>New task comment</label>
                                    <input type="text" name="new_task_comment" value="<?=$this->lang->line('new_task_comment')?htmlspecialchars($this->lang->line('new_task_comment')):"New task comment"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Task status changed.</label>
                                    <input type="text" name="task_status_changed" value="<?=$this->lang->line('task_status_changed')?htmlspecialchars($this->lang->line('task_status_changed')):"Task status changed."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>project file uploaded.</label>
                                    <input type="text" name="project_file_uploaded" value="<?=$this->lang->line('project_file_uploaded')?htmlspecialchars($this->lang->line('project_file_uploaded')):"project file uploaded."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Project status changed.</label>
                                    <input type="text" name="project_status_changed" value="<?=$this->lang->line('project_status_changed')?htmlspecialchars($this->lang->line('project_status_changed')):"Project status changed."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>new project created.</label>
                                    <input type="text" name="new_project_created" value="<?=$this->lang->line('new_project_created')?htmlspecialchars($this->lang->line('new_project_created')):"new project created."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>new meeting scheduled.</label>
                                    <input type="text" name="new_meeting_created" value="<?=$this->lang->line('new_meeting_created')?htmlspecialchars($this->lang->line('new_meeting_created')):"new meeting scheduled."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>new invoice received.</label>
                                    <input type="text" name="new_invoice_received" value="<?=$this->lang->line('new_invoice_received')?htmlspecialchars($this->lang->line('new_invoice_received')):"new invoice received."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>new estimate received.</label>
                                    <input type="text" name="new_estimate_received" value="<?=$this->lang->line('new_estimate_received')?htmlspecialchars($this->lang->line('new_estimate_received')):"new estimate received."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>estimate accepted.</label>
                                    <input type="text" name="estimate_accepted" value="<?=$this->lang->line('estimate_accepted')?htmlspecialchars($this->lang->line('estimate_accepted')):"estimate accepted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>estimate rejected.</label>
                                    <input type="text" name="estimate_rejected" value="<?=$this->lang->line('estimate_rejected')?htmlspecialchars($this->lang->line('estimate_rejected')):"estimate rejected."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>task assigned you.</label>
                                    <input type="text" name="task_assigned_you" value="<?=$this->lang->line('task_assigned_you')?htmlspecialchars($this->lang->line('task_assigned_you')):"task assigned you."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>User registered successfully. Go to the login page and login with your credentials.</label>
                                    <input type="text" name="user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials" value="<?=$this->lang->line('user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials')?htmlspecialchars($this->lang->line('user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials')):"User registered successfully. Go to the login page and login with your credentials."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invalid User ID</label>
                                    <input type="text" name="invalid_user_id" value="<?=$this->lang->line('invalid_user_id')?htmlspecialchars($this->lang->line('invalid_user_id')):"Invalid User ID"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You must be an administrator to take this action.</label>
                                    <input type="text" name="you_must_be_an_administrator_to_take_this_action" value="<?=$this->lang->line('you_must_be_an_administrator_to_take_this_action')?htmlspecialchars($this->lang->line('you_must_be_an_administrator_to_take_this_action')):"You must be an administrator to take this action."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Access Denied</label>
                                    <input type="text" name="access_denied" value="<?=$this->lang->line('access_denied')?htmlspecialchars($this->lang->line('access_denied')):"Access Denied"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Something wrong! Try again.</label>
                                    <input type="text" name="something_wrong_try_again" value="<?=$this->lang->line('something_wrong_try_again')?htmlspecialchars($this->lang->line('something_wrong_try_again')):"Something wrong! Try again."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>ToDo deleted successfully.</label>
                                    <input type="text" name="todo_deleted_successfully" value="<?=$this->lang->line('todo_deleted_successfully')?htmlspecialchars($this->lang->line('todo_deleted_successfully')):"ToDo deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>ToDo updated successfully.</label>
                                    <input type="text" name="todo_updated_successfully" value="<?=$this->lang->line('todo_updated_successfully')?htmlspecialchars($this->lang->line('todo_updated_successfully')):"ToDo updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>ToDo created successfully.</label>
                                    <input type="text" name="todo_created_successfully" value="<?=$this->lang->line('todo_created_successfully')?htmlspecialchars($this->lang->line('todo_created_successfully')):"ToDo created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Tax deleted successfully.</label>
                                    <input type="text" name="tax_deleted_successfully" value="<?=$this->lang->line('tax_deleted_successfully')?htmlspecialchars($this->lang->line('tax_deleted_successfully')):"Tax deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Tax created successfully.</label>
                                    <input type="text" name="tax_created_successfully" value="<?=$this->lang->line('tax_created_successfully')?htmlspecialchars($this->lang->line('tax_created_successfully')):"Tax created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Tax updated successfully.</label>
                                    <input type="text" name="tax_updated_successfully" value="<?=$this->lang->line('tax_updated_successfully')?htmlspecialchars($this->lang->line('tax_updated_successfully')):"Tax updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Wrong update file is selected.</label>
                                    <input type="text" name="wrong_update_file_is_selected" value="<?=$this->lang->line('wrong_update_file_is_selected')?htmlspecialchars($this->lang->line('wrong_update_file_is_selected')):"Wrong update file is selected."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Select valid zip file.</label>
                                    <input type="text" name="select_valid_zip_file" value="<?=$this->lang->line('select_valid_zip_file')?htmlspecialchars($this->lang->line('select_valid_zip_file')):"Select valid zip file."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Error occured during file extracting. Select valid zip file OR Please Try again later.</label>
                                    <input type="text" name="error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later" value="<?=$this->lang->line('error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later')?htmlspecialchars($this->lang->line('error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later')):"Error occured during file extracting. Select valid zip file OR Please Try again later."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Error occured during file uploading. Select valid zip file OR Please Try again later.</label>
                                    <input type="text" name="error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later" value="<?=$this->lang->line('error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later')?htmlspecialchars($this->lang->line('error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later')):"Error occured during file uploading. Select valid zip file OR Please Try again later."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment Setting Saved.</label>
                                    <input type="text" name="payment_setting_saved" value="<?=$this->lang->line('payment_setting_saved')?htmlspecialchars($this->lang->line('payment_setting_saved')):"Payment Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Email Setting Saved.</label>
                                    <input type="text" name="email_setting_saved" value="<?=$this->lang->line('email_setting_saved')?htmlspecialchars($this->lang->line('email_setting_saved')):"Email Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>General Setting Saved.</label>
                                    <input type="text" name="general_setting_saved" value="<?=$this->lang->line('general_setting_saved')?htmlspecialchars($this->lang->line('general_setting_saved')):"General Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Company Setting Saved.</label>
                                    <input type="text" name="company_setting_saved" value="<?=$this->lang->line('company_setting_saved')?htmlspecialchars($this->lang->line('company_setting_saved')):"Company Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Permissions Setting Saved.</label>
                                    <input type="text" name="permissions_setting_saved" value="<?=$this->lang->line('permissions_setting_saved')?htmlspecialchars($this->lang->line('permissions_setting_saved')):"Permissions Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Frontend Setting Saved.</label>
                                    <input type="text" name="frontend_setting_saved" value="<?=$this->lang->line('frontend_setting_saved')?htmlspecialchars($this->lang->line('frontend_setting_saved')):"Frontend Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Project deleted successfully.</label>
                                    <input type="text" name="project_deleted_successfully" value="<?=$this->lang->line('project_deleted_successfully')?htmlspecialchars($this->lang->line('project_deleted_successfully')):"Project deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Project updated successfully.</label>
                                    <input type="text" name="project_updated_successfully" value="<?=$this->lang->line('project_updated_successfully')?htmlspecialchars($this->lang->line('project_updated_successfully')):"Project updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Project created successfully.</label>
                                    <input type="text" name="project_created_successfully" value="<?=$this->lang->line('project_created_successfully')?htmlspecialchars($this->lang->line('project_created_successfully')):"Project created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Task deleted successfully.</label>
                                    <input type="text" name="task_deleted_successfully" value="<?=$this->lang->line('task_deleted_successfully')?htmlspecialchars($this->lang->line('task_deleted_successfully')):"Task deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Task created successfully.</label>
                                    <input type="text" name="task_created_successfully" value="<?=$this->lang->line('task_created_successfully')?htmlspecialchars($this->lang->line('task_created_successfully')):"Task created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Task updated successfully.</label>
                                    <input type="text" name="task_updated_successfully" value="<?=$this->lang->line('task_updated_successfully')?htmlspecialchars($this->lang->line('task_updated_successfully')):"Task updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Comment created successfully.</label>
                                    <input type="text" name="comment_created_successfully" value="<?=$this->lang->line('comment_created_successfully')?htmlspecialchars($this->lang->line('comment_created_successfully')):"Comment created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Status updated successfully.</label>
                                    <input type="text" name="status_updated_successfully" value="<?=$this->lang->line('status_updated_successfully')?htmlspecialchars($this->lang->line('status_updated_successfully')):"Status updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>User deleted successfully.</label>
                                    <input type="text" name="user_deleted_successfully" value="<?=$this->lang->line('user_deleted_successfully')?htmlspecialchars($this->lang->line('user_deleted_successfully')):"User deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>System updated successfully.</label>
                                    <input type="text" name="system_updated_successfully" value="<?=$this->lang->line('system_updated_successfully')?htmlspecialchars($this->lang->line('system_updated_successfully')):"System updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>File deleted successfully.</label>
                                    <input type="text" name="file_deleted_successfully" value="<?=$this->lang->line('file_deleted_successfully')?htmlspecialchars($this->lang->line('file_deleted_successfully')):"File deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan deleted successfully.</label>
                                    <input type="text" name="plan_deleted_successfully" value="<?=$this->lang->line('plan_deleted_successfully')?htmlspecialchars($this->lang->line('plan_deleted_successfully')):"Plan deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan updated successfully.</label>
                                    <input type="text" name="plan_updated_successfully" value="<?=$this->lang->line('plan_updated_successfully')?htmlspecialchars($this->lang->line('plan_updated_successfully')):"Plan updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan created successfully.</label>
                                    <input type="text" name="plan_created_successfully" value="<?=$this->lang->line('plan_created_successfully')?htmlspecialchars($this->lang->line('plan_created_successfully')):"Plan created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offline / Bank Transfer request sent successfully.</label>
                                    <input type="text" name="offline_bank_transfer_request_sent_successfully" value="<?=$this->lang->line('offline_bank_transfer_request_sent_successfully')?htmlspecialchars($this->lang->line('offline_bank_transfer_request_sent_successfully')):"Offline / Bank Transfer request sent successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>offline / bank transfer request accepted successfully.</label>
                                    <input type="text" name="offline_request_accepted_successfully" value="<?=$this->lang->line('offline_request_accepted_successfully')?htmlspecialchars($this->lang->line('offline_request_accepted_successfully')):"offline / bank transfer request accepted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>offline / bank transfer request rejected successfully.</label>
                                    <input type="text" name="offline_request_rejected_successfully" value="<?=$this->lang->line('offline_request_rejected_successfully')?htmlspecialchars($this->lang->line('offline_request_rejected_successfully')):"offline / bank transfer request rejected successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment unsuccessful. Please Try again later.</label>
                                    <input type="text" name="payment_unsuccessful_please_try_again_later" value="<?=$this->lang->line('payment_unsuccessful_please_try_again_later')?htmlspecialchars($this->lang->line('payment_unsuccessful_please_try_again_later')):"Payment unsuccessful. Please Try again later."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment successful.</label>
                                    <input type="text" name="payment_successful" value="<?=$this->lang->line('payment_successful')?htmlspecialchars($this->lang->line('payment_successful')):"Payment successful."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Bank transfer request received for the invoice</label>
                                    <input type="text" name="bank_transfer_request_received_for_the_invoice" value="<?=$this->lang->line('bank_transfer_request_received_for_the_invoice')?htmlspecialchars($this->lang->line('bank_transfer_request_received_for_the_invoice')):"Bank transfer request received for the invoice"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Bank transfer request accepted for the invoice</label>
                                    <input type="text" name="bank_transfer_request_accepted_for_the_invoice" value="<?=$this->lang->line('bank_transfer_request_accepted_for_the_invoice')?htmlspecialchars($this->lang->line('bank_transfer_request_accepted_for_the_invoice')):"Bank transfer request accepted for the invoice"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Bank transfer request rejected for the invoice</label>
                                    <input type="text" name="bank_transfer_request_rejected_for_the_invoice" value="<?=$this->lang->line('bank_transfer_request_rejected_for_the_invoice')?htmlspecialchars($this->lang->line('bank_transfer_request_rejected_for_the_invoice')):"Bank transfer request rejected for the invoice"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment received for the invoice</label>
                                    <input type="text" name="payment_received_for_the_invoice" value="<?=$this->lang->line('payment_received_for_the_invoice')?htmlspecialchars($this->lang->line('payment_received_for_the_invoice')):"Payment received for the invoice"?>" class="form-control">
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Choose valid subscription plan.</label>
                                    <input type="text" name="choose_valid_subscription_plan" value="<?=$this->lang->line('choose_valid_subscription_plan')?htmlspecialchars($this->lang->line('choose_valid_subscription_plan')):"Choose valid subscription plan."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan subscribed successfully.</label>
                                    <input type="text" name="plan_subscribed_successfully" value="<?=$this->lang->line('plan_subscribed_successfully')?htmlspecialchars($this->lang->line('plan_subscribed_successfully')):"Plan subscribed successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Notification deleted successfully.</label>
                                    <input type="text" name="notification_deleted_successfully" value="<?=$this->lang->line('notification_deleted_successfully')?htmlspecialchars($this->lang->line('notification_deleted_successfully')):"Notification deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Note deleted successfully.</label>
                                    <input type="text" name="note_deleted_successfully" value="<?=$this->lang->line('note_deleted_successfully')?htmlspecialchars($this->lang->line('note_deleted_successfully')):"Note deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Note updated successfully.</label>
                                    <input type="text" name="note_updated_successfully" value="<?=$this->lang->line('note_updated_successfully')?htmlspecialchars($this->lang->line('note_updated_successfully')):"Note updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Note created successfully.</label>
                                    <input type="text" name="note_created_successfully" value="<?=$this->lang->line('note_created_successfully')?htmlspecialchars($this->lang->line('note_created_successfully')):"Note created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Only english characters allowed.</label>
                                    <input type="text" name="only_english_characters_allowed" value="<?=$this->lang->line('only_english_characters_allowed')?htmlspecialchars($this->lang->line('only_english_characters_allowed')):"Only english characters allowed."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Already exists this language.</label>
                                    <input type="text" name="already_exists_this_language" value="<?=$this->lang->line('already_exists_this_language')?htmlspecialchars($this->lang->line('already_exists_this_language')):"Already exists this language."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Language deleted successfully.</label>
                                    <input type="text" name="language_deleted_successfully" value="<?=$this->lang->line('language_deleted_successfully')?htmlspecialchars($this->lang->line('language_deleted_successfully')):"Language deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Language updated successfully.</label>
                                    <input type="text" name="language_updated_successfully" value="<?=$this->lang->line('language_updated_successfully')?htmlspecialchars($this->lang->line('language_updated_successfully')):"Language updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Language created successfully.</label>
                                    <input type="text" name="language_created_successfully" value="<?=$this->lang->line('language_created_successfully')?htmlspecialchars($this->lang->line('language_created_successfully')):"Language created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>We will get back to you soon.</label>
                                    <input type="text" name="we_will_get_back_to_you_soon" value="<?=$this->lang->line('we_will_get_back_to_you_soon')?htmlspecialchars($this->lang->line('we_will_get_back_to_you_soon')):"We will get back to you soon."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Feature deleted successfully.</label>
                                    <input type="text" name="feature_deleted_successfully" value="<?=$this->lang->line('feature_deleted_successfully')?htmlspecialchars($this->lang->line('feature_deleted_successfully')):"Feature deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Feature updated successfully.</label>
                                    <input type="text" name="feature_updated_successfully" value="<?=$this->lang->line('feature_updated_successfully')?htmlspecialchars($this->lang->line('feature_updated_successfully')):"Feature updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Pages updated successfully.</label>
                                    <input type="text" name="pages_updated_successfully" value="<?=$this->lang->line('pages_updated_successfully')?htmlspecialchars($this->lang->line('pages_updated_successfully')):"Pages updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Feature created successfully.</label>
                                    <input type="text" name="feature_created_successfully" value="<?=$this->lang->line('feature_created_successfully')?htmlspecialchars($this->lang->line('feature_created_successfully')):"Feature created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Chat deleted successfully.</label>
                                    <input type="text" name="chat_deleted_successfully" value="<?=$this->lang->line('chat_deleted_successfully')?htmlspecialchars($this->lang->line('chat_deleted_successfully')):"Chat deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Chat created successfully.</label>
                                    <input type="text" name="chat_created_successfully" value="<?=$this->lang->line('chat_created_successfully')?htmlspecialchars($this->lang->line('chat_created_successfully')):"Chat created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Wait...</label>
                                    <input type="text" name="wait" value="<?=$this->lang->line('wait')?htmlspecialchars($this->lang->line('wait')):"Wait..."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Default language can not be deleted.</label>
                                    <input type="text" name="default_language_can_not_be_deleted" value="<?=$this->lang->line('default_language_can_not_be_deleted')?htmlspecialchars($this->lang->line('default_language_can_not_be_deleted')):"Default language can not be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Are you sure?</label>
                                    <input type="text" name="are_you_sure" value="<?=$this->lang->line('are_you_sure')?htmlspecialchars($this->lang->line('are_you_sure')):"Are you sure?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Notification?</label>
                                    <input type="text" name="you_want_to_delete_this_notification" value="<?=$this->lang->line('you_want_to_delete_this_notification')?htmlspecialchars($this->lang->line('you_want_to_delete_this_notification')):"You want to delete this Notification?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Feature?</label>
                                    <input type="text" name="you_want_to_delete_this_feature" value="<?=$this->lang->line('you_want_to_delete_this_feature')?htmlspecialchars($this->lang->line('you_want_to_delete_this_feature')):"You want to delete this Feature?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want reject this offline / bank transfer request? This can not be undo.</label>
                                    <input type="text" name="you_want_reject_this_offline_request_this_can_not_be_undo" value="<?=$this->lang->line('you_want_reject_this_offline_request_this_can_not_be_undo')?htmlspecialchars($this->lang->line('you_want_reject_this_offline_request_this_can_not_be_undo')):"You want reject this offline / bank transfer request? This can not be undo."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want accept this offline / bank transfer request? This can not be undo.</label>
                                    <input type="text" name="you_want_accept_this_offline_request_this_can_not_be_undo" value="<?=$this->lang->line('you_want_accept_this_offline_request_this_can_not_be_undo')?htmlspecialchars($this->lang->line('you_want_accept_this_offline_request_this_can_not_be_undo')):"You want accept this offline / bank transfer request? This can not be undo."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Default plan can not be deleted.</label>
                                    <input type="text" name="default_plan_can_not_be_deleted" value="<?=$this->lang->line('default_plan_can_not_be_deleted')?htmlspecialchars($this->lang->line('default_plan_can_not_be_deleted')):"Default plan can not be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Plan? All users under this plan will be added to the Default Plan.</label>
                                    <input type="text" name="you_want_to_delete_this_plan_all_users_under_this_plan_will_be_added_to_the_default_plan" value="<?=$this->lang->line('you_want_to_delete_this_plan_all_users_under_this_plan_will_be_added_to_the_default_plan')?htmlspecialchars($this->lang->line('you_want_to_delete_this_plan_all_users_under_this_plan_will_be_added_to_the_default_plan')):"You want to delete this Plan? All users under this plan will be added to the Default Plan."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this ToDo?</label>
                                    <input type="text" name="you_want_to_delete_this_todo" value="<?=$this->lang->line('you_want_to_delete_this_todo')?htmlspecialchars($this->lang->line('you_want_to_delete_this_todo')):"You want to delete this ToDo?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this note?</label>
                                    <input type="text" name="you_want_to_delete_this_note" value="<?=$this->lang->line('you_want_to_delete_this_note')?htmlspecialchars($this->lang->line('you_want_to_delete_this_note')):"You want to delete this note?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this project? All related data with this project also will be deleted.</label>
                                    <input type="text" name="you_want_to_delete_this_project_all_related_data_with_this_project_also_will_be_deleted" value="<?=$this->lang->line('you_want_to_delete_this_project_all_related_data_with_this_project_also_will_be_deleted')?htmlspecialchars($this->lang->line('you_want_to_delete_this_project_all_related_data_with_this_project_also_will_be_deleted')):"You want to delete this project? All related data with this project also will be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this task? All related data with this task also will be deleted.</label>
                                    <input type="text" name="you_want_to_delete_this_task_all_related_data_with_this_task_also_will_be_deleted" value="<?=$this->lang->line('you_want_to_delete_this_task_all_related_data_with_this_task_also_will_be_deleted')?htmlspecialchars($this->lang->line('you_want_to_delete_this_task_all_related_data_with_this_task_also_will_be_deleted')):"You want to delete this task? All related data with this task also will be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this user? All related data with this user also will be deleted.</label>
                                    <input type="text" name="you_want_to_delete_this_user_all_related_data_with_this_user_also_will_be_deleted" value="<?=$this->lang->line('you_want_to_delete_this_user_all_related_data_with_this_user_also_will_be_deleted')?htmlspecialchars($this->lang->line('you_want_to_delete_this_user_all_related_data_with_this_user_also_will_be_deleted')):"You want to delete this user? All related data with this user also will be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to upgrade the system? Please take a backup before going further.</label>
                                    <input type="text" name="you_want_to_upgrade_the_system_please_take_a_backup_before_going_further" value="<?=$this->lang->line('you_want_to_upgrade_the_system_please_take_a_backup_before_going_further')?htmlspecialchars($this->lang->line('you_want_to_upgrade_the_system_please_take_a_backup_before_going_further')):"You want to upgrade the system? Please take a backup before going further."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this file?</label>
                                    <input type="text" name="you_want_to_delete_this_file" value="<?=$this->lang->line('you_want_to_delete_this_file')?htmlspecialchars($this->lang->line('you_want_to_delete_this_file')):"You want to delete this file?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to activate this user?</label>
                                    <input type="text" name="you_want_to_activate_this_user" value="<?=$this->lang->line('you_want_to_activate_this_user')?htmlspecialchars($this->lang->line('you_want_to_activate_this_user')):"You want to activate this user?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to deactivate this user? This user will be not able to login after deactivation.</label>
                                    <input type="text" name="you_want_to_deactivate_this_user_this_user_will_be_not_able_to_login_after_deactivation" value="<?=$this->lang->line('you_want_to_deactivate_this_user_this_user_will_be_not_able_to_login_after_deactivation')?htmlspecialchars($this->lang->line('you_want_to_deactivate_this_user_this_user_will_be_not_able_to_login_after_deactivation')):"You want to deactivate this user? This user will be not able to login after deactivation."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this chat? This can not be undo.</label>
                                    <input type="text" name="you_want_to_delete_this_chat_this_can_not_be_undo" value="<?=$this->lang->line('you_want_to_delete_this_chat_this_can_not_be_undo')?htmlspecialchars($this->lang->line('you_want_to_delete_this_chat_this_can_not_be_undo')):"You want to delete this chat? This can not be undo."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>We will contact you for further process of payment as soon as possible. Click OK to confirm.</label>
                                    <input type="text" name="we_will_contact_you_for_further_process_of_payment_as_soon_as_possible_click_ok_to_confirm" value="<?=$this->lang->line('we_will_contact_you_for_further_process_of_payment_as_soon_as_possible_click_ok_to_confirm')?htmlspecialchars($this->lang->line('we_will_contact_you_for_further_process_of_payment_as_soon_as_possible_click_ok_to_confirm')):"We will contact you for further process of payment as soon as possible. Click OK to confirm."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Currency code need as per three letter ISO code.</label>
                                    <input type="text" name="currency_code_need_as_per_three_letter_iso_code" value="<?=$this->lang->line('currency_code_need_as_per_three_letter_iso_code')?htmlspecialchars($this->lang->line('currency_code_need_as_per_three_letter_iso_code')):"Currency code need as per three letter ISO code. Make sure payment gateways supporting this currency."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This details will be used as billing details.</label>
                                    <input type="text" name="this_details_will_be_used_as_billing_details" value="<?=$this->lang->line('this_details_will_be_used_as_billing_details')?htmlspecialchars($this->lang->line('this_details_will_be_used_as_billing_details')):"This details will be used as billing details."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Language?</label>
                                    <input type="text" name="you_want_to_delete_this_language" value="<?=$this->lang->line('you_want_to_delete_this_language')?htmlspecialchars($this->lang->line('you_want_to_delete_this_language')):"You want to delete this Language?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Tax?</label>
                                    <input type="text" name="you_want_to_delete_this_tax" value="<?=$this->lang->line('you_want_to_delete_this_tax')?htmlspecialchars($this->lang->line('you_want_to_delete_this_tax')):"You want to delete this Tax?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Invoice?</label>
                                    <input type="text" name="you_want_to_delete_this_invoice" value="<?=$this->lang->line('you_want_to_delete_this_invoice')?htmlspecialchars($this->lang->line('you_want_to_delete_this_invoice')):"You want to delete this Invoice?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice deleted successfully.</label>
                                    <input type="text" name="invoice_deleted_successfully" value="<?=$this->lang->line('invoice_deleted_successfully')?htmlspecialchars($this->lang->line('invoice_deleted_successfully')):"Invoice deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice created successfully.</label>
                                    <input type="text" name="invoice_created_successfully" value="<?=$this->lang->line('invoice_created_successfully')?htmlspecialchars($this->lang->line('invoice_created_successfully')):"Invoice created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invoice updated successfully.</label>
                                    <input type="text" name="invoice_updated_successfully" value="<?=$this->lang->line('invoice_updated_successfully')?htmlspecialchars($this->lang->line('invoice_updated_successfully')):"Invoice updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>We couldn't find any data</label>
                                    <input type="text" name="we_couldnt_find_any_data" value="<?=$this->lang->line('we_couldnt_find_any_data')?htmlspecialchars($this->lang->line('we_couldnt_find_any_data')):"We couldn't find any data"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Ending date should not be less then starting date.</label>
                                    <input type="text" name="ending_date_should_not_be_less_then_starting_date" value="<?=$this->lang->line('ending_date_should_not_be_less_then_starting_date')?htmlspecialchars($this->lang->line('ending_date_should_not_be_less_then_starting_date')):"Ending date should not be less then starting date."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Created successfully.</label>
                                    <input type="text" name="created_successfully" value="<?=$this->lang->line('created_successfully')?htmlspecialchars($this->lang->line('created_successfully')):"Created successfully"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Updated successfully.</label>
                                    <input type="text" name="updated_successfully" value="<?=$this->lang->line('updated_successfully')?htmlspecialchars($this->lang->line('updated_successfully')):"Updated successfully"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Deleted successfully.</label>
                                    <input type="text" name="deleted_successfully" value="<?=$this->lang->line('deleted_successfully')?htmlspecialchars($this->lang->line('deleted_successfully')):"Deleted successfully"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Timer already running on this task</label>
                                    <input type="text" name="timer_already_running_on_this_task" value="<?=$this->lang->line('timer_already_running_on_this_task')?htmlspecialchars($this->lang->line('timer_already_running_on_this_task')):"Timer already running on this task"?>" class="form-control">
                                </div>
                              
                                <div class="form-group col-md-12">
                                    <label>Storage Limit Exceeded</label>
                                    <input type="text" name="storage_limit_exceeded" value="<?=$this->lang->line('storage_limit_exceeded')?htmlspecialchars($this->lang->line('storage_limit_exceeded')):'Storage Limit Exceeded'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label>Please check your inbox and confirm your email address to activate your account.</label>
                                    <input type="text" name="please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account" value="<?=$this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account')?htmlspecialchars($this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account')):'Please check your inbox and confirm your email address to activate your account.'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label>Required email confirmation for new users.</label>
                                    <input type="text" name="required_email_confirmation_for_new_users" value="<?=$this->lang->line('required_email_confirmation_for_new_users')?htmlspecialchars($this->lang->line('required_email_confirmation_for_new_users')):'Required email confirmation for new users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Make sure to activate the account or ask the user to confirm the email address.</label>
                                    <input type="text" name="make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address" value="<?=$this->lang->line('make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address')?htmlspecialchars($this->lang->line('make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address')):'Make sure to activate the account or ask the user to confirm the email address.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Any special characters not allowed</label>
                                    <input type="text" name="any_special_characters_not_allowed" value="<?=$this->lang->line('any_special_characters_not_allowed')?htmlspecialchars($this->lang->line('any_special_characters_not_allowed')):'Any special characters not allowed'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>New project comment</label>
                                    <input type="text" name="new_project_comment" value="<?=$this->lang->line('new_project_comment')?htmlspecialchars($this->lang->line('new_project_comment')):'New project comment'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Leave request received</label>
                                    <input type="text" name="leave_request_received" value="<?=$this->lang->line('leave_request_received')?htmlspecialchars($this->lang->line('leave_request_received')):'Leave request received'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Leave request rejected</label>
                                    <input type="text" name="leave_request_rejected" value="<?=$this->lang->line('leave_request_rejected')?htmlspecialchars($this->lang->line('leave_request_rejected')):'Leave request rejected'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Leave request accepted</label>
                                    <input type="text" name="leave_request_accepted" value="<?=$this->lang->line('leave_request_accepted')?htmlspecialchars($this->lang->line('leave_request_accepted')):'Leave request accepted'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>I agree to the terms and conditions</label>
                                    <input type="text" name="i_agree_to_the_terms_and_conditions" value="<?=$this->lang->line('i_agree_to_the_terms_and_conditions')?htmlspecialchars($this->lang->line('i_agree_to_the_terms_and_conditions')):'I agree to the terms and conditions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Add details for bank transfer or custom payment</label>
                                    <input type="text" name="add_details_for_bank_transfer_or_custom_payment" value="<?=$this->lang->line('add_details_for_bank_transfer_or_custom_payment')?htmlspecialchars($this->lang->line('add_details_for_bank_transfer_or_custom_payment')):'Add details for bank transfer or custom payment'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload and Send for Confirmation</label>
                                    <input type="text" name="upload_and_send_for_confirmation" value="<?=$this->lang->line('upload_and_send_for_confirmation')?htmlspecialchars($this->lang->line('upload_and_send_for_confirmation')):'Upload and Send for Confirmation'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Supported Formats: jpg, jpeg, png</label>
                                    <input type="text" name="supported_formats" value="<?=$this->lang->line('supported_formats')?htmlspecialchars($this->lang->line('supported_formats')):'Supported Formats: jpg, jpeg, png'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>You will be logged out from the current account.</label>
                                    <input type="text" name="you_will_be_logged_out_from_the_current_account" value="<?=$this->lang->line('you_will_be_logged_out_from_the_current_account')?htmlspecialchars($this->lang->line('you_will_be_logged_out_from_the_current_account')):'You will be logged out from the current account.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Please explain your problem in detail. We will get back to you ASAP.</label>
                                    <input type="text" name="please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP" value="<?=$this->lang->line('please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP')?htmlspecialchars($this->lang->line('please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP')):'Please explain your problem in detail. We will get back to you ASAP.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your account has been successfully created.</label>
                                    <input type="text" name="your_account_has_been_successfully_created" value="<?=$this->lang->line('your_account_has_been_successfully_created')?htmlspecialchars($this->lang->line('your_account_has_been_successfully_created')):'Your account has been successfully created.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>New lead assigned to you.</label>
                                    <input type="text" name="new_lead_assigned_to_you" value="<?=$this->lang->line('new_lead_assigned_to_you')?htmlspecialchars($this->lang->line('new_lead_assigned_to_you')):'New lead assigned to you.'?>" class="form-control">
                                </div>








                                <div class="section-title col-md-12 mt-0">Frontend</div>
                                <div class="form-group col-md-12">
                                    <label>Home (Hero) Title</label>
                                    <input type="text" name="frontend_home_title" value="<?=$this->lang->line('frontend_home_title')?htmlspecialchars($this->lang->line('frontend_home_title')):'Professional Project Management tool and CRM'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Home (Hero) Description</label>
                                    <input type="text" name="frontend_home_description" value="<?=$this->lang->line('frontend_home_description')?htmlspecialchars($this->lang->line('frontend_home_description')):'TimWork SaaS is a perfect, robust, lightweight, superfast web application to fulfill all your Team Collaboration, Project Management and CRM needs.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Cookie Message.</label>
                                    <input type="text" name="frontend_cookie_message" value="<?=$this->lang->line('frontend_cookie_message')?htmlspecialchars($this->lang->line('frontend_cookie_message')):'We use cookies to ensure that we give you the best experience on our website.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>404 error Message.</label>
                                    <input type="text" name="404_error_message" value="<?=$this->lang->line('404_error_message')?htmlspecialchars($this->lang->line('404_error_message')):'The page you were looking for could not be found.'?>" class="form-control">
                                </div>

                            </div>
                            <div class="card-footer bg-whitesmoke text-md-right">
                                <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?htmlspecialchars($this->lang->line('save_changes')):'Save Changes'?></button>
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
</body>
</html>
