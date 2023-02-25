<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>email Templateo</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <style type="text/css">
        html {
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        a{
            text-decoration: none;
            color: #41849F;
            font-weight: 600;
        }
        .title{
            color: #000;
        }
        .ref-no{
            font-weight: 600;
            color: #000;
        }
        .logo-brand img{
            height: 90px;
            object-fit: contain;
        }
        .mob_center {
         padding-left: 50px;
        }
        .table-box {
            padding: 0 0 15px 65px;
            background: 
            #e6e6e6b0;
            border: 1px dashed
            #c4c4c4 !important;
            text-align: center;
            margin: 0 auto;
            }
        @media only screen and (min-device-width: 750px) {
            .table750 {
                width: 750px !important;
            }
        }

        @media only screen and (max-device-width: 750px),
        only screen and (max-width: 750px) {
            table[class="table750"] {
                width: 100% !important;
            }

            .mob_b {
                width: 93% !important;
                max-width: 93% !important;
                min-width: 93% !important;
            }

            .mob_b1 {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 100% !important;
            }

            .mob_left {
                text-align: left !important;
            }

            .mob_soc {
                width: 50% !important;
                max-width: 50% !important;
                min-width: 50% !important;
            }

            .mob_menu {
                width: 50% !important;
                max-width: 50% !important;
                min-width: 50% !important;
                box-shadow: inset -1px -1px 0 0 rgba(255, 255, 255, 0.2);
            }

            .mob_center {
                padding-left: 0px;
                text-align: left !important;
            }

            .top_pad {
                height: 15px !important;
                max-height: 15px !important;
                min-height: 15px !important;
            }

            .mob_pad {
                width: 15px !important;
                max-width: 15px !important;
                min-width: 15px !important;
            }
            table.info-table td {
                display: block;
                width: 95%!important;
            }

            .mob_div {
                display: block !important;
            }
        }

        @media only screen and (max-device-width: 550px),
        only screen and (max-width: 550px) {
            .mod_div {
                display: block !important;
            }
            table.info-table td {
                display: block;
                width: 95%!important;
            }
        }

        .table750 {
            width: 750px;
        }
        .logo-brand {
            /*border-bottom: 1px solid #c4c4c4;*/
            padding: 5px;
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
<table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background: #f3f3f3; min-width: 350px; font-size: 1px; line-height: normal;">
<tr>
    <td align="center" valign="top">
        <table cellpadding="0" cellspacing="0" border="0" width="750" class="table750"
                    style="width: 100%; max-width: 750px; min-width: 350px; background: #f3f3f3;">
            <tr>
                <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;
                </td>
                <td align="center" valign="top" style="background: #ffffff;">
                           
                           
                <div style=" line-height: 25px; font-size: 16px;">&nbsp;</div>
                <table cellpadding="0" cellspacing="0" border="0" width="88%"
                style="width: 88% !important; min-width: 88%; max-width: 88%; border-radius: 6px; border-color: rgb(240, 240, 240); margin-bottom: -25px;" class="info-table">
                    <tbody>
                        <tr>
                            <td style="width: 100%; padding: 0px;" align="left" valign="top">
                                <font face="'Source Sans Pro', sans-serif" color="#585858"
                                style="font-size: 24px; line-height: 32px;">
                                <span style="font-family: Calibri, Candara, Segoe, sans-serif; color: #585858; font-size: 16px; line-height: 20px;">

                                    <span style="font-size: 20px; letter-spacing: .6px; color: #090909"><?php if(trim($company['name'])){echo $company['name'].',';}?>
                                    </span><br>
                                    <?php if(trim($company['address'])){echo $company['address'];}?>
                                    <br>      
                                    <?php if(trim($company['city'])){echo $company['city'].',';}?>
                                    <?php if(trim($company['state'])){echo $company['state'];}?>
                                    <?php if(trim($company['pin'])){echo '-'.$company['pin'];}?>
                                    <?php if(trim($company['country'])){echo ','.$company['country'];}?>
                                </span>
                            
                            </td>
                             <td style="width: 100%; padding-bottom: 20px;" align="center" valign="top">
                                <div class="logo-brand">
                                    <?php
                                    $logo=$company['logo'];
                                    if($logo!='')
                                    {
                                      $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/thumb/".$logo;
                                    }
                                    else
                                    {
                                      $profile_img_path = assets_url().'images/user_img_icon.png';
                                    }
                                    
                                    ?>
                                    <img src="<?php echo $profile_img_path;?>" alt="<?php echo $company['name'];?>">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style="background:#d7d7d775;padding: 1px 0; margin: 0 auto; width: 90%; ">
                    <h3 style="text-transform: uppercase;font-size: 28px;padding: 0;margin: 10px 0;font-family: Calibri, Candara, Segoe, sans-serif; color: #090909; font-weight: 600;">QUOTATION</h3>
                </div>

                <table cellpadding="0" cellspacing="0" border="0" width="88%"
                style="width: 88% !important; min-width: 88%; max-width: 88%; border-radius: 6px; border-color: rgb(240, 240, 240);" class="info-table">
                    <tbody>
                        <tr>
                            <td style="width: 100%; padding: 0px;" align="left" valign="top">
                                <div style=" line-height: 25px; font-size: 16px;">&nbsp;</div>
                                <font face="'Source Sans Pro', sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                                    <span style="font-family:  Calibri, Candara, Segoe, sans-serif; color: #090909; font-size: 16px; line-height: 20px;">
                                       <span style="font-weight: 700;color: #090909;">
                                            To,<br>
                                        <?php echo $customer['contact_person']; ?><br>  
                                       </span>
                                        <?php if(trim($customer['company_name'])){echo $customer['company_name'].',';}?>
                                        <?php if(trim($customer['city'])){echo $customer['city'].',';}?>
                                        <?php if(trim($customer['country'])){echo $customer['country'];}?>.
                                    </span>  
                                </font>                              
                            </td>
                        </tr>
                    </tbody>
                </table>
                            
                <table cellpadding="0" cellspacing="0" border="0" width="88%"
                    style="width: 88% !important; min-width: 88%; max-width: 88%;">
                    <tr>
                        <td align="left" valign="top">
                            <font face="Arial, Helvetica, sans-serif" color="#1a1a1a"
                                style="font-size: 52px; line-height: 60px; font-weight: 300; letter-spacing: -1.5px;">
                                <span
                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 14px; line-height: 28px; font-weight: 600; letter-spacing: .2px;">Dear Sir/Madam,</span>
                            </font>

                            <div style="height: 15px; line-height: 20px; font-size: 18px;">&nbsp;</div>
                                        
                            <font face="Arial, Helvetica, sans-serif;" color="#585858"
                                style="font-size: 24px; line-height: 32px;">
                                <span
                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;">This is <b><?php echo $logged_in_user_data['name']; ?></b> and I am taking care of your enquiry dated <b><?php echo date_db_format_to_display_format($quotation['quote_date']); ?></b>. We are indeed thankful to you for referring us and evincing interest in our products. I studied your requirement carefully. Please find here with our most exclusive and technically viable offer for your perusal. We are glad to submit our offer. </span>
                            </font>

                            <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>
                                        
                            <table cellpadding="0" cellspacing="0" border="0" width="88%"
                                style="width: 100% !important;min-width: 100%;max-width: 100%;border-radius: 6px;border-color:rgb(240, 240, 240);text-align: center!important;margin: 0 auto;">
                                <tbody>
                                    <tr>
                                        <td style="width: 33%;text-align: center;border: 1px solid#99c1e5;">
                                            <font face="Arial, Helvetica, sans-serif;" color="#585858"
                                            style="font-size: 24px; line-height: 32px;">
                                            <span style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;font-weight: 400;">
                                               Quotation Date: <br><span style="font-weight: 300;">
                                                   <span style="font-weight: bold;"><?php echo date_db_format_to_display_format($quotation['quote_date']); ?></span>
                                               </span>                                       
                                            </span>
                                            
                                        </td>
                                        <td style="width: 33%;text-align: center;border: 1px solid#99c1e5;">
                                            <font face="Arial, Helvetica, sans-serif;" color="#585858"
                                            style="font-size: 24px; line-height: 32px;">
                                            <span
                                                style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;font-weight: 400;">
                                                Quote No: <br><span style="font-weight: 300;">
                                                    <span style="font-weight: bold;"><?php echo $quotation['quote_no']; ?></span>
                                                </span>                                       
                                            </span>
                                            
                                        </td>
                                        <td style="width: 33%;text-align: center;border: 1px solid#99c1e5;">
                                            <font face="Arial, Helvetica, sans-serif;" color="#585858"
                                            style="font-size: 24px; line-height: 32px;">
                                            <span
                                                style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;font-weight: 400;">
                                                Valid till: <br><span style="font-weight: 300;">
                                                    <span style="font-weight: bold;"><?php echo date_db_format_to_display_format($quotation['quote_valid_until']); ?></span>
                                                </span>                                      
                                            </span>
                                            
                                        </td>
        
        
                                    </tr>
                                </tbody>
                            </table>
                            <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>

                            <font face="Arial, Helvetica, sans-serif;" color="#090909"
                                style="font-size: 24px; line-height: 32px;">
                                <?php
                                $tmp_str=base64_encode(rand().'#'.$quotation['opportunity_id'].'#'.$quotation['id'].'#'.rand().'#12');
                                ?>
                                <span
                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;">You had enquired us for <b><?php echo $product_list[0]->product_name; ?> <?php if(count($product_list)>1){ ?>& <?php echo (count($product_list)-1) ?> other <?php } ?></b> product(s). The quote is attached with the mail. You can also download the Quotation by <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/download/quotation/'.$tmp_str);?>" target="_blank">clicking here.</a> </span>
                            </font>

                            <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>
                                        
                            <font face="Arial, Helvetica, sans-serif;" color="#585858"
                                style="font-size: 24px; line-height: 32px;">
                                <span
                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;">We trust our offer is in line with your requirement and expectations, should you need any further Clarification, please feel free to contact the undersigned. </span>
                            </font>

                            <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>
                                        
                            <font face="Arial, Helvetica, sans-serif;" color="#585858"
                                style="font-size: 24px; line-height: 32px;">
                                <span
                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;">Thanking you and assuring of our best attention at all times and we look forward to receive your valued purchase order and a healthy business partnership. </span>
                            </font>
                            <div style="height: 15px; line-height: 75px; font-size: 73px;">&nbsp;</div>
                        </td>
                    </tr>
                </table>

                <div style="height: 3px;background:#99c1e5; width: 90%;margin:40px auto 10px auto;  "></div>

                <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                    <tr>
                        <td align="left" valign="top">
                            <div style="display: inline-block; vertical-align: top; width: 80px;">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                    style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                    <tr>
                                        <td align="center" valign="top">
                                            <div style="height: 15px; line-height: 24px; font-size: 24px;">
                                                &nbsp;</div>
                                            <div style="display: block; max-width: 80px;">
                                                <?php
                                                if($logged_in_user_data['photo'])
                                                {
                                                    $photo_url=assets_url().'uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/admin/thumb/'.$this->session->userdata['admin_session_data']['personal_photo'];
                                                }
                                                else
                                                {
                                                    $photo_url=assets_url().'images/customer-icon.png';
                                                }
                                                ?>
                                                <img src="<?php echo $photo_url; ?>" alt="img" width="110" border="0"
                                                    style="display: block; width: 110px;" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="mob_div"
                                style="display: inline-block; vertical-align: top; width: 62%; min-width: 330px;">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                    style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                    <tr>
                                        <td class="mob_center" align="left" valign="top">
                                            <div style="height: 13px; line-height: 13px; font-size: 11px;">
                                                &nbsp;</div>
                                                <span
                                                    style="font-family: Calibri, Candara, Segoe, sans-serif; color: #090909; font-size: 14px; line-height: 13px;font-weight: bold;">With Best Regards </span><br><br><br><br>
                                            <font face="Arial, Helvetica, sans-serif;" color="#000000"
                                                style="font-size: 20px; line-height: 23px; ">
                                                    <span
                                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;"><?php echo $logged_in_user_data['name']; ?></span><br>
                                            </font>
                                            <div style="height: 1px; line-height: 1px; font-size: 1px;">
                                                &nbsp;</div>
                                            <font face="Arial, Helvetica, sans-serif;" color="#090909;"
                                                style="font-size: 13px; line-height: 20px;">                                                
                                                    <span
                                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 23px; font-weight: 400;"><?php echo $logged_in_user_data['mobile']; ?></span>
                                                    <br>
                                                    <span
                                                    style="font-family: Arial, Helvetica, sans-serif; color: #090909; font-size: 13px; line-height: 20px;">Email: <?php echo $logged_in_user_data['email']; ?></span> <br>
                                            </font>
                                        </td>
                                        <td width="18"
                                            style="width: 18px; max-width: 18px; min-width: 18px;">&nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="display: inline-block; vertical-align: top; width: 177px;">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                    style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                    <tr>
                                        <td align="center" valign="top">
                                            <div style="height: 13px; line-height: 13px; font-size: 11px;">
                                                &nbsp;</div>
                                            <div style="display: block; max-width: 177px;">
                                                <img src="<?php echo assets_url(); ?>images/txt.png" alt="img" width="110" border="0"
                                                    style="display: none; width: 110px; max-width: 100%;" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="height: 25px; line-height: 10px; font-size: 13px;">&nbsp;</div>
                <table cellpadding="0" cellspacing="0" border="0" width="90%" style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #fff; border-bottom: none; border-left: none; border-right: none;">
                    <tr>
                         <td style="width: 100%; padding: 15px 0 5px;text-align: center;" align="left" valign="top">
                            <font face="Calibri, Candara, Segoe, sans-serif;" color="#999"
                            style="font-size: 24px; line-height: 32px;">
                                <span style="font-family: Calibri, Candara, Segoe, sans-serif; color: #999; font-size: 16px; line-height: 20px; text-align: center;"><?php echo $company['name'];?><br>
                                    <span style="font-size: 12px;line-height: 16px;text-align: center;"><?php rander_company_address('email_template'); ?></span>

                                </span>
                            </font>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" width="90%"
                style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #fff; border-bottom: none; border-left: none; border-right: none;">
                <tr>
                     <td style="width: 100%; padding: 15px 0 5px;text-align: right;" align="right" valign="top">
                     <?php /* ?><p style="margin: 4px 0; line-height: 14px; font-size: 9px; color: #888; ">Powered By: <a href="https://lmsbaba.com/" target="_blank" style="color: #888;">LMSbaba.com</a></p>
						
                        <div style="float: right;text-align: center;">
                            <span style="font-size: 12px;color: #999;font-family: Calibri, Candara, Segoe, sans-serif;">Powered by</span>
							<img src="<?php echo assets_url().'images/logo.png'; ?>" alt="img" width="110" border="0" style="display: block; width: 110px; text-align: right;" />      
						</div>  
						<?php */ ?>
                    </td>
                </tr>
            </table>
        </td>
        <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;
        </td>
    </tr>
</table>
</td>
</tr>
</table>
<div style="height: 34px; line-height: 34px; font-size: 32px; background: #f3f3f3;">&nbsp;</div>
</body>
</html>