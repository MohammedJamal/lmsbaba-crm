<div class="card-block">
  <div class="no-more-tables">
    <?php 
    if($product_list && $method_name=='edit')
    {
    ?>
    <button type="button" class="btn btn-danger pull-right" onclick="return add_prod()">Add</button>  
    <?php
    }
    ?>
    <table id="datatable_prod" class="table datatable table-striped m-b-0">
      <thead>
        <tr>
          <?php if($method_name=='edit'){ ?><th>&nbsp;</th><?php } ?>
           <th>Name</th>                        
          <th>Product Code</th>
          <th>Unit</th>
          <th>Price</th> 
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
          <?php if($method_name=='edit'){ ?>
          <td data-title="Customer Name">
            <input type="checkbox" name="product" id="select_<?php echo $output->id;?>" value="<?php echo $output->id;?>"/>
          </td>
          </th><?php } ?>
          <td data-title="Customer Name">
            <a href="javascript:" onclick="GetSKUList(<?=$output->id;?>)"><?php echo $output->name;?></a>
          </td>                        
          
          <td data-title="Product Code">
            <?php echo $output->code;?>
          </td>
          <td data-title="Unit">
            <?php echo $output->unit.' '.$output->unit_type_name;?>
          </td>
         	<td data-title="Price">
             <?php echo $output->currency_type_code.' '.$output->price;?>
          </td>          
        </tr>
        <?php
    $i++;
    }
    	
}
else
{
?>
<tr>
<td colspan="5" align="center">No product tagged..</td>
</tr>
<?php
}
?>       	
</tbody>
</table>
<?php 
if($product_list && $method_name=='edit')
{
?>
<br>
<button type="button" class="btn btn-danger pull-right" onclick="return add_prod()" id="tagged_product_submit_confirm">Add</button>  
<?php
}
?>
</div>
</div>