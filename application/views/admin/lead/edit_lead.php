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
                <div class="layout-md b-b card process-sec">
                    <div class="layout-column-md">
                        <div class="card-block">
                            <div class="tsf-wizard tsf-wizard-1">
                                <div class="tsf-container">
                                    <div class=" pull-right ">
                                        <?php if($led_prev_id){?>
                                        <a style="color:#008ac9" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/edit/<?php echo $led_prev_id;?>">Previous</a>
                                        <?php }?>
                                        <?php if($led_prev_id && $led_next_id){?>
                                        | 
                                        <?php }?>
                                        <?php if($led_next_id){?>
                                        <a style="color:#008ac9" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/edit/<?php echo $led_next_id;?>">Next</a>
                                        <?php }?>
                                    </div>
                                    <div class="">
                                    <div class="lead_id heading-text-ar">
                                    <h3>Lead Title: 
                                    <a href="JavaScript:void(0);" class="get_original_quotation" data-id="<?php echo $lead_id; ?>" id=""><?=$cus_data->title?> (Lead #<?php echo $cus_data->id; ?>)</a>
                                    </h3>
                                     <div class="row">
                                    <div class="col-md-12">
                                    <!-- MESSAGE START -->
                                 <?php if($this->session->flashdata('error_msg')!=''){ ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            <?php echo $this->session->flashdata('error_msg'); ?>
                            </div>
                            <?php } ?>
                            <?php if($this->session->flashdata('success_msg')!=''){ ?>
                            <div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                           </button>
                            <?php echo $this->session->flashdata('success_msg'); ?>
                        </div>
                        <?php } ?>
                        <!-- MESSAGE END -->
                        </div>
                    </div>
                    <ul>
                    <li class="lead-red-bg"><strong>Lead Date:</strong>
                    <?=date("d M Y", strtotime($cus_data->create_date));?>
                    </li>
                    <li class="lead-blue-bg"><strong>Lead Source:</strong>
                    <?=$cus_data->source_name;?>
                    </li>
                    <li class="lead-green-bg">
                    <strong>Assigned To:</strong> <span id="assigned_to_user_name_span"><?=$cus_data->user_name;?></span> 
                    </li>
                    <li class="lead-yellow-bg"><strong>Quotation:</strong>
                    <?=count($opportunity_list);?>
                    </li>
                     <?php if($cus_data->modify_date!='0000-00-00') { ?>
                    <li class="lead-parple-bg"><strong>Last Updated Date:</strong>
                    <?=date("d M Y", strtotime($cus_data->modify_date));?>
                    </li>
                         <?php }  ?>

                    <li class="lead-parple-bg"><strong>Stage:</strong><span> <?php echo $cus_data->current_stage;?></span></li>
                    <li class="lead-parple-bg"><strong>Status:</strong><span> <?php echo $cus_data->current_status;?></span></li>
                    </ul>
                </div>

                <div class=" tab_gorup lead-edit-tab">
                    <?php /* ?>
                    <div class="col-md-8 tab tab-group-sec pl-0 pr-0">
                        <button class="tablinks" id="defaultOpen" onClick="openCity(event, 'lead_quotation_list')" type="button">Quotations</button>                        
                        <button class="tablinks" onClick="openCity(event, 'lead_info')"  type="button">Original Lead</button>
                        <button class="tablinks" onClick="openCity(event, 'update_lead')" type="button">Update Lead</button>
                        <button class="tablinks" onClick="openCity(event, 'lead_history')" type="button">Lead History</button>
                        <button class="tablinks" onClick="openCity(event, 'company_info')" type="button">Company Info</button>                        
                    </div>
                    <div class="col-md-3 pl-0">
                        <ul class="top-text-tab">
                        <li> Stage:<span> <?php echo $cus_data->current_stage;?></span></li>
                        <li>Status:<span> <?php echo $cus_data->current_status;?></span></li>
                        </ul>           
                    </div>
                    <?php */ ?>

        <div class="tab-section">
            <div id="lead_quotation_list" class="tabcontent">
                <?php if($cus_data->current_stage_id!='4'){ ?>
                <div class="lead-show-position text-right pb-0">
                    <input type="hidden" id="cust_email" value="<?php echo $cus_data->cus_email; ?>">
                    <button class="btn btn-primary btn-round-shadow mt-10 mr-10" id="create_quotation" ><i class="fa fa-plus" aria-hidden="true"></i> Create Quotation</button>
                </div>
                <?php } ?>
                <?php 
                if($opportunity_list)
                { 
                foreach($opportunity_list as $opportunity_data)
                {
                $followup_date = date("d M Y", strtotime($opportunity_data->followup_date)); ?>
                <div class="m-10px">
                <div class="update_proposal">
                <h5><?=$opportunity_data->opportunity_title;?> #<?=$opportunity_data->id;?></h5>
                <p>
                    <b>Status :</b> 
                    <span class="<?php echo $opportunity_data->status_class_name; ?>">
                        <?php echo $opportunity_data->status_name; ?>
                    </span> |                                                                    
                    <b>Deal Value:</b>

                    <?php 
                    if($opportunity_data->deal_value>0){
                        echo $opportunity_data->currency_code; ?> <?=number_format($opportunity_data->deal_value,2);
                    }
                    else
                    {
                        echo'N/A';
                    }
                    ?> |
                    <b>No. of Product(s):</b>
                    <?php
                    if($opportunity_data->product_count>0)
                    {
                        echo $opportunity_data->product_count;
                    }
                    else
                    {
                        echo'N/A';
                    }    
                    ?> |
                    <b>Created On.:</b><?php echo date_db_format_to_display_format($opportunity_data->create_date);?> |
                    <b>Last Modified On.:</b><?php echo date_db_format_to_display_format($opportunity_data->modify_date);?>
                </p>
                <?php 
                if($opportunity_data->tot_quotation > 0) 
                {  
                ?>    
                    <p>
                        <b>Quotation Sent On.:</b><?php echo date_db_format_to_display_format($opportunity_data->quotation_sent_on);?> |
                        <b>Quotation Type:</b>
                        <?php if($opportunity_data->is_extermal_quote=='Y'){
                            $ext=($opportunity_data->file_name=='')?'( Without Quotation )':'';
                        echo'<span class="text-danger"> <b>Custom '.$ext.'</b></span>';            
                        } else{
                        echo'<span class="text-danger"><b>Automated</b></span>';
                        } ?> |

                        <b>Purchase Order Status:</b>
                        <?php if($opportunity_data->is_po_received=='Y'){
                        echo'<span class="text-success"> <b>Received</b></span>';
                        if($opportunity_data->po_file_name)
                        {
                            echo ' (<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/download_po/'.$opportunity_data->lowp_id.'">  Download PO <i class="fa fa-cloud-download" aria-hidden="true"></i></a> )';
                        }
                        
                        } else{
                        echo'<span class="text-danger"><b>Not Received</b></span>';
                        } ?>
                    </p>

                    <?php if($opportunity_data->is_extermal_quote=='N'){ ?>
                    <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Preview <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a> &nbsp;|&nbsp;
                    <?php } ?>
                    <?php if($opportunity_data->is_extermal_quote=='N'){ ?>
                    <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Download <i class="fa fa-cloud-download" aria-hidden="true"></i></a> 
                    <?php if($cus_data->current_stage_id!='4'){ ?>
                    &nbsp;|&nbsp; <a href="JavaScript:void(0)" class="is_copy_confirm" data-url="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/clone_proporal/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" data-existingname="<?=$opportunity_data->opportunity_title;?>">Copy <i class="fa fa-clone" aria-hidden="true"></i></a>
                    <?php } ?>
                    <?php } ?>

                    <?php if($opportunity_data->is_extermal_quote=='Y' && $opportunity_data->file_name!=''){ ?>
                    <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" target="_blank">Download <i class="fa fa-cloud-download" aria-hidden="true"></i></a> 
                    <?php if($cus_data->current_stage_id!='4'){ ?>
                    &nbsp;|&nbsp; <a href="JavaScript:void(0)" class="is_copy_confirm" data-url="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/clone_proporal/'.$opportunity_data->id.'/'.$opportunity_data->q_id);?>" data-existingname="<?=$opportunity_data->opportunity_title;?>">Copy <i class="fa fa-clone" aria-hidden="true"></i></a>
                    <?php } ?>
                    <?php } ?>
                    
                    <?php 
                    if($opportunity_data->status==1) 
                    { 
                    ?>
                        <div class="clear"></div>
                        <?php if($opportunity_data->is_extermal_quote=='N'){ ?>
                        <a href="JavaScript:void(0)" class="btn btn-primary btn-round-shadow pull-left mt-10px" onclick="edit_qutation_view_modal('<?php echo $opportunity_data->id; ?>','<?php echo $opportunity_data->q_id; ?>')">Edit Quotation PDF</a>
                        <?php }else{ ?> 
                        <a href="JavaScript:void(0);" class="btn btn-primary btn-round-shadow pull-left mt-10px send_quotation_to_buyer_modal" data-oppid="<?php echo $opportunity_data->id; ?>" data-qid="<?php echo $opportunity_data->q_id; ?>">Sent Quotation PDF</a>
                        <?php } ?>    
                    <?php 
                    } 
                    else if($opportunity_data->status==2) 
                    { 			
                    ?>
                        <?php if($cus_data->current_stage_id!='4'){ ?>
                        <a href="JavaScript:void(0);" class="lead-btn grey-bg pull-right pull-right po_upload_view" data-loid="<?php echo $opportunity_data->id; ?>" data-lid="<?php echo $opportunity_data->lead_id; ?>" data-title="<?=$opportunity_data->opportunity_title;?> #<?=$opportunity_data->id;?>" data-dealvalue="<?php echo $opportunity_data->currency_code; ?>
                        <?=$opportunity_data->deal_value;?>">Upload Purchase Order (PO)</a>

                        
                        <?php if((($opportunity_data->is_extermal_quote=='Y' && $opportunity_data->file_name!='') || $opportunity_data->is_extermal_quote=='N') && $lead_data->cus_mobile!=''){ ?>
                        <a href="JavaScript:void(0);" style="margin-right:10px" class="lead-btn grey-bg pull-right quotation_sent_by_whatsapp" data-lid="<?php echo $lead_id; ?>" data-oppid="<?php echo $opportunity_data->id; ?>" data-qid="<?php echo $opportunity_data->q_id; ?>" >
                            <img src="<?php echo assets_url(); ?>images/social-whatsapp.png" style="width:16px;" />                                                       
                            Re-Send Quotation
                        </a>
                        <?php } ?>

                        <?php if($cus_data->cus_email!='' || $cus_data->cus_alt_email!=''){ ?>                        
                        <a href="javascript:" id="" style="margin-right:10px" class="lead-btn grey-bg pull-right qutation_re_send_to_buyer" data-opportunityid="<?php echo $opportunity_data->id; ?>" data-quotationid="<?php echo $opportunity_data->q_id; ?>">
                            <img src="<?php echo assets_url(); ?>images/cicon3.png" style="width:16px;" /> 
                             Re-Send Quotation
                        </a>
                        <?php } ?>
                        <?php } ?>
                    <?php 
                    } 
                    else{} 
                    ?>
                <?php 
                }
                else
                {
                    
                }  
                ?>
                <div id="update_<?=$opportunity_data->id;?>" class="opp_ajax_details" style="display: none;"></div>
                </div>
                </div>
                <?php 
                } 
                }
                else
                { 
                ?>
                <div class="m-10px">
                <div class="update_proposal">
                    <div class="width_full bold-txt">No Quotation Found.</div>
                </div>
                </div>
                <?php 
                } 
                ?>
            </div>

            <?php /* ?>
            <div id="lead_info" class="tabcontent">
                <div class="back_color_tsf boder-raduis10">
                    <div class="col-md-6" id="original_quotation_view_rander_tab_html">
                        <h6><b><?=$cus_data->title?></b></h6>
                        <?php echo $org_lead;?>
                    </div>
                </div>
            </div>

            <div id="update_lead" class="tabcontent">
                <div class="width_full" id="update_lead_div" style="display:block;">
                <form class="" name="lead_update_frm" id="lead_update_frm" method="post">
                <div class="general_update_textera back_color_tsf boder-raduis10" id="genupdate">
                <div class="row">
                <div id="gen_updt_res" class="alert alert-success no_display">Successfully Added Comment</div>
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>" />

                <div class="col-md-12 form-group leads-label-text">
                <div class="top-auto-full">
                    <label>Describe Comments<span class="text-danger">*</span>:</label>
                    <div class="lead-dropdown">
                        <div class="dropdown">
                            <button class="btn-dropdown" type="button" data-toggle="tooltip" data-placement="top" title="Choose Pre-Define Comment">
                            <i class="fa fa-commenting" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu left">
                                <div class="user_comment">
                                    <div class="user_header">
                                        
                                        <div class="pull-right">
                                            <a href="#" class="cbtn add-new-com-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                                        </div>
                                    </div>
                                    <div id="lead_scroller" class="default-scoller">                                  
                                                                    
                                    </div>
                                    <div class="select-action">
                                        <button type="button" class="custom_blu btn btn-primary" id="comment_txt_submit">Add</button>
                                        <button type="button" class="custom_blu btn btn-primary" id="comment_pop_close">Close</button>
                                    </div>
                                </div>
                                <div class="edit-user_comment">
                                    <div class="user_header">                                
                                        <div class="pull-right">
                                            <a href="#" class="cbtn go-back"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Title<span class="text-danger">*</span> <small class="text-danger" id="edit_pre_define_title_error"></small></label>
                                        <input class="form-control" type="text" name="edit_pre_define_title" id="edit_pre_define_title">
                                        
                                    </div>
                                    <div class="form-group">
                                        <label>Description<span class="text-danger">*</span><small class="text-danger" id="edit_pre_define_description_error"></small></label>
                                        <textarea class="form-control" name="edit_pre_define_description" id="edit_pre_define_description"></textarea>
                                        
                                    </div>
                                    <div class="select-action">
                                        <input type="hidden" name="pre_define_comment_id" id="pre_define_comment_id">
                                        <button type="button" class="custom_blu btn btn-primary" id="edit_pre_define_comment_confirm">Save</button>
                                    </div>
                                </div>
                                <div class="add-user_comment">
                                    <div class="user_header">                                
                                        <div class="pull-right">
                                            <a href="#" class="cbtn go-back"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Title<span class="text-danger">*</span> <small class="text-danger" id="pre_define_title_error"></small></label>
                                        <input class="form-control" type="text" name="pre_define_title" id="pre_define_title">
                                        
                                    </div>
                                    <div class="form-group">
                                        <label>Description<span class="text-danger">*</span> <small class="text-danger" id="pre_define_description_error"></small></label>
                                        <textarea class="form-control" name="pre_define_description" id="pre_define_description"></textarea>                        
                                    </div>
                                    <div class="select-action">
                                        <button type="button" class="custom_blu btn btn-primary" id="add_pre_define_update_lead_comment">Add New</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    &nbsp;
                    <div class="div-mail-trail">
                        <label class="check-box-sec">
                            <input type="checkbox" name="mail_trail_check" id="mail_trail_check">
                            <span class="checkmark"></span>
                        </label>
                        Add Mail Trail
                    </div>
                    <input type="hidden" name="lead_comments_for_mail_trail" id="lead_comments_for_mail_trail" value="">
                </div>
                <textarea placeholder="Type your comment" rows="3" name="general_description" id="general_description" class="form-control basic-wysiwyg-editor"  cols=""></textarea>
                </div> 
                <div class="col-md-12 pr-0">
                <div class="col-md-4 leads-label-text">
                <div class="form-group row">
                <label for="usr">CC to Employee:</label>
                <div class="">
                <?php if(count($user_list)){?>
                <select class="form-control input-sec select2" id="mark_cc_mail" name="mark_cc_mail[]" multiple>
                <?php foreach($user_list as $user){ ?>
                <option value="<?php echo $user->email; ?>">
                <?php echo $user->email .'( Emp ID: '.$user->id.')'; ?>
                </option>
                <?php } ?>
                </select>
                <?php } ?>
                </div>
                </div>
                </div>
                <div class="col-md-4 leads-label-text">
                <div class="form-group">
                <label for="usr">Update Type<span class="text-danger">*</span>:</label>

                <select id="communication_type" class="form-control input-sec" name="communication_type">
                <option value="">Select</option>
                <?php foreach($communication_list as $comm_data) {  ?>
                <option value="<?php echo $comm_data->id;?>" <?php if($comm_data->id==$opportunity_data->communication_type){?> selected="selected"
                <?php } ?>>
                <?php echo $comm_data->title;?>
                </option>
                <?php } ?>                                
                </select>
                </div>
                </div>
                <div class="col-md-4 lend-date-sec leads-label-text" <?php if($cus_data->current_stage_id=='4'){echo'style="display:none;"';} ?>>
                    <div class="form-group">
                    <label>Next Follow-up Date<span class="text-danger" id="next_follow_star">*</span>:</label>
                    <div class="input-prepend">
                    <span class="add-on input-group-addon">
                    <img src="<?php echo assets_url()?>images/calendar.png"/>
                    </span>
                    <?php
                    $next_followup_date='';
                    if($lead_data->followup_date!='' || $lead_data->followup_date!='0000-00-00')
                    {
                    if($lead_data->followup_date<date("Y-m-d"))
                    {
                    $next_followup_date='';
                    }
                    else
                    {
                    $next_followup_date=date_db_format_to_display_format($lead_data->followup_date);
                    }
                    }
                    ?>
                    <input type="text" class="form-control drp input-sec" name="next_followup_date" id="next_followup_date" placeholder="Next Followup.." value="<?php echo $next_followup_date; ?>" readonly="true" />
                </div>
                </div>
                </div>
                <div class="clear"></div>

                <div class="col-md-12">
                <div class="row">
                <div class="col-md-4 ff">
                    <label class="check-box-sec">
                        <input type="checkbox" name="mail_to_client" id="mail_to_client" value="Y">
                        <span class="checkmark"></span>
                    </label>
                        Mail to Client            
                </div>
                <div class="col-md-8 ff" <?php if($cus_data->current_stage_id=='4'){echo'style="display:none;"';} ?>>
                    <label class="check-box-sec">
                        <input type="checkbox" name="client_not_interested" id="client_not_interested" value="Y" <?php if($cus_data->current_stage_id=='3' || $cus_data->current_stage_id=='5' || $cus_data->current_stage_id=='6' || $cus_data->current_stage_id=='7'){echo'disabled';} ?>>
                        <span class="checkmark"></span>
                    </label>
                    <font class="text-red">Regret This Lead</font>            
                    <small id="regret_reason_text" class="text-danger">
                        <?php  if($cus_data->current_stage_wise_msg){
                        echo '( '.$cus_data->current_stage_wise_msg.' )'; } 
                        ?>
                    </small>
                </div>
                </div>
                </div> 
                <div class="col-md-12 leads-label-text">    
                <div id="update_lead_mail_to_client_mail_subject_div" style="display:none;" class="form-group row">
                <label>Mail Subject<span class="text-danger">*</span>:</label>            
                <input type="hidden" id="mail_to_client_mail_subject" name="mail_to_client_mail_subject" value="" class="form-control" />
                </div>

                <div id="update_lead_regret_this_lead_mail_subject_div" style="display:none;" class="form-group row"> 
                <div class="">
                    <label>Regret Reasons<span class="text-danger">*</span>:</label>
                    <select name="lead_regret_reason_id" id="lead_regret_reason_id" class="form-control">
                        <option value="">==Select Reason==</option>
                        <?php foreach($regret_reason_list as $row){ ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="">
                    <label style="margin-top: 10px;">Mail Subject<span class="text-danger">*</span>:</label>
                    <input type="hidden" id="regret_this_lead_mail_subject" name="regret_this_lead_mail_subject" value="" class="form-control" />
                </div>
                <input type="hidden" name="lead_regret_reason" id="lead_regret_reason">
                               
                </div>            
                </div>

                <div class="col-md-12 leads-label-text" style="margin-top: 10px;">                 
                <div class="form-group row">
                    <div class="plr-0 col-md-8">
                        <div class="custom_upload auto-width">
                            <label for="lead_attach_file">Attach File</label>
                            <input type="file" name="lead_attach_file[]" id="lead_attach_file" multiple="">
                            
                        </div>
                        <div class="upload-name-holder"></div>
                    </div>
                    <div class="plr-0 col-md-4 text-right">
                        <input type="hidden" name="lead_attach_file_removed" id="lead_attach_file_removed" value="">
                    <button type="button" class="btn btn-primary btn-round-shadow submit-padding pull-right" onclick="general_update()" id="lead_edit_confirm">Submit </button>
                    </div>
                </div>         
                </div>         

                </div>
                <div class="clear"></div>
                <div class="align-self-center">
                <input type="hidden" name="lead_id" value="<?php echo $cus_data->id;?>" />
                <div class="text-right mt-15">

                </div>
                </div>
                </div>
                </div>
                </form>
                </div>
            </div>

            <div id="lead_history" class="tabcontent">
                <div id="hist_list" class="lead_history_text" style="display: block;">
                    <?php 
                    if($comment_list) 
                    {      
                        foreach($comment_list as $comment_data) 
                        { 
                        ?>
                        <div class="one_p">
                            <h5><?=$comment_data->title;?></h5>
                            <p>
                                <b>Date:</b>
                                <?php echo date("d-M-Y h:i:s A", strtotime($comment_data->create_date));?> |
                                <b>Update By:</b>
                                <?php echo $comment_data->updated_by;?> |
                                <b>IP.</b>
                                <?php echo $comment_data->ip_address;?>
                                <?php if($comment_data->source_name){ ?>
                                | 
                                <b>Source:</b>
                                <?php echo $comment_data->source_name;?>
                                <?php } ?>
                                <?php 
                                if($comment_data->attach_file)
                                {
                                    $attach_file_arr=explode("|$|", $comment_data->attach_file); 
                                    foreach($attach_file_arr AS $file)
                                    {
                                    ?>
                                    <b>
                                        <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download/<?php echo base64_encode($file); ?>"> Download <i class="fa fa-download" aria-hidden="true"></i></a>
                                    </b>
                                    &nbsp;
                                <?php 
                                    }
                                } 
                                ?>
                            </p>
                            <?php if($comment_data->communication_type){ ?>
                            <p>
                                <b>Communication Type:</b>
                                <?php echo $comment_data->communication_type;?>
                                    <?php if($comment_data->next_followup_date!='0000-00-00'){ ?>
                                        | <b>Next Followup Date:</b>
                                        <?php echo date_db_format_to_display_format($comment_data->next_followup_date);?>
                                            <?php } ?>
                            </p>
                            <?php 
                            } 
                            ?>
                            <?php if($comment_data->quotation_id){ ?>
                                <p>
                                    <b>Quotation:</b>
                                    <?php echo '#'.$comment_data->quotation_id.' - '.$comment_data->quotation_title;?>
                                </p>
                                <?php } ?>
                                <p>
                                    <?php //echo str_replace('#','For ',$comment_data->comment);
                                echo strip_tags($comment_data->comment);
                                    if($comment_data->mail_trail_html)
                                    {
                                        echo'<br>';
                                        echo stripcslashes($comment_data->mail_trail_html);
                                    }
                                    
                                ?>
                                </p>
                                <?php if($comment_data->mail_to_client=='Y'){?>
                                    <p><b>Mail to Client:</b> Yes</p>
                                <?php }?>
                        </div>
                        <?php 
                        }
                    }
                    else
                    {
                        ?>
                        <div class="update_proposal">
                            <div class="width_full">No History Found!</div>
                        </div>
                    <?php 
                    }
                    ?>
                </div>
            </div>

            <div id="company_info" class="tabcontent">
                <div id="cus_updt_res" class="alert alert-success no_display" style="float:left; width: 100%;">Customer Updated Successfully
                </div>

                <div class="back_color_tsf boder-raduis10" id="contact_information_div" style="display: block">
                    <div class="col-md-6">
                        <table class="table">
                    <tr>
                                <th width="30%">Company ID <div class="pull-right">:</div></th>
                                <td class="text-left" id="">
                                    <?php echo $cus_data->customer_id;?>
                                </td>
                            </tr>
                            <tr>
                                <th width="30%">Company Name <div class="pull-right">:</div></th>
                                <td class="text-left" id="company_name_div">
                                    <?php echo ($cus_data->cus_company_name)?$cus_data->cus_company_name:'N/A';?>
                                </td>
                            </tr>
                            <tr>
                                <th width="30%">Contact Person <div class="pull-right">:</div></th>
                                <td class="text-left" id="contact_person_div">
                                    <?php echo ($cus_data->cus_contact_person)?$cus_data->cus_contact_person:'Purchase Manager';?>
                                </td>
                            </tr>
                            <?php if($cus_data->cus_address){ ?>
                                <tr>
                                    <th width="30%">Address <div class="pull-right">:</div></th>
                                    <td class="text-left">
                                        <?php echo $cus_data->cus_address;?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th width="30%">Email <div class="pull-right">:</div></th>
                                <td class="text-left" id="email_div">
                                    <?php echo ($cus_data->cus_email)?$cus_data->cus_email:'N/A';?>
                                        <?php if($cus_data->cus_alt_email){ ?> /
                                            <?php echo $cus_data->cus_alt_email;?>
                                                <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">Mobile <div class="pull-right">:</div></th>
                                <td class="text-left" id="mobile_div">
                                    <?php echo ($cus_data->cus_mobile)?$cus_data->cus_mobile:'N/A';?>
                                        <?php if($cus_data->cus_alt_mobile){ ?> /
                                            <?php echo $cus_data->cus_alt_mobile;?>
                                                <?php } ?>
                                </td>
                            </tr>
                    
                    <tr>
                                <th width="30%">Country <div class="pull-right">:</div></th>
                                <td class="text-left" id="country_div">
                                    <?php echo ($cus_data->cus_country)?$cus_data->cus_country:'N/A';?>
                                        
                                </td>
                            </tr>

                                    <tr>
                                        <td colspan="2">
                                            <a href="javascript:;" class="btn btn-primary get_detail_modal" data-id="<?php echo $cus_data->customer_id; ?>">View Company</a>

                                            <a href="javascript:;" class="btn btn-primary" id="edit_customer_view" data-id="<?php echo $cus_data->customer_id; ?>">Edit Company</a>

                                        </td>
                                    </tr>
                        </table>
                    </div>
                </div>
            </div>
            <?php */ ?>

        </div>

            </div>            
        </div>
        <input type="hidden" id="lead_id" name="lead_id" value="<?php echo $cus_data->id;?>" />
        <input type="hidden" name="selected_prod_id" id="selected_prod_id" value="<?=$temp_prod_id;?>" />
        </div>
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
<input type="hidden" id="lead_id" name="lead_id" value="<?php echo $cus_data->id;?>" />
<input type="hidden" name="selected_prod_id" id="selected_prod_id" value="<?=$temp_prod_id;?>" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script>       
</div>
</div>
</div>
<style type="text/css" media="screen">
.no-display{
    display: none;
}
.display-block{
    display: block;
}
#loader {
  position: fixed;
  top: 85%; left: 48%;
  transform: translate(-50%, -50%);
}
</style>
<!-- <link rel="stylesheet" href="<?=assets_url();?>plugins/jquery-ui/jquery-ui.min.css"> -->
<!-- <script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script> -->
<script type="text/javascript">
tinymce.init({
    selector: 'textarea.basic-wysiwyg-editor',
    force_br_newlines: true,
    force_p_newlines: false,
    forced_root_block: '',
    menubar: false,
    statusbar: false,
    toolbar: false,
    // setup: function(editor) {
    //      editor.on('focusout', function(e) {
    //        console.log(editor); 
    //        var updated_field_name=editor.id;
    //        var updated_content=editor.getContent();
    //        alert(updated_content);
    //        check_submit();
    //      });
    //  }
});
tinymce.init({
    selector: 'textarea.moderate-wysiwyg-editor',
    // height: 300,
    menubar: false,
    statusbar: false,
    plugins: ["code,advlist autolink lists link image charmap print preview anchor"],
    toolbar: 'bold italic backcolor | bullist numlist',
    content_css: [],
    setup: function(editor) {
        editor.on('focusout', function(e) {
            // console.log(editor);
            // var quotation_id=$("#quotation_id").val();
            // var updated_field_name=editor.id;
            // var updated_content=editor.getContent();
            // fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
            //check_submit();
        });
    }
});
</script>
<script type="text/javascript">
window.paceOptions = {
    document: true,
    eventLag: true,
    restartOnPushState: true,
    restartOnRequestAfter: true,
    ajax: {
        trackMethods: ['POST', 'GET']
    }
};
</script>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<script src="<?=assets_url();?>vendor/pace/pace.js"></script>
<script src="<?=assets_url();?>vendor/tether/dist/js/tether.js"></script>
<script src="<?=assets_url();?>vendor/fastclick/lib/fastclick.js"></script>
<script src="<?=assets_url();?>scripts/constants.js"></script>
<script src="<?=assets_url();?>scripts/main.js"></script>
<script src="<?=assets_url();?>vendor/parsleyjs/dist/parsley.min.js"></script>
<script src="<?=assets_url();?>scripts/helpers/tsf/js/tsf-wizard-plugin.js"></script>
<link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload.css" />
<link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload-ui.css" />
<!-- <script src="<?php echo assets_url(); ?>plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script> -->
<script src="<?=assets_url();?>vendor/jquery.ui/ui/core.js"></script>
<script src="<?=assets_url();?>vendor/jquery.ui/ui/widget.js"></script>
<script src="<?=assets_url();?>vendor/jquery.ui/ui/mouse.js"></script>
<script src="<?=assets_url();?>vendor/jquery.ui/ui/draggable.js"></script>
<script src="<?=assets_url();?>vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
<script src="<?=assets_url();?>vendor/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
<script src="<?=assets_url();?>vendor/blueimp-file-upload/js/jquery.fileupload.js"></script>

