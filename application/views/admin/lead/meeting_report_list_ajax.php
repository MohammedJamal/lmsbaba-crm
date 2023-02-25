<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <?php
        // $start_date = new DateTime($row['checkin_datetime']);
        // $since_start = $start_date->diff(new DateTime($row['checkout_datetime']));
        // echo $since_start->days.' days total<br>';
        // echo $since_start->y.' years<br>';
        // echo $since_start->m.' months<br>';
        // echo $since_start->d.' days<br>';
        // echo $since_start->h.' hours<br>';
        // echo $since_start->i.' minutes<br>';
        // echo $since_start->s.' seconds<br>';
        // $datetime_diff_in_minuts=$since_start->i;
        ?>
        <tr scope="row" class="">   
            <td class="">               
                <?php echo ($row['lead_id'])?'<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/manage/?search_by_id='.$row['lead_id'].'" target="_blank"><u>'.$row['lead_id'].'</u></a>':'N/A'; ?></td>  
            <td class=""><?php echo ($row['last_stage'])?$row['last_stage']:'N/A'; ?></td>     
            <td class=""><?php echo ($row['status'])?$row['status']:'N/A'; ?></td>                                                  
            <td class=""><?php echo ($row['checkin_datetime'])?date_db_format_to_display_format($row['checkin_datetime']):'N/A'; ?></td>
            <td class=""><?php echo ($row['cust_company_name'])?$row['cust_company_name']:'N/A'; ?></td> 
            <td class=""><?php echo $row['user']; ?></td> 
            
            <td class=""><?php echo ($row['meeting_type']=='P')?'Visit':'Online'; ?></td>
            <td class="auto-show hide">
                <?php if($row['meeting_type']=='P' && $row['checkin_datetime']!=NULL){ ?>
                    <?php if(($row['meeting_dispose_latitude']!='null' && $row['meeting_dispose_latitude']!=NULL) && ($row['meeting_dispose_longitude']!='null' && $row['meeting_dispose_longitude']!=NULL)){ ?>
                    <a href="http://maps.google.com/?q=<?php echo $row['meeting_dispose_latitude']; ?>,<?php echo $row['meeting_dispose_longitude']; ?>" target="_blank"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                    <?php } ?>
                <?php } ?>
                <?php echo ($row['checkin_datetime'])?time_db_format_to_display_format_ampm($row['checkin_datetime']):'N/A'; ?>
            </td> 
            <td class="auto-show hide"><?php echo ($row['checkout_datetime'])?time_db_format_to_display_format_ampm($row['checkout_datetime']):'N/A'; ?></td> 
            <td class=""><?php echo ($row['meeting_agenda_type_name'])?$row['meeting_agenda_type_name']:'N/A'; ?></td>
            <td class="auto-show hide"><?php echo ($row['meeting_with_at_checkin_time'])?$row['meeting_with_at_checkin_time']:'N/A'; ?></td>
            <td class=""><?php echo ($row['meeting_Purpose'])?$row['meeting_Purpose']:'N/A'; ?></td>             
            <td class="auto-show hide"><?php echo ($row['self_visited_or_visited_with_colleagues']=='S')?'Self':$row['visited_colleagues']; ?></td> 
            <td class="">
                    <?php 
                    if($row['status_id']=='4'){
                        echo ($row['cancellation_reason'])?'<span class="text-danger">'.$row['cancellation_reason'].'</span>':'N/A';
                    } 
                    else{
                        echo ($row['discussion_points'])?$row['discussion_points']:'N/A';
                    }
                    ?>
            <?php  ?></td>
            <td class="auto-show hide"><?php echo ($row['updated_at'])?date_db_format_to_display_format($row['updated_at']):'N/A'; ?></td> 
            <!-- <td class=""><?php echo ($row['meeting_type']=='P')?($row['meeting_venue'])?$row['meeting_venue']:'N/A':'N/A'; ?></td>                                     -->
        </tr>
        <?php } ?>
<?php }else{ ?>
    <tr scope="row" class="">                                                    
        <td class="" colspan="10">No record found!</td>                                    
    </tr>
<?php } ?>
