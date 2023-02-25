<?php //print_r($lead_data); ?>
<div class="row">
    <div class="col-md-12">
    	<p style="font-size: 16px;" class="text-info">
    		<u><?php echo $lead_data->cus_contact_person;?></u><br>
    		<u><?php echo ($lead_data->cus_company_name)?$lead_data->cus_company_name.', ':''; ?>
    			<?php echo ($lead_data->cus_country)?$lead_data->cus_country.'.':''; ?>
    		</u>
    	</p>
    	<p><?php echo ($lead_data->buying_requirement)?stripcslashes($lead_data->buying_requirement):'No requirement found!'; ?> </p>
    </div>
</div>