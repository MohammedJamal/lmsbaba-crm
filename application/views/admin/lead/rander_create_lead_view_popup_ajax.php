<input type="hidden" name="thread_id" id="thread_id" value="<?php echo $thread_id; ?>">
<input type="hidden" name="com_company_id" id="com_company_id" value="<?php echo $customer_id; ?>">
<input type="hidden" name="com_designation" id="com_designation" value="<?php echo $customer['designation']; ?>">
<input type="hidden" name="com_alternate_email" id="com_alternate_email" value="<?php echo $customer['alt_email']; ?>">
<input type="hidden" name="com_mobile_country_code" id="com_mobile_country_code" value="<?php echo $customer['mobile_country_code']; ?>">
<input type="hidden" name="com_alt_mobile_country_code" id="com_alt_mobile_country_code" value="<?php echo $customer['alt_mobile_country_code']; ?>">
<input type="hidden" name="com_alternate_mobile" id="com_alternate_mobile" value="<?php echo $customer['alt_mobile']; ?>">
<input type="hidden" name="com_landline_country_code" id="com_landline_country_code" value="<?php echo $customer['landline_country_code']; ?>">
<input type="hidden" name="com_landline_std_code" id="com_landline_std_code" value="<?php echo $customer['landline_std_code']; ?>">
<input type="hidden" name="landline_number" id="landline_number" value="<?php echo $customer['landline_number']; ?>">
<input type="hidden" name="office_std_code" id="office_std_code" value="" />
      <input type="hidden" name="office_phone" id="office_phone" value="" />
<input type="hidden" name="com_address" id="com_address" value="<?php echo $customer['address']; ?>">
<input type="hidden" name="com_state_id" id="com_state_id" value="<?php echo $customer['state']; ?>">
<input type="hidden" name="com_city_id" id="com_city_id" value="<?php echo $customer['city']; ?>">
<input type="hidden" name="com_zip" id="com_zip" value="<?php echo $customer['zip']; ?>">
<input type="hidden" name="com_website" id="com_website" value="<?php echo $customer['website']; ?>">
<input type="hidden" name="com_short_description" id="com_short_description" value="<?php echo $customer['short_description']; ?>">
<input type="hidden" name="lead_requirement" id="lead_requirement">
<input type="hidden" name="lead_follow_up_type" id="lead_follow_up_type" value="2">
<?php

