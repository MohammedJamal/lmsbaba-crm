<?php //print_r($quotation_data); ?>
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
.quotation-table .mce-edit-area iframe{
  max-height: 120px;
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
/*#mceu_4.mce-container{
  min-height: 170px !important;
  background: #FFF !important;
}*/
#letter_to_ifr{
  min-height: 120px !important;
}
#letter_subject_ifr{
  max-height: 40px !important;
}
</style>
<!-- <script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script> -->
<script type="text/javascript"> 
/* 
  tinymce.init({
    selector: 'textarea.basic-wysiwyg-editor',
    height: 100,
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
*/
</script>

<input type="hidden" id="quotation_id" value="<?php echo $quotation_id; ?>">
<div class="mobile-scoller">
<div class="quotation-search">
  <div class="heading-top-bar" style="background: #f2f2f2; border: solid 1px #ddd; height: 36px; display: table; width: 100%;">
     <div style="display: table-cell;    vertical-align: middle;">
        <center>
          <h1 class="q-title">
            <input type="text" class="default-input    quote_title_input" name="quote_title" id="quote_title" placeholder="" value="<?php echo ($quotation['quote_title'])?$quotation['quote_title']:'Quotation'; ?>" style="width: 250px; display:none;" >
            <span id="quote_title_div_outer">
              <span id="quote_title_div"><?php echo ($quotation['quote_title'])?$quotation['quote_title']:'Quotation'; ?></span>
            <a href="JavaScript:void(0);" id="quote_title_edit_icon"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 14px;"></i></a></span>
          </h1>
        </center>
     </div>
  </div>  
  <div class="table-h">
    <div class="payment-loop content-equel btb mt-15">
      <div class="row small-txt">
         <div class="col-md-3">
            <div class="auto-row">
               <label class="blue-title">Quotation Date:</label>
               <div class="full-width">
                  <div class="full-width">
                    <input type="text" class="calender-input display_date  date-input input_date quotation_update" name="quote_date" id="quote_date" value="<?php echo date_db_format_to_display_format($quotation['quote_date']); ?>" readonly="true"><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date">
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="auto-row">
               <label class="blue-title">Quotation Number:</label>
               <div class="full-width"><input type="text" class="date-input letter_update" name="quote_no" id="quote_no" placeholder="" value="<?php echo $quotation['quote_no']; ?>" ></div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="auto-row">
               <label class="blue-title">Enquiry Ref ID:</label>
               <div class="full-width"><input type="text" class="date-input" name="" id="" placeholder="" value="<?php echo get_company_name_initials(); ?> - <?php echo $quotation_data['lead_opportunity_data']['lead_id']; ?>" readonly></div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="auto-row">
               <label class="blue-title">Valid Till:</label>
              <div class="full-width">
                <input type="text" class="calender-input display_date letter_valid_untill_date_update date-input input_date" name="quote_valid_until" id="quote_valid_until" value="<?php echo date_db_format_to_display_format($quotation['quote_valid_until']); ?>" readonly="true"><img class="ui-datepicker-trigger" src="<?php echo assets_url(); ?>images/cal-icon.png" alt="Select date" title="Select date">
              </div>
            </div>
         </div>
      </div>
    </div>
  </div>
  
  <div class="letter-header-left small-txt" style="width: 100%; float: left;margin-top: 0px;display: inline-block;">
      <!--<label class="blue-title">To:</label>-->
      <div style="margin-top: 3px; font-size: 13px; width: 400px;">
          <textarea class="basic-wysiwyg-editor" name="letter_to" id="letter_to"  style="height: 500px;"><?php echo $quotation['letter_to']; ?></textarea>
      </div>
  </div>
  <div class="letter-header-left" style="width: 100%; float: left;margin-top: 15px;display: inline-block;">
    <!--<div style="margin-bottom: 5px;" class="blue-title-txt">
    Subject:
    </div>-->
    <div class="fontsize13" style="margin-top: 0px; font-size: 13px;">
      <!--<input type="text" class="border-solid text-input letter_update" name="letter_subject" id="letter_subject" value="<?php echo $quotation['letter_subject']; ?>" style="width: 100%; margin-left: 0px; font-size: 14px; color: #000; height: 34px; padding: 4px;border: 1px solid #c9d4ea" placeholder="Write Quotation Subject...">-->
	  <textarea class="basic-wysiwyg-editor" name="letter_subject" id="letter_subject" style="height: 2px;"><?php echo $quotation['letter_subject']; ?></textarea>
	</div>
  </div>  

  <div style="width: 100%; clear: both;margin-top: 15px;display: inline-block;">
    <p class="fontsize13" style="margin-top: 0px; font-size: 13px;">
        <textarea class="basic-wysiwyg-editor" name="letter_body_text" id="letter_body_text" style="height: 5px;"><?php echo $quotation['letter_body_text']; ?></textarea>
    </p>
  </div>
  <?php 
  if(strtoupper($quotation['currency_type'])=='INR'){
    $is_tax_show='Y';
  }
  else{
    $is_tax_show='N';
  }
  ?>
  <?php
  // IS DISCOUNT VAILABLE CHECKING : START
  $is_product_image_available='N';
  $is_product_youtube_video_available='N';
  $is_product_brochure_available='N'; 
  $img_flag=0;
  $youtube_video_flag=0;
  $brochure_flag=0;
  $is_discount_available='N';
  $discount_wise_colspan="colspan='2'";
  $discount_wise_colspan_cnt=0;
  if(count($prod_list))
  {
    foreach($prod_list as $p)
    {
      if($p->image!='' && $img_flag==0)
      {
        $is_product_image_available='Y';
        $img_flag=1;
      }
      if($p->youtube_video!='' && $youtube_video_flag==0)
      {
        $is_product_youtube_video_available='Y';
        $youtube_video_flag=1;
      }
      if($p->brochure!='' && $brochure_flag==0)
      {
        $is_product_brochure_available='Y';
        $brochure_flag=1;
      }
    }
  }
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
  $img_display=($is_product_image_available=='Y')?'block':'none';
  $youtube_display=($is_product_youtube_video_available=='Y')?'block':'none';
  $brochur_display=($is_product_brochure_available=='Y')?'block':'none';
  ?>
  <div style="width: 100%; clear: both;margin-top: 15px;display: inline-block;">
    <ul class="auto-ul-new" style="font-size:12px;">
       <li style="display: <?php echo $img_display; ?>" id="p_image_div">
          <label class="check-box-sec">
            <input type="checkbox" name="is_product_image_show_in_quotation" id="is_product_image_show_in_quotation" class="quotation_update"  <?php if($quotation['is_product_image_show_in_quotation']=='Y'){echo 'CHECKED';}?> >
            <span class="checkmark"></span>
          </label>
          Show Product Images
       </li>
       <li style="display: <?php echo $youtube_display; ?>" id="p_youtube_div">
          <label class="check-box-sec">
          <input type="checkbox" name="is_product_youtube_url_show_in_quotation" id="is_product_youtube_url_show_in_quotation" class="quotation_update"  <?php if($quotation['is_product_youtube_url_show_in_quotation']=='Y'){echo 'CHECKED';}?> >
          <span class="checkmark"></span>
          </label>
          Show Product Video URL
       </li>
       <li style="display: <?php echo $brochur_display; ?>" id="p_brochure_div">
          <label class="check-box-sec">
          <input type="checkbox" name="is_product_brochure_attached_in_quotation" id="is_product_brochure_attached_in_quotation" class="quotation_update"  <?php if($quotation['is_product_brochure_attached_in_quotation']=='Y'){echo 'CHECKED';}?> >          
          <span class="checkmark"></span>
          </label>
          Attach Product Brochure
       </li>
       <li>
          <label class="check-box-sec">
          <input type="checkbox" name="is_hide_total_net_amount_in_quotation" id="is_hide_total_net_amount_in_quotation" class="quotation_update" <?php if($quotation['is_hide_total_net_amount_in_quotation']=='Y'){echo 'CHECKED';}?>>
          <span class="checkmark"></span>
          </label>
          Hide Total Net Amount
       </li>
       <li>
          <label class="check-box-sec">
          <input type="checkbox" name="is_hide_gst_in_quotation" id="is_hide_gst_in_quotation" class="quotation_update" <?php if($quotation['is_hide_gst_in_quotation']=='Y'){echo 'CHECKED';}?> data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">
          <span class="checkmark"></span>
          </label>
          Hide GST
       </li>
       <li class="gst-extra hide">
          <label class="check-box-sec">
          <input type="checkbox" name="is_show_gst_extra_in_quotation" id="is_show_gst_extra_in_quotation" class="quotation_update" <?php if($quotation['is_show_gst_extra_in_quotation']=='Y'){echo 'CHECKED';}?>>
          <span class="checkmark"></span>
          </label>
          GST Extra
       </li>
       <li class="gst-consolidated">
          <label class="check-box-sec">
          <input type="checkbox" name="is_consolidated_gst_in_quotation" id="is_consolidated_gst_in_quotation" class="quotation_update" <?php if($quotation['is_consolidated_gst_in_quotation']=='Y'){echo 'CHECKED';}?> data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">
          <span class="checkmark"></span>
          </label>
          Consolidated GST
       </li>
    </ul>
  </div>

  
  
  <div>
  <div id="product_list_update_<?php echo $opportunity_id;?>" style="width: 100%; clear: both;margin-top: 15px;display: inline-block;">
     <table id="myTable_n" class="table quotation-table table-borderless">
        <thead>
           <tr>
              <th>Description<br>
                <a href="JavaScript:void(0);" class="quotation_product_rearrange auto_rearrange" data-oid="<?php echo $opportunity_id; ?>" data-qid="<?php echo $quotation_id; ?>" data-lid="<?php echo $lead_id; ?>"><i class="fa fa-arrows" aria-hidden="true"></i>Re-arrange</a>
              </th>
              <th class="text-center" width="275">Unit Price 
                <select id="currency_type_new" class="form-control color-select small-select width-60" name="currency_type_new" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">
                  <option value="">Select</option>
                  <?php foreach($currency_list as $currency_data) { ?>
                  <option value="<?php echo $currency_data->id;?>" <?php if($quotation_data['lead_opportunity_data']['currency_type_id']==$currency_data->id){ ?>selected="selected"
                  <?php } ?>>
                  <?php echo $currency_data->code;
                  ?>
                  </option>
                  <?php }  ?>
                </select>
              </th>
              <th class="text-center" width="70">Quantity</th>
              <th class="text-center" width="65">Discount<br>
                <select id="is_discount_p_or_a" class="form-control color-select small-select width-60" name="is_discount_p_or_a" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>"> 
                  <option value="P" <?php if($prod_list[0]->is_discount_p_or_a=='P'){echo'SELECTED';} ?>>%</option>
                  <option value="A" <?php if($prod_list[0]->is_discount_p_or_a=='A'){echo'SELECTED';} ?>>Amt.</option>
                </select>
              </th>
              <th class="text-center h-gst" width="60">GST (%)</th>
              <th class="text-center" width="120">Amount</th>
           </tr>
        </thead>
        <tbody id="quotation_product_sortable">          
          <?php                             
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
              // $item_price= $output->price;
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
              $row_final_amount=($item_total_amount-$row_discount_amount);
              $sub_total=$sub_total+$row_final_price;

              $total_price=$total_price+$item_total_amount;
              $total_discounted_price=$total_discounted_price+$row_discount_amount;
              $total_tax_price=$total_tax_price+$row_gst_amount;
            ?>            
            <tr id="row_<?php echo $output->id; ?>">
                <td>                             
                  <textarea class="basic-wysiwyg-editor" name="product_name" id="<?php echo 'product_name#'.$output->id;?>"  style="height: 120px;" ><?php echo $output->product_name; ?></textarea>
                  
                  <div class="row-picture">
                    <?php 

                    if($output->image){ 
                    $img_arr=explode(',',$output->image);
                    $image_for_show_arr=unserialize($output->image_for_show);
                    ?>
                    <ul>
                      <?php 
                      foreach($img_arr AS $img_tmp)
                      { 
                        $img_tmp_arr=explode("#", $img_tmp);
                        $imgid=$img_tmp_arr[0];
                        $img=$img_tmp_arr[1];
                        ?>
                        <li>
                          <label class="image-check images-over-show" data-content="<?php echo assets_url().'uploads/clients/'.$this->session->userdata['admin_session_data']['lms_url'].'/product/thumb/'.$img; ?>">
                          <input type="checkbox" id="image_for_show_<?php echo $output->id; ?>_<?php echo $imgid; ?>" class="im-check quotation_product_update" data-id="<?php echo $output->id; ?>_<?php echo $imgid; ?>" data-val="<?php echo $imgid; ?>" data-field="image_for_show" <?php if(in_array($imgid, $image_for_show_arr)){echo 'CHECKED';}?> name="image_for_show[]">
                          <img src="<?php echo assets_url().'uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/product/thumb/'.$img; ?>" class="img-fluid">
                           </label>
                        </li>
                      <?php } ?> 
                    </ul>
                    <?php } ?>
                  </div>
                  <div style="clear:both"></div>
                  
                  <div class="row-video">
                    <?php if($output->youtube_video){ ?>
                    <label class="up-check">
                      <input type="checkbox" id="is_youtube_video_url_show_<?php echo $output->id; ?>" class="doc-check quotation_product_update" data-id="<?php echo $output->id; ?>" data-val="<?php echo $output->youtube_video; ?>" data-field="is_youtube_video_url_show" <?php if($output->is_youtube_video_url_show=='Y'){echo 'CHECKED';}?> name="is_youtube_video_url_show[]">
                    </label>
                    <a href="<?php echo $output->youtube_video; ?>" class="view-up" target="_blank"><img src="<?php echo assets_url(); ?>images/video_youtube.png"></a>
                    <input type="text" name="" class="default-input text-left input-up" value="<?php echo $output->youtube_video; ?>" readonly >
                    <?php } ?>
                  </div>                  
                  
                  <div class="row-brochure">
                    <?php if($output->brochure){ ?>
                    <label class="up-check">
                      <input type="checkbox" id="is_brochure_attached_<?php echo $output->id; ?>" class="doc-check quotation_product_update" data-id="<?php echo $output->id; ?>" data-val="<?php echo $output->brochure; ?>" data-field="is_brochure_attached" <?php if($output->is_brochure_attached=='Y'){echo 'CHECKED';}?> name="is_brochure_attached[]">
                    </label>
                    <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/download_brochure/<?php echo base64_encode($output->brochure); ?>" class="view-up" target="_blank"><img src="<?php echo assets_url(); ?>images/document_download.png"></a>
                    <input type="text" name="" class="default-input text-left input-up" value="<?php echo $output->brochure; ?>" readonly>
                    <?php } ?>
                  </div>
                  
                </td>
                <td>
                  <div class="padding0 d-flex">
                    <input type="text" class="default-input calculate_quotation_price_update double_digit width-80" id="unit_price_update_<?=$i;?>" name="unit_price[]" value="<?php echo $output->price;?>" data-field="price" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
                    <span class="per-span">Per</span>
                    <input type="text" class="default-input calculate_quotation_price_update only_natural_number_noFirstZero ml-10 width-60" name="" id="" value="<?php echo $output->unit?>" data-field="unit" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" style="min-width: 50px;" />
                    
                    <select id="" class="default-select width-80 ml-10 calculate_quotation_price_update" name="" data-field="unit_type" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">
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
                  <input type="text" class="form-control calculate_quotation_price_update double_digit width-60" name="quantity[]" id="quantity_update_<?=$i;?>" value="<?php echo $output->quantity; ?>" data-field="quantity" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
                </td>
                <td>
                   <input type="text" class="form-control calculate_quotation_price_update double_digit width-60" id="disc_update_<?=$i;?>" name="disc[]" value="<?php echo $output->discount; ?>" data-field="discount" data-field="discount" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />  
                </td>
                <td class="h-gst">
                   <div class="form-group col-md-12 padding0">
                      <input type="text" class="form-control calculate_quotation_price_update double_digit width-60" id="gst_update_<?=$i;?>" name="gst[]" value="<?=ceil($output->gst);?>" data-field="gst" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
                   </div>
                </td>
                <td>
                   <div class="form-group col-md-12 padding0" align="center">
                      <input type="hidden" name="currency_type[]" value="<?=$output->currency_type;?>" id="currency_type_update_<?=$i;?>" class="form-control"/>
                      <span class="" id="total_sale_price_<?=$output->id;?>"><?php echo number_format($row_final_amount,2);?></span>
                   </div>
                   <a href="JavaScript:void(0)" class="del_quotation_product del_inv_product" data-id="<?=$output->id;?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" data-pid="<?=$output->product_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
                </td>
            </tr>
            <?php
            $s++;
            $i++;
            }
          }
          ?>
        </tbody>
        <tbody id="quotation_additional_sortable">          
          <?php if(count($selected_additional_charges))
          {
            ?>
            <?php 
            $j=0;
            foreach($selected_additional_charges AS $charge)
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
              $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

              $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
              $row_final_amount=($item_total_amount-$row_discount_amount);
              $sub_total=$sub_total+$row_final_price; 

              $total_price=$total_price+$item_total_amount;
              $total_tax_price=$total_tax_price+$row_gst_amount;
              $total_discounted_price=$total_discounted_price+$row_discount_amount;
            ?>               
            <tr id-data="additional_charge_row_<?=$i;?>" class="<?php echo ($j%2==0)?'event':'odd'; ?>" id="row_<?php echo $charge->id; ?>">              
              <td>
                <textarea class="basic-wysiwyg-editor" id="<?php echo 'additional_charge_name#'.$charge->id;?>" style="height: 300px;"><?php echo $charge->additional_charge_name; ?></textarea>
              </td>
              <td>
                <div class="padding0 d-flex">
                  <input type="text" class="width-80 default-input calculate_quotation_additional_charges_price_update double_digit" value="<?php echo $charge->price; ?>" data-field="price" data-id="<?php echo $charge->id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
                  <span class="per-span">Per</span>
                  <input type="text" class="default-input ml-10 width-60" value="-" readonly="true" />
                  <input type="text" class="default-select width-80 ml-10 " value="-" readonly="true" />
                </div>                
              </td> 

              <td>
                <div class="form-group col-md-12 padding0" >
                    <input type="text" class="form-control width-80" value="1" readonly="true" style="text-align:center" />
                </div>
              </td>                            
              <td style="vertical-align:middle">    
                <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_discount width-60" value="<?php echo $charge->discount; ?>"  data-field="discount" data-id="<?php echo $charge->id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
              </td>       

              <td class="h-gst">
                  <div class="form-group col-md-12 padding0">
                    <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_gst" value="<?php echo $charge->gst; ?>" data-field="gst" data-id="<?php echo $charge->id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
                  </div>
              </td> 

              <td align="center">
                  <div class="form-group col-md-12 padding0" align="center">     
                    <span class="" id="additional_charges_total_sale_price_<?php echo $charge->id; ?>"><?php echo number_format($row_final_amount,2); ?></span>
                  </div>
                  <a href="JavaScript:void(0)" class="del_additional_charges_update del_inv_product" data-id="<?php echo $charge->id;?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
              </td>
            </tr>            
            <?php 
            $j++; 
            } ?>
            <?php 
          } 
          ?>
          <?php /* ?>
          <tr class="net-ammount">
              <td colspan="5" style="text-align:right;" class="chan-col back_border_total bt">Total Net Amount</td>
              <td colspan="1" class="back_border_total text-center">
                <span id="total_deal_value" class="bt"><?php echo number_format($sub_total,2);?></span>
                <input type="hidden" name="deal_value" id="deal_value" value="<?=number_format($sub_total,2);?>">
              </td>
          </tr>
          <?php */ ?>          
        </tbody>
     </table>
  </div>

  <div class="row">
     <div class="col-md-6">
        <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_product_from_edit_quotation" data-oppid="<?php echo $opportunity_id;?>" data-lead="<?php echo $lead_id;?>" data-quotationid="<?php echo $quotation_id;?>" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Product </a></h4>

        <h4 class="text-primary"><a href="JavaScript:void(0);" class="add_additional_charges" data-oppid="<?php echo $opportunity_id;?>" data-quotationid="<?php echo $quotation_id;?>" style="font-size: 14px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Additional Charges </a></h4>

        <h4 class="text-primary"><a href="JavaScript:void(0);" id="add_new_row_qp" style="font-size: 14px; color: #0954de;" data-oppid="<?php echo $opportunity_id;?>" data-quotationid="<?php echo $quotation_id;?>"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add New Row</a></h4>
     </div>
     <div class="col-md-6">
         <table class="grand-summery table-borderless net-ammount" width="100%" border="0" cellpadding="0" cellspacing="0">
             <tbody>
                <tr>
                  <td width="50%"></td>
                  <td width="50%">
                    <b class="bt">Details (<span class="currency_code_div"><?php echo $quotation['currency_type']; ?></span>)</b>
                  </td>
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
                <?php //if($is_tax_show=='Y'){ ?>
                <tr class="h-gst">
                  <td>Total Tax</td>
                  <td><span id="total_tax"><?php echo number_format($total_tax_price,2); ?></span></td>
                </tr>
                <?php //} ?>
                <tr>
                  <td>Net Amount (Round Off)</td>
                  <td><span id="grand_total_round_off"><?php echo number_format(round($sub_total),2); ?></span></td>
                </tr>
                <tr>
                  <td colspan="2" style=""><span id="number_to_word_final_amount"><?php echo number_to_word(round($sub_total)); ?></span> (<span class="currency_code_div"><?php echo $quotation['currency_type']; ?></span>)</td>
                </tr> 
             </tbody>
          </table>
          <div class="gst-extra-block">** GST Extra</div>
     </div>
  </div>
  <div style="width: 100%; clear: both; display: inline-block;margin-top: 15px; padding-top: 10px; border-top: 1px solid rgb(243, 243, 243) !important;">
      <div class="blue-title-txt">Add Photos in PDF Quotation:</b> </div>
      <small>(Only .JPG & .PNG files supported. Max 4 images can be added and should not be greater than 1 mb.)</small>
      <div style="margin-top: 5px;">
         <div class="default-uploaded" id="upload_photo_in_quotation">Choose File</div>
      </div>
      <div class="add-photo-block" id="added_quotation_photo"></div>
   </div>

  <?php if(count($terms)){ ?>
  <div class="text" id="terms_condition_outer_div" style="margin-top: 15px;">
     <div style="width: 100%; clear: both; display: inline-block;margin-top: 15px;">
        <div class="blue-title-txt">Terms & Conditions:</b> ( <a href="JavaScript:void(0);" id="all_check_terms_show_in_letter">Select All</a> / <a href="JavaScript:void(0);" id="all_uncheck_terms_show_in_letter">Remove All</a> )</div>
        <div class="fontsize13" style="margin-top: 0px; font-size: 13px;">
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
                          <textarea class="border-solid text-input terms_update" data-id="<?php echo $term['id']; ?>" style="width: 100%;  font-size: 13px; color: #000; padding: 4px;border: 1px solid #c9d4ea;"><?php echo $term['value']; ?></textarea>
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
        </div> 
     </div>
  </div>
  <?php } ?>
  <div>&nbsp;</div>
  <div class="text">
    <div style="width: 100%; clear: both">    
      <div class="blue-title-txt"><!-- Other Terms & Conditions -->Additional Notes:</div>
      <div class="fontsize13" style="margin-top: 0px; font-size: 13px;">
        <textarea class="border-solid text-input letter_update" style="width: 100%; font-size: 13px; color: #000; border: 1px solid #c9d4ea; height: 50px;" name="letter_terms_and_conditions" id="letter_terms_and_conditions" rows="1"><?php echo $quotation['letter_terms_and_conditions']; ?></textarea>
      </div>                      
    </div>                       
  </div>
  <?php if($company['quotation_bank_details1']!='' || $company['quotation_bank_details2']!=''){ ?>
  <div class="row">
      <div class="col-md-6">
        <div class="text">
            <div style="width: 100%; clear: both">  
                
              <div class="blue-title-txt">
                <label class="check-box-sec">
                  <input type="checkbox" name="is_quotation_bank_details1_send" id="is_quotation_bank_details1_send" class="letter_update"  <?php if($quotation['is_quotation_bank_details1_send']=='Y'){echo 'CHECKED';}?> >
                  <span class="checkmark"></span>
                </label>
                Banker’s Detail 1:
              </div>
              <div class="fontsize13" style="margin-top: 0px; font-size: 13px;">
                <textarea class="basic-wysiwyg-editor" name="quotation_bank_details1" id="quotation_bank_details1" rows="6"><?php echo $company['quotation_bank_details1']; ?></textarea>                                
              </div>
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
            <?php /* ?>
            <p><?php //echo $quotation['letter_banker_details']; ?></p>
            <p><textarea class="basic-wysiwyg-editor" name="letter_banker_details" id="letter_banker_details" rows="50"><?php echo $quotation['letter_banker_details']; ?></textarea></p>
            <?php */ ?>
        </div>
      </div>
      <?php if($company['quotation_bank_details2']){ ?>
      <div class="col-md-6">
          <div class="blue-title-txt">
              <label class="check-box-sec">
                <input type="checkbox" name="is_quotation_bank_details2_send" id="is_quotation_bank_details2_send" class="letter_update"  <?php if($quotation['is_quotation_bank_details2_send']=='Y'){echo 'CHECKED';}?> >
                <span class="checkmark"></span>
              </label>
              Banker’s Detail 2:
          </div>
          <div class="fontsize13" style="margin-top: 0px; font-size: 13px;"><textarea class="basic-wysiwyg-editor" name="quotation_bank_details2" id="quotation_bank_details2" rows="6"><?php echo $company['quotation_bank_details2']; ?></textarea></div>
          <?php /* if(strtoupper($quotation['currency_type'])!='INR'){ ?>
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
          <div class="blue-title-txt">
              <label class="check-box-sec">
                <input type="checkbox" name="is_gst_number_show_in_quotation" id="is_gst_number_show_in_quotation" class="letter_update"  <?php if($quotation['is_gst_number_show_in_quotation']=='Y'){echo 'CHECKED';}?> >
                <span class="checkmark"></span>
              </label>
              GST: <?php echo $company['gst_number']; ?>
          </div>              
      </div>                      
  </div>
  <?php } ?>
  <div>&nbsp;</div>

  <div style="margin-top:8px;">
    <div style="width: 100%; clear: both">    
      <div class="blue-title-txt">Write Quotation facilitation Text (Letter Footer):</div>
      <div class="fontsize13 max-50" style="margin-top: 0px; font-size: 13px;">
        <textarea class=" basic-wysiwyg-editor" name="letter_footer_text" id="letter_footer_text"><?php echo $quotation['letter_footer_text']; ?></textarea>
      </div>                      
    </div>                      
  </div>
  <div style="clear:both;">&nbsp;</div>
  <?php
  $is_show_digital_signature_checked='N';
  if($curr_company['digital_signature'])
  {
    $is_show_digital_signature_checked='Y'; 
  }
  ?>
  <div class="<?php if($is_show_digital_signature_checked=='Y'){ echo'form-group';} ?>">
    <div class="row" <?php if($is_show_digital_signature_checked=='N'){ echo'style="display:none;"';} ?>>
       <div class="col-md-12">
          <label class="check-box-sec fl">
          <input class="styled-checkbox" type="checkbox" value="Y" name="quote_is_digital_signature_checked" id="quote_is_digital_signature_checked" <?php echo ($quotation['is_digital_signature_checked']=='Y')?'checked':'';?>>
          <span class="checkmark"></span>
          </label>&nbsp;&nbsp;<b class="blue-title-txt">Digital Signature</b>
       </div>
    </div>
  </div>
  <?php
  if($quotation['is_digital_signature_checked']=='Y')
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
          <div <?php if($show_signature=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> class="name_of_authorised_signature_div"><img src="<?php echo assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$curr_company['digital_signature']; ?>" height="50"></div>
          <label class="blue-title-txt" id="digital_signature_title"><?php echo ($quotation['is_digital_signature_checked']=='Y')?'Name of authorized signatory':'Thanks & Regards';?>:</label>
          <span <?php if($show_signature=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> id="name_of_authorised_signature_div" class="name_of_authorised_signature_div">
          <textarea class="basic-wysiwyg-editor " name="name_of_authorised_signature" id="name_of_authorised_signature" ><?php echo ($quotation['name_of_authorised_signature'])?$quotation['name_of_authorised_signature']:$curr_company['authorized_signatory']; ?></textarea></span>
          <span <?php if($show_thanks_and_regards=='Y'){echo'style="display:block"';}else{echo'style="display:none"';} ?> id="thanks_and_regards_div">
          <textarea class="basic-wysiwyg-editor" name="letter_thanks_and_regards" id="letter_thanks_and_regards" rows="6"><?php echo $quotation['letter_thanks_and_regards']; ?></textarea></span>
       </div>
    </div>
  </div>

  <?php /* ?>
  <div class="row">
    <div class="col-md-6">
      <div style="width: 100%; clear: both">    
        <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Thanks & Regards:</b></h4>
        <div class="fontsize13" style="margin-top: 0px; font-size: 13px;">
          <textarea class="basic-wysiwyg-editor" name="letter_thanks_and_regards" id="letter_thanks_and_regards" rows="6"><?php echo $quotation['letter_thanks_and_regards']; ?></textarea>
        </div>                      
      </div> 
    </div>
  </div>  
  <?php */ ?>


  <div class="row" style="margin-top: 15px;">
    <?php /* ?>
      <?php $img_display=($is_product_image_available=='Y')?'block':'none';?>
      <div class="col-md-4 ff" style="display: <?php echo $img_display; ?>" id="p_image_div">
        <label class="check-box-sec" style="display: block;float: left; margin-right: 6px;">
        <input type="checkbox" name="is_product_image_show_in_quotation--" id="is_product_image_show_in_quotation--" class="letter_update"  <?php if($quotation['is_product_image_show_in_quotation']=='Y'){echo 'CHECKED';}?> >
        <span class="checkmark"></span>
        </label>
        Show product images
      </div>       
      
      <?php $brochur_display=($is_product_brochure_available=='Y')?'block':'none';?>      
      <div class="col-md-4 ff" style="display: <?php echo $brochur_display; ?>" id="p_brochure_div">
        <label class="check-box-sec" style="float: left; margin-right: 6px;display: block;" >
        <input type="checkbox" name="is_product_brochure_attached_in_quotation--" id="is_product_brochure_attached_in_quotation--" class="letter_update"  <?php if($quotation['is_product_brochure_attached_in_quotation']=='Y'){echo 'CHECKED';}?> >
        <span class="checkmark"></span>
        </label>
        Attached product brochure
      </div>
    <?php */ ?>
      <?php if($curr_company['brochure_file']){ ?>
      <div class="col-md-4 ff">
        <label class="check-box-sec" style="display: block;float: left; margin-right: 6px;">
        <input type="checkbox" name="is_company_brochure_attached_in_quotation" id="is_company_brochure_attached_in_quotation" class="letter_update"  <?php if($quotation['is_company_brochure_attached_in_quotation']=='Y'){echo 'CHECKED';}?> >
        <span class="checkmark"></span>
        </label>
        Attached company brochure
      </div>
      <?php } ?>
  </div>
  <div class="page_three new" style="width: 100%;height: auto;display: inline-block;margin: 15px 0;">
     <!-- <p>Page 3</p>   -->
     <a href="JavaScript:void(0);" id="quotation_save_and_close">Save & Close</a>
     <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$opportunity_id.'/'.$quotation_id);?>" target="_blank">Preview</a>
     <?php //if(is_attribute_available('email_service')==TRUE){ ?>
        <?php if($customer['email']!='' || $customer['alt_email']!=''){ ?>
          <?php if($email_forwarding_setting['is_mail_send']=='Y'){ ?>
            <?php if($email_forwarding_setting['is_send_mail_to_client']=='Y' || $email_forwarding_setting['is_send_relationship_manager']=='Y' || $email_forwarding_setting['is_send_manager']=='Y' || $email_forwarding_setting['is_send_skip_manager']=='Y'){ ?>
            <a href="javascript:" id="qutation_send_to_buyer" data-opportunityid="<?php echo $opportunity_id; ?>" data-quotationid="<?php echo $quotation_id; ?>">Send Via Mail</a>
            <?php } ?>
          <?php } ?>
        <?php }else{ ?>
          <a href="javascript:" onclick="get_alert('Oops! Customer mail not set.')">Send Quotation</a>
        <?php } ?>
      <?php /*}else{ ?>
        <a href="javascript:alert('Sorry!You have restricted to get the functionality.');" id="" style="text-decoration: line-through;">Send Quotation</a>
          <?php }*/ ?>

        <!-- <pre><?php //print_r($lead_data->cus_mobile); ?></pre> -->
        <?php if((($quotation_data['quotation']['is_extermal_quote']=='Y' && $quotation_data['quotation']['file_name']!='') || $quotation_data['quotation']['is_extermal_quote']=='N') && $lead_data->cus_mobile!=''){ ?>
        <a href="JavaScript:void(0);"  class="quotation_sent_by_whatsapp pdf_download" data-lid="<?php echo $lead_id; ?>" data-oppid="<?php echo $opportunity_id; ?>" data-qid="<?php echo $quotation_id; ?>" data-is_quoted="Y">Send on WhatsApp</a>
        <?php } ?>
        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_id.'/'.$quotation_id);?>" target="_blank" class="pdf_download">Download</a>
        <a href="JavaScript:void(0);" style="background-color:red" data-oppid="<?php echo $opportunity_id; ?>" data-qid="<?php echo $quotation_id; ?>" id="delete_quotation">Cancel</a>
  </div>
