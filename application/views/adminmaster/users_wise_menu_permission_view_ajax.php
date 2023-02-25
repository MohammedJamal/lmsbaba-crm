<div class="container">
  <!-- <h2>Users List </h2>
  <p></p>             -->
  
<?php 
    // echo'<pre>';  
    // print_r($menu_list_data);
    
    // //print_r($_SESSION['adminportal_session_data']);
    
    // echo'</pre>';
?>
<?php if(count($menu_list_data)){ ?>
    <form action="<?=adminportal_url()?>users/update_user_permission" method="post">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <ul class="" style="padding-left:18px;">
    <?php foreach($menu_list_data AS $menu){ ?>                                                            
        <li>
            <div class="checkbox checkbox-warning">
            <input type="checkbox" class="styled parent_access" data-id="<?php echo $menu['menu_list']['menu_id']; ?>" name="menu_id[]" value="<?php echo $menu['menu_list']['menu_id']; ?>" <?php if(in_array($menu['menu_list']['menu_id'],$menu_permission_id)) echo'checked'; ?>>
            <label for=""> &nbsp; <b><?php echo $menu['menu_list']['menu_name']; ?></b></label>
            </div>
            <?php if($menu['menu_wise_element_list']){ ?>
                <ul class="" style="padding-left:26px;">
                <?php foreach($menu['menu_wise_element_list'] AS $element){ ?>                                   
                    <li>
                        <div class="checkbox checkbox-warning">
                        <input type="checkbox" class="styled child_access_<?php echo $menu['menu_list']['menu_id']; ?>" name="element_id[]" value="<?php echo $element['element_id']; ?>" <?php if(in_array($element['element_id'],$element_permission_id)) echo'checked'; ?>
                        
                        <?php if(!in_array($menu['menu_list']['menu_id'],$menu_permission_id)) echo'disabled'; ?>
                        >
                        <label for=""> <?php echo $element['text_name']; ?></label>
                        </div>
                    </li>
                <?php } ?>
                </ul>
            <?php } ?>

        </li>
    <?php } ?>
    </ul>
                                                    
    <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12">                                        
            <button style="float: left;" type="submit" class="btn btn-success">Update Permission </button>                                        
        </div>
    </div>
    </form>

    <?php } else { ?>
        No Menu Found!
<?php } ?>
</div>

