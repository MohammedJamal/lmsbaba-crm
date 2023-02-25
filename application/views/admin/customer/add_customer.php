<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?> 
   <link rel="stylesheet" href="<?=assets_url();?>plugins/jquery-ui/jquery-ui.min.css">

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
  </head>
  <body>
    <div class="app full-width expanding">    
        <div class="off-canvas-overlay" data-toggle="sidebar"></div>
        <div class="sidebar-panel">       
          <?php $this->load->view('admin/includes/left-sidebar'); ?>
        </div> 
        <div class="app horizontal top_hader_dashboard">
              <?php $this->load->view('admin/includes/header'); ?>
        </div>      

        <div class="main-panel">
            <div class="min_height_dashboard"></div>
            <div class="main-content">              
                <div class="content-view"> 
                  <div class="form_bg">            
                    <div class="middle_form">

                      <form id="form" name="form" method="post" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/add/">
                        <div class="padding_35">
                          <h5 class="lead_board p-0 mb-35">Company Search</h5>
                            <div class="form-group row">
                              <label class="col-sm-2 col-form-label label-text">E-mail</label>
                              <div class="col-sm-8"><input type="text" class="form-control" name="email" id="email" placeholder="E-mail" value="<?php echo $email; ?>" /></div>
                            </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label label-text">Mobile</label>
                            <div class="col-sm-8"><input type="text" class="form-control only_natural_number" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $mobile; ?>" maxlength="10"  /></div>
                              <div class="col-sm-2 no_display" id="loader"><img src="<?=assets_url();?>images/fetch.gif" alt="" /></div>
                            </div>
                                          
                           
                  </div>               
                                  
                          <div class="col-sm-10">
                            <p id="reset_form" class="file margin_top_right_arrow">
                              <input type="submit" value="" class="" id="search_submit_confirm" />
                              <label for="file" class="serach-btn">Search </label>
                            </p>
                          </div>
                      </form>

                    </div>
                  </div>  

                  <?php
                  if(count($company)>1)
                  {
                  ?>
                  <div class="form_bg">            
              <div class="middle_form">
                <div style="clear: both;"></div>
                <div class="padding_35">
                    <h5 class="lead_board p-0 mb-35">Companies Found</h5>               
                      <table class="table table-bordered">
                        <tr>
                          <th class="text-center">Tag With</th>
                          <th>Company Name</th>
                          <th class="text-center">Email Matched</th>
                          <th class="text-center">Mobile Matched</th>
                          <th class="text-center">Lead(s)</th>
                          <th class="text-center">Details</th>
                        </tr>
                        <?php foreach($company as $c){ ?>
                        <tr>
                          <td align="center">
                            
                            <label class="check-box-sec rounded">
                                  <input type="radio" name="tag_company_id" value="<?php echo $c['id']; ?>" class="tag_company_id">
                                  <span class="checkmark"></span>
                                </label>
                          </td>
                          <td><?php echo $c['company_name']; ?></td>
                          <td align="center">
                            <?php echo ($c['email']==$email)?'Yes':'No'; ?>
                          </td>
                          <td align="center">
                            <?php echo ($c['mobile']==$mobile)?'Yes':'No'; ?>
                          </td>
                          <td align="center"><?php echo $c['lead_count']; ?></td>
                          <td align="center"><a href="JavaScript:void(0);" data-id="<?php echo $c['id']; ?>" class="view_company_detail">View</a></td>
                        </tr>
                        <?php } ?>
                      </table>                    
                  </div>
              </div>
              </div>
              <?php                             
                  }

                  //echo count($company);

                  if(count($company)==0)
                  {             
                    $tmp_company_id='';
                    $tmp_com_company_name='';
                    $tmp_com_contact_person='';
                    $tmp_com_designation='';
                    $tmp_email=$email;
                    $tmp_com_alternate_email='';
                    $tmp_mobile=$mobile;
                    $tmp_com_alternate_mobile='';
                    $tmp_com_address='';
                    $tmp_com_country_id='';
                    $tmp_com_state_id='';
                    $tmp_com_city_id='';
                    $tmp_com_zip='';
                    $tmp_com_website='';
                    $tmp_com_source_id='';
                    $tmp_com_short_description='';
                  }
                  else if(count($company)==1)
                  { 
                    $tmp_company_id=$company[0]['id'];
                    $tmp_com_company_name=$company[0]['company_name'];
                    $tmp_com_contact_person=$company[0]['contact_person'];
                    $tmp_com_designation=$company[0]['designation'];
                    $tmp_email=$company[0]['email'];
                    $tmp_com_alternate_email=$company[0]['alt_email'];
                    $tmp_mobile_country_code=$company[0]['mobile_country_code'];
                    $tmp_mobile=$company[0]['mobile'];
                    $tmp_com_alt_mobile_country_code=$company[0]['alt_mobile_country_code'];
                    $tmp_com_alternate_mobile=$company[0]['alt_mobile'];
                    $tmp_com_landline_country_code=$company[0]['landline_country_code'];
                    $tmp_com_landline_std_code=$company[0]['landline_std_code'];
                    $tmp_com_landline_number=$company[0]['landline_number'];
                    $tmp_com_address=$company[0]['address'];
                    $tmp_com_country_id=$company[0]['country_id'];
                    $tmp_com_state_id=$company[0]['state'];
                    $tmp_com_city_id=$company[0]['city'];
                    $tmp_com_zip=($company[0]['zip']!=0)?$company[0]['zip']:'';
                    $tmp_com_website=$company[0]['website'];
                    $tmp_com_source_id=$company[0]['source_id'];
                    $tmp_com_short_description=$company[0]['short_description'];
                  }
                  else
                  {
                    $tmp_company_id='';
                    $tmp_com_company_name='';
                    $tmp_com_contact_person='';
                    $tmp_com_designation='';
                    $tmp_email='';
                    $tmp_com_alternate_email='';
                    $tmp_mobile_country_code='';
                    $tmp_mobile='';
                    $tmp_com_alt_mobile_country_code='';
                    $tmp_com_alternate_mobile='';
                    $tmp_com_landline_country_code='';
                    $tmp_com_landline_std_code='';
                    $tmp_com_landline_number='';
                    $tmp_com_address='';
                    $tmp_com_country_id='';
                    $tmp_com_state_id='';
                    $tmp_com_city_id='';
                    $tmp_com_zip='';
                    $tmp_com_website='';
                    $tmp_com_source_id='';
                    $tmp_com_short_description='';
                  }
                  ?>
                  <?php if($is_add_a_new_lead_form_show=='Y'){ ?>

                  <div class="form_bg">            
                    <div class="middle_form">

                      <form id="frmLeadAdd" name="frmLeadAdd" method="post" action="" class="new-lead-sec-from">
                        
                  <input type="hidden" name="com_company_id" id="com_company_id" value="<?php echo $tmp_company_id; ?>">

                  <div style="clear: both;"></div>
                  <div class="padding_35">                      

                            <div class="form-group">
                            <div class="new-lead-sec">
                                <h4 class="heading-text-hd"> COMPANY DETAILS</h4> 
                            </div>
                          </div>
                              <div class="row">
                            <div class="form-group col-md-6">
                    <label>Company Name<span class="text-danger">*</span>:</label>
                  
                      <input type="text" class="form-control" name="com_company_name" id="com_company_name" placeholder="Company Name" value="<?php echo $tmp_com_company_name; ?>" <?php if($tmp_com_company_name){echo"readonly='true'";}else{} ?>  />
                      <div class="text-danger" id="com_company_name_error"></div>
                    
                            </div>

                            <div class="form-group col-md-6">
                    <label>Contact Person<span class="text-danger">*</span>:</label>
                  
                      <input type="text" class="form-control" name="com_contact_person" id="com_contact_person" placeholder="Contact Person" value="<?php echo $tmp_com_contact_person; ?>" <?php if($tmp_com_contact_person){echo"readonly='true'";}else{} ?> />
                      <div class="text-danger" id="com_contact_person_error"></div>
                    
                            </div>
                              </div>
                               <div class="row">
                            <div class="form-group col-md-6">
                    <label>Designation:</label>
                    
                      <input type="text" class="form-control" name="com_designation" id="com_designation" placeholder="Designation" value="<?php echo $tmp_com_designation; ?>" <?php if($tmp_com_designation){echo"readonly='true'";}else{} ?> />
                      <div class="text-danger" id="com_designation_error"></div>
                    
                            </div>

                            <div class="form-group col-md-6">
                    <label>Email:</label>
                    
                      <input type="text" class="form-control" name="com_email" id="com_email" placeholder="Email" value="<?php echo $tmp_email; ?>" <?php if($tmp_email){echo"readonly='true'";}else{} ?> />
                    
                            </div>
                              </div>

                            <div class="form-group">
                    <label>Alternate Email:</label>
                    
                      <input type="text" class="form-control" name="com_alternate_email" id="com_alternate_email" placeholder="Alternate Email" value="<?php echo $tmp_com_alternate_email; ?>" <?php if($tmp_com_alternate_email){echo"readonly='true'";}else{} ?> />
                      <div class="text-danger" id="com_alternate_email_error"></div>
                  
                            </div>
                              
                              <div class="row">

                            <div class="col-md-6 form-group">
                    <label>Mobile:</label>
                                  <div class="clear"></div>
                     <div class="col-sm-3 p-0 border-right-0">
                      <input type="text" class="form-control" name="com_mobile_country_code" id="com_mobile_country_code" placeholder="Country Code" value="<?php echo $tmp_mobile_country_code; ?>" <?php if($tmp_mobile_country_code){echo"readonly='true'";}else{} ?> />
                                  </div>
                    <div class="col-sm-9 p-0">
                      

                      <input type="text" class="form-control" name="com_mobile" id="com_mobile" placeholder="Mobile" value="<?php echo $tmp_mobile; ?>" <?php if($tmp_mobile){echo"readonly='true'";}else{} ?> />
                    </div>
                            </div>

                            <div class="col-md-6 form-group">
                    <label>Alternate Mobile:</label>
                                  <div class="clear"></div>
                    <div class="col-sm-3 p-0 border-right-0">
                      <input type="text" class="form-control" name="com_alt_mobile_country_code" id="com_alt_mobile_country_code" placeholder="Country Code" value="<?php echo $tmp_com_alt_mobile_country_code; ?>" <?php if($tmp_com_alt_mobile_country_code){echo"readonly='true'";}else{} ?> />
                    </div>
                    <div class="col-sm-9 p-0">
                      <input type="text" class="form-control" name="com_alternate_mobile" id="com_alternate_mobile" placeholder="Alternate Mobile" value="<?php echo $tmp_com_alternate_mobile; ?>" <?php if($tmp_com_alternate_mobile){echo"readonly='true'";}else{} ?> />
                    </div>
                            </div>
                              </div>

                            <div class="form-group" style="display:inline-block; width:100%;">
                    <label>Landline Number:</label>
                                  <div>
                    <div class="col-sm-2 p-0 border-right-0">
                      <input type="text" class="form-control" name="com_landline_country_code" id="com_landline_country_code" placeholder="Country Code" value="<?php echo $tmp_com_landline_country_code; ?>" <?php if($tmp_com_landline_country_code){echo"readonly='true'";}else{} ?> />
                    </div>
                    <div class="col-sm-2 p-0 border-right-0">
                      <input type="text" class="form-control" name="com_landline_std_code" id="com_landline_std_code" placeholder="Std Code" value="<?php echo $tmp_com_landline_std_code; ?>" <?php if($tmp_com_landline_std_code){echo"readonly='true'";}else{} ?> />
                    </div>
                    <div class="col-sm-8 p-0">
                      <input type="text" class="form-control" name="landline_number" id="landline_number" placeholder="Landline Number" value="<?php echo $tmp_com_landline_number; ?>" <?php if($tmp_com_landline_number){echo"readonly='true'";}else{} ?> />
                    </div>
                                  </div>
                            </div>
                              
                             
                             <div class="row">
                                <div class="col-md-12 form-group">
                    <label>Address:</label>
                    
                      <input type="text" class="form-control" name="com_address" id="com_address" placeholder="Address" value="<?php echo $tmp_com_address; ?>" <?php if($tmp_com_address){echo"readonly='true'";}else{} ?> />
                    
                            </div>
                              
                              </div>
                          
                              <div class="row">

                            <div class="form-group col-md-6">
                    <label>Country<span class="text-danger">*</span>:</label>
                    
                      <select class="custom-select form-control" name="com_country_id" id="com_country_id" onchange="GetStateList(this.value)" <?php if($tmp_com_country_id){echo"disabled";} ?>>
                                    <option value="">Select</option>
                                    <?php foreach($country_list as $country_data)
                                    {
                                      ?>
                                      <option value="<?php echo $country_data->id;?>" <?php if($tmp_com_country_id==$country_data->id){echo"SELECTED";} ?>><?php echo $country_data->name;?></option>
                                      <?php
                                    }
                                    ?>
                              </select>
                              <input type="hidden" name="com_existing_country" id="com_existing_country"  value="<?php echo $tmp_com_country_id; ?>" />
                              <div class="text-danger" id="com_country_id_error"></div>
                    
                            </div>

                            <div class="form-group col-md-6">
                    <label>State:</label>
              
                      <select class="custom-select form-control" name="com_state_id" id="com_state_id" onchange="GetCityList(this.value)" <?php if($tmp_com_state_id){echo"disabled";} ?>>
                                  <option value="">Select</option>
                                </select>
                                <div class="text-danger" id="com_state_id_error"></div>
                      <input type="hidden" name="com_existing_state" id="com_existing_state"  value="<?php echo $tmp_com_state_id; ?>" />

                  
                            </div>
                              </div>
                             <div class="row">
                            <div class="form-group col-md-6">
                    <label>City:</label>
                  
                      <select class="custom-select form-control" name="com_city_id" id="com_city_id" <?php if($tmp_com_city_id){echo"disabled";} ?>>
                                  <option value="">Select</option>
                                </select>
                      <input type="hidden" name="com_existing_city" id="com_existing_city"  value="<?php echo $tmp_com_city_id; ?>" />
                  
                            </div>

                            <div class="form-group col-md-6">
                    <label>Zip:</label>
                    
                      <input type="text" class="form-control" name="com_zip" id="com_zip" placeholder="Zip" value="<?php echo $tmp_com_zip; ?>" <?php if($tmp_com_zip){echo"readonly='true'";}else{} ?> />
                    
                            </div>
                              </div>
                              <div class="row">
                            <div class="form-group col-md-6">
                    <label>Website:</label>
                  
                      <input type="text" class="form-control" name="com_website" id="com_website" placeholder="Website" value="<?php echo $tmp_com_website; ?>" <?php if($tmp_com_website){echo"readonly='true'";}else{} ?> />
                    
                            </div>

                            <div class="form-group col-md-6">
                    <label>Source<span class="text-danger">*</span>:</label>
                      
                    <?php 
                    if($tmp_com_source_id){
                    }else{echo '<a href="JavaScript:void(0)" class="add_source_popup">Add Source</a>';} 
                    ?>  
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
                            <div class="form-group">
                    <label>Company Short Description:</label>
                    
                      <textarea rows="5" cols="5" class="form-control" name="com_short_description" id="com_short_description" placeholder="Company Short Description" <?php if($tmp_com_short_description){echo"readonly='true'";}else{} ?>><?php echo $tmp_com_short_description; ?></textarea>
              
                            </div>
                  </div>

                    
                                    
                          <div class="col-sm-10">
                            <p id="sub_form" class="file margin_top_right_arrow " >
                            <input type="submit" value="" id="add_to_lead_submit_confirm" />
                            <label for="file" class="btn_enabled"><span class="btn-text">Submit<span> 
                              <i class="fa fa-angle-right" aria-hidden="true"></i>
                              </label>
                               
                            </p>
                          </div>
                          
                      </form>

                      
                    </div>
                  </div> 

                  <?php } ?> 
                </div>
                
            </div>
            <div class="content-footer">
              <?php $this->load->view('admin/includes/footer'); ?>
            </div>
        </div>
    </div>
    <?php $this->load->view('admin/includes/modal-html'); ?>
    <?php $this->load->view('admin/includes/app.php'); ?> 
  </body>
