<?php
//print_r($rows);
if(count($rows)>0)
{
    foreach($rows as $row) 
    { 
?>
    <tr id="tr_<?php echo $row->id; ?>">   
        <td>
            <div class="checkbox checkbox-inline">
                <input type="checkbox" id="chk_delete_<?php echo $row->id; ?>" value="<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>" class="set_individual" >
                <label for="chk_delete_<?php echo $row->id; ?>"><?php //echo $row['product_id']; ?></label>
            </div>
        </td>            
        <td>
            <b><?php echo $row->vendor_company_name; ?></b>
            <br><small><?php echo $row->vendor_contact_person; ?> <br><?php echo $row->vendor_mobile; ?> <br><?php echo $row->vendor_email; ?></small>
        </td>
        <td id="price_div_<?php echo $row->id; ?>">
            <div id="ptb_price_html_<?php echo $row->id; ?>"><?php echo $row->price; ?></div>
            <div style="display: none;" id="ptb_price_input_html_<?php echo $row->id; ?>">
                <input type="text" max="10" class="form-control double_digit" id="ptb_price_<?php echo $row->id; ?>" placeholder="Price" value="<?php echo $row->price; ?>" />
            </div>                
        </td>
        <td id="currency_div_<?php echo $row->id; ?>">
            <div id="ptb_currency_type_html_<?php echo $row->id; ?>"><?php echo $row->curr_name; ?></div>
            <div style="display: none;" id="ptb_currency_type_input_html_<?php echo $row->id; ?>">
                <select class="form-control" id="ptb_currency_type_<?php echo $row->id; ?>" >
                  <?php
                  foreach($currency_list as $currency_data)
                  {
                      ?>
                      <option value="<?=$currency_data->id;?>" <?php if($currency_data->id==$row->currency_type){echo"SELECTED";} ?>><?=$currency_data->name;?>(<?=$currency_data->code;?>)</option>
                      <?php
                  }
                  ?>
                </select>
            </div>
        </td>
        <td id="unit_div_<?php echo $row->id; ?>">
            <div id="ptb_unit_html_<?php echo $row->id; ?>"><?php echo $row->unit; ?></div>
            <div style="display: none;" id="ptb_unit_input_html_<?php echo $row->id; ?>">
                <input type="text" max="10" class="form-control only_natural_number" id="ptb_unit_<?php echo $row->id; ?>" placeholder="Unit" value="<?php echo $row->unit; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
            </div> 
        </td>
        <td id="unit_type_div_<?php echo $row->id; ?>">
            <div id="ptb_unit_type_html_<?php echo $row->id; ?>"><?php echo $row->unit_type_name; ?></div>
            <div style="display: none;" id="ptb_unit_type_input_html_<?php echo $row->id; ?>">
                <select class="form-control" id="ptb_unit_type_<?php echo $row->id; ?>" >
                  <?php
                  foreach($unit_type_list as $unit_type_data)
                  {
                      ?>
                      <option value="<?=$unit_type_data->id;?>" <?php if($unit_type_data->id==$row->unit_type){echo"SELECTED";} ?>><?=$unit_type_data->type_name;?></option>
                      <?php
                  }
                  ?>                          
              </select>
            </div>
        </td>
        <td><?php echo date_db_format_to_display_format($row->created_datetime); ?></td>       
        <td class="actions" width="10%">
            <a class="btn  btn-sm btn-primary edit_product_tagged_vendor" href="JavaScript:void(0);" data-id="<?php echo $row->id; ?>" id="edit_save_<?php echo $row->id; ?>" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
            <a class="btn  btn-sm btn-danger close_product_tagged_vendor" href="JavaScript:void(0);" data-id="<?php echo $row->id; ?>" id="close_btn_<?php echo $row->id; ?>" style="display: none;"><i class="fa fa-times " aria-hidden="true"></i></a>
            
        </td>
    </tr>
<?php 
    } 
}
else
{
    echo'<tr><td colspan="8">No Record Found..</td></tr>';
}
?>
