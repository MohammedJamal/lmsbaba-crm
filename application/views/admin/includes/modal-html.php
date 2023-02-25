<style type="text/css">
.hide-opa{
    display: none !important;
}
</style>
<?php /* ?>
<div id="vendor_tagged_product_add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <form id="frmVendorAdd" name="frmVendorAdd" onsubmit="return false;">
            <input type="hidden" name="v_product_varient_id" id="v_product_varient_id" value="<?php echo $edit_id; ?>">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title heading-text-hd">Add new vendor and tagged for <span class="text-danger pname" >"<?php echo $product_name; ?>"</span></h4>
                </div>

                <div class="card-block" id="prod_add_body">

                    <h5 class="vendor-text-hd">Vendor Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" name="v_company_name" id="v_company_name" class="form-control">
                                <div class="error_label" id="v_company_name_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Contact Person</label>
                            <input type="text" name="v_contact_person" id="v_contact_person" class="form-control">
                            <div class="error_label" id="v_contact_person_error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="v_designation" id="v_designation" class="form-control">
                                <div class="error_label" id="v_designation_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Mobile</label>
                            <input type="text" name="v_mobile" id="v_mobile" class="form-control only_natural_number" maxlength="10">
                            <div class="error_label" id="v_mobile_error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="v_email" id="v_email" class="form-control">
                                <div class="error_label" id="v_email_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Address</label>
                            <input type="text" name="v_address" id="v_address" class="form-control">
                            <div class="error_label" id="v_address_error"></div>
                        </div>
                    </div>

                    <h5 class="vendor-text-hd">Vendor's cost for <span class="text-danger pname">"<?php echo $product_name; ?>"</span></h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vendor_id">Price</label>
                                <input type="text" max="10" class="form-control double_digit" name="v_price" id="v_price" placeholder="Price" />
                                <div class="error_div" id="v_price_error"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vendor_id">Currency</label>
                                <select class="form-control" name="v_currency_type" id="v_currency_type">
                                    <option value="">Select</option>
                                    <?php
                          foreach($currency_list as $currency_data)
                          {
                              ?>
                                        <option value="<?=$currency_data->id;?>">
                                            <?=$currency_data->name;?>(
                                                <?=$currency_data->code;?>)</option>
                                        <?php
                          }
                          ?>
                                </select>
                                <div class="error_div" id="v_currency_type_error"></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vendor_id">Unit</label>
                                <input type="text" max="10" class="form-control only_natural_number" name="v_unit" id="v_unit" placeholder="Unit" />
                                <div class="error_div" id="v_unit_error"></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vendor_id">Unit type</label>
                                <select class="form-control" name="v_unit_type" id="v_unit_type">
                                    <option value="">Select</option>
                                    <?php
                          foreach($unit_type_list as $unit_type_data)
                          {
                              ?>
                                        <option value="<?=$unit_type_data->id;?>">
                                            <?=$unit_type_data->type_name;?>
                                        </option>
                                        <?php
                          }
                          ?>
                                </select>
                                <div class="error_div" id="v_unit_type_error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <div class="tsf-controls " style="display: block;">
                                <button type="button" class="btn btn-primary btn-round-shadow btn-right btn-padding" id="vendor_add_submit">SUBMIT</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer" style="display:none;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php */ ?>

<!--  ==================== START ============================ -->

<div id="prod_lead" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <input type="hidden" id="opp_id" value="">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!-- <h4 class="modal-title heading-text-hd">Following Products Are Available</h4> -->
                <h4 class="modal-title"> Search Products</h4>
                <hr>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab1_q" role="tab"><strong>Search By Keyword</strong></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab2_q" role="tab"><strong>Search By Category</strong></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1_q" role="tabpanel">
                        <div class="form-group">
                            <input type="text" class="default-input product_name_q" placeholder="Enter Product Name" name="" id="product_name_q">
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <button id="" class="btn btn-default btn-primary w-100 search_product_by_keyword_q" data-searchtype="keyword">Search</button>
                            </div>                    
                        </div>
                    </div>
                    <div class="tab-pane" id="tab2_q" role="tabpanel">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <select id="search_p_group_q" name="search_p_group_q" class="default-select">
                                   <option value="">--Select Group--</option></select>
                            </div>
                            <div class="col-md-6">
                                <select id="search_p_category_q" name="search_p_category_q" class="default-select">
                                   <option value="">-- Select Category --</option></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <button id="" class="btn btn-default btn-primary w-100 search_product_by_keyword_q" data-searchtype="category">Search</button>
                            </div>                     
                        </div>
                    </div>              
                </div>
                <div id="err_prod" class="alert alert-danger no_display">Please select a product</div>
                <?php /* ?>
                <div class="row">
                    <div class="col-md-10 pr-0">
                        <div class="form-group leads-label-text">
                            <!-- <label>Search</label> -->
                            <input  type="text" class="form-control search_product_by_keyword" placeholder="Enter Product Name">
                        </div>
                    </div>
                    <div class="col-md-2 pl-0">
                        <div class="form-group">
                            <button id="search_product_by_keyword" class="btn btn-default btn-primary">Search</button>
                        </div>
                    </div>
                </div>
                <?php */ ?>
                <div class="form-group row" id="selected_product_div"></div>
            </div>
            <div class="" id="prod_lead_list">
                <!-- <div class="card-block">
                    <div class="no-more-tables">
                        <table id="" class="table table-striped m-b-0">
                            <thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" align="center"><h3 class="no-found-text">No products found!</h3></td>
                                    </tr>
                                </tbody>
                            </thead>
                        </table>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<div id="prod_lead_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <input type="hidden" id="u_opp_id" value="">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"> Search Products</h4>
                <hr>
                <div id="err_prod" class="alert alert-danger no_display">Please select a product</div>
                <div class="row">
                    <div class="col-md-10 pr-0">
                        <div class="form-group leads-label-text">
        
                            <input  type="text" class="form-control update_search_product_by_keyword" placeholder="Enter Product Name">
                        </div>
                    </div>
                    <div class="col-md-2 pl-0">
                        <div class="form-group">
                            <button id="update_search_product_by_keyword" class="btn btn-default btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" id="prod_lead_list_update">
            </div>
        </div>
    </div>
