function sp(){
  //alert('load');
  window.createYear = generate_year_range(currentYear, maxYear);
  $('select#year').html(createYear);
  window.selectYear = document.getElementById("year");
  window.selectMonth = document.getElementById("month");
  
  
}
function generate_year_range(start, end) {
  var years = "";
  for (var year = start; year <= end; year++) {
      years += "<option value='" + year + "'>" + year + "</option>";
  }
  return years;
}

var selected_date=$("#selected_meeting_date").val();
const selected_date_arr = selected_date.split("-");
var selected_day=selected_date_arr[2];
var today = new Date();
var nowDate = today.getDate();
var currentMonth = today.getMonth();
var currentYear = today.getFullYear();
var selectYear = document.getElementById("year_cal");
var selectMonth = document.getElementById("month");

var maxYear = currentYear+5;
//alert(currentYear);
var createYear = generate_year_range(currentYear, maxYear);

var selectedDate = {day: 'none', month: 'none', year: 'none', time: 'none', type: 'Call'};
/** or
* createYear = generate_year_range( 1970, currentYear );
*/
//alert(createYear);
$('#year_cal').html(createYear);
//document.getElementById("year").innerHTML = createYear;

var calendar = document.getElementById("calendar");
var lang = calendar.getAttribute('data-lang');

var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

var dayHeader = "<tr>";
for (day in days) {
  dayHeader += "<th data-days='" + days[day] + "'>" + days[day] + "</th>";
}
dayHeader += "</tr>";

document.getElementById("thead-month").innerHTML = dayHeader;
monthAndYear = document.getElementById("monthAndYear");
showCalendar(currentMonth, currentYear);



function next_cal() {
  currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
  currentMonth = (currentMonth + 1) % 12;
  showCalendar(currentMonth, currentYear);
}

function previous_cal() {
  currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
  currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
  showCalendar(currentMonth, currentYear);
}

function jump() {
  selectMonth = document.getElementById("month");
  currentYear = parseInt(selectYear.value);
  currentMonth = parseInt(selectMonth.value);
  //alert(currentMonth);
  showCalendar(currentMonth, currentYear);
}

function showCalendar(month, year) {

  var firstDay = ( new Date( year, month ) ).getDay();

  tbl = document.getElementById("calendar-body");

  
  tbl.innerHTML = "";

  var getDay = nowDate;
  var getMonth = month;
  
  if(getDay < 10){
    getDay = '0'+getDay;
  }
  if(getMonth < 10){
    getMonth = '0'+getMonth;
  }
  //alert(year)
  //monthAndYear.innerHTML = months[month] + " " + year;
  monthAndYear.innerHTML = nowDate + "/" + month + "/" + year;
  //selectYear.value = year;
  //selectMonth.value = getMonth;

  //selectedDate.day = nowDate;
  //selectedDate.month = month;
  //selectedDate.year = year;
  //selectedDate.time = 'none';
  // creating all cells
  
  var date = 1;
  for ( var i = 0; i < 6; i++ ) {
      var row = document.createElement("tr");

      for ( var j = 0; j < 7; j++ ) {
          if ( i === 0 && j < firstDay ) {
              cell = document.createElement( "td" );
              cellText = document.createTextNode("");
              cell.appendChild(cellText);
              row.appendChild(cell);
          } else if (date > daysInMonth(month, year)) {           
            //var ddd =daysInMonth(month, year);
            //console.log(ddd);
              break;
          } else {
              cell = document.createElement("td");
              cell.setAttribute("data-date", date);
              cell.setAttribute("data-month", month + 1);
              cell.setAttribute("data-year", year);
              cell.setAttribute("data-month_name", months[month]);
              cell.className = "date-picker";
              //cell.innerHTML = '<span onclick="getSchedule(this)">' + date + '</span>';
              //var isUnavailable = availableDays.indexOf(day) === -1 || beforeStartDate || afterEndDate;
              

              // if ( date <= today.getDate()-1 && year === today.getFullYear() && month <= today.getMonth() ) {
              //     cell.className = "date-picker isUnavailable";
              //     cell.innerHTML = '<span>' + date + '</span>';
              // }else{
              //   cell.className = "date-picker available";
              //   cell.innerHTML = '<span onclick="getSchedule(this)">' + date + '</span>';
              // }
              ///////////
              if(date==selected_day){
                cell.className = "date-picker current available selected";
                  cell.innerHTML = '<span onclick="getSchedule(this)">' + date + '</span>';
              }
              else{
                if ( date <= today.getDate()-1 && year === today.getFullYear() && month === today.getMonth() ) {                
                  cell.className = "date-picker isUnavailable";
                  cell.innerHTML = '<span>' + date + '</span>';
                }
                else if ( date === today.getDate() && year === today.getFullYear() && month === today.getMonth() ) {
                
                    cell.className = "date-picker current available";
                    cell.innerHTML = '<span onclick="getSchedule(this)">' + date + '</span>';
                }              
                else{                
                    cell.className = "date-picker available";
                    cell.innerHTML = '<span onclick="getSchedule(this)">' + date + '</span>';                           
                  
                }
              }
              
              row.appendChild(cell);
              date++;
          }


      }

      tbl.appendChild(row);
  }

}

