
<div class="card process-sec">
    <!-- <h5 class="lead_board">Download From Gmail</h5> -->
    <!-- <span id="selected_filter_div" class="lead_filter_div"></span> -->
    <div class="card-block">                
      <div class="table-full-holder-new" style="width: 100%">
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
                      <a href="#" class="op"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                      
                    </li>

                  </ul>
                </div>
                <div class="other-holder float-left">
                  <ul class="action-ul">
                    <li><a href="#"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
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
                  <ul class="action-ul dropdown_new no-arrow">
                    <li>
                      <div class="dropdown over">
                        <a href="#" class="dropdown-toggle op-full" id="dropdownMenuButtonNew" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          1-50 of 16,432
                        </a>
                        <div class="dropdown-menu left" aria-labelledby="dropdownMenuButtonNew">
                          <a class="dropdown-item" href="#">Newest</a>
                          <a class="dropdown-item" href="#">Oldest</a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a href="#" class="op"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    </li>
                    <li>
                      <a href="#" class="op"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    </li>
                  </ul>
                </div>

              </th>
            </tr>
          </thead>
          <tbody>
            <?php if(count($rows)){ ?>
              <?php foreach($rows as $row){ ?>
                <tr class="unread">
                  <td class="text-left">
                    <label class="check-box-sec">
                        <input type="checkbox" value="417" class="" name="customer_id">
                          <span class="checkmark"></span>
                      </label>
                  </td>
                  <td class="text-left">
                    <strong class="tooltip not-ab" data-id="0"><?php echo $row['h_from_personal']; ?></strong>
                  </td>
                  <td class="text-left">
                    <div class="mail-subject">
                      <strong><?php echo $row['subject']; ?></strong> - <?php echo substr(strip_tags($row['message']),0,100); ?>
                    </div>
                    <div class="mail-action">
                      <ul class="action-ul">
                        <li><a href="#"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                    <div class="mail-time">
                      <?php echo date_db_format_to_display_format(date('Y-m-d',$row['udate'])); ?>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div> 
</div>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script> -->
<!-- <script src="<?=base_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js" type="text/javascript"></script> -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tooltipster.bundle.min.css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-shadow.min.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tooltipster.bundle.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
      //$('.tooltip').tooltipster();
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
      $('input:checkbox[name=customer_id]').prop('checked', $(this).prop("checked"));
    });
    

    $("body").on("click",".cAll",function(e){
      e.preventDefault();
      showOption();
      $('input:checkbox[name=customer_id], .user_all_checkbox').prop('checked',true);
    });
    $("body").on("click",".uAll",function(e){
      e.preventDefault();
      hideOption();
      $('.dropdown_new .check-box-sec').removeClass('same-checked');
      $('input:checkbox[name=customer_id], .user_all_checkbox').prop('checked',false);
    });
    //////////////////////////////
    //cRead
    $("body").on("click",".cRead",function(e){
      e.preventDefault();
      //hideOption();
      $('input:checkbox[name=customer_id]').prop('checked',false);
      var cc = 0;
      $('table.dataTable.new-table-style tbody > tr').each(function( index ) {
        console.log( index + ": " + $( this ).text() );

        if ($(this).hasClass('unread')) {
          cc++;
          $(this).find('input:checkbox[name=customer_id]').prop('checked',true);
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
      $('input:checkbox[name=customer_id]').prop('checked',false);
      var cc = 0;
      $('table.dataTable.new-table-style tbody > tr').each(function( index ) {
        console.log( index + ": " + $( this ).text() );

        if ($(this).hasClass('read')) {
          cc++;
          $(this).find('input:checkbox[name=customer_id]').prop('checked',true);
        }
        
      });
      if(cc > 0){
        showOption();
        $('.user_all_checkbox').prop('checked',false);
        $('.dropdown_new .check-box-sec').addClass('same-checked');
      }
    });
    /////////////////////////////
    $("input:checkbox[name=customer_id]").change(function () {
        var ddc = $('input:checkbox[name=customer_id]:checked').length;
        var dd = $('input:checkbox[name=customer_id]').length;
        //alert('ddc: '+ddc+', dd: '+dd)
        if (ddc > 0) {
          //$('#dropdownMenuUser').html('None');
          showOption();
          if (ddc == dd){
            $('.user_all_checkbox').prop('checked',true);
            $('.dropdown_new .check-box-sec').removeClass('same-checked');
          }else{
            $('.user_all_checkbox').prop('checked',false);
            $('.dropdown_new .check-box-sec').addClass('same-checked');
          }
          
          
        }else {
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