</div>

<!-- <div id="prod_lead_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Following Products Are Available
            <div>
                <small>
                    <span class="text-danger">Product not available?</span> 
                    <a href="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/redirect_url/add">Click to add product</a>
                </small>
            </div>
        </h4>
                <div id="err_prod_update" class="alert alert-danger no_display">Please select a product</div>
            </div>
            <div class="modal-body" id="prod_lead_list_update"></div>
            <div class="modal-footer">
                <div class="text-left">
                    <small>
                <span class="text-danger">Product not available?</span> 
                <a href="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/redirect_url/add">Click to add product</a>
            </small>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div> -->

<!--  ======================== END ======================== -->

<!--  ======================== START ======================== -->
<div id="quotation_list" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Following Proposal(s) Are Available</h4>

            </div>
            <div class="modal-body" id="quotation_list_all"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ======================== END ======================== -->

<!--  ======================= CUSTOMER EDIT - START ======== -->
<div id="edit_customer_view_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-header-info lead_renewal_success_info_div" style="display: none;" id="lead_info_div">
                    A new lead has been added successfully and the lead id is <span id="added_lead_id"></span>
                </div>
                <div class="modal-header-info lead_renewal_success_info_div" style="display: none;" id="renewal_success_div">
                    A new renewal/AMC has been added successfully.
                </div>
                <button type="button" class="close close-edit-customer-view-modal-new">&times;</button>               
                <h4 class="modal-title" id="edit_customer_view_rander_title">Buyer’s Company Details</h4>
            </div>
            <div class="modal-body box-details mt-10px" id="edit_customer_view_rander"></div>
<!--
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
-->
        </div>

    </div>
</div>

<!--  ======================= CUSTOMER EDIT - END ========== -->

<!--  ======================= ORIGINAL QUOTATION - START ======== -->
<div id="original_quotation_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buying Requirements</h4>
            </div>
            <div class="modal-body" id="original_quotation_view_rander"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!--  ======================= ORIGINAL QUOTATION - END ========== -->

<!--  ============ QUOTATION SEND TO BUYER:START ================= -->
<div id="qutation_send_to_buyer_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Quotation</h4>

            </div>
            <div class="modal-body" id="qutation_send_to_buyer_body"></div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>

    </div>
</div>


<div id="bulk_qutation_send_to_buyer_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Quotation</h4>

            </div>
            <div class="modal-body" id="bulk_qutation_send_to_buyer_body"></div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>

    </div>
</div>
<!--  ================ QUOTATION SEND TO BUYER:END ================= -->

<!--  ============ Additional charges for quotation :START ================= -->
<div id="additional_charges_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title heading-text-hd">Additional Charges List</h4>

            </div>
            <div class="modal-body" id="additional_charges_list_body" style="max-height: 300px;overflow-y: scroll;"></div>
            <div class="modal-footer">&nbsp;
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>

    </div>
</div>
<!--  ================ Additional charges for quotation :END ================= -->

<!--  ============ LEAD ASSIGNE CHANGE:START ================= -->
<div id="lead_assigne_to_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assigne To</h4>

            </div>
            <div class="modal-body" id="lead_assigne_to_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="company_assigne_to_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!-- <h4 class="modal-title">Assigne To</h4> -->

            </div>
            <div class="modal-body" id="company_assigne_to_body"></div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>

    </div>
</div>
<!--  ================ LEAD ASSIGNE CHANGE:END ================= -->
<!--  ============ MY DOCUMENT POPUP:START ================= -->
<div id="my_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Document List</h4>
            </div>
            <div class="modal-body" id="my_document_list_popup"></div>
            <!--<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>-->
        </div>

    </div>
</div>
<!--  ================ MY DOCUMENT POPUP:END ================= -->

<!--  ============ CHANGE USER/EMPLYEE PASSWORD:START ================= -->
<div id="change_password_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Password</h4>

            </div>
            <div class="modal-body" id="">
                <form name="changePasswordForm" id="changePasswordForm" method="post">
                    <div>
                        <!-- MESSAGE START -->
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" id="error_msg_div" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div id="error_msg"></div>
                        </div>

                        <div class="alert alert-success alert-dismissible fade in" role="alert" id="success_msg_div" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div id="success_msg"></div>
                        </div>

                        <!-- MESSAGE END -->

                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Username/Employee ID:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="" id="" placeholder="" value="#<?php echo $this->session->userdata['admin_session_data']['user_id']; ?>" readonly="true" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Password:</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="emp_password" id="emp_password" placeholder="Password" />
                                <div class="error_div" id="emp_password_error"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Confirm Password:</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="emp_confirm_password" id="emp_confirm_password" placeholder="Confirm Password" />
                                <div class="error_div" id="emp_confirm_password_error"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 text-right">
                                <input type="button" value="Submit" class="btn btn-primary" id="change_password_submit_confirm">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ CHANGE USER/EMPLYEE PASSWORD:END ================= -->

