<div class="user_comment" id="note_list_<?php echo $id;?>">
    <div class="user_header">
        <div class="pull-right">
            <a href="JavaScript:void(0);" class="cbtn add-new-com-btn note_close" data-id="<?php echo $id;?>" data-leadid="<?php echo $lead_id;?>"><i class="fa fa-close" aria-hidden="true"></i> Close</a>            
        </div>
    </div>
    
    <div id="lead_scroller" class="default-scoller">
        <?php echo ($note_list_html)?$note_list_html:'<ul><li class="unread">No Note Found!</li></ul>'; ?>        
    </div>
    <div class="select-action">		
        <button type="button" class="custom_blu btn btn-primary pull-right note_add_btn" data-id="<?php echo $id;?>" data-leadid="<?php echo $lead_id;?>" data-parentid="0" data-user_name="" data-note="">Add Note</button>
        
    </div>
</div>									
<div class="add-user_comment" id="note_add_<?php echo $id;?>">
    <div class="user_header">
        <div class="pull-right">
            <a href="JavaScript:void(0)" class="cbtn go-back note_back" data-id="<?php echo $id;?>" data-leadid="<?php echo $lead_id;?>"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
        </div>
    </div>
    <div class="form-group">
        <p id="parent_note" class="text-primary text-bold"></p>                  
    </div>

    <div class="form-group">
        <label>Note<span class="text-danger">*</span> <small class="text-danger" id="note"></small></label>
        <textarea class="form-control" name="note_text" id="note_text" style="white-space: pre-wrap;"></textarea> 
        <div id="note_error" class="text-danger"></div>                       
    </div>
    <div class="select-action">
        <button type="button" class="custom_blu btn btn-primary" id="add_note_confirm" data-id="<?php echo $id;?>" data-leadid="<?php echo $lead_id;?>">Save</button>
    </div>
</div>