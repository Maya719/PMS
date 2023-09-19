<?php $this->load->view('includes/head'); ?>
<form action="<?=base_url('settings/save_grace_minutes_setting')?>" method="POST" id="setting-form">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="card-title" style="white-space: nowrap;"><?=$this->lang->line('grace_minutes')?$this->lang->line('grace_minutes'):'Grace Minutes'?></h4>
            </div>
            <div class="col-md-6 text-md-right">
                <div class="form-group form-check-inline">
                    <input type="checkbox" class="form-check-input" name="enableGraceMinutes" id="enableGraceMinutes">
                    <label class="form-check-label" for="enableGraceMinutes">Enable</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body row">
        <div class="form-group col-md-6">
            <label><?=$this->lang->line('days_counter')?$this->lang->line('days_counter'):'Days Counter'?><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('m')?$this->lang->line('m'):'The number of late days of which Half Day will be marked.'?>"></i></label>
            <input type="number" name="days_counter" id="days_counter" class="form-control " required="">
        </div>
        <div class="form-group col-md-6">
            <label><?=$this->lang->line('grace_minutes')?$this->lang->line('grace_minutes'):'Grace Minutes'?><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('m')?$this->lang->line('m'):'The allowed grace minutes for being late.'?>"></i></label>
            <input type="number" name="grace_minutes" id="grace_minutes" class="form-control " required="">
        </div>
    </div>

    <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3) || permissions('time_schedule_edit')){ ?>
        <div class="card-footer bg-whitesmoke text-md-right">
            <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
        </div>
    <?php } ?>
    <div class="result"></div>
</form>




