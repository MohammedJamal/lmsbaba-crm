<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?> 
   <?php /* ?> 
   <script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">

      tinymce.init({
        selector: 'textarea.bulkEmail-wysiwyg-editor',
        height: 300,
        menubar: false,
        statusbar: false,
        plugins: ["code advlist autolink lists link image charmap print preview anchor textcolor"],
        toolbar: 'bold italic backcolor forecolor | bullist numlist',
        content_css: [],
        setup: function(editor) {
          editor.on('focusout', function(e) {
            // console.log(editor);       
            var updated_content=editor.getContent();
            $("#bulk_email_body").val(updated_content);
            //check_submit();
          });
        }
      });
      
    </script> 
    <?php */ ?> 
  </head>
  <body>
    <div class="app full-width expanding">    
        <div class="off-canvas-overlay" data-toggle="sidebar"></div>
        <div class="sidebar-panel">       
          <?php $this->load->view('admin/includes/left-sidebar'); ?>
        </div> 
        <div class="app horizontal top_hader_dashboard">
              <?php $this->load->view('admin/includes/header'); ?>
        </div>      
        <form id="customer_list_frm" name="customer_list_frm">
          <div class="main-panel">
              
              <div class="min_height_dashboard"></div>
              
              <div class="main-content">
                <div class="content-view">
                  <div class="row m-b-1">
                    <div class="col-sm-4 pr-0">
                      <div class="bg_white back_line">  
                        <h4>Manage <?php echo $menu_label_alias['menu']['company']; ?> </h4> 
                      </div>
                    </div>
                    <div class="col-sm-8 pleft_0">
                      <div class="bg_white_filt">
                        <ul class="filter_ul">
                          <!-- <li>
                            <button class="new_filter_btn bulk" type="button">
                              <span class="bg_span"><img src="<?php echo assets_url()?>images/bulk_email.png"></span>
                              Bulk Email
                            </button>
                          </li> -->
                          <li>
                            <button class="new_filter_btn filter" type="button" id="filter_company_btn" data-toggle="modal" data-target="#companyFilterModal"><span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>Filters</button>
                          </li>
                          <li>
                            <?php if(is_method_available('customer','add')==TRUE){ ?>
                                <div class="filter_item new">
                                  <a class="new_filter_btn" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/add" id="add_new_customer">
                                    <span class="bg_span"><img src="<?php echo assets_url()?>images/add_com.png"/></span>
                                    Add <?php echo $menu_label_alias['menu']['company']; ?>
                                  </a>
                                </div>
                            <?php } ?>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>     
                  <div class="card">                  
                    <div class="card-block"> 
                                   
                      <div class="table-responsive-holder" style="position: relative;">
                        <h5 class="new-heading"><?php echo $menu_label_alias['menu']['company']; ?> List</h5>
                         
                        <a href="#" class="ext-table mb-10">
                          <svg aria-hidden="true" role="img" class="octicon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom;"><path fill-rule="evenodd" d="M8.177 14.323l2.896-2.896a.25.25 0 00-.177-.427H8.75V7.764a.75.75 0 10-1.5 0V11H5.104a.25.25 0 00-.177.427l2.896 2.896a.25.25 0 00.354 0zM2.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM6 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM12 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zm2.25.75a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5z"></path></svg>
                        </a>
                        <span id="selected_filter_div" class="full"></span>
                        <div class="wrapper1">
                            <div class="div1"></div>
                        </div>
                        <div class="table-toggle-holder">
                          <div id="show_for_select">
                            <?php //print_r($this->session->userdata('checked_customer_ids')); ?>               
                          </div>
                          <div id="show_for_unselect">
                            <!-- <span>All 6,017 conversations in Primary are selected.</span>
                            <a href="#" class="text-info">Clear selection</a> -->
                          </div>
                          <div class="table-full-holder">
                            <div class="table-one-holder">                
                              <table id="datatable" class="table new-table-style dataTable table-expand-customer company-table customer-view-table" style="width: 100%">
                                <thead>
                                  <tr>
                                    <th class="text-left">
                                      <div class="dropdown_new">
                                        <a href="#" class="all-secondary">
                                          <label class="check-box-sec">
                                          <input type="checkbox" value="all" name="user_all" class="user_all_checkbox">
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
                                    </th>
                                    <th class="text-left sort_order desc" data-field="id" data-orderby="asc">#ID</th>
                                    <th class="text-left sort_order" data-field="company_name" data-orderby="asc">                  
                                      <div class="dropdown tTitle">Company Name
                                       
                                      </div>
                                    </th>
                                    <th class="text-center sort_order" data-field="contact_person" data-orderby="asc">Contact Person</th>
                                    <th class="text-center sort_order" data-field="designation" data-orderby="asc">Designation</th>
                                    <th class="text-center sort_order" data-field="assigned_user_id" data-orderby="asc">
                                      Assigned to
                                      
                                    </th>
                                    <th class="text-center sort_order " data-field="leads" data-orderby="asc">
                                      Leads
                                      
                                    </th>
                                    <th class="text-center sort_order " data-field="orders" data-orderby="asc">
                                      Orders                            
                                    </th>
                                    
                                    <th class="text-left sort_order  auto-show hide" data-field="email" data-orderby="asc">Email</th>
                                    <th class="text-left sort_order  auto-show hide" data-field="mobile" data-orderby="asc">
                                      Mobile
                                    </th>
                                    <th class="text-center auto-show hide sort_order" data-field="address" data-orderby="asc">Address</th>
                                    <th class="text-center auto-show hide sort_order" data-field="country_id" data-orderby="asc">Country</th>
                                    <th class="text-center auto-show hide sort_order" data-field="state" data-orderby="asc">State</th>
                                    <th class="text-center auto-show hide sort_order" data-field="city" data-orderby="asc">City</th>
                                    <th class="text-center auto-show hide ">Zip</th>
                                    <th class="text-center auto-show hide">GST</th>
                                    <th class="text-center auto-show hide sort_order" data-field="create_date" data-orderby="asc">Created On</th>
                                    <th class="text-center auto-show hide sort_order" data-field="last_mail_sent" data-orderby="asc">Last Contacted On</th>
                                    <th class="text-center auto-show hide " >Reference</th>
                                    <th class="text-center">Action</th>
                                    
                                  </tr>
                                </thead>
                                <tbody id="tcontent"></tbody>
                              </table>
                            </div>  
                          </div>
                        </div>
                        <div class="row">
                          <input type="hidden" id="total_row_count" value="">
                                <div id="page_record_count_info" class="col-md-6 text-left ffff"></div>
                                <div id="page" style="" class="col-md-6 text-right custom-pagination"></div>
                            </div>
                            <?php                            
                           if(is_permission_available('download_customer_list_non_menu'))
                           {
                           ?>
                            <div class="row">
                              <div class="col-md-12">
                                <a class="new_filter_btn" href="JavaScript:void(0);" id="download_customer_csv">
                                <span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"/></span> Download Report  </a>
                              </div>
                            </div>
                            <?php
                           }                  
                           ?>
                            <input type="hidden" id="filter_search_str" value="<?php echo isset($_REQUEST['search_keyword'])?$_REQUEST['search_keyword']:''; ?>">
                            <input type="hidden" id="filter_sort_by" name="filter_sort_by" value="">
                            <input type="hidden" id="filter_created_from_date" name="filter_created_from_date" value="">
                            <input type="hidden" id="filter_created_to_date" name="filter_created_to_date" value="">
                            <input type="hidden" id="filter_assigned_user" name="filter_assigned_user" value="">
                            <input type="hidden" id="filter_by_company_available_for" name="filter_by_company_available_for" value="">
                            <input type="hidden" id="filter_by_is_available_company_name" name="filter_by_is_available_company_name" value="">
                            <input type="hidden" id="filter_by_is_available_email" name="filter_by_is_available_email" value="">
                            <input type="hidden" id="filter_by_is_available_phone" name="filter_by_is_available_phone" value="">
                            <input type="hidden" id="filter_last_contacted" name="filter_last_contacted" value="">
                            <input type="hidden" id="filter_last_contacted_custom_date" name="filter_last_contacted_custom_date" value="">
                            <input type="hidden" id="filter_country" name="filter_country" value="">
                            <input type="hidden" id="filter_company_type" name="filter_company_type" value="">
                            <input type="hidden" id="filter_by_source" value="">
                            <input type="hidden" id="filter_business_type_id" value="">
                            
                            
                      </div>
                    </div>                
                  </div>
                </div>
              </div>

              <div class="content-footer">
                <?php $this->load->view('admin/includes/footer'); ?>
              </div>
          </div>
        </form>
    </div>
    <?php $this->load->view('admin/includes/modal-html'); ?>
    <?php $this->load->view('admin/includes/app'); ?> 
  </body>
