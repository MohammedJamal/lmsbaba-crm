<div class="form-group">
<?php
if($cus_data)
{
	?>
	<span class="tag_row" style="color: red;">Following match found with this email & mobile</span>
	<?php
}
?>

<?php
$i=2;
if($cus_data)
{
	
	foreach($cus_data as $data)
	{

	?>
		<div class="tag_row ff">

			<label class="check-box-sec rounded">
				<input type="radio" name="tag_rad" value="<?php echo $data->company_name?>" onclick="remove_attr()" <?php if($data->id==$cus_id){?> checked="checked" <?php } ?>/> Tag with <?php echo $data->company_name;?>
				<span class="checkmark"></span>
			</label>
			<?php
			if($data->id==$cus_id)
			{
				echo '(Already Tagged)';
			}
			?>
		</div>
	<?php
	}
	
}
else
{
	?>
	<span class="tag_row" style="color: red;">No match found with this email & mobile</span>
	<?php
}
?>
<div class="form-group row tag_row" style="text-align: left;">
					<label class="col-sm-2 col-form-label">
					&nbsp;
					</label>
					<div class="col-sm-8"> 
					<div class="checkbox checkbox-primary">
                        <input class="styled" type="checkbox" name="tag_rad" id="new" name="option1" onclick="remove_attr()" value="1"/>
                        <label for="checkbox2">
                            Add a New Buyer
                        </label>
                    </div>
                    </div>
					</div>
</div>
                            
                       