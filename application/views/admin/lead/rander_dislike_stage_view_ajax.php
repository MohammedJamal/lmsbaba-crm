<?php if(count($regret_reason_list)){ ?>
  <ul class="like-ul" id="">
  <?php foreach($regret_reason_list AS $reason){ ?>
    <li>
      <div class="cc-block">
        <label class="check-box-sec radio">
            <input type="radio" name="lead_regret_reason_id" value="<?php echo $reason['id']; ?>" data-text="<?php echo $reason['name']; ?>" data-lid="<?php echo $lead_id; ?>">
            <span class="checkmark"></span>
        </label>
        <?php echo $reason['name']; ?>     
      </div>
    </li>
  <?php } ?>
  </ul>
<?php } ?>

<script type="text/javascript">
$(document).ready(function(){ 
  $("#dislike_btn_confirm").attr("disabled",true);
  $("body").on("click",'input[name="lead_regret_reason_id"]',function(e){
    if ($(this).is(':checked')) {      
      $("#dislike_btn_confirm").attr("disabled",false);
    } else {
        $("#dislike_btn_confirm").attr("disabled",true); 
    }
  });   
});
</script>