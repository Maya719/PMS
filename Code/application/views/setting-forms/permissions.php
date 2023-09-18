                      <form action="<?=base_url('settings/save-permission-setting')?>" method="POST" id="setting-form">
                      <div class="card-body row">
                        <div class="alert alert-danger col-md-12 center">
                          <b><?=$this->lang->line('note')?$this->lang->line('note'):'Note'?></b> <?=$this->lang->line('admin_always_have_all_the_permission_here_you_can_set_permissions_for_users_and_clients')?$this->lang->line('admin_always_have_all_the_permission_here_you_can_set_permissions_for_users_and_clients'):"Admin always have all the permission. Here you can set permissions for users and clients."?>
                        </div>
                        <div class="col-md-12">
                          <div class="card-header">
                            <h4 class="card-title"><?=$this->lang->line('general_permissions')?$this->lang->line('general_permissions'):'General permissions'?></h4>
                          </div>
                          <div class="form-group mt-2 col-md-12">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="team_members_and_client_can_chat" name="team_members_and_client_can_chat" value="<?=(isset($permissions->team_members_and_client_can_chat) && !empty($permissions->team_members_and_client_can_chat))?$permissions->team_members_and_client_can_chat:0?>" <?=(isset($permissions->team_members_and_client_can_chat) && !empty($permissions->team_members_and_client_can_chat) && $permissions->team_members_and_client_can_chat == 1)?'checked':''?>>
                                <label class="form-check-label" for="team_members_and_client_can_chat"><?=$this->lang->line('team_embers_and_client_can_chat')?$this->lang->line('team_embers_and_client_can_chat'):'Team Members and Client can chat?'?></label>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="card-header">
                            <h4 class="card-title"><?=$this->lang->line('user_permissions')?$this->lang->line('user_permissions'):'User Permissions'?></h4>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></label>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_view" name="project_view" value="<?=(isset($permissions->project_view) && !empty($permissions->project_view))?$permissions->project_view:0?>" <?=(isset($permissions->project_view) && !empty($permissions->project_view) && $permissions->project_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_create" name="project_create" value="<?=(isset($permissions->project_create) && !empty($permissions->project_create))?$permissions->project_create:0?>" <?=(isset($permissions->project_create) && !empty($permissions->project_create) && $permissions->project_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_edit" name="project_edit" value="<?=(isset($permissions->project_edit) && !empty($permissions->project_edit))?$permissions->project_edit:0?>" <?=(isset($permissions->project_edit) && !empty($permissions->project_edit) && $permissions->project_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_delete" name="project_delete" value="<?=(isset($permissions->project_delete) && !empty($permissions->project_delete))?$permissions->project_delete:0?>" <?=(isset($permissions->project_delete) && !empty($permissions->project_delete) && $permissions->project_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_budget" name="project_budget" value="<?=(isset($permissions->project_budget) && !empty($permissions->project_budget))?$permissions->project_budget:0?>" <?=(isset($permissions->project_budget) && !empty($permissions->project_budget) && $permissions->project_budget == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_budget"><?=$this->lang->line('show_project_budget')?$this->lang->line('show_project_budget'):'Show project budget'?></label>
                              </div>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_view" name="task_view" value="<?=(isset($permissions->task_view) && !empty($permissions->task_view))?$permissions->task_view:0?>" <?=(isset($permissions->task_view) && !empty($permissions->task_view) && $permissions->task_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_create" name="task_create" value="<?=(isset($permissions->task_create) && !empty($permissions->task_create))?$permissions->task_create:0?>" <?=(isset($permissions->task_create) && !empty($permissions->task_create) && $permissions->task_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_edit" name="task_edit" value="<?=(isset($permissions->task_edit) && !empty($permissions->task_edit))?$permissions->task_edit:0?>" <?=(isset($permissions->task_edit) && !empty($permissions->task_edit) && $permissions->task_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_delete" name="task_delete" value="<?=(isset($permissions->task_delete) && !empty($permissions->task_delete))?$permissions->task_delete:0?>" <?=(isset($permissions->task_delete) && !empty($permissions->task_delete) && $permissions->task_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_status" name="task_status" value="<?=(isset($permissions->task_status) && !empty($permissions->task_status))?$permissions->task_status:0?>" <?=(isset($permissions->task_status) && !empty($permissions->task_status) && $permissions->task_status == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_status"><?=$this->lang->line('can_change_task_status')?$this->lang->line('can_change_task_status'):'Can change task status'?></label>
                              </div>
                          </div>
                        
                        
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?></label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="meetings_view" name="meetings_view" value="<?=(isset($permissions->meetings_view) && !empty($permissions->meetings_view))?$permissions->meetings_view:0?>" <?=(isset($permissions->meetings_view) && !empty($permissions->meetings_view) && $permissions->meetings_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="meetings_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="meetings_create" name="meetings_create" value="<?=(isset($permissions->meetings_create) && !empty($permissions->meetings_create))?$permissions->meetings_create:0?>" <?=(isset($permissions->meetings_create) && !empty($permissions->meetings_create) && $permissions->meetings_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="meetings_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="meetings_edit" name="meetings_edit" value="<?=(isset($permissions->meetings_edit) && !empty($permissions->meetings_edit))?$permissions->meetings_edit:0?>" <?=(isset($permissions->meetings_edit) && !empty($permissions->meetings_edit) && $permissions->meetings_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="meetings_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="meetings_delete" name="meetings_delete" value="<?=(isset($permissions->meetings_delete) && !empty($permissions->meetings_delete))?$permissions->meetings_delete:0?>" <?=(isset($permissions->meetings_delete) && !empty($permissions->meetings_delete) && $permissions->meetings_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="meetings_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('leads')?$this->lang->line('leads'):'Leads'?></label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="lead_view" name="lead_view" value="<?=(isset($permissions->lead_view) && !empty($permissions->lead_view))?$permissions->lead_view:0?>" <?=(isset($permissions->lead_view) && !empty($permissions->lead_view) && $permissions->lead_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="lead_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="lead_create" name="lead_create" value="<?=(isset($permissions->lead_create) && !empty($permissions->lead_create))?$permissions->lead_create:0?>" <?=(isset($permissions->lead_create) && !empty($permissions->lead_create) && $permissions->lead_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="lead_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="lead_edit" name="lead_edit" value="<?=(isset($permissions->lead_edit) && !empty($permissions->lead_edit))?$permissions->lead_edit:0?>" <?=(isset($permissions->lead_edit) && !empty($permissions->lead_edit) && $permissions->lead_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="lead_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="lead_delete" name="lead_delete" value="<?=(isset($permissions->lead_delete) && !empty($permissions->lead_delete))?$permissions->lead_delete:0?>" <?=(isset($permissions->lead_delete) && !empty($permissions->lead_delete) && $permissions->lead_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="lead_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('gantt')?$this->lang->line('gantt'):'Gantt'?></label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="gantt_view" name="gantt_view" value="<?=(isset($permissions->gantt_view) && !empty($permissions->gantt_view))?$permissions->gantt_view:0?>" <?=(isset($permissions->gantt_view) && !empty($permissions->gantt_view) && $permissions->gantt_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="gantt_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="gantt_edit" name="gantt_edit" value="<?=(isset($permissions->gantt_edit) && !empty($permissions->gantt_edit))?$permissions->gantt_edit:0?>" <?=(isset($permissions->gantt_edit) && !empty($permissions->gantt_edit) && $permissions->gantt_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="gantt_edit"><?=$this->lang->line('drag_date')?htmlspecialchars($this->lang->line('drag_date')):'Drag Date'?> / <?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                          </div>
                        
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="calendar_view" name="calendar_view" value="<?=(isset($permissions->calendar_view) && !empty($permissions->calendar_view))?$permissions->calendar_view:0?>" <?=(isset($permissions->calendar_view) && !empty($permissions->calendar_view) && $permissions->calendar_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="calendar_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('todo')?$this->lang->line('todo'):'ToDo'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="todo_view" name="todo_view" value="<?=(isset($permissions->todo_view) && !empty($permissions->todo_view))?$permissions->todo_view:0?>" <?=(isset($permissions->todo_view) && !empty($permissions->todo_view) && $permissions->todo_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="todo_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('notes')?$this->lang->line('notes'):'Notes'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="notes_view" name="notes_view" value="<?=(isset($permissions->notes_view) && !empty($permissions->notes_view))?$permissions->notes_view:0?>" <?=(isset($permissions->notes_view) && !empty($permissions->notes_view) && $permissions->notes_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="notes_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                          </div>

                          
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('chat')?$this->lang->line('chat'):'Chat'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chat_view" name="chat_view" value="<?=(isset($permissions->chat_view) && !empty($permissions->chat_view))?$permissions->chat_view:0?>" <?=(isset($permissions->chat_view) && !empty($permissions->chat_view) && $permissions->chat_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="chat_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chat_delete" name="chat_delete" value="<?=(isset($permissions->chat_delete) && !empty($permissions->chat_delete))?$permissions->chat_delete:0?>" <?=(isset($permissions->chat_delete) && !empty($permissions->chat_delete) && $permissions->chat_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="chat_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?> 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')?$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default'):"Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."?>"></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="user_view" name="user_view" value="<?=(isset($permissions->user_view) && !empty($permissions->user_view))?$permissions->user_view:0?>" <?=(isset($permissions->user_view) && !empty($permissions->user_view) && $permissions->user_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="user_view"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('clients')?$this->lang->line('clients'):'Clients'?> 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')?$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default'):"Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."?>"></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_view" name="client_view" value="<?=(isset($permissions->client_view) && !empty($permissions->client_view))?$permissions->client_view:0?>" <?=(isset($permissions->client_view) && !empty($permissions->client_view) && $permissions->client_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_view"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></label>
                              </div>
                          </div>

                        </div>


                        
                        <div class="col-md-6">
                          <div class="card-header">
                            <h4 class="card-title"><?=$this->lang->line('client_permissions')?$this->lang->line('client_permissions'):'Client Permissions'?></h4>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('projects')?$this->lang->line('projects'):'Projects'?></label>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_view" name="client_project_view" value="<?=(isset($clients_permissions->project_view) && !empty($clients_permissions->project_view))?$clients_permissions->project_view:0?>" <?=(isset($clients_permissions->project_view) && !empty($clients_permissions->project_view) && $clients_permissions->project_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_create" name="client_project_create" value="<?=(isset($clients_permissions->project_create) && !empty($clients_permissions->project_create))?$clients_permissions->project_create:0?>" <?=(isset($clients_permissions->project_create) && !empty($clients_permissions->project_create) && $clients_permissions->project_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_edit" name="client_project_edit" value="<?=(isset($clients_permissions->project_edit) && !empty($clients_permissions->project_edit))?$clients_permissions->project_edit:0?>" <?=(isset($clients_permissions->project_edit) && !empty($clients_permissions->project_edit) && $clients_permissions->project_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_delete" name="client_project_delete" value="<?=(isset($clients_permissions->project_delete) && !empty($clients_permissions->project_delete))?$clients_permissions->project_delete:0?>" <?=(isset($clients_permissions->project_delete) && !empty($clients_permissions->project_delete) && $clients_permissions->project_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_budget" name="client_project_budget" value="<?=(isset($clients_permissions->project_budget) && !empty($clients_permissions->project_budget))?$clients_permissions->project_budget:0?>" <?=(isset($clients_permissions->project_budget) && !empty($clients_permissions->project_budget) && $clients_permissions->project_budget == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_budget"><?=$this->lang->line('show_project_budget')?$this->lang->line('show_project_budget'):'Show project budget'?></label>
                              </div>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('tasks')?$this->lang->line('tasks'):'Tasks'?></label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_view" name="client_task_view" value="<?=(isset($clients_permissions->task_view) && !empty($clients_permissions->task_view))?$clients_permissions->task_view:0?>" <?=(isset($clients_permissions->task_view) && !empty($clients_permissions->task_view) && $clients_permissions->task_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_create" name="client_task_create" value="<?=(isset($clients_permissions->task_create) && !empty($clients_permissions->task_create))?$clients_permissions->task_create:0?>" <?=(isset($clients_permissions->task_create) && !empty($clients_permissions->task_create) && $clients_permissions->task_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_edit" name="client_task_edit" value="<?=(isset($clients_permissions->task_edit) && !empty($clients_permissions->task_edit))?$clients_permissions->task_edit:0?>" <?=(isset($clients_permissions->task_edit) && !empty($clients_permissions->task_edit) && $clients_permissions->task_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_delete" name="client_task_delete" value="<?=(isset($clients_permissions->task_delete) && !empty($clients_permissions->task_delete))?$clients_permissions->task_delete:0?>" <?=(isset($clients_permissions->task_delete) && !empty($clients_permissions->task_delete) && $clients_permissions->task_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_status" name="client_task_status" value="<?=(isset($clients_permissions->task_status) && !empty($clients_permissions->task_status))?$clients_permissions->task_status:0?>" <?=(isset($clients_permissions->task_status) && !empty($clients_permissions->task_status) && $clients_permissions->task_status == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_status"><?=$this->lang->line('can_change_task_status')?$this->lang->line('can_change_task_status'):'Can change task status'?></label>
                              </div>

                          </div>
                        
                        
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('video_meetings')?$this->lang->line('video_meetings'):'Video Meetings'?></label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_meetings_view" name="client_meetings_view" value="<?=(isset($clients_permissions->meetings_view) && !empty($clients_permissions->meetings_view))?$clients_permissions->meetings_view:0?>" <?=(isset($clients_permissions->meetings_view) && !empty($clients_permissions->meetings_view) && $clients_permissions->meetings_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_meetings_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_meetings_create" name="client_meetings_create" value="<?=(isset($clients_permissions->meetings_create) && !empty($clients_permissions->meetings_create))?$clients_permissions->meetings_create:0?>" <?=(isset($clients_permissions->meetings_create) && !empty($clients_permissions->meetings_create) && $clients_permissions->meetings_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_meetings_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_meetings_edit" name="client_meetings_edit" value="<?=(isset($clients_permissions->meetings_edit) && !empty($clients_permissions->meetings_edit))?$clients_permissions->meetings_edit:0?>" <?=(isset($clients_permissions->meetings_edit) && !empty($clients_permissions->meetings_edit) && $clients_permissions->meetings_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_meetings_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_meetings_delete" name="client_meetings_delete" value="<?=(isset($clients_permissions->meetings_delete) && !empty($clients_permissions->meetings_delete))?$clients_permissions->meetings_delete:0?>" <?=(isset($clients_permissions->meetings_delete) && !empty($clients_permissions->meetings_delete) && $clients_permissions->meetings_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_meetings_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('leads')?$this->lang->line('leads'):'Leads'?></label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_lead_view" name="client_lead_view" value="<?=(isset($clients_permissions->lead_view) && !empty($clients_permissions->lead_view))?$clients_permissions->lead_view:0?>" <?=(isset($clients_permissions->lead_view) && !empty($clients_permissions->lead_view) && $clients_permissions->lead_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_lead_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_lead_create" name="client_lead_create" value="<?=(isset($clients_permissions->lead_create) && !empty($clients_permissions->lead_create))?$clients_permissions->lead_create:0?>" <?=(isset($clients_permissions->lead_create) && !empty($clients_permissions->lead_create) && $clients_permissions->lead_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_lead_create"><?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_lead_edit" name="client_lead_edit" value="<?=(isset($clients_permissions->lead_edit) && !empty($clients_permissions->lead_edit))?$clients_permissions->lead_edit:0?>" <?=(isset($clients_permissions->lead_edit) && !empty($clients_permissions->lead_edit) && $clients_permissions->lead_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_lead_edit"><?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_lead_delete" name="client_lead_delete" value="<?=(isset($clients_permissions->lead_delete) && !empty($clients_permissions->lead_delete))?$clients_permissions->lead_delete:0?>" <?=(isset($clients_permissions->lead_delete) && !empty($clients_permissions->lead_delete) && $clients_permissions->lead_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_lead_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('gantt')?$this->lang->line('gantt'):'Gantt'?></label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_gantt_view" name="client_gantt_view" value="<?=(isset($clients_permissions->gantt_view) && !empty($clients_permissions->gantt_view))?$clients_permissions->gantt_view:0?>" <?=(isset($clients_permissions->gantt_view) && !empty($clients_permissions->gantt_view) && $clients_permissions->gantt_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_gantt_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_gantt_edit" name="client_gantt_edit" value="<?=(isset($clients_permissions->gantt_edit) && !empty($clients_permissions->gantt_edit))?$clients_permissions->gantt_edit:0?>" <?=(isset($clients_permissions->gantt_edit) && !empty($clients_permissions->gantt_edit) && $clients_permissions->gantt_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_gantt_edit"><?=$this->lang->line('drag_date')?htmlspecialchars($this->lang->line('drag_date')):'Drag Date'?> / <?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?></label>
                              </div>
                          </div>
                        
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('calendar')?$this->lang->line('calendar'):'Calendar'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_calendar_view" name="client_calendar_view" value="<?=(isset($clients_permissions->calendar_view) && !empty($clients_permissions->calendar_view))?$clients_permissions->calendar_view:0?>" <?=(isset($clients_permissions->calendar_view) && !empty($clients_permissions->calendar_view) && $clients_permissions->calendar_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_calendar_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('todo')?$this->lang->line('todo'):'ToDo'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_todo_view" name="client_todo_view" value="<?=(isset($clients_permissions->todo_view) && !empty($clients_permissions->todo_view))?$clients_permissions->todo_view:0?>" <?=(isset($clients_permissions->todo_view) && !empty($clients_permissions->todo_view) && $clients_permissions->todo_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_todo_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('notes')?$this->lang->line('notes'):'Notes'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_notes_view" name="client_notes_view" value="<?=(isset($clients_permissions->notes_view) && !empty($clients_permissions->notes_view))?$clients_permissions->notes_view:0?>" <?=(isset($clients_permissions->notes_view) && !empty($clients_permissions->notes_view) && $clients_permissions->notes_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_notes_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                          </div>

                          
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('chat')?$this->lang->line('chat'):'Chat'?> 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_chat_view" name="client_chat_view" value="<?=(isset($clients_permissions->chat_view) && !empty($clients_permissions->chat_view))?$clients_permissions->chat_view:0?>" <?=(isset($clients_permissions->chat_view) && !empty($clients_permissions->chat_view) && $clients_permissions->chat_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_chat_view"><?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?></label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_chat_delete" name="client_chat_delete" value="<?=(isset($clients_permissions->chat_delete) && !empty($clients_permissions->chat_delete))?$clients_permissions->chat_delete:0?>" <?=(isset($clients_permissions->chat_delete) && !empty($clients_permissions->chat_delete) && $clients_permissions->chat_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_chat_delete"><?=$this->lang->line('delete')?$this->lang->line('delete'):'Delete'?></label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('team_members')?$this->lang->line('team_members'):'Team Members'?> 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')?$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default'):"Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."?>"></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_user_view" name="client_user_view" value="<?=(isset($clients_permissions->user_view) && !empty($clients_permissions->user_view))?$clients_permissions->user_view:0?>" <?=(isset($clients_permissions->user_view) && !empty($clients_permissions->user_view) && $clients_permissions->user_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_user_view"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block"><?=$this->lang->line('clients')?$this->lang->line('clients'):'Clients'?> 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default')?$this->lang->line('only_admin_have_permission_to_add_edit_and_delete_users_you_can_make_any_user_as_admin_they_will_get_all_this_permissions_by_default'):"Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."?>"></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_client_view" name="client_client_view" value="<?=(isset($clients_permissions->client_view) && !empty($clients_permissions->client_view))?$clients_permissions->client_view:0?>" <?=(isset($clients_permissions->client_view) && !empty($clients_permissions->client_view) && $clients_permissions->client_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_client_view"><?=$this->lang->line('view')?$this->lang->line('view'):'View'?></label>
                              </div>
                          </div>
                        </div>

                      </div>
                      <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)){ ?>
                        <div class="card-footer bg-whitesmoke text-md-right">
                          <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
                        </div>
                      <?php } ?>
                      <div class="result"></div>
                    </form>