<!-- <pre><?php print_r($rows); ?></pre> -->
<div class="row">	
	<div class="col-md-12 panel-group" id="">
	<?php if(count($rows)){ ?>
	<?php foreach($rows as $row){ ?>
		<div class="panel panel-default">
	        <div class="panel-heading">
	          <h4 class="panel-title">
	          	<a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?search_by_id=<?php echo $row['id'];?>" class="blue-link"><b><?php echo $row['title'];  ?></b></a>
	          </h4>
	        </div>
	        <div id="collapse_<?php echo $row['id']; ?>" class="panel-collapse collapse in">
	          <div class="panel-body">
	            <div class="row">
	            	<div class="col-md-4"><b>Lead #:</b> <?php echo $row['id'];?></div>
	            	<div class="col-md-4"><b>Date:</b> <?php echo date_db_format_to_display_format($row['enquiry_date']);?></div>
					<div class="col-md-4"><b>Quotation(s):</b> <a href="JavaScript:void(0)" class="blue-link <?php echo ($row['proposal']>0)?'quoted_view_popup':'';?>" data-customerid="<?php echo $row['customer_id']; ?>" data-quotedlids="<?php echo $row['id'];?>"><?php echo $row['proposal'];?></a></div>
					<div>&nbsp;</div>
					<div class="col-md-4"><b>Stage:</b> <?php echo $row['current_stage'];?></div>
					<div class="col-md-4"><b>Status:</b> <?php echo $row['current_status'];?></div>	
					<div class="col-md-4"><b>Source:</b> <?php echo $row['source_name'];?></div>	
					<div>&nbsp;</div>
					<div class="col-md-4"><b>Assign To:</b> <?php echo $row['user_name'];?></div>	
					<div class="col-md-8"><b>Buying Requirement:</b> <?php echo $row['buying_requirement'];?></div>					
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