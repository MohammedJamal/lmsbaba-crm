$(document).ready(function(){
	    
    $("body").on("click", ".upload_csv", function(e) {
        //var id = $(this).attr('data-id');
        $("#upload_csv_modal").modal({
            backdrop: 'static',
            keyboard: false,
            //callback: fn_rander_company_details(id)
        });
    });

    $("body").on("click",".get_error_log",function(e){
        var base_url = $("#base_url").val();
        var uploaded_csv_file_name=$("#uploaded_csv_file_name2").val();
        data='uploaded_csv_file_name='+uploaded_csv_file_name;
        //alert(data); return false;
        $.ajax({
              url: base_url + "product/get_upload_csv_error_log_ajax",
              data: data,
              cache: false,
              method: 'POST',
              dataType: "html", 
              beforeSend: function( xhr ) {
                $("#upload_csv_modal").css("display","none");
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
                  // $("#upload_csv_modal").css("display","block");
              },           
              success: function(data){
                  result = $.parseJSON(data); 
                  $("#upload_csv_modal").modal('hide');
                  $("#csv_error_log_content").html(result.html);
                  $('#csv_error_log_modal').modal({
                      backdrop: 'static',
                      keyboard: false
                  });
                  //alert(1);
                  //$('.error-table-holder').doubleScroll();
              }
          });
    });
  
    $("#csv_error_log_modal").on('hide.bs.modal', function(){
        $('#upload_csv_modal').modal({
          backdrop: 'static',
          keyboard: false
      });
    });


	if($("#filter_search_str").val())
	{
		$("#filter_aproved").val('');
		$("#selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied: </b></span> By product name/code "'+$("#filter_search_str").val().replace(new RegExp(",", "g"), ", ")+'" <a href="JavaScript:void(0);" class="text-danger" id="product_filter_reload"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>');
		$("#status_tab").hide();
        $("#status").val('');
	}
	$("body").on("click","#product_filter_reload",function(e){
        //location.reload(true);
		var base_URL = $("#base_url").val();
		window.location.href=base_URL+"product/manage";
		
	});
    /* ########## FOR FIRST LIME LOADING START ############# */   
        load(1);    
        //load(1,'grid');  

		// ==================================
		// initilasize		
		rander_option_group(0,'');
		$("body").on("change","#group_id",function(e){
			var parent_id=$(this).val();
			rander_option_category(parent_id,'');
			
		});
    /* ########## FOR FIRST LIME LOADING END ############# */
	


    $(document).on('click', '.change_status', function (e) {
        
        
        var curr_status = $(this).attr('data-curstatus');     
        var id = $(this).attr('data-id');

        //Warning Message            
            swal({
                title: "Confirmation",
                text: "Do you want to change the current status?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, change it!",
                closeOnConfirm: true
            }, function () {

                if(curr_status==0)
                {
                    $("#product_disabled_reason_btn").attr("data-id",id);
                    $("#product_disabled_reason_btn").attr("data-curstatus",curr_status);
                    $('#product_disabled_reason_modal_div').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                }
                else
                {
                   change_status(id,curr_status,''); 
                }                
            });
       
    });
    $("body").on("click","#product_disabled_reason_btn",function(e){
        var id_tmp=$(this).attr("data-id");
        var curr_status_tmp=$(this).attr("data-curstatus");
        var disabled_reason=$("input:radio[name=status_disabled_reason]:checked").val();
        //alert(id+" / "+curr_status_tmp+" / "+disabled_reason);
        $('#product_disabled_reason_modal_div').modal('hide');
        $("input:radio[name=status_disabled_reason]").attr("checked",false);
        change_status(id_tmp,curr_status_tmp,disabled_reason); 
    });
    function change_status(id,curr_status,disabled_reason='')
    {
        var base_URL = $("#base_url").val();
        var data="id="+id+"&curr_status="+curr_status+"&disabled_reason="+disabled_reason;  

        $.ajax({
                url: base_URL+"product/change_status",
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
                success: function(data){
                    result = $.parseJSON(data);
                    //$(".preloader").hide();
                    //alert(result.status);
                    swal({
                        title: "Updated!",
                        text: "The status has been changed",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                        $("#approved_count_div").html(result.approved_count);
                        $("#disabled_count_div").html(result.disabled_count);
                        //location.reload(true); 
						var curr_page=$("#current_page_number").val();
                        load(curr_page);
                    });
                   
                },
                complete: function(){
					$.unblockUI();
               },
        });
    }
    // -----------------------------
    // DELETE FUNCTIONALITY
    

    $("body").on("click",".del_btn",function(e){
        var id = $(this).attr('data-id');
        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                var data = 'id='+id;               
                $.ajax({
                        url: base_url+"product/delete_product",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
                        beforeSend: function( xhr ) { 
                        $("#preloader").css('display','block');                           
                        },
                        success: function(data){
                            result = $.parseJSON(data);
                            $(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Deleted!",
                                text: "The record(s) have been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                load(1);
                            });
                           
                        },
                        complete: function(){
                        $("#preloader").css('display','none');
                       },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
    });

    

    // DELETE FUNCTIONALITY
    // -----------------------------
    
    
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH ################# */
    /* ######################################################################## */
    function validate()
    {        
        return true;
    }
    
    // AJAX SEARCH START
    $(document).on('click', '#submit', function (e) {
			e.preventDefault();
			var base_URL      = $("#base_url").val();			
            /* Validation Code */
            var r = validate();
            if(r === false) {
                    return false;
            }
            else {
                    load(1);
                    return false;
            }
            /* Validation code end */
            
    });
    
    $(document).on('click', '.myclass', function (e) { 
           e.preventDefault();
           var vt=($(this).attr('data-viewtype')=='grid')?'grid':'list';
           var str = $(this).attr('href'); 
           var res = str.split("/");
           var cur_page = res[1];
           $("#page_number").val(cur_page);
           if(cur_page) {
                load(cur_page);
            }
            else {
                load(1);
            }

            // $("input:checkbox[id=set_all]").prop('checked', false);
            // $("input:checkbox[class=set_individual]").prop('checked', false);
            // $("#checked_ids").val('');
	});
    // AJAX SEARCH END
    
    
    // AJAX LOAD START
    function load() 
	{
        //return;
        //var page_num=page;
		$("#current_page_number").val(page);
        var base_URL     = $("#base_url").val();  
        var search_product   = '';  
        var search_price     = '';
        var view_type=$("#view_type").val();
        var status=$("#status").val();
        
		var filter_search_str=$("#filter_search_str").val();
        var filter_aproved=$("#filter_aproved").val();
        var filter_disabled=$("#filter_disabled").val();
        var filter_disabled_reason=$("#filter_disabled_reason").val();

		var filter_group_id=$("#filter_group_id").val();
		var filter_cate_id=$("#filter_cate_id").val();

        var filter_product_available_for=$("#filter_product_available_for").val();

        var filter_with_gst=$("#filter_with_gst").val();
        var filter_with_hsn_code=$("#filter_with_hsn_code").val();

        var filter_with_image=$("#filter_with_image").val();    
        var filter_with_brochure=$("#filter_with_brochure").val();
        var filter_with_youtube_video=$("#filter_with_youtube_video").val();        
        
        var filter_sort_by=$("#filter_sort_by").val();
        var page=$("#page_number").val();
        
        var data = "page="+page+"&search_product="+search_product+"&view_type="+view_type+"&status="+status+"&filter_sort_by="+filter_sort_by+"&filter_product_available_for="+filter_product_available_for+"&filter_with_image="+filter_with_image+"&filter_with_brochure="+filter_with_brochure+"&filter_with_youtube_video="+filter_with_youtube_video+"&filter_with_gst="+filter_with_gst+"&filter_with_hsn_code="+filter_with_hsn_code+"&filter_aproved="+filter_aproved+"&filter_disabled="+filter_disabled+"&filter_disabled_reason="+filter_disabled_reason+"&filter_group_id="+filter_group_id+"&filter_cate_id="+filter_cate_id+"&filter_search_str="+filter_search_str;
        // alert(data); //return false;
        $.ajax({
            url: base_URL+"product/get_list_ajax/"+page,
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
             beforeSend: function( xhr ) {                
                addLoader('.acc_holder');
              },
           success:function(res){ 
               result = $.parseJSON(res);
               //$(".preloader").hide();
               //alert(result.table);
               $("#approved_count_div").html(result.approved_count);
               $("#disabled_count_div").html(result.disabled_count);
               if(view_type=='grid')
               {
                    
                    $("#view_table").addClass("datatable_grid");
                    //$("#thead").html('<tr><th>#</th><th>#</th></tr>');
					$("#thead_list").hide();
					$("#thead_grid").show();
					

               }
               else
               {
                    
                    $("#view_table").removeClass("datatable_grid");
					$("#thead_grid").hide();
					$("#thead_list").show();
					
                    //$("#thead").html('<tr><th class="sort_order" data-field="id" data-orderby="asc" >#ID</th><th class="sort_order" data-field="name" data-orderby="asc">Product Name</th><th class="sort_order" data-field="code" data-orderby="asc">Code</th><th class="sort_order" data-field="price" data-orderby="asc">Sales Price</th><th class="sort_order" data-field="unit" data-orderby="asc">Unit</th><th class="sort_order" data-field="unit_type" data-orderby="asc">Unit Type</th><th class="sort_order" data-field="gst_percentage" data-orderby="asc">GST</th><th >Photo</th><th>Brochure</th><th>Action</th></tr>')
               }
               //alert(3);
               
               $("#tcontent").html(result.table);
               $("#page").html(result.page);               
               if(view_type=='grid'){
                    //alert('grid')
                    /////////////
                    gridfunction();
                    /////////////
               }
               $('[data-toggle="tooltip"]').tooltipster();
           },
           complete: function(){
            removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
    }
    // AJAX LOAD END
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH END ############# */
    /* ######################################################################## */
    /////loader function
    // var $ddlBtn = $("#filter_dropdown .filter-btn");
    // $ddlBtn.on("click", function(){
    //   var expanded = /true/i.test($ddlBtn.attr("aria-expanded"));
    //   $ddlBtn
    //     .attr("aria-expanded", !expanded)
    //     .siblings(".dropdown-menu").toggleClass("show")
    //     .parent().toggleClass("show");
    // });
    // $('#filter a').click(function(event){
    //     event.preventDefault();
    //     //alert(1);
    //     $('#filter_dropdown').removeClass('show');
    //     $('#filter_dropdown').removeClass('open');
    //     $('#filter_dropdown .dropdown-menu').removeClass('show');
    //     $('#filter_dropdown .dropdown-toggle').removeAttr('aria-expanded');
    //     $('.dd_overlay').hide();
    // });
    // $('#filter_dropdown').on('show.bs.dropdown', function () {
    //     /////
    //     //$('#filter_dropdown .dropdown-menu').css({'opacity':0})
    //     var body = document.body,
    //     html = document.documentElement;

    //     var height = Math.max( body.scrollHeight, body.offsetHeight, 
    //                    html.clientHeight, html.scrollHeight, html.offsetHeight );
    //     var filter_width = $('#filter_dropdown .dropdown-menu').width();
    //     var filter_height = $('#filter_dropdown .dropdown-menu').height();
    //     var button_width = 144;
    //     var button_height = 42;
    //     // do something…
    //     var filter_top = $('#filter_dropdown').offset().top;
    //     var filter_left = $('#filter_dropdown').offset().left;
    //     //over 1
    //     $('.dd_overlay#over_top').css({'height':height});
    //     $('.dd_overlay').show();
    //     return;
    //     //over 2
    //     var getl = (filter_left+button_width)-filter_width;
    //     var geth = height-(filter_top+button_height)
    //     $('.dd_overlay#over_left').css({'top':filter_top+button_height, 'width':getl, 'height':geth});
    //     //over 3
    //     var three_w = $(document).width()-(filter_left+button_width)
    //     $('.dd_overlay#over_right').css({'top':filter_top+button_height, 'left':filter_left+button_width, 'width':three_w, 'height':geth});
    //     //over 4
    //     var four_top = (filter_top+button_height)+filter_height;
    //     var four_h = height-four_top;
    //     $('.dd_overlay#over_bottom').css({'width':filter_width, 'top':four_top, 'left':getl, 'height':four_h});
    //     ////
    //     $('.dd_overlay').show();
    //     //$('.dd_overlay').fadeIn('300');
    //     //$('#filter_dropdown .dropdown-menu').css({'opacity':1});
    // })
    // $('#filter_dropdown').on('hide.bs.dropdown', function (e) {
    //     //$('.dd_overlay').hide();
        
    // })
    
	$("body").on("click",".sort_order",function(e){
		var tmp_field=$(this).attr('data-field');
		var curr_orderby=$(this).attr('data-orderby');
		var new_orderby=(curr_orderby=='asc')?'desc':'asc';
		$(this).attr('data-orderby',new_orderby);
		$(".sort_order").removeClass('asc');
		$(".sort_order").removeClass('desc');
		$(this).addClass(curr_orderby);
		$("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
		load(1);
		//alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
	});
    function addLoader(getele){
        //alert(1)
        var gets = 100;
        if ($(window).scrollTop() > 200) {
            gets = $(window).scrollTop();
        }
        
        //alert(gets)
        var loaderhtml = '<div class="loader" style="background-position: 50% '+gets+'px"></div>';
        $(getele).css({'position':'relative', 'overflow':'hidden', 'min-height': '300px'}).prepend(loaderhtml);
        $('.loader').fadeIn('fast', function() {
            // Animation complete.
            //$(getele).css({'min-height': 'inherit'})
        });
    }
    function removeLoader(){
        //alert(1)
        
        $('.loader').fadeOut('fast', function() {
            // Animation complete.
            $('.loader').remove()
        });
    }
    // ------------------------------------------------------------------------
    // GRID VIEW FUNCTIONALITY


    $("body").on("change", "#fileupload_pdf", function(e) {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return;
        if (files[0].type == 'application/pdf') {
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function() {

                $("#PreviewPdf").html(files[0].name + '<span class="del_pdf"><i class="fa fa-trash" area-hidden="true" onclick="remove_pdf()"></i></span>');
                $("#PreviewPdf").show();
            }
        } else {
            swal('Upload a PDF');
        }
    });

    $("body").on("click", ".delete_brochure", function(e) {
        var id = $(this).attr('data-id');
        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "Want to delete the brochure!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                var data = 'id='+id;      
                        
                $.ajax({
                        url: base_url+"product/delete_brochure",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
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
                            //$(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Deleted!",
                                text: "The brochure has been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                load(1);
                            });
                           
                        },
                        complete: function(){
                        //$("#preloader").css('display','none');
                       },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "No record to delete.");            
        };
        
    });

    $("body").on("click", ".view_youtube_video", function(e) {
        var product_video = $(this).attr('data-content');
        //alert(product_video);
        var embed_code=youtube_parser(product_video);
        if(embed_code==false)
        {
            embed_code=product_video;
        }
        var viframe = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+embed_code+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
        $('#productVideoModal .modal-body .video_holder').html(viframe);
        //$('#productVideoModal').modal('show');
        $('#productVideoModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("body").on("click", ".delete_youtube_video", function(e) {
        var id = $(this).attr('data-id');
        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "Want to delete the youtube video link!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                var data = 'id='+id;      
                        
                $.ajax({
                        url: base_url+"product/delete_youtube_video",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
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
                            //$(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Deleted!",
                                text: "The youtube video has been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                load(1);
                            });
                           
                        },
                        complete: function(){
                        //$("#preloader").css('display','none');
                       },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "No record to delete.");            
        };
        
    });


    $("body").on("click","#btnContainer .get_view",function(e){
      $(".get_view").removeClass("active");
      $(this).addClass('active');
      var view_type=$(this).attr('data-target');
      $("#view_type").val(view_type);
      // $("#filter_list").hide();
      // $("#filter_grid").hide();
      // if(view_type=='list')
      // {
      //   $("#filter_list").show();
      // }
      // else
      // {
      //   $("#filter_grid").show();
      // }
      //alert(view_type)
      load(1);
    });

    $("body").on("click", ".get_detail_modal", function(e) {
        var id = $(this).attr('data-id');
        $("#product_details").modal({
            backdrop: 'static',
            keyboard: false,
            callback: fn_rander_product_details(id)
        });
    });

    $('#status_tab >li >a').click(function (e) {
        //e.preventDefault(); 
        $(".nav li").removeClass("active");
        $(this).parent().addClass("active");
        var target_status=$(this).attr("data-status");  
        $("#status").val(target_status); 
		$("#filter_aproved").val((target_status==0)?'Y':''); 
		$("#filter_disabled").val((target_status==1)?'Y':''); 
        $('#tabContent1').show();         
        load(1);
        //$(this).tab('show');
        //var tabContent = '#tabContent' + this.id;       
        //$('#tabContent1').hide();
        //$('#tabContent2').hide();
        //$(tabContent).show();
    });   

    


    $("body").on("click",".edit_content_view",function(e){              
        //$(this).html('<i class="fa fa-clock-o" aria-hidden="true"></i>');
        
        var product_id = $(this).attr('data-id');
        var editsection = $(this).attr('data-editsection');
        var base_URL     = $("#base_url").val();        
        var data = "product_id="+product_id+"&editsection="+editsection;       
        
        $.ajax({
            url: base_URL+"product/get_product_edit_popup_view",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
            beforeSend: function( xhr ) {
				//addLoader('.acc_holder');
            },
            success:function(res){ 
               result = $.parseJSON(res);               
               //alert(result.html);               
               $("#product_edit_rander").html(result.html);
               $('#productContentsModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
               //$('#productContentsModal').modal('show');
               if(editsection=='youtube')
               {
                $("#productContentsModalTitle").html('Add/Edit Video URL');
               }
               else if(editsection=='brochures')
               {
                $("#productContentsModalTitle").html('Add/Edit Product Brochure');
               }
               else
               {
                $("#productContentsModalTitle").html('Edit Product');
               }
               var maxShortLength = 100;
                $('#p_short_description').keyup(function() {
                  var textlen = maxShortLength - $(this).val().length;
                  $(this).parent().find('.rchars').text(textlen+' word(s) remaining');
                });
                var maxLongLength = 200;
                $('#p_long_description').keyup(function() {
                  var textlen = maxLongLength - $(this).val().length;
                  $(this).parent().find('.rchars').text(textlen+' word(s) remaining');
                });
                ///////

            },
            complete: function(){                
            },
            error: function(response) {
            //alert('Error'+response.table);
            }
        });
    });
    $('#productContentsModal').on('hide.bs.modal', function (e) {
        // do something...
        $("#product_edit_rander").removeAttr('style');
    })
    $(document).on('click', '#confirm_submit', function (e) {
        e.preventDefault();
        //alert('submit');
        //return false;
        var base_URL              = $("#base_url").val();   
        var p_editsection         = $("#p_editsection").val();
        var p_name                = $("#p_name").val();
        var p_short_description   = $("#p_short_description").val();
        var p_long_description    = $("#p_long_description").val();
        var p_youtube_video       = $("#p_youtube_video").val();    

        if(p_editsection=='youtube')
        {
            if(p_youtube_video=='')
            {                
                $("#p_youtube_video_error").html('youtube url should not be blank') ;         
                return false;
            }
            else
            {
                var getval = validateYouTubeUrl(p_youtube_video);        
                if(getval == 'not valid')
                {
                    $("#p_youtube_video_error").html('youtube url not valid');
                    return false;
                }
                else
                {
                    $("#p_youtube_video_error").html('');
                }
            }            
        }
        else if(p_editsection=='brochures')
        {
            
        }
        else
        {
            var flag='';
            if(p_name=='')
            {
                $("#p_name_error").html('Product name should not be blank.'); 
                flag='error';               
            }
            else
            {
                $("#p_name_error").html('');                
            }

            if(p_short_description=='')
            {
                $("#p_short_description_error").html('Product short description should not be blank.');
                flag='error';
            }
            else
            {
                $("#p_short_description_error").html('');                
            }

            if(p_long_description=='')
            {
                $("#p_long_description_error").html('Product full description should not be blank.');
                flag='error';
            }
            else
            {
                $("#p_long_description_error").html('');                
            }

            if(flag!='')
            {
                return false;
            }
        }

        

        $.ajax({

            url: base_URL+"product/update_product",
            //data: data,
            data: new FormData($('#frmProductEdit')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
                addLoader('#product_edit_rander');
            },
            success: function(data){
                result = $.parseJSON(data);
                //alert(result.status); 
                //return false;
                //alert(result.status);  
                removeLoader();  
                swal({
                    title: "Updated!",
                    text: "The product successfully updated",
                     type: "success",
                    confirmButtonText: "ok",
                    allowOutsideClick: "false"
                }, function () {                 
                    //location.reload(true); 
                    load(1);
                    $('#productContentsModal').modal('hide');
                });
            }
        });
        
    });

    // $("body").on("click",".delete_pic",function(e){
    //     var img_id=$(this).attr("data-imgid");
    //     alert(img_id);
    // });

    gridfunction = function(){
        //////////////////////////////
        //return;
        // $('.cycle-slideshow').cycle({
        //     speed: 600,
        //     manualSpeed: 100
        // });
        //$('.product_outer .product_thumb > ul > li a').click(function(event){
        $(document).on("click",".product_outer .product_thumb > ul > li a",function() {
             event.preventDefault();   
             var index = $(this).attr('data-id');
             //alert(index);
             $(this).parent().parent().find('.active').removeClass('active');
             $(this).parent().addClass('active');
             var target =$(this).parent().parent().parent().attr('data-target')   
             $('#'+target).cycle('goto', index);
        });
        //$('input[type="file"]').on('change', function() {
        $(document).on('change','.upload_image',function() {
            var files = !!this.files ? this.files : [];
            var target = $(this).parent().parent().parent().parent().parent().attr('data-target');
            var pid = $(this).parent().parent().parent().parent().parent().attr('data-id');
            var index = $(this).parent().parent().parent().index();
            //$(this).trigger("selected");
            //addLoader('.product_pic_div_'+pid);
            //alert(1);
            if (!files.length || !window.FileReader) return;
            if (/^image/.test(files[0].type)) 
            {
                addLoader('.acc_holder');
                //  Allow only image upload
                var getcon = $(this);
                var ReaderObj = new FileReader(); // Create instance of the FileReader
                ReaderObj.readAsDataURL(files[0]); // read the file uploaded
                ReaderObj.onloadend = function() 
                {
                    //console.log(getcon);
                    var result_obj = this.result;
                    //alert(pid+'/'+index)
                    //alert('#frmProductEdit_'+pid+'_'+index);
                    //$("#upload_"+pid+"_"+index).trigger("click");
                    // --------------------------------------------
                    var base_URL= $("#base_url").val();
                    $.ajax({

                        url: base_URL+"product/update_product_image",
                        //url: base_URL+"product/update_product",
                        //data: data,
                        data: new FormData($('#frmProductEdit_'+pid+'_'+index)[0]),
                        cache: false,
                        method: 'POST',
                        dataType: "html",
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend: function( xhr ) {
                            // addLoader('.slide_'+pid);
                        },
                        success: function(data){
                            result = $.parseJSON(data);
                            //alert(result.status); 
                            //return false;
                            //alert(result.msg);    
                            swal({
                                title: "Updated!",
                                text: "The image successfully updated",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () {                 
                                //location.reload(true); 
                                //removeLoader();
                                load(1);
                                //$('#productContentsModal').modal('hide');
                            });
                        }
                    });

                    // --------------------------------------------

                    var newcount = 0;
                    var slide_pic = '';
                    getcon.parent().parent().parent().parent().find('li').each(function( index ) {
                        
                        $(this).children('a').each(function( index ) {
                            $(this).attr('data-id',newcount);
                            var name_src = $(this).find('img').attr('src');
                            slide_pic += '<img data-count="'+newcount+'" src="' + name_src + '" >';
                            newcount++;
                        });
                        
                    });
                    slide_pic += '<img data-count="'+newcount+'" src="' + result_obj + '" >';
                    //alert(slide_pic)
                    getcon.parent().parent().html('<a href="#" data-id="'+newcount+'"><img src="' + result_obj + '" ></a>');
                    var new_image = '<img src="' + result_obj + '" ></a>';
                    $('#'+target).cycle('add', new_image);
                    //$('#'+target).cycle('remove', 0);
                    $('#'+target).cycle('destroy');
                    $('#'+target).html(slide_pic);
                    $('#'+target).cycle({
                        loop:false,
                        fx:'fade',
                        timeout: 0
                    });
                    ////////

                    ////////
                    //$('#'+target).cycle('reinit');
                }

                
            } 
            else 
            {
                console.log('Upload an image');
            }
        });
        $('.cycle-slideshow').cycle({
            loop:false,
            fx:'fade',
            timeout: 0
        });

        ////
        $(document).on("click","a.delete_pic",function() {
             event.preventDefault();   
             var index = $(this).parent().find('.cycle-slide-active').attr('data-count');
             var target = $(this).parent().find('.cycle-slideshow').attr('id');
             var pid = $(this).parent().find('.cycle-slide-active').attr('data-id');
             var imgid = $(this).parent().find('.cycle-slide-active').attr('data-imgid');

             
            //alert(pid+' / '+imgid);return false;
            // -------------------------------------------
            var base_url=$("#base_url").val();
            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this image!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+imgid;   
                //alert(data); return false;            
                $.ajax({
                        url: base_url+"product/delete_existing_image",
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
                            //addLoader('.slide_'+pid);  
                            addLoader('.acc_holder');                        
                        },
                        success: function(data){
                            result = $.parseJSON(data);
                            //$(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Deleted!",
                                text: "The image has been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                
                                //location.reload(true); 
                                load(1);
                            });
                           
                        },
                        complete: function(){
                            //removeLoader();
                        //$("#preloader").css('display','none');
                       },
                });
                
            });
            // -------------------------------------------
            /*
             $('#'+target).cycle('remove', index);             
             var uphtml = `<label class="phot_up">
                           <span><i class="fa fa-plus" aria-hidden="true"></i><br>Add Photo</span>
                           <input type="file">
                        </label>`;
            var ht = parseInt(index)+1;
             $(this).parent().parent().find('ul > li:nth-child('+ht+')').removeClass('active').html(uphtml);
            
             $('#'+target).cycle('destroy');
            $('#'+target).cycle({
                loop:false,
                fx:'fade',
                timeout: 0
            });
            var index_new = $(this).parent().find('.cycle-slide-active').attr('data-count');
            index_new = parseInt(index_new)-1;
            var newcount = 0;
            $(this).parent().parent().find('.product_thumb ul > li').each(function( index ) {
                
                $(this).children('a').each(function( index ) {
                    $(this).attr('data-id',newcount);
                    if(index_new == newcount){
                       $(this).parent().addClass('active');
                    }
                    newcount++;
                });
            });
            */
        });
        /////.zoom_pic
        $('.zoom_pic').click(function(event){
             event.preventDefault();
             //
             var index = $(this).parent().find('.cycle-slide-active').attr('src');
             var viframe = '<img src="'+index+'">'
             $('#productPhotoModal .modal-body .modal_pic').html(viframe);
             $('#productPhotoModal').modal('show');
             //productPhotoModal
        });      
        var product_id;
        $('.get_pdetails').click(function(event){
             event.preventDefault();
             product_id = $(this).attr('data-content');
             $('#productDetailsModal').modal('show');
        });         
        
        ////////          
        $('#productVideoModal').on('hide.bs.modal', function (event) {
             $('#productVideoModal .modal-body .video_holder').html('')
        })     
       
        //btnContainer btn
        $('#btnContainer .btn').click(function(event){
             event.preventDefault();
             $('#btnContainer .btn').removeClass('active');
             $(this).addClass('active');
             var gettarget = $(this).attr('data-target');         
             $('#table_view').removeClass('list_view').removeClass('grid_view').addClass(gettarget);
        });
        ////////////////////////////////////////////
    }

    // ---------------------------------------------
    // PRODUCT FILTER: START

    $("body").on("click","#filter_btn",function(e){
        $('#filterModal').modal({
            backdrop: 'static',
            keyboard: false
        });        
    });

    $("body").on("click","#disabled",function(e){
        if($("input:checkbox[name=disabled]:checked").val())
        {
            $("#disabled_reason").attr("disabled",false);

        }
        else
        {
            $("#disabled_reason").attr("disabled",true);
            $("#disabled_reason").prop("selectedIndex", 0);
        }
    });
	
	
	
    $("body").on("click","#product_filter",function(e){
        var filter_arr=[];
        var aproved=$("input:checkbox[name=aproved]:checked").val();
        var aproved_text=$("input:checkbox[name=aproved]:checked").attr('data-text');
        (aproved_text)?filter_arr.push(aproved_text):'';

        var disabled=$("input:checkbox[name=disabled]:checked").val();
        var disabled_text=$("input:checkbox[name=disabled]:checked").attr('data-text');        
        (disabled_text)?filter_arr.push(disabled_text):'';

        var disabled_reason=$("#disabled_reason").val();
        (disabled_reason!='' && disabled!='')?filter_arr.push('Selected Reason: '+$("#disabled_reason option:selected").text()):'';
	

        var product_available_for=[];
        $("input:checkbox[name=product_available_for]:checked").each(function(){
            product_available_for.push($(this).val());
            var product_available_for_text=$("input:checkbox[name=product_available_for]:checked").attr('data-text');     
            (product_available_for_text)?filter_arr.push(product_available_for_text):'';
        });
		
		var with_gst=$("input:checkbox[name=with_gst]:checked").val();
        var with_gst_text=$("input:checkbox[name=with_gst]:checked").attr('data-text');
        (with_gst_text)?filter_arr.push(with_gst_text):'';
		
		
        var group_id=$("#group_id").val();
        var group_text=$("#group_id option:selected").attr('data-text');
		
        (group_text)?filter_arr.push(group_text):'';
		
		var cate_id=$("#cate_id").val();
        var cate_text=$("#cate_id option:selected").attr('data-text');
        (cate_text)?filter_arr.push(cate_text):'';
		

        var with_hsn_code=$("input:checkbox[name=with_hsn_code]:checked").val();
        var with_hsn_code_text=$("input:checkbox[name=with_hsn_code]:checked").attr('data-text');
        (with_hsn_code_text)?filter_arr.push(with_hsn_code_text):'';


        var with_image=$("input:radio[name=with_image]:checked").val();
        var with_image_text=$("input:radio[name=with_image]:checked").attr('data-text');
        (with_image_text)?filter_arr.push(with_image_text):'';

        var with_brochure=$("input:radio[name=with_brochure]:checked").val();
        var with_brochure_text=$("input:radio[name=with_brochure]:checked").attr('data-text');
        (with_brochure_text)?filter_arr.push(with_brochure_text):'';

        var with_youtube_video=$("input:radio[name=with_youtube_video]:checked").val();       
        var with_youtube_video_text=$("input:radio[name=with_youtube_video]:checked").attr('data-text');
        (with_youtube_video_text)?filter_arr.push(with_youtube_video_text):'';
        
        var sort_by=$("input:radio.sort_by:checked").val();
        var sort_by_text=$("input:radio.sort_by:checked").attr('data-text');
        (sort_by_text)?filter_arr.push(sort_by_text):'';

        
        //alert(filter_arr); return false;
        if(filter_arr.join())
        {
            $("#selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="text-danger" id="product_filter_reset"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>');
        }
        else
        {
            $("#selected_filter_div").css({'display':'none'}).html('');
        }
        

        $("#status_tab").hide();
        $("#status").val('');       
        
        
        $("#filter_aproved").val((aproved)?aproved:'');
        $("#filter_disabled").val((disabled)?disabled:'');
        $("#filter_disabled_reason").val((disabled_reason)?disabled_reason:'');

        $("#filter_product_available_for").val(product_available_for.join());
		
		$("#filter_group_id").val((group_id)?group_id:'');
		$("#filter_cate_id").val((cate_id)?cate_id:'');
		
		
        $("#filter_with_gst").val((with_gst)?with_gst:'');
        $("#filter_with_hsn_code").val((with_hsn_code)?with_hsn_code:'');

        $("#filter_with_image").val((with_image)?with_image:'');
        $("#filter_with_brochure").val((with_brochure)?with_brochure:'');
        $("#filter_with_youtube_video").val((with_youtube_video)?with_youtube_video:'');               
        
        $("#filter_sort_by").val(sort_by);
        
        $("#filterModal").modal('hide');  
        load(1);
    });

    $("body").on("click","#product_filter_reset",function(e){
        //location.reload(true);
        $("#selected_filter_div").css({'display':'none'}).html('');
        // ------------------------------------------------------
        // FILTER RE-SET
        $("input:checkbox[name=aproved]").attr("checked",false);
        $("input:checkbox[name=disabled]").attr("checked",false);
        //$("input:checkbox[name=disabled_reason]").attr("checked",false);
		$("#group_id").val($("#group_id option:first").val());
		$("#cate_id").val($("#cate_id option:first").val());
		
        $("input:checkbox[name=product_available_for]").attr("checked",false);

        $("input:checkbox[name=with_gst]").attr("checked",false);
        $("input:checkbox[name=with_hsn_code]").attr("checked",false);

        $("input:radio[name=with_image]").attr("checked",false);
        $("input:radio[name=with_brochure]").attr("checked",false);
        $("input:radio[name=with_youtube_video]").attr("checked",false);       
        
        $("input:radio.sort_by").attr("checked",false);

        // -----
        $("#status").val('0');
		$("#filter_search_str").val('');
        $("#filter_aproved").val('Y');
        $("#filter_disabled").val('');
        $("#filter_disabled_reason").val('');
        $("#filter_product_available_for").val('');
		$("#filter_group_id").val('');
		$("#filter_cate_id").val('');
        $("#filter_with_gst").val('');
        $("#filter_with_hsn_code").val('');
        $("#filter_with_image").val('');
        $("#filter_with_brochure").val('');
        $("#filter_with_youtube_video").val('');        
        $("#filter_sort_by").val('');

        // FILTER RE-SET
        // ------------------------------------------------------  

        // $(".filter_dd").removeClass('open');
        // $(".filter_dd").removeClass('show');
        // $('.dd_overlay').hide();


        $("#status_tab").show();
        $("#selected_filter_div").html('');
        // $("#filterModal").modal('hide');          
        load(1);
    });

    
    // PRODUCT FILTER: END
	
	$("body").on("click",".bulk_update",function(e){
		var action = $(this).attr("data-id");
		var base_URL = $("#base_url").val();
        var data="action="+action; 

        $.ajax({
                url: base_URL+"product/bulk_product_update_list",
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
					//alert(result.html)
					$("#bulk_product_update_html_rander").html(result.html);
                    $('#bulk_product_update_modal_div').modal({
						backdrop: 'static',
						keyboard: false
					}).css('overflow-y', 'auto');
                   
                },
                complete: function(){
                //$("#preloader").css('display','none');
               },
        });
		
	});
	
	$("body").on("focusout",".update_input",function(e){
		
		var id=$(this).attr('data-id');
		var field = $(this).attr('data-field');		
		var field_value = $(this).val();				
		var base_URL = $("#base_url").val();		
		var data="id="+id+"&field="+field+"&field_value="+field_value;
		$.ajax({
				url: base_URL+"product/bulk_product_update",
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
					//$(".error_div").html("");
					$("#tr_bulk_update_"+id).removeClass("bg-danger");
					$("#tr_bulk_update_"+id).removeClass("bg-success");
				},
				success: function(data){
					result = $.parseJSON(data);
					if(result.status=='success')
					{
						if(result.is_updated=='Y')
						{
							$("#tr_bulk_update_"+id).addClass("bg-success");
							$("#tr_bulk_update_"+id).removeClass("bg-danger");
						}
					}
					else
					{
						$("#tr_bulk_update_"+id).addClass("bg-danger");
						$("#tr_bulk_update_"+id).removeClass("bg-success");
						//$("#field_error_"+id).html(result.msg);
					}
								
					
					//alert(result.msg)
					//$("#field_success_"+id).html("Successfully Updated..");
				   
				},
				complete: function(){
				//$("#preloader").css('display','none');
			   },
		});
		
        
	});
	
	$("body").on("click",".bulk_update_view_close",function(e){
		$("#bulk_product_update_modal_div").modal("hide");
		load(1);
	});
	
	// ==================================================================
	// vendor tagged functionality start
	$("body").on("input", ".search_vendor_by_keyword", function(e) {
        var base_url = $("#base_url").val();  
		var pid=$("#select-vendor-add-product-submit").attr("data-pid");
        var existing_vendors=$("#tag_vendor_"+pid).attr('data-vtagstr');      
        var search_keyword = $('.search_vendor_by_keyword').val();
		
        var data="existing_vendors="+existing_vendors+"&search_keyword="+search_keyword;
        //alert(data); //return false;
        $.ajax({
            url: base_url + "product/selectVendors",
            type: "POST",
            data: data,
            async: true,
            success: function(response) {
                $('#select_vendors_for_product_lead_body').html(response);
                $('#lead_select_vendors_modal').modal();
				$("#select-vendor-add-product-submit").addClass("save_tag_vendor");
				$("#select-vendor-add-product-submit").attr("data-pid",pid);
				
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
	$("body").on("click",".select_vendor_product_lead",function(e){
		//$('#vdraddfromProductId').val(pid);
		var existing_vendors=$(this).attr('data-vtagstr');  
		var pid=$(this).attr('data-pid');  
		var base_url=$("#base_url").val();
		var data="existing_vendors="+existing_vendors;
		//alert(data);//return false;
		$.ajax({
			url: base_url+'product/selectVendors',
			data: data,
			type: "POST",
			dataType: "html",
			success: function(response) {
				$('#select_vendors_for_product_lead_body').html(response);
				$('#lead_select_vendors_modal').modal();
				$("#select-vendor-add-product-submit").addClass("save_tag_vendor");
				$("#select-vendor-add-product-submit").attr("data-pid",pid);
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
	
	$("body").on("click",".save_tag_vendor",function(e){
		var pid=$(this).attr('data-pid');  
		var base_url=$("#base_url").val();
		var vendors="";
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

		var data="product_id="+pid+"&vendors="+vendors;
		//alert(data);return false;
		$.ajax({
			url: base_url+'product/tag_selected_vendor_ajax',
			data: data,
			type: "POST",
			dataType: "html",
			success: function(response) {
				
				swal({
					title: "Updated!",
					text: "The vendoe(s) successfully tagged. ",
					 type: "success",
					confirmButtonText: "ok",
					allowOutsideClick: "false"
				}, function () { 					
					//location.reload(true); 
					$("#lead_select_vendors_modal").modal("hide");
					load(1);
				});
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
	});
	// vendor tagged functionality end
	// ==================================================================
	
	$("body").on("click", ".get_product_wise_vendor_list_modal", function(e) {
        var id = $(this).attr('data-id');
        $("#product_wise_vendor_list_modal").modal({
            backdrop: 'static',
            keyboard: false,
            callback: fn_rander_product_wise_vendor_list(id)
        });
    });
	
});


function csv_upload_and_import(opp_id)
{    
    var base_url = $("#base_url").val();
    var extension=$('#csv_file').val().replace(/^.*\./, '');    
    if(extension=='csv')
    {
        swal({
        title: '',
        text: 'Are you sure? Do you want to upload the file?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, upload it!'
        }, function() {            
            $.ajax({
                url: base_url+"product/csv_upload_and_import_ajax/",
                data: new FormData($('#form_upload_csv')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function( xhr ) {
                  $("#upload_csv_modal").css("display","none");
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
                    $("#upload_csv_modal").css("display","block");
                },
                success: function(data){                
                    result = $.parseJSON(data); 
                    //console.log(result.success_msg); //return false;
                    // alert(result.status); 
                    if(result.status=='success')
                    {      
                      swal({
                        title: '',
                        text: 'CSV Successfully Uploaded and imported as lead',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Ok'
                        }, function() {
                          window.location.reload();
                        });   
                        // $('#upload_csv_fb_ig_modal').modal('hide');
                        // swal('CSV Successfully Uploaded and imported as lead'); 
                    }
                    else if(result.status=='Error_log')
                    {
                      $("#error_log_div").show();
                      $("#uploaded_csv_file_name2").val(result.file_name);
                      swal({
                        title: '',
                        text: result.error_msg,
                        type: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Ok'
                        }, function() {
                            $(".get_error_log").first().trigger('click');
                        });
                      //swal("Oops!", result.error_msg, "error");    
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
        swal('Please select a CSV File'); // display response from the PHP script, if any
        return false;
    }

}
function youtube_parser(url)
{
  var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
  var match = url.match(regExp);
  return (match&&match[7].length==11)? match[7] : false;
}
function remove_pdf() 
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
                $("#PreviewPdf").hide();
                swal('Deleted!', 'Your file has been deleted!', 'success');
            }
            return false;
        }
    );
}

function validateYouTubeUrl(geturl) 
{    
    var url = geturl;
    if (url != undefined || url != '') {        
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        if (match && match[2].length == 11) {
            // Do anything for being valid
            // if need to change the url to embed url then use below line            
            $('#videoObject').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=1&enablejsapi=1');
        } else {
            //alert('not valid');
            return 'not valid';
            // Do anything for not being valid
        }
    }
}

