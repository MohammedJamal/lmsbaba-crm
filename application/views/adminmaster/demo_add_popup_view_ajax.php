<script type="text/javascript">

    $(document).ready(function(){
        $(".select2").select2();
        $('#timepicker').mdtimepicker(); //Initializes the time picker
    
    

}); 
</script>

<form id="workorderForm" name="workorderForm">
<div class="row" >
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-6">
                <label class="full-label">Lead Id</label>
                <div class="col-full"><input type="text" class="form-control" name="lead_id" id="lead_id" placeholder="Lead Id" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Company Name</label>
                <div class="col-full"><input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company Name" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Contact Person</label>
                <div class="col-full"><input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Contact Person" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Email ID</label>
                <div class="col-full"><input type="text" class="form-control" name="email_id" id="email_id" placeholder="Email ID" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Mobile</label>
                <div class="col-full"><input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="" /></div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Demo Date</label>
                <div class="col-full">
                    <input type="date" class="form-control" name="demo_date" id="demo_date" placeholder="Select Demo Date" value="" />
                    <!--<input type="date" class="form-control" name="start_date" id="start_date" placeholder="Select Start Date" value="" />-->
                </div>
            </div>

            <div class="col-md-6">
                <label class="full-label">Demo Time</label>
                <div class="col-full">
                
                <input type="text" name="demo_time"   id="timepicker" style="background-color:none !important;" class="form-control"/>
                
                </div>
            </div>

            <div class="col-md-6">
            <label class="full-label">Sales Person</label>
            <div class="col-full">
                <select class="form-control" id="sales_person" name="sales_person">
                    <option value="">Select</option>
                        <?php if(count($get_users_list)){ ?>
                            <?php foreach($get_users_list AS $users){ ?>
                            <option value="<?php echo $users['id']; ?>"><?php echo $users['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                </select>
            </div>
    </div>

    <div class="col-md-6">
                <label class="full-label">Location</label>
                <div class="col-full">
                <select class="form-control select2" id="location" name="location" >
                <option value="">Select</option>
                  <?php foreach($city_list as $city_data)
                    {
                      ?>
                      <option value="<?php echo $city_data['id'];?>" ><?php echo $city_data['name'];?></option>
                      <?php
                    }
                    ?>
                </select>
                </div>
            </div>    


            <div class="col-md-6">
        <label class="full-label">Lead Generation Platforms</label>
            <div class="col-full">
                <?php if(count($lead_generation_list)){ ?>
                    <?php foreach($lead_generation_list AS $lead_generation){ ?>
                        <input type="checkbox"  name="lead_generation_platform[]" value="<?php echo $lead_generation['id']; ?>"> 
                         <label for="<?php echo $lead_generation['lead_generation_platform']; ?>"><?php echo $lead_generation['lead_generation_platform']; ?>:</label>
                    <?php } ?>
                <?php } ?>
            </div>
    </div>
    <div class="col-md-12">
        <label class="full-label">Comment</label>
            <div class="col-full">
                <textarea style="height:60px;" class="form-control" placeholder="Enter your Comments..." id="schedule_comment" name="schedule_comment" rows="3" ></textarea>
            </div>
    </div>
     </div>
     <input type="hidden" name="mode" value="N">
        <p id="reset_form" class="file margin_top">
            <input type="button" value="" class="" id="add_workorder_confirm" />
            <label for="file" class="serach-btn">Save</label>
            <b class="text-danger" id="addworkorder_errmsg_html"></b>
        </p>

    </div>
</div>
</form>
 <link href="<?php echo assets_url()?>css/mdtimepicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo assets_url()?>js/mdtimepicker.js"></script>  








