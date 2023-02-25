<input type="hidden" name="reply_email_body" id="reply_email_body" value="">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">

<div class="modal-body">
<a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
<svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
 <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
</svg>
</a>
<?php 
$to_mail=array();
?>
<div class="repply-block">
<div class="repply-white">
    <div class="repply-top">
        <div class="repply-action">
          <div class="repply-loop">
            <div class="btn-group left text-right mw-65 lh-49">
                To
            </div>
            <div class="mww-65">
				      <?php if(count($user_list)){?>
                <select class="form-control select2" id="to_mail" name="to_mail[]" multiple style="width: 100%">
                    <?php foreach($user_list as $user){ ?>
                    <option value="<?php echo $user->email; ?>"><?php echo $user->email .'( Emp ID: '.$user->id.')'; ?></option>                        
                    <?php } ?>
                </select>
                <?php } ?>
				
                <?php /* ?><input class="email-full" type="email" name="to_mail[]" id="to_mail" value="<?php echo implode(",",$to_mail); ?>"><?php */ ?>
            </div>
          </div>
          <div class="repply-loop">
            <div class="btn-group left text-right mw-65 lh-49">
                Cc
            </div>
            <div class="mww-65">
                <?php if(count($user_list)){?>
                <select class="form-control select2" id="cc_mail" name="cc_mail[]" multiple style="width: 100%">
                    <?php foreach($user_list as $user){ ?>
                    <option value="<?php echo $user->email; ?>"><?php echo $user->email .'( Emp ID: '.$user->id.')'; ?></option>                        
                    <?php } ?>
                </select>
                <?php } ?>
            </div>
          </div>
          <div class="repply-loop">
            <div class="btn-group left text-right mw-65">
                Subject
            </div>
            <input type="text" name="mail_subject" id="mail_subject"  value="<?php //echo $mail_subject; ?>" class="email-full" maxlength="255" />
          </div>
        </div>

        <div class="repply-body">
            <div class="buyer-scroller">
                <div class="buying-requirements default-com" style="min-height: 100px;" id="common_mail_content">Type your message here...</div> 
                 
					
				<?php /* ?>
				<div class="buying-requirements" style="min-height: 100px;"> 
                    <span class="placeholder-text">Type your message here...</span>
                    <div class="default-com" style="width: 100%;height: 26px;"></div>
                </div> 
				<?php */ ?>
            </div>

            <!-- <div class="float-left">
              <label class="up-label" for="lead_attach_file">
                 <div class="attachment_clip"></div><small>Click to Attach Documents</small>
                 <input type="file" name="lead_attach_file[]" id="lead_attach_file" multiple="" style="display: none;">
              </label>                          
           </div> -->

            <!-- <div class="upload-name-holder" style="display: inline-block;">
              <div class="fname_holder" id="attach_file_outer" style="display:none;">
                <span id="attach_file_div"></span>
                <a href="JavaScript:void(0)" data-filename="" class="file_close" id="attach_file_div_close"><i class="fa fa-times" aria-hidden="true"></i></a>
              </div>
            </div> -->
        </div>
		<div class="float-left mail-form-row">
			<label class="up-label" for="lead_attach_file">
			   <div class="attachment_clip"></div><small>Click to Attach Documents</small>
			   <input type="file" name="attach_file[]" id="lead_attach_file" multiple="" style="display: none;">
			</label>              
		 </div>
      <p id="files-area" style="float:left">
        <span id="filesList">
          <span id="files-names"></span>
        </span>
      </p>
        <?php
         /* 
         -----------------------------------
         Quick Reply 
         
         */
         /*
         ?>
         <div class="mail-form-row-full">
			
            <h1 class="quick_view_h1_tag <?php echo ($quick_reply_count==0)?'hide':''; ?>">Send your reply by choosing one</h1>
            <div class="mail-form-row" id="fix-width">
               <div class="add-com">
                  <span class="add-com-action dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Add Pre-define comment" ><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                  <div class="dropdown-menu left-dd">
                     <div class="com-holder-new">
                        <div class="com-holder-fild">
                            <div class="t-txt">
                            <label>Title</label>
                           <input type="text" name="quick_view_title" id="quick_view_title" placeholder="Text here...">
                           <small class="text-danger" id="quick_view_title_error"></small>
                       </div>
                        <div class="t-txt">
                        <label>Description</label>
                            <textarea name="quick_view_desc" id="quick_view_desc" placeholder="Text here..." class="basic-editor--"></textarea>
                            <small class="text-danger" id="quick_view_desc_error"></small>
                          </div>
                        </div>
                        <div class="com-holder-act">
                           <a href="JavaScript:void(0);" class="pop-ac yes add_quick_view_comment" data-target="txt-carousel">
                              <i class="fa fa-check" aria-hidden="true"></i>
                           </a>
                           <a href="JavaScript:void(0);" class="pop-ac no close_quick_view_comment">
                              <i class="fa fa-times" aria-hidden="true"></i>
                           </a>
                        </div>
                     </div>
                 </div>
                 <span class="add-com-count quick_reply_count <?php echo ($quick_reply_count==0)?'hide':''; ?>" >(<?php echo $quick_reply_count; ?>)</span>
               </div>
               <div id="txt-carousel" class="btn-side mh-32 quick_reply_list">
                  <?php 
                  if(count($quick_reply_comments))
                  {
                     foreach($quick_reply_comments AS $comment)
                     {
                        ?>
                        <div class="item">
							<div class="auto-txt-item quick_view_item" data-html="true" data-toggle="tooltip" title="<?php echo strip_tags($comment->comment); ?>" data-comment='<?php echo addslashes($comment->comment); ?>'>
							<?php echo substr($comment->title, 0, 10); 
							echo (strlen($comment->title)>10)?'...':'';
							?>

							<a href="JavaScript:void(0);" data-id="<?php echo $comment->id; ?>" class="del-item del-comm"><i class="fa fa-times" aria-hidden="true"></i></a></div>
						 </div>
                        <?php
                     }                           
                  }
                  ?>
               </div>
            </div>                     
         </div>
         <?php
         */
         /* 
         Quick Reply 
         -----------------------------------
         */
         ?>
    </div>
    <div class="bulk-footer footer-shadow">
      <div class="row">
        <div class="col-md-12">
            <ul style="">                        
              <li style="position: relative;">
                  <div class="btn-group-n dropup">
                    <button type="button" class="btn custom_blu" id="common_mail_send_confirm">Send</button>
                  </div>
              </li> 
              
              <li style="display: <?php echo ($company['brochure_file'])?'block':'none'; ?>; ">
                  <div class="pull-left ff">
                      <label class="check-box-sec">
                          <input type="checkbox" name="is_company_brochure_attached_with_pfi" id="is_company_brochure_attached_with_pfi">
                          <span class="checkmark"></span>
                      </label>
                      <span class="text-label">Attach Company Brochure</span>            
                  </div>
              </li>
            </ul>
        </div>                    
      </div>                      
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

  // ===========================================================================
  // multiple file upload display
  const dt = new DataTransfer(); 
  $("#lead_attach_file").on('change', function(e){    
    for(var i = 0; i < this.files.length; i++){
      let fileBloc = $('<span/>', {class: 'file-block'}),
        fileName = $('<span/>', {class: 'name', text: this.files.item(i).name});
        fileBloc.append('<span class="file-delete text-danger"><span>x</span></span>').append(fileName);
        $("#filesList > #files-names").append(fileBloc);
    };    
    for (let file of this.files) {
      dt.items.add(file);
    }    
    this.files = dt.files;    
    $('span.file-delete').click(function(){
      let name = $(this).next('span.name').text();     
      $(this).parent().remove();
      for(let i = 0; i < dt.items.length; i++){        
        if(name === dt.items[i].getAsFile().name){          
          dt.items.remove(i);
          continue;
        }
      }      
      document.getElementById('lead_attach_file').files = dt.files;
    });
  });
  // multiple file upload display
  // ===========================================================================
  // $('#lead_attach_file').change(function() {
  //   var filename = $(this).val();
  //   var fullPath = filename.split('.')[0];
  //   var ext = filename.split('.')[1];
  //   var filename = fullPath.replace(/^.*[\\\/]/, '');   
  //   $("#attach_file_outer").show();     
  //   $('#attach_file_div').html(filename+'.'+ext);
  // });

  // $("body").on("click","#attach_file_div_close",function(e){
  //   $("#attach_file_outer").hide();  
  //   $("#lead_attach_file").val('');      
  // });

