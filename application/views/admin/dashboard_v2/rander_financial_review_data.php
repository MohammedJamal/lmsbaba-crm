 
    <div class="w-chart-outer">
        <div id="one-chart-container"></div>
    </div>
    <div class="w-chart-txt">
        <span class="<?php echo ($data['since_last_month']<0)?'text-danger':''; ?>"><?php echo round($data['since_last_month']); ?>%</span><br>
        growth since last  <br>month
    </div>
                           
