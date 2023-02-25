<?php //print_r($rows); ?>
<div class="tholder max-scroller">
    <table class="table clock-table white-style">
      <thead class="thead-light">          
          <tr class="blue-header">
            <th scope="col">User </th> 
            <th scope="col">Date/Time  </th>
            <th scope="col">Duration(H:m:s)</th> 
            <th scope="col">Contact Person</th>
            <th scope="col">Company</th>
            <th scope="col">Mobile</th> 
            <th scope="col">Status</th>
            <th scope="col">Lead ID</th> 
            <th scope="col"></th>             
          </tr>
      </thead>
      <tbody>
          <?php if(count($rows)){ ?>
               <?php foreach($rows AS $row){ ?>
                    <?php
                    if($row->call_status=='Y')
                    {
                         // $call_status='<b class="text-success">Success</b>';
                         $call_status='<b class="text-success">'.$row->call_status_txt.'</b>';
                    }
                    else
                    {
                         // $call_status='<b class="text-danger">Fail</b>';
                         $call_status='<b class="text-danger">'.$row->call_status_txt.'</b>';
                    }

                    $date_a = new DateTime($row->exact_call_start);
                    $date_b = new DateTime($row->exact_call_end);
                    $interval = date_diff($date_a,$date_b);

                    $duration=$interval->format('%h:%i:%s');
                    ?>
                    <tr>
                         <td><?php echo $row->user_name; ?> </td>
                         <td><?php echo datetime_db_format_to_display_format($row->created_at); ?> </td>
                         <td><?php echo $duration; ?></td>
                         <td><?php echo $row->customer_contact_person; ?></td>
                         <td><?php echo ($row->company_name)?$row->company_name:'N/A'; ?></td>
                         <td><?php echo $row->client_mobile_number; ?></td>
                         <td><?php echo $call_status; ?></td>
                         <td><?php echo ($row->lead_id>0)?$row->lead_id:'N/A'; ?></td>
                         <td>
                              <?php 
                              if($row->uniquid){ ?>
                                   <div class="mediPlayer">
                                        <audio class="listen" preload="none" data-size="25" src="https://records.ariatelecom.net/Recording/Download?&UserID=AriaVoice&Password=atspl12345&Uniquerid=<?php echo $row->uniquid; ?>"></audio>
                                   </div>
                                   <div>
                                        <a href="https://records.ariatelecom.net/Recording/Download?&UserID=AriaVoice&Password=atspl12345&Uniquerid=<?php echo $row->uniquid; ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                                   </div>
                              <?php }else{echo'N/A';} ?>
                         </td>
                    </tr>
               <?php } ?>  
         <?php }else{ ?>
          <tr>
               <td colspan="9">No record found! </td>
          </tr>
         <?php } ?>       
      </tbody>
    </table>
  </div>  