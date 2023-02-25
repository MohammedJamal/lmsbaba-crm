<div class="col-md-812" >
    <?php if(count($rows)){ ?>
    <table class="table table-bordered">
        <tr>
            <th>Title</th>
            <th class="text-center" width="15%">File Type</th>
            <th class="text-center" width="10%">Action</th>
        </tr>
    <?php foreach($rows AS $row){ ?>
        <tr>
            <td><?php echo $row['title']; ?></td>
            <td align="center">
                <?php
                $ext_arr=explode(".",$row['file_name']);
                echo end($ext_arr);
                ?>
            </td>
            <td align="center">
                <a href="<?php echo base_url(); ?>clientportal/setting/downloadMyDocument/<?php echo base64_encode($row['title'].'#'.$row['file_name']); ?>" class="lead_stage_edit" ><i class="fa fa-download" aria-hidden="true" style="color: #10b6ff;"></i></a>
            </td>
        </tr>        
    <?php } ?>    
    <?php }else{ ?>    
        <tr><td colspan="3">No Record Found!</td></tr>
    <?php } ?>
    </table>
</div>
