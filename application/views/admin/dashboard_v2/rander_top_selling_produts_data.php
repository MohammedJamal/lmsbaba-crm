
    <div class="responsive-table same-height-top">
        <table class="table vertical-align-middle">
            <thead>
            <tr class="white-header">
                <th scope="col" colspan="2" class="text-left"></th>                
                <th scope="col" colspan="2" class="text-center top-round">Revenue</th>
            </tr>
            <tr>
                <th width="50%"></th>                
                <th widt="20%">No. Of PO</th>                
                <th widt="15%">INR</th>
                <th widt="15%">USD</th>
                <!-- <th widt="15%">Avg. Revenue</th> -->
            </tr>
            </thead>
            <tbody>
            <?php if(count($rows)){ ?>
                <?php foreach($rows AS $row){ ?>
                <tr>
                    <th>
                        <span class="table-txt"><?php echo $row['name']; ?></span>
                        <!-- <div class="user-details-block">
                            
                            <div class="user-details-infos">
                                <?php //echo $row['name']; ?><br>
                                <small>(New Buyer)</small>
                            </div>
                        </div> -->
                    </th>                    
                    <td style="color:#0275d8"><?php echo $row['total_po']; ?></td>
                    <td style="color:#0275d8"><?php echo number_format($row['total_revenue_inr']); ?></td>
                    <td style="color:#0275d8"><?php echo number_format($row['total_revenue_usd']); ?></td>
                    <!-- <td style="color:#0275d8"><?php //echo round(($row['total_revenue']/$row['total_po'])); ?></td> -->
                </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr><td colspan="4">No record found!</td></tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
    <div class="footer-table">
        <div class="footer-right">
            <a href="javascript: void(0);" id="tsp_download" class="download-icon mr-15"><img src="<?php echo assets_url() ?>images/download-icon.png"></a>
            <!-- <a href="#" class="more-dot">
                <span></span>
                <span></span>
                <span></span>
            </a> -->
        </div>
    </div>