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
		if($argument['user_id_str']!='')
		{
			$subsql .=" AND (";
			$subsql .=" lead.assigned_user_id IN (".$argument['user_id_str'].")";
			if($argument['assigned_user']!='')
			{				
				$subsql .=" OR lead.assigned_observer='".$argument['assigned_user']."'";
				$subsql .=" OR meeting.user_id='".$argument['assigned_user']."'";
			}  
			$subsql .=")";
		} 
        
        // SEARCH VALUE
        // ---------------------------------------   
		$sql="SELECT meeting.id FROM meeting INNER JOIN lead ON lead.id=meeting.lead_id WHERE  date(meeting.meeting_schedule_start_datetime)='".date("Y-m-d")."' $subsql ";
        // echo $sql; die();
		$query = $this->client_db->query($sql,false);
		if($query){
			$today_meeting_count = $query->num_rows();
		}
		else{
			$today_meeting_count =0;
		}
        

		$sql2="SELECT meeting.id FROM meeting INNER JOIN lead ON lead.id=meeting.lead_id WHERE DATE(meeting.meeting_schedule_start_datetime)>'".date("Y-m-d")."' AND status_id='1' $subsql ";
        $query2 = $this->client_db->query($sql2,false);
		if($query2){
			$upcoming_meeting_count = $query2->num_rows();
		}
		else{
			$upcoming_meeting_count =0;
		}
        
		$sql3="SELECT meeting.id FROM meeting INNER JOIN lead ON lead.id=meeting.lead_id WHERE meeting.status_id='4' $subsql ";
        $query3 = $this->client_db->query($sql3,false);
		if($query3){
			$cancelled_meeting_count = $query3->num_rows();
		}
		else{
			$cancelled_meeting_count =0;
		}
        

		$sql4="SELECT meeting.id FROM meeting INNER JOIN lead ON lead.id=meeting.lead_id WHERE meeting.status_id='1' AND date(meeting.meeting_schedule_start_datetime)<'".date("Y-m-d")."' $subsql ";
        $query4 = $this->client_db->query($sql4,false);
		if($query4){
			$unattended_meeting_count = $query4->num_rows();
		}
		else{
			$unattended_meeting_count =0;
		}        

		$sql5="SELECT meeting.id FROM meeting INNER JOIN lead ON lead.id=meeting.lead_id WHERE meeting.status_id='3' $subsql ";
        $query5 = $this->client_db->query($sql5,false);
		if($query5){
			$done_meeting_count = $query5->num_rows();
		}
		else{
			$done_meeting_count =0;
		}
        


		$result = array(					
			'today_meeting_count'=>$today_meeting_count,
			'upcoming_meeting_count'=>$upcoming_meeting_count,
			'cancelled_meeting_count'=>$cancelled_meeting_count,
			'unattended_meeting_count'=>$unattended_meeting_count,
			'done_meeting_count'=>$done_meeting_count
			);

        return $result;
    }

	
	public function GetList($argument,$client_info=array())
	{
		// if($this->class_name=='rest_meeting')
		if(isset($client_info->db_name))
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
		if(isset($argument['user_id_str']) || isset($argument['user_id']))
		{			
			if($argument['user_id_str']){	
				$subsql .=" AND (";			
				$subsql .=" lead.assigned_user_id IN (".$argument['user_id_str'].")";
				if($argument['user_id']){
					$subsql .=" OR lead.assigned_observer='".$argument['user_id']."'";
					$subsql .=" OR t1.user_id='".$argument['user_id']."'";
				}
				$subsql .=")";
			}			
		}
		
		if(isset($argument['status']))
		{
			
		}

		if(isset($argument['listing_type']) && $argument['listing_type']!="")
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
				$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)>'".date("Y-m-d")."' AND t1.status_id='1'";
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

		if(isset($argument['meeting_date']) && $argument['meeting_date']!=""){
			$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)='".$argument['meeting_date']."'";
		}

		if(isset($argument['company_name']) && $argument['company_name']!=""){
			$subsql .=" AND t2.company_name='".$argument['company_name']."'";
		}

		if(isset($argument['contact_person']) && $argument['contact_person']!=""){
			$subsql .=" AND t2.contact_person='".$argument['contact_person']."'";
		}

		if(isset($argument['contact_mobile']) && $argument['contact_mobile']!=""){
				$subsql .=" AND t2.mobile='".$argument['contact_mobile']."'";
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
			LEFT JOIN customer AS t2 ON t1.customer_id=t2.id	
			LEFT JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id 
			INNER JOIN lead ON lead.id=t1.lead_id
			WHERE 1=1 $subsql ORDER BY t1.meeting_schedule_start_datetime ASC, t1.created_at LIMIT $start,$limit";		
			// echo $sql; die();
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function GetListCount($argument,$client_info=array())
	{
		// if($this->class_name=='rest_meeting')
		if(isset($client_info->db_name))
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
		if(isset($argument['user_id_str']) || isset($argument['user_id']))
		{			
			if($argument['user_id_str']){	
				$subsql .=" AND (";			
				$subsql .=" lead.assigned_user_id IN (".$argument['user_id_str'].")";
				if($argument['user_id']){
					$subsql .=" OR lead.assigned_observer='".$argument['user_id']."'";
					$subsql .=" OR t1.user_id='".$argument['user_id']."'";
				}
				$subsql .=")";
			}			
		}
		
		if(isset($argument['status']))
		{
			
		}

		if(isset($argument['listing_type']) && $argument['listing_type']!="")
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
				$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)>'".date("Y-m-d")."' AND t1.status_id='1'";
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

		if(isset($argument['meeting_date']) && $argument['meeting_date']!=""){
			$subsql .=" AND DATE(t1.meeting_schedule_start_datetime)='".$argument['meeting_date']."'";
		}

		if(isset($argument['company_name']) && $argument['company_name']!=""){
			$subsql .=" AND t2.company_name='".$argument['company_name']."'";
		}

		if(isset($argument['contact_person']) && $argument['contact_person']!=""){
			$subsql .=" AND t2.contact_person='".$argument['contact_person']."'";
		}

		if(isset($argument['contact_mobile']) && $argument['contact_mobile']!=""){
				$subsql .=" AND t2.mobile='".$argument['contact_mobile']."'";
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
			LEFT JOIN customer AS t2 ON t1.customer_id=t2.id	
			LEFT JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id 
			INNER JOIN lead ON lead.id=t1.lead_id
			WHERE 1=1 $subsql";
		$query=$this->client_db->query($sql);
		//return $query->num_rows();
		if($query){
			return $query->num_rows();
		}
		else{
			return 0;
		}
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
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
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
		if(isset($argument['tmp_u_ids_str']) || isset($argument['user_id']))
		{			
			if($argument['tmp_u_ids_str']){
				$subsql .=" AND (";				
				$subsql .=" lead.assigned_user_id IN (".$argument['tmp_u_ids_str'].")";
				if($argument['user_id']){
					$subsql .=" OR lead.assigned_observer='".$argument['user_id']."'";
					$subsql .=" OR t1.user_id='".$argument['user_id']."'";
				}
				$subsql .=")";
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
			INNER JOIN lead ON lead.id=t1.lead_id
			WHERE 1=1 $subsql ORDER BY t1.created_at";
		$result=$this->client_db->query($sql);
		//return $result->result();

		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function GetListByYearMonth($argument,$client_info=array())
	{
		// if($this->class_name=='rest_meeting')
		if(isset($client_info->db_name))
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
		if(isset($argument['tmp_u_ids_str']) || isset($argument['user_id']))
		{			
			if($argument['tmp_u_ids_str']){
				$subsql .=" AND (";				
				$subsql .=" lead.assigned_user_id IN (".$argument['tmp_u_ids_str'].")";
				if($argument['user_id']){
					$subsql .=" OR lead.assigned_observer='".$argument['user_id']."'";
					$subsql .=" OR t1.user_id='".$argument['user_id']."'";
				}
				$subsql .=")";
			}
		}

		// if(isset($argument['user_id']))
		// {
		// 	if($argument['user_id']){
		// 		$subsql .=" AND t1.user_id='".$argument['user_id']."'";
		// 	}
		// }
		if(isset($argument['year']))
		{
			$subsql .=" AND YEAR(t1.meeting_schedule_start_datetime)='".$argument['year']."'";
		}
		if(isset($argument['month']))
		{
			$subsql .=" AND MONTH(t1.meeting_schedule_start_datetime)='".$argument['month']."'";
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
			INNER JOIN lead ON lead.id=t1.lead_id
			WHERE 1=1 $subsql ORDER BY t1.created_at";
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result();
		}
		else{
			return (object)array();
		}
	}

	public function GetListByUserForEventCalendar($user_id_str='',$user_id='')
	{		
		$subsql='';			
		// ----------------------------------------
		// SEARCH
		if($user_id_str!='')
		{
			$subsql .=" AND (";
			$subsql .=" lead.assigned_user_id IN (".$user_id_str.")";
			if($user_id){
				$subsql .=" OR lead.assigned_observer='".$user_id."'";
				$subsql .=" OR t1.user_id='".$user_id."'";
			}
			$subsql .=" )";
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
			INNER JOIN lead ON lead.id=t1.lead_id
			WHERE 1=1 $subsql GROUP BY t1.id ORDER BY t1.created_at";
		$query=$this->client_db->query($sql);
		$result=array();
		if($query){
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
		}
		
		return $result;
	}

	public function GetRow($id='',$client_info=array())
	{		
		// if($this->class_name=='rest_meeting')
		if(isset($client_info->db_name))
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
				t4.name AS meeting_assigned_to,
				t5.name AS meeting_scheduled_by_user,
				lead.assigned_observer 
			FROM meeting AS t1 	
			INNER JOIN customer AS t2 ON t1.customer_id=t2.id	
			INNER JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id
			INNER JOIN user AS t4 ON t4.id=t1.user_id 
			LEFT JOIN user AS t5 ON t5.id=t1.meeting_scheduled_by_user_id 
			LEFT JOIN lead ON lead.id=t1.lead_id
			WHERE 1=1 $subsql";
			// echo $sql; die();
		$query=$this->client_db->query($sql);
		$result=array();
		if($query){
			if($query->num_rows() > 0) 
			{
				$result=$query->row_array();
			}
		}		
		return $result;		
	}


	public function GetDetails($argument=NULL,$client_info=array())
	{		
		// if($this->class_name=='rest_meeting')
		if(isset($client_info->db_name))
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
		if($argument['user_id']!='')
		{			
			$subsql .=" AND t1.user_id='".$argument['user_id']."'";			
		}
		if($argument['meeting_id']!='')
		{			
			$subsql .=" AND t1.id='".$argument['meeting_id']."'";			
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
				t2.id AS cust_company_id,
				t2.company_name AS cust_company_name,
				t2.assigned_user_id AS company_assigned_user_id,
				t2.source_id AS company_source_id,
				t2.contact_person AS cust_contact_person,
				t2.address AS cust_contact_address,
				t2.email AS cust_contact_email,
				t2.mobile AS cust_contact_number,
				t3.name AS meeting_agenda_type_name,
				GROUP_CONCAT(t4.name) AS meeting_assigned_to,
				t5.title AS lead_title
			FROM meeting AS t1 	
			INNER JOIN customer AS t2 ON t1.customer_id=t2.id	
			INNER JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id
			INNER JOIN user AS t4 ON t4.id IN (t1.user_id)
			INNER JOIN lead AS t5 ON t1.lead_id=t5.id
			WHERE 1=1 $subsql";
		$query=$this->client_db->query($sql);
		$result=array();
		if($query){
			if($query->num_rows() > 0) 
			{
				$result=$query->row_array();
			}
		}
		
		return $result;		
	}

	public function get_user_name_by_id($ids='',$client_info=array())
	{		
		$subsql='';			
		// ----------------------------------------
		// SEARCH
		if($ids!='')
		{			
			$subsql .=" AND t1.id IN (".$ids.")";			
		}
		// SEARCH
		// ----------------------------------------
		$sql="SELECT GROUP_CONCAT(t1.name) AS name_str FROM user AS t1 WHERE 1=1 $subsql";
		$query=$this->client_db->query($sql);
		$ret_str='';
		if($query){
			if($query->num_rows() > 0) 
			{
				$row=$query->row_array();
				$ret_str=$row['name_str'];
			}
		}		
		return $ret_str;		
	}

	public function addMeetingVisitedUser($data,$client_info=array())
	{
		// if($this->class_name=='rest_meeting')
		if(isset($client_info->db_name))
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

		if($this->client_db->insert('meeting_visited_with_user',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	// ====================================================================
	// MEETING REPORT
	public function GetMeetingReport($argument)
	{
		
		$subsql='';	
		$start=$argument['start'];
        $limit=$argument['limit'];
		$order_by_str = " ORDER BY t1.checkin_datetime DESC ";
        if($argument['filter_sort_by']!='')
        {			
			$filter_sort_by_arr=explode("-",$argument['filter_sort_by']);
			$field_name=$filter_sort_by_arr[0];
			$order_by=$filter_sort_by_arr[1];
			$order_by_str = " ORDER BY  $field_name ".$order_by;
        }
		// ----------------------------------------
		// SEARCH
		if(isset($argument['filter_by_user_id']) || isset($argument['user_id']))
		{			
			if($argument['filter_by_user_id']){		
				$subsql .=" AND (";		
				// $subsql .=" lead.assigned_user_id IN (".$argument['filter_by_user_id'].")";
				$subsql .=" t1.user_id IN (".$argument['filter_by_user_id'].")";
				// if($argument['user_id']){
				// 	$subsql .=" OR lead.assigned_observer='".$argument['user_id']."'";
				// 	$subsql .=" OR t1.user_id='".$argument['user_id']."'";
				// }
				$subsql .=" )";
			}
		}

		// if(isset($argument['user_id']))
		// {
		// 	if($argument['user_id']){
		// 		// $subsql .=" AND t1.user_id IN (".$argument['user_id'].")";
		// 		// $subsql .=" AND lead.assigned_user_id IN (".$argument['user_id'].")";
		// 		$subsql .=" OR lead.assigned_observer='".$argument['user_id']."'";
		// 		$subsql .=" OR t1.user_id='".$argument['user_id']."'";
		// 	}
		// }

		if(isset($argument['filter_by_lead_id']))
		{
			if($argument['filter_by_lead_id']){
				$subsql .=" AND t1.lead_id='".$argument['filter_by_lead_id']."'";
			}
		}
		
		if(isset($argument['filter_by_keyword']))
		{
			if($argument['filter_by_keyword']){
				$subsql .= " AND  (t1.lead_id = '".$argument['filter_by_keyword']."' || t2.email LIKE '%".$argument['filter_by_keyword']."%' || t2.mobile LIKE '%".$argument['filter_by_keyword']."%' || t2.company_name LIKE '%".$argument['filter_by_keyword']."%')";
				// $subsql .=" AND (t1.meeting_venue LIKE '%".$argument['filter_by_keyword']."%')";
			}
		}
		

		if(isset($argument['filter_by_status_id']))
		{
			if($argument['filter_by_status_id']){
				$subsql .=" AND t1.status_id ='".$argument['filter_by_status_id']."'";
			}
		}
		if(isset($argument['filter_by_meeting_type']))
		{
			if($argument['filter_by_meeting_type']){
				$subsql .=" AND t1.meeting_type ='".$argument['filter_by_meeting_type']."'";
			}
		}
		if(isset($argument['filter_by_meeting_agenda_type_id']))
		{
			if($argument['filter_by_meeting_agenda_type_id']){
				$subsql .=" AND t1.meeting_agenda_type_id ='".$argument['filter_by_meeting_agenda_type_id']."'";
			}
		}
		if(isset($argument['filter_by_self_visited_or_visited_with_colleagues']))
		{
			if($argument['filter_by_self_visited_or_visited_with_colleagues']){
				$subsql .=" AND t1.self_visited_or_visited_with_colleagues ='".$argument['filter_by_self_visited_or_visited_with_colleagues']."'";
			}
		}

		if(isset($argument['filter_by_start_date']) && isset($argument['filter_by_end_date']))
		{
			if($argument['filter_by_start_date']!='' && $argument['filter_by_end_date']!=''){
				$subsql .=" AND (DATE(t1.checkin_datetime) BETWEEN '".$argument['filter_by_start_date']."' AND '".$argument['filter_by_end_date']."')";
			}
		}
		// SEARCH
		// ----------------------------------------
		$sql="SELECT
			t1.id,
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
			t1.meeting_dispose_latitude,
			t1.meeting_dispose_longitude,
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
			t4.name AS user,
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,
			lststg.last_stage_id,
			stg.name AS last_stage
			FROM meeting AS t1
			INNER JOIN customer AS t2 ON t1.customer_id = t2.id
			INNER JOIN meeting_agenda_type AS t3 ON	t1.meeting_agenda_type_id = t3.id
			INNER JOIN user AS t4 ON t1.user_id = t4.id
			INNER JOIN lead ON lead.id = t1.lead_id
			LEFT JOIN countries ON countries.id = t2.country_id
			LEFT JOIN states ON states.id = t2.state
			LEFT JOIN cities ON cities.id = t2.city
			LEFT JOIN 
			(
				SELECT t1.lead_id,last_stage_id FROM 
				(
					SELECT t.*,if(@ld=t.lead_id,@sl:=@sl+1,@sl:=0) as stg,if(@sl=total_stage-1,stage_id,null) as last_stage_id,@ld:=t.lead_id
					from 
					(
						SELECT t1.id,t1.lead_id,t1.stage_id,t2.name,t2.sort,t3.total_stage
						FROM `lead_stage_log` as t1 
						JOIN (SELECT * FROM `opportunity_stage` ORDER BY sort) as t2 ON t1.stage_id = t2.id
						JOIN lead AS ld ON ld.id = t1.lead_id 
						JOIN (SELECT @sl:=0,@ld:=0) AS v ON 1=1
						JOIN (SELECT lead_id,count(stage_id) AS total_stage FROM lead_stage_log  GROUP BY lead_id) AS t3 
							ON t1.lead_id = t3.lead_id ORDER BY `lead_id`,t2.sort
					) AS t
				) AS t1 WHERE last_stage_id is not null			
			) lststg ON lststg.lead_id = lead.id
			LEFT JOIN opportunity_stage as stg ON lststg.last_stage_id = stg.id	
			WHERE 1=1 $subsql  $order_by_str LIMIT $start,$limit";
			
		$result=$this->client_db->query($sql);
		//return $result->result();
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}

	public function GetMeetingReportCount($argument)
	{
		
		$subsql='';	
		// ----------------------------------------
		// SEARCH
		if($argument['filter_by_user_id']){		
			$subsql .=" AND (";
			$subsql .=" t1.user_id IN (".$argument['filter_by_user_id'].")";
			$subsql .=" )";
		}
		// if(isset($argument['filter_by_user_id']) || isset($argument['user_id']))
		// {			
		// 	if($argument['filter_by_user_id']){		
		// 		$subsql .=" AND (";		
		// 		$subsql .=" lead.assigned_user_id IN (".$argument['filter_by_user_id'].")";
		// 		if($argument['user_id']){
		// 			$subsql .=" OR lead.assigned_observer='".$argument['user_id']."'";
		// 			$subsql .=" OR t1.user_id='".$argument['user_id']."'";
		// 		}
		// 		$subsql .=" )";
		// 	}
		// }

		

		if(isset($argument['filter_by_lead_id']))
		{
			if($argument['filter_by_lead_id']){
				$subsql .=" AND t1.lead_id='".$argument['filter_by_lead_id']."'";
			}
		}
		
		if(isset($argument['filter_by_keyword']))
		{
			if($argument['filter_by_keyword']){
				$subsql .= " AND  (t1.lead_id = '".$argument['filter_by_keyword']."' || t2.email LIKE '%".$argument['filter_by_keyword']."%' || t2.mobile LIKE '%".$argument['filter_by_keyword']."%' || t2.company_name LIKE '%".$argument['filter_by_keyword']."%')";
				// $subsql .=" AND (t1.meeting_venue LIKE '%".$argument['filter_by_keyword']."%')";
			}
		}
		

		if(isset($argument['filter_by_status_id']))
		{
			if($argument['filter_by_status_id']){
				$subsql .=" AND t1.status_id ='".$argument['filter_by_status_id']."'";
			}
		}
		if(isset($argument['filter_by_meeting_type']))
		{
			if($argument['filter_by_meeting_type']){
				$subsql .=" AND t1.meeting_type ='".$argument['filter_by_meeting_type']."'";
			}
		}
		if(isset($argument['filter_by_meeting_agenda_type_id']))
		{
			if($argument['filter_by_meeting_agenda_type_id']){
				$subsql .=" AND t1.meeting_agenda_type_id ='".$argument['filter_by_meeting_agenda_type_id']."'";
			}
		}
		if(isset($argument['filter_by_self_visited_or_visited_with_colleagues']))
		{
			if($argument['filter_by_self_visited_or_visited_with_colleagues']){
				$subsql .=" AND t1.self_visited_or_visited_with_colleagues ='".$argument['filter_by_self_visited_or_visited_with_colleagues']."'";
			}
		}

		if(isset($argument['filter_by_start_date']) && isset($argument['filter_by_end_date']))
		{
			if($argument['filter_by_start_date']!='' && $argument['filter_by_end_date']!=''){
				$subsql .=" AND (DATE(t1.checkin_datetime) BETWEEN '".$argument['filter_by_start_date']."' AND '".$argument['filter_by_end_date']."')";
			}
		}
		// SEARCH
		// ----------------------------------------
		$sql="SELECT t1.id
			FROM meeting AS t1 	
			INNER JOIN customer AS t2 ON t1.customer_id=t2.id	
			INNER JOIN meeting_agenda_type AS t3 ON t1.meeting_agenda_type_id=t3.id 
			INNER JOIN user AS t4 ON t1.user_id=t4.id 
			INNER JOIN lead ON lead.id = t1.lead_id
			WHERE 1=1 $subsql";
			// echo $sql; die();
		$query=$this->client_db->query($sql);
		if($query->num_rows() > 0) {
            return $query->num_rows();
        }
        else {
            return 0;
        }
	}

	public function GetMeetingStatus()
	{
		
		$subsql='';			
		$sql="SELECT t1.id,t1.name FROM meeting_status AS t1 
			WHERE t1.id!='5' AND t1.is_deleted='N' $subsql ORDER BY t1.sort_order";			
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	public function GetMeetingAgendaType()
	{
		
		$subsql='';			
		$sql="SELECT t1.id,t1.name FROM meeting_agenda_type AS t1 
			WHERE t1.is_deleted='N' $subsql ORDER BY t1.sort_order";			
		$result=$this->client_db->query($sql);		
		if($result){
			return $result->result_array();
		}
		else{
			return array();
		}
	}
	
	// MEETING REPORT
	// ====================================================================
	public function add_user_wise_geo_location_track($data,$client_info=array())
	{		
		if(isset($client_info->db_name))
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

		if($this->client_db->insert('tbl_user_wise_geo_location_track',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}	

	public function GetMeetingForLocationTrack($id='',$client_info=array())
	{
		if(isset($client_info->db_name))
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
		$result=array();
		if($id){
			$sql="SELECT 
				t1.user_id,
				t1.meeting_venue,
				t1.checkin_datetime,
				t1.checkout_datetime
			FROM meeting AS t1 	
			WHERE t1.id='".$id."'";		
			$query=$this->client_db->query($sql);			
			if($query){
				if($query->num_rows() > 0) 
				{
					$result=$query->row_array();
				}
			}		
		}		
		return $result;		
	}

}
?>