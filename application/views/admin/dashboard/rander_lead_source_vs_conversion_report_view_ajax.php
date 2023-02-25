<?php if(count($rows)){ ?>
   <?php foreach($rows as $row){ ?>
      <?php
         $str=$row->total_revenue_wise_currency;
         $str_arr=explode("@",$str);
         $total_revenue_wise_currency_arr=array();
         foreach($str_arr AS $arr_val1)
         {
            // print_r($arr_val1);
            // echo"<br>";
            $arr2=explode(",",$arr_val1);
            $arr4=array();
            foreach($arr2 AS $arr_val2)
            {
               $arr3=explode("#",$arr_val2);
               $arr4=array($arr3[1]=>$arr3[0]);
               array_push($total_revenue_wise_currency_arr,$arr4);
            }
            //array_push($total_revenue_wise_currency_arr,$arr4);
         }        
      ?>
      <tr>
         <td><?php echo $row->source_name; ?></td>
         <td><?php echo $row->total_new_lead_count; ?></td>
         <td><?php echo $row->total_updated_lead_count; ?></td>
         <td><?php echo $row->total_quoted_lead_count; ?></td>
         <td><?php echo $row->total_deal_lost_lead_count; ?></td>
         <td><?php echo $row->total_deal_won_lead_count; ?></td>
         <?php if(count($currency_list)){  ?>
            <?php foreach($currency_list AS $currency){ ?>
               <td>
               <?php
               $existing_rev=0;
               //print_r($total_revenue_wise_currency_arr);
               foreach($total_revenue_wise_currency_arr AS $rev_curr_val)
               {
                  //print_r($rev_curr_val[$currency->code]);
                  if(isset($rev_curr_val[$currency->code]))
                  {
                     $existing_rev=$rev_curr_val[$currency->code]+$existing_rev;
                  }
               }
               echo number_format($existing_rev,2);
               //echo $row->total_revenue_wise_currency;
               ?>
               </td>
            <?php } ?>
         <?php } ?>
         <td><?php echo $row->total_auto_regretted_lead_count; ?></td>
         <td><?php echo $row->total_auto_deal_lost_lead_count; ?></td>
      </tr>
   <?php } ?>
<?php }else{ ?>
   <tr><td colspan="<?php echo (8+count($currency_list)); ?>">No Lead Source Vs. Conversion available.</td></tr>
<?php } ?>