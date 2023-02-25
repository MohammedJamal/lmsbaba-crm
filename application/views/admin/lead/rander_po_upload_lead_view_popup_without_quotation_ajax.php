
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

<input type="hidden" name="lead_opportunity_wise_po_id" id="lead_opportunity_wise_po_id" value="">
<input type="hidden" name="po_payment_term_id" id="po_payment_term_id" value="">
<input type="hidden" name="po_pro_forma_invoice_id" id="po_pro_forma_invoice_id" value="">
<input type="hidden" name="po_invoice_id" id="po_invoice_id" value="">
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
                    <li id="po_li_1" class="active">Add/Edit Purchase Order Details</li>
                    <li id="po_li_2">Add/Edit Payment Terms</li>
                    <li id="po_li_3">Proforma Invoice</li>
                    <li id="po_li_4">Invoice</li>
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
                          <label>Lead Date:</label>
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
                          <div class="full-width">
                            <input type="text" class="date-input input_date" id="po_date" name="po_date" placeholder="" value="<?php echo date_db_format_to_display_format(date('Y-m-d')); ?>" readonly="true"></div>
                            <div class="text-danger" id="po_date_error"></div>
                       </div>                       
                       <div class="col-md-3">
                          <label class="small-title">PO Number </label>
                          <div class="full-width">
                            <input type="text" class="default-input" id="po_number" name="po_number" placeholder="ex: 23">                 
                          </div>
                          <div class="text-danger" id="po_number_error"></div>
                       </div>                       
                       <div class="col-md-3">
                          <label class="small-title">Currency <span class="red">*</span></label>
                          <div class="full-width">
                            <select class="default-select" name="currency_type" id="currency_type">
                              <?php 
                              foreach($currency_list AS $currency){ ?>
                              <option value="<?php echo $currency->id; ?>" data-code="<?php echo $currency->code; ?>" <?php if($company['default_currency']==$currency->id){echo'SELECTED';}else{echo'disabled';}?>><?php echo $currency->code; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                       </div>
                       <div class="col-md-3">
                          <label class="small-title">PO Amount <span class="red">*</span></label>
                          <div class="full-width">
                            <input type="text" class="default-input double_digit" id="deal_value_as_per_purchase_order" name="deal_value_as_per_purchase_order" placeholder="ex: 100"></div>
                       </div>
                    </div>
                  </div>
                 <div class="form-group">
                     <div class="row">
                        <div class="col-md-6">
                           <label class="small-title">Delivery Instructions <span class="red">*</span></label>
                           <div class="full-width">
                              <textarea class="form-control" rows="5" id="po_upload_describe_comments" name="po_upload_describe_comments" style="height: 50px;"></textarea></div>
                           <div class="text-danger" id="po_upload_describe_comments_error"></div>
                        </div>
                        <div class="col-md-6">
                             <label class="small-title">Uplode PO Copy <small class="red">( *PDF file only. )</small></label>
                             
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
                     </div>
                  </div>

                    
                    <?php 
                    // -----------------------------
                    // renewal                
                    if($lead_data->renewal_detail_id>0)
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

             <div class="form-group  " id="">
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
                        <textarea class="form-control" rows="5" id="renewal_requirement" name="renewal_requirement"></textarea></div>
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
                                 <input class="styled-checkbox" id="styled-checkbox-1" type="checkbox" value="Y" name="po_upload_sent_ack_to_client" id="po_upload_sent_ack_to_client">
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
                                 <a href="JavaScript:void(0);" class="btn btn-primary txt-upper submit-bt pull-right" id="po_upload_without_quotation_submit">Save & Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
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
                    <div class="form-group row">
                       <div class="col-md-12">
                          <div class="full-width">
                             <input type="radio" id="full_pay" name="radio-pay" class="default-radio" checked="" value="full_payment">
                             <label for="full_pay" class="mbt-10">Full Payment</label>
                          </div>
                          <div class="payment-loop fix-input" id="payment_full">
                             <ul>
                                <li>
                                   <label class="blue-title">Payment Mode</label>
                                   <div class="full-width">
                                      <select class="default-select">
                                         <option>Please Select</option>
                                      </select>
                                   </div>
                                </li>
                                <li>
                                   <label class="blue-title">Date of Payment</label>
                                   <div class="full-width"><input type="text" class="date-input" name="" placeholder="MM-DD-YYYY"></div>
                                </li>
                                <li>
                                   <label class="blue-title">Amount</label>
                                   <div class="full-width">
                                      <input type="text" class="default-input" name="" placeholder="ex: 23">
                                      <small class="bal-txt">Balance Payment 2,3,5000</small>
                                   </div>
                                </li>
                                <li>
                                   <label class="blue-title">Narration</label>
                                   <div class="full-width"><input type="text" class="default-input" name=""></div>
                                </li>
                             </ul>
                          </div>
                       </div>
                    </div>
                    <div class="form-group row">
                       <div class="col-md-12">
                          <div class="full-width">
                             <input type="radio" id="part_pay" name="radio-pay" class="default-radio" value="part_payment">
                             <label for="part_pay">Part Payment</label>
                          </div>

                          <div class="payment-details-holder mb-0" style="display: none;">
                             <div class="payment-loop fix-input" id="payment_1">
                                <ul>
                                   <li>
                                      <label class="blue-title">Select Payment Mode</label>
                                      <div class="full-width">
                                         <select class="default-select">
                                            <option>Please Select</option>
                                         </select>
                                      </div>
                                   </li>
                                   <li>
                                      <label class="blue-title">Date od Payment</label>
                                      <div class="full-width"><input type="text" class="date-input" name="" placeholder="MM-DD-YYYY"></div>
                                   </li>
                                   <li>
                                      <label class="blue-title">Amount</label>
                                      <div class="full-width"><input type="text" class="default-input" name="" placeholder="ex: 23"><small class="bal-txt">Balance Payment 2,3,5000</small></div>
                                   </li>
                                   <li>
                                      <label class="blue-title">Narration</label>
                                      <div class="full-width"><input type="text" class="default-input" name=""></div>
                                   </li>
                                </ul>
                             </div>
                          </div>
                          <a href="#" class="pay-bt-add" style="display: none;">+Add</a>
                       </div>
                    </div>
                    <div class="form-group row">
                       <div class="col-md-12">
                          <a href="#" class="btn btn-primary txt-upper prev-bt pull-left"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Previous</a>
                          <a href="#" class="btn btn-primary txt-upper submit-bt pull-right">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                       </div>
                    </div>
                 </div>
                 <!-- step 2 end -->

                 <!-- step 3 start -->
                 <div class="form-steps" id="po_div_3" style="display: none;">
                    <h2>Generate Proforma Invoice</h2>
                    <div class="form-group row">
                       <div class="col-md-6">
                          <div class="autofit">
                             <!-- <a href="#" class="edit-input"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                             <input type="text" name="" class="default-input auto-width big-txt" value="Proforma Invoice" disabled="">
                          </div>
                       </div>
                       <div class="col-md-6 text-right">
                          <span class="g-txt lh-40 bl-txt">PO Amount: INR 1,20,000</span>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-12"> 
                          <div class="payment-loop content-equel btb">
                             <ul>
                                <li>
                                   <div class="auto-row">
                                      <label>Proforma No:</label>
                                      238
                                   </div>
                                </li>
                                <li>
                                   <div class="auto-row">
                                      <label>Proforma Date:</label>
                                      23 Mar 2021
                                   </div>
                                </li>
                                <li>
                                   <div class="auto-row">
                                      <label>Due Date:</label>
                                      23 Mar 2021
                                   </div>
                                </li>
                             </ul>
                          </div>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-6">
                          <strong class="semi-big"><i class="fa fa-address-card" aria-hidden="true"></i> Bill From:</strong><br>
                          <span class="font-12 tb">Shashi Narain<br>
                          Shalvi Engineering Corporation<br>
                          24 Parganas (n), West Bengal, India<br>
                          GST: AWS4554J7U</span>
                       </div>
                       <div class="col-md-6">
                          <div class="autofit pr-35"><strong class="semi-big"><i class="fa fa-address-card" aria-hidden="true"></i> Bill To:</strong> <a href="#" class="edit-input"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>
                          <div>
                             <span class="font-12 tb">Shashi Narain<br>
                             Shalvi Engineering Corporation<br>
                             24 Parganas (n), West Bengal, India<br>
                             GST: AWS4554J7U</span>
                          </div>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-12">
                          <div id="product_list_update_127" style="width: 100%; clear: both; display: inline-block;">
                             <table class="table quotation-table">
                                <thead>
                                   <tr>
                                      <th>SL</th>
                                      <th>Product Name</th>
                                      <th class="text-center">
                                         Unit Price <br>
                                         INR
                                      </th>
                                      <th class="text-center">Unit</th>
                                      <th class="text-center">Quantity</th>
                                      <th class="text-center">GST (%)</th>
                                      <th class="text-center">Total Sale Price</th>
                                      <th>&nbsp;</th>
                                   </tr>
                                </thead>
                                <tbody>
                                   <tr>
                                      <td>1</td>
                                      <td>
                                         <div class="default-div max-width-140" contenteditable="true">phone (Code: pho8665923)</div>

                                      </td>
                                      <td>
                                         <div class="padding0">
                                            <input type="text" class="default-input calculate_quotation_price_update double_digit width-70" id="unit_price_update_0" name="unit_price[]" value="23" data-field="price" data-id="142" data-pid="2" data-quotationid="124" data-opportunityid="127">
                                            
                                         </div>
                                      </td>
                                      <td>
                                         <div class="padding0 d-flex">
                                            <input type="text" class="default-input calculate_quotation_price_update only_natural_number_noFirstZero width-50" name="unit[]" id="unit_update_0" value="1" data-field="unit" data-id="142" data-pid="2" data-quotationid="124" data-opportunityid="127">

                                            <select class="default-select width-80 ml-10">
                                               <option>Select</option>
                                            </select>
                                         </div>
                                      </td>
                                      <td>
                                         <input type="text" class="default-input calculate_quotation_price_update double_digit width-70" id="disc_update_0" name="disc[]" value="0" data-field="discount" data-id="142" data-pid="2" data-quotationid="124" data-opportunityid="127">  
                                      </td>
                                      <td>
                                         <div class="padding0">
                                            <input type="text" class="default-input calculate_quotation_price_update double_digit width-70" id="gst_update_0" name="gst[]" value="0" data-field="gst" data-id="142" data-pid="2" data-quotationid="124" data-opportunityid="127">
                                         </div>
                                      </td>
                                      <td>
                                         <div class="padding0" align="center">
                                            <input type="hidden" name="" value="" id="" class="form-control">
                                            <span class="" id="total_sale_price_2">23</span>
                                         </div>
                                      </td>
                                      <td> 
                                         <a href="JavaScript:void(0)" class="del_quotation_product" data-id="142" data-quotationid="124" data-opportunityid="127" data-pid="2"><img style="cursor: pointer;" src="https://lmsbaba.com/assets/images/trash.png" alt=""></a>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td colspan="6" style="text-align:right;" class="back_border_total"><strong>Total Deal Value</strong></td>
                                      <td colspan="2" class="back_border_total">
                                         <span id="total_deal_value"><strong>23.00</strong></span>
                                         <input type="hidden" name="deal_value" id="deal_value" value="23.00">
                                      </td>
                                   </tr>
                                </tbody>
                             </table>
                          </div>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-6">
                          <a href="JavaScript:void(0);" id="add_product_from_edit_quotation" data-oppid="127" data-lead="89" data-quotationid="124" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add New Row</a>
                       </div>

                       <div class="col-md-6">
                          <table class="grand-summery" width="100%" border="0" cellpadding="0" cellspacing="0">
                             <tbody>
                                <tr>
                                   <td colspan="2"><b>Details (<span class="currency_code_div">INR</span>)</b></td>
                                </tr>
                                <tr>
                                   <td>Total Price</td>
                                   <td><span id="total_price">23.00</span></td>
                                </tr>
                                <tr>
                                   <td>Total Tax</td>
                                   <td><span id="total_tax">0.00</span></td>
                                </tr>
                                <tr>
                                   <td>Grand Total (Round Off)</td>
                                   <td><span id="grand_total_round_off">23.00</span></td>
                                </tr>
                                <tr>
                                   <td colspan="2" style="text-align: left"><b><span id="number_to_word_final_amount">Twenty Three Only</span> (INR)</b></td>
                                </tr>
                             </tbody>
                          </table>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-12">
                          <label class="small-title">Terms & Conditions/Commets:</label>
                          <div class="full-width not-150">
                             <textarea class="default-textarea"></textarea>
                          </div>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-12 text-center">
                          <a href="#" class="btn btn-primary txt-upper prev-bt pull-left"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Previous</a>

                          <a href="#" class="btn btn-primary txt-upper pro-form-preview-bt" ><i class="fa fa-eye" aria-hidden="true"></i> Preview</a>

                          <a href="#" class="btn btn-primary txt-upper submit-bt pull-right">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                       </div>
                    </div>
                 </div>
                 <!-- step 3 end -->
                 <!-- step 4 start -->
                 <div class="form-steps" id="po_div_4" style="display: none;">
                    <h2>Create Cost Sheet</h2>
                    <div class="form-group row">
                       <div class="col-md-12">
                          <div id="product_list_update_127" style="width: 100%; clear: both; display: inline-block;">
                             <table class="table quotation-table">
                                <thead>
                                   <tr>
                                      <th>SL</th>
                                      <th>Product Name</th>
                                      <th class="text-center">Unit Price</th>
                                      <th class="text-center">Quantity</th>
                                      <th class="text-center">Purchase Price</th>
                                      <th class="text-center">Total Purchase Cost</th>
                                      <th class="text-center">Total Sale Cost</th>
                                      <th>Diff (+/-)</th>
                                   </tr>
                                </thead>
                                <tbody>
                                   <tr>
                                      <td>1</td>
                                      <td>phone (Code: pho8665923)</td>
                                      <td>
                                         <div class="form-full-center">
                                            INR 1123 <br>per 1 piece
                                            
                                         </div>
                                      </td>
                                      <td>
                                         <div class="form-full-center">
                                            1000
                                         </div>
                                      </td>
                                      <td class="text-center">
                                         <input type="text" class="default-input calculate_quotation_price_update double_digit width-70" id="disc_update_0" name="disc[]" value="" data-field="discount" data-id="142" data-pid="2" data-quotationid="124" data-opportunityid="127"><br>
                                         per 1 piece
                                      </td>
                                      <td>
                                         
                                      </td>
                                      <td>
                                         
                                      </td>
                                      <td> 
                                        
                                      </td>
                                   </tr>
                                   <tr>
                                      <td colspan="6" style="text-align:right;" class="back_border_total"><strong>Total Deal Value</strong></td>
                                      <td colspan="2" class="back_border_total">
                                         <span id="total_deal_value"><strong>23.00</strong></span>
                                         <input type="hidden" name="deal_value" id="deal_value" value="23.00">
                                      </td>
                                   </tr>
                                </tbody>
                             </table>
                          </div>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-6">
                          <!-- <a href="JavaScript:void(0);" id="add_product_from_edit_quotation" data-oppid="127" data-lead="89" data-quotationid="124" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add New Row</a> -->
                       </div>

                       <div class="col-md-6">
                          <table class="grand-summery" width="100%" border="0" cellpadding="0" cellspacing="0">
                             <tbody>
                                <tr>
                                   <td colspan="2"><b>Details (<span class="currency_code_div">INR</span>)</b></td>
                                </tr>
                                <tr>
                                   <td>Total Sell Price</td>
                                   <td><span id="total_price">4,723.00</span></td>
                                </tr>
                                <tr>
                                   <td>Total Purchase Cost</td>
                                   <td><span id="total_tax">0.00</span></td>
                                </tr>
                                <tr>
                                   <td>Difference (+/-)</td>
                                   <td><span id="grand_total_round_off">4,523.00</span></td>
                                </tr>
                                <tr>
                                   <td colspan="2" style="text-align: left"><b><span id="number_to_word_final_amount">Twenty Three Only</span> (INR)</b></td>
                                </tr>
                             </tbody>
                          </table>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-12">
                          <label class="small-title">Comments:</label>
                          <div class="full-width not-150">
                             <textarea class="default-textarea"></textarea>
                          </div>
                       </div>
                    </div>

                    <div class="form-group row">
                       <div class="col-md-12 text-center">
                          
                          <a href="#" class="btn btn-primary txt-upper prev-bt pull-left"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Previous</a>

                          <a href="#" class="btn btn-primary txt-upper preview-bt"><i class="fa fa-eye" aria-hidden="true"></i> Preview</a>
                          
                          <a href="lead.html" class="btn btn-primary txt-upper submit-bt-no pull-right">Submit <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                       </div>
                    </div>
                 </div>
                 <!-- step 4 end -->

              </div>
            </div>
          </div>
        </div>
        <?php ?>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  /*
    $("body").on("change","#currency_type",function(e){
      var id=$(this).val();
      var code=$(this).find("option:selected").attr('data-code');
      $("#dv_curr_code").html('<option>'+code+'</option>');
      $(".curr_code").text(code);
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
});
</script>
<script src="<?php echo base_url();?>assets/js/custom/lead/po_upload_lead.js"></script>

