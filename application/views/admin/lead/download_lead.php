<?php $this->load->view('include/header');?>


	      <!-- content panel -->
      <div class="main-panel">
        <!-- top header -->
        <nav class="header navbar">
          <div class="header-inner">
            <div class="navbar-item navbar-spacer-right brand hidden-lg-up">
              <!-- toggle offscreen menu -->
              <a href="javascript:;" data-toggle="sidebar" class="toggle-offscreen">
                <i class="material-icons">menu</i>
              </a>
              <!-- /toggle offscreen menu -->
              <!-- logo -->
              <a class="brand-logo hidden-xs-down">
                <img src="<?=base_url();?>images/logo_white.png" alt="logo"/>
              </a>
              <!-- /logo -->
            </div>
            <a class="navbar-item navbar-spacer-right navbar-heading hidden-md-down" href="#">
              <span>LMS-Download Leads From E-mail</span>
            </a>
            <div class="navbar-search navbar-item">
              <form class="search-form">
                <i class="material-icons">search</i>
                <input class="form-control" type="text" placeholder="Search" />
              </form>
            </div>
            
          </div>
        </nav>
        <!-- /top header -->
        <!-- main area -->
        <!-- main area -->
        <div class="main-content">
          <div class="content-view">
            <form role="form" class="form-validation" id="form" action="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_lead_list" method="post">
              <div class="form-group m-b">
                <label>
                  Business E-mail Id
                </label>
                <input type="text" class="form-control" name="email" placeholder="E-mail" value="<?php echo $this->session->userdata['admin_session_data']['email']?>" required />
              </div>
              <div class="form-group m-b">
                <label>
                  Password
                </label>
                <input type="password" class="form-control" name="password" placeholder="Password" required/>
              </div>
              
              <div class="form-group">
                <button class="btn btn-primary m-r">
                  Submit
                </button>
                <button class="btn btn-default">
                  Reset
                </button>
              </div>
            </form>
            
            <!--<div>
            	<i class="material-icons">cached</i> sync with chanchal@maxbridgesolution.com
            </div>-->
          </div>
          <!-- bottom footer -->
          <div class="content-footer">
            <nav class="footer-right">
              <ul class="nav">
                <li>
                  <a href="javascript:;">Feedback</a>
                </li>
              </ul>
            </nav>
            <nav class="footer-left">
              <ul class="nav">
                <li>
                  <a href="javascript:;">
                    <span>Copyright</span>
                    &copy; 2016 Your App
                  </a>
                </li>
                <li class="hidden-md-down">
                  <a href="javascript:;">Privacy</a>
                </li>
                <li class="hidden-md-down">
                  <a href="javascript:;">Terms</a>
                </li>
                <li class="hidden-md-down">
                  <a href="javascript:;">help</a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- /bottom footer -->
        </div>
        <!-- /main area -->
         
         
          
         
    

    <!-- build:js({.tmp,app}) scripts/app.min.js -->
    <script src="<?=base_url();?>vendor/jquery/dist/jquery.js"></script>
    <script src="<?=base_url();?>vendor/pace/pace.js"></script>
    <script src="<?=base_url();?>vendor/tether/dist/js/tether.js"></script>
    <script src="<?=base_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script>
    <script src="<?=base_url();?>vendor/fastclick/lib/fastclick.js"></script>
    <script src="<?=base_url();?>scripts/constants.js"></script>
    <script src="<?=base_url();?>scripts/main.js"></script>
    <!-- endbuild -->
    
    
    <script type="text/javascript">
$(document).ready(function(){
   
   
    
    $('#form').validate({ // initialize the plugin
    
        rules: {
                     
            email: {
                required: true,
                email: true
            },
            password: {
                required: true               
            },
           
            
        },
        // Specify validation error messages
    messages: {      
      email: "Please enter a valid email address",
      password: "Please enter password"
    },
     
    });
});
</script>
   
   
       <!-- page scripts -->
    
    <!-- end page scripts -->

    <!-- initialize page scripts -->

    <!-- end initialize page scripts -->
    
    <!-- initialize page scripts -->
    <script src="<?=base_url();?>scripts/forms/upload.js"></script>
    <!-- end initialize page scripts -->
    <!-- initialize page scripts -->
    
 
    <!-- page scripts -->
    <script src="<?php echo base_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <!-- end page scripts -->
    
    <script>
  $( function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker2" ).datepicker();
  } );
  </script>
     <!-- initialize page scripts -->
    <script type="text/javascript">
      $('.form-validation').validate();
    </script>
    <!-- end initialize page scripts -->
   
    <!-- end initialize page scripts -->
          <!-- /bottom footer -->
        </div>
        <!-- /main area -->
      </div>
      <!-- /content panel -->

     

     

   

   


   

  </body>
</html>
