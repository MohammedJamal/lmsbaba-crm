<div class="payment-loop fix-input" id="div_<?php echo $divid; ?>">
<ul>
   <li>
    <label class="blue-title">Payment Mode <span class="red">*</span></label>
    <div class="full-width">
       <select class="default-select" name="p_payment_mode_id[]" id="">
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
    <div class="full-width"><input type="text" class="date-input input_date" name="p_payment_date[]" id="" placeholder="MM-DD-YYYY"></div>
   </li>
   <li>
     <label class="blue-title">Currency Type <span class="red">*</span></label>
     <div class="full-width">
        <select class="default-select" name="p_currency_type[]" id="">
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
       <input type="text" class="default-input" name="p_amount[]" id="" placeholder="ex: 23">
    </div>
    
   </li>
   <li>
    <label class="blue-title">Narration</label>
    <div class="full-width"><input type="text" class="default-input" name="p_narration[]" id=""></div>
    <span class="payment_span_del"><a href="JavaScript:void(0)" class="payment_div_del" data-divid="<?php echo $divid; ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></a></span>
   </li>
</ul>
</div>