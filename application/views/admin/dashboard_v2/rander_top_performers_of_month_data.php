<div id="performers-carousel" class="owl-carousel owl-theme">
    <?php if(count($rows)){ ?>
        <?php foreach($rows AS $row){ ?>
            <?php
            if($row['photo']!='')
            {
            $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$row['photo'];
            }
            else
            {
            $profile_img_path = assets_url().'images/user_img_icon.png';
            }
            ?>
            <div class="item">
                <div class="performers-item">
                    <div class="user-details-block">
                        <div class="user-details-pic"><img src="<?php echo $profile_img_path; ?>"></div>
                        <div class="user-details-infos">
                            <h2><?php echo $row['name']; ?></h2>
                            <?php echo $row['designation']; ?>
                        </div>
                    </div>
                    <div class="performers-details-block">
                        <div class="performers-details-row">
                            <div class="performers-details-col">
                                Revenue
                            </div>
                            <div class="performers-details-col">
                                Sales
                            </div>
                        </div>
                        <div class="performers-details-row">
                            <div class="performers-details-col">
                                <?php /* ?><a href="JavaScript:void(0)" class="blue-link #rander_detail_popup#" data-report="top_performer_of_month" data-filter1="<?php echo $filter_selected_year_month; ?>" data-filter2="<?php echo $row['id']; ?>"><?php echo $currency_info['default_currency_code']; ?>: <?php echo number_format($row['total_deal_value_as_per_purchase_order']); ?></a><?php */ ?>
                                <span style="color:#0275d8"><?php echo $currency_info['default_currency_code']; ?>: <?php echo number_format($row['total_deal_value_as_per_purchase_order']); ?></span>
                            </div>
                            <div class="performers-details-col">
                                <?php /* ?><a href="JavaScript:void(0)" class="blue-link #rander_detail_popup#" data-report="top_performer_of_month" data-filter1="<?php echo $filter_selected_year_month; ?>" data-filter2="<?php echo $row['id']; ?>"><?php echo $row['total_sales_count']; ?></a><?php */ ?>
                                <span style="color:#0275d8"><?php echo $row['total_sales_count']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>