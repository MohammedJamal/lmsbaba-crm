<?php
$client_id=$this->session->userdata['admin_session_data']['client_id'];
$tmp_str=base64_encode(rand().'#'.$quotation['opportunity_id'].'#'.$quotation['id'].'#'.$client_id.'#'.rand().'#12');
?>
Dear <?php echo $customer['contact_person']; ?>%0a%0a
Greeting from <?php echo $company['name']; ?>. With the reference of your enquiry, please find the Quotation Id #<?php echo $quotation['quote_no']; ?> (<?php echo get_company_name_initials(); ?> - <?php echo $lead_info->id; ?>) which is valid till <?php echo date_db_format_to_display_format($quotation['quote_valid_until']); ?>. %0a%0a
*Click to download the quotation:*%0a
<?php echo base_url('preview/'.$tmp_str);?>
%0a%0aLooking forward for your earliest response.
%0a%0aRegards
%0a<?php echo $assigned_to_user['name']; ?>
%0a<?php echo $company['name']; ?>
