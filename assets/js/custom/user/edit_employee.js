$(document).ready(function(){  
    // Select all elements with data-toggle="tooltips" in the document
    $('[data-toggle="tooltip"]').tooltip({html: true}); 

    
    //rander_department_dropdown_html();
    if($("#existing_department_id").val()){
        rander_manager_dropdown_html($("#existing_department_id").val());
    }
    rander_designation_dropdown_html();
    rander_functional_area_dropdown_html();

    // ------------------------------------------
    // DEPARTMENT START
    $("body").on("click",".add_department_ajax",function(e){
        //var form_action_url=$(this).attr('data-formaction');
        //$("#add_form").attr('action',form_action_url);
        //alert($(this).attr('data-pid')); return false;
        var pid=$(this).attr('data-pid');        
        var l=$(this).attr('data-level');
        $("#existing_pid").val(pid);
        $("#existing_l").val(l);
        $("#title_text").html('Add Department');  

        $("#FormOpenModal").modal({
            backdrop: 'static',
            keyboard: false,
            callback:fn_rander_department_add_view(pid)
        });
    });
   
    $("body").on("click","#add_department_submit",function(e){

        e.preventDefault();

        var base_url=$("#base_url").val();
        var department_name_obj=$("#category_name");
        var parent_department_id_obj=$("#category_id");
        if(department_name_obj.val()=='')
        {
            $("#department_name_error").html("Please enter department name.");
            department_name_obj.focus();
            return false;
        }        
        
        // Ajax Call
        var actionUrlPath = base_url + '/user/' + 'add_department_ajax';
        $.ajax({
            url: actionUrlPath,
            data: $("#add_form").serializeArray(),
            cache: false,
            method: 'POST',
            dataType: "html",                        
            beforeSend: function() {
                // setting a timeout
                //$("#preloader").css('display','block');
                $("#msg").removeClass('text-success');
                $("#msg").removeClass('text-danger');
            },
            success: function(res)
            {
                result = $.parseJSON(res);
                //console.log(result.return);return false;
                if(result.status=='success')
                {
                    var tmp_pid=parseInt($("#existing_pid").val())
                    var tmp_level=parseInt($("#existing_l").val());
                    var existing_level=parseInt($("#total_level").val());
                    //alert(tmp_pid+'/'+tmp_level+'/'+existing_level);
                    get_chield(tmp_pid,tmp_level,existing_level);
                    //rander_department_dropdown_html();
                    swal({
                        title: "Success",
                        text: result.msg,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: 'btn-warning',
                        confirmButtonText: "Ok!",
                        closeOnConfirm: false
                    });
                }
                else if (result.status=='fail')
                {
                    swal({
                            title: "Error!",
                            text: result.msg,
                            type: "error",
                            showCancelButton: false,
                            confirmButtonClass: 'btn-warning',
                            confirmButtonText: "Ok!",
                            closeOnConfirm: false
                        });
                }
                else
                {
                    swal('Oops! Unknown Error.');
                }


                $('#FormOpenModal').modal('hide');
            },
            complete: function(){
                //$("#preloader").css('display','none');
            }
        });

        
    });
    // DEPARTMENT END
    // --------------------------------------------


    // --------------------------------------------
    // DESIGNATION START
    $("body").on("click","#add_designation_ajax",function(e){
        $("#title_text").html('Add Designation');
        $("#FormOpenModal").modal({
            backdrop: 'static',
            keyboard: false,
            callback:fn_rander_designation_add_view()
        });
    });

    $("body").on("click","#add_designation_submit",function(e){

        e.preventDefault();
        var base_url=$("#base_url").val();
        var designation_name_obj=$("#designation_name");
        if(designation_name_obj.val()=='')
        {
            $("#designation_name_error").html("Please enter name.");
            designation_name_obj.focus();
            return false;
        }

        // Ajax Call
        var actionUrlPath = base_url + '/user/' + 'add_designation_ajax';
        
        $.ajax({
            url: actionUrlPath,
            data: $("#add_form").serializeArray(),
            cache: false,
            method: 'POST',
            dataType: "html",                        
            beforeSend: function() {
                // setting a timeout
                //$("#preloader").css('display','block');
                $("#msg").removeClass('text-success');
                $("#msg").removeClass('text-danger');
            },
            success: function(res)
            {
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                    rander_designation_dropdown_html();
                    swal({
                        title: "Success",
                        text: result.msg,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: 'btn-warning',
                        confirmButtonText: "Ok!",
                        closeOnConfirm: false
                    });
                }
                else if (result.status=='fail')
                {
                    swal({
                            title: "Error!",
                            text: result.msg,
                            type: "error",
                            showCancelButton: false,
                            confirmButtonClass: 'btn-warning',
                            confirmButtonText: "Ok!",
                            closeOnConfirm: false
                        });
                }
                else
                {
                    swal('Oops! Unknown Error.');
                }
                $('#FormOpenModal').modal('hide');
            },
            complete: function(){
                //$("#preloader").css('display','none');
            }
        });

        
    });
    // DESIGNATION END
    // ---------------------------------------------

    // --------------------------------------------
    // FUNCTIONAL AREA START
    $("body").on("click","#add_functional_area_ajax",function(e){
        $("#title_text").html('Add Functional Area');
        $("#FormOpenModal").modal({
            backdrop: 'static',
            keyboard: false,
            callback:fn_rander_functional_area_add_view()
        });
    });

    $("body").on("click","#add_functional_area_submit",function(e){

        e.preventDefault();
        var base_url=$("#base_url").val();
        var functional_area_name_obj=$("#functional_area_name");
        if(functional_area_name_obj.val()=='')
        {
            $("#functional_area_name_error").html("Please enter name.");
            functional_area_name_obj.focus();
            return false;
        }

        // Ajax Call
        var actionUrlPath = base_url + '/user/' + 'add_functional_area_ajax';
        
        $.ajax({
            url: actionUrlPath,
            data: $("#add_form").serializeArray(),
            cache: false,
            method: 'POST',
            dataType: "html",                        
            beforeSend: function() {
                // setting a timeout
                //$("#preloader").css('display','block');
                $("#msg").removeClass('text-success');
                $("#msg").removeClass('text-danger');
            },
            success: function(res)
            {
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                    rander_functional_area_dropdown_html();
                    swal({
                        title: "Success",
                        text: result.msg,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: 'btn-warning',
                        confirmButtonText: "Ok!",
                        closeOnConfirm: false
                    });
                }
                else if (result.status=='fail')
                {
                    swal({
                            title: "Error!",
                            text: result.msg,
                            type: "error",
                            showCancelButton: false,
                            confirmButtonClass: 'btn-warning',
                            confirmButtonText: "Ok!",
                            closeOnConfirm: false
                        });
                }
                else
                {
                    swal('Oops! Unknown Error.');
                }
                $('#FormOpenModal').modal('hide');
            },
            complete: function(){
                //$("#preloader").css('display','none');
            }
        });

        
    });
    // FUNCTIONAL AREA END
    // ---------------------------------------------

    var fncallcount=0;
    $("body").on("change",".get_chield",function(e){
        fncallcount++;
        var base_url=$("#base_url").val();
        var parent_id=$(this).val(); 
        //$(".set_name").attr('name','');  
        $(this).attr('name','department_id[]');         
        var actionUrlPath = base_url + '/user/' + 'ajax_load_child_department_dropdown_html';
        var level=parseInt($(this).attr('data-level'));
        var existing_level=parseInt($("#total_level").val());
        var emp_id=$("#emp_id").val();
        
        if(parent_id!='')
        {
            var post_param="parent_id="+parent_id+"&level="+level+"&emp_id="+emp_id;
            $.ajax({
                url: actionUrlPath,
                type: "POST",
                data: post_param,
                dataType: "HTML",
                beforeSend: function() {
                    //$("#preloader").css('display','block');
                },
                success: function(data)
                { 
                    if(level==0)
                    {
                       $(".child").html(''); 
                       $("#total_level").val(0);
                    }

                    $(".outer_"+level).remove();
                    $("#total_level").val(level);

                    if(data){
                        //$(".set_name").attr('name','');
                        $('#child_department_div').append('<div class="outer_'+level+' child">'+data+'</div>');
                    }

                    
                    //$("#select_rander_"+parent_id).attr('name','department_id');
                    
                    
                    if(existing_level>(level+1) && level!=0)
                    {
                        $(".child").html('');
                        $("#parent_id").prop('selectedIndex',0);
                        $("#total_level").val(0);
                    }
                    rander_manager_dropdown_html(parent_id);
                },
                complete: function(){
                    //$("#preloader").css('display','none');
                } 
            });
        }
        
    });


    // --------------------------------------------------
    // Valication Check

    // Marital Status
    $("body").on("change","#marital_status",function(e){
        var m_status=$(this).val();
        if(m_status=='married')
        {
            $("#marriage_anniversary").attr('disabled',false);
            $("#spouse_name").attr('readonly',false);            
        }
        else
        {
            $("#marriage_anniversary").attr('disabled',true);
            $("#marriage_anniversary").val('');
            $("#spouse_name").attr('readonly',true);
            $("#spouse_name").val('');
        }
    });   

    // $("body").on("input","#personal_email",function(e){
    //     var base_url=$("#base_url").val();
    //     var personal_email = $(this).val();
    //     // Ajax Call
    //     var actionUrlPath = base_url + '/user/' + 'personal_email_duplicate_check';        
    //     $.ajax({
    //         url: actionUrlPath,
    //         data: "personal_email="+personal_email,
    //         cache: false,
    //         method: 'POST',
    //         dataType: "html",                        
    //         beforeSend: function() {
    //             // setting a timeout
    //             //$("#preloader").css('display','block');
    //         },
    //         success: function(res)
    //         {
    //             result = $.parseJSON(res);
    //             if(result.status==1)
    //             {
    //                 $("#personal_email_error").html(result.msg);
    //             }
    //             else
    //             {
    //                 $("#personal_email_error").html('');
    //             }
    //         },
    //         complete: function(){
    //             //$("#preloader").css('display','none');
    //         }
    //     });

    // });

    // $("body").on("input","#personal_mobile",function(e){
    //     var base_url=$("#base_url").val();
    //     var personal_mobile = $(this).val();
    //     // Ajax Call
    //     var actionUrlPath = base_url + '/user/' + 'personal_mobile_duplicate_check';        
    //     $.ajax({
    //         url: actionUrlPath,
    //         data: "personal_mobile="+personal_mobile,
    //         cache: false,
    //         method: 'POST',
    //         dataType: "html",                        
    //         beforeSend: function() {
    //             // setting a timeout
    //             //$("#preloader").css('display','block');
    //         },
    //         success: function(res)
    //         {
    //             result = $.parseJSON(res);
    //             if(result.status==1)
    //             {
    //                 $("#personal_mobile_error").html(result.msg);
    //             }
    //             else
    //             {
    //                 $("#personal_mobile_error").html('');
    //             }
    //         },
    //         complete: function(){
    //             //$("#preloader").css('display','none');
    //         }
    //     });

    // });
    // Validation Check
    // -----------------------------------------------------

    $("body").on("click",".image_delete",function(e){
        
        var base_url=$("#base_url").val();
        var base_url_root=$("#base_url_root").val();
        var id=$(this).attr("data-id");
        var display_div=$(this).attr("data-display");
        var r=confirm("Dou you want to delete the image?");

        if(r==true)
        {
            // Ajax Call
            var actionUrlPath = base_url + '/user/' + 'delete_profile_pic';        
            $.ajax({
                url: actionUrlPath,
                data: "id="+id,
                cache: false,
                method: 'POST',
                dataType: "html",                        
                beforeSend: function() {
                    // setting a timeout
                    //$("#preloader").css('display','block');
                },
                success: function(res)
                {
                    result = $.parseJSON(res);                
                    if(result.status==0)
                    {
                        $("#delete_div").html('');
                        $("#"+display_div).html('<img src="'+base_url_root+'images/user_img_icon.png"/>');
                        alert(result.msg);
                    }
                    else
                    {
                       
                       alert(result.msg);
                    }
                },
                complete: function(){
                    //$("#preloader").css('display','none');
                }
            });  
        }
        
    });  

    $("body").on("click","#change_department",function(e){
        $("#department_outer_div").show();
        $("#is_department_change").val('Y');
    }) ;
});



