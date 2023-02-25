<?php //print_r($lead_data); ?>	
<form class="" name="lead_update_frm" id="lead_update_frm" method="post">
    <div class="general_update_textera back_color_tsf boder-raduis10" id="genupdate">
        <div class="row">
       <div id="gen_updt_res" class="alert alert-success no_display">Successfully Added Comment</div>
    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>" />
    <div class="col-md-12 form-group leads-label-text">
        <div class="top-auto-full">
            <label>Describe Comments<span class="text-danger">*</span>:</label>
            <div class="lead-dropdown">
                <div class="dropdown">
                    <button class="btn-dropdown" type="button" data-toggle="tooltip" title="Choose Pre-Define Comment">
                    <i class="fa fa-commenting" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu left">
                        <div class="user_comment">
                            <div class="user_header">
                                
                                <div class="pull-right">
                                    <a href="#" class="cbtn add-new-com-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                                </div>
                            </div>
                            <div id="lead_scroller" class="default-scoller">                                  
                                                               
                            </div>
                            <div class="select-action">
                                <button type="button" class="custom_blu btn btn-primary" id="comment_txt_submit">Add</button>
                                <button type="button" class="custom_blu btn btn-primary" id="comment_pop_close">Close</button>
                            </div>
                        </div>
                        <div class="edit-user_comment">
                            <div class="user_header">                                
                                <div class="pull-right">
                                    <a href="#" class="cbtn go-back"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Title<span class="text-danger">*</span> <small class="text-danger" id="edit_pre_define_title_error"></small></label>
                                <input class="form-control" type="text" name="edit_pre_define_title" id="edit_pre_define_title">
                                
                            </div>
                            <div class="form-group">
                                <label>Description<span class="text-danger">*</span><small class="text-danger" id="edit_pre_define_description_error"></small></label>
                                <textarea class="form-control" name="edit_pre_define_description" id="edit_pre_define_description"></textarea>
                                
                            </div>
                            <div class="select-action">
                                <input type="hidden" name="pre_define_comment_id" id="pre_define_comment_id">
                                <button type="button" class="custom_blu btn btn-primary" id="edit_pre_define_comment_confirm">Save</button>
                            </div>
                        </div>
                        <div class="add-user_comment">
                            <div class="user_header">                                
                                <div class="pull-right">
                                    <a href="#" class="cbtn go-back"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Title<span class="text-danger">*</span> <small class="text-danger" id="pre_define_title_error"></small></label>
                                <input class="form-control" type="text" name="pre_define_title" id="pre_define_title">
                                
                            </div>
                            <div class="form-group">
                                <label>Description<span class="text-danger">*</span> <small class="text-danger" id="pre_define_description_error"></small></label>
                                <textarea class="form-control" name="pre_define_description" id="pre_define_description"></textarea>
                                
                            </div>
                            <div class="select-action">
                                <button type="button" class="custom_blu btn btn-primary" id="add_pre_define_update_lead_comment">Add New</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            &nbsp;
            <div class="div-mail-trail">
                <label class="check-box-sec">
                    <input type="checkbox" name="mail_trail_check" id="mail_trail_check">
                    <span class="checkmark"></span>
                </label>
                Add Mail Trail
            </div>
            <input type="hidden" name="lead_comments_for_mail_trail" id="lead_comments_for_mail_trail" value="">
            <?php /* ?>
            &nbsp;
            <a hrtf="JavaScript:void(0)" class="btn-mail-trail" data-toggle="tooltip" title="Add Mail Trail" id="add_mail_trail">
                    <i class="fa fa-history" aria-hidden="true" style="font-size: 18px;"></i>
            </a>
            <?php */ ?>
        </div>
        <textarea placeholder="Type your comment" rows="3" name="general_description" id="general_description" class="form-control basic-wysiwyg-editor"  cols=""></textarea>
    </div> 
    <div class="col-md-12 pr-0">
 
    <div class="col-md-4 leads-label-text">
    <div class="form-group row">
    <label for="usr">CC to Employee:</label>
    <div class="">
     <?php if(count($user_list)){?>
     <select class="form-control input-sec select2" id="mark_cc_mail" name="mark_cc_mail[]" multiple>
     <?php foreach($user_list as $user){ ?>
    <option value="<?php echo $user->email; ?>">
     <?php echo $user->email .'( Emp ID: '.$user->id.')'; ?>
    </option>
    <?php } ?>
    </select>
    <?php } ?>
    </div>
    </div>
