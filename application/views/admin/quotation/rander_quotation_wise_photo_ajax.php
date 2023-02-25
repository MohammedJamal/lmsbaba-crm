<?php if(count($rows)){ ?>
   <?php foreach($rows AS $row){ ?>
   <div class="row mb-15" id="q_photo_<?php echo $row->id; ?>">
      <div class="col-md-2">
       <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Photos:</b></h4>
       <span class="d-block" id="images-over-show-${oi}" data-content="${event.target.result}"><img src="<?php echo assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$row->file_name; ?>" class="img-fluid"></span>
      </div>
      <div class="col-md-6">
       <h4 style="margin-bottom: 5px; font-size: 13px;"><b style="line-height: 37px;">Title:</b></h4>
       <input type="text" class="default-input q_photo_title_update" data-field="title" name="" value="<?php echo $row->title; ?>" data-id="<?php echo $row->id; ?>">
      </div>
      <div class="col-md-2">
       <a href="JavaScript:void(0)" class="del_photo_product" data-id="<?php echo $row->id; ?>"><img style="cursor: pointer;" src="<?php echo assets_url(); ?>images/trash.png" alt=""></a>
      </div>
   </div>
   <?php } ?>
<?php } ?>