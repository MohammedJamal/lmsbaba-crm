<!DOCTYPE html>
<html lang="en">
   <head>
      <?php $this->load->view('admin/includes/head'); ?>  
      <style>
        #blink {
            font-size: 16px;
            font-weight: bold;
            color: #d26d54;
            transition: 0.5s;
        }
    </style>
    <script type="text/javascript">
        setInterval(function() {
            if($("#blink").css("opacity")==0){
              $("#blink").css("opacity", "1");
            }
            else{
              $("#blink").css("opacity", "0");
            }            
        }, 1000);
    </script>
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
                    echo form_open(adminportal_url().'login/'.$id, array('method'=>'post','name'=>'form1', 'id'=>'form1','class'=>'pd-20', 'autocomplete'=>"off"));
                    //echo form_hidden('id', $id);
                    ?> 
                    <div class="text-xs-center m-b-3">
                      <h5>Welcome! Adminmaster</h5>
                      <p class="text-muted"></p>
                    </div>                    
                    <?php
                    if(isset($flag))
                    {
                    ?>
                      <div class="alert alert-danger">Please check Username/Password</div>
                    <?php
                    }
                    ?>
                    <fieldset class="form-group">
                      <label for="username">Email</label>
                      <?php
                      $data = array(
                              'type'  => 'text',
                              'name'  => 'adminportal_username',
                              'id'    => 'adminportal_username',
                              'value' => '',
                              'class' => 'form-control form-control-sm',
                              'placeholder'=>'Email',
                              'autocomplete'=>"off"
                      );
                      echo form_input($data);
                      ?>
                    </fieldset>
                    <fieldset class="form-group">
                      <label for="password">Password</label>
                      <?php
                      $data = array(
                              'type'  => 'password',
                              'name'  => 'adminportal_password',
                              'id'    => 'adminportal_password',
                              'value' => '',
                              'class' => 'form-control form-control-sm',
                              'placeholder'=>'password',
                              'autocomplete'=>"off"
                      );
                      echo form_input($data);
                      ?>
                    </fieldset>
                    <input type="hidden" name="remember" id="remember" value="">
                    

                    

                    <?php
                      $data = array(
                        'name'          => 'button',
                        'id'            => 'button',
                        'value'         => 'true',
                        'type'          => 'submit',
                        'content'       => 'Login',
                        'class'=>'btn btn-primary btn-block btn-lg login-btn'
                      );

                      echo form_button($data);
                      ?>
                  
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
   </body>
</html>