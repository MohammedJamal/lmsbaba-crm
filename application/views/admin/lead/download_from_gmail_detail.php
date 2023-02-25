<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>    
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
                  <div class="row m-b-1">
                    <div class="col-sm-4 pr-0">
                       <div class="bg_white back_line">
                          <h4>Manage Gmail Inbox <img src="<?php echo assets_url(); ?>images/gmail-icon.png"/></h4>
                       </div>
                    </div>
                    <div class="col-sm-8 pleft_0">
                       <div class="bg_white_filt">
                          <div class="text-right pull-right-flex mt-6">
                             <?php if($previous_thread){ ?>
                             <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_from_gmail_detail/<?php echo $previous_thread;?>" class="g-style"><img src="https://lmsbaba.com/dashboard/images/left_black.png"></a> <?php } ?> 
                             <?php if($next_thread){ ?> <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_from_gmail_detail/<?php echo $next_thread; ?>" class="g-style"><img src="https://lmsbaba.com/dashboard/images/right_black.png"></a><?php } ?>
                          </div>
                       </div>
                    </div>
                  </div>
                 
                  <div class="card process-sec mail-block">
                    <span id="selected_filter_div" class="lead_filter_div"></span>
                    <div class="card-block mail-block">
                       <div class="mail-box-holder">
                          <div class="mail-left-block">
                             <div class="mail-details-main">
                                <div class="fix-outer">
                                   <div class="fix-inner">
                                      <div class="other-holder float-left">
                                         <?php
                                         if($lead_id>0)
                                         {
                                            $is_sync='Y';
                                         }
                                         else
                                         {
                                            $is_sync='N';
                                         }
                                         ?>
                                         <ul class="action-ul no-arrow">
                                            <li><a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_from_gmail/<?php echo $is_sync; ?>"><img src="<?php echo assets_url(); ?>images/arrow_back_white.png"></a></li>
                                            <!-- <li><a href="#"><img src="https://lmsbaba.com/dashboard/images/report_white.png"></a></li> -->
                                            <li><a href="JavaScript:void(0)" class="delete" data-id="<?php echo $start_mail['thread_id']; ?>" data-toggle="tooltip" title="Delete"><img src="<?php echo assets_url(); ?>images/delete_white.png"></a></li>
                                            <li><a href="JavaScript:void(0)" data-toggle="tooltip" data-id="<?php echo $start_mail['thread_id']; ?>" title="<?php echo ($start_mail['is_read']=='N')?'Mark as read':'Mark as unread' ?>" data-curstatus="<?php echo $start_mail['is_read']; ?>" class="seen_status_change"><?php if($start_mail['is_read']=='N'){?><img src="<?php echo assets_url(); ?>images/drafts_white.png"><?php }else{?> <img src="<?php echo assets_url(); ?>images/mark_as_unread_white.png"><?php } ?></a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="selected_delete delete" data-status="A" data-toggle="tooltip" title="Archive"><img src="<?php echo assets_url(); ?>images/archive_black_icon.png"></a></li>
                                         </ul>
                                      </div>
                                   </div>
                                </div>
                                <div class="mail-block">
                                   <div class="mail-box">
                                      <div class="mail-title big-font"><?php echo $start_mail['subject']; ?></div>
                                   </div>
                                   <?php 
                                      
                                      $tmp_from_mail=array();
                                      $tmp_to_mail=array();
                                      if(count($rows)>0){ $i=0; ?>

                                   <?php foreach($rows AS $row){
                                      $last_from_email='';
                                      $last_to_email='';
                                      $last_id='';

                                      $f_email_arr=explode(',', $row['from_mail']);
                                      if(count($f_email_arr))
                                      {
                                         foreach($f_email_arr AS $fm)
                                         {
                                            $tmp_from_mail[$fm]=$fm;
                                         }
                                      }

                                      $t_email_arr=explode(',', $row['to_mail']);
                                      if(count($t_email_arr))
                                      {
                                         foreach($t_email_arr AS $tm)
                                         {               
                                            $tmp_to_mail[$tm]=$tm; 
                                         }
                                      }
                                      // $tmp_from_mail[$row['from_mail']]=$row['from_name']; 
                                      // $tmp_to_mail[$row['to_mail']]=$row['to_name']; 
                                      ?>
                                   <div class="mail-details-block">
                                      <div class="mail-profile-pic-holder">
                                         <figure><img src="<?php echo assets_url(); ?>images/gmail-user.png"></figure>
                                      </div>
                                      <div class="mail-profile-content-holder">
                                         <div class="mail-profile-content-top">
                                            <div class="float-full medium-font">
                                               <?php echo $row['from_name']; ?> 
                                               <span class="show-full">
                                               <span aria-hidden="true">&lt;</span> 
                                               <?php
                                                  echo preg_replace('/(.*)<(.*)>(.*)/sm', '\2', $row['from_name']);
                                                  ?>                     
                                               <span aria-hidden="true">&gt;</span>
                                               </span>
                                               <div class="mail-time float-right reply">
                                                  <?php if($row['file_name']){?>
                                                  <div class="attachment_clip"></div>
                                                  <?php } ?>
                                                  <?php 
                                                     $email_date=date('Y-m-d H:i:s',$row['internal_date']/1000);
                                                     echo datetime_db_format_to_gmail_details_format($email_date);?> 
                                                  <?php
                                                  //if($start_mail['lead_id']>0)
                                                  {
                                                     $last_from_email=$row['from_mail'];
                                                     $last_to_email=$row['to_mail'];
                                                     $last_id=$row['id'];
                                                  ?>
                                                  <a href="javascript:void(0);" class="mail-btn <?php echo ($is_gmail_connected=='Y')?'open_reply_box':'open_reply_box_restricted_alert' ?>" data-tomail="<?php echo $row['to_mail']; ?>" data-frommail="<?php echo $row['from_mail']; ?>" data-lid="<?php echo $start_mail['lead_id']; ?>" data-id="<?php echo $row['id']; ?>"><img src="<?php echo assets_url(); ?>images/reply_black_icon.png"></a> 
                                                  <?php
                                                  }
                                                  ?>
                                               </div>
                                               <!-- </lmsbabadev@gmail.com> -->
                                            </div>
                                            <div class="float-full show-full">
                                               <div class="dropdown">
                                                  <a href="#" class="dropdown-toggle" tid="dropdownMenuTo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> to <?php echo $row['to_mail']; ?></a>
                                                  <div class="dropdown-menu dropdown-left" aria-labelledby="dropdownMenuTo">
                                                     <ul>
                                                        <li>
                                                           <label>from:</label><?php echo htmlentities($row['from_name']); ?>   
                                                        </li>
                                                        <li><label>to:</label><?php echo htmlentities($row['to_mail']); ?></li>
                                                        <li><label>date:</label><?php echo $row['date']; ?></li>
                                                        <li><label>subject:</label><?php echo $row['subject']; ?></li>
                                                     </ul>
                                                  </div>
                                               </div>
                                            </div>
                                         </div>
                                         <div class="mail-profile-content-body">
                                            <?php if($i==0)
                                               {
                                                 ?>
                                                  <div class="tsp one"><?php echo $row['body_html']; ?></div>
                                               <?php
                                               }
                                               else
                                               {                     
                                                 ?>
                                                  <div class="tsp two">
                                                     <?php echo substr($row['body_text'],0,50);?>
                                                  </div>
                                                  <div class="gmail_quote_holdet">
                                                     <a href="#" class="show_gmail_quote"></a>
                                                     <div class="gmail_quote"><?php echo $row['body_html']; ?></div>
                                                  </div>
                                            <?php
                                               }
                                               ?>  
                                            <?php 
                                               if($row['file_name'])
                                               { 
                                               ?>
                                            <div class="mail-attachment ">
                                               <?php 
                                                  $file_arr=explode(",", $row['file_name']);
                                                  $file_path_arr=explode(",", $row['file_full_path']);
                                                  $k=0;
                                                  for($i=0;$i<count($file_arr);$i++)
                                                  {
                                                  ?>
                                               <span data-content="<?php echo base64_encode($file_path_arr[$i]); ?>" class="attachment_download"><?php 
                                                  $ext = pathinfo($file_arr[$i], PATHINFO_EXTENSION);
                                                  //var $ext=end(explode('.', $file_arr[$i]));
                                                  rander_extention_wise_image($ext);
                                                  ?>
                                               <?php echo $file_arr[$i]; ?></span>
                                               <?php 
                                                  }                   
                                                  ?>
                                            </div>
                                            <?php 
                                               } 
                                               ?>                                    
                                         </div>
                                      </div>
                                   </div>
                                   <?php $i++;} ?>
                                   <?php }                             
                                      $new_mail_arr=array_merge($tmp_from_mail,$tmp_to_mail);
                                      ?>      

                                      <?php
                                      //if($start_mail['lead_id']>0)
                                      {
                                      ?>
                                      <div class="mail-block-action">
                                        <ul class="ddg">                              
                                            <li>
                                              <a href="javascript:void(0);" class="mLink <?php echo ($is_gmail_connected=='Y')?'open_reply_box':'open_reply_box_restricted_alert' ?>" data-tomail="<?php echo $last_from_email; ?>" data-frommail="<?php echo $last_to_email; ?>" data-lid="<?php echo $start_mail['lead_id']; ?>" data-id="<?php echo $last_id; ?>">
                                                <span><img src="<?php echo assets_url(); ?>images/reply_black_icon.png"></span>
                                                Reply
                                              </a>
                                            </li>
                                          </ul>
                                      </div> 
                                      <?php
                                      }
                                      ?>                     
                                </div>
                             </div>
                          </div>
                          <div class="mail-right-block">
                             <div class="contact-scroller">
                                <?php
                                   if($start_mail['lead_id']>0)
                                   {
                                      ?>
                                               <input type="hidden" id="is_linked_view" value="Y">
                                               <input type="hidden" id="is_customer_exist" value="Y">
                                               <div class="mail-contact-block">
                                                  <div class="mail-contact">
                                                     <div class="link-title">Linked with Enquiry ID: <?php echo $lead_id; ?></div>
                                                     <div class="link-txt"><?php echo $lead_data->title; ?></div>
                                                     <div class="link-row"><strong>Stage:</strong> <?php echo $lead_data->current_stage; ?></div>
                                                     <div class="link-row"><strong>Status:</strong> <?php echo $lead_data->current_status; ?></div>
                                                  </div>
                                                  <div class="mail-link-holder s-w">
                                                     <a href="JavaScript:void(0)" class="lead-btn grey-border" id="linked_lead_view_popup" data-leadid="<?php echo $start_mail['lead_id']; ?>">View</a>
                                                     <a href="JavaScript:void(0)" class="lead-btn grey-border mlr-10" id="linked_comment_update_lead_popup" data-leadid="<?php echo $start_mail['lead_id']; ?>">UPDATE</a>
                                                  </div>
                                               </div>
                                <?php
                                   }
                                   else
                                   {
                                ?>
                                   <input type="hidden" id="is_linked_view" value="N">
                                   <?php
                                      if(count($non_contact_email_arr)>0)
                                      {
                                                  
                                      ?>                    
                                               <input type="hidden" id="is_customer_exist" value="N">
                                               <div class="mail-contact-block">
                                                  <div class="mail-contact">
                                                     <h2><?php echo count($non_contact_email_arr); ?> New Contact(s) Found</h2>
                                                     <div class="mail-row align-items-center">
                                                        <?php //print_r($new_mail_arr); ?>
                                                        <?php 
                                                           foreach($non_contact_email_arr AS $mail)
                                                           {
                                                             $name=htmlentities($new_mail_arr[$mail]);
                                                             $name_arr=explode(' ', $name);
                                                             $tmp_name=$name_arr[0];                        
                                                             ?>
                                                        <div class="mail-loop-new">
                                                           <div class="pp bd"><?php echo substr(strtoupper($name),0,1); ?></div>
                                                           <div class="mail-name">
                                                              <?php echo $new_mail_arr[$mail]; ?>
                                                              <span><?php echo $mail; ?></span>
                                                           </div>
                                                           <label class="check-box-sec">
                                                           <input type="radio" name="customer_info" date-custemail="<?php echo $mail; ?>" data-custname="<?php echo $tmp_name; ?>">
                                                           <span class="checkmark"></span>
                                                           </label>
                                                        </div>
                                                        <?php
                                                           }
                                                           ?>
                                                     </div>
                                                  </div>
                                                  <div class="mail-link-holder mt-30">
                                                     <a href="JavaScript:void(0);" class="lead-btn orange create_new_lead" id="" data-customerid="" data-threadid="<?php echo $thread_id; ?>">Create Lead</a>
                                                  </div>
                                               </div>
                                      <?php
                                      }
                                      if(count($contact_email_arr)>0)
                                      {

                                        ?>
                                            <input type="hidden" id="is_customer_exist" value="Y">
                                            <div class="mail-contact-heding">
                                               <h2><?php echo count($contact_list); ?> Old Contact(s) Found</h2>
                                            </div>
                                            <?php
                                            foreach($contact_list AS $contact)
                                            {
                                              $name=htmlentities($new_mail_arr[$contact['email']]);
                                               ?>
                                               <div class="mail-contact-block">
                                                  <div class="mail-contact">
                                                     <div class="mail-row align-items-center">
                                                        <div class="pp bd"><?php echo substr(strtoupper($name),0,1); ?></div>
                                                        <div class="mail-name">
                                                           <?php echo $name; ?>
                                                        </div>
                                                     </div>
                                                     <div class="found-contact">
                                                        <h3><?php echo $contact['company_name']; ?>- #<?php echo $contact['customer_id']; ?></h3>
                                                        <div class="found-col">
                                                           <label><b>Contact Person</b></label>:<b class="ml-6"><?php echo $contact['contact_person']; ?></b>
                                                        </div>
                                                        <div class="found-col">
                                                           <label>Open Lead</label>: <a href="JavaScript:void(0)" class="<?php if($contact['open_count']>0){echo 'lead_view_popup';} ?>" data-customerid="<?php echo $contact['customer_id']; ?>" data-filterbystage="pending_lead"><?php echo $contact['open_count']; ?></a>
                                                        </div>
                                                        <div class="found-col">
                                                           <label>Closed Lead</label>: <a href="JavaScript:void(0)" class=" <?php if($contact['closed_count']>0){echo 'lead_view_popup';} ?>" data-customerid="<?php echo $contact['customer_id']; ?>" data-filterbystage="closed_lead"><?php echo $contact['closed_count']; ?></a>
                                                        </div>
                                                        <div class="found-col">
                                                           <label>Quotation Count</label>: <a href="JavaScript:void(0)" class="quoted_view_popup" data-customerid="<?php echo $contact['customer_id']; ?>" data-quotedlids="<?php echo $contact['quoted_lead_ids']; ?>"><?php echo $contact['proposal_count']; ?></a>
                                                        </div>
                                                     </div>
                                                  </div>
                                                  <div class="mail-link-holder">
                                                     <a href="JavaScript:void(0)" class="lead-btn orange create_new_lead" id="" data-customerid="<?php echo $contact['customer_id']; ?>" data-threadid="<?php echo $thread_id; ?>">Create Lead</a>
                                                     <a href="JavaScript:void(0)" class="lead-btn grey-border comment_update_lead_popup" id="" data-customerid="<?php echo $contact['customer_id']; ?>">UPDATE LEAD</a>
                                                  </div>
                                               </div>
                                            <?php
                                            }                     
                                      } 
                                   }                                             
                                   ?>
                                </div>
                             </div>
                          </div>
                    </div>
                  </div>
              </div>                
            </div>
            <div class="content-footer">
              <?php $this->load->view('admin/includes/footer'); ?>
            </div>
        </div>
    </div>
    <?php $this->load->view('admin/includes/modal-html'); ?>
    <?php $this->load->view('admin/includes/app.php'); ?> 
  </body>
</html>
<!-- ---------------------------- -->
         <!-- MAIL REPLY MODAL -->
         <?php /* ?>
         <div id="bulk_mail_pop" class="bulk-mail">
            <form id="reply_mail_frm" name="reply_mail_frm">
               <div>
                  <div class="bulk-header">
                     <ul class="bHolder">
                        <li><a href="#" class="bulk-mini"></a></li>
                        <li><a href="#" class="bulk-full"></a></li>
                        <li>
                           <a href="#" class="bulk-close"><img src="<?php echo assets_url(); ?>images/close_window.png"/>"></a>
                        </li>
                     </ul>
                     
                  </div>
                  <div class="bulk-others">
                     <div class="bulk-sender">
                        <label class="flabel active">To</label>
                        <input type="text" class="bulk_to_new ani-txt active" name="email_to_email" id="email_to_email" value="" placeholder="" data-content="Recipients" data-to="To" readonly="true">
                        <div id="show_selected_to_email"></div>
                     </div>
                     <!-- <div class="bulk-sender">
                        <label class="flabel active">From</label>
                        <input type="text" class="bulk_to_new ani-txt active" name="email_from_email" id="email_from_email" value="<?php echo $this->session->userdata['admin_session_data']['email']; ?>" data-content="Your Email" data-to="From" readonly="true">
                     </div> -->
                     <div class="bulk-sender">
                        <input type="text" class="" name="email_subject" id="email_subject" value="" placeholder="Subject">
                     </div>
                     <div class="bulk-body">
                        <textarea placeholder="Type your comment" name="email_body" id="email_body" class="bulkEmail-wysiwyg-editor" ></textarea>
                     </div>
                     <div class="bulk-footer">
                        <ul>
                           <li>
                              <a href="JavaScript:void(0);" class="bulk-send-btn custom_blu" id="reply_submit_confirm">Send</a>
                           </li>
                           <!-- <li>
                              <label class="bulk-attach" for="mail_attach">
                              <input type="file" name="mail_attach" id="mail_attach">
                              <span><i class="fa fa-paperclip" aria-hidden="true"></i></span>
                              </label>
                              <div class="del_pdf_file" style="display: none;">
                                 <span>Maxbridge Solutions LLP.pdf</span>
                                 <a href="#" class="del_pdf_new"><i class="fa fa-trash" aria-hidden="true"></i></a>
                              </div>
                           </li> -->
                        </ul>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <?php */ ?>
         <!-- MAIL REPLY MODAL -->
         <!-- ---------------------------- -->
         <!-- ---------------------------- -->
         <!-- CREATE NEW LEAD MODAL -->
         <form id="frmLeadAdd">
            <div class="modal fade mail-modal new-lead" id="CreateLeadViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         </form>
         <!-- CREATE NEW LEAD MODAL -->
         <!-- ---------------------------- -->
         <!-- ---------------------------- -->
         <!-- LEAD DETAILS MODAL -->
         <form id="frmLeadEdit">
            <div class="modal fade mail-modal existing-lead small-new" id="LeadViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         </form>
         <!-- LEAD DETAILS MODAL -->
         <!-- ---------------------------- -->
         <!-- ---------------------------- -->
         <!-- QUOTATION MODAL -->
         <div class="modal fade mail-modal" id="QuotationViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         <!-- QUOTATION MODAL -->
         <!-- ---------------------------- -->
         <!-- ---------------------------- -->
         <!-- UPDATE LEAD MODAL -->
         <form class="" name="lead_update_frm" id="lead_update_frm" method="post">
            <div class="modal fade mail-modal" id="CommentUpdateLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         </form>
         <!-- UPDATE LEAD MODAL -->
         <!-- ---------------------------- -->

         <!-- ---------------------------- -->
         <!-- UPDATE LEAD MODAL -->
         <form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
            <div class="modal fade mail-modal" id="PoUploadLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         </form>
         <!-- UPDATE LEAD MODAL -->
         <!-- ---------------------------- -->

         <!-- ---------------------------- -->
         <!-- UPDATE LEAD MODAL -->
         
         <div class="modal fade mail-modal" id="QuotationListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         
         <!-- UPDATE LEAD MODAL -->
         <!-- ---------------------------- -->

         <!-- ---------------------------- -->
         <!-- UPDATE LEAD MODAL -->
         <form id="reply_mail_frm" name="reply_mail_frm">
            <div class="modal fade mail-modal" id="ReplyPopupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         </form>
         
         <!-- UPDATE LEAD MODAL -->
         <!-- ---------------------------- -->

         <!-- bottom footer -->
         
         <!-- <div class="loading--">Loading&#8230;</div> -->
         <link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
         <script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script>      
         <!-- <script type="text/javascript" src="<?php echo assets_url(); ?>js/app.js"></script> -->
         <!-- <script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.nice-select.js"></script> -->
         <!-- <script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script> -->
         <!-- <script type="text/javascript" src="<?php echo assets_url(); ?>js/common_functions.js"></script> -->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
         <script src="<?php echo assets_url(); ?>vendor/sweetalert/dist/sweetalert.min.js"></script>
         <script src="<?php echo assets_url(); ?>js/custom/lead/get_download_from_gmail_detail.js"></script>
         <script src="<?=assets_url();?>js/select2.min.js"></script>
         <script type="text/javascript"> 
            $(document).ready(function(){
                $(window).on("load",function(){
                  $(".contact-scroller").mCustomScrollbar({
                     scrollButtons:{enable:true},
                     theme:"rounded-dark"
                  });
            
                });
            
                $(document).on("click",".mail-details-block .mail-profile-content-holder .mail-profile-content-top",function(event) {
                event.preventDefault();
                $(this).parent().parent().toggleClass('active');
                });
                $('input.mail-input:not(.auto-w)').each(function( index ) {
                //console.log( index + ": " + $( this ).text() );
                $(this).attr('size', $(this).val().length);
                });
                $(".buyer-scroller").mCustomScrollbar({
                scrollButtons:{enable:true},
                theme:"rounded-dark"
                });



$(document).on("click",".create_new_lead",function(event) {
   event.preventDefault();                      
   var base_url = $("#base_url").val();
   var cust_id=$(this).attr("data-customerid");
   var thread_id=$(this).attr('data-threadid');
   var customer_email='';
   var customer_name='';
   var is_customer_exist=$("#is_customer_exist").val();
   //alert(cust_id);
   //if(is_customer_exist=='N')
   if(cust_id=='')
   {
      var customer_email=$("input[name=customer_info]:checked").attr('date-custemail');
      var customer_name=$("input[name=customer_info]:checked").attr('data-custname')
      if($("input[name=customer_info]:checked").length==0)
      {
         swal('Oops! Please checked a contact to create a customer.');
         return false;                        
      }
   }

   // var data="cust_id="+cust_id+"&thread_id="+thread_id+"&customer_email="+customer_email+"&customer_name="+customer_name; 

   // alert(data)
   var base_url = $("#base_url").val();
   $.ajax({
         url: base_url + "lead/rander_create_lead_view_popup_ajax",
         type: "POST",
         data: {
         'cust_id': cust_id,
         'thread_id':thread_id,
         'customer_email':customer_email,
         'customer_name':customer_name,
         },
         async: true,
         beforeSend: function( xhr ) {
                    $.blockUI({ 
                    message: 'Please wait...', 
                    css: { 
                       padding: '10px', 
                       backgroundColor: '#fff', 
                       border:'0px solid #000',
                       '-webkit-border-radius': '10px', 
                       '-moz-border-radius': '10px', 
                       opacity: .5, 
                       color: '#000',
                       width:'450px',
                       'font-size':'14px'
                      }
                });
      },
      complete: function (){
                $.unblockUI();
        },
         success: function(response) {
            // $('#quotationLeadModal').modal('show')
            $('#CreateLeadViewModal').html(response);
            $(".buyer-scroller").mCustomScrollbar({
               scrollButtons:{enable:true},
               theme:"rounded-dark"
            });
            simpleEditer();
            $('#CreateLeadViewModal').modal({
               backdrop: 'static',
               keyboard: false
            });
         },
         error: function() {

         }
   }); 
});
                $(document).on("click",".show_gmail_quote",function(event) {
                event.preventDefault();
                $(this).parent().find('.gmail_quote').toggleClass('show');
                });
                //////
                $("body").on("click","#top_menu",function(e){
                $("#top_menu_div").toggle();
                });
            
                $("body").on("click","#top_notification",function(e){
                $("#top_notification_div").toggle();
                });
            
            $(document).on("click","#linked_lead_view_popup",function(event) {
            var lead_id=$(this).attr('data-leadid');
            open_lead_view(lead_id,'');
            });
$(document).on("click",".lead_view_popup",function(event) {
     event.preventDefault();
     var base_url = $("#base_url").val();
     var cust_id=$(this).attr("data-customerid");
     var filter_by_stage=$(this).attr("data-filterbystage");
     var data="cust_id="+cust_id+"&filter_by_stage="+filter_by_stage; 
     // alert(data);                     
      $.ajax({
             url: base_url+"lead/get_lead_id_from_customers",
             data: data,
             //data: new FormData($('#frmAccount')[0]),
             cache: false,
             method: 'POST',
             dataType: "html",
             //mimeType: "multipart/form-data",
             //contentType: false,
             //cache: false,
             //processData:false,
             beforeSend: function( xhr ) {
                    $.blockUI({ 
                    message: 'Please wait...', 
                    css: { 
                       padding: '10px', 
                       backgroundColor: '#fff', 
                       border:'0px solid #000',
                       '-webkit-border-radius': '10px', 
                       '-moz-border-radius': '10px', 
                       opacity: .5, 
                       color: '#000',
                       width:'450px',
                       'font-size':'14px'
                      }
                });
               },
               complete: function (){
                         $.unblockUI();
                 },
             success: function(data){
                 result = $.parseJSON(data);
                 if(result.lid>0)
                 {
                   open_lead_view(result.lid,filter_by_stage)
                 }
             },
             
      });                                           
});
            
                $("body").on("click",".new_lead_view_popup",function(e){
                  var lid=$(this).attr('data-id');
                  var filter_by_stage=$(this).attr('data-filterbystage');
                  if(lid>0)
                  {
                    open_lead_view(lid,filter_by_stage)
                  }
                  
                });
            
                //quotationLeadModal
                $(document).on("click",".quoted_view_popup",function(event) {
                    event.preventDefault();
                    var base_url = $("#base_url").val();
                    var cid=$(this).attr('data-customerid');
                    var lead_ids=$(this).attr('data-quotedlids');
                    var data="cust_id="+cid+"&lead_ids="+lead_ids; 
                    // alert(data);  return false;                   
                    $.ajax({
                            url: base_url+"lead/get_quotation_id_from_leads",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'POST',
                            dataType: "html",
                            //mimeType: "multipart/form-data",
                            //contentType: false,
                            //cache: false,
                            //processData:false,
                            beforeSend: function( xhr ) { 
                              //$("#preloader").css('display','block');
                            },
                            success: function(data){
                                result = $.parseJSON(data);
                                // alert(result.id); return false;
                                if(result.id>0)
                                {                                    
                                  open_quotation_view(result.id,lead_ids);  
                                }
                            },
                            complete: function(){
                              //$("#preloader").css('display','none');
                            },
                    }); 
                                        
                });
            
                $("body").on("click",".new_quotation_view_popup",function(e){
                  var id=$(this).attr('data-id');
                  var lead_ids=$(this).attr('data-quotedlids');
                  
                  if(id>0 && lead_ids!='')
                  {
                    open_quotation_view(id,lead_ids)
                  }
                  
                });
            
            
$("body").on("click","#linked_comment_update_lead_popup",function(e)
{
   var lead_id=$(this).attr('data-leadid');      
   open_comment_update_lead_view(lead_id);
});

//update_lead
$(document).on("click",".comment_update_lead_popup",function(event) {
   event.preventDefault();
   var base_url = $("#base_url").val();
   var cust_id=$(this).attr("data-customerid");
   var data="cust_id="+cust_id+"&filter_by_stage="; 
   // alert(data);                     
   $.ajax({
   url: base_url+"lead/get_lead_id_from_customers",
   data: data,
   //data: new FormData($('#frmAccount')[0]),
   cache: false,
   method: 'POST',
   dataType: "html",
   //mimeType: "multipart/form-data",
   //contentType: false,
   //cache: false,
   //processData:false,
   beforeSend: function( xhr ) { 
   //$("#preloader").css('display','block');
   },
   success: function(data){
    result = $.parseJSON(data);
    
    if(result.lid>0)
    {
      // $('#updateLeadModal').modal('show')
      open_comment_update_lead_view(result.lid);
    }
    else
    {
      swal('Oops! No lead exist in the lead.');
    }
   },
   complete: function(){
   //$("#preloader").css('display','none');
   },
   }); 

});
       

$("body").on("click","#comment_update_confirm",function(e){
   var base_url = $("#base_url").val();  

   // var description=document.getElementById('general_description').value;
   // var description = tinyMCE.activeEditor.getContent();
   var ThisObj=$(this);
   var box = $('.buying-requirements');
   var description = box.html();
   var lead_id = document.getElementById('lead_id').value;   
   var communication_type = document.getElementById('communication_type').value;
   var next_followup_date = document.getElementById('next_followup_date').value;
   var next_followup_type_id = document.getElementById('next_followup_type_id').value;
   var mail_to_client = ($('input[name="mail_to_client"]:checked').val()) ? $('input[name="mail_to_client"]:checked').val() : 'N';
   var mark_cc_mail_str = '';
   $('#general_description').val(description);

   if (description == '') 
   {
      swal('Oops! Description should not be blank.');
      return false;
   }

   if (communication_type == '') 
   {
      swal('Oops! Please select communication type.');
      return false;
   }

   if (next_followup_date == '') 
   {
      swal('Oops! Please select next followup date.');
      return false;
   }

   if(next_followup_type_id == '') 
   {
      swal('Oops! Please select next followup type.');
      return false;
   }

   if ($("#mark_cc_mail").val()) 
   {
      var mark_cc_mail_str = $("#mark_cc_mail").val().toString();
   }

   if(mail_to_client=='Y')
   {
      if($("#mail_to_client_mail_subject").val()=='')
      {
         swal('Oops! Please enter mail subject for Mail to Client .');
         return false;
      }        
   }
   
   $.ajax({
         url: base_url + "lead/create_lead_comment_ajax",
         data: new FormData($('#lead_update_frm')[0]),
         cache: false,
         method: 'POST',
         dataType: "html",
         mimeType: "multipart/form-data",
         contentType: false,
         cache: false,
         processData: false,
         beforeSend: function(xhr) {
            //$(".preloader").show();
            ThisObj.attr("disabled",true);
         },
         complete: function(){
            //$("#preloader").css('display','none');
            ThisObj.attr("disabled",false);
         },
         success: function(data) {
            //$(".preloader").hide();
            result = $.parseJSON(data);
            //console.log(result.msg);

               if (result.status == 'success') 
               {
                   swal({
                           title: "Success",
                           text: result.msg,
                           type: "success",
                           showCancelButton: false,
                           confirmButtonColor: "#DD6B55",
                           confirmButtonText: "",
                           closeOnConfirm: true
                       },
                       function() {
                           // location.reload();
                           var is_linked_view=$("#is_linked_view").val();
                           if(is_linked_view=='N')
                           {
                             var thread_id=$("#thread_id").val();
                             var data="lead_id="+lead_id+"&thread_id="+thread_id;
                             
                             $.ajax({
                                     url: base_url+"lead/lead_update_to_gmail_thread",
                                     data: data,
                                     //data: new FormData($('#frmAccount')[0]),
                                     cache: false,
                                     method: 'POST',
                                     dataType: "html",
                                     //mimeType: "multipart/form-data",
                                     //contentType: false,
                                     //cache: false,
                                     //processData:false,
                                     beforeSend: function( xhr ) { 
                                       //$("#preloader").css('display','block');
                                     },
                                     success: function(data){
                                         result = $.parseJSON(data);
                                         // alert(result.id); return false;
                                         if(result.status='success')
                                         {  
                                          // window.location.reload();
                                          location.reload();  
                                         }
                                     },
                                     complete: function(){
                                       //$("#preloader").css('display','none');
                                     },
                             });
                           }
                           else
                           {
                             location.reload();
                           }
                         
                  });
               } 
               else 
               {

               }
         }
   });                      
});

$("body").on("click","#regret_comment_update_confirm",function(e){
   var base_url = $("#base_url").val();  

   // var description=document.getElementById('general_description').value;
   // var description = tinyMCE.activeEditor.getContent();
   var box = $('.buying-requirements');
   var description = box.html();
   var lead_id = document.getElementById('lead_id').value;   
   var lead_regret_reason_id = $("#lead_regret_reason_id").val();
   // var next_followup_date = document.getElementById('next_followup_date').value;
   // var next_followup_type_id = document.getElementById('next_followup_type_id').value;
   var mail_to_client = ($('input[name="mail_to_client"]:checked').val()) ? $('input[name="mail_to_client"]:checked').val() : 'N';
   // var mark_cc_mail_str = '';
   $('#general_description').val(description);
   // alert(lead_regret_reason_id)
   if (lead_regret_reason_id == '') 
   {
      swal('Oops! Please select reason .');
      return false;
   }

   if (description == '') 
   {
      swal('Oops! Comment should not be blank.');
      return false;
   }   

   // if ($("#mark_cc_mail").val()) 
   // {
   //    var mark_cc_mail_str = $("#mark_cc_mail").val().toString();
   // }

   if(mail_to_client=='Y')
   {
      if($("#mail_to_client_mail_subject").val()=='')
      {
         swal('Oops! Please enter mail subject for Mail to Client .');
         return false;
      }        
   }
   
   $.ajax({
         url: base_url + "lead/create_lead_comment_ajax",
         data: new FormData($('#lead_update_frm')[0]),
         cache: false,
         method: 'POST',
         dataType: "html",
         mimeType: "multipart/form-data",
         contentType: false,
         cache: false,
         processData: false,
         beforeSend: function(xhr) {
         //$(".preloader").show();
            $("#regret_comment_update_confirm").attr("disabled",true);
         },
         success: function(data) {
            //$(".preloader").hide();
            result = $.parseJSON(data);
            //console.log(result.msg);

               if (result.status == 'success') 
               {
                   swal({
                           title: "Success",
                           text: result.msg,
                           type: "success",
                           showCancelButton: false,
                           confirmButtonColor: "#DD6B55",
                           confirmButtonText: "",
                           closeOnConfirm: true
                       },
                       function() {
                           // location.reload();
                           var is_linked_view=$("#is_linked_view").val();
                           if(is_linked_view=='N')
                           {
                             var thread_id=$("#thread_id").val();
                             var data="lead_id="+lead_id+"&thread_id="+thread_id;
                             
                             $.ajax({
                                     url: base_url+"lead/lead_update_to_gmail_thread",
                                     data: data,
                                     //data: new FormData($('#frmAccount')[0]),
                                     cache: false,
                                     method: 'POST',
                                     dataType: "html",
                                     //mimeType: "multipart/form-data",
                                     //contentType: false,
                                     //cache: false,
                                     //processData:false,
                                     beforeSend: function( xhr ) { 
                                       //$("#preloader").css('display','block');
                                     },
                                     success: function(data){
                                         result = $.parseJSON(data);
                                         // alert(result.id); return false;
                                         if(result.status='success')
                                         {  
                                          // window.location.reload();
                                          location.reload();  
                                         }
                                     },
                                     complete: function(){
                                       //$("#preloader").css('display','none');
                                     },
                             });
                           }
                           else
                           {
                             location.reload();
                           }
                         
                  });
               } 
               else 
               {

               }
         }
   });                      
});             
            
 $("body").on("click",".new_comment_update_lead_popup",function(e){
   var lid=$(this).attr('data-id');                    
   if(lid>0)
   {
     open_comment_update_lead_view(lid)
   }
   
 });
            
 $(document).on("click",".edit-lead",function(event) {
   event.preventDefault();
   if ($(this).parent().parent().parent().hasClass('edit-mode')) {
      $(this).parent().parent().parent().removeClass('edit-mode');
      $(this).parent().parent().parent().find('.editable-field').prop("disabled", true);
      //$(this).parent().parent().parent().find('.buying-requirements').prop("contenteditable", false);
   }else{
      simpleEditer();
      $(this).parent().parent().parent().addClass('edit-mode');
      $(this).parent().parent().parent().find('.editable-field').prop("disabled", false);
      //$(this).parent().parent().parent().find('.buying-requirements').prop("contenteditable", true);
   }
   
});
$(document).on("click","#save_lead_confirm",function(event) {
      var thisObj=$(this);
      event.preventDefault();
      var box = $('.buying-requirements');
      var lead_requirement = box.html();
      $("#lead_requirement").val(lead_requirement);
     
      var base_url = $("#base_url").val();
      $.ajax({
            url: base_url+"lead/edit_lead_ajax",
            data: new FormData($('#frmLeadEdit')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
              
            },
            success: function(data){
              result = $.parseJSON(data);
              // console.log(result.msg);
              // alert(result.status);                            
              if(result.status=='success')
              {
                  swal({
                        title: "Success!",
                        text: "The lead successfully updated.",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 

                        if (thisObj.parent().parent().hasClass('edit-mode')) 
                        {
                            thisObj.parent().parent().removeClass('edit-mode');
                            thisObj.parent().parent().find('.editable-field').prop("disabled", true);
                            //thisObj.parent().parent().find('.buying-requirements').prop("contenteditable", false);
                            inactiveSimpleEditer();

                        }
                        else
                        {
                            thisObj.parent().parent().addClass('edit-mode');
                            thisObj.parent().parent().find('.editable-field').prop("disabled", false);
                            //thisObj.parent().parent().find('.buying-requirements').prop("contenteditable", true);
                        }
                        $('input.mail-input').each(function( index ) {
                            //console.log( index + ": " + $( this ).text() );
                            thisObj.attr('size', $(this).val().length);
                        });                        
                    });
              }   
          }
      });
});
            
            
              
            
$("body").on("click","#create_lead_confirm",function(e){

      e.preventDefault();
      var ThisObj=$(this);
      var base_url = $("#base_url").val();
      var lead_title_obj=$("#lead_title");
      // var lead_requirement_obj=$("#lead_requirement");
      // var lead_requirement =lead_requirement_obj.val();
      // var lead_requirement = tinyMCE.activeEditor.getContent();
      var box = $('.buying-requirements');
      var lead_requirement = box.html();  
      var lead_requirement_text = box.text();    
      var lead_enq_date_obj=$("#lead_enq_date");
      var lead_follow_up_date_obj=$("#lead_follow_up_date");
      var lead_follow_up_type_obj=$("#lead_follow_up_type");
      var assigned_user_id_obj=$("#assigned_user_id");

      var com_contact_person_obj=$("#com_contact_person");
      var com_email_obj=$("#com_email");
      var com_company_name_obj=$("#com_company_name");
      
      var com_mobile_obj=$("#com_mobile");
      var com_designation_obj=$("#com_designation");

      var com_country_id_obj=$("#com_country_id");
      var com_state_id_obj=$("#com_state_id");
      var com_source_id_obj=$("#com_source_id");
      
      if(lead_title_obj.val()=='')
      {
         swal("Oops!", 'Please enter lead title', "error"); 
         return false;
      }

      $('#lead_requirement').val(lead_requirement);
      if(lead_requirement_text.split(/\s/).join('')=='')
      {
         swal("Oops!", 'Please describe requirements', "error");
         return false;
      }

      if(com_contact_person_obj.val()=='')
      {
         swal("Oops!", 'Please enter contact person', "error");
         return false;
      }

      if(com_email_obj.val()=='')
      {
         swal("Oops!", 'Please enter customer email', "error");
         return false;
      }
      else
      {
         if(!is_email_validate(com_email_obj.val()))
         {
            swal("Oops!", 'Please enter valid email', "error");
            return false;
         }
      }

      // if(com_mobile_obj.val()=='')
      // {
      //    swal("Oops!", 'Please enter mobile', "error");
      //    return false;
      // }

      // if(com_company_name_obj.val()=='')
      // {
      //    swal("Oops!", 'Please enter company name', "error");
      //    return false;
      // }

      if(com_country_id_obj.val()=='')
      {
         swal("Oops!", 'Please select country', "error");
         return false;
      }
      
      if(com_source_id_obj.val()=='')
      {
         swal("Oops!", 'Please select source', "error");
         return false;
      }
      


      if(lead_enq_date_obj.val()=='')
      {
         swal("Oops!", 'Please select enquiry date', "error");
         return false;
      }


      if(lead_follow_up_date_obj.val()=='')
      {
         swal("Oops!", 'Please select follow up date', "error");
         return false;
      }


      if(lead_follow_up_type_obj.val()=='')
      {
         swal("Oops!", 'Please select follow up type', "error");
         return false;
      }

      if(assigned_user_id_obj.val()=='')
      {
         swal("Oops!", 'Please select assigned user', "error");
         return false;
      }     


      // if(com_designation_obj.val()=='')
      // {
      //   com_designation_obj.addClass('error_input');
      //   $("#com_designation_error").html('Please enter designation');
      //   com_designation_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_designation_obj.removeClass('error_input');
      //   $("#com_designation_error").html('');
      // }






      

      // if(com_state_id_obj.val()=='')
      // {
      //   com_state_id_obj.addClass('error_input');
      //   $("#com_state_id_error").html('Please select state');
      //   com_state_id_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_state_id_obj.removeClass('error_input');
      //   $("#com_state_id_error").html('');
      // }
      
      $.ajax({
           url: base_url+"lead/add_lead_ajax",
           data: new FormData($('#frmLeadAdd')[0]),
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData:false,
           beforeSend: function( xhr ) {
             // $('.btn_enabled').addClass("btn_disabled");
             // $(".btn_disabled").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
             // $("#add_to_lead_submit_confirm").attr("disabled",true);
               ThisObj.attr("disabled",true);
            },
            complete: function(){
               //$("#preloader").css('display','none');
               ThisObj.attr("disabled",false);
            },
           success: function(data){
                result = $.parseJSON(data);
                // console.log(result.msg);
                // alert(result.status);
                // console.log(result.msg); return false;
                $('.btn_enabled').removeClass("btn_disabled");
                $(".btn_enabled").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                $("#add_to_lead_submit_confirm").attr("disabled",false);
                if(result.status=='success')
                {
                    swal({
                          title: "Success!",
                          text: "A new lead successfully added.",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () {

                          var thread_id=$("#thread_id").val();
                          var data="lead_id="+result.lead_id+"&thread_id="+thread_id;

                          $.ajax({
                                  url: base_url+"lead/lead_update_to_gmail_thread",
                                  data: data,
                                  //data: new FormData($('#frmAccount')[0]),
                                  cache: false,
                                  method: 'POST',
                                  dataType: "html",
                                  //mimeType: "multipart/form-data",
                                  //contentType: false,
                                  //cache: false,
                                  //processData:false,
                                  beforeSend: function( xhr ) { 
                                    //$("#preloader").css('display','block');
                                  },
                                  success: function(data){
                                      result = $.parseJSON(data);
                                      // alert(result.id); return false;
                                      if(result.status='success')
                                      {  
                                       window.location.reload();  
                                      }
                                  },
                                  complete: function(){
                                    //$("#preloader").css('display','none');
                                  },
                          });                                        

                          //window.location.href=base_url+"lead/add";                        
                      });
                }   
              }
            }); 
      });

});
      


$("body").on("click",".po_upload_view",function(e){
   var lid=$(this).attr('data-lid');
   var l_opp_id=$(this).attr('data-loppid');
   if(l_opp_id!='')
   {      
      fn_get_po_upload_view(lid,l_opp_id)
   }
   else
   {
      fn_get_opp_id_view(lid);
   }   
});

$("body").on("click","#continue_po_upload",function(e){
   var opportunity_id=$("input[name=opportunity_id]:checked").attr('date-oppid');
   var lead_id=$("input[name=opportunity_id]:checked").attr('data-lid');   
   if($("input[name=opportunity_id]:checked").length==0)
   {
      swal('Oops! Please checked a quotation to continue.');
      return false;                        
   }
   if(lead_id!='' && opportunity_id!='')
   {
      $('#QuotationListModal').modal('hide')
      fn_get_po_upload_view(lead_id,opportunity_id);
   }
});

$("body").on("click",".open_reply_box",function(e){
   
   var to_mail = $(this).attr('data-tomail');
   var from_mail = $(this).attr('data-frommail');
   // var lead_id = $(this).attr('data-lid');
   var id = $(this).attr('data-id');
   fn_open_reply_box_view(id,to_mail,from_mail);
});

$("body").on("click",".open_reply_box_restricted_alert",function(e){
   swal("Oops", "Oops! Please connect with gmail to reply.", "error");
   return false;
});

function fn_open_reply_box_view(id,to_mail,from_mail)
{   
   // alert('To:'+to_mail+'  / From:'+from_mail);
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "lead/rander_reply_box_view_popup_ajax",
       type: "POST",
       data: {
           'id': id,
           'to_mail':to_mail,
           'from_mail':from_mail,
       },
       async: true,
       beforeSend: function( xhr ) {
              $.blockUI({ 
              message: 'Please wait...', 
              css: { 
                 padding: '10px', 
                 backgroundColor: '#fff', 
                 border:'0px solid #000',
                 '-webkit-border-radius': '10px', 
                 '-moz-border-radius': '10px', 
                 opacity: .5, 
                 color: '#000',
                 width:'450px',
                 'font-size':'14px'
                }
          });
      },
      complete: function (){
                $.unblockUI();
        },
       success: function(response) {           
           $('#ReplyPopupModal').html(response);
           $(".buyer-scroller").mCustomScrollbar({
             scrollButtons:{enable:true},
             theme:"rounded-dark"
             });
           //////
           $('.select2').select2();
           simpleEditer();
           ////////////////////////
           $('#ReplyPopupModal').modal({
               backdrop: 'static',
               keyboard: false
           });
       },
       error: function() {
           
       }
   });
}

$("body").on("click","#reply_submit_confirm",function(e){   

   var base_URL = $("#base_url").val();  
   var reply_mail_to=$("#reply_mail_to").val();  
   var reply_mail_to_cc=$("#reply_mail_to_cc").val();    
   var box = $('.buying-requirements');
   var email_body = box.html();       
   $('#reply_email_body').val(email_body);      

   if(reply_mail_to=='')
   {
       swal("Oops", "Please specify at least one recipient.", "error");
       return false;
   }

   if(email_body=='')
   {
       swal("Oops", "Please enter mail body", "error");
       return false;
   }
      
   $.ajax({                
       url: base_URL+"lead/gmail_reply",
       data: new FormData($('#reply_mail_frm')[0]),
       cache: false,
       method: 'POST',
       dataType: "html",
       mimeType: "multipart/form-data",
       contentType: false,
       cache: false,
       processData: false,
       beforeSend: function( xhr ) {
           $.blockUI({ 
           message: 'Please wait...', 
           css: { 
              padding: '10px', 
              backgroundColor: '#fff', 
              border:'0px solid #000',
              '-webkit-border-radius': '10px', 
              '-moz-border-radius': '10px', 
              opacity: .5, 
              color: '#000',
              width:'450px',
              'font-size':'14px'
             }
         });
      },
      complete: function (){
            $.unblockUI();
        },            
       success:function(res){ 
          result = $.parseJSON(res);          
          // alert(result.msg);
          if(result.status=='success')
          {
            swal({
                title: "Success!",
                text: "Reply successfully sent",
                type: "success",
                showCancelButton: false,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, send it!",
                closeOnConfirm: true
            }, function () {
               $('#ReplyPopupModal').modal('hide');
            });                    
          }
          else
          {
               swal('Fail!', 'Bulk mails fail to send.', 'error');
          }
                             
      },         
      error: function(response) {}
  }); 
});

function fn_get_opp_id_view(lid)
{
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "lead/rander_quotation_list_view_popup_ajax",
       type: "POST",
       data: {
           'lid': lid
       },
       async: true,
       success: function(response) {
           $('#CommentUpdateLeadModal').modal('hide')
           $('#QuotationListModal').html(response);
           $(".buyer-scroller").mCustomScrollbar({
             scrollButtons:{enable:true},
             theme:"rounded-dark"
             });
           //////
           $('.select2').select2();
           simpleEditer();
           ////////////////////////
           $('#QuotationListModal').modal({
               backdrop: 'static',
               keyboard: false
           });
       },
       error: function() {
           
       }
   });
}
function validate_fileupload(fileName) {
    var allowed_extensions = new Array("jpg", "png", "gif", "pdf", "txt", "doc", "docx");
    var file_extension = fileName.split('.').pop().toLowerCase(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

    for (var i = 0; i <= allowed_extensions.length; i++) {
        if (allowed_extensions[i] == file_extension) {
            return true; // valid file extension
        }
    }

    return false;
}
$("body").on("click", "#po_upload_submit", function(e) {
      e.preventDefault();
      var base_url = $("#base_url").val();
      var lead_id = $("#po_lead_id").val();
      var po_upload_file_obj = $("#po_upload_file");
      var po_upload_cc_to_employee_obj = $("#po_upload_cc_to_employee");
      var po_number_obj = $("#po_number");
      var po_upload_describe_comments_obj = $("#po_upload_describe_comments");

      if (po_number_obj.val() == '') {
      swal('Oops! Please enter PO Number.');
      return false;           
      }

      if (validate_fileupload(po_upload_file_obj.val()) == false) {
      swal('Oops! Please select PO attachment.');
      return false; 
      } 

      if (po_upload_describe_comments_obj.val() == '') {
      swal('Oops! Please enter your comments.');
      return false;             
      }
      if (po_upload_cc_to_employee_obj.val() == null) {

      swal('Oops! Please select CC to Employee.');
      return false;            
      }        

      

      $.ajax({
         url: base_url + "lead/po_upload_post_ajax",
         data: new FormData($('#frmPoUpload')[0]),
         cache: false,
         method: 'POST',
         dataType: "html",
         mimeType: "multipart/form-data",
         contentType: false,
         cache: false,
         processData: false,
         beforeSend: function(xhr) {
             $("#po_upload_submit").attr("disabled", true);
         },
         success: function(data) {
             result = $.parseJSON(data);
             //alert(result.msg);
             if (result.status == 'success') {

                 swal({
                     title: 'PO successfully uploaded.',
                     text: '',
                     type: 'success',
                     showCancelButton: false
                 }, function() {
                     location.reload(); 
                 });
             }
         }
      });
});

function fn_get_po_upload_view(lid,l_opp_id)
{
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "lead/rander_po_upload_view_popup_ajax",
       type: "POST",
       data: {
           'lid': lid,
           'l_opp_id':l_opp_id,
       },
       async: true,
       beforeSend: function( xhr ) {
                    $.blockUI({ 
                    message: 'Please wait...', 
                    css: { 
                       padding: '10px', 
                       backgroundColor: '#fff', 
                       border:'0px solid #000',
                       '-webkit-border-radius': '10px', 
                       '-moz-border-radius': '10px', 
                       opacity: .5, 
                       color: '#000',
                       width:'450px',
                       'font-size':'14px'
                      }
                });
      },
      complete: function (){
                $.unblockUI();
        },
       success: function(response) {
           $('#CommentUpdateLeadModal').modal('hide')
           $('#PoUploadLeadModal').html(response);
           $(".buyer-scroller").mCustomScrollbar({
             scrollButtons:{enable:true},
             theme:"rounded-dark"
             });
           //////
           $('.select2').select2();
           simpleEditer();
           ////////////////////////
           $('#PoUploadLeadModal').modal({
               backdrop: 'static',
               keyboard: false
           });
       },
       error: function() {
           
       }
   });
}


