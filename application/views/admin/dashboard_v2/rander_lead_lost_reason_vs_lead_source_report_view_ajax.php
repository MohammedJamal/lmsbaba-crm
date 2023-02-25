<div class="d-flex align-items-center" >
	<div id="chart_lost_reason" class="h-272 w-312">loading...</div>
	<div class="pending-followups-table-holder pt-30">
		
		<div class="tholder dash-style round">
		  <table class="table clock-table white-style scroll-table">
		    <thead>
		      <tr>
		        <th width="70%">Reasons</th>
		        <th width="30%">All Lead</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<?php 
          $pic_data='';
          if(count($rows)){ 
            ?>
				   <?php foreach($rows AS $row){ 
            $pic_data .="['".$row->reason_name."',".$row->total_lead_count."],";
            ?>      
				      <tr>
				         <td width="70%"><?php echo $row->reason_name; ?></td>
				         <td width="30%"><?php echo $row->total_lead_count; ?></td>
				      </tr>
				   <?php } ?>
				<?php $pic_data=rtrim($pic_data,",");}else{ ?>
				   <tr>
				      <td colspan="2">No Lead Lost Reason Vs. Lead Source available.</td>
				   </tr>
				<?php } ?>
		    </tbody>
		  </table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
    $('.same-height').matchHeight();
		Highcharts.chart('chart_lost_reason', {
          chart: {
              type: 'pie',
              options3d: {
                  enabled: true,
                  alpha: 45,
                  beta: 0
              }
          },
          title: {
              text: 'Pending Followups'
          },
          accessibility: {
              point: {
                  valueSuffix: '%'
              }
          },
          tooltip: {
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
          },
          plotOptions: {
              pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  depth: 35,
                  dataLabels: {
                    padding: 0,
                    distance: -25,
                    //softConnector: true,
                    enabled: false,
                    format: '{point.name}',
                    style: {
                        "fontSize": "10px",
                        "color": '#000'
                    }
                 }
              }
          },
          series: [{
              type: 'pie',
              name: 'Lead Lost Reasons',
              data: [<?php echo $pic_data; ?>]
          }]
      });
	});
</script>
