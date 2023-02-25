<?php //print_r($lead_data).'okk'; ?>
<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>">
<input type="hidden" name="communication_type" id="communication_type" value="2">
<input type="hidden" name="client_not_interested" id="client_not_interested" value="" >
<input type="hidden" name="lead_regret_reason" id="lead_regret_reason" value="">
<input type="hidden" name="lead_regret_reason_id" id="lead_regret_reason_id" value="">
<input type="hidden" name="lead_comments_for_mail_trail" id="lead_comments_for_mail_trail" value="">
<input type="hidden" name="general_description" id="general_description" value="">
<input type="hidden" id="mark_cc_mail" name="mark_cc_mail[]">
<input type="hidden" name="next_followup_type_id" id="next_followup_type_id" value="2">
<div class="modal-dialog" role="document">
  <div class="modal-content">
     <div class="modal-body">
        <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
           <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
              <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
           </svg>
        </a>
        <div class="lead-loop">
           <div class="lead-top">
              <div class="mail-form-row max-width">
                 <div class="auto-row">
                    <label>Enquiry ID:</label>
                    <input type="text" class="mail-input" value="#<?php echo $lead_data->id; ?>" readonly="">
                 </div>
                 <div class="auto-row">
                    <label>Enquiry Date:</label>
                    <input type="text" class="mail-input" value="<?php echo date_db_format_to_display_format($lead_data->enquiry_date); ?>" readonly="">
                    <!-- <span class="cal-icon"><img src="https://lmsbaba.com/dashboard/images/cal-icon.png"></span> -->
                 </div>
                 
                 <div class="auto-row-right">
                    <?php                     
                    $is_show_po='N';
                    $opp_id='';
                    if(count($opportunity_list)>0){
                      foreach($opportunity_list as $opp)
                      {
                        if($opp->status==2){
                          if(count($opportunity_list)==1)
                          {
                              $opp_id=$opp->id;
                          }
                          $is_show_po='Y';
                          break;
                        }
                      }
                    }
                    ?>
                    <?php 
                    //if($is_show_po=='Y'){ 
                    if($lead_data->current_stage_id!='4'){ ?>
                    <a href="JavaScript:void(0);" class="lead-btn green mr-10 po_upload_view" data-lid="<?php echo $lead_id; ?>" data-loppid="<?php echo $opp_id; ?>" data-is_q_exist="<?php echo $is_show_po; ?>">Won</a>
                     
                    <?php if($lead_data->current_stage_id=='3' || $lead_data->current_stage_id=='5' || $lead_data->current_stage_id=='6' || $lead_data->current_stage_id=='7'){}else{ ?>
                    <a href="JavaScript:void(0)" class="lead-btn pink regret_lead_view" data-lid="<?php echo $lead_id; ?>" data-iswon="<?php echo ($lead_data->current_stage_id=='4')?'Y':'N'; ?>">Lost</a> 
                    <?php } ?>
                    <?php } ?>
                 </div>
              </div>
              <div class="mail-form-row">
                 <div class="show-more-holder">
                    <a href="javascript:void(0);" class="show-more"><img src="<?php echo assets_url(); ?>images/mail-dots.png"></a>
                    <div class="arrow_box">
                       <div class="form-group">
                          <label>Company:</label> <?php echo $lead_data->cus_company_name; ?>
                       </div>
                       <div class="form-group">
                          <label>Name:</label> <?php echo $lead_data->cus_contact_person; ?>
                       </div>
                       <div class="form-group">
                          <label>Email:</label> <?php echo $lead_data->cus_email; ?>
                       </div>
                       <div class="form-group">
                          <label>Mobile:</label> <?php echo ($lead_data->cus_mobile)?$lead_data->cus_mobile:'N/A'; ?>
                       </div>
                       <div class="form-group">
                          <label>Address:</label> <?php echo ($lead_data->cus_address)?$lead_data->cus_address:'N/A';?>
                       </div>
                       <div class="form-group">
                          <label>City:</label> <?php echo ($lead_data->cus_city)?$lead_data->cus_city:'N/A'; ?>
                       </div>
                       <div class="form-group">
                          <label>State:</label> <?php echo ($lead_data->cus_state)?$lead_data->cus_state:'N/A';?>
                       </div>
                       <div class="form-group">
                          <label>Country:</label> <?php echo ($lead_data->cus_country)?$lead_data->cus_country:'N/A'; ?>
                       </div>
                    </div>
                 </div>
                 <div class="auto-row">
                    <label>Lead Title:</label>
                    <input type="text" class="mail-input" value="<?php echo $lead_data->title; ?>" disabled>
                 </div>
              </div>
              <div class="mail-form-row no-border">
                 <div class="auto-full">
                    <label>Describe Comments:</label>
                    <div class="buyer-scroller-n">
                      <div class="custom-editer">
                        <!-- <ul class="tools">
                          <li>
                            <input type="button" value="B" data-cmd="bold"/>
                          </li>
                          <li>
                            <input type="button" value="U" data-cmd="underline">
                          </li>
                        </ul> -->
                        <div class="buyer-scroller">
                          <div class="buying-requirements"><div class="default-com" style="width: 100%;height: 26px;"></div><?php /* ?>
                              <div class="default-com" style="width: 100%;height: 26px;"></div><br><br><br><br><br><br>
                              <div class="history_mail"> 
                                <div class="gmail_quote_holdet">
                                    <a href="#" class="show_gmail_quote_new"></a>
                                    <div class="gmail_quote">
                                      <div style="width: 100%;display: inline-block;margin-bottom: 10px; font-size: 13px; color: #807f7a;">
                                        <div style="width: 100%;display: inline-block;margin-bottom: 2px; font-size: 13px; color: #807f7a;">----- Original Enquiry -----</div>
                                        <div style="width: 100%;display: inline-block;margin-bottom: 2px; font-size: 13px; color: #807f7a;">From: <?php echo $lead_data->cus_contact_person; ?> <?php echo htmlentities('<'.$lead_data->cus_email.'>'); ?></div>
                                        <div style="width: 100%;display: inline-block;margin-bottom: 2px; font-size: 13px; color: #807f7a;">Date: <?php echo date_db_format_to_display_format($lead_data->enquiry_date); ?></div>
                                      </div>
                                      <?php echo $lead_data->title; ?><br>
                                      <?php echo $lead_data->buying_requirement; ?>
                                    </div>
                                </div>
                              </div>
                          <?php */ ?></div>
                        </div>
                      </div>
                       <!-- <div class="buying-requirements">
                         <textarea placeholder="Type your comment" rows="10" name="general_description" id="general_description" class="form-control basic-wysiwyg-editor"  cols=""><?php //echo $last_mail['body_html']; ?></textarea>
                       </div> -->
                    </div>
                 </div>
                 
                 <?php /* ?>
                 <div class="auto-full">
                  <label >CC to Employee:</label>
                  <?php if(count($user_list)){?>
                   <select class="form-control input-sec select2" id="mark_cc_mail" name="mark_cc_mail[]" multiple>
                   <?php foreach($user_list as $user){ ?>
                  <option value="<?php echo $user['email']; ?>">
                   <?php echo $user['email'] .'( Emp ID: '.$user['id'].')'; ?>
                  </option>
                  <?php } ?>
                  </select>
                  <?php } ?>
                </div>
                <?php */ ?>
                  <div class="float-left">
                    <label class="up-label" for="lead_attach_file">
                       <div class="attachment_clip"></div><small>Click to Attach Documents</small>
                       <input type="file" name="lead_attach_file[]" id="lead_attach_file" multiple="" style="display: none;">
                    </label>              
                  </div>
                  <p id="files-area" style="float:left">
                     <span id="filesList">
                        <span id="files-names"></span>
                     </span>
                  </p>
                  <!-- <div class="upload-name-holder" style="display: inline-block;">
                     <div class="fname_holder" id="attach_file_outer" style="display:none;">
                     <span id="attach_file_div"></span>
                     <a href="JavaScript:void(0)" data-filename="" class="file_close" id="attach_file_div_close"><i class="fa fa-times" aria-hidden="true"></i></a>
                     </div>
                  </div> -->
               <?php
               /* 
               -----------------------------------
               Quick Reply 
               
               */
               ?>
               <div class="mail-form-row-full">
                  <h1 class="quick_view_h1_tag <?php echo ($quick_reply_count==0)?'hide':''; ?>">Send your reply by choosing one</h1>
                  <div class="mail-form-row" id="fix-width">
                     <div class="add-com">
                        <span class="add-com-action dropdown-toggle" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Add Pre-define comment" ><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                        <div class="dropdown-menu left-dd">
                           <div class="com-holder-new">
                              <div class="com-holder-fild">
                                 <div class="t-txt">
                                    <label>Title</label>
                                 <input type="text" name="quick_view_title" id="quick_view_title" placeholder="Text here..." maxlength="100">
                                 <small class="text-danger" id="quick_view_title_error"></small>
                                 </div>
                                 <div class="t-txt">
                                    <label>Description</label>
                                 <textarea name="quick_view_desc" id="quick_view_desc" placeholder="Text here..." class="basic-editor---"></textarea>
                                 <small class="text-danger" id="quick_view_desc_error"></small>
                                 </div>
                              </div>
                              <div class="com-holder-act">
                                 <a href="JavaScript:void(0);" class="pop-ac yes add_quick_view_comment" data-target="txt-carousel">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                 </a>
                                 <a href="JavaScript:void(0);" class="pop-ac no close_quick_view_comment">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                 </a>
                              </div>
                           </div>
                       </div>

                        <span class="add-com-count quick_reply_count <?php echo ($quick_reply_count==0)?'hide':''; ?>" >(<?php echo $quick_reply_count; ?>)</span>
                     </div>
                     <div id="txt-carousel" class="btn-side mh-32 quick_reply_list">
                        <?php 
                        if(count($quick_reply_comments))
                        {
                           foreach($quick_reply_comments AS $comment)
                           {
                              ?>
                              <div class="item" id="item_<?php echo $comment->id; ?>">
                                  <div class="auto-txt-item quick_view_item" data-toggle="tooltip" title="<?php echo strip_tags($comment->comment); ?>" data-comment="<?php echo $comment->comment; ?>"><?php echo substr($comment->title, 0, 10); 
                                        echo (strlen($comment->title)>10)?'...':'';
                                        ?><a href="JavaScript:void(0);" data-id="<?php echo $comment->id; ?>" class="del-item del-comm"><i class="fa fa-times" aria-hidden="true"></i></a></div>
                               </div>
                              <?php
                           }                           
                        }
                        ?>
                     </div>
                  </div>                     
               </div>
               <?php
               /* 
               Quick Reply 
               -----------------------------------
               */
               ?>
            </div>
              
              <?php
              $nfd_fail_icon='';
              $next_followup_date='';
              /*
                            
              if($lead_data->followup_date!='' || $lead_data->followup_date!='0000-00-00 00:00:00')
              {
                if($lead_data->followup_date<date("Y-m-d"))
                {
                  //$next_followup_date='';
                  $nfd_fail_icon='<i class="fa fa-flag red-font-text" aria-hidden="true"></i>&nbsp;';
                }
                else
                {
                  // $next_followup_date=date_db_format_to_display_format($lead_data->followup_date);
                }
                $next_followup_date=datetime_db_format_to_display_format_ampm($lead_data->followup_date);
              }
              else
              {
                $next_followup_date='---';
              }
              */

                
                if($lead_data->current_stage_id=='7' || $lead_data->current_stage_id=='6' || $lead_data->current_stage_id=='3' || $lead_data->current_stage_id=='5' || $lead_data->current_stage_id=='4')
                {
                $next_followup_date='';
                }
                else
                {
                if($lead_data->followup_date!='0000-00-00 00:00:00')
                {
	                $red_merk_class="";
	                if($lead_data->followup_date<date("Y-m-d"))
	                {
		                $nfd_fail_icon='<i class="fa fa-flag red-font-text" aria-hidden="true"></i>&nbsp;';
	                }
	                //echo date_db_format_to_display_format($lead_data->followup_date);
	                $next_followup_date=datetime_db_format_to_display_format_ampm($lead_data->followup_date);
                }
                else
                {
	                $next_followup_date='';
                }
                }
              ?>
              <div class="mail-form-row blue-label footer-shadow">
                 <div class="auto-row">
                    <label>Next Follow-up:</label>
                    <div class="mail-input-div">
                    <?php if($next_followup_date!=''){ ?>
                    <?php echo $nfd_fail_icon; ?>                    
                    <input type="text" class="mail-input nfd_input_date" value="<?php echo $next_followup_date; ?>" name="next_followup_date" id="next_followup_date" readonly="true" style="width:130px;">
                    <img src="<?php echo assets_url(); ?>images/cal-icon.png" style="width:19px;" >
                    <!--<span class="cal-icon"><img src="<?php echo assets_url(); ?>images/cal-icon.png" style="width:19px;" ></span>-->
                    <?php }else{echo'--<input type="hidden" value="" name="next_followup_date" id="next_followup_date" >';} ?>
                  </div>                 
                  
                 </div>
                 <?php /* ?>
                 <div class="auto-row">                  
                    <label>Follow-up Type:</label>
                    <select class="mail-input editable-field" name="next_followup_type_id" id="next_followup_type_id">
                       <option value="">Select</option>
                       <?php foreach($next_follow_by AS $row){ ?>
                       <option value="<?php echo $row['id']; ?>" <?php if($row['id']==$lead_data->followup_type_id){echo'SELECTED';} ?>><?php echo $row['name']; ?></option>
                       <?php } ?>

                    </select>
                 </div>
                 <?php */ ?>

                 <div class="auto-row">
                    <label>Assign to:</label>
                    <input type="text" class="mail-input" value="<?php echo $lead_data->user_name; ?>" readonly="true">
                 </div>
                 <div class="auto-row">
                    <label>Stage:</label>
                    <input type="text" class="mail-input" value="<?php echo $lead_data->current_stage; ?>" readonly="true">
                 </div>
                 <div class="auto-row">
                    <label>Status:</label>
                    <input type="text" class="mail-input" value="<?php echo $lead_data->current_status; ?>" readonly="true">
                 </div>
              </div>
           </div>
           <div class="mail-form-row">
              <div class="col-md-4 ff">
                  <label class="check-box-sec">
                      <input type="checkbox" name="mail_to_client" id="mail_to_client" value="Y">
                      <span class="checkmark"></span>
                  </label>
                  <span class="text-label">Mail to Client</span>            
              </div>
              <!-- <a href="JavaScript:void(0)" class="lead-btn orange pull-right update-lead" id="comment_update_confirm">Update Lead</a> -->
              <button type="button" class="btn lead-btn orange pull-right update-lead" id="lead_update_confirm">Update Lead</button>
           </div>
        </div>
     </div>
  </div>