<!--  ============ CHANGE USER/EMPLYEE USERNAME:START ================= -->
<div id="change_username_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Username</h4>

            </div>
            <div class="modal-body" id="">
                <form name="changeUsernameForm" id="changeUsernameForm" method="post">                    
                    <div>
                        <!-- MESSAGE START -->
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" id="emp_username_error_msg_div" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div id="emp_username_error_msg"></div>
                        </div>

                        <div class="alert alert-success alert-dismissible fade in" role="alert" id="emp_username_success_msg_div" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div id="emp_username_success_msg"></div>
                        </div>

                        <!-- MESSAGE END -->
                        

                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Username:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control copy_paste_validate no_space_validate" name="emp_username" id="emp_username" placeholder="Enter Username" maxlength="20" />
                                
                            </div>
                        </div>   

                        <div class="form-group row">
                            <div class="col-sm-12 text-right">
                                <input type="button" value="Submit" class="btn btn-primary" id="change_username_submit_confirm">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ CHANGE USER/EMPLYEE USERNAME:END ================= -->



<!--  ============ COMPANY DETAILS:START ================= -->
<div id="lead_company_details" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Company Details</h4>

            </div>
            <div class="modal-body" id="lead_company_details_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ COMPANY DETAILS:END ================= -->

<!--  ============ COMPANY DETAILS:START ================= -->
<div id="company_history_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title heading-text-hd">Company History</h4>

            </div>
            <div class="modal-body" id="company_history_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ COMPANY DETAILS:END ================= -->

<!-- ADD SOURCE -->
<div id="add_source_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Source</h4>

            </div>
            <div class="modal-body" id="">
                <form name="AddSourceForm" id="AddSourceForm" method="post">
                    <div>
                        <!-- MESSAGE START -->
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" id="error_msg_div" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div id="error_msg"></div>
                        </div>

                        <div class="alert alert-success alert-dismissible fade in" role="alert" id="success_msg_div_source" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div id="success_msg_source"></div>
                        </div>

                        <!-- MESSAGE END -->

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Source:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="source" id="source" placeholder="Source Name.." value="" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 text-right">
                                <input type="button" value="Submit" class="btn btn-primary" id="add_source_submit_confirm">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!--  ============ COMPANY DETAILS:START ================= -->
<div id="vendor_details_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vendor Details</h4>

            </div>
            <div class="modal-body" id="vendordetailsbody">dfgdf</div>
<!--
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
-->
        </div>

    </div>
</div>
<!--  ================ COMPANY DETAILS:END ================= -->

<!-- ============ LEAD HISTORY:START ================= -->
<div id="lead_history_log_modal" class="modal fade" role="dialog" z-index="1051">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="lead_history_log_title">Lead Details</h4>

            </div>
            <div class="modal-body" id="lead_history_log_body"></div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>

    </div>
</div>
<!--  ================ LEAD HISTORY:END ================= -->
<!--  ============ REGRET REASON:START ================= -->
<div id="lead_regret_reason_list_modal" class="modal fade" role="dialog" style="z-index:9999 !important; background-color:#ccc !important;">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="client_not_interested_close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="">Lead Regret Reasons</h4>

            </div>
            <div class="modal-body" id="lead_regret_reason_list_body"></div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button> -->
                <button type="button" class="btn btn-default" id="reason_select_cinfirm">Ok</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ REGRET REASON:END ================= -->

<!--  ====== PO UPLOAD: START =========== -->
<?php /* ?>
<div id="po_upload_modal" class="modal fade upload-oder" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- Modal content-->
        <form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
            <input type="hidden" name="po_lead_opp_id" id="po_lead_opp_id" value="">
            <input type="hidden" name="po_lead_id" id="po_lead_id" value="">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">PO Upload for <span id="opp_title"></span></h4>
                </div>
                <div class="modal-body add-prod" id="prod_add_body">
                    <div class="general_update_textera">

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="col-sm-3 col-form-label">Upload PO<span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control-file" name="po_upload_file" id="po_upload_file">
                                    <div class="text-danger" id="po_upload_file_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right " id="">
                                <h5 class="text-danger">Deal Value: <span id="deal_value_div"></span></h5>
                            </div>

                        </div>
                        <!-- <div>&nbsp;</div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="inputPassword" class="col-sm-4 col-form-label">CC to Employee<span class="text-danger">*</span></label>
                                            <div class="col-sm-8 select-uplod-popup">
                                                <?php if(count($user_list)){?>
                                                    <select class="form-control input-sec select2" id="po_upload_cc_to_employee" name="po_upload_cc_to_employee[]" multiple>
                                                        <?php foreach($user_list as $user){ ?>
                                                            <option value="<?php echo $user->email; ?>">
                                                                <?php echo $user->email .'( Emp ID: '.$user->id.')'; ?>
                                                            </option>
                                                            <?php } ?>
                                                    </select>
                                                    <?php } ?>
                                                        <div class="text-danger" id="po_upload_cc_to_employee_error"></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="check-box-ar ff">
                                            
                                            <label class="check-box-sec">
                                                <input class="styled-checkbox" id="styled-checkbox-1" type="checkbox" value="Y" name="po_upload_sent_ack_to_client" id="po_upload_sent_ack_to_client">
                                                <span class="checkmark"></span>
                                              </label>
                                            Send Acknowledgement to client
                                            
                                        </div>
                                        <div class="error_label" id="po_upload_sent_ack_to_client_error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div>&nbsp;</div> -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">PO Number<span class="text-danger">*</span></label>
                                    <div class="col-sm-8 select-uplod-popup">
                                        <input type="text" name="po_number" id="po_number" class="form-control input-sec">
                                        <div class="text-danger" id="po_number_error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">PO Date<span class="text-danger">*</span></label>
                                    <div class="col-sm-8 select-uplod-popup">
                                        <input type="text" name="po_date" id="po_date" class="form-control input-sec datepicker_display_format" value="<?php echo date('d-M-Y'); ?>" readonly="true">
                                        <div class="text-danger" id="po_date_error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div>&nbsp;</div> -->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Describe Comments<span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" id="po_upload_describe_comments" name="po_upload_describe_comments"></textarea>
                                <div class="text-danger" id="po_upload_describe_comments_error"></div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="org-btn btn btn-primary btn-round-shadow" id="po_upload_submit">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                </div>
            </div>
        </form>
    </div>
</div>
<?php */ ?>
<!-- ======== PO UPLOAD: END ============= -->

