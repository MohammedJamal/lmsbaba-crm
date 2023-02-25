<form id="frmTemplateAddEdit">
    <input type="hidden" name="whatsapp_auto_template_id" id="whatsapp_auto_template_id" value="" />
    <input type="hidden" name="whatsapp_api_id" id="whatsapp_api_id" value="<?php echo $id; ?>" />
<div class="form-group row">    
    <div class="col-md-4">
        <label>WhatsApp API<span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="<?php echo $api_row['name']; ?>" name="whatsapp_t_api_name" id="whatsapp_t_api_name" readonly="true" />
    </div>  
    <div class="col-md-4">
        <label>Template Name<span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="" name="whatsapp_t_name" id="whatsapp_t_name"  />
    </div> 
    <div class="col-md-4">
        <label>Template ID<span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="" name="whatsapp_t_template_id" id="whatsapp_t_template_id"  />
    </div>   
</div>

<div class="form-group row"> 
    <div class="col-md-12">
        <label>Template variables</label>
        <input type="text" class="form-control" value="" name="whatsapp_t_template_variable" id="whatsapp_t_template_variable"  />
        <a href="JavaScript:void(0)" class="pull-right get_template_variable text-info">Get Template variables</a>
    </div>   
</div>

<div class="form-group row"> 
    <div class="col-md-12">
        <label>Template For Reference</label>
        <textarea class="form-control" name="whatsapp_t_text" id="whatsapp_t_text" rows="2" cols="10"></textarea>        
    </div>   
</div>
<div class="form-group text-center">
    <a href="javascript:;" class="btn btn-primary sss" id="add_whatsapp_template_confirm" >
    Save
    </a>
</div>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
    <thead>
        <tr>
            <th width="15%">API</th>
            <th width="10%">Name</th>
            <th width="35%">ID</th>
            <th width="30%">Variables</th>
            <th class="text-center" width="10%">Action</th>
        </tr>
    </thead>
    <tbody id="whatsapp_t_tcontent" class="t-contant-img"></tbody>
</table>
</div>
<script src="<?php echo base_url();?>assets/js/bootstrap-tagsinput.js"></script>
<script>
$(document).ready(function(){
    $("input#whatsapp_t_template_variable").tagsinput({
      maxTags: 20,
      trimValue: true,
      allowDuplicates: false
    });    
});
</script>