$("body").on("click",".regret_lead_view",function(e){
   var lid=$(this).attr('data-lid');
   var iswon=$(this).attr('data-iswon');

   if(iswon=='Y')
   {
      swal({
            title: "",
            text: "This deal has been won. Do you really want to make the deal lost?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: 'btn-warning',
            cancelButtonText: "No, cancel it!",
            confirmButtonClass: 'btn-warning',
            confirmButtonText: "Yes, do it!",
            closeOnConfirm: true
      },
      function() {
         fn_regret_lead_view(lid);
      });
   }
   else
   {
      fn_regret_lead_view(lid);
   }
   
   
});

$("body").on("click","#back_to_linked_comment_update_lead_popup",function(e)
{
   var lead_id=$(this).attr('data-leadid');   
   //$('#RegretUpdateLeadModal').modal('hide'); 
   open_comment_update_lead_view(lead_id);
});

$("body").on("click","#back_to_linked_comment_update_lead_popup2",function(e)
{
   var lead_id=$(this).attr('data-leadid');   
   $('#PoUploadLeadModal').modal('hide'); 
   open_comment_update_lead_view(lead_id);
});

$("body").on("click","#back_to_linked_comment_update_lead_popup3",function(e)
{
   var lead_id=$(this).attr('data-leadid');   
   $('#QuotationListModal').modal('hide'); 
   open_comment_update_lead_view(lead_id);
});