<!--  ============ COMPANY WISE LEAD LIST:START ================= -->
<div id="company_wise_lead_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title heading-text-hd">Company Wise Lead</h4>

            </div>
            <div class="modal-body" style=" height:400px;overflow-y: scroll;" id="company_wise_lead_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ COMPANY WISE LEAD LIST:END ================= -->

<!--  ============ MAIL SEND TO COMPANY:START =================  -->
<div id="send_mail_to_company" class="modal fade" role="dialog">
    <form name="frmSendMailToCompany" id="frmSendMailToCompany" enctype="multipart/form-data">
        <div class="modal-dialog modal-md modal_margin_top">
            <input type="hidden" id="customer_id" name="customer_id" value="">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send mail to Company</h4>

                </div>
                <div class="modal-body" id="">
                    <div class="row">
                        <div class="col-md-2 text-right"><b>To:</b></div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="com_to_email" name="com_to_email" value="" readonly="true" />
                            <div class="error_div text-danger" id="com_to_email_error"></div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="col-md-2 text-right"><b>From:</b></div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="com_from_email" name="com_from_email" value="<?php echo $this->session->userdata['admin_session_data']['email']; ?>" readonly="true" />
                            <div class="error_div text-danger" id="com_from_email_error"></div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="col-md-2 text-right"><b>Subject:</b></div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="com_mail_subject" name="com_mail_subject" value="" />
                            <div class="error_div text-danger" id="com_mail_subject_error"></div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="col-md-2 text-right"><b>Body:</b></div>
                        <div class="col-md-10">
                            <textarea class="form-control" id="com_mail_body" name="com_mail_body" rows="2"></textarea>
                            <div class="error_div text-danger" id="com_mail_body_error"></div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="col-md-2 text-right"><b>Attachment:</b></div>
                        <div class="col-md-10">
                            <input type="file" class="form-control" id="com_attachment" name="com_attachment" />
                            <div class="error_div text-danger" id="com_mail_subject_error"></div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="col-md-12 text-right"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="mail_send_to_company_confirm">Send</button>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- ================ MAIL SEND TO COMPANY:END =================  -->

<!--  ============ MAIL SEND TO COMPANY:START =================  -->
<div id="send_mail_to_vendor" class="modal fade" role="dialog">
    <form name="frmSendMailToVendor" id="frmSendMailToCompany" enctype="multipart/form-data">
        <div class="modal-dialog modal-md modal_margin_top vendor-popup">
            <input type="hidden" id="vdr_id" name="vdr_id" value="">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send mail to Vendor</h4>

                </div>
                <div class="modal-body" id="">
                   
                    <div class="row">
                       <div class="box-details">
                        <div class="form-group row">
                        <div class="col-md-2 text-right"><b>To:</b></div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="vdr_to_email" name="vdr_to_email" value="" readonly="true" />
                            <div class="error_div text-danger" id="vdr_to_email_error"></div>
                        </div>
                         </div>
                          <div class="form-group row">
                        <div class="col-md-2 text-right"><b>From:</b></div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="vdr_from_email" name="vdr_from_email" value="<?php echo $this->session->userdata['admin_session_data']['email']; ?>" readonly="true" />
                            <div class="error_div text-danger" id="com_from_email_error"></div>
                        </div>
                        </div>
                      <div class="form-group row">
                        <div class="col-md-2 text-right"><b>Subject:</b></div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="vdr_mail_subject" name="vdr_mail_subject" value="" />
                            <div class="error_div text-danger" id="vdr_mail_subject_error"></div>
                        </div>
                        </div>
                           <div class="form-group row">
                        <div class="col-md-2 text-right"><b>Body:</b></div>
                        <div class="col-md-10">
                            <textarea class="form-control" id="vdr_mail_body" name="vdr_mail_body" rows="2"></textarea>
                            <div class="error_div text-danger" id="vdr_mail_body_error"></div>
                        </div>
                           </div>
                        <div class="form-group row">
                        <div class="col-md-2 text-right"><b>Attachment:</b></div>
                        <div class="col-md-10">
                            <input type="file" class="form-control" id="vdr_attachment" name="vdr_attachment" />
                            <div class="error_div text-danger" id="vdr_mail_subject_error"></div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-2 text-right"></div>
                        <div class="col-md-10">
                           <button type="button" class="btn btn-primary text-right" id="mail_send_to_vendor_confirm">Send</button> 
                        </div>
                      </div>
                         
                        
                    </div>
                    </div>
                </div>
<!--
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   
                </div>
-->
            </div>

        </div>
    </form>
</div>

<!-- ================ MAIL SEND TO COMPANY:END =================  -->

