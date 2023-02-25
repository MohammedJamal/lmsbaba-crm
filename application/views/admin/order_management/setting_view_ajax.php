<div class="tab_gorup side-by-side custom-style-tab">
    <div class="tab tab-group-sec"> 
        <button class="Stablinks" onClick="SopenDiv(event, 'Stab_1')" id="SdefaultOpen" type="button">      
            Manage Order Stage
        </button>
        <button class="Stablinks" onClick="SopenDiv(event, 'Stab_2')" type="button">    
            Assigne Stages to the Users
        </button>
        <button class="Stablinks" onClick="SopenDiv(event, 'Stab_3')" type="button">    
           Create Order Management Forms
        </button>
        <button class="Stablinks" onClick="SopenDiv(event, 'Stab_4')" type="button">    
            Tag Order Forms with Stages
        </button>
        <button class="Stablinks" onClick="SopenDiv(event, 'Stab_5')" type="button">    
            User Wise Link Permissions
        </button>
    </div>

    <div class="tab-section">
        <!-- TAB 1 - START -->
        <div id="Stab_1" class="StabcontentDiv">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <h3 class="text-info"><i class="fa fa-sliders fa-fw" aria-hidden="true"></i> Manage Stage</h3> 
                    </div>
                </div>
                <div id="om_stage_tcontent" class="form-group row"></div>
            </div>            
        </div>
        <!-- TAB 1 - END -->

        <!-- TAB 2 - START -->
        <div id="Stab_2" class="StabcontentDiv">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <h3 class="text-info"><i class="fa fa-sliders fa-fw" aria-hidden="true"></i> Manage Stage Wise User Assign</h3> 
                    </div>
                </div>
                <div id="om_stage_wise_assign_user_tcontent" class="form-group row"></div>
            </div>            
        </div>
        <!-- TAB 2 - END -->
        <!-- TAB 3 - START -->
        <div id="Stab_3" class="StabcontentDiv">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <h3 class="text-info"><i class="fa fa-sliders fa-fw" aria-hidden="true"></i> Manage Stage Form</h3> 
                    </div>
                </div>
                <div id="om_stage_form_tcontent" class="form-group row"></div>
            </div>            
        </div>
        <!-- TAB 3 - END -->     
        <!-- TAB 4 - START -->
        <div id="Stab_4" class="StabcontentDiv">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <h3 class="text-info"><i class="fa fa-sliders fa-fw" aria-hidden="true"></i> Manage Stage Wise Form Assign</h3> 
                    </div>
                </div>
                <div id="om_stage_wise_assign_form_tcontent" class="form-group row"></div>
            </div>            
        </div>
        <!-- TAB 4 - END -->
        <!-- TAB 5 - START -->
        <div id="Stab_5" class="StabcontentDiv">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <h3 class="text-info"><i class="fa fa-sliders fa-fw" aria-hidden="true"></i> Manage Permissions</h3> 
                    </div>
                </div>
                <div id="om_user_wise_permission_tcontent" class="form-group row"></div>
            </div>            
        </div>
        <!-- TAB 5 - END -->
    </div> 