$(document).on("click",".show_gmail_quote_new",function(event) {
event.preventDefault();
var ff = $(this).parent().html();
$(this).hide();
$(this).parent().find('.gmail_quote').addClass('show');
$('#ReplyPopupModal .buying-requirements').focus();
//alert(ff);
// if ($(this).parent().find('.gmail_quote').hasClass("show")) {
//   //alert(1);
//   $(this).parent().find('.gmail_quote').removeClass('show');
// }else{
//   //alert(2);
//   $(this).parent().find('.gmail_quote').addClass('show');
// }

});
//ReplyPopupModal
$('#ReplyPopupModal').on('shown.bs.modal', function (e) {
// do something...
//buying-requirements
$('#ReplyPopupModal .buying-requirements').focus();
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

$('input#to_mail').tagsinput({
maxTags: 3,
trimValue: true,
allowDuplicates: false
});

$('input#reply_mail_to_cc').tagsinput({
maxTags: 3,
trimValue: true,
allowDuplicates: false
});


$('input#reply_mail_to_cc').on('beforeItemAdd', function(event) {
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

$(".select2").select2();         

});
</script>
<script type="text/javascript">
// bootstrap-tagsinput.js file - add in local

$("body").on("click",".buying-requirements",function(e){
	
	if($(this).text()=='Type your message here...'){
		$(this).text('');
	}
});

$("body").on("focusout",".buying-requirements",function(e){
	
	if($(this).text()==''){
		$(this).text('Type your message here...');
	}
});

</script>
<!-- <script src="<?php echo base_url();?>assets/js/custom/lead/edit.js"></script> -->
<style type="text/css">
.select2-container .select2-selection{
min-height: 28px;
line-height: 24px!important;
height: 100%;
}

</style>

