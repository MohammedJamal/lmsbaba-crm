<style type="text/css">
.mb-10px{
  margin-bottom: 10px !important;
}
#additional_charges_list_modal .modal-header {
    padding: 15px !important;
    border-bottom: none;
}
</style>
<div class="">
  <div class="no-more-tables">
    <?php 
    if($additional_charges_list)
    {
      ?>
     
      <div class="w-100">
        <div class="pull-right mb-10px">
            <button type="button" class="btn btn-primary btn-round-shadow" onclick="return add_additional_charges('<?php echo $opportunity_id;?>','<?php echo $quotation_id;?>')">Add</button>  
        </div>
      </div>

    <?php
    }
    ?>
    <table id="datatable_prod" class="table datatable table-striped m-b-0">
      
      <tbody>
      <?php 
      if($additional_charges_list)
      {
        $i=0;
        foreach($additional_charges_list as $output)
        {
        ?>
          <tr id="product_row_<?=$i;?>">
            <td data-title="Customer Name" width="20">
              <label class="check-box-sec">
                <input type="checkbox" name="q_additional_charges" id="q_additional_charges<?php echo $output->id;?>" value="<?php echo $output->id;?>"  />
                <span class="checkmark"></span>
              </label>
              
            </td>
            <td data-title="Product Name"><?php echo $output->name;?></td>
          </tr>
        <?php
        $i++;
        }    	
      }
      else
      {
      ?>
        <tr><td colspan="2" align="center">No products found</td></tr>                
      <?php
      }
    ?> 
    </tbody>
  </table>
  <?php 
  if($additional_charges_list)
  {
    ?>
    <div style="clear:both;">&nbsp;</div>
    <div class="w-100">
      <div class="pull-right">
          <button type="button" class="btn btn-primary btn-round-shadow" onclick="return add_additional_charges('<?php echo $opportunity_id;?>','<?php echo $quotation_id;?>')">Add</button>  
      </div>
    </div>
    <div style="clear:both;">&nbsp;</div>

  <?php
  }
  ?>
  <?php 
  /*
  if($additional_charges_list)
  {
  	?>
    <div style="clear:both;">&nbsp;</div>
    <div class="row">
      <row class="col-md-12">
          <button type="button" class="btn btn-danger" onclick="return add_additional_charges('<?php echo $opportunity_id;?>')">Add</button>  
      </row>
    </div>

  <?php
  }
  */
  ?>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    //var currency_type=$("#currency_type_update_<?php echo $opportunity_id; ?>").val();
    var currency_type=$("#currency_type_id").val();
    // alert(currency_type)
    if(currency_type!=1)
    {
      $("[name='gst[]']").attr("readonly",true);
    }
  });
</script>