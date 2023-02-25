<form id="md_form">
<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Add Document</legend>
        <div class="control-group">
            <div class="form-row">
                <div class="col-md-5">
                    <label for="md_title">Title:</label>
                    <input type="text" class="form-control " name="md_title" id="md_title" placeholder="" value="" maxlength="255" >
                </div>
                <div class="col-md-5">
                    <label for="md_file">File:</label>
                    <input type="file" class="form-control " name="md_file" id="md_file" placeholder="" value="" >
                </div>
                
                <div class="col-md-2">
                    <a href="javascript:void(0)" class="btn btn-success pull-right mt-25" id="my_document_add_submit">Add</a>
                </div>
            </div>
            
        </div>
    </fieldset>
    
</div>

<div class="col-md-12 my_document_outer_div" >
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
            <td align="center" width="20%">
                <a href="<?php echo base_url(); ?>clientportal/setting/downloadMyDocument/<?php echo base64_encode($row['title'].'#'.$row['file_name']); ?>" class="lead_stage_edit icon-btn btn-secondary text-white" ><i class="fa fa-download" aria-hidden="true"></i></a> &nbsp; 
                <a href="JavaScript:void(0);" class="my_document_delete icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
        </tr>        
    <?php } ?>    
    <?php }else{ ?>    
        <tr><td colspan="3">No Record Found!</td></tr>
    <?php } ?>
    </table>
</div>

</form>
<style type="text/css">
.my_document_outer_div > ul > li {
    
}
</style>
