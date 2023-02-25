<div class="funel-wapper">
   <?php if(count($data)){ ?>
      <?php foreach($data as $row){ ?>
      <div class="funel-item">
         <div>
            <span><?php echo $row['name'];?></span>
            <a href="JavaScript:void(0)" class="rander_detail_popup" data-report="lead_pipeline_report" data-filter1="<?php echo $row['id'];?>" data-filter2=""><?php echo $row['total_lead'];?></a>
         </div>
      </div>
      <?php } ?>
   <?php } ?>
</div>

