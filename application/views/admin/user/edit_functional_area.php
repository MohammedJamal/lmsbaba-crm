<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('include/header');?>
 <!-- content panel -->

<div class="main-panel">
  <div class="min_height_dashboard"></div> 
    <!-- main area -->
    <div class="main-content">
      <div class="content-view">
        <div class="layout-md b-b">
          <div class="layout-column-md">
            <div class="p-a-1 wizards">                 
              <!-- BEGIN STEP FORM WIZARD -->
              <div class="tsf-wizard tsf-wizard-1"> 
                <!-- BEGIN STEP CONTAINER -->
                <div class="tsf-container">
<form class="tsf-content" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/edit_functional_area/<?php echo $row->id?>" method="post" name="validate" id="validate">
  <input type="hidden" name="edit_id" value="<?php echo $row->id?>"/>
        <div id="error"></div>
  <div class="">
    <fieldset>
      <legend>Edit Functional Area</legend>
        <div class="row">
              <div class="tsf-step-content">
                <div class="col-lg-12">        
                  <div class="group_from">
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required value="<?php echo $row->name; ?>" />
                    </div>
                  </div>          
                </div>                  
              </div>
              <input type="hidden" name="command" value="1"/>
        </div>
    </fieldset>
</div>
</form>                         
</div>
<!-- BEGIN CONTROLS-->
<div class="tsf-controls ">  
    <button class="btn btn-right tsf-wizard-btn" onclick="form_submit()">UPDATE</button>
</div>
<!-- END CONTROLS-->
</div>
<!-- END STEP CONTAINER -->
</div>
<!-- END STEP FORM WIZARD -->
</div>
</div>
</div>
</div>
<!-- bottom footer -->
<?php $this->load->view('include/footer');?>
<!-- /bottom footer -->
</div>
<!-- /main area -->
</div>
<!-- /content panel --> 
      
<script type="text/javascript">
$(document).ready(function () {
    $('#validate').validate({ // initialize the plugin    
        rules: {
            name: {
                required: true,
            }            
        },
        // Specify validation error messages
    messages: {
      name: "Please enter name"      
    },     
    });
});
		
function form_submit()
{
	$('#validate').submit();
}	
</script>
     <script>
    /* OPTIONS
    $('.tsf-wizard-1').tsfWizard({
      stepEffect: 'slideLeftRight',
      stepStyle: 'style2',
      navPosition: 'top',
      stepTransition: true,
      validation: false,
      showButtons: true,
      showStepNum: true,
      height: '300px',
      prevBtn: 'My prev button',
      nextBtn: 'My next button',
      finishBtn: 'My finish button',
      onNextClick: function (e) {
          console.log('onNextClick');
      },
      onPrevClick: function (e) {
           console.log('onPrevClick');
      },
      onFinishClick: function (e) {
          console.log('onFinishClick' )
      }
    });*/
    var tsf_style = 'style1';
    var tsf_markup = '';
    $(function() {
      tsf_markup = $('.wizards')[0].innerHTML;
      pageLoadScript();
    });

    function mreload() {
      $('.wizards').html('');
      $('.wizards').append(tsf_markup).html()
      pageLoadScript();
    }

    function pageLoadScript() {
      var tsf_stepEffect = 'basic';
      var tsf_stepTransition = true;
      var tsf_showButtons = true;
      var tsf_showStepNum = true;
      $('.tsf-wizard-1').tsfWizard({
        stepEffect: tsf_stepEffect,
        validation: true,
        stepStyle: tsf_style,
        navPosition: 'top',
        stepTransition: tsf_stepTransition,
        showButtons: tsf_showButtons,
        showStepNum: tsf_showStepNum,
        prevBtn: '<i class="material-icons">arrow_back</i> Prev',
        nextBtn: 'Next <i class="material-icons">arrow_forward</i>',
        finishBtn: 'Finish'
      });
     
    
      
    }
    $('.wizard-styles, .wizard-effect, #stepTransition, #showButtons, #showStepNum').on('change', function(e) {
      if ($(this).hasClass('wizard-styles')) {
        tsf_style = this.value;
      }
      mreload();
    });
    </script> 
        </body>
</html>
