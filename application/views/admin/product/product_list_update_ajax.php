
<div class="card-block">
  <?php 
        if($product_list && count($product_list)>5)
        {
        ?>
          <div style="clear:both;">&nbsp;</div>
          <div class="row">
            <row class="col-md-12">
                <button type="button" class="btn btn-primary btn-round-shadow pull-left" onclick="return add_prod_update('<?php echo $opportunity_id;?>')">Add</button>
            </row>
          </div>  
        <?php
        }
        ?>
      <?php ?>
  <div class="no-more-tables">
    <table id="datatable_prod" class="table datatable table-striped m-b-0">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Name</th>
          <th>Unit</th>
          <th>Sale Price</th>
          <th class="text-center">
            Vendor
            <!-- <div class="count-action-bar">
              <div class="col-md-6">Count</div>
              <div class="col-md-6">Action</div>
            </div> -->
          </th>
        </tr>
      </thead>
      <tbody>
      <?php 
      if($product_list)
      {
        $i=0;
        foreach($product_list as $output)
        {
        ?>
          <tr id="product_row_<?=$i;?>">
            <td data-title="Customer Name">
              <label class="check-box-sec">
                <input type="checkbox" name="select[]" id="select_<?php echo $output->id;?>" value="<?php echo $output->id;?>" onclick="sel_multiple_update('<?php echo $output->id;?>','<?php echo $i;?>','<?php echo $opportunity_id;?>')"/>
                <span class="checkmark"></span>
              </label>
              
            </td>
            <td data-title="Product Name"><?php echo $output->name.' - '.$output->code;?></td>
            <td data-title="Unit"><?php echo $output->unit_type_name;?></td>
            <td data-title="Price"><?php echo $output->currency_type_code.' '.$output->price;?></td>
            <td data-title="Vendor count">
              <a href="javascript:void(0)" class="rander_vendor_html" data-id="<?php echo $output->id;?>" title="Click to view all vendor"><?php echo $output->vendor_count; ?></a>   

                / <a href="javascript:void(0);" class="text-success" data-pid="<?php echo $output->id;?>" data-pname="<?php echo $output->name.' - '.$output->code;?>" title="Add new vendor to the product" onclick="select_vendor_product_lead(<?php echo $output->id;?>)"><i class="fa fa-plus" aria-hidden="true"></i></a>
              
            </td>           
          </tr>
          <tr id="product_wise_denvor_<?php echo $output->id;?>" style="display:none;">
            <td colspan="5">
              <div class="row">
                <div class="col-md-12 text-right text-danger" style="font-size: 25px;"><a href="JavaScript:void(0);" data-id="<?php echo $output->id;?>" class="close_vendor_html"><i class="fa fa-times-circle" aria-hidden="true"></i></a></div>
                <div class="col-md-12">
                  <?php if($output->vendor_count>0){ ?>
                    <table class="table table-striped table-bordered">
                      <thead>
                          <tr>                                           
                              <th>Vendor</th>
                              <th width="10%">Price</th>
                              <th width="20%">Currency</th>
                              <th width="10%">Unit</th>
                              <th width="10%">Unit Type</th>
                              <th>Created On</th>
                          </tr>
                      </thead>                                    
                      <tbody> 
                        <?php foreach($output->vendor_list as $vendor){ ?>   
                        <tr>                                  
                          <td>
                              <b><?php echo $vendor->vendor_company_name; ?></b>
                              <br><small><?php echo $vendor->vendor_contact_person; ?> <br><?php echo $vendor->vendor_mobile; ?> <br><?php echo $vendor->vendor_email; ?></small>
                          </td>
                          <td><?php echo $vendor->price; ?></td>
                          <td><?php echo $vendor->curr_name; ?></td>
                          <td><?php echo $vendor->unit; ?></td>
                          <td><?php echo $vendor->unit_type_name; ?></td>
                          <td><?php echo date_db_format_to_display_format($vendor->created_datetime); ?></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  <?php }else{echo'No vendor found!';} ?>
                </div>
              </div>
            </td>
          </tr>
        <?php
        $i++;
        }    	
      }
      else
      {
      ?>        
        <tr><td colspan="6" align="center"><h6 class="no-found-text">No products found!</h6><button  class=" btn btn-md btn-success" onclick="add_product_modal('<?php echo $search_keyword;?>')"><i class="fa fa-plus"></i><u> Add '<?php echo $search_keyword?>'</u></button></td></tr>   
      <?php
      }
    ?> 
    </tbody>
  </table>
  <?php 
  if($product_list)
  {
  	?>
    <div style="clear:both;">&nbsp;</div>
    <div class="row">
      <row class="col-md-12">
          <button type="button" class="btn btn-primary btn-round-shadow" onclick="return add_prod_update('<?php echo $opportunity_id;?>')">Add</button>            
      </row>
    </div>

  <?php
  }
  ?>
  </div>
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