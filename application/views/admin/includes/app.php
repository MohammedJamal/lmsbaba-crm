<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script>
<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<!-- tooltip -->
<link rel="stylesheet" type="text/css" href="<?=assets_url();?>css/tooltipster.css" />
<script type="text/javascript" src="<?=assets_url();?>js/jquery.tooltipster.js"></script>
<!-- tooltip -->
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="<?php echo assets_url();?>js/tether.min.js"></script>
<script src="<?php echo assets_url()?>vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo assets_url()?>js/main.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/app.js?v=<?php echo rand(0,1000); ?>"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/common_functions.js"></script>
<script src="<?php echo assets_url();?>js/jquery.blockUI.js"></script>
<!-- owl -->
<link rel="stylesheet" href="<?=assets_url();?>css/owl.carousel.min.css">
<link rel="stylesheet" href="<?=assets_url();?>css/owl.theme.default.min.css">
<script src="<?=assets_url();?>js/owl.carousel.js"></script>
<!-- owl -->
<script type="text/javascript">
  tinymce.init({
    selector: 'textarea.basic-editor',
    height: 100,
    force_br_newlines : true,
    force_p_newlines : false,
    forced_root_block : '',
    menubar: false,
    statusbar: false,
    toolbar: false,    
    setup: function(editor) {
        editor.on('focusout', function(e) {
          //console.log(editor);
          var updated_field_name=editor.id;
          var updated_content=editor.getContent();
        })
    }                
  });
  
