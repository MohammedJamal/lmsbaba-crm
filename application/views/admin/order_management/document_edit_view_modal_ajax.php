
<form id="omDocEditFrm" name="omDocEditFrm" class="">
<input type="hidden" name="po_pi_id" value="<?php echo $po_pi_id; ?>" />
<input type="hidden" name="om_stage_form_submitted_id" value="<?php echo $id; ?>" />
<div class="row">
    <ul class="form-row"> 
        <?php if(count($form_wise_field_list)){ $i=0;?>
            <?php foreach($form_wise_field_list AS $field){ ?>
                <?php $required_edit_str=($field['is_mandatory']=='Y')?'required_edit':''; ?>
                <li>&nbsp;</li>
                <li class="col-md-12" style="margin: 10px;">
                    <label for=""><b><?php echo $field['label']; ?>:</b> <?php echo ($field['is_mandatory']=='Y')?'<apan class="text-red">*</span>':''; ?></label>
                    <?php if($field['input_type']=='IB'){?>                
                    <input type="text" class="form-control om_custom_form_field_edit <?php echo $required_edit_str; ?>" name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['label']; ?>" value="<?php echo $field['submitted_value']; ?>" maxlength="255" data-label="<?php echo $field['label']; ?>"  >
                    <?php } ?>

                    <?php if($field['input_type']=='TA'){?>
                    <textarea class="form-control om_custom_form_field_edit <?php echo $required_edit_str; ?> " name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['label']; ?>" data-label="<?php echo $field['label']; ?>"><?php echo $field['submitted_value']; ?></textarea>
                    <?php } ?>

                    <?php if($field['input_type']=='D'){?>
                    <input type="text" class="form-control om_custom_form_field_edit <?php echo $required_edit_str; ?> display_date " name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['label']; ?>" value="<?php echo $field['submitted_value']; ?>" maxlength="255"  data-label="<?php echo $field['label']; ?>">
                    <?php } ?>

                    <?php if($field['input_type']=='DT'){?>                    
                        <input type="text" name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>_date" data-id="<?php echo $field['id']; ?>" class="<?php echo $required_edit_str; ?> form-control display_datetime om_custom_form_field_edit" placeholder="<?php echo $field['label']; ?>" value="<?php echo $field['submitted_value']; ?>" data-label="Date"  />
                    <?php } ?>

                    <?php if($field['input_type']=='R'){?>
                        <?php
                        $option_arr=unserialize($field['option_with_value']);
                        $k = array_keys($option_arr);
                        $v = array_values($option_arr);
                        $rv = array_reverse($v);
                        $rk = array_reverse($k);
                        $options = array_combine($rk, $rv);                                        
                        ?>
                        <ul>
                            <?php if(count($options)){ ?>
                                <?php foreach($options AS $option_k=>$option_v){ ?>
                                    <li>
                                        <label class="check-box-sec radio">
                                        <input type="radio" value="<?php echo $option_k; ?>" class="<?php echo $required_edit_str; ?> om_custom_form_field_edit" name="<?php echo $field['input_type'].'_'.$field['form_id'].'_'.$i; ?>" data-text="<?php echo $options_v; ?>" data-id="<?php echo $field['id']; ?>" data-label="<?php echo $field['label']; ?>" <?php if($field['submitted_value']==$option_k){echo'checked';} ?>>
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
                        $rv = array_reverse($v);
                        $rk = array_reverse($k);
                        $options = array_combine($rk, $rv); 
                        $submitted_value_arr=explode("^",$field['submitted_value']);                    
                        ?>
                        <ul class="repeart_ul">
                            <?php if(count($options)){ ?>
                                <?php foreach($options AS $option_k=>$option_v){ ?>
                                    <li id="">
                                        <label class="check-box-sec">
                                        <input type="checkbox" value="<?php echo $option_k; ?>" class="<?php echo $required_edit_str; ?> om_custom_form_field_edit" name="<?php echo $field['input_type'].'_'.$field['form_id'].'_'.$i; ?>" data-id="<?php echo $field['id']; ?>" data-text="<?php echo $options_v; ?>" data-label="<?php echo $field['label']; ?>" <?php if(in_array($option_k,$submitted_value_arr)){echo 'checked';} ?>>
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
                        $rv = array_reverse($v);
                        $rk = array_reverse($k);
                        $options = array_combine($rk, $rv);                    
                        ?>
                        <select class="form-control <?php echo $required_edit_str; ?> om_custom_form_field_edit" name="<?php echo $field['input_type'].'_'.$field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" data-label="<?php echo $field['label']; ?>" >
                            <option value="">-- Select --</option>
                            <?php if(count($options)){ ?>
                                <?php foreach($options AS $option_k=>$option_v){ ?>
                                <option value="<?php echo $option_k; ?>" <?php if($field['submitted_value']==$option_k){echo'selected';} ?>><?php echo $option_v; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    <?php } ?>

                    <?php if($field['input_type']=='F'){?>                    
                        <input type="file" class="<?php //echo $required_edit_str; ?> om_custom_form_field_edit" name="<?php echo $field['input_type'].'_'.$field['form_id'].'_'.$i; ?>" data-id="<?php echo $field['id']; ?>" data-label="<?php echo $field['label']; ?>">
                        
                        <?php if($required_edit_str){ ?><p class="text-info">Choose file to replace existing File</a><?php } ?>
                        <?php if($field['submitted_value']){ ?>
                        <p><a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order_management/download/<?php echo base64_encode($field['submitted_value']); ?>" > <i class="fa fa-download" aria-hidden="true"></i> Download </a></p>
                        <?php } ?>
                    <?php } ?>
                    
                </li>
            <?php $i++;} ?>
        <?php } ?>
    </ul>
    <div class="col-md-12">
        <button class="btn btn-primary pull-right fix-ww" id="om_stage_wise_document_edit_confirm" type="submit" data-pi_id="<?php echo $po_pi_id; ?>" >Save</button>
    </div>
</div>
</form>
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
