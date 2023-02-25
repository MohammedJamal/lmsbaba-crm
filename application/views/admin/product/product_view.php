<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>    
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

        <div class="main-panel">
            <div class="min_height_dashboard"></div>
            <div class="main-content">              
                <div class="content-view">
         <div class="card process-sec">
            <?php if(is_method_available('product','add')==TRUE){ ?>
            <div class="top-title">
               <h5 class="lead_board col-md-8 mh"><?php echo $menu_label_alias['menu']['product']; ?> List</h5>
               <div class="col-md-4 pad-ri mh"><a class="pull-right" href="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/add"><button type="button" class="btn btn-primary m-r-xs m-b-xs btn-round-shadow mb-0 mt-14 ss">Add <?php echo $menu_label_alias['menu']['product']; ?>
                  </button></a>
               </div>
            </div>
            <?php } ?>
            <div class="card-box table-responsive card-block list_view" id="table_view">
            
          <div class="filter_holder">
              <span id="selected_filter_div"></span>
             <ul class="nav nav-tabs" id=status_tab>
                <li class="active">
                  <a href="javascript:void(0)" data-status="0">Approved (<span id="approved_count_div"><?php echo $product_count['approved_count']; ?></span>)</a>
                </li>
                <li><a href="javascript:void(0)" data-status="1">Disabled (<span id="disabled_count_div"><?php echo $product_count['disabled_count']; ?></span>)</a></li>
              </ul>
              
              <div class="filter_right">
                <div class="filter_block">
                  <div class="filter_item" id="filter_list">
                    <div class="dropdown bulk_dd">
                      <?php if(is_permission_available('edit_product_non_menu')){ ?>
                      <button class="new_filter_btn bulk dropdown-toggle" type="button" data-toggle="dropdown">
                        <span class="bg_span"><img src="<?php echo assets_url()?>images/bulk_upload_new.png"/></span>
                        Bulk Update
                      </button>
                      <?php } ?>
                      <ul class="dropdown-menu">
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="with_gst">With GST</a></li>
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="without_gst">Without GST</a></li>
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="with_code">With Product Code</a></li>
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="without_code">Without Product Code</a></li> 
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="with_hsn">With HSN Code</a></li>
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="without_hsn">Without HSN Code</a></li>                        
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="with_price">With Selling Price</a></li>
                        <li><a href="JavaScript:void(0);" class="bulk_update" data-id="without_price">Without Selling Price</a></li>                        
                      </ul>
                    </div>
                  </div>

                  <div class="filter_item" id="filter_grid">
                    <div id="filter_dropdown" class="dropdown filter_dd">
                      <button class="new_filter_btn filter dropdown-toggle" type="button" id="filter_btn"><span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>Filters</button>
                        
                    </div>
                  </div>

                  <div class="filter_item" id="filter_grid">
                    <div id="filter_dropdown" class="dropdown filter_dd">
                      <a href="JavaScript:void(0);" class="upload_excel upload_csv new_filter_btn">
                        <span class="bg_span lg-w">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M144 480C64.47 480 0 415.5 0 336C0 273.2 40.17 219.8 96.2 200.1C96.07 197.4 96 194.7 96 192C96 103.6 167.6 32 256 32C315.3 32 367 64.25 394.7 112.2C409.9 101.1 428.3 96 448 96C501 96 544 138.1 544 192C544 204.2 541.7 215.8 537.6 226.6C596 238.4 640 290.1 640 352C640 422.7 582.7 480 512 480H144zM223 263C213.7 272.4 213.7 287.6 223 296.1C232.4 306.3 247.6 306.3 256.1 296.1L296 257.9V392C296 405.3 306.7 416 320 416C333.3 416 344 405.3 344 392V257.9L383 296.1C392.4 306.3 407.6 306.3 416.1 296.1C426.3 287.6 426.3 272.4 416.1 263L336.1 183C327.6 173.7 312.4 173.7 303 183L223 263z"/></svg>
                        </span> Bulk Uploads 
                      </a>                        
                    </div>
                  </div>

                </div>
                <div id="btnContainer">
                  <button class="btn active get_view" data-target="list"><i class="fa fa-bars"></i></button> 
                  <button class="btn get_view" data-target="grid"><i class="fa fa-th-large"></i></button>
                </div>
              </div>
          </div>
               
          <div class="acc_holder">
           <div class="tabContent" id="tabContent1">
              <div class="">
                  <table id="view_table" class="table product-list no-border">
          <thead id="thead_grid" style="display:none;">
                        <tr><th>#</th><th>#</th></tr>
                     </thead>
                     <thead id="thead_list">
                        <tr>
              <th class="sort_order desc" data-field="id" data-orderby="asc" >#ID</th>
              <th class="sort_order" data-field="name" data-orderby="asc">Product Name</th>
              <th class="sort_order" data-field="code" data-orderby="asc">Code</th>
              <th class="sort_order" data-field="price" data-orderby="asc">Sales Price</th>
              <th class="sort_order" data-field="unit" data-orderby="asc">Unit</th>
              <th class="sort_order" data-field="unit_type" data-orderby="asc">Unit Type</th>
              <th class="sort_order" data-field="gst_percentage" data-orderby="asc">GST</th>
              <th >Photo</th>
              <th>Brochure</th>
              <th width="15%" class="text-center">Action</th>
            </tr>
                     </thead>
                     <tbody id="tcontent" class="t-contant-img">
                        
                     </tbody>
                  </table>
                  <div id="page" style=""></div>
               </div>
                <input type="hidden" id="status" value="0">
                <input type="hidden" id="view_type" value="list">
        
                <input type="hidden" id="filter_search_str" value="<?php echo isset($_REQUEST['search_keyword'])?$_REQUEST['search_keyword']:''; ?>">
                <input type="hidden" id="filter_aproved" value="Y">
                <input type="hidden" id="filter_disabled" value="">
                <input type="hidden" id="filter_disabled_reason" value="">        
                <input type="hidden" id="filter_group_id" value="">
                <input type="hidden" id="filter_cate_id" value="">        
                <input type="hidden" id="filter_with_image" value="">
                <input type="hidden" id="filter_with_brochure" value="">
                <input type="hidden" id="filter_with_youtube_video" value="">
                <input type="hidden" id="filter_with_gst" value="">
                <input type="hidden" id="filter_with_hsn_code" value="">
                <input type="hidden" id="filter_sort_by" value="">
                <input type="hidden" id="filter_product_available_for" value="">        
                <input type="hidden" id="current_page_number" value="">
                <input type="hidden" id="page_number" value="1">
            </div>  
            <div class="tabContent" id="tabContent2">
              Tab Content 3
            </div> 
          </div>
               
            </div>
         </div>
      </div>
                
            </div>
            <div class="content-footer">
              <?php $this->load->view('admin/includes/footer'); ?>
            </div>
        </div>
    </div>
    <?php $this->load->view('admin/includes/modal-html'); ?>
    <?php $this->load->view('admin/includes/app.php'); ?> 
  </body>
