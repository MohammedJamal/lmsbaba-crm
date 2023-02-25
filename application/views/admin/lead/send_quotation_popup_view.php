<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
    <?php //$this->load->view('admin/includes/head'); ?>  
    <link rel="icon" href="<?=assets_url();?>images/favicon_8.ico" type="image/ico" sizes="18x18">
    <link rel="stylesheet" href="<?=assets_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=assets_url();?>vendor/bootstrap/dist/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?=assets_url();?>vendor/font-awesome/css/font-awesome.css"/>
    <link rel="stylesheet" href="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.css"/>
    <!-- <link rel="stylesheet" href="<?=assets_url();?>css/app.css?v=<?php echo rand(0,1000); ?>" id="loadtsf_styles_before"/> -->
    <link rel="stylesheet" href="<?=assets_url();?>css/responsive.css"/>
    <style type="text/css" media="screen">
    .d-none{display: none;}
    .d-block{display: block;}
    </style>
    <script src="<?=assets_url();?>vendor/jquery/dist/jquery.js"></script>
    <script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script>
    <input type="hidden" id="base_url" value="<?php echo base_url().$lms_url;?>">
    <input type="hidden" id="assets_base_url" value="<?php echo assets_url();?>">
    <input type="hidden" id="base_url_root" value="<?php echo base_url();?>">
    <input type="hidden" id="is_mobile" value="<?php echo $is_mobile; ?>">  
    
    
   <script> 
        var body = document.getElementsByTagName('body')[0];
        document.addEventListener('readystatechange', event => {            
            if (event.target.readyState === "interactive") { 
                body.className = body.className.replace(/logo-loader/, '');
            }            
            if (event.target.readyState === "complete") {
                removeLoader();       
            }
        });
   </script>  
   <style type="text/css">
/**{
    font-family: 'Roboto', sans-serif !important;
}*/
#ReplyPopupModal .repply-block{
    overflow: hidden;
}
ul{
    list-style: none;
    padding: 0px;
    margin: 0px;
}
.lead-show-position{
    box-sizing: border-box;
    padding: 0 10px;
}
*,
.content-view *{
    font-family: 'Roboto', sans-serif;
}
.content-view a:hover{
    text-decoration: none !important;
}
.fileinput-button{
    position: relative;
}
.fileinput-button input[type="file"] {
    opacity: 0 !important;
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 33;
    left: 0px;
    top: 0px;
    cursor: pointer;
}
/*.material-icons {
    font-family: 'Material Icons' !important;
    font-size: 24px;
    width: 24px;
    font-weight: normal;
    font-style: normal;
    line-height: 1;
    display: inline-block;
    vertical-align: bottom;
    white-space: nowrap;
    letter-spacing: normal;
    text-transform: none;
    word-wrap: normal;
    direction: ltr;
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-feature-settings: 'liga';
    font-feature-settings: 'liga';
}*/
#files-area {
    width: 100%;
    margin: 0 auto;
}
.file-block {
    border-radius: 10px;
    background-color: rgba(144, 163, 203, 0.2);
    margin: 5px;
    color: initial;
    display: inline-flex;
}
.file-delete {
    display: flex;
    width: 24px;
    color: initial;
    background-color: #6eb4ff00;
    font-size: large;
    justify-content: center;
    margin-right: 3px;
    cursor: pointer;
}
.text-danger {
    color: #d26d54 !important;
}
.file-block > span.name {
    padding-right: 10px;
    width: max-content;
    display: inline-flex;
}
#prod_lead_list .no-found-text {
    font-family: 'Roboto', sans-serif;
    font-size: 20px;
    color: #76c7f7;
}
#lead_add_product_modal .modal-dialog {
    max-width: 900px !important;
}
#lead_add_product_modal .tsf-step-content .form-group label {
    width: 100% !important;
    display: inline-block !important;
    font-size: 0.8125rem;
}
.tsf-step-content .form-group label {
    color: #222;
}
.btn-success:hover,
.btn-success {
    color: white;
    background-color: #008ac9;
    border-color: #008ac9;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
}
.background_blue a:hover,
a:focus, 
a:hover{
    text-decoration: none !important;
}

.content-view .fa {
    font: normal normal normal 14px/1 FontAwesome !important;
}
.update_proposal p {
    font-size: 13px;
    color: #232323;
    margin-bottom: 6px;
}
.update_proposal .clear{
    margin-bottom: 15px !important;
}
.update_proposal a:not(.btn) {
    font-size: 12px;
    color: #232323;
    text-decoration: none;
}
.update_proposal a .fa {
    font-size: 15px;
    color: #008AC9 !important;
}
.text-success {
    color: #429a15 !important;
}
.main-content--- .process-sec.card.no-app{
    border: none !important;
}
.lead-edit-tab .update_proposal {
    background: #f7f7f7;
    width: 100%;
    float: left;
    padding: 15px 25px !important;
    border-radius: 9px;
    margin-top: 0px;
    margin-bottom: 10px;
    display: inline-block;
    position: relative;
}
.m-10px {
    margin: 10px !important;
}
.update_proposal h5 {
    font-size: 15px;
    color: #000;
    font-weight: 600;
}

.close-modal {
    width: 30px;
    height: 30px;
    position: absolute;
    z-index: 3;
    top: 10px;
    right: 15px;
}
.mail-modal .modal-dialog {
    max-width: 900px;
}

/*app css*/
.btn {
    border-radius: 2px;
    -webkit-transition: all 200ms linear;
    transition: all 200ms linear;
}

.btn:focus,
.btn:active:focus {
    outline: 0;
}

.dropdown-toggle::after {
    font-size: 14px;
}

.dropdown-menu {
    border-color: rgba(0, 0, 0, 0.1);
    box-shadow: rgba(0, 0, 0, 0.07) 0 2px 5px 0, rgba(0, 0, 0, 0.08) 0 2px 10px 0;
    border-radius: 2px;
    -webkit-transition: opacity 200ms ease-in-out, visibility 200ms ease-in-out;
    transition: opacity 200ms ease-in-out, visibility 200ms ease-in-out;
}

label {
    font-weight: 500;
}

textarea {
    resize: vertical;
}

.form-control {
    height: 42px;
    border-color: rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    box-shadow: none;
}

.form-control::-webkit-input-placeholder {
    color: rgba(38, 38, 38, 0.4);
}

.form-control::-moz-placeholder {
    color: rgba(38, 38, 38, 0.4);
}

.form-control:-ms-input-placeholder {
    color: rgba(38, 38, 38, 0.4);
}

.form-control::placeholder {
    color: rgba(38, 38, 38, 0.4);
}

.form-control:disabled {
    background-color: #e8e8e8;
}

.form-control:focus {
    border-color: #84a7f5;
    box-shadow: none;
}

.tag {
    font-weight: 600;
    border-radius: 2px;
}

.select2-container .select2-selection {
    line-height: 34px;
    display: block;
    height: 34px;
    padding: 0 0 0 8px;
    color: rgba(0, 0, 0, 0.7);
    border-color: rgba(0, 0, 0, 0.1);
    outline: 0;
    background-color: #fff;
    background-image: none;
}

.select2-container .select2-selection::after {
    position: absolute;
    top: 50%;
    right: 15px;
    display: block;
    width: 0;
    height: 0;
    margin-top: -3px;
    content: ' ';
    border-width: 5px 5px 0 5px;
    border-style: solid;
    border-color: #808080 transparent transparent transparent;
}

.select2-search input {
    border-color: rgba(0, 0, 0, 0.1) !important;
    outline: 0;
}

.btn {
    box-shadow: none;
}

.select2-container .select2-selection {
    min-height: 28px;
    line-height: 24px !important;
    height: 100%;
}

.check-box-sec {
    display: block;
    position: relative;
    line-height: 20px;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 15px;
    color: #000;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.check-box-sec input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #fff;
    border: solid 1px #ddd;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.check-box-sec input:checked~.checkmark:after {
    display: block;
}

