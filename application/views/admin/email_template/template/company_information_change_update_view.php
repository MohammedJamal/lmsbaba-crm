<?php //print_r($assigned_to_user); ?>
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
                            <td style="padding:0px 15px; background: #fff">
                                <table class="table-format" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0 auto; ">
                                <thead>
                                    <tr><th style="padding: 0px; background: #1e4e79; border: 1px solid #1e4e79; "></th></tr>
                                </thead>
                                
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
              <center><h2 style="font-size: 16px; font-weight: 300; color: #227ef0; margin-top: 0px;"><?php echo ($assigned_to_user['gender']=='M')?'Mr.':'Ms.'; ?><?php echo $assigned_to_user['name']; ?> has updated the details of the Company  <?php echo $customer->company_name; ?> - #<?php echo $company_id; ?></h2></center>
            </tr>
            <tr>
                <td align="left" style="color:#292c34; font-size: 13px; padding-bottom:20px;">
                    <?php if(count($update_history_log)){ ?>
                    <?php
                    foreach($update_history_log as $log)
                    {
                    ?>
                    <b>
                    <?php
                    echo $company_field_alias[$log['updated_field']];
                    //echo str_replace('_',' ',$log['updated_field']); ?></b><br>
                    Old: <?php echo $log['before_update_value']; ?><br>
                    New: <?php echo $log['updated_value']; ?><br><br>
                    <?php
                    }
                    ?>
                    <?php } ?>
                    <?php if($cc_mail){ ?>
                    CC: <?php echo str_replace(",",", ",$cc_mail); ?>
                    <?php } ?>
                </td>
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
            <table width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
                <tr style="background: #fff; padding:5px 30px 5px;">
                    <td style="width: 25%; border-left: 0px; padding: 0px 0px 5px 30px;">
                        <h3 style="margin: 0px;  font-weight: 600; font-size: 13px; margin-bottom: 15px;">With Best Regards</h3>
                          <div>
                            <h3 style="margin: 4px 0; font-size: 13px;"><?php echo ($assigned_to_user['gender']=='M')?'Mr.':'Ms.'; ?><?php echo $assigned_to_user['name']; ?></h3>
                               <p style="margin: 4px 0; font-size: 13px;">Mobile: <?php echo $assigned_to_user['mobile']; ?></p>
                               <p style="margin: 4px 0; font-size: 13px;">e-Mail: <?php echo $assigned_to_user['email']; ?></p>
                              
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