<?php //print_r($rows); ?>
<div class="tabContent" id="tabContent1">
  <div class="">
	  <table id="" class="table product-list no-border">
		 <thead id="thead">
			<tr>
				<th>Vendor</th>
				<th>Price</th>
				<th>Currency</th>
				<th>Unit</th>
				<th>Unit Type</th>
				<th>Created On</th>
			</tr>
			
		 </thead>
		 <tbody id="" class="t-contant-img">
			<?php
			//print_r($rows);
			if(count($rows)>0)
			{
				foreach($rows as $row) 
				{ 
			?>
				<tr id="tr_<?php echo $row->id; ?>">   

					<td>
						<b><?php echo $row->vendor_company_name; ?></b>
						<br><small><?php echo $row->vendor_contact_person; ?> <br><?php echo $row->vendor_mobile; ?> <br><?php echo $row->vendor_email; ?></small>
					</td>
					<td id="price_div_<?php echo $row->id; ?>">
						<div id="ptb_price_html_<?php echo $row->id; ?>"><?php echo $row->price; ?></div>
						<div style="display: none;" id="ptb_price_input_html_<?php echo $row->id; ?>">
							<input type="text" max="10" class="form-control double_digit" id="ptb_price_<?php echo $row->id; ?>" placeholder="Price" value="<?php echo $row->price; ?>" />
						</div>                
					</td>
					<td id="currency_div_<?php echo $row->id; ?>">
						<div id="ptb_currency_type_html_<?php echo $row->id; ?>"><?php echo $row->curr_name; ?></div>
						<div style="display: none;" id="ptb_currency_type_input_html_<?php echo $row->id; ?>">
							<select class="form-control" id="ptb_currency_type_<?php echo $row->id; ?>" >
							  <?php
							  foreach($currency_list as $currency_data)
							  {
								  ?>
								  <option value="<?=$currency_data->id;?>" <?php if($currency_data->id==$row->currency_type){echo"SELECTED";} ?>><?=$currency_data->name;?>(<?=$currency_data->code;?>)</option>
								  <?php
							  }
							  ?>
							</select>
						</div>
					</td>
					<td id="unit_div_<?php echo $row->id; ?>">
						<div id="ptb_unit_html_<?php echo $row->id; ?>"><?php echo $row->unit; ?></div>
						<div style="display: none;" id="ptb_unit_input_html_<?php echo $row->id; ?>">
							<input type="text" max="10" class="form-control only_natural_number" id="ptb_unit_<?php echo $row->id; ?>" placeholder="Unit" value="<?php echo $row->unit; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
						</div> 
					</td>
					<td id="unit_type_div_<?php echo $row->id; ?>">
						<div id="ptb_unit_type_html_<?php echo $row->id; ?>"><?php echo $row->unit_type_name; ?></div>
						<div style="display: none;" id="ptb_unit_type_input_html_<?php echo $row->id; ?>">
							<select class="form-control" id="ptb_unit_type_<?php echo $row->id; ?>" >
							  <?php
							  foreach($unit_type_list as $unit_type_data)
							  {
								  ?>
								  <option value="<?=$unit_type_data->id;?>" <?php if($unit_type_data->id==$row->unit_type){echo"SELECTED";} ?>><?=$unit_type_data->type_name;?></option>
								  <?php
							  }
							  ?>                          
						  </select>
						</div>
					</td>
					<td><?php echo date_db_format_to_display_format($row->created_datetime); ?></td>       
					
				</tr>
			<?php 
				} 
			}
			else
			{
				echo'<tr><td colspan="8">No Record Found..</td></tr>';
			}
			?>
		</tbody>
	  </table>	  
   </div>	
</div>  
