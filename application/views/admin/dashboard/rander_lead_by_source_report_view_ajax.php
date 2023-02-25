<?php /* ?>
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
<?php */ ?>
<div id="chart_lbs" class="pa-15 h-312">loading...</div>
 <div class="hide">
    <table id="table_lbs">
      <thead>
          <tr>
              <th></th>
              <th>Source</th>
          </tr>
      </thead>
      <tbody>
        <?php if(count($rows)){ ?>
        <?php foreach($rows AS $row){ ?>
        <tr>
            <th><?php echo $row->source_name; ?></th>
            <td><?php echo $row->total_lead_count; ?></td>
        </tr>
        <?php } ?>
        <?php } ?> 
      </tbody>
  </table>
 </div>
 <script type="text/javascript">
 	$(document).ready(function(){
     $('.same-height').matchHeight();
 		 Highcharts.chart('chart_lbs', {
             data: {
                 table: 'table_lbs'
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
                 text: 'Leads by Source'
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
                         this.point.y + ' <br/>' + this.point.name.toLowerCase();
                 }
             }
         });
 	});
 </script>