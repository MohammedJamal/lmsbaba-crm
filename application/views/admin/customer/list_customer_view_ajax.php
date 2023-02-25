<?php 
if($rows)
{ 
	foreach($rows as $customer_data) 
	{ 
	?>
	<tr class="<?php if($customer_data->is_blacklist=='Y'){ ?>bg-danger<?php }else{?><?php } ?>">
		<td class="text-left">
	  		<label class="check-box-sec">
	        	<input type="checkbox" value="<?php echo $customer_data->id;?>" class="" name="customer_id" 
	        	<?php 
	        	if(!empty($this->session->userdata('checked_customer_ids')))
	        	{
	        		if(in_array($customer_data->id,$this->session->userdata('checked_customer_ids'))){echo'checked';}
	        	}
	        	 ?>>
	            <span class="checkmark"></span>
	        </label>
	        <!-- <span class="fav-icon"><i class="fa fa-star" aria-hidden="true"></i></span> -->
	  	</td>
	  	<td class="text-left"><?php echo $customer_data->id; ?></td>

	  	<td class="text-left">
	  		<a href="JavaScript:void(0);" class="get_detail_modal max-wid" data-id="<?php echo $customer_data->id;?>"><?php echo ($customer_data->company_name)?$customer_data->company_name:'NA';?></a>
	  	</td>
	  	<td class="text-center">
			<?php echo ($customer_data->contact_person)?$customer_data->contact_person:'N/A'; ?> 
			
				<br> (<a href="JavaScript:void(0);" title="Contact Person List" class="list_more_contact_persion_view" data-id="<?php echo $customer_data->id;?>"><?php echo ($customer_data->contact_persion_count+1);?> Contact<?php echo ($customer_data->contact_persion_count>0)?'s':''; ?></a>) <?php if(is_method_available('customer','edit')==TRUE){ ?><a href="JavaScript:void(0);" title="Add Contact Persion" class="add_more_contact_persion_view" data-id="<?php echo $customer_data->id;?>"><i class="fa fa-plus-square" aria-hidden="true"></i></a><?php } ?>
			
		</td>
	  	<td class="text-center"><?php echo ($customer_data->designation)?$customer_data->designation:'N/A'; ?></td>
	  	<td class="text-center">
	  		<?php 									
		// if($customer_data->assigned_user_id>0)
		// {
		// 	$assign_to=get_value("name","user","id=".$customer_data->assigned_user_id);
		// }
		// echo ($assign_to)?$assign_to:'--';
		echo ($customer_data->assigned_user_name)?$customer_data->assigned_user_name:'--';
		?><br>(<a href="JavaScript:void(0);" class="company_assigne_change clink" data-cid="<?php echo $customer_data->id;?>" data-currassigned="<?php echo $customer_data->assigned_user_id; ?>">Change</a>)
	  	</td>
	  	<td class="text-center">
	  		<a href="JavaScript:void(0);" data-cid="<?php echo $customer_data->id; ?>" class="open_lead_view" data-filter=""><?php echo $customer_data->lead_count; ?></a><br>
	  		<?php if($customer_data->is_blacklist=='N'){ ?>
			  <a href="JavaScript:void(0);" class="open_add_lead_view" data-cid="<?php echo $customer_data->id;?>" data-mobile="<?php echo $customer_data->mobile;?>" data-email="<?php echo $customer_data->email;?>" title="Add new lead"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
			<?php } ?>
		</td>
	  	<td class="text-center"><?php echo $customer_data->lead_won_count; ?></td>
	  	
	  	<td class="text-left auto-show hide">
	  		<?php echo ($customer_data->email)?$customer_data->email:'N/A' ?>
	  		
	  	</td>  
	  	<td class="text-left auto-show hide">
	  		<?php echo ($customer_data->mobile)?$customer_data->mobile:'N/A' ?>
	  	</td>	
	  	
	  	
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->address)?$customer_data->address:'N/A'; ?></td>
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->country_name)?$customer_data->country_name:'N/A'; ?></td>
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->state_name)?$customer_data->state_name:'N/A'; ?></td>
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->city_name)?$customer_data->city_name:'N/A'; ?></td>
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->zip)?$customer_data->zip:'N/A'; ?></td>
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->gst_number)?$customer_data->gst_number:'N/A'; ?></td>
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->create_date!='0000-00-00')?date_db_format_to_display_format($customer_data->create_date):'N/A'; ?></td>
	  	<td class="text-center  auto-show hide"><?php echo ($customer_data->last_mail_sent!='0000-00-00 00:00:00' && $customer_data->last_mail_sent!=null)?date_db_format_to_display_format($customer_data->last_mail_sent):'N/A'; ?></td>
		<td class="text-center  auto-show hide"><?php echo ($customer_data->reference_name)?$customer_data->reference_name:'N/A'; ?></td>
		<td data-title="Action" class="text-center">
			<div class="action-holder">
				<a href="JavaScript:void(0);" class="get_detail_modal" data-id="<?php echo $customer_data->id;?>" title="View Details"><i class="fa fa-search-plus"></i></a>&nbsp;&nbsp;

				<a href="JavaScript:void(0);" class="send_mail_to_company_modal" data-id="<?php echo $customer_data->id;?>" data-email="<?php echo $customer_data->email;?>" title="Mail to Company"><i class="fa fa-envelope"></i></a>&nbsp;&nbsp;

				<a href="JavaScript:void(0);" class="get_history_modal" data-id="<?php echo $customer_data->id;?>" title="Company History"><i class="fa fa-history"></i></a>&nbsp;&nbsp;

				<?php if(is_method_available('customer','edit')==TRUE){ ?>
				<!-- <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/edit/<?php echo $customer_data->id;?>" title="Edit Company"><i class="fa fa-pencil"></i></a> -->
				<a href="JavaScript:void(0);" title="Edit Company" class="edit_customer_view" data-id="<?php echo $customer_data->id;?>"><i class="fa fa-pencil"></i></a>
				<?php }else{ ?>
					<i class="fa fa-pencil" style="text-decoration: line-through;"></i>
				<?php } ?> 
				&nbsp;&nbsp;<a href="JavaScript:void(0);" class="blacklist_toggle" data-id="<?php echo $customer_data->id;?>" data-blacklist_status="<?php echo $customer_data->is_blacklist; ?>" <?php if($customer_data->is_blacklist=='Y'){ ?>title="Reactivate Company"<?php }else{?>title="Blacklist Company"<?php } ?>><i class="fa fa-ban " <?php if($customer_data->is_blacklist=='Y'){ ?>style="color:red !important;"<?php }else{?>style="color:green!important;"<?php } ?>></i></a>
			</div>               
		</td>
	</tr>
	<?php
	}
}
else
{
?>
	<tr>
		<td colspan="8">Oops! No company found..</td>
	</tr>
<?php
}
?>
<input type="hidden" id="limit_per_page" value="<?php echo $limit; ?>">
<style type="text/css">
.modal{overflow-y: auto !important;}
</style>