</div>
<script>
$(document).ready(function(){
    
    document.getElementById("SdefaultOpen").click(); 

    // $("body").on("click","#om_stage_add_submit",function(e){
    //     var base_url=$("#base_url").val();
    //     var om_stage_name=$("#om_stage_name").val();
    //     var om_stage_position=$("#om_stage_position").find("option:selected").val();
    //     var om_stage_id_as_per_position=$("#om_stage_id").find("option:selected").val();
    //     var data='om_stage_name='+om_stage_name+'&om_stage_position='+om_stage_position+'&om_stage_id_as_per_position='+om_stage_id_as_per_position;
        

    //     if (om_stage_name=='') 
    //     {
    //         swal("Oops!", "Name should not be null",'error');        
    //         return false;
    //     } 

    //     if (om_stage_position=='') 
    //     {
    //         swal("Oops!", "Stage add position should not be null",'error');        
    //         return false;
    //     } 

    //     if (om_stage_id_as_per_position=='') 
    //     {
    //         swal("Oops!", "Stage as per position should not be null",'error');        
    //         return false;
    //     }  
        
    //     $.ajax({
    //             url: base_url+"order_management/add_om_stage_setting",
    //             data: data,                    
    //             cache: false,
    //             method: 'GET',
    //             dataType: "html",                   
    //             beforeSend: function( xhr ) { 
    //                 $.blockUI({ 
    //                     message: 'Please wait...', 
    //                     css: { 
    //                         padding: '10px', 
    //                         backgroundColor: '#fff', 
    //                         border:'0px solid #000',
    //                         '-webkit-border-radius': '10px', 
    //                         '-moz-border-radius': '10px', 
    //                         opacity: .5, 
    //                         color: '#000',
    //                         width:'450px',
    //                         'font-size':'14px'
    //                     }
    //                 });
    //             },
    //             complete: function(){
    //                 $.unblockUI();
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);                        
    //                 if(result.status=='success')
    //                 {
    //                     load_om_stage_view();
    //                 }
    //             },                    
    //     });
    // });


    // $("body").on("click",".tag_assign_user_popup",function(e){
    //     var base_url=$("#base_url").val(); 
    //     var ThisObj=$(this);
    //     var stage_id=ThisObj.attr("data-stage_id");       
    //     //   alert(stage_id); return false;
    //     $.ajax({
    //       url: base_url + "order_management/tag_assign_user_to_stage_view_rander_ajax",
    //       type: "POST",
    //       data: {
    //           'stage_id': stage_id
    //       },
    //       async: true,
    //       success: function(response) {
    //           $("#common_view_modal_title_md").text("Assign User");
    //           $('#rander_common_view_modal_html_md').html(response);
    //           $('#rander_common_view_modal_md').modal({
    //               backdrop: 'static',
    //               keyboard: false
    //           });
              
    //       },
    //       error: function() {
    //           swal({
    //                   title: 'Something went wrong there!',
    //                   text: '',
    //                   type: 'danger',
    //                   showCancelButton: false
    //               }, function() {

    //           });
    //       }
    //     });

    // });

    // $("body").on("click","#tag_assign_user_popup_confirm",function(e){
    //     var base_url = $("#base_url").val();        
    //     var stage_user_id=$("#om_stage_user_id").val();
    //     var stage_id=$(this).attr("data-stage_id");        
    //     var data = 'stage_id='+stage_id+"&stage_user_id="+stage_user_id;  
        
    //     if(!stage_user_id){
    //         swal("Oops!", "Please select user.",'error');          
    //         return false;
    //     }
    //     // alert(data); return false;
    //     $.ajax({
    //         url: base_url+"order_management/tag_assign_user_to_stage_update",
    //         data: data,                    
    //         cache: false,
    //         method: 'GET',
    //         dataType: "html",                   
    //         beforeSend: function( xhr ) { 
    //           //$("#preloader").css('display','block');                           
    //         },
    //         success: function(data){
    //             result = $.parseJSON(data);  
    //             // console.log(result)   
    //             if(result.status=='success')
    //             {
    //                 $("#rander_common_view_modal_md").modal('hide');
    //                 load_om_stage_wise_user_assign_view(); 
                    
    //             }
                
    //         },
    //         complete: function(){
    //         //$("#preloader").css('display','none');
    //         },
    //     });

    // });

    // $("body").on("click",".untag_assign_user_popup",function(e){
    //     var base_url=$("#base_url").val(); 
    //     var ThisObj=$(this);
    //     var stage_id=ThisObj.attr("data-stage_id");       
    //     //   alert(stage_id); return false;
    //     $.ajax({
    //       url: base_url + "order_management/untag_assign_user_to_stage_view_rander_ajax",
    //       type: "POST",
    //       data: {
    //           'stage_id': stage_id
    //       },
    //       async: true,
    //       success: function(response) {
    //           $("#common_view_modal_title_md").text("Assign User");
    //           $('#rander_common_view_modal_html_md').html(response);
    //           $('#rander_common_view_modal_md').modal({
    //               backdrop: 'static',
    //               keyboard: false
    //           });
              
    //       },
    //       error: function() {
    //           swal({
    //                   title: 'Something went wrong there!',
    //                   text: '',
    //                   type: 'danger',
    //                   showCancelButton: false
    //               }, function() {

    //           });
    //       }
    //     });

    // });

    // $("body").on("click",".untag_assign_user_popup_confirm",function(e){
        
    //     var base_url = $("#base_url").val();        
    //     var user_id=$(this).attr("data-user_id");
    //     var stage_id=$(this).attr("data-stage_id");        
    //     var data = 'stage_id='+stage_id+"&user_id="+user_id;  
        
    //     $.ajax({
    //         url: base_url+"order_management/untag_assign_user_to_stage_update",
    //         data: data,                    
    //         cache: false,
    //         method: 'GET',
    //         dataType: "html",                   
    //         beforeSend: function( xhr ) { 
    //           //$("#preloader").css('display','block');                           
    //         },
    //         success: function(data){
    //             result = $.parseJSON(data);  
    //             // console.log(result)   
    //             if(result.status=='success')
    //             {
    //                 // $("#rander_common_view_modal_md").modal('hide');
    //                 load_om_stage_wise_user_assign_view();                    
    //                 $("#div_"+user_id).remove();
    //                 if($("#un_tbody").find('tr').length==0){
    //                     $("#un_tbody").html('<tr><td colspan="2" class="align-middle" class="text-center">No Record Found!</td></tr>');
    //                 }
                    
    //             }
                
    //         },
    //         complete: function(){
    //         //$("#preloader").css('display','none');
    //         },
    //     });

    // });

    // ======================================================================
    // $("body").on("click","#om_stage_form_add_submit",function(e){
    //     var base_url=$("#base_url").val();
    //     var om_form_name=$("#om_form_name").val();        
    //     var data='om_form_name='+om_form_name;       

    //     if (om_form_name=='') 
    //     {
    //         swal("Oops!", "Name should not be null",'error');        
    //         return false;
    //     } 

        
    //     $.ajax({
    //             url: base_url+"order_management/add_om_stage_form_setting",
    //             data: data,                    
    //             cache: false,
    //             method: 'GET',
    //             dataType: "html",                   
    //             beforeSend: function( xhr ) { 
    //                 $.blockUI({ 
    //                     message: 'Please wait...', 
    //                     css: { 
    //                         padding: '10px', 
    //                         backgroundColor: '#fff', 
    //                         border:'0px solid #000',
    //                         '-webkit-border-radius': '10px', 
    //                         '-moz-border-radius': '10px', 
    //                         opacity: .5, 
    //                         color: '#000',
    //                         width:'450px',
    //                         'font-size':'14px'
    //                     }
    //                 });
    //             },
    //             complete: function(){
    //                 $.unblockUI();
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);                        
    //                 if(result.status=='success')
    //                 {
    //                     load_om_stage_form_view();
    //                 }
    //             },                    
    //     });
    // });

    // $("body").on("click",".om_stage_form_edit",function(e){
        
    //     var id = $(this).attr('data-id');        
    //     $("#form_output_div_"+id).hide();
    //     $("#form_input_div_inner_"+id).show();
    // });

    // $("body").on("click",".om_stage_form_edit_submit",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');
    //     var form_name=$("#stage_form_name_"+id).val();
    //     var data='edit_id='+id+'&form_name='+form_name;           

    //     if (form_name=='') 
    //     {
    //         swal("Oops!", "Name should not be null",'error');        
    //         return false;
    //     }  
        
    //     $.ajax({
    //             url: base_url+"order_management/edit_stage_form_setting",
    //             data: data,                    
    //             cache: false,
    //             method: 'GET',
    //             dataType: "html",                   
    //             beforeSend: function( xhr ) { 
    //                 //$("#preloader").css('display','block');                           
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);                        
    //                 if(result.status=='success')
    //                 {
    //                     $("#form_output_div_"+id).html(form_name);
    //                     $("#form_output_div_"+id).show();
    //                     $("#form_input_div_inner_"+id).hide();
    //                 }
    //             },
    //             complete: function(){
    //             //$("#preloader").css('display','none');
    //             },
    //     });
    // });

    // $("body").on("click",".form_input_div_close",function(e){
    //     var id = $(this).attr('data-id');
    //     $("#form_output_div_"+id).show();
    //     $("#form_input_div_inner_"+id).hide();
    // });

    // $("body").on("click",".om_stage_form_delete",function(e){
    //     var id = $(this).attr('data-id');

    //     if(id!='')
    //     {
    //         var base_url=$("#base_url").val();

    //         //Warning Message            
    //         swal({
    //             title: "Are you sure?",
    //             text: "You will not be able to recover this record!",
    //             type: "warning",
    //             showCancelButton: true,
    //             confirmButtonClass: 'btn-warning',
    //             confirmButtonText: "Yes, delete it!",
    //             closeOnConfirm: true
    //         }, function () {
    //             var data = 'id='+id;
    //             $.ajax({
    //                     url: base_url+"order_management/delete_stage_form",
    //                     data: data,
    //                     //data: new FormData($('#frmAccount')[0]),
    //                     cache: false,
    //                     method: 'GET',
    //                     dataType: "html",
    //                     //mimeType: "multipart/form-data",
    //                     //contentType: false,
    //                     //cache: false,
    //                     //processData:false,
    //                     beforeSend: function( xhr ) { 
    //                         $.blockUI({ 
    //                             message: 'Please wait...', 
    //                             css: { 
    //                                 padding: '10px', 
    //                                 backgroundColor: '#fff', 
    //                                 border:'0px solid #000',
    //                                 '-webkit-border-radius': '10px', 
    //                                 '-moz-border-radius': '10px', 
    //                                 opacity: .5, 
    //                                 color: '#000',
    //                                 width:'450px',
    //                                 'font-size':'14px'
    //                             }
    //                         });
    //                     },
    //                     complete: function(){
    //                         $.unblockUI();
    //                     },
    //                     success: function(data){
    //                         result = $.parseJSON(data);

    //                         if(result.status=='success'){
    //                             load_om_stage_form_view();
    //                         }
                            
                            
    //                     },
    //             });
                
    //         });
            
    //     }
    //     else
    //     { 
    //         swal("Oops!", "Check the record to delete.");            
    //     }
    // });

    // $("body").on("click",".om_stage_form_field_set_popup",function(e){        
       
       
    //     // var base_url=$("#base_url").val(); 
    //     var ThisObj=$(this);
    //     var id=ThisObj.attr('data-id');     
    //     //   alert(stage_id); return false;
    //     load_om_stage_form_field_set_popup_view(id);
    //     // $.ajax({
    //     //   url: base_url + "order_management/stage_form_field_set_popup_view_rander_ajax",
    //     //   type: "POST",
    //     //   data: {
    //     //       'id': id
    //     //   },
    //     //   async: true,
    //     //   dataType: "html", 
    //     //   success: function(data) {
    //     //     result = $.parseJSON(data); 
    //     //     $("#common_view_modal_title_lg").text(result.popup_title);
    //     //     $('#rander_common_view_modal_html_lg').html(result.html);
    //     //     $('#rander_common_view_modal_lg').modal({
    //     //         backdrop: 'static',
    //     //         keyboard: false
    //     //     });
              
    //     //   },
    //     //   error: function() {
    //     //       swal({
    //     //               title: 'Something went wrong there!',
    //     //               text: '',
    //     //               type: 'danger',
    //     //               showCancelButton: false
    //     //           }, function() {

    //     //       });
    //     //   }
    //     // });   
        
    // });

    // $("body").on("click","#om_stage_form_fields_add_submit",function(e){ 
    //     var base_url=$("#base_url").val();
    //     var form_id=$("#form_id").val();        
    //     $.ajax({
    //             url: base_url + "order_management/add_om_stage_form_fields_setting",
    //             data: new FormData($('#om_form_fields_set_frm')[0]),
    //             cache: false,
    //             method: 'POST',
    //             dataType: "html",
    //             mimeType: "multipart/form-data",
    //             contentType: false,
    //             cache: false,
    //             processData: false,                   
    //             beforeSend: function( xhr ) { 
    //                 $('#rander_common_view_modal_lg .modal-body').addClass('logo-loader');
    //             },
    //             complete: function(){
    //                 $('#rander_common_view_modal_lg .modal-body').removeClass('logo-loader');
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);
    //                 if(result.status=='success'){
    //                     load_om_stage_form_field_set_popup_view(form_id);
    //                 }
    //                 else{
    //                     swal("Oops!", result.msg, "error");
    //                 }

    //             },                    
    //     });
    // });
    // $("body").on("click",".om_stage_form_fields_delete",function(e){
    //     var id = $(this).attr('data-id');
    //     var form_id=$("#form_id").val();        

    //     if(id!='' && form_id!='')
    //     {
    //         var base_url=$("#base_url").val();

    //         //Warning Message            
    //         swal({
    //             title: "Are you sure?",
    //             text: "You will not be able to recover this record!",
    //             type: "warning",
    //             showCancelButton: true,
    //             confirmButtonClass: 'btn-warning',
    //             confirmButtonText: "Yes, delete it!",
    //             closeOnConfirm: true
    //         }, function () {
    //             var data = 'id='+id;
    //             $.ajax({
    //                     url: base_url+"order_management/delete_stage_form_fields",
    //                     data: data,
    //                     //data: new FormData($('#frmAccount')[0]),
    //                     cache: false,
    //                     method: 'GET',
    //                     dataType: "html",
    //                     //mimeType: "multipart/form-data",
    //                     //contentType: false,
    //                     //cache: false,
    //                     //processData:false,
    //                     beforeSend: function( xhr ) { 
    //                         $.blockUI({ 
    //                             message: 'Please wait...', 
    //                             css: { 
    //                                 padding: '10px', 
    //                                 backgroundColor: '#fff', 
    //                                 border:'0px solid #000',
    //                                 '-webkit-border-radius': '10px', 
    //                                 '-moz-border-radius': '10px', 
    //                                 opacity: .5, 
    //                                 color: '#000',
    //                                 width:'450px',
    //                                 'font-size':'14px'
    //                             }
    //                         });
    //                     },
    //                     complete: function(){
    //                         $.unblockUI();
    //                     },
    //                     success: function(data){
    //                         result = $.parseJSON(data);

    //                         if(result.status=='success'){
    //                             load_om_stage_form_field_set_popup_view(form_id);
    //                         }
                            
                            
    //                     },
    //             });
                
    //         });
            
    //     }
    //     else
    //     { 
    //         swal("Oops!", "Check the record to delete.");            
    //     }
    // });
    
    // $("body").on("click",".tag_assign_form_popup",function(e){
    //     var base_url=$("#base_url").val(); 
    //     var ThisObj=$(this);
    //     var stage_id=ThisObj.attr("data-stage_id");       
    //     //   alert(stage_id); return false;
    //     $.ajax({
    //       url: base_url + "order_management/tag_assign_form_to_stage_view_rander_ajax",
    //       type: "POST",
    //       data: {
    //           'stage_id': stage_id
    //       },
    //       async: true,
    //       success: function(response) {
    //           $("#common_view_modal_title_md").text("Assign Form");
    //           $('#rander_common_view_modal_html_md').html(response);
    //           $('#rander_common_view_modal_md').modal({
    //               backdrop: 'static',
    //               keyboard: false
    //           });
              
    //       },
    //       error: function() {
    //           swal({
    //                   title: 'Something went wrong there!',
    //                   text: '',
    //                   type: 'danger',
    //                   showCancelButton: false
    //               }, function() {

    //           });
    //       }
    //     });

    // });

    // $("body").on("click","#tag_assign_form_popup_confirm",function(e){
    //     var base_url = $("#base_url").val();        
    //     var stage_form_id=$("#om_stage_form_id").val();
    //     var stage_id=$(this).attr("data-stage_id");        
    //     var data = 'stage_id='+stage_id+"&stage_form_id="+stage_form_id;  
        
    //     if(!stage_form_id){
    //         swal("Oops!", "Please select user.",'error');          
    //         return false;
    //     }
    //     // alert(data); return false;
    //     $.ajax({
    //         url: base_url+"order_management/tag_assign_form_to_stage_update",
    //         data: data,                    
    //         cache: false,
    //         method: 'GET',
    //         dataType: "html",                   
    //         beforeSend: function( xhr ) { 
    //           //$("#preloader").css('display','block');                           
    //         },
    //         success: function(data){
    //             result = $.parseJSON(data);  
    //             // console.log(result)   
    //             if(result.status=='success')
    //             {
    //                 $("#rander_common_view_modal_md").modal('hide');
    //                 load_om_stage_wise_form_assign_view(); 
                    
    //             }
                
    //         },
    //         complete: function(){
    //         //$("#preloader").css('display','none');
    //         },
    //     });

    // });

    // $("body").on("click",".untag_assign_form_popup",function(e){
    //     var base_url=$("#base_url").val(); 
    //     var ThisObj=$(this);
    //     var stage_id=ThisObj.attr("data-stage_id");       
    //       //alert(stage_id);// return false;
    //     $.ajax({
    //       url: base_url + "order_management/untag_assign_form_to_stage_view_rander_ajax",
    //       type: "POST",
    //       data: {
    //           'stage_id': stage_id
    //       },
    //       async: true,
    //       success: function(response) {
    //           $("#common_view_modal_title_md").text("Assign Form");
    //           $('#rander_common_view_modal_html_md').html(response);
    //           $('#rander_common_view_modal_md').modal({
    //               backdrop: 'static',
    //               keyboard: false
    //           });
              
    //       },
    //       error: function() {
    //           swal({
    //                   title: 'Something went wrong there!',
    //                   text: '',
    //                   type: 'danger',
    //                   showCancelButton: false
    //               }, function() {

    //           });
    //       }
    //     });

    // });

    // $("body").on("click",".untag_assign_form_popup_confirm",function(e){
        
    //     var base_url = $("#base_url").val();        
    //     var form_id=$(this).attr("data-form_id");
    //     var stage_id=$(this).attr("data-stage_id");        
    //     var data = 'stage_id='+stage_id+"&form_id="+form_id;  
        
    //     $.ajax({
    //         url: base_url+"order_management/untag_assign_form_to_stage_update",
    //         data: data,                    
    //         cache: false,
    //         method: 'GET',
    //         dataType: "html",                   
    //         beforeSend: function( xhr ) { 
    //           //$("#preloader").css('display','block');                           
    //         },
    //         success: function(data){
    //             result = $.parseJSON(data);  
    //             // console.log(result)   
    //             if(result.status=='success')
    //             {
    //                 // $("#rander_common_view_modal_md").modal('hide');
    //                 load_om_stage_wise_form_assign_view();                    
    //                 $("#div_form_"+form_id).remove();
    //                 if($("#un_tbody_form").find('tr').length==0){
    //                     $("#un_tbody_form").html('<tr><td colspan="2" class="align-middle" class="text-center">No Record Found!</td></tr>');
    //                 }
                    
    //             }
                
    //         },
    //         complete: function(){
    //         //$("#preloader").css('display','none');
    //         },
    //     });

    // });
    // ======================================================================

    // ---------------------------------------------
    // ---------------------------------------------
    // $("body").on("click",".om_stage_edit",function(e){
    //     var id = $(this).attr('data-id');
    //     $("#output_div_"+id).hide();
    //     $("#input_div_inner_"+id).show();
    // });
    // $("body").on("click",".input_div_close",function(e){
    // var id = $(this).attr('data-id');
    // $("#output_div_"+id).show();
    // $("#input_div_inner_"+id).hide();
    // });

    // $("body").on("click",".om_stage_edit_submit",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');
    //     var stage=$("#stage_"+id).val();
    //     var data='edit_id='+id+'&stage='+stage;           

    //     if (stage=='') 
    //     {
    //         swal("Oops!", "Name should not be null",'error');        
    //         return false;
    //     }  

    //     $.ajax({
    //             url: base_url+"order_management/edit_stage_setting",
    //             data: data,                    
    //             cache: false,
    //             method: 'GET',
    //             dataType: "html",                   
    //             beforeSend: function( xhr ) { 
    //                 //$("#preloader").css('display','block');                           
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);                        
    //                 if(result.status=='success')
    //                 {
    //                     $("#output_div_"+id).html(stage);
    //                     $("#output_div_"+id).show();
    //                     $("#input_div_inner_"+id).hide();
    //                 }
    //             },
    //             complete: function(){
    //             //$("#preloader").css('display','none');
    //             },
    //     });
    // });

    // $("body").on("click",".om_stage_delete",function(e){
    //     var id = $(this).attr('data-id');

    //     if(id!='')
    //     {
    //         var base_url=$("#base_url").val();

    //         //Warning Message            
    //         swal({
    //             title: "Are you sure?",
    //             text: "You will not be able to recover this record!",
    //             type: "warning",
    //             showCancelButton: true,
    //             confirmButtonClass: 'btn-warning',
    //             confirmButtonText: "Yes, delete it!",
    //             closeOnConfirm: true
    //         }, function () {
    //             var data = 'id='+id;
    //             $.ajax({
    //                     url: base_url+"order_management/delete_stage",
    //                     data: data,
    //                     //data: new FormData($('#frmAccount')[0]),
    //                     cache: false,
    //                     method: 'GET',
    //                     dataType: "html",
    //                     //mimeType: "multipart/form-data",
    //                     //contentType: false,
    //                     //cache: false,
    //                     //processData:false,
    //                     beforeSend: function( xhr ) { 
    //                         $.blockUI({ 
    //                             message: 'Please wait...', 
    //                             css: { 
    //                                 padding: '10px', 
    //                                 backgroundColor: '#fff', 
    //                                 border:'0px solid #000',
    //                                 '-webkit-border-radius': '10px', 
    //                                 '-moz-border-radius': '10px', 
    //                                 opacity: .5, 
    //                                 color: '#000',
    //                                 width:'450px',
    //                                 'font-size':'14px'
    //                             }
    //                         });
    //                     },
    //                     complete: function(){
    //                         $.unblockUI();
    //                     },
    //                     success: function(data){
    //                         result = $.parseJSON(data);

    //                         if(result.status=='success'){
    //                             load_om_stage_view();
    //                         }
                            
                            
    //                     },
    //             });
                
    //         });
            
    //     }
    //     else
    //     { 
    //         swal("Oops!", "Check the record to delete.");            
    //     }
    // });

    // ---------------------------------------------
    // ---------------------------------------------

    
});




