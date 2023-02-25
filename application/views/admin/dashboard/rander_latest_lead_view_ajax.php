<ul class="border-ul">
	<?php if(count($rows)){ ?>
		<?php foreach($rows as $row){ ?>
			<li>
				<h4><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?search_by_id=<?php echo $row->id;?>"><?php echo $row->title; ?></a></h4>
				Date: <?php echo date_db_format_to_display_format($row->create_date); ?>  
			</li>
		<?php } ?>
		<?php }else{ ?>
			<li>No Lead available.</li>
	<?php } ?>	
</ul>