<?php $this->load->view('includes/head'); ?>
<style>
  .toggle-heading {
    cursor: pointer;
  }

  .card-header {
  position: relative;
}

.toggle-icon {
  position: absolute;
  right: 10px; /* Added 'px' unit here */
  top: 10px; /* Added 'px' unit here */
}
  .toggle-icon::before {
    content: "\f078"; /* Up arrow icon */
    font-family: 'Font Awesome 5 Free';
    display: inline-block;
    width: 20px; /* Adjust the width as needed */
    text-align: center;
  }

  .expanded .toggle-icon::before {
    content: "\f077"; /* Down arrow icon when expanded */
  }
</style>
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
              <h1><?=$this->lang->line('settings')?$this->lang->line('settings'):'Settings'?></h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
                <div class="breadcrumb-item"><?=$this->lang->line('settings')?$this->lang->line('settings'):'Settings'?></div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="card card-primary">
                    <div class="card-body">
                      <ul class="nav nav-pills flex-column">
                        <li class="nav-item"><a href="<?=base_url('settings')?>" class="nav-link <?=($main_page == 'general')?'active':''?>"><i class="fas fa-cogs"></i> <?=$this->lang->line('general')?$this->lang->line('general'):'General'?></a></li>

                        <?php if ($this->ion_auth->in_group(3)){ ?> 
                          <li class="nav-item"><a href="<?=base_url('settings/payment')?>" class="nav-link <?=($main_page == 'payment')?'active':''?>"><i class="fab fa-paypal"></i> <?=$this->lang->line('payment_gateway')?$this->lang->line('payment_gateway'):'Payment Gateway'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/seo')?>" class="nav-link <?=($main_page == 'seo')?'active':''?>"><i class="fas fa-search"></i> <?=$this->lang->line('seo')?$this->lang->line('seo'):'SEO'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/logins')?>" class="nav-link <?=($main_page == 'logins')?'active':''?>"><i class="fab fa-google"></i> <?=$this->lang->line('social_login')?htmlspecialchars($this->lang->line('social_login')):'Social Login'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/email')?>" class="nav-link <?=($main_page == 'email')?'active':''?>"><i class="fas fa-at"></i> <?=$this->lang->line('email')?$this->lang->line('email'):'Email'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/email-templates')?>" class="nav-link <?=($main_page == 'email-templates')?'active':''?>"><i class="fas fa-mail-bulk"></i> <?=$this->lang->line('email_templates')?$this->lang->line('email_templates'):'Email Templates'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('languages')?>" class="nav-link <?=($main_page == 'languages')?'active':''?>"><i class="fa fa-language"></i> <?=$this->lang->line('languages')?$this->lang->line('languages'):'Languages'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/update')?>" class="nav-link <?=($main_page == 'update')?'active':''?>"><i class="fas fa-hand-holding-heart"></i> <?=$this->lang->line('update')?$this->lang->line('update'):'Update'?></a></li>

                          <li class="nav-item"><a href="<?=base_url('settings/recaptcha')?>" class="nav-link <?=($main_page == 'recaptcha')?'active':''?>"><i class="fas fa-certificate"></i> <?=$this->lang->line('google_recaptcha')?$this->lang->line('google_recaptcha'):'Google reCAPTCHA'?></a></li>

                          <li class="nav-item"><a href="<?=base_url('settings/custom-code')?>" class="nav-link <?=($main_page == 'custom-code')?'active':''?>"><i class="fas fa-code"></i> <?=$this->lang->line('custom_code')?$this->lang->line('custom_code'):'Custom Code'?></a></li>
                        <?php }else{ ?>
                          <li class="nav-item"><a href="<?=base_url('settings/company')?>" class="nav-link <?=($main_page == 'company')?'active':''?>"><i class="fas fa-copyright"></i> <?=$this->lang->line('company')?$this->lang->line('company'):'Company'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/leaves')?>" class="nav-link <?=($main_page == 'leaves')?'active':''?>"><i class="fas fa-umbrella-beach"></i> <?=$this->lang->line('leave_type')?$this->lang->line('leave_type'):'Leave Type'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/device_config')?>" class="nav-link <?=($main_page == 'device_config')?'active':''?>"><i class="fas fa-microchip"></i> <?=$this->lang->line('device_config')?$this->lang->line('device_config'):'Device Configuration'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/departments')?>" class="nav-link <?=($main_page == 'departments')?'active':''?>"><i class="fas fa-building"></i> <?=$this->lang->line('departments')?$this->lang->line('departments'):'Departments'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/shift')?>" class="nav-link <?=($main_page == 'shift')?'active':''?>"><i class="fas fa-clock"></i> <?=$this->lang->line('shift_schedule')?$this->lang->line('shift_schedule'):'Shift Schedule'?></a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/department')?>" class="nav-link <?=($main_page == 'department')?'active':''?>"><i class="fas fa-business-time"></i> <?=$this->lang->line('time_schedule')?$this->lang->line('time_schedule'):'Time Schedule'?></a></li>
                          <?php if (is_module_allowed('taxes')){ ?> 
                            <!-- <li class="nav-item"><a href="<?=base_url('settings/taxes')?>" class="nav-link <?=($main_page == 'taxes')?'active':''?>"><i class="fas fa-money-bill-alt"></i> <?=$this->lang->line('taxes')?$this->lang->line('taxes'):'Taxes'?></a></li> -->
                          <?php } ?> 
                          <?php if (is_module_allowed('user_permissions')){ ?> 
                             <!--<li class="nav-item"><a href="<?=base_url('settings/user-permissions')?>" class="nav-link <?=($main_page == 'permissions')?'active':''?>"><i class="fas fa-user-cog"></i> <?=$this->lang->line('user_permissions')?$this->lang->line('user_permissions'):'User Permissions'?></a></li> -->
                             
                            <li class="nav-item"><a href="<?=base_url('settings/roles')?>" class="nav-link <?=($main_page == 'roles')?'active':''?>"><i class="fas fa-user-cog"></i> <?=$this->lang->line('roles')?$this->lang->line('roles'):'Roles'?></a></li>
                            <li class="nav-item"><a href="<?=base_url('settings/roles-permissions')?>" class="nav-link <?=($main_page == 'roles_permissions')?'active':''?>"><i class="fas fa-key"></i> <?=$this->lang->line('permissions')?$this->lang->line('permissions'):'Permissions'?></a></li>
                          <?php } ?>
                        <?php } ?>
                        
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="card card-primary" id="settings-card">
                    <?php $this->load->view('setting-forms/'.htmlspecialchars($main_page)); ?>
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

