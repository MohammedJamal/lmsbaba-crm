<?php
if(strtolower($company['state'])==strtolower($customer['state']))
{
  $is_same_state='Y';
}
else
{
  $is_same_state='N';
}
?>
<?php $this->load->view('include/header');?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<!-- <link rel="stylesheet" href="<?=base_url();?>assets/plugins/jquery-ui/jquery-ui.min.css"> -->
<style>
td span{
  font-weight: 400;
}
.fontsize13{ font-size: 13px; }
.wrapper-ar {
  /*width: 80%;*/
  margin: 0 auto;
  /*border: solid 1px #ddd;*/
  padding: 30px; font-size: 13px;
}

.header-border tr {
border: solid 1px #222;
}
/*.mce-content-body{ font-size: 13px !important; }*/
table #tinymce{ font-size: 13px !important; }
.header-border,
.header-border td,
.header-border th {
border: 1px solid #8c8c8c;
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
</style>

<script src="<?=base_url();?>assets/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
  
  tinymce.init({
    selector: 'textarea.basic-wysiwyg-editor',
    force_br_newlines : true,
    force_p_newlines : false,
    forced_root_block : '',
    menubar: false,
    statusbar: false,
    toolbar: false,    
    setup: function(editor) {
        editor.on('focusout', function(e) {
          //console.log(editor);          
          var quotation_id=$("#quotation_id").val();
          var updated_field_name=editor.id;
          var updated_content=editor.getContent();
          fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
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
          var quotation_id=$("#quotation_id").val();
          var updated_field_name=editor.id;
          var updated_content=editor.getContent();
          fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
          //check_submit();
      })
    }
  });  
  
</script>

<div class="main-panel" style="margin-left:0;">       
  <div class="min_height_dashboard"></div>
    <div class="main-content">
      <div class="content-view">
        <div class="container">
          <div class="lead_opportunity">
         		<div class="tab" style="display: none;">
              <button class="tablinks" onClick="openTab(event, 'spet1')" id="defaultOpen">1. Question Cover Letter</button>
              <button class="tablinks" onClick="openTab(event, 'step2')" id="defaultOpen2">2. Product Details & Pricing</button>
              <button class="tablinks" onClick="openTab(event, 'step3')" id="defaultOpen3">3. Terms & Conditions</button>
            </div>
            <input type="hidden" id="quotation_id" value="<?php echo $quotation['id']; ?>" >

            <!-- STEP 1 : START -->
            <div id="spet1" class="tabcontent">            
              <div>&nbsp;</div>

              <div class="wrapper-ar" style="padding-top:0px;">
                  <div class="heading-top-bar" style="background: #f2f2f2; border: solid 1px #ddd; height: 60px; display: table; width: 100%;">
                    <div style="display: table-cell;    vertical-align: middle;">
                      <center>
                        <h1 style="margin: 0;">QUOTATION</h1>
                      </center>
                    </div>
                  </div>
                 <div>&nbsp;</div>




                  <div class="col-md-9">
                    <div class="col-md-2">
                    <?php
                    $logo=$company['logo'];
                    if($logo!='')
                    {
                      $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['lms_url']."/company/logo/thumb/".$logo;
                    }
                    else
                    {
                      $profile_img_path = base_url().'images/user_img_icon.png';
                    }

                    ?>
                    <img src="<?php echo $profile_img_path;?>" style="width: 124px; height: 100px;">
                    </div>
                    <div class="col-md-9">
                      <h2  style="font-weight: 400; font-size: 22px; line-height: 25px;" /><?php echo $company['name'];?></h2>
                      <p style="font-size: 12px;"><?php rander_company_address('generate_quotation'); ?></p>
                    </div>
                  </div>
                  <div class="col-md-3">
                     <ul style="list-style: none; float: right;" class="co-details">
                      <li>
                        <img src="<?php echo base_url(); ?>assets/images/msg-icon.png" style="position: relative; padding-right: 3px;" width="16"> <?php echo $company['email1'];?>
                      </li>
                      <li>
                        <img src="<?php echo base_url(); ?>assets/images/mobile-phone-icon.png" style="position: relative;padding-right: 3px;" width="16"> <?php echo $company['mobile1'];?></li>
                      <li>
                        <img src="<?php echo base_url(); ?>assets/images/web.png" style="position: relative;padding-right: 3px; padding-top: 0px;" width="16"> 
                        <?php echo $company['website'];?>
                      </li>
                    </ul>
                  </div>
                  <table class="header-border" border="0" style="border: 0px; width: 100%; text-align: center;">
                    <tr>
                      <td width="25%">Quote Date<br>
                        <span><?php echo date_db_format_to_display_format($quotation['quote_date']); ?></span>
                      </td>
                      <td width="25%">Quote No<br>
                        <span><?php echo $quotation['quote_no']; ?></span>
                      </td>
                      <td width="25%">Enquiry Ref<br>
                        <span><?php echo get_company_name_initials(); ?> - <?php echo $quotation_data['lead_opportunity_data']['lead_id']; ?></span>
                      </td>
                      <td width="25%">Quote Valid Untill<br>                         
                          <span>
                          <input type="text" class="border-solid calender-input text-input display_date letter_valid_untill_date_update" name="quote_valid_until" id="quote_valid_until" value="<?php echo date_db_format_to_display_format($quotation['quote_valid_until']); ?>" style="width: 110px;border: 1px solid #c9d4ea" readonly="true">
                          <i class="fa fa-calendar calender-icon" aria-hidden="true"></i>
                          </span>
                      </td>
                    </tr>
                  </table>
                  <div>&nbsp;</div>
                  <div class="letter-header-left" style="width: 80%; float: left;">
                    <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">To:</b></h4>
                    <p style="margin-top: 3px; font-size: 13px; width: 300px;">
                      <textarea class="basic-wysiwyg-editor" name="letter_to" id="letter_to"  rows="5"><?php echo $quotation['letter_to']; ?></textarea>
                    </p>
                  </div>
                  <div>&nbsp;</div>
                  <div class="letter-header-left" style="width: 100%; float: left;">

                      <h4 style="margin-bottom: 5px; font-size: 13px;">
                        <b style="line-height: 37px;">Subject:</b></h4> 
                      <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                        <input type="text" class="border-solid text-input letter_update" name="letter_subject" id="letter_subject" value="<?php echo $quotation['letter_subject']; ?>" style="width: 100%; margin-left: 0px; font-size: 14px; color: #000; height: 34px; padding: 4px;border: 1px solid #c9d4ea" placeholder="Write Quotation Subject...">
                      </p>

                  </div>
                  <div>&nbsp;</div>
                  <br>
                  <div style="width: 100%; clear: both">    
                    <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Dear Sir/Ma’am:</b></h4>
                    <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                      <textarea class="basic-wysiwyg-editor" name="letter_body_text" id="letter_body_text"><?php echo $quotation['letter_body_text']; ?></textarea>
                    </p>                      
                  </div>
              </div>           
              <div class="page_one">
                  <a href="#" onClick="open_step2()">Save & Continue</a>
              </div>                 
            </div>         
            <!-- STEP 1 : END -->


            <!-- STEP 2 : START -->
            <?php 
            if(strtoupper($quotation['currency_type'])=='INR'){
              $is_tax_show='Y';
            }
            else{
              $is_tax_show='N';
            }
            ?>			      
            <div id="step2" class="tabcontent">            	
                <div>&nbsp;</div>
                <div class="wrapper-ar" style="padding-top:0px;">
                    <div class="heading-top-bar" style="background: #f2f2f2; border: solid 1px #ddd; height: 60px; display: table; width: 100%;">
                      <div style="display: table-cell;    vertical-align: middle;">
                        <center>
                          <h1 style="margin: 0;">QUOTATION</h1>
                        </center>
                      </div>
                    </div>
                    <div>&nbsp;</div>
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
                    <table style=" width: 100%;" class="table table-striped custom-table">                      
                        <thead>
                          <tr>
                              <th width="5%">#</th>
                              <th width="50%">Product Details</th>
                              <th width="5%">Unit</th>
                              <th width="10%">Rate (<?php echo $quotation['currency_type']; ?>)</th>
                              <th width="5%">Qty</th>  
                              <?php if($is_tax_show=='Y'){ ?>
                                <?php if($is_same_state=='Y'){ ?>
                              <th width="5%">SGST</th>
                              <th width="5%">CGST</th>   
                                <?php }else{ ?>
                              <th width="10%">IGST</th>
                                <?php } ?>  
                              <?php } ?> 
							  <?php if($is_discount_available=='Y'){ ?>
                              <th width="5%">Discount</th>
							  <?php }else{ ?>
							  <!--<th width="5%"></th>-->
							  <?php } ?>
							  <th width="100%" <?php echo $discount_wise_colspan; ?>>Amount (<?php echo $quotation['currency_type']; ?>)</th>						  
                          </tr>
                        </thead>
                        <tbody>
                        <?php    
                        //print_r($prod_list);                
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
                            $item_discount_per=$output->discount; 
                            $item_price= $output->price;
                            $item_qty=$output->unit;

                            $item_total_amount=($item_price*$item_qty);
                            $row_discount_amount=$item_total_amount*($item_discount_per/100);
                            $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                            $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                            $sub_total=$sub_total+$row_final_price;

                            $total_price=$total_price+$item_total_amount;
                            $total_discounted_price=$total_discounted_price+$row_discount_amount;
                            $total_tax_price=$total_tax_price+$row_gst_amount;
                          ?>
                          <tr>
                            <td valign="top"><?php echo $s;?></td>
                            <td style="text-align: left;" valign="top">
                              <h3 class="fontsize13" style="font-weight: bold;"><?php echo $output->product_name;?> (Code: <?php echo $output->product_sku;?>)</h3>
                              <p class="fontsize13" style=""><?php echo $output->description;?></p>
                            </td>
                            <td valign="top"><?php echo $output->unit_type;?></td>
                            <td valign="top"><?php echo $output->price;?></td>
                            <td valign="top"><?php echo $output->unit;?></td>
                            <?php if($is_tax_show=='Y'){ ?>
                              <?php if($is_same_state=='Y'){ ?>
                            <td valign="top"><?php echo $item_sgst_per.'%';?></td>
                            <td valign="top"><?php  echo $item_cgst_per.'%'; ?></td>
                              <?php }else{ ?>
                            <td valign="top"><?php echo $item_gst_per.'%';?></td>
                                <?php } ?>  
                            <?php } ?>
							<?php if($is_discount_available=='Y'){ ?>
                            <td valign="top"><?php echo $output->discount;?>%</td>
							<?php }else{ ?>
							<!--<td valign="top"></td>-->
							<?php } ?>
                            <td valign="top" <?php echo $discount_wise_colspan; ?>><?php echo number_format($row_final_price,2);?></td>
                          </tr>
                          <?php
                          $s++;
                          $i++;
                          }
                        }
                        ?>

                        <?php if(count($selected_additional_charges)){?>
                          <?php 
                            foreach($selected_additional_charges AS $charge)
                            {
                                
                                $i++; 
                                $item_gst_per= $charge->gst;
                                $item_sgst_per= ($item_gst_per/2);
                                $item_cgst_per= ($item_gst_per/2);  
                                $item_discount_per=$charge->discount; 
                                $item_price= $charge->price;
                                $item_qty=1;

                                $item_total_amount=($item_price*$item_qty);
                                $row_discount_amount=$item_total_amount*($item_discount_per/100);
                                $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                                $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                                $sub_total=$sub_total+$row_final_price; 

                                $total_price=$total_price+$item_total_amount;
                                $total_tax_price=$total_tax_price+$row_gst_amount;
                                $total_discounted_price=$total_discounted_price+$row_discount_amount;
                            ?>    
                            <tr>
                              <td valign="top"><?php echo $s;?></td>
                              <td style="text-align: left;" valign="top">
                                <h3 class="fontsize13" style="font-weight: bold;"><?php echo $charge->additional_charge_name;?></h3>
                              </td>
                              <td valign="top">-</td>
                              <td valign="top"><?php echo $charge->price;?></td>
                              <td valign="top">1</td>
                              <?php if($is_tax_show=='Y'){ ?>
                                <?php if($is_same_state=='Y'){ ?>
                              <td valign="top"><?php  echo $item_sgst_per.'%';?></td>
                              <td valign="top"><?php  echo $item_cgst_per.'%';?></td>
                                <?php }else{ ?>
                              <td valign="top"><?php echo $item_gst_per.'%';?></td>
                                <?php } ?>  
                              <?php } ?>
							  <?php if($is_discount_available=='Y'){ ?>
                              <td valign="top"><?php echo $charge->discount;?>%</td><?php }else{ ?>
							  <!--<td valign="top"></td>-->
							  <?php } ?>
                              <td valign="top" <?php echo $discount_wise_colspan; ?>><?php echo number_format($row_final_price,2);?></td>
                            </tr>
                          <?php 
                              $s++; 
                            } 
                          ?>
                        <?php 
                        } 
                        ?>
					
                        <tr class="no-color">
						<?php	
                        //if($is_tax_show=='Y' && $is_same_state=='N'){ $clsp=7;}else{$clsp=8;}
                        //if($is_tax_show=='N'){ $clsp=6;}
						if($is_tax_show=='Y'){$clsp=($is_same_state=='N')?'6':'7';}else{$clsp='6';}
            			?>						
                          <td colspan="<?php echo ($clsp+$discount_wise_colspan_cnt); ?>" style="text-align: right;"><h3 style="margin: 0px; font-size: 13px;font-weight: 900">Grand Total (<?php echo $quotation['currency_type']; ?>)</h3></td>
                          <td <?php echo $discount_wise_colspan; ?>><?php echo number_format($sub_total,2);?></td>
                        </tr>

                        <tr>
                          <td colspan="3" style="border: none">&nbsp;</td>
                          <td colspan="<?php echo ($clsp-1); ?>" class="nopad" style="border: none;">
                            <table class="grand-summery" width="100%" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="2"><b>Details (<?php echo $quotation['currency_type']; ?>)</b></td>
                              </tr>
                              <tr>
                                <td>Total Price</td>
                                <td><?php echo number_format($total_price,2); ?></td>
                              </tr>
							  <?php if($is_discount_available=='Y'){ ?>
                              <tr>
                                <td>Total Discount</td>
                                <td><?php echo number_format($total_discounted_price,2); ?></td>
                              </tr>
							  <?php } ?>
							 
                              <?php if($is_tax_show=='Y'){ ?>
                              <tr>
                                <td>Total Tax</td>
                                <td><?php echo number_format($total_tax_price,2); ?></td>
                              </tr>
                              <?php } ?>
                              <tr>
                                <td>Grand Total (Round Off)</td>
                                <td><?php echo number_format(round($sub_total),2); ?></td>
                              </tr>
                              <tr>
                                <td colspan="2" style="text-align: left"><b><?php echo number_to_word(round($sub_total)); ?> (<?php echo $quotation['currency_type']; ?>)</b></td>
                              </tr>  
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div>&nbsp;</div>
                    <div class="row">
                    	<div class="col-md-12 ff">
                        <label class="check-box-sec">
                          <input type="checkbox" name="is_product_image_show_in_quotation" id="is_product_image_show_in_quotation" class="letter_update"  <?php if($quotation['is_product_image_show_in_quotation']=='Y'){echo 'CHECKED';}?> >
                          <span class="checkmark"></span>
                        </label>
                    		 Show product images on quotation if available.
                    	</div>
                      <div>&nbsp;</div>
                      <div class="col-md-12 ff">
                        <label class="check-box-sec">
                          <input type="checkbox" name="is_product_brochure_attached_in_quotation" id="is_product_brochure_attached_in_quotation" class="letter_update"  <?php if($quotation['is_product_brochure_attached_in_quotation']=='Y'){echo 'CHECKED';}?> >
                          <span class="checkmark"></span>
                        </label>
                         Attached product brochure to buyer by mail if available.
                      </div>
                      <div>&nbsp;</div>
                      <div class="col-md-12 ff">
                        <label class="check-box-sec">
                          <input type="checkbox" name="is_company_brochure_attached_in_quotation" id="is_company_brochure_attached_in_quotation" class="letter_update"  <?php if($quotation['is_company_brochure_attached_in_quotation']=='Y'){echo 'CHECKED';}?> >
                          <span class="checkmark"></span>
                        </label>
                         Attached company brochure to buyer by mail if avilable.
                      </div>                   	
                    </div>
                    
                </div>

                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <?php //print_r($quotation); ?>                
                <div class="page_one">
                  <a href="#" onClick="open_step1()">Back</a>
                  <a href="#" onClick="open_step3()">Save & Continue</a>
                </div>   
                <div>&nbsp;</div>
                <div>&nbsp;</div>
            </div> 
            <!-- STEP 2 : END -->


            <!-- STEP 3 : START -->
            <div id="step3" class="tabcontent">  
                <div>&nbsp;</div>
                <div class="wrapper-ar" style="position: relative;padding-top:0px;" >
                  <div class="heading-top-bar" style="background: #f2f2f2; border: solid 1px #ddd; height: 60px; display: table; width: 100%;">
                    <div style="display: table-cell;    vertical-align: middle;">
                      <center>
                        <h1 style="margin: 0;">QUOTATION</h1>
                      </center>
                    </div>
                  </div>
                  <div>&nbsp;</div>
                	<div class="text">
                        <div style="width: 100%; clear: both">    
                          <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Terms & Conditions:</b> <?php if(count($terms)){ ?>( <a href="JavaScript:void(0);" id="all_check_terms_show_in_letter">Select All</a> / <a href="JavaScript:void(0);" id="all_uncheck_terms_show_in_letter">Remove All</a> )<?php } ?></h4>
                          <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                            <div class="panel-group" id="accordion">
                              <?php 
                              if(count($terms))
                              {                            
                                foreach($terms as $term)
                                {
                                  ?>
                                  <div class="panel panel-default">
                                    <div class="panel-heading">
                                      <h4 class="panel-title ff">
                                        
                                        <label class="check-box-sec">
                                          <input type="checkbox" id="is_terms_show_in_letter_<?php echo $term['id']; ?>" class="is_terms_show_in_letter" data-id="<?php echo $term['id']; ?>" <?php if($term['is_display_in_quotation']=='Y'){echo 'CHECKED';}?> name="is_terms_show_in_letter">
                                          <span class="checkmark"></span>
                                        </label>
                                         <!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $term['id']; ?>"> --><?php echo $term['name']; ?><!-- </a> -->
                                      </h4>
                                    </div>
                                    <div id="collapse_<?php echo $term['id']; ?>" class="panel-collapse collapse">
                                      <div class="panel-body">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <textarea class="border-solid text-input terms_update" data-id="<?php echo $term['id']; ?>" style="width: 100%;  font-size: 13px; color: #000; padding: 4px;border: 1px solid #c9d4ea"><?php echo $term['value']; ?></textarea>
                                          </div>
                                        </div>                                        
                                      </div>
                                    </div>

                                  </div>
                                  <?php
                                }
                              }
                              ?>
                            </div>
                          </p>                      
                        </div> 

                        
                         
                        <?php /* ?>
                        <h4 style="margin-bottom: 5px; font-size: 13px;"><b>Terms & Conditions:</b></h4>
                        <table class="table table-condensed">
                        	<tr>
                        		<td colspan="3" ><a href="JavaScript:void(0);" id="all_check_terms_show_in_letter">Select All</a> / <a href="JavaScript:void(0);" id="all_uncheck_terms_show_in_letter">Remove All</a></td>
                        	</tr>
                          <?php 
                          if(count($terms))
                          {
                            foreach($terms as $term)
                            {
                              ?>
                              <tr>
                                <td width="5%"><input type="checkbox" id="is_terms_show_in_letter_<?php echo $term['id']; ?>" class="is_terms_show_in_letter" data-id="<?php echo $term['id']; ?>" <?php if($term['is_display_in_quotation']=='Y'){echo 'CHECKED';}?> name="is_terms_show_in_letter"></td>
                                <td width="20%"><b><?php echo $term['name']; ?></b></td>
                                <td width="70%" align="left">
                                  <textarea class="border-solid text-input terms_update" data-id="<?php echo $term['id']; ?>" style="width: 95%; margin-left: 10px; font-size: 13px; color: #000; height: 80px; padding: 4px;border: 1px solid #c9d4ea"><?php echo $term['value']; ?></textarea></td>
                              </tr>
                              <?php
                            }
                          }
                          ?>
                        </table>                        
                        <?php */ ?>
                    </div>
                    <div class="text">
                        <div style="width: 100%; clear: both">    
                          <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Other Terms & Conditions:</b></h4>
                          <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                            <textarea class="border-solid text-input letter_update" style="width: 100%; font-size: 13px; color: #000; height: 80px;border: 1px solid #c9d4ea;" name="letter_terms_and_conditions" id="letter_terms_and_conditions"><?php echo $quotation['letter_terms_and_conditions']; ?></textarea>
                          </p>                      
                        </div>                       
                    </div>
                    <?php if($company['quotation_bank_details1']!='' || $company['quotation_bank_details2']!=''){ ?>
                    <div class="row">
                      	<div class="col-md-6">
                          <div class="text">
                              <div style="width: 100%; clear: both">  
                                  
                                <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">
                                  <label class="check-box-sec">
                                    <input type="checkbox" name="is_quotation_bank_details1_send" id="is_quotation_bank_details1_send" class="letter_update"  <?php if($quotation['is_quotation_bank_details1_send']=='Y'){echo 'CHECKED';}?> >
                                    <span class="checkmark"></span>
                                  </label>
                                  Banker’s Detail 1:</b>
                                </h4>
                                <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                                  <textarea class="basic-wysiwyg-editor" name="quotation_bank_details1" id="quotation_bank_details1" rows="6"><?php echo $company['quotation_bank_details1']; ?></textarea>                                
                                </p>
                                <?php /* ?>
                                <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                                  <b>For Credit to:</b> <?php echo $company['bank_credit_to']; ?><br>
                                  <b>Account Number:</b> <?php echo $company['bank_acount_number']; ?><br>
                                  <?php if(strtoupper($quotation['currency_type'])!='INR'){ ?>
                                  <b>Swift Number:</b> <?php echo $company['bank_swift_number']; ?><br>
                                  <b>Telex:</b> <?php echo $company['bank_telex']; ?><br>
                                  <?php } ?>                                  
                                  <b>Account of:</b> <?php echo $company['bank_name']; ?><br>
                                  <b>IFSC/RTGS Code:</b> <?php echo $company['bank_ifsc_code']; ?><br>
                                  <b>Brance Code:</b> <?php echo $company['bank_branch_code']; ?><br>
                                  <b>Bank Address:</b> <?php echo $company['bank_address']; ?>
                                </p>           
                                <?php */ ?>           
                              </div> 
                              <!-- <p><?php //echo $quotation['letter_banker_details']; ?></p> -->
                              <!-- <p><textarea class="basic-wysiwyg-editor" name="letter_banker_details" id="letter_banker_details" rows="50"><?php echo $quotation['letter_banker_details']; ?></textarea></p> -->
                          </div>
                      	</div>
                        <?php if($company['quotation_bank_details2']){ ?>
                      	<div class="col-md-6">
                            <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">
                                <label class="check-box-sec">
                                  <input type="checkbox" name="is_quotation_bank_details2_send" id="is_quotation_bank_details2_send" class="letter_update"  <?php if($quotation['is_quotation_bank_details2_send']=='Y'){echo 'CHECKED';}?> >
                                  <span class="checkmark"></span>
                                </label>
                                Banker’s Detail 2:</b>
                            </h4>
                            <p class="fontsize13" style="margin-top: 0px; font-size: 13px;"><textarea class="basic-wysiwyg-editor" name="quotation_bank_details2" id="quotation_bank_details2" rows="6"><?php echo $company['quotation_bank_details2']; ?></textarea></p>
                            <?php/* if(strtoupper($quotation['currency_type'])!='INR'){ ?>
                            <div class="text">
                              <div style="width: 100%; clear: both">    
                                <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Correspondent Bank:</b></h4>
                                <?php
                                $letter_correspondent_bank=$company['correspondent_bank_name'].'<br><b>Swift</b> : '.$company['correspondent_bank_swift_number'].'<br><b>IOB Account</b> : '.$company['correspondent_account_number'];
                                ?>
                                <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                                  <?php echo $letter_correspondent_bank; ?>
                                </p>                      
                              </div> 
                            </div>
						                <?php }*/ ?>
						            </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <?php if($company['gst_number']){ ?>
                    <div style="margin-top:8px;">
                        <div style="width: 100%; clear: both">    
                            <h4 style="margin-bottom: 5px; font-size: 13px;">
                              <b style="line-height: 37px;">
                                <label class="check-box-sec">
                                  <input type="checkbox" name="is_gst_number_show_in_quotation" id="is_gst_number_show_in_quotation" class="letter_update"  <?php if($quotation['is_gst_number_show_in_quotation']=='Y'){echo 'CHECKED';}?> >
                                  <span class="checkmark"></span>
                                </label>
                                GST: <?php echo $company['gst_number']; ?>
                              </b>
                            </h4>                    
                        </div>                      
                    </div>
                    <?php } ?>
                    

                    <div style="margin-top:8px;">
                        <div style="width: 100%; clear: both">    
                          <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Write Quotation facilitation Text (Letter Footer):</b></h4>
                          <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                            <textarea class=" basic-wysiwyg-editor" name="letter_footer_text" id="letter_footer_text"><?php echo $quotation['letter_footer_text']; ?></textarea>
                          </p>                      
                        </div>                      
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div style="width: 100%; clear: both">    
                          <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Thanks & Regards:</b></h4>
                          <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                            <textarea class="basic-wysiwyg-editor" name="letter_thanks_and_regards" id="letter_thanks_and_regards" rows="6"><?php echo $quotation['letter_thanks_and_regards']; ?></textarea>
                          </p>                      
                        </div> 
                      </div>
                    </div>
					
					<!--<div class="row">
						<div class="col-md-6">
							<div class="col-md-12">
							<form action="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/generate_upload_view');?>" method="post" enctype="multipart/form-data">
							<div class="row">
							<div class="col-md-5">
							<input type="text" name="doc_name" id="doc_name" placeholder="Document Name" />
							<input type="hidden" name="quotation_id" value="<?php echo $quotation_id; ?>"  />
							<input type="hidden" name="opportunity_id" value="<?php echo $opportunity_id; ?>"  />
							</div>
							<div class="col-md-5">
							<input type="file" name="userfile" id="userfile" />
							</div>
							<div class="col-md-2">	
							<input type="submit" name="submit" id="submit" value="submit" />
							</div>
							</div>
							</form>
							</div>				
						</div>					
					</div>-->
                    
                </div>  
                <div>&nbsp;</div>                
                <div class="page_three">
                    <!-- <p>Page 3</p>   -->
                    <a href="#" onClick="open_step2()">Back</a>
                    <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$opportunity_id.'/'.$quotation_id);?>" target="_blank">Preview Quotation</a>

                  <?php //if(is_attribute_available('email_service')==TRUE){ ?>

                    <?php if($customer['email']!='' || $customer['alt_email']!=''){ ?>
                      <?php if($email_forwarding_setting['is_mail_send']=='Y'){ ?>
                        <?php if($email_forwarding_setting['is_send_mail_to_client']=='Y' || $email_forwarding_setting['is_send_relationship_manager']=='Y' || $email_forwarding_setting['is_send_manager']=='Y' || $email_forwarding_setting['is_send_skip_manager']=='Y'){ ?>
                      <a href="javascript:" id="qutation_send_to_buyer" data-opportunityid="<?php echo $opportunity_id; ?>" data-quotationid="<?php echo $quotation_id; ?>">Send Quotation to the Buyer</a>
                        <?php } ?>
                      <?php } ?>
                    <?php }else{ ?>
                      <a href="javascript:" onclick="get_alert('Oops! Customer mail not set.')">Send Quotation to the Buyer</a>
                    <?php } ?>
                  <?php /*}else{ ?>
                    <a href="javascript:alert('Sorry!You have restricted to get the functionality.');" id="" style="text-decoration: line-through;">Send Quotation to the Buyer</a>
                      <?php }*/ ?>
                    <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_id.'/'.$quotation_id);?>" target="_blank" >Download Quotation to Send Buyer</a>
                </div>   

                <div>&nbsp;</div>
                <div>&nbsp;</div> 		
				                
            </div>        
            <!-- STEP 3 : END -->


 	        </div>
        </div>				
      </div>     
    </div>      
