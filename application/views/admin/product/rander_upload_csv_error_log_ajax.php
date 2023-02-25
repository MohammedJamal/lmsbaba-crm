<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Product Uploaded CSV File Error Log</h4>
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
		            <th>category</th>
		            <th>name</th>
		            <th>currency_code</th>
		            <th>unit_type</th>
		            <th>description</th>
		            <th>code</th>
		            <th>price</th>
		            <th>unit</th>
		            <th>minimum_order_quantity</th>
		            <th>gst_percentage</th>
		            <th>hsn_code</th>
		            <th>youtube_video</th>
		            <th>product_available_for</th>
		            <th class="text-center" width="100">Error</th>
	            </tr>
	          </thead>
	          <tbody class=""> 
				<?php
				foreach($rows AS $row)
				{
					$td_cat_danger='';					
					$td_currency_danger='';
					$td_unit_type_danger='';					
					if (array_key_exists($row['tmp_id'],$error_log))
					{
						$bg_color='danger';
						if(count($error_log[$row['tmp_id']]))
						{
							foreach($error_log[$row['tmp_id']] AS $error)
							{
								if($error['keyword']=='category')
								{
									$td_cat_danger='td-danger';
									break;
								}								
							}							

							foreach($error_log[$row['tmp_id']] AS $error)
							{
								if($error['keyword']=='currency_type')
								{
									$td_currency_danger='td-danger';
									break;
								}								
							}

							foreach($error_log[$row['tmp_id']] AS $error)
							{
								if($error['keyword']=='unit_type')
								{
									$td_unit_type_danger='td-danger';
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
						<td class="<?php echo $td_cat_danger; ?>"><?php echo $row['cate_id']; ?></td> 
						<td class=""><?php echo $row['name']; ?></td> 
						<td class="<?php echo $td_currency_danger; ?>"><?php echo $row['currency_type']; ?></td> 
						<td class="<?php echo $td_unit_type_danger; ?>"><?php echo $row['unit_type']; ?></td>
						
						<td class=""><?php echo $row['description']; ?></td> 
						<td class=""><?php echo $row['code']; ?></td> 
						<td class=""><?php echo $row['price']; ?></td> 
						<td class=""><?php echo $row['unit']; ?></td>

						<td class=""><?php echo $row['minimum_order_quantity']; ?></td> 
						<td class=""><?php echo $row['gst_percentage']; ?></td> 
						<td class=""><?php echo $row['hsn_code']; ?></td> 
						<td class=""><?php echo $row['youtube_video']; ?></td>
						<td class=""><?php echo $row['product_available_for']; ?></td>
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