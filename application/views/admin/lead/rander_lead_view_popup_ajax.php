
<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $id; ?>">
<input type="hidden" name="lead_requirement" id="lead_requirement" value="">
<input type="hidden" name="lead_follow_up_type" id="lead_follow_up_type" value="2">
<div class="modal-dialog" role="document">
   <div class="modal-content" >
      <div class="modal-body" >

         <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
               <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
            </svg>
         </a>
         <div class="lead-loop">
               <div class="lead-top">
                  <div class="mail-form-row max-width pr-45">
                     <?php if($filter_by_stage=='pending_lead'){ ?>
                     <a href="JavaScript:void(0)" class="lead-btn grey-bg edit-lead lh-40"><img src="<?php echo assets_url(); ?>images/iconfinder_edit-change-pencil_2931178.svg"> Edit</a>
                     <?php } ?>
                     <div class="auto-row mt-5">
                        <label>Enquiry ID:</label>
                        <input type="text" class="mail-input" value="#<?php echo $lead_data->id; ?>" disabled>
                     </div>
                     <div class="auto-row mt-5">
                        <label>Enquiry Date:</label>
                        <input type="text" class="mail-input" value="<?php echo date_db_format_to_display_format($lead_data->enquiry_date); ?>" disabled>
                        
                     </div>
                     <?php if($is_linked_view=='N'){ ?>
                     <div class="page-holder float-right mr-15">
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
                            <a href="JavaScript:void(0)" title="previous" data-id="<?php echo $all_ids[$pre]; ?>" data-filterbystage="<?php echo $filter_by_stage;?>" class="new_lead_view_popup"><img src="<?php echo assets_url(); ?>images/left_black.png"></a>
                          </li>
                          <li>
                            <a href="JavaScript:void(0)" title="Next" data-id="<?php echo $all_ids[$nxt]; ?>" data-filterbystage="<?php echo $filter_by_stage;?>" class="new_lead_view_popup"><img src="<?php echo assets_url(); ?>images/right_black.png"></a>
                          </li>
                        </ul>
                     </div>
                     <?php } ?>

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
                        <input type="text" class="mail-input editable-field" value="<?php echo $lead_data->title; ?>" name="lead_title" id="lead_title" disabled>
                     </div>
                  </div>
                  <div class="mail-form-row no-border">
                     <div class="auto-full">
                        <label>Buying Requirements:</label>
                        <div class="buyer-scroller-n">
                           <div class="custom-editer">
                              <ul class="tools" style="display: none">
                                <li>
                                  <input type="button" value="B" data-cmd="bold"/>
                                </li>
                                <li>
                                  <input type="button" value="U" data-cmd="underline">
                                </li>
                              </ul>
                              <div class="buyer-scroller">
                                <div class="buying-requirements"><?php echo $lead_data->buying_requirement; ?></div>
                              </div>
                           </div>
                           <!-- <div class="buyer-scroller">
                              <div class="buying-requirements" contenteditable="false" id="lead_requirement_div_text"><?php echo $lead_data->buying_requirement; ?></div>
                           </div> -->
                        </div>                        
                     </div>
                     <?php if($lead_data->attach_file){ ?>
                     <small>Attachment: <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download/<?php echo base64_encode($lead_data->attach_file); ?>"><?php echo $lead_data->attach_file; ?></a></small>
                     <?php } ?>
                  </div>
                  <div class="mail-form-row equal-row no-border">
                     <div class="auto-row">
                        <label>Contact Person:</label>
                        <input type="text" class="mail-input auto-w" value="<?php echo $lead_data->cus_contact_person; ?>" disabled>
                     </div>
                     <div class="auto-row">
                        <label>Email:</label>
                        <input type="text" class="mail-input auto-w" value="<?php echo $lead_data->cus_email; ?>" disabled>
                     </div>
                     <div class="auto-row">
                        <label>Mobile:</label>
                        <input type="text" class="mail-input auto-w" value="<?php echo ($lead_data->cus_mobile)?$lead_data->cus_mobile:'N/A'; ?>" disabled>
                     </div>
                  </div>
                  <div class="mail-form-row equal-row no-border">
                     <div class="auto-row">
                        <label>Company:</label>
                        <input type="text" class="mail-input auto-w" value="<?php echo $lead_data->cus_company_name; ?>" disabled>
                     </div>
                     <div class="auto-row">
                        <label>Country:</label>
                        <input type="text" class="mail-input auto-w" value="<?php echo $lead_data->cus_country; ?>" disabled>
                     </div>
                     <div class="auto-row">
                        <label>Source:</label>
                        <input type="text" class="mail-input auto-w" value="<?php echo $lead_data->source_name; ?>" disabled>
                     </div>
                  </div>
                  <div class="mail-form-row blue-label footer-shadow">
                     <div class="auto-row">
                        <label>Next Follow-up:</label>
                        <input type="text" class="mail-input input_date editable-field" value="<?php if($lead_data->followup_date!='0000-00-00'){echo date_db_format_to_display_format($lead_data->followup_date);}else{echo'N/A';} ?>" disabled name="lead_follow_up_date" id="lead_follow_up_date">
                        
                     </div>
                     <?php /* ?>
                     <div class="auto-row">
                        <label>Follow-up Type:</label>
                        <select class="mail-input editable-field" disabled name="lead_follow_up_type" id="lead_follow_up_type">
                           <option value="">Select</option>
                           <?php foreach($next_follow_by AS $row){ ?>
                           <option value="<?php echo $row['id']; ?>" <?php if($row['id']==$lead_data->followup_type_id){echo'SELECTED';} ?>><?php echo $row['name']; ?></option>
                           <?php } ?>

                        </select>
                     </div>
                     <?php */ ?>
                     <div class="auto-row">
                        <label>Assign to:</label>
                        <select class="mail-input editable-field" disabled>
                           <option><?php echo $lead_data->user_name; ?></option>
                        </select>
                     </div>
                     <div class="auto-row">
                        <label>Stage:</label>
                        <input type="text" class="mail-input editable-field" value="<?php echo $lead_data->current_stage; ?>" disabled>
                     </div>
                     <div class="auto-row">
                        <label>Status</label>
                        <input type="text" class="mail-input editable-field" value="<?php echo $lead_data->current_status; ?>" disabled>
                     </div>
                  </div>
               </div>
               <div class="mail-form-row not-active">
                  <a href="JavaScript:void(0);" class="lead-btn orange pull-right update-lead" id="save_lead_confirm">Save Changes</a>
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
        buttonText: "Select date"
      });

   });
</script>