<!-- ============ LEAD PRODUCT ADD:START ================= -->
<div id="lead_add_product_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="lead_add_product_title">Add Product</h4>
            </div>
            <div class="modal-body" id="add_product_for_lead_body"></div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>

    </div>
</div>
<!--  ================ LEAD PRODUCT ADD :END ================= -->

<!-- ============ LEAD PRODUCT VENDORS SELECT:START ================= -->
<div id="lead_select_vendors_modal" class="lead_select_vendors_modal modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="lead_select_vendors_title">Select Vendors</h4>
            </div>
            <div class="modal-body">
                <form id="AddVdrFrom">
                    <input type="hidden" id="vdraddfromProductId" name="product_id">
                    <input type="hidden" id="vdraddfromVendors" name="vendors">
                </form>
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group leads-label-text">
                            <input type="text" class="form-control search_vendor_by_keyword" placeholder="Search Vendor">
                        </div>
                    </div>
                </div>
                <div id="select_vendors_for_product_lead_body"></div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>
<!--  ================ LEAD PRODUCT VENDORS SELECT :END ================= -->

<!--  ============ PRODUCT DETAILS:START ================= -->
<div id="product_details" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Product Details</h4>

            </div>
            <div class="modal-body" id="product_details_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ COMPANY DETAILS:END ================= -->


<!--  ================ PRODUCT DESCRIPTION VIEW:START ================= -->
<div id="productContentsModal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title" id="productContentsModalTitle">Edit Product</h4>
         </div>
         <div class="modal-body" id="product_edit_rander">
            
         </div>
      </div>
   </div>
</div>
<!--  ================ PRODUCT DESCRIPTION VIEW:END ================= -->

<!--  ================ PRODUCT YOUTUBE VIDEO VIEW:START ================= -->
<div id="productVideoModal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">
               Product Video
            </h4>
         </div>
         <div class="modal-body">
            <!-- <label class="phot_up" for="upload_edit">
               <div class="more">Edit Video</div>
               <span><i class="fa fa-pencil" aria-hidden="true"></i></span>
            </label> -->
            <div class="video_holder"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<!--  ================ PRODUCT YOUTUBE VIDEO VIEW:END ================= -->

<!--  ================ GROUP ADD VIEW:START ================= -->
<div id="add_group_cat_modal_view" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title" id="add_group_cat_title"></h4>
         </div>
         <div class="modal-body" id="add_group_cat_view_div">
            
        </div>
         <div class="modal-footer text-center">
         <button type="button" class="custom_blu btn btn-primary submit_group_confirm" id="submit_group_confirm">Save</button>
            <button type="button" class="custom_blu btn btn-primary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<!--  ================ GROUP ADD VIEW:END ================= -->

<!--  ================ BULK PRODUCT UPDATE VIEW:START ================= -->
<div id="bulk_product_update_modal_div" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <!-- Modal content-->
      <div class="modal-content" >
         <div class="modal-header">
             <button type="button" class="close bulk_update_view_close" >&times;</button> 
            <h4 class="modal-title">Product List</h4>
         </div>
         <div class="modal-body" id="bulk_product_update_html_rander" style="max-height: 400px;overflow-y: scroll;" ></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default bulk_update_view_close">Close</button>
         </div>
      </div>
   </div>
</div>
<!--  ================ BULK PRODUCT UPDATE VIEW:END ================= -->

<!--  ============ PRODUCT WISE VENDOR LIST:START ================= -->
<div id="product_wise_vendor_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vendor List</h4>

            </div>
            <div class="modal-body" id="product_wise_vendor_list_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ PRODUCT WISE VENDOR LIST:END ================= -->

<!--  UPDATE LEAD: START -->
<div id="update_lead_modal" class="modal fade" role="dialog" style="z-index:9998 !important">
    <div class="modal-dialog modal-lg modal_margin_top">
	
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Lead <span id="lead_title_div"></span></h4>

            </div>
            <div class="modal-body" id="update_lead_body"></div>
            <?php /* ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php */ ?>
        </div>

    </div>
</div>
<!--  UPDATE LEAD: END -->

<!--  ============ LEAD UPDATE MAIL SENT TO CLIENT MAIL SUBJECT CHANGE:START ================= -->
<div id="update_lead_mail_subject_change_modal" class="modal fade" role="dialog" style="z-index: 9999 !important; background-color: rgb(204, 204, 204) !important;">
    <div class="modal-dialog modal-lg modal_margin_top">
	
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Mail Subject <span id="lead_title_div"></span></h4>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="update_lead_mail_subject" id="update_lead_mail_subject" class="form-control" value="">
                            <div class="error_label text-danger" id="update_lead_mail_subject_error"></div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-primary" id="update_lead_mail_subject_update_confirm">Update</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ LEAD UPDATE MAIL SENT TO CLIENT MAIL SUBJECT CHANGE:END ================= -->

<!--  ============ LEAD UPDATE MAIL SENT TO CLIENT MAIL SUBJECT CHANGE:START ================= -->
<div id="regret_this_lead_mail_subject_change_modal" class="modal fade" role="dialog" style="z-index: 9999 !important; background-color: rgb(204, 204, 204) !important;">
    <div class="modal-dialog modal-lg modal_margin_top">
	
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Update Mail Subject <span id="lead_title_div"></span></h4>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="regret_this_lead_mail_subject_edit" id="regret_this_lead_mail_subject_edit" class="form-control" value="">
                            <div class="error_label text-danger" id="regret_this_lead_mail_subject_edit_error"></div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-primary" id="regret_this_lead_mail_subject_update_confirm">Update</button>
            </div>
        </div>

    </div>
