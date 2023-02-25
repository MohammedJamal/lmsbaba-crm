<option value="">Select Form</option>
<?php 
if(count($rows)){
    if($is_fb_connected=='Y'){
        foreach($rows AS $row){
            ?>
            <option value="<?php echo $fb_page_id.'~~'.$row->id.'~~'.$row->name; ?>" data-id="<?php echo $row->id; ?>" data-page_id="<?php echo $fb_page_id; ?>" data-page_access_token="<?php echo $fb_page_access_token; ?>"><?php echo $row->name.' ( '.$row->id.' )'; ?></option>
            <?php
        }
    }
    else{
        foreach($rows AS $row){
            ?>
            <option <?php echo ($edit_row['fb_form_id']==$row['form_id'])?'SELECTED':''; ?> value="<?php echo $fb_page_id.'~~'.$row['form_id'].'~~'.$row['form_name']; ?>" data-id="<?php echo $row['form_id']; ?>" data-page_id="<?php echo $fb_page_id; ?>" data-page_access_token="<?php echo $fb_page_access_token; ?>"><?php echo $row['form_name'].' ( '.$row['form_id'].' )'; ?></option>
            <?php
        }
    }    
}
?>