<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>    
    
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
          //var quotation_id=$("#quotation_id").val();
          //var updated_field_name=editor.id;
          //var updated_content=editor.getContent();
          //fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
          //check_submit();
    })
    }
                
  });

   tinymce.init({
      selector: 'textarea.short-description-editor',
      force_br_newlines : true,
      force_p_newlines : false,
      forced_root_block : '',
      menubar: false,
      statusbar: false,
      toolbar: false, 
      browser_spellcheck : true,   
      setup: function(editor) {

         editor.on('change', function () {
            editor.save();
         });

         editor.on('focusout', function(e) {

             
         });

         editor.on('keyup', function (e) {
            // var words = editor.getContent().match(/\S+/g).length;
            // $("#description_word_count").val(words);
            // if (words > 100) {
            //    return false;       
            // }
            // else {        
            //   $('#display_count').text(words);
            //   $('#word_left').text(100-words);
            // }
         });
      }                
  });

  tinymce.init({
      selector: 'textarea.moderate-wysiwyg-editor',
      // height: 300,
      menubar: false,
      statusbar:false,
      plugins: ["code,advlist autolink lists link image charmap print preview anchor"],
      toolbar: 'styleselect | bullist numlist',
      content_css: [],
    browser_spellcheck : true,
      setup: function(editor) {
     
    /*
    if($("#long_description").val().match(/\S+/g).length>0)
    {
      $("#long_description_word_count").val($("#long_description").val().match(/\S+/g).length);
      $("#display_count2").text($("#long_description").val().match(/\S+/g).length);
    }
    */  
        
    editor.on('change', function () {
      editor.save();
    });
    
    editor.on('keyup', function (e) {
      // var words = editor.getContent().match(/\S+/g).length;
      // $("#long_description_word_count").val(words);
      // if (words > 1000) {        
      //   return false;        
      // }
      // else {        
      //   $('#display_count2').text(words);
      //   $('#word_left2').text(1000-words);
      // }
    });
    
        //editor.on('focusout', function(e) {
      
          //console.log(editor);
          //var quotation_id=$("#quotation_id").val();
          //var updated_field_name=editor.id;
          //var updated_content=editor.getContent();
          //fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
          //check_submit();
        //});
    
    
    
    
    }
  });

  tinymce.init({
    selector: 'textarea.advance-wysiwyg-editor',
    plugins: ["code,advlist autolink lists link image charmap print preview anchor,textcolor"],
    toolbar: "styleselect fontselect fontsizeselect | forecolor backcolor"
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
         <div class="layout-md b-b--">
            <div class="layout-column-md">
               <div class="p-a-1 wizards">
                  <div class="tsf-wizard tsf-wizard-1">
                     <div class="tsf-nav-step" style="display: none;"></div>
                     <div class="tsf-container">
                        <!-- MESSAGE START -->
                        <?php if($this->session->flashdata('error_msg')!=''){ ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                           <?php echo $this->session->flashdata('error_msg'); ?>
                        </div>
                        <?php } ?>
                        <?php if($this->session->flashdata('success_msg')!=''){ ?>
                        <div class="alert alert-success alert-dismissible fade in" role="alert">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                           <?php echo $this->session->flashdata('success_msg'); ?>
                        </div>
                        <?php } ?>
                        <!-- MESSAGE END -->
                        <!-- ======================  STEP 1 STSRT ===================== -->
                        <?php $active='';
                           if($step=='1'){
                               $active='active';
                           }
                           ?>
                        <div class="tsf-step step-1 <?=$active;?>">
                           <fieldset class="tsf-content">
                              <div class="col-lg-12">
                                 <legend class="app_pro_tittle">Edit Product <a href="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage" class="pull-right text-primary">Back</a></legend>
                              </div>
        
               <form name="product_add_edit_form" id="product_add_edit_form">
                  <input type="hidden" name="product_id" id="product_id" value="<?php echo $edit_id; ?>">
          <input type="hidden" name="existing_product_id" id="existing_product_id" value="<?php echo $edit_id; ?>">
          <input type="hidden" name="" id="selected_group_id" value="<?php echo $row->group_id; ?>">
          <input type="hidden" name="" id="selected_cat_id" value="<?php echo $row->cate_id; ?>">
          
         
         
                  <!-- BEGIN STEP CONTENT-->
                  <div class="tsf-step-content tsf_stape_content">
                     <div class="col-lg-12 col-sm-12 col-md-0">
                        <div class="add_form_new">
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Select Group<span class="text-danger">*</span></label>
                                 <div class="form_last">
                                    <select id="group_id" name="group_id">
                                       <option value="">-- Select Group --</option>
                                    </select>
                                    <div class="text-danger error_div" id="group_id_error"></div>
                                    <!-- <a href="JavaScript:void(0)" class="pull-right text-info add_group_modal"><small>Add New Group</small></a> -->
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Select Category<span class="text-danger">*</span></label>
                                 <div class="form_last">
                                    <select id="cate_id" name="cate_id">
                                       <option value="">-- Select Category --</option>
                                    </select>
                                    <div class="text-danger error_div" id="cate_id_error"></div>
                                    <!-- <a href="JavaScript:void(0)" class="pull-right text-info add_category_modal" ><small>Add New Category</small></a> -->
                                 </div>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Product Name<span class="text-danger">*</span></label>
                                 <div class="form_last">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $row->product_name; ?>">
                                    <div class="text-danger error_div" id="name_error"></div>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Product Code</label>
                                 <div class="form_last">
                                    <input type="text" class="form-control" name="code" id="code" placeholder="Product Code" value="<?php echo $row->code; ?>">
                                    <div class="text-danger error_div" id="code_error"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group row">
                              
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>HSN Code<a href="https://cbic-gst.gov.in/gst-goods-services-rates.html" target="_blank"><img src="<?=assets_url();?>images/hsn_icon.png"></a></span></label>
                                 <div class="form_last">
                                    <input type="text" class="form-control" name="hsn_code" id="hsn_code" placeholder="HSN Code" value="<?php echo $row->hsn_code; ?>">
                                    <div class="text-danger error_div" id="hsn_code_error"></div>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>GST%</label>
                                 <div class="form_last">
                                    <input type="text" class="form-control double_digit" name="gst_percentage" id="gst_percentage" placeholder="GST" value="<?php echo $row->gst_percentage; ?>">
                                    <div class="text-danger error_div" id="gst_percentage_error"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group row">
                              
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Selling Price<span class="text-danger">*</span></label>
                                 <div class="form_last">
                                    <div class="form_half">
                                       <input type="text" class="form-control double_digit" name="price" id="price" placeholder="Price" value="<?php echo $row->price; ?>">
                                       <div class="text-danger error_div" id="price_error"></div>
                                    </div>
                                    <div class="form_half">
                                       <select id="currency_type" name="currency_type">
                                          <option value="">Currency</option>
                                          <?php foreach($currency_list as $currency){ ?>
                                             <option value="<?php echo $currency->id; ?>" <?php if($row->currency_type==$currency->id){echo"selected";} ?>><?php echo $currency->code; ?></option>
                                          <?php } ?>
                                       </select>
                                       <div class="text-danger error_div" id="currency_type_error"></div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Unit<span class="text-danger">*</span></label>
                                 <div class="form_last">
                                    <div class="form_half">
                                       <input type="text" class="form-control only_natural_number_noFirstZero" name="unit" id="unit" placeholder="Unit" value="<?php echo $row->unit; ?>">
                                       <div class="text-danger error_div" id="unit_error"></div>
                                    </div>
                                    <div class="form_half">
                                       <select name="unit_type" id="unit_type">
                                          <option value="" >Type</option>
                                          <?php foreach($unit_type_list as $unit){ ?>
                                             <option value="<?php echo $unit->id; ?>" <?php if($row->unit_type==$unit->id){echo"selected";} ?>><?php echo $unit->type_name; ?></option>
                                          <?php } ?>
                                       </select>
                                       <div class="text-danger error_div" id="unit_type_error"></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
               
                <?php 
              $image_id=array();
              $image_name=array();
              $iamges_str=trim($row->image_files,'^');
              if($iamges_str)
              {
                $images=explode("^",$iamges_str);
                foreach ($images as $image) 
                {
                  $image_arr=explode("_", $image);  
                  $image_id[]=$image_arr[0];
                  $image_name[]=$image_arr[1];
                }   
              }
              //print_r($image_name);
                ?>
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                 <label class="up-label">Add Product Photo</label>
                                 <div class="form_last">
                                    <ul class="pup">
                                       <li>
                                          <label class="product_up" for="fileupload_image">
                                          <span>
                                          +<br>
                                          Add Photo
                                          </span>
                                          <input id="fileupload_image" type="file" name="image_files[]" accept="image/*" onclick="GetImagePreview(this,'product_photo_preview_1')">
                                          </label>
                                          <div class="product_photo_preview" id="product_photo_preview_<?php echo $image_id[0]; ?>" style="<?php echo ($image_name[0])?'display:block':''; ?>">
                                             <a href="JavaScript:void(0);" class="remove_pic  delete_img" data-id="<?php echo $image_id[0]; ?>"><i class="fa fa-trash" area-hidden="true"></i></a>
                                             <span><?php echo ($image_name[0])?'<img src="'.assets_url().'uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/product/thumb/'.$image_name[0].'" />':''; ?></span>
                                          </div>
                                       </li>
                                       <li>
                                          <label class="product_up" for="fileupload_image2">
                                          <span>
                                          +<br>
                                          Add Photo
                                          </span>
                                          <input id="fileupload_image2" type="file" name="image_files2[]" accept="image/*">
                                          </label>
                                          <div class="product_photo_preview" id="product_photo_preview_<?php echo $image_id[1]; ?>" style="<?php echo ($image_name[1])?'display:block':''; ?>">
                                             <a href="JavaScript:void(0)" class="remove_pic delete_img" data-id="<?php echo $image_id[1]; ?>"><i class="fa fa-trash" area-hidden="true"></i></a>
                                             <span><?php echo ($image_name[1])?'<img src="'.assets_url().'uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/product/thumb/'.$image_name[1].'" />':''; ?></span>
                                          </div>
                                       </li>
                                       <li>
                                          <label class="product_up" for="fileupload_image3">
                                          <span>
                                          +<br>
                                          Add Photo
                                          </span>
                                          <input id="fileupload_image3" type="file" name="image_files3[]" accept="image/*">
                                          </label>
                                          <div class="product_photo_preview" id="product_photo_preview_<?php echo $image_id[2]; ?>" style="<?php echo ($image_name[2])?'display:block':''; ?>">
                                             <a href="JavaScript:void();" class="remove_pic delete_img" data-id="<?php echo $image_id[2]; ?>"><i class="fa fa-trash" area-hidden="true"></i></a>
                                             <span><?php echo ($image_name[2])?'<img src="'.assets_url().'uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/product/thumb/'.$image_name[2].'" />':''; ?></span>
                                          </div>
                                       </li>
                                       
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Add Product Brochure</label>
                                 <div class="form_last">
                                    <span id="pdf_up" class="btn btn-success btn-icon fileinput-button m-b-1" style="<?php echo ($row->pdf_file_name)?'display:none':''; ?>">
                                    <label class="pdf_upload_btn" for="fileupload_pdf">Add PDF Brochure</label>
                                    <input id="fileupload_pdf" type="file" name="pdf_files[]" accept="application/pdf">
                                    </span>
                                    <div id="PreviewPdf" style="display: none;"></div>
                  <?php if($row->pdf_file_name){ ?>
                  <div class="del_pdf_file"><?php echo $row->pdf_file_name; ?><a href="JavaScript:void(0);" data-id="<?php echo $row->pdf_file_id; ?>" class="del_pdf_new"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                  <?php } ?>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Add Product Video</label>
                                 <div class="form_last">
                                    <input type="text" class="form-control" name="youtube_video" id="youtube_video" placeholder="Youtube Video URL" value="<?php echo $row->youtube_video; ?>">
                                    <div class="text-danger error_div" id="youtube_video_error"></div>
                                 </div>
                              </div>
                           </div>
                           
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                 <label>Short Description<!-- <span class="text-danger">*</span> --></label>
                                 <div class="form_last">
                                    <?php /* ?><textarea class="form-control" name="description" id="description" placeholder="Product Description in max 100 words" ><?php echo $row->description; ?></textarea><?php */ ?>
                                    <textarea class="form-control short-description-editor" name="description" id="description" placeholder="Product Description" ><?php echo $row->description; ?></textarea>
                                    <input type="hidden" id="description_word_count" value="0" />
                                    <!-- <div class="text-danger error_div" id="description_error"></div>
                                    <div class="rchars">Total word count: <span id="display_count">0</span> words. Words left: <span id="word_left">100</span></div> -->
                                 </div>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                 <label>Long Description</label>
                                 <div class="form_last">
                                    <textarea class="form-control moderate-wysiwyg-editor" name="long_description" id="long_description" placeholder="Product Description" ><?php echo $row->long_description; ?></textarea>
                                    <input type="hidden" id="long_description_word_count" value="0" />
                                    <!-- <div class="text-danger error_div" id="long_description_error"></div>
                                    <div class="rchars">Total word count: <span id="display_count2">0</span> words. Words left: <span id="word_left2">1000</span></div> -->
                                 </div>
                              </div>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Available For<span class="text-danger">*</span></label>
                                 <div class="form_last">
                                    <select id="product_available_for" name="product_available_for">
                                    <option value="A" <?php if($row->product_available_for=='A'){echo'SELECTED';} ?>>Export & Domestic</option>
                                    <option value="E" <?php if($row->product_available_for=='E'){echo'SELECTED';} ?>>Export Only</option>
                                    <option value="D" <?php if($row->product_available_for=='D'){echo'SELECTED';} ?>>Domestic Only</option>
                                    </select>
                                    <div class="text-danger error_div" id="product_available_for_error"></div>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                 <label>Min. Order Quantity</label>
                                 <div class="form_last">
                                    <input type="text" class="form-control only_natural_number_noFirstZero" name="minimum_order_quantity" id="minimum_order_quantity" placeholder="Order Quantity" value="<?php echo $row->minimum_order_quantity; ?>">
                                    <div class="text-danger error_div" id="minimum_order_quantity_error"></div>
                                 </div>
                              </div>
                           </div>
                           <?php //print_r($row); ?>
                           <div class="form-group row">
                              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                 
                                 <label>Tag Vendor</label>
                                 <div class="form_last">
                                    <button type="button" class="btn btn-default btn-md vendor-btn" onclick="select_vendor_product_lead()">Select Vendors</button>
                                    <!-- <input type="text" class="form-control" name="vendor_productvarient_tag" id="vendor_productvarient_tag" placeholder="Search Vendor">
                                    <div class="text-danger error_div" id="vendor_productvarient_tag_error"></div>
                                    <div class="ven_out">
                                    </div> -->
                                    <input type="hidden" name="vendors" id="vendors" value="<?php echo $row->v_tag_str; ?>" />
                                 </div>
                              </div>
                           </div>
                           <?php
                $existing_vdsList='';
              $vendors_str=trim($row->v_tag_str,'^');
              if($vendors_str)
              {
                $vendors=explode("^",$vendors_str);
                foreach ($vendors as $vendor) 
                {
                  $vdr=explode("_", $vendor);
                  $vdr[0]=trim($vdr[0],'@');                  
                  $v_name=get_value("company_name","vendor","id=".$vdr[0]);
                  $existing_vdsList .='<div class="rmVdr_btn" id="rmVdr_'.$vdr[0].'">';
                  $existing_vdsList .='<div class="form-group">';
                  $existing_vdsList .='<label class="label-btn"><i class="fa fa-check tick-icon" aria-hidden="true"></i>'.$v_name.'<i class="fa fa-trash-o rm_vdr" data-id="'.$vdr[0].'" aria-hidden="true"></i></label>';
                  $existing_vdsList .='</div>';
                  $existing_vdsList .='</div>';
                }   
              }
              
              ?>
            </div>
                        <div id="vdsList"><?php echo $existing_vdsList; ?></div>
                          <div class="form-group row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                             <div class="tsf-controls ">
                              <button type="button" class="btn btn-left_new tsf-wizard-btn" id="product_submit_confirm">Save and Close </button>
                             </div>
                            </div>
                          </div>
                        
                     </div>
                  </div>
                  <!-- END STEP CONTENT-->
                  <input type="hidden" name="step" value="1" />
               </form>
                     </fieldset>
                  </div>
                        <!--================== STEP 1 END  =============== -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
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
<script type="text/javascript" src="<?php echo assets_url(); ?>js/custom/product/add.js?v=<?php echo rand(0,1000); ?>"></script>
<script type="text/javascript">
   $(document).ready(function() {
         if($("#description").val().match(/\S+/g).length>0)
     {
       $("#display_count").text($("#description").val().match(/\S+/g).length);
     }
     
   });
   
</script>
