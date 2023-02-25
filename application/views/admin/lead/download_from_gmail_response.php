<?php $this->load->view('include/header');?>

<!-- content panel -->
<div class="main-panel">
  <!-- top header -->
  <div class="min_height_dashboard"></div>
    <!-- main area -->
    <div class="main-content lead_manage_page">
      <div class="content-view">          
        <div class="row m-b-1">            
          <div class="col-sm-4 pr-0">
            <div class="bg_white back_line">  
              <h4>Manage Leads <img src="<?php echo assets_url(); ?>images/message.png"/></h4> 
            </div>
          </div>
          <div class="col-sm-8 pleft_0">
            <div class="bg_white_filt">
              <ul class="filter_ul">
                <li>
                  <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_from_gmail" class="new_filter_btn" >
                    <span class="bg_span"><img src="<?php echo assets_url(); ?>images/filter_new.png"/></span>
                    Gmail
                  </a>
                </li>
              </ul>
            </div>
          </div>          
    </div>        
 
<div class="card process-sec">
    <h5 class="lead_board">Buyer's Responses</h5>
    <span id="selected_filter_div" class="lead_filter_div"></span>
    <div class="card-block">                
      <div class="table-full-holder-new" style="width: 100%">

        <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
          <thead>
            <tr>          
              <th class="sort_order desc" data-field="lead.id" data-orderby="asc" width="150">Response Date</th>
              <th class="text-center sort_order"  data-field="lead.create_date" data-orderby="asc" width="200">Mail Subject</th>
              <th class="text-center sort_order" data-field="lead.title" data-orderby="asc">Sender </th>
              <th class="text-center sort_order" data-field="countries.name" data-orderby="asc" >Assign To</th>
              <th class="text-center sort_order" data-field="user.name" data-orderby="asc">Lead ID</th>
              <th class="text-center sort_order" width="110" data-field="lead.modify_date" data-orderby="asc">Company</th>              
              <th class="text-center" width="100">Action</th>
            </tr>
          </thead>
          <tbody id="tcontent" class="t-contant-img"></tbody>
        </table>
        <div class="row">
          <div id="page_record_count_info" class="col-md-6 text-left ffff"></div>
          <div id="page" style="" class="col-md-6 text-right custom-pagination"></div>
        </div>

        <?php /* ?>
        <table id="table" class="table new-table-style dataTable table-expand lead-mail-table" style="width: 100%">
          <thead>
            <tr>
              <th class="text-left" colspan="3">
                <div class="dropdown_new float-left">
                   <a href="#" class="all-secondary">
                   <label class="check-box-sec">
                   <input type="checkbox" value="all" name="user_all" class="user_all_checkbox">
                   <span class="checkmark"></span>
                   </label>
                   </a>
                   <div class="dropdown">
                      <button class="btn-all dropdown-toggle auto-width" type="button" id="dropdownMenuUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      
                      </button>
                      <div class="dropdown-menu left" aria-labelledby="dropdownMenuUser">
                         <a class="dropdown-item cAll" href="#">All</a>
                         <a class="dropdown-item uAll" href="#">None</a>
                         <a class="dropdown-item cRead" href="#">Read</a>
                         <a class="dropdown-item uRead" href="#">Unread</a>
                      </div>
                   </div>
                </div>  
                <div class="refresh-holder float-left">                  
                  <ul class="action-ul">
                    <li>
                      <a href="javascript:void(0);" class="op refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    </li>
                  </ul>
                </div>
                <div class="other-holder float-left">
                  <ul class="action-ul">
                    <li><a href="javascript:void(0);" class="selected_delete"><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" title="Delete"></i></a></li>
                    <li><a href="javascript:void(0);" class="" id="selected_seen_status_change" data-toggle="tooltip" data-curstatus=""></a></li>
                  </ul>
                </div> 
                <div class="dd-holder float-left">
                  
                  <ul class="action-ul dropdown_new no-arrow">
                    
                    <li>
                      <div class="dropdown over">
                        <a href="#" class="dropdown-toggle op" id="dropdownMenuButtonMail" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                        <div class="dropdown-menu left" aria-labelledby="dropdownMenuButtonMail">
                          <a class="dropdown-item" href="#">Mark all as read</a>
                          <div class="dropdown-divider"></div>
                          <div class="menu-info">Select messages to see more actions</div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                
                <div class="page-holder float-right">
                  <ul class="action-ul dropdown_new no-arrow ">
                    <li>
                      <div class="dropdown over">
                        <a href="#" class="dropdown-toggle op-full" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="page_record_count_info"></a>
                        <div class="dropdown-menu left" aria-labelledby="">
                          <a class="dropdown-item" href="#">Newest</a>
                          <a class="dropdown-item" href="#">Oldest</a>
                        </div>
                      </div>
                    </li>                    
                  </ul>
                  <div class="" id="page"></div>
                </div>
                

              </th>
            </tr>
          </thead>
          <tbody id="tcontent"></tbody>
        </table>
        <?php */ ?>
        <input type="hidden" id="current_page_no" value="1">
      </div>
    </div> 