function daysInMonth(iMonth, iYear) {
  return 32 - new Date(iYear, iMonth, 32).getDate();
}

  
  function getSchedule(getm) 
  {
      
      var getYear = $(getm).parent().attr('data-year');
      var getMonth = $(getm).parent().attr('data-month');
      var getMonthName = $(getm).parent().attr('data-month_name');
      var getDay = $(getm).parent().attr('data-date');
      var clickItem = $(getm);        
      selectedDate.day = getDay;
      selectedDate.month = getMonth;
      selectedDate.year = getYear;
      //selectedDate.time = 'none';

      if(getDay < 10){
        getDay = '0'+getDay;
      }
      if(getMonth < 10){
        getMonth = '0'+getMonth;
      }
      var selected_meeting_date=getYear + "-" + getMonth + "-" + getDay;
      $("#selected_meeting_date").val(selected_meeting_date);
      var selected_user=$("#meeting_assigned_user_id").val();
      $('.table-calendar tbody').find('.selected').removeClass('selected');
      $(getm).parent().addClass('selected'); 
      get_existing_scheduled_meeting(selected_meeting_date,selected_user);        
      // monthAndYear.innerHTML = getDay + "/" + getMonth + "/" + getYear;         
      //showTimeSlot(clickItem);
      // updateTimeSlot();
  }

  function get_existing_scheduled_meeting(date,user_id)
  {
    var base_url=$("#base_url").val();
    var data="selected_meeting_date="+date+"&selected_user_id="+user_id;
    $.ajax({
        url: base_url + "lead/meeting_scheduled_list_by_date_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function(xhr) {
          $('#scheduleMeetingModal .modal-body').addClass('logo-loader');
        },
        complete: function() {
          $('#scheduleMeetingModal .modal-body').removeClass('logo-loader');
        },
        success: function(res) {
          result=$.parseJSON(res); 
          // $('#othersAppointmentDates h1 span').html('Other meeting on today('+getDay + '/' + getMonth + '/' + selectedDate.year+')')
          $('#othersAppointmentDates').html(result.html); 
          $('#othersAppointmentDates').addClass('show');            
                       
        },              
        error: function(response) {}
      });
  }

// function showTimeSlot(clickItem){
//   var offset = clickItem.position();
//   console.log(offset.left + ", " + offset.top)
//   $('.appoTimeBlock').css({left:offset.left+'px', top:offset.top+'px'})
// }


