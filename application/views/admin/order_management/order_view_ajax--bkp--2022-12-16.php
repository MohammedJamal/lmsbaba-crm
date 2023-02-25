<style>
.sortable_ul{ list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 143px;}
.sortable_ul li{ margin: 5px; padding: 5px; font-size: 1.2em; width: 120px; }
</style>

<div class="sortable_main_container">
    <div class="sortable_table_container">
        <ul class="sortable_main_ul">
            <li>
                <div class="sortable_head">
                    <h3>Un-Stage</h3>
                    <div class="heading-info">
                        <div>Totel order <strong>$1.825.000</strong></div>
                        <div><a href="#">View details</a></div>
                    </div>
                </div>
                <div class="sortable_body" id="sortable0" class="droptrue sortable_ul sortable_cancel">
                    <?php if(count($untagged_pi_list)){ ?>
                    <?php foreach($untagged_pi_list AS $untagged_pi_list){ ?>  
                    
                    <div class="ui-state-default custom-white-card" id="<?php echo $untagged_pi_list['id']; ?>" data-sid="0">
                        <div class="sortable_order_detail">
                            <p><?php echo ($untagged_pi_list['po_number'])?'<b>PO No.- </b>'.$untagged_pi_list['po_number']:''; ?></p>
                            <p><?php echo ($untagged_pi_list['po_date'])?'<b>PO Date-</b> '.date_db_format_to_display_format($untagged_pi_list['po_date']):''; ?></p>
                            <p>
                                <?php echo ($untagged_pi_list['cus_company_name'])?''.$untagged_pi_list['cus_company_name']:$untagged_pi_list['cus_contact_person']; ?>
                                <?php echo ($untagged_pi_list['cust_city_name'])?', '.$untagged_pi_list['cust_city_name']:''; ?>
                                <?php echo ($untagged_pi_list['cust_country_name'])?', '.$untagged_pi_list['cust_country_name']:''; ?>
                            </p>
                        </div>
                        <div class="sortable_order_dot">
                            <ul class="d-inline-flex">
                                <li class="pr-5px">
                                    <a class="change_status_order" href="JavaScript:void(0);">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a href="JavaScript:void(0)" class="get_om_detail dropdown-item" data-lowp="<?php echo $pi_list['lead_opportunity_wise_po_id']; ?>" data-pfi="<?php echo $pi_list['id']; ?>" data-stageid="<?php echo $stage_list['id']; ?>">View Detail</a>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <?php } ?>
                    <?php } ?>
                </div>
            </li>
            <?php if(count($active_stage_list)){ ?>
            <?php foreach($active_stage_list AS $stage_list){ ?>
            <li>
                <div class="sortable_head">
                    <h3><?php echo $stage_list['name']; ?></h3>
                    <div class="heading-info">
                        <div>Totel order <strong>$1.825.000</strong></div>
                        <div><a href="#">View details</a></div>
                    </div>
                </div>
                <div class="sortable_body" id="sortable<?php echo $stage_list['id']; ?>" class="droptrue sortable_ul sortable_cancel" data-id="<?php echo $stage_list['id']; ?>">
                <?php if(count($tagged_pi_list)){ ?>
                <?php foreach($tagged_pi_list AS $pi_list){ ?>
                <?php if($stage_list['id']==$pi_list['pi_stage_id']){ ?>
                
                    <div class="ui-state-default custom-white-card" id="<?php echo $pi_list['id']; ?>" data-sid="<?php echo $stage_list['id']; ?>">
                        <div class="sortable_order_detail">
                            <p><?php echo ($pi_list['lead_opportunity_wise_po_id'])?'<b>Order ID-</b> '.$pi_list['lead_opportunity_wise_po_id']:''; ?></p>
                            <p><?php echo ($pi_list['po_number'])?'<b>PO No.-</b> '.$pi_list['po_number']:''; ?></p>
                            <p><?php echo ($pi_list['po_date'])?'<b>PO Date-</b> '.date_db_format_to_display_format($pi_list['po_date']):''; ?></p>
                            <p>
                                <?php echo ($pi_list['cus_company_name'])?''.$pi_list['cus_company_name']:$pi_list['cus_contact_person']; ?>
                                <?php echo ($pi_list['cust_city_name'])?', '.$pi_list['cust_city_name']:''; ?>
                                <?php echo ($pi_list['cust_country_name'])?', '.$pi_list['cust_country_name']:''; ?>
                            </p>
                        </div>
                        <div class="sortable_order_dot">
                            <ul class="d-inline-flex">
                                <li class="pr-5px">
                                    <a class="change_status_order" href="JavaScript:void(0);">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a href="JavaScript:void(0)" class="get_om_detail dropdown-item" data-lowp="<?php echo $pi_list['lead_opportunity_wise_po_id']; ?>" data-pfi="<?php echo $pi_list['id']; ?>" data-stageid="<?php echo $stage_list['id']; ?>">View Detail</a>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            
                        </div>
                    </div>
                
                <?php } ?>
                <?php } ?>
                <?php } ?>
                </div>
            </li>
            <?php } ?>
            <?php } ?>
            
        </ul>

    </div>
