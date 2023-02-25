<div class="card-block">  
  <div class="no-more-tables" id="po_search_prod_lead_list">      
      <table id="" class="table table-striped m-b-0">
        <?php 
        if($product_list)
        {
          $i=0;
        ?>
        <thead>

          <!-- <tr>
            <td colspan="4">
              <div class="row">
                <row class="col-md-12">
                    <button type="button" class="btn btn-primary btn-round-shadow pull-left" onclick="return add_prod()">Add</button>        
                </row>
              </div>
            </td>
          </tr>
          <tr> -->
            <th>&nbsp;</th>
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
                        <input type="checkbox" name="select[]" id="select_<?php echo $output->id;?>" value="<?php echo $output->id;?>"  data-name="<?php echo $output->name; ?><?php echo ($output->code)?' - '.$output->code:'';?>" />
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
        <tr><td colspan="4" align="center"><h6 class="no-found-text">No products found!</h6><!-- <button  class=" btn btn-md btn-success" onclick="add_product_modal('<?php echo $search_keyword;?>')"><i class="fa fa-plus"></i><u>  Add '<?php echo $search_keyword?>'</u></button> --></td></tr>
        <?php
        }
        ?> 
        </tbody>
        <?php 
        if($product_list)
        {
        ?>
          <tfoot>
            <tr>
            <td colspan="4">
              <div class="row">
                <row class="col-md-12">
                    <button type="button" class="btn btn-primary btn-round-shadow pull-left" onclick="return add_po_prod()">Add</button>        
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
  <?php if($product_list){ ?>
  <!-- <form class="" name="create_new_opportunity" id="create_new_opportunity" method="post" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/opportunity/add" style="display: none;"> -->
    <button type="button" class="btn btn-primary btn-round-shadow pull-right hide" id="po_porduct_update_confirm">Save & Proceed</button> 
  <!-- </form> -->
  <?php } ?>
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
</script>