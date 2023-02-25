<div class="form-group">
	<?php
	if($cus_data)
	{
		?>
		<span class="tag_row" style="color: red;">Following match found with this email & mobile</span>
		<?php
	}
	?>
	<div class="tag_row2 ff">
		
		<label class="check-box-sec rounded">
                						<input class="n" type="radio" id="new" name="tag_rad" value="1" onclick="remove_attr()"/> 
                						<span class="checkmark"></span>
              						</label>
		Add a New Buyer
	</div>
	<?php
	$i=2;
	if($cus_data)
	{
		
		foreach($cus_data as $data)
		{
		?>
			<div class="tag_row ff">
				
				<label class="check-box-sec rounded">
                						<input type="radio" name="tag_rad" value="<?php echo $data->company_name?>" onclick="remove_attr()"/> 
                						<span class="checkmark"></span>
              						</label>
				Tag with <?php echo $data->company_name?>
			</div>
		<?php
		}
		
	}
	else
	{
		?>
		<span class="tag_row" style="color: red;">No match found with this email id</span>
		<?php
	}
	?>
</div>
