<div class="">
  <h2>My Client List</h2>
  <p></p>  
  
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
        <th>SL No.</th>
        <th>Type</th>
        <th>Company</th>
        <th>User Sold</th>
        <th>Last Login</th>
        <th class="text-center">Start Date</th>
        <th class="text-center">End Date</th>
        <th class="text-center">Expiry Date</th>
        <th>Assigned To</th>
        <th>Client Activity<br>Status</th>
        <th class="text-center" width="15%">Action</th>
      </tr>
    </thead>
    <tbody id="tcontent">
        <?php if(count($rows)){
          
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
                <td class="<?php echo $tbl_td_color;?>"><?php echo $i; ?></td>
                <td class="<?php echo $tbl_td_color;?>"><?php echo $row['account_type']; ?></td>
                <td class="<?php echo $tbl_td_color;?>" align="left" width="20%"> <mark>COMPANY:</mark> <a href="https://app.lmsbaba.com/<?php echo $row['domain_name']; ?>" target="_blank"><?php echo $row['client_name'].''; ?></a><br>
                <?php if(trim($row['company'])!=''){ ?><mark>OWNER:</mark> <?php echo $row['company']; } ?>
                <br><mark>LMS ID: <?php echo $row['client_id']; ?></mark></td>
                
                <td class="<?php echo $tbl_td_color;?>"><a href="JavaScript:void(0)" title="View User List" class="sold_user_list text-primary" data-cid="<?php echo $row['client_id']; ?>"><?php echo $row['total_user_count']; ?></a></td>
                <td class="<?php echo $tbl_td_color;?>"><?php echo date_db_format_to_display_format($row['last_login']); ?></td>
                <td class="<?php echo $tbl_td_color;?>" width="15%"><?php echo date_db_format_to_display_format($row['start_date']); ?></td>
                <td class="<?php echo $tbl_td_color;?>" width="20%">
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
                <td class="<?php echo $tbl_td_color;?>" width="20%">
                    <span id="span_expire_date_show_<?php echo $row['client_id']; ?>" style="display:block;">
                      <?php echo date_db_format_to_display_format($row['expire_date']); ?>
                    </span>
                    <div class="row" id="div_expire_date_edit_html_<?php echo $row['client_id']; ?>" style="display:none;">
                      <div class="col-md-12">
                        <input type="text" readonly="true" class="form-control display_date" id="expire_date_<?php echo $row['client_id']; ?>" style="width:150px;" value="<?php echo date_db_format_to_display_format($row['expire_date']); ?>" />
                      </div>
                    </div> 
                </td>
                <td class="<?php echo $tbl_td_color;?>">
                <?php if(trim($row['assign_to_name'])==''){
                    echo'NA';
                } else { 
                    echo $row['assign_to_name']; 
                } ?>
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
                
                <td class="<?php echo $tbl_td_color;?>">
                <!-- <a href="JavaScript:void(0)" title="Update" class="comment_update_row" data-cid="<?php echo $row['client_id']; ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i></a>
                  | -->
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