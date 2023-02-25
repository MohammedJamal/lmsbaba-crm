<div class="company-details">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php if($po_register_info->is_cancel=='Y'){ ?>
                    <div class="alert alert-danger">
                        <h6><strong>Note: </strong> This deal has been cancelled by <?php echo $po_register_info->cancelled_by; ?> on <?php echo date_db_format_to_display_format($po_register_info->cancelled_date); ?>.</h6>
                    </div>
                <?php } ?>
                <ul class="justify-content-between">
                    <?php if($po_register_info->is_cancel=='N'){ ?>
                    <li><a href="JavaScript:void(0);" class="open_po_popup_steps btn btn-primary txt-upper pro-form-preview-bt" data-step="3" data-lowp="<?php echo $lowp; ?>" data-lo_id="<?php echo $po_register_info->lead_opportunity_id; ?>" data-lid="<?php echo $po_register_info->lead_id; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                    <?php } ?>
                    <?php if($po_register_info->proforma_type=='S'){ ?>
                    <li><a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/preview_pro_forma_inv/'.$po_pro_forma_inv_info->lead_opportunity_wise_po_id);?>" class="btn btn-primary txt-upper pro-form-preview-bt <?php echo($po_pro_forma_inv_info->pro_forma_no)?'':'pfi_no_missing'; ?>" target="_blank" ><i class="fa fa fa-eye" aria-hidden="true"></i> Preview</a></li>  
                    <?php } ?>
                    <?php if($po_register_info->is_cancel=='N'){ ?>              
                    <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper pro-form-preview-bt <?php echo($po_pro_forma_inv_info->pro_forma_no)?'send_pro_forma_inv_to_buyer_modal':'pfi_no_missing'; ?>" data-lowp="<?php echo $po_pro_forma_inv_info->lead_opportunity_wise_po_id; ?>" ><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</a></li>
                    <?php } ?>

                    <?php 
                    
                    if($po_register_info->proforma_type=='S')
                    { 
                        $download_url=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_pro_forma_inv/'.$po_pro_forma_inv_info->lead_opportunity_wise_po_id);
                    }
                    else{
                        
                        $company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;

                        $f_name="PROFORMA INVOICE ".$po_pro_forma_inv_info->pro_forma_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".$company_name_tmp;
                        $file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
                        $download_url=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode($file_path.$po_pro_forma_inv_info->po_custom_proforma.'#'.$f_name);
                    }
                    ?>
                  <li><a href="<?php echo $download_url;?>" class="btn btn-primary txt-upper pro-form-preview-bt <?php echo($po_pro_forma_inv_info->pro_forma_no)?'':'pfi_no_missing'; ?>" target="_blank" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>