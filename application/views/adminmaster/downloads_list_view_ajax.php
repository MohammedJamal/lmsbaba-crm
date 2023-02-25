<div class="">
  <h2>Download Client List </h2>
  <p></p>            
  <table class="table table-bordered">
    <thead>
      <tr class="info bg-dark">
        <th>SL No.</th>
        <th>Type</th>
        <th>Company</th>
        <th>User Sold</th>
        <th>Last Login</th>
        <th class="text-center">Start Date</th>
        <th class="text-center">End Date</th>
        <th class="text-center">Expiry Date</th>
        <th>Amount</th>
        <th>Assigned To</th>
        <th>Client Activity<br>Status</th>
        <th>Call <br>Status & Update</th>
        <th>Next <br>Follow-up</th>
      </tr>
    </thead>
    <tbody>
        <?php if(count($rows)){

          $activity_subname='';
          $activity_subname_tc='';

          $today=date("Y-m-d");
          $i=1; ?>
            <?php foreach($rows AS $row){ ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row['account_type']; ?></td>
                <td align="left" width="20%"><a href="<?php echo $row['api_url'].$row['domain_name']; ?>" target="_blank"><?php echo $row['company'].''; ?></a><br><mark>LMS ID: <?php echo $row['client_id']; ?></mark></td>
                
                <td><a href="JavaScript:void(0)" title="View User List" class="sold_user_list text-primary" data-cid="<?php echo $row['client_id']; ?>"><?php echo $row['total_user_count']; ?></a></td>
                <td><?php echo date_db_format_to_display_format($row['last_login']); ?></td>
                <td width="15%"><?php echo date_db_format_to_display_format($row['start_date']); ?></td>
                <td width="20%">
                    <span id="span_package_end_date_show_<?php echo $row['client_id']; ?>" style="display:block;">
                      <?php echo date_db_format_to_display_format($row['package_end_date']); ?>
                      <!-- <a href="JavaScript:void(0)" class="update_package_end_date" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                    </span>
                    <div class="row" id="div_package_end_date_edit_html_<?php echo $row['client_id']; ?>" style="display:none;">
                      <div class="col-md-12">
                        <input type="text" readonly="true" class="form-control display_date" id="package_end_date_<?php echo $row['client_id']; ?>" style="width:150px;" value="<?php echo date_db_format_to_display_format($row['package_end_date']); ?>" />
                        <a href="JavaScript:void(0)" class="text-primary package_end_date_update_confirm" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a> /
                        <a href="JavaScript:void(0)" class="text-danger package_end_date_close" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                      </div>
                    </div>                   
                </td>
                <td width="20%">
                    <span id="span_expire_date_show_<?php echo $row['client_id']; ?>" style="display:block;">
                      <?php echo date_db_format_to_display_format($row['expire_date']); ?>
                      <!-- <a href="JavaScript:void(0)" class="update_expiry_date" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                    </span>
                    <div class="row" id="div_expire_date_edit_html_<?php echo $row['client_id']; ?>" style="display:none;">
                      <div class="col-md-12">
                        <input type="text" readonly="true" class="form-control display_date" id="expire_date_<?php echo $row['client_id']; ?>" style="width:150px;" value="<?php echo date_db_format_to_display_format($row['expire_date']); ?>" />
                        <!-- <a href="JavaScript:void(0)" class="text-primary date_update_confirm" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a> /
                        <a href="JavaScript:void(0)" class="text-danger date_close" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-times" aria-hidden="true"></i></a> -->
                      </div>
                    </div> 
                </td>
                <td>
                    <span id="span_package_price_show_<?php echo $row['client_id']; ?>" style="display:block;">
                      <?php echo $row['package_price']; ?>
                      <!-- <a href="JavaScript:void(0)" class="update_package_price" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                    </span>
                    <div class="row" id="div_package_price_edit_html_<?php echo $row['client_id']; ?>" style="display:none;">
                      <div class="col-md-12">
                        <input type="text"  class="form-control" id="package_price_<?php echo $row['client_id']; ?>" style="width:150px;" value="<?php echo $row['package_price']; ?>" />
                        <a href="JavaScript:void(0)" class="text-primary package_price_update_confirm" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a> /
                        <a href="JavaScript:void(0)" class="text-danger package_price_close" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                      </div>
                    </div>  
                </td>
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
                        } ?>
                      
                      <br><a href="JavaScript:void(0)" title="<?php echo $row['call_type_name']; ?> Update" class="comment_update_row text-info" data-cid="<?php echo $row['client_id']; ?>" data-tid="<?php echo $row['call_type_id']; ?>" data-title="<?php echo $row['call_type_name']; ?>" data-scid="<?php echo $row['call_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a>
                    <?php } ?>



                <!-- <a href="JavaScript:void(0)" title="Update" class="comment_update_row" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a> -->
                <?php } ?>
                </td>
                <td>
                <?php if(trim($row['scheduled_call_datetime'])=='' || $row['call_status']==1){  ?>
                  <a href="JavaScript:void(0)" title="Create New Call" class="create_new_call text-info" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-plus-square" aria-hidden="true"></i></a> | 
                <?php } else { 
                  echo date_db_format_to_display_format($row['next_followup_date']).'<br><br>'; 
                } ?>
                <a href="JavaScript:void(0)" title="View Log" class="view_comment_list" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></a>
              </td>
            </tr>
            <?php $i++; } ?>
            <?php }else{ ?>
            <tr><td colspan="12">No Downloads List Found!</td></tr>
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