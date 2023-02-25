<?php if(count($rows)){ ?>
    <?php foreach($rows as $row){ ?>
      <tr class="<?php echo ($row['is_read']=='Y')?'read':'unread' ?>" id="block_<?php echo $row['thread_id']; ?>" data-id="<?php echo $row['thread_id']; ?>">
        <td class="text-left " >
          <label class="check-box-sec">
              <input type="checkbox" value="<?php echo $row['thread_id']; ?>" class="" name="gmail_overview_id" data-currstatus="<?php echo $row['is_read']; ?>">
                <span class="checkmark"></span>
            </label>
            <?php if($row['is_contact_available']=='Y'){ ?>
              <div class="user-icon"><img src="<?php echo assets_url(); ?>images/user_active.png" title="Contact found"></div>            
            <?php }else{ ?> 
            <div class="user-icon"><img src="<?php echo assets_url(); ?>images/user_inactive.png"></div>
            <?php } ?>
        </td>
        <td class="text-left detail_page" data-id="<?php echo $row['thread_id']; ?>">
          <strong class="tooltip not-ab" data-id="0" style="cursor: pointer;"><?php echo (strlen(strip_tags($row['from_name_with_logic']))>25)?substr(strip_tags($row['from_name_with_logic']),0,25).'...':strip_tags($row['from_name_with_logic']); ?> &nbsp;<?php echo ($row['mail_count']>1)?$row['mail_count']:''; ?></strong>
        </td>
        <td class="text-left">
          <div class="mail-left">
                <div class="mail-subject detail_page" data-id="<?php echo $row['thread_id']; ?>" style="cursor: pointer;">
                  <strong style="cursor: pointer;"><?php echo $row['subject']; ?></strong> - 
                  <?php //echo substr(strip_tags($row['message']),0,10); ?>
                  <?php
                  echo $text = strip_tags($row['body_text']);              
                  //echo substr($text,0,200);
                  ?>
                </div>
                <?php if($row['file_name'])
                { 
                ?>              
                <div class="mail-attachment">
                    <?php 
                    $file_arr=explode(",", $row['file_name']);
                    $file_path_arr=explode(",", $row['file_full_path']);
                    $k=0;
                    for($i=0;$i<count($file_arr);$i++)
                    {
                      if($i<=1)
                      {
                    ?>
                    <span data-content="<?php echo base64_encode($file_path_arr[$i]); ?>" class="attachment_download">
                      <?php 
                      $ext = pathinfo($file_arr[$i], PATHINFO_EXTENSION);
                      //var $ext=end(explode('.', $file_arr[$i]));
                      rander_extention_wise_image($ext);
                      ?>
                      <?php echo $file_arr[$i]; ?></span>
                    <?php 
                        $k++;
                      }
                      
                    } 
                    if(count($file_arr)>2)
                    {
                      ?>
                        <span class="" onclick="">+<?php echo (count($file_arr)-$k); ?></span>
                      <?php
                    }                    
                    ?>
                </div>
                <?php 
                } 
                ?>
          </div>
          <div class="mail-action ">
            <ul class="action-ul">
              <?php /* ?>
              <li>
                <a href="JavaScript:void(0)" class="tooltip-new not-ab add_as_lead" data-toggle="tooltip" title="Add as lead" data-id="<?php echo $row['id']; ?>" ><i class="fa fa-plus" aria-hidden="true"></i></a>
              </li>
              <li>
                <a href="JavaScript:void(0)" class="tooltip-new not-ab add_as_company" data-toggle="tooltip" title="Add as company" data-id="<?php echo $row['id']; ?>"><i class="fa fa-building" aria-hidden="true"></i></a>
              </li>
              <?php */ ?>
              <li>
                <a href="JavaScript:void(0)" class="tooltip-new not-ab delete" data-id="<?php echo $row['thread_id']; ?>" data-status="A" data-toggle="tooltip" title="Archive"><i class="fa fa-archive" aria-hidden="true"></i></a>
              </li>
              <li>
                <a href="JavaScript:void(0)" class="tooltip-new not-ab delete" data-id="<?php echo $row['thread_id']; ?>" data-status="D" data-toggle="tooltip" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
              </li>
              <li>
                <a href="JavaScript:void(0)" class="tooltip-new not-ab seen_status_change" data-toggle="tooltip" data-id="<?php echo $row['thread_id']; ?>" title="<?php echo ($row['is_read']=='N')?'Mark as read':'Mark as unread' ?>" data-curstatus="<?php echo $row['is_read']; ?>"><?php if($row['is_read']=='N'){?><img src="<?php echo assets_url(); ?>images/drafts_white.png"><?php }else{?> <img src="<?php echo assets_url(); ?>images/mark_as_unread_white.png"><?php } ?> </a>
              </li>
            </ul>
          </div>
          <div class="mail-time">
            <?php //echo date_db_format_to_display_format(date('Y-m-d',$row['internal_date'])); 
            echo datetime_db_format_to_gmail_format($row['date']);
            ?>
          </div>
        </td>
      </tr>
    <?php } ?>
  <?php }else{ ?>
    <tr class="unread" id="block_<?php echo $row['id']; ?>" >
        <td class="text-center" colspan="3" style="width: 100%;">No mail available!</td>
      </tr>
  <?php } ?>
  <script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
  
});
</script>