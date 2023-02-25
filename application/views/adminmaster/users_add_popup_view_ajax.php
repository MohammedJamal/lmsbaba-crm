<div class="row">
    <div class="col-md-8">

        <div class="form-group">
            <label class="full-label">Select Manager</label>
            <div class="col-full">
                <select class="form-control" id="manager_id">
                <option value="0" >===Select One===</option>  
                <?php if(count($user_list)){
                    foreach($user_list AS $user){ ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?> (<?php echo $user['email']; ?>)</option>
                <?php }
                } ?>
                </select>

            </div>
        </div>

        <div class="form-group">
            <label class="full-label">User Name</label>
            <div class="col-full"><input type="text" class="form-control" id="user_name" placeholder="User Name" value="" /></div>
        </div>

        <div class="form-group">
            <label class="full-label">User E-mail ID</label>
            <div class="col-full"><input type="email" class="form-control" id="email_id" placeholder="E-mail ID" value="" /></div>
        </div>

        <div class="form-group">
            <label class="full-label">User Mobile Number</label>
            <div class="col-full"><input type="text" class="form-control" id="mobile" placeholder="Mobile Number" value="" maxlength="10" /></div>
        </div>

        <div class="form-group">
            <label class="full-label">User Login Password</label>
            <div class="col-full"><input type="text" class="form-control" id="log_password" placeholder="Login Password" value=""/></div>
        </div>
            
        <p id="reset_form" class="file margin_top">
            <input type="button" value="" class="" id="add_user_confirm" />
            <label for="file" class="serach-btn">Save</label>
            <b class="text-danger" id="adduser_errmsg_html"></b>
        </p>


    </div>
</div>