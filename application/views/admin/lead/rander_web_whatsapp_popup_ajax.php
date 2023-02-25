<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>">
<input type="hidden" name="cust_id" id="cust_id" value="<?php echo $cust_id; ?>">
<input type="hidden" name="is_history_update" id="is_history_update" value="N">
<input type="hidden" name="mobile_whatsapp_status" id="mobile_whatsapp_status" value="0">
<input type="hidden" name="whatsapp_number" id="whatsapp_number" value="<?php echo $lead_data->cus_mobile; ?>">
<div class="modal-dialog modal-dialog-centered" role="document" id="whatsapp_content_div">
       <div class="modal-content">         
         <div class="modal-body">            
            <div class="chat-container">
              <div class="user-bar">               
               <div class="avatar-new">
                  <!-- <div class="pp bd">M</div> -->
                  <img src="<?php echo assets_url(); ?>images/social-whatsapp-full.png">
               </div>
               <div class="name">
                  <span>Send Whatsapp message to:</span>
                  <span class="status">+<?php echo $lead_data->cus_country_code; ?> <?php echo $lead_data->cus_mobile; ?> (<?php echo $lead_data->cus_contact_person; ?>)</span>
               </div>
               <div class="actions">
                  <a href="#" class="close-what" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                       <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
                    </svg>
                   </a>          
               </div>
              </div>
              <div class="conversation">
                <div class="txt-area">
                  <label>Type your message</label>
                  <textarea id="whatsapp_txt" name="whatsapp_txt"></textarea>
                  <!-- <div class="form-group">
                    <label>Next Follow-up:</label>
                    <div class="auto-date">
                      <input type="text" class="mail-input input_date editable-field" value="08-Sep-2020">
                    </div>
                  </div> -->
                </div>
                <div class="what-action">
                  <button class="send" id="whatsapp_msg">
                    <i class="fa fa-commenting" aria-hidden="true"></i>
                  </button>
                  <button class="send" id="whatsapp_send_confirm">
                    Send Message <i class="fa fa-paper-plane" aria-hidden="true"></i>
                  </button>                  
                  &nbsp;&nbsp;&nbsp;
                  <a href="JavaScript:void(0);" class="append_product_quote_whatsapp" style="float: right;"><span class="text-label">Create Price List</span></a>
                  
                </div>
                <div class="what-templete">
                  <div class="what-scroller">
                    <ul>
                      <?php 
                      $is_quotation_exist=(count((array)$latest_opportunity)>0)?'Y':'N';
                      if(count($whatsapp_templates)){ ?>
                        <?php foreach($whatsapp_templates AS $template){ ?>
                          <li id="li_<?php echo $template['id']; ?>" <?php if($is_quotation_exist=='N'){echo ($template['id']==2 || $template['id']==3)?'style="display:none"':'';} ?>>
                            <label class="check-box-sec">
                              <input type="radio" value="" class="" name="pre_templete">
                                <span class="checkmark"></span>
                            </label>
                            <span class="use_m" data-text="<?php echo $template['description']; ?>" data-id="<?php echo $template['id']; ?>"><?php echo $template['name']; ?></span>
                            <?php if($template['user_id']>0){ ?>
                            <a href="javascript:void(0)" class="delete_whatsapp delete_template" data-id="<?php echo $template['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                          <?php } ?>
                          </li>
                        <?php } ?>
                      <?php }else{ ?>
                        <li>
                          <span class="use_m" data-text="">No template available!</span>
                        </li>
                      <?php } ?>
                      
                    </ul>
                  </div>
                  <div class="new-block">
                    <div class="add_txt">
                      <input type="text" name="t_title" id="t_title" class="msg-input" placeholder="Title">
                      <textarea name="t_desc" id="t_desc" placeholder="Description"></textarea>
                      <div style="display: inline-flex;">                   
                      <button class="send" id="save_msg">
                        Save
                      </button>
                      <button class="close-btn" id="close_msg" style="margin-left: 10px;">
                        Close
                      </button>
                    </div>
                    </div>
                    <button class="send" id="add_msg">
                      Add New Custom Message
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
       </div>
</div>

<div class="modal-dialog modal-dialog-centered" role="document" id="whatsapp_sent_confirm_div" style="display: none;">
  <div class="modal-content">         
   <div class="modal-body">            
      <div class="chat-container">              
        <div class="conversation white-bg">
          <div class="txt-area text-center">
            <label>Have you sent Whatsapp message to +<?php echo $lead_data->cus_country_code; ?> <?php echo $lead_data->cus_mobile; ?> (<?php echo $lead_data->cus_contact_person; ?>)</label>
            
          </div>
          <div class="radio-holder">
            <ul class="justify-content-new">
              <li>
                <label class="check-box-sec fl radio">
                  <input type="radio" name="is_message_sent" value="Y" checked="">
                  <span class="checkmark"></span>                  
                </label>
                Yes, I have sent Whatsapp message
              </li>
              <li>
                <label class="check-box-sec fl radio">
                  <input type="radio" name="is_message_sent" value="N">
                  <span class="checkmark"></span>                  
                </label>
                No, I did not send whatsapp message
              </li>
              <li>
                <label class="check-box-sec fl radio">
                  <input type="radio" name="is_message_sent" value="NOT_VALIDE">
                  <span class="checkmark"></span>                  
                </label>
                No, it is not a whatsapp number
              </li>
            </ul>
          </div>
          <div class="form-group">
            <?php
            $followup_date='';
            if($lead_data->followup_date!='0000-00-00 00:00:00')
            {
              $followup_date=datetime_db_format_to_display_format_ampm($lead_data->followup_date);
            }  
            ?>
            <label>Next Follow-up:</label>
            <div class="auto-date">
              <input type="text" class="mail-input next_followup_date editable-field" value="<?php echo $followup_date; ?>" name="followup_date" id="followup_date">
              <img src="<?php echo assets_url(); ?>images/cal-icon.png" style="width:19px;">
            </div>
          </div>
          <div class="what-action-new">                  
            <?php /* ?> 
            <button class="send" id="add_webwhatsapp_history">
              Yes
            </button>
            <button class="send" id="no_action">
              No
            </button>
            <button class="send" id="not_whatsapp_number">
              Submit
            </button>
            <?php */ ?>
            <button class="send" id="web_whatsapp_sent_submit">
              Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
.ui-datepicker-trigger{
  width: 25px;
}
</style>
<link rel="stylesheet" href="<?php echo base_url(); ?>styles/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
  // $('#followup_date').datepicker({
  //     dateFormat: "dd-M-yy",
  //     changeMonth: true,
  //     changeYear: true,
  //     yearRange: '-100:+5',
  //     buttonImage: "<?php echo assets_url(); ?>images/cal-icon.png",
  // });

  // $( "#followup_date" ).datepicker({
  //   dateFormat: "dd-M-yy",
  //   showOn: "both",
  //   buttonImage: "<?php echo assets_url(); ?>images/cal-icon.png",
  //   buttonImageOnly: true,
  //   buttonText: "Select date"
  // });
  $( "#followup_date" ).datetimepicker({			  
      // format:'d-M-Y H:i A',
      format:'d-M-Y g:i A',
      formatTime: 'g:i A',
      step: 15, 
      theme:'default',
      inline:false,
      lang:'en',
      minDate: '0',
      closeOnDateTimeSelect:true,
      onSelectTime : function (current_time,$input) {
          //console.log($input.attr('id')+'/'+$input.val())
          //alert($input.attr('id')+'/'+$input.val())
          // update_next_followup($input.attr('id'),$input.val());
        },
    });

  $(".what-scroller").mCustomScrollbar({
              scrollButtons:{enable:true},
              theme:"rounded-dark"
            });
</script>