<h5>Existing Form</h5>
<table class="table table-bordered">
    <thead>
        <tr>
        <th scope="col">Page Name</th>
        <th scope="col">Form Name</th>
        <th scope="col">Form ID</th>
        <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($rows)){ ?>
            <?php foreach($rows AS $row){ ?>
            <tr>
                <th scope="row"><?php echo $row['page_name']; ?></th>
                <td><?php echo $row['form_name']; ?></td>
                <td><?php echo $row['fb_form_id']; ?></td>

                <td class="text-center">
                    <?php
                    $curr_status=($row['is_default']=='Y')?'<i class="fa fa-unlock text-success" aria-hidden="true"></i>':'<i class="fa fa-lock text-danger" aria-hidden="true" style="color:red !important"></i>'; 
                    if($row['is_default']=='Y')
                    {
                        $status_tooltip_text="Click to In-active (Currently capturing form data)";
                    }
                    else
                    {
                        $status_tooltip_text="Click to active (Currently Not capturing form data) ";
                    }
                    echo $status_link='<a title="'.$status_tooltip_text.'" href="JavaScript:void(0);" class="icon-btn fb_default_change" data-curr_status="'.$row['is_default'].'" data-id="'.$row['id'].'" id="status_'.$row['id'].'" >'.$curr_status.'</a>';
                    
                    ?>
                    <a href="JavaScript:void(0);" class="icon-btn fb_edit" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true" style=""></i></a>
                    <a href="JavaScript:void(0);" class="icon-btn fb_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></a>
                </td>
            </tr>  
            <?php } ?>  
        <?php }else{ ?>
            <tr><td colspan="4">No Form Found!</td></tr>
        <?php } ?>
            
    </tbody>
</table>
<script type="text/javascript">
$( function() {
        $( document ).tooltip();
}); 
</script>