</div>

<script>
  $( function() {
    
    $( ".sortable_body" ).sortable({
        connectWith: ".sortable_body",
        update: function(event, ui ) {            
        },
        stop: function( event, ui) {            
            var current_stage_id=ui.item.parent().attr("data-id");
            var prev_stage_id=ui.item.attr("data-sid");
            var pi_id = ui.item.attr("id");            
            fn_update_pi_stage(pi_id,current_stage_id,prev_stage_id);           
        }
    }); 
    $(".sortable_cancel").sortable({
        cancel: ".unsortable"
    }); 
    // $("body").on("click",".get_om_detail",function(e){
    //     var base_url=$("#base_url").val(); 
    //     var ThisObj=$(this);
    //     var lowp=ThisObj.attr("data-lowp");
    //     var pfi=ThisObj.attr("data-pfi");
    //     var stage_id=ThisObj.attr("data-stageid");
    //     $.ajax({
    //       url: base_url + "order_management/om_detail_view_rander_ajax",
    //       type: "POST",
    //       data: {
    //           'lowp': lowp,
    //           'pfi': pfi,
    //           'stage_id': stage_id
    //       },
    //       async: true,
    //       success: function(data) {
    //             result = $.parseJSON(data); 
    //             $('#OmDetailModalBody').html(result.html);
    //             $('#OmDetailModal').modal({
    //                     backdrop: 'static',
    //                     keyboard: false
    //             });

    //             // $("#common_view_modal_title_lg").text("Assign Form");
    //             // $('#rander_common_view_modal_html_lg').html(result.html);
    //             // $('#rander_common_view_modal_lg').modal({
    //             //     backdrop: 'static',
    //             //     keyboard: false
    //             // });
              
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
       

    // =======================================================================================
    // ORDER MANAGEMENT DETAILS
    // $("body").on("change","#form_id",function(e){
        
    //     var base_url=$("#base_url").val();
    //     var f_id=$(this).val();
    //     var proforma_invoice_id=$("#proforma_invoice_id").val();
    //     if(f_id==''){
    //         $('#om_form_wise_fields_div').html('');
    //         return false;
    //     }
    //     $.ajax({
    //         url: base_url + "order_management/om_form_wise_fields_view_rander_ajax",
    //         type: "POST",
    //         data: {
    //             'f_id':f_id,
    //             'proforma_invoice_id':proforma_invoice_id
    //         },
    //         async: true,            
    //         success: function(data) {
    //             result = $.parseJSON(data); 
                
    //             if(result.status=='success'){
    //                 $('#om_form_wise_fields_div').html(result.html);
    //             }
    //             else{
    //                 swal({
    //                     title: 'Oops!',
    //                     text: result.msg,
    //                     type: 'error',
    //                     showCancelButton: false
    //                     }, function() {
  
    //                 });
    //             }
                
                
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
    // });
  
    // $("body").on("click","#om_stage_wise_document_save_confirm",function(e){
        
    //     e.preventDefault();
        
    //     var base_url=$("#base_url").val();  
    //     var missing_name='';  
    //     jQuery('.required').each(function() {
    //         var currentElement = $(this);
    //         var value = currentElement.val(); 
    //         if(value==''){
    //             missing_name +=currentElement.attr('data-label')+' required.<br>';
                
    //         }
    //     });
    //     if(missing_name){
    //         swal({   
    //             title: "Oops!",  
    //             text: missing_name,
    //             html: true,
    //             type:'error' 
    //         });                            
    //         return false;
    //     }
       
    //     var str_tmp='';
    //     jQuery('.om_custom_form_field').each(function() {
            
    //         var currentElement = $(this);
    //         var type=currentElement.prop("type");
            
    //         if(type=='text' || type=='textarea' || type=='file'){
    //             var value = currentElement.val(); 
    //             var id=currentElement.attr('data-id'); 
    //             var name=currentElement.attr('name'); 
    //             str_tmp +=id+'~'+value+'~'+name+'!***!';
    //         }
    //         else{
    //             if(type=='radio'){
    //                 if(currentElement.is(':checked')) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     var name=currentElement.attr('name');
    //                     str_tmp +=id+'~'+value+'~'+name+'!***!';
    //                 }
    //             } 
    //             else if(type=='checkbox'){                   
    //                 // if(currentElement.is(':checked')) {
    //                 //     var value = currentElement.val(); 
    //                 //     var id=currentElement.attr('data-id'); 
    //                 //     var name=currentElement.attr('name');
    //                 //     str_tmp +=id+'~'+value+'~'+name+'!***!';

                        
    //                 // }
                    
    //             }                
    //             else if(type=='select-one'){
    //                 if(currentElement.val()) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     var name=currentElement.attr('name');
    //                     str_tmp +=id+'~'+value+'~'+name+'!***!';
                        
    //                 }
    //             }
    //         }            
    //     });        
    //     var name_arr=[];
    //     jQuery('.om_custom_form_field').each(function() {            
    //         var currentElement = $(this);
    //         var type=currentElement.prop("type");            
    //         var name_tmp='';
    //         if(type=='checkbox'){
    //             var name=currentElement.attr('name');
    //             name_arr.push(name);                      
    //         }             
    //     });
    //     var name_unique = name_arr.filter(function(itm, i, a) {
    //         return i == a.indexOf(itm);
    //     });        
    //     $.each(name_unique, function( index, value ) {
    //         var yourArray=[];
    //         var yourArray2=[];    
    //         var flag=0;        
    //         $("input:checkbox[name="+value+"]:checked").each(function(){
    //             yourArray.push($(this).val());
    //             yourArray2.push($(this).attr('data-id'));
    //             flag=1;
                
    //         });

    //         if(flag==1){
    //             var id_unique = yourArray2.filter(function(itm, i, a) {
    //                 return i == a.indexOf(itm);
    //             });
    //             str_tmp +=id_unique+'~'+yourArray.join("^")+'~'+value+'!***!';
    //         }
    //     });
    //     // alert(str_tmp);return false;
    //     $('form#omDocFrm').append('<input type="hidden" name="om_custom_form_field" value="'+str_tmp+'" />');
    //     $.ajax({
    //             url: base_url + "order_management/om_stage_wise_doc_submit",
    //             data: new FormData($('#omDocFrm')[0]),
    //             cache: false,
    //             method: 'POST',
    //             dataType: "html",
    //             mimeType: "multipart/form-data",
    //             contentType: false,
    //             cache: false,
    //             processData: false,                   
    //             beforeSend: function( xhr ) {                
    //                 $("#om_stage_wise_document_save_confirm").attr("disabled",true);
    //             },
    //             complete: function(){
    //                 $("#om_stage_wise_document_save_confirm").attr("disabled",false);
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);
    //                 // alert(result.msg)
    //                 if(result.status=='success'){
    //                     swal({
    //                             title: 'Success!',
    //                             text: 'Record successfully saved',
    //                             type: 'success',
    //                             showCancelButton: false
    //                         }, function() {                                
    //                             $("#form_id option:first").prop("selected", "selected");
    //                             $("#om_form_wise_fields_div").html('');
    //                             var piid=$("#proforma_invoice_id").val();
    //                             fn_rander_document_list(piid);
    //                     });
    //                 }
    //                 else{
    //                     swal({
    //                             title: 'Oops!',
    //                             text: result.msg,
    //                             type: 'error',
    //                             showCancelButton: false
    //                         }, function() {  });
    //                 }
                    
                    
  
    //             },                    
    //     });
    // });
  
    // $('#rander_common_view_modal_lg').on('hide.bs.modal', function (e) {
    //     if ($('#OmDetailModal').hasClass('in')) {
    //         $("#OmDetailModal").css("display","block");            
    //     }
    // })
  
    // $("body").on("click",".document_view_popup",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');
    //     $.ajax({
    //       url: base_url + "order_management/document_view_ajax",
    //       type: "POST",
    //       data: {
    //           'id': id
    //       },
    //       async: true,
    //       success: function(data) {
    //             result = $.parseJSON(data);
    //             $("#OmDetailModal").css("display","none");
    //             $("#common_view_modal_title_lg").text(result.title);
    //             $('#rander_common_view_modal_html_lg').html(result.html);
    //             $('#rander_common_view_modal_lg').modal({
    //                 backdrop: 'static',
    //                 keyboard: false
    //             });
              
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
  
    // $("body").on("click",".document_edit_popup",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');
    //     $.ajax({
    //       url: base_url + "order_management/document_edit_view_ajax",
    //       type: "POST",
    //       data: {
    //           'id': id
    //       },
    //       async: true,
    //       success: function(data) {
    //             result = $.parseJSON(data);
    //             $("#OmDetailModal").css("display","none");
    //             $("#common_view_modal_title_lg").text(result.title);
    //             $('#rander_common_view_modal_html_lg').html(result.html);
    //             $('#rander_common_view_modal_lg').modal({
    //                 backdrop: 'static',
    //                 keyboard: false
    //             });
              
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
  
    // $("body").on("click","#om_stage_wise_document_edit_confirm",function(e){
    //     e.preventDefault();
    //     var base_url=$("#base_url").val();  
    //     var missing_name='';  
    //     jQuery('.required_edit').each(function() {
    //         var currentElement = $(this);
    //         var value = currentElement.val(); 
    //         if(value==''){
    //             missing_name +=currentElement.attr('data-label')+' required.<br>';
                
    //         }
    //     });
    //     if(missing_name){
    //         swal({   
    //             title: "Oops!",  
    //             text: missing_name,
    //             html: true,
    //             type:'error' 
    //         });                            
    //         return false;
    //     }
       
    //     var str_tmp='';
    //     jQuery('.om_custom_form_field_edit').each(function() {
            
    //         var currentElement = $(this);
    //         var type=currentElement.prop("type");
            
    //         if(type=='text' || type=='textarea' || type=='file'){
    //             var value = currentElement.val(); 
    //             var id=currentElement.attr('data-id'); 
    //             str_tmp +=id+'~'+value+'!***!';
    //         }
    //         else{
    //             if(type=='radio'){
    //                 if(currentElement.is(':checked')) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     str_tmp +=id+'~'+value+'!***!';
    //                 }
    //             } 
    //             else if(type=='checkbox'){
    //                 // if(currentElement.is(':checked')) {
                    
    //                 //     var value = currentElement.val(); 
    //                 //     var id=currentElement.attr('data-id'); 
    //                 //     str_tmp +=id+'~'+value+'!***!';
    //                 // }
    //             }                
    //             else if(type=='select-one'){
    //                 if(currentElement.val()) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     str_tmp +=id+'~'+value+'!***!';
                        
    //                 }
    //             }
    //         }            
    //     });

    //     var name_arr=[];
    //     jQuery('.om_custom_form_field_edit').each(function() {            
    //         var currentElement = $(this);
    //         var type=currentElement.prop("type");            
    //         var name_tmp='';
    //         if(type=='checkbox'){
    //             var name=currentElement.attr('name');
    //             name_arr.push(name);                      
    //         }             
    //     });
    //     var name_unique = name_arr.filter(function(itm, i, a) {
    //         return i == a.indexOf(itm);
    //     });        
    //     $.each(name_unique, function( index, value ) {
    //         var yourArray=[];
    //         var yourArray2=[];    
    //         var flag=0;        
    //         $("input:checkbox[name="+value+"]:checked").each(function(){
    //             yourArray.push($(this).val());
    //             yourArray2.push($(this).attr('data-id'));
    //             flag=1;
                
    //         });

    //         if(flag==1){
    //             var id_unique = yourArray2.filter(function(itm, i, a) {
    //                 return i == a.indexOf(itm);
    //             });
    //             str_tmp +=id_unique+'~'+yourArray.join("^")+'~'+value+'!***!';
    //         }
    //     });
    //     // alert(str_tmp);return false;
    //     $('form#omDocEditFrm').append('<input type="hidden" name="om_custom_form_field_edit" value="'+str_tmp+'" />');
    //     $.ajax({
    //             url: base_url + "order_management/om_stage_wise_doc_submit_edit",
    //             data: new FormData($('#omDocEditFrm')[0]),
    //             cache: false,
    //             method: 'POST',
    //             dataType: "html",
    //             mimeType: "multipart/form-data",
    //             contentType: false,
    //             cache: false,
    //             processData: false,                   
    //             beforeSend: function( xhr ) { 
                    
    //             },
    //             complete: function(){
                    
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);
                    
    //                 if(result.status=='success'){
  
    //                     swal({
    //                             title: 'Success!',
    //                             text: 'Record successfully updated',
    //                             type: 'success',
    //                             showCancelButton: false
    //                         }, function() {
    //                             $("#rander_common_view_modal_lg").modal("hide");
    //                     });
  
                        
    //                 }
                    
    //             },                    
    //     });
    // });
  
    // $("body").on("click",".document_delete",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');
  
    //     swal({
    //             title: "Are you sure?",
    //             text: "You will not be able to recover the document!",
    //             type: "warning",
    //             showCancelButton: true,
    //             confirmButtonClass: 'btn-warning',
    //             confirmButtonText: "Yes, delete it!",
    //             closeOnConfirm: false
    //         }, function () {
  
    //             $.ajax({
    //                 url: base_url + "order_management/document_delete_ajax",
    //                 type: "POST",
    //                 data: {
    //                     'id': id
    //                 },
    //                 async: true,
    //                 success: function(data) {
    //                         result = $.parseJSON(data);
    //                         if(result.status=='success'){
    //                             swal({
    //                                     title: 'Success!',
    //                                     text: 'Successfully deleted',
    //                                     type: 'success',
    //                                     showCancelButton: false
    //                                 }, function() {
    //                                     var piid=$("#proforma_invoice_id").val();
    //                                     fn_rander_document_list(piid);
    //                             });
    //                         }
                        
    //                 },
    //                 error: function() {
    //                     swal({
    //                             title: 'Something went wrong there!',
    //                             text: '',
    //                             type: 'danger',
    //                             showCancelButton: false
    //                         }, function() {
  
    //                     });
    //                 }
    //             });
    //         });
        
    // });
      // =======================================================================================
  } );

   
  </script>