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
                        <h2 class="meeting-title-lg">
                            Meeting Report
                            
                        </h2>
                        <div class="grey-search-box">
                            <ul>
                                <li>
                                    <label>SEARCH</label>
                                    <input type="text" class="form-control" id="meeting_by_keyword" placeholder="Lead ID/ Co. Name, Email, Mobile">
                                </li>
                                <li>
                                    <label>BY USER</label>
                                    <select class="form-control"  id="meeting_by_user_id">
                                        <option value="">==Select==</option>
                                        <?php if(count($user_list)){ ?>
                                            <?php foreach($user_list AS $user){ ?>
                                                <option value="<?php echo $user->id; ?>" <?php if($meeting_user_id==$user->id){echo'SELECTED';}?>><?php echo $user->name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </li>
                                <li>
                                    <label>BY STATUS</label>
                                    <select class="form-control"  id="meeting_by_status_id">
                                        <option value="">==Select==</option>
                                        <?php if(count($status_list)){ ?>
                                            <?php foreach($status_list AS $row){ ?>
                                                <option value="<?php echo $row['id']; ?>" <?php if($meeting_status==$row['id']){ echo'SELECTED';}?>><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </li>
                                <li>
                                    <label>BY MODE</label>
                                    <select class="form-control"  id="meeting_by_meeting_type">
                                        <option value="">==Select==</option>
                                        <option value="P">Visit</option>
                                        <option value="O">Online</option>
                                    </select>
                                </li>
                                <li>
                                    <label>BY MEETING TYPE</label>
                                    <select class="form-control"  id="meeting_by_meeting_agenda_type_id">
                                        <option value="">==Select==</option>
                                        <?php if(count($purpose_list)){ ?>
                                            <?php foreach($purpose_list AS $row){ ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </li>
                                <li>
                                    <label>BY VISITED WITH</label>
                                    <select class="form-control"  id="meeting_by_self_visited_or_visited_with_colleagues">
                                        <option value="">==Select==</option>
                                        <option value="S">Self</option>
                                        <option value="C">With colleagues</option>
                                    </select>
                                </li>
                                <li>
                                    <label>BY DATE</label>
                                    <input type="text" class="form-control date_input" id="meeting_by_start_date" placeholder="Start date" autocomplete="off" value="<?php echo $meeting_checkin_date;?>">
                                        
                                </li>
                                <li>
                                    <label>&nbsp;</label>
                                    <input type="text" class="form-control date_input" id="meeting_by_end_date" placeholder="End date" autocomplete="off" value="<?php echo $meeting_checkout_date;?>">
                                    
                                </li>
                                <li>
                                    <button type="button" class="btn btn-blue w-100 mt-21 btn-h-42" id="meeting_report_search_confirm">Search</button>
                                </li>
                            </ul>
                        </div>
                        <h2 class="meeting-title-lg">
                            Meeting Report
                            <a href="#" class="ext-table-new">
                                <svg aria-hidden="true" role="img" class="octicon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom;"><path fill-rule="evenodd" d="M8.177 14.323l2.896-2.896a.25.25 0 00-.177-.427H8.75V7.764a.75.75 0 10-1.5 0V11H5.104a.25.25 0 00-.177.427l2.896 2.896a.25.25 0 00.354 0zM2.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM6 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM12 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zm2.25.75a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5z"></path></svg>
                            </a>
                        </h2>
                        <div class="wrapper1">
                            <div class="div1"></div>
                        </div>
                        <div class="table-toggle-holder mt-15">
                            <div class="table-full-holder">
                                <div class="table-one-holder"> 

                                    <table class="table custom-table table-padding-10">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.lead_id" data-orderby="asc">Lead ID</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.status" data-orderby="asc">Lead Stage</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.status" data-orderby="asc">Meeting Status</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.meeting_schedule_start_datetime" data-orderby="asc" >Meeting Date</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t2.company_name" data-orderby="asc">Company</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.user_id" data-orderby="asc">Meeting By</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.meeting_type" data-orderby="asc">Mode</th>
                                                
                                                <th scope="col" class="sort_order_meeting_report sort_order_css auto-show hide" data-field="t1.checkin_datetime" data-orderby="asc">Check-in</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css auto-show hide" data-field="t1.checkout_datetime" data-orderby="asc">Check-out</th>
                                                <th scope="col" class="">Purpose</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css auto-show hide" data-field="t1.meeting_with_at_checkin_time" data-orderby="asc">Meeting with</th>
                                                
                                                <th scope="col" class="" width="20%">Remarks</th>                                    
                                                <th scope="col" class="sort_order_meeting_report sort_order_css auto-show hide" data-field="t1.self_visited_or_visited_with_colleagues" data-orderby="asc">Visited with</th>
                                                <th scope="col" class="">Disposition Comments</th>
                                                <th scope="col" class="sort_order_meeting_report sort_order_css auto-show hide" data-field="t1.updated_at" data-orderby="asc">Updated on</th>
                                                <!-- <th scope="col" class="sort_order_meeting_report sort_order_css" data-field="t1.meeting_venue" data-orderby="asc">Location</th> -->
                                            </tr>
                                            </tr>
                                        </thead>
                                        <tbody id="meeting_tcontent">
                                            
                                        </tbody>
                                    </table> 

                                </div>
                            </div>
                        </div>
                         
                    </div>
                    <div class="card-block">                              
                        <div class="row">
                            <div id="meeting_page_record_count_info" class="col-md-6 text-left ffff"></div>
                            <div id="meeting_page" style="" class="col-md-6 text-right custom-pagination"></div>
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
<input type="hidden" id="meeting_page_number" value="1">
<input type="hidden" id="meeting_filter_sort_by" value="">
<input type="hidden" id="meeting_filter_by_lead_id" value="<?php echo $lead_id; ?>">
<input type="hidden" id="meeting_filter_by_keyword" value="">
<input type="hidden" id="meeting_filter_by_user_id" value="<?php echo $meeting_user_id; ?>">
<input type="hidden" id="meeting_filter_by_status_id" value="<?php echo $meeting_status; ?>">
<input type="hidden" id="meeting_filter_by_meeting_type" value="">
<input type="hidden" id="meeting_filter_by_meeting_agenda_type_id" value="">
<input type="hidden" id="meeting_filter_by_self_visited_or_visited_with_colleagues" value="">
<input type="hidden" id="meeting_filter_by_start_date" value="<?php echo $meeting_checkin_date;?>">
<input type="hidden" id="meeting_filter_by_end_date" value="<?php echo $meeting_checkout_date;?>">

<script>  
$(document).ready(function(){
    rander_meeting_report();

    ///////////////////////////////
    var getPw = $('.table-responsive-holder').innerWidth();
  //alert(pw);
    var fwidth = 1600;
    var wArray = [7,8,5,5,6,7,4,6,6,8,5,6,5,5,4,9]
    //alert(getPw);
    var parentW = getPw+(getPw/2);
    //$('.wrapper1 .div1').css({'width':parentW});
    //$('.table-full-holder').css({'width':getPw});
    $('.wrapper1 .div1').css({'width':fwidth});
    //$('.table-full-holder').css({'width':'1600px'});
    //$('.table-one-holder').css({'width':getPw});
    //$('.table-two-holder').css({'width':getPw/2});
    $(document).on("click","#meetingReport .ext-table-new",function(event) {
      event.preventDefault();
      //alert('ext-table-new');
      //var getW = window.innerWidth;
      //alert("click: "+getW);
      if ($(this).hasClass('active')) {
        //alert(1);
        $(this).removeClass('active');
        $('.wrapper1').hide();
        //$(this).find('.fa').removeClass('fa-long-arrow-left').addClass('fa-long-arrow-right');
        $('.table-toggle-holder').find('.auto-show').addClass('hide');
          $('.table-full-holder').css({'width':'100%'});
          $('.table-toggle-holder').removeClass('scroll');
        $(".wrapper1")
            .scrollLeft(0);
        
      }else{
        //alert(2);
        $(this).addClass('active');
        $('.wrapper1').show();
        $('.table-full-holder').css({'width':fwidth});
        //$(this).find('.fa').removeClass('fa-long-arrow-right').addClass('fa-long-arrow-left');
        $('.table-toggle-holder').find('.auto-show').removeClass('hide');
        /////////////////////////////////////////
        $('table.dataTable.new-table-style thead > tr > th').each(function( index ) {
          var getWid = getPercentW(wArray[index]);
          //console.log(index+'> '+wArray[index])
          //$(this).attr('width', getWid);
      });
        /////////////////////////////////////////
        //$('.table-full-holder').css({'width':parentW});
        $('.table-toggle-holder').addClass('scroll');
        $('.table-toggle-holder, .wrapper1').stop( true, true ).
            animate({
              scrollLeft: fwidth
            }, 500, function() {
              //$('.media-grid-child').addClass('scroll-active');
            });
      }
      
      
      //$('.table-toggle-holder').scrollLeft(parentW);;
  });
    ///////
    $(".wrapper1").scroll(function(){
        $(".table-toggle-holder")
            .scrollLeft($(".wrapper1").scrollLeft());
    });
    $(".table-toggle-holder").scroll(function(){
        $(".wrapper1")
            .scrollLeft($(".table-toggle-holder").scrollLeft());
    });
    ///////
  getPercentW = function(per){
      // var getPN = $('.table-responsive-holder').innerWidth();
      // var fixW = (getPN/100)*per;
      // return fixW;
    }
  $(document).on("click",".ext-table-new2",function(event) {
      event.preventDefault();
     $("div").scrollLeft(100);      
  });
      ///////////////////////////////

    $('.date_input').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5'
    });
});
</script>