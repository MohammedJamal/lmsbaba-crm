<?php 
//echo'<pre>';
//print_r($data);
$leads_data = $data['leads_data'];
$revenue_data = $data['revenue_data'];
$sales_data = $data['sales_data'];
$customer_data = $data['customer_data'];
//echo'</pre>'; 
?>

<ul class="full-border-li">
   <li>
      <div class="row-c align-items-center">
         <div class="m-pic">
            <img src="<?php echo assets_url() ?>images/month-1.png" class="full-img">
         </div>
         <div class="m-title">
            <h2 class="color-1">New Leads</h2>
            <a class="rander_detail_popup"  data-report="this_month_report" data-filter1="new_lead" data-filter2=""><?php echo $leads_data['new_leads'];?></a>
         </div>
         <div class="m-info">
         <?php if($leads_data['newlead_growth_type']=='P'){ ?>
            <div class="plus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($leads_data['since_new_leads'],2);?>%
            </div>
         <?php } elseif($leads_data['newlead_growth_type']=='L'){ ?>
            <div class="minus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($leads_data['since_new_leads'],2);?>%
            </div>
         <?php } ?>
            Since last months
         </div>
      </div>
   </li>

   <li>
      <div class="row-c align-items-center">
         <div class="m-pic">
            <img src="<?php echo assets_url() ?>images/month-2.png" class="full-img">
         </div>
         <div class="m-title">
            <h2 class="color-2">Revenue</h2>
            <a class="rander_detail_popup" data-report="this_month_report" data-filter1="sales_order" data-filter2="">₹ <?php echo number_format($revenue_data['revenue']);?></a>
         </div>
         <div class="m-info">
         <?php if($revenue_data['revenue_growth_type']=='P'){ ?>
            <div class="plus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($revenue_data['since_revenue'],2);?>%
            </div>
         <?php } elseif($revenue_data['revenue_growth_type']=='L'){ ?>
            <div class="minus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($revenue_data['since_revenue'],2);?>%
            </div>
         <?php } ?>
            Since last months
         </div>
      </div>
   </li>

   <li>
      <div class="row-c align-items-center">
         <div class="m-pic">
            <img src="<?php echo assets_url() ?>images/month-3.png" class="full-img">
         </div>
         <div class="m-title">
            <h2 class="color-3">Sales Orders</h2>
            <a class="rander_detail_popup"  data-report="this_month_report" data-filter1="sales_order" data-filter2=""><?php echo $sales_data['sales_order'];?></a>
         </div>
         <div class="m-info">
         <?php if($sales_data['sales_growth_type']=='P'){ ?>
            <div class="plus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($sales_data['since_sales_order'],2);?>%
            </div>
         <?php } elseif($sales_data['sales_growth_type']=='L'){ ?>
            <div class="minus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($sales_data['since_sales_order'],2);?>%
            </div>
         <?php } ?>
            Since last months
         </div>
      </div>
   </li>

   <li>
      <div class="row-c align-items-center">
         <div class="m-pic">
            <img src="<?php echo assets_url() ?>images/month-4.png" class="full-img">
         </div>
         <div class="m-title">
            <h2 class="color-4">New Customers</h2>
            <?php echo $customer_data['customers'];?>
         </div>
         <div class="m-info">
         <?php if($customer_data['customer_growth_type']=='P'){ ?>
            <div class="plus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($customer_data['since_customers'],2);?>%
            </div>
         <?php } elseif($customer_data['customer_growth_type']=='L'){ ?>
            <div class="minus-up">
               <span class="arrow">
                  <svg width="20px" height="20px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2.5L16.5 9H13v8H7V9H3.5L10 2.5z"/></svg>
               </span><br>
               <?php echo round($customer_data['since_customers'],2);?>%
            </div>
         <?php } ?>
            Since last months
         </div>
      </div>
   </li>

</ul>