</div>
  
<!-- bottom footer -->
<?php $this->load->view('include/footer');?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script src="<?=base_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tooltipster.bundle.min.css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-shadow.min.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tooltipster.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<script src="<?php echo base_url();?>assets/js/custom/lead/get_download_from_gmail_response.js"></script>
</div>
<!-- /main area -->
</div>
<!-- /content panel -->
</div>


<!-- Modal -->
<div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div id="attach-holder">ffff</div>
      </div>
      
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
      //$('.tooltip').tooltipster();
      //attachmentModal
      $("body").on("click",".attachment_download",function(e){
        //e.preventDefault();
        var file_path=$(this).attr('data-content');
        var base_url=$("#base_url").val();        
        window.location.href=base_url+"lead/download_gmail_attachment/"+file_path;
        // var getit = $(this).attr('data-content');
        // $('#attachmentModal #attach-holder').html('<img src="'+getit+'">');
        // $('#attachmentModal').modal('show');
      });
      //tooltip-new
      $('.tooltip-new').tooltipster({
        theme: 'tooltipster-shadow'
      });
      $('.tooltip').tooltipster({
        content: 'Loading...',
        updateAnimation: false,
        theme: 'tooltipster-shadow',
        //trigger: 'click',
        functionBefore: function(instance, helper) {
          
          var $origin = $(helper.origin);
          
          if ($origin.data('ajax') !== 'cached') {
            var hh = `<div class="pop-outer">
                      <div class="pop-top">
                        <figure></figure>
                        <figcaption>
                          <h2>Flipkart</h2>
                          <p>info@flipkart.com</p>
                        </figcaption>
                      </div>
                    </div>`;
            instance.content($(hh));
            
            $origin.data('ajax', 'cached');
          }
        },
        functionAfter: function(instance) {
          //alert('The tooltip has closed!');
        }
    });
    /////////////////////////////////////////////////////
    $(".user_all_checkbox").change(function () {
      $('.dropdown_new .check-box-sec').removeClass('same-checked');
      //alert(1);
      if($(this).prop("checked") == true){
        //$('#dropdownMenuUser').html('All');
        showOption()
      }else{
        //$('#dropdownMenuUser').html('None');
        hideOption()
      }
      $('input:checkbox[name=gmail_overview_id]').prop('checked', $(this).prop("checked"));
    });
    

    $("body").on("click",".cAll",function(e){
      e.preventDefault();
      showOption();
      $('input:checkbox[name=gmail_overview_id], .user_all_checkbox').prop('checked',true);
    });
    $("body").on("click",".uAll",function(e){
      e.preventDefault();
      hideOption();
      $('.dropdown_new .check-box-sec').removeClass('same-checked');
      $('input:checkbox[name=gmail_overview_id], .user_all_checkbox').prop('checked',false);
    });
    //////////////////////////////
    //cRead
    $("body").on("click",".cRead",function(e){
      e.preventDefault();
      //hideOption();
      $('input:checkbox[name=gmail_overview_id]').prop('checked',false);
      var cc = 0;
      $('table.dataTable.new-table-style tbody > tr').each(function( index ) {
        console.log( index + ": " + $( this ).text() );

        if ($(this).hasClass('unread')) {
          cc++;
          $(this).find('input:checkbox[name=gmail_overview_id]').prop('checked',true);
        }
        
      });
      if(cc > 0){
        showOption();
        $('.user_all_checkbox').prop('checked',false);
        $('.dropdown_new .check-box-sec').addClass('same-checked');
      }
    });
    $("body").on("click",".uRead",function(e){
      e.preventDefault();
      //hideOption();
      $('input:checkbox[name=gmail_overview_id]').prop('checked',false);
      var cc = 0;
      $('table.dataTable.new-table-style tbody > tr').each(function( index ) {
        console.log( index + ": " + $( this ).text() );

        if ($(this).hasClass('read')) {
          cc++;
          $(this).find('input:checkbox[name=gmail_overview_id]').prop('checked',true);
        }
        
      });
      if(cc > 0){
        showOption();
        $('.user_all_checkbox').prop('checked',false);
        $('.dropdown_new .check-box-sec').addClass('same-checked');
      }
    });
    /////////////////////////////
    
    //$("input:checkbox[name=gmail_overview_id]").change(function () {
    $("body").on("click","input:checkbox[name=gmail_overview_id]",function(e){
        var base_url_root=$("#base_url_root").val();
        var ddc = $('input:checkbox[name=gmail_overview_id]:checked').length;
        var dd = $('input:checkbox[name=gmail_overview_id]').length;
        
        if (ddc > 0) 
        {
          //$('#dropdownMenuUser').html('None');
          showOption();
          if (ddc == dd){
            $('.user_all_checkbox').prop('checked',true);
            $('.dropdown_new .check-box-sec').removeClass('same-checked');
          }else{
            $('.user_all_checkbox').prop('checked',false);
            $('.dropdown_new .check-box-sec').addClass('same-checked');
          }
          
          var flag_read=0;
          $("input:checkbox[name=gmail_overview_id]:checked").each(function(){
              
              if($(this).attr('data-currstatus')=='N'){
                flag_read++;
              }     
          });
          
          if(flag_read>0)
          {
            var title='Mark as read';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/drafts_white.png">');
             $("#selected_seen_status_change").attr('data-curstatus','N');
          }
          else
          {
            var title='Mark as unread';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/mark_as_unread_white.png">');
            $("#selected_seen_status_change").attr('data-curstatus','Y');
          }
          $("#selected_seen_status_change").attr('title',title);
          
        }
        else 
        {
          hideOption()
          $('.user_all_checkbox').prop('checked',false);
          $('.dropdown_new .check-box-sec').removeClass('same-checked');
          
        }
    });
    function showOption(){
      $('table.dataTable.new-table-style .float-left.refresh-holder').hide();
      $('table.dataTable.new-table-style .float-left.other-holder').show();
    }
    function hideOption(){
      $('table.dataTable.new-table-style .float-left.refresh-holder').show();
      $('table.dataTable.new-table-style .float-left.other-holder').hide();
    }
    /////////////////////////////////////////////////////
    var posHeader = new Array();
