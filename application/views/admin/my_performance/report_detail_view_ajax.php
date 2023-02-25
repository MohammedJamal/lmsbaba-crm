<?php
// echo $user_id;
// print_r($report); 
$is_custom_kpi_editable='N';
if($user_id==$report['user_id'])
{
    if($report['is_self_approved']=='N')
    {
        $is_custom_kpi_editable='Y';
    }
    else
    {
        $is_custom_kpi_editable='N';
    }
}
else
{
    if($report['is_approved']=='N')
    {
        $is_custom_kpi_editable='Y';
    }
    else
    {
        $is_custom_kpi_editable='N';
    }
}
?>
<div class="card">
<div class="card-body bg-white px-0 pb-2">
    <div class="table-responsive" >
        <table class="table m-b-0 th_color lead-board table-nocolor report_detail_view_ajax" id="lead_table" style="width:98%">
            <thead>
                <tr>
                    <td colspan="7" class="td-grey">

                    <div class="row ">
                        <div class="col-md-6 text-left">
                            <b class="text-italic"><?php echo $user_info['u_user_name']; ?><br>
                            User Id: <?php echo $user_info['u_user_id']; ?></b>
                        </div>
                        <div class="col-md-6 text-right">
                            <b class="text-italic">Month: <?php echo date("M, Y");?><br>
                            Manager: <?php echo ($user_info['u_user_manager_name'])?$user_info['u_user_manager_name']:'N/A'; ?></b>
                        </div>
                    </div> 
                    </td>
                </tr>                
                <tr>
                    <th width="70" class="text-left">SL.</th>
                    <th class="text-left">KPIs</th>
                    <th class="text-center">Weighted Score<br><small><i>(Out of 100)</i></small></th>
                    <th class="text-center">Target</th>
                    <th class="text-center">Min. Target<br> Threshold</th>
                    <th class="text-center">Achieved</th>
                    <th class="text-center">Score Obtained</th>
                </tr>
            </thead>
            <tbody> 
                <?php if(count($rows)){ $i=1;$total_weighted_score=0;$total_score_obtained=0?>
                    <?php foreach($rows AS $row){ ?>
                        <?php
                        $weighted_score=$row['weighted_score']; 
                        $achieved=$row['achieved'];
                        $target=$row['target'];
                        $min_target_threshold=$row['min_target_threshold'];
                        $score_obtained=($achieved>=$min_target_threshold)?$row['score_obtained']:0;
                        $total_weighted_score=$total_weighted_score+$weighted_score;
                        $total_score_obtained=$total_score_obtained+$score_obtained;
                        ?>
                        <tr>
                            <td class="text-left"><?php echo $i; ?></td>
                            <td class="text-left"><?php echo $row['kpi_name']; ?></td>
                            <td class="text-center"><?php echo $weighted_score; ?></td>
                            <td class="text-center"><?php echo $target; ?></td>
                            <td class="text-center"><b><?php echo ($min_target_threshold>0)?'<span class="text-danger">'.$min_target_threshold.'</b>':'N/A'; ?></b></td>
                            <td class="text-center">
                                <div class="new-per-span">
                                    <span class="per-bg-ele" id="report_target_achieved_<?php echo $row['id']; ?>">
                                        <?php echo $achieved; ?>                                            
                                    </span>                                        
                                    <?php if($row['is_kpi_system_generated']=='N' && $is_custom_kpi_editable=='Y'){ ?>
                                        <a href="JavaScript:void(0);" class="report_target_achieved_edit pos-abso" title="Edit" id="report_target_achieved_edit_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>                                        
                                        <div class="per-bg-ele-new" id="report_target_achieved_edit_view_<?php echo $row['id']; ?>" style="display:none">

                                            <span class="w-100">
                                                <input type="text" class="span-input only_natural_number" name="" id="report_target_achieved_value_<?php echo $row['id']; ?>" value="<?php echo $achieved; ?>" style="width:80px;" />
                                            </span>                                            
                                            <a href="JavaScript:void(0);" class="report_target_achieved_save pos-abso top-0" data-id="<?php echo $row['id']; ?>" data-kpi_setting_id="<?php echo $kpi_setting_id; ?>" data-user_id="<?php echo $user_id; ?>" title="Save" id="target_achieved_save_<?php echo $row['id']; ?>"><i class="fa fa-save" aria-hidden="true" style=""></i></a>
                                            <a href="JavaScript:void(0);" class="report_target_achieved_close pos-abso bottom-0" data-id="<?php echo $row['id']; ?>" title="Close" id="report_target_achieved_close_<?php echo $row['id']; ?>"><i class="fa fa-times-circle-o" aria-hidden="true" style="color:red"></i></a>
                                        </div>
                                    <?php } ?>                           
                                </div>

                            </td>
                            <td class="text-center"><?php echo ceil($score_obtained); ?></td>
                        </tr>                 
                    <?php } ?>
                        <tr class="td-grey">
                            <td colspan="2" class="text-right">
                                <b>Total Score:</b>
                            </td>
                            <td class="text-center">
                                <?php echo $total_weighted_score; ?>
                            </td>
                            <td colspan="4" class="text-right">
                                <b class="text-success font-size-18">Achived Score: <?php echo ceil($total_score_obtained); ?></b>
                            </td>
                        </tr>
                    <?php }else{ ?>
                        <tr>                            
                            <td class="text-center" colspan="7">No record found!</td>
                        </tr>
                    <?php } ?>
                
            </tbody>
            
        </table>
    </div>