</html>
<script type="text/javascript">
      window.paceOptions = {
        document: true,
        eventLag: true,
        restartOnPushState: true,
        restartOnRequestAfter: true,
        ajax: {
          trackMethods: [ 'POST','GET']
        }
      };
    </script> 
<!-- build:js({.tmp,app}) scripts/app.min.js -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script src="<?php echo assets_url()?>vendor/jquery/dist/jquery.js"></script> -->
<script src="<?php echo assets_url()?>vendor/pace/pace.js"></script>
<script src="<?php echo assets_url()?>vendor/tether/dist/js/tether.js"></script>
<script src="<?php echo assets_url()?>vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo assets_url()?>vendor/fastclick/lib/fastclick.js"></script>
<script src="<?php echo assets_url()?>scripts/constants.js"></script>
<script src="<?php echo assets_url()?>scripts/main.js"></script>
<!-- endbuild -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script> -->
<script src="<?php echo assets_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?=assets_url();?>vendor/jquery.ui/ui/core.js"></script>
<script src="<?=assets_url();?>vendor/jquery.ui/ui/widget.js"></script>
<script src="<?=assets_url();?>vendor/jquery.ui/ui/mouse.js"></script>
<script src="<?=assets_url();?>vendor/jquery.ui/ui/draggable.js"></script>
<script src="<?=assets_url();?>vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
<script src="<?=assets_url();?>vendor/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
<script src="<?=assets_url();?>vendor/blueimp-file-upload/js/jquery.fileupload.js"></script>
<!-- page scripts -->
<!-- end page scripts -->
<script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?=assets_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>   
<script src="<?php echo assets_url();?>js/common_functions.js"></script>
<script src="<?php echo assets_url();?>js/custom/company/add.js"></script>

