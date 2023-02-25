<ul class="list-group">
<?php if($po_register_info->po_tds_certificate){ ?>	
	<li class="list-group-item d-flex justify-content-between align-items-center"><a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/download_po/<?php echo base64_encode($po_register_info->file_path.$po_register_info->po_tds_certificate.'#'.'po_tds_certificate');?>" title="click to download"><i class="fa fa-cloud-download" aria-hidden="true"></i> <?php echo $po_register_info->po_tds_certificate; ?></a> <span class=""><a href="JavaScript:void(0);" class="del_po_tds_certificate" data-lowp="<?php echo $lowp; ?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a></span></li>	
<?php }else{ ?>
	<li class="list-group-item d-flex justify-content-between align-items-center">No certificate found!</li>
<?php } ?>
</ul>