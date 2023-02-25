
<div class="form-group row">   
    <form name="om_form_fields_set_frm" id="om_form_fields_set_frm"> 
    <input type="hidden" id="form_id" name="form_id" value="<?php echo $form_id; ?>" />
    <div class="col-md-12">        
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Add Form Field:</legend>
            <div class="control-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="om_form_input_type">Input Type:</label>
                        <select class="form-control" name="om_form_input_type" id="om_form_input_type">
                            <option value="">-- Choose --</option>
                            <option value="IB">Input Box</option>
                            <option value="TA">Text Area</option>
                            <option value="D">Date</option>
                            <option value="DT">Date & Time</option>
                            <option value="R">Radio Button</option>
                            <option value="CB">Checkbox</option>
                            <option value="S">Select Option</option>
                            <option value="F">File</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label for="om_stage_name">Input Label:</label>
                        <input type="text" class="form-control " name="om_form_label" id="om_form_label" placeholder="" value="" maxlength="255" >
                    </div> 
                    <div class="col-md-2">
                        <label for="om_form_is_mandatory">Is Mandatory:</label>
                        <select class="form-control" name="om_form_is_mandatory" id="om_form_is_mandatory">                           
                            <option value="Y">Yes</option>
                            <option value="N" SELECTED>No</option>
                        </select>
                    </div>
                    <div class="col-md-12">&nbsp;</div>
                    <div class="col-md-12">                    
                        
                        <div id="option_fields_initial" class="row" style="display:none">                            
                            
                            <!-- <div class="col-sm-5 nopadding">                                
                                <input type="text" class="form-control" id="option_key" name="option_key[]" value="" placeholder="Option">                                
                            </div> -->
                            <div class="col-sm-10 nopadding">                                
                                <input type="text" class="form-control" id="option_value" name="option_value[]" value="" placeholder="Value">                               
                            </div> 
                            <div class="input-group-btn">
                                <button class="btn btn-success" type="button"  onclick="option_fields();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>
                            </div>          
                        </div> 
                        <div>&nbsp;</div>
                        <div id="option_fields" class="row"></div>
                    </div>
                        
                    <div class="col-md-1">
                        <a href="javascript:void(0)" class="btn btn-success mt-25" id="om_stage_form_fields_add_submit">Add</a>
                    </div>
                </div>
                
            </div>
        </fieldset>
    </div>  
    </form>
    <div class="col-md-12 lead_stage_sortable_outer_div" >
        <div class="table-responsive">
            <table class="table align-items-center mb-0 sp-table-new" width="100%">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="20%">Input Type</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2" width="20%">Input Label</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="20%">Is Mandatory</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Action</th>        
                </tr>
                </thead>
                <tbody id="stage_form_fields_sortable" class="list-group">
                <?php 
                    if(count($rows))
                    { 
                        $total_second=0;
                        $i=0;?>
                        <?php 
                        foreach($rows AS $row)
                        {           
                            $i++;
                        ?>
                        <tr id="li_<?php echo $row['id']; ?>">                                                       
                            <td><span class="text-xs font-weight-bold"> <?php echo get_form_fields_type_full_text($row['input_type']); ?> </span></td>                        
                            <td><span class="text-xs font-weight-bold"> <?php echo $row['label']; ?> </span></td>    
                            <td><span class="text-xs font-weight-bold"> <?php echo ($row['is_mandatory']=='Y')?'Yes':'No'; ?> </span></td>    
                            <td class="align-middle" class="text-center">
                                <span class="text-xs font-weight-bold">
                                    <a href="JavaScript:void(0);" class="om_stage_form_fields_edit_popup icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                </span>
                                <span class="text-xs font-weight-bold">
                                    <a href="JavaScript:void(0);" class="icon-btn btn-alert text-white om_stage_form_fields_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </span>
                            </td>                         
                        </tr>
                        <?php 
                                                    
                        } ?>
                        <?php } ?>        
                </tbody>
            </table>
        </div>
    </div>



    <!-- <div class="col-md-12 lead_stage_sortable_outer_div" >
        <?php if(count($rows)){ ?>
        <ul id="lead_stage_sortable" class="list-group">
        <?php foreach($rows AS $row){ ?>
            <li class="list-group-item d-flex justify-content-between align-items-center <?php echo ($row['is_system_generated']=='Y')?'ui-state-disabled':''; ?>" id="li_<?php echo $row['id']; ?>">
                <span id="output_div_<?php echo $row['id']; ?>" style="width: 30%; line-height: 28px;"><?php echo $row['label']; ?></span>
                <span id="input_div_<?php echo $row['id']; ?>" style="width: 50%;" >
                    <div class="input-group mb-3" style="display: none;" id="input_div_inner_<?php echo $row['id']; ?>">
                        <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" value="<?php echo $row['name']; ?>" name="" id="stage_<?php echo $row['id']; ?>" >
                        <div class="input-group-append">
                            <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn text-primary om_stage_edit_submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>

                            <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn input_div_close text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>         
                        </div>
                        </div>
                </span>
                <?php if($row['is_system_generated']=='Y'){
                    echo'Default';
                }
                else{
                ?>
                <span class="badge badge-primary badge-pill" style="background-color: #fff;" style="width: 20%;">
                    <a href="JavaScript:void(0);" class="om_stage_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> &nbsp; 

                    <a href="JavaScript:void(0);" class="icon-btn btn-alert text-white om_stage_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </span>
                <?php } ?>
            </li>
        <?php } ?>
        </ul>
        <?php }else{ ?>
            No record found!
        <?php } ?>
    </div>  -->
</div>


<style type="text/css">
#stage_form_fields_sortable > tr  {
    cursor: move;
}
</style>


<script>
    var room = 1;
    function option_fields() {    
        room++;
        var objTo = document.getElementById('option_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass"+room);
        var rdiv = 'removeclass'+room;
        divtest.innerHTML = '<!--<div class="col-sm-5 nopadding"><input type="text" class="form-control" id="option_key" name="option_key[]" value="" placeholder="Option"> </div>--><div class="col-sm-10 nopadding"><input type="text" class="form-control" id="option_value" name="option_value[]" value="" placeholder="Value"> </div>   <div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_option_fields('+ room +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></div></div></div></div><div class="clear"></div>';
        
        objTo.appendChild(divtest)
    }
   function remove_option_fields(rid) {
	   $('.removeclass'+rid).remove();
   }

   $(document).ready(function(){
        $("body").on('change','#om_form_input_type',function(e){
            var input_type=$(this).val();
            if(input_type=='R' || input_type=='S' || input_type=='CB'){
                $("#option_fields_initial").css("display",'block');
            }
            else{
                $("#option_fields_initial").css("display",'none');
                $("#option_fields").html('');
            }
            
        });

        
   });
</script>