</div>     
<?php //$this->load->view('include/modal-html'); ?>
   
<script type="text/javascript">
  window.paceOptions = {
    document: true,
    eventLag: true,
    restartOnPushState: true,
    restartOnRequestAfter: true,
    ajax: {
      trackMethods: [ 'POST','GET']
    }
  };
</script>
<?php //$this->load->view('include/footer');?>
<!-- endbuild -->
<script src="<?=base_url();?>vendor/jquery/dist/jquery.js"></script>

<script src="<?=base_url();?>vendor/jquery/dist/jquery.js"></script>
<script src="<?=base_url();?>vendor/pace/pace.js"></script>
<script src="<?=base_url();?>vendor/tether/dist/js/tether.js"></script>
<script src="<?=base_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?=base_url();?>vendor/fastclick/lib/fastclick.js"></script>
<script src="<?=base_url();?>scripts/constants.js"></script>
<script src="<?=base_url();?>scripts/main.js"></script>
<!-- endbuild -->
<!-- page scripts -->
<script src="<?=base_url();?>vendor/parsleyjs/dist/parsley.min.js"></script>
<script src="<?=base_url();?>scripts/helpers/tsf/js/tsf-wizard-plugin.js"></script>
<link rel="stylesheet" href="<?=base_url();?>vendor/blueimp-file-upload/css/jquery.fileupload.css"/>
<link rel="stylesheet" href="<?=base_url();?>vendor/blueimp-file-upload/css/jquery.fileupload-ui.css"/>
<!-- end page scripts -->
<!-- page scripts -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script> -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?=base_url();?>vendor/jquery.ui/ui/core.js"></script>
<script src="<?=base_url();?>vendor/jquery.ui/ui/widget.js"></script>
<script src="<?=base_url();?>vendor/jquery.ui/ui/mouse.js"></script>
<script src="<?=base_url();?>vendor/jquery.ui/ui/draggable.js"></script>
<script src="<?=base_url();?>vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
<script src="<?=base_url();?>vendor/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
<script src="<?=base_url();?>vendor/blueimp-file-upload/js/jquery.fileupload.js"></script>
<script src="<?=base_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>

