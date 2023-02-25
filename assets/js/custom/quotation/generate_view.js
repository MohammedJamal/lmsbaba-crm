$(document).ready(function(){
    
    
  	

  $("body").on("focusout",".letter_update",function(e){
      var quotation_id=$("#quotation_id").val();
      var updated_field_name=$(this).attr("id");
      var updated_content=$(this).val();
      fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
  });

  $("body").on("focusout",".is_terms_show_in_letter",function(e){
      var id=$(this).attr("data-id");  
      if($("#is_terms_show_in_letter_"+id).is(":checked")){
          updated_content='Y';          
      } 
      else{
          updated_content='N';         
      }
      fn_update_terms_show_in_letter(id,'is_display_in_quotation',updated_content);
  });

   
  $("input:checkbox[name=is_terms_show_in_letter]").each(function() { 

      if (this.checked) 
      {
        $("#collapse_"+$(this).attr("data-id")).show();
        $("#collapse_"+$(this).attr("data-id")).addClass("in"); 
      }
      else
      {
        $("#collapse_"+$(this).attr("data-id")).hide();
        $("#collapse_"+$(this).attr("data-id")).removeClass("in");
      }   
  });
  

  $("body").on("click",".is_terms_show_in_letter",function(e){
      var id=$(this).attr("data-id");  
      if($("#is_terms_show_in_letter_"+id).is(":checked")){           
          $("#collapse_"+id).show();
          $("#collapse_"+id).addClass("in");      
      } 
      else{          
          $("#collapse_"+id).hide();
          $("#collapse_"+id).removeClass("in");         
      }      
  });

  $("body").on("click","#all_check_terms_show_in_letter",function(e){ 
    $(".is_terms_show_in_letter").prop('checked', true);
    $("input:checkbox[name=is_terms_show_in_letter]:checked").each(function() {
        fn_update_terms_show_in_letter($(this).attr("data-id"),'is_display_in_quotation','Y');

        $("#collapse_"+$(this).attr("data-id")).show();
        $("#collapse_"+$(this).attr("data-id")).addClass("in");   
    });
  });

  $("body").on("click","#all_uncheck_terms_show_in_letter",function(e){ 
    $(".is_terms_show_in_letter").prop('checked', false);
    $("input:checkbox[name=is_terms_show_in_letter]").each(function() {
        fn_update_terms_show_in_letter($(this).attr("data-id"),'is_display_in_quotation','N');
        $("#collapse_"+$(this).attr("data-id")).hide();
        $("#collapse_"+$(this).attr("data-id")).removeClass("in");
    });
  });

  $("body").on("focusout",".terms_update",function(e){
      var id=$(this).attr("data-id");
      var updated_content=$(this).val();
      fn_update_terms_show_in_letter(id,'value',updated_content);
  });


  // $("body").on("focusout",".letter_update2",function(e){
  //     var quotation_id=$("#quotation_id").val();
  //     var updated_field_name=$(this).attr("data-updated_field_name");
  //     var updated_content=$(this).val();
  //     if(updated_field_name=='shipping_terms')
  //     {
  //         var text='Shipping Charge '+updated_content
  //     }
  //     else
  //     {
  //         var text=updated_content
  //     }
  //     $("#"+updated_field_name+"_text").html(text);
  //     fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
  // });

  $("body").on("change",".letter_valid_untill_date_update",function(e){  	
  	var quotation_id=$("#quotation_id").val();
  	var updated_field_name=$(this).attr("id");
  	var updated_content=$(this).val();	
  	fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
  });

  $("body").on("change",".quotation_update",function(e){   
    var quotation_id=$("#quotation_id").val();
    var updated_field_name=$(this).attr("id");
    var updated_content=$(this).val();  
    fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
  });

  $("body").on("focusout",".q_photo_title_update",function(e){
      var id=$(this).attr('data-id');
      var updated_field_name=$(this).attr("data-field");
      var updated_content=$(this).val();
      fn_update_quotation_photo(id,updated_field_name,updated_content);
    });

  

  $("body").on("click","#qutation_send_to_buyer",function(e){


      var base_url=$("#base_url").val();  
      var opp_id=$(this).attr("data-opportunityid");
      var quotation_id=$(this).attr("data-quotationid");

      var data="quotation_id="+quotation_id+"&opp_id="+opp_id;
      $.ajax({
              url: base_url+"opportunity/qutation_send_to_buyer_by_mail_ajax/",
              data: data,
              cache: false,
              method: 'POST',
              dataType: "html",
              beforeSend: function( xhr ) {
                  
              },
              success:function(res){ 
                 result = $.parseJSON(res);
                 //$("#qutation_send_to_buyer_body").html(result.html);
                 //$('#qutation_send_to_buyer_modal').modal({backdrop: 'static',keyboard: false});
				 $('#ReplyPopupModal').html(result.html);
				   $(".buyer-scroller").mCustomScrollbar({
					 scrollButtons:{enable:true},
					 theme:"rounded-dark"
					 });
				   //////
				   $('.select2').select2({tags: true});
				   simpleEditer();
				   //////
				   $('.btn-side .item').each(function( index ) {
						var gItemw = $(this).find('.auto-txt-item').outerWidth();
						// console.log( index + ": " + gItemw );
						$(this).css({'width':gItemw});
					 });
					 $('.btn-side').addClass('owl-carousel owl-theme')
					 $('[data-toggle="tooltip"]').tooltipster();
					 
					 $('#txt-carousel').owlCarousel({
						 margin:10,
						 loop:false,
						 autoWidth:true,
						 nav:true,
						 items:4,
						 dots:false,
						 navText: ['<i class="fa fa-chevron-circle-left" aria-hidden="true"></i>','<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>']
					 })
				   ////////////////////////
				   $('#ReplyPopupModal').modal({
					   backdrop: 'static',
					   keyboard: false
				   });
				   $(".basic-editor").each(function(){
						tinymce.init({
							force_br_newlines : true,
							force_p_newlines : false,
							forced_root_block : '',
							menubar: false,
							statusbar: false,
							toolbar: false,
							setup: function(editor) {
								editor.on('focusout', function(e) {                   
									var updated_field_name=editor.id;
									var updated_content=editor.getContent();
								})
							}
						});
						tinymce.execCommand('mceRemoveEditor', true, this.id); 
						tinymce.execCommand('mceAddEditor', true, this.id); 
					});
					
					$("body").on("click",".letter_update",function(e){
						var quotation_id=$(this).attr('data-quotationid');
						var updated_field_name=$(this).attr("id");
						var updated_content=$(this).val();
						//alert(quotation_id+'/'+updated_field_name+'/'+updated_content);
						fn_update_quotation2(quotation_id,updated_field_name,updated_content);
					});
                 
              },
              complete: function(){
                $('#automated_quotation_popup_modal').modal('hide');
              },
              error: function(response) {
              }
          });     
  });

  // $("#qutation_send_to_buyer_modal").on('hide.bs.modal', function(){           
  //     $('#automated_quotation_popup_modal').modal('show');      
  // });
  /*
  $("body").on("click","#send_to_buyer_confirm",function(e){
      e.preventDefault();
      var ThisObj=$(this);
      var base_url=$("#base_url").val();
      
      $.ajax({
              url: base_url+"opportunity/qutation_send_to_buyer_by_mail_confirm_ajax",
              //data: data,
              data: new FormData($('#send_to_buyer_frm')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function( xhr ) {
                $("#success_msg").html('');
                $("#send_to_buyer_success").hide(); 

                $("#error_msg").html('');
                $("#send_to_buyer_error").hide(); 

                ThisObj.attr("disabled", true);
              },
              complete: function(){
                ThisObj.attr("disabled", false);
              },
              success: function(data){        
                 
                  result = $.parseJSON(data);
                  // alert(result.return); return false;
                  // $("#send_to_buyer_confirm").attr("disabled", false);
                  if(result.status == 'success')
                  {
                      //console.log(result.msg);
                      //$("#success_msg").html(result.msg);
                      //$("#send_to_buyer_success").show(); 
                      // swal({
                      //       title: 'Success',
                      //       text: result.msg,
                      //       type: 'success',
                      //       showCancelButton: false
                      //   }); 
                        swal({
                            title: 'Success',
                            text: result.msg,
                            type: 'success',
                            showCancelButton: false
                        }, function() {
                            window.location.reload();
                        });                         
                        //$('#qutation_send_to_buyer_modal').modal('hide');                   
                  }
                  else
                  {
                      swal({
                            title: 'Fail',
                            text: result.msg,
                            type: 'success',
                            showCancelButton: false
                        });  
                        $('#qutation_send_to_buyer_modal').modal();      
                  }
              }
            }); 
  });
  */

  $("#additional_charges_list_modal").on('hide.bs.modal', function(){
    $('#automated_quotation_popup_modal').modal('show');
  });
  //var currency_type=$("#currency_type_update_<?php echo $opportunity_id; ?>").val();
  var currency_type=$("#currency_type_new option:selected").val();
  if(currency_type!=1)
  {
    $("[name='gst[]']").attr("readonly",true);
    $(".additional_charges_gst").attr("readonly",true);
  }
  else
  {
    $("[name='gst[]']").attr("readonly",false);
    $(".additional_charges_gst").attr("readonly",false);
  }
  $("body").on("click",".add_additional_charges",function(e){
    e.preventDefault();
    //return;
      var base_url=$("#base_url").val();
      var opp_id=$(this).attr('data-oppid');
      var q_id=$(this).attr('data-quotationid');
      //alert(1);
      $.ajax({
          url: base_url+"opportunity/get_additional_charges_checkbox_view_ajax",
          type: "POST",
          data: {"opp_id":opp_id,"q_id": q_id},       
          async:true,     
          beforeSend: function( xhr ) {
            //$('#automated_quotation_popup_modal').modal('hide');
            $('#automated_quotation_popup_modal').css('display','none').addClass('temp-hide');
          },
          complete: function (){
            
          },
          success: function (response) 
          {
              $('#additional_charges_list_body').html(response);  
              $('#additional_charges_list_modal').modal({backdrop: 'static',keyboard: false});
          },
          error: function () 
          {          
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {});
          }
      });  
  });
  $('#additional_charges_list_modal').on('hide.bs.modal', function (e) {
    if ($('#automated_quotation_popup_modal').hasClass('temp-hide')) {
        //alert(1);
        $('#automated_quotation_popup_modal').css('display','block').removeClass('temp-hide');
      }
  })
  // ===============================================
  // VALIDATION SCRIPT
  $("[name='disc[]'],[name='gst[]'],.additional_charges_gst,.additional_charges_discount").keyup(function(e) {
      if($(this).val()>100){
        $(this).val(0);
        return false;
      }
  });
  $(".double_digit").keydown(function(e) {
          debugger;
          var charCode = e.keyCode;
          if (charCode != 8) {
              //alert($(this).val());
              if (!$.isNumeric($(this).val()+e.key)) {
                  return false;
              }
          }
          return true;
  });
  // Namutal number and first letter not zero
  $('.only_natural_number_noFirstZero').keyup(function(e){ 
      var val = $(this).val()
      var reg = /^0/gi;
      if (val.match(reg)){
        $(this).val(val.replace(reg, ""));
        // alert("Please phone number first character bla blaa not 0!");
        // $(this).mask("999 999-9999");
      }
      else{
        if (/\D/g.test(this.value)){
          // Filter non-digits from input value.
          this.value = this.value.replace(/\D/g, '');
        }
      }          
  });
  // Namutal number and first letter not zero  
  // VALIDATION SCRIPT
  // ===============================================  
  
  $("body").on("click","#add_new_row_qp",function(e){
      var base_url=$("#base_url").val();
      var lid=$("#lead_id").val();
      var oppid=$(this).attr("data-oppid");
      var quotationid=$(this).attr("data-quotationid");
      

      $.ajax({
          url: base_url+"opportunity/new_row_added_ajax",
          type: "POST",
          data: {
            'quotation_id':quotationid,
            'opportunity_id':oppid,
          },       
          async:true,     
          beforeSend: function( xhr ) {
            $('#myTable_n').addClass('logo-loader');
          },
          complete: function (){
            $('#myTable_n').removeClass('logo-loader');
          },
          success: function (data) 
          {
              result = $.parseJSON(data);
              $("#product_list_update_" + oppid).html(result.html);
              $(".basic-wysiwyg-editor").each(function(){
                    tinymce.init({
                        force_br_newlines : true,
                        force_p_newlines : false,
                        forced_root_block : '',
                        menubar: false,
                        statusbar: false,
                        toolbar: false,
                        setup: function(editor) {
                            editor.on('focusout', function(e) {                   
                                var quotation_id=$("#quotation_id").val();
                                var updated_field_name=editor.id;
                                var updated_content=editor.getContent();
                                fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
                            })
                        }
                    });
                    tinymce.execCommand('mceRemoveEditor', true, this.id); 
                    tinymce.execCommand('mceAddEditor', true, this.id); 
                });
              // fn_rander_po_pfi_product_view(lowp);
              // alert(result.msg);
              // fn_get_po_upload_view(lid,l_opp_id,step,lowp)
          },
          error: function () 
          {        
            swal({
                    title: 'Something went wrong there!',
                    text: '',
                    type: 'danger',
                    showCancelButton: false
            }, function() {});
          }
      });
  });

  $("body").on("click",".quotation_photo_my_document",function(e){
    var quotation_id=$("#quotation_id").val();
    $('#select_quotation_photo_modal').css({'display':'none'});
    fn_rander_my_document_for_quotation();

  });
  $('#my_document_modal').on('hide.bs.modal', function (e) {
    $('#select_quotation_photo_modal').css({'display':'block'});
    // setTimeout(function(){ 
    //    $('#select_quotation_photo_modal').modal('show'); 
    // }, 700);
  });

  $("body").on("click",".drive_photo_add_to_quotation",function(e){
    selected_id_array=[];
    $.each($("input[name='chk_photo_for_quotation']:checked"), function(){
      selected_id_array.push($(this).val());
    }); 
    if(selected_id_array.length>0)
    {
        var base_url=$("#base_url").val();
        var quotation_id=$("#quotation_id").val();
        var selected_id_to_add=selected_id_array;

        $.ajax({
            url: base_url+"opportunity/add_q_photo_from_myDocument_ajax",
            type: "POST",
            data: {
              'quotation_id':quotation_id,
              'selected_id_to_add':selected_id_to_add,
            },       
            async:true,     
            beforeSend: function( xhr ) {
              $('#my_document_modal').addClass('logo-loader');
            },
            complete: function (){
              $('#my_document_modal').removeClass('logo-loader');
            },
            success: function (data) 
            {
                result = $.parseJSON(data);
                // $('#my_document_modal').modal('hide');
                rander_quotation_wise_photo_ajax(quotation_id);
                $('#automated_quotation_popup_modal').css({'display':'block'});
                $('#select_quotation_photo_modal').modal('toggle'); 
                $('#my_document_modal').modal('toggle'); 
            },
            error: function () 
            {        
              swal({
                      title: 'Something went wrong there!',
                      text: '',
                      type: 'danger',
                      showCancelButton: false
              }, function() {});
            }
        });
    }
    else
    {
      swal('Oops!','Select photo..','error');
    }
  });


  // =====================================
    // -------------------------------------
    

    var oi = 0;
    $('.quotation_photo').change(function(){

      var base_url = $("#base_url").val();  
      var quotation_id=$("#quotation_id").val();
      var form_data = new FormData();   
      var extension=$(this).val().replace(/^.*\./, '');      
      if(extension=='png' || extension=='jpg' || extension=='jpeg' || extension=='gif')
      {
        
          $.ajax({
            url: base_url+"opportunity/upload_quotation_photo_ajax/",
            data: new FormData($('#frmUploadPhotoToQuotation')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
              $("#select_quotation_photo_modal").addClass('logo-loader');
                // $.blockUI({ 
                //     message: 'Please wait...', 
                //     css: { 
                //        padding: '10px', 
                //        backgroundColor: '#fff', 
                //        border:'0px solid #000',
                //        '-webkit-border-radius': '10px', 
                //        '-moz-border-radius': '10px', 
                //        opacity: .5, 
                //        color: '#000',
                //        width:'450px',
                //        'font-size':'14px'
                //       }
                // });
            },
            complete: function (){
                // $.unblockUI();
                $('#select_quotation_photo_modal').removeClass('logo-loader');
            },
            success: function(data)
            {                
                result = $.parseJSON(data);                     
                if(result.status=='success')
                { 
                  rander_quotation_wise_photo_ajax(quotation_id);
                  $('#automated_quotation_popup_modal').css({'display':'block'});
                  $('#select_quotation_photo_modal').modal('toggle');
                    // $('#create_quotation_popup_modal').modal('toggle');
                }
                else
                {
                    swal("Oops!", result.error_msg, "error");                   
                }
                
            }
        });
      }
      else
      {
          swal('Oops!','Please select a image File','error'); 
          return false;
      }
      /*
      const file = this.files[0];
      console.log(file);
      $('#select_quotation_photo_modal').modal('hide');
      if (file){
        let reader = new FileReader();
        reader.onload = function(event){
          console.log(event.target.result);
          var html = `<div class="row mb-15">
                      <div class="col-md-2">
                         <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Photos:</b></h4>
                         <span class="d-block" id="images-over-show-${oi}" data-content="${event.target.result}"><img src="${event.target.result}" class="img-fluid"></span>
                      </div>
                      <div class="col-md-6">
                         <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Title:</b></h4>
                         <input type="text" class="default-input" name="">
                      </div>
                      <div class="col-md-2">
                         <a href="JavaScript:void(0)" class="del_photo_product"><img style="cursor: pointer;" src="https://dev.lmsbaba.com/assets/images/trash.png" alt=""></a>
                      </div>
                   </div>`;
          $('#added_quotation_photo').append(html);
          var bb = $('#images-over-show-'+oi);
          oi++;
        }
        reader.readAsDataURL(file);
      }
      */
    });
    //del_photo_product
    $(document).on("click",".del_photo_product",function(event) {
        event.preventDefault();
        var base_url = $("#base_url").val();
        var id=$(this).attr('data-id');
        var quotation_id=$("#quotation_id").val();

        $.ajax({
          url: base_url+"opportunity/del_q_photo_ajax",
          type: "POST",
          data: {
            'id': id
          },
          async: false,
          beforeSend: function( xhr ) {
            $("#added_quotation_photo").addClass('logo-loader');
          },
          complete: function (){
              $('#added_quotation_photo').removeClass('logo-loader');
          },
          success: function(response) {
              if(response=='success'){
                rander_quotation_wise_photo_ajax(quotation_id);
              }
          },
          error: function() {              
              swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
              }, function() {});
          }
      });
    });
    // -------------------------------------
    // =====================================

    $("body").on("click","#quote_title_edit_icon",function(e){
        $("#quote_title_div_outer").css("display","none");
        $(".quote_title_input").css("display","block");
        $(".quote_title_input").focus();
    });
    $("body").on("focusout",".quote_title_input",function(e){
      var quotation_id=$("#quotation_id").val();
      var updated_field_name=$(this).attr("id");
      var updated_content=$(this).val();
      $("#quote_title_div").text(updated_content);
      $("#quote_title_div_outer").css("display","block");
      $(".quote_title_input").css("display","none");
      fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
      
  });


  $("#rander_quotation_product_rearrange_view_modal").on('hide.bs.modal', function(){
    $('#selected_prod_id').val('');        
    var opportunity_id=($("#oid_tmp").val())?$("#oid_tmp").val():'';
    var quotation_id=($("#qid_tmp").val())?$("#qid_tmp").val():'';  
    // alert(opportunity_id+'/'+quotation_id)      
    if(quotation_id!='' && opportunity_id!='')
    {
        edit_qutation_view_modal(opportunity_id,quotation_id);
    }
});
$("body").on("click",".quotation_product_rearrange",function(e){
var base_url=$("#base_url").val();
// var opportunity_id=$("#opportunity_id").val();
// var quotation_id=$("#quotation_id").val(); 
// var lead_id=$("#lead_id_update").val(); 
var opportunity_id=$(this).attr('data-oid');
var quotation_id=$(this).attr('data-qid');
var lead_id=$(this).attr('data-lid');  
// var data="opportunity_id="+opportunity_id+"&quotation_id="+quotation_id+"&lead_id="+lead_id;     
$.ajax({
    url: base_url + "opportunity/rander_quotation_product_list_for_rearrange",
    type: "POST",
    data: {
        'quotation_id': quotation_id,
        'opportunity_id': opportunity_id,
        'lead_id':lead_id
    },
    async: false,
    beforeSend: function( xhr ) { 
        $('#myTable_n').addClass('logo-loader');
    },
    complete: function(){
        $('#myTable_n').removeClass('logo-loader');
    },
    success: function(data) {
        result = $.parseJSON(data);            
        $("#rander_quotation_product_rearrange_html").html(result.html);
        $('#rander_quotation_product_rearrange_view_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    },
    error: function() {}
}); 
});

  
});


