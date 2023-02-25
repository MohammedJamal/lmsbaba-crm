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
// echo'<pre>';
// print_r($report_logs);
// echo'</pre>';

$is_row_available='N';
?>

<?php if($user_id){ ?>
<div class="card">
    <div class="card-body bg-white px-0 pb-2">
        <div class="table-responsive" >
            <table class="table m-b-0 th_color lead-board performance_scorecard_ajax" id="lead_table">
                <thead>                
                    <tr>
                        <th width="10%" class="text-left">Month</th>
                        <th width="10%" class="text-center">Score Obtained<br><small><i>(Out of 100)</i></small></th>
                        <th width="10%" class="text-left">Min. Threshold<br>for PIP</th>                    
                        <th width="10%" class="text-center">PLI</th>
                        <th width="20%" class="text-center">Self Approval</th>
                        <th width="20%" class="text-center">Manager's Approval</th>
                        <th width="20%" class="text-center">Comments</th>
                        <th width="10%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php if(count($report_logs)){ $is_row_available='Y';?>
                        <?php foreach($report_logs AS $report){ ?>
                            <tr>
                                <td class="text-left"><?php echo date("F, Y ",strtotime($report['report_on'])); ?></td>
                                <td class="text-center">
                                    <?php 
                                    $total_score_obtained_tmp=($report['total_score_obtained_after_min_threshold_apply']+$report['grace_score']);
                                    echo ceil($total_score_obtained_tmp);
                                    ?>    
                                    <?php //echo ceil($report['total_score_obtained']+$report['grace_score']); ?></td>
                                <td class="text-left"><?php echo ($report['is_apply_pip']=='Y' && $report['target_threshold']>0)?$report['target_threshold']:'N/A'; ?></td>
                                
                                <td class="text-center">
                                    <?php 
                                    $pli=0;
                                    if($report['is_apply_pli']=='Y' && $report['monthly_salary']>0 && $report['pli_in']>0)
                                    {
                                        $pli_tmp=($report['pli_in']/100)*$report['monthly_salary'];
                                        $pli=($pli_tmp/100)*$report['total_score_obtained'];
                                    }
                                    echo ($pli>0)?ceil($pli):'N/A'; 
                                    ?>
                                </td>
                                <td class="text-center"><?php echo ($report['is_self_approved']=='Y')?'<span class="text-success">Approved</span>':'<span class="text-danger">Pending</span>'; ?></td>
                                <td class="text-center"><?php echo ($report['is_approved']=='Y')?'<span class="text-success">Approved</span>':'<span class="text-danger">Pending</span>'; ?></td>
                                <td class="text-center"><?php if($report['is_approved']=='Y'){ ?><span class="same-line" style="color:#<?php echo $report['color_code']; ?>"><?php echo $report['txt']; ?></span><?php }else{ ?><span class="text-danger">Pending</span><?php } ?></td>
                                <td class="text-center">
                                    <span class="badge badge-primary badge-pill" style="background-color: #fff;">
                                        <a href="JavaScript:void(0);" class="icon-btn blue-link report_detail_view" data-id="<?php echo $report['id']; ?>">
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
                <tfooter></footer>
            </table>
        </div>
    </div>
</div>
<?php }else{ ?>
<div class="card">
    <div class="card-body bg-white px-0 pb-2">
        <div class="table-responsive" >
            <table class="table m-b-0 th_color lead-board table-nocolor" id="lead_table">
                <thead>                
                    <tr class="td-grey">
                        <th width="10%" class="text-left">Name</th>
                        <th width="10%" class="text-left">User ID</th>
                        <th width="10%" class="text-left">Month</th>
                        <th width="10%" class="text-center">Score Obtained<br><small><i>(Out of 100)</i></small></th>
                        <th width="10%" class="text-center">PLI</th>
                        <th width="20%" class="text-center">Self Approval</th>
                        <th width="20%" class="text-center">Manager's Approval</th>
                        <th width="20%" class="text-center">Comments</th>
                        <th width="10%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php if(count($report_logs)){ $is_row_available='Y';?>
                        <?php foreach($report_logs AS $report){ ?>
                            <tr>
                                <td class="text-left"><span class="same-line"><?php echo $report['user_name']; ?></span></td>
                                <td class="text-left"><?php echo $report['user_id']; ?></td>
                                <td class="text-left"><?php echo date("F, Y ",strtotime($report['report_on'])); ?></td>                            
                                <td class="text-center">
                                    
                                    <?php 
                                    $total_score_obtained_tmp=($report['total_score_obtained_after_min_threshold_apply']+$report['grace_score']);
                                    echo ceil($total_score_obtained_tmp);
                                    
                                    // echo ceil($report['total_score_obtained']+$report['grace_score']); 
                                    ?>
                                </td>
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
                                <td class="text-center"><?php if($report['is_approved']=='Y'){ ?><span class="same-line" style="color:#<?php echo $report['color_code']; ?>"><?php echo $report['txt']; ?></span><?php }else{ ?><span class="text-danger">Pending</span><?php } ?></td>
                                <td class="text-center">
                                    <span class="badge badge-primary badge-pill" style="background-color: #fff;">
                                        <a href="JavaScript:void(0);" class="icon-btn report_detail_view blue-link" data-id="<?php echo $report['id']; ?>" data-listing_from="ps">
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
                <tfooter></footer>
            </table>
        </div>
    </div>
</div>
<?php } ?>
<?php if($is_row_available=='Y'){ ?>   
<div class="card">
    <div class="card-body bg-white px-0 pb-2">
        <div class="table-responsive" >
        <?php if(count($kpi_definitions)){ ?>
            <div class="list-group">
                <button type="button" class="list-group-item list-group-item-action active">Comment Description Note</button>
            <?php foreach($kpi_definitions AS $def){ ?>
                <button type="button" class="list-group-item list-group-item-action"><?php echo $def['condition1']; ?> To <?php echo ($def['condition2']>=100)?'100 and above':$def['condition2']; ?>: <b style="color:#<?php echo $def['color_code']; ?>"><?php echo $def['txt']; ?></b></button>
            <?php } ?>
            </div>
        <?php } ?>
        </div>
    </div>
</div>
<?php } ?>