</div>
   <div class="col-md-4 leads-label-text">
    <div class="form-group">
    <label for="usr">Update Type<span class="text-danger">*</span>:</label>

    <select id="communication_type" class="form-control input-sec" name="communication_type">
 <option value="">Select</option>
 <?php foreach($communication_list as $comm_data) {  ?>
 <option value="<?php echo $comm_data->id;?>" <?php if($comm_data->id==$opportunity_data->communication_type){?> selected="selected"
<?php } ?>>
<?php echo $comm_data->title;?>
</option>
<?php } ?>                                
 </select>

</div>
</div>

  <div class="col-md-4 lend-date-sec leads-label-text">
     <div class="form-group">
         <label>Next Follow-up Date<span class="text-danger" id="next_follow_star">*</span>:</label>
        <div class="input-prepend">
        <span class="add-on input-group-addon">
     <img src="<?php echo assets_url(); ?>images/calendar.png"/>
</span>
<?php
$next_followup_date='';
if($lead_data->followup_date!='' || $lead_data->followup_date!='0000-00-00')
{
	if($lead_data->followup_date<date("Y-m-d"))
	{
		$next_followup_date='';
	}
	else
	{
		$next_followup_date=date_db_format_to_display_format($lead_data->followup_date);
	}
}
?>
 <input type="text" class="form-control drp input-sec" name="next_followup_date" id="next_followup_date" placeholder="Next Followup.." value="<?php echo $next_followup_date; ?>" readonly="true" />
    </div>
</div>
</div>
<div class="clear"></div>
     
<div class="col-md-12">
    <div class="row">
        <div class="col-md-4 ff">
            <label class="check-box-sec">
                <input type="checkbox" name="mail_to_client" id="mail_to_client" value="Y">
                <span class="checkmark"></span>
            </label>
                Mail to Client            
        </div>



        <div class="col-md-8 ff">
            <label class="check-box-sec">


                <input type="checkbox" name="client_not_interested" id="client_not_interested" value="Y" <?php if($cus_data->current_stage_id=='3' || $cus_data->current_stage_id=='5' || $cus_data->current_stage_id=='6' || $cus_data->current_stage_id=='7'){echo'disabled';} ?>>
                <span class="checkmark"></span>
            </label>
            <font class="text-red">Regret This Lead</font> 
            <small id="regret_reason_text" class="text-danger">
                <?php  if($cus_data->current_stage_wise_msg){
                echo '( '.$cus_data->current_stage_wise_msg.' )'; } 
                ?>
            </small>
        </div>
    </div>
</div> 

