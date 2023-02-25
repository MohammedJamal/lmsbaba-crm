<fieldset class="tsf-content">
    <form method="post" enctype="multipart/form-data" action="<?=base_url().$this->session->userdata['admin_session_data']['lms_url']?>/product/select_vendor_for_add_product" name="frm_product_wise_dendor" id="select_vendor_for_add_product_from" class="select_vendor_for_add_product_from">
        <?php foreach($vendor_key_val as $k=>$val){ ?>
            <div class="row">
                <div class="col-lg-12">
<!--
                    <div class="checkbox-vendor-detail">
                        <label><input class="vndr" type="checkbox" name="vendors_id_<?php echo $k?>" value="<?php echo $k?>" data-val="<?php echo $val?>"><?php echo $val?></label>
                    </div>
-->
                    <div class="checkbox-vendor-detail">
                    <label class="checkbox-check">
                        <input class="vndr" type="checkbox" name="vendors_id_<?php echo $k?>" value="<?php echo $k?>" data-val="<?php echo $val?>" <?php if($k==$existing_vendors[$k]['vendor_id']){echo"checked";} ?>><?php echo $val?>
                        <span class="checkmark"></span>
                    </label>
                    </div>
                    
                </div>
            </div>
            <div class="row <?php if($k==$existing_vendors[$k]['vendor_id']){}else{echo 'd-none';} ?>" id="vendor_id_<?php echo $k?>">
                <div class="col-lg-2">
                    <div class="">
                        <div class="form-group">
                            <label>Price</label>
                            <input id="<?php echo $k?>_price" type="text" max="10" class="form-control double_digit" name="<?php echo $k?>_pwv_price" placeholder="Price" value="<?php echo ($k==$existing_vendors[$k]['vendor_id'])?$existing_vendors[$k]['price']:''; ?>">
                            <div class="text-danger" id="<?php echo $k?>_price_error"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="">
                        <div class="form-group">
                            <label>Currency</label>
                            <select id="<?php echo $k?>_currency" class="form-control" name="<?php echo $k?>_pwv_currency_type" >
                                <option value="">Select</option>
                                <?php foreach($currency_list as $currency_data) { ?>
                                    <option value="<?=$currency_data->id;?>" <?php if($k==$existing_vendors[$k]['vendor_id']){echo ($existing_vendors[$k]['currency_type']==$currency_data->id)?'selected':'';}; ?>>
                                        <?php //echo $currency_data->name;?>
                                        <?php echo $currency_data->code;?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="text-danger" id="<?php echo $k?>_currency_error"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="">
                        <div class="form-group">
                            <label>Unit</label>
                            <input id="<?php echo $k?>_unit" type="text" max="10" class="form-control" name="<?php echo $k?>_pwv_unit" placeholder="Unit" value="<?php echo ($k==$existing_vendors[$k]['vendor_id'])?$existing_vendors[$k]['unit']:''; ?>" />
                            <div class="text-danger" id="<?php echo $k?>_unit_error"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="">
                        <div class="form-group">
                            <label>Unit Type</label>
                            <select id="<?php echo $k?>_unit_type" class="form-control" name="<?php echo $k?>_pwv_unit_type">
                                <option value="">Select</option>
                                <?php  foreach($unit_type_list as $unit_type_data)  { ?>
                                    <option value="<?=$unit_type_data->id;?>" <?php if($k==$existing_vendors[$k]['vendor_id']){echo ($existing_vendors[$k]['unit_type']==$unit_type_data->id)?'selected':'';}; ?>>
                                        <?=$unit_type_data->type_name;?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="text-danger" id="<?php echo $k?>_unit_type_error"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        
    </form>
    <div class="row vendor-add-btn">
            <div class="col-lg-12">
                <button type="button" id="select-vendor-add-product-submit" class="btn btn-lg btn-default">Select & Proceed</button>
            </div>
        </div>
</fieldset>
