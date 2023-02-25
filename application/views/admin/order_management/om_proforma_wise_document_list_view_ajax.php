<?php 
if(count($rows))
{ 
    $total_second=0;
    $i=0;?>
    <?php 
    foreach($rows AS $row)
    {           
        $i++;
    ?>
    <tr>
        <td><?php echo $i; ?>.</td>                                
        <td><span class="text-xs font-weight-bold"> <?php echo $row['name']; ?> </span></td> 
        <td class="align-middle" class="text-center">
            <span class="text-xs font-weight-bold">
                
                <a href="JavaScript:void(0)" class="document_view_popup" data-id="<?php echo $row['id']; ?>"><i class="fa fa-eye" style=""  aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                <?php if($user_id==$row['user_id']){ ?>
                <a href="JavaScript:void(0)" class="document_edit_popup" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" style=""  aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                <a href="JavaScript:void(0)" class="document_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" style="color:red !important" aria-hidden="true"></i></a>
                <?php } ?>
            </span>
        </td>                         
    </tr>
    <?php 
                                
    } ?>
    <?php }else{ ?>
        <tr>
            <td colspan="3" class="text-center"> No document found!</td>
        </tr>
    <?php } ?>