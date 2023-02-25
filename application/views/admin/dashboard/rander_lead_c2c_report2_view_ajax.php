<?php 
if(count($rows))
{
   foreach($rows AS $row)
   { 
?>
      <tr>
         <td><?php echo date_db_format_to_display_format($row->created_at); ?></td>
         <td><a href="javascript:void(0)" class="c2c_date_wise_modal" data-filter="all" data-date="<?php echo $row->created_at; ?>"><?php echo $row->total_call_count; ?></a></td>
         <td><a href="javascript:void(0)" class="c2c_date_wise_modal" data-filter="outbound" data-date="<?php echo $row->created_at; ?>"><?php echo $row->outbound_count; ?></a></td>
         <td><a href="javascript:void(0)" class="c2c_date_wise_modal" data-filter="inbound" data-date="<?php echo $row->created_at; ?>"><?php echo $row->inbound_count; ?></td>
         <td><a href="javascript:void(0)" class="c2c_date_wise_modal" data-filter="success" data-date="<?php echo $row->created_at; ?>"><?php echo $row->success_call_count; ?></a></td>
         <td><a href="javascript:void(0)" class="c2c_date_wise_modal" data-filter="fail" data-date="<?php echo $row->created_at; ?>"><?php echo $row->fail_call_count; ?></a></td>
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