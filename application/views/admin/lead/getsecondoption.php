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
if($cus_data!='')
{
	
	foreach($cus_data as $data)
	{

	?>
	
		<div class="row tag_row" style="text-align: left;">
					<label class="col-sm-2 col-form-label">
					&nbsp;
					</label>
				<div class="col-sm-8"> 
					<div class="checkbox checkbox-primary">	                   
	                    <input class="styled" type="checkbox" name="option1" value="<?php echo $data->company_name?>"/>
	                    <label for="checkbox2">
	                        Tag with <?php echo $data->company_name?>
	                    </label>
	                </div>
                </div>
			</div>
	<?php
	}
	
}
else
{
	?>
	<span class="tag_row" style="color: red;">No match found with this mobile</span>
	<?php
}
?>
<div class="form-group row tag_row">
					<label class="col-sm-2 col-form-label">
					&nbsp;
					</label>
					<div class="col-sm-8"> 
					<div class="checkbox checkbox-primary">
                        <input class="styled" type="checkbox" id="new" name="option1" onclick="setnewcus()" value="1"/>
                        <label for="checkbox2">
                            Add a New Buyer
                        </label>
                    </div>
                    </div>
					</div>
</div>
                            
                       