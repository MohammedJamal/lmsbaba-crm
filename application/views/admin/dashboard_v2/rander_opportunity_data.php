<div class="opportunity-outer">
   
   <div class="opportunity-item">
      <div class="opportunity-block">
         <h1>Open Opportunity</h1>
         <div class="opportunity-val"><span><?php echo ($currency_info['default_currency_code']=='INR')?'₹':$currency_info['default_currency_code']; ?> <?php echo number_format($data['open_opportunity_value']);?></span></div>
         <div class="opportunity-process-holder">
            <div class="opportunity-process-txt">
               <span><?php echo $data['open_opportunity_percentage'];?>%</span>
               <span>Open opportunity leads /  Total open leads %</span>
            </div>
            <div class="opportunity-process-bar-holder">
               <div class="opportunity-process-bar-outer">
                  <div class="opportunity-process-bar" data-content="<?php echo $data['open_opportunity_percentage'];?>"></div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="opportunity-item">
      <div class="opportunity-block">
         <h1>Opportunity to Business Conversion</h1>
         <div class="opportunity-val"><span> <?php echo ($currency_info['default_currency_code']=='INR')?'₹':$currency_info['default_currency_code']; ?> <?php echo number_format($data['business_conversion_value']);?></span></div>
         <div class="opportunity-process-holder">
            <div class="opportunity-process-txt">
               <span><?php echo $data['business_conversion_percentage'];?>%</span>
               <span>Deal won / Total leads %</span>
            </div>
            <div class="opportunity-process-bar-holder">
               <div class="opportunity-process-bar-outer">
                  <div class="opportunity-process-bar" data-content="<?php echo $data['business_conversion_percentage'];?>"></div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="opportunity-item">
      <div class="opportunity-block">
         <h1>Opportunity Lost</h1>
         <div class="opportunity-val"><span><?php echo ($currency_info['default_currency_code']=='INR')?'₹':$currency_info['default_currency_code']; ?> <?php echo number_format($data['opportunity_lost_value']);?></span></div>
         <div class="opportunity-process-holder">
            <div class="opportunity-process-txt">
               <span><?php echo $data['opportunity_lost_percentage'];?>%</span>
               <span> Deal lost /Total leads %</span>
            </div>
            <div class="opportunity-process-bar-holder">
               <div class="opportunity-process-bar-outer">
                  <div class="opportunity-process-bar" data-content="<?php echo $data['opportunity_lost_percentage'];?>"></div>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>
                           