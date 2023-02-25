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
                    <div class="card-block vertical-style">
                        <h3>Search</h3>
                        <div class="row">
                            <div class="col-md-3">                               
                                <div class="form-group">
                                    <label for="email">By Keyword:</label>
                                    <input type="text" class="form-control" id="by_keyword" placeholder="Location">
                                </div>
                            </div>
                            <div class="col-md-3">                               
                                <div class="form-group">
                                    <label for="email">By User:</label>
                                    <select class="form-control"  id="by_user_id">
                                        <option value="">==Select==</option>
                                        <?php if(count($user_list)){ ?>
                                            <?php foreach($user_list AS $user){ ?>
                                                <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">                               
                                <div class="form-group">
                                    <label for="email">By Status:</label>
                                    <select class="form-control"  id="status_id">
                                        <option value="">==Select==</option>
                                        <?php if(count($status_list)){ ?>
                                            <?php foreach($status_list AS $row){ ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">                               
                                <div class="form-group">
                                    <label for="email">By Mode:</label>
                                    <select class="form-control"  id="meeting_type">
                                        <option value="">==Select==</option>
                                            <option value="P">Visit</option>
                                            <option value="O">Online</option>
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-3">                               
                                <div class="form-group">
                                    <label for="email">By Meeting Type:</label>
                                    <select class="form-control"  id="meeting_agenda_type_id">
                                        <option value="">==Select==</option>
                                        <?php if(count($purpose_list)){ ?>
                                            <?php foreach($purpose_list AS $row){ ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">                               
                                <div class="form-group">
                                    <label for="email">By Visited With:</label>
                                    <select class="form-control"  id="self_visited_or_visited_with_colleagues">
                                        <option value="">==Select==</option>
                                        <option value="S">Self</option>
                                        <option value="C">Colleagues</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">                               
                                <div class="form-group">
                                    <label for="email">By Date:</label>
                                    <div class="row">
                                        <div class="col-md-6"><input type="text" class="form-control" id="by_keyword" placeholder="Location"></div>
                                        <div class="col-md-6"><input type="text" class="form-control" id="by_keyword" placeholder="Location"></div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-md-12">                               
                            <button type="button" class="btn btn-default pull-right" id="meeting_report_search_confirm">Search</button>
                            </div>                                    
                        </div>
                        <hr>
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.meeting_schedule_start_datetime" data-orderby="asc" >Meeting Date</th>
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.user_id" data-orderby="asc">Meeting By</th>
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.meeting_type" data-orderby="asc">Mode</th>
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.checkin_datetime" data-orderby="asc">Check-in</th>
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.checkout_datetime" data-orderby="asc">Check-out</th>
                                    <!-- <th scope="col" class="">Purpose</th> -->
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.meeting_with_at_checkin_time" data-orderby="asc">Meeting with</th>
                                    <!-- <th scope="col" class="" width="20%">Remarks</th> -->
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.status" data-orderby="asc">Status</th>
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.self_visited_or_visited_with_colleagues" data-orderby="asc">Visited with</th>
                                    <!-- <th scope="col" class="">Minutes of Meeting</th> -->
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.updated_at" data-orderby="asc">Meeting Updated on</th>
                                    <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.meeting_venue" data-orderby="asc">Location</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody id="tcontent"></tbody>
                        </table>  
                    </div>
                    <div class="card-block">                              
                        <div class="row">
                            <div id="page_record_count_info" class="col-md-6 text-left ffff"></div>
                            <div id="page" style="" class="col-md-6 text-right custom-pagination"></div>
                        </div>                
                        <div class="row">
                           <div class="col-md-12">
                              <a class="new_filter_btn" href="JavaScript:void(0);" id="download_meeting_report_csv">
                              <span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"/></span> Download Report  </a>
                           </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="page_number" value="1">
<input type="hidden" id="filter_sort_by" value="">
<input type="hidden" id="filter_by_keyword" value="">
<input type="hidden" id="filter_by_user_id" value="">
<script>  
$(document).ready(function(){
    rander_meeting_report();

    $("body").on("click","#meeting_report_search_confirm",function(e){
        var by_keyword=$("#by_keyword").val();
        var by_user_id=$("#by_user_id").val();
        $("#filter_by_keyword").val(by_keyword);
        $("#filter_by_user_id").val(by_user_id);
        rander_meeting_report();
    });
});
</script>