<?php if($this->uri->segment(2) == 'custom-code'){ ?>
  <script>
    CodeMirror.fromTextArea(document.getElementById('header_code'), { 
      lineNumbers: true,
      theme: 'duotone-dark',
    }).on('change', editor => {
      $("#header_code").val(editor.getValue());
    });

    CodeMirror.fromTextArea(document.getElementById('footer_code'), { 
      lineNumbers: true,
      theme: 'duotone-dark',
    }).on('change', editor => {
      $("#footer_code").val(editor.getValue());
    });
  </script>
<?php } ?>


<script>
$(document).ready(function(){
  // Attach click event handler to all toggle-heading elements
  $(".toggle-heading").click(function(){
    // Toggle the expanded class to control the icon
    $(this).toggleClass("expanded");
    
    // Get the data-toggle attribute value
    var target = $(this).data("toggle");
    
    // Hide all inner-content elements except the one associated with the clicked heading
    $(".inner-content").not("[data-target=" + target + "]").hide();
    
    // Toggle the visibility of the inner-content element associated with the clicked heading
    $("[data-target=" + target + "]").toggle();
  });
});
$(document).ready(function() {
    // Function to fetch department time based on the selected shift type
    function getDepartmentTime() {
        $.ajax({
            url: '<?= base_url('settings/get_grace_minutes') ?>',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    var grace = JSON.parse(response);
                    if (grace.days_counter) {
                        $('#days_counter').val(grace.days_counter);
                    }if (grace.grace_minutes) {
                        $('#grace_minutes').val(grace.grace_minutes);
                    }
                    if (grace.apply == 1) {
                        $('#enableGraceMinutes').prop('checked', true);
                    }
                    // Add logic to set other elements based on departmentTime
                }
            },
            error: function() {
                // Handle the error case here
            }
        });
    }

    getDepartmentTime();
});

