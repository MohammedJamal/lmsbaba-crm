<div class="responsive-table same-height-top">
    <table class="table vertical-align-middle">
        <thead>
            <tr>
                <th width="40%"></th>
                <th width="25%">Po Date</th>
                <th width="20%">Assigned To</th>
                <th width="15%">Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($rows)){ ?>
                <?php foreach($rows AS $row){ ?>
                <tr>
                    <th>
                        <span class="table-txt"><?php echo ($row['cust_company_name'])?$row['cust_company_name']:'N/A'; ?></span>
                        <!-- <div class="user-details-block">
                            
                            <div class="user-details-infos">
                                <?php //echo ($row['cust_company_name'])?$row['cust_company_name']:'N/A'; ?><br>
                                <small>(New Buyer)</small>
                            </div>
                        </div> -->
                    </th>
                    <td style="color:#0275d8"><?php echo date_db_format_to_display_format($row['po_date']); ?></td>
                    <td style="color:#0275d8"><?php echo $row['assigned_user']; ?></td>
                    <td style="color:#0275d8"><?php echo $currency_info['default_currency_code']; ?> <?php echo number_format($row['deal_value_as_per_purchase_order']); ?></td>
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
        <a href="javascript: void(0);" id="latest_sales_orders_download_csv" class="download-icon mr-15"><img src="<?php echo assets_url() ?>images/download-icon.png"></a>
        <!-- <a href="#" class="more-dot">
            <span></span>
            <span></span>
            <span></span>
        </a> -->
    </div>
</div>