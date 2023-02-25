<?php if($kpi_target_by=='F'){$by_name='Function Area';}else if($kpi_target_by=='D'){$by_name='Department';}else if($kpi_target_by=='U'){$by_name='User';} ?>
<div class="kpi-header">
    <strong>Current KPI Settings:</strong> <span>By <?php echo $by_name; ?></span>
</div>
<div class="custom-table-responsive table-toggle-holder table-kpi-holder table-kpi">
    <table class="table custom-table" style="position: relative; overflow: hidden; width: 100%;">
       <thead>
          <tr>
             <th scope="col" width="20%"><?php echo $by_name; ?></th>
             <th scope="col">KPIs</th>
             <th scope="col" class="text-center" width="15%">Action</th>             
          </tr>
       </thead>
       <tbody id="tcontent">
            <?php if(count($rows)){ $i=0; ?>
                <?php foreach($rows AS $row){ ?>
                    <?php 
                    $kpi_ids=$row['kpi_ids'];
                    $kpi_ids_arr=explode(",",$kpi_ids);

                    $kpi_names=$row['kpi_names'];
                    $kpi_names_arr=array();
                    $kpi_names_arr=explode(",",$kpi_names);
                    if($kpi_ids_arr[0]){
                        $edit_display='none';
                        $view_display='block';
                    }
                    else{
                        $edit_display='block';
                        $view_display='none';
                    }
                    
                    ?>
                    <tr scope="row">
                        <td>
                            <?php echo $row['name']; //print_r($row);?>
                        </td>
                        <td>
                            
                            <div id="kpi_setting_div_edit_<?php echo $i; ?>" style="display:none<?php //echo $edit_display; ?>">        
                                <?php if(count($kpi_rows)){ ?>
                                    <ul class="employee_assign">
                                    <?php foreach($kpi_rows AS $kpi_row){ ?>        
                                        <li >
                                            <label class="check-box-sec">
                                            <input type="checkbox" name="kpi_ids_<?php echo $i; ?>[]" value="<?php echo $kpi_row['id']; ?>" data-name="<?php echo $kpi_row['name']; ?>" class="" <?php if(in_array($kpi_row['id'],$kpi_ids_arr)){echo'checked';} ?>>
                                            <span class="checkmark"></span>
                                                <?php echo $kpi_row['name']; ?>
                                            </label>                        
                                        </li>
                                    <?php } ?>
                                    </ul>
                                <?php } ?>
                                <div class="form-group row">
                                <div class="col-md-12">
                                    <a href="javascript:void(0)"  class=" btn btn-info kpi_submit_confirm" id="" data-kpi_target_by_id="<?php echo $row["id"]; ?>" data-serial_no="<?php echo $i; ?>">Save</a>
                                    <!-- <a href="javascript:void(0)" class="btn btn-success cancel_kpi_submit_confirm" id="" data-kpi_target_by_id="<?php echo $row["id"]; ?>" data-serial_no="<?php echo $i; ?>">Cancel</a> -->
                                </div>
                                </div>
                            </div>
                            <div id="kpi_setting_div_view_<?php echo $i; ?>" style="display:block<?php //echo $view_display; ?>">
                                <?php 
                                if($kpi_names_arr[0]){ ?>
                                    <?php foreach($kpi_names_arr AS $kpi){ ?>    
                                        <span class="kpi-blocks"><?php echo $kpi; ?></span>
                                    <?php } ?>
                                <?php }else{echo'<span class="kpi-blocks">No KPI found!</span>';}?>
                            </div>
                        </td>  
                        <td class="text-center  ">
                            <div id="kpi_setting_div_action_icon_<?php echo $i; ?>" style="display:<?php echo $view_display; ?>">
                                <a href="JavaScript:void(0);" class="icon-btn btn-secondary text-white kpi_setting_edit_action" data-serial_no="<?php echo $i; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>
                                <a href="JavaScript:void(0);" class="icon-btn btn-alert text-white kpi_setting_delete_action"  data-kpi_target_by_id="<?php echo $row["id"]; ?>" data-serial_no="<?php echo $i; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </div>
                            <div id="kpi_setting_div_action_icon_na_<?php echo $i; ?>" style="display:<?php echo $edit_display; ?>"><a href="JavaScript:void(0);" class="icon-btn btn-secondary text-white kpi_setting_edit_action" data-serial_no="<?php echo $i; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a></div>
                        </td>
                    </tr>
                    <tr class="spacer">
                        <td colspan="3"></td>
                    </tr>
                <?php $i++; } ?>
            <?php } ?>  
          
       </tbody>
    </table>
</div>