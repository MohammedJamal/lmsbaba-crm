<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_functions
{

	var $CI;
	var $category_tree_dropdown = "";
	var $tree_ul_li = "";
	var $note_html = "";

    function  __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }
	
	/**
	 * Dynamic function return a meassge div identifying its type
	 * @param array $val array field
	 * return a div with message identifying its type
	 */
	function view_message($val)
	{   
		$message_val = $val;
		$output_message="";
		$msg_type="";
		$msg_heading="";
				
		if(is_array($message_val))
		{
			//******** GET MESSAGE TYPE ********//
			if($message_val[0]==1)
			{    
				$msg_type = "danger";
				$msg_heading="Error!";
			}
			elseif($message_val[0]==2)
			{    
				$msg_type = "success";
				$msg_heading="Success!";
			}
			elseif($message_val[0]==3)
			{
				$msg_type = "warning";
				$msg_heading="Warning!";
			}
			elseif($message_val[0]==4)
			{
				$msg_type = "info";
				$msg_heading="Info!";
			}
			else
			{    
				$msg_type="";
				$msg_heading="";
			}
			
			$output_message .='<div class="col-md-12 header_notification_message">';
			$output_message .='<div class="alert alert-'.$msg_type.'">';
			$output_message .='<a href="#" data-dismiss="alert" onclick="close_alert();" class="close alert-close">x</a>';
			$output_message .='<h4 class="alert-heading">'.$msg_heading.'</h4>';
			$output_message .='<p>';
			
			$output_message .="<ul>";
			$output_message .= $message_val[1];
			$output_message .="</ul>";
			
			$output_message .='</p>';
			$output_message .='</div>';
			$output_message .='</div>';
			
		}
		
		return $output_message;
		
	}
	
	
	/**
     * Dynamic function to get a value respect to a particular filed of a 
     * table with passing where clause as parameter 
     * @param string $field Field name
     * @param string $tablename The table name
     * @param string $where Where clause condition
     * @return mixed Return a value. Also return NULL if record is not exists. 
     */
    function get_value($field,$tablename,$where='')
    {        
        $fieldval="";
		
        $sql="SELECT ".$field." FROM ".$tablename;
		if($where!="")
		{
			$sql = $sql." WHERE ".$where;
		}
		
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result))
		{	
			$rec = mysql_fetch_object($result);
			$fieldval= $rec->$field;
		}
		
		return $fieldval;
		
    }
	
	
	#---------------------------------------------------------------------------------##
    #---------------- Build n-level tree structure into dropdown menu ----------------##
    ##--------------------------------------------------------------------------------##
	
	
	/**
     * Dynamic function to get tree view structure of categories within select box
     * @param array $data_array category array id=>name structure
     * @param array $selected_ids passing ids displaying as a selected status within select box
     * @return string with html format 
     */
	function get_category_tree($data_array, $selected_ids=array(), $ignore_ids=array(), $params=array())
	{	
		global $category_tree_dropdown;
        
        $selectbox_name = "";
        $selectbox_class = "";
        $is_multiple = 0; //0 means single selected
        $default_selected = "";
        
        if(count($params))
        {  
            $selectbox_name = $params['selectbox_name'];
            $selectbox_id = (isset($params['selectbox_id']))?$params['selectbox_id']:$params['selectbox_name'];
            $selectbox_class = $params['class'];
            $is_multiple =  ($params['multiple']==1) ? 'multiple="multiple"' : '';
            $selectbox_class = $params['class'];
            $default_select_value = $params['default_select_value'];
            $default_select_text = ($params['default_select_text']!="") ? $params['default_select_text'] : "";
            $selected_ids=array($default_select_value);
        }
         
        if(count($selected_ids)<=0)
        {    
            $default_selected = 'selected="selected"'; 
        }

        
		$category_tree_dropdown .= '<select name="'.$selectbox_name.'" id="'.$selectbox_id.'" class="'.$selectbox_class.'" '.$is_multiple.'>';
        if($default_select_text!=''){
		$category_tree_dropdown .= '<option value="'.$default_select_value.'" '.$default_selected.'>'.$default_select_text.'</option>';
        }
		
		if(count($data_array))
		{
			$category_tree = $this->build_category_tree($data_array);
			$category_tree_dropdown .= $this->category_tree_dropdown($category_tree,$selected_ids,$ignore_ids);
		}
		
		$category_tree_dropdown .= '</select>'; 
		
		return $category_tree_dropdown;
		
		
	}
	
	function build_category_tree($data , $parent=0)
	{
		$tree = array();
		foreach($data as $d) 
		{
			if($d['parent_id'] == $parent) 
			{
				$children = $this->build_category_tree($data, $d['id']);
				
				// set a trivial key
				if (!empty($children))
				{
					$d['_children'] = $children;
				}
				$tree[] = $d;
			}
		}
		
		return $tree;
	}

	function category_tree_dropdown($tree, $selected_ids=array(),$ignore_ids=array(), $r = 0, $p = null)
	{
		global $category_tree_dropdown;
		
		foreach($tree as $i => $t) 
		{
			$dash = ($t['parent_id'] == 0) ? '' : str_repeat('--- ---', $r).' ';
			
            if(count($ignore_ids)!=0 && !in_array($t['id'],$ignore_ids))
            {
                $category_tree_dropdown .= '<option value="'.$t['id'].'" ';
                if(count($selected_ids) && in_array($t['id'],$selected_ids))
                {  
                    $category_tree_dropdown .='selected="selected"';
                }    
                $category_tree_dropdown .= ' >'.$dash.$t["name"].'</option>';
            }
            else
            {    
                $category_tree_dropdown .= '<option value="'.$t['id'].'" ';
                if(count($selected_ids) && in_array($t['id'],$selected_ids))
                {  
                    $category_tree_dropdown .='selected="selected"';
                }    
                $category_tree_dropdown .= ' >'.$dash.$t["name"].'</option>';
            }
			
			if(isset($t['_children']))
			{
				$this->category_tree_dropdown($t['_children'], $selected_ids, $ignore_ids, $r+1, $t['parent_id']);
			}
		}
	}
	#---------------------------------------------------------------------------------##
    #---------------- Build n-level tree structure into dropdown menu ----------------##
    ##--------------------------------------------------------------------------------##



	#---------------------------------------------------------------------------------##
    #----------------  n-level tree structure into note communication ----------------##
    ##--------------------------------------------------------------------------------##
	
	function rander_note_html($data_array)
	{	
		global $note_html;
		if(count($data_array))
		{
			$category_tree = $this->build_note_nth($data_array);
			$note_html .= $this->note_nth($category_tree);
		}			
		return ''.$note_html.'';		
	}
	
	function build_note_nth($data , $parent=0)
	{
		$tree = array();
		foreach($data as $d) 
		{
			if($d['parent_id'] == $parent) 
			{
				$children = $this->build_note_nth($data, $d['id']);				
				// set a trivial key
				if (!empty($children))
				{
					$d['_children'] = $children;
				}
				$tree[] = $d;
			}
		}		
		return $tree;
	}

	function note_nth($tree, $r = 0, $p = null)
	{
		global $note_html;
		$note_html .='<ul>';		
		foreach($tree as $i => $t) 
		{				
			// $dash = ($t['parent_id'] == 0) ? '' : str_repeat('>>', $r).' ';
			$dash_pre = ($t['parent_id'] == 0) ? '' : str_repeat('', $r).'';
			$dash_post = ($t['parent_id'] == 0) ? '' : str_repeat('', $r).' ';			
			
			$div_str='<li><div class="note-holder">
							<div class="note-name"><strong>'.$t["user_name"].'</strong> Wrote</div>
							<div class="note-date">'.datetime_db_format_to_display_format_ampm($t["created_at"]).'</div>
							<div class="note-txt">'.$t["note"].'</div>
							<a  class="pull-right note_add_btn" data-id="'.$t["form_id"].'" data-leadid="'.$t["lead_id"].'" data-user_name="'.$t["user_name"].'" data-parentid="'.$t["id"].'" data-note="'.$t["note"].'"><i class="fa fa-reply" aria-hidden="true"></i></a>
						</div>';
			$note_html .= ''.$dash_pre.$div_str.$dash_post.'';
			
			if(isset($t['_children']))
			{
				
				$this->note_nth($t['_children'], $r+1, $t['parent_id']);
			}

		}
		$note_html .='</ul>';
	}
	#---------------------------------------------------------------------------------##
    #----------------  n-level tree structure into note communication ----------------##
    ##--------------------------------------------------------------------------------##
    
    
    		
	
	
}

?>