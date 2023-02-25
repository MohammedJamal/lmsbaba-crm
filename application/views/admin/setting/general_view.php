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
                 <div class="col-sm-12">
                    <div class="bg_white back_line full-bt-style">
                       <h4>
                           <div class="row">
                                 <div class="col-md-4">Settings  <svg height="32" id="svg8" style="vertical-align: text-top;" version="1.1" viewBox="0 0 8.4666665 8.4666669" width="32" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs2"/><g id="layer1" transform="translate(0,-288.53332)"><path d="m 3.7041666,288.7979 a 0.26460976,0.26460976 0 0 0 -0.2511475,0.18087 l -0.2687174,0.80615 c -0.1084927,0.0382 -0.2146168,0.082 -0.3183269,0.13178 l -0.7601602,-0.37982 a 0.26460976,0.26460976 0 0 0 -0.3054077,0.0496 l -0.7482748,0.74827 a 0.26460976,0.26460976 0 0 0 -0.049609,0.30541 l 0.379305,0.75861 c -0.049895,0.10423 -0.094048,0.21083 -0.1322917,0.31988 l -0.80511879,0.26871 a 0.26460976,0.26460976 0 0 0 -0.18086751,0.25115 v 1.05833 a 0.26460976,0.26460976 0 0 0 0.18086751,0.25115 l 0.80770259,0.26924 c 0.038069,0.10784 0.081782,0.21314 0.1312582,0.31625 l -0.3808553,0.76172 a 0.26460976,0.26460976 0 0 0 0.049609,0.3054 l 0.7482748,0.74879 a 0.26460976,0.26460976 0 0 0 0.3054077,0.0496 l 0.7601602,-0.38033 c 0.1036035,0.0495 0.209454,0.0932 0.3178101,0.13125 l 0.2692342,0.80719 a 0.26460976,0.26460976 0 0 0 0.2511475,0.18087 h 1.0583333 a 0.26460976,0.26460976 0 0 0 0.2511476,-0.18087 l 0.2692341,-0.80874 c 0.1075521,-0.0379 0.2128936,-0.0815 0.3157429,-0.13074 l 0.7622276,0.38137 a 0.26460976,0.26460976 0 0 0 0.3054074,-0.0496 l 0.748275,-0.74879 a 0.26460976,0.26460976 0 0 0 0.049609,-0.3054 l -0.3798218,-0.75965 c 0.049789,-0.10387 0.093561,-0.21018 0.1317749,-0.31884 L 8.0222491,293.548 a 0.26460976,0.26460976 0 0 0 0.1808676,-0.25115 v -1.05833 a 0.26460976,0.26460976 0 0 0 -0.1808676,-0.25115 l -0.806669,-0.26871 c -0.038193,-0.10832 -0.082077,-0.21427 -0.1317747,-0.31781 l 0.3803385,-0.76068 a 0.26460976,0.26460976 0 0 0 -0.049609,-0.30541 l -0.748275,-0.74827 a 0.26460976,0.26460976 0 0 0 -0.3054074,-0.0496 l -0.7580934,0.37878 c -0.1045763,-0.05 -0.2115013,-0.094 -0.3209105,-0.13229 l -0.2682007,-0.8046 a 0.26460976,0.26460976 0 0 0 -0.251148,-0.18088 z m 0.190686,0.52917 h 0.6769613 l 0.245463,0.73691 a 0.26460976,0.26460976 0 0 0 0.1757,0.17001 c 0.1722022,0.0512 0.3388331,0.11967 0.4971272,0.20464 a 0.26460976,0.26460976 0 0 0 0.243396,0.004 l 0.6934978,-0.34675 0.4785236,0.47852 -0.3482991,0.6966 a 0.26460976,0.26460976 0 0 0 0.00362,0.24391 c 0.084769,0.15725 0.1537229,0.32244 0.2051555,0.49351 a 0.26460976,0.26460976 0 0 0 0.1694987,0.17519 l 0.738456,0.24598 v 0.67696 l -0.7379393,0.24598 a 0.26460976,0.26460976 0 0 0 -0.1694987,0.17518 c -0.051373,0.1714 -0.1203285,0.337 -0.2051555,0.49454 a 0.26460976,0.26460976 0 0 0 -0.00362,0.24392 l 0.3477824,0.69556 -0.4785236,0.47904 -0.6981486,-0.34933 a 0.26460976,0.26460976 0 0 0 -0.2439128,0.004 c -0.1566825,0.0843 -0.3210488,0.15287 -0.4914429,0.20412 a 0.26460976,0.26460976 0 0 0 -0.175183,0.1695 l -0.2464967,0.74052 H 3.8948526 l -0.2464967,-0.73949 a 0.26460976,0.26460976 0 0 0 -0.175183,-0.17001 c -0.1710385,-0.0511 -0.3367447,-0.11916 -0.4940265,-0.20361 a 0.26460976,0.26460976 0 0 0 -0.243396,-0.004 l -0.6960816,0.3483 -0.4785238,-0.47904 0.3488159,-0.69763 a 0.26460976,0.26460976 0 0 0 -0.00362,-0.24391 c -0.08452,-0.15682 -0.1532676,-0.32191 -0.2046387,-0.49248 a 0.26460976,0.26460976 0 0 0 -0.1694987,-0.17467 l -0.73948973,-0.24649 v -0.67696 l 0.73742263,-0.24598 a 0.26460976,0.26460976 0 0 0 0.1700155,-0.17519 c 0.051313,-0.17172 0.1197532,-0.33773 0.2046387,-0.49557 a 0.26460976,0.26460976 0 0 0 0.00362,-0.24392 l -0.3472656,-0.69453 0.4785238,-0.47852 0.6960816,0.34778 a 0.26460976,0.26460976 0 0 0 0.2439127,-0.004 c 0.1573948,-0.0848 0.3227911,-0.15375 0.4940266,-0.20515 a 0.26460976,0.26460976 0 0 0 0.1751832,-0.1695 z" id="path940" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/><path d="m 4.2324219,290.91406 c -1.0197435,0 -1.8515625,0.83377 -1.8515625,1.85352 0,1.01974 0.831819,1.85156 1.8515625,1.85156 1.0197434,0 1.8535156,-0.83182 1.8535156,-1.85156 0,-1.01975 -0.8337722,-1.85352 -1.8535156,-1.85352 z m 0,0.5293 c 0.7337606,0 1.3242187,0.59046 1.3242187,1.32422 0,0.73376 -0.5904581,1.32226 -1.3242187,1.32226 -0.7337606,0 -1.3222657,-0.5885 -1.3222657,-1.32226 0,-0.73376 0.5885051,-1.32422 1.3222657,-1.32422 z" id="path961" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/></g></svg></div>
                                 <?php //$this->load->view('admin/setting/setting_menu'); ?>                                
                           </div>
                       </h4>
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
                    <div class="tab_gorup side-by-side custom-style-tab">

                      <div class="tab tab-group-sec">   
                        <div class="setting-title">General Settings</div>
                        <button class="tablinks" onClick="openCity(event, 'smtp_settings')" id="defaultOpen" type="button">
                           &nbsp;<img src="<?php echo assets_url(); ?>images/smtp.svg" style="margin-right: 10px;width:18px;" /> SMTP Settings
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'terms_and_conditions')" type="button">
                          <i class="fa fa-list-alt fa-fw" aria-hidden="true"></i> Quote Terms & Conditions
                        </button> 

                        <button class="tablinks" onClick="openCity(event, 'email_forwarding_settings')" type="button">
                           &nbsp;<img src="<?php echo assets_url(); ?>images/email_notification.svg" style="margin-right: 10px;width:18px;" /> Email Notification Settings
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'sms_forwarding_settings')" type="button">
                          <i class="fa fa-comment-o" aria-hidden="true"></i> SMS Notification Settings
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'whatsapp_forwarding_settings')" type="button">
                          <i class="fa fa-whatsapp" aria-hidden="true"></i> WhatsApp Notification Settings
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'product_group_setting')" id="" type="button">
                          <i class="fa fa-object-group fa-fw" aria-hidden="true"></i> Product Group
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'product_category_setting')" id="" type="button">
                          <i class="fa fa-th-list fa-fw" aria-hidden="true"></i> Product Category
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'product_unit_setting')" id="" type="button">
                          &nbsp;<img src="<?php echo assets_url(); ?>images/unit_type.svg" style="margin-right: 10px;width:18px;" /> Product Unit Type
                        </button>
                        
                        <button class="tablinks" onClick="openCity(event, 'lead_source_setting')" id="" type="button">
                          <i class="fa fa-user fa-fw" aria-hidden="true"></i>Lead Source
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'business_type_setting')" id="" type="button">
                         &nbsp;<img src="<?php echo assets_url(); ?>images/business_type.svg" style="margin-right: 10px;width:18px;" /> Business Type
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'lead_stage_settings')"  type="button">
                          <i class="fa fa-sliders fa-fw" aria-hidden="true"></i>Lead Stage Settings
                        </button>
                        
                        <button class="tablinks" onClick="openCity(event, 'lead_regret_setting')" id="" type="button">
                        <i class="fa fa-frown-o fa-fw" aria-hidden="true"></i> Lead Regret Reasons
                        </button> 

                        <button class="tablinks" onClick="openCity(event, 'auto_regrete_setting')" type="button">
                           &nbsp;<img src="<?php echo assets_url(); ?>images/auto_regret.svg" style="margin-right: 10px;width:18px;" /> Auto Lead Regret Settings
                        </button>
                        
                        <!-- <button class="tablinks" onClick="openCity(event, 'c2c_settings')" type="button">
                          <i class="fa fa-volume-control-phone fa-fw" aria-hidden="true"></i> Click to Call Settings
                        </button> -->
                        <button class="tablinks" onClick="openCity(event, 'my_documents')" type="button">
                          <i class="fa fa-file-text-o fa-fw" aria-hidden="true"></i> My Documents
                        </button>     
                        
                        <!-- <button class="tablinks" onClick="openCity(event, 'key_performance_indicator')" id="" type="button">
                          <i class="fa fa-line-chart" aria-hidden="true"></i> Manage KPIs
                        </button> -->

                        <button class="tablinks" onClick="openCity(event, 'auto_expire_for_idle_setting')" type="button">
                           &nbsp;<img src="<?php echo assets_url(); ?>images/auto_session_expire.svg" style="margin-right: 10px;width:18px;" /> Auto Session Expire
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'menu_label_setting')" id="" type="button">
                          <i class="fa fa-tag" aria-hidden="true"></i> Manage Menu Label
                        </button>

                        <button class="tablinks" onClick="openCity(event, 'employee_type_setting')" id="" type="button">
                         &nbsp;<i class="fa fa-user fa-fw" aria-hidden="true"></i> User Type
                        </button>

                        

                      </div>

                      <div class="tab-section">

                           <!-- SMTP Settings - START -->
                           <div id="smtp_settings" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info"><img src="<?php echo assets_url(); ?>images/smtp.svg" style="margin-right: 10px;width:18px;" /> SMTP Settings <a href="javaScript:void(0)" id="smtp_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3>
                                    </div>
                                 </div>
                                 <input type="hidden" name="smtp_setting_id" id="smtp_setting_id" value="" />
                                 <div class="out-holder" id="smtp_add_div" style="display:none;">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">SMTP Server:</legend>
                                        <div class="control-group">
                                          <div class="form-row">
                                             <div class="col-md-12">
                                                          
                                                <ul class="employee_assign">
                                                   <li>
                                                      <label class="check-box-sec radio">
                                                      <input type="radio" name="smtp_type" id="smtp_type_2" value="2" class="smtp_type">
                                                      <span class="checkmark"></span>
                                                      Gmail 
                                                      <a class="" data-toggle="popover-hover" data-img="<?php echo assets_url() ?>images/Gmail-smtp-steps.png"><i class="fa fa-cog text-danger" aria-hidden="true" ></i></a>

                                                      </label>
                                                   </li>
                                                   <li>
                                                      <label class="check-box-sec radio">
                                                      <input type="radio" name="smtp_type" id="smtp_type_3" value="3" class="smtp_type">
                                                      <span class="checkmark"></span>
                                                      Other 
                                                      </label>
                                                   </li>
                                                </ul>                
                                             </div>
                                          </div>
                                          
                                          <div class="form-row mb-10">
                                             <div class="col-sm-6">
                                                <label for="" class="col-form-label">Host:</label>
                                                <input type="text" class="form-control" name="smtp_host" id="smtp_host" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             <div class="col-sm-6">
                                                <label for="" class="col-form-label">Port:</label>
                                                <input type="text" class="form-control only_natural_number" name="smtp_port" id="smtp_port" placeholder="" value="" maxlength="10"  />
                                             </div>

                                             
                                          </div>
                                          <div class="form-row">
                                             
                                             <div class="col-sm-6">
                                                <label for="" class="col-form-label">Email:</label>
                                                <input type="text" class="form-control" name="smtp_username" id="smtp_username" placeholder="" value="" maxlength="255"  />
                                             </div>                                             
                                             <div class="col-sm-6">
                                                <label for="" class="col-form-label">Password:</label>
                                                <input type="password" class="form-control" name="smtp_password" id="smtp_password" placeholder="" value=""  />
                                                <a href="JavaScript:void(0);" id="show_smtp_password" class="pull-right">Key Show</a>
                                             </div>
                                          </div>
                                          
                                          <div class="form-row">
                                             <div class="col-md-12">
                                                <a href="javascript:void(0)" class="btn btn-success fix-w" id="smtp_submit_confirm">Save</a>
                                                <a href="javascript:void(0)" class="btn btn-danger fix-w" id="smtp_submit_close">Close</a>
                                             </div>
                                          </div>
                                            <!-- <div class="form-row">
                                                <div class="col-md-6">
                                                    <label for="lrr_name">Name:</label>
                                                    <input type="text" class="form-control " name="lrr_name" id="lrr_name" placeholder="" value="" maxlength="255" >
                                                </div>
                                                
                                                <div class="col-md-1">
                                                    <a href="javascript:void(0)" class="btn btn-success mt-25" id="lead_regret_reason_add_submit">Add</a>
                                                </div>
                                            </div> -->
                                            
                                        </div>
                                    </fieldset>
                                    

                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                       <thead>
                                          <tr>
                                             <th width="20%">Mail Server</th>
                                             <th width="20%">Username</th>
                                             <!-- <th width="20%">Password</th> -->
                                             <th width="20%" class="text-center">Active</th>
                                             <th class="text-center" width="10%">Action</th>
                                          </tr>
                                       </thead>
                                       <tbody id="smtp_tcontent" class="t-contant-img"></tbody>
                                    </table>
                                 </div>
                                 <?php /* ?>
                                 <div class="form-group" id="submit_div">
                                    <div class="col-sm-12 mt-">
                                       <button type="submit" class="btn btn-primary btn-round-shadow mt-20px mb-15">Save</button>
                                    </div>
                                 </div>
                                 <?php */ ?>
                              </div>
                              <div>&nbsp;</div>
                           </div>
                           <!-- SMTP Settings - END -->

                           <!-- Quote Terms & Conditions - START -->
                           <div id="terms_and_conditions" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-md-12"><h3 class="text-info"><i class="fa fa-list-alt fa-fw" aria-hidden="true"></i> Terms & Conditions</h3></div>
                                    
                                 </div>
                                 <div class="form-group row">
                                    <div class="col-md-12 mb-25">
                                       <h3 class="text-info small">T&C for Domestic Leads <a href="javaScript:void(0)" id="dterms_add_div_toggle" class="pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3>
                                       <input type="hidden" name="dterms_id" id="dterms_id" value="" />
                                       <div class="out-holder row" id="dterms_add_div" style="display:none;">
                                          
                                          <div class="col-md-12">
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Add New T&C for Domestic Leads:</legend>
                                                <div class="control-group">

                                                  <div class="form-row mb-10">
                                                     <div class="col-sm-12">
                                                        <label for="" class="col-form-label">Name:</label>
                                                        <input type="text" class="form-control" name="dterms_name" id="dterms_name" placeholder="" value="" maxlength="255"  />
                                                        
                                                     </div>
                                                     <div class="col-sm-12">
                                                        <label for="" class="col-form-label">Value:</label>
                                                        <!-- <input type="text" class="form-control" name="dterms_value" id="dterms_value" placeholder="" value="" maxlength="255"  /> -->
                                                        <textarea class="form-control basic-wysiwyg-editor" name="dterms_value" id="dterms_value" style="height: 90px;"></textarea>
                                                     </div>
                                                  </div>
                                                  <div class="form-row">
                                                    <div class="col-md-12">
                                                        <a href="javascript:void(0)" class="btn btn-success fix-w" id="dterns_submit_confirm">Save</a>
                                                        <a href="javascript:void(0)" class="btn btn-danger fix-w" id="dterms_submit_close">Close</a>
                                                     </div>
                                                  </div>
                                                    <!-- <div class="form-row">
                                                        <div class="col-md-6">
                                                            <label for="lrr_name">Name:</label>
                                                            <input type="text" class="form-control " name="lrr_name" id="lrr_name" placeholder="" value="" maxlength="255" >
                                                        </div>
                                                        
                                                        <div class="col-md-1">
                                                            <a href="javascript:void(0)" class="btn btn-success mt-25" id="lead_regret_reason_add_submit">Add</a>
                                                        </div>
                                                    </div> -->
                                                    
                                                </div>
                                            </fieldset>
                                            
                                        </div>

                                          

                                       </div>
                                       <div class="table-responsive">
                                          <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="">
                                             <thead>
                                                <tr>
                                                   <th width="20%">Name</th>
                                                   <th width="60%">Value</th>
                                                   <th class="text-center" width="20%">Action</th>
                                                </tr>
                                             </thead>
                                             <tbody id="dterms_tcontent" class="t-contant-img"></tbody>
                                          </table>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <h3 class="text-info small">T&C for Export Leads <a href="javaScript:void(0)" id="iterms_add_div_toggle" class="pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3>
                                       <input type="hidden" name="iterms_id" id="iterms_id" value="" />
                                       
                                       <div class="out-holder row" id="iterms_add_div" style="display:none;">
                                          
                                          <div class="col-md-12">
                                              <fieldset class="scheduler-border">
                                                  <legend class="scheduler-border">Add New T&C for Export Leads:</legend>
                                                  <div class="control-group">
                                                      
                                                      <div class="form-row mb-10">
                                                         <div class="col-sm-12">
                                                            <label for="" class="col-form-label">Name:</label>
                                                            <input type="text" class="form-control" name="iterms_name" id="iterms_name" placeholder="" value="" maxlength="255"  />
                                                         </div>
                                                         <div class="col-sm-12">
                                                            <label for="" class="col-form-label">Value:</label>
                                                            <!-- <input type="text" class="form-control" name="iterms_value" id="iterms_value" placeholder="" value="" maxlength="255"  /> -->
                                                            <textarea class="form-control basic-wysiwyg-editor" name="iterms_value" id="iterms_value" style="height: 90px;"></textarea>
                                                         </div>
                                                      </div>
                                                      <div class="form-row">
                                                         <div class="col-md-12">
                                                            <a href="javascript:void(0)" class="btn btn-success fix-w" id="iterns_submit_confirm">Save</a>
                                                            <a href="javascript:void(0)" class="btn btn-danger fix-w" id="iterms_submit_close">Close</a>
                                                         </div>
                                                      </div>

                                                  </div>
                                              </fieldset>
                                              
                                          </div>
                                          
                                       </div>
                                       <div class="table-responsive">
                                          <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="">
                                             <thead>
                                                <tr>
                                                   <th width="20%">Name</th>
                                                   <th width="60%">Value</th>
                                                   <th class="text-center" width="20%">Action</th>
                                                </tr>
                                             </thead>
                                             <tbody id="iterms_tcontent" class="t-contant-img"></tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- Quote Terms & Conditions - END -->

                           <!-- Email Notification Settings - START -->
                           <div id="email_forwarding_settings" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info"><img src="<?php echo assets_url(); ?>images/email_notification.svg" style="margin-right: 10px;width:18px;" /> Email Notification Settings</h3>
                                    </div>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table email-settings-table">
                                       <thead>
                                          <tr>
                                             <th width="15%" align="center">Auto Email Actions</th>
                                             <th width="15%" align="center">Send Auto Email</th>
                                             <th width="15%" align="center">Mail to Client</th>
                                             <th width="20%" align="center">Mail to Relationship Manager</th>
                                             <th width="15%" align="center">Mail to Manager</th>
                                             <th width="20%" align="center">Mail to Skip Manager</th>                                                   
                                          </tr>
                                       </thead>
                                       <tbody id="email_forwarding_tcontent" class="t-contant-img"></tbody>
                                    </table>
                                 </div>
                              </div>
                              <div>&nbsp;</div>
                           </div>
                           <!-- Email Notification Settings - END -->

                           <!-- SMS Notification Settings - START -->
                           <div id="sms_forwarding_settings" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info"><i class="fa fa-comment-o" aria-hidden="true"></i> SMS Notification Settings</h3>
                                    </div>
                                 </div>
                                 <div class="table-responsive">
                                    <?php if(count($sms_api_rows)>0 && count($sms_forwarding_setting_rows)>0){ ?>
                                    <?php //if(count($sms_forwarding_setting_rows)>0){ ?>
                                    <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table-- email-settings-table--">
                                       <thead>
                                          <tr>
                                             <th width="15%" align="center">Auto SMS Actions</th>
                                             <th width="15%" align="center">Send Auto SMS</th>
                                             <th width="15%" align="center">SMS to Client</th>
                                             <th width="20%" align="center">SMS to Relationship Manager</th>
                                             <th width="15%" align="center">SMS to Manager</th>
                                             <th width="20%" align="center">SMS to Skip Manager</th>                                                   
                                          </tr>
                                       </thead>
                                       <tbody id="sms_forwarding_tcontent" class="t-contant-img--"></tbody>
                                    </table>
                                    <?php }else{ ?>
                                       <h3 class="text-danger">Not Active</h3>
                                       <b class="text-success">Note:</b><small> Please contact to LMSBABA Team to get SMS service. </small>
                                    <?php } ?>
                                 </div>
                              </div>
                              <div>&nbsp;</div>
                           </div>
                           <!-- SMS Notification Settings - END -->

                           <!-- WhatsApp Notification Settings - START -->
                           <div id="whatsapp_forwarding_settings" class="tabcontent ">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info"><i class="fa fa-whatsapp" aria-hidden="true"></i> WhatsApp Notification Settings</h3>
                                    </div>
                                 </div>
                                 <div class="table-responsive ">
                                    <?php if(count($whatsapp_api_rows)>0 && count($whatsapp_forwarding_setting_rows)>0){ ?>
                                    <?php //if(count($sms_forwarding_setting_rows)>0){ ?>
                                    <table class="table table-bordered table-striped m-b-0 th_color lead-board " id="lead_table-- email-settings-table--">
                                       <thead>
                                          <tr>
                                             <th width="15%" align="center">Auto Actions</th>
                                             <th width="15%" align="center">Send Auto</th>
                                             <th width="15%" align="center">send to Client</th>
                                             <th width="20%" align="center">send to Relationship Manager</th>
                                             <th width="15%" align="center">send to Manager</th>
                                             <th width="20%" align="center">send to Skip Manager</th>                                                   
                                          </tr>
                                       </thead>
                                       <tbody id="whatsapp_forwarding_tcontent" class="t-contant-img-- small-check"></tbody>
                                    </table>
                                    <?php }else{ ?>
                                       <h3 class="text-danger">Not Active</h3>
                                       <b class="text-success">Note:</b><small> Please contact to LMSBABA Team to get SMS service. </small>
                                    <?php } ?>
                                 </div>
                              </div>
                              <div>&nbsp;</div>
                           </div>
                           <!-- WhatsApp Notification Settings - END -->

                           <!-- Product Group - START -->
                           <div id="product_group_setting" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-md-12"><h3 class="text-info"><i class="fa fa-object-group fa-fw" aria-hidden="true"></i> Product Group List</h3></div>
                                 </div>
                                                  
                                 <div id="product_group_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Product Group - END -->

                           <!-- Product Category - START -->
                           <div id="product_category_setting" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-md-12"><h3 class="text-info"><i class="fa fa-th-list fa-fw" aria-hidden="true"></i> Product Category List</h3></div>
                                    
                                 </div>                   
                                 <div id="product_category_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Product Category - END -->

                           <!-- Product Unit Type - START -->
                           <div id="product_unit_setting" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-md-12"><h3 class="text-info"><img src="<?php echo assets_url(); ?>images/unit_type.svg" style="margin-right: 10px;width:18px;" /> Product Unit Type List</h3></div>
                                    
                                 </div>                   
                                 <div id="product_unit_type_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Product Unit Type - EBD -->

                           <!-- Lead Source - START -->
                           <div id="lead_source_setting" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group">
                                    <div class="col-md-12"><h3 class="text-info"><i class="fa fa-user fa-fw" aria-hidden="true"></i> Lead Source List</h3></div>
                                    
                                 </div>                   
                                 <div id="lead_source_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Lead Source - END -->

                           <!-- Business Type - START -->
                           <div id="business_type_setting" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-md-12"><h3 class="text-info"><img src="<?php echo assets_url(); ?>images/business_type.svg" style="margin-right: 10px;width:18px;" /> Business Type List</h3></div>
                                    
                                 </div>                   
                                 <div id="business_type_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Business Type - EBD -->

                           <!-- Lead Stage Settings - START -->
                           <div id="lead_stage_settings" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-md-12"><h3 class="text-info"><i class="fa fa-sliders fa-fw" aria-hidden="true"></i> Lead Stage List</h3></div>
                                 </div>                   
                                 <div id="lead_stage_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Lead Stage Settings - END -->
                           
                           
                           <!-- Lead Regret Reasons - START -->
                           <div id="lead_regret_setting" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group">
                                    <div class="col-md-12"><h3 class="text-info"><i class="fa fa-frown-o fa-fw" aria-hidden="true"></i> Lead Regret Reasons List</h3></div>
                                    
                                 </div>                   
                                 <div id="lead_regret_reason_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Lead Regret Reasons - END --> 
                           
                           <!-- Auto Regret Settings - START -->
                           <div id="auto_regrete_setting" class="tabcontent">
                              <div class="col-md-12">
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                    <div class="col-sm-12">
                                       <h5>What is Auto Lead Regret?</h5>
                                       <p>Auto Lead Regret is an option to keep your lead board clean and workable by removing unfollowed leads after a certain time period. You can choose the time period between minimum 10 days to 30 days of your last follow-up date. </p>
                                       <p><b>For example:</b> If you select Auto Lead Regret period of 10 days and there is a lead which has a next follow-up date of 10 Jan 2022 but you are not following this lead till 19th Jan 2022 then on 20th Jan 2022, this lead will be marked Auto Regretted.  You can make this lead active by setting the new followup date at any given point of time.</p>
                                    </div>
                                    </div>                   
                                    <div class="form-group">  
                                    
                                       <div class="col-md-12">
                                             <label class="check-box-sec">
                                                <input type="checkbox" value="Y" class="" name="is_cronjobs_auto_regretted_on" id="is_cronjobs_auto_regretted_on" <?php echo ($company['is_cronjobs_auto_regretted_on']=='Y')?'checked':''; ?>>
                                                <span class="checkmark"></span>
                                             </label>
                                             <b>Auto Lead Regretted</b>
                                       </div>
                                       <div class="col-md-12">
                                             <b>Mark unfollowed leads as Auto Lead Regretted after </b>
                                             <select class="custom-select form-control" name="auto_regretted_day_interval" id="auto_regretted_day_interval" style="width:70px;">                                        
                                                <?php for($i=10;$i<=30;$i++){?>
                                                <option value="<?php echo $i;?>" <?php if($company['auto_regretted_day_interval']==$i){echo'selected';} ?> ><?php echo $i;?></option>
                                                <?php } ?>
                                             </select>
                                          <b>Days.</d>
                                          <!-- <input type="number" min="10" max="30" class="form-control" name="auto_regretted_day_interval" id="auto_regretted_day_interval" placeholder="" value="<?php echo $company['auto_regretted_day_interval']; ?>" style="width:80px;" readonly="true" /> days. -->
                                       </div>
                                       <div class="col-md-12">
                                          <a href="javascript:void(0)" class="btn btn-success pull-left" id="auto_regretted_save_submit">Save</a>
                                       </div>                              
                                    </div>                              
                                 </div>       
                           </div>
                           <!-- Auto Regret Settings - END -->

                           <?php /* ?>
                           <div id="c2c_settings" class="tabcontent">
                              <?php         
                              $c2c_api_dial_url=$company['c2c_api_dial_url'];
                              $c2c_api_userid=$company['c2c_api_userid'];
                              $c2c_api_password=$company['c2c_api_password'];
                              $c2c_api_client_name=$company['c2c_api_client_name'];
                              if($c2c_api_dial_url!="" && $c2c_api_userid!="" && $c2c_api_password!="" && $c2c_api_client_name!="")
                              {
                                 $c2c_is_active='Y';
                              }
                              else
                              {
                                 $c2c_is_active='N';
                              }
                              ?>
                              <div class="col-md-12">
                                 <!-- <div>&nbsp;</div> -->
                                 <div class="form-group hide">
                                    <div class="col-sm-12">
                                       <h3 class="text-info">Click to Call  Settings </h3>
                                    </div>
                                 </div>
                                 
                                 <div class="out-holder" id="" style="display:block;">
                                    <?php 
                                    if($c2c_is_active=='Y')
                                    {
                                    ?>
                                    <div class="form-group row hide"> 
                                          <div class="col-sm-12">
                                          <label for="" class="col-form-label">API URL:</label>
                                          <input type="text" class="form-control " name="c2c_api_dial_url" id="c2c_api_dial_url" placeholder="" value="<?php echo $company['c2c_api_dial_url']; ?>" maxlength="255" readonly="true"  />
                                          </div>

                                          <div class="col-sm-4">
                                          <label for="" class="col-form-label">User ID:</label>
                                          <input type="text" class="form-control " name="c2c_api_userid" id="c2c_api_userid" placeholder="" value="<?php echo $company['c2c_api_userid']; ?>" maxlength="255" readonly="true" />
                                          </div> 
                                          <div class="col-sm-4">
                                          <label for="" class="col-form-label">Password:</label>
                                          <input type="text" class="form-control " name="c2c_api_password" id="c2c_api_password" placeholder="" value="<?php echo $company['c2c_api_password']; ?>" maxlength="255" readonly="true" />
                                          </div> 
                                          <div class="col-sm-4">
                                          <label for="" class="col-form-label">Client Name:</label>
                                          <input type="text" class="form-control " name="c2c_api_client_name" id="c2c_api_client_name" placeholder="" value="<?php echo $company['c2c_api_client_name']; ?>" maxlength="255" readonly="true" />
                                          </div>            
                                    </div>
                                    
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                    <div class="form-group">
                                       <div class="col-sm-12">
                                          <div>&nbsp;</div>
                                          <h3 class="text-danger">Not Active</h3>
                                          <b class="text-success">Note:</b><small> Please contact to LMSBABA Team to get click to Call service. </small>
                                       </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                 </div>                    
                              </div>

                              <?php if($c2c_is_active=='Y'){ ?>
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group">
                                    <div class="col-sm-12">
                                       <h3 class="text-info">Click to call User List <a href="javaScript:void(0)" id="c2c_add_div_toggle" class="pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3>
                                    </div>
                                 </div>
                                 <?php  ?>
                                 <input type="hidden" name="c2c_setting_id" id="c2c_setting_id" value="" />
                                 <div class="out-holder" id="c2c_add_div" style="display:none;">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label for="" class="col-form-label">Assign for C2C :</label>
                                          <?php if(count($user_list)){?>
                                          <ul class="employee_assign">
                                             <?php foreach($user_list as $user){ ?>
                                             <li>
                                                <label class="check-box-sec radio">
                                                <input type="radio" name="user_c2c_assign_to" id="" value="<?php echo $user->id; ?>" class="user_c2c_assign_to">
                                                <span class="checkmark"></span>
                                                <?php echo $user->name .'( Emp ID: '.$user->id.')'; ?>
                                                </label>                        
                                             </li>
                                             <?php } ?>
                                          </ul>
                                          <?php } ?>
                                       </div>
                                    </div>
                                    
                                    <div class="form-group row">    <div class="col-sm-4">
                                          <label for="" class="col-form-label">Name:</label>
                                          <input type="text" class="form-control " name="c2c_caller_name" id="c2c_caller_name" placeholder="" value="" maxlength="255" readonly="true"  />
                                          </div>
                                          <div class="col-sm-4">
                                          <label for="" class="col-form-label">Personal Mobile:</label>
                                          <input type="text" class="form-control only_natural_number" name="c2c_mobile" id="c2c_mobile" placeholder="" value="" maxlength="10" readonly="true" />
                                          </div> 
                                          <div class="col-sm-4">
                                          <label for="" class="col-form-label">Office No.( C2C No.):</label>
                                          <input type="text" class="form-control only_natural_number" name="c2c_office_no" id="c2c_office_no" placeholder="" value="" maxlength="10"  />
                                          </div>            
                                    </div>
                                    
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <a href="javascript:void(0)" class="btn btn-success" id="c2c_submit_confirm">Save</a>
                                          <a href="javascript:void(0)" class="btn btn-danger" id="c2c_submit_close">Close</a>
                                       </div>
                                    </div>
                                 </div>
                                 <?php  ?>
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                       <thead>
                                          <tr>
                                                <th width="20%">LMS User ID</th>
                                                <th width="20%">User</th>
                                                <th width="15%">Personal No.</th>
                                                <th width="15%">Office No.(C2C No.)</th>
                                                <th class="text-center" width="10%">Action</th>
                                          </tr>
                                       </thead>
                                       <tbody id="c2c_tcontent" class="t-contant-img"></tbody>
                                    </table>
                                 </div>
                                 
                              </div>
                              <?php } ?>        
                           </div>
                           <?php */ ?>

                           <!-- My Documents - START -->
                           <div id="my_documents" class="tabcontent">
                              <div class="col-md-12">
                                    <div>&nbsp;</div>
                                    <div class="form-group row">
                                      <div class="col-sm-12">
                                        <h3 class="text-info"><i class="fa fa-file-text-o fa-fw" aria-hidden="true"></i> My Document</h3>
                                      </div>
                                    </div>                   
                                    <div class="row">     
                                    <div id="my_document_tcontent" class="form-group row"></div>
                                    </div>
                                 </div>       
                           </div>
                           <!-- My Documents - END -->

                           <!-- key_performance_indicator - START -->
                           <?php /* ?>
                          <div id="key_performance_indicator" class="tabcontent">
                            <div class="col-md-12">
                                <div>&nbsp;</div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <h3 class="text-info">
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> Manage KPIs <a href="javaScript:void(0)" id="key_performance_indicator_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                        </h3>
                                    </div>
                                </div>
                                
                                <input type="hidden" id="key_performance_indicator_id" name="key_performance_indicator_id" value="" />
                                <div class="out-holder" id="key_performance_indicator_add_div" style="display:none;">
                                    <div class="col-md-12">
                                      <fieldset class="scheduler-border">
                                          <legend class="scheduler-border">Add New KPI:</legend>
                                          <div class="control-group">

                                              <div class="form-row mb-10">
                                                <div class="col-sm-12">
                                                    <label for="sms_account_name">Name<span class="text-danger">*</span>:</label>
                                                    <input type="text" class="form-control" name="key_performance_indicator_name" id="key_performance_indicator_name" placeholder="" value="" maxlength="255"  />
                                                </div>
                                              </div>
                                              
                                              <div class="form-row">
                                                <div class="col-md-12">
                                                    <a href="javascript:void(0)" class="btn btn-success fix-w" id="key_performance_indicator_submit_confirm">Save</a>
                                                    <a href="javascript:void(0)" class="btn btn-danger fix-w" id="key_performance_indicator_submit_close">Close</a>
                                                </div>
                                              </div>
                                              
                                          </div>
                                      </fieldset>
                                      
                                  </div>
                                    

                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                      <thead>
                                          <tr>
                                              <th width="50%">Name</th>
                                              <th width="30%">KPI Type</th>
                                              <th class="text-center" width="20%">Action</th>
                                          </tr>
                                      </thead>
                                      <tbody id="kpi_tcontent" class="t-contant-img"></tbody>
                                    </table>
                                </div>
                                
                              </div>
                              <div>&nbsp;</div>
                          </div>
                          <?php */ ?>
                          <!-- key_performance_indicator- END -->

                           <!-- Auto Regret Settings - START -->
                           <div id="auto_expire_for_idle_setting" class="tabcontent">
                              <div class="col-md-12">
                                    <div>&nbsp;</div>  
                                    <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info"><img src="<?php echo assets_url(); ?>images/auto_session_expire.svg" style="margin-right: 10px;width:18px;" /> Auto Session Expire </h3>
                                    </div>
                                 </div>                                                     
                                    <div class="form-group">  
                                    
                                       <div class="col-md-12">
                                             <label class="check-box-sec">
                                                <input type="checkbox" value="Y" class="" name="is_session_expire_for_idle" id="is_session_expire_for_idle" <?php echo ($company['is_session_expire_for_idle']=='Y')?'checked':''; ?>>
                                                <span class="checkmark"></span>
                                             </label>
                                             <b>Auto Session Expire</b>
                                       </div>
                                       <div class="col-md-12">
                                             <b>Mark Auto Session Expire after </b>
                                             <select class="custom-select form-control" name="idle_time" id="idle_time" style="width:70px;">                                        
                                                <?php for($i=1;$i<=60;$i++){?>
                                                <option value="<?php echo $i;?>" <?php if($company['idle_time']==$i){echo'selected';} ?> ><?php echo $i;?></option>
                                                <?php } ?>
                                             </select>
                                          <b>Mins for idle user.</d>
                                          
                                       </div>
                                       <div class="col-md-12">
                                          <a href="javascript:void(0)" class="btn btn-success pull-left" id="auto_session_expire_save_submit">Save</a>
                                       </div>                              
                                    </div>                              
                                 </div>       
                           </div>
                           <!-- Auto Regret Settings - END -->
                                       
                           <!-- menu_label_setting - START -->
                          <div id="menu_label_setting" class="tabcontent">
                              <div class="col-md-12">                                
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <h3 class="text-info">
                                        <i class="fa fa-tag" aria-hidden="true"></i> Manage Menu Label 
                                        </h3>
                                    </div>
                                </div> 
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                      <thead>
                                          <tr>
                                              <th width="30%" class="text-left">Menu Labels</th>
                                              <th width="70%" class="text-left">Menu Labels Alias</th>
                                          </tr>
                                      </thead>
                                      <tbody id="menu_label_alias_tcontent" class="t-contant-img"></tbody>
                                    </table>
                                </div>
                              </div>
                          </div>
                          <!-- menu_label_setting- END -->

                          <!-- Employee Type - START -->
                          <div id="employee_type_setting" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-md-12"><h3 class="text-info"><i class="fa fa-user fa-fw" aria-hidden="true"></i> User Type List</h3></div>
                                    
                                 </div>                   
                                 <div id="employee_type_tcontent" class="form-group row"></div>
                              </div>       
                           </div>
                           <!-- Employee Type - EBD -->
                          
                           

                           
                           
                           
                           
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

