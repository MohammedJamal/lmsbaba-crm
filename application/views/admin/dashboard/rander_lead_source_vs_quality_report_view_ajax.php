<?php /*if(count($rows)){ ?>
	<?php foreach($rows AS $row){ ?>
		<?php
		$prospects=($row->total_new_lead_count-($row->total_regretted_lead_count));
		$non_prospects=($row->total_regretted_lead_count);
		?>
		<tr>
			<td><?php echo $row->source_name; ?></td>
			<td><?php echo $prospects; ?></td>
			<td><?php echo $non_prospects; ?></td>
		</tr>
	<?php } ?>
<?php }else{ ?>
	<tr><td colspan="3">No Lead Source Vs. Quality available.</td></tr>
<?php }*/ ?>
<div id="chart_svq" class="pa-15 max-height-280">Loading...</div>
  <div class="hide">
     <table id="table_svq">
       <thead>
           <tr>
               <th></th>
               <th>Prospects</th>
               <th>Non-Prospects</th>
           </tr>
       </thead>
       <tbody>
       		<?php if(count($rows)){ ?>
			<?php foreach($rows AS $row){ ?>
			<?php
			$prospects=($row->total_new_lead_count-($row->total_regretted_lead_count));
			$non_prospects=($row->total_regretted_lead_count);
			?>
			<tr>
			  <th><?php echo $row->source_name; ?></th>
			  <td><?php echo $prospects; ?></td> 
			  <td><?php echo $non_prospects; ?></td>
			</tr>
			<?php } ?>
			<?php } ?>          
       </tbody>
   </table>
  </div>

 <script type="text/javascript">
 	$(document).ready(function(){
 		 Highcharts.chart('chart_svq', {
             data: {
                 table: 'table_svq'
             },
             chart: {
                 type: 'column',
                 options3d: {
                     enabled: true,
                     alpha: 2,
                     beta: 4,
                     depth: 50,
                     viewDistance: 25
                 }
             },
             title: {
                 text: 'Leads Quality'
             },
             yAxis: {
                 allowDecimals: false,
                 title: {
                     text: ''
                 }
             },
             tooltip: {
                 formatter: function () {
                     return '<b>' + this.series.name + '</b>: ' +
                         this.point.y + ' <br/>' + this.point.name;
                 }
             }
         });
 	});
 </script>