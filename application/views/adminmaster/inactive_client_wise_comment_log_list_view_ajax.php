    <style>
        .comment_part{
            height:400px;
            overflow-y: scroll;
        }
    </style>

    <?php if(count($comment_list)){ ?>
    <div class="form-group">
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
