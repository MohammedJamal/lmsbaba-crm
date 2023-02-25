<?php if(count($rows)){ ?>
<script type="text/javascript">
$(function() {
  $("body").on("click",".manager_tree_clickable",function(e){
    $("#m_tree_div").slideToggle('fast',function() {
        if ($('#m_tree_div').is(':hidden'))
        {
            // $('.advance-view').css("background-color", "#2B509A");
        }
        else
        {
          if (!$(".tree_clickable").hasClass("tree-down-arrow")) {
            $("#tree_div").slideToggle('fast');
            $(".tree_clickable").toggleClass("tree-down-arrow");
          }          
        }
    });
    $(this).toggleClass("m-tree-down-arrow");
    
  });
});
</script>
<div class="manager_tree_clickable m-tree-down-arrow dal-green-style">
  <i class="fa fa-angle-down" aria-hidden="true"></i> Manager's Dashboard
</div>
<!-- <div class="clear"></div> -->
<div id="m_tree_div" style="display: none;font-size: 15px;">  
    <div class="w-100 text-right mb-10">
      <!-- <a href="JavaScript:void(0)" id="uncheck_all"><i class="fa fa-check-square-o" aria-hidden="true"></i> Check/ Uncheck All</a>       -->
    </div>
    <div id="m_tree">
      <ul style="line-height:28px;">
      <?php foreach($rows AS $row){ ?>
        <li><input type="radio" name="select_manager_id" value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?></li>
      <?php } ?>
      </ul>
    </div>  
    <div class="tree-footer">
      <button type="button" class="custom_blu btn btn-primary fload-left" id="manager_checked_close">Close</button>
      <button type="button" class="custom_blu btn btn-primary" id="manager_checked_submit">Submit</button>
      <button type="button" class="custom_blu btn btn-primary" id="user_checked_reset">Reset</button>
        
    </div>
     
</div>
<div class="clear"></div>
<input type="hidden" id="manager_and_user_users" />
<script type="text/javascript">
    $(document).ready(function(){
        // $("body").on("click","#uncheck_all",function(e){
        //   $(this).attr("id","check_all");
        //   $('#m_tree').jstree(true).uncheck_all();
        // });
        // $("body").on("click","#check_all",function(e){
        //   $(this).attr("id","uncheck_all");
        //   $('#m_tree').jstree(true).check_all();
        // });

        $('input:radio[name="select_manager_id"]').change(function(){            
              var selected_mid=$(this).val();              
              var base_URL=$("#base_url").val();	             
              var data = "selected_mid="+selected_mid;              
              $.ajax({
                  url: base_URL+"dashboard_v2/get_users_by_managerId/",
                  data: data,
                  cache: false,
                  method: 'GET',
                  dataType: "html",
                  beforeSend: function( xhr ) {                
                    //showContentLoader('#dashboard_summery_count_div', 'loading data...');
                    $("#manager_checked_submit").attr("disabled",true);
                    $("#m_tree_div").addClass('logo-loader'); 
                  },
                  success:function(res){ 
                    result = $.parseJSON(res);	
                    // alert(result.users);
                    $("#manager_and_user_users").val(result.users);
                    
                  },
                  complete: function(){
                    //removeContentLoader('#dashboard_summery_count_div');
                    $("#manager_checked_submit").attr("disabled",false);
                    $('#m_tree_div').removeClass('logo-loader');
                  },
                  error: function(response) {
                    //alert('Error'+response.table);
                  }
                })
        });
    });
</script>
<?php } ?>