.check-box-sec .checkmark:after {
    left: 7px;
    top: 3px;
    width: 5px;
    height: 10px;
    border: solid #008ac9;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

.select2-container .select2-selection {
    min-height: 42px;
    height: 100%;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    left: auto;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 274px;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #eee;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

button:focus {
    outline: none !important;
}

.custom_blu {
    background-color: #008ac9 !important;
    border-color: #008ac9 !important;
    font-size: 14px !important;
    border-radius: 4px !important;
    color: #FFF !important;
    width: auto;
    display: inline-block !important;
    padding: 12px 24px !important;
    border-radius: 4px !important;
    overflow: hidden !important;
    font-weight: normal !important;
    line-height: normal !important;
}

.check-box-sec {
    width: auto;
    height: 20px;
    display: inline-block;
    margin: 0;
    padding: 0;
    min-width: 20px;
}

.ff .check-box-sec {
    float: left;
    margin: 0 5px 0 0;
}

.ff {
    line-height: 26px !important;
}

input,
textarea,
select {
    outline: none !important;
}

.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
    background-color: #008ac9 !important;
}

.ff .check-box-sec {
    height: 20px;
    display: block;
    padding: 0;
    width: 20px;
    float: left !important;
}

.upload-name-holder {
    width: calc(100% - 100px);
    display: none;
    float: left;
    box-sizing: border-box;
    padding: 0 0 0 10px;
}

.upload-name-holder .fname_holder {
    width: auto;
    height: auto;
    display: inline-block;
    font-size: 13px;
    font-weight: 400;
    line-height: 26px;
    margin: 0 10px 0 0;
    box-sizing: border-box;
    padding: 0;
    position: relative;
    float: left;
}

.upload-name-holder .fname_holder span {
    width: auto;
    height: auto;
    display: inline-block !important;
    font-size: 13px;
    font-weight: 400;
    line-height: 26px;
    display: none;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 230px;
    float: left;
}

.upload-name-holder .fname_holder a {
    width: 20px;
    height: 26px;
    line-height: 26px;
    text-align: center;
    float: left;
}

.fname_holder a .fa {
    color: #ff0303 !important;
}

.bulk-footer {
    width: 100%;
    height: auto;
    display: inline-block;
    position: relative;
    box-sizing: border-box;
    padding: 10px 15px 5px;
    border-top: #DDD 1px solid;
}

.bulk-footer ul {
    width: auto;
    display: inline-flex;
    margin: 0px;
    padding: 0px;
}

.bulk-footer ul>li {
    width: auto;
    display: inline-block;
    margin: 0 10px 0 0;
}

.bulk-footer ul>li:last-child {
    margin: 0;
}

.bulk-footer .custom_blu {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
    line-height: 36px !important;
    z-index: inherit !important;
    transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
}

.bulk-footer .custom_blu:hover {
    opacity: 0.8;
}

.float-left {
    width: auto;
    height: auto;
    display: inline-block;
    float: left;
}

.attachment_clip {
    width: 26px !important;
    height: 17px !important;
    display: block;
    float: left;
    margin-right: 10px;
    background-image: url(https://app.lmsbaba.com/assets/images/attachment_clip.png);
    background-position: center;
    background-repeat: no-repeat;
    background-size: 20px;
    height: 20px;
    width: 20px;
    opacity: .54;
}

.mail-modal .modal-body {
    position: relative;
    padding: 0px;
}

.close-modal {
    width: 30px;
    height: 30px;
    position: absolute;
    z-index: 3;
    top: 10px;
    right: 15px;
}

.mail-form-row {
    width: 100%;
    display: inline-block;
    float: none;
    box-sizing: border-box;
    padding: 8px 15px;
    margin: 0px;
    border-bottom: #f3f3f3 1px solid;
    position: relative;
}

.mail-form-row:last-child {
    border: none;
}

.mail-form-row label {
    color: #000000;
    font-size: 15px;
    font-weight: 600;
    line-height: 30px;
    margin: 0;
    text-align: left;
    margin: 0 6px 0 0;
}

.text-label {
    width: 100%;
    float: none;
    color: #000000;
    font-size: 15px;
    font-weight: 600;
    line-height: 30px;
    margin: 0;
    text-align: left;
    margin: 0 0 6px 0;
}

.buyer-scroller {
    width: 100%;
    height: auto;
    display: inline-block;
    background: #EFEFEF;
    box-sizing: border-box;
    padding: 15px;
    height: 160px;
    border-radius: 0px;
}

.buying-requirements:focus {
    outline: none !important;
}

.float-left {
    float: left;
}

.mail-form-row label.up-label {
    margin: 6px 0 0 0;
    cursor: pointer;
}

.up-label small {
    width: auto;
    display: inline-block;
    float: left;
    color: #00a2e8;
    line-height: 20px;
    text-decoration: underline;
}

.repply-block {
    width: 100%;
    height: auto;
    position: relative;
    display: inline-block;
}

.repply-white {
    width: 100%;
    height: auto;
    position: relative;
}

.repply-top {
    width: 100%;
    ;
    height: auto;
    display: inline-block;
    box-sizing: border-box;
}

.repply-action {
    width: 100%;
    height: auto;
    box-sizing: border-box;
    padding: 10px 15px;
    font-size: 14px;
    line-height: 40px;
    display: inline-block;
}

.repply-loop {
    width: 100%;
    height: auto;
    box-sizing: border-box;
    display: inline-block;
}

.repply-action .btn-group {
    width: auto;
    height: 40px;
    display: inline-flex;
    background: rgba(0, 0, 0, 0);
    border-radius: 5px;
    text-align: center;
    line-height: 40px;
    transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
}

.repply-action .btn-group:hover {
    background: rgba(32, 33, 36, 0.059);
}

.bulk-footer label {
    color: #3f48cc;
    font-size: 12px !important;
    font-weight: 700;
    line-height: 36px;
    margin: 0;
    text-align: left;
    margin: 0 6px 0 0;
}

.footer-shadow {
    position: relative;
    padding-top: 10px !important;
    border-top: none !important;
    margin-top: 10px;
}

.bulk-footer.footer-shadow {
    padding-bottom: 10px !important;
}

.footer-shadow:before {
    content: '';
    width: 100%;
    height: 1px;
    position: absolute;
    z-index: 3;
    left: 0px;
    top: 0px;
    box-shadow: 0 1px 4px 0 rgba(70, 70, 70, 0.5), 0 2px 6px 2px rgba(78, 78, 78, 0.2);
}

.footer-shadow:after {
    content: '';
    width: 100%;
    height: 10px;
    position: absolute;
    z-index: 6;
    left: 0;
    top: 0px;
    background: #FFF;
}

.email-full {
    width: 500px;
    border: none;
    color: #222222;
}

.repply-body {
    width: 100%;
    height: auto;
    box-sizing: border-box;
    padding: 10px 15px;
    font-size: 14px;
    display: inline-block;
}

.repply-block .bulk-footer ul>li {
    display: inline-flex;
}

.mCS-rounded-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
    background-color: #000 !important;
    background-color: rgba(0, 0, 0, 0.75) !important;
}

.mCSB_inside>.mCSB_container {
    margin-right: 5px !important;
}

.select2-container {
    width: 100% !important;
}

.bootstrap-tagsinput {
    background-color: #fff;
    box-shadow: none;
    display: inline-block;
    padding: 0px 6px;
    vertical-align: middle;
    border-radius: 0px;
    max-width: 100%;
    line-height: 30px;
    cursor: text;
    width: calc(100% - 115px);
    float: left;
}

.bootstrap-tagsinput input {
    border: none;
    box-shadow: none;
    outline: none;
    background-color: transparent;
    padding: 0;
    margin: 0;
    width: auto;
    max-width: inherit;
}

.bootstrap-tagsinput input:focus {
    border: none;
    box-shadow: none;
}

.bootstrap-tagsinput .tag {
    margin-right: 2px;
    color: #000;
    font-size: .75rem;
    letter-spacing: .3px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #5f6368;
    font-weight: 500;
    padding: 0 5px;
    line-height: 22px;
    background: #fff;
    border: #dadce0 1px solid !important;
    border-radius: 20px;
}

.bootstrap-tagsinput .tag [data-role="remove"] {
    margin-left: 8px;
    cursor: pointer;
}

.bootstrap-tagsinput .tag [data-role="remove"]:after {
    content: "x";
    padding: 0px 2px;
}

.bootstrap-tagsinput .tag [data-role="remove"]:hover {
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.bootstrap-tagsinput .tag [data-role="remove"]:hover:active {
    box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}

.repply-action .btn-group.left {
    float: left;
}

.mw-65 {
    width: 65px !important;
}

.repply-action .btn-group.mw-65 {
    height: 30px !important;
    line-height: 30px !important;
}

.select2-container .select2-selection {
    min-height: 45px !important;
}

::-webkit-input-placeholder {
    font-size: 14px !important;
}

:-ms-input-placeholder {
    font-size: 14px !important;
}

::placeholder {
    font-size: 14px !important;
}

#ReplyPopupModal .email-full {
    float: left;
}

#cust_reply_mail_frm .repply-action .btn-group:hover {
    background: #FFF;
}

#ReplyPopupModal .upload-name-holder {
    width: auto;
    max-width: 300px;
}

.up-label {
    cursor: pointer !important;
}

#ReplyPopupModal .pull-left.ff {
    margin-top: 10px;
}

#ReplyPopupModal .pull-left.ff .text-label {
    float: left;
    line-height: 20px;
    width: auto;
}

.big-ft {
    font-size: 1rem;
    line-height: 20px;
    color: #55595c;
}

.big-ft a {
    color: #3f48cc;
}

.big-ft a .fa {
    color: #8e8e8e;
}

.big-ft a span {
    text-decoration: underline;
}

.tag {
    background: #FFF;
    border-radius: 4px;
    color: #0094e3;
    font-weight: 400;
    display: inline-block;
    height: 23px;
    line-height: 21px;
    padding: 0 20px 0 12px;
    position: relative;
    border: #dcdcdc 1px solid;
    text-decoration: none;
    -webkit-transition: color 0.2s;
}

::after,
::before {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

::selection {
    color: white;
    background: #4c7ff0;
    text-shadow: none;
}

::selection {
    color: white;
    background: #4c7ff0;
    text-shadow: none;
}

.bootstrap-tagsinput {
    width: 100%;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    border-radius: 4px !important;
    padding: 8px 6px 9px !important;
}

.bootstrap-tagsinput .tag {
    margin-right: 2px;
    color: #0094e3;
    font-size: 12px;
    letter-spacing: .3px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #5f6368 !important;
    font-weight: 500;
    padding: 0 5px;
    line-height: 22px;
    background: #fff;
    border: #dcdcdc 1px solid !important;
    border-radius: 4px;
}

.auto-txt-item {
    width: auto;
    display: inline-block;
    white-space: nowrap;
    box-sizing: border-box;
    padding: 6px 15px;
    border: #0094e3 1px solid;
    background: #FFF;
    font-size: 12px;
    color: #0094e3;
    border-radius: 4px;
    cursor: pointer;
    max-width: 200px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

#fix-width {
    position: relative;
    box-sizing: border-box;
    padding-left: 100px;
    padding-right: 45px;
    border-bottom: none !important;
}

.btn-side.owl-theme .owl-nav {
    margin-top: 0px;
}

.btn-side.owl-theme .owl-nav [class*=owl-] {
    color: #0094e3;
    font-size: 28px;
    margin: 0px;
    padding: 0px;
    background: transparent;
    display: block;
    cursor: pointer;
    border-radius: 0px;
    width: 26px;
    height: 41px;
    text-align: center;
    line-height: 41px;
    position: absolute;
    z-index: 33;
    top: 50%;
    transform: translateY(-50%);
    -webkit-transition: all 300ms cubic-bezier(0.7, 0, 0.3, 1);
    transition: all 300ms cubic-bezier(0.7, 0, 0.3, 1);
}

.owl-carousel .owl-nav button.owl-prev {
    left: -30px;
}

.owl-carousel .owl-nav button.owl-next {
    right: -30px;
}

.btn-side.owl-theme .owl-nav [class*=owl-]:hover {
    background: transparent !important;
    color: #000 !important;
    text-decoration: none;
}

.add-com {
    color: #0094e3;
    font-size: 28px;
    margin: 0px;
    padding: 0px;
    background: transparent;
    display: flex;
    width: 59px;
    height: 41px;
    text-align: center;
    line-height: 41px;
    position: absolute;
    z-index: 39;
    top: 50%;
    transform: translateY(-50%);
    left: 15px;
    -webkit-transition: all 300ms cubic-bezier(0.7, 0, 0.3, 1);
    transition: all 300ms cubic-bezier(0.7, 0, 0.3, 1);
}

.add-com .add-com-action {
    color: #0094e3;
    font-size: 28px;
    margin: 0px;
    padding: 0px;
    display: block;
    cursor: pointer;
    border-radius: 0px;
    width: 26px;
    height: 41px;
    text-align: center;
}

.add-com-count {
    color: #0094e3;
    font-size: 12px;
    padding-left: 3px;
    font-weight: 600;
}

#fix-width .dropdown-menu {
    right: auto !important;
    left: 0px !important;
    z-index: 1000;
    min-width: 370px;
    border-radius: 4px;
    padding: 8px !important;
    line-height: normal !important;
    margin: 0px !important;
}

