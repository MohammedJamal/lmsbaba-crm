<?php
$company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;

$f_name="INVOICE ".$po_inv_info->invoice_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".strtoupper($company_name_tmp);

$file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
?>
<a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/download_po/<?php echo base64_encode($file_path.$po_inv_info->po_custom_invoice.'#'.$f_name);?>" title="click to download"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download </a>
<a href="JavaScript:void(0);" class=" del_po_custom_invoice"  data-id="<?php echo $po_inv_info->id; ?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>