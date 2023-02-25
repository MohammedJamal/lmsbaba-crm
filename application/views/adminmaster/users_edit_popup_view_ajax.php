<?php 
// echo'<pre>';
// print_r($user_details);
// echo'</pre>';
?>

<div class="row">
    <div class="col-md-8">
        <input type="hidden" id="edit_uid" value="<?php echo $user_details->id; ?>">

        <div class="form-group">
            <label class="full-label">Select Manager</label>
            <div class="col-full">
                <select class="form-control" id="manager_id">
                <option value="0" >===Select One===</option>  
                <?php
                if($user_details->id!=1){
                 if(count($user_list)){
                    foreach($user_list AS $user){ ?>
                    <option value="<?php echo $user['id']; ?>" <?php if($user_details->manager_id==$user['id']) echo'selected';?>><?php echo $user['name']; ?> (<?php echo $user['email']; ?>)</option>
                <?php }
                } 
                }?>
                </select>

            </div>
        </div>
        
        <div class="form-group">
            <label class="full-label">User Name</label>
            <div class="col-full"><input type="text" class="form-control" id="user_name" placeholder="User Name" value="<?php echo $user_details->name; ?>" /></div>
        </div>

        <div class="form-group">
            <label class="full-label">User E-mail ID</label>
            <div class="col-full"><input type="email" class="form-control" id="email_id" placeholder="E-mail ID" value="<?php echo $user_details->email; ?>" /></div>
        </div>

        <div class="form-group">
            <label class="full-label">User Mobile Number</label>
            <div class="col-full"><input type="text" class="form-control" id="mobile" placeholder="Mobile Number" value="<?php echo $user_details->mobile; ?>" maxlength="10" /></div>
        </div>

        <div class="form-group">
            <label class="full-label">User Login Password</label>
            <div class="col-full"><input type="text" class="form-control" id="log_password" placeholder="Enter new password if change login password" value=""/></div>
        </div>
            
        <p id="reset_form" class="file margin_top">
            <input type="button" value="" class="" id="edit_user_confirm" />
            <label for="file" class="serach-btn">Update</label>
            <b class="text-danger" id="edituser_errmsg_html"></b>
        </p>


    </div>
</div>