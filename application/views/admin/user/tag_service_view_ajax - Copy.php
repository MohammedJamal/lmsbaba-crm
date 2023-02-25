<!-- <pre><?php print_r($service_info['all_available_service_order']); ?></pre> -->
<div class="row mb-25">
    <div class="col-md-12">                                 
        <div><h5>User List</h5></div>
        <ul id="sortable0" class="dropfalse sortable-ul sortable-userlist" data-service_order_detail_id="">
        <?php 
        if(count($all_user))
        {
            foreach($all_user AS $user)
            {
            ?>                            
                <li id="<?php echo $user['id']; ?>" class="ui-state-default <?php if($user['id']==1){ ?>ui-state-disabled<?php } ?>"><?php echo $user['name'].' (ID: '.$user['id'].')'; ?></li>
            <?php
            }
        }
        ?>
        </ul>
    </div>
</div>
<div><h5>Service List</h5></div>
<div class="row col-gap-20">
    <?php    
    if(count($service_info['all_available_service_order']))
    {
        $i=1;
        $r=1;
        foreach($service_info['all_available_service_order'] AS $service_order)
        {

            if($s_id==$service_order['service_id'])
            { 
        ?> 
        <div class="col-md-3">
            <div class="service-div sp-drop">
            <div>
                <font class=""  style="font-weight:bold; color:#000"><?php echo $service_order['service_name'].'-'. $service_order['display_name']; ?></font><br>
                <small class="text-info">Available User: <?php echo $service_order['no_of_user']; ?> (Admin + <?php echo ($service_order['no_of_user']-1); ?>)</small><br>
                <small class="text-info">Start Date: <?php echo date_db_format_to_display_format($service_order['start_date']); ?></small><br>
                <small class="<?php echo (date("Y-m-d")<$service_order['end_date'])?'text-info':'text-danger'; ?>">End Date: <?php echo date_db_format_to_display_format($service_order['end_date']); ?></small><br>
                <small class="<?php echo (date("Y-m-d")<$service_order['expiry_date'])?'text-info':'text-danger'; ?>">Expiry Date: <?php echo date_db_format_to_display_format($service_order['expiry_date']); ?></small>
            </div>
            <ul id="sortable<?php echo $i; ?>" class="dropfalse sortable-ul w-100 m-0" data-service_order_detail_id="<?php echo $service_order['id']; ?>">
                <?php if(count($all_user_wise_service_order)){ ?>
                    <?php foreach($all_user_wise_service_order AS $tagged_sod){ ?>
                        <?php if($tagged_sod['service_order_detail_id']==$service_order['id']){ ?>
                            <li id="<?php echo $tagged_sod['user_id']; ?>" class="ui-state-default"><?php echo $tagged_sod['name'].' (User ID:- '.$tagged_sod['user_id'].')'; ?></li>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </ul>
            </div>
        </div>
        <?php 
        $i++; 
        $r++; 
        ?>
        <?php
            if( $r == 5){
            echo '</div> <div class="row col-gap-20">';
            $r=1;
            }
        ?>
        <?php
            }
        }
    }
    ?>
</div>