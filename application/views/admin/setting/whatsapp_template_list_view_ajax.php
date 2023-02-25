<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
              <td><?php echo $row['whatsapp_api_name']; ?></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['template_id']; ?></td>      
              <td><?php echo ($row['template_variable'])?$row['template_variable']:'--'; ?></td>
              <td class="text-center" >
                <a href="JavaScript:void(0);" class="whatsapp_template_edit" data-id="<?php echo $row['id']; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
                <a href="JavaScript:void(0);" class="whatsapp_template_delete" data-id="<?php echo $row['id']; ?>" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
              </td>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>