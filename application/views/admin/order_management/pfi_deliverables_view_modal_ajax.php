<table class="table table-striped" width="100%">
    <tr>
        <th width="5%">SL No.</th>
        <th>Name</th>
        <th>Quantity</th>
    </tr>
    <?php 
    $i=1;
    foreach($prod_list AS $p){ ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $p->product_name; ?></td>
            <td><?php echo ($p->split_quantity)?$p->split_quantity:$p->quantity; ?></td>
        </tr>
    <?php $i++;} ?>
</table>