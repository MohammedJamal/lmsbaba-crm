<?php
class Meeting_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() 
	{
        parent::__construct();
		// $this->load->database();
		$this->is_note_reply_seen='Y';
		$this->note_arr=array();
		$this->user_arr=array();
		$this->class_name=$this->router->fetch_class();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
		$this->note_tree_arr=array();
    } 
	
	public function add($data,$client_info=array())
	{
		if($this->class_name=='rest_meeting')
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

		if($this->client_db->insert('meeting',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function addStatusLog($data,$client_info=array())
	{
		if($this->class_name=='rest_meeting')
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

		if($this->client_db->insert('meeting_status_log',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}	

	public function update($data,$id,$client_info=array())
	{
		if($this->class_name=='rest_meeting')
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
		if($this->client_db->update('meeting',$data))
		{			
			return true;			
		}
		else
		{
			return false;
		}	
	}

	function get_dashboard_count($argument=NULL,$client_info=array())
    {       
		if($this->class_name=='rest_meeting')
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
        $subsql = '';   
		$subsqlInner=''; 
        // ---------------------------------------
        // SEARCH VALUE 
        if($argument['assigned_user']!='')
		{
			$subsql.=" AND t1.user_id='".$argument['assigned_user']."'";
		}  
        // SEARCH VALUE
        // ---------------------------------------   
		// $sql="SELECT COUNT(t1.id) AS today_meeting_count 
		// FROM meeting AS t1 
		// WHERE date(t1.t1.meeting_schedule_start_datetime,)='".date("Y-m-d")."' $subsql 
		// GROUP BY t1.user_id";
        // $query = $this->client_db->query($sql,false);
        // $row=$query->row_array();

		return array(					
					'today_meeting_count'=>10,
					'upcoming_meeting_count'=>10,
					'cancelled_meeting_count'=>10,
					'unattended_meeting_count'=>10,
					'done_meeting_count'=>10
					);
    }

	
	public function GetList($argument,$client_info=array())
	{
		if($this->class_name=='rest_meeting')
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
		$subsql='';	
		$limit=$argument['limit'];
      	$start=$argument['start'];
		// ----------------------------------------
		// SEARCH
		if(isset($argument['user_id']))
		{
			if($argument['user_id']){
				$subsql .=" AND t1.user_id='".$argument['user_id']."'";
			}
		}
		if(isset($argument['status']))
		{
			
		}
		if(isset($argument['listing_type']))
		{
			// TM->Today's Meeting list,
			// UM->Upcoming Meeting List,
			// FM-> Meeting Done,
			// CM->Cancelled Meeting List,
			// UAM->Unattended Meeting List
			if(strtoupper($argument['listing_type'])=='TM')
			{
				$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)='".date("Y-m-d")."'";
			}
			else if(strtoupper($argument['listing_type'])=='UM')
			{
				$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)>'".date("Y-m-d")."'";
			}
			else if(strtoupper($argument['listing_type'])=='FM') //Finished(3)
			{
				$subsql .=" AND t1.status_id='3'";
			}
			else if(strtoupper($argument['listing_type'])=='CM') //Cancelled(4)
			{
				$subsql .=" AND t1.status_id='4'";
			}
			else if(strtoupper($argument['listing_type'])=='UAM') //Open(1)
			{
				$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)<'".date("Y-m-d")."' AND t1.status_id='1'";
			}
		}
		// SEARCH
		// ----------------------------------------
		$sql="SELECT t1.id,
				t1.user_id,
				t1.meeting_type,
				t1.meeting_agenda_type_id,
				t1.customer_id,
				t1.meeting_with_before_checkin_time,
				t1.lead_id,
				t1.meeting_schedule_start_datetime,
				t1.meeting_schedule_end_datetime,
				t1.meeting_Purpose,
				t1.meeting_venue,
				t1.meeting_venue_latitude,
				t1.meeting_venue_longitude,
				t1.meeting_url_type,
				t1.meeting_url,
				t1.status_id,
				t1.status,
				t1.cancellation_reason,
				t1.checkin_datetime,
				t1.checkout_datetime,
				t1.meeting_with_at_checkin_time,
				t1.self_visited_or_visited_with_colleagues,
				t1.visited_colleagues,
				t1.discussion_points,
				t1.created_at,
				t1.updated_at,
				t2.company_name AS cust_company_name,
				t2.contact_person AS cust_contact_person,
				t2.email AS cust_contact_person,
				t3.name AS meeting_agenda_type_name
			FROM meeting AS t1 	
			INNER JOIN customer AS t2 ON t1.customer_id=t2.id	
			INNER JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id
			WHERE 1=1 $subsql ORDER BY t1.created_at LIMIT $start,$limit";
		$result=$this->client_db->query($sql);
		return $result->result();
	}

	public function GetMeetingAgendaTypeRows($client_info=array())
	{
		if($this->class_name=='rest_meeting')
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
		$subsql='';		
		$sql="SELECT t1.id,t1.name
			FROM meeting_agenda_type AS t1 		
			WHERE t1.is_deleted='N'  ORDER BY t1.sort_order";
		$result=$this->client_db->query($sql);
		return $result->result();
	}

	public function GetListBydate($argument,$client_info=array())
	{
		if($this->class_name=='rest_meeting')
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
		$subsql='';	
		

		// ----------------------------------------
		// SEARCH
		if(isset($argument['user_id']))
		{
			if($argument['user_id']){
				$subsql .=" AND t1.user_id='".$argument['user_id']."'";
			}
		}
		if(isset($argument['date']))
		{
			$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)='".$argument['date']."'";
		}
		// SEARCH
		// ----------------------------------------
		$sql="SELECT t1.id,
				t1.user_id,
				t1.meeting_type,
				t1.meeting_agenda_type_id,
				t1.customer_id,
				t1.meeting_with_before_checkin_time,
				t1.lead_id,
				t1.meeting_schedule_start_datetime,
				t1.meeting_schedule_end_datetime,
				t1.meeting_Purpose,
				t1.meeting_venue,
				t1.meeting_venue_latitude,
				t1.meeting_venue_longitude,
				t1.meeting_url_type,
				t1.meeting_url,
				t1.status_id,
				t1.status,
				t1.cancellation_reason,
				t1.checkin_datetime,
				t1.checkout_datetime,
				t1.meeting_with_at_checkin_time,
				t1.self_visited_or_visited_with_colleagues,
				t1.visited_colleagues,
				t1.discussion_points,
				t1.created_at,
				t1.updated_at,
				t2.company_name AS cust_company_name,
				t2.contact_person AS cust_contact_person,
				t2.email AS cust_contact_person,
				t3.name AS meeting_agenda_type_name
			FROM meeting AS t1 	
			INNER JOIN customer AS t2 ON t1.customer_id=t2.id	
			INNER JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id
			WHERE 1=1 $subsql ORDER BY t1.created_at";
		$result=$this->client_db->query($sql);
		return $result->result();
	}

	public function GetListByUserForEventCalendar($user_id_str='')
	{		
		$subsql='';			
		// ----------------------------------------
		// SEARCH
		if($user_id_str!='')
		{
			$subsql .=" AND t1.user_id IN (".$user_id_str.")";
		}
		// SEARCH
		// ----------------------------------------
		$sql="SELECT t1.id,
				t1.user_id,
				t1.meeting_type,
				t1.meeting_agenda_type_id,
				t1.customer_id,
				t1.meeting_with_before_checkin_time,
				t1.lead_id,
				t1.meeting_schedule_start_datetime,
				t1.meeting_schedule_end_datetime,
				t1.meeting_Purpose,
				t1.meeting_venue,
				t1.meeting_venue_latitude,
				t1.meeting_venue_longitude,
				t1.meeting_url_type,
				t1.meeting_url,
				t1.status_id,
				t1.status,
				t1.cancellation_reason,
				t1.checkin_datetime,
				t1.checkout_datetime,
				t1.meeting_with_at_checkin_time,
				t1.self_visited_or_visited_with_colleagues,
				t1.visited_colleagues,
				t1.discussion_points,
				t1.created_at,
				t1.updated_at,
				t2.company_name AS cust_company_name,
				t2.contact_person AS cust_contact_person,
				t2.email AS cust_contact_person,
				t3.name AS meeting_agenda_type_name,
				GROUP_CONCAT(t4.name) AS meeting_assigned_to
			FROM meeting AS t1 	
			INNER JOIN customer AS t2 ON t1.customer_id=t2.id	
			INNER JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id
			INNER JOIN user AS t4 ON t4.id IN (t1.user_id)
			WHERE 1=1 $subsql GROUP BY t1.id ORDER BY t1.created_at";
		$query=$this->client_db->query($sql);
		$result=array();
		if($query->num_rows() > 0) 
        {
        	foreach($query->result() AS $row)
        	{
				if($row->status_id=='1'){
					$backgroundColorTmp='#008ef0';
				}
				else if($row->status_id=='2'){
					$backgroundColorTmp='#00bd68';
				}
				else if($row->status_id=='3'){
					$backgroundColorTmp='#00bd68';
				}
				else if($row->status_id=='4'){
					$backgroundColorTmp='#FF0000';
				}
				else{
					$backgroundColorTmp='#008ef0';
				}
				
				
				$result[]=array(
								'id'=>$row->id,
								'title'=>$row->meeting_with_before_checkin_time.' with '.$row->meeting_assigned_to,
								'start'=>$row->meeting_schedule_start_datetime,
								'type'=>(strtoupper($row->meeting_type))=='P'?'Visit':'Online',
								'status'=>strtolower($row->status),
								'comments'=>$row->meeting_Purpose,
								'backgroundColor'=>$backgroundColorTmp,
								);
			}
		}
		return $result;
	}

	public function GetRow($id='')
	{		
		$subsql='';			
		// ----------------------------------------
		// SEARCH
		if($id!='')
		{			
			$subsql .=" AND t1.id='".$id."'";			
		}
		// SEARCH
		// ----------------------------------------
		$sql="SELECT t1.id,
				t1.user_id,
				t1.meeting_type,
				t1.meeting_agenda_type_id,
				t1.customer_id,
				t1.meeting_with_before_checkin_time,
				t1.lead_id,
				t1.meeting_schedule_start_datetime,
				t1.meeting_schedule_end_datetime,
				t1.meeting_Purpose,
				t1.meeting_venue,
				t1.meeting_venue_latitude,
				t1.meeting_venue_longitude,
				t1.meeting_url_type,
				t1.meeting_url,
				t1.status_id,
				t1.status,
				t1.cancellation_reason,
				t1.checkin_datetime,
				t1.checkout_datetime,
				t1.meeting_with_at_checkin_time,
				t1.self_visited_or_visited_with_colleagues,
				t1.visited_colleagues,
				t1.discussion_points,
				t1.created_at,
				t1.updated_at,
				t2.company_name AS cust_company_name,
				t2.contact_person AS cust_contact_person,
				t2.email AS cust_contact_person,
				t3.name AS meeting_agenda_type_name,
				GROUP_CONCAT(t4.name) AS meeting_assigned_to
			FROM meeting AS t1 	
			INNER JOIN customer AS t2 ON t1.customer_id=t2.id	
			INNER JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id
			INNER JOIN user AS t4 ON t4.id IN (t1.user_id)
			WHERE 1=1 $subsql";
		$query=$this->client_db->query($sql);
		$result=array();
		if($query->num_rows() > 0) 
        {
        	$result=$query->row_array();
		}
		return $result;		
	}
}
?>