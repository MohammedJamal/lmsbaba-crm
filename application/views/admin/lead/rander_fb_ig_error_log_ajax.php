<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Facebook/ Instagram Lead CSV File Error Log</h4>
    <h6 class="text-danger">Found a formatting error in <?php echo $total_error; ?> rows out of <?php echo $total_rows; ?> rows in <?php echo $uploaded_csv_file_name; ?> file which you are trying to upload.  Please correct the CSV file and upload again.</h6>
</div>
<div class="modal-body">
<?php
if(count($rows))
{
	//print_r($error_log);
	?>
	<div class="table-responsivedd error-table-holder">
		<table class="table error-table" id="" >
	          <thead>
	            <tr>          
		            <th>id</th>
		            <th>created_time</th>
		            <th>ad_id</th>
		            <th>ad_name</th>
		            <th>adset_id</th>
		            <th>adset_name</th>
		            <th>campaign_id</th>
		            <th>campaign_name</th>
		            <th>form_id</th>
		            <th>form_name</th>
		            <th>is_organic</th>
		            <th>platform</th>
		            <th>your_mob_no</th>
		            <th>full_name</th>
		            <th>email</th>
		            <th>phone_number</th>
		            <th>city</th>
		            <th>assigned_emp_id</th>
		            <th class="text-center" width="100">Error</th>
	            </tr>
	          </thead>
	          <tbody class=""> 
				<?php
				foreach($rows AS $row)
				{
					$td_city_danger='';
					$td_emp_danger='';
					if (array_key_exists($row['tmp_id'],$error_log))
					{
						$bg_color='danger';
						if(count($error_log[$row['tmp_id']]))
						{
							foreach($error_log[$row['tmp_id']] AS $error)
							{
								if($error['keyword']=='city')
								{
									$td_city_danger='td-danger';
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
						}
					}
					else
					{
						$bg_color='success tr-log-hide';
					}	


					?>
					<tr class="<?php echo $bg_color; ?>">  
						<td class=""><?php echo $row['id']; ?></td> 
						<td class=""><?php echo $row['created_time']; ?></td> 
						<td class=""><?php echo $row['ad_id']; ?></td> 
						<td class=""><div class="min-width"><?php echo $row['ad_name']; ?></div></td> 
						<td class=""><?php echo $row['adset_id']; ?></td> 
						<td class=""><div class="min-width"><?php echo $row['adset_name']; ?></div></td> 
						<td class=""><?php echo $row['campaign_id']; ?></td> 
						<td class=""><?php echo $row['campaign_name']; ?></td> 
						<td class=""><?php echo $row['form_id']; ?></td> 
						<td class=""><div class="min-width"><?php echo $row['form_name']; ?></div></td> 
						<td class=""><?php echo $row['is_organic']; ?></td> 
						<td class=""><?php echo $row['platform']; ?></td> 
						<td class=""><?php echo $row['your_mob_no']; ?></td> 
						<td class=""><?php echo $row['full_name']; ?></td> 
						<td class=""><?php echo $row['email']; ?></td> 
						<td class=""><?php echo $row['phone_number']; ?></td> 
						<td class="<?php echo $td_city_danger; ?>"><?php echo $row['city']; ?></td> 
						<td class="<?php echo $td_emp_danger; ?>"><?php echo $row['assigned_user_employee_id']; ?></td> 
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