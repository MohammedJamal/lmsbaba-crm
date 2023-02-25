<?php
foreach($image_list as $image_data)
{
?>
<div class="product_img_one">
  <span class="cross_icon_product_img" onclick="remove_image('<?php echo $image_data->id?>','<?php echo $image_data->product_id?>')"><i class="fa fa-times" aria-hidden="true"></i></span>
  <img src="<?=base_url('uploads/product/thumb/').$image_data->file_name;?>" alt="<?php echo $image_data->file_name;?>" title="<?php echo $image_data->file_name;?>" />
</div>
<?php
}
?>	
               