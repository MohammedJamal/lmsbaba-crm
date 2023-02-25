
<!doctype html>
<html lang="eng">
<head>
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
    <link rel="icon" type="image/png" href="<?php echo assets_url(); ?>images/favicon_8.ico">
    <!-- TITLE -->
    <title>LMSBABA.COM: Auto Lead Syncing</title>

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
                <h2>Auto Lead Syncing</h2>
                
            </div>
         </div>
         <div class="col-lg-12">
            <div class="text-black">
                <span class="problem-txt"><strong>Problem Statement:</strong> Companies receiving buyer’s leads from various sources but not able to aggregate leads at one place and this cause lead leakage.</span><br><br>
                
                Lead management Solutions requires this basic feature to automatically sync/capture leads from your lead generation platforms. LMSBaba™ is ahead of it and provides solution to fetch or sync leads from almost all your lead generation platforms be it Indiamart, TradeIndia, JustDial or your own website.<br><br>

                <strong>ZERO LEAD LEAKAGE!</strong><br><br>

                All Leads from various sources directly sync on your Lead Board and not only syncing but automatically assigned to your Sales Team with various set rules. Simultaneously LMSBaba™ sends an acknowledgement mail to your buyers with the details of your company and the sales person to whom the leads has been assigned. This gives a feel good to your buyers and a good first impression of your company established in your buyers mind which ultimately helps him to take buying decision in favour of your company. <br><br>

                How you integration of Indiamart CRM API with LMSBaba™.com?<br>

                <ul class="default-ul">
                    <li>IMPORTANT: You must be paid customer of Indiamart.</li>
                    <li>Go to seller.indiamart.com</li>
                    <li>Login with your Indiamart registered Mobile number & OTP</li>
                    <li>Go to Settings from left navigation </li>
                    <li>Go to Accounts Settings</li>
                    <li>Unser “Integrate Indiamart Lead Manager with third party CRM” section, click on “Generate Key”</li>
                    <li>Fill answers of desired questions asked in popup. </li>
                    <li>Fill OTP then it will generate a CRM Key for your account</li>
                    <li>Go to your LMSBaba™.com & Login to your account</li>
                    <li>Go to settings & click on Indiamart API Settings</li>
                    <li>Fill required details and select the users in which you want to distribute leads</li>
                    <li>Check your Lead Board</li>
                </ul><br>

                <strong>NOTE: You can sync leads from your multiple Indiamart account on LMSBaba™ Lead Board.</strong><br><br>

                <strong>How to integration of TradeIndia CRM API with LMSBaba™.com?</strong><br>

                <ul class="default-ul">
                    <li>IMPORTANT: You must be a paid customer of TradeIndia.</li>
                    <li>Login to your seller panel of TradeIndia Account > "Go to My Profile"</li>
                    <li>Click on the "My Inquiry API" option under the "Inquiries and Contacts" section.</li>
                    <li>You will see the API details, copy the details.<br>
                        (a) Your userid is.<br>
                        (b) Your profile_id is.<br>
                        (c) Your key is.</li>
                    <li>Go to your LMSBaba™.com & Login to your account</li>
                    <li>Go to settings & click on TradeIndia API Settings</li>
                    <li>Fill required details and select the users in which you want to distribute leads
                    Check your Lead Board</li>
                </ul>

            </div>
         </div>
         <!-- <div class="col-lg-6">
             <img src="<?php //echo assets_url(); ?>images_home_v2/aaa-1.jpg" class="img-fluid">
         </div> -->
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
        function aniFour(){
            TweenMax.fromTo('.dot2', 1, {left: '29.5%', scale: 1, opacity: 0}, {left: '36.6%', scale: 0.8, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot3', 1, {left: '17.5%', scale: 1, opacity: 0}, {left: '38%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot4', 1, {left: '12%', scale: 1, opacity: 0}, {left: '35.2%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot5', 1, {left: '15.2%', scale: 1, opacity: 0}, {left: '36.2%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            ///////////
            TweenMax.fromTo('.dot6', .3, {left: '23%', top: '70.6%', scale: 1, opacity: 0}, {left: '23%', top: '60.6%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot6', .3, {left: 'left: 23%;', top: '60.6%', scale: 0.7, opacity: 1}, {left: '29.3%', top: '44.6%', scale: 0.7, opacity: 1, delay:.3, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot6', .4, {left: '29.3%', top: '44.6%', scale: 0.7, opacity: 1}, {left: '36.2%', top: '44%', scale: 0.7, opacity: 1, delay:.6, transformOrigin: 'center center'});
            //////////////
            TweenMax.fromTo('.dot7', 0.6, {left: '32.5%', top: '64.6%', scale: 0.7, opacity: 1}, {left: '36.4%', top: '49.6%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});
            TweenMax.fromTo('.dot7', .4, {left: '36.4%', top: '49.6%', scale: 0.7, opacity: 1}, {left: '38.1%', top: '49.6%', scale: 0.7, opacity: 1, delay:.6, transformOrigin: 'center center'});
            /////////////////////////////////////////////
            TweenMax.fromTo('.sp-logo-2 .a1', 0.6, {rotation: 0}, {rotation: 180, repeat: 1, delay:1, repeatDelay:0, yoyo: true, transformOrigin: 'center center'});
            // TweenMax.to('.bolt-1', .4, {left: '36.4%', top: '49.6%', scale: 0.7, opacity: 1}, {left: '38.1%', top: '49.6%', scale: 0.7, opacity: 1, delay:.6, transformOrigin: 'center center'});
            TweenMax.to('.bolt-1', 2, {rotation:"360", delay:1.5, ease:Linear.easeNone, repeat:-1});
            TweenMax.to('.bolt-2', 2, {rotation:"-360", delay:1.5, ease:Linear.easeNone, repeat:-1});
            //////////
            TweenMax.fromTo('.dot1', 0.5, {right: '38%', opacity: 1}, {right: '23%', opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, delay:2.5, transformOrigin: 'center center'});
            ////
            TweenMax.fromTo('.sp-logo-1 .a1', 0.7, {rotation: 0}, {rotation: 180, repeat: 1, delay:3, repeatDelay:0, yoyo: true, transformOrigin: 'center center'});

            TweenMax.fromTo('.sp-logo-1 .a2', 0.7, {rotation: 180}, {rotation: 0, repeat: 1, delay:3, repeatDelay:0, yoyo: true, transformOrigin: 'center center', onComplete:function(){repeartAmi();}});
        }
        function repeartAmi(){
            //alert(1);

            TweenMax.fromTo('.dot2', 1, {left: '29.5%', scale: 1, opacity: 0}, {left: '36.6%', scale: 0.8, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot3', 1, {left: '17.5%', scale: 1, opacity: 0}, {left: '38%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot4', 1, {left: '12%', scale: 1, opacity: 0}, {left: '35.2%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot5', 1, {left: '15.2%', scale: 1, opacity: 0}, {left: '36.2%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            ///////////
            TweenMax.fromTo('.dot6', .3, {left: '23%', top: '70.6%', scale: 1, opacity: 0}, {left: '23%', top: '60.6%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot6', .3, {left: '23%;', top: '60.6%', scale: 0.7, opacity: 1}, {left: '29.3%', top: '44.6%', scale: 0.7, opacity: 1, delay:.3, transformOrigin: 'center center'});

            TweenMax.fromTo('.dot6', .4, {left: '29.3%', top: '44.6%', scale: 0.7, opacity: 1}, {left: '36.2%', top: '44%', scale: 0.7, opacity: 1, delay:.6, transformOrigin: 'center center'});
            //////////////
            TweenMax.fromTo('.dot7', 0.6, {left: '32.5%', top: '64.6%', scale: 0.7, opacity: 1}, {left: '36.4%', top: '49.6%', scale: 0.7, opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, transformOrigin: 'center center'});
            TweenMax.fromTo('.dot7', .4, {left: '36.4%', top: '49.6%', scale: 0.7, opacity: 1}, {left: '38.1%', top: '49.6%', scale: 0.7, opacity: 1, delay:.6, transformOrigin: 'center center'});
            /////////////////////////////////////////////
            TweenMax.fromTo('.sp-logo-2 .a1', 0.6, {rotation: 0}, {rotation: 180, repeat: 1, delay:1, repeatDelay:0, yoyo: true, transformOrigin: 'center center'});
            ////
            TweenMax.fromTo('.dot1', 0.5, {right: '38%', opacity: 1}, {right: '23%', opacity: 1, repeat: 0, repeatDelay:0, yoyo: false, delay:2.5, transformOrigin: 'center center'});
            ////
            TweenMax.fromTo('.sp-logo-1 .a1', 0.7, {rotation: 0}, {rotation: 180, repeat: 1, delay:3, repeatDelay:0, yoyo: true, transformOrigin: 'center center'});

            TweenMax.fromTo('.sp-logo-1 .a2', 0.7, {rotation: 180}, {rotation: 0, repeat: 1, delay:3, repeatDelay:0, yoyo: true, transformOrigin: 'center center', onComplete:function(){repeartAmi();}});
        }
        aniFour();
        //////////////////////////////////////////////////////////////////////////
        $( '.video-btn' ).click(function(event) {
            event.preventDefault();
            var video = $(this).attr('href')+'?autoplay=1';
            var html = '<iframe width="560" height="315" src="'+video+'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            $('#iframe-holder').html(html);
            $('#vieoModal').modal('show');
            //$('#testimonial-slider, #testimonial-slider-user').trigger('prev.owl.carousel');
        });
        $('#vieoModal').on('hide.bs.modal', function (e) {
            $('#iframe-holder').html('');
        })
        ////////
        // $('#screen-carousel').owlCarousel({
        //     items: 1,
        //     dots: true,
        //     nav: false,
        //     margin: 10,
        //     loop: false,
        //     autoWidth: false,
        //     autoplay:true,
        //     autoplayTimeout:3000,
        //     autoplayHoverPause:true,
        // });
        ////
        // $('#testimonial-slider').owlCarousel({
        //     items: 3,
        //     dots: true,
        //     nav: false,
        //     margin: 0,
        //     loop: false,
        //     autoWidth: false,
        //     responsive:{
        //         0:{
        //             items:1
        //         },
        //         600:{
        //             items:2
        //         },
        //         1000:{
        //             items:3,
        //             autoplay:true,
        //             autoplayTimeout:4000,
        //             autoplayHoverPause:true,
        //         }
        //     }
        // });
        // $('.testimonials_c_holder .testimonial_content_inner .testimonial_text_inner p').matchHeight();
	});

</script>
</body>
</html>


