<div class="" id="" >
  <div class="">
	  <table id="" class="table">
		 <thead id="thead">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th><?php echo ($field_label)?$field_label:'Updated Field'; ?></td>
			</tr>
		 </thead>
		 <tbody class="t-contant-img">
			<?php 
			if(count($rows)>0) 
			{
			   foreach($rows as $row)  
			   { 
			?>
			   <tr id="tr_bulk_update_<?php echo $row['product_id']; ?>" class="">
				  <td>
					 <?php echo $row['id']; ?>
				  </td>
				  <td>
					 <?php echo $row['name']; ?>
				  </td>
				  <td>
					<input type="text" class="form-control update_input double_digit" name="field_<?php echo $row['product_id']; ?>" id="field_<?php echo $row['product_id']; ?>" placeholder="" value="<?php echo ($field)?$row[$field]:''; ?>" data-field="<?php echo $field; ?>" data-id="<?php echo $row['product_id']; ?>">
                    <small class="text-danger error_div" id="field_error_<?php echo $row['product_id']; ?>"></small>
					<small class="text-success success_div" id="field_success_<?php echo $row['product_id']; ?>"></small>
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
		 </tbody>
	  </table>
	  <div id="page" style=""></div>
   </div>
</div>

 
