<?php 
if(count($rows)){
    if($is_fb_connected=='Y'){
        $fields = $rows[0]->field_data;
        if(count($fields)){
            echo '<ul class="list-group">';
            foreach($fields AS $field){           
                echo '<li class="list-group-item">'.$field->name.'</li>';    
            }
            echo '</ul>';
        }
    }
    else{
        
        echo '<ul class="list-group">';
        foreach($rows AS $field){           
            echo '<li class="list-group-item">'.$field['fb_field_name'].'</li>';    
        }
        echo '</ul>';
    }
    
}
?>
