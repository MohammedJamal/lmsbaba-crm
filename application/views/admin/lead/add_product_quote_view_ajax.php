<?php 
$product1_arr=[];
$product2_arr=[];
$existing_tagged_arr=[];
foreach($product_list AS $product)
{ 
	array_push($product1_arr, $product['name']);
} 
foreach($tagged_product_list AS $product2)
{ 
	array_push($product2_arr, $product2['name']);
}
$new_product_arr = array_unique (array_merge ($product1_arr, $product2_arr));
foreach($existing_tagged AS $ps)
{			
	array_push($existing_tagged_arr, $ps['name']);
} 

?>
<div class="form_bg">            
  <div class="middle_form">
    <form id="frmAddProductQuote" name="frmAddProductQuote" method="post" action="" class="new-lead-sec-from rounded-form"> 
    	<input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>"> 
		<div class="padding_35 full-l"> 
			<div class="form-group row">				
				<div class="col-sm-12">
					<label class="full-label">Product / Service Required<span class="text-danger">*</span>:</label>
					<select class="js-example-basic-multiple form-control sp-custom-select" name="product_quote[]" id="product_quote" multiple="multiple" >
						<?php foreach($product_list AS $product){ ?>
							<option value="<?php echo $product['id']; ?>" ><?php echo $product['name']; ?></option>
						<?php } ?>						
					</select>
					<div class="text-danger" id="product_quote_error"></div>
				</div>				
			</div>
		</div>		                  
      	<div class="col-sm-12 text-center">
        	<p id="sub_form" class="file in-p" >
            <input type="submit" value="" id="<?php echo ($is_mail_or_whatsapp=='mail')?'add_product_quote_submit_confirm':'add_product_quote_for_whatsapp_submit_confirm'; ?>" />
            <label for="file" class="serach-btn">
            	<span class="btn-text">Add Quote<span>             	
            </label>             
        	</p>
      	</div>
    </form>        
  </div>
</div> 
<input type="hidden" id="is_mail_or_whatsapp" value="<?php echo $is_mail_or_whatsapp; ?>">
