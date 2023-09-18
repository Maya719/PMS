<form action="<?=base_url('settings/save-seo-setting')?>" method="POST" id="language-form">

    <div class="card-header">
      <h4><?=$this->lang->line('seo')?$this->lang->line('seo'):'SEO'?></h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('meta_title')?htmlspecialchars($this->lang->line('meta_title')):'Meta Title'?></label>
        <input type="text" name="meta_title" value="<?=(isset($meta_title) && !empty($meta_title))?htmlspecialchars($meta_title):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('meta_description')?htmlspecialchars($this->lang->line('meta_description')):'Meta Description'?></label>
        <input type="text" name="meta_description" value="<?=(isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('meta_keywords')?htmlspecialchars($this->lang->line('meta_keywords')):'Meta Keywords'?></label>
        <input type="text" name="meta_keywords" value="<?=(isset($meta_keywords) && !empty($meta_keywords))?htmlspecialchars($meta_keywords):''?>" class="form-control">
      </div>
    </div>

    <div class="card-footer bg-whitesmoke text-md-right">
      <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
    </div>
    <div class="result"></div>
</form>