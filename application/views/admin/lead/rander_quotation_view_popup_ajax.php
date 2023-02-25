
<div class="modal-dialog" role="document">
   <div class="modal-content">
     
     <div class="modal-body">
         <a href="#" class="close-modal" data-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
             <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"/>
         </svg>
         </a>
         <div class="lead-loop">
             <div class="lead-top">

                 <div class="mail-form-row pr-45">
                     <div class="auto-row">
                         <label>Quotation Count</label>
                     </div>

                     <div class="page-holder float-right mr-15">
                        <ul class="action-ul">
                          <li class="mr-15"><span class="mail-info">
                           <?php 
                           $key = array_search($id, $all_ids);
                           echo ($key+1);

                           if($key>0)
                              $pre=($key-1);
                           if($key<count($all_ids))
                              $nxt=($key+1);
                           ?> of <?php echo count($all_ids); ?> </span></li>
                          <li>
                            <a href="JavaScript:void(0)" title="previous" data-id="<?php echo $all_ids[$pre]; ?>" data-quotedlids="<?php echo $lead_ids;?>" class="new_quotation_view_popup"><img src="<?php echo assets_url(); ?>images/left_black.png"></a>
                          </li>
                          <li>
                            <a href="JavaScript:void(0)" title="Next" data-id="<?php echo $all_ids[$nxt]; ?>" data-quotedlids="<?php echo $lead_ids;?>" class="new_quotation_view_popup"><img src="<?php echo assets_url(); ?>images/right_black.png"></a>
                          </li>
                        </ul>
                      </div>
                     
                 </div>
                 <div class="mail-form-row">
                     <div class="auto-row">
                         <label>Title:</label>
                         <?=$row->opportunity_title;?> #<?=$row->id;?>
                     </div>
                     <div class="auto-row">
                        <label>Status:</label>
                        <span class="<?php echo $row->status_class_name; ?>">
                        <?php echo $row->stage_name; ?>
                        </span>
                     </div>
                     <div class="auto-row">
                         <label>Deal Value:</label>
                         <?php echo $row->currency_code; ?> <?php echo $row->deal_value; ?>
                     </div>
                     <div class="auto-row">
                         <label>No. of Product(s):</label>
                          <?php echo $row->product_count; ?>
                     </div>
                     
                 </div>

                 <div class="mail-form-row">
                     <div class="auto-row">
                         <label>Created On.:</label>
                         <?php echo date_db_format_to_display_format($row->create_date);?>
                     </div>
                     <div class="auto-row">
                         <label>Last Modified On.:</label>
                         <?php echo date_db_format_to_display_format($row->modify_date);?>
                     </div>
                     <div class="auto-row">
                         <label>Quotation Sent On.:</label>
                         <?php echo date_db_format_to_display_format($row->quotation_sent_on);?>
                     </div>
                     
                     
                 </div>

                 <div class="mail-form-row">
                     <div class="auto-row">
                         <label>Quotation Type:</label>
                         <?php if($row->is_extermal_quote=='Y'){
                        echo'Custom';            
                        } else{
                        echo'Automated';
                        } ?>
                     </div>

                     <div class="auto-row">
                         <label>Purchase Order Status:</label>
                         <?php 
                         if($row->is_po_received=='Y'){
                            echo'<span class="text-success"> <b>Received</b></span>';
                            if($row->po_file_name)
                            {
                                echo ' (<a href="'.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/download_po/'.$row->lowp_id.'">  Download PO <i class="fa fa-cloud-download" aria-hidden="true"></i></a> )';
                            }
                            
                            } else{
                            echo'<span class="text-danger"><b>Not Received</b></span>';
                            } ?>
                     </div>
                     
                     
                 </div>

                 <div class="mail-form-row">
                     <div class="auto-row">
                        <?php if($row->is_extermal_quote=='N'){ ?>
                        <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$row->id.'/'.$row->q_id);?>" class="lead-btn grey-bg" target="_blank">
                             <svg xmlns="http://www.w3.org/2000/svg" width="12" height="16" viewBox="0 0 12 16">
                                 <path id="Icon_awesome-file-pdf" data-name="Icon awesome-file-pdf" d="M5.684,8a3.015,3.015,0,0,1-.062-1.466C5.884,6.537,5.859,7.691,5.684,8ZM5.631,9.478a14.42,14.42,0,0,1-.888,1.959,11.507,11.507,0,0,1,1.966-.684A4.048,4.048,0,0,1,5.631,9.478Zm-2.941,3.9c0,.025.412-.169,1.091-1.256A4.319,4.319,0,0,0,2.691,13.378ZM7.75,5H12V15.25a.748.748,0,0,1-.75.75H.75A.748.748,0,0,1,0,15.25V.75A.748.748,0,0,1,.75,0H7V4.25A.752.752,0,0,0,7.75,5ZM7.5,10.369A3.136,3.136,0,0,1,6.166,8.688a4.492,4.492,0,0,0,.194-2.006.783.783,0,0,0-1.494-.212,5.2,5.2,0,0,0,.253,2.406,29.345,29.345,0,0,1-1.275,2.681s0,0-.006,0c-.847.434-2.3,1.391-1.7,2.125A.971.971,0,0,0,2.806,14c.559,0,1.116-.563,1.909-1.931a17.813,17.813,0,0,1,2.469-.725,4.736,4.736,0,0,0,2,.609A.809.809,0,0,0,9.8,10.594c-.434-.425-1.7-.3-2.3-.225Zm4.281-7.087L8.719.219A.749.749,0,0,0,8.188,0H8V4h4V3.809A.748.748,0,0,0,11.781,3.281ZM9.466,11.259c.128-.084-.078-.372-1.337-.281C9.288,11.472,9.466,11.259,9.466,11.259Z"/>
                               </svg>                                  
                               Preview
                         </a>
                        <?php } ?>
                         
                     </div>
                     <div class="auto-row">
                        <?php //print_r($row); ?>
                        <?php if(($row->is_extermal_quote=='Y' && $row->file_name!='') || $row->is_extermal_quote=='N'){ ?>
                            <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/download_quotation/'.$row->id.'/'.$row->q_id);?>" class="lead-btn grey-bg" target="_blank">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                 <g id="Icon_feather-download" data-name="Icon feather-download" transform="translate(-3 -3)">
                                   <path id="Path_4" data-name="Path 4" d="M17.5,22.5v3.667A1.674,1.674,0,0,1,16.056,28H5.944A1.674,1.674,0,0,1,4.5,26.167V22.5" transform="translate(0 -10.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                   <path id="Path_5" data-name="Path 5" d="M10.5,15l2.833,4.583L16.167,15" transform="translate(-2.333 -5.968)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                   <path id="Path_6" data-name="Path 6" d="M18,15.5V4.5" transform="translate(-7)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                 </g>
                               </svg>                                                               
                             Download
                         </a>
                        <?php } ?>                         
                     </div>
					 
					 <div class="auto-row">
                        <?php //print_r($lead_data->cus_mobile); ?>
                        <?php if((($row->is_extermal_quote=='Y' && $row->file_name!='') || $row->is_extermal_quote=='N') && $lead_data->cus_mobile!=''){ ?>
                            <a href="JavaScript:void(0);" class="lead-btn grey-bg quotation_sent_by_whatsapp" data-lid="<?php echo $lead_ids; ?>" data-oppid="<?php echo $row->id; ?>" data-qid="<?php echo $row->q_id; ?>" data-is_quoted="Y">
								<img src="<?php echo assets_url(); ?>images/social-whatsapp.png" style="width:25px;" />                                                      
								Send Quotation
							</a>
                        <?php } ?>                         
                     </div>
                     <?php /* ?>
                     <div class="auto-row">
                         <a href="JavaScript:void(0);" class="lead-btn grey-bg is_copy_confirm" data-url="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/clone_proporal/'.$row->id);?>" data-existingname="<?=$row->opportunity_title;?>">
                             <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="16.875" viewBox="0 0 13.5 16.875">
                                 <g id="Group_60" data-name="Group 60" transform="translate(-232.5 -497.125)">
                                   <g id="Icon_ionic-ios-copy" data-name="Icon ionic-ios-copy" transform="translate(232.5 497.125)">
                                     <path id="Path_7" data-name="Path 7" d="M24.079,4.609h3.164a.212.212,0,0,0,.211-.211h0a.981.981,0,0,0-.352-.749L24.391,1.392a1.315,1.315,0,0,0-.724-.26h0a.26.26,0,0,0-.26.26V3.941A.673.673,0,0,0,24.079,4.609Z" transform="translate(-13.954 -1.129)"/>
                                     <path id="Path_8" data-name="Path 8" d="M15.855,3.937V1.125H11.25A1.128,1.128,0,0,0,10.125,2.25V14.062a1.128,1.128,0,0,0,1.125,1.125h8.437a1.128,1.128,0,0,0,1.125-1.125V5.519H17.437A1.584,1.584,0,0,1,15.855,3.937Z" transform="translate(-7.313 -1.125)"/>
                                     <path id="Path_9" data-name="Path 9" d="M6.328,17.3V5.625h-.7A1.128,1.128,0,0,0,4.5,6.75V19.125A1.128,1.128,0,0,0,5.625,20.25h9a1.128,1.128,0,0,0,1.125-1.125v-.7h-8.3A1.128,1.128,0,0,1,6.328,17.3Z" transform="translate(-4.5 -3.375)"/>
                                   </g>
                                 </g>
                               </svg>
                                                                                             
                             Copy
                         </a>
                     </div>
                     <?php */ ?>

                 </div>

                 <!-- <div class="mail-form-row footer-shadow">
                     <a href="#" class="lead-btn orange pull-left">Upload Purchase Order (PO)</a>
                 </div> -->
                 <?php //print_r($all_ids); ?>
             </div>
             
         </div>
         

     </div>
     
   </div>
 </div>