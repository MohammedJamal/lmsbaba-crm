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
        <td class="text-left auto-show hide"><?php echo ($row['employee_type'])?$row['employee_type']:'N/A'; ?></td>
        <td class="text-left auto-show hide"><?php if($row['branch']!='' || $row['cs_branch']!=''){ echo ($row['branch'])?$row['branch']:$row['cs_branch']; }else{echo'N/A';}?></td>
        <td class="text-left auto-show hide"><?php echo ($row['username'])?$row['username']:'N/A'; ?></td>
        <td class="text-center">
          <div class="action-holder">
            <a href="JavaScript:void(0);" class="view_employee_details" data-id="<?php echo $row['id']; ?>" data-managerid="" data-original-title="Click to view details" data-toggle="tooltip" data-placement="top"><i class="fa fa-search-plus"></i></a>
            <?php            
            if(strtolower($user_type)=='admin')
            {
                // ========= Status Link Start
                $curr_status=($row['status']=='0')?'<i class="fa fa-unlock text-success" aria-hidden="true"></i>':'<i class="fa fa-lock text-danger" aria-hidden="true" style="color:red !important"></i>'; 
                if($row['status']==0)
                {
                    $status_tooltip_text="Click to change disable";
                }
                else
                {
                    $status_tooltip_text="Click to change enable";
                }

                if($row['id']!=$user_id)
                {
                    echo $status_link='&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn-status-change" data-curstatus="'.$row['status'].'" data-id="'.$row['id'].'" id="status_'.$row['id'].'" data-original-title="'.$status_tooltip_text.'" data-toggle="tooltip" data-placement="top">'.$curr_status.'</a>';
                }
            }

            // $edit_link='';
            // if(strtolower($user_type)=='admin')
            // {
            //     $edit_link='&nbsp;&nbsp;<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row['id'].'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
            // }
            // else
            // {
            //     if($row['id']!=$user_id)
            //     {
            //         if(is_method_available('user','edit_employee')==TRUE){
                    //$edit_link=' / <a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row->id.'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
                //     }
                // }
                // else
                // {
                    //$edit_link=' / <a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row->id.'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
            //     }
            // }
            
            if(is_permission_available('edit_users_non_menu')){
                echo '&nbsp;&nbsp;<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$row['id'].'" class="redirect_to_href" data-original-title="'.$edit_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil" aria-hidden="true"></i><a>';
            }


            // $role_set_link='';
            // if(is_method_available('user','manage_permission')==TRUE && $user_id=='1'){
            // $role_set_link='&nbsp;&nbsp;<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_permission/'.$row['id'].'" class="redirect_to_href" data-original-title="'.$role_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-list" aria-hidden="true"></i></a>';
            // }
            // echo $role_set_link;

            if(is_permission_available('manage_user_permission_non_menu')){
                if($row['id']!='1'){
                    echo '&nbsp;&nbsp;<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_permission/'.$row['id'].'" class="redirect_to_href" data-original-title="'.$role_tooltip_text.'" data-toggle="tooltip" data-placement="top"><i class="fa fa-list" aria-hidden="true"></i></a>';
                }
            }

            $delete_link='';
            if(is_method_available('user','delete_employee')==TRUE){
            //$delete_link=' / <a href="JavaScript:void(0);" onclick="confirm_delete('.$row->id.')" data-original-title="Delete the employee" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
            }
            echo $delete_link;
            ?>
          </div>
        </td>
    </tr>
<?php
    }
}
?>

