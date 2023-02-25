<table class="table custom-table" id="lead_table">
   <thead>
      <tr>
         <th scope="col" width="20">
            <label class="control control--checkbox">
             <input type="checkbox" value="all" name="lead_all" class="lead_all_checkbox js-check-all">

               <div class="control__indicator"></div>
            </label>

         </th>
         <th scope="col" class="sort_order" data-field="lead.id" data-orderby="asc">ID</th>
         <th scope="col" width="20" class="plr"></th>

         <th scope="col" class="sort_order desc" data-field="lead.enquiry_date" data-orderby="asc">Date</th>
         <th scope="col" width="13%" class="sort_order" data-field="lead.title" data-orderby="asc">Title</th>
         <th scope="col" class="sort_order" data-field="cus.company_name" data-orderby="asc">Company</th>
         <th scope="col" class="sort_order" data-field="cus.contact_person" data-orderby="asc">Contact Person</th>
         <th scope="col" class="sort_order" data-field="cus.mobile" data-orderby="asc">Mobile</th>
         <th scope="col" class="sort_order" data-field="lead.current_stage" data-orderby="asc">Stage </th>
         <th scope="col" class="sort_order" data-field="user.name" data-orderby="asc">Assigned to</th>
         <th scope="col" class="sort_order" width="110" data-field="lead.followup_date" data-orderby="asc">Next Follow-up</th>
         <th scope="col" class="auto-show hide sort_order" data-field="lead.current_status" data-orderby="asc">Status</th>
         <th scope="col" class="auto-show hide sort_order" data-field="cities.name" data-orderby="asc">City</th>
         <th scope="col" class="auto-show hide sort_order" data-field="states.name" data-orderby="asc">State</th>
         <th scope="col" class="auto-show hide sort_order" data-field="countries.name" data-orderby="asc">Country</th>
         <th scope="col" class="auto-show hide sort_order" data-field="proposal" data-orderby="asc">Quote</th>
         <th scope="col" class="auto-show hide sort_order" data-field="lead.modify_date" data-orderby="asc">Last Updated</th>
         <th scope="col" class="auto-show hide sort_order" data-field="source.name" data-orderby="asc">Source</th>
      </tr>
   </thead>
   <tbody id="tcontent">
<?php 
if(count($rows)>0) 
{
   foreach($rows as $lead_data)  
   { 

   	if($lead_data->current_stage_id!='1')
      {
	      $stage_log_arr=array_unique(explode(',', $lead_data->stage_logs));							
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
         					
         				}  
         				else
         				{
         					
         					$active_stage_text=$stage['name'];
         				}
	            	}
	            	else
	            	{
	            		$active_stage_text=$stage['name'];
	            	}
	            	$k++;			            		
         		}
         	}

         }

         if(count($priority_wise_stage['active_lead_stages_n']))
   		{
      		foreach($priority_wise_stage['active_lead_stages_n'] AS $stage)
         	{
         		if($curr_stage_id==$stage['id']){ 
         			$active_stage_text=$stage['name'];
         		}
         		else{
         			
         		}			            		
         	}
         }		            	
   	} 
   	else
   	{
   		$active_stage_text='PENDING';
   	}