function fn_regret_lead_view(lid)
{
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "lead/rander_regret_lead_view_popup_ajax",
       type: "POST",
       data: {
           'lid': lid
       },
       async: true,
       success: function(response) {
           // $('#CommentUpdateLeadModal').modal('hide')
           $('#CommentUpdateLeadModal').html(response);
           $(".buyer-scroller").mCustomScrollbar({
             scrollButtons:{enable:true},
             theme:"rounded-dark"
             });
           //////
           $('.select2').select2();
           simpleEditer();
           ////////////////////////
           $('#CommentUpdateLeadModal').modal({
               backdrop: 'static',
               keyboard: false
           });
       },
       error: function() {
           
       }
   });
}
function open_lead_view(lid,filter_by_stage)
{  
      var base_url = $("#base_url").val();
      var is_linked_view=$("#is_linked_view").val();
      $.ajax({
          url: base_url + "lead/rander_lead_view_popup_ajax",
          type: "POST",
          data: {
              'lid': lid,
              'filter_by_stage':filter_by_stage,
              'is_linked_view':is_linked_view,
          },
          async: true,
          success: function(response) {
              $('#LeadViewModal').html(response);
              $(".buyer-scroller").mCustomScrollbar({
                scrollButtons:{enable:true},
                theme:"rounded-dark"
                });
              $('#LeadViewModal').modal({
                  backdrop: 'static',
                  keyboard: false
              });
          },
          error: function() {
              
          }
      });
}
            
