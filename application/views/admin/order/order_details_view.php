<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
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
$p_created_at=array();
$p_updated_at=array();
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
<!doctype html>
<html lang="en">   
<head>
<?php $this->load->view('admin/includes/head'); ?>  <style type="text/css" media="screen">
 .d-none{display: none;}
 .d-block{display: block;}
 .mail-modal img{
    max-width: 120px !important;
    height: auto !important;
 }
</style>
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/dashboard_style.css"/>
</head>
<body>
<div class="app full-width expanding">    
<div class="off-canvas-overlay" data-toggle="sidebar"></div>
<div class="sidebar-panel">      
<?php $this->load->view('admin/includes/left-sidebar'); ?>
</div> 
<div class="app horizontal top_hader_dashboard">
<?php $this->load->view('admin/includes/header'); ?>
</div>     

<div class="main-panel">
<div class="min_height_dashboard"></div>
<div class="main-content lead_manage_page">              
<div class="content-view"> 
<div class="row m-b-1">            
<div class="col-sm-4 pr-0">
<div class="bg_white back_line">  
  <h4>Manage Purchase Order </h4> 
</div>
</div>
<div class="col-sm-8 pleft_0">
<div class="bg_white_filt">
<ul class="filter_ul">
    <li>
    <a href="<?php echo $this->session->userdata('po_back_url_from_detail');?>" class="new_filter_btn" id="">
      <span class="bg_span"><img src="<?php echo assets_url()?>images/left_black.png"/></span>
      Back
    </a>
  </li>
  <?php /* ?>                        
  <li>
    <a href="JavaScript:void(0);" class="new_filter_btn" id="filter_btn">
      <span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>
      Filters
    </a>
  </li>
  
  <li>                          
    <a class="new_filter_btn" href="JavaScript:void(0);" id="rander_add_new_lead_view">
      <span class="bg_span"><img src="<?php echo assets_url()?>images/adduesr_new.png"/></span> Add New Lead 
  
    </a> 
  </li>
 
  <li>
    <?php                            
      if($this->session->userdata['admin_session_data']['user_id']=='1')
      {
      ?>                    
        <a href="JavaScript:void(0);" class="upload_excel upload_csv new_filter_btn"><span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"></span> Upload Leads </a>
      <?php
      }                  
      ?>
  </li> 
  <?php */ ?>                             
</ul>
</div>
</div>
</div>   
          