</div>
</div>
<input type="hidden" id="opportunity_id" name="opportunity_id" value="<?php echo $opportunity_id;?>" />
<input type="hidden" id="lead_id_update" name="lead_id_update" value="<?php echo $lead_id;?>" />
<input type="hidden" name="selected_prod_id" id="selected_prod_id_update_<?php echo $opportunity_id;?>" vale="<?=$temp_prod_id;?>" />
<input type="hidden" id="quotation_id" value="<?php echo $quotation['id']; ?>" >
<input type="hidden" id="currency_type_id" value="<?php echo $quotation_data['lead_opportunity_data']['currency_type_id']; ?>" >
<input type="hidden" id="quotation_id" value="">
<!-- <script src="<?php echo assets_url();?>js/custom/quotation/generate_view.js?v=<?php echo rand(0,1000); ?>"></script> -->
<script type="text/javascript">
  $('.display_date').datepicker({
      dateFormat: "dd-M-yy",
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+5'
    });

  $( "#quote_valid_until" ).datepicker({
      dateFormat: "dd-M-yy",
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+5'
    });
 $(document).ready(function(){
  

  //  =========================================================================
  // SORTABLE
  
  <?php /* ?>
  $("#rander_quotation_product_rearrange_view_modal").on('hide.bs.modal', function(){
        $('#selected_prod_id').val('');        
        var opportunity_id=($("#opportunity_id").val())?$("#opportunity_id").val():'';
        var quotation_id=($("#quotation_id").val())?$("#quotation_id").val():'';        
        if(quotation_id!='' && opportunity_id!='')
        {
            edit_qutation_view_modal(opportunity_id,quotation_id);
        }
  });
  $("body").on("click",".quotation_product_rearrange",function(e){
    var base_url=$("#base_url").val();
    var opportunity_id=$("#opportunity_id").val();
    var quotation_id=$("#quotation_id").val(); 
    var lead_id=$("#lead_id_update").val();    
    var data="opportunity_id="+opportunity_id+"&quotation_id="+quotation_id+"&lead_id="+lead_id;     
    $.ajax({
        url: base_url + "opportunity/rander_quotation_product_list_for_rearrange",
        type: "POST",
        data: {
            'quotation_id': quotation_id,
            'opportunity_id': opportunity_id,
            'lead_id':lead_id
        },
        async: false,
        beforeSend: function( xhr ) { 
            $('#myTable_n').addClass('logo-loader');
        },
        complete: function(){
            $('#myTable_n').removeClass('logo-loader');
        },
        success: function(data) {
            result = $.parseJSON(data);            
            $("#rander_quotation_product_rearrange_html").html(result.html);
            $('#rander_quotation_product_rearrange_view_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        error: function() {}
    }); 
  });
  $( "#quotation_product_sortable---" ).sortable({
    containment: "#myTable_n",
    // axis: 'y',
    start: function(event, ui) {
        // var start_pos = ui.item.index();
        // ui.item.data('start_pos', start_pos);
        // alert('start')
    },
    stop: function(event, ui) {        
        $('#myTable_n').addClass('logo-loader');
        var opportunity_id=$("#opportunity_id").val();
        var quotation_id=$("#quotation_id").val();            
        var new_sort = $("#quotation_product_sortable").sortable("serialize", {key:'new_sort[]'});
        var base_url=$("#base_url").val();
        var data=new_sort; 
        $.ajax({
                url: base_url+"opportunity/resort_quotation_product",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",  
                async: true,                 
                beforeSend: function( xhr ) { 
                  
                },
                complete: function(){
                  $('#myTable_n').removeClass('logo-loader');
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      quotation_product_load(opportunity_id,quotation_id);
                    }
                },
        }); 
    },
    change: function(event, ui) {
        // var start_pos = ui.item.data('start_pos');
        // var index = ui.placeholder.index();
        // alert('change')
        // if (start_pos < index) {
        //     $('#quotation_product_sortable tr:nth-child(' + index + ')').addClass('highlights');
        // } else {
        //     $('#quotation_product_sortable tr:eq(' + (index + 1) + ')').addClass('highlights');
        // }
    },
    update: function (event, ui) {}
  });
  $( "#quotation_additional_sortable__" ).sortable({
    containment: "#myTable_n", //(parent div)
    // axis: 'y',
    start: function(event, ui) {
        // var start_pos = ui.item.index();
        // ui.item.data('start_pos', start_pos);
        // alert('start')
    },
    stop: function(event, ui) {
        // $(this).children().removeClass( 'highlight' );
        // alert('stop')
        $('#myTable_n').addClass('logo-loader');
        var opportunity_id=$("#opportunity_id").val();
        var quotation_id=$("#quotation_id").val();            
        var new_sort = $("#quotation_additional_sortable").sortable("serialize", {key:'new_sort[]'});
        var base_url=$("#base_url").val();
        var data=new_sort; //alert(data); //return false;
        $.ajax({
                url: base_url+"opportunity/resort_quotation_additional_charges",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",    
                async: true,                     
                beforeSend: function( xhr ) { 
                  // $('#myTable_n').addClass('logo-loader');
                },
                complete: function(){
                  $('#myTable_n').removeClass('logo-loader');
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      // alert(opportunity_id+'/'+quotation_id)
                      quotation_product_load(opportunity_id,quotation_id);
                    }
                },
        });
    },
    change: function(event, ui) {
        // var start_pos = ui.item.data('start_pos');
        // var index = ui.placeholder.index();
        // alert('change')
        // if (start_pos < index) {
        //     $('#quotation_product_sortable tr:nth-child(' + index + ')').addClass('highlights');
        // } else {
        //     $('#quotation_product_sortable tr:eq(' + (index + 1) + ')').addClass('highlights');
        // }
    },
    update: function (event, ui) {      
                      
    }
  });
  <?php */ ?>
  // SORTABLE
  // ===========================================================================
    $("#quotation_id").val(<?php echo $quotation_id;?>);

    if ($("input[type='checkbox'][name='is_product_image_show_in_quotation']:checked").length != 0) {
      $(".row-picture").toggleClass("show");
    }
    $('#is_product_image_show_in_quotation').change(function() {
               
        if($("input[type='checkbox'][name='is_product_image_show_in_quotation']").is(':checked'))
        {
         
          $.each($("input[name='image_for_show[]']"), function(e){  
            $(this).attr("checked",true);          
            var quotation_pid=$(this).attr("data-id");
            var updated_field_name=$(this).attr("data-field");
            var updated_content=$(this).attr("data-val");           
            fn_update_quotation_product(quotation_pid,updated_field_name,updated_content);            
          });           
        }       
        
        $(".row-picture").toggleClass("show");
    });
    if ($("input[type='checkbox'][name='is_product_youtube_url_show_in_quotation']:checked").length != 0) {
      $(".row-video").toggleClass("show"); 
    }
    $('#is_product_youtube_url_show_in_quotation').change(function() {

      if($("input[type='checkbox'][name='is_product_youtube_url_show_in_quotation']").is(':checked'))
      {
        $.each($("input[name='is_youtube_video_url_show[]']"), function(){
          $(this).attr("checked",true);
          var quotation_pid=$(this).attr("data-id");
          var updated_field_name=$(this).attr("data-field");
          var updated_content=$(this).attr("data-val");           
          fn_update_quotation_product(quotation_pid,updated_field_name,updated_content); 
        }); 
      }
      $(".row-video").toggleClass("show");        
    });
    if ($("input[type='checkbox'][name='is_product_brochure_attached_in_quotation']:checked").length != 0) {
      $(".row-brochure").toggleClass("show");
    }
    $('#is_product_brochure_attached_in_quotation').change(function() {
      if($("input[type='checkbox'][name='is_product_brochure_attached_in_quotation']").is(':checked'))
        {
          $.each($("input[name='is_brochure_attached[]']"), function(){
            $(this).attr("checked",true);
            var quotation_pid=$(this).attr("data-id");
            var updated_field_name=$(this).attr("data-field");
            var updated_content=$(this).attr("data-val");           
            fn_update_quotation_product(quotation_pid,updated_field_name,updated_content); 
          }); 
        }
      $(".row-brochure").toggleClass("show");
    });

    if ($("input[type='checkbox'][name='is_hide_total_net_amount_in_quotation']:checked").length != 0) {
      $(".net-ammount").toggleClass("hide"); 
    }
    $('#is_hide_total_net_amount_in_quotation').change(function() {
      $(".net-ammount").toggleClass("hide");        
    });
    //h-gst
    if ($("input[type='checkbox'][name='is_hide_gst_in_quotation']:checked").length != 0) {
      $(".h-gst").toggleClass("hide"); 
      $('.gst-extra').toggleClass("hide");
      //chan-col 
      if ($(this).is(':checked')) {
        $(".chan-col").attr("colspan", 4);
      } else {
        $(".chan-col").attr("colspan", 5);
      } 
    }
    $('#is_hide_gst_in_quotation').change(function() {
        /*
        if($("input[type='checkbox'][name='is_hide_gst_in_quotation']:checked").length==0)
        {          
          $(".gst-extra-block").removeClass("show");
          $("#is_show_gst_extra_in_quotation").attr("checked",false);
        }
        else
        {
          var c_t = $("#currency_type_new").val();
          var opportunity_id = $(this).attr('data-opportunityid');
          var quotation_id = $(this).attr('data-quotationid');
          fn_quotation_gst_update_to_zero(c_t,opportunity_id,quotation_id);
        }
        */

        $(".h-gst").toggleClass("hide"); 
        $('.gst-extra').toggleClass("hide");
        //chan-col 
        if ($(this).is(':checked')) {          
          $(".chan-col").attr("colspan", 4);
          var opportunity_id = $(this).attr('data-opportunityid');
          var quotation_id = $(this).attr('data-quotationid');
          fn_quotation_hide_gst_zero(opportunity_id,quotation_id);
          $("#is_consolidated_gst_in_quotation").attr("checked",false);
          $("#is_consolidated_gst_in_quotation").trigger("change");
          $("#is_consolidated_gst_in_quotation").attr("disabled",true);
          $(".gst-consolidated").addClass('hide');
        } else {          
          $(".chan-col").attr("colspan", 5);
          $(".gst-extra-block").removeClass("show");
          $("#is_show_gst_extra_in_quotation").attr("checked",false);
          $("#is_consolidated_gst_in_quotation").attr("disabled",false);
          $(".gst-consolidated").removeClass('hide');
        }
    });
    //gst extra
    if ($("input[type='checkbox'][name='is_show_gst_extra_in_quotation']:checked").length != 0) {
      $(".gst-extra-block").toggleClass("show");
    }
    $('#is_show_gst_extra_in_quotation').change(function() {
      $(".gst-extra-block").toggleClass("show");
    });
    //default-uploaded
    $(document).on("click","#upload_photo_in_quotation",function(event) {
       event.preventDefault();  
       var quotation_id=$("#quotation_id").val();
       $('#frmUploadPhotoToQuotation').append('<input type="hidden" id="quotation_id" name="quotation_id" value="'+quotation_id+'" />');     
       $('#automated_quotation_popup_modal').css({'display':'none'});
       $('#select_quotation_photo_modal').modal('show');
    });
    $('#select_quotation_photo_modal').on('hide.bs.modal', function (e) {
       $('#automated_quotation_popup_modal').css({'display':'block'});
       // setTimeout(function(){ 
       //    $('#automated_quotation_popup_modal').modal('show'); 
       // }, 700);
    });
    $('#select_quotation_photo_modal').on('hidden.bs.modal', function (e) {
       $('body').addClass('modal-open');       
    }); 

    $("body").on("click",".pdf_download",function(e){
        $("#delete_quotation").remove();
    });  
  });

  $('.only_natural_number_noFirstZero').keyup(function(e)
  { 
      var val = $(this).val()
      var reg = /^0/gi;
      if (val.match(reg)) {
          $(this).val(val.replace(reg, ""));
          // alert("Please phone number first character bla blaa not 0!");
          // $(this).mask("999 999-9999");
      }
      else
      {
          if (/\D/g.test(this.value))
          {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
          }
      }          
  });
  $(".double_digit").keydown(function(e) {
        debugger;
        var charCode = e.keyCode; 
        if (charCode != 8 && charCode != 37 && charCode != 39 && charCode != 46 && charCode != 9) {
            //alert($(this).val());
            if (!$.isNumeric($(this).val()+e.key)) {
                return false;
            }
        }
    return true;
  });
</script>