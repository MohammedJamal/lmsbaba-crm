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
                <?php /* ?>
                <li>
                  <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_from_gmail" class="new_filter_btn" >
                    <span class="bg_span"><img src="<?php echo assets_url(); ?>images/filter_new.png"/></span>
                    Gmail
                  </a>
                </li>
                <li>
                  <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_from_gmail_response" class="new_filter_btn">
                    <span class="bg_span"><img src="<?php echo assets_url(); ?>images/filter_new.png"/></span>
                    Responses
                  </a>
                </li>
                
                <li>
                  <a class="new_filter_btn" href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_from_gmail" >
                    <span class="bg_span"><img src="<?php echo assets_url(); ?>images/gmail.png"/></span> Manage Gmail Account 
                
                  </a> 
                </li>
                <?php */ ?>
                <li>
                  <a href="JavaScript:void(0);" class="new_filter_btn" id="filter_btn">
                    <span class="bg_span"><img src="<?php echo assets_url(); ?>images/filter_new.png"/></span>
                    Filters
                  </a>
                </li>
                <li>
                  <!-- <a class="new_filter_btn" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/add/" > -->
                  <a class="new_filter_btn" href="JavaScript:void(0);" id="rander_add_new_lead_view">
                    <span class="bg_span"><img src="<?php echo assets_url(); ?>images/adduesr_new.png"/></span> Add New Lead 
                
                  </a> 
                </li>
  			       <?php  ?>
                <li>
                  <?php
                    //if(is_attribute_available('sync_lead_from_gmail')==TRUE)
                    if($this->session->userdata['admin_session_data']['user_id']=='1')
                    {
                    ?>                    
                      <a href="JavaScript:void(0);" class="upload_excel upload_csv new_filter_btn"><span class="bg_span"><img src="<?php echo assets_url(); ?>images/dwonload_new.png"></span> Upload Leads </a>
                    <?php
                    }                  
                    ?>
                </li>			  
              </ul>
              </div>
            </div>
          </div>        
        </div>
        <div class="card process-sec">
            <div class="filter_holder new">
              <div class="pull-left">
                <h5 class="lead_board">Lead Board  <a href="JavaScript:void(0)" title="Download lead from Indiamart" id="download_from_indiamart"><i class="fa fa-refresh" aria-hidden="true"></i></a></h5>
                <span id="selected_filter_div" class="lead_filter_div"></span>
              </div>
              <div class="filter_right filter_sort">
                <div class="filter_block">
                  <div class="filter_item"><strong>Sort by</strong></div>
                  <div class="filter_item">
                    <select class="sort_dd" id="sort_by">
                        <option value="">--Select One--</option>
                        <optgroup label="Lead ID">
                          <option value="lead.id-asc">ASC</option>
                          <option value="lead.id-desc">DESC</option>
                        </optgroup>
                        <optgroup label="Enquiry Date">
                          <option value="lead.enquiry_date-asc">ASC</option>
                          <option value="lead.enquiry_date-desc">DESC</option>
                        </optgroup>
                        <optgroup label="Lead Title">
                          <option value="lead.title-asc">A to Z</option>
                          <option value="lead.title-desc">Z to A</option>
                        </optgroup>
                        <optgroup label="Last Updated">
                          <option value="lead.modify_date-asc">ASC</option>
                          <option value="lead.modify_date-desc">DESC</option>
                        </optgroup>
                        <optgroup label="Next Follow-up Date">
                          <option value="lead.followup_date-asc">ASC</option>
                        <option value="lead.followup_date-desc">DESC</option>
                      </optgroup> 
                    </select>
                  </div>
                </div>
                <div id="btnContainer">

                  <button class="btn active get_view" data-target="grid"><i class="fa fa-th-large"></i></button> 
                  <button class="btn get_view" data-target="list"><i class="fa fa-bars"></i></button>
                </div>
              </div>
            </div>

            <div class="card-block">  
              <div class="bulk_bt_holder">
                <button class="new_filter_btn pull-right" type="button" id="company_assigne_change_multiple">
                  <span class="bg_span"><img src="<?php echo assets_url(); ?>images/bulk_update.png" ></span>
                  Change Assignee
                </button>
              </div>              
              <div class="table-hold">
                <table class="table table-bordered table-striped m-b-0 th_color lead-board" id="lead_table">
                  <thead>
                    <tr>  
                      <th style="text-align: center;" class="cousto_check">
                        <label class="check-box-sec">
                          <input type="checkbox" value="all" name="lead_all" class="lead_all_checkbox">
                          <span class="checkmark"></span>
                        </label>
                      </th>    
        	            <th class="sort_order" data-field="lead.id" data-orderby="asc" style="text-align: center;">                
                        #ID
                      </th>
        	            <th class="text-center sort_order desc" width="80" data-field="lead.enquiry_date" data-orderby="asc">Date</th>
        	            <th class="sort_order" data-field="lead.title" data-orderby="asc">Lead Title </th>
        	            <th class="text-center sort_order" data-field="countries.name" data-orderby="asc" width="80">Country</th>
        	            <th class="text-center sort_order" data-field="user.name" data-orderby="asc">Assigned to</th>
        	            <th class="text-center sort_order" width="110" data-field="lead.modify_date" data-orderby="asc">Last Updated</th>
        	            <th class="text-center sort_order" width="110" data-field="lead.followup_date" data-orderby="asc">Follow-up Date</th>
        	            <th class="text-center sort_order" data-field="proposal" data-orderby="asc">Quote</th>            
        	            <th class="text-center sort_order" data-field="lead.current_stage" data-orderby="asc">Stage</th>
        	            <th class="text-center sort_order" data-field="lead.current_status" data-orderby="asc">Status </th>
        	            <th class="text-center" width="100">Action</th>
                    </tr>
                  </thead>
                  <tbody id="tcontent" class="t-contant-img"></tbody>
                </table>
                

                  <input type="hidden" id="view_type" value="grid">
                  <input type="hidden" id="filter_search_str" value="<?php echo isset($_REQUEST['search_keyword'])?$_REQUEST['search_keyword']:''; ?>">		
                  <input type="hidden" id="filter_lead_from_date" value="">
                  <input type="hidden" id="filter_lead_to_date" value="">
                  <input type="hidden" id="filter_date_filter_by" value="">		
                  <input type="hidden" id="filter_assigned_user" value="10">	
                  <input type="hidden" id="filter_lead_applicable_for" value="">		
                  <input type="hidden" id="filter_opportunity_stage" value="">
                  <input type="hidden" id="filter_opportunity_status" value="">
                  <input type="hidden" id="filter_by_source" value="">
                  <input type="hidden" id="filter_is_hotstar" value="">
                  <input type="hidden" id="filter_pending_followup" value="">
                  <input type="hidden" id="filter_pending_followup_for" value="">
                  <input type="hidden" id="filter_sort_by" value="">
                  <input type="hidden" id="page_number" value="1">
                  <input type="hidden" id="is_scroll_to_top" value="N">
                  <input type="hidden" id="search_by_id" value="<?php echo isset($_GET['search_by_id'])?$_GET['search_by_id']:''; ?>">
                  <input type="hidden" id="filter_like_dsc" value="<?php echo isset($_GET['filter_like_dsc'])?$_GET['filter_like_dsc']:''; ?>">
                  

              </div>
              <div class="row">
                  <div id="page_record_count_info" class="col-md-6 text-left ffff"></div>
                  <div id="page" style="" class="col-md-6 text-right custom-pagination"></div>
                </div>
            </div> 
        </div>
      </div>    
      <div class="content-footer">
        <?php $this->load->view('admin/includes/footer'); ?>
      </div>
  </div>
 
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script src="<?=base_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/custom/lead/get.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.doubleScroll.js"></script>






