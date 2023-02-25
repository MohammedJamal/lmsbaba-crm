<nav class="" id="left-side-menu">
  <p class="nav-title">&nbsp;</p>
  <p class="nav-title">&nbsp;</p>
  <p class="nav-title">&nbsp;</p>
  <ul class="nav">
    <?php if(is_permission_available($service_wise_menu[0]['menu_list']['menu_keyword'])){ ?>
    <li>        
        <a href="javascript:;" id="menu_<?php echo $service_wise_menu[0]['menu_list']['id']; ?>">
          <span class="menu-caret">
            <i class="material-icons">arrow_drop_down</i>
          </span>
          <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
          <path style="fill:#FFCA12;" d="M501.801,313.316V198.684h-52.94c-4.002-13.486-9.367-26.387-15.958-38.527l37.434-37.434
            l-81.059-81.058l-37.434,37.434c-12.14-6.592-25.041-11.956-38.527-15.958V10.199H198.684v52.94
            c-13.486,4.002-26.387,9.367-38.527,15.958l-37.434-37.433l-81.058,81.058l37.434,37.434c-6.592,12.14-11.956,25.041-15.958,38.527
            H10.199v114.634h52.94c4.002,13.486,9.367,26.387,15.958,38.527l-37.433,37.433l81.058,81.058l37.434-37.434
            c12.14,6.592,25.041,11.956,38.527,15.958v52.941h114.634v-52.94c13.486-4.002,26.387-9.367,38.527-15.958l37.434,37.434
            l81.058-81.058l-37.434-37.434c6.592-12.14,11.956-25.041,15.958-38.527h52.94V313.316z M256,348.038
            c-50.831,0-92.038-41.207-92.038-92.038s41.207-92.038,92.038-92.038s92.038,41.207,92.038,92.038S306.831,348.038,256,348.038z"/>
          <path d="M313.316,512H198.684c-5.633,0-10.199-4.566-10.199-10.199v-45.473c-9.042-3.045-17.876-6.704-26.398-10.931l-32.153,32.153
            c-3.982,3.983-10.441,3.983-14.424,0L34.451,396.49c-3.983-3.983-3.983-10.441,0-14.424l32.153-32.153
            c-4.229-8.52-7.886-17.355-10.932-26.398H10.199C4.566,323.516,0,318.95,0,313.317V198.684c0-5.633,4.566-10.199,10.199-10.199
            h45.473c3.045-9.043,6.704-17.876,10.932-26.398l-32.153-32.153c-3.983-3.983-3.983-10.441,0-14.424l81.059-81.059
            c3.982-3.983,10.441-3.983,14.424,0l32.153,32.153c8.521-4.229,17.356-7.886,26.398-10.932V10.199
            C188.484,4.566,193.051,0,198.684,0h114.634c5.633,0,10.199,4.566,10.199,10.199v45.473c9.042,3.045,17.876,6.704,26.398,10.932
            l32.153-32.153c3.982-3.983,10.441-3.983,14.424,0l81.059,81.059c3.983,3.983,3.983,10.441,0,14.424l-32.153,32.153
            c4.229,8.52,7.886,17.354,10.931,26.398h45.472c5.633,0,10.199,4.566,10.199,10.199v114.634c0,5.633-4.566,10.199-10.199,10.199
            h-45.473c-3.045,9.044-6.704,17.877-10.931,26.398l32.153,32.153c3.983,3.983,3.983,10.441,0,14.424L396.49,477.55
            c-3.982,3.983-10.441,3.983-14.424,0l-32.153-32.153c-8.521,4.229-17.356,7.886-26.398,10.931v45.472
            C323.516,507.434,318.949,512,313.316,512z M208.883,491.602h94.236v-42.741c0-4.515,2.969-8.493,7.298-9.778
            c12.688-3.766,24.989-8.861,36.563-15.144c3.969-2.155,8.883-1.443,12.079,1.751l30.222,30.222l66.634-66.634l-30.222-30.222
            c-3.193-3.194-3.906-8.109-1.751-12.079c6.283-11.57,11.377-23.871,15.144-36.563c1.285-4.329,5.263-7.298,9.778-7.298h42.739
            v-94.236h-42.741c-4.515,0-8.493-2.969-9.778-7.298c-3.767-12.691-8.861-24.992-15.144-36.563c-2.155-3.97-1.443-8.885,1.751-12.079
            l30.222-30.222l-66.634-66.634l-30.222,30.222c-3.194,3.194-8.107,3.906-12.079,1.751c-11.573-6.284-23.874-11.378-36.563-15.144
            c-4.329-1.285-7.298-5.263-7.298-9.778V20.398h-94.236v42.741c0,4.515-2.969,8.493-7.298,9.778
            c-12.688,3.766-24.989,8.861-36.563,15.144c-3.97,2.154-8.885,1.442-12.079-1.751l-30.222-30.222l-66.634,66.634l30.222,30.222
            c3.193,3.194,3.906,8.109,1.751,12.079c-6.283,11.572-11.378,23.873-15.144,36.563c-1.285,4.329-5.263,7.298-9.778,7.298H20.398
            v94.236h42.741c4.515,0,8.493,2.969,9.778,7.298c3.766,12.689,8.861,24.99,15.144,36.563c2.155,3.97,1.443,8.885-1.751,12.079
            l-30.222,30.222l66.634,66.634l30.222-30.222c3.195-3.193,8.109-3.905,12.079-1.751c11.573,6.284,23.874,11.378,36.563,15.144
            c4.329,1.285,7.298,5.263,7.298,9.778v42.738H208.883z M256,358.237c-56.373,0-102.237-45.863-102.237-102.237
            S199.627,153.763,256,153.763S358.237,199.627,358.237,256S312.373,358.237,256,358.237z M256,174.162
            c-45.125,0-81.838,36.713-81.838,81.838c0,45.125,36.713,81.838,81.838,81.838c45.126,0,81.838-36.713,81.838-81.838
            S301.126,174.162,256,174.162z"/>
          <path d="M256,394.954c-5.633,0-10.199-4.566-10.199-10.199c0-5.633,4.566-10.199,10.199-10.199
            c50.206,0,95.15-31.792,111.838-79.111c1.873-5.312,7.697-8.099,13.011-6.226c5.312,1.874,8.099,7.698,6.226,13.011
            C367.515,357.69,314.84,394.954,256,394.954z"/>
          <path d="M384.62,272.319c-0.16,0-0.321-0.003-0.483-0.011c-5.627-0.262-9.975-5.036-9.713-10.664
            c0.088-1.875,0.132-3.774,0.132-5.643c0-5.633,4.566-10.199,10.199-10.199c5.633,0,10.199,4.566,10.199,10.199
            c0,2.186-0.052,4.404-0.154,6.595C394.545,268.06,390.034,272.319,384.62,272.319z"/>
          </svg>
          <span>Settings</span>
        </a>
        <ul class="sub-menu">
            <?php if($is_show_lead_management_service_menu=='Y'){ ?>
              <?php if(is_permission_available('company_profile_settings_menu')){ ?>
              <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/company/<?=$company_info['id'];?>" data-menuid="<?php echo $service_wise_menu[0]['menu_list']['id']; ?>"><span>Company Profile Settings</span></a></li>
              <?php } ?>
              <?php if(is_permission_available('general_setting_menu')){ ?>
              <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/general/<?=$company_info['id'];?>" data-menuid="<?php echo $service_wise_menu[0]['menu_list']['id']; ?>"><span>General Settings</span></a></li>
              <?php } ?>
              <?php if(is_permission_available('api_setting_menu')){ ?>
              <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/api/<?=$company_info['id'];?>" data-menuid="<?php echo $service_wise_menu[0]['menu_list']['id']; ?>"><span>API Settings</span></a></li>
              <?php } ?>
            <?php } ?>
            <?php if($is_show_hrm_menu=='Y'){ ?>
              <?php if(is_permission_available('hrm_setting_menu')){ ?>
              <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/target/<?=$company_info['id'];?>" data-menuid="<?php echo $service_wise_menu[0]['menu_list']['id']; ?>"><span>HRM Settings</span></a></li>          
              <?php } ?>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
 
  <?php   
  // if(count($service_wise_menu))
  if(isset($service_wise_menu[0]['menu_list']))
  { 
    $i=0;    
    foreach($service_wise_menu AS $row)
    { 
      $menu_name=$row['menu_list']['menu_name'];
      if($row['menu_list']['id']==2)
      {
        $menu_name=($menu_label_alias['menu']['lead'])?$menu_label_alias['menu']['lead']:$menu_name;
      }
      else if($row['menu_list']['id']==3)
      {
        $menu_name=($menu_label_alias['menu']['company'])?$menu_label_alias['menu']['company']:$menu_name;
      }
      else if($row['menu_list']['id']==4)
      {
        $menu_name=($menu_label_alias['menu']['user'])?$menu_label_alias['menu']['user']:$menu_name;
      }
      else if($row['menu_list']['id']==5)
      {
        $menu_name=($menu_label_alias['menu']['product'])?$menu_label_alias['menu']['product']:$menu_name;
      }
      else if($row['menu_list']['id']==6)
      {
        $menu_name=($menu_label_alias['menu']['vendor'])?$menu_label_alias['menu']['vendor']:$menu_name;
      }
      else if($row['menu_list']['id']==7)
      {
        $menu_name=($menu_label_alias['menu']['purchase_order'])?$menu_label_alias['menu']['purchase_order']:$menu_name;
      }
      else if($row['menu_list']['id']==8)
      {
        $menu_name=($menu_label_alias['menu']['renewal'])?$menu_label_alias['menu']['renewal']:$menu_name;
      }
      ?>
      <?php 
      if(is_permission_available($row['menu_list']['menu_keyword']))
      { 
        $is_show_menu='N';
        
        if(in_array($row['menu_list']['service_id'],$user_wise_service_info['service_tagged']))
        {
          $is_show_menu='Y';
          if($row['menu_list']['is_show_in_left_panel']=='Y'){
            $is_show_menu='Y';
          }
          else{
            $is_show_menu='N';
          } 
        }
        else{
          $is_show_menu='N';
        }

          
        if($is_show_menu=='Y')
        {
        ?>
          <li> 
                <a href="javascript:;" id="menu_<?php echo $row['menu_list']['id']; ?>">
                  <span class="menu-caret">
                    <i class="material-icons">arrow_drop_down</i>
                  </span>
                  <img src="<?php echo assets_url()?>images/<?php echo $row['menu_list']['icon']; ?>" width="44">
                  <span><?php echo $menu_name; ?></span>
                </a>
          
                  <?php 
                  if($row['menu_list']['id']==1) // Setting
                  { 
                  ?>
                  <ul class="sub-menu">
                    <?php if(is_permission_available('company_profile_settings_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/company/<?=$company_info['id'];?>" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Company Profile</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('general_setting_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/general/<?=$company_info['id'];?>" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>General Setting</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('api_setting_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/api/<?=$company_info['id'];?>" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>API Setting</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('hrm_setting_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/target/<?=$company_info['id'];?>" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>HR Setting</span></a></li>          
                    <?php } ?>
                  </ul>
                  <?php 
                  } 
                  else if($row['menu_list']['id']==2) // Lead Board
                  { 
                  ?>
                  <ul class="sub-menu">
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span><?php echo $menu_name; ?> Board</span></a></li>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/add/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Add New <?php echo $menu_name; ?></span></a></li>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage_sync_call/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Call Logs to <?php echo $menu_name; ?></span></a></li>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/note/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Manage Notes</span></a></li>          
                  </ul>
                  <?php 
                  }
                  else if($row['menu_list']['id']==3) // Company
                  { 
                  ?>
                  <ul class="sub-menu">
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Address Book</span></a></li>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/add/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Add New <?php echo $menu_name; ?></span></a></li>
                  </ul>
                  <?php 
                  }
                  else if($row['menu_list']['id']==4) // User
                  { 
                  ?>
                  <ul class="sub-menu">
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span><?php echo $menu_name; ?> List</span></a></li>
                    <?php if(is_permission_available('add_users_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/add_employee/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Add a New <?php echo $menu_name; ?></span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('manage_department_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_department/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Manage Department</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('manage_designation_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_designation/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Manage Designation</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('manage_functional_area_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_functional_area/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Manage Functional Area</span></a></li>
                    <?php } ?>
                  </ul>
                  <?php 
                  } 
                  else if($row['menu_list']['id']==5) // product
                  { 
                  ?>
                  <ul class="sub-menu">
                    
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span><?php echo $menu_name; ?> List</span></a></li>
                    
                    <?php if(is_permission_available('add_product_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/add/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Add a New <?php echo $menu_name; ?></span></a></li>
                    <?php } ?>
                  </ul>
                  <?php 
                  }
                  else if($row['menu_list']['id']==6) // vendor
                  { 
                  ?>
                  <ul class="sub-menu">
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Listing</span></a></li>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/add/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Add a New</span></a></li>
                  </ul>
                  <?php 
                  }
                  else if($row['menu_list']['id']==7) // PO
                  { 
                  ?>
                  <ul class="sub-menu">
                    <?php if(is_permission_available('po_register_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/po_register/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>PO Register</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('manage_proforma_invoice_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/proforma_invoice_management/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Manage Proforma</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('invoice_management_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/invoice_management/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Manage Invoice</span></a></li>
                    <?php } ?>
                    <?php if(is_permission_available('payment_followup_menu')){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order/payment_followup/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Follow Payments</span></a></li>          
                    <?php } ?>
                  </ul>
                  <?php 
                  }
                  else if($row['menu_list']['id']==8) // Renewal
                  { 
                  ?>
                  <ul class="sub-menu">
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/renewal/manage/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Manage Renewals / AMCs</span></a></li>
                  </ul>
                  <?php 
                  }
                  else if($row['menu_list']['id']==9) // Performance Scorecard Approval
                  { 
                  ?>
                  <ul class="sub-menu">
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/my_performance/" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>My Performance</span></a></li>
                  </ul>
                  <?php 
                  }
                  else if($row['menu_list']['id']==10) // Order Management
                  {
                  ?>
                  <ul class="sub-menu">
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order_management/?page=om" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>View Orders</span></a></li>
                    <?php if($this->session->userdata['admin_session_data']['user_id']=='1'){ ?>
                    <li><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/order_management/?page=setting" data-menuid="<?php echo $row['menu_list']['id']; ?>"><span>Order Settings</span></a></li>
                    <?php } ?>
                  </ul>
                  <?php 
                  }
                  ?>
          </li>
        <?php
        }
      } 
      ?>     
      <?php 
    $i++;
    }
    ?>
    
  </ul> 
  <?php
  } 
  ?>
</nav>

<script type="text/javascript">
$( document ).ready(function() {  
   var mId = $(".set_parent_active").attr("data-menuID");           
   $("#menu_"+mId).addClass('active');
    $(window).scroll(function(){       
       if($(this).scrollTop()==0)
       {
           $("#header-nav").removeClass('scroll-header');                 
       }
       else
       {
         $("#header-nav").addClass('scroll-header');              
       } 

       if($(this).scrollTop()>=110)
       {             
         $("#left-side-menu").addClass('no-margin');        
       }
       else
       {
         $("#left-side-menu").removeClass('no-margin');
       }
   });
});
</script>