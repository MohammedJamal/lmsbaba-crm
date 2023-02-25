<?php if(count($rows['menu'])){ ?>
    
    <?php foreach($rows['menu'] AS $k=>$v){ ?>
        <tr>
            <td class="text-left"><?php echo $v; ?></td>
            <td><input type="text" class="form-control menu_label" name="menu_label[<?php echo $k; ?>]" id="" placeholder="" value="<?php echo ($menu_alies_rows['menu']->$k)?$menu_alies_rows['menu']->$k:$v; ?>" maxlength="255"></td>
            
        </tr>
    <?php } ?>
    <tr><td colspan="2"><a href="javascript:void(0)" class="btn btn-success pull-right" id="menu_label_submit_confirm">Save</a></td></tr>
    
<?php } ?>