.com-holder-new {
    width: 100%;
    display: block;
    box-sizing: border-box;
}

.com-holder-new .com-holder-fild {
    width: 100%;
    display: block;
    box-sizing: border-box;
    float: none;
}

.com-holder-new .com-holder-fild input {
    width: 100%;
    border: none;
}

.com-holder-new .com-holder-act {
    width: 100%;
    display: block;
    box-sizing: border-box;
    float: none;
    margin-top: 10px;
}

.com-holder-new .com-holder-act a {
    width: 50px;
    height: 21px;
    display: block;
    float: left;
    font-size: 14px !important;
    text-align: center !important;
    line-height: 21px !important;
    color: #FFF;
    background: #1b79cd;
    border-radius: 5px;
}

.com-holder-new .com-holder-act a.no {
    color: #FF0000 !important;
}

.com-holder-new .com-holder-act a.no {
    margin-left: 10px;
    background: #DDD;
}

.add-com-action.dropdown-toggle:after {
    display: none !important;
}

.mail-form-row-full {
    width: 100%;
    height: auto;
    display: inline-block;
    box-sizing: border-box;
    border-top: #f3f3f3 1px solid !important;
    padding-top: 10px !important;
}

.mail-form-row-full h1 {
    font-size: 14px;
    font-weight: 400;
    width: 100%;
    height: auto;
    display: inline-block;
    box-sizing: border-box;
    padding: 0 15px;
}

.mh-32 {
    max-height: 32px;
}

.t-txt {
    width: 100%;
    display: inline-block;
    margin-bottom: 6px;
    box-sizing: border-box;
    padding-right: 0;
}

.t-txt:last-child {
    margin: 0px;
}

.t-txt label {
    width: 100%;
    display: inline-block;
    margin: 0 0 4px 0;
    font-size: 12px;
    line-height: normal;
}

.t-txt input {
    width: 100%;
    border: #c3cad8 1px solid !important;
    border-radius: 5px !important;
    height: 34px !important;
    padding: 10px !important;
}

.t-txt textarea {
    width: 100%;
    border: #c3cad8 1px solid !important;
    border-radius: 5px !important;
    height: 50px !important;
    padding: 10px !important;
    resize: none;
}

a.del-item {
    margin-left: 6px;
    color: #FF0000;
}

.mww-65 {
    width: calc(100% - 65px);
    float: left;
}

.repply-action .btn-group.mw-65.lh-49,
.lh-49 {
    line-height: 49px !important;
    height: 49px !important;
}

.btn-group {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    width: 100%;
}

div.disabled {
    position: relative;
}

div.disabled:after {
    content: '';
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.5);
    position: absolute;
    z-index: 44;
    left: 0px;
    top: 0px;
}

.btn-group {
    width: 100%;
    display: inline-block;
    margin: 0 0 15px 0;
}

.select2-container .select2-selection {
    height: 20px !important;
    max-height: 40px !important;
}

.mce-tinymce {
    border: 1px solid #c9d4ea !important;
    border-radius: 5px !important;
    overflow: hidden;
    outline: none !important;
}

.mce-container-body iframe {
    max-height: 70px !important;
}

#ReplyPopupModal .btn-group {
    margin-bottom: 0px !important;
}

#ReplyPopupModal .repply-action .btn-group.mw-65.lh-49,
#ReplyPopupModal .lh-49 {
    line-height: 45px !important;
    height: 45px !important;
}

#ReplyPopupModal .repply-action {
    padding-top: 25px !important;
    padding-bottom: 0px !important;
}

#ReplyPopupModal .repply-body {
    padding-top: 0px !important;
}

#ReplyPopupModal .repply-action .btn-group.mw-65 {
    height: 45px !important;
    line-height: 45px !important;
}

#ReplyPopupModal .repply-action input.email-full#mail_subject {
    line-height: 45px !important;
    width: calc(100% - 65px) !important;
    box-sizing: border-box !important;
    border: 1px solid #aaa !important;
    border-radius: 4px !important;
    padding-right: 10px !important;
    padding-left: 10px !important;
}

::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background-color: #FFF;
}

::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
}
.modal-content {
    border: none;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}
.modal-header {
    padding: 20px 30px 10px;
    border-bottom: none;
}
.modal-header .close {
    opacity: 1;
    color: #FFF;
    padding: 0px !important;
    background: #008ac9;
    width: 30px !important;
    height: 30px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
    border-radius: 50%;
}
.modal-title {
    margin: 0;
    line-height: 1.5;
    font-size: 18px;
    color: #222;
    font-weight: 600;
}
.modal.in .modal-dialog.modal-dialog-centered {
    transform: translate(0, 0);
}
.pr-30 {
    padding-right: 30px !important;
}
.form-group:not(.row) {
    width: 100%;
    display: inline-block;
}
.background_blue {
    width: 100%;
    height: auto;
    display: inline-block;
    box-sizing: border-box;
    padding: 15px;
    text-align: center;
    color: #000;
    font-size: 18px;
    box-sizing: border-box;
    background: #99d9ea;
    border: #7f7f7f 1px solid;
    line-height: normal;
    cursor: pointer;
    margin: 0px;
    border-radius: 5px !important;
}
#create_quotation_popup_modal .quotation-option .form-group:nth-child(1) .background_blue {
    padding: 0px;
}
.background_blue a {
    color: #000 !important;
    width: 100%;
    display: block;
}
#create_quotation_popup_modal .quotation-option .form-group:nth-child(1) .background_blue a {
    box-sizing: border-box;
    padding: 15px;
}
.background_blue .blue-link {
    color: #008ac9 !important;
    font-size: 16px;
}
.d-none {
    display: none !important;
}
#create_quotation_popup_modal .modal-header{
    padding-left: 0px !important;
    padding-right: 0px !important;
}
#create_quotation_popup_modal .modal-body.pl-30.pr-30{
    padding-left: 0px !important;
    padding-right: 0px !important;
    text-align: center;
}

.alert {
    border-radius: 2px;
}

.alert-danger {
    background-color: #f2d4cd;
    border-color: #efcac1;
    color: #983e28;
}

.btn {
    border-radius: 2px;
    -webkit-transition: all 200ms linear;
    transition: all 200ms linear;
}

.btn:focus,
.btn:active:focus {
    outline: 0;
}

.btn-default {
    color: rgba(0, 0, 0, 0.7);
    background-color: #e8e8e8;
    border-color: #e2e1e1;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
}

.btn-default:hover {
    color: rgba(0, 0, 0, 0.7);
    background-color: #cfcfcf;
    border-color: #c4c2c2;
}

.btn-default:focus {
    color: rgba(0, 0, 0, 0.7);
    background-color: #cfcfcf;
    border-color: #c4c2c2;
}

.btn-default:active {
    color: rgba(0, 0, 0, 0.7);
    background-color: #cfcfcf;
    border-color: #c4c2c2;
    background-image: none;
    box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}

.btn-default:active:hover,
.btn-default:active:focus {
    color: rgba(0, 0, 0, 0.7);
    background-color: #bdbdbd;
    border-color: #a3a0a0;
}

.btn-default:disabled:focus {
    background-color: #e8e8e8;
    border-color: #e2e1e1;
}

.btn-default:disabled:hover {
    background-color: #e8e8e8;
    border-color: #e2e1e1;
}

.btn-primary {
    color: white;
    background-color: #008ac9;
    border-color: #008ac9;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
    font-size: 12px;
    padding: 4px 8px;
}

.btn-primary:hover {
    color: white;
    background-color: #0b8bc5;
    border-color: #008ac9;
}

.btn-primary:focus {
    color: white;
    background-color: #1d5dec;
    border-color: #1457eb;
}

.btn-primary:active {
    color: white;
    background-color: #1d5dec;
    border-color: #1457eb;
    background-image: none;
    box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}

.btn-primary:active:hover,
.btn-primary:active:focus {
    color: white;
    background-color: #124ed4;
    border-color: #0f40ae;
}

.btn-primary:disabled:focus {
    background-color: #4c7ff0;
    border-color: #4c7ff0;
}

.btn-primary:disabled:hover {
    background-color: #4c7ff0;
    border-color: #4c7ff0;
}

.nav>li>a:hover,
.nav>li>a:focus {
    color: #fff;
    background-color: #4c7ff0;
}

.nav-tabs {
    margin-right: -1px;
    margin-left: -1px;
    border: 0;
}

.nav-tabs .nav-item {
    position: relative;
    display: block;
    margin: 0;
    text-align: center;
    text-decoration: none;
}

.nav-tabs .nav-item .nav-link {
    font-weight: 500;
    padding: 0.75rem 1rem;
    border-color: transparent;
    border-bottom: 0;
    border-radius: 3px 3px 0 0;
    color: rgba(0, 0, 0, 0.7);
    font-size: 0.8125rem !important;
}

.nav-tabs .nav-item .nav-link:hover,
.nav-tabs .nav-item .nav-link:focus {
    color: white;
    background-color: #4c7ff0;
}
#prod_lead .col-md-12 .btn-round-shadow {
    padding: 9px 25px !important;
}
.nav-tabs .nav-item .nav-link.active {
    color: rgba(0, 0, 0, 0.7);
    border-color: rgba(0, 0, 0, 0.1);
    background-color: white;
}
.search-item {
    width: auto;
    display: inline-flex;
    background: #f7f6f6;
    box-sizing: border-box;
    padding: 10px;
    font-size: 16px;
    color: #757474;
    line-height: normal;
}
.search-item a {
    color: #FF0000;
    margin-right: 8px;
}
.row {
    margin-right: -15px;
    margin-left: -15px;
}
.col-auto {
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
    flex: 0 0 auto;
    width: auto;
    max-width: none;
    float: left;
}
.tab-content {
    padding: 1rem;
    border-radius: 0 0 2px 2px;
    background-color: white;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05), 0 1px 1px rgba(0, 0, 0, 0.05);
}

.btn {
    box-shadow: none;
}

.w-100 {
    width: 100%;
}

.modal-title {
    margin: 0;
    line-height: 1.5;
    font-size: 18px;
    color: #222;
    font-weight: 600;
}

