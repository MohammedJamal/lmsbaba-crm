<script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
  
  tinymce.init({
    selector: 'textarea.basic-wysiwyg-editor',
    force_br_newlines : true,
    force_p_newlines : false,
    forced_root_block : '',
    menubar: false,
    statusbar: false,
    toolbar: false,    
    setup: function(editor) {
        editor.on('focusout', function(e) {
          //console.log(editor);          
          // var quotation_id=$("#quotation_id").val();
          // var updated_field_name=editor.id;
          // var updated_content=editor.getContent();
          // fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
          //check_submit();
    })
    }

  });

  tinymce.init({
      selector: 'textarea.moderate-wysiwyg-editor',
      // height: 300,
      menubar: false,
      statusbar:false,
      plugins: ["code,advlist autolink lists link image charmap print preview anchor"],
      toolbar: 'bold italic backcolor | bullist numlist',
      content_css: [],
      setup: function(editor) {
        editor.on('focusout', function(e) {
          //console.log(editor);
          // var quotation_id=$("#quotation_id").val();
          // var updated_field_name=editor.id;
          // var updated_content=editor.getContent();
          // fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
          //check_submit();
      })
    }
  });  
  
</script>
<form id="frmLeadEdit">
    <input type="hidden" name="lead_id" value="<?php echo $lead_data->id; ?>" />
    <input type="hidden" name="lead_follow_up_date" value="<?php echo $lead_data->followup_date; ?>" />
    <input type="hidden" name="lead_follow_up_type" value="<?php echo $lead_data->followup_type_id; ?>" />
    <div class="form-group row">
        <div class="col-sm-12">
            <label class="full-label">Title:</label>
            <input type="text" class="form-control" name="lead_title" id="lead_title" value="<?php echo $lead_data->title; ?>" >
            <div class="text-danger" id="lead_title_error"></div>
        </div>    
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <label class="full-label">Describe Requirements<span class="text-danger">*</span>:</label>
            <textarea rows="1" cols="1" class="form-control basic-wysiwyg-editor" name="lead_requirement" id="lead_requirement"><?php echo strip_tags($lead_data->buying_requirement,'<br>'); ?></textarea>
            <div class="text-danger" id="lead_requirement_error"></div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12"><button type="button" class="btn btn-primary fright" data-id="" data-title="" id="lead_title_desc_edit_submit_confirm">Save</button></div>
    </div>
</form>