<div class="form-group">
    <div class="col-sm-12 text-center">
        <div class="container">
            <h4 class="text-left">
                Company Name: <?php echo $client_info->name; ?> <br>
                LMS ID: <?php echo $client_info->client_id; ?> 
            </h4>
            <div>&nbsp;</div>
            
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-left"><u>All Services List</u>  </h5>
                    <p></p>
                    <div id="client_service_list_response_div">
                    
                    <table class="table ">
    <thead>
        <tr>
            <th>Service</th>
            <th>No of User(s)</th>
            <th>Amount</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Expiry Date</th>
            <th>Renewal Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
		$todaydate=date('Y-m-d');
		if(count($rows)){ $i=0;$sid='';?>
            <?php foreach($rows AS $row){
                
                $tbl_tr_bg='';
                $tbl_td_color='';
                if($row['is_active']=='N'){
                    $tbl_tr_bg='table-danger';
                    $tbl_td_color='text-white';
                } else {
                    $tbl_tr_bg='';
                    $tbl_td_color='';
                }
                
                ?>
                <?php 
                    if($row['service_id']!=$sid){ $i=0; }
                if($i==0){
                ?>
                <tr>
                    <td class="text-left table-info" colspan="8">
                        <h6 style="font-size: 16px;font-weight: normal;color:#fff"><?php echo $row['service_name']; ?> ( Total Amount: <?php echo number_format($row['total_price']); ?>/- )</h6>
                        
                    </td>
                </tr>
                <?php } ?>
                <tr class="<?php echo $tbl_tr_bg;?>">
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo $row['display_name']; ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo $row['no_of_user']; ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo $row['price']; ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?>"><?php echo date_db_format_to_display_format($row['start_date']); ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?> text-<?php echo (($row['is_active']=='N')?'white':(($todaydate>=$row['end_date'])?'danger':'black'));?>"><?php echo date_db_format_to_display_format($row['end_date']); ?></td>
                    <td class="text-left <?php echo $tbl_td_color;?> text-<?php echo (($row['is_active']=='N')?'white':(($todaydate>=$row['expiry_date'])?'danger':'black'));?>"><?php echo date_db_format_to_display_format($row['expiry_date']); ?></td>
                    <td class="text-center <?php echo $tbl_td_color;?>">
					
					<?php
					
					if($row['service_end_day']>60){
					
						echo'<span class="text-success">Enable</span>';
											
					} elseif($row['service_end_day']>0 && $client['service_end_day']<=60){
					
						echo'<span class="text-primary">Renewal</span>';
											
					} elseif($row['service_end_day']<=0){
					
						echo'<span class="text-'.(($row['is_active']=='N')?'white':'danger').'">Pending Renewal</span>';
												
					}
					?></td>
                    
                    <td class="text-center <?php echo $tbl_td_color;?>">
                        
                        
                        <a href="JavaScript:void(0);" title="Log" class="get_service_detail_log" data-service_order_detail_id="<?php echo $row['id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></a>
                        
                    </td>
                </tr>
            <?php $sid=$row['service_id'];$i++;} ?>
            <?php }else{ ?>
                <tr>
                    <td class="text-left" colspan="8">No Service Found!</td>
                </tr>
            <?php } ?>
    </tbody>
</table>
                    </div>                                                                
                </div>
            </div>

        </div>
    </div>
</div>