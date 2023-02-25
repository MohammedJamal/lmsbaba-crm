
<input type="hidden" name="compose_email_body" id="compose_email_body" value="">
<?php //print_r($gmail_data); ?>
<div class="modal-dialog modal-dialog-centered" role="document">
   <div class="modal-content">
     
     <div class="modal-body">
        <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
          <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
             <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
          </svg>
         </a>
        <div class="repply-block">
            <div class="repply-white">
                <div class="repply-top">
                    <div class="repply-action"> 
                      <?php /* ?>
                      <div class="repply-loop">
                        <div class="btn-group left">
                            <!-- <button type="button" class="btn btn-danger">Action</button> -->
                            <a href="javascript:void(0);" class="mail-btn"><img src="<?php echo assets_url(); ?>images/reply_black_icon.png"></a>
                            <button type="button" class="mail-btn dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                              <ul class="ddg">
                                <li>
                                  <a href="javascript:void(0);" class="mLink">
                                    <span><img src="<?php echo assets_url(); ?>images/reply_black.png"></span>
                                    Reply
                                  </a>
                                </li>
                                <li>
                                  <a href="javascript:void(0);" class="mLink">
                                    <span><img src="<?php echo assets_url(); ?>images/reply_all.png"></span>
                                    Reply all
                                  </a>
                                </li>
                                <li>
                                  <a href="javascript:void(0);" class="mLink">
                                    <span><img src="<?php echo assets_url(); ?>images/forward_icon.png"></span>
                                    Forward
                                  </a>
                                </li>
                              </ul> 
                            </div>
                        </div>
                        <input class="email-full" type="email" name="reply_mail_to" id="reply_mail_to" value="<?php echo $to_mail; ?>" readonly="true">
                      </div>
                      <?php */ ?>

                      <div class="repply-loop">
                        <div class="btn-group left text-right mw-65">
                            To
                        </div>
                        <input class="email-full plr-6" type="text" name="mail_to" id=mail_to value="" >
                      </div>


                      <div class="repply-loop">
                        <div class="btn-group left text-right mw-65">
                            <!-- <button type="button" class="btn btn-danger">Action</button> -->
                            Cc
                        </div>
                        <input class="email-full" type="text" name="mail_to_cc" id=mail_to_cc value="">
                      </div>

                      <div class="repply-loop">
                        <div class="btn-group left text-right mw-65">
                            Subject
                        </div>
                        <input class="email-full" type="text" name="mail_subject" id=mail_subject value="">
                      </div>
                    </div> 

                    <div class="repply-body">
                      <div class="buyer-scroller">
                            <div class="buying-requirements" style="min-height: 190px;"></div>                            
                         </div>
                    </div>
                </div>
                <div class="bulk-footer footer-shadow">
                    <ul>                        
                        <li style="position: relative;">
                            <div class="btn-group-n dropup">
                              <button type="button" class="btn custom_blu" id="compose_submit_confirm">Send</button>
                            </div>
                        </li>    
                        <!-- <li>
                            <label class="bulk-attach" for="bulk_attach">
                                <input type="file" name="bulk_attach" id="bulk_attach">
                                <span><i class="fa fa-paperclip" aria-hidden="true"></i></span>
                            </label>
                        </li> -->                    
                        <?php /* ?>
                        <li>
                            <div class="auto-row">
                                <label>Next Follow-up:</label>
                                <input type="text" class="mail-input input_date" value="08-Sep-2020">
                                <span class="cal-icon"><img src="https://lmsbaba.com/dashboard/images/cal-icon.png"></span>
                             </div>
                        </li> 
                        <li>
                            <div class="auto-row">
                                <label>Follow-up Type:</label>
                                <select class="mail-input">
                                   <option>Select</option>
                                </select>
                             </div>
                        </li>
                        <?php */ ?>
                    </ul>
                    <!-- <div class="pull-right"><a href="javascript:void(0);" class="mail-btn" data-status="D" id="del-mail"><img src="<?php echo assets_url(); ?>images/delete_white.png"></a></div> -->
                </div>
            </div>

        </div>
      </div>
   </div>
 </div>
<script src="<?php echo base_url();?>assets/js/bootstrap-tagsinput.js"></script>
<script type="text/javascript">
function is_email_validate(email) 
{
    var filter = /^([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))$/;
    return filter.test(email);
}
$(document).ready(function(){

    
    //ReplyPopupModal
    $('#ComposePopupModal').on('shown.bs.modal', function (e) {
      // do something...
      //buying-requirements
      $('#ComposePopupModal .buying-requirements').focus();
      //alert(1);
    })
    // $('input#reply_mail_to').tagsinput({
    //   maxTags: 3,
    //   trimValue: true,
    //   allowDuplicates: false
    // });


    // $('input#reply_mail_to').on('beforeItemAdd', function(event) {
    //   var tag = event.item; 
    //   if(is_email_validate(tag)==false)
    //   {       
    //     event.cancel = true;
    //   }      
    // });


    $('input#mail_to_cc').tagsinput({
      maxTags: 3,
      trimValue: true,
      allowDuplicates: false
    });


    $('input#mail_to_cc').on('beforeItemAdd', function(event) {
      var tag = event.item; 
      if(is_email_validate(tag)==false)
      {       
        event.cancel = true;
      }      
    });


    $('input.mail-input').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });

    $('input.mail-input:not(.auto-w)').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
        $(this).attr('size', $(this).val().length);
    });


    var base_url_root = $("#base_url_root").val();
    $( ".input_date" ).datepicker({
          showOn: "both",
          dateFormat: "dd-M-yy",
          buttonImage: base_url_root+"images/cal-icon.png",
          // changeMonth: true,
          // changeYear: true,
          // yearRange: '-100:+0',
          buttonImageOnly: true,
          buttonText: "Select date",
          minDate: 0,
    });
});
</script>
<script type="text/javascript">
// bootstrap-tagsinput.js file - add in local



</script>
<!-- <script src="<?php echo base_url();?>assets/js/custom/lead/edit.js"></script> -->