function open_quotation_view(id,lead_ids)
{                
    var base_url = $("#base_url").val();
     $.ajax({
          url: base_url + "lead/rander_quotation_view_popup_ajax",
          type: "POST",
          data: {
              'id': id,
              'lead_ids':lead_ids,
          },
          async: true,
          success: function(response) {
              // $('#quotationLeadModal').modal('show')
              $('#QuotationViewModal').html(response);
              $('#QuotationViewModal').modal({
                  backdrop: 'static',
                  keyboard: false
              });
          },
          error: function() {
              
          }
    });
}
   
// UPDATE COMMENT OF LEAD 
function open_comment_update_lead_view(lid)
{
  
   var base_url = $("#base_url").val();
   var thread_id=$("#thread_id").val();
   var is_linked_view=$("#is_linked_view").val();
   $.ajax({
       url: base_url + "lead/rander_comment_update_lead_view_popup_ajax",
       type: "POST",
       data: {
           'lid': lid,
           'thread_id':thread_id,
           'is_linked_view':is_linked_view,
       },
       async: true,
       beforeSend: function( xhr ) {
              $.blockUI({ 
              message: 'Please wait...', 
              css: { 
                 padding: '10px', 
                 backgroundColor: '#fff', 
                 border:'0px solid #000',
                 '-webkit-border-radius': '10px', 
                 '-moz-border-radius': '10px', 
                 opacity: .5, 
                 color: '#000',
                 width:'450px',
                 'font-size':'14px'
                }
          });
      },
      complete: function (){
                $.unblockUI();
        },
       success: function(response) {
           // $('#updateLeadModal').modal('show')
           $('#CommentUpdateLeadModal').html(response);
           $(".buyer-scroller").mCustomScrollbar({
             scrollButtons:{enable:true},
             theme:"rounded-dark"
             });
           //////
           $('.select2').select2();
           simpleEditer();
           ////////////////////////
           $('#CommentUpdateLeadModal').modal({
               backdrop: 'static',
               keyboard: false
           });
       },
       error: function() {
           
       }
   });
}
   // STYLE
   var edit = function(cmd) {
     var val = false;
     switch (cmd) {
       case 'formatBlock': val = 'blockquote'; break;
       case 'createLink': val = prompt('Enter the URL to hyperlink to from the selected text:'); break;
       case 'insertImage': val = prompt('Enter the image URL:'); break;
     }
     document.execCommand(cmd, false, val);
     box.focus();
   }
   function simpleEditer()
   {
      // $(".tools").show();
      var box = $('.buying-requirements');
      box.attr('contentEditable', true);

      

      // EDITING LISTENERS
      $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
         edit($(this).data('cmd'));
      });
      //var contents = box.html();
      // TOGGLE SOURCE
      // var view_source = true;
      // $('a').on('click', function(e) {
      //   e.preventDefault();
      //   if (view_source) {
      //     view_source = false;
      //     var contents = box.html();
      //     box.empty();
      //     box.append('<textarea></textarea>');
      //     box.find('textarea').val(contents);
      //   } else {
      //     view_source = true;
      //     var contents = box.find('textarea').val();
      //     box.empty();
      //     box.html(contents);
      //   }
      // });
   }

   function inactiveSimpleEditer()
   {
      $(".tools").hide();
      var box = $('.buying-requirements');
      box.attr('contentEditable', false);
   }
</script>  
<input type="hidden" name="thread_id" id="thread_id" value="<?php echo $thread_id; ?>">