<?php if($po_register_info->is_cancel=='Y'){ ?>
<div class="alert alert-danger">
  <h4><strong>Note: </strong> This deal has been cancelled by <?php echo $po_register_info->cancelled_by; ?> on <?php echo date_db_format_to_display_format($po_register_info->cancelled_date); ?>.</h4>
</div>
<?php } ?> 
<div class="card process-sec">
<div class="card-block"> 
    <?php //echo'<pre>';print_r($po_register_info);echo'</pre>'; ?>
    <div class="row">
        <div class="col-md-9">
            <div class="order_status_title">
               <h2>Order Details</h2>
               
            </div>
            
            <div class="border-block">
                <div class="tholder max-w-660">
                    <table class="table order-details-border-table">
                      
                        <tbody>
                            <tr>
                                <td>
                                    <strong>PO No.</strong><br><?php echo ($po_register_info->po_number)?$po_register_info->po_number:'N/A'; ?>
                                </td>
                                <td>
                                    <strong>PO Date</strong><br><?php echo date_db_format_to_display_format($po_register_info->po_date); ?>
                                </td>
                                <?php /* ?>
                                <td>
                                    <strong>Customer ID</strong><br><?php echo $po_register_info->cust_id; ?>
                                </td>
                                <?php */ ?>
                                <td>
                                    <strong>Invoice No.</strong><br><?php echo ($po_register_info->invoice_no)?$po_register_info->invoice_no:'-'; ?>
                                </td>
                                <td>
                                    <strong>Invoice Date</strong><br><?php echo ($po_register_info->invoice_date)?date_db_format_to_display_format($po_register_info->invoice_date):'-'; ?>
                                </td>
                                <td>
                                    <strong>Lead ID</strong><br><?php echo $po_register_info->lid; ?>
                                </td>
                                <td>
                                    <strong>Lead Date</strong><br><?php echo date_db_format_to_display_format($po_register_info->l_enquiry_date); ?>
                                </td>
                                <td>
                                    <strong>Assigned to</strong><br><?php echo $po_register_info->cust_assigned_user; ?>
                                </td>
                            </tr>                  
                        </tbody>
                   </table>
                </div>
            </div>
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
            <?php if($po_register_info->comments){ ?>
            <div class="border-block">
                <div class="instructions-title"><span>Delivery Instructions:</span> <?php echo $po_register_info->comments; ?></div>
            </div>
            <?php } ?>

            <?php if($po_register_info->po_tds_percentage)
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
            <?php } ?>
            <div class="border-block no-border">
                <div class="big-title">Download <i class="fa fa-download" aria-hidden="true"></i></div>
                <ul class="auto-ul">
                    <li>
                        
                    <?php 
                    $is_quotation_exist='N';
                    if($po_register_info->q_is_extermal_quote=='N'){ ?>
                        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$po_register_info->lo_id.'/'.$po_register_info->q_id);?>" target="_blank">Quotation</a>
                    <?php 
                    $is_quotation_exist='Y';
                    } 
                    ?>
                    <?php if($po_register_info->q_is_extermal_quote=='Y' && $po_register_info->q_file_name!=''){ ?>
                        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$po_register_info->lo_id.'/'.$po_register_info->q_id);?>" target="_blank">Quotation</a>
                    <?php 
                    $is_quotation_exist='Y';
                    } 
                    ?>
                    <?php
                    if($is_quotation_exist=='N')
                    {
                        ?>
                        <a href="JavaScript:void(0)" class="cicon_btn get_alert" data-text="Oops! There is no Quotation.">Quotation</a>
                    <?php
                    }
                    ?>
                    </li>
                    <?php if($po_register_info->is_cancel=='N'){ ?>
                    <li><a href="JavaScript:void(0)" class="open_po_preview--- open_po_popup_steps" data-step="1" data-lowp="<?php echo $lowp; ?>" data-lo_id="<?php echo $po_register_info->lead_opportunity_id; ?>" data-lid="<?php echo $po_register_info->lead_id; ?>">Purchase Order</a></li>
                    <?php } ?>
                    <li><a href="JavaScript:void(0)" class="<?php echo ($po_register_info->pro_forma_no)?'open_pfi_preview':'pfi_edit_view_confirmation'; ?>" data-step="3" data-lowp="<?php echo $lowp; ?>" data-lo_id="<?php echo $po_register_info->lead_opportunity_id; ?>" data-lid="<?php echo $po_register_info->lead_id; ?>">Proforma Invoice</a></li>
                    <li><a href="JavaScript:void(0)" class="<?php echo ($po_register_info->invoice_no)?'open_inv_preview':'inv_edit_view_confirmation'; ?>" data-step="4" data-lowp="<?php echo $lowp; ?>" data-lo_id="<?php echo $po_register_info->lead_opportunity_id; ?>" data-lid="<?php echo $po_register_info->lead_id; ?>">Invoice</a></li>
                    <li><a href="JavaScript:void(0)" class="view_payment_ledger">Payment Terms</a></li>
                    <li><a href="JavaScript:void(0)" class="view_payment_ledger">Payment Ledger</a></li>
                    <li><a href="JavaScript:void(0);" data-leadid="<?php echo $po_register_info->lead_id; ?>" class="view_lead_history">Lead History</a></li>
                </ul>
            </div>
            <form id="poUpdateCommentFrm" name="poUpdateCommentFrm">
            <div class="border-block no-border">
               <h2>Update Comment<span class="red">*</span></h2>
               <div class="order_status_txt">
                  <textarea class="default-textarea mb-15" name="po_uc_comment" id="po_uc_comment"></textarea>
                  <div class="row">
                       <div class="col-md-9">
                            <label class="uploaded-doc">
                            <i class="fa fa-paperclip" aria-hidden="true"></i> <span>Click to Attach Documents</span>
                               <input type="file" name="po_uc_file[]" id="po_uc_file">
                            </label>
                            <div class="upload-name-holder" style="display: inline-block;">
                            <div class="fname_holder" id="attach_file_outer" style="display:none;">
                            <span id="attach_file_div"></span>
                            <a href="JavaScript:void(0)" data-filename="" class="file_close" id="attach_file_div_close"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </div>
                            </div>
                       </div>
                       <div class="col-md-3">
                           <button class="btn btn-primary pull-right fix-ww" id="po_update_comment_confirm">Submit</button>
                       </div>
                   </div>
               </div>
               
            </div>
            </form>
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
    </div>
