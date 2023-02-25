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
// print_r($kpi_setting_user_wise_set_target_list);
$is_row_available='N';
?>
<div class="card">
<div class="card-body bg-white px-0 pb-2">
    <div class="table-responsive-mobile" >
        <table class="table m-b-0 th_color lead-board table-nocolor" id="lead_table">
            <thead>
                <tr>
                    <td colspan="7" class="td-grey">

                    <div class="row ">
                        <div class="col-md-6 text-left text-italic">
                            <strong><?php echo $user_info['u_user_name']; ?></strong><br>
                            <strong>User Id: <?php echo $user_info['u_user_id']; ?></strong>
                        </div>
                        <div class="col-md-6 text-right text-italic">
                            <strong>Month: <?php echo date("M, Y");?></strong><br>
                            <strong>Manager: <?php echo ($user_info['u_user_manager_name'])?$user_info['u_user_manager_name']:'N/A'; ?></strong>
                        </div>
                    </div> 
                    </td>
                </tr>
                <tr>
                    <th width="80" class="text-left">SL.</th>
                    <th class="text-left">KPIs</th>
                    <th class="text-center">Weighted Score<br><small><i>(Out of 100)</i></small></th>
                    <th class="text-center">Target</th>
                    <th class="text-center">Achieved</th>
                    <th class="text-center">Score Obtained</th>
                </tr>
            </thead>
            <tbody>  
                <?php 
                $tmp_weighted_score=0;
                $total_score_obtained=0;
                ?>

                <?php 
                    $i=1;
                    if(count($kpi_setting_user_wise_set_target_list)){ $target_achieved=60; $is_row_available='Y';?>
                    <?php 
                        foreach($kpi_setting_user_wise_set_target_list AS $row){ 
                        $weighted_score=$row['weighted_score']; 
                        $target_achieved=($row['achieved'])?$row['achieved']:get_user_wise_kpi_count($user_id,$row['kpi_id'],'');
                        // $target_achieved=($row['achieved'])?$row['achieved']:60;
                        $target=$row['target'];
                        $score_obtained=($weighted_score*($target_achieved/$target*100)/100);
                    ?>
                        <tr>
                            <td class="text-left"><?php echo ($i+1); ?></td>
                            <td class="text-left"><?php echo $row['kpi_name']; ?></td>
                            <td class="text-center"><?php echo $weighted_score; ?></td>
                            <td class="text-center"><?php echo $target; ?></td>
                            <td class="text-center">
                                <div class="new-per-span"> 
                                    <span class="per-bg-ele" id="target_achieved_<?php echo $row['id']; ?>">
                                        <?php echo $target_achieved; ?>     
                                    </span>   
                                    <?php if($row['is_kpi_system_generated']=='N'){ ?>
                                        <a href="JavaScript:void(0);" class="target_achieved_edit pos-abso" title="Edit" id="target_achieved_edit_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        <div class="per-bg-ele-new" id="target_achieved_edit_view_<?php echo $row['id']; ?>" style="display:none">
                                            <span class="w-100">
                                                <input type="text" class="span-input only_natural_number" name="" id="target_achieved_value_<?php echo $row['id']; ?>" value="<?php echo $target_achieved; ?>" style="width:80px;" />
                                            </span>
                                            
                                            <a href="JavaScript:void(0);" class="target_achieved_save pos-abso top-0" data-id="<?php echo $row['id']; ?>" data-kpi_setting_id="<?php echo $kpi_setting_id; ?>" data-user_id="<?php echo $user_id; ?>" title="Save" id="target_achieved_save_<?php echo $row['id']; ?>"><i class="fa fa-save" aria-hidden="true" style=""></i></a>
                                            
                                            <a href="JavaScript:void(0);" class="target_achieved_close pos-abso bottom-0" data-id="<?php echo $row['id']; ?>" title="Close" id="target_achieved_close_<?php echo $row['id']; ?>"><i class="fa fa-times-circle-o" aria-hidden="true" style="color:red"></i></a>
                                            
                                        </div>
                                    <?php } ?>                           
                                </div>
                                
                            </td>
                            <td class="text-center" id="score_obtained_<?php echo $row['id']; ?>"><?php echo ceil($score_obtained); ?></td>
                        </tr>
                    <?php 
                        $i++;
                        $tmp_weighted_score=($tmp_weighted_score+$weighted_score);
                        $total_score_obtained=($total_score_obtained+$score_obtained);
                        } ?>
                <?php } ?>



                <tr class="td-grey">
                    <td colspan="2" class="text-right">
                        <b>Total Score:</b>
                    </td>
                    <td class="text-center">
                        <?php echo $tmp_weighted_score; ?>
                    </td>
                    <td colspan="3" class="text-right">
                        <b class="text-success font-size-18">Achived Score: <?php echo ceil($total_score_obtained); ?></b>
                    </td>
                </tr>
                <tr>
                    <td class="text-left" colspan="6">
                        <div class="w-100">
                                <b><span class="blue-link">Performance Linked Incentive:</span>
                                <?php
                                $pli=0;
                                if($kpi_setting_user_wise_row['is_apply_pli']=='Y')
                                {
                                    $pli=($kpi_setting_user_wise_row['pli_in']/100)*$kpi_setting_user_wise_row['monthly_salary'];
                                }
                                ?>
                                <?php echo ($kpi_setting_user_wise_row['is_apply_pli']=='Y' && $pli>0)?'<span class="text-success"><b>'.ceil($pli).'</b></span>':'<span class="text-danger">N/A</span>' ?>
                                </b>
                            
                        </div>
                        <div class="w-100">
                            <b><span class="blue-link">Performance Improvement Plan:</span>
                            <?php echo ($kpi_setting_user_wise_row['is_apply_pip']=='Y' && $kpi_setting_user_wise_row['target_threshold']>0 && $kpi_setting_user_wise_row['target_threshold_for_x_consecutive_month']>0)?'Active with Performance score < <span class="text-danger"><b>'.$kpi_setting_user_wise_row['target_threshold'].'</b></span> in <span class="text-danger"><b>'.$kpi_setting_user_wise_row['target_threshold_for_x_consecutive_month'].'</b></span> consecutive months':'<span class="text-danger">N/A</span>' ?>
                                
                            </b> 
                        </div>
                    </td>
                </tr>
            </tbody>
            
        </table>
    </div>