.modal-header {
    padding: 20px 30px 10px;
    border-bottom: none;
}

.btn-primary {
    padding-top: 12px;
    padding-bottom: 12px;
}

#prod_lead .modal-title {
    font-size: 22px;
}

button:focus {
    outline: none !important;
}

button.btn-primary:not(.download-dropdown) {
    background-color: #008ac9 !important;
    border-color: #008ac9 !important;
    font-size: 14px !important;
    border-radius: 4px !important;
    color: #FFF !important;
    border: none !important;
}

.modal-header .close {
    opacity: 1;
    color: #008ac9;
}

.btn-primary {
    border-radius: 4px;
}

.modal-header .close {
    opacity: 1;
    color: #FFF;
    padding: 4px 8px;
    background: #008ac9;
}

.modal-header .close:hover {
    color: white;
    background-color: #c04f33;
}

input,
select {
    outline: none !important;
}

.btn-default:active {
    background-color: #FFF;
}

.modal-header .close {
    opacity: 1;
    color: #FFF;
    padding: 0px !important;
    background: #008ac9 url(<?=assets_url();?>/images/cancel_close_icon.png) no-repeat 50% 50%;
    background-size: 20px auto;
    width: 30px !important;
    height: 30px !important;
    line-height: 30px !important;
    text-align: center !important;
    transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
    border-radius: 50%;
    font-size: 0px !important;
    color: #008ac9 !important;
}

::-webkit-input-placeholder {
    font-size: 14px !important;
}

:-ms-input-placeholder {
    font-size: 14px !important;
}

::placeholder {
    font-size: 14px !important;
}

.form-group:not(.row) {
    width: 100%;
    display: inline-block;
}

::after,
::before {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

::selection {
    color: white;
    background: #4c7ff0;
    text-shadow: none;
}

::selection {
    color: white;
    background: #4c7ff0;
    text-shadow: none;
}

.default-input {
    width: 100%;
    height: 40px;
    line-height: 38px;
    border-radius: 5px;
    padding: 0 10px !important;
    font-size: 13px;
    background: #FFF;
    border: #c3cad8 1px solid;
}

.default-select {
    width: 100%;
    height: 40px;
    line-height: 38px;
    border-radius: 5px;
    padding-left: 12px;
    font-size: 13px;
    border: #c3cad8 1px solid;
}

#prod_lead .form-group.row {
    margin-bottom: 15px;
    margin-top: 15px;
    width: inherit !important;
    display: inherit !important;
}

.nav-tabs .nav-item .nav-link {
    transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
}

.nav-tabs .nav-item .nav-link {
    font-weight: 500;
    padding: 0.75rem 1rem;
    border-color: transparent;
    border-bottom: 0;
    border-radius: 3px 3px 0 0;
    background-color: #f2f2f2 !important;
}

.nav-tabs .nav-item .nav-link:hover,
.nav-tabs .nav-item .nav-link.active,
.nav-tabs .nav-item .nav-link:focus {
    color: white !important;
    background-color: #008ac9 !important;
}

#prod_lead .modal-header {
    padding: 30px 30px;
    border-bottom: none;
}

.w-100 {
    width: 100% !important;
}

::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background-color: #FFF;
}

::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
}


.form-control::placeholder {
    color: rgba(38, 38, 38, 0.4);
}

.form-control:disabled {
    background-color: #e8e8e8;
}

.form-control:focus {
    border-color: #84a7f5;
    box-shadow: none;
}

.table {
    border-radius: 2px;
}

.table th,
.table td {
    padding-left: 1rem;
    padding-right: 1rem;
    border-color: rgba(0, 0, 0, 0.1);
}

.table thead th {
    border-bottom-width: 0;
    border-color: rgba(0, 0, 0, 0.1);
}

.text-primary {
    color: #4c7ff0 !important;
}

.bt {
    bottom: 0;
}

.padding0 {
    padding: 0 !important;
}

.page_three {
    width: 100%;
    float: left;
    text-align: center;
    padding: 14px 0 7px;
}

.page_three a {
    padding: 5px 28px 7px;
    color: #fff;
    background: #008ac9;
    font-size: 16px;
    border-radius: 4px;
}

.check-box-sec {
    display: block;
    position: relative;
    line-height: 20px;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 15px;
    color: #000;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.check-box-sec input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #fff;
    border: solid 1px #ddd;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.check-box-sec input:checked~.checkmark:after {
    display: block;
}

.check-box-sec .checkmark:after {
    left: 7px;
    top: 3px;
    width: 5px;
    height: 10px;
    border: solid #008ac9;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

.mt-15 {
    margin-top: 15px;
}

.table td {
    padding: 0.9375rem !important;
    font-size: 13px;
    font-weight: 500;
    color: #505050;
}

.table>thead>tr>th {
    padding: 14px 8px;
}

.add_additional_charges {
    font-size: 17px;
    color: #0954de;
}

select.form-control:not([size]):not([multiple]) {
    height: 42px;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #eee;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.text-center {
    text-align: center;
}

.grand-summery td {
    border: solid 1px #969798 !important;
}

.check-box-sec {
    width: auto;
    height: 20px;
    display: inline-block;
    margin: 0;
    padding: 0;
    min-width: 20px;
}

.ff .check-box-sec {
    float: left;
    margin: 0 5px 0 0;
}

.ff {
    line-height: 26px !important;
}

input,
textarea,
select {
    outline: none !important;
}

#accordion .panel-title {
    font-size: 14px;
    color: inherit;
    line-height: 20px !important;
}

.ff .check-box-sec {
    height: 20px;
    display: block;
    padding: 0;
    width: 20px;
    float: left !important;
}

#accordion h4.panel-title {
    margin: 0px !important;
}

#accordion .panel.panel-default {
    margin-bottom: 10px;
}

#accordion .panel.panel-default .panel-heading {
    padding: 6px !important;
    border: solid 1px #ddd;
    background: #f2f2f2;
}

#accordion .panel.panel-default .panel-collapse {
    margin-top: 10px;
}

.auto-row img.ui-datepicker-trigger {
    vertical-align: middle;
    width: 19px;
    height: 30px;
    object-fit: contain;
}

.fl {
    width: 20px !important;
    float: left !important;
}

.blue-title {
    width: 100%;
    height: auto;
    display: inline-block;
    font-size: 18px;
    font-weight: 500;
    color: #00a2ed;
    margin: 0 0 15px 0;
}

.ml-10 {
    margin-left: 10px;
}

.d-flex {
    display: flex;
}

::-webkit-input-placeholder {
    font-size: 14px !important;
}

:-ms-input-placeholder {
    font-size: 14px !important;
}

::placeholder {
    font-size: 14px !important;
}

.table {
    width: 100%;
}

.form-group:not(.row) {
    width: 100%;
    display: inline-block;
}

.mt-15 {
    margin-top: 15px !important;
}

.quotation-table tr td {
    vertical-align: middle !important;
}

.quotation-table .form-group {
    width: 100%;
    display: inline-block;
    margin: 0px !important;
}

.quotation-table tbody tr td,
.quotation-table thead tr th {
    border-color: #c9d4ea !important;
}

.quotation-table {
    border: #c9d4ea 1px solid !important;
}

.quotation-table tbody tr:last-child {
    background: #FFF !important;
}

.grand-summery tbody tr td {
    padding: 10px;
}

.page_three.new a {
    padding: 10px 20px !important;
    color: #fff;
    background: #008ac9;
    font-size: 16px;
    border-radius: 4px;
}

.quotation-search {
    width: 100%;
    height: auto;
    display: inline-block;
}

.form-control#currency_type_new {
    height: 32px !important;
    box-shadow: none;
    padding: 0 10px !important;
    width: 70px !important;
    float: left !important;
}

#automated_quotation_popup_modal .grand-summery tbody tr td,
#automated_quotation_popup_modal .quotation-table tbody tr td,
#automated_quotation_popup_modal .quotation-table thead tr th {
    padding: 5px !important;
    vertical-align: middle !important;
}

#automated_quotation_popup_modal .calender-input {
    margin: 0px !important;
}

#automated_quotation_popup_modal .form-control#currency_type_new {
    height: 24px !important;
    line-height: 24px !important;
    box-shadow: none !important;
    padding: 0 10px !important;
    width: 70px !important;
    float: none !important;
    margin: 0 auto !important;
}

#automated_quotation_popup_modal .form-control {
    height: 34px;
    border-color: rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    box-shadow: none;
}

#automated_quotation_popup_modal #accordion .panel.panel-default .panel-collapse {
    margin-top: 0px;
}

#automated_quotation_popup_modal #accordion .panel-body {
    padding: 5px;
}

#automated_quotation_popup_modal #accordion .panel-body textarea {
    height: 35px !important;
}

#automated_quotation_popup_modal .panel-group {
    margin-bottom: 0px;
}

.q-title {
    font-size: 22px;
    font-weight: 700;
    margin: 0;
    color: #000;
}

.color-select {
    color: #fff !important;
    background: #008ac9 !important;
}

#automated_quotation_popup_modal .quotation-table thead tr th {
    background: #f3f3f3;
}

.grand-summery td {
    border-color: #c9d4ea !important;
}

.mobile-scoller {
    width: 100%;
    height: auto;
    display: inline-block;
}

