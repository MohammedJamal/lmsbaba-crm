<h1><i class="fa fa-calendar-check-o" aria-hidden="true"></i> <span>Other meeting scheduled on <?php echo $selected_meeting_date; ?> By <?php echo $selected_user_name; ?></span></h1>
<?php if(count($meeting_list_by_date)){ ?>
<ul>    
    <?php foreach($meeting_list_by_date AS $meeting){ ?>
    <li>
        <div class="meet-item" title="Online meeting with Shashi Ranain at 10.30 AM to 11.00 AM"><?php echo time_db_format_to_display_format_ampm($meeting->meeting_schedule_start_datetime); ?> to <?php echo time_db_format_to_display_format_ampm($meeting->meeting_schedule_end_datetime); ?></div>
    </li>
    <?php } ?>      
</ul>
<?php }else{ ?>
    No meeting scheduled!
<?php } ?>