</body>
</html>

<div id="NextFollowupDateUpdateForm" class="hide">
  <form id="nfdUpdateFrm">
    <div id="popover-content">          
        <div class="form-group">
            <div class="input-group date">
                <input type="text" class="form-control datetimepicker_nfd" placeholder="Select Next Followup Date" name=""  /> 
            </div>
        </div>                
        <div class="form-group">
            <button class="btn btn-primary btn-block" id="next_followup_update_confirm">Save</button>
        </div>          
      </div>
      <input type="hidden" id="nfd_lead_id" name="nfd_lead_id" value="">
      <input type="hidden" id="nfd_date" name="nfd_date" value="">
    </form>
</div>


<!-- LEAD FILTER Modal: START -->
<div class="modal fade" id="leadFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header">
            <h2>Filter Leads <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
        </div>
		<div class="modal-body">
			<div class="f_holder">
			  <div class="form-group">
				<ul>
				  <li><div class="title_f">By Date</div></li>
				  <li>
					<div class="input-prepend input-group">
					  <span class="add-on input-group-addon">
						<img src="<?php echo assets_url(); ?>images/calendar.png"/>
					  </span>
					  
					  <input type="text" class="form-control drp search_inp display_date" name="lead_from_date" id="datepicker3" placeholder="Enquiry Date" value="" />
					</div>
				  </li>
				  <li><div class="title_f">To</div></li>
				  <li>
					<div class="input-prepend input-group">
					  <span class="add-on input-group-addon">
					   <img src="<?php echo assets_url(); ?>images/calendar.png" />
					  </span>
					  
					  <input type="text" class="form-control drp search_inp display_date" name="lead_to_date" id="datepicker4" placeholder="Enquiry Date" value="" />
					</div>
				  </li>
				  <li>
					<select id="date_filter_by" name="date_filter_by" class="demo-default form-control select_user search_inp"  >
						<option value="added_on" data-text="Added On">Added On</option>
						<option value="updated_on" data-text="Last Updated">Last Updated</option>
						<option value="follow_up_on" data-text="Follow-up">Follow-up </option>
						<option value="quoted_on" data-text="Quoted">Quoted</option>
						<option value="regretted_on" data-text="Regretted">Regretted</option>
						<option value="deal_losted_on" data-text="Deal Lost">Deal Lost</option>
						<option value="deal_won_on" data-text="Deal Won">Deal Won</option>
					</select>
				  </li>
				</ul>
			  </div>
			  <div class="form-group">
				<ul>
				  <li><div class="title_f">By User</div></li>
				  <li>
					<select id="assigned_user" name="assigned_user" class="demo-default form-control select_user search_inp" placeholder="Select a User..." >
					  <option value="">Select User</option>
					  <?php
					foreach($user_list as $user_data)
					{
					  ?>
					  <option value="<?php echo $user_data['id'];?>"<?php if($assigned_user==$user_data['id']){?> selected="selected" <?php } ?> data-text="<?php echo $user_data['name']?>"><?php echo $user_data['name']?></option>
					<?php
					}
					?>
					</select>
				  </li>
				  <li>
					<label class="check-box-sec">
					  <input type="checkbox" value="E" class="" name="lead_applicable_for" data-text="Export Leads" >
					  <span class="checkmark"></span>
					</label>
					Export Leads
				  </li>
				  <li>
					<label class="check-box-sec">
					  <input type="checkbox" value="D" class="" name="lead_applicable_for" data-text="Domestic Leads">
					  <span class="checkmark"></span>
					</label>
					Domestic Leads
				  </li>
				</ul>
			  </div>
			  <div class="form-group">
				<div class="sss_title">
				  By Stage
				</div>
				<div class="sss_con">
				  <ul class="repeart_ul">
					<?php
					foreach($opportunity_stage_list as $opportunity_stage_data){
            $is_checked='';
            if($opportunity_stage_data->id==1 || $opportunity_stage_data->id==2 || $opportunity_stage_data->id==8 || $opportunity_stage_data->id==9)
            {
              $is_checked='checked="checked"';
            }
					?>
					<li>
					  <label class="check-box-sec">
						<input type="checkbox" value="<?php echo $opportunity_stage_data->id; ?>" name="opportunity_stage" id="opportunity_stage" data-text="<?php echo $opportunity_stage_data->name?>" <?php echo $is_checked; ?>>
						<span class="checkmark"></span>
					  </label>
					  <?php echo $opportunity_stage_data->name?>
					</li>
					<?php
					}
					?> 
				  </ul>
				</div>
			  </div>

			  <div class="form-group">
				<div class="sss_title">
				  By Status
				</div>
				<div class="sss_con">
				  <ul class="repeart_ul">
					<?php
					foreach($opportunity_status_list as $opportunity_status_data){
					?>
					<li>
					  <label class="check-box-sec">
						<input type="checkbox" value="<?php echo $opportunity_status_data->id; ?>" name="opportunity_status" id="opportunity_status" data-text="<?php echo $opportunity_status_data->name?>">
					  <span class="checkmark"></span>
					  </label>
					  <?php echo $opportunity_status_data->name?>
					</li>
					<?php
					}
					?> 
					<li>
					  <label class="check-box-sec">
						<input type="checkbox" value="Y" name="is_hotstar" data-text="Star Leads">
					  <span class="checkmark"></span>
					  </label>
					  Star Leads
					</li>
          <li>
            <label class="check-box-sec">
              <input type="checkbox" value="Y" name="pending_followup" data-text="Pending Followup">
              <span class="checkmark"></span>
            </label>
              Pending Followup
          </li>
				  </ul>
				</div>
			  </div>

            <div class="form-group">
                <div class="pull-left tree-holder">
                  <div class="tree_clickable fa tree-down-arrow">
                    <span>By Source </span>
                  </div>

                  <div id="select_div" class="default-scoller">
                    <div class="user_header">
                           
                       <div class="dropdown_new">
                        <a href="#" class="all-secondary">
                          <label class="check-box-sec">
                          <input type="checkbox" value="all" name="user_all" class="user_all_checkbox" >
                          <span class="checkmark"></span>
                         </label>
                        </a>
                        <div class="dropdown">
                          <button class="btn-all dropdown-toggle" type="button" id="dropdownMenuUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            None
                          </button>
                          <div class="dropdown-menu left" aria-labelledby="dropdownMenuUser">
                            <a class="dropdown-item cAll" href="#">All</a>
                            <a class="dropdown-item uAll" href="#">None</a>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="select_scroller">
                      <ul>                        
                        <?php if(count($source_list)){ ?>
                        <?php foreach($source_list as $source){ ?>
                        <li>
                           <label class="check-box-sec">
                            <input type="checkbox" value="<?php echo $source->id; ?>" name="by_source" class="user_checkbox"  data-text="<?php echo $source->name?>">
                            <span class="checkmark"></span>
                           </label>
                           <span class="cname"><?php echo $source->name; ?></span>
                        </li>
                        <?php } ?>
                        <?php } ?>
                      </ul>
                    </div>             
                  </div>                  
                </div>                
            </div>
			  <div class="filter_aaction">
				<button type="button" class="custom_blu btn btn-primary" id="lead_filter">Search</button>
				<button type="button" class="custom_blu btn btn-primary lead_filter_reset" id="">Reset</button>
			  </div>
			</div>
		</div>      
    </div>
  </div>
