<form id="frmTemplateAddEdit">
    <input type="hidden" name="sms_auto_template_id" id="sms_auto_template_id" value="" />
    <input type="hidden" name="sms_api_id" id="sms_api_id" value="<?php echo $id; ?>" />
<div class="form-group row">    
    <div class="col-md-4">
        <label>SMS API<span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="<?php echo $api_row['name']; ?>" name="sms_t_api_name" id="sms_t_api_name" readonly="true" />
    </div>  
    <div class="col-md-4">
        <label>Template Name<span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="" name="sms_t_name" id="sms_t_name"  />
    </div> 
    <div class="col-md-4">
        <label>Template ID<span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="" name="sms_t_template_id" id="sms_t_template_id"  />
    </div>   
</div>

<div class="form-group row"> 
    <div class="col-md-12">
        <label>Template Message<span class="text-danger">*</span></label>
        <textarea class="form-control" name="sms_t_text" id="sms_t_text" rows="2" cols="10"></textarea>
        <a href="JavaScript:void(0)" class="pull-right get_template_variable text-info">Get Template variables</a>
    </div>   
</div>
<div class="form-group text-center">
    <a href="javascript:;" class="btn btn-primary sss" id="add_sms_template_confirm" >
    Save
    </a>
</div>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
    <thead>
        <tr>
            <th width="15%">SMS API</th>
            <th width="10%">Name</th>
            <th width="45%">ID</th>
            <th width="20%">Template</th>
            <th class="text-center" width="10%">Action</th>
        </tr>
    </thead>
    <tbody id="sms_t_tcontent" class="t-contant-img"></tbody>
</table>
</div>
<script>
$(document).ready(function(){

    
});
    </script>