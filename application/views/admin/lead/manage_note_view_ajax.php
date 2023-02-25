<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){       
      
      $is_seen_all='Y';
      if(count($row->total_reply_ids))
      {
        foreach($row->total_reply_ids AS $reply_info)
        {
          $reply_info_arr=explode("#",$reply_info);
          if($reply_info_arr[1]=='N'){
            $is_seen_all='N';
            break;
          }
        }
      }
      ?>
    <tr scope="row" class="<?php echo ($is_seen_all=='Y')?'read':'unread'; ?>">
      <!-- <td class="">      
        <label class="control control--checkbox">
           <input type="checkbox" value="<?php echo $row->id;?>" name="sync_call_checked">
            <div class="control__indicator"></div>
         </label>          
      </td> -->      
      <td class=""><?php echo ($row->id)?$row->id:'-'; ?></td>
      <td class=""><?php echo ($row->created_at)?datetime_db_format_to_display_format_ampm($row->created_at):'-'; ?></td>      
      <td class="" align="middle">
        <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?search_by_id=<?php echo $row->lead_id;?>" style="color:#00aeef !important;font-size:12px;"><?php echo ($row->lead_id)?$row->lead_id:'-'; ?></a>
      </td>
      <td class=""><?php echo ($row->company_name)?$row->company_name:'-'; ?></td>
      <td class=""><?php echo ($row->assigned_user_name)?$row->assigned_user_name:'-'; ?></td>
      <td class=""><?php echo ($row->note_added_by)?$row->note_added_by:'-'; ?></td>
      <td class="" style="word-break:break-word;" width="30%">
        <?php echo ($row->note)?$row->note:'-'; ?>
        <br>
        
        <!-- NOTE -->
        <div id="vc_note_outer_<?php echo $row->id;?>">
          <div class="lead-dropdown new-style">
            <div class="dropdown">
              <button class="btn-dropdown note-icon view_comments" id="view_comments_<?php echo $row->id;?>" style="color:#00aeef !important;font-size:12px; <?php echo ($is_seen_all=='N')?'font-weight:bold':''; ?>" data-nid="<?php echo $row->id; ?>" data-id="<?php echo $row->id;?>" data-leadid="<?php echo $row->lead_id;?>" type="button" data-toggle="tooltip" data-placement="right" title="View Replies">
                View Replies (<?php print_r(count($row->total_reply_ids));?>)
              </button>
              <?php //print_r($row->total_reply_ids); ?>
              <div class="dropdown-menu right" id="vc_note_inner_div_<?php echo $row->id;?>"></div>
            </div>
          </div>
        </div>
        <!-- NOTE -->
        <!-- <br><a href="JavaScript:void(0)" class="view_comments" data-nid="<?php echo $row->id; ?>" data-id="<?php echo $row->id;?>" data-leadid="<?php echo $row->lead_id;?>">View Comments</a> -->
      </td>
      <td class="<?php echo ($row->cust_mobile!='')?'bg-success':''; ?>">
        <!-- NOTE -->
        <div id="note_outer_<?php echo $row->id;?>">
          <div class="lead-dropdown new-style">
            <div class="dropdown">
              <button class="btn-dropdown note-icon note_btn" data-id="<?php echo $row->id;?>" data-leadid="<?php echo $row->lead_id;?>" type="button" data-toggle="tooltip" data-placement="right" title="Add Note">
              <span id="note_count_<?php echo $row->id;?>" class="note_count_<?php echo $row->lead_id;?>" style="background:<?php echo ($row->note_count>0)?'#59bb60':'#709fe5'; ?>"><?php echo $row->note_count; ?></span>
              <img src="<?php echo assets_url(); ?>images/note.png">
              </button>
              <div class="dropdown-menu right" id="note_inner_div_<?php echo $row->id;?>"></div>
            </div>
          </div>
        </div>
        <!-- NOTE -->               
      </td>      
    </tr>    
    <tr class="spacer <?php echo ($is_seen_all=='Y')?'read':'unread'; ?>"><td colspan="8"></td></tr>
<?php } ?>
<?php }else{ ?>
  <tr><td colspan="8" style="text-align:center;">No record found!</td></tr>
<?php } ?>