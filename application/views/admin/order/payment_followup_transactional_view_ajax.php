<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){ ?>
    <tr scope="row" class="<?php echo ($row->is_cancel=='Y')?'canceled_po_tr ':''; ?>">
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><a href="JavaScript:void(0);" class="default-link view_payment_ledger" data-lowp="<?php echo $row->lead_opportunity_wise_po_id; ?>"><?php echo ($row->lead_opportunity_wise_po_id)?$row->lead_opportunity_wise_po_id:'-'; ?></a></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->cus_company_name)?$row->cus_company_name:'-'; ?></td>      
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->payment_date)?date_db_format_to_display_format($row->payment_date):'N/A'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php 
        echo $row->currency_type.' '.number_format($row->amount,2);
        ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php 
        echo $row->currency_type.' '.number_format($row->payment_received,2);
        ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php 
        echo $row->currency_type.' '.number_format($row->balance_payment,2);
        ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->amount)?$row->narration:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>">
        <!-- <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/'.$row->lead_opportunity_wise_po_id);?>" class="" title="View Details"><i class="fa fa-envelope"></i></a>
        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/'.$row->lead_opportunity_wise_po_id);?>" class="" title="View Details"><i class="fa fa-whatsapp"></i></a> -->
        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/'.$row->lead_opportunity_wise_po_id);?>" class="" title="View Details"><i class="fa fa-search-plus"></i></a>
      </td>
      <tr class="spacer"><td colspan="6"></td></tr>
    </tr>
<?php } ?>
<?php }else{ ?>
  <tr>
    <td colspan="6">No record found!</td>
  </tr>
<?php } ?>