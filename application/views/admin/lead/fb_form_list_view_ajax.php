<div class="user_comment" id="">
    <div class="user_header">
        <div class="pull-right">
            <a href="JavaScript:void(0);" class="cbtn add-new-com-btn fb_close" data-id="" data-leadid=""><i class="fa fa-close" aria-hidden="true"></i> Close</a>            
        </div>
    </div>    
    <div id="lead_scroller" class="default-scoller" style="margin:15px;padding:10px !important;">
        <?php if(count($rows)){ ?>
            <?php foreach($rows AS $row){ ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="fb_form_id" id="fb_form_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" <?php echo ($row['is_default']=='Y')?'CHECKED':''; ?>>
                <label class="form-check-label" for="">
                    <?php echo $row['form_name']; ?>
                </label>
            </div>
            <?php } ?>
        <?php }else{ ?>
            <ul><li class="unread">No Note Found!</li></ul>        
        <?php } ?>
    </div>  
    <?php if(count($rows)){ ?>
    <div class="select-action">
        <button type="button" class="custom_blu btn btn-primary" id="update_fb_default_form_confirm" data-id="" data-leadid="">Get Lead</button>
    </div>  
    <?php } ?>
</div>