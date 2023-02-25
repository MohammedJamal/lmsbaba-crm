<div class="w-100 mt-15" id="selected_product_list_div" style="display:none;">
	
     	
	  
</div>
<div class="w-100 mt-15" id="selected_product_ids_div_outer" style="display:none;">
	<div class="form-group row" id="selected_product_ids_div"></div>	
	<div class="form-group row">
		<div class="col-md-12 ">
			<button type="button" class="btn btn-primary btn-round-shadow pull-right" id="search_product_checked_proceed_confirm">Save & Proceed</button>
			<input type="hidden" id="selected_p_ids" value="">
		</div>
	</div>
</div>

<script type="text/javascript">
	
  $("body").on("click", $("input[type='checkbox'][name='select_product[]']"), function(e) {
        if ($("input[type='checkbox'][name='select_product[]']:checked").length == 0) {

              $("#search_product_checked_1").css("display","none");
          $("#search_product_checked_2").css("display","none");
        }
        else
        {
          $("#search_product_checked_1").css("display","block");
          $("#search_product_checked_2").css("display","block");  
        }
    });


    $("body").on("click","#search_product_by_name,#search_product_by_group",function(e){
    var searchtype=$(this).attr("data-searchtype");
    var search_p_name=$("#search_p_name").val();
    var search_p_group=$("#search_p_group").val();
    var search_p_category=$("#search_p_category").val();
    var selected_p_ids=$("#selected_p_ids").val();
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "lead/search_product_list_view_ajax",
        type: "POST",
        data: {
              "searchtype":searchtype,
              "search_p_name":search_p_name,
              "search_p_group":search_p_group,
              "search_p_category":search_p_category,
              "selected_p_ids":selected_p_ids
            },
        async: true,
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
        success: function(data) {
          result = $.parseJSON(data);
          
          $("#selected_product_list_div").css("display","block");    
          $('#selected_product_list_div').html(result.html);
          var tmp_btn_name=$("#search_add_btn_class").val();
          $("#search_product_checked_proceed_confirm").addClass(tmp_btn_name);

          
          
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


$("body").on("click",".search_product_checked",function(e)
{
      e.preventDefault();          
      var product_ids=$.map($('input[name="select_product[]"]:checked'), function(c) {
            return c.value;
      });
      
      //if (product_ids.length>0 && p_selected_error_flag==1){
        //p_selected_error_flag=0;          
      //}  

      // if(p_selected_error_flag==0)
      // {
      // }
      // else
      // {

      //   swal({
      //       title: "Warning",
      //       text: 'Please seleact a product',
      //       type: "warning",
      //       confirmButtonText: "OK",
      //     });
      // }
      existing_product_ids=[];
      var base_url = $("#base_url").val();
      if($("#selected_p_ids").val()){
        var existing_product_ids=$("#selected_p_ids").val().split(",");
      }
      var c = existing_product_ids.concat(product_ids);
      var new_p_ids=c.filter((item, pos) => c.indexOf(item) === pos);      
      var lead_id=$("#search_product_lead_id").val();
      $("#selected_p_ids").val(new_p_ids);

      
      var add_html='';
      $.each($("input[name='select_product[]']:checked"), function(){
          var name_tmp=$(this).attr('data-name');
          var id_tmp=$(this).val();         
          add_html +='<div class="col-auto" id="checked_prod_div_'+id_tmp+'"><div class="search-item"><a href="javaScript:void(0)" class="search-remove unchecked_product" data-id="'+id_tmp+'"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a><span>'+name_tmp+'</span></div></div>';
          $("#p_tr_"+id_tmp).remove();
      });
      $("#selected_product_ids_div").append(add_html);
      
      if(new_p_ids.length){
        $("#selected_product_ids_div_outer").css("display","block");
      }
      else{
        $("#selected_product_ids_div_outer").css("display","none");
      }
      
      $("#selectAllSearchedProducts").attr("checked", false);
      $("#selected_product_list_div").css("display","none");
});




  $("body").on("click",".unchecked_product",function(e){
      var del_id=$(this).attr('data-id');     
      if($("#selected_p_ids").val())
      {
        var existing_product_ids=$("#selected_p_ids").val().split(",");
        var index = existing_product_ids.indexOf(del_id);
        if (index !== -1) {
          existing_product_ids.splice(index, 1);
        }
        $("#selected_p_ids").val(existing_product_ids);
        $("#checked_prod_div_"+del_id).remove();
      }

      if($("#selected_p_ids").val()){
        $("#selected_product_ids_div_outer").css("display","block");
      }
      else{
        $("#selected_product_ids_div_outer").css("display","none");
      }
  });


</script>