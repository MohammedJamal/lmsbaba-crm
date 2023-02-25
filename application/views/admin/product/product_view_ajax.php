<?php
if(count($rows)>0)
{
    foreach($rows as $row) 
    { 
?>
    <tr id="tr_<?php echo $row['id']; ?>">
        <td>
            <div class="checkbox checkbox-inline">
                <input type="checkbox" id="chk_delete_<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>" class="set_individual" >
                <label for="chk_delete_<?php echo $row['id']; ?>"><?php //echo $row['id']; ?></label>
            </div>
        </td>
        <td>
            <?php 
            $images_str=$row['file_name'];    
            $images_arr=explode(",", $images_str);  
            $image=$images_arr[0];
            if (file_exists('accounts/'.$this->session->userdata['admin_session_data']['lms_url'].'/product/thumb/'.$image) && $image!=''){
            ?>
                <img src="<?php echo base_url().'accounts/'.$this->session->userdata['admin_session_data']['lms_url'].'/product/thumb/'.$image; ?>" />
            <?php
            }else{
            ?>
                <img src="<?=base_url();?>images/no_photo.png"/>
            <?php
            }          
            ?>
        </td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['curr_code'].' - '.$row['price']; ?></td>
        <td><?php echo $row['unit']; ?></td>
        <td><?php echo $row['unit_type_name']; ?></td>   
        <?php /* ?>     
        <td>
            
            <a href="JavaScript:void(0);" class="btn-status-change <?php if($row['status']=='0'){echo 'badge badge-success';}else{echo 'badge badge-danger';} ?>" data-curstatus="<?php echo $row['status']; ?>" data-id="<?php echo $row['id']; ?>" id="status_<?php echo $row['id']; ?>">
            <?php echo ($row['status']=='Y')?'Enabled':'Disabled'; ?>
            </a>
        </td>   
        <?php */ ?>     
        <td class="actions">            
            <a href="<?php echo base_url(); ?>admin/product/edit/<?php echo $row['id']; ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Edit"><i class="fa fa-pencil"></i></a>        
        </td>
    </tr>
<?php 
    } 
}
else
{
    echo'<tr><td colspan="7">No Record Found..</td></tr>';
}
?>