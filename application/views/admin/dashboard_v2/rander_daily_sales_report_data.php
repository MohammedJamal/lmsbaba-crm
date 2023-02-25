
<div class="responsive-table">
    <table class="table table-bordered new-table-bordered with-white-space" id="get-daily-sales-report">
        <thead>
            <tr>
                <th></th>
                <th width="120"><div>new Lead</div></th>
                <th width="120"><div>calls</div></th>
                <th width="120"><div>meetings</div></th>
                <th width="120"><div>Updated</div></th>
                <th width="120"><div>quoted</div></th>
                <th width="120"><div>Deal won</div></th>
                <th width="120"><div>Deal Lost</div></th>
                <th width="120"><div>Revenue</div></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($data AS $user){ ?>    
            <tr>
                <td>                      
                    <span class="font-15-16"><?php echo date_db_format_to_display_format($user['daily_date']);?></span>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="daily_sales_report" data-filter1="new_lead" data-filter2="<?php echo $user['daily_date']; ?>"><?php echo $user['total_new_lead'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="daily_sales_report" data-filter1="calls" data-filter2="<?php echo $user['daily_date']; ?>"><?php echo $user['total_call_log'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link meeting_report" data-leadid="" data-date="<?php echo $user['daily_date']; ?>" data-date2="<?php echo $user['daily_date']; ?>" data-user_id=""><?php echo $user['total_meeting'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="daily_sales_report" data-filter1="updated" data-filter2="<?php echo $user['daily_date']; ?>"><?php echo $user['total_updated'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="daily_sales_report" data-filter1="quoted" data-filter2="<?php echo $user['daily_date']; ?>"><?php echo $user['total_quoted'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="daily_sales_report" data-filter1="deal_won" data-filter2="<?php echo $user['daily_date']; ?>"><?php echo $user['total_dealwon'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="daily_sales_report" data-filter1="deal_lost" data-filter2="<?php echo $user['daily_date']; ?>"><?php echo $user['total_lost'];?></a>
                </td>
                <td class="text-center">
                    <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="daily_sales_report" data-filter1="revenue" data-filter2="<?php echo $user['daily_date']; ?>"><?php echo $currency_info['default_currency_code']; ?> <?php echo number_format($user['total_revenue']);?></a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>
<!-- <div class="footer-table">
    <div id="v2_uar_page_record_count_info" class="col-md-6 text-left"></div>
    <div class="footer-right">
        
        <a href="javascript: void(0);" id="v2_uar_download" class="downlDaily Sales Reportoad-icon mr-15"><img src="<?php echo assets_url() ?>images/download-icon.png"></a>
        <div id="v2_uar_page"></div>
        <a href="#" class="more-dot">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>
</div> -->