<?php //print_r($gmail_data); ?>
<form id="frmLeadAdd" name="frmLeadAdd" onsubmit="return false;">
    <input type="hidden" name="com_company_id" id="com_company_id" value="">
    <h5>LEAD DETAILS</h5>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Lead Title<span class="text-danger">*</span>:</label>
                <input type="text" class="form-control" name="lead_title" id="lead_title" placeholder="Lead Title" value="<?php echo $gmail_data['subject']; ?>"/>
                <div class="text-danger" id="lead_title_error"></div>
            </div>
        </div>
        
        
        <div class="col-md-12">
            <div class="form-group">
                <label>Describe Requirements<span class="text-danger">*</span>:</label>
                <textarea rows="5" cols="5" class="form-control basic-wysiwyg-editor" name="lead_requirement" id="lead_requirement" placeholder="Describe Requirements"><?php echo $gmail_data['message']; ?></textarea>
                <div class="text-danger" id="lead_requirement_error"></div>
            </div>
        </div>
        
      
        <div class="col-md-12">
            <div class="form-group">
                <label>Attach File (if any):</label>                
                <input type="file" name="lead_attach_file" id="lead_attach_file">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label>Enquiry date:</label>
            
                <input type="text" class="form-control display_date" name="lead_enq_date" id="lead_enq_date" placeholder="Enquiry date" value="<?php echo date_db_format_to_display_format(date('Y-m-d')); ?>" readonly="true" />
                <div class="text-danger" id="lead_enq_date_error"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Follow-up date:</label>                
                <input type="text" class="form-control " name="lead_follow_up_date" id="lead_follow_up_date" placeholder="Follow up date" value="<?php echo date_db_format_to_display_format(date('Y-m-d')); ?>" readonly="true" />
                <div class="text-danger" id="lead_follow_up_date_error"></div>
            </div>
        </div>
        <!-- <div class="form-group row">
            <label class="col-sm-2 col-form-label">Follow up date:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="lead_follow_up_date" id="lead_follow_up_date" placeholder="Follow up date" value=""/>
                <div class="text-danger" id="lead_follow_up_date_error"></div>
            </div>
        </div> -->
        
        <div class="col-md-12">
            <div class="form-group">
            <label>Assigne to<span class="text-danger">*</span>:</label>
                <?php 
                if($assigned_user_id)
                {
                ?>
                    <input type="hidden" name="assigned_user_id" name="assigned_user_id" value="<?php echo $assigned_user_id; ?>">
                <?php
                }
                ?>
                <select class="custom-select form-control select2" name="assigned_user_id" id="assigned_user_id" required <?php if($assigned_user_id){echo"disabled='disabled'";}else{} ?>>
                    <option value="" selected="selected">Select</option>
                    <?php
                    foreach($user_list as $user_data)
                    {
                        //if($user_data['id']!=1)
                        //{
                        ?>
                        <option value="<?php echo $user_data['id']?>" <?php if($assigned_user_id==$user_data['id']){echo'SELECTED';} ?>><?php echo $user_data['name'].' (Emp. ID: '.$user_data['id'].')';?></option>                    
                        <?php
                        //}
                    }
                    ?>    
                </select>
                <div class="text-danger" id="assigned_user_id_error"></div>
            </div>
        </div>
    </div>
    <h5>COMPANY DETAILS</h5>
    <div class="row">

        
        <div class="col-md-12">
            <div class="form-group">
            <label>Company Name<span class="text-danger">*</span>:</label>
        
                <input type="text" class="form-control" name="com_company_name" id="com_company_name" placeholder="Company Name" value=""   />
                <div class="text-danger" id="com_company_name_error"></div>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="form-group">
                <label>Contact Person<span class="text-danger">*</span>:</label>
                <input type="text" class="form-control" name="com_contact_person" id="com_contact_person" placeholder="Contact Person" value="<?php echo $gmail_data['h_from_personal']; ?>" <?php if($gmail_data['h_from_personal']){echo"readonly='true'";}else{} ?> />
                <div class="text-danger" id="com_contact_person_error"></div>
            </div>
        </div>
        
        
         <div class="col-md-6">
            <div class="form-group ">
                <label>Designation:</label>
                
                    <input type="text" class="form-control" name="com_designation" id="com_designation" placeholder="Designation" value=""  />
                    <div class="text-danger" id="com_designation_error"></div>
                
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">
            <div class="form-group ">
                <label>Email:</label>                
                <input type="text" class="form-control" name="com_email" id="com_email" placeholder="Email" value="<?php echo $gmail_data['h_from_mailbox'].'@'.$gmail_data['h_from_host']; ?>" readonly="true" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Alternate Email:</label>
                
                <input type="text" class="form-control" name="com_alternate_email" id="com_alternate_email" placeholder="Alternate Email" value=""  />
                <div class="text-danger" id="com_alternate_email_error"></div>            
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">

            <div class=" form-group">
                <label>Mobile:</label>
                <div class="clear"></div>
               <div class="col-sm-3 p-0 border-right-0">
                    <input type="text" class="form-control" name="com_mobile_country_code" id="com_mobile_country_code" placeholder="Country Code" value=""  />
                </div>
                <div class="col-sm-9 p-0">
                    

                    <input type="text" class="form-control" name="com_mobile" id="com_mobile" placeholder="Mobile" value=""  />
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Alternate Mobile:</label>
                <div class="clear"></div>
                <div class="col-sm-3 p-0 border-right-0">
                    <input type="text" class="form-control" name="com_alt_mobile_country_code" id="com_alt_mobile_country_code" placeholder="Country Code" value=""  />
                </div>
                <div class="col-sm-9 p-0">
                    <input type="text" class="form-control" name="com_alternate_mobile" id="com_alternate_mobile" placeholder="Alternate Mobile" value=""  />
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">
            <div class="form-group" style="display:inline-block; width:100%;">
            <label>Landline Number:</label>
            <div>
            <div class="col-sm-2 p-0 border-right-0">
                <input type="text" class="form-control" name="com_landline_country_code" id="com_landline_country_code" placeholder="Country Code" value=""  />
            </div>
            <div class="col-sm-2 p-0 border-right-0">
                <input type="text" class="form-control" name="com_landline_std_code" id="com_landline_std_code" placeholder="Std Code" value=""  />
            </div>
            <div class="col-sm-8 p-0">
                <input type="text" class="form-control" name="landline_number" id="landline_number" placeholder="Landline Number" value=""  />
            </div>
            </div>
        </div>
        </div>
        <div style="clear: both;"></div>
        
       
       <div class="col-md-12">
            <div class="form-group">
            <label>Address:</label>
                
            <input type="text" class="form-control" name="com_address" id="com_address" placeholder="Address" value=""  />
                
            </div>
        
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">

            <div class="form-group">
                <label>Country<span class="text-danger">*</span>:</label>
                
                    <select class="custom-select form-control" name="com_country_id" id="com_country_id" onchange="GetStateList(this.value)" <?php if($tmp_com_country_id){echo"disabled";} ?>>
                          <option value="">Select</option>
                          <?php foreach($country_list as $country_data)
                          {
                            ?>
                            <option value="<?php echo $country_data->id;?>"><?php echo $country_data->name;?></option>
                            <?php
                          }
                          ?>
                    </select>
                    <input type="hidden" name="com_existing_country" id="com_existing_country"  value="" />
                    <div class="text-danger" id="com_country_id_error"></div>
                
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>State:</label>

                    <select class="custom-select form-control" name="com_state_id" id="com_state_id" onchange="GetCityList(this.value)">
                      <option value="">Select</option>
                    </select>
                    <div class="text-danger" id="com_state_id_error"></div>
                    <input type="hidden" name="com_existing_state" id="com_existing_state"  value="" />

            
            </div>
        </div>
        <div style="clear: both;"></div>
       <div class="col-md-6">
            <div class="form-group ">
                <label>City:</label>            
                <select class="custom-select form-control" name="com_city_id" id="com_city_id" >
                <option value="">Select</option>
                </select>
                <input type="hidden" name="com_existing_city" id="com_existing_city"  value="" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group ">
                <label>Zip:</label>                
                <input type="text" class="form-control" name="com_zip" id="com_zip" placeholder="Zip" value=""  />
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Website:</label>        
                <input type="text" class="form-control" name="com_website" id="com_website" placeholder="Website" value=""  />            
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Source<span class="text-danger">*</span>:</label>                  
                <select class="custom-select form-control" name="com_source_id" id="com_source_id" <?php if($tmp_com_source_id){echo"disabled='true'";}else{} ?>>
                    <option value="">Select</option>
                      <?php foreach($source_list as $source)
                      {
                        ?>
                        <option value="<?php echo $source->id;?>" <?php if($tmp_com_source_id==$source->id){echo"SELECTED";} ?> ><?php echo $source->name;?></option>
                        <?php
                      }
                      ?>                                  
                </select>
                <input type="hidden" name="com_existing_source" id="com_existing_source"  value="<?php echo $tmp_com_source_id; ?>" />
                <div class="text-danger" id="com_source_id_error"></div>
                                        
            </div>
        </div>
        <div style="clear: both;"></div>
        
        <div class="col-md-12">
            <div class="form-group">
            <label>Company Short Description:</label>                
            <textarea rows="5" cols="5" class="form-control" name="com_short_description" id="com_short_description" placeholder="Company Short Description" <?php if($tmp_com_short_description){echo"readonly='true'";}else{} ?>><?php echo $tmp_com_short_description; ?></textarea>
            </div>
        </div>  

        <div class="col-md-12">
            
            <p id="sub_form" class="file margin_top_right_arrow " >
            <input type="submit" value="" id="add_to_lead_submit_confirm" />
            <label for="file" class="btn_enabled"><span class="btn-text">Submit<span> 
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </label>
             
            </p>
                
        </div>      
    </div>
    
