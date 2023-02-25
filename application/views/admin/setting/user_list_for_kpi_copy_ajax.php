<ul>
    <?php if(count($user_list)){ ?>
        <?php foreach($user_list AS $user){ ?>
            <li>
              <label class="check-box-sec radio">
                <input type="radio" value="<?php echo $user['user_id']; ?>" class="" name="copied_user"  id="copied_user_<?php echo $user['id']; ?>" data-kpi_setting_user_wise="<?php echo $user['id']; ?>">
                <span class="checkmark"></span>
              </label>
              <?php echo $user['user_name']; ?>
            </li>
        <?php } ?>
        <li>&nbsp;</li>
        <li><input type="button" class="btn btn-info " id="copy_kpi_confirm" data-to_uid="<?php echo $to_uid; ?>" value="Go"></li>
    <?php }else{ ?>
        <li>No user found with set KPI target</li>
    <?php } ?>
</ul>