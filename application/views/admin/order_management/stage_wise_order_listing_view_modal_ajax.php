
<table class="table custom-table" id="">
    <thead>
    <tr>
        <tr>
        <th scope="col" class="om_sort_order--" data-field="t1.pro_forma_no" data-orderby="asc">Order ID</th>
        <th>Date</th>
        <th>Company</th>
        <th>Stage</th>
        <th>Priority</th>
        <th>Expt. Delivery</th>
        <th>Action</th>
        </tr>
    </tr>
    </thead>
    <tbody id="om_list_tcontent"></tbody>
</table>
<div class="row">
    <div id="om_page_record_count_info_div" class="col-md-6 text-left ffff"></div>
    <div id="om_page_div" style="" class="col-md-6 text-right custom-pagination"></div>
</div>
<input type="hidden" id="om_page_number" value="1">
<input type="hidden" id="om_stage_id" value="<?php echo $stage_id; ?>">
<script>
    load_om_list();
    $("body").on("click",".om_sort_order",function(e){
		// var tmp_field=$(this).attr('data-field');
		// var curr_orderby=$(this).attr('data-orderby');
		// var new_orderby=(curr_orderby=='asc')?'desc':'asc';
		// $(this).attr('data-orderby',new_orderby);
		// $(".sort_order").removeClass('asc');
		// $(".sort_order").removeClass('desc');
		// $(this).addClass(curr_orderby);
		// load_om_list(1);
		
	});
    $(document).on('click', '.om_myclass', function (e) { 
        e.preventDefault();
        var vt=($(this).attr('data-viewtype')=='om_grid')?'om_grid':'om_list';
        var str = $(this).attr('href'); 
        var res = str.split("/");
        var cur_page = res[1];
        $("#om_page_number").val(cur_page);        
        if(cur_page) {              
            load_om_list(cur_page);
        }
        else {
            load_om_list();
        }
    });
    function load_om_list()
    {
        var base_URL=$("#base_url").val();  
        var view_type=$("#om_view_type").val();
        var page=$("#om_page_number").val();  
        var stage_id=$("#om_stage_id").val();  
        var data = "view_type="+view_type+"&page="+page+"&stage_id="+stage_id;
        //alert(data); //return false;
        $.ajax({
            url: base_URL+"order_management/rander_orders_list_ajax/"+page,
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
            beforeSend: function( xhr ) {                
                addLoader('#om_list_tcontent');                
            },
            success:function(res){ 
                result = $.parseJSON(res);  
                // alert(result.html)
                $("#om_list_tcontent").html(result.html);    
                $("#om_page_div").html(result.page);
			    $("#om_page_record_count_info_div").html(result.page_record_count_info);            
            },
            complete: function(){
                removeLoader();               
            },
            error: function(response) {
                //alert('Error'+response.table);
            }
        });
    }
</script>