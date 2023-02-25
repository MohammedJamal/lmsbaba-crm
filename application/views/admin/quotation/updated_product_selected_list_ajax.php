<table id="myTable_n" class="table quotation-table table-borderless">
  <thead>
     <tr>
        <th>Description<br>
          <a href="JavaScript:void(0);" class="quotation_product_rearrange auto_rearrange" data-oid="<?php echo $opportunity_id; ?>" data-qid="<?php echo $quotation_id; ?>" data-lid="<?php echo $lead_id; ?>"><i class="fa fa-arrows" aria-hidden="true"></i>Re-arrange</a>
        </th>
        <th class="text-center" width="275">Unit Price 
          <select id="currency_type_new" class="form-control color-select small-select width-60 " name="currency_type_new" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">
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
        <th class="text-center" width="65">Discount <br>
          <select id="is_discount_p_or_a" class="form-control color-select small-select width-60" name="is_discount_p_or_a" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>">  <option value="P" <?php if($prod_list[0]->is_discount_p_or_a=='P'){echo'SELECTED';} ?>>%</option>
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
        $item_discount_per=$output->discount; 
        $item_unit=$output->unit;
        $item_price=($output->price/$item_unit);
        $item_qty=$output->quantity;

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
        $total_discounted_price=$total_discounted_price+$row_discount_amount;
        $total_tax_price=$total_tax_price+$row_gst_amount;
      ?>
      <tr id="row_<?php echo $output->id; ?>">
          <td>
            <textarea class="basic-wysiwyg-editor" name="product_name" id="<?php echo 'product_name#'.$output->id;?>"  style="height: 300px;" ><?php echo $output->product_name; ?></textarea>
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
                    <input type="checkbox" id="image_for_show_<?php echo $output->id; ?>_<?php echo $imgid; ?>" class="im-check quotation_product_update" data-id="<?php echo $output->id; ?>_<?php echo $imgid; ?>" data-val="<?php echo $imgid; ?>" data-field="image_for_show" <?php if(in_array($imgid, $image_for_show_arr)){echo 'CHECKED';}?>>
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
                <input type="checkbox" id="is_youtube_video_url_show_<?php echo $output->id; ?>" class="doc-check quotation_product_update" data-id="<?php echo $output->id; ?>" data-val="<?php echo $output->youtube_video; ?>" data-field="is_youtube_video_url_show" <?php if($output->is_youtube_video_url_show=='Y'){echo 'CHECKED';}?>>
              </label>
              <a href="<?php echo $output->youtube_video; ?>" class="view-up" target="_blank"><img src="<?php echo assets_url(); ?>images/video_youtube.png"></a>
              <input type="text" name="" class="default-input text-left input-up" value="<?php echo $output->youtube_video; ?>" readonly >
              <?php } ?>
            </div>                  
                  
            <div class="row-brochure">
              <?php if($output->brochure){ ?>
              <label class="up-check">
                <input type="checkbox" id="is_brochure_attached_<?php echo $output->id; ?>" class="doc-check quotation_product_update" data-id="<?php echo $output->id; ?>" data-val="<?php echo $output->brochure; ?>" data-field="is_brochure_attached" <?php if($output->is_brochure_attached=='Y'){echo 'CHECKED';}?>>
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
            <input type="text" class="form-control calculate_quotation_price_update double_digit width-60" name="quantity[]" id="quantity_update_<?=$i;?>" value="<?php echo $output->quantity?>" data-field="quantity" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>"/>
          </td>
          <td>
             <input type="text" class="form-control calculate_quotation_price_update double_digit width-60" id="disc_update_<?=$i;?>" name="disc[]" value="<?php echo $output->discount; ?>" data-field="discount" data-field="discount" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>"  data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />  
          </td>
          <td class="h-gst">
             <div class="form-group col-md-12 padding0">
                <input type="text" class="form-control calculate_quotation_price_update double_digit" id="gst_update_<?=$i;?>" name="gst[]" value="<?=ceil($output->gst);?>" data-field="gst" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
             </div>
          </td>
          <td>
             <div class="form-group col-md-12 padding0" align="center">
                <input type="hidden" name="currency_type[]" value="<?=$output->currency_type;?>" id="currency_type_update_<?=$i;?>" class="form-control"/>
                <span class="" id="total_sale_price_<?=$output->id;?>"><?=number_format($row_final_amount,2);?></span>
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
        $total_discounted_price=$total_discounted_price+$row_discount_amount;
      ?>    
         
      <tr data-id="additional_charge_row_<?=$i;?>" class="<?php echo ($j%2==0)?'event':'odd'; ?>" id="row_<?php echo $charge->id; ?>">
        <td style="width: 249px;">
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
          
          <div class="form-group col-md-12 padding0">
              <input type="text" class="form-control width-80" value="1" readonly="true" style="text-align:center" />
          </div>
        </td>                            
        <td>    
          <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_discount width-60" value="<?php echo $charge->discount; ?>"  data-field="discount" data-id="<?php echo $charge->id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
        </td>       

        <td class="h-gst">
            <div class="form-group col-md-12 padding0">
          <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_gst" value="<?php echo $charge->gst; ?>" data-field="gst" data-id="<?php echo $charge->id; ?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>" />
            </div>
        </td> 

        <td align="center">
            <div class="form-group col-md-12 padding0">       
          <span class="" id="additional_charges_total_sale_price_<?php echo $charge->id; ?>"><?php echo number_format($row_final_amount,2); ?></span>
            </div>
            <a href="JavaScript:void(0)" class="del_additional_charges_update del_inv_product" data-id="<?php echo $charge->id;?>" data-quotationid="<?php echo $quotation_id;?>" data-opportunityid="<?php echo $opportunity_id;?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
        </td>
      </tr>      
      <?php $j++; } ?>
      <?php 
    } ?>
    <?php /* ?>
    <tr>
        <td colspan="5" style="text-align:right;" class="chan-col back_border_total bt">Total Net Amount</td>
        <td colspan="1" class="back_border_total">
          <span id="total_deal_value" class="bt"><?php echo number_format($sub_total,2);?></span>
          <input type="hidden" name="deal_value" id="deal_value" value="<?=number_format($sub_total,2);?>">
        </td>
    </tr>
    <?php */ ?>
  </tbody>
