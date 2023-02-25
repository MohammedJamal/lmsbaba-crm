<?php $this->load->view('include/header');?>



      <!-- content panel -->
      <div class="main-panel">
        <!-- top header -->
        <nav class="header navbar">
          <div class="header-inner">
            <div class="navbar-item navbar-spacer-right brand hidden-lg-up">
              <!-- toggle offscreen menu -->
              <a href="javascript:;" data-toggle="sidebar" class="toggle-offscreen">
                <i class="material-icons">menu</i>
              </a>
              <!-- /toggle offscreen menu -->
              <!-- logo -->
              <a class="brand-logo hidden-xs-down">
                <img src="<?php echo assets_url(); ?>images/logo_white.png" alt="logo"/>
              </a>
              <!-- /logo -->
            </div>
            <a class="navbar-item navbar-spacer-right navbar-heading hidden-md-down" href="#">
              <span>LMS-Deleted E-mail List</span>
            </a>
            <div class="navbar-search navbar-item">
              <form class="search-form">
                <i class="material-icons">search</i>
                <input class="form-control" type="text" placeholder="Search" />
              </form>
            </div>
            
          </div>
        </nav>
        <!-- /top header -->

        <!-- main area -->
        <div class="main-content">
  
          <div class="content-view">
            
            <div class="card">
            <?php
            	/*if(isset($flag))
            	{*/
					?>
					<div id="msg" class="alert alert-success" style="display: none">Lead Assigned Successfully</div>
					<?php
				/*}*/
            ?>
              <div class="card-header no-bg b-a-0">
               Deleted E-mail List
                <div class="pull-right">
               
                 
                </div>
              </div>
              <div class="card-block">
                <div class="no-more-tables">
                  <table id="datatable" class="table table-bordered datatable table-striped m-b-0">
                    <thead>
                      <tr>
                        <th>
                          Id
                        </th> 
                        <th>
                          Date
                        </th>                       
                        <th>
                          From
                        </th>                        
                        <th>
                          Subject
                        </th>
                        <th>
                          Attachment
                        </th>
                        <th>
                          Reason
                        </th>
                        <!--<th class="action_th_width">
                         Action
                        </th> --> 
                      </tr>
                    </thead>
                    <tbody>
                    
                    <?php 
                    $i=0;
                    foreach($mail_list as $output){
                    	
                    	$email=$output->from_email;
				
                    	?>
                    
                      <tr <?php ($output->seen ? 'read' : 'unread') ?>>
                        <td data-title="Id">
                          <?php echo $output->id;?>
                        </td>
                        <td data-title="Date">
                          <?php echo $output->email_date;?>
                        </td>                        
                       
                        <td data-title="E-mail">
                          <?php echo $email;?>
                        </td>
                        <td data-title="Subject">
                          <?php echo $output->subject;?>
                        </td>
                        <td data-title="Attachment">
                        <?php if($output->is_attach){ echo 'Yes'; }else{ echo 'No'; } ?>
                        </td>
                       	<td data-title="Status">
                       	<?php
                       	if($output->status=='3')
                       	{
							?>
							sPAM eMail
							<?php
						}
						else if($output->status=='4')
                       	{
							?>
							Marketing eMail
							<?php
						}
						else if($output->status=='5')
						{
							?>
							Irrelevant eMail
							<?php
						}
                       	?>
                          
                        </td>
                        
                        <!--<td data-title="Status">
                           <a href="#" onclick="GetModalBody(<?php echo $output->id;?>,'<?php echo $i;?>')" data-toggle="modal" data-target=".bd-example-modal"><i class="fa fa-eye" style="font-size: 20px;"></i></a>
                           <a onclick="GetModalDelete('<?php echo $email;?>','<?php echo $output->id;?>')"><i class="fa fa-trash-o" style="font-size: 20px; "></i></a>
                           <a href="#" onclick="GetTagLeadList('<?php echo $email;?>','<?php echo $output->id;?>')"><i class="fa fa-tag" style="font-size: 20px; "></i></a>
                           
                        </td>-->
                       
                      </tr>
                  <?php
                  $i++;
                  }
                  ?> 
                   	
                    </tbody>
                  </table>
                 <!-- <nav class="pull-right">
                <ul class="pagination">
                  <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                      <span class="sr-only">Previous</span>
                    </a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">4</a></li>
                  <li class="page-item"><a class="page-link" href="#">5</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                      <span class="sr-only">Next</span>
                    </a>
                  </li>
                </ul>
              </nav>-->
                </div>
              </div>
            </div>
          </div>
          
      
      
      
      
    <div class="modal fade bd-example-modal" id="mod1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content background_color">
           <div id="modal_body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
          </div>
        </div>
      </div>  
      
      <?php  
           $i=0;
                    foreach($mail_list as $output){
          ?>
          <?php
				$email=$output->from_email;
				
				
		
				?>
          
      
        <div class="modal fade bd-example-modal_user<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content background_color">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Assigned To User</h4>
            </div>
            <div class="modal-body">
            
              <div class="enquery">
           		
              	<!--<div>
              	<label>Existing Customer</label>
              		<input type="radio" name="chk_customer" id="chk_customer1_<?php echo $i;?>" value="1" onclick="return chk_exist_cus('<?php echo $i;?>')"/>
              		<label>New Customer</label>
              		<input type="radio" name="chk_customer" id="chk_customer2_<?php echo $i;?>" value="2" onclick="return chk_exist_cus('<?php echo $i;?>')"/>
              	</div>-->
              	
				<div id="exist_cus_<?php echo $i;?>">
				
				
				
				 <div class="col-lg-12">
                                  <div id="alert_form<?php echo $i;?>"></div>
                              <div class="form-group">
                                <label>
                  E-mail
                </label>
                <input type="text" class="form-control required" name="email_<?php echo $i;?>" id="email_<?php echo $i;?>" placeholder="E-mail" onkeyup="getoption(<?php echo $i;?>)" onblur="getoption(<?php echo $i;?>)" value="<?php echo $email?>" required/>
                              </div>
                             <div id="first_option_<?php echo $i;?>"></div>
                             <div class="form-group">
                                <label>
                  Mobile
                </label>
                <input type="text" class="form-control" name="mobile_<?php echo $i;?>" id="mobile_<?php echo $i;?>" placeholder="Mobile" onkeyup="getsecondoption(<?php echo $i;?>)" onblur="getsecondoption(<?php echo $i;?>)" value="" required/>
                              </div>
                              
                              <div id="second_option_<?php echo $i;?>"></div>
                            
                            
                            <div id="cust_info_<?php echo $i;?>" style="display: none;">
					<div class="form-group">
					<label>
					First Name
					</label>
					<input type="text" class="form-control" name="first_name_<?php echo $i;?>" id="first_name_<?php echo $i;?>" placeholder="First Name" required/>
					</div>
                              
					<div class="form-group">
					<label>
					Last Name
					</label>
					<input type="text" class="form-control" name="last_name_<?php echo $i;?>" id="last_name_<?php echo $i;?>" placeholder="Last Name" required/>
					</div>
                              
					<div class="form-group" style="display: none;">
					<label>
					Office Phone
					</label>
					<input type="text" class="form-control" name="office_phone_<?php echo $i;?>" id="office_phone_<?php echo $i;?>" placeholder="Office Phone" required/>
					</div>
                              
					<div class="form-group">
					<label>
					Website
					</label>
					<input type="text" class="form-control" name="website_<?php echo $i;?>" id="website_<?php echo $i;?>" placeholder="Website" required/>
					</div>
                              
					<div class="form-group">
					<label>
					Company Name
					</label>
					<input type="text" class="form-control" name="company_name_<?php echo $i;?>" id="company_name_<?php echo $i;?>" placeholder="Company Name" required/>
					</div>
                              
					<div class="form-group" style="display: none;">
					<label>
					Address
					</label>
					<textarea class="form-control" name="address_<?php echo $i;?>" id="address_<?php echo $i;?>" placeholder="Address" required></textarea>
					</div>
                              
					<div class="form-group" style="display: none;">
					<label>
					Country
					</label>
					<select class="form-control" name="country_id_<?php echo $i;?>" id="country_id_<?php echo $i;?>" required onchange="GetStateList(this.value,'<?php echo $i;?>')">
						<option value="">Select</option>
						<?php foreach($country_list as $country_data)
						{
							?>
							<option value="<?php echo $country_data->id;?>"><?php echo $country_data->name;?></option>
							<?php
						}
						?>
						
					</select>
					</div>
					<div class="form-group" style="display: none;">
					<label>
					State
					</label>
					<select class="form-control" name="state_<?php echo $i;?>" id="state_<?php echo $i;?>" required onchange="GetCityList(this.value,'<?php echo $i;?>')">
						<option value="">Select</option>
					</select>
					</div>
					<div class="form-group" style="display: none;">
					<label>
					City
					</label>
					<select class="form-control" name="city_<?php echo $i;?>" id="city_<?php echo $i;?>" required>
						<option value="">Select</option>
					</select>
					</div>
					<div class="form-group" style="display: none;">
					<label>
					ZIP
					</label>
					<input type="text" class="form-control" name="zip_<?php echo $i;?>" id="zip_<?php echo $i;?>" placeholder="ZIP" required/>
					</div>
                              
                              </div>
                              
                              
                                </div>
                                <div class="col-lg-12" id="">
                                </div>
				<div class="col-lg-12">
				<label>Assign User</label>
              	 <select rows="3" cols="50" id="user_id_<?php echo $i;?>" class="form-control" required>
              	 	<option value="">Select</option>
              	 	<?php
              	 		foreach($user_list as $user_data)
              	 		{
							?>
							<option value="<?php echo $user_data->id?>"><?php echo $user_data->name?></option>
							<?php
						}
              	 	?>
              	 </select> 
				</div>
              	</div>
              	
              </div>
            </div>
            <input type="hidden" name="customer_id" id="customer_id_<?php echo $i;?>" value=""/>
            <input type="hidden" name="mail_id" id="mail_id_<?php echo $i;?>" value="<?php echo $output->id?>"/>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="assign_submit_<?php echo $i;?>" name="submit" onclick="">Assign Lead</button>
            </div>
          </div>
        </div>
      </div>
      
      <?php
     
                    	$i++;
                  }
      ?>
      
      
          <!-- bottom footer -->
          <div class="content-footer">
          
            <nav class="footer-right">
              <ul class="nav">
                <li>
                  <a href="javascript:;">Feedback</a>
                </li>
              </ul>
            </nav>
            <nav class="footer-left">
              <ul class="nav">
                <li>
                  <a href="javascript:;">
                    <span>Copyright</span>
                    &copy; 2016 Your App
                  </a>
                </li>
                <li class="hidden-md-down">
                  <a href="javascript:;">Privacy</a>
                </li>
                <li class="hidden-md-down">
                  <a href="javascript:;">Terms</a>
                </li>
                <li class="hidden-md-down">
                  <a href="javascript:;">help</a>
                </li>
              </ul>
            </nav>
           
          </div>
          <!-- /bottom footer -->
        </div>
        <!-- /main area -->
      </div>
      <!-- /content panel -->
      
      
      
     <!-- Modal -->