</html>
<div id="companyFilterModal" class="modal fade in default_filter" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h2>Filters <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
         </div>
         <div class="modal-body">
            <div class="f_holder">
              <div class="form-group">
        <ul>
          <li><div class="title_f">Added on</div></li>
          <li>
          <div class="input-prepend input-group">
            <span class="add-on input-group-addon">
            <img src="<?php echo assets_url()?>images/calendar.png"/>
            </span>
            
            <input type="text" class="form-control drp search_inp display_date" name="created_from_date" id="created_from_date" placeholder="Enquiry Date" value="" />
          </div>
          </li>
          <li><div class="title_f">To</div></li>
          <li>
          <div class="input-prepend input-group">
            <span class="add-on input-group-addon">
             <img src="<?php echo assets_url()?>images/calendar.png" />
            </span>
            
            <input type="text" class="form-control drp search_inp display_date" name="created_to_date" id="created_to_date" placeholder="Enquiry Date" value="" />
          </div>
          </li>
        </ul>
        </div>
              <div class="form-group">
                  <ul>
                    <li><div class="title_f" >By User</div></li>
                    <li>
                      <select name="assign_to" id="assign_to" >
                        <option value="">--Select--</option>
                        <?php if(count($user_list)){ ?>
                          <?php foreach($user_list AS $user){ ?>
                            <option value="<?php echo $user['id']; ?>" data-text="Assigned to <?php echo $user['name']; ?>"><?php echo $user['name']; ?></option>
                          <?php } ?>
                        <?php } ?>
                      </select>
                    </li>
                    
                    <?php /* ?>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="A" class="" name="company_available_for" data-text="Export & Domestic">
                        <span class="checkmark"></span>
                      </label>
                      Export &amp; Domestic
                    </li>
                    <?php */ ?>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="E" class="" name="company_available_for" data-text="Export Only">
                        <span class="checkmark"></span>
                      </label>
                      Export
                    </li>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="D" class="" name="company_available_for" data-text="Domestic Only">
                        <span class="checkmark"></span>
                      </label>
                      Domestic
                    </li>
                  </ul>
                </div>

              <div class="form-group">
                <ul class="two_part">
            <li>
              <label class="check-box-sec radio-box">
                <input type="radio" value="Y" name="is_available_company_name" data-text="With Company Name">
                <span class="checkmark"></span>
              </label>
              With Company Name
            </li>
            <li>
              <label class="check-box-sec radio-box">
                <input type="radio" value="N" name="is_available_company_name" data-text="Without Company Name">
                <span class="checkmark"></span>
              </label>
              Without Company Name
            </li>
          </ul>
          <ul class="two_part">
            <li>
              <label class="check-box-sec radio-box">
                <input type="radio" value="Y" name="is_available_email" data-text="With Email">
                <span class="checkmark"></span>
              </label>
              With Email
            </li>
            <li>
              <label class="check-box-sec radio-box">
                <input type="radio" value="N" name="is_available_email" data-text="Without Email">
                <span class="checkmark"></span>
              </label>
              Without Email
            </li>
          </ul>
          <ul class="two_part">
            <li>
              <label class="check-box-sec radio-box">
                <input type="radio" value="Y" name="is_available_phone" data-text="With Phone">
                <span class="checkmark"></span>
              </label>
              With Phone
            </li>
            <li>
              <label class="check-box-sec radio-box">
                <input type="radio" value="N" name="is_available_phone" data-text="Without Phone">
                <span class="checkmark"></span>
              </label>
              Without Phone
            </li>
          </ul>
        </div>

                  <div class="form-group">
                    <ul>
                      <li><div class="" >Business Type</div></li>
                      <li>
                        <select name="business_type_id" id="business_type_id" >
                        <option value="">Select Business Type</option>
                          <?php 
                          foreach($cus_business_type_list as $row)
                          {
                          ?>
                          <option value="<?php echo $row['id'];?>" data-text="Business Type: <?php echo $row['name'];?>"><?php echo $row['name'];?></option>
                          <?php
                          }
                          ?>                          
                        </select>
                      </li>        
                    </ul>
                    <?php /* ?>
                    <ul>
                      <li><div class="title_f" >Last Contacted</div></li>
                      <li>
                        <select name="last_contacted" id="last_contacted" >
                          <option value="">--Select--</option>
                          <option value="15" data-text="Last Contacted: Brfore 15 Days">Before 15 Days</option>
                          <option value="30" data-text="Last Contacted: Brfore 30 Days">Before 30 Days</option>
                          <option value="60" data-text="Last Contacted: Brfore 60 Days">Before 60 Days</option>
                          <option value="custom_date" data-text="">Custom Date</option>
                        </select>
                      </li>  
                      <li class="custom_date_li" style="display: none;"><div class="title_f">Before</div></li>
                      <li class="custom_date_li" style="display: none;">
                      <div class="input-prepend input-group">
                        <span class="add-on input-group-addon">
                        <img src="<?php echo assets_url()?>images/calendar.png"/>
                        </span>           
                        <input type="text" class="form-control drp search_inp display_date" name="last_contacted_custom_date" id="last_contacted_custom_date" data-text="Last Contacted: Before" placeholder="Select Date" value="" />
                      </div>
                      </li>                  
                    </ul>
                    <?php */ ?>
                  </div>
                  <div class="form-group">
                    <ul>
                      <li><div class="title_f" >By Country</div></li>
                      <li>                      
                        <select name="country" id="country" multiple>
                          <option value="">--Select--</option>
                          <?php if(count($country_list)){ ?>
                            <?php foreach($country_list AS $country){ ?>
                              <option value="<?php echo $country['id']; ?>" data-text="<?php echo $country['name']; ?>" data-name="<?php echo $country['name']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </li>
                      <li style="font-size:11px !important;">
                        <label class="check-box-sec radio-box"">
                          <input type="radio" value="PC" class="" name="company_type" data-text="Paying <?php echo $menu_label_alias['menu']['company']; ?>">
                          <span class="checkmark"></span>
                        </label>
                        Paying <?php echo $menu_label_alias['menu']['company']; ?>
                      </li>
                      <li style="font-size:11px !important;">
                        <label class="check-box-sec radio-box"">
                          <input type="radio" value="FC" class="" name="company_type" data-text="Free <?php echo $menu_label_alias['menu']['company']; ?>">
                          <span class="checkmark"></span>
                        </label>
                        Free <?php echo $menu_label_alias['menu']['company']; ?>
                      </li>
                      <li style="font-size:11px !important;">
                        <label class="check-box-sec radio-box"">
                          <input type="radio" value="BC" class="" name="company_type" data-text="Blacklist <?php echo $menu_label_alias['menu']['company']; ?>">
                          <span class="checkmark"></span>
                        </label>
                        Blacklisted
                      </li>
                    </ul>
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
                              <input type="checkbox" value="all" name="user_all" class="filter_source_all_checkbox" >
                              <span class="checkmark"></span>
                              </label>
                            </a>
                            <div class="dropdown">
                              <button class="btn-all dropdown-toggle" type="button" id="dropdownMenuFilterSource" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                None
                              </button>
                              <div class="dropdown-menu left" aria-labelledby="dropdownMenuFilterSource">
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
                                <input type="checkbox" value="<?php echo $source->id; ?>" name="by_source" class="user_checkbox"  data-text="<?php echo ($source->alias_name)?$source->alias_name:$source->name;?>">
                                <span class="checkmark"></span>
                                </label>
                                <span class="cname"><?php echo ($source->alias_name)?$source->alias_name:$source->name;?></span>
                            </li>
                            <?php } ?>
                            <?php } ?>
                          </ul>
                        </div>             
                      </div>                  
                    </div>
                </div>
                <div class="filter_aaction">
                  <button type="button" class="custom_blu btn btn-primary" id="com_filter">Search</button>
                  <button type="button" class="custom_blu btn btn-primary" id="com_filter_reset">Reset</button>
                </div>
            </div>
         </div>
      </div>
   </div>
