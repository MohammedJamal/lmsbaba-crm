
<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $id; ?>">
<input type="hidden" name="communication_type" id="communication_type" value="6">
<input type="hidden" name="client_not_interested" id="client_not_interested" value="Y" >
<input type="hidden" name="lead_regret_reason" id="lead_regret_reason" value="">
<input type="hidden" name="lead_comments_for_mail_trail" id="lead_comments_for_mail_trail" value="">
<input type="hidden" name="general_description" id="general_description" value="">

<input type="hidden" name="po_upload_cc_to_employee[]" id="po_upload_cc_to_employee" value="">
<input type="hidden" name="po_lead_opp_id" id="po_lead_opp_id" value="">
<input type="hidden" name="po_lead_id" id="po_lead_id" value="">
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
                 
                <?php if($is_back_show=='Y'){ ?>
                 <div class="page-holder float-right mr-45">
                    <ul class="action-ul">                         
                      <li>                          	
                        <a href="JavaScript:void(0)" title="previous" class="back-bt" data-leadid="<?php echo $id; ?>" id="back_to_linked_comment_update_lead_popup3"><img src="<?php echo assets_url(); ?>images/left_black.png"> BACK</a>
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
                    <label>Select a Quotation to Proceed:</label>
                    <?php //print_r($opportunity_list); ?>
                    <ul class="list-group list-group-flush">
                    	<?php
                    	if(count($opportunity_list)){ ?>
                    	
                    	<?php foreach($opportunity_list AS $opportunity_data){ ?>
                    	<li class="update_proposal">
                          <div class="row">
                            <div class="col-md-1">
                              <label class="check-box-sec fl radio">
                              <input type="radio" name="opportunity_id" date-oppid="<?=$opportunity_data->id;?>" data-lid="<?php echo $id; ?>"> 
                              <span class="checkmark"></span>
                              </label>
                            </div>
                            <div class="col-md-11">
                              
                              <h5><?=$opportunity_data->opportunity_title;?> #<?=$opportunity_data->id;?></h5>
                              <b>Status :</b> 
                              <span class="<?php echo $opportunity_data->status_class_name; ?>">
                                  <?php echo $opportunity_data->status_name; ?>
                              </span> 
                              |
                              <b>Deal Value:</b>
                              <?php 
                              if($opportunity_data->deal_value>0){
                                  echo $opportunity_data->currency_code; ?> <?=number_format($opportunity_data->deal_value,2);
                              }
                              else
                              {
                                  echo'N/A';
                              }
                              ?>
                              |
                              <b>No. of Product(s):</b>
                              <?php
                              if($opportunity_data->product_count>0)
                              {
                                  echo $opportunity_data->product_count;
                              }
                              else
                              {
                                  echo'N/A';
                              }    
                              ?>
                              |
                              <b>Created On.:</b><?php echo date_db_format_to_display_format($opportunity_data->create_date);?> |
                              <b>Last Modified On.:</b><?php echo date_db_format_to_display_format($opportunity_data->modify_date);?>
                              <?php 
                              if($opportunity_data->tot_quotation > 0) 
                              {  
                              ?>    
                                  <p>
                                      <b>Quotation Sent On.:</b><?php echo date_db_format_to_display_format($opportunity_data->quotation_sent_on);?> |
                                      <b>Quotation Type:</b>
                                      <?php if($opportunity_data->is_extermal_quote=='Y'){
                                          $ext=($opportunity_data->file_name=='')?'( Without Quotation )':'';
                                      echo'<span class="text-danger"> <b>Custom '.$ext.'</b></span>';            
                                      } else{
                                      echo'<span class="text-danger"><b>Automated</b></span>';
                                      } ?> |

                                      <b>Purchase Order Status:</b>
                                      <?php if($opportunity_data->is_po_received=='Y'){
                                      echo'<span class="text-success"> <b>Received</b></span>';
                                      if($opportunity_data->po_file_name)
                                      {
                                          echo ' (<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/download_po/'.$opportunity_data->lowp_id.'">  Download PO <i class="fa fa-cloud-download" aria-hidden="true"></i></a> )';
                                      }
                                      
                                      } else{
                                      echo'<span class="text-danger"><b>Not Received</b></span>';
                                      } ?>
                                  </p>

                                  <?php 
                                  if($opportunity_data->is_extermal_quote=='N')
                                  { 
                                  ?>
                                  <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Preview <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a> &nbsp;|&nbsp;
                                  <?php } ?>
                                  <?php if(($opportunity_data->is_extermal_quote=='Y' && $opportunity_data->file_name!='') || $opportunity_data->is_extermal_quote=='N'){ ?>
                                  <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Download <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                  <?php 
                                  }
                              }
                              else
                              {
                                  
                              }  
                              ?>
                            </div>
                          </div>
                    		    
                            
                          <?php /* ?>
                            <b>Quotation id:</b> <?php echo $opportunity_data->id;?> | 
                            <b>Quotation Title:</b> <?php echo $opportunity_data->opportunity_title;?> | 
                            <b>Deal Value:</b> <?php echo $opportunity_data->currency_code; ?> <?=number_format($opportunity_data->deal_value,2);?> |
                            <b>No. of Product(s):</b> <?php echo $opportunity_data->product_count; ?> | 
                            <b>Created On.:</b> <?php echo date_db_format_to_display_format($opportunity_data->create_date);?> | 
                            <b>Quotation Sent On.:</b> <?php echo date_db_format_to_display_format($opportunity_data->quotation_sent_on);?> |
                            <b>Quotation Type:</b> <?php if($opportunity_data->is_extermal_quote=='Y'){
                              echo'Custom';            
                              } else{
                              echo'Automated';
                              } ?> 
                              <?php if($opportunity_data->is_extermal_quote=='N'){ ?>
                              <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" class="lead-btn grey-bg" target="_blank">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="12" height="16" viewBox="0 0 12 16">
                                       <path id="Icon_awesome-file-pdf" data-name="Icon awesome-file-pdf" d="M5.684,8a3.015,3.015,0,0,1-.062-1.466C5.884,6.537,5.859,7.691,5.684,8ZM5.631,9.478a14.42,14.42,0,0,1-.888,1.959,11.507,11.507,0,0,1,1.966-.684A4.048,4.048,0,0,1,5.631,9.478Zm-2.941,3.9c0,.025.412-.169,1.091-1.256A4.319,4.319,0,0,0,2.691,13.378ZM7.75,5H12V15.25a.748.748,0,0,1-.75.75H.75A.748.748,0,0,1,0,15.25V.75A.748.748,0,0,1,.75,0H7V4.25A.752.752,0,0,0,7.75,5ZM7.5,10.369A3.136,3.136,0,0,1,6.166,8.688a4.492,4.492,0,0,0,.194-2.006.783.783,0,0,0-1.494-.212,5.2,5.2,0,0,0,.253,2.406,29.345,29.345,0,0,1-1.275,2.681s0,0-.006,0c-.847.434-2.3,1.391-1.7,2.125A.971.971,0,0,0,2.806,14c.559,0,1.116-.563,1.909-1.931a17.813,17.813,0,0,1,2.469-.725,4.736,4.736,0,0,0,2,.609A.809.809,0,0,0,9.8,10.594c-.434-.425-1.7-.3-2.3-.225Zm4.281-7.087L8.719.219A.749.749,0,0,0,8.188,0H8V4h4V3.809A.748.748,0,0,0,11.781,3.281ZM9.466,11.259c.128-.084-.078-.372-1.337-.281C9.288,11.472,9.466,11.259,9.466,11.259Z"/>
                                     </svg>                                  
                                   Privew
                               </a>
                              <?php } ?>
                              <?php if(($opportunity_data->is_extermal_quote=='Y' && $opportunity_data->file_name!='') || $opportunity_data->is_extermal_quote=='N'){ ?>
                                <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" class="lead-btn grey-bg" target="_blank">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                 <g id="Icon_feather-download" data-name="Icon feather-download" transform="translate(-3 -3)">
                                   <path id="Path_4" data-name="Path 4" d="M17.5,22.5v3.667A1.674,1.674,0,0,1,16.056,28H5.944A1.674,1.674,0,0,1,4.5,26.167V22.5" transform="translate(0 -10.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                   <path id="Path_5" data-name="Path 5" d="M10.5,15l2.833,4.583L16.167,15" transform="translate(-2.333 -5.968)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                   <path id="Path_6" data-name="Path 6" d="M18,15.5V4.5" transform="translate(-7)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                 </g>
                               </svg>                                                               
                             Download
                         </a>
                        <?php } ?>
                        <?php */ ?>
                            <?php /* ?>  |
                            <b>Purchase Order Status:</b> <?php if($opportunity_data->is_po_received=='Y'){
                            echo'<span class="text-success"> <b>Received</b></span>';
                            echo ' (<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/download_po/'.$opportunity_data->lowp_id.'">  Download PO <i class="fa fa-cloud-download" aria-hidden="true"></i></a> )';
                            } else{
                            echo'<span class="text-danger"><b>Not Received</b></span>';
                            } ?>
                            <?php */ ?>
                    		</li>
                        <?php } ?>
                    	<?php } ?>
                    </ul>                    
                 </div>
                 
                 
        
            
            
                
               
                

               
                                       
                
                            
           
        

                
              
              <?php
              $next_followup_date='';
              if($lead_data->followup_date!='' || $lead_data->followup_date!='0000-00-00')
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
                    <input type="text" class="mail-input input_date" value="<?php echo $next_followup_date; ?>" name="next_followup_date" id="next_followup_date" disabled>
                    <span class="cal-icon"><img src="<?php echo assets_url(); ?>images/cal-icon.png"></span>
                  </div>
                 </div>
                 <?php /* ?>
                 <div class="auto-row">
                    <label>Follow-up Type:</label>
                    <select class="mail-input editable-field" name="next_followup_type_id" id="next_followup_type_id" disabled>
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
              
              <a href="JavaScript:void(0)" class="lead-btn orange pull-right update-lead" id="continue_po_upload">Continue</a>
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

