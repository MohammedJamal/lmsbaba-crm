<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8"/>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="description" content="<?php echo $page_description; ?>">
      <meta name="keywords" content="<?php echo $page_keyword; ?>">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1"/>
      <meta name="msapplication-tap-highlight" content="no">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="application-name" content="Milestone">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="apple-mobile-web-app-title" content="Milestone">
      <meta name="theme-color" content="#4C7FF0">
      <title></title>
      <link rel="icon" href="<?=base_url();?>assets/images/favicon_8.ico" type="image/ico" sizes="18x18">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
      <link rel="stylesheet" href="<?=base_url();?>vendor/font-awesome/css/font-awesome.css"/>
      <input type="hidden" id="base_url" value="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url'];?>/">
      <input type="hidden" id="assets_base_url" value="<?php echo assets_url();?>/">
      <input type="hidden" id="base_url_root" value="<?php echo base_url();?>/">
      <style type="text/css">
        .process-sec{
          width: 400px;
          height: auto;
          display: block;
          margin: 200px auto 0;
          box-sizing: border-box;
          padding: 25px;
          text-align: center;
        }

        html,
body {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
}
body {
  overflow: hidden;
}
.background {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background: linear-gradient(transparent, rgba(0,0,0,0.5)), url("https://images.pexels.com/photos/4827/nature-forest-trees-fog.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260");
  background-size: cover;
  background-position: center;
}
.modalbox.success,
.modalbox.error {
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  border-radius: 8px;
  background: #fff;
  padding: 25px 25px 15px;
  text-align: center;
}
.modalbox.success.animate .icon,
.modalbox.error.animate .icon {
  -webkit-animation: fall-in 0.75s;
  -moz-animation: fall-in 0.75s;
  -o-animation: fall-in 0.75s;
  animation: fall-in 0.75s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}
