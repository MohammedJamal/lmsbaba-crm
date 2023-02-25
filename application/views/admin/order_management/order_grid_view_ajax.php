
<div class="sortable_main_container">
    <div class="sortable_table_container">
        <ul class="sortable_main_ul">            
            <?php if(count($active_stage_list)){ ?>
            <?php foreach($active_stage_list AS $stage_list){ ?>
                <?php
                $is_disable='Y';
                if(isset($stage_wise_assigned_users[$stage_list['id']])){
                    $assign_user_arr=explode(",",$stage_wise_assigned_users[$stage_list['id']]);
                    if(in_array($user_id,$assign_user_arr)){
                        $is_disable='N';
                    }
                }                
                ?>
            <li class="">
                <div class="sortable_head">
                    <h3><?php echo $stage_list['name']; ?></h3>
                    <div class="heading-info">
                        <div>Order Count <strong><?php echo $stage_list['stage_wise_pi_count']; ?></strong></div>
                        <div><a href="JavaScript:void(0);" class="rander_stage_wise_listing_popup" data-stage_name="<?php echo $stage_list['name']; ?>" data-stage_id="<?php echo $stage_list['id']; ?>"><i class="fa fa-search" aria-hidden="true"></i></a></div>
                    </div>
                </div>
                <div class="sortable_body" data-stage_id="<?php echo $stage_list['id']; ?>" id="sortable<?php echo $stage_list['id']; ?>" class="droptrue sortable_ul sortable_cancel---" data-id="<?php echo $stage_list['id']; ?>">
                <?php /*if(count($tagged_pi_list)){ ?>
                <?php foreach($tagged_pi_list AS $pi_list){ ?>
                <?php if($stage_list['id']==$pi_list['pi_stage_id']){ ?>

                    <?php
                    $stage_wise_form_submitted_validate='Y';
                    if($stage_list['form_wise_mandatory_str'])
                    {
                        $submitted_form_id_arr=explode(',',$pi_list['submitted_form_id']);
                        $form_wise_mandatory_arr=explode(",",$stage_list['form_wise_mandatory_str']);
                        foreach($form_wise_mandatory_arr AS $val)
                        {
                            $val_arr=explode("#",$val);
                            $fid=$val_arr[0];
                            $f_is_mandatory=$val_arr[1];
                            if($f_is_mandatory=='Y'){
                                if(!in_array($fid,$submitted_form_id_arr)){
                                    $stage_wise_form_submitted_validate='N';
                                    break;
                                }
                            }                            
                        }
                    }                    
                    ?>
                    
                    <div class="ui-state-default custom-white-card <?php echo ($is_disable=='Y')?'sortable_cancel':''; ?>  <?php echo ($stage_wise_form_submitted_validate=='N')?'form_validate_error':'' ?>" id="<?php echo $pi_list['id']; ?>" data-sid="<?php echo $stage_list['id']; ?>" data-pfi_split_id="<?php echo $pi_list['split_id']; ?>">
                        
                        <div class="sortable_order_detail <?php echo ($is_disable=='N')?'click-open-pop':''; ?>" data-lowp="<?php echo $pi_list['lead_opportunity_wise_po_id']; ?>" data-pfi="<?php echo $pi_list['id']; ?>" data-stageid="<?php echo $stage_list['id']; ?>" data-po_pi_wise_stage_tag_id="<?php echo $pi_list['om_po_pi_wise_stage_tag_id']; ?>" data-pfi_split_id="<?php echo $pi_list['split_id']; ?>">
                            <p><?php echo ($pi_list['lead_opportunity_wise_po_id'])?'<b>Order ID-</b> '.$pi_list['lead_opportunity_wise_po_id']:'';  ?></p>                            
                            <p><?php echo ($pi_list['po_date'])?'<b>Date-</b> '.date_db_format_to_display_format($pi_list['po_date']):''; ?></p>
                            <p>
                                <?php echo ($pi_list['cus_company_name'])?''.$pi_list['cus_company_name']:$pi_list['cus_contact_person']; ?>
                                <?php echo ($pi_list['cust_city_name'])?', '.$pi_list['cust_city_name']:''; ?>
                                <?php echo ($pi_list['cust_country_name'])?', '.$pi_list['cust_country_name']:''; ?>
                            </p>
                            <p><?php echo ($pi_list['expected_delivery_date']!='' && $pi_list['expected_delivery_date']!='0000-00-00')?'<b>Expt. Delivery</b>: '.date_db_format_to_display_format($pi_list['expected_delivery_date']):''; ?></p>
                            <p class="text-left text-red"><small><i><?php echo ($pi_list['split_id'])?'Split Order':''; ?></i></small></p>
                        </div>
                        
                        <div class="sortable_order_dot">
                            <ul class="d-block">
                                <li class="pb-5px">                                
                                    <a data-stage_id="<?php echo $stage_list['id']; ?>" class="text-center change_status_order <?php echo ($is_disable=='N')?'change_priority':''; ?> <?php echo ($pi_list['pi_priority']=='2')?'active':''; ?>" href="JavaScript:void(0);" data-po_pi_wise_stage_tag_id="<?php echo $pi_list['om_po_pi_wise_stage_tag_id']; ?>"  data-pfi_split_id="<?php echo $pi_list['split_id']; ?>">
                                        <i class="fa fa-star" aria-hidden="true"></i> 
                                    </a>
                                </li>
                                <?php if($pi_list['pi_stage_id']=='1'){ ?>
                                <li class="pb-5px">                                
                                    <a title="Split the order" class="<?php echo ($is_disable=='N')?'split_order':''; ?>" href="JavaScript:void(0);" data-lowp="<?php echo $pi_list['lead_opportunity_wise_po_id']; ?>" data-pfi="<?php echo $pi_list['id']; ?>" data-stageid="<?php echo $stage_list['id']; ?>" data-po_pi_wise_stage_tag_id="<?php echo $pi_list['om_po_pi_wise_stage_tag_id']; ?>" data-pfi_split_id="<?php echo $pi_list['split_id']; ?>">
                                        
                                        <span class="split-icon">
                                            <svg fill="#000000" width="18px" height="18px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                              <path d="M19.2928932,12 L14,12 L14,19.5 C14,19.7761424 13.7761424,20 13.5,20 C13.2238576,20 13,19.7761424 13,19.5 L13,3.5 C13,3.22385763 13.2238576,3 13.5,3 C13.7761424,3 14,3.22385763 14,3.5 L14,11 L19.2928932,11 L16.1464466,7.85355339 C15.9511845,7.65829124 15.9511845,7.34170876 16.1464466,7.14644661 C16.3417088,6.95118446 16.6582912,6.95118446 16.8535534,7.14644661 L20.8535534,11.1464466 C21.0488155,11.3417088 21.0488155,11.6582912 20.8535534,11.8535534 L16.8535534,15.8535534 C16.6582912,16.0488155 16.3417088,16.0488155 16.1464466,15.8535534 C15.9511845,15.6582912 15.9511845,15.3417088 16.1464466,15.1464466 L19.2928932,12 Z M4.70710678,11 L10,11 L10,3.5 C10,3.22385763 10.2238576,3 10.5,3 C10.7761424,3 11,3.22385763 11,3.5 L11,19.5 C11,19.7761424 10.7761424,20 10.5,20 C10.2238576,20 10,19.7761424 10,19.5 L10,12 L4.70710678,12 L7.85355339,15.1464466 C8.04881554,15.3417088 8.04881554,15.6582912 7.85355339,15.8535534 C7.65829124,16.0488155 7.34170876,16.0488155 7.14644661,15.8535534 L3.14644661,11.8535534 C2.95118446,11.6582912 2.95118446,11.3417088 3.14644661,11.1464466 L7.14644661,7.14644661 C7.34170876,6.95118446 7.65829124,6.95118446 7.85355339,7.14644661 C8.04881554,7.34170876 8.04881554,7.65829124 7.85355339,7.85355339 L4.70710678,11 Z"/>
                                            </svg>
                                        </span>
                                    </a>
                                </li>
                                <?php } ?>                                
                            </ul>                            
                        </div> 
                    </div>
                
                <?php } ?>
                <?php } ?>
                <?php }*/ ?>
                </div>
            </li>
            <input type="hidden" class="om_page_number_stage_wise" id="om_page_number_stage_wise<?php echo $stage_list['id']; ?>" data-stage_id="<?php echo $stage_list['id']; ?>" value="0">
            <input type="hidden" class="is_pagination_active" id="is_pagination_active<?php echo $stage_list['id']; ?>" data-stage_id="<?php echo $stage_list['id']; ?>" value="Y">
            <?php } ?>
            <?php } ?>
            
        </ul>

    </div>
