$(document).ready(function(){
  document.getElementById("defaultOpen").click(); 
});
function openDiv(evt, divName) 
{ 
  $("body, html").animate({ scrollTop: 200 }, "slow");
  var i, tabcontent, tablinks;
  
  tabcontent = document.getElementsByClassName("tabcontentDiv");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  
  document.getElementById(divName).style.display = "block";
  evt.currentTarget.className += " active";
  
  //////////
  if(divName=='tab_1')
  { 
    load_order();
   
  } 
  if(divName=='tab_2')
  { 
    load_settings();
  }  
  if(divName=='tab_3')
  { 
    
  }   
}
function load_settings()
{
    var base_URL=$("#base_url").val();    
    var data = "";
     //alert(data);// return false;
    $.ajax({
        url: base_URL+"order_management/rander_settings_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result = $.parseJSON(res);  
            $("#settings_tcontent").html(result.html);
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}

function load_order()
{
    var base_URL=$("#base_url").val();    
    var data = "";
     //alert(data);// return false;
    $.ajax({
        url: base_URL+"order_management/rander_orders_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result = $.parseJSON(res);  
            $("#order_tcontent").html(result.html);
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}