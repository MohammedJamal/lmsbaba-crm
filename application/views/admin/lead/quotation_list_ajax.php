              <div class="card-block">
                <div class="no-more-tables">
                  <table id="datatable_prod" class="table datatable table-striped m-b-0">
                    <thead>
                      <tr>
                        <th>
                          Proposal Code
                        </th>
                         <th>
                          Upload Date
                        </th>
                        <th>
                          Edit
                        </th>       
                        <th>
                          View
                        </th>
                        <th>
                          Download
                        </th>
                                         

                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if($quotation_list)
                    {
					
                    $i=0;
                    foreach($quotation_list as $output)
                    {

                    	?>
                      <tr id="product_row_<?=$i;?>">
                        
                        <td data-title="Customer Name">
                          <?php echo $output->quote_no;?>
                        </td>                        
                         <td data-title="Customer Name">
                          <?=date("d M Y", strtotime($output->create_date));?>
                        </td>                        
                        
                        <td data-title="Product Code">
                        <?php
                        if($output->is_external=='0')
                        {
						
                        ?>
                          <a target="_blank" href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/generate_view/'.$output->opportunity_id.'/'.$output->id);?>">Edit</a>
                         <?php
                         }
                         else
                         {
						 	echo '&nbsp;';
						 }
						 "accounts/".$this->session->userdata['admin_session_data']['lms_url']."/quotation/".$output->quote_no.".pdf";
                         ?>
                        </td>
                        <td data-title="Product Code">
                        <?php
                        if($output->is_external=='0')
                        {
						
                        ?>
                          <a target="_blank" href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/preview_quotation/'.$output->opportunity_id.'/'.$output->id);?>">Preview</a>
                          <?php
                         }
                         else
                         {
                         	 $link=assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$output->quote_no.".pdf";
						 	?>
						 	<a target="_blank" href="<?php echo $link;?>">Preview</a>
						 	<?php
						 	
						 }
						 
                         ?>
                        </td>
                        <td data-title="Unit">
                          <a target="_blank" href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/opportunity/save_download/'.$output->opportunity_id.'/'.$output->id);?>">Downlod</a>
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
                        <td colspan="6" align="center">
                        No products found
                        </td>
                        </tr>
                        
                        <?php
					}
                  ?> 
                   
                   	
                    </tbody>
                  </table>
                  <?php 
                    if($product_list)
                    {
                    	?>
                  <button type="button" class="btn btn-danger" onclick="return add_prod()">Add</button>  
                  <?php
                  }
                  ?>
                </div>
              </div>