<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){?>
        <tr>
            <td align="center">
              <?php echo $row['whatsapp_name']; ?>              
            </td>
            <td align="center">                
                <div class="dashboard-right">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_whatsapp_send_<?php echo $row['id']; ?>" id="is_whatsapp_send_<?php echo $row['id']; ?>"  class="whatsapp_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_whatsapp_send" <?php echo ($row['is_whatsapp_send']=='Y')?'checked':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>
                <p style="color:#e97630; font-weight:bold;font-size:10px;">Default Template: 
                <a  href="JavaScript:void(0)" class="whatsapp_template_update" data-id="<?php echo $row['id']; ?>" data-field="default_template_id"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:12px;"></i></a>
                <?php if($row['default_template_id']){ ?>
                <a href="JavaScript:void(0)" class="whatsapp_template_view" id="whatsapp_template_view_<?php echo $row['id']; ?>_default_template_id" data-id="<?php echo $row['id']; ?>" data-field="default_template_id"><i class="fa fa-eye" aria-hidden="true" style="font-size:12px;"></i></a>
                <?php } ?>
              </p>
            </td> 
            <td align="center">
                <?php if($row['is_send_whatsapp_to_client']!='D'){ ?>
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_whatsapp_to_client_<?php echo $row['id']; ?>" id="is_send_whatsapp_to_client_<?php echo $row['id']; ?>"  class="whatsapp_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_whatsapp_to_client" <?php echo ($row['is_send_whatsapp_to_client']=='Y')?'checked':''; ?> <?php echo ($row['is_whatsapp_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div> 
                <p style="color:#e97630; font-weight:bold;font-size:10px;">Template: 
                  <a  href="JavaScript:void(0)" class="whatsapp_template_update" data-id="<?php echo $row['id']; ?>" data-field="send_whatsapp_to_client_template_id"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:12px;"></i></a>
                  <?php if($row['send_whatsapp_to_client_template_id']){ ?>
                  <a href="JavaScript:void(0)" class="whatsapp_template_view" id="whatsapp_template_view_<?php echo $row['id']; ?>_send_whatsapp_to_client_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_whatsapp_to_client_template_id"><i class="fa fa-eye" aria-hidden="true" style="font-size:12px;"></i></a>
                  <a href="JavaScript:void(0)" class="whatsapp_template_del" id="whatsapp_template_del_<?php echo $row['id']; ?>_send_whatsapp_to_client_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_whatsapp_to_client_template_id"><i class="fa fa-trash-o" aria-hidden="true" style="font-size:12px;color:red"></i></a>
                  <?php } ?>
                </p>             
                <?php }else{ ?>
                  Not Applicable
                <?php } ?>
            </td>
            <td align="center">
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_relationship_manager_<?php echo $row['id']; ?>" id="is_send_relationship_manager_<?php echo $row['id']; ?>"  class="whatsapp_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_relationship_manager" <?php echo ($row['is_send_relationship_manager']=='Y')?'checked':''; ?> <?php echo ($row['is_whatsapp_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>   
                <p style="color:#e97630; font-weight:bold;font-size:10px;">Template: 
                  <a  href="JavaScript:void(0)" class="whatsapp_template_update" data-id="<?php echo $row['id']; ?>" data-field="send_relationship_manager_template_id"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:12px;"></i></a>
                  <?php if($row['send_relationship_manager_template_id']){ ?>
                  <a href="JavaScript:void(0)" class="whatsapp_template_view" id="whatsapp_template_view_<?php echo $row['id']; ?>_send_relationship_manager_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_relationship_manager_template_id"><i class="fa fa-eye" aria-hidden="true" style="font-size:12px;"></i></a>
                  <a href="JavaScript:void(0)" class="whatsapp_template_del" id="whatsapp_template_del_<?php echo $row['id']; ?>_send_relationship_manager_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_relationship_manager_template_id"><i class="fa fa-trash-o" aria-hidden="true" style="font-size:12px;color:red"></i></a>
                  <?php } ?>
                </p>       
            </td> 
            <td align="center">
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_manager_<?php echo $row['id']; ?>" id="is_send_manager_<?php echo $row['id']; ?>"  class="whatsapp_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_manager" <?php echo ($row['is_send_manager']=='Y')?'checked':''; ?> <?php echo ($row['is_whatsapp_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>
                <p style="color:#e97630; font-weight:bold;font-size:10px;">Template: 
                  <a  href="JavaScript:void(0)" class="whatsapp_template_update" data-id="<?php echo $row['id']; ?>" data-field="send_manager_template_id"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:12px;"></i></a>
                  <?php if($row['send_manager_template_id']){ ?>
                  <a href="JavaScript:void(0)" class="whatsapp_template_view" id="whatsapp_template_view_<?php echo $row['id']; ?>_send_manager_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_manager_template_id"><i class="fa fa-eye" aria-hidden="true" style="font-size:12px;"></i></a>
                  <a href="JavaScript:void(0)" class="whatsapp_template_del" id="whatsapp_template_del_<?php echo $row['id']; ?>_send_manager_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_manager_template_id"><i class="fa fa-trash-o" aria-hidden="true" style="font-size:12px;color:red"></i></a>
                  <?php } ?>
                </p>  
            </td> 
            <td align="center">
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_skip_manager_<?php echo $row['id']; ?>" id="is_send_skip_manager_<?php echo $row['id']; ?>"  class="whatsapp_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_skip_manager" <?php echo ($row['is_send_skip_manager']=='Y')?'checked':''; ?> <?php echo ($row['is_whatsapp_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>
                <p style="color:#e97630; font-weight:bold;font-size:10px;">Template: 
                  <a  href="JavaScript:void(0)" class="whatsapp_template_update" data-id="<?php echo $row['id']; ?>" data-field="send_skip_manager_template_id"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:12px;"></i></a>
                  <?php if($row['send_skip_manager_template_id']){ ?>
                  <a href="JavaScript:void(0)" class="whatsapp_template_view" id="whatsapp_template_view_<?php echo $row['id']; ?>_send_skip_manager_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_skip_manager_template_id"><i class="fa fa-eye" aria-hidden="true" style="font-size:12px;"></i></a>
                  <a href="JavaScript:void(0)" class="whatsapp_template_del" id="whatsapp_template_del_<?php echo $row['id']; ?>_send_skip_manager_template_id" data-id="<?php echo $row['id']; ?>" data-field="send_skip_manager_template_id"><i class="fa fa-trash-o" aria-hidden="true" style="font-size:12px;color:red"></i></a>
                  <?php } ?>
                </p> 
            </td>            
        </tr>
    <?php } ?>
<?php }else{ ?>
    
<?php } ?>



