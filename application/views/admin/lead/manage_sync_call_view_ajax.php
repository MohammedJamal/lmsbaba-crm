<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){ ?>
    <?php 
    
    if($row->current_stage_id!='1')
    { 
        $stage_log_arr=array_unique(explode(',', $row->stage_id_log_str));	
        $curr_stage_id=$row->current_stage_id;
        $stage_count=0;
        if(count($priority_wise_stage['active_lead_stages_y']))
        {
          foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
          {
            if(in_array($stage['id'], $stage_log_arr)){
                $stage_count++;
              }
          }
        }
        $active_stage_text='';
        $active_stage_id='';
        $k=1;		            	
        if(count($priority_wise_stage['active_lead_stages_y']))
        {
          foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
          {
            if(in_array($stage['id'], $stage_log_arr))
            {
              if($stage_count==$k)
              {		           
                if(($curr_stage_id=='3') || ($curr_stage_id=='5'))
                {
                  // $is_active_stage='';
                }  
                else
                {
                  if(in_array('4', $stage_log_arr)){ 
                    // $is_active_stage='active--';
                    }
                    else{
                      // $is_active_stage='active';
                      
                    }
                    $active_stage_text=$stage['name'];
                    $active_stage_id=$stage['id'];
                }
              }
              else
              {
                // $is_active_stage='';
                $active_stage_text=$stage['name'];
                $active_stage_id=$stage['id'];
              }
              $k++;			            		
            }
          }
        }

        if(count($priority_wise_stage['active_lead_stages_n']))
        {
          foreach($priority_wise_stage['active_lead_stages_n'] AS $stage)
          {
            if($curr_stage_id==$stage['id']){
              // $lead_lost_show='';
              $active_stage_text=$stage['name'];
              $active_stage_id=$stage['id'];
            }
            else{
              // $lead_lost_show='hide';
            }			            		
          }
        }		     
    } 
    else
    {
      if($row->lead_str){
        $active_stage_text='PENDING';
        $active_stage_id=1;
      }
      else{
        $active_stage_text='';
        $active_stage_id='';
      }
    }  
    
    if($row->cust_mobile!=''){

      if($active_stage_id=='3' || $active_stage_id=='5' || $active_stage_id=='6' || $active_stage_id=='7'){
        $bg_class='bg-danger';
      }
      else{
        $bg_class='bg-success';
      }
      
    }
    else{
      $bg_class='';
    }
    
    ?>
    <tr scope="row" class="">
      <td class="<?php echo $bg_class; ?>">      
        <label class="control control--checkbox">
           <input type="checkbox" value="<?php echo $row->id;?>" name="sync_call_checked">
            <div class="control__indicator"></div>
         </label>          
      </td>
      <td class="<?php echo $bg_class; ?>">
        <?php if($row->is_paying_customer=='Y'){ ?>
        <div class="call-log-lead-crown tooltipstered">
          <img src="<?php echo assets_url(); ?>images/crown.png" title="Paying Customer" alt="Paying Customer">
        </div>
        <?php } ?>
      </td>
      <td class="<?php echo $bg_class; ?>"><?php echo ($row->number)?$row->number:'-'; ?></td>
      <td class="<?php echo $bg_class; ?>"><?php echo ($row->name)?$row->name:'-'; ?></td>
      <td class="<?php echo $bg_class; ?>"><?php echo ($row->cust_company_name)?$row->cust_company_name:'-'; ?></td>
      <td class="<?php echo $bg_class; ?>">
        <?php 
        if($row->last_lead){ 
          if(in_array($row->assigned_user_id,$tmp_u_ids_arr)){
            ?>
            <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/?search_by_id=<?php echo $row->last_lead;?>"><?php echo $row->last_lead; ?></a>
            <?php
          }
          else{
            echo $row->last_lead;
          }
        }else{ echo '-'; }?>   
        <?php 
        // echo $row->is_active_stage_available;
        // echo'<br>';
        // print_r($row->active_lead_arr);
        // echo'<br>';
        // print_r($row->in_active_lead_arr);
        // echo'<br>';
        // print_r($row->deal_won_lead_arr);
        ?>
      </td>
      <td class="<?php echo $bg_class; ?>"><?php echo ($active_stage_text)?$active_stage_text:'-'; ?></td>            
      <td class="<?php echo $bg_class; ?>"><?php echo ($row->bound_type)?ucwords($row->bound_type):'-'; ?></td>
      <td width="10%" class="<?php echo $bg_class; ?>"><?php echo ($row->call_start)?date_db_format_to_display_format($row->call_start):'-'; ?></td>
      <td class="<?php echo $bg_class; ?>"><?php echo ($row->call_start)?time_db_format_to_display_format_ampm($row->call_start):'-'; ?></td>
      <td class="<?php echo $bg_class; ?>"><?php echo ($row->call_end)?time_db_format_to_display_format_ampm($row->call_end):'-'; ?></td>
      <td class="<?php echo $bg_class; ?>" align="center">
        <?php         
        if($row->call_start!='' && $row->call_end!='')
        {
          $date_a = new DateTime($row->call_start);
          $date_b = new DateTime($row->call_end);
          $interval = date_diff($date_a,$date_b);
          echo $interval->format('%h:%i:%s');
        }
        else
        {
          echo'-';
        }        
        ?>
      </td>
      <td class="<?php echo $bg_class; ?>"><?php echo ($row->assigned_user_name)?$row->assigned_user_name:'-'; ?></td>
      <td class="<?php echo $bg_class; ?>">       
        
        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/details/');?>" class="add_as_lead-- popup_action_row" title="Add as Lead" data-id="<?php echo $row->id; ?>" ><i class="fa fa-plus"></i></a>&nbsp;
        <a class="icon-btn custom-tooltip tooltipstered delete_row" href="JavaScript:void(0)" data-id="<?php echo $row->id; ?>" style="background-color: red;" title="Delete the row"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
      </td>
      <tr class="spacer"><td colspan="14"></td></tr>
    </tr>
<?php } ?>
<?php }else{ ?>
  <tr><td colspan="14" style="text-align:center;">No record found!</td></tr>
<?php } ?>