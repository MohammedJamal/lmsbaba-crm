<table class="table table-bordered">
    <thead>
      <tr>
        <th>Branch Name</th>
        <th>Contact Persion</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php if(count($branch_list)){ ?>
            <?php foreach($branch_list AS $row){ ?>
                <tr>
                    <td><b><?php echo ($row['name'])?$row['name']:$row['cs_name']; ?></b></td>
                    <td><?php echo ($row['contact_person'])?$row['contact_person']:$row['cs_contact_person']; ?></td>
                    <td><?php if($row['email']!='' || $row['cs_email']!=''){echo ($row['email'])?$row['email']:$row['cs_email'];}else{echo'-';} ?></td>
                    <td><?php if($row['mobile']!='' || $row['cs_mobile']!=''){echo ($row['mobile'])?$row['mobile']:$row['cs_mobile'];}else{echo'-';} ?></td>
                    <td>
                      <?php if($row['address']!='' || $row['cs_address']!=''){echo ($row['address'])?$row['address']:$row['cs_address'];} ?><br>
                      <?php echo ($row['country_name'])?$row['country_name']:''; ?>
                      <?php echo ($row['state_name'])?', '.$row['state_name']:''; ?>
                      <?php echo ($row['city_name'])?', '.$row['city_name']:''; ?><?php if($row['pin']!='' || $row['cs_pin']!=''){echo ($row['pin'])?'-'.$row['pin']:'-'.$row['cs_pin'];} ?>
                      <?php if($row['gst']!='' || $row['cs_gst']!=''){echo ($row['gst'])?'<br><b>GST:</b> '.$row['gst']:'<br><b>GST:</b> '.$row['cs_gst']; }?>
                      
                      
                    </td>
                    <td align="center">
                      <?php if($row['company_setting_id']){
                        echo'N/A';
                      }
                      else{ ?>                      
                      <a href="JavaScript:void(0);" title="Edit Branch" class="edit_branch_view" data-id="<?php echo $row['id'];?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;
                      <a href="JavaScript:void(0);" title="Delete Branch" class="delete_branch_view" data-id="<?php echo $row['id'];?>" ><i class="fa fa-trash red" aria-hidden="true"></i></a>
                      <?php } ?>
                    </td>
                </tr>
                <?php } ?>
          <?php }else{ ?>
            <tr><td colspan="6">No Branch Found!</td></tr>
          <?php } ?>
      
    </tbody>
  </table>