<div id="tag_lead" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Following Buyer found matching with this lead</h4>
      </div>
      <div class="modal-body" id="tag_lead_list"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="del_email" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Delete Reason</h4>
      </div>
      <div class="modal-body">
              <div class="enquery">
      <form action="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/emaildelete/" method="post" id="reply_form_inline" name="reply_form">
      			<label>Enter Reason</label>
              	 <textarea name="reply" rows="3" cols="50"></textarea>
              	 <input type="hidden" name="buyer_email" id="buyer_email_inline" value=""/>
              	 <input type="hidden" name="delete_type" id="delete_type_inline" value=""/>
              	 <input type="hidden" name="mail_id" id="mail_id_inline" value=""/>
              	 </form>
      <ul>
              	 	
              	 	<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style=".dropdown-toggle::after{display:none !important;}">Delete This Lead</a>
              	 	<ul class="dropdown-menu">
                            <li><a class="bg_none" href="#" onclick="return confirmation_inline('3')">sPAM eMail</a></li>
                            <li><a class="bg_none" href="#" onclick="return confirmation_inline('4')">Marketing eMail</a></li>
                            <li><a class="bg_none" href="#" onclick="return confirmation_inline('5')">Irrelevant eMail</a></li>
                         </ul>
              	 	</li>
              	 	
              	 </ul>
      </div>        	 
      </div>        	 
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
      
      <script type="text/javascript">
		function opentxtar(id)
		{
			if(document.getElementById('chk_'+id).checked==true)
			{
				document.getElementById('reply_'+id).style.display='block';
			}
			else
			{
				document.getElementById('reply_'+id).style.display='none';
			}
			
		}
