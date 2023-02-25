<?php
// print_r($po_register_info);
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
$payment_term_arr=array();
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

$payment_term_arr[]=array(
				'date'=>$f_payment_date,
				'currency_type'=>$f_currency_type,
				'amount'=>$f_amount,
				'method'=>$f_payment_method,
				'narration'=>$f_narration
				);
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

    $payment_term_arr[]=array(
				'date'=>$payment_date,
				'currency_type'=>$currency_type,
				'amount'=>$amount,
				'method'=>$payment_method,
				'narration'=>$narration
				);
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
?>
<div class="company-details">
<div class="container-fluid">
<?php if($po_register_info->is_cancel=='Y'){ ?>
    <div class="alert alert-danger">
        <h4><strong>Note: </strong> This deal has been cancelled by <?php echo $po_register_info->cancelled_by; ?> on <?php echo date_db_format_to_display_format($po_register_info->cancelled_date); ?>.</h4>
    </div>
<?php } ?> 
<div class="row">
<div class="col-md-8">
		<div class="company-name">
        <h1>Company:</h1>
        <?php echo ($po_register_info->cust_company_name)?'<strong>'.$po_register_info->cust_company_name.'</strong><br>':''; ?>
            <?php echo ($po_register_info->cust_contact_person)?''.$po_register_info->cust_contact_person.'<br>':''; ?>
            <?php echo ($po_register_info->cus_mobile)?'Mobile: +'.$po_register_info->cus_mobile_country_code.'-'.$po_register_info->cus_mobile.'<br>':'';?>
            <?php echo ($po_register_info->cus_email)?'Email: '.$po_register_info->cus_email.'<br>':'';?>
            <?php 
            $cust_website='';
            if($po_register_info->cus_website)
            {
                $cus_website_prefix=http_or_https_check($po_register_info->cus_website);
                if($cus_website_prefix=='')
                {
                    $cust_website='http://'.$po_register_info->cus_website;
                }
                else
                {
                    $cust_website=$po_register_info->cus_website;
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
                        echo ($website)?'Website '.$i.': <a href="'.$website_tmp.'" class="blue-link" target="_blank">Visit</a><br>':'';
                    }
                    else
                    {
                        echo ($website)?'Website: <a href="'.$website_tmp.'" class="blue-link" target="_blank">Visit</a><br>':'';
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
</div>


<div class="col-md-4">
		<div class="order-infos pull-right">
            <strong>PO ID:</strong> #<?php echo $po_register_info->lowpo; ?><br>
    		<strong>Order No.:</strong> <?php echo ($po_register_info->po_number)?$po_register_info->po_number:'N/A'; ?><br>
    		<strong>Order Date:</strong> <?php echo date_db_format_to_display_format($po_register_info->po_date); ?><br>
    		<strong>Deal Value:</strong> <?php echo $po_register_info->po_currency_type_code; ?> <?php echo number_format($deal_value,2); ?>
		</div>
</div>
</div>
</div>
</div>
<hr>
<div class="company-details">
<div class="container-fluid">
<div class="row">
<div class="col-md-8">
		<div class="maxw-525">
			<div class="payment-table-loop">
				<div class="payment-table-header">
					<strong>Payment Terms</strong> (<?php echo $payment_type; ?>)
                    <?php if($po_register_info->is_cancel=='N'){ ?>
                        <?php if($action_type!='view'){ ?>
                        <div class="pull-right">
                            (<a href="JavaScript:void(0);" class="pay-edit" id="open_po_payment_terms" data-step="2" data-lowp="<?php echo $lowp; ?>" data-lo_id="<?php echo $po_register_info->lead_opportunity_id; ?>" data-lid="<?php echo $po_register_info->lead_id; ?>">Edit</a>)
                        </div>
                        <?php } ?>
                    <?php } ?>
				</div>
				<div class="payment-table-holder">
                <table class="table table-bordered-color">
                   	<thead>
                      	<tr>
                         	<th>Payment Date</th>
                         	<th>Amount</th>
                     		<th>Payment Mode</th>
                     		<th>Narration</th>
                      	</tr>
                   	</thead>
                   	<tbody>
                   		<?php 
                   		if(count($payment_term_arr))
                   		{

                   			foreach($payment_term_arr AS $pt)
                   			{
                   		?>
                      	<tr>
                         	<td><?php echo date_db_format_to_display_format($pt['date']);?></td>
                         	<td><?php echo $pt['currency_type'].' '.number_format($pt['amount'],2);?></td>
                         	<td><?php echo $pt['method'];?></td>
                         	<td><?php echo ($pt['narration'])?$pt['narration']:'N/A';?></td>
                      	</tr>
                      	<?php
                            }
                        }
                        else
                        {
                        ?>
                        <tr>
                         	<td colspan="4">No record found!</td>
                      	</tr>
                        <?php
                        }
                      	?>                             	
                   	</tbody>
                </table>
             </div>
			</div>
			<div class="payment-table-loop">
				<div class="payment-table-header">
					<strong>Payment Ledger</strong>
				</div>
				<div class="payment-table-holder">
                <table class="table table-bordered-color">
                   	<thead>
                      	<tr>
                         	<th>Payment Date</th>
                         	<th>Mode</th>        
                         	<th>Narration</th>
                         	<th class="text-center">Credit</th>          
                         	<th class="text-center">Debit</th>    
                         	<th class="text-center pay-action">Balance</th>
                      	</tr>
                   	</thead>
                   	<tbody id="payment_legder_content"></tbody>
                </table>
             </div>
			</div>

		</div>
</div>
<?php if($action_type!='view'){ ?>
    <div class="col-md-4">
        <div class="maxw-270">
            <h1>Add Payment Received</h1>
            <div class="new-pay-bg">
            
            <div class="form-group">
                <label class="black-title">Date of Payment<span class="red">*</span>:</label>
                <div class="full-width">
                    <input type="text" class="default-input" name="pr_date" id="pr_date" placeholder="MM-DD-YYYY">
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="pr_currency_type" id="pr_currency_type" value="<?php echo ($payment_term_arr[0]['currency_type'])?$payment_term_arr[0]['currency_type']:$company['default_currency_code']; ?>">
                <label class="black-title">Amount<span class="red">*</span> <?php echo ($payment_term_arr[0]['currency_type'])?'('.$payment_term_arr[0]['currency_type'].')':$company['default_currency_code']; ?>:</label>
                <div class="full-width">
                    <input type="text" class="default-input" name="pr_amount" id="pr_amount" placeholder="ex: 23">
                </div>
            </div>

            <div class="form-group">
                <label class="black-title">Payment Mode<span class="red">*</span>:</label>
                <div class="full-width">
                    <select class="default-select" name="pr_payment_mode_id" id="pr_payment_mode_id">
                        <option value="">Select</option>
                        <?php 
                        if(count($po_payment_method))
                        { 
                        foreach($po_payment_method AS $method)
                        {
                        ?>
                        <option value="<?php echo $method->id; ?>" <?php if($f_payment_method_id==$method->id){echo'SELECTED';} ?>><?php echo $method->name; ?></option>
                        <?php
                        }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="black-title">Narration</label>
                <div class="full-width">
                    <input type="text" class="default-input" name="pr_narration" id="pr_narration" placeholder="">
                </div>
            </div>
            <div class="full-width">
                <a href="JavaScript:void(0);" class="btn btn-primary txt-upper" id="add_payment_ledger" data-lowp="<?php echo $lowp; ?>">Add Payment</a>
            </div>
        </div>
    </div>
<?php } ?>
</div>
</div>
</div>
</div>
<script type="text/javascript">
var assets_base_url = $("#assets_base_url").val();
    $("#pr_date" ).datepicker({
          showOn: "both",
          dateFormat: "dd-M-yy",
          buttonImage: assets_base_url+"images/cal-icon.png",
          // changeMonth: true,
          // changeYear: true,
          // yearRange: '-100:+0',
          buttonImageOnly: true,
          buttonText: "Select date",
          // minDate: 0,
          maxDate: 'now'
    });
</script>
