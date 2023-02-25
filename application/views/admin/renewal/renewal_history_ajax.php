<input type="hidden" id="renewal_id" value="<?php echo $renewal_id; ?>">
<input type="hidden" id="rd_id" value="<?php echo $rd_id; ?>">
<div class="row">
	<div class="col-md-12">
		<div class="table-full-holder">
            <div class="table-one-holder">
                <table class="table " id="">
                  	<tr>
                  		<td><b>Renewal ID:</b> <?php echo $renewal->id; ?></td>
                  		<td><b>Created On:</b> 21-Aug-2021</td>
                  		<td><b>Assign To:</b> <?php echo $renewal->cust_assign_user_name; ?></td>
                  	</tr>	                  	   
                </table> 

                <table class="table custom-table" id="lead_table">      
                	<tr>
                		<th>Renewal Date</th>
                		<th>Follow-up Date</th>
                		<th>Status</th>
                		<th>Product/ Service</th>
                		<th width="20"></th>
                	</tr>
                	<?php                 	
                	if(count($renewal_details))
                	{
                		foreach($renewal_details AS $row)
                		{

                			$r_p_arr=explode(',', $row->renewal_product);
                		?>
                		<tr>
	                  		<td align="left">
	                  			
	                  			<?php echo date_db_format_to_display_format($row->renewal_date); ?></td>
	                  		<td align="left"><?php echo date_db_format_to_display_format($row->next_follow_up_date); ?></td>
	                  		<td align="left">
	                  			<?php 
	                  			$is_deleted_show='Y';
	                  			if($row->lead_id)
	                  			{
	                  				$is_deleted_show='N';
	                  				$won=array('4');
	                  				$lost=array('3','5','6','7');
	                  				if(in_array($row->current_stage_id,$won))
	                  				{
	                  					echo '<span class="badge badge-pill bg-success">Won</span>';
	                  				}
	                  				else if(in_array($row->current_stage_id,$lost))
	                  				{
	                  					echo '<span class="badge badge-pill bg-danger">Lost</span>';
	                  				}
	                  				else
	                  				{
	                  					echo '<span class="badge badge-pill bg-info">Active</span>';
	                  				}	
	                  			}
	                  			else
	                  			{
	                  				echo '<span class="badge badge-pill bg-warning">Pending</span>';
	                  			}
	                  			?>
	                  		</td>
	                  		<td>
	                  			  <ul class="list-group list-group-flush">
	                  			<?php
	                  			foreach($r_p_arr AS $p)
	                  			{
	                  				$p_arr=explode("#", $p);
	                  				$p_name=$p_arr[0];
	                  				$p_price=$p_arr[1];
	                  				?>
	                  				<li class="list-group-item list-group-item-info"><?php echo $p_name; ?><span class="badge badge-primary badge-pill bg-dark"><?php echo $company['default_currency_code'].'&nbsp;'. number_format($p_price,2); ?></span></li>
	                  				<?php
	                  			}
	                  			?>
 								
								</ul> 
	                  		</td>
	                  		<td class="text-center">
	                  			<?php if($is_deleted_show=='Y'){ ?>
	                  			<a href="JavaScript:void(0)" data-rid="<?php echo $row->renewal_id; ?>" data-rdid="<?php echo $row->id; ?>" class="edit_renewal_detail_view icon-btn-new btn-danger text-white"><i class="fa fa-pencil" aria-hidden="true"></i></a>
	                  		<?php }else{echo'';} ?>
	                  		</td>
	                  	</tr>
                		<?php
                		}
                	}
                  	?>
                  	<tr>
                  		<td colspan="4" align="left">
                  			<p><b>Company Details:(<a href="JavaScript:void(0)" class="edit_customer_view text-info" data-id="<?php echo $renewal->cus_id; ?>"><u>Edit</u></a>)</b></p>
                  			<p>
                  				<?php echo $renewal->cus_contact_person; ?><br>
                  				<?php echo ($renewal->cus_company_name)?$renewal->cus_company_name.'<br>':''; ?>
                  				<?php echo ($renewal->cus_mobile)?'Mobile: +'.$renewal->cus_mobile_country_code.'-'.$renewal->cus_mobile.'<br>':''; ?>
                  				<?php echo ($renewal->cus_email)?'Email: '.$renewal->cus_email.'<br>':''; ?>
                  				<?php echo ($renewal->cust_country_name)?'Location: '.$renewal->cust_country_name.'<br>':''; ?>
                  			</p>
                  		</td>
                  	</tr>           
                </table>      
            </div>
        </div>
	</div>
</div>