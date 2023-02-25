<?php if(count($rows)){ ?>
<?php
// $edit_permission=is_method_available('user','edit_employee');
$edit_permission=is_permission_available('edit_users_non_menu');
?>
<script src="<?php echo assets_url(); ?>jqtree/dist/jstree.min.js"></script>
<link rel="stylesheet" href="<?php echo assets_url(); ?>jqtree/dist/themes/default/style.min.css" />
<script type="text/javascript">
$(function() {
  var data =<?php echo json_encode($rows); ?>;
  $('#tree').jstree({     
    'core' : {
      "animation" : 500,
      'data' : data,
      "check_callback" : true,
    },   

    'plugins' : [ "types"<?php /* if($edit_permission==TRUE){ ?>,"dnd"<?php }*/ ?>],
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


    $('#tree').bind("move_node.jstree", function(e, data) {
        //data.node was dragged and dropped on data.parent
        //console.log(data);
        //alert(data.parent+'/'+data.node.id)
        var base_URL=$("#base_url").val();  
        var auto_id=data.node.id;
        var manager_auto_id=(data.parent=='#')?1:data.parent;
        var data="auto_id="+auto_id+"&manager_auto_id="+manager_auto_id;

        $.ajax({
            url:base_URL+"user/set_manager_ajax/",
                data: data,
                cache: false,
                method: 'GET',
                dataType: "html",
            beforeSend: function( xhr ) {
                //$("#preloader").css('display','block');
              },
            success:function(res){ 
               result = $.parseJSON(res);
               if(result.status=='success')
               {
                    swal({
                      type: 'success',
                      title: 'Manager level change',
                      text: 'Manager level successfully updated.',
                      footer: '',
                      showCancelButton: false,
                      confirmButtonClass: 'btn-warning',
                      confirmButtonText: "Ok",
                      closeOnConfirm: true,
                    }, function() {   
                      $("#view_type").val('tree');
                      //load_managerial_tree();                           
                      //window.location.href=base_URL+"user/manage_employee/t";
                        
                    });
               }
               else
               {
                  swal({
                        title: "Error!",
                        text: 'Manager is missing',
                        type: "error",
                        showCancelButton: false,
                        confirmButtonClass: 'btn-warning',
                        confirmButtonText: "Ok!",
                        closeOnConfirm: false
                    });
               }
            },
            complete: function(){
             //$("#preloader").css('display','none');
            },
            error: function(response) {
             //alert('Error'+response.table);
            }
       })
    });     

    $('#tree').bind("loaded.jstree", function(event, data) {
          $(this).jstree("open_all");
          $('[data-toggle="tooltip"]').tooltip({html: true});
    });
});
</script>
<div class="tree_clickable fa tree-down-arrow">
<span>
Tree View
</span>
</div>
<div class="clear"></div>
<div id="tree_div" style="display: block;font-size: 15px;">
    <div id="tree"></div>
</div>
<input type="hidden" id="manager_auto_id" value="">


<script type="text/javascript">
    $(document).ready(function(){
        
    });
</script>
<?php } ?>