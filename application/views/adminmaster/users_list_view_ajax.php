<div class="container">
  <h2>Users List </h2>
  <button style="float: left;" type="button" class="btn btn-info"><i class="fa fa-bars" aria-hidden="true"></i></button>

  <button style="float: left;" type="button" class="btn" id="show_tree_view"><i class="fa fa-sitemap" aria-hidden="true"></i></button>

  <button style="float: right;" type="button" class="btn btn-success" id="add_user"><i class="fa fa-plus" aria-hidden="true"></i> Add User </button>
       
  <table class="table table-bordered">
    <thead>
      <tr class="info bg-dark">
        <th class="text-center">SL No.</th>
        <th class="text-left">User Name</th>
        <th class="text-left">Manager</th>
        <th class="text-left">Email ID</th>
        <th class="text-left">Mobile Number</th>
        <th class="text-left">Menu Permission</th>
        <th class="text-left">Create Date</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
        <?php if(count($rows)){
          $i=1; ?>
            <?php foreach($rows AS $row){ ?>
            <tr>
                <td class="text-center"><?php echo $i; ?></td>
                <td class="text-left"><?php echo $row['name']; ?></td>
                <td class="text-left"><?php if(trim($row['manager_name'])==''){ echo 'NA'; } else { echo $row['manager_name']; } ?></td>
                <td class="text-left"><?php echo $row['email']; ?></td>
                <td class="text-left"><?php echo $row['mobile']; ?></td>
                <td class="text-left">
                <?php if($row['id']!=1){ echo $row['menu_permission']; } else { 
                  echo"ALL ACCESS";
                } ?>
                </td>
                <td class="text-left"><?php echo date_db_format_to_display_format($row['create_date']); ?></td>
                <td>
                  <?php if($row['id']!=1){ ?>
                    <a href="JavaScript:void(0)" title="Permission" class="permission_update_row text-success" data-uid="<?php echo $row['id']; ?>"><i class="fa fa-list" aria-hidden="true"></i></a>
                  
                  | <?php } ?>
                  <a href="JavaScript:void(0)" title="Edit" id="edit_user_view" class="text-primary" data-uid="<?php echo $row['id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                  <?php if($row['id']!=1){ ?>
                  |
                  <a href="JavaScript:void(0)" title="Delete" id="delete_user" class="text-danger" data-uid="<?php echo $row['id']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                  <?php } ?>
                </td>
            </tr>
            <?php $i++; } ?>
            <?php }else{ ?>
            <tr><td colspan="10">No User Found!</td></tr>
        <?php } ?>
    </tbody>
  </table>
  
</div>

