<!DOCTYPE html>
<html lang="en">
   <head>
      <?php $this->load->view('admin/includes/head'); ?>     
   </head>
   <body>
    <?php //print_r($client_info); ?>
      <!--================================-->
      <!-- User Singin Start -->
      <!--================================-->			
      <div class="app no-padding no-footer layout-static">
          <div class="session-panel">
            <div class="container">
          <div class="row">         
             <div class="col-md-12">
                 <div class="card card-block form-layout shadow-ar mt-45">
                  
                    <?php 
                    echo form_open(base_url().'account/update_password', array('method'=>'post','name'=>'forget_form', 'id'=>'forget_form','class'=>'pd-20', 'autocomplete'=>"off"));
                    //echo form_hidden('id', $id);


                    ?> 
                    <input type="hidden" name="key" id="key" value="<?php echo $key_str; ?>">
                    <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id; ?>">
                    <input type="hidden" name="user_key" value="<?php echo $user_data['forget_key']; ?>">

                    <div class="text-xs-center m-b-3">
                      <img src="<?php echo assets_url(); ?>images/logo.png" alt="LMSBABA" width="70px">
                      <h5>Welcome! <?php echo $client_info->name; ?></h5>
                      <p class="text-muted">Reset your new password.</p>
                    </div>
                    <?php if($user_data['forget_key']!=''){ ?>
                    <fieldset class="form-group">
                      <label for="password">
                        New Password
                      </label>
                      <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password"/>
                    </fieldset>


                    <fieldset class="form-group">
                      <label for="username">
                        Confirm Password
                      </label>
                      <input type="password" class="form-control form-control-lg" id="confirm_password" placeholder="Confirm Password" name="confirm_password"/>
                    </fieldset>

                    <button class="btn btn-primary btn-block btn-lg" type="submit" id="password_reset_submit">
                      Reset Password </button>
                      <div>&nbsp;</div>
                    <?php } ?>
                    <h4 class="text-center">
                      <a href="<?php echo base_url(); ?>" class="bottom-link" id="">Go to Login</a>
                    </h4>
                  
                  <?php echo form_close(); ?>
                  <div>&nbsp;</div>
                  
                  <div class="row">
                    <div class="col-md-12">
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
                      <strong>Well done!</strong> <?php echo $this->session->flashdata('success_msg'); ?>
                      </div>
                      <?php } ?>
                    </div>                
                  </div>
                  <div class="text-center">
                    <p class="login-footer">Powered by</p>
                     <img src="<?php echo assets_url(); ?>images/logo.png" alt="" class="m-b-1" style="width:84px" /> 
                      
                  </div>
                </div>
             </div>
          </div>         
        </div>
      </div>
    </div>
      <?php $this->load->view('admin/includes/app.php'); ?>
   </body>
</html>
<script type="text/javascript">
$(document).ready(function(){
  $("body").on("click","#password_reset_submit",function(e){
    
    var password=$("#password").val();
    var confirm_password=$("#confirm_password").val();

    if(password=='')
    {
      swal('Oops!', 'Please enter new password.','error');
      return false;
    }

    if(confirm_password=='')
    {
      swal('Oops!', 'Please enter confirm password.','error');
      return false;
    }

    if(password!=confirm_password)
    {
      swal('Oops!', 'New password and confirm password are not matching.','error');
      return false;
    }    

    return true;
  });

});
</script>