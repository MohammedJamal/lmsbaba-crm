<ul class="form-row"> 
    <?php if(count($form_wise_field_list)){ $i=0;?>
        <?php foreach($form_wise_field_list AS $field){ ?>
            <?php if($field['submitted_value']){ ?>
            <li>&nbsp;</li>
            <li class="col-md-12">
                <label for=""><b><?php echo $field['label']; ?>:</b></label>
                <?php if($field['input_type']=='IB'){?>                
                <input type="text" class="form-control"  value="<?php echo $field['submitted_value']; ?>"  disabled  >
                <?php } ?>

                <?php if($field['input_type']=='TA'){?>
                <textarea class="form-control " disabled><?php echo $field['submitted_value']; ?></textarea>
                <?php } ?>

                <?php if($field['input_type']=='D'){?>
                <input type="text" class="form-control"  value="<?php echo $field['submitted_value']; ?>" disabled>
                <?php } ?>

                <?php if($field['input_type']=='DT'){?>                    
                    <input type="text" class="form-control" value="<?php echo $field['submitted_value']; ?>" disabled />
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
                                    <input disabled type="radio" value="<?php echo $option_k; ?>" class="" <?php if($field['submitted_value']==$option_k){echo 'checked';} ?>>
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
                                    <input disabled type="checkbox" value="<?php echo $option_k; ?>" class=" " <?php if(in_array($option_k,$submitted_value_arr)){echo 'checked';} ?>>
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
                    <select class="form-control " disabled>
                        <option value="" >-- Select --</option>
                        <?php if(count($options)){ ?>
                            <?php foreach($options AS $option_k=>$option_v){ ?>
                            <option value="<?php echo $option_k; ?>" <?php if($field['submitted_value']==$option_k){echo 'selected';} ?>><?php echo $option_v; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                <?php } ?>

                <?php if($field['input_type']=='F'){?>

                    <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order_management/download/<?php echo base64_encode($field['submitted_value']); ?>" > <i class="fa fa-download" aria-hidden="true"></i> Download </a>
                    
                <?php } ?>
                
            </li>
        <?php $i++;}} ?>
    <?php } ?>
</ul>


