<?php 
$option_with_value=unserialize($row['option_with_value']);
?>

<form name="om_form_fields_edit_frm" id="om_form_fields_edit_frm"> 
    <input type="hidden" id="form_id" name="form_id" value="<?php echo $form_id; ?>" />
    <input type="hidden" id="edit_id" name="edit_id" value="<?php echo $edit_id; ?>" />
    <div class="form-group row"> 
        <div class="col-md-12">        
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Add Form Field:</legend>
                <div class="control-group">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label for="">Input Type:</label>
                            <input type="hidden" name="om_form_input_type" value="<?php echo $row['input_type']; ?>" />
                            <select class="form-control" name="" id="" disabled>
                                <option value="">-- Choose --</option>
                                <option value="IB" <?php if($row['input_type']=='IB'){echo'selected';} ?>>Input Box</option>
                                <option value="TA" <?php if($row['input_type']=='TA'){echo'selected';} ?>>Text Area</option>
                                <option value="D" <?php if($row['input_type']=='D'){echo'selected';} ?>>Date</option>
                                <option value="DT" <?php if($row['input_type']=='DT'){echo'selected';} ?>>Date & Time</option>
                                <option value="R" <?php if($row['input_type']=='R'){echo'selected';} ?>>Radio Button</option>
                                <option value="CB" <?php if($row['input_type']=='CB'){echo'selected';} ?>>Checkbox</option>
                                <option value="S" <?php if($row['input_type']=='S'){echo'selected';} ?>>Select Option</option>
                                <option value="F" <?php if($row['input_type']=='F'){echo'selected';} ?>>File</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="om_stage_name">Input Label:</label>
                            <input type="text" class="form-control " name="om_form_label" id="om_form_label" placeholder="" value="<?php echo $row['label']; ?>" maxlength="255" >
                        </div> 
                        <div class="col-md-2">
                            <label for="om_form_is_mandatory">Is Mandatory:</label>
                            <select class="form-control" name="om_form_is_mandatory" id="om_form_is_mandatory">                           
                                <option value="Y" <?php if($row['is_mandatory']=='Y'){echo'selected';} ?>>Yes</option>
                                <option value="N" <?php if($row['is_mandatory']=='N'){echo'selected';} ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-12"> 
                            <div id="option_fields_edit" class="row">
                                <?php if(count($option_with_value)){ $i=0;?>
                                <?php foreach($option_with_value AS $v){ ?>
                                    <?php if($i==0){ ?>
                                    <div class="form-group ">                                     
                                        <div class="col-sm-10 nopadding">                                
                                            <input type="text" class="form-control" id="option_value" name="option_value[]" value="<?php echo $v; ?>" placeholder="Value">                               
                                        </div> 
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" type="button"  onclick="option_fields_edit();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>
                                        </div>
                                    </div> 
                                    <?php }else{ ?>
                                    <div class="form-group removeclass_edit<?php echo $i; ?>">                                   
                                        <div class="col-sm-10 nopadding">
                                            <input type="text" class="form-control" id="option_value" name="option_value[]" value="<?php echo $v; ?>" placeholder="Value"> 
                                        </div>   
                                        <div class="input-group-btn">
                                            <button class="btn btn-danger" type="button" onclick="remove_option_fields_edit('<?php echo $i; ?>');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="clear"></div>  
                                <?php $i++;} ?>
                                <?php } ?>   
                            </div>
                        </div>
                            
                        <div class="col-md-1">
                            <a href="javascript:void(0)" class="btn btn-success mt-25" id="om_stage_form_fields_edit_submit">Save</a>
                        </div>
                    </div>
                    
                </div>
            </fieldset>
        </div>  
    </div>
</form>

    <script>
    var room = 1;
    function option_fields_edit() {    
        room++;
        var objTo = document.getElementById('option_fields_edit')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass_edit"+room);
        var rdiv = 'removeclass_edit'+room;
        divtest.innerHTML = '<!--<div class="col-sm-5 nopadding"><input type="text" class="form-control" id="option_key" name="option_key[]" value="" placeholder="Option"> </div>--><div class="col-sm-10 nopadding"><input type="text" class="form-control" id="option_value" name="option_value[]" value="" placeholder="Value"> </div>   <div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_option_fields_edit('+ room +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></div></div></div></div><div class="clear"></div>';
        
        objTo.appendChild(divtest)
    }
   function remove_option_fields_edit(rid) {
	   $('.removeclass_edit'+rid).remove();
   }

   $(document).ready(function(){
       

        
   });
</script>