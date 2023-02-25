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
    <div class="col-lg-3">
        <label class="control-label" for="category_name">Name <span class="text-danger">*</span></label>
    </div>
    <div class="col-lg-9">
        <input type="text" placeholder="Department Name" class="form-control" id="category_name" name="category_name" required="" value="<?php echo $category_name; ?>">
        <div class="error" id="department_name_error"></div>
    </div>
</div>
<div class="form-group" id="">
    <div class="col-lg-12" id="is_same_level_check_view"></div>
</div>

<div class="form-group" id="parent_select_div_html">
    <div class="col-lg-3">
        <label class="control-label" for="parent_id">Select Parent </label>
    </div>
    <div class="col-lg-9"> <?php echo $tree_view_dropdown; ?> </div>
</div>

<div class="form-group">
    <div class="col-lg-3"></div>
    <div class="col-lg-9">
        <button data-toggle="tooltip" title="" class="btn btn-primary pull-right" data-original-title="Save" id="add_department_submit">Save</button>
    </div>
</div>