// function SopenDiv(evt, divName) 
// { 
//   $("body, html").animate({ scrollTop: 200 }, "slow");
//   var i, tabcontent, tablinks;
  
//   tabcontent = document.getElementsByClassName("StabcontentDiv");
//   for (i = 0; i < tabcontent.length; i++) {
//     tabcontent[i].style.display = "none";
//   }
  
//   tablinks = document.getElementsByClassName("Stablinks");
//   for (i = 0; i < tablinks.length; i++) {
//     tablinks[i].className = tablinks[i].className.replace(" active", "");
//   }
  
//   document.getElementById(divName).style.display = "block";
//   evt.currentTarget.className += " active";
  
//   //////////
//   if(divName=='Stab_1')
//   { 
//     load_om_stage_view();   
//   } 
//   if(divName=='Stab_2')
//   { 
//     load_om_stage_wise_user_assign_view();
//   }  
//   if(divName=='Stab_3')
//   { 
//     load_om_stage_form_view();
//   }
//   if(divName=='Stab_4')
//   { 
//     load_om_stage_wise_form_assign_view();
//   } 
    
// }
// ---------------------------------------------
// ---------------------------------------------

// function load_om_stage_view()
// {       
//     var base_URL=$("#base_url").val();
//     var data = "";        
//     $.ajax({
//         url: base_URL+"order_management/rander_om_stage_list_ajax/",
//         data: data,
//         cache: false,
//         method: 'GET',
//         dataType: "html",
//         beforeSend: function( xhr ) {                
//             //addLoader('.table-responsive');
//         },
//         success:function(res){ 
//             result = $.parseJSON(res);           
//             // $("body, html").animate({ scrollTop: 500 }, "slow");   
//             $("#om_stage_tcontent").html(result.table);
//             $( "#lead_stage_sortable" ).sortable({
//                 axis: 'y',
//                 update: function (event, ui) {                  
//                     var new_sort = $("#lead_stage_sortable").sortable("serialize", {key:'new_sort[]'});
//                     var base_url=$("#base_url").val();
//                     var data=new_sort;
//                     $.ajax({
//                         url: base_url+"order_management/resort_om_stage",
//                         data: data,                    
//                         cache: false,
//                         method: 'GET',
//                         dataType: "html",                   
//                         beforeSend: function( xhr ) { 
//                         $.blockUI({ 
//                             message: 'Please wait...', 
//                             css: { 
//                                 padding: '10px', 
//                                 backgroundColor: '#fff', 
//                                 border:'0px solid #000',
//                                 '-webkit-border-radius': '10px', 
//                                 '-moz-border-radius': '10px', 
//                                 opacity: .5, 
//                                 color: '#000',
//                                 width:'450px',
//                                 'font-size':'14px'
//                                 }
//                         });
//                         },
//                         complete: function(){
//                         $.unblockUI();
//                         },
//                         success: function(data){
//                             result = $.parseJSON(data);                        
//                             if(result.status=='success')
//                             {
//                                 load_om_stage_view();
//                             }
//                         },
//                     });
//                 }
//             });
//         },
//         complete: function(){
//         //removeLoader();
//         },
//         error: function(response) {
//         //alert('Error'+response.table);
//         }
//     })
// }
// function load_om_stage_wise_user_assign_view()
// {       
//     var base_URL=$("#base_url").val();
//     var data = "";        
//     $.ajax({
//         url: base_URL+"order_management/rander_om_stage_wise_user_assign_ajax/",
//         data: data,
//         cache: false,
//         method: 'GET',
//         dataType: "html",
//         beforeSend: function( xhr ) {                
//             //addLoader('.table-responsive');
//         },
//         success:function(res){ 
//             result = $.parseJSON(res);           
//             // $("body, html").animate({ scrollTop: 500 }, "slow");   
//             $("#om_stage_wise_assign_user_tcontent").html(result.table);            
//         },
//         complete: function(){
//         //removeLoader();
//         },
//         error: function(response) {
//         //alert('Error'+response.table);
//         }
//     })
// }
// ---------------------------------------------
// ---------------------------------------------

