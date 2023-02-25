<style>
td span{font-weight: 400;}
.fontsize13{ font-size: 13px; }
.wrapper-ar {
margin: 0 auto;
padding: 30px; font-size: 13px;
}
.header-border tr {
border: solid 1px #222;
}
table #tinymce{ font-size: 13px !important; }
.header-border,
.header-border td,
.header-border th {
border: 1px solid #c9d4ea;
}
.calender-icon{position: relative; top:-2px; left: -25px}
.header-border td,
.header-border th {
padding: 0px;
font-size: 13px;
font-weight: 600;
color: #444;
}
table td,
table th {
padding: 13px;
font-size: 13px;
color: #444;  border:solid 1px #ececec;
}
table th{font-size: 13px;}
table tr {
border-bottom: 1px solid #f0f0f0;
}
table {
border-collapse: collapse;
}
.border-solid{border: solid 1px #ccc;}
.calender-input {
border: solid 1px 
#ccc;
padding-top: 0px;
padding-bottom: 0px;
padding-left: 10px;
margin-bottom: 8px;
}
.grand-summery td {
padding: 5px;
text-align: center;
font-size: 13px;
color: #444;
border: solid 1px 
#000;
}
.text{margin-bottom: 15px;}
.co-details li{
font-size: 13px;
font-weight: 400;
color: #444;
}
.mce-tinymce{
border: 1px solid #c9d4ea !important;  
}
.mce-edit-area{
border: none !important;
}
.nopad{
padding-right: 0px !important;
}
.mce-edit-area iframe {
max-height: 62px;
}
.ih-150  .mce-edit-area iframe{
max-height: 90px;
}
.max-50  .mce-edit-area iframe{
max-height: 50px;
}
.form-control#is_discount_p_or_a {
height: 32px !important;
box-shadow: none;
padding: 0 10px !important;
width: 70px !important;
float: left !important;
}
#automated_quotation_popup_modal .form-control#is_discount_p_or_a {
height: 24px !important;
line-height: 24px !important;
box-shadow: none !important;
padding: 0 10px !important;
width: 70px !important;
float: none !important;
margin: 0 auto !important;
}
label.background_blue input.quotation_photo{
   display: none;
}
</style>
<script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript"> 
tinymce.init({
selector: 'textarea.basic-wysiwyg-editor',
height: 400,
force_br_newlines : true,
force_p_newlines : false,
forced_root_block : '',
menubar: false,
statusbar: false,
toolbar: false,    
setup: function(editor) {
editor.on('focusout', function(e) {                   
var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
var po_invoice_id=$("#po_invoice_id").val();
var updated_field_name=editor.id;
var updated_content=editor.getContent();          
fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
//check_submit();
})
}                
});
tinymce.init({
selector: 'textarea.moderate-wysiwyg-editor',
// height: 300,
menubar: false,
statusbar:false,
plugins: ["code,advlist autolink lists link image charmap print preview anchor"],
toolbar: 'bold italic backcolor | bullist numlist',
content_css: [],
setup: function(editor) {
editor.on('focusout', function(e) {
//console.log(editor);
var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
var po_invoice_id=$("#po_invoice_id").val();
var updated_field_name=editor.id;
var updated_content=editor.getContent();
fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
//check_submit();
})
}
});
</script>
<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $id; ?>">
<input type="hidden" name="communication_type" id="communication_type" value="6">
<input type="hidden" name="client_not_interested" id="client_not_interested" value="Y" >
<input type="hidden" name="lead_regret_reason" id="lead_regret_reason" value="">
<input type="hidden" name="lead_comments_for_mail_trail" id="lead_comments_for_mail_trail" value="">
<input type="hidden" name="general_description" id="general_description" value="">
<input type="hidden" name="po_upload_cc_to_employee[]" id="po_upload_cc_to_employee" value="">
<input type="hidden" name="po_lead_opp_id" id="po_lead_opp_id" value="<?php echo $opp_id; ?>">
<input type="hidden" name="po_lead_id" id="po_lead_id" value="<?php echo $id; ?>">
<input type="hidden" name="next_followup_type_id" id="next_followup_type_id" value="2">
<input type="hidden" name="lead_opportunity_wise_po_id" id="lead_opportunity_wise_po_id" value="<?php echo $lowp; ?>">
<input type="hidden" name="po_payment_term_id" id="po_payment_term_id" value="<?php echo $po_pt_id; ?>">
<input type="hidden" name="po_pro_forma_invoice_id" id="po_pro_forma_invoice_id" value="<?php echo $po_pro_forma_inv_info->id; ?>">
<input type="hidden" name="po_invoice_id" id="po_invoice_id" value="<?php echo $po_inv_info->id; ?>">
<input type="hidden" id="po_curr_step" value="">

<!-- <input type="hidden" id="deal_value_as_per_purchase_order" value="<?php //echo round($opportunity_data->deal_value_as_per_purchase_order); ?>"> -->
<?php 
//echo'<pre>';
//print_r($company);
//print_r($po_register_info);
//echo'</pre>'; 
$company_state_id=$company['state_id'];
$customer_state_id='';
if(count($po_register_info))
{
$mode='edit';
$po_tds_percentage=$po_register_info->po_tds_percentage;
$po_date=$po_register_info->po_date;
$po_number=$po_register_info->po_number;
$po_currency_type=$po_register_info->currency_type;
$po_amount=$po_register_info->deal_value_as_per_purchase_order;
$po_file_path=$po_register_info->file_path;
$po_file=$po_register_info->file_name;
$po_comments=$po_register_info->comments;
$customer_state_id=$po_register_info->cust_state_id;
$po_upload_sent_ack_to_client=$po_register_info->is_send_acknowledgement_to_client;
}
else
{
$mode='add';
$po_tds_percentage='';
$po_date=date("Y-m-d");
$po_number='';
$po_currency_type=$company['default_currency'];
$po_amount=round($opportunity_data->deal_value);
$po_file_path='';
$po_file='';
$po_comments='';
$po_upload_sent_ack_to_client='';
}

$f_payment_method='';
$f_payment_method_id='';
$f_payment_date='';
$f_amount='';
$f_narration='';
$f_created_at='';
$f_updated_at='';
$f_currency_type='';
$f_amount_balance=0;


$p_payment_method=array();
$p_payment_method_id=array();
$p_payment_date=array();
$p_amount=array();
$p_narration=array();
$p_created_at='';
$p_updated_at='';
$p_currency_type=array();
$po_payment_type=$po_register_info->payment_type;
$p_amount_total=0;
$p_amount_balance=0;
if($po_payment_type=='F')
{
$payment_log=$po_register_info->payment_log;
$payment_log_arr=explode("#", $payment_log);
$f_payment_method=$payment_log_arr[0];
$f_payment_method_id=$payment_log_arr[1];
$f_payment_date=$payment_log_arr[2];
$f_amount=$payment_log_arr[3];
$f_narration=$payment_log_arr[4];
$f_created_at=$payment_log_arr[5];
$f_updated_at=$payment_log_arr[6];
$f_currency_type=$payment_log_arr[7];
}
else if($po_payment_type=='P')
{

$payment_log=$po_register_info->payment_log;
$payment_log_arr=explode(",", $payment_log);
if(count($payment_log_arr))
{
foreach($payment_log_arr AS $payment)
{
$payment_arr=explode("#", $payment);
$payment_method=$payment_arr[0];
$payment_method_id=$payment_arr[1];
$payment_date=$payment_arr[2];
$amount=$payment_arr[3];
$narration=$payment_arr[4];
$created_at=$payment_log_arr[5];
$updated_at=$payment_log_arr[6];
$currency_type=$payment_arr[7];            

array_push($p_payment_method, $payment_method);
array_push($p_payment_method_id, $payment_method_id);
array_push($p_payment_date, $payment_date);
array_push($p_created_at, $created_at);
array_push($p_updated_at, $updated_at);
array_push($p_currency_type, $currency_type);
array_push($p_amount, $amount);
array_push($p_narration, $narration);
$p_amount_total=($p_amount_total+$amount);
}
}
}
$f_amount_balance=(round($opportunity_data->deal_value_as_per_purchase_order)-$f_amount);
$p_amount_balance=(round($opportunity_data->deal_value_as_per_purchase_order)-$p_amount_total);



if($company_state_id==$customer_state_id)
{
  $is_same_state='Y';
}
else
{
  $is_same_state='N';
}

if($company_state_id=='' || $customer_state_id=='')
{
  $is_state_missing="Y";
}
else
{
  $is_state_missing="N";
}
?>
<input type="hidden" name="is_same_state" id="is_same_state" value="<?php echo $is_same_state; ?>">
<input type="hidden" name="is_state_missing" id="is_state_missing" value="<?php echo $is_state_missing; ?>">
<?php 
/*
$tmp_price=0;
$tmp_additional_charge=0;
if(count($product_list))
{
foreach($product_list AS $p)
{
$price=($p->price*$p->unit)+(($p->price*$p->unit)*($p->gst/100));  
$tmp_price=$tmp_price+$price;  
}
}

if(count($additional_charges_list))
{
foreach($additional_charges_list AS $charge)
{
$price=$charge->price+($charge->price*($charge->gst/100));  
$tmp_additional_charge=$tmp_additional_charge+$price;  
}
}

$deal_value_as_per_purchase_order=($tmp_price+$tmp_additional_charge);
$price_difference=($deal_value_as_per_purchase_order-$opportunity_data->deal_value);
*/
?>
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-body">
<div class="lead-loop">
<div class="lead-top">
<div class="mail-form-row max-width">
<a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
   <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
      <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
   </svg>
</a>
<?php if($is_back_show=='Y'){ ?>
<div class="page-holder float-right mr-45" id="back_div">
   <ul class="action-ul">
      <li>
         <a href="JavaScript:void(0)" title="previous" class="back-bt" data-leadid="<?php echo $id; ?>" id="back_to_linked_comment_update_lead_popup2" data-is_multiple_quotation="<?php echo $is_multiple_quotation; ?>"><img src="<?php echo assets_url(); ?>images/left_black.png"> BACK</a>
      </li>
   </ul>
</div>
<?php } ?>
</div>
</div>
</div>
<div class="content-view">
<div class="card process-sec">
<div class="card-block vertical-style">
<div class="dash-process">
   <ol>
      <li id="po_li_1" class="active">Add/ Edit Purchase Order Details</li>
      <li id="po_li_2">Add/Edit Payment Terms</li>
      <li id="po_li_3">Proforma Invoice</li>
      <li id="po_li_4">Tax Invoice</li>
   </ol>
