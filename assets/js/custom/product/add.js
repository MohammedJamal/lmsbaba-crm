$(document).ready(function(){
    // ==================================
    // initilasize
	var selected_group_id=($("#selected_group_id").val())?$("#selected_group_id").val():'';
	var selected_cat_id=($("#selected_cat_id").val())?$("#selected_cat_id").val():'';
	
    rander_option_group(0,selected_group_id);
	
	if(selected_group_id!='')
	{			
		rander_option_category(selected_group_id,selected_cat_id);
	}
			
			
	/*
    var maxShortDes = 100;
    $('#description').keyup(function() {
       var textlen = maxShortDes - $(this).val().length;
       $(this).parent().find('.rchars').text(textlen+' word(s) remaining');
    });
    var maxLongDes = 1000;
    $('#long_description').keyup(function() {
       var textlen = maxLongDes - $(this).val().length;
       $(this).parent().find('.rchars').text(textlen+' word(s) remaining');
    });  
	*/
    //////
    $('input#fileupload_pdf').change(function(e) { 
        var geekss = e.target.files[0].name; 
        //alert(geekss + ' is the selected file.');
        $('#pdf_up').hide();
        $(this).parent().parent().append('<div class="del_pdf_file">'+geekss+'<a href="#" class="del_pdf_new"><i class="fa fa-trash" aria-hidden="true"></i></a></div>');
        //$(this).parent().find('label').text(geekss)

    }); 
    //
    $(document).on('click', '.del_pdf_new', function (e) {
       e.preventDefault();
	   var pdf_id=$(this).attr("data-id");
	   if(pdf_id)
	   {
		   swal({
                title: "Confirmation",
                text: "Do you want to delete the pdf?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () { 
				var base_URL = $("#base_url").val();
                var data="pdf_id="+pdf_id;  
				
                $.ajax({
                        url: base_URL+"/product/delete_pdf_ajax",
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
                                               
                        },
                        success: function(data){
                            result = $.parseJSON(data);
                            $(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Updated!",
                                text: "The pdf has been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true);  
								$('#pdf_up').show();
							    $('#fileupload_pdf').val('');
							    $('.del_pdf_file').remove();
                            });
                           
                        },
                        complete: function(){
                       
                       },
                });
                
            }); 
	   }
	   else
	   {
		   $('#pdf_up').show();
		   $('#fileupload_pdf').val('');
		   $('.del_pdf_file').remove();
	   }
       
    }); 
    //////
    $('.product_up input').change(function(e) { 
       var geekss = e.target.files[0].name;
       var files = !!this.files ? this.files : [];
       var tar = $(this);
       if (!files.length || !window.FileReader) return;
       if (/^image/.test(files[0].type)){ 
            var getcon = $(this);
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function(){
                var result_obj = this.result;
                //alert(result_obj);
                tar.parent().parent().find('.product_up').hide();
                tar.parent().parent().find('.product_photo_preview span').html('<img src="' + result_obj + '" >');
                tar.parent().parent().find('.product_photo_preview').show();
            }
       }
       //alert(geekss + ' is the selected file.');
       
    });
    $(document).on('click', '.product_photo_preview .remove_pic', function (e) {
       e.preventDefault();
	   var img_id=$(this).attr("data-id");
	   if(img_id)
	   {
		   swal({
                title: "Confirmation",
                text: "Do you want to delete the image?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () { 
				var base_URL = $("#base_url").val();
                var data="img_id="+img_id;  
				
                $.ajax({
                        url: base_URL+"/product/delete_image_ajax",
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
                                               
                        },
                        success: function(data){
                            result = $.parseJSON(data);
                            $(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Updated!",
                                text: "The image has been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true);  
								$("#product_photo_preview_"+img_id).parent().find('.product_up input').val('');
								$("#product_photo_preview_"+img_id).parent().find('.product_up').show();
								$("#product_photo_preview_"+img_id).hide();
                            });
                           
                        },
                        complete: function(){
                       
                       },
                });
                
            }); 
	   }
	   else
	   {
		   $(this).parent().parent().find('.product_up input').val('');
		   $(this).parent().parent().find('.product_up').show();
		   $(this).parent().hide();
	   }
	   
       
    });
    //////

    $("body").on("click",".add_group_modal",function(e){
       
        rander_cate_add_view('g');        
        // $('#add_group_modal_view').modal({
        //     backdrop: 'static',
        //     keyboard: false
        // });
    });
    $("body").on("click",".add_category_modal",function(e){
        rander_cate_add_view('c');      
        // $('#add_category_modal_view').modal({
        //     backdrop: 'static',
        //     keyboard: false
        // });
    });

    $("body").on("click","#submit_group_confirm",function(e){
        
        var cat_name_obj=$("#cat_name");
        var parent_id=$("#parent_id").val();
        
        //alert(parent_id);
        if(parent_id=="")
        {
            $("#parent_id_error").html("Select group.");            
            return false;
        }
        else
        {
            $("#parent_id_error").html("");
        }

        if(cat_name_obj.val()=="")
        {
            $("#cat_name_error").html("Enter name.");
            cat_name_obj.focus();
            return false;
        }
        else
        {
            $("#cat_name_error").html("");
        }
        $(this).attr("disabled",true);
        var cat_name=cat_name_obj.val();
        cat_name_obj.val('');
        category_add(parent_id,cat_name);
    });

    

    $("body").on("change","#group_id",function(e){
        var parent_id=$(this).val();
        rander_option_category(parent_id,'');
		
    });
	
	
	
	
	


    $("body").on("click","#product_submit_confirm",function(e){
        e.preventDefault();
		//alert($("#vendors").val());return false;
        var base_URL = $("#base_url").val();
		var product_id=$("#product_id").val();
        var group_id_obj=$("#group_id");
        var cate_id_obj=$("#cate_id");
        var name_obj=$("#name");
        var code_obj=$("#code");
        var hsn_code_obj=$("#hsn_code");
        var gst_percentage_obj=$("#gst_percentage");
        var price_obj=$("#price");
        var currency_type_obj=$("#currency_type");
        var unit_obj=$("#unit");
        var unit_type_obj=$("#unit_type");
        var youtube_video_obj=$("#youtube_video");
        var description_obj=$("#description");
        var description_word_count=$("#description_word_count").val();
        var long_description_obj=$("#long_description");
		var long_description_word_count=$("#long_description_word_count").val();
        var product_available_for_obj=$("#product_available_for");
        var minimum_order_quantity_obj=$("#minimum_order_quantity");
        var vendor_productvarient_tag_obj=$("#vendor_productvarient_tag");
		//alert(long_description_word_count)
		
        if(group_id_obj.val()=='')
        {
            group_id_obj.addClass('error_input');
            $("#group_id_error").html('Please select group');
            group_id_obj.focus();
            return false;
        }
        else
        {
            group_id_obj.removeClass('error_input');
            $("#group_id_error").html('');
        }

        if(cate_id_obj.val()=='')
        {
            cate_id_obj.addClass('error_input');
            $("#cate_id_error").html('Please select category');
            cate_id_obj.focus();
            return false;
        }
        else
        {
            cate_id_obj.removeClass('error_input');
            $("#cate_id_error").html('');
        }

        if(name_obj.val()=='')
        {
            name_obj.addClass('error_input');
            $("#name_error").html('Please enter name');
            name_obj.focus();
            return false;
        }
        else
        {
            name_obj.removeClass('error_input');
            $("#name_error").html('');
        }

        // if(code_obj.val()=='')
        // {
        //     code_obj.addClass('error_input');
        //     $("#code_error").html('Please enter code');
        //     code_obj.focus();
        //     return false;
        // }
        // else
        // {
        //     code_obj.removeClass('error_input');
        //     $("#code_error").html('');
        // }
		/*
        if(hsn_code_obj.val()=='')
        {
            hsn_code_obj.addClass('error_input');
            $("#hsn_code_error").html('Please enter HSN code');
            hsn_code_obj.focus();
            return false;
        }
        else
        {
            hsn_code_obj.removeClass('error_input');
            $("#hsn_code_error").html('');
        }
		*/

        // if(gst_percentage_obj.val()=='')
        // {
        //     gst_percentage_obj.addClass('error_input');
        //     $("#hsn_code_error").html('Please enter GST%');
        //     gst_percentage_obj.focus();
        //     return false;
        // }
        // else
        // {
        //     gst_percentage_obj.removeClass('error_input');
        //     $("#hsn_code_error").html('');
        // }

        if(price_obj.val()=='')
        {
            price_obj.addClass('error_input');
            $("#price_error").html('Please enter selling price');
            price_obj.focus();
            return false;
        }
        else
        {
            price_obj.removeClass('error_input');
            $("#price_error").html('');
        }

        if(currency_type_obj.val()=='')
        {
            currency_type_obj.addClass('error_input');
            $("#currency_type_error").html('Please select currency');
            currency_type_obj.focus();
            return false;
        }
        else
        {
            currency_type_obj.removeClass('error_input');
            $("#currency_type_error").html('');
        }

        if(unit_obj.val()=='')
        {
            unit_obj.addClass('error_input');
            $("#unit_error").html('Please enter unit');
            unit_obj.focus();
            return false;
        }
        else
        {
            unit_obj.removeClass('error_input');
            $("#unit_error").html('');
        }

        if(unit_type_obj.val()=='')
        {
            unit_type_obj.addClass('error_input');
            $("#unit_type_error").html('Please select type');
            unit_type_obj.focus();
            return false;
        }
        else
        {
            unit_type_obj.removeClass('error_input');
            $("#unit_type_error").html('');
        }

        // if(youtube_video_obj.val()=='')
        // {
        //     youtube_video_obj.addClass('error_input');
        //     $("#youtube_video_error").html('Please enter youtube video URL');
        //     youtube_video_obj.focus();
        //     return false;
        // }
        // else
        // {
        //     youtube_video_obj.removeClass('error_input');
        //     $("#youtube_video_error").html('');
        // }

        // if(description_obj.val()=='')
        // {
        //     description_obj.addClass('error_input');
        //     $("#description_error").html('Please enter small product description');
        //     description_obj.focus();
        //     return false;
        // }
        // else
        // {
        //     description_obj.removeClass('error_input');
        //     $("#description_error").html('');
        // }

        // if(long_description_obj.val()=='')
        // {
        //     long_description_obj.addClass('error_input');
        //     $("#long_description_error").html('Please enter long product description');
        //     long_description_obj.focus();
        //     return false;
        // }
        // else
        // {
        //     long_description_obj.removeClass('error_input');
        //     $("#long_description_error").html('');
        // }
        // if(description_word_count>100)
        // {
        // long_description_obj.addClass('error_input');
        // $("#description_error").html('Word count should not be greater than 100.');
        // //long_description_obj.focus();
        // return false;
        // }
        // else
        // {
        // long_description_obj.removeClass('error_input');
        // $("#description_error").html('');
        // }

        // if(long_description_word_count>1000)
        // {
        // long_description_obj.addClass('error_input');
        // $("#long_description_error").html('Word count should not be greater than 1000.');
        // //long_description_obj.focus();
        // return false;
        // }
        // else
        // {
        // long_description_obj.removeClass('error_input');
        // $("#long_description_error").html('');
        // }
		
		
        if(product_available_for_obj.val()=='')
        {
            product_available_for_obj.addClass('error_input');
            $("#product_available_for_error").html('Please select available for');
            product_available_for_obj.focus();
            return false;
        }
        else
        {
            product_available_for_obj.removeClass('error_input');
            $("#product_available_for_error").html('');
        }
		/*
        if(minimum_order_quantity_obj.val()=='')
        {
            minimum_order_quantity_obj.addClass('error_input');
            $("#minimum_order_quantity_error").html('Please enter order quantity');
            minimum_order_quantity_obj.focus();
            return false;
        }
        else
        {
            minimum_order_quantity_obj.removeClass('error_input');
            $("#minimum_order_quantity_error").html('');
        }
		*/

        // if(vendor_productvarient_tag_obj.val()=='')
        // {
        //     vendor_productvarient_tag_obj.addClass('error_input');
        //     $("#vendor_productvarient_tag_error").html('Please select vendor');
        //     vendor_productvarient_tag_obj.focus();
        //     return false;
        // }
        // else
        // {
        //     vendor_productvarient_tag_obj.removeClass('error_input');
        //     $("#vendor_productvarient_tag_error").html('');
        // }
        
        $.ajax({
            url: base_URL+"product/add_edit_ajax",
            data: new FormData($('#product_add_edit_form')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
              $("#product_submit_confirm").attr("disabled",true);
            },
            success: function(data){
			  $("#product_submit_confirm").attr("disabled",false);
              result = $.parseJSON(data);
              // console.log(result.msg);
            //   alert(result.status);
              
              if(result.status=='success')
              {
                // console.log(result.postdata);
                  swal({
                        title: "Success!",
                        text: result.msg,
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
						
						if(product_id=='')
						{
							window.location.href=base_URL+"product/add";  
						}
						else
						{
							//window.location.href=base_URL+"product/edit/"+product_id; 
                            window.location.href=base_URL+"product/manage/";  
						}
                                              
                    });
              }   
              else
              {
                swal({
                    title: "Oops!",
                    text: result.msg,
                     type: "error",
                    confirmButtonText: "ok",
                    allowOutsideClick: "false"
                }, function () { 
                                       
                });
              }
            }
          }); 

    });

    
    // ==================================================================
    // vendor tagged functionality start
    $("body").on("input", ".search_vendor_by_keyword", function(e) {
        var base_url = $("#base_url").val();   
        var existing_vendors=$("#vendors").val();      
        var search_keyword = $('.search_vendor_by_keyword').val();
        var data="existing_vendors="+existing_vendors+"&search_keyword="+search_keyword;
        
        $.ajax({
            url: base_url + "product/selectVendors",
            type: "POST",
            data: data,
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
    $("body").on("click", "#select-vendor-add-product-submit", function(){
        var pid=$('#vdraddfromProductId').val();
        var vendors="";
        var html="";
        var error_flag=0;
        $('.vndr').each(function(){
            var vdr=$(this);
            if(vdr.prop("checked") == true){
                var id=vdr.val();
                var price=$('#'+id+'_price').val();
                var currency=$('#'+id+'_currency').val();
                var unit=$('#'+id+'_unit').val();
                var unit_type=$('#'+id+'_unit_type').val();
                if(price=='')
                {
                    $('#'+id+'_price_error').html("Enter price."); 
                    error_flag=1;                  
                }
                else
                {
                    $('#'+id+'_price_error').html("");
                }
                if(currency=='')
                {
                    $('#'+id+'_currency_error').html("Select currency."); 
                    error_flag=1;                  
                }
                else
                {
                    $('#'+id+'_currency_error').html("");
                }

                if(unit=='')
                {
                    $('#'+id+'_unit_error').html("Enter unit."); 
                    error_flag=1;                  
                }
                else
                {
                    $('#'+id+'_unit_error').html("");
                }

                if(unit_type=='')
                {
                    $('#'+id+'_unit_type_error').html("Select type.");
                    error_flag=1;                  
                }
                else
                {
                    $('#'+id+'_unit_type_error').html("");
                }


                vendors+='@'+id+'_'+price+'_'+currency+'_'+unit+'_'+unit_type+'^';
                if(!pid){
                    var name=vdr.attr("data-val");
                    html+='<div class="rmVdr_btn" id="rmVdr_'+id+'">';
                        html+='<div class="form-group">';
                            html+='<label class="label-btn"><i class="fa fa-check tick-icon" aria-hidden="true"></i>'+name+'<i class="fa fa-trash-o rm_vdr" data-id="'+id+'" aria-hidden="true"></i></label>';
                        html+='</div>';
                    html+='</div>'
                }
            }
        });
        
        if(error_flag==1 || vendors=='')
        { 
            if(vendors=='')
            {
                swal({
                    title: "Oops!",
                    text: "Please select a vendor.",
                    type: "warning",
                    confirmButtonText: "OK",
                    allowOutsideClick: "false"
                });                
            }
            return false;            
        }
        

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

    $("body").on("click", ".rm_vdr", function(){
		
		var id=$(this).attr("data-id");
        var new_vendors="";
        var vendors=$('#vendors').val().split('^');
			
		swal({
			title: "Confirmation",
			text: "Are you sure? Do you want to remove this vendor!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: 'btn-warning',
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function () { 
			
            $.each( vendors, function( index, value ) {
                var res=value.includes('@'+id);
                if(res){
                    $('#rmVdr_'+id).remove();
                }else{
                    new_vendors+=value+'^';
                }
            });
			//alert(new_vendors)
			$('#vendors').val(''); 
            $('#vendors').val(new_vendors.slice(0, -1)); 			
		});
		/*	
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
		*/
    });
    // vendor tagged functionality end
    // ==================================================================
    
	
	// ===========================================================
	// word count	

	
	$("#description").on('keyup', function() {
        var words = this.value.match(/\S+/g).length;
        if (words > 100) {
            // Split the string on first 200 words and rejoin on spaces
            var trimmed = $(this).val().split(/\s+/, 100).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count').text(words);
            $('#word_left').text(100-words);
        }
    });
	
	$("#long_description").on('keyup', function() {
        var words = this.value.match(/\S+/g).length;
        if (words > 1000) {
            // Split the string on first 200 words and rejoin on spaces
            var trimmed = $(this).val().split(/\s+/, 1000).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count2').text(words);
            $('#word_left2').text(1000-words);
        }
    });
	
	// word count
	// ===========================================================
	
	$("body").on("focusout",".generate_code",function(e){
		var name=$(this).val();
		var code_str=name.substring(0,3)+Math.floor((Math.random() * 9999999) + 1);
		$("#code").val(code_str);
	});
});

function rander_cate_add_view(view_type)
{
    var base_URL = $("#base_url").val();   
    var data="view_type="+view_type;
    
    $.ajax({
        url: base_URL+"product/rander_group_cat_add_view",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {            
        },
        success: function(data){
            result = $.parseJSON(data); 
            //alert(result.html)
            $("#add_group_cat_title").html((view_type=='g')?"Add Group":"Add Category");
            $("#add_group_cat_view_div").html(result.html);
            $('#add_group_cat_modal_view').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });
}

function category_add(parent_id,cat_name)
{
    var base_URL = $("#base_url").val();
    var data="parent_id="+parent_id+"&cat_name="+cat_name;
    //alert(data); return false;
    $.ajax({
        url: base_URL+"product/add_group_category",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {
            
        },
        success: function(data){
            result = $.parseJSON(data);
            //alert(result.status); 
            //return false;
            //alert(result.status);                  
            swal({
                title: "Added!",
                text: result.msg,
                    type: "success",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            }, function () {                 
                //location.reload(true);   
                if(parent_id==0){
                    rander_option_group(parent_id,'');
                } 
                else{
                    //rander_option('cate_id',parent_id);
                }                            
                
                //$("#group_id").html(result.group_option_rander);
                $(".submit_group_confirm").attr("disabled",false);
                $('#add_group_cat_modal_view').modal('hide');
            });
        }
    });
}




// ==================================================================
// vendor tagged functionality start
function select_vendor_product_lead(pid) 
{
    $('#vdraddfromProductId').val(pid);
    var existing_vendors=$("#vendors").val();    
    var base_url=$("#base_url").val();
    var data="existing_vendors="+existing_vendors;
    $.ajax({
        url: base_url+'product/selectVendors',
        data: data,
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
// vendor tagged functionality end
// ==================================================================




