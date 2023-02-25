
<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $id; ?>">
<input type="hidden" name="communication_type" id="communication_type" value="6">
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
                 <?php if($is_linked_view=='N'){ ?>
                 <div class="page-holder float-right mr-45">
                    <ul class="action-ul">
                      <li class="mr-15"><span class="mail-info">
                       <?php 
                       $key = array_search($id, $all_ids);
                       echo ($key+1);

                       if($key>0)
                          $pre=($key-1);
                       if($key<count($all_ids))
                          $nxt=($key+1);
                       ?> of <?php echo count($all_ids); ?> </span></li>
                      <li>
                        <a href="JavaScript:void(0)" title="previous" data-id="<?php echo $all_ids[$pre]; ?>" data-filterbystage="<?php echo $filter_by_stage;?>" class="new_comment_update_lead_popup"><img src="<?php echo assets_url(); ?>images/left_black.png"></a>
                      </li>
                      <li>
                        <a href="JavaScript:void(0)" title="Next" data-id="<?php echo $all_ids[$nxt]; ?>" data-filterbystage="<?php echo $filter_by_stage;?>" class="new_comment_update_lead_popup"><img src="<?php echo assets_url(); ?>images/right_black.png"></a>
                      </li>
                    </ul>
                 </div>
                <?php } ?>
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
                    <?php if($is_show_po=='Y'){ ?>
                    <a href="JavaScript:void(0);" class="lead-btn green mr-10 po_upload_view" data-lid="<?php echo $id; ?>" data-loppid="<?php echo $opp_id; ?>">Won</a><?php } ?>
                    <?php if($lead_data->current_stage_id=='3' || $lead_data->current_stage_id=='5' || $lead_data->current_stage_id=='6' || $lead_data->current_stage_id=='7'){}else{ ?><a href="JavaScript:void(0)" class="lead-btn pink regret_lead_view" data-lid="<?php echo $id; ?>" data-iswon="<?php echo ($lead_data->current_stage_id=='4')?'Y':'N'; ?>">Lost</a> <?php } ?>
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
                          <label>Email:</label> <?php echo $lead_data->cus_contact_person; ?>
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
                    <label>Buyer's Response:</label>
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
                          <div class="buying-requirements">
                            <p><?php echo $last_mail['body_html']; ?></p>
                          </div>
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
                       <div class="attachment_clip"></div><small>Click to Atach Documents</small>
                       <input type="file" name="lead_attach_file[]" id="lead_attach_file" multiple="" style="display: none;">
                    </label>                          
                 </div>
              </div>
              
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
              <div class="mail-form-row blue-label footer-shadow">
                 <div class="auto-row">
                    <label>Next Follow-up:</label>
                    <input type="text" class="mail-input input_date" value="<?php echo $next_followup_date; ?>" name="next_followup_date" id="next_followup_date">
                    <span class="cal-icon"><img src="<?php echo assets_url(); ?>images/cal-icon.png"></span>
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
                    <select class="mail-input" disabled>
                       <option><?php echo $lead_data->user_name; ?></option>
                    </select>
                 </div>
                 <div class="auto-row">
                    <label>Stage:</label>
                    <input type="text" class="mail-input" value="<?php echo $lead_data->current_stage; ?>">
                 </div>
                 <div class="auto-row">
                    <label>Status:</label>
                    <input type="text" class="mail-input" value="<?php echo $lead_data->current_status; ?>">
                 </div>
              </div>
           </div>
           <div class="mail-form-row">
              <div class="col-md-4 ff">
                  <label class="check-box-sec">
                      <input type="checkbox" name="mail_to_client" id="mail_to_client" value="Y">
                      <span class="checkmark"></span>
                  </label>
                      Mail to Client            
              </div>
              <!-- <a href="JavaScript:void(0)" class="lead-btn orange pull-right update-lead" id="comment_update_confirm">Update Lead</a> -->
              <button type="button" class="btn lead-btn orange pull-right update-lead" id="comment_update_confirm">Update Lead</button>
           </div>
        </div>
     </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('input.mail-input').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });

    $('input.mail-input:not(.auto-w)').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });


    var base_url_root = $("#base_url_root").val();
    $( ".input_date" ).datepicker({
          showOn: "both",
          dateFormat: "dd-M-yy",
          buttonImage: base_url_root+"images/cal-icon.png",
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

