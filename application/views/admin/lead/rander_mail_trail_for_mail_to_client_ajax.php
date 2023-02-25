<?php //print_r($rows); ?>
<div class="row">	
	<div class="col-md-12">		
		<div class="select-all-holder">
			<div class="dropdown_new">
			  <a href="#" class="all-secondary">
			    <label class="check-box-sec">
				  <input type="checkbox" value="all" name="user_all" class="user_all_checkbox" checked="true" >
				  <span class="checkmark"></span>
			   </label>
			  </a>
			  <div class="dropdown">
				  <button class="btn-all dropdown-toggle" type="button" id="dropdownMenuUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All</button>
				  <div class="dropdown-menu left" aria-labelledby="dropdownMenuUser">
				    <a class="dropdown-item cAll" href="#">All</a>
				    <a class="dropdown-item uAll" href="#">None</a>				    
				  </div>
				</div>
			</div>
		</div>
		<?php if(count($rows)){ ?>
			<?php foreach($rows AS $row){ ?>
			<div class="loop-mail-trail">
				<div class="sel-mail-trail">
					<label class="check-box-sec">
				        <input type="checkbox" class="trail_check" name="lead_comment[]" checked="true" value="<?php echo $row->id; ?>">
				        <span class="checkmark"></span>
				    </label>
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
			<button type="button" class="btn btn-primary btn-round-shadow submit-padding pull-right" id="trail_mail_comment_selected_confirm">Add </button>
		<?php
		}
		?>

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".user_all_checkbox").change(function () {        
	        $('.dropdown_new .check-box-sec').removeClass('same-checked');
	        
	        if($(this).prop("checked") == true){
	            $('#dropdownMenuUser').html('All');
	        }else{
	            $('#dropdownMenuUser').html('None');
	        }
	        $('input.trail_check').prop('checked', $(this).prop("checked"));
	    });
	    $("body").on("click",".cAll",function(e){
	        e.preventDefault();
	        $('#dropdownMenuUser').html('All');
	        $('input.trail_check, .user_all_checkbox').prop('checked',true);
	    });
	    $("body").on("click",".uAll",function(e){
	        e.preventDefault();
	        $('#dropdownMenuUser').html('None');
	        $('.dropdown_new .check-box-sec').removeClass('same-checked');
	        $('input.trail_check, .user_all_checkbox').prop('checked',false);
	    });
	    $("input.trail_check").change(function () {
	        if ($('input.trail_check').not(':checked').length == 0) {
	            $('#dropdownMenuUser').html('None');
	            $('.user_all_checkbox').prop('checked',true);
	            $('.dropdown_new .check-box-sec').removeClass('same-checked');
	        } else {
	            $('#dropdownMenuUser').html('All');
	            $('.user_all_checkbox').prop('checked',false);
	            $('.dropdown_new .check-box-sec').addClass('same-checked');
	        }
	    });
	})
</script>
