<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
            <td><?php echo $row['name']; ?><?php if($row['info']){ ?><br><small class="text-success"><i>( <?php echo $row['info']; ?> )</i></small> <?php } ?></td>
            <td><?php echo ($row['is_system_generated']=='Y')?'Default':'Custom'; ?></td>
            <td class="text-center" >
            <?php if($row['is_system_generated']=='N'){ ?>
            <a href="JavaScript:void(0);" class="key_performance_indicator_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
            <a href="JavaScript:void(0);" class="key_performance_indicator_delete icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
            <?php }else{echo'N/A';} ?>
            </td>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>