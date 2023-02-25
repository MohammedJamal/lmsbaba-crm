<?php
$existing_tagged_arr=[];
if(count($tagged_product))
{
  foreach($tagged_product AS $ps)
  {			
    array_push($existing_tagged_arr, $ps['product_id']);
  }
}
?>
<div class="card-block">   
  <div class="no-more-tables" id="search_prod_lead_list" >  
        
      <table id="" class="table table-striped m-b-0">
        <?php 
        if(count($product_list))
        {
          $i=0;
                
        ?>
        <thead>
          <tr>
            <td colspan="4">
              <div class="row pull-right">
                <row class="col-md-12">
                    <button type="button" class="btn btn-primary btn-round-shadow pull-left" onclick="return add_prod()">Add</button>
                </row>
              </div>
            </td>
          </tr>
          <tr>
            <th><label class="check-box-sec">
           <input type="checkbox" name="selectAllSearchedProductsQ" id="selectAllSearchedProductsQ">
           <span class="checkmark"></span>
           </label></th>
            <th>Name</th>
            <th class="text-center">Unit</th>
            <th class="text-center">Sale Price</th>
            <!-- <th>Vendor</th> -->
          </tr>
        </thead>
        <tbody>
              <?php 
              foreach($product_list as $output)
              {
              ?>
                <tr id="product_row_<?=$i;?>">
                  <td data-title="">
                    <label class="check-box-sec">
                        <input type="checkbox" name="select[]" id="select_<?php echo $output->id;?>" value="<?php echo $output->id;?>" onclick="sel_multiple('<?php echo $output->id;?>','<?php echo $i;?>')" data-name="<?php echo $output->name; ?><?php echo ($output->code)?' - '.$output->code:'';?>" <?php if(in_array($output->id,$existing_tagged_arr)){echo'checked';} ?> />
                        <span class="checkmark"></span>
                      </label>
                    
                  </td>
                  <td data-title="product Name"><?php echo $output->name; ?><?php echo ($output->code)?' - '.$output->code:'';?></td>
                  <td data-title="Unit" class="text-center"><?php echo $output->unit;?> <?php echo $output->unit_type_name;?></td>
                 	<td data-title="Price" class="text-center"><?php echo $output->currency_type_code.' '.$output->price;?></td>
                </tr>
              <?php
            $i++;
            }    	
        }
        else
        {
        ?>
        
        <tr><td colspan="4" align="center"><h6 class="no-found-text">No products found!</h6><?php if(is_permission_available('add_product_menu')){ ?><button  class=" btn btn-md btn-success" onclick="add_product_modal('<?php echo $search_keyword;?>')"><i class="fa fa-plus"></i><u>  Add '<?php echo $search_keyword?>'</u></button><?php } ?></td></tr>
        
        <?php
        }
        ?> 
        </tbody>
        <?php 
        if(count($product_list))
        {
        ?>
          <tfoot>
            <tr>
            <td colspan="4">
              <div class="row">
                <row class="col-md-12">
                    <button type="button" class="btn btn-primary btn-round-shadow pull-left" onclick="return add_prod()">Add</button>        
                </row>
              </div>
            </td>
          </tr>
          </tfoot>                      
        <?php
        }
        ?>
        
      </table>
      <?php 
      if($product_list)
      {
      ?>
        <!-- <div style="clear:both;">&nbsp;</div>
        <div class="row">
          <row class="col-md-12">
              <button type="button" class="btn btn-primary btn-round-shadow pull-left" onclick="return add_prod()">Add</button>        
          </row>
        </div>  --> 
      <?php
      }
      ?>
  </div>
  <div>&nbsp;</div>
  <form class="" name="create_new_opportunity" id="create_new_opportunity" method="post" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/add" style="display: none;">
          <button type="button" class="btn btn-primary btn-round-shadow pull-right" id="generate_automated_quotation_step2">Save & Proceed</button> 
  </form>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("body").on("click",".rander_vendor_html",function(e){
        var id=$(this).attr("data-id");
        $("#product_wise_denvor_"+id).show(300);
    });

    $("body").on("click",".close_vendor_html",function(e){
        var id=$(this).attr("data-id");
        $("#product_wise_denvor_"+id).hide(300);
    });
    
  });

  $("#selectAllSearchedProductsQ").click(function() {
    $("#prod_lead input[type=checkbox]").prop("checked", $(this).prop("checked"));
  });
</script>