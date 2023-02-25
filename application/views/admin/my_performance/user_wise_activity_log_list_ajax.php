<div class="card">            
            <div class="card-body bg-white px-0 pb-2">
               <div class="table-responsive">
                  <table class="table align-items-center mb-0 sp-table-new">
                     <thead>
                       <tr>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="80">Sl. No.</th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Login Time</th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Logout Time</th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder">IP</th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="140">Total Active Time (HH:mm:ss)</th>
                        
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
                                    $action_at_time=date("h:i:s A",strtotime($row['action_at']));                                    
                                        
                                ?>
                                    <tr>
                                        <td><?php echo $i; ?>.</td>                                
                                        <td>
                                        <span class="text-xs font-weight-bold"> <?php echo ($row['action_type']=='LI')?$action_at_time:'-'; ?> </span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold"> <?php echo ($row['action_type']=='LO')?$action_at_time:'-'; ?> </span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold"> <?php echo $row['ip_address']; ?> </span>
                                        </td>
                                        <td class="align-middle" class="text-center">
                                        <span class="text-xs font-weight-bold"> 
                                            <?php 
                                            if($row['action_type']=='LO' && $i!=1)
                                            { 
                                                // $dateDiff=intval((strtotime($prev_time)-strtotime($row['action_at']))/60);
                                                // $hours=intval($dateDiff/60);
                                                // $minutes=$dateDiff%60;
                                                // $second=$dateDiff%60;
                                                // echo $hours.''.$minutes;
                                                $expiry_time = new DateTime($prev_time);
                                                $current_date = new DateTime($row['action_at']);
                                                $diff = $expiry_time->diff($current_date);
                                                $time=$diff->format('%H:%I:%S');  // returns difference in hr min and sec
                                                echo $time;
                                                $tmp_time=explode(":",$time);
                                                $total_second=($tmp_time[0]*60*60)+($tmp_time[1]*60)+$tmp_time[2]+$total_second;
                                            } 
                                            else{
                                                echo'--';
                                            }
                                            ?>
                                            </span>
                                        </td>                         
                                    </tr>
                            <?php 
                                $prev_time=$row['action_at'];                                
                                } ?>
                       <?php } ?>
                       <tr class="grey-bg">
                         <td colspan="3"></td>
                         <td class="align-middle text-sm">
                           <span class="text-xs font-weight-bold"> 
                              Total Login Hour<br>
                              (HH:MM:SS)
                           </span>
                         </td>
                         
                         <td class="align-middle" class="text-center">
                            <span class="text-xs font-weight-bold"> 
                                <?php  echo gmdate("H:i:s", $total_second);?>
                            </span>
                         </td>
                         
                       </tr>
                     </tbody>
                   </table>
               </div>
            </div>

         </div>