<!-- select2 -->
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.toaster.js"></script>
<script>
      // var interval;
      // var codetmpl = "<code>%codeobj%</code><br><code>%codestr%</code>";

      $(document).ready(function ()
      {
        // randomToast();

        // $('#btnstart').click(start);
        // $('#btnstop').click(stop);
        // $('#preptoast').on('submit', maketoast);

        // start();

      });

      // function start ()
      // {
      //   if (!interval)
      //   {
      //     interval = setInterval(function ()
      //     {
      //       randomToast();
      //     }, 1500);
      //   }
      //   this.blur();
      // }

      // function stop ()
      // {
      //   if (interval)
      //   {
      //     clearInterval(interval);
      //     interval = false;
      //   }
      //   this.blur();
      // }

      function successToast(msg)
      {
        var priority = 'success';
        var title    = 'Success';
        var message  = msg;

        $.toaster({ priority : priority, title : title, message : message });
      }

      // function randomToast ()
      // {
      //   var priority = 'success';
      //   var title    = 'Success';
      //   var message  = 'It worked!';

      //   $.toaster({ priority : priority, title : title, message : message });
      // }

      // function maketoast (evt)
      // {
      //   evt.preventDefault();

      //   var options =
      //   {
      //     priority : $('#toastPriority').val() || null,
      //     title    : $('#toastTitle').val() || null,
      //     message  : $('#toastMessage').val() || 'A message is required'
      //   };

      //   if (options.priority === '<use default>')
      //   {
      //     options.priority = null;
      //   }

      //   var codeobj = [];
      //   var codestr = [];

      //   var labels = ['message', 'title', 'priority'];
      //   for (var i = 0, l = labels.length; i < l; i += 1)
      //   {
      //     if (options[labels[i]] !== null)
      //     {
      //       codeobj.push([labels[i], "'" + options[labels[i]] + "'"].join(' : '));
      //     }

      //     codestr.push((options[labels[i]] !== null) ? "'" + options[labels[i]] + "'" : 'null');
      //   }

      //   if (codestr[2] === 'null')
      //   {
      //     codestr.pop();
      //     if (codestr[1] === 'null')
      //     {
      //       codestr.pop();
      //     }
      //   }

      //   codeobj = "$.toaster({ " + codeobj.join(", ") + " });"
      //   codestr = "$.toaster(" + codestr.join(", ") + ");"

      //   $('#toastCode').html(codetmpl.replace('%codeobj%', codeobj).replace('%codestr%', codestr));
      //   $.toaster(options);
      // }
    </script>

