<?php 
$stage_log_arr_tmp=array_unique(explode(',', ($row->stage_logs)?$row->stage_logs:1));
// $curr_stage_id=$lead_data->current_stage_id;
if($curr_stage_id=='2')
{
  $definite_articles = array();
  $stage_log_arr_tmp = array_diff($stage_log_arr_tmp, $definite_articles);
}

$stage_log_arr=$stage_log_arr_tmp;
?>
<?php if(count($like_stage_list)){ ?>
  <ul class="like-ul" id="">
  <?php 
  foreach($like_stage_list AS $stage){ 
    $is_checked=(in_array($stage['id'],$stage_log_arr))?'CHECKED':'';
    $is_disabled=(in_array($stage['id'],$stage_log_arr)==true)?'disabled':'';
    $is_sys_disabled=(in_array($stage['id'],$stage_log_arr)==true)?'sys_disabled_Y':'';

  ?>
  <li <?php if($stage['id']==1){echo'class="hide"';} ?>>
    <div class="cc-block">
      <label class="check-box-sec">
          <input type="checkbox" name="stage_id" id="stage_id_<?php echo $stage['id']; ?>" value="<?php echo $stage['id']; ?>" <?php echo $is_checked; ?> <?php echo $is_disabled; ?> class="<?php echo $is_sys_disabled; ?>" >
          <span class="checkmark"></span>
      </label>
      <?php echo $stage['name']; ?>        
    </div>
  </li>
  <?php } ?>
  </ul>
<?php } ?>
<input type="hidden" name="lid" id="lid" value="<?php echo $lead_id; ?>">
<input type="hidden" name="last_checked_stage" id="last_checked_stage" value="">
<script type="text/javascript">
$(document).ready(function(){
 
  $("#like_btn_confirm").attr("disabled",true);
  $("body").on("click",'input[name="stage_id"]',function(e){
    if ($(this).is(':checked')) {      
      $("#like_btn_confirm").attr("disabled",false);
    } else {
        $("#like_btn_confirm").attr("disabled",true); 
    }
  });
  var ckbox = $("input[name='stage_id']");
  var chkId = '';
  $('input').on('click', function() {    
      if (ckbox.is(':checked')) {       
        $("input[name='stage_id']").each ( function() {
            if($(this).is(':checked')==false)
            { 
              $(this).attr("disabled", true);
                            
            }
        });       
        
        if($(this).is(':checked')==true)
        {          
          $("#last_checked_stage").val($(this).val());
        }
        else
        {
          $("input[name='stage_id']").each ( function() {
              $(this).attr("disabled", false);                       
          });
          $("#last_checked_stage").val('');
        }
        
        $("input[name='stage_id']").each ( function() {            
            if($(this).hasClass( "sys_disabled_Y" ))
            {
              $(this).attr("disabled", true);
            }            
        });        
      }     
  });  
});
</script>