</table>
<script type="text/javascript">
    if ($("input[type='checkbox'][name='is_hide_gst_in_quotation']:checked").length != 0) {
        $(".chan-col").attr("colspan", 5);
        $(".h-gst").addClass("hide"); 
        $('.gst-extra').toggleClass("show"); 
        $(".gst-extra-block").removeClass("show");             
    }
    if ($("input[type='checkbox'][name='is_show_gst_extra_in_quotation']:checked").length != 0) {
      $(".gst-extra-block").toggleClass("show");
    }
    // =====================================
    // -------------------------------------
    if ($("input[type='checkbox'][name='is_product_image_show_in_quotation']:checked").length != 0) {
      $(".row-picture").toggleClass("show");
    }
    if ($("input[type='checkbox'][name='is_product_youtube_url_show_in_quotation']:checked").length != 0) {
      $(".row-video").toggleClass("show"); 
    }
    if ($("input[type='checkbox'][name='is_product_brochure_attached_in_quotation']:checked").length != 0) {
      $(".row-brochure").toggleClass("show");
    }
    // -------------------------------------
    // =====================================
  $(document).ready(function(){

    
    //  =========================================================================
    // SORTABLE
    $( "#quotation_product_sortable__" ).sortable({
      containment: "#myTable_n",
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
          var new_sort = $("#quotation_product_sortable").sortable("serialize", {key:'new_sort[]'});
          var base_url=$("#base_url").val();
          var data=new_sort; //alert(data)
          $.ajax({
                  url: base_url+"opportunity/resort_quotation_product",
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
    // SORTABLE
    // ===========================================================================

    //var currency_type=$("#currency_type_update_<?php echo $opportunity_id; ?>").val();
    var currency_type=$("#currency_type_new option:selected").val();
    // alert(currency_type)
    if(currency_type!=1)
    {
      $("[name='gst[]']").attr("readonly",true);
      $(".additional_charges_gst").attr("readonly",true);
    }
    else
    {
      $("[name='gst[]']").attr("readonly",false);
      $(".additional_charges_gst").attr("readonly",false);
    }
    // ===============================================
    // VALIDATION SCRIPT
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
        // Namutal number and first letter not zero
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
        // Namutal number and first letter not zero
    // VALIDATION SCRIPT
    // ===============================================
  });
</script>