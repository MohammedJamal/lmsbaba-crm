<?php
class Adminportal_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() 
	{
		parent::__construct();
		$this->class_name=$this->router->fetch_class();
		$this->user_ids_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
		// echo 'Client DB connect';
	}



	public function get_inactive_client_list_BACKUP_23_11_22($argument=array())
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
		if($argument['user_type']!="Admin" && trim($argument['user_type'])!=""){
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
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			MIN(T4.start_date) AS start_date_data,
			IF(T4.end_date >=CURRENT_DATE,MIN(T4.end_date),MAX(T4.end_date)) AS end_date_data,
			IF(T4.expiry_date >=CURRENT_DATE,MIN(T4.expiry_date),MAX(T4.expiry_date)) AS expiry_date_data,
			T5.last_touch_day,
			T6.name AS assign_to_name,
			T7.next_followup_date,

			IACT.id AS call_id,
			IACT.scheduled_call_datetime,
			IACT.scheduled_call_datetime AS next_followup_date,
			IACT.actual_call_done_datetime,
			IACT.service_call_type_id AS call_type_id,
			IACT.name AS call_type_name,
			IF(IACT.actual_call_done_datetime!='',1,0) AS call_status
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 

			LEFT JOIN tbl_service_order AS T3 ON T1.id=T3.client_id
			LEFT JOIN tbl_service_order_detail AS T4 ON T3.id=T4.service_order_id

			LEFT JOIN 
			(
				SELECT 
				DATEDIFF(CURRENT_DATE,MAX(created_at)) AS last_touch_day, 
				client_id
				FROM tbl_activity_log 
				WHERE client_update_type_id='1' GROUP BY client_id
			) AS T5 ON T1.id=T5.client_id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id 

			LEFT JOIN 
			(
				SELECT 
				IF(followup_date >=CURRENT_DATE,MIN(followup_date),MAX(followup_date)) AS next_followup_date,
				client_id AS log_cid
				FROM tbl_activity_log
				WHERE followup_date!=''
				GROUP BY client_id
			) AS T7 ON T1.id=T7.log_cid

			LEFT JOIN 
			(
				SELECT 
				ICL.id,
				ICL.service_id AS sid,
				ICL.client_id AS cl_client_id,
				ICL.scheduled_call_datetime,
				ICL.actual_call_done_datetime,
				ICL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS ICL
				LEFT JOIN tbl_service_call_type AS DCT ON ICL.service_call_type_id=DCT.id
				WHERE ICL.actual_call_done_datetime IS NULL AND ICL.service_call_type_id='5' 
				ORDER BY ICL.id DESC
			) AS IACT ON T1.id=IACT.cl_client_id
			
			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T1.is_deleted='N' AND T1.activity_status_type_id IN(2,4,6) ".$subsql." GROUP BY T1.id ORDER BY T1.id DESC ";

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
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );

				if($query2){
					// if($query2->num_rows()>0)
					// {
						$user_row=$query2->row();

						$result[]=array(
							'client_id'=>$client_info->id,
							'account_type'=>$client_info->account_type,
							'assign_to_name'=>$client_info->assign_to_name,
							'company'=>$client_info->name,
							'domain_name'=>$client_info->domain_name,
							'api_url'=>$client_info->api_url,
							'is_account_active'=>$client_info->is_active,
							'start_date'=>$client_info->start_date_data,
							'package_end_date'=>$client_info->end_date_data,
							'expire_date'=>$client_info->expiry_date_data,
							'last_login'=>$user_row->last_login_date,
							'not_logged_day'=>$user_row->not_logged_day,
							'last_touch_day'=>$client_info->last_touch_day,
							'total_user_count'=>$user_row->total_user_count,
							'active_user_count'=>$user_row->active_user_count,
							'inactive_user_count'=>$user_row->inactive_user_count,
							'deleted_user_count'=>$user_row->deleted_user_count,
							'call_status'=>$client_info->call_status,
							'call_id'=>$client_info->call_id,
							'call_type_id'=>$client_info->call_type_id,
							'call_type_name'=>$client_info->call_type_name,
							'actual_call_done_datetime'=>$client_info->actual_call_done_datetime,
							'scheduled_call_datetime'=>$client_info->scheduled_call_datetime,
							'next_followup_date'=>$client_info->next_followup_date,
							'activity_name'=>$client_info->activity_name,
							'activity_sub_name'=>$client_info->activity_sub_name,
							'activity_status_type_id'=>$client_info->activity_status_type_id
						);

					//}
				}
			}
		}
		return $result;
	}

	public function get_inactive_client_list($argument=array())
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
		if($argument['user_type']!="Admin" && trim($argument['user_type'])!=""){
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
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			MIN(T4.start_date) AS start_date_data,
			IF(T4.end_date >=CURRENT_DATE,MIN(T4.end_date),MAX(T4.end_date)) AS end_date_data,
			IF(T4.expiry_date >=CURRENT_DATE,MIN(T4.expiry_date),MAX(T4.expiry_date)) AS expiry_date_data,
			T5.last_touch_day,
			T6.name AS assign_to_name,
			

			IACT.id AS call_id,
			IACT.scheduled_call_datetime,
			IACT.actual_call_done_datetime,
			IACT.service_call_type_id AS call_type_id,
			IACT.name AS call_type_name,
			IF(IACT.actual_call_done_datetime!='',1,0) AS call_status
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 

			LEFT JOIN tbl_service_order AS T3 ON T1.id=T3.client_id
			LEFT JOIN tbl_service_order_detail AS T4 ON T3.id=T4.service_order_id

			LEFT JOIN 
			(
				SELECT 
				DATEDIFF(CURRENT_DATE,MAX(created_at)) AS last_touch_day, 
				client_id
				FROM tbl_activity_log 
				WHERE client_update_type_id='1' GROUP BY client_id
			) AS T5 ON T1.id=T5.client_id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id 

			LEFT JOIN 
			(
				SELECT 
				ICL.id,
				ICL.service_id AS sid,
				ICL.client_id AS cl_client_id,
				ICL.scheduled_call_datetime,
				ICL.actual_call_done_datetime,
				ICL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS ICL
				LEFT JOIN tbl_service_call_type AS DCT ON ICL.service_call_type_id=DCT.id
				WHERE ICL.actual_call_done_datetime IS NULL AND ICL.service_call_type_id IN(4,5,6) 
				GROUP BY ICL.client_id
				ORDER BY ICL.id DESC
			) AS IACT ON T1.id=IACT.cl_client_id
			
			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T1.is_deleted='N' AND T1.activity_status_type_id IN(2,4,6) ".$subsql." GROUP BY T1.id ORDER BY T1.id DESC ";

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
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );

				if($query2){
					// if($query2->num_rows()>0)
					// {
						$user_row=$query2->row();

						$result[]=array(
							'client_id'=>$client_info->id,
							'account_type'=>$client_info->account_type,
							'assign_to_name'=>$client_info->assign_to_name,
							'company'=>$client_info->name,
							'domain_name'=>$client_info->domain_name,
							'api_url'=>$client_info->api_url,
							'is_account_active'=>$client_info->is_active,
							'start_date'=>$client_info->start_date_data,
							'package_end_date'=>$client_info->end_date_data,
							'expire_date'=>$client_info->expiry_date_data,
							'last_login'=>$user_row->last_login_date,
							'not_logged_day'=>$user_row->not_logged_day,
							'last_touch_day'=>$client_info->last_touch_day,
							'total_user_count'=>$user_row->total_user_count,
							'active_user_count'=>$user_row->active_user_count,
							'inactive_user_count'=>$user_row->inactive_user_count,
							'deleted_user_count'=>$user_row->deleted_user_count,
							'call_status'=>$client_info->call_status,
							'call_id'=>$client_info->call_id,
							'call_type_id'=>$client_info->call_type_id,
							'call_type_name'=>$client_info->call_type_name,
							'actual_call_done_datetime'=>$client_info->actual_call_done_datetime,
							'scheduled_call_datetime'=>$client_info->scheduled_call_datetime,
							'next_followup_date'=>$client_info->next_followup_date,
							'activity_name'=>$client_info->activity_name,
							'activity_sub_name'=>$client_info->activity_sub_name,
							'activity_status_type_id'=>$client_info->activity_status_type_id
						);

					//}
				}
			}
		}
		return $result;
	}

	public function get_inactive_client_list_count($argument=array())
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
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}

		$result=array();
		$sql="SELECT 
			T1.id,
			T1.account_type_id,
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
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			MIN(T4.start_date) AS start_date_data,
			IF(T4.end_date >=CURRENT_DATE,MIN(T4.end_date),MAX(T4.end_date)) AS end_date_data,
			IF(T4.expiry_date >=CURRENT_DATE,MIN(T4.expiry_date),MAX(T4.expiry_date)) AS expiry_date_data,
			T5.last_touch_day,
			T6.name AS assign_to_name,
			

			IACT.id AS call_id,
			IACT.scheduled_call_datetime,
			IACT.actual_call_done_datetime,
			IACT.service_call_type_id AS call_type_id,
			IACT.name AS call_type_name,
			IF(IACT.actual_call_done_datetime!='',1,0) AS call_status
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 

			LEFT JOIN tbl_service_order AS T3 ON T1.id=T3.client_id
			LEFT JOIN tbl_service_order_detail AS T4 ON T3.id=T4.service_order_id

			LEFT JOIN 
			(
				SELECT 
				DATEDIFF(CURRENT_DATE,MAX(created_at)) AS last_touch_day, 
				client_id
				FROM tbl_activity_log 
				WHERE client_update_type_id='1' GROUP BY client_id
			) AS T5 ON T1.id=T5.client_id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id 

			LEFT JOIN 
			(
				SELECT 
				ICL.id,
				ICL.service_id AS sid,
				ICL.client_id AS cl_client_id,
				ICL.scheduled_call_datetime,
				ICL.actual_call_done_datetime,
				ICL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS ICL
				LEFT JOIN tbl_service_call_type AS DCT ON ICL.service_call_type_id=DCT.id
				WHERE ICL.actual_call_done_datetime IS NULL AND ICL.service_call_type_id IN(4,5,6) 
				GROUP BY ICL.client_id
				ORDER BY ICL.id DESC
			) AS IACT ON T1.id=IACT.cl_client_id
			
			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T1.is_deleted='N' AND T1.activity_status_type_id IN(2,4,6) ".$subsql." GROUP BY T1.id ";	
		$query = $this->fsas_db->query($sql,false );		

		return $query->num_rows();
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

	public function get_update_type()
	{
		$result=array();
		$sql="SELECT 
			T1.id,
			T1.type_name
			FROM tbl_client_update_type AS T1  			
			ORDER BY T1.id";		
		$query = $this->fsas_db->query($sql,false );		
		if($query->num_rows()>0)
		{            
			$result=$query->result_array();
		}
		return $result;
	}

	public function get_inactive_client_details($client_id)
	{
		$result=array();
		
			$sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T2.name AS account_type			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 
			WHERE T1.id='".$client_id."' ";

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
					DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS last_touch_day
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );


				if($query2){

					$user_row=$query2->row();

					$result[]=array(
						'client_id'=>$client_info->id,
						'account_type'=>$client_info->account_type,
						'company'=>$client_info->name,
						'domain_name'=>$client_info->domain_name,
						'api_url'=>$client_info->api_url,
						'is_account_active'=>$client_info->is_active,
						'start_date'=>$client_info->start_date_data,
						'package_end_date'=>$client_info->end_date_data,
						'expire_date'=>$client_info->expiry_date_data,
						'last_login'=>$user_row->last_login_date,
						'last_touch_day'=>$user_row->last_touch_day,
						'total_user_count'=>$user_row->total_user_count
					);

				}
			}
		}
		return $result;
	}

	public function get_my_client_details($client_id)
	{
		$result=array();
		
			$sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T2.name AS account_type			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 
			WHERE T1.id='".$client_id."' ";

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

				$calcul_row=array();
				$calcul_sql="SELECT 
					SUM(t3.no_of_user) AS total_user_count
					FROM tbl_service_order_detail AS t3
					INNER JOIN tbl_service_order AS t4 ON t3.service_order_id=t4.id 
					WHERE t4.service_id=1 AND t4.client_id='".$client_info->id."' AND t3.expiry_date >=CURRENT_DATE";
				$calcul_query = $this->fsas_db->query($calcul_sql,false );
				if($calcul_query){
					$calcul_row=$calcul_query->row();
				}

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

					$result[]=array(
						'client_id'=>$client_info->id,
						'account_type'=>$client_info->account_type,
						'company'=>$client_info->name,
						'domain_name'=>$client_info->domain_name,
						'api_url'=>$client_info->api_url,
						'is_account_active'=>$client_info->is_active,
						'start_date'=>$client_info->start_date_data,
						'package_end_date'=>$client_info->end_date_data,
						'expire_date'=>$client_info->expiry_date_data,
						'last_login'=>$user_row->last_login_date,
						'not_logged_day'=>$user_row->not_logged_day,
						'last_touch_day'=>$user_row->last_touch_day,
						'total_user_count'=>$calcul_row->total_user_count
					);

				}
			}
		}
		return $result;
	}

	public function get_service_call_details($client_id,$service_id)
	{
		$result=array();
		
			$sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T2.name AS account_type,

			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count		

			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id
			INNER JOIN tbl_clients AS T1 ON T3.client_id=T1.id
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 
			WHERE T1.id='".$client_id."' AND T4.id='".$service_id."' ";

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
					MAX(last_login_datetime) AS last_login_date
					FROM user";
				$query2 = $this->client_db->query($sql2,false );



				if($query2){

					$user_row=$query2->row();

					$result[]=array(
						'client_id'=>$client_info->id,
						'account_type'=>$client_info->account_type,
						'company'=>$client_info->name,
						'domain_name'=>$client_info->domain_name,
						'api_url'=>$client_info->api_url,
						'is_account_active'=>$client_info->is_active,
						'start_date'=>$client_info->start_date_data,
						'package_end_date'=>$client_info->end_date_data,
						'expire_date'=>$client_info->expiry_date_data,
						'last_login'=>$user_row->last_login_date,
						'last_touch_day'=>$user_row->last_touch_day,
						'total_user_count'=>$user_row->total_user_count,
						'module_name'=>$client_info->module_name,
						'service_name'=>$client_info->service_name,
					);

				}
			}
		}
		return $result;
	}

	public function insert_comment($data)
	{
		
		if($this->fsas_db->insert('tbl_activity_log',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function insert_call_update($data)
	{
		
		if($this->fsas_db->insert('tbl_service_wise_service_call',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function service_call_update($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_service_wise_service_call',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}	
	}
	public function service_update($data,$id)
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

	public function get_active_service_call_details($subsql="")
	{
		
		$result=array();

		$sql="SELECT * 
			FROM tbl_service_wise_service_call
			WHERE actual_call_done_datetime IS NULL ".$subsql." 
			ORDER BY id DESC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->row();
		}
		return $result;
	}

	public function get_all_active_service_call_list($subsql="")
	{
		
		$result=array();

		$sql="SELECT * 
			FROM tbl_service_wise_service_call
			WHERE actual_call_done_datetime IS NULL ".$subsql." 
			ORDER BY id DESC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->result_array();
		}
		return $result;
	}

	public function get_active_service_call_count($argument=array())
	{
		
		$subsql="";
		if(trim($argument['client_id'])!=""){
			$subsql.=" AND client_id='".$argument['client_id']."' ";
		}

		if(trim($argument['call_type_id'])!=""){
			$subsql.=" AND service_call_type_id='".$argument['call_type_id']."' ";
		}

		$sql="SELECT * 
			FROM tbl_service_wise_service_call
			WHERE actual_call_done_datetime IS NULL ".$subsql."
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			return $query->num_rows();
		} else {
			return '0';
		}
		
	}

	public function get_inactive_comment_list($client_id)
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

	public function get_download_comment_list($client_id)
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

	public function get_renewal_list($argument=array())
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
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}

		

		if(isset($argument['show_data_type']) && trim($argument['show_data_type'])!='')
		{
			if($argument['show_data_type']=='R'){
				$subsql .=" AND T1.activity_status_type_id IN(1,2) ";
			} elseif($argument['show_data_type']=='PR'){
				$subsql .=" AND T1.activity_status_type_id IN(3,4) ";
			} elseif($argument['show_data_type']=='ALL'){
				$subsql .=" AND T1.activity_status_type_id IN(1,2,3,4,5,6) ";
			}

			if(trim($argument['filter_year'])!='' || trim($argument['filter_month'])!=''){
				
				if(trim($argument['filter_year'])!='' && trim($argument['filter_month'])=='')
				{
					$subsql .=" AND YEAR(T4.end_date)='".$argument['filter_year']."'";
				} 
				elseif(trim($argument['filter_year'])!='' && trim($argument['filter_month'])!='')
				{
					$subsql .=" AND YEAR(T4.end_date)='".$argument['filter_year']."' AND MONTH(T4.end_date)='".$argument['filter_month']."' ";
				}
				elseif(trim($argument['filter_year'])=='' && trim($argument['filter_month'])!='')
				{
					$subsql .=" AND YEAR(T4.end_date)=YEAR(CURRENT_DATE) AND MONTH(T4.end_date)='".$argument['filter_month']."' ";
				}
			}

			
		} else {

			if(trim($argument['filter_year'])!='' || trim($argument['filter_month'])!=''){
				
				
				if(trim($argument['filter_year'])!='' && trim($argument['filter_month'])=='')
				{
					$subsql .=" AND YEAR(T4.end_date)='".$argument['filter_year']."'";
				} 
				elseif(trim($argument['filter_year'])!='' && trim($argument['filter_month'])!='')
				{
					$subsql .=" AND YEAR(T4.end_date)='".$argument['filter_year']."' AND MONTH(T4.end_date)='".$argument['filter_month']."' ";
				}
				elseif(trim($argument['filter_year'])=='' && trim($argument['filter_month'])!='')
				{
					$subsql .=" AND YEAR(T4.end_date)=YEAR(CURRENT_DATE) AND MONTH(T4.end_date)='".$argument['filter_month']."' ";
				}

				
			} else {
				$subsql .=" AND T1.activity_status_type_id IN(1,2,3,4) AND MONTH(T4.end_date)=MONTH(CURRENT_DATE) AND YEAR(T4.end_date)=YEAR(CURRENT_DATE) ";
			}
			

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
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.price AS service_amount,
			T5.last_touch_day,
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,

			DLD.id AS call_id,
			DLD.scheduled_call_datetime,
			DLD.actual_call_done_datetime,
			DLD.service_call_type_id AS call_type_id,
			DLD.name AS call_type_name,
			IF(DLD.actual_call_done_datetime!='',1,0) AS call_status,

			RSD.all_end_date,
			RSD.all_total_price
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id

			INNER JOIN tbl_clients AS T1 ON T3.client_id=T1.id
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id

			LEFT JOIN 
			(
				SELECT 
				DATEDIFF(CURRENT_DATE,MAX(created_at)) AS last_touch_day, 
				client_id 
				FROM tbl_activity_log 
				WHERE client_update_type_id='1' GROUP BY client_id
			) AS T5 ON T5.client_id=T1.id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id 

			LEFT JOIN 
			(
				SELECT 
				IF(followup_date >=CURRENT_DATE,MIN(followup_date),MAX(followup_date)) AS next_followup_date,
				client_id AS log_cid
				FROM tbl_activity_log
				WHERE followup_date!=''
				GROUP BY client_id
			) AS T7 ON T1.id=T7.log_cid

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id

			LEFT JOIN 
			(
				SELECT 
				DCL.id,
				DCL.scheduled_call_datetime,
				DCL.actual_call_done_datetime,
				DCL.service_call_type_id,
				DCL.client_id AS cl_client_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS DCL
				LEFT JOIN tbl_service_call_type AS DCT ON DCL.service_call_type_id=DCT.id
				WHERE DCL.service_call_type_id IN(4,5,6) AND actual_call_done_datetime IS NULL 
				GROUP BY DCL.client_id
				ORDER BY DCL.id DESC
			) AS DLD ON T1.id=DLD.cl_client_id

			LEFT JOIN
			(
				SELECT
				TOD.id,
				GROUP_CONCAT(DATE_FORMAT(TOD.end_date, '%d-%b-%Y') ORDER BY TOD.end_date ASC SEPARATOR ',<br>') AS all_end_date,
				GROUP_CONCAT(TOD.price ORDER BY TOD.end_date ASC SEPARATOR ',<br>') AS all_total_price,
				TSO.client_id
				FROM  tbl_service_order_detail AS TOD 
				INNER JOIN tbl_service_order AS TSO ON TOD.service_order_id=TSO.id
				WHERE TOD.is_active = 'Y'
				GROUP BY TSO.client_id
			) AS RSD ON T1.id=RSD.client_id 
			
			WHERE T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql." 
			GROUP BY T1.id 
			ORDER BY T4.end_date ASC ";

			//WHERE T4.is_active='Y' AND T4.end_date<=(CURRENT_DATE+INTERVAL 60 DAY) ".$subsql." ORDER BY T4.end_date DESC "
			
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
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );


				// if($query2){
				// 	if($query2->num_rows()>0)
				// 	{
						$user_row=$query2->row();

						$result[]=array(
							'client_id'=>$client_info->id,
							'account_type'=>$client_info->account_type,
							'assign_to_name'=>$client_info->assign_to_name,
							'company'=>$client_info->name,
							'domain_name'=>$client_info->domain_name,
							'api_url'=>$client_info->api_url,
							'is_account_active'=>$client_info->is_active,
							'module_name'=>$client_info->module_name,
							'service_order_detail_id'=>$client_info->service_order_detail_id,
							'service_name'=>$client_info->service_name,
							'service_amount'=>$client_info->service_amount,
							'start_date'=>$client_info->start_date_data,
							'package_end_date'=>$client_info->end_date_data,
							'expire_date'=>$client_info->expiry_date_data,
							'last_login'=>$user_row->last_login_date,
							'not_logged_day'=>$user_row->not_logged_day,
							'last_touch_day'=>$client_info->last_touch_day,
							'total_user_count'=>$user_row->total_user_count,
							'active_user_count'=>$user_row->active_user_count,
							'inactive_user_count'=>$user_row->inactive_user_count,
							'deleted_user_count'=>$user_row->deleted_user_count,
							'call_status'=>$client_info->call_status,
							'call_id'=>$client_info->call_id,
							'call_type_id'=>$client_info->call_type_id,
							'call_type_name'=>$client_info->call_type_name,
							'actual_call_done_datetime'=>$client_info->actual_call_done_datetime,
							'scheduled_call_datetime'=>$client_info->scheduled_call_datetime,
							'next_followup_date'=>$client_info->next_followup_date,
							'activity_name'=>$client_info->activity_name,
							'activity_sub_name'=>$client_info->activity_sub_name,
							'activity_status_type_id'=>$client_info->activity_status_type_id,
							'all_end_date'=>$client_info->all_end_date,
							'all_total_price'=>$client_info->all_total_price
							
							
						);

				// 	}
				// }
			}
		}
		
		return $result;
	}

	public function get_renewal_details($client_id,$service_order_detail_id)
	{
		$result=array();
		
		$sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T2.name AS account_type,	
			T3.service_name AS module_name,
			T3.id AS service_order_id,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.display_name AS service_name,
			T4.no_of_user AS total_user_count	
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id
			INNER JOIN tbl_service_order AS T3 ON T1.id=T3.client_id
			INNER JOIN tbl_service_order_detail AS T4 ON T3.id=T4.service_order_id

			WHERE T1.id='".$client_id."' AND T4.id='".$service_order_detail_id."' ";

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
					DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS last_touch_day
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );


				if($query2){

					$user_row=$query2->row();

					$result[]=array(
						'client_id'=>$client_info->id,
						'account_type'=>$client_info->account_type,
						'company'=>$client_info->name,
						'domain_name'=>$client_info->domain_name,
						'api_url'=>$client_info->api_url,
						'is_account_active'=>$client_info->is_active,
						'module_name'=>$client_info->module_name,
						'service_name'=>$client_info->service_name,
						'start_date'=>$client_info->start_date_data,
						'package_end_date'=>$client_info->end_date_data,
						'expire_date'=>$client_info->expiry_date_data,
						'last_login'=>$user_row->last_login_date,
						'last_touch_day'=>$user_row->last_touch_day,
						'total_user_count'=>$client_info->total_user_count
					);

				}
			}
		}
		return $result;
	}

	public function get_renewal_comment_list($client_id)
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
	
	public function get_client_wise_service_list($argument=array())
	{
		$subsql='';
		if($argument['client_id'])
		{
			$subsql .=" AND T3.client_id='".$argument['client_id']."'";
		}

		$result=array();
		
			$sql="SELECT 
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date,
			T4.end_date,
			T4.expiry_date
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id

			WHERE T4.is_active='Y' ".$subsql." ORDER BY T4.end_date DESC ";

		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows()>0)
			{
				$result=$query->result_array();
			}
		}
		return $result;
	}

	public function get_client_wise_renewal_service_list($client_id)
	{
		$subsql='';
		if(trim($client_id)!='')
		{
			$subsql .=" AND T3.client_id='".$client_id."'";
		}

		$result=array();
		
			$sql="SELECT 
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day,
			T4.no_of_user,
			T4.price,
			T4.display_name AS service_name,
			T4.start_date,
			T4.end_date,
			T4.expiry_date
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id

			WHERE T4.is_active='Y' AND T4.end_date<=(CURRENT_DATE+INTERVAL 60 DAY) ".$subsql." ORDER BY T4.end_date DESC ";
			
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows()>0)
			{
				$result=$query->result_array();
			}
		}
		return $result;
	}

	public function get_downloads_client_list($argument=array())
	{
		$subsql='';
		if($argument['account_type_id'])
		{
			$subsql .=" AND T1.account_type_id='".$argument['account_type_id']."'";
		}
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
	

		$result=array();
		$sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.name,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			TA.total_amount,
			T4.name AS assign_to_name,
			T6.name AS activity_name,
			T6.sub_name AS activity_sub_name,

			DLD.id AS call_id,
			DLD.scheduled_call_datetime,
			DLD.scheduled_call_datetime AS next_followup_date,
			DLD.actual_call_done_datetime,
			DLD.service_call_type_id AS call_type_id,
			DLD.name AS call_type_name,
			IF(DLD.actual_call_done_datetime!='',1,0) AS call_status
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id 

			LEFT JOIN
			(
				SELECT 
				SUM(ta1.price) AS total_amount, ta3.client_id AS ta_cid
				FROM tbl_service_order_detail_log AS ta1 
				INNER JOIN tbl_service_order_detail AS ta2 ON ta1.service_order_detail_id=ta2.id
				INNER JOIN tbl_service_order AS ta3 ON ta2.service_order_id=ta3.id
			) AS TA ON T1.id=TA.ta_cid

			LEFT JOIN 
			(
				SELECT 
				DCL.id,
				DCL.service_id AS sid,
				DCL.client_id AS cl_client_id,
				DCL.scheduled_call_datetime,
				DCL.actual_call_done_datetime,
				DCL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS DCL
				LEFT JOIN tbl_service_call_type AS DCT ON DCL.service_call_type_id=DCT.id
				WHERE DCL.service_call_type_id='7' 
				ORDER BY DCL.id DESC
				LIMIT 1
			) AS DLD ON T1.id=DLD.cl_client_id

			LEFT JOIN user AS T4 ON T1.assigned_to_user_id=T4.id

			LEFT JOIN tbl_client_activity_status_type AS T6 ON T1.activity_status_type_id=T6.id
			
			WHERE T1.is_deleted='N' AND T1.activity_status_type_id='7' $subsql ORDER BY T1.id DESC ".$limitcond;	
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
					MAX(last_login_datetime) AS last_login_date
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
					'company'=>$client_info->name,
					'domain_name'=>$client_info->domain_name,
					'api_url'=>$client_info->api_url,
					'is_account_active'=>$client_info->is_active,
					'package_price'=>$calcul_row->total_amount,
					'start_date'=>$calcul_row->start_date_data,
					'package_end_date'=>$calcul_row->end_date_data,
					'expire_date'=>$calcul_row->expiry_date_data,
					'last_login'=>$user_row->last_login_date,
					'total_user_count'=>$calcul_row->total_user_count,
					'active_user_count'=>$user_row->active_user_count,
					'inactive_user_count'=>$user_row->inactive_user_count,
					'deleted_user_count'=>$user_row->deleted_user_count,
					'activity_name'=>$client_info->activity_name,
					'activity_sub_name'=>$client_info->activity_sub_name,
					'activity_status_type_id'=>$client_info->activity_status_type_id,
					'call_status'=>$client_info->call_status,
					'call_id'=>$client_info->call_id,
					'call_type_id'=>$client_info->call_type_id,
					'call_type_name'=>$client_info->call_type_name,
					'actual_call_done_datetime'=>$client_info->actual_call_done_datetime,
					'scheduled_call_datetime'=>$client_info->scheduled_call_datetime,
					'next_followup_date'=>$client_info->next_followup_date
				);
			}
		}
		return $result;
	}

	public function get_users_list($argument=array())
	{

		$result=array();
		$sql="SELECT 
			T1.id,
			T1.manager_id,
			T1.user_type,
			T1.name,
			T1.email,
			T1.mobile,
			T1.company_name,
			T1.company_profile,
			T1.create_date,
			T2.name AS manager_name,
			GROUP_CONCAT(menu_name SEPARATOR '<br>') AS menu_permission

			FROM user AS T1
			LEFT JOIN user AS T2 ON T1.manager_id=T2.id
			LEFT JOIN tbl_user_wise_admin_menu_permission  AS T3 ON T1.id=T3.user_id
			LEFT JOIN tbl_admin_menu AS T4 ON T3.menu_id=T4.id
			GROUP BY T1.id
			ORDER BY T1.id ASC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->result_array();
		}
		return $result;
	}

	function get_users_list_treeview($user_id=0)
    {
       
        $sql="SELECT id, name, email
		FROM user
		WHERE manager_id='".$user_id."' ";       

        $query = $this->fsas_db->query($sql,false);        

		 
        $arr = array();
		if($query){
			if($query->num_rows())
			{	
				$i=1;
				foreach($query->result() as $row)
				{
					$text='';
					$action_btn='';
					if($row->id!=1){
						$action_btn ='[ 
							<a href="JavaScript:void(0)" title="Permission" class="permission_update_row text-success" data-uid="'.$row->id.'"><i class="fa fa-list" aria-hidden="true"></i></a> 
							|
							<a href="JavaScript:void(0)" title="Edit" id="edit_user_view" class="text-primary" data-uid="'.$row->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							|
							<a href="JavaScript:void(0)" title="Delete" id="delete_user" class="text-danger" data-uid="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
							]';
					 } else {
						$action_btn ='[ 
							<a href="JavaScript:void(0)" title="Edit" id="edit_user_view" class="text-primary" data-uid="'.$row->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							]';
					 }

					$text .='<b>'.$row->name.'</b> ('.$row->email.') '.$action_btn;  

					$arr[] = array(
								'text'=>$text,
								'id'=>$row->id,
								'children'=>$this->get_users_list_treeview($row->id)
								);
					$i++;
				}
			}  
		}		

         
        return $arr;   
    } 

	public function get_manager_list($user_id="")
	{
		$sub_sql="";
		if($user_id>0){
			$sub_sql="WHERE id!='".$user_id."' ";
		}
		$result=array();
		$sql="SELECT 
			id, name, email			
			FROM user
			".$sub_sql."
			ORDER BY name ASC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->result_array();
		}
		return $result;
	}

	public function get_work_order_list($argument=array())
	{

		$result=array();
		$sql="SELECT 
			T1.id,
			T1.account_type_id,
			T1.no_of_user,
			T1.domain_name,
			T1.display_name AS service_title,
			T1.deal_value,
			T1.payment_terms,
			T1.lead_id,
			T1.subscription_period,
			T1.company_name,
			T1.company_address,
			T1.pin_code,
			T1.gst_number,
			T1.owner_name,
			T1.contact_person,
			T1.email_id,
			T1.mobile_number,
			T1.website,
			T1.country_id,
			T1.state_id,
			T1.city_id,
			T1.start_date,
			T1.end_date,
			T1.expiry_date,
			T1.created_at,
			T1.approve_status,

			T2.name AS account_type,
			T3.name AS service_type

			FROM tbl_clients_work_order AS T1 
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 
			LEFT JOIN tbl_service AS T3 ON T3.id=T1.service_id 
			ORDER BY T1.id DESC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->result_array();
		}
		return $result;
	}

	public function get_all_menu_list()
	{

		$result=array();
		$sql="SELECT 
			id AS menu_id,
			menu_name,
			controller_name,
			method_name			
			FROM tbl_admin_menu
			WHERE is_active='Y'
			ORDER BY sort_order ASC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->result_array();
		}
		return $result;
	}

	public function get_all_menu_with_element_list()
	{

		$result=array();
		$sql="SELECT 
			id AS menu_id,
			menu_name,
			controller_name,
			method_name			
			FROM tbl_admin_menu
			WHERE is_active='Y'
			ORDER BY sort_order ASC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{
			if($query->num_rows()>0) 
			{     
				foreach($query->result_array() as $row)
				{
					$sql2 ="SELECT 
					id AS element_id,
					menu_id,
					reserved_keyword,
					text_name			
					FROM tbl_admin_menu_element
					WHERE is_active='Y' AND menu_id='".$row['menu_id']."'
					ORDER BY sort_order ASC
					"; 
					$query2=$this->fsas_db->query($sql2);
					$result2=$query2->result_array();
					$result[]=array('menu_list'=>$row,'menu_wise_element_list'=>$result2);
				}
			}
		}
		return $result;
	}

	public function get_menu_permission_id_list($arg=array())
	{
		$result=array();
		$sql="SELECT menu_id 
		FROM tbl_user_wise_admin_menu_permission
		WHERE user_id='".$arg['user_id']."'
		";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$list=$query->result_array();
			foreach($list AS $menu){
				$result[]=$menu['menu_id'];
			}
		}
		return $result;
	}

	public function get_element_permission_id_list($arg=array())
	{
		$result=array();
		$sql="SELECT element_id 
		FROM tbl_user_wise_admin_menu_element_permission
		WHERE user_id='".$arg['user_id']."'
		";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$list=$query->result_array();
			foreach($list AS $menu){
				$result[]=$menu['element_id'];
			}
		}
		return $result;
	}


	public function set_menu_permission()
    {
    	$user_id = $this->input->post('user_id');
		$menu_id = $this->input->post('menu_id');
		$element_id = $this->input->post('element_id');		

		$sql_delete = "DELETE FROM tbl_user_wise_admin_menu_permission WHERE user_id='".$user_id."'";
		$this->fsas_db->query($sql_delete);
		if(count($menu_id)>0)
		{
			foreach($menu_id as $k=>$v)
			{
				$sql_insert = "INSERT INTO 
				tbl_user_wise_admin_menu_permission 
				(user_id,menu_id) 
				VALUES ('".$user_id."','".$v."')"; 
				$this->fsas_db->query($sql_insert);
			}
		}
 
		$sql_delete = "DELETE FROM  tbl_user_wise_admin_menu_element_permission WHERE user_id='".$user_id."'";
		$this->fsas_db->query($sql_delete);
		if(is_array($element_id) && count($element_id)>0)
		{
			foreach($element_id as $k=>$v)
			{
				$sql_insert = "INSERT INTO 
				tbl_user_wise_admin_menu_element_permission 
				(user_id,element_id) 
				VALUES ('".$user_id."','".$v."')"; 
				$this->fsas_db->query($sql_insert);
			}
		}

	}

	public function get_user_wise_menu_list($menu_permission_id)
    {
		$result=array();
		$sql="SELECT 
			id AS menu_id,
			menu_name,
			controller_name,
			method_name			
			FROM tbl_admin_menu
			WHERE is_active='Y' AND id IN($menu_permission_id)
			ORDER BY sort_order ASC
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->result_array();
		}
		return $result;

	}

	public function duplicate_user_email_check($email,$id)
	{
		$sub_sql="";
		if($id>0){
			$sub_sql=" AND id<>".$id;
		}
		$sql = "SELECT * FROM user WHERE email='".$email."' ".$sub_sql;
        $query  = $this->fsas_db->query($sql);                   
        
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
        
	}

	public function insert_user($data)
	{
		
		if($this->fsas_db->insert('user',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function insert_work_order($data)
	{
		
		if($this->fsas_db->insert('tbl_clients_work_order',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function update_work_order($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('tbl_clients_work_order',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}
			
	}

	public function delete_work_order($id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->delete('tbl_clients_work_order'))
		{
			return true;		  
		}
		else
		{
			return false;
		}
			
	}

	public function get_user_details($user_id)
    {
		$result=array();
		$sql="SELECT 
			T1.id,
			T1.user_type,
			T1.manager_id,
			T1.name,
			T1.email,
			T1.mobile
			
			FROM user AS T1
			WHERE id ='".$user_id."'
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->row();
		}
		return $result;

	}

	public function update_user($data,$id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->update('user',$data))
		{
			return true;		  
		}
		else
		{
			return false;
		}
			
	}

	public function delete_user($id)
	{
		$this->fsas_db->where('id',$id);
		if($this->fsas_db->delete('user'))
		{

			$this->fsas_db->where('user_id',$id);
			$this->fsas_db->delete('tbl_user_wise_admin_menu_permission');

			return true;		  
		}
		else
		{
			return false;
		}
			
	}

	function get_self_and_under_alluser_ids($user_id=0)
    {
        $result = array();
        
        $sql="SELECT id 
		FROM user
		WHERE id='".$user_id."'";        

        $query = $this->fsas_db->query($sql,false);
        if($query){
			
			if($query->num_rows())
			{      	
				foreach($query->result() as $row)
				{
					$this->user_ids_arr[]=$row->id;
					$this->get_employee_nth_level($row->id);
				}
			} 
		}               
        return $this->user_ids_arr;
    }

	function get_employee_nth_level($user_id=0)
    {  
        $sql="SELECT id
		FROM user
		WHERE manager_id='".$user_id."' ";       

        $query = $this->fsas_db->query($sql,false); 
        $tmp_arr = array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result() as $row)
				{
					$this->user_ids_arr[]=$row->id;
					$this->get_employee_nth_level($row->id);
				}
			} 
		}
         
    }

	public function get_all_service_calls_list($argument=array())
	{
		$subsql='';
		
		
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
		if(trim($argument['call_type'])!=""){
			if($argument['call_type']==1)$subsql .=" AND wel_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==2)$subsql .=" AND d3_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==3)$subsql .=" AND d7_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==4)$subsql .=" AND d15_actual_call_done_datetime IS NULL ";	
		}
		if(trim($argument['selected_date'])!=""){
			$subsql .="AND (
			DATE(WEL.wel_scheduled_call_datetime)='".$argument['selected_date']."' OR 
			DATE(D3.d3_scheduled_call_datetime)='".$argument['selected_date']."' OR 
			DATE(D7.d7_scheduled_call_datetime)='".$argument['selected_date']."' OR  
			DATE(D15.d15_scheduled_call_datetime)='".$argument['selected_date']."' 
			)"; 
		}
		if(trim($argument['comp_name_id'])!=""){
			$subsql .="AND (T1.name LIKE '%".$argument['comp_name_id']."%' || T1.id LIKE '%".$argument['comp_name_id']."')"; 
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
			T1.id AS client_id,
			T1.account_type_id,
			T1.name AS company,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active AS is_account_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day,
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			WEL.wel_call_id,
			WEL.wel_scheduled_call_datetime,
			WEL.wel_actual_call_done_datetime,
			D3.d3_call_id,
			D3.d3_scheduled_call_datetime,
			D3.d3_actual_call_done_datetime,
			D7.d7_call_id,
			D7.d7_scheduled_call_datetime,
			D7.d7_actual_call_done_datetime,
			D15.d15_call_id,
			D15.d15_scheduled_call_datetime,
			D15.d15_actual_call_done_datetime,
			
			IF(wel_actual_call_done_datetime!='',1,0) AS wel_call_status,
			IF(d3_actual_call_done_datetime!='',1,0) AS d3_call_status,
			IF(d7_actual_call_done_datetime!='',1,0) AS d7_call_status,
			IF(d15_actual_call_done_datetime!='',1,0) AS d15_call_status,

			FLD.scheduled_call_datetime AS next_fllowup_date
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id
			INNER JOIN tbl_clients AS T1 ON T3.client_id=T1.id
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id

			LEFT JOIN
			(
				SELECT 
				id AS wel_call_id,
				service_id AS wel_sid,
				scheduled_call_datetime AS wel_scheduled_call_datetime,
				actual_call_done_datetime AS wel_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='1'
			) AS WEL ON T4.id=WEL.wel_sid

			LEFT JOIN
			(
				SELECT 
				id AS d3_call_id,
				service_id AS d3_sid,
				scheduled_call_datetime AS d3_scheduled_call_datetime,
				actual_call_done_datetime AS d3_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='2'
			) AS D3 ON T4.id=D3.d3_sid

			LEFT JOIN
			(
				SELECT 
				id AS d7_call_id,
				service_id AS d7_sid,
				scheduled_call_datetime AS d7_scheduled_call_datetime,
				actual_call_done_datetime AS d7_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='3'
			) AS D7 ON T4.id=D7.d7_sid

			LEFT JOIN
			(
				SELECT 
				id AS d15_call_id,
				service_id AS d15_sid,
				scheduled_call_datetime AS d15_scheduled_call_datetime,
				actual_call_done_datetime AS d15_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='4' AND actual_call_done_datetime IS NULL
				ORDER BY id DESC
			) AS D15 ON T4.id=D15.d15_sid

			LEFT JOIN
			(
				SELECT 
				service_id AS sid,
				scheduled_call_datetime
				FROM tbl_service_wise_service_call
				WHERE actual_call_done_datetime IS NULL
				ORDER BY id DESC
			) AS FLD ON T4.id=FLD.sid

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T4.end_date>(CURRENT_DATE+INTERVAL 60 DAY) AND T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql." 
			ORDER BY T1.id DESC, T4.id DESC ".$limitcond;

		$query = $this->fsas_db->query($sql,false );		
		if($query->num_rows()>0)
		{        
			//$result=$query->result_array();
		    
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
					MAX(last_login_datetime) AS last_login_date,
					DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS not_logged_day
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );


				if($query2){
					$user_row=$query2->row();
				}
				
				$result[]=array(
					'client_id'=>$client_info->client_id,
					'account_type_id'=>$client_info->account_type_id,
					'company'=>$client_info->company,
					'is_account_active'=>$client_info->is_account_active,
					'account_type'=>$client_info->account_type,
					'module_name'=>$client_info->module_name,
					'service_order_detail_id'=>$client_info->service_order_detail_id,
					'service_name'=>$client_info->service_name,
					'start_date_data'=>$client_info->start_date_data,
					'end_date_data'=>$client_info->end_date_data,
					'expiry_date_data'=>$client_info->expiry_date_data,
					'service_end_day'=>$client_info->service_end_day,
					'assign_to_name'=>$client_info->assign_to_name,
					'wel_call_status'=>$client_info->wel_call_status,
					'd3_call_status'=>$client_info->d3_call_status,
					'd7_call_status'=>$client_info->d7_call_status,
					'd15_call_status'=>$client_info->d15_call_status,
					'wel_call_id'=>$client_info->wel_call_id,
					'wel_actual_call_done_datetime'=>$client_info->wel_actual_call_done_datetime,
					'wel_scheduled_call_datetime'=>$client_info->wel_scheduled_call_datetime,
					'd3_call_id'=>$client_info->d3_call_id,
					'd3_actual_call_done_datetime'=>$client_info->d3_actual_call_done_datetime,
					'd3_scheduled_call_datetime'=>$client_info->d3_scheduled_call_datetime,
					'd7_call_id'=>$client_info->d7_call_id,
					'd7_actual_call_done_datetime'=>$client_info->d7_actual_call_done_datetime,
					'd7_scheduled_call_datetime'=>$client_info->d7_scheduled_call_datetime,
					'd15_call_id'=>$client_info->d15_call_id,
					'd15_actual_call_done_datetime'=>$client_info->d15_actual_call_done_datetime,
					'd15_scheduled_call_datetime'=>$client_info->d15_scheduled_call_datetime,
					'next_fllowup_date'=>$client_info->next_fllowup_date,
					'total_user_count'=>$client_info->total_user_count,
					'last_login_date'=>$user_row->last_login_date,
					'not_logged_day'=>$user_row->not_logged_day,
					'activity_name'=>$client_info->activity_name,
					'activity_sub_name'=>$client_info->activity_sub_name,
					'activity_status_type_id'=>$client_info->activity_status_type_id
				);

			}
		}
		return $result;


	}

	public function get_all_service_calls_list_count($argument=array())
	{
		$subsql='';
		
		
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
		if(trim($argument['call_type'])!=""){
			if($argument['call_type']==1)$subsql .=" AND wel_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==2)$subsql .=" AND d3_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==3)$subsql .=" AND d7_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==4)$subsql .=" AND d15_actual_call_done_datetime IS NULL ";	
		}
		if(trim($argument['selected_date'])!=""){
			$subsql .="AND (
			DATE(WEL.wel_scheduled_call_datetime)='".$argument['selected_date']."' OR 
			DATE(D3.d3_scheduled_call_datetime)='".$argument['selected_date']."' OR 
			DATE(D7.d7_scheduled_call_datetime)='".$argument['selected_date']."' OR  
			DATE(D15.d15_scheduled_call_datetime)='".$argument['selected_date']."' 
			)"; 
		}
		if(trim($argument['comp_name_id'])!=""){
			$subsql .="AND (T1.name LIKE '%".$argument['comp_name_id']."%' || T1.id LIKE '%".$argument['comp_name_id']."')"; 
		}


		$result=array();
		
			$sql="SELECT 
			T1.id AS client_id,
			T1.account_type_id,
			T1.name AS company,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active AS is_account_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day,
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			WEL.wel_call_id,
			WEL.wel_scheduled_call_datetime,
			WEL.wel_actual_call_done_datetime,
			D3.d3_call_id,
			D3.d3_scheduled_call_datetime,
			D3.d3_actual_call_done_datetime,
			D7.d7_call_id,
			D7.d7_scheduled_call_datetime,
			D7.d7_actual_call_done_datetime,
			D15.d15_call_id,
			D15.d15_scheduled_call_datetime,
			D15.d15_actual_call_done_datetime,
			
			IF(wel_actual_call_done_datetime!='',1,0) AS wel_call_status,
			IF(d3_actual_call_done_datetime!='',1,0) AS d3_call_status,
			IF(d7_actual_call_done_datetime!='',1,0) AS d7_call_status,
			IF(d15_actual_call_done_datetime!='',1,0) AS d15_call_status,

			FLD.scheduled_call_datetime AS next_fllowup_date
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id
			INNER JOIN tbl_clients AS T1 ON T3.client_id=T1.id
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id

			LEFT JOIN
			(
				SELECT 
				id AS wel_call_id,
				service_id AS wel_sid,
				scheduled_call_datetime AS wel_scheduled_call_datetime,
				actual_call_done_datetime AS wel_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='1'
			) AS WEL ON T4.id=WEL.wel_sid

			LEFT JOIN
			(
				SELECT 
				id AS d3_call_id,
				service_id AS d3_sid,
				scheduled_call_datetime AS d3_scheduled_call_datetime,
				actual_call_done_datetime AS d3_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='2'
			) AS D3 ON T4.id=D3.d3_sid

			LEFT JOIN
			(
				SELECT 
				id AS d7_call_id,
				service_id AS d7_sid,
				scheduled_call_datetime AS d7_scheduled_call_datetime,
				actual_call_done_datetime AS d7_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='3'
			) AS D7 ON T4.id=D7.d7_sid

			LEFT JOIN
			(
				SELECT 
				id AS d15_call_id,
				service_id AS d15_sid,
				scheduled_call_datetime AS d15_scheduled_call_datetime,
				actual_call_done_datetime AS d15_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='4' AND actual_call_done_datetime IS NULL
				ORDER BY id DESC
			) AS D15 ON T4.id=D15.d15_sid

			LEFT JOIN
			(
				SELECT 
				service_id AS sid,
				scheduled_call_datetime
				FROM tbl_service_wise_service_call
				WHERE actual_call_done_datetime IS NULL
				ORDER BY id DESC
			) AS FLD ON T4.id=FLD.sid

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T4.end_date>(CURRENT_DATE+INTERVAL 60 DAY) AND T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql." ORDER BY T1.id DESC ";	
		$query = $this->fsas_db->query($sql,false );		

		return $query->num_rows();
	}

	public function get_service_call_list($arg=array())
	{
		$subsql="";

		if(trim($arg['client_id'])!=""){
			$subsql.=" WHERE T1.client_id='".$arg['client_id']."' ";
		}
		if(trim($arg['service_id'])!=""){
			$subsql.=" WHERE T1.service_id='".$arg['service_id']."' ";
		}

		$sql="SELECT 
		T1.*,
		T2.name AS type_name,
		T3.name AS updated_by
		FROM tbl_service_wise_service_call AS T1
		LEFT JOIN tbl_service_call_type AS T2 ON T1.service_call_type_id=T2.id
		LEFT JOIN user AS T3 ON T1.call_by_user_id=T3.id
		".$subsql."
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

	public function get_all_welcome_calls_list($argument=array())
	{
		$subsql='';
		
		
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
		if(trim($argument['call_type'])!=""){
			if($argument['call_type']==1)$subsql .=" AND service_call_type_id='1' AND wel_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==2)$subsql .=" AND service_call_type_id='2' AND d3_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==3)$subsql .=" AND service_call_type_id='3' AND d7_actual_call_done_datetime IS NULL ";	
		} else {
			$subsql.=" AND service_call_type_id IN(1,2,3) ";
		}
		if(trim($argument['selected_date'])!=""){
			$subsql .=" AND DATE(T4.next_followup_date)='".$argument['selected_date']."' "; 
		}
		if(trim($argument['comp_name_id'])!=""){
			$subsql .=" AND (T1.name LIKE '%".$argument['comp_name_id']."%' || T1.id LIKE '%".$argument['comp_name_id']."')"; 
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
			T1.id AS client_id,
			T1.account_type_id,
			T1.name AS company,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active AS is_account_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day,
			T4.service_call_type_id,
			T4.next_followup_date,
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			WEL.wel_call_id,
			WEL.wel_scheduled_call_datetime,
			WEL.wel_actual_call_done_datetime,
			D3.d3_call_id,
			D3.d3_scheduled_call_datetime,
			D3.d3_actual_call_done_datetime,
			D7.d7_call_id,
			D7.d7_scheduled_call_datetime,
			D7.d7_actual_call_done_datetime,
			
			IF(wel_actual_call_done_datetime!='',1,0) AS wel_call_status,
			IF(d3_actual_call_done_datetime!='',1,0) AS d3_call_status,
			IF(d7_actual_call_done_datetime!='',1,0) AS d7_call_status
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id
			INNER JOIN tbl_clients AS T1 ON T3.client_id=T1.id
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id

			LEFT JOIN
			(
				SELECT 
				id AS wel_call_id,
				service_id AS wel_sid,
				scheduled_call_datetime AS wel_scheduled_call_datetime,
				actual_call_done_datetime AS wel_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='1'
			) AS WEL ON T4.id=WEL.wel_sid

			LEFT JOIN
			(
				SELECT 
				id AS d3_call_id,
				service_id AS d3_sid,
				scheduled_call_datetime AS d3_scheduled_call_datetime,
				actual_call_done_datetime AS d3_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='2'
			) AS D3 ON T4.id=D3.d3_sid

			LEFT JOIN
			(
				SELECT 
				id AS d7_call_id,
				service_id AS d7_sid,
				scheduled_call_datetime AS d7_scheduled_call_datetime,
				actual_call_done_datetime AS d7_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='3'
			) AS D7 ON T4.id=D7.d7_sid

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T4.end_date>(CURRENT_DATE+INTERVAL 30 DAY) AND T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql."
			GROUP BY T4.id
			ORDER BY T1.id DESC, T4.id DESC ".$limitcond;

		$query = $this->fsas_db->query($sql,false );		
		if($query->num_rows()>0)
		{        
			//$result=$query->result_array();
		    
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
					MAX(last_login_datetime) AS last_login_date,
					DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS not_logged_day
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );


				if($query2){
					$user_row=$query2->row();
				}
				
				$result[]=array(
					'client_id'=>$client_info->client_id,
					'account_type_id'=>$client_info->account_type_id,
					'company'=>$client_info->company,
					'is_account_active'=>$client_info->is_account_active,
					'account_type'=>$client_info->account_type,
					'module_name'=>$client_info->module_name,
					'service_order_detail_id'=>$client_info->service_order_detail_id,
					'service_name'=>$client_info->service_name,
					'start_date_data'=>$client_info->start_date_data,
					'end_date_data'=>$client_info->end_date_data,
					'expiry_date_data'=>$client_info->expiry_date_data,
					'service_end_day'=>$client_info->service_end_day,
					'assign_to_name'=>$client_info->assign_to_name,
					'wel_call_status'=>$client_info->wel_call_status,
					'd3_call_status'=>$client_info->d3_call_status,
					'd7_call_status'=>$client_info->d7_call_status,
					'wel_call_id'=>$client_info->wel_call_id,
					'wel_actual_call_done_datetime'=>$client_info->wel_actual_call_done_datetime,
					'wel_scheduled_call_datetime'=>$client_info->wel_scheduled_call_datetime,
					'd3_call_id'=>$client_info->d3_call_id,
					'd3_actual_call_done_datetime'=>$client_info->d3_actual_call_done_datetime,
					'd3_scheduled_call_datetime'=>$client_info->d3_scheduled_call_datetime,
					'd7_call_id'=>$client_info->d7_call_id,
					'd7_actual_call_done_datetime'=>$client_info->d7_actual_call_done_datetime,
					'd7_scheduled_call_datetime'=>$client_info->d7_scheduled_call_datetime,
					'service_call_type_id'=>$client_info->service_call_type_id,
					'next_followup_date'=>$client_info->next_followup_date,
					'total_user_count'=>$client_info->total_user_count,
					'last_login_date'=>$user_row->last_login_date,
					'not_logged_day'=>$user_row->not_logged_day,
					'activity_name'=>$client_info->activity_name,
					'activity_sub_name'=>$client_info->activity_sub_name,
					'activity_status_type_id'=>$client_info->activity_status_type_id
				);

			}
		}
		return $result;


	}

	public function get_all_welcome_calls_list_count($argument=array())
	{
		$subsql='';
		
		
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
		if(trim($argument['call_type'])!=""){
			if($argument['call_type']==1)$subsql .=" AND service_call_type_id='1' AND wel_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==2)$subsql .=" AND service_call_type_id='2' AND d3_actual_call_done_datetime IS NULL ";
			elseif($argument['call_type']==3)$subsql .=" AND service_call_type_id='3' AND d7_actual_call_done_datetime IS NULL ";	
		} else {
			$subsql.=" AND service_call_type_id IN(1,2,3) ";
		}
		if(trim($argument['selected_date'])!=""){
			$subsql .=" AND DATE(T4.next_followup_date)='".$argument['selected_date']."' "; 
		}
		if(trim($argument['comp_name_id'])!=""){
			$subsql .=" AND (T1.name LIKE '%".$argument['comp_name_id']."%' || T1.id LIKE '%".$argument['comp_name_id']."')"; 
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
			T1.id AS client_id,
			T1.account_type_id,
			T1.name AS company,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active AS is_account_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day,
			T4.next_followup_date,
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			WEL.wel_call_id,
			WEL.wel_scheduled_call_datetime,
			WEL.wel_actual_call_done_datetime,
			D3.d3_call_id,
			D3.d3_scheduled_call_datetime,
			D3.d3_actual_call_done_datetime,
			D7.d7_call_id,
			D7.d7_scheduled_call_datetime,
			D7.d7_actual_call_done_datetime,
			
			IF(wel_actual_call_done_datetime!='',1,0) AS wel_call_status,
			IF(d3_actual_call_done_datetime!='',1,0) AS d3_call_status,
			IF(d7_actual_call_done_datetime!='',1,0) AS d7_call_status
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id
			INNER JOIN tbl_clients AS T1 ON T3.client_id=T1.id
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id

			LEFT JOIN
			(
				SELECT 
				id AS wel_call_id,
				service_id AS wel_sid,
				scheduled_call_datetime AS wel_scheduled_call_datetime,
				actual_call_done_datetime AS wel_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='1'
			) AS WEL ON T4.id=WEL.wel_sid

			LEFT JOIN
			(
				SELECT 
				id AS d3_call_id,
				service_id AS d3_sid,
				scheduled_call_datetime AS d3_scheduled_call_datetime,
				actual_call_done_datetime AS d3_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='2'
			) AS D3 ON T4.id=D3.d3_sid

			LEFT JOIN
			(
				SELECT 
				id AS d7_call_id,
				service_id AS d7_sid,
				scheduled_call_datetime AS d7_scheduled_call_datetime,
				actual_call_done_datetime AS d7_actual_call_done_datetime
				FROM tbl_service_wise_service_call
				WHERE service_call_type_id='3'
			) AS D7 ON T4.id=D7.d7_sid

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T4.end_date>(CURRENT_DATE+INTERVAL 30 DAY) AND T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql." 
			GROUP BY T4.id
			ORDER BY T1.id DESC ";	
		$query = $this->fsas_db->query($sql,false );		

		return $query->num_rows();
	}

	public function get_my_pending_all_calls_list_BACKUP_23_11_22($argument=array())
	{
		$subsql='';
		$call_list_subsql='';
		$inactive_call_list_subsql='';
		$inactive_join_type="LEFT JOIN";
		$renewal_join_type="LEFT JOIN";
		$service_join_type="INNER JOIN";
		
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
		
		if(trim($argument['comp_name_id'])!=""){
			$subsql .="AND (T1.name LIKE '%".$argument['comp_name_id']."%' || T1.id LIKE '%".$argument['comp_name_id']."')"; 
		}

		if(trim($argument['call_type'])!="" && $argument['call_type']!=5){
			$call_list_subsql .=" AND CL.service_call_type_id='".$argument['call_type']."' ";	
		}

		if($argument['call_type']==5){
			$service_join_type="LEFT JOIN";
			$inactive_join_type="INNER JOIN";
		}
		if($argument['call_type']==6){
			$service_join_type="LEFT JOIN";
			$renewal_join_type="INNER JOIN";
		}

		if(trim($argument['selected_date'])!=""){
			$call_list_subsql .="AND DATE(CL.scheduled_call_datetime)='".$argument['selected_date']."' "; 
			$inactive_call_list_subsql ="AND DATE(ICL.scheduled_call_datetime)='".$argument['selected_date']."' ";
			$renewal_call_list_subsql ="AND DATE(RCL.scheduled_call_datetime)='".$argument['selected_date']."' ";
		} else {
			$call_list_subsql .="AND DATE(CL.scheduled_call_datetime)<=DATE(CURRENT_DATE) "; 
			$inactive_call_list_subsql ="AND DATE(ICL.scheduled_call_datetime)<=DATE(CURRENT_DATE) ";
			$renewal_call_list_subsql ="AND DATE(RCL.scheduled_call_datetime)<=DATE(CURRENT_DATE) ";
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
			T1.id AS client_id,
			T1.account_type_id,
			T1.name AS company,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active AS is_account_active,
			T1.activity_status_type_id,
			T2.name AS account_type,
			
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day, 
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			IF(FLD.actual_call_done_datetime!='',1,0) AS call_status,
			IF(RNCL.id!='',RNCL.id,IF(IACT.id!='',IACT.id,FLD.id)) AS call_id,
			IF(RNCL.id!='',RNCL.service_call_type_id,IF(IACT.id!='',IACT.service_call_type_id,FLD.service_call_type_id)) AS call_type_id,
			IF(RNCL.id!='',RNCL.name,IF(IACT.id!='',IACT.name,FLD.name)) AS call_type_name,
			IF(RNCL.id!='',RNCL.scheduled_call_datetime,IF(IACT.id!='',IACT.scheduled_call_datetime,FLD.scheduled_call_datetime)) AS scheduled_call_datetime,
			IF(RNCL.id!='',RNCL.scheduled_call_datetime,IF(IACT.id!='',IACT.scheduled_call_datetime,FLD.scheduled_call_datetime)) AS next_fllowup_date,
			IF(RNCL.id!='',RNCL.actual_call_done_datetime,IF(IACT.id!='',IACT.actual_call_done_datetime,FLD.actual_call_done_datetime)) AS actual_call_done_datetime
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id
			INNER JOIN tbl_clients AS T1 ON T3.client_id=T1.id
			INNER JOIN tbl_client_account_type AS T2 ON T1.account_type_id=T2.id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id

			".$service_join_type." 
			(
				SELECT 
				CL.id,
				CL.service_id AS sid,
				CL.scheduled_call_datetime,
				CL.actual_call_done_datetime,
				CL.service_call_type_id,
				CT.name
				
				FROM tbl_service_wise_service_call AS CL
				LEFT JOIN tbl_service_call_type AS CT ON CL.service_call_type_id=CT.id
				WHERE CL.actual_call_done_datetime IS NULL ".$call_list_subsql."
				ORDER BY CL.id DESC
			) AS FLD ON T4.id=FLD.sid

			".$inactive_join_type." 
			(
				SELECT 
				ICL.id,
				ICL.service_id AS sid,
				ICL.client_id AS cl_client_id,
				ICL.scheduled_call_datetime,
				ICL.actual_call_done_datetime,
				ICL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS ICL
				LEFT JOIN tbl_service_call_type AS DCT ON ICL.service_call_type_id=DCT.id
				WHERE ICL.actual_call_done_datetime IS NULL AND ICL.service_call_type_id='5' ".$inactive_call_list_subsql."
				ORDER BY ICL.id DESC
			) AS IACT ON T3.client_id=IACT.cl_client_id

			".$renewal_join_type." 
			(
				SELECT 
				RCL.id,
				RCL.service_id AS sid,
				RCL.scheduled_call_datetime,
				RCL.actual_call_done_datetime,
				RCL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS RCL
				LEFT JOIN tbl_service_call_type AS DCT ON RCL.service_call_type_id=DCT.id
				WHERE RCL.actual_call_done_datetime IS NULL AND RCL.service_call_type_id='6' ".$renewal_call_list_subsql."
				ORDER BY RCL.id DESC
			) AS RNCL ON T4.id=RNCL.sid

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql." 
			GROUP BY T4.id
			ORDER BY T1.id DESC ".$limitcond;

		$query = $this->fsas_db->query($sql,false );		
		if($query->num_rows()>0)
		{        
			//$result=$query->result_array();
		    
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
					MAX(last_login_datetime) AS last_login_date,
					DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS not_logged_day
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );


				if($query2){
					$user_row=$query2->row();
				}
				
				$result[]=array(
					'client_id'=>$client_info->client_id,
					'account_type_id'=>$client_info->account_type_id,
					'company'=>$client_info->company,
					'is_account_active'=>$client_info->is_account_active,
					'account_type'=>$client_info->account_type,
					'module_name'=>$client_info->module_name,
					'service_order_detail_id'=>$client_info->service_order_detail_id,
					'service_name'=>$client_info->service_name,
					'start_date_data'=>$client_info->start_date_data,
					'end_date_data'=>$client_info->end_date_data,
					'expiry_date_data'=>$client_info->expiry_date_data,
					'service_end_day'=>$client_info->service_end_day,
					'assign_to_name'=>$client_info->assign_to_name,
					'call_status'=>$client_info->call_status,
					'call_id'=>$client_info->call_id,
					'call_type_id'=>$client_info->call_type_id,
					'call_type_name'=>$client_info->call_type_name,
					'actual_call_done_datetime'=>$client_info->actual_call_done_datetime,
					'scheduled_call_datetime'=>$client_info->scheduled_call_datetime,
					'next_fllowup_date'=>$client_info->next_fllowup_date,
					'total_user_count'=>$client_info->total_user_count,
					'last_login_date'=>$user_row->last_login_date,
					'not_logged_day'=>$user_row->not_logged_day,
					'activity_name'=>$client_info->activity_name,
					'activity_sub_name'=>$client_info->activity_sub_name,
					'activity_status_type_id'=>$client_info->activity_status_type_id
				);

			}
		}
		return $result;


	}

	public function get_my_pending_all_calls_list($argument=array())
	{
		$subsql='';
		$call_list_subsql='';
		$inactive_call_list_subsql='';
		
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
		
		if(trim($argument['comp_name_id'])!=""){
			$subsql .="AND (T1.name LIKE '%".$argument['comp_name_id']."%' || T1.id LIKE '%".$argument['comp_name_id']."')"; 
		}

		if(trim($argument['call_type'])!=""){
			$call_list_subsql .=" AND ICL.service_call_type_id IN(".$argument['call_type'].") ";	
		} else {
			$call_list_subsql .=" AND ICL.service_call_type_id IN(4,5,6) ";
		}

		// if(trim($argument['selected_date'])!=""){
		// 	$call_list_subsql .="AND DATE(ICL.scheduled_call_datetime)='".$argument['selected_date']."' "; 
		// } else {
		// 	$call_list_subsql .="AND DATE(ICL.scheduled_call_datetime)<=DATE(CURRENT_DATE) "; 
		// }

		if(trim($argument['selected_date'])!=""){
			$subsql .="AND DATE(T1.next_followup_date)='".$argument['selected_date']."' "; 
		} else {
			$subsql .="AND DATE(T1.next_followup_date)<=DATE(CURRENT_DATE) "; 
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
			T1.id AS client_id,
			T1.account_type_id,
			T1.name AS company,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active AS is_account_active,
			T1.activity_status_type_id,
			T1.next_followup_date,
			T2.name AS account_type,
			
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day, 
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			IACT.id AS call_id,
			IACT.scheduled_call_datetime,
			IACT.actual_call_done_datetime,
			IACT.service_call_type_id AS call_type_id,
			IACT.name AS call_type_name,
			IF(IACT.actual_call_done_datetime!='',1,0) AS call_status 
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 

			LEFT JOIN tbl_service_order AS T3 ON T1.id=T3.client_id
			LEFT JOIN tbl_service_order_detail AS T4 ON T3.id=T4.service_order_id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id

			INNER JOIN 
			(
				SELECT 
				ICL.id,
				ICL.service_id AS sid,
				ICL.client_id AS cl_client_id,
				ICL.scheduled_call_datetime,
				ICL.actual_call_done_datetime,
				ICL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS ICL
				LEFT JOIN tbl_service_call_type AS DCT ON ICL.service_call_type_id=DCT.id
				WHERE ICL.actual_call_done_datetime IS NULL ".$call_list_subsql." 
				GROUP BY ICL.client_id
				ORDER BY ICL.id DESC
			) AS IACT ON T1.id=IACT.cl_client_id

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql." 
			GROUP BY T1.id
			ORDER BY T1.id DESC ".$limitcond;

		$query = $this->fsas_db->query($sql,false );		
		if($query->num_rows()>0)
		{        
			//$result=$query->result_array();
		    
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
					MAX(last_login_datetime) AS last_login_date,
					DATEDIFF(CURRENT_DATE, MAX(last_login_datetime)) AS not_logged_day
					FROM user ";
				$query2 = $this->client_db->query($sql2,false );


				if($query2){
					$user_row=$query2->row();
				}
				
				$result[]=array(
					'client_id'=>$client_info->client_id,
					'account_type_id'=>$client_info->account_type_id,
					'company'=>$client_info->company,
					'is_account_active'=>$client_info->is_account_active,
					'account_type'=>$client_info->account_type,
					'module_name'=>$client_info->module_name,
					'service_order_detail_id'=>$client_info->service_order_detail_id,
					'service_name'=>$client_info->service_name,
					'start_date_data'=>$client_info->start_date_data,
					'end_date_data'=>$client_info->end_date_data,
					'expiry_date_data'=>$client_info->expiry_date_data,
					'service_end_day'=>$client_info->service_end_day,
					'assign_to_name'=>$client_info->assign_to_name,
					'call_status'=>$client_info->call_status,
					'call_id'=>$client_info->call_id,
					'call_type_id'=>$client_info->call_type_id,
					'call_type_name'=>$client_info->call_type_name,
					'actual_call_done_datetime'=>$client_info->actual_call_done_datetime,
					'scheduled_call_datetime'=>$client_info->scheduled_call_datetime,
					'next_followup_date'=>$client_info->next_followup_date,
					'total_user_count'=>$client_info->total_user_count,
					'last_login_date'=>$user_row->last_login_date,
					'not_logged_day'=>$user_row->not_logged_day,
					'activity_name'=>$client_info->activity_name,
					'activity_sub_name'=>$client_info->activity_sub_name,
					'activity_status_type_id'=>$client_info->activity_status_type_id
				);

			}
		}
		return $result;


	}

	public function get_my_pending_all_calls_list_count($argument=array())
	{
		$subsql='';
		$call_list_subsql='';
		$inactive_call_list_subsql='';
		
		if($argument['user_type']!="Admin"){
			$subsql .=" AND T1.assigned_to_user_id IN(".$argument['assigned_user'].")";
		}
		
		if(trim($argument['comp_name_id'])!=""){
			$subsql .="AND (T1.name LIKE '%".$argument['comp_name_id']."%' || T1.id LIKE '%".$argument['comp_name_id']."')"; 
		}

		if(trim($argument['call_type'])!=""){
			$call_list_subsql .=" AND ICL.service_call_type_id IN(".$argument['call_type'].") ";	
		} else {
			$call_list_subsql .=" AND ICL.service_call_type_id IN(4,5,6) ";
		}

		// if(trim($argument['selected_date'])!=""){
		// 	$call_list_subsql .="AND DATE(ICL.scheduled_call_datetime)='".$argument['selected_date']."' "; 
		// } else {
		// 	$call_list_subsql .="AND DATE(ICL.scheduled_call_datetime)<=DATE(CURRENT_DATE) "; 
		// }

		if(trim($argument['selected_date'])!=""){
			$subsql .="AND DATE(T1.next_followup_date)='".$argument['selected_date']."' "; 
		} else {
			$subsql .="AND DATE(T1.next_followup_date)<=DATE(CURRENT_DATE) "; 
		}

		$result=array();
		
			$sql="SELECT 
			T1.id AS client_id,
			T1.account_type_id,
			T1.name AS company,
			T1.domain_name,
			T1.api_url,
			'".DB_USERNAME."' AS db_username,
			'".DB_PASSWORD."' AS db_password,
			T1.db_name,
			T1.is_active AS is_account_active,
			T1.activity_status_type_id,
			T1.next_followup_date,
			T2.name AS account_type,
			
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date AS start_date_data,
			T4.end_date AS end_date_data,
			T4.expiry_date AS expiry_date_data,
			T4.no_of_user AS total_user_count,
			DATEDIFF(T4.end_date,CURRENT_DATE) AS service_end_day, 
			T6.name AS assign_to_name,
			T8.name AS activity_name,
			T8.sub_name AS activity_sub_name,
			
			IACT.id AS call_id,
			IACT.scheduled_call_datetime,
			IACT.actual_call_done_datetime,
			IACT.service_call_type_id AS call_type_id,
			IACT.name AS call_type_name,
			IF(IACT.actual_call_done_datetime!='',1,0) AS call_status 
			
			FROM tbl_clients AS T1  
			INNER JOIN tbl_client_account_type AS T2 ON T2.id=T1.account_type_id 

			LEFT JOIN tbl_service_order AS T3 ON T1.id=T3.client_id
			LEFT JOIN tbl_service_order_detail AS T4 ON T3.id=T4.service_order_id

			LEFT JOIN user AS T6 ON T1.assigned_to_user_id=T6.id

			INNER JOIN 
			(
				SELECT 
				ICL.id,
				ICL.service_id AS sid,
				ICL.client_id AS cl_client_id,
				ICL.scheduled_call_datetime,
				ICL.actual_call_done_datetime,
				ICL.service_call_type_id,
				DCT.name
				
				FROM tbl_service_wise_service_call AS ICL
				LEFT JOIN tbl_service_call_type AS DCT ON ICL.service_call_type_id=DCT.id
				WHERE ICL.actual_call_done_datetime IS NULL ".$call_list_subsql." 
				GROUP BY ICL.client_id
				ORDER BY ICL.id DESC
			) AS IACT ON T1.id=IACT.cl_client_id

			LEFT JOIN tbl_client_activity_status_type AS T8 ON T1.activity_status_type_id=T8.id 

			WHERE T1.is_deleted='N' AND T1.is_active='Y' AND T4.is_active='Y' ".$subsql." 
			GROUP BY T1.id
			ORDER BY T1.id DESC ";
		$query = $this->fsas_db->query($sql,false );		

		return $query->num_rows();
	}

	public function get_service_call_type_list($ids="")
	{
		
		if(trim($ids)!=''){
			$subsql=" AND id IN(".$ids.")";
		}
		$result=array();
		$sql="SELECT *		
			FROM tbl_service_call_type
			WHERE is_active='Y' ".$subsql;	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->result_array();
		}
		return $result;
	}


	public function get_work_order_details($worder_id)
    {
		$result=array();
		$sql="SELECT 
			T1.*
			
			FROM tbl_clients_work_order AS T1
			WHERE T1.id ='".$worder_id."'
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->row();
		}
		return $result;

	}

	public function insert_client_active_inactive_log($data)
	{
		
		if($this->fsas_db->insert('tbl_client_active_inactive_log',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function get_client_wise_last_active_inactive_log_row($condition="")
    {
		if(trim($condition)!=''){
			$condition=" WHERE ".$condition;
		}
		$result=array();
		$sql="SELECT * 
			FROM tbl_client_active_inactive_log
			".$condition."
			ORDER BY id DESC
			LIMIT 1
			";	
		$query = $this->fsas_db->query($sql,false );
			
		if($query)
		{            
			$result=$query->row();
		}
		return $result;

	}



	public function create_manual_service_call_list()
	{
		$result=array();
		
			$sql="SELECT 
			T3.service_name AS module_name,
			T4.id AS service_order_detail_id,
			T4.display_name AS service_name,
			T4.start_date,
			T4.end_date,
			T4.expiry_date,
			T4.service_call_type_id
			
			FROM  tbl_service_order_detail AS T4 
			INNER JOIN tbl_service_order AS T3 ON T4.service_order_id=T3.id

			WHERE 
			
			T4.is_active='Y' AND T4.service_call_type_id IS NULL
			
			ORDER BY T4.end_date DESC ";

		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows()>0)
			{
				$result=$query->result_array();
			}
		}
		return $result;
	}



	public function get_demo_list($argument=array())
	{
		$subsql='';
		$result=array();

		if($argument['filter_demo_type']!=""){
			if($argument['filter_demo_type']=='A')
			{
				$subsql .=" ";
			}
			else
			{
				$subsql .=" AND T1.status = '".$argument['filter_demo_type']."'";
			}
			
		}

		if(trim($argument['filter_year'])!='' || trim($argument['filter_month'])!='')
		{
				
			if(trim($argument['filter_year'])!='' && trim($argument['filter_month'])=='')
			{
				$subsql .=" AND YEAR(T1.demo_date)='".$argument['filter_year']."'";
			} 
			elseif(trim($argument['filter_year'])!='' && trim($argument['filter_month'])!='')
			{
				$subsql .=" AND YEAR(T1.demo_date)='".$argument['filter_year']."' AND MONTH(T1.demo_date)='".$argument['filter_month']."' ";
			}
			elseif(trim($argument['filter_year'])=='' && trim($argument['filter_month'])!='')
			{
				$subsql .=" AND YEAR(T1.demo_date)=YEAR(CURRENT_DATE) AND MONTH(T1.demo_date)='".$argument['filter_month']."' ";
			}
		}

			$sql="SELECT *,GROUP_CONCAT(TABLE1.lead_generation_platform) LEADGENERATIONPLATFORM FROM
			(
			SELECT 
			T1.id,
			T1.lead_id,
			T1.company_name,
			T1.contact_person,
			T1.email_id,
			T1.mobile,
			T1.demo_date,
			T1.demo_time,
			T4.lead_generation_platform,
			T2.name AS SALESPERSONNAME,
			T3.name AS CITIESNAME,
			T1.status
			
			
			FROM  
			tbl_demo AS T1 
			INNER JOIN user AS T2 ON T2.id=T1.sales_person
			INNER JOIN cities AS T3 ON T3.id=T1.location
			INNER JOIN tbl_lead_generation_platforms_demo AS T4 ON FIND_IN_SET(T4.id,T1.lead_generation_platform)
			WHERE 	
			T1.is_active='Y' AND T1.is_deleted = 'N'  ".$subsql."  ORDER BY T1.created_at DESC 
			) TABLE1
            GROUP BY TABLE1.id";
			 	
		$query = $this->fsas_db->query($sql,false );
		//echo $last_query = $this->fsas_db->last_query();die();
		if($query){
			if($query->num_rows()>0)
			{
				$result=$query->result_array();
			}
		}
		return $result;
	}
	
	

	public function GetAllIndianCitiesList($ids='')
	{
		$result=array();
		if($ids)
		{
			$subsql .=" AND t1.id IN ($ids)";
		}	
		$sql="SELECT 
			t1.id,
			t1.name 
			FROM cities AS t1 
			INNER JOIN states as t2 ON t1.state_id=t2.id WHERE t2.country_id='101' $subsql
			ORDER BY t1.name";	
		//$query = $this->client_db->query($sql,false);        
        // echo $last_query = $this->client_db->last_query();die();
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows()>0)
			{
				$result=$query->result_array();
			}
		}
		return $result;
        
	}


	public function GetAllLeadGenerationList()
	{
		$result=array();
		
		 $sql="SELECT 
			t1.id,
			t1.lead_generation_platform 
			FROM tbl_lead_generation_platforms_demo AS t1 
			ORDER BY t1.id";	
		$query = $this->fsas_db->query($sql,false );
		if($query){
			if($query->num_rows()>0)
			{
				$result=$query->result_array();
			}
		}
		return $result;
        
	}
	


	public function insert_demo($data)
	{
		
		if($this->fsas_db->insert('tbl_demo',$data))
		{			
			return $this->fsas_db->insert_id();			
		}
		else
		{
			return false;
		}	
	}

	function CreateDemoActivityLog($data,$client_info=array())
	{    
		
		$this->fsas_db->insert('tbl_demo_activity_log',$data);    
		//echo $last_query = $this->fsas_db->last_query(); die(" SDFSDAF SDF");
		if($this->fsas_db->insert('tbl_demo_activity_log',$data))
   		{
			
           return $this->fsas_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}


	public function get_demo_done_details($demo_id,$lead_id)
	{
		$result=array();
		
				 $sql="SELECT 
						T1.id,
						T1.lead_id,
						T1.company_name,
						T1.contact_person,
						T1.email_id,
						T1.mobile,
						T1.demo_date,
						T1.demo_time,
						T1.lead_generation_platform,
						T1.sales_person,
						T1.location,
						T1.end_time,
						T1.done_prospect,
						T1.user_required,
						T1.quotation_sent,
						T1.next_followup_date,
						T1.cancelled_prospect,
						T1.need_followup,
						T1.lead_regretted,
						T1.status,
						T1.comment,
						T1.is_active,
						T1.is_deleted,
						T1.created_at,
						T1.updated_at,
						T1.created_by
						FROM  tbl_demo AS T1 
						WHERE T1.id='".$demo_id."' AND T1.lead_id='".$lead_id."' ";
						
						$query = $this->fsas_db->query($sql,false );		
						if($query->num_rows()>0)
						{            
							$result=$query->result_array();
						}
						return $result;


	}


	public function update_demo($data,$id)
	{
		$this->fsas_db->where('id',$id);
		//echo $last_query = $this->fsas_db->last_query(); die(" SDFSDAF SDF");
		if($this->fsas_db->update('tbl_demo',$data))
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