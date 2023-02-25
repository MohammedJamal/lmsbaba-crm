<?php //print_r($user_wise_stage); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title></title>
<style>
/* A simple css reset */

</style>
</head>

<body>
<table width="100%">
<tbody>
<tr>
<td class="wrapper" width="600" align="center">
<!-- Header image -->
<table class="section header" border="0" cellpadding="0" cellspacing="0" width="800" style="border:1px solid #DDD;">
<tr>
    <td>
        <h2 style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 22px; font-weight: 700; color: #5f83b7; text-align: center; margin: 0; background: #DDD; padding: 7px 0;">Day Report - All</h2>
    </td>
</tr>
<tr>
    <td>
        <table cellpadding="0" cellspacing="0" width="100%" border="0">

            <tbody>
                <tr>
                    <th style="border-bottom:1px solid #000; border-top:1px solid #000;text-align: left; font-weight:700; padding:10px 15px; background: #EEE;" width="70%">Date</th>
                    <th style="border-bottom:1px solid #000;border-top:1px solid #000;font-weight: 700;padding:10px 15px; text-align:center;background: #EEE" width="30%"><?php echo date_db_format_to_display_format($report_date); ?></th>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px;">New Leads</th>
                    <th style="border-bottom:1px solid #000;font-weight:400; padding:10px 15px;"><?php echo $new_lead_report['new_lead_count']; ?></th>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px;">Updated Leads</th>
                    <th style="border-bottom:1px solid #000;font-weight:400; padding:10px 15px;"><?php echo $updated_lead_report['updated_lead_count']; ?></th>
                </tr>
                <?php if(count($stage_wise_report)){ ?>
                <?php foreach($stage_wise_report AS $all){
                    if($all['stage_wise_lead_count']>0){ 
                 ?>
                    <tr>
                        <th style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px"><?php echo $all['stage_name']; ?></th>
                        <th style="border-bottom:1px solid #000;font-weight:400; padding:10px 15px;"><?php echo $all['stage_wise_lead_count']; ?></th>
                    </tr>            
                <?php }} ?>
                <?php } ?>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td style="height:20px"></td>
</tr>


<tr>
    <td>
        <h2 style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 22px; font-weight: 700; color: #5f83b7; text-align: center; margin: 0; background: #DDD; padding: 7px 0;">Day Report - Existing Buyer</h2>
    </td>
</tr>
<tr>
    <td>
        <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tbody>
                <tr>
                    <th style="border-bottom:1px solid #000; border-top:1px solid #000;text-align: left; font-weight:700; padding:10px 15px; background: #EEE;" width="70%">Date</th>
                    <th style="border-bottom:1px solid #000;border-top:1px solid #000;font-weight: 700;padding:10px 15px; text-align:center;background: #EEE" width="30%"><?php echo date_db_format_to_display_format($report_date); ?></th>

                </tr>
                <tr>
                    
                    <th style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px;">New Leads</th>
                    <th style="border-bottom:1px solid #000;font-weight:400; padding:10px 15px;"><?php echo $new_lead_report['paying_customer_new_lead_count']; ?></th>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px;">Updated Leads</th>
                    <th style="border-bottom:1px solid #000;font-weight:400; padding:10px 15px;"><?php echo $updated_lead_report['paying_customer_updated_lead_count']; ?></th>
                </tr>
                <?php if(count($stage_wise_report)){ ?>
                <?php foreach($stage_wise_report AS $all){
                    if($all['paying_customer_stage_wise_lead_count']>0){ 
                 ?>
                    <tr>
                        <th style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px;"><?php echo $all['stage_name']; ?></th>
                        <th style="border-bottom:1px solid #000;font-weight:400; padding:10px 15px;"><?php echo $all['paying_customer_stage_wise_lead_count']; ?></th>
                    </tr>            
                <?php }} ?>
                <?php } ?>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td style="height:20px"></td>
