
<input type="hidden" name="fb_page_id" value="<?php echo $fb_page_id; ?>">
<input type="hidden" name="fb_page_access_token" value="<?php echo $fb_page_access_token; ?>">
<input type="hidden" name="fb_form_id" value="<?php echo $fb_form_id; ?>">
<input type="hidden" name="is_fb_connected" value="<?php echo $is_fb_connected; ?>">
<?php 
if(count($rows)){
    if($is_fb_connected=='Y'){
        $fields = $rows[0]->field_data;
        if(count($fields)){ ?>
            <table class="table table-bordered">
            <thead>
                <tr><th scope="col" colspan="2">Action</th></tr>
            </thead>
            <?php
            foreach($fields AS $field){ ?>        
                <tr>
                    <th scope="row"><input type="text" class="form-control" value="<?php echo $field->name; ?>" readonly="true" name="fb_field[]"></th>
                    <td>
                        <select class="form-control" id="" name="fb_system_field_keyword[]">
                            <option>Select a Veriable</option>
                            <?php if(count($field_keyword)){ ?>
                                <?php foreach($field_keyword AS $keyword){ ?>
                                    <option value="<?php echo $keyword['keyword']; ?>"><?php echo $keyword['display_name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <tr><td class="text-center" scope="col" colspan="2"><a href="javascript:void(0)" class="btn btn-success" id="confirm_fb_setting">Save</a></td></tr>
            </table>
        <?php
        }
    }
    else{
        ?>
            <table class="table table-bordered">
            <thead>
                <tr><th scope="col" colspan="2">Action</th></tr>
            </thead>
            <?php
            foreach($rows AS $field){ ?>        
                <tr>
                    <th scope="row"><input type="text" class="form-control" value="<?php echo $field['fb_field_name']; ?>" readonly="true" name="fb_field[]"></th>
                    <td>
                        <select class="form-control" id="" name="fb_system_field_keyword[]">
                            <option>Select a Veriable</option>
                            <?php if(count($field_keyword)){ ?>
                                <?php foreach($field_keyword AS $keyword){ ?>
                                    <option value="<?php echo $keyword['keyword']; ?>" <?php echo ($keyword['keyword']==$field['system_field_name_keyword'])?'SELECTED':''; ?>><?php echo $keyword['display_name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <tr><td class="text-center" scope="col" colspan="2"><a href="javascript:void(0)" class="btn btn-success" id="confirm_fb_setting">Save</a></td></tr>
            </table>
        <?php        
    }    
}
?>
