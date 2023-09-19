<form action="<?=base_url('settings/save-permissions-setting')?>" method="POST" id="setting-form">

                    <div class="card-body row">
                      <div class="alert alert-danger col-md-12 center">
                        <b><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></b> <?=$this->lang->line('admin_always_have_all_the_permission_here_you_can_set_permissions_for_other_roles')?$this->lang->line('admin_always_have_all_the_permission_here_you_can_set_permissions_for_other_roles'):"Admin always have all the permission. Here you can set permissions for other roles."?>
                      </div>
                      <div class="col-md-12">
                        <div class="card-header">
                          <h4 class="card-title"><?=$this->lang->line('general_permissions')?$this->lang->line('general_permissions'):'General permissions'?></h4>
                        </div>
                        <div class="form-group mt-2 col-md-12">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="team_members_and_client_can_chat" name="team_members_and_client_can_chat" value="<?=(isset($members_permissions->team_members_and_client_can_chat) && !empty($members_permissions->team_members_and_client_can_chat))?$members_permissions->team_members_and_client_can_chat:0?>" <?=(isset($members_permissions->team_members_and_client_can_chat) && !empty($members_permissions->team_members_and_client_can_chat) && $members_permissions->team_members_and_client_can_chat == 1)?'checked':''?>>
                              <label class="form-check-label" for="team_members_and_client_can_chat"><?=$this->lang->line('team_embers_and_client_can_chat')?$this->lang->line('team_embers_and_client_can_chat'):'Team Members and Client can chat?'?></label>
                            </div>
                        </div>
                      </div>

                      <?php foreach ($roles as $role): 
                        $permissionsVariableName = $role['name'] . '_permissions';
                        $permissions = $$permissionsVariableName;?>
                        <div class="col-md-12">
                          <div class="card-header">
                            <h4 class="card-title toggle-heading" data-toggle="<?= $role['name'] ?>-permissions">
                              <?=$this->lang->line($role['description'].'_permissions') ? $this->lang->line($role['description'].'_permissions') : $role['description'].' Permissions'?>
                              <span class="toggle-icon" ></span>
                            </h4>
                          </div>
                          <div class="row">
                            <div class="inner-content mt-2 col-md-6" data-target="<?= $role['name'] ?>-permissions"  style="display: none;">
                              <?php if (isset($role['permissions'])): ?>
                                <?php if (strpos($role['permissions'], 'attendance') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'Attendance'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'attendance_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_attendance_view" name="<?= $role['name'] ?>_attendance_view" value="<?=(isset($permissions->attendance_view) && !empty($permissions->attendance_view))?$permissions->attendance_view:0?>" <?=(isset($permissions->attendance_view) && !empty($permissions->attendance_view) && $permissions->attendance_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_attendance_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      
                                      <?php if (strpos($role['permissions'], 'attendance_view_all ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_attendance_view_all" name="<?= $role['name'] ?>_attendance_view_all" value="<?=(isset($permissions->attendance_view_all) && !empty($permissions->attendance_view_all))?$permissions->attendance_view_all:0?>" <?=(isset($permissions->attendance_view_all) && !empty($permissions->attendance_view_all) && $permissions->attendance_view_all == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_attendance_view_all"><?=$this->lang->line('view_all_attedance')?$this->lang->line('view_all_attedance'):'View All Attendance'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'attendance_view_selected ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_attendance_view_selected" name="<?= $role['name'] ?>_attendance_view_selected" value="<?=(isset($permissions->attendance_view_selected) && !empty($permissions->attendance_view_selected))?$permissions->attendance_view_selected:0?>" <?=(isset($permissions->attendance_view_selected) && !empty($permissions->attendance_view_selected) && $permissions->attendance_view_selected == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_attendance_view_selected"><?=$this->lang->line('view_selected_user_attedance')?$this->lang->line('view_selected_user_attedance'):'View Selected User Attendance'?></label>
                                        </div>
                                      <?php endif; ?>

                                  </div>
                                <?php endif; ?>
                                
                                <?php if (strpos($role['permissions'], 'leaves') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('leaves')?$this->lang->line('leaves'):'Leaves'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'leaves_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leaves_view" name="<?= $role['name'] ?>_leaves_view" value="<?=(isset($permissions->leaves_view) && !empty($permissions->leaves_view))?$permissions->leaves_view:0?>" <?=(isset($permissions->leaves_view) && !empty($permissions->leaves_view) && $permissions->leaves_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leaves_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'leaves_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leaves_create" name="<?= $role['name'] ?>_leaves_create" value="<?=(isset($permissions->leaves_create) && !empty($permissions->leaves_create))?$permissions->leaves_create:0?>" <?=(isset($permissions->leaves_create) && !empty($permissions->leaves_create) && $permissions->leaves_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leaves_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'leaves_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leaves_edit" name="<?= $role['name'] ?>_leaves_edit" value="<?=(isset($permissions->leaves_edit) && !empty($permissions->leaves_edit))?$permissions->leaves_edit:0?>" <?=(isset($permissions->leaves_edit) && !empty($permissions->leaves_edit) && $permissions->leaves_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leaves_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'leaves_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leaves_delete" name="<?= $role['name'] ?>_leaves_delete" value="<?=(isset($permissions->leaves_delete) && !empty($permissions->leaves_delete))?$permissions->leaves_delete:0?>" <?=(isset($permissions->leaves_delete) && !empty($permissions->leaves_delete) && $permissions->leaves_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leaves_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'leaves_status ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leaves_status" name="<?= $role['name'] ?>_leaves_status" value="<?=(isset($permissions->leaves_status) && !empty($permissions->leaves_status))?$permissions->leaves_status:0?>" <?=(isset($permissions->leaves_status) && !empty($permissions->leaves_status) && $permissions->leaves_status == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leaves_status"><?=$this->lang->line('can_change_leaves_status')?$this->lang->line('can_change_leaves_status'):'Can change leaves status'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'leaves_view_all ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leaves_view_all" name="<?= $role['name'] ?>_leaves_view_all" value="<?=(isset($permissions->leaves_view_all) && !empty($permissions->leaves_view_all))?$permissions->leaves_view_all:0?>" <?=(isset($permissions->leaves_view_all) && !empty($permissions->leaves_view_all) && $permissions->leaves_view_all == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leaves_view_all"><?=$this->lang->line('view_all_leaves')?$this->lang->line('view_all_leaves'):'View All Leaves'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'leaves_view_selected ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leaves_view_selected" name="<?= $role['name'] ?>_leaves_view_selected" value="<?=(isset($permissions->leaves_view_selected) && !empty($permissions->leaves_view_selected))?$permissions->leaves_view_selected:0?>" <?=(isset($permissions->leaves_view_selected) && !empty($permissions->leaves_view_selected) && $permissions->leaves_view_selected == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leaves_view_selected"><?=$this->lang->line('view_selected_user_leaves')?$this->lang->line('view_selected_user_leaves'):'View Selected User Leaves'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                  </div> 
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'biometric_request') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('biometric_request')?$this->lang->line('biometric_request'):'Biometric Request'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'biometric_request_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_biometric_request_view" name="<?= $role['name'] ?>_biometric_request_view" value="<?=(isset($permissions->biometric_request_view) && !empty($permissions->biometric_request_view))?$permissions->biometric_request_view:0?>" <?=(isset($permissions->biometric_request_view) && !empty($permissions->biometric_request_view) && $permissions->biometric_request_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_biometric_request_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'biometric_request_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_biometric_request_create" name="<?= $role['name'] ?>_biometric_request_create" value="<?=(isset($permissions->biometric_request_create) && !empty($permissions->biometric_request_create))?$permissions->biometric_request_create:0?>" <?=(isset($permissions->biometric_request_create) && !empty($permissions->biometric_request_create) && $permissions->biometric_request_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_biometric_request_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'biometric_request_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_biometric_request_edit" name="<?= $role['name'] ?>_biometric_request_edit" value="<?=(isset($permissions->biometric_request_edit) && !empty($permissions->biometric_request_edit))?$permissions->biometric_request_edit:0?>" <?=(isset($permissions->biometric_request_edit) && !empty($permissions->biometric_request_edit) && $permissions->biometric_request_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_biometric_request_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'biometric_request_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_biometric_request_delete" name="<?= $role['name'] ?>_biometric_request_delete" value="<?=(isset($permissions->biometric_request_delete) && !empty($permissions->biometric_request_delete))?$permissions->biometric_request_delete:0?>" <?=(isset($permissions->biometric_request_delete) && !empty($permissions->biometric_request_delete) && $permissions->biometric_request_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_biometric_request_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'biometric_request_status ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_biometric_request_status" name="<?= $role['name'] ?>_biometric_request_status" value="<?=(isset($permissions->biometric_request_status) && !empty($permissions->biometric_request_status))?$permissions->biometric_request_status:0?>" <?=(isset($permissions->biometric_request_status) && !empty($permissions->biometric_request_status) && $permissions->biometric_request_status == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_biometric_request_status"><?=$this->lang->line('can_change_biometric_request_status')?$this->lang->line('can_change_biometric_request_status'):'Can change biometric request status'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'biometric_request_view_all ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_biometric_request_view_all" name="<?= $role['name'] ?>_biometric_request_view_all" value="<?=(isset($permissions->biometric_request_view_all) && !empty($permissions->biometric_request_create))?$permissions->biometric_request_view_all:0?>" <?=(isset($permissions->biometric_request_view_all) && !empty($permissions->biometric_request_view_all) && $permissions->biometric_request_view_all == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_biometric_request_view_all"><?=$this->lang->line('view_all_biometric_request')?$this->lang->line('view_all_biometric_request'):'View All Biomteric Request'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'project') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></label>

                                      <?php if (strpos($role['permissions'], 'project_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_project_view" name="<?= $role['name'] ?>_project_view" value="<?=(isset($permissions->project_view) && !empty($permissions->project_view))?$permissions->project_view:0?>" <?=(isset($permissions->project_view) && !empty($permissions->project_view) && $permissions->project_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_project_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'project_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_project_create" name="<?= $role['name'] ?>_project_create" value="<?=(isset($permissions->project_create) && !empty($permissions->project_create))?$permissions->project_create:0?>" <?=(isset($permissions->project_create) && !empty($permissions->project_create) && $permissions->project_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_project_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'project_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_project_edit" name="<?= $role['name'] ?>_project_edit" value="<?=(isset($permissions->project_edit) && !empty($permissions->project_edit))?$permissions->project_edit:0?>" <?=(isset($permissions->project_edit) && !empty($permissions->project_edit) && $permissions->project_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_project_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'project_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_project_delete" name="<?= $role['name'] ?>_project_delete" value="<?=(isset($permissions->project_delete) && !empty($permissions->project_delete))?$permissions->project_delete:0?>" <?=(isset($permissions->project_delete) && !empty($permissions->project_delete) && $permissions->project_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_project_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'project_budget ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_project_budget" name="<?= $role['name'] ?>_project_budget" value="<?=(isset($permissions->project_budget) && !empty($permissions->project_budget))?$permissions->project_budget:0?>" <?=(isset($permissions->project_budget) && !empty($permissions->project_budget) && $permissions->project_budget == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_project_budget"><?=$this->lang->line('show_project_budget')?$this->lang->line('show_project_budget'):'Show project budget'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'project_view_all ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_project_view_all" name="<?= $role['name'] ?>_project_view_all" value="<?=(isset($permissions->project_view_all) && !empty($permissions->project_view_all))?$permissions->project_view_all:0?>" <?=(isset($permissions->project_view_all) && !empty($permissions->project_view_all) && $permissions->project_view_all == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_project_view_all"><?=$this->lang->line('view_all_project')?$this->lang->line('view_all_project'):'View All Project'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'project_view_selected ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_project_view_selected" name="<?= $role['name'] ?>_project_view_selected" value="<?=(isset($permissions->project_view_selected) && !empty($permissions->project_view_selected))?$permissions->project_view_selected:0?>" <?=(isset($permissions->project_view_selected) && !empty($permissions->project_view_selected) && $permissions->project_view_selected == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_project_view_selected"><?=$this->lang->line('view_selected_user_project')?$this->lang->line('view_selected_user_project'):'View Selected User Project'?></label>
                                        </div>
                                      <?php endif; ?>

                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'task') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'task_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_task_view" name="<?= $role['name'] ?>_task_view" value="<?=(isset($permissions->task_view) && !empty($permissions->task_view))?$permissions->task_view:0?>" <?=(isset($permissions->task_view) && !empty($permissions->task_view) && $permissions->task_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_task_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'task_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_task_create" name="<?= $role['name'] ?>_task_create" value="<?=(isset($permissions->task_create) && !empty($permissions->task_create))?$permissions->task_create:0?>" <?=(isset($permissions->task_create) && !empty($permissions->task_create) && $permissions->task_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_task_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'task_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_task_edit" name="<?= $role['name'] ?>_task_edit" value="<?=(isset($permissions->task_edit) && !empty($permissions->task_edit))?$permissions->task_edit:0?>" <?=(isset($permissions->task_edit) && !empty($permissions->task_edit) && $permissions->task_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_task_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'task_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_task_delete" name="<?= $role['name'] ?>_task_delete" value="<?=(isset($permissions->task_delete) && !empty($permissions->task_delete))?$permissions->task_delete:0?>" <?=(isset($permissions->task_delete) && !empty($permissions->task_delete) && $permissions->task_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_task_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'task_status ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_task_status" name="<?= $role['name'] ?>_task_status" value="<?=(isset($permissions->task_status) && !empty($permissions->task_status))?$permissions->task_status:0?>" <?=(isset($permissions->task_status) && !empty($permissions->task_status) && $permissions->task_status == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_task_status"><?=$this->lang->line('can_change_task_status')?$this->lang->line('can_change_task_status'):'Can change task status'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'task_view_all ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_task_view_all" name="<?= $role['name'] ?>_task_view_all" value="<?=(isset($permissions->task_view_all) && !empty($permissions->task_view_all))?$permissions->task_view_all:0?>" <?=(isset($permissions->task_view_all) && !empty($permissions->task_view_all) && $permissions->task_view_all == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_task_view_all"><?=$this->lang->line('view_all_task')?$this->lang->line('view_all_task'):'View All Tasks'?></label>
                                        </div>
                                      <?php endif; ?>

                                  </div> 
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'gantt') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('gantt')?$this->lang->line('gantt'):'Gantt'?></label>

                                      <?php if (strpos($role['permissions'], 'gantt_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_gantt_view" name="<?= $role['name'] ?>_gantt_view" value="<?=(isset($permissions->gantt_view) && !empty($permissions->gantt_view))?$permissions->gantt_view:0?>" <?=(isset($permissions->gantt_view) && !empty($permissions->gantt_view) && $permissions->gantt_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_gantt_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'gantt_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_gantt_edit" name="<?= $role['name'] ?>_gantt_edit" value="<?=(isset($permissions->gantt_edit) && !empty($permissions->gantt_edit))?$permissions->gantt_edit:0?>" <?=(isset($permissions->gantt_edit) && !empty($permissions->gantt_edit) && $permissions->gantt_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_gantt_edit"><?=$this->lang->line('drag_date')?htmlspecialchars($this->lang->line('drag_date')):'Drag Date'?> / <?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'user') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?> 
                                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')?$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default'):"Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."?>"></i>
                                      </label>
                                      
                                      <?php if (strpos($role['permissions'], 'user_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_user_view" name="<?= $role['name'] ?>_user_view" value="<?=(isset($permissions->user_view) && !empty($permissions->user_view))?$permissions->user_view:0?>" <?=(isset($permissions->user_view) && !empty($permissions->user_view) && $permissions->user_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_user_view"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'user_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_user_create" name="<?= $role['name'] ?>_user_create" value="<?=(isset($permissions->user_create) && !empty($permissions->user_create))?$permissions->user_create:0?>" <?=(isset($permissions->user_create) && !empty($permissions->user_create) && $permissions->user_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_user_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'user_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_user_edit" name="<?= $role['name'] ?>_user_edit" value="<?=(isset($permissions->user_edit) && !empty($permissions->user_edit))?$permissions->user_edit:0?>" <?=(isset($permissions->user_edit) && !empty($permissions->user_edit) && $permissions->user_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_user_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'user_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_user_delete" name="<?= $role['name'] ?>_user_delete" value="<?=(isset($permissions->user_delete) && !empty($permissions->user_delete))?$permissions->user_delete:0?>" <?=(isset($permissions->user_delete) && !empty($permissions->user_delete) && $permissions->user_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_user_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'user_view_all ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_user_view_all" name="<?= $role['name'] ?>_user_view_all" value="<?=(isset($permissions->user_view_all) && !empty($permissions->user_view_all))?$permissions->user_view_all:0?>" <?=(isset($permissions->user_view_all) && !empty($permissions->user_view_all) && $permissions->user_view_all == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_user_view_all"><?=$this->lang->line('view_all_user')?$this->lang->line('view_all_user'):'View All user'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'user_view_selected ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_user_view_selected" name="<?= $role['name'] ?>_user_view_selected" value="<?=(isset($permissions->user_view_selected) && !empty($permissions->user_view_selected))?$permissions->user_view_selected:0?>" <?=(isset($permissions->user_view_selected) && !empty($permissions->user_view_selected) && $permissions->user_view_selected == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_user_view_selected"><?=$this->lang->line('view_selected_user')?$this->lang->line('view_selected_user'):'View Selected Users'?></label>
                                        </div>
                                      <?php endif; ?>

                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'reports') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('reports')?$this->lang->line('reports'):'Reports'?> </label>

                                      <?php if (strpos($role['permissions'], 'reports_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_reports_view" name="<?= $role['name'] ?>_reports_view" value="<?=(isset($permissions->reports_view) && !empty($permissions->reports_view))?$permissions->reports_view:0?>" <?=(isset($permissions->reports_view) && !empty($permissions->reports_view) && $permissions->reports_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_reports_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'device') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('device')?$this->lang->line('device'):'Device Configuration'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'device_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_device_view" name="<?= $role['name'] ?>_device_view" value="<?=(isset($permissions->device_view) && !empty($permissions->device_view))?$permissions->device_view:0?>" <?=(isset($permissions->device_view) && !empty($permissions->device_view) && $permissions->device_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_device_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'device_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_device_create" name="<?= $role['name'] ?>_device_create" value="<?=(isset($permissions->device_create) && !empty($permissions->device_create))?$permissions->device_create:0?>" <?=(isset($permissions->device_create) && !empty($permissions->device_create) && $permissions->device_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_device_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'device_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_device_edit" name="<?= $role['name'] ?>_device_edit" value="<?=(isset($permissions->device_edit) && !empty($permissions->device_edit))?$permissions->device_edit:0?>" <?=(isset($permissions->device_edit) && !empty($permissions->device_edit) && $permissions->device_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_device_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'device_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_device_delete" name="<?= $role['name'] ?>_device_delete" value="<?=(isset($permissions->device_delete) && !empty($permissions->device_delete))?$permissions->device_delete:0?>" <?=(isset($permissions->device_delete) && !empty($permissions->device_delete) && $permissions->device_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_device_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'departments') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('departments')?$this->lang->line('departments'):'Departments'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'departments_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_departments_view" name="<?= $role['name'] ?>_departments_view" value="<?=(isset($permissions->departments_view) && !empty($permissions->departments_view))?$permissions->departments_view:0?>" <?=(isset($permissions->departments_view) && !empty($permissions->departments_view) && $permissions->departments_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_departments_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'departments_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_departments_create" name="<?= $role['name'] ?>_departments_create" value="<?=(isset($permissions->departments_create) && !empty($permissions->departments_create))?$permissions->departments_create:0?>" <?=(isset($permissions->departments_create) && !empty($permissions->departments_create) && $permissions->departments_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_departments_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'departments_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_departments_edit" name="<?= $role['name'] ?>_departments_edit" value="<?=(isset($permissions->departments_edit) && !empty($permissions->departments_edit))?$permissions->departments_edit:0?>" <?=(isset($permissions->departments_edit) && !empty($permissions->departments_edit) && $permissions->departments_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_departments_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'departments_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_departments_delete" name="<?= $role['name'] ?>_departments_delete" value="<?=(isset($permissions->departments_delete) && !empty($permissions->departments_delete))?$permissions->departments_delete:0?>" <?=(isset($permissions->departments_delete) && !empty($permissions->departments_delete) && $permissions->departments_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_departments_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'plan_holiday') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('plan_holiday')?$this->lang->line('plan_holiday'):'Plan Holiday'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'plan_holiday_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_plan_holiday_view" name="<?= $role['name'] ?>_plan_holiday_view" value="<?=(isset($permissions->plan_holiday_view) && !empty($permissions->plan_holiday_view))?$permissions->plan_holiday_view:0?>" <?=(isset($permissions->plan_holiday_view) && !empty($permissions->plan_holiday_view) && $permissions->plan_holiday_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_plan_holiday_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'plan_holiday_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_plan_holiday_create" name="<?= $role['name'] ?>_plan_holiday_create" value="<?=(isset($permissions->plan_holiday_create) && !empty($permissions->plan_holiday_create))?$permissions->plan_holiday_create:0?>" <?=(isset($permissions->plan_holiday_create) && !empty($permissions->plan_holiday_create) && $permissions->plan_holiday_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_plan_holiday_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'plan_holiday_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_plan_holiday_edit" name="<?= $role['name'] ?>_plan_holiday_edit" value="<?=(isset($permissions->plan_holiday_edit) && !empty($permissions->plan_holiday_edit))?$permissions->plan_holiday_edit:0?>" <?=(isset($permissions->plan_holiday_edit) && !empty($permissions->plan_holiday_edit) && $permissions->plan_holiday_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_plan_holiday_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'plan_holiday_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_plan_holiday_delete" name="<?= $role['name'] ?>_plan_holiday_delete" value="<?=(isset($permissions->plan_holiday_delete) && !empty($permissions->plan_holiday_delete))?$permissions->plan_holiday_delete:0?>" <?=(isset($permissions->plan_holiday_delete) && !empty($permissions->plan_holiday_delete) && $permissions->plan_holiday_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_plan_holiday_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                              </div>

                              <div class="inner-content mt-2 col-md-6" data-target="<?= $role['name'] ?>-permissions"  style="display: none;">

                                <?php if (strpos($role['permissions'], 'shift') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('shift')?$this->lang->line('shift'):'Shift'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'shift_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_shift_view" name="<?= $role['name'] ?>_shift_view" value="<?=(isset($permissions->shift_view) && !empty($permissions->shift_view))?$permissions->shift_view:0?>" <?=(isset($permissions->shift_view) && !empty($permissions->shift_view) && $permissions->shift_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_shift_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'shift_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_shift_create" name="<?= $role['name'] ?>_shift_create" value="<?=(isset($permissions->shift_create) && !empty($permissions->shift_create))?$permissions->shift_create:0?>" <?=(isset($permissions->shift_create) && !empty($permissions->shift_create) && $permissions->shift_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_shift_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'shift_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_shift_edit" name="<?= $role['name'] ?>_shift_edit" value="<?=(isset($permissions->shift_edit) && !empty($permissions->shift_edit))?$permissions->shift_edit:0?>" <?=(isset($permissions->shift_edit) && !empty($permissions->shift_edit) && $permissions->shift_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_shift_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'shift_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_shift_delete" name="<?= $role['name'] ?>_shift_delete" value="<?=(isset($permissions->shift_delete) && !empty($permissions->shift_delete))?$permissions->shift_delete:0?>" <?=(isset($permissions->shift_delete) && !empty($permissions->shift_delete) && $permissions->shift_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_shift_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'leave_type') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('leave_type')?$this->lang->line('leave_type'):'Leave Type'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'leave_type_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leave_type_view" name="<?= $role['name'] ?>_leave_type_view" value="<?=(isset($permissions->leave_type_view) && !empty($permissions->leave_type_view))?$permissions->leave_type_view:0?>" <?=(isset($permissions->leave_type_view) && !empty($permissions->leave_type_view) && $permissions->leave_type_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leave_type_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'leave_type_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leave_type_create" name="<?= $role['name'] ?>_leave_type_create" value="<?=(isset($permissions->leave_type_create) && !empty($permissions->leave_type_create))?$permissions->leave_type_create:0?>" <?=(isset($permissions->leave_type_create) && !empty($permissions->leave_type_create) && $permissions->leave_type_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leave_type_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'leave_type_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leave_type_edit" name="<?= $role['name'] ?>_leave_type_edit" value="<?=(isset($permissions->leave_type_edit) && !empty($permissions->leave_type_edit))?$permissions->leave_type_edit:0?>" <?=(isset($permissions->leave_type_edit) && !empty($permissions->leave_type_edit) && $permissions->leave_type_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leave_type_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'leave_type_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_leave_type_delete" name="<?= $role['name'] ?>_leave_type_delete" value="<?=(isset($permissions->leave_type_delete) && !empty($permissions->leave_type_delete))?$permissions->leave_type_delete:0?>" <?=(isset($permissions->leave_type_delete) && !empty($permissions->leave_type_delete) && $permissions->leave_type_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_leave_type_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>
                                
                                <?php if (strpos($role['permissions'], 'time_schedule') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('time_schedule')?$this->lang->line('time_schedule'):'Time Schedule'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'time_schedule_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_time_schedule_view" name="<?= $role['name'] ?>_time_schedule_view" value="<?=(isset($permissions->time_schedule_view) && !empty($permissions->time_schedule_view))?$permissions->time_schedule_view:0?>" <?=(isset($permissions->time_schedule_view) && !empty($permissions->time_schedule_view) && $permissions->time_schedule_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_time_schedule_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'time_schedule_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_time_schedule_edit" name="<?= $role['name'] ?>_time_schedule_edit" value="<?=(isset($permissions->time_schedule_edit) && !empty($permissions->time_schedule_edit))?$permissions->time_schedule_edit:0?>" <?=(isset($permissions->time_schedule_edit) && !empty($permissions->time_schedule_edit) && $permissions->time_schedule_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_time_schedule_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'general') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('general_settings')?$this->lang->line('general_settings'):'General Settings'?> 
                                      </label>
                                      <?php if (strpos($role['permissions'], 'general_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_general_view" name="<?= $role['name'] ?>_general_view" value="<?=(isset($permissions->general_view) && !empty($permissions->general_view))?$permissions->general_view:0?>" <?=(isset($permissions->general_view) && !empty($permissions->general_view) && $permissions->general_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_general_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'company') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('company_settings')?$this->lang->line('company_settings'):'Company Settings'?> 
                                      </label>
                                      <?php if (strpos($role['permissions'], 'company_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_company_view" name="<?= $role['name'] ?>_company_view" value="<?=(isset($permissions->company_view) && !empty($permissions->company_view))?$permissions->company_view:0?>" <?=(isset($permissions->company_view) && !empty($permissions->company_view) && $permissions->company_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_company_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'support') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('support')?$this->lang->line('support'):'Support'?> </label>
                                      
                                      <?php if (strpos($role['permissions'], 'support_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_support_view" name="<?= $role['name'] ?>_support_view" value="<?=(isset($permissions->support_view) && !empty($permissions->support_view))?$permissions->support_view:0?>" <?=(isset($permissions->support_view) && !empty($permissions->support_view) && $permissions->support_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_support_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>
                                
                                <?php if (strpos($role['permissions'], 'meetings') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'meetings_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_meetings_view" name="<?= $role['name'] ?>_meetings_view" value="<?=(isset($permissions->meetings_view) && !empty($permissions->meetings_view))?$permissions->meetings_view:0?>" <?=(isset($permissions->meetings_view) && !empty($permissions->meetings_view) && $permissions->meetings_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_meetings_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'meetings_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_meetings_create" name="<?= $role['name'] ?>_meetings_create" value="<?=(isset($permissions->meetings_create) && !empty($permissions->meetings_create))?$permissions->meetings_create:0?>" <?=(isset($permissions->meetings_create) && !empty($permissions->meetings_create) && $permissions->meetings_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_meetings_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'meetings_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_meetings_edit" name="<?= $role['name'] ?>_meetings_edit" value="<?=(isset($permissions->meetings_edit) && !empty($permissions->meetings_edit))?$permissions->meetings_edit:0?>" <?=(isset($permissions->meetings_edit) && !empty($permissions->meetings_edit) && $permissions->meetings_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_meetings_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'meetings_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_meetings_delete" name="<?= $role['name'] ?>_meetings_delete" value="<?=(isset($permissions->meetings_delete) && !empty($permissions->meetings_delete))?$permissions->meetings_delete:0?>" <?=(isset($permissions->meetings_delete) && !empty($permissions->meetings_delete) && $permissions->meetings_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_meetings_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'lead') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('leads')?$this->lang->line('leads'):'Leads'?></label>
                                      
                                      <?php if (strpos($role['permissions'], 'lead_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_lead_view" name="<?= $role['name'] ?>_lead_view" value="<?=(isset($permissions->lead_view) && !empty($permissions->lead_view))?$permissions->lead_view:0?>" <?=(isset($permissions->lead_view) && !empty($permissions->lead_view) && $permissions->lead_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_lead_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'lead_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_lead_create" name="<?= $role['name'] ?>_lead_create" value="<?=(isset($permissions->lead_create) && !empty($permissions->lead_create))?$permissions->lead_create:0?>" <?=(isset($permissions->lead_create) && !empty($permissions->lead_create) && $permissions->lead_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_lead_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'lead_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_lead_edit" name="<?= $role['name'] ?>_lead_edit" value="<?=(isset($permissions->lead_edit) && !empty($permissions->lead_edit))?$permissions->lead_edit:0?>" <?=(isset($permissions->lead_edit) && !empty($permissions->lead_edit) && $permissions->lead_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_lead_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'lead_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_lead_delete" name="<?= $role['name'] ?>_lead_delete" value="<?=(isset($permissions->lead_delete) && !empty($permissions->lead_delete))?$permissions->lead_delete:0?>" <?=(isset($permissions->lead_delete) && !empty($permissions->lead_delete) && $permissions->lead_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_lead_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'calendar') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar'?> </label>

                                      <?php if (strpos($role['permissions'], 'calendar_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_calendar_view" name="<?= $role['name'] ?>_calendar_view" value="<?=(isset($permissions->calendar_view) && !empty($permissions->calendar_view))?$permissions->calendar_view:0?>" <?=(isset($permissions->calendar_view) && !empty($permissions->calendar_view) && $permissions->calendar_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_calendar_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>

                                <?php if (strpos($role['permissions'], 'todo') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('todo')?$this->lang->line('todo'):'ToDo'?> 
                                      </label>
                                      <?php if (strpos($role['permissions'], 'todo_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_todo_view" name="<?= $role['name'] ?>_todo_view" value="<?=(isset($permissions->todo_view) && !empty($permissions->todo_view))?$permissions->todo_view:0?>" <?=(isset($permissions->todo_view) && !empty($permissions->todo_view) && $permissions->todo_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_todo_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>
                                
                                <?php if (strpos($role['permissions'], 'notes') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('notes')?$this->lang->line('notes'):'Notes'?> 
                                      </label>
                                      <?php if (strpos($role['permissions'], 'notes_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_notes_view" name="<?= $role['name'] ?>_notes_view" value="<?=(isset($permissions->notes_view) && !empty($permissions->notes_view))?$permissions->notes_view:0?>" <?=(isset($permissions->notes_view) && !empty($permissions->notes_view) && $permissions->notes_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_notes_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>
                                
                                <?php if (strpos($role['permissions'], 'chat') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('chat')?$this->lang->line('chat'):'Chat'?> 
                                      </label>
                                      <?php if (strpos($role['permissions'], 'chat_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_chat_view" name="<?= $role['name'] ?>_chat_view" value="<?=(isset($permissions->chat_view) && !empty($permissions->chat_view))?$permissions->chat_view:0?>" <?=(isset($permissions->chat_view) && !empty($permissions->chat_view) && $permissions->chat_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_chat_view"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></label>
                                        </div>
                                      <?php endif; ?>
                                      <?php if (strpos($role['permissions'], 'chat_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_chat_delete" name="<?= $role['name'] ?>_chat_delete" value="<?=(isset($permissions->chat_delete) && !empty($permissions->chat_delete))?$permissions->chat_delete:0?>" <?=(isset($permissions->chat_delete) && !empty($permissions->chat_delete) && $permissions->chat_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_chat_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?>
                                  </div>
                                <?php endif; ?>
                                
                                <?php if (strpos($role['permissions'], 'client') !== false): ?>
                                  <div class="form-group col-md-12">
                                      <label class="d-block"><?=$this->lang->line('clients')?$this->lang->line('clients'):'Clients'?> 
                                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')?$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default'):"Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."?>"></i>
                                      </label>
                                      
                                      <?php if (strpos($role['permissions'], 'client_view ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_client_view" name="<?= $role['name'] ?>_client_view" value="<?=(isset($permissions->client_view) && !empty($permissions->client_view))?$permissions->client_view:0?>" <?=(isset($permissions->client_view) && !empty($permissions->client_view) && $permissions->client_view == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_client_view"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></label>
                                        </div>
                                      <?php endif; ?>

                                      <?php if (strpos($role['permissions'], 'client_create ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_client_create" name="<?= $role['name'] ?>_client_create" value="<?=(isset($permissions->client_create) && !empty($permissions->client_create))?$permissions->client_create:0?>" <?=(isset($permissions->client_create) && !empty($permissions->client_create) && $permissions->client_create == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_client_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <?php if (strpos($role['permissions'], 'client_edit ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_client_edit" name="<?= $role['name'] ?>_client_edit" value="<?=(isset($permissions->client_edit) && !empty($permissions->client_edit))?$permissions->client_edit:0?>" <?=(isset($permissions->client_edit) && !empty($permissions->client_edit) && $permissions->client_edit == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_client_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                                        </div>
                                      <?php endif; ?>
                                      
                                      <!-- <?php if (strpos($role['permissions'], 'client_delete ') !== false): ?>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" id="<?= $role['name'] ?>_client_delete" name="<?= $role['name'] ?>_client_delete" value="<?=(isset($permissions->client_delete) && !empty($permissions->client_delete))?$permissions->client_delete:0?>" <?=(isset($permissions->client_delete) && !empty($permissions->client_delete) && $permissions->client_delete == 1)?'checked':''?>>
                                          <label class="form-check-label" for="<?= $role['name'] ?>_client_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                                        </div>
                                      <?php endif; ?> -->

                                  </div>
                                <?php endif; ?>
                              </div>
                            </div>
                          <?php endif; ?>

                        </div>

                      
                      <?php endforeach; ?>
                    </div>

                      <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)){ ?>
                        <div class="card-footer bg-whitesmoke text-md-right">
                          <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
                        </div>
                      <?php } ?>
                      <div class="result"></div>
                    </form>
