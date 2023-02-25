
<div class="responsive-table">
    <table class="table table-bordered new-table-bordered with-white-space" id="get-daily-sales-report">
        <thead>
        
            <tr>
                <th></th>
                <th width="120"><div>Leads</div></th>
                <th width="120"><div>Active Leads</div></th>
                <th width="120"><div>Quoted</div></th>
                <th width="120"><div>Lead Lost</div></th>
                <th width="120"><div>Lead Won</div></th>
                <th width="120"><div>Revenue (INR)</div></th>
                <!-- <th width="120"><div>AOV (INR)</div></th> -->
                <th width="120"><div>Revenue (USD)</div></th>
                <!-- <th width="120"><div>AOV (USD)</div></th> -->
                <th width="120"><div>Conversion</div></th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if(count($data))
        {
            $total_po=0;
            foreach($data AS $row){
                $total_po=$row['total_orders']+$total_po;
            }
            foreach($data AS $row){ ?>    
            <tr>
                <td>                      
                    <span class="font-15-16"><?php echo $row['source_name'];?></span>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="lead_by_source_report" data-filter1="new_lead" data-filter2="<?php echo $row['source_id'];?>"><?php echo $row['total_lead'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="lead_by_source_report" data-filter1="active_lead" data-filter2="<?php echo $row['source_id'];?>"><?php echo $row['is_active'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="lead_by_source_report" data-filter1="quoted" data-filter2="<?php echo $row['source_id'];?>"><?php echo $row['is_quoted'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="lead_by_source_report" data-filter1="deal_lost" data-filter2="<?php echo $row['source_id'];?>"><?php echo $row['is_lost'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="lead_by_source_report" data-filter1="deal_won" data-filter2="<?php echo $row['source_id'];?>"><?php echo $row['is_won'];?></a>
                </td>
                <td class="text-center" style="color:#0275d8">
                    <?php echo number_format($row['INR_revenue']);?>
                </td>
                <!-- <td class="text-center" style="color:#0275d8">
                    <?php echo ($row['INR_revenue']>0)?ROUND($row['INR_revenue']/$row['is_won']):0; ?>
                </td> -->
                <td class="text-center" style="color:#0275d8">
                    <?php echo number_format($row['USD_revenue']);?>
                </td>
                <!-- <td class="text-center" style="color:#0275d8">
                    <?php echo ($row['USD_revenue']>0)?ROUND($row['USD_revenue']/$row['is_won']):0; ?>
                </td> -->
                <td class="text-center" style="color:#0275d8">
                    <?php echo number_format(($row['is_won']/$row['total_lead']*100));?>%
                </td>
            </tr>
        <?php 
            }
        }
        else
        {
            ?>
            <tr><td colspan="9">No Record Found</td></tr>
            <?php
        } ?>

        </tbody>
    </table>
</div>