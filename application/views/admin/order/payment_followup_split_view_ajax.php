<?php if(count($rows)){ ?>
    <tr class="no-hover">
      <td align="left"></td>
      <td align="left"></td>
      <td align="left"><b>Payment Due Date</b></td>
      <td align="left"><b>Amount Due</b></td>
      <td align="left"><b>Payment Received</b></td>
      <td align="left"><b>Balance Payment Due</b></td>
      <td align="left"><b>Narration</b></td>
      <td align="left"></td>
    </tr>
  <?php foreach($rows AS $row){ ?>
    <tr class="no-hover">
      <td align="left"></td>
      <td align="left"></td>
      <td align="left"><?php echo ($row->payment_date)?date_db_format_to_display_format($row->payment_date):'N/A'; ?></td>
      <td align="left"><?php 
        echo $row->currency_type.' '.number_format($row->amount,2);
        ?></td>
      <td align="left"><?php 
        echo $row->currency_type.' '.number_format($row->payment_received,2);
        ?></td>
      <td align="left"><?php 
        echo $row->currency_type.' '.number_format($row->balance_payment,2);
        ?></td>
      <td align="left"><?php echo ($row->narration)?$row->narration:'-'; ?></td>
      <td></td>
    </tr>
  <?php } ?>
<?php }else{} ?>