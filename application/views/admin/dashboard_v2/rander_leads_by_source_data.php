<?php
$data_str='';
if(count($rows)){    
    foreach($rows AS $row){
        $source_name=($row['source_alias_name'])?$row['source_alias_name']:$row['source_name'];
        $lead_count=($row['lead_count'])?$row['lead_count']:0;        
        $data_str .="{name: '".$source_name."',y: ".$lead_count."},";
    }    
    $data_str=rtrim($data_str,",");
    $data_str='['.$data_str.']';
}
?>
<div id="rander_leads_source_chart" class="">loading...</div>
 <script type="text/javascript">
 	$(document).ready(function(){
        $('.same-height').matchHeight();
        Highcharts.chart('rander_leads_source_chart', {
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