</div>

   
<script>   
var wscroll = 0; 
jQuery(function($){
    
    
    $('.sortable_body').on('scroll', function() {
        // console.log('SCROLLING...');
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight-200) {
            // console.log('end reached');
            var sid=$(this).attr("data-stage_id"); 
            // console.log('sid: '+sid);
            if(wscroll == 0 && $("#is_pagination_active"+sid).val()=='Y'){
                wscroll = 1;
                load_order_stage_wise(sid);
            }
            
        }
    });

    // $('.sortable_body').on('scroll', function(){
    //     // console.log($(this).scrollTop()+$(this).innerHeight()+'>='+$(this)[0].scrollHeight)
    //     // console.log($(this).attr('data-id'))
    //     // console.log($(this).scrollTop()+$(this).innerHeight())
    //     var st = $(this).scrollTop(); 
    //     var sh = $(this)[0].scrollHeight;
    //     var ih = $(this).innerHeight();   
    //     console.log('SCROLLING...: '+st+', SH: '+sh);
    //     if ($(this).scrollTop()+$(this).innerHeight()==$(this)[0].scrollHeight){                
    //         var sid=$(this).attr("data-stage_id"); 
    //         console.log('SCROLL: '+sid);
    //         // if($("#is_pagination_active"+sid).val()=='Y'){
    //         //     load_order_stage_wise(sid);
    //         // }
            
    //     }
    // });
});
</script>

<?php  /* ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
  $( function() {
    
    $( ".sortable_body" ).sortable({        
        connectWith: ".sortable_body",
        update: function(event, ui ) {            
        },
        stop: function( event, ui) {            
            var current_stage_id=ui.item.parent().attr("data-id");
            var prev_stage_id=ui.item.attr("data-sid");
            var pi_id = ui.item.attr("id");  
            var pfi_split_id = ui.item.attr("data-pfi_split_id");
            fn_update_pi_stage(pi_id,current_stage_id,prev_stage_id,pfi_split_id);           
        }
    }); 
    
    $(".sortable_cancel").sortable({
        cancel: ".unsortable"
    }); 
    $(".form_validate_error").sortable({
        cancel: ".unsortable",
        stop: function( event, ui) {            
            swal('Oops','Please fill the mandatory order management forms to change the stage.','error');           
        }
    });     
  } );

   
  </script>
<?php */ ?>