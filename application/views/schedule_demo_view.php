
<!doctype html>
<html lang="eng">
<head>
    <!-- TITLE -->
    <title>LMSBABA Demo Request Form, Ask for LMSbaba Demo</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/meanmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/owl.carousel.min.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/flipster/jquery.flipster.css" media="all">
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/flipster/demo.css" media="all">

    <link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/style.css">
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css_home_v2/responsive.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="css_home_v2images/favicon_8.ico">
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LVZSQQ7NJM"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LVZSQQ7NJM');
</script>

</head>

<body class="home_v2">

<div class="rimu-nav-style rimu-nav-style-five fixed-header-nav mobile-abso">
    <div class="navbar-area">
        <!-- Menu For Mobile Device -->
        <div class="mobile-nav">
            <a href="https://lmsbaba.com/" class="logo">
                <img src="<?php echo assets_url(); ?>images_home_v2/logo.png" alt="LMS Logo" width="100">
            </a>
        </div>
        <!-- Menu For Desktop Device -->
        <div class="main-nav">
            <nav class="navbar navbar-expand-md navbar-light">
                <div class="container">
                    <a class="navbar-brand pt-10" href="https://lmsbaba.com/">
                        <img src="<?php echo assets_url(); ?>images_home_v2/logo.png" alt="LMS Logo" width="170">
                    </a>
                    <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent" style="display: block;">
                        <?php $this->load->view('top-menu'); ?>                        
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>

<section class="section-image-inner light section-bottom-layer pt-140 pb-100 mpt-56">
   <div class="container">
      <div class="row align-items-center">
         
         <div class="col-lg-12">
            <div class="text-left mb-4">
                <h2>Ask for a Demo of LMSBaba CRM</h2>
                
            </div>
         </div>
         <div class="col-lg-6">
            <div class="show-sucess-block" style="display:none;">
                <div class="sucess-img">
                    <img src="<?php echo assets_url(); ?>images_home_v2/thank-you-icon.png">
                </div>
                <div class="sucess-txt">
                    <h2>Thank You.</h2>
                    <p>Your submission has been sent.</p>
                    <div class="sucess-sction">
                        <a href="#book_demo_form" class="btn btn-primary close-sucess-block"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                    </div>
                </div>
            </div>

            <form id="book_demo_form">
                 <div class="form-group row">
                    <div class="col-12">
                       <label>Name<span class="text-danger">*</span></label>
                       <input type="text" class="form-control" placeholder="Enter here.." id="book_demo_name" name="book_demo_name">
                       <div class="text-danger" id="book_demo_name_error"></div>
                    </div>
                 </div>

                 <div class="form-group row">
                    <div class="col-6">
                       <label>Email<span class="text-danger">*</span></label>
                       <input type="text" class="form-control" id="book_demo_email" name="book_demo_email" placeholder="Enter here..">
                        <div class="text-danger" id="book_demo_email_error"></div>
                    </div>
                    <div class="col-6">
                       <label>Mobile<span class="text-danger">*</span></label>
                       <input type="text" class="form-control only_natural_number" id="book_demo_mobile" name="book_demo_mobile" placeholder="Enter here.." maxlength="10">
                             <div class="text-danger" id="book_demo_mobile_error"></div>
                    </div>
                 </div>

                 
                 <div class="form-group row">
                    <div class="col-12">
                       <label>Company Name<span class="text-danger">*</span></label>
                       <input type="text" class="form-control" placeholder="Enter here.." id="book_demo_company_name" name="book_demo_company_name">
                        <div class="text-danger" id="book_demo_company_name_error"></div>
                    </div>
                 </div>

                 <div class="form-group row">
                    <div class="col-6">
                       <label>Preferred Date</label>
                       <input type="date" class="form-control" placeholder="Enter here.." id="book_demo_date" name="book_demo_date" onfocus="this.showPicker()">
                        <div class="text-danger" id="book_demo_date_error"></div>
                    </div>
                    <div class="col-6">
                       <label>Preferred Time</label>
                       <input type="time" class="form-control" placeholder="Enter here.." id="book_demo_time" name="book_demo_time" onfocus="this.showPicker()">
                        <div class="text-danger" id="book_demo_time_error"></div>
                    </div>
                 </div>
                 
                 <input type="hidden" name="book_demo_comment" id="book_demo_comment" value="">
                 <div class="text-center">
                    <button type="button" class="btn btn-primary" id="book_demo_send_confirm">Send</button>  
                </div>
            </form>
         </div>
         <div class="col-lg-6">
             <img src="<?php echo assets_url(); ?>images_home_v2/book-demo.png" class="img-fluid">
         </div>
      </div>
   </div>
</section>


<footer class="footer-bg ptb-40">
    <?php $this->load->view('footer'); ?>  
</footer> 
<script src="<?php echo assets_url(); ?>js_home_v2/jquery-3.2.1.slim.min.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/popper.min.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/bootstrap.min.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/jquery.meanmenu.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/wow.min.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/jquery.matchHeight.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/jquery.nice-select.min.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/jquery.appear.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/custom.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/jquery.datetimepicker.full.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/TweenMax.min.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/common_functions.js"></script>
<script src="<?php echo assets_url(); ?>js_home_v2/app.js"></script>

<script type="text/javascript" src="<?php echo assets_url(); ?>js_home_v2/owl.carousel.min.js"></script>
<script>
	$(document).ready(function(){
		console.log('working.....');
        //////////////////////////////////////////////////////////////////////////\
        //book_demo_form
        showSucess = function(){
            console.log('submit sucess....');
            $('#book_demo_form').hide();
            $('.show-sucess-block').show();
        }
	});

</script>
</body>
</html>


