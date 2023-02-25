$(document).ready(function(){   
    load(1);
    $(document).on('click', '#product_wise_vendor_submit', function (e) {
      e.preventDefault();      
      var base_url = $("#base_url").val(); 

      var existing_product_id_obj=$("#existing_product_id");
      var pwv_vendor_id_obj=$("#pwv_vendor_id");
      var pwv_price_obj=$("#pwv_price");
      var pwv_currency_type_obj=$("#pwv_currency_type");
      var pwv_unit_obj=$("#pwv_unit");
      var pwv_unit_type_obj=$("#pwv_unit_type");

      if(pwv_vendor_id_obj.val()=='')
      {
        pwv_vendor_id_obj.addClass('error_input');
        $("#pwv_vendor_id_error").html('Please select vendor.');
        return false;
      }
      else
      {
        pwv_vendor_id_obj.removeClass('error_input');
        $("#pwv_vendor_id_error").html('');
      }

      if(pwv_price_obj.val()=='')
      {
        pwv_price_obj.addClass('error_input');
        $("#pwv_price_error").html('Please enter price.');
        pwv_price_obj.focus();
        return false;
      }
      else
      {
        pwv_price_obj.removeClass('error_input');
        $("#pwv_price_error").html('');
      }

      if(pwv_currency_type_obj.val()=='')
      {
        pwv_currency_type_obj.addClass('error_input');
        $("#pwv_currency_type_error").html('Please select currency type.');
        return false;
      }
      else
      {
        pwv_currency_type_obj.removeClass('error_input');
        $("#pwv_currency_type_error").html('');
      }

      if(pwv_unit_obj.val()=='')
      {
        pwv_unit_obj.addClass('error_input');
        $("#pwv_unit_error").html('Please enter unit');
        pwv_unit_obj.focus();
        return false;
      }
      else
      {
        pwv_unit_obj.removeClass('error_input');
        $("#pwv_unit_error").html('');
      }

      if(pwv_unit_type_obj.val()=='')
      {
        pwv_unit_type_obj.addClass('error_input');
        $("#pwv_unit_type_error").html('Please select unit type.');
        return false;
      }
      else
      {
        pwv_unit_type_obj.removeClass('error_input');
        $("#pwv_unit_type_error").html('');
      }

      $.ajax({
        url: base_url+"product/add_product_wise_dendor_ajax",
        data: new FormData($('#frm_product_wise_dendor')[0]),
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

          $("#pwv_vendor_id").val($("#pwv_vendor_id option:first").val());
          $("#pwv_price").val('');
          $("#pwv_currency_type").val($("#pwv_currency_type option:first").val());
          $("#pwv_unit").val('');
          $("#pwv_unit_type").val($("#pwv_unit_type option:first").val());

          if(result.status=='success')
          {            
            swal('Success!', 'The vendor successfully tagged to the product!', 'success');
            load(1);
          }
          else if(result.status=='already_exist')
          {
            swal('Oops!', 'The vendor already tagged to the product!', 'error');
          }
        }
      });      
    });

    // all check
    $("body").on("click","#set_all",function(e){
        if(this.checked) {            
            $("input:checkbox[class=set_individual]").prop('checked', this.checked);
        }
        else{            
            $("input:checkbox[class=set_individual]").prop('checked', false);
        }
        var ids_arr=[];
        $.each($("input[class='set_individual']:checked"), function(){ 
            ids_arr.push($(this).val());
        });
        $("#checked_ids").val(ids_arr.toString());
    });

    $("body").on("click",".set_individual",function(e){ 
        var ids_arr=[];
        $.each($("input[class='set_individual']:checked"), function(){ 
            ids_arr.push($(this).val());
        });
        $("#checked_ids").val(ids_arr.toString());
    });
    // all check end

    $("body").on("click","#del_product_tagged_vendor_btn",function(e){
        var del_id_str = $("#checked_ids").val();
        if(del_id_str!='')
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
                var data = 'del_id_str='+del_id_str;               
                $.ajax({
                        url: base_url+"product/delete_product_tagged_vendor",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
                        beforeSend: function( xhr ) { },
                        success: function(data){
                            result = $.parseJSON(data);                            
                            swal({
                                title: "Deleted!",
                                text: "The record(s) have been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { load(1); });
                           
                        },
                        complete: function(){},
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
    });

    $("body").on("click",".close_product_tagged_vendor",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr("data-id");

        $("#ptb_price_input_html_"+id).hide()
        $("#ptb_currency_type_input_html_"+id).hide()
        $("#ptb_unit_input_html_"+id).hide()
        $("#ptb_unit_type_input_html_"+id).hide()

        $("#ptb_price_html_"+id).show();
        $("#ptb_currency_type_html_"+id).show();
        $("#ptb_unit_html_"+id).show();
        $("#ptb_unit_type_html_"+id).show();


        $("#edit_save_"+id).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        $("#edit_save_"+id).addClass('edit_product_tagged_vendor');
        $("#edit_save_"+id).removeClass('save_product_tagged_vendor');

        $(this).hide();

    });

    $("body").on("click",".edit_product_tagged_vendor",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr("data-id");
        // $("#price_div_"+id).html($("#ptb_price_input_html_"+id).html());
        // $("#currency_div_"+id).html($("#ptb_currency_type_input_html_"+id).html());
        // $("#unit_div_"+id).html($("#ptb_unit_input_html_"+id).html());
        // $("#unit_type_div_"+id).html($("#ptb_unit_type_input_html_"+id).html());

        $("#ptb_price_input_html_"+id).show()
        $("#ptb_currency_type_input_html_"+id).show()
        $("#ptb_unit_input_html_"+id).show()
        $("#ptb_unit_type_input_html_"+id).show()

        $("#ptb_price_html_"+id).hide();
        $("#ptb_currency_type_html_"+id).hide();
        $("#ptb_unit_html_"+id).hide();
        $("#ptb_unit_type_html_"+id).hide();

        $(this).html('<i class="fa fa-save" aria-hidden="true"></i>');
        $(this).removeClass('edit_product_tagged_vendor');
        $(this).addClass('save_product_tagged_vendor');

        $("#close_btn_"+id).show();
    });

    $("body").on("click",".save_product_tagged_vendor",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr("data-id");
        var ptb_price_obj=$("#ptb_price_"+id);
        var ptb_currency_type_obj=$("#ptb_currency_type_"+id);
        var ptb_unit_obj=$("#ptb_unit_"+id);
        var ptb_unit_type_obj=$("#ptb_unit_type_"+id);

        if(ptb_price_obj.val()=='')
        {
          ptb_price_obj.addClass('error_input');
          ptb_price_obj.focus();
          return false;
        }
        else
        {

          var float= /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
          var a = ptb_price_obj.val();
          if (float.test(a)) 
          {
            // do something
            ptb_price_obj.removeClass('error_input');
          }
          else 
          {
            ptb_price_obj.addClass('error_input');
            ptb_price_obj.focus();
            return false;
          }         
        }

        if(ptb_currency_type_obj.val()=='')
        {
          ptb_currency_type_obj.addClass('error_input');
          ptb_currency_type_obj.focus();
          return false;
        }
        else
        {
          ptb_currency_type_obj.removeClass('error_input');
        }

        if(ptb_unit_obj.val()=='')
        {
          ptb_unit_obj.addClass('error_input');
          ptb_unit_obj.focus();
          return false;
        }
        else
        {
          
          ptb_unit_obj.removeClass('error_input');
          
          
        }

        if(ptb_unit_type_obj.val()=='')
        {
          ptb_unit_type_obj.addClass('error_input');
          ptb_unit_type_obj.focus();
          return false;
        }
        else
        {
          ptb_unit_type_obj.removeClass('error_input');
        }

        var data="id="+id+"&ptb_price="+ptb_price_obj.val()+"&ptb_currency_type="+ptb_currency_type_obj.val()+"&ptb_unit="+ptb_unit_obj.val()+"&ptb_unit_type="+ptb_unit_type_obj.val();
       
        $.ajax({
            url: base_url+"product/update_product_tagged_vendor",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
            beforeSend: function( xhr ) {
                
              },
            success:function(res){ 
               result = $.parseJSON(res);

               swal({
                    title: "Updated!",
                    text: "The record(s) updated successfully",
                     type: "success",
                    confirmButtonText: "ok",
                    allowOutsideClick: "false"
                }, function () { load(1); });
            },
            complete: function(){            
            },
             error: function(response) {
              //alert('Error'+response.table);
            }
        });
        
    });

    $("body").on("click","#open_vendor_tagged_modal",function(e){
        $('#vendor_tagged_product_add_modal').modal({backdrop: 'static',keyboard: false});
    });

    $(document).on('click', '#vendor_add_submit', function (e) {
      e.preventDefault();

      var base_url = $("#base_url").val();

      var v_company_name_obj=$("#v_company_name");
      var v_contact_person_obj=$("#v_contact_person");
      var v_designation_obj=$("#v_designation");
      var v_mobile_obj=$("#v_mobile");
      var v_email_obj=$("#v_email");
      var v_address_obj=$("#v_address");

      var v_price_obj=$("#v_price");
      var v_currency_type_obj=$("#v_currency_type");
      var v_unit_obj=$("#v_unit");
      var v_unit_type_obj=$("#v_unit_type");

      if(v_company_name_obj.val()=='')
      {
        v_company_name_obj.addClass('error_input');
        $("#v_company_name_error").html('Please enter company name');
        v_company_name_obj.focus();
        return false;
      }
      else
      {
        v_company_name_obj.removeClass('error_input');
        $("#v_company_name_error").html('');
      }

      if(v_contact_person_obj.val()=='')
      {
        v_contact_person_obj.addClass('error_input');
        $("#v_contact_person_error").html('Please enter contact person');
        v_contact_person_obj.focus();
        return false;
      }
      else
      {
        v_contact_person_obj.removeClass('error_input');
        $("#v_contact_person_error").html('');
      }

      if(v_designation_obj.val()=='')
      {
        v_designation_obj.addClass('error_input');
        $("#v_designation_error").html('Please enter designation');
        v_designation_obj.focus();
        return false;
      }
      else
      {
        v_designation_obj.removeClass('error_input');
        $("#v_designation_error").html('');
      }

      if(v_mobile_obj.val()=='')
      {
        v_mobile_obj.addClass('error_input');
        $("#v_mobile_error").html('Please enter mobile');
        v_mobile_obj.focus();
        return false;
      }
      else
      {
        v_mobile_obj.removeClass('error_input');
        $("#v_mobile_error").html('');
      }

      if(v_email_obj.val()=='')
      {
        v_email_obj.addClass('error_input');
        $("#v_email_error").html('Please enter email');
        v_email_obj.focus();
        return false;
      }
      else
      {
        if(is_email_validate(v_email_obj.val())==false)
        {
            v_email_obj.addClass('error_input');
            $("#v_email_error").html("Please enter valid email.");
            v_email_obj.focus();
            return false;
        }
        else
        {
            v_email_obj.removeClass('error_input');
            $("#v_email_error").html('');
        }
        
      }

      if(v_address_obj.val()=='')
      {
        v_address_obj.addClass('error_input');
        $("#v_address_error").html('Please enter address');
        v_address_obj.focus();
        return false;
      }
      else
      {
        v_address_obj.removeClass('error_input');
        $("#v_address_error").html('');
      }

      if(v_price_obj.val()=='')
      {
        v_price_obj.addClass('error_input');
        $("#v_price_error").html('Please enter price');
        v_price_obj.focus();
        return false;
      }
      else
      {
        v_price_obj.removeClass('error_input');
        $("#v_price_error").html('');
      }

      if(v_currency_type_obj.val()=='')
      {
        v_currency_type_obj.addClass('error_input');
        $("#v_currency_type_error").html('Please select currency');
        v_currency_type_obj.focus();
        return false;
      }
      else
      {
        v_currency_type_obj.removeClass('error_input');
        $("#v_currency_type_error").html('');
      }

      if(v_unit_obj.val()=='')
      {
        v_unit_obj.addClass('error_input');
        $("#v_unit_error").html('Please enter unit');
        v_unit_obj.focus();
        return false;
      }
      else
      {
        v_unit_obj.removeClass('error_input');
        $("#v_unit_error").html('');
      }

      if(v_unit_type_obj.val()=='')
      {
        v_unit_type_obj.addClass('error_input');
        $("#v_unit_type_error").html('Please select unit type');
        v_unit_type_obj.focus();
        return false;
      }
      else
      {
        v_unit_type_obj.removeClass('error_input');
        $("#v_unit_type_error").html('');
      }


      $.ajax({
        url: base_url+"product/add_vendor_ajax",
        data: new FormData($('#frmVendorAdd')[0]),
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
          if(result.status=='success')
          {
              swal({
                    title: "Success!",
                    text: "A new vendor successfully added and tagged with the product.",
                     type: "success",
                    confirmButtonText: "ok",
                    allowOutsideClick: "false"
                }, function () { 
                  $('#vendor_tagged_product_add_modal').modal('toggle');
                  load(1); });
          }   
        }
      });      
    });
    
});

// AJAX LOAD START
function load(page) 
{
    //var page_num=page;
    var base_url=$("#base_url").val(); 
    var search_existing_product_id   = $("#existing_product_id").val(); 
    var data="page="+page+"&search_existing_product_id="+search_existing_product_id;
    
    $.ajax({
        url: base_url+"product/get_product_wise_vendor_list_ajax/"+page,
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {
            
          },
        success:function(res){ 
           result = $.parseJSON(res);
           $("#tcontent").html(result.table);
           $("#page").html(result.page);
        },
        complete: function(){            
        },
         error: function(response) {
          //alert('Error'+response.table);
        }
    });
}
// AJAX LOAD END