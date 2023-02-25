<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>LMS for Login | Powered by LMSBABA.COM</title>
    <?php //$this->load->view('admin/includes/head'); ?> 
    <link rel="icon" href="<?=assets_url();?>images/favicon_8.ico" type="image/ico" sizes="18x18">
    <link rel="stylesheet" href="<?php echo assets_url(); ?>login/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo assets_url(); ?>login/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.css"/>
    <link rel="stylesheet" href="<?php echo assets_url(); ?>login/css/style.css">
    
</head>
<body>
    <input type="hidden" id="base_url" value="<?php echo base_url().$lms_url;?>">
    <input type="hidden" id="assets_base_url" value="<?php echo assets_url();?>">
    <input type="hidden" id="base_url_root" value="<?php echo base_url();?>">
    <input type="hidden" id="is_mobile" value="<?php echo $is_mobile; ?>">
    <section class="login-page1">
        <div class="container-fluid p-0">
            <div class="row no-gutters ">
                <div class="col-md-7">
                    <div class="left-section">
                        <div class="logo-img-text">
                            <div class="logo-img">
                                <img src="<?php echo assets_url(); ?>login/images/left-logo.png" width="">
                            </div>
                            <div class="logo-text">
                                <h3>LMSbaba.com - SAAS for SMBs</h3>
                                <p>Digital Transformation Solution for Small and Midsize Businesses with Leveraging
                                    Automation and AI.</p>
                            </div>
                        </div>
                        <div class="login-img ">
                            <img src="<?php echo assets_url(); ?>login/images/login1.png">
                            <div class="icon-bottom"></div>
                        </div>
                        <div class="left-footer">
                            <div class="footer-img">
                                <div class="footer-div">
                                    <a href="https://srishtiventures.in/" target="_bank"><img src="<?php echo assets_url(); ?>login/images/srishtiventures_logo.png" width="120"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="login-form">
                        <div class="login-form-ar">

                            <h2 class="text-left">Welcome, <br>
                                <span><?php echo $client_info->name; ?></span>
                            </h2>                           

                            <div class="form-group">
                            <?php if($this->session->flashdata('error_msg')){ ?>
                            <div class="alert alert-danger alert-dismissible custom-alt" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                                <strong>Oops!</strong> <?php echo $this->session->flashdata('error_msg'); ?>
                            </div>
                            <?php } ?>

                            <?php if($this->session->flashdata('success_msg')){ ?>
                            <div class="alert alert-icon alert-success alert-dismissible custom-alt" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                            <i class="mdi mdi-check-all"></i>
                            <strong></strong> <?php echo $this->session->flashdata('success_msg'); ?>
                            </div>
                            <?php } ?>
                            </div>
                            <?php 
                            echo form_open(admin_url().'login/'.$id, array('method'=>'post','name'=>'form1', 'id'=>'form1','class'=>'pd-20', 'autocomplete'=>"off","onsubmit"=>"return frm_validate()"));
                            //echo form_hidden('id', $id);
                            ?> 
                                <input type="hidden" name="id" id="client_id" value="<?php echo $id; ?>">
                                <div class="form-group">

                                    <?php
                                    $data = array(
                                            'type'  => 'text',
                                            'name'  => 'login_id',
                                            'id'    => 'login_id',
                                            'value' => $cookie_username,
                                            'class' => 'form-control form-control-sm',
                                            'placeholder'=>'User ID/ Username',
                                            'autocomplete'=>"off"
                                    );
                                    echo form_input($data);
                                    ?>
                                    <span class="form-icon"><i class="fas fa-user"></i></span>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $data = array(
                                            'type'  => 'password',
                                            'name'  => 'login_password',
                                            'id'    => 'login_password',
                                            'value' => $cookie_password,
                                            'class' => 'form-control form-control-sm',
                                            'placeholder'=>'password',
                                            'autocomplete'=>"off"
                                    );
                                    echo form_input($data);
                                    ?>                                    
                                    <span class="form-icon"><i class="fas fa-lock-alt"></i></span>
                                    <br>
                                    <label class="check-box-sec">
                                        <input type="checkbox" value="Y" class="" name="chk_terms_of_services" id="chk_terms_of_services" checked>
                                        <span class="checkmark">I agree to Lmsbaba's <a href="https://lmsbaba.com/terms_of_services" target="_blank"> Terms of Services<a/> and <a href="https://lmsbaba.com/privacy_policy" target="_blank">Privacy Policy.</a></span>
                                    </label>                                    
                                    <!-- <a href="#" class="mt-3 mb-3 fpass">Forgot password?</a> -->
                                </div>
                                <div class="form-group">                                    
                                    <?php
                                    $data = array(
                                        'name'          => 'button',
                                        'id'            => 'button',
                                        'value'         => 'true',
                                        'type'          => 'submit',
                                        'content'       => 'Sign in',
                                        'class'=>'btn btn-blue'
                                    );

                                    echo form_button($data);
                                    ?>
                                </div>
                                <input type="hidden" name="remember" id="remember" value="">
                            <?php echo form_close(); ?>
                            <a href="javascript:void(0);" class="forgot-pass-link mt-3--- mb-3--- fpass"  id="forget">Forgot Password?</a>
                        </div>

                    </div>
                    <div class="right-footer">
                        <div class="container-fluid-full">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="footer-li">
                                        <ul class="social">
                                            <li><a href="https://www.facebook.com/lmsbaba/" target="_blank"><i
                                                        class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="https://www.youtube.com/channel/UCUkMXZZTH4FMFxsSyszJf7Q/videos"
                                                    target="_blank"> <i class="fab fa-youtube"></i></a></li>
                                            <li><a href="https://www.instagram.com/lmsbaba_crm/" target="_blank"><i
                                                        class="fab fa-instagram" target="_blank"></i></a></li>
                                            <li><a href="https://www.linkedin.com/company/76492679/admin/"
                                                    target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                                            <li><a href="https://api.whatsapp.com/send/?phone=919711004302&text=Hi%20Sumit..&app_absent=0"
                                                    target="_blank"><i class="fab fa-whatsapp"></i></a></li>
                                            <li><a href="https://twitter.com/shashinarain" target="_blank"><i class="fab fa-twitter"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="footer-li text-right">
                                        <ul>
                                            <li><a href="https://g.page/r/CcZHHoO9W_t7EAI/review" target="_blank"><img
                                                        src="<?php echo assets_url(); ?>login/images/rating.png" class="rate-img"></a></li>
                                            <li><a href="https://play.google.com/store/apps/details?id=com.lmsbaba.app" target="_blank"><img
                                                        src="<?php echo assets_url(); ?>login/images/app-icon.png" width="115" class="rate-img----"></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?php echo assets_url(); ?>login/js/jquery-3.5.1.min.js"></script>
    <script src="<?php echo assets_url(); ?>login/js/bootstrap.min.js"></script>
    <?php  $this->load->view('admin/includes/app.php'); ?>
