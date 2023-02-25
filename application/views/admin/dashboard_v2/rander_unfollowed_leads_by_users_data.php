<?php
$data_str='';
if(count($rows)){    
    foreach($rows AS $row){
        $name=($row['assigned_user_name'])?$row['assigned_user_name']:'';
        $lead_count=($row['lead_count'])?$row['lead_count']:0;        
        $data_str .="{name: '".$name."',y: ".$lead_count."},";
    }    
    $data_str=rtrim($data_str,",");
    $data_str='['.$data_str.']';
}
// echo $rows;
?>
<div id="rander_leads_unfollowed_chart" class="">loading...</div>
<script type="text/javascript">
 	$(document).ready(function(){
        $('.same-height').matchHeight();
        Highcharts.chart('rander_leads_unfollowed_chart', {
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