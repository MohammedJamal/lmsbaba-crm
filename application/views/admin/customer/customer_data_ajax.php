<!--<div class="form-group">
    Name:<?=$cus_data->first_name;?> <?=$cus_data->last_name;?>
    Email:<?=$cus_data->email;?>
    Mobile:<?=$cus_data->mobile;?>
    Office Phone:<?=$cus_data->office_phone;?>
    Website:<?=$cus_data->website;?>
    Address:<?=$cus_data->address;?>
    ZIP:<?=$cus_data->zip;?>
    City:<?=$cus_data->city_name;?>
    State:<?=$cus_data->state_name;?>
    Country:<?=$cus_data->country_name;?>
    
</div>-->
<div class="row">
   <div class="box-details">
    <div class="col-sm-6">
        <ul class="buyer_email_subject buyer_email_subject_border">
            <li><b>Company ID:</b> <span class="display"><?php echo $cus_data->id;?></span></li>
            <?php if($cus_data->company_name){ ?>       
            <li><b>Company Name:</b> <span class="display"><?php echo ($cus_data->company_name)?$cus_data->company_name:'-';?></span></li>
            <?php } ?>
            <li><b>Contact Person:</b> <span class="display"><?php echo ($cus_data->contact_person)?$cus_data->contact_person:'-';?> </span></li>   
            <?php if($cus_data->designation){ ?>         
            <li><b>Designation:</b> <span class="display"><?php echo ($cus_data->designation)?$cus_data->designation:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->address){ ?>
            <li><b>Address :</b> <span class="display"><?php echo ($cus_data->address)?$cus_data->address:'-';?></span></li>
            <?php } ?>
            <li><b>Country :</b> <span class="display"><?php echo ($cus_data->country_name)?$cus_data->country_name:'-';?></span></li>
            <?php if($cus_data->state_name){ ?>
            <li><b>State :</b> <span class="display"><?php echo ($cus_data->state_name)?$cus_data->state_name:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->city_name){ ?>
            <li><b>City :</b> <span class="display"><?php echo ($cus_data->city_name)?$cus_data->city_name:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->zip>0){ ?>
            <li><b>Zip :</b> <span class="display"><?php echo ($cus_data->zip)?$cus_data->zip:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->gst_number){ ?>
            <li><b>GST Number:</b> <span class="display"><?php echo ($cus_data->gst_number)?$cus_data->gst_number:'-';?></span></li>
            <?php } ?>
        </ul>
    </div>
    <div class="col-sm-6">
        <ul class="buyer_email_subject buyer_email_subject_border">
            <?php if($cus_data->email){ ?>
            <li><b>Email:</b> <span class="display"><?php echo ($cus_data->email)?$cus_data->email:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->alt_email){ ?>
            <li><b>Alt. Email:</b> <span class="display"><?php echo ($cus_data->alt_email)?$cus_data->alt_email:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->mobile){ ?>
            <li><b>Mobile:</b> <span class="display"><?php echo ($cus_data->mobile_country_code)?'(+'.$cus_data->mobile_country_code.')':'';?><?php echo ($cus_data->mobile)?$cus_data->mobile:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->alt_mobile){ ?>
            <li><b>Alt. Mobile:</b> <span class="display"><?php echo ($cus_data->alt_mobile_country_code)?'(+'.$cus_data->alt_mobile_country_code.')':'';?><?php echo ($cus_data->alt_mobile)?$cus_data->alt_mobile:'-';?></span></li>
            <?php } ?>

            <?php if($cus_data->landline_number){ ?>
            <li><b>Phone:</b> <span class="display"><?php echo ($cus_data->landline_country_code)?'(+'.$cus_data->landline_country_code.')':'';?><?php echo ($cus_data->landline_std_code)?'('.$cus_data->landline_std_code.')':'';?><?php echo ($cus_data->landline_number)?$cus_data->landline_number:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->office_phone){ ?>
            <li><b>Alt. Phone:</b> <span class="display"><?php echo ($cus_data->office_country_code)?'(+'.$cus_data->office_country_code.')':'';?><?php echo ($cus_data->office_std_code)?'('.$cus_data->office_std_code.')':'';?><?php echo ($cus_data->office_phone)?$cus_data->office_phone:'-';?></span></li>
            <?php } ?>

            <?php if($cus_data->website){ ?>
            <li><b>Website:</b> <span class="display"><?php echo ($cus_data->website)?$cus_data->website:'-';?></span></li>   
            <?php } ?>      
            <?php if($cus_data->source_name){ ?>   
            <li><b>Source:</b> <span class="display"><?php echo ($cus_data->source_alias_name)?$cus_data->source_alias_name:$cus_data->source_name;?></span></li>
            <?php } ?>
            <?php if($cus_data->short_description){ ?>
            <li><b>Profile:</b> <span class="display"><?php echo ($cus_data->short_description)?$cus_data->short_description:'-';?></span></li>
            <?php } ?>
            <?php if($cus_data->reference_name){ ?>
            <li><b>Reference:</b> <span class="display"><?php echo ($cus_data->reference_name)?$cus_data->reference_name:'-';?></span></li>
            <?php } ?>       
        </ul>
    </div>
</div>
</div>