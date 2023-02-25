<div class="container">
  <h2>Work Order List </h2>

  <?php if(is_admin_element_permission_available(10,"can_add")){ ?><button style="float: right;" type="button" class="btn btn-success" id="add_work_order"><i class="fa fa-plus" aria-hidden="true"></i> Add New Work Order </button><?php } ?>
       
  <table class="table table-bordered">
    <thead>
      <tr class="info bg-dark">
        <th class="text-center">SL<br>No.</th>
        <th class="text-left">Service Details</th>
        <th class="text-left">Company Details</th>
        <th class="text-left">Date Details</th>
        <th class="text-left">Email ID</th>
        <th class="text-center">Mobile<br>Number</th>
        <th class="text-left">Create Date</th>
        <th class="text-center">Approve<br>Status</th>
        <th class="text-center">Action</th>
        
      </tr>
    </thead>
    <tbody>
        <?php if(count($rows)){
          $i=1; ?>
            <?php foreach($rows AS $row){ ?>
            <tr>
                <td class="text-center"><?php echo $i; ?></td>
                <td class="text-left">
                  <b>Account Type:</b> <?php echo $row['account_type']; ?><br>
                  <b>Service Type:</b> <?php echo $row['service_type']; ?><br>
                  <b>Service Title:</b> <?php echo $row['service_title']; ?><br>
                  <b>No of User:</b> <?php echo $row['no_of_user']; ?><br>
                  <b>Lead ID:</b> <?php echo $row['lead_id']; ?><br>
                </td>
                <td class="text-left">
                  <b>Name:</b> <?php echo $row['company_name']; ?><br>
                  <b>Address:</b> <?php echo $row['company_address']; ?><br>
                  <b>Owner Name:</b> <?php echo $row['owner_name']; ?><br>
                  <b>Contact Person:</b> <?php echo $row['contact_person']; ?><br>
                </td>
                <td class="text-left">
                  <span class="text-primary">Start: <?php echo date_db_format_to_display_format($row['start_date']); ?><br></span>
                  <b class="text-warning">End: <?php echo date_db_format_to_display_format($row['end_date']); ?><br></b>
                  <span class="text-danger">Expiry:<?php echo date_db_format_to_display_format($row['expiry_date']); ?></span>
                </td>
                <td class="text-left"><?php echo $row['email_id']; ?></td>
                <td class="text-center"><?php echo $row['mobile_number']; ?></td>
                <td class="text-left"><?php echo date_db_format_to_display_format($row['created_at']); ?></td>
                <td class="text-center">
                  <?php 
                  if($row['approve_status']=='P') echo'<b class="text-primary">PENDING</b>';
                  elseif($row['approve_status']=='A') echo'<b class="text-success">APPROVED</b>';
                  elseif($row['approve_status']=='R') echo'<b class="text-danger">REJECTED</b>';
                  ?>
                </td>
                <td>
                <?php if(is_admin_element_permission_available(10,"can_edit")){ ?><a href="JavaScript:void(0)" title="Update" id="edit_user_view" class="text-primary" data-worder="<?php echo $row['id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><?php } ?>

                <?php if(is_admin_element_permission_available(10,"can_delete") && $row['approve_status']!='A'){ ?>
                  |
                  <a href="JavaScript:void(0)" title="Delete" id="delete_workorder" class="text-danger" data-uid="<?php echo $row['id']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                <?php } ?>
                </td>
            </tr>
            <?php $i++; } ?>
            <?php }else{ ?>
            <tr><td colspan="10">No Work Order Found!</td></tr>
        <?php } ?>
    </tbody>
  </table>
  
</div>

