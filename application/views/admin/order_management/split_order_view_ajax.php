<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            
        <div id="pfi_product_list" style="width: 100%; clear: both;margin-top: 15px;display: inline-block;">
               <table class="table quotation-table table-borderless">
                  <thead>
                     <tr>
                        <th width="5%" class="text-center">Split</th>
                        <th width="50%">Product Description</th>
                        <th class="text-center">Order Quantity</th>
                        <th class="text-center">Split Order Quantity</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php                                          
                        if(count($prod_list))
                        {         
                          $i=0;
                          $s=1;                          
                          foreach($prod_list as $output)
                          {
                          ?>
                        <tr class="spacer"><td colspan="4"></td></tr>
                        <tr id="">
                            <td class="text-center">
                                <label class="blue-small-title lh-20">
                                    <label class="check-box-sec fl">
                                    <input class="styled-checkbox is_split" type="checkbox" value="Y" name="is_split[]" id="" data-sno="<?php echo $i; ?>"  data-po_pro_forma_invoice_product_id="<?php echo $output->id; ?>" data-p_id="<?php echo $output->product_id; ?>" >
                                    <span class="checkmark"></span>
                                    </label>                      
                                    &nbsp; &nbsp; <?php //echo $output->id; ?>
                                </label>                                 
                            </td>
                            <td><?php echo $output->product_name; ?></td>   
                            <td class="text-center">
                                <input type="text" class="default-input width-80" value="<?php echo ($output->split_quantity)?$output->split_quantity:$output->quantity; ?>" readonly="true" name="existing_qtn[]" id="existing_qtn_<?php echo $i; ?>" />  
                            </td>
                            <td>                                
                                <input type="text" class="default-input double_digit width-80 split_qtn" readonly="true" name="split_qtn[]" id="split_qtn_<?php echo $i; ?>" data-sno="<?php echo $i; ?>" value="0" />  
                            </td>
                        </tr>
                        <tr class="spacer"><td colspan="4"></td></tr>
                        <?php
                            $s++;
                            $i++;
                            }
                        }
                        ?> 
                        <tr class="spacer">
                            <td colspan="3"></td>
                            <td class="text-center">
                                <a href="JavaScript:void(0);" data-pfi="<?php echo $pfi; ?>" data-lowp="<?php echo $lowp; ?>" data-pfi_split_id="<?php echo $pfi_split_id; ?>" class="btn btn-primary txt-upper submit-bt" id="om_split_confirm" style="display:none">Save <i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>                    
                  </tbody>
               </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(".double_digit").keydown(function(e) {
        debugger;
        var charCode = e.keyCode;
        if (charCode != 8 && charCode != 37 && charCode != 39 && charCode != 46 && charCode != 9) {           
            if (!$.isNumeric($(this).val()+e.key)) {
                return false;
            }
        }
        return true;
    });
</script>