</script>

    
      

    </div>
    <?php
   /* function extract_email_address($string) {
    foreach(preg_split('/\s/', $string) as $token) {
        $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        if ($email !== false) {
            $emails[] = $email;
        }
    }
    return $emails;
}*/
    ?>

    <script type="text/javascript">
      window.paceOptions = {
        document: true,
        eventLag: true,
        restartOnPushState: true,
        restartOnRequestAfter: true,
        ajax: {
          trackMethods: [ 'POST','GET']
        }
      };
      
      function confirmation(type_id,row)
      {
      	
		document.getElementById('delete_type_'+row).value=type_id;
		
	  	 var t=confirm('Are you want to delete this lead?');
	  	 if(t==true)
	  	 {
	  	 	
		 	document.getElementById("reply_form").submit();
		 }
		 else
		 {
		 	return false;
		 }
	  }
function confirmation_inline(type_id)
{
	 var t=confirm('Are you want to delete this lead?');
	 if(t==true)
	 {
	 	document.getElementById('delete_type_inline').value=type_id;
	 	document.getElementById("reply_form_inline").submit();
	 }
	 else
	 {
	 	return false;
	 }
}

  
       
    

    </script>
     

    <!-- build:js({.tmp,app}) scripts/app.min.js -->
    <script src="<?php echo base_url()?>vendor/jquery/dist/jquery.js"></script>
    <script src="<?php echo base_url()?>vendor/pace/pace.js"></script>
    <script src="<?php echo base_url()?>vendor/tether/dist/js/tether.js"></script>
    <script src="<?php echo base_url()?>vendor/bootstrap/dist/js/bootstrap.js"></script>
    <script src="<?php echo base_url()?>vendor/fastclick/lib/fastclick.js"></script>
    <script src="<?php echo base_url()?>scripts/constants.js"></script>
    <script src="<?php echo base_url()?>scripts/main.js"></script>
    <!-- page scripts -->
    <script src="<?php echo base_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <!-- end page scripts -->
    <!-- endbuild -->
	<script src="<?php echo base_url();?>vendor/datatables/media/js/jquery.dataTables.js"></script>
    <script src="<?php echo base_url();?>vendor/datatables/media/js/dataTables.bootstrap4.js"></script>
    <!-- page scripts -->
    <!-- end page scripts -->
	
    <!-- initialize page scripts -->
    <!-- end initialize page scripts -->
    
    <script type="text/javascript">
     $(document).ready(function() {
    $('#datatable').DataTable({
    	"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
               
            }
        ],
    	"order": [[ 0, "asc" ]]
    });
} );
        function GetModalDelete(email,mail_id)
        {
			
			document.getElementById('buyer_email_inline').value=email;
			document.getElementById('mail_id_inline').value=mail_id;
			$('#del_email').modal(); 
			 
		} 
		
		
		function GetTagLeadList(email,mail_id)
        {
			$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/gettagleadlist",
			  type: "POST",
			  data: {'email':email,'mail_id':mail_id},			 
			  async:true,		  
			  success: function (response) 
			  {
			  	
			  		$('#tag_lead_list').html(response);
			  		
					$('#tag_lead').modal(); 
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   	alert('Something went wrong there');
			  }
		   });
		} 

		function chk_exist_cus(row){
		   var t= document.getElementById('chk_customer1_'+row).checked;
		   var s= document.getElementById('chk_customer2_'+row).checked;
		   if(t==true)
		   {
		   	 document.getElementById('exist_cus_'+row).style.display='block';
		   	 
		   }
		   else if(s==true)
		   {
		   	 document.getElementById('exist_cus_'+row).style.display='none';
		   	 //window.location.href='<?php echo base_url()?>customer/add/';
		   }
		}
      
      function getoption(row)
      {
      	
      	var val=document.getElementById('email_'+row).value;
      	
      	
	  	$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getfirstoption",
			  type: "POST",
			  data: {'email':val},
			  async:true,		  
			  success: function (response) 
			  {
			  		
			  		
			  	
			  		var response=response.replace("setnewcus()", "cust_form("+row+")");  		
			  		
			  		$('#first_option_'+row).html(response);	
			  			
			  		//getmobile(row);
			  		$('#second_option_'+row).html(''); 
			  		var cnt_rdo=$('#first_option_'+row+' input:radio').size();
			  		if(cnt_rdo>1)
			  		{
						$("#assign_submit_"+row).attr("onclick","exist_cus_assign("+row+")");
					}
					else
					{
						$("#assign_submit_"+row).attr("onclick","setnewcus("+row+")");
					}
					
			  		
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
	  }
	  function getmobile(row)
      {
      	val=document.getElementById('email_'+row).value;
      	
	  	$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getmobileno",
			  type: "POST",
			  data: {'email':val},
			  async:true,		  
			  success: function (response) 
			  {
			  	
			  		var data=response.split('&')
			  		//$('#email').val(data[0]); 
			  		$('#mobile_'+row).val(data[1]); 
			  		$('#customer_id_'+row).val(data[2]); 
			  		
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
	  }
	  function getsecondoption(row)
      {
      	val=document.getElementById('mobile_'+row).value;
      	if(val!='')
      	{
			
		  	$.ajax({
				  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getsecondoption",
				  type: "POST",
				  data: {'mobile':val},		  
				  success: function (response) 
				  {
				  		$('#second_option_'+row).html(response); 
				  		getemail(row);
				  		$('#first_option_'+row).html(''); 
				  		
				  	var cnt_rdo=$('#second_option_'+row+' input:radio').size();
			  		if(cnt_rdo>1)
			  		{
						$("#assign_submit_"+row).attr("onclick","exist_cus_assign("+row+")");
					}
					else
					{
						$("#assign_submit_"+row).attr("onclick","setnewcus("+row+")");
					}
				  	$("#new").attr("onclick","cust_form("+row+")");
				  		
				  },
				  error: function () 
				  {
				   //$.unblockUI();
				   alert('Something went wrong there');
				  }
			   });
		}
	  }
	  
	  function getemail(row)
      {
      
      	val=document.getElementById('mobile_'+row).value;
      	if(val!='')
      	{
	  	$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getemail",
			  type: "POST",
			  data: {'mobile':val},
			  async:true,		  
			  success: function (response) 
			  {
			  	
			  		var data=response.split('&')
			  		if(data[0]!='')
			  		{
						$('#email_'+row).val(data[0]); 
				  		//$('#mobile').val(data[1]); 
				  		$('#customer_id_'+row).val(data[2]); 
					}
			  		
			  		
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
		}
	  }
	  

	function cust_form(row)
	{  
		if(document.getElementById('new').checked==true)
		{
			document.getElementById('cust_info_'+row).style.display='block';
			$("#mobile_"+row).attr("onkeyup","");
			$("#mobile_"+row).attr("onblur","");
		}
		else
		{
			document.getElementById('cust_info_'+row).style.display='none';
			$("#mobile_"+row).attr("onkeyup","getsecondoption("+row+")");
			$("#mobile_"+row).attr("onblur","getsecondoption("+row+")");
		}
	}
	function setnewcus(row)
	{
		
		var email=document.getElementById('email_'+row).value;
		var mobile=document.getElementById('mobile_'+row).value;
		var first_name=document.getElementById('first_name_'+row).value;
		var last_name=document.getElementById('last_name_'+row).value;
		var office_phone=document.getElementById('office_phone_'+row).value;
		var website=document.getElementById('website_'+row).value;
		var company_name=document.getElementById('company_name_'+row).value;
		var address=document.getElementById('address_'+row).value;
		var country=document.getElementById('country_id_'+row).value;
		var state=document.getElementById('state_'+row).value;
		var city=document.getElementById('city_'+row).value;
		var zip=document.getElementById('zip_'+row).value;
		var user_id=document.getElementById('user_id_'+row).value;
		var mail_id=document.getElementById('mail_id_'+row).value;
		
		if(email=='' && mobile=='')
		{
			document.getElementById('alert_form'+row).innerHTML='<div class="alert alert-danger">Please enter email or mobile</div>';
		}
		else if(document.getElementById('new').checked==false) {
        // get value, set checked flag or do whatever you need to alert-form
        document.getElementById('alert_form'+row).innerHTML='<div class="alert alert-danger">No Buyer found with this Email ID . Please Check the box to create a new buyer with this Email and add the lead under that.</div>';
        return false;
    }
    
		else if(user_id=='')
		{
			document.getElementById('alert_form'+row).innerHTML='<div class="alert alert-danger">Please select user</div>';
		}
		else
		{
			$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/setnewcustomer_lead",
			  type: "POST",
			  data: {'mobile':mobile,'email':email,'user_id':user_id,'mail_id':mail_id,'first_name':first_name,'last_name':last_name,'office_phone':office_phone,'website':website,'company_name':company_name,'address':address,'country':country,'state':state,'city':city,'zip':zip},		  
			  success: function (response) 
			  {
			  		window.location.href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getemaillist/";	
			  		document.getElementById('msg').style.display='block';	  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
		}
		
	}
	
	function exist_cus_assign(row)
	{
		
		var customer_id=document.getElementById('customer_id_'+row).value;
		
		var user_id=document.getElementById('user_id_'+row).value;
		var mail_id=document.getElementById('mail_id_'+row).value;
		var radios = document.getElementsByTagName('option1');
		for (var i = 0; i < radios.length; i++) {
    if (radios[i].type === 'radio' && radios[i].checked) {
        // get value, set checked flag or do whatever you need to
        value = radios[i].value;       
    }
}

		if(user_id=='') {
        // get value, set checked flag or do whatever you need to
        alert('Please check on add new buyer');
        return false;
    }    
    else
    {
		
	
		$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/exist_cus_assign",
			  type: "POST",
			  data: {'customer_id':customer_id,'assigned_to':user_id,'mail_id':mail_id,'command':'1'},		  
			  success: function (response) 
			  {
			  	if(response==1)
			  	{
					window.location.href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getemaillist/";
					document.getElementById('msg').style.display='block';
				}
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
		}
	}
	
	function GetStateList(cont,row)
	{
		$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getstatelist",
			  type: "POST",
			  data: {'country_id':cont},		  
			  success: function (response) 
			  {
			  	if(response!='')
			  	{
					document.getElementById('state_'+row).innerHTML=response;
				}
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
	}
	
	function GetCityList(state,row)
	{
		$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getcitylist",
			  type: "POST",
			  data: {'state_id':state},		  
			  success: function (response) 
			  {
			  	if(response!='')
			  	{
					document.getElementById('city_'+row).innerHTML=response;
				}
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
	}
	
	function tag_lead(mail_id)
	{
		
		var lead_tag = document.getElementsByName('lead_tag');
		var lead_tag_value='';
		for(var i = 0; i < lead_tag.length; i++){
		    if(lead_tag[i].checked){
		        var lead_tag_value = lead_tag[i].value;
		    }
		}

		if(lead_tag_value=='')
		{
			alert('Please select a lead');
		}
		else
		{
			var res = lead_tag_value.split(",");
			var customer_id=res[0];
			var lead_id=res[1];
			var assigned_user_id=res[2];
			
			$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/lead_tag",
			  type: "POST",
			  data: {'customer_id':customer_id,'assigned_user_id':assigned_user_id,'lead_id':lead_id,'mail_id':mail_id,'command':'1'},		  
			  success: function (response) 
			  {
			  	if(response==1)
			  	{
					window.location.href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getemaillist/";
					document.getElementById('msg').style.display='block';
				}
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
		}
	}
	
	function close_modal()
	{
		$('#tag_lead').modal('toggle');
	}
	
	
	function GetModalBody(id,row_id)
	{
		document.getElementById('modal_body').innerHTML='';
		
		$.ajax({
			  url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/modalbody",
			  type: "POST",
			  data: {'email_id':id,'row_id':row_id},		  
			  success: function (response) 
			  {
			  	if(response!='')
			  	{
					document.getElementById('modal_body').innerHTML=response;
				}
			  		
			  },
			  error: function () 
			  {
			   //$.unblockUI();
			   alert('Something went wrong there');
			  }
		   });
	}

    </script>
    
  

  </body>
</html>
