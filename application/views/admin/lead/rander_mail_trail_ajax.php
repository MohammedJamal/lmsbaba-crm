<?php if(count($rows)){ ?>
	<?php foreach($rows AS $row){ ?>
	<div class="loop-mail-trail">
		<div class="sel-mail-trail">
		</div>
		<div class="txt-mail-trail">
			<div style="width: 100%; height: auto; display: block;box-sizing: border-box;border-top: #c3c3c3 1px solid; padding: 10px 0 0 20px; margin: 10px 0;">
			    <div style="width: 100%; height: auto; display: block;box-sizing: border-box;border-left: #c3c3c3 1px solid; padding: 0 0 0 6px; font-size: 14px; color: #000;">
			        <p style="margin: 0 0 6px 0;">On <?php echo datetime_db_format_to_display_format($row->create_date); ?> <<?php echo $row->user_email; ?>> <?php echo $row->user_name; ?> wrote:</p> 
			        <p style="margin: 0 0 6px 0;"><?php echo $row->comment; ?></p>
			    </div>
			</div>
		</div>
	</div>
	<?php
	}
	?>
<?php
}
?>