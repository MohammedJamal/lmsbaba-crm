
                                <div class="col-sm-5">
                                    <ul class="buyer_email_subject">
                                        <li><b>Title :</b> <span><?php echo $cus_data->title;?></span>
                                        <input type="hidden" class="form-control " name="title" id="title" value="<?php echo $cus_data->title;?>" />
                                        </li>
                                        <li><b>Assigned To :</b>
                                        <select class="custom-select no_display" name="assigned_to" id="assigned_to" required>
						                	<option>Select</option>
						                	<?php
						                	foreach($user_list as $user_data)
						                	{
												?>
												<option value="<?php echo $user_data->id?>" <?php if($user_data->id==$cus_data->assigned_user_id){ $user_name=$user_data->name; ?> selected="selected" <?php } ?>><?php echo $user_data->name;?></option>
												<?php
											}
						                	?>
						                	
						                </select>
						                <span class="display"><?php echo $user_name;?></span>
                </li>
           
                                        
                                       
                                       
                                    </ul>
                                </div>
                                
                                <div class="col-sm-2">
                                    <div class="edit_icon">
                                        <a href="#"><img style="font-size: 20px; cursor: pointer;" onclick="open_form2()" src="<?=base_url('images/edit_icon.png');?>" alt=""/> </a>                                 
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <ul class="buyer_email_subject">
                                        <li><b>Source :</b>
                <select class="custom-select no_display" name="source" id="source" required>
                	<option>Select</option>
                	<?php
                	foreach($source_list as $source_data)
                	{
						?>
						<option value="<?php echo $source_data->id?>" <?php if($source_data->id==$cus_data->source_id){ $source=$source_data->name; ?> selected="selected" <?php } ?>><?php echo $source_data->name;?></option>
						<?php
					}
                	?>
                </select>
                 <span class="display"><?php echo $source;?></span>
                                        </li>
                                       
                                        <?php
            
			$enquiry_date = date("m/d/Y", strtotime($cus_data->enquiry_date));
            ?>
                                        <li><b>Enquiry Date :</b> <span><?php echo $enquiry_date?></span> <input type="hidden" class="form-control" name="enquiry_date" id="datepicker2" placeholder="Enquiry Date" value="<?php echo $enquiry_date?>" /></li>
                                        
                                       
                                        
                                        
                                    </ul>
                                    
                                </div>
                                <div class="row">
                                <div class="col-sm-12">
                                    <div class="original_requirement">
                                        <p>Original Requirement</p>
                                        <textarea class="form-control" name="description" id="description" required><?php echo $cus_data->description;?></textarea>
                                    </div>  
                                </div>
                             </div>
                                <button class="no_display" type="button" onclick="update_lead()">Update Lead</button>