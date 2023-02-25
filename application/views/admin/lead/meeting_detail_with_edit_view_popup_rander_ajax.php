<?php
$meeting_schedule_start_date = date("Y-m-d", strtotime($row['meeting_schedule_start_datetime']));
?>
<form id="MeetingFrm" name="MeetingFrm">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Meeting Details (Lead ID: #<?php echo $row['lead_id']; ?>)</h5>
            <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                    <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"></path>
                </svg>
            </a> 
        </div>
        <div class="modal-body">
            
            <?php //print_r($row); ?>               
            <div class="schedule-outer">
                <h2><?php echo ($row['cust_company_name'])?$row['cust_company_name']:$row['cust_contact_person']; ?></h2>
                <div class="row">
                    <?php if($status==1 || $status==5){ // Pending / Reshedule?>
                        <div class="col-md-12 mt-15">
                            <div class="form-group schedule-time">
                                <ul class="flex-ul">
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_start_datetime'])?date_db_format_to_display_format($row['meeting_schedule_start_datetime']):'-'; ?></div>
                                    </li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_start_datetime'])?time_db_format_to_display_format_ampm($row['meeting_schedule_start_datetime']):'-'; ?></div>
                                    </li>
                                    <li>To</li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_end_datetime'])?time_db_format_to_display_format_ampm($row['meeting_schedule_end_datetime']):'-'; ?></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group schedule-time">
                                <ul class="flex-ul">
                                    <li>
                                        <div class="m-type <?php echo ($row['meeting_type']=='P')?'visit':'online'; ?>">
                                            <?php echo ($row['meeting_type']=='P')?'Visit':'Online'; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="pending-txt">Status: <?php echo $row['status']; ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group schedule-time">                                
                                <ul class="map-ul">
                                    <li>
                                        <?php if($row['meeting_type']=='P'){ ?>
                                            <svg class="fw-icon" height="78px" id="Layer_1" style="enable-background:new 0 0 78 78;" version="1.1" viewBox="0 0 78 78" width="78px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M69,30.451c0-16.568-13.431-30-30-30c-16.568,0-30,13.432-30,30c0,7.086,2.464,13.596,6.572,18.729   c0.001,0.001,0.002,0.003,0.001,0.004s-0.002,0.002-0.004,0.002c7.746,9.674,18.693,23.347,22.259,27.8   c0.285,0.355,0.716,0.562,1.171,0.563c0.456,0,0.888-0.206,1.172-0.562c3.564-4.452,14.508-18.119,22.254-27.794   c0.001-0.001,0.002-0.003,0.001-0.004s-0.002-0.002-0.004-0.002C66.534,44.052,69,37.542,69,30.451z M57.567,45.66L39,68.851   L20.613,45.887l-0.188-0.234c-0.055-0.075-0.111-0.149-0.169-0.222C16.817,41.134,15,35.954,15,30.451c0-13.234,10.767-24,24-24   s24,10.766,24,24c0,5.506-1.819,10.688-5.262,14.986C57.68,45.51,57.623,45.584,57.567,45.66z" style="fill:#333F4F;"/><circle cx="39" cy="30.451" r="10.5" style="fill:#333F4F;"/></g></svg>
                                        <?php } else { ?>
                                            <svg  class="fw-icon" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg"><rect fill="none" height="256" width="256"/><path d="M122.3,71.4l19.8-19.8a44.1,44.1,0,0,1,62.3,62.3l-28.3,28.2a43.9,43.9,0,0,1-62.2,0" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M133.7,184.6l-19.8,19.8a44.1,44.1,0,0,1-62.3-62.3l28.3-28.2a43.9,43.9,0,0,1,62.2,0" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                        <?php } ?>                                        
                                    </li>
                                    <?php if($row['meeting_type']=='P'){ ?>
                                    <li>
                                        <div class="grey-bg-auto"><span><?php echo ($row['meeting_venue'])?$row['meeting_venue']:'-'; ?></span></div>
                                    </li>
                                    <?php } else { ?>                                    
                                    <li>
                                        <div class="grey-bg-auto"><span><a href="<?php echo ($row['meeting_url'])?$row['meeting_url']:'-'; ?>" target="__blank"><?php echo ($row['meeting_url'])?$row['meeting_url']:'-'; ?></a></span></div>
                                    </li>
                                    <?php } ?> 
                                </ul>
                            </div>
                            <div class="form-group schedule-time">
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting Scheduled by:</strong> <?php echo ($row['meeting_scheduled_by_user'])?$row['meeting_scheduled_by_user']:'-'; ?>
                                </div>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting Assigned to:</strong> <?php echo ($row['meeting_assigned_to'])?$row['meeting_assigned_to']:'-'; ?>
                                </div>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting with:</strong> <?php echo ($row['meeting_with_before_checkin_time'])?$row['meeting_with_before_checkin_time']:'-'; ?>
                                </div>
                            </div>
                            <div class="form-group schedule-time">
                                <div class="grey-bg-full-txt">
                                    <strong>Remarks:</strong><br>
                                    <?php echo ($row['meeting_Purpose'])?$row['meeting_Purpose']:'-'; ?>
                                </div>
                            </div>
                        </div>
                    <?php }else if($status==3){ // Finished?>
                        <!-- Meeting Finished Start -->
                        <div class="col-md-12 mt-15">
                            <div class="form-group schedule-time">
                                <ul class="flex-ul">
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_start_datetime'])?date_db_format_to_display_format($row['meeting_schedule_start_datetime']):'-'; ?></div>
                                    </li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_start_datetime'])?time_db_format_to_display_format_ampm($row['meeting_schedule_start_datetime']):'-'; ?></div>
                                    </li>
                                    <li>To</li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_end_datetime'])?time_db_format_to_display_format_ampm($row['meeting_schedule_end_datetime']):'-'; ?></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group schedule-time">
                                <ul class="flex-ul">
                                    <li>
                                        <div class="m-type <?php echo ($row['meeting_type']=='P')?'visit':'online'; ?>">
                                            <?php echo ($row['meeting_type']=='P')?'Visit':'Online'; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="finished-txt">Status: <?php echo $row['status']; ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group schedule-time">                                
                                <ul class="map-ul">
                                    <li>
                                        <?php if($row['meeting_type']=='P'){ ?>
                                            <svg class="fw-icon" height="78px" id="Layer_1" style="enable-background:new 0 0 78 78;" version="1.1" viewBox="0 0 78 78" width="78px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M69,30.451c0-16.568-13.431-30-30-30c-16.568,0-30,13.432-30,30c0,7.086,2.464,13.596,6.572,18.729   c0.001,0.001,0.002,0.003,0.001,0.004s-0.002,0.002-0.004,0.002c7.746,9.674,18.693,23.347,22.259,27.8   c0.285,0.355,0.716,0.562,1.171,0.563c0.456,0,0.888-0.206,1.172-0.562c3.564-4.452,14.508-18.119,22.254-27.794   c0.001-0.001,0.002-0.003,0.001-0.004s-0.002-0.002-0.004-0.002C66.534,44.052,69,37.542,69,30.451z M57.567,45.66L39,68.851   L20.613,45.887l-0.188-0.234c-0.055-0.075-0.111-0.149-0.169-0.222C16.817,41.134,15,35.954,15,30.451c0-13.234,10.767-24,24-24   s24,10.766,24,24c0,5.506-1.819,10.688-5.262,14.986C57.68,45.51,57.623,45.584,57.567,45.66z" style="fill:#333F4F;"/><circle cx="39" cy="30.451" r="10.5" style="fill:#333F4F;"/></g></svg>
                                        <?php } else { ?>
                                            <svg  class="fw-icon" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg"><rect fill="none" height="256" width="256"/><path d="M122.3,71.4l19.8-19.8a44.1,44.1,0,0,1,62.3,62.3l-28.3,28.2a43.9,43.9,0,0,1-62.2,0" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M133.7,184.6l-19.8,19.8a44.1,44.1,0,0,1-62.3-62.3l28.3-28.2a43.9,43.9,0,0,1,62.2,0" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                        <?php } ?>                                        
                                    </li>
                                    <?php if($row['meeting_type']=='P'){ ?>
                                    <li>
                                        <div class="grey-bg-auto"><span><?php echo ($row['meeting_venue'])?$row['meeting_venue']:'-'; ?></span></div>
                                    </li>
                                    <?php } else { ?>                                    
                                    <li>
                                        <div class="grey-bg-auto"><span><?php echo ($row['meeting_url'])?$row['meeting_url']:'-'; ?></span></div>
                                    </li>
                                    <?php } ?> 
                                </ul>
                            </div>
                            <div class="form-group schedule-time">
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting Scheduled by:</strong> <?php echo ($row['meeting_scheduled_by_user'])?$row['meeting_scheduled_by_user']:'-'; ?>
                                </div>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting Assigned to:</strong> <?php echo ($row['meeting_assigned_to'])?$row['meeting_assigned_to']:'-'; ?>
                                </div>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting with:</strong> <?php echo ($row['meeting_with_at_checkin_time'])?$row['meeting_with_at_checkin_time']:'-'; ?>
                                </div>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Purpose of Meeting:</strong> <?php echo ($row['meeting_agenda_type_name'])?$row['meeting_agenda_type_name']:'-'; ?>
                                </div>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting check-in/ Check-out:</strong> <ul class="flex-ul">
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['checkin_datetime'])?date_db_format_to_display_format($row['checkin_datetime']):'-'; ?></div>
                                    </li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['checkin_datetime'])?time_db_format_to_display_format_ampm($row['checkin_datetime']):'-'; ?></div>
                                    </li>
                                    <li>To</li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['checkout_datetime'])?time_db_format_to_display_format_ampm($row['checkout_datetime']):'-'; ?></div>
                                    </li>
                                </ul>
                                </div>
                            </div>
                            <div class="form-group schedule-time">
                                <div class="grey-bg-full-txt">
                                    <strong>Remarks:</strong><br>
                                    <?php echo ($row['meeting_Purpose'])?$row['meeting_Purpose']:'-'; ?>
                                </div>
                            </div>
                            <div class="form-group schedule-time">
                                <div class="grey-bg-full-txt">
                                    <strong>Discussion Points:</strong><br>
                                    <?php echo ($row['discussion_points'])?$row['discussion_points']:'-'; ?>
                                </div>
                            </div>
                            <small class="pull-right"><i><b>Meeting Updated on <?php echo datetime_db_format_to_display_format_ampm($row['updated_at']); ?></b></i></small>
                        </div>
                        <!-- Meeting Finished End -->
                    <?php }else if($status==4){ // Cancelled?>
                        <!-- Meeting Cancelled Start -->
                        <div class="col-md-12 mt-15">
                            <div class="form-group schedule-time">
                                <ul class="flex-ul">
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_start_datetime'])?date_db_format_to_display_format($row['meeting_schedule_start_datetime']):'-'; ?></div>
                                    </li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_start_datetime'])?time_db_format_to_display_format_ampm($row['meeting_schedule_start_datetime']):'-'; ?></div>
                                    </li>
                                    <li>To</li>
                                    <li>
                                        <div class="grey-bg-auto"><?php echo ($row['meeting_schedule_end_datetime'])?time_db_format_to_display_format_ampm($row['meeting_schedule_end_datetime']):'-'; ?></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group schedule-time">
                                <ul class="flex-ul">
                                    <li>
                                        <div class="m-type <?php echo ($row['meeting_type']=='P')?'visit':'online'; ?>">
                                            <?php echo ($row['meeting_type']=='P')?'Visit':'Online'; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="cancelled-txt">Status: <?php echo $row['status']; ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group schedule-time">                                
                                <ul class="map-ul">
                                    <li>
                                        <?php if($row['meeting_type']=='P'){ ?>
                                            <svg class="fw-icon" height="78px" id="Layer_1" style="enable-background:new 0 0 78 78;" version="1.1" viewBox="0 0 78 78" width="78px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M69,30.451c0-16.568-13.431-30-30-30c-16.568,0-30,13.432-30,30c0,7.086,2.464,13.596,6.572,18.729   c0.001,0.001,0.002,0.003,0.001,0.004s-0.002,0.002-0.004,0.002c7.746,9.674,18.693,23.347,22.259,27.8   c0.285,0.355,0.716,0.562,1.171,0.563c0.456,0,0.888-0.206,1.172-0.562c3.564-4.452,14.508-18.119,22.254-27.794   c0.001-0.001,0.002-0.003,0.001-0.004s-0.002-0.002-0.004-0.002C66.534,44.052,69,37.542,69,30.451z M57.567,45.66L39,68.851   L20.613,45.887l-0.188-0.234c-0.055-0.075-0.111-0.149-0.169-0.222C16.817,41.134,15,35.954,15,30.451c0-13.234,10.767-24,24-24   s24,10.766,24,24c0,5.506-1.819,10.688-5.262,14.986C57.68,45.51,57.623,45.584,57.567,45.66z" style="fill:#333F4F;"/><circle cx="39" cy="30.451" r="10.5" style="fill:#333F4F;"/></g></svg>
                                        <?php } else { ?>
                                            <svg  class="fw-icon" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg"><rect fill="none" height="256" width="256"/><path d="M122.3,71.4l19.8-19.8a44.1,44.1,0,0,1,62.3,62.3l-28.3,28.2a43.9,43.9,0,0,1-62.2,0" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M133.7,184.6l-19.8,19.8a44.1,44.1,0,0,1-62.3-62.3l28.3-28.2a43.9,43.9,0,0,1,62.2,0" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                        <?php } ?>                                        
                                    </li>
                                    <?php if($row['meeting_type']=='P'){ ?>
                                    <li>
                                        <div class="grey-bg-auto"><span><?php echo ($row['meeting_venue'])?$row['meeting_venue']:'-'; ?></span></div>
                                    </li>
                                    <?php } else { ?>                                    
                                    <li>
                                        <div class="grey-bg-auto"><span><?php echo ($row['meeting_url'])?$row['meeting_url']:'-'; ?></span></div>
                                    </li>
                                    <?php } ?> 
                                </ul>
                            </div>
                            <div class="form-group schedule-time">                                
                                <div class="form-group schedule-time">
                                    <div class="w-100 m-row font-size-14">
                                        <strong>Meeting Scheduled by:</strong> <?php echo ($row['meeting_scheduled_by_user'])?$row['meeting_scheduled_by_user']:'-'; ?>
                                    </div>
                                    <div class="w-100 m-row font-size-14">
                                        <strong>Meeting Assigned to:</strong> <?php echo ($row['meeting_assigned_to'])?$row['meeting_assigned_to']:'-'; ?>
                                    </div>
                                    <div class="w-100 m-row font-size-14">
                                        <strong>Meeting with:</strong> <?php echo ($row['meeting_with_before_checkin_time'])?$row['meeting_with_before_checkin_time']:'-'; ?>
                                    </div>
                                </div>
                                <?php /* ?>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting by:</strong> <?php echo ($row['meeting_assigned_to'])?$row['meeting_assigned_to']:'-'; ?>
                                </div>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Meeting with:</strong> <?php echo ($row['meeting_with_at_checkin_time'])?$row['meeting_with_at_checkin_time']:'-'; ?>
                                </div>
                                <?php */ ?>
                                <div class="w-100 m-row font-size-14">
                                    <strong>Purpose of Meeting:</strong> <?php echo ($row['meeting_agenda_type_name'])?$row['meeting_agenda_type_name']:'-'; ?>
                                </div>
                            </div>
                            <div class="form-group schedule-time">
                                <div class="grey-bg-full-txt mb-10">
                                    <strong>Remarks:</strong><br>
                                    <?php echo ($row['meeting_Purpose'])?$row['meeting_Purpose']:'-'; ?>
                                </div>
                                <div class="grey-bg-full-txt">
                                    <strong>Reason For Cancellation:</strong><br>
                                    <?php echo ($row['cancellation_reason'])?$row['cancellation_reason']:'-'; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Meeting Cancelled End -->
                    <?php } ?>                    
                </div>
                 
                
                <?php if($status==1 || $status==5){ ?>                
                <div class="action-row">
                    <div class="co-auto">
                        <a href="#" class="m-icon update-meeting">
                            <span>
                                <svg id="Icons" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#74c0fc;}</style></defs><path class="cls-1" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm5.707,8.707-7,7a1,1,0,0,1-1.414,0l-3-3a1,1,0,0,1,1.414-1.414L10,14.586l6.293-6.293a1,1,0,0,1,1.414,1.414Z"/></svg>
                            </span>
                            <span>Dispose Meeting</span>
                        </a>
                    </div>
                    <!-- <div class="co-auto">
                        <a href="#" class="m-icon reschedule-meeting">
                            <span>
                                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path style="fill: #74c0fc;" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z"/></svg>
                            </span>
                            <span>Reschedule Meeting</span>
                        </a>
                    </div> -->
                    <?php //if($meeting_schedule_start_date>=date("Y-m-d")){ ?>
                    <div class="co-auto">
                        <a href="JavaScript:void(0)" class="m-icon meeting_schedule_view_popup" id="<?php echo $row['id']; ?>" data-leadid="<?php echo $row['lead_id']; ?>" data-cid="<?php echo $row['customer_id']; ?>" >
                            <span>
                                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path style="fill: #74c0fc;" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z"/></svg>
                            </span>
                            <span>Edit Meeting</span>
                        </a>
                    </div>
                    <?php //} ?>
                    <div class="co-auto">
                        <a href="#" class="m-icon cancel-meeting">
                            <span>
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 496.158 496.158" style="enable-background:new 0 0 496.158 496.158;" xml:space="preserve">
                                <path style="fill:#E04F5F;" d="M0,248.085C0,111.063,111.069,0.003,248.075,0.003c137.013,0,248.083,111.061,248.083,248.082
                                    c0,137.002-111.07,248.07-248.083,248.07C111.069,496.155,0,385.087,0,248.085z"/>
                                <path style="fill:#FFFFFF;" d="M383.546,206.286H112.612c-3.976,0-7.199,3.225-7.199,7.2v69.187c0,3.976,3.224,7.199,7.199,7.199
                                    h270.934c3.976,0,7.199-3.224,7.199-7.199v-69.187C390.745,209.511,387.521,206.286,383.546,206.286z"/>
                                </svg>
                            </span>
                            <span>Cancel Meeting</span>
                        </a>
                    </div>
                </div>
                <div class="update-meeting-block">
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="form-flex">
                                <label>Meeting done date</label>
                                <div class="w-auto">
                                <input type="text" readonly="true" class="default-input display_date" name="checkin_date" id="checkin_date" value="<?php echo ($row['meeting_schedule_start_datetime'])?date_db_format_to_display_format($row['meeting_schedule_start_datetime']):'-'; ?>" data-existing_value="<?php echo ($row['meeting_schedule_start_datetime'])?date_db_format_to_display_format($row['meeting_schedule_start_datetime']):'-'; ?>" >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="form-flex">
                                <label>Check-in Time</label>
                                <div class="w-auto">
                                    <input type="time" onfocus="this.showPicker()" class="default-input time_element--" name="checkin_time" id="checkin_time" placeholder="_ _:_ _ : AM" value="<?php echo ($row['meeting_schedule_start_datetime'])?date("H:i:s",strtotime($row['meeting_schedule_start_datetime'])):''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                    <div class="col-md-12">
                        <div class="form-flex">
                            <label>Check-out Time</label>
                            <div class="w-auto">
                                <input type="time" onfocus="this.showPicker()" class="default-input time_element--" name="checkout_time" id="checkout_time" placeholder="_ _:_ _ : AM" value="<?php echo ($row['meeting_schedule_end_datetime'])?date("H:i:s",strtotime($row['meeting_schedule_end_datetime'])):''; ?>">
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label>Met with:</label>
                            <div class="w-100">
                            <input type="text" class="default-input " name="meeting_with_at_checkin_time" id="meeting_with_at_checkin_time" placeholder="" value="<?php echo ($row['meeting_with_before_checkin_time'])?$row['meeting_with_before_checkin_time']:''; ?>">
                                <?php /* ?> <select class="default-select" id="meeting_with_at_checkin_time" name="meeting_with_at_checkin_time">
                                    <?php if($cus_data->contact_person){ ?>
                                    <option value="<?php echo $cus_data->contact_person; ?>"><?php echo $cus_data->contact_person; ?></option>
                                    <?php } ?>
                                    <?php if(count($contact_persion_list)){ ?>
                                        <?php foreach($contact_persion_list AS $contact_persion){ ?>
                                        <option value="<?php echo $contact_persion['name']; ?>"><?php echo $contact_persion['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>   <?php */ ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-flex-auto">
                        <div class="col-flex-auto">
                            <label class="default-checkbox">Self Visit
                                <input type="radio" name="self_visited_or_visited_with_colleagues" checked="checked" value="S">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-md-auto">
                            <label class="default-checkbox">Visited with Colleagues 
                                <input type="radio" name="self_visited_or_visited_with_colleagues" value="C">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row visited_colleagues_div hide" >
                        <div class="col-md-12">
                            <label>Colleagues:</label>
                            <div class="w-100">
                                <select class="default-select" id="visited_colleagues" name="visited_colleagues[]" multiple>
                                    <?php if(count($user_list)){ ?>
                                        <?php foreach($user_list AS $user){ ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>   
                            </div>
                        </div>
                    </div>
                                            
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label>Discussion Points:</label>
                            <div class="w-100">
                                <textarea class="default-textarea" id="discussion_points" name="discussion_points"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 d-inline-block border-top-grey pt-25">
                    <a href="JavaScript:void(0)" class="lead-btn orange pull-right update-lead" id="meeting_finished_confirm">SUBMIT</a>
                    </div>
                </div>
                <div class="cancel-meeting-block">
                    <hr>
                    <div class="form-group row">
                    <div class="col-md-12">
                        <label>Reason For  Cancellation:</label>
                        <div class="w-100">
                            <textarea class="default-textarea" name="cancellation_reason" id="cancellation_reason"></textarea>
                        </div>
                    </div>
                    </div>

                    <div class="w-100 d-inline-block border-top-grey pt-25">
                    <a href="JavaScript:void(0)" class="lead-btn orange pull-right cancel-meeting-btn" id="meeting_cancelled_confirm">SUBMIT</a>
                    </div>
                    
                </div>

                <?php }else if($status==3){ ?>                
                    <?php /* ?> <div class="form-group row">
                    <div class="col-md-12">
                        <div class="m-row-flex">
                            <div>
                                <img src="<?php echo assets_url(); ?>images/event-time-icon.png">
                            </div>
                            <div class="m-filed mil">
                                <div class="m-auto">
                                    <span>Check-in Time</span>
                                </div>
                                <div class="m-auto">
                                    <input type="text" name="" value="<?php echo ($row['checkin_datetime'])?time_db_format_to_display_format_ampm($row['checkin_datetime']):'-'; ?>" class="default-input" disabled="">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="m-row-flex">
                            <div>
                                <img src="<?php echo assets_url(); ?>images/event-time-icon.png">
                            </div>
                            <div class="m-filed mil">
                                <div class="m-auto">
                                    <span>Check-out Time</span>
                                </div>
                                <div class="m-auto">
                                    <input type="text" name="" value="<?php echo ($row['checkout_datetime'])?time_db_format_to_display_format_ampm($row['checkout_datetime']):'-'; ?>" class="default-input" disabled="">
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="m-row-flex">
                            <div>
                                <img src="<?php echo assets_url(); ?>images/event-time-icon.png">
                            </div>
                            <div class="m-filed mil">
                                <div class="m-auto">
                                    <span>Actual Meeting With</span>
                                </div>
                                <div class="m-auto">
                                    <?php echo ($row['meeting_with_at_checkin_time'])?$row['meeting_with_at_checkin_time']:'-'; ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-12 mb-15">
                        <div class="m-row-flex">
                            <div>
                                <img src="<?php echo assets_url(); ?>images/event-time-icon.png">
                            </div>
                            <div class="m-filed mil">
                                <div class="m-auto">
                                    <span>Visited With</span>
                                </div>
                                <div class="m-auto">
                                    <?php echo ($row['self_visited_or_visited_with_colleagues']=='S')?'Self':$row['visited_colleagues']; ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-12 mr-15">
                        <div class="m-row-flex">
                            <div>
                                <img src="<?php echo assets_url(); ?>images/file-icon.png">
                            </div>
                            <div class="m-filed mil">
                                <div class="m-auto">
                                    <a href="#" class="mPoints"><span>Discussion Points</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                </div>
                            </div> 
                        </div>

                        <div class="mPointsDetails">
                            <div class="point-area"><?php echo $row['discussion_points']; ?></div>
                        </div>

                    </div>
                </div> <?php */ ?>
                
                <?php }else if($status==4){ ?>                
                    <?php /* ?> <div class="form-group row">
                    <div class="col-md-12 mr-15">
                        <div class="m-row-flex">
                            <div>
                                <img src="<?php echo assets_url(); ?>images/file-icon.png">
                            </div>
                            <div class="m-filed mil">
                                <div class="m-auto">
                                    <a href="#" class="mCancelledPoints"><span>Reason of Cancelled</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                </div>
                            </div> 
                        </div>

                        <div class="mCancelledPointsDetails">
                            <div class="point-area"><?php echo $row['cancellation_reason']; ?></div>
                        </div>

                    </div>
                </div> <?php */ ?>
                <?php } ?>
                
            </div>
        </div>
    </div>
</div>
</form>
<!-- <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/timepicki.css">
<script src="<?php echo assets_url(); ?>js/timepicki.js"></script> -->
<link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>
<script>
    
    $('#checkin_date').datepicker({
      dateFormat: "dd-M-yy",
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+5',
      maxDate:0
    });
    $('input[type=radio][name=self_visited_or_visited_with_colleagues]').on('change', function() {
        var getVal = $(this).val();        
        if(getVal == 'S'){
            $('.visited_colleagues_div').addClass('hide');
        }else{
            $('.visited_colleagues_div').removeClass('hide');
        }
        // updateTimeSlot()
    });
    $('#visited_colleagues').multiselect({
      buttonClass:'custom-multiselect',
      includeSelectAllOption:true
    });
</script>