<?php
class Department_model extends CI_Model
{
        private $client_db = '';
        private $fsas_db = '';
		##----------------------------------------##
		##---------- Load Constructor ------------##
		function __construct() 
		{        
			parent::__construct();
            $this->selected_id_arr=array();
            $this->user_arr=array();
            $this->client_db = $this->load->database('client', TRUE);
            $this->fsas_db = $this->load->database('default', TRUE);
		}
		##----------------------------------------##
        
		function get_key_val($parent_id=0)
        {
          $result = array();           
          $subsql = '';      
          if($parent_id==0){
            $subsql .= " AND dept.parent_id=0";
          }
          else{
            $subsql .= " AND dept.parent_id='".$parent_id."'";
          }
          $subsql .= " AND dept.is_deleted='N'";
          $sql="SELECT dept.*,group_concat(concat(dept2.category_name,'@',dept2.id)) AS chield_name_id_str,if(dept2.id,'Y','N') AS has_child           
          FROM category AS dept 
          LEFT JOIN category AS dept2 on dept.id = dept2.parent_id
          WHERE 1=1 $subsql GROUP BY dept.id ORDER BY dept.listing_position ";         
          $query = $this->client_db->query($sql,false);        
        //   $last_query = $this->client_db->last_query();  
          if($query){
            foreach ($query->result() as $row) 
            {               
                $result[$row->id] = $row->category_name.'#'.$row->has_child;
            }
          }      
          
          return $result;        
        }

		##-------------------------------------------------------##
		##------------- Get n-level category array --------------##
        function get_nlevel_array()
        {  
			$sql="SELECT dept.*,COUNT(emp.id) emp_count FROM category AS dept 
                    LEFT JOIN user AS emp ON dept.id=emp.department_id AND emp.status IN ('0','1')
                    WHERE dept.is_deleted='N'  GROUP BY dept.id ORDER BY listing_position ";
            $query = $this->client_db->query($sql);
            $arr = array();
            if($query){
                if($query->num_rows() > 0)
                {    
                    foreach($query->result() as $row)
                    { 
                        $arr[$row->id] = array(
                                        'category_name'=> $row->category_name,
                                        'parent_id'=> $row->parent_id, 
                                        'listing_position'=> $row->listing_position,
                                        'emp_count'=>$row->emp_count
                                        );
                    }
                } 
            }
                       
            return $arr;
        }    
    
        ##-------------------------------------------------------##
		##---------------- Update category order ----------------##
        function update_category_order($arr,$parentid)
        {
            global $psortno;
            foreach ($arr as $i => $t) 
            {
                if($parentid==0)
                { 
                        $psortno++;
                        $this->update_parent_listing_position($t['id'],$psortno); 
                }
                $this->update_parent($t['id'],$parentid);
                if(isset($t['children'])) 
                {
                        $this->update_child_listing_position($t['children']);
                        $this->update_category_order($t['children'], $t['id']);
                }

            }

        }
    
        ##-------------------------------------------------------##
		##----- UPDATE SORT ORDER OF MENU WHERE PARENT ID 0 -----##
        function update_parent_listing_position($id,$psortno)
        {  
            $paramsArr = array('listing_position'=>$psortno);
            $this->client_db->where('id',$id);
            $this->client_db->update('category',$paramsArr);
        }

        
        ##-------------------------------------------------------##
		##---------- UPDATE SORT ORDER OF CHILD MENU ------------##
        function update_child_listing_position($arr)
        {
            $j=1;
            for($i=0; $i<count($arr); $i++)
            {  
                $paramsArr = array('listing_position'=>$j);
                $this->client_db->where('id',$arr[$i]['id']);
                $this->client_db->update('category',$paramsArr);

                $j++;
            }
        }

        
        ##-------------------------------------------------------##
		##----------- UPDATE PARENT ID OF EACH MENU -------------##
        function update_parent($id,$parentid)
        {   
            $paramsArr = array('parent_id'=>$parentid);
            $this->client_db->where('id',$id);
            $this->client_db->update('category',$paramsArr);
        }
        
		
				
		
    
        
		
		##----------------------------------------------------------------##
		##--------------------  Add  ---------------------------##
		##----------------------------------------------------------------##
        public function addDepartment($data)
        {
            if($this->client_db->insert('category',$data))
            {
               return $this->client_db->insert_id();
            }
            else
            {
              return false;
            }
        }

