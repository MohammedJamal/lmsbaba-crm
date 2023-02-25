<?php //Google Map API Key : AIzaSyC5bAC702lxEOajEHcxFgakKOWb_F6Mpq4 ; ?>
<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ $google_map_api_key=$company['google_map_api_key'];?>
        <div class="track-details">
            <div class="check-box-sec mr-25">
                    <strong>User:</strong> <?php echo $row['user_name']; ?> 
                </div>
                <div class="check-box-sec mr-25">
                    <strong>Date:</strong> <?php echo date_db_format_to_display_format($row['datetime']); ?> 
                </div>
                <div class="check-box-sec">
                    <?php
                    $lat_long_arr=explode(',',$row['lat_long_srt']); 
                    if(count($lat_long_arr))
                    {                        
                        $tmp_lat1='';
                        $tmp_long1=''; 
                        $tmp_lat2='';
                        $tmp_long2='';   
                        $total_distance=0;                
                        for($i=0;$i<count($lat_long_arr);$i++)
                        {
                            $lat_long_tmp_arr=explode("#",$lat_long_arr[$i]);
                            $lat=$lat_long_tmp_arr[0];
                            $long=$lat_long_tmp_arr[1]; 
                            
                            if($i%2!=0){
                                $tmp_lat2=$lat;
                                $tmp_long2=$long;
                            }
                            else{
                                $tmp_lat1=$lat;
                                $tmp_long1=$long;
                            }
                            
                            if($tmp_lat1!='' &&  $tmp_long1!='' && $tmp_lat2!='' &&  $tmp_long2!='')
                            {
                                $total_distance=($total_distance+distance_by_lat_long($tmp_lat1,'-'.$tmp_long1,$tmp_lat2,'-'.$tmp_long2, "K"));
                            }
                        }
                    }
                    ?>
                    <strong>Total Distance Covered:</strong> <?php echo ($total_distance)?number_format($total_distance,2).' KM':''; ?> 
                </div>
            </div>

            <div class="track-timeline timeline-one-side">
                <?php 
                $tmp_icon_color='';
                $bubble_icon_color=['text-success','text-danger','text-info','text-warning','text-dark'];                
                // $lat_long_arr=explode(',',$row['lat_long_srt']);  
                $time_arr=explode(',',$row['time_srt']); 
                $address_arr=explode('$',$row['address_srt']); 
                $tmp_address='';          
                if(count($lat_long_arr))
                {
                    $j=0; 
                    $tmp_lat1='';
                    $tmp_long1=''; 
                    $tmp_lat2='';
                    $tmp_long2='';   
                    $get_distance='';                
                    for($i=0;$i<count($lat_long_arr);$i++)
                    {
                        
                        $lat_long_tmp_arr=explode("#",$lat_long_arr[$i]);
                        $lat=$lat_long_tmp_arr[0];
                        $long=$lat_long_tmp_arr[1];
                        $latlng=$lat.','.$long;
                        if($google_map_api_key)
                        {
                            $google_map_api_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng&sensor=false&key=$google_map_api_key";
                            $geocode=file_get_contents($google_map_api_url);
                            $output= json_decode($geocode);     
                            $tmp_address=$output->results[0]->formatted_address;
                        }
                        else{
                            $tmp_address=$address_arr[$i];
                        }

                        
                        $google_map_url="https://maps.google.com/maps?q=$latlng&t=&z=15&ie=UTF8&iwloc=&output=embed";
                        $tmp_icon_color=$bubble_icon_color[$j]; 
                        
                        if($i%2!=0){
                            // echo 'second';
                            $tmp_lat2=$lat;
                            $tmp_long2=$long;
                        }
                        else{
                            // echo'first';
                            $tmp_lat1=$lat;
                            $tmp_long1=$long;
                        }
                        
                        if($tmp_lat1!='' &&  $tmp_long1!='' && $tmp_lat2!='' &&  $tmp_long2!='')
                        {
                            // echo'OK-'.$tmp_lat1.',-'.$tmp_long1.','.$tmp_lat2.',-'.$tmp_long2;
                            $get_distance=distance_by_lat_long($tmp_lat1,'-'.$tmp_long1,$tmp_lat2,'-'.$tmp_long2, "K");
                        }
                                              
                        ?>
                        <div class="timeline-block mb-3">
                        <span class="timeline-km"><?php  echo ($get_distance)?number_format($get_distance,2).' KM':''; ?></span>
                            <span class="timeline-step">
                                <a href="<?php echo $google_map_url; ?>" class="get_google_map" data-title="Map" title="Click to view Map"><i class='material-icons <?php echo $tmp_icon_color; ?> text-gradient'>person_pin_circle</i></a>                                                    
                            </span>
                            <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0"><?php echo $time_arr[$i]; ?></h6>
                            <small><b>Latitude:</b> <i><?php echo $lat; ?></i></small>
                            <small><b>Longitude:</b> <i><?php echo $long; ?></i></small>
                            <p class="text-secondary font-weight-bold text-xs mt-0 mb-0"><?php echo $tmp_address; ?></p>
                            </div>
                        </div>
                        <?php  
                        $j++;
                        if($j==count($bubble_icon_color)){                            
                            $j=0;
                        }
                    }
                }
                ?>
                
               
                <!-- 
                <div class="timeline-block mb-3">

                    <span class="timeline-step">
                    <i class='material-icons text-success text-gradient'>person_pin_circle</i>                                                    
                    </span>
                    <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">10:30 AM</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-0 mb-0">Victoria Memorial</p>
                    </div>
                </div>
                <div class="timeline-block mb-3">
                    <span class="timeline-km">7 KM</span>
                    <span class="timeline-step">
                    <i class="material-icons text-danger text-gradient">person_pin_circle</i>
                    
                    </span>
                    <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">11:20 AM</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-0 mb-0">Fort William</p>
                    </div>
                </div>
                <div class="timeline-block mb-3">
                    <span class="timeline-km">7 KM</span>
                    <span class="timeline-step">
                    <i class="material-icons text-info text-gradient">person_pin_circle</i>
                    
                    </span>
                    <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">11:55 AM</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-0 mb-0">Howrah Bridge</p>
                    </div>
                </div>
                <div class="timeline-block mb-3">
                    <span class="timeline-km">7 KM</span>
                    <span class="timeline-step">
                    
                    <i class="material-icons text-warning text-gradient">person_pin_circle</i>
                    </span>
                    <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">12:30 PM</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-0 mb-0">Park Street</p>
                    </div>
                </div>
                <div class="timeline-block">
                    <span class="timeline-km">7 KM</span>
                    <span class="timeline-step">

                    <i class="material-icons text-dark text-gradient">person_pin_circle</i>
                    </span>
                    <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">02:20 PM</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-0 mb-0">Salt Lake</p>
                    </div>
                </div> -->
        </div>
    <?php } ?>
<?php }else{ ?>
    <div class="track-details">No Record Found!</div>
<?php } ?>