<script src="<?php echo base_url();?>assets/js/common_functions.js"></script>
<script src="<?php echo base_url();?>assets/js/custom/quotation/generate_view.js"></script>
<!-- initialize page scripts -->
<script type="text/javascript">
  $('.timeline-toggle .btn').on('click', function (e) {
    var val = $(this).find('input').val();
    if (val === 'stacked') {
      $('.timeline').addClass('stacked');
    }
    else {
      $('.timeline').removeClass('stacked');
    }
  });
</script>
    
   
    <!-- end initialize page scripts -->
    <script type="text/javascript">
         $(document).ready(function () {  

         $("body").on("click",".terms_save",function(e){
            var id=$(this).attr('data-id');
            $("#collapse_"+id).removeClass("in"); 
            successToast("Record successfully updated.")
          });  

         $("body").on("click",".terms_close",function(e){
            var id=$(this).attr('data-id');
            $("#collapse_"+id).removeClass("in"); 
          });  


         /*	$('#file').on('change', function () {  
                    var form_data = $("#upload_form").serialize();                    
                    form_data.append("file", form_data);
                    $.ajax({
                        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/reset_profile_pic", // point to server-side PHP script                         
                       
                        data: form_data,
                        type: "POST",                        
                        async:true,	
                        contentType: false,       // The content type used when sending data to the server.
						cache: false,             // To unable request pages to be cached
						processData:false, 
                        success: function (response) {
                            alert(response);
                        },
                        error: function (response) {
                            alert('hello2');
                        }
                    });
                });
          */        	
         	
    // $('#datepicker').datepicker({
    //   dateFormat: "dd M yy"    
    // });

    // $('#datepicker2').datepicker({
    //   dateFormat: "dd M yy"
    // });
    
    
    $('#bank').on('click', function() {
      if ($("#bank").is(":checked")) {
      var flag=1;
  	}
  	else
  	{
  		flag=2;
  	}


  $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/GetBankDetailsAjax",
		type: "POST",
		data:  {'flag':flag,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
   }); 
   
   $('#statutory').on('click', function() {
    if ($("#statutory").is(":checked")) {
    var flag=1;
	}
	else
	{
		flag=2;
	}


  $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/GetStatutoryAjax",
		type: "POST",
		data:  {'flag':flag,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
   });    
   
  $('#submit_profile').on('click', function() {

   

     	 var datastring = $("#profile_form").serialize();
    $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/profile_update",
		type: "GET",
		data:  datastring,
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){		
		$('#edit_comp_addr_content').html(data);
		$('#profile_update').modal('toggle');
		},
		error: function(){} 	        
		});
  

});

$('#submit_to').on('click', function() {  

    var datastring = $("#to_form").serialize();
    $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateFromQuotationAjax",
		type: "GET",
		data:  datastring,
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){		
		$('#edit_to_content').html(data);
		$('#to_update').modal('toggle');
		},
		error: function(){} 	        
		});



});

