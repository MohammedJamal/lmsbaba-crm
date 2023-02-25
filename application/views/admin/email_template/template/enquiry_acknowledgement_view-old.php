<?php print_r($compaitny); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i&display=swap" rel="stylesheet">
    <style type="text/css">
        body {font-family: 'Roboto', sans-serif;}
        .table-format {font-family: 'Roboto', sans-serif;border-collapse: collapse;}
        .table-format td,
        .table-format th {border: 1px solid #222;text-align: left;padding: 8px;font-size: 13px;}
    </style>
</head>

<body style="margin:0; padding:0;" bgcolor="#fff">
<table style="min-width:320px;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff">
<!-- fix for gmail -->
<tr>
    <td class="hide">
        <table width="600" cellpadding="0" cellspacing="0" style="width:600px !important;">
            <tr>
                <td style="min-width:600px; font-size:0; line-height:0;">&nbsp;</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td class="wrapper" style="padding:0 10px;">
        <!-- module 1 -->
        <table data-module="module-1" data-thumb="thumbnails/01.png" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td data-bgcolor="bg-module" bgcolor="#fff">
                    <table class="flexible" width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding:0px 15px; background: #fff; border-top: solid 4px #1e4e79;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="flex" width="300" align="left" style="padding:0;">
                    <table class="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <h4 style=" color: #444343;font-weight: 200; font-size: 16px; margin-bottom: 0; margin-top: 0px;"><?php echo $company['name']; ?></h4>
                                <div>
                                    <p style="width: 100%; float: left; color: #444343;font-size: 13px; margin: 0px; margin-top: 4px; font-weight:300;">
                                        <?php if(trim($company['city_name'])){echo $company['city_name'].',';}?>
                                        <?php if(trim($company['country_name'])){echo $company['country_name'];
                                        }?>
                                    </p>


                                </div>
                            </td>
                        </tr>
                    </table>
                </th>
                <th class="flex" align="left" style="padding:0;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td data-color="text" data-size="size navigation" data-min="10" data-max="22" data-link-style="text-decoration:none; color:#888;" class="nav" align="right" style="">
                               <div><img src="<?php echo base_url(); ?>accounts/lmsportal/company/logo/<?php echo $company['logo']; ?>" style=" width: 80px;"></div>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- module 2 -->
        <table data-module="module-2" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td data-bgcolor="bg-module" bgcolor="#fff">
                    <table class="flexible" width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding:0px 15px; background: #fff">
                                <table class="table-format" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0 auto; background: #fff">
                                <thead>
                                    <tr>

                                        <th style="background:#BDD6EE;font-weight: 300;">Enquiry Date</th>
                                        <th style="background:#BDD6EE;font-weight: 300;">Enquiry ID</th>
                                        <th style="background:#BDD6EE;font-weight: 300;">Enquiry Status</th>
                                        

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 13px;">
                                          <?php echo date_db_format_to_display_format($lead_info->enquiry_date); ?>
                                        </td>
                                        <td style="font-size: 13px;"><?php echo get_company_name_initials(); ?> - <?php echo $lead_info->id; ?></td>
                                        <td style="font-size: 13px;"><?php if($lead_info->current_stage=='PENDING'){echo 'IN PROCESS';}else{echo $lead_info->current_stage;} ?></td>
                                        
                                        

                                    </tr>
                                </tbody>
                                
                            </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
<!-- module 3 -->
<table data-module="module-2" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td data-bgcolor="bg-module" bgcolor="#fff">
            <table width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:10px 30px 5px;" bgcolor="#fff">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr style=" background: #fff; margin: 15px;">
                                <center><h2 style="font-size: 16px; font-weight: 300; color: #227ef0; margin-top: 0px;">Your Enquiry has been acknowledged</h2></center>
                            </tr>
                            <tr>
                                <td align="left" style="color:#292c34; font-size: 13px;">
                                   Dear <?php echo $customer->contact_person; ?>,
                                </td>
                            </tr>
                            <?php if($assigned_to_user['id']=='1'){ ?>
                            <tr>
                                <td align="left" style="color:#555; line-height: 16px; text-align: justify;">                                
                                    <p style="font-weight: 400; color: #3e3737; font-size: 13px; margin-top: 8px; margin-bottom: 8px;">Thank you for choosing to do business with <span style="font-weight: bold;font-size: 13px;"><?php echo $company['name']; ?>.</span> We have received
                                    your enquiry and  your <span style="font-weight: bold">RFQ No <?php echo $lead_info->id; ?>. </span>We are working on your requirement and shall get back to you with the best quotation and product catalogs / details at the earliest. Your relationship manager will contact you very soon.</p>
                                </td>
                            </tr>
                            <?php }else{ ?>
                            <tr>
                                <td align="left" style="color:#555; line-height: 16px; text-align: justify;">
                                    <p style="font-weight: 400; color: #3e3737; font-size: 13px; margin-top: 8px; margin-bottom: 8px;">Thank you for choosing to do business with <span style="font-weight: bold;font-size: 13px;"><?php echo $company['name']; ?>.</span> We have received
                                    your enquiry and it has been assigned to <span style="font-weight:bold;font-size: 13px;"><?php echo ($assigned_to_user['gender']=='M')?'Mr.':'Ms.'; ?> <?php echo $assigned_to_user['name']; ?></span> with <span style="font-weight: bold">RFQ No <?php echo $lead_info->id; ?>. </span><?php echo ($assigned_to_user['gender']=='M')?'He':'She'; ?> is working on your requirement and shall get back to you with the best quotation and product catalogs / details at the earliest.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#555; line-height: 23px; font-size: 13px;">
                                    <?php /* ?>
                                    <p style=" margin-top: 0px; font-weight: 400; color: #3e3737; font-size: 13px;">Feel free to contact <?php echo ($assigned_to_user['gender']=='M')?'Mr.':'Ms.'; ?> <?php echo $assigned_to_user['name']; ?>. 
                                        <?php echo ($assigned_to_user['gender']=='M')?'His':'Her'; ?> contact details are as given below:</p><?php */ ?>
                                    <p style=" margin-top: 0px; font-weight: 400; color: #3e3737; font-size: 13px;">Contact details of your relationship manager are given below:</p>
                                </td>
                            </tr>
                            <?php } ?>                            
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- module 3 -->
<?php if($assigned_to_user['id']!='1'){ ?>
<table data-module="module-2" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td data-bgcolor="bg-module" bgcolor="#fff">
            <table width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
                <tr style="background: #fff; padding:5px 30px 5px;">
                    <?php if($assigned_to_user['photo']){ ?>
                        <td style="width: 5%; padding-left: 30px;">
                          <div>
                            <img src="<?php echo base_url(); ?>accounts/lmsportal/admin/thumb/<?php echo $assigned_to_user['photo']; ?>" style="width: 80px;">
                          </div>  

                        </td>
                    <?php } ?>
                        <td align="" style=" width: 50%;  padding-left: 30px;">
                            <div>
                               <h3 style="margin: 4px 0; font-size: 13px;"><?php echo ($assigned_to_user['gender']=='M')?'Mr.':'Ms.'; ?> <?php echo $assigned_to_user['name']; ?></h3>
                               <p style="margin: 4px 0; font-size: 13px;">Mobile: <?php echo $assigned_to_user['mobile']; ?></p>
                               <p style="margin: 4px 0; font-size: 13px;">e-Mail: <?php echo $assigned_to_user['email']; ?></p>
                            </div>

                        </td>
                    </tr>
            </table>
        </td>
    </tr>
</table>
<?php } ?>
<table data-module="module-2" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td data-bgcolor="bg-module" bgcolor="#fff">
            <table width="600" align="center" style="background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
             <tr style="background: #fff;">
                        
                        <td align="left" style=" border-left: 0px; padding: 0px 30px 5px;">
                            <div>
                               <h3 style="margin: 0px;  font-weight: 600; font-size: 13px; margin: 4px 0; ">With Best Regards</h3>
                               <p style="font-size: 13px;margin: 4px 0;font-weight: 400;">Team, <?php echo $company['name']; ?></p>
                               <?php if($assigned_to_user['id']=='1'){ ?>
                               <p style="font-size: 13px;margin: 4px 0;font-weight: 400;">Mobile: <?php echo $company['mobile1']; ?></p>
                               <p style="font-size: 13px;margin: 4px 0;font-weight: 400;">e-Mail: <?php echo $company['email1']; ?></p>
                              <?php } ?>
                            </div>
                        </td>
                    </tr>
            </table> 
        </td>
    </tr>
</table>            
        
<table data-module="module-2" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td data-bgcolor="bg-module" bgcolor="#fff">
            <table width="600" align="center" style="background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
             <tr>
                        
                       <td align="" style=" width: 100%; text-align: center;border-left: 0px;">
                            <div>
                               <h3 style="margin: 0px; color: #888; font-size: 12px;  font-weight:400;padding-top:5px; "><?php echo $company['name']; ?></h3>
                               <p style="margin: 4px 0; line-height: 14px; font-size: 9px; color: #888; "><?php rander_company_address('email_template'); ?></p>
                            </div>
                        </td>
                    </tr>
            </table> 
        </td>
    </tr>
</table>            
                                                    
<table data-module="module-2" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td data-bgcolor="bg-module" bgcolor="#fff">
            <table width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
             <tr style="padding-top:5px;">    
                    <td align="right">
						<p style="margin: 4px 0; line-height: 14px; font-size: 9px; color: #888; ">Powered By: <a href="https://lmsbaba.com/" target="_blank" style="color: #888;">LMSbaba.com</a></p>
						<?php /* ?><div><img src="<?php echo assets_url(); ?>images/logo.png" width="100px;"></div><?php */ ?>
                    </td>
                    </tr>
            </table> 
        </td>
    </tr>
</table>           
</td>
</tr>
</table>
</body>
</html>