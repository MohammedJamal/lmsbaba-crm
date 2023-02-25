
<form action="" method="post" name="bulk_send_to_buyer_frm" id="bulk_send_to_buyer_frm">
	<input type="hidden" name="opportunity_id_str" id="opportunity_id_str" value="<?php echo $opportunity_id_str; ?>">
	<input type="hidden" name="quotation_id_str" id="quotation_id_str" value="<?php echo $quotation_id_str; ?>">
	<div class="row" >
		<div class="col-md-12">		
			<div class="alert alert-danger" id="send_to_buyer_error" style="display: none">
			  <strong>Oops!</strong> <span id="error_msg"></span>
			</div>
			<div class="alert alert-success" id="send_to_buyer_success" style="display: none">
			  <strong>Success!</strong> <span id="success_msg"></span>
			</div>
		</div>

		<div class="col-md-12 mb-15">
			<div class="row">
				<div class="col-md-2 text-label-new">To:</div>
				<div class="col-md-10">
					<div class="to-name">						
						<?php 
						
						$to_email_str=implode(", ", $send_to_email_arr);
						// $to_email_str='';
						// if(count($send_to_email_arr))
						// {
						// 	$i=1;
						// 	foreach($send_to_email_arr AS $email)
						// 	{
						// 		$to_email_str .=$i.') '.$email.', ';
						// 		if($i%2==0){
						// 			$to_email_str .='<br>';
						// 		}
						// 		$i++;
						// 	}
						// 	$to_email_str=rtrim($to_email_str,', ');
						// } 
						echo $send_to_email_arr[0];
						if(count($send_to_email_arr)>1)
						{
							if(count($send_to_email_arr)==2){
								$rest_txt='other';
							}
							else{
								$rest_txt='others';
							}
							echo ' <a href="JavaScript:void(0)"  data-toemails="'.$to_email_str.'" id="bulk_quotation_email_list_popup" class="text-primary"><u>& '.(count($send_to_email_arr)-1).' '.$rest_txt.'</u></a>';
						}
						?>
					</div>
				</div>				
			</div>	
		</div>

			
		
		
		<?php if($curr_company['brochure_file']){ ?>
		<div class="col-md-12 mb-15">
			<div class="row">
				<div class="col-md-2 text-label-new"></div>
				<div class="col-md-10 ff big-ft">
					<label class="check-box-sec">
						<input type="checkbox" name="is_company_brochure_attached_in_quotation" id="is_company_brochure_attached_in_quotation" class=""  value="Y">
						<span class="checkmark"></span>
					</label>
					Attached Company Brochure.
				</div>
			</div>
		</div>
		
		<?php } ?>
		<div class="col-md-12 mb-15">
			<div class="row">
				<div class="col-md-2 text-label-new"></div>
				<div class="col-md-10 ff big-ft">
					<a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$first_opportunity_id.'/'.$first_quotation_id);?>" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> <span>View Quotation</span></a>
				</div>
			</div>
		</div>
		<input type="hidden" id="is_extermal_quote" value="<?php echo $is_extermal_quote; ?>">
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="" style="width: 100%;height: auto;display: inline-block; text-align: center;">
				<input type="hidden" name="opportunity_id" value="<?php echo $opportunity_id; ?>">
			<input type="hidden" name="quotation_id" value="<?php echo $quotation_id; ?>">
			<button class="btn btn-primary btn-round-shadow" id="bulk_send_to_buyer_confirm" data-opportunityid="<?php echo $opportunity_id; ?>" data-quotationid="<?php echo $quotation_id; ?>">Send Quotation</button>
			</div>
			
		</div>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		 $("body").on("click",".letter_update",function(e){
	        var quotation_id=$(this).attr('data-quotationid');
	        var updated_field_name=$(this).attr("id");
	        var updated_content=$(this).val();
	        //alert(quotation_id+'/'+updated_field_name+'/'+updated_content);
	        fn_update_quotation2(quotation_id,updated_field_name,updated_content);
	    });
	});
function fn_update_quotation2(quotation_id,updated_field_name,updated_content)
{	
	var base_url=$("#base_url").val();	
	//alert(base_url+' / '+quotation_id+' / '+updated_field_name+' / '+updated_content);return false;
    if(updated_field_name=='is_product_image_show_in_quotation')
    {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
    }

    if(updated_field_name=='is_product_brochure_attached_in_quotation')
    {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
    }

    if(updated_field_name=='is_company_brochure_attached_in_quotation')
    {
      if($("#"+updated_field_name).is(":checked")){
          updated_content='Y';
      } 
      else{
          updated_content='N';
      }
    }
  
    var data="quotation_id="+quotation_id+"&updated_field_name="+updated_field_name+"&updated_content="+encodeURIComponent(updated_content)
    // alert(data); return false;
	$.ajax({
          url: base_url+"opportunity/quotation_letter_field_update_ajax/",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {
              
          },
          success:function(res){ 
             result = $.parseJSON(res);
             if(result.status=='success')
             {
                // swal({
                //     title: 'Quation successfully updated',
                //     text: '',
                //     type: 'success',
                //     showCancelButton: false
                // }, function() {                    
                    
                // });
             }
             
          },
          complete: function(){
          
          },
          error: function(response) {
          }
    });
}
</script>
<style type="text/css">
	.select2-container .select2-selection{
		min-height: 28px;
		line-height: 24px!important;
    	height: 100%;
}

</style>