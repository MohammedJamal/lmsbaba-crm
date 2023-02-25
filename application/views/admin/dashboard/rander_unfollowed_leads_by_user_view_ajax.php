<?php //print_r($rows); ?>
<div class="d-flex align-items-center" >
<div id="chart_rvs" class="h-272 w-312">loading...</div>
<div class="pending-followups-table-holder">
<div class="full-width">
  <div class="cTitle">Unfollowed Leads By User</div>
</div>
<div class="tholder dash-style round">
  <table class="table clock-table white-style scroll-table max-h-100" id="">
    <thead>
      <tr>
        <th width="40%">User</th>
        <th width="20%">All</th>
        <th width="20%">Prospect</th>
        <th width="20%">New</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $pic_data='';
      if(count($rows)){ ?>
        <?php foreach($rows AS $row){ 
          $pic_data .="['".$row->assigned_user_name."',".($row->prospect_lead_not_followed_count+$row->new_lead_not_followed_count)."],";
          ?>
          <tr>
            <td width="40%"><?php echo $row->assigned_user_name; ?></td>
            <td width="20%"><a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?u=<?php echo $row->assigned_user_id; ?>&pf=Y&pff="><?php echo ($row->prospect_lead_not_followed_count+$row->new_lead_not_followed_count); ?></a></td>
            <td width="20%"><a href="JavaScript:void(0)"><?php echo $row->prospect_lead_not_followed_count; ?></a></td>
            <td width="20%"><a href="JavaScript:void(0)"><?php echo $row->new_lead_not_followed_count; ?></a></td>           
           </tr>
        <?php } ?>
      <?php $pic_data=rtrim($pic_data,",");}else{ ?>
          <tr>
            <td colspan="4">No Lead available.</td>
          </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    Highcharts.chart('chart_rvs', {
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
              name: 'Unfollowed Leads',
              data: [<?php echo $pic_data; ?>]
          }]
      });
  });
</script>