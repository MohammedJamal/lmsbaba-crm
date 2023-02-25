<?php if(count($rows)){ ?>
    <?php foreach($rows as $row){ ?>
      <tr class="<?php echo ($row['is_read']=='Y')?'read':'unread' ?>" id="block_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>">
      <td class="" align="center"><?php echo $row['date']; ?></td>        
        <td class="" align="center"><?php echo $row['h_subject']; ?></td>
        <td class="" align="center"><?php echo $row['h_from_personal']; ?></td>
        <td class="" align="center">--</td>
        <td class="" align="center">--</td>
        <td class="" align="center">--</td>
        <td data-title="Action" class="text-center" width="100"> </td>        
      </tr>
    <?php } ?>
  <?php }else{ ?>
    <tr class="unread" id="block_<?php echo $row['id']; ?>">
        <td class="text-center" colspan="3">No mail available!</td>
      </tr>
  <?php } ?>
  <script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
  
});
</script>