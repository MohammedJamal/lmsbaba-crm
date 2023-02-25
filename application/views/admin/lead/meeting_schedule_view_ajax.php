<a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
    <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
        <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle"
            d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z"
            transform="translate(-3.375 -3.375)"></path>
    </svg>
</a>
<?php //print_r($meeting_info); ?>

<form name="meetingFrm sp-meet" id="meetingFrm">
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>" />
    <input type="hidden" name="c_id" id="c_id" value="<?php echo $c_id; ?>" />
    <!-- <input type="hidden" name="selected_meeting_date" id="selected_meeting_date" value="<?php if(count($meeting_info)){echo ($meeting_info['meeting_schedule_start_datetime'])?date("Y-m-d",strtotime($meeting_info['meeting_schedule_start_datetime'])):'';} ?>" /> -->
    <input type="hidden" name="meeting_venue_latitude" id="meeting_venue_latitude" value="" />
    <input type="hidden" name="meeting_venue_longitude" id="meeting_venue_longitude" value="" />
    <!-- strat -->
    <div class="holder-calendar">

        <div class="alert alert-danger hide" role="alert" id="meeting_error_msg_div"></div>
        <div class="alert alert-success hide" role="alert" id="meeting_success_msg_div"></div>

        <div class="wrapper-calendar--">            
            <div class="appointmentDates--">
                <div class="form-group row">
                    <div class="col-md-12">
                        <h3 class="meeting-title"><strong>Company:</strong> <?php echo ($cus_data->company_name)?$cus_data->company_name:'N/A'; ?></h3> 
                    </div>
                </div>
                
                 
                <!-- form loop start -->
                <div class="form-group row">
                    <div class="col-md-12">
                        <!-- <label>Meeting Type</label> -->
                        <div class="meeting_type row">
                            <div class="col-md-4">
                                <label class="switchCall">
                                <input type="radio" name="meeting_type" id="meeting_type_v" value="P" <?php if(count($meeting_info)){echo ($meeting_info['meeting_type']=='P')?'checked':'';}else{echo 'checked';} ?>>
                                <span class="slider">Visit</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="switchCall">
                                <input type="radio" name="meeting_type" id="meeting_type_o" value="O" <?php if(count($meeting_info)){echo ($meeting_info['meeting_type']=='O')?'checked':'';} ?>>
                                <span class="slider">Online</span>
                                </label>
                            </div>                            
                        </div>
                    </div>  
                </div>
                <!-- form loop end -->
                
                <!-- form loop start -->
                <div class="form-group row">
                    <div class="col-md-4">
                        <label>Meeting Date</label>
                        <div class="timeSelect w-100">
                            <input type="text" name="selected_meeting_date" id="selected_meeting_date" class="calendar_input" placeholder="Enter Date" value="<?php if(count($meeting_info)){echo ($meeting_info['meeting_schedule_start_datetime'])?date_db_format_to_display_format($meeting_info['meeting_schedule_start_datetime']):'';} ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Start Time</label>
                        <div class="timeSelect w-100"> 
                            <input type="time" name="meeting_schedule_start_time" id="meeting_schedule_start_time" class="calendar_input" placeholder="_ _:_ _ : AM" onfocus="this.showPicker()" value="<?php if(count($meeting_info)){echo ($meeting_info['meeting_schedule_start_datetime'])?date("H:i:s", strtotime($meeting_info['meeting_schedule_start_datetime'])):'';} ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>End Time</label>
                        <div class="timeSelect w-100">
                            <input type="time" name="meeting_schedule_end_time" id="meeting_schedule_end_time" class="calendar_input" placeholder="_ _:_ _ : AM" onfocus="this.showPicker()" value="<?php if(count($meeting_info)){echo ($meeting_info['meeting_schedule_end_datetime'])?date("H:i:s", strtotime($meeting_info['meeting_schedule_end_datetime'])):'';} ?>" />
                        </div>
                    </div>
                </div>
                <!-- form loop end -->
                <!-- form loop start -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Meeting Assigned To</label>
                        <div class="w-100">
                            <select class="default-select" id="meeting_assigned_user_id" name="meeting_assigned_user_id">
                                <option value="">--Select--</option>
                                <?php if(count($user_list)){ ?>
                                    <?php foreach($user_list AS $user){ ?>
                                    <option value="<?php echo $user['id']; ?>" <?php if(count($meeting_info)){if($meeting_info['user_id']==$user['id']){echo'SELECTED';}} ?>><?php echo $user['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>                            
                        </div>
                    </div> 
                    
                    <div class="col-md-6">
                        <?php $meeting_with_before_checkin_time_arr=explode(",",$meeting_info['meeting_with_before_checkin_time']);?> 
                        <label>Meeting With &nbsp;<a href="JavaScript:void(0);" class="" id="add_more_contact_persion_view" data-leadid="<?php echo $lead_id; ?>" data-cid="<?php echo $c_id; ?>"><i class="fa fa-plus-square" aria-hidden="true"></i></a></label>
                        <div class="w-100">
                            <select class="default-select" id="meeting_with_before_checkin_time" name="meeting_with_before_checkin_time[]" multiple>
                                <?php if($cus_data->contact_person){ ?>
                                <option value="<?php echo $cus_data->contact_person.'#'.$cus_data->email; ?>" <?php if(count($meeting_info)){if(in_array($cus_data->contact_person,$meeting_with_before_checkin_time_arr)){echo'SELECTED';}} ?> ><?php echo $cus_data->contact_person; ?></option>
                                <?php } ?>
                                <?php if(count($contact_persion_list)){ ?>
                                    <?php foreach($contact_persion_list AS $contact_persion){ ?>
                                    <option value="<?php echo $contact_persion['name'].'#'.$contact_persion['email']; ?>"  <?php if(count($meeting_info)){if(in_array($contact_persion['name'],$meeting_with_before_checkin_time_arr)){echo'SELECTED';}} ?>><?php echo $contact_persion['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>                            
                        </div>
                    </div>
                    
                </div>
                <!-- form loop end -->
                <!-- form loop start -->
                <div class="form-group row">
                    <div class="col-md-12">
                        <label>Purpose </label>
                        <div class="w-100">
                            <select class="default-select" id="meeting_agenda_type_id" name="meeting_agenda_type_id">
                                <?php if(count($meeting_agenda_type_list)){ ?>
                                    <?php foreach($meeting_agenda_type_list AS $mat){ ?>
                                    <option value="<?php echo $mat->id; ?>" <?php if(count($meeting_info)){if($meeting_info['meeting_agenda_type_id']==$mat->id){echo'SELECTED';}} ?>><?php echo $mat->name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                </div>
                <!-- form loop end -->
                <!-- form loop start -->
                <div class="form-group row meeting_platform hide">
                    <div class="form-group hide">
                        <div class="col-md-12 hide">
                           
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6">                            
                            <input type="hidden" name="online_meeting_platform" value="" />
                        </div> 
                    </div>
                    <div class="form-group mb-0  online-meeting-url">
                        <div class="col-md-12">
                            <label>Online Meeting URL</label>
                            <div class="w-100">
                                <input type="text" name="online_meeting_meeting_url" id="online_meeting_meeting_url" class="calendar_input" value="<?php if(count($meeting_info)){echo ($meeting_info['meeting_url'])?$meeting_info['meeting_url']:'';} ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- form loop end -->  
                                  
                <!-- form loop start -->
                <div class="form-group row meeting_type_p">
                    <div class="col-md-12 ">
                        <label>Venue</label>
                        <div class="w-100">
                            <?php
                            $city_state_country_str='';
                            if($cus_data->city_name){
                                $city_state_country_str .=', '.$cus_data->city_name;
                            }
                            if($cus_data->state_name){
                                $city_state_country_str .=', '.$cus_data->state_name;
                            }
                            if($cus_data->country_name){
                                $city_state_country_str .=', '.$cus_data->country_name;
                            }
                            ?>
                            <input type="text" name="meeting_venue" id="meeting_venue" class="calendar_input" value="<?php if(count($meeting_info)){echo ($meeting_info['meeting_venue'])?$meeting_info['meeting_venue']:'';}else{echo ($cus_data->address)?$cus_data->address.$city_state_country_str:'';} ?>" autocomplete="off">
                        </div>
                        
                    </div>  
                </div>
                <!-- form loop end -->
                <!-- form loop start -->
                <div class="form-group row">
                    <div class="col-md-12">
                        <label>Remarks</label>
                        <div class="w-100">
                            <input type="text" name="meeting_Purpose" id="meeting_Purpose" class="calendar_input" value="<?php if(count($meeting_info)){echo ($meeting_info['meeting_Purpose'])?$meeting_info['meeting_Purpose']:'';} ?>" autocomplete="off">
                        </div>
                    </div>  
                </div>
                <!-- form loop end --> 
            </div>

        </div>


        <div id="othersAppointmentDates" class="mt-10">
            
        </div>

        <div id="finalAppointmentDates" class="mt-10">
            <svg width='18' height='18' xmlns='http://www.w3.org/2000/svg'>
                <g fill='#FFF'>
                <path
                    d='M18 5.625v11.28c0 .604-.504 1.095-1.125 1.095H1.125C.504 18 0 17.51 0 16.904V5.625h18zM4.732 12.844H2.68l-.101.009a.563.563 0 00-.426.357l-.026.095-.01.101v1.969l.01.101a.563.563 0 00.357.426l.095.026.101.01h2.052l.1-.01a.563.563 0 00.427-.357l.026-.095.01-.101v-1.969l-.01-.1a.564.564 0 00-.357-.427l-.095-.026-.101-.01zm5.294 0H7.974l-.1.009a.563.563 0 00-.427.357l-.026.095-.01.101v1.969l.01.101a.563.563 0 00.357.426l.095.026.101.01h2.052l.1-.01a.563.563 0 00.427-.357l.026-.095.01-.101v-1.969l-.01-.1a.564.564 0 00-.357-.427l-.095-.026-.101-.01zM4.732 7.687H2.68l-.101.01a.563.563 0 00-.426.357l-.026.095-.01.101v1.969l.01.1a.563.563 0 00.357.427l.095.026.101.01h2.052l.1-.01a.563.563 0 00.427-.357l.026-.095.01-.101V8.25l-.01-.101a.563.563 0 00-.357-.426l-.095-.026-.101-.01zm5.294 0H7.974l-.1.01a.563.563 0 00-.427.357l-.026.095-.01.101v1.969l.01.1a.563.563 0 00.357.427l.095.026.101.01h2.052l.1-.01a.563.563 0 00.427-.357l.026-.095.01-.101V8.25l-.01-.101a.563.563 0 00-.357-.426l-.095-.026-.101-.01zm5.294 0h-2.052l-.1.01a.563.563 0 00-.427.357l-.026.095-.01.101v1.969l.01.1a.563.563 0 00.357.427l.095.026.101.01h2.052l.1-.01a.563.563 0 00.427-.357l.026-.095.01-.101V8.25l-.01-.101a.563.563 0 00-.357-.426l-.095-.026-.101-.01zM14.625 1.266h2.25C17.496 1.266 18 1.8 18 2.46V4.5H0V2.46c0-.66.504-1.194 1.125-1.194h2.25v-.07C3.375.535 3.879 0 4.5 0s1.125.535 1.125 1.195v.07h6.75v-.07C12.375.535 12.879 0 13.5 0s1.125.535 1.125 1.195v.07z' />
                </g>
            </svg>
            <span class="finalResult">
                Please select date, time and meeting type to continue.
            </span>
        </div> 
    </div>
    <!-- end -->
    <div class="mail-form-row border-top-grey">
        <label class="mb-0">
            <input type="checkbox" name="sent_invitation" id="sent_invitation" value="Y"> Send Invite on Email
        </label>
        <a href="JavaScript:void(0)" class="lead-btn orange pull-right update-lead" id="meeting_schedule_submit_confirm">Save</a>
    </div>
</form>

<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/calendar.css">
<link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>

<script>
    $('#meeting_with_before_checkin_time').multiselect({
        nonSelectedText: '---Select---',
        buttonClass:'custom-multiselect',
        includeSelectAllOption:true
    });

    
    $(document).ready(function(){ 
        var selectedDate = {day: 'none', month: 'none', year: 'none', time: 'none', type: 'Call'};
        // $("body").on("click","#meeting_schedule_submit_confirm",function(e){  
        //     var id=$("#id").val();
        //     var lead_id=$("#lead_id").val();
        //     var c_id=$("#c_id").val();
        //     var base_url=$("#base_url").val();
        //     var meeting_with_before_checkin_time_arr=[];        
        //     $('#meeting_with_before_checkin_time option:selected').each(function() {
        //         meeting_with_before_checkin_time_arr.push($(this).val());            
        //     });
        //     $('#meetingFrm').append('<input type="hidden" id="meeting_with_before_checkin_time_selected" name="meeting_with_before_checkin_time_selected" value="'+meeting_with_before_checkin_time_arr+'" />');
        //     $.ajax({
        //         url: base_url+"lead/meeting_add_edit_ajax",
        //         data: new FormData($('#meetingFrm')[0]),
        //         cache: false,
        //         method: 'POST',
        //         dataType: "html",
        //         mimeType: "multipart/form-data",
        //         contentType: false,
        //         cache: false,
        //         processData:false,
        //         beforeSend: function(xhr) {            
        //         $('#scheduleMeetingModal .modal-body').addClass('logo-loader');
        //         },
        //         complete: function(){              
        //             $('#scheduleMeetingModal .modal-body').removeClass('logo-loader');
        //         },
        //         success: function(data){
        //         result = $.parseJSON(data);
        //         if(result.status=='success')
        //         {                     
        //             swal({
        //             title: 'Success',
        //             text: result.msg,
        //             type: 'success',
        //             showCancelButton: false,
        //             confirmButtonColor: '#DD6B55',
        //             confirmButtonText: '',
        //             closeOnConfirm: true
        //             }, function() {
                        
        //                 $('#scheduleMeetingModal').modal('hide');
                        
        //                 if(id){ 
        //                     // rander_calendar_view();
        //                     if($('input[name="sent_invitation"]').is(':checked')){   
        //                         $("#MeetingDetailEditModal").css("display",'none');                                    
        //                         common_mail_send_modal('',result.txt,result.mail_subject,result.mail_form,result.mail_to);                    
        //                     } 
        //                     else{ 
        //                         open_meeting_detail_popup(id);
        //                     }
        //                 }
        //                 else{
        //                     if($('input[name="sent_invitation"]').is(':checked')){          
        //                         // alert(result.txt+'/'+result.mail_subject+'/'+result.mail_form+'/'+result.mail_to) ; 
        //                         common_mail_send_modal('',result.txt,result.mail_subject,result.mail_form,result.mail_to); 

        //                     } 
        //                 }
                        
                                                       
        //             });  
                            
        //         }   
        //         else if(result.status=='error')
        //         {
        //             swal('Oops!',result.msg,'error')
                    
        //         }
        //         }
        //     });
        // });
        $('#ReplyPopupModal').on('hide.bs.modal', function (event) {
            $('#MeetingDetailEditModal').css('display','block');
        });

        if($("input[name='meeting_type']:checked").val()){
        var getVal = $("input[name='meeting_type']:checked").val()
        if(getVal == 'O'){
            $('.meeting_type_p').addClass('hide');    
            $('.meeting_platform').removeClass('hide');
        }else{
            $('.meeting_type_p').removeClass('hide');
            $('.meeting_platform').addClass('hide');
        }
        }
        $('input[type=radio][name=meeting_type]').on('change', function() {
            var getVal = $(this).val(); 
            selectedDate.type = getVal;  
            if(getVal == 'O'){
                $('.meeting_type_p').addClass('hide');    
                $('.meeting_platform').removeClass('hide');
            }else{
                $('.meeting_type_p').removeClass('hide');
                $('.meeting_platform').addClass('hide');
            }
            
        });

        // $(".time_element").timepicki({
        //     step_size_minutes:5,
        //     on_change : function(ct){
        //         var getval = $(ct).val();
        //         selectedDate.time = getval;
        //     }
        // });

        $('#selected_meeting_date').datepicker({
            dateFormat: "dd-M-yy",
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+5'
        });
        
    });   
</script>