</div>
<!--  ================ LEAD UPDATE MAIL SENT TO CLIENT MAIL SUBJECT CHANGE:END ================= -->

<!-- MAIL TRAIL VIEW -->
<div id="view_and_select_mail_trail_modal" class="modal fade" role="dialog" style="z-index:9998 !important">
    <div class="modal-dialog modal-lg modal_margin_top">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="view_and_select_mail_trail_modal_close">&times;</button>
                <h4 class="modal-title">Mail Trail History</h4>

            </div>
            <div class="modal-body" id="view_and_select_mail_trail_body"></div>
            <?php /* ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php */ ?>
        </div>

    </div>
</div>


<!--  ======================= GMAIL CUSTOMER ADD - START ======== -->
<div id="add_customer_view_modal" class="modal fade modal-fullscreen" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Customer</h4>
            </div>
            <div class="modal-body box-details mt-10px" id="add_customer_view_rander"></div>
<!--
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
-->
        </div>

    </div>
</div>

<!--  ======================= GMAIL CUSTOMER EDIT - END ========== -->

<!--  ======================= GMAIL LEAD ADD - START ======== -->
<div id="gmail_add_lead_view_modal" class="modal fade modal-fullscreen" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Lead</h4>
            </div>
            <div class="modal-body box-details mt-10px" id="gmail_add_lead_view_rander"></div>
<!--
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
-->
        </div>

    </div>
</div>

<!--  ======================= GMAIL LEAD EDIT - END ========== -->

<!-- ========================================================= -->
<!-- CREATE QUOTATION VIEW -->
<div class="modal fade" id="create_quotation_popup_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Quotation</h4>
      </div>
      <form id="form_upload_pdf">
          <div class="modal-body pl-30 pr-30">
            <div class="quotation-option">
                <div class="form-group">
                    <div class="background_blue">
                        <a id="generate_automated_quotation" class="custom-model-open" href="JavaScript:void(0);">
                            <b>Generate PDF Quotation</b>
                            <br />
                            <span class="blue-link">(Recomended)</span>
                        </a>
                    </div>
                </div>
                <div class="form-group">                
                    <label for="pdf_file" class="background_blue">
                        <input type="file" name="pdf_file" class="d-none" id="pdf_file" onchange="custom_quation_pdf_upload()">
                        <b>Send Custom Quotation</b>
                        <br>
                        <span class="blue-link">(PDF File Only)</span>
                    </label>
                </div>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade modal-big" id="automated_quotation_popup_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <!-- <h4 class="modal-title">Quotation</h4> -->
      </div>
      <div class="modal-body pl-30 pr-30" id="automated_quotation_popup_modal_body"></div>      
    </div>
  </div>
</div>
<div class="modal fade" id="select_quotation_photo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
       <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Add Photo</h4>
       </div>
       <form id="frmUploadPhotoToQuotation">
          <div class="modal-body pl-30 pr-30">
             <div class="quotation-option">
                <div class="form-group">
                   <div class="background_blue">
                        <a class="custom-model-open quotation_photo_my_document" href="JavaScript:void(0);">
                        <b>Select from My Document</b>
                        </a>
                   </div>
                </div>
                <div class="form-group">
                   <label for="photo" class="background_blue">
                   <input type="file" name="q_photo" id="photo" class="quotation_photo hide-opa"><b>Select from Computer</b></label>
                </div>
             </div>
          </div>
       </form>
    </div>
  </div>
</div>


<div class="modal fade" id="create_bulk_quotation_popup_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Quotation</h4>
      </div>
      <form id="form_upload_pdf_bulk">
          <div class="modal-body pl-30 pr-30">
            <div class="quotation-option">                
                <div class="form-group">                
                    <label for="pdf_file_bulk" class="background_blue">
                        <input type="file" name="pdf_file_bulk" class="d-none" id="pdf_file_bulk" onchange="custom_quation_pdf_upload_bulk()">
                        <b>Send Custom Quotation</b>
                        <br>
                        <span class="blue-link">(PDF File Only)</span>
                    </label>
                </div>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<!-- CREATE QUOTATION VIEW -->
<!-- ========================================================= -->


<!-- ========================================================= -->
<!-- DASHBOARD USER WISE PENDING LIST POPUP VIEW -->
<div class="modal fade clock-modal" id="pendingFollowupPopModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pending Follow-ups</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="dashboard_pending_followup_body"></div>
    </div>
  </div>
</div>
<!-- DASHBOARD USER WISE PENDING LIST POPUP VIEW -->
<!-- ========================================================= -->

<!-- ========================================================= -->
<!-- Unfollowed Leads By User POPUP VIEW -->
<div class="modal fade clock-modal" id="UnfollowedLeadsByUserPopModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Unfollowed Leads By User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="unfollowed_leads_by_user_body"></div>
    </div>
  </div>
</div>
<!-- Unfollowed Leads By User POPUP VIEW -->
<!-- ========================================================= -->

<!-- ========================================================= -->
<!-- Product Vs. Leads POPUP VIEW -->
<div class="modal fade clock-modal" id="ProductVsLeadsPopModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Product Vs. Leads</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="product_vs_leads_body"></div>
    </div>
  </div>
</div>
<!-- Product Vs. Leads POPUP VIEW -->
<!-- ========================================================= -->

<!-- ---------------------------- -->
<!-- QUOTATION MODAL -->
<div class="modal fade mail-modal" id="QuotationViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
<!-- QUOTATION MODAL -->
<!-- ---------------------------- -->
<!-- -------------------------------- -->
<!-- ------ADD NEW LEAD MODAL ---- -->

