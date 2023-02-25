<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){ ?>
    <tr scope="row" class="<?php echo ($row->is_cancel=='Y')?'canceled_po_tr ':''; ?> parent_div_<?php echo $row->id; ?>">
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?> get_split" data-pt_id="<?php echo $row->id; ?>"><?php echo ($row->lead_opportunity_wise_po_id)?$row->lead_opportunity_wise_po_id:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?> get_split" data-pt_id="<?php echo $row->id; ?>"><?php echo ($row->cus_company_name)?$row->cus_company_name:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?> get_split" data-pt_id="<?php echo $row->id; ?>"><?php echo ($row->payment_date)?date_db_format_to_display_format($row->payment_date):'N/A'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?> get_split" data-pt_id="<?php echo $row->id; ?>">
        <?php 
        echo $row->currency_type.' '.number_format($row->total_amount,2);
        ?>
      </td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?> get_split" data-pt_id="<?php echo $row->id; ?>">
        <?php 
        echo $row->currency_type.' '.number_format($row->total_payment_received,2);
        ?>
      </td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?> get_split" data-pt_id="<?php echo $row->id; ?>">
        <?php 
        echo $row->currency_type.' '.number_format($row->total_balance_payment,2);
        ?>
      </td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?> get_split" data-pt_id="<?php echo $row->id; ?>"><?php echo ($row->narration)?$row->narration:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>">
        <!-- <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/'.$row->lead_opportunity_wise_po_id);?>" class="" title="View Details"><i class="fa fa-envelope"></i></a>
        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/'.$row->lead_opportunity_wise_po_id);?>" class="" title="View Details"><i class="fa fa-whatsapp"></i></a> -->
        <a href="JavaScript:void(0);" class="default-link view_payment_ledger" data-lowp="<?php echo $row->lead_opportunity_wise_po_id; ?>"><i class="fa fa-search-plus"></i></a>
      </td>
    </tr>
    <tr class="spacer"><td colspan="8"></td></tr>
    
    <div id="split_outer_div_<?php echo $row->id; ?>" style="">
      <tr id="split_outer_tr_<?php echo $row->id; ?>" style="display: none;">
        <td colspan="8" class="padd-no">
          <table class="table custom-table same-parent" id="rander_html_div_<?php echo $row->id; ?>">
            
          </table>
        </td>
      </tr>
    </div>
    
    <tr class="spacer hide_spacer spacer_<?php echo $row->id; ?>"><td colspan="8"></td></tr>
<?php } ?>
<?php }else{ ?>
  <tr>
    <td colspan="8" style="text-align:center;">No record found!</td>
  </tr>
<?php } ?>