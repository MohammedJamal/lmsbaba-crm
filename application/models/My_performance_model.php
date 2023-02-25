<?php
class My_performance_model extends CI_Model
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

	public function GetActivityList($argument=array())
	{
		$subsql='';
		if($argument['user_id']){
			$subsql .=" AND t1.user_id IN (".$argument['user_id'].")";
		}
		if($argument['date_from']!='' && $argument['date_to']!=''){
			$subsql .=" AND (DATE(t1.action_at) BETWEEN '".$argument['date_from']."' AND '".$argument['date_to']."')";
		}
		$sql="SELECT 
				t1.id,
				t1.user_id,
				t1.action_at,
				DATE(t1.action_at) as action_at_date,
				t1.action_type,
				t1.ip_address,
				t2.name AS user_name,
				GROUP_CONCAT(t1.action_at ORDER BY t1.id) as action_at_str,
				GROUP_CONCAT(t1.action_type ORDER BY t1.id) as action_type_str
			FROM user_login_history AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			WHERE 1 $subsql GROUP BY t1.user_id,DATE(t1.action_at) ORDER BY DATE(t1.action_at) DESC";
		$result=$this->client_db->query($sql);
		return $result->result_array();		
	}

	public function GetUserWiseActivityList($argument=array())
	{
		$subsql='';
		if($argument['user_id']){
			$subsql .=" AND t1.user_id IN (".$argument['user_id'].")";
		}
		if($argument['date']!=''){
			$subsql .=" AND DATE(t1.action_at)='".$argument['date']."'";
		}
		// $sql="SELECT City FROM user_login_history WHERE action_type='LI'
		// 		UNION
		// 		SELECT City FROM user_login_history WHERE action_type='LO'
		// 		ORDER BY City";
		$sql="SELECT 
				t1.id,
				t1.user_id,
				t1.action_at,
				t1.action_type,
				t1.ip_address,
				t2.name AS user_name
			FROM user_login_history AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			WHERE 1 $subsql ORDER BY t1.id";
		$result=$this->client_db->query($sql);
		if($result){
			return $result->result_array();		
		}
		else{
			return array();
		}
		
	}

	public function GetLocationTracking($argument=array())
	{
		$subsql='';
		if($argument['user_id']){
			$subsql .=" AND t1.user_id='".$argument['user_id']."'";
		}
		if($argument['date_from']!='' && $argument['date_to']!=''){
			$subsql .=" AND (DATE(t1.datetime) BETWEEN '".$argument['date_from']."' AND '".$argument['date_to']."')";
		}
		$sql="SELECT 
				t1.id,
				t1.user_id,
				t1.latitude,
				t1.longitude,
				t1.address,
				t1.datetime,
				t1.system_datetime,
				t2.name AS user_name,
				GROUP_CONCAT(t1.latitude) AS latitudes,
				GROUP_CONCAT(t1.longitude) AS longitudes,
				GROUP_CONCAT(CONCAT(t1.latitude,'#',t1.longitude) ORDER BY t1.datetime) AS lat_long_srt,
				GROUP_CONCAT(t1.address ORDER BY t1.datetime SEPARATOR '$') AS address_srt,
				GROUP_CONCAT(DATE_FORMAT(t1.datetime,'%h:%i %p') ORDER BY t1.datetime) AS time_srt
			FROM tbl_user_wise_geo_location_track AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id
			WHERE 1 $subsql GROUP BY t1.user_id,DATE(t1.datetime) ORDER BY DATE(t1.datetime)";
		$result=$this->client_db->query($sql);
		return $result->result_array();		
	}

	public function target_achieved_update($data,$id)
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('kpi_setting_user_wise_set_target',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}
	
	public function kpi_user_wise_report_logs($user_id,$y_month='',$tmp_u_ids_str='')
	{
		$subsql="";
		if($user_id)
		{
			$subsql .=" AND t1.user_id='".$user_id."'";
		}
		if($y_month)
		{
			$subsql .=" AND DATE_FORMAT(`report_on`,'%Y-%m')='".$y_month."'";
		}
		if($tmp_u_ids_str)
		{
			$subsql .=" AND t1.user_id IN (".$tmp_u_ids_str.")";
		}
		$sql="SELECT 
			t1.id,
			t1.report_on,
			t1.user_id,
			t1.set_target_by,
			t1.target_threshold,
			t1.is_apply_pip,
			t1.is_apply_pli,
			t1.monthly_salary,
			t1.pli_in,
			t1.total_score_obtained,
			t1.grace_score,
			t1.self_comment,
			t1.self_file_name,
			t1.is_self_approved,
			t1.self_approved_datetime,
			t1.comment_on_approved,
			t1.is_approved,
			t1.approved_datetime,
			t1.approved_by,
			t1.created_at,
			t2.name AS user_name,
			t3.txt,
			t3.color_code,
			SUM(if(t4.achieved>=t4.min_target_threshold,t4.score_obtained,0)) AS total_score_obtained_after_min_threshold_apply 
			FROM kpi_user_wise_report_log AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id 
			LEFT JOIN kpi_performance_category_definitions AS t3 ON CEIL(t1.total_score_obtained+t1.grace_score) between t3.condition1 and t3.condition2 
			LEFT JOIN kpi_user_wise_report_log_detail AS t4 ON t1.id=t4.kpi_user_wise_report_log_id
			WHERE 1=1 $subsql GROUP BY t1.id ORDER BY t1.id DESC ";
        $query = $this->client_db->query($sql);
        return $query->result_array();
	}

	public function kpi_user_wise_report_logs_by_id($id)
	{
		$sql="SELECT 
			t1.id,
			t1.report_on,
			t1.user_id,
			t1.set_target_by,
			t1.target_threshold,
			t1.is_apply_pip,
			t1.is_apply_pli,
			t1.monthly_salary,
			t1.pli_in,
			t1.total_score_obtained,
			t1.grace_score,
			t1.self_comment,
			t1.self_file_name,
			t1.is_self_approved,
			t1.self_approved_datetime,
			t1.comment_on_approved,
			t1.is_approved,
			t1.approved_datetime,
			t1.approved_by,
			t1.created_at,
			t2.name AS self_approved_by_name,
			t3.name AS approved_by_name,
			SUM(if(t4.achieved>=t4.min_target_threshold,t4.score_obtained,0)) AS total_score_obtained_after_min_threshold_apply 			
			FROM kpi_user_wise_report_log AS t1 
			INNER JOIN user AS t2 ON t1.user_id=t2.id 
			LEFT JOIN user AS t3 ON t1.approved_by=t3.id 
			LEFT JOIN kpi_user_wise_report_log_detail AS t4 ON t1.id=t4.kpi_user_wise_report_log_id			
			WHERE t1.id='".$id."'";
        $query = $this->client_db->query($sql);
        return $query->row_array();
	}

	public function kpi_user_wise_report_log_details($kpi_user_wise_report_log_id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_user_wise_report_log_id,
			t1.kpi_id,
			t1.weighted_score,
			t1.target,
			t1.achieved,
			t1.score_obtained,
			t1.min_target_threshold,
			t2.name AS kpi_name,
			t2.is_system_generated AS is_kpi_system_generated 
			FROM kpi_user_wise_report_log_detail AS t1 
			INNER JOIN kpi AS t2 ON t1.kpi_id=t2.id 
			WHERE t1.kpi_user_wise_report_log_id='".$kpi_user_wise_report_log_id."' ORDER BY t1.id ";
        $query = $this->client_db->query($sql);
        return $query->result_array();
	}

	public function update_kpi_user_wise_report_log($data,$id)
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('kpi_user_wise_report_log',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function update_kpi_user_wise_report_log_detail($data,$id)
	{
		
		$this->client_db->where('id',$id);
		if($this->client_db->update('kpi_user_wise_report_log_detail',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	public function kpi_user_wise_report_log_detail_row($id)
	{
		$sql="SELECT 
			t1.id,
			t1.kpi_user_wise_report_log_id,
			t1.kpi_id,
			t1.weighted_score,
			t1.target,
			t1.achieved,
			t1.score_obtained,
			t1.min_target_threshold,
			t2.name AS kpi_name,
			t2.is_system_generated AS is_kpi_system_generated 
			FROM kpi_user_wise_report_log_detail AS t1 
			INNER JOIN kpi AS t2 ON t1.kpi_id=t2.id 
			WHERE t1.id='".$id."' ORDER BY t1.id ";
        $query = $this->client_db->query($sql);
        return $query->row_array();
	}

	public function get_kpi_setting_user_wise_in_cronjobs($client_info=array())
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

		$sql="SELECT 
			t1.id,
			t1.user_id,
			t1.set_target_by,
			t1.target_threshold,
			t1.target_threshold_for_x_consecutive_month,
			t1.is_apply_pip,
			t1.is_apply_pli,
			t1.monthly_salary,
			t1.pli_in 
			FROM kpi_setting_user_wise AS t1 ORDER BY 1";
        $query = $this->client_db->query($sql,false);        
        //echo $last_query = $this->client_db->last_query();  
		$return_arr=array();      
        if($query->num_rows() > 0) 
        {
        	foreach($query->result_array() AS $row)
        	{
				$detail_rows=array();
				$sql2="SELECT 
						id,
						kpi_id,
						weighted_score,
						target,
						achieved,
						min_target_threshold,
						created_at,
						updated_at
						FROM kpi_setting_user_wise_set_target 
						WHERE kpi_setting_user_wise_id=$row[id] ORDER BY 1";
				$query2 = $this->client_db->query($sql2);
				$details=$query2->result_array();
				$total_score_obtained=0;		
				if(count($details)) 
				{
					foreach($details AS $row2)
					{
						$weighted_score=$row2['weighted_score']; 
                        $target_achieved=($row2['achieved'])?$row2['achieved']:get_user_wise_kpi_count($row['user_id'],$row2['kpi_id'],'',$client_info);
                        $target=$row2['target'];
                        $score_obtained=($weighted_score*($target_achieved/$target*100)/100);
						$total_score_obtained=($total_score_obtained+$score_obtained);

						$detail_rows[]=array(
							'id'=>$row2['id'],
							'kpi_setting_user_wise_id'=>$row2['kpi_setting_user_wise_id'],
							'kpi_id'=>$row2['kpi_id'],
							'weighted_score'=>$row2['weighted_score'],
							'target'=>$row2['target'],
							'achieved'=>$target_achieved,
							'min_target_threshold'=>$row2['min_target_threshold'],
							'created_at'=>$row2['created_at'],
							'updated_at'=>$row2['updated_at']
							);
					}
				}
				
				
				$return_arr[]=array(
									'id'=>$row['id'],
									'user_id'=>$row['user_id'],
									'set_target_by'=>$row['set_target_by'],
									'target_threshold'=>$row['target_threshold'],
									'target_threshold_for_x_consecutive_month'=>$row['target_threshold_for_x_consecutive_month'],
									'is_apply_pip'=>$row['is_apply_pip'],
									'is_apply_pli'=>$row['is_apply_pli'],
									'monthly_salary'=>$row['monthly_salary'],
									'pli_in'=>$row['pli_in'],
									'pli_in'=>$row['pli_in'],
									'total_score_obtained'=>$total_score_obtained,
									'details'=>$detail_rows
									);
				
			}
		}
		return $return_arr;
	}

	public function add_kpi_user_wise_report_log($data,$client_info=array())
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
		if($this->client_db->insert('kpi_user_wise_report_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function add_kpi_user_wise_report_log_detail($data,$client_info=array())
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
		if($this->client_db->insert('kpi_user_wise_report_log_detail',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function create_kpi_log($data,$client_info=array())
	{
		if($this->class_name=='cronjobs' || $this->class_name=='rest_lead')
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
		if($this->client_db->insert('kpi_log',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	public function kpi_definitions()
	{
		$sql="SELECT 
			id,
			txt,
			condition1,
			condition2,
			color_code 
			FROM kpi_performance_category_definitions 
			WHERE 1=1 ORDER BY condition1 DESC";
        $query = $this->client_db->query($sql);
        return $query->result_array();
	}
}
?>