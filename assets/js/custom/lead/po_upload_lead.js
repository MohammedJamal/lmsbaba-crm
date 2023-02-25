$(document).ready(function(){
  
  // go_to_next_step(3); 

  	var assets_base_url = $("#assets_base_url").val();
    $( ".input_date" ).datepicker({
          showOn: "both",
          dateFormat: "dd-M-yy",
          buttonImage: assets_base_url+"images/cal-icon.png",
          // changeMonth: true,
          // changeYear: true,
          // yearRange: '-100:+0',
          buttonImageOnly: true,
          buttonText: "Select date",
          // minDate: 0,
    });

    $('#renewal_date').datepicker({
      showOn: "both",
      dateFormat: "dd-M-yy",
      buttonImage: assets_base_url+"images/cal-icon.png",
      buttonImageOnly: true,
      buttonText: "Select date",
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+5',
      // maxDate: 0,
      minDate: 0,
    });

    $('#renewal_follow_up_date').datepicker({
      showOn: "both",
      dateFormat: "dd-M-yy",
      buttonImage: assets_base_url+"images/cal-icon.png",
      buttonImageOnly: true,
      buttonText: "Select date",
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+5',
      // maxDate: 0,
      minDate: 0,
    });



    $("body").on("change","#currency_type",function(e){
      var id=$(this).val();
      var code=$(this).find("option:selected").attr('data-code');
      $("#dv_curr_code").html('<option>'+code+'</option>');
    });
    
    $(".double_digit").keydown(function(e) {
        debugger;
        var charCode = e.keyCode;
        // alert(charCode)
        if (charCode != 8 && charCode != 37 && charCode != 39 && charCode != 46) {
            // alert($(this).val());
            if (!$.isNumeric($(this).val()+e.key)) {
              return false;
            }
        }
        return true;
    });
    // =========================================================
    // CUSTOM FILE UPLOAD
    $('.custom_upload input[type="file"]').change(function(e) { 
        var uphtml = '';


        for (var i = 0; i < e.target.files.length; i++)
        {
           
            uphtml += '<div class="fname_holder">';
            uphtml += '<span>'+e.target.files[i].name+'</span>';
            uphtml += '<a href="lead_attach_file_'+i+'" data-filename="'+e.target.files[i].name+'" class="file_close"><i class="fa fa-times" aria-hidden="true"></i></a>';
            uphtml += '</div>';
            
        }        
        $('.upload-name-holder').css({'display':'block'}).html(uphtml);

    });
    $("body").on("click",".file_close",function(e){
        event.preventDefault();
        var remove_file_name=$(this).attr("data-filename");        
        var storedFiles=[];
        storedFiles=$('#po_upload_file')[0].files;
        var remove_index=0;        
        for(var i=0;i<storedFiles.length;i++) 
        {            
            if(storedFiles[i].name === remove_file_name) 
            {
                remove_index=i; 
                break;
            }            
        }               
        $(this).parent().remove(); 
        const file = document.querySelector('#po_upload_file'); 
            file.value = '';

    });
    // CUSTOM FILE UPLOAD
    // =========================================================
    $("body").on("blur","#deal_value_as_per_purchase_order",function(e){
      var deal_value=parseFloat($("#deal_value").val());
      var deal_value_as_per_purchase_order=parseFloat($("#deal_value_as_per_purchase_order").val());    
      
      if($("#deal_value").val()==0)
      {
          $("#deal_value_display").val((deal_value_as_per_purchase_order)?deal_value_as_per_purchase_order:0);
          var diff=(deal_value_as_per_purchase_order-deal_value_as_per_purchase_order);
          $("#diff_value").text((Math.round(diff))?Math.round(diff):0);
      }
      else
      {
          var diff=(deal_value_as_per_purchase_order-deal_value);
          $("#diff_value").text(Math.round(diff));
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
	// ========================================
  // ----------------------------------------
    
    //same-height
    //pro-form-preview-bt
    /*
    $('.pro-form-preview-bt').click(function( event ) {
      event.preventDefault();
      //addLeadpoModal
      $('#proFormPreviewModal').modal('show');
    });
    //costSheetPreviewModal preview-bt
    $('.preview-bt').click(function( event ) {
      event.preventDefault();
      //addLeadpoModal
      $('#costSheetPreviewModal').modal('show');
    });
    //print_one
    $('.print_one').click(function( event ) {
      event.preventDefault();
      //addLeadpoModal
      $('#proFormPreviewPrint').printThis();
    });
    //costSheetPreviewPrint
    $('.print_two').click(function( event ) {
      event.preventDefault();
      //addLeadpoModal
      $('#costSheetPreviewPrint').printThis();
    });
    //form-steps-1
    var formHeight = [];
    var currentfrom = 0;
    var aniSpeed = 500;
    $('.dash-form-holder .form-steps').each(function( index ) {
      var fHeight = $( this ).outerHeight();
      var fId = $( this ).attr('id');
      //console.log( index + ": " + fHeight );
      formHeight.push({"form_id": fId, "form_height": fHeight})
    });
    
    //submit-bt
    $(document).on("click",".submit-bt",function(event) {
      event.preventDefault();
      // gotonexForm();
    });
    function gotonexForm()
    {
      var nowFormId =  formHeight[currentfrom].form_id;
      currentfrom += 1;
      var nextFormId =  formHeight[currentfrom].form_id;
      var nextFormHeight =  formHeight[currentfrom].form_height;
      $('#'+nowFormId).fadeOut(aniSpeed);
      $('.dash-form-holder').animate({
         height: nextFormHeight+60+"px"
      }, aniSpeed, function() {
         // Animation complete.
         checkActive();
         $('#'+nextFormId).fadeIn(aniSpeed);
      });
    }
    $(document).on("click",".prev-bt",function(event) {
      event.preventDefault();
      //gotopreForm();
    });
    function gotopreForm(){
      var nowFormId =  formHeight[currentfrom].form_id;
      currentfrom -= 1;
      var nextFormId =  formHeight[currentfrom].form_id;
      var nextFormHeight =  formHeight[currentfrom].form_height;
      $('#'+nowFormId).fadeOut(aniSpeed);
      $('.dash-form-holder').animate({
         height: nextFormHeight+60+"px"
      }, aniSpeed, function() {
         // Animation complete.
         checkActive();
         $('#'+nextFormId).fadeIn(aniSpeed);
      });
    }
    function checkActive()
    {
      $('html, body').animate({
         scrollTop: $(".dash-process").offset().top-100
      }, 500);
      $('.dash-process > ol li').removeClass('active');
      $('.dash-process > ol li').removeClass('done');
      //alert(currentfrom)
      for (var i = 0; i < currentfrom+1; i++) {
         var n = i+1;

         $('.dash-process > ol > li:nth-child('+n+')').addClass('active');
         $('.dash-process > ol > li:nth-child('+i+')').addClass('done');
      }
    }
    */
    //add_lead_bt
    if($('input[type=radio][name="payment_type"]:checked').val()=='F')
    {
          $('select#pay_part').attr("disabled", true);
          $('.pay-bt-add').fadeOut('slow')
          $('.payment-details-holder').slideUp('slow', function() {
            // Animation complete.   
          });
          $('#payment_type_f_div').slideDown();
    }
    else if($('input[type=radio][name="payment_type"]:checked').val()=='P')
    {
        $('.pay-bt-add').fadeIn('slow');
        $('.payment-details-holder').slideDown();
        $('#payment_type_f_div').slideUp('slow', function() {
        // Animation complete.
        });
    }  

    $('input[type=radio][name="payment_type"]').change(function() {
      
        if (this.value == 'F') 
        {
          $('select#pay_part').attr("disabled", true);
          $('.pay-bt-add').fadeOut('slow')
          $('.payment-details-holder').slideUp('slow', function() {
            // Animation complete.   
          });
          $('#payment_type_f_div').slideDown();
        }
        else if (this.value == 'P') 
        {
          $('.pay-bt-add').fadeIn('slow');
          $('.payment-details-holder').slideDown();
          $('#payment_type_f_div').slideUp('slow', function() {
          // Animation complete.
          });
        }
    });
    
    /////
    //follow_date
    // $( "#op_date" ).datepicker({
    //   showOn: "both",
    //   buttonImage: assets_base_url+"images/cal-icon-2.png",
    //   buttonImageOnly: true,
    //   buttonText: "Select date"
    // });
    // $( ".date-input" ).datepicker({
    //   showOn: "both",
    //   buttonImage: assets_base_url+"images/cal-icon-2.png",
    //   buttonImageOnly: true,
    //   buttonText: "Select date"
    // });
    //payment_full
    // $( "#payment_full .date-input" ).datepicker({
    //   showOn: "both",
    //   buttonImage: assets_base_url+"images/cal-icon-2.png",
    //   buttonImageOnly: true,
    //   buttonText: "Select date"
    // });
    // $( "#payment_1 .date-input" ).datepicker({
    //   showOn: "both",
    //   buttonImage: assets_base_url+"images/cal-icon-2.png",
    //   buttonImageOnly: true,
    //   buttonText: "Select date"
    // });
    /////////////////////

    // ----------------------------------------
    // ========================================

    $("body").on("change","#f_amount",function(e){
      var value = $(this).val();
      var deal_value_as_per_purchase_order=$("#deal_value_as_per_purchase_order").val();
      var f_amount_balance=(deal_value_as_per_purchase_order-value);
      $("#f_amount_balance").html(f_amount_balance);
      if(f_amount_balance!=0)
      {
        swal({
        title: 'Amount missmatch',
        text: 'PO Amount and input amount missmatch. Do you continue with your amount?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, Continue!'
        }, function(inputValue) {          
          if(inputValue===false){
            $("#f_amount").val('');
            $("#f_amount_balance").html(deal_value_as_per_purchase_order);
          }else{            
          }
        });
      }      
    });  

    $("body").on("change",'input[name="p_amount[]"]',function(e){
        
        var deal_value_as_per_purchase_order=$("#deal_value_as_per_purchase_order").val();
        var value = 0;
        $('input:input[name="p_amount[]"]').each(function(e){
           
           value += ($(this).val())?parseInt($(this).val()):0;
        });
        var p_amount_balance=(deal_value_as_per_purchase_order-value);
        $("#p_amount_balance").html(p_amount_balance);
        // if(p_amount_balance!=0)
        // {
        //   swal({
        //   title: 'Amount missmatch',
        //   text: 'PO Amount and input amount missmatch. Do you continue with your amount?',
        //   type: 'warning',
        //   showCancelButton: true,
        //   confirmButtonColor: '#DD6B55',
        //   confirmButtonText: 'Yes, Continue!'
        //   }, function(inputValue) {          
        //     if(inputValue===false){
              
        //       $('input:input[name="p_amount[]"]').each(function(e){
        //          $(this).val('');
        //       });
        //       $("#p_amount_balance").html(deal_value_as_per_purchase_order);
        //     }else{            
        //     }
        //   });
        // } 

    });  

    $("body").on("click",".del_po_file",function(e){
        var lowp=$(this).attr('data-lowp');
        var base_url = $("#base_url").val();
        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "order/del_po_file_ajax",
                type: "POST",
                data: {
                    'lowp': lowp,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#po_upload_file_div").html('');
                },
                error: function() {
                    swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                    });
                }
            });
        });
    });

    
    $('input[type=checkbox][name="is_po_tds_applicable"]').change(function() {
      
        if($(this).prop("checked") == true){
            $("#po_tds_percentage").attr("readonly",false);
        }else{
            $("#po_tds_percentage").val('');
            $("#po_tds_percentage").attr("readonly",true);
        }
    });

    // -------------------------------------
    

    if($('input[type=radio][name="invoice_type"]:checked').val()=='S')
    {
        $('#invoice_type_s_div').slideDown("fast");
        $('#invoice_type_c_div').slideUp("fast");
    }
    else if($('input[type=radio][name="invoice_type"]:checked').val()=='C')
    {
        $('#invoice_type_s_div').slideUp("fast");
        $('#invoice_type_c_div').slideDown("fast");
    }  

    $('input[type=radio][name="invoice_type"]').change(function() {
      
        
        if (this.value == 'S') 
        {
            $('#invoice_type_s_div').slideDown("fast");
            $('#invoice_type_c_div').slideUp("fast");
            
        }
        else if (this.value == 'C') 
        {
            $('#invoice_type_s_div').slideUp("fast");
            $('#invoice_type_c_div').slideDown("fast");
        }
    });

    $("body").on("click",".del_po_custom_invoice",function(e){
        var lowp=$(this).attr('data-lowp');
        var inv_id=$(this).attr('data-id');
        var base_url = $("#base_url").val();

        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "order/del_po_custom_invoice_ajax",
                type: "POST",
                data: {
                    'lowp': lowp,
                    'inv_id':inv_id,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#po_custom_invoice").val('');
                    $("#po_custom_invoice_div").html('');
                    $("#po_custom_invoice").attr('data-existing','');
                },
                error: function() {
                    swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                    });
                }
            });
        });
    });


    if($('input[type=radio][name="proforma_type"]:checked').val()=='S')
    {
        $('#proforma_type_s_div').slideDown("fast");
        $('#proforma_type_c_div').slideUp("fast");
    }
    else if($('input[type=radio][name="proforma_type"]:checked').val()=='C')
    {
        $('#proforma_type_s_div').slideUp("fast");
        $('#proforma_type_c_div').slideDown("fast");
    }  

    $('input[type=radio][name="proforma_type"]').change(function() {
      
        if (this.value == 'S') 
        {
            $('#proforma_type_s_div').slideDown("fast");
            $('#proforma_type_c_div').slideUp("fast");
            
        }
        else if (this.value == 'C') 
        {
            $('#proforma_type_s_div').slideUp("fast");
            $('#proforma_type_c_div').slideDown("fast");
        }
    });

    $("body").on("click",".del_po_custom_proforma",function(e){
        var lowp=$(this).attr('data-lowp');
        var proforma_id=$(this).attr('data-id');
        var base_url = $("#base_url").val();

        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "order/del_po_custom_proforma_ajax",
                type: "POST",
                data: {
                    'lowp': lowp,
                    'proforma_id':proforma_id,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#po_custom_proforma").val('');
                    $("#po_custom_proforma_div").html('');
                    $("#po_custom_proforma").attr('data-existing','');
                },
                error: function() {
                    swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                    });
                }
            });
        });
    });

    // -------------------------------------
    
});


function checkActiveLi(curr_step='')
{
  $('html, body').animate({
     scrollTop: $(".dash-process").offset().top-100
  }, 500);            
  $('.dash-process > ol li').removeClass('active');
  $('.dash-process > ol li').removeClass('done');
  
  $("#po_li_"+curr_step).addClass('active');
  for(var i=1;i<curr_step;i++)
  {
    $("#po_li_"+i).addClass('active');
    $("#po_li_"+i).addClass('done');
  }
}
function go_to_next_step(curr_step='')
{
  var nextFormHeight =  $('#po_div_'+curr_step).form_height;
  $('.dash-form-holder > div').fadeOut('500');
  $('#po_div_'+curr_step).animate({
     height: nextFormHeight+60+"px"
  }, 500, function() {       
     $(this).fadeIn(500);
     checkActiveLi(curr_step);
  });
}