</div> 
<!-- LEAD FILTER Modal: END -->
<!-- ----------------- -->
<!-- upload csv (fb/ig) -->
<div id="upload_csv_fb_ig_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-width-generate modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Facebook/Instagram Lead CSV Upload System</h4>
            </div>
            <div class="modal-body">
            	<div class="alert alert-danger" id="fb_ig_error_log_div" style="display: none;">
				  <strong>Error!</strong> Error on csv data, please <a href="JavaScript:void(0)" class="get_fb_ig_error_log"><b><u>Click Here</u></b></a> to see the error logs..
				</div>
                <form id="form_upload_fb_ig_csv">                    
                        <div class="row">                            
                            <div class="col-sm-12">
                                <div class="background_grey">
                                    <a href="JavaScript:void(0)">
                                        <span class="file">             
                                                <input type="file" name="fb_ig_csv_file" id="fb_ig_csv_file" onchange="fb_ig_csv_upload_and_import()">
                                                <label for="file"><b>Click here to Upload Facebook/Instagram CSV file</b><br>OR drag and drop <br>the CSV file</label>
                                        </span>
                                    </a>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                        	<div class="col-md-12">
                            	<b class="text-danger">Note:</b> 
                            	<ol>
                            		<il>Please <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_fb_ig_sample" target="_blank"><b class="text-primary"><u>Click Here</u></b></a> to see the sample of csv.</il>
                            	</ul>
                            	<ul>
                            		<il>Please do not use any comma (,) seperator at the CSV fields</il>
                            		<li>Please do not edit or delete any columns of heading (e.g. A1 row)</li>

                            	</ol>
                            </div>
                        </div>
                </form>
            </div>            
        </div>
    </div>
