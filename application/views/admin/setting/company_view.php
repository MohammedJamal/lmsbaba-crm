<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>
   

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
    <script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
       tinymce.PluginManager.add('placeholder', function (editor){
                 editor.on('init', function () {
                     var label = new Label;
                     onBlur();
                     tinymce.DOM.bind(label.el, 'click', onFocus);
                     editor.on('focus', onFocus);
                     editor.on('blur', onBlur);
                     editor.on('change', onBlur);
                     editor.on('setContent', onBlur);
                     function onFocus() { if (!editor.settings.readonly === true) { label.hide(); } editor.execCommand('mceFocus', false); }
                     function onBlur() { if (editor.getContent() == '') { label.show(); } else { label.hide(); } }
                 });
                 var Label = function () {
                     var placeholder_text = editor.getElement().getAttribute("placeholder") || editor.settings.placeholder;
                     var placeholder_attrs = editor.settings.placeholder_attrs || { style: { position: 'absolute', top: '2px', left: 0, color: '#aaaaaa', padding: '.25%', margin: '5px', width: '80%', 'font-size': '17px !important;', overflow: 'hidden', 'white-space': 'pre-wrap' } };
                     var contentAreaContainer = editor.getContentAreaContainer();
                     tinymce.DOM.setStyle(contentAreaContainer, 'position', 'relative');
                     this.el = tinymce.DOM.add(contentAreaContainer, "label", placeholder_attrs, placeholder_text);
                 }
                 Label.prototype.hide = function () { tinymce.DOM.setStyle(this.el, 'display', 'none'); }
                 Label.prototype.show = function () { tinymce.DOM.setStyle(this.el, 'display', ''); }
         });
       
       tinymce.init({
       selector: 'textarea.basic-wysiwyg-editor',
       force_br_newlines : true,
       force_p_newlines : false,
       forced_root_block : '',
       menubar: false,
       statusbar: false,
       toolbar: false,
       plugins: ["placeholder"],
       setup: function(editor) {      
         //editor.on('focusout', function(e) {
           //console.log(editor);          
           // var quotation_id=$("#quotation_id").val();
           // var updated_field_name=editor.id;
           // var updated_content=editor.getContent();
           // fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
           //check_submit();
         //})
       }                
       });
       
       tinymce.init({
       selector: 'textarea.moderate-wysiwyg-editor',
       height: 300,
       menubar: false,
       statusbar:false,
       plugins: ["code,advlist autolink lists link image charmap print preview anchor"],
       toolbar: 'bold italic backcolor | bullist numlist',
       content_css: [],
       setup: function(editor) {
         // editor.on('init change', function () {
         //     editor.save();
         // });
         //editor.on('focusout', function(e) {
           //console.log(editor);
           // var quotation_id=$("#quotation_id").val();
           // var updated_field_name=editor.id;
           // var updated_content=editor.getContent();
           // fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
           //check_submit();
       //})
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
      <form role="form" class="form-validation" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/company/<?php echo $id;?>" method="post" name="form" id="profile_update_form" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" id="edit_id" value="<?=$id;?>"/>
        <input type="hidden" name="existing_profile_image" value="<?php echo $company['logo']; ?>">
        <input type="hidden" name="existing_brochure_file" value="<?php echo $company['brochure_file']; ?>">
        <div class="row">
          <div class="col-lg-12">                          
              <div class="row m-b-1">                

                 <div class="col-sm-4 pr-0">
                  <div class="bg_white back_line">  
                    <h4>Settings  <svg height="32" id="svg8" style="vertical-align: text-top;" version="1.1" viewBox="0 0 8.4666665 8.4666669" width="32" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs2"/><g id="layer1" transform="translate(0,-288.53332)"><path d="m 3.7041666,288.7979 a 0.26460976,0.26460976 0 0 0 -0.2511475,0.18087 l -0.2687174,0.80615 c -0.1084927,0.0382 -0.2146168,0.082 -0.3183269,0.13178 l -0.7601602,-0.37982 a 0.26460976,0.26460976 0 0 0 -0.3054077,0.0496 l -0.7482748,0.74827 a 0.26460976,0.26460976 0 0 0 -0.049609,0.30541 l 0.379305,0.75861 c -0.049895,0.10423 -0.094048,0.21083 -0.1322917,0.31988 l -0.80511879,0.26871 a 0.26460976,0.26460976 0 0 0 -0.18086751,0.25115 v 1.05833 a 0.26460976,0.26460976 0 0 0 0.18086751,0.25115 l 0.80770259,0.26924 c 0.038069,0.10784 0.081782,0.21314 0.1312582,0.31625 l -0.3808553,0.76172 a 0.26460976,0.26460976 0 0 0 0.049609,0.3054 l 0.7482748,0.74879 a 0.26460976,0.26460976 0 0 0 0.3054077,0.0496 l 0.7601602,-0.38033 c 0.1036035,0.0495 0.209454,0.0932 0.3178101,0.13125 l 0.2692342,0.80719 a 0.26460976,0.26460976 0 0 0 0.2511475,0.18087 h 1.0583333 a 0.26460976,0.26460976 0 0 0 0.2511476,-0.18087 l 0.2692341,-0.80874 c 0.1075521,-0.0379 0.2128936,-0.0815 0.3157429,-0.13074 l 0.7622276,0.38137 a 0.26460976,0.26460976 0 0 0 0.3054074,-0.0496 l 0.748275,-0.74879 a 0.26460976,0.26460976 0 0 0 0.049609,-0.3054 l -0.3798218,-0.75965 c 0.049789,-0.10387 0.093561,-0.21018 0.1317749,-0.31884 L 8.0222491,293.548 a 0.26460976,0.26460976 0 0 0 0.1808676,-0.25115 v -1.05833 a 0.26460976,0.26460976 0 0 0 -0.1808676,-0.25115 l -0.806669,-0.26871 c -0.038193,-0.10832 -0.082077,-0.21427 -0.1317747,-0.31781 l 0.3803385,-0.76068 a 0.26460976,0.26460976 0 0 0 -0.049609,-0.30541 l -0.748275,-0.74827 a 0.26460976,0.26460976 0 0 0 -0.3054074,-0.0496 l -0.7580934,0.37878 c -0.1045763,-0.05 -0.2115013,-0.094 -0.3209105,-0.13229 l -0.2682007,-0.8046 a 0.26460976,0.26460976 0 0 0 -0.251148,-0.18088 z m 0.190686,0.52917 h 0.6769613 l 0.245463,0.73691 a 0.26460976,0.26460976 0 0 0 0.1757,0.17001 c 0.1722022,0.0512 0.3388331,0.11967 0.4971272,0.20464 a 0.26460976,0.26460976 0 0 0 0.243396,0.004 l 0.6934978,-0.34675 0.4785236,0.47852 -0.3482991,0.6966 a 0.26460976,0.26460976 0 0 0 0.00362,0.24391 c 0.084769,0.15725 0.1537229,0.32244 0.2051555,0.49351 a 0.26460976,0.26460976 0 0 0 0.1694987,0.17519 l 0.738456,0.24598 v 0.67696 l -0.7379393,0.24598 a 0.26460976,0.26460976 0 0 0 -0.1694987,0.17518 c -0.051373,0.1714 -0.1203285,0.337 -0.2051555,0.49454 a 0.26460976,0.26460976 0 0 0 -0.00362,0.24392 l 0.3477824,0.69556 -0.4785236,0.47904 -0.6981486,-0.34933 a 0.26460976,0.26460976 0 0 0 -0.2439128,0.004 c -0.1566825,0.0843 -0.3210488,0.15287 -0.4914429,0.20412 a 0.26460976,0.26460976 0 0 0 -0.175183,0.1695 l -0.2464967,0.74052 H 3.8948526 l -0.2464967,-0.73949 a 0.26460976,0.26460976 0 0 0 -0.175183,-0.17001 c -0.1710385,-0.0511 -0.3367447,-0.11916 -0.4940265,-0.20361 a 0.26460976,0.26460976 0 0 0 -0.243396,-0.004 l -0.6960816,0.3483 -0.4785238,-0.47904 0.3488159,-0.69763 a 0.26460976,0.26460976 0 0 0 -0.00362,-0.24391 c -0.08452,-0.15682 -0.1532676,-0.32191 -0.2046387,-0.49248 a 0.26460976,0.26460976 0 0 0 -0.1694987,-0.17467 l -0.73948973,-0.24649 v -0.67696 l 0.73742263,-0.24598 a 0.26460976,0.26460976 0 0 0 0.1700155,-0.17519 c 0.051313,-0.17172 0.1197532,-0.33773 0.2046387,-0.49557 a 0.26460976,0.26460976 0 0 0 0.00362,-0.24392 l -0.3472656,-0.69453 0.4785238,-0.47852 0.6960816,0.34778 a 0.26460976,0.26460976 0 0 0 0.2439127,-0.004 c 0.1573948,-0.0848 0.3227911,-0.15375 0.4940266,-0.20515 a 0.26460976,0.26460976 0 0 0 0.1751832,-0.1695 z" id="path940" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/><path d="m 4.2324219,290.91406 c -1.0197435,0 -1.8515625,0.83377 -1.8515625,1.85352 0,1.01974 0.831819,1.85156 1.8515625,1.85156 1.0197434,0 1.8535156,-0.83182 1.8535156,-1.85156 0,-1.01975 -0.8337722,-1.85352 -1.8535156,-1.85352 z m 0,0.5293 c 0.7337606,0 1.3242187,0.59046 1.3242187,1.32422 0,0.73376 -0.5904581,1.32226 -1.3242187,1.32226 -0.7337606,0 -1.3222657,-0.5885 -1.3222657,-1.32226 0,-0.73376 0.5885051,-1.32422 1.3222657,-1.32422 z" id="path961" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/></g></svg></h4> 
                  </div>
                </div>

                 <div class="col-sm-8 pleft_0">
                    <div class="bg_white_filt">
                    <ul class="filter_ul">                        
                      
                      <li>                          
                        <a class="new_filter_btn add_branch_view" href="JavaScript:void(0);" id="" title="Add Branch" data-id="">
                          <span class="bg_span"><img src="<?php echo assets_url()?>images/add_branch.png"/></span> Add Branch                      
                        </a> 
                      </li>

                      <li>                          
                        <a class="new_filter_btn list_branch_view" href="JavaScript:void(0);" id="" title="View Branch List" data-id="">
                          <span class="bg_span"><img src="<?php echo assets_url()?>images/list_branch.png"/></span> View Branch                      
                        </a> 
                      </li>
                                                 
                    </ul>
                    </div>
                  </div>
              </div>
              <?php
              if($this->session->flashdata('success_msg')) { ?>
              <!--  success message area start  -->
              <div class="alert alert-success alert-alt" style="display:block;" id="notification-success">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="fa fa-check-circle"></i> Success</h4>
              <span id="success_msg">
              <?php echo $this->session->flashdata('success_msg'); ?></span>
              </div>
              <!--  success message area end  -->
              <?php } ?>
              <?php if($this->session->flashdata('error_msg') || $error_msg) { ?>
              <!--  error message area start  -->
              <div class="alert alert-danger alert-alt" style="display:block;" id="notification-error">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="fa fa-exclamation-triangle"></i> Error</h4>
              <span id="error_msg">
              <?php echo ($this->session->flashdata('error_msg'))?$this->session->flashdata('error_msg'):$error_msg; ?></span>
              </div>
              <!--  error message area end  -->
              <?php } ?>
              <div class="panel panel-primary card process-sec">
                <div class="group_from bg_color_white_no">
                  <?php
                  $logo=$company['logo'];
                  if($logo!='')
                  {
                   $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/thumb/".$logo;
                  }
                  else
                  {
                   $profile_img_path = assets_url().'images/user_img_icon.png';
                  }
                  ?>                                
                  <div class="card-body p-0">
                    <div class="tab_gorup w-100 company-setting-form">
                     <div class="tab tab-group-sec hide">
                        <button class="tablinks" onClick="openCity(event, 'company_profile')" id="defaultOpen" type="button">Company Profile</button>           
                     </div>
                      <div class="tab-section border-0">
                        <div id="company_profile" class="tabcontent">
                            <div class="company_profile_wapper">
                              
                              <div class="form-group row">
                                
                                <div class="col-sm-3">
                                  <div class="user_image w-100 mb-25">
                                     <a href="javascript:void(0)" id="agent_photo_prev">
                                        <img src="<?php echo $profile_img_path;?>"/>
                                     </a>
                                  </div>

                                  <div class="user_btn">
                                    <span class="file">
                                        <input type="file" name="image" id="photo" onchange="GetImagePreview(this,'agent_photo_prev')" />
                                        <label for="photo">Change Picture</label>
                                    </span>
                                  </div>
                                </div>

                                <div class="col-sm-9">
                                  
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label class="col-form-label">Company Name<span class="text-danger">*</span> :</label>             
                                      <input type="text" class="form-control" name="name" id="name" placeholder="Company Name" value="<?php echo $company['name']; ?>" />
                                    </div>
                                    
                                  </div>

                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label class="col-form-label">Company Address<span class="text-danger">*</span> :</label>
                                      <input type="text" class="form-control" name="address" id="address" placeholder="Company Address" value="<?php echo $company['address']; ?>" />
                                    </div>
                                  </div>

                                  <div class="form-group row mb-0">
                                    <div class="col-sm-4">
                                      <label class="col-form-label">Country<span class="text-danger">*</span> :</label>
                                      <select class="custom-select form-control" name="country_id" id="country" onchange="GetStateList(this.value)">
                                        <option value="">Select</option>
                                        <?php foreach($country_list as $country_data)
                                           {
                                             ?>
                                        <option value="<?php echo $country_data->id;?>" <?php if($company['country_id']==$country_data->id){echo'selected';} ?>><?php echo $country_data->name;?></option>
                                        <?php
                                           }
                                        ?>
                                      </select>
                                    </div>
                                    <div class="col-sm-4">
                                      <label class="col-form-label">State<span class="text-danger">*</span> :</label>
                                      <select class="custom-select form-control" name="state_id" id="state" onchange="GetCityList(this.value)">
                                        <option value="">Select</option>
                                        <?php foreach($state_list as $state_data)
                                           {
                                             ?>
                                        <option value="<?php echo $state_data->id;?>" <?php if($company['state_id']==$state_data->id){?> selected <?php } ?>><?php echo $state_data->name;?></option>
                                        <?php
                                           }
                                           ?>
                                      </select>
                                    </div>
                                    <div class="col-sm-4">
                                      <label class="col-form-label">City<span class="text-danger">*</span> :</label>
                                      <select class="custom-select form-control" name="city_id" id="city">
                                        <option value="">Select</option>
                                        <?php foreach($city_list as $city_data)
                                           {
                                             ?>
                                        <option value="<?php echo $city_data->id;?>" <?php if($company['city_id']==$city_data->id){?> selected <?php } ?>><?php echo $city_data->name;?></option>
                                        <?php
                                           }
                                           ?>
                                      </select>
                                    </div>

                                  </div>

                                </div>

                              </div>

                              <div class="form-group row">
                                
                                <div class="col-sm-4">
                                  <label class="col-form-label">Pin Number<span class="text-danger">*</span> :</label>
                                  <input type="text" class="form-control" name="pin" id="pin" placeholder="Pin Number" value="<?php echo $company['pin']; ?>" />
                                </div>
                                <div class="col-sm-4">
                                  <label class="col-form-label">GST<span class="text-danger">*</span> :</label>
                                  <input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GST" value="<?php echo $company['gst_number']; ?>" />
                                </div>
                                <div class="col-sm-4">
                                  <label class="col-form-label">PAN<span class="text-danger">*</span> :</label>
                                  <input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="PAN" value="<?php echo $company['pan_number']; ?>" />
                                </div>
                              </div>

                              <div class="form-group row">
                                <div class="col-sm-4">
                                  <label for="" class=" col-form-label">Default Currency<span class="text-danger">*</span> :</label>
                                   <select class="custom-select form-control" name="default_currency" id="default_currency">
                                      <option value="">Select</option>
                                      <?php foreach($currency_list as $curr)
                                         {
                                           ?>
                                      <option value="<?php echo $curr->id;?>" <?php if($company['default_currency']==$curr->id){?> selected <?php } ?>><?php echo $curr->name;?></option>
                                      <?php
                                         }
                                         ?>
                                   </select>
                                </div>
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">CEO Name<span class="text-danger">*</span> :</label>
                                     <input type="text" class="form-control" name="ceo_name" id="ceo_name" placeholder="CEO Name" value="<?php echo $company['ceo_name']; ?>" />
                                </div>
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">Contact Person<span class="text-danger">*</span> :</label>
                                     <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Contact Person" value="<?php echo $company['contact_person']; ?>" /> 
                                </div>

                              </div>

                              <div class="form-group row">
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">Email 1<span class="text-danger">*</span> :</label>
                                     <input type="text" class="form-control" name="email1" id="email1" placeholder="Email 1" value="<?php echo $company['email1']; ?>" />
                                </div>
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">Email 2 :</label>
                                     <input type="text" class="form-control" name="email2" id="email2" placeholder="Email 2" value="<?php echo $company['email2']; ?>" />
                                </div>
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">Website</label>
                                  <input type="text" class="form-control" name="website" id="website" placeholder="Website"  value="<?php echo $company['website']; ?>" />
                                </div>

                              </div>

                              <div class="form-group row">
                                <div class="col-sm-3">
                                  <label for="" class="col-form-label">Mobile 1<span class="text-danger">*</span> :</label>
                                     <input type="text" class="form-control" name="mobile1" id="mobile1" placeholder="Mobile 1" value="<?php echo $company['mobile1']; ?>" />
                                </div>
                                <div class="col-sm-3">
                                  <label for="" class="col-form-label">Mobile 2 :</label>
                                     <input type="text" class="form-control" name="mobile2" id="mobile2" placeholder="Mobile 2" value="<?php echo $company['mobile2']; ?>" /> 
                                </div>
                                <div class="col-sm-3">
                                  <label for="" class="col-form-label">Phone 1 :</label>
                                     <input type="text" class="form-control" name="phone1" id="phone1" placeholder="Phone 1"  value="<?php echo $company['phone1']; ?>" maxlength="255" />
                                </div>
                                <div class="col-sm-3">
                                  <label for="" class="col-form-label">Phone 2 :</label>
                                     <input type="text" class="form-control" name="phone2" id="phone2" placeholder="Phone 2"  value="<?php echo $company['phone2']; ?>"  maxlength="255" />
                                </div>
                              </div>

                              <div class="form-group row">
                                <div class="col-sm-12">
                                  <label for="" class="col-form-label">About Company<span class="text-danger">*</span> :</label>
                                     <textarea class="form-control" name="about_company" id="about_company" style="height: 90px; padding: 10px"><?php echo $company['about_company']; ?></textarea>
                                </div>
                              </div>

                              <div class="form-group row">
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">Quotation Cover Letter(Body Text) :</label>
                                  <textarea class="form-control basic-wysiwyg-editor" name="quotation_cover_letter_body_text" id="quotation_cover_letter_body_text" style="height: 90px;"><?php echo $company['quotation_cover_letter_body_text']; ?></textarea>
                                </div>
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">Quotation Cover Letter(Footer Text) :</label>
                                  <textarea class="form-control basic-wysiwyg-editor" name="quotation_cover_letter_footer_text" id="quotation_cover_letter_footer_text" style="height: 90px;"><?php echo $company['quotation_cover_letter_footer_text']; ?></textarea>
                                </div>
                                <div class="col-sm-4">
                                  <label for="" class="col-form-label">Other Terms & Conditions :</label>
                                  <textarea class="form-control" name="quotation_terms_and_conditions" id="quotation_terms_and_conditions" style="height: 70px;"><?php echo $company['quotation_terms_and_conditions']; ?></textarea>
                                </div>
                                
                              </div>

                              <div class="form-group row">
                                <div class="col-sm-6">
                                  <label for="" class="col-form-label">Banker's Details 1 :</label>
                                  <textarea class="form-control basic-wysiwyg-editor" name="quotation_bank_details1" id="quotation_bank_details1" style="height: 90px;" placeholder="Add Bank account details"><?php echo $company['quotation_bank_details1']; ?></textarea>
                                </div>
                                <div class="col-sm-6">
                                  <label for="" class="col-form-label">Banker's Details 2 :</label>
                                  <textarea class="form-control basic-wysiwyg-editor" name="quotation_bank_details2" id="quotation_bank_details2" style="height: 90px;" placeholder="Add Bank account details"><?php echo $company['quotation_bank_details2']; ?></textarea>
                                </div>
                            
                              </div>

                              <div class="form-group row">
                                <div class="col-sm-12">
                                  <label class="col-form-label">PDF Brochure</label> 
                                </div>
                                <div class="col-sm-2">
                                  
                                  <span class="btn btn-success btn-icon fileinput-button m-b-1">
                                     <i class="material-icons">add</i>
                                     <span>Select PDF file...</span>
                                     <input id="fileupload_pdf" type="file" name="pdf_files[]">
                                  </span> 
                                  
                                  
                                </div>
                                <div class="col-sm-10">
                                  <div id="PreviewPdf" style="display: inline-block;margin-left:0px;">
                                       <?php 
                                          if ($company['brochure_file']!=''){
                                          ?>
                                        <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/download_brochure/<?php echo base64_encode($company['brochure_file']);?>" title="click to download" data-toggle="tooltip" data-placement="top"><?=$company['brochure_file'];?></a>
                                        <span class="del_pdf_new icon-btn btn-alert text-white" onclick="remove_pdf('<?php echo $company['id']?>')">
                                          <i class="fa fa-trash" area-hidden="true"></i>
                                        </span>
                                       <?php
                                          }         
                                          ?>         
                                    </div>
                                </div>
                                
                              </div>

                              <div class="form-group row">
                                <div class="col-sm-4">
                                   <label for="" class="col-form-label">Name of authorized signatory :</label>
                                   <input type="text" class="form-control" name="authorized_signatory" id="authorized_signatory" placeholder=""  value="<?php echo $company['authorized_signatory']; ?>" maxlength="255" />
                                </div>
                                

                              </div>

                              <div class="form-group row">
                                <div class="col-sm-3">
                                  <?php
                                  if($company['digital_signature']!='')
                                  {
                                    $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$company['digital_signature'];
                                  }
                                  else
                                  {
                                    $profile_img_path = assets_url().'images/no_signature.png';
                                  }
                                  
                                  ?>
                                  <div class="photo-wapper mp-0">
                                    
                                    <div class="user_image">
                                      <a id="signature_img_prev"><img src="<?php echo $profile_img_path;?>"/></a>
                                        
                                    </div>

                                    
                                  </div>
                                </div>
                                <div class="col-sm-3">
                                  <span class="file">
                                      <input type="file" name="digital_signature" id="digital_signature" onchange="GetImagePreview(this,'signature_img_prev')" accept="image/png, image/gif, image/jpeg">
                                      <label for="digital_signature" class="new">Change Digital Signature</label>
                                    </span>
                                </div>
                                

                              </div>
                              <div class="form-group row">
                                <div class="col-sm-4">
                                  <button type="submit" class="btn btn-primary btn-round-shadow mt-20px mb-15 setting_submit_confirm">Save</button>
                                </div>
                                

                              </div>


                            </div>
                        </div>
                        
                        <div id="lead_api_settings" class="tabcontent">
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group">
                                <div class="col-sm-12">
                                   <h3 class="text-info">Indiamart API Settings <a href="javaScript:void(0)" id="im_add_div_toggle" class="pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3>
                                </div>
                             </div>
                             <input type="hidden" name="indiamart_setting_id" id="indiamart_setting_id" value="" />
                             <div class="out-holder" id="im_add_div" style="display:none;">
                                <div class="form-group row">
                                   <div class="col-sm-4">
                                      <label for="" class="col-form-label">Account Name:</label>
                                      <input type="text" class="form-control" name="indiamart_account_name" id="indiamart_account_name" placeholder="" value="" maxlength="255"  />
                                   </div>
                                   <div class="col-sm-4">
                                      <label for="" class="col-form-label">Mobile:</label>
                                      <input type="text" class="form-control only_natural_number" name="indiamart_glusr_mobile" id="indiamart_glusr_mobile" placeholder="" value="" maxlength="10"  />
                                   </div>
                                   <div class="col-sm-4">
                                      <label for="" class="col-form-label">Key:</label>
                                      <input type="password" class="form-control" name="indiamart_glusr_mobile_key" id="indiamart_glusr_mobile_key" placeholder="" value=""  />
                                      <!-- <a href="JavaScript:void(0);" id="show_indiamart_glusr_mobile_key" class="pull-right">Key Show</a> -->
                                   </div>
                                </div>
                                <div class="form-group row">
                                  <div class="col-md-12" >
                                    <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                    <div id="im_assign_rule_div">
                                      <select class="form-control" name="assign_rule" id="assign_rule">
                                        <option value="">Select One</option>
                                        <option value="1">Assign Leads on Round-Robin basis</option>
                                        <!-- <option value="2">Assign Leads by India & Foreign</option> -->
                                        <option value="3">Assign Leads by Indian State</option>
                                        <!-- <option value="4">Assign Leads by Keyword</option> -->
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <div class="col-md-12" id="im_rule_wise_view">

                                  </div>
                                </div>
                                
                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                   <thead>
                                      <tr>
                                         <th width="20%">Account Name</th>
                                         <th width="15%">Mobile</th>
                                         <!-- <th width="15%">Key</th> -->
                                         <th width="40%">Assign To</th>
                                         <th class="text-center" width="10%">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody id="im_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                             
                          </div>
                          <div>&nbsp;</div>
                        </div>
                        <div id="ti_api_settings" class="tabcontent">
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group">
                                <div class="col-sm-12">
                                   <h3 class="text-info">Tradeindia API Settings <a href="javaScript:void(0)" id="ti_add_div_toggle" class="pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3>
                                </div>
                             </div>
                             <input type="hidden" name="tradeindia_setting_id" id="tradeindia_setting_id" value="" />
                             <div class="out-holder" id="ti_add_div" style="display:none;">
                                <div class="form-group row">
                                   <div class="col-sm-6">
                                      <label for="" class="col-form-label">Account Name<span class="text-danger">*</span>:</label>
                                      <input type="text" class="form-control" name="tradeindia_account_name" id="tradeindia_account_name" placeholder="" value="" maxlength="255"  />
                                   </div>
                                   <div class="col-sm-6">
                                      <label for="" class="col-form-label">User ID<span class="text-danger">*</span>:</label>
                                      <input type="text" class="form-control" name="tradeindia_userid" id="tradeindia_userid" placeholder="" value="" maxlength="255"  />
                                   </div>
                                   <div class="col-sm-6">
                                      <label for="" class="col-form-label">Profile ID<span class="text-danger">*</span>:</label>
                                      <input type="text" class="form-control" name="tradeindia_profile_id" id="tradeindia_profile_id" placeholder="" value="" maxlength="10"  />
                                   </div>
                                   <div class="col-sm-6">
                                      <label for="" class="col-form-label">Key<span class="text-danger">*</span>:</label>
                                      <input type="password" class="form-control" name="tradeindia_key" id="tradeindia_key" placeholder="" value=""  />
                                      <!-- <a href="JavaScript:void(0);" id="show_indiamart_glusr_mobile_key" class="pull-right">Key Show</a> -->
                                   </div>
                                </div>
                                <div class="form-group row">
                                   <div class="col-md-12">
                                      <label for="" class="col-form-label">Employee assign for Tradeindia :</label>
                                      <?php if(count($user_list)){?>
                                      <ul class="employee_assign">
                                         <?php foreach($user_list as $user){ ?>
                                         <li>
                                            <label class="check-box-sec">
                                            <input type="checkbox" name="tradeindia_assign_to[]" id="" value="<?php echo $user->id; ?>" class="tradeindia_assign_to">
                                            <span class="checkmark"></span>
                                            <?php echo $user->name .'( Emp ID: '.$user->id.')'; ?>
                                            </label>                        
                                         </li>
                                         <?php } ?>
                                      </ul>
                                      <?php } ?>
                                   </div>
                                </div>
                                <div class="form-group row">
                                   <div class="col-md-12">
                                      <a href="javascript:void(0)" class="btn btn-success" id="ti_submit_confirm">Save</a>
                                      <a href="javascript:void(0)" class="btn btn-danger" id="ti_submit_close">Close</a>
                                   </div>
                                </div>
                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                   <thead>
                                      <tr>
                                         <th width="20%">Account Name</th>
                                         <th width="15%">User ID</th>
                                         <th width="15%">Profile ID</th>
                                         <!-- <th width="15%">Key</th> -->
                                         <th width="40%">Assign To</th>
                                         <th class="text-center" width="10%">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody id="ti_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                             
                          </div>
                          <div>&nbsp;</div>
                        </div>

                        <div id="jd_api_settings" class="tabcontent">
                          
                          <div class="col-md-12">
                             <div>&nbsp;</div>

                             <div class="form-group">
                                <div class="col-sm-12">
                                   <h3 class="text-info">JustDial Settings <a href="javaScript:void(0)" id="jd_add_div_toggle" class="pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3>
                                </div>
                             </div>
                             <div class="form-group row">
                              <div class="col-md-12">
                                <h6>API URL: <span
                                   class="text-primary"><?php echo base_url(); ?>capture/justdial/<?php echo $client_info->api_access_token; ?></span></h6>
                              </div>
                            </div>
                             <input type="hidden" name="justdial_setting_id" id="justdial_setting_id" value="" />
                             <div class="out-holder" id="jd_add_div" style="display:none;">                                
                                <div class="form-group row">
                                   <div class="col-md-12">
                                      <label for="" class="col-form-label">Employee assign for JustDial :</label>
                                      <?php if(count($user_list)){?>
                                      <ul class="employee_assign">
                                         <?php foreach($user_list as $user){ ?>
                                         <li>
                                            <label class="check-box-sec">
                                            <input type="checkbox" name="justdial_assign_to[]" id="" value="<?php echo $user->id; ?>" class="justdial_assign_to">
                                            <span class="checkmark"></span>
                                            <?php echo $user->name .'( Emp ID: '.$user->id.')'; ?>
                                            </label>                        
                                         </li>
                                         <?php } ?>
                                      </ul>
                                      <?php } ?>
                                   </div>
                                </div>
                                <div class="form-group row">
                                   <div class="col-md-12">
                                      <a href="javascript:void(0)" class="btn btn-success" id="jd_submit_confirm">Save</a>
                                      <a href="javascript:void(0)" class="btn btn-danger" id="jd_submit_close">Close</a>
                                   </div>
                                </div>
                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                   <thead>
                                      <tr>
                                         <th width="20%">Account Name</th>
                                        
                                         <th width="40%">Assign To</th>
                                         <th class="text-center" width="10%">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody id="jd_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                             
                          </div>
                          <div>&nbsp;</div>
                        </div>  

                      </div>            
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </form>
    </div>    
  </div>
  <div class="content-footer">
    <?php $this->load->view('admin/includes/footer'); ?>
  </div>
</div>

</div>
</div>
<?php $this->load->view('admin/includes/modal-html'); ?>
<?php $this->load->view('admin/includes/app.php'); ?> 
  </body>
</html>
<!-- /main area -->  
<script type="text/javascript">
   // window.paceOptions = {
   //   document: true,
   //   eventLag: true,
   //   restartOnPushState: true,
   //   restartOnRequestAfter: true,
   //   ajax: {
   //   trackMethods: [ 'POST','GET']
   //   }
   // };
</script>
<?php /* ?>
<script src="<?=base_url();?>vendor/jquery/dist/jquery.js"></script>
<script src="<?=base_url();?>vendor/pace/pace.js"></script>
<script src="<?=base_url();?>vendor/tether/dist/js/tether.js"></script>
<script src="<?=base_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?=base_url();?>vendor/fastclick/lib/fastclick.js"></script>
<script src="<?=base_url();?>scripts/constants.js"></script>
<script src="<?=base_url();?>scripts/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="<?=base_url();?>vendor/blueimp-file-upload/css/jquery.fileupload.css"/>
<link rel="stylesheet" href="<?=base_url();?>vendor/blueimp-file-upload/css/jquery.fileupload-ui.css"/>
<!-- page scripts -->
<script src="<?php echo base_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>
<!-- initialize page scripts -->
<script src="<?=base_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<!-- select2 -->
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
<?php */ ?>
<link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload.css"/>
<link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload-ui.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>


<script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url();?>js/custom/setting/edit.js?v=<?php echo rand(0,1000); ?>"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-tagsinput.js"></script> 
<script type="text/javascript">
   $(document).ready(function () {

            // popovers initialization - on hover
    $('[data-toggle="popover-hover"]').popover({
      html: true,
      trigger: 'hover',
      placement: 'right',
      content: function () { return '<img height="500" src="' + $(this).data('img') + '" />'; }
    });
     
       // $('#form').validate({
      
     //     rules: {
     //         name: {
     //             required: true
     //         }, 
     //         address: {
     //             required: true
     //         },
     //         city_id: {
     //             required: true
     //         },   
     //         state_id: {
     //             required: true
     //         },
     //         country_id: {
     //             required: true
     //         },
     //         pin: {
     //             required: true
     //         },  
     //         about_company: {
     //             required: true
     //         },  
     //         gst_number: {
     //             required: true
     //         },   
     //         pan_number: {
     //             required: true
     //         },  
     //         ceo_name: {
     //             required: true
     //         },   
     //         contact_person: {
     //             required: true
     //         },   
     //         email1: {
     //             required: true,
     //             email: true
     //         },
     //         mobile1: {
     //             required: true,
     //             minlength: '10'               
     //         },
     //         phone1: {
     //             required: true              
     //         }           
     //     },
     //     // Specify validation error messages
     //     messages: {      
     //       name: "Please enter company name",      
     //       address: "Please enter company address",    
     //       city_id: "Please select city",
     //       state_id:"Please select state",
     //       country_id:"Please select country",    
     //       pin: "Please enter Pin",  
     //       about_company: "Please enter about company", 
     //       gst_number:"Please enter GST", 
     //       pan_number:"Please enter PAN", 
     //       ceo_name:"Please enter CEO name", 
     //       contact_person:"Please enter contact person name", 
     //       email1:"Please enter valid email", 
     //       mobile1: "Please enter mobile no. (Length - 10)", 
     //       phone1: "Please enter phone no.",     
     //     },     
     // });
   });
   
</script>
<script>
   // Get the element with id="defaultOpen" and click on it
   document.getElementById("defaultOpen").click();
   //====================================================================
   // Get Image Preview
   // function GetImagePreview(input,displayDiv)
   // {
   //    if (input.files && input.files[0]) 
   //    {
   //       var reader = new FileReader();
   //       reader.onload = function (e) {
   //         var strHtml = '<img src="'+e.target.result+'" width="300">'; 
   //         $('#'+displayDiv).html(strHtml);  
   //       };
   //       reader.readAsDataURL(input.files[0]);
   //    }
   // }
   // Get Image Preview
   //====================================================================
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

