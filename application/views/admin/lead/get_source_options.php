<option value="">Select</option>
<?php foreach($source_list as $source){ ?>
<option value="<?php echo $source->id;?>" <?php if($tmp_com_source_id==$source->id){echo"SELECTED";} ?> ><?php echo $source->name;?></option>
<?php } ?>