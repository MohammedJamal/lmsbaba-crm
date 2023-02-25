<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
<head>
<?php $this->load->view('adminmaster/includes/head'); ?> 
</head>
<body>
<div class="app full-width">
<div class="main-panel">            
<div class="main-content">              
    <div class="content-view"> 
        <div class="topnav">
            <?php $this->load->view('adminmaster/includes/top_menu'); ?>            
        </div>
    	<div class="card process-sec">
    			<div class="filter_holder new">
              <div class="pull-left">
                <h5 class="lead_board">  </h5>
              </div>
          	</div>
			<div class="form-group">
		    	<div class="col-sm-12 text-center">
                    <div class="container">
                        <h2 class="text-left"><?php echo $client_info->name; ?> - <?php echo $client_info->client_id; ?> <a href="<?php echo adminportal_url(); ?>client/detail/<?php echo $client_info->client_id; ?>" class="pull-right btn"><font style="font-size: 16px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</font> </a></h2>
                        <a href="<?php echo adminportal_url(); ?>client/manage_user_tag_service/<?php echo $client_info->client_id; ?>" id="" class="pull-right btn-primary">Tag Services</a>
                        <div style="clear:both;">&nbsp;</div>
                        
                        <div class="row">
							<div class="col-lg-12 col-md-12">
                            <div class="table-toggle-holder">
                                <div class="table-full-holder--">
                                    <div class="table-one-holder">  
                                        <table id="datatable" class="table dataTable table-expand-customer company-table" style="width: 100%">
                                            <thead>
                                                <tr>
                                                <th class="text-left sort_order asc" width="80" data-field="t1.id" data-orderby="desc">User Id</th>
                                                <th class="text-left sort_order" data-field="t1.name" data-orderby="desc">Name</th>
                                                <th class="text-left sort_order" data-field="t1.designation_id" data-orderby="desc">Designation</th>
                                                <th class="text-left sort_order" data-field="t1.department_id" data-orderby="desc">Department</th>
                                                <th class="text-left sort_order" data-field="t1.functional_area_id" data-orderby="desc">Functional Area</th>
                                                <th class="text-left sort_order" data-field="t1.manager_id" data-orderby="desc">Manager</th>
                                                <th class="text-left auto-show hide">Email</th>
                                                <th class="text-center">Action</th>
                                                
                                                </tr>
                                            </thead>
                                            <tbody id="tcontent">
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
                        
                            <div id="page" style="" class="pull-right"></div>
                            <input type="hidden" id="filter_sort_by" value="">
                            <input type="hidden" id="page_number" value="1">
                            <input type="hidden" id="client_id" value="<?php echo $client_info->client_id; ?>">  
                            </div>
                        </div>
                        
                                                
                        
                    </div>
                </div>
		  </div>
    	</div>                	 
    </div>                
</div>
<div class="content-footer">
  <?php $this->load->view('adminmaster/includes/footer'); ?>
</div>
</div>
</div>
</body>
</html>
<script>
$(document).ready(function(){
    load();
    
    $(document).on('click', '.myclass', function (e) { 
           e.preventDefault();
           var str = $(this).attr('href'); 
           var res = str.split("/");
           var cur_page = res[1];
           $("#page_number").val(cur_page);
           if(cur_page) {
                load(cur_page);
            }
            else {
                load(1);
            }
            
	});
    // AJAX SEARCH END
    
    $("body").on("click",".sort_order",function(e){
      var tmp_field=$(this).attr('data-field');
      var curr_orderby=$(this).attr('data-orderby');
      var new_orderby=(curr_orderby=='asc')?'desc':'asc';
      $(this).attr('data-orderby',new_orderby);
      $(".sort_order").removeClass('asc');
      $(".sort_order").removeClass('desc');
      $(this).addClass(curr_orderby);
      $("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
      load(1);
      //alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });
    // AJAX LOAD START
    function load() 
    {   
        //var page_num=page;
        var base_URL=$("#base_url").val();
        var client_id=$("#client_id").val();
        var filter_sort_by=$("#filter_sort_by").val();
        var page=$("#page_number").val();  
        var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&client_id="+client_id;
        // alert(data)
        $.ajax({
            url: base_URL+"client/get_user_list_ajax/"+page,
      			data: data,
      			cache: false,
      			method: 'GET',
      			dataType: "html",
             beforeSend: function( xhr ) {
                //$("#preloader").css('display','block');
              },
           success:function(res){ 
               result = $.parseJSON(res);
               //alert(result.table);
               $("#tcontent").html(result.table);
               $("#page").html(result.page);
               //$("#tcontent").accordion();
               //alert("okk");
           },
           complete: function(){
            //$("#preloader").css('display','none');
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
    }
    // AJAX LOAD END
});
</script>