</div> 

<div id="fb_ig_csv_error_log_modal" class="modal fade modal-fullscreen" role="dialog">
    <div class="modal-dialog modal-width-generate modal_margin_top modal-lg">
        <!-- Modal content-->
        <div class="modal-content" id="fb_ig_csv_error_log_content">
            
        </div>
        <input type="hidden" id="uploaded_csv_file_name" value="">
    </div>
</div>
<!-- upload csv (fb/ig) -->
<!-- ------------------ -->

<!-- ----------------- -->
<!-- upload csv -->
<div id="upload_csv_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-width-generate modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CSV Upload System</h4>
            </div>
            <div class="modal-body">
            	<div class="alert alert-danger" id="error_log_div" style="display: none;">
				  <strong>Error!</strong> Error on csv data, please <a href="JavaScript:void(0)" class="get_error_log"><b><u>Click Here</u></b></a> to see the error logs..
				</div>
                <form id="form_upload_csv">                    
                        <div class="row">                            
                            <div class="col-sm-12">
                                <div class="background_grey">
                                    <a href="JavaScript:void(0)">
                                        <span class="file">             
                                                <input type="file" name="csv_file" id="csv_file" onchange="csv_upload_and_import()">
                                                <label for="file"><b>Click here to Upload CSV file</b><br>OR drag and drop <br>the CSV file</label>
                                        </span>
                                    </a>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                        	<div class="col-md-12">
                            	<b class="text-danger">Note:</b> 
                            	<ol>
                            		<il>Please <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_lead_upload_sample" target="_blank"><b class="text-primary"><u>Click Here</u></b></a> to see the sample of csv.</il>
                            	</ul>
                            	<ul>
                            		<?php 
                            		$source_arr=[];
                            		$get_source=get_source();
                            		$source_str ='';
                            		$source_str .='<ol>';
                            		if(count($get_source))
                            		{
                            			foreach($get_source AS $rource)
                            			{
                            				$source_str .='<li class="copy">'.$rource['name'].'</li>';
                            			}
                            		} 
                            		$source_str .='</ol>';
                            		?>
                                <li>Date Format in all over CSV is dd-mm-yyyy <b>(Ex. 01-01-2021)</b></li>
                            		<il>Please do not use any <b>comma (,)</b> seperator at the CSV fields</il>
                            		<li>Please do not edit or delete any columns of heading (e.g. A1 row)</li>
                            		<li>
										<b>Required Fields<span class="text-danger">*</span>:</b> 
										<ul>
											<li>(*) Lead_Title</li>
											<li>(*) Lead_Describe_Requirement</li>
											<li>(*) Lead_Source: (Click the text to copy)
												<?php echo $source_str; ?>
											</li>
											<li>(*) Assigned_User_Employee_Id (For Ex.,1)</li>
											<li>(*) Company_Name</li>
											<li>(*) Company_Contact_Person</li>
											<li>(*) Company_Email</li>
											<li>(*) Company_Mobile</li>
											<li>(*) Company_City</li>
										</ul>
									</li>
                            	</ol>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<div id="csv_error_log_modal" class="modal fade modal-fullscreen" role="dialog">
    <div class="modal-dialog modal-width-generate modal_margin_top modal-lg">
        <!-- Modal content-->
        <div class="modal-content" id="csv_error_log_content">
            
        </div>
        <input type="hidden" id="uploaded_csv_file_name2" value="">
    </div>
