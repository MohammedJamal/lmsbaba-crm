<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
<head>
    <?php $this->load->view('adminmaster/includes/head'); ?> 
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script> -->
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
                        <h2 class="text-left"><?php echo $client_info->name; ?> - <?php echo $client_info->client_id; ?> <a href="<?php echo adminportal_url(); ?>client/manage_user/<?php echo $client_info->client_id; ?>" class="pull-right btn"><font style="font-size: 16px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</font> </a></h2>

                        <div style="clear:both;">&nbsp;</div>
                        
                        <div class="row">
							<div class="col-lg-12 col-md-12">
                            <div class="no-more-tables"> 
                                <div class="row">
                                    <div class="col-sm-3 text-left">
                                    <label class="col-form-label "><b>Select Service:</b></label>
                                    <select class="custom-select form-control" name="service" id="service">
                                        <option value="">Select</option>
                                        <?php if(count($all_available_service)){ ?>
                                            <?php foreach($all_available_service AS $service){ ?>
                                                <option value="<?php echo $service['service_id'] ?>"><?php echo $service['service_name'] ?></option>
                                            <?php } ?>
                                        <?php } ?>                                 
                                    </select>
                                    </div>
                                </div>
                                <br>
                                <div id="rander_html"></div>                          
                            </div>
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
<input type="hidden" id="client_id" value="<?php echo $client_id; ?>" />
<script>
$(document).ready(function(){
    $("body").on("change","#service",function(e){
        var s_id=$(this).val();
        rander_service_list_ajax(s_id);        
    });
});
    function checkDropMove(){      
      $( ".sp-drop" ).each(function() {
        var getLength = $( this ).find('.ul-drop-target > li').length;        
        if(getLength == 0){
          var ht = `<div class="showDrag">
                      <span>
                          <img src="<?=assets_url();?>images/drag-icon.png">
                      </span> Drag here...
                    </div>`;
          $(this).append(ht);
        }else{
          $(this).find('.showDrag').remove();
        }        
      });
    }
    function rander_service_list_ajax(s_id)
    {
            var base_URL=$("#base_url").val(); 
            var client_id=$("#client_id").val();    
            var data = "s_id="+s_id+"&client_id="+client_id; 
               
               
            $.ajax({
                url: base_URL+"client/rander_service_list_ajax/",
                data: data,
                cache: false,
                method: 'GET',
                dataType: "html",
                beforeSend: function( xhr ) { 
                $.blockUI({ 
                    message: 'Please wait...', 
                    css: { 
                        padding: '10px', 
                        backgroundColor: '#fff', 
                        border:'0px solid #000',
                        '-webkit-border-radius': '10px', 
                        '-moz-border-radius': '10px', 
                        opacity: .5, 
                        color: '#000',
                        width:'450px',
                        'font-size':'14px'
                        }
                });
                },
                complete: function(){
                $.unblockUI();
                },
                success:function(res){ 
                result = $.parseJSON(res);             
                $("#rander_html").html(result.html);
                $(".sortable-ul").sortable({
                    connectWith: "ul",
                    dropOnEmpty: true,
                    cursor: "move",
                    start: function(e, ui) {
                    },
                    change: function( event, ui ) {
                    },
                    update: function( event, ui ) {
                    },
                    stop: function( event, ui ) {
                    },
                    activate: function( event, ui ) {          
                    },
                    create: function( event, ui ) {          
                    },
                    deactivate: function( event, ui ) {          
                    },
                    receive: function( event, ui ) {                      
                        // var gId = event.target.id;                      
                        // var getLength = $('#'+gId+' > li').length;                      
                        checkDropMove();                      
                        var id = ui.item.attr("id");
                        var sourceList = ui.sender;
                        var targetList = $(this);
                        var service_order_detail_id_to=targetList[0].dataset.service_order_detail_id;
                        var service_order_detail_id_from=sourceList[0].dataset.service_order_detail_id;            
                        var base_URL = $("#base_url").val();
                        var data="user_id="+id+"&sod_id_to="+service_order_detail_id_to+"&sod_id_from="+service_order_detail_id_from+"&client_id="+client_id; 
                        if(service_order_detail_id_to=='' && id==1)
                        {
                            swal({
                                title: "Oops!",
                                text: 'Admin should be tagged with minimum one service.',
                                type: "error",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                // location.reload(true);                                    
                                rander_service_list_ajax(s_id);                             
                            }); 
                        }  
                        else
                        {
                            $.ajax({
                                url: base_URL+"client/tag_user_wise_service",
                                data: data,
                                cache: false,
                                method: 'POST',
                                dataType: "html",
                                beforeSend: function( xhr ) { 
                                    $.blockUI({ 
                                        message: 'Please wait...', 
                                        css: { 
                                        padding: '10px', 
                                        backgroundColor: '#fff', 
                                        border:'0px solid #000',
                                        '-webkit-border-radius': '10px', 
                                        '-moz-border-radius': '10px', 
                                        opacity: .5, 
                                        color: '#000',
                                        width:'450px',
                                        'font-size':'14px'
                                        }
                                    });                        
                                },
                                complete: function(){
                                    $.unblockUI();
                                },
                                success: function(data){
                                    result=$.parseJSON(data);                       
                                    if(result.status=='success'){                       
                                    }     
                                    else
                                    {
                                        swal({
                                            title: "Oops!",
                                            text: result.msg,
                                            type: "error",
                                            confirmButtonText: "ok",
                                            allowOutsideClick: "false"
                                        }, function () { 
                                            // location.reload(true);                                    
                                            rander_service_list_ajax(s_id);
                                        }); 
                                    }
                                                    
                                }              
                            });
                        }     
                        
                    }
                    });
            },       
            error: function(response) {}
        });      
    }
</script>
