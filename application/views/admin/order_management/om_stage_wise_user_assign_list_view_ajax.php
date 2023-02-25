<div class="table-responsive">
    <table class="table align-items-center mb-0 sp-table-new" width="100%">
        <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Sl. No.</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2" width="20%">Stage</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Assigned User</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="10%">Action</th>        
        </tr>
        </thead>
        <tbody>
        <?php 
            if(count($rows))
            { 
                $total_second=0;
                $i=0;?>
                <?php 
                foreach($rows AS $row)
                {           
                    $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?>.</td>                                
                    <td><span class="text-xs font-weight-bold"> <?php echo $row['name']; ?> </span></td>                        
                    <td><span class="text-xs font-weight-bold"><a href="JavaScript:void(0)" class="untag_assign_user_popup" data-stage_id="<?php echo $row['id']; ?>"><?php echo ($row['assigned_user_name'])?$row['assigned_user_name']:'-'; ?></a></span></td>
                    <td class="align-middle" class="text-center">
                        <span class="text-xs font-weight-bold">
                            <a href="JavaScript:void(0)" class="tag_assign_user_popup" data-stage_id="<?php echo $row['id']; ?>"><i class="fa fa-tags" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                            <a href="JavaScript:void(0)" class="untag_assign_user_popup" data-stage_id="<?php echo $row['id']; ?>"><i class="fa fa-chain-broken" aria-hidden="true"></i></a>
                        </span>
                    </td>                         
                </tr>
                <?php 
                                              
                } ?>
                <?php } ?>        
        </tbody>
    </table>
</div>


