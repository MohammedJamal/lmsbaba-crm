<?php //print_r($rows); ?>
<div id="chart_lvo" class="pa-15 h-312">02 loading...</div>
<div class="hide">
  <table id="table_lvo">
    <thead>
        <tr>
            <th></th>
            <th>Leads</th>
            <th>Orders</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($rows)){ ?>
          <?php foreach($rows AS $row){ ?>
            <tr>
               <th><?php echo $row->date_str; ?></th>
               <td><?php echo $row->total_lead_count; ?></td> 
               <td><?php echo $row->total_deal_won_lead_count; ?></td>  
            </tr>
          <?php } ?>
        <?php } ?>            
    </tbody>
  </table>
</div>
<script type="text/javascript">
 	$(document).ready(function(){
 		  Highcharts.chart('chart_lvo', {
             data: {
                 table: 'table_lvo'
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
                 text: 'Leads Vs. Orders'
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
