<div class="row" >
	<div class="col-md-12">
			<?php 
			foreach($rows as $row){ ?>
			<div class="">
				<input type="radio" id="regret_reason" name="regret_reason" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['name']; ?>" class="" >
				<?php echo $row['name']; ?> 				
			</div>
			<?php } ?>
	</div>
	<div>&nbsp;</div>
	<div class="col-md-12" style="display: none;">
		<div class="form-group">
			<label><b>Mail Subject</b></label>
			<input type="text" name="update_lead_regret_this_lead_mail_subject" id="update_lead_regret_this_lead_mail_subject" class="form-control" value="<?php echo $mail_subject; ?>">
			<div class="error_label text-danger" id="update_lead_regret_this_lead_mail_subject_error"></div>
		</div>
	</div>	
</div>
<script type="text/javascript">
	$("body").on("click","#regret_reason",function(e){
        var reason=$(this).val();
        var reason_id=$(this).attr('data-id');
        $("#regret_reason_text").html(reason);
        $("#lead_regret_reason").val(reason);
        $("#lead_regret_reason_id").val(reason_id);
    });
</script>