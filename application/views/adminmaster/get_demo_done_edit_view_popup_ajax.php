<script type="text/javascript">

    $(document).ready(function(){
        $('#timepickerstarttime').mdtimepicker(); //Initializes the time picker
        $('#timepickerendtime').mdtimepicker(); //Initializes the time picker

}); 
</script>

<form id="clientForm" name="clientForm">
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lid; ?>" />
    <div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-6">
                <label class="full-label">Demo Date</label>
                <div class="col-full">
                    <input type="date" class="form-control" name="done_demo_date" id="done_demo_date" placeholder="Enter Demo Date" value="" />
                    <!--<input type="date" class="form-control" name="start_date" id="start_date" placeholder="Select Start Date" value="" />-->
                </div>
            </div>
            
            <div class="col-md-6">
                <label class="full-label">Start Time</label>
                <div class="col-full">
                <input type="text" name="done_start_time"   id="timepickerstarttime" style="background-color:none !important;" class="form-control"/>
                </div>
            </div>
            <div class="col-md-6">
                <label class="full-label">End Time</label>
                <div class="col-full">
                <input type="text" name="done_end_time"   id="timepickerendtime" style="background-color:none !important;" class="form-control"/>
                </div>
            </div>
            <div class="col-md-6">
                <label class="full-label">Next Followup Date</label>
                <div class="col-full">
                    <input type="date" class="form-control" name="next_followup_date" id="next_followup_date" placeholder="Next Followup  Date" value="" />
                </div>
            </div>
            <div class="col-md-6">
                <label class="full-label">Quotation Sent</label>
                <div class="col-full">
                <input type="radio" id="quotation_sent" name="quotation_sent" value="Y"> <label for="html">Yes</label> &nbsp; &nbsp;
                    <input type="radio" id="quotation_sent" name="quotation_sent" value="N"> <label for="html">No</label> &nbsp; &nbsp;
                </div>
            </div>
            <div class="col-md-6">
                <label class="full-label">Prospect</label>
                <div class="col-full">
                    <input type="radio" id="done_prospect" name="done_prospect" value="Y"> <label for="html">Yes</label> &nbsp; &nbsp;
                    <input type="radio" id="done_prospect" name="done_prospect" value="N"> <label for="html">No</label> &nbsp; &nbsp;
                    <input type="radio" id="done_prospect" name="done_prospect" value="M"> <label for="html">May Be</label> &nbsp; &nbsp;
                </div>
            </div>
            
            <div class="col-md-6">
                <label class="full-label">User Required</label>
                <div class="col-full">
                <input type="number" class="form-control" name="user_required" id="user_required" placeholder="User Required" value="" />
                </div>
            </div>
            <div class="col-md-12">
                <label class="full-label">Comment</label>
                <div class="col-full">
                <textarea style="height:120px;" class="form-control" placeholder="Enter your Comments..." id="done_comment" name="done_comment" rows="5" ></textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
        <button type="button" class="btn btn-primary" id="edit_done_demo">Save</button>
        </div>
        
    </div>
    </div>
</form>
<link href="<?php echo assets_url()?>css/mdtimepicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo assets_url()?>js/mdtimepicker.js"></script>