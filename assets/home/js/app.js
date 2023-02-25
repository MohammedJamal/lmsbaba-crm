function refreshCaptcha()
{
  var img = document.images['captchaimg'];
  img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
$(document).ready(function(){

    // BOOK DEMO
    $("#book_demo_modal").on("hidden.bs.modal", function () {
        $("#book_demo_success_div").hide();
        $("#book_demo_error_div").hide();
        $("#book_demo_name").val('');
        $("#book_demo_mobile").val('');
        $("#book_demo_email").val('');
        $("#book_demo_company_name").val('');
        $("#book_demo_demo_datetime").val('');
        $("#book_demo_comment").val('');
        $("#book_demo_captcha_code").val('');
    });

    $("body").on("click",".book_demo",function(e){
        $("#book_demo_modal").modal({
            backdrop: 'static',
            keyboard: false,
            //callback: fn_rander_seo_content_view(id)
        });
    });

    $("body").on("click","#book_demo_send_confirm",function(e){

      var base_url=$("#base_url").val();
      var book_demo_name=$("#book_demo_name").val();
      var book_demo_mobile=$("#book_demo_mobile").val();
      //var book_demo_email=$("#book_demo_email").val();
      var book_demo_company_name=$("#book_demo_company_name").val();
      //var book_demo_demo_datetime=$("#book_demo_demo_datetime").val();
      var book_demo_comment=$("#book_demo_comment").val();
      //var book_demo_captcha_code=$("#book_demo_captcha_code").val();
      if(book_demo_name=='')
      {
        $("#book_demo_name_error").html("Please enter your name.");
        $("#book_demo_name").focus();
        return false;
      }
      else
      {
        $("#book_demo_name_error").html("");
      }

      if(book_demo_mobile=='')
      {
        $("#book_demo_mobile_error").html("Please enter your mobile.");
        $("#book_demo_mobile").focus();
        return false;
      }
      else
      {
        $("#book_demo_mobile_error").html("");
      }
      // if(book_demo_email=='')
      // {
      //   $("#book_demo_email_error").html("Please enter your email.");
      //   $("#book_demo_email").focus();
      //   return false;
      // }
      // else
      // {
      //   if(is_email_validate(book_demo_email)==false)
      //   {
      //     $("#book_demo_email_error").html("Please enter valid email.");
      //     $("#book_demo_email").focus();
      //     return false;
      //   }
      //   else
      //   {
      //     $("#book_demo_email_error").html("");
      //   }
        
      // }

      if(book_demo_company_name=='')
      {
        $("#book_demo_company_name_error").html("Please enter company name.");
        $("#book_demo_company_name").focus();
        return false;
      }
      else
      {
        $("#book_demo_company_name_error").html("");
      }

      // if(book_demo_demo_datetime=='')
      // {
      //   $("#book_demo_demo_datetime_error").html("Please select demo date time.");
      //   $("#book_demo_demo_datetime").focus();
      //   return false;
      // }
      // else
      // {
      //   $("#book_demo_demo_datetime_error").html("");
      // }

      // if(book_demo_comment=='')
      // {
      //   $("#book_demo_comment_error").html("Please enter comment.");
      //   $("#book_demo_comment").focus();
      //   return false;
      // }
      // else
      // {
      //   $("#book_demo_comment_error").html("");
      // }

      // if(book_demo_captcha_code=='')
      // {
      //   $("#book_demo_captcha_code_error").html("Please enter captcha code.");
      //   $("#book_demo_captcha_code").focus();
      //   return false;
      // }
      // else
      // {
      //   $("#book_demo_captcha_code_error").html("");
      // }
      $.ajax({
              url: "home/book_demo_add_ajax",
              data: new FormData($('#book_demo_form')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function( xhr ) { 
                $('#book_demo_modal .modal-body').addClass('logo-loader');
              },
              complete: function (){ 
                $('#book_demo_modal .modal-body').removeClass('logo-loader');
              },
              success: function(data){                
                  result = $.parseJSON(data); 
                  //alert(result.status); 
                  if(result.status=='success')
                  {
                    //$("#sceo_content_success_msg").html('SEO Content successfully saved..');
                    $("#book_demo_success_div").show();
                    $("#book_demo_success_msg").html(result.msg);
                    $("#book_demo_name").val('');
                    $("#book_demo_mobile").val('');
                    $("#book_demo_email").val('');
                    $("#book_demo_company_name").val('');
                    $("#book_demo_demo_datetime").val('');
                    $("#book_demo_comment").val('');
                    $("#book_demo_captcha_code").val(''); 
                    $('.book_demo_form_holder').css({'display':'none'});
                    $('.book_demo_sucess_holder').css({'display':'inline-block'});                   
                  } 
                  else if(result.status=='code_error')
                  {                        
                    $("#book_demo_captcha_code_error").html(result.msg);
                  }
                  else
                  {
                    $("#book_demo_error_div").show();
                    $("#book_demo_error_msg").html(result.msg);
                  }                                          
              }
          });
    });



    // CONTACT US
    $("#contact_us_modal").on("hidden.bs.modal", function () {
        $("#contact_us_success_div").hide();
        $("#contact_us_error_div").hide();
        $("#contact_us_name").val('');
        $("#contact_us_mobile").val('');
        $("#contact_us_email").val('');
        $("#contact_us_comment").val('');
    });

    $("body").on("click",".contact_us",function(e){
        $("#contact_us_modal").modal({
                  backdrop: 'static',
                  keyboard: false,
                  //callback: fn_rander_seo_content_view(id)
        });
    });

    

    $("body").on("click","#contact_us_send_confirm",function(e){

      var base_url=$("#base_url").val();
      var contact_us_name=$("#contact_us_name").val();
      var contact_us_mobile=$("#contact_us_mobile").val();
      var contact_us_email=$("#contact_us_email").val();
      var contact_us_comment=$("#contact_us_comment").val();
      if(contact_us_name=='')
      {
        $("#contact_us_name_error").html("Please enter your name.");
        $("#contact_us_name").focus();
        return false;
      }
      else
      {
        $("#contact_us_name_error").html("");
      }

      if(contact_us_mobile=='')
      {
        $("#contact_us_mobile_error").html("Please enter your mobile.");
        $("#contact_us_mobile").focus();
        return false;
      }
      else
      {
        $("#contact_us_mobile_error").html("");
      }
      if(contact_us_email=='')
      {
        $("#contact_us_email_error").html("Please enter your email.");
        $("#contact_us_email").focus();
        return false;
      }
      else
      {
        if(is_email_validate(contact_us_email)==false)
        {
          $("#contact_us_email_error").html("Please enter valid email.");
          $("#contact_us_email").focus();
          return false;
        }
        else
        {
          $("#contact_us_email_error").html("");
        }
        
      }
      if(contact_us_comment=='')
      {
        $("#contact_us_comment_error").html("Please enter your comment.");
        $("#contact_us_comment").focus();
        return false;
      }
      else
      {
        $("#contact_us_comment_error").html("");
      }
      $.ajax({
              //url: base_url+"home/contact_us_add_ajax",
			  url: "home/contact_us_add_ajax",
              data: new FormData($('#contact_us_form')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function( xhr ) {
                $('#contact_us_modal .modal-body').addClass('logo-loader');
               },
              complete: function (){ 
                $('#contact_us_modal .modal-body').removeClass('logo-loader');
              },
              success: function(data){                
                  result = $.parseJSON(data); 
                  //alert(result.msg); 
                  if(result.status=='success')
                  {
                    //$("#sceo_content_success_msg").html('SEO Content successfully saved..');
                    $("#contact_us_success_div").show();
                    $("#contact_us_success_msg").html(result.msg);
                    $("#contact_us_name").val('');
                    $("#contact_us_mobile").val('');
                    $("#contact_us_email").val('');
                    $("#contact_us_comment").val('');                        
                  } 
                  else
                  {
                    $("#contact_us_error_div").show();
                    $("#contact_us_error_msg").html(result.msg);
                  }                                          
              }
          });
    });
});


var sections = $('section')
  , nav = $('nav')
  , nav_height = nav.outerHeight();

$(window).on('scroll', function () {
  var cur_pos = $(this).scrollTop();
  
  sections.each(function() {
        var top = $(this).offset().top - nav_height,
        bottom = top + $(this).outerHeight();
    
    if (cur_pos >= top && cur_pos <= bottom) {
      nav.find('a').removeClass('active');
      sections.removeClass('active');
      
      $(this).addClass('active');
      nav.find('a[href="#'+$(this).attr('id')+'"]').addClass('active');
    }
  });
});

nav.find('a').on('click', function (event) {
    // event.preventDefault();
  var $el = $(this)
    , id = $el.attr('href');
  //alert(nav_height);
  nav_height = nav.outerHeight();
  $('html, body').animate({
    scrollTop: $(id).offset().top - (nav_height+60)
  }, 500);
  
  return false;
});