$(function() {
    $("#datepicker").datepicker();
    $("#datepicker2").datepicker();
    $(".datepicker_display_format").datepicker({
        dateFormat: "dd-M-yy"
    });
    $('#next_followup_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5'
    });

});
$(document).ready(function() {
    
    $('#create_quotation_popup_modal').on('shown.bs.modal', function() {
        $(document).off('focusin.modal');
    });
    /*
    $("body").on("click", "#create_qot", function(e) {
        $(this).addClass('no-display');
        $('#create_qot_cancel').removeClass('no-display');
        $('#create_qot_cancel').addClass('display-block');
        $('#create_quotation_div').removeClass('no-display');
    });

    $("body").on("click", "#create_qot_cancel", function(e) {
        $('#create_quotation_div').addClass('no-display');
        $('#create_qot_cancel').removeClass('display-block');
        $('#create_qot_cancel').addClass('no-display');
        $('#create_qot').removeClass('no-display');
        $('#product_list').html("");
    });
    */

    $(".select2").select2();
    $('[data-toggle="tooltip"]').tooltip({
        position: { my: "center bottom", at: "center top-7" }
    });

    // $('.tooltip-popover').popover({
    //    container: 'body',
    //    html:true,
    //    placement:'bottom',
    //  }).on('show.bs.popover', function () {
    //      var html=$("#stage_chnage_html").html();
    //      $(this).attr('data-content',html);
    //  });   

    $("body").on("click", ".change_stage_html_toggle", function(e) {
        var id = $(this).attr('data-id');
        $("#stage_chnage_html_" + id).slideToggle();
    });

    $("body").on("change", ".opportunity_stage_id", function(e) {
        var base_url = $("#base_url").val();
        var lead_id = $("#lead_id").val();
        var stage_id = $(this).val();
        var opportunity_id = $(this).attr('data-opportunityid');
        var data = "stage_id=" + stage_id + "&opportunity_id=" + opportunity_id;
        swal({
            title: 'Do you want to change the current quotation stage?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, do it!'
        }, function() {
            $.ajax({
                url: base_url + "lead/quotation_stage_update_ajax/",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {

                },
                success: function(res) {
                    result = $.parseJSON(res);
                    if (result.status == 'success') {
                        swal({
                            title: 'Quation stage successfully updated',
                            text: '',
                            type: 'success',
                            showCancelButton: false
                        }, function() {
                            window.location.href = base_url + "lead/edit/" + lead_id;
                        });
                    }

                },
                complete: function() {

                },
                error: function(response) {}
            });
        });
    });

    $("body").on("click", ".add_product", function(e) {
        var lead_id = $(this).attr('data-id');
        //$('#prod_add_body').html(lead_id);          
        $('#prod_add_modal').modal({
            backdrop: 'static',
            keyboard: false
        });

        // $.ajax({
        //       url: "<?php echo base_url()?><?php echo $this->session->userdata['logged_in']['lms_url']; ?>/product/getprodlist_ajax",
        //       type: "POST",
        //       data: {'lead_id':lead_id,'temp_prod_id':temp_prod_id},       
        //       async:true,     
        //       success: function (response) 
        //       {
        //           $('#prod_lead_list').html(response);          
        //           $('#prod_lead').modal(); 
        //       },
        //       error: function () 
        //       {
        //           alert('Something went wrong there');
        //       }
        // });
    });

    //$("#fileupload_image").on("change", function() 
    $("body").on("change", "#fileupload_image", function(){
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // Check if File is selected, or no FileReader support

        if (/^image/.test(files[0].type)) { //  Allow only image upload
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function() { // set uploaded image data as background of div
                $("#PreviewPicture").css("background-image", "url(" + this.result + ")");
                //$("#prod_up_pic a").show();
                //$("#prod_up_pic a").attr("href", this.result);
                //$("#PreviewPicture").show();
                $("#PreviewPicture").css("display", "block");
            }
        } else {
            swal('Upload an image');
        }
    });

    $("body").on("change", "#fileupload_pdf", function(){
    //$("#fileupload_pdf").on("change", function() {
        var assets_url=$('#assets_base_url').val()+'images/pdf.webp';
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // Check if File is selected, or no FileReader support

        if (files[0].type == 'application/pdf') {
             //  Allow only image upload
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function() { // set uploaded image data as background of div
                //$("#PreviewPdf").prepend(files[0].name);
                $("#PreviewPdf").css("background-image", "url(" + assets_url + ")");
                $("#PreviewPdf").css("display", "block");
            }
            
        } else {
            swal('Upload a PDF');
        }
    });

    /*
    $(document).on('click', '#add_product_submit', function (e) {
      e.preventDefault();
      var base_url = $("#base_url").val();
      $.ajax({
        url: base_url+"product/add_product_ajax",
        data: new FormData($('#frmProductAdd')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data);
          alert(result.status);        
        }
      });      
    });
    */

    /*$("body").on("input",".search_product_by_keyword",function(e){
      var base_url=$("#base_url").val();
      var lead_id=$("#lead_id").val();
      var search_keyword=$(this).val();
      var opp_id=$("#opp_id").val();
      var temp_prod_id=document.getElementById('selected_prod_id').value;     
      $.ajax({
          url: base_url+"product/getprodlist_ajax",
          type: "POST",
          data: {'lead_id':lead_id,'temp_prod_id':temp_prod_id,'search_keyword':search_keyword,'opportunity_id':opp_id},       
          async:true,     
          success: function (response) 
          {
              $('#prod_lead_list').html(response);          
              $('#prod_lead').modal(); 
          },
          error: function () 
          {
              alert('Something went wrong there');
          }
      });
    });*/

    $("#prod_lead").on('hide.bs.modal', function(){
        $('#selected_prod_id').val('');        
        var opportunity_id=($("#opportunity_id").val())?$("#opportunity_id").val():'';
        var quotation_id=($("#quotation_id").val())?$("#quotation_id").val():'';
        if(quotation_id!='' && opportunity_id!='')
        {
            edit_qutation_view_modal(opportunity_id,quotation_id);
        }
        del_prod();
    });
    $("body").on("click", ".search_product_by_keyword_q", function(e) {
        var base_url = $("#base_url").val();
        var lead_id = $("#lead_id").val();
        var search_keyword = $('.product_name_q').val();
        var search_p_group_q = $('#search_p_group_q').val();
        var search_p_category_q = $('#search_p_category_q').val();
        var searchtype = $(this).attr('data-searchtype');
        var opp_id = ($("#opportunity_id").val())?$("#opportunity_id").val():'';
        var q_id = ($("#quotation_id").val())?$("#quotation_id").val():'';
        //$("#selected_product_div").html('');
        var temp_prod_id = document.getElementById('selected_prod_id').value;
        // alert(search_keyword+'/'+search_p_group_q+'/'+search_p_category_q+'/'+searchtype);
        // return false;
        $.ajax({
            url: base_url + "product/getprodlist_ajax",
            //type: "POST",
            cache: false,
            method: 'POST',
            dataType: "html",
            data: {
                'lead_id': lead_id,
                'temp_prod_id': temp_prod_id,
                'search_keyword': search_keyword,
                'opportunity_id': opp_id,
                'quotation_id': q_id,
                'searchtype':searchtype,
                'search_p_group_q':search_p_group_q,
                'search_p_category_q':search_p_category_q
            },
            //async: true,
            beforeSend: function( xhr ) {
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
                //$.unblockUI();
            },
            success: function(response) {
                $('#prod_lead_list').html(response);
                $('#prod_lead').modal();
                var selected_prod_id=$("#selected_prod_id").val(); 
                if(selected_prod_id=='')
                {            
                    $("#create_new_opportunity").hide();
                } 
                else
                {
                    $("#create_new_opportunity").show();
                }
                //del_prod();             
            },
            error: function() {
                swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
                //alert('Something went wrong there');
            }
        });
    });

    

    $('#prod_lead').on('hidden.bs.modal', function () {
        $("#product_name_q").val('');
        document.getElementById("search_p_group_q").selectedIndex = "0";
        document.getElementById("search_p_category_q").selectedIndex = "0";
            
    });

    $("body").on("click", "#update_search_product_by_keyword", function(e) {
        var base_url = $("#base_url").val();
        var lead_id = $("#lead_id").val();
        var search_keyword = $('.update_search_product_by_keyword').val();
        var opp_id = $("#u_opp_id").val();
        var temp_prod_id = document.getElementById('selected_prod_id_update_' + opp_id).value;
        $.ajax({
            url: base_url + "product/getprodlistupdate_ajax",
            type: "POST",
            data: {
                'lead_id': lead_id,
                'temp_prod_id': temp_prod_id,
                'search_keyword': search_keyword,
                'opportunity_id': opp_id
            },
            async: true,
            success: function(response) {
                $('#prod_lead_list_update').html(response);
                $('#prod_lead_update').modal();
            },
            error: function() {
                swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
                //alert('Something went wrong there');
            }
        });
    });

    $("body").on("input", ".search_vendor_by_keyword", function(e) {
        var base_url = $("#base_url").val();
        var search_keyword = $('.search_vendor_by_keyword').val();
        $.ajax({
            url: base_url + "product/selectVendors",
            type: "POST",
            data: {
                'search_keyword': search_keyword
            },
            async: true,
            success: function(response) {
                $('#select_vendors_for_product_lead_body').html(response);
                $('#lead_select_vendors_modal').modal();
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

    $("body").on("click", "#add-product-for-lead-submit", function(){
        var base_url = $("#base_url").val();
        $.ajax({
            url: base_url + "product/add_ajax",
            data: new FormData($('#lead_add_product_form')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
                /*
                $("#add-product-for-lead-submit").attr("disabled", true);
                var id = 'loader', fill = '#333',
                  size = 20, radius = 3, duration = 1000,
                  maxOpacity = 0.6, minOpacity = 0.15;
                $('<svg id="'+id+'" width="'+(size*3.5)+'" height="'+size+'">' + 
                    '<rect width="'+size+'" height="'+size+'" x="0" y="0" rx="'+radius+'" ry="'+radius+'" fill="'+fill+'" fill-opacity="'+maxOpacity+'">' + 
                        '<animate attributeName="opacity" values="1;'+minOpacity+';1" dur="'+duration+'ms" repeatCount="indefinite"/>' + 
                    '</rect>' + 
                    '<rect width="'+size+'" height="'+size+'" x="'+(size*1.25)+'" y="0" rx="'+radius+'" ry="'+radius+'" fill="'+fill+'" fill-opacity="'+maxOpacity+'">' + 
                        '<animate attributeName="opacity" values="1;'+minOpacity+';1" dur="'+duration+'ms" begin="'+(duration/4)+'ms" repeatCount="indefinite"/>' + 
                    '</rect>' + 
                    '<rect width="'+size+'" height="'+size+'" x="'+(size*2.5)+'" y="0" rx="'+radius+'" ry="'+radius+'" fill="'+fill+'" fill-opacity="'+maxOpacity+'">' + 
                        '<animate attributeName="opacity" values="1;'+minOpacity+';1" dur="'+duration+'ms" begin="'+(duration/2)+'ms" repeatCount="indefinite"/>' + 
                    '</rect>' + 
                '</svg>').appendTo('#lead_add_product_modal');*/
                $('#lead_add_product_modal .modal-body').addClass('logo-loader');
            },
            complete: function (){
                $('#lead_add_product_modal .modal-body').removeClass('logo-loader');
            },
            success: function(data) {
                result = $.parseJSON(data);                
                // $("#add-product-for-lead-submit").attr("disabled", false);
                // $('#loader').remove();
                if (result.status == 'success') {
                    swal({
                        title: "Success!",
                        text: "Product added successfully.",
                        type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    });
                    $('#search_product_by_keyword').trigger("click");
                    $('#lead_add_product_modal').modal('toggle');
                }else{
                    swal({
                        title: "Warning!",
                        text: result.msg,
                        type: "warning",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    });
                }
            }
        });
        /*var isVendor=$('#isSelectVendors').val();
        if(isVendor=='added'){
            var base_url = $("#base_url").val();
            $.ajax({
                url: base_url + "product/add_ajax",
                data: new FormData($('#lead_add_product_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    $('.btn_enabled').addClass("btn_disabled");
                    $(".btn_disabled").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                    $("#add-product-for-lead-submit").attr("disabled", true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    // console.log(result.msg);
                    // alert(result.status);
                    $('.btn_enabled').removeClass("btn_disabled");
                    $(".btn_enabled").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                    $("#add-product-for-lead-submit").attr("disabled", false);
                    if (result.status == 'success') {
                        swal({
                            title: "Success!",
                            text: "Product added successfully.",
                            type: "success",
                            confirmButtonText: "ok",
                            allowOutsideClick: "false"
                        });
                        //$('#search_product_by_keyword').trigger("click");
                        $('#lead_add_product_modal').modal('toggle');
                    }
                }
            });
        }else{
            swal({
                title: "Warning!",
                text: "Please Select Vendors.",
                type: "warning",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
        }*/
    });

    $("body").on("click", ".rm_vdr", function(){
        var isConfirm = confirm("Are you sure?\nYou want to remove this vendor!");
        if (isConfirm) {
                var id=$(this).attr("data-id");
                var new_vendors="";
            var vendors=$('#vendors').val().split('^');
            $.each( vendors, function( index, value ) {
                var res=value.includes('@'+id);
                if(res){
                    $('#rmVdr_'+id).remove();
                }else{
                    new_vendors+=value;
                }
            });
            $('#vendors').val(new_vendors);
        }
    });

    $("body").on("click", "#select-vendor-add-product-submit", function(){
        var pid=$('#vdraddfromProductId').val();
        var vendors="";
        var html="";
        $('.vndr').each(function(){
            var vdr=$(this);
            if(vdr.prop("checked") == true){
                var id=vdr.val();
                var price=$('#'+id+'_price').val();
                var currency=$('#'+id+'_currency').val();
                var unit=$('#'+id+'_unit').val();
                var unit_type=$('#'+id+'_unit_type').val();
                vendors+='@'+id+'_'+price+'_'+currency+'_'+unit+'_'+unit_type+'^';
                if(!pid){
                    var name=vdr.attr("data-val");
                    html+='<div class="col-md-4 col-sm-4 col-lg-4" id="rmVdr_'+id+'">';
                        html+='<div class="form-group">';
                            html+='<label class="label-btn"><i class="fa fa-check tick-icon" aria-hidden="true"></i>'+name+'<i class="fa fa-trash-o rm_vdr" data-id="'+id+'" aria-hidden="true"></i></label>';
                        html+='</div>';
                    html+='</div>'
                }
            }
        });
        if(pid){
            $('#vdraddfromVendors').val(vendors);
            var base_url = $("#base_url").val();
            $.ajax({
                url: base_url + "product/addProductVendors",
                data:  new FormData($('#AddVdrFrom')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    $('.btn_enabled').addClass("btn_disabled");
                    var id = 'loader', fill = '#333',
                      size = 20, radius = 3, duration = 1000,
                      maxOpacity = 0.6, minOpacity = 0.15;
                    $('<svg id="'+id+'" width="'+(size*3.5)+'" height="'+size+'">' + 
                        '<rect width="'+size+'" height="'+size+'" x="0" y="0" rx="'+radius+'" ry="'+radius+'" fill="'+fill+'" fill-opacity="'+maxOpacity+'">' + 
                            '<animate attributeName="opacity" values="1;'+minOpacity+';1" dur="'+duration+'ms" repeatCount="indefinite"/>' + 
                        '</rect>' + 
                        '<rect width="'+size+'" height="'+size+'" x="'+(size*1.25)+'" y="0" rx="'+radius+'" ry="'+radius+'" fill="'+fill+'" fill-opacity="'+maxOpacity+'">' + 
                            '<animate attributeName="opacity" values="1;'+minOpacity+';1" dur="'+duration+'ms" begin="'+(duration/4)+'ms" repeatCount="indefinite"/>' + 
                        '</rect>' + 
                        '<rect width="'+size+'" height="'+size+'" x="'+(size*2.5)+'" y="0" rx="'+radius+'" ry="'+radius+'" fill="'+fill+'" fill-opacity="'+maxOpacity+'">' + 
                            '<animate attributeName="opacity" values="1;'+minOpacity+';1" dur="'+duration+'ms" begin="'+(duration/2)+'ms" repeatCount="indefinite"/>' + 
                        '</rect>' + 
                    '</svg>').appendTo('#lead_add_product_modal');
                    $("#lead_select_vendors_modal").attr("disabled", true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    $('.btn_enabled').removeClass("btn_disabled");
                    $("#select-vendor-add-product-submit").attr("disabled", false);
                    if (result.status == 'success') {
                        swal({
                            title: "Success!",
                            text: "Vendor added successfully.",
                            type: "success",
                            confirmButtonText: "OK",
                            allowOutsideClick: "false"
                        });
                        $('#search_product_by_keyword').trigger('click');
                    }else{
                        swal({
                            title: "Warning!",
                            text: "Vendor not added! Please try again.",
                            type: "warning",
                            confirmButtonText: "OK",
                            allowOutsideClick: "false"
                        });
                    }
                }
            });
        }else{
            $('#vendors').val(vendors);
            $('#vdsList').html(html);
            $('#isSelectVendors').val('added');
            swal({
                title: "Success!",
                text: "Vendor added successfully.",
                type: "success",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
        }
        $('#lead_select_vendors_modal').modal('toggle');
    });

    $("body").on("click", ".vndr", function(e) {
        var obj=$(this);

        if(obj.prop("checked")==true){
            $('#vendor_id_'+obj.val()).removeClass('d-none');
        }else{
            $('#vendor_id_'+obj.val()).removeClass('d-none');
            $('#vendor_id_'+obj.val()).addClass('d-none');
        }
    });

    $("body").on("click", ".open_vendor_add_modal", function(e) {
        var pid = $(this).attr("data-pid");
        var pname = $(this).attr("data-pname");
        var lead_id = $("#lead_id").val();
        //alert("P ID: "+pid+' / '+"Lead ID :"+lead_id+" / pname:"+pname)
        $('#prod_lead').modal('toggle');
        //$('#vendor_tagged_product_add_modal').modal();
        $("#v_product_varient_id").val(pid);
        $(".pname").html('"' + pname + '"');
        $('#vendor_tagged_product_add_modal').modal({
            backdrop: 'static',
            keyboard: false
        }).css('overflow-y', 'auto');
    });

    $("body").on('click', '#vendor_add_submit', function(e) {
        e.preventDefault();
        var oid = $("#opp_id").val();
        var lead_id = $("#lead_id").val();
        var base_url = $("#base_url").val();
        var v_company_name_obj = $("#v_company_name");
        var v_contact_person_obj = $("#v_contact_person");
        var v_designation_obj = $("#v_designation");
        var v_mobile_obj = $("#v_mobile");
        var v_email_obj = $("#v_email");
        var v_address_obj = $("#v_address");

        var v_price_obj = $("#v_price");
        var v_currency_type_obj = $("#v_currency_type");
        var v_unit_obj = $("#v_unit");
        var v_unit_type_obj = $("#v_unit_type");

        if (v_company_name_obj.val() == '') {
            v_company_name_obj.addClass('error_input');
            $("#v_company_name_error").html('Please enter company name');
            v_company_name_obj.focus();
            return false;
        } else {
            v_company_name_obj.removeClass('error_input');
            $("#v_company_name_error").html('');
        }

        if (v_contact_person_obj.val() == '') {
            v_contact_person_obj.addClass('error_input');
            $("#v_contact_person_error").html('Please enter contact person');
            v_contact_person_obj.focus();
            return false;
        } else {
            v_contact_person_obj.removeClass('error_input');
            $("#v_contact_person_error").html('');
        }

        if (v_designation_obj.val() == '') {
            v_designation_obj.addClass('error_input');
            $("#v_designation_error").html('Please enter designation');
            v_designation_obj.focus();
            return false;
        } else {
            v_designation_obj.removeClass('error_input');
            $("#v_designation_error").html('');
        }

        if (v_mobile_obj.val() == '') {
            v_mobile_obj.addClass('error_input');
            $("#v_mobile_error").html('Please enter mobile');
            v_mobile_obj.focus();
            return false;
        } else {
            v_mobile_obj.removeClass('error_input');
            $("#v_mobile_error").html('');
        }

        if (v_email_obj.val() == '') {
            v_email_obj.addClass('error_input');
            $("#v_email_error").html('Please enter email');
            v_email_obj.focus();
            return false;
        } else {
            if (is_email_validate(v_email_obj.val()) == false) {
                v_email_obj.addClass('error_input');
                $("#v_email_error").html("Please enter valid email.");
                v_email_obj.focus();
                return false;
            } else {
                v_email_obj.removeClass('error_input');
                $("#v_email_error").html('');
            }

        }

        if (v_address_obj.val() == '') {
            v_address_obj.addClass('error_input');
            $("#v_address_error").html('Please enter address');
            v_address_obj.focus();
            return false;
        } else {
            v_address_obj.removeClass('error_input');
            $("#v_address_error").html('');
        }

        if (v_price_obj.val() == '') {
            v_price_obj.addClass('error_input');
            $("#v_price_error").html('Please enter price');
            v_price_obj.focus();
            return false;
        } else {
            v_price_obj.removeClass('error_input');
            $("#v_price_error").html('');
        }

        if (v_currency_type_obj.val() == '') {
            v_currency_type_obj.addClass('error_input');
            $("#v_currency_type_error").html('Please select currency');
            v_currency_type_obj.focus();
            return false;
        } else {
            v_currency_type_obj.removeClass('error_input');
            $("#v_currency_type_error").html('');
        }

        if (v_unit_obj.val() == '') {
            v_unit_obj.addClass('error_input');
            $("#v_unit_error").html('Please enter unit');
            v_unit_obj.focus();
            return false;
        } else {
            v_unit_obj.removeClass('error_input');
            $("#v_unit_error").html('');
        }

        if (v_unit_type_obj.val() == '') {
            v_unit_type_obj.addClass('error_input');
            $("#v_unit_type_error").html('Please select unit type');
            v_unit_type_obj.focus();
            return false;
        } else {
            v_unit_type_obj.removeClass('error_input');
            $("#v_unit_type_error").html('');
        }

        $.ajax({
            url: base_url + "product/add_vendor_ajax",
            data: new FormData($('#frmVendorAdd')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.status == 'success') {
                    swal({
                        title: "Success!",
                        text: "A new vendor successfully added and tagged with the product.",
                        type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function() {
                        $('#vendor_tagged_product_add_modal').modal('toggle');
                        $("#v_company_name").val('');
                        $("#v_contact_person").val('');
                        $("#v_designation").val('');
                        $("#v_mobile").val('');
                        $("#v_email").val('');
                        $("#v_address").val('');
                        $("#v_price").val('');
                        $("#v_currency_type").val($("#v_currency_type option:first").val());
                        $("#v_unit").val('');
                        $("#v_unit_type").val($("#v_unit_type option:first").val());
                        if (oid != '') {
                            GetProdLeadListUpdate(lead_id, oid);
                        } else {
                            GetProdLeadList(lead_id);
                        }

                    });
                }
            }
        });
    });

    $("body").on("click", "#edit_customer_view", function(e) {

        var base_url = $("#base_url").val();
        var customer_id = $(this).attr('data-id');

        $.ajax({
            url: base_url + "customer/customer_edit_view_rander_ajax",
            type: "POST",
            data: {
                'customer_id': customer_id
            },
            async: true,
            success: function(response) {
                $('#edit_customer_view_rander').html(response);
                $('#edit_customer_view_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
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

    $("body").on("click", "#update_customer_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        $.ajax({
            url: base_url + "customer/update_customer_ajax",
            data: new FormData($('#frmCustomerEdit')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
                $('#edit_customer_view_modal .modal-body').addClass('logo-loader');

            },
            complete: function (){
                $('#edit_customer_view_modal .modal-body').removeClass('logo-loader');
            }, 
            success: function(data) {
                result = $.parseJSON(data);

                if (result.status == 'success') {
                    $("#company_name_div").html(result.company_name);
                    $("#contact_person_div").html(result.contact_person);
                    $("#email_div").html(result.email);
                    $("#mobile_div").html(result.mobile);
                    $("#country_div").html(result.country);

                    swal({
                        title: 'Customer successfully updated!',
                        text: '',
                        type: 'success',
                        showCancelButton: false
                    }, function() {

                    });
                }
            }
        });
    });

    $("body").on("click", "#show_create_quotation_div", function(e) {
        //$("#create_quotation_div").toggle();

        $('#create_quotation_div').slideToggle('fast', function() {
            if ($(this).is(':visible') == true) {
                $("#show_create_quotation_icon").html('<i class="fas fa-chevron-up"></i>');
            } else {
                $("#show_create_quotation_icon").html('<i class="fas fa-chevron-down"></i>');
            }
        });

    });

    $("body").on("click", "#show_contact_information_div", function(e) {

        $('#contact_information_div').slideToggle('fast', function() {
            if ($(this).is(':visible') == true) {
                $("#show_contact_information_icon").html('<i class="fas fa-chevron-up"></i>');
            } else {
                $("#show_contact_information_icon").html('<i class="fas fa-chevron-down"></i>');
            }
        });

    });
    $("body").on("click", ".get_original_quotation", function(e) {
        var lead_id = $(this).attr("data-id");
        var base_url = $("#base_url").val();
        $.ajax({
            url: base_url + "lead/original_quotation_view_rander_ajax",
            type: "POST",
            data: {
                'lead_id': lead_id
            },
            async: true,
            success: function(response) {
                $('#original_quotation_view_rander').html(response);
                $('#original_quotation_view_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
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


    $("body").on("change", ".calculate_quotation_price", function(e) {
        //var base_url=$("#base_url").val(); 
        var pid = $(this).attr('data-pid');
        var field = $(this).attr('data-field');
        var value = $(this).val();
        calculate_quotation_price(pid, field, value);
        // $.ajax({
        //       url: base_url+"product/update_temp_selected_product_ajax",
        //       type: "POST",
        //       data: {'field':field,'value':value,'pid':pid},       
        //       async:true,     
        //       success: function (data) 
        //       { 
        //           result = $.parseJSON(data);                                    
        //           $("#g_total_"+pid).html(result.total_sale_price);
        //           $("#sub_total").html(result.sub_total);

        //       },
        //       error: function () 
        //       {
        //           alert('Something went wrong there');
        //       }
        // });
    });

    $("body").on('focusin', '.calculate_quotation_price_update', function(){
            // console.log("Saving value " + $(this).val());
            $(this).data('val', $(this).val());
        }).on('change','.calculate_quotation_price_update', function(){
            var prev_val = $(this).data('val');        
            var pid = $(this).attr('data-pid');
            var opportunity_id = $(this).attr('data-opportunityid');
            var quotation_id = $(this).attr('data-quotationid');
            var id = $(this).attr('data-id');
            var field = $(this).attr('data-field');
            var value = $(this).val();
            
            if(value!=""){                
                calculate_quotation_price_update(pid, opportunity_id, id, field, value,quotation_id);
            }
            else{
                $(this).val(prev_val);
                swal('Oops!','Value should not be blank.','error');
                return false;
            }
    });

    $("body").on("change", ".calculate_quotation_price_update---", function(e) {
         
        var pid = $(this).attr('data-pid');
        var opportunity_id = $(this).attr('data-opportunityid');
        var quotation_id = $(this).attr('data-quotationid');
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        var value = $(this).val();
        if(value!=""){
            calculate_quotation_price_update(pid, opportunity_id, id, field, value,quotation_id);
        }
        else{
            var prev_val=$(this).attr("data-prevval");
            $(this).val(prev_val);
            swal('Oops!','Value should not be blank.','error');
            return false;
        }
        
        //alert(opportunity_id+'/'+quotation_id); //return false;     
        // $.ajax({
        //       url: base_url+"opportunity/update_opportunity_product_ajax",
        //       type: "POST",
        //       data: {'field':field,'value':value,'id':id,'pid':pid,'opportunity_id':opportunity_id},       
        //       async:true,     
        //       success: function (data) 
        //       { 
        //           result = $.parseJSON(data);
        //           //alert(result.sub_total);
        //           $("#g_total_update_"+pid).html(result.total_sale_price);
        //           $("#sub_total_update_"+opportunity_id).html(result.sub_total);
        //           //$("#product_list_update_"+result.opportunity_id).html(result.html)
        //       },
        //       error: function () 
        //       {
        //           alert('Something went wrong there');
        //       }
        // });
    });

    $("body").on("click", ".del_prod_update", function(e) {

        var base_url = $("#base_url").val();
        var id = $(this).attr('data-id');
        var opportunity_id = $(this).attr('data-opportunityid');

        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "opportunity/del_prod_update_ajax",
                type: "POST",
                data: {
                    'id': id,
                    'opportunity_id': opportunity_id
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#product_list_update_" + opportunity_id).html(result.html);
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

    $("body").on("change", ".calculate_quotation_additional_charges_price_update", function(e) {
        // var base_url=$("#base_url").val();         
        var opportunity_id = $(this).attr('data-opportunityid');
        var quotation_id = $(this).attr('data-quotationid');
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        var value = $(this).val();
        calculate_quotation_additional_charges_price_update(opportunity_id, id, field, value,quotation_id);
        //alert('field:'+field+'/value:'+value+'/id:'+id); return false;     
        // $.ajax({
        //       url: base_url+"opportunity/update_opportunity_additional_charges_ajax",
        //       type: "POST",
        //       data: {'field':field,'value':value,'id':id,'opportunity_id':opportunity_id},       
        //       async:true,     
        //       success: function (data) 
        //       { 
        //           result = $.parseJSON(data);
        //           //alert(result.sub_total);
        //            $("#row_total_additional_price_update_"+id).html(result.total_sale_price);
        //            $("#sub_total_update_"+opportunity_id).html(result.sub_total);
        //           //$("#product_list_update_"+result.opportunity_id).html(result.html)
        //       },
        //       error: function () 
        //       {
        //           alert('Something went wrong there');
        //       }
        // });
    });

    $("body").on("click", ".del_additional_charges_update", function(e) {

        var base_url = $("#base_url").val();
        var id = $(this).attr('data-id');
        var opportunity_id = $(this).attr('data-opportunityid');
        var quotation_id = $(this).attr('data-quotationid');;
        // alert(opportunity_id+'/'+quotation_id); //return false;
        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "opportunity/del_additional_charges_update_ajax",
                type: "POST",
                data: {
                    'id': id,
                    'opportunity_id': opportunity_id,
                    'quotation_id': quotation_id,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);

                    $("#product_list_update_" + opportunity_id).html(result.html);
                    $("#additional_charges_total_sale_price_" + id).html(result.total_sale_price);
            
                    //$("#g_total_update_" + pid).html(result.total_sale_price);
                    //$("#sub_total_update_" + opportunity_id).html(result.sub_total);
                    
                    $("#total_deal_value").html(result.total_deal_value);
                    $("#total_price").html(result.total_price);
                    $("#total_discount").html(result.total_discount);
                    $("#total_tax").html(result.total_tax);
                    $("#grand_total_round_off").html(result.grand_total_round_off);
                    $("#number_to_word_final_amount").html(result.number_to_word_final_amount);

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

    $("body").on("click", ".is_copy_confirm", function(e) {
        var target_url = $(this).attr('data-url');
        var existingname = $(this).attr('data-existingname');
        var base_url = $("#base_url").val();
        //alert(target_url);return false;
        swal({
                title: 'Do you want to copy the "' + existingname + '"? Please confirm!',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, do it!',
                closeOnConfirm: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    window.location.href = target_url;
                }

            });
    });

    $("body").on("change", "#currency_type_new", function(e) {
        var base_url = $("#base_url").val();
        var c_t = $(this).val();
        var c_t_code = $('#currency_type_new option:selected').text();              
        // var opportunity_id=$("#opportunity_id").val();
        // var quotation_id=$("#quotation_id").val();   
        var opportunity_id = $(this).attr('data-opportunityid');
        var quotation_id = $(this).attr('data-quotationid');

        // alert(c_t+'/'+opportunity_id+'/'+quotation_id); 
        //return false;
        if(c_t!='' && opportunity_id!='' && quotation_id!='')
        {
           $.ajax({
                url: base_url + "opportunity/update_opportunity_currency_ajax",
                type: "POST",
                data: {
                    'currency_type': c_t,
                    'opportunity_id': opportunity_id,
                    'quotation_id': quotation_id,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    // $(".currency_code_div").html(c_t_code);
                    // alert(result.html)
                    $("#product_list_update_" + opportunity_id).html(result.html);
                    $("#total_deal_value").text(result.total_deal_value);
                    $("#total_price").text(result.total_price);
                    $("#total_discount").text(result.total_discount);
                    $("#total_tax").text(result.total_tax);
                    $("#grand_total_round_off").text(result.grand_total_round_off);
                    $("#number_to_word_final_amount").text(result.number_to_word_final_amount);
                    $(".currency_code_div").html(result.currency_code);
                    
                    if(result.terms_html){
                        $("#terms_condition_outer_div").show();                        
                    }
                    else{
                        $("#terms_condition_outer_div").hide();
                    }
                    $("#accordion").html(result.terms_html);
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
            // if (c_t != 1) {
            //     input_gst_disable_by_name('gst[]');
            // } else {
            //     input_gst_enable_by_name('gst[]')
            // } 
        }
        else
        {
            swal('Oops','Please select a currency','error');
        }
        
    });

    $("body").on("change", "[name=currency_type_update]", function(e) {
        var c_t = $(this).val();
        if (c_t != 1) {
            input_gst_update_disable_by_name('gst[]');
            input_additional_charges_gst_disable_by_name();
        } else {
            input_gst_update_enable_by_name('gst[]')
            input_additional_charges_gst_enable_by_name();
        }
    });

    $("[name='disc[]'],[name='gst[]'],.additional_charges_gst").keyup(function(e) {
        if ($(this).val() > 100) {
            $(this).val(0);
            return false;
        }
    });

    $("body").on("click", "#lead_assigne_change", function(e) {
        var lead_id = $(this).attr("data-id");
        var base_url = $("#base_url").val();
        var data = "lead_id=" + lead_id;
        $.ajax({
            url: base_url + "lead/change_assigned_to_ajax",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function(xhr) {

            },
            success: function(res) {
                result = $.parseJSON(res);
                $("#lead_assigne_to_body").html(result.html);
                $('#lead_assigne_to_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

            },
            complete: function() {

            },
            error: function(response) {}
        });

    });

    $("body").on("click", "#lead_assigne_change_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        $.ajax({
            url: base_url + "lead/update_change_assigned_to_ajax",
            data: new FormData($('#lead_assigne_change_frm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {

            },
            success: function(data) {
                result = $.parseJSON(data);
                //alert(result.return);          
                if (result.status == 'success') {
                    swal({
                        title: 'The lead has been assigned to ' + result.assigned_to_user_name,
                        text: '',
                        type: 'success',
                        showCancelButton: false
                    }, function() {
                        //$("#assigned_to_user_name_span").html(result.assigned_to_user_name);                  
                        location.reload();
                    });
                }
            }
        });
    });

    $("body").on("click", "#mail_to_client", function(e) {
        if ($("input[type='checkbox'][name='mail_to_client']:checked").length != 0) {
            // swal({
            //     title: '',
            //     text: 'By checking, A mail will be sent to the respective client..',
            //     type: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#06D755',
            //     confirmButtonText: 'Yes, Do it!',
            //     cancelButtonText: "No, cancel it!",
            //     closeOnConfirm: true,
            //     closeOnCancel: true
            // }, function(isConfirm) {
            //     if (isConfirm == false) {
            //         $("#mail_to_client").attr('checked', false); 
            //         $("#update_lead_mail_to_client_mail_subject_div").hide();
            //         $("#mail_to_client_mail_subject").val('');
            //         $("#mail_to_client_mail_subject").attr("type","hidden");                    
            //         $("#client_not_interested").attr("disabled",false);
            //     } else {
            //         $("#mail_to_client").attr('checked', true);
            //         var lead_id=$("#lead_id").val();
            //         var m_subject='Enquiry # '+lead_id+' - Query/Update from your A/C Manager';
            //         $("#update_lead_mail_to_client_mail_subject_div").show();
            //         $("#mail_to_client_mail_subject").val(m_subject);
            //         $("#mail_to_client_mail_subject").attr("type","text");
            //         $("#client_not_interested").attr("disabled",true);
            //     }

            // });

            $("#mail_to_client").attr('checked', true);
            var lead_id=$("#lead_id").val();
            var m_subject='Enquiry # '+lead_id+' - Query/Update from your A/C Manager';
            $("#update_lead_mail_to_client_mail_subject_div").show();
            $("#mail_to_client_mail_subject").val(m_subject);
            $("#mail_to_client_mail_subject").attr("type","text");
            $("#client_not_interested").attr("disabled",true);

        }
    });

    $("body").on("click", "#mail_to_client", function(e) {
        if ($("input[type='checkbox'][name='mail_to_client']:checked").length == 0) {
            $("#update_lead_mail_to_client_mail_subject_div").hide(); 
            $("#mail_to_client_mail_subject").val('');
            $("#mail_to_client_mail_subject").attr("type","hidden");
            $("#client_not_interested").attr("disabled",false);
        } 
    });

    // $("body").on("click","#update_lead_mail_subject_update_confirm",function(e){
    //     var mail_subject=$("#update_lead_mail_subject").val();
    //     if(mail_subject=='')
    //     {
    //         swal('Oops! Please enter Mail subject.');
    //         //return false;
    //         //$("#update_lead_mail_subject_error").html("Mail subject should not be blank.");
    //     }
    //     else
    //     {
    //         $("#update_lead_mail_subject_error").html("");
    //         $("#mail_subject_text_div").html(mail_subject);
    //         $("#mail_to_client_mail_subject").val(mail_subject);
    //         $('#update_lead_mail_subject_change_modal').modal("hide");
    //         $("#update_lead_mail_to_client_mail_subject_div").show();
    //     }
        
    // });

    // $("body").on("click",".change_mail_subject_popup_for_lead_update_client_mail",function(e){        
    //     fn_open_lead_update_client_mail_subject_popup();    
    // });

    $("body").on("click", ".get_detail_modal", function(e) {
        var id = $(this).attr('data-id');
        $("#lead_company_details").modal({
            backdrop: 'static',
            keyboard: false,
            callback: fn_rander_company_details(id)
        });
    });


    // $("body").on("click","#regret_this_lead_mail_subject_update_confirm",function(e){
    //     var mail_subject=$("#regret_this_lead_mail_subject_edit").val();
    //     if(mail_subject=='')
    //     {
    //         $("#regret_this_lead_mail_subject_edit_error").html("Mail subject should not be blank.");
    //     }
    //     else
    //     {
    //         $("#regret_this_lead_mail_subject_edit_error").html("");
    //         $("#mail_subject_regret_this_lead_text_div").html(mail_subject);
    //         $("#regret_this_lead_mail_subject").val(mail_subject);
    //         $('#regret_this_lead_mail_subject_change_modal').modal("hide");
    //         $("#update_lead_regret_this_lead_mail_subject_div").show();
    //     }
        
    // });

    // $("body").on("click",".change_mail_subject_popup_for_regret_this_lead_mail",function(e){
    //     fn_open_lead_update_regret_this_lead_mail_subject_popup();    
    // });

    $("body").on("click", "#client_not_interested", function(e) {
        if ($("input[type='checkbox'][name='client_not_interested']:checked").length != 0) {
            $("#lead_edit_confirm").attr("onclick", "general_update2()");
            $("#next_follow_star").html('');
            $("#next_followup_date").attr("disabled", true);

            $("#mail_to_client").attr("disabled",true);
        } else {
            $("#lead_edit_confirm").attr("onclick", "general_update()");
            $("#next_follow_star").html('*');
            $("#next_followup_date").attr("disabled", false);

            $("#mail_to_client").attr("disabled",false);
        }
    });

    $("body").on("click", "#client_not_interested", function(e) {
        if ($("input[type='checkbox'][name='client_not_interested']:checked").length != 0) {
            // $("#lead_regret_reason_list_modal").modal({
            //     backdrop: 'static',
            //     keyboard: false,
            //     callback: fn_rander_regret_reason()
            // });
            var lead_id=$("#lead_id").val();
            var m_subject='Enquiry # '+lead_id+' - Query/Update from your A/C Manager';

            $("#update_lead_regret_this_lead_mail_subject_div").show();            
            $("#regret_this_lead_mail_subject").val(m_subject);
            $("#regret_this_lead_mail_subject").attr("type","text");

        } else {
            $("#regret_reason_text").html('');
            $("#lead_regret_reason").val('');
            //$("#lead_regret_reason_id").val('');
            $('#lead_regret_reason_id').prop('selectedIndex',0);
            

            $("#update_lead_regret_this_lead_mail_subject_div").hide();            
            $("#regret_this_lead_mail_subject").val('');
            $("#regret_this_lead_mail_subject").attr("type","text");
            //$("#mail_subject_regret_this_lead_text_div").html('');
            //$("#regret_this_lead_mail_subject").val(''); 
        }
    });

    $("body").on("click","#client_not_interested_close",function(e){

        document.getElementById("client_not_interested").checked = false;
        $("#regret_reason_text").html('');
        $("#lead_regret_reason").val('');
        //$("#lead_regret_reason_id").val('');
        $('#lead_regret_reason_id').prop('selectedIndex',0);

        $("#lead_edit_confirm").attr("onclick", "general_update()");
        $("#next_follow_star").html('*');
        $("#next_followup_date").attr("disabled", false);

        
        $("#update_lead_regret_this_lead_mail_subject_div").hide();
        //$("#mail_subject_regret_this_lead_text_div").html('');
        $("#regret_this_lead_mail_subject").val(''); 
        $("#regret_this_lead_mail_subject").attr("type","text");
    });

    $("body").on("click","#reason_select_cinfirm",function(e){
        var regret_reason = document.getElementsByName('regret_reason');
        var regValue = false;
        var mail_subject=$("#update_lead_regret_this_lead_mail_subject").val();
        for(var i=0; i<regret_reason.length;i++){
            if(regret_reason[i].checked == true){
                regValue = true;    
            }
        }
        if(!regValue){
            swal('Oops! Please select any regret reasons.');
            return false;
        }
        
        // if(mail_subject=='')
        // {
        //     swal('Oops! Please enter Mail subject.');
        //     return false;
        // }

        if(regValue==true)
        {
            $("#update_lead_regret_this_lead_mail_subject_div").show();
            //$("#mail_subject_regret_this_lead_text_div").html(mail_subject);
            $("#regret_this_lead_mail_subject").val(mail_subject);
            $("#regret_this_lead_mail_subject").attr("type","text");
            $("#lead_regret_reason_list_modal").modal("hide");
        }
    });

    $("body").on("change","#lead_regret_reason_id",function(e){
        var reason_text=$("#lead_regret_reason_id option:selected").text();
        $("#lead_regret_reason").val(reason_text);
    });
    $("body").on("click", ".po_upload_view", function(e) {
        var lead_opp_id = $(this).attr("data-loid");
        var lead_id = $(this).attr("data-lid");
        var opp_title = $(this).attr("data-title");
        var deal_value = $(this).attr("data-dealvalue");
        
        if(lead_id!='' && lead_opp_id!='')
        {
            fn_get_po_upload_view(lead_id,lead_opp_id);
        }
        

        // $("#po_upload_modal").modal({
        //     backdrop: 'static',
        //     keyboard: false,
        //     callback: fn_set_post_data(lead_opp_id, lead_id, opp_title,deal_value)
        // });
    });

    /*
    $("body").on("click", "#po_upload_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        var lead_id = $("#po_lead_id").val();
        var po_upload_file_obj = $("#po_upload_file");
        var po_upload_cc_to_employee_obj = $("#po_upload_cc_to_employee");
        var po_number_obj = $("#po_number");
        var po_upload_describe_comments_obj = $("#po_upload_describe_comments");

        if (validate_fileupload(po_upload_file_obj.val()) == false) {
            po_upload_file_obj.addClass('error_input');
            $("#po_upload_file_error").html('Please select PO attachment');
            po_upload_file_obj.focus();
            return false;
        } else {
            po_upload_file_obj.removeClass('error_input');
            $("#po_upload_file_error").html('');
        }

        // if (po_upload_cc_to_employee_obj.val() == null) {
        //     po_upload_cc_to_employee_obj.addClass('error_input');
        //     $("#po_upload_cc_to_employee_error").html('Please select CC to Employee');
        //     po_upload_cc_to_employee_obj.focus();
        //     return false;
        // } else {
        //     po_upload_cc_to_employee_obj.removeClass('error_input');
        //     $("#po_upload_cc_to_employee_error").html('');
        // }

        if (po_number_obj.val() == '') {
            po_number_obj.addClass('error_input');
            $("#po_number_error").html('Please enter PO Number');
            po_number_obj.focus();
            return false;
        } else {
            po_number_obj.removeClass('error_input');
            $("#po_number_error").html('');
        }

        if (po_upload_describe_comments_obj.val() == '') {
            po_upload_describe_comments_obj.addClass('error_input');
            $("#po_upload_describe_comments_error").html('Please enter your comments');
            po_upload_describe_comments_obj.focus();
            return false;
        } else {
            po_upload_describe_comments_obj.removeClass('error_input');
            $("#po_upload_describe_comments_error").html('');
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
                        window.location.href = base_url + "lead/edit/" + lead_id;
                    });
                }
            }
        });
    });
    */

    $("body").on("click", "#po_upload_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        var lead_id = $("#po_lead_id").val();
        var po_upload_file_obj = $("#po_upload_file");
        var po_upload_cc_to_employee_obj = $("#po_upload_cc_to_employee");
        var po_number_obj = $("#po_number");
        var po_upload_describe_comments_obj = $("#po_upload_describe_comments");

        var deal_value_as_per_purchase_order_obj = $("#deal_value_as_per_purchase_order");

        var renewal_date_obj = $("#renewal_date");
        var renewal_follow_up_date_obj = $("#renewal_follow_up_date");
        var renewal_requirement_obj = $("#renewal_requirement");

        var po_tds_percentage_obj=$("#po_tds_percentage");
        // if (po_number_obj.val() == '') {
        // swal('Oops! Please enter PO Number.');
        // return false;           
        // }

        // alert(deal_value_as_per_purchase_order_obj.val())
        if (deal_value_as_per_purchase_order_obj.val() == '' || deal_value_as_per_purchase_order_obj.val() == '0') {
        swal('Oops! Please enter Purchase Order amount.');
        return false;           
        }

        if(po_upload_file_obj.val())
        {
            if (validate_fileupload(po_upload_file_obj.val()) == false) {
                swal('Oops! The PO attachment should be pdf, doc or docx..');
                return false; 
            }  
        }


        if (po_upload_describe_comments_obj.val() == '') {
            swal('Oops! Please enter your comments.');
            return false;             
        }

        // if (po_upload_cc_to_employee_obj.val() == null) {
        //     swal('Oops! Please select CC to Employee.');
        //     return false;            
        // }        

        if(document.getElementById("is_po_tds_applicable").checked==true)
        {
            if (po_tds_percentage_obj.val() == '') {
                swal('Oops! TDS Deduction Applicable is checked and %age not filled.');
                return false;             
            }
            else
            {
                if(po_tds_percentage_obj.val()>10)
                {
                    swal('Oops! TDS deduction should not be greater than 10%.');
                    return false;  
                }
            }
        }


        if(document.getElementById("is_renewal_available").checked==true)
        {          
          // ==================
          var d = new Date(renewal_follow_up_date_obj.val());
          var dateString = [
            d.getFullYear(),
            ('0' + (d.getMonth() + 1)).slice(-2),
            ('0' + d.getDate()).slice(-2)
          ].join('-');
          var follow_date = dateString.substring(1);
          
          var d = new Date(renewal_date_obj.val());
          var dateString = [
            d.getFullYear(),
            ('0' + (d.getMonth() + 1)).slice(-2),
            ('0' + d.getDate()).slice(-2)
          ].join('-');
          var renewal_date = dateString.substring(1);

          // ==================
          if (renewal_date_obj.val() == '') {
            swal('Oops! Please select renewal date.');
            return false;           
          }

          if (renewal_follow_up_date_obj.val() == '') {
            swal('Oops! Please select renewal next follow up date.');
            return false;           
          }

          if (renewal_requirement_obj.val() == '') {
            swal('Oops! Please enter renewal/ AMC Type .');
            return false;           
          }

          // if(renewal_date<follow_date)
          // {
          //   swal('Oops! Renewal date should be equal or greather than Next Follow Up Date.');
          //   return false;             
          // }
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
                 $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
             },
             complete: function (){
              $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
            },
             success: function(data) {
                 result = $.parseJSON(data);
                 //alert(result.msg);
                 if (result.status == 'success') {

                     swal({
                         title: 'Success',
                         text: 'PO successfully uploaded.',
                         type: 'success',
                         showCancelButton: false
                     }, function() {
                        var redirect_uri_str=result.redirect_uri_str;
                        document.location.href = base_url+'order/po_register/?'+redirect_uri_str;
                      
                        // window.location.reload();
                     });
                 }
             }
          });
    })

    $('#qutation_send_to_buyer_modal').on('hidden.bs.modal', function () {
        var is_extermal_quote=$("#is_extermal_quote").val();
		var is_resend=$("#is_resend").val();
        if(is_extermal_quote=='N' && is_resend=='N'){
            $('#automated_quotation_popup_modal').modal('show');    
        }
        else{
            window.location.reload();
        }        
    });
	
	$('#ReplyPopupModal').on('hidden.bs.modal', function () {
        var is_extermal_quote=$("#is_extermal_quote").val();
		var is_resend=$("#is_resend").val();
        if(is_extermal_quote=='N' && is_resend=='N'){
            // $('#automated_quotation_popup_modal').modal('show'); 
            window.location.reload();   
        }
        else{
            window.location.reload();
        }        
    });
	
	

    $("body").on("click",".send_quotation_to_buyer_modal",function(e){
        var oppid=$(this).attr('data-oppid');
        var qid=$(this).attr('data-qid');
        //alert(oppid+ ' / ' +qid);
        qutation_send_to_buyer_modal(oppid,qid);
    });

    $("body").on("click","#send_to_buyer_confirm",function(e){
        e.preventDefault();
		var thisObj=$(this);
        var base_url=$("#base_url").val();
		var box = $('.buying-requirements');
        var email_body = box.html();       
        $('#reply_email_body').val(email_body);

        $.ajax({
                url: base_url+"opportunity/qutation_send_to_buyer_by_mail_confirm_ajax",
                //data: data,
                //data: new FormData($('#send_to_buyer_frm')[0]),
				data: new FormData($('#cust_reply_mail_frm')[0]),
                cache: false,
				method: 'POST',
				dataType: "html",
				mimeType: "multipart/form-data",
				contentType: false,
				cache: false,
				processData: false,
                beforeSend: function( xhr ) {
                  //$("#success_msg").html('');
                  //$("#send_to_buyer_success").hide(); 
  
                  //$("#error_msg").html('');
                  //$("#send_to_buyer_error").hide(); 
				  
				  thisObj.attr("disabled", true);
                  $('#ReplyPopupModal .modal-body').addClass('logo-loader');
  
                  // $("#send_to_buyer_confirm").attr("disabled", true);
                  //$('#qutation_send_to_buyer_modal .modal-body').addClass('logo-loader');
                },
                complete: function (){
                    //$('#qutation_send_to_buyer_modal .modal-body').removeClass('logo-loader');
					thisObj.attr("disabled", false);
                    $('#ReplyPopupModal .modal-body').removeClass('logo-loader');
                },
                success: function(data){       
                   
                    
                    result = $.parseJSON(data);             
                    // $("#send_to_buyer_confirm").attr("disabled", false);
                    //alert(result.status);
                    if(result.status == 'success')
                    {
                        //console.log(result.msg);
                        //$("#success_msg").html(result.msg);
                        //$("#send_to_buyer_success").show(); 
                        // swal({
                        //       title: result.msg,
                        //       text: '',
                        //       type: 'success',
                        //       showCancelButton: false
                        //   });  
                        //   $('#qutation_send_to_buyer_modal').modal();   
                        //alert('result.msg'); 
                        // window.top.showAlert(result.msg, 'Success', 'success');
                        swal({
                            title: 'Success!',
                            text: result.msg,
                            type: 'success',
                            showCancelButton: false
                        }, function() {
                            $('#ReplyPopupModal').modal('hide');
                        });
                    }
                    else
                    {
                        // swal('Error!', result.msg, 'warning');
                          window.top.showAlert(result.msg, 'Warning', 'warning');
                            
                    }
                }
            });  
      });
    /*
    $("body").on("focusout",".letter_update",function(e){
        var quotation_id=$(this).attr('data-quotationid');
        var updated_field_name=$(this).attr("id");
        var updated_content=$(this).val();
        fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
    });
    */

    // ================================================
    // PRE-DEFAULT COMMENTS FOR UPDATE LEADS
    $(document).on('click', '.btn-dropdown', function (e) {
        e.preventDefault();
        resetUpdate()
        $('.lead-dropdown .dropdown-menu.left').slideToggle();
        load_pre_fedine_update_lead_comments();
    });
    $(document).on('click', '#comment_txt_submit', function (e) {
        e.preventDefault();
        
        if (!$("input[name='pre_define_comment']").is(':checked')) {
            swal('Oops! Please select a comment.');
            //alert('Please select a comments');
        }
        else {
            var id=$("input:radio[name=pre_define_comment]:checked").attr('data-id');
            //var getDrec = $(this).parent().parent().find('.comments_details').html();
            var getDrec = $("#comment_"+id).val();
            $('.lead-dropdown .dropdown-menu.left').slideUp();
            //$('.mce-content-body#tinymce').html(getDrec);
            var myContent = tinymce.get("general_description").setContent(getDrec);
            //alert('Details: '+getDrec);
        }
    });
    $(document).on('click', '#comment_pop_close', function (e) {
        e.preventDefault()
        resetUpdate();
        $('.lead-dropdown .dropdown-menu.left').slideUp();
    });
    $("body").on("click",".comments_checkbox",function(e){
              
        if (!$(this).hasClass("active")) {
            //alert(1)
            resetUpdate()
            var id=$(this).attr("data-id");
            ///////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////
            $('#lead_scroller ul > li').find('.active').removeClass('active');
            var id=$(this).attr("data-id");
            $(this).addClass('active');
            $(".comments_details").slideUp('fast');
            $("#comments_details_"+id).stop(true, true).slideDown('slow');
           
        }
    });
    function resetUpdate(){
        //$('#lead_scroller ul > li').find('.edit-comm').html('edit').show();
        //$('#lead_scroller ul > li').find('textarea').prop("disabled", true);
        //$('#lead_scroller ul > li').find('.comments_update').hide();
    }
    //edit-comm
    
    $("body").on("click","#comment_txt_close",function(e){
        e.preventDefault();
        var id=$(this).attr("data-id"); 
               
        //$("form#comment_frm").reset();
        //document.getElementById("comment_frm_"+id).reset();
        $("#edit_comm_"+id).show();
        $("#comment_"+id).prop("disabled", true);
        $("#comments_update_"+id).slideUp();
    })

    $("body").on("click",".del-comm___x",function(e){
        var base_URL = $("#base_url").val();
        var click_btn=$(this);
        var id=$(this).attr("data-id");
          
        
        swal({
            title: 'Warning',
            text: 'Are you sure? Do you want to delete the record?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
          }, 
          function(isConfirm) {
                if (isConfirm) {
                    var data = "id="+id;  
                    $.ajax({
                        url: base_URL+"lead/delete_lead_update_pre_define_comment",
                        data: data,
                        cache: false,
                        method: 'POST',
                        dataType: "html",
                        beforeSend: function( xhr ) {},
                        success:function(res){ 
                            result = $.parseJSON(res);
                            if(result.status=='success')
                            {
                                swal('Success!', result.msg, 'success');
                                // $formComments.animate({
                                //     height: $formComments.outerHeight()-29
                                // });
                                load_pre_fedine_update_lead_comments();                  
                            }
                        },
                        complete: function(){},
                        error: function(response) {
                        //alert('Error'+response.table);
                        }
                    })
              }
              return false;
          });


        
        
    });
    $("body").on("click","#edit_pre_define_comment_confirm",function(e){
        var base_URL = $("#base_url").val();
        var click_btn_obj=$(this);
        var id=$("#pre_define_comment_id").val();
        var title=$("#edit_pre_define_title").val();
        var desc=$("#edit_pre_define_description").val();
        if(title=='')
        {
            $("#edit_pre_define_title_error").html("( Title should not be blank.)");
            $("#edit_pre_define_title").focus();
            return false;
        }
        else
        {
            $("#edit_pre_define_title_error").html("");
        }

        if(desc=='')
        {
            $("#edit_pre_define_description_error").html("( Description should not be blank.)");
            $("#edit_pre_define_description").focus();
            return false;
        }
        else
        {
            $("#edit_pre_define_description_error").html("");
        }
        var data = "id="+id+"&description="+desc+"&title="+title;
        
        $.ajax({
            url: base_URL+"lead/update_lead_update_pre_define_comment",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {
                click_btn_obj.attr("disabled",true);
            },
            success:function(res){ 
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                    swal('Success!', result.msg, 'success');  
                    modalAnimate($formEdit, $formComments); 
                    load_pre_fedine_update_lead_comments();                                     
                }
            },
            complete: function(){
                click_btn_obj.attr("disabled",false);
            },
            error: function(response) {
            //alert('Error'+response.table);
            }
        })
    });
    $("body").on("click","#add_pre_define_update_lead_comment",function(e){
        e.preventDefault();
        
        var click_btn_obj=$(this);
        var base_URL = $("#base_url").val();
        var user_id=$("#user_id").val();   
        var pre_define_title_obj=$("#pre_define_title");
        // var pre_define_description_obj=$("#pre_define_description");
        var pre_define_description_obj=tinyMCE.activeEditor.getContent();
        if(pre_define_title_obj.val()=='')
        {
            $("#pre_define_title_error").html("( Please enter title )");
            pre_define_title_obj.focus();
            return false;
        }
        else
        {
            $("#pre_define_title_error").html("");
        }

        if(pre_define_description_obj.val()=='')
        {
            $("#pre_define_description_error").html("( Please enter description )");
            pre_define_description_obj.focus();
            return false;
        }
        else
        {
            $("#pre_define_description_error").html("");
        }           
        var data = "user_id="+user_id+"&title="+pre_define_title_obj.val()+"&description="+pre_define_description_obj.val();
        $.ajax({
            url: base_URL+"lead/add_lead_update_pre_define_comment",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {
                click_btn_obj.attr("disabled",true);
            },
            success:function(res){ 
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                    swal('Success!', result.msg, 'success');
                    $("#pre_define_title").val('');
                    $("#pre_define_description").val('');
                    modalAnimate($formAdd, $formComments, 29); 
                    load_pre_fedine_update_lead_comments();
                    
                }
            },
            complete: function(){
                click_btn_obj.attr("disabled",false);
            },
            error: function(response) {
            //alert('Error'+response.table);
            }
        })
    });
    //////////////////////////////////
    //modalAnimate($formRegister, $formLogin); 
    var $formComments = $('.lead-dropdown .user_comment');
    var $formAdd = $('.lead-dropdown .add-user_comment');
    var $formEdit = $('.lead-dropdown .edit-user_comment');
    var $divForms = $('.lead-dropdown .dropdown-menu.left');
    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;
    $(document).on('click', '.lead-dropdown a.add-new-com-btn', function (e) {
        e.preventDefault();
        modalAnimate($formComments, $formAdd);
         
    });
    $("body").on("click",".edit-comm",function(e){
        e.preventDefault();
        //alert(3333)
        var dataid = $(this).attr('data-id');
        var title = $(this).parent().parent().find('.cname').html();
        var details = $(this).parent().parent().parent().find('textarea').val();
        //alert(title+'\n'+details);
        $formEdit.find('#pre_define_comment_id').val(dataid);
        $formEdit.find('#edit_pre_define_title').val(title);
        $formEdit.find('#edit_pre_define_description').val(details);
        modalAnimate($formComments, $formEdit);
    })
    $(document).on('click', '.add-user_comment a.go-back', function (e) {
        e.preventDefault();
        modalAnimate($formAdd, $formComments); 
        load_pre_fedine_update_lead_comments();
    });
    $(document).on('click', '.edit-user_comment a.go-back', function (e) {
        e.preventDefault();
        modalAnimate($formEdit, $formComments); 
        load_pre_fedine_update_lead_comments();
    });
    
    function modalAnimate ($oldForm, $newForm, extraHeight) 
    {
        extraHeight = extraHeight || 0;
        var $oldH = $oldForm.outerHeight();
        var $newH = $newForm.outerHeight()+(40+extraHeight);
        $divForms.css("height",$oldH);
        $oldForm.fadeToggle($modalAnimateTime, function(){
            $divForms.animate({height: $newH}, $modalAnimateTime, function(){
                $newForm.fadeToggle($modalAnimateTime);
            });
        });
    }
    // PRE-DEFAULT COMMENTS FOR UPDATE LEADS
    // =================================================


    
    // =========================================================
    // RANDER HISTORY FOR LEAD UPDATE COMMENTS FOR UPDATE LEADS(MAIL TRAIL)

    $("body").on("click","#mail_trail_check",function(e){
        var obj=$(this);

        if(obj.prop("checked")==true)
        {
            var base_URL = $("#base_url").val();
            var lead_id=$("#lead_id").val();      
            var data = "lead_id="+lead_id;
            //alert(data); return false;
            $.ajax({
                url: base_URL+"lead/rander_mail_trail_for_mail_to_client",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
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
                success:function(res){ 
                    result = $.parseJSON(res);
                    //alert(result.history_count)
                    if(result.history_count>0)
                    {
                        var mailhtml = result.html;
                        //var myContent = tinymce.get("general_description").setContent(mailhtml);
                        $('#view_and_select_mail_trail_body').html(mailhtml);                   
                        $('#view_and_select_mail_trail_modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).css('overflow-y', 'auto');
                    }
                    else
                    {
                        swal('Oops! No mail trail found for this lead.');
                    }
                    
                },
                complete: function(){
                    $.unblockUI();
                },
                error: function(response) {
                //alert('Error'+response.table);
                }
            })
        }
        else
        {
            $("#lead_comments_for_mail_trail").val('');
            $('#view_and_select_mail_trail_body').html('');                   
            $('#view_and_select_mail_trail_modal').modal('hide');
        }
    });

    $("body").on("click","#view_and_select_mail_trail_modal_close",function(e){       
        $("#mail_trail_check").attr("checked",false);
        $("#view_and_select_mail_trail_modal").modal('hide');

    });

    $("body").on("click","#trail_mail_comment_selected_confirm",function(e){
        var cboxes = document.getElementsByName('lead_comment[]');
        var len = cboxes.length;
        var lead_comments=[];
        var lead_comments_str='';
        for (var i=0; i<len; i++) 
        {
            //alert(i + (cboxes[i].checked?' checked ':' unchecked ') + cboxes[i].value);
            if(cboxes[i].checked)
            {
                lead_comments.push(cboxes[i].value);
            }
        }
        lead_comments_str=lead_comments.toString();
        if(lead_comments_str=='')
        {
            swal({
                  title: 'Oops',
                  text: 'Please select any comment for mail trail.',
                  type: 'warning',
                  showCancelButton: false
              }); 
            $("#lead_comments_for_mail_trail").val('');
            return false;
        }
        else
        {
            $("#lead_comments_for_mail_trail").val(lead_comments_str);
            $("#view_and_select_mail_trail_modal").modal('hide');
        }
    });
    // $("body").on("click","#add_mail_trail",function(e){

    //     var base_URL = $("#base_url").val();
    //     var lead_id=$("#lead_id").val();      
    //     var data = "lead_id="+lead_id;
    //     //alert(data); return false;
    //     $.ajax({
    //         url: base_URL+"lead/rander_history_for_lead_update",
    //         data: data,
    //         cache: false,
    //         method: 'POST',
    //         dataType: "html",
    //         beforeSend: function( xhr ) {},
    //         success:function(res){ 
    //             result = $.parseJSON(res);
    //             //alert(result.history_count)
    //             if(result.history_count>0)
    //             {
    //                 var mailhtml = result.html;
    //                 var myContent = tinymce.get("general_description").setContent(mailhtml);
    //             }
    //             else
    //             {
    //                 swal('Oops! No mail trail found for this lead.');
    //             }
                
    //         },
    //         complete: function(){},
    //         error: function(response) {
    //         //alert('Error'+response.table);
    //         }
    //     })
        
    // });

    

    // $("body").on("click","#view_and_select_mail_trail",function(e){

    //     var base_URL = $("#base_url").val();
    //     var lead_id=$("#lead_id").val();      
    //     var data = "lead_id="+lead_id;
    //     //alert(data); return false;
    //     $.ajax({
    //         url: base_URL+"lead/view_and_select_history_for_lead_update",
    //         data: data,
    //         cache: false,
    //         method: 'POST',
    //         dataType: "html",
    //         beforeSend: function( xhr ) {},
    //         success:function(res){ 
    //             result = $.parseJSON(res);
    //             //alert(result.history_count)
    //             if(result.history_count>0)
    //             {
    //                 var mailhtml = result.html;
    //                 //var myContent = tinymce.get("general_description").setContent(mailhtml);
    //                 $('#view_and_select_mail_trail_body').html(mailhtml);                   
    //                 $('#view_and_select_mail_trail_modal').modal({
    //                     backdrop: 'static',
    //                     keyboard: false
    //                 }).css('overflow-y', 'auto');
    //             }
    //             else
    //             {
    //                 swal('Oops! No mail trail found for this lead.');
    //             }
                
    //         },
    //         complete: function(){},
    //         error: function(response) {
    //         //alert('Error'+response.table);
    //         }
    //     })
        
    // });

    // RANDER HISTORY FOR LEAD UPDATE COMMENTS FOR UPDATE LEADS
    // =========================================================

    // =========================================================
    // CUSTOM FILE UPLOAD
    $('.custom_upload input[type="file"]').change(function(e) { 
        //alert(e.target.files.length);
        if (e.target.files.length > 3) {
            alert("You can select max 3 files");
            return;
        }
        var uphtml = '';


        for (var i = 0; i < e.target.files.length; i++)
        {
            //alert(e.target.files[i]);
            uphtml += '<div class="fname_holder">';
            //uphtml += '<input type="hidden" name="lead_attach_file_send[]" id="lead_attach_file_send'+i+'" value="'+e.target.files[i].name+'">';
            uphtml += '<span>'+e.target.files[i].name+'</span>';
            uphtml += '<a href="lead_attach_file_'+i+'" data-filename="'+e.target.files[i].name+'" class="file_close"><i class="fa fa-times" aria-hidden="true"></i></a>';
            uphtml += '</div>';
            //alert(e.target.files.item(i).name); // alternatively
        }
        //return
        //$('input#lead_attach_file').val('');
        $('.upload-name-holder').css({'display':'inline-block'}).html(uphtml);

    });

    $("body").on("click",".file_close",function(e){
        event.preventDefault();
        var remove_file_name=$(this).attr("data-filename");
        //alert(remove_val)
        // console.log(remove_file_name);
        var storedFiles=[];
        storedFiles=$('#lead_attach_file')[0].files;
        var remove_index=0;        
        for(var i=0;i<storedFiles.length;i++) 
        {
            //console.log(storedFiles[i].name);
            if(storedFiles[i].name === remove_file_name) 
            {
                remove_index=i;                
                //storedFiles.splice(i,1);                
                break;
            }            
        }
        var existing_val=$("#lead_attach_file_removed").val();
        var uphtml='';
        if(existing_val)
        {
            new_val=existing_val+','+remove_index;
        }
        else
        {
            new_val=remove_index;
        }
        $("#lead_attach_file_removed").val(new_val);
        //$('#lead_attach_file')[0].files.splice(1,1);
        //console.log($('#lead_attach_file')[0].files[1]);        
        $(this).parent().remove();       

    });
    // $("body").on("click",".upload-name-holder .fname_holder a",function(e){
    //     event.preventDefault();     

    //     $(this).parent().remove();
    //     checkUploadCount();
    // });
    function checkUploadCount(){
        var getT = $('.upload-name-holder .fname_holder').size();
        if(getT == 0){
            $('.upload-name-holder').css({'display':'none'}).html('');
            //$('input#lead_attach_file').val('');
        }
    }
    // =========================================================

    // ===========================================================
    // CREATE QUOTATION
    $("body").on('click',"#create_quotation",function(e){        
        $('#create_quotation_popup_modal').modal({
            backdrop: 'static',
            keyboard: false
        }).css('overflow-y', 'auto');
    });

    $("body").on('click',"#generate_automated_quotation",function(e){

        var lead_id=$("#lead_id").val(); 
        $('#create_quotation_popup_modal').modal('toggle');
        GetProdLeadList(lead_id);
        // $.ajax({
        //     url: base_url + "lead/add_ajax",
        //     data: new FormData($('#searchCompanyFrm')[0]),
        //     cache: false,
        //     method: 'POST',
        //     dataType: "html",
        //     mimeType: "multipart/form-data",
        //     contentType: false,
        //     cache: false,
        //     processData: false,
        //     beforeSend: function(xhr) {
        //         $.blockUI({ 
        //             message: 'Please wait...', 
        //             css: { 
        //                padding: '10px', 
        //                backgroundColor: '#fff', 
        //                border:'0px solid #000',
        //                '-webkit-border-radius': '10px', 
        //                '-moz-border-radius': '10px', 
        //                opacity: .5, 
        //                color: '#000',
        //                width:'450px',
        //                'font-size':'14px'
        //               }
        //         });
        //     },
        //     complete: function (){
        //         $.unblockUI();
        //     },
        //     success: function(response) {
        //     //result = $.parseJSON(data);
        //     $('#rander_add_new_lead_view_html').html(response);
        //     $('#rander_add_new_lead_view_modal').modal({backdrop: 'static',keyboard: false});
            
        //         $('#create_quotation_popup_modal').modal({
        //             backdrop: 'static',
        //             keyboard: false
        //         }).css('overflow-y', 'auto');
        //     }
        // });        
    });

    $("body").on('click',"#add_product_from_edit_quotation",function(e){
        var lead_id=$("#lead_id").val(); 
        var oppid=$(this).attr("data-oppid");
        $('#automated_quotation_popup_modal').modal('hide');
        GetProdLeadList(lead_id,oppid);            
    });

    $("body").on('click',"#generate_automated_quotation_step2",function(e){
        
        var base_url = $("#base_url").val(); 
        // var prod_id_array = $.map($('input[name="select[]"]:checked'), function(c) {
        //     return c.value;
        // });   
        //if (prod_id_array.length > 0) 
        //{
            var lead_id=$("#lead_id").val();  
            var quotation_id=($("#quotation_id").val())?$("#quotation_id").val():'';
            var opportunity_id=($("#opportunity_id").val())?$("#opportunity_id").val():'';
            var action_type=(quotation_id!='' && opportunity_id!='')?'edit':'add';         
            $('#create_new_opportunity').append('<input type="hidden" id="lead_id" name="lead_id" value="'+lead_id+'" />');
            $('#create_new_opportunity').append('<input type="hidden" id="action_type" name="action_type" value="'+action_type+'" />');
            $('#create_new_opportunity').append('<input type="hidden" id="quotation_id" name="quotation_id" value="'+quotation_id+'" />');
            $('#create_new_opportunity').append('<input type="hidden" id="opportunity_id" name="opportunity_id" value="'+opportunity_id+'" />');
            //alert(opportunity_id+'/'+action_type+'/'+quotation_id); 
            //return false;
            //document.getElementById("create_new_opportunity").submit();
            $.ajax({
                url: base_url+"opportunity/add_ajax/",
                data: new FormData($('#create_new_opportunity')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
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
                    //$.unblockUI();
                },
                success: function(data)
                {                
                    result = $.parseJSON(data);                     
                    if(result.status=='success')
                    { 
                        parent.load('N');
                        $('#prod_lead').modal('toggle');   
                        //alert(result.opportunity_id+'/'+result.quotation_id)                     
                        edit_qutation_view_modal(result.opportunity_id,result.quotation_id);                    
                    }
                    else
                    {
                        swal("Oops!", result.error_msg, "error");                   
                    }
                    
                }
            }); 
           
        // } 
        // else 
        // {
        //     swal({
        //         title: "Warning",
        //         text: 'Please seleact a product.',
        //         type: "warning",
        //         confirmButtonText: "OK",
        //     });
        // }        
                
    });

    $("body").on("change","#is_discount_p_or_a",function(e){
        var base_url = $("#base_url").val();
        var opportunity_id = $(this).attr('data-opportunityid');
        var quotation_id = $(this).attr('data-quotationid');
        var is_discount_p_or_a=$(this).val();
        // alert('Opp Id:'+opportunity_id+'/Quot id:'+quotation_id+'/'+is_discount_p_or_a);
        $.ajax({
            url: base_url + "opportunity/product_discount_type_update_ajax",
            type: "POST",
            data: {
                'quotation_id': quotation_id,
                'opportunity_id': opportunity_id,
                'is_discount_p_or_a':is_discount_p_or_a
            },
            async: true,
            success: function(data) {
                result = $.parseJSON(data);
                // alert(result.total_discount)
                $("#product_list_update_" + opportunity_id).html(result.html);
                $("#total_deal_value").html(result.total_deal_value);
                $("#total_price").html(result.total_price);
                $("#total_discount").html(result.total_discount);
                $("#total_tax").html(result.total_tax);
                $("#grand_total_round_off").html(result.grand_total_round_off);
                $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
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
            error: function() {}
        });

    });

    $("body").on("click", ".del_quotation_product", function(e) {

        var base_url = $("#base_url").val();
        var id = $(this).attr('data-id');
        var quotation_id = $(this).attr('data-quotationid');
        var opportunity_id = $(this).attr('data-opportunityid');
        var pid=$(this).attr('data-pid');
        // alert(quotation_id+'/'+opportunity_id+'/'+pid); 
        //return false;
        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "opportunity/del_quotation_product_ajax",
                type: "POST",
                data: {
                    'id': id,
                    'quotation_id': quotation_id,
                    'opportunity_id': opportunity_id,
                    'pid': pid,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    // alert(result.html)
                    $("#product_list_update_" + opportunity_id).html(result.html);
                    $("#total_sale_price_" + id).html(result.total_sale_price);
                    //$("#sub_total_update_" + opportunity_id).html(result.sub_total);
                    // alert(result.total_price)
                    $("#total_deal_value").html(result.total_deal_value);
                    $("#total_price").html(result.total_price);
                    $("#total_discount").html(result.total_discount);
                    $("#total_tax").html(result.total_tax);
                    $("#grand_total_round_off").html(result.grand_total_round_off);
                    $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
                    
                    // alert(result.is_product_image_available+'/'+result.is_product_brochure_available);
                    if(result.is_product_image_available=='Y'){
                        $("#p_image_div").css('display','block');
                    }
                    else{
                        $("#p_image_div").css('display','none');
                    }

                    if(result.is_product_youtube_video_available=='Y'){
                        $("#p_youtube_div").css('display','block');
                    }
                    else{
                        $("#p_youtube_div").css('display','none');
                    }

                    if(result.is_product_brochure_available=='Y'){
                        $("#p_brochure_div").css('display','block');
                    }
                    else{
                        $("#p_brochure_div").css('display','none');
                    }
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

    $("body").on("change",".quotation_product_update",function(e){   
        var quotation_pid=$(this).attr("data-id");
        var updated_field_name=$(this).attr("data-field");
        var updated_content=$(this).attr("data-val");
        fn_update_quotation_product(quotation_pid,updated_field_name,updated_content);
    });

    $("body").on("click",".unchecked_pro",function(e){
        var id=$(this).attr('data-id');
        del_prod(id);
        //document.getElementById("select_"+id).checked = false;
        $(this).parent().parent().remove(); 
        var selected_prod_id=$("#selected_prod_id").val(); 
        
        if(selected_prod_id)
        {            
            var array=selected_prod_id.split(",");
            var newArray = array.filter((value)=>value!=id); 
            
            if(newArray.length>0)
            {
                $("#selected_prod_id").val(newArray.toString(','));
            }
            else
            {
                $("#selected_prod_id").val(''); 
                $("#create_new_opportunity").hide();
            }
        } 

        // var flag=0;
        // $('input:checkbox[name="select[]"]:checked').each(function(){
        //     flag++;
        // });

        // if(flag==0)
        // {
        //     $("#create_new_opportunity").hide();
        // }
    });
    /*
    $("body").on('click','input:checkbox[name="select[]"]',function(e){

        var id=$(this).val();
        if($(this).is(":checked")){
        }
        else
        {
            del_prod(id);
            $("#checked_prod_div_"+id).remove(); 
        }

        var flag=0;
        $('input:checkbox[name="select[]"]:checked').each(function(){
            flag++;
        });

        if(flag==0)
        {
            $("#create_new_opportunity").hide();
        }
    });
    */
    $("body").on("click","#quotation_save_and_close",function(e){        
        location.reload();
    });

    $("body").on("click","#delete_quotation",function(e){        
        var quotation_id=$(this).attr("data-qid");
        var opp_id=$(this).attr("data-oppid");
        var base_url=$("#base_url").val();  
        var data="quotation_id="+quotation_id+"&opp_id="+opp_id;
        
        $.ajax({
            url: base_url+"opportunity/qutation_delete_ajax/",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            async: false,
            beforeSend: function( xhr ) {},
            complete: function(){
                $('#automated_quotation_popup_modal').modal('hide');
            },
            success:function(res){ 
                result = $.parseJSON(res);            
                location.reload();
            },            
            error: function(response) {}
        });
    });
    // CREATE QUOTATION
    // ===========================================================

    $("body").on("click","#pdf_file",function(e){
        var cust_email=$("#cust_email").val();
        if(cust_email=='')
        {
            swal('Oops','Quotation can\'t be send as buyer\'s e-mail not available.','error'); 
            return false;
        }
    });

    $("body").on("click","input:checkbox[name=quote_is_digital_signature_checked]",function(e){
        var checked = $(this).is(':checked');
        if(checked) 
        {
            $("#digital_signature_title").html('Name of authorized signatory');
            $(".name_of_authorised_signature_div").css("display",'block');
            $("#thanks_and_regards_div").css("display",'none');
            var updated_content = 'Y';         
        } 
        else 
        {     
            $("#digital_signature_title").html('Thanks & Regards');
            $(".name_of_authorised_signature_div").css("display",'none');
            $("#thanks_and_regards_div").css("display",'block')         
            var updated_content = 'N';        
        }
        
        var quotation_id=$("#quotation_id").val();
        var updated_field_name='is_digital_signature_checked';
        // alert(quotation_id+'/'+updated_field_name+'/'+updated_content)
        fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
    }); 

    $("body").on("change","#search_p_group_q",function(e){
        var parent_id=$(this).val();
        rander_option_category(parent_id,'');    
    });
	
	$("body").on("click",".qutation_re_send_to_buyer",function(e){
	       
           
            //return;
		  var base_url=$("#base_url").val();  
		  var opp_id=$(this).attr("data-opportunityid");
		  var quotation_id=$(this).attr("data-quotationid");

		  var data="quotation_id="+quotation_id+"&opp_id="+opp_id+"&is_resend=Y";
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

      $("body").on("change","#cc_lead_opportunity_currency_type",function(e){
        var c_t=$(this).val();
        $("#input_cc_lead_opportunity_currency_type").val(c_t)
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
         //$('#CommentUpdateLeadModal').modal('hide')
         $('#PoUploadLeadModal').html(response);
         $("#back_div").html('');
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
function del_prod(prod_id='') 
{
    var base_url=$("#base_url").val();
    // var mail_id = $("#selected_prod_id");
    // var mail_val = mail_id.val();
    // mail_id.val(mail_val.replace(prod_id + ',', ''));    
    $.ajax({
        url: base_url+"product/del_tmp_prod_ajax",
        type: "POST",
        data: {
            'prod_id': prod_id
        },
        async: true,
        success: function(response) {
            // $('#product_list').html(response);
            // change_currency('currency_type_new', 'currency_name_prod_tot', 'currency_type_name_new');
            // $('#product_del').show();

            // var sum = 0;
            // $(".amount").each(function(i) {
            //     var val = $(this).val();
            //     if (val != "") {

            //         sum = sum + parseFloat(val);
            //     } else {
            //         sum = sum + 0;
            //     }
            // });

            // $('#sub_total').html(parseInt(sum));
            //$('#deal_value_new').val(parseInt(sum));

        },
        error: function() {
            //$.unblockUI();
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
                }, function() {

                });
        }
    });
}
function edit_qutation_view_modal(opp_id,quotation_id)
{
    var base_url=$("#base_url").val(); 
    var lead_id=$("#lead_id").val(); 
    var data="opp_id="+opp_id+'&lead_id='+lead_id+"&quotation_id="+quotation_id;     
    //alert(data)
    $.ajax({
            url: base_url+"opportunity/edit_ajax",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
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
            success:function(response){ 
                //result = $.parseJSON(res); 
                rander_quotation_wise_photo_ajax(quotation_id);
                $("#automated_quotation_popup_modal_body").html(response);
                $('#automated_quotation_popup_modal').modal({backdrop: 'static',keyboard: false}).css('overflow-y', 'auto');
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
            }
        }); 
}


function add_prod(prod_id_array=[],opp_id='') 
{
    $("#create_new_opportunity").hide();
    $("#selected_product_div").html('');
    var base_url = $("#base_url").val();

    if(prod_id_array.length==0) 
    {
        var prod_id_array = $.map($('input[name="select[]"]:checked'), function(c) {
            return c.value;
        });
    }

    // var prod_name_array=[]; 
    // $('input:checkbox[name="select[]"]:checked').each(function(){
    //     var tmp_str=$(this).val()+'@'+$(this).attr('data-name');
    //     prod_name_array.push(tmp_str);
    // });   

    if (prod_id_array.length > 0) 
    {
        var prod_id = prod_id_array.toString();
        $.ajax({
            url: base_url + "product/selectleadprod_ajax",
            type: "POST",
            data: {
                'prod_id': prod_id,
                'opp_id':opp_id
            },
            async: true,
            success: function(data) {  
                result = $.parseJSON(data);  
                //$('#product_list').html(response);
                //$('#prod_lead').modal('toggle');

                $('#err_prod').hide();
                $('#product_del').hide();
                var new_prod_name_array=[];                     
                var selected_product_html='';
                if(result.selected_prod_id.length>0)
                {
                    for(var i=0;i<result.selected_prod_id.length;i++)
                    {
                        var tmp_str = result.selected_prod_id[i].split("@");
                        selected_product_html +='<div class="col-auto" id="checked_prod_div_'+tmp_str[0]+'"><div class="search-item"><a href="javaScript:void(0)" class="search-remove unchecked_pro" data-id="'+tmp_str[0]+'"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a><span>'+tmp_str[1]+'</span></div></div>';
                    
                        new_prod_name_array.push(tmp_str[0]);
                    }
                }
                
                $('#prod_lead_list').html(result.html);
                $("#selected_product_div").html(selected_product_html);
                $("#create_new_opportunity").show(); 
                $("#selected_prod_id").val(new_prod_name_array);
                $("#search_prod_lead_list").hide();
                $(".search_product_by_keyword").val('');

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
    } 
    else 
    {
        swal({
            title: "Warning",
            text: 'Please seleact a product.',
            type: "warning",
            confirmButtonText: "OK",
        });
    }
}
function custom_quation_pdf_upload()
{    
    var base_url = $("#base_url").val();  
    var lead_id=$("#lead_id").val();
    // var form_data = new FormData();       
    var currency_select_html=$("#currency_select_html_for_custom_quation_pdf_upload").html();
    var extension=$('#pdf_file').val().replace(/^.*\./, '');
    if(extension=='pdf')
    {
        swal({
            title: "Deal Value",
            html: true,            
            type: "input",
            text: currency_select_html,
            showCancelButton: true,
            showConfirmButton: true,
            closeOnConfirm: false,
            confirmButtonText: 'Submit',
            inputPlaceholder: "Deal Value"
            // title: "Deal Value",
            // text: "Enter Deal Value for the Quotation:",
            // type: "input",
            // showCancelButton: true,
            // showConfirmButton: true,
            // closeOnConfirm: false,
            // confirmButtonText: 'Submit',
            // inputPlaceholder: "Deal Value"
        },
        function(inputValue){

            if (inputValue===null) return false;
            
            if (inputValue==="") {
                swal.showInputError("You need to write Deal Value for the quotation!");
                return false;
            }
            
            if((Math.round(inputValue) % 1 === 0) && inputValue!=''){
                var currency_type=$('#input_cc_lead_opportunity_currency_type').val();                
                $('#form_upload_pdf').append('<input type="hidden" id="deal_value" name="deal_value" value="'+inputValue+'" />');
                $('#form_upload_pdf').append('<input type="hidden" id="currency_type" name="currency_type" value="'+currency_type+'" />');
                $('#form_upload_pdf').append('<input type="hidden" id="lead_id" name="lead_id" value="'+lead_id+'" />');
                swal({
                    title: "Success",
                    text: "The Deal Value successfully submitted",
                    showCancelButton: false,
                    showConfirmButton: true,
                    closeOnConfirm: true,
                    confirmButtonText: 'Ok',
                },
                function(){   
                    // alert($("#deal_value").val()+'/'+$("#currency_type").val()+'/'+lead_id);                 
                    $.ajax({
                        url: base_url+"opportunity/pdf_upload_for_custom_quotation_ajax/",
                        data: new FormData($('#form_upload_pdf')[0]),
                        cache: false,
                        method: 'POST',
                        dataType: "html",
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData:false,
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
                        success: function(data)
                        {                
                            result = $.parseJSON(data);                     
                            if(result.status=='success')
                            { 
                                $('#create_quotation_popup_modal').modal('toggle');
                                //swal('PDF Uploaded Successfully');    
                                qutation_send_to_buyer_modal(result.opportunity_id,result.quotation_id);                    
                            }
                            else
                            {
                                swal("Oops!", result.error_msg, "error");                   
                            }
                            
                        }
                    });
                });

               
            }
            else
            {
                swal.showInputError("Deal Value should be numeric!");
                return false;
            }
            return false;            
        });
        

        /*
        swal({
        title: 'Are you want to upload this file?',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, upload it!'
        }, function() {

            $.ajax({
                url: base_url+"opportunity/pdf_upload_for_custom_quotation_ajax/",
                data: new FormData($('#form_upload_pdf')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
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
                success: function(data)
                {                
                    result = $.parseJSON(data);                     
                    if(result.status=='success')
                    { 
                        $('#create_quotation_popup_modal').modal('toggle');
                        //swal('PDF Uploaded Successfully');    
                        qutation_send_to_buyer_modal(result.opportunity_id,result.quotation_id);                    
                    }
                    else
                    {
                        swal("Oops!", result.error_msg, "error");                   
                    }
                    
                }
            });
        }); */
    }
    else
    {
        swal('Please select a PDF File'); // display response from the PHP script, if any
        return false;
    }

}

function submit_new_opportunity() 
{
    var opportunity_title_new_obj = $('#opportunity_title_new');
    var currency_type_new_obj = $('#currency_type_new');
    var base_url = $("#base_url").val();
    if (opportunity_title_new_obj.val() == '' || opportunity_title_new_obj.val() == null) {
        opportunity_title_new_obj.focus();
        $("#opportunity_title_new_error").html('Please enter Quotation Title');
        return false;
    } else {
        $("#opportunity_title_new_error").html('');
    }

    if (currency_type_new_obj.val() == '' || currency_type_new_obj.val() == null) {
        currency_type_new_obj.focus();
        $("#currency_type_new_error").html('Please select Currency');
        return false;
    } else {
        $("#currency_type_new_error").html('');
    }

    $.ajax({
        url: base_url + "lead/check_temp_product_ajax",
        type: "POST",
        data: {},
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            if (result.msg == 'EXIST') {
                document.getElementById("create_new_opportunity").submit();
            } else {
                $("#select_product_error").html('Please select product.');
                return false;
            }
        },
        error: function() {
            //$.unblockUI();
            swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
        }
    });

}
function load_pre_fedine_update_lead_comments() 
{    
    var base_URL = $("#base_url").val();
    var user_id=$("#user_id").val();      
    var data = "user_id="+user_id;
    
    $.ajax({
        url: base_URL+"lead/rander_lead_update_pre_define_comment",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success:function(res){ 
            result = $.parseJSON(res);
            $("#lead_scroller").html(result.html)
        },
        complete: function(){},
        error: function(response) {
        //alert('Error'+response.table);
        }
    })
}

function fn_open_lead_update_client_mail_subject_popup()
{
    var lead_id=$("#lead_id").val();
    var m_subject=($("#mail_to_client_mail_subject").val())?$("#mail_to_client_mail_subject").val():'Enquiry # '+lead_id+' - Query/Update from your A/C Manager';
    $("#update_lead_mail_subject").val(m_subject);
    $('#update_lead_mail_subject_change_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}
function fn_open_lead_update_regret_this_lead_mail_subject_popup()
{
    var m_subject=$("#regret_this_lead_mail_subject").val();
    $("#regret_this_lead_mail_subject_edit").val(m_subject);
    $('#regret_this_lead_mail_subject_change_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

/*
function fn_update_quotation_letter(quotation_id,updated_field_name,updated_content)
{	
	var base_url=$("#base_url").val();	
	//alert(base_url+' / '+quotation_id+' / '+updated_field_name+' / '+updated_content);return false;
    if(updated_field_name=='is_product_image_show_in_quotation')
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

    if(updated_field_name=='is_company_brochure_attached_in_quotation')
    {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
    }
  
    var data="quotation_id="+quotation_id+"&updated_field_name="+updated_field_name+"&updated_content="+encodeURIComponent(updated_content)
    //alert(data); return false;
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
*/
function validate_fileupload(fileName) {
    var allowed_extensions = new Array("pdf", "doc", "docx");
    var file_extension = fileName.split('.').pop().toLowerCase(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

    for (var i = 0; i <= allowed_extensions.length; i++) {
        if (allowed_extensions[i] == file_extension) {
            return true; // valid file extension
        }
    }

    return false;
}

function fn_set_post_data(lead_opp_id, lead_id, opp_title,deal_value) {
    $("#opp_title").html("'" + opp_title + "'");
    $("#po_lead_opp_id").val(lead_opp_id);
    $("#po_lead_id").val(lead_id);
    $("#deal_value_div").html(deal_value);
}



function input_gst_disable_by_name(name) {
    $("[name='" + name + "']").val(0);
    $("[name='" + name + "']").attr("readonly", true);
    $("[name='" + name + "']").each(function(e) {
        //alert($(this).attr("data-field")+' / '+$(this).attr("data-pid"));
        var pid = $(this).attr("data-pid");
        var field = $(this).attr("data-field");
        var value = 0;
        calculate_quotation_price(pid, field, value);
    });
}

function input_gst_enable_by_name(name) {
    $("[name='" + name + "']").attr("readonly", false);
}

function calculate_quotation_price(pid, field, value) {
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "product/update_temp_selected_product_ajax",
        type: "POST",
        data: {
            'field': field,
            'value': value,
            'pid': pid
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            $("#g_total_" + pid).html(result.total_sale_price);
            $("#sub_total").html(result.sub_total);
            $("#deal_value").val(result.sub_total);

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
}

function input_gst_update_disable_by_name(name) {
    $("[name='" + name + "']").val(0);
    $("[name='" + name + "']").attr("readonly", true);
    $("[name='" + name + "']").each(function(e) {
        var pid = $(this).attr("data-pid");
        var opportunity_id = $("#opportunity_id").val();
        var quotation_id = $("#quotation_id").val();
        var id = $(this).attr("data-id");
        var field = $(this).attr("data-field");
        var value = 0;
        //alert(pid+' / '+opportunity_id+' / '+id+' / '+field+' / '+value); 
        calculate_quotation_price_update(pid, opportunity_id, id, field, value,quotation_id);
    });
}

function input_gst_update_enable_by_name(name) {
    $("[name='" + name + "']").attr("readonly", false);
}

function calculate_quotation_price_update(pid, opportunity_id, id, field, value, quotation_id) {
    //alert(pid+'/'+opportunity_id+'/'+quotation_id+'/'+field+'/'+value)
    
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "opportunity/update_quotation_product_ajax",
        type: "POST",
        data: {
            'field': field,
            'value': value,
            'id': id,
            'pid': pid,
            'opportunity_id': opportunity_id,
            'quotation_id': quotation_id,
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            // alert(pid+': '+result.total_sale_price)			
            $("#total_sale_price_"+id).html(result.total_sale_price);
            //$("#sub_total_update_" + opportunity_id).html(result.sub_total);

            $("#total_deal_value").html(result.total_deal_value);
            $("#total_price").html(result.total_price);
            $("#total_discount").html(result.total_discount);
            $("#total_tax").html(result.total_tax);
            $("#grand_total_round_off").html(result.grand_total_round_off);
            $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
            
            // alert(result.total_price+'/'+result.total_discount+'/'+result.total_tax+'/'+result.grand_total_round_off+'/'+result.number_to_word_final_amount)
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
}

function input_additional_charges_gst_disable_by_name() {
    $(".additional_charges_gst").val(0);
    $(".additional_charges_gst").attr("readonly", true);

    $(".additional_charges_gst").each(function(e) {
        var pid = $(this).attr("data-pid");
        var opportunity_id = $("#opportunity_id").val();
        var quotation_id = $("#quotation_id").val();
        var id = $(this).attr("data-id");
        var field = $(this).attr("data-field");
        var value = 0;
        //alert(opportunity_id+' / '+id+' / '+field+' / '+value); 
        calculate_quotation_additional_charges_price_update(opportunity_id, id, field, value,quotation_id);
    });

}

function input_additional_charges_gst_enable_by_name() {
    $(".additional_charges_gst").attr("readonly", false);
}

function calculate_quotation_additional_charges_price_update(opportunity_id, id, field, value,quotation_id) {
    var base_url = $("#base_url").val();
    //var quotation_id = $("#quotation_id").val();
    // alert(quotation_id+'/'+opportunity_id);
    $.ajax({
        url: base_url + "opportunity/update_opportunity_additional_charges_ajax",
        type: "POST",
        data: {
            'field': field,
            'value': value,
            'id': id,
            'opportunity_id': opportunity_id,
            'quotation_id': quotation_id,
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            //alert(result.sub_total);
            // alert(result.total_deal_value);
            $("#additional_charges_total_sale_price_" + id).html(result.total_sale_price);
            
            //$("#g_total_update_" + pid).html(result.total_sale_price);
            //$("#sub_total_update_" + opportunity_id).html(result.sub_total);
            
            $("#total_deal_value").html(result.total_deal_value);
            $("#total_price").html(result.total_price);
            $("#total_discount").html(result.total_discount);
            $("#total_tax").html(result.total_tax);
            $("#grand_total_round_off").html(result.grand_total_round_off);
            $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
            

            // $("#sub_total_update_" + opportunity_id).html(result.sub_total);
            // $("#deal_value").val(result.sub_total);
            //$("#product_list_update_"+result.opportunity_id).html(result.html)
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
}

function GetProdLeadListUpdate(lead_id, opp_id) {
    $("#u_opp_id").val(opp_id);
    $('#prod_lead_list_update').html("");
    $('.update_search_product_by_keyword').val("");      
    $('#prod_lead_update').modal();
    /*$("#opp_id").val(opp_id);
    var temp_prod_id = document.getElementById('selected_prod_id_update_' + opp_id).value;
    var base_url = $("#base_url").val();
    //alert(lead_id+'/'+opp_id);
    $.ajax({
        url: base_url + "product/getprodlist_ajax",
        type: "POST",
        data: {
            'lead_id': lead_id,
            'temp_prod_id': temp_prod_id,
            'search_keyword': '',
            'opportunity_id': opp_id
        },
        async: true,
        success: function(response) {
            //$('#prod_lead_list_update').html(response);         
            $('#prod_lead_update').modal();
            
        },
        error: function() {
            //$.unblockUI();
            swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
        }
    });*/
}

function add_prod_update(opp_id) {
    //var total=$('input[type="checkbox"]:checked').length;
    var prod_id_array = $.map($('input[name="select[]"]:checked'), function(c) {
        return c.value;
    })
    var base_url = $("#base_url").val();
    if (prod_id_array.length > 0) {
        //var prod_id=document.getElementById('selected_prod_id_update_'+opp_id).value;       
        var prod_id = prod_id_array.toString();
        var opportunity_id = opp_id;
        $.ajax({
            url: base_url + "product/selectleadprodupdate_ajax",
            type: "POST",
            data: {
                'prod_id': prod_id,
                'opportunity_id': opportunity_id
            },
            async: true,
            success: function(data) {
                result = $.parseJSON(data);
                $("#product_list_update_" + opportunity_id).html(result.html);
                $('#prod_lead_update').modal('toggle');
                // $('#product_list_update_'+opportunity_id).html(response);

                // change_currency_update('currency_type_update_'+opp_id,'currency_name_prod_tot_update_'+opp_id,'currency_type_name_update_'+opp_id,'all_currency_update_'+opp_id);
                // $('#prod_lead_update').modal('toggle');
                // $('#err_prod_update').hide();
                // $('#product_del_update').hide();

                // var tot_amt=$('#sub_total_update_'+opp_id).html();
                // $('#all_currency_update_'+opp_id).val(parseInt(tot_amt));
                // $('#deal_value_update_'+opp_id).val(parseInt(tot_amt));

            },
            error: function() {
                //$.unblockUI();
               swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
            }
        });
    } else {
        $('#err_prod').show();
    }
}

function remove_image() 
{
    swal({
      title: 'Are you sure?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: false
    }, 
    function(isConfirm) {
          if (isConfirm) {
          //$("#prod_up_pic a").hide();
          //$("#PreviewPicture").hide();
          $('#fileupload_image').val("");
          $("#PreviewPicture").css("display", "none");
          swal('Deleted!', 'Your file has been deleted!', 'success');
        }
        return false;
    });
}

function remove_pdf() {
    swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
        }, 
        function(isConfirm) {
            if (isConfirm) {
            //$("#PreviewPdf").hide();
            $('#fileupload_pdf').val("");
            $("#PreviewPdf").css("display", "none");
            swal('Deleted!', 'Your file has been deleted!', 'success');
        }
        return false;
    }); 
}






function change_currency(id, div, currency_type_name_new) {
    var value = $("#" + id + " option:selected").val();
    var val = $("#" + id + " option:selected").text();
    var val2 = val.match(/\((.*)\)/);

    $("#" + div).html(val2[1]);
    $("." + currency_type_name_new).text(val2[1]);
    $(".all_currency_new").text(val2[1]);
}

function calculate_price(unit_prod, price_prod, total_id, disc_id, grand_total, prod_id, currency_id, main_price) {
    return false;
    var base_url = $("#base_url").val();
    var qty = $('#' + unit_prod).val();
    var price = $('#' + price_prod).val();
    var disc = $('#' + disc_id).val();
    var currency_id = $('#' + currency_id).val();
    var main_price = $('#' + main_price).val();
    var f_tot = 0;
    if (parseInt(main_price) < parseInt('1')) {
        $.ajax({
            url: base_url + "product/update_prod_price_ajax",
            type: "POST",
            data: {
                'price': price,
                'prod_id': prod_id
            },
            success: function(response) {
                swal('Master Product price updated');
                $('#' + price_prod).val(parseInt(price));
            },
            error: function() {
                //$.unblockUI();
                swal('Something went wrong there');
            }
        });
    } else if (parseInt(price) < parseInt(main_price)) {
        swal('Your price is lower actual price');
        $('#' + price_prod).val(parseInt(main_price));
        return false;
    }

    var tot = parseInt(qty) * parseInt(price);
    var tot_n = (parseInt(disc) / parseInt('100')) * parseInt(tot);
    f_tot = parseInt(tot) - parseInt(tot_n);
    $('#' + total_id).val(parseInt(f_tot));
    $('#' + grand_total).html(parseInt(f_tot));

    $.ajax({
        url: base_url + "product/update_quantity_ajax",
        type: "POST",
        data: {
            'quantity': qty,
            'price': price,
            'discount': disc,
            'prod_id': prod_id,
            'currency_id': currency_id
        },
        success: function(response) {
            // console.log('success');
        },
        error: function() {
            //$.unblockUI();
            swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
        }
    });

    var sum = 0;
    $(".amount").each(function(i) {
        var val = $(this).val();
        if (val != "") {
            sum = sum + parseFloat(val);
        } else {
            sum = sum + 0;
        }
    });

    $('#sub_total').html(parseInt(sum));
    $('#deal_value_new').val(parseInt(sum));

}

function GetProdLeadList(lead_id,opp_id='') 
{
    var base_url=$("#base_url").val();     
    $("#opp_id").val('');
    $.ajax({
        url: base_url+"lead/get_lead_tagged_product_ajax",
        type: "POST",
        data: {'lead_id':lead_id,'opp_id':opp_id},       
        async:true,     
        success: function (response) 
        {   
            if(response)
            {    
                add_prod(response,opp_id);
            }
            var html="";
            /*html+='<div class="card-block"><div class="no-more-tables"><table id="" class="table table-striped m-b-0"><thead><tbody><tr><td colspan="6" align="center"><h3 class="no-found-text">No products found!</h3></td></tr></tbody></thead></table></div></div>'*/
            $("#selected_product_div").html('');
            $('#prod_lead_list').html(html);
            $('.search_product_by_keyword').val("");
            rander_option_group(0,'');
            $('#prod_lead').modal({
                backdrop: 'static',
                keyboard: false
            }).css('overflow-y', 'auto');

        },
        error: function () 
        {
            alert('Something went wrong there');
        }
    });



    /*var base_url=$("#base_url").val(); 
    $("#opp_id").val('');
    var temp_prod_id=document.getElementById('selected_prod_id').value;

    $.ajax({
        url: base_url+"product/getprodlist_ajax",
        type: "POST",
        data: {'lead_id':lead_id,'temp_prod_id':temp_prod_id,'search_keyword':'','opportunity_id':''},       
        async:true,     
        success: function (response) 
        {
            $('#prod_lead_list').html(response);          
            //$('#prod_lead').modal().css('overflow-y', 'auto');
            $('#prod_lead').modal({backdrop: 'static',keyboard: false}).css('overflow-y', 'auto');

        },
        error: function () 
        {
            alert('Something went wrong there');
        }
    });*/
}

function get_opportunity_details(opportunity_id, lead_id) {
    var base_url = $("#base_url").val();
    $('.opp_ajax_details').hide(600);
    $('.opp_ajax_details').html('');
    var state = $('#update_' + opportunity_id).is(':visible');
    if (state == false) {
        $.ajax({
            url: base_url + "opportunity/details_ajax",
            type: "POST",
            data: {
                'opportunity_id': opportunity_id,
                'lead_id': lead_id
            },
            success: function(response) {
                if (response != '') {
                    $("#show_quotation_wise_product_icon_" + opportunity_id).html('<i class="fa fa-level-up" aria-hidden="true"></i>');
                    $('#update_' + opportunity_id).html(response);
                    $('#update_' + opportunity_id).show(800);
                }
            },
            error: function() {
                //$.unblockUI();
                swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
            }
        });
    } else {
        $("#show_quotation_wise_product_icon_" + opportunity_id).html('<i class="fa fa-level-down" aria-hidden="true"></i>');
        $('#update_' + opportunity_id).hide(800);
    }
}


function general_update() 
{   
    var base_url = $("#base_url").val();
    //var description=document.getElementById('general_description').value;
    var description = tinyMCE.activeEditor.getContent();
    var lead_id = document.getElementById('lead_id').value;
    var communication_type = document.getElementById('communication_type').value;
    var next_followup_date = document.getElementById('next_followup_date').value;
    var mail_to_client = ($('input[name="mail_to_client"]:checked').val()) ? $('input[name="mail_to_client"]:checked').val() : 'N';
    var mark_cc_mail_str = '';
    $('#general_description').val(description);
    if (description == '') {
        swal('Oops! Description should not be blank.');
        return false;
    }

    if (communication_type == '') {
        swal('Oops! Please select communication type.');
        return false;
    }

    if (next_followup_date == '') {
        swal('Oops! Please select next followup date.');
        return false;
    }

    if ($("#mark_cc_mail").val()) {
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
            $("#lead_edit_confirm").attr("disabled",true);
        },
        success: function(data) {

            //$(".preloader").hide();
            result = $.parseJSON(data);
            //console.log(result.msg);
			
            if (result.status == 'success') {
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
                        location.reload();
                    });

            } else {

            }

        }
    });
    /*
    $.ajax({
            url: base_url+"lead/create_lead_comment_ajax",
            type: "POST",
            data: {'description':description,'lead_id':lead_id,'communication_type':communication_type,'next_followup_date':next_followup_date,'mail_to_client':mail_to_client,'mark_cc_mail_str':mark_cc_mail_str},
            cache: false,
            method: 'POST',
            dataType: "html",
            success: function (res) 
            {
              result = $.parseJSON(res);
              //alert(result.msg);
              location.reload();
            },
            error: function () 
            {
              //$.unblockUI();
              alert('Something went wrong there');
            }
    }); 
    */
}

function general_update2() 
{
    var base_url = $("#base_url").val();
    //var description=document.getElementById('general_description').value;
    var description = tinyMCE.activeEditor.getContent();
    var lead_id = document.getElementById('lead_id').value;
    var communication_type = document.getElementById('communication_type').value;
    var next_followup_date = document.getElementById('next_followup_date').value;
    var mail_to_client = ($('input[name="mail_to_client"]:checked').val()) ? $('input[name="mail_to_client"]:checked').val() : 'N';
    var client_not_interested = ($('input[name="client_not_interested"]:checked').val()) ? $('input[name="client_not_interested"]:checked').val() : 'N';
    var mark_cc_mail_str = '';
    $('#general_description').val(description);
    if (description == '') {
        swal('Oops! Description should not be blank.');
        return false;
    }

    if (communication_type == '') {
        swal('Oops! Please select communication type.');
        return false;
    }

    // if(next_followup_date=='')
    // {
    //     swal('Oops! Please select next followup date.');
    //     return false;
    // }

    if ($("#mark_cc_mail").val()) {
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

    if(client_not_interested=='Y')
    {
        if($("#lead_regret_reason_id").val()=='')
        {
            swal('Oops! Please select Regret Reasons.');
            return false;
        }     

        if($("#regret_this_lead_mail_subject").val()=='')
        {
            swal('Oops! Please enter mail subject for Regret This Lead .');
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
            $("#lead_edit_confirm").attr("disabled",true);
        },
        success: function(data) {

            //$(".preloader").hide();
            result = $.parseJSON(data);
            //alert(result.msg);return false;
            if (result.status == 'success') {
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
                        location.reload();
                    });

            } else {

            }

        }
    });
}


	
$("body").on("click", "#hist_info", function(e) {

    $('#hist_list').slideToggle('fast', function() {
        if ($(this).is(':visible') == true) {

            $("#hist_info_icon").html('<i class="fas fa-chevron-up"></i>');
        } else {

            $("#hist_info_icon").html('<i class="fas fa-chevron-down"></i>');
        }
    });

})

$("body").on("click", "#show_update_lead_div", function(e) {

    $('#update_lead_div').slideToggle('fast', function() {
        if ($(this).is(':visible') == true) {

            $("#show_update_lead_div_icon").html('<i class="fas fa-chevron-up"></i>');
        } else {

            $("#show_update_lead_div_icon").html('<i class="fas fa-chevron-down"></i>');
        }
    });

})



function submit_opportunity(opportunity_id) 
{
    var opportunity_title_update_obj = $('#opportunity_title_update_' + opportunity_id);
    var currency_type_new_obj = $('#currency_type_update_' + opportunity_id);
    var base_url = $("#base_url").val();
    if (opportunity_title_update_obj.val() == '' || opportunity_title_update_obj.val() == null) 
    {
        opportunity_title_update_obj.focus();
        $("#opportunity_title_update_" + opportunity_id + "_error").html('Please enter Quotation title');
        return false;
    } 
    else 
    {
        $("#opportunity_title_update_" + opportunity_id + "_error").html('');
    }

    if (currency_type_new_obj.val() == '' || currency_type_new_obj.val() == null) 
    {
        currency_type_new_obj.focus();
        $("#currency_type_update_" + opportunity_id + "_error").html('Please select Currency');
        return false;
    } 
    else 
    {
        $("#currency_type_update_" + opportunity_id + "_error").html('');
    }

    $.ajax({
        url: base_url + "lead/check_opportunity_product_ajax",
        type: "POST",
        data: {
            "opportunity_id": opportunity_id
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            if (result.msg == 'EXIST') {
                $('#update_opportunity_form').submit();
            } else {
                $("#select_product_update_" + opportunity_id + "_error").html('Please select product.');
                return false;
            }
        },
        error: function() {
            //$.unblockUI();
            swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
        }
    });

}

/*===================Start Add product for lead */

function add_product_modal(search_product) {
    //$('#lead_add_product_modal').modal();
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url+'product/addProduct_ajax',
        type: "POST",
        data: {
            "product": search_product
        },
        dataType: "html",
        success: function(response) {
            $('#add_product_for_lead_body').html(response);
            //$('#lead_add_product_modal').modal();
            $('#lead_add_product_modal').modal({
                backdrop: 'static',
                keyboard: false
            }).css('overflow-y', 'auto');
        },
        error: function() {
            //$.unblockUI();
            swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
        }
    });
}

/*===================End  Add product for lead */

/*===================Start Add product for lead */

function select_vendor_product_lead(pid) {


    $('#vdraddfromProductId').val(pid);
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url+'product/selectVendors',
        type: "POST",
        dataType: "html",
        success: function(response) {
            $('#select_vendors_for_product_lead_body').html(response);
            $('#lead_select_vendors_modal').modal();
        },
        error: function() {
            //$.unblockUI();
            swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
        }
    });
}

/*===================End  Add product for lead */

function generate_quotation(url, opp_id) 
{    
    $("#opp_id").val(55);
    $('#generate_quotation').modal('toggle');
    $('#quote_url').attr("href", url);
    $('#pdf_file').attr("onchange", "quation_upload("+opp_id+")");
    
}
function quation_upload(opp_id)
{
    
    var base_url = $("#base_url").val();
    //var file_data = $('#pdf_file').prop('files')[0];   
    var form_data = new FormData();                  
    //form_data.append('pdf_file', file_data);
    //form_data.append('opp_id', opp_id);
    $('#form_upload_pdf').append('<input type="hidden" id="opp_id" name="opp_id" value="'+opp_id+'" />');
    var extension=$('#pdf_file').val().replace(/^.*\./, '');    
    if(extension=='pdf')
    {
        swal({
        title: 'Are you want to upload this file?',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, upload it!'
        }, function() {
            
            $.ajax({
                url: base_url+"opportunity/quotation_pdf_upload_ajax/",
                data: new FormData($('#form_upload_pdf')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
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
                    //console.log(result.success_msg); //return false;
                    //alert(result.status); 
                    if(result.status=='success')
                    {					
                        //$('#'+opp_id+'_quote_count').html(response);
                        $('#generate_quotation').modal('toggle');
                        swal('PDF Uploaded Successfully');    
                        qutation_send_to_buyer_modal(result.opportunity_id,result.quotation_id);                    
                    }
                    else
                    {
                        swal("Oops!", result.error_msg, "error");					
                    }
                    
                }
            });
            /*
            $.ajax({
                url: base_url+"opportunity/pdf_upload_ajax", // point to server-side PHP script 
                dataType: "text",
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: "POST",
                success: function(response){
                    $('#'+opp_id+'_quote_count').html(response);
                    $('#generate_quotation').modal('toggle');
                    swal('PDF Uploaded Successfully'); 
                }
            });
            */

        });	
    }
    else
    {
        swal('Please select a PDF File'); // display response from the PHP script, if any
        return false;
    }

}

function qutation_send_to_buyer_modal(opp_id,quotation_id)
{
    var base_url=$("#base_url").val();  
    //var opp_id=$(this).attr("data-opportunityid");
    //var quotation_id=$(this).attr("data-quotationid");
    var data="quotation_id="+quotation_id+"&opp_id="+opp_id;
    $.ajax({
            url: base_url+"opportunity/qutation_send_to_buyer_by_mail_ajax/",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
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
               //$("#qutation_send_to_buyer_body").html(result.html);
               //$('#qutation_send_to_buyer_modal').modal({backdrop: 'static',keyboard: false}).css('overflow-y', 'auto');
               
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
            error: function(response) {
            }
        }); 
}


function quotation_product_load(opportunity_id,quotation_id)
{
    var base_url = $("#base_url").val();
    var opportunity_id = $(this).attr('data-opportunityid');
    var quotation_id = $(this).attr('data-quotationid');    
    $.ajax({
        url: base_url + "opportunity/rander_quotation_product_ajax",
        type: "POST",
        data: {
            'quotation_id': quotation_id,
            'opportunity_id': opportunity_id
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
            // alert(result.html)
            $("#product_list_update_" + opportunity_id).html(result.html);
            $("#total_deal_value").html(result.total_deal_value);
            $("#total_price").html(result.total_price);
            $("#total_discount").html(result.total_discount);
            $("#total_tax").html(result.total_tax);
            $("#grand_total_round_off").html(result.grand_total_round_off);
            $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
            $("#quotation_product_sortable .basic-wysiwyg-editor").each(function(){
                tinymce.init({
                    force_br_newlines : true,
                    force_p_newlines : false,
                    forced_root_block : '',
                    menubar: false,
                    statusbar: false,
                    toolbar: false,
                    setup: function(editor) {
                        // editor.on('focusout', function(e) {                   
                        //     var quotation_id=$("#quotation_id").val();
                        //     var updated_field_name=editor.id;
                        //     var updated_content=editor.getContent();
                        //     fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
                        // })
                    }
                });
                tinymce.execCommand('mceRemoveEditor', true, this.id); 
                tinymce.execCommand('mceAddEditor', true, this.id); 
            });

            $("#quotation_additional_sortable .basic-wysiwyg-editor").each(function(){
                tinymce.init({
                    force_br_newlines : true,
                    force_p_newlines : false,
                    forced_root_block : '',
                    menubar: false,
                    statusbar: false,
                    toolbar: false,
                    setup: function(editor) {
                        // editor.on('focusout', function(e) {                   
                        //     var quotation_id=$("#quotation_id").val();
                        //     var updated_field_name=editor.id;
                        //     var updated_content=editor.getContent();
                        //     fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
                        // })
                    }
                });
                tinymce.execCommand('mceRemoveEditor', true, this.id); 
                tinymce.execCommand('mceAddEditor', true, this.id); 
            });
            
        },
        error: function() {}
    });
}