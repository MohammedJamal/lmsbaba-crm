<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
<head>
 <?php $this->load->view('admin/includes/head'); ?>
 <link rel="stylesheet" href="<?php echo assets_url() ?>css/progres-bar.css">
 <script src="<?php echo assets_url() ?>js/player.js"></script>
  <link rel="stylesheet" href="<?php echo assets_url() ?>css/picker.css">
  <link  href="<?php echo assets_url() ?>css/hotel-datepicker.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo assets_url() ?>css/dashboard_style.css"/>
  <link rel="stylesheet" href="<?php echo assets_url() ?>css/dashboard_v2.css"/> <!--FOR NEW DASHBOARD-->
</head>
<body>
<div class="app full-width expanding">    
      <div class="off-canvas-overlay" data-toggle="sidebar"></div>
      <div class="sidebar-panel">       
        <?php $this->load->view('admin/includes/left-sidebar'); ?>
      </div> 
      <div class="app horizontal top_hader_dashboard">
            <?php $this->load->view('admin/includes/header'); ?>
      </div>      

    <div class="main-panel">         
        <div class="min_height_dashboard"></div>         
        <div class="main-content">  
          <div class="content-view">
            <input type="hidden" id="filter_selected_user_id" value="<?php echo $selected_user_id; ?>">
            <input type="hidden" id="filter_summery_count_or_percentage" value="C">
            <input type="hidden" id="filter_leads_vs_orders_report_group_by" value="M">
            <input type="hidden" id="filter_daily_sales_report_group_by" value="D">
            <input type="hidden" id="filter_business_report_weekly_period" value="<?php echo date("Y-m"); ?>">
            <input type="hidden" id="filter_business_report_monthly_period" value="<?php echo date("Y"); ?>">   

            <div class="white-box">         
              <h2 class="dashboard-title">
                <div class="full-left">
                  <div class="pull-left">
                    <div class="dashboard_nav_menu">
                      <a href="javascript:;" data-toggle="sidebar" class="toggle-offscreen">
                        <i class="material-icons">menu</i>
                      </a>
                    
                    </div>
                    DASHBOARD
                  </div>
                  <div class="pull-left tree-holder">                    
                    <div id="" class="default-scoller">                      
                      <?php echo $user_list_treeview; ?>            
                    </div>                  
                  </div>


                  <div class="pull-right mobile-full">
                    <div class="dashboard-right">
                      <div class="left">Number</div>
                      <label class="switch_dashboard">
                        <input type="checkbox" name="is_count_or_percentage" id="is_count_or_percentage">
                        <span class="slider round"></span>
                      </label>
                      <div class="right">Percent</div>
                    </div>
                  </div>
                </div>
                <div class="full_selected_filter_div" style="display: block;"><span>Report For:</span> <font id="report_applied_for_div"></font></div>
              </h2>
              <div class="pai-holder small-pai" id="dashboard_summery_count_div"></div>
            </div>

            
            <!-- new dashboard start -->
            
               <div class="row mb-30">
                  <div class="col-md-4">
                     <div class="white-box pa-0">
                        <div class="w-new-header text-uppercase justify-content-between">
                           This Month
                           <a href="#" class="cal-icon-right"><img src="<?php echo assets_url() ?>images/new-images/cal-icon-right.png"></a>
                        </div>
                        <div class="w-new-body same-height position-relative" id="show_this_month_data">
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-md-4">
                     <div class="white-box pa-0">
                        <div class="w-new-header text-uppercase justify-content-between">
                           Lead Pipeline
                        </div>
                        <div class="w-new-body same-height position-relative" id="show_lead_pipeline_data">
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-md-4">
                     <div class="white-box pa-0">
                        <div class="w-new-header text-uppercase justify-content-between">
                           Opportunity
                        </div>
                        <div class="w-new-body same-height position-relative" id="show_opportunity_data">
                        </div>
                     </div>
                  </div>
               </div>





        </div>
        <div class="content-footer">
          <?php $this->load->view('admin/includes/footer'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('admin/includes/modal-html'); ?>
<?php $this->load->view('admin/includes/app.php'); ?> 

<!-- Modal -->
<div class="modal fade clock-modal-new" id="c2c_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Call Report</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body" id="c2c_modal_body"></div>
      </div>
   </div>
</div>

</body>
</html>

<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.nice-select.js"></script>
<!-- <script src="<?php echo assets_url();?>js/jquery.blockUI.js"></script>
<script src="<?php echo assets_url(); ?>plugins/jquery-ui/jquery-ui.js"></script> -->
<script src="<?php echo assets_url();?>js/jquery.matchHeight-min.js"></script>
<script src="<?php echo assets_url();?>js/custom/dashboard/report_v2.js?v=<?php echo rand(0,1000); ?>"></script>
<link rel="stylesheet" href="<?php echo assets_url();?>css/jquery.mCustomScrollbar.css">
<script src="<?php echo assets_url();?>js/jquery.mCustomScrollbar.concat.min.js"></script>
<link rel="stylesheet" href="<?php echo assets_url();?>css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo assets_url();?>css/rowReorder.dataTables.min.css">
<link rel="stylesheet" href="<?php echo assets_url();?>css/responsive.dataTables.min.css">
<script src="<?php echo assets_url();?>js/jquery.dataTables.min.js"></script>
<script src="<?php echo assets_url();?>js/dataTables.rowReorder.min.js"></script>
<script src="<?php echo assets_url();?>js/dataTables.responsive.min.js"></script>

<script src="<?php echo assets_url();?>js/highcharts.js"></script> <!--FOR NEW DASHBOARD-->

<link rel="stylesheet" type="text/css" href="<?php echo assets_url();?>css/fixedHeader.dataTables.min.css">
<script src="<?php echo assets_url();?>js/dataTables.fixedHeader.min.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript" src="<?php echo assets_url();?>js/fecha.min.js"></script>
<script src="<?php echo assets_url();?>js/hotel-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
<script src="<?php echo assets_url();?>js/datePicker.js"></script>
<script type="text/javascript">
function getMonthYear(m_count) {
  var month = new Array();
  month[1] = "Jan.";
  month[2] = "Feb.";
  month[3] = "Mar.";
  month[4] = "Apr.";
  month[5] = "May";
  month[6] = "June";
  month[7] = "July";
  month[8] = "Aug.";
  month[9] = "Sept.";
  month[10] = "Oct.";
  month[11] = "Nov.";
  month[12] = "Dec.";   
  return month[m_count];
}
var getDaysInMonth = function(month,year) {
  // Here January is 1 based
  //Day 0 is the last day in the previous month
 return new Date(year, month, 0).getDate();
// Here January is 0 based
// return new Date(year, month+1, 0).getDate();
};

 $(document).ready(function(){  
    ////
    


      //same-height
      $('.new_lead_scroll').mCustomScrollbar({
       theme: "minimal-dark",
       alwaysShowScrollbar: 1
      });
      
      $( ".date-input" ).datepicker();
      $('.same-height').matchHeight();
      ////

      ////
      function getDays(getVal){
       //2021-03-09 / 2021-03-16 
       var res = getVal.split(" / ");
       var future = moment(res[1]);
       var start = moment(res[0]);
       var d = future.diff(start, 'days'); // 9
       //alert(d);
       return d;
      }
      //getDays()
      var today = new Date();
       var dd = String(today.getDate()).padStart(2, '0');
       var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
       var yyyy = today.getFullYear();
       var startdate = '2021-01-01';
       today = yyyy + '-' + mm + '-' + dd;

       $('#month_range_lvo')
       .rangePicker({ minDate:[1,2020], maxDate:[mm,yyyy], RTL:false })
       // subscribe to the "done" event after user had selected a date
       .on('datePicker.done', function(e, result){
        // alert(result)
        
          if( result instanceof Array ){
             // alert(new Date(result[0][1], result[0][0] - 1));
             // alert(new Date(result[1][1], result[1][0] - 1));
             var tmp_from_m=getMonthYear(result[0][0]);
             var tmp_to_m=getMonthYear(result[1][0]);
      //alert(tmp_date)
      var to_days=(getDaysInMonth(result[1][0], result[1][1]))
             var from=(result[0][1]+'-'+result[0][0]+'-1');
             var to=(result[1][1]+'-'+result[1][0]+'-'+to_days);
             //alert(from+' to '+to)
             $("#date_range_user_define_from_lvo").val(from);
             $("#date_range_user_define_to_lvo").val(to);
             $("#date_range_pre_define_lvo").val('');
             //alert('User Define :'+last_x_month);
             $('#show_daterange_html_lvo').html(''+tmp_from_m+', '+result[0][1]+' to '+tmp_to_m+', '+result[1][1]);
          }
          else{
            var last_x_month=result.slice(0, -1);
            $("#date_range_pre_define_lvo").val(last_x_month);
            $("#date_range_user_define_from_lvo").val('');
              $("#date_range_user_define_to_lvo").val('');
              $('#show_daterange_html_lvo').html('Last '+last_x_month+' months');
          }
          load_leads_vs_orders_report(1);
       });        
      // ======================================================
      // Leads Quality  
      var input = document.getElementById('date_range_user_define_svq');
      var datepicker = new HotelDatepicker(input, {
          startDate: startdate,
          format: 'YYYY-MM-DD',
          separator: ' to ',
          endDate: today,
          autoClose: false,
          onSelectRange: function() {
        
    }
       });
      input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_svq').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
    var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
    var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
          if(getVal.length > 3)
          {               
             //var gD = getDays(getVal);
             $("#date_range_pre_define_svq").val($("#date_range_pre_define_svq option:first").val());
             $('#show_daterange_html_svq').html(''+fromDateDisplayFormat+' to '+toDateDisplayFormat+'');
             $("#date_range_user_define_from_svq").val(fromDateDisplayFormat);
             $("#date_range_user_define_to_svq").val(toDateDisplayFormat);
             load_lead_source_vs_quality_report(1);
          }
      }, false);

      $("body").on("change","#date_range_pre_define_svq",function(e){
    var date_range_pre_define_text=$('#date_range_pre_define_svq option:selected').text();    
    $('#show_daterange_html_svq').html(''+date_range_pre_define_text+'');
    load_lead_source_vs_quality_report(1);
  });
      // Leads Quality 
      // ======================================================

      // ======================================================
      // Leads by Source 
      var input = document.getElementById('date_range_user_define_lbs');
      var datepicker = new HotelDatepicker(input, {
          startDate: startdate,
          format: 'YYYY-MM-DD',
          separator: ' to ',
          endDate: today,
          autoClose: false,
          onSelectRange: function() {
        
    }
       });
      input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_lbs').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
    var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
    var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
          if(getVal.length > 3)
          {               
             //var gD = getDays(getVal);
             $("#date_range_pre_define_lbs").val($("#date_range_pre_define_lbs option:first").val());
             $('#show_daterange_html_lbs').html(''+fromDateDisplayFormat+' to '+toDateDisplayFormat+'');
             $("#date_range_user_define_from_lbs").val(fromDateDisplayFormat);
             $("#date_range_user_define_to_lbs").val(toDateDisplayFormat);
             load_lead_by_source_report(1);
          }
      }, false);

      $("body").on("change","#date_range_pre_define_lbs",function(e){
    var date_range_pre_define_text=$('#date_range_pre_define_lbs option:selected').text();    
    $('#show_daterange_html_lbs').html(''+date_range_pre_define_text+'');
    load_lead_by_source_report(1);
  });
      // Leads by Source 
      // ======================================================

      // ======================================================
      // Lead Lost Reasons 
      var input = document.getElementById('date_range_user_define_rvs');
      var datepicker = new HotelDatepicker(input, {
          startDate: startdate,
          format: 'YYYY-MM-DD',
          separator: ' to ',
          endDate: today,
          autoClose: false,
          onSelectRange: function() {
        
    }
       });
      input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_rvs').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
    var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
    var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
          if(getVal.length > 3)
          {               
             //var gD = getDays(getVal);
             $("#date_range_pre_define_rvs").val($("#date_range_pre_define_rvs option:first").val());
             $('#show_daterange_html_rvs').html(''+fromDateDisplayFormat+' to '+toDateDisplayFormat+'');
             $("#date_range_user_define_from_rvs").val(fromDateDisplayFormat);
             $("#date_range_user_define_to_rvs").val(toDateDisplayFormat);
             load_lead_lost_reason_vs_lead_source_report(1);
          }
      }, false);

      $("body").on("change","#date_range_pre_define_rvs",function(e){
    var date_range_pre_define_text=$('#date_range_pre_define_rvs option:selected').text();    
    $('#show_daterange_html_rvs').html(''+date_range_pre_define_text+'');
    load_lead_lost_reason_vs_lead_source_report(1);
  });
      // Lead Lost Reasons 
      // ======================================================

  // ======================================================
      // Unfollowed Leads Reports
      var input = document.getElementById('date_range_user_define_ul');
      var datepicker = new HotelDatepicker(input, {
          startDate: startdate,
          format: 'YYYY-MM-DD',
          separator: ' to ',
          endDate: today,
          autoClose: false,
          onSelectRange: function() {
        
    }
       });
      input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_ul').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
    var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
    var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
          if(getVal.length > 3)
          {               
             //var gD = getDays(getVal);
             $("#date_range_pre_define_ul").val($("#date_range_pre_define_ul option:first").val());
             $('#show_daterange_html_ul').html('( '+fromDateDisplayFormat+' to '+toDateDisplayFormat+' )');
             $("#date_range_user_define_from_ul").val(fromDateDisplayFormat);
             $("#date_range_user_define_to_ul").val(toDateDisplayFormat);
             load_unfollowed_leads_by_user(1);
          }
      }, false);

      $("body").on("change","#date_range_pre_define_ul",function(e){
    var date_range_pre_define_text=$('#date_range_pre_define_ul option:selected').text();   
    $('#show_daterange_html_ul').html('( '+date_range_pre_define_text+' )');
    load_unfollowed_leads_by_user(1);
  });
      // Unfollowed Leads Reports 
      // ======================================================

      // ======================================================
      // User Activity Reports
      var input = document.getElementById('date_range_user_define_uar');
      var datepicker = new HotelDatepicker(input, {
          startDate: startdate,
          format: 'YYYY-MM-DD',
          separator: ' to ',
          endDate: today,
          autoClose: false,
          onSelectRange: function() {
        
    }
       });
      input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_uar').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
    var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
    var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
          if(getVal.length > 3)
          {               
             //var gD = getDays(getVal);
             $("#date_range_pre_define_uar").val($("#date_range_pre_define_uar option:first").val());
             $('#show_daterange_html_uar').html('( '+fromDateDisplayFormat+' to '+toDateDisplayFormat+' )');
             $("#date_range_user_define_from_uar").val(fromDateDisplayFormat);
             $("#date_range_user_define_to_uar").val(toDateDisplayFormat);
             load_user_activity_report(1);
          }
      }, false);

      $("body").on("change","#date_range_pre_define_uar",function(e){
    var date_range_pre_define_text=$('#date_range_pre_define_uar option:selected').text();    
    $('#show_daterange_html_uar').html('( '+date_range_pre_define_text+' )');
    load_user_activity_report(1);
  });
      // User Activity Reports 
      // ======================================================
      // ======================================================
      // Lead Source Vs. Conversion 
      var input = document.getElementById('date_range_user_define_svc');
      var datepicker = new HotelDatepicker(input, {
          startDate: startdate,
          format: 'YYYY-MM-DD',
          separator: ' to ',
          endDate: today,
          autoClose: false,
          onSelectRange: function() {
        
    }
       });
      input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_svc').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
    var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
    var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
          if(getVal.length > 3)
          {               
             //var gD = getDays(getVal);
             $("#date_range_pre_define_svc").val($("#date_range_pre_define_svc option:first").val());
             $('#show_daterange_html_svc').html('( '+fromDateDisplayFormat+' to '+toDateDisplayFormat+' )');
             $("#date_range_user_define_from_svc").val(fromDateDisplayFormat);
             $("#date_range_user_define_to_svc").val(toDateDisplayFormat);
             load_lead_source_vs_conversion_report(1);
          }
      }, false);

      $("body").on("change","#date_range_pre_define_svc",function(e){
    var date_range_pre_define_text=$('#date_range_pre_define_svc option:selected').text();    
    $('#show_daterange_html_svc').html('( '+date_range_pre_define_text+' )');
    load_lead_source_vs_conversion_report(1);
  });
      // Lead Source Vs. Conversion 
      // ======================================================

      // ======================================================
      // Daily Sales Report 
      var input = document.getElementById('date_range_user_define_sr');
      var datepicker = new HotelDatepicker(input, {
          startDate: startdate,
          format: 'YYYY-MM-DD',
          separator: ' to ',
          endDate: today,
          autoClose: false,
          onSelectRange: function() {
        
    }
       });
      input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_sr').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
    var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
    var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
          if(getVal.length > 3)
          {               
             //var gD = getDays(getVal);
             $("#date_range_pre_define_sr").val($("#date_range_pre_define_sr option:first").val());
             $('#show_daterange_html_sr').html('( '+fromDateDisplayFormat+' to '+toDateDisplayFormat+' )');
             $("#date_range_user_define_from_sr").val(fromDateDisplayFormat);
             $("#date_range_user_define_to_sr").val(toDateDisplayFormat);
             load_daily_sales_report(1);
          }
      }, false);

      $("body").on("change","#date_range_pre_define_sr",function(e){
    var date_range_pre_define_text=$('#date_range_pre_define_sr option:selected').text();   
    $('#show_daterange_html_sr').html('( '+date_range_pre_define_text+' )');
    load_daily_sales_report(1);
  });
      // Daily Sales Report
      // ======================================================

      // =================================================
      // c2c 
      if($("#c2c_is_active").val()=='Y'){
        var input = document.getElementById('date_range_user_define_c2c');
        var datepicker = new HotelDatepicker(input, {
            startDate: startdate,
            format: 'YYYY-MM-DD',
            separator: ' to ',
            endDate: today,
            autoClose: false,
            onSelectRange: function() {
          
            }
         });
        input.addEventListener('afterClose', function () {
          var getVal = $('#date_range_user_define_c2c').val();
          var dateRangeArr = getVal.split("to");
          var fromDate=dateRangeArr[0];
          var toDate=dateRangeArr[1]; 
          var fromDateDisplayFormat=moment(fromDate, 'YYYY-MM-DD').format('DD-MMM-YYYY'); 
          var toDateDisplayFormat=moment(toDate, 'YYYY-MM-DD').format('DD-MMM-YYYY');
                if(getVal.length > 3)
                {               
                   //var gD = getDays(getVal);
                   $("#date_range_pre_define_c2c").val($("#date_range_pre_define_c2c option:first").val());
                   $('#show_daterange_html_c2c').html('( '+fromDateDisplayFormat+' to '+toDateDisplayFormat+' )');
                   $("#date_range_user_define_from_c2c").val(fromDateDisplayFormat);
                   $("#date_range_user_define_to_c2c").val(toDateDisplayFormat);
                   load_lead_c2c_report(1);
                }
            }, false);

          $("body").on("change","#date_range_pre_define_c2c",function(e){
              var date_range_pre_define_text=$('#date_range_pre_define_c2c option:selected').text();    
              $('#show_daterange_html_c2c').html('( '+date_range_pre_define_text+' )');
              load_lead_c2c_report(1);
          });
      }
      // c2c
      // ==================================================

      $(".open-calendar").click(function(event){
          event.preventDefault();
          //alert("The paragraph was clicked.");
          //datepicker.open();
          $(this).parent().find('input').click();

       });  

             
    });
</script>