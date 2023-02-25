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
                <div class="card">
                  <div class="">
                      <h5 class="lead_board col-md-8"></h5>
                  </div>
                  <div class="clear"></div>
                  <h5 class="lead_board_title">
                    Manage Companies
                    <div class="pull-right">
                    <a class="com-right custom_blu" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/manage">
                    View Companies
                    </a>


                    <a class="com-right custom_blu" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/add" id="add_new_customer">
                    Add Company
                    </a>
                          </div>
                  </h5>
                  <div class="card-block">
                    <div class="row">
                          <div class="col-md-9 company-total-color-text">
                            <div class="col-md-2 text-center">
                              <label class="orange-color-text text-left">Total <br>Companies</label><hr>
                              <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/manage"><?php echo $total_companies?></a>
                            </div>
                            <div class="col-md-2 text-center">
                              <label class="blue-color-text text-left">Paying <br> Companies</label><hr>
                              <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/manage/paying_companies"><?php echo $paying_companies?></a>
                            </div>
                            <div class="col-md-3 text-center">
                              <label class="green-color-text text-left">Domestic<br> Companies</label><hr>
                              <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/manage/domestic_companies"><?php echo $domestic_companies?></a>
                            </div>
                            <div class="col-md-2 text-center">
                              <label class="pink-color-text text-left">Foreign <br>Companies</label><hr>
                              <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/manage/foreign_companies"><?php echo $foreign_companies?></a>
                            </div>
                            <div class="col-md-3 text-center">
                              <label class="light-blue-color-text text-left">Companies <br> With Repeat Order</label><hr>
                              <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/manage/repeat_order">0</a>
                            </div>
                          </div>
                    </div>
                    <div class="row">
                        <form action="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/manage" method="post" accept-charset="utf-8">
                                <div class="col-md-12 mt-15">
                                    <div class="form-group">
                                        <label class="search-company-text">Search Companies</label>
                                        <input type="text" name="search_keyword" class="form-control" placeholder="Search Companies">
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
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script> -->
<!-- <link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css"> -->
<!-- <script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script> -->
<script src="<?php echo assets_url();?>js/custom/company/add_company_by_popup.js"></script>
<script type="text/javascript">


</script>