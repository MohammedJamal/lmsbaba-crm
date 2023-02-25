  
                  
                  
                    <table class="table">
	
		<thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Products</th>
                                                            <th>Price (Rs)</th>
                                                            <th>Quantity</th>
                                                            <th>Sub-Total</th>
                                                            <th>Discount</th>
                                                            <th>GRAND TOTAL</th>
                                                            <th>&nbsp;</th>
                                                        </tr>
                                                    </thead>
	<tbody> 
   
                    <?php 
                    if($product_list)
                    {
					
                    $x=1;
                    $i=0;
                    $sub_total=0;
                     foreach($product_list as $output)
                    {
                    	if($temp_prod_id=='')
                    	{
							$temp_prod_id=$output->product_id.',';
						}
						else
						{
							$temp_prod_id=$temp_prod_id.$output->product_id.',';
						}
							
                    	?>
                    	
                    <tr id="product_row_<?=$i;?>">
                    	<td style="width: 24px;">                      
                          <?php echo $x;?>
                        </td>
                        
                        <td style="width: 249px;">                      
                          <?php echo $output->name;?>
                        </td>
                        <?php $price= $output->price*$output->unit;?>
                        <td style="width:76px;">
                          <div class="form-group col-md-12 padding0">
                          <input type="text" class="form-control" id="unit_price_update_<?=$i;?>" name="unit_price[]" value="<?=ceil($output->price);?>" onkeyup="calculate_price_update('unit_update_<?=$i;?>','unit_price_update_<?=$i;?>','total_update_<?=$i;?>','disc_update_<?=$i;?>','g_total_update_<?=$i;?>','<?=$output->product_id;?>','<?php echo $opportunity_id;?>','currency_type_update_<?php echo $opportunity_id;?>','unit_price_main_update_<?=$i;?>')" />
                          <input type="hidden" id="unit_price_main_update_<?=$i;?>" value="<?=$output->price;?>"  />
                          
                          </div>
                        </td>
                        
                        <td style="width:76px;">
                        <div class="form-group col-md-12 padding0">
                          
                          <input type="text" class="form-control" name="unit[]" id="unit_update_<?=$i;?>" value="<?php echo $output->unit?>" onkeyup="calculate_price_update('unit_update_<?=$i;?>','unit_price_update_<?=$i;?>','total_update_<?=$i;?>','disc_update_<?=$i;?>','g_total_update_<?=$i;?>','<?=$output->product_id;?>','<?php echo $opportunity_id;?>','currency_type_update_<?php echo $opportunity_id;?>','unit_price_main_update_<?=$i;?>')"/>
                          </div>
                        </td>
                         <?php
                        
						$tot_n = ($output->discount/100) * $price;
						
						$f_tot=$price - $tot_n;
						
						$sub_total=$sub_total+$f_tot;
                        ?>
                        <td style="width:76px;">
                          <div class="form-group col-md-12 padding0">
                          	
		                          
		                          <input type="text" class="form-control amount1" id="total_update_<?=$i;?>" name="total[]" value="<?=$f_tot;?>" readonly="true"/>
		                          </div>
                        </td>
                        
                        <td style="width: 76px;">
	                                              
		                          <input type="hidden" name="unit_type[]" value="<?=$output->unit_type;?>" id="unit_type_update_<?=$i;?>" class="form-control"/>
		                           
			                      <input type="hidden" name="unit_type_name[]" value="<?=$output->unit_type_name;?>" id="unit_type_name_update_<?=$i;?>" readonly="true" class="form-control"/>	
			                      
			                      <select id="disc_update_<?=$i;?>" name="disc[]" class="form-control" onchange="calculate_price_update('unit_update_<?=$i;?>','unit_price_update_<?=$i;?>','total_update_<?=$i;?>','disc_update_<?=$i;?>','g_total_update_<?=$i;?>','<?=$output->product_id;?>','<?php echo $opportunity_id;?>','currency_type_update_<?php echo $opportunity_id;?>','unit_price_main_update_<?=$i;?>')">
                          <?php
                          for($p=0;$p<=100;$p++)
                          {
						  
                          ?>
                          	<option value="<?=$p;?>"<?php if($p==$output->discount){?> selected="selected" <?php } ?>><?=$p;?>%</option>
                          <?php
                          	
						  }
						  ?>
                          </select>
		                         
	                     </td>
                        
                        
	                        
                        <td style="width:124px;">
                          <div class="form-group col-md-12 padding0">
                          	<input type="hidden" name="currency_type[]" value="<?=$output->currency_type;?>" id="currency_type_update_<?=$i;?>" class="all_currency_update"/>
		                          <input type="hidden" name="currency_type_name[]" value="<?=$output->currency_type_name;?>" id="currency_type_name_update_<?=$i;?>" readonly="true" class="form-control"/>
		                          
		                          
		                           <span class="all_currency_update_<?php echo $opportunity_id;?> amount">
		                          <?php
		                          if($output->currency_type_code!='')
                     				{
		                          ?>
		                          <?=$output->currency_type_code;?>
		                          	<?php
		                          	}
		                          	else
		                          	{
										echo $currency_name;
									}
		                          	?>
		                          </span>  
		                          <span class="" id="g_total_update_<?=$i;?>"><?=$f_tot;?></span>
		                          
		                          </div>
                        </td>
                        
                        
                        
                        
                       
                        
                      
                        
                       
                        
                         <!--<div class="form-group col-md-1 padding_right0">
                          <a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>                        
                        </div>-->
                       	<td style="width:32px;">
                          
                          <img style="cursor: pointer;" onclick="del_prod_update('<?=$output->product_id;?>','<?php echo $opportunity_id;?>')" src="<?php echo assets_url(); ?>images/trash.png" alt=""/>
                        </td>
                       
                              
                   

					</tr>

                    
                      <?php
                      if($output->currency_type_code!='')
                      {
					  	$currency_name=$output->currency_type_code;
					  }
                  $i++;
                  $x++;
                  }
                  ?>
                  <tr>
                  	
                  	<td colspan="6" style="text-align:right;" class="back_border_total">Total Deal Value</td>
                                                            <td colspan="2" class="back_border_total"><!--<span id="currency_name_prod_update"><?=$currency_name;?></span> <span id="sub_total_update"><?=$sub_total;?></span></td>-->
                                                            
                                                            <span id="currency_name_prod_tot_update_<?php echo $opportunity_id;?>"><?=$currency_name;?></span>
                                                            <span id="sub_total_update_<?php echo $opportunity_id;?>"><?=$sub_total;?></span></td>
                   
                   
                </tr>    
                  <?php
                  	
					}
					else
					{
						?>
						
                         <tr>
                  	
                  	<td colspan="8" style="text-align:right;" class="back_border_total">No products found
                  	
                  	</td>
                  	</tr>
                       
                        
                        <?php
					}
                  ?> 
                   
          </tbody>                   
	
</table>     
                   
             