function fn_rander_department_add_view(pid='')
{
    var base_url=$("#base_url").val();
    var actionUrlPath = base_url + '/user/' + 'ajax_load_add_category';
    if(pid==0){
        var parent_text = $("#parent_id").find(":selected").text();
        var parent_id = $("#parent_id").find(":selected").val();
    }
    else{
        var parent_text = $("#select_rander_"+pid).find(":selected").text();
        var parent_id = $("#select_rander_"+pid).find(":selected").val();
    } 

    var parent_text_temp='';   
    if(parent_text.toLowerCase()!='select')
    {
        var parent_text_temp=parent_text;
    }
    else
    {
        parent_text_temp='';
    }
    //alert(actionUrlPath); return false;
    $.ajax({
        url: actionUrlPath,
        type: "POST",
        data: 'pid='+pid,
        dataType: "HTML",
        beforeSend: function() {
            //$("#preloader").css('display','block');
        },
        success: function(data)
        {
            $('#render_add_html_view').html(data);
            if(parent_text_temp){
               $("#is_same_level_check_view").html('<input type="checkbox" name="is_same_level" id="is_same_level" checked><input type="hidden" name="parent_id" id="parent_id" value="'+parent_id+'"> Add the department as sub-department of '+parent_text_temp); 
            }
            else{
                $("#is_same_level_check_view").html('<div style="display:none"><input type="checkbox" name="is_same_level" id="is_same_level" ><input type="hidden" name="parent_id" id="parent_id" value=""></div>');
            }            
            $("#parent_select_div_html").hide();
        },
        complete: function(){
            //$("#preloader").css('display','none');
        } 
    });
}
function fn_rander_designation_add_view()
{
    var base_url=$("#base_url").val();
    var actionUrlPath = base_url + '/user/' + 'ajax_load_add_designation';
    //alert(actionUrlPath); return false;
    $.ajax({
        url: actionUrlPath,
        type: "POST",
        data: '',
        dataType: "HTML",
        beforeSend: function() {
            //$("#preloader").css('display','block');
        },
        success: function(data)
        { 
            $('#render_add_html_view').html(data);
        },
        complete: function(){
            //$("#preloader").css('display','none');
        } 
    });
}
function fn_rander_functional_area_add_view()
{
    var base_url=$("#base_url").val();
    var actionUrlPath = base_url + '/user/' + 'ajax_load_add_functional_area';
    //alert(actionUrlPath); return false;
    $.ajax({
        url: actionUrlPath,
        type: "POST",
        data: '',
        dataType: "HTML",
        beforeSend: function() {
            //$("#preloader").css('display','block');
        },
        success: function(data)
        { 
            $('#render_add_html_view').html(data);
        },
        complete: function(){
            //$("#preloader").css('display','none');
        } 
    });
}



