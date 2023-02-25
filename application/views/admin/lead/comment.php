<?php $this->load->view('include/header');?>
<div class="main-panel">
<div class="min_height_dashboard"></div>
<div class="main-content">
<div class="content-view">
<div class="card-deck-wrapper">
<div class="card-deck">
<div class="row">
<div class="card col-lg-12">         
<div class="card-block">
  <div class="comment_text">
  	<div class="post-comments">
    <h4 class="m-b-0">History Log of <b>"<?php echo $lead_data->title; ?>"</b>. (Company Name: <?php echo $company_name; ?>)</h4><hr/>
    <?php
    if($comment_list)
    {		 
      foreach($comment_list as $comment_data)
      {
			?>        
        <div class="media">
          <div class="comment">
            <div class="comment-author heading-font">
              <a href="javascript:;">
                <i class="fa fa-product-hunt text-success" aria-hidden="true"></i> <?php echo $comment_data->cus_first_name.' '.$comment_data->cus_last_name;?>
                <time datetime="2015-09-01" class="time">
                  <i class="fa fa-calendar text-danger" aria-hidden="true"></i> <?php
            echo date("d/m/Y", strtotime($comment_data->create_date));?>
                </time>
                <time datetime="2015-09-01" class="time">
                  <i class="fa fa-clock-o text-danger" aria-hidden="true"></i> <?php
            echo date("h:m:i A", strtotime($comment_data->create_date));?>
                </time>
                
              </a>
            </div>
            <p><?php echo $comment_data->comment;?></p>
            <hr/>
          </div>
        </div>                      
          <?php
          }
        }
        else
        {
		  	?>
		  	<div>No Comments Found</div>
		    <?php } ?>                      
</div>
</div>
</div>
<div><a href="javascript:window.close();"><button class="btn btn-success">Back</button></a></div>
</div>
</div>        
</div>
</div>
</div>
<!-- bottom footer -->
<?php $this->load->view('include/footer');?>
<!-- /bottom footer -->
</div>
</div>
</div>

    <script type="text/javascript">
      window.paceOptions = {
        document: true,
        eventLag: true,
        restartOnPushState: true,
        restartOnRequestAfter: true,
        ajax: {
          trackMethods: [ 'POST','GET']
        }
      };
    </script>

   <?php //$this->load->view('include/footer');?>
    <!-- endbuild -->

    <!-- page scripts -->
    <!-- end page scripts -->

    <!-- initialize page scripts -->
    <script type="text/javascript">
      $('.timeline-toggle .btn').on('click', function (e) {
        var val = $(this).find('input').val();
        if (val === 'stacked') {
          $('.timeline').addClass('stacked');
        }
        else {
          $('.timeline').removeClass('stacked');
        }
      });
    </script>
    <!-- end initialize page scripts -->
    