// ---------------------------------------------
// ---------------------------------------------
// function load_om_stage_form_view()
// {       
//     var base_URL=$("#base_url").val();
//     var data = "";        
//     $.ajax({
//         url: base_URL+"order_management/rander_om_stage_form_list_ajax/",
//         data: data,
//         cache: false,
//         method: 'GET',
//         dataType: "html",
//         beforeSend: function( xhr ) {                
//             //addLoader('.table-responsive');
//         },
//         success:function(res){ 
//             result = $.parseJSON(res);           
//             // $("body, html").animate({ scrollTop: 500 }, "slow");   
//             $("#om_stage_form_tcontent").html(result.table);            
//         },
//         complete: function(){
//         //removeLoader();
//         },
//         error: function(response) {
//         //alert('Error'+response.table);
//         }
//     })
// }
// ---------------------------------------------
// ---------------------------------------------

// ---------------------------------------------
// ---------------------------------------------
// function load_om_stage_form_field_set_popup_view(id)
// {       
//     var base_url=$("#base_url").val(); 
//     $.ajax({
//         url: base_url + "order_management/stage_form_field_set_popup_view_rander_ajax",
//         type: "POST",
//         data: {
//             'id': id
//         },
//         async: true,
//         dataType: "html", 
//         success: function(data) {
//         result = $.parseJSON(data); 
//         $("#common_view_modal_title_lg").text(result.popup_title);
//         $('#rander_common_view_modal_html_lg').html(result.html);
//         $( "#stage_form_fields_sortable" ).sortable({
//             axis: 'y',
//             update: function (event, ui) {                  
//                 var new_sort = $("#stage_form_fields_sortable").sortable("serialize", {key:'new_sort[]'});
//                 var base_url=$("#base_url").val();
//                 var data=new_sort;
                