::after,
::before {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

::selection {
    color: white;
    background: #4c7ff0;
    text-shadow: none;
}

::selection {
    color: white;
    background: #4c7ff0;
    text-shadow: none;
}

.date-input,
.default-input {
    width: 100%;
    height: 40px;
    line-height: 38px;
    border-radius: 5px;
    padding: 0 10px !important;
    font-size: 13px;
    background: #FFF;
    border: #c3cad8 1px solid;
}

.date-input {
    padding-right: 35px !important;
}

.full-width .ui-datepicker-trigger {
    vertical-align: middle;
    width: 20px;
    height: 38px;
    object-fit: contain;
    position: absolute;
    z-index: 3;
    top: 1px;
    right: 10px;
}

.default-select {
    width: 100%;
    height: 40px;
    line-height: 38px;
    border-radius: 5px;
    padding-left: 12px;
    font-size: 13px;
    border: #c3cad8 1px solid;
}

.full-width {
    width: 100%;
    display: inline-block;
    margin-bottom: 0px;
    position: relative;
}

.payment-loop {
    box-sizing: border-box;
    padding: 10px;
    background: rgb(243, 243, 243);
    border-radius: 6px;
}

.payment-loop:last-child {
    margin-bottom: 0px;
}

.btb {
    border-top: 1px solid rgb(243, 243, 243) !important;
    border-bottom: 1px solid rgb(243, 243, 243) !important;
    padding-top: 4px !important;
    padding-bottom: 4px !important;
}

.payment-loop.content-equel {
    box-sizing: border-box;
    padding: 0px;
    background: transparent;
    border-radius: 0px;
}

.quotation-table {
    margin: 0px !important;
    border: 1px solid rgb(221, 221, 221) !important;
}

.quotation-table tr,
.quotation-table.table>thead>tr>th {
    vertical-align: middle !important;
}

.quotation-table thead tr th {
    font-family: Inter, sans-serif;
    background: rgb(243, 243, 243) !important;
    padding: 8px !important;
    color: rgb(0, 0, 0) !important;
    border-bottom: none !important;
    border-right: 1px solid rgb(221, 221, 221) !important;
}

.quotation-table tr,
.quotation-table.table>thead>tr>th {
    vertical-align: middle !important;
}

.quotation-table tbody tr td:nth-child(1),
.quotation-table tbody tr td:nth-child(2) {
    text-align: left;
}

.quotation-table tbody tr td {
    text-align: center;
}

.quotation-table tbody tr td {
    font-family: Inter, sans-serif;
    background: transparent !important;
    padding: 8px !important;
    border-color: rgb(221, 221, 221) !important;
}

.quotation-table>tbody>tr>td {
    border-right: 1px solid rgb(201, 212, 234) !important;
}

.small-select {
    height: 30px !important;
    padding: 0px 10px !important;
    font-size: 14px !important;
    max-width: 75px !important;
    display: inline-block !important;
}

.default-select.width-80 {
    width: 80px !important;
}

.payment-loop {
    margin-bottom: 15px;
}

.payment-loop:last-child {
    margin-bottom: 15px;
}

.width-60 {
    width: 60px !important;
}

.btb {
    padding-top: 10px !important;
    padding-bottom: 10px !important;
}

.table-borderless.quotation-table {
    border: none !important;
}

.table-borderless.quotation-table>tbody>tr>td {
    border-right: 1px #f3f3f3 solid !important;
    border-top: 1px #f3f3f3 solid !important;
    border-left: 4px #f3f3f3 solid !important;
    border-bottom: 4px #f3f3f3 solid !important;
    background-color: #FFF !important;
}

.table-borderless.quotation-table>tbody>tr {
    border: none !important;
}

.table-borderless.quotation-table thead tr th {
    background-color: #f3f3f3 !important;
    border-right: 4px #FFF solid !important;
    border-top: 1px #f3f3f3 solid !important;
    border-left: 4px #f3f3f3 solid !important;
    border-bottom: 4px #FFF solid !important;
}

.table-borderless.quotation-table thead tr th:last-child {
    border-right: 1px #f3f3f3 solid !important;
}

.table-borderless.quotation-table .default-input {
    border-radius: 5px;
    padding: 0 4px;
    font-size: 13px;
    text-align: center;
}

.width-80 {
    width: 80px !important;
}

.width-60 {
    width: 60px !important;
}

.table-borderless.quotation-table .ml-10 {
    margin-left: 5px !important;
}

.table-borderless.quotation-table .del_inv_product {
    position: absolute;
    right: 24px;
}

.bt {
    font-size: 17px !important;
    font-weight: 700 !important;
}

.text-center {
    text-align: center !important;
}

.grand-summery.table-borderless {
    border: none !important;
}

.grand-summery.table-borderless tbody tr td {
    border: none !important;
    padding: 5px 10px !important;
}

.grand-summery.table-borderless tr {
    border: none !important;
}

.grand-summery.table-borderless tr td {
    text-align: right;
}

.grand-summery.table-borderless tr:last-child td,
.grand-summery.table-borderless tr:nth-child(1) td {
    text-align: right;
}

.grand-summery.table-borderless tr td:nth-child(2) {
    border-right: 1px #f3f3f3 solid !important;
    border-top: 1px #f3f3f3 solid !important;
    border-left: 4px #f3f3f3 solid !important;
    border-bottom: 4px #f3f3f3 solid !important;
    background-color: #FFF !important;
}

.grand-summery.table-borderless tr:nth-child(1) td:nth-child(2) {
    border: none !important;
}

.table-borderless .small-select {
    padding: 0px 2px !important;
    font-size: 13px !important;
    display: inline-block !important;
}

.quotation-table .default-select {
    text-align: center;
}

.per-span {
    line-height: 40px;
    margin-left: 5px;
    font-style: italic;
}

.quotation-table .default-select {
    padding-left: 1px !important;
}

.name_of_authorised_signature_div img {
    width: auto;
    height: 100px;
    display: block;
}

.grand-summery tr:last-child td span {
    font-weight: 700 !important;
    font-size: 12px !important;
}

.table-borderless.quotation-table>tbody>tr>td:last-child {
    padding-right: 30px !important;
}

#automated_quotation_popup_modal .date-input,
#automated_quotation_popup_modal .default-input {
    padding: 0 5px;
}

#automated_quotation_popup_modal .calculate_quotation_price_update {
    min-width: 60px !important;
}

#automated_quotation_popup_modal .form-control {
    height: 40px !important;
    line-height: 38px !important;
    padding: 0 5px !important;
}

.small-txt .blue-title {
    color: #198aca;
    font-size: 14px;
    font-weight: 400;
    line-height: normal;
    margin: 0;
    text-align: left;
    margin: 0 0 5px 0;
}

.mce-tinymce {
    border: 1px solid #c9d4ea !important;
    border-radius: 5px !important;
    overflow: hidden;
    outline: none !important;
}

#automated_quotation_popup_modal .table-borderless.quotation-table tbody tr td {
    vertical-align: middle !important;
}

#automated_quotation_popup_modal .table-borderless.quotation-table tbody tr td:last-child {
    padding-right: 30px !important;
}

#automated_quotation_popup_modal .table-borderless.quotation-table .del_inv_product {
    right: 44px !important;
}

#automated_quotation_popup_modal .table-borderless.quotation-table .mce-panel {
    height: 120px;
    overflow: hidden;
}

#automated_quotation_popup_modal .quotation-table .default-input {
    padding: 0 3px !important;
}

#automated_quotation_popup_modal select.form-control {
    height: 26px !important;
    line-height: 26px !important;
    padding: 0 5px !important;
}

.row-picture {
    width: 100%;
    display: none;
    margin-top: 10px;
}

#automated_quotation_popup_modal_body h1,
#automated_quotation_popup_modal_body h4,
#automated_quotation_popup_modal_body b,
#automated_quotation_popup_modal_body *:not(.fa) {
    font-family: 'Inter', sans-serif !important;
}

.auto-ul-new {
    width: auto;
    display: inline-flex;
}

.auto-ul-new>li {
    margin-right: 15px;
}

.auto-ul-new>li:last-child {
    margin-right: 0px;
}

.auto-ul-new>li .check-box-sec {
    width: auto;
    height: 20px;
    display: block;
    margin: 0 6px 0 0;
    padding: 0;
    min-width: 20px;
    float: left;
}

.row-brochure,
.row-video {
    width: 100%;
    height: auto;
    display: none;
    position: relative;
    margin-top: 10px;
}
.up-check {
    width: 38px;
    height: 38px;
    background-color: #FFF;
    position: absolute;
    z-index: 4;
    left: 1px;
    top: 1px;
    box-sizing: border-box;
    padding: 12px;
    border-radius: 5px 0 0 5px;
    border-right: #c3cad8 1px solid;
    transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
}
.view-up {
    width: 38px;
    height: 38px;
    background-color: #FFF;
    position: absolute;
    z-index: 4;
    right: 1px;
    top: 1px;
    box-sizing: border-box;
    padding: 5px;
    border-radius: 0 5px 5px 0;
    border-left: #c3cad8 1px solid;
    transition: all 0.3s;
    -moz-transition: all 0.3s;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
}
.view-up img {
    width: 100%;
    height: 28px;
    object-fit: cover;
    display: block;
}
.row-picture {
    width: 100%;
    display: none;
    margin-top: 10px;
}
.row-picture.show {
    display: inline-block;
}
.row-picture > ul {
    margin-right: -5px;
    margin-left: -5px;
}
.row-picture > ul > li {
    position: relative;
    float: left;
    width: 33.333333%;
    min-height: 1px;
    padding-left: 5px;
    padding-right: 5px;
}
.image-check {
    width: 100%;
    height: auto;
    display: block;
    position: relative;
    margin: 0px;
    cursor: pointer;
    box-sizing: border-box;
    border: 1px solid #c9d4ea !important;
    border-radius: 5px !important;
    overflow: hidden;
    box-sizing: border-box;
}
.image-check img {
    width: 100%;
    height: 65px;
    display: block;
    object-fit: contain;
}
.blue-title-txt {
    color: #198aca;
    font-size: 14px;
    font-weight: 400;
    line-height: normal;
    margin: 0;
    text-align: left;
    margin: 0 0 5px 0;
}

.gst-extra.hide {
    display: none !important;
}

.default-uploaded {
    width: auto;
    display: inline-block;
    padding: 5px 20px !important;
    color: #fff;
    background: #008ac9;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
}

.gst-extra-block {
    width: 100%;
    height: auto;
    display: none;
    margin: 0 0 15px 0;
    text-align: right;
}

.mce-container-body iframe {
    max-height: 70px !important;
}

.quotation-table .mce-container-body iframe {
    height: 120px !important;
    max-height: initial !important;
}

#automated_quotation_popup_modal .form-control.calculate_quotation_price_update {
    text-align: center !important;
    margin-left: auto;
    margin-right: auto;
}

.small-txt {
    font-size: 14px !important;
}

.table th {
    white-space: nowrap;
}

.d-flex {
    display: flex !important;
}

.text-center {
    text-align: center !important;
}

.ml-10 {
    margin-left: 10px !important;
}

.table>thead>tr>th {
    padding: 0.9375rem !important;
}