</tr>


<tr>
    <td>
        <h2 style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 22px; font-weight: 700; color: #5f83b7; text-align: center; margin: 0; background: #DDD; padding: 7px 0;">User Wise Day Report on <?php echo date_db_format_to_display_format($report_date); ?></h2>
    </td>
</tr>
<tr>
    <td>
        <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tbody>
                <tr>
                    <th style="border-bottom:1px solid #000; border-top:1px solid #000;text-align: left; font-weight:700; padding:10px 15px; background: #EEE;" align="left" width="12%">User</th>
                    <th style="border-bottom:1px solid #000; border-top:1px solid #000;text-align: center; font-weight:700; padding:10px 15px; background: #EEE;" align="left" width="12%">New</th>
                    <th style="border-bottom:1px solid #000;border-top:1px solid #000;font-weight: 700;padding:10px 15px; text-align:left;background: #EEE" align="left" width="76%">Stage Wise Count (all/ existing buyer)</th>
                </tr>
                <?php if(count($user_wise_new_leads)){ ?>
                    <?php foreach($user_wise_new_leads AS $row){ ?>
                <tr>
                    <td style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px;"><?php echo $row['assigned_user_name']; ?></td>
                    <td style="border-bottom:1px solid #000; text-align: center; font-weight:400; padding:10px 15px;"><?php echo $row['new_lead_count']; ?></td>
                    <td style="border-bottom:1px solid #000; text-align: left; font-weight:400; padding:10px 15px; font-size: 12px;" align="left">
                        <?php 
                        // print_r($user_wise_stage[$report_date][$row['assigned_user_id']]);
                        if(count($user_wise_stage[$report_date][$row['assigned_user_id']])>0 && isset($user_wise_stage[$report_date][$row['assigned_user_id']])){ $i=1;?>
                        <?php foreach($user_wise_stage[$report_date][$row['assigned_user_id']] AS $row_status){ ?>
                            
                                <b><?php echo str_replace(' ', '', $row_status['stage_name']); ?>:</b> <font size="4"><?php echo $row_status['stage_wise_lead_count']; ?>/ <?php echo $row_status['paying_customer_stage_wise_lead_count']; ?></font>
                                <?php
                                if(count($user_wise_stage[$report_date][$row['assigned_user_id']])>$i)
                                {
                                    echo "&nbsp;|&nbsp;";
                                }
                                ?> 
                        <?php $i++;} ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>                
                <?php }else{ ?>
                <tr>
                    <td colspan="3">No record found!</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td style="height:20px"></td>
</tr>


<?php 
/*
if(count($user_wise_stage[$report_date])){ ?>
    <?php foreach($user_wise_stage[$report_date] AS $key=>$val){ ?>
<tr>
    <td>
        <h2 style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 22px; font-weight: 700; color: #5f83b7; text-align: center; margin: 0 0 15px; background: #DDD; padding: 7px 0;">Day Report on <?php echo date_db_format_to_display_format($report_date); ?> for the user - <?php echo $val[0]['assigned_user_name']; ?></h2>
    </td>
</tr>
<tr>
    <td>
        <?php print_r($val); ?>
        <table cellpadding="2" cellspacing="2" width="100%" border="0">
            <tbody>
                <tr>
                    <td style=" font-size: 12px;" align="left">
                <?php if(count($val)){ $i=1;?>
                <?php foreach($val AS $row){ ?>
                    
                        <b><?php echo $row['stage_name']; ?>:</b> <font size="4"><?php echo $row['stage_wise_lead_count']; ?></font>
                        <?php
                        if(count($val)>$i)
                        {
                            echo "&nbsp;|&nbsp;";
                        }
                        ?> 
                <?php $i++;} ?>
                <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td style="height:20px"></td>
</tr>
<?php } ?>
<?php }*/ ?>



</table>

</td>
</tr>
</tbody>
</table>
</body>
</html>