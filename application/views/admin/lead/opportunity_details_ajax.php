<form class="" name="update_opportunity_form" id="update_opportunity_form" method="post" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/edit">
  <div class="width_full mt-20px">
    <div class="general_update_textera" style="width: 100%;">
      <div class="row group_creat_new_opprchunity" style="padding-right: 0;">

	<div class="form-group col-md-7 padding_right0">
	    <label for="inputEmail4">Quotation Title</label>
	      <input type="text" class="form-control" name="opportunity_title" id="opportunity_title_update_<?php echo $opportunity_id;?>" placeholder="Quotation Title" value="<?=$opportunity_data->opportunity_title;?>"/>

	      <div class="error_div text-danger" id="opportunity_title_update_<?php echo $opportunity_id;?>_error"></div>
	</div>
	
	<div class="form-group col-md-2 padding_right0">
	   <label for="inputState">Select Currency</label>
	       <select id="currency_type_update_<?php echo $opportunity_id;?>" class="form-control" name="currency_type_update" onchange="change_currency_update('currency_type_update_<?php echo $opportunity_id;?>','currency_name_prod_tot_update_<?php echo $opportunity_id;?>','currency_type_name_update_<?php echo $opportunity_id;?>','all_currency_update_<?php echo $opportunity_id;?>')">                  
		  <option value="">Select</option>
			<?php
			foreach($currency_list as $currency_data)
			{
			?>
				<option value="<?php echo $currency_data->id;?>"<?php if($currency_data->id==$opportunity_data->currency_type){?> selected="selected" <?php } ?>><?php echo $currency_data->code;?></option>
			<?php
			}
			?>                                  
	      </select>
	      <div class="error_div text-danger" id="currency_type_update_<?php echo $opportunity_id;?>_error"></div>
	</div>

	<div class="form-group col-md-3">
	  <button type="button" class="select_product select-btn btn btn-primary btn-round-shadow" onclick="GetProdLeadListUpdate('<?php echo $lead_id;?>','<?php echo $opportunity_id;?>')">Select Product(s)</button>

	  <div class="error_div text-danger" id="select_product_update_<?php echo $opportunity_id;?>_error"></div>
	</div>   

      </div>
					    
					    