function rander_department_dropdown_html()
{
    var base_url=$("#base_url").val();
    var selected_value=$("#department_selected_value").val();
    var actionUrlPath = base_url + '/user/' + 'ajax_load_department_dropdown_html';
    $.ajax({
        url: actionUrlPath,
        type: "POST",
        data: "selected_value="+selected_value,
        dataType: "HTML",
        beforeSend: function() {
            //$("#preloader").css('display','block');
        },
        success: function(data)
        { 
            $('#department_dropdown_div').html(data);
        },
        complete: function(){
            //$("#preloader").css('display','none');
        } 
    });
}


function rander_manager_dropdown_html(department_id)
{
    var base_url=$("#base_url").val();
    var selected_value=$("#manager_selected_value").val();
    var emp_id=$("#emp_id").val();
    var actionUrlPath = base_url + '/user/' + 'ajax_load_manager_dropdown_html';
    $.ajax({
        url: actionUrlPath,
        type: "POST",
        data: "selected_value="+selected_value+"&department_id="+department_id+"&emp_id="+emp_id,
        dataType: "HTML",
        beforeSend: function() {
            //$("#preloader").css('display','block');
        },
        success: function(data)
        {
            $('#manager_dropdown_div').html(data);
        },
        complete: function(){
            //$("#preloader").css('display','none');
        } 
    });
}