</div>
</div>  
</div>                           
</div>
<div class="content-footer">
<?php $this->load->view('admin/includes/footer'); ?>
</div>
</div>
</div>
<?php $this->load->view('admin/includes/modal-html'); ?>
<?php $this->load->view('admin/includes/app.php'); ?></body>
</html>
</div>
</div>
</div>
</body>
</html>
<script type="text/javascript">
function simpleEditer()
{
    // $(".tools").show();
    var box = $('.buying-requirements');
    box.attr('contentEditable', true);
    // EDITING LISTENERS
    $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
       edit($(this).data('cmd'));
    });    
}
function inactiveSimpleEditer()
{
    $(".tools").hide();
    var box = $('.buying-requirements');
    box.attr('contentEditable', false);
}
// =====================================================
  // Quick View

  $("body").on("click",".quick_view_item",function(e){
    var txt=$(this).attr('data-comment');
    // var existing_txt=$(".buying-requirements").html();
    $(".buying-requirements .default-com").html(txt);
  });
  $("body").on("click",".del-comm",function(e){
        var base_URL = $("#base_url").val();
        var click_btn=$(this);
        var id=$(this).attr("data-id");
          var indexToRemove = $(this).parent().parent().parent().index();
        
        swal({
            title: 'Warning',
            text: 'Are you sure? Do you want to delete the record?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
          }, 
          function(isConfirm) {
                if (isConfirm) {
                    var data = "id="+id;  
                    $.ajax({
                        url: base_URL+"lead/delete_lead_update_pre_define_comment",
                        data: data,
                        cache: false,
                        method: 'POST',
                        dataType: "html",
                        beforeSend: function( xhr ) {},
                        success:function(res){ 
                            result = $.parseJSON(res);
                            if(result.status=='success')
                            {
                                swal('Success!', result.msg, 'success'); 
                                $("#item_"+id).parent().html(''); 


                                $(this).parent().parent().parent().index();
      
                                $("#txt-carousel").trigger('remove.owl.carousel', [indexToRemove]).trigger('refresh.owl.carousel'); 
                                var c = $('#txt-carousel .owl-stage .owl-item').size();
                                

                                $('.quick_reply_count').html('('+c+')');                                       
                            }
                        },
                        complete: function(){},
                        error: function(response) {
                        //alert('Error'+response.table);
                        }
                    })
              }
              return false;
          });        
    });
  /*
  $(document).on('click', 'a.del-item', function (e) {
      e.preventDefault();
      var base_URL = $("#base_url").val();
      var id=$(this).attr("data-id");
      var indexToRemove = $(this).parent().parent().parent().index();
      
      $("#txt-carousel").trigger('remove.owl.carousel', [indexToRemove]).trigger('refresh.owl.carousel');

      var c = $('#txt-carousel .owl-stage .owl-item').size();

        //$('.quick_view_h1_tag').removeClass('hide');
        //$('.quick_reply_count').removeClass('hide');
        $('.quick_reply_count').html('('+c+')');

    });
  */
  $(document).on('click', '.add_quick_view_comment', function (e) {
      e.preventDefault();

      var getTxt = $('#quick_view_title').val();
      // var getTxtDesc = $('#quick_view_desc').val();
      var getTxtDesc=tinyMCE.activeEditor.getContent();
      var getTarget = $(this).attr('data-target');
      var error='';
      
      if(getTxt =='')
      {  
        $("#quick_view_title_error").text("Required");
        $("#quick_view_title").addClass("field-error");
        error='1';
        e.stopPropagation();
      } 
      else
      {
        $("#quick_view_title_error").text("");
        $("#quick_view_title").removeClass("field-error");
         error='';
      }

      if(getTxtDesc=='')
      {  
        $("#quick_view_desc_error").text("Required");
        $("#quick_view_desc").addClass("field-error");
         error='1';
        e.stopPropagation();
      } 
      else
      {
        $("#quick_view_desc_error").text("");
        $("#quick_view_desc").removeClass("field-error");
        error='';
      }
         
      if(error=='')
      {

        var base_URL = $("#base_url").val();        
        var data = "title="+getTxt+"&description="+getTxtDesc;
        // alert(data); return false;
        $.ajax({
            url: base_URL+"lead/add_lead_update_pre_define_comment",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {
                
            },
            complete: function(){
                
            },
            success:function(res){ 
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                  $('[data-toggle="tooltip"]').tooltipster('destroy');
                  var ttt = '<div class="item noshow"><div class="auto-txt-item">'+getTxt+'<a href="#" class="del-item"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
                  $('body').append(ttt);
                  var gdw = $('.noshow .auto-txt-item').innerWidth();
                  $('.noshow').remove();
                  var ttt = '<div class="item" style="width: '+gdw+'px;"><div class="auto-txt-item quick_view_item" data-toggle="tooltip"  title="'+getTxtDesc+'" data-comment="'+getTxtDesc+'">'+getTxt+'<a href="JavaScript:void(0)" data-id="'+result.id+'" class="del-item del-comm"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
                  //alert(gdw)
                  $('#'+getTarget).trigger('add.owl.carousel', [$(ttt), 0]).trigger('refresh.owl.carousel');
                  $('#'+getTarget).trigger('to.owl.carousel', 0);
                  //updateNumber(getTarget);
                  $('.com-holder-new .com-holder-fild input').val('');

                  $('[data-toggle="tooltip"]').tooltipster();
                  ////////
                  var c = $('#'+getTarget+' .owl-stage .owl-item').size();

                  $('.quick_view_h1_tag').removeClass('hide');
                  $('.quick_reply_count').removeClass('hide');
                  $('.quick_reply_count').html('('+c+')');


                  //$('.add-com-count').html('('+c+')');
                  //$('[data-toggle="tooltip"]').tooltip('update')
                   $('#quick_view_title').val('');
                   $('#quick_view_desc').val('');
                   tinyMCE.activeEditor.setContent('');
                }
            },            
            error: function(response) {
            //alert('Error'+response.table);
            }
        });
      }
   });
   $(document).on('click', '.close_quick_view_comment', function (e) {
      e.preventDefault();
        $("#quick_view_desc_error").text("");
        $("#quick_view_title_error").text("");
        $('#quick_view_title').val('');
        $('#quick_view_desc').val('');
        tinyMCE.activeEditor.setContent('');
   });
  $('#CommentUpdateLeadModal').on('shown.bs.modal', function (e) {
      // do something...
      //console.log('do something...')
      $('#txt-carousel .owl-stage .owl-item').each(function( index ) {
         var gItemw = $(this).find('.auto-txt-item').outerWidth();
         //console.log( index + ": " + gItemw );
         $(this).find('.item').css({'width':gItemw});
         $('#txt-carousel').trigger('refresh.owl.carousel');

      });
   });
  $('#ReplyPopupModal').on('shown.bs.modal', function (e) {
      // do something...
      //console.log('do something...')
      $('#txt-carousel .owl-stage .owl-item').each(function( index ) {
         var gItemw = $(this).find('.auto-txt-item').outerWidth();
         //console.log( index + ": " + gItemw );
         $(this).find('.item').css({'width':gItemw});
         $('#txt-carousel').trigger('refresh.owl.carousel');

      });
   });
  // Quick View
  // =====================================================
</script>
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.doubleScroll.js"></script>

<link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>
<!-- owl -->
<link rel="stylesheet" href="<?=assets_url();?>css/owl.carousel.min.css">
<link rel="stylesheet" href="<?=assets_url();?>css/owl.theme.default.min.css">
<script src="<?=assets_url();?>js/owl.carousel.js"></script>
<!-- owl -->
<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<script src="<?php echo assets_url();?>js/custom/order/order.js?v=<?php echo rand(0,1000); ?>"></script>
<script src="<?php echo assets_url();?>js/custom/order/details.js?v=<?php echo rand(0,1000); ?>"></script>

