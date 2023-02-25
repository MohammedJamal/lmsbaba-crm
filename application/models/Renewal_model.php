<?php
class Renewal_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() 
	{
        parent::__construct();
		// $this->load->database();
		$this->user_arr=array();
		$this->class_name=$this->router->fetch_class();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    } 



    function get_list_count($argument=NULL)
    {
        $subsql = ''; 
		$subsqlInner='';
        // ---------------------------------------
        // SEARCH VALUE        
		
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND cus.assigned_user_id IN (".$argument['assigned_user'].")";
		}
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id
			FROM tbl_renewal AS t1
			JOIN 
			(
              SELECT    
              MAX(id) max_id, 
              renewal_id 
              FROM tbl_renewal_details 
              GROUP BY renewal_id
          	) rd_max ON (rd_max.renewal_id = t1.id)
			JOIN tbl_renewal_details t2 ON (t2.id=rd_max.max_id)
			LEFT JOIN
			(
				SELECT SUM(price) AS renewal_amount,renewal_detail_id AS p_rd_id FROM tbl_renewal_wise_product_service GROUP BY renewal_detail_id
			) AS product ON product.p_rd_id=rd_max.max_id
			LEFT JOIN
			(
				SELECT 
				count(T1.id) AS total_renewal_history_count,
				T1.renewal_id AS rid,
				if(T1.lead_id>0,'Y','N') AS is_any_renewal_tagged_with_lead,
				SUM(if(T1.lead_id>0,1,0)) AS count_renewal_tagged_with_lead,
				SUM(if(T2.current_stage_id=4,1,0)) AS count_renewal_won,
				SUM(if(T2.current_stage_id=3 || T2.current_stage_id=5 || T2.current_stage_id=6 || T2.current_stage_id=7,1,0)) AS count_renewal_lost,
				SUM(if(T2.current_stage_id!=4 || T2.current_stage_id!=3 || T2.current_stage_id!=5 || T2.current_stage_id!=6 || T2.current_stage_id!=7,1,0)) AS count_renewal_pending	
				FROM tbl_renewal_details AS T1
				LEFT JOIN lead AS T2 ON T1.lead_id=T2.id
				GROUP BY T1.renewal_id
			) AS active_renewal_chk ON active_renewal_chk.rid=t1.id
			INNER JOIN customer AS cus ON cus.id=t1.customer_id 
			$subsqlInner
			WHERE 1=1 $subsql GROUP BY t2.renewal_id";  

        $query = $this->client_db->query($sql,false);     
        if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
    }
    
    function get_list($argument=NULL)
    {       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';      
        $order_by_str = " ORDER BY t1.id DESC ";
        if($argument['sort_by']!='')
        {			
			$filter_sort_by_arr=explode("-",$argument['sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE        
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND cus.assigned_user_id IN (".$argument['assigned_user'].")";
		}			
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.customer_id,
			t1.created_at AS renewal_created_at,
			t1.Updated_at AS renewal_Updated_at,
			t2.id AS rd_id,
			t2.renewal_id,
			t2.next_follow_up_date,
			t2.renewal_date,
			t2.description,
			t2.attach_file,
			t2.lead_id,
			t2.created_at AS rd_created_at,
			t2.updated_at AS rd_updated_at,
			cus.id AS cus_id,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile_country_code AS cus_mobile_country_code,
			cus.mobile AS cus_mobile,
			cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
			cus.email AS cus_email,
			cus.website AS cus_website,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,
			product.*,
			active_renewal_chk.*,
			cus_user.name AS cus_assigned_user
			FROM tbl_renewal AS t1
			JOIN 
			(
              SELECT    
              MAX(id) max_id, 
              renewal_id 
              FROM tbl_renewal_details 
              GROUP BY renewal_id
          	) rd_max ON (rd_max.renewal_id = t1.id)
			JOIN tbl_renewal_details AS t2 ON (t2.id=rd_max.max_id)
			LEFT JOIN
			(
				SELECT SUM(price) AS renewal_amount,renewal_detail_id AS p_rd_id,
				GROUP_CONCAT(product_name) AS renewal_product_name_str 
				FROM tbl_renewal_wise_product_service GROUP BY renewal_detail_id
			) AS product ON product.p_rd_id=rd_max.max_id
			LEFT JOIN
			(
				SELECT 
				count(T1.id) AS total_renewal_history_count,
				T1.renewal_id AS rid,
				if(T1.lead_id>0,'Y','N') AS is_any_renewal_tagged_with_lead,
				SUM(if(T1.lead_id>0,1,0)) AS count_renewal_tagged_with_lead,
				SUM(if(T2.current_stage_id=4,1,0)) AS count_renewal_won,
				SUM(if(T2.current_stage_id=3 || T2.current_stage_id=5 || T2.current_stage_id=6 || T2.current_stage_id=7,1,0)) AS count_renewal_lost,
				SUM(if(T2.current_stage_id!=4 || T2.current_stage_id!=3 || T2.current_stage_id!=5 || T2.current_stage_id!=6 || T2.current_stage_id!=7,1,0)) AS count_renewal_pending,
				T2.current_stage_id
				FROM tbl_renewal_details AS T1
				LEFT JOIN lead AS T2 ON T1.lead_id=T2.id
				GROUP BY T1.renewal_id
			) AS active_renewal_chk ON active_renewal_chk.rid=t1.id
			INNER JOIN customer AS cus ON cus.id=t1.customer_id 
			INNER JOIN user AS cus_user ON cus_user.id=cus.assigned_user_id 
			$subsqlInner
			WHERE 1=1 $subsql GROUP BY t2.renewal_id $order_by_str LIMIT $start,$limit";
		 //echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function get_trans_list_count($argument=NULL)
    {
        $result = array();        
        $subsql = '';   
		$subsqlInner=''; 
        // ---------------------------------------
        // SEARCH VALUE  
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND cus.assigned_user_id IN (".$argument['assigned_user'].")";
		}
		
		if($argument['renewal_from_date']!='' && $argument['renewal_to_date']!='')
		{			
			$from = date_display_format_to_db_format($argument['renewal_from_date']);  
			$to = date_display_format_to_db_format($argument['renewal_to_date']);  
			$subsql.=" AND (t1.renewal_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($argument['followup_from_date']!='' && $argument['followup_to_date']!='')
		{			
			$from = date_display_format_to_db_format($argument['followup_from_date']);  
			$to = date_display_format_to_db_format($argument['followup_to_date']);  
			$subsql.=" AND (t1.next_follow_up_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($argument['lead_type']!='')
		{							
			// P,AL,WL,LL
			$renewal_status_str=$argument['lead_type'];
			$renewal_status_arr=explode(",",$renewal_status_str);

			if(count($renewal_status_arr))
			{
				$flag_pending='N';
				$flag_non_pending='N';
				$flag_p='N';
				$flag_al='N';
				$flag_wl='N';
				$flag_ll='N';
				$c_status_str='';
				$c_status_arr=array();
				$i=1;
				foreach($renewal_status_arr AS $status)
				{
					if(strtoupper($status)=='P')
					{
						// $subsql.=" OR t1.lead_id IS NULL";
						// $subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
						$flag_p='Y';
						$flag_pending='Y';
					}		
					else if(strtoupper($status)=='AL' || strtoupper($status)=='WL' || strtoupper($status)=='LL')
					{
						$flag_non_pending='Y';
						if(strtoupper($status)=='AL')
						{
							$al_status_arr =$this->opportunity_model->get_type_wise_lead_stage_arr('AL');
							if(count($al_status_arr))
							{
								foreach($al_status_arr AS $val)
								{
									array_push($c_status_arr, $val);
								}
							}
						}
						

						if(strtoupper($status)=='WL')
						{
							array_push($c_status_arr, 4);
							
							$flag_wl='Y';
						}

						if(strtoupper($status)=='LL')
						{
							array_push($c_status_arr, 3);
							array_push($c_status_arr, 5);
							array_push($c_status_arr, 6);
							array_push($c_status_arr, 7);

							$flag_ll='Y';
						}
						
					}					
					$i++;				
				}

				$c_status_arr_new=array_unique($c_status_arr);
				$c_status_str =implode(',', $c_status_arr_new);
				if($flag_pending=='Y' && $flag_non_pending=='N')
				{		
					$subsql.=" AND t1.lead_id IS NULL";	
					$subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
				}
				if($flag_pending=='N' && $flag_non_pending=='Y')
				{		
					$subsqlInner .=" INNER JOIN lead ON lead.id=t1.lead_id AND lead.current_stage_id IN ($c_status_str)";
				}
				else if($flag_pending=='Y' && $flag_non_pending=='Y')
				{		
					// $subsql.=" OR t1.lead_id IS NULL";				
					$subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
					$subsql.=" AND (t1.lead_id IS NULL OR lead.current_stage_id IN ($c_status_str))";
				}
			}			
						
		}
		else
		{
			$subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
		}
				
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.renewal_id,
			t1.next_follow_up_date,
			t1.renewal_date,
			t1.description,
			t1.attach_file,
			t1.lead_id,
			t1.created_at AS rd_created_at,
			t1.updated_at AS rd_updated_at,
			t2.customer_id,
			t2.created_at AS renewal_created_at,
			t2.Updated_at AS renewal_Updated_at,
			cus.id AS cus_id,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile_country_code AS cus_mobile_country_code,
			cus.mobile AS cus_mobile,
			cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
			cus.email AS cus_email,
			cus.website AS cus_website,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,
			product.*,			
			if(t1.lead_id>0,'Y','N') AS is_any_renewal_tagged_with_lead,
			lead.current_stage_id,
			if(lead.current_stage_id=4,1,0) AS count_renewal_won,
			cus_user.name AS cus_assigned_user
			FROM tbl_renewal_details AS t1 
			INNER JOIN tbl_renewal AS t2 ON t1.renewal_id=t2.id 
			LEFT JOIN
			(
				SELECT SUM(price) AS renewal_amount,renewal_detail_id AS p_rd_id,
				GROUP_CONCAT(product_name) AS renewal_product_name_str FROM tbl_renewal_wise_product_service GROUP BY renewal_detail_id
			) AS product ON product.p_rd_id=t1.id
			INNER JOIN customer AS cus ON cus.id=t2.customer_id 
			INNER JOIN user AS cus_user ON cus_user.id=cus.assigned_user_id 
			$subsqlInner
			WHERE 1=1 $subsql";
        $query = $this->client_db->query($sql,false);   
		if($query){
			if($query->num_rows() > 0) {
				return $query->num_rows();
			}
			else {
				return 0;
			}
		} 
		else{
			return 0;
		} 
        
    }
    
    function get_trans_list($argument=NULL)
    {       
        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';      
        $order_by_str = " ORDER BY t1.id DESC ";
        if($argument['sort_by']!='')
        {			
			$filter_sort_by_arr=explode("-",$argument['sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }

        // ---------------------------------------
        // SEARCH VALUE    
		if($argument['assigned_user']!='')
		{
			$subsql.=" AND cus.assigned_user_id IN (".$argument['assigned_user'].")";
		}
		if($argument['renewal_from_date']!='' && $argument['renewal_to_date']!='')
		{			
			$from = date_display_format_to_db_format($argument['renewal_from_date']);  
			$to = date_display_format_to_db_format($argument['renewal_to_date']);  
			$subsql.=" AND (t1.renewal_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($argument['followup_from_date']!='' && $argument['followup_to_date']!='')
		{			
			$from = date_display_format_to_db_format($argument['followup_from_date']);  
			$to = date_display_format_to_db_format($argument['followup_to_date']);  
			$subsql.=" AND (t1.next_follow_up_date BETWEEN '".$from."' AND '".$to."') ";
		}

		if($argument['lead_type']!='')
		{							
			// P,AL,WL,LL
			$renewal_status_str=$argument['lead_type'];
			$renewal_status_arr=explode(",",$renewal_status_str);

			if(count($renewal_status_arr))
			{
				$flag_pending='N';
				$flag_non_pending='N';
				$flag_p='N';
				$flag_al='N';
				$flag_wl='N';
				$flag_ll='N';
				$c_status_str='';
				$c_status_arr=array();
				$i=1;
				foreach($renewal_status_arr AS $status)
				{
					if(strtoupper($status)=='P')
					{
						// $subsql.=" OR t1.lead_id IS NULL";
						// $subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
						$flag_p='Y';
						$flag_pending='Y';
					}		
					else if(strtoupper($status)=='AL' || strtoupper($status)=='WL' || strtoupper($status)=='LL')
					{
						$flag_non_pending='Y';
						if(strtoupper($status)=='AL')
						{
							$al_status_arr =$this->opportunity_model->get_type_wise_lead_stage_arr('AL');
							if(count($al_status_arr))
							{
								foreach($al_status_arr AS $val)
								{
									array_push($c_status_arr, $val);
								}
							}
						}
						

						if(strtoupper($status)=='WL')
						{
							array_push($c_status_arr, 4);
							
							$flag_wl='Y';
						}

						if(strtoupper($status)=='LL')
						{
							array_push($c_status_arr, 3);
							array_push($c_status_arr, 5);
							array_push($c_status_arr, 6);
							array_push($c_status_arr, 7);

							$flag_ll='Y';
						}
						
					}					
					$i++;				
				}

				$c_status_arr_new=array_unique($c_status_arr);
				$c_status_str =implode(',', $c_status_arr_new);
				if($flag_pending=='Y' && $flag_non_pending=='N')
				{		
					$subsql.=" AND t1.lead_id IS NULL";	
					$subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
				}
				if($flag_pending=='N' && $flag_non_pending=='Y')
				{		
					$subsqlInner .=" INNER JOIN lead ON lead.id=t1.lead_id AND lead.current_stage_id IN ($c_status_str)";
				}
				else if($flag_pending=='Y' && $flag_non_pending=='Y')
				{		
					// $subsql.=" OR t1.lead_id IS NULL";				
					$subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
					$subsql.=" AND (t1.lead_id IS NULL OR lead.current_stage_id IN ($c_status_str))";
				}
			}			
						
		}
		else
		{
			$subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
		}
				
		
        // SEARCH VALUE
        // ---------------------------------------

            
		$sql="SELECT 
			t1.id,
			t1.renewal_id,
			t1.next_follow_up_date,
			t1.renewal_date,
			t1.description,
			t1.attach_file,
			t1.lead_id,
			t1.created_at AS rd_created_at,
			t1.updated_at AS rd_updated_at,
			t2.customer_id,
			t2.created_at AS renewal_created_at,
			t2.Updated_at AS renewal_Updated_at,
			cus.id AS cus_id,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile_country_code AS cus_mobile_country_code,
			cus.mobile AS cus_mobile,
			cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
			cus.email AS cus_email,
			cus.website AS cus_website,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,
			product.*,			
			if(t1.lead_id>0,'Y','N') AS is_any_renewal_tagged_with_lead,
			lead.current_stage_id,
			if(lead.current_stage_id=4,1,0) AS count_renewal_won,
			cus_user.name AS cus_assigned_user
			FROM tbl_renewal_details AS t1 
			INNER JOIN tbl_renewal AS t2 ON t1.renewal_id=t2.id 
			LEFT JOIN
			(
				SELECT SUM(price) AS renewal_amount,renewal_detail_id AS p_rd_id,
				GROUP_CONCAT(product_name) AS renewal_product_name_str FROM tbl_renewal_wise_product_service GROUP BY renewal_detail_id
			) AS product ON product.p_rd_id=t1.id
			INNER JOIN customer AS cus ON cus.id=t2.customer_id 
			INNER JOIN user AS cus_user ON cus_user.id=cus.assigned_user_id 
			$subsqlInner
			WHERE 1=1 $subsql $order_by_str LIMIT $start,$limit";
		//echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function create($data,$client_info=array())
	{
		
		if($this->client_db->insert('tbl_renewal',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function createDetails($data,$client_info=array())
	{
		
		if($this->client_db->insert('tbl_renewal_details',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function update($data,$id,$client_info=array())
	{		
		$this->client_db->where('id',$id);
		if($this->client_db->update('tbl_renewal',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function updateDetails($data,$id,$client_info=array())
	{		
		if($this->class_name=='cronjobs')
		{
			$config['hostname'] = DB_HOSTNAME;
	        $config['username'] = $client_info->db_username;
	        $config['password'] = $client_info->db_password;
	        $config['database'] = $client_info->db_name;
	        $config['dbdriver'] = 'mysqli';
	        $config['dbprefix'] = '';
	        $config['pconnect'] = FALSE;
	        $config['db_debug'] = FALSE;
	        $config['cache_on'] = FALSE;
	        $config['cachedir'] = '';
	        $config['char_set'] = 'utf8';
	        $config['dbcollat'] = 'utf8_general_ci'; 
	        $this->client_db=$this->load->database($config,TRUE);
		}
		$this->client_db->where('id',$id);
		if($this->client_db->update('tbl_renewal_details',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function delete($id)
	{
		$this->client_db->where('id', $id);
    	$this->client_db->delete('tbl_renewal');

    	$sql = "DELETE 
    			t1,t2
    			FROM tbl_renewal_details t1
  				JOIN tbl_renewal_wise_product_service t2 ON t1.id = t2.renewal_detail_id
  				WHERE t1.renewal_id = '".$id."'";
		$this->client_db->query($sql, array($id));


    	// $this->client_db->where('renewal_id', $id);
    	// $this->client_db->delete('tbl_renewal_details');

    	// $this->client_db->where('renewal_id', $id);
    	// $this->client_db->delete('tbl_renewal_wise_product_service');
	}
	
	function CreateRenewalWiseProductTag($data,$client_info=array())
	{		
		if($this->client_db->insert('tbl_renewal_wise_product_service',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function get_renewal_row($id)
    {            
		$sql="SELECT 
			t1.id,
			t1.customer_id,
			t1.created_at,
			t1.updated_at,			
			cus.id AS cus_id,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile_country_code AS cus_mobile_country_code,
			cus.mobile AS cus_mobile,
			cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
			cus.email AS cus_email,
			cus.website AS cus_website,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,
			cust_country.name AS cust_country_name,
			cust_assign_user.name AS cust_assign_user_name
			FROM tbl_renewal AS t1
			INNER JOIN customer AS cus ON cus.id=t1.customer_id 
			INNER JOIN countries AS cust_country ON cust_country.id=cus.country_id 
			INNER JOIN user AS cust_assign_user ON cust_assign_user.id=cus.assigned_user_id WHERE t1.id='".$id."'";
		// echo $sql;die();
        $query = $this->client_db->query($sql,false);
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
        
    }

    function get_renewal_details_by_renewal_id($renewal_id,$rd_id='')
    {
    	$subsql='';
    	if($renewal_id)
    	{
    		$subsql .=" AND t1.renewal_id='".$renewal_id."'";
    	}
    	if($rd_id)
    	{
    		$subsql .=" AND t1.id='".$rd_id."'";
    	}
    	$sql="SELECT 
			t1.id,
			t1.renewal_id,
			t1.next_follow_up_date,
			t1.renewal_date,
			t1.description,
			t1.attach_file,
			t1.lead_id,
			GROUP_CONCAT(CONCAT(t2.product_name,'#',t2.price)) AS renewal_product,
			t3.current_stage_id		
			FROM tbl_renewal_details AS t1 
			INNER JOIN tbl_renewal_wise_product_service AS t2 ON t1.id=t2.renewal_detail_id 
			LEFT JOIN lead AS t3 ON t1.lead_id=t3.id
			WHERE 1=1 $subsql GROUP BY t2.renewal_detail_id ORDER BY t1.id desc";
			// echo $sql; die();
		$query = $this->client_db->query($sql,false);
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function get_renewal_product_by_renewal_detail_id($rd_id)
    {
    	$sql="SELECT 
			t1.id,
			t1.renewal_detail_id,
			t1.product_id,
			t1.product_name,
			t1.price,
			t2.renewal_date,
			t2.next_follow_up_date,
			t2.description,
			t2.attach_file		
			FROM tbl_renewal_wise_product_service AS t1 
			INNER JOIN tbl_renewal_details AS t2 ON t1.renewal_detail_id=t2.id
			WHERE t1.renewal_detail_id='".$rd_id."'";
			// echo $sql; die();
		$query = $this->client_db->query($sql,false);
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function get_renewal_detail_row($id)
    {
    	$sql="SELECT 
			t1.id,
			t1.renewal_id,
			t1.next_follow_up_date,
			t1.renewal_date,
			t1.description,
			t1.attach_file,
			t1.lead_id,
			GROUP_CONCAT(CONCAT(t2.product_id,'#',t2.product_name,'#',t2.price)) AS renewal_product
			FROM tbl_renewal_details AS t1 
			INNER JOIN tbl_renewal_wise_product_service AS t2 ON t1.id=t2.renewal_detail_id
			WHERE t1.id='".$id."' GROUP BY t2.renewal_detail_id ORDER BY t1.id desc";
			// echo $sql; die();
		$query = $this->client_db->query($sql,false);
        $last_query = $this->client_db->last_query();
        
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
    }

    function product_delete_by_rdid($rdid)
	{
    	$this->client_db->where('renewal_detail_id', $rdid);
    	$this->client_db->delete('tbl_renewal_wise_product_service');
	}

	function delete_detail($id)
	{

    	$sql = "DELETE 
    			t1,t2
    			FROM tbl_renewal_details t1
  				JOIN tbl_renewal_wise_product_service t2 ON t1.id = t2.renewal_detail_id
  				WHERE t1.id = '".$id."'";
		$this->client_db->query($sql, array($id));
		return true;
    	
	}

	function get_renewal_list_by_today($date='',$client_info=array())
    {       
    	if($this->class_name=='cronjobs')
		{
			$config['hostname'] = DB_HOSTNAME;
	        $config['username'] = $client_info->db_username;
	        $config['password'] = $client_info->db_password;
	        $config['database'] = $client_info->db_name;
	        $config['dbdriver'] = 'mysqli';
	        $config['dbprefix'] = '';
	        $config['pconnect'] = FALSE;
	        $config['db_debug'] = FALSE;
	        $config['cache_on'] = FALSE;
	        $config['cachedir'] = '';
	        $config['char_set'] = 'utf8';
	        $config['dbcollat'] = 'utf8_general_ci'; 
	        $this->client_db=$this->load->database($config,TRUE);
		}

        $result = array();
        $start=$argument['start'];
        $limit=$argument['limit'];
        $subsql = '';   
		$subsqlInner='';      
        $order_by_str = " ORDER BY t1.id DESC ";
        // ---------------------------------------
        // SEARCH VALUE   
        if($date)
        {
        	$subsql.=" AND t1.next_follow_up_date<='".$date."'";	
        }     
			
        // SEARCH VALUE
        // ---------------------------------------

        $subsqlInner .=" LEFT JOIN lead ON lead.id=t1.lead_id";
		$sql="SELECT 
			t1.id,
			t1.renewal_id,
			t1.next_follow_up_date,
			t1.renewal_date,
			t1.description,
			t1.attach_file,
			t1.lead_id,
			t1.created_at AS rd_created_at,
			t1.updated_at AS rd_updated_at,
			t2.customer_id,
			t2.created_at AS renewal_created_at,
			t2.Updated_at AS renewal_Updated_at,
			cus.id AS cus_id,
			cus.first_name AS cus_first_name,
			cus.last_name AS cus_last_name,
			cus.mobile_country_code AS cus_mobile_country_code,
			cus.mobile AS cus_mobile,
			cus.mobile_whatsapp_status AS cus_mobile_whatsapp_status,
			cus.email AS cus_email,
			cus.website AS cus_website,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,
			cus.assigned_user_id AS cus_assigned_user_id,
			cus.source_id AS cus_source_id,
			product.*,			
			if(t1.lead_id>0,'Y','N') AS is_any_renewal_tagged_with_lead,
			lead.current_stage_id
			FROM tbl_renewal_details AS t1 
			INNER JOIN tbl_renewal AS t2 ON t1.renewal_id=t2.id 
			INNER JOIN
			(
				SELECT SUM(price) AS renewal_amount,renewal_detail_id AS p_rd_id,
				GROUP_CONCAT(product_name) AS product_name_str,
				GROUP_CONCAT(concat(product_name,'#',price,'#',product_id)) AS product_name_price_str 
				FROM tbl_renewal_wise_product_service GROUP BY renewal_detail_id
			) AS product ON product.p_rd_id=t1.id
			INNER JOIN customer AS cus ON cus.id=t2.customer_id 
			$subsqlInner
			WHERE t1.lead_id IS NULL $subsql";
		// echo $sql;die();
        $query = $this->client_db->query($sql,false);        
        // $last_query = $this->client_db->last_query();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
        
    }

    function get_product_str($rd_id)
	{
		$sql="SELECT GROUP_CONCAT(CONCAT(t1.product_id,'#',t1.product_name,'#',t1.price)) AS p_str FROM tbl_renewal_wise_product_service AS t1  WHERE t1.renewal_detail_id='".$rd_id."' GROUP BY t1.renewal_detail_id";
		// echo $sql; die();
		//$result=$this->client_db->query($sql);
		$query = $this->client_db->query($sql,false); 	
		if($query){
			if($query->num_rows()>0)
			{
				return $query->row()->p_str;
			}
			else
			{
				return '';
			}
		}
		else{
			return '';
		}	
	}      
}
?>