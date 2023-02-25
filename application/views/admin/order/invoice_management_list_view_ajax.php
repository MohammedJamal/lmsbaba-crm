<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){ ?>
    <tr scope="row" class="<?php echo ($row->is_cancel=='Y')?'canceled_po_tr ':''; ?>">
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->lead_opportunity_wise_po_id)?$row->lead_opportunity_wise_po_id:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->invoice_no)?$row->invoice_no:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->invoice_date)?date_db_format_to_display_format($row->invoice_date):'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->cus_company_name)?$row->cus_company_name:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->total_payble_amount)?$row->currency_type.' '.number_format($row->total_payble_amount,2):'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>">
        <?php
        $payment_type='';
        if($row->payment_type=='F')
        {
          $payment_type='Full Payment';
        }
        else if($row->payment_type=='P')
        {
          $payment_type='Part Payment';
        }
        else
        {
          $payment_type='N/A';
        }
        ?>
        <?php echo $payment_type; ?>
      </td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>">
        <?php echo ($row->payment_received)?$row->currency_type.' '.number_format($row->payment_received,2):'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php 
        echo $row->currency_type.' '.number_format($row->total_payble_amount-$row->payment_received,2);
        ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->po_tds_percentage>0)?$row->po_tds_percentage.'%':'N/A'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>">
      <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_invoice/'.$row->lead_opportunity_wise_po_id);?>" class="" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/'.$row->lead_opportunity_wise_po_id);?>" class="" title="View Details"><i class="fa fa-search-plus"></i></a>
      </td>
      <tr class="spacer"><td colspan="8"></td></tr>
    </tr>
<?php } ?>
<?php }else{ ?>
  <tr>
    <td colspan="9" style="text-align:center;">No record found!</td>
  </tr>
<?php } ?>