</div>
<div class="dash-form-holder">
   <!-- step 1 start -->
   <div class="form-steps" id="po_div_1">
      <h2>Add/Edit Purchase Order Details</h2>
      <div class="form-group small-same-row">
         <div class="row">
            <div class="col-md-3">
               <label>Lead ID:</label>
               <div class="mail-input">#<?php echo $lead_data->id; ?></div>
            </div>
            <div class="col-md-3">
               <label>Lead Enquiry Date:</label>
               <div class="mail-input"><?php echo date_db_format_to_display_format($lead_data->enquiry_date); ?></div>
            </div>
            <div class="col-md-3">
               <label>Assign To:</label>
               <div class="mail-input"><?php echo $lead_data->user_name; ?></div>
            </div>
            <div class="col-md-3">
               <label>Quotation ID:</label>
               <div class="mail-input"><?php echo ($opportunity_data->id)?'#'.$opportunity_data->id:'--'; ?></div>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-3">
               <label class="small-title">PO Date <span class="red">*</span></label>
               <div class="full-width"><input type="text" class="date-input input_date<?php //if($mode=='edit'){}else{echo'input_date';} ?>" id="po_date" name="po_date" placeholder="" value="<?php echo date_db_format_to_display_format($po_date); ?>" readonly="true" <?php if($mode=='edit'){echo'readonly="true"';} ?>></div>
            </div>
            <div class="col-md-3">
               <label class="small-title">PO Number</label>
               <div class="full-width">
                  <input type="text" class="default-input" id="po_number" name="po_number" placeholder="ex: 23" value="<?php echo $po_number; ?>" <?php //if($mode=='edit'){echo'readonly="true"';} ?>>                 
               </div>
               <div class="text-danger" id="po_number_error"></div>
            </div>
            <div class="col-md-3">
               <label class="small-title">Currency <span class="red">*</span></label>
               <div class="full-width">
                  <select class="default-select" name="currency_type" id="currency_type" <?php //if($mode=='edit'){echo'disabled';} ?>>
                     <?php 
                        foreach($currency_list AS $currency){ ?>
                     <option value="<?php echo $currency->id; ?>" data-code="<?php echo $currency->code; ?>" <?php if($po_currency_type==$currency->id){echo'SELECTED';}?>><?php echo $currency->code; ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-3">
               <label class="small-title">PO Amount <span class="red">*</span></label>
               <div class="full-width">
                  <input type="text" class="default-input double_digit" id="deal_value_as_per_purchase_order" name="deal_value_as_per_purchase_order" placeholder="ex: 100" value="<?php echo round($po_amount); ?>" <?php //if($mode=='edit'){echo'readonly="true"';} ?>>
               </div>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-6">
               <label class="small-title">Delivery Instructions <span class="red">*</span></label>
               <div class="full-width">
                  <textarea class="form-control" rows="4" id="po_upload_describe_comments" name="po_upload_describe_comments" <?php //if($mode=='edit'){echo'readonly="true"';} ?> style="height: 50px;"><?php echo $po_comments; ?></textarea>
               </div>
               <div class="text-danger" id="po_upload_describe_comments_error">
               </div>
            </div>
            <?php //if($mode=='add'){ ?>
            <div class="col-md-6">
               <label class="small-title">Upload PO Copy <?php if($po_file){ echo'<small class="red">( Upload new file to replace existing. )</small>'; }else{echo'<small class="red">(PDF file only)</small>';}?></label>
               <input class="form-control  po_upload_file" name="po_upload_file" id="po_upload_file" type="file"  accept="application/pdf">
               <!-- <div class="file-upload">
                  <div class="image-upload-wrap">
                    <input class="file-upload-input po_upload_file" name="po_upload_file" id="po_upload_file" type="file" onchange="readURL(this);" accept="*">
                    <div class="drag-text">
                      <button class="file-upload-btn btn-link" type="button" onclick="$('.file-upload-input').trigger('click')">
                             <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                      </button>
                      <h3>
                         Browse File <br>
                         <span>Drag and drop file here</span>
                      </h3>
                    </div>
                  </div>
                  </div> -->
            </div>
            <?php 
            //}
            //else{ 
            ?>
            <?php if($po_file!=''){ ?>
            <div class="col-md-6 text-right" style="margin-top: 10px;">
               <!-- <label class="small-title">Uploaded File</label> -->
               <div class="full-width" id="po_upload_file_div">
                  <?php
                  $f_name="PO-".$po_number;
                  ?>
                  <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/download_po/<?php echo base64_encode($po_file_path.$po_file.'#'.$f_name);?>" title="click to download"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download </a>
                  <a href="JavaScript:void(0);" class="del_po_file" data-lowp="<?php echo $lowp; ?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
               </div>
            </div>
            <?php } ?>
            <?php //} ?>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-12">

            </div>
         </div>
      </div>
      <!-- <div class="form-group row">
         <div class="col-md-12">
           <label class="small-title">Describe Comments <span class="red">*</span></label>
             <div class="full-width">
                 <textarea class="form-control" rows="5" id="po_upload_describe_comments" name="po_upload_describe_comments" <?php if($mode=='edit'){echo'readonly="true"';} ?>><?php echo $po_comments; ?></textarea></div>
             <div class="text-danger" id="po_upload_describe_comments_error">
                   </div>
         </div>
         </div> -->
      <?php 
         // -----------------------------
         // renewal                
         if($lead_data->renewal_detail_id>0 && $mode=='add')
         {  
         ?> 
      <input type="hidden" name="renewal_customer_id" value="<?php echo $lead_data->cus_id; ?>">
      <input type="hidden" name="renewal_id" value="<?php echo $lead_data->renewal_id; ?>">
      <input type="hidden" name="renewal_detail_id" value="<?php echo $lead_data->renewal_detail_id; ?>">
      <div class="form-group">
         <div class="row">
            <div class="col-md-12">
               <label class="check-box-sec fl">
               <input class="styled-checkbox" type="checkbox" value="Y" name="is_renewal_available" id="is_renewal_available" checked>
               <span class="checkmark"></span>
               </label>
               <b>Set Next Renewal Reminder</b>
            </div>
         </div>
      </div>
      <div class="form-group" id="">
         <div class="row">
            <div id="renewal_div">
               <div class="col-md-6 ">
                  <label class="small-title">Renewal Date <span class="red">*</span></label>
                  <div class="full-width"><input type="text" class="date-input " id="renewal_date" name="renewal_date" placeholder="" value="" readonly="true"></div>
               </div>
               <div class="col-md-6 ">
                  <label class="small-title">Next Follow Up <span class="red">*</span></label>
                  <div class="full-width"><input type="text" class="date-input " id="renewal_follow_up_date" name="renewal_follow_up_date" placeholder="" value="" readonly="true"></div>
               </div>
               <div>&nbsp;</div>
               <div class="col-md-12 ">
                  <label class="small-title">Describe Renewal/ AMC Type <span class="red">*</span></label>
                  <div class="full-width">
                     <textarea class="form-control" rows="5" id="renewal_requirement" name="renewal_requirement"></textarea>
                  </div>
                  <div class="text-danger" id="renewal_requirement_error">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php 
         }
         else 
         {
           ?>
      <input type="hidden" value="N" name="is_renewal_available" id="is_renewal_available">
      <?php
         }
         // renewal
         // -----------------------------

         ?>
      <div class="form-group ">
         <div class="row">
            <div class="col-md-12">
               <ul class="justify-content-between">
                  <li>
                  <label class="check-box-sec fl">
                     <input class="styled-checkbox" id="styled-checkbox-1" type="checkbox" value="Y" name="po_upload_sent_ack_to_client" id="po_upload_sent_ack_to_client" <?php echo ($po_upload_sent_ack_to_client=='Y')?'checked':''; ?>>
                     <span class="checkmark"></span>
                  </label>
                  &nbsp;<font style="color:#000;">Send Acknowledgement to Client</font>           
               </li>
               <li>
                  <label class="check-box-sec fl">
                     <input class="styled-checkbox"  type="checkbox" value="Y" name="is_po_tds_applicable" id="is_po_tds_applicable" <?php echo ($po_tds_percentage)?'checked':''; ?>>
                     <span class="checkmark"></span>
                  </label>
                  &nbsp;<font style="color:#000;">TDS Deduction Applicable</font>  
                  <input type="text" class="default-input double_digit" id="po_tds_percentage" name="po_tds_percentage" placeholder="" value="<?php echo ($po_tds_percentage)?$po_tds_percentage:''; ?>" style="width: 48px;height: 25px;" <?php echo ($po_tds_percentage)?'':'readonly="true"'; ?>> %      
               </li>
               <li>
               <?php //if($mode=='add'){ ?>
               <a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt pull-right" id="po_upload_submit">Save & Continue &nbsp;<i class="fa fa-floppy-o" aria-hidden="true"></i></a>
               <?php //} ?>
               </li>
               <li>
               <?php if($mode=='edit'){ ?>
               <a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt pull-right skip_to_next" id="">Next <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
               <?php } ?>
               </li>
            </ul>
            </div>
         </div>
      </div>
   </div>
   <!-- step 1 end -->
   <!-- step 2 start -->
   <div class="form-steps" id="po_div_2" style="display: none;">
      <h2>Add/Edit Payment Terms</h2>
      <div class="form-group">
         <div class="row">
            <div class="col-md-6">
               <div class="autofit">
                  <input type="text" name="" class="default-input auto-width big-txt" value="Payment Terms" disabled="">
               </div>
            </div>
            <div class="col-md-6 text-right">
               <span class="g-txt lh-40 bl-txt">PO Amount: <?php echo $opportunity_data->currency_code; ?> <?php echo number_format(round($opportunity_data->deal_value_as_per_purchase_order),2); ?>   <small class="text-danger">
                     <?php echo ($po_tds_percentage)?'<b>('.$po_tds_percentage.'% TDS Applied)</b> ':''; ?>
                  </small>
               </span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-12">
               <div class="full-width">
                  <input type="radio" id="payment_type_f" name="payment_type" class="default-radio" checked="" value="F" <?php if($po_payment_type=='F'){echo'checked';} ?>>
                  <label for="payment_type_f" class="mbt-10">Full Payment</label>
               </div>
               <span id="payment_type_f_div">
               <div class="payment-loop fix-input">
                  <ul>
                     <li>
                        <label class="blue-title">Payment Mode <span class="red">*</span></label>
                        <div class="full-width">
                           <select class="default-select" name="f_payment_mode_id" id="f_payment_mode_id">
                              <option value="">Select</option>
                              <?php if(count($po_payment_method)){ ?>
                              <?php
                                 foreach($po_payment_method AS $method){
                                     ?>
                              <option value="<?php echo $method->id; ?>" <?php if($f_payment_method_id==$method->id){echo'SELECTED';} ?>><?php echo $method->name; ?></option>
                              <?php
                                 }
                                 }
                                 ?>
                           </select>
                        </div>
                     </li>
                     <li>
                        <label class="blue-title">Date of Payment <span class="red">*</span></label>
                        <div class="full-width"><input type="text" class="date-input input_date" name="f_payment_date" id="f_payment_date" placeholder="MM-DD-YYYY" value="<?php echo ($f_payment_date)?date_db_format_to_display_format($f_payment_date):''; ?>"></div>
                     </li>
                     <li>
                        <label class="blue-title">Currency Type <span class="red">*</span></label>
                        <div class="full-width">
                           <select class="default-select" name="f_currency_type" id="f_currency_type">
                              <?php 
                                 foreach($currency_list AS $currency){ ?>
                              <option value="<?php echo $currency->code; ?>" data-code="<?php echo $currency->code; ?>" <?php if($f_currency_type==$currency->code){echo'SELECTED';}?>><?php echo $currency->code; ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </li>
                     <li>
                        <label class="blue-title">Amount <span class="red">*</span></label>
                        <div class="full-width">
                           <input type="text" class="default-input double_digit" name="f_amount" id="f_amount" placeholder="ex: 23" value="<?php echo $f_amount; ?>">
                           
                        </div>
                     </li>
                     <li>
                        <label class="blue-title">Narration</label>
                        <div class="full-width"><input type="text" class="default-input" name="f_narration" id="f_narration" value="<?php echo $f_narration; ?>"></div>
                     </li>
                  </ul> 
               </div>
               <div class="text-right"><small class="bal-txt">Balance Payment <span id="f_amount_balance"><?php echo $f_amount_balance; ?></span></small></div></span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-12">
               <div class="full-width">
                  <input type="radio" id="payment_type_p" name="payment_type" class="default-radio" value="P" <?php if($po_payment_type=='P'){echo'checked';} ?>>
                  <label for="payment_type_p">Part Payment </label>
               </div>
               <div class="payment-details-holder mb-0" style="display: none;">
                  <div id="payment_type_p_div">
                     
                        <?php 
                           if(count($p_payment_method_id))
                           { 
                               for($i=0;$i<count($p_payment_method_id);$i++)
                               {
                           
                           ?>
                           <div class="payment-loop fix-input" id="div_<?php echo $i; ?>">
                        <ul>
                           <li>
                              <label class="blue-title">Payment Mode <span class="red">*</span></label>
                              <div class="full-width">
                                 <select class="default-select" name="p_payment_mode_id[]" id="p_payment_mode_id_<?php echo $i; ?>">
                                    <option value="">Select</option>
                                    <?php if(count($po_payment_method)){ ?>
                                    <?php
                                       foreach($po_payment_method AS $method){
                                           ?>
                                    <option value="<?php echo $method->id; ?>" <?php if($p_payment_method_id[$i]==$method->id){echo'selected';} ?>><?php echo $method->name; ?></option>
                                    <?php
                                       }
                                       }
                                       ?>
                                 </select>
                              </div>
                           </li>
                           <li>
                              <label class="blue-title">Date of Payment <span class="red">*</span></label>
                              <div class="full-width"><input type="text" class="date-input input_date" name="p_payment_date[]" id="p_payment_date_<?php echo $i; ?>" placeholder="MM-DD-YYYY" value="<?php echo ($p_payment_date[$i])?date_db_format_to_display_format($p_payment_date[$i]):''; ?>"></div>
                           </li>
                           <li>
                              <label class="blue-title">Currency Type <span class="red">*</span></label>
                              <div class="full-width">
                                 <select class="default-select" name="p_currency_type[]" id="p_currency_type_<?php echo $i; ?>">
                                    <?php 
                                       foreach($currency_list AS $currency){ ?>
                                    <option value="<?php echo $currency->code; ?>" data-code="<?php echo $currency->code; ?>" <?php if($p_currency_type[$i]==$currency->code){echo'SELECTED';}?>><?php echo $currency->code; ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </li>
                           <li>
                              <label class="blue-title">Amount <span class="red">*</span></label>
                              <div class="full-width">
                                 <input type="text" class="default-input" name="p_amount[]" id="p_amount_<?php echo $i; ?>" placeholder="ex: 23" value="<?php echo $p_amount[$i]; ?>"> 
                              </div>
                              
                           </li>
                           <li>
                              <label class="blue-title">Narration</label>
                              <div class="full-width"><input type="text" class="default-input" name="p_narration[]" id="p_narration_1" value="<?php echo $p_narration[$i]; ?>"></div>
                              <?php if($i!=0){ ?>
                              <span class="payment_span_del"><a href="JavaScript:void(0)" class="payment_div_del" data-divid="<?php echo $i; ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></a></span><?php } ?>
                           </li>
                        </ul>
                        </div>
                        <?php 
                           }
                           }
                           else
                           {
                           ?>
                           <div class="payment-loop fix-input">
                        <ul>
                           <li>
                              <label class="blue-title">Payment Mode <span class="red">*</span></label>
                              <div class="full-width">
                                 <select class="default-select" name="p_payment_mode_id[]" id="p_payment_mode_id_1">
                                    <option value="">Select</option>
                                    <?php if(count($po_payment_method)){ ?>
                                    <?php
                                       foreach($po_payment_method AS $method){
                                           ?>

                                    <option value="<?php echo $method->id; ?>"><?php echo $method->name; ?></option>
                                    <?php
                                       }
                                       }
                                       ?>
                                 </select>
                              </div>
                           </li>
                           <li>
                              <label class="blue-title">Date of Payment <span class="red">*</span></label>
                              <div class="full-width"><input type="text" class="date-input input_date" name="p_payment_date[]" id="p_payment_date_1" placeholder="MM-DD-YYYY" value=""></div>
                           </li>
                           <li>
                              <label class="blue-title">Currency Type <span class="red">*</span></label>
                              <div class="full-width">
                                 <select class="default-select" name="p_currency_type[]" id="p_currency_type_1">
                                    <?php 
                                       foreach($currency_list AS $currency){ ?>
                                    <option value="<?php echo $currency->code; ?>" data-code="<?php echo $currency->code; ?>"><?php echo $currency->code; ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </li>
                           <li>
                              <label class="blue-title">Amount <span class="red">*</span></label>
                              <div class="full-width">
                                 <input type="text" class="default-input" name="p_amount[]" id="p_amount_1" placeholder="ex: 23" value="">
                              </div>
                              
                           </li>
                           <li>
                              <label class="blue-title">Narration</label>
                              <div class="full-width"><input type="text" class="default-input" name="p_narration[]" id="p_narration_1" value=""></div>
                           </li>
                        </ul>
                     </div>
                        <?php
                           }
                           ?>
                     
                  </div>
         <div class="text-right"><small class="bal-txt">Balance Payment <span id="p_amount_balance"><?php echo $p_amount_balance; ?></span></small></div>
                  <!-- <div class="payment-loop fix-input" id="">
                     <ul>
                        <li></li>
                        <li></li>
                        <li><small class="bal-txt">Balance Payment <span id="p_amount_balance"><?php //echo $p_amount_balance; ?></span></small></li>
                        <li></li>
                     </ul>
                  </div> -->
               </div>
               <a href="JavaScript:void(0);" class="pay-bt-add" style="display: none;" id="add_p_payment_btn"> +Add</a>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-12">
               <ul class="justify-content-between">
                  <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper prev-bt pull-left skip_to_prev"><i class="fa fa-long-arrow-left skip_to_prev" aria-hidden="true"></i> Previous</a></li>
                  <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt" id="po_payment_terms_submit">Save & Continue <i class="fa fa-floppy-o" aria-hidden="true"></i></a></li>
                  <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt pull-right skip_to_next" id="">Skip & Next <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></li>
               </ul>          
            </div>
            
         </div>
      </div>
   </div>
   <!-- step 2 end -->
   <!-- step 3 start -->
   <div class="form-steps" id="po_div_3" style="display: none;">
      <h2>Generate Proforma Invoice</h2>
      
      <div class="form-group">
         <div class="row">
            <div class="col-md-6">
               <div class="autofit">
                  <input type="text" name="" class="default-input auto-width big-txt" value="Proforma Invoice" disabled="">
               </div>
            </div>
            <div class="col-md-6 text-right">
               <span class="g-txt lh-40 bl-txt">PO Amount: <?php echo $opportunity_data->currency_code; ?> <?php echo number_format(round($opportunity_data->deal_value_as_per_purchase_order),2); ?>    <small class="text-danger">
                     <?php echo ($po_tds_percentage)?'<b>('.$po_tds_percentage.'% TDS Applied)</b> ':''; ?>
                  </small>
               </span>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-8 d-flex">
               <div class="full-width">
                  <input type="radio" id="proforma_type_s" name="proforma_type" class="default-radio"  value="S" <?php echo ($po_pro_forma_inv_info->proforma_type=='S')?'checked=""':''; ?>>
                  <label for="proforma_type_s" class="mbt-10">Generate PDF Proforma Invoice</label>
               </div>
               <div class="full-width">
                  <input type="radio" id="proforma_type_c" name="proforma_type" class="default-radio" value="C" <?php echo ($po_pro_forma_inv_info->proforma_type=='C')?'checked=""':''; ?>>
                  <label for="proforma_type_c" class="mbt-10">Upload Custom Proforma Invoice</label>
               </div>
            </div>

         </div>
      </div>
      <div class="form-group">
         <div class="btb">
            <div class="row">
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-small-title">Proforma No:<span class="red">*</span></label>
                     <div class="full-width">
                        <input type="text" class="date-input  invoice_number" name="pfi_pro_forma_no" id="pfi_pro_forma_no" placeholder="" value="<?php echo ($po_pro_forma_inv_info->pro_forma_no)?$po_pro_forma_inv_info->pro_forma_no:$system_proforma_no; ?>" >
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-small-title">Proforma Date:<span class="red">*</span></label>
                     <div class="full-width"><input type="text" class="date-input input_date" name="pfi_pro_forma_date" id="pfi_pro_forma_date" placeholder="" value="<?php echo date_db_format_to_display_format($po_pro_forma_inv_info->pro_forma_date); ?>" readonly="true"><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date"></div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-small-title">Due Date:</label>
                     <div class="full-width"><input type="text" class="date-input input_date" name="pfi_due_date" id="pfi_due_date" placeholder="" value="<?php echo date_db_format_to_display_format($po_pro_forma_inv_info->due_date); ?>"><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date"></div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-small-title">Exp. Delivery Date:</label>
                     <div class="full-width"><input type="text" class="date-input input_date" name="pfi_expected_delivery_date" id="pfi_expected_delivery_date" placeholder="" value="<?php echo date_db_format_to_display_format($po_pro_forma_inv_info->expected_delivery_date); ?>"><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div id="proforma_type_c_div" style="display: none;">
         <div class="form-group custom_invoice">
            <div class="row">
               <div class="col-md-6">
                  <label class="small-title">Upload Proforma Invoice <small class="red">(PDF file only)</small></label>
                  <input class="form-control  po_custom_proforma" name="po_custom_proforma" id="po_custom_proforma" type="file"  accept="application/pdf" data-existing="<?php echo $po_pro_forma_inv_info->po_custom_proforma; ?>">
               </div>
            
            <div class="col-md-6 text-left" style="margin-top: 10px;">
               <label class="small-title">&nbsp;</label>
               <div class="full-width" id="po_custom_proforma_div">
               <?php                
               if($po_pro_forma_inv_info->po_custom_proforma!=''){ 
                  
                  $company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;
                  $f_name="PROFORMA INVOICE ".$po_pro_forma_inv_info->pro_forma_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".$company_name_tmp;
                  $file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
                  ?>
                  <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/download_po/<?php echo base64_encode($file_path.$po_pro_forma_inv_info->po_custom_proforma.'#'.$f_name);?>" title="click to download"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download </a>
                  <a href="JavaScript:void(0);" class=" del_po_custom_proforma" data-id="<?php echo $po_pro_forma_inv_info->id; ?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
                  <?php } ?>

               </div>
            </div>
            
            </div>
         </div>
      </div>

      <div id="proforma_type_s_div">
         <div class="form-group">
            <div class="row">
               <div class="col-md-4">
                  <label class="blue-small-title lh-20">Bill From:</label>
                  <textarea class="basic-wysiwyg-editor" name="pfi_bill_from" id="pfi_bill_from"  style="height: 400px;"><?php echo $po_pro_forma_inv_info->bill_from; ?></textarea>
               </div>
               <div class="col-md-4">
                  <label class="blue-small-title lh-20">Bill To:</label>
                  <textarea class="basic-wysiwyg-editor" name="pfi_bill_to" id="pfi_bill_to"  style="height: 400px;"><?php echo ($po_pro_forma_inv_info->pro_forma_no)?$po_pro_forma_inv_info->bill_to:$curr_customer_bill_to; ?></textarea>
               </div>
               <div class="col-md-4">
                  <label class="blue-small-title lh-20">
                  <label class="check-box-sec fl">
                  <input class="styled-checkbox" type="checkbox" value="Y" name="pfi_is_ship_to_available" id="pfi_is_ship_to_available" <?php echo ($po_pro_forma_inv_info->ship_to)?'checked':'';?>>
                  <span class="checkmark"></span>
                  </label>                      
                  &nbsp; &nbsp;Ship To:
                  </label>
                  <textarea class="basic-wysiwyg-editor" name="pfi_ship_to" id="pfi_ship_to"  style="height: 400px;" disabled=""><?php echo $po_pro_forma_inv_info->ship_to; ?></textarea>
               </div>
            </div>
         </div>
            <?php 
            if(strtoupper($company['default_currency'])=='INR'){
            $is_tax_show='Y';
            }
            else{
            $is_tax_show='N';
            }
            ?>
            <?php
            // IS DISCOUNT VAILABLE CHECKING : START
            $is_discount_available='N';
            $discount_wise_colspan="colspan='2'";
            $discount_wise_colspan_cnt=0;
            if(count($prod_list))
            {
            foreach($prod_list as $p)
            {
              if($p->discount>0)
              {
                $is_discount_available='Y';
                $discount_wise_colspan='';
                $discount_wise_colspan_cnt=1;
                break;
              }
            }
            }         
            if($is_discount_available=='N')
            {
            if(count($selected_additional_charges))
            {
              foreach($selected_additional_charges AS $c)
              {
                if($c->discount>0)
                {
                  $is_discount_available='Y';
                  $discount_wise_colspan="";
                  $discount_wise_colspan_cnt=1;
                  break;
                }
              }
            }
            } 
            // IS DISCOUNT VAILABLE CHECKING : END
            ?>
         <div class="form-group">
         <div class="row">
         <div class="col-md-12">
            <div id="pfi_product_list" style="width: 100%; clear: both;margin-top: 15px;display: inline-block;">
               <table class="table quotation-table table-borderless">
                  <thead>
                     <tr>
                        <th width="25%">Product Description</th>
                        <th class="text-center">
                           Unit Price <br>
                           <select id="change_currency_type_pfi" class="form-control color-select small-select width-60" name="change_currency_type_pfi">
                              <option value="">Select</option>
                              <?php foreach($currency_list as $currency_data) { ?>
                              <option value="<?php echo $currency_data->id;?>" <?php if($po_pro_forma_inv_info->currency_type==$currency_data->code){ ?>selected="selected"
                                 <?php } ?>>
                                 <?php echo $currency_data->code;
                                    ?>
                              </option>
                              <?php }  ?>
                           </select>
                        </th>
                        <!-- <th class="text-center">Unit</th> -->
                        <th class="text-center">Quantity</th>
                        <th class="text-center">
                           Discount<br>
                           <select id="is_discount_p_or_a_pfi" class="form-control color-select small-select width-60" name="is_discount_p_or_a_pfi" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">
                              <option value="P" <?php if($prod_list){if($prod_list[0]->is_discount_p_or_a=='P'){echo'SELECTED';} }?>>%</option>
                              <option value="A" <?php if($prod_list){if($prod_list[0]->is_discount_p_or_a=='A'){echo'SELECTED';}} ?>>Amt.</option>
                           </select>
                        </th>
                        <th class="text-center">GST (%)</th>
                        <th class="text-center" width="14%">Net Amount</th>
                        
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $is_product_image_available='N';
                        $is_product_brochure_available='N'; 
                        $img_flag=0;
                        $brochure_flag=0;                  
                        if(count($prod_list))
                        {         
                          $i=0;
                          $sub_total=0;
                          $s=1;
                          $discount=0;
                          $item_gst_per=0;
                          $item_sgst_per=0;
                          $item_cgst_per=0;
                          $total_price=0;
                          $total_discounted_price=0;
                          $total_tax_price=0;
                          foreach($prod_list as $output)
                          {
                            $item_gst_per= $output->gst;
                            $item_sgst_per= ($item_gst_per/2);
                            $item_cgst_per= ($item_gst_per/2); 
                            $item_is_discount_p_or_a=$output->is_discount_p_or_a; 
                            $item_discount=$output->discount; 
                            $item_unit=$output->unit;
                            $item_price=($output->price/$item_unit);
                            $item_qty=$output->quantity;
                        
                            $item_total_amount=($item_price*$item_qty);
                            if($item_is_discount_p_or_a=='A'){
                              $row_discount_amount=$item_discount;
                            }
                            else{
                              $row_discount_amount=$item_total_amount*($item_discount/100);
                            }
                        
                            
                            $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
                        
                            $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                            $sub_total=$sub_total+$row_final_price;
                        
                            $total_price=$total_price+$item_total_amount;
                            $total_discounted_price=$total_discounted_price+$row_discount_amount;
                            $total_tax_price=$total_tax_price+$row_gst_amount;
                        
                            if($output->image!='' && $img_flag==0)
                            {
                              $is_product_image_available='Y';
                              $img_flag=1;
                            }
                            if($output->brochure!='' && $brochure_flag==0)
                            {
                              $is_product_brochure_available='Y';
                              $brochure_flag=1;
                            }
                        
                          ?>
                          <tr class="spacer"><td colspan="6"></td></tr>
                     <tr id="tr_<?php echo $output->id;?>">
                        <td>
                           <textarea class="basic-wysiwyg-editor" id="<?php echo 'pfi_product_name#'.$output->id;?>" style="height: 300px;"><?php echo $output->product_name; ?></textarea>
                           <!-- <input type="text" class="default-input calculate_pfi_price_update" name="" id="" value="<?php echo $output->product_name; ?>" data-field="product_name" data-id="<?php echo $output->id;?>"   data-pid="<?php echo $output->product_id; ?>"  data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>"/> -->
                           <!-- <span class="p_code"><?php // echo ($output->product_sku)?'( Code: '.$output->product_sku.' )':'';?></span> -->
                        </td>             
                        <td>
                           <div class="padding0 d-flex">
                              <input type="text" class="default-input calculate_pfi_price_update double_digit width-80" id="" name="" value="<?php echo $output->price;?>" data-field="price" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" />
                              <span class="per-span">Per</span>
                              <input type="text" class="default-input calculate_pfi_price_update only_natural_number_noFirstZero ml-10 width-60" name="" id="" value="<?php echo $output->unit?>" data-field="unit" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" style="min-width: 50px;" />
                              
                              <select id="" class="default-select width-80 ml-10 calculate_pfi_price_update" name="" data-field="unit_type" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>">
                                 <option value="">Select</option>
                                 <?php foreach($unit_type_list as $unit_type) { ?>
                                 <option value="<?php echo $unit_type->type_name;?>" <?php if($output->unit_type==$unit_type->type_name){ ?>selected="selected"
                                    <?php } ?>>
                                    <?php echo $unit_type->type_name;
                                       ?>
                                 </option>
                                 <?php }  ?>
                              </select>
                           </div>
                        </td>
                        <td>
                           <input type="text" class="default-input calculate_pfi_price_update double_digit width-80" id="" name="" value="<?php echo $output->quantity; ?>" data-field="quantity" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>"   />  
                        </td>
                        <td>
                           <input type="text" class="default-input calculate_pfi_price_update double_digit width-60" id="" name="" value="<?php echo $output->discount; ?>" data-field="discount"data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>"   />  
                        </td>
                        <td>
                           <input type="text" class="default-input calculate_pfi_price_update double_digit width-60" id="" name="" value="<?=ceil($output->gst);?>" data-field="gst" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>"  />
                        </td>
                        <td>
                           <!-- <input type="hidden" name="currency_type[]" value="<?=$output->currency_type;?>" id="currency_type_update_<?=$i;?>" class="form-control"/> -->
                           <span class="" id="total_sale_price_<?=$output->id;?>"><?php echo number_format($row_final_price,2);?></span>
                        
                           <a href="JavaScript:void(0)" class="del_pfi_product" data-id="<?=$output->id;?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" data-pid="<?=$output->product_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
                        </td>
                     </tr>
                     <tr class="spacer"><td colspan="6"></td></tr>
                     <?php
                        $s++;
                        $i++;
                        }
                        }
                        ?>
                     <?php if(count($additional_charges_list)){?>
                     <?php 
                        $j=0;
                        foreach($additional_charges_list AS $charge)
                        {
                          $i++; 
                          $item_gst_per= $charge->gst;
                          $item_sgst_per= ($item_gst_per/2);
                          $item_cgst_per= ($item_gst_per/2);
                          $item_is_discount_p_or_a=$charge->is_discount_p_or_a;  
                          $item_discount_per=$charge->discount; 
                          $item_price= $charge->price;
                          $item_qty=1;
                        
                          $item_total_amount=($item_price*$item_qty);
                          if($item_is_discount_p_or_a=='A'){
                            $row_discount_amount=$item_discount_per;
                          }
                          else{
                            $row_discount_amount=$item_total_amount*($item_discount_per/100);
                          }
                          // $row_discount_amount=$item_total_amount*($item_discount_per/100);
                          $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
                        
                          $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                          $sub_total=$sub_total+$row_final_price; 
                        
                          $total_price=$total_price+$item_total_amount;
                          $total_tax_price=$total_tax_price+$row_gst_amount;
                          $total_discounted_price=$total_discounted_price+$row_discount_amount;
                        ?>    
                     <tr id="tr_additional_charge_<?php echo $charge->id;?>" class="<?php echo ($j%2==0)?'event':'odd'; ?>">
                        <td>
                           <textarea class="basic-wysiwyg-editor" id="<?php echo 'pfi_additional_charge_name#'.$charge->id;?>" style="height: 300px;"><?php echo $charge->additional_charge_name; ?></textarea>
                           <!-- <input type="text" class="default-input calculate_pfi_additional_charges_price_update" value="<?php echo $charge->additional_charge_name; ?>" data-field="additional_charge_name" data-id="<?php echo $charge->id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" /> -->
                           <?php //echo $charge->additional_charge_name; ?>
                        </td>
                        <td>
                           <div class="padding0 d-flex">
                           <input type="text" class="width-80 default-input calculate_pfi_additional_charges_price_update double_digit" value="<?php echo $charge->price; ?>" data-field="price" data-id="<?php echo $charge->id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" />
                           <span class="per-span">Per</span>
                           <input type="text" class="default-input ml-10 width-60" value="-" readonly="true" />
                            <input type="text" class="default-select width-80 ml-10 " value="-" readonly="true" />
                         </div>
                        </td>
                        
                        <td>
                           <input type="text" class="default-input width-80" value="-" readonly="true" />
                        </td>
                        <td>    
                           <input type="text" class="width-60 default-input calculate_pfi_additional_charges_price_update double_digit additional_charges_discount" value="<?php echo $charge->discount; ?>"  data-field="discount" data-id="<?php echo $charge->id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" />
                        </td>
                        <td>                        
                           <input type="text" class="default-input calculate_pfi_additional_charges_price_update double_digit additional_charges_gst width-60" value="<?php echo $charge->gst; ?>" data-field="gst" data-id="<?php echo $charge->id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" />
                        </td>
                        <td align="center">
                           <span class="" id="additional_charges_total_sale_price_<?php echo $charge->id; ?>"><?php echo number_format($row_final_price,2); ?></span>
                            
                           <a href="JavaScript:void(0)" class="del_pfi_additional_charges_update" data-id="<?php echo $charge->id;?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>" data-pid="<?=$output->product_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
                        </td>
                     </tr>
                     <tr class="spacer"><td colspan="6"></td></tr>
                     <?php $j++; } ?>
                     <?php } ?>
                     <tr>
                        <td colspan="5" style="text-align:right;" class="back_border_total bt">Total Net Amount</td>
                        <td colspan="1" class="back_border_total text-center">
                           <span id="total_deal_value" class="bt"><?php echo number_format($sub_total,2);?></span>
                           <input type="hidden" name="deal_value" id="deal_value" value="<?=number_format($sub_total,2);?>">
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_product_to_pfi" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Product </a></h4>
                  <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_additional_charges_to_pfi" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Additional Charges </a></h4>
                  <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_new_row_pfi" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add New Row</a></h4>
               </div>
               <div class="col-md-6">
                  <table class="grand-summery table-borderless" width="100%" border="0" cellpadding="0" cellspacing="0">
                     <tbody>
                        <tr>
                           <td width="50%"></td>
                           <td colspan="50%"><b class="bt">Details (<span class="pfi_currency_code_div"><?php echo $po_pro_forma_inv_info->currency_type; ?></span>)</b></td>
                        </tr>
                        <tr>
                           <td>Total Gross Amount</td>
                           <td><span id="total_price"><?php echo number_format($total_price,2); ?></span></td>
                        </tr>
                        <?php //if($is_discount_available=='Y'){ ?>
                        <tr>
                           <td>Total Discount</td>
                           <td><span id="total_discount"><?php echo number_format($total_discounted_price,2); ?></span></td>
                        </tr>
                        <?php //} ?> 
                        <?php 
                        //if($is_tax_show=='Y'){ 
                        /*
                        ?>
                        <tr>
                           <td>Total Tax</td>
                           <td><span id="total_tax"><?php echo number_format($total_tax_price,2); ?></span></td>
                        </tr>
                        <?php 
                        */
                        //} 
                        ?>
                        <?php 
                        if($is_same_state=='Y')
                        { 
                           $sgst=($total_tax_price/2);
                           $cgst=($total_tax_price/2);
                        ?>
                        <tr>
                           <td>SGST</td>
                           <td><span id="pfi_total_sgst"><?php echo number_format($sgst,2); ?></span></td>
                        </tr>
                        <tr>
                           <td>CGST</td>
                           <td><span id="pfi_total_cgst"><?php echo number_format($cgst,2); ?></span></td>
                        </tr>
                        <?php 
                        } 
                        else
                        {
                        ?>
                        <tr>
                           <td><?php echo ($is_state_missing=='Y')?'GST':'IGST'; ?></td>
                           <td><span id="pfi_total_tax"><?php echo number_format($total_tax_price,2); ?></span></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                           <td>Payable amount (Round Off)</td>
                           <td><span id="grand_total_round_off"><?php echo number_format(round($sub_total),2); ?></span></td>
                        </tr>
                        <tr>
                           <td colspan="2"><span id="number_to_word_final_amount"><strong><?php echo number_to_word(round($sub_total)); ?></strong></span> (<span class="pfi_currency_code_div"><strong><?php echo $po_pro_forma_inv_info->currency_type; ?></strong></span>)</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         </div>
         </div>
         <div class="form-group">
         <div class="row">
         <div class="col-md-12">
            <label class="blue-small-title">Bank Detail:</label>
         </div>
         <div class="col-md-6">
            <textarea class="basic-wysiwyg-editor" name="pfi_bank_detail_1" id="pfi_bank_detail_1"  style="height: 300px;" placeholder="Bank Detail 1"><?php echo $po_pro_forma_inv_info->bank_detail_1; ?></textarea>         
         </div>
         <div class="col-md-6">
            <textarea class="basic-wysiwyg-editor" name="pfi_bank_detail_2" id="pfi_bank_detail_2"  style="height: 300px;" placeholder="Bank Detail 2"><?php echo $po_pro_forma_inv_info->bank_detail_2; ?></textarea>          
         </div>
         </div>
         </div>
         <div class="form-group">
         <div class="row">
         <div class="col-md-12">
            <label class="blue-small-title">Terms & Conditions/Comments:</label>
            <textarea class=" basic-wysiwyg-editor" name="pfi_terms_conditions" id="pfi_terms_conditions"><?php echo $po_pro_forma_inv_info->terms_conditions; ?></textarea>
         </div>
         </div>
         </div>
         <div class="form-group">
         <div class="row">
         <div class="col-md-12">
            <label class="blue-small-title">Additional Note:</label>
            <textarea class=" basic-wysiwyg-editor" name="pfi_additional_note" id="pfi_additional_note"><?php echo $po_pro_forma_inv_info->additional_note; ?></textarea>
         </div>
         </div>
         </div>
         <?php
            $is_show_digital_signature_checked='N';
            if($company['digital_signature'])
            {
              $is_show_digital_signature_checked='Y'; 
            }
            ?>
         <div class="<?php if($is_show_digital_signature_checked=='Y'){ echo'form-group';} ?>">
            <div class="row" <?php if($is_show_digital_signature_checked=='N'){ echo'style="display:none;"';} ?>>
               <div class="col-md-12">
                  <label class="check-box-sec fl">
                  <input class="styled-checkbox" type="checkbox" value="Y" name="pfi_is_digital_signature_checked" id="pfi_is_digital_signature_checked" <?php echo ($po_pro_forma_inv_info->is_digital_signature_checked=='Y')?'checked':'';?>>
                  <span class="checkmark"></span>
                  </label>&nbsp;&nbsp;<b>Digital Signature</b>
               </div>
            </div>
         </div>
         <?php
            if($po_pro_forma_inv_info->is_digital_signature_checked=='Y')
            {
              $show_thanks_and_regards='N';
              $show_signature='Y';
            }
            else
            {
              $show_thanks_and_regards='Y';
              $show_signature='N';
            }    
            ?>
         <div class="form-group ">
            <div class="row">
               <div class="col-md-6">
                  <div <?php if($show_signature=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> class="name_of_authorised_signature_div"><img src="<?php echo assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$company['digital_signature']; ?>" height="50"></div>
                  <label class="blue-small-title" id="digital_signature_title"><?php echo ($po_pro_forma_inv_info->is_digital_signature_checked=='Y')?'Name of authorized signatory':'Thanks & Regards';?>:</label>
                  <span <?php if($show_signature=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> id="name_of_authorised_signature_div" class="name_of_authorised_signature_div">
                  <textarea class="basic-wysiwyg-editor " name="pfi_name_of_authorised_signature" id="pfi_name_of_authorised_signature" ><?php echo ($po_pro_forma_inv_info->name_of_authorised_signature)?$po_pro_forma_inv_info->name_of_authorised_signature:$company['authorized_signatory']; ?></textarea></span>
                  <span <?php if($show_thanks_and_regards=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> id="thanks_and_regards_div">
                  <textarea class="basic-wysiwyg-editor " name="pfi_thanks_and_regards" id="pfi_thanks_and_regards" ><?php echo $po_pro_forma_inv_info->thanks_and_regards; ?></textarea></span>
               </div>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-12">
               <ul class="justify-content-between">
                  <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper prev-bt pull-left skip_to_prev"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Previous</a></li> 
                  <?php //if($po_register_info->proforma_type=='S'){ ?> 
                  <li><a href="JavaScript:void(0);" data-actiontype="pfi_preview" data-actionurl="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/preview_pro_forma_inv/'.$po_pro_forma_inv_info->lead_opportunity_wise_po_id);?>" class="btn btn-primary txt-upper pro-form-preview-bt po_pro_forma_invoice_submit"  ><i class="fa fa fa-eye" aria-hidden="true"></i> Preview</a></li>   
                  <?php //} ?>             
                  <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper pro-form-preview-bt po_pro_forma_invoice_submit" data-lowp="<?php echo $po_pro_forma_inv_info->lead_opportunity_wise_po_id; ?>" data-actiontype="pfi_send" data-actionurl=""  ><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</a></li>
                  <li>	
						<?php
						
						$download_url_s=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_pro_forma_inv/'.$po_pro_forma_inv_info->lead_opportunity_wise_po_id);
												
						$company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;
						$f_name="PROFORMA INVOICE ".$po_pro_forma_inv_info->pro_forma_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".$company_name_tmp;
						$file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
						$download_url_c=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode($file_path.$po_pro_forma_inv_info->po_custom_proforma.'#'.$f_name);
						
						if($po_register_info->proforma_type=='S'){ 
							//$download_url=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_pro_forma_inv/'.$po_pro_forma_inv_info->lead_opportunity_wise_po_id);
							$download_url=$download_url_s;
						}
						else
						{
							//$company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;
							//$f_name="PROFORMA INVOICE ".$po_pro_forma_inv_info->pro_forma_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".$company_name_tmp;
							//$file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
							//$download_url=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode($file_path.$po_pro_forma_inv_info->po_custom_proforma.'#'.$f_name);
							$download_url=$download_url_c;
						}						
						?>	 
						<a href="JavaScript:void(0)" data-actiontype="pfi_download" data-actionurl="<?php echo $download_url;?>" data-actionurl_s="<?php echo $download_url_s;?>" data-actionurl_c="<?php echo $download_url_c;?>" class="btn btn-primary txt-upper pro-form-preview-bt po_pro_forma_invoice_submit" id="po_pro_forma_invoice_download"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download</a></li>
                  <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt pull-right po_pro_forma_invoice_submit" id="" data-actiontype="pfi_save_and_continue" data-actionurl="">Save & Continue <i class="fa fa-floppy-o" aria-hidden="true"></i></a></li>
                  <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt pull-right skip_to_next" id="">Skip & Next <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></li>
               </ul>
            </div>
            
         </div>
      </div>
   </div>
   <!-- step 3 end -->
   <!-- step 4 start -->
   <div class="form-steps" id="po_div_4" style="display: none;">
      <h2>Generate Invoice</h2>
      <div class="form-group">
         <div class="row">
            <div class="col-md-6">
               <div class="autofit">
                  <input type="text" name="" class="default-input auto-width big-txt" value="Tax Invoice" disabled="">
               </div>
            </div>
            <div class="col-md-6 text-right">
               <span class="g-txt lh-40 bl-txt">PO Amount: <?php echo $opportunity_data->currency_code; ?> <?php echo number_format(round($opportunity_data->deal_value_as_per_purchase_order),2); ?><small class="text-danger">
                     <?php echo ($po_tds_percentage)?'<b>('.$po_tds_percentage.'% TDS Applied)</b> ':''; ?>
                  </small></span>
            </div>
         </div>
      </div>

      <div class="form-group">
         <div class="row">
            <div class="col-md-8 d-flex">
               <div class="full-width">
                  <input type="radio" id="invoice_type_s" name="invoice_type" class="default-radio"  value="S" <?php echo ($po_inv_info->invoice_type=='S')?'checked=""':''; ?>>
                  <label for="invoice_type_s" class="mbt-10">Generate PDF Invoice</label>
               </div>
               <div class="full-width">
                  <input type="radio" id="invoice_type_c" name="invoice_type" class="default-radio" value="C" <?php echo ($po_inv_info->invoice_type=='C')?'checked=""':''; ?>>
                  <label for="invoice_type_c" class="mbt-10">Upload Custom Invoice</label>
               </div>
            </div>

         </div>
      </div>      

      <div class="form-group">
         <div class="payment-loop content-equel btb">
            <div class="row">
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-title">Invoice No:<span class="red">*</span></label>
                     <div class="full-width">
                        <input type="text" class="date-input  invoice_number" name="po_inv_no" id="po_inv_no" placeholder="" value="<?php echo ($po_inv_info->invoice_no)?$po_inv_info->invoice_no:$system_invoice_no; ?>" >
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-title">Invoice Date:<span class="red">*</span></label>
                     <div class="full-width"><input type="text" class="date-input input_date" name="po_inv_date" id="po_inv_date" placeholder="" value="<?php echo date_db_format_to_display_format($po_inv_info->invoice_date); ?>" readonly="true"><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date"></div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-title">Due Date:</label>
                     <div class="full-width"><input type="text" class="date-input input_date" name="po_inv_due_date" id="po_inv_due_date" placeholder="" value="<?php echo date_db_format_to_display_format($po_inv_info->due_date); ?>" ><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date"></div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="auto-row">
                     <label class="blue-title">Exp. Delivery Date:</label>
                     <div class="full-width"><input type="text" class="date-input input_date" name="po_inv_expected_delivery_date" id="po_inv_expected_delivery_date" placeholder="" value="<?php echo date_db_format_to_display_format($po_inv_info->expected_delivery_date); ?>" ><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div id="invoice_type_c_div" style="display: none;">
         <div class="form-group custom_invoice">
            <div class="row">
               <div class="col-md-6">
                  <label class="small-title">Upload Invoice <small class="red">(PDF file only)</small></label>
                  <input class="form-control  po_custom_invoice" name="po_custom_invoice" id="po_custom_invoice" type="file"  accept="application/pdf" data-existing="<?php echo $po_inv_info->po_custom_invoice; ?>">
               </div>
               <?php                
                ?>
            <div class="col-md-6 text-left" style="margin-top: 10px;">
               <label class="small-title">&nbsp;</label>
               <div class="full-width" id="po_custom_invoice_div">
                  <?php
                  if($po_inv_info->po_custom_invoice!=''){
                  $company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person; 

                  $f_name="INVOICE ".$po_inv_info->invoice_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".$company_name_tmp;
                  $file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
                  ?>
                  <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/download_po/<?php echo base64_encode($file_path.$po_inv_info->po_custom_invoice.'#'.$f_name);?>" title="click to download"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download </a>
                  <a href="JavaScript:void(0);" class=" del_po_custom_invoice" data-lowp="<?php echo $lowp; ?>" data-id="<?php echo $po_inv_info->id; ?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
                  <?php } ?>
               </div>
            </div>
            
            </div>
         </div>
      </div>

      <div id="invoice_type_s_div" style="display: block;">
         <div class="form-group pdf_invoice">
            <div class="row">
               <div class="col-md-4">
                  <label class="blue-title">Bill From:</label>
                  <textarea class="basic-wysiwyg-editor" name="po_inv_bill_from" id="po_inv_bill_from"  style="height: 300px;"><?php echo $po_inv_info->bill_from; ?></textarea>
               </div>
               <div class="col-md-4">
                  <label class="blue-title">Bill To:</label>
                  <textarea class="basic-wysiwyg-editor" name="po_inv_bill_to" id="po_inv_bill_to"  style="height: 300px;"><?php echo ($po_inv_info->invoice_no)?$po_inv_info->bill_to:$curr_customer_bill_to; ?></textarea>
               </div>
               <div class="col-md-4">
                  <label class="blue-title">
                  <label class="check-box-sec fl">
                  <input class="styled-checkbox" type="checkbox" value="Y" name="po_inv_is_ship_to_available" id="po_inv_is_ship_to_available" <?php echo ($po_inv_info->ship_to)?'checked':'';?>>
                  <span class="checkmark"></span>
                  </label>
                  &nbsp;&nbsp;Ship To:
                  </label>
                  <textarea class="basic-wysiwyg-editor" name="po_inv_ship_to" id="po_inv_ship_to"  style="height: 300px;"><?php echo $po_inv_info->ship_to; ?></textarea>
               </div>
            </div>
         </div>
         
         
         <div class="form-group pdf_invoice">
         <div class="row">
         <div class="col-md-12">
            <div id="po_inv_product_list" style="width: 100%; clear: both;margin-top: 15px;display: inline-block;">
               <table class="table quotation-table table-borderless">
                  <thead>
                     <tr>
                        <th width="25%">Product Description</th>
                        <th class="text-center">
                           Unit Price <br>
                           <select id="change_currency_type_inv" class="form-control color-select small-select width-60" name="change_currency_type_inv">
                              <option value="">Select</option>
                              <?php foreach($currency_list as $currency_data) { ?>
                              <option value="<?php echo $currency_data->id;?>" <?php if($po_inv_info->currency_type==$currency_data->code){ ?>selected="selected"
                                 <?php } ?>>
                                 <?php echo $currency_data->code;
                                    ?>
                              </option>
                              <?php }  ?>
                           </select>
                        </th>
                        <!-- <th class="text-center">Unit</th> -->
                        <th class="text-center">Quantity</th>
                        <th class="text-center">
                           Discount<br>
                           <select id="is_discount_p_or_a_inv" class="form-control color-select small-select width-60" name="is_discount_p_or_a_inv" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">
                              <option value="P" <?php if($inv_prod_list[0]->is_discount_p_or_a=='P'){echo'SELECTED';} ?>>%</option>
                              <option value="A" <?php if($inv_prod_list[0]->is_discount_p_or_a=='A'){echo'SELECTED';} ?>>Amt.</option>
                           </select>
                        </th>
                        <th class="text-center">GST (%)</th>
                        <th class="text-center" width="14%">Net Amount</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $is_product_image_available='N';
                        $is_product_brochure_available='N'; 
                        $img_flag=0;
                        $brochure_flag=0;                  
                        if(count($inv_prod_list))
                        {         
                          $i=0;
                          $sub_total=0;
                          $s=1;
                          $discount=0;
                          $item_gst_per=0;
                          $item_sgst_per=0;
                          $item_cgst_per=0;
                          $total_price=0;
                          $total_discounted_price=0;
                          $total_tax_price=0;
                          foreach($inv_prod_list as $output)
                          {
                            $item_gst_per= $output->gst;
                            $item_sgst_per= ($item_gst_per/2);
                            $item_cgst_per= ($item_gst_per/2); 
                            $item_is_discount_p_or_a=$output->is_discount_p_or_a; 
                            $item_discount=$output->discount; 
                            $item_unit=$output->unit;
                            $item_price=($output->price/$item_unit);
                            $item_qty=$output->quantity;
                        
                            $item_total_amount=($item_price*$item_qty);
                            if($item_is_discount_p_or_a=='A'){
                              $row_discount_amount=$item_discount;
                            }
                            else{
                              $row_discount_amount=$item_total_amount*($item_discount/100);
                            }
                        
                            
                            $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
                        
                            $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                            $sub_total=$sub_total+$row_final_price;
                        
                            $total_price=$total_price+$item_total_amount;
                            $total_discounted_price=$total_discounted_price+$row_discount_amount;
                            $total_tax_price=$total_tax_price+$row_gst_amount;
                        
                            if($output->image!='' && $img_flag==0)
                            {
                              $is_product_image_available='Y';
                              $img_flag=1;
                            }
                            if($output->brochure!='' && $brochure_flag==0)
                            {
                              $is_product_brochure_available='Y';
                              $brochure_flag=1;
                            }
                        
                          ?>
                          <tr class="spacer"><td colspan="6"></td></tr>
                     <tr id="inv_tr_<?php echo $output->id;?>">
                        <td>
                           <textarea class="basic-wysiwyg-editor" id="<?php echo 'po_inv_product_name#'.$output->id;?>" style="height: 300px;"><?php echo $output->product_name; ?></textarea>
                           <!-- <input type="text" class="default-input calculate_inv_price_update" name="" id="" value="<?php echo $output->product_name; ?>" data-field="product_name" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>"  data-inv_id="<?php echo $output->po_invoice_id; ?>"/> -->
                           <!-- <span class="inv_unit_type"><?php //echo ($output->product_sku)?'( Code: '.$output->product_sku.' )':'';?></span> -->
                        </td>
                        
                        <td>
                           <div class="padding0 d-flex">
                              <input type="text" class="default-input calculate_inv_price_update double_digit width-80" id="" name="" value="<?php echo $output->price;?>" data-field="price" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
                              <span class="per-span">Per</span>
                              <input type="text" class="default-input calculate_inv_price_update only_natural_number_noFirstZero width-60 ml-10" name="" id="" value="<?php echo $output->unit?>" data-field="unit" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" style="min-width: 50px;" />
                              
                              <select id="" class="default-select width-80 ml-10 calculate_inv_price_update" name="" data-field="unit_type" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-pfi_id="<?php echo $output->po_pro_forma_invoice_id; ?>">
                                 <option value="">Select</option>
                                 <?php foreach($unit_type_list as $unit_type) { ?>
                                 <option value="<?php echo $unit_type->type_name;?>" <?php if($output->unit_type==$unit_type->type_name){ ?>selected="selected"
                                    <?php } ?>>
                                    <?php echo $unit_type->type_name;
                                       ?>
                                 </option>
                                 <?php }  ?>
                              </select>
                           </div>
                        </td>
                        <td>
                           <input type="text" class="default-input calculate_inv_price_update double_digit width-80" id="" name="" value="<?php echo $output->quantity; ?>"  data-field="quantity" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>"   />  
                        </td>
                        <td>
                           <input type="text" class="default-input calculate_inv_price_update double_digit width-60" id="" name="" value="<?php echo $output->discount; ?>"  data-field="discount" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>"   />  
                        </td>
                        <td>
                           <div class="form-group col-md-12 padding0">
                              <input type="text" class="default-input calculate_inv_price_update double_digit width-60" id="" name="" value="<?=ceil($output->gst);?>" data-field="gst" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>"  />
                           </div>
                        </td>
                        <td>
                           <div class="form-group col-md-12 padding0" align="center">
                              <!-- <input type="hidden" name="inv_currency_type[]" value="<?=$output->currency_type;?>" id="inv_currency_type_update_<?=$i;?>" class="form-control"/> -->
                              <span class="" id="inv_total_sale_price_<?=$output->id;?>"><?php echo number_format($row_final_price,2);?></span>
                           </div>
                         
                           <a href="JavaScript:void(0)" class="del_inv_product" data-id="<?=$output->id;?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" data-pid="<?=$output->product_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
                        </td>
                     </tr>
                     <tr class="spacer"><td colspan="6"></td></tr>
                     <?php
                        $s++;
                        $i++;
                        }
                        }
                        ?>
                     <?php if(count($inv_additional_charges_list)){?>
                     <?php 
                        $j=0;
                        foreach($inv_additional_charges_list AS $charge)
                        {
                          $i++; 
                          $item_gst_per= $charge->gst;
                          $item_sgst_per= ($item_gst_per/2);
                          $item_cgst_per= ($item_gst_per/2);
                          $item_is_discount_p_or_a=$charge->is_discount_p_or_a;  
                          $item_discount_per=$charge->discount; 
                          $item_price= $charge->price;
                          $item_qty=1;
                        
                          $item_total_amount=($item_price*$item_qty);
                          if($item_is_discount_p_or_a=='A'){
                            $row_discount_amount=$item_discount_per;
                          }
                          else{
                            $row_discount_amount=$item_total_amount*($item_discount_per/100);
                          }
                          // $row_discount_amount=$item_total_amount*($item_discount_per/100);
                          $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
                        
                          $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                          $sub_total=$sub_total+$row_final_price; 
                        
                          $total_price=$total_price+$item_total_amount;
                          $total_tax_price=$total_tax_price+$row_gst_amount;
                          $total_discounted_price=$total_discounted_price+$row_discount_amount;
                        ?>    
                     <tr id="inv_tr_additional_charge_<?php echo $charge->id;?>" class="<?php echo ($j%2==0)?'event':'odd'; ?>">
                        <td>
                           <textarea class="basic-wysiwyg-editor" id="<?php echo 'po_inv_additional_charge_name#'.$charge->id;?>" style="height: 300px;"><?php echo $charge->additional_charge_name; ?></textarea>
                           <!-- <input type="text" class="default-input calculate_inv_additional_charges_price_update " value="<?php echo $charge->additional_charge_name; ?>" data-field="additional_charge_name" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" /> -->
                           <?php //echo $charge->additional_charge_name; ?>
                        </td>
                        <td>
                           <div class="padding0 d-flex">
                           <input type="text" class="default-input calculate_inv_additional_charges_price_update double_digit width-80" value="<?php echo $charge->price; ?>" data-field="price" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
                           <span class="per-span">Per</span> 
                           <input type="text" class="default-input width-60 ml-10" value="-" readonly="true" />
                           <input type="text" class="default-select width-80 ml-10 " value="-" readonly="true" />
                        </div>
                        </td>
                        
                        <td>
                           <input type="text" class="default-input width-80" value="-" readonly="true" />
                        </td>
                        <td>    
                           <input type="text" class="default-input calculate_inv_additional_charges_price_update double_digit inv_additional_charges_discount width-60" value="<?php echo $charge->discount; ?>"  data-field="discount" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
                        </td>
                        <td>
                           <input type="text" class="default-input calculate_inv_additional_charges_price_update double_digit inv_additional_charges_gst width-60" value="<?php echo $charge->gst; ?>" data-field="gst" data-id="<?php echo $charge->id; ?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" />
                        </td>
                        <td align="center">
                           <span class="" id="inv_additional_charges_total_sale_price_<?php echo $charge->id; ?>"><?php echo number_format($row_final_price,2); ?></span>
                           
                           <a href="JavaScript:void(0)" class="del_inv_additional_charges_update" data-id="<?php echo $charge->id;?>" data-inv_id="<?php echo $output->po_invoice_id; ?>" data-pid="<?=$output->product_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
                        </td>
                     </tr>
                     <tr class="spacer"><td colspan="6"></td></tr>
                     <?php $j++; } ?>
                     <?php } ?>                     
                     <tr>
                        <td colspan="5" style="text-align:right;" class="back_border_total bt">Total Net Amount</td>
                        <td colspan="1" class="back_border_total text-center">
                           <span id="inv_total_deal_value" class="bt"><?php echo number_format($sub_total,2);?></span>
                           <input type="hidden" name="inv_deal_value" id="inv_deal_value" value="<?=number_format($sub_total,2);?>">
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_product_to_inv" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Product </a></h4>
                  <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_additional_charges_to_inv" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Additional Charges </a></h4>
                  <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_new_row_inv" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add New Row</a></h4>
               </div>
               <div class="col-md-6">
                  <table class="grand-summery table-borderless" width="100%" border="0" cellpadding="0" cellspacing="0">
                     <tbody>
                        <tr>
                           <td width="50%"></td>
                           <td width="50%"><b class="bt">Details (<span class="inv_currency_code_div"><?php echo $po_inv_info->currency_type; ?></span>)</b></td>
                        </tr>
                        <tr>
                           <td>Total Gross Amount</td>
                           <td><span id="inv_total_price"><?php echo number_format($total_price,2); ?></span></td>
                        </tr>
                        <?php //if($is_discount_available=='Y'){ ?>
                        <tr>
                           <td>Total Discount</td>
                           <td><span id="inv_total_discount"><?php echo number_format($total_discounted_price,2); ?></span></td>
                        </tr>
                        <?php //} ?> 
                        <?php 
                        //if($is_tax_show=='Y'){ 
                        /*
                        ?>
                        <tr>
                           <td>Total Tax</td>
                           <td><span id="inv_total_tax"><?php echo number_format($total_tax_price,2); ?></span></td>
                        </tr>
                        <?php 
                        */
                        //} 
                        ?>
                        <?php 
                        if($is_same_state=='Y')
                        { 
                           $inv_sgst=($total_tax_price/2);
                           $inv_cgst=($total_tax_price/2);
                        ?>
                        <tr>
                           <td>SGST</td>
                           <td><span id="inv_total_sgst"><?php echo number_format($inv_sgst,2); ?></span></td>
                        </tr>
                        <tr>
                           <td>CGST</td>
                           <td><span id="inv_total_cgst"><?php echo number_format($inv_cgst,2); ?></span></td>
                        </tr>
                        <?php 
                        } 
                        else
                        {
                        ?>
                        <tr>
                           <td><?php echo ($is_state_missing=='Y')?'GST':'IGST'; ?></td>
                           <td><span id="inv_total_tax"><?php echo number_format($total_tax_price,2); ?></span></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                           <td>Payable amount (Round Off)</td>
                           <td><span id="inv_grand_total_round_off"><?php echo number_format(round($sub_total),2); ?></span></td>
                        </tr>
                        <tr>
                           <td colspan="2"><b><span id="inv_number_to_word_final_amount"><strong><?php echo number_to_word(round($sub_total)); ?></strong></span> (<span class="inv_currency_code_div"><strong><?php echo $po_inv_info->currency_type; ?></strong></span>)</b></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         </div>
         </div>
         <div class="form-group pdf_invoice">
         <div class="row">
         <div class="col-md-12">
            <label class="blue-title">Bank Detail:</label>
         </div>
         <div class="col-md-6">
            
            <textarea class=" basic-wysiwyg-editor" name="po_inv_bank_detail_1" id="po_inv_bank_detail_1" placeholder="Bank Detail 1"><?php echo $po_inv_info->bank_detail_1; ?></textarea>
         </div>
         <div class="col-md-6">

            <textarea class=" basic-wysiwyg-editor" name="po_inv_bank_detail_2" id="po_inv_bank_detail_2" placeholder="Bank Detail 2"><?php echo $po_inv_info->bank_detail_2; ?></textarea>
         </div>
         </div>
         </div>
         <div class="form-group row pdf_invoice">
         <div class="row">
         <div class="col-md-12">
            <label class="blue-title">Terms & Conditions/Comments:</label>
            <textarea class=" basic-wysiwyg-editor" name="po_inv_terms_conditions" id="po_inv_terms_conditions"><?php echo $po_inv_info->terms_conditions; ?></textarea>
         </div>
         </div>
         </div>
         <div class="form-group row pdf_invoice">
         <div class="row">
         <div class="col-md-12">
            <label class="blue-title">Additional Note:</label>
            <textarea class=" basic-wysiwyg-editor" name="po_inv_additional_note" id="po_inv_additional_note"><?php echo $po_inv_info->additional_note; ?></textarea>
         </div>
         </div>
         </div>
         <div class="<?php if($is_show_digital_signature_checked=='Y'){ echo'form-group';} ?> pdf_invoice">
            <div class="row" <?php if($is_show_digital_signature_checked=='N'){ echo'style="display:none;"';} ?>>
            <div class="col-md-12">
               <label class="check-box-sec fl">
               <input class="styled-checkbox" type="checkbox" value="Y" name="po_inv_is_digital_signature_checked" id="po_inv_is_digital_signature_checked" <?php echo ($po_inv_info->is_digital_signature_checked=='Y')?'checked':'';?>>
               <span class="checkmark"></span>
               </label>&nbsp;&nbsp;<b>Digital Signature</b>
            </div>
            </div>
         </div>
         <?php
         if($po_inv_info->is_digital_signature_checked=='Y')
         {
           $po_inv_show_thanks_and_regards='N';
           $po_inv_show_signature='Y';
         }
         else
         {
           $po_inv_show_thanks_and_regards='Y';
           $po_inv_show_signature='N';
         }    
         ?>
         <div class="form-group row pdf_invoice">
            <div class="row">
               <div class="col-md-6">
                  <div <?php if($po_inv_show_signature=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> class="po_inv_name_of_authorised_signature_div"><img src="<?php echo assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$company['digital_signature']; ?>" height="50"></div>
                  <label class="blue-small-title" id="po_inv_digital_signature_title"><?php echo ($po_inv_info->is_digital_signature_checked=='Y')?'Name of authorized signatory':'Thanks & Regards';?>:</label>
                  <span <?php if($po_inv_show_signature=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> id="po_inv_name_of_authorised_signature_div" class="po_inv_name_of_authorised_signature_div">
                  <textarea class=" basic-wysiwyg-editor" name="po_inv_name_of_authorised_signature" id="po_inv_name_of_authorised_signature">
                     <?php echo ($po_inv_info->name_of_authorised_signature)?$po_inv_info->name_of_authorised_signature:$company['authorized_signatory']; ?></textarea></span>
                  <span <?php if($po_inv_show_thanks_and_regards=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> id="po_inv_thanks_and_regards_div">
                  <textarea class="basic-wysiwyg-editor" name="po_inv_thanks_and_regards" id="po_inv_thanks_and_regards" ><?php echo $po_inv_info->thanks_and_regards; ?></textarea></span>
               </div>
            </div>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-md-12">
            <ul class="justify-content-between">
               <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper prev-bt pull-left skip_to_prev"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Previous</a></li>
               <?php //if($po_register_info->invoice_type=='S'){ ?>
               <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper pro-form-preview-bt po_invoice_submit" data-actiontype="inv_preview" data-actionurl="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/preview_invoice/'.$po_pro_forma_inv_info->lead_opportunity_wise_po_id);?>"><i class="fa fa-eye" aria-hidden="true"></i> Preview</a></li>
               <?php //} ?>
               <li>

                  <a href="JavaScript:void(0);" class="btn btn-primary txt-upper pro-form-preview-bt po_invoice_submit" data-lowp="<?php echo $po_pro_forma_inv_info->lead_opportunity_wise_po_id; ?>" data-actiontype="inv_send" data-actionurl=""><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</a></li>
               <li>
                  <?php  
					$download_url_s=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_invoice/'.$po_inv_info->lead_opportunity_wise_po_id);
					
					$company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;
					$f_name="INVOICE ".$po_inv_info->invoice_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".strtoupper($company_name_tmp);
					$file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
					$download_url_c=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode($file_path.$po_inv_info->po_custom_invoice.'#'.$f_name);
                 
				 if($po_register_info->invoice_type=='S')
                 { 
					$download_url=$download_url_s;
                     //$download_url=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_invoice/'.$po_inv_info->lead_opportunity_wise_po_id);
                 }
                 else{
					 $download_url=$download_url_c;
                     //$company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;

                     //$f_name="INVOICE ".$po_inv_info->invoice_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".strtoupper($company_name_tmp);
                     //$file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
                     //$download_url=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode($file_path.$po_inv_info->po_custom_invoice.'#'.$f_name);
                 }
                 ?>
                  <a href="JavaScript:void(0);" class="btn btn-primary txt-upper pro-form-preview-bt po_invoice_submit" data-actiontype="inv_download" data-actionurl="<?php echo $download_url;?>" data-actionurl_c="<?php echo $download_url_c;?>" data-actionurl_s="<?php echo $download_url_s;?>"   ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download</a></li>
               <li><a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt pull-right po_invoice_submit" id="" data-actiontype="inv_save_and_continue" data-actionurl="">Save & Close <i class="fa fa-floppy-o" aria-hidden="true"></i></a></li>
            </ul>           
         </div>
      </div>
   </div>
   <!-- step 4 end -->
</div>
</div>
</div>
</div>
<?php  ?>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
/*
$("body").on("change","#currency_type",function(e){
var id=$(this).val();
var code=$(this).find("option:selected").attr('data-code');
$("#dv_curr_code").html('<option>'+code+'</option>');
});

$(".double_digit").keydown(function(e) {
debugger;
var charCode = e.keyCode;
if (charCode != 8) {
//alert($(this).val());
if (!$.isNumeric($(this).val()+e.key)) {
   return false;
}
}
return true;
});


// =========================================================
// CUSTOM FILE UPLOAD

$('.custom_upload input[type="file"]').change(function(e) { 

var uphtml = '';


for (var i = 0; i < e.target.files.length; i++)
{

uphtml += '<div class="fname_holder">';
uphtml += '<span>'+e.target.files[i].name+'</span>';
uphtml += '<a href="lead_attach_file_'+i+'" data-filename="'+e.target.files[i].name+'" class="file_close"><i class="fa fa-times" aria-hidden="true"></i></a>';
uphtml += '</div>';

}        
$('.upload-name-holder').css({'display':'block'}).html(uphtml);

});
$("body").on("click",".file_close",function(e){
event.preventDefault();
var remove_file_name=$(this).attr("data-filename");
var storedFiles=[];
storedFiles=$('#po_upload_file')[0].files;
var remove_index=0;        
for(var i=0;i<storedFiles.length;i++) 
{
if(storedFiles[i].name === remove_file_name) 
{
 remove_index=i;
 break;
}            
}              
$(this).parent().remove(); 
const file = document.querySelector('#po_upload_file'); 
file.value = '';
});

// CUSTOM FILE UPLOAD
// =========================================================



$("body").on("blur","#deal_value_as_per_purchase_order",function(e){
var deal_value=parseFloat($("#deal_value").val());
var deal_value_as_per_purchase_order=parseFloat($("#deal_value_as_per_purchase_order").val());    

if($("#deal_value").val()==0)
{
$("#deal_value_display").val((deal_value_as_per_purchase_order)?deal_value_as_per_purchase_order:0);
var diff=(deal_value_as_per_purchase_order-deal_value_as_per_purchase_order);
$("#diff_value").text((Math.round(diff))?Math.round(diff):0);
}
else
{
var diff=(deal_value_as_per_purchase_order-deal_value);
$("#diff_value").text(Math.round(diff));
}
});
$('input.mail-input').each(function( index ) {
//console.log( index + ": " + $( this ).text() );
$(this).attr('size', $(this).val().length);
});

$('input.mail-input:not(.auto-w)').each(function( index ) {
//console.log( index + ": " + $( this ).text() );
$(this).attr('size', $(this).val().length);
});


*/

//====================================================================
// Namutal number
$('.invoice_number').keyup(function(e)
{ 
    if (/\D/g.test(this.value))
       {
         // Filter non-digits from input value.
         this.value = this.value.replace(/\D/g, '');
       }                 
});
// Namutal number
//====================================================================



});
</script>
<script src="<?php echo base_url();?>assets/js/custom/lead/po_upload_lead.js"></script>