<style>
.sortable_ul{ list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 143px;}
.sortable_ul li{ margin: 5px; padding: 5px; font-size: 1.2em; width: 120px; }
</style>

<ul id="sortable0" class="droptrue sortable_ul sortable_cancel" data-id="0">
    <li class="unsortable"><h3>Un-Stage<h3></li>
    <?php if(count($untagged_pi_list)){ ?>
        <?php foreach($untagged_pi_list AS $untagged_pi_list){ ?>            
            <li class="ui-state-default" id="<?php echo $untagged_pi_list['id']; ?>" data-sid="0">
                <p><?php echo ($untagged_pi_list['lead_opportunity_wise_po_id'])?'<b>Order ID-</b> #'.$untagged_pi_list['lead_opportunity_wise_po_id']:''; ?></p>
                <p><?php echo ($untagged_pi_list['po_number'])?'<b>PO No.- </b>'.$untagged_pi_list['po_number']:''; ?></p>
                <p><?php echo ($untagged_pi_list['po_date'])?'<b>PO Date-</b> '.date_db_format_to_display_format($untagged_pi_list['po_date']):''; ?></p>
                <p>
                    <?php echo ($untagged_pi_list['cus_company_name'])?''.$untagged_pi_list['cus_company_name']:$untagged_pi_list['cus_contact_person']; ?>
                    <?php echo ($untagged_pi_list['cust_city_name'])?', '.$untagged_pi_list['cust_city_name']:''; ?>
                    <?php echo ($untagged_pi_list['cust_country_name'])?', '.$untagged_pi_list['cust_country_name']:''; ?>
                </p>
            </li>
            
        <?php } ?>
    <?php } ?>
</ul>
<?php if(count($active_stage_list)){ ?>
    <?php foreach($active_stage_list AS $stage_list){ ?>       
        <ul id="sortable<?php echo $stage_list['id']; ?>" class="droptrue sortable_ul sortable_cancel" data-id="<?php echo $stage_list['id']; ?>">
            <li class="unsortable"><h3><?php echo $stage_list['name']; ?><h3></li>
            <?php if(count($tagged_pi_list)){ ?>
                <?php foreach($tagged_pi_list AS $pi_list){ ?>
                    <?php if($stage_list['id']==$pi_list['pi_stage_id']){ ?>
                    <li class="ui-state-default" id="<?php echo $pi_list['id']; ?>" data-sid="<?php echo $stage_list['id']; ?>">
                        
                            <p><a href="JavaScript:void(0)" class="get_om_detail" data-lowp="<?php echo $pi_list['lead_opportunity_wise_po_id']; ?>" data-pfi="<?php echo $pi_list['id']; ?>" data-stageid="<?php echo $stage_list['id']; ?>"><?php echo ($pi_list['lead_opportunity_wise_po_id'])?'<b>Order ID-</b> #'.$pi_list['lead_opportunity_wise_po_id']:''; ?></a></p>
                            <p><?php echo ($pi_list['po_number'])?'<b>PO No.-</b> '.$pi_list['po_number']:''; ?></p>
                            <p><?php echo ($pi_list['po_date'])?'<b>PO Date-</b> '.date_db_format_to_display_format($pi_list['po_date']):''; ?></p>
                            <p>
                                <?php echo ($pi_list['cus_company_name'])?''.$pi_list['cus_company_name']:$pi_list['cus_contact_person']; ?>
                                <?php echo ($pi_list['cust_city_name'])?', '.$pi_list['cust_city_name']:''; ?>
                                <?php echo ($pi_list['cust_country_name'])?', '.$pi_list['cust_country_name']:''; ?>
                            </p>
                        
                    </li>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </ul>
    <?php } ?>
<?php } ?>
<script>
  $( function() {
    $( ".sortable_ul" ).sortable({
        connectWith: "ul",
        update: function(event, ui ) {            
        },
        stop: function( event, ui) {            
            var current_stage_id=ui.item.parent().attr("data-id");
            var prev_stage_id=ui.item.attr("data-sid");
            var pi_id = ui.item.attr("id");            
            fn_update_pi_stage(pi_id,current_stage_id,prev_stage_id);           
        }
    }); 
    $(".sortable_cancel").sortable({
        cancel: ".unsortable"
    }); 
    
    $("body").on("click",".get_om_detail",function(e){
        var base_url=$("#base_url").val(); 
        var ThisObj=$(this);
        var lowp=ThisObj.attr("data-lowp");
        var pfi=ThisObj.attr("data-pfi");
        var stage_id=ThisObj.attr("data-stageid");
        $.ajax({
          url: base_url + "order_management/om_detail_view_rander_ajax",
          type: "POST",
          data: {
              'lowp': lowp,
              'pfi': pfi,
              'stage_id': stage_id
          },
          async: true,
          success: function(data) {
                result = $.parseJSON(data); 
                $('#OmDetailModalBody').html(result.html);
                $('#OmDetailModal').modal({
                        backdrop: 'static',
                        keyboard: false
                });

                // $("#common_view_modal_title_lg").text("Assign Form");
                // $('#rander_common_view_modal_html_lg').html(result.html);
                // $('#rander_common_view_modal_lg').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });
              
          },
          error: function() {
              swal({
                      title: 'Something went wrong there!',
                      text: '',
                      type: 'danger',
                      showCancelButton: false
                  }, function() {

              });
          }
        });
    });
  } );

    function fn_update_pi_stage(pi_id,current_stage_id,prev_stage_id)
    {
            var base_url = $("#base_url").val(); 
            var data = 'pi_id='+pi_id+"&current_stage_id="+current_stage_id+"&prev_stage_id="+prev_stage_id; 
            
            $.ajax({
            url: base_url+"order_management/pi_stage_change_update",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);  
                // console.log(result)   
                if(result.status=='success')
                {
                    load_order();
                    // load_om_stage_wise_user_assign_view(); 
                }
                
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
        });
    }
  </script>