<form id="frmGroupWiseCatAdd">
    <div class="row">
        <div class="box-details">
            
            <?php if($view_type=='c'){ ?>
                <div class="form-group row">
                    <div class="col-md-2 text-right"><b>Group:</b></div>
                    <div class="col-md-10 form_last">                    
                        <select id="parent_id" name="parent_id" class="form-control">
                            <option value="">-- Select Group --</option>
                            <?php
                            if(count($group_option_list))
                            {
                                foreach($group_option_list as $cat)
                                {
                            ?>
                                <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <div class="error_div text-danger" id="parent_id_error"></div>
                    </div>
                </div>

                
            <?php }else{ ?>
                <input type="hidden" name="parent_id" id="parent_id" value="0">
            <?php } ?>
            <div class="form-group row">
                <div class="col-md-2 text-right"><b>Name:</b></div>
                <div class="col-md-10">                    
                    <input type="text" class="form-control" id="cat_name" name="cat_name" value="" />
                    <div class="error_div text-danger" id="cat_name_error"></div>
                </div>
            </div>
        </div>
    </div>
</form>