function add_additional_charges(opp_id,q_id)
{  
    //var total=$('input[type="checkbox"]:checked').length;
    // var selected_id_array = $.map($('input[name="select[]"]:checked'), function(c){return c.value; });
    var base_url=$("#base_url").val();
    var selected_id_array=[];
    $.each($("input[name='q_additional_charges']:checked"), function(){
          selected_id_array.push($(this).val());
    });    
    // alert(selected_id_array.length); //return false;
    if(selected_id_array.length>0)
    {           
      var additional_charges = selected_id_array.toString();
      var opportunity_id=opp_id;
      var quotation_id = q_id;
      $.ajax({
            url: base_url+"opportunity/selected_additional_charges_added_ajax",
            type: "POST",
            data: {
              'additional_charges':additional_charges,
              'opportunity_id':opportunity_id,
              'quotation_id': quotation_id,
            },       
            async:true,     
            beforeSend: function( xhr ) {
              //$('#automated_quotation_popup_modal').toggle();
            },
            complete: function (){
              $('#automated_quotation_popup_modal').modal('show');
            },
            success: function (data) 
            {
                result = $.parseJSON(data);
                //alert(result.msg);
                $("#product_list_update_"+opportunity_id).html(result.html);
                $('#additional_charges_list_modal').modal('toggle');
                $(".basic-wysiwyg-editor").each(function(){
                        tinymce.init({
                            force_br_newlines : true,
                            force_p_newlines : false,
                            forced_root_block : '',
                            menubar: false,
                            statusbar: false,
                            toolbar: false,
                            setup: function(editor) {
                                editor.on('focusout', function(e) {                   
                                    var quotation_id=$("#quotation_id").val();
                                    var updated_field_name=editor.id;
                                    var updated_content=editor.getContent();
                                    fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
                                })
                            }
                        });
                        tinymce.execCommand('mceRemoveEditor', true, this.id); 
                        tinymce.execCommand('mceAddEditor', true, this.id); 
                    });
            },
            error: function () 
            {        
              swal({
                      title: 'Something went wrong there!',
                      text: '',
                      type: 'danger',
                      showCancelButton: false
              }, function() {});
            }
      });
    }
    else{
        $('#err_prod').show();
    }
} 
function fn_update_terms_show_in_letter(id,updated_field_name,updated_content)
{ 
  var base_url=$("#base_url").val();  
  var data="id="+id+"&updated_field_name="+updated_field_name+"&updated_content="+encodeURIComponent(updated_content)
  
  //alert(data); return false;
  $.ajax({
          url: base_url+"opportunity/is_terms_show_in_letter_ajax/",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {
              
          },
          success:function(res){ 
             result = $.parseJSON(res);
             if(result.status=='success')
             {
                // swal({
                //     title: 'Quation successfully updated',
                //     text: '',
                //     type: 'success',
                //     showCancelButton: false
                // }, function() {                    
                    
                // });
             }
             
          },
          complete: function(){
          
          },
          error: function(response) {
          }
      });
}

