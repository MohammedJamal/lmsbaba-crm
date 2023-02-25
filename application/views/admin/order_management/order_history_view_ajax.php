<?php   
if(count($order_history)){                            
    foreach($order_history as $history)
    {
    ?>      
    <div class="one_p">
        <p><b><?=$history['title'];?></b></p>
        <p style="color:#212121">
            <b>Date:</b> 
            <?php  echo datetime_db_format_to_display_format_ampm($history['created_at']); ?> | 
            <b>Updated By:</b> <?php echo $history['updated_by'];?> | 
            <b>IP.</b> <?php echo $history['ip_address'];?>
            <?php if($history['source_name']){ ?>
            | 
            <b>Source:</b> <?php echo ($history['source_alias_name'])?$history['source_alias_name']:$history['source_name'];?>
            <?php } ?>
            <?php if($history['attach_file']){ ?>
                <?php
                $attach_file_arr=explode("|$|", $history['attach_file']); 
                echo'|';
                foreach($attach_file_arr AS $file)
                {
                    $file_ext=end(explode(".",$file));
                    if(strtolower($file_ext)=='mp3' || strtolower($file_ext)=='mp4')
                    {
                        $file_action_text='Play <i class="fa fa-play" aria-hidden="true"></i>';
                    }
                    else
                    {
                        $file_action_text='Download <i class="fa fa-download" aria-hidden="true"></i>';
                    }
                ?>
            <b><a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download/<?php echo base64_encode($file); ?>" data-filepath="<?php echo assets_url();?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/company/lead/<?php echo $file; ?>"><?php echo $file_action_text; ?></b></a>&nbsp; 
            <?php }} ?>
        </p>                            
        <?php /* if($history['quotation_id']){ ?>
        <p style="color:#212121">
            <b>Quotation:</b> <?php echo '#'.$history['quotation_id'].' - '.$history['quotation_title'];?></p>
        <?php } */ ?>
        
        <p style="color:#212121">
            <b>Proforma No.:</b> <?php echo ($history['pro_forma_no'])?$history['pro_forma_no']:'N/A';?> |
            <b>Proforma Date:</b> <?php echo ($history['pro_forma_date'])?date_db_format_to_display_format($history['pro_forma_date']):'N/A';?> |
            <b>Proforma Due Date:</b> <?php echo ($history['pro_forma_due_date'])?date_db_format_to_display_format($history['pro_forma_due_date']):'N/A';?>                                
        </p>
        
        <p style="color:#212121">
            <b>Comment:</b><br>
            <?php echo str_replace('#','For ',strip_tags($history['comment']));?>                            
        </p>                            
    </div>                                
    <?php
    }
}
else{
?>
<div class="one_p">
    <p>No History Found!</p>        
</div>
<?php
}
?> 