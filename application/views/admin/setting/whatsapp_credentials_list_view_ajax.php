<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
            <td><?php echo $row['service_provider']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['sender']; ?></td>
            <!-- <td><?php //echo $row['apikey']; ?></td> -->
            <td class="text-center" >
            <a href="JavaScript:void(0);" class="whatsapp_template_add  icon-btn btn-warning text-white" data-id="<?php echo $row['id']; ?>" title="Add Template"><i class="fa fa-plus" aria-hidden="true" style=""></i></a>&nbsp;
            <a href="JavaScript:void(0);" class="whatsapp_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
            <a href="JavaScript:void(0);" class="whatsapp_delete icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
            </td>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>