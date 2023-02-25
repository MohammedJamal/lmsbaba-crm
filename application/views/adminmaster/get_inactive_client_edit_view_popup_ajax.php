<form id="clientForm" name="clientForm">
    <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id; ?>" />

    <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id; ?>" />
    <input type="hidden" name="call_type_id" id="call_type_id" value="<?php echo $call_type_id; ?>" />
    <input type="hidden" name="call_type_name" id="call_type_name" value="Service Calls (<?php echo $call_type_name; ?>)" />
    <input type="hidden" name="service_call_id" id="service_call_id" value="<?php echo $service_call_id; ?>" />

    <div class="form-group">
    <b>Company:</b> <?php echo $client_row[0]['company']; ?><br>
    <b>LMS ID:</b> <?php echo $client_row[0]['client_id']; ?><br>
    <b>Last Login:</b> <?php echo date_db_format_to_display_format($client_row[0]['last_login']); ?><br>
    <b>No. of Users Tagged:</b> <a href="JavaScript:void(0)" title="View User List" class="tagged_user_list text-primary" data-cid="<?php echo $client_row[0]['client_id']; ?>"><?php echo $client_row[0]['total_user_count']; ?></a>
        
        <hr>
    </div>
    
    <div class="form-group">
        <label for="update_type_id"><b class="text-primary">Update Type:<span class="text-danger">*</span></b> &nbsp; </label>
            <?php if(count($update_type)){ ?>
                <?php foreach($update_type AS $type){ ?>
                    <input type="radio" id="update_type_id" name="update_type_id" value="<?php echo $type['id']; ?>"> <label for="html"><?php echo $type['type_name']; ?> </label> &nbsp; &nbsp;
                <?php } ?>
            <?php } ?> 
    </div>
    <div class="form-group">
        <label for="call_done"><b class="text-primary">Please Check If Done this <?php echo $call_type_name; ?>: </b> &nbsp; </label>
        <input type="checkbox" id="call_done" name="call_done" value="1"> 
    </div>
    <div class="form-group" id="shownxt_flwdate">
        <label for="comment"><b class="text-primary">Next Followup Date:<span class="text-danger">*</span></b></label>
        <input type="text" id="followup_date" name="followup_date" class="form-control" value="">
    </div>
    <div class="form-group">
        <label for="comment"><b class="text-primary">Comments:<span class="text-danger">*</span></b></label>
        <textarea class="form-control" placeholder="Enter your Comments..." id="comment_text" name="comment_text" rows="5"></textarea>
    </div>

    <style>
        .form-control{
            height:auto;
        }
        .comment_part{
            height:300px;
            overflow-y: scroll;
        }
    </style>

    <div class="form-group">
        <button type="button" class="btn btn-primary" id="inactive_edit_comment_confirm">Save</button>
    </div>

    <?php if(count($comment_list)){ ?>
    <div class="form-group">
        <br><br>
        <b>Comments History:</b><br><br>
        <div class="comment_part">
        <?php foreach($comment_list AS $comment){ ?>
        <p>
            <b><?php echo $comment['activity_title']; ?></b><br>
            Updated By: <?php echo $comment['updated_by']; ?><br>
            Date: <?php echo datetime_db_format_to_display_format_ampm($comment['created_at']); ?><br>
            <?php if(trim($comment['followup_date'])!=''){ ?>
            Next Followup Date: <?php echo date_db_format_to_display_format($comment['followup_date']); ?><br>
            <?php
            }
            if(trim($comment['type_name'])!='') { ?> Update Type: <?php echo $comment['type_name']; ?><br> <?php } ?>
            Comment: <?php echo $comment['activity_text']; ?>
        </p>
        <hr>
        <?php } ?>
        </div>
    </div>
    <?php } ?>



</form>