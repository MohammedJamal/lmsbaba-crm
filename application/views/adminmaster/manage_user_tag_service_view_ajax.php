<!-- <pre><?php print_r($service_info['all_available_service_order']); ?></pre> -->
<style>

</style>
<div class="row mb-25">
    <div class="col-md-12">   
    
        <fieldset class="ts-the-fieldset text-left">
            <legend class="ts-the-legend"><b>User List</b></legend>
            <ul id="sortable0" class="dropfalse sortable-ul sortable-userlist" data-service_order_detail_id="">
                <?php 
                if(count($all_user))
                {
                    foreach($all_user AS $user)
                    {
                    ?>                            
                        <li id="<?php echo $user['id']; ?>" class="ui-state-default <?php /*if($user['id']==1){ ?>ui-state-disabled<?php }*/ ?>"><?php echo $user['name'].' (ID: '.$user['id'].')'; ?></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </fieldset>   
        
    </div>
</div>
<!-- <div><h5>Service List</h5></div> -->
<fieldset class="ts-the-fieldset ">
        <legend class="ts-the-legend text-left"><b>Service List</b></legend>
        <div class="row col-gap-20">
                
                    <?php    
            if(count($all_available_service_order))
            {
                $i=1;
                $r=1;
                foreach($all_available_service_order AS $service_order)
                {

                    if($s_id==$service_order['service_id'])
                    { 
                ?> 
                <div class="col-md-3">
                    <div class="service-div sp-drop">
                    <div class="text-left">
                        <font class=""  style="font-weight:bold; color:#000"><?php echo $service_order['service_name'].': '.$service_order['no_of_user']; ?> User<?php echo ($service_order['no_of_user']>1)?'s':''; ?> License</font><br>
                        <small class="text-info">Available User: <?php echo $service_order['no_of_user']; ?></small><br>
                        <small class="text-info">Start Date: <?php echo date_db_format_to_display_format($service_order['start_date']); ?></small><br>
                        <small class="<?php echo (date("Y-m-d")<$service_order['end_date'])?'text-info':'text-danger'; ?>">End Date: <?php echo date_db_format_to_display_format($service_order['end_date']); ?></small><br>
                        <small class="<?php echo (date("Y-m-d")<$service_order['expiry_date'])?'text-info':'text-danger'; ?>">Expiry Date: <?php echo date_db_format_to_display_format($service_order['expiry_date']); ?></small>
                    </div>
                    <ul id="sortable<?php echo $i; ?>" class="ul-drop-target dropfalse sortable-ul w-100 m-0" data-service_order_detail_id="<?php echo $service_order['id']; ?>">
                        
                        <?php 
                        $tagcount = 0;
                        ?>

                        <?php if(count($all_user_wise_service_order)){ ?>
                            <?php foreach($all_user_wise_service_order AS $tagged_sod){ ?>
                                <?php if($tagged_sod['service_order_detail_id']==$service_order['id']){ ?>
                                    <li id="<?php echo $tagged_sod['user_id']; ?>" class="ui-state-default">
                                        <?php echo $tagged_sod['name'].' (User ID:- '.$tagged_sod['user_id'].')'; ?>
                                        <?php $tagcount++; ?>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        
                    </ul>
                    <?php if($tagcount == 0){ ?>
                        <div class="showDrag">
                            <span>
                                <img src="<?=assets_url();?>images/drag-icon.png">
                            </span> Drag here...
                        </div>
                    <?php } ?>
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
</sieldset>