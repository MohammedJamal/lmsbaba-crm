<table class="table ">
    <thead>
        <tr>
            <th>Service</th>
            <th>No of User(s)</th>
            <th>Amount</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Expiry Date</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($rows)){ $i=0;$sid='';?>
            <?php foreach($rows AS $row){
                
                $tbl_tr_bg='';
                $tbl_td_color='';
                if($row['is_active']=='N'){
                    $tbl_tr_bg='table-danger';
                    $tbl_td_color='text-white';
                } else {
                    $tbl_tr_bg='';
                    $tbl_td_color='';
                }
                
                ?>
                <?php 
                    if($row['service_id']!=$sid){ $i=0; }
                if($i==0){
                ?>
                <tr>
                    <td class="text-left table-info" colspan="7">
                        <h6 style="font-size: 16px;font-weight: normal;color:#fff"><?php echo $row['service_name']; ?> ( Total Amount: <?php echo number_format($row['total_price']); ?>/- )</h6>
                        
                    </td>
                </tr>
                <?php } ?>
                <tr class="<?php echo $tbl_tr_bg;?>">
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo $row['display_name']; ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo $row['no_of_user']; ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo $row['price']; ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo date_db_format_to_display_format($row['start_date']); ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo date_db_format_to_display_format($row['end_date']); ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo date_db_format_to_display_format($row['expiry_date']); ?></td>
                    <td class="text-center <?php echo $tbl_td_color;?>">
                        <?php
                        $renewal_start_date=date('Y-m-d', strtotime('+1 day', strtotime($row['end_date'])) );
                        $renewal_end_date=date('Y-m-d', strtotime('+1 year', strtotime($row['end_date'])) );
                        $renewal_expiry_date=date('Y-m-d', strtotime('+1 year', strtotime($row['expiry_date'])) );
                        ?>
                        <a href="JavaScript:void(0);" title="Edit" class="edit_service" data-service_order_detail_id="<?php echo $row['id']; ?>" data-client_id="<?php echo $row['client_id']; ?>" data-service_id="<?php echo $row['service_id']; ?>" data-display_name="<?php echo $row['display_name']; ?>" data-no_of_user="<?php echo $row['no_of_user']; ?>" data-price="<?php echo $row['price']; ?>" data-start_date="<?php echo date_db_format_to_display_format($row['start_date']); ?>" data-end_date="<?php echo date_db_format_to_display_format($row['end_date']); ?>" data-expiry_date="<?php echo date_db_format_to_display_format($row['expiry_date']); ?>"  data-service_status="<?php echo $row['is_active']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp; | &nbsp;
                        <a href="JavaScript:void(0);" title="Log" class="get_service_detail_log" data-service_order_detail_id="<?php echo $row['id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></a>&nbsp; | &nbsp;
                        <a href="JavaScript:void(0);" title="Renew" class="renew_service"  data-service_order_detail_id="<?php echo $row['id']; ?>" data-client_id="<?php echo $row['client_id']; ?>" data-service_id="<?php echo $row['service_id']; ?>" data-display_name="<?php echo $row['display_name']; ?>" data-no_of_user="<?php echo $row['no_of_user']; ?>" data-price="<?php echo $row['price']; ?>" data-start_date="<?php echo date_db_format_to_display_format($row['start_date']); ?>" data-end_date="<?php echo date_db_format_to_display_format($row['end_date']); ?>" data-expiry_date="<?php echo date_db_format_to_display_format($row['expiry_date']); ?>"  data-renewal_start_date="<?php echo date_db_format_to_display_format($renewal_start_date); ?>" data-renewal_end_date="<?php echo date_db_format_to_display_format($renewal_end_date); ?>" data-renewal_expiry_date="<?php echo date_db_format_to_display_format($renewal_expiry_date); ?>"  data-service_status="<?php echo $row['is_active']; ?>"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                        
                    </td>
                </tr>
            <?php $sid=$row['service_id'];$i++;} ?>
            <?php }else{ ?>
                <tr>
                    <td class="text-left" colspan="7">No Service Found!</td>
                </tr>
            <?php } ?>
    </tbody>
</table>