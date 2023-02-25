<?php print_r($compaitny); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i&display=swap" rel="stylesheet">
   <style type="text/css">
         body {
         font-family: 'Roboto', sans-serif;
         }
         .table-format {
         font-family: 'Roboto', sans-serif;
         border-collapse: collapse;
         }
         .table-format td,
         .table-format th {
         border: 1px solid #222;
         text-align: left;
         padding: 8px;
         font-size: 13px;
         }
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
        <table data-module="module-1" data-thumb="" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td data-bgcolor="bg-module" bgcolor="#fff">
                    <table class="flexible" width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding: 0px 20px 0px; background: #fff; border-top: solid 4px #1e4e79;">
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
                               <div><img src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/company/logo/<?php echo $company['logo']; ?>" style=" width: 80px;"></div>
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
                            <td style="padding: 0px 20px 0px; background: #fff">
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
                    <td style="padding:0px 20px 0px;" bgcolor="#fff">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr style=" background: #fff;">
                                <?php /* ?>
                                <center><h2 style="font-size: 16px; font-weight: 300; color: #227ef0; margin-top: 0px;">Your Enquiry has been acknowledged</h2></center>
                                <?php */ ?>
                                &nbsp;
                            </tr>
                            <tr>
                                <td align="left" style="color:#292c34; font-size: 13px;">
                                   Dear <?php echo $assigned_to_user['name']; ?>,
                                </td>
                            </tr>
							<tr>
                                <td align="left" style="color:#555; line-height: 16px; text-align: justify;">                                
                                    <p style="font-weight: 400; color: #3e3737; font-size: 13px; margin-top: 8px; margin-bottom: 8px;">
										<b>Lead ID - <?php echo $lead_info->id; ?> has been assigned to you in LMS.</b><br>										 
										<?php echo ($customer->contact_person)?'<b>Contact Person:</b> '.$customer->contact_person.'<br>':''; ?>
										<?php echo ($customer->mobile)?'<b>Contact No:</b> '.$customer->mobile.'<br>':''; ?>
										<?php echo ($customer->city_name)?'<b>City:</b> '.$customer->city_name.'<br>':''; ?>	
										<?php echo ($lead_info->title)?'<b>Lead Title:</b> '.$lead_info->title:''; ?>
									</p>
									<?php if($lead_info->buying_requirement){ ?>
									<p style="font-weight: 400; color: #3e3737; font-size: 13px; margin-top: 8px; margin-bottom: 8px;">
										<?php echo $lead_info->buying_requirement; ?>
									</p>
									<?php } ?>
									<p style="font-weight: 400; color: #3e3737; font-size: 13px; margin-top: 8px; margin-bottom: 8px;">
									Thanks
									</p>
									
                                </td>
                            </tr> 
							<tr>
                                <td align="left" style="color:#292c34; font-size: 13px;">&nbsp;</td>
                            </tr>
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
                    <?php /* ?><p style="margin: 4px 0; line-height: 14px; font-size: 9px; color: #888; ">Powered By: <a href="https://lmsbaba.com/" target="_blank" style="color: #888;">LMSbaba.com</a></p>
						<div><img src="<?php echo assets_url(); ?>images/logo.png" width="100px;"></div><?php */ ?>
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