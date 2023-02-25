<table class="table">	
	<thead>
    <tr>
      <th style="width: 24px;">SL</th>
      <th style="width: 249px;">Product Name</th>
      <th style="width:150px;">Unit Sale Price</th>
      <th style="width:76px;">Qtn.</th>
      <th style="width:100px;">Discount (%)</th>
      <th style="width: 76px;">GST (%)</th>
      <th style="width:124px;">Total Sale Price</th>
      <th style="width:32px;">&nbsp;</th>
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

      $item_gst_per= $output->gst;
      $item_sgst_per= ($item_gst_per/2);
      $item_cgst_per= ($item_gst_per/2);  
      $item_discount_per=$output->discount; 
      $item_price= $output->price;
      $item_qty=$output->unit;

      $item_total_amount=($item_price*$item_qty);
      $row_discount_amount=$item_total_amount*($item_discount_per/100);
      $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
      
      $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
      $sub_total=$sub_total+$row_final_price;	
      ?>                    	
      <tr id="product_row_<?=$i;?>">
        <td><?php echo $x;?>8</td>
        <td><?php echo $output->name;?></td>
        <?php $price= $output->price*$output->unit;?>
        <td>
          <div class="form-group col-md-12 padding0">
              <input type="text" class="form-control calculate_quotation_price double_digit" id="unit_price_<?=$i;?>" name="unit_price[]" value="<?=$output->price;?>" data-field="price" data-pid="<?php echo $output->product_id; ?>" />
          </div>
        </td>                        
        <td>
          <div class="form-group col-md-12 padding0">                          
            <input type="text" class="form-control calculate_quotation_price only_natural_number_noFirstZero" name="unit[]" id="unit_<?=$i;?>" value="<?php echo $output->unit?>" data-field="unit" data-pid="<?php echo $output->product_id; ?>"/>
          </div>
        </td>

        <?php
        // $tot_n = ($output->discount/100) * $price;
        // $tot_gst = ($output->gst/100) * $price;
        // $f_tot=($price-$tot_n)+$tot_gst;        
        // $sub_total=$sub_total+$f_tot;
        ?>            
        <td>

            <input type="text" class="form-control calculate_quotation_price double_digit" id="disc_<?=$i;?>" name="disc[]" value="<?php echo $output->discount; ?>" data-field="discount" data-pid="<?php echo $output->product_id; ?>" />

	      </td>

        <td>
            <div class="form-group col-md-12 padding0">
                <input type="text" class="form-control calculate_quotation_price double_digit" id="gst_<?=$i;?>" name="gst[]" value="<?=$output->gst;?>" data-field="gst" data-pid="<?php echo $output->product_id; ?>" />
            </div>
        </td>    

        <td>
          <div class="form-group col-md-12 padding0" align="center">          	
              <!-- <span class="currency_type_name_new amount"><?=$output->currency_type_code;?></span>  -->
              <span class="amount" id="g_total_<?=$output->product_id;?>"><?php echo number_format($row_final_price,2);?></span>
          </div>
        </td>          
       
     	  <td> 

            <img style="cursor: pointer;" onclick="del_prod(<?=$output->product_id;?>)" src="<?php echo assets_url(); ?>images/trash.png" alt=""/>

        </td>
			</tr>
      <?php
      //$currency_name=$output->currency_type_code;
      $i++;
      $x++;
      }
      ?>
      <tr>
          <td colspan="6" style="text-align:right;" class="back_border_total">
            Total Deal Value</td>
          <td colspan="2" class="back_border_total">
            <!-- <span class="currency_type_name_new amount"><?=$currency_name;?></span>  -->
            <span id="sub_total"><?php echo number_format($sub_total,2);?></span>
            <input type="hidden" name="deal_value" id="deal_value" value="<?=$sub_total;?>">
          </td>
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
<script type="text/javascript">
  $(document).ready(function(){
    var currency_type=$("#currency_type_new").val();
    if(currency_type!=1)
    {
      $("[name='gst[]']").attr("readonly",true);
    }
    else
    {
      $("[name='gst[]']").attr("readonly",false);
    }
  });
</script>