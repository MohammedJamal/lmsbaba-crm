<?php 
// $product1_arr=[];
// $product2_arr=[];
$existing_tagged_arr=[];
// foreach($product_list AS $product)
// { 
// 	array_push($product1_arr, $product['name']);
// } 
// foreach($tagged_product_list AS $product2)
// { 
// 	array_push($product2_arr, $product2['name']);
// }
// $new_product_arr = array_unique (array_merge ($product1_arr, $product2_arr));
// foreach($existing_tagged AS $ps)
// {			
// 	array_push($existing_tagged_arr, $ps['product_id']);
// }
?>
<div class="form_bg">            
  <div class="middle_form">
    <form id="frmLeadAddTaggedPS" name="frmLeadAddTaggedPS" method="post" action="" class="new-lead-sec-from rounded-form"> 
    	<input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>"> 
		<div class="padding_35 full-l"> 
			<div class="form-group row">
				<?php /* ?>
				<div class="col-sm-12">
					<label class="full-label">Existing Product / Service<span class="text-danger">*</span>:</label>							
					<div class="lead-tag">
	            		<ul class="tags">
	            			<?php
	            			$existing_tagged_arr=[];
	            			$existing_tagged_str='';
	            			foreach($existing_tagged AS $ps)
	            			{			
	            			?>
  							<li>
  								<span class="tag"><?php echo $ps['name']; ?> </span>
  							</li>
  							<?php 
  							array_push($existing_tagged_arr, $ps['name']);
  							$existing_tagged_str .=''.$ps['name'].', ';
  							} 
  							$existing_tagged_str = rtrim($existing_tagged_str, ', ');
  							?>
  						</ul>
	            	</div>
				</div>
				<?php */ ?>
				<div class="col-sm-12">
					<label class="full-label">Product / Service Required<span class="text-danger">*</span>:</label>
					<select class="js-example-basic-multiple form-control sp-custom-select" name="product_tags[]" id="product_tags" multiple="multiple" >
						<?php /*foreach($product_list AS $product){ ?>
							<option value="<?php echo $product['id']; ?>" <?php if(in_array($product['id'], $existing_tagged_arr)){echo'selected';} ?>><?php echo $product['name']; ?></option>
						<?php }*/ ?>						
					</select>
					<div class="text-danger" id="lead_title_error"></div>
				</div>				
			</div>
		</div>		                  
      	<div class="col-sm-12 text-center">
        	<p id="sub_form" class="file in-p" >
            <input type="submit" value="" id="add_lead_tagged_ps_submit_confirm" />
            <label for="file" class="serach-btn">
            	<span class="btn-text">Save<span>             	
            </label>             
        	</p>
      	</div>
    </form>        
  </div>
</div> 
