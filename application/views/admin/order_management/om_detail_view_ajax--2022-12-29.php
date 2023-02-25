<?php
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
$payment_type='';
if($po_register_info->payment_type=='F')
{
    $payment_type='Full Payment';
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

    $deal_value=$f_amount;
}
else if($po_register_info->payment_type=='P')
{
    $payment_type='Part Payment';
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

    $tmp_amt=0;
    if(count($p_amount))
    {
        foreach($p_amount AS $amt)
        {
            $tmp_amt=$tmp_amt+$amt;
        }
    }
    $deal_value=$tmp_amt;
}
else
{
  $payment_type='N/A';
  $deal_value=$po_register_info->deal_value_as_per_purchase_order;
}

$pr_currency_code=$company['default_currency_code'];
$pr_total_amount=0;
$payment_receivedm_arr=array();
$payment_received_log=$po_register_info->payment_received_log;
$payment_received_log_arr=explode(",", $payment_received_log);
if(count($payment_received_log_arr))
{
    foreach($payment_received_log_arr AS $pr)
    {
        $pr_arr=explode("#", $pr);

        $pr_received_date=$pr_arr[0];
        $pr_currency_type=$pr_arr[1];
        $pr_amount=$pr_arr[2];
        $pr_payment_mode_id=$pr_arr[3];
        $pr_payment_mode=$pr_arr[4];
        $pr_narration=$pr_arr[5];
        $pr_created_at=$pr_arr[6];
        $payment_receivedm_arr[]=array(
                'received_date'=>$pr_received_date,
                'currency_type'=>$pr_currency_type,
                'amount'=>$pr_amount,
                'payment_mode_id'=>$pr_payment_mode_id,
                'payment_mode'=>$pr_payment_mode,
                'narration'=>$pr_narration,
                'created_at'=>$pr_created_at
                );
        $pr_total_amount=$pr_total_amount+$pr_amount;
        $pr_currency_code=$pr_currency_type;
    }
}
$pr_balance_amount=($deal_value-$pr_total_amount);
?>
<input type="hidden" name="lowp" id="lowp" value="<?php echo $lowp; ?>">
<input type="hidden" id="po_tds_percentage_existing" name="po_tds_percentage_existing" value="<?php echo $po_register_info->po_tds_percentage; ?>">
<?php if($po_register_info->is_cancel=='Y'){ ?>
<div class="alert alert-danger">
  <h4><strong>Note: </strong> This deal has been cancelled by <?php echo $po_register_info->cancelled_by; ?> on <?php echo date_db_format_to_display_format($po_register_info->cancelled_date); ?>.</h4>
</div>
<?php } ?>  
<div class="card-block">
    <div class="row">
        <div class="col-md-12">
            <span class="badge bg-success"><font color="black">Priority:</font> <?php echo get_om_priority_name_by_id($pi_tag_row['priority']); ?></span>
            <div class="lead-status">
                <?php                 
                $completed_stage_ids_arr=explode(",",$completed_stage_ids);
                ?>
                <div class="order-status-container mobile-up-down" style="width: 100%;">
                    <?php 
                    if(count($all_stage_rows))
                    { 
                        foreach($all_stage_rows AS $stage)
                        { 
                            if(in_array($stage['id'],$completed_stage_ids_arr)){
                                $is_completed="Y";
                            }
                            else{
                                $is_completed="N";
                            }
                        ?>
                        <div class="status-item done <?php echo ($is_completed=='N')?'om-in-active-stage':''; ?>">
                            <div class="status-circle"></div>
                            <div class="status-text"><?php echo $stage['name']; ?></div>
                        </div>
                        <?php 
                        }
                    } 
                    ?>
                </div>
            </div>
        </div> 
    </div> 
    <hr>
    <div class="row">        
        <div class="col-md-9"> 
                              
            <div class="border-block">
                <div class="tholder max-w-660--">
                    <table class="table order-details-border-table">                      
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Order ID</strong><br><?php echo ($po_register_info->id)?$po_register_info->id:'N/A'; ?>
                                </td>                               
                                <td>
                                    <strong>Date</strong><br><?php echo date_db_format_to_display_format($po_register_info->po_date); ?>
                                </td>                               
                                <td>
                                    <strong>Proforma No.</strong><br><?php echo ($po_register_info->pro_forma_no)?$po_register_info->pro_forma_no:'-'; ?>
                                </td>
                                <td>
                                    <strong>Proforma Date</strong><br><?php echo ($po_register_info->pro_forma_date)?date_db_format_to_display_format($po_register_info->pro_forma_date):'-'; ?>
                                </td>
                                <td>
                                    <strong>Lead ID</strong><br><?php echo $po_register_info->lid; ?>
                                </td>
                                <td>
                                    <strong>Lead Date</strong><br><?php echo date_db_format_to_display_format($po_register_info->l_enquiry_date); ?>
                                </td>
                                <td>
                                    <strong>Assigned to</strong><br><?php echo $po_register_info->cust_assigned_user; ?>
                                    
                                    <?php if($po_register_info->cust_assigned_user_mobile){ ?>
                                    <a class="badge bg-success show_tooltip" href="JavaScript:void(0);" title="<?php echo $po_register_info->cust_assigned_user_mobile; ?>" data-title="Mobile" data-mobile="<?php echo $po_register_info->cust_assigned_user_mobile; ?>"><i class="fa fa-phone" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if($po_register_info->cust_assigned_user_email){ ?>
                                    <a class="badge bg-success show_tooltip"  href="JavaScript:void(0);" title="<?php echo $po_register_info->cust_assigned_user_email; ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>                  
                        </tbody>
                   </table>
                </div>
            </div>
            <?php /* ?>
            <div class="border-block">
                <div class="value-title"><span>Deal Value:</span> <?php echo $po_register_info->po_currency_type_code; ?> <?php echo number_format($deal_value,2); ?></div>
                <div class="tholder max-w-540">
                    <table class="table order-details-border-table">
                      
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Payment Terms</strong><br>
                                    <?php echo $payment_type; ?>
                                </td>
                                <td>
                                    <strong>Payment Recived</strong><br><?php echo $pr_currency_code; ?> 
                                    <span id="po_payment_recived_div"><?php echo number_format($pr_total_amount,2); ?></span>
                                </td>
                                <td>
                                    <strong>Balance Payment</strong><br><span class="red"><?php echo $pr_currency_code; ?>  
                                    <font id="po_balance_payment_div"><?php echo number_format($pr_balance_amount,2); ?></font></span>
                                    (<a href="JavaScript:void(0)" class="view_payment_ledger">Update Payment Received</a>)
                                </td>
                                
                            </tr>
                         
                        </tbody>
                   </table>
                </div>
            </div>
            <?php */ ?>
            <div class="border-block">
                <div class="instructions-title"><span>Expected Delivery Date:</span> <?php echo ($po_register_info->pro_forma_expected_delivery_date)?date_db_format_to_display_format($po_register_info->pro_forma_expected_delivery_date):'N/A'; ?></div>
            </div>
            <?php if($po_register_info->comments){ ?>
            <div class="border-block">
                <div class="instructions-title"><span>Delivery Instructions:</span> <?php echo $po_register_info->comments; ?></div>
            </div>
            <?php } ?>

            <?php 
            /*if($po_register_info->po_tds_percentage)
            {
            ?>
            <form id="po_tds_certificate_frm">
            <div class="border-block">
                <div class="instructions-title"> 
                    <label class="uploaded-doc">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> 
                        <span>Click to Upload TDS Certificate</span>
                        <input type="file" name="po_tds_certificate" id="po_tds_certificate">
                    </label> <small class="text-danger">(PDF File Only)</small><small class="text-danger">
                     <b>(<?php echo $po_register_info->po_tds_percentage; ?>% TDS Deduction Applied)</b></small>
                    <div class="row"><div class="col-md-6" id="tds_certificate_div"></div></div>
                </div>
            </div>
            </form>
            <?php }*/ ?>
            <div class="border-block no-border">
                <div class="big-title">Download <i class="fa fa-download" aria-hidden="true"></i></div>
                <ul class="auto-ul">
                    <li>                        
                    <?php 
                    $is_quotation_exist='N';
                    if($po_register_info->q_is_extermal_quote=='N'){ ?>
                        <a class="set_history_from_link" data-pfi="<?php echo $pfi; ?>" data-history_text="The PDF quotation has been downloaded" href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$po_register_info->lo_id.'/'.$po_register_info->q_id);?>" target="_blank">Quotation</a>
                    <?php 
                    $is_quotation_exist='Y';
                    } 
                    ?>
                    <?php if($po_register_info->q_is_extermal_quote=='Y' && $po_register_info->q_file_name!=''){ ?>
                        <a class="set_history_from_link" data-pfi="<?php echo $pfi; ?>" data-history_text="The custom quotation has been downloaded" href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$po_register_info->lo_id.'/'.$po_register_info->q_id);?>" target="_blank">Quotation</a>
                    <?php 
                    $is_quotation_exist='Y';
                    } 
                    ?>
                    <?php
                    if($is_quotation_exist=='N')
                    {
                        ?>
                        <!-- <a href="JavaScript:void(0)" class="cicon_btn get_alert" data-text="Oops! There is no Quotation.">Quotation</a> -->
                    <?php
                    }
                    ?>
                    </li>
                    <?php if($po_register_info->is_cancel=='N'){ ?>
                        <?php if($po_register_info->file_name){ $file_path=$po_register_info->file_path.''.$po_register_info->file_name?>
                            <li><a class="set_history_from_link" data-pfi="<?php echo $pfi; ?>" data-history_text="The Purchase Order has been downloaded"   href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/app/download/'.$po_register_info->file_name.'/'.base64_encode($file_path));?>" class="">Purchase Order</a></li>
                        <?php } ?>
                    <?php } ?>
                    
                    <?php
                    $proforma_download_url='';
                    if($po_register_info->proforma_type=='S')
                    { 
                        $proforma_download_url=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_pro_forma_inv/'.$po_register_info->id);
                    }
                    else{
                        if($po_register_info->pro_forma_no){
                            $company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;

                            $f_name="PROFORMA INVOICE ".$po_register_info->pro_forma_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".$company_name_tmp;
                            $file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
                            $proforma_download_url=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode($file_path.$po_register_info->po_custom_proforma.'#'.$f_name);
                        
                        }
                    }
                    ?>
                    <?php if($proforma_download_url){ ?>
                    <li><a class="set_history_from_link" data-pfi="<?php echo $pfi; ?>" data-history_text="The Proforma Invoice has been downloaded" href="<?php echo $proforma_download_url; ?>" class="">Proforma Invoice</a></li>
                    <?php } ?>

                    <?php 
                    $inv_download_url='';
                    if($po_register_info->invoice_type=='S')
                    { 
                        $inv_download_url=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_invoice/'.$po_register_info->id);
                    }
                    else{
                        if($po_register_info->invoice_no){
                            $company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;

                            $f_name="INVOICE ".$po_register_info->invoice_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".strtoupper($company_name_tmp);
                            $file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
                            $inv_download_url=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode($file_path.$po_register_info->po_custom_invoice.'#'.$f_name);
                        
                        }
                    }
                    ?>
                    <?php if($inv_download_url){ ?>
                    <li><a class="set_history_from_link" data-pfi="<?php echo $pfi; ?>" data-history_text="The Invoice has been downloaded" href="<?php echo $inv_download_url; ?>" class="">Invoice</a></li>
                    <?php } ?>
                    <li><a href="JavaScript:void(0)" class="om_view_payment_ledger" data-lowp="<?php echo $po_register_info->id; ?>">Payment Ledger</a></li>
                    <li><a href="JavaScript:void(0);" data-leadid="<?php echo $po_register_info->lead_id; ?>" class="om_view_lead_history">Lead History</a></li>
                </ul>
            </div>
                         
        </div>
        <div class="col-md-3">
            <div class="grey-order-bg">
                <div class="top-order-bg">
                    <h1>Customer’s Details</h1>
                    <?php echo ($po_register_info->cust_company_name)?'<strong>'.$po_register_info->cust_company_name.'</strong><br>':''; ?>
                    <?php echo ($po_register_info->cust_contact_person)?''.$po_register_info->cust_contact_person.'<br>':''; ?>
                    <?php echo ($po_register_info->cust_mobile)?'Mobile: +'.$po_register_info->cust_mobile_country_code.'-'.$po_register_info->cust_mobile.'<br>':'';?>
                    <?php echo ($po_register_info->cus_email)?'Email: '.$po_register_info->cus_email.'<br>':'';?>
                    <?php 
                    $cust_website='';
                    if($po_register_info->cust_website)
                    {
                        $cus_website_prefix=http_or_https_check($po_register_info->cust_website);
                        if($cus_website_prefix=='')
                        {
                            $cust_website='http://'.$po_register_info->cust_website;
                        }
                        else
                        {
                            $cust_website=$po_register_info->cust_website;
                        }
                    }
                    if($cust_website!='')
                    {
                        $cust_website_arr=explode(',', $cust_website);
                        $i=1;
                        foreach($cust_website_arr AS $website)
                        {
                            
                            $website_tmp=$website;
                            if(count($cust_website_arr)>1)
                            {
                                echo ($website)?'Website '.$i.': <a href="'.$website_tmp.'" class="blue-link show_tooltip" target="_blank" title="'.$website_tmp.'">Visit</a><br>':'';
                            }
                            else
                            {
                                echo ($website)?'Website: <a href="'.$website_tmp.'" class="blue-link show_tooltip" target="_blank" title="'.$website_tmp.'">Visit</a><br>':'';
                            }
                            $i++; 
                        }
                    }                    
                    $location='';
                    if($po_register_info->cust_city_name || $po_register_info->cust_state_name || $po_register_info->cust_country_name)
                    {
                        $location .=($po_register_info->cust_city_name)?$po_register_info->cust_city_name.', ':'';
                        $location .=($po_register_info->cust_state_name)?$po_register_info->cust_state_name.', ':'';
                        $location .=($po_register_info->cust_country_name)?$po_register_info->cust_country_name.', ':'';
                    }
                    echo ($location)?'Location: '.rtrim($location,', ').'<br>':'';
                    ?> 
                    <?php echo ($po_register_info->cust_gst_number)?'<span>GST: '.$po_register_info->cust_gst_number.'</span>':'';?>
                </div>
                <div class="product_action">
                   <ul>
                        <li>
                            <?php if($po_register_info->cust_mobile!=''){ ?>
                            <?php 
                            if(count($c2c_credentials))
                            {
                            ?>
                            <a href="JavaScript:void(0)" class="cicon_btn <?php echo ($po_register_info->cust_mobile)?'set_c2c':''; ?>" data-leadid="<?php echo $po_register_info->lead_id;?>" data-cusid="<?php echo $po_register_info->cust_id; ?>" data-custmobile="<?php echo $po_register_info->cust_mobile; ?>" data-contactperson="<?php echo $po_register_info->cust_contact_person; ?>" data-usermobile="<?php echo $c2c_credentials['mobile']; ?>" data-userid="<?php echo $c2c_credentials['user_id']; ?>" ><img src="<?php echo assets_url(); ?>images/cicon1.png" title="Click to Call using API"></a>
                            <?php
                            }
                            else
                            {
                            ?>
                            <a href="JavaScript:void(0)" class="cicon_btn <?php echo ($po_register_info->cust_mobile)?'set_call_schedule_from_app':''; ?>" data-leadid="<?php echo $po_register_info->lead_id;?>" data-mobile="<?php echo $po_register_info->cust_mobile; ?>" data-contactperson="<?php echo $po_register_info->cust_contact_person; ?>"><img src="<?php echo assets_url(); ?>images/cicon1.png" title="Click to Call from LMSBABA app"></a>
                                <?php
                            }
                            ?>
                            <?php 
                            }
                            else
                            { 
                            ?>
                            <a href="JavaScript:void(0)" class="cicon_btn get_alert" data-text="Oops! There is no mobile number added to the company."><img src="<?php echo assets_url(); ?>images/cicon1-disabled.png" title="Mobile nummber is missing"></a>
                        <?php } ?>
                        </li>
                        <li>
                            <?php if($po_register_info->cust_mobile!='' && $po_register_info->cust_country_id!='0'){ 
                            if($po_register_info->cust_mobile_whatsapp_status==2)
                            {
                                $whatsapp_image='social-whatsapp-disabled.png';
                                $whatsapp_title='The number is not available in Whatsapp';
                            }
                            else
                            {
                                $whatsapp_image='social-whatsapp.png';
                                $whatsapp_title='Click to send Whatsapp message';
                            }
                            
                            ?>
                            <a href="JavaScript:void(0);" class="cicon_btn web_whatsapp_popup"  data-leadid="<?php echo $po_register_info->lead_id;?>" data-custid="<?php echo $po_register_info->cust_id;?>" title="<?php echo $whatsapp_title; ?>"><img src="<?php echo assets_url(); ?>images/<?php echo $whatsapp_image; ?>"></a>
                            <?php 
                            }
                            else
                            { 
                            ?>
                            <a href="JavaScript:void(0);" class="cicon_btn get_alert"  data-text="Oops! There is no mobile number added to the company."><img src="<?php echo assets_url(); ?>images/social-whatsapp-disabled.png" title="Mobile nummber is missing"></a>
                         <?php 
                            } 
                            ?>
                        </li>
                        <li>
                                            
                            <?php if($po_register_info->cust_email){ ?>                 
                            <a href="JavaScript:void(0)" class="cicon_btn open_cust_reply_box" data-leadid="<?php echo $po_register_info->lead_id;?>" data-custid="<?php echo $po_register_info->cust_id;?>"><img src="<?php echo assets_url(); ?>/images/cicon3.png"></a>
                        <?php }else{ ?>
                            <a href="JavaScript:void(0)" class="cicon_btn get_alert" data-leadid="<?php echo $po_register_info->lead_id;?>" data-custid="<?php echo $po_register_info->cust_id;?>" data-text="Oops! There is no email added to the company."><img src="<?php echo assets_url(); ?>/images/cicon3-disabled.png"></a>
                        <?php } ?>
                        </li>                   
                   </ul>
                   
 
                </div>
            </div>
        </div>

        <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <form id="omDocFrm" name="omDocFrm" class="">
                    <input type="hidden" name="proforma_invoice_id" id="proforma_invoice_id" value="<?php echo $pfi; ?>" />
                    <div class="border-block no-border">
                        <h2>Update Order</h2>
                        <div class="order_status_txt">
                                <div class="control-group">
                                        <div class="form-row">                                
                                            <div class="col-md-12">
                                                <label for="form_id">Form:</label>
                                                <select class="form-control" name="form_id" id="form_id">
                                                    <option value="">-- Select --</option>
                                                    <?php if(count($assigned_form_list)){ ?>
                                                        <?php foreach($assigned_form_list AS $form){ ?>
                                                        <option value="<?php echo $form['form_id']; ?>"><?php echo $form['name']; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-12" id="om_form_wise_fields_div"></div>                                
                                        </div>                    
                                </div> 
                        </div>               
                    </div>
                </form>

                <div class="border-block no-border">
                    <h2>Submitted Document</h2>
                    <div class="order_status_txt">                                                
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 sp-table-new" width="100%">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Sl. No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2" width="60%">Document</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="30%">Action</th>        
                                </tr>
                                </thead>
                                <tbody id="om_document_list_div"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">                
                <div class="border-block no-border">
                    <h2>Order History</h2>
                    <div class="width_full">            
                        <div id="om_history_div" class="lead_history_text">
                        <?php   
                        if(count($order_history)){                            
                            foreach($order_history as $history)
                            {
                            ?>      
                            <div class="one_p">
                                <p><b><?=$history['title'];?></b></p>
                                <p style="color:#212121">
                                    <b>Date:</b> 
                                    <?php  echo datetime_db_format_to_display_format_ampm($history['created_at']); ?> | 
                                    <b>Updated By:</b> <?php echo $history['updated_by'];?> | 
                                    <b>IP.</b> <?php echo $history['ip_address'];?>
                                    <?php if($history['source_name']){ ?>
                                    | 
                                    <b>Source:</b> <?php echo ($history['source_alias_name'])?$history['source_alias_name']:$history['source_name'];?>
                                    <?php } ?>
                                    <?php if($history['attach_file']){ ?>
                                        <?php
                                        $attach_file_arr=explode("|$|", $history['attach_file']); 
                                        echo'|';
                                        foreach($attach_file_arr AS $file)
                                        {
                                            $file_ext=end(explode(".",$file));
                                            if(strtolower($file_ext)=='mp3' || strtolower($file_ext)=='mp4')
                                            {
                                                $file_action_text='Play <i class="fa fa-play" aria-hidden="true"></i>';
                                            }
                                            else
                                            {
                                                $file_action_text='Download <i class="fa fa-download" aria-hidden="true"></i>';
                                            }
                                        ?>
                                    <b><a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download/<?php echo base64_encode($file); ?>" data-filepath="<?php echo assets_url();?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/company/lead/<?php echo $file; ?>"><?php echo $file_action_text; ?></b></a>&nbsp; 
                                    <?php }} ?>
                                </p>                            
                                <?php /* if($history['quotation_id']){ ?>
                                <p style="color:#212121">
                                    <b>Quotation:</b> <?php echo '#'.$history['quotation_id'].' - '.$history['quotation_title'];?></p>
                                <?php } */ ?>
                                
                                <p style="color:#212121">
                                    <b>Proforma No.:</b> <?php echo ($history['pro_forma_no'])?$history['pro_forma_no']:'N/A';?> |
                                    <b>Proforma Date:</b> <?php echo ($history['pro_forma_date'])?date_db_format_to_display_format($history['pro_forma_date']):'N/A';?> |
                                    <b>Proforma Due Date:</b> <?php echo ($history['pro_forma_due_date'])?date_db_format_to_display_format($history['pro_forma_due_date']):'N/A';?>                                
                                </p>
                                
                                <p style="color:#212121">
                                    <b>Comment:</b><br>
                                    <?php echo str_replace('#','For ',strip_tags($history['comment']));?>                            
                                </p>                            
                            </div>                                
                            <?php
                            }
                        }
                        else{
                        ?>
                        <div class="one_p">
                            <p>No History Found!</p>        
                        </div>
                        <?php
                        }
                        ?>                                    
                        </div>                                
                    </div>
                </div>                
            </div>
        </div>
        </div>
    </div>
</div>
<script src="<?php echo assets_url(); ?>js/jquery.datetimepicker.full.js" ></script>
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.datetimepicker.css"  />
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>

<script>    
$(document).ready(function(e){
   
    
    $( ".show_tooltip" ).tooltip({
      show: null,
      position: {
        my: "left top",
        at: "left bottom"
      },
      open: function( event, ui ) {
        ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
      }
    });


    $("body").on("click",".get_info_sm",function(e){
        var mobile=$(this).attr("data-mobile");        
        $("#common_view_modal_title_sm").text('Mobile');
        $('#rander_common_view_modal_html_sm').html(mobile);
        $('#rander_common_view_modal_sm').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('#PoPaymentLedgerModal').on('hide.bs.modal', function (e) {
        if ($('#OmDetailModal').hasClass('in')) {
            $("#OmDetailModal").css("display","block");            
        }
    });

    $("body").on("click",".om_view_payment_ledger",function(e){
		e.preventDefault();
        $("#OmDetailModal").css("display","none");
		var lowp=$(this).attr('data-lowp');             
		fn_rander_payment_view_popup(lowp);		
	});

    $('#lead_history_log_modal').on('hide.bs.modal', function (e) {
        if ($('#OmDetailModal').hasClass('in')) {
            $("#OmDetailModal").css("display","block");            
        }
    });
    
    $("body").on("click",".om_view_lead_history",function(e){
		e.preventDefault();
        $("#OmDetailModal").css("display","none");
		var lid=$(this).attr("data-leadid");        
        fn_open_lead_history(lid);	
	});
    
    

    if($("#proforma_invoice_id").val()){
        var piid=$("#proforma_invoice_id").val();
        fn_rander_document_list(piid);
    }
    // $("body").on("change","#form_id",function(e){
        
    //     var base_url=$("#base_url").val();
    //     var f_id=$(this).val();
    //     var proforma_invoice_id=$("#proforma_invoice_id").val();
    //     if(f_id==''){
    //         $('#om_form_wise_fields_div').html('');
    //         return false;
    //     }
    //     $.ajax({
    //         url: base_url + "order_management/om_form_wise_fields_view_rander_ajax",
    //         type: "POST",
    //         data: {
    //             'f_id':f_id,
    //             'proforma_invoice_id':proforma_invoice_id
    //         },
    //         async: true,            
    //         success: function(data) {
    //             result = $.parseJSON(data); 
                
    //             if(result.status=='success'){
    //                 $('#om_form_wise_fields_div').html(result.html);
    //             }
    //             else{
    //                 swal({
    //                     title: 'Oops!',
    //                     text: result.msg,
    //                     type: 'error',
    //                     showCancelButton: false
    //                     }, function() {

    //                 });
    //             }
                
                
    //         },
    //         error: function() {
    //             swal({
    //                     title: 'Something went wrong there!',
    //                     text: '',
    //                     type: 'danger',
    //                     showCancelButton: false
    //                 }, function() {

    //             });
    //         }
    //     });
    // });

    // $("body").on("click","#om_stage_wise_document_save_confirm",function(e){
        
    //     e.preventDefault();
        
    //     var base_url=$("#base_url").val();  
    //     var missing_name='';  
    //     jQuery('.required').each(function() {
    //         var currentElement = $(this);
    //         var value = currentElement.val(); 
    //         if(value==''){
    //             missing_name +=currentElement.attr('data-label')+' required.<br>';
                
    //         }
    //     });
    //     if(missing_name){
    //         swal({   
    //             title: "Oops!",  
    //             text: missing_name,
    //             html: true,
    //             type:'error' 
    //         });                            
    //         return false;
    //     }
       
    //     var str_tmp='';
    //     jQuery('.om_custom_form_field').each(function() {
            
    //         var currentElement = $(this);
    //         var type=currentElement.prop("type");
            
    //         if(type=='text' || type=='textarea' || type=='file'){
    //             var value = currentElement.val(); 
    //             var id=currentElement.attr('data-id'); 
    //             var name=currentElement.attr('name'); 
    //             str_tmp +=id+'~'+value+'~'+name+'!***!';
    //         }
    //         else{
    //             if(type=='radio'){
    //                 if(currentElement.is(':checked')) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     var name=currentElement.attr('name');
    //                     str_tmp +=id+'~'+value+'~'+name+'!***!';
    //                 }
    //             } 
    //             else if(type=='checkbox'){
    //                 if(currentElement.is(':checked')) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     var name=currentElement.attr('name');
    //                     str_tmp +=id+'~'+value+'~'+name+'!***!';
    //                 }
    //             }                
    //             else if(type=='select-one'){
    //                 if(currentElement.val()) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     var name=currentElement.attr('name');
    //                     str_tmp +=id+'~'+value+'~'+name+'!***!';
                        
    //                 }
    //             }
    //         }            
    //     });
    //     // alert(str_tmp);return false;
    //     $('form#omDocFrm').append('<input type="hidden" name="om_custom_form_field" value="'+str_tmp+'" />');
    //     $.ajax({
    //             url: base_url + "order_management/om_stage_wise_doc_submit",
    //             data: new FormData($('#omDocFrm')[0]),
    //             cache: false,
    //             method: 'POST',
    //             dataType: "html",
    //             mimeType: "multipart/form-data",
    //             contentType: false,
    //             cache: false,
    //             processData: false,                   
    //             beforeSend: function( xhr ) {                
    //                 $("#om_stage_wise_document_save_confirm").attr("disabled",true);
    //             },
    //             complete: function(){
    //                 $("#om_stage_wise_document_save_confirm").attr("disabled",false);
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);
    //                 alert(result.msg)
    //                 if(result.status=='success'){
    //                     swal({
    //                             title: 'Success!',
    //                             text: 'Record successfully saved',
    //                             type: 'success',
    //                             showCancelButton: false
    //                         }, function() {                                
    //                             $("#form_id option:first").prop("selected", "selected");
    //                             $("#om_form_wise_fields_div").html('');
    //                             var piid=$("#proforma_invoice_id").val();
    //                             fn_rander_document_list(piid);
    //                     });
    //                 }
    //                 else{
    //                     swal({
    //                             title: 'Oops!',
    //                             text: result.msg,
    //                             type: 'error',
    //                             showCancelButton: false
    //                         }, function() {  });
    //                 }
                    
                    

    //             },                    
    //     });
    // });

    // $('#rander_common_view_modal_lg').on('hide.bs.modal', function (e) {
    //     if ($('#OmDetailModal').hasClass('in')) {
    //         $("#OmDetailModal").css("display","block");            
    //     }
    // })

    // $("body").on("click",".document_view_popup",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');
    //     $.ajax({
    //       url: base_url + "order_management/document_view_ajax",
    //       type: "POST",
    //       data: {
    //           'id': id
    //       },
    //       async: true,
    //       success: function(data) {
    //             result = $.parseJSON(data);
    //             $("#OmDetailModal").css("display","none");
    //             $("#common_view_modal_title_lg").text(result.title);
    //             $('#rander_common_view_modal_html_lg').html(result.html);
    //             $('#rander_common_view_modal_lg').modal({
    //                 backdrop: 'static',
    //                 keyboard: false
    //             });
              
    //       },
    //       error: function() {
    //           swal({
    //                   title: 'Something went wrong there!',
    //                   text: '',
    //                   type: 'danger',
    //                   showCancelButton: false
    //               }, function() {

    //           });
    //       }
    //     });
    // });

    // $("body").on("click",".document_edit_popup",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');
    //     $.ajax({
    //       url: base_url + "order_management/document_edit_view_ajax",
    //       type: "POST",
    //       data: {
    //           'id': id
    //       },
    //       async: true,
    //       success: function(data) {
    //             result = $.parseJSON(data);
    //             $("#OmDetailModal").css("display","none");
    //             $("#common_view_modal_title_lg").text(result.title);
    //             $('#rander_common_view_modal_html_lg').html(result.html);
    //             $('#rander_common_view_modal_lg').modal({
    //                 backdrop: 'static',
    //                 keyboard: false
    //             });
              
    //       },
    //       error: function() {
    //           swal({
    //                   title: 'Something went wrong there!',
    //                   text: '',
    //                   type: 'danger',
    //                   showCancelButton: false
    //               }, function() {

    //           });
    //       }
    //     });
    // });

    // $("body").on("click","#om_stage_wise_document_edit_confirm",function(e){
    //     e.preventDefault();
    //     var base_url=$("#base_url").val();  
    //     var missing_name='';  
    //     jQuery('.required_edit').each(function() {
    //         var currentElement = $(this);
    //         var value = currentElement.val(); 
    //         if(value==''){
    //             missing_name +=currentElement.attr('data-label')+' required.<br>';
                
    //         }
    //     });
    //     if(missing_name){
    //         swal({   
    //             title: "Oops!",  
    //             text: missing_name,
    //             html: true,
    //             type:'error' 
    //         });                            
    //         return false;
    //     }
       
    //     var str_tmp='';
    //     jQuery('.om_custom_form_field_edit').each(function() {
            
    //         var currentElement = $(this);
    //         var type=currentElement.prop("type");
            
    //         if(type=='text' || type=='textarea' || type=='file'){
    //             var value = currentElement.val(); 
    //             var id=currentElement.attr('data-id'); 
    //             str_tmp +=id+'~'+value+'!***!';
    //         }
    //         else{
    //             if(type=='radio'){
    //                 if(currentElement.is(':checked')) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     str_tmp +=id+'~'+value+'!***!';
    //                 }
    //             } 
    //             else if(type=='checkbox'){
    //                 if(currentElement.is(':checked')) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     str_tmp +=id+'~'+value+'!***!';
    //                 }
    //             }                
    //             else if(type=='select-one'){
    //                 if(currentElement.val()) {
                    
    //                     var value = currentElement.val(); 
    //                     var id=currentElement.attr('data-id'); 
    //                     str_tmp +=id+'~'+value+'!***!';
                        
    //                 }
    //             }
    //         }            
    //     });
    //     // alert(str_tmp);return false;
    //     $('form#omDocEditFrm').append('<input type="hidden" name="om_custom_form_field_edit" value="'+str_tmp+'" />');
    //     $.ajax({
    //             url: base_url + "order_management/om_stage_wise_doc_submit_edit",
    //             data: new FormData($('#omDocEditFrm')[0]),
    //             cache: false,
    //             method: 'POST',
    //             dataType: "html",
    //             mimeType: "multipart/form-data",
    //             contentType: false,
    //             cache: false,
    //             processData: false,                   
    //             beforeSend: function( xhr ) { 
                    
    //             },
    //             complete: function(){
                    
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);
                    
    //                 if(result.status=='success'){

    //                     swal({
    //                             title: 'Success!',
    //                             text: 'Record successfully updated',
    //                             type: 'success',
    //                             showCancelButton: false
    //                         }, function() {
    //                             $("#rander_common_view_modal_lg").modal("hide");
    //                     });

                        
    //                 }
                    
    //             },                    
    //     });
    // });

    // $("body").on("click",".document_delete",function(e){
    //     var base_url=$("#base_url").val();
    //     var id=$(this).attr('data-id');

    //     swal({
    //             title: "Are you sure?",
    //             text: "You will not be able to recover the document!",
    //             type: "warning",
    //             showCancelButton: true,
    //             confirmButtonClass: 'btn-warning',
    //             confirmButtonText: "Yes, delete it!",
    //             closeOnConfirm: false
    //         }, function () {

    //             $.ajax({
    //                 url: base_url + "order_management/document_delete_ajax",
    //                 type: "POST",
    //                 data: {
    //                     'id': id
    //                 },
    //                 async: true,
    //                 success: function(data) {
    //                         result = $.parseJSON(data);
    //                         if(result.status=='success'){
    //                             swal({
    //                                     title: 'Success!',
    //                                     text: 'Successfully deleted',
    //                                     type: 'success',
    //                                     showCancelButton: false
    //                                 }, function() {
    //                                     var piid=$("#proforma_invoice_id").val();
    //                                     fn_rander_document_list(piid);
    //                             });
    //                         }
                        
    //                 },
    //                 error: function() {
    //                     swal({
    //                             title: 'Something went wrong there!',
    //                             text: '',
    //                             type: 'danger',
    //                             showCancelButton: false
    //                         }, function() {

    //                     });
    //                 }
    //             });
    //         });
        
    // });
    
});

function fn_rander_document_list(proforma_id='')
{
    var base_url=$("#base_url").val();    
    $.ajax({
        url: base_url + "order_management/om_proforma_wise_document_list_view_rander_ajax",
        type: "POST",
        data: {
            'proforma_id':proforma_id
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data); 
            $('#om_document_list_div').html(result.html);            
        },
        error: function() {
            swal({
                    title: 'Something went wrong there!',
                    text: '',
                    type: 'danger',
                    showCancelButton: false
                }, function() {

            });
        }
    });
}




</script>