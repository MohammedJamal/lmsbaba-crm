<?php 
$kpi_ids=$get_kpi_setting['kpi_ids'];
$kpi_ids_arr=explode(",",$kpi_ids);

$kpi_names=$get_kpi_setting['kpi_names'];
$kpi_names_arr=explode(",",$kpi_names);

$existing_arr=array();
if(count($kpi_setting_user_wise_set_target_list))
{
    foreach($kpi_setting_user_wise_set_target_list AS $row)
    {
        $existing_arr[$row['kpi_id']]=array(
                            'weighted_score'=>$row['weighted_score'],
                            'target'=>$row['target'],
                            'min_target_threshold'=>$row['min_target_threshold']
                        );
    }    
}
// print_r($kpi_setting_user_wise_row);
?>
    <div>&nbsp;</div>
    
    <form role="form" class="form-validation" action="" method="post" name="form" id="profile_update_form_<?php echo $user_id; ?>" enctype="multipart/form-data">
    <input type="hidden" name="kpi_setting_id_<?php echo $user_id; ?>" value="<?php echo $kpi_setting_id; ?>" />
    <input type="hidden" name="set_report_for_<?php echo $user_id; ?>" id="set_report_for_<?php echo $user_id; ?>" value="M" />
    
        <div class="target-table-loop">
            <div class="table-responsive">
                <table class="table table-bordered table-striped m-b-0 th_color lead-board">
                    <thead>
                        <tr>
                        <td colspan="5" class="bg-info font-sm">
                            <div class="row ">
                                <div class="col-md-6">
                                    <b><?php echo $user_info['u_user_name']; ?></b><br>
                                    <b>User Id: </b><?php echo $user_info['u_user_id']; ?>                       
                                </div>
                                <div class="col-md-6 text-right">
                                <div class="float-right ml-15">
                                    <a href="JavaScript:void(0);" class="copy-btn copy_kpi_inactive" data-uid="<?php echo $user_id; ?>" data-uid="<?php echo $user_id; ?>" id="copy_kpi_<?php echo $user_id; ?>"><i class="fa fa-files-o" aria-hidden="true" ></i></a>
                                </div>  
                                <div class="float-right">
                                    <b>Manager: </b>  <br>  
                                    <span class="text-italic"><?php echo ($user_info['u_user_manager_name'])?$user_info['u_user_manager_name']:'N/A'; ?></span> 
                                </div>
                                                
                                </div>
                            </div>
                        </td>
                        </tr>
                        <tr>
                            <th width="10" class="text-left">SL.</th>
                            <th width="35%" class="text-left">KPIs</th>
                            <th class="text-center">Weighted Score<br><small><i>(Out of 100)</i></small></th>
                            <th class="text-center">Target</th>
                            <th class="text-center">Min. Target Threshold<br><small><i>(Optional)</i></small></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $tmp_weighted_score=0;
                        if(count($kpi_names_arr)){ ?>
                        <?php 
                            for($i=0;$i<count($kpi_names_arr);$i++){ 
                                $weighted_score=($existing_arr[$kpi_ids_arr[$i]])?$existing_arr[$kpi_ids_arr[$i]]['weighted_score']:0;        
                        ?>
                            <tr>
                                <td class="text-left"><?php echo ($i+1); ?></td>
                                <td class="text-left"><?php echo $kpi_names_arr[$i]; ?><input type="hidden" name="kpi[]"  value="<?php echo $kpi_ids_arr[$i]; ?>" /></td>
                                <td class="text-center"><input type="text" value="<?php echo ($existing_arr[$kpi_ids_arr[$i]])?$existing_arr[$kpi_ids_arr[$i]]['weighted_score']:''; ?>" class="form-control maxw-75 border-blue only_natural_number weighted_score weighted_score_<?php echo $user_id; ?>" name="weighted_score_<?php echo $user_id; ?>[]" id="weighted_score_<?php echo $user_id; ?>_<?php echo $kpi_ids_arr[$i]; ?>" data-uid="<?php echo $user_id; ?>" data-kpi_id="<?php echo $kpi_ids_arr[$i]; ?>" readonly="true" /></td>
                                <td class="text-center"><input type="text" value="<?php echo ($existing_arr[$kpi_ids_arr[$i]])?$existing_arr[$kpi_ids_arr[$i]]['target']:''; ?>" class="form-control maxw-125 border-blue double_digit target_<?php echo $user_id; ?>" name="target_<?php echo $user_id; ?>[]" id="target_<?php echo $user_id; ?>_<?php echo $kpi_ids_arr[$i]; ?>" data-kpi_id="<?php echo $kpi_ids_arr[$i]; ?>" readonly="true" /></td>
                                <td class="text-center"><input type="text" value="<?php echo ($existing_arr[$kpi_ids_arr[$i]])?$existing_arr[$kpi_ids_arr[$i]]['min_target_threshold']:''; ?>" class="form-control maxw-125 border-blue double_digit min_target_threshold_<?php echo $user_id; ?>" name="min_target_threshold_<?php echo $user_id; ?>[]" id="min_target_threshold_<?php echo $user_id; ?>_<?php echo $kpi_ids_arr[$i]; ?>" data-kpi_id="<?php echo $kpi_ids_arr[$i]; ?>" readonly="true" /></td>
                            </tr>
                        <?php $tmp_weighted_score=($tmp_weighted_score+$weighted_score);} ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                        <td class="text-center">
                        <strong><span id="weighted_score_out_of_<?php echo $user_id; ?>_100"><?php echo $tmp_weighted_score; ?>/100</span></strong>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>

                    


                        <td class="text-left" colspan="5">
                        <div class="target-footer-content mb-15">
                            <div class="text-left d-inline-flex mr-15">
                                <label class="check-box-sec">
                                    <input type="checkbox" value="Y" class="is_apply_pli" name="is_apply_pli_<?php echo $user_id; ?>" id="is_apply_pli_<?php echo $user_id; ?>" <?php echo ($kpi_setting_user_wise_row['is_apply_pli']=='Y')?'checked':''; ?> data-uid="<?php echo $user_id; ?>" disabled>
                                    <span class="checkmark"></span>
                                </label>
                                <b class="ml-10">Performance Linked Incentive (PLI):</b>
                            </div>
                            <div class="text-left d-inline-flex mr-15">                            
                            <input type="number" value="<?php echo $kpi_setting_user_wise_row['pli_in']; ?>" class="form-control only_natural_number maxw-75" name="pli_in_<?php echo $user_id; ?>" id="pli_in_<?php echo $user_id; ?>" <?php echo ($kpi_setting_user_wise_row['is_apply_pli']=='Y')?'readonly="true"':'readonly=true'; ?> />
                            </div>
                            <div class="text-left d-inline-flex mr-10 ml-10">
                            % of Monthly Salary
                            </div>
                            <div class="text-left d-inline-flex">
                            <input type="number" value="<?php echo $user_info['u_salary']; ?>" class="form-control only_natural_number maxw-125" name="monthly_salary_<?php echo $user_id; ?>" id=""  readonly=true />
                            
                            </div>
                        </div>
                        <div class="target-footer-content mb-15">
                            <div class="text-left d-inline-flex">
                                <label class="check-box-sec">
                                <input type="checkbox" value="Y" class="is_apply_pip" name="is_apply_pip_<?php echo $user_id; ?>" id="is_apply_pip_<?php echo $user_id; ?>" <?php echo ($kpi_setting_user_wise_row['is_apply_pip']=='Y')?'checked':''; ?> data-uid="<?php echo $user_id; ?>" disabled>
                                    <span class="checkmark"></span>
                                </label>
                                <b class="ml-10">Performance Improvement Programme (PIP):</b>&nbsp; Monthly Performance Score <
                            </div>

                            


                            <div class="text-left d-inline-flex mr-10 ml-10">
                                <input type="number" value="<?php echo $kpi_setting_user_wise_row['target_threshold']; ?>" class="form-control only_natural_number maxw-75" name="set_total_target_threshold_<?php echo $user_id; ?>" id="set_total_target_threshold_<?php echo $user_id; ?>" style="width:150px;" <?php echo ($kpi_setting_user_wise_row['is_apply_pip']=='Y')?'readonly="true"':'readonly=true'; ?> />
                            
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                            in
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                                <input type="number" value="<?php echo $kpi_setting_user_wise_row['target_threshold_for_x_consecutive_month']; ?>" class="form-control only_natural_number maxw-75" name="set_total_target_threshold_for_x_consecutive_month_<?php echo $user_id; ?>" id="set_total_target_threshold_for_x_consecutive_month_<?php echo $user_id; ?>" <?php echo ($kpi_setting_user_wise_row['is_apply_pip']=='Y')?'readonly="true"':'readonly=true'; ?> />
                            
                            </div>
                            <div class="text-left d-inline-flex">
                            Consecutive Months
                            </div>
                        </div>
                        <div class="w-100">
                        <input type="button" class="btn btn-success set_kpi_for_user_btn_confirm_inactive pull-right" id="" data-uid="<?php echo $user_id; ?>" value="Edit" />
                        </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </form>
