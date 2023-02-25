<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){ ?>
    <tr scope="row" class="<?php echo ($row->is_cancel=='Y')?'canceled_po_tr ':''; ?>">
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->id)?$row->id:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->po_number)?$row->po_number:'-'; ?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><span class="no-line-brek"><?php echo date_db_format_to_display_format($row->po_date);?></span></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->cus_company_name)?$row->cus_company_name:'-';?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo $row->lead_opp_currency_code;?> <?php echo number_format($row->deal_value_as_per_purchase_order,2);?></td>
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
        <a href="JavaScript:void(0);" class="default-link view_payment_ledger" data-step="2" data-lowp="<?php echo $row->id; ?>" data-lo_id="<?php echo $row->lead_opportunity_id; ?>" data-lid="<?php echo $row->lead_id; ?>"><?php echo $payment_type; ?></a>
      </td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><a href="JavaScript:void(0)" class="default-link <?php echo ($row->pro_forma_no)?'open_pfi_preview':'pfi_edit_view_confirmation'; ?>" data-step="3" data-lowp="<?php echo $row->id; ?>" data-lo_id="<?php echo $row->lead_opportunity_id; ?>" data-lid="<?php echo $row->lead_id; ?>"><?php echo ($row->pro_forma_no)?$row->pro_forma_no:'N/A';?></a></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->pro_forma_date)?date_db_format_to_display_format($row->pro_forma_date):'-';?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><a href="JavaScript:void(0)" class="default-link <?php echo ($row->invoice_no)?'open_inv_preview':'inv_edit_view_confirmation'; ?>" data-step="4" data-lowp="<?php echo $row->id; ?>" data-lo_id="<?php echo $row->lead_opportunity_id; ?>" data-lid="<?php echo $row->lead_id; ?>"><?php echo ($row->invoice_no)?$row->invoice_no:'N/A';?></a></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>"><?php echo ($row->invoice_date)?date_db_format_to_display_format($row->invoice_date):'-';?></td>
      <td class="<?php echo ($row->is_cancel=='Y')?'canceled_po_td bg-danger':''; ?>">
        <span class="no-line-brek">
        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/'.$row->id);?>" class="" title="View Details"><i class="fa fa-search-plus"></i></a>
        <?php if($row->is_cancel=='N'){ ?>
        &nbsp;
        <a class="icon-btn custom-tooltip tooltipstered cancel_po" href="JavaScript:void(0)" data-id="<?php echo $row->id; ?>" data-pono="<?php echo $row->po_number; ?>" data-po_pf_no="<?php echo $row->pro_forma_no;?>" data-po_inv_no="<?php echo $row->invoice_no;?>" data-lead_id="<?php echo $row->lead_id; ?>" title="cancel PO" style="background-color: red;"><i class="fa fa-thumbs-down " aria-hidden="true"></i></a>
        <?php } ?>
      </span>
      </td>
      <tr class="spacer"><td colspan="10"></td></tr>
    </tr>
<?php } ?>
<?php }else{ ?>
  <tr>
    <td colspan="10" style="text-align:center;">No record found!</td>
  </tr>
<?php } ?>