function fn_update_quotation_letter(quotation_id,updated_field_name,updated_content)
{	
	var base_url=$("#base_url").val();
	// alert(base_url+' / '+quotation_id+' / '+updated_field_name+' / '+updated_content);
  // return false;
	if(updated_field_name=='is_product_image_show_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_product_youtube_url_show_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_product_brochure_attached_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_hide_total_net_amount_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_hide_gst_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_show_gst_extra_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_company_brochure_attached_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_quotation_bank_details1_send')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_quotation_bank_details2_send')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_gst_number_show_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_consolidated_gst_in_quotation')
  {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }
  
  var data="quotation_id="+quotation_id+"&updated_field_name="+updated_field_name+"&updated_content="+encodeURIComponent(updated_content)
	// alert(data); return false;
	$.ajax({
          url: base_url+"opportunity/quotation_letter_field_update_ajax/",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {
              
          },
          success:function(res){ 
             result = $.parseJSON(res);
             if(result.status=='success')
             {
                // swal({
                //     title: 'Quation successfully updated',
                //     text: '',
                //     type: 'success',
                //     showCancelButton: false
                // }, function() {                    
                    
                // });
             }
             
          },
          complete: function(){
          
          },
          error: function(response) {
          }
      });
} 

function fn_update_quotation_product(quotation_pid,updated_field_name,updated_content)
{ 
  var base_url=$("#base_url").val();
  
  if(updated_field_name=='is_youtube_video_url_show')
  {
      if($("#"+updated_field_name+'_'+quotation_pid).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='is_brochure_attached')
  {
      if($("#"+updated_field_name+'_'+quotation_pid).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  if(updated_field_name=='image_for_show')
  {
      if($("#"+updated_field_name+'_'+quotation_pid).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
  }

  
  
  var data="quotation_pid="+quotation_pid+"&updated_field_name="+updated_field_name+"&updated_content="+encodeURIComponent(updated_content)
  // alert(data); return false;
  $.ajax({
          url: base_url+"opportunity/quotation_product_update_ajax/",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {
              
          },
          success:function(res){ 
             result = $.parseJSON(res);
             if(result.status=='success')
             {
                // swal({
                //     title: 'Quation successfully updated',
                //     text: '',
                //     type: 'success',
                //     showCancelButton: false
                // }, function() {                    
                    
                // });
             }
             
          },
          complete: function(){
          
          },
          error: function(response) {
          }
      });
} 

function fn_update_quotation_photo(id,updated_field_name,updated_content)
{ 
  var base_url=$("#base_url").val();
  var data="id="+id+"&updated_field_name="+updated_field_name+"&updated_content="+encodeURIComponent(updated_content)
  // alert(data); return false;
  $.ajax({
          url: base_url+"opportunity/quotation_photo_update_ajax/",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {
              
          },
          success:function(res){ 
             result = $.parseJSON(res);
             if(result.status=='success')
             {
                // swal({
                //     title: 'Quation successfully updated',
                //     text: '',
                //     type: 'success',
                //     showCancelButton: false
                // }, function() {                    
                    
                // });
             }
             
          },
          complete: function(){
          
          },
          error: function(response) {
          }
      });
} 


// ===============================
// Tab Functionality : start
// function openTab(evt, cityName) 
// {   
//     var i, tabcontent, tablinks;
//     tabcontent = document.getElementsByClassName("tabcontent");
//     for (i = 0; i < tabcontent.length; i++){
//       tabcontent[i].style.display = "none";
//     }
//     tablinks = document.getElementsByClassName("tablinks");
//     for (i = 0; i < tablinks.length; i++){
//       tablinks[i].className = tablinks[i].className.replace(" active", "");
//     }
//     document.getElementById(cityName).style.display = "block";
//     evt.currentTarget.className += " active";
// }
// Get the element with id="defaultOpen" and click on it
// document.getElementById("defaultOpen").click();

// function open_step1() 
// {
//   document.getElementById("defaultOpen").click();
// }

// function open_step2() 
// {
//   document.getElementById("defaultOpen2").click();
// }

// function open_step3() 
// {
//   document.getElementById("defaultOpen3").click();
// }
// Tab Functionality : end
// ===============================

function get_alert(msg)
{   
  swal(msg);
}




