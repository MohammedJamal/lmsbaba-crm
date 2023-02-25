<row class="row form-group" id="selected_p_sub_div_<?php echo $product['id']; ?>">
	
    <div class="col-md-2 text-left lh-45"><b><?php echo $product['name']; ?></b></div>
	<div class="col-md-4 text-center">
        <div class="rela-div">
            <span class="label-set">Renewal Amount</span>
            <input type="hidden" class="form-control" name="product_tags[]" value="<?php echo $product['id']; ?>">
            <input type="text" class="form-control double_digit" name="product_price_tags_<?php echo $product['id']; ?>" placeholder="" value="<?php echo $product['price']; ?>">
        </div>
		
	</div>
	<div class="col-md-2 text-left lh-45"><a href="JavaScript:void(0)" class="del_selected_p_div icon-btn-new btn-danger text-white" data-id="<?php echo $product['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
</row>
<script type="text/javascript">
$(".double_digit").keydown(function(e) {
        debugger;
        var charCode = e.keyCode;
        if (charCode != 8) {
            //alert($(this).val());
            if (!$.isNumeric($(this).val()+e.key)) {
                return false;
            }
        }
    return true;
  });
</script>