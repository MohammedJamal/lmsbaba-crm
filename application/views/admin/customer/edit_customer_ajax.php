<div class="col-sm-6">
                                        <ul class="buyer_email_subject buyer_email_subject_border">
                                            <li><b>First Name :</b> <span class="display"><?php echo $cus_data->cus_first_name;?></span>
                                            <input type="text" class="form-control required no_display" name="first_name" id="first_name" placeholder="First Name" value="<?php echo $cus_data->cus_first_name;?>" required />
                                            </li>
                                            <li><b>Last Name :</b> <span class="display"><?php echo $cus_data->cus_last_name;?></span>
                                                <input type="text" class="form-control required no_display" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo $cus_data->cus_last_name;?>" required/>
                                            </li>
                                            
                                            <li><b>E-mail :</b> <span><?php echo $cus_data->cus_email;?></span></li>
                                             <?php
                                             if($cus_data->cus_alt_email)
                                             {
											
                                             ?>
                                            <li><b>Alternate E-mail :</b> <span><?php echo $cus_data->cus_alt_email;?></span></li>
                                            <?php
                                             	
											 }
											 ?>
                                            <li><b>Mobile :</b> <span class="display"><?php echo $cus_data->cus_office_phone;?></span> <input type="text" class="form-control required no_display" name="mobile" id="mobile" placeholder="Office Phone" value="<?php echo $cus_data->cus_mobile;?>" required/></li>
                                            <li><b>Office Phone :</b> <span class="display"><?php echo $cus_data->cus_office_phone;?></span> <input type="text" class="form-control required no_display" name="office_phone" id="office_phone" placeholder="Office Phone" value="<?php echo $cus_data->cus_office_phone;?>" required/></li>
                                            
                                            <li><b>Address :</b> <span class="display"><?php echo $cus_data->cus_address;?></span><input type="text" class="form-control required no_display" name="address" id="address" placeholder="Address" value="<?php echo $cus_data->cus_address;?>" required/></li>
                                            <li><b>City :</b> <span class="display"><?php echo $cus_data->cus_city;?></span> 
                   
                       <select class="custom-select required no_display" name="city" id="city" required>
                            <option>Select</option>
                            <?php
                            foreach($city_list as $city_data)
                            {
                                ?>
                                <option value="<?php echo $city_data->id?>" <?php if($city_data->id==$cus_data->city){ ?> selected="selected" <?php } ?>><?php echo $city_data->name;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    
                    </li>
                    <li><b>State :</b> <span class="display"><?php echo $cus_data->cus_state;?></span> 
                                            <select class="custom-select no_display" name="state" id="state" required onchange="GetCityList(this.value)">
                            <option>Select</option>
                            <?php
                            foreach($state_list as $state_data)
                            {
                                ?>
                                <option value="<?php echo $state_data->id?>" <?php if($state_data->id==$cus_data->state){ ?> selected="selected" <?php } ?>><?php echo $state_data->name;?></option>
                                <?php
                            }
                            ?>
                        </select>
                        </li>
                                            <li><b>ZIP :</b> <span class="display"><?php echo $cus_data->cus_zip;?></span> 				<input type="text" class="form-control required no_display" name="zip" id="zip" placeholder="ZIP" value="<?php echo $cus_data->cus_zip;?>" required/></li>
                                            <li><b>Country :</b> <span class="display"><?php echo $cus_data->cus_country;?></span> 
                                            <select class="custom-select no_display" name="country_id" id="country_id" onchange="GetStateList(this.value)" >
                        <option>Select</option>
                        <?php
                        foreach($country_list as $country_data)
                        {
                            ?>
                            <option value="<?php echo $country_data->id; ?>"<?php if($country_data->id==$cus_data->country_id){ ?> selected="selected" <?php } ?>><?php echo $country_data->name;?></option>
                            <?php
                        }
                        ?>
                    </select></li>
                                            
                </ul>
            </div>
            
            <div class="col-sm-6">
                <ul class="buyer_email_subject buyer_email_subject_border">
                        
                    <li><b>Company Name :</b> <span class="display"><?php echo $cus_data->cus_company_name;?></span> <input type="text" class="form-control required no_display" name="company_name" id="company_name" placeholder="Company Name" value="<?php echo $cus_data->cus_company_name;?>" required/></li>
                    <li><b>Website :</b> <span class="display"><?php echo $cus_data->cus_website;?></span><input type="text" class="form-control required no_display" name="website" id="website" placeholder="Website" value="<?php echo $cus_data->cus_website;?>" required/></li>
                    
                    
                    <li><b>Mobile :</b> <span class="display"><?php echo $cus_data->cus_mobile;?></span>
                    <input type="text" class="form-control required no_display" name="mobile" id="mobile" placeholder="Mobile" onkeyup="getsecondoption()" onblur="getsecondoption()"  value="<?php echo $cus_data->cus_mobile;?>" required/>
                    </li>
                    
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
                                            
                                          <!--  <li><b>Title :</b> <span><?php echo $cus_data->title;?></span>
                                            <input type="hidden" class="form-control " name="title" id="title" value="<?php echo $cus_data->title;?>" />
                                            </li>-->
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
                                            
                                            <li><div class="edit_icon">
                                            <a href="javascript:;" class="display" onclick="open_form()">Edit Contact Details</a>                                 		<a href="javascript:;" class="no_display"  onclick="update_customer()">Update Contact Details</a>                           
                                        </div></li>
                                            
                                        </ul>
                                        <div id="second_option" style="display: none;"><img src="<?=base_url();?>images/fetch.gif" alt="" height="150" width="200" /></div>
                                    </div>
                                    
                                    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $cus_data->customer_id?>"/>
                                    
                                    
                                    
                                    <div class="col-sm-11">
                                        <div class="original_requirement">
                                            <p>Original Requirement</p>
                                            <!--<textarea class="form-control no_display" name="description" rows="3" id="description" required><?php echo $cus_data->description;?></textarea> -->
                                            <span class="display"><?php echo $cus_data->description;?></span>
                                        </div>  
                                    </div>