<?php 
if(count($rows)>0) 
{
   foreach($rows as $row)  
   { 
?>
   <tr id="tr_<?php echo $row['product_id']; ?>" class="list_tr">
      <td>
         <?php echo $row['id']; ?>
      </td>
      <td>
         <a href="JavaScript:void(0);" class="get_pdetails get_detail_modal" data-id="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
      </td>
      <td><?php echo $row['code']; ?></td>
      <td><?php echo $row['curr_code'].' '.$row['price']; ?></td>
      <td><?php echo $row['unit']; ?></td>
      <td><?php echo $row['unit_type_name']; ?></td>
      <td><?php echo ($row['gst_percentage'])?$row['gst_percentage'].'%':'-'; ?></td>
      <td><?php echo ($row['images'])?'Yes':'No'; ?></td>
      <td><?php echo ($row['brochures'])?'Yes':'No'; ?></td>
      <td class="actions" align="center"> 
         <?php 
         if($row['status']==0)
         {
            $icon='fa fa-unlock-alt';
            $status_style="";
            $is_enable_disable_text="Disable";
         }
         else
         {
            $icon='fa fa-lock';
            $status_style="color: red !important";
            $is_enable_disable_text="Enable";
         }
         ?>
         <a href="JavaScript:void(0);" class="get_pdetails-- get_detail_modal" data-id="<?php echo $row['id']; ?>" data-toggle="tooltip" title="View Details"><i class="fa fa-search" aria-hidden="true"></i></a>&nbsp;
         <?php if(is_permission_available('edit_product_non_menu')){ ?>
         <a href="JavaScript:void(0);" class="change_status" data-id="<?php echo $row['id']; ?>" data-curstatus="<?php echo $row['status']; ?>" data-toggle="tooltip" title="<?php echo $is_enable_disable_text; ?> Product"><i class="<?php echo $icon;?>" aria-hidden="true" style="<?php echo $status_style; ?>"></i></a>&nbsp;
         <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/product/edit/<?php echo $row['product_id']; ?>" class="" data-original-title="Edit" data-toggle="tooltip" title="Edit Product" ><i class="fa fa-pencil"></i></a>&nbsp;
         <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/product/copy/<?php echo $row['product_id']; ?>" title="Make a Copy" class=""><i class="fa fa-clone" aria-hidden="true"></i></a>&nbsp;
         <a href="JavaScript:void(0);"  class="del_btn" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true" style="color: red !important"></i></a>      
         <?php } ?>
      </td>
   </tr>
<?php 
   } 
}
else
{
	echo'<tr><td colspan="7">No Record Found..</td></tr>';
}
?>