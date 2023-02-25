<?php //print_r($row); ?>
<form name="frmProductEdit" id="frmProductEdit" action="" >
	<input type="hidden" name="p_editsection" id="p_editsection" value="<?php echo $editsection; ?>">
	<input type="hidden" name="p_id" id="p_id" value="<?php echo $row->product_id; ?>">
	<?php if($editsection=='description'){ ?>
	<div class="form-group">
	  <label>Product Name</label>
	  <input type="text" name="p_name" id="p_name" value="<?php echo $row->name; ?>">
	  <div id="p_name_error" class="text-danger"></div>
	</div>
	<div class="form-group">
	  <label>Short Description</label>
	  <input type="text" name="p_short_description" id="p_short_description" value="<?php echo $row->description; ?>" maxlength="100">
	  <div id="p_short_description_error" class="text-danger"></div>
	  <div class="rchars">max 100 words</div>
	</div>
	<div class="form-group">
	  <label>Full Description</label>
	  <textarea name="p_long_description" id="p_long_description" maxlength="200"><?php echo $row->long_description; ?></textarea>
	  <div id="p_long_description_error" class="text-danger"></div>
	  <div class="rchars">max 200 words</div>
	</div>
	<?php } ?>
	<?php if($editsection=='youtube'){ ?>
	<div class="form-group">
	  <label>Youtube Link</label>
	  <input type="text" name="p_youtube_video" id="p_youtube_video" value="<?php echo $row->youtube_video; ?>">
	  <div id="p_youtube_video_error" class="text-danger"></div>
	</div>	
	<?php } ?>
	<?php if($editsection=='brochures'){ ?>
	<div class="form-group row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        	<!-- <label>Add Product Brochure</label> -->
	        <div class="form_last">
	           <!-- <span class="btn btn-success btn-icon fileinput-button m-b-1">
	                <span>Add PDF Brochure</span>
	                <input id="fileupload_pdf" type="file" name="pdf_files[]">
	            </span> -->
	            <div>
		            <label class="phot_up pdf_style" for="fileupload_pdf">
	                   <span><i class="fa fa-plus" aria-hidden="true"></i>Add PDF Brochure</span>
	                   <input type="file" name="pdf_files[]" id="fileupload_pdf">
	                </label>
	            </div>
	            <div id="PreviewPdf" style="display: none;"></div>
	       </div>
        </div>
   </div>
   <?php } ?>
	<div class="form-group content_action">
	  <button type="button" class="btn btn-default" id="confirm_submit">Update</button>
	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</form>