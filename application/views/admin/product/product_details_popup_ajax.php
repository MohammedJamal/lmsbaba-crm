<?php //print_r($row); ?>

<div class="box-details-new">
    <div class="form-group row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Product Name</label><span>:</span>
            <div class="pd"><?php echo ($row->name)?$row->name:'-';?></div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Product ID</label><span>:</span>
            <div class="pd"><?php echo $row->id;?></div>
        </div>
    </div>
	<div class="form-group row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Product Code</label><span>:</span>
            <div class="pd"><?php echo ($row->code)?$row->code:'-';?></div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>HSN Code</label><span>:</span>
            <div class="pd"><?php echo ($row->hsn_code)?$row->hsn_code:'-';?></div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Sales Price</label><span>:</span>
            <div class="pd"><?php echo ($row->price)?$row->curr_code.' '.$row->price:'-';?></div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>GST%</label><span>:</span>
            <div class="pd"><?php echo ($row->gst_percentage)?$row->gst_percentage.'%':'-';?></div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Unit</label><span>:</span>
            <div class="pd"><?php echo ($row->unit)?$row->unit:'-';?></div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Unit Type</label><span>:</span>
            <div class="pd"><?php echo ($row->unit_type_name)?$row->unit_type_name:'-';?></div>
        </div>
        
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Group</label><span>:</span>
            <div class="pd"><?php echo ($row->group_name)?$row->group_name:'N/A';?></div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Category</label><span>:</span>
            <div class="pd"><?php echo ($row->cate_name)?$row->cate_name:'N/A';?></div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Min. Order Quantity</label><span>:</span>
            <div class="pd"><?php echo ($row->minimum_order_quantity)?$row->minimum_order_quantity:'--';?></div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Available For</label><span>:</span>
            <div class="pd">
			<?php
			if($row->product_available_for=='E')
			{
				echo'Export Only';
			}
			else if($row->product_available_for=='D')
			{
				echo'Domestic Only';
			}
			else if($row->product_available_for=='A')
			{
				echo'Export & Domestic';
			}
			else
			{
				echo'N/A';
			}
			?>
			</div>
        </div>
    </div>
	<?php if($row->images){ ?>
	
    <div class="form-group row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 product_preview_photo">
            <label>Product Photo</label><span>:</span>
			<?php
			$image_arr=explode(",",$row->images);
			?>
            <ul class="product_preview_pic">
				<?php foreach($image_arr as $image){ ?>				
                <li>
				<img src="<?php echo assets_url(); ?>uploads/clients/<?php echo $this->session->userdata['admin_session_data']['client_id']; ?>/product/thumb/<?php echo $image; ?>">
				</li>
				<?php } ?>                
            </ul>
        </div>
    </div>
	<?php } ?>
    <div class="form-group row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Product Video</label><span>:</span>
            <div class="pd">
				<?php if($row->youtube_video){ ?>
                <a href="<?php echo $row->youtube_video; ?>" target="_blank"><?php echo $row->youtube_video; ?></a>
				<?php }else{ ?>
				N/A
				<?php } ?>
            </div>
        </div>
    
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <label>Product Brochure</label><span>:</span>
            <div class="pd">
				<?php if($row->brochures){ ?>
				<a href="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/download_brochure/<?php echo base64_encode($row->brochures); ?>" class="download_pdf"> <i class="fa fa-download" aria-hidden="true"></i>
                </a>
				<?php }else{ ?>
				N/A
				<?php } ?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <label>Short Description</label><span>:</span>
            <div class="pd"><?php echo (trim(strip_tags($row->description)))?$row->description:'--'; ?></div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <label>Long Description</label><span>:</span>
            <div class="pd"><?php echo (trim(strip_tags(str_replace("&nbsp;","",$row->long_description))))?$row->long_description:'--'; ?></div>
        </div>
    </div>
</div>
