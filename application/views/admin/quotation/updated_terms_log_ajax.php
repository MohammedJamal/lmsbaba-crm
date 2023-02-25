<?php 
if(count($terms))
{                            
  foreach($terms as $term)
  {
    ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title ff">
          
          <label class="check-box-sec">
            <input type="checkbox" id="is_terms_show_in_letter_<?php echo $term['id']; ?>" class="is_terms_show_in_letter" data-id="<?php echo $term['id']; ?>" <?php if($term['is_display_in_quotation']=='Y'){echo 'CHECKED';}?> name="is_terms_show_in_letter">
            <span class="checkmark"></span>
          </label>
           <!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $term['id']; ?>"> --><?php echo $term['name']; ?><!-- </a> -->
        </h4>
      </div>
      <div id="collapse_<?php echo $term['id']; ?>" class="panel-collapse collapse">
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              <textarea class="border-solid text-input terms_update" data-id="<?php echo $term['id']; ?>" style="width: 100%;  font-size: 13px; color: #000; padding: 4px;border: 1px solid #c9d4ea;"><?php echo $term['value']; ?></textarea>
            </div>
          </div>                                        
        </div>
      </div>

    </div>
    <?php
  }
}
?>