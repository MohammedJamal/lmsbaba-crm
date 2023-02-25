<div class="">
    <fieldset class="tsf-content">
        <div class="col-lg-12">
            <legend style="padding-bottom: 12px; pro-pro-text">Provide Product Details </legend>
        </div>
        <form method="post" enctype="multipart/form-data" action="<?=base_url().$this->session->userdata['admin_session_data']['lms_url']?>/product/add_ajax" name="product_form" id="lead_add_product_form">
            <!-- BEGIN STEP CONTENT-->
            <div class="tsf-step-content tsf_stape_content">
                <div class="col-lg-12 col-sm-12 col-md-0">
                    <div class="row">
                        <div class="col-md-7 col-sm-5 col-lg-5">
                            <div class="form-group">
                                <label>Name*</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name?>" />
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-2 col-lg-4 ">
                            <div class="form-group">
                                <label>Product Code</label>
                                <input type="text" class="form-control" name="code" value="<?php echo $code?>"  placeholder="Product Code" />
                            </div>
                        </div>
                    </div>
                       <div class="row">
                        <div class="col-md-3 col-sm-6 col-lg-3 ">
                            <div class="form-group">
                                <label>Currency*</label>
                                <select class="form-control" name="currency_type" id="currency_type">
                                    <?php  foreach($currency_list as $currency_data) { 
                                        if($currency_data->id=='1'){?>
                                            <option value="<?=$currency_data->id;?>">
                                                <?=$currency_data->code;?>
                                            </option>
                                        <?php } 
                                    }  ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3 ">
                            <div class="form-group">
                                <label>Sale Price*</label>
                                <input type="text" max="10" class="form-control" name="price" id="price" placeholder="Sale Price" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3 ">
                            <div class="form-group">
                                <label>Unit*</label>
                                <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3 ">
                            <div class="form-group">
                                <label>Unit Type*</label>
                                <select class="form-control" name="unit_type" id="unit_type">
                                    <option value="">Select</option>
                                    <?php foreach($unit_type_list as $unit_type_data) { ?>
                                        <option value="<?=$unit_type_data->id;?>">
                                            <?=$unit_type_data->type_name;?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12 ">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="description" placeholder="Description" style="height: 58px;"></textarea>
                                Total word count: <span id="display_count">0</span> words. Words left: <span id="word_left">50</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-9 col-lg-12">
                                <div class="col-md-5 col-sm-5 col-lg-5">
                                    <div id="" class="form-group">
                                        <label>Product Image</label>
                                        <span class="btn btn-success btn-icon fileinput-button m-b-1">
                                            <i class="material-icons">add</i>
                                            <span>Select Image</span>
                                            <input id="fileupload_image" type="file" name="image_files[]">
                                        </span>
                                        <div id="PreviewPicture" style="display: none;">
                                            <span class="del_pic"><i class="fa fa-trash" area-hidden="true" onclick="remove_image()"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-7 col-lg-7">
                                    <div class="form-group">
                                        <label>PDF Brochure</label>
                                        <span class="btn btn-success btn-icon fileinput-button m-b-1">
                                            <i class="material-icons">add</i>
                                            <span>Select PDF file...</span>
                                            <input id="fileupload_pdf" type="file" name="pdf_files[]">
                                        </span>
                                        <div id="PreviewPdf" style="height:160px;display:none;background-image:url(<?php echo assets_url().'images/pdf.webp'?>);">
                                            <span class="del_pdf"><i class="fa fa-trash" area-hidden="true" onclick="remove_pdf()"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                   <button type="button" class="btn btn-default btn-md vendor-btn" onclick="select_vendor_product_lead()">Select Vendors</button>
                                </div>
                            </div> -->
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="tsf-controls ">
                                    <button type="button" id="add-product-for-lead-submit" class="btn btn-right tsf-wizard-btn">Add Product </button>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="vdsList"></div>
                    </div>
                </div>
            </div>
            <!-- END STEP CONTENT-->
            <input type="hidden" id="isSelectVendors" value="empty">
            <input type="hidden" id="vendors" name="vendors" value="">
        </form>
    </fieldset>
</div>  
<script type="text/javascript">
$(document).ready(function() {
    $("#description").on('keyup', function() {
        var words = this.value.match(/\S+/g).length;
        if (words > 50) {
            // Split the string on first 200 words and rejoin on spaces
            var trimmed = $(this).val().split(/\s+/, 50).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count').text(words);
            $('#word_left').text(50-words);
        }
    });
 });
</script> 