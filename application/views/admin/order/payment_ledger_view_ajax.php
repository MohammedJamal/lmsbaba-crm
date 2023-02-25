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
$payment_term_arr=array();
if($po_register_info->payment_type=='F')
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
    $deal_value=$po_register_info->deal_value_as_per_purchase_order;
}
?>

<?php if($deal_value){ ?>
<tr>
    <td><?php echo date_db_format_to_display_format($po_register_info->po_date); ?></td>      
    <td>N/A</td>         
    <td>New Deal</td>                   
    <td class="text-center"><span class="minus"><?php echo number_format($deal_value,2); ?></span></td>
    <td class="text-center">0</td>          
    <td class="text-center pay-action"><?php echo number_format($deal_value,2); ?></td>
</tr>
<?php } ?>
<?php 
$po_pr=0;
$balance=$deal_value;
if(count($get_payment_received)){ ?>
<?php 
foreach($get_payment_received AS $pr){ $po_pr=($po_pr+$pr->amount);?>
<tr>
    <td><?php echo date_db_format_to_display_format($pr->received_date); ?></td>      
    <td><?php echo $pr->payment_mode; ?></td>
    <td><?php echo $pr->narration; ?></td>
    <td class="text-center">0</td>               
    <td class="text-center"><span class="plus"><?php echo $pr->amount; ?></span></td>          
    <td class="text-center pay-action">
        <?php 
        $balance=($balance-$pr->amount);
        echo $balance; 
        ?>
        <a href="JavaScript:void(0)" class="del_payment_ledger del_pay_product" data-id="<?php echo $pr->id; ?>" data-lowp="<?php echo $lowp; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
    </td>
</tr>
<?php } ?>
<tr>
    <td colspan="6"><input type="hidden" id="po_pb" value="<?php echo number_format($balance,2); ?>"><input type="hidden" id="po_pr" value="<?php echo number_format($po_pr,2); ?>"></td>
</tr>
<?php }else{ ?>
<tr>
    <td colspan="6" align="center">Payment not received!</td>
</tr>
<?php } ?>
