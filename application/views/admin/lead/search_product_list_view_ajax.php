   <?php if(count($product_list)){ ?>
   <div class="form-group">
     <button type="button" class="btn btn-primary btn-round-shadow pull-right add-search search_product_checked" id="search_product_checked_1" style="display:none;">Add</button>
   </div>
   <?php } ?>
   <table id="" class="table table-striped m-b-0 table-border">
     <thead>        
         <tr>
           <th>
              <label class="check-box-sec">
              <input type="checkbox" name="selectAllSearchedProducts" id="selectAllSearchedProducts">
              <span class="checkmark"></span>
              </label>
           </th>
           <th>Name</th>
           <th class="text-center">Unit</th>
           <th class="text-center">Sale Price</th>
         </tr>
     </thead>
     <tbody id="search_product_list_tr">
   <?php if(count($product_list)){ ?>
   <?php foreach($product_list AS $product){ ?>
      <tr id="p_tr_<?php echo $product['id']; ?>" >
        <td data-title="">
          <label class="check-box-sec">
          <input type="checkbox" name="select_product[]" id="select_product_<?php echo $product['id']; ?>" value="<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>">
          <span class="checkmark"></span>
          </label>
        </td>
        <td data-title="product Name"><?php echo $product['name']; ?></td>
        <td data-title="Unit" class="text-center"><?php echo $product['unit']; ?> <?php echo $product['unit_type_name']; ?></td>
        <td data-title="Price" class="text-center"><?php echo $product['curr_code']; ?> <?php echo $product['price']; ?></td>
      </tr>
   <?php } ?>      
   <?php }else{ ?>
   <tr id="">
      <td align="center" colspan="4">No Record Found!</td>
   </tr>

   <?php } ?>
   </tbody>
   <?php if(count($product_list)){ ?>
   <tfoot>

    <tr>
      <td colspan="4">
            <div class="row">
              <row class="col-md-12">
                <button type="button" class="btn btn-primary btn-round-shadow pull-left add-search search_product_checked" id="search_product_checked_2" style="display:none;">Add</button>        
              </row>
            </div>
      </td>
    </tr>
   </tfoot>
   <?php } ?>
   </table>
   <script type="text/javascript">
      $("#selectAllSearchedProducts").click(function() {
          $("#rander_search_product_view_modal input[type=checkbox]").prop("checked", $(this).prop("checked"));
      });
   </script>