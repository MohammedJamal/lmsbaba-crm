<?php
if(count($rows)>0)
{
    $i=1;
    foreach($rows as $row) 
    { 
?>
    <tr id="">
          <td style="width: 24px;"><?php echo $i; ?></td>                        
          <td style="width:160px;"><?php echo $row['name']; ?></td>
          <td style="width:76px;">
            <div class="form-group col-md-12 padding0">
              <!-- <input type="text" class="form-control" id="" name="" value="<?php echo $row['price']; ?>" onkeyup="" readonly="true"> -->
              <?php echo $row['price']; ?>
            </div>
          </td>

          <td style="width: 76px;">
                  <!-- <select id="" name="" class="form-control" onchange="">
                    <?php foreach($currency_list as $curr){ ?>
                    <option value="<?php echo $curr->id; ?>" <?php if($row['currency_type']==$curr->id){echo'SELECTED';} ?>><?php echo $curr->code; ?></option>
                    <?php } ?>
                  </select> -->
                  <?php foreach($currency_list as $curr){ ?>
                  <?php if($row['currency_type']==$curr->id){$curr_code = $curr->code;} ?>
                  <?php } ?>
                  <!-- <input type="text" class="form-control" id="" name="" value="<?php echo $curr_code; ?>" onkeyup="" readonly="true">-->
                  <?php echo $curr_code; ?>                    
          </td>                        
          <td style="width:146px;">
              <select id="delivery_time_<?php echo $row['tag_auto_id']; ?>" name="" class="form-control" onchange="" style="width:35%; float:left;">
                  <?php for($dt=1;$dt<=12;$dt++){ ?>
                  <option value="<?php echo $dt; ?>" <?php if($row['delivery_time']==$dt){echo'SELECTED';} ?>><?php echo $dt; ?></option>
                  <?php } ?>                  
              </select>
              <select id="delivery_time_unit_<?php echo $row['tag_auto_id']; ?>" name="" class="form-control" onchange="" style="width:62%; float:right;">
                  <option value="D" <?php if($row['delivery_time_unit']=='D'){echo'SELECTED';} ?>>Days</option>
                  <option value="M" <?php if($row['delivery_time_unit']=='M'){echo'SELECTED';} ?>>Months</option>
                  <option value="Y" <?php if($row['delivery_time_unit']=='Y'){echo'SELECTED';} ?>>Years</option>
              </select>
          </td>
                              
          <td style="width:32px;">
            <a href="JavaScript:void(0);" data-id="<?php echo $row['tag_auto_id']; ?>" class="vendor_tag_save"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
            <a href="JavaScript:void(0);" data-id="<?php echo $row['tag_auto_id']; ?>" class="vendor_tag_delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
          </td>
      </tr>
<?php 
    $i++;
    } 
}
else
{
    echo'<tr><td colspan="6" align="center">No Record Found..</td></tr>';
}
?>