/* #quotation_product_sortable,
#quotation_additional_sortable {
    cursor: move;
} */
.modal-big .modal-dialog {
    max-width: 954px !important;
}
/*app css*/
        body{
            background: #FFF !important;
            overflow-x: hidden !important;
        }
       .content-view{
            background: #FFF;
        }
        .process-sec.card.no-app {
            margin-bottom: 0;
            display: inline-block;
            width: 100%;
            border-radius: 0px;
            box-shadow: none;
        }
        .process-sec.card.no-app .lead-edit-tab .tab-section {
            border: none;
            */: ;
            display: inline-block;
            width: 100%;
            margin-top: 3px;
            border-radius: 0px;
            box-shadow: none;
        }
        .process-sec.card.no-app .card-block {
            padding: 0;
        }
        .modal-dialog {
            margin: 0px auto 0px !important;
        }
        #prod_lead .modal-content,
        .mail-modal .modal-content {
            position: relative;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: none !important;
            border-radius: 0 !important;
            outline: 0;
            margin-left: -1px !important;
        }
        .modal-backdrop.in {
            opacity: 1 !important;
            background: #FFF !important;
        }
        #prod_lead .modal-dialog {
            max-width: 900px !important;
        }
        .modal-content {
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }
        .tab-content{
            margin-bottom: 10px !important;
        }
        .lead-edit-tab .update_proposal {
            padding: 15px 25px !important;
        }
        .tab-content {
            margin-bottom: 0px !important;
            padding-bottom: 5px !important;
        }
        #prod_lead .modal-header {
            padding: 30px 30px 0px !important;
            border-bottom: none;
        }
        #prod_lead .table thead td{
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }

        @media (max-width: 700px) {
          .no-more-tables table,
          .no-more-tables thead,
          .no-more-tables tbody,
          .no-more-tables th,
          .no-more-tables td,
          .no-more-tables tr {
            display: block; 
          }
          .no-more-tables thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px; 
        }
          .no-more-tables tr {
            border: 0.0625rem solid rgba(0, 0, 0, 0.1); 
        }
          .no-more-tables td {
            position: relative;
            padding-left: 40% !important;
            border-top: 0 !important;
            border-bottom: 0.0625rem solid rgba(0, 0, 0, 0.1);
            text-align: left;
            white-space: normal; }
          .no-more-tables td:before {
            position: absolute;
            top: 0.375rem;
            left: 0.375rem;
            padding-right: 0.625rem;
            width: 45%;
            text-align: left;
            white-space: nowrap;
            font-weight: 600; }
          .no-more-tables td:before {
            content: attr(data-title); 
        }         
        }
        .no-display{
            display: none;
        }
        .display-block{
            display: block;
        }
        #loader {
        position: fixed;
        top: 85%; left: 48%;
        transform: translate(-50%, -50%);
        }

        .auto_rearrange{
            font-size: 14px;
            font-weight: 600;
            color: #FFF;
            display: inline-flex;
            align-items: center;
            background: #008ac9;
            box-sizing: border-box;
            padding: 3px 6px;
            border-radius: 4px;
        }
        .auto_rearrange .fa{
            margin-right: 6px;
            font-size: 12px;
        }
        .auto_rearrange:hover{
            color: #FFF;
        }
        #rander_quotation_product_rearrange_view_modal .modal-dialog {
            max-width: 100%;
        }
        #rander_quotation_product_rearrange_view_modal .list-group-item{
            font-size: 13px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        #rander_quotation_product_rearrange_view_modal .list-group-item .badge {
            padding: 3px 7px;
            color: #000;
            background-color: #e8e8e8;
            border-radius: 10px;
        }
        #rander_quotation_product_rearrange_view_modal .list-group-item .left-txt{
            max-width: 80%;
        }
        #rander_quotation_product_rearrange_view_modal .modal-header {
            padding: 20px 15px 10px;
            border-bottom: none;
        }
        .btn-blue{
            background: #008ac9 !important;
            color: #FFF !important;
        }
        .modal-header .btn-blue {
        opacity: 1;
        color: #FFF !important;
        padding: 5 10px !important;
        width: 140px !important;
        height: 40px !important;
        line-height: 30px !important;
        text-align: center !important;
        transition: all 0.3s;
        -moz-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        border-radius: 4px;
        font-size: 14px !important;
        display: block;
        font-weight: 400 !important;
        margin: 0;
        position: absolute;
        top: 0;
        right: 15px;
        text-indent: auto;
    }
   </style>
  </head>
  <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>" />
  <body class="logo-loader">    
    <div class="app-- full-width--- expanding---"> 
        <div class="main-panel---">          
          <div class="main-content---">              
              <div class="content-view">                
                <div class="layout-md card process-sec no-app">
                    <div class="layout-column-md">
                        <div class="card-block">
                            <div class="tsf-wizard tsf-wizard-1">
                                <div class="tsf-container">                                    
                                    <div class="">
                                    <div class="lead_id heading-text-ar">                                    
                                     <div class="row">
                                    <div class="col-md-12">
                                    <!-- MESSAGE START -->
                                 <?php if($this->session->flashdata('error_msg')!=''){ ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            <?php echo $this->session->flashdata('error_msg'); ?>
                            </div>
                            <?php } ?>
                            <?php if($this->session->flashdata('success_msg')!=''){ ?>
                            <div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                           </button>
                            <?php echo $this->session->flashdata('success_msg'); ?>
                        </div>
                        <?php } ?>
                        <!-- MESSAGE END -->
                        </div>
                    </div>                    
                </div>
                <div class=" tab_gorup lead-edit-tab">                   

        <div class="tab-section">
            <div id="lead_quotation_list" class="tabcontent">                
                <?php if($cus_data->current_stage_id!='4' && $cus_data->current_stage_id!='3' && $cus_data->current_stage_id!='5' && $cus_data->current_stage_id!='6' && $cus_data->current_stage_id!='7'){ ?>
                <div class="lead-show-position text-right pb-0">
                    <input type="hidden" id="cust_email" value="<?php echo $cus_data->cus_email; ?>">
                    <button class="btn btn-primary btn-round-shadow mt-10 mr-10" id="create_quotation" ><i class="fa fa-plus" aria-hidden="true"></i> Create Quotation</button>
                </div>
                <?php } ?>
                <?php 
                if($opportunity_list)
                { 
                foreach($opportunity_list as $opportunity_data)
                {
                    // $followup_date = date("d M Y", strtotime($opportunity_data->followup_date)); ?>
                    <div class="m-10px">
                        <div class="update_proposal">
                            <h5><?=$opportunity_data->opportunity_title;?> #<?=$opportunity_data->id;?></h5>
                            <p>
                                <b>Status :</b> 
                                <span class="<?php echo $opportunity_data->status_class_name; ?>">
                                    <?php echo $opportunity_data->status_name; ?>
                                </span> |                                                                    
                                <b>Deal Value:</b>

                                <?php 
                                if($opportunity_data->deal_value>0){
                                    echo $opportunity_data->currency_code; ?> <?=number_format($opportunity_data->deal_value,2);
                                }
                                else
                                {
                                    echo'N/A';
                                }
                                ?> |
                                <b>No. of Product(s):</b>
                                <?php
                                if($opportunity_data->product_count>0)
                                {
                                    echo $opportunity_data->product_count;
                                }
                                else
                                {
                                    echo'N/A';
                                }    
                                ?> |
                                <b>Created On.:</b><?php echo date_db_format_to_display_format($opportunity_data->create_date);?> |
                                <b>Last Modified On.:</b><?php echo date_db_format_to_display_format($opportunity_data->modify_date);?>
                            </p>
                            <?php 
                            if($opportunity_data->tot_quotation > 0) 
                            {  
                            ?>    
                                <p>
                                    <b>Quotation Sent On.:</b><?php echo date_db_format_to_display_format($opportunity_data->quotation_sent_on);?> |
                                    <b>Quotation Type:</b>
                                    <?php if($opportunity_data->is_extermal_quote=='Y'){
                                        $ext=($opportunity_data->file_name=='')?'( Without Quotation )':'';
                                    echo'<span class="text-danger"> <b>Custom '.$ext.'</b></span>';            
                                    } else{
                                    echo'<span class="text-danger"><b>Automated</b></span>';
                                    } ?> |

                                    <b>Purchase Order Status:</b>
                                    <?php if($opportunity_data->is_po_received=='Y'){
                                    echo'<span class="text-success"> <b>Received</b></span>';
                                    if($opportunity_data->po_file_name)
                                    {
                                        echo ' (<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/download_po/'.$opportunity_data->lowp_id.'">  Download PO <i class="fa fa-cloud-download" aria-hidden="true"></i></a> )';
                                    }
                                    
                                    } else{
                                    echo'<span class="text-danger"><b>Not Received</b></span>';
                                    } ?>
                                </p>

                                <?php if($opportunity_data->is_extermal_quote=='N'){ ?>
                                <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Preview <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a> &nbsp;|&nbsp;
                                <?php } ?>
                                <?php if($opportunity_data->is_extermal_quote=='N'){ ?>
                                <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Download <i class="fa fa-cloud-download" aria-hidden="true"></i></a> 
                                <?php if($cus_data->current_stage_id!='4'){ ?>
                                &nbsp;|&nbsp; <a href="JavaScript:void(0)" class="is_copy_confirm" data-url="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/clone_proporal/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" data-existingname="<?=$opportunity_data->opportunity_title;?>">Copy <i class="fa fa-clone" aria-hidden="true"></i></a>
                                <?php } ?>
                                <?php } ?>

                                <?php if($opportunity_data->is_extermal_quote=='Y' && $opportunity_data->file_name!=''){ ?>
                                <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Download <i class="fa fa-cloud-download" aria-hidden="true"></i></a> 
                                
                                <?php } ?>
                                
                                <?php 
                                if($opportunity_data->status==1) 
                                { 
                                ?>
                                    <div class="clear"></div>
                                    <?php if($opportunity_data->is_extermal_quote=='N'){ ?>
                                    <a href="JavaScript:void(0)" class="btn btn-primary btn-round-shadow pull-left mt-10px" onclick="edit_qutation_view_modal('<?php echo $opportunity_data->id; ?>','<?php echo $opportunity_data->q_id; ?>')">Edit Quotation PDF</a>
                                    <?php }else{ ?> 
                                    <a href="JavaScript:void(0);" class="btn btn-primary btn-round-shadow pull-left mt-10px send_quotation_to_buyer_modal" data-oppid="<?php echo $opportunity_data->id; ?>" data-qid="<?php echo $opportunity_data->q_id; ?>">Sent Quotation PDF</a>
                                    <?php } ?>    
                                <?php } ?>  
                                <div class="clear mt-10"></div>
                                <?php 
                                } 
                                if($opportunity_data->status==2) 
                                { 
                                ?>
                                    <?php if($cus_data->current_stage_id!='4'){ ?>                          
                                    <?php if((($opportunity_data->is_extermal_quote=='Y' && $opportunity_data->file_name!='') || $opportunity_data->is_extermal_quote=='N') && $cus_data->cus_mobile!=''){ ?>
                                    <a href="JavaScript:void(0);" style="margin-right:0px; width: 38px; height: 38px; display:block; font-size:34px; text-align:center;line-height: 38px;color: #008ac9" class="pull-right quotation_sent_by_whatsapp" data-lid="<?php echo $lead_id; ?>" data-oppid="<?php echo $opportunity_data->id; ?>" data-qid="<?php echo $opportunity_data->q_id; ?>" >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="38px" height="38px"><path style="fill:#25D366;" d="M224 122.8c-72.7 0-131.8 59.1-131.9 131.8 0 24.9 7 49.2 20.2 70.1l3.1 5-13.3 48.6 49.9-13.1 4.8 2.9c20.2 12 43.4 18.4 67.1 18.4h.1c72.6 0 133.3-59.1 133.3-131.8 0-35.2-15.2-68.3-40.1-93.2-25-25-58-38.7-93.2-38.7zm77.5 188.4c-3.3 9.3-19.1 17.7-26.7 18.8-12.6 1.9-22.4.9-47.5-9.9-39.7-17.2-65.7-57.2-67.7-59.8-2-2.6-16.2-21.5-16.2-41s10.2-29.1 13.9-33.1c3.6-4 7.9-5 10.6-5 2.6 0 5.3 0 7.6.1 2.4.1 5.7-.9 8.9 6.8 3.3 7.9 11.2 27.4 12.2 29.4s1.7 4.3.3 6.9c-7.6 15.2-15.7 14.6-11.6 21.6 15.3 26.3 30.6 35.4 53.9 47.1 4 2 6.3 1.7 8.6-1 2.3-2.6 9.9-11.6 12.5-15.5 2.6-4 5.3-3.3 8.9-2 3.6 1.3 23.1 10.9 27.1 12.9s6.6 3 7.6 4.6c.9 1.9.9 9.9-2.4 19.1zM400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM223.9 413.2c-26.6 0-52.7-6.7-75.8-19.3L64 416l22.5-82.2c-13.9-24-21.2-51.3-21.2-79.3C65.4 167.1 136.5 96 223.9 96c42.4 0 82.2 16.5 112.2 46.5 29.9 30 47.9 69.8 47.9 112.2 0 87.4-72.7 158.5-160.1 158.5z"/></svg>
                                        
                                    </a>
                                    <?php } ?>

                                    <?php if($cus_data->cus_email!='' || $cus_data->cus_alt_email!=''){ ?>                        
                                    <a href="javascript:" id="" style="margin-right:5px; width: 38px; height: 38px; display:block; font-size:24px; text-align:center;line-height: 24px;color: #008ac9" class="pull-right qutation_re_send_to_buyer" data-opportunityid="<?php echo $opportunity_data->id; ?>" data-quotationid="<?php echo $opportunity_data->q_id; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="38px" height="38px">
                                            <path style="fill: #008ac9;" d="M384 32H64C28.63 32 0 60.63 0 96v320c0 35.38 28.62 64 64 64h320c35.38 0 64-28.62 64-64V96C448 60.63 419.4 32 384 32zM384 336c0 17.67-14.33 32-32 32H96c-17.67 0-32-14.33-32-32V225.9l138.5 69.27C209.3 298.5 216.6 300.2 224 300.2s14.75-1.688 21.47-5.047L384 225.9V336zM384 190.1l-152.8 76.42c-4.5 2.25-9.812 2.25-14.31 0L64 190.1V176c0-17.67 14.33-32 32-32h256c17.67 0 32 14.33 32 32V190.1z"/>
                                        </svg>
                                    </a>
                                    <?php } ?>
                                    <?php } ?>
                            <?php 
                            }
                            else
                            {
                                
                            }  
                            ?>
                            <div id="update_<?=$opportunity_data->id;?>" class="opp_ajax_details" style="display: none;"></div>
                        </div>
                    </div>
                <?php 
                } 
                }
                else
                { 
                ?>
                    <div class="m-10px">
                        <div class="update_proposal">
                            <div class="width_full bold-txt">No Quotation Found.</div>
                        </div>
                    </div>
                <?php 
                } 
                ?>
            </div>
        </div>
            </div>            
        </div>
        <input type="hidden" id="lead_id" name="lead_id" value="<?php echo $cus_data->id;?>" />
        <input type="hidden" name="selected_prod_id" id="selected_prod_id" value="<?=$temp_prod_id;?>" />
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>                  
          </div>          
        </div>
    </div>
    <?php $this->load->view('admin/includes/modal-html'); ?>
    <?php $this->load->view('admin/includes/app.php'); ?> 
  </body>
