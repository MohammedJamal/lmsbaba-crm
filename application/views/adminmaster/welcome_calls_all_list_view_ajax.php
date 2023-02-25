<?php $today=date("Y-m-d"); ?>
<div class="">
  <h2>Welcome Calls List</h2>
  <p></p>  

  <table class="table table-bordered text-left">
    <thead>
      <tr class="bg-dark">
        <th>SL No.</th>
        <th>Type</th>
        <th>Company</th>
        <th class="text-left">Module &<br>Service</th>
        <th class="text-left">Date</th>
        
        <?php
        if(is_array($service_call_type) && count($service_call_type)>0){
            foreach($service_call_type AS $type){ ?>
            <th><?php echo $type['name']; ?></th>
            <?php }
        } 
        ?>
        
        <th>Assigned To</th>
        <th>Client Activity<br>Status</th>
        <th>Next <br>Follow-up</th>
        
      </tr>
    </thead>
    <tbody id="tcontent">
        <?php if(count($rows)){
          
          $activity_subname='';
          $activity_subname_tc='';
          $i=$sl_start; ?>
            <?php foreach($rows AS $row){ 
              
              $tbl_tr_bg='';
                $tbl_td_color='';
                ?>
                
            <tr class="<?php echo $tbl_tr_bg; ?>">
                <td class="<?php echo $tbl_td_color;?>"><?php echo $i; ?></td>
                <td class="<?php echo $tbl_td_color;?>"><?php echo $row['account_type']; ?></td>
                <td class="<?php echo $tbl_td_color;?>"><a href="<?php echo $row['api_url'].$row['domain_name']; ?>" target="_blank"><?php echo $row['company'].''; ?></a><br><mark>LMS ID: <?php echo $row['client_id']; ?></mark><br>
              User Sold: <a href="JavaScript:void(0)" title="View User List" class="tagged_user_list text-primary" data-cid="<?php echo $row['client_id']; ?>"><?php echo $row['total_user_count']; ?></a><br>
              <b>Last Login:</b> <?php echo date_db_format_to_display_format($row['last_login_date']); ?><br>
              <b>Not Logged in:</b> <?php echo (($row['not_logged_day']!='')?$row['not_logged_day'].' Days':''); ?>
              
              </td>
                <td><?php echo $row['service_name'].''; ?><br><mark><?php echo $row['module_name']; ?></mark></td>
                <td>
                  
                <span class="text-primary">Start: <?php echo date_db_format_to_display_format($row['start_date_data']); ?><br></span>
                <b class="text-warning">End: <?php echo date_db_format_to_display_format($row['end_date_data']); ?><br></b>
                <span class="text-danger">Expiry:<?php echo date_db_format_to_display_format($row['expiry_date_data']); ?></span>
                </td>

                <?php
                
                if(is_array($service_call_type) && count($service_call_type)>0){
                    foreach($service_call_type AS $type){ 
                      
                    if($type['id']==1){ ?>
                    <td>
                    <span class="text-primary" title="Call Schedule Date">
                      <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['wel_scheduled_call_datetime']); ?><br></span>
                    
                    <?php if($row['wel_call_status']==1){ ?>
                      <span class="text-success" title="Call Done Date">
                    <i class="fa fa-phone-square" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['wel_actual_call_done_datetime']); ?></span>
                    <?php } else { 
                        
                        if($today==date_display_format_to_db_format($row['wel_scheduled_call_datetime'])){
                          echo'<span class="text-warning" title="CALL TODAY"><i class="fa fa-phone-square" aria-hidden="true"></i> TODAY</span>';
                        } elseif($today>date_display_format_to_db_format($row['wel_scheduled_call_datetime'])){
                          echo'<span class="text-danger" title="CALL PENDING"><i class="fa fa-phone-square" aria-hidden="true"></i> PENDING</span>';
                        } elseif($today<date_display_format_to_db_format($row['wel_scheduled_call_datetime'])){
                          echo'<span class="text-secondary" title="CALL UPCOMING"><i class="fa fa-phone-square" aria-hidden="true"></i> UPCOMING</span>';
                        } ?>

                      <br><a href="JavaScript:void(0)" title="<?php echo $type['name']; ?> Update" class="call_comment_update_row text-info" data-cid="<?php echo $row['client_id']; ?>" data-sid="<?php echo $row['service_order_detail_id']; ?>" data-tid="<?php echo $type['id']; ?>" data-title="<?php echo $type['name']; ?>" data-scid="<?php echo $row['wel_call_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a>
                    <?php } ?>
                    </td>
                    <?php } 

                    if($type['id']==2){ ?>
                    <td>
                      <?php if(trim($row['d3_scheduled_call_datetime'])==''){ echo'NA'; } else { ?>
                    <span class="text-primary" title="Call Schedule Date">
                      <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['d3_scheduled_call_datetime']); ?><br></span>

                    <?php if($row['d3_call_status']==1){ ?>
                      <span class="text-success" title="Call Done Date">
                    <i class="fa fa-phone-square" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['d3_actual_call_done_datetime']); ?></span>
                    <?php } else { 
                        
                        if($today==date_display_format_to_db_format($row['d3_scheduled_call_datetime'])){
                          echo'<span class="text-warning" title="CALL TODAY"><i class="fa fa-phone-square" aria-hidden="true"></i> TODAY</span>';
                        } elseif($today>date_display_format_to_db_format($row['d3_scheduled_call_datetime'])){
                          echo'<span class="text-danger" title="CALL PENDING"><i class="fa fa-phone-square" aria-hidden="true"></i> PENDING</span>';
                        } elseif($today<date_display_format_to_db_format($row['d3_scheduled_call_datetime'])){
                          echo'<span class="text-secondary" title="CALL UPCOMING"><i class="fa fa-phone-square" aria-hidden="true"></i> UPCOMING</span>';
                        } ?>


                      <br><a href="JavaScript:void(0)" title="<?php echo $type['name']; ?> Update" class="call_comment_update_row text-info" data-cid="<?php echo $row['client_id']; ?>" data-sid="<?php echo $row['service_order_detail_id']; ?>" data-tid="<?php echo $type['id']; ?>" data-title="<?php echo $type['name']; ?>" data-scid="<?php echo $row['d3_call_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a>
                    <?php } } ?>
                    </td>
                    <?php } 
                    
                    if($type['id']==3){ ?>
                      <td>
                      <?php if(trim($row['d7_scheduled_call_datetime'])==''){ echo'NA'; } else { ?>
                      <span class="text-primary" title="Call Schedule Date">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['d7_scheduled_call_datetime']); ?><br></span>
                        
                      <?php if($row['d7_call_status']==1){ ?>
                        <span class="text-success" title="Call Done Date">
                    <i class="fa fa-phone-square" aria-hidden="true"></i> <?php echo date_db_format_to_display_format($row['d7_actual_call_done_datetime']); ?></span>
                      <?php } else { 
                        
                        if($today==date_display_format_to_db_format($row['d7_scheduled_call_datetime'])){
                          echo'<span class="text-warning" title="CALL TODAY"><i class="fa fa-phone-square" aria-hidden="true"></i> TODAY</span>';
                        } elseif($today>date_display_format_to_db_format($row['d7_scheduled_call_datetime'])){
                          echo'<span class="text-danger" title="CALL PENDING"><i class="fa fa-phone-square" aria-hidden="true"></i> PENDING</span>';
                        } elseif($today<date_display_format_to_db_format($row['d7_scheduled_call_datetime'])){
                          echo'<span class="text-secondary" title="CALL UPCOMING"><i class="fa fa-phone-square" aria-hidden="true"></i> UPCOMING</span>';
                        } ?>

                        <br><a href="JavaScript:void(0)" title="<?php echo $type['name']; ?> Update" class="call_comment_update_row text-info" data-cid="<?php echo $row['client_id']; ?>" data-sid="<?php echo $row['service_order_detail_id']; ?>" data-tid="<?php echo $type['id']; ?>" data-title="<?php echo $type['name']; ?>" data-scid="<?php echo $row['d7_call_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a>
                      <?php } } ?>
                      </td>
                      <?php } ?>
                    <?php }
                } 
                ?>

                <td class="<?php echo $tbl_td_color;?>">
                <?php if(trim($row['assign_to_name'])==''){
                    echo'NA';
                } else { 
                    echo $row['assign_to_name']; 
                } ?>
                </td>
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
                    $activity_subname='';
                    $activity_subname_tc='';
                  } 
                  ?>

                <td class="<?php echo $tbl_td_color;?> <?php echo $activity_subname;?>">
                <b><?php echo $row['activity_name']; ?></b>
                (<i class="<?php echo $activity_subname_tc;?>"><?php echo $row['activity_sub_name']; ?></i>)

                </td>
                <td class="<?php echo $tbl_td_color;?>"><?php echo date_db_format_to_display_format($row['next_followup_date']); ?>
                    <br>
                    <?php

                    if($today==date_display_format_to_db_format($row['next_followup_date'])){
                      echo'<span class="text-warning" title="CALL TODAY">TODAY</span>';
                    } elseif($today>date_display_format_to_db_format($row['next_followup_date'])){
                      echo'<span class="text-danger" title="CALL PENDING">PENDING</span>';
                    } elseif($today<date_display_format_to_db_format($row['next_followup_date'])){
                      echo'<span class="text-secondary" title="CALL UPCOMING">UPCOMING</span>';
                    } ?>
                    <br><br>
                    
                    <!-- <?php echo $row['service_call_type_id']; ?>
                    <br><br> -->
                <a href="JavaScript:void(0)" title="View Log" class="view_comment_list" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></a></td>
                
            </tr>
            <?php $i++; } ?>
            <?php }else{ ?>
            <tr><td colspan="12">No Welcome Calls Found!</td></tr>
        <?php } ?>
    </tbody>
  </table>

  <?php
   if(count($rows)){
     echo $page_record_count_info;
     echo $page;
   } ?>
</div>
<script>
$(document).ready(function(){
  $( ".display_date" ).datepicker({dateFormat: 'dd-M-yy'});
});
    
</script>
