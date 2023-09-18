<form action="<?=base_url('settings/save-payment-setting')?>" method="POST" id="setting-form">

    <div class="card-header">
      <h4><?=$this->lang->line('paypal')?$this->lang->line('paypal'):'Paypal'?></h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('paypal_client_id')?$this->lang->line('paypal_client_id'):'Paypal Client ID'?></label>
        <input type="text" name="paypal_client_id" value="<?=(isset($paypal_client_id) && !empty($paypal_client_id))?htmlspecialchars($paypal_client_id):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('paypal_secret')?htmlspecialchars($this->lang->line('paypal_secret')):'Paypal Secret'?></label>
        <input type="text" name="paypal_secret" value="<?=(isset($paypal_secret) && !empty($paypal_secret))?htmlspecialchars($paypal_secret):''?>" class="form-control">
      </div>
    </div>

    <div class="card-header">
      <h4><?=$this->lang->line('stripe')?$this->lang->line('stripe'):'Stripe'?></h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('publishable_key')?$this->lang->line('publishable_key'):'Publishable Key'?></label>
        <input type="text" name="stripe_publishable_key" value="<?=(isset($stripe_publishable_key) && !empty($stripe_publishable_key))?htmlspecialchars($stripe_publishable_key):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('secret_key')?$this->lang->line('secret_key'):'Secret Key'?></label>
        <input type="text" name="stripe_secret_key" value="<?=(isset($stripe_secret_key) && !empty($stripe_secret_key))?htmlspecialchars($stripe_secret_key):''?>" class="form-control">
      </div>
    </div>


    <div class="card-header">
      <h4><?=$this->lang->line('razorpay')?$this->lang->line('razorpay'):'Razorpay'?></h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('key_id')?$this->lang->line('key_id'):'Key ID'?></label>
        <input type="text" name="razorpay_key_id" value="<?=(isset($razorpay_key_id) && !empty($razorpay_key_id))?htmlspecialchars($razorpay_key_id):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('key_secret')?htmlspecialchars($this->lang->line('key_secret')):'Key Secret'?></label>
        <input type="text" name="razorpay_key_secret" value="<?=(isset($razorpay_key_secret) && !empty($razorpay_key_secret))?htmlspecialchars($razorpay_key_secret):''?>" class="form-control">
      </div>
    </div>

    <div class="card-header">
      <h4><?=$this->lang->line('paystack')?$this->lang->line('paystack'):'Paystack'?></h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('paystack_public_key')?$this->lang->line('paystack_public_key'):'Paystack Public Key'?></label>
        <input type="text" name="paystack_public_key" value="<?=(isset($paystack_public_key) && !empty($paystack_public_key))?htmlspecialchars($paystack_public_key):''?>" class="form-control">
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('paystack_secret_key')?htmlspecialchars($this->lang->line('paystack_secret_key')):'Paystack Secret Key'?></label>
        <input type="text" name="paystack_secret_key" value="<?=(isset($paystack_secret_key) && !empty($paystack_secret_key))?htmlspecialchars($paystack_secret_key):''?>" class="form-control">
      </div>
    </div>

    <div class="card-header">
      <h4><?=$this->lang->line('offline_bank_transfer')?$this->lang->line('offline_bank_transfer'):'Offline / Bank Transfer'?> / <?=$this->lang->line('custom_payment')?htmlspecialchars($this->lang->line('custom_payment')):'Custom Payment'?></h4>
    </div>
    <div class="card-body row">
      <div class="form-group col-md-12">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="offline_bank_transfer" name="offline_bank_transfer" value="<?=(isset($offline_bank_transfer) && !empty($offline_bank_transfer))?$offline_bank_transfer:0?>" <?=(isset($offline_bank_transfer) && !empty($offline_bank_transfer) && $offline_bank_transfer == 1)?'checked':''?>>
          <label class="d-block form-check-label" for="offline_bank_transfer"><?=$this->lang->line('active')?$this->lang->line('active'):'Active'?> <?=$this->lang->line('offline_bank_transfer')?$this->lang->line('offline_bank_transfer'):'Offline / Bank Transfer'?> / <?=$this->lang->line('custom_payment')?htmlspecialchars($this->lang->line('custom_payment')):'Custom Payment'?>
          </label>
        </div>
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('add_details_for_bank_transfer_or_custom_payment')?htmlspecialchars($this->lang->line('add_details_for_bank_transfer_or_custom_payment')):'Add details for bank transfer or custom payment'?></label>
        <textarea name="bank_details"><?=(isset($bank_details) && !empty($bank_details))?$bank_details:''?></textarea>
      </div>
    </div>

    <div class="card-footer bg-whitesmoke text-md-right">
      <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
    </div>
    <div class="result"></div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js"></script>
<script>

tinymce.init({
  selector: 'textarea',
  height: 240,
  plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap  emoticons code',
  menubar: 'edit view insert format tools table tc help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor permanentpen removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment code',
  setup: function (editor) {
    editor.on("change keyup", function (e) {
        tinyMCE.triggerSave(); 
    });
  }
});

</script>