.modalbox.success h1,
.modalbox.error h1 {
  font-family: 'Montserrat', sans-serif;
}
.modalbox.success p,
.modalbox.error p {
  font-family: 'Open Sans', sans-serif;
}
.modalbox.success button,
.modalbox.error button,
.modalbox.success button:active,
.modalbox.error button:active,
.modalbox.success button:focus,
.modalbox.error button:focus {
  -webkit-transition: all 0.1s ease-in-out;
  transition: all 0.1s ease-in-out;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  border-radius: 30px;
  margin-top: 15px;
  width: 80%;
  background: transparent;
  color: #4caf50;
  border-color: #4caf50;
  outline: none;
}
.modalbox.success button:hover,
.modalbox.error button:hover,
.modalbox.success button:active:hover,
.modalbox.error button:active:hover,
.modalbox.success button:focus:hover,
.modalbox.error button:focus:hover {
  color: #fff;
  background: #4caf50;
  border-color: transparent;
}
.modalbox.success .icon,
.modalbox.error .icon {
  position: relative;
  margin: 0 auto;
  margin-top: -75px;
  background: #4caf50;
  height: 100px;
  width: 100px;
  border-radius: 50%;
}
.modalbox.success .icon span,
.modalbox.error .icon span {
  postion: absolute;
  font-size: 4em;
  color: #fff;
  text-align: center;
  padding-top: 20px;
}
.modalbox.error button,
.modalbox.error button:active,
.modalbox.error button:focus {
  color: #f44336;
  border-color: #f44336;
}
.modalbox.error button:hover,
.modalbox.error button:active:hover,
.modalbox.error button:focus:hover {
  color: #fff;
  background: #f44336;
}
.modalbox.error .icon {
  background: #f44336;
}
.modalbox.error .icon span {
  padding-top: 25px;
}
.center {
  float: none;
  margin-left: auto;
  margin-right: auto;
/* stupid browser compat. smh */
}
.center .change {
  clearn: both;
  display: block;
  font-size: 10px;
  color: #ccc;
  margin-top: 10px;
}
@-webkit-keyframes fall-in {
  0% {
    -ms-transform: scale(3, 3);
    -webkit-transform: scale(3, 3);
    transform: scale(3, 3);
    opacity: 0;
  }
  50% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
  60% {
    -ms-transform: scale(1.1, 1.1);
    -webkit-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
  }
  100% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
}
@-moz-keyframes fall-in {
  0% {
    -ms-transform: scale(3, 3);
    -webkit-transform: scale(3, 3);
    transform: scale(3, 3);
    opacity: 0;
  }
  50% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
  60% {
    -ms-transform: scale(1.1, 1.1);
    -webkit-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
  }
  100% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
}
@-o-keyframes fall-in {
  0% {
    -ms-transform: scale(3, 3);
    -webkit-transform: scale(3, 3);
    transform: scale(3, 3);
    opacity: 0;
  }
  50% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
  60% {
    -ms-transform: scale(1.1, 1.1);
    -webkit-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
  }
  100% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
}
@-webkit-keyframes plunge {
  0% {
    margin-top: -100%;
  }
  100% {
    margin-top: 25%;
  }
}
@-moz-keyframes plunge {
  0% {
    margin-top: -100%;
  }
  100% {
    margin-top: 25%;
  }
}
@-o-keyframes plunge {
  0% {
    margin-top: -100%;
  }
  100% {
    margin-top: 25%;
  }
}
@-moz-keyframes fall-in {
  0% {
    -ms-transform: scale(3, 3);
    -webkit-transform: scale(3, 3);
    transform: scale(3, 3);
    opacity: 0;
  }
  50% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
  60% {
    -ms-transform: scale(1.1, 1.1);
    -webkit-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
  }
  100% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
}
@-webkit-keyframes fall-in {
  0% {
    -ms-transform: scale(3, 3);
    -webkit-transform: scale(3, 3);
    transform: scale(3, 3);
    opacity: 0;
  }
  50% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
  60% {
    -ms-transform: scale(1.1, 1.1);
    -webkit-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
  }
  100% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
}
@-o-keyframes fall-in {
  0% {
    -ms-transform: scale(3, 3);
    -webkit-transform: scale(3, 3);
    transform: scale(3, 3);
    opacity: 0;
  }
  50% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
  60% {
    -ms-transform: scale(1.1, 1.1);
    -webkit-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
  }
  100% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
}
@keyframes fall-in {
  0% {
    -ms-transform: scale(3, 3);
    -webkit-transform: scale(3, 3);
    transform: scale(3, 3);
    opacity: 0;
  }
  50% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
    opacity: 1;
  }
  60% {
    -ms-transform: scale(1.1, 1.1);
    -webkit-transform: scale(1.1, 1.1);
    transform: scale(1.1, 1.1);
  }
  100% {
    -ms-transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
}
@-moz-keyframes plunge {
  0% {
    margin-top: -100%;
  }
  100% {
    margin-top: 15%;
  }
}
@-webkit-keyframes plunge {
  0% {
    margin-top: -100%;
  }
  100% {
    margin-top: 15%;
  }
}
@-o-keyframes plunge {
  0% {
    margin-top: -100%;
  }
  100% {
    margin-top: 15%;
  }
}
@keyframes plunge {
  0% {
    margin-top: -100%;
  }
  100% {
    margin-top: 15%;
  }
}

      </style>
   </head>
   <body>
      <!-- <div class="card process-sec">
         <h5 class="lead_board text-center text-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Gmail Token Created Successfully</h5>
         <span id="selected_filter_div" class="lead_filter_div"></span>
         <div class="card-block text-center">
            <button type="button" class="btn btn-success">Close</button>
         </div>
      </div> -->
      <div class="container">
        <div class="row">
          <div class="modalbox success col-sm-8 col-md-6 col-lg-5 center animate">
            <div class="icon">
              <span class="glyphicon glyphicon-ok"></span>
            </div>
            <!--/.icon-->
            <h1>Success!</h1>
            <p>Gmail Token Created Successfully</p>
            <button type="button" class="redo btn" id="close_window">Click to Close</button>
          </div>
          <!--/.success-->
        </div>
        
      </div>
      <!--/.container-->
      <!-- bottom footer -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
      <script src="<?=base_url();?>vendor/jquery/dist/jquery.js"></script>
      <script type="text/javascript">
        
         function RefreshParent() {
             if (window.opener != null && !window.opener.closed) {
                 window.opener.location.reload();
             }
         }
         window.onbeforeunload = RefreshParent;

         $(document).ready(function(){
          $("body").on("click","#close_window",function(e){
            window.opener.location.reload(true);
            window.close();
          });
         });
      </script>
   </body>
</html>