if($customer_id>0)
{
  $tmp_com_email=$customer['email'];
  $tmp_com_contact_person=$customer['contact_person'];
}
else
{
  $tmp_com_email=$customer_email;
  $tmp_com_contact_person=$customer_name;
}
?>
<div class="modal-dialog" role="document">
  <div class="modal-content">
     <div class="modal-body">
        <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
           <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
              <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
           </svg>
        </a>
        <div class="lead-loop new-mode">
           <div class="lead-top">
              <div class="mail-form-row max-width">
                 <div class="auto-row">
                    <label>Enquiry Date:</label>
                    <input type="text" class="mail-input input_date editable-field" value="<?php echo date_db_format_to_display_format(date("Y-m-d")); ?>" name="lead_enq_date" id="lead_enq_date">
                    
                 </div>
              </div>
              <div class="mail-form-row">
                 <div class="show-more-holder">
                    <a href="javascript:void(0);" class="show-more"><img src="<?php echo assets_url(); ?>images/mail-dots.png"></a>
                    <div class="arrow_box">
                           <div class="form-group">
                              <label>Company:</label> <?php echo $customer['company_name']; ?>
                           </div>
                           <div class="form-group">
                              <label>Name:</label> <?php echo $tmp_com_contact_person; ?>
                           </div>
                           <div class="form-group">
                              <label>Email:</label> <?php echo $tmp_com_email; ?>
                           </div>
                           <div class="form-group">
                              <label>Mobile:</label> <?php echo ($customer['mobile'])?$customer['mobile']:'N/A'; ?>
                           </div>
                           <div class="form-group">
                              <label>Address:</label> <?php echo ($customer['address'])?$customer['address']:'N/A';?>
                           </div>
                           <div class="form-group">
                              <label>City:</label> <?php echo ($customer['city_name'])?$customer['city_name']:'N/A'; ?>
                           </div>
                           <div class="form-group">
                              <label>State:</label> <?php echo ($customer['state_name'])?$customer['state_name']:'N/A';?>
                           </div>
                           <div class="form-group">
                              <label>Country:</label> <?php echo ($customer['country_name'])?$customer['country_name']:'N/A'; ?>
                           </div>
                        </div>
                 </div>
                 <div class="full-row-flex">
                    <label class="w-72">Lead Title:</label>
                    <input type="text" class="mail-input editable-field full-width-72" value="<?php echo $start_mail['subject']; ?>" name="lead_title" id="lead_title" >
                 </div>
              </div>
              <div class="mail-form-row no-border">
                 <div class="auto-full">
                    <label>Buying Requirements:</label>
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
                            <?php echo $last_mail['body_html']; ?>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                    
                 </div>
              </div>
              <div class="mail-form-row equal-row no-border">
                 <div class="auto-row">
                    <label>Contact Person:</label>
                    <input type="text" class="mail-input auto-w" value="<?php echo $tmp_com_contact_person; ?>" <?php echo ($tmp_com_contact_person)?'':''; ?> name="com_contact_person" id="com_contact_person">
                 </div>
                 <div class="auto-row">
                    <label>Email:</label>
                    <input type="text" class="mail-input auto-w" value="<?php echo $tmp_com_email; ?>" <?php echo ($tmp_com_email)?'readonly':''; ?> name="com_email" id="com_email">
                 </div>
                 <div class="auto-row">
                    <label>Mobile:</label>
                    <input type="text" class="mail-input auto-w only_natural_number" value="<?php echo $customer['mobile']; ?>" <?php echo ($customer['mobile'])?'':''; ?> name="com_mobile" id="com_mobile" maxlength="10">
                 </div>
              </div>
              <div class="mail-form-row equal-row no-border">
                 <div class="auto-row">
                    <label>Company:</label>
                    <input type="text" class="mail-input auto-w" value="<?php echo $customer['company_name']; ?>" <?php echo ($customer['company_name'])?'':''; ?> name="com_company_name" id="com_company_name">
                 </div>
                 <div class="auto-row">
                    <label>Country:</label>
                    <?php if($customer['country_id']>0){ ?>
                      <input type="text" class="mail-input auto-w" value="<?php echo $customer['country_name']; ?>" readonly>

                      <input type="hidden" name="com_country_id" id="com_country_id" value="<?php echo $customer['country_id']; ?>">
                    <?php }else{ ?>                        
                        <select class="mail-input editable-field" name="com_country_id" id="com_country_id">
                            <option value="">Select</option>
                            <?php foreach($country_list as $country_data)
                            {
                              ?>
                              <option value="<?php echo $country_data->id;?>" <?php if($customer['country_id']==$country_data->id){echo"SELECTED";} ?>><?php echo $country_data->name;?></option>
                              <?php
                            }
                            ?>
                        </select>
                    <?php } ?>
                    
                 </div>
                 <div class="auto-row">
                    <label>Source:</label>
                    <?php if($customer['source_id']>0){ ?>
                      <input type="text" class="mail-input auto-w" value="<?php echo $customer['source_name']; ?>" readonly>
                      
                      <input type="hidden" value="<?php echo $customer['source_id']; ?>" name="com_source_id" id="com_source_id">
                    <?php }else{ ?>
                        
                        <select class="mail-input editable-field" name="com_source_id" id="com_source_id">
                          <option value="">Select</option>
                              <?php foreach($source_list as $source)
                              {
                                ?>
                                <option value="<?php echo $source->id;?>" <?php if($selected_source==$source->id){echo"SELECTED";} ?> ><?php echo $source->name;?></option>
                                <?php
                              }
                              ?>                            
                            </select>
                    <?php } ?>
                 </div>
              </div>
              <div class="mail-form-row blue-label footer-shadow">
                 <div class="auto-row">
                    <label>Next Follow-up:</label>
                    <input type="text" class="mail-input input_date editable-field" value="<?php echo date_db_format_to_display_format(date("Y-m-d")); ?>" name="lead_follow_up_date" id="lead_follow_up_date">
                    
                 </div>
                 
                 <?php /* ?>
                 <div class="auto-row">
                    <label>Follow-up Type:</label>
                    <select class="mail-input editable-field" name="lead_follow_up_type" id="lead_follow_up_type">
                           <option value="">Select</option>
                           <?php foreach($next_follow_by AS $row){ ?>
                           <option value="<?php echo $row['id']; ?>" <?php if($row['id']==$lead_data->followup_type_id){echo'SELECTED';} ?>><?php echo $row['name']; ?></option>
                           <?php } ?>

                        </select>
                 </div>
                 <?php */ ?>
                 <div class="auto-row">
                    <label>Assign to:</label>
                    <?php 
                    if($customer['assigned_user_id']>0)
                    {
                    ?>
                      <input type="hidden" name="assigned_user_id" name="assigned_user_id" value="<?php echo $customer['assigned_user_id']; ?>">
                    <?php
                    }
                    ?>
                    <select class="mail-input editable-field" id="assigned_user_id" name="assigned_user_id" <?php if($customer['assigned_user_id']>0){echo"disabled='disabled'";}else{} ?>>
                       <option value="">Select</option>
                          <?php
                          foreach($user_list as $user_data)
                          {
                            //if($user_data['id']!=1)
                            //{
                            ?>
                            <option value="<?php echo $user_data['id']?>" <?php if($user_id==$user_data['id']){echo'SELECTED';} ?>><?php echo $user_data['name'].' (Emp. ID: '.$user_data['id'].')';?></option>                    
                            <?php
                            //}
                          }
                          ?> 
                    </select>
                 </div>
                 <div class="auto-row">
                    <label>Stage:</label>
                    <input type="text" class="mail-input editable-field" value="Pending">
                 </div>
                 <div class="auto-row">
                    <label>Status</label>
                    <input type="text" class="mail-input editable-field" value="Hot">
                 </div>
              </div>
           </div>
           <div class="mail-form-row">
              <!-- <a href="javascript:void(0)" class="lead-btn orange pull-right " id="create_lead_confirm">Create Lead</a> -->
              <button type="button" class="btn lead-btn orange pull-right" id="create_lead_confirm">Create Lead</button>
           </div>
        </div>
     </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/common_functions.js"></script>
<!-- <script src="<?php echo base_url();?>assets/js/custom/lead/edit.js"></script> -->
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
        buttonText: "Select date"
      });

   });
</script>
