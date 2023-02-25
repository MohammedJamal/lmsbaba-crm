<?php if(count($rows)){

  $today=date("Y-m-d");
          $i=$sl_start; ?>
            <?php foreach($rows AS $row){ ?>
            <tr class="<?php echo ($row['is_account_active']=='N')?'table-danger':''; ?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $row['account_type']; ?></td>
                <td align="left" width="20%"><a href="<?php echo $row['api_url'].$row['domain_name']; ?>" target="_blank"><?php echo $row['company'].''; ?></a><br><mark>LMS ID: <?php echo $row['client_id']; ?></mark><br>
                <b>User Tagged</b>: <a href="JavaScript:void(0)" title="View User List" class="tagged_user_list text-primary" data-cid="<?php echo $row['client_id']; ?>"><?php echo $row['total_user_count']; ?></a></td>
                
                <td><?php echo date_db_format_to_display_format($row['last_login']); ?></td>
                <td><?php echo $row['not_logged_day']; ?> Days</td>
                <td width="15%"><?php echo date_db_format_to_display_format($row['start_date']); ?></td>
                <td width="20%">
                    <span>
                      <?php echo date_db_format_to_display_format($row['package_end_date']); ?>
                    </span>       
                </td>
                <td width="20%">
                    <span>
                      <?php echo date_db_format_to_display_format($row['expire_date']); ?>
                    </span>
                </td>
                <td width="10%"> <?php echo (($row['last_touch_day']!='')?$row['last_touch_day'].' Days':'Not Touch'); ?> </td>
                <td>
                <?php if(trim($row['assign_to_name'])==''){ echo 'NA'; } else { echo $row['assign_to_name']; } ?>
                </td>

                <?php
                  if($row['activity_status_type_id']==1){
                    $activity_subname='text-primary';
                    $activity_subname_tc='text-success';
                  } elseif($row['activity_status_type_id']==2){
                    $activity_subname='text-primary';
                    $activity_subname_tc='text-danger';
                  } elseif($row['activity_status_type_id']==3){
                    $activity_subname='text-danger';
                    $activity_subname_tc='text-success';
                  } elseif($row['activity_status_type_id']==4){
                    $activity_subname='text-danger';
                    $activity_subname_tc='text-danger';
                  } elseif($row['activity_status_type_id']==5){
                    $activity_subname='text-success';
                    $activity_subname_tc='text-success';
                  } elseif($row['activity_status_type_id']==6){
                    $activity_subname='text-success';
                    $activity_subname_tc='text-danger';
                  } elseif($row['activity_status_type_id']==7){
                    $activity_subname='text-danger';
                    $activity_subname_tc='text-danger';
                  } 
                  ?>

                <td class="<?php echo $tbl_td_color;?> <?php echo $activity_subname;?>">
                <b><?php echo $row['activity_name']; ?></b>
                (<i class="<?php echo $activity_subname_tc;?>"><?php echo $row['activity_sub_name']; ?></i>)

                </td>
                
                <td>
                  <!-- <a href="JavaScript:void(0)" title="Update" class="comment_update_row" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a> -->

                  <?php if(trim($row['scheduled_call_datetime'])==''){ echo'NA'; } else { ?>
                    <!-- <mark><?php echo $row['call_type_name']; ?></mark><br /> -->
                    <span class="text-primary" title="Call Schedule Date">
                      <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['scheduled_call_datetime']); ?><br></span>
                    
                    <?php if($row['call_status']==1){ ?>
                      <span class="text-success" title="Call Done Date">
                    <i class="fa fa-phone-square" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['actual_call_done_datetime']); ?></span>
                    <?php } else { 
                                            
                        if($today==date_display_format_to_db_format($row['scheduled_call_datetime'])){
                          echo'<span class="text-warning" title="CALL TODAY"><i class="fa fa-phone-square" aria-hidden="true"></i> TODAY</span>';
                        } elseif($today>date_display_format_to_db_format($row['scheduled_call_datetime'])){
                          echo'<span class="text-danger" title="CALL PENDING"><i class="fa fa-phone-square" aria-hidden="true"></i> PENDING</span>';
                        } elseif($today<date_display_format_to_db_format($row['scheduled_call_datetime'])){
                          echo'<span class="text-secondary" title="CALL UPCOMING"><i class="fa fa-phone-square" aria-hidden="true"></i> UPCOMING</span>';
                        } 
                      
                        
                      if($row['call_type_id']==6 || $row['activity_status_type_id']==1 || $row['activity_status_type_id']==2 || $row['activity_status_type_id']==3 || $row['activity_status_type_id']==4){ ?>

                      <br><a href="JavaScript:void(0)" title="Renewal Call Update" class="renewal_comment_update_row text-info" data-cid="<?php echo $row['client_id']; ?>" data-scid="<?php echo $row['call_id']; ?>" ><i class="fa fa-commenting-o" aria-hidden="true"></i></a>

                      <?php } else { ?>

                        <br><a href="JavaScript:void(0)" title="<?php echo $row['call_type_name']; ?> Update" class="comment_update_row text-info" data-cid="<?php echo $row['client_id']; ?>" data-tid="<?php echo $row['call_type_id']; ?>" data-title="<?php echo $row['call_type_name']; ?>" data-scid="<?php echo $row['call_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a> 

                      <?php } ?>

                    <?php } ?>


                <?php } ?>
                </td>
                <td><?php echo date_db_format_to_display_format($row['next_followup_date']); ?>
                    <br>
                    <?php

                    if($today==date_display_format_to_db_format($row['next_followup_date'])){
                      echo'<span class="text-warning" title="CALL TODAY">TODAY</span>';
                    } elseif($today>date_display_format_to_db_format($row['next_followup_date'])){
                      echo'<span class="text-danger" title="CALL PENDING">PENDING</span>';
                    } elseif($today<date_display_format_to_db_format($row['next_followup_date'])){
                      echo'<span class="text-secondary" title="CALL UPCOMING">UPCOMING</span>';
                    } ?><br><br>
              
                <a href="JavaScript:void(0)" title="View Log" class="view_comment_list" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></a>
              </td>
            </tr>
            <?php $i++; } ?>
            <?php }else{ ?>
            <tr><td colspan="12">No Inactive Client Found!</td></tr>
        <?php } ?>