</div>
<!-- upload csv -->
<!-- ------------------ -->

<!-- -------------------- -->
<!-- DOWNLOAD FROM GMAIL -->
<div id="download_from_gmail_modal" class="modal fade modal-fullscreen" role="dialog">
    <div class="modal-dialog modal-width-generate modal_margin_top modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
      			    <button type="button" class="close" data-dismiss="modal">&times;</button>
      			    <h4 class="modal-title">Download From Gmail</h4>			    
      			</div>
			      <div class="modal-body" id="download_from_gmail_content"></div>
        </div>        
    </div>
</div>
<!-- DOWNLOAD FROM GMAIL -->
<!-- -------------------- -->

<!-- -------------------- -->
<!-- WEB WHATSAPP POPUP -->
<form id="frmWebWhatsappSend">
    <div class="modal fade whatsapp-modal" id="WebWhatsappModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>

<!-- WEB WHATSAPP POPUP -->
<!-- -------------------- -->

<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form class="" name="lead_update_frm" id="lead_update_frm" method="post">
  <div class="modal fade mail-modal" id="CommentUpdateLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>
<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->

<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
  <div class="modal fade mail-modal" id="PoUploadLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
  <input type="hidden" id="is_back_show" value="Y">
</form>
<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->
<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->

<div class="modal fade mail-modal" id="QuotationListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>

