<table id="datatable_list" class="table table-bordered" style="width:100%;">
    <thead>
      <tr class="info">
      <th class="text-left sort_order asc" data-field="sln" data-orderby="desc">Demo Id</th>
        <th class="text-left sort_order asc" data-field="sln" data-orderby="desc">Lead Id</th>
        <th class="text-left sort_order" data-field="companyname" data-orderby="desc">Company Name</th>
        <!--<th class="text-left sort_order" data-field="module_service" data-orderby="desc">Module & Service</th>-->
        <th class="text-center sort_order" data-field="taggeduser" data-orderby="desc">Contact Person</th>
        <th class="text-center sort_order" data-field="notloggedin" data-orderby="desc">Email ID</th>
        <th class="text-center sort_order" data-field="lasttouch" data-orderby="desc">Mobile</th>
        <th class="text-center">Demo Date</th>
        <th class="text-center">Demo Time</th>
        <th class="text-center">Lead Generation Platforms</th>
        <th>Sales Person</th>
        <th class="text-center">Location</th>
        <th class="text-center">Demo By</th>
        <th class="text-center">Status</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>


<?php if(count($rows)){

          $activity_subname='';
          $activity_subname_tc='';

          $today=date("Y-m-d");
          $i=$sl_start; ?>
            <?php foreach($rows AS $row){
              
              ?>
            <tr class="<?php echo ($row['is_account_active']=='N')?'table-danger':''; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['lead_id']; ?></td>
                <td><?php echo $row['company_name']; ?></td>
                <td><?php echo $row['contact_person']; ?></td>
                <td><?php echo $row['email_id']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td> <?php echo $row['demo_date']; ?> </td>
                <td> <?php echo $row['demo_time']; ?> </td>
                <td> <?php echo $row['LEADGENERATIONPLATFORM']; ?> </td>
                <td> <?php echo $row['SALESPERSONNAME']; ?> </td>
                <td> <?php echo $row['CITIESNAME']; ?> </td>
                <td> <?php //echo 'DEMO BY'; ?> </td>
                <td> 
                  <?php 
                  if($row['status']=='S')
                  {
                      echo "Scheduled";
                  }elseif($row['status']=='D')
                  {
                    echo "Done";
                  }elseif($row['status']=='R')
                  {
                    echo "Reschedule";
                  }elseif($row['status']=='C')
                  {
                    echo "Cancelled";
                  }elseif($row['status']=='CM')
                  {
                    echo "Confirm";
                  }
                   ?> 
                </td>
                <td> 
                <a href="JavaScript:void(0)" title="Demo Done" class="comment_update_row text-info" data-id="<?php echo $row['id']; ?>" data-lid="<?php echo $row['lead_id']; ?>" ><i class="fa fa-thumbs-o-up"  aria-hidden="true"></i></a> 
                |
                <a href="JavaScript:void(0)" title="Demo Confirm" class="comment_confirm_row text-info" data-id="<?php echo $row['id']; ?>" data-lid="<?php echo $row['lead_id']; ?>" ><i class="fa fa-check"  aria-hidden="true"></i></a> 
                 
                |
                <!--<a href="JavaScript:void(0)" title="Demo Reschedule" class="comment_reschedule_row text-info" data-id="<?php echo $row['id']; ?>" data-lid="<?php echo $row['lead_id']; ?>" ><i class="fa fa-plus-circle"  aria-hidden="true"></i></a> 
                 <a href="JavaScript:void(0)" title="View Services List" class="view_services_list text-primary" data-cid="<?php echo $row['id']; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a> 
                | 
                
                <a href="JavaScript:void(0)" title="View Log" class="view_comment_list" data-cid="<?php echo $row['id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></a>   -->
                </td>
            </tr>
            <?php $i++; } ?>
            <?php }else{ ?>
            <tr><td colspan="12">No Demo List Found!</td></tr>
        <?php } ?>

        </tbody>
  </table>