<?php
$ip = $this->input->ip_address();
$ip_wise_country_code=ip_info("$ip", "Country Code");
?>
<div class="container">
      <div class="row text-center w-75 mx-auto">
         <!-- <div class="footer-logo">
            <img src="home/images/logo.png">
            </div> -->
         <div class="footer-menu w-100">         
            <ul>
                <li class="nav-item">
                    <a href="https://lmsbaba.com/#Features" class="nav-link dropdown-toggle">Features
                    </a>
                </li>
                <li class="nav-item">
                    <a href="about-us" class="nav-link dropdown-toggle">
                        About us
                    </a>
                </li>          
                <?php if($ip_wise_country_code=='IN'){?>      
                <li class="nav-item">
                    <a href="https://lmsbaba.com/#Pricing" class="nav-link">Pricing</a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="https://lmsbaba.com/#Clients" class="nav-link">Clients</a>
                </li>                
                <li class="nav-item">
                    <a href="contact-us" class="nav-link contact_us--">Contact</a>
                </li>
                
            </ul>
         </div>
         <!-- <div class="footer-menu w-100 social-menu">
            <ul>
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
            </ul>
            </div> -->
         <div class="text-center w-100">
            <div class="footer-end-text" style="margin-bottom: 10px;">
               <a href="https://app.lmsbaba.com/terms_of_services">Terms of Services</a> - <a href="https://app.lmsbaba.com/privacy_policy">Privacy Policy</a> - <a href="https://app.lmsbaba.com/disclaimer">Disclaimer</a>
            </div>
            <div class="footer-end-text"><b>NOIDA SALES OFFICE</b><br>
               F-04, H 169, Block H, Sector 63, Noida - 201301 (India)<br>
               Phone: +91-120-4114277, Mobile: +91-85 86 87 0 89 4 
               <br><br>
               <b>MUMBAI SALES OFFICE</b><br>
               No. 108, First Floor, Neco Chamber, Plot No. 48 Sector 11, CBD Belapur<br>Navi Mumbai, Thane-400614, Maharashtra, India<br>
               Phone: +91-22-40125628/ 29, Mobile: +91-85 86 87 0 89 4 
            </div>
            <!-- <div class="footer-end-text">
               Suite G-04, A 140, Block A, Sector 63, Noida - 201301 (India)<br>
               Phone: +91-120-4264861, Mobile: +91-85 86 87 0 89 4 
               </div> -->
            <div class="w-100">
               <hr>
               <p class="mt-4 text-center footer-text">Copyright © 2022 <a href="https://app.lmsbaba.com/">LMSBaba.com</a>. All rights reserved.</p>
            </div>
            <div class="footer-end-logo">
               <span>Powered By Srishti Ventures Inc.</span>
               <a href="https://srishtiventures.in/" target="_blank"><img src="<?php echo assets_url(); ?>images_home_v2/logo-srishti.webp"> </a>
            </div>
         </div>
      </div>
   </div>
   <!-- Modal -->
   <div class="modal fade contact-popup" id="book_demo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-body">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               <div class="book_demo_form_holder_new row align-items-center">
                  <div class="col-md-12">
                     <h3>Ask a demo</h3>
                  </div>
                  <div class="col-md-5">
                      <div class="full-pic">
                          <img src="<?php echo assets_url(); ?>images_home_v2/book-demo.png">
                      </div>
                  </div>
                  <div class="col-md-7">
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
                  <!-- <div class="alert alert-success" id="book_demo_success_div" style="display:none;">
                     <strong>Success!</strong> <span id="book_demo_success_msg"></span>
                  </div>
                  <div class="alert alert-danger" id="book_demo_error_div" style="display:none;">
                     <strong>Danger!</strong> <span id="book_demo_error_msg"></span>
                  </div> -->
                  
               </div>
               <div class="book_demo_sucess_holder" style="display: none;">
                  <h3>Thank you for your demo request. LMSbaba<sup>TM</sup> <br>team will contact you soon.</h3>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade contact-popup" id="contact_us_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-body">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
               <div class="row">       
                   <div class="col-md-12">
                      <h3>Contact Us</h3>
                   </div>
                   <div class="col-md-5">
                        <div class="contact-grey">
                            <div>
                                <h4>LMSBABA.COM</h4>
                                <h5>Brand of Srishti Ventures Inc.</h5>
                                <p>F-04. H 169. Block H. Sector 63 <br>Noida - 201301 (India)</p>
                                <p>
                                    Phone: +91-120-4114277. <br>Mobile: +91-85 86 87 0 89 4 <br>Email: info@Imsbaba.com
                                </p>
                            </div>
                        </div>
                   </div>
                   <div class="col-md-7">
                      <form id="contact_us_form">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label>Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter here.." name="contact_us_name" id="contact_us_name">
                                <div class="text-danger" id="contact_us_name_error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label>Company<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter here.." name="contact_us_company" id="contact_us_company">
                                <div class="text-danger" id="contact_us_company_error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleFormControlInput1">Mobile<span class="text-danger">*</span></label>
                                <input type="text" class="form-control only_natural_number" name="contact_us_mobile" id="contact_us_mobile" maxlength="10" placeholder="Enter here..">
                                <div class="text-danger" id="contact_us_mobile_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleFormControlInput1">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="contact_us_email" id="contact_us_email" placeholder="Enter here..">
                                <div class="text-danger" id="contact_us_email_error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="comment">Comment<span class="text-danger">*</span>:</label>
                                <textarea class="form-control" rows="3" id="contact_us_comment" name="contact_us_comment" placeholder="Enter here.."></textarea>
                                <div class="text-danger" id="contact_us_comment_error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="alert alert-success" id="contact_us_success_div" style="display:none;">
                                <strong>Success!</strong> <span id="contact_us_success_msg"></span>
                            </div>
                            <div class="alert alert-danger" id="contact_us_error_div" style="display:none;">
                                <strong>Danger!</strong> <span id="contact_us_error_msg"></span>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="contact_us_send_confirm">Send</button>  
                        </div>
                      </form>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Modal -->
    <div class="modal fade" id="vieoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </button>
                    <div id="iframe-holder"></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="base_url" value="<?php echo base_url();?>">