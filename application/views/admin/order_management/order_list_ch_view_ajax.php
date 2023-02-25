
<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <?php
        $is_disable='Y';
        if(isset($stage_wise_assigned_users[$row['pi_stage_id']])){
            $assign_user_arr=explode(",",$stage_wise_assigned_users[$row['pi_stage_id']]);
            if(in_array($user_id,$assign_user_arr)){
                $is_disable='N';
            }
        }                
        ?>
        <tr scope="row" class="" style="<?php echo ($is_disable=='Y')?'background: #999;opacity:.5;':''; ?>">
            <td class=""><?php echo ($row['lead_opportunity_wise_po_id'])?$row['lead_opportunity_wise_po_id']:'-'; ?></td>
            <td class=""><?php echo ($row['po_date'])?date_db_format_to_display_format($row['po_date']):'-'; ?></td>
            <td class="">
                <?php echo ($row['cus_company_name'])?''.$row['cus_company_name']:$row['cus_contact_person']; ?>
                <?php echo ($row['cust_city_name'])?', '.$row['cust_city_name']:''; ?>
                <?php echo ($row['cust_country_name'])?', '.$row['cust_country_name']:''; ?>
            </td>
            <td class=""><?php echo $row['stage_name']; ?></td>
            <td class=""><?php echo get_om_priority_name_by_id($row['pi_priority']); ?> </td>
            <td class=""><?php echo ($row['expected_delivery_date']!='' && $row['expected_delivery_date']!='0000-00-00')?date_db_format_to_display_format($row['expected_delivery_date']):'N/A'; ?></td>
            <td class="" align="center">
                <?php if($stage_id!=''){echo'N/A';}else{ ?>
                <a href="JavaScript:void(0)" class="<?php echo ($is_disable=='N')?'get_om_detail':''; ?>" data-lowp="<?php echo $row['lead_opportunity_wise_po_id']; ?>" data-pfi="<?php echo $row['id']; ?>" data-stageid="<?php echo $row['stage_id']; ?>" data-po_pi_wise_stage_tag_id="<?php echo $row['om_po_pi_wise_stage_tag_id']; ?>" data-pfi_split_id="<?php echo $row['split_id']; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                <?php if($is_priority_link_available==true){ ?>
                &nbsp;&nbsp;
                <a class="change_status_order-- <?php echo ($is_disable=='N')?'change_priority':''; ?> <?php echo ($row['pi_priority']=='2')?'active':''; ?>" href="JavaScript:void(0);" data-po_pi_wise_stage_tag_id="<?php echo $row['om_po_pi_wise_stage_tag_id']; ?>">
                    <i class="fa fa-star" aria-hidden="true"></i> 
                </a>
                <?php } ?>
                <?php } ?>
            </td>
        </tr> 
        <?php } ?>
<?php } ?>   