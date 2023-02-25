<?php if(count($rows)){ $j=1;?>
    <?php foreach($rows AS $row){ ?>
        <tr>
            <td class="align-middle">
                <span class="text-xs font-weight-bold"><?php echo $row['user_id']; ?></span>
            </td>
            <td class="align-middle">
                <span class="text-xs font-weight-bold"><?php echo $row['user_name']; ?></span>
            </td>
            <td class="align-middle">
                <span class="text-xs font-weight-bold"><?php echo date_db_format_to_display_format($row['action_at']); ?></span>
            </td>
            <td class="align-middle">
                <span class="text-xs font-weight-bold"><?php echo date('l', strtotime($row['action_at'])); ?></span>               
            </td>
                                                        
            <td class="align-middle">
                                 
                    <?php 
                    $action_at_arr=explode(',',$row['action_at_str']); 
                    $action_type_arr=explode(',',$row['action_type_str']); 
                    if(count($action_at_arr))
                    {
                        $total_second=0;
                        for($i=0;$i<count($action_at_arr);$i++)
                        {
                            if($action_type_arr[$i]=='LO' && $i!=0)
                            {
                                $expiry_time = new DateTime($prev_time);
                                $current_date = new DateTime($action_at_arr[$i]);
                                $diff = $expiry_time->diff($current_date);
                                $time=$diff->format('%H:%I:%S');                                
                                $tmp_time=explode(":",$time);
                                $total_second=($tmp_time[0]*60*60)+($tmp_time[1]*60)+$tmp_time[2]+$total_second;
                            }
                            $prev_time=$action_at_arr[$i]; 
                        }
                    }
                    ?>
                    <span class="text-xs font-weight-bold"><?php  echo gmdate("H:i:s", $total_second);?></span> 
            </td>
            <td class="text-center">
                <a href="JavaScript:void(0);" class="icon-btn btn-secondary text-white user_activity_log_breakup_view" data-userid="<?php echo $row['user_id']; ?>" data-date="<?php echo $row['action_at_date']; ?>" data-displaydate="<?php echo date_db_format_to_display_format($row['action_at']); ?>" data-day="<?php echo date('l', strtotime($row['action_at'])); ?>" data-username="<?php echo $row['user_name']; ?>">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 57.945 57.945" style="enable-background:new 0 0 57.945 57.945;" xml:space="preserve">
                    <path style="fill:#FFF" d="M57.655,27.873c-7.613-7.674-17.758-11.9-28.568-11.9c-0.02,0-0.039,0.001-0.059,0.001c-0.01,0-0.019-0.001-0.028-0.001
                    c-0.027,0-0.054,0.004-0.081,0.004C18.173,16.02,8.093,20.239,0.52,27.873l-0.23,0.232c-0.389,0.392-0.386,1.025,0.006,1.414
                    c0.195,0.193,0.45,0.29,0.704,0.29c0.257,0,0.515-0.099,0.71-0.296l0.23-0.232c5.254-5.297,11.782-8.855,18.895-10.411
                    C17.89,21.255,16,24.896,16,28.972c0,7.168,5.832,13,13,13s13-5.832,13-13c0-4.11-1.923-7.774-4.91-10.158
                    c7.211,1.527,13.829,5.108,19.145,10.467c0.389,0.393,1.023,0.395,1.414,0.006C58.041,28.898,58.044,28.265,57.655,27.873z
                        M29,23.972c-2.757,0-5,2.243-5,5c0,0.552-0.448,1-1,1s-1-0.448-1-1c0-3.86,3.14-7,7-7c0.552,0,1,0.448,1,1S29.552,23.972,29,23.972
                    z"/>            
                    </svg>
                </a>
            </td>
        </tr>
    <?php $j++;} ?>
<?php }else{ ?>
    <tr>
        <td class="align-middle" colspan="6">
            <span class="text-xs font-weight-bold">No Record Found!</span>
        </td>
    </tr>
<?php } ?>