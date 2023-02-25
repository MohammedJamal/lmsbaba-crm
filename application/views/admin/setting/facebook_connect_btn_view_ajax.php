<script>
window.fbAsyncInit = function() {
    FB.init({
        appId      : '1591257798016059',
        xfbml      : true,
        version    : 'v16.0'
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

function subscribeApp(page_id, page_access_token) {
    // console.log('Subscribing page to app! ' + page_id);
    FB.api(
        '/' + page_id + '/subscribed_apps',
        'post',
        {access_token: page_access_token, subscribed_fields: ['leadgen']},
        function(response) {
        // console.log('Successfully subscribed page', response);
        }
    );
}
    
// Only works after `FB.init` is called
function myFacebookLogin() {
    FB.login(function(response){
        // console.log('Successfully logged in', response);
        // console.log('User Access Token:'+response.authResponse.accessToken);
        // console.log('User ID:'+response.authResponse.userID);
        $("#fb_user_access_token").val(response.authResponse.accessToken);
        $("#fb_user_id").val(response.authResponse.userID);
        FB.api('/me/accounts', function(response) {
        // console.log('Successfully retrieved pages', response);
        $("#is_fb_connected").val('Y');
        $("#page_list").attr('disabled',false);
        $("#form_list").html('<option value="">Select Form</option>');
        $("#form_list").attr('disabled',false);
        // var str ='Connected';
        var page_list_option='<option data-id="" data-token="" value="">Select Page</option>';
        // str +='<br><span id="fbLogout" onclick="fbLogout()"><a class="fb_button fb_button_medium"><span class="fb_button_text">Logout</span></a></span>';
        
        // document.getElementById('status').innerHTML =str;
        var pages = response.data;
        // var ul = document.getElementById('list');
        for (var i = 0, len = pages.length; i < len; i++) {
            var page = pages[i];
            // var li = document.createElement('li');
            // var a = document.createElement('a');
            // a.href = "#";
            // a.onclick = subscribeApp.bind(this, page.id, page.access_token);
            // a.innerHTML = page.name;
            // li.appendChild(a);
            // ul.appendChild(li);
            
            page_list_option +='<option value="'+page.id+'~~'+page.name+'~~'+page.access_token+'" data-id="'+page.id+'" data-token="'+page.access_token+'">'+page.name+'</option>';
        }
          $("#page_list").html(page_list_option);
        });
    }, {scope: 'pages_show_list,leads_retrieval,pages_read_engagement,pages_manage_ads'});
}
var selected_page_list = $('#page_list').find(":selected").val();
if(selected_page_list){
  var fb_page_id=$('#page_list').find(':selected').attr('data-id');
  var fb_page_access_token=$('#page_list').find(':selected').attr('data-token');
  var is_fb_connected=$("#is_fb_connected").val();    
  get_facebook_page_wise_form_list(fb_page_id,fb_page_access_token,is_fb_connected);
}

function get_facebook_page_wise_form_list(fb_page_id='',fb_page_access_token='',is_fb_connected='N')
{
    var base_url=$("#base_url").val();
    var fb_form_wise_lead_field_set_id=$("#fb_form_wise_lead_field_set_id").val(); 
    var data='fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token+'&is_fb_connected='+is_fb_connected+"&fb_form_wise_lead_field_set_id="+fb_form_wise_lead_field_set_id;
    $.ajax({
      url: base_url+"setting/get_facebook_page_wise_form_list",
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
      success: function(data){
          result = $.parseJSON(data); 
          // alert(result.status)                
          if(result.status=='success')
          {
              // alert(result.html)
              $("#form_list").html(result.html);
             
              var selected_form_list = $('#form_list').find(":selected").val();
              if(selected_form_list){
                var fb_form_id=$("#form_list").find(':selected').attr('data-id');
                var fb_page_id=$("#form_list").find(':selected').attr('data-page_id');
                var fb_page_access_token=$("#form_list").find(':selected').attr('data-page_access_token');
                
                get_fb_fields(fb_form_id,fb_page_id,fb_page_access_token);
              }
          }
          else{
            // swal("Oops!", result.msg,'error'); 
          }
      }, 
      error: function(response) {
        swal("Oops!", response,'error');      
      }                   
  });
}

function get_fb_fields(fb_form_id='',fb_page_id='',fb_page_access_token='')
{
    var base_url=$("#base_url").val();   
    var is_fb_connected=$("#is_fb_connected").val();
    if(fb_page_id==''){
      swal("Oops!", "Page is missing",'error');   
      return false;
    }

    if(fb_page_access_token==''){
      swal("Oops!", "Page token is missing",'error');   
      return false;
    }

    if(fb_form_id==''){
      swal("Oops!", "Form is missing",'error');   
      return false;
    }
    var data='fb_form_id='+fb_form_id+'&fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token+'&is_fb_connected='+is_fb_connected;
    $.ajax({
            url: base_url+"setting/get_facebook_form_wise_lead",
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
            success: function(data){
                result = $.parseJSON(data); 
                          
                if(result.status=='success')
                {
                   
                    $("#form_field").html(result.html);
                    $("#form_field_for_tag").html(result.html2);
                }
            }, 
            error: function(response) {
              swal("Oops!", response,'error');      
            }                   
    });
}
/*
$("body").on("change","#page_list",function(e){
    var base_url=$("#base_url").val();
    var fb_page_id=$(this).find(':selected').attr('data-id');
    var fb_page_access_token=$(this).find(':selected').attr('data-token');
    var is_fb_connected=$("#is_fb_connected").val();
    // alert(fb_page_id+'/'+fb_page_access_token)
    var data='fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token+'&is_fb_connected='+is_fb_connected;
    // alert(data)
    $.ajax({
            url: base_url+"setting/get_facebook_page_wise_form_list",
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
            success: function(data){
                result = $.parseJSON(data); 
                // alert(result.status)                
                if(result.status=='success')
                {
                    // alert(result.html)
                    $("#form_list").html(result.html);
                }
            }, 
            error: function(response) {
              swal("Oops!", response,'error');      
            }                   
    });
});

$("body").on("change","#form_list",function(e){
    // var base_url=$("#base_url").val();
    var fb_form_id=$(this).find(':selected').attr('data-id');
    var fb_page_id=$(this).find(':selected').attr('data-page_id');
    var fb_page_access_token=$(this).find(':selected').attr('data-page_access_token');
    
    get_fb_fields(fb_form_id,fb_page_id,fb_page_access_token);
    // var data='fb_form_id='+fb_form_id+'&fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token;    
    // $.ajax({
    //         url: base_url+"setting/get_facebook_form_wise_lead",
    //         data: data,                    
    //         cache: false,
    //         method: 'GET',
    //         dataType: "html",                   
    //         beforeSend: function( xhr ) { 
    //           $.blockUI({ 
    //               message: 'Please wait...', 
    //               css: { 
    //                  padding: '10px', 
    //                  backgroundColor: '#fff', 
    //                  border:'0px solid #000',
    //                  '-webkit-border-radius': '10px', 
    //                  '-moz-border-radius': '10px', 
    //                  opacity: .5, 
    //                  color: '#000',
    //                  width:'450px',
    //                  'font-size':'14px'
    //                 }
    //           });
    //         },
    //         complete: function(){
    //           $.unblockUI();
    //         },
    //         success: function(data){
    //             result = $.parseJSON(data); 
                          
    //             if(result.status=='success')
    //             {
                   
    //                 $("#form_field").html(result.html);
    //             }
    //         }, 
    //         error: function(response) {
    //           swal("Oops!", response,'error');      
    //         }                   
    // });
});

$("body").on("click","#fb_update_available_fields",function(e){
  var fb_form_id=$("#form_list").find(':selected').attr('data-id');
  var fb_page_id=$("#form_list").find(':selected').attr('data-page_id');
  var fb_page_access_token=$("#form_list").find(':selected').attr('data-page_access_token');
  get_fb_fields(fb_form_id,fb_page_id,fb_page_access_token);
});

$("body").on("click","#confirm_fb_setting",function(e){
  var base_url=$("#base_url").val();

  var page_values = [];
  var $select = $('select[name=page_list]');
  $select.find('option').each(function() {
    if($(this).val()){
      page_values.push($(this).val());
    }    
  });
  $new_input = $('<input type="hidden" name="page_list_all">'); // use 'type="hidden"' in production
  $new_input.val(page_values.join('^'));
  $select.parent().append($new_input);


  var form_values = [];
  var $select = $('select[name=form_list]');
  $select.find('option').each(function() {
    if($(this).val()){
      form_values.push($(this).val());
    }    
  });
  $new_input = $('<input type="hidden" name="form_list_all">'); // use 'type="hidden"' in production
  $new_input.val(form_values.join('^'));
  $select.parent().append($new_input);

  $.ajax({
          url: base_url + "setting/add_edit_fb_lead_field",
          data: new FormData($('#profile_update_form')[0]),
          cache: false,
          method: 'POST',
          dataType: "html",
          mimeType: "multipart/form-data",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function(xhr) {
              
          },
          success: function(data) {
              result = $.parseJSON(data);
              //alert(result.status)
              if(result.status='success'){
                    //   swal({
                    //     title: "Success!",
                    //     text: "The record(s) have been saved",
                    //       type: "success",
                    //     confirmButtonText: "ok",
                    //     allowOutsideClick: "false"
                    // }, function () {                         
                    // });
              }
          }
      });
});

function get_fb_fields(fb_form_id='',fb_page_id='',fb_page_access_token='')
{
    var base_url=$("#base_url").val();   
    var is_fb_connected=$("#is_fb_connected").val();
    if(fb_page_id==''){
      swal("Oops!", "Page is missing",'error');   
      return false;
    }

    if(fb_page_access_token==''){
      swal("Oops!", "Page token is missing",'error');   
      return false;
    }

    if(fb_form_id==''){
      swal("Oops!", "Form is missing",'error');   
      return false;
    }
    var data='fb_form_id='+fb_form_id+'&fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token+'&is_fb_connected='+is_fb_connected;
    $.ajax({
            url: base_url+"setting/get_facebook_form_wise_lead",
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
            success: function(data){
                result = $.parseJSON(data); 
                          
                if(result.status=='success')
                {
                   
                    $("#form_field").html(result.html);
                    $("#form_field_for_tag").html(result.html2);
                }
            }, 
            error: function(response) {
              swal("Oops!", response,'error');      
            }                   
    });
}
*/



</script>
<a href="Javascript:void();" onclick="myFacebookLogin()"><img src="<?php echo assets_url(); ?>images/fb-btn.png" width="200" /></a>
<!-- <button onclick="myFacebookLogin()" type="button">Connect with Facebook</button> -->
<div class="row">
  <div class="col-md-6">
    
    
        <input type="hidden" id="is_fb_connected" value="N">
        <input type="hidden" id="fb_user_access_token" name="fb_user_access_token" value="">
        <input type="hidden" id="fb_user_id" name="fb_user_id" value="">
        <input type="hidden" id="fb_form_wise_lead_field_set_id"  value="<?php echo $fb_form_wise_lead_field_set_id; ?>">
        <div class="form-group">
          <label>Page</label>
          <select class="form-control" id="page_list" name="page_list" disabled >
            <option>Select Page</option>
            <?php if(count($fb_page_list)){ ?>
              <?php foreach($fb_page_list AS $page){ ?>
                <option data-id="<?php echo $page['fb_id']; ?>" data-token="<?php echo $page['fb_token']; ?>" <?php echo ($row['fb_page_id']==$page['fb_id'])?'SELECTED':''; ?>><?php echo $page['fb_name']; ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label>Form</label>
          <select class="form-control" id="form_list" name="form_list" disabled>
            <option value="">Select Form</option>
          </select>         
        </div>
        <div class="form-group">
          <!-- <a href="JavaScript:void(0)" id="fb_update_available_fields" class="text-link"><i class="fa fa-refresh" aria-hidden="true"></i> Update available fields</a> -->
        </div>
        <div class="form-group">
          <div id="form_field"></div>       
        </div>
    
  </div>
  <div class="col-md-6">
    <div id="form_field_for_tag"></div>
  </div>
</div>
<!-- <div id="status"></div>
<ul id="list"></ul> -->