<div class="product_item_value" style="padding-right: 11px;">
  <div id="product_del_update" class="alert alert-success no_display">Product Deleted Succesfully</div>
  <div id="product_list_update_<?php echo $opportunity_id;?>">
  <?php
  if($tmp_prod_list)
  {
  ?>            
  <table class="table table-bordered table-striped th_color">
    <thead>
      <tr>
	  <th class="text-center" style="width: 24px;">SL</th>
	  <th class="text-center" style="width: 249px;">Product Name</th>
	  <th class="text-center" style="width:150px;">Unit Sale Price</th>
	  <th class="text-center" style="width:76px;">Qtn.</th>
	  <th class="text-center" style="width:100px;">Discount (%)</th>
	  <th class="text-center" style="width: 76px;">GST (%)</th>
	  <th class="text-center" style="width:124px;">Total Sale Price</th>
	  <th class="text-center" style="width:32px;">&nbsp;</th>
      </tr> 
    </thead>
  <tbody>
  <?php 
  $temp_prod_id='';
  if($tmp_prod_list)
  {
		$x=1;
    $i=0;
    $sub_total=0;    
    foreach($tmp_prod_list as $output)
    {
                if($temp_prod_id==''){
                        $temp_prod_id=$output->product_id.',';
                }
                else{
                        $temp_prod_id=$temp_prod_id.$output->product_id.',';
                }
                		
                $item_gst_per= $output->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2);  
                $item_discount_per=$output->discount; 
                $item_price= $output->price;
                $item_qty=$output->unit;

                $item_total_amount=($item_price*$item_qty);
                $row_discount_amount=$item_total_amount*($item_discount_per/100);
                $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                $sub_total=$sub_total+$row_final_price; 
        ?>                        
        <tr id="product_row_<?=$i;?>" class="<?php echo ($i%2==0)?'event':'odd'; ?>">
	    <td><?php echo $x;?></td>
	    <td><?php echo $output->name;?></td>
	    <?php $price= $output->price*$output->unit;?>
	    <td>
	      <div class="form-group col-md-12 padding0">
	      <input type="text" class="form-control calculate_quotation_price_update double_digit" id="unit_price_update_<?=$i;?>" name="unit_price[]" value="<?php echo $output->price;?>" data-field="price" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" />
	      </div>
	    </td> 

	    <td>
	      <div class="form-group col-md-12 padding0">
		      <input type="text" class="form-control calculate_quotation_price_update only_natural_number_noFirstZero" name="unit[]" id="unit_update_<?=$i;?>" value="<?php echo $output->unit?>" data-field="unit" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>"/>
	      </div>
	    </td>			    
	    <td style="width: 76px;">	       
	      <input type="text" class="form-control calculate_quotation_price_update double_digit" id="disc_update_<?=$i;?>" name="disc[]" value="<?php echo $output->discount; ?>" data-field="discount" data-field="discount" data-id="<?php echo $output->id;?>" data-pid="<?php echo $output->product_id; ?>" />			 
	    </td>             

	    <td style="width: 76px;">
		<div class="form-group col-md-12 padding0">
		    <input type="text" class="form-control calculate_quotation_price_update double_digit" id="gst_update_<?=$i;?>" name="gst[]" value="<?=ceil($output->gst);?>" data-field="gst" data-id="<?php echo $output->id; ?>" data-pid="<?php echo $output->product_id; ?>" />
		</div>
	    </td> 

	    <td style="width:124px;" align="center">
		<div class="form-group col-md-12 padding0">
			<input type="hidden" name="currency_type[]" value="<?=$output->currency_type;?>" id="currency_type_update_<?=$i;?>" class="form-control"/>
		    <span class="" id="g_total_update_<?=$output->product_id;?>"><?=$row_final_price;?></span>
		</div>
	    </td>
		<td style="width:32px;">
	      <a href="JavaScript:void(0)" class="del_prod_update" data-id="<?=$output->id;?>" data-opportunityid="<?php echo $opportunity_id;?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>	      
	    </td>
	</tr>                    
      <?php                        
      $i++;
      $x++;
    }
  ?>
    
    
    <?php if(count($selected_additional_charges)){?>
      <?php 
        $j=0;
        foreach($selected_additional_charges AS $charge)
        {
                $i++; 
                $item_gst_per= $charge->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2);  
                $item_discount_per=$charge->discount; 
                $item_price= $charge->price;
                $item_qty=1;

                $item_total_amount=($item_price*$item_qty);
                $row_discount_amount=$item_total_amount*($item_discount_per/100);
                $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                $sub_total=$sub_total+$row_final_price; 
        ?>    
        <tr id="additional_charge_row_<?=$i;?>" class="<?php echo ($j%2==0)?'event':'odd'; ?>">
        	<td style="width: 24px;"><?php echo $i; ?></td>
        	<td style="width: 249px;"><?php echo $charge->additional_charge_name; ?></td>
        	<td style="width:150px;">
        	  <div class="form-group col-md-12 padding0">
        	  <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit" value="<?php echo $charge->price; ?>" data-field="price" data-id="<?php echo $charge->id; ?>" data-opportunityid="<?php echo $opportunity_id; ?>" />
        	  </div>
        	</td> 

        	<td style="width:76px;">
        	  <div class="form-group col-md-12 padding0">
        	      <input type="text" class="form-control" value="1" readonly="true" />
        	  </div>
        	</td>                            
        	<td style="width: 100px;">    
        	  <input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_discount" value="<?php echo $charge->discount; ?>"  data-field="discount" data-id="<?php echo $charge->id; ?>" data-opportunityid="<?php echo $opportunity_id; ?>" />
        	</td>       

        	<td style="width: 76px;">
        	    <div class="form-group col-md-12 padding0">
        		<input type="text" class="form-control calculate_quotation_additional_charges_price_update double_digit additional_charges_gst" value="<?php echo $charge->gst; ?>" data-field="gst" data-id="<?php echo $charge->id; ?>" data-opportunityid="<?php echo $opportunity_id; ?>" />
        	    </div>
        	</td> 

        	<td style="width:124px;" align="center">
        	    <div class="form-group col-md-12 padding0">	      
        		<span class="" id="row_total_additional_price_update_<?php echo $charge->id; ?>"><?php echo $row_final_price; ?></span>
        	    </div>
        	</td>
        	<td style="width:32px;">    
            <a href="JavaScript:void(0)" class="del_additional_charges_update" data-id="<?php echo $charge->id;?>" data-opportunityid="<?php echo $opportunity_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

        	 
        	</td>
        </tr>
      <?php $j++; } ?>
    <?php } ?>
	
      
     
    
    
    <tr>        
	<td colspan="6" style="text-align:right;" class="back_border_total">Total Deal Value</td>
	<td colspan="2" class="back_border_total">
	  <!-- <span id="currency_name_prod_tot_update_<?php echo $opportunity_id;?>"><?=$currency_name;?></span> -->
	  <span id="sub_total_update_<?php echo $opportunity_id;?>"><?php echo number_format($sub_total,2);?></span>
	  <input type="hidden" name="deal_value" id="deal_value" value="<?=number_format($sub_total,2);?>">
      </td>
    </tr>    
  <?php         
	}
	else
	{
	?>                                              
  <tr>
    <td colspan="6" style="text-align:right;" class="back_border_total">
    No products found</td>
  </tr>
  <?php
	}
  ?> 
  </tbody> 
  </table>
