<div class="form-group">
<?php
if($cus_data)
{
	?>
	<span class="tag_row" style="color:#bc6100;">Following buyers found matching with above email id or mobile number</span>
	<?php
}
?>

</div>


<div id="lead_row" class="row form-group"> 
                         
                      <div class="position_relative">
                      <div id="div_disable" class=""></div>
                        <div class="table-responsive">
                          <table class="table table-bordered table-striped m-b-0 border_none_table">
                            <thead>
                              <tr>
                                <th>Lead ID</th>
                          <th>Email Matched</th>
                          <th>Mobile Matched</th>
                          <th>View Details</th>
                        </tr>
                            </thead>
                            
                            <tbody>
                              <?php
$i=2;
if($cus_data)
{
	
	foreach($cus_data as $data)
	{

	?>
                              <tr>
                                <td><label class="custom-control custom-radio">
                                  
                                  
                                  <input type="radio" name="tag_rad" value="<?php echo $data->lead_id?>" onclick="remove_attr(<?php echo $data->lead_id?>)" class="custom-control-input"/>
                                  <span class="custom-control-indicator"></span>
                                  <span class="custom-control-description"><?php echo $data->lead_id?></span>
                                  </label>                                 </td>
                          <td>
                            <?php
                        if($post_email==$data->email)
                        {
							echo 'Yes';
						}
						else
						{
							echo 'No';
						}
                        ?>                            </td>
                          <td><?php
                        if($post_mobile==$data->mobile)
                        {
							echo 'Yes';
						}
						else
						{
							echo 'No';
						}
                        ?></td>
                          <td><a href="javascript:;" onclick="getcusdata(<?=$data->id?>)" class="btn btn-primary btn-round padding_btn">View</a></td>
                        </tr>
                              
                              <?php
	}
	
	
}
else
{
	?>
                              <tr>
                                <td colspan="4"><span class="tag_row" style="color:#ff0000;">No match found with this email & mobile</span></td>
      </tr>
                              <?php
}
?>                
                            </tbody>
                          </table>
                        </div>
                      </div>
                                      
                    <div class="col-sm-12">
                    <div class="form-group padding_30">

                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" id="new" name="new_buyer" onclick="cust_form()" value="1" class="custom-control-input" <?php if(!$cus_data){?> checked="checked" <?php } ?>/>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Continue as a new lead</span>
                        </label>
                    </div>
                    
                    </div>
 
                      <div class="col-sm-12 col-md-12 col-xs-12">
                        <div id="buy_req_div" class="form-group m-b padding_textera">
                          <label class="control-label col-sm-4">Buying Requirements </label>
                          <div class="col-sm-8">
                            <textarea class="form-control" name="buy_req" id="buy_req"></textarea>
                          </div>
                          </div>
                      </div>
</div>
          
          
                
                 
                
               
                              
                    <div id="lead_form" class="<?php if($cus_data){?>no_display<?php } ?> lead_form">
                    	
                    	 <div class="form-group row">
                                <label class="control-label col-sm-3">
                 Lead Title
                </label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control required" name="title" id="title" placeholder="Title" required/>
                                </div>
                           </div>           
                
              
              <input type="hidden" name="customer_id" id="customer_id" value=""/>
              <input type="hidden" name="assigned_to" id="assigned_to" value="1"/>
              <input type="hidden" name="source" id="source" value="2"/>
              
              
              <div class="form-group m-b row">
            <label class="control-label col-sm-3">
                  Description
                </label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                </div>
              </div>
            
             
          
              
               <div class="form-group m-b row">
                <label class="control-label col-sm-3">
                  Enquiry Date
                </label>
                <div class="col-sm-9">
                  <input type="text" onchange="getdate()" class="form-control" name="enquiry_date" id="datepicker2" placeholder="Enquiry Date" />
                </div>
              </div>
              
              
               <div class="form-group m-b row">
                <label class="control-label col-sm-3">
                  Followup Date
                </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" onchange="checkDate()" name="followup_date" id="datepicker" placeholder="Followup Date" />
                </div>
              </div>     
                    </div>          
               
            <input type="hidden" name="command" value="1"/>                                
 <script>
  $( function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker2" ).datepicker();
  } );
  </script>                      