<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->
<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form id="cust_reply_mail_frm" name="cust_reply_mail_frm">
  <div class="modal fade mail-modal" id="ReplyPopupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>

<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->



<!-- -------------------- -->
<!-- -------------------------------- -->
<!-- ------ADD LEAD WISE PRODUCT/SERVICES TAGGED---- -->

<div id="rander_add_tagged_ps_view_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-md">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Product/Service</h4>
    </div>
    <div class="modal-body" id="rander_add_tagged_ps_view_html"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
</div>

<!-- ------ADD LEAD WISE PRODUCT/SERVICES TAGGED ---- -->
<!-- -------------------------------- -->
<!-- ---------------------------- -->

<!-- Modal -->
<!-- <div class="modal fade w-340" id="likeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header blue-header">
        <h5 class="modal-title" id="exampleModalLabel">Ohh, is it! Tell us why?</h5>
        <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
           <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
              <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"></path>
           </svg>
        </a>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary fix-w-80">Submit</button>
      </div>
    </div>
  </div>
</div> -->

<div class="like_pop" id="like_pop_block">
  <div class="pop-header">
      <h5>Great Going!</h5>
      <a href="#" class="close-pop">
         <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
            <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"></path>
         </svg>
      </a>
    </div>
    <div class="pop-body">
      <input type="hidden" name="like_id_field" id="like_id_field">
      <div class="like_scroller" id="like_stage_view"></div>
      <!-- <div class="com-holder">
        <textarea class="mail-input bg-input" placeholder="Mention your reason"></textarea>
      </div> -->
    </div>
    <div class="pop-footer">
      <button type="button" class="btn btn-primary fix-w-80" id="like_btn_confirm">Submit</button>
    </div>
</div>

<div class="like_pop" id="dislike_icon_block">
  <div class="pop-header">
      <h5>Ohh, is it! Tell us why?</h5>
      <a href="#" class="close-pop">
         <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
            <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"></path>
         </svg>
      </a>
    </div>
    <div class="pop-body">
      <input type="hidden" name="dislike_id_field" id="dislike_id_field">
      <div class="like_scroller" id="dislike_stage_view"></div>
      <!-- <div class="com-holder">
        <textarea class="mail-input bg-input" placeholder="Mention your reason"></textarea>
      </div> -->
    </div>
    <div class="pop-footer">
      <button type="button" class="btn btn-primary fix-w-80" id="dislike_btn_confirm">Submit</button>
    </div>
</div>
<div class="like_overlay"></div>

