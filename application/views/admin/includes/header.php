<?php $session_data = $this->session->userdata('admin_session_data');?>
<!-- content panel -->
<div class="main-panel background_white">
  <!-- top header -->
  <nav class="header navbar box_shadow_hader top-fixed-header" id="header-nav">
        <div class="header-inner">
            <div class="navbar-item navbar-spacer-right brand hidden-lg-up">
                  <!-- toggle offscreen menu -->
                  <a href="javascript:;" data-toggle="sidebar" class="toggle-offscreen">
                  <i class="material-icons">menu</i>
                  </a>
                  <!-- /toggle offscreen menu -->
                  <!-- logo -->
                  <a class="brand-logo hidden-xs-down">
                  <img src="<?=assets_url();?>images/logo_white.png?v=<?php echo rand(0,1000); ?>" alt="logo">
                  </a>
                  <!-- /logo -->
            </div>
                <a class="navbar-item navbar-spacer-right navbar-heading hidden-md-down col-sm-2 logo-header" href="#">
                <img src="<?php echo assets_url()?>images/logo.png?v=<?php echo rand(0,1000); ?>"/>
                </a>

            
            <div class="navbar-search navbar-item col-sm-7 mx-auto">
              <a href="#" class="mobile-close mobile-show"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
              <form class="search-form" id="top_search_frm" action="<?php echo base_url();?><?php echo $session_info['lms_url']; ?>/lead/manage/" method="post">
                <div class="col-md-2 pr-0">
                  <select class="form-control header-select-sec search_from mdb-select selectpicker multiple" placeholder="Select a User..." name="search_type" id="search_type">
                      <?php if(is_permission_available($service_wise_menu[1]['menu_list']['menu_keyword'])){ ?>
                      <option value="L" data-id="<?php echo base_url();?><?php echo $session_info['lms_url']; ?>/lead/manage/" <?php if($_POST['search_type']=='L'){echo'SELECTED';} ?>><?php echo $menu_label_alias['menu']['lead']; ?></option>
                      <?php } ?>
                      <?php if(is_permission_available($service_wise_menu[2]['menu_list']['menu_keyword'])){ ?>
                      <option value="C" data-id="<?php echo base_url();?><?php echo $session_info['lms_url']; ?>/customer/manage/" <?php if($_POST['search_type']=='C'){echo'SELECTED';} ?>><?php echo $menu_label_alias['menu']['company']; ?></option>
                      <?php } ?>
                      <?php if(is_permission_available($service_wise_menu[7]['menu_list']['menu_keyword'])){ ?>
                      <option value="E" data-id="<?php echo base_url();?><?php echo $session_info['lms_url']; ?>/user/manage_employee/" <?php if($_POST['search_type']=='E'){echo'SELECTED';} ?>><?php echo $menu_label_alias['menu']['user']; ?></option>
                      <?php } ?>
                      <?php if(is_permission_available($service_wise_menu[3]['menu_list']['menu_keyword'])){ ?>
                      <option value="P" data-id="<?php echo base_url();?><?php echo $session_info['lms_url']; ?>/product/manage" <?php if($_POST['search_type']=='P'){echo'SELECTED';} ?>><?php echo $menu_label_alias['menu']['product']; ?></option>
                      <?php } ?>
                    </select>
                </div>
                <div class="col-md-8 pl-0">
                  <input class="form-control" type="text" placeholder="Search for Leads/Lead ID" name="search_keyword" id="search_keyword" value="<?php echo isset($_POST['search_keyword'])?$_POST['search_keyword']:''; ?>" autocomplete="off">
                  <div class="error_div text-danger" id="search_keyword_error"></div>
                </div>
                <div class="col-md-2">
                  <button class="search-btn" type="submit" id="search_by_keyword_confirm"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </form>
            </div>          
                    
                    
          <div class="navbar-item nav navbar-nav col-sm-3">
            <div class="images_profile">
              <div class="col-md-8 profile-text-ar">
                <div>
                  <?php
                  if($session_info['personal_photo']!='')
                  {
                  ?>
                  <img src="<?php echo assets_url().'uploads/clients/'.$session_info['client_id']?>/admin/thumb/<?=$session_info['personal_photo'];?>"/>
                  <?php
                  }
                  else
                  {
                  ?>
                  <img src="<?php echo assets_url()?>images/face2.jpg"/>
                  <?php
                  }
                  ?> 
                </div>
                <div class="header-img-text-name">
                  <span class="font-bold">Welcome, <?=$session_info['name'];?>
                    
                  </span>
                </div>
              </div>

              <div class="col-md-4">
                <div class="dropdown pull-right display-flex">                                               
                  <div class="dd_new">
                    <a href="#" class="btn_back_none header-right-icon"><i class="fa fa-cog" aria-hidden="true"></i></a>
                    <div  id="stDrpdwn" class="dropdown-menu padding_dropdown dropdown-content" aria-labelledby="dropdownMenuButton">
                  <h5 class="drop_down_heading">This account is managed by LMSBABA</h5>
                        <div class="col-sm-4">
                          <div class="profile_image_edit">
                            <?php
                            if($session_info['personal_photo']!='')
                            {
                            ?>
                              <img src="<?php echo assets_url().'uploads/clients/'.$session_info['client_id']?>/admin/thumb/<?=$session_info['personal_photo'];?>"/>
                            <?php
                            }
                            else
                            {
                            ?>
                              <img src="<?php echo assets_url()?>images/face2.jpg"/>
                            <?php
                            }
                            ?>
                          </div>                                                            
                        </div>
                        <div class="col-sm-8">                
                          <?php /*if($session_info['user_id']==1){ ?>
                          <a class="dropdown-item" href="<?=base_url();?><?php echo $session_info['lms_url']; ?>/setting/company/1">Settings</a> 
                          <?php }*/ ?>
                          <a class="dropdown-item my_document_popup" href="JavaScript:void(0);" >My Document</a> 
                          <a class="dropdown-item change_password" href="JavaScript:void(0);" >Change Password</a> 
                          <a class="dropdown-item" href="https://play.google.com/store/apps/details?id=com.lmsbaba.app" target="_blank">Download APP</a>                
                          <div class="dropdown-divider"></div>               
                          <a class="dropdown-item" href="<?php echo admin_url(); ?>logout/index">Logout</a>                
                        </div>                                              
                  </div>
                  </div>        
                </div>
              </div>   

              <div class="mobile-show mobile-search-bt"><i class="fa fa-search" aria-hidden="true"></i></div> 
            </div>

          </div>
        </div>
  </nav>
  <!-- menu header -->

  <nav class="header-secondary navbar bg_white bg-faded shadow">
    <div class="navbar-collapse">
      <a class="navbar-heading hidden-md-down margin_top11" href="javascript:;">
        <span>
          <?php if($company_info['logo']!=''){?>
          <img src="<?php echo assets_url().'uploads/clients/'.$session_info['client_id']?>/company/logo/<?php echo $company_info['logo'];?>"/>
          <?php } else{ ?>
            <img src="<?php echo assets_url()?>images/no-image.png"/>
            <?php }?>
        </span>
      </a>

      <span class="company_name">
        <h4><?php echo $company_info['name'];?></h4>
      </span>        
      <ul class="nav navbar-nav pull-xs-right margin_top_sec_nav">
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/dashboard">
              <b><img src="<?php echo assets_url()?>images/dashboard-icon.svg"/></b>
              <span class="<?php if($this->router->fetch_class()=='dashboard'){ ?>dashboard_active<?php } ?>">Dashboard</span>
          </a>
        </li>
        <?php if($is_show_lead_management_service_menu=='Y'){ ?>
        <?php if(is_permission_available($service_wise_menu[1]['menu_list']['menu_keyword'])){ ?>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/">
              <b><img src="<?php echo assets_url()?>images/<?php echo $service_wise_menu[1]['menu_list']['icon']; ?>"/></b>
              <span class="<?php if($this->router->fetch_class()=='lead'){ ?>dashboard_active<?php } ?>"><?php echo $menu_name=($menu_label_alias['menu']['lead'])?$menu_label_alias['menu']['lead']:$service_wise_menu[1]['menu_list']['menu_name']; ?></span>
          </a>
        </li>
        <?php } ?>
        <?php if(is_permission_available($service_wise_menu[2]['menu_list']['menu_keyword'])){ ?>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/">
              <b><img src="<?php echo assets_url()?>images/<?php echo $service_wise_menu[2]['menu_list']['icon']; ?>"/></b>
              <span class="<?php if($this->router->fetch_class()=='customer'){ ?>dashboard_active<?php } ?>"><?php echo $menu_name=($menu_label_alias['menu']['company'])?$menu_label_alias['menu']['company']:$service_wise_menu[2]['menu_list']['menu_name']; ?></span>
          </a>
        </li>
        <?php } ?>
        <?php if(is_permission_available($service_wise_menu[3]['menu_list']['menu_keyword'])){ ?>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/">
              <b><img src="<?php echo assets_url()?>images/<?php echo $service_wise_menu[3]['menu_list']['icon']; ?>"/></b>
              <span class="<?php if($this->router->fetch_class()=='product'){ ?>dashboard_active<?php } ?>"><?php echo $menu_name=($menu_label_alias['menu']['product'])?$menu_label_alias['menu']['product']:$service_wise_menu[3]['menu_list']['menu_name'];; ?></span>
          </a>
        </li>
        <?php } ?>
        <?php if(is_permission_available($service_wise_menu[6]['menu_list']['menu_keyword'])){ ?>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/">
              <b><img src="<?php echo assets_url()?>images/<?php echo $service_wise_menu[6]['menu_list']['icon']; ?>"/></b>
              <span class="<?php if($this->router->fetch_class()=='vendor'){ ?>dashboard_active<?php } ?>"><?php echo $menu_name=($menu_label_alias['menu']['vendor'])?$menu_label_alias['menu']['vendor']:$service_wise_menu[6]['menu_list']['menu_name'];; ?></span>
          </a>
        </li>
        <?php } ?>
        <?php } ?>
        <?php if(is_permission_available($service_wise_menu[7]['menu_list']['menu_keyword'])){ ?>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee/">
              <b><img src="<?php echo assets_url()?>images/<?php echo $service_wise_menu[7]['menu_list']['icon']; ?>"/></b>
              <span class="<?php if($this->router->fetch_class()=='user'){ ?>dashboard_active<?php } ?>"><?php echo $menu_name=($menu_label_alias['menu']['user'])?$menu_label_alias['menu']['user']:$service_wise_menu[7]['menu_list']['menu_name'];; ?></span>
          </a>
        </li>
        <?php } ?>
      </ul>      
    </div>
  </nav>
</div>

