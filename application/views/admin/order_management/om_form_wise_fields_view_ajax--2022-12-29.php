
<ul class="form-row"> 
    <?php if(count($form_wise_field_list)){ $i=0;?>
        <?php foreach($form_wise_field_list AS $field){ ?>
            <?php $required_str=($field['is_mandatory']=='Y')?'required':''; ?>
            <li>&nbsp;</li>
            <li class="col-md-12">
                <label for=""><?php echo $field['label']; ?> <?php echo ($field['is_mandatory']=='Y')?'<apan class="text-red">*</span>':''; ?>:</label>
                <?php if($field['input_type']=='IB'){?>                
                <input type="text" class="form-control om_custom_form_field <?php echo $required_str; ?>" name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['label']; ?>" value="" maxlength="255" data-label="<?php echo $field['label']; ?>"  >
                <?php } ?>

                <?php if($field['input_type']=='TA'){?>
                <textarea class="form-control om_custom_form_field <?php echo $required_str; ?> " name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['label']; ?>" data-label="<?php echo $field['label']; ?>"></textarea>
                <?php } ?>

                <?php if($field['input_type']=='D'){?>
                <input type="text" class="form-control om_custom_form_field <?php echo $required_str; ?> display_date " name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['label']; ?>" value="" maxlength="255"  data-label="<?php echo $field['label']; ?>">
                <?php } ?>

                <?php if($field['input_type']=='DT'){?>                    
                    <input type="text" name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>_date" data-id="<?php echo $field['id']; ?>" class="<?php echo $required_str; ?> form-control display_datetime om_custom_form_field" placeholder="<?php echo $field['label']; ?>" value="" data-label="Date"  />
                <?php } ?>

                <?php if($field['input_type']=='R'){?>
                    <?php
                    $option_arr=unserialize($field['option_with_value']);
                    $k = array_keys($option_arr);
                    $v = array_values($option_arr);
                    // $rv = array_reverse($v);
                    // $rk = array_reverse($k);
                    // $options = array_combine($rk, $rv);
                    $options = array_combine($k, $v);                                        
                    ?>
                    <ul>
                        <?php if(count($options)){ ?>
                            <?php foreach($options AS $option_k=>$option_v){ ?>
                                <li>
                                    <label class="check-box-sec radio">
                                    <input type="radio" value="<?php echo $option_k; ?>" class="<?php echo $required_str; ?> om_custom_form_field" name="<?php echo $field['input_type'].'_'.$field['form_id'].'_'.$i; ?>" data-text="<?php echo $options_v; ?>" data-id="<?php echo $field['id']; ?>" data-label="<?php echo $field['label']; ?>">
                                    <span class="checkmark"></span>
                                    </label>
                                    <?php echo $option_v; ?>             
                                </li>
                            <?php } ?>
                        <?php } ?>                        
                    </ul>

                    
                <?php } ?>

                <?php if($field['input_type']=='CB'){?>
                    <?php
                    $option_arr=unserialize($field['option_with_value']);
                    $k = array_keys($option_arr);
                    $v = array_values($option_arr);
                    // $rv = array_reverse($v);
                    // $rk = array_reverse($k);
                    // $options = array_combine($rk, $rv);                     
                    $options = array_combine($k, $v);                                     
                    ?>
                    <ul class="repeart_ul">
                        <?php if(count($options)){ ?>
                            <?php foreach($options AS $option_k=>$option_v){ ?>
                                <li id="">
                                    <label class="check-box-sec">
                                    <input type="checkbox" value="<?php echo $option_k; ?>" class="<?php echo $required_str; ?> om_custom_form_field" name="<?php echo $field['input_type'].'_'.$field['form_id'].'_'.$i; ?>" data-id="<?php echo $field['id']; ?>" data-text="<?php echo $options_v; ?>" data-label="<?php echo $field['label']; ?>">
                                    <span class="checkmark"></span>
                                    </label>
                                    <?php echo $option_v; ?>                       
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <?php if($field['input_type']=='S'){?>
                    <?php
                    $option_arr=unserialize($field['option_with_value']);
                    $k = array_keys($option_arr);
                    $v = array_values($option_arr);
                    // $rv = array_reverse($v);
                    // $rk = array_reverse($k);
                    // $options = array_combine($rk, $rv); 
                    $options = array_combine($k, $v);                    
                    ?>
                    <select class="form-control <?php echo $required_str; ?> om_custom_form_field" name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" data-label="<?php echo $field['label']; ?>">
                        <option value="">-- Select --</option>
                        <?php if(count($options)){ ?>
                            <?php foreach($options AS $option_k=>$option_v){ ?>
                            <option value="<?php echo $option_k; ?>"><?php echo $option_v; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                <?php } ?>

                <?php if($field['input_type']=='F'){?>
                    <!-- <label class="uploaded-doc">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> <span>Click to Attach Documents</span>
                        <input type="file" name="po_uc_file[]" id="po_uc_file">
                    </label> -->
                    <input type="file" class="<?php echo $required_str; ?> om_custom_form_field" name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" data-label="<?php echo $field['label']; ?>">
                <?php } ?>
                
            </li>
        <?php $i++;} ?>
    <?php } ?>
</ul>
<div class="col-md-12">
    <button class="btn btn-primary pull-right fix-ww" id="om_stage_wise_document_save_confirm" type="submit" >Save</button>
</div>
<script>
$('.display_date').datepicker({
    dateFormat: "dd-M-yy",
    changeMonth: true,
    changeYear: true,
    yearRange: '-100:+5'
});


$( ".display_datetime" ).datetimepicker({       		  
    format:'d-M-Y g:i A',
    formatTime: 'g:i A',
    ampm: true,
    step: 15, 
    theme:'default',
    inline:false,
    lang:'en',
    minDate: '0',
    closeOnDateTimeSelect:true,
    onSelectTime : function (current_time,$input) {
    
    },
});
</script>
