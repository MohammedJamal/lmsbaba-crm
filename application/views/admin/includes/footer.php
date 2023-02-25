<nav class="footer-right">
    <ul class="nav">
        <li>
          <table cellpadding="0" cellspacing="0" border="0" width="90%"
            style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border:none; border-bottom: none; border-left: none; border-right: none;">
                <tr>
                     <td style="width: 100%; padding: 15px 0 5px;text-align: right;" align="right" valign="top">
                        <div style="float: right;text-align: center;">                            
                            <img src="<?php echo assets_url().'images/logo.png'; ?>" border="0" style="display: block; width: 80px; text-align: right;" />      
                      </div>                         
                    </td>
                </tr>
        </table>
        </li>
    </ul>
</nav>
<nav class="footer-left">
    <ul class="nav">
        <li><a href="https://www.lmsbaba.com" target="_blank">
          Copyright © <?php echo date('Y'); ?>, LMSBaba.com. All rights reserved.</a></li>
    </ul>
</nav>

<div class="ba-we-love-subscribers-wrap">	
    <div class="ba-we-love-subscribers-fab" id="common_mail_send" style="curser:pointer;">
        <div class="wrap">
            <div class="img-fab img"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
		$("body").on("click","#common_mail_send",function(){
			var position='bottom_right';
			common_mail_send_modal(position);
		});
		
		/*
        $(".ba-we-love-subscribers-fab").click(function() {
        $('.ba-we-love-subscribers-fab .wrap').toggleClass("ani");
        $('.ba-we-love-subscribers').toggleClass("open");
        $('.img-fab.img').toggleClass("close");
		});*/
    });
</script>