$('#submit_sincere').on('click', function() {  

    var datastring = $("#sincere_form").serialize();
    $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateSincereQuotationAjax",
		type: "GET",
		data:  datastring,
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){		
		//$('#edit_to_content').html(data);
		$('#sincere_update').modal('toggle');
		},
		error: function(){} 	        
		});



});

$('#submit_user_list').on('click', function() {
	
    var datastring = $("#user_list_form").serialize();
    $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/AttachQuotationAjax",
		type: "GET",
		data:  datastring,
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){		
		//$('#edit_to_content').html(data);
		$('#sincere_update').modal('toggle');
		},
		error: function(){} 	        
		});
});

$('#get_user_list').on('click', function() {
     	 
    $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/user_list_ajax",
		type: "GET",
		data:  '',
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){		
		$('#user_list_all').html(data);
		$('#user_list').modal('toggle');
		},
		error: function(){} 	        
		});
});

$('#save_terms_conditions').on('click', function() {
	
if($("#save_terms_conditions").prop('checked') == true){
	var terms='1';
	}
	else
	{
		var terms='0';
	}
   

    var datastring = $("#sincere_form").serialize();
    $.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateTermsConditionAjax",
		type: "POST",
		data:  {'terms':terms,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		//$('#edit_to_content').html(data);
		
		},
		error: function(){} 	        
		});
});
});


  function edit_valid()
  {
		var content=document.getElementById('edit_valid_content').innerHTML;		
		document.getElementById('edit_valid_content').style.display='none';
	
		document.getElementById('datepicker').value = content;		
		document.getElementById('edit_valid_write').style.display='block';		
  }
	
	function save_valid()
  {
		var content=document.getElementById('datepicker').value;
		document.getElementById('edit_valid_write').style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateValidQuotationAjax",
		type: "POST",
		data:  {'valid_until':content,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
	
		document.getElementById('edit_valid_content').innerHTML = content;		
		document.getElementById('edit_valid_content').style.display='block';		
	}
	
	
	function edit_enquiry()
  {
		var content=document.getElementById('edit_enquiry_content').innerHTML;		
		document.getElementById('edit_enquiry_content').style.display='none';
	
		document.getElementById('datepicker2').value = content;		
		document.getElementById('edit_enquiry_write').style.display='block';		
	}
	
	function save_enquiry()
  {
		var content=document.getElementById('datepicker2').value;
		document.getElementById('edit_enquiry_write').style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateEnquiryQuotationAjax",
		type: "POST",
		data:  {'enquiry_date':content,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
	
		document.getElementById('edit_enquiry_content').innerHTML = content;		
		document.getElementById('edit_enquiry_content').style.display='block';		
	}
	
	function edit_last()
  {
    var content=document.getElementById('edit_last_content').innerHTML;		
		document.getElementById('edit_last_content').style.display='none';		
	
		tinymce.get('last_text').getBody().innerHTML = content;		
		document.getElementById('edit_to_last_content').style.display='block';		
	}
	
	function save_last()
  {
		var content=tinymce.get('last_text').getBody().innerHTML;		
		document.getElementById('edit_to_last_content').style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateFecilateQuotationAjax",
		type: "POST",
		data:  {'fecilate_text':content,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
		document.getElementById('edit_last_content').innerHTML = content;		
		document.getElementById('edit_last_content').style.display='block';		
	}
	function edit_terms()
  {
    	var content=document.getElementById('edit_terms_content').innerHTML;		
		document.getElementById('edit_terms_content').style.display='none';		
	
		tinymce.get('terms').getBody().innerHTML = content;		
		document.getElementById('edit_terms_write').style.display='block';		
	}
	
	function save_terms()
  {
		var content=tinymce.get('terms').getBody().innerHTML;		
		document.getElementById('edit_terms_write').style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateTermsQuotationAjax",
		type: "POST",
		data:  {'terms_condition':content,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
		document.getElementById('edit_terms_content').innerHTML = content;		
		document.getElementById('edit_terms_content').style.display='block';		
	}
	
	function edit_prod(product_id)
  {
    	var content=document.getElementById('edit_prod_content_'+product_id).innerHTML;		
		document.getElementById('edit_prod_content_'+product_id).style.display='none';
		
	
		document.getElementById('prod_text_'+product_id).value = content;		
		document.getElementById('edit_prod_write_'+product_id).style.display='block';
	}
	
	function save_prod(product_id)
  {
		var content=document.getElementById('prod_text_'+product_id).value;					
		document.getElementById('edit_prod_write_'+product_id).style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateProductNameQuotationAjax",
		type: "POST",
		data:  {'product_name':content,'product_id':product_id},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
		document.getElementById('edit_prod_content_'+product_id).innerHTML = content;		
		document.getElementById('edit_prod_content_'+product_id).style.display='block';		
	}
	
	function edit_cons()
  {
		var content=document.getElementById('edit_cons_content').innerHTML;		
		document.getElementById('edit_cons_content').style.display='none';
	
		tinymce.get('cons_text').getBody().innerHTML = content;		
		document.getElementById('edit_cons_write').style.display='block';		
	}
	
	function save_cons()
  {
		var content=tinymce.get('cons_text').getBody().innerHTML;		
		document.getElementById('edit_cons_write').style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateCoverLetterQuotationAjax",
		type: "POST",
		data:  {'cover_letter':content,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
	
		document.getElementById('edit_cons_content').innerHTML = content;		
		document.getElementById('edit_cons_content').style.display='block';		
	}
	
	function edit_comp()
  {
		var content=document.getElementById('edit_comp_content').innerHTML;		
		document.getElementById('edit_comp_content').style.display='none';
	
		tinymce.get('comp_text').getBody().innerHTML = content;		
		document.getElementById('edit_comp_write').style.display='block';		
	}
	
	function save_comp()
  {
		var content=tinymce.get('comp_text').getBody().innerHTML;		
		document.getElementById('edit_comp_write').style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateCompQuotationAjax",
		type: "POST",
		data:  {'about_company':content,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
	
		document.getElementById('edit_comp_content').innerHTML = content;		
		document.getElementById('edit_comp_content').style.display='block';		
	}
	
	function edit_subj()
  {
		var content=document.getElementById('edit_subj_content').innerHTML;		
		document.getElementById('edit_subj_content').style.display='none';
	
		tinymce.get('subj_text').getBody().innerHTML = content;		
		document.getElementById('edit_subj_write').style.display='block';		
	}
	
	function save_subj()
  {
		
		
		var content=tinymce.get('subj_text').getBody().innerHTML;		
		document.getElementById('edit_subj_write').style.display='none';
		
		$.ajax({url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/UpdateSubjectQuotationAjax",
		type: "POST",
		data:  {'subject':content,'quotation_id':'<?=$quotation_id?>'},
		async:true,	
		success: function(data){		
		
		},
		error: function(){} 	        
		});	
	
		document.getElementById('edit_subj_content').innerHTML = content;		
		document.getElementById('edit_subj_content').style.display='block';	
			
	}	
	
	

function GetStateList(cont,id)
{
		$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getstatelist",
			  type: "POST",
			  data: {'country_id':cont},		  
			  success: function (response) 
			  {
			  	if(response!='')
			  	{
					document.getElementById(id).innerHTML=response;
				}
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
	}
	
	function GetCityList(state,id)
	{
		$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getcitylist",
			  type: "POST",
			  data: {'state_id':state},		  
			  success: function (response) 
			  {
			  	if(response!='')
			  	{
					document.getElementById(id).innerHTML=response;
				}
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
}
 function sgst_calc(val)
 {
 	var sub_tot=$('#sub_tot_val').html();
 	
 	var res=val*parseInt(sub_tot)/100;
 	$('#sgst_val').html(res);
 	
 	$('#sub_tot').html(parseInt(res)+parseInt(sub_tot));
 	$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/update_sgst",
			  type: "POST",
			  data: {'sgst':val,'quotation_id':'<?=$quotation->id?>'},		  
			  success: function (response) 
			  {
			  	
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
 }
 function cgst_calc(val)
{
 	var sub_tot=$('#sub_tot_val').html();
 	var res=val*parseInt(sub_tot)/100;
 	$('#cgst_val').html(res);
 	$('#sub_tot').html(parseInt(res)+parseInt(sub_tot));
 	$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/update_cgst",
			  type: "POST",
			  data: {'cgst':val,'quotation_id':'<?=$quotation->id?>'},		  
			  success: function (response) 
			  {
			  	
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
 }
  function disc_calc(val)
 {
 	var sub_tot=$('#sub_tot_val').html();
 	var res=val*parseInt(sub_tot)/100;
 	$('#disc_val').html(res);
 	$('#sub_tot').html(parseInt(res)+parseInt(sub_tot));
 	$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/update_disc",
			  type: "POST",
			  data: {'disc':val,'quotation_id':'<?=$quotation->id?>'},		  
			  success: function (response) 
			  {
			  	
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
 }	
	

         	
$(document).ready(function () {

// Function to preview image after validation
$(function() {
  $("#image").change(function() {
  	
  	$.ajax({
  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/reset_profile_pic", // Url to which the request is send
  type: "POST",             // Type of request to be send, called as method
  data:  new FormData( $("#upload_form")[0] ), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
  contentType: false,       // The content type used when sending data to the server.
  cache: false,             // To unable request pages to be cached
  processData:false,        // To send DOMDocument or non processed data file it is set to false
  success: function(data)   // A function to be called if request succeeds
  {
  $('#comp_logo').attr('src',data);
  $('#comp_logo_menu').attr('src',data);
  $('#comp_logo_menu2').attr('src',data);
  $('#comp_logo_head').attr('src',data);

  }
});
//$("#message").empty(); // To remove the previous error message
});
});
function imageIsLoaded(e) {
  $("#image").css("color","green");

  $('#comp_logo').attr('src', e.target.result);
  $('#comp_logo_menu').attr('src', e.target.result);
  $('#comp_logo_menu2').attr('src', e.target.result);
  $('#comp_logo_head').attr('src', e.target.result);

  };
});     	
       
         	

function isValidEmailAddress(emailAddress) 
{
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
</script>
<!-- <script type="text/javascript" src="<?=base_url();?>tinymce/jscripts/tiny_mce/tiny_mce.js"></script> -->
<script type="text/javascript">
	// tinyMCE.init({
	// 	mode : "textareas",
	// 	theme : "advanced",
	// 	height: 300,
	// 	width: 850,
	// 	theme_advanced_toolbar_location : "top",
	// 	theme_advanced_toolbar_align : "left",
	// 	theme_advanced_statusbar_location : "bottom",
	// 	theme_advanced_resizing : true,
	// });	
</script>


