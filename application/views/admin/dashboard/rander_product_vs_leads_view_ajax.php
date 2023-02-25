<?php //print_r($rows); ?>
<div class="tholder dash-style round new_lead_scroll" style="height: 350px">
   <table class="table clock-table">
       <thead>
         <tr>
           <th>Product Name</th>
           <th>All Leads</th>
           <th>Quoted</th>
           <th>Lost</th>
           <th>Won</th>
         </tr>
       </thead>
       <tbody>
        <?php if(count($rows)){ ?>
          <?php foreach($rows AS $row){ 
              $total_lost=($row->total_deal_lost_lead_count+$row->total_regretted_lead_count+$row->total_auto_regretted_lead_count+$row->total_auto_deal_lost_lead_count);
            ?>
            <tr>
             <td><?php echo $row->product_name; ?></td>
             <td><a href="JavaScript:void(0)"><?php echo $row->total_lead_count; ?></a></td>
             <td><a href="JavaScript:void(0)"><?php echo $row->total_quoted_lead_count; ?></a></td>
             <td><a href="JavaScript:void(0)"><?php echo $total_lost; ?></a></td>
             <td><a href="JavaScript:void(0)"><?php echo $row->total_deal_won_lead_count; ?></a></td>
           </tr>
          <?php } ?>
        <?php }else{ ?>
          <tr>
             <td colspan="5">No record found!</td>
           </tr>
        <?php } ?>
       </tbody>
     </table>
</div>
<script type="text/javascript">
   $(document).ready(function(){  
      ////
        //same-height
        $('.new_lead_scroll').mCustomScrollbar({
         theme: "minimal-dark",
         alwaysShowScrollbar: 1
        });
         /////////////////////
      });
</script>