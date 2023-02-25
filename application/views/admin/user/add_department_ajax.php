<?php  
if(count($row))
{
    $category_name=$row['category_name'];    
    $edit_id=$row['id'];
}
else
{
    $category_name='';
    $edit_id='';
}
?>

<div class="form-group">
    <div class="col-lg-4">
        <label class="control-label" for="category_name"><b>Name</b> <span class="text-danger">*</span></label>
    </div>
    <div class="col-lg-8">
        <input type="text" placeholder="Department Name" class="form-control" id="category_name" name="category_name" required="" value="<?php echo $category_name; ?>">
        <div class="error text-error" id="department_name_error"></div>
    </div>
</div>
<div class="form-group" id="parent_select_div_html">
    <div class="col-lg-4">
        <label class="control-label" for="parent_id"><b>Select Parent Department</b> </label>
    </div>
    <div class="col-lg-8"> <?php echo $tree_view_dropdown; ?> </div>
</div>


<?php if(is_method_available('user','add_department')==TRUE && $edit_id==''){ ?>
<div class="form-group">
    <div class="col-lg-3"></div>
    <div class="col-lg-9">
        <button data-toggle="tooltip" title="" class="btn btn-right btn-primary btn-round-shadow border_blue pull-right" data-original-title="Save" id="add_department_submit">Submit</button>
    </div>
</div>
<?php } ?>
<?php if(is_method_available('user','edit_department')==TRUE && $edit_id!=''){ ?>
<div class="form-group">
    <div class="col-lg-3"></div>
    <div class="col-lg-9">
        <button data-toggle="tooltip" title="" class="btn btn-right btn-primary btn-round-shadow border_blue pull-right" data-original-title="Save" id="add_department_submit">Submit</button>
    </div>
</div>
<?php } ?>