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
                <h5 class="lead_board">Manage Vendors</h5>
                <div class="card-block">
                    <div class="row">
                    <div class="col-md-9 company-total-color-text">
                      <div class="col-md-2 text-center">
                            <label class="orange-color-text text-left">Total <br>Vendors</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage"><?php echo $total_vendors?></a>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="blue-color-text text-left">Approved <br> Vendors</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage/approved_vendors"><?php echo $approved_vendors?></a>
                        </div>
                        <div class="col-md-3 text-center">
                            <label class="green-color-text text-left">Rejected<br> Vendors</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage/rejected_vendors"><?php echo $rejected_vendors?></a>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="pink-color-text text-left">Premium <br>Vendors</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage/premium_vendors"><?php echo $premium_vendors?></a>
                        </div>
                        <div class="col-md-3 text-center">
                            <label class="light-blue-color-text text-left"> Blacklisted<br>Vendors</label><hr>
                            <a class="font-text" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage/blacklisted_vendors">
                                <?php echo $blacklisted_vendors?>
                            </a>
                        </div>  
                        
                    </div>
                    <div class="col-md-3">
                     <div class="col-md-6">
                            <a class="pull-right" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage">
                                <button type="button" class="btn btn-primary m-r-xs m-b-xs view-btn-blue mb-0 mt-14">View Vendors</button>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a class="pull-right" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/add">
                                <button type="button" class="btn btn-primary m-r-xs m-b-xs view-btn-blue mb-0 mt-14">Add Vendor</button>
                            </a>
                        </div>
                        
                    </div>
                        
                        
                    </div>
                    <div class="row">
                        <form action="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage" method="post" accept-charset="utf-8">
                            <div class="col-md-12 mt-15">
                                <div class="form-group">
                                    <label class="search-company-text">Search Vendors</label>
                                    <input type="text" name="search_keyword" class="form-control" placeholder="Search Vendors">
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
