
<h2>My Platform</h2>
<?php  ?>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1591257798016059',//383683266827046
      xfbml      : true,
      version    : 'v15.0'
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
    console.log('Subscribing page to app! ' + page_id);
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token, subscribed_fields: ['leadgen']},
      function(response) {
        console.log('Successfully subscribed page', response);
      }
    );
  }
    
  // Only works after `FB.init` is called
  function myFacebookLogin() {
    FB.login(function(response){
      console.log('Successfully logged in', response);
      FB.api('/me/accounts', function(response) {
        console.log('Successfully retrieved pages', response);
        var str ='Connected';
    //   str +='<br><span id="fbLogout" onclick="fbLogout()"><a class="fb_button fb_button_medium"><span class="fb_button_text">Logout</span></a></span>';
      document.getElementById('status').innerHTML =str;
        var pages = response.data;
        var ul = document.getElementById('list');
        for (var i = 0, len = pages.length; i < len; i++) {
          var page = pages[i];
          var li = document.createElement('li');
          var a = document.createElement('a');
          a.href = "#";
          a.onclick = subscribeApp.bind(this, page.id, page.access_token);
          a.innerHTML = page.name;
          li.appendChild(a);
          ul.appendChild(li);
        }
      });
    }, {scope: 'pages_show_list'});
  }
</script>
<button onclick="myFacebookLogin()">Login with Facebook</button>
<div id="status"></div>
<ul id="list"></ul>
<?php  ?>
<?php /* ?>

<script>

  function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
      console.log(response.authResponse.accessToken);
      testAPI();  
    } else {                                 // Not logged into your webpage or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this webpage.';
    }
  }


  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
  }


  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1591257798016059',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v15.0'           // Use this Graph API version for this call.
    });


    FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
  };
 
  function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {   
      console.log(response)     
      console.log('Successful login for: ' + response.name);
      var str ='Thanks for logging in, ' + response.name + '!';
    //   str +='<br><span id="fbLogout" onclick="fbLogout()"><a class="fb_button fb_button_medium"><span class="fb_button_text">Logout</span></a></span>';
      document.getElementById('status').innerHTML =str;
    });
  }
  function fbLogout() {
        FB.logout(function (response) {
            //Do what ever you want here when logged out like reloading the page
            window.location.reload();
        });
    }

</script>

<!-- The JS SDK Login Button -->
<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>
<div id="status"></div>
<!-- Load the JS SDK asynchronously -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

<?php */ ?>