<!-- <div class="row text-center">
	<div class="col-md-12 bg-info"><b>Start Date:</b> <?php echo date_db_format_to_display_format($package_info->purchased_datetime); ?> | <b>Expire Date:</b> <?php echo date_db_format_to_display_format($package_info->expire_date); ?></div>
</div> -->
<div>&nbsp;</div>
<div class="table-responsive">
	<table class="table table-bordered table-striped m-b-0 th_color lead-board" id="">
		<thead>
			<tr>
				<th width="5%" align="center">User ID</th>
				<th width="">User Name</th>
                <th width="20%">Mobile Number</th>
				<th width="20%" align="center">Last Logged In</th>
				<th width="10%" align="center">Status</th>
			</tr>
		</thead>
		<tbody  class="t-contant-img">
			
			<?php if(count($rows)){ ?>
				<?php foreach($rows AS $row){ ?>
					<tr>
						<td align="center"><?php echo $row['id']; ?></td>
						<td align="left"><?php echo $row['name']; ?></td>
                        <td align="left">
                            Office: <?php echo $row['mobile']; ?><br>
                            Personal: <?php echo $row['personal_mobile']; ?>
                        </td>
						<td align="center"><?php echo ($row['last_login_datetime'])?datetime_db_format_to_display_format_ampm($row['last_login_datetime']):'-'; ?></td>
						<td align="center">
							<?php  
							if($row['status']=='0'){
								echo'<b class="text-success">Active</b>';
							}
							else if($row['status']=='1'){
								echo'<b class="text-danger">In-Active</b>';

							}
							else if($row['status']=='2'){
								echo'<b class="text-danger">Deleted</b>';

							}
							?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>