function rander_designation_dropdown_html()
{
    var base_url=$("#base_url").val();
    var selected_value=$("#designation_selected_value").val();
    var actionUrlPath = base_url + '/user/' + 'ajax_load_designation_dropdown_html';
    $.ajax({
        url: actionUrlPath,
        type: "POST",
        data: "selected_value="+selected_value,
        dataType: "HTML",
        beforeSend: function() {
            //$("#preloader").css('display','block');
        },
        success: function(data)
        { 
            $('#designation_dropdown_div').html(data);
        },
        complete: function(){
            //$("#preloader").css('display','none');
        } 
    });
}

function rander_functional_area_dropdown_html()
{
    var base_url=$("#base_url").val();
    var selected_value=$("#functional_area_selected_value").val();
    var actionUrlPath = base_url + '/user/' + 'ajax_load_functional_area_dropdown_html';
    $.ajax({
        url: actionUrlPath,
        type: "POST",
        data: "selected_value="+selected_value,
        dataType: "HTML",
        beforeSend: function() {
            //$("#preloader").css('display','block');
        },
        success: function(data)
        { 
            $('#functional_area_dropdown_div').html(data);
        },
        complete: function(){
            //$("#preloader").css('display','none');
        } 
    });
}



function get_chield(parent_id,level,existing_level)
{  
        var base_url=$("#base_url").val();
        var actionUrlPath = base_url + '/user/' + 'ajax_reset_department_select_option';
        //var level=parseInt($(this).attr('data-level'));
        //var existing_level=parseInt($("#total_level").val());
        //alert(level);
        $.ajax({
            url: actionUrlPath,
            type: "POST",
            data: "parent_id="+parent_id+"&level="+level,
            dataType: "HTML",
            beforeSend: function() {
                //$("#preloader").css('display','block');
            },
            success: function(data)
            {    
                //alert(parent_id);      
                if(parent_id==0)
                {
                    $("#parent_id").html(data);
                } 
                else
                {
                    $("#select_rander_"+parent_id).html(data);
                }     
                //$(".child").html('');
                // $("#parent_id").prop('selectedIndex',0);
                // $("#total_level").val(0);          
                
            },
            complete: function(){
                //$("#preloader").css('display','none');
            } 
        });
}