<?php
$data_str='';
if(count($rows)){    
    foreach($rows AS $row){
        $name=($row['lost_reason'])?$row['lost_reason']:'';
        $lead_count=($row['lead_lost_count'])?$row['lead_lost_count']:0;        
        $data_str .="{name: '".$name."',y: ".$lead_count."},";
    }    
    $data_str=rtrim($data_str,",");
    $data_str='['.$data_str.']';
}
?>
<div id="rander_leads_lost_reasons_chart" class="">loading...</div>
<script type="text/javascript">
 	$(document).ready(function(){
        $('.same-height').matchHeight();
        Highcharts.chart('rander_leads_lost_reasons_chart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            series: [{
                data:<?php echo $data_str; ?>,
                name: 'Lead Count'
                }]
        });        
 	});
 </script>