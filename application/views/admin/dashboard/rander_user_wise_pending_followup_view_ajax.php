<div class="tholder">
  <table class="table clock-table">
    <thead>
      <tr>
        <th width="26%">Users</th>
        <th width="14.8%">All</th>
        <th width="14.8%">Today’s</th>
        <th width="14.8%">Yesterday's</th>
        <th width="14.8%">>2 Days Old</th>
        <th width="14.8%">>5 Days Old</th>
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