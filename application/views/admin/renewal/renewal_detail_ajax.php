<div class="row">
	<div class="col-md-12">
		<div class="table-full-holder">
            <div class="table-one-holder">
                <table class="table custom-table" id="lead_table">                  
                  	
	                  	<tr>
	                  		<td width="30%">Renewal ID:</td>
	                  		<td width="2%">:</td>
	                  		<td>#<?php echo $renewal->id; ?></td>
	                  	</tr>
	                  	<tr>
	                  		<td width="30%">Renewal Service/ Product :</td>
	                  		<td>:</td>
	                  		<td>
	                  		<?php 
	                  		$p_str='';
	                  		if(count($renewal_products))
	                  		{
	                  			foreach($renewal_products AS $p)
	                  			{
	                  				$p_str .="".$p->product_name.', ';
	                  			}
	                  			echo rtrim($p_str,', ');
	                  		}
	                  		?>
	                  		</td>
	                  	</tr>
	                  	<tr>
	                  		<td>Created On</td>
	                  		<td>:</td>
	                  		<td><?php echo datetime_db_format_to_display_format($renewal->created_at); ?></td>
	                  	</tr>
	                  	<tr>
	                  		<td>Company ID</td>
	                  		<td>:</td>
	                  		<td>#<?php echo ($renewal->cus_id)?$renewal->cus_id:'N/A'; ?></td>
	                  	</tr>
	                  	<tr>
	                  		<td>Company</td>
	                  		<td>:</td>
	                  		<td><?php echo ($renewal->cus_company_name)?$renewal->cus_company_name:'N/A'; ?></td>
	                  	</tr>
	                  	<tr>
	                  		<td>Contact Person</td>
	                  		<td>:</td>
	                  		<td><?php echo ($renewal->cus_contact_person)?$renewal->cus_contact_person:'N/A'; ?></td>
	                  	</tr>
	                  	<tr>
	                  		<td>Company Email</td>
	                  		<td>:</td>
	                  		<td><?php echo ($renewal->cus_email)?$renewal->cus_email:'N/A'; ?></td>
	                  	</tr> 
	                  	<tr>
	                  		<td>Company Mobile</td>
	                  		<td>:</td>
	                  		<td><?php echo ($renewal->cus_mobile)?$renewal->cus_mobile:'N/A'; ?></td>
	                  	</tr>    
                </table> 

                <table class="table custom-table" id="lead_table">      
                	<tr>
                		<th width="10%">Price</th>
                		<th width="15%">Follow-up Date</th>
                		<th width="15%">Renewal Date</th>
                		<th width="60%">Describe Renewal/ AMC Type</th>
                	</tr>            
                  	<?php 
                  	if(count($renewal_details))
                  	{
                  		foreach($renewal_details AS $row)
                  		{
                  		?>
	                  	<tr>
	                  		<td><?php echo $row->price; ?>/-</td>
	                  	
	                  		<td><?php echo date_db_format_to_display_format($row->next_follow_up_date); ?></td>	                  	
	                  		<td><?php echo date_db_format_to_display_format($row->renewal_date); ?></td>
	                  		<td><?php echo $row->description; ?></td>
	                  	</tr>
                  	<?php 
                  		}
                  	}
                  	?>                  
                </table>      
            </div>
        </div>
	</div>
</div>