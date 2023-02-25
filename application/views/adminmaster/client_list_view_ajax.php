<div class="">
  <h2>All Client List</h2>
  <p></p>  
  <input type="hidden" id="page_number" value="<?php echo $page_number;?>">
  <div class="bulk_bt_holder row" style="display: none;">
    <div class="col-md-8">
      <select class="form-control" name="assigne_user_id" id="assigne_user_id">
          <option value="" >===Select Any User to Assign===</option>             
          <?php if(count($user_list)){
                    foreach($user_list AS $user){ ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?> (<?php echo $user['email']; ?>)</option>
                <?php }
          } ?>
      </select>
    </div>
    <div class="col-md-4">
      <button type="button" style="float: left;" class="btn btn-success" id="client_assigne_change_multiple"><i class="fa fa-refresh" aria-hidden="true"></i> Bulk Assignee </button>
    </div>
  </div> 

  <table class="table table-bordered">
    <thead>
      <tr class="info bg-dark">
        <?php if(is_admin_element_permission_available(1,"assign_user")){ ?>
        <th>
          <label class="control control--checkbox">
            <input type="checkbox" value="all" name="client_all" class="js-check-all" id="client_all_checkbox">
            <div class="control__indicator"></div>
          </label>
        </th>
        <?php } ?>
        <th>SL No.</th>
        <th>Type</th>
        <th>Name</th>
        <th class="text-center">LMS ID</th>
        <th>User <br>Sold</th>
        <th>Not Logged<br>in Since</th>
        <th class="text-center">Start Date</th>
        <th class="text-center">End Date</th>
        <th class="text-center">Expiry Date</th>
        <th>Total Revenue</th>
        <th>Assigned To</th>
        <th>Client Activity<br>Status</th>
        <th>Last Touch</th>
        <th class="text-center" width="15%">Action</th>
      </tr>
    </thead>
    <tbody id="tcontent">
        <?php if(count($rows)){
          $activity_name_tc='';
          $activity_subname_tc='';
          $today_date=date("Y-m-d");

          $i=$sl_start; ?>
            <?php foreach($rows AS $row){ 
              
                $tbl_tr_bg='';
                $tbl_td_color='';
                if($row['is_account_active']=='N'){
                  $tbl_tr_bg='table-danger';
                  $tbl_td_color='text-white';
                } else {
                    $tbl_tr_bg='';
                    $tbl_td_color='';
                }
                ?>
            <tr class="<?php echo $tbl_tr_bg; ?>">
            <?php if(is_admin_element_permission_available(1,"assign_user")){ ?>
                <td <?php echo $tbl_td_color;?>>
                  
                <label class="control control--checkbox">
                  <input type="checkbox" value="<?php echo $row['client_id'];?>" name="checked_to_customer">
                </label>

                </td>
            <?php } ?>
                <td class="<?php echo $tbl_td_color;?>"><?php echo $i; ?></td>
                <td class="<?php echo $tbl_td_color;?>"><?php echo $row['account_type']; ?></td>
                <td class="<?php echo $tbl_td_color;?>" align="left" width="20%">
                <mark>COMPANY:</mark> <a href="https://app.lmsbaba.com/<?php echo $row['domain_name']; ?>" target="_blank"><?php echo $row['client_name'].''; ?></a><br>
                <?php if(trim($row['company'])!=''){ ?><mark>OWNER:</mark> <?php echo $row['company']; } ?>
              
              </td>

                <td class="<?php echo $tbl_td_color;?>" align="center" width="20%">
                <mark><?php echo $row['client_id']; ?></mark></td>
                
                <td class="<?php echo $tbl_td_color;?>"><a href="JavaScript:void(0)" title="View User List" class="sold_user_list text-primary" data-cid="<?php echo $row['client_id']; ?>"><?php echo $row['total_user_count']; ?></a></td>
                <td class="<?php echo $tbl_td_color;?>"><?php echo $row['not_logged_day']; ?> Days</td>
                <td class="<?php echo $tbl_td_color;?>" width="15%"><?php echo date_db_format_to_display_format($row['start_date']); ?></td>
                <td class="<?php echo $tbl_td_color;?>" width="20%">
                    <span <?php if($today_date>$row['package_end_date']) echo'class="text-danger"'; ?>>
                      <?php echo date_db_format_to_display_format($row['package_end_date']); ?>
                    </span>
                                      
                </td>
                <td class="<?php echo $tbl_td_color;?>" width="20%">
                    <span <?php if($today_date>$row['expire_date']) echo'class="text-danger"'; ?>>
                      <?php echo date_db_format_to_display_format($row['expire_date']); ?>
                    </span>
                </td>
                <td class="<?php echo $tbl_td_color;?>">
                    <span id="span_package_price_show_<?php echo $row['client_id']; ?>" style="display:block;">
                      <?php echo $row['package_price']; ?>
                    </span>
                </td>
                <td class="<?php echo $tbl_td_color;?>">
                <?php if(trim($row['assign_to_name'])==''){ ?>
                  NA<br>
                  <?php if(is_admin_element_permission_available(1,"assign_user")){ ?>
                  (<a href="JavaScript:void(0)" title="Add Assign" class="client_assigne_change_single text-success" data-cid="<?php echo $row['client_id']; ?>" data-exas_id="<?php echo $row['assigned_to_user_id']; ?>">Assign</a>) 
                  <?php } ?>
                <?php } else { 
                  echo $row['assign_to_name']; 
                  ?> <br>
                  <?php if(is_admin_element_permission_available(1,"assign_user")){ ?>
                  (<a href="JavaScript:void(0)" title="Change Assign" class="client_assigne_change_single text-primary" data-cid="<?php echo $row['client_id']; ?>" data-exas_id="<?php echo $row['assigned_to_user_id']; ?>">Change</a>)
                  <?php } ?>
                <?php  } ?>
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

                <td class="<?php echo $tbl_td_color;?>"><?php echo (($row['last_touch_day']!='')?$row['last_touch_day'].' Days':'Not Touch'); ?></td>
                <td class="<?php echo $tbl_td_color;?>">
                  <a href="JavaScript:void(0)" class="update_row" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                  <br>
                  <a href="<?php echo adminportal_url(); ?>client/detail/<?php echo $row['client_id']; ?>" class=""><i class="fa fa-eye" aria-hidden="true"></i></a>
                  <br>
                  <a href="JavaScript:void(0)" title="View Log" class="view_comment_list" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></a>
                </td>
            </tr>
            <?php $i++; } ?>
            <?php }else{ ?>
            <tr><td colspan="12">No Client Found!</td></tr>
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