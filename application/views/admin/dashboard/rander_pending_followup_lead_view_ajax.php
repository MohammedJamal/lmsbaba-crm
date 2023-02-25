<?php 
/*
if(count($rows)){ ?>
  <?php foreach($rows as $row){ ?>
  <div class="white-box">
    <h4><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/edit/<?php echo $row->id;?>"><?php echo $row->title; ?></a></h4>
    Date: <?php echo date_db_format_to_display_format($row->create_date); ?>
  </div>
  <?php } ?>
<?php }else{ ?>
  <div class="white-box">No Lead available.</div>
<?php }

*/
//print_r($user_wise_pending_followup_count);
?>
<div class="tholder dash-style round">
    <table class="table clock-table white-style">
      <thead>
        <tr>
          <th width="25%">Users</th>
          <th width="14.5%">All</th>
          <th width="14.5%">Today’s</th>
          <th width="15.4%">Yesterday's</th>
          <th width="15.3%">&gt;2 Days Old</th>
          <th width="15.3%">&gt;5 Days Old</th>
        </tr>
      </thead>
      <tbody>
      	<?php if(count($user_wise_pending_followup_count)){ ?>
	        <?php foreach($user_wise_pending_followup_count AS $pending){ ?>
	        <tr>
	          <td><?php echo $pending->assigned_user_name; ?></td>
	          <td><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?u=<?php echo $pending->assigned_user_id; ?>&pf=Y&pff="><?php echo $pending->total_pending_count; ?></a></td>
	          <td><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?u=<?php echo $pending->assigned_user_id; ?>&pf=Y&pff=today"><?php echo $pending->today_pending_count; ?></a></td>
	          <td><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?u=<?php echo $pending->assigned_user_id; ?>&pf=Y&pff=yesterday"><?php echo $pending->yesterday_pending_count; ?></a></td>
	          <td><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?u=<?php echo $pending->assigned_user_id; ?>&pf=Y&pff=twodaysbefore"><?php echo $pending->twoday_pending_count; ?></a></td>
	          <td><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?u=<?php echo $pending->assigned_user_id; ?>&pf=Y&pff=fivedaysbefore"><?php echo $pending->fiveday_pending_count; ?></a></td>
	        </tr>
	        <?php } ?>  
	      <?php }else{ ?>
	      <tr>
	        <td colspan="6">No Pending Followup..</td>
	      </tr>
	      <?php } ?> 
      </tbody>
    </table>    
  </div>
  