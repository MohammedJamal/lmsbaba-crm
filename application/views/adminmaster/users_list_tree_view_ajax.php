<div class="container">
  <h2>Users List </h2>
  <button style="float: left;" type="button" class="btn" id="show_table_view"><i class="fa fa-bars" aria-hidden="true"></i></button>

  <button style="float: left;" type="button" class="btn btn-info"><i class="fa fa-sitemap" aria-hidden="true"></i></button>

  <button style="float: right;" type="button" class="btn btn-success" id="add_user"><i class="fa fa-plus" aria-hidden="true"></i> Add User </button>
       
  
  

<div style="width:100%; padding:20px; float:left; text-align:left; border: 1px solid #eceeef;">

<?php if(count($rows)){ ?>

<script src="<?php echo assets_url(); ?>jqtree/dist/jstree.min.js"></script>
<link rel="stylesheet" href="<?php echo assets_url(); ?>jqtree/dist/themes/default/style.min.css" />

<div class="tree_clickable fa tree-down-arrow">
<span>
All Users List
</span>
</div>
<div class="clear"></div>
<div id="tree_div" style="display: block;font-size: 15px;">
    <div id="tree" class="border"></div>
</div>

<script type="text/javascript">
$(function() {
  var data =<?php echo json_encode($rows); ?>;
  $('#tree').jstree({     
    'core' : {
      "animation" : 500,
      'data' : data,
      "check_callback" : true,
    },   

    'plugins' : [ "types"],
    'types' : {
        'default' : {
            'icon' : 'fa fa-user fa-fw'
        }
        
    }   
    
    });



    $('#tree').bind("loaded.jstree", function(event, data) {
          $(this).jstree("open_all");
          $('[data-toggle="tooltip"]').tooltip({html: true});
    });
});
</script>

<?php } ?>

</div>


  
</div>

