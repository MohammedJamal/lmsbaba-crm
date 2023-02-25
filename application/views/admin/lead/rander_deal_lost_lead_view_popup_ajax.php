
<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>">
<input type="hidden" name="communication_type" id="communication_type" value="2">
<input type="hidden" name="client_not_interested" id="client_not_interested" value="Y" >
<input type="hidden" name="lead_regret_reason" id="lead_regret_reason" value="">
<input type="hidden" name="lead_comments_for_mail_trail" id="lead_comments_for_mail_trail" value="">
<input type="hidden" name="general_description" id="general_description" value="">
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
                 
                 <div class="page-holder float-right mr-45">
                        <ul class="action-ul">                         
                          <li>
                            <a href="JavaScript:void(0)" title="previous" class="back-bt" data-leadid="<?php echo $lead_id; ?>" id="back_to_linked_comment_update_lead_popup"><img src="<?php echo assets_url(); ?>images/left_black.png"> BACK</a>
                          </li>                          
                        </ul>
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
              <div class="mail-form-row no-border pl-0 pr-0">
                 <div class="auto-full pl-15 pr-15">
                    <label>Oops.. Sorry to hear that you lost the deal. Better luck next time.</label>                    
                 </div>
                 
                 <div class="auto-full pl-15 pr-15">
                  <label>Select Reason:</label>
                  <select name="lead_regret_reason_id" id="lead_regret_reason_id" class="form-control max-width-300">
                      <option value="">==Select Reason==</option>
                      <?php foreach($regret_reason_list as $row){ ?>
                      <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                      <?php } ?>
                  </select>
                </div>

                <div class="mail-form-row no-border">
                 <div class="auto-full">
                    <label class="stitle">Add Comment</label>
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
                        <div class="buyer-scroller buyer-scroller-lost">
                          <div class="buying-requirements"></div>
                        </div>
                      </div>
                       <!-- <div class="buying-requirements">
                         <textarea placeholder="Type your comment" rows="10" name="general_description" id="general_description" class="form-control basic-wysiwyg-editor"  cols=""><?php //echo $last_mail['body_html']; ?></textarea>
                       </div> -->
                    </div>
                 </div>
              </div>
              
              <?php
              $nfd_fail_icon='';
              $next_followup_date='';
              if($lead_data->followup_date!='' || $lead_data->followup_date!='0000-00-00')
              {
                if($lead_data->followup_date<date("Y-m-d"))
                {
                  $nfd_fail_icon='<i class="fa fa-flag red-font-text" aria-hidden="true"></i>&nbsp;';
                  //$next_followup_date='';
                }
                else
                {
                  // $next_followup_date=date_db_format_to_display_format($lead_data->followup_date);
                }
                $next_followup_date=date_db_format_to_display_format($lead_data->followup_date);
              }
              else
              {
                $next_followup_date='---';
              }
              ?>
              <div class="mail-form-row blue-label footer-shadow">
                 <div class="auto-row">
                    <label>Next Follow-up:</label>
                    <div class="mail-input-div">
                    <?php echo $nfd_fail_icon; ?>
                    <input type="text" class="mail-input input_date" value="<?php echo $next_followup_date; ?>" name="next_followup_date" id="next_followup_date" readonly="true">
                    <span class="cal-icon"><img src="<?php echo assets_url(); ?>images/cal-icon.png"></span>
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
              <div class="pull-left">
                  <label class="check-box-sec fl">
                      <input type="checkbox" name="mail_to_client" id="mail_to_client" value="Y">
                      <span class="checkmark"></span>
                  </label>
                      Send Acknowledgement to Client            
              </div>
              <!-- <a href="JavaScript:void(0)" class="lead-btn orange pull-right update-lead" id="regret_comment_update_confirm">Regret This Lead</a> -->
              <button type="button" class="btn lead-btn orange pull-right update-lead" id="regret_comment_update_confirm">Regret This Lead</button>
           </div>
        </div>
     </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){

    $("body").on("change","#lead_regret_reason_id",function(e){
      var reason=$(this).children("option:selected").text();
      $(".buying-requirements").text(reason);
    });

    $('input.mail-input').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });

    $('input.mail-input:not(.auto-w)').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });


    var assets_base_url = $("#assets_base_url").val();
    $( ".input_date" ).datepicker({
          showOn: "both",
          dateFormat: "dd-M-yy",
          buttonImage: assets_base_url+"images/cal-icon.png",
          // changeMonth: true,
          // changeYear: true,
          // yearRange: '-100:+0',
          buttonImageOnly: true,
          buttonText: "Select date",
          minDate: 0,
    });


});
</script>
<!-- <script src="<?php echo base_url();?>assets/js/custom/lead/edit.js"></script> -->

