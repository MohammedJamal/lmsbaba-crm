<ul class="grid-view-ul">
<?php 
$mc = 1;
if(count($rows)>0) 
{
   foreach($rows as $lead_data)  
   { 
?>
<li>
	<div class="product_bg lead_grid_block">	      
	   <div class="total_info">
	      <div class="total_info_left">
	         <div class="mail-form-row max-width sm-gap no-border">
			   <div class="auto-row">
			      <label>Lead ID:</label><br>
			      <input type="text" class="mail-input" value="<?php echo $lead_data->id;?>" disabled="" size="6">
			   </div>
			   <div class="auto-row">
			      <label>Lead Date:</label><br>
			      <input type="text" class="mail-input" value="<?php echo $enquiry_date = date_db_format_to_display_format($lead_data->enquiry_date);?>" disabled="" size="11">
			   </div>
			   <div class="auto-row">
			   		<?php 
					$closer_date=($lead_data->closer_date)?date_db_format_to_display_format($lead_data->closer_date):''; 
					$deal_value='';
					$deal_value_currency_code='';
					if($lead_data->quotation_matured_deal_value_as_per_purchase_order){
						$deal_value=round($lead_data->quotation_matured_deal_value_as_per_purchase_order);
						$deal_value_currency_code=$lead_data->quotation_matured_currency_code;
					}
					else{ 
						if($lead_data->quotation_sent_deal_value){
							$deal_value=round($lead_data->quotation_sent_deal_value);
							$deal_value_currency_code=$lead_data->quotation_sent_currency_code;
						}
						else{
							$deal_value=($lead_data->deal_value)?round($lead_data->deal_value):'';
							$deal_value_currency_code=$lead_data->deal_value_currency_code;
						}
					} 
					
					if($lead_data->quotation_sent_deal_value>0 || $lead_data->quotation_matured_deal_value_as_per_purchase_order>0){ 
						$is_deal_value_editable='N'; 
					}
					else{
						$is_deal_value_editable='Y';
					}
					
					?>
					
					<label>Closure Date:</label><br>
					<div class="d-flex" style="align-items: center;">
						<input type="text" class="mail-input" value="<?php echo ($closer_date)?$closer_date:'N/A'; ?>" disabled="" size="9" style="min-width: 25px" id="lead_closer_date_<?php echo $lead_data->id; ?>"> <a href="JavaScript:void(0)" id="link_lead_closer_date_<?php echo $lead_data->id; ?>" class="closer_date_deal_value_popup" data-id="<?php echo $lead_data->id; ?>" data-closer_date="<?php echo $closer_date; ?>" data-deal_value="<?php echo $deal_value; ?>" data-currency_code="<?php echo $deal_value_currency_code; ?>" data-is_deal_value_editable="<?php echo $is_deal_value_editable; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					</div>
				</div>

				<div class="auto-row">
					<label>Deal Value:</label><br>						
					<div class="d-flex" style="align-items: center;">
						<input type="text" class="mail-input" value="<?php echo ($deal_value)?$deal_value_currency_code.' '.$deal_value:'N/A'; ?>" disabled="" size="9" style="min-width: 25px" id="lead_deal_value_<?php echo $lead_data->id; ?>"> <?php if($is_deal_value_editable=='Y'){ ?><a href="JavaScript:void(0)" id="link_lead_deal_value_<?php echo $lead_data->id; ?>" class="closer_date_deal_value_popup" data-id="<?php echo $lead_data->id; ?>" data-closer_date="<?php echo $closer_date; ?>" data-deal_value="<?php echo $deal_value; ?>" data-currency_code="<?php echo $deal_value_currency_code; ?>" data-is_deal_value_editable="<?php echo $is_deal_value_editable; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><?php } ?>
					</div>
				</div>
			   <div class="auto-row">
			      <label>Quotation:</label><br>
			      <a href="JavaScript:void(0)" class="blue-link <?php echo ($lead_data->proposal>0)?'quoted_view_popup':'';?>" data-customerid="<?php echo $lead_data->cus_id; ?>" data-quotedlids="<?php echo $lead_data->id;?>"><?php echo ($lead_data->proposal>0)?$lead_data->proposal:'N/A';?></a>
			   </div>
			   <div class="auto-row">
			   		<span id="hotstar_div_<?php echo $lead_data->id;?>">
					<?php
					if($lead_data->is_hotstar=='Y'){
						$icon='<i class="fa fa-star" aria-hidden="true" style="color: #FBD657 !important"></i>';							
					}
					else{
						$icon='<i class="fa fa-star-o" aria-hidden="true" style="color: #b1afa7 !important"></i>';							
					}
					?>
					 <a class="pr-5px change_status_hotstar" href="JavaScript:void(0);" data-leadid="<?php echo $lead_data->id;?>" id="hotstar_icon_<?php echo $lead_data->id;?>"><?php echo $icon; ?></a>
				  </span>
			   </div>
			   
			   <div  class="auto-row">						
					<a href="Javascript:void(0)" class="like-dis-icon like_icon  <?php if($lead_data->current_stage_id!='1' && $lead_data->current_stage_id!='5' && $lead_data->current_stage_id!='3' && $lead_data->current_stage_id!='6' && $lead_data->current_stage_id!='7'){echo 'up';}else{} ?>" id="like_<?php echo $lead_data->id;?>" data-id="<?php echo $lead_data->id;?>" data-toggle="tooltip" title="Change Stage" data-placement="left">
				   	<svg data-name="Layer 21" height="24" id="Layer_21" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><title/><rect height="10" width="3" x="2" y="10"/><path d="M19,9H14V4a1,1,0,0,0-1-1H12L7.66473,8.37579A3.00021,3.00021,0,0,0,7,10.259V18a2,2,0,0,0,2,2h6.43481a2.99991,2.99991,0,0,0,2.69037-1.67273L21,12.5V11A2,2,0,0,0,19,9Z"/></svg>
				   </a>
			   </div>
			   <?php if($lead_data->current_stage_id!='4'){ ?>
			   <div  class="auto-row">
				
			   	<a href="Javascript:void(0);" class="like-dis-icon  dislike_icon <?php if($lead_data->current_stage_id=='5' || $lead_data->current_stage_id=='3' || $lead_data->current_stage_id=='6' || $lead_data->current_stage_id=='7'){echo 'down';}else{echo 'dislike_action';} ?>" id="dislike_<?php echo $lead_data->id;?>" data-id="<?php echo $lead_data->id;?>" data-toggle="tooltip" title="Mark Deal Lost" data-placement="left">
			   		<svg data-name="Layer 21" height="24" id="Layer_21" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><title/><rect height="10" width="3" x="2" y="10"/><path d="M19,9H14V4a1,1,0,0,0-1-1H12L7.66473,8.37579A3.00021,3.00021,0,0,0,7,10.259V18a2,2,0,0,0,2,2h6.43481a2.99991,2.99991,0,0,0,2.69037-1.67273L21,12.5V11A2,2,0,0,0,19,9Z"/></svg>
			   	</a>
			   </div>
			   <?php } ?>
			   <div class="pull-right ml-20"><a href="JavaScript:void(0)" class="blue-link view_lead_history latest_lead_history" data-leadid="<?php echo $lead_data->id;?>" id="history_div_<?php echo $lead_data->id;?>"><i class="fa fa-history" aria-hidden="true"></i> History</a></div>
				
				<!-- NOTE -->
			    <div class="pull-right" id="note_outer_<?php echo $lead_data->id;?>">
					<div class="lead-dropdown new-style">
						<div class="dropdown">
							<button class="btn-dropdown note-icon note_btn" data-id="<?php echo $lead_data->id;?>" data-leadid="<?php echo $lead_data->id;?>" type="button" data-toggle="tooltip" data-placement="top" title="Add Note">
							<span id="note_count_<?php echo $lead_data->id;?>" class="note_count_<?php echo $row->id;?>" style="background:<?php echo ($lead_data->note_count>0)?'#59bb60':'#709fe5'; ?>"><?php echo $lead_data->note_count; ?></span>
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
	<g>
		<g>
			<path d="M128,256h34.133c4.719,0,8.533-3.823,8.533-8.533s-3.814-8.533-8.533-8.533H128c-4.719,0-8.533,3.823-8.533,8.533
				S123.281,256,128,256z"/>
			<path d="M264.533,187.733H128c-4.719,0-8.533,3.823-8.533,8.533s3.814,8.533,8.533,8.533h136.533
				c4.719,0,8.533-3.823,8.533-8.533S269.252,187.733,264.533,187.733z"/>
			<path d="M256,298.667c0,4.71,3.814,8.533,8.533,8.533h85.333c4.719,0,8.533-3.823,8.533-8.533s-3.814-8.533-8.533-8.533h-85.333
				C259.814,290.133,256,293.956,256,298.667z"/>
			<path d="M298.667,238.933h-102.4c-4.719,0-8.533,3.823-8.533,8.533s3.814,8.533,8.533,8.533h102.4
				c4.719,0,8.533-3.823,8.533-8.533S303.386,238.933,298.667,238.933z"/>
			<path d="M298.667,136.533h17.067c4.719,0,8.533-3.823,8.533-8.533s-3.814-8.533-8.533-8.533h-7.97
				c3.866-27.861,26.129-34.125,27.102-34.389c3.806-0.947,6.468-4.361,6.468-8.277V42.667c0-4.71-3.814-8.533-8.533-8.533
				c-2.688,0-6.656-0.64-8.533-1.468V17.067h51.2v15.599c-1.775,0.777-5.53,1.451-8.533,1.468c-4.719,0-8.533,3.823-8.533,8.533
				V76.8c0,3.917,2.671,7.33,6.468,8.277c0.247,0.068,23.177,6.135,27.102,34.389h-42.103c-4.719,0-8.533,3.823-8.533,8.533v51.2
				c0,4.71,3.814,8.533,8.533,8.533c4.719,0,8.533-3.823,8.533-8.533v-42.667h42.667c4.719,0,8.533-3.823,8.533-8.533
				c0-33.604-19.703-50.842-34.133-57.216V50.398c9.054-1.775,17.067-6.656,17.067-16.265v-25.6C392.533,3.823,388.719,0,384,0
				h-68.267c-4.719,0-8.533,3.823-8.533,8.533v25.6c0,9.609,8.013,14.49,17.067,16.265v20.386
				c-14.43,6.374-34.133,23.612-34.133,57.216C290.133,132.71,293.948,136.533,298.667,136.533z"/>
			<path d="M128,307.2h102.4c4.719,0,8.533-3.823,8.533-8.533s-3.814-8.533-8.533-8.533H128c-4.719,0-8.533,3.823-8.533,8.533
				S123.281,307.2,128,307.2z"/>
			<path d="M435.2,68.267h-8.533c-4.719,0-8.533,3.823-8.533,8.533s3.814,8.533,8.533,8.533h8.533c14.114,0,25.6,11.486,25.6,25.6
				v358.4c0,14.114-11.486,25.6-25.6,25.6H196.267c-4.719,0-8.533,3.823-8.533,8.533s3.814,8.533,8.533,8.533H435.2
				c23.526,0,42.667-19.14,42.667-42.667v-358.4C477.867,87.407,458.726,68.267,435.2,68.267z"/>
			<path d="M136.533,375.467H93.867c-4.719,0-8.533,3.823-8.533,8.533s3.814,8.533,8.533,8.533h42.667
				c9.412,0,17.067,7.654,17.067,17.067v73.267l-102.4-102.4V110.933c0-14.114,11.486-25.6,25.6-25.6h196.267
				c4.719,0,8.533-3.823,8.533-8.533s-3.814-8.533-8.533-8.533H76.8c-23.526,0-42.667,19.14-42.667,42.667V384
				c0,2.261,0.896,4.437,2.5,6.033L156.1,509.5c1.63,1.63,3.814,2.5,6.033,2.5c1.101,0,2.21-0.213,3.268-0.648
				c3.191-1.323,5.265-4.437,5.265-7.885V409.6C170.667,390.775,155.358,375.467,136.533,375.467z"/>
			<path d="M247.467,358.4h119.467c4.719,0,8.533-3.823,8.533-8.533s-3.814-8.533-8.533-8.533H247.467
				c-4.719,0-8.533,3.823-8.533,8.533S242.748,358.4,247.467,358.4z"/>
			<path d="M392.533,247.467c0-4.71-3.814-8.533-8.533-8.533h-51.2c-4.719,0-8.533,3.823-8.533,8.533S328.081,256,332.8,256H384
				C388.719,256,392.533,252.177,392.533,247.467z"/>
		</g>
	</g>
</g>
</svg>
								<!-- <img src="<?php //echo assets_url(); ?>images/note.png"> -->
							</button>

							<div class="dropdown-menu left" id="note_inner_div_<?php echo $lead_data->id;?>"></div>
						</div>
					</div>
				</div>
				<!-- NOTE -->



			</div>
	         <div class="mail-form-row max-width no-border">
	         	<div class="lead-title"><a href="JavaScript:void(0);" class="get_original_quotation" data-id="<?php echo $lead_data->id; ?>"><?php echo mb_substr($lead_data->title,0,80);?></a> <a href="JavaScript:void(0);" class="lead_title_desc_edit_view" data-id="<?php echo $lead_data->id; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:15px;"></i></a></div>
	         	<div class="lead-details"> <?php echo $lead_data->buying_requirement;?></div>

	         	<div class="lead-tag">
	         		<ul class="tags">
	         	<?php
	         	$tagged_p_s=$lead_data->tagged_ps;
	         	if($tagged_p_s)
	         	{
	            	$tagged_p_s_arr=explode(",", $tagged_p_s);
	        			foreach($tagged_p_s_arr AS $ps)
	        			{ 
	        				$ps_arr=explode("#", $ps);
	        				$id=$ps_arr[0];
	        				$name=$ps_arr[1];            				
	        			?>
							<li>
								<span class="tag"><?php echo $name; ?> <a href="JavaScript:void(0);" class="delete_tagged_ps" data-id="<?php echo $id; ?>" data-name="<?php echo $name; ?>" data-leadid="<?php echo $lead_data->id;?>"><img src="<?php echo assets_url(); ?>images/tag-close.png"></a></span>
							</li>
						<?php 
						} 					
	         	}
	         	?>
	         			<li><a href="JavaScript:void(0);" class="add-tag tagged_ps" data-leadid="<?php echo $lead_data->id;?>" title="Tag Products"><i class="fa fa-tags text-info" aria-hidden="true"></i></a></li>
						</ul>
	         	</div>

		<?php 
		if($lead_data->current_stage_id!='1')
		{ 

	     $stage_log_arr=array_unique(explode(',', $lead_data->stage_logs));	
	   	$curr_stage_id=$lead_data->current_stage_id;
	   	?>
	      <div class="lead-status">            		
				<div class="order-status-container mobile-up-down">
					<?php
					$stage_count=0;
					if(count($priority_wise_stage['active_lead_stages_y']))
	         		{
	            		foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
		            	{
		            		if(in_array($stage['id'], $stage_log_arr)){
		            				$stage_count++;
		            			}
	            		}
	            	}
	            	$active_stage_text='';
	            	$k=1;		            	
						if(count($priority_wise_stage['active_lead_stages_y']))
	         		{
	            		foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
		            	{
		            		if(in_array($stage['id'], $stage_log_arr)){
		            			if($stage_count==$k)
		            			{		           
		            				if(($curr_stage_id=='3') || ($curr_stage_id=='5'))
		            				{
		            					$is_active_stage='';
		            				}  
		            				else
		            				{
		            					if(in_array('4', $stage_log_arr)){ 
		            						// $is_active_stage='active deal-won';
		            						$is_active_stage='active--';
		            						}
		            						else{
		            							$is_active_stage='active';
		            							
		            						}
		            						$active_stage_text=$stage['name'];
		            				}
				            	}
				            	else
				            	{
				            		$is_active_stage='';
				            		$active_stage_text=$stage['name'];
				            	}
				            	$k++;
		            		?>
		            		<div class="status-item done <?php echo $is_active_stage; ?> <?php echo (str_replace(' ', '', strtolower($stage['name']))=='dealwon')?'deal-won':''; ?>">
									<div class="status-circle"></div>
									<div class="status-text">
									   <?php echo $stage['name']; ?> 
									</div>
								</div>
		            		<?php
		            		}
		            	}

		            }

		            if(count($priority_wise_stage['active_lead_stages_n']))
	         		{
	            		foreach($priority_wise_stage['active_lead_stages_n'] AS $stage)
		            	{
		            		if($curr_stage_id==$stage['id']){
		            			$lead_lost_show='';
		            			$active_stage_text=$stage['name'];
		            		}
		            		else{
		            			$lead_lost_show='hide';
		            		}
		            		?>
		            		<div class="status-item deactive <?php echo $lead_lost_show; ?>">
									<div class="status-circle"></div>
									<div class="status-text">
									   <?php echo $stage['name'];?>
									   <?php echo ($lead_data->current_stage_wise_msg)?'<br><small>'.$lead_data->current_stage_wise_msg.'</small>':'';?>
									</div>
								</div> 
		            		<?php
		            	}
		            }
	            	?>		            	
			</div>
	   </div>
	   <?php 
		} 
		else
		{
			$active_stage_text='PENDING';
		}
		?>
	         </div>

	         <div class="mail-block-action">
	         	<?php if($lead_data->cus_email){ ?>
	         	<a href="JavaScript:void(0)" class="mLink open_cust_reply_box" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>">
	                <span><img src="<?php echo assets_url(); ?>images/reply_black_icon.png"></span>
	                Reply
	             </a>
	         	<?php }else{ ?>
	         	<a href="JavaScript:void(0)" class="mLink get_alert" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>" data-text="Oops! There is no email added to the company.">
	                <span><img src="<?php echo assets_url(); ?>images/reply_black_icon.png"></span>
	                Reply
	             </a>
	         	<?php } ?> 

	         	<div class="right-btn-holder">
	         		<div class="cl-info">
	         			<strong>Calls:</strong> <a href="JavaScript:void(0)" class="<?php echo ($lead_data->call_count>0)?'call_report_details':''; ?>" data-leadid="<?php echo $lead_data->id;?>" data-userid="<?php echo $lead_data->assigned_user_id; ?>" data-original-title="Talked call/Total call" data-placement="left" data-toggle="tooltip"><?php echo $lead_data->talked_call_count;?>/<?php echo $lead_data->call_count;?></a>
	         		</div>
	         		<div class="cl-info ml-10">
	         			<strong>Meetings:</strong> <a href="JavaScript:void(0)" class="<?php echo ($lead_data->meeting_count>0)?'meeting_report':''; ?>" data-leadid="<?php echo $lead_data->id;?>" data-original-title="Completed meeting/Total meeting" data-placement="left" data-toggle="tooltip"><?php echo $lead_data->meeting_completed_count;?>/<?php echo $lead_data->meeting_count;?></a>
	         		</div>
						<?php if(is_permission_available('send_quotation_non_menu')){ ?>
						<a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/send_quotation_popup/<?php echo $lead_data->id;?>" class="btn btn-primary fright ml-10  send_quotation_popup_iframe " data-title="<?=$lead_data->title?> (Lead #<?php echo $lead_data->id; ?>)">Send Quotation</a>
	             	<?php } ?> 
						<button type="button" class="btn btn-primary rander_update_lead_view2 fright ml-10" data-id="<?php echo $lead_data->id;?>" data-title="<?php echo $lead_data->title;?>">Update</button>
					</div>
				
	         </div>
	         <a href="#" class="show_lead_quote"></a>
	         <div>&nbsp;</div>
	         <div class="mail-form-row blue-label footer-shadow ptb-7" >
				   <div class="auto-row new-label">
				      <label>Next Follow-up:</label>
				      <div class="mail-input-div">
				      	<span id="ndf_<?php echo $lead_data->id;?>">
						<?php
						$nfd=''; 							
						if($lead_data->current_stage_id=='7' || $lead_data->current_stage_id=='6' || $lead_data->current_stage_id=='3' || $lead_data->current_stage_id=='5' || $lead_data->current_stage_id=='4')
						{
							
						}
						else
						{
							if($lead_data->followup_date!='0000-00-00 00:00:00')
							{
								$red_merk_class="";
								if($lead_data->followup_date<date("Y-m-d"))
								{
								 echo'<i class="fa fa-flag red-font-text" aria-hidden="true"></i>&nbsp;';
								}									
								$nfd=datetime_db_format_to_display_format_ampm($lead_data->followup_date);
							}
							else
							{
							  
							}
						}							
						?>
						</span>	
						<?php if($nfd!=''){ ?>
						<input type="text" class="mail-input nfd_input_date" value="<?php echo $nfd; ?>" name="next_followup_date" id="<?php echo $lead_data->id;?>" style="width:120px;" data-lid="<?php echo $lead_data->id;?>" readonly="true">
						<img src="<?php echo assets_url(); ?>images/cal-icon.png" style="width:19px;" >
						<?php }else{echo'--';} ?>
					  </div>
				   </div>				   
				   <div class="auto-row new-label">
				      	<label>Assigned to:</label>
				      	<div class="mail-input-div">
							<a href="JavaScript:void(0);" class="<?php if(is_permission_available('assignee_change_non_menu')){?>company_assigne_change<?php } ?>" data-cid="<?php echo $lead_data->customer_id;?>" data-currassigned="<?php echo $lead_data->assigned_user_id; ?>" data-lid="<?php echo $lead_data->id;?>">
								<?php echo ($lead_data->assigned_user_id==0)?'Common lead pool':$lead_data->assigned_user_name; ?>
							</a>
						</div>
				   </div>
				   <div class="auto-row new-label">
				      <label>Stage:</label>
				      <div class="mail-input-div">
				      	<?php 
				      	echo $active_stage_text;
						// echo $lead_data->ll_stage_name;					      	
				      	?>
				      </div>
				   </div>
				   <div class="auto-row new-label">
				      <label>Status:</label>
				      <div class="mail-input-div"><a href="JavaScript:void(0);" class="lead_status_change" data-cid="<?php echo $lead_data->customer_id;?>" data-lid="<?php echo $lead_data->id;?>" ><?php echo $lead_data->current_status;?></a></div>
				   </div>
				   <div class="auto-row new-label">
				      	<label>Source:</label>
				      	<div class="mail-input-div">
							<a href="JavaScript:void(0);" class="<?php if(is_permission_available('lead_source_change_non_menu')){?>lead_source_change<?php } ?>" data-cid="<?php echo $lead_data->customer_id;?>" data-currsource="<?php echo $lead_data->source_id; ?>" data-lid="<?php echo $lead_data->id;?>">
								<?php echo ($lead_data->source_name)?($lead_data->source_alias_name)?$lead_data->source_alias_name:''.$lead_data->source_name.'':'';?>
							</a> 
						</div>
				   </div>
				   <div class="auto-row new-label">
				      <label>Observer:</label>
				      <div class="mail-input-div"><?php 
				      echo ($lead_data->assigned_observer_name)?'<a hred="JavaScript:void(0);" class="observer_remove" data-leadid="'.$lead_data->id.'"><i class="fa fa-times text-red" aria-hidden="true"></i></a> '.$lead_data->assigned_observer_name:'--'; ?>
				   	</div>
				   </div>
				</div>
	      </div>
	      <div class="product_vendor_action">
	         <div class="product_vendor lead_vendor">
	         	<?php if($lead_data->is_paying_customer=='Y')
	         	{ 
	         	?>
	         	<div class="lead-crown" title="Paying Customer">	            		
	         		<!-- <img src="<?php //echo assets_url(); ?>images/crown.png" title="Paying Customer" alt="Paying Customer"> -->
	         		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 473.658 473.658" style="enable-background:new 0 0 473.658 473.658;" xml:space="preserve">
						<circle style="fill:#FFF;" cx="236.829" cy="236.829" r="236.829"/>
						<g>
							<path style="fill:#FFC000;" d="M334.743,347.39c0,13.608-11.028,24.636-24.629,24.636H164.195
								c-13.601,0-24.629-11.028-24.629-24.636l-30.691-80.042c0-13.601,11.021-24.629,24.629-24.629h207.301
								c13.608,0,24.629,11.028,24.629,24.629L334.743,347.39z"/>
							<path style="fill:#FFC000;" d="M189.818,324.429c5.998,10.935,2.001,24.659-8.934,30.654l0,0
								c-10.935,6.002-24.659,2.001-30.654-8.926L73.087,202.051c-5.998-10.935-2.001-24.659,8.934-30.654l0,0
								c10.927-6.002,24.651-2.001,30.654,8.926L189.818,324.429z"/>
							<path style="fill:#FFC000;" d="M284.498,324.429c-6.002,10.935-2.008,24.659,8.926,30.654l0,0
								c10.927,6.002,24.659,2.001,30.654-8.926l77.144-144.106c5.998-10.935,2.001-24.659-8.934-30.654l0,0
								c-10.927-6.002-24.651-2.001-30.654,8.926L284.498,324.429z"/>
							<path style="fill:#FFC000;" d="M262.344,324.332c0.052,12.475-10.007,22.628-22.475,22.684l0,0
								c-12.475,0.06-22.628-10-22.684-22.475l0.942-163.447c-0.06-12.475,10.007-22.628,22.475-22.684l0,0
								c12.468-0.06,22.621,10,22.684,22.468L262.344,324.332z"/>
							<path style="fill:#FFC000;" d="M102.375,170.877c0,0,69.104,42.766,121.1-24.285l-3.762,98.86l-72.53,8.208L102.375,170.877z"/>
							<path style="fill:#FFC000;" d="M373.306,170.193c0,0-61.573,46.184-113.575-20.859L257,247.161l72.522,8.208L373.306,170.193z"/>
							<circle style="fill:#FFC000;" cx="75.016" cy="168.806" r="31.128"/>
							<circle style="fill:#FFC000;" cx="239.559" cy="134.289" r="31.128"/>
							<circle style="fill:#FFC000;" cx="398.604" cy="168.132" r="31.128"/>
						</g>
						<path style="fill:#B8972F;" d="M334.743,347.39c0,13.608-11.028,24.636-24.629,24.636H164.195
							c-13.601,0-24.629-11.028-24.629-24.636c0,0,56.767-10.523,97.443-10.523S334.743,347.39,334.743,347.39z"/>
						<g style="opacity:0.4;">
							<path style="fill:#FEFF9A;" d="M248.87,306.505c0,2.55-3.351,4.615-7.502,4.615l0,0c-4.14,0-7.502-2.068-7.502-4.615V218.77
								c0-2.55,3.358-4.615,7.502-4.615l0,0c4.147,0,7.502,2.068,7.502,4.615V306.505z"/>
						</g>
						<g style="opacity:0.4;">
							<path style="fill:#FEFF9A;" d="M296.3,317.679c-1.387,2.132-5.389,1.978-8.934-0.337l0,0c-3.541-2.315-5.28-5.931-3.885-8.055
								l38.776-59.228c1.395-2.132,5.396-1.978,8.941,0.337l0,0c3.534,2.322,5.273,5.924,3.878,8.055L296.3,317.679z"/>
						</g>
						<g style="opacity:0.4;">
							<path style="fill:#FEFF9A;" d="M180.825,317.679c1.395,2.132,5.396,1.978,8.941-0.337l0,0c3.534-2.315,5.273-5.931,3.878-8.055
								l-38.776-59.228c-1.395-2.132-5.396-1.978-8.941,0.337l0,0c-3.534,2.322-5.273,5.924-3.878,8.055L180.825,317.679z"/>
						</g>
						<g style="opacity:0.4;">
							<path style="fill:#FEFF9A;" d="M49.939,176.042c-5.471-5.471,0.535-20.62,6.002-26.099c5.479-5.464,18.72-10.658,24.191-5.187
								C85.599,150.231,55.41,181.505,49.939,176.042z"/>
						</g>
						<g style="opacity:0.4;">
							<path style="fill:#FEFF9A;" d="M214.32,140.452c-5.471-5.471,0.535-20.62,6.002-26.091c5.467-5.471,18.713-10.658,24.184-5.187
								C249.977,114.645,219.791,145.919,214.32,140.452z"/>
						</g>
						<g style="opacity:0.4;">
							<path style="fill:#FEFF9A;" d="M373.62,173.499c-5.478-5.471,0.535-20.62,6.002-26.099c5.471-5.464,18.713-10.658,24.184-5.187
								S379.083,178.962,373.62,173.499z"/>
						</g>
</svg>
	         	</div>
	         	<?php 
	         	} 
	         	?>
	         	<?php
	         	$cust_website='';
	         	if($lead_data->cus_website)
	         	{
	         		$cus_website_prefix=http_or_https_check($lead_data->cus_website);
	         		if($cus_website_prefix=='')
	         		{
	         			$cust_website='http://'.$lead_data->cus_website;
	         		}
	         		else
	         		{
	         			$cust_website=$lead_data->cus_website;
	         		}
	         	}
	         	?>
	            <h2>From</h2>
	            <p>
	            	<strong><?php echo ($lead_data->cus_contact_person)?$lead_data->cus_contact_person.'':'';?></strong><br>
				<?php echo ($lead_data->cus_company_name)?$lead_data->cus_company_name.'<br>':'';?>
				<?php echo ($lead_data->cus_mobile)?'Mobile: +'.$lead_data->cus_mobile_country_code.'-'.$lead_data->cus_mobile.'<br>':'';?>
				<?php echo ($lead_data->cus_email)?'Email: '.$lead_data->cus_email.'<br>':'';?>
				<?php 
				if($cust_website!='')
				{
					$cust_website_arr=explode(',', $cust_website);
					$i=1;
					foreach($cust_website_arr AS $website)
					{							
						$website_tmp=$website;
						if(count($cust_website_arr)>1)
						{
							echo ($website)?'Website '.$i.': <a href="'.$website_tmp.'" class="blue-link" target="_blank">Visit</a><br>':'';
						}
						else
						{
							echo ($website)?'Website: <a href="'.$website_tmp.'" class="blue-link" target="_blank">Visit</a><br>':'';
						}
						$i++;
						
					}
				}				

				?>
				<?php 
				$location='';
				if($lead_data->cust_city_name || $lead_data->cust_state_name || $lead_data->cust_country_name)
				{
					$location .=($lead_data->cust_city_name)?$lead_data->cust_city_name.', ':'';
					$location .=($lead_data->cust_state_name)?$lead_data->cust_state_name.', ':'';
					$location .=($lead_data->cust_country_name)?$lead_data->cust_country_name.', ':'';
				}
				echo ($location)?'Location: '.rtrim($location,', '):'';
				?>
	            </p>
	            <div>
	            		<a href="JavaScript:void();" class="blue-link mr-15 get_detail_modal" data-id="<?php echo $lead_data->cus_id; ?>"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
	            		<a href="JavaScript:void();" class="blue-link edit_customer_view" data-id="<?php echo $lead_data->cus_id; ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit</a>
	            		<?php 
	            		if($lead_data->cust_repeat_count>1)
	            		{ 
	            		?>
	            		<p ><a href="JavaScript:void(0);" class="text-danger search_by_customer" data-custemail="<?php echo $lead_data->cus_email; ?>" data-custmobile="<?php echo $lead_data->cus_mobile; ?>"><b>Repeat Buyer: <?php echo $lead_data->cust_repeat_count; ?> times</b></a></p>
	            		<?php 
	            		} 
	            		?>
	            </div>
	         </div>
	         <div class="product_action">
	            <ul>
	               <li>
	               <?php if($lead_data->cus_mobile!=''){ ?>
						<?php 
						if(count($c2c_credentials))
						{
						?>
							<a href="JavaScript:void(0)" class="cicon_btn <?php echo ($lead_data->cus_mobile)?'set_c2c':''; ?>" data-leadid="<?php echo $lead_data->id;?>" data-cusid="<?php echo $lead_data->cus_id; ?>" data-custmobile="<?php echo $lead_data->cus_mobile; ?>" data-contactperson="<?php echo $lead_data->cus_contact_person; ?>" data-usermobile="<?php echo $c2c_credentials['mobile']; ?>" data-userid="<?php echo $c2c_credentials['user_id']; ?>" data-c2c_settings_id="<?php echo $c2c_credentials['id']; ?>" >
								<img src="<?php echo assets_url(); ?>images/square-phone-flip-solid.svg" title="Click to Call using API">
							</a>
						<?php
						}
						else
						{
						?>							
							<a href="JavaScript:void(0)" class="cicon_btn <?php echo ($lead_data->cus_mobile)?'set_call_schedule_from_app':''; ?>" data-leadid="<?php echo $lead_data->id;?>" data-mobile="<?php echo $lead_data->cus_mobile; ?>" data-contactperson="<?php echo $lead_data->cus_contact_person; ?>">
								<img src="<?php echo assets_url(); ?>images/square-phone-flip-solid.svg" title="Click to Call from LMSBABA app">
							</a>
	                  	<?php
	                  	}
						?>
	                 <?php }else{ ?>
	                 	<a href="JavaScript:void(0)" class="cicon_btn get_alert" data-text="Oops! There is no mobile number added to the company.">
	                 		<img src="<?php echo assets_url(); ?>images/square-phone-flip-solid-inactive.svg" title="Mobile nummber is missing">
	                 	</a>
	                 <?php } ?>
	               </li>
	               <li>
	               	<?php 
					
					if($lead_data->cus_mobile!='' && $lead_data->country_id!='0'){ 
	               		if($lead_data->cus_mobile_whatsapp_status==2)
					  	{
					  		$whatsapp_image='square-whatsapp-brands-disabled.svg';
					  		$whatsapp_title='The number is not available in Whatsapp';
							$popup_class="web_whatsapp_popup";
					  	}
					  	else
					  	{
					  		$whatsapp_image='square-whatsapp-brands.svg';
					  		$whatsapp_title='Click to send Whatsapp message';
							$popup_class="web_whatsapp_popup";
					  	}
					  	
					  	?>
	                  	<a href="JavaScript:void(0);" class="cicon_btn <?php echo $popup_class; ?>"  data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>" title="<?php echo $whatsapp_title; ?>">
	                  		<img src="<?php echo assets_url(); ?>images/<?php echo $whatsapp_image; ?>">
	                  	</a>
	                  <?php }else{ ?>
	                  	<a href="JavaScript:void(0);" class="cicon_btn get_alert"  data-text="Oops! There is no mobile number added to the company.">
	                  		<img src="<?php echo assets_url(); ?>images/square-whatsapp-brands-disabled.svg" title="Mobile nummber is missing">
	                  	</a>
	                  <?php } ?>
	               </li>
	               <li>
	               	<?php if($lead_data->cus_email){ ?>	                
	               		<a href="JavaScript:void(0)" class="cicon_btn open_cust_reply_box" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>"><img src="<?php echo assets_url(); ?>/images/envelope-solid.svg"></a>
	               	<?php }else{ ?>
	               		<a href="JavaScript:void(0)" class="cicon_btn get_alert" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>" data-text="Oops! There is no email added to the company."><img src="<?php echo assets_url(); ?>/images/envelope-solid-disabled.svg"></a>
	               	<?php } ?>
	               </li>	
	               <li>
	               	<a href="JavaScript:void(0);" data-leadid="<?php echo $lead_data->id;?>" data-cid="<?php echo $lead_data->customer_id;?>" class="cicon_btn bw bt-schedule-meeting meeting_schedule_view_popup">
					<img src="<?php echo assets_url(); ?>images/handshake-solid.svg">
					</a>	                  	
	               </li>                  
	            </ul>
	         </div>				
	      </div>
	   </div>
	</div>
</li>
<?php 
$mc++;
   } 
}
else
{
	?>
	<li>
		<h2 style="text-align:center">No Lead Found!</h2>
	</li>
	<?php
}
?>
</ul>
<script type="text/javascript">
$(document).ready(function(){
	// $('.latest_lead_history').tooltip({
    //     delay: 500,
    //     placement: "bottom",
    //     title: userDetails,
    //     html: true
    // }); 
	$('.latest_lead_history').popover({
		title:'Latest Lead History',
		trigger: 'hover',
		html:true,
		placement:'left',
		"content": function(){			
			return getLatestLeadHistory(this.id)
		}
	}); 
	
	$('[data-toggle="tooltip"]').tooltip();

	$("body").on("click",".search_by_customer",function(e){
		var cust_email=$(this).attr('data-custemail');
		var cust_mobile=$(this).attr('data-custmobile');
		var post_url=$("#search_type").find('option:first-child').attr("data-id");
		var search_str=(cust_email)?cust_email:cust_mobile;
		$("#top_search_frm").attr("action",post_url);
		$("#search_keyword").val(search_str);
		$("#top_search_frm").submit();		
	});	
	
})
</script>