<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?=assets_url();?>scripts/forms/upload.js"></script>
<script src="<?php echo assets_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="<?php echo assets_url();?>js/common_functions.js"></script>
<script src="<?php echo assets_url();?>js/custom/lead/edit.js?v=<?php echo rand(0,1000); ?>"></script>
<script src="<?php echo assets_url();?>js/custom/quotation/generate_view.js?v=<?php echo rand(0,1000); ?>"></script>
<!-- <script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script> -->
  
 
<script>
function open_form() {
    $('#sec1 .display').toggle();
    $('#sec1 .no_display').toggle();
}
function openbox(val, id) {
    if (val == '1') {
        $('#genupdate').show();
        $('#new_opportunity').hide();
        //$('#update_opportunity').hide();
    } else if (val == '2') {
        $('#new_opportunity').show();
        $('#genupdate').hide();
        //$('#update_opportunity').hide();
    } else if (val == '3') {
        //$('#update_opportunity').show();
        $('#new_opportunity').hide();
        $('#genupdate').hide();
    } else {
        return false;
    }
}
</script>
<script type="text/javascript">
window.paceOptions = {
    document: true,
    eventLag: true,
    restartOnPushState: true,
    restartOnRequestAfter: true,
    ajax: {
        trackMethods: ['POST', 'GET']
    }
};
function toggleAndChangeText() {
    $('#sec1').toggle();
    if ($('#sec1').css('display') == 'none') {
        $('#cust_info').html('(Show Details)');
    } else {
        $('#cust_info').html('(Hide Details)');
    }
}
function update_customer() {
    var first_name = document.getElementById('first_name').value;
    var last_name = document.getElementById('last_name').value;
    var office_phone = document.getElementById('office_phone').value;
    var website = document.getElementById('website').value;
    var address = document.getElementById('address').value;
    var mobile = document.getElementById('mobile').value;
    var state = document.getElementById('state').value;
    var zip = document.getElementById('zip').value;
    var company_name = document.getElementById('company_name').value;
    var country_id = document.getElementById('country_id').value;
    var city = document.getElementById('city').value;
    var customer_id = document.getElementById('customer_id').value;
    var lead_id = document.getElementById('lead_id').value;

    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/edit_ajax",
        type: "POST",
        data: {
            'first_name': first_name,
            'last_name': last_name,
            'office_phone': office_phone,
            'website': website,
            'address': address,
            'mobile': mobile,
            'state': state,
            'zip': zip,
            'company_name': company_name,
            'country_id': country_id,
            'city': city,
            'command': '1',
            'customer_id': customer_id,
            'lead_id': lead_id
        },
        async: true,
        success: function(response) {
            //open_form();
            $('#sec1').html(response);
            $('#cus_updt_res').show();
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function update_lead() {
    var title = document.getElementById('title').value;
    var assigned_to = document.getElementById('assigned_to').value;
    var ssource = document.getElementById('source').value;
    var datepicker2 = document.getElementById('datepicker2').value;
    var customer_id = document.getElementById('customer_id').value;
    var description = document.getElementById('description').value;
    var lead_id = document.getElementById('lead_id').value;

    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/edit_ajax",
        type: "POST",
        data: {
            'title': title,
            'assigned_to': assigned_to,
            'source': ssource,
            'enquiry_date': datepicker2,
            'description': description,
            'command': '1',
            'lead_id': lead_id,
            'customer_id': customer_id
        },
        async: true,
        success: function(response) {
            //open_form();
            $('#sec2').html(response);
            $('#lead_updt_res').show();
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function sel_multiple(id, row_id) 
{

    if ($('#select_' + id).is(":checked")) {
        $('#err_prod').hide();
        var mail_id = $("#selected_prod_id");
        var mail_id_val = mail_id.val();
        if (mail_id_val != '') {
            if (mail_id_val.match(id + ',')) {
                //swal('already added');

            } else {
                mail_id.val(mail_id.val() + id + ',');
            }

        } else {

            mail_id.val(mail_id.val() + id + ',');
        }

    } else {

        var mail_id = $("#selected_prod_id");
        var mail_val = mail_id.val();
        mail_id.val(mail_val.replace(id + ',', ''));
    }
}
function sel_multiple_update(id, row_id, opp_id) {

    if ($('#select_' + id).is(":checked")) {
        $('#err_prod_update').hide();
        var mail_id = $("#selected_prod_id_update_" + opp_id);
        var mail_id_val = mail_id.val();
        if (mail_id_val != '') {
            if (mail_id_val.match(id + ',')) {
                // swal('already added');
                // return false;
            } else {
                mail_id.val(mail_id.val() + id + ',');
            }

        } else {
            mail_id.val(mail_id.val() + id + ',');
        }

    } else {
        var mail_id = $("#selected_prod_id_update_" + opp_id);
        var mail_val = mail_id.val();
        mail_id.val(mail_val.replace(id + ',', ''));
    }
}
function remove_attr() {
    if ($("input:radio[name='tag_rad']").is(":checked")) {
        $('#next').removeAttr('disabled');
    }
}
function getoption() {
    var val = document.getElementById('email').value;
    var val1 = document.getElementById('mobile').value;
    if (val != '' && val1 != '') {

        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getfirstoption_new",
            type: "POST",
            data: {
                'email': val
            },
            async: true,
            success: function(response) {
                //$('#first_option').html(response);                    
                //getmobile();
                $('#second_option').html(response);

            },
            error: function() {
                //$.unblockUI();
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function getmobile() {
    var val = document.getElementById('email').value;
    var val1 = document.getElementById('mobile').value;
    if (val != '') {
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getmobileno",
            type: "POST",
            data: {
                'email': val,
                'mobile': val1
            },
            async: true,
            success: function(response) {

                $('#customer_id').val(response);
                if ($("input:radio[name='tag_rad']").is(":checked")) {
                    $('#next').removeAttr('disabled');
                }
            },
            error: function() {
                //$.unblockUI();
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function getsecondoption() {
    var val = document.getElementById('email').value;
    var val1 = document.getElementById('mobile').value;
    $("#second_option").html('<img src="<?=base_url();?>images/fetch.gif" alt="" height="150" width="200" />');
    $("#second_option").hide();
    if (val != '' && val1 != '') {
        $("#second_option").show();
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getsecondoption_edit",
            type: "POST",
            data: {
                'mobile': val1,
                'email': val,
                'customer_id': '<?php echo $cus_data->customer_id?>'
            },
            success: function(response) {
                $("#second_option").html(response);
                getmobile();

            },
            error: function() {
                //$.unblockUI();
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function getemail(val) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url'];?>/lead/getemail",
        type: "POST",
        data: {
            'mobile': val
        },
        async: true,
        success: function(response) {
            var data = response.split('&');
            var val1 = document.getElementById('email').value;
            if (val1 == '' || val1 == null) {
                $('#email').val(data[0]);
            }

            //$('#mobile').val(data[1]); 
            $('#customer_id').val(data[2]);

            if ($("input:radio[name='tag_rad']").is(":checked")) {
                $('#next').removeAttr('disabled');
            }
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
</script>
<script type="text/javascript">
function setnewcus() {
    var rad = $('input[name=tag_rad]:checked', '#form').val();
    if (rad == '1') {
        var email = document.getElementById('email').value;
        var mobile = document.getElementById('mobile').value;
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/setnewcustomer",
            type: "POST",
            data: {
                'mobile': mobile,
                'email': email
            },
            success: function(response) {
                window.location.href = "<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/add/";

            },
            error: function() {
                //$.unblockUI();
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    }
}
function GetQuoteLeadList(opportunity_id) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/getquotelist_ajax",
        type: "POST",
        data: {
            'opportunity_id': opportunity_id
        },
        async: true,
        success: function(response) {
            $('#quotation_list_all').html(response);
            $('#quotation_list').modal();
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function close_modal() {
    $('#prod_lead').modal('toggle');
}
function GetStateList(cont) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getstatelist",
        type: "POST",
        data: {
            'country_id': cont
        },
        success: function(response) {
            if (response != '') {
                document.getElementById('state').innerHTML = response;
            }
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {
        });
        }
    });
}
function GetCityList(state) {
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getcitylist",
        type: "POST",
        data: {
            'state_id': state
        },
        success: function(response) {
            if (response != '') {
                document.getElementById('city').innerHTML = response;
            }

        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
}
function calculate_price_update(unit_prod, price_prod, total_id, disc_id, grand_total, prod_id, opp_id, currency_id, main_price) {
    var qty = $('#' + unit_prod).val();
    var price = $('#' + price_prod).val();
    var disc = $('#' + disc_id).val();
    var currency_id = $('#' + currency_id).val();
    var main_price = $('#' + main_price).val();
    var f_tot = 0;
    if (parseInt(main_price) < parseInt('1')) {
        $.ajax({
            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/update_prod_price_ajax",
            type: "POST",
            data: {
                'price': price,
                'prod_id': prod_id
            },
            success: function(response) {
                swal('Master Product price updated');
                $('#' + price_prod).val(parseInt(price));
            },
            error: function() {
                //$.unblockUI();
                swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
            }
        });
    } else if (parseInt(price) < parseInt(main_price)) {
        swal('Your price is lower actual price');
        $('#' + price_prod).val(parseInt(main_price));
        return false;
    }

    var tot = parseInt(qty) * parseInt(price);
    var tot_n = (parseInt(disc) / parseInt('100')) * parseInt(tot);
    f_tot = parseInt(tot) - parseInt(tot_n);
    $('#' + total_id).val(parseInt(f_tot));
    $('#' + grand_total).html(parseInt(f_tot));

    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/update_opportunity_prod_ajax",
        type: "POST",
        data: {
            'quantity': qty,
            'price': price,
            'discount': disc,
            'prod_id': prod_id,
            'opp_id': opp_id,
            'currency_id': currency_id
        },
        success: function(response) {
            console.log('success');
        },
        error: function() {
            //$.unblockUI();
            swal({
                title: 'Something went wrong there!',
                text: '',
                type: 'danger',
                showCancelButton: false
            }, function() {

        });
        }
    });
    var sum = 0;
    $(".amount1").each(function(i) {
        var val = $(this).val();
        if (val != "") {

            sum = sum + parseFloat(val);
        } else {
            sum = sum + 0;
        }
    });

    $('#sub_total_update_' + opp_id).html(parseInt(sum));
    $('#all_currency_update_' + opp_id).val(parseInt(sum));
    //$('#deal_value_update_'+opp_id).val(parseInt(sum));
}

function change_currency_update(id, div, currency_type_name_new, all_row_class) {

    var value = $("#" + id + " option:selected").val();
    var val = $("#" + id + " option:selected").text();
    var val2 = val.match(/\((.*)\)/);

    $("#" + div).html(val2[1]);
    $("." + currency_type_name_new).text(val2[1]);
    $("." + all_row_class).text(val2[1]);
}

var st = "yes";
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    if(st == "no"){
        var nowy = $('.tab_gorup.lead-edit-tab').offset().top - 80;
        var scroll = $(window).scrollTop();
        $('html, body').animate({
                scrollTop: $(".tab_gorup.lead-edit-tab").offset().top-80
            }, 500);
        // alert(nowy+', '+scroll)
        // if (scroll > nowy) {            
        // }
    }
    if(st == "yes"){
        st = "no"
    }   
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();
</script>
</body>

</html>
<!-- START -->
<!-- MODAL HTML -->
<!-- MODAL HTML -->
<!-- END -->
<!--  ====== PO UPLOAD: START =========== -->
<?php /* ?>
<div id="po_upload_modal" class="modal fade upload-oder" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">
        <!-- Modal content-->
        <form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
            <input type="hidden" name="po_lead_opp_id" id="po_lead_opp_id" value="">
            <input type="hidden" name="po_lead_id" id="po_lead_id" value="">
            <input type="hidden" name="deal_value_as_per_purchase_order" id="deal_value_as_per_purchase_order" value="0">
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
                                            <label for="inputPassword" class="col-sm-4 col-form-label">CC to Employee</label>
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
                                    <div class="col-md-6" style="display: none;">
                                        <div class="check-box-ar ff">
                                            
                                            <label class="check-box-sec">
                                                <input class="styled-checkbox" id="styled-checkbox-1" type="checkbox" value="Y" name="po_upload_sent_ack_to_client" id="po_upload_sent_ack_to_client" checked="checked">
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
<!-- CREATE QUOTATION VIEW -->
<!-- generateQuotationModal -->
<div class="modal fade modal-big" id="generateQuotationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Quotation</h4>
      </div>
      <div class="modal-body pl-30 pr-30">
        <div class="quotation-search">
            <div class="form-group">
                <label>Select Product(s)</label>
                <div class="from-full row">
                    <div class="col-md-10 pr-0">
                        <input type="text" class="form-control search_product_by_keyword" placeholder="Enter Product Name">
                    </div>
                    <div class="col-md-2 pl-0">
                        <button id="search_product_by_keyword" class="btn btn-default btn-primary mt-0">Search</button>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-auto">
                    <div class="search-item">
                        <a href="#" class="search-remove"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                        <span>OnePlus 7 Mobile Phone</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="search-item">
                        <a href="#" class="search-remove"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                        <span>Mobile 12x</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <table class="table custom-table-white table-color">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Unit Type</th>
                            <th>Sale Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label class="check-box-sec">
                                    <input type="checkbox" name="" value="">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td>OnePlus 7 Mobile Phone</td>
                            <td>1</td>
                            <td>Pieces</td>
                            <td>INR 10000.00</td>
                        </tr>
                        <tr>
                            <td>
                                <label class="check-box-sec">
                                    <input type="checkbox" name="" value="">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td>OnePlus 7 Mobile Phone</td>
                            <td>1</td>
                            <td>Pieces</td>
                            <td>INR 10000.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <ul class="col-same-bt">
                    <li><a href="#" class="btn btn-primary btn-round-shadow semi-big-bt">Close</a></li>
                    <li><a href="#" class="btn btn-primary btn-round-shadow semi-big-bt">Add</a></li>
                    <li><a href="#" class="btn btn-primary btn-round-shadow semi-big-bt custom-model-open" href="#" data-show="#customFinalQuotationModal" data-hide="#generateQuotationModal">Save & Proceed</a></li>
                </ul>
            </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
  <div class="modal fade mail-modal modal-fullscreen" id="PoUploadLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>
<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->


<!-- quotationModal -->
<div class="modal fade" id="customQuotationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Quotation</h4>
      </div>
      <div class="modal-body pl-30 pr-30">
        <div class="quotation-send">
            
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label">To</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control-plaintext" placeholder="" value="swarup@gmail.com">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label">Subject</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control-plaintext" placeholder="" value="Requirement for Cotton Canvas Fabric">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label"></label>
                <div class="col-sm-10 lh-20">
                  <label class="check-box-sec f-l mr-6">
                      <input type="checkbox" name="mail_to_client" id="mail_to_client" value="Y">
                      <span class="checkmark"></span>
                  </label>
                  Attached company brochure to buyer by mail if avilable
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 no-padd-label"></label>
                <div class="col-sm-10">
                  <label for="quotation_upload" class="btn btn-primary btn-round-shadow quotation-bt">
                      <input type="file" name="quotation_upload" class="d-none" id="quotation_upload">
                      Attach File
                  </label>
                  <div class="uploaded_info">
                      <span>demo_file_name.pdf</span>
                      <a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <button type="submit" class="btn btn-primary btn-round-shadow big-bt pull-right">Send Quotation</button>
            </div>
        </div>
      </div>      
    </div>
  </div>
</div>
<script type="text/javascript"> 
$( document ).ready(function() {
    $(document).on("click",".custom-model-open",function(event) {
        event.preventDefault();
        var showModel = $(this).attr('data-show');
        var hideModel = $(this).attr('data-hide');
        $(hideModel).modal('hide');
        ShowModel(showModel);
        //alert("click");
    });
    function ShowModel(getele){
        setTimeout(function(){ 
            $(getele).modal('show');
        }, 500);
    }
});
// STYLE
  var edit = function(cmd) {
    var val = false;
    switch (cmd) {
     case 'formatBlock': val = 'blockquote'; break;
     case 'createLink': val = prompt('Enter the URL to hyperlink to from the selected text:'); break;
     case 'insertImage': val = prompt('Enter the image URL:'); break;
    }
    document.execCommand(cmd, false, val);
    box.focus();
  }
  function simpleEditer()
  {
    // $(".tools").show();
    var box = $('.buying-requirements');
    box.attr('contentEditable', true);

    

    // EDITING LISTENERS
    $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
       edit($(this).data('cmd'));
    });    
  }
  function inactiveSimpleEditer()
  {
    $(".tools").hide();
    var box = $('.buying-requirements');
    box.attr('contentEditable', false);
  }
</script>
