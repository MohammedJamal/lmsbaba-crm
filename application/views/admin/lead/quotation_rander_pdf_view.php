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

if(trim($company['state'])=='' || trim($customer['state'])=='')
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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
</head>
<body style="font-family: century-gothic;">
	<div class="wrapper-ar">

		
			
			<div class="head-invoice" style="width:100%; height:auto; display: inline-block; padding: 10px; box-sizing: border-box; text-align:center; font-size:16px; font-weight:700;background: #000; color: #FFF;font-family: century-gothic;">
      QUOTATION
    </div>
		<div>&nbsp;</div>
        <div style="width: 68%; float: left; margin-bottom: 5px">
         	<div class="" style="width: 20%; float: left; box-sizing: border-box; padding: 0 20px 0 0;">
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
				<img style="width: 100%; height: auto; display: block;" src="<?php echo $profile_img_path;?>">
            </div>
            <div style="width:70%; float: left; font-family: century-gothic;">
			  	<h2 style="font-weight: 600; font-size: 16px; margin: 0; font-family: century-gothic;">
			  		<b><?php echo $company['name'];?></b>
			  			
			  	</h2>
			  	<p style="font-size: 14px;"><?php rander_company_address('generate_quotation_pdf',$client_info); ?></p>
		    </div>
        </div>
        <div style="width: 32%; float: right;">
 	       <ul style="list-style: none; float: right; font-size: 14px;">
				<li>
					<img src="<?php echo assets_url(); ?>images/msg-icon.png" style="position: relative; padding-right: 3px;" width="16"> <?php echo $company['email1'];?>
				</li>
				<li>
					<img src="<?php echo assets_url(); ?>images/mobile-phone-icon.png" style="position: relative;padding-right: 3px;" width="16"> <?php echo $company['mobile1'];?></li>
				<li>
					<img src="<?php echo assets_url(); ?>images/web.png" style="position: relative;padding-right: 3px; padding-top: 5px;" width="16"> 
					<?php echo $company['website'];?>
				</li>
			</ul> 
        </div>

      	<div style="width: 100%; float: left;">
	        <table class="header-border" border="0" style="border: 1px solid #000; width: 100%; color: #000; background: #FFF;">
				<tr>
					<td style="padding: 6px; text-align: center; font-family: century-gothic; font-size: 14px; border-right: 1px solid #000;">
						<strong>Quote Date</strong><br>
						<?php echo date_db_format_to_display_format($quotation['quote_date']); ?>
					</td>
					<td style="padding: 6px; text-align: center; font-family: century-gothic; font-size: 14px; border-right: 1px solid #000;">
						<strong>Quote No</strong><br>
						<?php echo $quotation['quote_no']; ?>
					</td>
					<td style="padding: 6px; text-align: center; font-family: century-gothic; font-size: 14px; border-right: 1px solid #000;">
						<strong>Enquiry Ref</strong><br>
						<?php echo get_company_name_initials($client_info); ?> - <?php echo $quotation_data['lead_opportunity_data']['lead_id']; ?>
					</td>
					<td style="padding: 6px; text-align: center; font-family: century-gothic; font-size: 14px;">
						<strong>Quote Valid Untill</strong><br>
						<?php echo date_db_format_to_display_format($quotation['quote_valid_until']); ?>
					</td>
				</tr>
			</table>
      	</div>

      	<div class="letter-header-left" style="width: 80%; float: left;">
        	<h4 style="margin-bottom: 5px; font-size: 14px;">To,</h4>
        	<p  style="margin-top: 3px;  font-weight: 400; line-height: normal; font-size: 14px;">
        		<?php echo $quotation['letter_to']; ?>
        	</p>
        	<h3 style="margin-bottom: 5px; font-size: 12px;">Subject: <?php echo $quotation['letter_subject']; ?></h3>	
        </div>

        <div style="width: 100%; clear: both; margin: 0 0 20px;">
        	
			<!-- <h4 style="margin-bottom: 0px; font-weight: 700; font-size: 12px;">Dear Sir/Ma’am,</h4> -->
			<p style="margin-top: 0px; font-size: 14px;"><?php echo $quotation['letter_body_text']; ?></p>        	
        </div>
		
		<?php /* ?><pagebreak /><?php */ ?>
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
	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 20px 0; font-weight: 400;">
		<table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
			<thead>
				<tr>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
						<span style="font-size:14px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;"><b>Sl.</b></span>
					</th>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:left;">
						<strong style="font-size:14px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic; text-align: left;"><b>Product Details</b></strong>
					</th>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid; font-weight:400">
						<div style="font-size:14px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;"><b>Unit Price</b></div>
						<span style="font-size:12px; color:#000;margin: 0; font-weight:100; width: 100%;font-family: century-gothic; white-space: nowrap;">(<?php echo $quotation['currency_type']; ?>)</span>
					</th>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
						<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;">Qty</strong>
					</th>
					<?php if($is_discount_available=='Y'){ ?>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;font-weight: 400;">
						<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block; white-space: nowrap;font-family: century-gothic;"><b>Discount</b></strong><br>
						<span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;">(in <?php echo ($prod_list[0]->is_discount_p_or_a=='P')?'%':'Amt.'; ?>)</span>
					</th>
					<?php } ?>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;font-weight: 400;">
						<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block; white-space: nowrap;font-family: century-gothic;"><b>GST</b></strong><br><span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic; white-space: nowrap;">(in %)</span>
					</th>					
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-bottom:#888888 1px solid; font-weight:400">
						<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;"><b>Net Amount</b></strong><br>
						<span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;">(<?php echo $quotation['currency_type']; ?>)</span>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
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
				 ?>
				<tr>
					<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
								<strong style="font-size:14px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $s; ?></b></strong>
					</td>
			    <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-alignl:left;">
			      		<div style="font-size:14px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic;">
			      			<?php echo $output->product_name; ?>
			      			<?php 			      			
									if($quotation['is_product_image_show_in_quotation']=='Y')
									{ 
										if($output->image!='')
										{
										 $p_image_path = assets_url()."uploads/clients/".$client_id."/product/thumb/".$output->image;
										  if(file_exists("assets/uploads/clients/".$client_id."/product/thumb/".$output->image))
										  {
										  ?>
										  <p style="margin: 0;"><img src="<?php echo $p_image_path;?>" style="width:100px;"></p>
										  <?php
											}											
										}                        
									} 
									?>
			      	</div>
			      </td>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:center">
			      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $output->price;?></b></strong><br>
			      			<span style="font-size:14px; color:#000;margin: 0; width: 100%;font-family: century-gothic;">Per <?php echo $output->unit;?> <?php echo $output->unit_type; ?></span>
			      </td>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
			      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $output->quantity; ?></b></strong>
			      </td>
			      <?php if($is_discount_available=='Y'){ ?>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
			      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($output->discount>0)?$output->discount:'-'; ?> <?php echo ($output->is_discount_p_or_a=='P' && $output->discount>0)?'%':''; ?></b></b></strong>
			      </td>
			    	<?php } ?>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center">
			      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($output->gst>0)?$output->gst:'NIL';?></b></strong>
			      </td>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($row_final_price,2);?></b></strong>
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
						$sub_total=$sub_total+$row_final_price; 

						$total_price=$total_price+$item_total_amount;
						$total_tax_price=$total_tax_price+$row_gst_amount;
						$total_discounted_price=$total_discounted_price+$row_discount_amount;
          	
          ?>

          <tr>
						<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
									<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $s; ?></b></strong>
						</td>
				    <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-alignl:left;">		
				      		<div style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;font-family: century-gothic;"><?php echo $charge->additional_charge_name; ?></div>
				      </td>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:center">
				      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $charge->price; ?></b></strong>
				      </td>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
				      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;">-</strong>
				      </td>
				      <?php if($is_discount_available=='Y'){ ?>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
				      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($charge->discount>0)?$charge->discount:'-'; ?> <?php echo ($charge->is_discount_p_or_a=='P' && $charge->discount>0)?'%':''; ?></b></b></strong>
				      </td>
				    	<?php } ?>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center">
				      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($charge->gst>0)?$charge->gst:'NIL'; ?></b></strong>
				      </td>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
				      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($row_final_price,2); ?></b></strong>
				      </td>
				  </tr>
          <?php
          	$s++;
          	$j++; 
        		} 
        	} 
        	?>			    
			    <tr>			      	
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:right" colspan="<?php echo ($is_discount_available=='Y')?'6':'5'; ?>">
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Total Net Amount:</b></strong>
			      	</td>
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      		
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($sub_total,2);?></b></strong>
			      	</td>
			    </tr>

			    <tr>
			      	
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:right" colspan="<?php echo ($is_discount_available=='Y')?'6':'5'; ?>">
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b>Total Gross Amount:</b></strong><br>
			      		<?php if($is_discount_available=='Y'){ ?>
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b>Total Discount:</b></strong><br>
			      		<?php } ?>
			      		<?php 
								if($is_same_state=='Y')
								{ 
								?>
								<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b>SGST:</b></strong><br>
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>CGST:</b></strong>
								<?php
								}
								else
								{
									?>
									<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($is_state_missing=='Y')?'GST':'IGST'; ?>:</b></strong>
									<?php
								}
								?>
			      		
			      	</td>
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo number_format($total_price,2); ?></b></strong><br>
			      		<?php if($is_discount_available=='Y'){ ?>
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo number_format($total_discounted_price,2); ?></b></strong><br>
			      		<?php } ?>
			      		<?php 
								if($is_same_state=='Y')
								{ 
									$sgst=($total_tax_price/2);
									$cgst=($total_tax_price/2);
								?>
									<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo ($sgst>0)?number_format($sgst,2):'NIL'; ?></b></strong><br>
			      			<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($cgst>0)?number_format($cgst,2):'NIL'; ?></b></strong>
								<?php 
								} 
								else
								{
								?>
								<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo ($total_tax_price>0)?number_format($total_tax_price,2):'NIL'; ?></b></strong>
								<?php
								}
								?>			      		
			      	</td>
			    </tr>

			    <tr>			      	
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:right" colspan="<?php echo ($is_discount_available=='Y')?'6':'5'; ?>">
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Payable amount (Round Off):</b></strong>
			      	</td>
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      		
			      		<strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format(round($sub_total),2); ?></b></strong>
			      	</td>
			    </tr>
			    
			</tbody>
		</table>
	</div>
	<pagebreak />
    
		
     

		


	    <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;">
        <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
          <thead>
            <tr>
              <th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left">
                <strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic;"><b>Terms & Conditions:</b></strong>
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
							              <p style="font-size: 13px;"><span style="font-size: 13px; font-weight: bold;"><?php echo ($term['name']); ?>: </span><?php echo ($term['value']); ?></p>
							            <?php } ?>
							          <?php } ?>
							        <?php }else{echo'Not Applicable';} ?> 
                  </td>
              </tr>
          </tbody>
        </table>
      </div>


	    

		<?php if($quotation['letter_terms_and_conditions']){ ?>
      <div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;">
        <table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
          <thead>
            <tr>
              <th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left">
                <strong style="font-size:14px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic;"><b>Other Terms & Conditions:</b></strong>
              </th>
              
            </tr>
          </thead>
          <tbody>
            <tr>
                  <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
                    <span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
                      <?php echo $quotation['letter_terms_and_conditions']; ?>
                  </td>
              </tr>
          </tbody>
        </table>
      </div>

      <?php } ?>

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
	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;">
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
                <span style="font-size:14px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
                  <?php echo $company['quotation_bank_details1']; ?>
                </span>
            </td>
            <?php
              }
              ?>
            <?php if($quotation['is_quotation_bank_details2_send']=='Y'){?>
            <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
                <span style="font-size:14px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
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
		<?php /*if(strtoupper($quotation['currency_type'])!='INR'){ ?>
		<div class="text" style="margin-bottom: 5px;">
			<h3 style="font-size: 16px;">Correspondent Bank: </h3>
			<?php
			$letter_correspondent_bank='<b>'.$company['correspondent_bank_name'].'</b><br><b>Swift Number</b> : '.$company['correspondent_bank_swift_number'].'<br><b>IOB Account</b> : '.$company['correspondent_account_number'];
			?>
			<p style="font-size: 13px;"><?php echo $letter_correspondent_bank; ?></p>
		</div>
		<?php }*/ ?>
		<?php if($quotation['is_gst_number_show_in_quotation']=='Y'){ ?>
		<div class="text">
			<div style="font-size: 13px;"><b style="font-size: 12px;">GST Number:</b> <?php echo $company['gst_number']; ?></div>
		</div>
		<?php } ?>
		<div style="margin-top:8px;"><?php echo $quotation['letter_footer_text']; ?></div>
			<p class="pdf_thank_content">        
        <?php //echo $quotation['letter_thanks_and_regards']; ?>

        <?php
        
        if($quotation['is_digital_signature_checked']=='Y' && $curr_company['digital_signature']!='')
        {
          $digital_signature = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$curr_company['digital_signature'];
          
          if(@GetImageSize($digital_signature))
          {
          ?>
          <div  style="width:100%; height:auto; display: inline-block; margin: 0;">
            <img src="<?php echo $digital_signature;?>" style="width: 100%; height:auto; display:inline-block; max-width:100px">
          </div>
          <?php 
          }
        }
        if($quotation['is_digital_signature_checked']=='Y')
        {
        ?>
        <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px; font-weight:600;color: #000;font-family: century-gothic;">
          Name of Authorised Signatory
        </div>
        <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px;color: #000;font-family: century-gothic;">
          <?php echo ($quotation['name_of_authorised_signature'])?$quotation['name_of_authorised_signature']:''; ?>
        </div>
        <?php
        }
        else
        {
          ?>
          <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px; font-weight:600;color: #000;font-family: century-gothic;">
          Thanks & Regards
        </div>
        <div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px;color: #000;font-family: century-gothic;">
          <?php echo ($quotation['letter_thanks_and_regards'])?$quotation['letter_thanks_and_regards']:''; ?>
        </div>
          <?php
        }
        ?>
      </p>
			<!-- <p style="font-size: 13px;">
				<b>Thanks & Regards, </b><br>
				<?php //echo $quotation['letter_thanks_and_regards']; ?>
			</p> -->
		</div>
		<p style="font-size: 13px; text-align: center; position: absolute;bottom: 20px; width: 100%">This document generated electronically, hence signature is not required.</p>	
</body>
</html>