?>
<tr scope="row">
	<td>			
		<label class="control control--checkbox">
		   <input type="checkbox" value="<?php echo $lead_data->customer_id;?>" name="checked_to_customer" data-custemail="<?php echo $lead_data->cus_email; ?>" data-custname="<?php echo $lead_data->cus_contact_person; ?>" data-leadid="<?php echo $lead_data->id;?>">
		    <div class="control__indicator"></div>
		 </label>
		</td>
		<td class="clickable_col"><?php echo $lead_data->id;?></td>
		<td class="plr">	
			<span id="hotstar_div_<?php echo $lead_data->id;?>">
			<?php
			if($lead_data->is_hotstar=='Y')
			{
				$icon='<i class="fa fa-star" aria-hidden="true" style="color: #FBD657 !important"></i>';
				
			}
			else
			{
				$icon='<i class="fa fa-star-o" aria-hidden="true" style="color: #b1afa7 !important"></i>';
				
			}
			?>
			<a class="change_status_hotstar icon-btn text-black size-18 pa-0 fav-icon" href="JavaScript:void(0);" data-leadid="<?php echo $lead_data->id;?>" id="hotstar_icon_<?php echo $lead_data->id;?>"><?php echo $icon; ?></a>
		  </span>	
		</td>
		<td class="clickable_col"><?php echo $enquiry_date = date_db_format_to_display_format($lead_data->enquiry_date);?></td>
		<td class="clickable_col">
			<a href="JavaScript:void(0)" class="get_original_quotation" data-id="<?php echo $lead_data->id; ?>"> <?php echo mb_substr($lead_data->title,0,80);?></a> 		 
		</td>
		<td class="clickable_col"><a href="JavaScript:void(0);" class="get_detail_modal" data-id="<?php echo $lead_data->cus_id; ?>" style="text-decoration:none;color:#505050"><?php echo ($lead_data->cus_company_name)?''.$lead_data->cus_company_name.'':'N/A';?></a></td>
		<td class="clickable_col"><?php echo $lead_data->cus_contact_person; ?></td>
		<td class="clickable_col"><?php echo ($lead_data->cus_mobile)?$lead_data->cus_mobile:'N/A'; ?></td>
		<td class="clickable_col"><span class="alert"><?php echo $active_stage_text; ?></span></td>
		<td class="clickable_col"><a href="JavaScript:void(0);" class="company_assigne_change" data-cid="<?php echo $lead_data->customer_id;?>" data-currassigned="<?php echo $lead_data->assigned_user_id; ?>"><?php echo $lead_data->assigned_user_name;?></a></td>
		<td class="clickable_col">
		 <div class="show-on-hover">
		    <ul>
		       <li>
		       	<a href="Javascript:void(0)" class="icon-btn custom-tooltip like-dis-icon like_icon  <?php if($lead_data->current_stage_id=='8' || $lead_data->current_stage_id=='10' || $lead_data->current_stage_id=='11' || $lead_data->current_stage_id=='2' || $lead_data->current_stage_id=='9' || $lead_data->current_stage_id=='4'){echo 'up';}else{} ?>" id="like_<?php echo $lead_data->id;?>" data-id="<?php echo $lead_data->id;?>" data-toggle="tooltip" title="Change Stage" data-placement="right"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
					</a>
		       </li>
			    <?php if($lead_data->current_stage_id!='4'){ ?>
		        <li>
		       	<a href="Javascript:void(0);" class="icon-btn custom-tooltip like-dis-icon  dislike_icon <?php if($lead_data->current_stage_id=='5' || $lead_data->current_stage_id=='3' || $lead_data->current_stage_id=='6' || $lead_data->current_stage_id=='7'){echo 'down';}else{echo 'dislike_action';} ?>" id="dislike_<?php echo $lead_data->id;?>" data-id="<?php echo $lead_data->id;?>" data-toggle="tooltip" title="Mark Deal Lost" data-placement="right"><i class="fa fa-thumbs-down" aria-hidden="true"></i>
				   </a>
		        </li>
				<?php } ?>
		       <li>
		       	<?php if($lead_data->cus_email){ ?>
            	<a href="JavaScript:void(0)" class="icon-btn custom-tooltip open_cust_reply_box" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>" title="Reply">
                   <i class="fa fa-reply" aria-hidden="true"></i>
                </a>
            	<?php }else{ ?>
            	<a href="JavaScript:void(0)" class="icon-btn custom-tooltip get_alert" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>" data-text="Oops! There is no email added to the company." title="Reply">
                   <i class="fa fa-reply" aria-hidden="true"></i>
                </a>
            	<?php } ?>
		          
		       </li>
		       
		       
	       	<?php 
	       	if($lead_data->cus_mobile)
	       	{ 	
	       		echo'<li>';	       	 
					if(count($c2c_credentials))
					{
					?>
					<a href="JavaScript:void(0)" class="icon-btn custom-tooltip <?php echo ($lead_data->cus_mobile)?'set_c2c':''; ?>" data-leadid="<?php echo $lead_data->id;?>" data-cusid="<?php echo $lead_data->cus_id; ?>" data-custmobile="<?php echo $lead_data->cus_mobile; ?>" data-contactperson="<?php echo $lead_data->cus_contact_person; ?>" data-usermobile="<?php echo $c2c_credentials['mobile']; ?>" data-userid="<?php echo $c2c_credentials['user_id']; ?>" title="Click to Call using API"><i class="fa fa-phone"  aria-hidden="true"></i></a>
					<?php
					}
					else
					{
					?>
		  			<a class="set_call_schedule_from_app icon-btn custom-tooltip" href="JavaScript:void(0)" data-leadid="<?php echo $lead_data->id;?>" data-mobile="<?php echo $lead_data->cus_mobile; ?>" data-contactperson="<?php echo $lead_data->cus_contact_person; ?>" title="Click to Call from LMSBABA app"><i class="fa fa-phone"  aria-hidden="true" ></i></a>
		  			<?php
             	}
             	echo '</li>';
				} 
				?>
	       
	       	<?php 
	       	if($lead_data->cus_mobile!='' && $lead_data->country_id!='0')
	       	{
				  	if($lead_data->cus_mobile_whatsapp_status==2)
				  	{
				  		$whatsapp_image='social-whatsapp-disabled.png';
				  		$whatsapp_title='The number is not available in Whatsapp';
				  	}
				  	else
				  	{
				  		$whatsapp_image='social-whatsapp.png';
				  		$whatsapp_title='Click to send Whatsapp message';
				  	}
			  	
			  		?>			
			  		<li>		  		
			  		<a class="whatsapp web_whatsapp_popup icon-btn custom-tooltip" href="JavaScript:void(0);" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>" title="<?php echo $whatsapp_title; ?>"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
			  		</li>
			  	<?php 
				} 
				?>	
				<?php if(is_permission_available('send_quotation_non_menu')){ ?> 
		       	<li>
		          <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/send_quotation_popup/<?php echo $lead_data->id;?>" class="icon-btn custom-tooltip send_quotation_popup_iframe" title="Send Quotetion" data-title="<?=$lead_data->title?> (Lead #<?php echo $lead_data->id; ?>)">
		             <i class="fa fa-file-text"></i>
		          </a>
		       	</li>
				<?php } ?> 
		       <li>
		       	<a class="rander_update_lead_view2 icon-btn custom-tooltip" href="JavaScript:void(0);" title="Update Lead" data-id="<?php echo $lead_data->id;?>" data-title="<?php echo $lead_data->title;?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		       </li>
		       <li>
		       	<a class="view_lead_history icon-btn custom-tooltip" href="JavaScript:void(0)" data-leadid="<?php echo $lead_data->id;?>" title="History"><i class="fa fa-history" aria-hidden="true"></i></a>
		       </li>
		    </ul>
		 </div>
		 <span id="ndf_<?php echo $lead_data->id;?>">
			<?php  

			$nfd='';                     
			if($lead_data->current_stage_id==1 || $lead_data->current_stage_id==8 || $lead_data->current_stage_id==2 || $lead_data->current_stage_id==9 || $lead_data->current_stage_id==10 || $lead_data->current_stage_id==11)
			{
			if($lead_data->followup_date!='0000-00-00')
			{
				$red_merk_class="";
				if($lead_data->followup_date<date("Y-m-d"))
				{
				 echo'<i class="fa fa-flag red-font-text" aria-hidden="true"></i>&nbsp;';
				}

				//echo date_db_format_to_display_format($lead_data->followup_date);
				$nfd=date_db_format_to_display_format($lead_data->followup_date);
			}
			else
			{
			  echo'--';
			}

			}
			else
			{
			echo'--';
			}                            
			?>
			</span>
			<?php echo $nfd; ?>
		</td>
		<td class="auto-show hide clickable_col"><span class="alert"><?php echo $lead_data->current_status;?></span></td>
		<td class="auto-show hide clickable_col"><?php echo ($lead_data->cust_city_name)?$lead_data->cust_city_name:'N/A';?></td>
		<td class="auto-show hide clickable_col"><?php echo ($lead_data->cust_state_name)?$lead_data->cust_state_name:'N/A';?></td>
		<td class="auto-show hide clickable_col"><?php echo ($lead_data->cust_country_name)?$lead_data->cust_country_name:'';;?></td>
		<td class="auto-show hide clickable_col"><a href="JavaScript:void(0)" class="blue-link <?php echo ($lead_data->proposal>0)?'quoted_view_popup':'';?>" data-customerid="<?php echo $lead_data->cus_id; ?>" data-quotedlids="<?php echo $lead_data->id;?>"><?php echo ($lead_data->proposal>0)?$lead_data->proposal:'N/A';?></a></td>
		<td class="auto-show hide clickable_col"> <?php echo date_db_format_to_display_format($lead_data->modify_date);?></td>
		<td class="auto-show hide">
		 <span class="hide-on-hover"><?php echo ($lead_data->source_name)?''.$lead_data->source_name.'':'';?></span>
		 
		</td>
		</tr>
		<tr class="spacer"><td colspan="100"></td></tr>

	<?php /* ?>
   <tr>  
    	<td data-title="check" class="">
			<label class="check-box-sec">            	
            	<input type="checkbox" value="<?php echo $lead_data->customer_id;?>" name="checked_to_customer" data-custemail="<?php echo $lead_data->cus_email; ?>" data-custname="<?php echo $lead_data->cus_contact_person; ?>" data-leadid="<?php echo $lead_data->id;?>">
                <span class="checkmark"></span>
            </label>
		</td>
		<td data-title="date" class="">			
		  	<?php echo $lead_data->id;?>
		</td>                        
		<td data-title="date" class="text-center less_pad">
		  <?php echo $enquiry_date = date_db_format_to_display_format($lead_data->enquiry_date);?>
		</td>                        
		<td data-title="Phone">
			<a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/edit/<?php echo $lead_data->id;?>"> <?php echo mb_substr($lead_data->title,0,80);?></a> 
			<a href="JavaScript:void(0);" class="get_detail_modal" data-id="<?php echo $lead_data->cus_id; ?>" style="text-decoration:none;color:#505050"><?php echo ($lead_data->cus_company_name)?'<br>('.$lead_data->cus_company_name.')':'';?></a>
			<a href="JavaScript:void(0);" class="" style="text-decoration:none;color:#505050">
				<?php echo ($lead_data->source_name)?'<br>Source: '.$lead_data->source_name.'':'';?> 
				<?php echo ($lead_data->im_account_name)?' >> '.$lead_data->im_account_name.'(AC)':'';?> 
			</a>
			
		</td>
		<td data-title="Company" class="text-center" >
		  <?php echo $lead_data->cust_country_name;?>
		</td>
		<td data-title="Status" class="" align="center">
		  <?php echo $lead_data->assigned_user_name;?>
		  <br> (<a href="JavaScript:void(0);" class="company_assigne_change" data-cid="<?php echo $lead_data->customer_id;?>" data-currassigned="<?php echo $lead_data->assigned_user_id; ?>">Change</a>)
		</td>
		<td class="" align="center">
		  <?php echo date_db_format_to_display_format($lead_data->modify_date);?>
		</td>
		<td data-title="Last Update" class="" align="center less_pad" style="padding-left: 5px; padding-right: 5px; white-space: nowrap;">			
		  <?php                          
		  if($lead_data->current_stage_id==1 || $lead_data->current_stage_id==8 || $lead_data->current_stage_id==2 || $lead_data->current_stage_id==9)
		  {
			if($lead_data->followup_date!='0000-00-00')
			{
				$red_merk_class="";
				if($lead_data->followup_date<date("Y-m-d"))
				{
				 echo'<i class="fa fa-flag red-font-text" aria-hidden="true"></i>&nbsp;';
				}

				echo date_db_format_to_display_format($lead_data->followup_date);
			}
			else
			{
			  echo'--';
			}
			
		  }
		  else
		  {
			echo'--';
		  }                            
		  ?>

		</td>
		<td data-title="Quotation" class="" align="center">
		  <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/edit/<?php echo $lead_data->id;?>"><?php echo ($lead_data->proposal>0)?$lead_data->proposal:'N/A';?></a>
		</td>
		
		<td data-title="Stage" class="" align="center"><?php echo $lead_data->current_stage;?></td>                        
		<td data-title="Status" class="" align="center"><?php echo $lead_data->current_status;?></td>  
		<td data-title="Action" class="text-center" width="100">
			<a href="JavaScript:void(0);" class="pr-5px get_original_quotation" data-id="<?php echo $lead_data->id;?>" id="" title="View Original Enquiry" data-companyinfo="<?php echo $lead_data->cus_company_name.', '.$lead_data->cus_company_country; ?>"><i class="fa fa-search" aria-hidden="true" style="font-size: 15px; color:#008AC9;"></i></a>
			
			<a class="pr-5px rander_update_lead_view2" href="JavaScript:void(0);" title="Update Lead" data-id="<?php echo $lead_data->id;?>" data-title="<?php echo $lead_data->title;?>"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 15px; color:#008AC9;"></i></a>

		  <a class="pr-5px view_lead_history" href="JavaScript:void(0)" data-leadid="<?php echo $lead_data->id;?>"><i class="fa fa-history" title="Lead History" aria-hidden="true" style="font-size: 15px;color:#008AC9;"></i></a>
		  
		  <?php if($lead_data->cus_mobile){ ?>
		  		<?php 
				if(count($c2c_credentials))
				{
				?>
					<a href="JavaScript:void(0)" class="cicon_btn <?php echo ($lead_data->cus_mobile)?'set_c2c':''; ?>" data-leadid="<?php echo $lead_data->id;?>" data-cusid="<?php echo $lead_data->cus_id; ?>" data-custmobile="<?php echo $lead_data->cus_mobile; ?>" data-contactperson="<?php echo $lead_data->cus_contact_person; ?>" data-usermobile="<?php echo $c2c_credentials['mobile']; ?>" data-userid="<?php echo $c2c_credentials['user_id']; ?>" ><i class="fa fa-phone" title="Click to Call using API" aria-hidden="true" style="font-size: 15px;color:#008AC9;"></i></a>
				<?php
				}
				else
				{
				?>
		  			<a class="pr-5px set_call_schedule_from_app" href="JavaScript:void(0)" data-leadid="<?php echo $lead_data->id;?>" data-mobile="<?php echo $lead_data->cus_mobile; ?>" data-contactperson="<?php echo $lead_data->cus_contact_person; ?>"><i class="fa fa-phone" title="Click to Call from LMSBABA app" aria-hidden="true" style="font-size: 15px;color:#008AC9;"></i></a>
		  		<?php
             	}
				?>
		  <?php } ?>
		  
		  <?php if($lead_data->cus_mobile!='' && $lead_data->country_id!='0'){ 
		  	

		  	if($lead_data->cus_mobile_whatsapp_status==2)
		  	{
		  		$whatsapp_image='social-whatsapp-disabled.png';
		  		$whatsapp_title='The number is not available in Whatsapp';
		  	}
		  	else
		  	{
		  		$whatsapp_image='social-whatsapp.png';
		  		$whatsapp_title='Click to send Whatsapp message';
		  	}
		  	
		  	?>
		  <a class="pr-5px whatsapp web_whatsapp_popup" href="JavaScript:void(0);" data-leadid="<?php echo $lead_data->id;?>" data-custid="<?php echo $lead_data->customer_id;?>" title="<?php echo $whatsapp_title; ?>"><img src="<?php echo assets_url(); ?>images/<?php echo $whatsapp_image; ?>" style="width: 18px; height: auto;display: inline-block;"></a>
		  <?php } ?>
		
		  <span id="hotstar_div_<?php echo $lead_data->id;?>">
			<?php
			if($lead_data->is_hotstar=='Y')
			{
				$icon='<i class="fa fa-star" aria-hidden="true" style="color: #FBD657 !important"></i>';
				
			}
			else
			{
				$icon='<i class="fa fa-star-o" aria-hidden="true" style="color: #b1afa7 !important"></i>';
				
			}
			?>
			<a class="pr-5px change_status_hotstar" href="JavaScript:void(0);" data-leadid="<?php echo $lead_data->id;?>" id="hotstar_icon_<?php echo $lead_data->id;?>"><?php echo $icon; ?></a>
		  </span>		   
		</td>
	</tr>
	<?php */ ?>
<?php 
   } 
}
else
{
	echo'<tr><td colspan="11">No Record Found..</td></tr>';
}
?>
</tbody>
</table>