<script type="text/javascript">
  $(window).load(function() {
    $('.order-status-container').each(function( index ) {
      var getChild = $(this).find('.status-item').size();
      var newW = getChild*20;
      $(this).css({'width':newW+'%'})
      console.log( index + ": " + newW );
    });
  });
	$(document).ready(function() {

    
    $("body").on("click",".get_alert",function(e){
      var txt=$(this).attr('data-text');
      swal("Oops!", txt, "error");
    });    
    $('.modal').on("hidden.bs.modal", function (e) { 
      
      setTimeout(function(){ 
        console.log('1 closeeeeeeeeee')
        if ($('.modal:visible').length) { 
          console.log('2 closeeeeeeeeee')
          $('body').addClass('modal-open');
        }
      }, 500);
      
    });
    //////////
updateLeadView = function(){
  $(".lead_all_checkbox").change(function() {
      $('.cousto_check .check-box-sec').removeClass('same-checked');
      $('input:checkbox[name="checked_to_customer"]').prop('checked', $(this).prop("checked"));
      //console.log($(this).prop("checked"));
      if($(this).prop("checked") == true){
        showUpdateBt();
      }else{
        hideUpdateBt();
      }
  });
  var $checkboxes = $('#tcontent input[name="checked_to_customer"]');
  var totalcheck = $checkboxes.length;
  $checkboxes.change(function(){
      var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
      //console.log(totalcheck+', '+countCheckedCheckboxes);
      $('.cousto_check .check-box-sec').removeClass('same-checked');
      if(countCheckedCheckboxes > 0 && countCheckedCheckboxes < totalcheck){
        $('.cousto_check .check-box-sec').addClass('same-checked');
        $('.lead_all_checkbox').prop('checked', false);
        showUpdateBt();
      }else if(countCheckedCheckboxes == totalcheck){
        $('.lead_all_checkbox').prop('checked', true);
        showUpdateBt();
        hideUpdateBt();
      }else{
        $('.lead_all_checkbox').prop('checked', false);
        hideUpdateBt();
      }
  }); 
  function showUpdateBt(){
    //onsole.log('showUpdateBt')
    $('.bulk_bt_holder').css({'display':'inline-block'});
  }
  function hideUpdateBt(){
    //console.log('hideUpdateBt')
    $('.bulk_bt_holder').hide();
  }  
}  
    /////////////rander_add_new_lead_view_modal
    $('#lead_company_details').on('show.bs.modal', function () {
        $('#rander_add_new_lead_view_modal').addClass('goback');
    });
     
    $('#lead_company_details').on('hidden.bs.modal', function () {
        $('#rander_add_new_lead_view_modal').removeClass('goback');
    });
    $('#add_source_modal').on('show.bs.modal', function () {
        $('#rander_add_new_lead_view_modal').addClass('goback');
    });
     
    $('#add_source_modal').on('hidden.bs.modal', function () {
        $('#rander_add_new_lead_view_modal').removeClass('goback');
    });
    
    function trimText(selector, limit) {    
        var text = selector.text(),
            trim;

        selector.each(function() {
            if ($(this).text().length > limit) {
                trim = $(this).text().substr(0, limit);
                var txt = trim+'... <span class="expand">Read More <i class="fa fa-plus-circle">'
                $('<div class="expend-link show-more">'+txt+'</div>').insertBefore(this);
                $(this).hide();
                //$(this).append(trim+'...');
                $(this).append('<div class="expend-link show-less mt-10"><span class="collapse">Read Less <i class="fa fa-chevron-circle-up"></div>');
            };
        });

        $('body').on("click",".expend-link .expand", function() { //future element
          console.log('readmore');
          $(this).parent().hide();
          $(this).parent().parent().find('.lead-details').show();
          
        });

        $('body').on("click", ".expend-link .collapse",function() { //future element
          $(this).parent().parent().hide();
          $(this).parent().parent().parent().find('.show-more').show();
        });

    };
    updateGrid = function(){
      //alert('updateGrid()');
      $('.bulk_bt_holder').hide();
      $('.lead_all_checkbox').prop('checked', false);
      $('input:checkbox[name="lead_id"]').prop('checked', false);
      $( ".lead-details" ).each(function( index ) {
        console.log( index + ": " + $( this ).text() );
        trimText($(this),   94);
      });
      
      $(document).on("click","a.show_lead_quote",function(event) {
        event.preventDefault();
        $(this).parent().find('.footer-shadow').toggleClass('show');
      });
    }
		$('.modal').on('hidden.bs.modal', function (event) {
			$('.modal:visible').length && $(document.body).addClass('modal-open');
		});	

		var copy = document.querySelectorAll(".copy"); 
		for (const copied of copy) { 
		  copied.onclick = function() { 
		    document.execCommand("copy"); 
		  };  
		  copied.addEventListener("copy", function(event) { 
		    event.preventDefault(); 
		    if (event.clipboardData) { 
		      event.clipboardData.setData("text/plain", copied.textContent);
		      //console.log(event.clipboardData.getData("text"));
		      
		      swal("Lead_Source: '"+event.clipboardData.getData("text")+"' copied!"); 
		    };
		  });
		};
	});  

  // ======================================================
  // UPDATE COMMENT OF LEAD 
  function fn_regret_lead_view(lid)
  {
     var base_url = $("#base_url").val();   
     $.ajax({
         url: base_url + "lead/rander_deal_lost_lead_view_popup_ajax",
         type: "POST",
         data: {
             'lid': lid
         },
         async: true,
         success: function(response) {
             // $('#CommentUpdateLeadModal').modal('hide')
             $('#CommentUpdateLeadModal').html(response);
             $(".buyer-scroller").mCustomScrollbar({
               scrollButtons:{enable:true},
               theme:"rounded-dark"
               });
             //////
             $('.select2').select2();
             simpleEditer();
             ////////////////////////
             $('#CommentUpdateLeadModal').modal({
                 backdrop: 'static',
                 keyboard: false
             });
         },
         error: function() {
             
         }
     });
  }
  function fn_get_opp_id_view(lid)
  {
     var base_url = $("#base_url").val();  
     var is_back_show=$("#is_back_show").val();      
     $.ajax({
         url: base_url + "lead/rander_quotation_list_view_popup_ajax",
         type: "POST",
         data: {
             'lid': lid,
             'is_back_show': is_back_show,
         },
         async: true,
         success: function(response) {
             $('#CommentUpdateLeadModal').modal('hide')
             $('#QuotationListModal').html(response);
             $(".buyer-scroller").mCustomScrollbar({
               scrollButtons:{enable:true},
               theme:"rounded-dark"
               });
             //////
             $('.select2').select2();
             simpleEditer();
             ////////////////////////
             $('#QuotationListModal').modal({
                 backdrop: 'static',
                 keyboard: false
             });
         },
         error: function() {
             
         }
     });
  }
  function fn_get_po_upload_view(lid,l_opp_id)
  {
     var base_url = $("#base_url").val(); 
     var is_back_show=$("#is_back_show").val(); 
       
     $.ajax({
         url: base_url + "lead/rander_po_upload_view_popup_ajax",
         type: "POST",
         data: {
             'lid': lid,
             'l_opp_id':l_opp_id,
             'is_back_show': is_back_show,
         },
         async: true,
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
             $('#CommentUpdateLeadModal').modal('hide')
             $('#PoUploadLeadModal').html(response);
             $(".buyer-scroller").mCustomScrollbar({
               scrollButtons:{enable:true},
               theme:"rounded-dark"
               });
             //////
             $('.select2').select2();
             simpleEditer();
             ////////////////////////
             $('#PoUploadLeadModal').modal({
                 backdrop: 'static',
                 keyboard: false
             });
         },
         error: function() {
             
         }
     });
  }
  function open_lead_update_lead_view(lid)
  {
     var base_url = $("#base_url").val();     
     $.ajax({
         url: base_url + "lead/rander_lead_update_view_popup_ajax",
         type: "POST",
         data: {
             'lid': lid
         },
         async: true,
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
             // $('#updateLeadModal').modal('show')
             $('#CommentUpdateLeadModal').html(response);
             $(".buyer-scroller").mCustomScrollbar({
               scrollButtons:{enable:true},
               theme:"rounded-dark"
               });
             //////
             $('.select2').select2();
             simpleEditer();
             ////////////////////////
             $('#CommentUpdateLeadModal').modal({
                 backdrop: 'static',
                 keyboard: false
             });
         },
         error: function() {
             
         }
     });
  }

  function fn_get_po_upload_view_without_quotation(lid,l_opp_id)
  {
     var base_url = $("#base_url").val();  
     var is_back_show=$("#is_back_show").val(); 
     
     $.ajax({
         url: base_url + "lead/rander_po_upload_view_popup_without_quotation_ajax",
         type: "POST",
         data: {
             'lid': lid,
             'l_opp_id':'',
             'is_back_show': is_back_show,
         },
         async: true,
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
             $('#CommentUpdateLeadModal').modal('hide')
             $('#PoUploadLeadModal').html(response);
             $(".buyer-scroller").mCustomScrollbar({
               scrollButtons:{enable:true},
               theme:"rounded-dark"
               });
             //////
             $('.select2').select2();
             simpleEditer();
             ////////////////////////
             $('#PoUploadLeadModal').modal({
                 backdrop: 'static',
                 keyboard: false
             });
         },
         error: function() {
             
         }
     });
  }
  
  // STYLE
  var edit = function(cmd) {
    var val = false;
    switch (cmd) {
     case 'formatBlock': val = 'blockquote'; break;
     case 'createLink': val = prompt('Enter the URL to hyperlink to from the selected text:'); break;
     case 'insertImage': val = prompt('Enter the image URL:'); break;
    }
    document.execCommand(cmd, false, val);
    box.focus();
  }
  function simpleEditer()
  {
    // $(".tools").show();
    var box = $('.buying-requirements');
    box.attr('contentEditable', true);

    

    // EDITING LISTENERS
    $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
       edit($(this).data('cmd'));
    });    
  }
  function inactiveSimpleEditer()
  {
    $(".tools").hide();
    var box = $('.buying-requirements');
    box.attr('contentEditable', false);
  }
  // UPDATE COMMENT OF LEAD 
  // ======================================================
</script>
<style type="text/css">.copy {cursor: copy;}</style>

