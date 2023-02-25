<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
            <td align="center"><?php echo $row['mail_name']; ?></td>
            <td align="center">
                
                <div class="dashboard-right">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_mail_send_<?php echo $row['id']; ?>" id="is_mail_send_<?php echo $row['id']; ?>"  class="email_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_mail_send" <?php echo ($row['is_mail_send']=='Y')?'checked':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>
                

                <!-- <ul class="employee_assign email_setting">                
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_mail_send_<?php echo $row['id']; ?>" id="is_mail_send_<?php echo $row['id']; ?>" value="Y" class="email_setting_update" <?php echo ($row['is_mail_send']=='Y')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_mail_send">
                      <span class="checkmark"></span>
                      ON
                    </label>                        
                  </li> 
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_mail_send_<?php echo $row['id']; ?>" id="is_mail_send_<?php echo $row['id']; ?>" value="N" class="email_setting_update" <?php echo ($row['is_mail_send']=='N')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_mail_send">
                      <span class="checkmark"></span>
                      OFF 
                    </label>                        
                  </li>
                </ul> -->
            </td> 
            <td align="center">
                <?php if($row['is_send_mail_to_client']!='D'){ ?>
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_mail_to_client_<?php echo $row['id']; ?>" id="is_send_mail_to_client_<?php echo $row['id']; ?>"  class="email_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_mail_to_client" <?php echo ($row['is_send_mail_to_client']=='Y')?'checked':''; ?> <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>

                <!-- <ul class="employee_assign email_setting">                
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_mail_to_client_<?php echo $row['id']; ?>" id="is_send_mail_to_client_<?php echo $row['id']; ?>" value="Y" class="email_setting_update" <?php echo ($row['is_send_mail_to_client']=='Y')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_mail_to_client" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      ON
                    </label>                        
                  </li> 
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_mail_to_client_<?php echo $row['id']; ?>" id="is_send_mail_to_client_<?php echo $row['id']; ?>" value="N" class="email_setting_update" <?php echo ($row['is_send_mail_to_client']=='N')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_mail_to_client" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      OFF 
                    </label>                        
                  </li>
                </ul> -->
                <?php }else{ ?>
                  Not Applicable
                <?php } ?>
            </td>
            <td align="center">
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_relationship_manager_<?php echo $row['id']; ?>" id="is_send_relationship_manager_<?php echo $row['id']; ?>"  class="email_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_relationship_manager" <?php echo ($row['is_send_relationship_manager']=='Y')?'checked':''; ?> <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>

                <!-- <ul class="employee_assign email_setting">                
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_relationship_manager_<?php echo $row['id']; ?>" id="is_send_relationship_manager_<?php echo $row['id']; ?>" value="Y" class="email_setting_update" <?php echo ($row['is_send_relationship_manager']=='Y')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_relationship_manager" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      ON
                    </label>                        
                  </li> 
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_relationship_manager_<?php echo $row['id']; ?>" id="is_send_relationship_manager_<?php echo $row['id']; ?>" value="N" class="email_setting_update" <?php echo ($row['is_send_relationship_manager']=='N')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_relationship_manager" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      OFF 
                    </label>                        
                  </li>
                </ul> -->
            </td> 
            <td align="center">
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_manager_<?php echo $row['id']; ?>" id="is_send_manager_<?php echo $row['id']; ?>"  class="email_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_manager" <?php echo ($row['is_send_manager']=='Y')?'checked':''; ?> <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>

                <!-- <ul class="employee_assign email_setting">                
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_manager_<?php echo $row['id']; ?>" id="is_send_manager_<?php echo $row['id']; ?>" value="Y" class="email_setting_update" <?php echo ($row['is_send_manager']=='Y')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_manager" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      ON
                    </label>                        
                  </li> 
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_manager_<?php echo $row['id']; ?>" id="is_send_manager_<?php echo $row['id']; ?>" value="N" class="email_setting_update" <?php echo ($row['is_send_manager']=='N')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_manager" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      OFF 
                    </label>                        
                  </li>
                </ul> -->
            </td> 
            <td align="center">
                <div class="dashboard-right ">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_send_skip_manager_<?php echo $row['id']; ?>" id="is_send_skip_manager_<?php echo $row['id']; ?>"  class="email_setting_update" data-id="<?php echo $row['id']; ?>" data-field="is_send_skip_manager" <?php echo ($row['is_send_skip_manager']=='Y')?'checked':''; ?> <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>

                <!-- <ul class="employee_assign email_setting">                
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_skip_manager_<?php echo $row['id']; ?>" id="is_send_skip_manager_<?php echo $row['id']; ?>" value="Y" class="email_setting_update" <?php echo ($row['is_send_skip_manager']=='Y')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_skip_manager" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      ON
                    </label>                        
                  </li> 
                  <li>
                    <label class="check-box-sec radio">
                      <input type="radio" name="is_send_skip_manager_<?php echo $row['id']; ?>" id="is_send_skip_manager_<?php echo $row['id']; ?>" value="N" class="email_setting_update" <?php echo ($row['is_send_skip_manager']=='N')?'checked':''; ?> data-id="<?php echo $row['id']; ?>" data-field="is_send_skip_manager" <?php echo ($row['is_mail_send']=='N')?'disabled':''; ?>>
                      <span class="checkmark"></span>
                      OFF 
                    </label>                        
                  </li>
                </ul> -->
            </td>            
        </tr>
    <?php } ?>
<?php }else{ ?>
    <!-- <tr>
        <td colspan="5">No record found!</td>
    </tr> -->
<?php } ?>
<tr>
    <td>Day Report</td>
    <td colspan="5">
      <div class="form-group row">
        <div class="col-sm-12">
          <label class="col-form-label">To Mail :</label>
          <input type="text" class="form-control " name="daily_report_tomail" id="daily_report_tomail" placeholder="" value="<?php echo $company_info['daily_report_tomail']; ?>" maxlength="255" >
          <span class="text-danger pull-right">**Max 5 email will be added.</span>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <label class="col-form-label">Mail Subject:</label>
          <input type="text" class="form-control " name="daily_report_mail_subject" id="daily_report_mail_subject" placeholder="" value="<?php echo $company_info['daily_report_mail_subject']; ?>" maxlength="255" >
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <label class="col-form-label">Active:</label>
          <div class="dashboard-right">
            <div class="left text-danger">OFF</div>
            <label class="switch_dashboard">
              <input type="checkbox" name="daily_report_email_send" id="daily_report_email_send"  class="" data-id="" data-field="" <?php echo ($company_info['is_daily_report_send']=='Y')?'checked':''; ?>>
              <span class="slider round"></span>
            </label>
            <div class="right text-success">ON</div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <a href="javascript:void(0)" class="btn btn-success" id="daily_report_email_send_update_confirm">Save</a>
        </div>
      </div>

      </div>
    </td>
</tr>