<div class="col-md-12 leads-label-text">    
    <div id="update_lead_mail_to_client_mail_subject_div" style="display:none;" class="form-group row">
        <!-- <span id="mail_subject_text_div"></span> -->
        <!-- <span id="mail_subject_update_btn_div"><a href="JavaScript:void(0);" class="change_mail_subject_popup_for_lead_update_client_mail"><i class="fa fa-pencil"></i></a></span> -->
        <label>Mail Subject<span class="text-danger">*</span> :</label>            
        <input type="hidden" id="mail_to_client_mail_subject" name="mail_to_client_mail_subject" value="" class="form-control" />
    </div>

    <div id="update_lead_regret_this_lead_mail_subject_div" style="display:none;" class="form-group row"> 
        <div class="">
            <label>Regret Reasons<span class="text-danger">*</span>:</label>
            <select name="lead_regret_reason_id" id="lead_regret_reason_id" class="form-control">
                <option value="">==Select a Reason==</option>
                <?php foreach($regret_reason_list as $row){ ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="">
            <label style="margin-top: 10px;">Mail Subject<span class="text-danger">*</span>:</label>
            <input type="hidden" id="regret_this_lead_mail_subject" name="regret_this_lead_mail_subject" value="" class="form-control" />
        </div>
        <input type="hidden" name="lead_regret_reason" id="lead_regret_reason">
        <?php /* ?> <input type="hidden" name="lead_regret_reason_id" id="lead_regret_reason_id"> <?php */ ?>                
    </div>            
</div>

    <div class="col-md-12 leads-label-text" style="margin-top: 10px;">                 
        <div class="form-group row">
            <div class="plr-0 col-md-8">
                <div class="custom_upload auto-width">
                    <label for="lead_attach_file">Attach File</label>
                    <input type="file" name="lead_attach_file[]" id="lead_attach_file" multiple="">
                    
                </div>
                <div class="upload-name-holder"></div>
            </div>
            <div class="plr-0 col-md-4 text-right">
                <input type="hidden" name="lead_attach_file_removed" id="lead_attach_file_removed" value="">
            <button type="button" class="btn btn-primary btn-round-shadow submit-padding pull-right" onclick="general_update()" id="lead_edit_confirm">Submit </button>
            </div>
        </div>         
    </div>         

</div>
<div class="clear"></div>
<div class="align-self-center">
<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $cus_data->id;?>" />
<div class="text-right mt-15">
 
</div>
</div>
</div>
</div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $(".select2").select2();
		
		$('#next_followup_date').datepicker({
			dateFormat: "dd-M-yy",
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+5'
		});
    });
</script>
<style type="text/css">
    .select2-container .select2-selection{
        min-height: 28px!important;
        line-height: 24px!important;
        height: 100%!important; 
}
.select2-container--default{ width: 100% !important; height: 40px; }
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 40px;
}
.custom_upload .fname_holder a .fa {
    color: #ff0303 !important;
}
</style>
<script src="<?php echo base_url();?>assets/js/custom/lead/edit.js"></script>
<script src="<?=base_url();?>assets/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: 'textarea.basic-wysiwyg-editor',
		force_br_newlines: true,
		force_p_newlines: false,
		forced_root_block: '',
		menubar: false,
		statusbar: false,
		toolbar: false,
		// setup: function(editor) {
		//      editor.on('focusout', function(e) {
		//        console.log(editor); 
		//        var updated_field_name=editor.id;
		//        var updated_content=editor.getContent();
		//        alert(updated_content);
		//        check_submit();
		//      });
		//  }
	});

	tinymce.init({
		selector: 'textarea.moderate-wysiwyg-editor',
		// height: 300,
		menubar: false,
		statusbar: false,
		plugins: ["code,advlist autolink lists link image charmap print preview anchor"],
		toolbar: 'bold italic backcolor | bullist numlist',
		content_css: [],
		setup: function(editor) {
			editor.on('focusout', function(e) {
				// console.log(editor);
				// var quotation_id=$("#quotation_id").val();
				// var updated_field_name=editor.id;
				// var updated_content=editor.getContent();
				// fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
				//check_submit();
			});
		}
	});
	
	// $('.custom_upload input[type="file"]').change(function(e) { 
	// 	var geekss = e.target.files[0].name; 
	// 	//alert(geekss + ' is the selected file.');
	// 	$(this).parent().find('label').css({'display':'none'});
	// 	$(this).parent().find('.fname_holder span').html(geekss);
	// 	$(this).parent().find('.fname_holder').css({'display':'block'});
	// 	//$(this).parent().find('label').text(geekss)

	// }); 
	//////
	// $(".custom_upload .fname_holder a").click(function(event){
	// 	event.preventDefault();
	// 	//alert(1);
	// 	$(this).parent().parent().find('label').css({'display':'inline-block'});
	// 	$(this).parent().parent().find('.fname_holder span').html('');
	// 	$(this).parent().parent().find('input').val('');
	// 	$(this).parent().parent().find('.fname_holder').css({'display':'none'})
	// });
</script>
