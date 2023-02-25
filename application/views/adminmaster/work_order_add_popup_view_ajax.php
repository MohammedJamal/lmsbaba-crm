<form id="workorderForm" name="workorderForm">
    <input type="hidden" name="mode" value="N">
<div class="row">
    <div class="col-md-12">

        <div class="form-group">
            <div class="col-md-6">
                <label class="full-label">Account Type</label>
                <div class="col-full">
                    
                <select class="form-control" id="account_type_id" name="account_type_id">
                    <?php if(count($account_type)){ ?>
                        <?php foreach($account_type AS $type){ ?>
                        <option value="<?php echo $type['id']; ?>" <?php if($client_row->account_type_id==$type['id']){echo'selected';} ?>><?php echo $type['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>

                </div>
            </div>

            <div class="col-md-6">
                <label class="full-label">No. of Users</label>
                <div class="col-full">
                <select class="form-control" id="no_of_user" name="no_of_user">  
                    <?php for($i=1;$i<=100;$i++){ ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> user<?php echo ($i>1)?'s':''; ?></option>
                    <?php } ?>                                    
                </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="full-label">LMSbaba URL</label>
                <div class="col-full"><input type="text" class="form-control" name="domain_name" id="domain_name" placeholder="LMSbaba URL" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Deal Value (Without GST)</label>
                <div class="col-full"><input type="text" class="form-control" name="deal_value" id="deal_value" placeholder="Deal Value (Without GST)" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Payment Terms</label>
                <div class="col-full"><input type="text" class="form-control" name="payment_terms" id="payment_terms" placeholder="Payment Terms" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Lead ID</label>
                <div class="col-full"><input type="text" class="form-control" name="lead_id" id="lead_id" placeholder="Lead ID" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Subscription Period (In Months)</label>
                <div class="col-full"><input type="text" class="form-control" name="subscription_period" id="subscription_period" placeholder="Subscription Period (In Months)" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Company Name</label>
                <div class="col-full"><input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company Name" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Company Address</label>
                <div class="col-full"><input type="text" class="form-control" name="company_address" id="company_address" placeholder="Company Address" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Country</label>
                <div class="col-full">
                    
                <select class="custom-select form-control" name="country_id" id="country_id" onchange="GetStateList_maindb(this.value)">
                  <option value="">Select</option>
                  <?php foreach($country_list as $country_data)
                  {
                    ?>
                    <option value="<?php echo $country_data->id;?>" <?php if($country_id==$country_data->id){echo'selected';} ?>><?php echo $country_data->name;?></option>
                    <?php
                  }
                  ?>
                  
                </select>

                </div>
            </div>

            <div class="col-md-6">
                <label class="full-label">State</label>
                <div class="col-full">
                    
                <select class="custom-select form-control" name="state_id" id="state_id" onchange="GetCityList_maindb(this.value)">
                  <option value="">Select</option>
                    <?php foreach($state_list as $state_data)
                    {
                      ?>
                      <option value="<?php echo $state_data->id;?>" <?php if($state_id==$state_data->id){?> selected <?php } ?>><?php echo $state_data->name;?></option>
                      <?php
                    }
                    ?>
                </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="full-label">City</label>
                <div class="col-full">
                    
                <select class="custom-select form-control" name="city_id" id="city_id">
                  <option value="">Select</option>
                    <?php foreach($city_list as $city_data)
                    {
                      ?>
                      <option value="<?php echo $city_data->id;?>" <?php if($city_id==$city_data->id){?> selected <?php } ?>><?php echo $city_data->name;?></option>
                      <?php
                    }
                    ?>
                </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Pin</label>
                <div class="col-full"><input type="text" class="form-control" name="pin_code" id="pin_code" placeholder="Pin" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">GST</label>
                <div class="col-full"><input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GST" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Owner Name</label>
                <div class="col-full"><input type="text" class="form-control" name="owner_name" id="owner_name" placeholder="Owner Name" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Contact Person</label>
                <div class="col-full"><input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Contact Person" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Email</label>
                <div class="col-full"><input type="text" class="form-control" name="email_id" id="email_id" placeholder="Email" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Mobile</label>
                <div class="col-full"><input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Mobile" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Website</label>
                <div class="col-full"><input type="text" class="form-control" name="website" id="website" placeholder="Website" value="" /></div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <br>
                    <h4 class="modal-title text-primary">Service Details</h4>
                    <hr>
                </div>
                <div class="col-md-6">
                    <label class="full-label">Service Type</label>
                    <div class="col-full">
                    <select class="form-control" id="service_id" name="service_id">
                        <?php if(count($service_list)){ ?>
                            <?php foreach($service_list AS $service){ ?>
                            <option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="full-label">Service Title</label>
                    <div class="col-full"><input type="text" class="form-control" name="display_name" id="display_name" placeholder="Service Title" value="" /></div>
                </div>
                <div class="col-md-4">
                    <label class="full-label">Start Date</label>
                    <div class="col-full"><input type="date" class="form-control" name="start_date" id="start_date" placeholder="Select Start Date" value="" /></div>
                </div>
                <div class="col-md-4">
                    <label class="full-label">End Date</label>
                    <div class="col-full"><input type="date" class="form-control" name="end_date" id="end_date" placeholder="Select End Date" value="" /></div>
                </div>
                <div class="col-md-4">
                    <label class="full-label">Expiry Date</label>
                    <div class="col-full"><input type="date" class="form-control" name="expiry_date" id="expiry_date" placeholder="Select Expiry Date" value="" /></div>
                </div>
            </div>
            
        </div>
            
        <p id="reset_form" class="file margin_top">
            <input type="button" value="" class="" id="add_workorder_confirm" />
            <label for="file" class="serach-btn">Save</label>
            <b class="text-danger" id="addworkorder_errmsg_html"></b>
        </p>

    </div>
</div>
</form>