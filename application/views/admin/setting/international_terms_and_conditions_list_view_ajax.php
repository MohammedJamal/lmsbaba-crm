<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
              <td><?php echo $row->name; ?></td>
              <td><?php echo $row->value; ?></td>
              <td class="text-center" >
                <a href="JavaScript:void(0);" class="iterms_edit icon-btn btn-secondary text-white" data-id="<?php echo $row->id; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
                <a href="JavaScript:void(0);" class="iterms_delete icon-btn btn-alert text-white" data-id="<?php echo $row->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>&nbsp;
                <a href="JavaScript:void(0);" class="iterms_copy_to_dterms icon-btn btn-warning text-white" data-id="<?php echo $row->id; ?>"  data-name="<?php echo $row->name; ?>" data-toggle="tooltip" title="Copy to T&C for Domestic Leads" data-placement="top"><i class="fa fa-clone" aria-hidden="true"></i></a>
              </td>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>