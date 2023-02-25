<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <div class="lead-loop">
                <div class="lead-top">
                    <div class="mail-form-row max-width">
                        <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                                <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>            
            <div class="content-view">
                <div class="card process-sec">
                    <!-- <pre><?php //print_r($rows); ?></pre> -->
                    <div class="card-block vertical-style">
                        <h2 class="meeting-title-lg">Detail Report</h2>                        
                        <div class="wrapper1">
                            <div class="div1"></div>
                        </div>
                        <?php if($filter1=='calls'){ ?>
                            <!-- <pre><?php //print_r($rows); ?></pre> -->
                            <div class="table-toggle-holder mt-15">                            
                                <div class="table-one-holder-"> 
                                    <table class="table custom-table table-padding-10">
                                        
                                        <thead>
                                            <tr>
                                                <th scope="col" class=""></th> 
                                                <th scope="col" class="">Tagged Lead ID</th>
                                                <th scope="col" class="">Name</th>
                                                <th scope="col" class="">Contact Number</th>
                                                <th scope="col" class="">Call Type</th>
                                                <th scope="col" class="">Call Start</th>
                                                <th scope="col" class="">Call End</th>
                                                <th scope="col" class="" align="center">Total Call Time (H:m:s)</th>
                                                <th scope="col" class="">Assigned User</th>
                                                <th scope="col" class="">Comment</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dr_tcontent"></tbody>
                                    </table> 
                                </div>
                                <div class="card-block">                              
                                    <div class="row">
                                        <div id="dr_page_record_count_info" class="col-md-6 text-left ffff"></div>
                                        <div id="dr_page" style="" class="col-md-6 text-right custom-pagination"></div>
                                    </div> 
                                </div>
                            </div>
                        <?php }else{ ?>
                        <div class="table-toggle-holder mt-15">                            
                            <div class="table-one-holder-"> 
                                <table class="table custom-table table-padding-10">
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col" class="">Lead ID</th>
                                            <th scope="col" class="">Date</th>
                                            <th scope="col" class="">Source</th>
                                            <th scope="col" class="">Company</th>
                                            <th scope="col" class="">Contact Person</th>
                                            <th scope="col" class="">Stage</th>
                                            <th scope="col" class="">Assigned to</th>                                                
                                            <th scope="col" class="">Deal Value</th>
                                            <th scope="col" class="">Lead Title</th>
                                            <th scope="col" class=""></th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody id="dr_tcontent"></tbody>
                                </table> 
                            </div>
                            <div class="card-block">                              
                                <div class="row">
                                    <div id="dr_page_record_count_info" class="col-md-6 text-left ffff"></div>
                                    <div id="dr_page" style="" class="col-md-6 text-right custom-pagination"></div>
                                </div> 
                            </div>
                        </div> 
                        <?php } ?>                        
                    </div>
                    <input type="hidden" id="dr_page_number" value="1">
                    <input type="hidden" id="csv_filter_selected_user_id" value="<?php echo $filter_selected_user_id; ?>" />
                    <input type="hidden" id="csv_report" value="<?php echo $report; ?>" />
                    <input type="hidden" id="csv_filter1" value="<?php echo $filter1; ?>" />
                    <input type="hidden" id="csv_filter2" value="<?php echo $filter2; ?>" />
                    <input type="hidden" id="csv_filter_date_range_pre_define" value="<?php echo $filter_date_range_pre_define; ?>" />
                    <input type="hidden" id="csv_filter_date_range_user_define_from" value="<?php echo $filter_date_range_user_define_from; ?>" />
                    <input type="hidden" id="csv_filter_date_range_user_define_to" value="<?php echo $filter_date_range_user_define_to; ?>" />

                    <div class="card-block">   
                        <div class="row">
                           <div class="col-md-12">
                              <a class="new_filter_btn" href="JavaScript:void(0);" id="download_leads_csv">
                              <span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"/></span> Download Report  </a>
                           </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<script>  
$(document).ready(function(){
    rander_dr_list();    
});
</script>