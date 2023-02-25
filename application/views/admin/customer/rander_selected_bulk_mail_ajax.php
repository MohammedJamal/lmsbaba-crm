<?php //print_r($rows); ?>
<?php if(count($rows))
{
	echo '<ul>';
	foreach($rows AS $row)
	{
	?>
	<li><?php echo $row->email; ?></li>
	<?php
	}
	echo '</ul>';
}
?>