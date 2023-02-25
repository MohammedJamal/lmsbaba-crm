
<ul>
<?php if(count($rows)){ ?>  
<?php foreach($rows AS $row){ ?>  
<li>
    <div class="topC-holder">
        <label class="check-box-sec full-check radio">
            <input type="radio" value="<?php echo $row->comment; ?>" name="pre_define_comment" id="pre_define_comment_<?php echo $row->id; ?>" class="comments_checkbox" data-id="<?php echo $row->id; ?>">
            <span class="checkmark"></span>
            <div class="cname"><?php echo $row->title; ?></div>
            <small id="comment_error_<?php echo $row->id; ?>" class="text-danger"></small>
        </label>
        <div class="action-block">
            <a href="JavaScript:void(0);" class="edit-comm" data-id="<?php echo $row->id; ?>" id="edit_comm_<?php echo $row->id; ?>"><i class="fa fa-pencil"  aria-hidden="true"></i></a>
            <a href="JavaScript:void(0);" class="del-comm" data-id="<?php echo $row->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="comments_details" id="comments_details_<?php echo $row->id; ?>"> 
    	<textarea disabled="" name="comment_<?php echo $row->id; ?>" id="comment_<?php echo $row->id; ?>"><?php echo $row->comment; ?></textarea>
    </div>
</li> 

<?php } ?>
<?php }else{ ?>
    <li>No comment found</li> 
<?php } ?>
</ul> 
