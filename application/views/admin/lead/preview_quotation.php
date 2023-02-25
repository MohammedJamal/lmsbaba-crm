<?php 
   $client_id=isset($this->session->userdata['admin_session_data']['client_id'])?$this->session->userdata['admin_session_data']['client_id']:$client_info->client_id;
   if(strtolower(str_replace(' ', '', $company['state']))==strtolower(str_replace(' ', '', $customer['state'])))
   {
     $is_same_state='Y';
   }
   else
   {
     $is_same_state='N';
   }
   
   if($company['state']=='' || $customer['state']=='')
   {
     $is_state_missing="Y";
   }
   else
   {
     $is_state_missing="N";
   }
   
   
   if(strtoupper($quotation['currency_type'])=='INR'){
     $is_tax_show='Y';
   }
   else{
     $is_tax_show='N';
   }
   ?>
<!DOCTYPE html>
<html lang="">
   <head>
      <meta charset="utf-8">
      <title>LMS for Quotation | Powered by LMSBABA.COM</title>
      <link rel="icon" href="<?=assets_url();?>images/favicon_8.ico" type="image/ico" sizes="18x18">
      <link rel="stylesheet" href="<?php echo assets_url();?>css/century_font.css">
      <style type="text/css" media="screen">
         *:not(.btn){
         font-family: 'Century Gothic' !important;
         line-height: normal !important;
         font-size: 14px !important;
         }
         *:not(.btn) span{
         font-size: 12px !important;
         }
         b{
         font-weight: 900;
         }
         .row-picture {
         width: 100%;
         display: inline-block;
         margin-top: 10px;
         }
         .row-picture > ul {
         margin: 0;
         list-style: none;
         padding: 0;
         margin-right: -5px;
         margin-left: -5px;
         }
         .row-picture > ul > li {
         position: relative;
         float: left;
         width: 60px;
         min-height: 1px;
         padding-left: 5px;
         padding-right: 5px;
         }
         .img-fluid {
         width: 100%;
         height: auto;
         display: block;
         }
         .max-w-200{
         max-width: 200px;
         }
         .quotation-title{
         display: none;
         }
         .new-row-tr .quotation-title br{
         display: block !important;
         }
         .btn {
         display: inline-block;
         padding: 6px 12px;
         margin-bottom: 0;
         font-size: 12px !important;
         font-weight: 400;
         line-height: 1.42857143;
         text-align: center;
         white-space: nowrap;
         vertical-align: middle;
         -ms-touch-action: manipulation;
         touch-action: manipulation;
         cursor: pointer;
         -webkit-user-select: none;
         -moz-user-select: none;
         -ms-user-select: none;
         user-select: none;
         background-image: none;
         border: 1px solid transparent;
         border-radius: 4px;
         box-shadow: #ccc 0 0 6px;
         }
         .btn-primary {
         background-color: #fff;
         border-color: #fafafa;
         border: 1px solid #e0e0e0;
         border-radius: 5px;
         color: #008ac9;
         }
         .btn-primary svg{
         width: 16px;
         height: 16px;
         }
         .btn-primary svg path{
         fill: #008ac9;
         }
         .btn-primary:hover{
         background-color: #008ac9;
         color: #FFF;
         }
         .btn-primary:hover svg path{
         fill: #FFF;
         }
         .main-invoice-bg{
         box-shadow: 0 0 6px #ccc;
         background-color: #fff;
         }
         .main-tab{
         width: 800px;margin:12px auto;clear:both;padding:0 10px
         }
         .main-invoice-bg-neww{
         width:800px; height:auto; display: block; margin: 20px auto; background:#FFF;
         }
      </style>
      <style type="text/css" media="print">
         *{
         font-family: century-gothic; 
         font-size: 14px !important;
         }
      </style>
   </head>
   <body style="margin: 0px; background:#f8f9fe;" id="contentToPrint">
      <?php if($download_url){ ?>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript">
         function printpage(){
            /*
            var printContent = document.getElementById('contentToPrint').innerHTML;
            var printDiv = document.getElementById('divToPrint').innerHTML;
            var printHidden = document.getElementById('hiddenDivToPrint').innerHTML;
            var allContent = document.body.innerHTML;
            document.body.innerHTML = printContent + printDiv + printHidden;
            window.print();
            document.body.innerHTML = allContent;*/
            var assets_url="<?php echo assets_url();?>";
            var contents = $("#divToPrint").html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({ "position": "absolute", "top": "-1000000px" });
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title>Quotation</title>');
            frameDoc.document.write('</head><body>');
            //Append the external CSS file.
            frameDoc.document.write('<link href="'+assets_url+'css/preview_quotation.css" rel="stylesheet" type="text/css" />');
            frameDoc.document.write('<link href="'+assets_url+'css/century_font.css" rel="stylesheet" type="text/css" />');
            //Append the DIV contents.
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
               window.frames["frame1"].focus();
               window.frames["frame1"].print();
               frame1.remove();
            }, 500);
            
            
            //window.print();
         
         }
      </script>
      <div class="main-tab" id="hiddenDivToPrint">
         <div class="row">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 col-xl-8">
               <a class="btn btn-primary f-13 btn-action" onclick="printpage()" href="JavaScript:void(0)" style="text-decoration: none;">
                  <span>
                     <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                        <path id="Icon_awesome-print" data-name="Icon awesome-print" d="M31.5,13.5V5.432a2.251,2.251,0,0,0-.659-1.591L27.659.659A2.25,2.25,0,0,0,26.068,0H6.75A2.25,2.25,0,0,0,4.5,2.25V13.5A4.5,4.5,0,0,0,0,18v7.875A1.125,1.125,0,0,0,1.125,27H4.5v6.75A2.25,2.25,0,0,0,6.75,36h22.5a2.25,2.25,0,0,0,2.25-2.25V27h3.375A1.125,1.125,0,0,0,36,25.875V18A4.5,4.5,0,0,0,31.5,13.5ZM27,31.5H9V24.75H27Zm0-15.75H9V4.5H22.5V7.875A1.125,1.125,0,0,0,23.625,9H27Zm3.375,5.063a1.688,1.688,0,1,1,1.688-1.687A1.688,1.688,0,0,1,30.375,20.813Z"/>
                     </svg>
                  </span>
                  <br> 
                  <span>Print</span> 
               </a>
               <a class="btn btn-primary f-13 btn-action"  href="<?php echo $download_url; ?>" target="_blank" style="text-decoration: none">
                  <span>
                     <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                        <path id="Icon_awesome-download" data-name="Icon awesome-download" d="M15.188,0h5.625A1.683,1.683,0,0,1,22.5,1.688V13.5h6.166a1.4,1.4,0,0,1,.991,2.4L18.963,26.6a1.362,1.362,0,0,1-1.92,0L6.335,15.9a1.4,1.4,0,0,1,.991-2.4H13.5V1.688A1.683,1.683,0,0,1,15.188,0ZM36,26.438v7.875A1.683,1.683,0,0,1,34.313,36H1.688A1.683,1.683,0,0,1,0,34.313V26.438A1.683,1.683,0,0,1,1.688,24.75H12L15.448,28.2a3.6,3.6,0,0,0,5.1,0L24,24.75H34.313A1.683,1.683,0,0,1,36,26.438Zm-8.719,6.188a1.406,1.406,0,1,0-1.406,1.406A1.41,1.41,0,0,0,27.281,32.625Zm4.5,0a1.406,1.406,0,1,0-1.406,1.406A1.41,1.41,0,0,0,31.781,32.625Z"/>
                     </svg>
                  </span>
                  <br> 
                  <span>Download PDF</span> 
               </a>
               <?php /* ?>
               <button class="btn btn-primary f-13 btn-action" ng-click="share()">
                  <span>
                     <svg xmlns="http://www.w3.org/2000/svg" width="31.5" height="36" viewBox="0 0 31.5 36">
                        <path id="Icon_awesome-share-alt" data-name="Icon awesome-share-alt" d="M24.75,22.5a6.721,6.721,0,0,0-4.2,1.469l-7.206-4.5a6.789,6.789,0,0,0,0-2.931l7.206-4.5A6.738,6.738,0,1,0,18.16,8.215l-7.206,4.5a6.75,6.75,0,1,0,0,10.562l7.206,4.5A6.751,6.751,0,1,0,24.75,22.5Z"/>
                     </svg>
                  </span>
                  <br> 
                  <span>Share</span> 
               </button>
               <?php */ ?>
            </div>
            <!-- ngIf: model.showPayOnline -->
         </div>
      </div>
      <?php } ?>
      <div class="main-invoice-bg-neww" id="divToPrint">
         <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 6px 0;">
            <table style="width:100%">
               <tbody>
                  <tr>
                     <td width="160px" style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle;">
                        <?php
                           $logo=$company['logo'];
                           if($logo!='')
                           {
                             $profile_img_path = assets_url()."uploads/clients/".$client_id."/company/logo/thumb/".$logo;
                           }
                           else
                           {
                             $profile_img_path = assets_url().'images/user_img_icon.png';
                           }
                           
                           ?>
                        <img src="<?php echo $profile_img_path; ?>" style="width: 100px;height: auto;">
                     </td>
                     <td style="box-sizing: border-box; padding: 10px;vertical-align: middle;">
                        <h2 style="font-size:16px !important; color:#031319;margin: 0px;font-family: century-gothic;"><?php echo $company['name'];?></h2>
                        <span style="font-size:10px; color:#031319;margin: 0px;font-family: century-gothic;"><?php rander_company_address('preview_quotation',$client_info); ?></span>
                     </td>
                     <td style="box-sizing: border-box; padding: 10px; border-left: #969696 1px solid;vertical-align: middle;">
                        <span style="font-size:10px; color:#031319;margin: 0px;font-family: century-gothic; white-space: nowrap;">
                        Email: <?php echo $company['email1'];?><br>             
                        Mobile: <?php echo $company['mobile1'];?><br>              Website: <?php echo $company['website'];?>  
                        <?php 
                           echo ($quotation['is_gst_number_show_in_quotation']=='Y')?'<br>GST: '.$company['gst_number']:''; ?>          
                        </span>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div class="head-invoice" style="width:100%; height:auto; display: inline-block; padding: 10px; box-sizing: border-box; text-align:center; font-size:16px; font-weight:500;background: #000; color: #FFF;font-family: century-gothic;">
            <b><?php echo ($quotation['quote_title'])?$quotation['quote_title']:'Quotation'; ?></b>
         </div>
         <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin:0;">
            <table colspan="0" rowspan="0" style="width:100%;border-bottom: #878787 3px solid;">
               <tbody>
                  <tr>
                     <td width="60%" style="box-sizing: border-box; padding: 10px;vertical-align: middle;">
                        <table width="100%" style="width:100%; border-collapse: collapse;">
                           <tbody>
                              <tr>
                                 <td style="width: 100%; height: auto; display: inline-block;padding: 15px 0;">
                                    <!--<h2 style="font-size:12px; color:#000000;margin: 0 0 5px 0; font-weight: 700;font-family: century-gothic; display: inline-block;"><strong>TO:</strong></h2>-->
                                    <div style="font-size:12px; color:#000;margin: 0;  width: 100%; display:inline-block;font-family: century-gothic;"> 
                                       <?php echo $quotation['letter_to']; ?>               
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                     <td width="40%" style="box-sizing: border-box; padding: 10px 0; border-left: #878787 3px solid;vertical-align: middle;">
                        <table width="100%" style="width:100%; border-collapse: collapse;">
                           <tbody>
                              <tr>
                                 <td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px; border-bottom: #000000 1px solid;box-sizing: border-box;">
                                    <div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($quotation['quote_title'])?$quotation['quote_title']:'Quotation'; ?> No:  <?php echo $quotation['quote_no']; ?></b></div>
                                 </td>
                              </tr>
                              <tr>
                                 <td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px; border-bottom: #000000 1px solid;box-sizing: border-box;">
                                    <div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($quotation['quote_title'])?$quotation['quote_title']:'Quotation'; ?> Date: <?php echo date_db_format_to_display_format($quotation['quote_date']); ?></b></div>
                                 </td>
                              </tr>
                              <tr>
                                 <td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px; border-bottom: #000000 1px solid;box-sizing: border-box;">
                                    <div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;">
                                       <b>Valid Till: <?php echo date_db_format_to_display_format($quotation['quote_valid_until']); ?></b>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px; box-sizing: border-box;">
                                    <div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Enquiry Ref. ID: <?php echo get_company_name_initials($client_info); ?> - <?php echo $quotation_data['lead_opportunity_data']['lead_id']; ?></b></div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div style="width:100%; height:auto; display: inline-block; margin: 20px 0; font-weight: 400; font-size:12px; color:#242424">
            <!--<b style="font-size:12px; color:#000000;font-family: century-gothic;">Subject:</b>-->
            <span style="font-size:12px; color:#000000;font-family: century-gothic;box-sizing: border-box;padding: 0 10px"><?php echo $quotation['letter_subject']; ?></span>
         </div>
         <div style="width:100%; height:auto; display: inline-block; margin: 20px 0; font-weight: 400; font-size:12px; color:#242424;font-family: century-gothic;box-sizing: border-box;padding: 0 10px">
            <!-- <b style="font-size:12px; color:#000000;width:100%; height:auto; display: inline-block;">Dear Sir/Ma’am,</b>  -->
            <?php echo $quotation['letter_body_text']; ?>
         </div>
         <?php
            // IS DISCOUNT VAILABLE CHECKING : START
            $is_discount_available='N';
            $discount_wise_colspan="colspan='2'";
            $discount_wise_colspan_cnt=0;
            if(count($prod_list))
            {
              foreach($prod_list as $p)
              {
                if($p->discount>0)
                {
                  $is_discount_available='Y';
                  $discount_wise_colspan='';
                  $discount_wise_colspan_cnt=1;
                  break;
                }
              }
            }         
            if($is_discount_available=='N')
            {
              if(count($selected_additional_charges))
              {
                foreach($selected_additional_charges AS $c)
                {
                  if($c->discount>0)
                  {
                    $is_discount_available='Y';
                    $discount_wise_colspan="";
                    $discount_wise_colspan_cnt=1;
                    break;
                  }
                }
              }
            } 
            // IS DISCOUNT VAILABLE CHECKING : END
            ?>
         <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 20px 0; font-weight: 400; box-sizing: border-box; padding:0 10px;">
            <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
               <thead>
                  <tr>
                     <th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
                        <span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;"><b>Sl.</b></span>
                     </th>
                     <th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:left;" width="55%">
                        <strong style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic; text-align: left;"><b>Product Details</b></strong>
                     </th>
                     <th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid; font-weight:400">
                        <div style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;"><b>Unit Price</b></div>
                        <span style="font-size:10px; color:#000;margin: 0; font-weight:100; width: 100%;font-family: century-gothic; white-space: nowrap;">(<?php echo $quotation['currency_type']; ?>)</span>
                     </th>
                     <th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
                        <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;">Qty</strong>
                     </th>
                     <?php
                        if($is_discount_available=='Y')
                        {
                        ?>
                     <th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;font-weight: 400;">
                        <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block; white-space: nowrap;font-family: century-gothic;"><b>Discount</b></strong><br>
                        <span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;">(in <?php echo ($prod_list[0]->is_discount_p_or_a=='P')?'%':'Amt.'; ?>)</span>
                     </th>
                     <?php
                        }
                        ?>
                     <?php 
                        if($quotation['is_hide_gst_in_quotation']!='Y' && $quotation['is_consolidated_gst_in_quotation']!='Y')
                        {
                        ?>
                     <th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;font-weight: 400;">
                        <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block; white-space: nowrap;font-family: century-gothic;"><b>GST</b></strong><br><span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic; white-space: nowrap;">(in %)</span>
                     </th>
                     <?php
                        }
                        ?>
                     <th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-bottom:#888888 1px solid; font-weight:400">
                        <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;"><b>Amount</b></strong><br>
                        <span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;">(<?php echo $quotation['currency_type']; ?>)</span>
                     </th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                     $gst_breakup=array();
                     $taxable_amount=array();
                     
                     if(count($prod_list))
                     {         
                       $i=0;
                       $sub_total=0;
                       $s=1;
                       $discount=0;
                       $item_gst_per=0;
                       $item_sgst_per=0;
                       $item_cgst_per=0;
                       $total_price=0;
                       $total_discounted_price=0;
                       $total_tax_price=0;
                       foreach($prod_list as $output)
                       {
                         $item_gst_per= $output->gst;
                         $item_sgst_per= ($item_gst_per/2);
                         $item_cgst_per= ($item_gst_per/2); 
                         $item_is_discount_p_or_a=$output->is_discount_p_or_a; 
                         $item_discount=$output->discount; 
                         $item_unit=$output->unit;
                         $item_price=($output->price/$item_unit);
                         $item_qty=$output->quantity;
                     
                         $item_total_amount=($item_price*$item_qty);
                         if($item_is_discount_p_or_a=='A'){
                          $row_discount_amount=$item_discount;
                         }
                         else{
                          $row_discount_amount=$item_total_amount*($item_discount/100);
                         }
                     
                     
                         $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
                         
                         $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                         $row_final_amount=($item_total_amount-$row_discount_amount);
                         $sub_total=$sub_total+$row_final_price;
                     
                         $total_price=$total_price+$item_total_amount;
                         $total_discounted_price=$total_discounted_price+$row_discount_amount;
                         $total_tax_price=$total_tax_price+$row_gst_amount;
                     
                         if($output->image!='' && $img_flag==0)
                         {
                          $is_product_image_available='Y';
                          $img_flag=1;
                         }
                         if($output->brochure!='' && $brochure_flag==0)
                         {
                          $is_product_brochure_available='Y';
                          $brochure_flag=1;
                         }

                        $gst_breakup[]=array('type'=>'P','percentage'=>$item_gst_per,'amount'=>$row_gst_amount);
                        if($row_gst_amount>0){
                           $taxable_amount[]=array('type'=>'P','amount'=>$row_final_amount);
                        }                       
                        
                     ?>
                  <tr>
                     <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid; vertical-align:top;">
                        <strong style="font-size:12px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $s; ?></b></strong>
                     </td>
                     <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-alignl:left;">
                        <div style="font-size:12px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic; max-width: 400px;">
                           <?php echo $output->product_name; ?>
                        </div>
                        <?php 
                           if($quotation['is_product_image_show_in_quotation']=='Y')
                           {
                           ?>
                        <?php
                           $img_arr=array();
                           if($output->image_for_show_files){
                             $img_arr=explode(',',$output->image_for_show_files);
                           }
                           if(count($img_arr))
                           {
                           ?>
                        <div class="row-picture show">
                           <br>
                           <div style="font-size:12px !important; font-weight:700;color: #000; width: 100%; display:inline-block;font-family: century-gothic; width: 100%; display:inline-block;margin: 0;">
                              Product Image
                           </div>
                           <div style="width:100%;height: 10px; display:block;"></div>
                           <br>
                           <table border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                 <tr>
                                    <?php                    
                                       foreach($img_arr AS $img_tmp)
                                       {
                                       ?>
                                    <td width="70" style="padding-right:10px; width:70px" valign="top">
                                       <div class="quotation-phots" style="width:70px;display: inline-block; box-sizing:border-box; float:left;">
                                          <img src="<?php echo assets_url().'uploads/clients/'.$client_id.'/product/thumb/'.$img_tmp; ?>" class="img-fluid" style="width:70px;height: 70px; display:block; object-fit: contain;">
                                       </div>
                                    </td>
                                    <?php
                                       }
                                       ?>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
         </div>
         <?php
            }
            }
            ?>
         <?php 
            if($quotation['is_product_youtube_url_show_in_quotation']=='Y')
            {
              if($output->youtube_video!="" && $output->is_youtube_video_url_show=='Y')
              {
            ?>
         <div class="row-picture max-w-200" style="font-size:12px; color:#000; width: 100%; display:inline-block;">
         <br>
         <div style="font-size:12px !important; font-weight:700;color: #000; margin: 0 0 3px 0; width: 100%; display:inline-block;font-family: century-gothic;">Product Video URL</div>
         <a href="<?php echo $output->youtube_video; ?>" target="_blank" style="font-family: century-gothic; font-size: 12px"><?php echo $output->youtube_video; ?></a>
         </div>
         <?php
            }
            }
            ?>
         </td>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:center">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $output->price;?></b></strong><br>
         <span style="font-size:12px; color:#000;margin: 0; width: 100%;font-family: century-gothic;">Per <?php echo $output->unit;?> <?php echo $output->unit_type; ?></span>
         </td>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $output->quantity; ?> </b></strong>
         <br><?php echo $output->unit_type; ?>
         </td>
         <?php
            if($is_discount_available=='Y')
            {
            ?>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($output->discount>0)?$output->discount:'-'; ?><?php echo ($output->is_discount_p_or_a=='P' && $output->discount>0)?'%':''; ?></b></strong>
         </td>
         <?php
            }
            ?>
         <?php 
            if($quotation['is_hide_gst_in_quotation']!='Y' && $quotation['is_consolidated_gst_in_quotation']!='Y')
            {
            ?>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($output->gst>0)?$output->gst.'%':'NIL';?></b></strong>
         </td>
         <?php
            }
            ?>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($row_final_amount,2);?></b></strong>
         </td>
         </tr>
         <?php
            $s++;
            $i++;
            }
            }
            ?>
         <?php 
            if(count($selected_additional_charges))
            {
            
              $j=0;
              foreach($selected_additional_charges AS $charge)
              {
                
                $i++; 
                $item_gst_per= $charge->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2);
                $item_is_discount_p_or_a=$charge->is_discount_p_or_a;  
                $item_discount_per=$charge->discount; 
                $item_price= $charge->price;
                $item_qty=1;
            
                $item_total_amount=($item_price*$item_qty);
                if($item_is_discount_p_or_a=='A'){
                $row_discount_amount=$item_discount_per;
                }
                else{
                $row_discount_amount=$item_total_amount*($item_discount_per/100);
                }
                // $row_discount_amount=$item_total_amount*($item_discount_per/100);
                $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
            
                $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                $row_final_amount=($item_total_amount-$row_discount_amount);
                $sub_total=$sub_total+$row_final_price; 
            
                $total_price=$total_price+$item_total_amount;
                $total_tax_price=$total_tax_price+$row_gst_amount;
                $total_discounted_price=$total_discounted_price+$row_discount_amount;
                $gst_breakup[]=array('type'=>'S','percentage'=>$item_gst_per,'amount'=>$row_gst_amount);
                if($row_gst_amount>0){
                  $taxable_amount[]=array('type'=>'P','amount'=>$row_final_amount);
               }
               
              ?>
         <tr>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
         <strong style="font-size:12px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $s; ?></b></strong>
         </td>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-alignl:left;">
         <div style="font-size:12px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic; max-width: 200px;"><?php echo $charge->additional_charge_name; ?></div>
         </td>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:center">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $charge->price; ?></b></strong><br>
         </td>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>-</b></strong>
         </td>
         <?php
            if($is_discount_available=='Y')
            {
            ?>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($charge->discount>0)?$charge->discount:'-'; ?> <?php echo ($charge->is_discount_p_or_a=='P' && $charge->discount>0)?'%':''; ?></b></strong>
         </td>
         <?php
            }
            ?>
         <?php 
            if($quotation['is_hide_gst_in_quotation']!='Y' && $quotation['is_consolidated_gst_in_quotation']!='Y')
            {
            ?>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($charge->gst>0)?$charge->gst.'%':'NIL'; ?></b></strong>
         </td>
         <?php
            }
            ?>
         <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
         <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($row_final_amount,2); ?></b></strong>
         </td>
         </tr>
         <?php
            $s++;
            $j++; 
            } 
            } 
            ?> 
         </tbody>
         </table>
      </div>
      <?php
         if($quotation['is_hide_total_net_amount_in_quotation']!='Y')
         {
         ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;box-sizing: border-box; padding:0 10px;">
         <div class="head-left" style="width:70%; height:auto; display: inline-block; margin: 0; float:right;">
            <table class="grand-summery table-borderless net-ammount" width="100%" border="0" cellpadding="0" cellspacing="0" style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
               <tbody>
                  <tr>
                     <td width="50%" style="text-align: right; border-bottom:#888888 1px solid; box-sizing: border-box; padding: 5px;" colspan="2">
                        <div style="font-size:12px !important; color:#000;font-weight:700;font-family: century-gothic; text-align: right; box-sizing: border-box;"><b>Details (<?php echo $quotation['currency_type']; ?>)</b></div>
                     </td>
                  </tr>
                  <tr>
                     <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid; box-sizing: border-box;">Total Gross Amount</td>
                     <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid; border-left:#888888 1px solid; box-sizing: border-box;"><span id="total_price"><?php echo number_format($total_price,2); ?></span></td>
                  </tr>
                  <?php if($is_discount_available=='Y')
                  { 
                  ?> 
                     <tr>
                        <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;">Total Discount</td>
                        <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><span id="total_discount"><?php echo number_format($total_discounted_price,2); ?></span></td>
                     </tr>
                  <?php 
                  } 
                  ?>
                  <?php 
                  $total_taxable_amount=0;
                  if(count($taxable_amount)){
                     foreach($taxable_amount AS $tax_a){
                        $total_taxable_amount=$total_taxable_amount+$tax_a['amount'];
                     } 
                  }   
                                 
                  if($total_taxable_amount>0 && $total_taxable_amount<$total_price)
                  { 
                  ?> 
                     <tr>
                        <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;">Taxable Amount</td>
                        <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><span id="total_discount"><?php echo number_format($total_taxable_amount,2); ?></span></td>
                     </tr>
                  <?php 
                  } 
                  ?>
                  <?php    
                  if($quotation['is_hide_gst_in_quotation']!='Y')
                  {          
                     if($quotation['is_consolidated_gst_in_quotation']=='N')
                     {  
                        if($is_same_state=='Y')
                        { 
                           $sgst=($total_tax_price/2);
                           $cgst=($total_tax_price/2);
                        ?>
                           <tr>
                              <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;">SGST</td>
                              <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><?php echo ($sgst>0)?number_format($sgst,2):'NIL'; ?></td>
                           </tr>
                           <tr>
                              <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;">CGST</td>
                              <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><?php echo ($cgst>0)?number_format($cgst,2):'NIL'; ?></td>
                           </tr>
                        <?php
                        }
                        else
                        {
                        ?>
                           <tr>
                              <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;"><?php echo ($is_state_missing=='Y')?'GST':'IGST'; ?></td>
                              <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><span id="total_tax"><?php echo ($total_tax_price>0)?number_format($total_tax_price,2):'NIL'; ?></span></td>
                           </tr>
                        <?php
                        }
                     }
                     else if($quotation['is_consolidated_gst_in_quotation']=='Y')
                     {
                        
                        // print_r($gst_breakup);
                        $gst_breakup_new=$gst_breakup;
                        $gst_breakup_final=array();
                        if(count($gst_breakup))
                        {
                           $i=0;
                           foreach($gst_breakup AS $sgt_b)
                           {  
                              $tmp_am=0;
                              foreach($gst_breakup_new AS $sgt_b_new)
                              {                                    
                                 if($sgt_b['percentage']==$sgt_b_new['percentage']){
                                    $tmp_am=$tmp_am+$sgt_b_new['amount'];                                       
                                 }                                    
                              }
                              if($sgt_b['percentage']>0){
                                 $gst_breakup_final[$sgt_b['percentage']]=$tmp_am;
                              }                                 
                           }
                        }
                        
                        if($is_same_state=='Y')
                        {                             
                                 
                           if(count($gst_breakup_final))
                           {
                              foreach($gst_breakup_final AS $gst_k=>$gst_v)
                              {  
                                 $sgst_per=($gst_k/2);
                                 $cgst_per=($gst_k/2);

                                 $sgst=($gst_v/2);
                                 $cgst=($gst_v/2);
                                 ?>
                                 <tr>
                                    <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;">SGST <?php echo ($sgst_per>0)?'('.$sgst_per.'%)':''; ?></td>
                                    <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><?php echo ($sgst>0)?number_format($sgst,2):'NIL'; ?></td>
                                 </tr>
                                 <tr>
                                    <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;">CGST <?php echo ($cgst_per>0)?'('.$cgst_per.'%)':''; ?></td>
                                    <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><?php echo ($cgst>0)?number_format($cgst,2):'NIL'; ?></td>
                                 </tr>
                                 <?php
                              }
                           }
                        }
                        else
                        {  
                           if(count($gst_breakup_final))
                           {
                              foreach($gst_breakup_final AS $gst_k=>$gst_v)
                              {                                     
                                 ?>
                                 <tr>
                                    <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;"><?php echo ($is_state_missing=='Y')?'GST':'IGST'; ?> <?php echo ($gst_k>0)?'('.$gst_k.'%)':''; ?></td>
                                    <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><span id="total_tax"><?php echo ($gst_v>0)?number_format($gst_v,2):'NIL'; ?> <?php //echo ($total_tax_price>0)?number_format($total_tax_price,2):'NIL'; ?></span></td>
                                 </tr>
                                 <?php                                   
                              }
                           }
                        }
                     }
                     else
                     {

                     }
                  }
                  ?>
                     <tr>
                        <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding: 5px;border-bottom:#888888 1px solid;">Net Amount (Round Off)</td>
                        <td style="font-size:12px !important; color:#000;font-weight:400;font-family: century-gothic; text-align: right;padding:5px;border-bottom:#888888 1px solid;border-left:#888888 1px solid; box-sizing: border-box;"><span id="grand_total_round_off"><?php echo number_format(round($sub_total),2); ?></span></td>
                     </tr>
                  <tr>
                     <td colspan="2" style="font-size:12px !important; color:#000;font-weight:700;font-family: century-gothic; text-align: right; padding: 5px;"><b><?php echo number_to_word(round($sub_total)); ?></b> (<b><?php echo $quotation['currency_type']; ?></b>)</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <?php
         }
         ?>
      <?php
         if($quotation['is_show_gst_extra_in_quotation']=='Y')
         {
         ?>
      <div style="text-align:right;padding-bottom: 0px;">** GST Extra</div>
      <div>&nbsp;</div>
      <?php
         }
         ?>
      <?php 
         $q_photos_arr=array();
         $q_photos=$quotation['q_photos']; 
         if($q_photos)
         {
           $q_photos_arr=explode(",", $q_photos);
         }
         ?>
      <?php if(count($q_photos_arr)){ ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0; box-sizing: border-box; padding:0 10px;">
         <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
            <thead>
               <tr>
                  <th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left; border-top: #888888 1px solid; border-right:#888888 1px solid; border-left:#888888 1px solid;">
                     <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic; text-transform: uppercase;">Photos</strong>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF; height:auto; border-bottom: #888888 1px solid; border-right:#888888 1px solid; border-left:#888888 1px solid;">
                     <?php 
                        $t_count = 1;
                        ?>
                     <table border="0" cellpadding="0" cellspacing="0" style="width:100%">
                        <tbody>
                           <tr>
                              <?php 
                                 foreach($q_photos_arr AS $img_tmp){
                                   $img_tmp_arr=explode("#", $img_tmp);
                                   $img_t=$img_tmp_arr[0];
                                   $img=$img_tmp_arr[1];
                                 ?>
                              <td width="33.333%" valign="top" style="padding-right:10px; text-align: center; width:160px" valign="top;">
                                 <div class="quotation-title" style="width:100%; height:auto; display: inline-block; margin-bottom: 5px; font-weight: 400; font-size:12px; color:#242424; text-align: center;font-family: century-gothic;">
                                    <br>
                                    <?php echo $img_t; ?>
                                 </div>
                                 <div class="quotation-phots" style="width:160px;display: inline-block; box-sizing:border-box; border:#DDD 1px solid; padding:10px">
                                    <img src="<?php echo assets_url()."uploads/clients/".$client_id."/quotation/".$img; ?>" class="img-fluid" style="width:138px;height: 105px; display:block; object-fit:contain;">
                                 </div>
                              </td>
                              <?php
                                 if($t_count == 4){
                                 ?>
                           </tr>
                           <tr class="new-row-tr">
                              <?php
                                 $t_count = 0;
                                 }
                                 ?>
                              <?php
                                 $t_count++;
                                 }
                                 ?>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <?php } ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;box-sizing: border-box; padding:0 10px;">
         <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
            <thead>
               <tr>
                  <th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left">
                     <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic;">TERMS & CONDITIONS</strong>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
                     <span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
                        <?php if(count($terms)){ ?>
                        <?php foreach($terms as $term){ ?>
                        <?php if($term['is_display_in_quotation']=='Y'){ ?>
                        <p><b><?php echo ($term['name']); ?>: </b><?php echo ($term['value']); ?></p>
                        <?php } ?>
                        <?php } ?>
                        <?php }else{echo'Not Applicable';} ?>
                     </span>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <?php if($quotation['letter_terms_and_conditions']){ ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;box-sizing: border-box; padding:0 10px;">
         <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
            <thead>
               <tr>
                  <th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left">
                     <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic; text-transform: uppercase;">Additional Notes</strong>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
                     <span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;"><?php echo $quotation['letter_terms_and_conditions']; ?></span>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <?php } ?>
      <?php 
         /*if($quotation['is_gst_number_show_in_quotation']=='Y'){ ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;">
         <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
            <thead>
               <tr>
                  <th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left">
                     <strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic; text-transform: uppercase;">GST</strong>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
                     <span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;"><?php echo $company['gst_number']; ?></span>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <?php }*/ ?>
      <?php 
         if($quotation['is_quotation_bank_details1_send']=='Y' || $quotation['is_quotation_bank_details2_send']=='Y')
         {
         
           if($quotation['is_quotation_bank_details1_send']=='Y' && $quotation['is_quotation_bank_details2_send']=='Y')
           {
             $bank_td_colspan='2';
           }
           else 
           {
             $bank_td_colspan='1';
           } 
         ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;box-sizing: border-box; padding:0 10px;">
         <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
            <thead>
               <tr>
                  <th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left" colspan="<?php echo $bank_td_colspan; ?>">
                     <strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic;"><b>BANK DETAILS</b></strong>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <?php if($quotation['is_quotation_bank_details1_send']=='Y'){?>
                  <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF; border-right:#888888 1px solid;">
                     <span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
                     <?php echo $company['quotation_bank_details1']; ?>
                     </span>
                  </td>
                  <?php
                     }
                     ?>
                  <?php if($quotation['is_quotation_bank_details2_send']=='Y'){?>
                  <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
                     <span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
                     <?php echo $company['quotation_bank_details2']; ?>
                     </span>
                  </td>
                  <?php
                     }
                     ?>
               </tr>
            </tbody>
         </table>
      </div>
      <?php
         }
         ?>
      <?php if($quotation['letter_footer_text']){ ?>
      <div style="width:100%; height:auto; display: inline-block; margin: 20px 0; font-weight: 400; font-size:12px; color:#242424;font-family: century-gothic;box-sizing: border-box; padding:0 10px;">
         <?php echo $quotation['letter_footer_text']; ?>
      </div>
      <?php } ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;box-sizing: border-box; padding:0 10px;">
         <div  style="width:100%; height:auto; display: inline-block; margin: 0; text-align:left;">
            <p class="pdf_thank_content">        
               <?php //echo $quotation['letter_thanks_and_regards']; ?>
               <?php        
                  if($quotation['is_digital_signature_checked']=='Y' && $curr_company['digital_signature']!='')
                  {
                    $digital_signature = assets_url()."uploads/clients/".$client_id."/company/logo/".$curr_company['digital_signature'];
                    
                    if(@GetImageSize($digital_signature))
                    {
                    ?>
            <div  style="width:100%; height:auto; display: inline-block; margin: 0; text-align:left;">
               <img src="<?php echo $digital_signature;?>" style="width: 100%; height:auto; display:inline-block; max-width:100px">
            </div>
            <?php 
               }
               }
               if($quotation['is_digital_signature_checked']=='Y')
               {
               ?>
            <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px; font-weight:600;color: #000;font-family: century-gothic; text-align: left;">
               Name of Authorised Signatory
            </div>
            <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px;color: #000;font-family: century-gothic; text-align:left;">
               <?php echo ($quotation['name_of_authorised_signature'])?$quotation['name_of_authorised_signature']:''; ?>
            </div>
            <?php
               }
               else
               {
                 ?>
            <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px; font-weight:600;color: #000;font-family: century-gothic;text-align: left;">
               Thanks & Regards
            </div>
            <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px;color: #000;font-family: century-gothic;text-align: left;">
               <?php echo ($quotation['letter_thanks_and_regards'])?$quotation['letter_thanks_and_regards']:''; ?>
            </div>
            <?php
               }
               ?>
            </p>
         </div>
      </div>
      </div>
   </body>
</html>