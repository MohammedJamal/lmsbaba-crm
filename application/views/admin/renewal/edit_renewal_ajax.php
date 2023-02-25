<?php
$renewal_product_arr=explode(",", $renual_detail->renewal_product);
$pstr='';
foreach($renewal_product_arr AS $p_str)
{
	$p_arr=explode("#", $p_str);
	$pid=$p_arr[0];
	$pstr .=$pid.',';
}
$pstr=rtrim($pstr,',');
?>
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


<div class="form_bg" style="" id="lead_add_div">            
  <div class="middle_form">
	  <form id="frmRenewalEdit" name="frmRenewalEdit" method="post" action="" class="new-lead-sec-from rounded-form">
	  	<input type="hidden" name="edit_id" value="<?php echo $renual_detail->id; ?>">
	    <div style="clear: both;"></div>
			<div class="padding_35 full-l">
				<?php /* ?> 
				<div class="form-group row">
					<div class="col-sm-9">
						<label class="full-label">Product / Service Required<span class="text-danger">*</span>:</label>
						<select class="js-example-basic-multiple form-control" name="product_tags[]" id="product_tags" multiple="multiple" >
							<?php foreach($product_list AS $product){ ?>
								<option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
							<?php } ?>
						</select>					
						<div class="text-danger" id="product_tags_error"></div>
					</div>
					<div class="col-sm-3">
						<label class="full-label">Renewal Amount<span class="text-danger">*</span>:</label>
						<input type="text" class="form-control double_digit" name="renewal_amount" id="renewal_amount" placeholder="" value="" >
						<div class="text-danger" id="renewal_amount_error"></div>
					</div>				
				</div>
				<?php */ ?>
				<input type="hidden" id="selected_product_str" value="<?php echo $pstr; ?>">
				<div class="form-group row">
					<div class="col-sm-11">
						<label class="full-label">Product / Service Required<span class="text-danger">*</span>:</label>
						<select class="form-control " name="" id="product_to_add" >
							<option value="">Type Product / Service.</option>
							<?php foreach($product_list AS $product){ ?>
								<option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
							<?php } ?>
						</select>
						<div class="text-danger" id="product_tags_error"></div>	
					</div>	
					<div class="col-sm-1">
						<label class="full-label">&nbsp;</label>
						<a href="JavaScript:void(0)" id="add_product_btn" class="btn btn-primary">Add</a>
					</div>							
				</div>
				<div class="form-group row" >
					<div class="col-sm-12">
						<div id="selected_product_html_div">
							<?php
							foreach($renewal_product_arr AS $p_str)
							{
								$p_arr=explode("#", $p_str);
								$pid=$p_arr[0];
								$pname=$p_arr[1];
								$pprice=$p_arr[2];
							?>
							<div class="form-group row" id="selected_p_sub_div_<?php echo $pid; ?>">
							<div class="col-md-2 text-left lh-45"><?php echo $pname; ?></div>
							<div class="col-md-4 text-center lh-45">
								<input type="hidden" class="form-control" name="product_tags[]" value="<?php echo $pid; ?>">
								<input type="text" class="form-control double_digit" name="product_price_tags_<?php echo $pid; ?>" placeholder="" value="<?php echo $pprice; ?>">
							</div>
							<div class="col-md-2 text-left lh-45">
								<a href="JavaScript:void(0)" class="del_selected_p_div icon-btn-new btn-danger text-white" data-id="<?php echo $pid; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
							</div>
						</div>
						<?php } ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-12">
						<label>Describe Renewal/ AMC Type<span class="text-danger">*</span>:</label>
						<textarea rows="1" cols="1" class="form-control basic-wysiwyg-editor" name="renewal_requirement" id="renewal_requirement" placeholder="Describe Requirements"><?php echo $renual_detail->description; ?></textarea>
						<div class="text-danger" id="renewal_requirement_error"></div>
					</div>
					<div class="col-sm-12 d-flex mt-10">
						Attach File (if any): &nbsp;&nbsp;<input type="file" name="renewal_attach_file" id="renewal_attach_file">
					</div>
				</div>			
				
				<div class="form-group row">
					<div class="col-sm-6">
						<label class="full-label">Renewal Date<span class="text-danger">*</span>:</label>
						<div class="rela-div">
							<span class="label-set">Select a date</span>
						<input type="text" class="form-control " name="renewal_date" id="renewal_date" placeholder="" value="<?php echo date_db_format_to_display_format($renual_detail->renewal_date); ?>" readonly="true" />
						</div>
						<div class="text-danger" id="renewal_date_error"></div>
					</div>
					<div class="col-sm-6">
						<label class="full-label">Next Follow Up Date<span class="text-danger">*</span>:</label>
						<div class="rela-div">
							<span class="label-set">Select a date</span>
						<input type="text" class="form-control " name="renewal_follow_up_date" id="renewal_follow_up_date" placeholder="Follow up date" value="<?php echo date_db_format_to_display_format($renual_detail->next_follow_up_date); ?>" readonly="true" />
						</div>
						<div class="text-danger" id="renewal_follow_up_date_error"></div>
					</div>
				</div>
			</div>		                  
	  	<div class="col-sm-12 text-center">
	    	<p id="sub_form" class="file in-p" >
	        <input type="submit" value="" id="edit_to_renewal_submit_confirm" />
	        <label for="file" class="serach-btn">
	        	<span class="btn-text">Save Update<span> 
	        	
	        </label>
	         
	    	</p>
	  	</div>
		</form>        
  </div>
</div> 

<script type="text/javascript">
$(document).ready(function() {		
    // $('#product_tags').select2({
    // 	tags: false,
    // 	//tokenSeparators: [',', ' ']
    // }); 

    $('#product_to_add').select2({
    	tags: false
    });  





    $(".double_digit").keydown(function(e) {
        debugger;
        var charCode = e.keyCode;
        if (charCode != 8) {
            //alert($(this).val());
            if (!$.isNumeric($(this).val()+e.key)) {
                return false;
            }
        }
    return true;
  });
});
$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
    $('#renewal_date').datepicker({
    	dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5',
        // maxDate: 0,
        minDate: 0,
    });

    $('#renewal_follow_up_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5',
        // maxDate: 0,
        minDate: 0,
    });
});
</script>