var head, tableOffset, tableHeight, posHeaderLen;

function appendHead_bbb() {
  if ($(".lead-mail-table").hasClass("table-fixed")) {
      return;
    } else {
      $(".lead-mail-table").addClass("table-fixed");
      $(".lead-mail-table thead").addClass('header-copy header-fixed');
    }
}
function appendHead() {
  //return;
  $("#table").each(function(index, element) {
    //alert(element.id)

    head = "#" + element.id + " thead";
    tableHeight = $(element).height();
    tableOffset = $(element).offset();

    posHeader[3 * index] = tableOffset.top;
    posHeader[3 * index + 1] = element.id;
    posHeader[3 * index + 2] = tableHeight + posHeader[3 * index];
    var tw =$(element).width();
    /* Add a class to the table to identify the processed table */
    if ($(element).hasClass("table-fixed")) {
      return;
    } else {
      $("#" + element.id).addClass("table-fixed");
    }
    $("#" + element.id + " thead").addClass('header-copy header-fixed');
    //return;
    var headerCopy = $(".header-copy");
    //$("#" + element.id + " thead").clone().addClass('header-copy header-fixed').stop().appendTo("#" + element.id);

    var attributes = $("#" + element.id + " thead").prop("attributes");

    $.each(attributes, function() {
      headerCopy.attr(this.name, this.value);
    });
    var style = [];
    $(element).find('thead > tr:first > th').each(function(i, h) {
      return style.push($(h).width());
    });
    $.each(style, function(i, w) {
      return $(element).find('thead > tr > th:eq(' + i + '), thead.header-copy > tr > th:eq(' + i + ')').css({
        width: w
      });
    });
    //alert(tw);
    $(element).find('thead.header-copy').css({
      margin: '0 auto',
      width: $(element).width(),
      top: tableOffset
    });
    $(element).find('thead.header-copy th').css({
      width: $(element).width()
    });

    posHeaderLen = parseInt(posHeader.length / 3);

  });

}
var xpos = 0;
$(window).scroll(function() {
  scrollAmount = $(window).scrollTop()+56;
  
  for (j = 0; j <= posHeaderLen; j++) {
    posizione = j * 3;

    if (posHeader[posizione] < scrollAmount) {
      //siamo all'interno della tabella
      flag = true;
      //console.log(posHeader[2 + posizione]);
      if (posHeader[2 + posizione] > scrollAmount) {
        //siamo ancora all'interno della tabella
        
        xpos = $("#" + posHeader[1 + posizione]).offset().left;
        console.log(1+': '+xpos);
        $("#" + posHeader[1 + posizione]).find("thead").css('left', xpos);
        $("#" + posHeader[1 + posizione]).addClass("visible");

      } else {
        // siamo arrivati alla fine della tabella
        flag = false;
        //xpos = 0;
        console.log(2+': '+xpos);
        $("#" + posHeader[1 + posizione]).find("thead").css('left', 0);
        $("#" + posHeader[1 + posizione]).removeClass("visible");
      }
    } else {

      flag = false;
      //xpos = 0;
      console.log(3+': '+xpos);
      $("#" + posHeader[1 + posizione]).find("thead").css('left', 0);
      $("#" + posHeader[1 + posizione]).removeClass("visible");
    }
  }
  //var x = $(".table-full-holder-new").offset();
  console.log("xpos: " + xpos);
  // if(flag == true){
  //   xpos = $("#" + posHeader[1 + posizione]).offset().left;
  // }else{
  //   xpos = 0;
  // }
  orizScroll = (-1) * $(window).scrollLeft();

  //$(".header-copy").css('left', xpos);

});

$(window).resize(function() {
  for (k = 0; k < posHeaderLen; k++) {
    posizione = k * 3;
    tableId = "#" + posHeader[1 + posizione];

    var headerCopy = $(tableId + " .header-copy");
    var attributes = $(tableId + " thead").prop("attributes");

    $.each(attributes, function() {
      headerCopy.attr(this.name, this.value);
    });
    var style = [];
    $(tableId).find('thead > tr:first > th').each(function(i, h) {
      return style.push($(h).width());
    });
    $.each(style, function(i, w) {
      return $(tableId).find('thead > tr > th:eq(' + i + '), thead.header-copy > tr > th:eq(' + i + ')').css({
        width: w
      });
    });
    $(tableId).find('thead.header-copy').css({
      margin: '0 auto',
      width: $(tableId).width(),
      top: tableOffset
    });
  }
});
/*
$(document).bind("DOMSubtreeModified", function(){
  appendHead();
});*/

appendHead();
    /////////////////////////////////////////////////////
  });
</script>
</body>
</html>


