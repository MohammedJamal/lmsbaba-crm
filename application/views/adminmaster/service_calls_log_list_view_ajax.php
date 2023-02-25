    <style>
        .comment_part{
            height:400px;
            overflow-y: scroll;
        }
    </style>

    <?php if(count($comment_list)){ ?>
    <div class="form-group">
        <p>
            
    <b>Company:</b> <?php echo $client_row[0]['company']; ?><br>
    <b>LMS ID:</b> <?php echo $client_row[0]['client_id']; ?><br>
    <b>Last Login:</b> <?php echo date_db_format_to_display_format($client_row[0]['last_login']); ?><br>
    <!-- <b>No. of Users Tagged:</b> <a href="JavaScript:void(0)" title="View User List" class="tagged_user_list text-primary" data-cid="<?php echo $client_row[0]['client_id']; ?>"><?php echo $client_row[0]['total_user_count']; ?></a><br> 
    <b>Module Name:</b> <?php echo $client_row[0]['module_name']; ?><br>
    <b>Service Name:</b> <?php echo $client_row[0]['service_name']; ?>-->
    <hr>
        </p>
        <div class="comment_part">
        <?php foreach($comment_list AS $comment){ ?>
        <p>
            <b><?php echo $comment['type_name']; ?></b><br>
            Updated By: <?php echo $comment['updated_by']; ?><br>
            Call Scheduled Date: <?php echo datetime_db_format_to_display_format_ampm($comment['scheduled_call_datetime']); ?><br>
            Call Done Date: <?php echo datetime_db_format_to_display_format_ampm($comment['actual_call_done_datetime']); ?><br>
            
            <?php
            if(trim($comment['type_name'])!='') { ?> Update Type: <?php echo $comment['type_name']; ?><br> <?php } ?>
            Comment: <?php echo $comment['comment']; ?>
        </p>
        <hr>
        <?php } ?>
        </div>
    </div>
    <?php } ?>
