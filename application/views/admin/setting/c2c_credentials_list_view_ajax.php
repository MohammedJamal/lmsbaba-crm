<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
              <td><?php echo $row['user_id']; ?></td>
              <td><?php echo $row['caller_name']; ?></td>
              <td><?php echo $row['personal_no']; ?></td>
              <td><?php echo $row['office_no']; ?></td>
              <?php  ?>
               <td class="text-center" >
              <a href="JavaScript:void(0);" class="c2c_edit" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
                <a href="JavaScript:void(0);" class="c2c_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
              </td> 
              <?php  ?>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>