<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){ ?>
   <tr>
      <td></td>
      <td>
         <div class="product_bg lead_grid_block">
            <div class="total_info">
               <div class="total_info_left">
                  <div class="mail-form-row max-width no-border">
                     <div class="auto-row">
                        <label>Enquiry ID:</label>
                        <div class=".auto-name">#<?php echo $row->l_id;?></div>
                        
                     </div>
                     <div class="auto-row">
                        <label>Enquiry Date:</label>
                        <div class=".auto-name"><?php echo date_db_format_to_display_format($row->l_enquiry_date);?></div>
                        
                     </div>
                     <div class="auto-row">
                        <label>Quotation Id:</label>
                        <div class=".auto-name">#<?php echo $row->lead_opportunity_id;?></div>
                        
                     </div>
                     
                     
                     <div class="pull-right"><a href="JavaScript:void(0)" class="blue-link view_lead_history" data-leadid="<?php echo $row->l_id;?>"><i class="fa fa-history" aria-hidden="true"></i> Order History</a></div>
                  </div>
                  <div class="mail-form-row max-width no-border">
                     <div class="lead-table">
                        <div class="">
                           <table class="table clock-table white-style">
                              <tr>
                                <th>PO Date</th>
                                <th>PO Number</th>
                                <th>Enq ID</th>
                                <th>Currency</th>
                                <th>PO Amount</th>
                                <th>Payment Recived</th>
                                <th>Balance Payment</th>
                              </tr> 
                              <tr>
                                 <td><?php echo date_db_format_to_display_format($row->po_date);?></td>
                                 <td><?php echo $row->po_number; ?></td>
                                 <td><?php echo $row->l_id;?></td>
                                 <td><?php echo $row->lead_opp_currency_code;?></td>
                                 <td><?php echo $row->lead_opp_currency_code;?> <?php echo number_format($row->deal_value_as_per_purchase_order,2);?></td>
                                 <td><?php echo $row->lead_opp_currency_code;?> <?php echo number_format($row->payment_received,2);?></td>
                                 <td>
                                    <?php
                                    $received_payment=$row->payment_received;
                                    $total_payble_amount=$row->total_payble_amount;
                                    $balance=($total_payble_amount-$received_payment);
                                    echo $row->lead_opp_currency_code.' '.number_format($balance,2);
                                    ?>
                                 </td>
                              </tr>                         
                          </table>
                        </div>
                     </div>
                  </div>
                  <div class="mail-block-action text-right">
                     
                     <a href="JavaScript:void(0);" class="btn btn-primary fleft mr-10 add_po_bt open_po_popup_steps" data-step="2" data-lowp="<?php echo $row->id; ?>" data-lo_id="<?php echo $row->lead_opportunity_id; ?>" data-lid="<?php echo $row->lead_id; ?>">Payment Terms</a>

                     <a href="JavaScript:void(0);" class="btn btn-primary fleft mr-10 open_po_popup_steps" data-step="3" data-lowp="<?php echo $row->id; ?>" data-lo_id="<?php echo $row->lead_opportunity_id; ?>" data-lid="<?php echo $row->lead_id; ?>">Pro Forma Invoice</a>

                     <a href="JavaScript:void(0);" class="btn btn-primary fleft mr-10 open_po_popup_steps" data-step="4" data-lowp="<?php echo $row->id; ?>" data-lo_id="<?php echo $row->lead_opportunity_id; ?>" data-lid="<?php echo $row->lead_id; ?>">Invoice</a>
                     
                  </div>
                  <a href="#" class="show_lead_quote"></a>
                  <div class="mail-form-row blue-label footer-shadow">
                     <div class="auto-row">
                        <label>Next Follow-up:</label>
                        <div class="mail-input-div">
                           <!-- <i class="fa fa-flag red-font-text" aria-hidden="true"></i>&nbsp;--><?php echo date_db_format_to_display_format($row->l_followup_date);?>    
                        </div>
                     </div>
                     <div class="auto-row">
                        <label>Assigned to:</label>
                        <div class="mail-input-div"><a href="JavaScript:void(0);" class="company_assigne_change" data-cid="3" data-currassigned="2"><?php echo $row->assigned_user_name;?></a></div>
                     </div>
                     <div class="auto-row">
                        <label>Stage:</label>
                        <div class="mail-input-div"><?php echo $row->l_current_stage;?></div>
                     </div>
                     <div class="auto-row">
                        <label>Status</label>
                        <div class="mail-input-div"><?php echo $row->l_current_status;?></div>
                     </div>
                     <div class="auto-row">
                        <label>Source</label>
                        <div class="mail-input-div"><?php echo ($row->source_name)?''.$row->source_name.'':'';?> </div>
                     </div>
                  </div>
               </div>
               <div class="product_vendor_action">
                  <div class="product_vendor lead_vendor">
                     <h2>Comapny Details</h2>
                     <p>
                        <?php echo ($row->cus_contact_person)?$row->cus_contact_person.'<br>':'';?>
                        <?php echo ($row->cus_company_name)?$row->cus_company_name.'<br>':'';?>
                        <?php echo ($row->cus_mobile)?'Mobile: +'.$row->cus_mobile_country_code.'-'.$row->cus_mobile.'<br>':'';?>
                        <?php echo ($row->cus_email)?'Email: '.$row->cus_email.'<br>':'';?>
                        <?php 
                        if($cust_website!='')
                        {
                           $cust_website_arr=explode(',', $cust_website);
                           $i=1;
                           foreach($cust_website_arr AS $website)
                           {
                              // $is_http_exist=http_or_https_check($website);
                              // if($is_http_exist=='')
                              // {
                              //    $website_tmp='http://'.$website;
                              // }
                              // else
                              // {
                              //    $website_tmp=$website;
                              // }
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
                        //echo ($cust_website)?'Website: <a href="'.$cust_website.'" class="blue-link" target="_blank">Visit</a><br>':'';

                        ?>
                        <?php 
                        $location='';
                        if($row->cust_city_name || $row->cust_state_name || $row->cust_country_name)
                        {
                           $location .=($row->cust_city_name)?$row->cust_city_name.', ':'';
                           $location .=($row->cust_state_name)?$row->cust_state_name.', ':'';
                           $location .=($row->cust_country_name)?$row->cust_country_name.', ':'';
                        }
                        echo ($location)?'Location: '.rtrim($location,', '):'';
                        ?>                 
                     </p>
                     <div>
                        <a href="JavaScript:void();" class="blue-link mr-15 get_detail_modal" data-id="<?php echo $row->cus_id; ?>"><i class="fa fa-eye" aria-hidden="true"></i> View</a>

                        <a href="JavaScript:void();" class="blue-link edit_customer_view" data-id="<?php echo $row->cus_id; ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit</a>
                        <?php 
                        if($row->cust_repeat_count>1)
                        { 
                        ?>
                        <p ><a href="JavaScript:void(0);" class="text-danger search_by_customer" data-custemail="<?php echo $row->cus_email; ?>" data-custmobile="<?php echo $row->cus_mobile; ?>"><b>Repeat Buyer: <?php echo $row->cust_repeat_count; ?> times</b></a></p>
                        <?php 
                        } 
                        ?>
                     </div>
                  </div>
                  <div class="product_action">
                     <ul>
                        <li>
                           <?php if($row->cus_mobile!=''){ ?>
                           <?php 
                           if(count($c2c_credentials))
                           {
                           ?>
                              <a href="JavaScript:void(0)" class="cicon_btn <?php echo ($row->cus_mobile)?'set_c2c':''; ?>" data-leadid="<?php echo $row->l_id;?>" data-cusid="<?php echo $row->cus_id; ?>" data-custmobile="<?php echo $row->cus_mobile; ?>" data-contactperson="<?php echo $row->cus_contact_person; ?>" data-usermobile="<?php echo $c2c_credentials['mobile']; ?>" data-userid="<?php echo $c2c_credentials['user_id']; ?>" ><img src="<?php echo assets_url(); ?>images/cicon1.png" title="Click to Call using API"></a>
                           <?php
                           }
                           else
                           {
                           ?>                   
                              <a href="JavaScript:void(0)" class="cicon_btn <?php echo ($row->cus_mobile)?'set_call_schedule_from_app':''; ?>" data-leadid="<?php echo $row->l_id;?>" data-mobile="<?php echo $row->cus_mobile; ?>" data-contactperson="<?php echo $row->cus_contact_person; ?>"><img src="<?php echo assets_url(); ?>images/cicon1.png" title="Click to Call from LMSBABA app"></a>
                                 <?php
                                 }
                           ?>
                             <?php }else{ ?>
                              <a href="JavaScript:void(0)" class="cicon_btn get_alert" data-text="Oops! There is no mobile number added to the company."><img src="<?php echo assets_url(); ?>images/cicon1-disabled.png" title="Mobile nummber is missing"></a>
                             <?php } ?>
                        </li>
                        <li>
                           <?php if($row->cus_mobile!='' && $row->cus_country_id!='0'){ 
                                 if($row->cus_mobile_whatsapp_status==2)
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
                           <a href="JavaScript:void(0);" class="cicon_btn web_whatsapp_popup"  data-leadid="<?php echo $row->l_id;?>" data-custid="<?php echo $row->l_customer_id;?>" title="<?php echo $whatsapp_title; ?>"><img src="<?php echo assets_url(); ?>images/<?php echo $whatsapp_image; ?>"></a>
                        <?php }else{ ?>
                           <a href="JavaScript:void(0);" class="cicon_btn get_alert"  data-text="Oops! There is no mobile number added to the company."><img src="<?php echo assets_url(); ?>images/social-whatsapp-disabled.png" title="Mobile nummber is missing"></a>
                        <?php } ?>
                        </li>
                        <li>
                           <?php if($row->cus_email){ ?>                 
                           <a href="JavaScript:void(0)" class="cicon_btn open_cust_reply_box" data-leadid="<?php echo $row->l_id;?>" data-custid="<?php echo $row->l_customer_id;?>"><img src="<?php echo assets_url(); ?>/images/cicon3.png"></a>
                        <?php }else{ ?>
                           <a href="JavaScript:void(0)" class="cicon_btn get_alert" data-leadid="<?php echo $row->l_id;?>" data-custid="<?php echo $row->l_customer_id;?>" data-text="Oops! There is no email added to the company."><img src="<?php echo assets_url(); ?>/images/cicon3-disabled.png"></a>
                        <?php } ?>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </td>
   </tr>
   <?php } ?>
<?php } ?>