<?php
}
?>   

    
</div>  
</div>
<div class="row">
  <div class="col-md-12"><h4 class="text-primary"><a href="JavaScript:void(0);" class="add_additional_charges" data-oppid="<?php echo $opportunity_id;?>" style="font-size: 17px; color: #0954de;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Additional Charges </a></h4> </div> 
</div>                                          
     
  <?php
  if($tmp_prod_list)
  {
    $quoteurl=base_url().$this->session->userdata['admin_session_data']['lms_url']."/opportunity/generate/".$opportunity_data->id;
  ?>            
      <a class="generate_opportunity btn btn-primary btn-round-shadow" href="javascript:;" onclick="generate_quotation('<?=$quoteurl?>','<?php echo $opportunity_id;?>')">Generate Quotation PDF</a>
						 
  <?php
  }
  ?>
					  
    <button class="pull-right btn btn-primary btn-round-shadow" type="button" onclick="submit_opportunity(<?php echo $opportunity_id;?>)">Update </button>
</div>
</div>

    <input type="hidden" id="opportunity_id" name="opportunity_id" value="<?php echo $opportunity_id;?>" />
    <input type="hidden" id="lead_id_update" name="lead_id_update" value="<?php echo $lead_id;?>" />
    <input type="hidden" name="selected_prod_id" id="selected_prod_id_update_<?php echo $opportunity_id;?>" vale="<?=$temp_prod_id;?>" />
</form>                 
<script type="text/javascript">
 // $( function() {   
 //    $( "#datepicker3" ).datepicker();
 //  } );
 $(document).ready(function(){

    var currency_type=$("#currency_type_update_<?php echo $opportunity_id; ?>").val();
    if(currency_type!=1)
    {
      $("[name='gst[]']").attr("readonly",true);
      $(".additional_charges_gst").attr("readonly",true);
    }
    else
    {
      $("[name='gst[]']").attr("readonly",false);
      $(".additional_charges_gst").attr("readonly",false);
    }


    $("body").on("click",".add_additional_charges",function(e){
      	var base_url=$("#base_url").val();
      	var opp_id=$(this).attr('data-oppid');
      	$.ajax({
      	    url: base_url+"opportunity/get_additional_charges_checkbox_view_ajax",
      	    type: "POST",
      	    data: {"opp_id":opp_id},       
      	    async:true,     
      	    success: function (response) 
      	    {
      		$('#additional_charges_list_body').html(response);  
      		$('#additional_charges_list_modal').modal({backdrop: 'static',keyboard: false});
      	    },
      	    error: function () 
      	    {
      		//$.unblockUI();
      		//alert('Something went wrong there');
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


        // ===============================================
        // VALIDATION SCRIPT
        $("[name='disc[]'],[name='gst[]'],.additional_charges_gst,.additional_charges_discount").keyup(function(e) {
			if($(this).val()>100)
			{
				$(this).val(0);
				return false;
			}
        });

        $(".double_digit").keydown(function(e) {
                debugger;
                var charCode = e.keyCode;
                if (charCode != 8) {
                    //alert($(this).val());
                    if (!$.isNumeric($(this).val()+e.key)) {
                        return false;
                    }
                }
                return true;
        });


        // Namutal number and first letter not zero
        $('.only_natural_number_noFirstZero').keyup(function(e)
        { 
        var val = $(this).val()
        var reg = /^0/gi;
        if (val.match(reg)) {
          $(this).val(val.replace(reg, ""));
          // alert("Please phone number first character bla blaa not 0!");
          // $(this).mask("999 999-9999");
        }
        else
        {
          if (/\D/g.test(this.value))
          {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
          }
        }          
        });
        // Namutal number and first letter not zero
        


        // VALIDATION SCRIPT
        // ===============================================
    
 });

function add_additional_charges(opp_id)
{  
    //var total=$('input[type="checkbox"]:checked').length;
    var selected_id_array = $.map($('input[name="select[]"]:checked'), function(c){return c.value; });

    var base_url=$("#base_url").val();
    if(selected_id_array.length>0)
    {
	//var prod_id=document.getElementById('selected_prod_id_update_'+opp_id).value;       
	var additional_charges = selected_id_array.toString();
	var opportunity_id=opp_id;        

	$.ajax({
	      url: base_url+"opportunity/selected_additional_charges_added_ajax",
	      type: "POST",
	      data: {'additional_charges':additional_charges,'opportunity_id':opportunity_id},       
	      async:true,     
	      success: function (data) 
	      {
		result = $.parseJSON(data);
		//alert(result.msg);
		$("#product_list_update_"+opportunity_id).html(result.html);
		$('#additional_charges_list_modal').modal('toggle');
	      },
	      error: function () 
	      {
		    //$.unblockUI();
		    //alert('Something went wrong there');
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
    else
    {
	$('#err_prod').show();
    }
} 
</script>     