function assess_denied(msg='')
{
    msg=(msg)?msg:'Oops! Access restricted..';
    alert(msg);
} 
</script>
<script type="text/javascript">
  //text-show
  showTextLoader = function(getselecter, gettext){
     var getelement = $(getselecter);
     var getTxt = gettext;
     var loaderHtml = '<div id="text-loader"><div class="text-loader-center"><div class="text-logo-loader"></div><div class="text-show">'+getTxt+'</div></div></div>';
     $(getselecter).append(loaderHtml);
  }
  hideTextLoader = function(){
     $('text-loader').remove();
  }
  //////

      
  function myFunction(){
    if($('#stDrpdwn').hasClass('d-none')){
      $('#stDrpdwn').removeClass('d-none');
      $('#stDrpdwn').addClass('d-block');
    }else if($('#stDrpdwn').hasClass('d-block')){
      $('#stDrpdwn').addClass('d-none');
      $('#stDrpdwn').removeClass('d-block');
    }else{
      $('#stDrpdwn').addClass('d-block');
    }
  }
  $(document).ready(function(){
    $(document).on("click","a.mobile-close",function(event) {
      event.preventDefault();
      $('.main-panel > .header .navbar-search').fadeOut();
    });
    //.mobile-search-bt
    $(document).on("click",".mobile-search-bt",function(event) {
      event.preventDefault();
      $('.main-panel > .header .navbar-search').fadeIn();
    });
      // console.log('ready')
      $("body").on("click",".my_document_popup",function(e){    

          var base_url=$("#base_url").val();
          var data='';
          $.ajax({
            url: base_url+"setting/rander_my_document_list_popup_ajax",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
            beforeSend: function( xhr ) {},
            success: function(data){
              result = $.parseJSON(data);               
              $("#my_document_list_popup").html(result.html)
              $('#my_document_modal').modal({backdrop: 'static',keyboard: false});
            }
        });    
                  
      });

      $("body").on("click",".change_password",function(e){         
          $('#change_password_modal').modal({backdrop: 'static',keyboard: false});        
      }); 

      $("body").on("click","#change_password_submit_confirm",function(e){
          var base_url=$("#base_url").val();
          var emp_password_obj=$("#emp_password");
          var emp_confirm_password_obj=$("#emp_confirm_password");

          $.ajax({
            url: base_url+"app/change_password_ajax",
            data: new FormData($('#changePasswordForm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {},
            success: function(data){
              result = $.parseJSON(data);
               
              if(result.status=='success')
              {
                $("#emp_password").val('');
                $("#emp_confirm_password").val('');
                $("#error_msg_div").hide();
                $("#success_msg_div").show();
                $("#success_msg").html(result.msg);
                  
              }   
              else if(result.status=='error')
              {
                $("#error_msg_div").show();
                $("#success_msg_div").hide();
                $("#error_msg").html(result.msg);
              }
            }
        });



      });


      $("body").on("click",".change_username_view_popup",function(e){   
          var existing_username=$(this).attr("data-username");  
          var emp_id=$(this).attr("data-id");
          
          $("#change_username_submit_confirm").attr("data-id",emp_id);
          $('#change_username_modal').modal({backdrop: 'static',keyboard: false});        
      }); 
      $("body").on("click","#change_username_submit_confirm",function(e){
          var thisObj=$(this);
          var base_url=$("#base_url").val();
          var emp_id=$(this).attr("data-id");          
          var emp_username=$("#emp_username").val();
          $('form#changeUsernameForm').append('<input type="hidden" name="emp_id" id="emp_id" value="'+emp_id+'" />');

          $.ajax({
            url: base_url+"app/change_username_ajax",
            data: new FormData($('#changeUsernameForm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {},
            success: function(data){
              result = $.parseJSON(data);
               
              if(result.status=='success')
              {
                $(".change_username_view_popup").attr("data-username",emp_username);
                
                $("#existing_username").val(emp_username);
                $("#emp_username").val('');
                $("#emp_username_error_msg_div").hide();
                $("#emp_username_success_msg_div").show();
                $("#emp_username_error_msg").html(''); 
                $("#emp_username_success_msg").html(result.msg);  

              }   
              else if(result.status=='error')
              {
                $("#emp_username_error_msg_div").show();
                $("#emp_username_success_msg_div").hide();
                $("#emp_username_error_msg").html(result.msg);
                $("#emp_username_success_msg").html('');
              }
            }
        });



      });



      $("body").on("change",".search_from",function(e){
        var post_url=$(this).find(':selected').attr("data-id");
        $("#top_search_frm").attr("action",post_url);
        if($(this).find(':selected').val()=='L')
        {
           $('#search_keyword').attr("placeholder","Search for Leads/Lead ID");
        }
        else if($(this).find(':selected').val()=='C')
        {
           $('#search_keyword').attr("placeholder","Search for Company name");
        }
        else if($(this).find(':selected').val()=='E')
        {
           $('#search_keyword').attr("placeholder","Search for Employee");
        }
        else if($(this).find(':selected').val()=='P')
        {
           $('#search_keyword').attr("placeholder","Search for Product name/code");
        }
      });

     

      $("body").on("click","#search_by_keyword_confirm",function(e){
        e.preventDefault();
        var search_type_obj=$("#search_type");
        var search_keyword_obj=$("#search_keyword");
        // alert(search_type_obj.val())
        if(search_type_obj.val()==null)
        {
          search_keyword_obj.focus();
          // search_keyword_obj.attr("placeholder","Please enter keyword...");
          return false;
        } 
        if(search_keyword_obj.val()=='')
        {
          search_keyword_obj.focus();
          search_keyword_obj.attr("placeholder","Please enter keyword...");
          return false;
        }      
        $("#top_search_frm").submit();
      });

    if($('.search_from').find(':selected').attr('data-id'))
    {
      var tmpUrl=$('.search_from').find(':selected').attr('data-id');
      $("#top_search_frm").attr("action",tmpUrl);
    }


// =============================================
// TOP SEARCH DROPDOWN
// =============================================
(function ($){
      'use strict';
      //<editor-fold desc="Shims">
      if (!String.prototype.includes) {
      (function () {
        'use strict'; // needed to support `apply`/`call` with `undefined`/`null`
        var toString = {}.toString;
        var defineProperty = (function () {
          // IE 8 only supports `Object.defineProperty` on DOM elements
          try {
            var object = {};
            var $defineProperty = Object.defineProperty;
            var result = $defineProperty(object, object, object) && $defineProperty;
          } catch (error) {
          }
          return result;
        }());
                  var indexOf = ''.indexOf;
                  var includes = function (search) {
                    if (this == null) {
                      throw new TypeError();
                    }
                    var string = String(this);
                    if (search && toString.call(search) == '[object RegExp]') {
                      throw new TypeError();
                    }
                    var stringLength = string.length;
                    var searchString = String(search);
                    var searchLength = searchString.length;
                    var position = arguments.length > 1 ? arguments[1] : undefined;
                    // `ToInteger`
                    var pos = position ? Number(position) : 0;
                    if (pos != pos) { // better `isNaN`
                      pos = 0;
                    }
                    var start = Math.min(Math.max(pos, 0), stringLength);
                    // Avoid the `indexOf` call if no match is possible
                    if (searchLength + start > stringLength) {
                      return false;
                    }
                    return indexOf.call(string, searchString, pos) != -1;
                  };
                  if (defineProperty) {
                    defineProperty(String.prototype, 'includes', {
                      'value': includes,
                      'configurable': true,
                      'writable': true
                    });
                  } else {
                    String.prototype.includes = includes;
                  }
                }());
              }

              if (!String.prototype.startsWith) {
                (function () {
                  'use strict'; // needed to support `apply`/`call` with `undefined`/`null`
                  var defineProperty = (function () {
                    // IE 8 only supports `Object.defineProperty` on DOM elements
                    try {
                      var object = {};
                      var $defineProperty = Object.defineProperty;
                      var result = $defineProperty(object, object, object) && $defineProperty;
                    } catch (error) {
                    }
                    return result;
                  }());
                  var toString = {}.toString;
                  var startsWith = function (search) {
                    if (this == null) {
                      throw new TypeError();
                    }
                    var string = String(this);
                    if (search && toString.call(search) == '[object RegExp]') {
                      throw new TypeError();
                    }
                    var stringLength = string.length;
                    var searchString = String(search);
                    var searchLength = searchString.length;
                    var position = arguments.length > 1 ? arguments[1] : undefined;
                    // `ToInteger`
                    var pos = position ? Number(position) : 0;
                    if (pos != pos) { // better `isNaN`
                      pos = 0;
                    }
                    var start = Math.min(Math.max(pos, 0), stringLength);
                    // Avoid the `indexOf` call if no match is possible
                    if (searchLength + start > stringLength) {
                      return false;
                    }
                    var index = -1;
                    while (++index < searchLength) {
                      if (string.charCodeAt(start + index) != searchString.charCodeAt(index)) {
                        return false;
                      }
                    }
                    return true;
                  };
                  if (defineProperty) {
                    defineProperty(String.prototype, 'startsWith', {
                      'value': startsWith,
                      'configurable': true,
                      'writable': true
                    });
                  } else {
                    String.prototype.startsWith = startsWith;
                  }
                }());
              }

              if (!Object.keys) {
                Object.keys = function (
                  o, // object
                  k, // key
                  r  // result array
                  ){
                  // initialize object and result
                  r=[];
                  // iterate over object keys
                  for (k in o)
                      // fill result array with non-prototypical keys
                    r.hasOwnProperty.call(o, k) && r.push(k);
                  // return result
                  return r;
                };
              }

              // set data-selected on select element if the value has been programmatically selected
              // prior to initialization of bootstrap-select
              // * consider removing or replacing an alternative method *
              var valHooks = {
                useDefault: false,
                _set: $.valHooks.select.set
              };

              $.valHooks.select.set = function(elem, value) {
                if (value && !valHooks.useDefault) $(elem).data('selected', true);

                return valHooks._set.apply(this, arguments);
              };

              var changed_arguments = null;
              $.fn.triggerNative = function (eventName) {
                var el = this[0],
                    event;

                if (el.dispatchEvent) { // for modern browsers & IE9+
                  if (typeof Event === 'function') {
                    // For modern browsers
                    event = new Event(eventName, {
                      bubbles: true
                    });
                  } else {
                    // For IE since it doesn't support Event constructor
                    event = document.createEvent('Event');
                    event.initEvent(eventName, true, false);
                  }

                  el.dispatchEvent(event);
                } else if (el.fireEvent) { // for IE8
                  event = document.createEventObject();
                  event.eventType = eventName;
                  el.fireEvent('on' + eventName, event);
                } else {
                  // fall back to jQuery.trigger
                  this.trigger(eventName);
                }
              };
              //</editor-fold>

              // Case insensitive contains search
              $.expr.pseudos.icontains = function (obj, index, meta) {
                var $obj = $(obj).find('a');
                var haystack = ($obj.data('tokens') || $obj.text()).toString().toUpperCase();
                return haystack.includes(meta[3].toUpperCase());
              };

              // Case insensitive begins search
              $.expr.pseudos.ibegins = function (obj, index, meta) {
                var $obj = $(obj).find('a');
                var haystack = ($obj.data('tokens') || $obj.text()).toString().toUpperCase();
                return haystack.startsWith(meta[3].toUpperCase());
              };

              // Case and accent insensitive contains search
              $.expr.pseudos.aicontains = function (obj, index, meta) {
                var $obj = $(obj).find('a');
                var haystack = ($obj.data('tokens') || $obj.data('normalizedText') || $obj.text()).toString().toUpperCase();
                return haystack.includes(meta[3].toUpperCase());
              };

              // Case and accent insensitive begins search
              $.expr.pseudos.aibegins = function (obj, index, meta) {
                var $obj = $(obj).find('a');
                var haystack = ($obj.data('tokens') || $obj.data('normalizedText') || $obj.text()).toString().toUpperCase();
                return haystack.startsWith(meta[3].toUpperCase());
              };

              /**
               * Remove all diatrics from the given text.
               * @access private
               * @param {String} text
               * @returns {String}
               */
              function normalizeToBase(text) {
                var rExps = [
                  {re: /[\xC0-\xC6]/g, ch: "A"},
                  {re: /[\xE0-\xE6]/g, ch: "a"},
                  {re: /[\xC8-\xCB]/g, ch: "E"},
                  {re: /[\xE8-\xEB]/g, ch: "e"},
                  {re: /[\xCC-\xCF]/g, ch: "I"},
                  {re: /[\xEC-\xEF]/g, ch: "i"},
                  {re: /[\xD2-\xD6]/g, ch: "O"},
                  {re: /[\xF2-\xF6]/g, ch: "o"},
                  {re: /[\xD9-\xDC]/g, ch: "U"},
                  {re: /[\xF9-\xFC]/g, ch: "u"},
                  {re: /[\xC7-\xE7]/g, ch: "c"},
                  {re: /[\xD1]/g, ch: "N"},
                  {re: /[\xF1]/g, ch: "n"}
                ];
                $.each(rExps, function () {
                  text = text ? text.replace(this.re, this.ch) : '';
                });
                return text;
              }


              // List of HTML entities for escaping.
              var escapeMap = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#x27;',
                '`': '&#x60;'
              };
              
              var unescapeMap = {
                '&amp;': '&',
                '&lt;': '<',
                '&gt;': '>',
                '&quot;': '"',
                '&#x27;': "'",
                '&#x60;': '`'
              };

              // Functions for escaping and unescaping strings to/from HTML interpolation.
              var createEscaper = function(map) {
                var escaper = function(match) {
                  return map[match];
                };
                // Regexes for identifying a key that needs to be escaped.
                var source = '(?:' + Object.keys(map).join('|') + ')';
                var testRegexp = RegExp(source);
                var replaceRegexp = RegExp(source, 'g');
                return function(string) {
                  string = string == null ? '' : '' + string;
                  return testRegexp.test(string) ? string.replace(replaceRegexp, escaper) : string;
                };
              };

              var htmlEscape = createEscaper(escapeMap);
              var htmlUnescape = createEscaper(unescapeMap);

              var Selectpicker = function (element, options) {
                // bootstrap-select has been initialized - revert valHooks.select.set back to its original function
                if (!valHooks.useDefault) {
                  $.valHooks.select.set = valHooks._set;
                  valHooks.useDefault = true;
                }

                this.$element = $(element);
                this.$newElement = null;
                this.$button = null;
                this.$menu = null;
                this.$lis = null;
                this.options = options;

                // If we have no title yet, try to pull it from the html title attribute (jQuery doesnt' pick it up as it's not a
                // data-attribute)
                if (this.options.title === null) {
                  this.options.title = this.$element.attr('title');
                }

                // Format window padding
                var winPad = this.options.windowPadding;
                if (typeof winPad === 'number') {
                  this.options.windowPadding = [winPad, winPad, winPad, winPad];
                }

                //Expose public methods
                this.val = Selectpicker.prototype.val;
                this.render = Selectpicker.prototype.render;
                this.refresh = Selectpicker.prototype.refresh;
                this.setStyle = Selectpicker.prototype.setStyle;
                this.selectAll = Selectpicker.prototype.selectAll;
                this.deselectAll = Selectpicker.prototype.deselectAll;
                this.destroy = Selectpicker.prototype.destroy;
                this.remove = Selectpicker.prototype.remove;
                this.show = Selectpicker.prototype.show;
                this.hide = Selectpicker.prototype.hide;

                this.init();
              };

              Selectpicker.VERSION = '1.12.2';

              // part of this is duplicated in i18n/defaults-en_US.js. Make sure to update both.
              Selectpicker.DEFAULTS = {
                noneSelectedText: 'Nothing selected',
                noneResultsText: 'No results matched {0}',
                countSelectedText: function (numSelected, numTotal) {
                  return (numSelected == 1) ? "{0} item selected" : "{0} items selected";
                },
                maxOptionsText: function (numAll, numGroup) {
                  return [
                    (numAll == 1) ? 'Limit reached ({n} item max)' : 'Limit reached ({n} items max)',
                    (numGroup == 1) ? 'Group limit reached ({n} item max)' : 'Group limit reached ({n} items max)'
                  ];
                },
                selectAllText: 'Select All',
                deselectAllText: 'Deselect All',
                doneButton: false,
                doneButtonText: 'Close',
                multipleSeparator: ', ',
                styleBase: 'btn',
                style: 'btn-default',
                size: 'auto',
                title: null,
                selectedTextFormat: 'values',
                width: false,
                container: false,
                hideDisabled: false,
                showSubtext: false,
                showIcon: true,
                showContent: true,
                dropupAuto: true,
                header: false,
                liveSearch: false,
                liveSearchPlaceholder: null,
                liveSearchNormalize: false,
                liveSearchStyle: 'contains',
                actionsBox: false,
                iconBase: 'glyphicon',
                tickIcon: 'glyphicon-ok',
                showTick: false,
                template: {
                  caret: '<span class="caret"></span>'
                },
                maxOptions: false,
                mobile: false,
                selectOnTab: false,
                dropdownAlignRight: false,
                windowPadding: 0
              };

              Selectpicker.prototype = {

                constructor: Selectpicker,

                init: function () {
                  var that = this,
                      id = this.$element.attr('id');

                  this.$element.addClass('bs-select-hidden');

                  // store originalIndex (key) and newIndex (value) in this.liObj for fast accessibility
                  // allows us to do this.$lis.eq(that.liObj[index]) instead of this.$lis.filter('[data-original-index="' + index + '"]')
                  this.liObj = {};
                  this.multiple = this.$element.prop('multiple');
                  this.autofocus = this.$element.prop('autofocus');
                  this.$newElement = this.createView();
                  this.$element
                    .after(this.$newElement)
                    .appendTo(this.$newElement);
                  this.$button = this.$newElement.children('button');
                  this.$menu = this.$newElement.children('.dropdown-menu');
                  this.$menuInner = this.$menu.children('.inner');
                  this.$searchbox = this.$menu.find('input');

                  this.$element.removeClass('bs-select-hidden');

                  if (this.options.dropdownAlignRight === true) this.$menu.addClass('dropdown-menu-right');

                  if (typeof id !== 'undefined') {
                    this.$button.attr('data-id', id);
                    $('label[for="' + id + '"]').click(function (e) {
                      e.preventDefault();
                      that.$button.focus();
                    });
                  }

                  this.checkDisabled();
                  this.clickListener();
                  if (this.options.liveSearch) this.liveSearchListener();
                  this.render();
                  this.setStyle();
                  this.setWidth();
                  if (this.options.container) this.selectPosition();
                  this.$menu.data('this', this);
                  this.$newElement.data('this', this);
                  if (this.options.mobile) this.mobile();

                  this.$newElement.on({
                    'hide.bs.dropdown': function (e) {
                      that.$menuInner.attr('aria-expanded', false);
                      that.$element.trigger('hide.bs.select', e);
                    },
                    'hidden.bs.dropdown': function (e) {
                      that.$element.trigger('hidden.bs.select', e);
                    },
                    'show.bs.dropdown': function (e) {
                      that.$menuInner.attr('aria-expanded', true);
                      that.$element.trigger('show.bs.select', e);
                    },
                    'shown.bs.dropdown': function (e) {
                      that.$element.trigger('shown.bs.select', e);
                    }
                  });

                  if (that.$element[0].hasAttribute('required')) {
                    this.$element.on('invalid', function () {
                      that.$button
                        .addClass('bs-invalid')
                        .focus();

                      that.$element.on({
                        'focus.bs.select': function () {
                          that.$button.focus();
                          that.$element.off('focus.bs.select');
                        },
                        'shown.bs.select': function () {
                          that.$element
                            .val(that.$element.val()) // set the value to hide the validation message in Chrome when menu is opened
                            .off('shown.bs.select');
                        },
                        'rendered.bs.select': function () {
                          // if select is no longer invalid, remove the bs-invalid class
                          if (this.validity.valid) that.$button.removeClass('bs-invalid');
                          that.$element.off('rendered.bs.select');
                        }
                      });
                    });
                  }

                  setTimeout(function () {
                    that.$element.trigger('loaded.bs.select');
                  });
                },

                createDropdown: function () {
                  // Options
                  // If we are multiple or showTick option is set, then add the show-tick class
                  var showTick = (this.multiple || this.options.showTick) ? ' show-tick' : '',
                      inputGroup = this.$element.parent().hasClass('input-group') ? ' input-group-btn' : '',
                      autofocus = this.autofocus ? ' autofocus' : '';
                  // Elements
                  var header = this.options.header ? '<div class="popover-title"><button type="button" class="close" aria-hidden="true">&times;</button>' + this.options.header + '</div>' : '';
                  var searchbox = this.options.liveSearch ?
                  '<div class="bs-searchbox">' +
                  '<input type="text" class="form-control" autocomplete="off"' +
                  (null === this.options.liveSearchPlaceholder ? '' : ' placeholder="' + htmlEscape(this.options.liveSearchPlaceholder) + '"') + ' role="textbox" aria-label="Search">' +
                  '</div>'
                      : '';
                  var actionsbox = this.multiple && this.options.actionsBox ?
                  '<div class="bs-actionsbox">' +
                  '<div class="btn-group btn-group-sm btn-block">' +
                  '<button type="button" class="actions-btn bs-select-all btn btn-default">' +
                  this.options.selectAllText +
                  '</button>' +
                  '<button type="button" class="actions-btn bs-deselect-all btn btn-default">' +
                  this.options.deselectAllText +
                  '</button>' +
                  '</div>' +
                  '</div>'
                      : '';
                  var donebutton = this.multiple && this.options.doneButton ?
                  '<div class="bs-donebutton">' +
                  '<div class="btn-group btn-block">' +
                  '<button type="button" class="btn btn-sm btn-default">' +
                  this.options.doneButtonText +
                  '</button>' +
                  '</div>' +
                  '</div>'
                      : '';
                  var drop =
                      '<div class="btn-group bootstrap-select' + showTick + inputGroup + '">' +
                      '<button type="button" class="' + this.options.styleBase + ' dropdown-toggle" data-toggle="dropdown"' + autofocus + ' role="button">' +
                      '<span class="filter-option pull-left"></span>&nbsp;' +
                      '<span class="bs-caret">' +
                      this.options.template.caret +
                      '</span>' +
                      '</button>' +
                      '<div class="dropdown-menu open" role="combobox">' +
                      header +
                      searchbox +
                      actionsbox +
                      '<ul class="dropdown-menu inner" role="listbox" aria-expanded="false">' +
                      '</ul>' +
                      donebutton +
                      '</div>' +
                      '</div>';

                  return $(drop);
                },

                createView: function () {
                  var $drop = this.createDropdown(),
                      li = this.createLi();

                  $drop.find('ul')[0].innerHTML = li;
                  return $drop;
                },

                reloadLi: function () {
                  // rebuild
                  var li = this.createLi();
                  this.$menuInner[0].innerHTML = li;
                },

                createLi: function () {
                  var that = this,
                      _li = [],
                      optID = 0,
                      titleOption = document.createElement('option'),
                      liIndex = -1; // increment liIndex whenever a new <li> element is created to ensure liObj is correct

                  // Helper functions
                  /**
                   * @param content
                   * @param [index]
                   * @param [classes]
                   * @param [optgroup]
                   * @returns {string}
                   */
                  var generateLI = function (content, index, classes, optgroup) {
                    return '<li' +
                        ((typeof classes !== 'undefined' & '' !== classes) ? ' class="' + classes + '"' : '') +
                        ((typeof index !== 'undefined' & null !== index) ? ' data-original-index="' + index + '"' : '') +
                        ((typeof optgroup !== 'undefined' & null !== optgroup) ? 'data-optgroup="' + optgroup + '"' : '') +
                        '>' + content + '</li>';
                  };

                  /**
                   * @param text
                   * @param [classes]
                   * @param [inline]
                   * @param [tokens]
                   * @returns {string}
                   */
                  var generateA = function (text, classes, inline, tokens) {
                    return '<a tabindex="0"' +
                        (typeof classes !== 'undefined' ? ' class="' + classes + '"' : '') +
                        (inline ? ' style="' + inline + '"' : '') +
                        (that.options.liveSearchNormalize ? ' data-normalized-text="' + normalizeToBase(htmlEscape($(text).html())) + '"' : '') +
                        (typeof tokens !== 'undefined' || tokens !== null ? ' data-tokens="' + tokens + '"' : '') +
                        ' role="option">' + text +
                        '<span class="' + that.options.iconBase + ' ' + that.options.tickIcon + ' check-mark"></span>' +
                        '</a>';
                  };

                  if (this.options.title && !this.multiple) {
                    // this option doesn't create a new <li> element, but does add a new option, so liIndex is decreased
                    // since liObj is recalculated on every refresh, liIndex needs to be decreased even if the titleOption is already appended
                    liIndex--;

                    if (!this.$element.find('.bs-title-option').length) {
                      // Use native JS to prepend option (faster)
                      var element = this.$element[0];
                      titleOption.className = 'bs-title-option';
                      titleOption.innerHTML = this.options.title;
                      titleOption.value = '';
                      element.insertBefore(titleOption, element.firstChild);
                      // Check if selected or data-selected attribute is already set on an option. If not, select the titleOption option.
                      // the selected item may have been changed by user or programmatically before the bootstrap select plugin runs,
                      // if so, the select will have the data-selected attribute
                      var $opt = $(element.options[element.selectedIndex]);
                      if ($opt.attr('selected') === undefined && this.$element.data('selected') === undefined) {
                        titleOption.selected = true;
                      }
                    }
                  }

                  var $selectOptions = this.$element.find('option');

                  $selectOptions.each(function (index) {
                    var $this = $(this);

                    liIndex++;

                    if ($this.hasClass('bs-title-option')) return;

                    // Get the class and text for the option
                    var optionClass = this.className || '',
                        inline = htmlEscape(this.style.cssText),
                        text = $this.data('content') ? $this.data('content') : $this.html(),
                        tokens = $this.data('tokens') ? $this.data('tokens') : null,
                        subtext = typeof $this.data('subtext') !== 'undefined' ? '<small class="text-muted">' + $this.data('subtext') + '</small>' : '',
                        icon = typeof $this.data('icon') !== 'undefined' ? '<span class="' + that.options.iconBase + ' ' + $this.data('icon') + '"></span> ' : '',
                        $parent = $this.parent(),
                        isOptgroup = $parent[0].tagName === 'OPTGROUP',
                        isOptgroupDisabled = isOptgroup && $parent[0].disabled,
                        isDisabled = this.disabled || isOptgroupDisabled,
                        prevHiddenIndex;

                    if (icon !== '' && isDisabled) {
                      icon = '<span>' + icon + '</span>';
                    }

                    if (that.options.hideDisabled && (isDisabled && !isOptgroup || isOptgroupDisabled)) {
                      // set prevHiddenIndex - the index of the first hidden option in a group of hidden options
                      // used to determine whether or not a divider should be placed after an optgroup if there are
                      // hidden options between the optgroup and the first visible option
                      prevHiddenIndex = $this.data('prevHiddenIndex');
                      $this.next().data('prevHiddenIndex', (prevHiddenIndex !== undefined ? prevHiddenIndex : index));

                      liIndex--;
                      return;
                    }

                    if (!$this.data('content')) {
                      // Prepend any icon and append any subtext to the main text.
                      text = icon + '<span class="text">' + text + subtext + '</span>';
                    }

                    if (isOptgroup && $this.data('divider') !== true) {
                      if (that.options.hideDisabled && isDisabled) {
                        if ($parent.data('allOptionsDisabled') === undefined) {
                          var $options = $parent.children();
                          $parent.data('allOptionsDisabled', $options.filter(':disabled').length === $options.length);
                        }

                        if ($parent.data('allOptionsDisabled')) {
                          liIndex--;
                          return;
                        }
                      }

                      var optGroupClass = ' ' + $parent[0].className || '';

                      if ($this.index() === 0) { // Is it the first option of the optgroup?
                        optID += 1;

                        // Get the opt group label
                        var label = $parent[0].label,
                            labelSubtext = typeof $parent.data('subtext') !== 'undefined' ? '<small class="text-muted">' + $parent.data('subtext') + '</small>' : '',
                            labelIcon = $parent.data('icon') ? '<span class="' + that.options.iconBase + ' ' + $parent.data('icon') + '"></span> ' : '';

                        label = labelIcon + '<span class="text">' + htmlEscape(label) + labelSubtext + '</span>';

                        if (index !== 0 && _li.length > 0) { // Is it NOT the first option of the select && are there elements in the dropdown?
                          liIndex++;
                          _li.push(generateLI('', null, 'divider', optID + 'div'));
                        }
                        liIndex++;
                        _li.push(generateLI(label, null, 'dropdown-header' + optGroupClass, optID));
                      }

                      if (that.options.hideDisabled && isDisabled) {
                        liIndex--;
                        return;
                      }

                      _li.push(generateLI(generateA(text, 'opt ' + optionClass + optGroupClass, inline, tokens), index, '', optID));
                    } else if ($this.data('divider') === true) {
                      _li.push(generateLI('', index, 'divider'));
                    } else if ($this.data('hidden') === true) {
                      // set prevHiddenIndex - the index of the first hidden option in a group of hidden options
                      // used to determine whether or not a divider should be placed after an optgroup if there are
                      // hidden options between the optgroup and the first visible option
                      prevHiddenIndex = $this.data('prevHiddenIndex');
                      $this.next().data('prevHiddenIndex', (prevHiddenIndex !== undefined ? prevHiddenIndex : index));

                      _li.push(generateLI(generateA(text, optionClass, inline, tokens), index, 'hidden is-hidden'));
                    } else {
                      var showDivider = this.previousElementSibling && this.previousElementSibling.tagName === 'OPTGROUP';

                      // if previous element is not an optgroup and hideDisabled is true
                      if (!showDivider && that.options.hideDisabled) {
                        prevHiddenIndex = $this.data('prevHiddenIndex');

                        if (prevHiddenIndex !== undefined) {
                          // select the element **before** the first hidden element in the group
                          var prevHidden = $selectOptions.eq(prevHiddenIndex)[0].previousElementSibling;
                          
                          if (prevHidden && prevHidden.tagName === 'OPTGROUP' && !prevHidden.disabled) {
                            showDivider = true;
                          }
                        }
                      }

                      if (showDivider) {
                        liIndex++;
                        _li.push(generateLI('', null, 'divider', optID + 'div'));
                      }
                      _li.push(generateLI(generateA(text, optionClass, inline, tokens), index));
                    }

                    that.liObj[index] = liIndex;
                  });

                  //If we are not multiple, we don't have a selected item, and we don't have a title, select the first element so something is set in the button
                  if (!this.multiple && this.$element.find('option:selected').length === 0 && !this.options.title) {
                    this.$element.find('option').eq(0).prop('selected', true).attr('selected', 'selected');
                  }

                  return _li.join('');
                },

                findLis: function () {
                  if (this.$lis == null) this.$lis = this.$menu.find('li');
                  return this.$lis;
                },

                /**
                 * @param [updateLi] defaults to true
                 */
                render: function (updateLi) {
                  var that = this,
                      notDisabled,
                      $selectOptions = this.$element.find('option');

                  //Update the LI to match the SELECT
                  if (updateLi !== false) {
                    $selectOptions.each(function (index) {
                      var $lis = that.findLis().eq(that.liObj[index]);

                      that.setDisabled(index, this.disabled || this.parentNode.tagName === 'OPTGROUP' && this.parentNode.disabled, $lis);
                      that.setSelected(index, this.selected, $lis);
                    });
                  }

                  this.togglePlaceholder();

                  this.tabIndex();

                  var selectedItems = $selectOptions.map(function () {
                    if (this.selected) {
                      if (that.options.hideDisabled && (this.disabled || this.parentNode.tagName === 'OPTGROUP' && this.parentNode.disabled)) return;

                      var $this = $(this),
                          icon = $this.data('icon') && that.options.showIcon ? '<i class="' + that.options.iconBase + ' ' + $this.data('icon') + '"></i> ' : '',
                          subtext;

                      if (that.options.showSubtext && $this.data('subtext') && !that.multiple) {
                        subtext = ' <small class="text-muted">' + $this.data('subtext') + '</small>';
                      } else {
                        subtext = '';
                      }
                      if (typeof $this.attr('title') !== 'undefined') {
                        return $this.attr('title');
                      } else if ($this.data('content') && that.options.showContent) {
                        return $this.data('content').toString();
                      } else {
                        return icon + $this.html() + subtext;
                      }
                    }
                  }).toArray();

                  //Fixes issue in IE10 occurring when no default option is selected and at least one option is disabled
                  //Convert all the values into a comma delimited string
                  var title = !this.multiple ? selectedItems[0] : selectedItems.join(this.options.multipleSeparator);

                  //If this is multi select, and the selectText type is count, the show 1 of 2 selected etc..
                  if (this.multiple && this.options.selectedTextFormat.indexOf('count') > -1) {
                    var max = this.options.selectedTextFormat.split('>');
                    if ((max.length > 1 && selectedItems.length > max[1]) || (max.length == 1 && selectedItems.length >= 2)) {
                      notDisabled = this.options.hideDisabled ? ', [disabled]' : '';
                      var totalCount = $selectOptions.not('[data-divider="true"], [data-hidden="true"]' + notDisabled).length,
                          tr8nText = (typeof this.options.countSelectedText === 'function') ? this.options.countSelectedText(selectedItems.length, totalCount) : this.options.countSelectedText;
                      title = tr8nText.replace('{0}', selectedItems.length.toString()).replace('{1}', totalCount.toString());
                    }
                  }

                  if (this.options.title == undefined) {
                    this.options.title = this.$element.attr('title');
                  }

                  if (this.options.selectedTextFormat == 'static') {
                    title = this.options.title;
                  }

                  //If we dont have a title, then use the default, or if nothing is set at all, use the not selected text
                  if (!title) {
                    title = typeof this.options.title !== 'undefined' ? this.options.title : this.options.noneSelectedText;
                  }

                  //strip all HTML tags and trim the result, then unescape any escaped tags
                  this.$button.attr('title', htmlUnescape($.trim(title.replace(/<[^>]*>?/g, ''))));
                  this.$button.children('.filter-option').html(title);

                  this.$element.trigger('rendered.bs.select');
                },

                /**
                 * @param [style]
                 * @param [status]
                 */
                setStyle: function (style, status) {
                  if (this.$element.attr('class')) {
                    this.$newElement.addClass(this.$element.attr('class').replace(/selectpicker|mobile-device|bs-select-hidden|validate\[.*\]/gi, ''));
                  }

                  var buttonClass = style ? style : this.options.style;

                  if (status == 'add') {
                    this.$button.addClass(buttonClass);
                  } else if (status == 'remove') {
                    this.$button.removeClass(buttonClass);
                  } else {
                    this.$button.removeClass(this.options.style);
                    this.$button.addClass(buttonClass);
                  }
                },

                liHeight: function (refresh) {
                  if (!refresh && (this.options.size === false || this.sizeInfo)) return;

                  var newElement = document.createElement('div'),
                      menu = document.createElement('div'),
                      menuInner = document.createElement('ul'),
                      divider = document.createElement('li'),
                      li = document.createElement('li'),
                      a = document.createElement('a'),
                      text = document.createElement('span'),
                      header = this.options.header && this.$menu.find('.popover-title').length > 0 ? this.$menu.find('.popover-title')[0].cloneNode(true) : null,
                      search = this.options.liveSearch ? document.createElement('div') : null,
                      actions = this.options.actionsBox && this.multiple && this.$menu.find('.bs-actionsbox').length > 0 ? this.$menu.find('.bs-actionsbox')[0].cloneNode(true) : null,
                      doneButton = this.options.doneButton && this.multiple && this.$menu.find('.bs-donebutton').length > 0 ? this.$menu.find('.bs-donebutton')[0].cloneNode(true) : null;

                  text.className = 'text';
                  newElement.className = this.$menu[0].parentNode.className + ' open';
                  menu.className = 'dropdown-menu open';
                  menuInner.className = 'dropdown-menu inner';
                  divider.className = 'divider';

                  text.appendChild(document.createTextNode('Inner text'));
                  a.appendChild(text);
                  li.appendChild(a);
                  menuInner.appendChild(li);
                  menuInner.appendChild(divider);
                  if (header) menu.appendChild(header);
                  if (search) {
                    var input = document.createElement('input');
                    search.className = 'bs-searchbox';
                    input.className = 'form-control';
                    search.appendChild(input);
                    menu.appendChild(search);
                  }
                  if (actions) menu.appendChild(actions);
                  menu.appendChild(menuInner);
                  if (doneButton) menu.appendChild(doneButton);
                  newElement.appendChild(menu);

                  document.body.appendChild(newElement);

                  var liHeight = a.offsetHeight,
                      headerHeight = header ? header.offsetHeight : 0,
                      searchHeight = search ? search.offsetHeight : 0,
                      actionsHeight = actions ? actions.offsetHeight : 0,
                      doneButtonHeight = doneButton ? doneButton.offsetHeight : 0,
                      dividerHeight = $(divider).outerHeight(true),
                      // fall back to jQuery if getComputedStyle is not supported
                      menuStyle = typeof getComputedStyle === 'function' ? getComputedStyle(menu) : false,
                      $menu = menuStyle ? null : $(menu),
                      menuPadding = {
                        vert: parseInt(menuStyle ? menuStyle.paddingTop : $menu.css('paddingTop')) +
                              parseInt(menuStyle ? menuStyle.paddingBottom : $menu.css('paddingBottom')) +
                              parseInt(menuStyle ? menuStyle.borderTopWidth : $menu.css('borderTopWidth')) +
                              parseInt(menuStyle ? menuStyle.borderBottomWidth : $menu.css('borderBottomWidth')),
                        horiz: parseInt(menuStyle ? menuStyle.paddingLeft : $menu.css('paddingLeft')) +
                              parseInt(menuStyle ? menuStyle.paddingRight : $menu.css('paddingRight')) +
                              parseInt(menuStyle ? menuStyle.borderLeftWidth : $menu.css('borderLeftWidth')) +
                              parseInt(menuStyle ? menuStyle.borderRightWidth : $menu.css('borderRightWidth'))
                      },
                      menuExtras =  {
                        vert: menuPadding.vert +
                              parseInt(menuStyle ? menuStyle.marginTop : $menu.css('marginTop')) +
                              parseInt(menuStyle ? menuStyle.marginBottom : $menu.css('marginBottom')) + 2,
                        horiz: menuPadding.horiz +
                              parseInt(menuStyle ? menuStyle.marginLeft : $menu.css('marginLeft')) +
                              parseInt(menuStyle ? menuStyle.marginRight : $menu.css('marginRight')) + 2
                      }

                  document.body.removeChild(newElement);

                  this.sizeInfo = {
                    liHeight: liHeight,
                    headerHeight: headerHeight,
                    searchHeight: searchHeight,
                    actionsHeight: actionsHeight,
                    doneButtonHeight: doneButtonHeight,
                    dividerHeight: dividerHeight,
                    menuPadding: menuPadding,
                    menuExtras: menuExtras
                  };
                },

                setSize: function () {
                  this.findLis();
                  this.liHeight();

                  if (this.options.header) this.$menu.css('padding-top', 0);
                  if (this.options.size === false) return;

                  var that = this,
                      $menu = this.$menu,
                      $menuInner = this.$menuInner,
                      $window = $(window),
                      selectHeight = this.$newElement[0].offsetHeight,
                      selectWidth = this.$newElement[0].offsetWidth,
                      liHeight = this.sizeInfo['liHeight'],
                      headerHeight = this.sizeInfo['headerHeight'],
                      searchHeight = this.sizeInfo['searchHeight'],
                      actionsHeight = this.sizeInfo['actionsHeight'],
                      doneButtonHeight = this.sizeInfo['doneButtonHeight'],
                      divHeight = this.sizeInfo['dividerHeight'],
                      menuPadding = this.sizeInfo['menuPadding'],
                      menuExtras = this.sizeInfo['menuExtras'],
                      notDisabled = this.options.hideDisabled ? '.disabled' : '',
                      menuHeight,
                      menuWidth,
                      getHeight,
                      getWidth,
                      selectOffsetTop,
                      selectOffsetBot,
                      selectOffsetLeft,
                      selectOffsetRight,
                      getPos = function() {
                        var pos = that.$newElement.offset(),
                            $container = $(that.options.container),
                            containerPos;

                        if (that.options.container && !$container.is('body')) {
                          containerPos = $container.offset();
                          containerPos.top += parseInt($container.css('borderTopWidth'));
                          containerPos.left += parseInt($container.css('borderLeftWidth'));
                        } else {
                          containerPos = { top: 0, left: 0 };
                        }

                        var winPad = that.options.windowPadding;
                        selectOffsetTop = pos.top - containerPos.top - $window.scrollTop();
                        selectOffsetBot = $window.height() - selectOffsetTop - selectHeight - containerPos.top - winPad[2];
                        selectOffsetLeft = pos.left - containerPos.left - $window.scrollLeft();
                        selectOffsetRight = $window.width() - selectOffsetLeft - selectWidth - containerPos.left - winPad[1];
                        selectOffsetTop -= winPad[0];
                        selectOffsetLeft -= winPad[3];
                      };

                  getPos();

                  if (this.options.size === 'auto') {
                    var getSize = function () {
                      var minHeight,
                          hasClass = function (className, include) {
                            return function (element) {
                                if (include) {
                                    return (element.classList ? element.classList.contains(className) : $(element).hasClass(className));
                                } else {
                                    return !(element.classList ? element.classList.contains(className) : $(element).hasClass(className));
                                }
                            };
                          },
                          lis = that.$menuInner[0].getElementsByTagName('li'),
                          lisVisible = Array.prototype.filter ? Array.prototype.filter.call(lis, hasClass('hidden', false)) : that.$lis.not('.hidden'),
                          optGroup = Array.prototype.filter ? Array.prototype.filter.call(lisVisible, hasClass('dropdown-header', true)) : lisVisible.filter('.dropdown-header');

                      getPos();
                      menuHeight = selectOffsetBot - menuExtras.vert;
                      menuWidth = selectOffsetRight - menuExtras.horiz;

                      if (that.options.container) {
                        if (!$menu.data('height')) $menu.data('height', $menu.height());
                        getHeight = $menu.data('height');

                        if (!$menu.data('width')) $menu.data('width', $menu.width());
                        getWidth = $menu.data('width');
                      } else {
                        getHeight = $menu.height();
                        getWidth = $menu.width();
                      }

                      if (that.options.dropupAuto) {
                        that.$newElement.toggleClass('dropup', selectOffsetTop > selectOffsetBot && (menuHeight - menuExtras.vert) < getHeight);
                      }

                      if (that.$newElement.hasClass('dropup')) {
                        menuHeight = selectOffsetTop - menuExtras.vert;
                      }

                      if (that.options.dropdownAlignRight === 'auto') {
                        $menu.toggleClass('dropdown-menu-right', selectOffsetLeft > selectOffsetRight && (menuWidth - menuExtras.horiz) < (getWidth - selectWidth));
                      }

                      if ((lisVisible.length + optGroup.length) > 3) {
                        minHeight = liHeight * 3 + menuExtras.vert - 2;
                      } else {
                        minHeight = 0;
                      }

                      $menu.css({
                        'max-height': menuHeight + 'px',
                        'overflow': 'hidden',
                        'min-height': minHeight + headerHeight + searchHeight + actionsHeight + doneButtonHeight + 'px'
                      });
                      $menuInner.css({
                        'max-height': menuHeight - headerHeight - searchHeight - actionsHeight - doneButtonHeight - menuPadding.vert + 'px',
                        'overflow-y': 'auto',
                        'min-height': Math.max(minHeight - menuPadding.vert, 0) + 'px'
                      });
                    };
                    getSize();
                    this.$searchbox.off('input.getSize propertychange.getSize').on('input.getSize propertychange.getSize', getSize);
                    $window.off('resize.getSize scroll.getSize').on('resize.getSize scroll.getSize', getSize);
                  } else if (this.options.size && this.options.size != 'auto' && this.$lis.not(notDisabled).length > this.options.size) {
                    var optIndex = this.$lis.not('.divider').not(notDisabled).children().slice(0, this.options.size).last().parent().index(),
                        divLength = this.$lis.slice(0, optIndex + 1).filter('.divider').length;
                    menuHeight = liHeight * this.options.size + divLength * divHeight + menuPadding.vert;

                    if (that.options.container) {
                      if (!$menu.data('height')) $menu.data('height', $menu.height());
                      getHeight = $menu.data('height');
                    } else {
                      getHeight = $menu.height();
                    }

                    if (that.options.dropupAuto) {
                      //noinspection JSUnusedAssignment
                      this.$newElement.toggleClass('dropup', selectOffsetTop > selectOffsetBot && (menuHeight - menuExtras.vert) < getHeight);
                    }
                    $menu.css({
                      'max-height': menuHeight + headerHeight + searchHeight + actionsHeight + doneButtonHeight + 'px',
                      'overflow': 'hidden',
                      'min-height': ''
                    });
                    $menuInner.css({
                      'max-height': menuHeight - menuPadding.vert + 'px',
                      'overflow-y': 'auto',
                      'min-height': ''
                    });
                  }
                },

                setWidth: function () {
                  if (this.options.width === 'auto') {
                    this.$menu.css('min-width', '0');

                    // Get correct width if element is hidden
                    var $selectClone = this.$menu.parent().clone().appendTo('body'),
                        $selectClone2 = this.options.container ? this.$newElement.clone().appendTo('body') : $selectClone,
                        ulWidth = $selectClone.children('.dropdown-menu').outerWidth(),
                        btnWidth = $selectClone2.css('width', 'auto').children('button').outerWidth();

                    $selectClone.remove();
                    $selectClone2.remove();

                    // Set width to whatever's larger, button title or longest option
                    this.$newElement.css('width', Math.max(ulWidth, btnWidth) + 'px');
                  } else if (this.options.width === 'fit') {
                    // Remove inline min-width so width can be changed from 'auto'
                    this.$menu.css('min-width', '');
                    this.$newElement.css('width', '').addClass('fit-width');
                  } else if (this.options.width) {
                    // Remove inline min-width so width can be changed from 'auto'
                    this.$menu.css('min-width', '');
                    this.$newElement.css('width', this.options.width);
                  } else {
                    // Remove inline min-width/width so width can be changed
                    this.$menu.css('min-width', '');
                    this.$newElement.css('width', '');
                  }
                  // Remove fit-width class if width is changed programmatically
                  if (this.$newElement.hasClass('fit-width') && this.options.width !== 'fit') {
                    this.$newElement.removeClass('fit-width');
                  }
                },

                selectPosition: function () {
                  this.$bsContainer = $('<div class="bs-container" />');

                  var that = this,
                      $container = $(this.options.container),
                      pos,
                      containerPos,
                      actualHeight,
                      getPlacement = function ($element) {
                        that.$bsContainer.addClass($element.attr('class').replace(/form-control|fit-width/gi, '')).toggleClass('dropup', $element.hasClass('dropup'));
                        pos = $element.offset();

                        if (!$container.is('body')) {
                          containerPos = $container.offset();
                          containerPos.top += parseInt($container.css('borderTopWidth')) - $container.scrollTop();
                          containerPos.left += parseInt($container.css('borderLeftWidth')) - $container.scrollLeft();
                        } else {
                          containerPos = { top: 0, left: 0 };
                        }

                        actualHeight = $element.hasClass('dropup') ? 0 : $element[0].offsetHeight;

                        that.$bsContainer.css({
                          'top': pos.top - containerPos.top + actualHeight,
                          'left': pos.left - containerPos.left,
                          'width': $element[0].offsetWidth
                        });
                      };

                  this.$button.on('click', function () {
                    var $this = $(this);

                    if (that.isDisabled()) {
                      return;
                    }

                    getPlacement(that.$newElement);

                    that.$bsContainer
                      .appendTo(that.options.container)
                      .toggleClass('open', !$this.hasClass('open'))
                      .append(that.$menu);
                  });

                  $(window).on('resize scroll', function () {
                    getPlacement(that.$newElement);
                  });

                  this.$element.on('hide.bs.select', function () {
                    that.$menu.data('height', that.$menu.height());
                    that.$bsContainer.detach();
                  });
                },

                /**
                 * @param {number} index - the index of the option that is being changed
                 * @param {boolean} selected - true if the option is being selected, false if being deselected
                 * @param {JQuery} $lis - the 'li' element that is being modified
                 */
                setSelected: function (index, selected, $lis) {
                  if (!$lis) {
                    this.togglePlaceholder(); // check if setSelected is being called by changing the value of the select
                    $lis = this.findLis().eq(this.liObj[index]);
                  }

                  $lis.toggleClass('selected', selected).find('a').attr('aria-selected', selected);
                },

                /**
                 * @param {number} index - the index of the option that is being disabled
                 * @param {boolean} disabled - true if the option is being disabled, false if being enabled
                 * @param {JQuery} $lis - the 'li' element that is being modified
                 */
                setDisabled: function (index, disabled, $lis) {
                  if (!$lis) {
                    $lis = this.findLis().eq(this.liObj[index]);
                  }

                  if (disabled) {
                    $lis.addClass('disabled').children('a').attr('href', '#').attr('tabindex', -1).attr('aria-disabled', true);
                  } else {
                    $lis.removeClass('disabled').children('a').removeAttr('href').attr('tabindex', 0).attr('aria-disabled', false);
                  }
                },

                isDisabled: function () {
                  return this.$element[0].disabled;
                },

                checkDisabled: function () {
                  var that = this;

                  if (this.isDisabled()) {
                    this.$newElement.addClass('disabled');
                    this.$button.addClass('disabled').attr('tabindex', -1).attr('aria-disabled', true);
                  } else {
                    if (this.$button.hasClass('disabled')) {
                      this.$newElement.removeClass('disabled');
                      this.$button.removeClass('disabled').attr('aria-disabled', false);
                    }

                    if (this.$button.attr('tabindex') == -1 && !this.$element.data('tabindex')) {
                      this.$button.removeAttr('tabindex');
                    }
                  }

                  this.$button.click(function () {
                    return !that.isDisabled();
                  });
                },

                togglePlaceholder: function () {
                  var value = this.$element.val();
                  this.$button.toggleClass('bs-placeholder', value === null || value === '' || (value.constructor === Array && value.length === 0));
                },

                tabIndex: function () {
                  if (this.$element.data('tabindex') !== this.$element.attr('tabindex') && 
                    (this.$element.attr('tabindex') !== -98 && this.$element.attr('tabindex') !== '-98')) {
                    this.$element.data('tabindex', this.$element.attr('tabindex'));
                    this.$button.attr('tabindex', this.$element.data('tabindex'));
                  }

                  this.$element.attr('tabindex', -98);
                },

                clickListener: function () {
                  var that = this,
                      $document = $(document);

                  $document.data('spaceSelect', false);

                  this.$button.on('keyup', function (e) {
                    if (/(32)/.test(e.keyCode.toString(10)) && $document.data('spaceSelect')) {
                        e.preventDefault();
                        $document.data('spaceSelect', false);
                    }
                  });

                  this.$button.on('click', function () {
                    that.setSize();
                  });

                  this.$element.on('shown.bs.select', function () {
                    if (!that.options.liveSearch && !that.multiple) {
                      that.$menuInner.find('.selected a').focus();
                    } else if (!that.multiple) {
                      var selectedIndex = that.liObj[that.$element[0].selectedIndex];

                      if (typeof selectedIndex !== 'number' || that.options.size === false) return;

                      // scroll to selected option
                      var offset = that.$lis.eq(selectedIndex)[0].offsetTop - that.$menuInner[0].offsetTop;
                      offset = offset - that.$menuInner[0].offsetHeight/2 + that.sizeInfo.liHeight/2;
                      that.$menuInner[0].scrollTop = offset;
                    }
                  });

                  this.$menuInner.on('click', 'li a', function (e) {
                    var $this = $(this),
                        clickedIndex = $this.parent().data('originalIndex'),
                        prevValue = that.$element.val(),
                        prevIndex = that.$element.prop('selectedIndex'),
                        triggerChange = true;

                    // Don't close on multi choice menu
                    if (that.multiple && that.options.maxOptions !== 1) {
                      e.stopPropagation();
                    }

                    e.preventDefault();

                    //Don't run if we have been disabled
                    if (!that.isDisabled() && !$this.parent().hasClass('disabled')) {
                      var $options = that.$element.find('option'),
                          $option = $options.eq(clickedIndex),
                          state = $option.prop('selected'),
                          $optgroup = $option.parent('optgroup'),
                          maxOptions = that.options.maxOptions,
                          maxOptionsGrp = $optgroup.data('maxOptions') || false;

                      if (!that.multiple) { // Deselect all others if not multi select box
                        $options.prop('selected', false);
                        $option.prop('selected', true);
                        that.$menuInner.find('.selected').removeClass('selected').find('a').attr('aria-selected', false);
                        that.setSelected(clickedIndex, true);
                      } else { // Toggle the one we have chosen if we are multi select.
                        $option.prop('selected', !state);
                        that.setSelected(clickedIndex, !state);
                        $this.blur();

                        if (maxOptions !== false || maxOptionsGrp !== false) {
                          var maxReached = maxOptions < $options.filter(':selected').length,
                              maxReachedGrp = maxOptionsGrp < $optgroup.find('option:selected').length;

                          if ((maxOptions && maxReached) || (maxOptionsGrp && maxReachedGrp)) {
                            if (maxOptions && maxOptions == 1) {
                              $options.prop('selected', false);
                              $option.prop('selected', true);
                              that.$menuInner.find('.selected').removeClass('selected');
                              that.setSelected(clickedIndex, true);
                            } else if (maxOptionsGrp && maxOptionsGrp == 1) {
                              $optgroup.find('option:selected').prop('selected', false);
                              $option.prop('selected', true);
                              var optgroupID = $this.parent().data('optgroup');
                              that.$menuInner.find('[data-optgroup="' + optgroupID + '"]').removeClass('selected');
                              that.setSelected(clickedIndex, true);
                            } else {
                              var maxOptionsText = typeof that.options.maxOptionsText === 'string' ? [that.options.maxOptionsText, that.options.maxOptionsText] : that.options.maxOptionsText,
                                  maxOptionsArr = typeof maxOptionsText === 'function' ? maxOptionsText(maxOptions, maxOptionsGrp) : maxOptionsText,
                                  maxTxt = maxOptionsArr[0].replace('{n}', maxOptions),
                                  maxTxtGrp = maxOptionsArr[1].replace('{n}', maxOptionsGrp),
                                  $notify = $('<div class="notify"></div>');
                              // If {var} is set in array, replace it
                              /** @deprecated */
                              if (maxOptionsArr[2]) {
                                maxTxt = maxTxt.replace('{var}', maxOptionsArr[2][maxOptions > 1 ? 0 : 1]);
                                maxTxtGrp = maxTxtGrp.replace('{var}', maxOptionsArr[2][maxOptionsGrp > 1 ? 0 : 1]);
                              }

                              $option.prop('selected', false);

                              that.$menu.append($notify);

                              if (maxOptions && maxReached) {
                                $notify.append($('<div>' + maxTxt + '</div>'));
                                triggerChange = false;
                                that.$element.trigger('maxReached.bs.select');
                              }

                              if (maxOptionsGrp && maxReachedGrp) {
                                $notify.append($('<div>' + maxTxtGrp + '</div>'));
                                triggerChange = false;
                                that.$element.trigger('maxReachedGrp.bs.select');
                              }

                              setTimeout(function () {
                                that.setSelected(clickedIndex, false);
                              }, 10);

                              $notify.delay(750).fadeOut(300, function () {
                                $(this).remove();
                              });
                            }
                          }
                        }
                      }

                      if (!that.multiple || (that.multiple && that.options.maxOptions === 1)) {
                        that.$button.focus();
                      } else if (that.options.liveSearch) {
                        that.$searchbox.focus();
                      }

                      // Trigger select 'change'
                      if (triggerChange) {
                        if ((prevValue != that.$element.val() && that.multiple) || (prevIndex != that.$element.prop('selectedIndex') && !that.multiple)) {
                          // $option.prop('selected') is current option state (selected/unselected). state is previous option state.
                          changed_arguments = [clickedIndex, $option.prop('selected'), state];
                          that.$element
                            .triggerNative('change');
                        }
                      }
                    }
                  });

                  this.$menu.on('click', 'li.disabled a, .popover-title, .popover-title :not(.close)', function (e) {
                    if (e.currentTarget == this) {
                      e.preventDefault();
                      e.stopPropagation();
                      if (that.options.liveSearch && !$(e.target).hasClass('close')) {
                        that.$searchbox.focus();
                      } else {
                        that.$button.focus();
                      }
                    }
                  });

                  this.$menuInner.on('click', '.divider, .dropdown-header', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (that.options.liveSearch) {
                      that.$searchbox.focus();
                    } else {
                      that.$button.focus();
                    }
                  });

                  this.$menu.on('click', '.popover-title .close', function () {
                    that.$button.click();
                  });

                  this.$searchbox.on('click', function (e) {
                    e.stopPropagation();
                  });

                  this.$menu.on('click', '.actions-btn', function (e) {
                    if (that.options.liveSearch) {
                      that.$searchbox.focus();
                    } else {
                      that.$button.focus();
                    }

                    e.preventDefault();
                    e.stopPropagation();

                    if ($(this).hasClass('bs-select-all')) {
                      that.selectAll();
                    } else {
                      that.deselectAll();
                    }
                  });

                  this.$element.change(function () {
                    that.render(false);
                    that.$element.trigger('changed.bs.select', changed_arguments);
                    changed_arguments = null;
                  });
                },

                liveSearchListener: function () {
                  var that = this,
                      $no_results = $('<li class="no-results"></li>');

                  this.$button.on('click.dropdown.data-api', function () {
                    that.$menuInner.find('.active').removeClass('active');
                    if (!!that.$searchbox.val()) {
                      that.$searchbox.val('');
                      that.$lis.not('.is-hidden').removeClass('hidden');
                      if (!!$no_results.parent().length) $no_results.remove();
                    }
                    if (!that.multiple) that.$menuInner.find('.selected').addClass('active');
                    setTimeout(function () {
                      that.$searchbox.focus();
                    }, 10);
                  });

                  this.$searchbox.on('click.dropdown.data-api focus.dropdown.data-api touchend.dropdown.data-api', function (e) {
                    e.stopPropagation();
                  });

                  this.$searchbox.on('input propertychange', function () {
                    that.$lis.not('.is-hidden').removeClass('hidden');
                    that.$lis.filter('.active').removeClass('active');
                    $no_results.remove();

                    if (that.$searchbox.val()) {
                      var $searchBase = that.$lis.not('.is-hidden, .divider, .dropdown-header'),
                          $hideItems;
                      if (that.options.liveSearchNormalize) {
                        $hideItems = $searchBase.not(':a' + that._searchStyle() + '("' + normalizeToBase(that.$searchbox.val()) + '")');
                      } else {
                        $hideItems = $searchBase.not(':' + that._searchStyle() + '("' + that.$searchbox.val() + '")');
                      }

                      if ($hideItems.length === $searchBase.length) {
                        $no_results.html(that.options.noneResultsText.replace('{0}', '"' + htmlEscape(that.$searchbox.val()) + '"'));
                        that.$menuInner.append($no_results);
                        that.$lis.addClass('hidden');
                      } else {
                        $hideItems.addClass('hidden');

                        var $lisVisible = that.$lis.not('.hidden'),
                            $foundDiv;

                        // hide divider if first or last visible, or if followed by another divider
                        $lisVisible.each(function (index) {
                          var $this = $(this);

                          if ($this.hasClass('divider')) {
                            if ($foundDiv === undefined) {
                              $this.addClass('hidden');
                            } else {
                              if ($foundDiv) $foundDiv.addClass('hidden');
                              $foundDiv = $this;
                            }
                          } else if ($this.hasClass('dropdown-header') && $lisVisible.eq(index + 1).data('optgroup') !== $this.data('optgroup')) {
                            $this.addClass('hidden');
                          } else {
                            $foundDiv = null;
                          }
                        });
                        if ($foundDiv) $foundDiv.addClass('hidden');

                        $searchBase.not('.hidden').first().addClass('active');
                        that.$menuInner.scrollTop(0);
                      }
                    }
                  });
                },

                _searchStyle: function () {
                  var styles = {
                    begins: 'ibegins',
                    startsWith: 'ibegins'
                  };

                  return styles[this.options.liveSearchStyle] || 'icontains';
                },

                val: function (value) {
                  if (typeof value !== 'undefined') {
                    this.$element.val(value);
                    this.render();

                    return this.$element;
                  } else {
                    return this.$element.val();
                  }
                },

                changeAll: function (status) {
                  if (!this.multiple) return;
                  if (typeof status === 'undefined') status = true;

                  this.findLis();

                  var $options = this.$element.find('option'),
                      $lisVisible = this.$lis.not('.divider, .dropdown-header, .disabled, .hidden'),
                      lisVisLen = $lisVisible.length,
                      selectedOptions = [];
                      
                  if (status) {
                    if ($lisVisible.filter('.selected').length === $lisVisible.length) return;
                  } else {
                    if ($lisVisible.filter('.selected').length === 0) return;
                  }
                      
                  $lisVisible.toggleClass('selected', status);

                  for (var i = 0; i < lisVisLen; i++) {
                    var origIndex = $lisVisible[i].getAttribute('data-original-index');
                    selectedOptions[selectedOptions.length] = $options.eq(origIndex)[0];
                  }

                  $(selectedOptions).prop('selected', status);

                  this.render(false);

                  this.togglePlaceholder();

                  this.$element
                    .triggerNative('change');
                },

                selectAll: function () {
                  return this.changeAll(true);
                },

                deselectAll: function () {
                  return this.changeAll(false);
                },

                toggle: function (e) {
                  e = e || window.event;

                  if (e) e.stopPropagation();

                  this.$button.trigger('click');
                },

                keydown: function (e) {
                  var $this = $(this),
                      $parent = $this.is('input') ? $this.parent().parent() : $this.parent(),
                      $items,
                      that = $parent.data('this'),
                      index,
                      prevIndex,
                      isActive,
                      selector = ':not(.disabled, .hidden, .dropdown-header, .divider)',
                      keyCodeMap = {
                        32: ' ',
                        48: '0',
                        49: '1',
                        50: '2',
                        51: '3',
                        52: '4',
                        53: '5',
                        54: '6',
                        55: '7',
                        56: '8',
                        57: '9',
                        59: ';',
                        65: 'a',
                        66: 'b',
                        67: 'c',
                        68: 'd',
                        69: 'e',
                        70: 'f',
                        71: 'g',
                        72: 'h',
                        73: 'i',
                        74: 'j',
                        75: 'k',
                        76: 'l',
                        77: 'm',
                        78: 'n',
                        79: 'o',
                        80: 'p',
                        81: 'q',
                        82: 'r',
                        83: 's',
                        84: 't',
                        85: 'u',
                        86: 'v',
                        87: 'w',
                        88: 'x',
                        89: 'y',
                        90: 'z',
                        96: '0',
                        97: '1',
                        98: '2',
                        99: '3',
                        100: '4',
                        101: '5',
                        102: '6',
                        103: '7',
                        104: '8',
                        105: '9'
                      };


                  isActive = that.$newElement.hasClass('open');

                  if (!isActive && (e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 96 && e.keyCode <= 105 || e.keyCode >= 65 && e.keyCode <= 90)) {
                    if (!that.options.container) {
                      that.setSize();
                      that.$menu.parent().addClass('open');
                      isActive = true;
                    } else {
                      that.$button.trigger('click');
                    }
                    that.$searchbox.focus();
                    return;
                  }

                  if (that.options.liveSearch) {
                    if (/(^9$|27)/.test(e.keyCode.toString(10)) && isActive) {
                      e.preventDefault();
                      e.stopPropagation();
                      that.$menuInner.click();
                      that.$button.focus();
                    }
                  }

                  if (/(38|40)/.test(e.keyCode.toString(10))) {
                    $items = that.$lis.filter(selector);
                    if (!$items.length) return;

                    if (!that.options.liveSearch) {
                      index = $items.index($items.find('a').filter(':focus').parent());
                  } else {
                      index = $items.index($items.filter('.active'));
                    }

                    prevIndex = that.$menuInner.data('prevIndex');

                    if (e.keyCode == 38) {
                      if ((that.options.liveSearch || index == prevIndex) && index != -1) index--;
                      if (index < 0) index += $items.length;
                    } else if (e.keyCode == 40) {
                      if (that.options.liveSearch || index == prevIndex) index++;
                      index = index % $items.length;
                    }

                    that.$menuInner.data('prevIndex', index);

                    if (!that.options.liveSearch) {
                      $items.eq(index).children('a').focus();
                    } else {
                      e.preventDefault();
                      if (!$this.hasClass('dropdown-toggle')) {
                        $items.removeClass('active').eq(index).addClass('active').children('a').focus();
                        $this.focus();
                      }
                    }

                  } else if (!$this.is('input')) {
                    var keyIndex = [],
                        count,
                        prevKey;

                    $items = that.$lis.filter(selector);
                    $items.each(function (i) {
                      if ($.trim($(this).children('a').text().toLowerCase()).substring(0, 1) == keyCodeMap[e.keyCode]) {
                        keyIndex.push(i);
                      }
                    });

                    count = $(document).data('keycount');
                    count++;
                    $(document).data('keycount', count);

                    prevKey = $.trim($(':focus').text().toLowerCase()).substring(0, 1);

                    if (prevKey != keyCodeMap[e.keyCode]) {
                      count = 1;
                      $(document).data('keycount', count);
                    } else if (count >= keyIndex.length) {
                      $(document).data('keycount', 0);
                      if (count > keyIndex.length) count = 1;
                    }

                    $items.eq(keyIndex[count - 1]).children('a').focus();
                  }

                  // Select focused option if "Enter", "Spacebar" or "Tab" (when selectOnTab is true) are pressed inside the menu.
                  if ((/(13|32)/.test(e.keyCode.toString(10)) || (/(^9$)/.test(e.keyCode.toString(10)) && that.options.selectOnTab)) && isActive) {
                    if (!/(32)/.test(e.keyCode.toString(10))) e.preventDefault();
                    if (!that.options.liveSearch) {
                      var elem = $(':focus');
                      elem.click();
                      // Bring back focus for multiselects
                      elem.focus();
                      // Prevent screen from scrolling if the user hit the spacebar
                      e.preventDefault();
                      // Fixes spacebar selection of dropdown items in FF & IE
                      $(document).data('spaceSelect', true);
                    } else if (!/(32)/.test(e.keyCode.toString(10))) {
                      that.$menuInner.find('.active a').click();
                      $this.focus();
                    }
                    $(document).data('keycount', 0);
                  }

                  if ((/(^9$|27)/.test(e.keyCode.toString(10)) && isActive && (that.multiple || that.options.liveSearch)) || (/(27)/.test(e.keyCode.toString(10)) && !isActive)) {
                    that.$menu.parent().removeClass('open');
                    if (that.options.container) that.$newElement.removeClass('open');
                    that.$button.focus();
                  }
                },

                mobile: function () {
                  this.$element.addClass('mobile-device');
                },

                refresh: function () {
                  this.$lis = null;
                  this.liObj = {};
                  this.reloadLi();
                  this.render();
                  this.checkDisabled();
                  this.liHeight(true);
                  this.setStyle();
                  this.setWidth();
                  if (this.$lis) this.$searchbox.trigger('propertychange');

                  this.$element.trigger('refreshed.bs.select');
                },

                hide: function () {
                  this.$newElement.hide();
                },

                show: function () {
                  this.$newElement.show();
                },

                remove: function () {
                  this.$newElement.remove();
                  this.$element.remove();
                },

                destroy: function () {
                  this.$newElement.before(this.$element).remove();

                  if (this.$bsContainer) {
                    this.$bsContainer.remove();
                  } else {
                    this.$menu.remove();
                  }

                  this.$element
                    .off('.bs.select')
                    .removeData('selectpicker')
                    .removeClass('bs-select-hidden selectpicker');
                }
              };

              // SELECTPICKER PLUGIN DEFINITION
              // ==============================
              function Plugin(option) {
                // get the args of the outer function..
                var args = arguments;
                // The arguments of the function are explicitly re-defined from the argument list, because the shift causes them
                // to get lost/corrupted in android 2.3 and IE9 #715 #775
                var _option = option;

                [].shift.apply(args);

                var value;
                var chain = this.each(function () {
                  var $this = $(this);
                  if ($this.is('select')) {
                    var data = $this.data('selectpicker'),
                        options = typeof _option == 'object' && _option;

                    if (!data) {
                      var config = $.extend({}, Selectpicker.DEFAULTS, $.fn.selectpicker.defaults || {}, $this.data(), options);
                      config.template = $.extend({}, Selectpicker.DEFAULTS.template, ($.fn.selectpicker.defaults ? $.fn.selectpicker.defaults.template : {}), $this.data().template, options.template);
                      $this.data('selectpicker', (data = new Selectpicker(this, config)));
                    } else if (options) {
                      for (var i in options) {
                        if (options.hasOwnProperty(i)) {
                          data.options[i] = options[i];
                        }
                      }
                    }

                    if (typeof _option == 'string') {
                      if (data[_option] instanceof Function) {
                        value = data[_option].apply(data, args);
                      } else {
                        value = data.options[_option];
                      }
                    }
                  }
                });

                if (typeof value !== 'undefined') {
                  //noinspection JSUnusedAssignment
                  return value;
                } else {
                  return chain;
                }
              }

              var old = $.fn.selectpicker;
              $.fn.selectpicker = Plugin;
              $.fn.selectpicker.Constructor = Selectpicker;

              // SELECTPICKER NO CONFLICT
              // ========================
              $.fn.selectpicker.noConflict = function () {
                $.fn.selectpicker = old;
                return this;
              };

      $(document)
          .data('keycount', 0)
          .on('keydown.bs.select', '.bootstrap-select [data-toggle=dropdown], .bootstrap-select [role="listbox"], .bs-searchbox input', Selectpicker.prototype.keydown)
          .on('focusin.modal', '.bootstrap-select [data-toggle=dropdown], .bootstrap-select [role="listbox"], .bs-searchbox input', function (e) {
            e.stopPropagation();
          });

      // SELECTPICKER DATA-API
      // =====================
      $(window).on('load.bs.select.data-api', function () {
        $('.selectpicker').each(function () {
          var $selectpicker = $(this);
          Plugin.call($selectpicker, $selectpicker.data());
        })
      });
})(jQuery);
// =============================================
// TOP SEARCH DROPDOWN
// =============================================

// =====================================================
  // Quick View

  $("body").on("click",".quick_view_item",function(e){
    var txt=$(this).attr('data-comment');
    // var existing_txt=$(".buying-requirements").html();
    //$(".buying-requirements .default-com").html(txt);
	  // $(".buying-requirements").html(txt);
    $(".default-com").html(txt);
  });
  $("body").on("click",".del-comm",function(e){
      
        var base_URL = $("#base_url").val();
        var click_btn=$(this);
        var id=$(this).attr("data-id");
        var indexToRemove = $(this).parent().parent().parent().index();
        
        swal({
            title: 'Warning',
            text: 'Are you sure? Do you want to delete the record?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
          }, 
          function(isConfirm) {
                if (isConfirm) {
                    var data = "id="+id;  
                    $.ajax({
                        url: base_URL+"lead/delete_lead_update_pre_define_comment",
                        data: data,
                        cache: false,
                        method: 'POST',
                        dataType: "html",
                        beforeSend: function( xhr ) {},
                        success:function(res){ 
                            result = $.parseJSON(res);
                            if(result.status=='success')
                            {
                                swal('Success!', result.msg, 'success'); 
                                $("#item_"+id).parent().html(''); 
                                $(this).parent().parent().parent().index();      
                                $("#txt-carousel").trigger('remove.owl.carousel', [indexToRemove]).trigger('refresh.owl.carousel'); 
                                var c = $('#txt-carousel .owl-stage .owl-item').size();  
                                $('.quick_reply_count').html('('+c+')');                 
                                                      
                            }
                        },
                        complete: function(){},
                        error: function(response) {
                        //alert('Error'+response.table);
                        }
                    })
              }
              return false;
          });        
    });
  /*
  $(document).on('click', 'a.del-item', function (e) {
      e.preventDefault();
      var base_URL = $("#base_url").val();
      var id=$(this).attr("data-id");
      var indexToRemove = $(this).parent().parent().parent().index();
      
      $("#txt-carousel").trigger('remove.owl.carousel', [indexToRemove]).trigger('refresh.owl.carousel');

      var c = $('#txt-carousel .owl-stage .owl-item').size();

        //$('.quick_view_h1_tag').removeClass('hide');
        //$('.quick_reply_count').removeClass('hide');
        $('.quick_reply_count').html('('+c+')');

    });
  */
  $(document).on('click', '.add_quick_view_comment', function (e) {
      e.preventDefault();

      var getTxt = $('#quick_view_title').val();
      // var getTxtDesc=tinyMCE.activeEditor.getContent();
      // var getTxtDesc=tinyMCE.get('quick_view_desc').getContent();
      var getTxtDesc = $('#quick_view_desc').val();
      var getTarget = $(this).attr('data-target');
      var error='';

      if(getTxt =='')
      {  
        $("#quick_view_title_error").text("Required");
        $("#quick_view_title").addClass("field-error");
        error='1';
        e.stopPropagation();
      } 
      else
      {
        $("#quick_view_title_error").text("");
        $("#quick_view_title").removeClass("field-error");
         error='';
      }

      if(getTxtDesc=='')
      {  
        $("#quick_view_desc_error").text("Required");
        $("#quick_view_desc").addClass("field-error");
         error='1';
        e.stopPropagation();
      } 
      else
      {
        $("#quick_view_desc_error").text("");
        $("#quick_view_desc").removeClass("field-error");
        error='';
      }
         
      if(error=='')
      {        
        getTxtDesc=getTxtDesc.replace(/\r?\n/g, '<br />');
        var base_URL = $("#base_url").val();        
        var data = "title="+getTxt+"&description="+getTxtDesc;
        // alert(data); return false;
        $.ajax({
            url: base_URL+"lead/add_lead_update_pre_define_comment",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {
                
            },
            complete: function(){
                
            },
            success:function(res){ 
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                    var getTxtDesc_title=getTxtDesc.replace( /(<([^>]+)>)/ig, '');

                    $('[data-toggle="tooltip"]').tooltipster('destroy');
                    var ttt = "<div class='item noshow'><div class='auto-txt-item'>'"+getTxt+"'<a href='#' class='del-item'><i class='fa fa-times' aria-hidden='true'></i></a></div></div>";
                    $('body').append(ttt);
                    var gdw = $('.noshow .auto-txt-item').innerWidth();
                    $('.noshow').remove();
                    var ttt = "<div class='item' style='width: '"+gdw+"'px;'><div class='auto-txt-item quick_view_item' data-toggle='tooltip'  title='"+getTxtDesc_title+"' data-comment='"+getTxtDesc+"'>"+getTxt+"<a href='JavaScript:void(0)' data-id='"+result.id+"' class='del-item del-comm'><i class='fa fa-times' aria-hidden='true'></i></a></div></div>";
                    
                    $('#'+getTarget).trigger('add.owl.carousel', [$(ttt), 0]).trigger('refresh.owl.carousel');
                    $('#'+getTarget).trigger('to.owl.carousel', 0);
                    
                    $('.com-holder-new .com-holder-fild input').val('');

                    $('[data-toggle="tooltip"]').tooltipster();
                    ////////
                    var c = $('#'+getTarget+' .owl-stage .owl-item').size();

                    $('.quick_view_h1_tag').removeClass('hide');
                    $('.quick_reply_count').removeClass('hide');
                    $('.quick_reply_count').html('('+c+')');


                    $('#quick_view_title').val('');
                    $('#quick_view_desc').val('');
                    // tinyMCE.activeEditor.setContent('');
                    // tinyMCE.get('quick_view_desc').setContent('');
                    $(".add-com").removeClass('open');
                }
            },            
            error: function(response) {
            //alert('Error'+response.table);
            }
        });
      }
   });
   $(document).on('click', '.close_quick_view_comment', function (e) {
      e.preventDefault();
	  
        $("#quick_view_desc_error").text("");
        $("#quick_view_title_error").text("");
        $('#quick_view_title').val('');
        $('#quick_view_desc').val('');
        tinyMCE.activeEditor.setContent('');
		$(".add-com").removeClass('open');		
   });
  $('#CommentUpdateLeadModal').on('shown.bs.modal', function (e) {
      // do something...
      //console.log('do something...')
      $('#txt-carousel .owl-stage .owl-item').each(function( index ) {
         var gItemw = $(this).find('.auto-txt-item').outerWidth();
         //console.log( index + ": " + gItemw );
         $(this).find('.item').css({'width':gItemw});
         $('#txt-carousel').trigger('refresh.owl.carousel');

      });
   });
  $('#ReplyPopupModal').on('shown.bs.modal', function (e) {
      // do something...
      //console.log('do something...')
      $('#txt-carousel .owl-stage .owl-item').each(function( index ) {
         var gItemw = $(this).find('.auto-txt-item').outerWidth();
         //console.log( index + ": " + gItemw );
         $(this).find('.item').css({'width':gItemw});
         $('#txt-carousel').trigger('refresh.owl.carousel');

      });
   });
  // Quick View
  // =====================================================

  $("body").on("change","#country",function(e){
        var cid=$(this).val();
        var base_url = $("#base_url").val();
        $.ajax({
            url: base_url + "lead/get_country_code_ajax",
            type: "POST",
            data: {
                'cid': cid
            },
            async: true,
            success: function(data) {
                result = $.parseJSON(data);                
                $("#mobile_country_code_div").html('+'+result.country_code);
                $("#alt_mobile_country_code_div").html('+'+result.country_code);
                $("#landline_country_code_div").html('+'+result.country_code);
                $("#office_country_code_div").html('+'+result.country_code);
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
    });

    $("body").on("click",".get_template_variable",function(e){         
        $("#template_variable_list_modal").modal({
          backdrop: 'static',
          keyboard: false,
          //callback: fn_rander_company_details(id)
        });
    });
    var copy = document.querySelectorAll(".copy_template_variable"); 
      for (const copied of copy) { 
         copied.onclick = function() { 
            document.execCommand("copy"); 
         };  
         copied.addEventListener("copy", function(event) { 
            event.preventDefault(); 
            if (event.clipboardData) { 
                event.clipboardData.setData("text/plain", copied.textContent);
                //console.log(event.clipboardData.getData("text"));
                //  alert(event.clipboardData.attr("data-reserve_keyword"))                
               swal("Template Variable: '"+event.clipboardData.getData("text")+"' copied!"); 
            };
         });
      };

    
});
  

function GetStateList(cont,rander_name='',selected_id='')
{  
	$.ajax({
		  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getstatelist",
		  type: "POST",
		  data: {'country_id':cont,'selected_id':selected_id},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{	
          //alert(response);
          //document.getElementById('state').innerHTML=response;          
          var rander_name_tmp=(rander_name)?rander_name:'#state';  
          let pos_tmp = rander_name_tmp.indexOf("#"); 
          if(pos_tmp!=0){
            var rander_name_tmp='#'+rander_name_tmp; 
          }    
          $(rander_name_tmp).html(response);
        }
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}
	
function GetCityList(state,rander_name='',selected_id='')
{
	$.ajax({
		  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getcitylist",
		  type: "POST",
		  data: {'state_id':state,'selected_id':selected_id},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{
          var rander_name_tmp=(rander_name)?rander_name:'#city'; 
          let pos_tmp = rander_name_tmp.indexOf("#"); 
          if(pos_tmp!=0){
            var rander_name_tmp='#'+rander_name_tmp; 
          } 
          $(rander_name_tmp).html(response);      
				  // document.getElementById('city').innerHTML=response;
			  }
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}

function GetSmsTemplateList(api_id,selected_id='',rander_pointer='')
{
  // alert("API ID:"+api_id+'/ Selected'+selected_id)
	$.ajax({
		  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/getsmstemplatelist",
		  type: "POST",
		  data: {'api_id':api_id,'selected_id':selected_id},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{                 
          $(rander_pointer).html(response);
        }
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}

function GetWhatsappTemplateList(api_id,selected_id='',rander_pointer='')
{
  // alert("API ID:"+api_id+'/ Selected'+selected_id)
	$.ajax({
		  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/getwhatsapptemplatelist",
		  type: "POST",
		  data: {'api_id':api_id,'selected_id':selected_id},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{                 
          $(rander_pointer).html(response);
        }
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}
<?php 
if($idle_info['is_session_expire_for_idle']=='Y' && $idle_info['idle_time']>0)
{ 
?>
  var idleTime = 0;
  var idle_time_in_minut=(<?php echo $idle_info['idle_time']; ?>*60);
  $(document).ready(function () {
      // Increment the idle time counter every minute.
      var idleInterval = setInterval(timerIncrement, (parseInt(idle_time_in_minut)*1000)); // 5 minute    
      // Zero the idle timer on mouse movement.
      $(this).mousemove(function (e) {
          idleTime = 0;        
      });
      $(this).keypress(function (e) {
          idleTime = 0;        
      });
      $(this).scroll(function (e) {
          idleTime = 0;
      });
      
  });
  function timerIncrement() 
  {
      idleTime = idleTime + 1;
      
      if (idleTime > 1) { 

        $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/logout/idle_logout",
        type: "POST",
        data: {},		  
        success: function (response) 
        {
          if(response=='Y')
          {                 
            alert("Session has been expired!")
            location.href = '<?php echo admin_url(); ?>logout/index';
          }		  		
        },
        error: function () 
        {}
      });      
      }
  }

<?php 
} 
?>
<?php  ?>


$(document).ready(function () {


    var checkCloseX = 0;
    $(document).mousemove(function (e) {
      //console.log(e.pageY)
      if (e.pageY <= 10) {
          checkCloseX = 1;
      }
      else 
      { 
        checkCloseX = 0; 
      }
    });   

    window.onbeforeunload = function (event) {
        if (event) {
            if (checkCloseX == 1) {

                // alert('1111');                    
                $.ajax({                        
                    url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/logout/on_browser_close_logout",
                    type: "POST",
                    success: function (result) {
                        if (result != null) {
                        }
                    }
                });
            }
        }
    };
});
<?php /* ?>
window.onbeforeunload = BrowserCloseAction;
function BrowserCloseAction()
{
    $.ajax({                        
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/logout/on_browser_close_logout",
        type: "POST",
        success: function (result) {
            if (result != null) {
            }
        }
    });
}
<?php */ ?>
//  ==================================================================================
// =================== MEETING =======================================================
$("body").on("click",".meeting_report",function(e){
      var base_url = $("#base_url").val(); 
      var lead_id=$(this).attr("data-leadid");
      var meeting_checkin_date=$(this).attr("data-date");
      var meeting_checkout_date=$(this).attr("data-date2");
      var meeting_user_id=$(this).attr("data-user_id");
      // alert(meeting_checkin_date+'/'+meeting_checkout_date)  
      $.ajax({
         url: base_url + "lead/rander_meeting_report_view_popup_ajax",
         type: "POST",
         data: {"lead_id":lead_id,"meeting_checkin_date":meeting_checkin_date,"meeting_user_id":meeting_user_id,"meeting_checkout_date":meeting_checkout_date},
         async: false,
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
         complete: function (){                          
               $.unblockUI();
         },
         success: function(response) {               
               $('#meetingReport').html(response); 
               $('#meetingReport').modal({
                     backdrop: 'static',
                     keyboard: false
               });
         },
         error: function() {
            
         }
      });


    });
    
    
    $("body").on("click",".sort_order_meeting_report",function(e){
        var tmp_field=$(this).attr('data-field');
        var curr_orderby=$(this).attr('data-orderby');
        var new_orderby=(curr_orderby=='asc')?'desc':'asc';
        $(this).attr('data-orderby',new_orderby);
        $(".sort_order").removeClass('asc');
        $(".sort_order").removeClass('desc');
        $(this).addClass(curr_orderby);
        $("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
        rander_meeting_report();
    });
    $(document).on('click', '.meeting_pagination_class', function (e) { 
		e.preventDefault();
		var str = $(this).attr('href'); 
		var res = str.split("/");
		var cur_page = res[1];
		$("#meeting_page_number").val(cur_page);        
		rander_meeting_report();
	});
    function rander_meeting_report()
   {
      var base_URL = $("#base_url").val();
      var page=$("#meeting_page_number").val(); 
      var filter_sort_by=$("#meeting_filter_sort_by").val();
      var filter_by_lead_id=$("#meeting_filter_by_lead_id").val();
      var filter_by_keyword=$("#meeting_filter_by_keyword").val();
      var filter_by_user_id=$("#meeting_filter_by_user_id").val();
      var filter_by_status_id=$("#meeting_filter_by_status_id").val();
      var filter_by_meeting_type=$("#meeting_filter_by_meeting_type").val();
      var filter_by_meeting_agenda_type_id=$("#meeting_filter_by_meeting_agenda_type_id").val();
      var filter_by_self_visited_or_visited_with_colleagues=$("#meeting_filter_by_self_visited_or_visited_with_colleagues").val();
      var filter_by_start_date=$("#meeting_filter_by_start_date").val();
      var filter_by_end_date=$("#meeting_filter_by_end_date").val();
      var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&filter_by_keyword="+filter_by_keyword+"&filter_by_user_id="+filter_by_user_id+"&filter_by_status_id="+filter_by_status_id+"&filter_by_meeting_type="+filter_by_meeting_type+"&filter_by_meeting_agenda_type_id="+filter_by_meeting_agenda_type_id+"&filter_by_self_visited_or_visited_with_colleagues="+filter_by_self_visited_or_visited_with_colleagues+"&filter_by_start_date="+filter_by_start_date+"&filter_by_end_date="+filter_by_end_date+"&filter_by_lead_id="+filter_by_lead_id;
      // alert(data)
      $.ajax({
         url: base_URL+"lead/rander_meeting_report_list_ajax/"+page,
         data: data,
         cache: false,
         method: 'GET',
         dataType: "html",
         beforeSend: function( xhr ) {                
               // $.blockUI({ 
               //     message: 'Please wait...', 
               //     css: { 
               //             padding: '10px', 
               //             backgroundColor: '#fff', 
               //             border:'0px solid #000',
               //             '-webkit-border-radius': '10px', 
               //             '-moz-border-radius': '10px', 
               //             opacity: .5, 
               //             color: '#000',
               //             width:'450px',
               //             'font-size':'14px'
               //     }
               // });
               addLoader('#meeting_tcontent');
         },
         complete: function(){
               // $.unblockUI();
               removeLoader();
         },
         success:function(res){ 
               result = $.parseJSON(res);
               // alert(result.status)
               if(result.status!='success'){
                  swal('Oops!',result.msg,'error');
               }
               $("#meeting_tcontent").html(result.table);
               $("#meeting_page").html(result.page);
               $("#meeting_page_record_count_info").html(result.page_record_count_info);
               //updateScrollBar();
               
      },           
      error: function(response) {
         //alert('Error'+response.table);
         }
      });
   }

   $(document).on("click","#download_meeting_report_csv",function (e){
        
      var base_URL     = $("#base_url").val();       
      var filter_sort_by=$("#meeting_filter_sort_by").val();
      var filter_by_lead_id=$("#meeting_filter_by_lead_id").val();
      var filter_by_keyword=$("#meeting_filter_by_keyword").val();
      var filter_by_user_id=$("#meeting_filter_by_user_id").val();
      var filter_by_status_id=$("#meeting_filter_by_status_id").val();
      var filter_by_meeting_type=$("#meeting_filter_by_meeting_type").val();
      var filter_by_meeting_agenda_type_id=$("#meeting_filter_by_meeting_agenda_type_id").val();
      var filter_by_self_visited_or_visited_with_colleagues=$("#meeting_filter_by_self_visited_or_visited_with_colleagues").val();
      var filter_by_start_date=$("#meeting_filter_by_start_date").val();
      var filter_by_end_date=$("#meeting_filter_by_end_date").val();
      var data = "filter_sort_by="+filter_sort_by+"&filter_by_keyword="+filter_by_keyword+"&filter_by_user_id="+filter_by_user_id+"&filter_by_status_id="+filter_by_status_id+"&filter_by_meeting_type="+filter_by_meeting_type+"&filter_by_meeting_agenda_type_id="+filter_by_meeting_agenda_type_id+"&filter_by_self_visited_or_visited_with_colleagues="+filter_by_self_visited_or_visited_with_colleagues+"&filter_by_start_date="+filter_by_start_date+"&filter_by_end_date="+filter_by_end_date+"&filter_by_lead_id="+filter_by_lead_id;
      
      document.location.href = base_URL+'lead/download_meeting_report_csv/?'+data;
    });

    $("body").on("click","#meeting_report_search_confirm",function(e){
        var by_keyword=$("#meeting_by_keyword").val();
        var by_user_id=$("#meeting_by_user_id").val();
        var by_status_id=$("#meeting_by_status_id").val();
        var by_meeting_type=$("#meeting_by_meeting_type").val();
        var by_meeting_agenda_type_id=$("#meeting_by_meeting_agenda_type_id").val();
        var by_self_visited_or_visited_with_colleagues=$("#meeting_by_self_visited_or_visited_with_colleagues").val();
        var by_start_date=$("#meeting_by_start_date").val();
        var by_end_date=$("#meeting_by_end_date").val();

        $("#meeting_page_number").val('1');
        $("#meeting_filter_by_keyword").val(by_keyword);
        $("#meeting_filter_by_user_id").val(by_user_id);
        $("#meeting_filter_by_status_id").val(by_status_id);
        $("#meeting_filter_by_meeting_type").val(by_meeting_type);
        $("#meeting_filter_by_meeting_agenda_type_id").val(by_meeting_agenda_type_id);
        $("#meeting_filter_by_self_visited_or_visited_with_colleagues").val(by_self_visited_or_visited_with_colleagues);
        $("#meeting_filter_by_start_date").val(by_start_date);
        $("#meeting_filter_by_end_date").val(by_end_date);
        rander_meeting_report();
    });
    $("body").on("click","#meeting_schedule_submit_confirm",function(e){  
          var id=$("#id").val();
          var lead_id=$("#lead_id").val();
          var c_id=$("#c_id").val();
          var base_url=$("#base_url").val();
          var meeting_with_before_checkin_time_arr=[];        
          $('#meeting_with_before_checkin_time option:selected').each(function() {
              meeting_with_before_checkin_time_arr.push($(this).val());            
          });
          $('#meetingFrm').append('<input type="hidden" id="meeting_with_before_checkin_time_selected" name="meeting_with_before_checkin_time_selected" value="'+meeting_with_before_checkin_time_arr+'" />');
          $.ajax({
              url: base_url+"lead/meeting_add_edit_ajax",
              data: new FormData($('#meetingFrm')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function(xhr) {            
              $('#scheduleMeetingModal .modal-body').addClass('logo-loader');
              },
              complete: function(){              
                  $('#scheduleMeetingModal .modal-body').removeClass('logo-loader');
              },
              success: function(data){
              result = $.parseJSON(data);
              if(result.status=='success')
              {                     
                  swal({
                  title: 'Success',
                  text: result.msg,
                  type: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#DD6B55',
                  confirmButtonText: '',
                  closeOnConfirm: true
                  }, function() {
                      
                      $('#scheduleMeetingModal').modal('hide');
                      
                      if(id){ 
                          // rander_calendar_view();
                          if($('input[name="sent_invitation"]').is(':checked')){   
                              $("#MeetingDetailEditModal").css("display",'none');                                    
                              common_mail_send_modal('',result.txt,result.mail_subject,result.mail_form,result.mail_to);                    
                          } 
                          else{ 
                              open_meeting_detail_popup(id);
                          }
                      }
                      else{
                          load();
                          if($('input[name="sent_invitation"]').is(':checked')){          
                              // alert(result.txt+'/'+result.mail_subject+'/'+result.mail_form+'/'+result.mail_to) ; 
                              common_mail_send_modal('',result.txt,result.mail_subject,result.mail_form,result.mail_to); 

                          } 
                      }
                      
                                                      
                  });  
                          
              }   
              else if(result.status=='error')
              {
                  swal('Oops!',result.msg,'error')
                  
              }
              }
          });
      });
    // =================== MEETING =======================================================
   // ===================================================================================

   function call_report_view_details(date='',type='',filter_assigned_user='',lead_id='')
  {
      var base_url = $("#base_url").val(); 
      var data="date="+date+"&type="+type+"&filter_assigned_user="+filter_assigned_user+"&lead_id="+lead_id;
      // alert(data);
      $.ajax({
          url: base_url + "lead/rander_call_history_report_detail_ajax",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function(xhr) { 
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
          complete: function (){ 
              $.unblockUI();                   
          },
          success: function(res) {
              result = $.parseJSON(res);
              if(result.status=='success')
              {
                  var link_tmp='';
                  if($("#scr_assigned_user_default").val()=='1'){
                      link_tmp=' ( <a href="JavaScript:void(0)" class="download_call_log_details_csv" data-date="'+date+'" data-type="'+type+'"><i class="fa fa-cloud-download" aria-hidden="true"></i></a> )';
                  }
                  $("#call_history_report_detail_title").html(result.title+link_tmp);
                  $('#rander_call_history_report_detail_html').html(result.html);
                  $('#rander_call_history_report_detail_modal').modal({backdrop: 'static',keyboard: false});
              }
              else
              {
                  swal('Oops',result.msg,'error');
              }
          },
          error: function(response) {}
      });
  }

  
</script>
 
