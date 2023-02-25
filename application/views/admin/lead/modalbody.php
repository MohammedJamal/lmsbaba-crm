 <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="myModalLabel"><?php echo $output->subject;?></h4>
            </div>
            <div class="modal-body">
              <div class="enquery">
           		<p><strong>Buyer Email:</strong><?php echo $output->from_email;?></p>
              	<p><strong>Subject:</strong><?php echo $output->subject;?></p>
              	<p><strong>Attachment:</strong> <?php if($output->is_attach){ echo 'Yes'; }else{ echo 'No'; } ?></p>
              	<p><strong>Status:</strong> <?php if($output->customer_id){ echo 'Repeat'; }else{ echo 'New'; } ?></p>
              	<p><strong>Enquiry Date:</strong> <?php echo $output->email_date;?></p>
              	<p><strong>Description:</strong></p>
              	 <div ><?php echo $output->message;?></div>
                 
                 <div class="checkbox checkbox-primary">
                        <input id="chk_<?php echo $i;?>" class="styled" type="checkbox" value="1" onclick=opentxtar(<?php echo $i;?>)>
                        <label for="checkbox2">
                            Reply to the Buyer
                        </label>
                    </div>
                  
              	 <!--<div class="checkbox">
				  <label><input id="chk_<?php //echo $i;?>" type="checkbox" value="1" onclick=opentxtar(<?php //echo $i;?>)>Reply to the Buyer</label>
				</div>-->
                
                
				<div id="reply_div_<?php echo $i;?>" style="display:none;">
				
              	 <form action="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/emaildelete/" method="post" id="reply_form" name="reply_form">
              	 <textarea name="reply" id="reply_<?php echo $i;?>" rows="3" cols="50"></textarea>
              	 <input type="hidden" name="buyer_email" value="<?php echo $output->from_email;?>"/>
              	 <input type="hidden" name="delete_type" id="delete_type_<?php echo $i;?>" value=""/>
              	 <input type="hidden" name="mail_id" value="<?php echo $output->id;?>"/>
              	 </form> 
              	</div>
              	 <ul>
              	 	<li><a href="#" onclick="getoption('<?php echo $i;?>');" data-toggle="modal" data-target=".bd-example-modal_user<?php echo $i;?>">Add as New Lead</a></li>
              	 	<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style=".dropdown-toggle::after{display:none !important;}">Delete This Lead</a>
              	 	<ul class="dropdown-menu">
                            <li><a class="bg_none" href="#" onclick="return confirmation('3','<?php echo $i;?>')">sPAM eMail</a></li>
                            <li><a class="bg_none" href="#" onclick="return confirmation('4','<?php echo $i;?>')">Marketing eMail</a></li>
                            <li><a class="bg_none" href="#" onclick="return confirmation('5','<?php echo $i;?>')">Irrelevant eMail</a></li>
                         </ul>
              	 	</li>
              	 	<li><a href="#" onclick="GetTagLeadList('<?php echo $output->from_email;?>','<?php echo $output->id;?>','<?php echo $i;?>')">Tag This Lead</a></li>
              	 </ul>
              	 <?php
              	 if($attachment_list)
              	 {				
              	 ?>
              	 <ul>
              	 <?php
              	 	foreach($attachment_list as $attachment_data)
					{
				?>
              	 	<li>
              	 	<a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_attachment/<?php echo $attachment_data->id;?>"><i class="material-icons" aria-hidden="true">
                      attach_file
                    </i> <?php echo $attachment_data->file_name;?></a>
                    </li>
              	 <?php
              	 	}
              	 ?>
              	 </ul>
              	 <?php
              	 }
              	 ?>
              </div>
            </div>