<?php 
if($company['state_id']==$po_register_info->cust_state_id)
{
  $is_same_state='Y';
}
else
{
  $is_same_state='N';
}

if($company['state_id']=='' || $po_register_info->cust_state_id=='')
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

// IS DISCOUNT VAILABLE CHECKING : START
$is_discount_available='N';
if(count($prod_list))
{
	foreach($prod_list as $p)
	{
		if($p->discount>0)
		{
			$is_discount_available='Y';
			break;
		}
	}
}					
if($is_discount_available=='N')
{
	if(count($additional_charges_list))
	{
		foreach($additional_charges_list AS $c)
		{
			if($c->discount>0)
			{
				$is_discount_available='Y';
				break;
			}
		}
	}
}	
// IS DISCOUNT VAILABLE CHECKING : END
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>TAX INVOICE</title>
	<link rel="stylesheet" href="<?php echo assets_url();?>css/century_font.css">
	<style type="text/css" media="screen">
		*{
			font-family: 'Century Gothic';
			line-height: normal !important;
			font-size: 14px !important;
		}
		b{
			font-weight: 900;
		}
	</style>
</head>
<body>

<div class="main-invoice" style="width:800px; height:auto; display: block; margin: 20px auto 0; background:#FFF:">
	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 6px 0;">
		<table style="width:100%">
			<tbody>
				<tr>
					<td width="160px" style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle;">
						<?php
			            if($company['logo']!='')
			            {
			              $company_logo = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/thumb/".$company['logo'];
			            }
			            else
			            {
			              $company_logo = assets_url().'images/invoice-logo.jpg';
			            }			            
			            ?>
			        	<img src="<?php echo $company_logo;?>" style="width: 100px;height: auto;">
					</td>
					<td style="box-sizing: border-box; padding: 10px;vertical-align: middle;">

						<h2 style="font-size:16px; color:#031319;margin: 0px;font-family: century-gothic;"><?php echo $company['name']; ?></h2>
						<span style="font-size:10px; color:#031319;margin: 0px;font-family: century-gothic;"><?php echo $company['address']; ?><br><?php echo ($company['city_name'])?'City- '.$company['city_name']:''; ?><?php echo ($company['state_name'])?', State- '.$company['state_name']:''; ?><?php echo ($company['pin'])?', Pin-'.$company['pin']:''; ?><?php echo ($company['country_name'])?' ('.$company['country_name'].')':''; ?></span>
					</td>
					<td style="box-sizing: border-box; padding: 10px; border-left: #969696 1px solid;vertical-align: middle;">
						<span style="font-size:10px; color:#031319;margin: 0px;font-family: century-gothic;">
							<?php echo ($company['email1'])?'Email: '.$company['email1'].'<br>':''; ?>
							<?php echo ($company['mobile1'])?'Mobile: '.$company['mobile1'].'<br>':''; ?>
							<?php echo ($company['website'])?'Website: '.$company['website']:''; ?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; padding: 10px; box-sizing: border-box; text-align:center; font-size:16px; font-weight:500;background: #000; color: #FFF;font-family: century-gothic;">
		<b>TAX INVOICE</b>
	</div>

	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin:0;">
		<table colspan="0" rowspan="0" style="width:100%;border-bottom: #878787 3px solid;">
			<tbody>
				<tr>
					
					<td width="60%" style="box-sizing: border-box; padding: 10px;vertical-align: middle;">
						<table width="100%" style="width:100%; border-collapse: collapse;">
							<tbody>
								<tr>
									<td style="width: 100%; height: auto; display: inline-block;padding: 15px 0; border-bottom: #000000 1px dashed;">
										<h2 style="font-size:10px; color:#000000;margin: 0 0 5px 0; font-weight: 700;font-family: century-gothic; display: inline-block;"><b>BILL FROM:</b></h2>

										<div style="font-size:12px; color:#000;margin: 0 0 10px 0;  width: 100%; display:inline-block;font-family: century-gothic;">	<?php 
											echo ($po_inv_info->bill_from)?$po_inv_info->bill_from:'N/A';
											?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<table width="100%" style="width:100%; border-collapse: collapse;">
							<tbody>
								<tr>
									<td style="width: 100%; height: auto; display: inline-block;padding: 15px 0;">
										<h2 style="font-size:10px; color:#000000;margin: 0 0 5px 0; font-weight: 700;font-family: century-gothic;"><b>BILL TO:</b></h2>
										<div style="font-size:12px; color:#000;margin: 0 0 10px 0;  width: 100%; display:inline-block;font-family: century-gothic;">	<?php 
											echo ($po_inv_info->bill_to)?$po_inv_info->bill_to:'N/A';
											?>
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
										<div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Invoice No:  <?php 
											echo ($po_inv_info->invoice_no)?$po_inv_info->invoice_no:'N/A';
											?></b></div>
									</td>
								</tr>
								<tr>
									<td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px; border-bottom: #000000 1px solid;box-sizing: border-box;">
										<div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Invoice Date: <?php 
											echo ($po_inv_info->invoice_date)?date_db_format_to_display_format($po_inv_info->invoice_date):'N/A';
											?></b></div>
									</td>
								</tr>
								<tr>
									<td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px; border-bottom: #000000 1px solid;box-sizing: border-box;">
										<div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Due Date: <?php 											
											echo ($po_inv_info->due_date!='' && $po_inv_info->due_date!='0000-00-00')?date_db_format_to_display_format($po_inv_info->due_date):'N/A';
											?></b></div>
									</td>
								</tr>
								<tr>
									<td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px; border-bottom: #000000 1px solid;box-sizing: border-box;">
										<div style="font-size:10px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Expected Delivery Date: <?php 
											echo ($po_inv_info->expected_delivery_date!='' && $po_inv_info->expected_delivery_date!='0000-00-00')?date_db_format_to_display_format($po_inv_info->expected_delivery_date):'N/A';
											?></b></div>
									</td>
								</tr>
								<tr>
									<td style="width: 100%; height: auto; display: inline-block;padding: 8px 10px;">
										<?php if($po_inv_info->ship_to){ ?>
										<h2 style="font-size:10px; color:#000000;margin: 0 0 5px 0; font-weight: 700;font-family: century-gothic;"><b>SHIP TO:</b></h2>
										<div style="font-size:12px; color:#000;margin: 0 0 10px 0;  width: 100%; display:inline-block;font-family: century-gothic;">	<?php 
											echo ($po_inv_info->ship_to)?$po_inv_info->ship_to:'N/A';
											?>
										</div>
										<?php }else{echo'&nbsp;';} ?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 20px 0; font-weight: 400;">
		<table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
			<thead>
				<tr>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
						<span style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;"><b>Sl.</b></span>
					</th>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:left;">
						<strong style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic; text-align: left;"><b>Product Details</b></strong>
					</th>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid; font-weight:400">
						<div style="font-size:12px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;"><b>Unit Price</b></div>
						<span style="font-size:10px; color:#000;margin: 0; font-weight:100; width: 100%;font-family: century-gothic; white-space: nowrap;">(<?php echo $po_inv_info->currency_type; ?>)</span>
					</th>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;">
						<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;">Qty</strong>
					</th>
					<?php if($is_discount_available=='Y'){ ?>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;font-weight: 400;">
						<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block; white-space: nowrap;font-family: century-gothic;"><b>Discount</b></strong><br>
						<span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;">(in <?php echo ($prod_list[0]->is_discount_p_or_a=='P')?'%':'Amt.'; ?>)</span>
					</th>
					<?php } ?>
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-left:#888888 1px solid; border-bottom:#888888 1px solid;font-weight: 400;">
						<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block; white-space: nowrap;font-family: century-gothic;"><b>GST</b></strong><br><span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic; white-space: nowrap;">(in %)</span>
					</th>					
					<th style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: top; background: #d6d6d6; border-bottom:#888888 1px solid; font-weight:400">
						<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;white-space: nowrap;font-family: century-gothic;"><b>Net Amount</b></strong><br>
						<span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%;font-family: century-gothic;">(<?php echo $po_inv_info->currency_type; ?>)</span>
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
								<strong style="font-size:12px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $s; ?></b></strong>
					</td>
			    <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-alignl:left;">
			      		<div style="font-size:10px; color:#000;margin: 0; width: 100%; display:inline-block;font-family: century-gothic;"><?php echo $output->product_name; ?></div>
			      </td>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:center">
			      			<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $output->price;?></b></strong><br>
			      			<span style="font-size:12px; color:#000;margin: 0; width: 100%;font-family: century-gothic;">Per <?php echo $output->unit;?> <?php echo $output->unit_type; ?></span>
			      </td>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
			      			<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $output->quantity; ?></b></strong>
			      </td>
			      <?php if($is_discount_available=='Y'){ ?>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($output->discount>0)?$output->discount:'-'; ?> <?php echo ($output->is_discount_p_or_a=='P' && $output->discount>0)?'%':''; ?></b></strong>
			      </td>
			    	<?php } ?>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center">
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($output->gst>0)?$output->gst:'NIL';?></b></strong>
			      </td>
			      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($row_final_price,2);?></b></strong>
			      </td>
			  </tr>
		    <?php
				$s++;
				$i++;
				}
				}
				?>
				<?php 
				if(count($additional_charges_list))
				{

					$j=0;
					foreach($additional_charges_list AS $charge)
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
								<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $s; ?></b></strong>
						</td>
				    <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-alignl:left;">		
				      	<div style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block;font-family: century-gothic;"><?php echo $charge->additional_charge_name; ?></div>
				      </td>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid; text-align:center">
				      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo $charge->price; ?></b></strong>
				      </td>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
				      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;">-</strong>
				      </td>
				      <?php if($is_discount_available=='Y'){ ?>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center;font-family: century-gothic;">
				      	<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($charge->discount>0)?$charge->discount:'-'; ?> <?php echo ($charge->is_discount_p_or_a=='P' && $charge->discount>0)?'%':''; ?></b></strong>
				      </td>
				    	<?php } ?>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:center">
				      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($charge->gst>0)?$charge->gst:'NIL'; ?></b></strong>
				      </td>
				      <td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
				      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($row_final_price,2); ?></b></strong>
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
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Total Net Amount:</b></strong>
			      	</td>
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      		
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format($sub_total,2);?></b></strong>
			      	</td>
			    </tr>

			    <tr>			      	
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:right" colspan="<?php echo ($is_discount_available=='Y')?'6':'5'; ?>">
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b>Total Gross Amount:</b></strong><br>
			      		<?php if($is_discount_available=='Y'){ ?>
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b>Total Discount:</b></strong><br>
			      		<?php } ?>
			      		<?php 
								if($is_same_state=='Y')
								{ 
								?>
								<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b>SGST:</b></strong><br>
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>CGST:</b></strong>
								<?php
								}
								else
								{
									?>
									<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($is_state_missing=='Y')?'GST':'IGST'; ?>:</b></strong>
									<?php
								}
								?>
			      		
			      	</td>
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo number_format($total_price,2); ?></b></strong><br>
			      		<?php if($is_discount_available=='Y'){ ?>
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo number_format($total_discounted_price,2); ?></b></strong><br>
			      		<?php } ?>
			      		<?php 
								if($is_same_state=='Y')
								{ 
									$sgst=($total_tax_price/2);
									$cgst=($total_tax_price/2);
								?>
									<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo ($sgst>0)?number_format($sgst,2):'NIL'; ?></b></strong><br>
			      			<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo ($cgst>0)?number_format($cgst,2):'NIL'; ?></b></strong>
								<?php 
								} 
								else
								{
								?>
								<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;margin-bottom: 5px;font-family: century-gothic;"><b><?php echo ($total_tax_price>0)?number_format($total_tax_price,2):'NIL'; ?></b></strong>
								<?php
								}
								?>
			      		
			      	</td>
			    </tr>

			    <tr>			      	
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-left:#888888 1px solid; border-bottom:#888888 1px solid;text-align:right" colspan="<?php echo ($is_discount_available=='Y')?'6':'5'; ?>">
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b>Payable amount (Round Off):</b></strong>
			      	</td>
			      	<td style="box-sizing: border-box; padding: 10px; border-right: #969696 1px solid; vertical-align: middle; background: #FFF; border-bottom:#888888 1px solid;text-align:center">
			      		
			      		<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%; display:inline-block;font-family: century-gothic;"><b><?php echo number_format(round($sub_total),2); ?></b></strong>
			      	</td>
			    </tr>
			    
			</tbody>
		</table>
	</div>

<?php if($po_inv_info->terms_conditions){ ?>
	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;">
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
			      		<span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
			      			<?php 
									echo $po_inv_info->terms_conditions;
									?></span>
			      	</td>
			    </tr>
			</tbody>
		</table>
	</div>
<?php } ?>
<?php if($po_inv_info->additional_note){ ?>
	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;">
		<table style="width:100%; border-collapse: collapse; border:#888888 1px solid;">
			<thead>
				<tr>
					<th style="box-sizing: border-box; padding: 10px 15px; border-bottom: #969696 1px solid; vertical-align: top; background: #d6d6d6; text-align:left">
						<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic;">ADDITIONAL NOTE</strong>
					</th>
					
				</tr>
			</thead>
			<tbody>
				<tr>
			      <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
			      		<span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;"><?php 
									echo $po_inv_info->additional_note;
								?></span>
			      	</td>
			    </tr>
			</tbody>
		</table>
	</div>
<?php } ?>
<?php 
if($po_inv_info->bank_detail_1!="" || $po_inv_info->bank_detail_2!="")
{
	if($po_inv_info->bank_detail_1!="" && $po_inv_info->bank_detail_2!="")
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
						<strong style="font-size:12px; color:#000;margin: 0; font-weight:700; width: 100%;font-family: century-gothic;">BANK DETAILS</strong>
					</th>
					
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php
					if($po_inv_info->bank_detail_1!="")
					{
					?>
			     <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF; border-right:#888888 1px solid;">
			      		<span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
			      			<?php 
									echo ($po_inv_info->bank_detail_1)?$po_inv_info->bank_detail_1:'N/A';
									?>
								</span>
		      	</td>
		      	<?php
		      	}			      	
						if($po_inv_info->bank_detail_2!="")
						{
		      	?>
			      <td style="box-sizing: border-box; padding: 15px; vertical-align: middle; background: #FFF;">
			      		<span style="font-size:10px; color:#000;margin: 0; font-weight:400; width: 100%; display:inline-block; text-align: left; line-height: 20px;font-family: century-gothic;">
			      			<?php 
									echo ($po_inv_info->bank_detail_2)?$po_inv_info->bank_detail_2:'N/A';
									?>
			      		</span>
			      	</td>
			      	<?php
			      	}
			      	?>
			    </tr>
			</tbody>
		</table>
	</div>
<?php } ?>

	<div class="head-invoice" style="width:100%; height:auto; display: inline-block; margin: 0 0 15px 0;">
		<div  style="width:250px; height:auto; display: inline-block; margin: 0; text-align:center;">

			<?php
			if($po_inv_info->is_digital_signature_checked=='Y' && $company['digital_signature']!='')
			{
				$digital_signature = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$company['digital_signature'];
				
				if(@GetImageSize($digital_signature))
				{
			?>
			<div  style="width:100%; height:auto; display: inline-block; margin: 0;">
				<img src="<?php echo $digital_signature;?>" style="width: 100%; height:auto; display:inline-block; max-width:100px">
			</div>
			<?php 
				}
			}
			if($po_inv_info->is_digital_signature_checked=='Y')
			{
			?>
			<div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px; font-weight:400;color: #000;font-family: century-gothic;">
				Name of Authorised Signatory
			</div>
			<div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px;color: #000;font-family: century-gothic;">
				<?php echo ($po_inv_info->name_of_authorised_signature)?$po_inv_info->name_of_authorised_signature:''; ?>
			</div>
			<?php
			}
			else
			{
				?>
				<div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px; font-weight:400;color: #000;font-family: century-gothic;">
				Thanks & Regards
			</div>
			<div  style="width:100%; height:auto; display: inline-block; margin: 0; white-space:nowrap; font-size: 14px;color: #000;font-family: century-gothic;">
				<?php echo ($po_inv_info->thanks_and_regards)?$po_inv_info->thanks_and_regards:''; ?>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
</body>
</html>