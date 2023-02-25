<?php $cid = 1 ?>
<?php if(count($rows)){ ?>
	<?php foreach($rows as $row){ ?>
		<tr>
			<th scope="row"><?php echo date_db_format_to_display_format($row->date_str); ?></th>
			<td><?php echo $row->total_lead_count; ?></td>
			<td><?php echo $row->total_quoted_lead_count; ?></td>
			<td><?php echo $row->total_regretted_lead_count; ?></td>
			<td><?php echo $row->total_pending_lead_count; ?></td>
			<td><?php echo $row->total_deal_won_lead_count; ?></td>
			<td><?php echo $row->total_deal_lost_lead_count; ?></td>
			<td><a class="revenue_btn" href="weekly_details_<?php echo $cid; ?>"></a></td>			
		</tr>
		<tr class="hide_details" id="weekly_details_<?php echo $cid; ?>">
			<th colspan="8">
				<ul class="details_ul">
					<?php if(count($currency_list)){ ?>
						<?php
						$str=$row->total_revenue_wise_currency;
						$str_arr=explode("@",$str);
						$total_revenue_wise_currency_arr=array();
						foreach($str_arr AS $arr_val1)
						{
							// print_r($arr_val1);
							// echo"<br>";
							$arr2=explode(",",$arr_val1);
							$arr4=array();
							foreach($arr2 AS $arr_val2)
							{
								$arr3=explode("#",$arr_val2);
								$arr4=array($arr3[1]=>$arr3[0]);
								array_push($total_revenue_wise_currency_arr,$arr4);
							}
							//array_push($total_revenue_wise_currency_arr,$arr4);
						}
						?>
						<?php foreach($currency_list AS $currency){?>
							<li>
								<strong><?php echo $currency->code; ?></strong>
								<?php
								$existing_rev=0;
								//print_r($total_revenue_wise_currency_arr);
								foreach($total_revenue_wise_currency_arr AS $rev_curr_val)
								{
									//print_r($rev_curr_val[$currency->code]);
									if(isset($rev_curr_val[$currency->code]))
									{
										$existing_rev=$rev_curr_val[$currency->code]+$existing_rev;
									}
								}
								echo number_format($existing_rev,2);
								//echo $row->total_revenue_wise_currency;
								?>
							</li>							
						<?php } ?>
					<?php } ?>
				</ul>
			</th>
		</tr>
		<?php $cid++ ?>
	<?php } ?>
<?php }else{ ?>
		<tr class="no-border-bottom">
			<th colspan="8"><div class="no-record weekly">No record found..</div></th>
		</tr>
<?php } ?>