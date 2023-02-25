
<div class="row">
    <div class="box-details">
        <div class="col-sm-6">
            <ul class="buyer_email_subject buyer_email_subject_border">
                <li><b>Company Name:</b> <span class="display"><?php echo ($vds_data->company_name)?$vds_data->company_name:'-';?></span></li> 
                <li><b>Address :</b> <span class="display"><?php echo ($vds_data->address)?$vds_data->address:'-';?></span></li>          
                <li><b>Country :</b> <span class="display"><?php echo ($vds_data->country_name)?$vds_data->country_name:'-';?></span></li>
                <li><b>State :</b> <span class="display"><?php echo ($vds_data->state_name)?$vds_data->state_name:'-';?></span></li>
                <li><b>City :</b> <span class="display"><?php echo ($vds_data->city_name)?$vds_data->city_name:'-';?></span></li>
                <li><b>Zip :</b> <span class="display"><?php echo ($vds_data->zip)?$vds_data->zip:'-';?></span></li>
                <li><b>Website:</b> <span class="display"><?php echo ($vds_data->website)?$vds_data->website:'-';?></span></li> 
            </ul>
        </div>
        <div class="col-sm-6">

            <ul class="buyer_email_subject buyer_email_subject_border">
                <li><b>Contact Person:</b> <span class="display"><?php echo ($vds_data->contact_person)?$vds_data->contact_person:'-';?> </span></li> 
                <li><b>Designation:</b> <span class="display"><?php echo ($vds_data->designation)?$vds_data->designation:'-';?></span></li>
                <li><b>Email:</b> <span class="display"><?php echo ($vds_data->email)?$vds_data->email:'-';?></span></li>
                 <li><b>Mobile:</b> <span class="display"><?php echo ($vds_data->mobile)?$vds_data->mobile:'-';?></span></li>
                 <li><b>Office Phone:</b> <span class="display"><?php echo ($vds_data->office_phone)?$vds_data->office_phone:'-';?></span></li>
                 <li><b>Status:</b> <span class="display"><?php echo status_text($vds_data->status)?></span></li>            
            </ul>
        </div>
    </div>
</div>
<?php

    if($vds_data->visiting_card_font!=''){
      $vc_font = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/".$vds_data->visiting_card_font;
    }else{
      $vc_font = assets_url().'images/no-image.png';
    }
    if($vds_data->visiting_card_back!=''){
      $vc_back = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/".$vds_data->visiting_card_back;
    }else{
      $vc_back = assets_url().'images/no-image.png';
    }
?>
<div class="row" style=" padding: 0 12px 10px;">
    <div class="col-sm-3">
        <div class="thired_tab">
            <img src="<?php echo $vc_font;?>"/>
            <div>
                <span class="file">   
                    <label for="file">Visiting Card - Front</label>
                </span>
            </div>
        </div>                                         
    </div>
    <div class="col-sm-3">   
        <div class="thired_tab">
            <img src="<?php echo $vc_back;?>"/>
            <div>
                <span class="file">
                    <label for="file">Visiting Card - Back</label>
                </span>
            </div>
        </div>
    </div>                                         
</div>

<style type="text/css">
  .thired_tab {
    width: 100%;
    height: 156px;
    float: left;
    padding: 4px;
    border:1px dashed #d9d9d9;
    background: #f5f5f6;
    position: relative;
}

.thired_tab img {
    width: 100%;
    height:147px;
    object-fit: cover;
}

.thired_tab .file {
    position: absolute;
    bottom: 0;
    width: 100%;
    float: left;
    text-align: center;
}

.thired_tab .file input {
    position: absolute;
    display: inline-block;
    left: 0;
    top: 0;
    opacity: 0.01;
    cursor: pointer;
}

.thired_tab .file label {
    background: #c1c2c4;
    padding: 2px 13px;
    color: #000;
    font-weight: bold;
    font-size: .9em;
    transition: all .4s;
}
</style>