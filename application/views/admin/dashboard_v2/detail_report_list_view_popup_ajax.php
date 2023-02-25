<?php if($filter1=='calls'){ ?>
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
            <img src="<?php echo assets_url(); ?>images/crown.png" title="Paying Customer" alt="Paying Customer">
            </div>
            <?php } ?>
        </td>   
        <td class=""><?php echo ($row->tagged_lead_id)?'<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/manage/?search_by_id='.$row->tagged_lead_id.'" target="_blank"><u>'.$row->tagged_lead_id.'</u></a>':'N/A'; ?></td>  
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
        <tr><td colspan="10" style="">No record found!</td></tr>
    <?php } ?>

<?php }else{ ?>
    <?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr scope="row" class="">   
            <td class=""><?php echo ($row['lead_id'])?'<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/manage/?search_by_id='.$row['lead_id'].'" target="_blank"><u>'.$row['lead_id'].'</u></a>':'N/A'; ?></td>  
            <td class=""><?php echo ($row['create_date'])?date_db_format_to_display_format($row['create_date']):'N/A'; ?></td>     
            <td class=""><?php echo ($row['source_name'])?$row['source_name']:'N/A'; ?></td>                                                  
            <td class=""><?php echo ($row['cust_company_name'])?$row['cust_company_name']:'N/A'; ?></td>
            <td class=""><?php echo ($row['cust_contact_person'])?$row['cust_contact_person']:'N/A'; ?></td> 
            <td class=""><?php echo ($row['lead_ls'])?$row['lead_ls']:'N/A'; ?></td>  
            <td class=""><?php echo ($row['assigned_user'])?$row['assigned_user']:'N/A'; ?></td>         
            <td class="">
                
                <?php 
                if($row['deal_value_as_per_purchase_order']){
                    $deal_value_str=$row['lo_currency_code'].' '.$row['deal_value_as_per_purchase_order'];
                }
                else{
                    $deal_value_str=($row['dv'])?$row['lo_currency_code'].' '.number_format($row['dv']):$row['deal_value_currency_code'].' '.number_format($row['deal_value']);
                }
                // echo $currency_info['default_currency_code'].' '.number_format($deal_value);
                echo $deal_value_str;
                ?>
            </td>  
            <td class=""><?php echo ($row['title'])?$row['title']:'N/A'; ?></td> 
            <td class=""><a href="JavaScript:void(0)" class="blue-link view_lead_history latest_lead_history" data-leadid="<?php echo $row['lead_id'];?>" id="history_div_<?php echo $row['lead_id'];?>"><i class="fa fa-history" aria-hidden="true"></i></a></td>              
        </tr>  
        <?php } ?>     
    <?php }else{ ?>
        <tr scope="row" class=""><td class="" colspan="10">No record found!</td></tr>
    <?php } ?>
<?php } ?> 

<script>  
$(document).ready(function(){
    $('.latest_lead_history').popover({
		title:'Latest Lead History',
		trigger: 'hover',
		html:true,
		placement:'left',
		"content": function(){			
			return getLatestLeadHistory(this.id)
		}
	}); 
});
</script>