        function get_listing_position($id)
        {            
            $sql_lpos="Select max(listing_position) max_val FROM `category` WHERE parent_id='".$id."'";
            $query = $this->client_db->query($sql_lpos);
            $rec = $query->row();
            if($rec){
                return $listing_pos= $rec->max_val +1;
            }
            else{
                return 1;
            }
            
        }

		function add()
		{
            //echo "<pre>"; print_r($_POST); die();            
            ##----------------- Listing position ---------------------##
            $parent_id = $this->input->post('category_id');
            $sql_lpos="Select max(listing_position) max_val FROM `category` WHERE parent_id='".$parent_id."'";
            $query = $this->client_db->query($sql_lpos);
            $rec = $query->row();
            
            $listing_pos= $rec->max_val +1;
            ##--------------------------------------------------------##
            
            //############## Insert post value into table #############//
			$postdata = array(
						'category_name'=> $this->input->post('category_name'),
						'parent_id'   => $this->input->post('category_id'),
                        'listing_position'  => $listing_pos,
                        'date_added'=>date("Y-m-d H:i:s"),
                        'date_modified'=>date("Y-m-d H:i:s")
						);
	
			$this->client_db->insert('category',$postdata);
            $cat_id=$this->client_db->insert_id();           
							
			return "success";
		}
		
		
		##----------------------------------------------------------------##
		##--------------------  Edit  -------------------------##
		##----------------------------------------------------------------##
		function edit($id)
		{
            //#### Insert post value into table ##############//
			$postdata = array(
                'category_name'  => $this->input->post('category_name'),
                'parent_id'      => $this->input->post('category_id'),
                'listing_position'=> $listing_pos,
                'date_modified'=>date("Y-m-d H:i:s")
                );

			$this->client_db->where('id',$id);
        	$this->client_db->update('category',$postdata);            
			return "edit_success";
		}
		
		
		##----------------------------------------------------------------##
		##---------------------  GET Category Details  -------------------##
		##----------------------------------------------------------------##
		function get_details($id)
		{
			$result = array();
            $this->client_db->where('id',$id);
            $this->client_db->select('*');
			$query = $this->client_db->get('category');
            if($query){
                if($query->num_rows() > 0)
                {
                    $rec = $query->row();
                    $result = array(
                                'id' 			    => $rec->id,
                                'category_name' 	=> $rec->category_name,
                                'seo_url' 	        => $rec->seo_url,
                                'category_image' 	=> $rec->category_image,
                                'parent_id' 		=> $rec->parent_id,
                                'listing_position' 	=> $rec->listing_position,
                                'meta_title' 	    => $rec->meta_title,
                                'meta_keywords' 	=> $rec->meta_keyword,
                                'meta_description' 	=> $rec->meta_description
                                
                        );
                    
                }
            }
										
			return $result;			
		}
    
        
        ##----------------------------------------------------------------##
		##------------------  DELETE CATEGORY IMAGE  ---------------------##
		##----------------------------------------------------------------##
        function delete_cat_image($catId,$filename)
        {
            $this->client_db->where('id',$catId);
        	$this->client_db->update('category',array('category_image'=>''));
             
             ##----- Delete images from its respective paths -------##
             unlink('uploads/category_image/'.$filename);
             unlink('uploads/category_image/thumb/'.$filename);
             ##----------------------------------------------------##
        }
		
				
		
		##----------------------------------------------------------------##
		##---------------------  DELETE CATEGORY  ------------------------##
		##----------------------------------------------------------------##
		function delete($id)
		{
		    
            ##-- Check subcategory exists under that category or not ---##
            $this->client_db->where('parent_id',$id);
            $this->client_db->where('is_deleted','N');
            $this->client_db->select('id');
            $query=$this->client_db->get('category');
            
            if($query->num_rows() > 0)
            {    
                return "subcat_exists";
            }
            else
            {    
                ##-- Check product exists under that category or not ---##
                $this->client_db->where('department_id',$id);
                $this->client_db->where('status!=','2');
                $this->client_db->select('id');
                $query=$this->client_db->get('user');
                // echo $this->client_db->last_query();die();
                if($query->num_rows() > 0)
                {    
                    return "product_exists";
                }
                else
                {    
                    ##--------- delete records from category table --------------##
                    $paramsArr = array('is_deleted'=>'Y');
                    $this->client_db->where('id',$id);
                    $this->client_db->update('category',$paramsArr);
                    //$sql = "DELETE FROM `category` WHERE id ='".$id."'";
                    //$this->client_db->query($sql);                    
                    return "success";
                }
            }	
		}