</body>
</html>
<script type="text/javascript">
(function($) {
  'use strict';
$('#forget').on('click', function() {
    swal({
      title: 'Request Employee ID!',
      email: 'Enter Employee ID',
      type: 'input',
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      animation: 'slide-from-top',
      inputPlaceholder: 'Enter Employee ID'
    }, function(inputValue) {
      if (inputValue === false) {
        return false;
      }
      if (inputValue === '' || !validateOnlyNumber(inputValue)) {
        swal.showInputError('You need to write Employee ID!');
        return false;
      }
      else
      {       
          var base_url=$("#base_url").val();
          var client_id=$("#client_id").val();
          var data='emp_id='+inputValue+'&client_id='+client_id; 
          
          $.ajax({
              url: base_url + "account/forget_password_ajax",
              data: data,
              cache: false,
              method: 'POST',
              dataType: "json", 
              beforeSend: function( xhr ) {
                  
              },
              complete: function (){
                 
              },           
              success: function(data){
                  //result = $.parseJSON(data);
                  if(data=='1')
                  {
                    swal('Nice!', 'Reset-Password link sent to the relevant email address.');
                  }
                  else if(data=='2')
                  {
                    swal.showInputError('User does not exists');
                  }  
                  else if(data=='3')
                  {
                    swal.showInputError('Mail not sent. Please try again later.');
                  }
                                 
              }
          });
    }
      
    });
  });
  })(jQuery);


function frm_validate()
{
    if ($("#login_id").val()=='') {
        swal('Oops!', 'User ID is required','error');
        return false;
    }
    if (!$("#login_password").val()) {
        swal('Oops!', 'Password is required','error');
        return false;
    }

    if (!$("#chk_terms_of_services").is(":checked")) {
        swal('Oops!', 'To continue, you need to agree with Lmababa\'s Terms of Services and Privacy Policy','error');
        return false;
    }
}
</script>