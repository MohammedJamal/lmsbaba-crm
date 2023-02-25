<?php 
//print_r($rows); 

$is_per='';
$new_lead_count_per=$rows['new_lead_count_per'];
$today_followup_count_per=$rows['today_followup_count_per'];
$pending_followup_count_per=$rows['pending_followup_count_per'];
$upcoming_followup_count_per=$rows['upcoming_followup_count_per'];
$quoted_lead_count_per=$rows['quoted_lead_count_per'];
$auto_regretted_lead_count_per=$rows['auto_regretted_lead_count_per'];
//$foreign_lead_count_per=$rows['foreign_lead_count_per'];
//$domestic_lead_count_per=$rows['domestic_lead_count_per'];

$active_lead=$rows['active_lead_count'];
if($is_count_or_percentage=='P')
{	
	$new_lead=$rows['new_lead_count_per'];
	$today_followup=$rows['today_followup_count_per'];
	$pending_followup=$rows['pending_followup_count_per'];
	$upcoming_followup=$rows['upcoming_followup_count_per'];
	$quoted_lead=$rows['quoted_lead_count_per'];
	$auto_regretted_lead=$rows['auto_regretted_lead_count_per'];	
	//$foreign_lead=$rows['foreign_lead_count_per'];
	//$domestic_lead=$rows['domestic_lead_count_per'];
	$is_per='%';
}
else
{	
	$new_lead=$rows['new_lead_count'];
	$today_followup=$rows['today_followup_count'];
	$pending_followup=$rows['pending_followup_count'];
	$upcoming_followup=$rows['upcoming_followup_count'];
	$quoted_lead=$rows['quoted_lead_count'];
	$auto_regretted_lead=$rows['auto_regretted_lead_count'];	
	//$foreign_lead=$rows['foreign_lead_count'];
	//$domestic_lead=$rows['domestic_lead_count'];
}


?>
<?php  ?>
<ul class="default-ul seven-ul">
	<li>
	 <div class="chart-holder">
		<svg title="Active Lead" class="radial-progress" data-percentage="100" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="active_lead"><?php echo $active_lead; ?></a></text>
		</svg>
	 </div>
	 Active Lead
	</li>
	<li>
	 <div class="chart-holder">
		<svg title="New Lead" class="radial-progress" data-percentage="<?php echo $new_lead_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="new_lead"><?php echo $new_lead.$is_per; ?></a></text>
		</svg>
	 </div>
	 New Lead
	</li>
	<li>
	 <div class="chart-holder">
		<svg title="Today's Follow-up" class="radial-progress" data-percentage="<?php echo $today_followup_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="todays_followup"><?php echo $today_followup.$is_per; ?></a></text>
		</svg>
	 </div>
	 Today's Follow-up
	</li>
	
	<li>
	 <div class="chart-holder">
		<svg title="Pending Follow-up" class="radial-progress" data-percentage="<?php echo $pending_followup_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="pending_followup"><?php echo $pending_followup.$is_per; ?></a></text>
		</svg>
	 </div>
	 Pending Follow-up
	</li>
	<li>
	 <div class="chart-holder">
		<svg title="Upcoming Follow-up" class="radial-progress" data-percentage="<?php echo $upcoming_followup_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="upcoming_followup"><?php echo $upcoming_followup.$is_per; ?></a></text>
		</svg>
	 </div>
	 Upcoming Follow-up
	</li>
	<li>
	 <div class="chart-holder">
		<svg title="Quoted Leads" class="radial-progress" data-percentage="<?php echo $quoted_lead_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="quoted_leads"><?php echo $quoted_lead.$is_per; ?></a></text>
		</svg>
	 </div>
	 Quoted Leads
	</li>
	<li>
	 <div class="chart-holder">
		<svg title="Auto Regretted" class="radial-progress" data-percentage="<?php echo $auto_regretted_lead_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="auto_regretted"><?php echo $auto_regretted_lead.$is_per; ?></a></text>
		</svg>
	 </div>
	 Auto Regretted
  </li>
  
  <?php /* ?>
  <li>
	 <div class="chart-holder">
		<svg title="Foreign" class="radial-progress" data-percentage="<?php echo $foreign_lead_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="fl"><?php echo $foreign_lead.$is_per; ?></a></text>
		</svg>
	 </div>
	 Foreign
  </li>
  
  <li>
	 <div class="chart-holder">
		<svg title="Domestic" class="radial-progress" data-percentage="<?php echo $domestic_lead_count_per; ?>" viewBox="0 0 80 80">
		   <defs>
			  <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
				 <stop offset="0%" stop-color="#00bc9b" />
				 <stop offset="100%" stop-color="#5eaefd" />
			  </linearGradient>
		   </defs>
		   <circle class="incomplete" cx="40" cy="40" r="35"></circle>
		   <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 220;" stroke="url(#gradient)"></circle>
		   <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><a href="JavaScript:void(0);" class="go_list" data-filter="dl"><?php echo $domestic_lead.$is_per; ?></a></text>
		</svg>
	 </div>
	 Domestic
  </li>
  <?php */ ?>
</ul>
<script>
$(document).ready(function(){
	$('svg.radial-progress').tooltip();      
      
    var itemscolor = ['#dc3545', '#28a745', '#ffc107', '#17a2b8', '#007bff', '#6c757d', '#17a2b8'];
	$('.chart').each(function( index ) {
		$(this).easyPieChart({
			easing: 'easeOutBounce',
			trackColor: '#DDD',
			barColor: itemscolor[index],
			lineWidth: 7,
			lineCap: 'round',
			onStep: function(from, to, percent) {
			   //data-type
			   var datatype = $(this.el).attr('data-type');
			   $(this.el).find('.percent').text(Math.round(percent)+datatype);
			}
		});
	});
	//////radial-progress
	function showHolding(){
		$('svg.radial-progress').each(function( index ) {
			$(this).find($('circle.complete')).removeAttr( 'style' );
			$(this).find('.incomplete').css({'stroke': itemscolor[index]});
			$(this).find('.complete').css({'stroke': itemscolor[index]});
		}); 
		setTimeout(function(){ showProcessRadial() }, 700);
	}
	showProcessRadial = function(){
		//console.log('showProcessRadial')
		$('svg.radial-progress').each(function( index, value ) { 
			percent = $(value).data('percentage');
			radius = $(this).find($('circle.complete')).attr('r');
			circumference = 2 * Math.PI * radius;
			strokeDashOffset = circumference - ((percent * circumference) / 100);
			console.log('strokeDashOffset: '+strokeDashOffset)
			$(this).find($('circle.complete')).animate({'stroke-dashoffset': strokeDashOffset}, 2000);
		});
	}
    showHolding();
	resetProcessRadial = function(){
		$('svg.radial-progress').each(function( index ) {
			$(this).find($('circle.complete')).animate({'stroke-dashoffset': 219.91148575129}, 1000, function() {
			   // Animation complete.		   
			});
		});
		setTimeout(function(){ showHolding() }, 1000);
	}
	$('#dash_check').click(function() {
		resetProcessRadial();	 
		// if ($(this).is(':checked')) {
		//    alert("Are you sure?");		
		// }else{
		//    alert("not");
		// }
	});
	///	

	
});
</script>
<?php  ?>