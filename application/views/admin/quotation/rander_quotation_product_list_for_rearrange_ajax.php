<ul class="list-group" id="quotation_product_rearrange">
    <?php                             
    if(count($prod_list))
    {
        foreach($prod_list as $output)
        {
        ?>    
        <li id="row_<?php echo $output->id; ?>" class="list-group-item">
            <div class="left-txt"><?php echo $output->product_name; ?> </div>
            <span class="badge wwww"><?php echo $output->price;?>&nbsp;<i><b>Per</b></i> &nbsp;<?php echo $output->unit?> <?php echo $output->unit_type; ?></span>
        </li>        
        <?php
        }
    }
    ?>
</ul>
<ul class="list-group" id="quotation_additional_rearrange">
    <?php
    if(count($selected_additional_charges))
    {
        foreach($selected_additional_charges AS $charge)
        {
        ?>
        <li id="row_<?php echo $charge->id; ?>" class="list-group-item">
            <div class="left-txt"><?php echo $charge->additional_charge_name; ?> </div>
            <span class="badge eeee"><?php echo $charge->price; ?></span>
        </li>        
        <?php
        }
    }
    ?>
</ul>
<input type="hidden" id="oid_tmp" value="<?php echo $opportunity_id; ?>" />
<input type="hidden" id="qid_tmp" value="<?php echo $quotation_id; ?>" />
<input type="hidden" id="lid_tmp" value="<?php echo $lead_id; ?>" />

<style>
#quotation_product_rearrange,#quotation_additional_rearrange{
    cursor: move;
}
</style>
<script>
$(document).ready(function(e){
    //alert(123)
    $( "#quotation_product_rearrange" ).sortable({
        containment: "#quotation_product_rearrange",    
        start: function(event, ui) {},
        stop: function(event, ui) {        
            $('#quotation_product_rearrange').addClass('logo-loader');
            var opportunity_id=<?php echo $opportunity_id; ?>;
            var quotation_id=<?php echo $quotation_id; ?>;            
            var new_sort = $("#quotation_product_rearrange").sortable("serialize", {key:'new_sort[]'});
            var base_url=$("#base_url").val();
            var data=new_sort; 
            $.ajax({
                    url: base_url+"opportunity/resort_quotation_product",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",  
                    async: true,                 
                    beforeSend: function( xhr ) { 
                        
                    },
                    complete: function(){
                        $('#quotation_product_rearrange').removeClass('logo-loader');
                    },
                    success: function(data){
                        result = $.parseJSON(data);
                    },
            }); 
        },
        change: function(event, ui) {},
        update: function (event, ui) {}
    });

    $( "#quotation_additional_rearrange" ).sortable({
        containment: "#quotation_additional_rearrange",
        start: function(event, ui) {},
        stop: function(event, ui) {        
            $('#quotation_additional_rearrange').addClass('logo-loader');
            var opportunity_id=<?php echo $opportunity_id; ?>;
            var quotation_id=<?php echo $quotation_id; ?>;    
            var new_sort = $("#quotation_additional_rearrange").sortable("serialize", {key:'new_sort[]'});
            var base_url=$("#base_url").val();
            var data=new_sort;
            $.ajax({
                    url: base_url+"opportunity/resort_quotation_additional_charges",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",    
                    async: true,                     
                    beforeSend: function( xhr ) {
                        
                    },
                    complete: function(){
                    $('#quotation_additional_rearrange').removeClass('logo-loader');
                    },
                    success: function(data){
                        result = $.parseJSON(data);
                    },
            });
        },
        change: function(event, ui) {},
        update: function (event, ui) {}
    });
});
    </script>