<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<!-- initialize page scripts -->
<script src="<?=assets_url();?>scripts/forms/upload.js"></script>
<!-- end initialize page scripts -->
<!-- initialize page scripts -->    
<script>
  $( function(){
    $( "#datepicker" ).datepicker();
    $( "#datepicker2" ).datepicker();
  });
</script>
</div>
</div>
<script type="text/javascript">
function checkDate() {
  var selectedDate = document.getElementById('datepicker').value;
  var tt = document.getElementById('datepicker2').value;
  var date = new Date(tt);
  if (selectedDate < date) {
    swal("Date must be in the future");
  }
}
    function getdate() {
    var tt = document.getElementById('datepicker2').value;

    var date = new Date(tt);
    var newdate = new Date(date);

    newdate.setDate(newdate.getDate() + 2);
    
    var dd = newdate.getDate();
    var mm = newdate.getMonth() + 1;
    var y = newdate.getFullYear();

    var someFormattedDate = mm + '/' + dd + '/' + y;
    document.getElementById('datepicker').value = someFormattedDate;
}
      function lead_submit()
      {
      document.getElementById('form').submit();
    }
      /*function setmobileno(mob)
      {
      document.getElementById('mobile').value=mob;
      document.getElementById('mobile').onkeyup='';
    }*/
    
    function remove_attr(id)
      {
      document.getElementById('lead_form').style.display='none';
      document.getElementById('new').checked=false;
    }
    function reset_lead_form()
      {
    $("#second_option").hide();
    $("#second_option").html('');
    $("#email").val('');
    $("#mobile").val('');
    $("#step_flag").val('1');
    $("#reset_form").hide();
    }
      function getoption()
      {
        var val=document.getElementById('email').value;
        var val1=document.getElementById('mobile').value;
        if(val!='' && val1!='')
        {
        
        
      $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getfirstoption_new",
        type: "POST",
        data: {'email':val},
        async:true,     
        success: function (response) 
        {
            //$('#first_option').html(response);            
            //getmobile();
            $('#second_option').html(response); 
            
        },
        error: function () 
        {
         //$.unblockUI();
         alert('Something went wrong there');
        }
       });
      }
    }
    function getmobile()
      {
        var val=document.getElementById('email').value;
        var val1=document.getElementById('mobile').value;
        if(val!='')
        {
    
      $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getmobileno",
        type: "POST",
        data: {'email':val,'mobile':val1},
        async:true,     
        success: function (response) 
        {
          $('#customer_id').val(response); 
          if($("input:radio[name='tag_rad']").is(":checked"))
          {
          $('#next').removeAttr('disabled');
        }
        },
        error: function () 
        {
         //$.unblockUI();
         alert('Something went wrong there');
        }
       });
      
    }   
    }
    function getsecondoption()
      {
        
      var val=document.getElementById('email').value;
        var val1=document.getElementById('mobile').value;
        $("#loader").show();
         
        $("#second_option").html(''); 
        
          
        if(val!='' || val1!='')
        {
      
          $("#second_option").show(); 
      $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getsecondoption_new",
        type: "POST",
        data: {'mobile':val1,'email':val},      
        success: function (response) 
        {
            $("#loader").hide();
            $("#second_option").html(response);
            $("#step_flag").val('2');
            $("#reset_form").show();            
            $("#sub_form").show();            
            //getmobile();
            
        },
        error: function () 
        {
         //$.unblockUI();
         alert('Something went wrong there');
        }
       });
      }
      else
        {
      swal('Please enter email or mobile!');
      $("#loader").hide();
    } 
    }
    
    function getemail(val)
      {
      
        
      $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url'];?>/lead/getemail",
        type: "POST",
        data: {'mobile':val},
        async:true,     
        success: function (response) 
        {
          
            var data=response.split('&');
            var val1=document.getElementById('email').value;
            if(val1=='' || val1==null)
            {
            $('#email').val(data[0]); 
          }
            
            //$('#mobile').val(data[1]); 
            $('#customer_id').val(data[2]); 
            
            
            if($("input:radio[name='tag_rad']").is(":checked"))
            {
            $('#next').removeAttr('disabled');
          }
            
            
        },
        error: function () 
        {
         //$.unblockUI();
         alert('Something went wrong there');
        }
       });
    }
    </script>

