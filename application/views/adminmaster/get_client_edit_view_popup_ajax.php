<form id="clientForm" name="clientForm">
    <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id; ?>" />
    <div class="form-group">
        <label for="account_type_id">Type</label>
        <select class="form-control" id="account_type_id" name="account_type_id">
            <?php if(count($account_type)){ ?>
                <?php foreach($account_type AS $type){ ?>
                <option value="<?php echo $type['id']; ?>" <?php if($client_row->account_type_id==$type['id']){echo'SELECTED';} ?>><?php echo $type['name']; ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="c_name">Company Name</label>
        <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Enter Company Name..." value="<?php echo $client_row->name; ?>">
    </div>
    <div class="form-group">
        <label for="c_name">Owner Name</label>
        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter Owner Name..." value="<?php echo $client_row->company_name; ?>">
    </div>
    <div class="form-group">
        <label for="c_name">Contact Person Name</label>
        <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Enter Contact Person Name..." value="<?php echo $client_row->contact_person; ?>">
    </div>
    <div class="form-group">
        <label for="c_is_active">Client Status</label>
        <select class="form-control" id="c_is_active" name="c_is_active">            
                <option value="Y" <?php if($client_row->is_active=='Y'){echo'SELECTED';} ?>>Enable</option>
                <option value="N" <?php if($client_row->is_active=='N'){echo'SELECTED';} ?>>Disable</option>
        </select>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary" id="edit_company_confirm">Save</button>
    </div>
</form>