</div>
</div>
<form id="frm_report_log">
<input type="hidden" id="kpi_user_wise_report_log_id" name="kpi_user_wise_report_log_id" value="<?php echo $report['id']; ?>" >
<input type="hidden" id="listing_from" name="listing_from" value="<?php echo $listing_from; ?>" >
<?php if($user_id==$report['user_id']){ //SELF ?>
    <?php if($report['is_self_approved']=='Y' && $report['is_approved']=='Y'){ ?>
        <div class="row">
            <div class="col-md-9">
                
                <div class="form-group mb-0 form-group-sm">  
                    <div class="col-sm-12 text-left">
                    Score Achived: <?php echo $total_score_obtained; ?> + Grace Score: <?php echo $report['grace_score']; ?> = Total Score: <?php echo ceil($total_score_obtained+$report['grace_score']); ?>
                    </div>                  
                </div>
            </div>
            <div class="col-md-3 text-left">
                <b>Approval Status:</b> <?php echo ($report['is_approved']=='Y')?'<span class="text-success">Approved</span>':'Not Approved'; ?><br>
                <b>Approved By:</b> <?php echo $report['approved_by_name']; ?><br>
                <b>Approval Date:</b> <?php echo date_db_format_to_display_format($report['approved_datetime']); ?>
            </div>
        </div>
        <?php if($report['is_apply_pli']=='Y'){ ?>
            <?php
            $pli=0;
            $pli=($report['pli_in']/100)*$report['monthly_salary'];
            $pli_earned=($pli/100)*($report['total_score_obtained']+$report['grace_score']);
            ?>
        <div class="form-group row">                                
            <div class="col-sm-12">
                <h5>Performance Linked Incentive Applicable</h5>
                <p>Monthly Salary Amount on which PLI is applied: INR <?php echo $report['monthly_salary']; ?></p> 
                <p>Monthly PLI Component @ <?php echo $report['pli_in']; ?>%: INR <?php echo number_format($pli,2); ?> </p> 
                <p>PLI Earned for the month of <?php echo date("F, Y ",strtotime($report['report_on'])); ?>: INR <?php echo number_format($pli_earned,2); ?> </p> 
            </div>                        
        </div>
        <?php } ?>
    <?php } ?>


    <?php if($report['is_self_approved']=='N'){ ?>
    <div class="card-body bg-white px-0 pb-2">
        <div class="table-responsive" >
            <table class="table m-b-0 th_color lead-board no-border-nopadding" id="" style="width:98%">
                <tr>
                    <td>
                        <div class="form-group row">                                
                            <div class="col-sm-12">
                                <label class="col-form-label"><b>Comment:<span class="text-danger">*</span></b></label>
                                <textarea name="self_comment" id="self_comment" placeholder="" rows="5" cols="10" class="form-control-noo span-input text-left maxh-70"></textarea>
                            </div>                        
                        </div>
                        <div class="form-group mb-0 row">                                
                            <div class="col-sm-12">
                                <div class="fl-left">
                                    <label class="up-label mb-0" for="self_file_name">
                                       <div class="attachment_clip"></div><small>Click to Attach Documents</small>
                                       <input type="file" name="self_file_name" id="self_file_name" style="display: none;">
                                    </label>
                                    <div class="upload-name-holder" style="display: none;">
                                        <div class="fname_holder" id="attach_file_outer" style="">
                                            <span id="attach_file_div">656670.jpg</span>
                                            <a href="JavaScript:void(0)" data-filename="" class="file_close" id="attach_file_div_close"><i class="fa fa-times" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>
                        <div class="form-group mb-0 row">                                
                            <div class="col-sm-12 text-right mt-0"><a href="javascript:void(0)" class="btn btn-success btn-bus btn-lg mt-0" id="self_approve_the_report_confirm">Submit</a></div>                        
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php }else{ ?>
        <div class="form-group row">     
            <?php if($report['self_comment']){ ?>                          
            <div class="col-sm-12">
                <h5>Commented by <?php echo $report['self_approved_by_name']; ?></h5>
                <p><?php echo $report['self_comment']; ?></p>  
            </div>   
            <?php } ?>
            <?php if($report['self_file_name']){ ?>          
            <div class="col-sm-12">
                <label class="up-label" for="lead_attach_file">
                    <div class="attachment_clip"></div>
                    <small>
                        <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/my_performance/download/<?php echo base64_encode($report['self_file_name']); ?>" >Download</a>
                    </small>                                
                </label>    
            </div>
            <?php } ?>
        </div>
        <?php if($report['is_approved']=='Y'){ ?>
        <div class="form-group row">     
            <?php if($report['comment_on_approved']){ ?>                             
            <div class="col-sm-12">
                <h5>Commented by <?php echo $report['approved_by_name']; ?></h5>
                <p><?php echo $report['comment_on_approved']; ?></p>  
            </div> 
            <?php } ?>
        </div>
        <?php } ?>
    <?php } ?>

<?php }else{ // MANAGER/ ADMIN ?>
    <div class="card-body bg-white px-0 pb-2">
    <div class="table-responsive" >
        <table class="table m-b-0 th_color lead-board no-border-nopadding" id="" style="width:98%">
            <tr>
                <td class="text-left">                    
                    <div class="form-group form-group-sm">
                        <div class="target-footer-content">
                            <div class="text-left d-inline-flex mr-10">
                                <strong>Score Achived</strong>
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                                <input class="span-input maxw-80" type="text" id="total_score_obtained" value="<?php echo $total_score_obtained; ?>" readonly="true">
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                                <strong>+</strong>
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                                <strong>Grace Score</strong>
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                                <input class="span-input maxw-80" type="number" id="grace_score" value="<?php echo $report['grace_score']; ?>" <?php if($report['is_approved']=='Y'){ echo'disabled'; }?>>
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                                <strong>=</strong>
                            </div>
                            <div class="text-left d-inline-flex mr-10">
                                <strong>Total Score</strong>
                            </div>
                            <div class="text-left d-inline-flex mr-0">
                                <input class="span-input maxw-80" type="text" id="total_score_obtained_after_grace" readonly="true" value="<?php echo ceil($total_score_obtained+$report['grace_score']); ?>">
                            </div>
                        </div>
                        
                                                   
                    </div>
                    <?php if($report['is_apply_pli']=='Y'){ ?>
                    <?php
                    $pli=0;
                    $pli=($report['pli_in']/100)*$report['monthly_salary'];
                    $pli_earned=($pli/100)*($report['total_score_obtained']+$report['grace_score']);
                    ?>
                    <div class="form-group row">                                
                        <div class="col-sm-12">
                            <h5 class="blue-link"><strong>Performance Linked Incentive Applicable</strong></h5>
                            <p class="mb-05">Monthly Salary Amount on which PLI is applied: <strong>INR <?php echo $report['monthly_salary']; ?></strong></p> 
                            <p class="mb-05">Monthly PLI Component @ <?php echo $report['pli_in']; ?>%: <strong>INR <?php echo number_format($pli,2); ?></strong> </p> 
                            <p class="mb-0">PLI Earned for the month of <?php echo date("F, Y ",strtotime($report['report_on'])); ?>: <strong>INR <span class="pli_earned"><?php echo number_format($pli_earned,2); ?></span></strong> </p> 
                        </div>                        
                    </div>
                    <?php } ?>
                    <div class="form-group row">     
                        <?php if($report['self_comment']){ ?>                          
                        <div class="col-sm-12">
                            <div class="grey-bg-box">
                                <h5>Commented by <?php echo $report['self_approved_by_name']; ?></h5>
                                <p><?php echo $report['self_comment']; ?></p>  
                            </div>
                        </div>   
                        <?php } ?>
                        <?php if($report['self_file_name']){ ?>          
                        <div class="col-sm-12">
                            <label class="up-label" for="lead_attach_file">
                                <div class="attachment_clip"></div><small><a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/my_performance/download/<?php echo base64_encode($report['self_file_name']); ?>" >Download</a></small>                                
                            </label>    
                        </div>
                        <?php } ?>
                    </div>   
                    <?php if($report['is_approved']=='Y'){ ?>
                    <div class="form-group row">     
                        <?php if($report['comment_on_approved']){ ?>                             
                        <div class="col-sm-12">
                            <h5>Commented by <?php echo $report['approved_by_name']; ?></h5>
                            <p><?php echo $report['comment_on_approved']; ?></p>  
                        </div> 
                        <?php } ?>
                    </div>
                    <?php }else{ ?>
                    <div class="form-group row">                                
                        <div class="col-sm-12 mt-0">
                            <label class="col-form-label"><b>Your Comment:<span class="text-danger">*</span></b></label>
                            <textarea name="comment_on_approved" id="comment_on_approved" placeholder="Comment" rows="5" cols="10" class="form-control"></textarea>
                        </div>   
                        <div>&nbsp;</div>
                        <div class="col-sm-12 text-right mt-0" id="approved_html">
                            <?php if($report['is_approved']=='Y'){ ?>
                            <button type="button" class="btn">Approved by <?php echo $report['approved_by_name']; ?> on <?php echo datetime_db_format_to_display_format_ampm($report['approved_datetime']); ?></button>
                            <?php }else{ ?>
                            <a href="javascript:void(0)" class="btn btn-success btn-bus btn-lg mt-0" id="approve_the_report_confirm">Save & Approve</a></div>
                            <?php } ?>
                        </div>                     
                    </div> 
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php } ?>
</form>




