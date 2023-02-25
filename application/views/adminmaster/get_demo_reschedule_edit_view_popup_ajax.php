<script type="text/javascript">

    $(document).ready(function(){
        $('#timepickerstarttime').mdtimepicker({ minTime:'now'}); //Initializes the time picker
        //$('#timepickerendtime').mdtimepicker(); //Initializes the time picker

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
                    <input type="date" readonly class="form-control" name="done_demo_date" id="done_demo_date"  value="<?php echo date('d-m-Y',strtotime($demo_row['0']['demo_date'])); ?>" />
                </div>
            </div>
            
            <div class="col-md-6">
                <label class="full-label">Confirm Time</label>
                <div class="col-full">
                <input type="text" name="confirm_time"   id="timepickerstarttime" style="background-color:none !important;" value="<?php echo $demo_row['0']['demo_time']; ?>" class="form-control"/>
                </div>
            </div>
            
        </div>
        <div class="form-group">
        <div class="col-md-6">
                <div class="col-full">
        <button type="button" class="btn btn-primary" id="edit_reschedule_demo">Save</button>
        </div>
            </div>
    </div>
        
    </div>
    </div>
</form>
<link href="<?php echo assets_url()?>css/mdtimepicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo assets_url()?>js/mdtimepicker.js"></script>