</html>
<!-- Modal -->
<div id="productDetailsModal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">
               Product Name
            </h4>
         </div>
         <div class="modal-body">
            <div class="pro_pic">
               <div class="cycle-slideshow">
                  <img src="https://lmsbaba.com/dashboard/accounts/lmsportal/product/thumb/3908a9c74772cf47e6673d7a2ccc0aab.jpg">
                  <img src="https://lmsbaba.com/dashboard/accounts/lmsportal/product/thumb/2c56343f8acb5fb8ec1731de27aa97e2.jpg">
                  <div class="cycle-pager"></div>
               </div>
            </div>
            <div class="pro_con">
               <div class="pro_loop">Selling Price: INR - 8000.00</div>
               <div class="pro_loop">Unit: 1 Piece</div>
               <div class="pro_loop">GST: NA</div>
               <div class="pro_loop">HSN Code: AD12334</div>
               <div class="pro_loop">No. of Vender: <span>2</span></div>
            </div>
            <div class="pro_de">
               Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry. Lorem Ipsum Has Been The Industry's Standard Dummy Text Ever Since The 1500s
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<!-- edit video -->


<!-- photo model -->
<div id="productPhotoModal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <!--<h4 class="modal-title">Image</h4>-->

    </div>
         <div class="modal-body">
            <!-- <label class="phot_up" for="upload_edit">
               <div class="more">Edit Photo</div>
               <span><i class="fa fa-pencil" aria-hidden="true"></i></span>
               <input type="file" name="upload_edit" id="upload_edit">
            </label> -->
            <div class="modal_pic">
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<!--  ================ PRODUCT FILTER :START ================= -->
<div id="filterModal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h2>Filters Product <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
         </div>
         <div class="modal-body">
            <div class="f_holder">
              <form>
                <div class="form-group">
                  <ul>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="Y" name="aproved" data-text="Approved">
                        <span class="checkmark"></span>
                      </label>
                      Approved
                    </li>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="Y" name="disabled" id="disabled" data-text="Disabled">
                        <span class="checkmark"></span>
                      </label>
                      Disabled
                    </li>
                    <li>
                      <select name="disabled_reason" id="disabled_reason" disabled="true">
                        <option value="">Select Reason</option>
                        <?php if(count($disabled_reason_list)){ ?>
                        <?php foreach($disabled_reason_list as $k=>$v){ ?>
                          <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </li>
                  </ul>
                </div>
                <div class="form-group">
                  <ul>
                    <li>
                      <div class="title_f">By Group</div>
                    </li>
                    <li>
                      <select name="group_id" id="group_id">
                        <option value="">Select Group</option>
                      </select>
                    </li>
                    <li>
                      <select name="cate_id" id="cate_id">
                        <option value="">Select Category</option>
                      </select>
                    </li>
                  </ul>
                </div>
                <div class="form-group">
                  <ul>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="A" class="" name="product_available_for" data-text="Export & Domestic">
                        <span class="checkmark"></span>
                      </label>
                      Export & Domestic
                    </li>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="E" class="" name="product_available_for" data-text="Export Only">
                        <span class="checkmark"></span>
                      </label>
                      Export Only
                    </li>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="D" class="" name="product_available_for" data-text="Domestic Only">
                        <span class="checkmark"></span>
                      </label>
                      Domestic Only
                    </li>
                  </ul>
                </div>
                <div class="form-group">
                  <ul>
                    
                    
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="Y" name="with_gst" data-text="With GST">
                        <span class="checkmark"></span>
                      </label>
                      With GST
                    </li>
                    <li>
                      <label class="check-box-sec">
                        <input type="checkbox" value="Y" name="with_hsn_code" data-text="With HSN Code">
                        <span class="checkmark"></span>
                      </label>
                      With HSN Code
                    </li>
                  </ul>
                </div>

                <div class="form-group">
                  <ul class="two_part">
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="Y" name="with_image" data-text="With Image">
                        <span class="checkmark"></span>
                      </label>
                      With Image
                    </li>
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="N" name="with_image" data-text="Without Image">
                        <span class="checkmark"></span>
                      </label>
                      Without Image
                    </li>
                  </ul>
                  <ul class="two_part">
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="Y" name="with_brochure" data-text="With PDF Brochure">
                        <span class="checkmark"></span>
                      </label>
                      With PDF Brochure
                    </li>
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="N" name="with_brochure" data-text="Without PDF Brochure">
                        <span class="checkmark"></span>
                      </label>
                      Without PDF Brochure
                    </li>
                  </ul>
                  <ul class="two_part">
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="Y" name="with_youtube_video" data-text="With Video">
                        <span class="checkmark"></span>
                      </label>
                      With Video
                    </li>
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="N" name="with_youtube_video" data-text="Without Video">
                        <span class="checkmark"></span>
                      </label>
                      Without Video
                    </li>
                  </ul>
                </div>

                <div class="form-group">
                  <ul>
                    <li><div class="title_f">Sort By</div></li>
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="P_L_M" name="sort_by" class="sort_by" data-text="Sort By: Last Modified">
                        <span class="checkmark"></span>
                      </label>
                      Last Modified
                    </li>
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="P_L_TO_H" name="sort_by" class="sort_by" data-text="Sort By: Price Low to High">
                        <span class="checkmark"></span>
                      </label>
                      Price Low to High
                    </li>
                    <li>
                      <label class="check-box-sec radio-box">
                        <input type="radio" value="P_H_TO_L" name="sort_by" class="sort_by" data-text="Sort By: Price High to Low">
                        <span class="checkmark"></span>
                      </label>
                      Price High to Low
                    </li>
                  </ul>
                </div>
                <div class="filter_aaction">
                  <button type="button" class="custom_blu btn btn-primary" id="product_filter">Search</button>
                  <button type="button" class="custom_blu btn btn-primary" id="product_filter_reset">Reset</button>
                </div>
              </form>
            </div>
         </div>
         
      </div>
   </div>
</div>
<!--  ================ PRODUCT FILTER :END ================= -->

<!--  ================ PRODUCT FILTER :START ================= -->
<div id="product_disabled_reason_modal_div" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h2>Disabled Reason</h2>
         </div>
         <div class="modal-body">
            <div class="f_holder">
              <form>
                <?php if(count($disabled_reason_list)){ ?>
                <div class="form-group">
                  
                    <?php foreach($disabled_reason_list as $k=>$v){ ?>
                      <ul class="two_part row">
                        <li class="col-md-12">
                          <label class="check-box-sec radio-box">
                            <input type="radio" value="<?php echo $k; ?>" name="status_disabled_reason" id="status_disabled_reason">
                            <span class="checkmark"></span>
                          </label>
                          <?php echo $v; ?>
                        </li>
                      </ul>
                    <?php } ?>                   
                  
                </div>
                <?php } ?>
                <div class="filter_aaction">
                  <button type="button" class="custom_blu" id="product_disabled_reason_btn">Done</button>
                </div>
              </form>
            </div>
         </div>
         
      </div>
   </div>
</div>
<!--  ================ PRODUCT FILTER :END ================= -->

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
              <il>Please <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/product/download_lead_upload_sample" target="_blank"><b class="text-primary"><u>Click Here</u></b></a> to see the sample of csv.</il>
              </ul>
              <ul>
              <?php
                // --------------------------------------------------------------------------------------------      
                $category_str ='';
                $category_str .='<ol>';
                if(count($category_list))
                {
                  foreach($category_list AS $category)
                  {
                    $category_str .='<li class="copy_cat" data-title="Category ID" data-id="'.$category['id'].'">'.$category['name'].' <b><i>(ID: '.$category['id'].', Group: '.$category['group_name'].')</i></b></li>';
                  }
                } 
                $category_str .='</ol>';
                // --------------------------------------------------------------------------------------------
                // --------------------------------------------------------------------------------------------
                $currency_str ='';
                $currency_str .='<ol>';
                if(count($currency_list))
                {
                  foreach($currency_list AS $currency)
                  {
                    $currency_str .='<li class="copy" data-title="Currency Code">'.$currency->code.'</li>';
                  }
                } 
                $currency_str .='</ol>';
                // --------------------------------------------------------------------------------------------                
                // --------------------------------------------------------------------------------------------
                $unit_type_str ='';
                $unit_type_str .='<ol>';
                if(count($unit_type_list))
                {
                  foreach($unit_type_list AS $unit_type)
                  {
                    $unit_type_str .='<li class="copy" data-title="Unit Type">'.$unit_type['name'].'</li>';
                  }
                } 
                $unit_type_str .='</ol>';
                // --------------------------------------------------------------------------------------------
              ?>              
              <il>Please do not use any <b>comma (,)</b> seperator at the CSV fields</il>
              <li>Please do not edit or delete any columns of heading (e.g. A1 row)</li>
              <li>
                <b>Required Fields<span class="text-danger">*</span>:</b> 
                <ul>
                  <li>(*) category: (<b class="text-primary">Click the below Category text to copy</b>)
                      <?php echo $category_str; ?>
                  </li>
                  <li>(*) name</li>
                  <li>(*) currency_code (<b class="text-primary">Click the below Currency code text to copy</b>)
                      <?php echo $currency_str; ?>
                  </li>
                  <li>(*) unit_type (<b class="text-primary">Click the below Unit Type text to copy</b>)
                      <?php echo $unit_type_str; ?>
                  </li>
                  <li>(*) description</li>
                  <li>(*) price</li>
                  <li>(*) unit (For Ex.,1)</li> 
                  <li>(*) product_available_for (For Ex.,'E'- Export Only, 'D'- Domestic Only, 'A' - All)</li>                                  
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

<div id="over_top" class="dd_overlay"></div>

<!-- <div id="over_left" class="dd_overlay"></div>
<div id="over_right" class="dd_overlay"></div>
<div id="over_bottom" class="dd_overlay"></div> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css"/>
<script src="<?=assets_url();?>vendor/pace/pace.js"></script>
<script src="<?=assets_url();?>scripts/tether.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="<?=assets_url();?>scripts/moment.min.js"></script>
<script src="<?=assets_url();?>vendor/fastclick/lib/fastclick.js"></script>
<script src="<?=assets_url();?>scripts/constants.js"></script>
<script src="<?=assets_url();?>scripts/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<!-- endbuild -->

<!-- page scripts -->
<script src="<?php echo assets_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>
<!-- endbuild -->
<script src="<?php echo assets_url();?>vendor/datatables/media/js/jquery.dataTables.js"></script>
<!-- page scripts -->
<script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<!-- end page scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js.map"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js.map"></script>
<!-- initialize page scripts -->
<script src="<?php echo assets_url();?>scripts/jquery.cycle2.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/custom/product/get.js"></script>
<style type="text/css">
    .copy,.copy_cat {cursor: copy;}

</style>
<script type="text/javascript">
$(document).ready(function() {   
  
  var copy = document.querySelectorAll(".copy"); 
  for (const copied of copy) {     
    copied.onclick = function() { 
      document.execCommand("copy");  
      
    };  
    copied.addEventListener("copy", function(event) { 
      event.preventDefault(); 
      if (event.clipboardData) {           
        event.clipboardData.setData("text/plain", copied.textContent);
        swal(copied.getAttribute('data-title')+": '"+event.clipboardData.getData("text")+"' copied!"); 
      };
    });
  };

  var copy = document.querySelectorAll(".copy_cat"); 
  for (const copied of copy) {     
    copied.onclick = function() { 
      document.execCommand("copy");  
      
    };  
    copied.addEventListener("copy", function(event) { 
      event.preventDefault(); 
      if (event.clipboardData) {           
        event.clipboardData.setData("text/plain", copied.getAttribute('data-id'));
        swal(copied.getAttribute('data-title')+": '"+copied.getAttribute('data-id')+"' copied!"); 
      };
    });
  };
});
</script>
