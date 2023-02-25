<table class="table quotation-table table-borderless">
  <thead>
    <tr>
      <th width="25%">Product Description</th>
      <th class="text-center">Unit Price <br>
        <select id="change_currency_type_inv" class="form-control color-select small-select width-60" name="change_currency_type_inv">
            <option value="">Select</option>
            <?php foreach($currency_list as $currency_data) { ?>
            <option value="<?php echo $currency_data->id;?>" <?php if($po_inv_info->currency_type==$currency_data->code){ ?>selected="selected"
            <?php } ?>>
            <?php echo $currency_data->code;
            ?>
            </option>
            <?php }  ?>
          </select>
        </th>        
        <th class="text-center">Quantity</th>
        <th class="text-center">Discount<br>
          <select id="is_discount_p_or_a_inv" class="form-control color-select small-select width-60" name="is_discount_p_or_a_inv" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>"> 
            <option value="P" <?php if($prod_list[0]->is_discount_p_or_a=='P'){echo'SELECTED';} ?>>%</option>
            <option value="A" <?php if($prod_list[0]->is_discount_p_or_a=='A'){echo'SELECTED';} ?>>Amt.</option>
          </select>
        </th>
        <th class="text-center">GST (%)</th>
        <th class="text-center" width="14%">Net Amount</th>
     </tr>
  </thead>
  <tbody>
    <?php 
    $is_product_image_available='N';
    $is_product_brochure_available='N'; 
    $img_flag=0;
    $brochure_flag=0;                  
    if(count($prod_list))
    {         
      $i=0;
      $sub_total=0;
      $s=1;
      $discount=0;
      $item_gst_per=0;
      $item_sgst_per=0;
      $item_cgst_per=0;
      $total_price=0;
      $total_discounted_price=0;
      $total_tax_price=0;
      foreach($prod_list as $output)
      {
        $item_gst_per= $output->gst;
        $item_sgst_per= ($item_gst_per/2);
        $item_cgst_per= ($item_gst_per/2); 
        $item_is_discount_p_or_a=$output->is_discount_p_or_a; 
        $item_discount=$output->discount; 
        $item_unit= $output->unit;
        $item_price=($output->price/$item_unit);
        $item_qty=$output->quantity;

        $item_total_amount=($item_price*$item_qty);
        if($item_is_discount_p_or_a=='A'){
          $row_discount_amount=$item_discount;
        }
        else{
          $row_discount_amount=$item_total_amount*($item_discount/100);
        }

        
        $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

        $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
        $sub_total=$sub_total+$row_final_price;

        $total_price=$total_price+$item_total_amount;
        $total_discounted_price=$total_discounted_price+$row_discount_amount;
        $total_tax_price=$total_tax_price+$row_gst_amount;

        if($output->image!='' && $img_flag==0)
        {
          $is_product_image_available='Y';
          $img_flag=1;
        }
        if($output->brochure!='' && $brochure_flag==0)
        {
          $is_product_brochure_available='Y';
          $brochure_flag=1;
        }

      ?>
      <tr class="spacer"><td colspan="6"></td></tr>
      <tr id="inv_tr_<?php echo $output->id;?>">
          <td>
              <textarea class="basic-wysiwyg-editor" id="<?php echo 'po_inv_product_name#'.$output->id;?>" style="height: 300px;"><?php echo $output->product_name; ?></textarea>

            <!-- <input type="text" class="default-input calculate_inv_price_update" name="" id="" value="<?php echo $output->product_name; ?>" data-field="product_name" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>"  data-inv_id="<?php echo $output->po_invoice_id; ?>"/> -->
            <!-- <span class="inv_unit_type"><?php //echo ($output->product_sku)?'( Code: '.$output->product_sku.' )':'';?></span> -->
          </td>
          <td>
            <div class="padding0 d-flex">
              <input type="text" class="width-80 default-input calculate_inv_price_update double_digit" id="" name="" value="<?php echo $output->price;?>" data-field="price" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
                <!-- <span class="unit_type"> 
                  <?php //echo ($output->unit_type)?'per '.$output->unit_type.'':'';?></span>-->
                <span class="per-span">Per</span>
                <input type="text" class="default-input calculate_inv_price_update only_natural_number_noFirstZero ml-10 width-60" name="" id="" value="<?php echo $output->unit?>" data-field="unit" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" style="min-width: 50px;" />
                <select id="" class="default-select width-80 ml-10 calculate_inv_price_update" name="" data-field="unit_type" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>">
                  <option value="">Select</option>
                  <?php foreach($unit_type_list as $unit_type) { ?>
                  <option value="<?php echo $unit_type->type_name;?>" <?php if($output->unit_type==$unit_type->type_name){ ?>selected="selected"
                  <?php } ?>>
                  <?php echo $unit_type->type_name;
                  ?>
                  </option>
                  <?php }  ?>
                </select>
            </div>
          </td>
          <td>
             <input type="text" class="default-input calculate_inv_price_update double_digit width-80" id="" name="" value="<?php echo $output->quantity; ?>"  data-field="quantity" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>"   />  
          </td>
          <td>
             <input type="text" class="default-input calculate_inv_price_update double_digit width-60" id="" name="" value="<?php echo $output->discount; ?>" data-field="discount" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>"   />  
          </td>
          <td>             
              <input type="text" class="default-input calculate_inv_price_update double_digit width-60" id="" name="" value="<?=ceil($output->gst);?>" data-field="gst" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>"  />
          </td>
          <td>
                <!-- <input type="hidden" name="inv_currency_type[]" value="<?=$output->currency_type;?>" id="inv_currency_type_update_<?=$i;?>" class="form-control"/> -->
                <span class="" id="inv_total_sale_price_<?=$output->id;?>"><?php echo number_format($row_final_price,2);?></span>
          
            <a href="JavaScript:void(0)" class="del_inv_product" data-id="<?=$output->id;?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" data-pid="<?=$output->product_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
          </td>
      </tr>
      <tr class="spacer"><td colspan="6"></td></tr>
      <?php
      $s++;
      $i++;
      }
    }
    ?>

    <?php if(count($additional_charges_list)){?>
    <?php 
      $j=0;
      foreach($additional_charges_list AS $charge)
      {
        $i++; 
        $item_gst_per= $charge->gst;
        $item_sgst_per= ($item_gst_per/2);
        $item_cgst_per= ($item_gst_per/2);
        $item_is_discount_p_or_a=$charge->is_discount_p_or_a;  
        $item_discount_per=$charge->discount; 
        $item_price= $charge->price;
        $item_qty=1;

        $item_total_amount=($item_price*$item_qty);
        if($item_is_discount_p_or_a=='A'){
          $row_discount_amount=$item_discount_per;
        }
        else{
          $row_discount_amount=$item_total_amount*($item_discount_per/100);
        }
        // $row_discount_amount=$item_total_amount*($item_discount_per/100);
        $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

        $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
        $sub_total=$sub_total+$row_final_price; 

        $total_price=$total_price+$item_total_amount;
        $total_tax_price=$total_tax_price+$row_gst_amount;
        $total_discounted_price=$total_discounted_price+$row_discount_amount;
      ?>    
      <tr id="inv_tr_additional_charge_<?php echo $charge->id;?>" class="<?php echo ($j%2==0)?'event':'odd'; ?>">
        <td>
          <textarea class="basic-wysiwyg-editor" id="<?php echo 'po_inv_additional_charge_name#'.$charge->id;?>" style="height: 300px;"><?php echo $charge->additional_charge_name; ?></textarea>
          <!-- <input type="text" class="default-input calculate_inv_additional_charges_price_update " value="<?php echo $charge->additional_charge_name; ?>" data-field="additional_charge_name" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" /> -->
          <?php //echo $charge->additional_charge_name; ?>
        </td>
        <td>
          <div class="padding0 d-flex">
          <input type="text" class="width-80 default-input calculate_inv_additional_charges_price_update double_digit" value="<?php echo $charge->price; ?>" data-field="price" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
            <span class="per-span">Per</span>
            <input type="text" class="default-input ml-10 width-60" value="-" readonly="true" />
            <input type="text" class="default-select width-80 ml-10" value="-" readonly="true" />
          </div>
        </td> 

        
        <td>
          <input type="text" class="default-input width-80" value="-" readonly="true" />
        </td>                            
        <td>    
          <input type="text" class="default-input calculate_inv_additional_charges_price_update double_digit inv_additional_charges_discount width-60" value="<?php echo $charge->discount; ?>"  data-field="discount" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
        </td>       

        <td>
            
          <input type="text" class="default-input calculate_inv_additional_charges_price_update double_digit inv_additional_charges_gst width-60" value="<?php echo $charge->gst; ?>" data-field="gst" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
        </td> 

        <td align="center">
          <span class="" id="inv_additional_charges_total_sale_price_<?php echo $charge->id; ?>"><?php echo number_format($row_final_price,2); ?></span>
         
          <a href="JavaScript:void(0)" class="del_inv_additional_charges_update" data-id="<?php echo $charge->id;?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" data-pid="<?=$output->product_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
        </td>
      </tr>
      <tr class="spacer"><td colspan="6"></td></tr>
      <?php $j++; } ?>
      <?php } ?>                     
      <tr>
          <td colspan="5" style="text-align:right;" class="back_border_total bt">Total Net Amount</td>
          <td colspan="1" class="back_border_total text-center">
            <span id="inv_total_deal_value" class="bt"><?php echo number_format($sub_total,2);?></span>
            <input type="hidden" name="inv_deal_value" id="inv_deal_value" value="<?=number_format($sub_total,2);?>">
          </td>
      </tr>
  </tbody>
</table>