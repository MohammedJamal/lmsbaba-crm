
<div class="responsive-table">
    <?php  
    $stage_name_tmp=array();
    if(count($rows[0])){
        foreach($rows[0] AS $v){
            if($v){array_push($stage_name_tmp,$v);} 
        } 
    }
    ?>
    <table class="table table-bordered new-table-bordered with-white-space" id="get-sales-pipeline">
        <?php if(count($rows)){ $i=0;?>
            <?php foreach($rows AS $row){ ?>
                <?php if($i==0){ ?>
                <thead>
                    <tr>
                        <th width="290"></th>
                        <?php  $k=1; foreach($row AS $val){ ?>
                        <?php if($val){ ?>
                        <th><div><?php echo $val; ?></div></th>
                        <?php } ?>
                        <?php $k++;}  ?>
                    </tr>
                </thead>
                <tbody>
                <?php }else{ ?>
                    <?php
                    
                    if($row['photo']!='')
                    {
                    $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$row['photo'];
                    }
                    else
                    {
                    $profile_img_path = assets_url().'images/user_img_icon.png';
                    }
                    ?>
                    <tr>
                        <td width="328">
                            <div class="user-details-block">
                                <div class="user-details-pic"><img src="<?php echo $profile_img_path; ?>"></div>
                                <div class="user-details-infos">
                                    <h2><?php echo $row['customer']; ?></h2>
                                    <?php echo ($row['designation'])?$row['designation']:''; ?>
                                </div>
                            </div>
                        </td>                        
                        <?php for($j=1;$j<=(count($row)-4);$j++){ ?>
                            <?php if(isset($row['stage_'.$j])){ ?>
                            <td class="text-center">
                                <a href="JavaScript:void(0);" class="blue-link rander_detail_popup" data-report="user_wise_sales_pipeline_report" data-filter1="<?php echo $stage_name_tmp[$j-1]; ?>" data-filter2="<?php echo $row['user_id'];?>" ><?php echo $row['stage_'.$j]; ?></a>
                            </td>
                            <?php } ?>
                        <?php } ?>                        
                    </tr>   
                    <?php } ?> 
        <?php $i++;} }?>
        </tbody>
    </table>
</div>



<!-- <div class="footer-table">
    <div class="footer-right">
        <a href="#" class="download-icon mr-15"><img src="<?php echo assets_url() ?>images/download-icon.png"></a>
        <a href="#" class="more-dot">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>
</div> -->
        
        