<div id="rander_add_new_lead_view_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Lead</h4>
    </div>
    <div class="modal-body" id="rander_add_new_lead_view_html"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
</div>

<!-- ------ADD NEW LEAD MODAL ---- -->
<!-- -------------------------------- -->

<!-- -------------------------------- -->
<!-- ------ADD NEW LEAD MODAL ---- -->

<div id="rander_add_new_company_view_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="rander_add_new_company_view_title">Add New Company</h4>
    </div>
    <div class="modal-body" id="rander_add_new_company_view_html"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
</div>

<!-- ------COMMON MODAL ---- -->
<!-- -------------------------------- -->
<div id="rander_common_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Company Details</h4>
            </div>
            <div class="modal-body" id="rander_common_view_modal_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div id="rander_common_view_modal_md" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title_md">Company Details</h4>
            </div>
            <div class="modal-body" id="rander_common_view_modal_html_md"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="rander_common_view_modal_lg" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title_lg"></h4>
            </div>
            <div class="modal-body" id="rander_common_view_modal_html_lg"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="rander_common_view_modal_sm" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title_sm">Company Details</h4>
            </div>
            <div class="modal-body" id="rander_common_view_modal_html_sm"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="rander_common_view_modal_full" class="modal fade modal-fullscreen header-blue" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/></svg>
                </a> 
                <h1 class="modal-title" id="common_view_modal_title_full"></h1>
            </div>   
            <div class="modal-body" id="rander_common_view_modal_html_full"></div>
        </div>
    </div>
</div>






<!-- ----------------- -->
<!-- PRO FORMA INVOICE -->
<div id="po_prod_lead" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- <input type="hidden" id="opp_id" value=""> -->
        <input type="hidden" id="is_pfi_or_inv" value="">
        <input type="hidden" id="po_selected_prod_id">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!-- <h4 class="modal-title heading-text-hd">Following Products Are Available</h4> -->
                <h4 class="modal-title"> Search Products</h4>
                <hr>
                <div id="po_err_prod" class="alert alert-danger no_display">Please select a product</div>
                <div class="row">
                    <div class="col-md-10 pr-0">
                        <div class="form-group leads-label-text">
                            <!-- <label>Search</label> -->
                            <input  type="text" class="form-control po_search_product_by_keyword" placeholder="Enter Product Name">
                        </div>
                    </div>
                    <div class="col-md-2 pl-0">
                        <div class="form-group">
                            <button id="po_search_product_by_keyword" class="btn btn-default btn-primary">Search</button>
                        </div>
                    </div>
                </div>
                <div class="form-group row" id="po_selected_product_div"></div>
            </div>
            <div class="" id="po_prod_lead_list">
                
            </div>
        </div>
    </div>
</div>
<div id="po_additional_charges_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title heading-text-hd">Additional Charges List</h4>

            </div>
            <div class="modal-body" id="po_additional_charges_list_body" style="max-height: 300px;overflow-y: scroll;"></div>
            <div class="modal-footer">&nbsp;
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>

    </div>
</div>
<!-- ----------------- -->
<!-- PRO FORMA INVOICE -->
<!-- -------------------- -->
<!-- WEB WHATSAPP POPUP -->
<form id="frmWebWhatsappSend">
    <div class="modal fade whatsapp-modal" id="WebWhatsappModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>

<!-- WEB WHATSAPP POPUP -->
<!-- -------------------- -->
<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form id="cust_reply_mail_frm" name="cust_reply_mail_frm">
  <div class="modal fade mail-modal" id="ReplyPopupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>

<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->

<!-- ---------------------------- -->
<!-- PO UPLOAD MODAL -->
<form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
  <div class="modal fade mail-modal modal-fullscreen" id="PoUploadLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
  <input type="hidden" id="is_back_show" value="Y">
</form>
<?php /* ?>
<div id="pro_forma_inv_send_to_buyer_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Proforma Invoice</h4>

            </div>
            <div class="modal-body" id="pro_forma_inv_send_to_buyer_body"></div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>

    </div>
</div>


<div id="invoice_send_to_buyer_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Invoice</h4>

            </div>
            <div class="modal-body" id="invoice_send_to_buyer_body"></div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>

    </div>
</div>
<?php */ ?>



  
<div id="PoPaymentLedgerModal" class="modal fade modal-fullscreen header-blue" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/></svg>
                </a> 
                <h1 class="modal-title">Payment Ledger</h1>
            </div>   
            <div class="modal-body" id="PoPaymentLedgerModalBody">
            </div>
        </div>
    </div>
</div>  
<!-- PO UPLOAD MODAL -->
<!-- ---------------------------- -->
<!-- ---------------------------- -->

<!-- ------------------------------------ -->
<!-- ------ CALL HISTORY POPUP MODAL ---- -->

<div id="rander_call_history_popup_action_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="call_history_popup_action_title"></h4>
    </div>
    <div class="modal-body" id="rander_call_history_popup_action_html"></div>
    <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>-->
  </div>
  </div>
</div>

<div id="rander_call_history_report_detail_modal" class="modal fade modal-fullscreen" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="call_history_report_detail_title"></h4>
    </div>
    <div class="modal-body" id="rander_call_history_report_detail_html"></div>
    <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>-->
  </div>
  </div>
</div>

<!-- ------ CALL HISTORY POPUP MODAL ---- -->
<!-- ------------------------------------ -->

<!-- -------------------- -->
<!-- -------------------------------- -->
<!-- ------ADD LEAD WISE PRODUCT/SERVICES TAGGED---- -->