</form>
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
    //  var geekss = e.target.files[0].name; 
    //  //alert(geekss + ' is the selected file.');
    //  $(this).parent().find('label').css({'display':'none'});
    //  $(this).parent().find('.fname_holder span').html(geekss);
    //  $(this).parent().find('.fname_holder').css({'display':'block'});
    //  //$(this).parent().find('label').text(geekss)

    // }); 
    //////
    // $(".custom_upload .fname_holder a").click(function(event){
    //  event.preventDefault();
    //  //alert(1);
    //  $(this).parent().parent().find('label').css({'display':'inline-block'});
    //  $(this).parent().parent().find('.fname_holder span').html('');
    //  $(this).parent().parent().find('input').val('');
    //  $(this).parent().parent().find('.fname_holder').css({'display':'none'})
    // });
</script>
<script type="text/javascript">
$(document).ready(function(){
    // $(".select2").select2();
    
    $('#lead_follow_up_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: false,
        changeYear: false,
        yearRange: '-100:+0',
        minDate: 0,
    });

     $('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+0'
    });
});

function GetStateList(cont)
{
  var base_url=$("#base_url").val();
  $.ajax({
      url: base_url+"lead/getstatelist",
      type: "POST",
      data: {'country_id':cont},      
      success: function (response) 
      {
        if(response!='')
        {
          document.getElementById('com_state_id').innerHTML=response;
          $("#com_state_id").val($("#com_existing_state").val());
          GetCityList($("#com_state_id option:selected").val());
        }
          
      },
      error: function () 
      {
       //$.unblockUI();
       //alert('Something went wrong there');
       swal({
                title: "Danger!",
                text: "Something went wrong there",
                type: "danger",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
      }
  });
}

function GetCityList(state)
{
  var base_url=$("#base_url").val();
  $.ajax({
      url: base_url+"lead/getcitylist",
      type: "POST",
      data: {'state_id':state},     
      success: function (response) 
      {
        if(response!='')
        {
          document.getElementById('com_city_id').innerHTML=response;
          $("#com_city_id").val($("#com_existing_city").val())
        }
          
      },
      error: function () 
      {
       //$.unblockUI();
       //alert('Something went wrong there');
       swal({
                title: "Danger!",
                text: "Something went wrong there",
                type: "danger",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
      }
     });
}
</script>
