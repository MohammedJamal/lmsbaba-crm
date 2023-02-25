<table class="table table-bordered table-striped th_color">	
	<thead>
    <tr>
      <th style="width: 24px;">SL</th>
      <th style="width: 249px;">Product Name</th>
      <th style="width:150px;">Unit Sale Price</th>
      <th style="width:76px;">Unit</th>
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
      <tr id="product_row_<?=$i;?>" class="<?php echo ($i%2==0)?'event':'odd'; ?>">
        <td><?php echo $x;?></td> 
        <td><?php echo $output->name;?></td>
        <?php $price= $output->price*$output->unit;?>
        <td>
          <div class="form-group col-md-12 padding0">
              <input type="text" class="form-control calculate_quotation_price_update double_digit" id="unit_price_update_<?=$i;?>" name="unit_price[]" value="<?=$output->price;?>" data-field="price" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>"/>
          </div>
        </td>                        
        <td>
          <div class="form-group col-md-12 padding0">                          
            <input type="text" class="form-control calculate_quotation_price_update only_natural_number_noFirstZero" name="unit[]" id="unit_<?=$i;?>" value="<?php echo $output->unit?>" data-field="unit" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" />
          </div>
        </td>

                   
        <td>

            <input type="text" class="form-control calculate_quotation_price_update double_digit" id="disc_<?=$i;?>" name="disc[]" value="<?php echo $output->discount; ?>" data-field="discount" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" />

	      </td>

        <td>
            <div class="form-group col-md-12 padding0">
                <input type="text" class="form-control calculate_quotation_price_update double_digit" id="gst_<?=$i;?>" name="gst[]" value="<?=$output->gst;?>" data-field="gst" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" />
            </div>
        </td>    

        <td align="center">
          <div class="form-group col-md-12 padding0">  
              <span class="amount" id="g_total_update_<?=$output->product_id;?>"><?=$row_final_price;?></span>
          </div>
        </td>          
       
     	  <td> 
            <a href="JavaScript:void(0)" class="del_prod_update" data-id="<?=$output->id;?>" data-opportunityid="<?php echo $output->opportunity_id;?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

        </td>
			</tr>
      <?php
      
      $i++;
      $x++;
      }
      ?>

      <?php if(count($selected_additional_charges)){?>
      <?php 
        foreach($selected_additional_charges AS $charge)
        {
          $i++; 

          $item_gst_per= $charge->gst;
          $item_sgst_per= ($item_gst_per/2);
          $item_cgst_per= ($item_gst_per/2);  
          $item_discount_per=$charge->discount; 
          $item_price= $charge->price;
          $item_qty=1;

          $item_total_amount=($item_price*$item_qty);
          $row_discount_amount=$item_total_amount*($item_discount_per/100);
          $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

          $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
          $sub_total=$sub_total+$row_final_price; 
        ?>    
        <tr id="additional_charge_row_<?=$i;?>" class="<?php echo ($i%2==0)?'event':'odd'; ?>">
          <td style="width: 24px;"><?php echo $i; ?></td>
          <td style="width: 249px;"><?php echo $charge->additional_charge_name; ?></td>
          <td style="width:150px;">
            <div class="form-group col-md-12 padding0">
            <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit" value="<?php echo $charge->price; ?>" data-field="price" data-id="<?php echo $charge->id; ?>" data-opportunityid="<?php echo $opportunity_id; ?>" />
            </div>
          </td> 

          <td style="width:76px;">
            <div class="form-group col-md-12 padding0">
                <input type="text" class="form-control" value="1" readonly="true" />
            </div>
          </td>                            
          <td style="width: 100px;">    
            <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_discount" value="<?php echo $charge->discount; ?>"  data-field="discount" data-id="<?php echo $charge->id; ?>" data-opportunityid="<?php echo $opportunity_id; ?>" />
          </td>       

          <td style="width: 76px;">
              <div class="form-group col-md-12 padding0">
            <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_gst" value="<?php echo $charge->gst; ?>" data-field="gst" data-id="<?php echo $charge->id; ?>" data-opportunityid="<?php echo $opportunity_id; ?>" />
              </div>
          </td> 

          <td style="width:124px;" align="center">
              <div class="form-group col-md-12 padding0">       
            <span class="" id="row_total_additional_price_update_<?php echo $charge->id; ?>"><?php echo $row_final_price; ?></span>
              </div>
          </td>
          <td style="width:32px;">  
            <a href="JavaScript:void(0)" class="del_additional_charges_update" data-id="<?php echo $charge->id;?>" data-opportunityid="<?php echo $opportunity_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
          </td>
        </tr>
        <?php  } ?>
      <?php } ?>
      <tr>
          <td colspan="6" style="text-align:right;" class="back_border_total">
            Total Deal Value</td>
          <td colspan="2" class="back_border_total">
            <!-- <span class="currency_type_name_new amount"><?=$currency_name;?></span>  -->
            <span id="sub_total_update_<?php echo $opportunity_id; ?>"><?=$sub_total;?></span>
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

    var currency_type=$("#currency_type_update_<?php echo $opportunity_id; ?>").val();
    if(currency_type!=1)
    {
      $("[name='gst[]']").attr("readonly",true);
      $(".additional_charges_gst").attr("readonly",true);
    }
    else
    {
      $("[name='gst[]']").attr("readonly",false);
      $(".additional_charges_gst").attr("readonly",false);
    }
    // ===============================================
    // VALIDATION SCRIPT
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


        // Namutal number and first letter not zero
        $('.only_natural_number_noFirstZero').keyup(function(e)
        { 
        var val = $(this).val()
        var reg = /^0/gi;
        if (val.match(reg)) {
          $(this).val(val.replace(reg, ""));
          // alert("Please phone number first character bla blaa not 0!");
          // $(this).mask("999 999-9999");
        }
        else
        {
          if (/\D/g.test(this.value))
          {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
          }
        }          
        });
        // Namutal number and first letter not zero
        


    // VALIDATION SCRIPT
    // ===============================================



  });
</script>