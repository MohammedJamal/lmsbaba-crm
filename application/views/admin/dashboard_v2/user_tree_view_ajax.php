<?php if(count($rows)){ ?>
<script src="<?php echo assets_url(); ?>jqtree/dist/jstree.min.js"></script>
<link rel="stylesheet" href="<?php echo assets_url(); ?>jqtree/dist/themes/default/style.min.css" />
<script type="text/javascript">
$(function() {
  var data =<?php echo json_encode($rows); ?>;
  $('#tree').jstree({     
    'core' : {
      "animation" : 500,
      'data' : data,
      "check_callback" : false      
    },   
    "checkbox" : {
      "keep_selected_style" : true,
      "class":'jstree-checked',
      "three_state": false
    },
    'plugins' : [ "types","checkbox"],
    'types' : {
        'default' : {
            'icon' : 'fa fa-user fa-fw'
        }
        // ,
        // 'f-open' : {
        //     'icon' : 'fa fa-folder-open fa-fw'
        // },
        // 'f-closed' : {
        //     'icon' : 'fa fa-folder fa-fw'
        // }
    }   
    
    });


  // $('#tree').bind("move_node.jstree", function(e, data) {        
  //     var base_URL=$("#base_url").val();  
  //     var auto_id=data.node.id;
  //     var manager_auto_id=(data.parent=='#')?1:data.parent;
  //     var data="auto_id="+auto_id+"&manager_auto_id="+manager_auto_id;
  //     $.ajax({
  //         url:base_URL+"user/set_manager_ajax/",
  //             data: data,
  //             cache: false,
  //             method: 'GET',
  //             dataType: "html",
  //       beforeSend: function( xhr ) {},
  //       success:function(res){ 
  //           result = $.parseJSON(res);
  //           if(result.status=='success')
  //           {
  //               swal({
  //                 type: 'success',
  //                 title: 'Manager level change',
  //                 text: 'Manager level successfully updated.',
  //                 footer: '',
  //                 showCancelButton: false,
  //                 confirmButtonClass: 'btn-warning',
  //                 confirmButtonText: "Ok",
  //                 closeOnConfirm: true,
  //               }, function() {   
  //                 $("#view_type").val('tree');                        
  //               });
  //           }
  //           else
  //           {
  //             swal({
  //                   title: "Error!",
  //                   text: 'Manager is missing',
  //                   type: "error",
  //                   showCancelButton: false,
  //                   confirmButtonClass: 'btn-warning',
  //                   confirmButtonText: "Ok!",
  //                   closeOnConfirm: false
  //               });
  //           }
  //       },
  //       complete: function(){},
  //       error: function(response) {}
  //     });
  // });     

  $('#tree').bind("loaded.jstree", function(event, data) {
      $(this).jstree("open_all");
      var userArr=[];
      var userNameArr=[];
      var selectedElms = $('#tree').jstree("get_selected", true);
      $.each(selectedElms, function() {
        userArr.push(this.id);
        userNameArr.push($("#"+this.id+"_anchor").attr('data-name'));
      });
      var userStr=userArr.join();
      var userNameStr=userNameArr.join(", ");
      $("#filter_selected_user_id").val(userStr);
      $("#select_div").slideToggle('fast');	
      //$("#report_applied_for_div").html(userNameStr); 
      $("#report_applied_for_div").html("All Users");        
  });

  $("body").on("click",".tree_clickable",function(e){
    $("#tree_div").slideToggle('fast',function() {
        if ($('#m_tree_div').is(':hidden'))
        {
            // $('.advance-view').css("background-color", "#2B509A");
        }
        else
        {          
          if (!$(".manager_tree_clickable").hasClass("m-tree-down-arrow")) {
            $("#m_tree_div").slideToggle('fast');
            $(".manager_tree_clickable").toggleClass("m-tree-down-arrow");
          }              
        }
    });
    $(this).toggleClass("tree-down-arrow");

    
    
  });
});
</script>
<div class="tree_clickable tree-down-arrow dal-green-style">
  <i class="fa fa-angle-down" aria-hidden="true"></i>User's Dashboard
</div>
<!-- <div class="clear"></div> -->
<div id="tree_div" style="display: none;font-size: 15px;">  
    <div class="w-100 text-right mb-10">
      <a href="JavaScript:void(0)" id="uncheck_all"><i class="fa fa-check-square-o" aria-hidden="true"></i> Check/ Uncheck All</a>
      <!-- <a href="JavaScript:void(0)" id="check_all">Check All</a> -->
    </div>
    <div id="tree"></div>  
    <div class="tree-footer">
      <button type="button" class="custom_blu btn btn-primary fload-left" id="user_checked_close">Close</button>
      <button type="button" class="custom_blu btn btn-primary" id="user_checked_submit">Submit</button>
      <button type="button" class="custom_blu btn btn-primary" id="user_checked_reset">Reset</button>
        
    </div>
     
</div>
<div class="clear"></div>
<!-- <input type="hidden" id="manager_auto_id" value=""> -->
<script type="text/javascript">
    $(document).ready(function(){
        $("body").on("click","#uncheck_all",function(e){
          $(this).attr("id","check_all");
          $('#tree').jstree(true).uncheck_all();
        });
        $("body").on("click","#check_all",function(e){
          $(this).attr("id","uncheck_all");
          $('#tree').jstree(true).check_all();
        });
    });
</script>
<?php } ?>