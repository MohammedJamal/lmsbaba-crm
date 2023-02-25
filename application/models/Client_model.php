<?php
class Client_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() 
	{
		parent::__construct();
		$this->class_name=$this->router->fetch_class();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
		// echo 'Client DB connect';
	}

	public function get_details($client_id)
	{
		$sql="SELECT 
			T1.id,
			T1.id AS client_id,
			T1.account_type_id,
			T1.name,
			T1.company_name,
			T1.contact_person,
			T1.domain_name,
			T1.api_url,
			T1.api_access_token,
			T1.logo,
			T1.favicon,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T1.created_at,
			T1.updated_at,
			T2.name AS account_type 
			FROM tbl_clients AS T1
			LEFT JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id  WHERE T1.id='".$client_id."'";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
		
	}

	public function chk_valid_client($id)
	{
		
		$sql="SELECT id FROM tbl_clients WHERE id='".$id."' AND is_active='Y'";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows()==0)
			{
				return FALSE;			
			}
			else
			{
				return TRUE;
			}
		}
		else{
			return FALSE;
		}
			
	}

	public function chk_domain_name($domain_name)
	{
		
		$sql="SELECT id FROM tbl_clients WHERE domain_name='".trim($domain_name)."' ";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows()==0)
			{
				return FALSE;			
			}
			else
			{
				return TRUE;
			}
		}
		else{
			return FALSE;
		}
			
	}

	public function get_all($arg=array())
	{
		$subsql='';
		$limit='';
		if($arg['client_id'])
		{
			$subsql .=" AND id IN (".$arg['client_id'].")";
		}
		if($arg['cronjobs_action'])
		{
			if($arg['cronjobs_action']=='stage_and_status'){
				$subsql .=" AND is_lead_status_and_stage_update_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='indiamart'){
				$subsql .=" AND is_indiamart_api_call_by_cronjobs='Y'";	
				if($arg['get_limit_offset']!=''){
					if($arg['get_limit_offset']>=0){
					$limit =" LIMIT ".$arg['get_limit_offset'].",150";
					}
				}								
			}
			else if($arg['cronjobs_action']=='tradeindia'){
				$subsql .=" AND is_tradeindia_api_call_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='aajjo'){
				$subsql .=" AND is_aajjo_api_call_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='dashboard_report'){
				$subsql .=" AND is_dashboard_report_update_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='justdial'){
				$subsql .=" AND is_justdial_api_call_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='indiamart_backlog'){
				$subsql .=" AND is_indiamart_backlog_api_call_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='renewal'){
				$subsql .=" AND is_create_lead_from_renewal_call_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='daily_report'){
				$subsql .=" AND is_generate_daily_report_call_by_cronjobs='Y'";
			}
			else if($arg['cronjobs_action']=='exporterindia'){
				$subsql .=" AND is_exporterindia_api_call_by_cronjobs='Y'";
			}			
		}

		$sql="SELECT id,
			id AS client_id,
			account_type_id,
			name,
			domain_name,
			api_url,
			api_access_token,
			logo,
			favicon,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			db_name,
			indiamart_backlog_initial_date,
			is_active,
			created_at,
			updated_at 
			FROM tbl_clients 
			WHERE is_active='Y' AND is_deleted='N' $subsql ORDER BY 1 ".$limit;
		// return $sql; die();
		$query = $this->fsas_db->query($sql,false );
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_id_from_domain_name($domain_name)
	{
		$sql="SELECT id FROM tbl_clients WHERE domain_name='".$domain_name."' AND is_active='Y'";
		$query = $this->fsas_db->query($sql,false );
		if($query->num_rows())
		{
			return $query->row()->id;			
		}
		else
		{
			return FALSE;
		}	
		
	}

	public function get_token_from_domain_name($domain_name)
	{
		$sql="SELECT api_access_token FROM tbl_clients WHERE domain_name='".$domain_name."' AND is_active='Y'";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows())
			{
				return $query->row()->api_access_token;			
			}
			else
			{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}

	public function get_id_from_token($token)
	{
		$sql="SELECT id FROM tbl_clients WHERE api_access_token='".$token."' AND is_active='Y'";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows())
			{
				return $query->row()->id;			
			}
			else
			{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
			
		
	}

	public function get_detail_from_domain_name($domain_name)
	{
		$sql="SELECT id,name,domain_name,api_url,api_access_token FROM tbl_clients WHERE domain_name='".$domain_name."' AND is_active='Y'";
		$query = $this->fsas_db->query($sql,false );
		if($query->num_rows())
		{
			return $query->row_array();			
		}
		else
		{
			return FALSE;
		}	
		
	}

	public function insertBookDemo($data)
	{
		if($this->fsas_db->insert('book_demo',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}
	public function insertcontactUs($data)
	{
		if($this->fsas_db->insert('contact_us',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function create_cronjobs_log($data)
	{
		if($this->fsas_db->insert('tbl_cronjobs_logs',$data))
		{
			return $this->fsas_db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function update_cronjobs_log($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_cronjobs_logs',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

    function set_data($data)
    {
    	if($this->fsas_db->insert('test',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
    }


	public function get_details_by_token($token)
	{
		$sql="SELECT id,
			id AS client_id,
			name,
			domain_name,
			api_url,
			api_access_token,
			logo,
			favicon,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			db_name,
			is_website_capture_available,
			is_active,
			created_at,
			updated_at 
			FROM tbl_clients WHERE api_access_token='".$token."' AND is_active='Y'";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
		
	}

	public function get_cid_for_execute($f_name)
	{

		$sql="SELECT id,client_id,updated_db_name FROM tbl_cronjobs_logs WHERE function_name='".$f_name."' ORDER BY 1 DESC LIMIT 1";
		$query = $this->fsas_db->query($sql,false );
		if($query->num_rows())
		{
			$row=$query->row_array();	
			$cid=$row['client_id'];

			$sql2="SELECT id
			FROM tbl_clients WHERE id>'".$cid."' AND is_active='Y' ORDER BY 1 ASC LIMIT 1";
			$query2 = $this->fsas_db->query($sql2,false );
			if($query2->num_rows())
			{
				$row2=$query2->row();
				return $row2->id;
			}
			else
			{
				$sql2="SELECT id				
				FROM tbl_clients WHERE id<'".$cid."' AND is_active='Y' ORDER BY 1 ASC LIMIT 1";
				$query2 = $this->fsas_db->query($sql2,false );

				$row2=$query2->row();
				return $row2->id;
			}
		}
		else
		{
			$sql2="SELECT id
			FROM tbl_clients WHERE is_active='Y' ORDER BY 1 ASC LIMIT 1";
			$query2 = $this->fsas_db->query($sql2,false );
			if($query2->num_rows())
			{
				$row2=$query2->row();
				return $row2->id;
			}
			else
			{
				return '';
			}
		}

	}

	public function update($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_clients',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function get_details_by_domain_name($domain_name)
	{
		$sql="SELECT id,
			id AS client_id,
			name,
			domain_name,
			api_url,
			api_access_token,
			logo,
			favicon,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			db_name,
			is_active,
			created_at,
			updated_at 
			FROM tbl_clients WHERE domain_name='".$domain_name."' AND is_active='Y'";
		$query = $this->fsas_db->query($sql,false );
		//return $query->row();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
	}

	public function get_app_setting($id)
	{
		$sql="SELECT id,
			is_version_update_mandatory,
			version,
			updated_at 
			FROM tbl_mobile_app_settings WHERE id='".$id."'";
		$query = $this->fsas_db->query($sql,false );
		//return $query->row();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
	}

	function update_client_db($client_info=array(),$query_script)
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
		//echo $query_script; die();
		/*$dbRet = $this->client_db->query("$query_script");
		if( !$dbRet )
		{
		    $errNo   = $this->db->_error_number();
		    echo $errMess = $this->db->_error_message();die();
		}*/
		//echo strip_tags(addslashes($query_script)); die();
		if ($this->client_db->query($query_script))
		{
		   return"success";
		}
		else
		{
			return 'fail';
		}
    }

    function add_query_update_log($data)
	{
		if($this->fsas_db->insert('tbl_query_update_log',$data))
		{
	     return true;
		}
		else
		{
	    return false;
		}
	}
	function truncate_query_update_log()
	{
		$this->fsas_db->truncate('tbl_query_update_log');
	}
	
	function update_custom_client_db($client_info=array())
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
		
		$sql="SELECT 
			lead.id from lead 
			INNER JOIN lead_comment ON lead_comment.lead_id=lead.id 
			GROUP BY lead.id 
			HAVING SUM(if(lead_comment.next_followup_date IS NULL || date(lead_comment.next_followup_date)='0000-00-00',0,1))>0";
		//echo $sql; die();
		$query = $this->client_db->query($sql,false);
		if($query->num_rows() > 0) 
        {
			$data=array('is_followup_date_changed'=>'Y');
        	foreach($query->result() AS $row)
        	{				
				$this->client_db->where('id',$row->id);
				if($this->client_db->update('lead',$data))
				{					
					//return true;
				}
				else
				{
					//return false;
				}
			}
			return 'updated';
		}		
		else
		{
			
			return 'No record found';
		}
   }

   public function get_client_package_info($client_info=array())
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

		$sql="SELECT package_name,
			package_price,
			purchased_datetime,
			package_end_date,
			expire_date 
			FROM tbl_package_order";
		$query = $this->client_db->query($sql,false );
		//return $query->row();
		if($query){
			return $query->row();
		}
		else{
			return (object)array();
		}
	}

	public function get_only_all_client_list($argument=array())
	{
		$subsql='';
		$result=array();
		 $sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.assigned_to_user_id,
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T1.activity_status_type_id,
			T1.next_followup_date,
			T2.name AS account_type,
			SRV.service_end_date,
			SRV.service_end_day
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id 
			
			LEFT JOIN
			(
				SELECT 
				MIN(T4.end_date) AS service_end_date,
				DATEDIFF(MIN(T4.end_date), CURRENT_DATE) AS service_end_day,
				T3.client_id AS cid
				FROM tbl_service_order_detail AS T4
				INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id
				WHERE T4.is_active='Y' AND T4.end_date IS NOT NULL
				GROUP BY T3.client_id
			) AS SRV ON T1.id=SRV.cid

			WHERE T1.is_deleted='N' ";	
		$query = $this->fsas_db->query($sql,false );
		
		if($query){
			if($query->num_rows()>0)
			{            
				foreach($query->result() as $client_info)
				{
					$service_user_row=array();

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
					if($this->client_db=$this->load->database($config,TRUE)){

					
						$service_user_sql="SELECT 
							COUNT(id) total_user_count,
							SUM(if(status='0',1,0)) AS active_user_count,
							SUM(if(status='1',1,0)) AS inactive_user_count,
							SUM(if(status='2',1,0)) AS deleted_user_count,
							MAX(last_login_datetime) AS last_login_date,
							DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS not_logged_day
							FROM user 
							";
							//HAVING last_login_date < (CURRENT_DATE - INTERVAL 7 DAY)
						$service_user_query = $this->client_db->query($service_user_sql,false );

						if($service_user_query){
							$service_user_row=$service_user_query->row();
						}
					}

					$result[]=array(
						'client_id'=>$client_info->id,
						'account_type'=>$client_info->account_type,
						'company'=>$client_info->name,
						'is_active'=>$client_info->is_active,
						'activity_status_type_id'=>$client_info->activity_status_type_id,
						'next_followup_date'=>$client_info->next_followup_date,
						'service_end_date'=>$client_info->service_end_date,
						'service_end_day'=>$client_info->service_end_day,
						'last_login'=>$service_user_row->last_login_date,
						'not_logged_day'=>$service_user_row->not_logged_day,
						'total_user_count'=>$service_user_row->total_user_count,
						'active_user_count'=>$service_user_row->active_user_count,
						'inactive_user_count'=>$service_user_row->inactive_user_count,
						'deleted_user_count'=>$service_user_row->deleted_user_count
						
						
					);
				}
			}
		}
		return $result;
	}

	public function get_client_list($argument=array())
	{
		$subsql='';
		
		if($argument['account_type_id'])
		{
			$subsql .=" AND T1.account_type_id='".$argument['account_type_id']."'";
		}
		if($argument['is_active'])
		{
			$subsql .=" AND T1.is_active='".$argument['is_active']."'";
		}
		if($argument['user_type']!="Admin" && trim($argument['user_type'])!=''){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}

		$start=$argument['start'];
		$limit=$argument['limit'];

		if(trim($start)!=''){
			$limitcond="LIMIT ".$start.",".$limit;
		} else {
			$limitcond="";
		}		

		$result=array();
		 $sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.assigned_to_user_id,
			T1.name,
			T1.company_name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			T4.name AS assign_to_name,
			T5.last_touch_day,
			T6.name AS activity_name,
			T6.sub_name AS activity_sub_name
			 
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id 

			LEFT JOIN user AS T4 ON T1.assigned_to_user_id=T4.id 

			LEFT JOIN 
			(
				SELECT 
				DATEDIFF(CURRENT_DATE,MAX(created_at)) AS last_touch_day, 
				client_id 
				FROM tbl_activity_log 
				WHERE client_update_type_id='1' 
				GROUP BY client_id
			) AS T5 ON T5.client_id=T1.id

			LEFT JOIN tbl_client_activity_status_type AS T6 ON T1.activity_status_type_id=T6.id 
			
			WHERE T1.is_deleted='N' ".$subsql." ORDER BY T1.id DESC ".$limitcond;	
		$query = $this->fsas_db->query($sql,false );

		if($query->num_rows()>0)
		{            
			foreach($query->result() as $client_info)
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
				
				$user_row=array();
				$sql2="SELECT 
					COUNT(id) total_user_count,
					SUM(if(status='0',1,0)) AS active_user_count,
					SUM(if(status='1',1,0)) AS inactive_user_count,
					SUM(if(status='2',1,0)) AS deleted_user_count,
					MAX(last_login_datetime) AS last_login_date,
					DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS not_logged_day
					FROM user";
				$query2 = $this->client_db->query($sql2,false );
				if($query2){
					$user_row=$query2->row();
				}
				//IF(t1.expiry_date >=CURRENT_DATE,SUM(t1.no_of_user),'0') AS total_user_count,
				//IF(t1.end_date >=CURRENT_DATE,MIN(t1.end_date),MAX(t1.end_date)) AS end_date_data,
				//IF(t1.expiry_date >=CURRENT_DATE,MIN(t1.expiry_date),MAX(t1.expiry_date)) AS expiry_date_data,
				$calcul_row=array();
				$calcul_sql="SELECT 
				MIN(t1.start_date) AS start_date_data,
				MIN(t1.end_date) AS end_date_data,
				MIN(t1.expiry_date) AS expiry_date_data,
				
				(
					SELECT 
					SUM(t3.no_of_user) 
					FROM tbl_service_order_detail AS t3
					INNER JOIN tbl_service_order AS t4 ON t3.service_order_id=t4.id 
					WHERE t4.service_id=1 AND t4.client_id='".$client_info->id."' AND t3.expiry_date >=CURRENT_DATE
				) AS total_user_count,

				(
					SELECT 
					SUM(t1.price)
					FROM tbl_service_order_detail_log AS t1 
					INNER JOIN tbl_service_order_detail AS t2 ON t1.service_order_detail_id=t2.id
					INNER JOIN tbl_service_order AS t3 ON t2.service_order_id=t3.id
					WHERE t3.client_id='".$client_info->id."'
				) AS total_amount

				FROM tbl_service_order_detail AS t1 
				INNER JOIN tbl_service_order AS t2 ON t1.service_order_id=t2.id
	
				WHERE t2.client_id='".$client_info->id."' AND t1.is_active='Y' ";
				$calcul_query = $this->fsas_db->query($calcul_sql,false );
				if($calcul_query){
					$calcul_row=$calcul_query->row();
				}
				
				

				$result[]=array(
						'client_id'=>$client_info->id,
						'account_type'=>$client_info->account_type,
						'assign_to_name'=>$client_info->assign_to_name,
						'assigned_to_user_id'=>$client_info->assigned_to_user_id,
						'company'=>$client_info->company_name,
						'client_name'=>$client_info->name,
						'domain_name'=>$client_info->domain_name,
						'api_url'=>$client_info->api_url,
						'is_account_active'=>$client_info->is_active,
						'package_price'=>$calcul_row->total_amount,
						'start_date'=>$calcul_row->start_date_data,
						'package_end_date'=>$calcul_row->end_date_data,
						'expire_date'=>$calcul_row->expiry_date_data,
						'last_login'=>$user_row->last_login_date,
						'not_logged_day'=>$user_row->not_logged_day,
						'total_user_count'=>$calcul_row->total_user_count,
						'active_user_count'=>$user_row->active_user_count,
						'inactive_user_count'=>$user_row->inactive_user_count,
						'deleted_user_count'=>$user_row->deleted_user_count,
						'last_touch_day'=>$client_info->last_touch_day,
						'activity_name'=>$client_info->activity_name,
						'activity_sub_name'=>$client_info->activity_sub_name,
						'activity_status_type_id'=>$client_info->activity_status_type_id
						
				);
			}
		}
		return $result;
	}

	public function get_client_list_count($argument=array())
	{
		$subsql='';
		
		if($argument['account_type_id'])
		{
			$subsql .=" AND T1.account_type_id='".$argument['account_type_id']."'";
		}
		if($argument['is_active'])
		{
			$subsql .=" AND T1.is_active='".$argument['is_active']."'";
		}
		if($argument['user_type']!="Admin" && trim($argument['user_type'])!=''){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}

		$result=array();
		 $sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.assigned_to_user_id,
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			T4.name AS assign_to_name,
			T5.next_followup_date,
			T6.name AS activity_name,
			T6.sub_name AS activity_sub_name
			 
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id 

			LEFT JOIN user AS T4 ON T1.assigned_to_user_id=T4.id 

			LEFT JOIN 
			(
				SELECT 
				IF(followup_date >=CURRENT_DATE,MIN(followup_date),MAX(followup_date)) AS next_followup_date,
				client_id AS log_cid
				FROM tbl_activity_log
				WHERE followup_date!=''
				GROUP BY client_id
			) AS T5 ON T1.id=T5.log_cid

			LEFT JOIN tbl_client_activity_status_type AS T6 ON T1.activity_status_type_id=T6.id 
			
			WHERE T1.is_deleted='N' ".$subsql." ORDER BY T1.id DESC ";	
		$query = $this->fsas_db->query($sql,false );		

		return $query->num_rows();
	}

	public function update_client_package($data,$id,$client_info=array())
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

		$this->client_db->where('id',$id);
		if($this->client_db->update('tbl_package_order',$data))
		{			
			// return $last_query = $this->client_db->last_query();
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function chk_valid_adminportal_login($username,$password)
	{	
		$sql="SELECT id,
			user_type,
			name,
			email,
			mobile
			FROM user 
			WHERE email='".$username."' AND password='".$password."' 
			AND status='0'";
		$query = $this->fsas_db->query($sql,false );
		if($query->num_rows()==0)
		{
		  return FALSE;
		}
		else
		{
			return $query->row();
		}
	}

	public function get_account_type()
	{
		$result=array();
		$sql="SELECT 
			T1.id,
			T1.name
			FROM tbl_client_account_type AS T1  			
			WHERE T1.is_active='Y' ORDER BY T1.name";		
		$query = $this->fsas_db->query($sql,false );		
		if($query->num_rows()>0)
		{            
			$result=$query->result_array();
		}
		return $result;
	}

	public function get_client_all($arg=array())
	{
		$subsql='';
		$sql="SELECT id,
			id AS client_id,
			account_type_id,
			name,
			domain_name,
			api_url,
			api_access_token,
			logo,
			favicon,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			db_name,
			indiamart_backlog_initial_date,
			is_active,
			created_at,
			updated_at 
			FROM tbl_clients 
			WHERE is_deleted='N' $subsql ORDER BY 1";
		// echo $sql; die();
		$query = $this->fsas_db->query($sql,false );
		//return $query->result();
		if($query){
			return $query->result();
		}
		else{
			return (object)array();
		}
	}

	public function get_service_row($id)
	{
		$sql="SELECT 
			id,
			name,
			sort_order,
			is_active
			FROM tbl_service 
			WHERE id='".$id."'";
		$query=$this->fsas_db->query($sql,false );
		//return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function get_service()
	{
		$sql="SELECT 
			id,
			name,
			sort_order,
			is_active
			FROM tbl_service 
			WHERE is_active='Y' ORDER BY sort_order";
		$query=$this->fsas_db->query($sql,false );
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	function add_client_wise_service_order($post_data)
	{
		if($this->fsas_db->insert('tbl_service_order',$post_data))
   		{
           return $this->fsas_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function edit_client_wise_service_order($data,$id)
	{		
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_service_order',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}

	function edit_client_wise_service_order_detail($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_service_order_detail',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}

	function add_client_wise_service_order_detail($post_data)
	{
		if($this->fsas_db->insert('tbl_service_order_detail',$post_data))
   		{
           return $this->fsas_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function add_service_wise_service_call($post_data)
	{
		if($this->fsas_db->insert('tbl_service_wise_service_call',$post_data))
   		{
           return $this->fsas_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	function add_client_wise_service_order_detail_log($post_data)
	{
		if($this->fsas_db->insert('tbl_service_order_detail_log',$post_data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function edit_client_wise_service_order_detail_log($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_service_order_detail_log',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}

	public function get_client_wise_service_order_list($client_id)
	{
		 $sql="SELECT 
			t1.id,
			t1.service_order_id,
			t1.display_name,
			t1.no_of_user,
			t1.price,
			t1.start_date,
			t1.end_date,
			t1.expiry_date,
			t1.created_at,
			t1.is_active,
			t2.client_id,
			t2.service_id,
			t2.service_name,
			t2.created_at AS service_started_at,
			t6.*


			FROM tbl_service_order_detail AS t1 
			INNER JOIN tbl_service_order AS t2 ON t1.service_order_id=t2.id

			JOIN (

				SELECT 
			SUM(t3.price) AS total_price, t5.service_id AS sid
			FROM tbl_service_order_detail_log AS t3 
			INNER JOIN tbl_service_order_detail AS t4 ON t4.id=t3.service_order_detail_id
			INNER JOIN tbl_service_order AS t5 ON t5.id=t4.service_order_id
			WHERE t5.client_id='".$client_id."' 
			GROUP BY t5.service_id
			
			) AS t6 ON t6.sid=t2.service_id

			

			WHERE t2.client_id='".$client_id."' 
			ORDER BY t1.service_order_id,t1.expiry_date DESC";


			// return $sql="SELECT 
			// t1.id,
			// t1.service_order_id,
			// t1.display_name,
			// t1.no_of_user,
			// t1.price,
			// t1.start_date,
			// t1.end_date,
			// t1.expiry_date,
			// t1.created_at,
			// t2.client_id,
			// t2.service_id,
			// t2.service_name,
			// t2.created_at AS service_started_at,
			// SUM(t3.price) AS total_price
			// FROM tbl_service_order_detail AS t1 
			// INNER JOIN tbl_service_order AS t2 ON t1.service_order_id=t2.id
			// INNER JOIN tbl_service_order_detail_log AS t3 ON t3.service_order_detail_id=t1.id
			// WHERE t2.client_id='".$client_id."' GROUP BY t3.service_order_detail_id
			// ORDER BY t1.service_order_id,t1.expiry_date DESC";


		$query=$this->fsas_db->query($sql,false );
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}
	
	public function get_renewal_client_wise_service_order_list($client_id)
	{
		 $sql="SELECT 
			t1.id,
			t1.service_order_id,
			t1.display_name,
			t1.no_of_user,
			t1.price,
			t1.start_date,
			t1.end_date,
			t1.expiry_date,
			t1.created_at,
			t1.is_active,
			DATEDIFF(t1.end_date, CURRENT_DATE) AS service_end_day,
			t2.client_id,
			t2.service_id,
			t2.service_name,
			t2.created_at AS service_started_at,
			t6.*


			FROM tbl_service_order_detail AS t1 
			INNER JOIN tbl_service_order AS t2 ON t1.service_order_id=t2.id

			JOIN (

				SELECT 
			SUM(t3.price) AS total_price, t5.service_id AS sid
			FROM tbl_service_order_detail_log AS t3 
			INNER JOIN tbl_service_order_detail AS t4 ON t4.id=t3.service_order_detail_id
			INNER JOIN tbl_service_order AS t5 ON t5.id=t4.service_order_id
			WHERE t5.client_id='".$client_id."' 
			GROUP BY t5.service_id
			
			) AS t6 ON t6.sid=t2.service_id

			WHERE t2.client_id='".$client_id."' 
			ORDER BY t1.service_order_id,t1.expiry_date DESC";

		$query=$this->fsas_db->query($sql,false );
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}


	public function get_client_wise_service_order_list_BACKUP($client_id)
	{
		$sql="SELECT 
			t1.id,
			t1.service_order_id,
			t1.display_name,
			t1.no_of_user,
			t1.price,
			t1.start_date,
			t1.end_date,
			t1.expiry_date,
			t1.created_at,
			t2.client_id,
			t2.service_id,
			t2.service_name,
			t2.total_price,
			t2.created_at AS service_started_at
			FROM tbl_service_order_detail AS t1 
			INNER JOIN tbl_service_order AS t2 ON t1.service_order_id=t2.id WHERE t2.client_id='".$client_id."' 
			ORDER BY t1.service_order_id,t1.expiry_date DESC";
		$query=$this->fsas_db->query($sql,false );
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}


	public function get_service_order_by_client($client_id)
	{
		$sql="SELECT 
			t1.id,
			t1.client_id,
			t1.service_id,
			t1.service_name,
			t1.total_price,
			t1.created_at
			FROM tbl_service_order AS t1 WHERE t1.client_id='".$client_id."'";
		$query=$this->fsas_db->query($sql,false );
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_client_wise_service_order($id)
	{
		$sql="SELECT 
			t1.id,
			t1.client_id,
			t1.service_id,
			t1.service_name,
			t1.total_price,
			t1.created_at
			FROM tbl_service_order AS t1 WHERE t1.id='".$id."'";
		$query=$this->fsas_db->query($sql,false );
		//return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function chk_client_wise_service_order($client_id,$service_id)
	{
		$sql="SELECT 
			t1.id,
			t1.client_id,
			t1.service_id,
			t1.service_name,
			t1.total_price,
			t1.created_at
			FROM tbl_service_order AS t1 WHERE t1.client_id='".$client_id."' AND t1.service_id='".$service_id."'";
		$query=$this->fsas_db->query($sql,false );
		//return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	public function get_client_wise_service_order_detail_log($service_order_detail_id)
	{
		$sql="SELECT 
			t1.id,
			t1.service_order_detail_id,
			t1.display_name,
			t1.no_of_user,
			t1.price,
			t1.start_date,
			t1.end_date,
			t1.expiry_date,
			t1.created_at
			FROM tbl_service_order_detail_log AS t1 WHERE t1.service_order_detail_id='".$service_order_detail_id."' ORDER BY t1.id DESC";
		$query=$this->fsas_db->query($sql,false );
		//return $query->result_array();
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
	}

	public function get_last_service_order_detail_log_id($service_order_detail_id)
	{
		$sql="SELECT t1.id FROM tbl_service_order_detail_log AS t1 WHERE t1.service_order_detail_id='".$service_order_detail_id."' ORDER BY t1.id DESC LIMIT 1";
		$query=$this->fsas_db->query($sql,false );		
		if($query->num_rows()>0)
		{            
			$row=$query->row_array();
			return $row['id'];
		}
		else
		{
			return 0;
		}
	}

	public function get_service_order_detail($id)
	{
		$sql="SELECT 
			t1.id,
			t1.service_order_id,
			t1.display_name,
			t1.no_of_user,
			t1.price,
			t1.start_date,
			t1.end_date,
			t1.expiry_date,
			t1.created_at,
			t2.client_id,
			t2.service_id,
			t2.service_name,
			t2.total_price,
			t2.created_at AS service_started_at
			FROM tbl_service_order_detail AS t1 
			INNER JOIN tbl_service_order AS t2 ON t1.service_order_id=t2.id WHERE t1.id='".$id."'";
		$query=$this->fsas_db->query($sql,false );
		//return $query->row_array();
		if($query){
			return $query->row_array();
		}
		else{
			return array();
		}
	}

	function CreateActivityLog($data,$client_info=array())
	{        
		if($this->fsas_db->insert('tbl_activity_log',$data))
   		{
           return $this->fsas_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_im_offset()
	{
		$sql="SELECT text FROM test WHERE id='1'";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows())
			{
				return $query->row()->text;			
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
	public function update_im_offset($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('test',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function get_im_last_call($client_id)
	{
		$sql="SELECT last_indiamart_api_call_by_cronjobs FROM tbl_clients WHERE id='".$client_id."'";		
		$query = $this->fsas_db->query($sql,false );
		if($query){
			return $query->row()->last_indiamart_api_call_by_cronjobs;
		} else {
			return "";
		}
	}

	function migrate_permission($client_info=array())
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

		$return_array=array();
		// $sql="SELECT id	FROM user_wise_service_order";
		// $query = $this->client_db->query($sql,false );
		// return $query->num_rows();

		
		$sql="SELECT 
		t1.user_id,
		t1.attribute_name,
		t1.reserved_keyword,
		t1.display_value,
		t1.menu_id,
		t1.sub_menu_ids,
		t2.name AS user_name 
		FROM tbl_user_permission AS t1 INNER JOIN user AS t2 ON t1.user_id=t2.id ORDER BY t1.user_id";		
		$query = $this->client_db->query($sql,false );
		if($query->num_rows()>0)
		{            
			foreach($query->result_array() as $row)
			{
				
				$m_name='';
				if($row['menu_id'])
				{
					$sql2="SELECT menu_name FROM tbl_menu WHERE id='".$row['menu_id']."'";		
					$query2 = $this->fsas_db->query($sql2,false );
					$m_name=$query2->row()->menu_name;
				}
				

				$m_sub_name='';
				if($row['sub_menu_ids'])
				{
					$sql3="SELECT group_concat(sub_menu_name) AS sub_menu_name FROM tbl_sub_menu WHERE id IN (".$row['sub_menu_ids'].") GROUP BY menu_id";		
					$query3 = $this->fsas_db->query($sql3,false );
					$m_sub_name=$query3->row()->sub_menu_name;
				}
				
				$return_array[]=array(
										'user_id'=>$row['user_id'],
										'user_name'=>$row['user_name'].'('.$row['user_id'].')',
										'attribute_name'=>$row['attribute_name'],
										'display_value'=>$row['display_value'],
										'reserved_keyword'=>$row['reserved_keyword'],
										'menu_id'=>$row['menu_id'],
										'menu_name'=>$m_name,
										'sub_menu_ids'=>$row['sub_menu_ids'],
										'sub_menu_str'=>$m_sub_name
										
									);
			}
		}
		return $return_array;
		
    }

	public function get_comment_list($client_id)
	{
		$sql="SELECT 
		T1.id,
		T1.user_id,
		T1.client_id,
		T1.client_update_type_id,
		T1.activity_title,
		T1.activity_text,
		T1.ip_address,
		T1.followup_date,
		T1.created_at,
		T1.activity_type,
		T2.type_name,
		T3.name AS updated_by
		FROM tbl_activity_log AS T1
		LEFT JOIN tbl_client_update_type AS T2 ON T1.client_update_type_id=T2.id
		LEFT JOIN user AS T3 ON T1.user_id=T3.id
		WHERE T1.client_id='".$client_id."'
		ORDER BY id DESC";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows())
			{
				return $query->result_array();		
			}
			else
			{
				return array();
			}
		} else {
			return array();
		}
	}


	function update_client_assign_to_user($data,$id)
	{		
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_clients',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}		
	}

	public function insert_as_client($data)
	{
		if($this->fsas_db->insert('tbl_clients',$data))
		{			
			return $this->fsas_db->insert_id();		
		}
		else
		{
			return false;
		}	
	}

	public function total_client_db_count()
	{
		$sql="SELECT COUNT(*) AS total_count
			FROM information_schema.SCHEMATA 
			WHERE SCHEMA_NAME LIKE 'u412811690_%' AND SCHEMA_NAME !='u412811690_lmsbaba';";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			$query=$query->row();
			return $query->total_count;
		}
		else{
			return '0';
		}	
	}

	public function total_client_count()
	{
		$sql="SELECT COUNT(id) AS total_count
			FROM tbl_clients";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			$query=$query->row();
			return $query->total_count;
		}
		else{
			return '0';
		}	
	}

	public function get_last_lmsid()
	{
		$sql="SELECT id
			FROM tbl_clients
			ORDER BY id DESC";
		$query = $this->fsas_db->query($sql,false );
		if($query){
			$query=$query->row();
			return $query->id;
		}
		else{
			return '0';
		}	
	}

	function create_capture_log($data)
	{
		if($this->fsas_db->insert('tbl_capture_logs',$data))
		{
			return $this->fsas_db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function update_capture_log($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_capture_logs',$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}



}
?>