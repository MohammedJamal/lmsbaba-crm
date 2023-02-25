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
                        <div class="setting-title">API Settings</div>
                           <button class="tablinks" onClick="openCity(event, 'lead_api_settings')" id="defaultOpen" type="button">
                              <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api1.png"></span>  Indiamart API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'ti_api_settings')" type="button">
                              <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api2.png"></span> 
                              Tradeindia API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'aj_api_settings')" type="button">
                              <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/aajjo.jpg"></span> 
                              Aajjo API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'jd_api_settings')" type="button">
                              <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api3.png"></span>JustDial API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'ei_api_settings')" id="" type="button">
                              <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/ei.png"></span>  Exporter India API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'websitep_api_setting')" type="button">
                           <i class="fa fa-cogs fa-fw" aria-hidden="true"></i> Website API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'sms_api_settings')" type="button">
                              <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api-sms.png"></span> 
                              SMS API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'whatsapp_api_settings')" type="button">
                              <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/social-whatsapp.png"></span> 
                              WhatsApp API Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'c2c_settings')" type="button">
                           <i class="fa fa-volume-control-phone fa-fw" aria-hidden="true"></i> Click to Call Settings
                           </button>
                           <button class="tablinks" onClick="openCity(event, 'google_map_api_key_setting')" type="button">
                           <i class="fa fa-map-marker" aria-hidden="true"></i> Google Map API Settings
                           </button> 
                           <?php //if($this->session->userdata('admin_session_data')['client_id']=='1' || $this->session->userdata('admin_session_data')['client_id']=='17' || $this->session->userdata('admin_session_data')['client_id']=='13'){ ?>
                           <button class="tablinks" onClick="openCity(event, 'facebook_api_key_setting')" type="button">
                           <i class="fa fa-facebook-official" aria-hidden="true"></i> Facebook Lead API Settings
                           </button>
                           <!-- <button class="tablinks" onClick="openCity(event, 'fb_lead_assignment_setting')" type="button">
                           <i class="fa fa-facebook-official" aria-hidden="true"></i> FB Lead Assignment Settings
                           </button>   -->
                           <?php //} ?>                      
                      </div>
                      <div class="tab-section">
                        
                        
                        <div id="lead_api_settings" class="tabcontent">
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group row">
                                <div class="col-md-12"><h3 class="text-info"><span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api1.png"></span> Indiamart API Settings <a href="javaScript:void(0)" id="im_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a></h3></div>
                             </div>
                             
                             <input type="hidden" name="indiamart_setting_id" id="indiamart_setting_id" value="" />
                             <div class="out-holder" id="im_add_div" style="display:none;">

                                <fieldset class="scheduler-border">
                                  <legend class="scheduler-border">Add New Indiamart API:</legend>
                                  <div class="control-group">
                                      
                                       <div class="form-row mb-10">
                                         <div class="col-sm-12">
                                         <ul class="employee_assign">                                                         
                                             <li>
                                                <label class="check-box-sec radio">
                                                   <input type="radio" name="is_old_version" id="is_old_version_1" value="Y" class="" checked="">
                                                   <span class="checkmark"></span>
                                                   Old Version                                                 
                                                </label>                        
                                             </li>  
                                             <li>
                                                <label class="check-box-sec radio">
                                                   <input type="radio" name="is_old_version" id="is_old_version_2" value="N" class="">
                                                   <span class="checkmark"></span>
                                                   New Version                                            
                                                </label>                        
                                             </li>                                                                                                    
                                          </ul>
                                         </div>                                         
                                      </div>

                                      <div class="form-row mb-10">
                                         <div class="col-sm-6">
                                            <label for="" class="col-form-label">Account Name:</label>
                                            <input type="text" class="form-control" name="indiamart_account_name" id="indiamart_account_name" placeholder="" value="" maxlength="255"  />
                                         </div>
                                         <div class="col-sm-6">
                                            <label for="" class="col-form-label">Mobile:</label>
                                            <input type="text" class="form-control only_natural_number" name="indiamart_glusr_mobile" id="indiamart_glusr_mobile" placeholder="" value="" maxlength="10"  />
                                         </div>
                                         
                                      </div>
                                      <div class="form-row mb-10">
                                        <div class="col-sm-6">
                                            <label for="" class="col-form-label">Key:</label>
                                            <input type="password" class="form-control" name="indiamart_glusr_mobile_key" id="indiamart_glusr_mobile_key" placeholder="" value=""  />
                                            
                                         </div>
                                        <div class="col-md-6">
                                          <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                          <div id="im_assign_rule_div">
                                            <select class="form-control" name="assign_rule" id="assign_rule">
                                              <option value="">Select One</option>
                                              <option value="1">Assign Leads on Round-Robin basis</option>
                                              <option value="2">Assign Leads by Country</option>
                                              <option value="3">Assign Leads by Indian State</option>
                                              <option value="4">Assign Leads by Indian City</option>
                                              <option value="5">Assign Leads by Keyword</option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="form-row">
                                        <div class="col-md-12" id="im_rule_wise_view">

                                        </div>
                                      </div>

                                  </div>
                              </fieldset>
                                
                                
                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                                   <thead>
                                      <tr>
                                          <th width="10%">Version</th>
                                         <th width="20%">Account Name</th>
                                         <th width="15%">Mobile</th>
                                         <th width="30%">Assign To</th>
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
                             <div class="form-group row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                      <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api2.png"></span> Tradeindia API Settings <a href="javaScript:void(0)" id="ti_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                    </h3>
                                </div>
                             </div>
                             
                             <input type="hidden" name="tradeindia_setting_id" id="tradeindia_setting_id" value="" />
                             <div class="out-holder" id="ti_add_div" style="display:none;">
                                <div class="col-md-12">
                                  <fieldset class="scheduler-border">
                                      <legend class="scheduler-border">Add New Tradeindia API:</legend>
                                      <div class="control-group">
                                          
                                          <div class="form-row mb-10">
                                             <div class="col-sm-6">
                                                <label for="tradeindia_account_name">Account Name<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="tradeindia_account_name" id="tradeindia_account_name" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             <div class="col-sm-6">
                                                <label for="tradeindia_userid">User ID<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="tradeindia_userid" id="tradeindia_userid" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             
                                          </div>

                                          <div class="form-row mb-10">
                                             
                                             <div class="col-sm-6">
                                                <label for="tradeindia_profile_id">Profile ID<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="tradeindia_profile_id" id="tradeindia_profile_id" placeholder="" value="" maxlength="10"  />
                                             </div>
                                             <div class="col-sm-6">
                                                <label for="tradeindia_key">Key<span class="text-danger">*</span>:</label>
                                                <input type="password" class="form-control" name="tradeindia_key" id="tradeindia_key" placeholder="" value=""  />
                                               
                                             </div>
                                          </div>

                                          <div class="form-row mb-10">                                             
                                             <div class="col-sm-12">                                                
                                                <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                                <div id="ti_assign_rule_div">
                                                   <select class="form-control" name="ti_assign_rule" id="ti_assign_rule">
                                                      <option value="">Select One</option>
                                                      <option value="1">Assign Leads on Round-Robin basis</option>
                                                      <option value="2">Assign Leads by Country</option>
                                                      <option value="3">Assign Leads by Indian State</option>
                                                      <option value="4">Assign Leads by Indian City</option>
                                                      <option value="5">Assign Leads by Keyword</option>
                                                   </select>
                                                </div>                                                
                                             </div>
                                          </div>

                                          <div class="form-row">
                                             <div class="col-md-12" id="ti_rule_wise_view"></div>
                                          </div>

                                          <?php /* ?>
                                          <div class="form-row mb-10">
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
                                          <div class="form-row">
                                             <div class="col-md-12">
                                                <a href="javascript:void(0)" class="btn btn-success fix-w" id="ti_submit_confirm--">Save</a>
                                                <a href="javascript:void(0)" class="btn btn-danger fix-w" id="ti_submit_close--">Close</a>
                                             </div>
                                          </div>
                                          <?php */ ?>
                                          
                                      </div>
                                  </fieldset>
                                  
                              </div>
                                

                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="">
                                   <thead>
                                      <tr>
                                         <th width="20%">Account Name</th>
                                         <th width="15%">User ID</th>
                                         <th width="15%">Profile ID</th>
                                         <th width="35%">Assign To</th>
                                         <th class="text-center" width="15%">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody id="ti_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                             
                          </div>
                          <div>&nbsp;</div>
                        </div>

                        <div id="aj_api_settings" class="tabcontent">
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                      <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/aajjo.jpg"></span> Aajjo API Settings <a href="javaScript:void(0)" id="aj_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                    </h3>
                                </div>
                             </div>
                             
                             <input type="hidden" name="aajjo_setting_id" id="aajjo_setting_id" value="" />
                             <div class="out-holder" id="aj_add_div" style="display:none;">
                                <div class="col-md-12">
                                  <fieldset class="scheduler-border">
                                      <legend class="scheduler-border">Add New Aajjo API:</legend>
                                      <div class="control-group">
                                          
                                          <div class="form-row mb-10">
                                             <div class="col-sm-6">
                                                <label for="aajjo_account_name">Account Name<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="aajjo_account_name" id="aajjo_account_name" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             <div class="col-sm-6">
                                                <label for="aajjo_username">Username<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="aajjo_username" id="aajjo_username" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             
                                          </div>

                                          <div class="form-row mb-10">                                             
                                             
                                             <div class="col-sm-12">
                                                <label for="aajjo_key">Key<span class="text-danger">*</span>:</label>
                                                <input type="password" class="form-control" name="aajjo_key" id="aajjo_key" placeholder="" value=""  />
                                               
                                             </div>
                                          </div>

                                          <div class="form-row mb-10">                                             
                                             <div class="col-sm-12">                                                
                                                <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                                <div id="ti_assign_rule_div">
                                                   <select class="form-control" name="aj_assign_rule" id="aj_assign_rule">
                                                      <option value="">Select One</option>
                                                      <option value="1">Assign Leads on Round-Robin basis</option>
                                                      <option value="2">Assign Leads by Country</option>
                                                      <option value="3">Assign Leads by Indian State</option>
                                                      <option value="4">Assign Leads by Indian City</option>
                                                      <option value="5">Assign Leads by Keyword</option>
                                                   </select>
                                                </div>                                                
                                             </div>
                                          </div>

                                          <div class="form-row">
                                             <div class="col-md-12" id="aj_rule_wise_view"></div>
                                          </div>                                          
                                      </div>
                                  </fieldset>                                  
                              </div>
                                

                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="">
                                   <thead>
                                      <tr>
                                         <th width="20%">Account Name</th>
                                         <th width="15%">Username</th>
                                         <th width="35%">Assign To</th>
                                         <th class="text-center" width="15%">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody id="aj_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                             
                          </div>
                          <div>&nbsp;</div>
                        </div>


                        <div id="jd_api_settings" class="tabcontent">
                          
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                      <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api3.png"></span> JustDial Settings <a href="javaScript:void(0)" id="jd_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                    </h3>
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
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Employee assign for JustDial:</legend>
                                    <div class="control-group">
                                       <?php /* ?>
                                       <div class="form-row mb-10">
                                         <div class="col-md-12">                                            
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
                                       <div class="form-row">
                                         <div class="col-md-12">
                                            <a href="javascript:void(0)" class="btn btn-success fix-w" id="jd_submit_confirm">Save</a>
                                            <a href="javascript:void(0)" class="btn btn-danger fix-w" id="jd_submit_close">Close</a>
                                         </div>
                                       </div>
                                       <?php */ ?>

                                       <div class="form-row mb-10">                                             
                                          <div class="col-sm-12">                                                
                                             <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                             <div id="jd_assign_rule_div">
                                                <select class="form-control" name="jd_assign_rule" id="jd_assign_rule">
                                                   <option value="">Select One</option>
                                                   <option value="1">Assign Leads on Round-Robin basis</option>
                                                   <option value="2">Assign Leads by Country</option>
                                                   <option value="3">Assign Leads by Indian State</option>
                                                   <option value="4">Assign Leads by Indian City</option>
                                                   <option value="5">Assign Leads by Keyword</option>
                                                </select>
                                             </div>                                                
                                          </div>
                                       </div>

                                       <div class="form-row">
                                          <div class="col-md-12" id="jd_rule_wise_view"></div>
                                       </div>
                                        
                                    </div>
                                </fieldset> 
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

                        <!-- Exporter India Settings - START -->
                        <div id="ei_api_settings" class="tabcontent">
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                      <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/ei.png"></span> Exporter India API Settings <a href="javaScript:void(0)" id="ei_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                    </h3>
                                </div>
                             </div>
                             
                             <input type="hidden" name="exporterindia_setting_id" id="exporterindia_setting_id" value="" />
                             <div class="out-holder" id="ei_add_div" style="display:none;">
                                <div class="col-md-12">
                                  <fieldset class="scheduler-border">
                                      <legend class="scheduler-border">Add/ Edit New Exporterindia API:</legend>
                                      <div class="control-group">
                                          
                                          <div class="form-row mb-10">
                                             <div class="col-sm-12">
                                                <label for="">Account Name<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="exporterindia_account_name" id="exporterindia_account_name" placeholder="" value="" maxlength="255"  />
                                             </div>                                                                                      
                                          </div>

                                          <div class="form-row mb-10">
                                             <div class="col-sm-6">
                                                <label for="">Email<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="exporterindia_userid" id="exporterindia_userid" placeholder="" value="" maxlength="255"  />
                                                <input type="hidden" name="exporterindia_profile_id" id="exporterindia_profile_id" value="testing"   />
                                             </div> 
                                             <div class="col-sm-6">
                                                <label for="">Key<span class="text-danger">*</span>:</label>
                                                <input type="password" class="form-control" name="exporterindia_key" id="exporterindia_key" placeholder="" value=""  />
                                               
                                             </div>
                                          </div>

                                          <div class="form-row mb-10">                                             
                                             <div class="col-sm-12">                                                
                                                <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                                <div id="ti_assign_rule_div">
                                                   <select class="form-control" name="ei_assign_rule" id="ei_assign_rule">
                                                      <option value="">Select One</option>
                                                      <option value="1">Assign Leads on Round-Robin basis</option>
                                                      <option value="2">Assign Leads by Country</option>
                                                      <option value="3">Assign Leads by Indian State</option>
                                                      <option value="4">Assign Leads by Indian City</option>
                                                      <option value="5">Assign Leads by Keyword</option>
                                                   </select>
                                                </div>                                                
                                             </div>
                                          </div>

                                          <div class="form-row">
                                             <div class="col-md-12" id="ei_rule_wise_view"></div>
                                          </div>                                          
                                      </div>
                                  </fieldset>                                  
                              </div> 
                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="">
                                   <thead>
                                      <tr>
                                         <th width="20%">Account Name</th>
                                         <th width="30%">Email</th>
                                         <th width="35%">Assign To</th>
                                         <th class="text-center" width="15%">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody id="ei_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>                             
                          </div>
                          <div>&nbsp;</div>
                        </div>
                        <!-- Exporter India Settings - END -->

                        <!-- website Settings - START -->
                        <div id="websitep_api_setting" class="tabcontent">
                           <div class="form-group">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>                                                       
                                 <div class="form-group row"> 
                                    <div class="col-md-12">
                                       <h3 class="text-info">
                                       <i class="fa fa-cogs fa-fw" aria-hidden="true"></i> Website API Settings <a href="javaScript:void(0)" id="web_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                       </h3>
                                    </div>
                                 </div>
                        
                                 <div>
                                    <h6>API URL: <span class="text-primary"><?php echo base_url(); ?>capture/website/<?php echo $this->session->userdata['admin_session_data']['id']; ?></span></h6>
                                 </div>
                                 <div>&nbsp;</div>
                                 <div><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/download_website_api_document" class="btn btn-info">Download Website API Document</a></div>                                                          
                              </div>
                              <div>&nbsp;</div>
                              <input type="hidden" name="website_setting_id" id="website_setting_id" value="" />
                              <div class="out-holder" id="web_add_div" style="display:none;">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Employee assign for Website Capture:</legend>
                                    <div class="control-group">                                      

                                       <div class="form-row mb-10">                                             
                                          <div class="col-sm-12">                                                
                                             <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                             <div id="web_assign_rule_div">
                                                <select class="form-control" name="web_assign_rule" id="web_assign_rule">
                                                   <option value="">Select One</option>
                                                   <option value="1">Assign Leads on Round-Robin basis</option>
                                                   <option value="2">Assign Leads by Country</option>
                                                   <option value="3">Assign Leads by Indian State</option>
                                                   <option value="4">Assign Leads by Indian City</option>
                                                   <option value="5">Assign Leads by Keyword</option>
                                                </select>
                                             </div>                                                
                                          </div>
                                       </div>

                                       <div class="form-row">
                                          <div class="col-md-12" id="web_rule_wise_view"></div>
                                       </div>
                                        
                                    </div>
                                </fieldset> 
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
                                   <tbody id="web_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                           </div>
                        </div>
                        <!-- website Settings - END -->
                        
                        <div id="sms_api_settings" class="tabcontent">
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                      <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/api-sms.png"></span> SMS API Settings <?php if(count($sms_forwarding_setting_rows)>0){ ?><a href="javaScript:void(0)" id="sms_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a><?php } ?>
                                    </h3>
                                </div>
                             </div>
                             <?php if(count($sms_forwarding_setting_rows)>0){ ?>
                             <input type="hidden" name="sms_setting_id" id="sms_setting_id" value="" />
                             <div class="out-holder" id="sms_add_div" style="display:none;">
                                <div class="col-md-12">
                                  <fieldset class="scheduler-border">
                                      <legend class="scheduler-border">Add New SMS API:</legend>
                                      <div class="control-group">
                                          
                                          <div class="form-row mb-10">
                                             <div class="col-sm-12">
                                                <label for="sms_account_name">Service Provider<span class="text-danger">*</span>:</label>
                                                <select name="sms_service_provider_id" id="sms_service_provider_id" class="form-control">
                                                <option value="">== Select ==</option>
                                                   <?php foreach($sms_service_provider_list AS $sp){ ?>
                                                      <option value="<?php echo $sp['id']; ?>"><?php echo $sp['name']; ?></option>
                                                   <?php } ?>
                                                </select>                                                
                                             </div>
                                          </div>

                                          <div class="form-row mb-10">
                                             <div class="col-sm-6">
                                                <label for="sms_account_name">Name<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="sms_account_name" id="sms_account_name" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             <div class="col-sm-6">
                                                <label for="tradeindia_userid">Sender<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="sms_sender" id="sms_sender" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             
                                          </div>

                                          <div class="form-row mb-10">
                                             
                                             <div class="col-sm-6">
                                                <label for="sms_apikey">API Key<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="sms_apikey" id="sms_apikey" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             <div class="col-sm-6">
                                                <label for="sms_entity_id">Entity ID<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="sms_entity_id" id="sms_entity_id" placeholder="" value="" maxlength="255"  />
                                               
                                             </div>
                                          </div>

                                          
                                          <div class="form-row">
                                             <div class="col-md-12">
                                                <a href="javascript:void(0)" class="btn btn-success fix-w" id="sms_submit_confirm">Save</a>
                                                <a href="javascript:void(0)" class="btn btn-danger fix-w" id="sms_submit_close">Close</a>
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
                                          <th width="20%">Service Provider</th>
                                          <th width="10%">Name</th>
                                          <th width="10%">Sender</th>
                                          <th width="40%">API Key</th>
                                          <th class="text-center" width="20%">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody id="sms_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                             <?php }else{ ?>
                                 <h3 class="text-danger">Not Active</h3>
                                 <b class="text-success">Note:</b><small> Please contact to LMSBABA Team to get SMS service. </small>
                              <?php } ?>
                          </div>
                          <div>&nbsp;</div>
                        </div>

                        <!-- WhatsApp -->
                        <div id="whatsapp_api_settings" class="tabcontent">
                          <div class="col-md-12">
                             <div>&nbsp;</div>
                             <div class="form-group row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                      <span class="setting-icon"><img src="<?php echo assets_url(); ?>images/social-whatsapp.png"></span> WhatsApp API Settings <?php if(count($sms_forwarding_setting_rows)>0){ ?><a href="javaScript:void(0)" id="whatsapp_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a><?php } ?>
                                    </h3>
                                </div>
                             </div>
                             <?php if(count($whatsapp_forwarding_setting_rows)>0){ ?>
                             <input type="hidden" name="whatsapp_setting_id" id="whatsapp_setting_id" value="" />
                             <div class="out-holder" id="whatsapp_add_div" style="display:none;">
                                <div class="col-md-12">
                                  <fieldset class="scheduler-border">
                                      <legend class="scheduler-border">Add New WhatsApp API:</legend>
                                      <div class="control-group">
                                          
                                          <div class="form-row mb-10">
                                             <div class="col-sm-12">
                                                <label for="whatsapp_account_name">Service Provider<span class="text-danger">*</span>:</label>
                                                <select name="whatsapp_service_provider_id" id="whatsapp_service_provider_id" class="form-control">
                                                <option value="">== Select ==</option>
                                                   <?php foreach($whatsapp_service_provider_list AS $sp){ ?>
                                                      <option value="<?php echo $sp['id']; ?>"><?php echo $sp['name']; ?></option>
                                                   <?php } ?>
                                                </select>                                                
                                             </div>
                                          </div>

                                          <div class="form-row mb-10">
                                             <div class="col-sm-6">
                                                <label for="whatsapp_account_name">Name<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="whatsapp_account_name" id="whatsapp_account_name" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             <div class="col-sm-6">
                                                <label for="whatsapp_sender">Sender Mobile<span class="text-danger">*</span>:</label>
                                                <input type="text" class="form-control" name="whatsapp_sender" id="whatsapp_sender" placeholder="" value="" maxlength="255"  />
                                             </div>
                                             
                                          </div>

                                          <div class="form-row mb-10">
                                             
                                             <div class="col-sm-12">
                                                <label for="whatsapp_apikey">API Key<span class="text-danger">*</span>:</label>
                                                <input type="password" class="form-control" name="whatsapp_apikey" id="whatsapp_apikey" placeholder="" value="" maxlength="255"  />
                                             </div>
                                          </div>

                                          
                                          <div class="form-row">
                                             <div class="col-md-12">
                                                <a href="javascript:void(0)" class="btn btn-success fix-w" id="whatsapp_submit_confirm">Save</a>
                                                <a href="javascript:void(0)" class="btn btn-danger fix-w" id="whatsapp_submit_close">Close</a>
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
                                          <th width="20%">Service Provider</th>
                                          <th width="10%">Name</th>
                                          <th width="10%">Sender Mobile</th>
                                          <!-- <th width="40%">API Key</th> -->
                                          <th class="text-center" width="20%">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody id="whatsapp_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                             <?php }else{ ?>
                                 <h3 class="text-danger">Not Active</h3>
                                 <b class="text-success">Note:</b><small> Please contact to LMSBABA Team to get WhatsApp service. </small>
                              <?php } ?>
                          </div>
                          <div>&nbsp;</div>
                        </div>
                        
                        <!-- WhatsApp -->


                        <div id="c2c_settings" class="tabcontent">
                              <?php         
                              $c2c_api_dial_url=$company['c2c_api_dial_url'];
                              $c2c_api_userid=$company['c2c_api_userid'];
                              $c2c_api_password=$company['c2c_api_password'];
                              $c2c_api_client_name=$company['c2c_api_client_name'];
                              if(count($c2c_service_provider_list)>0)
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
                                    <?php /* ?>
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <a href="javascript:void(0)" class="btn btn-success" id="c2c_credential_submit_confirm">Save</a>
                                       </div>
                                    </div>
                                    <?php */ ?>
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
                                    <div class="col-md-12">
                                       <fieldset class="scheduler-border">
                                       <legend class="scheduler-border">Add New C2C API:</legend>
                                       <div class="control-group">                                             
                                             <div class="form-row mb-10">
                                                <div class="col-sm-12">
                                                   <label for="c2c_service_provider_id">Service Provider<span class="text-danger">*</span>:</label>
                                                   <select name="c2c_service_provider_id" id="c2c_service_provider_id" class="form-control">
                                                      <?php foreach($c2c_service_provider_list AS $sp){ ?>
                                                         <option value="<?php echo $sp['id']; ?>"><?php echo $sp['name']; ?></option>
                                                      <?php } ?>
                                                   </select>                                                
                                                </div>
                                             </div>

                                             <div class="form-row mb-10">
                                                <div class="col-sm-12">
                                                   <label for="user_c2c_assign_to">Assign for C2C :<span class="text-danger">*</span>:</label>
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
                                             <div class="form-row mb-10">
                                                <div class="col-sm-4">
                                                   <label for="c2c_caller_name">Name<span class="text-danger">*</span>:</label>
                                                   <input type="text" class="form-control " name="c2c_caller_name" id="c2c_caller_name" placeholder="" value="" maxlength="255" readonly="true"  />
                                                </div>
                                                <div class="col-sm-4">
                                                   <label for="c2c_mobile">Personal Mobile<span class="text-danger">*</span>:</label>
                                                   <input type="text" class="form-control only_natural_number" name="c2c_mobile" id="c2c_mobile" placeholder="" value="" maxlength="10" readonly="true" />
                                                </div>
                                                <div class="col-sm-4">
                                                   <label for="c2c_office_no">Office No. (C2C No.)<span class="text-danger">*</span>:</label>
                                                   <input type="text" class="form-control only_natural_number" name="c2c_office_no" id="c2c_office_no" placeholder="" value="" maxlength="10"  />
                                                </div>
                                             </div>                                             

                                             
                                             <div class="form-row">
                                                <div class="col-md-12">
                                                   <a href="javascript:void(0)" class="btn btn-success fix-w" id="c2c_submit_confirm">Save</a>
                                                   <a href="javascript:void(0)" class="btn btn-danger fix-w" id="c2c_submit_close">Close</a>
                                                </div>
                                             </div>
                                             
                                       </div>
                                       </fieldset>                                  
                                    </div>
                                 </div>
                                 
                                 <!-- <div class="out-holder" id="c2c_add_div" style="display:none;">
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
                                    
                                    <div class="form-group row">    
                                       <div class="col-sm-4">
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
                                 </div> -->

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
                                 <?php /* ?>
                                 <div class="form-group" id="submit_div">
                                    <div class="col-sm-12 mt-">
                                       <button type="submit" class="btn btn-primary btn-round-shadow mt-20px mb-15">Save</button>
                                    </div>
                                 </div>
                                 <?php */ ?>
                              </div>
                              <?php } ?>        
                           </div>


                           <!-- Auto Regret Settings - START -->
                           <div id="google_map_api_key_setting" class="tabcontent">
                              <div class="col-md-12">
                                    <div>&nbsp;</div>                                                       
                                    <div class="form-group"> 
                                       
                                       <div class="col-md-12">
                                          <label for="" class="col-form-label">Google Map API Key:</label>
                                          <input type="text" class="form-control" name="google_map_api_key" id="google_map_api_key" value="<?php echo $company['google_map_api_key']; ?>"/>                                          
                                       </div>
                                       <div>&nbsp;</div>
                                       <div class="col-md-12">
                                          <a href="javascript:void(0)" class="btn btn-success pull-left" id="google_map_api_key_submit_btn">Save</a>
                                       </div>                              
                                    </div>                              
                                 </div>       
                           </div>
                           <!-- Auto Regret Settings - END -->


                        


                        <!-- Facebook connect Settings - START -->                           
                        <div id="facebook_api_key_setting" class="tabcontent">
                           <div class="col-md-12">
                                 <div>&nbsp;</div>                                                       
                                 <div class="form-group"> 
                                    
                                    <div class="col-md-12">
                                       <label for="" class="col-form-label">Facebook Lead API Settings:</label>                                          
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="col-md-12" id="facebook_api_connect_btn"></div>
                                    <div class="col-md-12" id="facebook_form_list_div"></div>
                                                                  
                                 </div>                              
                              </div>       
                        </div>
                        <!-- Facebook connect Settings - END -->



                        <!-- FB Settings - START -->
                        <div id="fb_lead_assignment_setting" class="tabcontent">
                           <div class="form-group">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>                                                       
                                 <div class="form-group row"> 
                                    <div class="col-md-12">
                                       <h3 class="text-info">
                                       <i class="fa fa-cogs fa-fw" aria-hidden="true"></i> Facebook Assignment Settings <a href="javaScript:void(0)" id="fb_add_div_toggle" class="pull-right small-txt"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                       </h3>
                                    </div>
                                 </div>                                                    
                              </div>
                              <div>&nbsp;</div>
                              <input type="hidden" name="fb_setting_id" id="fb_setting_id" value="" />
                              <div class="out-holder" id="fb_add_div" style="display:none;">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Employee assign for FB Lead Capture:</legend>
                                    <div class="control-group">                                      

                                       <div class="form-row mb-10">                                             
                                          <div class="col-sm-12">                                                
                                             <label for="" class="col-form-label">Select Auto Lead Assignment Rule:</label>
                                             <div id="fb_assign_rule_div">
                                                <select class="form-control" name="fb_assign_rule" id="fb_assign_rule">
                                                   <option value="">Select One</option>
                                                   <option value="1">Assign Leads on Round-Robin basis</option>
                                                   <?php /* ?>
                                                   <option value="2">Assign Leads by Country</option>
                                                   <option value="3">Assign Leads by Indian State</option>
                                                   <option value="4">Assign Leads by Indian City</option>
                                                   <option value="5">Assign Leads by Keyword</option>
                                                   <?php */ ?>
                                                </select>
                                             </div>                                                
                                          </div>
                                       </div>

                                       <div class="form-row">
                                          <div class="col-md-12" id="fb_rule_wise_view"></div>
                                       </div>
                                        
                                    </div>
                                </fieldset> 
                             </div>
                             <div class="table-responsive">
                                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="">
                                   <thead>
                                      <tr>
                                         <th width="20%">Account Name</th>                                        
                                         <th width="40%">Assign To</th>
                                         <th class="text-center" width="10%">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody id="fb_tcontent" class="t-contant-img"></tbody>
                                </table>
                             </div>
                           </div>
                        </div>
                        <!-- FB Settings - END -->

                           
                           

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
<!-- <script src="<?php echo base_url();?>assets/js/bootstrap-tagsinput.js"></script>  -->
<script type="text/javascript">
   $(document).ready(function () {
    ////////
    $('.modal').on("hidden.bs.modal", function (e) { 
      
      setTimeout(function(){ 
        console.log('1 closeeeeeeeeee')
        if ($('.modal:visible').length) { 
          console.log('2 closeeeeeeeeee')
          $('body').addClass('modal-open');
        }
      }, 500);
      
    });
    //////
      // popovers initialization - on hover
      $('[data-toggle="popover-hover"]').popover({
         html: true,
         trigger: 'hover',
         placement: 'right',
         content: function () { return '<img height="500" src="' + $(this).data('img') + '" />'; }
      });

       

      
   });
   
</script>
<script>
   // Get the element with id="defaultOpen" and click on it
   document.getElementById("defaultOpen").click();   
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

