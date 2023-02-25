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
                    <table class="flexible" width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;margin-top:50px;" cellpadding="0" cellspacing="0">
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
                                    <p style="width: 100%; float: left; color: #444343;font-size: 13px; margin: 0px; margin-top: 4px; font-weight:300;margin-bottom: 10px;"><?php if(trim($company['city_name'])){echo $company['city_name'].',';}?>
                                        <?php if(trim($company['country_name'])){echo $company['country_name'];
                                        }?></p></br>


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

<!-- module 3 -->
<table data-module="module-2" width="100%" cellpadding="0" cellspacing="0">
    <tr>
    <tr>
        <td data-bgcolor="bg-module" bgcolor="#fff">
            <table width="600" align="center" style=" background: #ccc; padding: 0px 20px 0px; margin:0 auto;" cellpadding="0" cellspacing="0">
             
                <tr>
                    <td style="padding:10px 30px 5px;" bgcolor="#fff">
                        <table width="100%" cellpadding="0" cellspacing="0">
                           <tr style=" background: #fff; margin: 15px;">
              <center><h2 style="font-size: 16px; font-weight: 300; color: #227ef0; margin-top: 0px;">Congratulations <?php echo $name?>! Your login has been created.</h2></center>
            </tr>
            <tr>
                <td align="left" style="color:#292c34; font-size: 13px; padding-bottom:20px;">
                   Dear <?php echo ($gender=='M')?'Mr.':'Ms.'; ?><?php echo $name?>,</br>
                   <p style="margin-top: 10px;">
                   We are happy to onboard you with our organization&apos;s Lead Management System (LMS). Your User ID is <?php echo $user_id?>. </p>
                    <p style="margin-top: 1px;font-size: 14px;"><b>LMS Link:</b>
                        <a href="<?php echo base_url(); ?>" target="_blank"><?php echo base_url(); ?></a><br>
                        <b>User ID/ Username:&nbsp;</b><?php echo $user_id?> / <?php echo $username; ?><br>
                        <b>Password:&nbsp;</b><?php echo $password?>
                    </p>
                   
                </td>
            </tr>
            <tr>
                <td align="left" style="color:#292c34; font-size: 13px; padding-bottom:10px;">
                   Your login details are as below:
                   
                </td>
            </tr>
                            <tr>
                                <td align="left" style="color:#555; line-height: 16px; text-align: justify;">
                                <p style="font-weight: 400; color: #3e3737; font-size: 13px; margin-top: 8px; margin-bottom: 8px;">You are requested to login in your LMS account and change your password. For any query, contact to your HR team.</p>
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
                    <td style="width: 20%; border-left: 0px; padding: 0px 0px 5px 30px;">
                        <h3 style="margin: 0px;  font-weight: 600; font-size: 13px; margin-bottom: 8px;">With Best Regards</h3>
                          <p style="margin: 4px 0; font-size: 13px;">
                            <?php if($assigned_to_user['name']){
                                echo ($assigned_to_user['gender']=='M')?'Mr.':'Ms.'; 
                                echo $assigned_to_user['name'].'<br>';} 
                            ?>
                            <?php echo $company['name']; ?>
                          </p>
                    </td>
                    <!-- <td align="" style=" width: 50%;  border-left: 0px;">
                        <div style="margin-top: 15px;">
                               <h3 style="margin: 4px 0; font-size: 13px;"><?php echo $assigned_to_user['name']; ?></h3>
                               <p style="margin: 4px 0; font-size: 13px;">Mobile: <?php echo $assigned_to_user['mobile']; ?></p>
                               <p style="margin: 4px 0; font-size: 13px;">e-Mail: <?php echo $assigned_to_user['email']; ?></p>
                        </div>

                        </td> -->
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
                    <div><img src="<?php echo assets_url(); ?>images/logo-mail.png" width="100px;"></div>
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