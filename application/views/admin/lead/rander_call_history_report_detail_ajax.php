<div class="bg_white_filt">
 <div class="custom-table-responsive table-toggle-holder scroll-table-responsive">
        <div class="table-full-holder">
          <div class="table-one-holder">
            <table class="table custom-table" id="renewal_table">
              <thead>
                <tr>
                  <tr>   
					<?php if($is_delete_icon_show=='Y'){ ?>      
                  	<th scope="col" class=""></th>
					  <?php } ?>
                  	<th scope="col" class=""></th> 
                    <th scope="col" class="">Name</th>
                    <th scope="col" class="">Contact Number</th>
                    <th scope="col" class="">Call Type</th>
                    <th scope="col" class="">Call Start</th>
                    <th scope="col" class="">Call End</th>
                    <th scope="col" class="" align="center">Total Call Time (H:m:s)</th>
                    <th scope="col" class="">Assigned User</th>
                    <th scope="col" class="">Comment</th>
                  </tr>
                </tr>
              </thead>
              <tbody id="">
              	<?php if(count($rows)){ ?>
			   	<?php foreach($rows AS $row){ ?>
			    <tr scope="row" class="" id="chrd_tr_<?php echo $row->id; ?>">
					<?php if($is_delete_icon_show=='Y'){ ?>      
					<td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>">
						<a class="icon-btn custom-tooltip tooltipstered delete_row" href="JavaScript:void(0)" data-id="<?php echo $row->id; ?>" title="Delete the row"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>  
					<?php } ?>
      				<td class="">
			        <?php if($row->is_paying_customer=='Y'){ ?>
			        <div class="call-log-lead-crown tooltipstered">
			          <img style="width:25px;" src="<?php echo assets_url(); ?>images/crown.png" title="Paying Customer" alt="Paying Customer">
			        </div>
			        <?php } ?>
			      </td>    
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>"><?php echo ($row->name)?$row->name:'-'; ?></td>
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>"><?php echo ($row->number)?$row->number:'-'; ?></td>      
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>"><?php echo ($row->bound_type)?ucwords($row->bound_type):'-'; ?></td>
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>"><?php echo ($row->call_start)?datetime_db_format_to_display_format($row->call_start):'-'; ?></td>
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>"><?php echo ($row->call_end)?datetime_db_format_to_display_format($row->call_end):'-'; ?></td>
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>" align="center">
			        <?php         
			        if($row->call_start!='' && $row->call_end!='')
			        {
			          $date_a = new DateTime($row->call_start);
			          $date_b = new DateTime($row->call_end);
			          $interval = date_diff($date_a,$date_b);
			          echo $interval->format('%h:%i:%s');
			        }
			        else
			        {
			          echo'-';
			        }        
			        ?>
			      </td>
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>"><?php echo ($row->assigned_user_name)?$row->assigned_user_name:'-'; ?></td>
			      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>" width="20%"><?php echo ($row->status_wise_msg)?$row->status_wise_msg:'-'; ?></td>
			    </tr>
				<?php } ?>
				<?php }else{ ?>
				  <tr><td colspan="8" style="text-align:center;">No record found!</td></tr>
				<?php } ?>
              </tbody>
            </table>            
          </div>
        </div>
      </div>

</div>
<style type="text/css">
/*.scroll-table-responsive {
	overflow-y:scroll;
    max-height:450px;
}*/
</style>