<script type="text/javascript">
function validateEmail(sEmail)
{
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail))
    {
        return true;
    }
    else
    {
        return false;
    }
}
  function getcusdata(id)
  {
     
    
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/GetCusData_ajax/"+id,
        type: "GET",
        data: {},     
        success: function (response) 
        {
          $('#cus_details').html(response);             
          $('#cus_prof').modal();             
        },
        error: function () 
        {
         //$.unblockUI();
         alert('Something went wrong there');
        }
       });
      
    
  }
  
  function cust_form()
  {  
    if(document.getElementById('new').checked==true)
    {
      document.getElementById('lead_form').style.display='block';
      document.getElementById('buy_req_div').style.display='none';
      //remove_attr();
      //$("#lead_row").unbind("click");
      
      $("#div_disable").attr('class', 'position_up_color');
      $('input[name="tag_rad"]:checked').prop('checked', false);
    }
    else
    {
      document.getElementById('lead_form').style.display='none';
      document.getElementById('buy_req_div').style.display='block';
      
      $("#div_disable").attr('class', '');
    }
    
  }
  function setnewcus()
  {
    if(document.getElementById('step_flag').value=='2')
    {
    
      if(document.getElementById('new').checked==true)                                    {
        var email=document.getElementById('email').value;
        var mobile=document.getElementById('mobile').value;
        var title=document.getElementById('title').value;
        var source_id=document.getElementById('source').value;
        
        if(email=='' && mobile=='')
        {
          swal('Please enter email or mobile!');  
        }       
        else if(title=='')
        {
          swal('Please enter title!'); 
        }
        else if(source_id=='')
        {
          swal('Please enter source!'); 
        }     
        else
        {
          lead_submit();
          $("#add_lead").modal('toggle'); 
              swal('New Lead successfully created!'); 
        }
      }
      else
      {
        
        var lead_id =$('input[name="tag_rad"]:checked').val();
      
        var description=document.getElementById('buy_req').value;
        if(!lead_id)
        {
          swal('Please select a lead'); 
          return false;
        }
        else if(description=='')
        {
          swal('Please enter buying requirement'); 
          return false;
        }
        else
        {
        
      
      
      var email=document.getElementById('email').value;
      var mobile=document.getElementById('mobile').value;
      
      
        $.ajax({
          url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/lead_tag",
          type: "POST",
          data: {'lead_id':lead_id,'description':description,'command':'2','email':email,'mobile':mobile},      
          success: function (response) 
          {
              $("#email").val('');
              $("#mobile").val('');
              $("#second_option").hide();                       
                $("#add_lead").modal('toggle'); 
                swal('Lead tagged successfully done!');         
          },
          error: function () 
          {
           //$.unblockUI();
           alert('Something went wrong there');
          }
         });
      
        }       
                
      }
    } 
    else
    {
      getsecondoption();
      
    }
  }
</script>
