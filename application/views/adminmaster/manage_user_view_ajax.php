<?php
if(count($rows)>0)
{
    foreach($rows as $row) 
    { 
        $role_tooltip_text='Manage permission of '.$row['name'];
        if($row['manager_id']==0)
        {
            $edit_tooltip_text='Edit the super admin';            
        }
        else
        {
            $edit_tooltip_text='Edit the employee';            
        }
?>
    <tr>
        <td class="text-left"><?php echo $row['id']; ?></td>
        <td class="text-left"><?php echo ($row['name'])?$row['name']:'N/A'; ?></td>
        <td class="text-left"><?php echo $row['designation_name']; ?></td>
        <td class="text-left"><?php echo $row['dept_name']; ?></td>
        <td class="text-left"><?php echo $row['functional_area_name']; ?></td>
        <td class="text-left"><?php echo ($row['manager_name'])?$row['manager_name']:'N/A'; ?></td>
        <td class="text-left auto-show hide"><?php echo $row['email']; ?></td>
        <td class="text-center">
          <div class="action-holder">
            <!-- <a href="JavaScript:void(0);" class="view_employee_details" data-id="<?php echo $row['id']; ?>" data-client_id="<?php echo $client_id; ?>" data-managerid="" data-original-title="Click to view details" data-toggle="tooltip" data-placement="top"><i class="fa fa-search-plus"></i></a> -->
            <a href="<?php echo adminportal_url(); ?>client/manage_user_permission/<?php echo $client_id; ?>/<?php echo $row['id']; ?>" class="view_employee_details" data-id="<?php echo $row['id']; ?>" data-client_id="<?php echo $client_id; ?>" data-managerid="" data-original-title="Click to view details" data-toggle="tooltip" data-placement="top"><i class="fa fa-list" aria-hidden="true"></i></a>
            
          </div>
        </td>
    </tr>
<?php
    }
}
?>