</div>
</div>
<div class="card">
<div class="card-body bg-white px-0 pb-2">
    <div class="table-responsive-mobile" >
        <table class="table m-b-0 th_color lead-board table-nocolor" id="lead_table">
            <thead>                
                <tr>
                    <th width="10%" class="text-left">Month</th>
                    <th width="10%" class="text-center">Score Obtained<br><small><i>(Out of 100)</i></small></th>
                    <th width="10%" class="text-center">Min. Threshold <br> for PIP</th>                    
                    <th width="10%" class="text-center">PLI</th>
                    <th width="20%" class="text-center">Self <br>Approval</th>
                    <th width="20%" class="text-center">Manager's <br>Approval</th>
                    <th width="20%" class="text-center">Comments</th>
                    <th width="10%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody> 
                <?php if(count($report_logs)){ $is_row_available='Y';?>
                    <?php foreach($report_logs AS $report){ ?>
                        <tr>
                            <td class="text-left">
                               <span class="same-line"> <?php echo date("F, Y ",strtotime($report['report_on'])); ?></span>                                    
                            </td>
                            <td class="text-center">
                                <?php 
                                // $total_score_obtained_tmp=($report['total_score_obtained']+$report['grace_score']); 
                                // echo ceil($total_score_obtained_tmp);
                                $total_score_obtained_tmp=($report['total_score_obtained_after_min_threshold_apply']+$report['grace_score']);
                                echo ceil($total_score_obtained_tmp);
                                ?>
                            </td>
                            <td class="text-center"><?php echo ($report['is_apply_pip']=='Y' && $report['target_threshold']>0)?$report['target_threshold']:'N/A'; ?></td>
                            
                            <td class="text-center">
                                <?php 
                                $pli=0;
                                if($report['is_apply_pli']=='Y' && $report['monthly_salary']>0 && $report['pli_in']>0)
                                {
                                    $pli_tmp=($report['pli_in']/100)*$report['monthly_salary'];
                                    $pli=($pli_tmp/100)*($report['total_score_obtained']+$report['grace_score']);
                                }
                                echo ($pli>0)?ceil($pli):'N/A'; 
                                ?>
                            </td>
                            <td class="text-center"><?php echo ($report['is_self_approved']=='Y')?'<span class="text-success">Approved</span>':'<span class="text-danger">Pending</span>'; ?></td>
                            <td class="text-center"><?php echo ($report['is_approved']=='Y')?'<span class="text-success">Approved</span>':'<span class="text-danger">Pending</span>'; ?></td>
                            <td class="text-center">
                                <?php if($report['is_approved']=='Y'){ ?><span class="same-line" style="color:#<?php echo $report['color_code']; ?>"><?php echo $report['txt']; ?></span><?php }else{ ?><span class="text-danger">Pending</span><?php } ?>
                                
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary badge-pill" style="background-color: #fff;">
                                    <a href="JavaScript:void(0);" class="icon-btn report_detail_view blue-link" data-id="<?php echo $report['id']; ?>" data-listing_from="mps">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>                                
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                        <tr>                            
                            <td class="text-center" colspan="8">No record found!</td>
                        </tr>
                <?php } ?>
                
            </tbody>
            
        </table>
    </div>
</div>
</div>

<?php if($is_row_available=='Y'){ ?>   
<!-- <div class="card-body bg-white px-0 pb-2"> -->
    <!-- <div class="table-responsive" > -->
        <?php if(count($kpi_definitions)){ ?>
            <div class="list-group">
                <button type="button" class="list-group-item list-group-item-action active">Comment Description Note</button>
            <?php foreach($kpi_definitions AS $def){ ?>
                <button type="button" class="list-group-item list-group-item-action"><?php echo $def['condition1']; ?> To <?php echo ($def['condition2']>=100)?'100 and above':$def['condition2']; ?>: <b style="color:#<?php echo $def['color_code']; ?>"><?php echo $def['txt']; ?></b></button>
            <?php } ?>
            </div>
        <?php } ?>
    <!-- </div> -->
<!-- </div> -->
<?php } ?>