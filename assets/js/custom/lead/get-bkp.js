$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
    $('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5'
    });
});
function inArray(myArray,myValue){
    var inArray = false;
    myArray.map(function(key){
        if (key === myValue){
            inArray=true;
        }
    });
    return inArray;
};

$(document).ready(function(){	

  //////////////////////////////////////////////////////////
	// NOTE
  /*
	$("body").on('click', '.note_btn', function (e) {
		e.preventDefault();
		var id=$(this).attr("data-id");	
    var lead_id=$(this).attr("data-leadid");	
    rander_note_html(id,lead_id);	    		
	});
	$("body").on('click', '.note_close', function (e) {
		e.preventDefault();
		id=$(this).attr("data-id");
    $("#note_inner_div_"+id).html('');
		$("#note_inner_div_"+id).hide('fast');
	});
	var $modalAnimateTime = 300;
  $(document).on('click', '.note_add_btn', function (e) {
      e.preventDefault();
      var parentid=$(this).attr("data-parentid");
      var note=$(this).attr("data-note");
      var user_name=$(this).attr("data-user_name");
      var id=$(this).attr("data-id");     
      $("#add_note_confirm").attr("data-parentid",parentid);	
      
      if(note){
        $("#parent_note").html('<span class="text-danger">Reply To '+user_name+':</span><br>'+note);
      }
      
      var $formComments = $("#note_list_"+id);
      var $formAdd = $('#note_add_'+id);
      var $divForms = $("#note_inner_div_"+id);		
      modalAnimate($formComments, $formAdd,'',$divForms);         
  });	
  $(document).on('click', '.note_back', function (e) {
      e.preventDefault();
      var id=$(this).attr("data-id"); 
      var $formComments = $("#note_add_"+id);
      var $formAdd = $('#note_list_'+id);
      var $divForms = $("#note_inner_div_"+id);		 
      modalAnimate($formComments, $formAdd,'',$divForms);        
  });   
  $("body").on("click","#add_note_confirm",function(e){
    
		var click_btn_obj=$(this);
    var parentid=$(this).attr("data-parentid");
		var id=click_btn_obj.attr("data-id");
    var lead_id=click_btn_obj.attr("data-leadid");
		var base_URL = $("#base_url").val();        
		var note_obj=$("#note_text"); 
		
		// var pre_define_description_obj=tinyMCE.activeEditor.getContent();   
		if(note_obj.val()=='')
		{
			$("#note_error").html("( Please enter note )");
			note_obj.focus();
			return false;
		}
		else
		{
			$("#note_error").html("");
		}     
    var note_text = note_obj.val();
    note_text=note_text.replace(/\r?\n/g, '<br />');      
		var data = "lead_id="+lead_id+"&note="+note_text+"&parentid="+parentid;	
    // alert(data); return false;	
		$.ajax({
			url: base_URL+"lead/add_note_ajax",
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
          $("#note_count_"+id).css("background","#59bb60");
          $("#note_count_"+id).text(result.note_count);
          
          // $("#note_inner_div_"+id).slideToggle();
          // $("#note_inner_div_"+id).html(''); 
          $("#note_inner_div_"+id).html('');
		      $("#note_inner_div_"+id).hide('fast');
					// rander_note_html(id);					
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
  
  function rander_note_html(id,lead_id)
  {
    var base_URL = $("#base_url").val();            	
    var data="id="+id+"&lead_id="+lead_id;
    $.ajax({
            url: base_URL+"lead/rander_note_html",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {                       
            },
            success: function(data){
                result = $.parseJSON(data);
                $("#note_inner_div_"+id).html(result.html);  
                $("#note_inner_div_"+id).show('fast');                           
            },
            complete: function(){
            },
    });	
  }
	function modalAnimate ($oldForm, $newForm, extraHeight,$divForms) 
  {
      extraHeight = extraHeight || 0;
      var $oldH = $oldForm.outerHeight();
      var $newH = $newForm.outerHeight()+(40+extraHeight);
      $oldForm.hide();
      $newForm.show();
  }
  */
	// NOTE
	///////////////////////////////////////////////////////////


	// --------------------------------
  // QUOTATION SEND WITH IFRAME POPUP
  $("body").on("click",".send_quotation_popup_iframe",function(e){
    e.preventDefault();  
    var target_url=$(this).attr("href");
    var lead_title=$(this).attr("data-title");
    $.modalLink.open(target_url, {
        title: (lead_title)?lead_title:"Send Quotation"
    });
  });
  
  $("body").on("click",".sparkling-modal-close",function(e){    
      load('N')
      truncate_temp_selected_product();
  });
	//==========================================================
  /*
	$("body").on("click",".quotation_sent_by_whatsapp",function(e){
		var lid=$(this).attr('data-lid');
		var oppid=$(this).attr('data-oppid');
		var qid=$(this).attr('data-qid');
		var is_mobile=$("#is_mobile").val();
		//alert(lid+'/'+oppid+'/'+qid);
		var base_URL = $("#base_url").val();
		var data="lid="+lid+"&oppid="+oppid+"&qid="+qid;
		$.ajax({
			url: base_URL+"/lead/rander_html_for_quotation_sent_by_whatsapp_ajax",
			data: data,                        
			cache: false,
			method: 'POST',
			dataType: "html",
			beforeSend: function( xhr ) {},
			complete: function(){},
			success: function(data){
				result = $.parseJSON(data);	
				//alert(result.html)
				var whatsapp_txt=result.html;
				var recipient_mobile=result.recipient_mobile;				
				if(is_mobile=='Y')
				{
				var web_whatsapp_url='https://api.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
				}
				else
				{
				var web_whatsapp_url='https://web.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
				}
				let params = 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=4000,height=4000';
				popup = window.open(web_whatsapp_url, "WhatsAppPopup", params);
				popup.focus();
			},
			error: function(response) {}
		});
	});
	*/
	//==========================================================
	
	
  $("input:radio[name=filter_followup]:checked").each(function(){	  
	  $("#filter_followup").val($(this).val());
  });
  
  // if (window.innerWidth < 768) {
  //   $('.like_icon').tooltip( "disable" );
  // }
  
  // ----------------------------------
  // renewal
  $("body").on("click","input:checkbox[name=is_renewal_available]",function(e){
      var checked = $(this).is(':checked');
      if (checked) {
          $("#renewal_div").fadeIn('fast');;          
      } else {         
        $("#renewal_div").fadeOut('fast');;
          
      }
    });
  // renewal
  // ------------------------------------

  const params = new URLSearchParams(window.location.search); 
  var url_str=window.location.pathname.substring(1, window.location.pathname.length-1);
  //var url_str=window.location.pathname.substring(1, window.location.pathname.length);
  var url_arr=url_str.split("/");  
  var last_uri_segment=url_arr[url_arr.length-1];
  if(params!='')
  {
      var action=params.getAll('action');
      var uid=params.getAll('u');
      var pf=params.getAll('pf');
      var pff=params.getAll('pff');
      var dsc_filter_txt=params.getAll('dsc_filter_txt');
      //alert(uid+'/'+pf+'/'+pff)
      //alert(dsc_filter_txt);return false;
      if(uid!='' && pf=='Y')
      {
          document.getElementById("assigned_user").value=uid; 
          $("input:checkbox[name=pending_followup]").prop('checked',true); 
          $("#filter_pending_followup_for").val(pff);          
      } 
      
      if(dsc_filter_txt!='' && selected_user!='')
      {

        var selected_user=$("#filter_like_dsc_selected_user").val();
        var selected_user_arr=selected_user.split(',');
        document.getElementById("assigned_user").value=selected_user;
        $('#assigned_user > option').each(function() {  
          for(var i=0;i<selected_user_arr.length;i++)
          {              
              if(this.value==selected_user_arr[i])
              {
                $(this).attr('selected',true);
                break;
              }
          }          
          //alert(this.text + ' ' + this.value);                   
        });
                
        //$("#lead_type_al").attr("checked",true);
        if(dsc_filter_txt=='active_lead')
        {          
			
			$("input:radio[name=lead_type]").attr("checked",false);
			document.getElementById("lead_type_all").checked = true;
			
			$("#filter_followup_all").attr("checked",true);
			$("#filter_followup").val('AL');
			//$("input:radio[name=lead_type]").attr("checked",false);
			//document.getElementById("lead_type_al").checked = true;
			$("#filter_like_dsc").val('');
        } 
        else if(dsc_filter_txt=='new_lead')
        {          
          $("#filter_followup_new").attr("checked",true);
		      $("#filter_followup").val('NL');
        } 
        else if(dsc_filter_txt=='todays_followup')
        {          
			$("#filter_followup_today").attr("checked",true);  
			$("#filter_followup").val('TL');
        } 
        else if(dsc_filter_txt=='pending_followup')
        {          
			$("#filter_followup_pending").attr("checked",true); 
			$("#filter_followup").val('PL');
        } 
        else if(dsc_filter_txt=='upcoming_followup')
        {          
            $("#filter_followup_upcoming").attr("checked",true);
		    $("#filter_followup").val('UL');         
        } 
        else if(dsc_filter_txt=='quoted_leads')
        {		
			
			$("input:radio[name=lead_type]").attr("checked",false);
			document.getElementById("lead_type_al").checked = true;	
			$("#opportunity_stage_2").attr("checked",true);
		    $("#filter_followup").val('');
			$("#filter_like_dsc").val('');
        }
		else if(dsc_filter_txt=='auto_regretted')
        {        
			$("input:radio[name=lead_type]").attr("checked",false);
			document.getElementById("lead_type_all").checked = true;
			$("#filter_followup").val('');			
			//$("#lead_type_al").attr("checked",true); 
			$("#opportunity_stage_6").attr("checked",true);
			$("#opportunity_stage_7").attr("checked",true);
			$("#filter_like_dsc").val('');
        }   
      }

      if(action=='add')
      {
        //$("#rander_add_new_lead_view").trigger('click');
        rander_add_new_lead_view();        
      }  
      var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '';
      // alert(newurl); return false;
      window.history.pushState({path:newurl},'',newurl);

      set_lead_filter();
  }
  else
  {  
      if(last_uri_segment=='manage_sync_call' || last_uri_segment=='manage_sync_cal'){
      }
      else{
        set_lead_filter();
      }
      // if(last_uri_segment!='manage_sync_call'){
      //   set_lead_filter();
      // }      
  }

  
	/////////
    if(window.innerWidth < 768){
      //.lead_manage_table thead tr th:nth-child(1)
      $('.lead_manage_table thead tr th:nth-child(1)').click(function(){
        //alert("The paragraph was clicked.");
        if ($(this).hasClass("opened")) {
          $(this).removeClass("opened");
          $('.lead_manage_table thead tr th').not(":first").css({'display':'none'});
        }else{
          $(this).addClass("opened");
          $('.lead_manage_table thead tr th').not(":first").css({'display':'inline-block'});
        }
        
      });
    }
    /////////
	 // load();

  $(document).on('click', '.change_status_hotstar', function (e) {        
        var base_URL = $("#base_url").val();            
        var id = $(this).attr('data-leadid'); 
        //Warning Message            
        // swal({
        //     title: "",
        //     text: "Do you really want to change the current Star mark status?",
        //     type: "warning",
        //     showCancelButton: true,
        //     cancelButtonClass: 'btn-warning',
        //     cancelButtonText: "No, cancel it!",
        //     confirmButtonClass: 'btn-warning',
        //     confirmButtonText: "Yes, do it!",
        //     closeOnConfirm: false
        // }, function () { 

                var data="id="+id;
                $.ajax({
                        url: base_URL+"/lead/change_status_hotstar",
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
                        $("#preloader").css('display','block');                           
                        },
                        success: function(data){
                            result = $.parseJSON(data);
                            //alert(result.msg);
                            // $(".preloader").hide();                            
                            // swal({
                            //     title: "",
                            //     text: "The lead status has been changed to star",
                            //      type: "success",
                            //     confirmButtonText: "ok",
                            //     allowOutsideClick: "false"
                            // }, function () { 
                                //location.reload(true); 
                                if(result.curr_star_status=='Y')
                                {
                                  $("#hotstar_icon_"+id).html('<i class="fa fa-star" aria-hidden="true" style="color: #FBD657 !important"></i>');
                                }
                                else
                                {
                                  $("#hotstar_icon_"+id).html('<i class="fa fa-star-o" aria-hidden="true" style="color: #b1afa7 !important"></i>');
                                }                                                   
                            // });
                           
                        },
                        complete: function(){
                        $("#preloader").css('display','none');
                       },
                });
                
        //});        
    }); 

  $("body").on("mouseover",".latest_lead_history",function(e){
	var lid=$(this).attr('data-leadid');
	var base_url = $("#base_url").val();
	
	$.ajax({
		url: base_url+"lead/get_latest_lead_history_ajax",
		type: "POST",
		data: {
		  'lid': lid,
		},			
		async: true,
		beforeSend: function( xhr ) {
			
		},
		complete: function (){
			
		},
		success: function(response){
			//$('#history_div_'+lid).popover();
			//alert(response)
			
			$('#history_div_'+lid).popover({
			  title:'Latest Lead History',
			  trigger: 'hover',
			  html:true,
			  placement:'left',
			  "content": function(){			
					return response;
				}
			});
		}			
	});
});
  $("body").on("click",".view_lead_history",function(e){
    var lid=$(this).attr("data-leadid");
    var base_url=$("#base_url").val();
    var data="lid="+lid;
    
    $.ajax({
        url: base_url+"lead/view_lead_history_ajax",
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
        success: function(data){
          result = $.parseJSON(data);           
          //alert(result.html);
          $("#lead_history_log_title").html(result.title);
          $("#lead_history_log_body").html(result.html);
          $('#lead_history_log_modal').modal({backdrop: 'static',keyboard: false}); 
        }
      });
    
  });
  //lead_history_log_modal
  $("body").on("click","#lead_history_log_modal #hist_list .one_p a",function(e){
    
    var filename = $(this).attr('data-filepath');
    //var filename = 'https://lmsbaba.com/lmsbaba/clientportal/lead/download/file_example_MP4_480_1_5MG.mp4';
    var getx = filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
    
    //////////
    if(getx == 'mp3' || getx == 'mp4'){
      e.preventDefault();
      //alert(getx);
      $('#lead_history_log_modal').css({'opacity': 0});
      createMediaView(getx, filename);
    }
    /////////
  });
  function createMediaView(getx, filename){
    // console.log(filename);
    //z-index: 9991;
    var exType = 'video/mp4';
    var tt = 'video';
    if(getx == 'mp3'){
      exType = 'audio/mpeg';
      tt = 'audio';
    }
    var playerHtml = `<div class="media-wapper ${tt}" id="media-preview">
            <a href="#" class="media-close"><svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                  <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"></path>
               </svg></a>
            <div class="media-body">
               <${tt} controls autoplay>
                 <source src="${filename}" type="${exType}">
                 Your browser does not support the file tag.
               </${tt}>
            </div>
         </div>`;
    $('body').append(playerHtml);
    $('.media-wapper .media-close').click(function( event ) {
      event.preventDefault();
      $('#lead_history_log_modal').css({'opacity': 1});
      $('#media-preview').remove();
    });
    //.media-wapper .media-close
  }
  $("body").on("click",".set_call_schedule_from_app",function(e){
    var lid=$(this).attr("data-leadid");
    var base_url=$("#base_url").val();
    var mobile=$(this).attr('data-mobile');
    var contact_person=$(this).attr('data-contactperson');
    
    // alert(data); return false;
    swal({
          title: "",
          text: "Do you want to call to "+contact_person+" ("+mobile+")?",
          type: "warning",
          showCancelButton: true,
          cancelButtonClass: 'btn-warning',
          cancelButtonText: "No, cancel it!",
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, do it!",
          closeOnConfirm: true
      }, function () { 

          var data="lid="+lid;
          $.ajax({
                  url: base_url+"lead/set_call_schedule_from_app_ajax",
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
                  success: function(data){
                    result = $.parseJSON(data);           
                      // console.log(result.msg);
                      if(result.status=='success')
                      {
                          swal({
                            title: "Success!",
                            text: "You can call the customer from your app",
                            type: "success",
                            confirmButtonText: "ok",
                            allowOutsideClick: "false"
                          });
                      }
                      else
                      {
                        swal({
                            title: "Oops!",
                            text: "Something went wrong there",
                            type: "danger",
                            confirmButtonText: "ok",
                            allowOutsideClick: "false"
                          });
                      }
                    }
                });
          
      });
    
    
  });

  $("body").on("click",".set_c2c",function(e){
    var base_url=$("#base_url").val();
    var lid=$(this).attr("data-leadid");    
    var cust_mobile=$(this).attr('data-custmobile');
    var cust_id=$(this).attr('data-cusid');
    var contact_person=$(this).attr('data-contactperson');
    var user_mobile=$(this).attr('data-usermobile');
    var user_id=$(this).attr('data-userid');
    var data="lid="+lid+"&cust_mobile="+cust_mobile+"&cust_id="+cust_id+"&user_mobile="+user_mobile+"&user_id="+user_id+"&contact_person="+contact_person;
    // alert(data); return false;
    swal({
          title: "",
          text: "Do you want to call to "+contact_person+" ("+cust_mobile+")?",
          type: "warning",
          showCancelButton: true,
          cancelButtonClass: 'btn-warning',
          cancelButtonText: "No, cancel it!",
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, do it!",
          closeOnConfirm: true
      }, function () {
          $.ajax({
              url: base_url+"lead/set_c2c_using_api_ajax",
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
                  success: function(data){
                    result = $.parseJSON(data);           
                      // alert(result.api_url);
                      // window.open(result.api_url,'_blank');
                      let params = 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=800';
                      popup = window.open(result.api_url, "c2c_popup_box", params);
                      popup.focus();
                      // if(result.status=='success')
                      // {
                      //     swal({
                      //       title: "Success!",
                      //       text: "You can call the customer from your app",
                      //       type: "success",
                      //       confirmButtonText: "ok",
                      //       allowOutsideClick: "false"
                      //     });
                      // }
                      // else
                      // {
                      //   swal({
                      //       title: "Oops!",
                      //       text: "Something went wrong there",
                      //       type: "danger",
                      //       confirmButtonText: "ok",
                      //       allowOutsideClick: "false"
                      //     });
                      // }
                    }
              });
      });
  });

  $("body").on("click",".get_original_quotation",function(e){
		var lead_id=$(this).attr("data-id");
		var base_url=$("#base_url").val();
		var data="lead_id="+lead_id;
		$.ajax({
			  //url: base_url+"lead/original_quotation_view_rander_ajax",
			  //type: "POST",
			  //data: {'lead_id':lead_id},       
			  //async:true,  
				url: base_url+"lead/original_quotation_view_rander_ajax",
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
			success: function (response) 
			{ 
			  $('#original_quotation_view_rander').html(response);
			  $('#original_quotation_view_modal').modal({backdrop: 'static',keyboard: false});
			},
			error: function () 
			{
			  //alert('Something went wrong there');
			  swal({
				title: "Danger!",
				text: "Something went wrong there",
				type: "danger",
				confirmButtonText: "ok",
				allowOutsideClick: "false"
			});
			}
      });
    });
	 
   $("body").on("click","#btnContainer .get_view",function(e){
      $(".get_view").removeClass("active");
      $(this).addClass('active');
      var view_type=$(this).attr('data-target');
      $("#view_type").val(view_type);

      // $("#filter_list").hide();
      // $("#filter_grid").hide();
      
      //alert(view_type)
      load();
    });
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
                    load();
                    return false;
            }
            /* Validation code end */
            
    });
    
    $(document).on('click', '.myclass', function (e) { 
           e.preventDefault();
		        closeExpendTable();
           var vt=($(this).attr('data-viewtype')=='grid')?'grid':'list';
           var str = $(this).attr('href'); 
           var res = str.split("/");
           var cur_page = res[1];
           $("#page_number").val(cur_page);
           $("#is_scroll_to_top").val('Y'); 
           if(cur_page) {              
              load(cur_page);
            }
            else {
              load();
            }

            // $("input:checkbox[id=set_all]").prop('checked', false);
            // $("input:checkbox[class=set_individual]").prop('checked', false);
            // $("#checked_ids").val('');
	});
    // AJAX SEARCH END
    
    
    
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH END ############# */
    /* ######################################################################## */
	   
    // $("body").on("click",".open-calendar",function(e){

    //   $(this).on('shown.bs.popover', function () { 
    //               alert('aaaa')
    //     $('.datetimepicker_nfd').datepicker({
    //                                 dateFormat: "dd-M-yy",
    //                                 changeMonth: true,
    //                                 changeYear: true,
    //                                 yearRange: '-100:+5',
    //                                 minDate:0,
    //                                 onSelect : function (ev) {
    //                                     // here your code
    //                                     // alert(ev)
    //                                     $("#nfd_date").val(ev);
    //                                 }
    //                             }).update();
    //           });        
    // });
    // var base_url_root = $("#base_url_root").val();
    // $( ".nfd_input_date" ).datepicker({
    //     showOn: "both",
    //     dateFormat: "dd-M-yy",
    //     buttonImage: base_url_root+"images/cal-icon.png",
    //     // changeMonth: true,
    //     // changeYear: true,
    //     // yearRange: '-100:+0',
    //     buttonImageOnly: true,
    //     buttonText: "Select date",
    //     minDate: 0,
    //     onSelect : function (dateText, inst) {
    //           // here your code
    //           // var lid=$(this).attr('data-lid');
    //           // alert(ev+'/'+lid)
    //           alert(inst.id)
    //     }
    // });
    
    


     $("body").on("click","#next_followup_update_confirm",function(e){      
        e.preventDefault();

        var base_url = $("#base_url").val();
        var lid=$("#nfd_lead_id").val();    
        $('#open_calendar_update_'+lid).popover('hide');
        $.ajax({
            url: base_url + "lead/update_next_followup_date_ajax",
            data: new FormData($('#nfdUpdateFrm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
              // $(".popover-content").addClass('logo-loader')             
            },
            success: function(data) {
                result = $.parseJSON(data);
                         
                if (result.status == 'success') {
                    // alert(result.msg)                    
                    //location.reload();
                    $("#ndf_"+result.lid).html(result.updated_nfd);
                    $('.open-calendar').popover('hide'); 
                    //load(); 
                    /*
                    swal({
                        title: 'Success',
                        text: 'The lead has been assigned to ' + result.assigned_to_user_name,
                        type: 'success',
                        showCancelButton: false
                    }, function() {
                        //$("#assigned_to_user_name_span").html(result.assigned_to_user_name);                  
                        location.reload();
                    });
                    */
                }
                else
                {

                  
                }
            }
        });

    });

     
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
	
	
	
	// ---------------------------------------------------------------
	// UPDATE LEAD
	$("body").on("click", ".rander_update_lead_view", function(e) {        
        var base_url = $("#base_url").val();
    		var leadid=$(this).attr("data-id");
    		var leadtitle=$(this).attr("data-title");
    		var data="leadid="+leadid;
        $.ajax({
			url: base_url + "lead/rander_update_lead_view_ajax",
			data: data,
			cache: false,
			method: 'POST',
			dataType: "html",
			beforeSend: function(xhr) {
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
			success: function(res) {
				result = $.parseJSON(res);     
				//alert(result.html)
				$("#lead_title_div").html(': '+leadtitle+' (Lead #'+leadid+')');
				$("#update_lead_body").html(result.html);
				$('#update_lead_modal').modal({
					backdrop: 'static',
					keyboard: false
				});

			},
			error: function(response) {}
		}); 

    });
	
	// UPDATE LEAD
	// ---------------------------------------------------------------
	
  // ---------------------------------------------------------------
  // Change Assigne to
	$("body").on("click", ".company_assigne_change", function(e) {
        var c_id = $(this).attr("data-cid");
        var currassigned_to = $(this).attr("data-currassigned");
        var lid = $(this).attr("data-lid");
        var base_url = $("#base_url").val();
        var data = "c_id=" + c_id+"&currassigned_to="+currassigned_to+"&lid="+lid;

        $.ajax({
                url: base_url + "customer/change_assigned_to_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {
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
                success: function(res) {
                    result = $.parseJSON(res);     
                     //alert(result.html)
                    $("#company_assigne_to_body").html(result.html);
                    $('#company_assigne_to_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                complete: function() {
                   $.unblockUI();
                },
                error: function(response) {}
        });
        /*
        swal({
  			  title: "Are you sure?",
  			  text: "To change the user assign to this company all existing lead assign users will be changed accordingly under the company!",
  			  type: "warning",
  			  showCancelButton: true,
  			  confirmButtonClass: "btn-danger",
  			  confirmButtonText: "Yes, do it!",
  			  cancelButtonText: "No, leave it!",
  			  closeOnConfirm: true,
  			  closeOnCancel: false
  			},
  			function(isConfirm) {

    				if (isConfirm) 
    				{
    					
    				}
    				else 
    				{
    					swal("Cancelled", "You have no changed :)", "error");
    				}
        }); 
        */

    });
	$("body").on("click", "#company_assigne_change_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        // var flag=0;
        if($("#assigned_to").val()=='')
        {
        	$("#assigned_to_error").html("Please select user to assign.");
        	return false;
          // flag=1;
        }
        else
        {
        	$("#assigned_to_error").html("");
        }

        // if($("#observer").val()=='')
        // {
        //   $("#observer_error").html("Please select observer");
        //   return false;
        //   flag=1;
        // }
        // else
        // {
        //   $("#observer_error").html("");
        // }        

        $.ajax({
            url: base_url + "customer/update_change_assigned_to_ajax",
            data: new FormData($('#company_assigne_change_frm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
            	$('#company_assigne_to_modal .modal-body').addClass('logo-loader');
            },
            complete: function (){
              $('#company_assigne_to_modal .modal-body').removeClass('logo-loader');
            }, 
            success: function(data) {
                result = $.parseJSON(data);
                         
                if (result.status == 'success') {
                    //location.reload();
                    $('#company_assigne_to_modal').modal('hide');
                    load();
                    /*
                    swal({
                        title: 'Success',
                        text: 'The lead has been assigned to ' + result.assigned_to_user_name,
                        type: 'success',
                        showCancelButton: false
                    }, function() {
                        //$("#assigned_to_user_name_span").html(result.assigned_to_user_name);                  
                        location.reload();
                    });
                    */
                }
                else
                {

                	swal({
					      title: 'Warning',
					      text: result.msg,
					      type: 'warning',
					      showCancelButton: false,
					      confirmButtonColor: '#DD6B55',
					      confirmButtonText: '',
					      closeOnConfirm: true
					    }, function() {
					    	
					      $("#company_assigne_change_submit").attr("disabled",false);
					    });
                }
            }
        });
    });

  $("body").on("click","#company_assigne_change_multiple",function(e){
      
      var base_url = $("#base_url").val();
      var c_id_arr = [];   
      var l_id_arr=[];   
      $.each($("input[name='checked_to_customer']:checked"), function(){            
          c_id_arr.push($(this).val());
          l_id_arr.push($(this).attr('data-leadid'));
      });
      var c_id_str=c_id_arr.toString(',');  
      var l_id_str=l_id_arr.toString(','); 
      // alert(l_id_str); return false;     
      var data = "c_id_str=" + c_id_str+"&l_id_str="+l_id_str;
      $.ajax({
                url: base_url + "customer/change_assigned_to_multiple_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {
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
                success: function(res) {
                    result = $.parseJSON(res);     
                     //alert(result.html)
                    $("#company_assigne_to_body").html(result.html);
                    $('#company_assigne_to_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                complete: function() {
                   $.unblockUI();
                },
                error: function(response) {}
      });
  });

  $("body").on("click", "#company_assigne_change_multiple_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        // var flag=0;
        if($("#assigned_to").val()=='')
        {
          $("#assigned_to_error").html("Please select user to assign.");
          return false;
          // flag++;
        }
        else
        {
          $("#assigned_to_error").html("");
        }

        // if($("#observer").val()=='')
        // {
        //   $("#observer_error").html("Please select observer");
        //   return false;
        //   flag++;
        // }
        // else
        // {
        //   $("#observer_error").html("");
        // }

        // if(flag==2)
        // {
        //   return false;
        // }
        $.ajax({
            url: base_url + "customer/update_change_assigned_to_multiple_ajax",
            data: new FormData($('#company_assigne_change_frm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
              $('#company_assigne_to_modal .modal-body').addClass('logo-loader');
            },
            complete: function (){
                $('#company_assigne_to_modal .modal-body').removeClass('logo-loader');
            },
            success: function(data) {
                result = $.parseJSON(data);
                         
                if (result.status == 'success') {
                    //location.reload();
                    $('#company_assigne_to_modal').modal('hide');
                    load();
                    $.each($("input[name='lead_all']:checked"), function(){            
                        $(this).prop('checked', false);
                    });
                    $.each($("input[name='checked_to_customer']:checked"), function(){            
                        $(this).prop('checked', false);
                    });
                    $('.cousto_check .check-box-sec').removeClass('same-checked');
                    $('.bulk_bt_holder').hide();
                    

                    /*
                    swal({
                        title: 'Success',
                        text: 'The lead has been assigned to ' + result.assigned_to_user_name,
                        type: 'success',
                        showCancelButton: false
                    }, function() {
                        //$("#assigned_to_user_name_span").html(result.assigned_to_user_name);                  
                        location.reload();
                    });
                    */
                }
                else
                {

                  swal({
                title: 'Warning',
                text: result.msg,
                type: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: '',
                closeOnConfirm: true
              }, function() {
                
                //$("#company_assigne_change_submit").attr("disabled",false);
              });
                }
            }
        });
    });
  // Change Assigne to
  // ---------------------------------------------------------------
	
	// --------------------------------------------------
	// FILTER SECTION
	
    $("body").on("click","input:checkbox[name=opportunity_stage]",function(e){
         
        var curr_checked_val=$(this).val();
        // alert(curr_checked_val)

        // if(this.checked==true)
        // {
        //   alert("checked");
        // }
        // else
        // {
        //   alert('not checked')
        // }
        if(curr_checked_val==1){
            if(this.checked==true)
            {
              $("input:checkbox[name=opportunity_stage]").each(function(){
                if($(this).val()!='1')
                {
                  $(this).attr("checked",false); 
                  //$(this).attr("disabled",true);                  
                }                        
              });
            }
            else
            {
              $("input:checkbox[name=opportunity_stage]").each(function(){
                
                  //$(this).attr("disabled",false);
              });
            }            
        }
        else{
          if($('input:checkbox[name=opportunity_stage]:checked').length>0)
          {
            $("input:checkbox[name=opportunity_stage]").each(function(){
                if($(this).val()=='1')
                {
                  $(this).attr("checked",false);
                  //$(this).attr("disabled",true);                  
                }                        
            });
          }
          else
          {
            $("input:checkbox[name=opportunity_stage]").each(function(){
              //$(this).attr("disabled",false);
            });
          }
        }
        //alert(curr_checked_val)
        // if($('input:checkbox[name=opportunity_stage]:checked').length==0)
        // {
        //     $("input:checkbox[name=opportunity_stage]").each(function(){
        //       $(this).attr("disabled",false);
        //     });
        // }
        // $("input:checkbox[name=opportunity_stage]").each(function(){
            
        //     if(curr_checked_val=='1')
        //     {
        //         if($(this).val()!='1')
        //         {
        //           $(this).attr("disabled",true);                  
        //         } 

        //     }
        //     else
        //     {
        //         if($(this).val()=='1')
        //         {
        //             $(this).attr("disabled",true);
        //         }                   
        //     }                 
        // });
    });
	  $("body").on("click","#filter_btn",function(e){
        $('#leadFilterModal').modal({
            backdrop: 'static',
            keyboard: false
        });        
    });
	 
    $("body").on("click",".lead_individual_filter_reset",function(e){
        var filter_id=$(this).attr('data-id');
        var filter_type=$(this).attr('data-filter');
        lead_filter_individual_reset(filter_id,filter_type);
    });


    function lead_filter_individual_reset(id,filter_type)
    {
      // alert(id+' / '+filter_type); //return false; 
      if(filter_type=='filter_by_keyword') 
      {        
          $("#filter_by_keyword").val('');
          $("#filter_search_str").val(''); 
      }

      if(filter_type=='date_filter') 
      {        
          $("#datepicker3").val('');
          $("#datepicker4").val('');          
          $("#date_filter_by").val($("#date_filter_by option:first").val());
      }

      if(filter_type=='assigned_user') 
      {        
        //$("#assigned_user").val($("#assigned_user option:first").val());
      
        $('#assigned_user option:selected').each(function() {

            if($(this).val()==id)      
            {
                $(this).attr("selected",false);
                $('#assigned_user').multiselect('deselect', $(this).val());
            }        
        });
      }

      if(filter_type=='lead_applicable_for') 
      {
        $("input:checkbox[name=lead_applicable_for]:checked").each(function(){

            if($(this).val()==id)      
            {
                $(this).attr("checked",false);
            }        
        });
      }

      if(filter_type=='lead_type') 
      {
        $("input:checkbox[name=lead_type]:checked").each(function(){

            if($(this).val()==id)      
            {
                $(this).attr("checked",false);
            }        
        });
      }

      if(filter_type=='opportunity_stage') 
      {
        $("input:checkbox[name=opportunity_stage]:checked").each(function(){

            if($(this).val()==id)      
            {
                $(this).attr("checked",false);
            }        
        });
      }

      if(filter_type=='opportunity_status') 
      {
        $("input:checkbox[name=opportunity_status]:checked").each(function(){

            if($(this).val()==id)      
            {
                $(this).attr("checked",false);
            }        
        });
      }

      if(filter_type=='hotstar_status') 
      {
        $("input:checkbox[name=is_hotstar]").attr("checked",false);
      }

      if(filter_type=='pending_followup') 
      {
        $("input:checkbox[name=pending_followup]").attr("checked",false);
        $("#filter_pending_followup_for").val('');
      }

      if(filter_type=='by_source') 
      {
        $("input:checkbox[name=by_source]:checked").each(function(){

            if($(this).val()==id)      
            {
                $(this).attr("checked",false);
            }        
        });
      }
      set_lead_filter();
    }

function set_lead_filter()
{  
      // const params = new URLSearchParams(window.location.search);      
      // if(params!='')
      // {
      //   var uid=params.getAll('u');
      //   var pf=params.getAll('pf');
      //   var pff=params.getAll('pff');
      //   document.getElementById("assigned_user").value = uid; 
      //   $("input:checkbox[name=pending_followup]").prop('checked', true); 
      //   $("#filter_pending_followup_for").val(pff);
      // }

      var filter_arr=[];
      var filter_by_keyword=$("#filter_by_keyword").val();
      if(filter_by_keyword)
      {
        filter_arr.push(' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="" data-filter="filter_by_keyword"><i class="fa fa-times" aria-hidden="true"></i></a> By Keyword: '+filter_by_keyword);
      }     

      var filter_lead_from_date=$("#datepicker3").val();
      var filter_lead_to_date=$("#datepicker4").val();
      var date_filter_by=$("#date_filter_by option:selected").val();  
      var date_filter_by_text=$("#date_filter_by option:selected").attr('data-text'); 
      if(filter_lead_from_date!='' && filter_lead_to_date!='')
      {
        var date_range_text='"'+date_filter_by_text+'"'+' between "'+filter_lead_from_date+'" - "'+filter_lead_to_date+'"';
        (date_range_text)?filter_arr.push(' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="" data-filter="date_filter"><i class="fa fa-times" aria-hidden="true"></i></a> '+date_range_text):'';
      }
      
      //var assigned_user=$("#assigned_user option:selected").val();
      //var assigned_user_text=$("#assigned_user option:selected").attr('data-text');
      //(assigned_user_text)?filter_arr.push('By User: '+' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+assigned_user+'" data-filter="assigned_user"><i class="fa fa-times" aria-hidden="true"></i></a> '+assigned_user_text):'';
      

      var assigned_user=[];
      var x=1;
      $('#assigned_user option:selected').each(function() {
          assigned_user.push($(this).val());
          var assigned_user_text=$(this).attr('data-text');
          if(assigned_user_text!='' && x==1)
          {
            label_text='By User: ';
          }
          else
          {
            label_text='';
          }      
          (assigned_user_text)?filter_arr.push(label_text+' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+$(this).val()+'" data-filter="assigned_user"><i class="fa fa-times" aria-hidden="true"></i></a> '+assigned_user_text):'';
          x++;
      });





      var lead_applicable_for=[];
      $("input:checkbox[name=lead_applicable_for]:checked").each(function(){
          lead_applicable_for.push($(this).val());
          var lead_applicable_for_text=$(this).attr('data-text');     
          (lead_applicable_for_text)?filter_arr.push(' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+$(this).val()+'" data-filter="lead_applicable_for"><i class="fa fa-times" aria-hidden="true"></i></a> '+lead_applicable_for_text):'';
      });





      var lead_type=[];
      var j=1;
      $("input:radio[name=lead_type]:checked").each(function(){
          lead_type.push($(this).val());      
          
          if($(this).val()=='AL'){
            $("#by_stage_li_3").attr("style", "display: none !important");
            $("#by_stage_li_4").attr("style", "display: none !important");
            $("#by_stage_li_5").attr("style", "display: none !important");
            $("#by_stage_li_6").attr("style", "display: none !important");
            $("#by_stage_li_7").attr("style", "display: none !important");
          }

          var lead_type_text=$(this).attr('data-text'); 
          if(lead_type_text!='' && j==1)
          {
            label_text='By Type: ';
          }
          else
          {
            label_text='';
          } 
          (lead_type_text)?filter_arr.push(label_text+' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+$(this).val()+'" data-filter="lead_type"><i class="fa fa-times" aria-hidden="true"></i></a> '+lead_type_text):'';   
          j++;
      });


      $("body").on("change","input:radio[name=lead_type]:checked",function(e){
        $("input:checkbox[name=opportunity_stage]:checked").each(function(){
          $(this).attr("checked",false);                   
        });
        if($(this).val()=='AL'){
            $("#by_stage_li_3").attr("style", "display: none !important");
            $("#by_stage_li_4").attr("style", "display: none !important");
            $("#by_stage_li_5").attr("style", "display: none !important");
            $("#by_stage_li_6").attr("style", "display: none !important");
            $("#by_stage_li_7").attr("style", "display: none !important");
        }
        else{
            $("#by_stage_li_3").attr("style", "display: block !important");
            $("#by_stage_li_4").attr("style", "display: block !important");
            $("#by_stage_li_5").attr("style", "display: block !important");
            $("#by_stage_li_6").attr("style", "display: block !important");
            $("#by_stage_li_7").attr("style", "display: block !important");
        }
      });


      
      
      var opportunity_stage=[];
      var i=1;
      $("input:checkbox[name=opportunity_stage]:checked").each(function(){      
          opportunity_stage.push($(this).val());
          var opportunity_stage_text=$(this).attr('data-text'); 
          if(opportunity_stage_text!='' && i==1)
          {
            label_text='By Stage: ';
          }
          else
          {
            label_text='';
          }
          (opportunity_stage_text)?filter_arr.push(label_text+' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+$(this).val()+'" data-filter="opportunity_stage"><i class="fa fa-times" aria-hidden="true"></i></a> '+opportunity_stage_text):'';
          i++;
      });
      
      var opportunity_status=[];
      var j=1;
      $("input:checkbox[name=opportunity_status]:checked").each(function(){
          opportunity_status.push($(this).val());
          var opportunity_status_text=$(this).attr('data-text');
          if(opportunity_status_text!='' && j==1)
          {
            label_text='By Current Status: ';
          }
          else
          {
            label_text='';
          }
          
          (opportunity_status_text)?filter_arr.push(label_text+' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+$(this).val()+'" data-filter="opportunity_status"><i class="fa fa-times" aria-hidden="true"></i></a> '+opportunity_status_text):'';
          j++;
      });

      var by_source=[];
      var j=1;
      $("input:checkbox[name=by_source]:checked").each(function(){
          by_source.push($(this).val());
          var by_source_text=$(this).attr('data-text');
          if(by_source_text!='' && j==1)
          {
            label_text='By Source: ';
          }
          else
          {
            label_text='';
          }
          
          (by_source_text)?filter_arr.push(label_text+' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+$(this).val()+'" data-filter="by_source"><i class="fa fa-times" aria-hidden="true"></i></a> '+by_source_text):'';
          j++;
      });
      
      var is_hotstar=$("input:checkbox[name=is_hotstar]:checked").val();    
      var is_hotstar_text=$("input:checkbox[name=is_hotstar]:checked").attr('data-text');
          (is_hotstar_text)?filter_arr.push(' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="" data-filter="hotstar_status"><i class="fa fa-times" aria-hidden="true"></i></a> '+is_hotstar_text):'';
      //var opportunity_stage=$("#opportunity_stage option:selected").val();
      //var opportunity_status=$("#opportunity_status option:selected").val();
      

      var pending_followup=$("input:checkbox[name=pending_followup]:checked").val();    
      var pending_followup_for=$("#filter_pending_followup_for").val();
      if(pending_followup_for=='')
      {
        pending_followup_for_text=" : All";
      }
      else if(pending_followup_for=='today')
      {
        pending_followup_for_text=" : today's";
      }
      else if(pending_followup_for=='yesterday')
      {
        pending_followup_for_text=" : Yesterday";
      }
      else if(pending_followup_for=='twodaysbefore')
      {
        pending_followup_for_text=" : >2 Days Old";
      }
      else if(pending_followup_for=='fivedaysbefore')
      {
        pending_followup_for_text=" : >5 Days Old";
      }
      var pending_followup_text=$("input:checkbox[name=pending_followup]:checked").attr('data-text');
      (pending_followup_text)?filter_arr.push(' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="" data-filter="pending_followup"><i class="fa fa-times" aria-hidden="true"></i></a> '+pending_followup_text+pending_followup_for_text):'';

      
      if(filter_arr.join())
      {
          $("#selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="text-danger lead_filter_reset" id=""><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>');
      }
      else
      {
          $("#selected_filter_div").css({'display':'none'}).html('');
      }
      
      $("input:checkbox[name=common_lead_pool]").attr("checked",false);


      // SET VAL
      if(filter_by_keyword)
      {
        $("#filter_search_str").val(filter_by_keyword);
      }
      $("#filter_lead_from_date").val(filter_lead_from_date);
      $("#filter_lead_to_date").val(filter_lead_to_date);
      $("#filter_date_filter_by").val(date_filter_by);
      
      $("#filter_assigned_user").val(assigned_user);
      
      $("#filter_lead_applicable_for").val(lead_applicable_for.join());

      $("#filter_lead_type").val(lead_type.join());
      
      $("#filter_opportunity_stage").val(opportunity_stage.join());
      $("#filter_opportunity_status").val(opportunity_status.join());
      $("#filter_by_source").val(by_source.join());
      $("#filter_is_hotstar").val(is_hotstar);
      $("#filter_pending_followup").val(pending_followup);
      $("#filter_common_lead_pool").val('N');
      $("#leadFilterModal").modal('hide');  
      load(1);
}


	$("body").on("click","#lead_filter",function(e){
		$("#filter_like_dsc").val('');
		$("#filter_like_dsc_selected_user").val('');
		
		$("input:radio[name=filter_followup]:checked").each(function(){
			$(this).attr("checked",false);
		});	
		$("#filter_followup").val('');
		if(($("#datepicker3").val()!='' && $("#datepicker4").val()!='') && $("#date_filter_by").val()==''){
			
			
			var opportunity_stage_chked_count=0;
			$("input:checkbox[name=opportunity_stage]:checked").each(function(){      
			  opportunity_stage_chked_count++;
			});
			
			var opportunity_status_chked_count=0;
			$("input:checkbox[name=opportunity_status]:checked").each(function(){      
			  opportunity_status_chked_count++;
			});
			
			if(opportunity_stage_chked_count==0 && opportunity_status_chked_count==0){
				swal("Oops!", 'Please refine your search to get desired result', "error");
				return false;
			}
			
			
		}
		
		set_lead_filter();
		
	});
	
	$("body").on("click",".lead_filter_reset",function(e){
		
        //location.reload(true);
        $("#selected_filter_div").css({'display':'none'}).html('');
        // ------------------------------------------------------
        // FILTER RE-SET
		$("input:radio[name=filter_followup]:checked").each(function(){
			$(this).attr("checked",false);
		});	
		$("#filter_followup").val('');
		
		
        $("#filter_by_keyword").val('');

        $("#datepicker3").val('');
    		$("#datepicker4").val('');
    		$("#date_filter_by").val($("#date_filter_by option:first").val());
    		
    		//$("#assigned_user").val($("#assigned_user option:first").val());
    		
        $('#assigned_user option:selected').each(function() {           
            $(this).attr("selected",false);     
            $('#assigned_user').multiselect('deselect', $(this).val());             
        });


    		$("input:checkbox[name=lead_applicable_for]").attr("checked",false);

        $("input:radio[name=lead_type]").attr("checked",false);
    		document.getElementById("lead_type_all").checked = true;
        if($(this).val()=='AL'){
            $("#by_stage_li_3").attr("style", "display: none !important");
            $("#by_stage_li_4").attr("style", "display: none !important");
            $("#by_stage_li_5").attr("style", "display: none !important");
            $("#by_stage_li_6").attr("style", "display: none !important");
            $("#by_stage_li_7").attr("style", "display: none !important");
        }
        else{
            $("#by_stage_li_3").attr("style", "display: block !important");
            $("#by_stage_li_4").attr("style", "display: block !important");
            $("#by_stage_li_5").attr("style", "display: block !important");
            $("#by_stage_li_6").attr("style", "display: block !important");
            $("#by_stage_li_7").attr("style", "display: block !important");
        }
        $("input:checkbox[name=opportunity_stage]:checked").each(function(){
            $(this).attr("checked",false);                   
        });
    		$("input:checkbox[name=opportunity_stage]").attr("checked",false);
    		$("input:checkbox[name=opportunity_status]").attr("checked",false);
        $("input:checkbox[name=by_source]").attr("checked",false);
    		$("input:checkbox[name=is_hotstar]").attr("checked",false);
        $("input:checkbox[name=pending_followup]").attr("checked",false);
    		$("input:checkbox[name=common_lead_pool]").attr("checked",false);
    		
        // SET VAL
        $("#filter_search_str").val('');
    		$("#filter_lead_from_date").val('');
    		$("#filter_lead_to_date").val('');
    		$("#filter_date_filter_by").val('');
    		
    		$("#filter_assigned_user").val('');
    		
    		$("#filter_lead_applicable_for").val('');
        $("#filter_lead_type").val('ALL');
    		
    		$("#filter_opportunity_stage").val('');
    		$("#filter_opportunity_status").val('');
        $("#filter_by_source").val('');
    		$("#filter_is_hotstar").val('');
        $("#filter_pending_followup").val('');
        $("#filter_pending_followup_for").val('');

        $("#filter_like_dsc").val('');
        $("#filter_like_dsc_selected_user").val('');

        $("#filter_common_lead_pool").val('N');


		  

        // FILTER RE-SET
        // ------------------------------------------------------ 
        $("#selected_filter_div").html('');
        // $("#leadFilterModal").modal('hide');        
        load(1);
    });

    // SHOW/HIDE USERS CHECK BOX
    $("body").on("click",".tree_clickable",function(e){
      $("#select_div").slideToggle('fast');
      $(this).toggleClass("tree-down-arrow");
    });

    $(".user_all_checkbox").change(function () {
      $('.dropdown_new .check-box-sec').removeClass('same-checked');
      
      if($(this).prop("checked") == true){
        $('#dropdownMenuUser').html('All');
      }else{
        $('#dropdownMenuUser').html('None');
      }
        $('input:checkbox[name=by_source]').prop('checked', $(this).prop("checked"));
    });
    $("body").on("click",".cAll",function(e){
      e.preventDefault();
      $('#dropdownMenuUser').html('All');
      $('input:checkbox[name=by_source], .user_all_checkbox').prop('checked',true);
    });
    $("body").on("click",".uAll",function(e){
      e.preventDefault();
      $('#dropdownMenuUser').html('None');
      $('input:checkbox[name=by_source], .user_all_checkbox').prop('checked',false);
    });
	// FILTER SECTION
	// --------------------------------------------------

  $("body").on("click", ".get_detail_modal", function(e) {
      var id = $(this).attr('data-id');
      $("#lead_company_details").modal({
          backdrop: 'static',
          keyboard: false,
          callback: fn_rander_company_details(id)
      });
  });

  $("body").on("click", ".upload_csv_fb_ig", function(e) {
      //var id = $(this).attr('data-id');
      $("#upload_csv_fb_ig_modal").modal({
          backdrop: 'static',
          keyboard: false,
          //callback: fn_rander_company_details(id)
      });
  });	

  $("body").on("click",".get_fb_ig_error_log",function(e){
      var base_url = $("#base_url").val();
      var uploaded_csv_file_name=$("#uploaded_csv_file_name").val();
      data='uploaded_csv_file_name='+uploaded_csv_file_name;
      $.ajax({
            url: base_url + "lead/get_fb_ig_error_log_ajax",
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
            success: function(data){
                result = $.parseJSON(data); 
                $("#upload_csv_fb_ig_modal").modal('hide');
                $("#fb_ig_csv_error_log_content").html(result.html);
                $('#fb_ig_csv_error_log_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                //alert(1);
                //$('.error-table-holder').doubleScroll();
            }
        });
  });

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
            url: base_url + "lead/get_upload_csv_error_log_ajax",
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

  // -----------------------------------------------
  // doenload from gmail
  $("body").on("click",".download_from_gmail",function(e){
      var base_url = $("#base_url").val();      
      data='';      
      $.ajax({
            url: base_url + "lead/download_from_gmail_ajax",
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
            success: function(data){
                result = $.parseJSON(data);
                //alert(result.msg);
                $("#download_from_gmail_content").html(result.html);
                $('#download_from_gmail_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });               
            }
        });
  });

  $("body").on("click",".show_details",function(e){
    var id=$(this).attr('data-id');
    $("#detail_"+id).toggle();
  });
  // download from gmail
  // -----------------------------------------------


  // -----------------------------------------------
  // WEB WHATSAPP MSG


  $(document).on("click","#whatsapp_msg",function(event) {
      event.preventDefault();
      $('.what-templete').slideToggle();
  });
  $(document).on("click","#add_msg",function(event) {
      event.preventDefault();
      // $('.add_txt').slideToggle();
      // $(this).prop('disabled', true);
      $('.add_txt').show();
      $(this).hide();
  });
  $(document).on("click",".what-templete ul li input:radio",function(event) {
    var base_url = $("#base_url").val();     
    var getval = $(this).parent().parent().find('.use_m').attr("data-text");
    var id = $(this).parent().parent().find('.use_m').attr("data-id");
    var lead_id=$("#lead_id").val();
    //use_m
    // alert(id);
    var data='id='+id+'&lead_id='+lead_id;
    // alert(data);
    $.ajax({
            url: base_url + "lead/rander_web_whatsapp_template_html",
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
            success: function(data){
                result = $.parseJSON(data);     
                // alert(result.html)           
                $('#whatsapp_txt').val(result.html);
                $('.what-templete').slideUp();
                $(this).prop('checked', false);            
            }
        });
    
  });
  $(document).on("click","#save_msg",function(event) {
        event.preventDefault();
        // var getval = $('.add_txt .msg-input').val();
        var base_url = $("#base_url").val(); 
        var t_title=$("#t_title").val();
        var t_desc=$("#t_desc").val();       
        let t_desc_new = t_desc.replace(/#|%0A/g,'<BR>');
        var data='t_title='+t_title+'&t_desc='+t_desc_new;
        if(t_title=='')
        {
             swal("Oops!", 'Template title is required.', "error");
             return false;
        }
        if(t_desc=='')
        {
             swal("Oops!", 'Template description is required.', "error");
             return false;
        }
        $.ajax({
              url: base_url + "lead/save_web_whatsapp_template",
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
              success: function(data){
                  result = $.parseJSON(data);
                  if(result.status=='success')
                  {
                    var t_added_id=result.id;
                    swal("Success", 'A new template added.', "success");
                    var hh = `<li>
                              <label class="check-box-sec">
                                <input type="radio" value="" class="" name="pre_templete">
                                  <span class="checkmark"></span>
                              </label><span class="use_m" data-text="`+t_desc+`" data-id="`+t_added_id+`">`
                              +t_title+
                            `</span></li>`;
                    $('.what-templete ul').append(hh);
                    $(".what-scroller").mCustomScrollbar("scrollTo","bottom");
                    $('.add_txt').hide();
                    $('#add_msg').show();
                    $('#t_title').val('');
                    $('#t_desc').val('');
                  }               
              }
          });
        //https://web.whatsapp.com/send?phone=+919831767490&text=
        
        
    });
  $("body").on("click","#close_msg",function(e){
    event.preventDefault();
    $('.add_txt').hide();
    $('#add_msg').show();
    $('#t_title').val('');
    $('#t_desc').val('');
  });
  $("body").on("click",".delete_template",function(e){
    event.preventDefault();
    var base_url = $("#base_url").val(); 
    var id=$(this).attr('data-id');
    var data='id='+id; 
    $.ajax({
            url: base_url + "lead/delete_web_whatsapp_template",
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
            success: function(data){
                result = $.parseJSON(data);
                if(result.status=='success')
                {
                  swal("Success!", 'The template successfully deleted', "success"); 
                  $("#li_"+id).html('');
                }               
            }
        });
  });
  $("body").on("click",".web_whatsapp_popup",function(e){
      var base_url = $("#base_url").val(); 
      var lead_id=$(this).attr('data-leadid');
      var cust_id=$(this).attr('data-custid');
      var data='lead_id='+lead_id+'&cust_id='+cust_id; 

      $.ajax({
            url: base_url + "lead/rander_web_whatsapp_popup",
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
            success: function(data){
                result = $.parseJSON(data);
                //alert(result.msg);
                $("#WebWhatsappModal").html(result.html);
                $('#WebWhatsappModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });               
            }
      });
    
  });

  

  $("body").on("click","#whatsapp_send_confirm",function(e){
      event.preventDefault();
       
      var base_url = $("#base_url").val(); 
      var whatsapp_txt=$("#whatsapp_txt").val();
      var is_mobile=$("#is_mobile").val();
      if(whatsapp_txt=='')
      {
         swal("Oops!", 'Message should not be blank.', "error"); 
         return false; 
      }
      $.ajax({
          url: base_url+"lead/web_whatsapp_sent_ajax",
          data: new FormData($('#frmWebWhatsappSend')[0]),
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
            if(result.status=='success')
            {
              //$('#WebWhatsappModal').modal('hide');
              $("#whatsapp_content_div").hide();
              $("#whatsapp_sent_confirm_div").show();                           
              var recipient_mobile=result.recipient_mobile;
              // window.open('https://web.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt, '_blank');
              //var web_whatsapp_url='https://wa.me/'+recipient_mobile+'?text='+whatsapp_txt;
              // var web_whatsapp_url='https://api.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
              // alert(whatsapp_txt)
              if(is_mobile=='Y')
              {
                var web_whatsapp_url='https://api.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
              }
              else
              {
                var web_whatsapp_url='https://web.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
              }
              let params = 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=4000,height=4000';
              popup = window.open(web_whatsapp_url, "WhatsAppPopup", params);
              popup.focus();
            }   
          }
      });
  });

  $("body").on("click","#web_whatsapp_sent_submit",function(e){
    event.preventDefault();
    var base_url = $("#base_url").val(); 
    var is_message_sent = $("input:radio[name=is_message_sent]:checked").val()
    
    if(is_message_sent=='Y')
    {
        $("#is_history_update").val("Y");  
        $("#mobile_whatsapp_status").val("1"); 
    }
    else if(is_message_sent=='N')
    {
        $("#is_history_update").val("N");  
        $("#mobile_whatsapp_status").val("0"); 
    }
    else if(is_message_sent=='NOT_VALIDE')
    {
        $("#is_history_update").val("Y");  
        $("#mobile_whatsapp_status").val("2"); 
    }

    $.ajax({
          url: base_url+"lead/web_whatsapp_sent_ajax",
          data: new FormData($('#frmWebWhatsappSend')[0]),
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
            if(result.status=='success')
            {
              $('#WebWhatsappModal').modal('hide');
              load();
              // swal({
              //       title: '',
              //       text: 'Lead updated successfully',
              //       type: 'success',
              //       showCancelButton: false
              //   }, function() {
              //       $('#WebWhatsappModal').modal('hide');
              //       load();
              //   });
                                         
            }   
          }
      });

  });

  /*
  $("body").on("click","#add_webwhatsapp_history",function(e){
      event.preventDefault();
      var base_url = $("#base_url").val(); 
      $("#is_history_update").val("Y");  
      $("#mobile_whatsapp_status").val("1");  
      $.ajax({
          url: base_url+"lead/web_whatsapp_sent_ajax",
          data: new FormData($('#frmWebWhatsappSend')[0]),
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
            if(result.status=='success')
            {
              $('#WebWhatsappModal').modal('hide');                           
            }   
          }
      });
  });

  $("body").on("click","#no_action",function(e){
    event.preventDefault();
    $('#WebWhatsappModal').modal('hide');  
  });

  $("body").on("click","#not_whatsapp_number",function(e){
      event.preventDefault();
      var base_url = $("#base_url").val(); 
      $("#is_history_update").val("N");  
      $("#mobile_whatsapp_status").val("2");  
      $.ajax({
          url: base_url+"lead/web_whatsapp_sent_ajax",
          data: new FormData($('#frmWebWhatsappSend')[0]),
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
            if(result.status=='success')
            {
              $('#WebWhatsappModal').modal('hide');                           
            }   
          }
      });
  });
  */

  // WEB WHATSAPP MSG
  // -----------------------------------------------

  
  $("body").on("click", ".edit_customer_view", function(e) {

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



  $("body").on("change","#sort_by",function(e){
    var opt_val=$(this).val();    
    $("#filter_sort_by").val(opt_val);
    $(".sort_order").removeClass('desc');
    $(".sort_order").removeClass('asc');
    load();
  });

  // ===================================================
  // ADD NEW LEAD
  

  $("body").on("click","#rander_add_new_lead_view",function(e){
      e.preventDefault();
      // console.log('rander_add_new_lead_view');
      rander_add_new_lead_view();
  });
  /*
  $("body").on("click","#rander_add_new_lead_view",function(e){
      e.preventDefault();
      var base_url = $("#base_url").val();
        $.ajax({
              url: base_url + "lead/add_ajax",
              type: "POST",
              data: {
                  'is_search_box_show': 'Y'
              },
              async: true,
              beforeSend: function(xhr) {
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
                $('#rander_add_new_lead_view_html').html(response);
                $('#rander_add_new_lead_view_modal').modal({backdrop: 'static',keyboard: false});
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
  */

  $("body").on("click","#search_company_submit_confirm",function(e){
      e.preventDefault();
      var base_url = $("#base_url").val();
      var email_obj=$("#email");
      var mobile_obj=$("#mobile");
      var flag=1;
      var status=false;
      if(email_obj.val()!='')
      {
          flag=0;          
      }

      if(mobile_obj.val()!='')
      {
         flag=0;
      }

      if(flag==1)
      {        
        swal('Oops! Search by valid E-mail/Mobile.');
        return false;
      }
      else
      {
        if(email_obj.val()!='')
        {
            if(is_email_validate(email_obj.val())==false)
            {
                swal('Oops! Search by valid E-mail.');
                return false;
            }
            else
            {
                status=true;
            }
        }
        else
        {
            status=true;
        }
        
      }

      if(status==true)
      {
          $.ajax({
          url: base_url + "lead/add_ajax",
          data: new FormData($('#searchCompanyFrm')[0]),
          cache: false,
          method: 'POST',
          dataType: "html",
          mimeType: "multipart/form-data",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function(xhr) {
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
            //result = $.parseJSON(data);
            if(response=='blacklist'){
                swal('Oops!','The buyer is backlisted.','error');
                return false;
            }
            else{
              $('#rander_add_new_lead_view_html').html(response);
              $('#rander_add_new_lead_view_modal').modal({backdrop: 'static',keyboard: false});
            }
            
          }
      });
      }
            
  });

  $("body").on("click","#get_lead_add_view",function(e){
    $("#lead_add_div").show();
    $("#multiple_company_div").hide();
  });

  $("body").on("click","#add_to_lead_submit_confirm",function(e){

      e.preventDefault();

      var url_str=window.location.pathname.substring(1, window.location.pathname.length-1);      
      var url_arr=url_str.split("/");  
      var last_uri_segment=url_arr[url_arr.length-1];
      

      var base_url = $("#base_url").val();
      var product_tags_obj=$("#product_tags");
      var lead_title_obj=$("#lead_title");
      var com_source_id_obj=$("#com_source_id");
      //var lead_requirement_obj=$("#lead_requirement");
      var lead_requirement = tinyMCE.activeEditor.getContent();
      var com_contact_person_obj=$("#com_contact_person");
      var com_company_name_obj=$("#com_company_name");
      var com_country_id_obj=$("#com_country_id");
      var com_state_id_obj=$("#com_state_id");
      var assigned_user_id_obj=$("#assigned_user_id");

      var lead_enq_date_obj=$("#lead_enq_date");
      var lead_follow_up_date_obj=$("#lead_follow_up_date");
      
      
      
      var com_designation_obj=$("#com_designation");
      var com_alternate_email_obj=$("#com_alternate_email");

      
      

      if(lead_title_obj.val()=='')
      {
        lead_title_obj.addClass('error_input');
        $("#lead_title_error").html('Please enter lead Product / Service Required');
        product_tags_obj.focus();
        return false;
      }
      else
      {
        product_tags_obj.removeClass('error_input');
        $("#lead_title_error").html('');
      }

      if(com_source_id_obj.val()=='')
      {
        com_source_id_obj.addClass('error_input');
        $("#com_source_id_error").html('Please select source');
        com_source_id_obj.focus();
        return false;
      }
      else
      {
        com_source_id_obj.removeClass('error_input');
        $("#com_source_id_error").html('');
      }


      $('#lead_requirement').val(lead_requirement);
      if(lead_requirement=='')
      {
        //lead_requirement.addClass('error_input');
        $("#lead_requirement_error").html('Please describe requirements');
        //lead_requirement.focus();
        return false;
      }
      else
      {
        //lead_requirement.removeClass('error_input');
        $("#lead_requirement_error").html('');
      }

      if(com_contact_person_obj.val()=='')
      {
        com_contact_person_obj.addClass('error_input');
        $("#com_contact_person_error").html('Please enter contact person');
        com_contact_person_obj.focus();
        return false;
      }
      else
      {
        com_contact_person_obj.removeClass('error_input');
        $("#com_contact_person_error").html('');
      }

      // if(com_company_name_obj.val()=='')
      // {
      //   com_company_name_obj.addClass('error_input');
      //   $("#com_company_name_error").html('Please enter company name');
      //   com_company_name_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_company_name_obj.removeClass('error_input');
      //   $("#com_company_name_error").html('');
      // }

      if(com_country_id_obj.val()=='')
      {
        com_country_id_obj.addClass('error_input');
        $("#com_country_id_error").html('Please select country');
        com_country_id_obj.focus();
        return false;
      }
      else
      {
        com_country_id_obj.removeClass('error_input');
        $("#com_country_id_error").html('');
      }

      if(assigned_user_id_obj.val()=='')
      {
        assigned_user_id_obj.addClass('error_input');
        $("#assigned_user_id_error").html('Please select account manager');
        assigned_user_id_obj.focus();
        return false;
      }
      else
      {
        assigned_user_id_obj.removeClass('error_input');
        $("#assigned_user_id_error").html('');
      }


      if(lead_enq_date_obj.val()=='')
      {
        lead_enq_date_obj.addClass('error_input');
        $("#lead_enq_date_error").html('Please select enquiry date');
        lead_enq_date_obj.focus();
        return false;
      }
      else
      {
        lead_enq_date_obj.removeClass('error_input');
        $("#lead_enq_date_error").html('');
      }

      if(lead_follow_up_date_obj.val()=='')
      {
        lead_follow_up_date_obj.addClass('error_input');
        $("#lead_follow_up_date_error").html('Please select follow up date');
        lead_follow_up_date_obj.focus();
        return false;
      }
      else
      {
        lead_follow_up_date_obj.removeClass('error_input');
        $("#lead_follow_up_date_error").html('');
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

      // if(com_alternate_email_obj.val()!='')
      // {
      //   if(is_email_validate(com_alternate_email_obj.val())==false)
      //   {
      //       com_alternate_email_obj.addClass('error_input');
      //       $("#com_alternate_email_error").html("Please enter valid email.");
      //       com_alternate_email_obj.focus();
      //       return false;
      //   }
      //   else
      //   {
      //       com_alternate_email_obj.removeClass('error_input');
      //       $("#com_alternate_email_error").html('');
      //   }
      // }
      // else
      // {
      //       com_alternate_email_obj.removeClass('error_input');
      //       $("#com_alternate_email_error").html('');
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
                $('.btn_enabled').addClass("btn_disabled");
                $(".btn_disabled").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                $("#add_to_lead_submit_confirm").attr("disabled",true);
                $('#rander_add_new_lead_view_modal .modal-body').addClass('logo-loader');
              },
              complete: function (){
                $('#rander_add_new_lead_view_modal .modal-body').removeClass('logo-loader');
              },
              success: function(data){
                result = $.parseJSON(data);
                // console.log(result.msg);
                // alert(result.msg);return false;
                //alert(result.lead_id+' / '+result.company_id);
                $('.btn_enabled').removeClass("btn_disabled");
                $(".btn_enabled").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                $("#add_to_lead_submit_confirm").attr("disabled",false);
                if(result.status=='success')
                {
                    //swal('Success!', 'A new lead successfully added', 'success');
                    $('#rander_add_new_lead_view_modal').modal('hide');
                    

                    if(last_uri_segment=='manage_sync_call' || last_uri_segment=='manage_sync_cal'){
                      load_sync_call();
                    }
                    else{
                      load();
                    }

                    // if(result.call_sync_id){
                    //   load_sync_call();
                    // }
                    // else{
                    //   load();
                    // }
                    
                    rander_customer_edit_view(result.company_id,result.lead_id);
                    
                    /*
                    swal({
                          title: "Success!",
                          text: "A new lead successfully added.",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false",
                          showCancelButton: false,
                          closeOnConfirm: true
                      }, function () {                           
                          window.location.href=base_url+"lead/add";                        
                      });
                    */
                }   
              }
            }); 

  });
  
  $("body").on("click", "#update_customer_submit", function(e) {
        e.preventDefault();

        var url_str=window.location.pathname.substring(1, window.location.pathname.length-1);      
        var url_arr=url_str.split("/");  
        var last_uri_segment=url_arr[url_arr.length-1];

        var base_url = $("#base_url").val();        
        var email_obj= $("#email");
        var alt_email_obj= $("#alt_email");
        var website_obj= $("#website");

        if(email_obj.val()!='')
        {
        	if(is_email_validate(email_obj.val())==false)
        	{
        		swal('Oops! Please enter valid Buyer’s Email.');
        		return false;
        	}
        }

        if(alt_email_obj.val()!='')
        {
        	if(is_email_validate(alt_email_obj.val())==false)
        	{
        		swal('Oops! Please enter valid Alternate Email.');
        		return false;
        	}
        }

        if(website_obj.val()!='')
        {
        	if(isUrl(website_obj.val())==false)
        	{
        		swal('Oops! Please enter valid Website.');
        		return false;
        	}
        }

        

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
                        title: 'Company details updated successfully!',
                        text: '',
                        type: 'success',
                        showCancelButton: false
                    }, function() {

                        if(last_uri_segment=='manage_sync_call' || last_uri_segment=='manage_sync_cal'){
                          load_sync_call();
                        }
                        else{
                          load();
                        }                        
                        $("#lead_info_div").html('');
                        $("#lead_info_div").hide();
                        $('#edit_customer_view_modal').modal('hide');
                    });
                }
            }
        });
    });


  $("body").on("click",".add_source_popup",function(e){    
    $("#add_source_modal").modal({backdrop: 'static',keyboard: false });
  });
  $("body").on("click","#add_source_submit_confirm",function(e){
    fn_rander_source_add_view();
  });

  $("body").on("click",".view_company_detail",function(e){
    var cid=$(this).attr("data-id");
    var base_url=$("#base_url").val();
    var data="cid="+cid;
    $.ajax({
        url: base_url+"customer/view_company_detail_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data);           
          //alert(result.html);
          $("#lead_company_details_body").html(result.html);
          $('#lead_company_details').modal({backdrop: 'static',keyboard: false}); 
        }
      });
    
  });

  $("body").on("change",".tag_company_id",function(e){
      
      var cid=$(this).val();
      var base_url=$("#base_url").val();
      var data="cid="+cid;
      $.ajax({
          url: base_url+"customer/get_company_detail_ajax",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {},
          success: function(data){
            result = $.parseJSON(data);           
            // console.log(result.row);
            // alert(result.row.company_name)
            // =====================================================
            $("#com_company_id").val(cid);
            $("#com_company_name").val(result.row.company_name);
            if(result.row.company_name){
              $("#com_company_name").attr("readonly",true);
            }
            else{
              $("#com_company_name").attr("readonly",false);
            }

            $("#com_contact_person").val(result.row.contact_person);
            if(result.row.contact_person){
              $("#com_contact_person").attr("readonly",true);
            }
            else{
              $("#com_contact_person").attr("readonly",false);
            }

            $("#com_designation").val(result.row.designation);
            if(result.row.designation){
              $("#com_designation").attr("readonly",true);
            }
            else{
              $("#com_designation").attr("readonly",false);
            }

            $("#com_email").val(result.row.email);
            if(result.row.email){
              $("#com_email").attr("readonly",true);
            }
            else{
              $("#com_email").attr("readonly",false);
            }

            $("#com_alternate_email").val(result.row.alt_email);
            if(result.row.alt_email){
              $("#com_alternate_email").attr("readonly",true);
            }
            else{
              $("#com_alternate_email").attr("readonly",false);
            }

            $("#com_mobile_country_code").val(result.row.mobile_country_code);
            if(result.row.mobile_country_code){
              $("#com_mobile_country_code").attr("readonly",true);
            }
            else{
              $("#com_mobile_country_code").attr("readonly",false);
            }

            $("#com_mobile").val(result.row.mobile);
            if(result.row.mobile){
              $("#com_mobile").attr("readonly",true);
            }
            else{
              $("#com_mobile").attr("readonly",false);
            }

            $("#com_alt_mobile_country_code").val(result.row.alt_mobile_country_code);
            if(result.row.alt_mobile_country_code){
              $("#com_alt_mobile_country_code").attr("readonly",true);
            }
            else{
              $("#com_alt_mobile_country_code").attr("readonly",false);
            }

            $("#com_alternate_mobile").val(result.row.alt_mobile);
            if(result.row.alt_mobile){
              $("#com_alternate_mobile").attr("readonly",true);
            }
            else{
              $("#com_alternate_mobile").attr("readonly",false);
            }


            $("#com_landline_country_code").val(result.row.landline_country_code);
            if(result.row.landline_country_code){
              $("#com_landline_country_code").attr("readonly",true);
            }
            else{
              $("#com_landline_country_code").attr("readonly",false);
            }

            $("#com_landline_std_code").val(result.row.landline_std_code);
            if(result.row.landline_std_code){
              $("#com_landline_std_code").attr("readonly",true);
            }
            else{
              $("#com_landline_std_code").attr("readonly",false);
            }

            $("#landline_number").val(result.row.landline_number);
            if(result.row.landline_number){
              $("#landline_number").attr("readonly",true);
            }
            else{
              $("#landline_number").attr("readonly",false);
            }


            $("#com_address").val(result.row.address);
            if(result.row.address){
              $("#com_address").attr("readonly",true);
            }
            else{
              $("#com_address").attr("readonly",true);
            }

            $("#com_country_id").val(result.row.country_id);
            if(result.row.country_id>0)
            {
                // alert(result.row.country_id);
                //GetStateList(result.row.country_id);
                // alert(result.row.country_id+'/'+result.row.state+'/'+result.row.city)				 
                GetStateList(result.row.country_id,'#com_state_id',result.row.state);
                 $("#com_country_id").attr("disabled",true);
                 $("#com_existing_country").val(result.row.country_id);

                if(result.row.state>0)
                {					
					          GetCityList(result.row.state,'#com_city_id',result.row.city);
                    $("#com_existing_state").val(result.row.state);
                    $("#com_state_id").attr("disabled",true);
                }
                else
                {
                  $("#com_state_id").val($("#com_state_id option:first").val());
                  $("#com_state_id").attr("disabled",false);
                }

                if(result.row.city>0)
                {
					
                  $("#com_existing_city").val(result.row.city);
                  $("#com_city_id").attr("disabled",true);
                }
                else
                {
                  $("#com_city_id").val($("#com_city_id option:first").val());
                  $("#com_city_id").attr("disabled",false);
                }
            }
            else
            {
              $("#com_country_id").val($("#com_country_id option:first").val());
              $("#com_country_id").attr("disabled",false);

              $("#com_state_id").val($("#com_state_id option:first").val());
              $("#com_state_id").attr("disabled",false);

              $("#com_city_id").val($("#com_city_id option:first").val());
              $("#com_city_id").attr("disabled",false);
            }

            // $("#com_state_id").val();  
            // $("#com_city_id").val();
            $("#com_zip").val((result.row.zip!=0)?result.row.zip:'');
            if(result.row.zip>0){
              $("#com_zip").attr("readonly",true);
            }
            else{
              $("#com_zip").attr("readonly",false);
            }

            $("#com_website").val(result.row.website);
            if(result.row.website){
              $("#com_website").attr("readonly",true);
            }
            else{
              $("#com_website").attr("readonly",false);
            }

            $("#com_source_id").val(result.row.source_id);
            if(result.row.source_id>0)
            { 
              $("#com_existing_source").val(result.row.source_id);
              //$("#com_source_id").attr("disabled",true);
              $(".add_source_popup").css("display","none");
            }
            else
            { 
              $("#com_source_id").val($("#com_source_id option:first").val());
              $("#com_source_id").attr("disabled",false);
              $(".add_source_popup").css("display","block");
            }

            $("#com_short_description").val(result.row.short_description);
            if(result.row.short_description){
              $("#com_short_description").attr("readonly",true);
            }
            else{
              $("#com_short_description").attr("readonly",false);
            }


            $("#assigned_user_id").val(result.row.assigned_user_id);
            
            // =====================================================
          }
        });
  });

  


	$("body").on("change","#product_tags",function(e){
		var product_tags=$(this).val();
		var product_tags_str = product_tags.join(", ");
    var new_text=''+product_tags_str;
		$("#lead_title").val(new_text);		
	});
  // ADD NEW LEAD
  // ===================================================


	// ===================================================
	// CUSTOMER REPLY
	$("body").on("click",".open_cust_reply_box",function(e){  
	   var lead_id = $(this).attr('data-leadid');
	   var customer_id = $(this).attr('data-custid');
	   //alert(lead_id+' / '+customer_id); return false;
	   fn_open_cust_reply_box_view(lead_id);
	});
  $("body").on("click","#cust_reply_submit_confirm",function(e){   
	   var ThisObj=$(this);
	   var base_URL = $("#base_url").val();  
	   var reply_mail_to=$("#reply_mail_to").val();  
	   var reply_mail_subject=$("#reply_mail_subject").val();    
	   var box = $('.buying-requirements');
	   var email_body = box.html();       
	   $('#reply_email_body').val(email_body);      

	   if(reply_mail_to=='')
	   {
	       swal("Oops", "Please specify at least one recipient.", "error");
	       return false;
	   }

	   if(reply_mail_subject=='')
	   {
	       swal("Oops", "Please enter subject.", "error");	       
	       return false;
	   }

	   if(email_body=='')
	   {
	       swal("Oops", "Please enter mail body", "error");
	       return false;
	   }
	    //alert(reply_mail_to+' / '+reply_mail_to_cc+' / '+email_body); return false;
	   $.ajax({                
	       url: base_URL+"lead/cust_reply_sent_ajax",
	       data: new FormData($('#cust_reply_mail_frm')[0]),
	       cache: false,
	       method: 'POST',
	       dataType: "html",
	       mimeType: "multipart/form-data",
	       contentType: false,
	       cache: false,
	       processData: false,
	       beforeSend: function( xhr ) {
	       		$('#ReplyPopupModal .modal-body').addClass('logo-loader');
  	      },
          complete: function (){
              $('#ReplyPopupModal .modal-body').removeClass('logo-loader');
          },            
	       success:function(res){ 
	          result = $.parseJSON(res);          
	          // alert(result.msg);
	          if(result.status=='success')
	          {
	            swal({
	                title: "Success!",
	                text: "Mail to customer successfully sent",
	                type: "success",
	                showCancelButton: false,
	                confirmButtonClass: 'btn-warning',
	                confirmButtonText: "Ok",
	                closeOnConfirm: true
	            }, function () {
                  $('#ReplyPopupModal').html('');
	                $('#ReplyPopupModal').modal('hide');
	            });                    
	          }
	          else
	          {
	               swal('Fail!', 'There have some system error! Please try again later.', 'error');
	          }
	                             
	      },         
	      error: function(response) {}
	  }); 
  });
	// CUSTOMER REPLY
	// ===================================================

    $("#ReplyPopupModal").on('hide.bs.modal', function(){
      $('#ReplyPopupModal').html('');
    });

    $("#CommentUpdateLeadModal").on('hide.bs.modal', function(){
      $('#CommentUpdateLeadModal').html('');
    });

  	// ======================================================
    // UPDATE COMMENT OF LEAD 
    $("body").on("click",".rander_update_lead_view2",function(e){
       var lead_id=$(this).attr('data-id'); 
       $("#is_back_show").val('Y');      
       open_lead_update_lead_view(lead_id);
    });

    $("body").on("click","#lead_update_confirm",function(e){
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
          swal('Oops! Comments should not be blank.');
          return false;
       }

       if (communication_type == '') 
       {
          swal('Oops! Please select communication type.');
          return false;
       }
        
        var d1=defaultFormatDate($("#curr_date_chk").val());
        var d2=defaultFormatDate(next_followup_date); 
        if (d1>d2) 
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
                ThisObj.attr("disabled",true);
                $('#CommentUpdateLeadModal .modal-body').addClass('logo-loader');
             },
             complete: function(){
                ThisObj.attr("disabled",false);
                $('#CommentUpdateLeadModal .modal-body').removeClass('logo-loader');
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
                               $("#CommentUpdateLeadModal").html(''); 
                               $('#CommentUpdateLeadModal').modal('hide');
                               load();                           
                      		});
                   } 
                   else 
                   {

                   }
             }
       });                      
    });

    $("body").on("click",".po_upload_view",function(e){
        var is_q_exist=$(this).attr('data-is_q_exist');
        var lid=$(this).attr('data-lid');
        var l_opp_id=$(this).attr('data-loppid');
        if(is_q_exist=='N')
        {
            swal({
                title: "",
                text: "Do you want this lead to be marked WON without quotation?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-warning',
                cancelButtonText: "No, cancel it!",
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, do it!",
                closeOnConfirm: true
            },
            function() {
                fn_get_po_upload_view_without_quotation(lid,'');                         
            });
        }
        else
        {
            if(l_opp_id!='')
            {      
              fn_get_po_upload_view(lid,l_opp_id);
            }
            else
            {
              fn_get_opp_id_view(lid);
            } 
        }
          
    });
    $("body").on("click","#back_to_linked_comment_update_lead_popup3",function(e)
    {
       var lead_id=$(this).attr('data-leadid');   
       $('#QuotationListModal').modal('hide'); 
       open_lead_update_lead_view(lead_id);
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

    $("body").on("click","#back_to_linked_comment_update_lead_popup2",function(e)
    {
       var lead_id=$(this).attr('data-leadid');
       var is_multiple_quotation=$(this).attr('data-is_multiple_quotation');   
       $('#PoUploadLeadModal').modal('hide'); 
       if(is_multiple_quotation=='Y')
       {
          fn_get_opp_id_view(lead_id);
       }
       else
       {
          open_lead_update_lead_view(lead_id);
       }
       
    });

    $("body").on("click","#back_to_linked_comment_update_lead_popup",function(e){
       var lead_id=$(this).attr('data-leadid');   
       //$('#RegretUpdateLeadModal').modal('hide'); 
       open_lead_update_lead_view(lead_id);
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
              $('#CommentUpdateLeadModal .modal-body').addClass('logo-loader');
	         },
           complete: function (){
              $('#CommentUpdateLeadModal .modal-body').removeClass('logo-loader');
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
	                           $('#CommentUpdateLeadModal').modal('hide');
                               load();
	                  		});
	               } 
	               else 
	               {

	               }
	         }
	   });                      
	}); 

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


        // if (po_number_obj.val() == '') {
        // swal('Oops! Please enter PO Number.');
        // return false;           
        // }


        if (deal_value_as_per_purchase_order_obj.val() == '') {
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
	      if (po_upload_cc_to_employee_obj.val() == null) {

	      swal('Oops! Please select CC to Employee.');
	      return false;            
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
                      // go_to_next_step(2);
                      // $("#lead_opportunity_wise_po_id").val(result.lead_opportunity_wise_po_id);
                      // load();
	                 });
	             }
	         }
	      });
	});

  	function validate_fileupload(fileName) 
  	{
  	    var allowed_extensions = new Array("pdf","doc","docx");
  	    var file_extension = fileName.split('.').pop().toLowerCase(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

  	    for (var i = 0; i <= allowed_extensions.length; i++) {
  	        if (allowed_extensions[i] == file_extension) {
  	            return true; // valid file extension
  	        }
  	    }
  	    return false;
  	}
    // UPDATE COMMENT OF LEAD
    // ======================================================

    // ======================================================
    // QUOTATION/PROPOSAL VIEW
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

    // QUOTATION/PROPOSAL VIEW
    // ======================================================

    // ========================================================
    // PO UPLOAD WITHOUT QUOTATION
    $("body").on("click", "#po_upload_without_quotation_submit", function(e) {
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

        // if (po_number_obj.val() == '') {
        // swal('Oops! Please enter PO Number.');
        // return false;           
        // }

        if (deal_value_as_per_purchase_order_obj.val() == '') {
        swal('Oops! Please enter Purchase Order amount.');
        return false;           
        }        

        if(po_upload_file_obj.val())
        {
            if (validate_fileupload(po_upload_file_obj.val()) == false) {
              swal('Oops! The PO attachment should be pdf, doc or docx.');
              return false; 
            }
        }         

        if (po_upload_describe_comments_obj.val() == '') {
        swal('Oops! Please enter your comments.');
        return false;             
        }
        if (po_upload_cc_to_employee_obj.val() == null) {

        swal('Oops! Please select CC to Employee.');
        return false;            
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
           url: base_url + "lead/po_upload_post_without_quotation_ajax",
           data: new FormData($('#frmPoUpload')[0]),
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
              $("#po_upload_without_quotation_submit").attr("disabled", true);
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
                       showCancelButton: false,
                       closeOnConfirm: true,
                   }, function() {
                      var redirect_uri_str=result.redirect_uri_str;
                      document.location.href = base_url+'order/po_register/?'+redirect_uri_str;
                      
                      // go_to_next_step(2);
                      // $("#lead_opportunity_wise_po_id").val(result.lead_opportunity_wise_po_id);
                      // load();
                   });
               }
           }
        });
    });
    // PO UPLOAD WITHOUT QUOTATION
    // ========================================================

    
    // ========================================================  
    // LIKE AND DISLIKE FUNCTIONALITY
    $("body").on("click",".like_icon",function(event){
      event.preventDefault();
      var getId = $(this).attr('data-id');
      //<input type="text" name="like_id_field" id="like_id_field">
      $(this).parent().parent().parent().parent().parent().addClass('on-show');
      $('#like_id_field').val(getId);
      var x = $(this).offset();
      var getTop = x.top-54;
      var getLeft = x.left+45;
      var getRight = x.left;
      var base_url = $("#base_url").val();   
      $.ajax({
         url: base_url + "lead/rander_like_stage_view_ajax",
         type: "POST",
         data: {
             'lid':getId
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
               
              $("#like_stage_view").html(response);
              if (window.innerWidth > 768) {
                //alert(getRight);
                var newRight = getLeft-310;
                $('#like_pop_block').css({'left':newRight, 'top': getTop}).fadeIn();
              }else{
                //alert(2);
                var newLeft = (window.innerWidth-250)/2;
                var newTop = x.top+30;
                //var getTop = x.top-61;
                //var getLeft = x.left+45;
                $('#like_pop_block').addClass('popMobile').css({'left':newLeft, 'top': newTop}).fadeIn();
              }
              //$('#like_pop_block').css({'left':getLeft, 'top': getTop}).fadeIn();
              $('.like_overlay').fadeIn();
         },
         error: function() {
             
         }
      });
      
    }); 

    // $("body").on("click","input:checkbox[name=stage_id]",function(e){
    //     var stage_id=$(this).val();        
    //     if(document.getElementById("stage_id_2").checked==true){
    //       document.getElementById("stage_id_8").checked = true;
    //     } 

    //     if(document.getElementById("stage_id_4").checked==true){
    //       document.getElementById("stage_id_8").checked = true;
    //       document.getElementById("stage_id_2").checked = true;
    //       document.getElementById("stage_id_9").checked = true;
    //     }
    // });

    $("body").on("click","#like_btn_confirm",function(e){
      //var stage_id=$("input:checkbox[name=stage_id]:checked").val();
      var thisBtn=$(this);
      var base_url=$("#base_url").val(); 
      var lead_id=$("#lid").val();   
      var last_checked_stage=$("#last_checked_stage").val();  
      var stage_id_arr=[];
      $("input:checkbox[name=stage_id]:checked").each(function (i) {
          stage_id_arr[i] = $(this).val();          
      });
      var stage_id_str=stage_id_arr.join();
      var last_stage_id=stage_id_arr.slice(-1).pop();
      if(stage_id_arr.length==1)
      {
          swal('Oops','Please choose any stage.','error');
          return false;
      }      
      
      var data="all_stage_id_str="+stage_id_str+"&last_stage_id="+last_stage_id+"&lead_id="+lead_id+"&last_checked_stage="+last_checked_stage;
      //alert(data); return false;
      $.ajax({
              url: base_url + "lead/update_lead_stage_ajax",
              data: data,
              cache: false,
              method: 'POST',
              dataType: "html",
              beforeSend: function(xhr) {
                  $('#like_pop_block .pop-body').addClass('logo-loader');
                  $('#like_btn_confirm').attr('disabled',true);
              },
              complete: function (){
                  //$('#dislike_'+lead_id).addClass('down');
                  $('#like_pop_block .pop-body').removeClass('logo-loader');
                  $('#like_btn_confirm').attr('disabled',false);
              },
                success: function(res) {
                  result = $.parseJSON(res);
                  //alert(result.is_q_exist+'/'+result.loppid); return false;

                  if(result.status=='success')
                  {
                      load();
                      $('.list_view .custom-table tbody').find('.on-show').removeClass('on-show');
                      $("#like_pop_block").fadeOut();
                      $('.like_overlay').fadeOut();
                  }
                  else if(result.status=='deal_won')
                  {
                      $("#like_pop_block").fadeOut();
                      $('.like_overlay').fadeOut();
                      $('.list_view .custom-table tbody').find('.on-show').removeClass('on-show');
                      $("#is_back_show").val('N');
                      if(result.is_q_exist=='N')
                      {
                          swal({
                              title: "",
                              text: "Do you want this lead to be marked WON without quotation?",
                              type: "warning",
                              showCancelButton: true,
                              cancelButtonClass: 'btn-warning',
                              cancelButtonText: "No, cancel it!",
                              confirmButtonClass: 'btn-warning',
                              confirmButtonText: "Yes, do it!",
                              closeOnConfirm: true
                          },
                          function() {
                              fn_get_po_upload_view_without_quotation(lead_id,'');                         
                          });
                      }
                      else
                      {
                          if(result.loppid!='')
                          {      
                            fn_get_po_upload_view(lead_id,result.loppid);
                          }
                          else
                          {
                            fn_get_opp_id_view(lead_id);
                          } 
                      }
                  }
                  else
                  {
                      swal('Oops',result.msg,'error');
                  }
                  
                },
                error: function(response) {}
      });
    });
    $("body").on("click",".dislike_action",function(event){
      event.preventDefault();
      //dislike_icon_block
      $(this).parent().parent().parent().parent().parent().addClass('on-show');
      var getId = $(this).attr('data-id');
      //<input type="text" name="like_id_field" id="like_id_field">
      $('#dislike_id_field').val(getId);
      var x = $(this).offset();
      var getTop = x.top-54;
      var getLeft = x.left+45;     

      var base_url = $("#base_url").val();   
      $.ajax({
         url: base_url + "lead/rander_dislike_stage_view_ajax",
         type: "POST",
         data: {
             'lid':getId
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
               
              $("#dislike_stage_view").html(response);
              //$('#dislike_icon_block').css({'left':getLeft, 'top': getTop}).fadeIn();
              if (window.innerWidth > 768) {
                var newRight = getLeft-430;
                $('#dislike_icon_block').css({'left':newRight, 'top': getTop}).fadeIn();
              }else{
                var newLeft = (window.innerWidth-330)/2;
                var newTop = x.top+30;
                $('#dislike_icon_block').addClass('popMobile').css({'left':newLeft, 'top': newTop}).fadeIn();
              }
              $('.like_overlay').fadeIn();
         },
         error: function() {
             
         }
      });
    });

    $("body").on("click","#dislike_btn_confirm",function(e){
        
        var thisBtn=$(this);
        var base_url = $("#base_url").val();
        var lead_regret_reason_id=$("input:radio[name=lead_regret_reason_id]:checked").val();
        var lead_regret_reason=$("input:radio[name=lead_regret_reason_id]:checked").attr("data-text");
        var lead_id=$("input:radio[name=lead_regret_reason_id]:checked").attr("data-lid");
        var data="lead_regret_reason_id="+lead_regret_reason_id+"&lead_regret_reason="+lead_regret_reason+"&lead_id="+lead_id;
        
        if ($('input[name=lead_regret_reason_id]:checked').length==0) 
        {
          swal('Oops','Please choose any reason.','error');
          return false;
        }
        // alert($('input[name=lead_regret_reason_id]:checked').length); return false;
        //alert(data); return false;

        $.ajax({
                url: base_url + "lead/update_lead_stage_to_lost_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {
                    //thisBtn.attr("")
                    $('#dislike_icon_block .pop-body').addClass('logo-loader');
                    $('#dislike_btn_confirm').attr('disabled',true);
                },
                complete: function (){
                    $('#dislike_icon_block .pop-body').removeClass('logo-loader');
                    $('#dislike_btn_confirm').attr('disabled',false);
                    $('#dislike_'+lead_id).addClass('down');
                },
                  success: function(res) {
                    result = $.parseJSON(res);
                    if(result.status=='success')
                    {
                        load();
                        $('.list_view .custom-table tbody').find('.on-show').removeClass('on-show');
                        $("#dislike_icon_block").fadeOut();
                        $('.like_overlay').fadeOut();
                    }
                    else
                    {
                        swal('Oops',result.msg,'error');
                    }
                    
                  },
                  error: function(response) {}
        });
    });

    
    /////
    // $("body").on("click","#like_pop_block .pop-footer .btn-primary",function(event){
    //   event.preventDefault();
    //   var getId = $('#like_pop_block #like_id_field').val();
    //   $('#like_'+getId).addClass('up');
    //   $('#like_pop_block').fadeOut();
    //   $('.like_overlay').fadeOut();
    // }); 

    // $("body").on("click","#dislike_icon_block .pop-footer .btn-primary",function(event){
    //   event.preventDefault();
    //   var getId = $('#dislike_icon_block #dislike_id_field').val();
      
    //   $('#dislike_'+getId).addClass('down');
    //   $('#dislike_icon_block').fadeOut();
    //   $('.like_overlay').fadeOut();
    // }); 

    //////  
    function closeExpendTable(){
      
      if ($('.ext-table').hasClass('active')) {
        $('.ext-table').removeClass('active');
        //$('.wrapper1').hide();
        $('.grey-card-block.list_view').removeClass('show_hide');
        $('.table-full-holder').css({'width':'100%'});
        $('.table-toggle-holder').removeClass('scroll');
        $(".wrapper1").scrollLeft(0);
        // console.log('cccccc');
        // setTimeout(function(){ 
        //   console.log('ddddd');
        //   $('.show-on-hover').css({'right': '0px','left':'auto'});
        // }, 600);
        //actionPos(0);
        $('.show-on-hover').css({'right': '0px','left':'auto'});
        $('.wrapper1').hide();
      }
    }  
    $("body").on("click",".close-pop",function(event){
      event.preventDefault();
      $(this).parent().parent().fadeOut();
      $('.list_view .custom-table tbody').find('.on-show').removeClass('on-show');
      $('.like_overlay').fadeOut();
    });
    // $(document).on('change', '.other_check', function() {
    //   if(this.checked) {
    //     $(this).parent().parent().parent().find('.other_holder').fadeIn();
    //   }else{
    //     $(this).parent().parent().parent().find('.other_holder').fadeOut();
    //   }
    // });
    // LIKE AND DISLIKE FUNCTIONALITY
    // ======================================================== 

    $("body").on("click","#download_from_indiamart",function(e){          
        var base_url = $("#base_url").val();
        $.ajax({
            url: base_url + "cronjobs/force_download_from_indiamart_ajax",
            type: "POST",
            data: {},
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
              var res_str=response;
              var res_arr = res_str.split("#");
              var status=res_arr[0];
              var msg=res_arr[1];
              if(status=='success')
              {
                swal('success',msg,'success');
                load();
              }
              else
              {
                swal('fail',msg,'error');
              }

            },
            error: function(){}
        });
    }); 
    

    $("body").on("click",".delete_tagged_ps",function(e){
        var base_url = $("#base_url").val();
        var id=$(this).attr("data-id");
        var name=$(this).attr("data-name");
        var lead_id=$(this).attr("data-leadid");
        // alert(id+' / '+name+' / '+lead_id);return false;
        $.ajax({
              url: base_url + "lead/delete_tagged_ps_ajax",
              type: "POST",
              data: {
                  'id': id,
                  'name':name,
                  'lead_id':lead_id,
              },
              async: true,
              success: function(response) {
                if(response=='success')
                {
                    load();
                }   
                else
                {
                    swal({
                        title: "Oops",
                        text: response,
                        type: "error",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    });
                }               
              },
              error: function() {
                  
              }
        });
    });

    $("body").on("click",".tagged_ps",function(e){
        var base_url = $("#base_url").val();
        var lead_id=$(this).attr("data-leadid");
        // alert(lead_id)
        rander_lead_wise_tagged_ps_view(lead_id);
    });

    $("body").on("click","#add_lead_tagged_ps_submit_confirm",function(e){

      e.preventDefault();
      var base_url = $("#base_url").val();
      // var product_tags_obj=$("#product_tags");
      // var lead_title_obj=$("#lead_title");

      // if(lead_title_obj.val()=='')
      // {
      //   lead_title_obj.addClass('error_input');
      //   $("#lead_title_error").html('Please enter lead Product / Service Required');
      //   product_tags_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   product_tags_obj.removeClass('error_input');
      //   $("#lead_title_error").html('');
      // }      
      
      
       $.ajax({
              url: base_url+"lead/add_tagged_ps_ajax",
              data: new FormData($('#frmLeadAddTaggedPS')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function( xhr ) {
                // $('#add_lead_tagged_ps_submit_confirm').addClass("btn_disabled");
                // $("#add_lead_tagged_ps_submit_confirm").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                // $("#add_lead_tagged_ps_submit_confirm").attr("disabled",true);
                $('#rander_add_tagged_ps_view_modal .modal-body').addClass('logo-loader');
              },
              complete: function (){
                $('#rander_add_tagged_ps_view_modal .modal-body').removeClass('logo-loader');
              },
              success: function(data){
                result = $.parseJSON(data);
               
                // $('#add_lead_tagged_ps_submit_confirm').removeClass("btn_disabled");
                // $("#add_lead_tagged_ps_submit_confirm").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                // $("#add_lead_tagged_ps_submit_confirm").attr("disabled",false);
                if(result.status=='success')
                {
                    //swal('Success!', 'A new lead successfully added', 'success');
                    $('#rander_add_tagged_ps_view_modal').modal('hide');
                    load();
                }   
              }
            }); 

  });

    $(document).on("click","#download_leads_csv",function (e){
        
        var base_URL     = $("#base_url").val();    
		var filter_search_str=$("#filter_search_str").val();
		var filter_like_dsc=$("#filter_like_dsc").val(); 
		var filter_search_by_id=$("#search_by_id").val();
		var filter_by_keyword=$("#filter_by_keyword").val();
		
		var filter_lead_from_date=$("#filter_lead_from_date").val();
		var filter_lead_to_date=$("#filter_lead_to_date").val();
		var filter_date_filter_by=$("#filter_date_filter_by").val();
		var filter_assigned_user=$("#filter_assigned_user").val();
		var filter_lead_applicable_for=$("#filter_lead_applicable_for").val();
		var filter_lead_type=$("#filter_lead_type").val();
		var filter_opportunity_stage=$("#filter_opportunity_stage").val();
		var filter_opportunity_status=$("#filter_opportunity_status").val();
		var filter_by_source=$("#filter_by_source").val();
		var filter_is_hotstar=$("#filter_is_hotstar").val();
		var filter_pending_followup=$("#filter_pending_followup").val();
		var filter_pending_followup_for=$("#filter_pending_followup_for").val();
		var filter_followup=(filter_pending_followup=='Y' || filter_search_str!='' || filter_search_by_id!='')?'':$("#filter_followup").val();
		var filter_common_lead_pool=(filter_followup!='')?'N':$("#filter_common_lead_pool").val(); 
		var filter_lead_observer=$("#filter_lead_observer").val();
		var filter_sort_by=$("#filter_sort_by").val();
		var view_type=$("#view_type").val();
		var page=$("#page_number").val(); 
		var is_scroll_to_top=$("#is_scroll_to_top").val(); 
		 
		 
		if((filter_search_str!='' || filter_search_by_id!='') && filter_by_keyword==''){ 
		  lead_filter_reset();     
		}
		
		
		if(filter_common_lead_pool=='Y' || filter_followup!=''){
		  lead_filter_reset();
		}
		if(filter_followup!=''){
			$("input:checkbox[name=common_lead_pool]").attr("checked",false);
		}
		if(filter_pending_followup=='Y' || filter_search_str!='' || filter_search_by_id!='' ){
			
			$("input:radio[name=filter_followup]:checked").each(function(){
				$(this).attr("checked",false);
			});	
		}
		
		var data = "page="+page+"&filter_search_str="+filter_search_str+"&filter_lead_from_date="+filter_lead_from_date+"&filter_lead_to_date="+filter_lead_to_date+"&filter_date_filter_by="+filter_date_filter_by+"&filter_assigned_user="+filter_assigned_user+"&filter_lead_applicable_for="+filter_lead_applicable_for+"&filter_lead_type="+filter_lead_type+"&filter_opportunity_stage="+filter_opportunity_stage+"&filter_opportunity_status="+filter_opportunity_status+"&filter_by_source="+filter_by_source+"&filter_is_hotstar="+filter_is_hotstar+"&filter_sort_by="+filter_sort_by+"&view_type="+view_type+"&filter_pending_followup="+filter_pending_followup+"&filter_pending_followup_for="+filter_pending_followup_for+"&filter_search_by_id="+filter_search_by_id+"&filter_like_dsc="+filter_like_dsc+"&filter_common_lead_pool="+filter_common_lead_pool+"&filter_followup="+filter_followup+"&filter_lead_observer="+filter_lead_observer;
		
        
        document.location.href = base_URL+'lead/download_csv/?'+data;
    });

    // ===========================================================
    // CREATE QUOTATION
    $('#bulk_qutation_send_to_buyer_modal').on('hidden.bs.modal', function () {
        window.location.reload();
    });
    $("body").on('click',"#create_quotation_bulk",function(e){        
        
        var ignore_lid=[];
        var email_error_count=0;
        var error_msg='';
        var i=1;
        var lid_str='';
        $('input[name=checked_to_customer]:checked').each(function () {
            
            var lid_tmp=$(this).attr('data-leadid');
            var email_tmp=$(this).attr('data-custemail');
            var name_tmp =$(this).attr('data-custname');
            lid_str +=$(this).attr('data-leadid')+',';

            if(email_tmp=='')
            {
              ignore_lid.push(lid_tmp);
              email_error_count++;              
              error_msg +=''+lid_tmp+',';
            }
            i++;
        });      
        lid_str = lid_str.replace(/(\s*,?\s*)*$/, "");
        $("#lid_bulk").val(lid_str);

        if(i>31)
        {
          swal('Oops!','Maximum Quotation send limit is 30.','error'); 
          return false;
        }

        if(email_error_count>0)
        { 
            error_msg = error_msg.replace(/(\s*,?\s*)*$/, "");
           swal({
            title: 'Oops',
            text: 'Following lead IDs do not have Buyer\'s Email ID:\n '+error_msg,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Ignore & Continue!'
            }, function() {
                var lid_str='';
                // alert(ignore_lid);
                $('input[name=checked_to_customer]:checked').each(function () {
                    var all_checked_lid=$(this).attr('data-leadid');
                    // alert(ignore_lid.indexOf(all_checked_lid))                    
                    if(ignore_lid.indexOf(all_checked_lid)==-1)
                    {
                      lid_str +=$(this).attr('data-leadid')+',';
                    }   
                    else
                    {
                      $(this).prop('checked', false);
                      // $('.cousto_check .check-box-sec').addClass('same-checked');
                      $('.lead_all_checkbox').prop('checked', false);
                    }                     
                }); 
                lid_str = lid_str.replace(/(\s*,?\s*)*$/, "");
                $("#lid_bulk").val(lid_str);

                $('#create_bulk_quotation_popup_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                }).css('overflow-y', 'auto'); 
            });
            return false;
        }  
        
        $('#create_bulk_quotation_popup_modal').modal({
            backdrop: 'static',
            keyboard: false
        }).css('overflow-y', 'auto');      

    });
    // $("body").on("click","#pdf_file_bulk",function(e){
    //   var email_error_count=0;
    //   var error_msg='';
    //   var i=1;
    //   var lid_str='';
    //   $('input[name=checked_to_customer]:checked').each(function () {
          
    //       var email_tmp=$(this).attr('data-custemail');
    //       var name_tmp =$(this).attr('data-custname');
    //       lid_str +=$(this).attr('data-leadid')+',';
    //       if(email_tmp=='')
    //       {
    //         email_error_count++;              
    //         error_msg +=i+') '+name_tmp+'\n';
    //       }
    //       i++;
    //   });      
    //   lid_str = lid_str.replace(/(\s*,?\s*)*$/, "");
    //   $("#lid_bulk").val(lid_str);

    //   if(i>11)
    //   {
    //     swal('Oops!','Maximum Quotation send limit is 10.','error'); 
    //     return false;
    //   }
    //   if(email_error_count>0)
    //   {          
    //     swal('Oops! Quotation can\'t be send as buyer\'s e-mail not available',error_msg,'error'); 
    //     return false;

    //   }

    // });

    $("body").on("click","#bulk_send_to_buyer_confirm",function(e){
        e.preventDefault();
        var base_url=$("#base_url").val();
        $.ajax({
                url: base_url+"opportunity/bulk_qutation_send_to_buyer_by_mail_confirm_ajax",
                //data: data,
                data: new FormData($('#bulk_send_to_buyer_frm')[0]),
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
                  showTextLoader('#bulk_qutation_send_to_buyer_modal .modal-content', 'This action may take sometime so please do not close or refresh this browser window.')
                  // $("#send_to_buyer_confirm").attr("disabled", true);
                  // $('#bulk_qutation_send_to_buyer_modal .modal-body').addClass('logo-loader');
                },
                complete: function (){
                  hideTextLoader();
                  // $('#bulk_qutation_send_to_buyer_modal .modal-body').removeClass('logo-loader');
                },
                success: function(data){       
                   
                    result = $.parseJSON(data);                    
                    // $("#send_to_buyer_confirm").attr("disabled", false);
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
                          
                          swal({
                            title: 'Success',
                            text: result.msg,
                            type: 'success',
                            showCancelButton: false
                        }, function() {
                            window.location.reload();
                        });

                    }
                    else
                    {
                        swal({
                              title: result.msg,
                              text: '',
                              type: 'warning',
                              showCancelButton: false
                          }); 
                            
                    }
                }
            });  
      });

    $("body").on("click","#bulk_quotation_email_list_popup",function(e){
      var get_email_list=$(this).attr("data-toemails");
      // swal("To",get_email_list);
      swal({
          title: "To",
          text: get_email_list,
          customClass: 'sweetalert-lg',
        });
    });
    // CREATE QUOTATION
    // ===========================================================
    
    // ======================================
    // Product Quote
    $("body").on("click",".append_product_quote_mail",function(e){
        // add_product_quote_view('mail');
        $("#search_product_lead_id").val($("#lead_id").val());
        $("#is_mail_or_whatsapp").val('mail');
        $("#search_add_btn_class").val('add_searched_product_confirm_mail');
        $('#ReplyPopupModal').css("display","none");
        // if($('#ReplyPopupModal').hasClass('in'))
        // {
        //     $('#ReplyPopupModal').modal('hide');
        // }
        // else
        // {
            
        // }
        // $("#ReplyPopupModal").modal('hide');
        setTimeout(function(){ 
          search_product_view();
        }, 600);   
    });

    $("body").on("change","#search_p_group",function(e){
        var parent_id=$(this).val();
        rander_option_category(parent_id,'');    
    });


    $("body").on("click","#search_product_checked_proceed_confirm",function(e){
      e.preventDefault();
      var base_url = $("#base_url").val();
      // var product_ids = $.map($('input[name="select_product[]"]:checked'), function(c) {
      //       return c.value;
      // });
      var product_ids=$("#selected_p_ids").val();
      var lead_id=$("#search_product_lead_id").val();
      
      // alert(product_ids+'/'+lead_id+'/'+product_ids.length); return false;
      if (product_ids)
      {
          var base_url = $("#base_url").val(); 
          var is_mail_or_whatsapp =$("#is_mail_or_whatsapp").val();  
          $.ajax({ 
              url: base_url + "lead/add_product_quote_ajax",
              type: "POST",
              data: {"product_ids":product_ids,"lead_id":lead_id,"is_mail_or_whatsapp":is_mail_or_whatsapp},
              async: true,
              beforeSend: function(xhr) {
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
              success: function(data) {
                  result = $.parseJSON(data);
                  $("#rander_search_product_view_modal").modal('hide');
                  var is_mail_or_whatsapp=$("#is_mail_or_whatsapp").val();
                  
                  if(is_mail_or_whatsapp=='mail')
                  { 
                    $(".buying-requirements").html(result.quote_text);
                    $('#ReplyPopupModal').css("display","block");
                    // $("#ReplyPopupModal").modal('show');
                    // setTimeout(function(){ 
                    //   $("#ReplyPopupModal").modal('show');
                    // }, 600);                    
                  }
                  else if(is_mail_or_whatsapp=='whatsapp')
                  {
                      $("#whatsapp_txt").html(result.quote_text);
                      $('#WebWhatsappModal').css("display","block");
                  }
                  $("#search_add_btn_class").val('');
                  // $(append_to).html(result.quote_text);
                  // $('#rander_add_product_quote_view_modal').modal('hide');
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
      // var product_ids=$("#product_quote").val();
      // if(product_ids=='' || product_ids==null)
      // {
      //   $("#product_quote_error").html('Please select Product / Service');
      //   return false;
      // }
      // get_product_quote_text(product_ids,lead_id,'.buying-requirements');
    });

    $("body").on("click",".append_product_quote_whatsapp",function(e){
        // add_product_quote_view('whatsapp');
        $("#search_product_lead_id").val($("#lead_id").val());
        $("#is_mail_or_whatsapp").val('whatsapp');
        $("#search_add_btn_class").val('add_searched_product_confirm_whatsapp');
        $('#WebWhatsappModal').css("display","none");
        setTimeout(function(){ 
          search_product_view();
        }, 600);
    });
    $("body").on("click","#add_product_quote_for_whatsapp_submit_confirm",function(e){
      e.preventDefault();
      var base_url = $("#base_url").val();
      var product_ids=$("#product_quote").val(); 
      var lead_id=$("#lead_id").val();
      if(product_ids=='' || product_ids==null)
      {
        $("#product_quote_error").html('Please select Product / Service');
        return false;
      }
      get_product_quote_text(product_ids,lead_id,'#whatsapp_txt');
    });

    $('#rander_search_product_view_modal').on('hidden.bs.modal', function () {
        var is_mail_or_whatsapp=$("#is_mail_or_whatsapp").val();     
    
        if(is_mail_or_whatsapp=='mail')
        { 
          $('#ReplyPopupModal').css("display","block");
        }
        else if(is_mail_or_whatsapp=='whatsapp')
        {
          $('#WebWhatsappModal').css("display","block");
        }
    });

    // end
    // =======================================

    $("body").on("click","input:checkbox[name=common_lead_pool]",function(e){
        var checked = $(this).is(':checked');
        if(checked){ 
			$("#filter_common_lead_pool").val('Y'); 
			$("input:radio[name=filter_followup]:checked").each(function(){
				$(this).attr("checked",false);
			});	
			$("#filter_followup").val('');
			
			
			$("#lead_observer").attr("checked",false);
			$("#filter_lead_observer").val('');
        }else{         
          $("#filter_common_lead_pool").val('N');
		  document.getElementById("filter_followup_today").checked = true;
		  $("#filter_followup").val('TL');
        }
        load();
    });
	
	$("body").on("click","input:checkbox[name=lead_observer]",function(e){
        var checked = $(this).is(':checked');
        if(checked){ 
			$("#filter_lead_observer").val('Y'); 
			
			$("input:radio[name=filter_followup]:checked").each(function(){
				$(this).attr("checked",false);
			});	
			$("#filter_followup").val('');			
			
			$("#common_lead_pool").attr("checked",false);
			$("#filter_common_lead_pool").val('');
        }else{         
          $("#filter_lead_observer").val('');
		  document.getElementById("filter_followup_today").checked = true;
		  $("#filter_followup").val('TL');
        }
        load();
    });
	

    $("body").on("click",".observer_remove",function(e){
      var lid=$(this).attr("data-leadid");
      var base_url = $("#base_url").val();
      var data='lid='+lid;
      // alert(data); return false;
      $.ajax({
            url: base_url + "lead/observer_remove_ajax",
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
            success: function(data){
              result=$.parseJSON(data); 
              if(result.status=='success'){
                load();
              }              
            }
        });
    });

	$("body").on("change","input:radio[name=filter_followup]:checked",function(e){
		var val=$(this).val(); 
		
		$("#filter_followup").val(val);
		$("#filter_pending_followup").val('');
		$("#filter_pending_followup_for").val('');
		$("#filter_search_str").val('');
		$("#search_keyword").val('');
		$("#search_by_id").val('');
		
		$("input:checkbox[name=common_lead_pool]").attr("checked",false);
		$("#filter_common_lead_pool").val('');
		$("#lead_observer").attr("checked",false);
		$("#filter_lead_observer").val('');
    $("#filter_like_dsc").val('');			
		load();
        		
	});
	$("body").on("click","#uncheck_followup_filter",function(e){	
		var flag=0;
		$("input:radio[name=filter_followup]:checked").each(function(){
			$(this).attr("checked",false); 
			flag++;
		});	
		$("#filter_followup").val('');
		if(flag>0)
		{
			load();
			
		}
		
	});


  // ===========================================================
  // EDIT TITLE AND DESCRIPTION POPUP
  $("body").on("click",".lead_title_desc_edit_view",function(e){
		var lead_id=$(this).attr("data-id");
		var base_url=$("#base_url").val();
		var data="lead_id="+lead_id;
		$.ajax({ 
				url: base_url+"lead/lead_title_desc_edit_view_rander_ajax",
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
			success: function (response) 
			{ 
        $("#common_view_modal_title").text("Edit Lead Title & Description");
			  $('#rander_common_view_modal_html').html(response);
			  $('#rander_common_view_modal').modal({backdrop: 'static',keyboard: false});
			},
			error: function () 
			{
			  //alert('Something went wrong there');
			  swal({
				title: "Danger!",
				text: "Something went wrong there",
				type: "danger",
				confirmButtonText: "ok",
				allowOutsideClick: "false"
			});
			}
      });
  });

  $("body").on("click","#lead_title_desc_edit_submit_confirm",function(e){

      e.preventDefault();
      var ThisObj=$(this);
      var base_url = $("#base_url").val();
      var lead_title_obj=$("#lead_title");      
      var lead_requirement = tinyMCE.activeEditor.getContent();  

      if(lead_title_obj.val()=='')
      {
        $("#lead_title_error").html('Please enter lead title');
        return false;
      }
      else
      {
        $("#lead_title_error").html('');
      }

      $('#lead_requirement').val(lead_requirement);     
      if(lead_requirement=='')
      {
        $("#lead_requirement_error").html('Please describe requirements');
        return false;
      }
      else
      {
        $("#lead_requirement_error").html('');
      }

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
                ThisObj.addClass("btn_disabled");
                $(".btn_disabled").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                ThisObj.attr("disabled",true);
                $('#rander_common_view_modal .modal-body').addClass('logo-loader');
              },
              complete: function (){
                $('#rander_common_view_modal .modal-body').removeClass('logo-loader');
              },
              success: function(data){
                result = $.parseJSON(data);
                ThisObj.removeClass("btn_disabled");
                $(".btn_enabled").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                ThisObj.attr("disabled",false);
                if(result.status=='success')
                {                    
                  $('#rander_common_view_modal').modal('hide');
                  load(); 
                }   
              }
            }); 

  });
  // EDIT TITLE AND DESCRIPTION POPUP
  // ===========================================================
});
function custom_quation_pdf_upload_bulk()
{
    var base_url = $("#base_url").val();  
    var lead_ids=$("#lid_bulk").val();
    var form_data = new FormData();   
    $('#form_upload_pdf_bulk').append('<input type="hidden" id="lead_ids" name="lead_ids" value="'+lead_ids+'" />');
    var extension=$('#pdf_file_bulk').val().replace(/^.*\./, '');
    if(extension=='pdf')
    {      
        swal({
        title: 'Do you want to upload this file?',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, upload it!'
        }, function() {
            
            $.ajax({
                url: base_url+"opportunity/pdf_upload_for_custom_quotation_bulk_ajax/",
                data: new FormData($('#form_upload_pdf_bulk')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function( xhr ) {
                  // $('#create_bulk_quotation_popup_modal .modal-body').addClass('logo-loader');
                  showTextLoader('#create_bulk_quotation_popup_modal .modal-content', 'This action may take sometime so please do not close or refresh this browser window.');
                },
                complete: function (){
                  hideTextLoader();
                 // $('#create_bulk_quotation_popup_modal .modal-body').removeClass('logo-loader');
                },
                success: function(data)
                {                
                    result = $.parseJSON(data);                     
                    if(result.status=='success')
                    { 
                        $('#create_bulk_quotation_popup_modal').modal('toggle');

                        // swal('PDF Uploaded Successfully');
                        bulk_qutation_send_to_buyer_modal(result.opportunity_id_str,result.quotation_id_str);                    
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
        swal('Please select a PDF File'); // display response from the PHP script, if any
        return false;
    }
}

function bulk_qutation_send_to_buyer_modal(opp_id_str,quotation_id_str)
{
    var base_url=$("#base_url").val();  
    //var opp_id=$(this).attr("data-opportunityid");
    //var quotation_id=$(this).attr("data-quotationid");
    var data="quotation_id_str="+quotation_id_str+"&opp_id_str="+opp_id_str;
    // alert(data); return false;
    $.ajax({
            url: base_url+"opportunity/bulk_qutation_send_to_buyer_by_mail_ajax/",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {
              
                
            },
            complete: function (){
                    $.unblockUI();
            },
            success:function(res){ 
               result = $.parseJSON(res);
               $("#bulk_qutation_send_to_buyer_body").html(result.html);
               $('#bulk_qutation_send_to_buyer_modal').modal({backdrop: 'static',keyboard: false}).css('overflow-y', 'auto');
               
            },
            error: function(response) {
            }
        }); 
}

function rander_lead_wise_tagged_ps_view(lead_id)
{   
      var base_url = $("#base_url").val();
      $.ajax({
            url: base_url + "lead/view_add_tagged_ps_ajax",
            type: "POST",
            data: {
                'lead_id':lead_id
            },
            async: true,
            beforeSend: function(xhr) {
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
            //console.log('product_tags.....')
              $('#rander_add_tagged_ps_view_html').html(response);
              $('#rander_add_tagged_ps_view_modal').modal({backdrop: 'static',keyboard: false});
              // $('.sp-custom-select').select2({
              //   tags: false,
              // });
              $('.sp-custom-select').select2({
                  tags: false,
                  ajax: {
                  url: base_url+"lead/get_product_select2_autocomplete",
                  dataType: 'json',
                  delay: 250,
                  data: function (data) {
                    return {
                      searchTerm: data.term // search term
                    };
                  },
                  processResults: function (response) {
                    return {
                      results:response
                    };
                  },
                  cache: true
                }
              });
              $("body").on("change",".sp-custom-select",function(e){
                var product_tags=$(this).val();
                var product_tags_str = product_tags.join(", ");   
                // var new_text='Requirement for '+product_tags_str; 
                var new_text=''+product_tags_str;   
                //console.log('product_tags: '+product_tags)
                $("#lead_title").val(new_text);
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
}
/*
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

function fn_open_cust_reply_box_view(lead_id)
{   
   // alert('To:'+to_mail+'  / From:'+from_mail);
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "lead/rander_cust_reply_box_view_popup_ajax",
       type: "POST",
       data: {
           'lead_id': lead_id
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
           //////
           $('.btn-side .item').each(function( index ) {
                var gItemw = $(this).find('.auto-txt-item').outerWidth();
                console.log( index + ": " + gItemw );
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
       },
       error: function() {
           
       }
   });
}
*/
function fn_rander_source_add_view()
{
    var base_url=$("#base_url").val(); 
    $.ajax({
        url: base_url+"lead/add_source_ajax",
        data: new FormData($('#AddSourceForm')[0]),
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
          // alert(result.msg);       
          if(result.status=='success')
          {
            
            swal({
                title: "Success",
                text: result.msg,
                type: "warning",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
            $("#com_source_id").html(result.options);
            $("#source").val('');
            $("#source").focus();
              
          }   
          else if(result.status=='error')
          {
            //alert(result.msg);            
            $("#source").focus();
            swal(result.msg);
          }
        }
    });
}

function lead_filter_reset()
{
    //location.reload(true);
    $("#selected_filter_div").css({'display':'none'}).html('');
    // ------------------------------------------------------
    // FILTER RE-SET
    $("#datepicker3").val('');
    $("#datepicker4").val('');
    $("#date_filter_by").val($("#date_filter_by option:first").val());
    
    //$("#assigned_user").val($("#assigned_user option:first").val());
	$('#assigned_user option:selected').each(function() {           
		$(this).attr("selected",false);     
		$('#assigned_user').multiselect('deselect', $(this).val());             
	});
    
    $("input:checkbox[name=lead_applicable_for]").attr("checked",false);
    
    document.getElementById("lead_type_all").checked = true;
    $("input:checkbox[name=opportunity_stage]").attr("checked",false);
    $("input:checkbox[name=opportunity_status]").attr("checked",false);
    $("input:checkbox[name=by_source]").attr("checked",false);
    $("input:checkbox[name=is_hotstar]").attr("checked",false);
    
    
        // SET VAL
    $("#filter_lead_from_date").val('');
    $("#filter_lead_to_date").val('');
    $("#filter_date_filter_by").val('');
    
    $("#filter_assigned_user").val('');
    
    $("#filter_lead_applicable_for").val('');
    $("#filter_lead_type").val('ALL');
    $("#filter_opportunity_stage").val('');
    $("#filter_opportunity_status").val('');
    $("#filter_by_source").val('');
    $("#filter_is_hotstar").val('');
  

    // FILTER RE-SET
    // ------------------------------------------------------ 
    $("#selected_filter_div").html('');
    // $("#leadFilterModal").modal('hide');
}
function fb_ig_csv_upload_and_import(opp_id)
{    
    var base_url = $("#base_url").val();
    //var file_data = $('#pdf_file').prop('files')[0];   
    //var form_data = new FormData();                  
    //form_data.append('pdf_file', file_data);
    //form_data.append('opp_id', opp_id);
    //$('#form_upload_fb_ig_csv').append('<input type="hidden" id="opp_id" name="opp_id" value="'+opp_id+'" />');
    var extension=$('#fb_ig_csv_file').val().replace(/^.*\./, '');    
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
                url: base_url+"lead/fb_ig_csv_upload_and_import_ajax/",
                data: new FormData($('#form_upload_fb_ig_csv')[0]),
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
                      $("#fb_ig_error_log_div").show();
                      $("#uploaded_csv_file_name").val(result.file_name);
                      swal({
                        title: '',
                        text: result.error_msg,
                        type: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Ok'
                        }, function() {
                            $(".get_fb_ig_error_log").first().trigger('click');
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

function csv_upload_and_import(opp_id)
{    
    var base_url = $("#base_url").val();
    //var file_data = $('#pdf_file').prop('files')[0];   
    //var form_data = new FormData();                  
    //form_data.append('pdf_file', file_data);
    //form_data.append('opp_id', opp_id);
    //$('#form_upload_fb_ig_csv').append('<input type="hidden" id="opp_id" name="opp_id" value="'+opp_id+'" />');
    
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
                url: base_url+"lead/csv_upload_and_import_ajax/",
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

function addLoader(getele)
{	
	var gets = 100;
	if ($(window).scrollTop() > 200) {
		gets = $(window).scrollTop();
	}
	var loaderhtml = '<div class="loader" style="background-position: 50% '+gets+'px"></div>';
	$(getele).css({'position':'relative', 'overflow':'hidden', 'min-height': '300px'}).prepend(loaderhtml);
	$('.loader').fadeIn('fast', function() {
		// Animation complete.
		//$(getele).css({'min-height': 'inherit'})
	});
}
function removeLoader()
{
	$('.loader').fadeOut('fast', function() {
		// Animation complete.
		$('.loader').remove()
	});
}
function rander_add_new_lead_view(call_sync_id='',is_search_box_show='Y',mobile='',email='',cid='',is_customer_basic_data_show='Y')
{      
    var base_url = $("#base_url").val();
    $.ajax({
            url: base_url + "lead/add_ajax",
            type: "POST",
            data: {
                'is_search_box_show':is_search_box_show,
                'is_customer_basic_data_show':is_customer_basic_data_show,
                'cid':cid,
                'mobile': mobile,
                'email': email,
                'call_sync_id':call_sync_id
            },
            async: true,
            beforeSend: function(xhr) {
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
              $('#rander_add_new_lead_view_html').html(response);
              $('#rander_add_new_lead_view_modal').modal({backdrop: 'static',keyboard: false});
              
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
// AJAX LOAD START
function load(is_page_load_show='Y') 
{ 
  
    //return;
    //var page_num=page;
    var base_URL     = $("#base_url").val();    
    var filter_search_str=$("#filter_search_str").val();
    var filter_like_dsc=$("#filter_like_dsc").val(); 
    var filter_search_by_id=$("#search_by_id").val();
    var filter_by_keyword=$("#filter_by_keyword").val();
	
    var filter_lead_from_date=$("#filter_lead_from_date").val();
    var filter_lead_to_date=$("#filter_lead_to_date").val();
    var filter_date_filter_by=$("#filter_date_filter_by").val();
    var filter_assigned_user=$("#filter_assigned_user").val();
    var filter_lead_applicable_for=$("#filter_lead_applicable_for").val();
    var filter_lead_type=$("#filter_lead_type").val();
    var filter_opportunity_stage=$("#filter_opportunity_stage").val();
    var filter_opportunity_status=$("#filter_opportunity_status").val();
    var filter_by_source=$("#filter_by_source").val();
    var filter_is_hotstar=$("#filter_is_hotstar").val();
    var filter_pending_followup=$("#filter_pending_followup").val();
    var filter_pending_followup_for=$("#filter_pending_followup_for").val();
	var filter_followup=(filter_pending_followup=='Y' || filter_search_str!='' || filter_search_by_id!='')?'':$("#filter_followup").val();
	var filter_common_lead_pool=(filter_followup!='')?'N':$("#filter_common_lead_pool").val(); 
	var filter_lead_observer=$("#filter_lead_observer").val();
    var filter_sort_by=$("#filter_sort_by").val();
    var view_type=$("#view_type").val();
    var page=$("#page_number").val(); 
    var is_scroll_to_top=$("#is_scroll_to_top").val(); 
     
	 
    if((filter_search_str!='' || filter_search_by_id!='') && filter_by_keyword==''){ 
      lead_filter_reset();     
    }
	
	
    if((filter_common_lead_pool=='Y' || filter_followup!='') && filter_like_dsc==''){
      lead_filter_reset();
    }
	if(filter_followup!=''){
		$("input:checkbox[name=common_lead_pool]").attr("checked",false);
	}
    if(filter_pending_followup=='Y' || filter_search_str!='' || filter_search_by_id!='' ){
		
		$("input:radio[name=filter_followup]:checked").each(function(){
			$(this).attr("checked",false);
		});	
	}
	
    var data = "page="+page+"&filter_search_str="+filter_search_str+"&filter_lead_from_date="+filter_lead_from_date+"&filter_lead_to_date="+filter_lead_to_date+"&filter_date_filter_by="+filter_date_filter_by+"&filter_assigned_user="+filter_assigned_user+"&filter_lead_applicable_for="+filter_lead_applicable_for+"&filter_lead_type="+filter_lead_type+"&filter_opportunity_stage="+filter_opportunity_stage+"&filter_opportunity_status="+filter_opportunity_status+"&filter_by_source="+filter_by_source+"&filter_is_hotstar="+filter_is_hotstar+"&filter_sort_by="+filter_sort_by+"&view_type="+view_type+"&filter_pending_followup="+filter_pending_followup+"&filter_pending_followup_for="+filter_pending_followup_for+"&filter_search_by_id="+filter_search_by_id+"&filter_like_dsc="+filter_like_dsc+"&filter_common_lead_pool="+filter_common_lead_pool+"&filter_followup="+filter_followup+"&filter_lead_observer="+filter_lead_observer;
    // alert(data);// return false;
    $.ajax({
        url: base_URL+"lead/get_list_ajax/"+page,
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) { 
          if(is_page_load_show!='N'){
            addLoader('#lead_table');
          }               
          
        },
        complete: function(){
          if(is_scroll_to_top=='Y'){
          $("body, html").animate({ scrollTop: 250 }, "slow");
          }
          $("#is_scroll_to_top").val('N');
          if(is_page_load_show!='N'){
            removeLoader();
          }
        },
          success:function(res){ 
           result = $.parseJSON(res);
           //$(".preloader").hide();
           // alert(result.table);
           //alert(3);        
           //alert(result.page_record_count_info)
           $("#tcontent").html(result.table);
           $("#page").html(result.page);
           $("#page_record_count_info").html(result.page_record_count_info);
           // alert(view_type)
           if(view_type == 'grid'){
            updateGrid();
            $('#lead_table').addClass('datatable_grid');
            //trimText($(".lead-details"),   100);
            $(".grey-card-block").removeClass('list_view');
            $(".grey-card-block").addClass('grid_view');
           }else{
            updateLeadView();
            $('#lead_table').removeClass('datatable_grid');
            $(".grey-card-block").addClass('list_view');
            $(".grey-card-block").removeClass('grid_view');
           }
           
          // --------------------------
          // NEXT FOLLOW UP DATE FORM
          /*
          $('.open-calendar').popover({       
                placement: 'top',
                title: 'Next Follow-up',
                html:true,
                content:  $('#NextFollowupDateUpdateForm').html(),
                showCallback: function () {                      
                  //$('#datetimepicker1').datepicker();
                }
          }).on('click', function(){
              var lid=$(this).attr("data-id");
              $("#nfd_lead_id").val(lid);     
              // $('.datetimepicker_nfd').datepicker('update');           
          });

          $('.open-calendar').on('shown.bs.popover', function () { 
              
              $('.datetimepicker_nfd').datepicker({
                                          dateFormat: "dd-M-yy",
                                          changeMonth: true,
                                          changeYear: true,
                                          yearRange: '-100:+5',
                                          minDate:0,
                                          onSelect : function (ev) {
                                              // here your code
                                              // alert(ev)
                                              $("#nfd_date").val(ev);
                                          }
                                      });
          });             
          */         

		  $( ".nfd_input_date" ).datetimepicker({			  
			  format:'d-M-Y H:i A',
			  step: 15, 
			  theme:'default',
			  inline:false,
			  lang:'en',
			  minDate: '0',
			  closeOnDateTimeSelect:true,
			  onSelectTime : function (current_time,$input) {
            //console.log($input.attr('id')+'/'+$input.val())
            //alert($input.attr('id')+'/'+$input.val())
					  update_next_followup($input.attr('id'),$input.val());
          },
		  });
		      /*
		      var assets_base_url = $("#assets_base_url").val();
          $( ".nfd_input_date" ).datepicker({
                showOn: "both",
                dateFormat: "dd-M-yy",
                buttonImage: assets_base_url+"images/cal-icon.png",
                // changeMonth: true,
                // changeYear: true,
                // yearRange: '-100:+0',
                buttonImageOnly: true,
                buttonText: "Select date",
                minDate: 0,
                onSelect : function (dateText, inst) {
                      // here your code
                      update_next_followup(inst.id,dateText);
					  //console.log(inst.id+'/'+dateText)
                }
          });      
		      */
          // NEXT FOLLOW UP DATE FORM
          // --------------------------
          
          
                    
       },       
       error: function(response) {
        //alert('Error'+response.table);
        }
   })
}
// AJAX LOAD END
function update_next_followup(nfd_lead_id,nfd_date)
{
    var base_url = $("#base_url").val();
    var data = "nfd_lead_id="+nfd_lead_id+"&nfd_date="+nfd_date;
    // alert(data); return false;
    $.ajax({
            url: base_url + "lead/update_next_followup_date_ajax",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function(xhr) {
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
            success: function(res) {
                result = $.parseJSON(res);
                if (result.status == 'success') 
                {                    
                  //$("#ndf_"+result.lid).html('');
				  load();
                }
                else{}
            },
            complete: function() {
               $.unblockUI();
            },
            error: function(response) {}
    });
}
function truncate_temp_selected_product(prod_id='') 
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