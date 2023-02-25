<?php 
//$stage_log_arr=array_unique(explode(',', $row->stage_logs));
//print_r($stage_log_arr);
?>
<?php if(count($regret_reason_list)){ ?>
  <ul class="like-ul" id="">
  <?php foreach($regret_reason_list AS $reason){ ?>
    <li>
      <div class="cc-block">
        <label class="check-box-sec radio">
            <input type="radio" name="lead_regret_reason_id" value="<?php echo $reason['id']; ?>" data-text="<?php echo $reason['name']; ?>" data-lowp="<?php echo $lowp; ?>">
            <span class="checkmark"></span>
        </label>
        <?php echo $reason['name']; ?>     
      </div>
    </li>
  <?php } ?>
  </ul>
<?php } ?>