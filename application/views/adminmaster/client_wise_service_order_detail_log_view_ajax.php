<table class="table ">
    <thead>
        <tr>
            <th>Service</th>
            <th>No of User(s)</th>
            <th>Amount</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Expiry Date</th>
            <th>Created On</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($rows)){ $i=0;$sid='';?>
            <?php foreach($rows AS $row){ ?>                
                <tr>
                    <td class="text-left"><?php echo $row['display_name']; ?></td>
                    <td class="text-left"><?php echo $row['no_of_user']; ?></td>
                    <td class="text-left"><?php echo $row['price']; ?></td>
                    <td class="text-left"><?php echo date_db_format_to_display_format($row['start_date']); ?></td>
                    <td class="text-left"><?php echo date_db_format_to_display_format($row['end_date']); ?></td>
                    <td class="text-left"><?php echo date_db_format_to_display_format($row['expiry_date']); ?></td>
                    <td class="text-left"><?php echo date_db_format_to_display_format($row['created_at']); ?></td>
                </tr>
            <?php } ?>
            <?php }else{ ?>
                <tr>
                    <td class="text-left" colspan="6">No Service Found!</td>
                </tr>
            <?php } ?>
    </tbody>
</table>