//                 $.ajax({
//                     url: base_url+"order_management/resort_om_stage_form_fields",
//                     data: data,                    
//                     cache: false,
//                     method: 'GET',
//                     dataType: "html",                   
//                     beforeSend: function( xhr ) { 
//                     $.blockUI({ 
//                         message: 'Please wait...', 
//                         css: { 
//                             padding: '10px', 
//                             backgroundColor: '#fff', 
//                             border:'0px solid #000',
//                             '-webkit-border-radius': '10px', 
//                             '-moz-border-radius': '10px', 
//                             opacity: .5, 
//                             color: '#000',
//                             width:'450px',
//                             'font-size':'14px'
//                             }
//                     });
//                     },
//                     complete: function(){
//                     $.unblockUI();
//                     },
//                     success: function(data){
//                         result = $.parseJSON(data);                        
//                         if(result.status=='success')
//                         {
//                             load_om_stage_form_field_set_popup_view(id);
//                         }
//                     },
//                 });
//             }
//         });
//         $('#rander_common_view_modal_lg').modal({
//             backdrop: 'static',
//             keyboard: false
//         });
            
//         },
//         error: function() {
//             swal({
//                     title: 'Something went wrong there!',
//                     text: '',
//                     type: 'danger',
//                     showCancelButton: false
//                 }, function() {

//             });
//         }
//     });
// }
// ---------------------------------------------
// ---------------------------------------------

// ---------------------------------------------
// ---------------------------------------------
// function load_om_stage_wise_form_assign_view()
// {       
//     var base_URL=$("#base_url").val();
//     var data = "";        
//     $.ajax({
//         url: base_URL+"order_management/rander_om_stage_wise_form_assign_ajax/",
//         data: data,
//         cache: false,
//         method: 'GET',
//         dataType: "html",
//         beforeSend: function( xhr ) {                
//             //addLoader('.table-responsive');
//         },
//         success:function(res){ 
//             result = $.parseJSON(res);           
//             // $("body, html").animate({ scrollTop: 500 }, "slow");               
//             $("#om_stage_wise_assign_form_tcontent").html(result.table);            
//         },
//         complete: function(){
//         //removeLoader();
//         },
//         error: function(response) {
//         //alert('Error'+response.table);
//         }
//     })
// }
// ---------------------------------------------
// ---------------------------------------------
</script>
