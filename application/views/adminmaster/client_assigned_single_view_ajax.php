<?php 
// echo'<pre>';
// print_r($user_details);
// echo'</pre>';
?>

<div class="row">
    <div class="col-md-8">
        <input type="hidden" id="single_assigne_client_id" value="<?php echo $client_id; ?>">

        <div class="form-group">
            <label class="full-label">Select User</label>
            <div class="col-full">
            <select class="form-control" id="single_assigne_user_id">
                <option value="" >===Select Any User to Assign===</option>             
                <?php if(count($user_list)){
                    foreach($user_list AS $user){ ?>
                    <option value="<?php echo $user['id']; ?>" <?php if($user['id']==$assigned_user_id)echo'selected'; ?>><?php echo $user['name']; ?> (<?php echo $user['email']; ?>)</option>
                <?php }
                } ?>
            </select>

            </div>
        </div>
        
        <p id="reset_form" class="file margin_top">
            <input type="button" value="" class="" id="single_assigne_client_confirm" />
            <label for="file" class="serach-btn">Update</label>
            <b class="text-danger" id="single_assigne_client_errmsg_html"></b>
        </p>
    </div>
</div>