		function first_level_data($limit='all')
        {
                $result = array();
                $this->client_db->where('parent_id', 0);
                $this->client_db->order_by('listing_position','ASC');
                $this->client_db->select('*');
                if($limit!='all'){
                $this->client_db->limit($limit);
                }
                $query = $this->client_db->get('category');
                if($query){
                    if($query->num_rows() > 0){
                        foreach ($query->result() as $row) 
                        {
                            $result[] = array(
                            'id' 						=> $row->id,
                            'category_name' 			=> $row->category_name,
                            'seo_name' 					=> $row->seo_name,
                            'parent_id'					=> $row->parent_id,
                            'serial'					=> $row->listing_position
                                            );
                        }
                    }
                }
                
                return $result;
        }
    
        function get_id_fromSeo($seo_name)
        {
            $this->client_db->where('seo_name',$seo_name);
            $this->client_db->select('id');
            $query = $this->client_db->get('category');
            if($query){
                if($query->num_rows() > 0)
                {
                    $rec = $query->row();
                    return $rec->id;
                }
                else
                {
                    return 0;
                }
            }
            else{
                return 0;
            }
            
        }

    ##---------------------------------------------------------------##
    ##----------------  Get product categories  ---------------------##
    ##---------------------------------------------------------------##
    function get_categories()
    {
        $this->client_db->order_by('category_name','ASC');
        $this->client_db->select('id,category_name');
        $query = $this->client_db->get('category');
        
        return $query->result_array();
    }


    // -----------------------------------
    // FRONT END
    function get_category_list($limit='all')
    {
        $result = array();
        // $this->client_db->where('parent_id', 0);
        // $this->client_db->where('status', 'Y');
        // $this->client_db->order_by('listing_position','ASC');
        // $this->client_db->select('*');    
        // $query = $this->client_db->get('category');
        // $query = $this->client_db->query($sql);                
        // return $query->result_array();
        $sql="SELECT * FROM category WHERE parent_id='0' AND status='Y' AND is_deleted='N' ORDER BY category_name";
        $query = $this->client_db->query($sql,false);
        if($query){
            if($query->num_rows() > 0)
            {
                foreach ($query->result() as $row) 
                {                
                    $seo_url='';
                    $where = "query='category_id=".$row->id."'";
                    $seo_url=get_value('keyword','url_alias',$where);

                    $result[] = array(
                            'id'=> $row->id,
                            'is_show_in_top_menu'=> $row->is_show_in_top_menu,
                            'category_name'=> $row->category_name,
                            'seo_url'=> ($seo_url)?$seo_url:'index/?category_id='.$row->id
                            );  
                }
            }
        }
        else{

        }
                                    
        return $result;
    }


    function get_all_selected_ids_arr($end_id='')
    {       
        
        $sql="SELECT id,parent_id FROM category WHERE id='".$end_id."'";
        $query=$this->client_db->query($sql,false);
        if($query){
            if($query->num_rows() > 0)
            {
                $row=$query->row();
                array_push($this->selected_id_arr, $row->parent_id);
                $this->get_all_selected_ids_arr($row->parent_id);
            } 
        }                
          
        return $this->selected_id_arr;      
        
    }

    function get_terr_array($selected_arr)
    {
        $tmp_arr=array();
       foreach($selected_arr as $id)
       {
            $sql="SELECT * FROM category WHERE id='".$id."'";
            $query=$this->client_db->query($sql,false);
            $result=array();
            if($query){
                if($query->num_rows() > 0)
                {                    
                    $row=$query->row();
                    $sql2="SELECT * FROM category WHERE parent_id='".$row->parent_id."'";
                    $query2=$this->client_db->query($sql2,false);
                    foreach ($query2->result() as $row2) 
                    {
                        $result[] = array(
                                'id'=> $row2->id,
                                'name'=> $row2->category_name
                                );  
                    }
                }
            }
            
            $tmp_arr[]=array('selected_id'=>$id,'chield_arr'=>$result);
       } 
       return $tmp_arr;
    }
}
