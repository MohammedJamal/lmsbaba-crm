<?php if(count($rows)){ ?>
    <?php foreach($rows AS $row){ ?>
        <tr>
              <td>
                <?php 
                if($row['smtp_type']=='1'){
                  echo 'Hostinger(Default)';
                }
                else if($row['smtp_type']=='2'){
                  echo'Gmail';
                }
                else if($row['smtp_type']=='3'){
                  echo'Other';
                }
                
                ?>
              </td>
              <td><?php echo $row['username']; ?></td>
              <!-- <td><?php //echo $row['password']; ?></td> -->
              <td class="text-center">
                <div class="dashboard-right">
                  <div class="left text-danger">OFF</div>
                  <label class="switch_dashboard">
                    <input type="checkbox" name="is_mail_send_<?php echo $row['id']; ?>" id="is_active_<?php echo $row['id']; ?>"  class="smtp_update" data-id="<?php echo $row['id']; ?>" data-field="is_active" <?php echo ($row['is_active']=='Y')?'checked':''; ?>>
                    <span class="slider round"></span>
                  </label>
                  <div class="right text-success">ON</div>
                </div>
              </td>
              <td class="text-center" >
              <a href="JavaScript:void(0);" class="smtp_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>&nbsp;
              <?php if($row['is_default']=='N'){ ?>
                <a href="JavaScript:void(0);" class="smtp_delete icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
              <?php } ?>
              </td>
        </tr>
    <?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">No record found!</td>
    </tr>
<?php } ?>