<div id="rander_add_product_quote_view_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-md">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Product/Service Quote</h4>
    </div>
    <div class="modal-body" id="rander_add_product_quote_view_html"></div>
    <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div> -->
  </div>
  </div>
</div>

<!-- ------ADD LEAD WISE PRODUCT/SERVICES TAGGED ---- -->
<!-- -------------------------------- -->
<!-- ---------------------------- -->



<!-- ---------------------------------- -->
<!-- ---------------------------------- -->
<!-- ------SEARCH PRODUCT/SERVICES ---- -->
<input type="hidden" id="search_product_lead_id">
<input type="hidden" id="is_mail_or_whatsapp">
<input type="hidden" id="search_add_btn_class">
<div id="rander_search_product_view_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg modal_margin_top">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Search Product/Service</h4><hr>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><strong>Search By Keyword</strong></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#profile" role="tab"><strong>Search By Category</strong></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="home" role="tabpanel">
                <div class="form-group">
                    <input type="text" class="default-input" placeholder="Enter Product Name" name="search_p_name" id="search_p_name">
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <button id="search_product_by_name" class="btn btn-default btn-primary w-100 product_search_confirm" data-searchtype="keyword">Search</button>
                    </div>                     
                </div>
            </div>
            <div class="tab-pane" id="profile" role="tabpanel">
                <div class="form-group row">
                    <div class="col-md-6">
                        <select id="search_p_group" name="search_p_group" class="default-select">
                           <option value="">--Select Group--</option></select>
                    </div>
                    <div class="col-md-6">
                        <select id="search_p_category" name="search_p_category" class="default-select">
                           <option value="">-- Select Category --</option></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <button id="search_product_by_group" class="btn btn-default btn-primary w-100 product_search_confirm" data-searchtype="category">Search</button>
                    </div>                     
                </div>
            </div>              
        </div>
    </div>
    <div class="modal-body" id="rander_search_product_view_html"></div>
    <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div> -->
  </div>
  </div>
</div>

<!-- ------SEARCH PRODUCT/SERVICES ---- -->
<!-- ---------------------------------- -->
<!-- ---------------------------------- -->


<div id="template_variable_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="">Template Variable List</h4>
            </div>
            <div class="modal-body" id="">               
                  <?php                   
                  $get_template=get_template_variable_list();
                  $template_str ='';
                  $template_str .='<ol>';
                  if(count($get_template))
                  {
                    $last_variable_type='';
                     foreach($get_template AS $row)
                     {
                        $variable_type='';
                        switch ($row['variable_type']) {
                            case "buyer_details":
                                $variable_type="Buyer Details";
                                break;
                            case "lead_details":
                                $variable_type="Lead Details";
                                break;
                            case "user_details":
                                $variable_type="User Details";
                                break;
                            default:
                                $variable_type="Company";
                        }
                        if($last_variable_type!=$row['variable_type'])
                        {
                            $template_str .='</ol>';
                            $template_str .='<h5>'.$variable_type.' (Click the text to copy)</h5>';
                            $template_str .='<ol>';
                        }
                        $title=$variable_type.': '.$row['name'];
                        $template_str .='<li class="copy_template_variable" data-toggle="tooltip" data-placement="top" title="'.$title.'">'.$row['reserve_keyword'].'</li>';
                        $last_variable_type=$row['variable_type'];
                    }
                  } 
                  $template_str .='</ol>';
                  ?>                 
                  <?php echo $template_str; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- ---------------------------- -->
<!-- COMMON MAIL MODAL -->
<?php /* ?>
<form id="common_cust_reply_mail_frm" name="common_cust_reply_mail_frm">
  <div class="modal fade mail-modal" id="CommonReplyPopupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>
<?php */ ?>
<!-- COMMON MAIL MODAL -->
<!-- ---------------------------- -->
<!-- ================================== MEETING ============================ -->
<div class="modal fade calender-modal" id="scheduleMeetingModal" tabindex="-1" role="dialog"
   aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header blue-heading" id="schedule_meeting_title">Schedule New Meeting</div>
        <div class="modal-body" id="scheduleMeetingModalBody"></div>
      </div>
   </div>
</div>
<!-- ================================== MEETING ============================ -->

<div id="rander_quotation_product_rearrange_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-blue" data-dismiss="modal">Done</button>
                <h4 class="modal-title" id="">Re-arrange</h4>
            </div>
            <div class="modal-body" id="rander_quotation_product_rearrange_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-blue" data-dismiss="modal">Done</button>
            </div>
        </div>

    </div>
</div>

<div id="OmDetailModal" class="modal fade modal-fullscreen header-blue" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/></svg>
                </a> 
                <h1 class="modal-title">Order Details</h1>
            </div>   
            <div class="modal-body" id="OmDetailModalBody"></div>
        </div>
    </div>
</div> 

<div id="OmFormFieldsEditModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h1 class="modal-title" id="OmFormFieldsEditModal_title"></h1>
            </div>
            <div class="modal-body" id="OmFormFieldsEditModal_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="OmSplitModal" class="modal fade modal-fullscreen-- header-blue" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/></svg>
                </a> 
                <h1 class="modal-title">Split Order</h1>
            </div>   
            <div class="modal-body" id="OmSplitModalBody">
            </div>
        </div>
    </div>
</div> 


<div id="meetingReport" class="modal fade modal-fullscreen" role="dialog"></div>
<div id="dashboardDetailReport" class="modal fade modal-fullscreen" role="dialog"></div>
<script>
</script>

