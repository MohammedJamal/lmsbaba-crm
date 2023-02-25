<?php //print_r($lead_data); ?>
<div class="row">
    <div class="col-md-12">
        <?php
        if($comment_list)
        {
        ?>
        <div class="width_full">            
            <div id="hist_list" class="lead_history_text">
                <?php        
                foreach($comment_list as $comment_data)
                {
                ?>      
                <div class="one_p">
                    <p><b><?=$comment_data->title;?></b></p>
                    <p style="color:#212121">
                        <b>Date:</b> 
                        <?php 
                        // echo date("d-M-Y h:i:s A", strtotime($comment_data->create_date));
                        echo datetime_db_format_to_display_format_ampm($comment_data->create_date);
                        ?> | 
                        <b>Updated By:</b> <?php echo $comment_data->updated_by;?> | 
                        <b>IP.</b> <?php echo $comment_data->ip_address;?>
                        <?php if($comment_data->source_name){ ?>
                        | 
                        <b>Source:</b> <?php echo ($comment_data->source_alias_name)?$comment_data->source_alias_name:$comment_data->source_name;?>
                        <?php } ?>
                        <?php if($comment_data->attach_file){ ?>
                            <?php
                            $attach_file_arr=explode("|$|", $comment_data->attach_file); 
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
                    
                    <p style="color:#212121">
						<?php if($comment_data->communication_type){ ?>
                        <b>Communication Type:</b> <?php echo $comment_data->communication_type;?> 
						<?php } ?>
                        <?php
						if($comment_data->next_followup_date!='' && $comment_data->next_followup_date!='0000-00-00 00:00:00'){ ?>
                        |						
						<b>Next Followup Date:</b> <?php echo datetime_db_format_to_display_format_ampm($comment_data->next_followup_date);?> 
                        <?php } ?>
                    </p>
                    
                    <?php if($comment_data->quotation_id){ ?>
                    <p style="color:#212121">
                        <b>Quotation:</b> <?php echo '#'.$comment_data->quotation_id.' - '.$comment_data->quotation_title;?></p>
                    <?php } ?>
                    <p style="color:#212121">
                        <?php 
                        if($comment_data->communication_type)
                        {
                            echo'<b>Comment:</b><br>';
                        }
                        echo str_replace('#','For ',strip_tags($comment_data->comment));
                        if($comment_data->mail_trail_html)
                        {
                            echo'<br>';
                            echo stripcslashes($comment_data->mail_trail_html);
                        }
                        ?>                            
                    </p>
                    <?php if($comment_data->mail_to_client=='Y'){?>
                    <p><b>Mail to Client:</b> Yes</p>
                    <?php }?>
                </div>                                
                <?php
                }
                ?>                                      
            </div>                                
        </div>
        <?php
        }
        ?> 
    </div>
</div>
<style type="text/css">
    .lead_history_text{
        width: 100% !important;
    }
</style>