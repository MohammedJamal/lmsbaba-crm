
<option value="">-- Select <?php echo ($is_group=='Y')?'Group':'Category';?> --</option>
<?php
if(count($option_list))
{
    foreach($option_list as $cat)
    {
?>
    <option value="<?php echo $cat->id; ?>" <?php if($selected_id){ echo ($selected_id==$cat->id)?'SELECTED':'';} ?> data-text="<?php echo $cat->name; ?>"><?php echo $cat->name; ?></option>
<?php
    }
}
?>