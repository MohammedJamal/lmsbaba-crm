
<table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Designation</th>
        <th>Birthday</th>
        <th>Marriage anniversary</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <tr class="bg-warning">
            <td><?php echo ($cus_data->contact_person)?$cus_data->contact_person:'-'; ?></td>
            <td><?php echo ($cus_data->email)?$cus_data->email:'-'; ?></td>
            <td><?php echo ($cus_data->mobile)?$cus_data->mobile:'-'; ?></td>
            <td><?php echo ($cus_data->designation)?$cus_data->designation:'-'; ?></td>
            <td><?php echo ($cus_data->dob)?date_db_format_to_display_format($cus_data->dob):'-'; ?></td>
            <td><?php echo ($cus_data->doa)?date_db_format_to_display_format($cus_data->doa):'-'; ?></td>
            <td align="center"> <a href="JavaScript:void(0);" title="Edit Contact Persion" class="edit_more_contact_persion_view" data-id="" data-actionTable="c" data-cid="<?php echo $customer_id;?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
        </tr>
        <?php if(count($contact_persion_list)){ ?>
            <?php foreach($contact_persion_list AS $row){ ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo ($row['email'])?$row['email']:'-'; ?></td>
                    <td><?php echo ($row['mobile'])?$row['mobile']:'-'; ?></td>
                    <td><?php echo ($row['designation'])?$row['designation']:'-'; ?></td>
                    <td><?php echo ($row['dob'])?date_db_format_to_display_format($row['dob']):'-'; ?></td>
                    <td><?php echo ($row['doa'])?date_db_format_to_display_format($row['doa']):'-'; ?></td>
                    <td align="center">
                      <a href="JavaScript:void(0);" title="Edit Contact Persion" class="edit_more_contact_persion_view" data-id="<?php echo $row['id'];?>" data-actionTable="cp" data-cid="<?php echo $customer_id;?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;
                      <a href="JavaScript:void(0);" title="Delete Contact Persion" class="delete_more_contact_persion_view" data-id="<?php echo $row['id'];?>" data-cid="<?php echo $customer_id;?>"><i class="fa fa-trash red" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <?php } ?>
        <?php } ?>
      
    </tbody>
  </table>
