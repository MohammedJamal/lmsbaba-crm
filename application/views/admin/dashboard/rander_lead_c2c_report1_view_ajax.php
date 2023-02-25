<?php 
if(count($rows))
{
   foreach($rows AS $row)
   { 
      
?>
      <tr>
         <td><?php echo $row->user_name; ?></td>
         <td><a href="javascript:void(0)" class="c2c_user_wise_modal" data-filter="all" data-userid="<?php echo $row->user_id; ?>"><?php echo $row->total_call_count; ?></a></td>
         <td><a href="javascript:void(0)" class="c2c_user_wise_modal" data-filter="outbound" data-userid="<?php echo $row->user_id; ?>"><?php echo $row->outbound_count; ?></a></td>
         <td><a href="javascript:void(0)" class="c2c_user_wise_modal" data-filter="inbound" data-userid="<?php echo $row->user_id; ?>"><?php echo $row->inbound_count; ?></a></td>
         <td><a href="javascript:void(0)" class="c2c_user_wise_modal" data-filter="success" data-userid="<?php echo $row->user_id; ?>"><?php echo $row->success_call_count; ?></a></td>
         <td><a href="javascript:void(0)" class="c2c_user_wise_modal" data-filter="fail" data-userid="<?php echo $row->user_id; ?>"><?php echo $row->fail_call_count; ?></a></td>
      </tr>
<?php
   }
}
else
{
?>
<tr>
   <td colspan="6">No Record Found!</td>
</tr>
<?php
}
?>