function teamMembersFormatter(value, row) {
    const teamMembers = value.split('<br>');
    const maxVisibleTeamMembers = 10;

    if (teamMembers.length > maxVisibleTeamMembers) {
        const visibleMembers = teamMembers.slice(0, maxVisibleTeamMembers).join('<br>');
        const hiddenMembers = teamMembers.slice(maxVisibleTeamMembers).join('<br>');
        return `
            <div class="visible-members">${visibleMembers}</div>
            <div class="hidden-members" style="display: none">${hiddenMembers}</div>
            <a href="#" class="see-more-link text-danger">See More...</a>
        `;
    } else {
        return value;
    }
}

function permissionsFormatter(value, row) {
    const teamMembers = value.split('<br>');
    const maxVisibleTeamMembers = 10;

    if (teamMembers.length > maxVisibleTeamMembers) {
        const visibleMembers = teamMembers.slice(0, maxVisibleTeamMembers).join('<br>');
        const hiddenMembers = teamMembers.slice(maxVisibleTeamMembers).join('<br>');
        return `
            <div class="visible-members">${visibleMembers}</div>
            <div class="hidden-members" style="display: none">${hiddenMembers}</div>
            <a href="#" class="see-more-link text-danger">See More...</a>
        `;
    } else {
        return value;
    }
}

$(document).ready(function() {
  $('#permissions').select2();
  $('#selectAllPermissions').change(function() {
      $('#permissions').val([]); 
      if (this.checked) {
          $('#permissions option').prop('selected', true);
      } else {
          $('#permissions option').prop('selected', false);
      }
      $('#permissions').trigger('change');
  });

  $('#permissions_create').select2();
  $('#selectAllPermissions_create').change(function() {
      $('#permissions_create').val([]); 
      if (this.checked) {
          $('#permissions_create option').prop('selected', true);
      } else {
          $('#permissions_create option').prop('selected', false);
      }
      $('#permissions_create').trigger('change');
  });

  $('#users').select2();
  $('#selectAllUsers').change(function() {
      $('#users').val([]); 
      if (this.checked) {
          $('#users option').prop('selected', true);
      } else {
          $('#users option').prop('selected', false);
      }
      $('#users').trigger('change');
  });

  $('#users_create').select2();
  $('#selectAllUsers_create').change(function() {
      $('#users_create').val([]); 
      if (this.checked) {
          $('#users_create option').prop('selected', true);
      } else {
          $('#users_create option').prop('selected', false);
      }
      $('#users_create').trigger('change');
  });
});

$(document).ready(function() {
    $(document).on('click', '.see-more-link', function(e) {
        e.preventDefault();
        console.log('See More link clicked');
        const $this = $(this);
        const $cell = $this.closest('td');
        $cell.find('.hidden-members').slideToggle();
        $this.text($this.text() === 'See More...' ? 'See Less' : 'See More...');
    });
});

    function setTableHeight() {
        var options = $('#shift_list').bootstrapTable('getOptions');
        options.height = 650;

        $('#shift_list').bootstrapTable('refreshOptions',options);
        var options = $('#leaves_list').bootstrapTable('getOptions');
        options.height = 600;

        $('#leaves_list').bootstrapTable('refreshOptions',options);
    }

    // Call the function initially and whenever the table data is refreshed
    $(document).ready(function() {
        setTableHeight();
    });
</script>

</body>
</html>
