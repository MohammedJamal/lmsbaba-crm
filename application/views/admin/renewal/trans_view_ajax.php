<?php 
if(count($rows)>0) 
{
   foreach($rows as $row)  
   {   	
?>
<tr scope="row">	
	<td class=""><?php echo $row->id;?></td>	
	<td class="">
		<?php
		if($row->count_renewal_won>0){ ?>
		<div class="re-crown"><img class="custom-tooltips" src="https://dev.lmsbaba.com/assets/images/crown.png" title="Paying Customer" alt="Paying Customer"></div>
		<?php } ?>
	</td>
	<td class=""><a href="JavaScript:void(0);" class="get_detail_modal default-link" data-id="<?php echo $row->cus_id; ?>" style="text-decoration:none;color:#505050"><?php echo ($row->cus_company_name)?''.$row->cus_company_name.'':'N/A';?></a></td>	
	<td class="">		
		<a href="JavaScript:void(0)" class="get_renewal_description default-link" data-id="<?php echo $row->id;?>">
		<?php 		
		if(strlen(strip_tags($row->renewal_product_name_str))>30){echo substr(strip_tags($row->renewal_product_name_str), 0, 30).'...';}else{echo strip_tags($row->renewal_product_name_str);}
			?></a>
		<div id="description_<?php echo $row->id;?>" style="display: none;">
			<?php 
				$p_arr=explode(",", $row->renewal_product_name_str);
				$p_str = '';
				$p_str .='<ol>';
				foreach($p_arr AS $p){
					$p_str .='<li>'.$p.'</li>';
				}
				$p_str .='</ol>';
				echo $p_str;
			?>
		</div>
	</td>
	<td class=""><?php echo number_format($row->renewal_amount,2);?></td>
	<td>
		<?php 
		if($row->lead_id)
		{			
			$won=array('4');
			$lost=array('3','5','6','7');
			if(in_array($row->current_stage_id,$won))
			{
				echo '<span class="badge badge-pill bg-success">Won</span>';
			}
			else if(in_array($row->current_stage_id,$lost))
			{
				echo '<span class="badge badge-pill bg-danger">Lost</span>';
			}
			else
			{
				echo '<span class="badge badge-pill bg-info">Active</span>';
			}	
		}
		else
		{
			echo '<span class="badge badge-pill bg-warning">Pending</span>';
		}
		?>
	</td>
	
	<td class=""><?php echo $renewal_date = date_db_format_to_display_format($row->renewal_date);?></td>
	<td class="">
		<?php if($row->next_follow_up_date!='0000-00-00')
		{
			echo date_db_format_to_display_format($row->next_follow_up_date);
		}
		else
		{
		  echo'--';
		} 
		?>
	</td>
	<td class=""><?php echo $row->cus_assigned_user; ?></td>
	<td class="">
		<?php 
		if($row->lead_id){ ?>
			<a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url'];?>/lead/manage/?search_by_id=<?php echo $row->lead_id; ?>" target="_self" class="default-link"><?php echo $row->lead_id; ?></a>
		<?php
		}else{
			echo'N/A';
		} ?>
	</td>	
	<td class="">		
		<a href="JavaScript:void(0);" class="view_renewal_history" data-id="<?php echo $row->renewal_id;?>" data-rdid="<?php echo $row->id;?>"><i class="fa fa-search-plus"></i></a>
		<?php if($row->is_any_renewal_tagged_with_lead=='N'){ ?>
		&nbsp;
		<a class="icon-btn custom-tooltip tooltipstered renewal_detail_delete text-danger" href="JavaScript:void(0)" data-id="<?php echo $row->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
		<?php } ?>
	</td>
</tr>
<tr class="spacer"><td colspan="11"></td></tr>
<?php 
   } 
}
else
{
	echo'<tr><td colspan="11" style="text-align:center">No Record Found..</td></tr>';
}
?>