</html>
<input type="hidden" id="lead_id" name="lead_id" value="<?php echo $cus_data->id;?>" />
<input type="hidden" name="selected_prod_id" id="selected_prod_id" value="<?=$temp_prod_id;?>" />
</div>
</div>
</div>
<script src="<?php echo assets_url();?>js/common_functions.js"></script>
<script src="<?php echo assets_url();?>js/custom/lead/edit.js?v=<?php echo rand(0,1000); ?>"></script>
<script src="<?php echo assets_url();?>js/custom/quotation/generate_view.js?v=<?php echo rand(0,1000); ?>"></script>
<script>
function open_form() {
    $('#sec1 .display').toggle();
    $('#sec1 .no_display').toggle();
}
function openbox(val, id) {
    if (val == '1') {
        $('#genupdate').show();
        $('#new_opportunity').hide();
    } else if (val == '2') {
        $('#new_opportunity').show();
        $('#genupdate').hide();
    } else if (val == '3') {
        $('#new_opportunity').hide();
        $('#genupdate').hide();
    } else {
        return false;
    }
}
</script>
<script type="text/javascript">
    $(document).on({
    'show.bs.modal': function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    },
    'hidden.bs.modal': function() {
        if ($('.modal:visible').length > 0) {            
            setTimeout(function() {
                $(document.body).addClass('modal-open');
            }, 0);
        }
    }
}, '.modal');
window.paceOptions = {
    document: true,
    eventLag: true,
    restartOnPushState: true,
    restartOnRequestAfter: true,
    ajax: {
        trackMethods: ['POST', 'GET']
    }
};
function toggleAndChangeText() {
    $('#sec1').toggle();
    if ($('#sec1').css('display') == 'none') {
        $('#cust_info').html('(Show Details)');
    } else {
        $('#cust_info').html('(Hide Details)');
    }
}
function update_customer() {
    var first_name = document.getElementById('first_name').value;
    var last_name = document.getElementById('last_name').value;
    var office_phone = document.getElementById('office_phone').value;
    var website = document.getElementById('website').value;
    var address = document.getElementById('address').value;
    var mobile = document.getElementById('mobile').value;
    var state = document.getElementById('state').value;
    var zip = document.getElementById('zip').value;
    var company_name = document.getElementById('company_name').value;
    var country_id = document.getElementById('country_id').value;
    var city = document.getElementById('city').value;
    var customer_id = document.getElementById('customer_id').value;
    var lead_id = document.getElementById('lead_id').value;

    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/edit_ajax",
        type: "POST",
        data: {
            'first_name': first_name,
            'last_name': last_name,
            'office_phone': office_phone,
            'website': website,
            'address': address,
            'mobile': mobile,
            'state': state,
            'zip': zip,
            'company_name': company_name,
            'country_id': country_id,
            'city': city,
            'command': '1',
            'customer_id': customer_id,
            'lead_id': lead_id
        },
        async: true,
        success: function(response) {
            //open_form();
            $('#sec1').html(response);
            $('#cus_updt_res').show();
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function update_lead() {
    var title = document.getElementById('title').value;
    var assigned_to = document.getElementById('assigned_to').value;
    var ssource = document.getElementById('source').value;
    var datepicker2 = document.getElementById('datepicker2').value;
    var customer_id = document.getElementById('customer_id').value;
    var description = document.getElementById('description').value;
    var lead_id = document.getElementById('lead_id').value;

    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/edit_ajax",
        type: "POST",
        data: {
            'title': title,
            'assigned_to': assigned_to,
            'source': ssource,
            'enquiry_date': datepicker2,
            'description': description,
            'command': '1',
            'lead_id': lead_id,
            'customer_id': customer_id
        },
        async: true,
        success: function(response) {
            //open_form();
            $('#sec2').html(response);
            $('#lead_updt_res').show();
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function sel_multiple(id, row_id) 
{

    if ($('#select_' + id).is(":checked")) {
        $('#err_prod').hide();
        var mail_id = $("#selected_prod_id");
        var mail_id_val = mail_id.val();
        if (mail_id_val != '') {
            if (mail_id_val.match(id + ',')) {
                //swal('already added');

            } else {
                mail_id.val(mail_id.val() + id + ',');
            }

        } else {

            mail_id.val(mail_id.val() + id + ',');
        }

    } else {

        var mail_id = $("#selected_prod_id");
        var mail_val = mail_id.val();
        mail_id.val(mail_val.replace(id + ',', ''));
    }
}
function sel_multiple_update(id, row_id, opp_id) {

    if ($('#select_' + id).is(":checked")) {
        $('#err_prod_update').hide();
        var mail_id = $("#selected_prod_id_update_" + opp_id);
        var mail_id_val = mail_id.val();
        if (mail_id_val != '') {
            if (mail_id_val.match(id + ',')) {                
            } else {
                mail_id.val(mail_id.val() + id + ',');
            }

        } else {
            mail_id.val(mail_id.val() + id + ',');
        }

    } else {
        var mail_id = $("#selected_prod_id_update_" + opp_id);
        var mail_val = mail_id.val();
        mail_id.val(mail_val.replace(id + ',', ''));
    }
}
function remove_attr() {
    if ($("input:radio[name='tag_rad']").is(":checked")) {
        $('#next').removeAttr('disabled');
    }
}
function getoption() {
    var val = document.getElementById('email').value;
    var val1 = document.getElementById('mobile').value;
    if (val != '' && val1 != '') {

        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getfirstoption_new",
            type: "POST",
            data: {
                'email': val
            },
            async: true,
            success: function(response) {
               
                $('#second_option').html(response);

            },
            error: function() {
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function getmobile() {
    var val = document.getElementById('email').value;
    var val1 = document.getElementById('mobile').value;
    if (val != '') {
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getmobileno",
            type: "POST",
            data: {
                'email': val,
                'mobile': val1
            },
            async: true,
            success: function(response) {

                $('#customer_id').val(response);
                if ($("input:radio[name='tag_rad']").is(":checked")) {
                    $('#next').removeAttr('disabled');
                }
            },
            error: function() {
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function getsecondoption() {
    var val = document.getElementById('email').value;
    var val1 = document.getElementById('mobile').value;
    $("#second_option").html('<img src="<?=base_url();?>images/fetch.gif" alt="" height="150" width="200" />');
    $("#second_option").hide();
    if (val != '' && val1 != '') {
        $("#second_option").show();
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getsecondoption_edit",
            type: "POST",
            data: {
                'mobile': val1,
                'email': val,
                'customer_id': '<?php echo $cus_data->customer_id?>'
            },
            success: function(response) {
                $("#second_option").html(response);
                getmobile();

            },
            error: function() {
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function getemail(val) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url'];?>/lead/getemail",
        type: "POST",
        data: {
            'mobile': val
        },
        async: true,
        success: function(response) {
            var data = response.split('&');
            var val1 = document.getElementById('email').value;
            if (val1 == '' || val1 == null) {
                $('#email').val(data[0]);
            }
            $('#customer_id').val(data[2]);

            if ($("input:radio[name='tag_rad']").is(":checked")) {
                $('#next').removeAttr('disabled');
            }
        },
        error: function() {
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
</script>
<script type="text/javascript">
function setnewcus() {
    var rad = $('input[name=tag_rad]:checked', '#form').val();
    if (rad == '1') {
        var email = document.getElementById('email').value;
        var mobile = document.getElementById('mobile').value;
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/setnewcustomer",
            type: "POST",
            data: {
                'mobile': mobile,
                'email': email
            },
            success: function(response) {
                window.location.href = "<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/add/";

            },
            error: function() {
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function GetQuoteLeadList(opportunity_id) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/getquotelist_ajax",
        type: "POST",
        data: {
            'opportunity_id': opportunity_id
        },
        async: true,
        success: function(response) {
            $('#quotation_list_all').html(response);
            $('#quotation_list').modal();
        },
        error: function() {
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function close_modal() {
    $('#prod_lead').modal('toggle');
}
function GetStateList(cont) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getstatelist",
        type: "POST",
        data: {
            'country_id': cont
        },
        success: function(response) {
            if (response != '') {
                document.getElementById('state').innerHTML = response;
            }
        },
        error: function() {
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {
        });
        }
    });
}
function GetCityList(state) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getcitylist",
        type: "POST",
        data: {
            'state_id': state
        },
        success: function(response) {
            if (response != '') {
                document.getElementById('city').innerHTML = response;
            }

        },
        error: function() {
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function calculate_price_update(unit_prod, price_prod, total_id, disc_id, grand_total, prod_id, opp_id, currency_id, main_price) {
    var qty = $('#' + unit_prod).val();
    var price = $('#' + price_prod).val();
    var disc = $('#' + disc_id).val();
    var currency_id = $('#' + currency_id).val();
    var main_price = $('#' + main_price).val();
    var f_tot = 0;
    if (parseInt(main_price) < parseInt('1')) {
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/update_prod_price_ajax",
            type: "POST",
            data: {
                'price': price,
                'prod_id': prod_id
            },
            success: function(response) {
                swal('Master Product price updated');
                $('#' + price_prod).val(parseInt(price));
            },
            error: function() {
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    } else if (parseInt(price) < parseInt(main_price)) {
        swal('Your price is lower actual price');
        $('#' + price_prod).val(parseInt(main_price));
        return false;
    }

    var tot = parseInt(qty) * parseInt(price);
    var tot_n = (parseInt(disc) / parseInt('100')) * parseInt(tot);
    f_tot = parseInt(tot) - parseInt(tot_n);
    $('#' + total_id).val(parseInt(f_tot));
    $('#' + grand_total).html(parseInt(f_tot));

    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/update_opportunity_prod_ajax",
        type: "POST",
        data: {
            'quantity': qty,
            'price': price,
            'discount': disc,
            'prod_id': prod_id,
            'opp_id': opp_id,
            'currency_id': currency_id
        },
        success: function(response) {
        },
        error: function() {
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
    var sum = 0;
    $(".amount1").each(function(i) {
        var val = $(this).val();
        if (val != "") {

            sum = sum + parseFloat(val);
        } else {
            sum = sum + 0;
        }
    });

    $('#sub_total_update_' + opp_id).html(parseInt(sum));
    $('#all_currency_update_' + opp_id).val(parseInt(sum));
}

function change_currency_update(id, div, currency_type_name_new, all_row_class) {

    var value = $("#" + id + " option:selected").val();
    var val = $("#" + id + " option:selected").text();
    var val2 = val.match(/\((.*)\)/);

    $("#" + div).html(val2[1]);
    $("." + currency_type_name_new).text(val2[1]);
    $("." + all_row_class).text(val2[1]);
}

var st = "yes";
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    if(st == "no"){
        var nowy = $('.tab_gorup.lead-edit-tab').offset().top - 80;
        var scroll = $(window).scrollTop();
        $('html, body').animate({
                scrollTop: $(".tab_gorup.lead-edit-tab").offset().top-80
            }, 500);
       
    }
    if(st == "yes"){
        st = "no"
    }   
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

</script>
</body>

</html>
<!-- START -->
<!-- MODAL HTML -->
<!-- MODAL HTML -->
<!-- END -->
<!--  ====== PO UPLOAD: START =========== -->

<!-- ======== PO UPLOAD: END ============= -->
<!-- CREATE QUOTATION VIEW -->
<!-- generateQuotationModal -->
<div class="modal fade modal-big" id="generateQuotationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Quotation</h4>
      </div>
      <div class="modal-body pl-30 pr-30">
        <div class="quotation-search">
            <div class="form-group">
                <label>Select Product(s)</label>
                <div class="from-full row">
                    <div class="col-md-10 pr-0">
                        <input type="text" class="form-control search_product_by_keyword" placeholder="Enter Product Name">
                    </div>
                    <div class="col-md-2 pl-0">
                        <button id="search_product_by_keyword" class="btn btn-default btn-primary mt-0">Search</button>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-auto">
                    <div class="search-item">
                        <a href="#" class="search-remove"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                        <span>OnePlus 7 Mobile Phone</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="search-item">
                        <a href="#" class="search-remove"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                        <span>Mobile 12x</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <table class="table custom-table-white table-color">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Unit Type</th>
                            <th>Sale Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label class="check-box-sec">
                                    <input type="checkbox" name="" value="">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td>OnePlus 7 Mobile Phone</td>
                            <td>1</td>
                            <td>Pieces</td>
                            <td>INR 10000.00</td>
                        </tr>
                        <tr>
                            <td>
                                <label class="check-box-sec">
                                    <input type="checkbox" name="" value="">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td>OnePlus 7 Mobile Phone</td>
                            <td>1</td>
                            <td>Pieces</td>
                            <td>INR 10000.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <ul class="col-same-bt">
                    <li><a href="#" class="btn btn-primary btn-round-shadow semi-big-bt">Close</a></li>
                    <li><a href="#" class="btn btn-primary btn-round-shadow semi-big-bt">Add</a></li>
                    <li><a href="#" class="btn btn-primary btn-round-shadow semi-big-bt custom-model-open" href="#" data-show="#customFinalQuotationModal" data-hide="#generateQuotationModal">Save & Proceed</a></li>
                </ul>
            </div>
        </div>
      </div>      
    </div>
  </div>
</div>

<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
  <div class="modal fade mail-modal modal-fullscreen" id="PoUploadLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>
<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->

<!-- quotationModal -->
<div class="modal fade" id="customQuotationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Quotation</h4>
      </div>
      <div class="modal-body pl-30 pr-30">
        <div class="quotation-send">
            
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label">To</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control-plaintext" placeholder="" value="swarup@gmail.com">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label">Subject</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control-plaintext" placeholder="" value="Requirement for Cotton Canvas Fabric">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label"></label>
                <div class="col-sm-10 lh-20">
                  <label class="check-box-sec f-l mr-6">
                      <input type="checkbox" name="mail_to_client" id="mail_to_client" value="Y">
                      <span class="checkmark"></span>
                  </label>
                  Attached company brochure to buyer by mail if avilable
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label"></label>
                <div class="col-sm-10">
                  <label for="quotation_upload" class="btn btn-primary btn-round-shadow quotation-bt">
                      <input type="file" name="quotation_upload" class="d-none" id="quotation_upload">
                      Attach File
                  </label>
                  <div class="uploaded_info">
                      <span>demo_file_name.pdf</span>
                      <a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <button type="submit" class="btn btn-primary btn-round-shadow big-bt pull-right">Send Quotation</button>
            </div>
        </div>
      </div>      
    </div>
  </div>
</div>
<script type="text/javascript"> 
$( document ).ready(function() {
    $(document).on("click",".custom-model-open",function(event) {
        event.preventDefault();
        var showModel = $(this).attr('data-show');
        var hideModel = $(this).attr('data-hide');
        $(hideModel).modal('hide');
        ShowModel(showModel);
        //alert("click");
    });
    function ShowModel(getele){
        setTimeout(function(){ 
            $(getele).modal('show');
        }, 500);
    }
});
// STYLE
  var edit = function(cmd) {
    var val = false;
    switch (cmd) {
     case 'formatBlock': val = 'blockquote'; break;
     case 'createLink': val = prompt('Enter the URL to hyperlink to from the selected text:'); break;
     case 'insertImage': val = prompt('Enter the image URL:'); break;
    }
    document.execCommand(cmd, false, val);
    box.focus();
  }
  function simpleEditer()
  {
    // $(".tools").show();
    var box = $('.buying-requirements');
    box.attr('contentEditable', true);
    // EDITING LISTENERS
    $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
       edit($(this).data('cmd'));
    });    
  }
  function inactiveSimpleEditer()
  {
    $(".tools").hide();
    var box = $('.buying-requirements');
    box.attr('contentEditable', false);
  }
</script>
<input type="hidden" name="" id="input_cc_lead_opportunity_currency_type" value="" style="display:none" />
<div id="currency_select_html_for_custom_quation_pdf_upload" style="display:none;"><select id="cc_lead_opportunity_currency_type" name="cc_lead_opportunity_currency_type" class="form-control"><?php if(count($currency_list)){foreach($currency_list AS $currency){echo'<option value="'.$currency->id.'">'.$currency->code.'</option>';}} ?></select></div>
