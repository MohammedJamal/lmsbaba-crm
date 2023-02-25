<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
            <td><?php echo $row['user_name']; ?></td>
            <td><?php echo $row['gmail_address']; ?></td>             
            <td class="text-center" >
            <a href="JavaScript:void(0);" class="user_gmail_edit" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
              <a href="JavaScript:void(0);" class="user_gmail_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
            </td>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>