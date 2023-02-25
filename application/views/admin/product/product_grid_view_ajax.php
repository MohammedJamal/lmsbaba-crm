<?php 
if(count($rows)>0) 
{
   $i=0;
   foreach($rows as $row)  
   { 
   ?>
   <tr id="tr_<?php echo $row['product_id']; ?>">
   <td></td>
   <td>
      <div class="product_bg" id="product_bg_<?php echo $row['product_id']; ?>">
         <div class="product_pic" id="product_pic_div_<?php echo $row['product_id']; ?>">
            <div class="product_outer">
               
                  <!-- thumb start -->
                  <div class="product_thumb" data-target="slide_<?php echo $row['product_id']; ?>" data-id="<?php echo $row['product_id']; ?>">
                     <ul>
                        <?php
                        $images_arr=array();
                        $images_id_arr=array();
                        if($row['images']){                           
                           $images_arr_tmp=explode(",", $row['images']);
                           foreach($images_arr_tmp as $val)
                           {
                              $img_val=explode("#", $val);
                              array_push($images_arr, $img_val[1]);
                              array_push($images_id_arr, $img_val[0]);
                           }                           
                        }
                        ?>
                        <?php   
                        //print_r($images_id_arr);   
                        //echo count($images_arr);                  
                        if(count($images_arr)==3)
                        {
                           for($i=0;$i<count($images_arr);$i++)
                           {
                              ?>
                              <li data-id="<?php echo $i ?>" class="<?php echo ($i==0)?'active':''; ?>">
                                 <a href="#" data-id="<?php echo $i ?>"><img src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/thumb/<?php echo $images_arr[$i]; ?>"></a>
                              </li>
                              <?php
                           }
                        }
                        else if(count($images_arr)==2)
                        {
                        ?>
                        <li class="active">
                           <a href="#" data-id="0"><img src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/thumb/<?php echo $images_arr[0]; ?>"></a>
                        </li>
                        <li class="active">
                           <a href="#" data-id="1"><img src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/thumb/<?php echo $images_arr[1]; ?>"></a>
                        </li>
                        <li>
                           <form name="frmProductEdit_<?php echo $row['product_id']; ?>_2" id="frmProductEdit_<?php echo $row['product_id']; ?>_2" action="" type="multipart/form-data" >
                           <input type="hidden" name="p_editsection" value="image">
                           <input type="hidden" name="p_id" value="<?php echo $row['product_id']; ?>">
                           <input type="hidden" name="img_index" value="2">
                           <label class="phot_up" for="upload_<?php echo $row['product_id']; ?>_2">                           
                              <span><i class="fa fa-plus" aria-hidden="true"></i><br>Add Photo</span>
                              <input type="file" name="image_files[]" id="upload_<?php echo $row['product_id']; ?>_2" class="upload_image">
                           </label>
                        </form>
                        </li>
                        <?php
                        }
                        else if(count($images_arr)==1)
                        {
                        ?>
                        <li class="active">
                           <a href="#" data-id="0"><img src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/thumb/<?php echo $images_arr[0]; ?>"></a>
                        </li>
                        <li>
                           <form name="frmProductEdit_<?php echo $row['product_id']; ?>_1" id="frmProductEdit_<?php echo $row['product_id']; ?>_1" action="" type="multipart/form-data" >
                           <input type="hidden" name="p_editsection" value="image">
                           <input type="hidden" name="p_id" value="<?php echo $row['product_id']; ?>">
                           <input type="hidden" name="img_index" value="1">
                           <label class="phot_up" for="upload_<?php echo $row['product_id']; ?>_1">                           
                              <span><i class="fa fa-plus" aria-hidden="true"></i><br>Add Photo</span>
                              <input type="file" name="image_files[]" id="upload_<?php echo $row['product_id']; ?>_1" class="upload_image">
                           </label>
                        </form>
                        </li>
                        <li>
                           <form name="frmProductEdit_<?php echo $row['product_id']; ?>_2" id="frmProductEdit_<?php echo $row['product_id']; ?>_2" action="" type="multipart/form-data" >
                           <input type="hidden" name="p_editsection" value="image">
                           <input type="hidden" name="p_id" value="<?php echo $row['product_id']; ?>">
                           <input type="hidden" name="img_index" value="2">
                           <label class="phot_up" for="upload_<?php echo $row['product_id']; ?>_2">                           
                              <span><i class="fa fa-plus" aria-hidden="true"></i><br>Add Photo</span>
                              <input type="file" name="image_files[]" id="upload_<?php echo $row['product_id']; ?>_2" class="upload_image">
                           </label>
                        </form>
                        </li>
                        <?php
                        }
                        else
                        {
                        ?>
                        <li>
                           <form name="frmProductEdit_<?php echo $row['product_id']; ?>_0" id="frmProductEdit_<?php echo $row['product_id']; ?>_0" action="" type="multipart/form-data" >
                           <input type="hidden" name="p_editsection" value="image">
                           <input type="hidden" name="p_id" value="<?php echo $row['product_id']; ?>">
                           <input type="hidden" name="img_index" value="0">
                           <label class="phot_up" for="upload_<?php echo $row['product_id']; ?>_0">                           
                              <span><i class="fa fa-plus" aria-hidden="true"></i><br>Add Photo</span>
                              <input type="file" name="image_files[]" id="upload_<?php echo $row['product_id']; ?>_0" class="upload_image" data-pid="<?php echo $row['product_id']; ?>" data-imgindex="0">
                           </label>
                        </form>
                        </li>
                        <li>
                           <form name="frmProductEdit_<?php echo $row['product_id']; ?>_1" id="frmProductEdit_<?php echo $row['product_id']; ?>_1" action="" type="multipart/form-data" >
                           <input type="hidden" name="p_editsection" value="image">
                           <input type="hidden" name="p_id" value="<?php echo $row['product_id']; ?>">
                           <input type="hidden" name="img_index" value="1">
                           <label class="phot_up" for="upload_<?php echo $row['product_id']; ?>_1">
                              <span><i class="fa fa-plus" aria-hidden="true"></i><br>Add Photo</span>
                              <input type="file" name="image_files[]" id="upload_<?php echo $row['product_id']; ?>_1" class="upload_image">
                           </label>
                           </form>
                        </li>
                        <li>
                           <form name="frmProductEdit_<?php echo $row['product_id']; ?>_2" id="frmProductEdit_<?php echo $row['product_id']; ?>_2" action="" type="multipart/form-data" >
                           <input type="hidden" name="p_editsection" value="image">
                           <input type="hidden" name="p_id" value="<?php echo $row['product_id']; ?>">
                           <input type="hidden" name="img_index" value="2">
                           <label class="phot_up" for="upload_<?php echo $row['product_id']; ?>_2">
                              <span><i class="fa fa-plus" aria-hidden="true"></i><br>Add Photo</span>
                              <input type="file" name="image_files[]" id="upload_<?php echo $row['product_id']; ?>_2" class="upload_image">
                           </label>
                           </form>
                        </li>
                        <?php
                        }
                        ?>
                     </ul>
                  </div>
               
               <!-- thumb end -->
               <div class="product_slider">
                  <a href="#" class="zoom_pic"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
                  <?php if(count($images_arr)>0){ ?>
                  <a href="JavaScript:void();" class="delete_pic"><i class="fa fa-trash" aria-hidden="true"></i></a>
                  <!-- <a href="JavaScript:void();" class="lock_pic"><i class="fa fa-lock" aria-hidden="true"></i></a> -->
                  <?php } ?>

                  <div id="slide_<?php echo $row['product_id']; ?>" class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-timeout="0">
                     
                     <?php                    
                     if(count($images_arr)==3)
                     {

                        for($i=0;$i<count($images_arr);$i++)
                        {
                           ?>
                           <img data-count="<?php echo $i ?>" src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/<?php echo $images_arr[$i]; ?>" data-id="<?php echo $row['product_id']; ?>" data-imgid="<?php echo $images_id_arr[$i]; ?>">                           
                           <?php
                        }
                     }
                     else if(count($images_arr)==2)
                     {
                     ?>                     
                     <img data-count="0" src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/<?php echo $images_arr[0]; ?>" data-id="<?php echo $row['product_id']; ?>" data-imgid="<?php echo $images_id_arr[0]; ?>">
                     <img data-count="1" src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/<?php echo $images_arr[1]; ?>" data-id="<?php echo $row['product_id']; ?>" data-imgid="<?php echo $images_id_arr[1]; ?>">

                     <?php
                     }
                     else if(count($images_arr)==1)
                     {
                        ?>
                     <img data-count="0" src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/<?php echo $images_arr[0]; ?>" data-id="<?php echo $row['product_id']; ?>" data-imgid="<?php echo $images_id_arr[0]; ?>">
                        <?php
                     }
                     else
                     {
                     ?>
                     <img class="replace_photo" src="<?php echo assets_url(); ?>images/no_photo.png">
                     <?php
                     }
                     ?>
                  </div>
               </div>
            </div>
         </div>







         <div class="total_info">
            <div class="total_info_left">
               <div class="product_name">
                  <font class="get_pdetails-- get_detail_modal" data-id="<?php echo $row['product_id']; ?>" style="cursor:pointer"><?php echo $row['name']; ?></font>           
                  <span>Category: <?php echo ($row['group_name'])?$row['group_name']:"N/A"; ?> > <?php echo ($row['cat_name'])?$row['cat_name']:'N/A'; ?></span>
               </div>
               <div class="product_info">
                  <?php if(is_permission_available('edit_product_non_menu')){ ?>
                  <a href="JavaScript:void(0);" class="edit_content edit_content_view" data-id="<?php echo $row['product_id']; ?>" data-editsection="description">
                     <i class="fa fa-pencil" aria-hidden="true"></i>
                  </a>
                  <?php } ?>
                  <p><?php echo ($row['description'])?substr($row['description'],0,100):'--'; ?></p>
               </div>
               <div class="product_price_code">
                  <ul>
                    <li>Product Code: <?php echo $row['code']; ?></li>
                    <li>HSN Code: <?php echo ($row['hsn_code'])?$row['hsn_code']:'N/A'; ?> </li>
                    <li>GST: <?php echo ($row['gst_percentage'])?$row['gst_percentage'].'%':'N/A'; ?></li>
                  </ul>
               </div>
               <div class="product_price">
                  Selling Price:  - <?php echo $row['curr_code']; ?> <?php echo $row['price']; ?> / <?php echo $row['unit']; ?> <?php echo $row['unit_type_name']; ?>
               </div>
               
               <div class="product_last">
                  Last Modified Date: <?php echo date_db_format_to_display_format($row['date_modified']); ?>
                     <small>( <?php echo ($row['status']==0?'<span class="text-success"><b>Enabled</b></span>':'<span class="text-danger"><b>Disabled</b></span>'); ?>    )</small>
               </div>
            </div>

            <div class="product_vendor_action">
               <div class="product_vendor">
                  <div class="mc">
                  <span class="<?php echo ($row['vendor_count'])?'get_product_wise_vendor_list_modal':0; ?>" data-id="<?php echo $row['product_id']; ?>" ><?php echo ($row['vendor_count'])?$row['vendor_count']:0; ?> Vendor Found</span>
                  <span>
                  <?php if(is_permission_available('edit_product_non_menu')){ ?>
                  <button type="button" class="btn btn-primary select_vendor_product_lead"  data-vtagstr="<?php echo $row['v_tag_str']; ?>" data-pid="<?php echo $row['product_id']; ?>" id="tag_vendor_<?php echo $row['product_id']; ?>">Tag Vendor
                  </button>
                  <?php } ?>
                  </span>
                  </div>
               </div>
               <div class="product_action">
                  <ul>
                     <li>
                        <?php if($row['youtube_video']){ ?>
                        <div class="like_h">
                           <i class="fa fa-youtube-play" aria-hidden="true"></i>
                           <span>Video</span>
                        </div>
                        <ul>
                           <li>
                              <a href="JavaScript:void(0)" class="view_youtube_video" data-content="<?php echo $row['youtube_video']; ?>" data-toggle="tooltip" data-placement="top" title="Preview">
                                 <i class="fa fa-eye" aria-hidden="true"></i>
                              </a>
                           </li>
                           <?php if(is_permission_available('edit_product_non_menu')){ ?>
                           <li>
                              <a href="JavaScript:void(0)" class="edit_content_view" data-id="<?php echo $row['product_id']; ?>" data-editsection="youtube">
                                 <i class="fa fa-pencil" aria-hidden="true"></i>
                              </a>
                           </li>
                           <li>
                              <a href="JavaScript:void(0);" class="delete_youtube_video" data-id="<?php echo $row['product_id']; ?>" title="Delete">
                                 <i class="fa fa-trash" aria-hidden="true"></i>
                              </a>
                           </li>
                           <?php } ?>
                        </ul>
                        <?php }else{ ?>                           
                        <div class="like_h no <?php if(is_permission_available('edit_product_non_menu')){ ?>edit_content_view<?php } ?>" data-id="<?php echo $row['product_id']; ?>" data-editsection="youtube">
                           <i class="fa fa-play" aria-hidden="true"></i>
                           <span>Video</span>
                        </div>
                        <?php } ?>
                     </li>
                     <li>
                        <?php if($row['brochures']){ ?>
                        <div class="like_h">
                           <i class="fa fa-file-pdf-o" aria-hidden="true"></i><span>Pdf</span>
                        </div>
                        <ul>
                           <li>
                              <a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/download_brochure/<?php echo base64_encode($row['brochures']); ?>" class="download_pdf">
                                 <i class="fa fa-download" aria-hidden="true"></i>
                              </a>
                           </li>
                           <?php if(is_permission_available('edit_product_non_menu')){ ?>
                           <li>
                              <a href="JavaScript:void();" class="edit_content_view" data-id="<?php echo $row['product_id']; ?>" data-editsection="brochures">
                                 <i class="fa fa-pencil" aria-hidden="true"></i>
                              </a>
                           </li>
                           <li>
                               <a href="JavaScript:void(0);" class="delete_brochure" data-id="<?php echo $row['product_id']; ?>" title="Delete">
                                 <i class="fa fa-trash" aria-hidden="true"></i>
                              </a>
                           </li>
                           <?php } ?>
                        </ul>
                        <?php }else{ ?>
                        <div class="like_h no <?php if(is_permission_available('edit_product_non_menu')){ ?>edit_content_view<?php } ?>" data-id="<?php echo $row['product_id']; ?>" data-editsection="brochures">
                           <i class="fa fa-file-pdf-o" aria-hidden="true"></i><span>Pdf</span>
                        </div>
                        <?php } ?>                        
                     </li>
                     <!-- <li>
                        <a href="#"><i class="fa fa-trash" aria-hidden="true"></i><span>Delete</span></a>
                        </li> -->
                     <li>
                        <div class="like_h no">
                           <i class="fa fa-wrench" aria-hidden="true"></i><span>Edit</span>
                        </div>
                        <ul>
                           <li>
                              <a href="JavaScript:void(0);" class="get_pdetails-- get_detail_modal" data-id="<?php echo $row['product_id']; ?>" data-toggle="tooltip" title="View Details">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              </a>
                           </li>
                           <?php if(is_permission_available('edit_product_non_menu')){ ?>
                           <li>
                              <?php 
                              if($row['status']==0)
                              {
                                 $icon='fa fa-unlock-alt';
                                 $status_style="";
                                 $is_enable_disable_text="Disable";
                              }
                              else
                              {
                                 $icon='fa fa-lock';
                                 $status_style="color: red !important";
                                 $is_enable_disable_text="Enable";
                              }
                              ?>
                              <a href="JavaScript:void(0);" class="change_status" data-id="<?php echo $row['id']; ?>" data-curstatus="<?php echo $row['status']; ?>" data-toggle="tooltip" title="<?php echo $is_enable_disable_text; ?> Product">
                                 <i class="<?php echo $icon;?>" aria-hidden="true" style="<?php echo $status_style; ?>"></i>
                              </a>
                           </li>
                           <li>
                           <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/product/edit/<?php echo $row['product_id']; ?>" class="" data-toggle="tooltip" title="Edit Product">
                                 <i class="fa fa-pencil" aria-hidden="true"></i>
                              </a>                           
                           </li>
                           <li><a href="JavaScript:void(0);"  class="del_btn" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true" style="color: red !important"></i></a></li>
                           <?php } ?>
                        </ul>
                        
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </td>
   </tr>
   <?php 
   $i++;
   } 
}
else
{
   echo'<tr><td colspan="2">No Record Found..</td></tr>';
}
?>



