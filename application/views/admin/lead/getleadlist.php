<?php
if($cus_list)
{

?>
<table cellpadding="2" cellspacing="5" class="table table-bordered table-striped m-b-0">
	<tr>
		<th></th>
		<th>Title</th>
		<th>Name</th>
		<th>Email Id</th>
		<th>Mobile</th>
		<th>City</th>
		<th>Country</th>
	</tr>
	<?php
	$i=0;
	foreach($cus_list as $cust_data)
	{
	
	?>
	<tr>
		<td>
			
			<label class="check-box-sec rounded">
				<input type="radio" class="lead_tag" value="<?php echo $cust_data->id;?>,<?php echo $cust_data->lead_id;?>,<?php echo $cust_data->assigned_user_id;?>" name="lead_tag" id="lead_tag_<?php echo $i?>" />
				<span class="checkmark"></span>
			</label>
		</td>
		<td>
		<a tabindex="0"   
   data-html="true" 
   data-toggle="popover" 
   data-trigger="focus" 
   
   data-placement="bottom"
   data-content="<div class='all_detail_hover'><p><?php echo $cust_data->lead_title;?></p><p><?php echo $cust_data->first_name.' '.$cust_data->last_name;?> <a href='#' class='close_btn_right'><img src='http://maxbridgesolution.com/projectwork/lms/images/close.png' /></a></p><p><?php echo $cust_data->city_name;?>-<?php echo $cust_data->zip;?> <?php echo $cust_data->state_name;?> <?php echo $cust_data->country_name;?></p><ul><li><p><?php echo $cust_data->address;?></p></li><li class='li_right'><p>Buyer ID:230009</p><p>Added On:22 Jun 2017</p></li></ul><p>Email:<?php echo $cust_data->email;?></p><p>Mobile: <?php echo $cust_data->mobile;?></p><p>Office Phone: <?php echo $cust_data->office_phone;?></p><p>Website:<?php echo $cust_data->website;?></p><p>Assigned To: <?php echo $cust_data->assigned_user_name;?></p><h3>Enquiries/ Leads posted by <?php echo $cust_data->first_name.' '.$cust_data->last_name;?></h3></div>">
		<?php echo $cust_data->lead_title;?></a></td>
		<td><?php echo $cust_data->first_name.' '.$cust_data->last_name;?></td>
		<td><?php echo $cust_data->email;?></td>
		<td><?php echo $cust_data->mobile;?></td>
		<td><?php echo $cust_data->city_name;?></td>
		<td><?php echo $cust_data->country_name;?></td>
	</tr>
	 
	
	<?php
	$i++;
	}
	?>
</table>
<div class="margin_top10"><input type="button" value="Tag This Lead" onclick="tag_lead('<?php echo $mail_id; ?>')" class="tag_this_lead"/>
<input type="button" value="Dont Tag" onclick="close_modal()" class="tag_this_lead"/></div>
<input type="hidden" id="tag_lead_reply" name="reply" value=""/>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
});
</script>
<?php
}
else
{
	?>No Buyers found<?php
}
	?>