</div>


<div id="bulk_mail_pop" class="bulk-mail">
  <form id="bulk_mail_frm" name="bulk_mail_frm">
    <div>
      
      <div class="bulk-header">
        <ul class="bHolder">
          <li><a href="#" class="bulk-mini"></a></li>
          <li><a href="#" class="bulk-full"></a></li>
          <li>
            <a href="#" class="bulk-close"><img src="<?php echo assets_url()?>images/close_window.png"/>"></a>
          </li>
        </ul>
        <?php /* ?>   
        <ul class="option-bulk">
              <li>
                <select class="bulk-select">
                  <option>Last Mail Send</option>
                  <option>>5</option>
                  <option>All</option>
                </select>
              </li>
              <li>
                <select class="bulk-select">
                  <option>Country List</option>
                  <option>India</option>
                  <option>All</option>
                </select>
              </li>    
            </ul>
            <?php */ ?>
      </div>

      <div class="bulk-others">
        <div class="bulk-sender">
          <label class="flabel active">To</label>
          <input type="text" class="bulk_to_new ani-txt active" name="bulk_email_to_email" id="bulk_email_to_email" value="" placeholder="" data-content="Recipients" data-to="To" readonly="true"> <a href="JavaScript:void(0)" class="text-info bulk_email_to_email_show" ><i class="fa fa-bars" aria-hidden="true"></i></a>
          <div id="show_selected_to_email"></div>
        </div>
        <div class="bulk-sender">
          <label class="flabel active">From</label>
          <input type="text" class="bulk_to_new ani-txt active" name="bulk_email_from_email" id="bulk_email_from_email" value="<?php echo $this->session->userdata['admin_session_data']['email']; ?>" data-content="Your Email" data-to="From" readonly="true">
        </div>
        <div class="bulk-sender">
          <input type="text" class="" name="bulk_email_subject" id="bulk_email_subject" value="" placeholder="Subject">
        </div>
        <div class="bulk-body">
          <textarea placeholder="Type your comment" name="bulk_email_body" id="bulk_email_body" class="bulkEmail-wysiwyg-editor" ></textarea>
        </div>
        <div class="bulk-footer">
          <ul>
            <li>
              <a href="JavaScript:void(0);" class="bulk-send-btn custom_blu" id="bulk_email_submit_confirm">Send</a>
            </li>
            <li>
              <div class="btn-group dropup">
                <button type="button" class="btn custom_blu" id="bulk_email_test_submit_confirm">Send Test Mail</button>
                <button type="button" class="btn custom_blu dropdown-toggle dropdown-toggle-split" id="mail_to_test_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" id="mail_to_test_div">
                  <div class="bulk-sender with-border">
                  <span>To</span>
                  <input type="text" class="bulk_to" name="bulk_email_to_email_test" id="bulk_email_to_email_test" value="" placeholder="Enter Email Id">
                </div>
                </div>
              </div>
            </li>
            <li>
              <label class="bulk-attach" for="bulk_attach">
                <input type="file" name="bulk_attach" id="bulk_attach">
                <span><i class="fa fa-paperclip" aria-hidden="true"></i></span>
              </label>
              <div class="del_pdf_file" style="display: none;">
                <span>Maxbridge Solutions LLP.pdf</span>
                <a href="#" class="del_pdf_new"><i class="fa fa-trash" aria-hidden="true"></i></a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </form>
