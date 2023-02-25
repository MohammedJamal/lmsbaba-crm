<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
              <td><?php echo $row['account_name']; ?></td>
              <td><?php echo $row['userid']; ?></td>
              <td><?php echo $row['assign_to_str']; ?></td>
              <td class="text-center" >
              <a href="JavaScript:void(0);" class="ei_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
                <a href="JavaScript:void(0);" class="ei_delete  icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
              </td>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>