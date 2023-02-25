<?php

if(count($row)>0)
{ 
        if($row['user_type']=='Admin')
        {
            $edit_tooltip_text='Edit the super admin';
            $super_admin_text='<span class="label label-success" data-original-title="Super admin" data-toggle="tooltip" data-placement="left">Super Admin</span>';
        }
        else
        {
            $edit_tooltip_text='Edit the employee';
            $super_admin_text='';
        }
?>

    <div id="clickable_div_<?php echo $row['id']; ?>">
        <div class="card-box table-responsive">
            <table width="100%" cellpadding="4" cellspacing="4" class="table table-bordered table-striped m-b-0">
                <tr id="tr_<?php echo $row['id']; ?>">        
                    <td width="100%">
                        <table width="100%" cellpadding="4" cellspacing="4" class="table table-bordered table-striped m-b-0">
                            <tr>
                                <th colspan="2" class="text-left">Official Details</th>
                            </tr>
                            <tr>
                                <td width="30%">User Type</td>
                                <td class="text-left"><?php echo ($row['employee_type'])?$row['employee_type']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td width="30%">Branch</td>
                                <td class="text-left"><?php if($row['branch_id']){echo ($row['branch_name'])?$row['branch_name']:$row['cs_branch_name'];}else{echo'N/A';} ?></td>
                            </tr>
                            <tr>
                                <td width="30%">Official Email</td>
                                <td class="text-left"><?php echo ($row['email'])?$row['email']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Official Mobile</td>
                                <td><?php echo ($row['mobile'])?$row['mobile']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td><?php echo ($row['dept_name'])?$row['dept_name']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Manager</td>
                                <td><?php echo ($row['manager_name'])?$row['manager_name']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td><?php echo ($row['designation_name'])?$row['designation_name']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Functional Area</td>
                                <td><?php echo ($row['functional_area_name'])?$row['functional_area_name']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Joining Date</td>
                                <td><?php echo ($row['joining_date'])?date_db_format_to_display_format($row['joining_date']):'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td><?php echo ($row['date_of_birth'])?date_db_format_to_display_format($row['date_of_birth']):'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Salary</td>
                                <td><?php echo ($row['salary'])?$row['salary']:'N/A'; ?> (<?php echo ($row['salary_currency_code'])?$row['salary_currency_code']:'N/A'; ?>)</td>
                            </tr>
                            <tr>
                                <td>PAN No.</td>
                                <td><?php echo ($row['pan'])?$row['pan']:'N/A'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Aashar Card</td>
                                <td><?php echo ($row['aadhar'])?$row['aadhar']:'N/A'; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        <table width="100%" cellpadding="4" cellspacing="4" class="table table-bordered table-striped m-b-0">
                            <tr>
                                <th colspan="2" class="text-left">Personal Details</th>
                            </tr>
                            <tr>
                                <td width="30%">Personal Email</td>
                                <td class="text-left"><?php echo ($row['personal_email'])?$row['personal_email']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Personal Mobile</td>
                                <td><?php echo ($row['personal_mobile'])?$row['personal_mobile']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td><?php echo ($row['gender'])?($row['gender']=='M')?'Male':'Female':'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Marital Status</td>
                                <td><?php echo ($row['marital_status'])?$row['marital_status']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Marriage Anniversary</td>
                                <td><?php echo ($row['marriage_anniversary'])?date_db_format_to_display_format($row['marriage_anniversary']):'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Spouse Name</td>
                                <td><?php echo ($row['spouse_name'])?$row['spouse_name']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td><?php echo ($row['address'])?$row['address']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td><?php echo ($row['country_name'])?$row['country_name']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td><?php echo ($row['state_name'])?$row['state_name']:'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td><?php echo ($row['city_name'])?$row['city_name']:'N/A'; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        <table width="100%" cellpadding="4" cellspacing="4" class="table table-bordered table-striped m-b-0">
                            <tr>
                                <th colspan="2" class="text-left">Login Details</th>
                            </tr>
                            <tr>
                                <td width="30%">Emp. ID</td>
                                <td class="text-left"><?php echo ($row['id'])?$row['id']:'N/A'; ?> <a href="JavaScript:void(0);" data-toggle="tooltip" title="The Emp. ID is used as login username."><i class="fa fa-info-circle" aria-hidden="true"></i></a></td>
                            </tr>
                            <tr>
                                <td>Password </td>
                                <td><a href="JavaScript:void(0);" data-toggle="tooltip" title="Password has been sent to the personal email address."><i class="fa fa-info-circle" aria-hidden="true"></i></a></td>
                            </tr>
                            <?php /* ?>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <a href="JavaScript:void(0);" class="btn-status-change <?php if($row['status']=='0'){echo 'btn btn-success btn-sm';}else{echo 'btn btn-danger btn-sm';} ?>" data-curstatus="<?php echo $row['status']; ?>" data-id="<?php echo $row['id']; ?>" id="status_<?php echo $row['id']; ?>">
                                    <?php echo ($row['status']=='0')?'Enabled':'Disabled'; ?>
                                </a></td>
                            </tr>
                            <?php */ ?>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php     
}
else
{
    echo'No Record Found..';
}

?>
<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({html: true});
    });
</script>