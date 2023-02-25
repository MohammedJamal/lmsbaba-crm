<!-- <pre><?php print_r($rows); ?></pre> -->
<div class="row">
	<div class="col-md-12 panel-group accordion" id="accordion">
	<?php if(count($rows)){ ?>
	<?php foreach($rows as $row){ ?>
		<div class="panel panel-default">
	        <div class="panel-heading">
	          <h4 class="panel-title">
	          	<a data-toggle="collapse" data-parent="#accordion_<?php echo $row['id']; ?>" href="#collapse_<?php echo $row['id']; ?>">
	          		Updated By: <?php echo $row['updated_by_user_name'];  ?> | Updated On: <?php echo date_db_format_to_display_format($row['created_at']);  ?> | IP: <?php echo $row['ip_address'];  ?>
	          		<?php echo ($row['comment'])?' | '.$row['comment']:''; ;  ?>
	          	</a>
	          </h4>
	        </div>
	        <div id="collapse_<?php echo $row['id']; ?>" class="panel-collapse collapse">
	          <div class="panel-body">
	            <div class="row">
					
	            	<?php 
					foreach($row['details'] as $r)
					{ 
					?>
						<?php					
						if($row['history_type']=='T')
						{
						?>
						  <div class="col-md-6">
							<?php
							// if (array_key_exists($r['updated_field'], $field_name_arr)) {
							//     echo "The 'first' element is in the array";
							// }
							?>
							<b><?php echo $field_name_arr[$r['updated_field']]; ?></b>: <font class="text-success"><?php echo $r['updated_value']; ?></font> (<font class="text-danger">Before: <?php echo $r['before_update_value']; ?></font>)
						  </div>
						<?php
						}
						else if($row['history_type']=='E')
						{
						 ?>
							<div class="col-md-12">		              	
								<b>From mail</b>: <font class="text-success"><?php echo $r['from_mail']; ?></font>
							  </div>
							  <div class="col-md-12">		              	
								<b>From Name</b>: <font class="text-success"><?php echo $r['from_name']; ?></font>
							  </div>
							  <div class="col-md-12">		              	
								<b>To mail</b>: <font class="text-success"><?php echo $r['to_mail']; ?></font>
							  </div>
							  <?php if($r['cc_mail']){ ?>
							  <div class="col-md-12">		              	
								<b>CC mail</b>: <font class="text-success"><?php echo $r['cc_mail']; ?></font>
							  </div>
							  <?php } ?>
							  <div class="col-md-12">		              	
								<b>Mail subject</b>: <font class="text-success"><?php echo $r['subject']; ?></font>
							  </div>
							  <div class="col-md-12">		              	
								<b>Mail Body</b>: <font class="text-success"><?php echo $r['body']; ?></font>
							  </div>
							  <?php if($r['attachment']){ ?>
							  <div class="col-md-12">	
								<a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/customer/download/<?php echo base64_encode($r['attachment']); ?>"> Download <i class="fa fa-download" aria-hidden="true"></i></b></a> 		              	
							  </div>
								 <?php
								}
								?>
	          		<?php 
					} 
					
					}
					?>
					
					
	          		
					
	            </div>                                        
	          </div>
	        </div>
	    </div>
	<?php } ?> 
	</div>
	<?php }else{ ?>
	<div class="col-md-12">Oops! No record found.</div>
	<?php } ?>

</div>