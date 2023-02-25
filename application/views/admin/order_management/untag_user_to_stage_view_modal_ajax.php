<div class="table-responsive">
    <table class="table align-items-center mb-0 sp-table-new">
        <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2" width="80%">Name</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Action</th>        
        </tr>
        </thead>
        <tbody id="un_tbody">
        <?php 
            if(count($user_list))
            { 
                $total_second=0;
                $i=0;?>
                <?php 
                foreach($user_list AS $row)
                {           
                    $i++;
                ?>
                <tr id="div_<?php echo $row['id']; ?>">                                          
                    <td><span class="text-xs font-weight-bold"> <?php echo $row['name']; ?> </span></td> 
                    <td class="align-middle text-center">
                        <span class="text-xs font-weight-bold">
                            <a href="JavaScript:void(0)" class="untag_assign_user_popup_confirm text-red" data-user_id="<?php echo $row['id']; ?>" data-stage_id="<?php echo $stage_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                        </span>
                    </td>                         
                </tr>
                <?php 
                                              
                } ?>
                <?php }else{ ?>  
                <tr> 
                    <td colspan="2" class="align-middle" class="text-center">No Record Found!</td>                         
                </tr>
                <?php } ?>
        </tbody>
    </table>
</div>