</div>
<style>
table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting{
    padding-right:10px !important;
  }
table.dataTable thead .sorting::after,
table.dataTable thead .sorting_asc::after {
    content: "";
}

table.dataTable thead .sorting_desc::after {
    content: "";
}

table.dataTable thead .sorting::before,
table.dataTable thead .sorting_asc::before {
    content: "";
}

table.dataTable thead .sorting_desc::before {
    content: "";
}
</style>

<!-- end page scripts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<?php /* ?>
<!-- <script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script> -->
<!-- <script src="<?php echo base_url();?>assets/js/common_functions.js"></script> -->
<!-- <script src="<?=base_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script> -->
<!-- <script src="<?php echo base_url();?>vendor/datatables/media/js/jquery.dataTables.js"></script>    -->
<!-- <script src="<?php //echo base_url();?>vendor/datatables/media/js/dataTables.bootstrap4.js"></script> -->
<?php */ ?>
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="<?php echo assets_url();?>js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="<?php echo assets_url();?>css/bootstrap-multiselect.css" type="text/css"/>

<!-- <script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script> -->
<script type="text/javascript">
  $(document).ready(function() {
    //ext-table
    $('select#country').multiselect();
    // var pw = getW(9);
    // $('table.dataTable.company-table thead > tr > th:nth-child(1)').attr('width', pw);
    // var pw2 = getW(27);
    // $('table.dataTable.company-table thead > tr > th:nth-child(2)').attr('width', pw2);
    // var pw3 = getW(9);
    // $('table.dataTable.company-table thead > tr > th:nth-child(3)').attr('width', pw3);
    // var pw4 = getW(9);
    // $('table.dataTable.company-table thead > tr > th:nth-child(4)').attr('width', pw4);
    // var pw5 = getW(14);
    // $('table.dataTable.company-table thead > tr > th:nth-child(5)').attr('width', pw5);
    // var pw6 = getW(22);
    // $('table.dataTable.company-table thead > tr > th:nth-child(6)').attr('width', pw6);
    // var pw7 = getW(10);
    // $('table.dataTable.company-table thead > tr > th:nth-child(7)').attr('width', pw7);
 //   <div class="table-toggle-holder">
  // <div class="table-full-holder">
  // <div class="table-one-holder">
  //////
  $('input.ani-txt').on('focus', function(){
    var gt = $(this).attr('data-to');
    //alert(gt)
    $(this).addClass('active');
      $(this).parent().find('label.flabel').addClass('active').html(gt);
    });
    $('input.ani-txt').on('blur', function(){
    var gt = $(this).attr('data-content');
      if($(this).val() === '') {
        console.log('gt: '+gt);
        $(this).removeClass('active');
         $(this).parent().find('label.flabel').removeClass('active').html(gt);
      }
    });
  ///////////////////////////////////
  $('input#bulk_attach').change(function(e) { 
        var geekss = e.target.files[0].name; 
        //alert(geekss + ' is the selected file.');
        $(this).parent().hide();
        $(this).parent().parent().find('.del_pdf_file span').text(geekss);
        $(this).parent().parent().find('.del_pdf_file').show();

    }); 
    $(document).on("click",".del_pdf_file .del_pdf_new",function(event) {
      event.preventDefault();
      $(this).parent().hide();
      $(this).parent().parent().find('.bulk-attach').show();
      $(this).parent().parent().find('.bulk-attach input').val('');
  });
  //////////////
  
  
  
  ///
  
  ///////////////////////////////////
  var getPw = $('.table-responsive-holder').innerWidth();
  //alert(pw);
    
    var wArray = [7,8,5,5,6,7,4,6,6,8,5,6,5,5,4,9]
    //alert(getPw);
    var parentW = getPw+(getPw/2);
    $('.wrapper1 .div1').css({'width':parentW});
    $('.table-full-holder').css({'width':getPw});
    //$('.table-one-holder').css({'width':getPw});
    //$('.table-two-holder').css({'width':getPw/2});
    $(document).on("click",".ext-table",function(event) {
      event.preventDefault();
      //var getW = window.innerWidth;
      //alert("click: "+getW);
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $('.wrapper1').hide();
        //$(this).find('.fa').removeClass('fa-long-arrow-left').addClass('fa-long-arrow-right');
        $('.table-toggle-holder').find('.auto-show').addClass('hide');
          $('.table-full-holder').css({'width':'100%'});
          $('.table-toggle-holder').removeClass('scroll');
        $(".wrapper1")
            .scrollLeft(0);
        
      }else{
        $(this).addClass('active');
        $('.wrapper1').show();
        //$(this).find('.fa').removeClass('fa-long-arrow-right').addClass('fa-long-arrow-left');
        $('.table-toggle-holder').find('.auto-show').removeClass('hide');
        /////////////////////////////////////////
        $('table.dataTable.new-table-style thead > tr > th').each(function( index ) {
          var getWid = getPercentW(wArray[index]);
          //console.log(index+'> '+wArray[index])
          //$(this).attr('width', getWid);
      });
        /////////////////////////////////////////
        $('.table-full-holder').css({'width':parentW});
        $('.table-toggle-holder').addClass('scroll');
        $('.table-toggle-holder, .wrapper1').stop( true, true ).
            animate({
              scrollLeft: parentW
            }, 500, function() {
              //$('.media-grid-child').addClass('scroll-active');
            });
      }
      
      
      //$('.table-toggle-holder').scrollLeft(parentW);;
  });
    ///////
    $(".wrapper1").scroll(function(){
        $(".table-toggle-holder")
            .scrollLeft($(".wrapper1").scrollLeft());
    });
    $(".table-toggle-holder").scroll(function(){
        $(".wrapper1")
            .scrollLeft($(".table-toggle-holder").scrollLeft());
    });
    ///////
  getPercentW = function(per){
      var getPN = $('.table-responsive-holder').innerWidth();
      var fixW = (getPN/100)*per;
      return fixW;
    }
  $(document).on("click",".ext-table2",function(event) {
      event.preventDefault();
     $("div").scrollLeft(100);      
  });
    
    $(document).on('click', '.dropdown-menu', function (e) {
      e.stopPropagation();
  });
    
    $("body").on("click",".fav-icon",function(e){
      $(this).toggleClass("active");
  });
    //check_all_company
    // $("#check_all_company").change(function () {
    //  $('input:checkbox[name=company_name]').prop('checked', $(this).prop("checked"));
    // });
    /*
    $(".user_all_checkbox").change(function () {
    $('.dropdown_new .check-box-sec').removeClass('same-checked');
    
    if($(this).prop("checked") == true){
      $('#dropdownMenuUser').html('All');
    }else{
      $('#dropdownMenuUser').html('None');
    }
      $('input:checkbox[name=company_name]').prop('checked', $(this).prop("checked"));
  });
  

  $("body").on("click",".cAll",function(e){
    e.preventDefault();
    $('#dropdownMenuUser').html('All');
    $('input:checkbox[name=company_name], .user_all_checkbox').prop('checked',true);
  });
  $("body").on("click",".uAll",function(e){
    e.preventDefault();
    $('#dropdownMenuUser').html('None');
    $('.dropdown_new .check-box-sec').removeClass('same-checked');
    $('input:checkbox[name=company_name], .user_all_checkbox').prop('checked',false);
  });

  $("input:checkbox[name=company_name]").change(function () {
      if ($('input:checkbox[name=company_name]').not(':checked').length == 0) {
        $('#dropdownMenuUser').html('None');
          $('.user_all_checkbox').prop('checked',true);
          $('.dropdown_new .check-box-sec').removeClass('same-checked');
      } else {
        $('#dropdownMenuUser').html('All');
          $('.user_all_checkbox').prop('checked',false);
          $('.dropdown_new .check-box-sec').addClass('same-checked');
      }
  });
  */    
});
</script>

<script type="text/javascript">
  /*
function confirm_delete(id) {
    swal({
      title: 'Are you sure?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: false
    }, function() {
      
      window.location.href="<?php //echo assets_url()?><?php //echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/delete/"+id;
    });
    return false;
  }
  */
</script>
<script src="<?php echo assets_url();?>js/custom/company/get.js?v=<?php echo rand(0,1000); ?>"></script>
<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url();?>js/custom/company/add_company_by_popup.js"></script>

