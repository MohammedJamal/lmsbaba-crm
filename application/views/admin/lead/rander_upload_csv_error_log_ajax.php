<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Lead Uploaded CSV File Error Log</h4>
    <h6 class="text-danger">Found a formatting error in <?php echo $total_error; ?> row(s) out of <?php echo $total_rows; ?> row(s) in <?php echo $uploaded_csv_file_name; ?> file which you are trying to upload.  Please correct the CSV file and upload again.</h6>
</div>
<div class="modal-body">
<?php
if(count($rows))
{
	//print_r($error_log);
	?>
	<div class="table-responsivedd error-table-holder">
		<table class="table error-table table-striped" id="" >
	          <thead>
	            <tr>          
		            <th>lead_title</th>
		            <th>lead_describe_requirement</th>
		            <th>lead_enquiry_date</th>
		            <th>lead_next_followup_date</th>
		            <th>lead_source</th>
		            <th>assigned_user_employee_id</th>
		            <th>company_name</th>
		            <th>company_contact_person</th>
		            <th>company_contact_person_designation</th>
		            <th>company_email</th>
		            <th>company_alternate_email</th>
		            <th>company_mobile</th>
		            <th>company_alternate_mobile</th>
		            <th>company_landline_number</th>
		            <th>company_address</th>
		            <th>company_city</th>
		            <th>company_zip</th>
		            <th>company_website</th>
		            <th>company_short_description</th>
					<th>reference_name</th>
					<th>company_country</th>
		            <th class="text-center" width="100">Error</th>
	            </tr>
	          </thead>
	          <tbody class=""> 
				<?php
				foreach($rows AS $row)
				{
					$td_source_danger='';
					$td_emp_danger='';
					$td_city_danger='';					
					if (array_key_exists($row['tmp_id'],$error_log))
					{
						$bg_color='danger';
						if(count($error_log[$row['tmp_id']]))
						{
							foreach($error_log[$row['tmp_id']] AS $error)
							{
								if($error['keyword']=='lead_source')
								{
									$td_source_danger='td-danger';
									break;
								}								
							}

							foreach($error_log[$row['tmp_id']] AS $error)
							{
								if($error['keyword']=='assigned_user_employee_id')
								{
									$td_emp_danger='td-danger';
									break;
								}								
							}

							foreach($error_log[$row['tmp_id']] AS $error)
							{
								if($error['keyword']=='company_city')
								{
									$td_city_danger='td-danger';
									break;
								}								
							}

							
						}
					}
					else
					{
						$bg_color='success tr-log-hide';
					}	


					?>
					<tr class="<?php echo $bg_color; ?>">  
						<td class=""><?php echo $row['lead_title']; ?></td> 
						<td class=""><?php echo $row['lead_describe_requirement']; ?></td> 
						<td class=""><?php echo $row['lead_enquiry_date']; ?></td> 
						<td class=""><div class="min-width"><?php echo $row['lead_next_followup_date']; ?></div></td> 
						<td class="<?php echo $td_source_danger; ?>"><?php echo $row['lead_source']; ?></td> 
						<td class="<?php echo $td_emp_danger; ?>"><div class="min-width"><?php echo $row['assigned_user_employee_id']; ?></div></td> 
						<td class=""><?php echo $row['company_name']; ?></td> 
						<td class=""><?php echo $row['company_contact_person']; ?></td> 
						<td class=""><?php echo $row['company_contact_person_designation']; ?></td> 
						<td class=""><div class="min-width"><?php echo $row['company_email']; ?></div></td> 
						<td class=""><?php echo $row['company_alternate_email']; ?></td> 
						<td class=""><?php echo $row['company_mobile']; ?></td> 
						<td class=""><?php echo $row['company_alternate_mobile']; ?></td> 
						<td class=""><?php echo $row['company_landline_number']; ?></td> 
						<td class=""><?php echo $row['company_address']; ?></td> 
						<td class="<?php echo $td_city_danger; ?>"><?php echo $row['company_city']; ?></td> 
						<td class=""><?php echo $row['company_zip']; ?></td> 
						<td class=""><?php echo $row['company_website']; ?></td> 
						<td class=""><?php echo $row['company_short_description']; ?></td>
						<td class=""><?php echo $row['reference_name']; ?></td>
						<td class=""><?php echo $row['company_country']; ?></td> 
						<td data-title="Action" class="text-center">
							<div class="min-width error">
								<?php
								if(count($error_log[$row['tmp_id']]))
								{
									echo'<ol>';
									foreach($error_log[$row['tmp_id']] AS $error)
									{
										echo '<li>'.$error['msg'].'</li>';
									}
									echo'</ol>';
								}
								?>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
	    </table>
    </div>
	<?php
}
?>
</div>
<!-- <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div> -->
<style type="text/css">
	.tr-log-hide{
		display: none;
	}
</style>