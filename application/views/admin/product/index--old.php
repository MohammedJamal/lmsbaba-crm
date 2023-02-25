<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('include/header');?>

<!-- content panel -->
<div class="main-panel">
    <div class="min_height_dashboard"></div>
    <!-- main area -->
    <div class="main-content">
        <div class="content-view">
            <div class="card">
                <div class="">
                    <h5 class="lead_board col-md-8"></h5>
                </div>
                <div class="clear"></div>
                <!-- <h5 class="lead_board">Manage Products</h5> -->
                <h5 class="lead_board_title">
                    Manage Products
                    <div class="pull-right">
                        <a class="com-right custom_blu" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage">
                        View Products
                        </a>


                        <a class="com-right custom_blu" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/add">
                        Add Products
                        </a>
                    </div>
                </h5>
                <div class="card-block">
                    <div class="row">
                    <div class="col-md-9 company-total-color-text">
                      <div class="col-md-2 text-center">
                            <label class="orange-color-text text-left">Total <br>Products</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage"><?php echo $product_count['total_count']; ?></a>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="blue-color-text text-left">Products with <br>Single Price</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage/with_single_price"><?php echo $with_single_price?></a>
                        </div>
                        <div class="col-md-3 text-center">
                            <label class="green-color-text text-left">Products with<br>Vendors</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage/with_vendors"><?php echo $with_vendors?></a>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="pink-color-text text-left">Products with <br>Photo</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage/with_photo"><?php echo $with_photo?></a>
                        </div>
                        <div class="col-md-3 text-center">
                            <label class="light-blue-color-text text-left">Products with <br>Brochure</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage/with_brochure"><?php echo $with_brochure?></a>
                        </div>  
                        
                    </div>
                    <!-- <div class="col-md-3">
                     
                        
                        
                    </div> -->
                        
                        
                    </div>
                    <div class="row">
                        <form action="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage" method="post" accept-charset="utf-8">
                            <div class="col-md-12 mt-15">
                                <div class="form-group">
                                    <label class="search-company-text">Search Products</label>
                                    <input type="text" name="search_keyword" class="form-control" placeholder="Search by Product name/code ">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group company-search">
                                    <button type="submit" class="btn view-btn-blue">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- bottom footer -->
            <?php $this->load->view('include/footer');?>
                <!-- /bottom footer -->
        </div>
        <!-- /main area -->
    </div>
    <!-- /content panel -->
</div>
</body>
</html>