$(".time_element").timepicki({
  step_size_minutes:5,
  on_change : function(ct){
    var getval = $(ct).val();
    selectedDate.time = getval;
  }
});
// $('input.time_element').change(function(){
//   alert("The text has been changed.");
// });
$('.appointmentSlot.slot').click(function(){
  //alert("The paragraph was clicked.");
  var getSelTime = $(this).attr('data-value');
  
  if ($(this).hasClass('selected')) {
    selectedDate.time = 'none';
    $(this).addClass('slot').removeClass('selected');
  }else{
    selectedDate.time = getSelTime;
    $('.appointmentSlotsContainer').find('.selected').removeClass('selected').addClass('slot');
    $(this).removeClass('slot').addClass('selected');
  }
  
  // updateTimeSlot();
});

function GetMonthName(monthNumber) {
  //var months = ['January', 'February', 'March', 'April', 'May', 'June',
  //'July', 'August', 'September', 'October', 'November', 'December'];
  return months[monthNumber];
}
function resetCalender() {
  selectedDate.time = "none";
  //selectedDate.type = 'Call'
  showCalendar(currentMonth, currentYear);
  $('#finalAppointmentDates').removeClass('show');
  $('.table-calendar tbody').find('.selected').removeClass('selected');
  //$('.appointmentSlotsContainer').find('.selected').removeClass('selected').addClass('slot');
  $('.timeSelect input.time_element').val('');
  $('#finalAppointmentDates').removeClass('show');
  $('#finalAppointmentDates .finalResult').html('Please select date, time and meeting type to continue.')
}
if($("input[name='meeting_type']:checked").val()){
  var getVal = $("input[name='meeting_type']:checked").val()
  if(getVal == 'O'){
    $('.meeting_type_p').addClass('hide');    
    $('.meeting_platform').removeClass('hide');
  }else{
    $('.meeting_type_p').removeClass('hide');
    $('.meeting_platform').addClass('hide');
  }
}
$('input[type=radio][name=meeting_type]').on('change', function() {
  var getVal = $(this).val(); 
  selectedDate.type = getVal;  
  if(getVal == 'O'){
    $('.meeting_type_p').addClass('hide');    
    $('.meeting_platform').removeClass('hide');
  }else{
    $('.meeting_type_p').removeClass('hide');
    $('.meeting_platform').addClass('hide');
  }
  // updateTimeSlot()
});
$('input[type=radio][name=online_meeting_platform]').on('change', function() {
  var getVal = $(this).val();
  //alert(getVal);
  selectedDate.type = getVal;
  if(getVal == 'O'){
    $('.online-meeting-url').removeClass('hide');
  }else{
    $('.online-meeting-url').addClass('hide');
  }
  // updateTimeSlot()
});
function updateTimeSlot()
{
  // console.log(selectedDate);
  //alert(selectedDate.type);
  //alert(GetMonthName(selectedDate.month));
  //day: 'none', month: 'none', year: 'none', time: 'none', type: 'Call'
  if(selectedDate.day != "none" && selectedDate.month != "none" && selectedDate.year != "none" && selectedDate.time != "none" && selectedDate.type != "none"){
    //$('#finalAppointmentDates').removeClass('show');
    // console.log('TIME: '+selectedDate.time);
   
    var getDay = selectedDate.day;
    var getMonth = selectedDate.month;
    
    if(getDay < 10){
      getDay = '0'+getDay;
    }
    if(getMonth < 10){
      getMonth = '0'+getMonth;
    }
    //finalType
    // $('#finalAppointmentDates #finalType').html(selectedDate.type);
    // $('#finalAppointmentDates #finalTime').html(selectedDate.time);
    // $('#finalAppointmentDates #finalDate').html(getDay + "/" + getMonth + "/" + selectedDate.year);
    
    $('#finalAppointmentDates .finalResult').html('You have selected '+selectedDate.type+' on '+getDay + '/' + getMonth + '/' + selectedDate.year+' at '+selectedDate.time);
    $('#finalAppointmentDates').addClass('show');
  }
}
$('.date-picker:nth-child(1)').removeClass('available').addClass('isUnavailable');