</div>
<input type="hidden" id="curr_date_chk" value="<?php echo date_db_format_to_display_format(date("Y-m-d")); ?>">
<script type="text/javascript">
$(document).ready(function(){

   // ===========================================================================
   // multiple file upload display
   const dt = new DataTransfer(); 
   $("#lead_attach_file").on('change', function(e){    
   for(var i = 0; i < this.files.length; i++){
      let fileBloc = $('<span/>', {class: 'file-block'}),
         fileName = $('<span/>', {class: 'name', text: this.files.item(i).name});
         fileBloc.append('<span class="file-delete text-danger"><span>x</span></span>').append(fileName);
         $("#filesList > #files-names").append(fileBloc);
   };    
   for (let file of this.files) {
      dt.items.add(file);
   }    
   this.files = dt.files;    
   $('span.file-delete').click(function(){
      let name = $(this).next('span.name').text();     
      $(this).parent().remove();
      for(let i = 0; i < dt.items.length; i++){        
         if(name === dt.items[i].getAsFile().name){          
         dt.items.remove(i);
         continue;
         }
      }      
      document.getElementById('lead_attach_file').files = dt.files;
   });
   });
   // multiple file upload display
   // ===========================================================================
   //  $('#lead_attach_file').change(function() {
   //      var filename = $(this).val();
   //      var fullPath = filename.split('.')[0];
   //      var ext = filename.split('.')[1];
   //      var filename = fullPath.replace(/^.*[\\\/]/, '');   
   //      $("#attach_file_outer").show();     
   //      $('#attach_file_div').html(filename+'.'+ext);
   //  });
   //  $("body").on("click","#attach_file_div_close",function(e){
   //    $("#attach_file_outer").hide();  
   //    $("#lead_attach_file").val('');
   //  });

    $(document).on("click",".show_gmail_quote_new",function(event) {
      event.preventDefault();
      var ff = $(this).parent().html();
      $(this).hide();
      $(this).parent().find('.gmail_quote').addClass('show');
      $('#ReplyPopupModal .buying-requirements').focus();
      //alert(ff);
      // if ($(this).parent().find('.gmail_quote').hasClass("show")) {
      //   //alert(1);
      //   $(this).parent().find('.gmail_quote').removeClass('show');
      // }else{
      //   //alert(2);
      //   $(this).parent().find('.gmail_quote').addClass('show');
      // }
      
    });
    $('input.mail-input').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });

    $('input.mail-input:not(.auto-w)').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });

      /*
      var assets_base_url = $("#assets_base_url").val();
      $( ".input_date" ).datetimepicker({
         format:'d-M-Y h:i A',
         step: 15, 
         showOn: "both",          
         buttonImage: assets_base_url+"images/cal-icon.png",
         // changeMonth: true,
         // changeYear: true,
         // yearRange: '-100:+0',
         buttonImageOnly: true,
         buttonText: "Select date",
         minDate: 0,
      });
      */

    
      
});
      
</script>

