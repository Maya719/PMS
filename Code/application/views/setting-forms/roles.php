<?php $this->load->view('includes/head'); ?>
            
<div class="card-body row">
    <div class="card-header">
        <h4 class="card-title"><?=$this->lang->line('roles')?$this->lang->line('roles'):'Roles'?></h4>
        <a href="#" id="modal-add-roles" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create_role')?$this->lang->line('create_role'):'Create Role'?></a>
    </div>

    <div class="col-md-12" >
        <table class='table-striped' id='role_list'
            data-toggle="table"
            data-url="<?=base_url('settings/get_roles')?>"
            data-click-to-select="true"
            data-side-pagination="server"
            data-pagination="true"
            data-height="700"
            data-page-list="[5, 10, 20, 50, 100, 200]"
            data-search="false" data-show-columns="false"
            data-show-refresh="false" data-trim-on-search="false"
            data-sort-name="id" data-sort-order="DESC"
            data-mobile-responsive="true"
            data-toolbar="" data-show-export="false"
            data-maintain-selected="true"
            data-export-types='["txt","excel"]'
            data-export-options='{
            "fileName": "shift-list",
            "ignoreColumn": ["state"] 
            }'
            data-query-params="queryParams">
            <thead>
            <tr>
                <th data-field="sr_no" data-sortable="false"><?=$this->lang->line('sr_no')?$this->lang->line('sr_no'):'S.no'?></th>
                <th data-field="id" data-sortable="false" data-visible="false" class="left-pad"><?=$this->lang->line('id')?$this->lang->line('id'):'ID'?></th>
                <th data-field="name" data-sortable="false" class="left-pad"><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?></th>
                <th data-field="description" data-sortable="false" class="left-pad"><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?></th>
                <th data-field="descriptive_name" data-sortable="false" class="left-pad"><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?></th>
                <th data-field="permissions" data-sortable="false" data-formatter="permissionsFormatter">
                    <?=$this->lang->line('show_permissions')?htmlspecialchars($this->lang->line('show_permissions')):'Show Permissions'?>
                </th>
                <th data-field="users" data-sortable="false" class="left-pad" data-formatter="teamMembersFormatter">
                    <?=$this->lang->line('aasigned_users')?htmlspecialchars($this->lang->line('aasigned_users')):'Assigned Users'?>
                </th>
                <th data-field="action" data-sortable="false" class="left-pad"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<form action="<?=base_url('settings/roles_create')?>" method="POST" class="modal-part" id="modal-add-roles-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
    
    <div class="form-group">
        <label><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?><span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required="">
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
        <input type="text" name="description" class="form-control" required="">
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
        <input type="text" name="descriptive_name" class="form-control" required="">
    </div>
    
    <div class="form-group">
        <label><?=$this->lang->line('show_permissions')?$this->lang->line('show_permissions'):'Show Permissions'?><span class="text-danger">*</span></label>
        <input type="checkbox" id="selectAllPermissions_create"> Select All
        <select name="permissions[]" id="permissions_create" class="form-control select2" multiple="">
            <?php foreach($permissions_list as $permission){ ?>
            <option value="<?= $permission['id'] ?>"><?= $permission['description'] ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('assigned_users')?$this->lang->line('assigned_users'):'Assigned Users'?><span class="text-danger">*</span></label>
        <input type="checkbox" id="selectAllUsers_create"> Select All
        <select name="users[]" id="users_create" class="form-control select2" multiple="">
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
        </select>
    </div>

</form>

<form action="<?=base_url('settings/roles_edit')?>" method="POST" class="modal-part" id="modal-edit-roles-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">

    <input type="hidden" name="update_id" id="update_id">

    <div class="form-group">
        <label><?=$this->lang->line('type')?$this->lang->line('type'):'Type'?><span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control" required="">
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('name')?$this->lang->line('name'):'Name'?><span class="text-danger">*</span></label>
        <input type="text" name="description" id="description" class="form-control" required="">
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('description')?$this->lang->line('description'):'Description'?><span class="text-danger">*</span></label>
        <input type="text" name="descriptive_name" id="descriptive_name" class="form-control" required="">
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('show_permissions')?$this->lang->line('show_permissions'):'Show Permissions'?><span class="text-danger">*</span></label>
        <input type="checkbox" id="selectAllPermissions"> Select All
        <select name="permissions[]" id="permissions" class="form-control select2" multiple="">
            <?php foreach($permissions_list as $permission){ ?>
            <option value="<?= $permission['id'] ?>"><?= $permission['description'] ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label><?=$this->lang->line('assigned_users')?$this->lang->line('assigned_users'):'Assigned Users'?><span class="text-danger">*</span></label>
        <input type="checkbox" id="selectAllUsers"> Select All
        <select name="users[]" id="users" class="form-control select2" multiple="">
        <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
        <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
        <?php } } ?>
        </select>
    </div>


</form>

<div id="modal-edit-roles"></div>

<script>
  function queryParams(p){
    return {
      limit:p.limit,
      sort:p.sort,
      order:p.order,
      offset:p.offset,
      search:p.search
    };
  }
</script>

<style>
.left-pad{
  padding-left:0.5em !important;
  padding-right:0.5em !important;
  padding-bottom:0.5em !important;
  padding-top:0.5em !important;
} 
.create {
    position: absolute;
    top: 0;
    left: 0;
    margin-top: 20px;
    margin-left: 15px;
    z-index: 100; /* Set a higher value for z-index */
}
</style>




